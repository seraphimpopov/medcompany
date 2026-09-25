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
?>
<div class="container">
    <div class="body__body-image">
        <div class="contact-item">
            <div>
                г. Ярославль, ул. Нагорная, дом 9/31
            </div>
        </div>
        <div class="contact-item">
            <div>
                <div>
                    Телефон:
                </div>
                <a class="num" href="tel: +74852429560" title="title">+7 (4852) 42-95-60</a>
            </div>
        </div>
        <div class="contact-item">
            <div>
                <div>
                    Время работы
                </div>
                <ul class="working-hours">
                    <li>Пн-Пт 8:30-17:30</li>
                    <li>Сб-Вс Выходной</li>
                    <li></li>
                </ul>
            </div>
        </div>
        <div class="contact-item">
            <div>
                <div>
                    Электронная почта:
                </div>
                <a class="mail" href="mailto: secretary@mk37.ru" title="title">secretary@mk37.ru</a>
            </div>
        </div>
    </div>
    <div style="margin-bottom: 40px;">
        <span style="display: flex; flex-wrap: wrap; font-size: 24px; font-weight: 700; line-height: 36px; margin-bottom: 40px;">Региональные представительства</span>
        Наша компания имеет широкую сеть региональных представительств и дилеров. Вы можете обратиться в ближайший к Вам
        офис для получения дополнительных консультаций.
    </div>
    <div class="news-list">
        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="body__body-image" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
                    <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><img
                                    class="preview_picture"
                                    border="0"
                                    src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                    width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                    height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                    alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                    title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                    style="display: block; width: 220px; height: 150px; background-size: cover; background-repeat: no-repeat; border-radius: 10px; margin-right: 20px;"
                            /></a>
                    <? else: ?>
                        <img
                                class="preview_picture"
                                border="0"
                                src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                style="display: block; width: 220px; height: 150px; background-size: cover; background-repeat: no-repeat; border-radius: 10px; margin-right: 20px;"
                        />
                    <? endif; ?>
                <? endif ?>
                <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
                    <span class="news-date-time"><br/><? echo $arItem["DISPLAY_ACTIVE_FROM"] ?></span>
                <? endif ?>
                <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
                    <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                    <? else: ?>
                        <b><? echo $arItem["NAME"] ?></b><br/>
                    <? endif; ?>
                <? endif; ?>
                <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                    <? echo $arItem["PREVIEW_TEXT"]; ?>
                <? endif; ?>
                <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
                <? endif ?>
                <? foreach ($arItem["FIELDS"] as $code => $value): ?>
                    <span class="contacts">
                        <?= GetMessage("IBLOCK_FIELD_" . $code) ?>:&nbsp;<?= $value; ?>
                    </span>
                    <br/>
                <? endforeach; ?>
                <? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
                    <span class="contacts">
                        <?= $arProperty["NAME"] ?>:&nbsp;
                        <? if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                            <br/>
                            <?= implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); ?>
                        <? else: ?>
                            <br/>
                            <?= $arProperty["DISPLAY_VALUE"]; ?>
                        <? endif ?>
                    </span><br/>
                <? endforeach; ?>
            </div>
        <? endforeach; ?>
        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>
    </div>
</div>