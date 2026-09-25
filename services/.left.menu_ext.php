<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php
/**
 * @var array $aMenuLinks
 */

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
	"intec.universe:menu.sections", 
	"", 
	array(
		"IS_SEF" => "Y",
		"SEF_BASE_URL" => "/services/",
		"SECTION_PAGE_URL" => "#SECTION_CODE#/",
		"DETAIL_PAGE_URL" => "#SECTION_CODE#/#CODE#/",
		"IBLOCK_TYPE" => "#CATALOGS_SERVICES_IBLOCK_TYPE#",
		"IBLOCK_ID" => "20",
		"DEPTH_LEVEL" => "4",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"ID" => $_REQUEST["ID"],
		"SECTION_URL" => "/services/?SECTION_ID=#ID#",
		"USUAL" => "N",
		"ELEMENTS_ROOT" => "N",
		"ELEMENTS_SECTIONS" => "Y",
		"ELEMENTS_COUNT" => "N"
	),
	false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
