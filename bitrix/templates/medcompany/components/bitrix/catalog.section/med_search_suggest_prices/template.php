<?php
// Read the native catalogue calculation without rendering a second product list.
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
$this->setFrameMode(false);
$GLOBALS['medSearchSuggestionPrices'] = [];
foreach ($arResult['ITEMS'] as $item) {
    $price = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']] ?? null;
    if (!$price || !isset($price['RATIO_PRICE'], $price['CURRENCY'], $price['PRINT_RATIO_PRICE'])) continue;
    $GLOBALS['medSearchSuggestionPrices'][(int)$item['ID']] = [
        'price' => (float)$price['RATIO_PRICE'],
        'currency' => $price['CURRENCY'],
        'price_text' => trim(html_entity_decode(strip_tags($price['PRINT_RATIO_PRICE']), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
    ];
}
