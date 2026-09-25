<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.faq",
        "template.1",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "11",
            "SECTIONS" => array(
            ),
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER_TEXT" => "Вопрос - ответ",
            "DESCRIPTION_SHOW" => "N",
            "BY_SECTION" => "Y",
            "ELEMENT_TEXT_ALIGN" => "left",
            "FOOTER_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC",
            "TABS_POSITION" => "left"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>
