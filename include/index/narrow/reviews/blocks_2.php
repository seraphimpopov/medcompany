<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.reviews",
        "template.6",
        array(
            "IBLOCK_TYPE" => "reviews",
            "IBLOCK_ID" => "19",
            "COUNT_ELEMENTS" => "2",
			"SELECT_BY_PROPERTY" => "N",
			"HEADER_SHOW" => "Y",
			"HEADER_POSITION" => "left",
			"HEADER_TEXT" => "Отзывы",
			"DESCRIPTION_SHOW" => "N",
			"LINE_COUNT" => 2,
			"POSITION_SHOW" => "Y",
			"TRUNCATE" => "150",
			"FOOTER_SHOW" => "Y",
			"FOOTER_POSITION" => "right",
			"FOOTER_TEXT" => "Показать все",
			"LIST_PAGE" => "/company/reviews/",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"SORT_BY" => "SORT",
			"ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>