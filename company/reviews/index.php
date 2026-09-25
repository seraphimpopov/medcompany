<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Отзывы на сайте Медкомпания.рф");
$APPLICATION->SetPageProperty("title", "Отзывы | Медкомпания.рф ");

$APPLICATION->SetTitle("Отзывы");

?>
<?php $APPLICATION->IncludeComponent(
    "intec.universe:main.reviews",
    "template.4",
    array(
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => "39",
        "ELEMENTS_COUNT" => "20",
        "SECTIONS_MODE" => "id",
        "SECTIONS" => array(),
        "SETTINGS_USE" => "Y",
        "LAZYLOAD_USE" => "N",
        "PROPERTY_POSITION" => "",
        "HEADER_SHOW" => "N",
        "DESCRIPTION_SHOW" => "N",
        "LINK_USE" => "N",
        "FOOTER_SHOW" => "N",
        "LIST_PAGE_URL" => "",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600000",
        "SORT_BY" => "SORT",
        "ORDER_BY" => "ASC"
    ),
    false
); ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>