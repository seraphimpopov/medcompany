<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.advantages",
        "template.1",
        array(
            "IBLOCK_TYPE" => "#ADVANTAGES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#ADVANTAGES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "4",
            "ELEMENTS_ROW_COUNT" => "4",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER" => "Преимущества",
            "DESCRIPTION_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>