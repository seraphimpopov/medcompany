<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/medcompany_search.php';
$meta = $arResult['ITEM']['MED_SEARCH_META'] ?? [];
$photo = MedcompanySearch::image($meta['photo'] ?? 0);
$arResult['ITEM']['MED_SEARCH_PHOTO'] = $photo;
$arResult['ITEM']['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] = $meta['name'] ?? $arResult['ITEM']['NAME'];
$arResult['ITEM']['SECOND_PICT'] = false;
$arResult['ITEM']['MORE_PHOTO'] = [];
$arResult['ITEM']['MORE_PHOTO_COUNT'] = 0;
if ($photo) { $arResult['ITEM']['PREVIEW_PICTURE'] = ['SRC' => $photo, 'WIDTH' => 360, 'HEIGHT' => 360]; }
$requestPrice = MedcompanySearch::priceOnRequest($meta['brand'] ?? '', $meta['section'] ?? 0);
$price = $arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']] ?? [];
if (!MedcompanySearch::noCart($meta['brand'] ?? '', $meta['section'] ?? 0) && $price && $arResult['ITEM']['CAN_BUY']) {
    // Reuse the site's native pricing/quantity/basket implementation.
    require dirname(__DIR__).'/item/template.php';
} else {
    $item = $arResult['ITEM'];
    $actualItem = $item;
    $productTitle = $meta['name'] ?? $item['NAME'];
    $areaId = $arResult['AREA_ID'];
    $itemIds = [];
    foreach (['PICT', 'PICT_SLIDER', 'PRICE', 'BUY_LINK', 'QUANTITY', 'QUANTITY_UP', 'QUANTITY_DOWN', 'BASKET_ACTIONS', 'NOT_AVAILABLE_MESS'] as $id) { $itemIds[$id] = $areaId.'_'.strtolower($id); }
    $measureRatio = 1;
    echo '<div class="product-item-container" id="'.MedcompanySearch::escape($areaId).'" data-entity="item">';
    require __DIR__.'/card/template.php';
    echo '</div>';
}
