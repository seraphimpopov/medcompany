<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

const IBLOCK_ID = 16;
const REQUIRED_HEADERS = ['ID', 'ATT_TOVAR', 'ATT_VIEW', 'ATT_TYPE', 'ATT_TEXT'];
const PROPERTY_CODES = ['ATT_TOVAR', 'ATT_VIEW', 'ATT_TYPE', 'ATT_TEXT'];

function out(string $message): void
{
    fwrite(STDOUT, $message . PHP_EOL);
}

function err(string $message): void
{
    fwrite(STDERR, $message . PHP_EOL);
}

function fail(string $message, int $code = 1): never
{
    err('ОШИБКА: ' . $message);
    exit($code);
}

function parseArgs(array $argv): array
{
    $args = [
        'file' => __DIR__ . '/products_enriched_v10_import.csv',
        'apply' => false,
        'limit' => 0,
        'offset' => 0,
        'ids' => [],
        'no_verify' => false,
        'help' => false,
    ];

    foreach (array_slice($argv, 1) as $arg) {
        if ($arg === '--apply') {
            $args['apply'] = true;
        } elseif ($arg === '--dry-run') {
            $args['apply'] = false;
        } elseif ($arg === '--no-verify') {
            $args['no_verify'] = true;
        } elseif ($arg === '--help' || $arg === '-h') {
            $args['help'] = true;
        } elseif (str_starts_with($arg, '--file=')) {
            $args['file'] = substr($arg, 7);
        } elseif (str_starts_with($arg, '--limit=')) {
            $args['limit'] = max(0, (int)substr($arg, 8));
        } elseif (str_starts_with($arg, '--offset=')) {
            $args['offset'] = max(0, (int)substr($arg, 9));
        } elseif (str_starts_with($arg, '--ids=')) {
            $ids = array_filter(array_map('trim', explode(',', substr($arg, 6))), static fn(string $v): bool => $v !== '');
            foreach ($ids as $id) {
                if (!ctype_digit($id) || (int)$id <= 0) {
                    fail('Некорректный ID в --ids: ' . $id);
                }
            }
            $args['ids'] = array_values(array_unique(array_map('intval', $ids)));
        } else {
            fail('Неизвестный аргумент: ' . $arg);
        }
    }

    return $args;
}

function printHelp(): void
{
    out('Импорт свойств товаров в инфоблок 16.');
    out('');
    out('Проверка без изменений:');
    out('  php import_products_v10.php --dry-run --limit=10');
    out('');
    out('Тестовая запись конкретных товаров:');
    out('  php import_products_v10.php --apply --ids=626,627,683,695,716,727,782,788,794,798');
    out('');
    out('Полный импорт:');
    out('  php import_products_v10.php --apply');
    out('');
    out('Параметры: --file=PATH --limit=N --offset=N --ids=1,2,3 --no-verify');
}

function findDocumentRoot(): string
{
    $candidates = [];
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $candidates[] = rtrim((string)$_SERVER['DOCUMENT_ROOT'], '/\\');
    }

    $current = __DIR__;
    for ($i = 0; $i < 8; $i++) {
        $candidates[] = $current;
        $parent = dirname($current);
        if ($parent === $current) {
            break;
        }
        $current = $parent;
    }

    foreach (array_unique($candidates) as $root) {
        if (is_file($root . '/bitrix/modules/main/include/prolog_before.php')) {
            return $root;
        }
    }

    fail('Не найден bitrix/modules/main/include/prolog_before.php. Положи папку проекта внутрь public_html/local/tools/.');
}

function bootstrapBitrix(): void
{
    $root = findDocumentRoot();
    $_SERVER['DOCUMENT_ROOT'] = $root;

    define('NO_KEEP_STATISTIC', true);
    define('NOT_CHECK_PERMISSIONS', true);
    define('BX_CRONTAB_SUPPORT', true);
    define('STOP_STATISTICS', true);

    require_once $root . '/bitrix/modules/main/include/prolog_before.php';

    if (!\Bitrix\Main\Loader::includeModule('iblock')) {
        fail('Не удалось подключить модуль iblock.');
    }
}

