<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Раздел покупки - Интернет-магазин стоматологического оборудования и материалов Медкомпания.рф");
$APPLICATION->SetPageProperty("title", "Раздел покупки");

$APPLICATION->SetTitle("Покупки");

?><div class="container mk-buy">
    <section class="mk-buy__hero">
        <div class="mk-buy__hero-text">
            <p class="mk-buy__eyebrow">Как заказать и оплатить товар</p>
            <h2 class="mk-buy__title">Оформление заказа в&nbsp;четыре шага</h2>
            <p class="mk-buy__lead">Уважаемые покупатели! Ниже — всё, что нужно знать о покупке в интернет-магазине: от корзины до получения товара.</p>
            <div class="mk-buy__actions">
                <a class="mk-pill mk-pill--solid" href="/catalog/">Перейти в каталог</a>
                <a class="mk-pill mk-pill--outline" href="tel:+74852429560">+7 (4852) 42-95-60</a>
            </div>
        </div>
        <img class="mk-buy__hero-img" src="/images/buys.png" alt="Оформление заказа в интернет-магазине" loading="lazy">
    </section>

    <ol class="mk-buy__steps">
        <li class="mk-buy__step">
            <span class="mk-buy__num" aria-hidden="true">1</span>
            <h3>Регистрация</h3>
            <p>Зарегистрируйтесь в интернет-магазине. Если не хотите регистрироваться, этот шаг можно пропустить.</p>
            <a href="/personal/profile/">Войти или зарегистрироваться</a>
        </li>
        <li class="mk-buy__step">
            <span class="mk-buy__num" aria-hidden="true">2</span>
            <h3>Корзина</h3>
            <p>Поместите интересующий вас товар в&nbsp;«корзину» и&nbsp;оформите заказ.</p>
            <a href="/personal/cart/">Открыть корзину</a>
        </li>
        <li class="mk-buy__step">
            <span class="mk-buy__num" aria-hidden="true">3</span>
            <h3>Доставка</h3>
            <ul>
                <li>самовывоз из магазина;</li>
                <li>доставка по г. Ярославль (стоимость уточняйте у оператора);</li>
                <li>доставка транспортной компанией в другие города.</li>
            </ul>
            <a href="/dostavka/">Подробнее о доставке</a>
        </li>
        <li class="mk-buy__step">
            <span class="mk-buy__num" aria-hidden="true">4</span>
            <h3>Оплата</h3>
            <ul>
                <li>наличные — в магазине при получении или курьеру по г. Ярославль;</li>
                <li>счёт для оплаты.</li>
            </ul>
        </li>
    </ol>

    <div class="mk-buy__notice" role="note">
        <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" d="M4 6h16v12H4z"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="m4 7 8 6 8-6"/></svg>
        <p><strong>Ваш заказ оформлен?</strong> Ждите ответа оператора по электронной почте.</p>
    </div>

    <div class="mk-buy__info">
        <article class="mk-buy__card">
            <h3>Оплата банковской картой</h3>
            <p>При оплате картой вы автоматически перейдёте на сайт системы обработки платежей. Нажмите «Перейти», заполните форму и нажмите «Оплатить».</p>
            <p>Предварительно уточните наличие товара на складе у оператора. При самовывозе необходимо личное присутствие держателя карты и документ, удостоверяющий личность.</p>
        </article>
        <article class="mk-buy__card">
            <h3>Счёт на оплату и доставка</h3>
            <p>Сумму доставки уточняйте у оператора. При выборе «Счёт на оплату» оператор свяжется с вами и пришлёт счёт на указанную почту — с учётом доставки до вашего города.</p>
            <p>Товар резервируется за вами до 3 дней с момента выставления счёта. После поступления оплаты на наш расчётный счёт товар отправляется. Во избежание недоразумений подтверждайте оплату по телефону или электронной почте.</p>
        </article>
    </div>
</div><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
