<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arRes = CIBlockSection::GetList([], ['IBLOCK_ID' => 16, 'DEPTH_LEVEL' => 1, 'ACTIVE' => 'Y']);
while ($arElements = $arRes->GetNext()) {
    $arResult['SECTION'][]=$arElements;
}