function ensureDirectory(string $path): void
{
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        fail('Не удалось создать папку: ' . $path);
    }
}

function openCsv(string $file): array
{
    if (!is_file($file)) {
        fail('CSV не найден: ' . $file);
    }

    $handle = fopen($file, 'rb');
    if ($handle === false) {
        fail('Не удалось открыть CSV: ' . $file);
    }

    $header = fgetcsv($handle, 0, ';', '"', '\\');
    if ($header === false) {
        fclose($handle);
        fail('CSV пустой.');
    }

    $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$header[0]);
    if ($header !== REQUIRED_HEADERS) {
        fclose($handle);
        fail('Неверные заголовки CSV. Ожидалось: ' . implode(';', REQUIRED_HEADERS) . '. Получено: ' . implode(';', $header));
    }

    return [$handle, array_flip($header)];
}

function normalizeText(string $value): string
{
    $value = str_replace(["\r\n", "\r"], "\n", $value);
    return trim($value);
}

function validatePropertyConfiguration(): array
{
    $result = [];
    $res = CIBlockProperty::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => IBLOCK_ID]);
    while ($property = $res->Fetch()) {
        $code = (string)$property['CODE'];
        if (in_array($code, PROPERTY_CODES, true)) {
            $result[$code] = $property;
        }
    }

    foreach (PROPERTY_CODES as $code) {
        if (!isset($result[$code])) {
            fail('В инфоблоке ' . IBLOCK_ID . ' не найдено свойство ' . $code);
        }
    }

    if ((string)$result['ATT_TOVAR']['PROPERTY_TYPE'] !== 'N') {
        fail('ATT_TOVAR должен иметь тип Число (N). Сейчас: ' . $result['ATT_TOVAR']['PROPERTY_TYPE']);
    }
    foreach (['ATT_VIEW', 'ATT_TYPE'] as $code) {
        if ((string)$result[$code]['PROPERTY_TYPE'] !== 'S') {
            fail($code . ' должен иметь тип Строка (S).');
        }
    }
    if ((string)$result['ATT_TEXT']['PROPERTY_TYPE'] !== 'S' || strtoupper((string)$result['ATT_TEXT']['USER_TYPE']) !== 'HTML') {
        fail('ATT_TEXT должен иметь тип HTML/текст (S + USER_TYPE=HTML).');
    }

    return $result;
}

function getElement(int $id): ?array
{
    $res = CIBlockElement::GetList([], ['IBLOCK_ID' => IBLOCK_ID, 'ID' => $id], false, false, ['ID', 'NAME']);
    $row = $res->Fetch();
    return $row ?: null;
}

function readCurrentProperties(int $id): array
{
    $values = [
        'ATT_TOVAR' => '',
        'ATT_VIEW' => '',
        'ATT_TYPE' => '',
        'ATT_TEXT' => '',
        'ATT_TEXT_TYPE' => 'TEXT',
    ];

    $res = CIBlockElement::GetProperty(IBLOCK_ID, $id, ['sort' => 'asc'], []);
    while ($property = $res->Fetch()) {
        $code = (string)$property['CODE'];
        if (!in_array($code, PROPERTY_CODES, true)) {
            continue;
        }

        $value = $property['VALUE'];
        if ($code === 'ATT_TEXT' && is_array($value)) {
            $values['ATT_TEXT'] = normalizeText((string)($value['TEXT'] ?? ''));
            $type = strtoupper((string)($value['TYPE'] ?? 'TEXT'));
            $values['ATT_TEXT_TYPE'] = in_array($type, ['TEXT', 'HTML'], true) ? $type : 'TEXT';
        } else {
            $values[$code] = normalizeText((string)$value);
        }
    }

    return $values;
}

