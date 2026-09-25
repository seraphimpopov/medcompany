<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px',
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.gallery",
        "template.1",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "15",
            "SECTIONS" => array(
            ),
            "ELEMENTS_COUNT" => "",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Примеры выполненных работ",
            "DESCRIPTION_SHOW" => "N",
            "TABS_POSITION" => "center",
            "LINE_COUNT" => "4",
            "MAX_SECTION_ELEMENTS" => "8",
            "ELEMENTS_DELIMITER" => "N",
            "FOOTER_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>