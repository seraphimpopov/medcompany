<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$CurDir = $APPLICATION->GetCurDir();
$CurUri = $APPLICATION->GetCurUri();
$isIndex = $APPLICATION->GetCurPage() == SITE_DIR;
?><!doctype html>
<html lang="ru-Ru">
<head>
    <?

    use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;

$server = Context::getCurrent()->getServer();
$request = Context::getCurrent()->getRequest();
$canonical = ($request->isHttps() ? 'https://' : 'http://') . preg_replace('/:\d+/', '', $server->getHttpHost()) . $request->getRequestedPageDirectory() . '/';

    // Пример подключения JS
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jquery-3.7.0.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/fancybox/jquery.fancybox.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/slick_slider/slick.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/myscripts.min.js');
    // Пример подключения CSS
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . 'template_styles.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/vars.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/bootstrap-grid.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/js/fancybox/jquery.fancybox.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/js/slick_slider/slick.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/fonts/fontAwesome/font-awesome.min.css');
    $APPLICATION->ShowHead();
    ?>
    <meta content="width=device-width, height=device-height, initial-scale=1.0" name="viewport">
<meta name="google-site-verification" content="r6OQ8kf41aPzfQ8qZRZSPHjyoq3mVZxW3LgxXd5tzmo" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta property="og:url" content="<?= $canonical ?>"/>
        <link rel="canonical" href="<?= $canonical ?>"/>
    <title><? $APPLICATION->ShowTitle(true) ?></title>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(98187524, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            ecommerce:"dataLayer"
        });
    </script>
    <!-- /Yandex.Metrika counter -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "Медкомпания",
            "url": "https://xn--80ahcoijdjgl3p.xn--p1ai/"
        }
    </script>
</head>
<body>
<noscript><div><img src="https://mc.yandex.ru/watch/98187524" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<?php
$APPLICATION->ShowPanel();

$APPLICATION->IncludeComponent(
    "bazarow:favorites.add",
    "",
    array(),
);
?>

<header class="header">
    <h1 class="head">Медкомпания</h1>
    <div class="header__body">
        <div class="container">
            <div class="header__body-inner">
                <div class="nav">
                    <div class="header__nav-list">
                        <!--<div class="header__nav-item">
                           <a class="header__nav-link" href="/">Главная</a>
                       </div>-->
                        <div class="header__nav-item">
                            <a class="header__nav-link" href="/services">Услуги</a>
                        </div>
                        <div class="header__nav-item">
                            <a class="header__nav-link" href="/contacts">Контакты</a>
                        </div>
