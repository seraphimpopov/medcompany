<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
$this->setFrameMode(false);
\CJSCore::Init(['popup', 'ajax', 'fx', 'currency']);
$this->addExternalJs('/bitrix/templates/medcompany/components/bitrix/catalog.item/item/script.js');
if (!empty($arResult['CURRENCIES'])): ?>
<script>BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true)?>);</script>
<?php endif; ?>
<script>
BX.message({BTN_MESSAGE_BASKET_REDIRECT:'Перейти в корзину',BASKET_URL:'/personal/cart/',ADD_TO_BASKET_OK:'Товар добавлен в корзину',TITLE_ERROR:'Ошибка',TITLE_BASKET_PROPS:'Свойства товара',TITLE_SUCCESSFUL:'Товар добавлен в корзину',BASKET_UNKNOWN_ERROR:'Не удалось добавить товар. Попробуйте ещё раз.',BTN_MESSAGE_SEND_PROPS:'Выбрать',BTN_MESSAGE_CLOSE:'Закрыть',BTN_MESSAGE_CLOSE_POPUP:'Продолжить покупки',PRICE_TOTAL_PREFIX:'Итого',SITE_ID:'<?=CUtil::JSEscape(SITE_ID)?>'});
</script>
<?php
$general = $arParams + ['LABEL_POSITION_CLASS' => '', 'DISCOUNT_POSITION_CLASS' => '',
    'RELATIVE_QUANTITY_FACTOR' => 5, 'SLIDER_INTERVAL' => 3000, 'SLIDER_PROGRESS' => 'N',
    'SHOW_MAX_QUANTITY' => 'N', 'SKU_PROPS' => [], 'TEMPLATE_THEME' => 'green',
    'PRODUCT_BLOCKS_ORDER' => ['price', 'quantity', 'buttons'], 'DATA_LAYER_NAME' => 'dataLayer', 'BRAND_PROPERTY' => 'CML2_MANUFACTURER'];
$general['DISPLAY_COMPARE'] = false;
$general['~BASKET_URL'] = '/personal/cart/';
$general['~ADD_URL_TEMPLATE'] = $arResult['~ADD_URL_TEMPLATE'];
$general['~BUY_URL_TEMPLATE'] = $arResult['~BUY_URL_TEMPLATE'];
?>
<div class="ms-grid">
<?php foreach ($arResult['ITEMS'] as $item):
    $meta = $GLOBALS['medSearchMeta'][(int)$item['ID']] ?? [];
    $item['MED_SEARCH_META'] = $meta;
    $area = $this->GetEditAreaId('ms_'.$item['ID']);
    $APPLICATION->IncludeComponent('bitrix:catalog.item', 'med_search', [
        'RESULT' => ['ITEM' => $item, 'AREA_ID' => $area, 'TYPE' => 'CARD',
            'BIG_LABEL' => 'N', 'BIG_DISCOUNT_PERCENT' => 'N', 'BIG_BUTTONS' => 'N', 'SCALABLE' => 'N'],
        'PARAMS' => $general,
    ], $component, ['HIDE_ICONS' => 'Y']);
endforeach; ?>
</div>
