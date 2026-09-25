<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.rates",
        "template.1",
        array(
            "IBLOCK_TYPE" => "#RATES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#RATES_IBLOCK_ID#",
            "SECTIONS" => array(

            ),
            "COUNT_SECTIONS" => "",
            "PROPERTY_PRICE" => "PRICE",
            "PROPERTY_CURRENCY" => "CURRENCY",
            "PROPERTY_DISCOUNT" => "DISCOUNT",
            "PROPERTY_DISCOUNT_TYPE" => "DISCOUNT_TYPE",
            "PROPERTY_DETAIL_URL" => "URL",
            "PROPERTY_LIST" => array(
                0 => "COUNT_PRODUCT",
                1 => "COUNT_PHOTO",
                2 => "COUNT_DOCUMENTS",
                3 => "DISK_SPACE"
            ),
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Тарифы",
            "DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => "4",
            "SECTION_MAX_COUNT" => "4",
            "VIEW" => "tabs",
            "TABS_POSITION" => "center",
            "SECTION_DESCRIPTION_SHOW" => "Y",
            "SECTION_DESCRIPTION_POSITION" => "center",
            "COUNT_SHOW" => "Y",
            "COUNT_TEXT" => "Тариф",
            "PRICE_SHOW" => "Y",
            "DISCOUNT_STICKER_SHOW" => "Y",
            "ELEMENT_DESCRIPTION_SHOW" => "N",
            "PROPERTY_LIST_SHOW" => "Y",
            "BUTTON_SHOW" => "N",
            "SLIDER_USE" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "SORT_ORDER" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>