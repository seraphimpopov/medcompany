<?php
/**
 * Fill missing product descriptions in 1C-Bitrix.
 *
 * Safety guarantees:
 * - Does not update images, prices, names, sections or properties.
 * - Does not overwrite non-empty PREVIEW_TEXT or DETAIL_TEXT.
 * - Uses article first; exact product name is only a fallback.
 * - Ambiguous matches are skipped and written to CSV log.
 * - DRY_RUN is enabled by default.
 */

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;

set_time_limit(0);
ini_set('memory_limit', '1024M');

if (!Loader::includeModule('iblock')) {
    die("Cannot include iblock module\n");
}

$CONFIG = [
    // Strongly recommended: put the exact product/offers iblock ID here.
    // 0 = search all active iblocks. This is slower and may produce ambiguous matches.
    'IBLOCK_ID' => 0,

    // Common article property codes. Add your catalog property code if it differs.
    'ARTICLE_PROPERTY_CODES' => [
        'CML2_ARTICLE', 'ARTNUMBER', 'ARTICLE', 'VENDOR_CODE', 'VENDORCODE', 'SKU'
    ],

    'JSON_FILE' => __DIR__ . '/descriptions_all_products.json',
    'LOG_FILE' => __DIR__ . '/descriptions_import_log.csv',

    // Safety first: inspect the log before switching to false.
    'DRY_RUN' => true,

    // Fill each field only when that specific field is empty.
    'FILL_PREVIEW_TEXT' => true,
    'FILL_DETAIL_TEXT' => true,

    // Exact name fallback covers products without articles and helps resolve duplicate articles.
    'MATCH_BY_EXACT_NAME' => true,

    // Batch controls. Example: LIMIT=500, START_FROM=0; then 500, 1000, etc.
    'LIMIT' => 0,
    'START_FROM' => 0,

    // Avoid writing obviously broken source text.
    'MIN_DESCRIPTION_LENGTH' => 40,
];

function normalizeText(string $value): string
{
    $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = preg_replace('/\s+/u', ' ', $value);
    return trim((string)$value);
}

function normalizeArticle(string $value): string
{
    return normalizeText($value);
}

function normalizedCompare(string $value): string
{
    $value = normalizeText($value);
    return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
}

function isFieldEmpty($value): bool
{
    return normalizeText((string)$value) === '';
}

function textLength(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

function writeLogRow($fp, array $row): void
{
    fputcsv($fp, $row, ';');
}

function getIblockIds(int $configuredId): array
{
    if ($configuredId > 0) {
        return [$configuredId];
    }

    $ids = [];
    $res = CIBlock::GetList([], ['ACTIVE' => 'Y'], false);
    while ($iblock = $res->Fetch()) {
        $ids[] = (int)$iblock['ID'];
    }
    return $ids;
}

function getArticlePropertyCodes(int $iblockId, array $wantedCodes): array
{
    static $cache = [];
    $cacheKey = $iblockId . ':' . implode('|', $wantedCodes);
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    $wanted = array_flip(array_map('strtoupper', $wantedCodes));
    $found = [];
    $res = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId]);
    while ($prop = $res->Fetch()) {
        $code = strtoupper((string)$prop['CODE']);
        if (isset($wanted[$code])) {
            $found[] = (string)$prop['CODE'];
        }
    }
    $cache[$cacheKey] = $found;
    return $found;
}

function selectFields(): array
{
    return ['ID', 'IBLOCK_ID', 'NAME', 'XML_ID', 'CODE', 'PREVIEW_TEXT', 'DETAIL_TEXT'];
}

function findByArticle(string $article, array $config): array
{
    if ($article === '') {
        return [];
    }

    $matches = [];
    foreach (getIblockIds((int)$config['IBLOCK_ID']) as $iblockId) {
        $orFilter = ['LOGIC' => 'OR', ['=XML_ID' => $article], ['=CODE' => $article]];
        foreach (getArticlePropertyCodes($iblockId, $config['ARTICLE_PROPERTY_CODES']) as $code) {
            $orFilter[] = ['=PROPERTY_' . $code => $article];
        }

        $filter = [
            'IBLOCK_ID' => $iblockId,
            'CHECK_PERMISSIONS' => 'N',
            $orFilter,
        ];
        $res = CIBlockElement::GetList([], $filter, false, ['nTopCount' => 20], selectFields());
        while ($element = $res->Fetch()) {
            $matches[$element['IBLOCK_ID'] . ':' . $element['ID']] = $element;
        }
    }
    return array_values($matches);
}

