<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Доставка, Рыбинск, Ковров, Кострома,Владимир,Тейково,Кинешма,Наволоки,Заволжск,Фурманов-Приволжск,Волгореченск,Ростов Великий,Переславль-Залесский,Тутаев,Шуя,Родники-Вичуга");
$APPLICATION->SetPageProperty("description", "Доставка интернет-магазина стоматологического оборудования и материалов Медкомпания.рф");
$APPLICATION->SetPageProperty("title", "Доставка по всей России");

$APPLICATION->SetTitle("Доставка");

?>
<div class="container mk-info-page mk-delivery">
    <figure class="mk-page-banner">
        <img src="/images/delivery-banner.webp"
             srcset="/images/delivery-banner-640.webp 640w, /images/delivery-banner.webp 1280w"
             sizes="(max-width: 1320px) calc(100vw - 32px), 1280px"
             width="1280" height="455" fetchpriority="high"
             alt="МК Ярославль. Самовывоз из офиса и склада: г. Ярославль, ул. Нагорная, 9/31; г. Иваново, ул. Сакко, д. 41А, пом. 1011; г. Владимир, пр-т Ленина, д. 5. Товар можно получить с понедельника по пятницу с 08:30 до 17:30 без обеда, суббота и воскресенье — выходной. Доставка при заказе от 3000 руб с 09:00 до 17:00.">
    </figure>

    <div class="mk-info-grid">
        <section class="mk-info-card">
            <span class="mk-info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M3 10 12 4l9 6v10H3z"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M9 20v-6h6v6"/></svg></span>
            <h3>Самовывоз</h3>
            <p class="mk-info-card__muted">Адрес офиса и склада:</p>
            <ul class="mk-addr-list">
                <li>г. Ярославль, ул. Нагорная, 9/31</li>
                <li>г. Иваново, ул. Сакко, д. 41А, пом. 1011</li>
                <li>г. Владимир, пр-т Ленина, д. 5</li>
            </ul>
            <div class="mk-hours">
                <span>Пн–Пт</span><strong>08:30–17:30, без обеда</strong>
                <span>Сб–Вс</span><strong>выходной</strong>
            </div>
        </section>

        <section class="mk-info-card">
            <span class="mk-info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M2 6h12v10H2zM14 9h4l4 4v3h-8z"/><circle cx="6" cy="18" r="2" fill="#fff" stroke="currentColor" stroke-width="1.8"/><circle cx="17" cy="18" r="2" fill="#fff" stroke="currentColor" stroke-width="1.8"/></svg></span>
            <h3>Курьером по Ярославлю и Иваново</h3>
            <p>Доставляем с&nbsp;09:00 до&nbsp;17:00 в&nbsp;течение 1–2 рабочих дней.</p>
            <p class="mk-highlight">При заказе от&nbsp;3&nbsp;000&nbsp;₽ — <strong>бесплатно</strong></p>
        </section>

        <section class="mk-info-card">
            <span class="mk-info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3"/></svg></span>
            <h3>По всей России</h3>
            <p>Отправляем транспортными компаниями — выберите любую из&nbsp;списка:</p>
            <ul class="mk-chip-list">
                <li>СДЭК</li>
                <li>Major Express</li>
                <li>Бизнес доставка</li>
                <li>Деловые Линии</li>
                <li>ЖелДорЭкспедиция</li>
            </ul>
            <p class="mk-info-card__muted">Стоимость рассчитывается индивидуально и&nbsp;оплачивается заказчиком.</p>
        </section>
    </div>

    <section class="mk-schedule">
        <div class="mk-schedule__head">
            <h3>Доставка по городам области</h3>
            <p>Выезжаем по расписанию — закажите заранее, чтобы попасть в ближайший рейс.</p>
        </div>
        <ol class="mk-schedule__list">
            <li><span class="mk-schedule__day">Понедельник</span><span class="mk-schedule__cities">Рыбинск, Ковров</span></li>
            <li><span class="mk-schedule__day">Вторник</span><span class="mk-schedule__cities">Кострома, Владимир, Тейково</span></li>
            <li><span class="mk-schedule__day">Среда</span><span class="mk-schedule__cities">Кинешма, Наволоки, Заволжск</span></li>
            <li><span class="mk-schedule__day">Четверг</span><span class="mk-schedule__cities">Владимир, Фурманов, Приволжск, Волгореченск</span></li>
            <li><span class="mk-schedule__day">Четверг <small>раз в 2 недели</small></span><span class="mk-schedule__cities">Ростов Великий, Переславль-Залесский</span></li>
            <li><span class="mk-schedule__day">Пятница</span><span class="mk-schedule__cities">Кострома, Тутаев, Шуя, Родники, Вичуга</span></li>
        </ol>
    </section>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
