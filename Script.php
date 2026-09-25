<?php
// 1) Подключаем пролог Битрикс, чтобы загрузить ядро
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

// 2) Подключаем модуль инфоблоков
if (!CModule::IncludeModule('iblock')) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Не удалось подключить модуль iblock.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3) Задаём ID инфоблока
$iblockId = 16;

// 4) Формируем фильтр для выборки всех разделов указанного инфоблока
$arFilter = [
    'IBLOCK_ID' => $iblockId
];

// 5) Запрашиваем все разделы инфоблока: получаем поля ID, NAME, ACTIVE
$rsSections = CIBlockSection::GetList(
    ['ID' => 'ASC'],           // сортировка по возрастанию ID
    $arFilter,                 // фильтр
    false,                     // не считаем количество элементов
    ['ID', 'NAME', 'ACTIVE']   // получаем поля: ID, NAME, ACTIVE
);

// 6) Подготавливаем объект для обновления
$sectionObj = new CIBlockSection();

// 7) Перебираем каждый раздел и обновляем ACTIVE = 'Y'
$results = [];

while ($arSection = $rsSections->GetNext()) {
    $sectionId   = (int) $arSection['ID'];
    $sectionName = $arSection['NAME'];
    $currentFlag = $arSection['ACTIVE']; // 'Y' или 'N'

    if ($currentFlag === 'Y') {
        // Уже активен — ничего не делаем
        $results[] = [
            'ID'     => $sectionId,
            'NAME'   => $sectionName,
            'STATUS' => 'ALREADY_ACTIVE'
        ];
        continue;
    }

    // Ставим ACTIVE = 'Y' через экземпляр CIBlockSection
    $updateFields = ['ACTIVE' => 'Y'];
    $bSuccess = $sectionObj->Update($sectionId, $updateFields);

    if ($bSuccess) {
        $results[] = [
            'ID'     => $sectionId,
            'NAME'   => $sectionName,
            'STATUS' => 'SUCCESS'
        ];
    } else {
        global $APPLICATION;
        $error = $APPLICATION->GetException();
        $errorMsg = $error ? $error->GetString() : 'Неизвестная ошибка при обновлении';

        $results[] = [
            'ID'      => $sectionId,
            'NAME'    => $sectionName,
            'STATUS'  => 'ERROR',
            'MESSAGE' => $errorMsg
        ];
    }
}

// 8) Отдаём итоговый отчёт в формате JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_UNESCAPED_UNICODE);
exit;



