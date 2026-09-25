<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");
?><?$APPLICATION->IncludeComponent(
	"bazarow:favorites.list",
	"",
	Array(
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"DATE_FORMAT" => "d.m.Y",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "Y",
		"FAVORITES_COUNT" => "20",
		"FILTER_NAME" => "",
		"NAV_TEMPLATE" => "",
		"SET_TITLE" => "Y",
		"SORT_BY" => "DATE_INSERT",
		"SORT_ORDER" => "DESC"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>