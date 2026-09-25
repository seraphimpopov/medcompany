<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.shares",
        "template.3",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "22",
            "ELEMENTS_COUNT" => 3,
			"HEADER_BLOCK_SHOW" => "Y",
			"HEADER_BLOCK_POSITION" => "left",
			"HEADER_BLOCK_TEXT" => "Акции",
			"DESCRIPTION_BLOCK_SHOW" => "N",
			"LINE_COUNT" => 3,
			"LINK_USE" => "Y",
			"DESCRIPTION_USE" => "Y",
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