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
        "template.2",
        array(
            "IBLOCK_TYPE" => "#SERVICES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#SERVICES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "5",
            "HEADER_BLOCK_SHOW" => "Y",
            "HEADER_BLOCK_POSITION" => "LEFT",
            "HEADER_BLOCK_TEXT" => "Услуги",
            "DESCRIPTION_BLOCK_SHOW" => "N",
            "TEMPLATE_VIEW" => "mosaic",
            "PRICE_SHOW" => "N",
            "BUTTON_SHOW" => "Y",
            "BUTTON_TYPE" => "detail",
            "BUTTON_TEXT" => "",
            "SEE_ALL_SHOW" => "N",
            "LIST_PAGE_URL" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => ""
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>