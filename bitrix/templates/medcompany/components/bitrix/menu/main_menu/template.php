<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <div class="lock">
        <div class="overlay"></div>
        <nav class="menu__body">
            <ul class="main_menu">
                <div class="header__top-login-1">
                    <svg class="icon">
                        <use xlink:href="#account_circle_black_18dp"></use>
                    </svg>
                    <a class="header__top-login11" href="/personal/profile" title="title">
                        <?php
                        global $USER;
                        if ($USER->IsAuthorized()) {
                            echo $USER->GetFullName();
                        } else {
                            echo "Войти";
                        }
                        ?>
                    </a>
                    <? if ($USER->IsAuthorized()): ?>
                        <a class="header__top-login1" href="/?logout=yes&<?= bitrix_sessid_get() ?>">Выйти</a>
                    <? endif ?>
                </div>
                <?
                foreach ($arResult as $key => $arItem):
                    if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                        continue;
                    if ($arItem["TEXT"] != 'Каталог производителей'):
                        ?>
                        <? if ($arItem["SELECTED"]): ?>
                        <li><a href="<?= $arItem["LINK"] ?>" class="select"><?= $arItem["TEXT"] ?><i></i></a></li>
                    <? else: ?>
                        <li><a style="<? if ($key == 0) : ?> text-decoration: underline <? endif; ?>"
                               href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?><i></i></a></li>
                    <? endif; ?>
                    <? endif ?>
                <? endforeach ?>
            </ul>
        </nav>
    </div>

<? endif ?>
