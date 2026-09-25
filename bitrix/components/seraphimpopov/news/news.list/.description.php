<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => "Список новостей",
    "DESCRIPTION" => "Выводит список новостей",
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "custom_components", // Своя группа компонентов
        "CHILD" => array(
            "ID" => "news_components",
            "NAME" => "Новости",
        ),
    ),
);
?>
