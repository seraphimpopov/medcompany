<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Большой выбор оснащения для стоматологических клиник в интернет магазине медкомпания.рф - гарантия качества!");
$APPLICATION->SetPageProperty("title", "Интернет магазин комплексного оснащения стоматологических клиник - медкомпания.рф");

/**
 * @global $APPLICATION
 */

$APPLICATION->SetTitle("Медицинская компания");

?><div class="container">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"newlist", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "newlist",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "10",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "HEADER",
			1 => "DESCRIPTION",
			2 => "LINK",
			3 => "BUTTON_SHOW",
			4 => "BANNER_COLOR",
			5 => "TITLE_TEXT_COLOR",
			6 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?>
	<div class="body__body">
		<div class="body__body-inner">
			<div class="body__head-text">
 <span class="good">
				Лучшее </span>
			</div>
			 <?
	$GLOBALS['TheBest'] = array("PROPERTY_90_VALUE"=>"Лучшее");
?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"top1", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "SYSTEM_IMAGES",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "top1",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "TheBest",
		"HIDE_NOT_AVAILABLE" => "L",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "catalogs",
		"LABEL_PROP" => "",
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_NOT_AVAILABLE_SERVICE" => "",
		"MESS_SHOW_MAX_QUANTITY" => "Наличие",
		"OFFERS_LIMIT" => "4",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "Для_сайта",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => "",
		"ROTATE_TIMER" => "30",
		"SECTION_URL" => "",
		"SEF_MODE" => "N",
		"SHOW_CLOSE_POPUP" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PAGINATION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"VIEW_MODE" => "SECTION"
	),
	false
);?>
		</div>
	</div>
	<div class="body__body">
		<div class="body__body-inner">
			<div class="body__head-text">
 <span class="good">
				Новинки </span>
			</div>
			 <?
	$GLOBALS['TheNew'] = array("PROPERTY_90_VALUE"=>"Новое");
?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"top1", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "SYSTEM_IMAGES",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "N",
		"COMPONENT_TEMPLATE" => "top1",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "TheNew",
		"HIDE_NOT_AVAILABLE" => "L",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "catalogs",
		"LABEL_PROP" => "",
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_NOT_AVAILABLE_SERVICE" => "",
		"MESS_SHOW_MAX_QUANTITY" => "Наличие",
		"OFFERS_LIMIT" => "4",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "Для_сайта",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => "",
		"ROTATE_TIMER" => "30",
		"SECTION_URL" => "",
		"SEF_MODE" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PAGINATION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"VIEW_MODE" => "SECTION"
	),
	false
);?>
		</div>
	</div>
	<div class="body__body">
		<div class="body__body-inner">
			<div class="body__head-text">
 <span class="good">
				Хиты </span>
			</div>
			 <?
	$GLOBALS['Trands'] = array("PROPERTY_90_VALUE"=>"Хит");
?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"top1", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "SYSTEM_IMAGES",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "N",
		"COMPONENT_TEMPLATE" => "top1",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "Trands",
		"HIDE_NOT_AVAILABLE" => "L",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "catalogs",
		"LABEL_PROP" => "",
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_NOT_AVAILABLE_SERVICE" => "",
		"MESS_SHOW_MAX_QUANTITY" => "Наличие",
		"OFFERS_LIMIT" => "4",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "Для_сайта",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => "",
		"ROTATE_TIMER" => "30",
		"SECTION_URL" => "",
		"SEF_MODE" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PAGINATION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"VIEW_MODE" => "SECTION"
	),
	false
);?>
		</div>
	</div>
	<div class="about" id="company">
		<div class="med_company-top">
			<div class="med_company">
				<div class="text_mover">
					<h1>О нас</h1>
					<h2>Медицинская Компания</h2>
					<h3>С 2018 года мы являемся официальными дистрибьюторами компании Dentsplay Sirona и MIS, а с 2019 года официально представляем продукцию компании Ivoclar. Преимуществами компании являются постоянное наличие наиболее востребованного ассортимента на складах, конкурентная цена, быстрая обработка и доставка заказов.</h3>
				</div>
 <img alt="negr_and_woman.jpg" src="/upload/medialibrary/85f/85f7721f88a3eeb4b48f48ca1b7185e0.jpg" title="" class="med_image1">
			</div>
		</div>
		<div class="med_company-bot">
			<div class="med_company">
 <img alt="teeth.jpg" src="/upload/medialibrary/fdb/fdb12c5253dc91c886b50e6f94e34157.jpg" title="" class="med_image2">
				<div class="text_mover-1">
					<h1>За 10 лет безупречной работы, компания зарекомендовала себя не только, как надежного поставщика материалов и оборудования, но и серьезного партнера.</h1>
					<h3>Мы всегда на волне новых тенденций и сотрудничаем с ведущими производителями и поставщиками стоматологических материалов: Dentsplay Sirona, Ivoclar, 3MESPE, DMG, Olident, Tokuyama Dental и т.д. Наш многолетний опыт работы на рынке Ярославской, Костромской и Ивановской областях продаж по продажам стоматологических материалов и оборудования поможет Вам в выборе стоматологической продукции.</h3>
				</div>
			</div>
		</div>
	</div>
</div>
 <br><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>