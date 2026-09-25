<?php
/**
 * Show active iblocks and likely article property codes.
 * Run before import to choose the correct IBLOCK_ID.
 */
$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../..');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    die("Cannot include iblock module\n");
}

$candidates = ['CML2_ARTICLE', 'ARTNUMBER', 'ARTICLE', 'VENDOR_CODE', 'VENDORCODE', 'SKU'];
$wanted = array_flip($candidates);

echo "Active iblocks and article-like properties\n";
echo str_repeat('=', 90) . "\n";

$res = CIBlock::GetList(['ID' => 'ASC'], ['ACTIVE' => 'Y'], false);
while ($iblock = $res->Fetch()) {
    $id = (int)$iblock['ID'];
    $props = [];
    $propRes = CIBlockProperty::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $id]);
    while ($prop = $propRes->Fetch()) {
        $code = strtoupper(trim((string)$prop['CODE']));
        if (isset($wanted[$code]) || stripos((string)$prop['NAME'], 'артик') !== false) {
            $props[] = (string)$prop['CODE'] . ' [' . (string)$prop['NAME'] . ']';
        }
    }

    echo "IBLOCK_ID={$id}\n";
    echo "NAME=" . (string)$iblock['NAME'] . "\n";
    echo "TYPE=" . (string)$iblock['IBLOCK_TYPE_ID'] . "\n";
    echo "ARTICLE_PROPERTIES=" . (!empty($props) ? implode(', ', $props) : 'not found') . "\n";
    echo str_repeat('-', 90) . "\n";
}
?>