function propertyPayload(array $row): array
{
    return [
        'ATT_TOVAR' => $row['ATT_TOVAR'],
        'ATT_VIEW' => $row['ATT_VIEW'],
        'ATT_TYPE' => $row['ATT_TYPE'],
        'ATT_TEXT' => [
            'VALUE' => [
                'TEXT' => $row['ATT_TEXT'],
                'TYPE' => 'TEXT',
            ],
        ],
    ];
}

function valuesMatch(array $expected, array $actual): bool
{
    foreach (['ATT_TOVAR', 'ATT_VIEW', 'ATT_TYPE', 'ATT_TEXT'] as $code) {
        if (normalizeText((string)$expected[$code]) !== normalizeText((string)$actual[$code])) {
            return false;
        }
    }
    return true;
}

function writeCsvRow($handle, array $row): void
{
    if (fputcsv($handle, $row, ';', '"', '\\') === false) {
        fail('Не удалось записать CSV-журнал.');
    }
}

$args = parseArgs($argv);
if ($args['help']) {
    printHelp();
    exit(0);
}

bootstrapBitrix();
$properties = validatePropertyConfiguration();
ensureDirectory(__DIR__ . '/logs');

[$handle, $index] = openCsv($args['file']);
$selectedIds = array_flip($args['ids']);
$mode = $args['apply'] ? 'APPLY' : 'DRY-RUN';
$stamp = date('Ymd_His');
$logPath = __DIR__ . '/logs/import_' . strtolower($mode) . '_' . $stamp . '.log';
$errorPath = __DIR__ . '/logs/errors_' . $stamp . '.csv';
$backupPath = __DIR__ . '/logs/backup_' . $stamp . '.csv';

$log = fopen($logPath, 'ab');
$errors = fopen($errorPath, 'wb');
if ($log === false || $errors === false) {
    fail('Не удалось открыть файлы журнала.');
}
fwrite($errors, "\xEF\xBB\xBF");
writeCsvRow($errors, ['ROW', 'ID', 'ERROR']);

$backup = null;
if ($args['apply']) {
    $backup = fopen($backupPath, 'wb');
    if ($backup === false) {
        fail('Не удалось открыть резервную копию: ' . $backupPath);
    }
    fwrite($backup, "\xEF\xBB\xBF");
    writeCsvRow($backup, ['ID', 'NAME', 'ATT_TOVAR', 'ATT_VIEW', 'ATT_TYPE', 'ATT_TEXT', 'ATT_TEXT_TYPE']);
}

out('Режим: ' . $mode);
out('Инфоблок: ' . IBLOCK_ID);
out('CSV: ' . realpath($args['file']));
out('ATT_TOVAR: Число; ATT_VIEW/ATT_TYPE: Строка; ATT_TEXT: HTML/текст');
if ($args['apply']) {
    out('Резервная копия: ' . $backupPath);
}
out('Журнал: ' . $logPath);
out('');

$stats = [
    'csv_rows' => 0,
    'selected' => 0,
    'valid' => 0,
    'updated' => 0,
    'verified' => 0,
    'skipped' => 0,
    'errors' => 0,
];
$seenIds = [];
$dataPosition = 0;

