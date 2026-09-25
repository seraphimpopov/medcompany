<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'padding-top' => '50px',
    'padding-bottom' => '50px',
    'background-color' => '#f8f9fb'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.news",
        "template.3",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "14",
            "ELEMENTS_COUNT" => 4,
			"HEADER_BLOCK_SHOW" => "Y",
			"HEADER_BLOCK_POSITION" => "center",
			"HEADER_BLOCK_TEXT" => "Новости",
			"DESCRIPTION_BLOCK_SHOW" => "N",
			"LINE_COUNT" => 4,
			"LINK_USE" => "Y",
			"DATE_SHOW" => "Y",
			"DATE_FORMAT" => "d.m.Y",
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