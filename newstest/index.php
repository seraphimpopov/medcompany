<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("newstest");
?>

<?$APPLICATION->IncludeComponent(
    "seraphimpopov:news.list", // Имя вашего комплексного компонента
    "",
    Array(
        "CACHE_TIME" => "3600",        // Время кэширования
        "PAGE_TITLE" => "Список новостей",  // Заголовок страницы
    )
);?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
