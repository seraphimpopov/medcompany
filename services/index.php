<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "ремонт стоматологического оборудования, техническое обслуживание медтехники, монтаж стоматологических установок, сервис стоматологических аппаратов, Центральный федеральный округ, Москва, Московская область, Тверь, Тверская область, Калуга, Калужская область, Кострома, Костромская область, Владимир, Владимирская область, Ярославль, Ярославская область, Иваново, Ивановская область, Нижний новгород, Нижненовгородская область");
$APPLICATION->SetPageProperty("description", "Мы предоставляем услуги по ремонту и техническому обслуживанию стоматологического оборудования в Центральном федеральном округе: Москва, Московская область, Тверь, Калуга, Кострома, Владимир, Ярославль, Иваново, Нижний Новгород и другие регионы ЦФО. Гарантируем быстрый монтаж и качественный сервис медицинских изделий. Узнайте больше о наших услугах и заключите долгосрочный договор на обслуживание.");
$APPLICATION->SetPageProperty("title", "Раздел Услуги ");

$APPLICATION->SetTitle("Ремонт и обслуживание стоматологического оборудования");

?>
<div class="container mk-info-page mk-services">
    <section class="mk-hero-card mk-hero-card--services">
        <div class="mk-hero-card__text">
            <p class="mk-hero-card__eyebrow">Сервисный центр</p>
            <h2 class="mk-hero-card__title">Ремонт и обслуживание медицинского оборудования</h2>
            <p class="mk-hero-card__lead">Наша компания занимается техническим обслуживанием медицинских изделий: монтаж, ремонт и плановое ТО стоматологического оборудования.</p>
            <div class="mk-hero-card__actions">
                <a class="mk-pill mk-pill--solid" href="tel:+74932264660">Позвонить: +7 (4932) 26-46-60</a>
            </div>
        </div>
        <div class="mk-hero-card__media">
            <img src="<?= SITE_TEMPLATE_PATH ?>/img/Fon.png" alt="" aria-hidden="true">
            <img class="mk-hero-card__person" src="<?= SITE_TEMPLATE_PATH ?>/img/engineer.png" alt="Инженер сервисного центра Медкомпании" width="720" height="480">
        </div>
    </section>

    <div class="mk-info-grid">
        <section class="mk-info-card">
            <span class="mk-info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M6 20V9l6-5 6 5v11M10 20v-5h4v5"/></svg></span>
            <h3>Монтаж и демонтаж установок</h3>
            <p>Устанавливаем и демонтируем стоматологическое оборудование — быстро и&nbsp;качественно.</p>
        </section>
        <section class="mk-info-card">
            <span class="mk-info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.6 2.6-2.4-.6-.6-2.4z"/></svg></span>
            <h3>Ремонт оборудования</h3>
            <p>Наши специалисты ремонтируют разные типы стоматологического оборудования и&nbsp;обеспечивают его бесперебойную работу.</p>
        </section>
        <section class="mk-info-card">
            <span class="mk-info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M6 3h9l4 4v14H6z"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M9 12h7M9 16h7M9 8h3"/></svg></span>
            <h3>Договоры на техобслуживание</h3>
            <p>Заключаем долгосрочные договоры на ТО медицинских изделий: постоянная поддержка и своевременная профилактика.</p>
        </section>
    </div>

    <section class="mk-contact-band">
        <div>
            <h3>Остались вопросы?</h3>
            <p>По всем вопросам сервиса обращайтесь к&nbsp;Елене — подскажет по срокам, стоимости и&nbsp;договору.</p>
        </div>
        <a class="mk-contact-link mk-contact-link--big" href="tel:+74932264660">
            <span class="mk-contact-link__k">Елена, сервисный отдел</span>
            <span class="mk-contact-link__v">+7 (4932) 26-46-60</span>
        </a>
    </section>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
