<?php

$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../..");

if (!$_SERVER["DOCUMENT_ROOT"] || !file_exists($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php")) {
    die("Cannot find Bitrix document root. Put this folder into /local/ai-agent inside the site root.\n");
}

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_CRONTAB", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;

Loader::includeModule("iblock");

$config = require __DIR__ . "/config.php";

if (empty($config["AI_GATEWAY_URL"]) || strpos($config["AI_GATEWAY_URL"], "YOUR_GATEWAY") !== false) {
    die("AI_GATEWAY_URL is not configured in config.php\n");
}

if (empty($config["AI_GATEWAY_TOKEN"]) || strpos($config["AI_GATEWAY_TOKEN"], "CHANGE_ME") !== false) {
    die("AI_GATEWAY_TOKEN is not configured in config.php\n");
}

$products = getProducts($config);

if (!$products) {
    echo "No products to process\n";
    exit;
}

foreach ($products as $product) {
    try {
        echo "Processing product ID {$product["ID"]}: {$product["NAME"]}\n";

        $ai = askGatewayAboutProduct($product, $config);

        if (($ai["confidence"] ?? 0) < $config["MIN_CONFIDENCE"] || !empty($ai["need_review"])) {
            logMessage("need_review", $product["ID"], $ai);
            echo "Need review product ID {$product["ID"]}\n";
            continue;
        }

        applyResult($product, $ai, $config);
        logMessage("applied", $product["ID"], $ai);
        echo "Applied product ID {$product["ID"]}\n";

    } catch (Throwable $e) {
        logMessage("error", $product["ID"], ["error" => $e->getMessage()]);
        echo "Error product ID {$product["ID"]}: {$e->getMessage()}\n";
    }
}

function getProducts(array $config): array
{
    $props = $config["PROPS"];
    $items = [];

    $filter = [
        "IBLOCK_ID" => $config["IBLOCK_ID"],
        "ACTIVE" => "Y",
        [
            "LOGIC" => "OR",
            ["PROPERTY_" . $props["BIND"] => false],
            ["PROPERTY_" . $props["VIEW"] => false],
            ["PROPERTY_" . $props["TYPE"] => false],
            ["PROPERTY_" . $props["TEXT"] => false],
        ],
    ];

    $select = [
        "ID",
        "IBLOCK_ID",
        "NAME",
        "CODE",
        "PREVIEW_TEXT",
        "DETAIL_TEXT",
        "PROPERTY_" . $config["ARTICLE_PROP"],
        "PROPERTY_" . $props["BIND"],
        "PROPERTY_" . $props["VIEW"],
        "PROPERTY_" . $props["TYPE"],
        "PROPERTY_" . $props["TEXT"],
    ];

    $res = CIBlockElement::GetList(
        ["ID" => "ASC"],
        $filter,
        false,
        ["nTopCount" => (int)$config["LIMIT"]],
        $select
    );

    while ($row = $res->Fetch()) {
        $items[] = [
            "ID" => (int)$row["ID"],
            "IBLOCK_ID" => (int)$row["IBLOCK_ID"],
            "NAME" => (string)$row["NAME"],
            "CODE" => (string)$row["CODE"],
            "ARTICLE" => (string)($row["PROPERTY_" . $config["ARTICLE_PROP"] . "_VALUE"] ?? ""),
            "PREVIEW_TEXT" => (string)$row["PREVIEW_TEXT"],
            "DETAIL_TEXT" => (string)$row["DETAIL_TEXT"],
            "PROP_BIND" => $row["PROPERTY_" . $props["BIND"] . "_VALUE"] ?? "",
            "PROP_VIEW" => $row["PROPERTY_" . $props["VIEW"] . "_VALUE"] ?? "",
            "PROP_TYPE" => $row["PROPERTY_" . $props["TYPE"] . "_VALUE"] ?? "",
            "PROP_TEXT" => $row["PROPERTY_" . $props["TEXT"] . "_VALUE"] ?? "",
        ];
    }

    return $items;
}

function askGatewayAboutProduct(array $product, array $config): array
{
    $payload = [
        "product_id" => $product["ID"],
        "name" => $product["NAME"],
        "article" => $product["ARTICLE"],
        "code" => $product["CODE"],
        "preview_text" => $product["PREVIEW_TEXT"],
        "detail_text" => $product["DETAIL_TEXT"],
        "existing_type" => (string)$product["PROP_TYPE"],
        "existing_view" => (string)$product["PROP_VIEW"],
        "existing_bind" => (string)$product["PROP_BIND"],
        "existing_text" => is_array($product["PROP_TEXT"]) ? json_encode($product["PROP_TEXT"], JSON_UNESCAPED_UNICODE) : (string)$product["PROP_TEXT"],
    ];

    $ch = curl_init($config["AI_GATEWAY_URL"]);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-Gateway-Token: " . $config["AI_GATEWAY_TOKEN"],
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 180,
        CURLOPT_CONNECTTIMEOUT => 20,
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($response === false || $status < 200 || $status >= 300) {
        throw new RuntimeException("Gateway error HTTP {$status}: {$error} {$response}");
    }

    $json = json_decode($response, true);

    if (!is_array($json)) {
        throw new RuntimeException("Invalid gateway JSON: " . $response);
    }

    return $json;
}

function applyResult(array $product, array $ai, array $config): void
{
    $props = $config["PROPS"];
    $values = [];

    if ($config["OVERWRITE_FILLED_FIELDS"] || isEmptyValue($product["PROP_TYPE"])) {
        $values[$props["TYPE"]] = trim((string)$ai["product_type"]);
    }

    if ($config["OVERWRITE_FILLED_FIELDS"] || isEmptyValue($product["PROP_VIEW"])) {
        $values[$props["VIEW"]] = trim((string)$ai["product_view"]);
    }

    if ($config["OVERWRITE_FILLED_FIELDS"] || isEmptyValue($product["PROP_BIND"])) {
        $values[$props["BIND"]] = resolveBindValue((string)$ai["binding_key"], $product["ID"], $config);
    }

    if ($config["OVERWRITE_FILLED_FIELDS"] || isEmptyValue($product["PROP_TEXT"])) {
        $values[$props["TEXT"]] = prepareTextPropertyValue($props["TEXT"], trim((string)$ai["description"]), $config);
    }

    if (!$values) {
        return;
    }

    CIBlockElement::SetPropertyValuesEx(
        $product["ID"],
        $config["IBLOCK_ID"],
        $values
    );
}

function prepareTextPropertyValue(string $code, string $text, array $config)
{
    $property = CIBlockProperty::GetList(
        [],
        [
            "IBLOCK_ID" => $config["IBLOCK_ID"],
            "CODE" => $code,
        ]
    )->Fetch();

    if ($property && $property["PROPERTY_TYPE"] === "S" && $property["USER_TYPE"] === "HTML") {
        return [
            "VALUE" => [
                "TEXT" => $text,
                "TYPE" => "TEXT",
            ],
        ];
    }

    return $text;
}

function resolveBindValue(string $bindingKey, int $productId, array $config)
{
    if (($config["BIND_MODE"] ?? "group_first_product_id") === "ai_key") {
        return trim($bindingKey);
    }

    $file = __DIR__ . "/bindings.json";
    $bindings = [];

    if (file_exists($file)) {
        $bindings = json_decode(file_get_contents($file), true);
        if (!is_array($bindings)) {
            $bindings = [];
        }
    }

    $key = mb_strtoupper(trim($bindingKey));

    if ($key === "") {
        $key = "PRODUCT_GROUP_" . $productId;
    }

    if (!isset($bindings[$key])) {
        $bindings[$key] = $productId;
        file_put_contents($file, json_encode($bindings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    return $bindings[$key];
}

function isEmptyValue($value): bool
{
    if (is_array($value)) {
        if (isset($value["TEXT"])) {
            return trim((string)$value["TEXT"]) === "";
        }
        return count($value) === 0;
    }

    return trim((string)$value) === "";
}

function logMessage(string $status, int $productId, array $data): void
{
    $dir = __DIR__ . "/logs";
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $line = json_encode([
        "time" => date("Y-m-d H:i:s"),
        "status" => $status,
        "product_id" => $productId,
        "data" => $data,
    ], JSON_UNESCAPED_UNICODE);

    file_put_contents($dir . "/agent.log", $line . PHP_EOL, FILE_APPEND);
}
