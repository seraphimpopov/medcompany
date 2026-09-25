<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\LoaderException;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Localization\Loc;

class NewsListComponent extends CBitrixComponent
{
    // Подготовка параметров компонента
    public function onPrepareComponentParams($arParams)
    {
        $arParams["CACHE_TIME"] = isset($arParams["CACHE_TIME"]) ? (int)$arParams["CACHE_TIME"] : 3600;
        $arParams["PAGE_TITLE"] = isset($arParams["PAGE_TITLE"]) ? $arParams["PAGE_TITLE"] : "Список новостей";
        return $arParams;
    }

    // Главная функция выполнения компонента
    public function executeComponent()
    {
        try {
            // Проверка наличия модуля инфоблоков
            if (!Loader::includeModule('iblock')) {
                throw new Main\SystemException(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALL"));
            }

            $cacheTime = $this->arParams['CACHE_TIME']; // Время кэширования
            $cache = Cache::createInstance();           // Экземпляр кэша
            $cacheKey = 'news_list_' . md5(serialize($this->arParams)); // Уникальный ключ для кэша

            // Проверка наличия кэша
            if ($cache->initCache($cacheTime, $cacheKey)) {
                $this->arResult = $cache->getVars();    // Получение данных из кэша
            } elseif ($cache->startDataCache()) {        // Если кэша нет, создаем его
                $newsList = $this->getNewsList();       // Получение списка новостей

                // Проверка, что новости получены
                if (empty($newsList)) {
                    $cache->abortDataCache();           // Прекращаем кэширование, если данных нет
                } else {
                    $this->arResult['NEWS'] = $newsList;
                    $cache->endDataCache($this->arResult);  // Сохраняем в кэш
                }
            }

            // Устанавливаем заголовок страницы
            $this->setPageTitle();

            // Подключаем шаблон компонента
            $this->includeComponentTemplate();

        } catch (LoaderException $e) {
            ShowError($e->getMessage());
        }
    }

    // Получение списка новостей с использованием D7 API
    private function getNewsList()
    {
        $news = [];
        $result = ElementTable::getList([
            "select" => ["ID", "NAME", "PREVIEW_TEXT"],
            "filter" => ["IBLOCK_ID" => 47, "ACTIVE" => "Y"],
        ]);

        while ($item = $result->fetch()) {
            $news[] = $item;
        }

        return $news;
    }

    // Установка заголовка страницы
    private function setPageTitle()
    {
        global $APPLICATION;
        if ($this->arParams['PAGE_TITLE']) {
            $APPLICATION->SetTitle($this->arParams['PAGE_TITLE']);
        }
    }
}
?>
