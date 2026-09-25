<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;

class NewsDetailComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError("Модуль инфоблоков не загружен");
            return;
        }

        $newsId = $this->arParams['ELEMENT_ID'];
        $this->arResult['NEWS'] = $this->getNewsDetail($newsId);

        if (!$this->arResult['NEWS']) {
            ShowError("Новость не найдена");
        } else {
            $this->includeComponentTemplate();
        }
    }

    private function getNewsDetail($newsId)
    {
        return ElementTable::getList([
            "select" => ["ID", "NAME", "PREVIEW_TEXT"],
            "filter" => ["ID" => $newsId, "ACTIVE" => "Y"],
        ])->fetch();
    }
}
?>
