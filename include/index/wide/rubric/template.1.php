<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <? $APPLICATION->IncludeComponent(
        "intec.universe:main.categories",
        "template.1",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "7",
            "COUNT_SECTIONS" => "4",
            "PROPERTY_LINK" => "SYSTEM_LINK",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Рубрики",
            "DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => 4,
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "SORT_ORDER" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>