<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("newstest");
?>

<?$APPLICATION->IncludeComponent(
    "seraphimpopov:news.detail",
    "",
    Array(
        "ELEMENT_ID" => $_REQUEST["ID"],
    )
);?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
