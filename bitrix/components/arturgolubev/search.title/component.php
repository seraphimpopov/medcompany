<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

use \Arturgolubev\Smartsearch\SearchComponent,
	\Arturgolubev\Smartsearch\Components\STitle,
	\Arturgolubev\Smartsearch\Encoding as Enc;

$ag_module = 'arturgolubev.smartsearch';

global $USER;

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

$arParams = SearchComponent::prepareSettings($arParams, 'title');

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arFILTERCustom = [];
else
{
	$arFILTERCustom = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arFILTERCustom))
		$arFILTERCustom = [];
}
$arResult["CATEGORIES"] = [];

$arResult["query"] = ltrim($_POST["q"]);
$arResult["query"] = \Bitrix\Main\Text\Encoding::convertEncodingToCurrent($arResult["query"]);

$arResult["FORM_ACTION"] = htmlspecialcharsbx(str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"]));

if(
	!empty($arResult["query"])
	&& $_REQUEST["ajax_call"] === "y"
	&& (
		!isset($_REQUEST["INPUT_ID"])
		|| $_REQUEST["INPUT_ID"] == $arParams["INPUT_ID"]
	)
)
{
	$smartcomponent = new SearchComponent($arResult["query"], 'title');
	$smartcomponent->setItemIdFilterMode('');
	
	// echo '<pre>'; print_r($smartcomponent); echo '</pre>';
	if($smartcomponent->getOption('engine') == 'sphinx' && !is_array($arParams['ORDER'])){
		if($arParams['ORDER'] == 'date'){
			$arParams['ORDER'] = 'date_change';
		}
	
		$arParams['ORDER'] = ['CUSTOM_RANK' => "DESC", $arParams['ORDER'] => "DESC"];
	}

	$arParams['MIN_LENGTH'] = $_REQUEST["l"];

	$arResult["VISUAL_PARAMS"] = [
		'THEME_CLASS' => 'theme-'.$smartcomponent->getOption('theme_class'),
		'THEME_COLOR' => $smartcomponent->getOption('theme_color'),
		'PLACEHOLDER' => $smartcomponent->getOption('theme_placeholder'),
	];

	$arResult["DEBUG"] = [
		'SHOW' => ($USER->IsAdmin()) ? $smartcomponent->getOption('debug') : 0,
		'main' => [
			'query_base' => $smartcomponent->baseQuery,
			'query_work' => $smartcomponent->query,
			'limit' => $arParams["TOP_COUNT"],
			'count' => 0,
		],
	];

	CUtil::decodeURIComponent($smartcomponent->baseQuery);

	$time_start = microtime(true); 

	for($i = 0; $i < $arParams["NUM_CATEGORIES"]; $i++)
	{
		$bCustom = true;
		if(is_array($arParams["CATEGORY_".$i])){
			foreach($arParams["CATEGORY_".$i] as $categoryCode){
				if ((strpos($categoryCode, 'custom_') !== 0)){
					$bCustom = false;
					break;
				}
			}
		}else{
			$bCustom = (strpos($arParams["CATEGORY_".$i], 'custom_') === 0);
		}

		if ($bCustom)
			continue;

		$category_title = trim($arParams["CATEGORY_".$i."_TITLE"]);
		if(empty($category_title)){
			if(is_array($arParams["CATEGORY_".$i]))
				$category_title = implode(", ", $arParams["CATEGORY_".$i]);
			else
				$category_title = trim($arParams["CATEGORY_".$i]);
		}
		
		if(empty($category_title))
			continue;

		if(true){
			$arResult["CATEGORIES"][$i] = [
				"TITLE" => htmlspecialcharsbx($category_title),
				"ITEMS" => []
			];

			$exFILTER = [
				0 => CSearchParameters::ConvertParamsToFilter($arParams, "CATEGORY_".$i),
			];
			$exFILTER[0]["LOGIC"] = "OR";

			if($arParams["CHECK_DATES"] === "Y")
				$exFILTER["CHECK_DATES"] = "Y";

			$correctionParams = STitle::makeCorrectionParams($exFILTER);
						
			if(!empty($arFILTERCustom))
				$exFILTER = array_merge($exFILTER, $arFILTERCustom);
			
			$subParams = [
				'query' => $smartcomponent->query,
				'base_query' => $smartcomponent->baseQuery,

				'min_length' => $arParams['MIN_LENGTH'],
				'max_count' => $arParams["TOP_COUNT"],
				'order' => $arParams["ORDER"],

				'guess' => $arParams["USE_LANGUAGE_GUESS"],
				'guess_plus' => $smartcomponent->getOption('use_guessplus'),
				
				'use_fixes' => $smartcomponent->getOption('use_fixes'),
				'always_use_fixes' => $arParams["ALWAYS_USE_SMART"],
				'disable_item_id_filter' => $smartcomponent->getOption('disable_item_id_filter'),
			];

			$findData = STitle::mainQuery($subParams, $exFILTER);

			if($findData['count']){
				foreach($findData['items'] as $item){
					$arResult["CATEGORIES"][$i]["ITEMS"][] = $item;
				}

				$arResult["DEBUG"]['main']["count"] += $findData['count'];
			}

			if($findData['correction_variants']){
				$arResult["DEBUG"]["correction_variants"] = $findData['correction_variants'];
			}

			$arResult['DEBUG']['TIMES']['base'] = $findData['times'];

			if($findData['debug']){
				$arResult['DEBUG']['CHECK'] = $findData['debug'];
			}

			if(!count($arResult["CATEGORIES"][$i]["ITEMS"])){
				unset($arResult["CATEGORIES"][$i]);
			}
		}
	}

	$subParams = [
		'query' => $smartcomponent->query,
		'base_query' => $smartcomponent->baseQuery,
		'min_length' => $arParams['MIN_LENGTH'],
		'page' => $arParams["PAGE"],

		'guess' => $arParams["USE_LANGUAGE_GUESS"],
		'guess_plus' => $smartcomponent->getOption('use_guessplus'),

		'use_fixes' => $smartcomponent->getOption('use_fixes'),
		'disable_item_id_filter' => $smartcomponent->getOption('disable_item_id_filter'),
	];
	$hintsResult = STitle::getHints($subParams);
	// $arResult['DEBUG']['HINTS'] = $hintsResult;

	if($hintsResult['count']){
		$arResult["CATEGORIES"] = array_merge([
			'HINTS' => [
				'TITLE' => '',
				'ITEMS' => $hintsResult['items'],
			]
		], $arResult["CATEGORIES"]);

		$arResult["DEBUG"]['main']["count_hints"] = $hintsResult['count'];
	}
	if(is_array($hintsResult['times']) && count($hintsResult['times'])){
		$arResult["DEBUG"]["TIMES"]["hints"] = $hintsResult['times'];
	}

	if($hintsResult['debug']){
		$arResult['DEBUG']['HINTS_CHECK'] = $hintsResult['debug'];
	}
	
	$arResult["CATEGORIES"] = STitle::replaceResultNames($arResult["CATEGORIES"]);

	$arResult["DEBUG"]["TIMES"]["full_work"] = round((microtime(true) - $time_start), 4);

	if(!empty($arResult["CATEGORIES"])){
		$arResult["CATEGORIES"]["all"] = [
			"TITLE" => "",
			"ITEMS" => [
				[
					"NAME" => Loc::getMessage("CC_BST_ALL_RESULTS"),
					"URL" => CHTTP::urlAddParams(
						str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"]),
						["q" => $arResult["query"]],
						["encode"=>true]
					),
				]
			]
		];
	}
	
	$arResult['CATEGORIES_ITEMS_EXISTS'] = false;
	foreach ($arResult["CATEGORIES"] as $category){
		if (!empty($category['ITEMS']) && is_array($category['ITEMS'])){
			$arResult['CATEGORIES_ITEMS_EXISTS'] = true;
			break;
		}
	}
}

if (
	$_REQUEST["ajax_call"] === "y"
	&& (
		!isset($_REQUEST["INPUT_ID"])
		|| $_REQUEST["INPUT_ID"] == $arParams["INPUT_ID"]
	)
)
{
	$APPLICATION->RestartBuffer();

	if(!empty($smartcomponent->baseQuery))
		$this->IncludeComponentTemplate('ajax');
	CMain::FinalActions();
	die();
}
else
{
	$APPLICATION->AddHeadScript($this->GetPath().'/script.js');
	CUtil::InitJSCore(['ajax']);
	$this->IncludeComponentTemplate();
}
?>
