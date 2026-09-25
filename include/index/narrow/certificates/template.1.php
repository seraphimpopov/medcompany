<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.certificates",
        "template.1",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "9",
            "ELEMENTS_COUNT" => 4,
            "LINE_COUNT" => 4,
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER_TEXT" => "Сертификаты",
            "DESCRIPTION_SHOW" => "N",
            "ELEMENT_NAME_SHOW" => "Y",
            "FOOTER_SHOW" => "Y",
            "FOOTER_POSITION" => "right",
            "FOOTER_TEXT" => "Показать все",
            "LIST_PAGE" => "/company/sertifikaty/",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>