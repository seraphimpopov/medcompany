ИМПОРТ СВОЙСТВ ТОВАРОВ В БИТРИКС24 / 1С-БИТРИКС

Что обновляется в инфоблоке 16:
- ATT_TOVAR
- ATT_VIEW
- ATT_TYPE
- ATT_TEXT

Не изменяются названия, цены, картинки, активность, разделы и остатки.
В CSV 4917 товаров. Разделитель - точка с запятой, кодировка UTF-8 BOM.

Рекомендуемый путь на сервере:
/home/c/ct94339/medcompany.rf/public_html/local/tools/bitrix_ai_import_v10

1. Проверка синтаксиса:
php -l import_products_v10.php
php -l rollback_products_v10.php

2. Проверка 10 товаров без записи:
php import_products_v10.php --dry-run --ids=626,627,683,695,716,727,782,788,794,798

3. Запись только 10 тестовых товаров:
php import_products_v10.php --apply --ids=626,627,683,695,716,727,782,788,794,798

После этого открыть эти товары в админке и проверить:
- Связка товаров
- Вид
- Тип товара
- Описание
- отображение карточки на сайте

4. Полная проверка без записи:
php import_products_v10.php --dry-run

5. Полный импорт:
php import_products_v10.php --apply

Для запуска с сохранением работы после закрытия SSH:
nohup php import_products_v10.php --apply > logs/nohup_import.log 2>&1 &
tail -f logs/nohup_import.log

Резервные копии создаются автоматически в logs/backup_YYYYMMDD_HHMMSS.csv.

Проверка файла отката:
php rollback_products_v10.php --file=logs/backup_YYYYMMDD_HHMMSS.csv

Фактический откат:
php rollback_products_v10.php --apply --file=logs/backup_YYYYMMDD_HHMMSS.csv

Коды завершения:
0 - успешно
2 - есть ошибки в отдельных строках
3 - не выбрано ни одной строки
