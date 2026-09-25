<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.services", 
	"template.4", 
	array(
		"IBLOCK_TYPE" => "#SERVICES_IBLOCK_TYPE#",
		"IBLOCK_ID" => "#SERVICES_IBLOCK_ID#",
		"ELEMENTS_COUNT" => "4",
		"PROPERTY_ICON" => "",
		"HEADER_BLOCK_SHOW" => "N",
		"DESCRIPTION_BLOCK_SHOW" => "N",
		"DESCRIPTION_SHOW" => "Y",
		"ICON_SHOW" => "Y",
		"NUMBER_SHOW" => "Y",
		"DETAIL_SHOW" => "Y",
		"DETAIL_TEXT" => "Подробнее",
		"SEE_ALL_SHOW" => "N",
		"LIST_PAGE_URL" => "",
		"SECTION_URL" => "",
		"DETAIL_URL" => ""
	),
	false
); ?>