<div class="header__nav-item">
                            <a class="header__nav-link" href="/catalog/anesteziya-ooo-ardent/">Анестезия</a>
                        </div>
                    </div>
                </div>
                <a href="/catalog/" class="catalog_head header__nav-link"
                   style="">
                    Каталог
                </a>
                <div class="pay">
                    <a class="header__body-logo logo" href="/">
                        <img class="image" src="<?= SITE_TEMPLATE_PATH ?>/img/logo.png" alt="Logo">
                    </a>
                    <div class="header__body-shop-1">
                        <? $APPLICATION->IncludeComponent(
                            "bazarow:favorites.line",
                            "",
                            array(),
                        ); ?>
                        <div style="display: flex; align-items: center">
                            <a href="/personal/cart/">
                                <svg class="icon-2">
                                    <use xlink:href="#корзина-01"></use>
                                </svg>
                            </a>
                            <? // Ссылка на корзину
                            $APPLICATION->IncludeComponent(
                                "bitrix:sale.basket.basket.line",
                                "small_basket",
                                array(
                                    "COMPONENT_TEMPLATE" => "small_basket",
                                    "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                                    "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                                    "SHOW_NUM_PRODUCTS" => "Y",
                                    "SHOW_TOTAL_PRICE" => "Y",
                                    "SHOW_EMPTY_VALUES" => "Y",
                                    "SHOW_PERSONAL_LINK" => "N",
                                    "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                                    "SHOW_AUTHOR" => "N",
                                    "PATH_TO_AUTHORIZE" => "",
                                    "SHOW_REGISTRATION" => "N",
                                    "PATH_TO_REGISTER" => SITE_DIR . "login/",
                                    "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                                    "SHOW_PRODUCTS" => "N",
                                    "POSITION_FIXED" => "N",
                                    "HIDE_ON_BASKET_PAGES" => "N",
                                    "SHOW_DELAY" => "N",
                                    "SHOW_NOTAVAIL" => "N",
                                    "SHOW_IMAGE" => "Y",
                                    "SHOW_PRICE" => "Y",
                                    "SHOW_SUMMARY" => "N",
                                    "MAX_IMAGE_SIZE" => "70"
                                ),
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
                <div class="header__bottom-nav">
                    <ul class="bottom__nav-list">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "main_menu",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "top",
                                "USE_EXT" => "Y",
                                "COMPONENT_TEMPLATE" => "main_menu"
                            ),
                            false
                        ); ?>
                    </ul>
                </div>
                <a href="/blog/" class="centr_head header__nav-link"
                   style="">
                    Учебный центр
                </a>
                <div class="nav-item planshet-item">
                    <a class="header__nav-link" href="/dostavka/">Доставка</a>
                </div>
                <div class="header__user-nav">
                    <div class="planshet-num">
                        <div class="header__top-number">
                            <a class="header__top-number1" href="tel: +74852429560" title="title">+7 (4852) 42-95-60</a>
                        </div>
                        <div class="header__top-mail">
                            <a class="header__top-mail1" href="mailto: secretary@mk37.ru"
                               title="title">secretary@mk37.ru</a>
                        </div>
                    </div>
                    <div class="header__top-login">
                        <svg class="icon">
                            <use xlink:href="#mdi--account"></use>
                        </svg>
                        <a class="header__top-login1" href="/personal/profile" title="title">
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
                </div>
            </div>

            <div class="line">
                <div class="row align-self-center justify-content-center">
                    <div class="col-xl-3 col-md-3 align-self-center center-block text-center promotion">
                        <a href="/aktsii/" class="justify-content-center"
                              style="color: #c50000; text-decoration: none">Акция</a>
                    </div>
                    <div class="col-xl-7 col-md-7 col-xs-12 search">
                        <? // Поиск по заголовкам - http://dev.1c-bitrix.ru/user_help/settings/search/components_2/search_title.php
                        $APPLICATION->IncludeComponent(
                            "bitrix:search.title",
                            "main_search",
                            array(
                                "NUM_CATEGORIES" => "1",
                                "TOP_COUNT" => "5",
                                "ORDER" => "rank",
                                "USE_LANGUAGE_GUESS" => "Y",
                                "CHECK_DATES" => "N",
                                "SHOW_PREVIEW" => "Y",
                                "SHOW_OTHERS" => "N",
                                "PAGE" => "#SITE_DIR#search/index.php",
                                "CATEGORY_0_TITLE" => "",
                                "CATEGORY_0" => array(
                                    0 => "iblock_catalogs",
                                ),
                                "COMPONENT_TEMPLATE" => "main_search",
                                "SHOW_INPUT" => "Y",
                                "INPUT_ID" => "title-search-input",
                                "CONTAINER_ID" => "title-search",
                                "CATEGORY_0_iblock_med_shop" => array(
                                    0 => "4",
                                ),
                                "PRICE_CODE" => array(
                                    0 => "Для_сайта",
                                ),
                                "PRICE_VAT_INCLUDE" => "Y",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "PREVIEW_WIDTH" => "75",
                                "PREVIEW_HEIGHT" => "75",
                                "CONVERT_CURRENCY" => "N",
                                "CATEGORY_0_iblock_catalogs" => array(
                                    0 => "16",
                                ),
                                "CATEGORY_1_TITLE" => "",
                                "CATEGORY_1" => ""
                            ),
                            false
                        ); ?>
                        <button class="col-xs-2 burger">
                            <span></span>
                        </button>
                    </div>
                    <div class="col-xl-2 col-md-2 header__body-int align-self-center">
                        <? $APPLICATION->IncludeComponent(
                            "bazarow:favorites.line",
                            "",
                            array(),
                        ); ?>
                        <div class="favor">
                            <a href="/personal/cart/">
                                <svg class="icon-2">
                                    <use xlink:href="#корзина-01"></use>
                                </svg>
                            </a>
                            <? // Ссылка на корзину
                            $APPLICATION->IncludeComponent(
                                "bitrix:sale.basket.basket.line",
                                "small_basket",
                                array(
                                    "COMPONENT_TEMPLATE" => "small_basket",
                                    "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                                    "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                                    "SHOW_NUM_PRODUCTS" => "Y",
                                    "SHOW_TOTAL_PRICE" => "Y",
                                    "SHOW_EMPTY_VALUES" => "Y",
                                    "SHOW_PERSONAL_LINK" => "N",
                                    "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                                    "SHOW_AUTHOR" => "N",
                                    "PATH_TO_AUTHORIZE" => "",
                                    "SHOW_REGISTRATION" => "N",
                                    "PATH_TO_REGISTER" => SITE_DIR . "login/",
                                    "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                                    "SHOW_PRODUCTS" => "N",
                                    "POSITION_FIXED" => "N",
                                    "HIDE_ON_BASKET_PAGES" => "N",
                                    "SHOW_DELAY" => "N",
                                    "SHOW_NOTAVAIL" => "N",
                                    "SHOW_IMAGE" => "Y",
                                    "SHOW_PRICE" => "Y",
                                    "SHOW_SUMMARY" => "N",
                                    "MAX_IMAGE_SIZE" => "70"
                                ),
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="header__bottom">
        <div class="container">
            <? if (!$isIndex): ?>
                <? // Навигационная цепочка - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/navigation/breadcrumb.php
                $APPLICATION->IncludeComponent(
                    "bitrix:breadcrumb",
                    "breadcrumb",
                    array(
                        "START_FROM" => "0",
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "COMPONENT_TEMPLATE" => "breadcrumb"
                    ),
                    false
                ); ?>
            <? endif; ?>
        </div>
    </div>
</header>


<style>

    .header__bottom-nav {
        margin-bottom: 0;
    }

    .image {
        @media (min-width: 1100px) {
            width: 250px;
        }
        @media (max-width: 1100px) {
            width: 200px;
        }
    }

    .header__top-inner {
         display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap; /* Чтобы элементы переносились на новую строку при уменьшении экрана */
    padding: 10px 20px; 
    }

    .header__body-inner:last-child {
        margin: 0 20px;
    }

    .liniya-2 {
        height: 2px;
        border: 1px solid #abafb3;
    }

    .header__top-number1 {
        font-size: 14px;
        color: #626971;
        text-decoration: none;
        margin-right: 17px;
        position: relative;
        margin-left: 20px;
        white-space: nowrap;
        transition: .3s;
    }

    .header__top-number {
        display: flex;
        align-items: center;
    }

    .header__top-number1:hover {
        color: var(--color1)
    }

    .header__top-mail1 {
        font-size: 14px;
        color: #626971;
        text-decoration: none;
        line-height: 1.6;
        position: relative;
        white-space: nowrap;
        transition: .3s;
    }

    .header__top-mail {
        display: flex;
        align-items: center;
    }

    .header__top-mail1:hover {
        color: var(--color1)
    }

    .header__top-login1 {
        font-size: 14px;
        color: #626971;
        text-decoration: none;
        line-height: 1.6;
        position: relative;
        white-space: nowrap;
        transition: .3s;
    }

    .header__top-login {
        display: flex;
        align-items: center;
    }

    .header__top-login1:hover {
        color: var(--color1)
    }

    .header__nav-link {
        font-size: 16px;
        text-transform: uppercase;
        color: var(--color1);
        text-decoration: none;
        line-height: 1.6;
        white-space: nowrap;
        transition: all .3s;
    }

    .header__nav-link:hover {
        color: var(--color1)
    }

    .header__top-inner {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        @media (min-width: 768px) and (max-width: 1160px) {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 768px) {
        .header__top-inner {
            display: none;
        }

        .liniya-2 {
            display: none;
        }

        .img1 {
            display: none;
        }

        .img2 {
            display: none;
        }

        .img3 {
            display: none;
        }

        .header__body-int {
            display: none;
        }

        .header__user-nav {
            display: none;
        }

        .header__body-logo {
            padding: 0;
            margin-right: 40px;
        }

        .image {
            max-width: 180px;
        }

        .pay {
            display: flex;
            align-items: center;
        }

        .header__body-shop-1 {
            display: flex;
            align-items: flex-start;
            font-weight: 700;
            line-height: 1.6;
            position: relative;
            padding-left: 14px;
            transition: .3s;
        }
    }

    @media (min-width: 768px) {
        .header__body-int {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header__body-shop-1 {
            display: none;
        }
    }

    .head {
        display: none;
    }

</style>

	<? if ($CurUri == "/personal/profile/" || $CurUri == "/personal/profile/?login=yes") { ?>
<div class="container" style="display: flex;
    justify-content: center;">
<? } ?>