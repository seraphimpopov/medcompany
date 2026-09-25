# Bitrix AI Agent via external gateway

Папка должна лежать в корне сайта Битрикс:

```text
/local/ai-agent/
```

Настроено под:

```text
IBLOCK_ID = 16
ATT_TOVAR - связка товаров
ATT_VIEW  - вид товара
ATT_TYPE  - тип товара
ATT_TEXT  - описание
```

## Установка

Загрузите файлы в:

```text
~/medcompany.rf/public_html/local/ai-agent/
```

Отредактируйте `config.php`:

```php
"AI_GATEWAY_URL" => "http://IP_ВАШЕГО_AI_СЕРВЕРА:8088/classify",
"AI_GATEWAY_TOKEN" => "тот_же_секрет_что_в_gateway",
```

## Первый запуск

```bash
cd ~/medcompany.rf/public_html
php local/ai-agent/run_worker.php
```

Логи:

```bash
tail -n 100 local/ai-agent/logs/agent.log
```

## Cron

Когда тест пройдет нормально:

```bash
*/5 * * * * flock -n /tmp/bitrix_ai_agent.lock /usr/bin/php -d memory_limit=512M /home/ct94339/medcompany.rf/public_html/local/ai-agent/run_worker.php >> /home/ct94339/medcompany.rf/public_html/local/ai-agent/logs/cron.log 2>&1
```

Если путь отличается, проверьте его командой `pwd` в корне сайта.
