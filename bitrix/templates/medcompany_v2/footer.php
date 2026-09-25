<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$mkCurUri = $APPLICATION->GetCurUri();
$mkCurDir = $APPLICATION->GetCurDir();
if ($mkCurUri == "/personal/profile/" || $mkCurUri == "/personal/profile/?login=yes") { ?></div><? } ?>
</main>

<footer class="footer mk-footer">
    <div class="mk-container">
        <div class="mk-footer__grid">
            <div class="mk-footer__col">
                <div class="mk-footer__head">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" d="M4 20V9l8-5l8 5v11h-5v-6H9v6z"/></svg>
                    <a href="/about/">О компании</a>
                </div>
                <ul class="mk-footer__links">
                    <li><a href="/news/">Статьи</a></li>
                    <li><a href="/company/consent/">Политика конфиденциальности</a></li>
                </ul>
                <address class="mk-footer__addr">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.6" d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                    <span>
                        г. Ярославль, ул. Нагорная, дом 9/31<br>
                        <a href="tel:+74852429560">+7 (4852) 42-95-60</a><br>
                        <a href="mailto:secretary@mk37.ru">secretary@mk37.ru</a>
                    </span>
                </address>
            </div>
            <div class="mk-footer__col">
                <div class="mk-footer__head">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" d="M4 14v-2a8 8 0 0 1 16 0v2M4 14h3v5H5a1 1 0 0 1-1-1zm16 0h-3v5h2a1 1 0 0 0 1-1z"/></svg>
                    <a href="/services/">Услуги</a>
                </div>
                <ul class="mk-footer__links">
                    <li><a href="/dostavka/">Доставка</a></li>
                    <li><a href="/services/">Сервис</a></li>
                </ul>
                <address class="mk-footer__addr">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.6" d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                    <span>
                        г. Иваново, ул. Сакко, дом 41а, пом. 1011<br>
                        <a href="tel:+74932264660">+7 (4932) 26-46-60</a><br>
                        <a href="mailto:office@mk37.ru">office@mk37.ru</a>
                    </span>
                </address>
            </div>
            <div class="mk-footer__col">
                <div class="mk-footer__head">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.6"/><path fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" d="M9.6 9.3a2.5 2.5 0 1 1 3.4 2.3c-.6.3-1 .8-1 1.5v.4M12 17h.01"/></svg>
                    <a href="/help/">Помощь</a>
                </div>
                <ul class="mk-footer__links">
                    <li><a href="/help/buys/">Покупки</a></li>
                    <li><a href="/help/faq/">Вопрос — ответ</a></li>
                </ul>
                <address class="mk-footer__addr">
                    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.6" d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                    <span>
                        г. Владимир, пр-т Ленина, д. 5<br>
                        <a href="tel:+79108158165">+7 (910) 815-81-65</a><br>
                        <a href="mailto:manager_v1@mk37.ru">manager_v1@mk37.ru</a>
                    </span>
                </address>
            </div>
        </div>
        <div class="mk-footer__bottom">
            <span>© <?= date('Y') ?> <a href="https://t.me/cblpoFFum4uk">Popov</a></span>
        </div>
    </div>
</footer>

<script>
        (function(w, d, s, u, k) {w[k] = w[k] || function() {(w[k].q = w[k].q || []).push(arguments);};w[k].l = +new Date();var js = d.createElement(s);js.async = 1;js.src = u;var f = d.getElementsByTagName(s)[0];f.parentNode.insertBefore(js, f);})(window, document, 'script', 'https://kgymtrk.ru/embed/kagayaki.js', 'kagayaki');

        kagayaki('init', {
            publicId:  "e315b987-b23c-4729-812d-9953075c48a5",
            apiBase: 'https://kgymtrk.ru',
            cssUrl: 'https://kgymtrk.ru/embed/kagayaki.css',
            noBeacon: false,

            /**
             * widgetToken: '',
             * mode: 'moderator',
             */
        })
    </script>

<?
include __DIR__ . '/include/sprite.php';
if (strpos($mkCurDir, '/services/') === 0 || strpos($mkCurDir, '/dostavka/') === 0) {
    include __DIR__ . '/include/sprite_heavy.php';
}
?>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/redesign.js?v=<?= @filemtime(__DIR__ . '/js/redesign.js') ?>" defer></script>
</body>
</html>
