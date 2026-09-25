<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.widget",
        "products.1",
        array(
            "IBLOCK_TYPE" => "#PRODUCTS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#PRODUCTS_IBLOCK_ID#",
            "MODE" => "all",
            "ACTION" => "buy",
            "PRICE_CODE" => array(
                "BASE"
            ),
            "PROPERTY_LABEL_NEW" => "SYSTEM_NEW",
            "PROPERTY_LABEL_RECOMMEND" => "SYSTEM_RECOMMEND",
            "PROPERTY_LABEL_HIT" => "SYSTEM_HIT",
            "PROPERTY_CATEGORY" => "SYSTEM_CATEGORY",
            "DISCOUNT_SHOW" => "Y",
            "SLIDER_USE" => "N",
            "TITLE_SHOW" => "N",
            "DESCRIPTION_SHOW" => "N",
            "COLUMNS" => 3,
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/personal/basket.php",
            "CONSENT_URL" => "/company/consent/",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "FORM_ID" => "3",
            "FORM_PROPERTY_PRODUCT" => "form_text_7"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>