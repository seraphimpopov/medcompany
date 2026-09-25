<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(false);

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

use \Arturgolubev\Smartsearch\SearchComponent;
use \Arturgolubev\Smartsearch\Encoding as Enc;

$ag_module = 'arturgolubev.smartsearch';

global $USER, $APPLICATION;

if(!Loader::includeModule("search")){
	if($USER->IsAdmin()){
		ShowError(Loc::getMessage("SEARCH_MODULE_UNAVAILABLE"));
	}
	return;
}

if(!Loader::includeModule($ag_module)){
	if($USER->IsAdmin()){
		ShowError(Loc::getMessage("ARTURGOLUBEV_SMARTSEARCH_MODULE_UNAVAILABLE"));
	}
	return;
}

// params
$arParams = SearchComponent::prepareSettings($arParams, 'page');

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

CRatingsComponentsMain::GetShowRating($arParams);

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arFILTERCustom = [];
else
{
	$arFILTERCustom = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arFILTERCustom))
		$arFILTERCustom = [];
}

$exFILTER = CSearchParameters::ConvertParamsToFilter($arParams, "arrFILTER");

//options
$tags = (isset($_REQUEST["tags"])) ? trim($_REQUEST["tags"]) : false;

if($arParams["SHOW_WHEN"] && isset($_REQUEST["from"]) && is_string($_REQUEST["from"]) && strlen($_REQUEST["from"]) && CheckDateTime($_REQUEST["from"]))
	$arResult["REQUEST"]["~FROM"] = $_REQUEST["from"];
else
	$arResult["REQUEST"]["~FROM"] = "";

$arResult["REQUEST"]["FROM"] = htmlspecialcharsbx($arResult["REQUEST"]["~FROM"]);

if($arParams["SHOW_WHEN"] && isset($_REQUEST["to"]) && is_string($_REQUEST["to"]) && strlen($_REQUEST["to"]) && CheckDateTime($_REQUEST["to"]))
	$arResult["REQUEST"]["~TO"] = $_REQUEST["to"];
else
	$arResult["REQUEST"]["~TO"] = "";

$arResult["REQUEST"]["TO"] = htmlspecialcharsbx($arResult["REQUEST"]["~TO"]);

$where = $arParams["SHOW_WHERE"]? trim($_REQUEST["where"]): "";

$arResult["REQUEST"]["HOW"] = trim($_REQUEST["how"]);
if($arResult["REQUEST"]["HOW"] == "d")
	$arResult["REQUEST"]["HOW"] = "d";
elseif($arResult["REQUEST"]["HOW"] == "r")
	$arResult["REQUEST"]["HOW"] = "";
elseif($arParams["DEFAULT_SORT"] == "date")
	$arResult["REQUEST"]["HOW"] = "d";
else
	$arResult["REQUEST"]["HOW"] = "";

if($arResult["REQUEST"]["HOW"]=="d")
	$aSort = ["CUSTOM_RANK"=>"DESC", "DATE_CHANGE"=>"DESC", "TITLE_RANK"=>"ASC"];
else
	$aSort = ["CUSTOM_RANK"=>"DESC", "TITLE_RANK"=>"ASC"];

$arrDropdown = [];

