<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = [
    "NAME" => "Список новостей",
    "DESCRIPTION" => "Выводит список новостей с детальной информацией",
    "COMPLEX" => "Y",
    "PATH" => [
        "ID" => "custom_components",
        "CHILD" => [
            "ID" => "newstest",
            "NAME" => "Новости"
        ]
    ],
];
