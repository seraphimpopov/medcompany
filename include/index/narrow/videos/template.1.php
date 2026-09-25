<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.videos",
        "template.1",
        array(
            "COMPONENT_TEMPLATE" => "template.1",
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "28",
            "ELEMENTS_COUNT" => "9",
            "PROPERTY_URL" => "LINK",
            "PROPERTY_IBLOCK_IMAGE" => "USE_IMAGE_IBLOCK",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER" => "Видеогалерея",
            "DESCRIPTION_SHOW" => "N",
            "IMAGE_QUALITY" => "hqdefault",
            "LINE_COUNT" => "3",
            "FOOTER_SHOW" => "N",
            "SLIDER_USE" => "Y",
            "SLIDER_LOOP_USE" => "N",
            "SLIDER_AUTO_PLAY_USE" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>