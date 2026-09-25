<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult['SECTION'])):
    global $APPLICATION;
    $mkCurDir = $APPLICATION->GetCurDir();
    ?>
    <? foreach ($arResult['SECTION'] as $arItem):
        $mkUrl = rtrim($arItem["DETAIL_PAGE_URL"], '/') . '/';
        ?>
        <li class="mk-side__item<?= $mkCurDir === $mkUrl ? ' is-active' : '' ?>"><a href="<?= $mkUrl ?>"><?= $arItem["NAME"] ?></a></li>
    <? endforeach ?>
<? endif ?>
