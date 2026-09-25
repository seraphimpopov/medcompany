<?php
require_once __DIR__.'/medcompany_price_request.php';

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleBasketItemBeforeSaved',
    ['MedcompanyPriceRequest', 'onBasketItemBeforeSaved']
);
