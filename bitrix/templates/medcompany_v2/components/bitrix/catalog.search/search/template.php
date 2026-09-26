<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/medcompany_search.php';
$this->setFrameMode(false);
$APPLICATION->SetAdditionalCSS('/local/assets/medcompany-search.css?v=20260905.4');
$query = MedcompanySearch::text($_GET['q'] ?? '');
$filters = MedcompanySearch::filters($_GET);
$sort = MedcompanySearch::text($_GET['sort'] ?? 'relevance', 20);
$sorts = ['relevance' => 'По релевантности', 'price_asc' => 'Сначала дешевле', 'price_desc' => 'Сначала дороже', 'name' => 'По названию А–Я'];
if (!isset($sorts[$sort])) { $sort = 'relevance'; }
$pageSize = (int)MedcompanySearch::text($_GET['count'] ?? '24', 2);
if (!in_array($pageSize, [24, 48], true)) { $pageSize = 24; }
$error = false;
try { $data = MedcompanySearch::find($query); }
catch (\Throwable $exception) {
    AddMessage2Log($exception->getMessage(), 'medcompany.search');
    $error = true;
    $data = ['items' => [], 'sections' => [], 'limited' => false];
}
$items = []; $brands = []; $sections = []; $sectionDirect = []; $sectionTotal = 0;
// price bounds for the filter slider: every other filter applies, the price one does not
$priceFilters = $filters; $priceFilters['min'] = null; $priceFilters['max'] = null; $priceMin = null; $priceMax = null;
foreach ($data['items'] as $id => $item) {
    if (MedcompanySearch::matches($item, $filters)) { $items[$id] = $item; }
    if ($item['price'] !== null && MedcompanySearch::matches($item, $priceFilters)) {
        $priceMin = $priceMin === null ? $item['price'] : min($priceMin, $item['price']);
        $priceMax = $priceMax === null ? $item['price'] : max($priceMax, $item['price']);
    }
    if ($item['brand_id'] && MedcompanySearch::matches($item, $filters, 'brand')) {
        if (!isset($brands[$item['brand_id']])) { $brands[$item['brand_id']] = ['name' => $item['brand'], 'count' => 0]; }
        $brands[$item['brand_id']]['count']++;
    }
    if (MedcompanySearch::matches($item, $filters, 'section')) {
        $sectionTotal++;
        foreach ($item['sections'] as $sectionId) { $sections[$sectionId] = ($sections[$sectionId] ?? 0) + 1; }
        foreach ($item['direct_sections'] ?? [$item['section']] as $sectionId) { $sectionDirect[$sectionId] = ($sectionDirect[$sectionId] ?? 0) + 1; }
    }
}
$sectionChoices = []; $sectionChildren = [];
foreach ($sections as $id => $count) {
    $parent = $data['sections'][$id]['parent'] ?? 0;
    $sectionChildren[$parent] = ($sectionChildren[$parent] ?? 0) + 1;
}
$queryStems = function_exists('stemming') ? array_keys(stemming(mb_strtolower($data['resolved_query'] ?? $query), 'ru')) : [];
foreach ($data['sections'] as $id => $section) {
    $count = $sections[$id] ?? 0;
    if (!$count && $filters['section'] !== $id) continue;
    $nameStems = function_exists('stemming') ? array_keys(stemming(mb_strtolower($section['name']), 'ru')) : [];
    $matchingWords = 0;
    foreach ($queryStems as $stem) {
        foreach ($nameStems as $word) {
            if ($word === $stem || (mb_strlen($stem) >= 3 && mb_strpos($word, $stem) === 0)) { $matchingWords++; break; }
        }
    }
    // Omit redundant ancestor levels with one child and no directly assigned products.
    if (empty($sectionDirect[$id]) && ($sectionChildren[$id] ?? 0) < 2 && !$matchingWords && $filters['section'] !== $id) continue;
    $sectionChoices[$id] = ['name' => $section['name'], 'count' => $count,
        'rank' => $matchingWords ? ($nameStems === $queryStems ? 2 : 1) : 0];
}
uasort($sectionChoices, function ($a, $b) { return ($b['rank'] <=> $a['rank']) ?: ($b['count'] <=> $a['count']) ?: strcmp($a['name'], $b['name']); });
if ($filters['section'] && isset($sectionChoices[$filters['section']])) {
    $sectionChoices = [$filters['section'] => $sectionChoices[$filters['section']]] + $sectionChoices;
}
uasort($brands, function ($a, $b) { return strnatcasecmp($a['name'], $b['name']); });
if ($sort !== 'relevance') {
    uasort($items, function ($a, $b) use ($sort) {
        if ($sort === 'name') { return strcmp(mb_strtolower($a['name']), mb_strtolower($b['name'])) ?: ($a['id'] <=> $b['id']); }
        if ($a['price'] === null || $b['price'] === null) {
            return ($a['price'] === null) <=> ($b['price'] === null) ?: ($a['id'] <=> $b['id']);
        }
        return ($sort === 'price_asc' ? $a['price'] <=> $b['price'] : $b['price'] <=> $a['price']) ?: ($a['id'] <=> $b['id']);
    });
}
$total = count($items);
$pages = max(1, (int)ceil($total / $pageSize));
$page = max(1, min($pages, (int)MedcompanySearch::text($_GET['page'] ?? '1', 8)));
$pageItems = array_slice($items, ($page - 1) * $pageSize, $pageSize, true);
$url = function ($changes = []) use ($query, $filters, $sort, $pageSize) {
    $params = array_merge(['q' => $query, 'section' => $filters['section'], 'brand' => $filters['brand'],
        'min' => $filters['min'], 'max' => $filters['max'], 'stock' => (int)$filters['stock'],
        'photo' => (int)$filters['photo'], 'sort' => $sort, 'count' => $pageSize], $changes);
    $params = array_filter($params, function ($v) { return $v !== null && $v !== '' && $v !== 0; });
    return '/search/?'.http_build_query($params, '', '&', PHP_QUERY_RFC3986);
};
$e = ['MedcompanySearch', 'escape'];
?>
<main class="ms-search" id="search-results">
    <div class="ms-heading"><div><p class="ms-eyebrow">КАТАЛОГ МЕДКОМПАНИИ</p><h1>Поиск товаров</h1></div><a href="/catalog/">Перейти в каталог →</a></div>
    <?php if (mb_strlen($query) < 2): ?>
        <div class="ms-empty"><h2>Что вы ищете?</h2><p>Введите название, бренд или артикул в строке поиска вверху страницы — хотя бы 2 символа.</p><div class="ms-examples"><?php foreach (['цемент', 'перчатки', 'Filtek', 'Fuji'] as $example): ?><a href="<?=$e('/search/?q='.rawurlencode($example))?>"><?=$e($example)?></a><?php endforeach; ?></div></div>
    <?php elseif ($error): ?>
        <div class="ms-empty" role="alert"><h2>Поиск временно недоступен</h2><p>Попробуйте ещё раз или обратитесь к менеджеру: <a href="tel:+74852429560">+7 (4852) 42-95-60</a>.</p></div>
    <?php else: ?>
        <p class="ms-summary">По запросу «<?=$e($query)?>»: <strong><?=$total?></strong> <?=($filters['section'] || $filters['brand'] || $filters['stock'] || $filters['photo'] || $filters['min'] !== null || $filters['max'] !== null) ? 'из '.count($data['items']).' товаров' : MedcompanySearch::productWord($total)?>.</p>
        <?php if ($data['limited']): ?><p class="ms-notice">Показаны первые 10 000 совпадений. Уточните название или артикул.</p><?php endif; ?>
        <?php if ($sectionChoices): ?>
        <section class="ms-sections" aria-labelledby="ms-sections-title">
            <div class="ms-sections-heading"><h2 id="ms-sections-title">Найдено в разделах</h2><span>Выберите нужную группу товаров</span></div>
            <nav class="ms-section-links" id="ms-section-links" aria-label="Разделы найденных товаров">
                <a class="ms-section-link ms-section-all" href="<?=$e($url(['section' => 0]))?>" <?=$filters['section'] === 0 ? 'aria-current="true"' : ''?>><span>Все разделы</span><span class="ms-section-count"><?=$sectionTotal?></span></a>
                <?php $sectionIndex = 0; foreach ($sectionChoices as $id => $choice): ?>
                    <a class="ms-section-link <?=$sectionIndex >= 7 ? 'ms-section-extra' : ''?>" href="<?=$e($url(['section' => $id]))?>" <?=$filters['section'] === $id ? 'aria-current="true"' : ''?> data-section-id="<?=$id?>"><span><?=$e($choice['name'])?></span><span class="ms-section-count"><?=$choice['count']?></span></a>
                <?php $sectionIndex++; endforeach; ?>
            </nav>
            <?php if (count($sectionChoices) > 7): ?><button type="button" class="ms-sections-toggle" aria-controls="ms-section-links" aria-expanded="true" hidden>Показать все разделы (<?=count($sectionChoices)?>)</button><?php endif; ?>
            <p class="ms-sections-note">Счётчики учитывают текущие фильтры и подразделы. Товар может входить в несколько разделов.</p>
        </section>
        <?php endif; ?>
        <div class="ms-layout">
            <aside class="ms-sidebar">
                <details class="ms-filter-details" open>
                    <summary>Фильтры <span aria-hidden="true">⌄</span></summary>
                    <form action="/search/" method="get" id="ms-filter-form" class="ms-filter-form">
                        <input type="hidden" name="q" value="<?=$e($query)?>"><input type="hidden" name="sort" value="<?=$e($sort)?>"><input type="hidden" name="count" value="<?=$pageSize?>">
                        <input type="hidden" name="section" value="<?=$filters['section']?>">
                        <label class="ms-field" for="ms-brand">Производитель<select id="ms-brand" name="brand"><option value="">Все производители</option>
                            <?php if ($filters['brand'] && !isset($brands[$filters['brand']])): ?><option value="<?=$filters['brand']?>" selected>Выбранный производитель (0)</option><?php endif; ?>
                            <?php foreach ($brands as $id => $brand): ?><option value="<?=$id?>" <?=$filters['brand'] === $id ? 'selected' : ''?>><?=$e($brand['name'])?> (<?=$brand['count']?>)</option><?php endforeach; ?>
                        </select></label>
                        <fieldset class="ms-price-range"<?php if ($priceMin !== null && $priceMax > $priceMin): ?> data-min="<?= (int)floor($priceMin) ?>" data-max="<?= (int)ceil($priceMax) ?>"<?php endif ?>><legend>Цена, ₽</legend><div><label><span class="ms-sr-only">Цена от</span><input type="number" name="min" min="0" max="100000000" step="any" value="<?=$e($filters['min'])?>" placeholder="От"></label><span>—</span><label><span class="ms-sr-only">Цена до</span><input type="number" name="max" min="0" max="100000000" step="any" value="<?=$e($filters['max'])?>" placeholder="До"></label></div></fieldset>
                        <label class="ms-check"><input type="checkbox" name="stock" value="1" <?=$filters['stock'] ? 'checked' : ''?>> Только в наличии</label>
                        <label class="ms-check"><input type="checkbox" name="photo" value="1" <?=$filters['photo'] ? 'checked' : ''?>> Только с фотографией</label>
                        <button type="submit" class="ms-primary">Применить фильтры</button>
                        <a class="ms-reset" href="<?=$e('/search/?q='.rawurlencode($query))?>">Сбросить фильтры</a>
                    </form>
                </details>
                <p class="ms-help">Не нашли нужное?<br><a href="tel:+74852429560">+7 (4852) 42-95-60</a><br>Поможем подобрать товар.</p>
            </aside>
            <section class="ms-results" aria-label="Найденные товары">
                <form method="get" action="/search/" class="ms-toolbar">
                    <?php foreach (['q' => $query, 'section' => $filters['section'], 'brand' => $filters['brand'], 'min' => $filters['min'], 'max' => $filters['max'], 'stock' => (int)$filters['stock'], 'photo' => (int)$filters['photo']] as $name => $value): if ($value === null || $value === '' || $value === 0) { continue; } ?><input type="hidden" name="<?=$name?>" value="<?=$e($value)?>"><?php endforeach; ?>
                    <label>Сортировка<select name="sort" aria-label="Сортировка товаров"><?php foreach ($sorts as $value => $label): ?><option value="<?=$value?>" <?=$sort === $value ? 'selected' : ''?>><?=$label?></option><?php endforeach; ?></select></label>
                    <label>Показывать<select name="count" aria-label="Товаров на странице"><?php foreach ([24, 48] as $value): ?><option value="<?=$value?>" <?=$pageSize === $value ? 'selected' : ''?>><?=$value?></option><?php endforeach; ?></select></label><button type="submit" class="ms-sort-apply">Применить</button>
                </form>
                <?php if (!$total): ?>
                    <div class="ms-empty"><h2>Ничего не найдено</h2><p><?=count($data['items']) ? 'Попробуйте сбросить фильтры или расширить диапазон цены.' : 'Проверьте артикул, сократите запрос или попробуйте другое название.'?></p><a href="<?=$e('/search/?q='.rawurlencode($query))?>">Сбросить фильтры</a></div>
                <?php else:
                    $GLOBALS['medSearchMeta'] = $pageItems;
                    $GLOBALS['medSearchFilter'] = ['ID' => array_keys($pageItems)];
                    $params = $arParams;
                    $params = array_merge($params, ['FILTER_NAME' => 'medSearchFilter', 'SECTION_ID' => '', 'SECTION_CODE' => '',
                        'SHOW_ALL_WO_SECTION' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y',
                        'ELEMENT_SORT_FIELD' => 'ID', 'ELEMENT_SORT_ORDER' => array_keys($pageItems),
                        'ELEMENT_SORT_FIELD2' => 'ID', 'ELEMENT_SORT_ORDER2' => 'ASC',
                        'PAGE_ELEMENT_COUNT' => $pageSize, 'LINE_ELEMENT_COUNT' => 3,
                        'DISPLAY_TOP_PAGER' => 'N', 'DISPLAY_BOTTOM_PAGER' => 'N',
                        'CACHE_TYPE' => 'N', 'CACHE_GROUPS' => 'Y', 'CACHE_FILTER' => 'Y',
                        'HIDE_NOT_AVAILABLE' => 'N', 'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
                        'SET_TITLE' => 'N', 'ADD_SECTIONS_CHAIN' => 'N', 'SET_STATUS_404' => 'N',
                        'PROPERTY_CODE' => ['CML2_ARTICLE', 'CML2_MANUFACTURER', 'SYSTEM_IMAGES', 'MORE_PHOTO'],
                        'ADD_PICT_PROP' => 'SYSTEM_IMAGES', 'SHOW_SLIDER' => 'N', 'LAZY_LOAD' => 'N',
                        'LOAD_ON_SCROLL' => 'N', 'PRODUCT_DISPLAY_MODE' => 'Y', 'ADD_TO_BASKET_ACTION' => 'ADD',
                        'SHOW_CLOSE_POPUP' => 'Y', 'SHOW_OLD_PRICE' => 'N', 'SHOW_DISCOUNT_PERCENT' => 'N',
                        'USE_ENHANCED_ECOMMERCE' => 'N', 'PRODUCT_SUBSCRIPTION' => 'N', 'DISPLAY_COMPARE' => 'N']);
                    // The outer pager is stateless; ignore legacy Bitrix pager parameters.
                    $savedPager = [];
                    foreach ($_GET as $key => $value) { if (preg_match('/^(PAGEN|SIZEN|SHOWALL)_\d+$/', $key)) { $savedPager[$key] = $value; unset($_GET[$key], $_REQUEST[$key]); } }
                    $APPLICATION->IncludeComponent('bitrix:catalog.section', 'med_search', $params, $component, ['HIDE_ICONS' => 'Y']);
                    foreach ($savedPager as $key => $value) { $_GET[$key] = $_REQUEST[$key] = $value; }
                    ?>
                    <?php if ($pages > 1): ?><nav class="ms-pagination" aria-label="Страницы результатов">
                        <?php if ($page > 1): ?><a href="<?=$e($url(['page' => $page - 1]))?>">← Назад</a><?php endif; ?>
                        <?php $previous = 0; for ($i = 1; $i <= $pages; $i++): if ($i !== 1 && $i !== $pages && abs($i - $page) > 2) { continue; } if ($previous && $i > $previous + 1): ?><span>…</span><?php endif; ?>
                            <a href="<?=$e($url(['page' => $i]))?>" <?=$page === $i ? 'aria-current="page"' : ''?>><?=$i?></a>
                        <?php $previous = $i; endfor; ?>
                        <?php if ($page < $pages): ?><a href="<?=$e($url(['page' => $page + 1]))?>">Далее →</a><?php endif; ?>
                    </nav><?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    <?php endif; ?>
</main>
<script>
document.querySelectorAll('.ms-toolbar select').forEach(function(select){select.form.classList.add('ms-toolbar-enhanced');select.addEventListener('change',function(){select.form.submit();});});
var sectionBlock=document.querySelector('.ms-sections'),sectionToggle=document.querySelector('.ms-sections-toggle');
if(sectionBlock&&sectionToggle){var showAllText=sectionToggle.textContent;sectionBlock.classList.add('ms-sections-collapsed');sectionToggle.hidden=false;sectionToggle.setAttribute('aria-expanded','false');sectionToggle.addEventListener('click',function(){var expanded=sectionToggle.getAttribute('aria-expanded')==='true';sectionBlock.classList.toggle('ms-sections-collapsed',expanded);sectionToggle.setAttribute('aria-expanded',String(!expanded));sectionToggle.textContent=expanded?showAllText:'Свернуть разделы';});}
if(window.matchMedia('(max-width: 767px)').matches){var filters=document.querySelector('.ms-filter-details');if(filters)filters.open=false;}
</script>
