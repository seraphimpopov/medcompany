<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Фотогалерея интернет-магазина стоматологического оборудования и материалов Медкомпания.рф");
$APPLICATION->SetPageProperty("title", "Раздел Фотогалерея");

$APPLICATION->SetTitle("Фотогалерея");

?><div class="container">
<?$APPLICATION->IncludeComponent("bitrix:photo", "photo", Array(
	"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"DETAIL_FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем фотографии
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки фотографий в разделе
		"FILE_404" => "/404.php",	// Страница для показа (по умолчанию /404.php)
		"IBLOCK_ID" => "15",	// Инфоблок
		"IBLOCK_TYPE" => "content",	// Тип инфоблока
		"LAZYLOAD_USE" => "N",
		"LIST_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"LIST_FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
		"PAGER_TITLE" => "Фотографии",	// Название категорий
		"SECTION_COUNT" => "20",	// Максимальное количество выводимых разделов
		"SECTION_LINE_ELEMENT_COUNT" => "3",	// Количество фотографий, выводимых в одной строке таблицы раздела
		"SECTION_PAGE_ELEMENT_COUNT" => "20",	// Количество элементов на странице
		"SECTION_SORT_FIELD" => "sort",	// По какому полю сортируем разделы
		"SECTION_SORT_ORDER" => "asc",	// Порядок сортировки разделов
		"SEF_FOLDER" => "/photo/",	// Каталог ЧПУ (относительно корня сайта)
		"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
		"SEF_URL_TEMPLATES" => array(
			"detail" => "#SECTION_ID#/#ELEMENT_ID#/",
			"section" => "#SECTION_ID#/",
			"sections_top" => "",
		),
		"SETTINGS_USE" => "Y",
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"SET_STATUS_404" => "Y",	// Устанавливать статус 404
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"SHOW_404" => "Y",	// Показ специальной страницы
		"TOP_ELEMENT_COUNT" => "100",	// Максимальное количество фотографий, выводимых в каждом разделе
		"TOP_ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем фотографии
		"TOP_ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки фотографий в разделе
		"TOP_FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"TOP_LINE_ELEMENT_COUNT" => "3",	// Количество фотографий, выводимых в одной строке таблицы списка разделов
		"TOP_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"USE_FILTER" => "N",	// Показывать фильтр
		"USE_PERMISSIONS" => "N",	// Использовать дополнительное ограничение доступа
		"USE_RATING" => "N",	// Разрешить голосование
		"USE_REVIEW" => "N",	// Разрешить отзывы
	),
	false
);?>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>