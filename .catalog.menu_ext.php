<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php
/**
 * @var array $aMenuLinks
 */

global $APPLICATION;

$aMenuLinksExt=$APPLICATION->IncludeComponent("bitrix:menu.sections", "", array(
	"IS_SEF" => "Y",
		"SEF_BASE_URL" => "/catalog/",
		"SECTION_PAGE_URL" => "#SECTION_CODE#/",
		"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#",
		"IBLOCK_TYPE" => "catalogs",
		"IBLOCK_ID" => "16",
		"DEPTH_LEVEL" => "3",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"ID" => $_REQUEST["ID"],
		"SECTION_URL" => "//catalog/?SECTION_ID=#ID#",
		"USUAL" => "N",
		"ELEMENTS_ROOT" => "N",
		"ELEMENTS_SECTIONS" => "N",
		"ELEMENTS_COUNT" => "N"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
