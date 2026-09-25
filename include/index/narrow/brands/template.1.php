<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.brands",
        "template.1",
        array(
            "COMPONENT_TEMPLATE" => "template.1",
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "8",
            "ELEMENTS_COUNT" => "8",
            "ELEMENT_LINK_USE" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Нам доверяют",
            "DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => "4",
            "ELEMENT_TRANSPARENT" => "",
            "SLIDER_USE" => "N",
            "FOOTER_SHOW" => "N",
            "LIST_PAGE_URL" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>