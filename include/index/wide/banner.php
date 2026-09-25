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

$code = 'main_banner';
$display = ArrayHelper::getValue($blocks, ['active', $code], 'Y') === 'Y';
$display = $display && $banner < 5;
$style = [];
$template = null;

if ($banner == 1) {
    $template = 'slider';
} else {
    $template = 'slider.complex';
    $style = [
        'padding-top' => '20px',
        'padding-bottom' => '20px',
        'background-color' => '#f8f9fb'
    ];
}

if ($display) { ?>
    <?= Html::beginTag('div', ['style' => $style]) ?>
        <?php if ($template == 'slider') { ?>
            <?php $APPLICATION->IncludeComponent(
                "intec.universe:widget",
                "slider",
                array(
                    "IBLOCK_TYPE" => "content",
                    "IBLOCK_ID" => "5",
                    "SLIDER_COUNT" => "",
                    "SLIDER_ACTIVE_ELEMENTS" => "Y",
                    "SLIDER_PROPERTY_TITLE" => "HEADER",
                    "SLIDER_PROPERTY_TITLE_COLOR" => "TITLE_TEXT_COLOR",
                    "SLIDER_PROPERTY_DESCRIPTION" => "DESCRIPTION",
                    "SLIDER_PROPERTY_DESCRIPTION_COLOR" => "DESCRIPTION_TEXT_COLOR",
                    "SLIDER_PROPERTY_LINK" => "LINK",
                    "SLIDER_PROPERTY_BLANK" => "NEW_TAB",
                    "SLIDER_PROPERTY_BUTTON_SHOW" => "BUTTON_SHOW",
                    "SLIDER_PROPERTY_BUTTON_TEXT" => "BUTTON_TEXT",
                    "SLIDER_PROPERTY_BUTTON_TEXT_COLOR" => "BUTTON_TEXT_COLOR",
                    "SLIDER_PROPERTY_BUTTON_COLOR" => "BUTTON_COLOR",
                    "SLIDER_PROPERTY_TEXT_POSITION" => "POSITION",
                    "SLIDER_PROPERTY_IMAGE" => "BANNER_IMG",
                    "SLIDER_PROPERTY_IMAGE_POSITION" => "BANNER_IMG_POSITION",
                    "SLIDER_PROPERTY_AUTOPLAY" => "Y",
                    "SLIDER_PROPERTY_AUTOPLAY_DELAY" => "5000",
                    "SLIDER_PROPERTY_HEIGHT" => "600",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "0"
                ),
                false
            ); ?>
        <?php } else { ?>
            <?php $APPLICATION->IncludeComponent(
                "intec.universe:widget",
                "slider.complex",
                array(
                    "IBLOCK_TYPE" => "content",
                    "IBLOCK_ID" => "5",
                    "SLIDER_COUNT" => "",
                    "SLIDER_ACTIVE_ELEMENTS" => "Y",
                    "SLIDER_PROPERTY_TITLE" => "HEADER",
                    "SLIDER_PROPERTY_TITLE_COLOR" => "TITLE_TEXT_COLOR",
                    "SLIDER_PROPERTY_DESCRIPTION" => "DESCRIPTION",
                    "SLIDER_PROPERTY_DESCRIPTION_COLOR" => "DESCRIPTION_TEXT_COLOR",
                    "SLIDER_PROPERTY_LINK" => "LINK",
                    "SLIDER_PROPERTY_BLANK" => "NEW_TAB",
                    "SLIDER_PROPERTY_BUTTON_SHOW" => "BUTTON_SHOW",
                    "SLIDER_PROPERTY_BUTTON_TEXT" => "BUTTON_TEXT",
                    "SLIDER_PROPERTY_BUTTON_TEXT_COLOR" => "BUTTON_TEXT_COLOR",
                    "SLIDER_PROPERTY_BUTTON_COLOR" => "BUTTON_COLOR",
                    "SLIDER_PROPERTY_TEXT_POSITION" => "POSITION",
                    "SLIDER_PROPERTY_IMAGE" => "BANNER_IMG",
                    "SLIDER_PROPERTY_IMAGE_POSITION" => "BANNER_IMG_POSITION",
                    "SLIDER_PROPERTY_AUTOPLAY" => "Y",
                    "SLIDER_PROPERTY_AUTOPLAY_DELAY" => "5000",
                    "SLIDER_PROPERTY_HEIGHT" => "500",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "0",
                    "IBLOCK_TYPE_CB" => "content",
                    "IBLOCK_ID_CB" => "6",
                    "SLIDER_CB_PROPERTY_ELEMENTS" =>  array(
                        0 => "328",
                        1 => "329",
                        2 => "330",
                        3 => "331"
                    ),
                    "SLIDER_CB_PROPERTY_LINK" => "LINK",
                    "SLIDER_CB_PROPERTY_LINK_BLANK" => "LINK_BLANK",
                    "SLIDER_CB_PROPERTY_TEXT_COLOR" => "TEXT_COLOR",
                    "SLIDER_CB_PROPERTY_VIEW" => "left",
                    "SLIDER_CB_PROPERTY_COUNT" => "4"
                ),
                false
            ); ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php }