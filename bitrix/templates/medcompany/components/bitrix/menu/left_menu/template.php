<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <div class="lock">
        <div class="overlay"></div>
        <nav class="menu__body">
            <ul class="main_menu-1">
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
                foreach ($arResult['SECTION'] as $key => $arItem):
                    ?>
                    <li><a href="<?= $arItem["SECTION_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a></li>
                <? endforeach ?>
            </ul>
        </nav>
    </div>

<? endif ?>
