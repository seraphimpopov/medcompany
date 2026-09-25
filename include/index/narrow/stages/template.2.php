<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.stages",
        "template.2",
        array(
            "IBLOCK_TYPE" => "#ADVANTAGES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#ADVANTAGES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "3",
            "ELEMENT_DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => "3",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER" => "Этапы работ",
            "DESCRIPTION_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>