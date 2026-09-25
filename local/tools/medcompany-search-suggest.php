<?php
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('PUBLIC_AJAX_MODE', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/medcompany_search.php';
$APPLICATION->RestartBuffer();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') { http_response_code(405); header('Allow: GET'); echo '{}'; exit; }
$query = MedcompanySearch::text($_GET['q'] ?? '');
$response = ['items' => [], 'total' => 0, 'url' => '/search/?q='.rawurlencode($query)];
if (mb_strlen($query) >= 3 || in_array(mb_strtolower($query), ['gc', '3m', 'pd'], true)) {
    try {
        $data = MedcompanySearch::find($query);
        $response['total'] = count($data['items']);
        $top = array_slice($data['items'], 0, 6, true);
        if ($top) {
            $urls = [];
            $result = CIBlockElement::GetList([], ['IBLOCK_ID' => 16, 'ID' => array_keys($top), 'CHECK_PERMISSIONS' => 'Y'], false, false, ['ID', 'IBLOCK_ID', 'DETAIL_PAGE_URL']);
            while ($row = $result->GetNext()) { $urls[(int)$row['ID']] = $row['~DETAIL_PAGE_URL'] ?? $row['DETAIL_PAGE_URL']; }
            // Calculate only six visible products, fresh and in the current visitor's context.
            // Use the same component, retail price type, quantity and VAT rules as search cards.
            $GLOBALS['medSearchSuggestionFilter'] = ['ID' => array_keys($top)];
            $GLOBALS['medSearchSuggestionPrices'] = [];
            ob_start();
            try {
                $APPLICATION->IncludeComponent('bitrix:catalog.section', 'med_search_suggest_prices', [
                    'IBLOCK_ID' => 16, 'IBLOCK_TYPE' => 'catalogs', 'FILTER_NAME' => 'medSearchSuggestionFilter',
                    'SECTION_ID' => '', 'SECTION_CODE' => '', 'SHOW_ALL_WO_SECTION' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y',
                    'ELEMENT_SORT_FIELD' => 'ID', 'ELEMENT_SORT_ORDER' => array_keys($top),
                    'PAGE_ELEMENT_COUNT' => 6, 'PRICE_CODE' => ['Розничная'], 'PRICE_VAT_INCLUDE' => 'Y',
                    'CONVERT_CURRENCY' => 'N', 'USE_PRICE_COUNT' => 'Y', 'SHOW_PRICE_COUNT' => 1, 'USE_PRODUCT_QUANTITY' => 'Y',
                    'HIDE_NOT_AVAILABLE' => 'N', 'HIDE_NOT_AVAILABLE_OFFERS' => 'N', 'CACHE_TYPE' => 'N',
                    'DISPLAY_TOP_PAGER' => 'N', 'DISPLAY_BOTTOM_PAGER' => 'N', 'SET_TITLE' => 'N',
                    'SET_BROWSER_TITLE' => 'N', 'SET_META_KEYWORDS' => 'N', 'SET_META_DESCRIPTION' => 'N',
                    'ADD_SECTIONS_CHAIN' => 'N', 'SET_STATUS_404' => 'N', 'SHOW_404' => 'N',
                    'PROPERTY_CODE' => [], 'PRODUCT_SUBSCRIPTION' => 'N', 'DISPLAY_COMPARE' => 'N',
                ], false, ['HIDE_ICONS' => 'Y']);
            } finally { ob_end_clean(); }
            foreach ($top as $item) {
                $price = ['price' => null, 'currency' => null, 'price_text' => 'Цена по запросу'];
                if (!MedcompanySearch::priceOnRequest($item['brand'], $item['section']) && isset($GLOBALS['medSearchSuggestionPrices'][$item['id']])) {
                    $price = $GLOBALS['medSearchSuggestionPrices'][$item['id']];
                }
                $response['items'][] = array_merge(['id' => $item['id'], 'name' => $item['name'], 'article' => $item['article'],
                    'image' => MedcompanySearch::image($item['photo'], 100), 'url' => $urls[$item['id']] ?? $response['url'],
                    'stock' => $item['stock']], $price);
            }
        }
    } catch (\Throwable $exception) {
        AddMessage2Log($exception->getMessage(), 'medcompany.search');
        http_response_code(503);
        $response['error'] = true;
    }
}
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
exit;
