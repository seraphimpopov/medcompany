<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Купить стоматологические товары в интернет магазине Медкомпания.рф");
$APPLICATION->SetPageProperty("keywords", "Vatech, рентгеновские аппараты, стоматологическое оборудование, купить рентген для стоматологии, Центральный федеральный округ, Москва, Московская область, Тверь, Тверская область, Калуга, Калужская область, Кострома, Костромская область, Владимир, Владимирская область, Ярославль, Ярославская область, Иваново, Ивановская область, Нижний новгород, Нижненовгородская область");
$APPLICATION->SetPageProperty("title", "Поиск стоматологических товаров — Медкомпания.рф");
$APPLICATION->SetPageProperty("robots", "noindex, follow");

$APPLICATION->SetTitle("Поиск товаров");

?><div class="container">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.search", 
	"search", 
	array(
		"ACTION_VARIABLE" => "action",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"COMPONENT_TEMPLATE" => "search",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "catalogs",
		"LINE_ELEMENT_COUNT" => "4",
		"NO_WORD_LOGIC" => "N",
		"OFFERS_LIMIT" => "6",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "12",
		"PRICE_CODE" => array(
			0 => "Розничная",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"RESTART" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SHOW_PRICE_COUNT" => "1",
		"USE_LANGUAGE_GUESS" => "Y",
		"USE_PRICE_COUNT" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"USE_SEARCH_RESULT_ORDER" => "Y",
		"USE_TITLE_RANK" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
</div>
 <br><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
