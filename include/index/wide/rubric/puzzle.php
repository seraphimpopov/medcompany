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
        "template.8",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "7",
            "COUNT_SECTIONS" => "5",
			"PROPERTY_LINK" => "SYSTEM_LINK",
			"PROPERTY_TARGET" => "SYSTEM_TARGET",
			"PROPERTY_SHOW_STICKER" => "SYSTEM_SHOW_STICK",
			"PROPERTY_STICKER" => "SYSTEM_STICK",
			"HEADER_SHOW" => "Y",
			"HEADER_POSITION" => "center",
			"HEADER_TEXT" => "Рубрики",
			"DESCRIPTION_SHOW" => "N",
			"LINE_COUNT" => 4,
			"NAME_VERTICAL" => "bottom",
			"NAME_HORIZONTAL" => "left",
			"FIRST_ITEM_BIG" => "Y",
			"PICTURE_ZOOM" => "Y",
			"SHOW_STICKER" => "Y",
			"STICKER_VERTICAL" => "top",
			"STICKER_HORIZONTAL" => "left",
			"FOOTER_SHOW" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"SORT_BY" => "SORT",
			"SORT_ORDER" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>