$obCache = new CPHPCache;
if($obCache->StartDataCache($arParams["CACHE_TIME"], $this->GetCacheID(), "/".SITE_ID.$this->GetRelativePath())){
	$arIBlockTypes = [];
	if(Loader::includeModule("iblock")){
		$rsIBlockType = CIBlockType::GetList(["sort"=>"asc"], ["ACTIVE"=>"Y"]);
		while($arIBlockType = $rsIBlockType->Fetch())
		{
			if($ar = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID))
				$arIBlockTypes[$arIBlockType["ID"]] = $ar["~NAME"];
		}
	}

	foreach($arParams["arrWHERE"] as $code){
		list($module_id, $part_id) = explode("_", $code, 2);
		if(strlen($module_id)>0)
		{
			if(strlen($part_id)<=0)
			{
				switch($module_id)
				{
					case "forum":
						$arrDropdown[$code] = Loc::getMessage("SEARCH_FORUM");
						break;
					case "blog":
						$arrDropdown[$code] = Loc::getMessage("SEARCH_BLOG");
						break;
					case "socialnetwork":
						$arrDropdown[$code] = Loc::getMessage("SEARCH_SOCIALNETWORK");
						break;
					case "intranet":
						$arrDropdown[$code] = Loc::getMessage("SEARCH_INTRANET");
						break;
					case "crm":
						$arrDropdown[$code] = Loc::getMessage("SEARCH_CRM");
						break;
					case "disk":
						$arrDropdown[$code] = Loc::getMessage("SEARCH_DISK");
						break;
				}
			}
			else
			{
				// if there is additional information specified besides ID then
				switch($module_id)
				{
					case "iblock":
						if(isset($arIBlockTypes[$part_id]))
							$arrDropdown[$code] = $arIBlockTypes[$part_id];
						break;
				}
			}
		}
	}
	$obCache->EndDataCache($arrDropdown);
}else{
	$arrDropdown = $obCache->GetVars();
}

$arResult["DROPDOWN"] = htmlspecialcharsex($arrDropdown);

$smartcomponent = new SearchComponent(trim($_REQUEST["q"]), 'page');
$smartcomponent->setItemIdFilterMode($arParams["DISABLE_ITEM_ID_FILTER"]);
$smartcomponent->setTitle();

$arResult["DEBUG"] = [];
$arResult["DEBUG"]["TYPE"] = Loc::getMessage("SEARCH_DEBUG_TYPE_BASE");
$arResult["DEBUG"]["TOP_COUNT"] = $arParams["PAGE_RESULT_COUNT"]; 
$arResult["DEBUG"]["RES_COUNT"] = 0; 

$arResult["VISUAL_PARAMS"] = [];
$arResult["VISUAL_PARAMS"]["THEME_CLASS"] = 'theme-'.$smartcomponent->getOption('theme_class');
$arResult["VISUAL_PARAMS"]["THEME_COLOR"] = $smartcomponent->getOption('theme_color');
$arResult["VISUAL_PARAMS"]["CLARIFY_SECTION"] = $smartcomponent->getOption('use_clarify');

global $USER; if($USER->IsAdmin()){
	$arResult["DEBUG"]["SHOW"] = $smartcomponent->getOption('debug');
}

if($smartcomponent->baseQuery != ''){
	\Arturgolubev\Smartsearch\Tools::setSearchHistory($smartcomponent->baseQuery, IntVal($arParams["HISTORY_COUNT"]));
	
	$arResult["REQUEST"]["~QUERY"] = $smartcomponent->baseQuery;
	$arResult["REQUEST"]["QUERY"] = htmlspecialcharsex($smartcomponent->baseQuery);
	
	$q = $smartcomponent->query;
	
	CArturgolubevSmartsearch::checkRedirectRules(SITE_ID, $smartcomponent->baseQuery);
}
else
{
	$arResult["REQUEST"]["QUERY"] = $arResult["REQUEST"]["~QUERY"] = false;
}

if($tags!==false)
{
	$arResult["REQUEST"]["~TAGS_ARRAY"] = [];
	$arTags = explode(",", $tags);
	foreach($arTags as $tag)
	{
		$tag = trim($tag);
		if(strlen($tag) > 0)
			$arResult["REQUEST"]["~TAGS_ARRAY"][$tag] = $tag;
	}
	$arResult["REQUEST"]["TAGS_ARRAY"] = htmlspecialcharsex($arResult["REQUEST"]["~TAGS_ARRAY"]);
	$arResult["REQUEST"]["~TAGS"] = implode(",", $arResult["REQUEST"]["~TAGS_ARRAY"]);
	$arResult["REQUEST"]["TAGS"] = htmlspecialcharsex($arResult["REQUEST"]["~TAGS"]);
}else{
	$arResult["REQUEST"]["TAGS_ARRAY"] = $arResult["REQUEST"]["~TAGS_ARRAY"] = [];
	$arResult["REQUEST"]["TAGS"] = $arResult["REQUEST"]["~TAGS"] = false;
}

