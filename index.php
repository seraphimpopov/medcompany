<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Медкомпания.рф — ваш надежный партнер в области стоматологии. Мы специализируемся на продаже высококачественных стоматологических товаров и оборудования, включая зубные инструменты, стоматологические материалы и ортодонтические изделия.");
$APPLICATION->SetPageProperty("keywords", "стоматологические товары в ярославле, стоматологическое оборудование, зубные инструменты, стоматологические материалы, профессиональные зубные щётки, ортодонтические изделия, стоматологическая продукция, стоматологические средства, товары для стоматологов, оборудование для стоматологии, средства для ухода за зубами, дентальные инструменты, стоматологические расходники, медицинское оборудование для стоматологии, покупки стоматологических товаров онлайн,в ярославле");
$APPLICATION->SetPageProperty("title", "МК Ярославль");
$APPLICATION->SetTitle("Интернет-магазин \"МК Ярославль\"");
?>
    <div class="container ls1">
        <div>
            <div class="row">
                <div class="col-xl-3 col-md-3 catalog">
                    <div class="sum_cat">
                        <a href="/catalog/" class="cat">Каталог</a>
                        <ul class="bottom__nav-list">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "left_menu",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "COMPONENT_TEMPLATE" => "left_menu",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "top",
                                    "USE_EXT" => "Y"
                                )
                            ); ?>
                        </ul>
                        <a href="/manufacturers/" class="cat">Каталог производителей</a>
                        <ul class="bottom__nav-list">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom_menu",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "COMPONENT_TEMPLATE" => "left_menu",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "top",
                                    "USE_EXT" => "Y"
                                )
                            ); ?>
                        </ul>
                    </div>
                    <div style="margin-top: 20px">
                        <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"newlist1", 
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
		"CHECK_DATES" => "N",
		"COMPONENT_TEMPLATE" => "newlist1",
		"DETAIL_URL" => "#SITE_DIR#/blog/?ELEMENT_ID=#ELEMENT_ID#",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "40",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
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
			0 => "",
			1 => "HEADER",
			2 => "DESCRIPTION",
			3 => "LINK",
			4 => "BUTTON_SHOW",
			5 => "BANNER_COLOR",
			6 => "TITLE_TEXT_COLOR",
			7 => "",
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
		"STRICT_SECTION_CHECK" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
                    </div>
                </div>
                <div class="col-xl-9 col-md-9 col-xs-12">
                    <? $APPLICATION->IncludeComponent(
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
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "newlist",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
); ?>
                    <div class="body__body">
                        <div class="body__body-inner">
                            <?
                                                        if (!function_exists('mkHomeProductIds')) {
                                // Tagged products first, then filled up to $count with in-stock items that have a picture and a price.
                                function mkHomeProductIds($tag, $count, $fillOrder)
                                {
                                    $cache = new CPHPCache();
                                    $cacheId = 'mk_home_' . md5($tag . $count . $fillOrder);
                                    if ($cache->InitCache(3600, $cacheId, '/mk_home_products')) {
                                        return $cache->GetVars();
                                    }
                                    $cache->StartDataCache();
                                    $base = array(
                                        'IBLOCK_ID' => 16, 'ACTIVE' => 'Y', 'CATALOG_AVAILABLE' => 'Y', '>CATALOG_PRICE_3' => 0,
                                        array('LOGIC' => 'OR', '!PREVIEW_PICTURE' => false, '!DETAIL_PICTURE' => false),
                                    );
                                    $ids = array();
                                    $res = CIBlockElement::GetList(array('SORT' => 'ASC', 'ID' => 'DESC'), array_merge($base, array('PROPERTY_90_VALUE' => $tag)), false, array('nTopCount' => $count), array('ID'));
                                    while ($row = $res->Fetch()) {
                                        $ids[] = (int)$row['ID'];
                                    }
                                    if (count($ids) < $count) {
                                        $fill = $base;
                                        if ($ids) {
                                            $fill['!ID'] = $ids;
                                        }
                                        $res = CIBlockElement::GetList(array($fillOrder => 'DESC', 'ID' => 'DESC'), $fill, false, array('nTopCount' => $count - count($ids)), array('ID'));
                                        while ($row = $res->Fetch()) {
                                            $ids[] = (int)$row['ID'];
                                        }
                                    }
                                    if (!$ids) {
                                        $ids = array(0);
                                    }
                                    $cache->EndDataCache($ids);
                                    return $ids;
                                }
                            }
                            CModule::IncludeModule('iblock');
                            CModule::IncludeModule('catalog');
                            $GLOBALS['TheBest'] = array("ID" => mkHomeProductIds("Лучшее", 6, "SHOW_COUNTER"));
                            ?><? $APPLICATION->IncludeComponent(
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
                                    "ELEMENT_COUNT" => "6",
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
                                    "LABEL_PROP" => array(),
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
                                    "PRICE_CODE" => array(0 => "Для_Сайта", 1 => "Розничная",),
                                    "PRICE_VAT_INCLUDE" => "Y",
                                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                                    "PRODUCT_ID_VARIABLE" => "id",
                                    "PRODUCT_PROPERTIES" => array(),
                                    "PRODUCT_PROPS_VARIABLE" => "prop",
                                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
                                    "PRODUCT_SUBSCRIPTION" => "Y",
                                    "PROPERTY_CODE" => array(0 => "CML2_ARTICLE", 1 => "",),
                                    "PROPERTY_CODE_MOBILE" => array(),
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
                                )
                            ); ?>
                        </div>
                    </div>
                    <div class="body__body">
                        <div class="body__body-inner">
                            <?
                            $GLOBALS['TheNew'] = array("ID" => mkHomeProductIds("Новое", 6, "DATE_CREATE"));
                            ?><? $APPLICATION->IncludeComponent(
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
                                    "ELEMENT_COUNT" => "6",
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
                                    "LABEL_PROP" => array(),
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
                                    "PRICE_CODE" => array(0 => "Для_Сайта", 1 => "Розничная",),
                                    "PRICE_VAT_INCLUDE" => "Y",
                                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                                    "PRODUCT_ID_VARIABLE" => "id",
                                    "PRODUCT_PROPERTIES" => array(),
                                    "PRODUCT_PROPS_VARIABLE" => "prop",
                                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
                                    "PRODUCT_SUBSCRIPTION" => "Y",
                                    "PROPERTY_CODE" => array(0 => "CML2_ARTICLE", 1 => "",),
                                    "PROPERTY_CODE_MOBILE" => array(),
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
                                )
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>