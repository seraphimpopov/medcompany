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
        var n = badge ? parseInt(badge.textContent, 10) || 0 : 0;
        if (icon) {
            icon.setAttribute('aria-label', n ? 'Корзина, товаров: ' + n : 'Корзина, пусто');
        }
        // Mobile header icons mirror the desktop counters
        var favWrap = document.querySelector('.favor-list-wrap');
        var favN = favWrap ? parseInt(favWrap.textContent, 10) || 0 : 0;
        [['a[href="/personal/cart/"]', n, 'Корзина'], ['a[href="/personal/wishlist/"]', favN, 'Избранное']].forEach(function (cfg) {
            var link = document.querySelector('.mk-header__mobile-icons ' + cfg[0]);
            if (!link) return;
            var b = link.querySelector('.mk-mbadge');
            if (!b) { b = document.createElement('span'); b.className = 'mk-mbadge'; b.setAttribute('aria-hidden', 'true'); link.appendChild(b); }
            b.textContent = cfg[1];
            b.hidden = !cfg[1];
            link.setAttribute('aria-label', cfg[1] ? cfg[2] + ', товаров: ' + cfg[1] : cfg[2]);
        });
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
            if (sel === '.slider_area' && !el.querySelector('.mk-slider-arrow')) {
                [['prev', 'Предыдущий баннер', 'M15 6l-6 6 6 6'], ['next', 'Следующий баннер', 'M9 6l6 6-6 6']].forEach(function (a) {
                    var arrow = document.createElement('button');
                    arrow.type = 'button';
                    arrow.className = 'mk-slider-arrow mk-slider-arrow--' + a[0];
                    arrow.setAttribute('aria-label', a[1]);
                    arrow.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" d="' + a[2] + '"/></svg>';
                    arrow.addEventListener('click', function () { $el.slick(a[0] === 'prev' ? 'slickPrev' : 'slickNext'); });
                    el.appendChild(arrow);
                });
            }
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
                var decode = function (html) { var t = document.createElement('div'); t.innerHTML = html; return (t.textContent || '').replace(/ /g, ' ').replace(/\s+/g, ' ').trim(); };
                var label = decode(parts[0] || '').replace(':', '').trim().toLowerCase();
                var value = decode(parts.slice(1).join(' ') || '');
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

    // Focus rings only while the visitor navigates with the keyboard
    function initKeyboardMode() {
        var body = document.body;
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Tab' || e.key.indexOf('Arrow') === 0) body.classList.add('mk-kbd');
        }, true);
        ['mousedown', 'pointerdown', 'touchstart'].forEach(function (type) {
            document.addEventListener(type, function () { body.classList.remove('mk-kbd'); }, true);
        });
    }

    // Transliteration so "ивоклар" finds "Ivoclar" and vice versa
    var RU = { 'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sch', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya' };
    function normalize(s) {
        s = String(s).toLowerCase().replace(/ё/g, 'е');
        var lat = s.replace(/[а-я]/g, function (c) { return RU[c] || ''; });
        // collapse spelling variants: k/c/q, ph/f, w/v, double letters, y/i
        return lat.replace(/ph/g, 'f').replace(/[cq]/g, 'k').replace(/w/g, 'v').replace(/y/g, 'i').replace(/x/g, 'ks').replace(/(.)\1+/g, '$1').replace(/[^a-z0-9]/g, '');
    }

    // Sidebar lists (catalog + manufacturers) get an inline search
    function initSidebarSearch() {
        document.querySelectorAll('.sum_cat > a.cat').forEach(function (head) {
            var list = head.nextElementSibling;
            if (!list || head.dataset.mkSearch) return;
            head.dataset.mkSearch = '1';
            var wrap = document.createElement('div');
            wrap.className = 'mk-side-head';
            head.parentNode.insertBefore(wrap, head);
            wrap.appendChild(head);
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'mk-side-head__btn';
            btn.setAttribute('aria-label', 'Поиск: ' + head.textContent.trim().toLowerCase());
            btn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M9.5 3a6.5 6.5 0 0 1 5.2 10.4l5.4 5.5-1.4 1.4-5.5-5.4A6.5 6.5 0 1 1 9.5 3m0 2a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9"/></svg>';
            wrap.appendChild(btn);
            var input = document.createElement('input');
            input.type = 'search';
            input.className = 'mk-side-head__input';
            input.placeholder = head.textContent.trim().toLowerCase().indexOf('производ') !== -1 ? 'Найти производителя' : 'Найти раздел';
            input.setAttribute('aria-label', input.placeholder);
            input.hidden = true;
            wrap.appendChild(input);
            var empty = document.createElement('p');
            empty.className = 'mk-side-empty';
            empty.textContent = 'Ничего не найдено';
            empty.hidden = true;
            list.parentNode.insertBefore(empty, list.nextSibling);
            var items = list.querySelectorAll('.mk-side__item');
            var skeleton = function (t) { return t.replace(/[aeiou]/g, ''); };
            var filter = function () {
                var q = normalize(input.value);
                var qs = skeleton(q);
                var shown = 0;
                items.forEach(function (li) {
                    var name = normalize(li.textContent);
                    var ok = !q || name.indexOf(q) !== -1 || (qs.length >= 3 && skeleton(name).indexOf(qs) !== -1);
                    li.hidden = !ok;
                    if (ok) shown++;
                });
                empty.hidden = shown > 0;
            };
            var open = function (state) {
                wrap.classList.toggle('is-searching', state);
                input.hidden = !state;
                btn.setAttribute('aria-label', state ? 'Закрыть поиск' : 'Поиск: ' + head.textContent.trim().toLowerCase());
                btn.innerHTML = state
                    ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>'
                    : '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M9.5 3a6.5 6.5 0 0 1 5.2 10.4l5.4 5.5-1.4 1.4-5.5-5.4A6.5 6.5 0 1 1 9.5 3m0 2a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9"/></svg>';
                if (state) { input.focus(); } else { input.value = ''; filter(); }
            };
            btn.addEventListener('click', function () { open(!wrap.classList.contains('is-searching')); });
            input.addEventListener('input', filter);
            input.addEventListener('keydown', function (e) { if (e.key === 'Escape') open(false); });
        });
    }

    // Banners: one height; near-matching banners fill the frame, others sit on their own edge colour
    function fitBanners() {
        var TARGET = 2.95;
        document.querySelectorAll('.slider_area .slider_area__item').forEach(function (item) {
            var img = item.querySelector('img');
            if (!img || item.dataset.mkFit) return;
            var apply = function () {
                if (!img.naturalWidth) return;
                item.dataset.mkFit = '1';
                var ratio = img.naturalWidth / img.naturalHeight;
                if (Math.abs(ratio / TARGET - 1) <= 0.2) {
                    item.classList.add('mk-fit-cover');
                    return;
                }
                item.classList.add('mk-fit-contain');
                try {
                    var c = document.createElement('canvas');
                    var w = c.width = 48, h = c.height = Math.max(8, Math.round(48 / ratio));
                    var ctx = c.getContext('2d', { willReadFrequently: true });
                    ctx.drawImage(img, 0, 0, w, h);
                    var d = ctx.getImageData(0, 0, w, h).data;
                    var sum = [0, 0, 0], n = 0;
                    var take = function (x, y) { var i = (y * w + x) * 4; sum[0] += d[i]; sum[1] += d[i + 1]; sum[2] += d[i + 2]; n++; };
                    for (var x = 0; x < w; x++) { take(x, 0); take(x, h - 1); }
                    for (var y = 0; y < h; y++) { take(0, y); take(w - 1, y); }
                    item.style.backgroundColor = 'rgb(' + Math.round(sum[0] / n) + ',' + Math.round(sum[1] / n) + ',' + Math.round(sum[2] / n) + ')';
                } catch (e) { /* cross-origin image: keep neutral background */ }
            };
            if (img.complete) apply(); else img.addEventListener('load', apply, { once: true });
        });
    }

    // Product page: description blocks become tabs
    function initProductTabs() {
        var blocks = Array.prototype.filter.call(document.querySelectorAll('.mk-main .block-biography.row'), function (b) {
            return b.querySelector('.block .title') && !b.closest('.bx-newsdetail, .mk-article') && document.querySelector('.product_row.row[id]');
        });
        if (blocks.length < 2 || document.querySelector('.mk-tabs')) return;
        var box = document.createElement('section');
        box.className = 'mk-tabs';
        var bar = document.createElement('div');
        bar.className = 'mk-tabs__bar';
        bar.setAttribute('role', 'tablist');
        box.appendChild(bar);
        blocks[0].parentNode.insertBefore(box, blocks[0]);
        blocks.forEach(function (block, i) {
            var title = block.querySelector('.block .title');
            var tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'mk-tabs__tab';
            tab.id = 'mk-tab-' + i;
            tab.setAttribute('role', 'tab');
            tab.setAttribute('aria-controls', 'mk-panel-' + i);
            tab.textContent = title.textContent.trim();
            bar.appendChild(tab);
            var panel = document.createElement('div');
            panel.className = 'mk-tabs__panel';
            panel.id = 'mk-panel-' + i;
            panel.setAttribute('role', 'tabpanel');
            panel.setAttribute('aria-labelledby', tab.id);
            var text = block.querySelector('.block .text') || block.querySelector('.block');
            panel.appendChild(text);
            box.appendChild(panel);
            block.remove();
        });
        // "Key: value" lists become a specs table; long plain text flows into columns
        box.querySelectorAll('.mk-tabs__panel').forEach(function (panel) {
            var text = panel.querySelector('.text') || panel.firstElementChild;
            if (!text) return;
            var items = text.querySelectorAll('li');
            var pairs = Array.prototype.map.call(items, function (li) {
                var t = li.textContent.replace(/\s+/g, ' ').trim();
                var i = t.indexOf(':');
                return i > 0 && i < 60 ? [t.slice(0, i).trim(), t.slice(i + 1).trim()] : null;
            });
            if (items.length >= 2 && pairs.every(Boolean)) {
                var table = document.createElement('table');
                table.className = 'mk-specs';
                table.innerHTML = '<tbody>' + pairs.map(function (p) { return '<tr><th scope="row">' + esc(p[0]) + '</th><td>' + esc(p[1]) + '</td></tr>'; }).join('') + '</tbody>';
                text.innerHTML = '';
                text.appendChild(table);
            } else if (!items.length && text.textContent.trim().length > 700) {
                text.classList.add('mk-cols');
            }
        });
        var tabs = bar.querySelectorAll('.mk-tabs__tab');
        var select = function (idx) {
            tabs.forEach(function (t, i) {
                var on = i === idx;
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                t.tabIndex = on ? 0 : -1;
                document.getElementById('mk-panel-' + i).hidden = !on;
            });
        };
        tabs.forEach(function (t, i) {
            t.addEventListener('click', function () { select(i); });
            t.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                    var n = (i + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length;
                    select(n);
                    tabs[n].focus();
                }
            });
        });
        select(0);
    }

    // Basket: "select all" + delete selected
    function initBasketSelection() {
        var table = document.getElementById('basket-item-table');
        var header = document.querySelector('.basket-items-list-header');
        if (!table || !header) return;
        var rows = function () { return Array.prototype.slice.call(table.querySelectorAll('tr.basket-items-list-item-container')); };
        var bar = header.querySelector('.mk-basket-select');
        if (!bar) {
            bar = document.createElement('div');
            bar.className = 'mk-basket-select';
            bar.innerHTML = '<label class="mk-check mk-check--inline"><input type="checkbox" class="mk-select-all" checked><span class="mk-check__box" aria-hidden="true"></span><span class="mk-check__text">Выбрать все</span></label>' +
                '<button type="button" class="mk-basket-delete" disabled>Удалить выбранные</button>';
            header.insertBefore(bar, header.firstChild);
            bar.querySelector('.mk-select-all').addEventListener('change', function () {
                var on = this.checked;
                rows().forEach(function (r) { var c = r.querySelector('.mk-row-check input'); if (c) c.checked = on; });
                sync();
            });
            bar.querySelector('.mk-basket-delete').addEventListener('click', function () {
                var chosen = rows().filter(function (r) { var c = r.querySelector('.mk-row-check input'); return c && c.checked; });
                if (!chosen.length) return;
                if (!window.confirm('Удалить из корзины выбранные товары (' + chosen.length + ')?')) return;
                chosen.forEach(function (r, i) {
                    var del = r.querySelector('[data-entity="basket-item-delete"]');
                    if (del) setTimeout(function () { del.click(); }, i * 350);
                });
            });
        }
        var sync = function () {
            var all = rows();
            var checked = all.filter(function (r) { var c = r.querySelector('.mk-row-check input'); return c && c.checked; });
            var master = bar.querySelector('.mk-select-all');
            master.checked = all.length > 0 && checked.length === all.length;
            master.indeterminate = checked.length > 0 && checked.length < all.length;
            var del = bar.querySelector('.mk-basket-delete');
            del.disabled = checked.length === 0;
            del.textContent = checked.length ? 'Удалить выбранные (' + checked.length + ')' : 'Удалить выбранные';
        };
        rows().forEach(function (r) {
            if (r.querySelector('.mk-row-check')) return;
            var cell = r.querySelector('.basket-items-list-item-descriptions-inner');
            if (!cell) return;
            var name = (r.querySelector('.basket-item-info-name') || {}).textContent || 'товар';
            var label = document.createElement('label');
            label.className = 'mk-check mk-check--inline mk-row-check';
            label.innerHTML = '<input type="checkbox" checked aria-label="Выбрать: ' + esc(name.trim()) + '"><span class="mk-check__box" aria-hidden="true"></span>';
            cell.insertBefore(label, cell.firstChild);
            label.querySelector('input').addEventListener('change', sync);
        });
        sync();
    }

    // Search page: sort select becomes the same chip bar as in the catalog
    function initSearchSort() {
        var toolbar = document.querySelector('.ms-toolbar');
        if (!toolbar || toolbar.querySelector('.mk-sortbar')) return;
        var select = toolbar.querySelector('select[name="sort"]') || toolbar.querySelector('select');
        if (!select) return;
        var apply = function (value) {
            select.value = value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            if (toolbar.tagName === 'FORM' && !toolbar.classList.contains('ms-toolbar-enhanced')) toolbar.submit();
        };
        var bar = document.createElement('div');
        bar.className = 'mk-sortbar mk-sortbar--search';
        bar.innerHTML = '<span class="mk-sortbar__label">Сортировка:</span><div class="mk-sortbar__options"></div>'
            + '<label class="mk-sortbar__select"><span class="mk-sortbar__select-text">Сортировка</span><select aria-label="Сортировка товаров"></select></label>';
        var opts = bar.querySelector('.mk-sortbar__options');
        var compact = bar.querySelector('.mk-sortbar__select select');
        Array.prototype.forEach.call(select.options, function (o) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'mk-sortbar__opt' + (o.selected ? ' is-active' : '');
            b.textContent = o.textContent.trim();
            if (o.selected) b.setAttribute('aria-current', 'true');
            b.addEventListener('click', function () { apply(o.value); });
            opts.appendChild(b);
            var copy = new Option(o.textContent.trim(), o.value, false, o.selected);
            compact.appendChild(copy);
        });
        compact.addEventListener('change', function () { apply(compact.value); });
        var label = select.closest('label');
        (label || select).classList.add('mk-visually-hidden-control');
        // "Показывать по" lives inside the bar, as in the catalog
        var count = toolbar.querySelector('select[name="count"]');
        if (count) {
            var oldLabel = count.closest('label');
            var limit = document.createElement('label');
            limit.className = 'mk-sortbar__limit';
            limit.innerHTML = '<span class="mk-sortbar__limit-text">Показывать по</span>';
            limit.appendChild(count);
            bar.appendChild(limit);
            if (oldLabel && oldLabel !== limit) oldLabel.remove();
        }
        toolbar.insertBefore(bar, toolbar.firstChild);
    }

    // Two-handle price slider with the catalog filter's look; the inputs stay the source of truth
    function priceSlider(minInput, maxInput, lo, hi) {
        var wrap = document.createElement('div');
        wrap.className = 'smart-filter-slider-track-container';
        wrap.innerHTML = '<div class="smart-filter-slider-track">'
            + '<div class="smart-filter-slider-price-bar-v"></div>'
            + '<div class="smart-filter-slider-range" style="left:0;right:0">'
            + '<button type="button" class="smart-filter-slider-handle left" aria-label="Минимальная цена"></button>'
            + '<button type="button" class="smart-filter-slider-handle right" aria-label="Максимальная цена"></button>'
            + '</div></div>';
        var track = wrap.querySelector('.smart-filter-slider-track');
        var bar = wrap.querySelector('.smart-filter-slider-price-bar-v');
        var left = wrap.querySelector('.left');
        var right = wrap.querySelector('.right');
        var span = hi - lo;
        var value = function (input, fallback) {
            var v = parseFloat(String(input.value).replace(',', '.'));
            return isNaN(v) ? fallback : Math.min(hi, Math.max(lo, v));
        };
        var draw = function () {
            var a = (value(minInput, lo) - lo) / span;
            var b = (value(maxInput, hi) - lo) / span;
            if (a > b) { var t = a; a = b; b = t; }
            left.style.left = (a * 100) + '%';
            right.style.right = ((1 - b) * 100) + '%';
            bar.style.left = (a * 100) + '%';
            bar.style.right = ((1 - b) * 100) + '%';
            left.setAttribute('aria-valuetext', Math.round(lo + a * span) + ' ₽');
            right.setAttribute('aria-valuetext', Math.round(lo + b * span) + ' ₽');
        };
        var set = function (input, v) {
            v = Math.round(v);
            var edge = input === minInput ? v <= lo : v >= hi;
            input.value = edge ? '' : String(v);
            draw();
        };
        var drag = function (handle, input) {
            handle.addEventListener('pointerdown', function (e) {
                e.preventDefault();
                handle.setPointerCapture(e.pointerId);
                var move = function (ev) {
                    var r = track.getBoundingClientRect();
                    var f = Math.min(1, Math.max(0, (ev.clientX - r.left) / r.width));
                    var v = lo + f * span;
                    if (input === minInput) v = Math.min(v, value(maxInput, hi));
                    else v = Math.max(v, value(minInput, lo));
                    set(input, v);
                };
                var up = function () {
                    handle.removeEventListener('pointermove', move);
                    handle.removeEventListener('pointerup', up);
                };
                handle.addEventListener('pointermove', move);
                handle.addEventListener('pointerup', up);
            });
            handle.addEventListener('keydown', function (e) {
                var step = Math.max(1, Math.round(span / 50));
                var current = value(input, input === minInput ? lo : hi);
                if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') { e.preventDefault(); set(input, current - step); }
                if (e.key === 'ArrowRight' || e.key === 'ArrowUp') { e.preventDefault(); set(input, current + step); }
            });
        };
        drag(left, minInput);
        drag(right, maxInput);
        minInput.addEventListener('input', draw);
        maxInput.addEventListener('input', draw);
        draw();
        return wrap;
    }

    // Search page: the filter gets the catalog filter's markup (sections, check lists, "Показать" / "Сбросить")
    function initSearchFilter() {
        var form = document.getElementById('ms-filter-form');
        if (!form || form.dataset.mkFilter) return;
        form.dataset.mkFilter = '1';
        form.classList.add('mk-filter', 'mk-sfilter');
        var card = document.createElement('div');
        card.className = 'smart-filter';
        var uid = 0;

        var section = function (title) {
            var box = document.createElement('div');
            box.className = 'smart-filter-parameters-box bx-active';
            var head = document.createElement('button');
            head.type = 'button';
            head.className = 'smart-filter_title';
            head.setAttribute('aria-expanded', 'true');
            head.textContent = title;
            head.addEventListener('click', function () {
                var open = !box.classList.contains('bx-active');
                box.classList.toggle('bx-active', open);
                head.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
            var collapse = document.createElement('div');
            collapse.className = 'mk-collapse';
            var inner = document.createElement('div');
            inner.className = 'mk-collapse__inner';
            collapse.appendChild(inner);
            box.appendChild(head);
            box.appendChild(collapse);
            card.appendChild(box);
            return inner;
        };
        var checkRow = function (input, text, count) {
            var label = document.createElement('label');
            label.className = 'mk-check' + (input.type === 'radio' ? ' mk-check--radio' : '');
            if (!input.id) input.id = 'mk-sf-' + (++uid);
            label.setAttribute('for', input.id);
            label.appendChild(input);
            label.insertAdjacentHTML('beforeend', '<span class="mk-check__box" aria-hidden="true"></span>');
            var t = document.createElement('span');
            t.className = 'mk-check__text';
            t.textContent = text;
            label.appendChild(t);
            if (count) {
                var c = document.createElement('span');
                c.className = 'mk-check__count';
                c.textContent = count;
                label.appendChild(c);
            }
            return label;
        };

        // price
        var range = form.querySelector('.ms-price-range');
        var active = false;
        if (range) {
            var inputs = range.querySelectorAll('input');
            var grid = document.createElement('div');
            grid.className = 'smart-filter-digits mk-range-inputs';
            ['от', 'до'].forEach(function (word, i) {
                var inp = inputs[i];
                if (!inp) return;
                if (inp.value) active = true;
                var l = document.createElement('label');
                l.className = 'mk-range-inputs__field';
                l.innerHTML = '<span>' + word + '</span>';
                inp.placeholder = '';
                inp.setAttribute('inputmode', 'numeric');
                inp.setAttribute('aria-label', 'Цена ' + word);
                l.appendChild(inp);
                grid.appendChild(l);
            });
            var priceBox = section('Цена, ₽');
            priceBox.appendChild(grid);
            var lo = parseInt(range.getAttribute('data-min'), 10);
            var hi = parseInt(range.getAttribute('data-max'), 10);
            if (inputs[0] && inputs[1] && !isNaN(lo) && !isNaN(hi) && hi > lo) {
                inputs[0].placeholder = String(lo);
                inputs[1].placeholder = String(hi);
                priceBox.appendChild(priceSlider(inputs[0], inputs[1], lo, hi));
            }
            range.remove();
        }

        // manufacturer: single choice, so radios with counts instead of a select
        var select = form.querySelector('select[name="brand"]');
        if (select) {
            var inner = section('Производитель');
            var list = document.createElement('div');
            list.className = 'mk-checklist';
            list.setAttribute('role', 'radiogroup');
            list.setAttribute('aria-label', 'Производитель');
            Array.prototype.forEach.call(select.options, function (o) {
                var m = o.textContent.trim().match(/^(.*?)\s*\((\d+)\)\s*$/);
                var radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'brand';
                radio.value = o.value;
                radio.checked = o.selected;
                if (o.selected && o.value) active = true;
                list.appendChild(checkRow(radio, m ? m[1] : o.textContent.trim(), m ? m[2] : ''));
            });
            if (select.options.length > 9) {
                var search = document.createElement('input');
                search.type = 'search';
                search.className = 'mk-filter-search';
                search.placeholder = 'Найти: производитель';
                search.setAttribute('aria-label', 'Поиск по списку производителей');
                search.autocomplete = 'off';
                search.addEventListener('input', function () {
                    var q = normalize(search.value);
                    list.querySelectorAll('.mk-check').forEach(function (row) {
                        row.hidden = !!q && normalize(row.textContent).indexOf(q) === -1;
                    });
                });
                inner.appendChild(search);
            }
            inner.appendChild(list);
            (select.closest('label') || select).remove();
        }

        // stock / photo switches
        var checks = form.querySelectorAll('label.ms-check');
        if (checks.length) {
            var group = document.createElement('div');
            group.className = 'mk-checklist';
            checks.forEach(function (l) {
                var inp = l.querySelector('input');
                if (!inp) return;
                if (inp.checked) active = true;
                group.appendChild(checkRow(inp, l.textContent.trim().replace(/^Только /, 'Только '), ''));
                l.remove();
            });
            section('Наличие и фото').appendChild(group);
        }

        var submit = form.querySelector('.ms-primary');
        var reset = form.querySelector('.ms-reset');
        var actions = document.createElement('div');
        actions.className = 'smart-filter_bottons';
        if (submit) {
            submit.className = 'btn mk-filter-apply';
            submit.textContent = 'Показать';
            actions.appendChild(submit);
        }
        if (reset) {
            reset.className = 'mk-filter-reset';
            reset.hidden = !active;
            actions.appendChild(reset);
        }
        form.appendChild(card);
        form.appendChild(actions);
        var details = form.closest('.ms-filter-details');
        if (details) details.classList.add('mk-sfilter-wrap');
    }

    // Breadcrumbs: "…" unfolds the middle of a long path
    function initCrumbs() {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest && e.target.closest('.mk-crumbs__more');
            if (!btn) return;
            var nav = btn.closest('.mk-crumbs');
            nav.classList.add('is-expanded');
            btn.setAttribute('aria-expanded', 'true');
            var first = nav.querySelector('.mk-crumbs__item--folded a');
            if (first) first.focus();
        });
    }

    // Add to cart without the Bitrix popup: the button turns into "В корзине" and then leads to the cart
    var CART_URL = '/personal/cart/';
    var cartIds = {};
    function markInCart(button) {
        if (!button || button.classList.contains('mk-in-cart')) return;
        button.classList.add('mk-in-cart');
        button.textContent = 'В корзине';
        button.setAttribute('aria-label', 'Товар в корзине — перейти в корзину');
        button.setAttribute('title', 'Перейти в корзину');
    }
    function catalogObjects() {
        var list = [];
        var types = [window.JCCatalogItem, window.JCCatalogElement].filter(Boolean);
        if (!types.length) return list;
        Object.keys(window).forEach(function (key) {
            if (key.indexOf('ob') !== 0) return;
            var obj;
            try { obj = window[key]; } catch (e) { return; }
            if (!obj || typeof obj !== 'object') return;
            for (var i = 0; i < types.length; i++) {
                if (obj instanceof types[i]) { list.push(obj); break; }
            }
        });
        return list;
    }
    function currentProductId(obj) {
        if (obj.offers && obj.offers.length && typeof obj.offerNum === 'number' && obj.offers[obj.offerNum]) {
            return parseInt(obj.offers[obj.offerNum].ID, 10);
        }
        return obj.product ? parseInt(obj.product.id, 10) : 0;
    }
    function markCatalogButtons() {
        if (!Object.keys(cartIds).length) return;
        catalogObjects().forEach(function (obj) {
            if (obj.canBuy === false) return;
            if (cartIds[currentProductId(obj)]) {
                markInCart(obj.obBuyBtn);
                markInCart(obj.obAddToBasketBtn);
            }
        });
    }
    // "Добавлено в корзину" card: top right on desktop, a bottom sheet-like card on phones
    var toastTimer = null;
    function showCartToast(obj) {
        var name = obj.product && obj.product.name ? obj.product.name : '';
        var root = obj.obProduct || null;
        var img = '';
        if (obj.product && obj.product.pict && obj.product.pict.SRC) img = obj.product.pict.SRC;
        if (!img && root) {
            var pic = root.querySelector('img');
            if (pic) img = pic.currentSrc || pic.src;
        }
        if (!img) {
            var mainPic = document.querySelector('.product_row img[itemprop="image"]');
            if (mainPic) img = mainPic.currentSrc || mainPic.src;
        }
        var qty = obj.obQuantity && obj.obQuantity.value ? parseFloat(obj.obQuantity.value) : 1;
        var toast = document.getElementById('mk-cart-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'mk-cart-toast';
            toast.className = 'mk-cart-toast';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
            toast.addEventListener('mouseenter', function () { clearTimeout(toastTimer); });
            toast.addEventListener('mouseleave', function () { hideLater(2500); });
            toast.addEventListener('click', function (e) {
                if (e.target.closest('.mk-cart-toast__close')) hide();
            });
            // swipe down to dismiss on phones
            var startY = null;
            toast.addEventListener('touchstart', function (e) { startY = e.touches[0].clientY; }, { passive: true });
            toast.addEventListener('touchmove', function (e) {
                if (startY !== null && e.touches[0].clientY - startY > 40) { startY = null; hide(); }
            }, { passive: true });
        }
        var esc = function (t) { var d = document.createElement('div'); d.textContent = t; return d.innerHTML; };
        toast.innerHTML = '<span class="mk-cart-toast__img">' + (img ? '<img src="' + esc(img) + '" alt="">' : '') + '</span>'
            + '<div class="mk-cart-toast__body">'
            + '<div class="mk-cart-toast__status"><span class="mk-cart-toast__check" aria-hidden="true"></span>Добавлено в корзину</div>'
            + (name ? '<div class="mk-cart-toast__name">' + esc(name) + '</div>' : '')
            + '<div class="mk-cart-toast__meta">' + (qty > 1 ? esc(String(qty)) + ' шт.' : '1 шт.') + '</div>'
            + '<a class="mk-cart-toast__go" href="' + CART_URL + '">Перейти в корзину</a>'
            + '</div>'
            + '<button type="button" class="mk-cart-toast__close" aria-label="Закрыть уведомление"></button>';
        toast.classList.remove('is-open');
        void toast.offsetWidth;
        toast.classList.add('is-open');
        hideLater(5000);
        function hide() { clearTimeout(toastTimer); toast.classList.remove('is-open'); }
        function hideLater(ms) { clearTimeout(toastTimer); toastTimer = setTimeout(hide, ms); }
    }

    function patchBasketResult(Ctor) {
        if (!Ctor || !Ctor.prototype || Ctor.prototype.__mkQuietBasket) return;
        var original = Ctor.prototype.basketResult;
        Ctor.prototype.__mkQuietBasket = true;
        Ctor.prototype.basketResult = function (result) {
            var isBuy = this.basketMode === 'BUY' || (this.basketAction === 'BUY' && this.basketMode !== 'ADD');
            if (!result || result.STATUS !== 'OK' || isBuy) {
                // errors still explain themselves in the popup; "Купить" still redirects
                return original.apply(this, arguments);
            }
            if (this.obPopupWin) this.obPopupWin.close();
            if (typeof this.setAnalyticsDataLayer === 'function') this.setAnalyticsDataLayer('addToCart');
            BX.onCustomEvent('OnBasketChange');
            if (this.obProduct && BX.findParent(this.obProduct, { className: 'bx_sale_gift_main_products' }, 10)) {
                BX.onCustomEvent('onAddToBasketMainProduct', [this]);
            }
            cartIds[currentProductId(this)] = true;
            markInCart(this.obBuyBtn);
            markInCart(this.obAddToBasketBtn);
            showCartToast(this);
        };
    }
    function initCartButtons() {
        patchBasketResult(window.JCCatalogItem);
        patchBasketResult(window.JCCatalogElement);
        // a click on "В корзине" goes to the cart instead of adding the product again
        document.addEventListener('click', function (e) {
            var button = e.target.closest && e.target.closest('.mk-in-cart');
            if (!button) return;
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            window.location.href = CART_URL;
        }, true);
        if (!window.fetch || !document.querySelector('[id$="_buy_link"], [id$="_add_basket_link"]')) return;
        fetch('/bitrix/templates/medcompany_v2/ajax/basket-ids.php', { credentials: 'same-origin', cache: 'no-store' })
            .then(function (r) { return r.ok ? r.json() : []; })
            .then(function (ids) {
                (ids || []).forEach(function (id) { cartIds[id] = true; });
                markCatalogButtons();
                // product objects may be created after this script runs
                if (document.readyState !== 'complete') window.addEventListener('load', markCatalogButtons);
                setTimeout(markCatalogButtons, 1200);
            })
            .catch(function () {});
    }

    // Cart: the empty state (server-rendered or shown after the last item is removed) gets the site's look
    function emptyCart() {
        document.querySelectorAll('.bx-sbb-empty-cart-container:not([data-mk-empty])').forEach(function (box) {
            box.setAttribute('data-mk-empty', '1');
            box.innerHTML = '<span class="mk-empty-cart__ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" d="M3 4h2.2l2.3 11.2a1.5 1.5 0 0 0 1.5 1.2h8.4a1.5 1.5 0 0 0 1.5-1.1L20.5 8H6.1"/><circle cx="9.5" cy="20" r="1.3" fill="currentColor"/><circle cx="17" cy="20" r="1.3" fill="currentColor"/></svg></span>'
                + '<h2 class="mk-empty-cart__title">Ваша корзина пуста</h2>'
                + '<p class="mk-empty-cart__text">Загляните в каталог или воспользуйтесь поиском — добавленные товары появятся здесь.</p>'
                + '<div class="mk-empty-cart__actions"><a class="mk-pill mk-pill--solid" href="/catalog/">Перейти в каталог</a><a class="mk-pill mk-pill--outline" href="/">На главную</a></div>';
        });
    }

    // Home: the sticky catalog block always fits between its current top and the bottom of the screen
    // (or the end of its column), so neither its top nor its rounded bottom leaves the viewport
    function initHomeSidebar() {
        var box = document.querySelector('.mk-index .catalog > .sum_cat');
        if (!box) return;
        var queued = false;
        var fit = function () {
            queued = false;
            if (window.innerWidth < 992) { box.style.height = ''; return; }
            // on QHD / 4K the page blocks are zoomed: rects are in screen pixels, the height is set in CSS pixels
            var zoom = box.currentCSSZoom || parseFloat(getComputedStyle(document.getElementById('mk-main') || document.body).zoom) || 1;
            var gap = 16 * zoom;
            var col = box.parentElement.getBoundingClientRect();
            var top = Math.max(gap, col.top);
            var bottom = Math.min(window.innerHeight - gap, col.bottom);
            box.style.height = Math.max(260, Math.round((bottom - top) / zoom)) + 'px';
        };
        var request = function () {
            if (!queued) { queued = true; requestAnimationFrame(fit); }
        };
        window.addEventListener('scroll', request, { passive: true });
        window.addEventListener('resize', request);
        fit();
        setTimeout(fit, 800);
    }

    // Checkout: a collapsed "Покупатель" step lists the filled-in contact data instead of a bare "Свойства заказа"
    function soaPropsSummary() {
        var section = document.getElementById('bx-soa-properties');
        var C = window.BX && BX.Sale && BX.Sale.OrderAjaxComponent;
        if (!section || !C || !C.result || !C.result.ORDER_PROP) return;
        var content = section.querySelector('.bx-soa-section-content');
        if (!content || section.classList.contains('bx-selected') || content.querySelector('.bx-soa-customer, .mk-soa-summary')) return;
        var rows = [];
        (C.result.ORDER_PROP.properties || []).forEach(function (p) {
            if (p.TYPE === 'LOCATION' || p.TYPE === 'FILE' || p.TYPE === 'Y/N') return;
            var value = [].concat(p.VALUE || []).filter(function (v) { return v !== '' && v != null; }).join(', ');
            if (value) rows.push([p.NAME, value]);
        });
        if (!rows.length) return;
        var list = document.createElement('dl');
        list.className = 'mk-soa-summary';
        rows.forEach(function (row) {
            var item = document.createElement('div');
            var dt = document.createElement('dt');
            var dd = document.createElement('dd');
            dt.textContent = row[0];
            dd.textContent = row[1];
            item.appendChild(dt);
            item.appendChild(dd);
            list.appendChild(item);
        });
        Array.prototype.forEach.call(content.children, function (child) {
            if (child.tagName === 'STRONG') child.hidden = true;
        });
        content.appendChild(list);
    }

    // Checkout: the collapsed "Самовывоз" step becomes a card (photo + labelled rows) instead of bold-text lines
    function soaPickupCard() {
        var section = document.getElementById('bx-soa-pickup');
        if (!section || section.classList.contains('bx-selected')) return;
        var content = section.querySelector('.bx-soa-section-content');
        var img = content && content.querySelector(':scope > img.bx-soa-pickup-preview-img');
        if (!img || content.querySelector('.mk-pickup')) return;
        var name = '';
        var rows = [];
        var label = null;
        var value = '';
        var flush = function () {
            var clean = value.replace(/\s+/g, ' ').trim().replace(/^[-–:]\s*/, '');
            if (label !== null && clean) rows.push([label, clean]);
            label = null;
            value = '';
        };
        Array.prototype.slice.call(content.childNodes).forEach(function (node) {
            if (node === img || (node.classList && node.classList.contains('alert'))) return;
            if (node.nodeName === 'STRONG') {
                var text = node.textContent.trim();
                if (!name && !rows.length && label === null) { name = text; }
                else { flush(); label = text.replace(/[:\s-]+$/, ''); }
                node.parentNode.removeChild(node);
            } else if (node.nodeName === 'BR') {
                flush();
                node.parentNode.removeChild(node);
            } else if (node.nodeType === 3 || node.nodeType === 1) {
                if (label !== null) value += node.textContent;
                if (node.parentNode === content && node.nodeName !== 'DIV') node.parentNode.removeChild(node);
            }
        });
        flush();
        var card = document.createElement('div');
        card.className = 'mk-pickup';
        img.className = 'mk-pickup__img';
        img.alt = name;
        card.appendChild(img);
        var body = document.createElement('div');
        body.className = 'mk-pickup__body';
        var title = document.createElement('div');
        title.className = 'mk-pickup__name';
        title.textContent = name;
        body.appendChild(title);
        var list = document.createElement('dl');
        list.className = 'mk-pickup__facts';
        rows.forEach(function (row) {
            var item = document.createElement('div');
            var dt = document.createElement('dt');
            var dd = document.createElement('dd');
            dt.textContent = row[0];
            dd.textContent = row[1];
            item.appendChild(dt);
            item.appendChild(dd);
            list.appendChild(item);
        });
        body.appendChild(list);
        card.appendChild(body);
        content.appendChild(card);
    }

    // Checkout: each pickup point in the open "Самовывоз" step becomes a card — photo, name, short address,
    // phone and hours, then "Выбрать" or "Выбрано". The component's own nodes and handlers are reused.
    function soaPickupList() {
        var items = document.querySelectorAll('#bx-soa-pickup .bx-soa-pickup-list-item:not(.mk-store)');
        if (!items.length) return;
        var subtitle = document.querySelector('#bx-soa-pickup .bx-soa-pickup-subTitle');
        if (subtitle) subtitle.textContent = subtitle.textContent.replace(/[:\s]+$/, '');
        var node = function (tag, className, text) {
            var el = document.createElement(tag);
            el.className = className;
            if (text) el.textContent = text;
            return el;
        };
        Array.prototype.forEach.call(items, function (item) {
            var detail = item.querySelector('.bx-soa-pickup-l-item-detail');
            var desc = item.querySelector('.bx-soa-pickup-l-item-desc');
            if (!detail || !desc) return;
            var facts = {};
            desc.innerHTML.split(/<br\s*\/?>/i).forEach(function (line) {
                var holder = document.createElement('div');
                holder.innerHTML = line;
                var text = holder.textContent.replace(/\s+/g, ' ').trim();
                var colon = text.indexOf(':');
                if (colon < 1) return;
                var key = text.slice(0, colon).toLowerCase();
                var value = text.slice(colon + 1).trim();
                if (/^адрес/.test(key)) facts.address = value;
                else if (/^телефон/.test(key)) facts.phone = value;
                else if (/^(режим|график)/.test(key)) facts.hours = value;
            });
            var nameNode = item.querySelector('.bx-soa-pickup-l-item-name');
            var addressNode = item.querySelector('.bx-soa-pickup-l-item-adress');
            var address = (facts.address || (addressNode ? addressNode.textContent : ''))
                .replace(/^\s*\d{6},\s*/, '')
                .replace(/^[^,]*\sобл(\.|асть)?,\s*/i, '')
                .trim();
            var hours = (facts.hours || '')
                .replace(/с\s*(\d{1,2}[:.]\d{2})\s*до\s*(\d{1,2}[:.]\d{2})/g, '$1–$2')
                .replace(/([А-ЯЁ][а-яё])\.?\s*-\s*([А-ЯЁ][а-яё])\.?/g, '$1–$2');

            var body = node('div', 'mk-store__body');
            body.appendChild(node('div', 'mk-store__name', nameNode ? nameNode.textContent.trim() : ''));
            if (address) body.appendChild(node('div', 'mk-store__addr', address));
            if (facts.phone || hours) {
                var meta = node('div', 'mk-store__meta');
                if (facts.phone) meta.appendChild(node('span', 'mk-store__fact mk-store__fact--phone', facts.phone));
                if (hours) {
                    // "Пн–Пт 8:30–17:30, Сб–Вс выходной" wraps only between its parts
                    var time = node('span', 'mk-store__fact mk-store__fact--time');
                    var text = node('span', 'mk-store__fact-text');
                    hours.split(/,\s*/).forEach(function (part, i, parts) {
                        text.appendChild(node('span', 'mk-store__nowrap', part + (i < parts.length - 1 ? ',' : '')));
                        if (i < parts.length - 1) text.appendChild(document.createTextNode(' '));
                    });
                    time.appendChild(text);
                    meta.appendChild(time);
                }
                body.appendChild(meta);
            }
            var img = item.querySelector('.bx-soa-pickup-l-item-img');
            var button = item.querySelector('.bx-soa-pickup-l-item-btn');
            var chosen = node('span', 'mk-store__chosen', 'Выбрано');

            if (img) {
                img.classList.add('mk-store__img');
                img.alt = nameNode ? nameNode.textContent.trim() : '';
                item.appendChild(img);
            } else {
                item.classList.add('mk-store--no-img');
            }
            item.appendChild(body);
            if (button) item.appendChild(button);
            item.appendChild(chosen);
            if (addressNode) addressNode.parentNode.removeChild(addressNode);
            detail.parentNode.removeChild(detail);
            item.classList.add('mk-store');
        });
    }

    // Checkout: the pickup point follows the region chosen in the form (Ярославская / Ивановская / Владимирская);
    // that office is listed first and selected, a point picked by hand is kept until the location changes
    function soaPickupByCity() {
        var C = window.BX && BX.Sale && BX.Sale.OrderAjaxComponent;
        if (!C || C.__mkPickupByCity || typeof C.refreshOrder !== 'function' || typeof C.getPickUpInfoArray !== 'function') return;
        C.__mkPickupByCity = true;
        var byCode = {};
        var lastLocation = null;
        var locationCode = function () {
            var props = (C.result && C.result.ORDER_PROP && C.result.ORDER_PROP.properties) || [];
            for (var i = 0; i < props.length; i++) {
                if (props[i].TYPE === 'LOCATION') return String([].concat(props[i].VALUE || [])[0] || '');
            }
            return '';
        };
        var preferredStore = function (stores) {
            var store = byCode[locationCode()];
            return store && stores.indexOf(store) !== -1 ? store : null;
        };
        var deliveryStores = function () {
            var d = typeof C.getSelectedDelivery === 'function' ? C.getSelectedDelivery() : null;
            return d && d.STORE ? d.STORE.map(String) : [];
        };
        var originalInfo = C.getPickUpInfoArray;
        C.getPickUpInfoArray = function (ids) {
            var list = originalInfo.apply(this, arguments);
            var preferred = preferredStore((ids || []).map(String));
            if (preferred && list && list.length > 1) {
                list.sort(function (a, b) { return (String(b.ID) === preferred) - (String(a.ID) === preferred); });
            }
            return list;
        };
        var choose = function () {
            var input = document.getElementById('BUYER_STORE');
            var preferred = preferredStore(deliveryStores());
            if (!input || !preferred) return;
            var first = document.querySelector('#bx-soa-pickup .bx-soa-pickup-list-item');
            if (String(input.value) !== preferred) {
                input.value = preferred;
                C.sendRequest();
            } else if (first && first.id !== 'store-' + preferred) {
                C.sendRequest(); // the list was drawn before the office was known: redraw it in the right order
            }
        };
        var apply = function () {
            if (!deliveryStores().length || !document.getElementById('BUYER_STORE')) return;
            var code = locationCode();
            if (!code || code === lastLocation) return;
            lastLocation = code;
            if (byCode.hasOwnProperty(code)) { choose(); return; }
            fetch('/bitrix/templates/medcompany_v2/ajax/pickup-store.php?code=' + encodeURIComponent(code), { credentials: 'same-origin' })
                .then(function (r) { return r.ok ? r.json() : {}; })
                .then(function (data) {
                    byCode[code] = data && data.store ? String(data.store) : '';
                    if (code === locationCode()) choose();
                })
                .catch(function () {});
        };
        var originalRefresh = C.refreshOrder;
        C.refreshOrder = function () {
            var result = originalRefresh.apply(this, arguments);
            setTimeout(apply, 0);
            return result;
        };
        setTimeout(apply, 600);
    }

    // Checkout: no height tweening or forced scroll jumps
    function calmCheckout() {
        var C = window.BX && BX.Sale && BX.Sale.OrderAjaxComponent;
        if (!C || C.__mkCalm || !BX.easing) return;
        C.__mkCalm = true;
        var Real = BX.easing;
        var Instant = function (opts) { this.opts = opts; };
        Instant.prototype.animate = function () {
            var o = this.opts;
            var realScroll = window.scrollTo;
            window.scrollTo = function (x, y) { realScroll.call(window, { top: typeof y === 'number' ? y : 0, behavior: 'smooth' }); };
            try { if (o.step) o.step(o.finish); } finally { window.scrollTo = realScroll; }
            if (o.complete) o.complete();
        };
        Instant.prototype.stop = function () {};
        for (var k in Real) { if (Real.hasOwnProperty(k)) Instant[k] = Real[k]; }
        Object.keys(C).forEach(function (name) {
            var fn = C[name];
            if (typeof fn !== 'function' || name === 'init') return;
            C[name] = function () {
                var prev = BX.easing;
                BX.easing = Instant;
                try { return fn.apply(this, arguments); } finally { BX.easing = prev; }
            };
        });
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
        initKeyboardMode();
        initSidebarSearch();
        initProductTabs();
        initSearchSort();
        initSearchFilter();
        initCrumbs();
        initHomeSidebar();
        emptyCart();
        // catalog objects are created by inline scripts on BX.ready, so wait a tick
        if (window.BX && BX.ready) { BX.ready(function () { setTimeout(initCartButtons, 0); }); } else { setTimeout(initCartButtons, 300); }
        calmCheckout();
        soaPickupByCity();
        initBasketSelection();
        fitBanners();
        setTimeout(fitBanners, 800);
        if (window.BX && BX.addCustomEvent) {
            BX.addCustomEvent('OnBasketChange', function () { setTimeout(initBasketSelection, 300); });
        }
        document.addEventListener('click', function (e) {
            if (e.target.closest('.bazarow_add_favor')) { setTimeout(syncFavourites, 700); setTimeout(function () { syncFavourites(); labelCart(); }, 1600); }
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
                requestAnimationFrame(function () { pending = false; groupCardActions(); labelControls(); labelCart(); initBasketSelection(); fitBanners(); soaPropsSummary(); soaPickupCard(); soaPickupList(); emptyCart(); markCatalogButtons(); });
            }).observe(document.querySelector('.mk-main') || document.body, { childList: true, subtree: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
