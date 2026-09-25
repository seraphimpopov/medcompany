<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php $APPLICATION->IncludeComponent(
    "intec.universe:main.video",
    "template.1",
    array(
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => "28",
        "ELEMENT" => "",
        "PROPERTY_LINK" => "LINK",
        "PROPERTY_IMAGE_USE" => "",
        "HEADER_SHOW" => "N",
        "DESCRIPTION_SHOW" => "N",
        "QUALITY" => "maxresdefault",
        "WIDTH" => "Y",
        "HEIGHT" => "400",
        "BUTTON_COLOR_THEME" => "light",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "0"
    ),
    false
); ?>