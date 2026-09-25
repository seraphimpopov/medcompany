<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");
?>

<div style="text-align: center;">
    <? if ($arResult["NavPageNomer"] > 1): ?>

        <? if ($arResult["bSavePage"]): ?>
            <a class="fa fa-arrow-left" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"></a>
        <? else: ?>
            <? if ($arResult["NavPageNomer"] > 2): ?>
                <a class="fa fa-arrow-left" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"></a>
            <? else: ?>
                <a class="fa fa-arrow-left" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"></a>
            <? endif ?>
        <? endif ?>

    <? else: ?>
        <a class="fa fa-arrow-left"></a>
    <? endif ?>

    <? while ($arResult["nStartPage"] <= $arResult["nEndPage"]): ?>

        <? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]): ?>
            <a class="active"><?= $arResult["nStartPage"] ?></a>
        <? elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false): ?>
            <a class="num" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $arResult["nStartPage"] ?></a>
        <? else: ?>
            <a class="num" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"><?= $arResult["nStartPage"] ?></a>
        <? endif ?>
        <? $arResult["nStartPage"]++ ?>
    <? endwhile ?>

    <? if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]): ?>
        <a class="fa fa-arrow-right" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"></a>
    <? else: ?>
        <a class="fa fa-arrow-right"></a>
    <? endif ?>
</div>
