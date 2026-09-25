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
$template = ArrayHelper::getValue($blocks, ['templates', $code], 'default');

if ($display) { ?>
    <?php if ($template == 'default') { ?>
        <?= Html::beginTag('div', ['style' => [
            'margin-top' => '50px',
            'margin-bottom' => '50px'
        ]]) ?>
            <?php $APPLICATION->IncludeComponent(
                "intec.universe:widget",
                "photo",
                array(
                    "IBLOCK_TYPE" => "content",
                    "IBLOCK_ID" => "15",
                    "ALIGHT_HEADER" => "center",
                    "SHOW_TITLE" => "Y",
                    "TITLE" => "Фотогалерея",
                    "SHOW_DETAIL_LINK" => "N",
                    "USE_CAROUSEL" => "N",
                    "COLUMNS_COUNT" => "4",
                    "ITEMS_LIMIT" => "8",
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