function findByExactName(string $name, array $config): array
{
    if ($name === '') {
        return [];
    }

    $matches = [];
    foreach (getIblockIds((int)$config['IBLOCK_ID']) as $iblockId) {
        $filter = [
            'IBLOCK_ID' => $iblockId,
            'CHECK_PERMISSIONS' => 'N',
            '=NAME' => $name,
        ];
        $res = CIBlockElement::GetList([], $filter, false, ['nTopCount' => 20], selectFields());
        while ($element = $res->Fetch()) {
            // Double-check after whitespace/HTML normalization.
            if (normalizedCompare((string)$element['NAME']) === normalizedCompare($name)) {
                $matches[$element['IBLOCK_ID'] . ':' . $element['ID']] = $element;
            }
        }
    }
    return array_values($matches);
}

function resolveElement(array $product, array $config): array
{
    $article = normalizeArticle((string)($product['article'] ?? ''));
    $name = normalizeText((string)($product['name'] ?? ''));

    $articleMatches = findByArticle($article, $config);
    if (count($articleMatches) === 1) {
        return ['status' => 'ARTICLE', 'element' => $articleMatches[0], 'count' => 1];
    }

    // If an article matches several elements, exact name may safely disambiguate them.
    if (count($articleMatches) > 1 && $name !== '') {
        $sameName = array_values(array_filter($articleMatches, static function (array $element) use ($name): bool {
            return normalizedCompare((string)$element['NAME']) === normalizedCompare($name);
        }));
        if (count($sameName) === 1) {
            return ['status' => 'ARTICLE_AND_NAME', 'element' => $sameName[0], 'count' => 1];
        }
    }

    if (!empty($config['MATCH_BY_EXACT_NAME']) && $name !== '') {
        $nameMatches = findByExactName($name, $config);
        if (count($nameMatches) === 1) {
            return ['status' => 'EXACT_NAME', 'element' => $nameMatches[0], 'count' => 1];
        }
        if (count($nameMatches) > 1) {
            return ['status' => 'AMBIGUOUS_NAME', 'element' => null, 'count' => count($nameMatches)];
        }
    }

    if (count($articleMatches) > 1) {
        return ['status' => 'AMBIGUOUS_ARTICLE', 'element' => null, 'count' => count($articleMatches)];
    }
    return ['status' => 'NOT_FOUND', 'element' => null, 'count' => 0];
}

if (!file_exists($CONFIG['JSON_FILE'])) {
    die("JSON file not found: {$CONFIG['JSON_FILE']}\n");
}

$data = json_decode((string)file_get_contents($CONFIG['JSON_FILE']), true);
if (!is_array($data) || empty($data['products']) || !is_array($data['products'])) {
    die("Bad JSON or no products\n");
}

$lockPath = __DIR__ . '/descriptions_import.lock';
$lockFp = fopen($lockPath, 'c');
if (!$lockFp || !flock($lockFp, LOCK_EX | LOCK_NB)) {
    die("Another description import is already running\n");
}

$logFp = fopen($CONFIG['LOG_FILE'], 'w');
fwrite($logFp, "\xEF\xBB\xBF");
writeLogRow($logFp, [
    'date', 'dry_run', 'source_index', 'article', 'source_name', 'match_method',
    'status', 'element_id', 'iblock_id', 'bitrix_name', 'preview_action',
    'detail_action', 'description_source', 'message'
]);

$processed = 0;
$wouldUpdate = 0;
$updated = 0;
$alreadyFilled = 0;
$notFound = 0;
$ambiguous = 0;
$invalid = 0;
$errors = 0;

