(function () {
    'use strict';
    function init() {
        document.querySelectorAll('.med-search-widget').forEach(function (widget) {
            if (widget.dataset.ready) return;
            widget.dataset.ready = '1';
            var input = widget.querySelector('.med-search-input');
            var panel = widget.querySelector('.med-suggest');
            var list = panel.querySelector('[role=listbox]');
            var all = panel.querySelector('.med-suggest-all');
            var status = widget.querySelector('[role=status]');
            var timer, controller, sequence = 0, selected = -1, links = [];
            function close() {
                panel.hidden = true;
                input.setAttribute('aria-expanded', 'false');
                input.removeAttribute('aria-activedescendant');
                selected = -1;
            }
            function highlight(index) {
                selected = index;
                links.forEach(function (link, i) { link.setAttribute('aria-selected', i === index ? 'true' : 'false'); });
                if (links[index]) {
                    input.setAttribute('aria-activedescendant', links[index].id);
                    links[index].scrollIntoView({block: 'nearest'});
                } else input.removeAttribute('aria-activedescendant');
            }
            function searchInput() {
                clearTimeout(timer);
                if (controller) controller.abort();
                var request = ++sequence;
                close();
                status.textContent = '';
                var query = input.value.trim();
                if (query.length < 3 && !/^(gc|3m|pd)$/i.test(query)) return;
                timer = setTimeout(function () {
                    controller = new AbortController();
                    status.textContent = 'Ищем товары…';
                    fetch('/local/tools/medcompany-search-suggest.php?q=' + encodeURIComponent(query), {signal: controller.signal, credentials: 'same-origin'})
                        .then(function (response) { if (!response.ok) throw new Error('search'); return response.json(); })
                        .then(function (data) {
                            if (request !== sequence || document.activeElement !== input) return;
                            list.replaceChildren();
                            links = [];
                            data.items.forEach(function (item, index) {
                                var link = document.createElement('a');
                                link.className = 'med-suggest-item';
                                link.id = list.id + '-' + index;
                                link.href = item.url;
                                link.setAttribute('role', 'option');
                                link.setAttribute('aria-selected', 'false');
                                var picture = document.createElement('span');
                                picture.className = 'med-suggest-photo';
                                if (item.image) {
                                    var image = document.createElement('img');
                                    image.src = item.image; image.alt = ''; image.width = 54; image.height = 54;
                                    picture.appendChild(image);
                                } else picture.textContent = 'Нет фото';
                                var body = document.createElement('span');
                                body.className = 'med-suggest-body';
                                var name = document.createElement('span');
                                name.className = 'med-suggest-name'; name.textContent = item.name;
                                var detail = document.createElement('span');
                                detail.className = 'med-suggest-detail';
                                detail.textContent = (item.article ? 'Арт. ' + item.article + ' · ' : '') + (item.stock ? 'В наличии' : 'Уточните наличие');
                                var price = document.createElement('span');
                                price.className = 'med-suggest-price' + (item.price === null ? ' med-suggest-price-request' : '');
                                price.textContent = item.price_text || 'Цена по запросу';
                                body.append(name, detail, price); link.append(picture, body); list.appendChild(link); links.push(link);
                            });
                            if (!data.items.length) {
                                var empty = document.createElement('p');
                                empty.className = 'med-suggest-empty'; empty.textContent = 'Ничего не найдено. Попробуйте другое название или артикул.'; list.appendChild(empty);
                            }
                            all.href = data.url; all.textContent = data.total ? 'Все результаты (' + data.total + ') →' : 'Перейти к поиску →';
                            panel.hidden = false; input.setAttribute('aria-expanded', 'true');
                            status.textContent = 'Найдено товаров: ' + data.total;
                        })
                        .catch(function (error) { if (error.name !== 'AbortError' && request === sequence) status.textContent = 'Нажмите Enter, чтобы открыть поиск.'; });
                }, 300);
            }
            input.addEventListener('input', searchInput);
            input.addEventListener('focus', function () { if (panel.hidden) searchInput(); });
            if (document.activeElement === input) searchInput();
            input.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') { ++sequence; if (controller) controller.abort(); clearTimeout(timer); close(); return; }
                if (panel.hidden) return;
                if (event.key === 'ArrowDown') { event.preventDefault(); highlight(Math.min(selected + 1, links.length - 1)); }
                if (event.key === 'ArrowUp') { event.preventDefault(); highlight(Math.max(selected - 1, 0)); }
                if (event.key === 'Enter' && selected >= 0 && links[selected]) { event.preventDefault(); window.location.assign(links[selected].href); }
            });
            document.addEventListener('click', function (event) { if (!widget.contains(event.target)) close(); });
            widget.addEventListener('focusout', function () { setTimeout(function () { if (!widget.contains(document.activeElement)) close(); }, 0); });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
