<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

// Проверка на наличие значения свойства ATT_TOVAR
if (!empty($arResult["PROPERTIES"]["ATT_TOVAR"]["VALUE"])) {
    // выборка активных элементов из информационного блока $yvalue,
    // у которых установлено значение свойства с символьным кодом SRC
    // и дата начала автивности старше 1 января 2003 года
    // выбранные элементы будут сгруппированы по дате активности
    $arFilter = array(
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "ACTIVE" => "Y",
        "PROPERTY_ATT_TOVAR" => $arResult["PROPERTIES"]["ATT_TOVAR"]["VALUE"],
    );
    $res = CIBlockElement::GetList(
        array(
            "SORT" => "ASC",
            "PROPERTY_ATT_TYPE" => "ASC",
            "PROPERTY_ATT_VIEW" => "ASC"
        ),
        $arFilter,
        false,
        false,
        array(
            "PROPERTY_ATT_VIEW",
            "DETAIL_PAGE_URL",
            "PROPERTY_ATT_TYPE"
        )
    );

    while ($ar_fields = $res->GetNext()) {
        //echo '<pre>'; print_r($ar_fields); echo '</pre>';
        $arResult["LINKED_ITEMS"][$ar_fields["PROPERTY_ATT_TYPE_VALUE"]][] = $ar_fields;
    }
}

if (!empty($arResult['DETAIL_PICTURE']['SRC'])) {
    $path = CFile::GetFileArray($arResult['DETAIL_PICTURE']['ID']);
    $arResult['IMAGE'] = CFile::ResizeImageGet($path, array("width" => 459, "height" => 409), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false);
} else if ($arResult['PREVIEW_PICTURE']['SRC']) {
    $path = CFile::GetFileArray($arResult['PREVIEW_PICTURE']['ID']);
    $arResult['IMAGE'] = CFile::ResizeImageGet($path, array("width" => 459, "height" => 409), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false);
} else {
    $path = CFile::GetFileArray($arResult['DEFAULT_PICTURE']['ID']);
    $arResult['IMAGE'] = CFile::ResizeImageGet($path, array("width" => 459, "height" => 409), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false);
}

if (!empty($arResult['PROPERTIES']['CML2_MANUFACTURER'])) {
    $arFilter1 = array(
        "IBLOCK_ID" => 45,
        "ACTIVE" => "Y",
    );

    $res1 = CIBlockElement::GetList([], $arFilter1, false, false, ["NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL"]);

    while ($ar_fields1 = $res1->GetNext()) {
        if ($ar_fields1["NAME"] == $arResult['PROPERTIES']['CML2_MANUFACTURER']["VALUE"]) {
            $path = CFile::GetFileArray($ar_fields1['PREVIEW_PICTURE']);
            $arResult['PREVIEW_IMAGE']['IMAGE'] = CFile::ResizeImageGet($path, array("width" => 100, "height" => 39), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false);
            $arResult['PREVIEW_IMAGE']['SRC'] = $ar_fields1["DETAIL_PAGE_URL"] . '/';
$arResult['IMAGE_ALT'] = $ar_fields1["NAME"];
        }
    }
}

//$mainId = $this->GetEditAreaId();

/*$ar_res = CCatalogProduct::GetByID($arResult['ID']);

echo "<br>Товар с кодом ".$ID." имеет следующие параметры:<pre>";
print_r($ar_res);
echo "</pre>";*/
