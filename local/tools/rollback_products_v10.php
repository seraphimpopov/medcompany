<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

const IBLOCK_ID = 16;

function fail(string $message, int $code = 1): never
{
    fwrite(STDERR, 'ОШИБКА: ' . $message . PHP_EOL);
    exit($code);
}

function findDocumentRoot(): string
{
    $current = __DIR__;
    for ($i = 0; $i < 8; $i++) {
        if (is_file($current . '/bitrix/modules/main/include/prolog_before.php')) {
            return $current;
        }
        $parent = dirname($current);
        if ($parent === $current) {
            break;
        }
        $current = $parent;
    }
    fail('Не найден prolog_before.php');
}

$file = '';
$apply = false;
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--apply') {
        $apply = true;
    } elseif (str_starts_with($arg, '--file=')) {
        $file = substr($arg, 7);
    } elseif ($arg === '--help' || $arg === '-h') {
        echo "Проверка: php rollback_products_v10.php --file=logs/backup_YYYYMMDD_HHMMSS.csv\n";
        echo "Откат:   php rollback_products_v10.php --apply --file=logs/backup_YYYYMMDD_HHMMSS.csv\n";
        exit(0);
    } else {
        fail('Неизвестный аргумент: ' . $arg);
    }
}
if ($file === '' || !is_file($file)) {
    fail('Укажи существующий backup: --file=logs/backup_YYYYMMDD_HHMMSS.csv');
}

$root = findDocumentRoot();
$_SERVER['DOCUMENT_ROOT'] = $root;
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('STOP_STATISTICS', true);
require_once $root . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    fail('Не подключился модуль iblock');
}

$h = fopen($file, 'rb');
if ($h === false) {
    fail('Не удалось открыть backup');
}
$header = fgetcsv($h, 0, ';', '"', '\\');
$header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$header[0]);
$expected = ['ID', 'NAME', 'ATT_TOVAR', 'ATT_VIEW', 'ATT_TYPE', 'ATT_TEXT', 'ATT_TEXT_TYPE'];
if ($header !== $expected) {
    fail('Неверный формат backup');
}

$count = 0;
$errors = 0;
while (($row = fgetcsv($h, 0, ';', '"', '\\')) !== false) {
    if (count($row) !== count($expected)) {
        $errors++;
        continue;
    }
    $data = array_combine($expected, $row);
    $id = (int)$data['ID'];
    if ($id <= 0) {
        $errors++;
        continue;
    }
    $count++;
    echo ($apply ? '[RESTORE] ' : '[CHECK] ') . 'ID ' . $id . ': ' . $data['NAME'] . PHP_EOL;
    if (!$apply) {
        continue;
    }

    $textType = strtoupper((string)$data['ATT_TEXT_TYPE']);
    if (!in_array($textType, ['TEXT', 'HTML'], true)) {
        $textType = 'TEXT';
    }

    try {
        CIBlockElement::SetPropertyValuesEx($id, IBLOCK_ID, [
            'ATT_TOVAR' => $data['ATT_TOVAR'],
            'ATT_VIEW' => $data['ATT_VIEW'],
            'ATT_TYPE' => $data['ATT_TYPE'],
            'ATT_TEXT' => [
                'VALUE' => [
                    'TEXT' => $data['ATT_TEXT'],
                    'TYPE' => $textType,
                ],
            ],
        ]);
    } catch (Throwable $e) {
        $errors++;
        fwrite(STDERR, '[ERROR] ID ' . $id . ': ' . $e->getMessage() . PHP_EOL);
    }
}
fclose($h);

echo "Строк: $count; ошибок: $errors; режим: " . ($apply ? 'APPLY' : 'DRY-RUN') . PHP_EOL;
exit($errors > 0 ? 2 : 0);
