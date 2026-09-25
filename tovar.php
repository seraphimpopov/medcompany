<?

// 1. Определяем корень сайта вручную
define('DOCUMENT_ROOT', '/home/c/ct94339/medcompany.rf/public_html');

// 2. Имитируем веб-среду для ядра Битрикс
$_SERVER['DOCUMENT_ROOT'] = DOCUMENT_ROOT;
$_SERVER['HTTP_HOST']      = 'medcompany.rf'; // Ваш домен
$_SERVER['REQUEST_URI']    = '/';
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

// 3. Отключаем ненужные проверки для CLI
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);

// 4. Подключаем пролог
require_once DOCUMENT_ROOT . '/bitrix/modules/main/include/prolog_before.php';

if (CModule::IncludeModule('iblock')) {

    $iblockId   = 16;
    $elementIds = [38752, 34253]; // оба товара

    $element = new CIBlockElement();

    foreach ($elementIds as $elementId) {
        $arLoadProductArray = [
            "IBLOCK_ID" => $iblockId,
            "ACTIVE"    => "N",
        ];

        $updateResult = $element->Update($elementId, $arLoadProductArray);

        if ($updateResult) {
            echo "Элемент {$elementId} отключен<br>";
        } else {
            echo "Ошибка при обновлении {$elementId}: " . $element->LAST_ERROR . "<br>";
        }
    }
}
