<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\constructor\models\Build;
use intec\core\io\Path;

/**
 * @global CMain $APPLICATION
 */

$properties = Build::getCurrent()->getPage()->getProperties();
$blocks = $properties->get('main_blocks');
$banner = ArrayHelper::getValue($blocks, ['templates', 'main_banner']);

/**
 * @param string $code
 * @param string $template
 */
$widgetInclude = function ($code) use (&$blocks, &$banner) {
    global $APPLICATION;

    $path = Path::from(__DIR__);
    $path = $path
        ->add('narrow')
        ->add($code.'.php');

    /**
     * @param string $template
     */
    $templateInclude = function ($template) use (&$code, &$blocks, &$banner) {
        global $APPLICATION;

        $path = Path::from(__DIR__);
        $path = $path
            ->add('narrow')
            ->add($code)
            ->add($template.'.php');

        if (FileHelper::isFile($path->value))
            include($path->value);
    };

    if (FileHelper::isFile($path->value))
        include($path->value);
};

?>
<div class="intec-content intec-content-visible clearfix" style="margin-top: 50px">
    <div class="intec-content-wrapper">
        <div class="intec-content-left">
            <?php $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "vertical",
                array(
                    "COMPONENT_TEMPLATE" => "vertical",
                    "ROOT_MENU_TYPE" => "catalog",
                    "IBLOCK_TYPE" => "#PRODUCTS_IBLOCK_TYPE#",
                    "IBLOCK_ID" => "#PRODUCTS_IBLOCK_ID#",
                    "PROPERTY_IMAGE" => "UF_IMAGE",
                    "PROPERTY_SHOW_HEADER_SUBMENU" => "N",
                    "MENU_CACHE_TYPE" => "N",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "N",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MAX_LEVEL" => "4",
                    "CHILD_MENU_TYPE" => "catalog",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N"
                ),
                false
            ); ?>
            <?php $widgetInclude('news') ?>
        </div>
        <div class="intec-content-right">
            <div class="intec-content-right-wrapper">
                <?php

                $widgetInclude('banner');
                $widgetInclude('icons');
                $widgetInclude('categories');
                $widgetInclude('rubric');
                $widgetInclude('photogallery');
                $widgetInclude('products');
                $widgetInclude('services');
                $widgetInclude('stages');
                $widgetInclude('advantages');

                ?>
                <?= Html::beginTag('div') ?>
                    <?php $APPLICATION->IncludeComponent(
                        "intec.universe:main.form",
                        "template.1",
                        array(
                            "ID" => "1",
                            "NAME" => "Заказать звонок",
                            "CONSENT" => "/company/consent/",
                            "TEMPLATE" => ".default",
                            "VIEW" => "left",
                            "BG_COLOR" => "",
                            "BG_IMAGE_SHOW" => "Y",
                            "BG_IMAGE" => "#TEMPLATE_PATH#/images/bg.png",
                            "BG_IMAGE_POSITION_V" => "center",
                            "BG_IMAGE_POSITION_H" => "left",
                            "BG_IMAGE_SIZE" => "cover",
                            "BG_IMAGE_REPEAT" => "no-repeat",
                            "TITLE" => "Узнайте, с чего начать строительство вашего дома",
                            "DESCRIPTION_SHOW" => "Y",
                            "DESCRIPTION" => "Наши консультанта ответят на все ваши вопросы, дадут оценку и рекомендации вашему проекту",
                            "TEXT_THEME" => "dark",
                            "BUTTON_TEXT" => "Заказать",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600"
                        ),
                        false
                    ); ?>
                <?= Html::endTag('div') ?>
                <?php

                $widgetInclude('video');
                $widgetInclude('rates');
                $widgetInclude('staff');
                $widgetInclude('certificates');
                $widgetInclude('faq');
                $widgetInclude('videos');
                $widgetInclude('shares');
                $widgetInclude('articles');
                $widgetInclude('reviews');
                $widgetInclude('about');
                $widgetInclude('brands');

                ?>
            </div>
        </div>
    </div>
</div>
<?php

$widgetInclude('contact');

?>
<?= Html::tag('div', null, ['style' => [
    'margin-bottom' => '-30px'
]]) ?>
