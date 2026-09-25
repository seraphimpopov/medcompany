<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>
<div class="container">
    <section class="mk-404">
        <div class="mk-404__art" aria-hidden="true">
            <span class="mk-404__digit">4</span>
            <svg class="mk-404__tooth" viewBox="0 0 64 64"><path fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round" d="M32 13c-4-4-9-6-14-4-6 2-8 8-7 15 1 5 3 9 4 14 1 6 2 13 6 16 3 2 5-3 6-8 1-4 2-7 5-7s4 3 5 7c1 5 3 10 6 8 4-3 5-10 6-16 1-5 3-9 4-14 1-7-1-13-7-15-5-2-10 0-14 4z"/><path fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" d="M26 27c2 1.5 4 1.5 6 0M32 27c2 1.5 4 1.5 6 0"/></svg>
            <span class="mk-404__digit">4</span>
        </div>
        <h2 class="mk-404__title">Такой страницы нет</h2>
        <p class="mk-404__text">Возможно, в адресе опечатка или страница переехала. Попробуйте найти нужный товар через поиск или загляните в каталог.</p>
        <form class="mk-404__search" action="/search/" method="get" role="search">
            <input type="search" name="q" placeholder="Название товара или артикул…" aria-label="Поиск по каталогу" autocomplete="off">
            <button type="submit" class="btn">Найти</button>
        </form>
        <div class="mk-404__actions">
            <a class="mk-pill mk-pill--solid" href="<?= SITE_DIR ?>catalog/">Перейти в каталог</a>
            <a class="mk-pill mk-pill--outline" href="<?= SITE_DIR ?>">На главную</a>
        </div>
        <nav class="mk-404__links" aria-label="Популярные разделы">
            <a href="/catalog/terapiya-1/">Терапия</a>
            <a href="/catalog/bory-1/">Боры</a>
            <a href="/catalog/anesteziya/">Анестезия</a>
            <a href="/catalog/endodontiya-1/">Эндодонтия</a>
            <a href="/catalog/khirurgiya/">Хирургия</a>
            <a href="/aktsii/">Акции</a>
        </nav>
    </section>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
