<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.gallery",
        "template.2",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "15",
            "SECTIONS" => array(
            ),
            "ELEMENTS_COUNT" => "12",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Примеры выполненных работ",
            "DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => "6",
            "WIDTH_FULL" => "Y",
            "DELIMITER_USE" => "N",
            "FOOTER_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>