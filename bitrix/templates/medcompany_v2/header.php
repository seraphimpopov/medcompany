<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$CurDir = $APPLICATION->GetCurDir();
$CurUri = $APPLICATION->GetCurUri();
$isIndex = $APPLICATION->GetCurPage() == SITE_DIR;
?><!doctype html>
<html lang="ru">
<head>
    <?

    use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;

$server = Context::getCurrent()->getServer();
$request = Context::getCurrent()->getRequest();
// One canonical host, the real path (no doubled slash) and only the parameters that change the content
$mkSiteUrl = 'https://xn--80ahcoijdjgl3p.xn--p1ai';
$canonical = $mkSiteUrl . $APPLICATION->GetCurPage(false);
$mkCanonicalQuery = array();
foreach (array('ELEMENT_ID', 'SECTION_ID') as $mkParam) {
    if (isset($_GET[$mkParam]) && ctype_digit((string)$_GET[$mkParam])) {
        $mkCanonicalQuery[$mkParam] = (int)$_GET[$mkParam];
    }
}
foreach ($_GET as $mkParam => $mkValue) {
    if (preg_match('/^PAGEN_\d+$/', $mkParam) && (int)$mkValue > 1) {
        $mkCanonicalQuery[$mkParam] = (int)$mkValue;
    }
}
if ($mkCanonicalQuery) {
    $canonical .= '?' . http_build_query($mkCanonicalQuery);
}
$mkNoIndex = preg_match('#^/(personal|auth|login)/#', $APPLICATION->GetCurPage(false)); // /search/ sets its own robots tag

if (!function_exists('mkSeoTitleText')) {
    // "<name> — купить в Ярославле | Медкомпания" for catalog pages, "<name> | Медкомпания" elsewhere
    function mkSeoTitleText()
    {
        global $APPLICATION;
        $page = $APPLICATION->GetCurPage(false);
        if ($page === '/') {
            return 'Медкомпания — стоматологические материалы и оборудование в Ярославле, Иваново и Владимире';
        }
        $title = trim((string)$APPLICATION->GetPageProperty('title'));
        if ($title === '') {
            $title = trim((string)$APPLICATION->GetTitle());
        }
        $title = trim(preg_replace('/\s+/u', ' ', strip_tags(htmlspecialchars_decode($title))));
        if ($title === '') {
            return 'Медкомпания';
        }
        // a title written in the admin (it already names the shop) is kept as is
        if (mb_stripos($title, 'медкомпани') !== false) {
            return $title;
        }
        if (strpos($page, '/catalog/') === 0 && $page !== '/catalog/' && mb_stripos($title, 'купить') === false) {
            $title .= ' — купить в Ярославле';
        }
        return $title . ' | Медкомпания';
    }
    function mkSeoTitle()
    {
        return htmlspecialcharsbx(mkSeoTitleText());
    }
    // Open Graph / Twitter cards; pages may set og:image and og:type as page properties
    function mkSeoMeta()
    {
        global $APPLICATION;
        $site = 'https://xn--80ahcoijdjgl3p.xn--p1ai';
        $description = trim(strip_tags((string)$APPLICATION->GetPageProperty('description')));
        $image = (string)$APPLICATION->GetPageProperty('og:image');
        if ($image === '') {
            $image = '/images/delivery-banner.webp';
        }
        if (strpos($image, 'http') !== 0) {
            $image = $site . $image;
        }
        $type = (string)$APPLICATION->GetPageProperty('og:type');
        $tags = array(
            '<meta property="og:type" content="' . htmlspecialcharsbx($type !== '' ? $type : 'website') . '">',
            '<meta property="og:site_name" content="Медкомпания">',
            '<meta property="og:locale" content="ru_RU">',
            '<meta property="og:title" content="' . htmlspecialcharsbx(mkSeoTitleText()) . '">',
            '<meta property="og:image" content="' . htmlspecialcharsbx($image) . '">',
            '<meta name="twitter:card" content="summary_large_image">',
        );
        if ($description !== '') {
            $tags[] = '<meta property="og:description" content="' . htmlspecialcharsbx($description) . '">';
        }
        return implode("\n    ", $tags) . "\n";
    }
}

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jquery-3.7.0.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/fancybox/jquery.fancybox.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/slick_slider/slick.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/myscripts.min.js');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/vars.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/bootstrap-grid.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/js/fancybox/jquery.fancybox.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/js/slick_slider/slick.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/fonts/fontAwesome/font-awesome.min.css');
    $APPLICATION->ShowHead();
    $mkV2Ver = @filemtime(__DIR__ . '/css/redesign.css');
    ?>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="theme-color" content="#2b6a2e">
