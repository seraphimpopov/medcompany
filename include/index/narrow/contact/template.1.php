<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div') ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.widget",
        "contact.1",
        array(
            "SETTINGS_USE" => "Y",
            "MAP_VENDOR" => "yandex",
            "INIT_MAP_TYPE" => "MAP",
            "MAP_MAP_DATA" => "",
            "BLOCK_SHOW" => "Y",
            "BLOCK_TITLE" => "Наши контакты",
            "ADDRESS_SHOW" => "Y",
            "ADDRESS_CITY" => "г. Челябинск",
            "ADDRESS_STREET" => "",
            "PHONE_SHOW" => "Y",
            "PHONE_VALUES" => array(
                0 => "+7 (000) 000 00 00",
            ),
            "FORM_SHOW" => "Y",
            "FORM_ID" => "1",
            "FORM_TEMPLATE" => ".default",
            "FORM_TITLE" => "Заказать звонок",
            "FORM_BUTTON_TEXT" => "Заказать звонок",
            "EMAIL_SHOW" => "Y",
            "EMAIL_VALUES" => array(
                0 => "shop@example.com",
            ),
            "CONSENT_URL" => "/company/consent/",
            "MAP_OVERLAY" => "Y",
            "WIDE" => "Y",
            "BLOCK_VIEW" => "over",
            "MAP_CONTROLS" => array(
                0 => "ZOOM",
                1 => "SMALLZOOM",
                2 => "MINIMAP",
                3 => "TYPECONTROL",
                4 => "SCALELINE",
            ),
            "MAP_OPTIONS" => array(
                0 => "ENABLE_SCROLL_ZOOM",
                1 => "ENABLE_DBLCLICK_ZOOM",
                2 => "ENABLE_RIGHT_MAGNIFIER",
                3 => "ENABLE_DRAGGING",
            ),
            "MAP_MAP_ID" => ""
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>