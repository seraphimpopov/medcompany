<?php
// 1. Определяем корень сайта вручную
define('DOCUMENT_ROOT', '/home/c/ct94339/medcompany.rf/public_html');

// 2. Имитируем веб-среду для ядра Битрикс
$_SERVER['DOCUMENT_ROOT'] = DOCUMENT_ROOT;
$_SERVER['HTTP_HOST'] = 'medcompany.rf'; // Ваш домен
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

// 3. Отключаем ненужные проверки для CLI
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);

// 4. Подключаем пролог
require_once DOCUMENT_ROOT . '/bitrix/modules/main/include/prolog_before.php';

if (CModule::IncludeModule('iblock')) {
    $sectionId = 1253; // ID раздела
    $iblockId = 16;    // ID инфоблока

    // Обновляем активность раздела
    $section = new CIBlockSection();
    $updateResult = $section->Update($sectionId, ['ACTIVE' => 'Y']);
    
    if (!$updateResult) {
        echo 'Ошибка при обновлении раздела: ' . $section->LAST_ERROR;
    } else {
        // Получаем элементы раздела (без подразделов)
        $elements = new CIBlockElement();
        $res = $elements->GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => $sectionId,
                'INCLUDE_SUBSECTIONS' => 'N'
            ],
            false,
            false,
            ['ID']
        );

        $elementIds = [];
        while ($element = $res->Fetch()) {
            $elementIds[] = $element['ID'];
        }

        // Обновляем активность элементов
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($elementIds as $elementId) {
            if ($elements->Update($elementId, ['ACTIVE' => 'Y'])) {
                $successCount++;
            } else {
                $errorCount++;
                // Для детального лога ошибок раскомментируйте:
                // echo 'Ошибка элемента ' . $elementId . ': ' . $elements->LAST_ERROR . '<br>';
            }
        }

        echo "Раздел обновлен. Элементов: успешно - $successCount, с ошибкой - $errorCount";
    }
} else {
    echo 'Модуль iblock не подключен!';
}