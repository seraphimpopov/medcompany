# медкомпания.рф — код сайта

Интернет-магазин на 1С-Битрикс (PHP 8.2). Боевой сервер: `ct94339@92.53.96.171`, docroot `~/medcompany.rf/public_html`,
доступ по SSH-ключу `~/.ssh/medcompany_92_53_96_171_ed25519`.

Репозиторий — зеркало **собственного кода** сайта. Источник правды — сервер: правки делаются там,
сюда подтягиваются скриптом `ops/pull-from-server.sh` и коммитятся.

## Что лежит в репозитории

| Путь | Что это |
|---|---|
| `bitrix/templates/medcompany_v2/` | **Текущий шаблон сайта** (редизайн, с 25.09.2026): `css/redesign.css`, `js/redesign.js`, свои шаблоны компонентов |
| `bitrix/templates/medcompany/` | Старый шаблон — запасной вариант для отката |
| `bitrix/templates/.default`, `eshop_bootstrap_green`, `mail_user`, … | Прочие шаблоны Битрикса |
| `local/php_interface/` | `init.php`, `medcompany_price_request.php` (цена по запросу / запрет корзины), `medcompany_search.php` (поиск) |
| `local/assets/`, `local/tools/` | Поисковые подсказки, скрипты импорта товаров и описаний |
| `local/ai-agent/` | Воркер классификации товаров через AI-шлюз (`config.php` — только на сервере, шаблон: `config.example.php`) |
| `bitrix/php_interface/` | Старые обработчики Битрикса (без `dbconn.php`) |
| `bitrix/components/<vendor>/` | Нестандартные компоненты: `seraphimpopov`, `kalashayn`, `arturgolubev`, `bazarow`, … |
| Корень, `catalog/`, `about/`, `personal/`, `include/`, … | Публичные страницы, меню, `urlrewrite.php`, `.htaccess` |

## Что намеренно не хранится

- Ядро Битрикса (`bitrix/modules`, `bitrix/components/bitrix`, кеши и т. п. — ~63 ГБ) и `upload/` (~13 ГБ).
- Секреты: `bitrix/php_interface/dbconn.php`, `bitrix/.settings.php`, `local/ai-agent/config.php`.
- Дампы БД (`*.sql`), архивы, `sitemap*.xml`, логи и CSV/JSON-выгрузки в `local/tools`.
- `bitrix/templates/medcompany (1) (1)/` — старая копия шаблона.
- `auth/manager.php` — см. «Безопасность».

Полный список — в `.gitignore` и в `ops/pull-from-server.sh`.

## Как обновить репозиторий с сервера

```bash
bash ops/pull-from-server.sh
git add -A
git commit -m "Sync from server"
```

## Выкладка правок на сервер

Автодеплоя нет: изменённые файлы копируются на сервер (`scp`), перед этим — бэкап в `~/backups/<задача>_<дата>/`.
После изменений шаблона обязательно сбросить **композитный кеш**, иначе посетители увидят старый HTML:

```bash
ssh -i ~/.ssh/medcompany_92_53_96_171_ed25519 ct94339@92.53.96.171 \
  'rm -f ~/medcompany.rf/public_html/bitrix/html_pages/*/*.html'
```

Переключение/откат шаблона: на сервере из docroot `php ~/mk_tpl_set.php medcompany` (или `medcompany_v2`), затем сбросить кеш.
У поиска свой кеш — при изменении выдачи поднимать `MedcompanySearch::VERSION`.

## Безопасность

На сервере найдены три одинаковых скрипта, которые выполняют присланный POST-запросом PHP-код
(веб-шелл, датированы 2020–2021 годами): `auth/manager.php`, `bitrix/modules/main/tools/manager.php`,
`bitrix/themes/intec.constructor/css/manager.php`. Они в репозиторий не добавлены и подлежат удалению с сервера.
