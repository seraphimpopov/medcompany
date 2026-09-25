<?php
/** Search-only adapter. No catalogue records or search index are modified. */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }

final class MedcompanySearch
{
    const VERSION = '20260925.1';
    const IBLOCK = 16;
    const LIMIT = 10000;
    private static $articlePropertyId;
    private static $directCache = [];
    private static $dictionary;

    public static function text($value, $length = 120)
    {
        return is_string($value) ? mb_substr(trim(preg_replace('/[\x00-\x1F\x7F]/u', ' ', $value)), 0, $length) : '';
    }

    public static function escape($value)
    {
        return htmlspecialcharsbx((string)$value);
    }

    public static function productWord($count)
    {
        $count = abs((int)$count) % 100;
        if ($count >= 11 && $count <= 19) { return 'товаров'; }
        $last = $count % 10;
        return $last === 1 ? 'товар' : ($last >= 2 && $last <= 4 ? 'товара' : 'товаров');
    }

    private static function normalized($value)
    {
        return trim(preg_replace('/[^\p{L}\p{N}]+/u', ' ', mb_strtolower(str_replace('ё', 'е', $value))));
    }

    public static function priceOnRequest($brand, $section)
    {
        require_once __DIR__.'/medcompany_price_request.php';
        return MedcompanyPriceRequest::hidePrice($brand);
    }

    public static function noCart($brand, $section)
    {
        require_once __DIR__.'/medcompany_price_request.php';
        return MedcompanyPriceRequest::noCart($brand, $section);
    }

    private static function cacheEngine()
    {
        return \Bitrix\Main\Data\Cache::getCacheEngineType() === 'cacheenginenone'
            ? new \Bitrix\Main\Data\Cache(new \Bitrix\Main\Data\CacheEngineFiles())
            : \Bitrix\Main\Data\Cache::createInstance();
    }

    private static function cacheContext()
    {
        global $USER;
        $groups = $USER->GetUserGroupArray(); sort($groups);
        return SITE_ID.'|'.(int)$USER->GetID().'|'.implode(',', $groups);
    }

    private static function saveCache($cache, $directory, $data)
    {
        global $CACHE_MANAGER;
        if ($cache->startDataCache()) {
            if (defined('BX_COMP_MANAGED_CACHE')) {
                $CACHE_MANAGER->StartTagCache($directory);
                $CACHE_MANAGER->RegisterTag('iblock_id_'.self::IBLOCK);
                $CACHE_MANAGER->EndTagCache();
            }
            $cache->endDataCache($data);
        }
    }

    private static function nameTokens($query)
    {
        return array_values(array_filter(array_slice(explode(' ', self::normalized($query)), 0, 8), function ($token) {
            return mb_strlen($token) >= 2;
        }));
    }

