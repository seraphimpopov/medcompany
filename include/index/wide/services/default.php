<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.services",
        "template.9",
        array(
            "IBLOCK_TYPE" => "#SERVICES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#SERVICES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => 6,
            "HEADER_BLOCK_SHOW" => "Y",
            "HEADER_BLOCK_POSITION" => "center",
            "HEADER_BLOCK_TEXT" => "Услуги",
            "DESCRIPTION_BLOCK_SHOW" => "N",
            "LINE_COUNT" => 3,
            "LINK_USE" => "Y",
            "SEE_ALL_SHOW" => "N",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>