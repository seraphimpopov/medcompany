<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);
?>
<div class="bx-newslist mk-cards">
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
    <div class="row mk-cards__grid">
        <? foreach ($arResult["ITEMS"] as $key => $arItem):
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            $props = $arItem["DISPLAY_PROPERTIES"];
            $hasLink = !$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]);
            // "Скоро" only for events whose date has not passed yet
            $isUpcoming = !empty($arItem["ACTIVE_FROM"]) && MakeTimeStamp($arItem["ACTIVE_FROM"]) >= strtotime('today');
            $city = !empty($props['CITY']['VALUE']) ? $props['CITY']['VALUE'] : '';
            $type = !empty($props['TYPE_OF_EVENT']['VALUE']) ? $props['TYPE_OF_EVENT']['VALUE'] : '';
            $speaker = !empty($props['SPEAKER']['VALUE']) ? $props['SPEAKER']['VALUE'] : '';
            $speakerLabel = !empty($props['SPEAKER']['NAME']) ? $props['SPEAKER']['NAME'] : 'Лектор';
            $date = ($arParams["DISPLAY_DATE"] != "N" && $arItem["ACTIVE_FROM"]) ? $arItem["ACTIVE_FROM"] : '';

            $img = null;
            if (is_array($arItem["PREVIEW_PICTURE"])) {
                $img = array(
                    'src' => !empty($arResult["IMAGES"][$key]["src"]) ? $arResult["IMAGES"][$key]["src"] : $arItem["PREVIEW_PICTURE"]["SRC"],
                    'alt' => $arItem["PREVIEW_PICTURE"]["ALT"] ?: $arItem["NAME"],
                );
            } elseif (!empty($arItem["SLIDER"][0]["SRC"])) {
                $img = array('src' => $arItem["SLIDER"][0]["SRC"], 'alt' => $arItem["SLIDER"][0]["ALT"] ?: $arItem["NAME"]);
            }
            ?>
            <article class="bx-newslist-container mk-ncard<?= $isUpcoming ? ' mk-ncard--upcoming' : '' ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                    <div class="mk-ncard__media">
                        <? if ($img): ?>
                            <? if ($hasLink): ?><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" tabindex="-1" aria-hidden="true"><? endif ?>
                                <img src="<?= $img['src'] ?>" alt="<?= htmlspecialcharsbx($img['alt']) ?>" loading="lazy">
                            <? if ($hasLink): ?></a><? endif ?>
                        <? else: ?>
                            <span class="mk-ncard__noimg" aria-hidden="true"></span>
                        <? endif ?>
                        <? if ($isUpcoming || $date || $city): ?>
                            <div class="mk-ncard__chips">
                                <? if ($isUpcoming): ?><span class="mk-chip mk-chip--accent">Скоро</span><? endif ?>
                                <? if ($date): ?><span class="mk-chip"><?= $date ?></span><? endif ?>
                                <? if ($city): ?><span class="mk-chip"><?= $city ?></span><? endif ?>
                            </div>
                        <? endif ?>
                    </div>
                <? endif ?>
                <div class="mk-ncard__body">
                    <? if ($type): ?><p class="mk-ncard__type"><?= $type ?></p><? endif ?>
                    <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
                        <h3 class="mk-ncard__title">
                            <? if ($hasLink): ?>
                                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
                            <? else: ?>
                                <?= $arItem["NAME"] ?>
                            <? endif ?>
                        </h3>
                    <? endif ?>
                    <? if ($speaker): ?>
                        <p class="mk-ncard__speaker"><span><?= $speakerLabel ?></span><?= $speaker ?></p>
                    <? elseif ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                        <p class="mk-ncard__text"><?= TruncateText(strip_tags($arItem["PREVIEW_TEXT"]), 140) ?></p>
                    <? endif ?>
                    <? if ($hasLink): ?>
                        <a class="mk-ncard__more" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">Подробнее<span aria-hidden="true"> →</span></a>
                    <? endif ?>
                </div>
            </article>
        <? endforeach; ?>
    </div>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
</div>
