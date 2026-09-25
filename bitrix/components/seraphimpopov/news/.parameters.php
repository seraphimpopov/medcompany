<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "CACHE_TIME" => [
            "PARENT" => "BASE",
            "NAME" => "Время кэширования (сек)",
            "TYPE" => "STRING",
            "DEFAULT" => "3600",
        ],
        "PAGE_TITLE" => [
            "PARENT" => "BASE",
            "NAME" => "Заголовок страницы",
            "TYPE" => "STRING",
            "DEFAULT" => "Список новостей",
        ],
    ],
];
?>
