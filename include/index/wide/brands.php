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
$template = ArrayHelper::getValue($blocks, ['templates', $code], 'brands');

if ($display) { ?>
    <?php if (ArrayHelper::isIn($template, [
        'brands'
    ])) { ?>
        <?= Html::beginTag('div', ['style' => [
            'margin-top' => '100px',
            'margin-bottom' => '100px'
        ]]) ?>
            <?php $APPLICATION->IncludeComponent(
                "intec.universe:widget",
                "brands",
                array(
                    "IBLOCK_TYPE" => "content",
                    "IBLOCK_ID" => "8",
                    "ITEMS_LIMIT" => "",
                    "DISPLAY_TITLE" => "N",
                    "SHOW_DESCRIPTION" => "N",
                    "COUNT_ELEMENT_IN_ROW" => "6",
                    "AUTOPLAY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "0"
                ),
                false
            ); ?>
        <?= Html::endTag('div') ?>
    <?php } else { ?>
        <?php $templateInclude($template) ?>
    <?php } ?>
<?php }