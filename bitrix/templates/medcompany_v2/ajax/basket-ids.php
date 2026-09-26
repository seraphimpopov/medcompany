<?php
// IDs of products in the visitor's basket, so product buttons can show "В корзине" on cached pages
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('PUBLIC_AJAX_MODE', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');

$ids = array();
if (\Bitrix\Main\Loader::includeModule('sale')) {
    $fuserId = \Bitrix\Sale\Fuser::getId(true);
    if ($fuserId) {
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser($fuserId, SITE_ID);
        foreach ($basket as $item) {
            if ($item->canBuy() && !$item->isDelay()) {
                $ids[] = (int)$item->getProductId();
            }
        }
    }
}

echo json_encode(array_values(array_unique($ids)));
