<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult['SECTION'])):
    global $APPLICATION;
    $mkCurDir = $APPLICATION->GetCurDir();
    ?>
    <? foreach ($arResult['SECTION'] as $arItem):
        $mkActive = strpos($mkCurDir, $arItem["SECTION_PAGE_URL"]) === 0;
        ?>
        <li class="mk-side__item<?= $mkActive ? ' is-active' : '' ?>"><a href="<?= $arItem["SECTION_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a></li>
    <? endforeach ?>
<? endif ?>
