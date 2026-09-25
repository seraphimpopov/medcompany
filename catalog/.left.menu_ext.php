<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?><?php
/**
 * @var array $aMenuLinks
 */

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
	"bitrix:menu.sections", 
	"", 
	array(
		"IS_SEF" => "Y",
		"SEF_BASE_URL" => "/catalog/",
		"SECTION_PAGE_URL" => "#SECTION_CODE#/",
		"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#",
		"IBLOCK_TYPE" => "catalogs",
		"IBLOCK_ID" => "16",
		"DEPTH_LEVEL" => "3",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"ID" => $_REQUEST["ID"],
		"SECTION_URL" => "/catalog/?SECTION_ID=#ID#"
	),
	false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);