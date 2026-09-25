<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("") ?><div class="container">
<?php

use Bitrix\Main\ModuleManager;

$APPLICATION->SetTitle("Корзина")

?>
<?php if (ModuleManager::isModuleInstalled('sale')) { ?>
    <? $APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"bootstrap_v4", 
	array(
		"DEFERRED_REFRESH" => "N",
		"USE_DYNAMIC_SCROLL" => "Y",
		"SHOW_FILTER" => "Y",
		"SHOW_RESTORE" => "Y",
		"ORDER_FAST_USE" => "N",
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "SUM",
		),
		"COLUMNS_LIST_MOBILE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "SUM",
		),
		"TOTAL_BLOCK_DISPLAY" => array(
			0 => "top",
		),
		"PRICE_DISPLAY_MODE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		"USE_PRICE_ANIMATION" => "Y",
		"LABEL_PROP" => array(
		),
		"PATH_TO_ORDER" => "/personal/basket/order.php",
		"HIDE_COUPON" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"USE_PREPAYMENT" => "N",
		"QUANTITY_FLOAT" => "Y",
		"CORRECT_RATIO" => "Y",
		"AUTO_CALCULATION" => "Y",
		"SET_TITLE" => "Y",
		"ACTION_VARIABLE" => "basketAction",
		"COMPATIBLE_MODE" => "Y",
		"EMPTY_BASKET_HINT_PATH" => "/",
		"OFFERS_PROPS" => array(
		),
		"BASKET_IMAGES_SCALING" => "adaptive",
		"USE_GIFTS" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"COMPONENT_TEMPLATE" => "bootstrap_v4",
		"TEMPLATE_THEME" => "green",
		"DISPLAY_MODE" => "extended",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"ADDITIONAL_PICT_PROP_3" => "-",
		"ADDITIONAL_PICT_PROP_16" => "-",
		"ADDITIONAL_PICT_PROP_29" => "-",
		"ADDITIONAL_PICT_PROP_33" => "-"
	),
	false
); ?>
<?php } else { ?>
    <? $APPLICATION->IncludeComponent(
        "intec:startshop.basket",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "CURRENCY" => "rub",
            "REQUEST_VARIABLE_ACTION" => "action",
            "REQUEST_VARIABLE_ITEM" => "item",
            "REQUEST_VARIABLE_QUANTITY" => "quantity",
            "REQUEST_VARIABLE_PAGE" => "page",
            "URL_BASKET_EMPTY" => "",
            "USE_ITEMS_PICTURES" => "Y",
            "USE_BUTTON_CLEAR" => "Y",
            "USE_BUTTON_BASKET" => "Y",
            "USE_SUM_FIELD" => "Y",
            "TITLE_BASKET" => "",
            "TITLE_ORDER" => "Оформление заказа",
            "TITLE_PAYMENT" => "Оплата",
            "URL_ORDER_CREATED" => "/personal/profile/orders/?ORDER_ID=#ID#",
            "USE_ADAPTABILITY" => "Y",
            "REQUEST_VARIABLE_PAYMENT" => "payment",
            "REQUEST_VARIABLE_VALUE_RESULT" => "result",
            "REQUEST_VARIABLE_VALUE_SUCCESS" => "success",
            "REQUEST_VARIABLE_VALUE_FAIL" => "fail",
            "URL_ORDER_CREATED_TO_USER" => "/personal/profile/",
            "AJAX_MODE" => "N",
            "USE_BUTTON_FAST_ORDER" => "N",
            "USE_BUTTON_CONTINUE_SHOPPING" => "Y",
            "URL_CATALOG" => "/catalog/",
            "VERIFY_CONSENT_TO_PROCESSING_PERSONAL_DATA" => "Y",
            "URL_RULES_OF_PERSONAL_DATA_PROCESSING" => "/company/consent/",
            "USE_FAST_ORDER" => "N"
        ),
        false
    ); ?>
<?php } ?>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>