while (($csvRow = fgetcsv($handle, 0, ';', '"', '\\')) !== false) {
    $stats['csv_rows']++;
    $rowNumber = $stats['csv_rows'] + 1;

    if (count($csvRow) === 1 && trim((string)$csvRow[0]) === '') {
        continue;
    }
    if (count($csvRow) !== count(REQUIRED_HEADERS)) {
        $stats['errors']++;
        writeCsvRow($errors, [$rowNumber, '', 'Ожидалось 5 колонок, получено ' . count($csvRow)]);
        continue;
    }

    $row = [];
    foreach (REQUIRED_HEADERS as $header) {
        $row[$header] = normalizeText((string)$csvRow[$index[$header]]);
    }

    if (!ctype_digit($row['ID']) || (int)$row['ID'] <= 0) {
        $stats['errors']++;
        writeCsvRow($errors, [$rowNumber, $row['ID'], 'Некорректный ID']);
        continue;
    }
    $id = (int)$row['ID'];

    if ($args['ids'] !== [] && !isset($selectedIds[$id])) {
        continue;
    }
    if ($args['ids'] === []) {
        if ($dataPosition++ < $args['offset']) {
            continue;
        }
        if ($args['limit'] > 0 && $stats['selected'] >= $args['limit']) {
            break;
        }
    }

    $stats['selected']++;

    if (isset($seenIds[$id])) {
        $stats['errors']++;
        writeCsvRow($errors, [$rowNumber, $id, 'Повторяющийся ID в CSV']);
        continue;
    }
    $seenIds[$id] = true;

    if (!ctype_digit($row['ATT_TOVAR']) || (int)$row['ATT_TOVAR'] <= 0) {
        $stats['errors']++;
        writeCsvRow($errors, [$rowNumber, $id, 'ATT_TOVAR должен быть положительным целым числом']);
        continue;
    }

    foreach (['ATT_VIEW', 'ATT_TYPE', 'ATT_TEXT'] as $requiredCode) {
        if ($row[$requiredCode] === '') {
            $stats['errors']++;
            writeCsvRow($errors, [$rowNumber, $id, $requiredCode . ' пустой']);
            continue 2;
        }
    }

    $element = getElement($id);
    if ($element === null) {
        $stats['errors']++;
        writeCsvRow($errors, [$rowNumber, $id, 'Элемент не найден в инфоблоке 16']);
        continue;
    }

    $current = readCurrentProperties($id);
    $stats['valid']++;

    if (!$args['apply']) {
        $message = sprintf('[OK] ID %d: %s; ATT_TOVAR=%s', $id, $element['NAME'], $row['ATT_TOVAR']);
        out($message);
        fwrite($log, $message . PHP_EOL);
        continue;
    }

    writeCsvRow($backup, [
        $id,
        (string)$element['NAME'],
        $current['ATT_TOVAR'],
        $current['ATT_VIEW'],
        $current['ATT_TYPE'],
        $current['ATT_TEXT'],
        $current['ATT_TEXT_TYPE'],
    ]);

    try {
        CIBlockElement::SetPropertyValuesEx($id, IBLOCK_ID, propertyPayload($row));
        $stats['updated']++;

        if (!$args['no_verify']) {
            $after = readCurrentProperties($id);
            if (!valuesMatch($row, $after)) {
                throw new RuntimeException('Проверка после записи не совпала с CSV');
            }
            $stats['verified']++;
        }

        $message = sprintf('[UPDATED] ID %d: %s; ATT_TOVAR=%s', $id, $element['NAME'], $row['ATT_TOVAR']);
        out($message);
        fwrite($log, $message . PHP_EOL);
    } catch (Throwable $e) {
        $stats['errors']++;
        writeCsvRow($errors, [$rowNumber, $id, $e->getMessage()]);
        $message = sprintf('[ERROR] ID %d: %s', $id, $e->getMessage());
        err($message);
        fwrite($log, $message . PHP_EOL);
    }
}

fclose($handle);
fclose($log);
fclose($errors);
if (is_resource($backup)) {
    fclose($backup);
}

out('');
out('ИТОГ');
foreach ($stats as $key => $value) {
    out(str_pad($key, 12) . ': ' . $value);
}
out('Ошибки CSV: ' . $errorPath);
if ($args['apply']) {
    out('Резервная копия: ' . $backupPath);
}

if ($stats['selected'] === 0) {
    fail('Не выбрано ни одной строки.', 3);
}
if ($stats['errors'] > 0) {
    exit(2);
}
exit(0);
