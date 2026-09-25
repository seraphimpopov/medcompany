<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php") ?>
<?php

$APPLICATION->SetTitle("Магазины");

?>
<?php $APPLICATION->IncludeComponent(
	"bitrix:catalog.store", 
	".default", 
	array(
		"SETTINGS_USE" => "Y",
		"MAP_ID" => "",
		"MAP_TYPE" => "0",
		"MAP_OVERLAY" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/stores/",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"PHONE" => "Y",
		"SCHEDULE" => "Y",
		"SET_TITLE" => "Y",
		"TITLE" => "Магазины",
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_URL_TEMPLATES" => array(
			"liststores" => "index.php",
			"element" => "#store_id#",
		)
	),
	false
); ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>