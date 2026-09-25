(function () {
    'use strict';

    // Mobile drawer
    function initDrawer() {
        var drawer = document.getElementById('mk-drawer');
        var burger = document.querySelector('.mk-burger');
        if (!drawer || !burger) return;

        function open() {
            drawer.hidden = false;
            requestAnimationFrame(function () { drawer.classList.add('is-open'); });
            document.body.classList.add('mk-lock');
            burger.setAttribute('aria-expanded', 'true');
            var first = drawer.querySelector('a, button');
            if (first) first.focus();
        }

        function close() {
            drawer.classList.remove('is-open');
            document.body.classList.remove('mk-lock');
            burger.setAttribute('aria-expanded', 'false');
            setTimeout(function () { drawer.hidden = true; }, 280);
            burger.focus();
        }

        burger.addEventListener('click', open);
        drawer.addEventListener('click', function (e) {
            if (e.target.closest('[data-mk-close]')) close();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !drawer.hidden) close();
        });
    }

    // Quantity stepper and "В корзину" on one row inside product cards
    function groupCardActions(root) {
        (root || document).querySelectorAll('.product-item:not(.mk-has-actions)').forEach(function (card) {
            var amount = card.querySelector(':scope > .product-item-info-container .product-item-amount');
            var buttons = card.querySelector(':scope > .product-item-info-container .product-item-button-container');
            if (!buttons) return;
            var btnBox = buttons.closest('.product-item-info-container');
            var row = document.createElement('div');
            row.className = 'mk-actions';
            btnBox.parentNode.insertBefore(row, btnBox);
            if (amount) row.appendChild(amount.closest('.product-item-info-container'));
            row.appendChild(btnBox);
            card.classList.add('mk-has-actions');
        });
    }

    var ICONS = {
        truck: '<svg viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M3 9h19v15H3zM22 14h6l5 5v5h-11z"/><circle cx="9" cy="26" r="3" fill="#fff"/><circle cx="27" cy="26" r="3" fill="#fff"/></svg>',
        shield: '<svg viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M18 4l12 4v9c0 7.5-5.2 12.7-12 15c-6.8-2.3-12-7.5-12-15V8z"/><path d="M13 18l3.5 3.5L23.5 14" stroke-linecap="round"/></svg>',
        percent: '<svg viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M27 9L9 27"/><circle cx="11" cy="11" r="3.5"/><circle cx="25" cy="25" r="3.5"/></svg>',
        headset: '<svg viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20v-3a11 11 0 0 1 22 0v3"/><path d="M7 20h4v7H8a1 1 0 0 1-1-1zM29 20h-4v7h3a1 1 0 0 0 1-1z"/><path d="M27 27c0 2.5-3 4-7 4"/></svg>'
    };

    var FEATURES = [
        ['truck', 'Быстрая доставка', 'Доставка по Ярославлю и всей России'],
        ['shield', 'Гарантия качества', 'Только сертифицированная продукция'],
        ['percent', 'Выгодные условия', 'Скидки, акции и специальные предложения'],
        ['headset', 'Поддержка клиентов', 'Консультации и помощь на всех этапах']
    ];

    function initHome() {
        if (!document.body.classList.contains('mk-index')) return;
        var slider = document.querySelector('.ls1 .slider_area');
        if (slider && !document.querySelector('.mk-features')) {
            var bar = document.createElement('section');
            bar.className = 'mk-features';
            bar.setAttribute('aria-label', 'Преимущества');
            bar.innerHTML = FEATURES.map(function (f) {
                return '<div class="mk-feature"><span class="mk-feature__icon" aria-hidden="true">' + ICONS[f[0]] + '</span>' +
                    '<div><p class="mk-feature__title">' + f[1] + '</p><p class="mk-feature__text">' + f[2] + '</p></div></div>';
            }).join('');
            slider.parentNode.insertBefore(bar, slider.nextSibling);
        }
        var titles = ['Популярные товары', 'Новинки'];
        document.querySelectorAll('.ls1 .body__body').forEach(function (block, i) {
            if (!titles[i] || block.previousElementSibling && block.previousElementSibling.classList.contains('mk-section-title')) return;
            var h = document.createElement('h2');
            h.className = 'mk-section-title';
            h.textContent = titles[i];
            block.parentNode.insertBefore(h, block);
        });
    }

    // Accessible names for icon-only controls rendered by Bitrix components
    function labelControls(root) {
        root = root || document;
        root.querySelectorAll('.bazarow_add_favor:not([aria-label])').forEach(function (b) {
            b.setAttribute('aria-label', 'Добавить в избранное');
        });
        root.querySelectorAll('.smart-filter-slider-handle.left:not([aria-label])').forEach(function (a) {
            a.setAttribute('aria-label', 'Минимальная цена');
        });
        root.querySelectorAll('.smart-filter-slider-handle.right:not([aria-label])').forEach(function (a) {
            a.setAttribute('aria-label', 'Максимальная цена');
        });
        root.querySelectorAll('a.fa-arrow-left:not([aria-label])').forEach(function (a) { a.setAttribute('aria-label', 'Предыдущая страница'); });
        root.querySelectorAll('a.fa-arrow-right:not([aria-label])').forEach(function (a) { a.setAttribute('aria-label', 'Следующая страница'); });
        root.querySelectorAll('.mk-main a[href]:not([aria-label])').forEach(function (a) {
            if (a.textContent.trim() || a.querySelector('img[alt]:not([alt=""]), [aria-label]')) return;
            var href = a.getAttribute('href');
            var same = Array.prototype.find.call(document.querySelectorAll('.mk-main a[href]'), function (x) {
                return x !== a && x.getAttribute('href') === href && x.textContent.trim();
            });
            var item = a.parentElement && a.parentElement.closest('[class*="item"], tr, li');
            var text = (same && same.textContent) || (item ? item.textContent : '');
            text = text.replace(/\s+/g, ' ').trim().slice(0, 90);
            if (text) a.setAttribute('aria-label', text);
        });
        root.querySelectorAll('.mk-main input:not([type=hidden]):not([aria-label])').forEach(function (i) {
            if (i.closest('label') || (i.id && document.querySelector('label[for="' + i.id + '"]'))) return;
            var hint = i.getAttribute('placeholder') || (i.closest('[class*="coupon"]') && 'Купон на скидку') || i.getAttribute('name');
            if (!hint) {
                var prev = i.previousElementSibling || (i.parentElement && i.parentElement.previousElementSibling);
                hint = prev ? prev.textContent.replace(/\s+/g, ' ').trim().slice(0, 60) : '';
            }
            if (hint) i.setAttribute('aria-label', hint);
        });
    }

    // Cart icon announces the count; the numeric badge link is a visual duplicate
    function labelCart() {
        var box = document.querySelector('.mk-shop-link--cart');
        if (!box) return;
        var badge = box.querySelector('.bask');
        var icon = box.querySelector('.mk-shop-link__icon');
        if (badge) {
            badge.setAttribute('tabindex', '-1');
            badge.setAttribute('aria-hidden', 'true');
        }
        if (icon) {
            var n = badge ? parseInt(badge.textContent, 10) || 0 : 0;
            icon.setAttribute('aria-label', n ? 'Корзина, товаров: ' + n : 'Корзина, пусто');
        }
    }

    // Hero carousel: pause control, and no auto-rotation for reduced motion
    function initSliderControls() {
        if (!window.jQuery) return;
        var $ = window.jQuery;
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        ['.slider_area', '.slider_area_1'].forEach(function (sel) {
            var el = document.querySelector(sel);
            if (!el || !el.classList.contains('slick-initialized') || el.querySelector('.mk-slider-toggle')) return;
            var $el = $(el);
            var PAUSE = '<svg viewBox="0 0 16 16" aria-hidden="true"><path fill="currentColor" d="M4 3h3v10H4zm5 0h3v10H9z"/></svg>';
            var PLAY = '<svg viewBox="0 0 16 16" aria-hidden="true"><path fill="currentColor" d="M5 3l8 5l-8 5z"/></svg>';
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'mk-slider-toggle';
            function set(paused) {
                btn.innerHTML = paused ? PLAY : PAUSE;
                btn.setAttribute('aria-label', paused ? 'Запустить прокрутку баннеров' : 'Остановить прокрутку баннеров');
                btn.setAttribute('aria-pressed', paused ? 'true' : 'false');
                $el.slick(paused ? 'slickPause' : 'slickPlay');
                el.dataset.mkPaused = paused ? '1' : '';
            }
            btn.addEventListener('click', function () { set(!el.dataset.mkPaused); });
            el.appendChild(btn);
            set(reduce);
        });
    }

    // Contacts page: structured office cards and labelled contact strip
    var OFFICE_ICONS = {
        addr: '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
        time: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M12 7v5l3 2"/></svg>',
        phone: '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>',
        mail: '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" d="M4 6h16v12H4z"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="m4 7 8 6 8-6"/></svg>'
    };

    function esc(s) {
        return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; });
    }

    function initContacts() {
        var strip = document.querySelector('.body__body-image:has(> .contact-item)');
        if (strip && !strip.dataset.mk) {
            strip.dataset.mk = '1';
            strip.querySelectorAll('.contact-item').forEach(function (item, i) {
                var inner = item.firstElementChild;
                if (!inner) return;
                if (i === 0) {
                    inner.innerHTML = '<span class="mk-label">Адрес</span>' + inner.innerHTML;
                } else if (inner.firstElementChild) {
                    inner.firstElementChild.classList.add('mk-label');
                    inner.firstElementChild.textContent = inner.firstElementChild.textContent.replace(':', '').trim();
                }
            });
        }
        document.querySelectorAll('.news-list > .body__body-image:not([data-mk])').forEach(function (card) {
            card.dataset.mk = '1';
            var data = {};
            card.querySelectorAll('.contacts').forEach(function (span) {
                var parts = span.innerHTML.split(/<br\s*\/?>/i);
                var label = (parts[0] || '').replace(/&nbsp;|:/g, ' ').replace(/<[^>]+>/g, '').trim().toLowerCase();
                var value = (parts.slice(1).join(' ') || '').replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
                if (label.indexOf('адрес') === 0) data.addr = value;
                else if (label.indexOf('время') === 0) data.time = value;
                else if (label.indexOf('телефон') === 0) data.phone = value;
                else if (label.indexOf('почт') !== -1) data.mail = value;
            });
            var img = card.querySelector('img');
            var city = (img && img.getAttribute('alt')) || ((data.addr || '').match(/г\.\s*([^,]+)/) || [])[1] || '';
            var rows = '';
            if (data.addr) rows += '<div class="mk-office__row">' + OFFICE_ICONS.addr + '<div><span class="mk-office__k">Адрес</span><span class="mk-office__v">' + esc(data.addr) + '</span></div></div>';
            if (data.time) rows += '<div class="mk-office__row">' + OFFICE_ICONS.time + '<div><span class="mk-office__k">Время работы</span><span class="mk-office__v">' + esc(data.time) + '</span></div></div>';
            if (data.phone) rows += '<div class="mk-office__row">' + OFFICE_ICONS.phone + '<div><span class="mk-office__k">Телефон</span><span class="mk-office__v"><a href="tel:' + esc(data.phone.replace(/[^\d+]/g, '')) + '">' + esc(data.phone) + '</a></span></div></div>';
            if (data.mail) rows += '<div class="mk-office__row">' + OFFICE_ICONS.mail + '<div><span class="mk-office__k">Почта</span><span class="mk-office__v"><a href="mailto:' + esc(data.mail) + '">' + esc(data.mail) + '</a></span></div></div>';
            if (!rows) return;
            var box = document.createElement('div');
            box.className = 'mk-office';
            box.innerHTML = (city ? '<h3 class="mk-office__city">' + esc(city) + '</h3>' : '') + '<div class="mk-office__list">' + rows + '</div>' +
                (data.addr ? '<a class="mk-pill mk-pill--outline mk-office__route" target="_blank" rel="noopener" href="https://yandex.ru/maps/?text=' + encodeURIComponent(data.addr) + '">Построить маршрут</a>' : '');
            Array.prototype.slice.call(card.childNodes).forEach(function (n) {
                if (n.nodeType === 1 && n.tagName === 'A' && n.querySelector('img')) return;
                card.removeChild(n);
            });
            card.appendChild(box);
            var photoLink = card.querySelector('a');
            if (photoLink) { photoLink.setAttribute('tabindex', '-1'); photoLink.setAttribute('aria-hidden', 'true'); }
        });
    }

    // Favourite buttons: expose state; header icon gets a spoken label
    function syncFavourites() {
        document.querySelectorAll('.bazarow_add_favor').forEach(function (b) {
            var on = b.classList.contains('in-favor');
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
            b.setAttribute('aria-label', on ? 'Убрать из избранного' : 'Добавить в избранное');
        });
        var fav = document.querySelector('.mk-shop-link--fav .favorite');
        if (fav) {
            var n = parseInt((fav.querySelector('.favor-list-wrap') || {}).textContent, 10) || 0;
            fav.setAttribute('aria-label', n ? 'Избранное, товаров: ' + n : 'Избранное, пусто');
            var label = document.querySelector('.mk-shop-link--fav .mk-shop-link__label');
            if (label) label.setAttribute('tabindex', '-1');
        }
    }

    // Keep keyboard focus inside the open drawer
    function trapDrawerFocus() {
        var drawer = document.getElementById('mk-drawer');
        if (!drawer) return;
        drawer.addEventListener('keydown', function (e) {
            if (e.key !== 'Tab') return;
            var items = drawer.querySelectorAll('a[href], button:not([disabled])');
            if (!items.length) return;
            var first = items[0], last = items[items.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        });
    }

    function init() {
        initDrawer();
        trapDrawerFocus();
        initHome();
        groupCardActions();
        labelControls();
        labelCart();
        initContacts();
        syncFavourites();
        document.addEventListener('click', function (e) {
            if (e.target.closest('.bazarow_add_favor')) { setTimeout(syncFavourites, 700); setTimeout(syncFavourites, 1600); }
        });
        // slick initialises on jQuery ready; give it a tick
        setTimeout(initSliderControls, 300);
        if (window.BX && BX.addCustomEvent) {
            BX.addCustomEvent('OnBasketChange', function () { setTimeout(labelCart, 600); });
            BX.addCustomEvent('onAjaxSuccess', function () { setTimeout(labelControls, 100); });
        }
        setTimeout(labelControls, 1500);
        // Bitrix may re-render product lists via AJAX (pagination, filter)
        if ('MutationObserver' in window) {
            var pending = false;
            new MutationObserver(function () {
                if (pending) return;
                pending = true;
                requestAnimationFrame(function () { pending = false; groupCardActions(); labelControls(); labelCart(); });
            }).observe(document.querySelector('.mk-main') || document.body, { childList: true, subtree: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