$arResult["REQUEST"]["WHERE"] = htmlspecialcharsbx($where);

$arResult["URL"] = $APPLICATION->GetCurPage()
	."?q=".urlencode($q)
	.(isset($_REQUEST["spell"])? "&amp;spell=1": "")
	."&amp;where=".urlencode($where)
	.($tags!==false? "&amp;tags=".urlencode($tags): "")
;

$arResult["ERROR_CODE"] = 0;
$arResult["ERROR_TEXT"] = '';

$templatePage = "";
$arResult["arReturn"] = false;
if($this->InitComponentTemplate($templatePage))
{
	$template = &$this->GetTemplate();
	$smartcomponent->folderPath = $arResult["FOLDER_PATH"] = $template->GetFolder();
	
	if(strlen($arResult["FOLDER_PATH"]) > 0)
	{
		$arResult["FULL_CNT"] = 0;
		$arResult["SEARCH"] = [];
		$arResult["arReturn"] = [];
		
		$arSearchOpt = [
			"ERROR_ON_EMPTY_STEM" => 1,
			"NO_WORD_LOGIC" => 0
		];
		
		$obSearch = new CSearchExt();
		$obSearch->SetOptions($arSearchOpt);
		
		$arFilter = [
			"SITE_ID" => SITE_ID,
			"QUERY" => $tmp,
			"TAGS" => $arResult["REQUEST"]["~TAGS"],
		];
		
		$arFilter = array_merge($arFILTERCustom, $arFilter);
		if(strlen($where)>0)
		{
			list($module_id, $part_id) = explode("_",$where,2);
			$arFilter["MODULE_ID"] = $module_id;
			if(strlen($part_id)>0) $arFilter["PARAM1"] = $part_id;
		}
		if($arParams["CHECK_DATES"])
			$arFilter["CHECK_DATES"]="Y";

		if($arResult["REQUEST"]["~FROM"])
			$arFilter[">=DATE_CHANGE"] = $arResult["REQUEST"]["~FROM"];

		if($arResult["REQUEST"]["~TO"])
			$arFilter["<=DATE_CHANGE"] = $arResult["REQUEST"]["~TO"];
		
		$correctionParams = [
			'type' => 'full',
			'filter' => []
		];
		
		foreach($exFILTER as $flt){
			if(is_array($flt)){
				$tmp = [];
				if($flt['=MODULE_ID']){
					$tmp['MODULE_ID'] = $flt['=MODULE_ID'];
				}
				if($flt['PARAM1']){
					$tmp['PARAM1'] = $flt['PARAM1'];
				}
				if(is_array($flt['PARAM2']) && count($flt['PARAM2'])){
					$tmp['PARAM2'] = $flt['PARAM2'];
				}
				if(count($tmp)){
					$correctionParams['filter'][] = $tmp;
				}
			}
		}
				
		// base-1
		if($q)
			$arFilter["QUERY"] = $q;
		else
			$arFilter["QUERY"] = false;
		
		$time_start = microtime(true); 
				
		if(($smartcomponent->getOption('use_stemming') || $smartcomponent->getOption('mode') == 'standart') || !$arFilter["QUERY"])
		{
			SearchComponent::addWords($arFilter["QUERY"]);

			$cnt = 0;
			$time_start2 = microtime(true); 
			$obSearch->Search($arFilter, $aSort, $exFILTER);
			if($obSearch->errorno==0){
				$obSearch->NavStart($arParams["PAGE_RESULT_COUNT"], false);
				while($ar = $obSearch->GetNext())
				{
					$arResult["arReturn"][$ar["ID"]] = $ar["ITEM_ID"];
					$arResult["FULL_CNT"]++;
					
					$ar = $smartcomponent->searchRowPrepare($ar);
					$arResult["SEARCH"][]=$ar;
					$cnt++;
				}
			}else{
				$arResult["ERROR_CODE"] = $obSearch->errorno;
				$arResult["ERROR_TEXT"] = $obSearch->error;
			}
			
			$arResult["DEBUG"]["Q"][] = $arFilter["QUERY"] . ' (time: '.round((microtime(true) - $time_start2), 4).', cnt: '.$cnt.')';
		}
		// end base-1
		
		// base-2
		if($q && $smartcomponent->getOption('mode') != 'standart' && $arParams["MAX_SEARCH_COUNT"] > $arResult["FULL_CNT"])
		{
			$arFilter["QUERY"] = $smartcomponent->addQuerySymbols($q);

			if(!empty($arResult["arReturn"]) && !$smartcomponent->getOption('disable_item_id_filter')) $arFilter["!=ITEM_ID"] = array_values($arResult["arReturn"]);
			
			$cnt = 0;
			$time_start2 = microtime(true); 
			$obSearch->Search($arFilter, $aSort, $exFILTER);
			if($obSearch->errorno==0){
				$obSearch->NavStart($arParams["PAGE_RESULT_COUNT"], false);
				while($ar = $obSearch->GetNext())
				{
					if($arParams["MAX_SEARCH_COUNT"] <= $arResult["FULL_CNT"]) break;
					if(isset($arResult["arReturn"][$ar["ID"]])) continue;
					
					$arResult["arReturn"][$ar["ID"]] = $ar["ITEM_ID"];
					$arResult["FULL_CNT"]++;
					
					$ar = $smartcomponent->searchRowPrepare($ar);
					$arResult["SEARCH"][]=$ar;
					$cnt++;
				}
			}else{
				$arResult["ERROR_CODE"] = $obSearch->errorno;
				$arResult["ERROR_TEXT"] = $obSearch->error;
			}
			
			$arResult["DEBUG"]["Q"][] = $arFilter["QUERY"] . ' (time: '.round((microtime(true) - $time_start2), 4).', cnt: '.$cnt.')';
		}
		// end base-2
		
		if(empty($arResult["arReturn"]) && $arParams["USE_LANGUAGE_GUESS"] == "Y" && !isset($_REQUEST["spell"])){
			$guessQuery = SearchComponent::getGuessQuery($arResult["REQUEST"]["~QUERY"], $correctionParams);

			if($guessQuery){
				$arFilter["QUERY"] = $guessQuery;
				SearchComponent::addWords($arFilter["QUERY"]);
				
				if(!empty($arResult["arReturn"]) && !$smartcomponent->getOption('disable_item_id_filter')) $arFilter["!=ITEM_ID"] = array_values($arResult["arReturn"]);
				
				$cnt = 0;
				$time_start2 = microtime(true); 
				$obSearch->Search($arFilter, $aSort, $exFILTER);
				if($obSearch->errorno==0)
				{
					$obSearch->NavStart($arParams["PAGE_RESULT_COUNT"], false);
					while($ar = $obSearch->GetNext())
					{
						if($arParams["MAX_SEARCH_COUNT"] <= $arResult["FULL_CNT"]) break;
						if(isset($arResult["arReturn"][$ar["ID"]])) continue;
				
						$arResult["arReturn"][$ar["ID"]] = $ar["ITEM_ID"];
						$arResult["FULL_CNT"]++;
						
						$ar = $smartcomponent->searchRowPrepare($ar);
						$arResult["SEARCH"][]=$ar;
						$cnt++;
					}
				}
				$arResult["DEBUG"]["Q"][] = $arFilter["QUERY"] . ' (time: '.round((microtime(true) - $time_start2), 4).', cnt: '.$cnt.')';
				
				if(count($arResult["SEARCH"]) > 0 && $smartcomponent->getOption('use_guessplus') && $smartcomponent->getOption('use_stemming') && $smartcomponent->getOption('mode') != 'standart'){
					$arFilter["QUERY"] = $smartcomponent->addQuerySymbols($guessQuery);
					if(!empty($arResult["arReturn"]) && !$smartcomponent->getOption('disable_item_id_filter')) $arFilter["!=ITEM_ID"] = array_values($arResult["arReturn"]);
					
					$cnt = 0;
					$time_start2 = microtime(true); 
					$obSearch->Search($arFilter, $aSort, $exFILTER);
					if($obSearch->errorno==0)
					{
						$obSearch->NavStart($arParams["PAGE_RESULT_COUNT"], false);
						while($ar = $obSearch->GetNext())
						{
							if($arParams["MAX_SEARCH_COUNT"] <= $arResult["FULL_CNT"]) break;
							if(isset($arResult["arReturn"][$ar["ID"]])) continue;
					
							$arResult["arReturn"][$ar["ID"]] = $ar["ITEM_ID"];
							$arResult["FULL_CNT"]++;
							
							$ar = $smartcomponent->searchRowPrepare($ar);
							$arResult["SEARCH"][]=$ar;
							$cnt++;
						}
					}
					$arResult["DEBUG"]["Q"][] = $arFilter["QUERY"] . ' (time: '.round((microtime(true) - $time_start2), 4).', cnt: '.$cnt.')';
				}
				
				if(count($arResult["SEARCH"]) > 0)
				{
					$arResult["REQUEST"]["~ORIGINAL_QUERY"] = $arResult["REQUEST"]["~QUERY"];
					$arResult["REQUEST"]["ORIGINAL_QUERY"] = htmlspecialcharsex($arResult["REQUEST"]["~QUERY"]);
					
					$arResult["REQUEST"]["~QUERY"] = $guessQuery;
					$arResult["REQUEST"]["QUERY"] = $guessQuery;
					
					$arResult["DEBUG"]["TYPE"] = Loc::getMessage("SEARCH_DEBUG_TYPE_KEY");
				}
			}
		}

		if(!empty($arResult["arReturn"]) && $arResult["REQUEST"]["HOW"] != "d"){
			$arResult = SearchComponent::reOrderFullResults($arResult);
		}
		
		if ($smartcomponent->getOption('use_fixes') && $q && (empty($arResult["arReturn"]) || $arParams["ALWAYS_USE_SMART"] == 'Y'))
		{
			$time_start2 = microtime(true); 
			$arLavelsWords = CArturgolubevSmartsearch::getSimilarWordsList($q, 'full', $correctionParams);
			$arResult["DEBUG"]["TYPE"] = Loc::getMessage("SEARCH_DEBUG_TYPE_SMART").' ('.$smartcomponent->getOption('mode').')';
			
			if(empty($arLavelsWords) && $smartcomponent->getOption('use_guessplus') && $guessQuery){
				$arLavelsWords = CArturgolubevSmartsearch::getSimilarWordsList($guessQuery, 'full', $correctionParams);
				$arResult["DEBUG"]["TYPE"] = Loc::getMessage("SEARCH_DEBUG_TYPE_SMART").' ('.$smartcomponent->getOption('mode').' + guess)';
			}
			
			$arResult["DEBUG"]["RESULT_WORDS"] = $arLavelsWords;
			$arResult["DEBUG"]["TIMES"]["SIMILARITY"] = round((microtime(true) - $time_start2), 4);
			
			// echo '<pre>arLavelsWords'; print_r($arLavelsWords); echo '</pre>';
			if(!empty($arLavelsWords))
			{
				foreach($arLavelsWords as $level=>$searchArray)
				{
					foreach($searchArray as $searchIteration){
						if(CArturgolubevSmartsearch::checkMatrixLineEmpty($searchIteration)){
							$arResult["DEBUG"]["Q"][] = $searchIteration . ' (skip)';
							continue;
						}
						
						$arFilter["QUERY"] = $searchIteration;
						SearchComponent::addWords($arFilter["QUERY"]);
						
						if(!empty($arResult["arReturn"]) && !$smartcomponent->getOption('disable_item_id_filter')) $arFilter["!=ITEM_ID"] = array_values($arResult["arReturn"]);
						
						$cnt = 0;
						$time_start2 = microtime(true); 
						$obSearch->Search($arFilter, $aSort, $exFILTER);
						if($obSearch->errorno==0)
						{
							$obSearch->NavStart($arParams["PAGE_RESULT_COUNT"], false);
							while($ar = $obSearch->GetNext())
							{
								if($arParams["MAX_SEARCH_COUNT"] <= $arResult["FULL_CNT"]) break;
								if(isset($arResult["arReturn"][$ar["ID"]])) continue;
						
								$arResult["arReturn"][$ar["ID"]] = $ar["ITEM_ID"];
								$arResult["FULL_CNT"]++;
								
								$ar = $smartcomponent->searchRowPrepare($ar);
								$arResult["SEARCH"][]=$ar;
								$cnt++;
							}
						}
						
						CArturgolubevSmartsearch::saveMatrixLineEmpty($searchIteration, $cnt);
						
						$arResult["DEBUG"]["Q"][] = $arFilter["QUERY"] . ' (time: '.round((microtime(true) - $time_start2), 4).', cnt: '.$cnt.')';
					}

					if (!empty($arResult["arReturn"])) {
						break;
					}
				}

				if($smartcomponent->getOption('engine') == 'sphinx' && !empty($arResult["arReturn"]) && $arResult["REQUEST"]["HOW"] != "d"){
					$arResult = SearchComponent::reOrderFullResults($arResult);
				}
			}
		}
		
		$arResult["DEBUG"]["RES_COUNT"] = $arResult["FULL_CNT"];
		$arResult["DEBUG"]["TIMES"]["FULL"] = round((microtime(true) - $time_start), 4);
		
		if(isset($arResult["REQUEST"]["~ORIGINAL_QUERY"]))
		{
			$arResult["ORIGINAL_QUERY_URL"] = $APPLICATION->GetCurPage()
				."?q=".urlencode($arResult["REQUEST"]["~ORIGINAL_QUERY"])
				."&amp;spell=1"
				."&amp;where=".urlencode($arResult["REQUEST"]["WHERE"])
				.($arResult["REQUEST"]["HOW"]=="d"? "&amp;how=d": "")
				.($arResult["REQUEST"]["FROM"]? '&amp;from='.urlencode($arResult["REQUEST"]["~FROM"]): "")
				.($arResult["REQUEST"]["TO"]? '&amp;to='.urlencode($arResult["REQUEST"]["~TO"]): "")
				.($tags!==false? "&amp;tags=".urlencode($tags): "")
			;
		}
		
		if(Loader::includeModule("iblock") && $arResult["VISUAL_PARAMS"]["CLARIFY_SECTION"] && count($arResult["arReturn"]) > 1){
			$arResult["SELECTED_SECTION"] = IntVal($_REQUEST["section"]);
			$arResult["CLARIFY_SECTION"] = $smartcomponent->getClarify(array_values($arResult["arReturn"]));
			$arResult["arReturn"] = $smartcomponent->applyClarify(array_values($arResult["arReturn"]), $arResult["SELECTED_SECTION"]);
		}
		
		if(count($arResult["arReturn"])>0)
		{
			$arResult["INFORMATION"] = CArturgolubevSmartsearch::getRealElementsName($arResult["arReturn"]);
			
			if(!empty($arResult["INFORMATION"])){
				foreach($arResult["SEARCH"] as $key => $searchinfo){
					if($searchinfo['MODULE_ID'] != 'iblock') continue;
					
					if($arResult["SELECTED_SECTION"]){
						if(!in_array($searchinfo["ITEM_ID"], $arResult["arReturn"])){
							unset($arResult["SEARCH"][$key]);
							continue;
						}
					}
					
					$newTitle = $arResult["INFORMATION"][$searchinfo["ITEM_ID"]]["NAME"];
					if($newTitle)
					{
						$arResult["SEARCH"][$key]["TITLE_S"] = $arResult["SEARCH"][$key]["TITLE"];
						$arResult["SEARCH"][$key]["TITLE_SF"] = $arResult["SEARCH"][$key]["TITLE_FORMATED"];
						
						$arResult["SEARCH"][$key]["TITLE"] = $newTitle;
						$arResult["SEARCH"][$key]["TITLE_FORMATED"] = CArturgolubevSmartsearch::formatElementName($arResult["SEARCH"][$key]["TITLE_FORMATED"], $newTitle);
					}
				}
			}
			
			unset($arResult["INFORMATION"]);
		}
		
		if($arResult["REQUEST"]["HOW"]=="d" && is_array($arResult["SEARCH"]) && count($arResult["SEARCH"])){
			foreach($arResult["SEARCH"] as $k=>$item){
				$arResult["SEARCH"][$k]["TIME_DATE_CHANGE"] = strtotime($item["FULL_DATE_CHANGE"]);
			}
			
			usort($arResult["SEARCH"], function ($a, $b){
				if ($a["TIME_DATE_CHANGE"] == $b["TIME_DATE_CHANGE"]) {
					return 0;
				}
				return ($a["TIME_DATE_CHANGE"] > $b["TIME_DATE_CHANGE"]) ? -1 : 1;
			});
		}
		
		if($arParams["NEED_PAGER"])
		{
			$time_start2 = microtime(true); 
			
			// $rs_ObjectList = new CDBResult;
			$rs_ObjectList = new CSearchExt;
			$rs_ObjectList->InitFromArray($arResult["SEARCH"]);
			$rs_ObjectList->NavStart($arParams["PAGER_SIZE"], false);
			$arResult["NAV_STRING"] = $rs_ObjectList->GetPageNavStringEx($navComponentObject,  $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], ($arParams["PAGER_SHOW_ALWAYS"] != "N"));
			$arResult["PAGE_START"] = $rs_ObjectList->SelectedRowsCount() - ($rs_ObjectList->NavPageNomer - 1) * $rs_ObjectList->NavPageSize;
			
			$arResult["SEARCH"] = [];
			while($ar_Field = $rs_ObjectList->Fetch()){
				$arResult["SEARCH"][] = $ar_Field;
			}
			
			$page_num = $rs_ObjectList->NavPageNomer;
			$arResult["NAV_RESULT"] = $rs_ObjectList;
			
			$arResult["DEBUG"]["TIMES"]["PAGER"] = round((microtime(true) - $time_start2), 4);
		}else{
			if(count($arResult["SEARCH"]) > 0)
				$page_num = 1;
			else
				$page_num = 0;
		}

		if ($arResult["REQUEST"]["QUERY"] && COption::GetOptionString("search", "stat_phrase") == "Y")
		{
			$statistic = new CSearchStatistic($arResult["REQUEST"]["QUERY"]);
			$statistic->PhraseStat(count($arResult["arReturn"]), $page_num);
			if ($statistic->phrase_id){
				$arResult["sphrase_id"] = $statistic->phrase_id;
			}
		}

		$arResult["TAGS_CHAIN"] = [];
		$url = [];
		foreach ($arResult["REQUEST"]["~TAGS_ARRAY"] as $key => $tag)
		{
			$url_without = $arResult["REQUEST"]["~TAGS_ARRAY"];
			unset($url_without[$key]);
			$url[$tag] = $tag;
			$result = [
				"TAG_NAME" => $tag,
				"TAG_PATH" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url)), ["tags"]),
				"TAG_WITHOUT" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url_without)), ["tags"]),
			];
			$arResult["TAGS_CHAIN"][] = $result;
		}
		
		if($arResult['ERROR_CODE']){
			$arResult["DEBUG"]['ERROR'] = [
				'CODE' => $arResult["ERROR_CODE"],
				'TEXT' => $arResult["ERROR_TEXT"],
			];
		}

		if($arResult["DEBUG"]["SHOW"] == 'Y')
		{
			echo '<pre>Debug Info: '; print_r($arResult["DEBUG"]); echo '</pre>';
			
			if(false)
			{
				foreach($arResult["SEARCH"] as $arItem){
					?>
					<div class="">
						<?echo '<pre>'; print_r($arItem["TITLE_SF"].' ('.$arItem["TITLE"].') ['.$arItem["ITEM_ID"].']'); echo '</pre>';?>
						<?echo '<pre>'; print_r('CUSTOM_RANK = '.$arItem["CUSTOM_RANK"].'; TITLE_RANK = '.$arItem["TITLE_RANK"]); echo '</pre>';?>
					</div>
					<?
				}
			}
		}

		$this->ShowComponentTemplate();
	}
}
else
{
	$this->__ShowError(str_replace("#PAGE#", $templatePage, str_replace("#NAME#", $this->__templateName, "Can not find '#NAME#' template with page '#PAGE#'")));
}
return $arResult["arReturn"];
?>
