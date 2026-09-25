<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.reviews",
        "template.1",
        array(
            "COMPONENT_TEMPLATE" => "template.1",
            "IBLOCK_TYPE" => "reviews",
            "IBLOCK_ID" => "19",
            "COUNT_ELEMENTS" => "",
            "SELECT_BY_PROPERTY" => "N",
            "PROPERTY_POSITION" => "",
            "VIDEO_SHOW" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER_TEXT" => "Отзывы",
            "DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => "1",
            "SLIDER_USE" => "N",
            "FOOTER_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>