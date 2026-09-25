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
$style = [
    'margin-bottom' => '10px'
];

if ($banner > 1 && $banner < 5)
    $style = [
        'margin-top' => '30px',
        'margin-bottom' => '10px'
    ];

if ($display) { ?>
    <?= Html::beginTag('div', ['style' => $style]) ?>
        <?php $APPLICATION->IncludeComponent(
            "intec.universe:widget",
            "icons",
            array(
                "IBLOCK_TYPE" => "content",
                "IBLOCK_ID" => "12",
                "SECTIONS_ID" => array(
                    0 => "",
                    1 => "",
                ),
                "ELEMENTS_ID" => array(
                    0 => "",
                    1 => "",
                ),
                "ELEMENTS_COUNT" => "4",
                "SHOW_HEADER" => "N",
                "HEADER" => "",
                "HEADER_POSITION" => "center",
                "LINE_ELEMENTS_COUNT" => "4",
                "VIEW" => "left-float",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "0",
                "PROPERTY_USE_LINK" => "USE_LINK",
                "PROPERTY_LINK" => "LINK",
                "FONT_SIZE_HEADER" => "14",
                "FONT_STYLE_HEADER" => "italic",
                "FONT_STYLE_HEADER_BOLD" => "N",
                "FONT_STYLE_HEADER_ITALIC" => "N",
                "FONT_STYLE_HEADER_UNDERLINE" => "N",
                "HEADER_TEXT_POSITION" => "left",
                "HEADER_TEXT_COLOR" => "",
                "BACKGROUND_COLOR_ICON" => "",
                "BACKGROUND_OPACITY_ICON" => "",
                "BACKGROUND_BORDER_RADIUS" => "",
                "TARGET_BLANK" => "N",
                "FONT_SIZE_DESCRIPTION" => "14",
                "FONT_STYLE_DESCRIPTION_BOLD" => "N",
                "FONT_STYLE_DESCRIPTION_ITALIC" => "N",
                "FONT_STYLE_DESCRIPTION_UNDERLINE" => "N",
                "DESCRIPTION_TEXT_POSITION" => "left",
                "DESCRIPTION_TEXT_COLOR" => ""
            ),
            false
        ); ?>
    <?= Html::endTag('div') ?>
<?php }