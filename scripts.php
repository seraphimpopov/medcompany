<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?><?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Проверяем подключение модуля инфоблоков
if (!CModule::IncludeModule("iblock")) {
    die("Модуль инфоблоков не установлен");
}

// Укажите ID инфоблока с товарами
$IBLOCK_ID = 16; // Замените 2 на ваш реальный ID

// Формируем фильтр для выборки активных товаров инфоблока
$arFilter = array(
    "IBLOCK_ID" => $IBLOCK_ID,
    "ACTIVE"    => "Y"
);

// Выбираем необходимые поля: ID, название и SEO-настройки
$arSelect = array("ID", "NAME", "IPROPERTY_TEMPLATES");

// Получаем список элементов
$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

var_dump($rsElements->SelectedRowsCount());

while ($arElement = $rsElements->Fetch()) {
    // Извлекаем текущие значения SEO полей
    $seoTitle       = isset($arElement["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"]) ? $arElement["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"] : "";
    $seoKeywords    = isset($arElement["IPROPERTY_TEMPLATES"]["ELEMENT_META_KEYWORDS"]) ? $arElement["IPROPERTY_TEMPLATES"]["ELEMENT_META_KEYWORDS"] : "";
    $seoDescription = isset($arElement["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"]) ? $arElement["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"] : "";

	echo "<pre>"; print_r($arElement); echo "</pre>";

    // Готовим данные для обновления: перезаписываем SEO-настройки тем же текстом, чтобы система считала их заданными вручную
    $arUpdateFields = array(
        "IPROPERTY_TEMPLATES" => array(
            "ELEMENT_META_TITLE"       => $seoTitle,
            "ELEMENT_META_KEYWORDS"    => $seoKeywords,
            "ELEMENT_META_DESCRIPTION" => $seoDescription,
        )
    );
    
    // Обновляем элемент
	/*$el = new CIBlockElement;
    if (!$el->Update($arElement["ID"], $arUpdateFields)) {
        echo "Ошибка обновления товара ID " . $arElement["ID"] . ": " . $el->LAST_ERROR . "<br>";
    } else {
        echo "Товар ID " . $arElement["ID"] . " обновлен<br>";
}*/
}

// Завершаем выполнение скрипта, подключая эпилог
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>