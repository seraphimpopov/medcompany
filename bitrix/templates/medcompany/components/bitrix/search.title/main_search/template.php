<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
$this->setFrameMode(true);
$inputId = preg_replace('/[^a-zA-Z0-9_-]/', '', $arParams['INPUT_ID'] ?? 'title-search-input') ?: 'title-search-input';
$containerId = preg_replace('/[^a-zA-Z0-9_-]/', '', $arParams['CONTAINER_ID'] ?? 'title-search') ?: 'title-search';
$query = isset($_GET['q']) && is_string($_GET['q']) ? mb_substr($_GET['q'], 0, 120) : '';
$this->addExternalCss('/local/assets/medcompany-search-suggest.css?v=20260905.3');
$this->addExternalJs('/local/assets/medcompany-search-suggest.js?v=20260905.3');
if ($arParams['SHOW_INPUT'] !== 'N'): ?>
<div id="<?=htmlspecialcharsbx($containerId)?>" class="col-xl-12 bx-searchtitle med-search-widget">
    <form action="/search/" method="get" role="search">
        <div class="bx-input-group">
            <input id="<?=htmlspecialcharsbx($inputId)?>" type="search" name="q" value="<?=htmlspecialcharsbx($query)?>" autocomplete="off" class="bx-form-control med-search-input" placeholder="Название товара или артикул…" maxlength="120" aria-label="Поиск по каталогу" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="<?=htmlspecialcharsbx($containerId)?>-suggestions">
            <span class="bx-input-group-btn"><button class="btn btn-default" type="submit" aria-label="Найти товар"><i class="fa fa-search" aria-hidden="true"></i></button></span>
        </div>
    </form>
    <div class="med-suggest" hidden><div id="<?=htmlspecialcharsbx($containerId)?>-suggestions" role="listbox" aria-label="Подсказки товаров"></div><a class="med-suggest-all" href="/search/">Все результаты →</a></div>
    <span class="med-suggest-status" role="status" aria-live="polite"></span>
</div>
<?php endif; ?>
