<?php
/** Товары, которые нельзя положить в корзину на сайте; у части из них ещё и скрыта цена. */
if (!class_exists('\Bitrix\Main\Loader')) { die(); } // подключается из init.php, до B_PROLOG_INCLUDED

final class MedcompanyPriceRequest
{
    const PHONE = '+7 (4852) 42-95-60';
    const PHONE_LINK = 'tel:+74852429560';
    const BRANDS = ['MIS', 'AmmanGirrbach'];   // «Цена при запросе» (зависит от курса), без корзины
    const NO_CART_SECTIONS = [1253];            // Анестезия ООО «АрДент»: цена видна, заказ только по телефону

    public static function hidePrice($brand)
    {
        return in_array((string)$brand, self::BRANDS, true);
    }

    public static function noCart($brand, $section)
    {
        return self::hidePrice($brand) || in_array((int)$section, self::NO_CART_SECTIONS, true);
    }

    /** $item — элемент из catalog.item / catalog.element ($item, $arResult). */
    public static function hidePriceItem(array $item)
    {
        return self::hidePrice($item['PROPERTIES']['CML2_MANUFACTURER']['VALUE'] ?? '');
    }

    public static function noCartItem(array $item)
    {
        return self::noCart($item['PROPERTIES']['CML2_MANUFACTURER']['VALUE'] ?? '', $item['IBLOCK_SECTION_ID'] ?? 0);
    }

    public static function notice(array $item)
    {
        if (self::hidePriceItem($item)) {
            return 'Цена зависит от курса доллара. Подробную информацию можно получить по номеру: '.self::PHONE.'.';
        }
        if (self::noCartItem($item)) {
            return 'Этот товар можно заказать только по телефону: '.self::PHONE.'.';
        }
        return '';
    }

    public static function noCartProduct($productId)
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock')) { return false; }
        $row = \CIBlockElement::GetList([], ['ID' => (int)$productId], false, false, ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID'])->Fetch();
        if (!$row) { return false; }
        $prop = \CIBlockElement::GetProperty($row['IBLOCK_ID'], $row['ID'], [], ['CODE' => 'CML2_MANUFACTURER'])->Fetch();
        $brand = $prop ? ($prop['VALUE_ENUM'] ?: $prop['VALUE']) : '';
        return self::noCart($brand, $row['IBLOCK_SECTION_ID']);
    }

    /** sale:OnSaleBasketItemBeforeSaved — не даём положить такой товар в корзину с сайта (админка не затрагивается). */
    public static function onBasketItemBeforeSaved(\Bitrix\Main\Event $event)
    {
        if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) { return null; }
        $basketItem = $event->getParameter('ENTITY');
        if (!$basketItem || $basketItem->getId() > 0 || $basketItem->getField('MODULE') !== 'catalog') { return null; }
        if (!self::noCartProduct($basketItem->getProductId())) { return null; }
        return new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::ERROR,
            new \Bitrix\Sale\ResultError('Этот товар можно заказать только по телефону: '.self::PHONE, 'MEDCOMPANY_PRICE_REQUEST'),
            'sale'
        );
    }
}