<meta name="google-site-verification" content="r6OQ8kf41aPzfQ8qZRZSPHjyoq3mVZxW3LgxXd5tzmo" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta property="og:url" content="<?= $canonical ?>"/>
        <link rel="canonical" href="<?= $canonical ?>"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/css/redesign.css?v=<?= $mkV2Ver ?>">
    <title><? $APPLICATION->AddBufferContent('mkSeoTitle') ?></title>
    <? $APPLICATION->AddBufferContent('mkSeoMeta') ?>
    <? if ($mkNoIndex): ?><meta name="robots" content="noindex, follow"><? endif ?>
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
            "@graph": [
                {
                    "@type": "Organization",
                    "@id": "https://xn--80ahcoijdjgl3p.xn--p1ai/#org",
                    "name": "Медкомпания",
                    "alternateName": "МК Ярославль",
                    "url": "https://xn--80ahcoijdjgl3p.xn--p1ai/",
                    "logo": "https://xn--80ahcoijdjgl3p.xn--p1ai/bitrix/templates/medcompany_v2/img/logo.png",
                    "contactPoint": [{"@type": "ContactPoint", "telephone": "+7-4852-42-95-60", "contactType": "sales", "areaServed": "RU", "availableLanguage": "Russian"}],
                    "department": [
                        {"@type": "Store", "name": "Медкомпания — Ярославль", "telephone": "+7-4852-42-95-60", "openingHours": "Mo-Fr 08:30-17:30",
                         "address": {"@type": "PostalAddress", "streetAddress": "ул. Нагорная, 9/31", "addressLocality": "Ярославль", "addressCountry": "RU"}},
                        {"@type": "Store", "name": "Медкомпания — Иваново", "telephone": "+7-4932-26-46-60", "openingHours": "Mo-Fr 08:30-17:30",
                         "address": {"@type": "PostalAddress", "streetAddress": "ул. Сакко, д. 41А, пом. 1011", "addressLocality": "Иваново", "addressCountry": "RU"}},
                        {"@type": "Store", "name": "Медкомпания — Владимир", "telephone": "+7-910-815-81-65", "openingHours": "Mo-Fr 08:30-17:30",
                         "address": {"@type": "PostalAddress", "streetAddress": "пр-т Ленина, д. 5", "addressLocality": "Владимир", "addressCountry": "RU"}}
                    ]
                },
                {
                    "@type": "WebSite",
                    "@id": "https://xn--80ahcoijdjgl3p.xn--p1ai/#site",
                    "name": "Медкомпания",
                    "url": "https://xn--80ahcoijdjgl3p.xn--p1ai/",
                    "publisher": {"@id": "https://xn--80ahcoijdjgl3p.xn--p1ai/#org"},
                    "potentialAction": {"@type": "SearchAction", "target": "https://xn--80ahcoijdjgl3p.xn--p1ai/search/?q={search_term_string}", "query-input": "required name=search_term_string"}
                }
            ]
        }
    </script>
</head>
<?
$mkSection = preg_replace('/[^a-z0-9_-]/', '', strtok(trim($CurDir, '/'), '/') ?: 'home');
?>
<body class="mk-v2 mk-sec-<?= $mkSection ?><?= $isIndex ? ' mk-index' : '' ?>">
<noscript><div><img src="https://mc.yandex.ru/watch/98187524" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<?php
$APPLICATION->ShowPanel();

$APPLICATION->IncludeComponent(
    "bazarow:favorites.add",
    "",
    array(),
);

