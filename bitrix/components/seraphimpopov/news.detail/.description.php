<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => "Детальная страница новости",
    "DESCRIPTION" => "Выводит детальную информацию о новости",
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "custom_components",
        "CHILD" => array(
            "ID" => "news_components",
            "NAME" => "Новости",
        ),
    ),
);
?>