    private static function legacyDirectIds($query)
    {
        // Fallback for catalogues using a different property-storage format.
        $tokens = array_slice(explode(' ', self::normalized($query)), 0, 8);
        $nameFilter = ['LOGIC' => 'AND'];
        foreach ($tokens as $token) {
            if (mb_strlen($token) >= 2) $nameFilter[] = ['%NAME' => $token];
        }
        $match = ['LOGIC' => 'OR', ['%PROPERTY_CML2_ARTICLE' => $query]];
        if (count($nameFilter) > 1) $match[] = $nameFilter;
        $result = \CIBlockElement::GetList(['ID' => 'ASC'], ['IBLOCK_ID' => self::IBLOCK,
            'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'CHECK_PERMISSIONS' => 'Y', 'MIN_PERMISSION' => 'R', $match],
            false, ['nTopCount' => self::LIMIT + 1], ['ID']);
        $ids = [];
        while ($row = $result->Fetch()) $ids[] = (int)$row['ID'];
        return array_values(array_unique($ids));
    }

    private static function primeDirectIds($queries)
    {
        global $DB;
        $context = self::cacheContext();
        $directory = '/medcompany/search/'.self::VERSION.'/lookups';
        $pending = [];
        foreach (array_unique($queries) as $query) {
            $key = hash('sha256', $query.'|'.$context);
            if (array_key_exists($key, self::$directCache)) continue;
            $cache = self::cacheEngine();
            if ($cache->initCache(300, $key, $directory)) {
                self::$directCache[$key] = $cache->getVars();
            } else {
                $pending[] = ['query' => $query, 'key' => $key, 'cache' => $cache];
            }
        }
        if (!$pending) return;
        if (self::$articlePropertyId === null) {
            $property = \CIBlockProperty::GetList([], ['IBLOCK_ID' => self::IBLOCK, 'CODE' => 'CML2_ARTICLE'])->Fetch();
            self::$articlePropertyId = (int)($property['ID'] ?? 0);
        }
        if ((int)\CIBlock::GetArrayByID(self::IBLOCK, 'VERSION') !== 1) {
            foreach ($pending as $entry) {
                $ids = self::legacyDirectIds($entry['query']);
                self::$directCache[$entry['key']] = $ids;
                self::saveCache($entry['cache'], $directory, $ids);
            }
            return;
        }
        // Evaluate several layout/transliteration candidates in two scans, not one large joined scan per guess.
        // SQL LIKE retains the database's collation and matching semantics. Nothing from these scans is
        // returned or cached until the native API has checked permissions, active dates and workflow state.
        foreach (array_chunk($pending, 8) as $batch) {
            $nameConditions = []; $articleConditions = []; $candidates = []; $union = [];
            foreach ($batch as $number => $entry) {
                $tokens = self::nameTokens($entry['query']);
                $parts = [];
                foreach ($tokens as $token) $parts[] = "E.NAME LIKE '%".$DB->ForSql($token)."%'";
                $nameConditions[$number] = $parts ? '('.implode(' AND ', $parts).')' : '(1=0)';
                $articleConditions[$number] = "(P.VALUE LIKE '%".$DB->ForSql($entry['query'])."%')";
                $candidates[$number] = [];
            }
            $select = function ($conditions) {
                $fields = [];
                foreach ($conditions as $number => $condition) $fields[] = 'CASE WHEN '.$condition.' THEN 1 ELSE 0 END AS Q'.$number;
                return implode(', ', $fields);
            };
            $sql = ['SELECT E.ID, '.$select($nameConditions).' FROM b_iblock_element E WHERE E.IBLOCK_ID='.self::IBLOCK.
                ' AND ('.implode(' OR ', $nameConditions).')'];
            if (self::$articlePropertyId) {
                $sql[] = 'SELECT P.IBLOCK_ELEMENT_ID AS ID, '.$select($articleConditions).
                    ' FROM b_iblock_element_property P WHERE P.IBLOCK_PROPERTY_ID='.self::$articlePropertyId.
                    ' AND ('.implode(' OR ', $articleConditions).')';
            }
            foreach ($sql as $statement) {
                $rows = $DB->Query($statement);
                while ($row = $rows->Fetch()) {
                    $id = (int)$row['ID']; $union[$id] = $id;
                    foreach ($batch as $number => $entry) {
                        if ((int)$row['Q'.$number] === 1) $candidates[$number][$id] = $id;
                    }
                }
            }
            $allowed = [];
            if ($union) {
                foreach (array_chunk(array_values($union), 2000) as $chunk) {
                    $rows = \CIBlockElement::GetList([], ['IBLOCK_ID' => self::IBLOCK, 'ID' => $chunk,
                        'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'CHECK_PERMISSIONS' => 'Y', 'MIN_PERMISSION' => 'R'], false, false, ['ID']);
                    while ($row = $rows->Fetch()) $allowed[(int)$row['ID']] = true;
                }
            }
            foreach ($batch as $number => $entry) {
                $ids = array_values(array_intersect_key($candidates[$number], $allowed));
                sort($ids, SORT_NUMERIC); $ids = array_slice($ids, 0, self::LIMIT + 1);
                self::$directCache[$entry['key']] = $ids;
                self::saveCache($entry['cache'], $directory, $ids);
            }
        }
    }

    private static function directIds($query, $limit = self::LIMIT + 1)
    {
        self::primeDirectIds([$query]);
        return array_slice(self::$directCache[hash('sha256', $query.'|'.self::cacheContext())], 0, $limit);
    }

    private static function isProductCode($query)
    {
        return (bool)preg_match('~^(?=.*[0-9])[\p{L}0-9._/+\-]+$~u', $query);
    }

    private static function queryVariants($query)
    {
        // Never reinterpret codes or guess from one/two letters. Validate every variant against the catalogue.
        if (mb_strlen($query) < 3 || mb_strlen($query) > 80 || preg_match('/[0-9]/u', $query)) return [];
        $query = mb_strtolower($query);
        $variants = [];
        $add = function ($value, $strategy) use (&$variants, $query) {
            if ($value !== $query && self::normalized($value) !== '' && !isset($variants[$value])) $variants[$value] = $strategy;
        };
        $latinCount = preg_match_all('/[a-z]/u', $query);
        $russianCount = preg_match_all('/[а-яё]/u', $query);
        $lookalikes = ['a'=>'а','c'=>'с','e'=>'е','o'=>'о','p'=>'р','x'=>'х','y'=>'у'];
        if ($latinCount && $russianCount) {
            $add(strtr($query, $russianCount >= $latinCount ? $lookalikes : array_flip($lookalikes)), 'lookalikes');
        }
        $latinKeys = preg_split('//u', "qwertyuiop[]asdfghjkl;'zxcvbnm,.`", -1, PREG_SPLIT_NO_EMPTY);
        $russianKeys = preg_split('//u', 'йцукенгшщзхъфывапролджэячсмитьбюё', -1, PREG_SPLIT_NO_EMPTY);
        $keyboard = array_combine($latinKeys, $russianKeys);
        if ($latinCount && !$russianCount) $add(strtr($query, $keyboard), 'keyboard');
        if ($russianCount && !$latinCount) $add(strtr($query, array_flip($keyboard)), 'keyboard');
        if ($latinCount && !$russianCount) {
            $translit = ['shch'=>'щ','sch'=>'щ','yo'=>'ё','zh'=>'ж','kh'=>'х','ts'=>'ц','ch'=>'ч','sh'=>'ш','yu'=>'ю','ya'=>'я',
                'a'=>'а','b'=>'б','v'=>'в','g'=>'г','d'=>'д','e'=>'е','z'=>'з','i'=>'и','j'=>'й','k'=>'к','l'=>'л','m'=>'м',
                'n'=>'н','o'=>'о','p'=>'п','r'=>'р','s'=>'с','t'=>'т','u'=>'у','f'=>'ф','h'=>'х','c'=>'ц','y'=>'й','w'=>'в',"'"=>''];
            $add(strtr($query, $translit), 'transliteration');
            $translit['y'] = 'ы';
            $add(strtr($query, $translit), 'transliteration');
            $translit['c'] = 'к';
            $add(strtr($query, $translit), 'transliteration');
        }
        if ($russianCount && !$latinCount) {
            $add(strtr($query, ['щ'=>'shch','ш'=>'sh','ч'=>'ch','ц'=>'ts','ж'=>'zh','х'=>'kh','ю'=>'yu','я'=>'ya','ё'=>'yo',
                'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m',
                'н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','ы'=>'y','э'=>'e','ь'=>'','ъ'=>'']), 'transliteration');
        }
        return $variants;
    }

    private static function resolveQuery($query, $directIds, $allowWords = true)
    {
        $result = ['query' => $query, 'strategy' => 'original', 'ids' => $directIds];
        if ($directIds || self::isProductCode($query)) return $result;
        $variants = self::queryVariants($query);
        self::primeDirectIds(array_keys($variants));
        foreach ($variants as $variant => $strategy) {
            $ids = self::directIds($variant);
            if ($ids) return ['query' => $variant, 'strategy' => $strategy, 'ids' => $ids];
        }
        // Preserve a correctly typed brand/size in a mixed query such as "wtv Fuji".
        $words = preg_split('/\s+/u', $query, -1, PREG_SPLIT_NO_EMPTY);
        if ($allowWords && count($words) >= 2 && count($words) <= 4 && mb_strlen($query) <= 80) {
            $changed = false;
            self::primeDirectIds($words);
            foreach ($words as &$word) {
                if (mb_strlen($word) < 3 || preg_match('/[0-9]/', $word) || self::directIds($word, 1)) continue;
                $resolved = self::resolveQuery($word, [], false);
                if ($resolved['strategy'] !== 'original') { $word = $resolved['query']; $changed = true; }
            }
            unset($word);
            if ($changed) {
                $variant = implode(' ', $words);
                $ids = self::directIds($variant);
                if ($ids) return ['query' => $variant, 'strategy' => 'mixed_words', 'ids' => $ids];
            }
        }
        return $result;
    }

    private static function wordDictionary($correction)
    {
        if (self::$dictionary !== null) return self::$dictionary;
        $params = \CArturgolubevSmartsearch::prepareBaseParams($correction);
        $directory = '/medcompany/search/'.self::VERSION.'/dictionary';
        $key = hash('sha256', SITE_ID.'|'.$params['filter_map'].'|'.\CArturgolubevSmartsearch::CACHE_VERSION);
        $cache = self::cacheEngine();
        if ($cache->initCache(3600, $key, $directory)) {
            return self::$dictionary = $cache->getVars();
        }
        self::$dictionary = \CArturgolubevSmartsearch::getWordsListFromDb($params);
        self::saveCache($cache, $directory, self::$dictionary);
        return self::$dictionary;
    }

    private static function guessFromDictionary($query, $dictionary)
    {
        // Same enhanced keyboard check as the module, using the already prepared vocabulary.
        if (\Arturgolubev\Smartsearch\Unitools::getSetting('mode_guessplus') === 'Y') {
            $words = preg_split('/\s+/u', trim($query)); $corrected = [];
            foreach ($words as $word) {
                $variants = [];
                foreach ([['en', 'ru'], ['ru', 'en']] as $languages) {
                    $variant = \CSearchLanguage::ConvertKeyboardLayout($word, $languages[0], $languages[1]);
                    $variant = \CArturgolubevSmartsearch::checkReplaceRules($variant);
                    $variant = \CArturgolubevSmartsearch::prepareQuery($variant);
                    $variants[] = \CArturgolubevSmartsearch::clearExceptionsWords($variant);
                }
                $found = false;
                foreach ($variants as $variant) {
                    foreach ($dictionary as $catalogueWord => $transliteration) {
                        if (\Arturgolubev\Smartsearch\Encoding::exStripos($catalogueWord, $variant) !== false) {
                            $corrected[] = $variant; $found = true; break 2;
                        }
                    }
                }
                if (!$found) { $corrected = []; break; }
            }
            if ($corrected) return implode(' ', $corrected);
        }
        $language = \CSearchLanguage::GuessLanguage($query);
        return is_array($language) && $language['from'] !== $language['to']
            ? \CSearchLanguage::ConvertKeyboardLayout($query, $language['from'], $language['to']) : '';
    }

    private static function similarPhrases($query, $smart, $correction, $dictionary)
    {
        // Reuse the installed module's word matching and combination rules; only dictionary I/O changes.
        $params = \CArturgolubevSmartsearch::prepareBaseParams($correction);
        $queryWords = \CArturgolubevSmartsearch::prepBaseArray(explode(' ', \CArturgolubevSmartsearch::prepareQuery($query)), 1);
        $found = []; $variationCount = 0;
        foreach ($queryWords as $word => $translated) {
            $words = \CArturgolubevSmartsearch::getSimilarQueryWord($dictionary, ['cache' => $params['cache'],
                'word' => $word, 'trans' => $translated, 'type' => 'full', 'wordscount' => count($queryWords),
                'mode' => $smart->getOption('mode'), 'engine' => $params['engine'], 'filter_map' => $params['filter_map']]);
            if ($words) { $found[] = $words; $variationCount += ($variationCount + 1) * count($words); }
        }
        if (!$found) return [];
        $result = []; $matrix = \CArturgolubevSmartsearch::generateVariation($found);
        if ($variationCount < 200) {
            foreach (array_merge($matrix, \CArturgolubevSmartsearch::generateVariants($found)) as $words) {
                $result[count($words)][] = implode(' ', $words);
            }
        } else {
            if (count($matrix) < 200) {
                foreach ($matrix as $words) $result[count($words)][] = implode(' ', $words);
            }
            $result[1] = [];
            foreach ($found as $words) foreach ($words as $word) $result[1][] = $word;
        }
        foreach ($result as &$phrases) $phrases = array_values(array_unique($phrases));
        unset($phrases);
        return $result;
    }

    private static function indexIds($query)
    {
        $smart = new \Arturgolubev\Smartsearch\SearchComponent($query, 'page');
        // Keep the installed component as a compatibility fallback for other module/search-engine modes.
        if ($smart->getOption('mode') !== 'standart' || $smart->getOption('engine') === 'sphinx') return null;
        $smart->setItemIdFilterMode('N');
        $smart->setTitle();
        if ($smart->baseQuery !== '') {
            \Arturgolubev\Smartsearch\Tools::setSearchHistory($smart->baseQuery, 0);
            \CArturgolubevSmartsearch::checkRedirectRules(SITE_ID, $smart->baseQuery);
        }
        $search = new class extends \CSearchExt {
            public function Fetch()
            {
                // Only IDs are consumed. CSearch::Fetch would fetch/highlight every description,
                // while the page component also generates breadcrumbs and SEO titles per match.
                return \CDBResult::Fetch();
            }
        };
        $search->SetOptions(['ERROR_ON_EMPTY_STEM' => 1, 'NO_WORD_LOGIC' => 0]);
        $extra = \CSearchParameters::ConvertParamsToFilter(['arrFILTER' => ['iblock_catalogs'],
            'arrFILTER_iblock_catalogs' => [self::IBLOCK]], 'arrFILTER');
        $correction = ['type' => 'full', 'filter' => [['MODULE_ID' => 'iblock', 'PARAM1' => 'catalogs', 'PARAM2' => [self::IBLOCK]]]];
        $rows = [];
        $maximum = max(1, min(self::LIMIT, (int)\COption::GetOptionString('search', 'max_result_size', 50)));
        $run = function ($phrase, $bounded = false) use ($search, $extra, $smart, $maximum, &$rows) {
            $filter = ['SITE_ID' => SITE_ID, 'QUERY' => $phrase, 'TAGS' => false, 'CHECK_DATES' => 'Y'];
            if ($rows && !$smart->getOption('disable_item_id_filter')) $filter['!=ITEM_ID'] = array_values($rows);
            $search->Search($filter, ['CUSTOM_RANK' => 'DESC', 'TITLE_RANK' => 'ASC'], $extra);
            if ($search->errorno) return 0;
            $search->NavStart(self::LIMIT, false);
            $added = 0;
            while ($row = $search->Fetch()) {
                if (($bounded && count($rows) >= $maximum) || count($rows) >= self::LIMIT) break;
                if (isset($rows[$row['ID']])) continue;
                $rows[$row['ID']] = $row['ITEM_ID']; $added++;
            }
            return $added;
        };
        $run($smart->query);
        if ($rows || !$smart->query) return self::recordIndexSearch($smart->baseQuery, $rows);
        $dictionary = self::wordDictionary($correction);
        $guess = '';
        if (\Arturgolubev\Smartsearch\Unitools::getSetting('language_guess') !== 'N') {
            $guess = self::guessFromDictionary($smart->baseQuery, $dictionary);
            if ($guess) $run($guess, true);
        }
        $statisticQuery = $rows ? $guess : $smart->baseQuery;
        if (!$rows && $smart->getOption('use_fixes')) {
            $levels = self::similarPhrases($smart->query, $smart, $correction, $dictionary);
            if (!$levels && $smart->getOption('use_guessplus') && $guess) {
                $levels = self::similarPhrases($guess, $smart, $correction, $dictionary);
            }
            foreach ((array)$levels as $phrases) {
                foreach ($phrases as $phrase) {
                    if (\CArturgolubevSmartsearch::checkMatrixLineEmpty($phrase)) continue;
                    $added = $run($phrase, true);
                    \CArturgolubevSmartsearch::saveMatrixLineEmpty($phrase, $added);
                }
                if ($rows) break;
            }
        }
        return self::recordIndexSearch($statisticQuery, $rows);
    }

    private static function recordIndexSearch($query, $rows)
    {
        // Keep the module's existing search-phrase statistics, without preparing its visual template.
        if ($query && \COption::GetOptionString('search', 'stat_phrase') === 'Y') {
            $statistic = new \CSearchStatistic($query);
            $statistic->PhraseStat(count($rows), $rows ? 1 : 0);
        }
        return array_values($rows);
    }

    public static function find($query)
    {
        global $APPLICATION, $USER, $CACHE_MANAGER;
        $query = self::text($query);
        $empty = ['items' => [], 'sections' => [], 'limited' => false];
        if (mb_strlen(self::normalized($query)) < 2) { return $empty; }
        foreach (['iblock', 'catalog', 'search', 'arturgolubev.smartsearch'] as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) { throw new \RuntimeException('Search module unavailable'); }
        }
        $cache = self::cacheEngine();
        $directory = '/medcompany/search/'.self::VERSION;
        $key = hash('sha256', $query.'|'.self::cacheContext());
        if ($cache->initCache(300, $key, $directory)) { return $cache->getVars(); }

        $matchingQuery = trim(preg_replace('/\s+/u', ' ', str_replace(['–', '—', '−'], '-', $query)));
        $resolution = self::resolveQuery($matchingQuery, self::directIds($matchingQuery));
        $searchQuery = $resolution['query'];
        $directIds = $resolution['ids'];
        if ($searchQuery !== $matchingQuery) {
            // Share the canonical result instead of repeating hydration/ranking for every spelling.
            // Do not cache the copy again: the canonical result keeps its original expiry time.
            $data = self::find($searchQuery);
            $data['resolved_query'] = $searchQuery;
            $data['query_strategy'] = $resolution['strategy'];
            return $data;
        }
        $ids = [];

        // Preserve the module's typo/layout correction, but isolate it from unrelated request fields.
        // Prefix queries with actual matches need no expensive fuzzy pass; codes must not be guessed.
        if (!self::isProductCode($searchQuery) && !($directIds && mb_strlen(self::normalized($searchQuery)) <= 4)) {
        $savedRequest = $_REQUEST;
        $savedGet = $_GET;
        $_REQUEST = $_GET = ['q' => $searchQuery, 'how' => '', 'tags' => '', 'where' => ''];
        ob_start();
        try {
            $ids = self::indexIds($searchQuery);
            if ($ids === null) $ids = $APPLICATION->IncludeComponent('arturgolubev:search.page', 'empty', [
                'RESTART' => 'Y', 'NO_WORD_LOGIC' => 'Y', 'USE_LANGUAGE_GUESS' => 'Y',
                'CHECK_DATES' => 'Y', 'arrFILTER' => ['iblock_catalogs'],
                'arrFILTER_iblock_catalogs' => [self::IBLOCK], 'USE_TITLE_RANK' => 'Y',
                'DEFAULT_SORT' => 'rank', 'SHOW_WHERE' => 'N', 'SHOW_WHEN' => 'N',
                'PAGE_RESULT_COUNT' => self::LIMIT, 'DISPLAY_TOP_PAGER' => 'N',
                'DISPLAY_BOTTOM_PAGER' => 'N', 'CACHE_TYPE' => 'A', 'CACHE_TIME' => 300,
            ], false, ['HIDE_ICONS' => 'Y']);
        } finally {
            ob_end_clean();
            $_REQUEST = $savedRequest;
            $_GET = $savedGet;
        }
        }
        $ids = array_values(array_unique(array_map('intval', array_filter((array)$ids, function ($id) {
            return ctype_digit((string)$id) && (int)$id > 0;
        }))));

        // Supplement the full-text index with actual names and SKUs, reusing the resolution lookup.
        $directCount = count($directIds);
        $ids = array_merge($ids, $directIds);
        $ids = array_values(array_unique($ids));
        $limited = count($ids) > self::LIMIT || $directCount > self::LIMIT;
        $ids = array_slice($ids, 0, self::LIMIT);
        if (!$ids) { $data = $empty; } else {
            $positions = array_flip($ids);
            $items = [];
            $rows = \CIBlockElement::GetList([], ['IBLOCK_ID' => self::IBLOCK, 'ID' => $ids,
                'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'CHECK_PERMISSIONS' => 'Y', 'MIN_PERMISSION' => 'R'],
                false, false, ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE',
                    'DETAIL_PICTURE', 'CATALOG_GROUP_3', 'CATALOG_QUANTITY', 'CATALOG_AVAILABLE']);
            while ($row = $rows->Fetch()) {
                $id = (int)$row['ID'];
                if (isset($items[$id])) { continue; }
                $items[$id] = ['id' => $id, 'name' => $row['NAME'], 'section' => (int)$row['IBLOCK_SECTION_ID'],
                    'photo' => (int)($row['PREVIEW_PICTURE'] ?: $row['DETAIL_PICTURE']),
                    'stock' => (float)$row['CATALOG_QUANTITY'] > 0,
                    'available' => $row['CATALOG_AVAILABLE'] === 'Y',
                    'price' => isset($row['CATALOG_PRICE_3']) ? (float)$row['CATALOG_PRICE_3'] : null,
                    'currency' => $row['CATALOG_CURRENCY_3'] ?? 'RUB', 'position' => $positions[$id]];
            }
            $properties = [];
            \CIBlockElement::GetPropertyValuesArray($properties, self::IBLOCK, ['ID' => array_keys($items)],
                ['CODE' => ['CML2_ARTICLE', 'CML2_MANUFACTURER', 'SYSTEM_IMAGES', 'MORE_PHOTO']]);
            $queryNormal = self::normalized($searchQuery);
            $queryCompact = str_replace(' ', '', $queryNormal);
            $stems = array_keys(stemming($queryNormal, 'ru'));
            foreach ($items as $id => &$item) {
                $props = $properties[$id] ?? [];
                $item['article'] = (string)($props['CML2_ARTICLE']['VALUE'] ?? '');
                $item['brand'] = (string)($props['CML2_MANUFACTURER']['VALUE'] ?? '');
                $item['brand_id'] = (int)($props['CML2_MANUFACTURER']['VALUE_ENUM_ID'] ?? 0);
                if (!$item['photo']) {
                    foreach (['SYSTEM_IMAGES', 'MORE_PHOTO'] as $code) {
                        foreach ((array)($props[$code]['VALUE'] ?? []) as $file) {
                            if ((int)$file > 0) { $item['photo'] = (int)$file; break 2; }
                        }
                    }
                }
                if (self::priceOnRequest($item['brand'], $item['section'])) { $item['price'] = null; }
                $nameNormal = self::normalized($item['name']);
                $nameStems = array_keys(stemming($nameNormal, 'ru'));
                $matches = 0;
                foreach ($stems as $stem) {
                    foreach ($nameStems as $word) {
                        if ($word === $stem || (mb_strlen($stem) >= 3 && mb_strpos($word, $stem) === 0)) {
                            $matches++; break;
                        }
                    }
                }
                $score = $stems && $matches === count($stems) ? 200 : ($matches ? 40 : 0);
                if (mb_strpos($nameNormal, $queryNormal) !== false) { $score += 50; }
                if (mb_strpos($nameNormal, $queryNormal) === 0) { $score += 20; }
                $brandNormal = self::normalized($item['brand']);
                if ($queryNormal === $brandNormal || ($queryNormal !== '' && mb_strpos($brandNormal, $queryNormal.' ') === 0)) {
                    $score = max($score, 700);
                }
                $article = str_replace(' ', '', self::normalized($item['article']));
                if ($article !== '' && $article === $queryCompact) { $score = 1000; }
                elseif ($article !== '' && mb_strpos($article, $queryCompact) !== false) { $score += 300; }
                $item['score'] = $score;
                $item['tier'] = $score >= 1000 ? 1000 : ($score >= 700 ? 700 : ($score >= 300 ? 300 : ($score >= 200 ? 200 : ($score >= 40 ? 40 : 0))));
            }
            unset($item);
            uasort($items, function ($a, $b) {
                return ($b['tier'] <=> $a['tier']) ?: ($b['stock'] <=> $a['stock'])
                    ?: ((bool)$b['photo'] <=> (bool)$a['photo']) ?: ($b['score'] <=> $a['score'])
                    ?: ($a['position'] <=> $b['position']);
            });
            $sections = [];
            $result = \CIBlockSection::GetList(['LEFT_MARGIN' => 'ASC'], ['IBLOCK_ID' => self::IBLOCK, 'GLOBAL_ACTIVE' => 'Y'],
                false, ['ID', 'NAME', 'IBLOCK_SECTION_ID']);
            while ($row = $result->Fetch()) {
                $sections[(int)$row['ID']] = ['name' => $row['NAME'], 'parent' => (int)$row['IBLOCK_SECTION_ID']];
            }
            // Products may belong to several sections, not just their primary section.
            $memberships = [];
            $groupsResult = \CIBlockElement::GetElementGroups(array_keys($items), true, ['ID', 'IBLOCK_ELEMENT_ID']);
            while ($row = $groupsResult->Fetch()) {
                $sectionId = (int)$row['ID'];
                $productId = (int)$row['IBLOCK_ELEMENT_ID'];
                if (isset($sections[$sectionId], $items[$productId])) $memberships[$productId][$sectionId] = $sectionId;
            }
            foreach ($items as &$item) {
                $direct = $memberships[$item['id']] ?? [];
                if (isset($sections[$item['section']])) $direct[$item['section']] = $item['section'];
                $item['direct_sections'] = array_values($direct);
                $allSections = [];
                foreach ($direct as $section) {
                    for ($depth = 0; $section && isset($sections[$section]) && $depth < 20; $depth++) {
                        $allSections[$section] = $section;
                        $section = $sections[$section]['parent'];
                    }
                }
                $item['sections'] = array_values($allSections);
            }
            unset($item);
            $data = ['items' => $items, 'sections' => $sections, 'limited' => $limited];
        }
        $data['resolved_query'] = $searchQuery;
        $data['query_strategy'] = $resolution['strategy'];
        self::saveCache($cache, $directory, $data);
        return $data;
    }

    public static function filters($request)
    {
        $number = function ($key) use ($request) {
            $value = self::text($request[$key] ?? '', 20);
            return $value !== '' && is_numeric(str_replace(',', '.', $value))
                ? max(0, min(100000000, (float)str_replace(',', '.', $value))) : null;
        };
        $filters = ['section' => (int)self::text($request['section'] ?? '', 10),
            'brand' => (int)self::text($request['brand'] ?? '', 10),
            'min' => $number('min'), 'max' => $number('max'),
            'stock' => self::text($request['stock'] ?? '') === '1',
            'photo' => self::text($request['photo'] ?? '') === '1'];
        if ($filters['min'] !== null && $filters['max'] !== null && $filters['min'] > $filters['max']) {
            [$filters['min'], $filters['max']] = [$filters['max'], $filters['min']];
        }
        return $filters;
    }

    public static function matches($item, $filters, $except = '')
    {
        return ($except === 'section' || !$filters['section'] || in_array($filters['section'], $item['sections'], true))
            && ($except === 'brand' || !$filters['brand'] || $filters['brand'] === $item['brand_id'])
            && (!$filters['stock'] || $item['stock']) && (!$filters['photo'] || $item['photo'])
            && ($filters['min'] === null || ($item['price'] !== null && $item['price'] >= $filters['min']))
            && ($filters['max'] === null || ($item['price'] !== null && $item['price'] <= $filters['max']));
    }

    public static function image($file, $size = 360)
    {
        if (!$file) { return ''; }
        $image = \CFile::ResizeImageGet((int)$file, ['width' => $size, 'height' => $size], BX_RESIZE_IMAGE_PROPORTIONAL, true);
        return is_array($image) ? $image['src'] : '';
    }
}
