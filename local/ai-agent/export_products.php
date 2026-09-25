<?php

$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../..");

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_CRONTAB", true);
define("BX_SECURITY_SESSION_READONLY", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

use Bitrix\Main\Loader;

Loader::includeModule("iblock");

$IBLOCK_ID = 16;

$exportDir = $_SERVER["DOCUMENT_ROOT"] . "/upload/ai_export";

if (!is_dir($exportDir)) {
    mkdir($exportDir, 0775, true);
}

$filePath = $exportDir . "/products_export_" . date("Y-m-d_H-i-s") . ".csv";

$fp = fopen($filePath, "w");

if (!$fp) {
    die("Cannot create export file\n");
}

// BOM для нормального открытия в Excel
fwrite($fp, "\xEF\xBB\xBF");

$headers = [
    "ID",
    "ACTIVE",
    "NAME",
    "CODE",
    "ARTICLE",
    "SECTION_ID",
    "SECTION_NAME",
    "PREVIEW_TEXT",
    "DETAIL_TEXT",
    "ATT_TOVAR",
    "ATT_VIEW",
    "ATT_TYPE",
    "ATT_TEXT",
    "PREVIEW_PICTURE",
    "DETAIL_PICTURE",
];

fputcsv($fp, $headers, ";");

$res = CIBlockElement::GetList(
    ["ID" => "ASC"],
    [
        "IBLOCK_ID" => $IBLOCK_ID,
    ],
    false,
    false,
    [
        "ID",
        "IBLOCK_ID",
        "ACTIVE",
        "NAME",
        "CODE",
        "IBLOCK_SECTION_ID",
        "PREVIEW_TEXT",
        "DETAIL_TEXT",
        "PREVIEW_PICTURE",
        "DETAIL_PICTURE",

        "PROPERTY_CML2_ARTICLE",
        "PROPERTY_ATT_TOVAR",
        "PROPERTY_ATT_VIEW",
        "PROPERTY_ATT_TYPE",
        "PROPERTY_ATT_TEXT",
    ]
);

$count = 0;

while ($item = $res->Fetch()) {
    $sectionName = "";

    if (!empty($item["IBLOCK_SECTION_ID"])) {
        $section = CIBlockSection::GetByID($item["IBLOCK_SECTION_ID"])->Fetch();
        if ($section) {
            $sectionName = $section["NAME"];
        }
    }

    $previewPicture = "";
    $detailPicture = "";

    if (!empty($item["PREVIEW_PICTURE"])) {
        $previewPicture = CFile::GetPath($item["PREVIEW_PICTURE"]);
    }

    if (!empty($item["DETAIL_PICTURE"])) {
        $detailPicture = CFile::GetPath($item["DETAIL_PICTURE"]);
    }

    $row = [
        $item["ID"],
        $item["ACTIVE"],
        $item["NAME"],
        $item["CODE"],
        $item["PROPERTY_CML2_ARTICLE_VALUE"],
        $item["IBLOCK_SECTION_ID"],
        $sectionName,
        clearText($item["PREVIEW_TEXT"]),
        clearText($item["DETAIL_TEXT"]),
        normalizeProperty($item["PROPERTY_ATT_TOVAR_VALUE"]),
        normalizeProperty($item["PROPERTY_ATT_VIEW_VALUE"]),
        normalizeProperty($item["PROPERTY_ATT_TYPE_VALUE"]),
        normalizeProperty($item["PROPERTY_ATT_TEXT_VALUE"]),
        $previewPicture,
        $detailPicture,
    ];

    fputcsv($fp, $row, ";");

    $count++;

    if ($count % 1000 === 0) {
        echo "Exported: {$count}\n";
    }
}

fclose($fp);

echo "Done\n";
echo "Exported: {$count}\n";
echo "File: " . str_replace($_SERVER["DOCUMENT_ROOT"], "", $filePath) . "\n";

function normalizeProperty($value): string
{
    if (is_array($value)) {
        if (isset($value["TEXT"])) {
            return clearText($value["TEXT"]);
        }

        return clearText(implode(", ", $value));
    }

    return clearText((string)$value);
}

function clearText(string $text): string
{
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, "UTF-8");
    $text = strip_tags($text);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim($text);
}
