<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var string $code
 * @var array $blocks
 * @var integer $banner
 * @var Closure $templateInclude($template)
 */

$display = ArrayHelper::getValue($blocks, ['active', $code], 'Y') === 'Y';

?>
<?php if ($display) { ?>
    <?= Html::beginTag('div', [
        'style' => [
            'margin-top' => '50px'
        ]
    ]) ?>
        <?php $APPLICATION->IncludeComponent(
            "intec.universe:main.news",
            "template.4",
            array(
                "IBLOCK_TYPE" => "content",
                "IBLOCK_ID" => "14",
                "ELEMENTS_COUNT" => 4,
                "HEADER_BLOCK_SHOW" => "Y",
                "HEADER_BLOCK_POSITION" => "center",
                "HEADER_BLOCK_TEXT" => "Новости",
                "DESCRIPTION_BLOCK_SHOW" => "N",
                "LINK_USE" => "Y",
                "DATE_SHOW" => "Y",
                "DATE_FORMAT" => "d.m.Y",
                "SEE_ALL_SHOW" => "N",
                "SECTION_URL" => "",
                "DETAIL_URL" => "",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 3600,
                "SORT_BY" => "DATE_ACTIVE",
                "ORDER_BY" => "DESC"
            ),
            false
        ); ?>
    <?= Html::endTag('div') ?>
<?php } ?>