<?php define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");

?><div class="container">
 <?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"REGISTER_URL" => "",
		"FORGOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "personal.php",
		"SHOW_ERRORS" => "N"
	),
	false
);?></div><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>