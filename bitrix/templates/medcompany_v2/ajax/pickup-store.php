<?php
// Pickup point for a location code: the office of the buyer's region (catalog store IDs 1, 11, 12)
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('PUBLIC_AJAX_MODE', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=86400');

$regions = array(
    'Ярославская' => 1,  // МК Главный склад, Ярославль
    'Ивановская' => 11,  // МК Иваново
    'Владимирская' => 12, // МК Владимир
);
$code = preg_replace('/[^0-9A-Za-z_]/', '', (string)($_GET['code'] ?? ''));
$store = 0;
if ($code !== '' && \Bitrix\Main\Loader::includeModule('sale')) {
    try {
        $path = \Bitrix\Sale\Location\LocationTable::getPathToNodeByCode($code, array(
            'select' => array('LNAME' => 'NAME.NAME'),
            'filter' => array('=NAME.LANGUAGE_ID' => 'ru'),
        ));
        while ($row = $path->fetch()) {
            foreach ($regions as $needle => $id) {
                if (mb_stripos((string)$row['LNAME'], $needle) !== false) {
                    $store = $id;
                }
            }
        }
    } catch (\Exception $e) {
        $store = 0;
    }
}

echo json_encode(array('store' => $store));
