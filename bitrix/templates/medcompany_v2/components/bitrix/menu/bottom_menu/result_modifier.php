<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arRes = CIBlockElement::GetList(["NAME" => "ASC"], ['IBLOCK_ID' => 45, 'ACTIVE' => 'Y']);
while ($arElements = $arRes->GetNext()) {
    $arResult['SECTION'][]=$arElements;
}