foreach ($data['products'] as $index => $product) {
    if ($index < (int)$CONFIG['START_FROM']) {
        continue;
    }
    if ((int)$CONFIG['LIMIT'] > 0 && $processed >= (int)$CONFIG['LIMIT']) {
        break;
    }
    $processed++;

    $article = normalizeArticle((string)($product['article'] ?? ''));
    $sourceName = normalizeText((string)($product['name'] ?? ''));
    $description = trim((string)($product['description'] ?? ''));
    $descriptionSource = (string)($product['description_source'] ?? '');
    $sourceIndex = (string)($product['source_index'] ?? $index);

    if (textLength(normalizeText($description)) < (int)$CONFIG['MIN_DESCRIPTION_LENGTH']) {
        $invalid++;
        writeLogRow($logFp, [date('c'), $CONFIG['DRY_RUN'] ? 'Y' : 'N', $sourceIndex, $article,
            $sourceName, '', 'INVALID_DESCRIPTION', '', '', '', '', '', $descriptionSource,
            'description is too short']);
        continue;
    }

    $resolved = resolveElement($product, $CONFIG);
    if (!$resolved['element']) {
        if (strpos($resolved['status'], 'AMBIGUOUS') === 0) {
            $ambiguous++;
        } else {
            $notFound++;
        }
        writeLogRow($logFp, [date('c'), $CONFIG['DRY_RUN'] ? 'Y' : 'N', $sourceIndex, $article,
            $sourceName, $resolved['status'], $resolved['status'], '', '', '', '', '',
            $descriptionSource, 'matches=' . (int)$resolved['count']]);
        continue;
    }

    $element = $resolved['element'];
    $previewEmpty = isFieldEmpty($element['PREVIEW_TEXT'] ?? '');
    $detailEmpty = isFieldEmpty($element['DETAIL_TEXT'] ?? '');

    $fields = [];
    $previewAction = 'DISABLED';
    $detailAction = 'DISABLED';

    if (!empty($CONFIG['FILL_PREVIEW_TEXT'])) {
        if ($previewEmpty) {
            $fields['PREVIEW_TEXT'] = $description;
            $fields['PREVIEW_TEXT_TYPE'] = 'text';
            $previewAction = 'SET';
        } else {
            $previewAction = 'KEEP_EXISTING';
        }
    }

    if (!empty($CONFIG['FILL_DETAIL_TEXT'])) {
        if ($detailEmpty) {
            $fields['DETAIL_TEXT'] = $description;
            $fields['DETAIL_TEXT_TYPE'] = 'text';
            $detailAction = 'SET';
        } else {
            $detailAction = 'KEEP_EXISTING';
        }
    }

    if (empty($fields)) {
        $alreadyFilled++;
        writeLogRow($logFp, [date('c'), $CONFIG['DRY_RUN'] ? 'Y' : 'N', $sourceIndex, $article,
            $sourceName, $resolved['status'], 'ALREADY_FILLED', $element['ID'], $element['IBLOCK_ID'],
            $element['NAME'], $previewAction, $detailAction, $descriptionSource, 'nothing changed']);
        continue;
    }

    if (!empty($CONFIG['DRY_RUN'])) {
        $wouldUpdate++;
        writeLogRow($logFp, [date('c'), 'Y', $sourceIndex, $article, $sourceName,
            $resolved['status'], 'DRY_RUN_OK', $element['ID'], $element['IBLOCK_ID'], $element['NAME'],
            $previewAction, $detailAction, $descriptionSource, 'would fill empty fields']);
        continue;
    }

    $updater = new CIBlockElement();
    if ($updater->Update((int)$element['ID'], $fields, false, true, true)) {
        $updated++;
        writeLogRow($logFp, [date('c'), 'N', $sourceIndex, $article, $sourceName,
            $resolved['status'], 'UPDATED', $element['ID'], $element['IBLOCK_ID'], $element['NAME'],
            $previewAction, $detailAction, $descriptionSource, '']);
    } else {
        $errors++;
        writeLogRow($logFp, [date('c'), 'N', $sourceIndex, $article, $sourceName,
            $resolved['status'], 'ERROR', $element['ID'], $element['IBLOCK_ID'], $element['NAME'],
            $previewAction, $detailAction, $descriptionSource, $updater->LAST_ERROR]);
    }
}

fclose($logFp);
flock($lockFp, LOCK_UN);
fclose($lockFp);
@unlink($lockPath);

echo "Done\n";
echo "processed={$processed}\n";
echo "would_update={$wouldUpdate}\n";
echo "updated={$updated}\n";
echo "already_filled={$alreadyFilled}\n";
echo "not_found={$notFound}\n";
echo "ambiguous={$ambiguous}\n";
echo "invalid={$invalid}\n";
echo "errors={$errors}\n";
echo "dry_run=" . ($CONFIG['DRY_RUN'] ? 'Y' : 'N') . "\n";
echo "log={$CONFIG['LOG_FILE']}\n";
?>