global $USER;
$mkUserName = $USER->IsAuthorized() ? trim($USER->GetFullName()) : '';
if ($USER->IsAuthorized() && $mkUserName === '') {
    $mkUserName = $USER->GetLogin();
}
$mkNav = [
    ['/services/', 'Услуги'],
    ['/contacts/', 'Контакты'],
    ['/catalog/anesteziya-ooo-ardent/', 'Анестезия'],
    ['/aktsii/', 'Акции'],
    ['/news/', 'Новости'],
    ['/help/buys/', 'Оплата'],
];
$mkBasketParams = array(
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
);
?>
<a class="mk-skip" href="#mk-main">Перейти к содержимому</a>

<header class="header mk-header">
    <div class="mk-container mk-header__top">
        <a class="mk-logo" href="/" aria-label="Медкомпания — на главную">
            <img src="<?= SITE_TEMPLATE_PATH ?>/img/logo.png" alt="МК Медицинская компания" width="160" height="48">
        </a>
        <nav class="mk-nav" aria-label="Основное меню">
            <? foreach ($mkNav as $mkItem): ?>
                <a class="mk-nav__link<?= strpos($CurDir, $mkItem[0]) === 0 ? ' is-active' : '' ?>" href="<?= $mkItem[0] ?>"><?= $mkItem[1] ?></a>
            <? endforeach ?>
        </nav>
        <div class="mk-header__actions">
            <a class="mk-pill mk-pill--outline" href="/dostavka/">Доставка</a>
            <a class="mk-pill mk-pill--outline" href="/blog/">Учебный центр</a>
            <a class="mk-pill mk-pill--soft" href="/personal/profile/">
                <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 4a4 4 0 1 1 0 8a4 4 0 0 1 0-8m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4"/></svg>
                <span class="mk-pill__text"><?= $mkUserName !== '' ? htmlspecialcharsbx($mkUserName) : 'Войти' ?></span>
            </a>
            <? if ($USER->IsAuthorized()): ?>
                <a class="mk-logout" href="/?logout=yes&<?= bitrix_sessid_get() ?>" title="Выйти" aria-label="Выйти">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z"/></svg>
                </a>
            <? endif ?>
        </div>
        <div class="mk-header__mobile-icons">
            <a class="mk-icon-btn" href="/personal/wishlist/" aria-label="Избранное">
                <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M12 20s-7.5-4.6-7.5-10.1A4.4 4.4 0 0 1 12 7.3a4.4 4.4 0 0 1 7.5 2.6C19.5 15.4 12 20 12 20Z"/></svg>
            </a>
            <a class="mk-icon-btn" href="/personal/cart/" aria-label="Корзина">
                <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.2 11h10.6L20 8H6.2M9 20h.01M17 20h.01"/></svg>
            </a>
            <button class="mk-icon-btn mk-burger" type="button" aria-label="Открыть меню" aria-controls="mk-drawer" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <div class="mk-container mk-header__bar">
        <a class="mk-catalog-btn" href="/catalog/">
            <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M4 4h7v7H4zm9 0h7v7h-7zM4 13h7v7H4zm9 0h7v7h-7z" opacity=".9"/></svg>
            Каталог
        </a>
        <div class="mk-search">
            <? $APPLICATION->IncludeComponent(
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
        </div>
        <div class="mk-header__shop">
            <div class="mk-shop-link mk-shop-link--fav">
                <span class="mk-shop-link__iconwrap">
                    <? $APPLICATION->IncludeComponent(
                        "bazarow:favorites.line",
                        "",
                        array(),
                    ); ?>
                </span>
                <a class="mk-shop-link__label" href="/personal/wishlist/">Избранное</a>
            </div>
            <div class="mk-shop-link mk-shop-link--cart">
                <span class="mk-shop-link__iconwrap">
                    <a class="mk-shop-link__icon" href="/personal/cart/" aria-label="Корзина">
                        <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.2 11h10.6L20 8H6.2M9 20h.01M17 20h.01"/></svg>
                    </a>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:sale.basket.basket.line",
                        "small_basket",
                        $mkBasketParams,
                        false
                    ); ?>
                </span>
                <a class="mk-shop-link__label" href="/personal/cart/" tabindex="-1">Корзина</a>
            </div>
        </div>
    </div>

    <? if (!$isIndex): ?>
        <div class="mk-container mk-breadcrumbs">
            <? $APPLICATION->IncludeComponent(
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
        </div>
    <? endif; ?>
</header>

<div class="mk-drawer" id="mk-drawer" role="dialog" aria-modal="true" aria-label="Меню сайта" hidden>
    <div class="mk-drawer__overlay" data-mk-close></div>
    <nav class="mk-drawer__panel" aria-label="Мобильное меню">
        <div class="mk-drawer__head">
            <img src="<?= SITE_TEMPLATE_PATH ?>/img/logo.png" alt="" width="120" height="36">
            <button class="mk-icon-btn" type="button" data-mk-close aria-label="Закрыть меню">
                <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        <a class="mk-pill mk-pill--soft mk-drawer__login" href="/personal/profile/"><?= $mkUserName !== '' ? htmlspecialcharsbx($mkUserName) : 'Войти в кабинет' ?></a>
        <div class="mk-drawer__group">
            <a class="mk-drawer__link mk-drawer__link--strong" href="/catalog/">Каталог</a>
            <? foreach ($mkNav as $mkItem): ?>
                <a class="mk-drawer__link" href="<?= $mkItem[0] ?>"><?= $mkItem[1] ?></a>
            <? endforeach ?>
            <a class="mk-drawer__link" href="/dostavka/">Доставка</a>
            <a class="mk-drawer__link" href="/blog/">Учебный центр</a>
            <a class="mk-drawer__link" href="/manufacturers/">Производители</a>
        </div>
        <div class="mk-drawer__contacts">
            <a href="tel:+74852429560">+7 (4852) 42-95-60</a>
            <a href="mailto:secretary@mk37.ru">secretary@mk37.ru</a>
        </div>
    </nav>
</div>

<main id="mk-main" class="mk-main">
<?
// Pages that render their own heading (catalog, product, hero pages) skip the template H1.
// Hero pages (home, services, delivery) keep an H1 for screen readers only.
$mkNoTitle = ($CurDir !== '/catalog/' && strpos($CurDir, '/catalog/') === 0) || strpos($CurDir, '/search/') === 0;
$mkHiddenTitle = $isIndex || strpos($CurDir, '/services/') === 0 || strpos($CurDir, '/dostavka/') === 0;
if ($mkHiddenTitle): ?>
    <h1 class="mk-sr-only"><?= $isIndex ? 'Медкомпания — стоматологические материалы и оборудование' : '' ?><? if (!$isIndex) $APPLICATION->ShowTitle(false) ?></h1>
<? elseif (!$mkNoTitle): ?>
    <div class="mk-container mk-page-head"><h1 class="mk-page-title"><? $APPLICATION->ShowTitle(false) ?></h1></div>
<? endif ?>
<? if ($USER->IsAuthorized() && strpos($CurDir, '/personal/') === 0 && strpos($CurDir, '/personal/order/') !== 0):
    $mkAccount = array(
        array('/personal/profile/', 'Обзор', true),
        array('/personal/profile/orders/', 'Текущие заказы', false),
        array('/personal/profile/orders/?filter_history=Y', 'История заказов', false),
        array('/personal/profile/user/', 'Личные данные', false),
        array('/personal/wishlist/', 'Избранное', false),
        array('/personal/cart/', 'Корзина', false),
    );
    $mkHistory = isset($_GET['filter_history']) && $_GET['filter_history'] === 'Y';
    ?>
    <nav class="mk-container mk-account-nav" aria-label="Разделы личного кабинета">
        <? foreach ($mkAccount as $mkA):
            $mkPath = strtok($mkA[0], '?');
            $mkIsHistory = strpos($mkA[0], 'filter_history') !== false;
            $mkOn = $mkA[2] ? $CurDir === $mkPath : (strpos($CurDir, $mkPath) === 0 && $mkIsHistory === $mkHistory);
            ?>
            <a class="mk-account-nav__link<?= $mkOn ? ' is-active' : '' ?>" href="<?= $mkA[0] ?>"<?= $mkOn ? ' aria-current="page"' : '' ?>><?= $mkA[1] ?></a>
        <? endforeach ?>
    </nav>
<? endif ?>
