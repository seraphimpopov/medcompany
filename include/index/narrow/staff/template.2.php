<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.staff",
        "template.2",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "27",
            "SECTIONS" => array(
            ),
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER_TEXT" => "Наша команда",
            "ELEMENTS_COUNT" => 4,
            "LINE_COUNT" => 4,
            "DESCRIPTION_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>