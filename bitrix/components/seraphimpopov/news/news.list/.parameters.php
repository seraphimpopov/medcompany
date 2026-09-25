<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "CACHE_TIME" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => "Время кэширования (сек.)",
            "TYPE" => "STRING",
            "DEFAULT" => "3600",
        ),
        "PAGE_TITLE" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => "Заголовок страницы",
            "TYPE" => "STRING",
            "DEFAULT" => "Новости",
        ),
    ),
);
?>
