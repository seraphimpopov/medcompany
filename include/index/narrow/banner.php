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
$style = [
    'margin-bottom' => '50px'
];
$template = null;

if ($banner == 1) {
    $template = 'slider';
} else {
    $template = 'slider.complex';
}

if ($display) {
    if ($banner < 5) { ?>
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
                        "SLIDER_PROPERTY_HEIGHT" => "500",
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
                        "SLIDER_CB_PROPERTY_ELEMENTS" => array(
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
    <?php } else if ($banner == 10) { ?>
        <?= Html::beginTag('div', ['style' => $style]) ?>
            <?php $APPLICATION->IncludeComponent(
                "intec.universe:main.slider",
                "template.5",
                array(
                    "IBLOCK_TYPE" => "content",
                    "IBLOCK_ID" => "5",
                    "BLOCKS_IBLOCK_TYPE" => "content",
                    "BLOCKS_IBLOCK_ID" => "6",
                    "BLOCKS_COUNT" => 2,
                    "PROPERTY_HEADER" => "HEADER",
                    "PROPERTY_DESCRIPTION" => "DESCRIPTION",
                    "PROPERTY_HEADER_COLOR" => "TITLE_TEXT_COLOR",
                    "PROPERTY_DESCRIPTION_COLOR" => "DESCRIPTION_TEXT_COLOR",
                    "PROPERTY_LINK" => "LINK",
                    "PROPERTY_LINK_BLANK" => "NEW_TAB",
                    "PROPERTY_BUTTON_SHOW" => "BUTTON_SHOW",
                    "PROPERTY_BUTTON_TEXT" => "BUTTON_TEXT",
                    "PROPERTY_BUTTON_TEXT_COLOR" => "BUTTON_TEXT_COLOR",
                    "PROPERTY_BUTTON_COLOR" => "BUTTON_COLOR",
                    "PROPERTY_BANNER_COLOR" => "BANNER_COLOR",
                    "PROPERTY_VIDEO_URL" => "BANNER_VIDEO_LINK",
                    "BLOCKS_PROPERTY_LINK" => "LINK",
                    "BLOCKS_PROPERTY_LINK_BLANK" => "LINK_BLANK",
                    "HEADER_SHOW" => "Y",
                    "DESCRIPTION_SHOW" => "Y",
                    "HEIGHT" => 500,
                    "WIDE" => "N",
                    "VIDEO_SHADOW_USE" => "N",
                    "SLIDER_DOTS" => "Y",
                    "SLIDER_NAV" => "Y",
                    "SLIDER_LOOP" => "N",
                    "SLIDER_SPEED" => 500,
                    "SLIDER_AUTO_USE" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => 3600,
                    "SORT_BY" => "SORT",
                    "ORDER_BY" => "ASC",
                    "SLIDER_AUTO_TIME" => 10000,
                    "SLIDER_AUTO_PAUSE" => "N"
                ),
                false
            ); ?>
        <?= Html::endTag('div') ?>
    <?php }
}