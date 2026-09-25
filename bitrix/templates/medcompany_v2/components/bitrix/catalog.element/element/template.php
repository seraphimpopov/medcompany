 <?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$templateLibrary = array('popup', 'fx', 'ui.fonts.opensans');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$haveOffers = !empty($arResult['OFFERS']);

$templateData = [
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => [
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
    ],
];
if ($haveOffers) {
    $templateData['ITEM']['OFFERS_SELECTED'] = $arResult['OFFERS_SELECTED'];
    $templateData['ITEM']['JS_OFFERS'] = $arResult['JS_OFFERS'];
}
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $haveOffers && !empty($arResult['OFFERS_PROP']) ? $mainId . '_skudiv' : null,
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DESCRIPTION_ID' => $mainId . '_description',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = $arResult['NAME']; //!empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : 
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

if ($haveOffers) {
    $actualItem = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] ?? reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

if ($arParams['SHOW_SKU_DESCRIPTION'] === 'Y') {
    $skuDescription = false;
    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['DETAIL_TEXT'] != '' || $offer['PREVIEW_TEXT'] != '') {
            $skuDescription = true;
            break;
        }
    }
    $showDescription = $skuDescription || !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
} else {
    $showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
}
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');

$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$titles = $arResult["NAME"] . ' от интернет магазина Медкомпания.рф';
$APPLICATION->SetPageProperty("title", $titles);



$themeClass = isset($arParams['TEMPLATE_THEME']) ? ' bx-' . $arParams['TEMPLATE_THEME'] : '';

require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/medcompany_price_request.php';
$showPriceOnRequest = MedcompanyPriceRequest::hidePriceItem($arResult);
$noCart = MedcompanyPriceRequest::noCartItem($arResult);
$priceInfoText = MedcompanyPriceRequest::notice($arResult);
?>

<??>


<? /*echo "<pre>"; var_dump($arResult["PROPERTIES"]["CML2_MANUFACTURER"]);*/ ?>
    <div class="container">
        <!--<button style="position: relative" data-id="<?php /*= $arResult['ID'] */ ?>" type="button" class="bazarow_add_favor">
                <svg class="icon">
                    <use xlink:href="#heart"></use>
                </svg>
            </button>-->
<div class="product_row row">
<div class="col-xl-2">
            </div>
<? if ($arResult['PROPERTIES']['CML2_MANUFACTURER']["VALUE"] == 'HLW') { ?>
	<img style="max-width: 330px" src="/upload/medialibrary/584/jsjppz7effwyv8cuoee59ehfh1p7r58n.jpg">
	<? } ?>
</div>
        <div class="product_row row" id="<?= $itemIds['ID'] ?>" itemscope itemtype="http://schema.org/Product">
            <div class="col-xl-2">
            </div>
            <div class="product_row_images_dop col-xl-1 col-md-1">
                <? if (count($arResult["MORE_PHOTO"]) > 0) {
                    foreach ($arResult["MORE_PHOTO"] as $PHOTO) {
                        ?>
                        <a href="<?= $PHOTO['SRC'] ?>" data-fancybox="images"
                           data-caption="Купить <?= $arResult['NAME'] ?>">
                            <img src="<?= $PHOTO['SRC'] ?>" alt="<? echo $actualItem['NAME'] ?>">
                        </a>
                    <? }
                } ?>
            </div>
            <div class="product_row_images col-xl-3 col-md-6 col-xs-12" id="<?= $itemIds['BIG_SLIDER_ID'] ?>">
                <button data-id="<?= $arResult['ID'] ?>" type="button" class="bazarow_add_favor mk-fav-detail" aria-label="Добавить в избранное"></button>

                <div class="product_row_images_main" data-entity="images-container">
                    <? if ($arResult['IMAGE']['src']) { ?>
                        <a href="<?= $arResult['IMAGE']['src'] ?>" data-fancybox="images"
                           data-caption="Купить <?= $arResult['NAME'] ?>">
<link rel="preload" as="image" href="<?= $arResult['IMAGE']['src'] ?>">
                            <img itemprop="image" loading="eager" fetchpriority="high" src="<?= $arResult['IMAGE']['src'] ?>"
                                 alt="Купить <? echo $actualItem['NAME'] ?>">
                        </a>
                        <?
                    } else { ?>
                    <div style="margin-bottom: 10px;">
                        <a href="<?= $arResult['DEFAULT_PICTURE']['SRC'] ?>">
<link rel="preload" as="image" href="<?= $arResult['DEFAULT_PICTURE']['SRC'] ?>">
                            <img itemprop="image" loading="eager" fetchpriority="high" src="<?= $arResult['DEFAULT_PICTURE']['SRC'] ?>" alt="Купить <? echo $actualItem['NAME'] ?>">
                        </a>
                    </div>
                    <? }?>
                </div>
            </div>
            <div class="cot col-xxl-4 col-xl-4 col-md-6">
                <div style="margin-bottom: 20px;">
                    <h1 class="tit1"><?= $arResult['NAME'] ?></h1>
				</div>
				<? if (!empty($arResult['PROPERTIES']['CML2_ARTICLE']['VALUE'])) { ?>
<div style="margin-bottom: 20px;">
                    <span class=""
	style="font-weight: bold; flex-wrap: wrap"><?=$arResult['PROPERTIES']['CML2_ARTICLE']['NAME']?>: <?= $arResult['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?></span>
				</div>
				<?}?>
                <div class="prop">
                    <span class="tit" style="margin-bottom: 20px; font-weight: bold; flex-wrap: wrap"><?= $arResult['NAME'] ?></span>
                    <? if ($arResult['PREVIEW_IMAGE']['IMAGE']) { ?>
                        <div style="margin-bottom: 10px;">
                            <a href="<?= $arResult['PREVIEW_IMAGE']['SRC'] ?>">
                                <img itemprop="image" src="<?= $arResult['PREVIEW_IMAGE']['IMAGE']['src'] ?>" alt="<?= $arResult['IMAGE_ALT'] ?>">
                            </a>
                        </div>
                    <? } ?>
                    <!--<ul class="product_row_content_props">
                        <? /* foreach ($arResult['DISPLAY_PROPERTIES'] as $PROPERTY) { */ ?>
                            <li>
                                <span style="font-weight: bold; margin-right: 10px;"><?php /*= $PROPERTY['NAME'] */ ?>:</span>
                                <? /* if (is_array($PROPERTY['VALUE'])) { */ ?>
                                    <? /* echo implode(', ', $PROPERTY['VALUE']) */ ?>
                                    <? /*
                                } else { */ ?>
                                    <?php /*= $PROPERTY['VALUE'] */ ?>
                                    <? /*
                                } */ ?>
                            </li>
                            <? /*
                        } */ ?>
                    </ul>-->
                    <? if (!empty($arResult["PROPERTIES"]["ATT_TOVAR"]["VALUE"])) {
                        ?>
                        <div class="types">
                            <span style="font-weight: bold;">Тип:</span>
                            <ul class="type-types">
                                <? foreach ($arResult["LINKED_ITEMS"] as $type => $items) {
                                    ?>
                                    <a href="#" class="type-link" data-type="<? echo $type ?>">
                                        <? echo $type ?>
                                    </a>
                                <? } ?>
                            </ul>
                        </div>
                        <?
                    } ?>
                    <? if (!empty($arResult["PROPERTIES"]["ATT_TOVAR"]["VALUE"])) {
                        ?>
                        <div class="properties">
                            <span style="font-weight: bold; margin-right: 10px;">Вид:</span>
                            <? foreach ($arResult["LINKED_ITEMS"] as $type => $items) {
                                ?>
                                <ul class="type-properties" data-type="<? echo $type ?>">
                                    <? foreach ($items as $item) { ?>
                                        <a class="url"
                                           href="<? echo $item["DETAIL_PAGE_URL"] ?>">
                                            <? echo $item["PROPERTY_ATT_VIEW_VALUE"] ?>
                                        </a>
                                    <? } ?>
                                </ul>
                                <?
                            } ?>
                        </div>
                        <?
                    } ?>
                </div>
                <?php
                $showOffersBlock = $haveOffers && !empty($arResult['OFFERS_PROP']);
                $mainBlockProperties = array_intersect_key($arResult['DISPLAY_PROPERTIES'], $arParams['MAIN_BLOCK_PROPERTY_CODE']);
                $showPropsBlock = !empty($mainBlockProperties) || $arResult['SHOW_OFFERS_PROPS'];
                $showBlockWithOffersAndProps = $showOffersBlock || $showPropsBlock;
                ?>
                <div class="product_row_content">
                    <div class="product_row_content_actions" style="margin-top: 20px;">
                        <?php
                        foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName) {
                            switch ($blockName) {

                                case 'price':
                                    ?>
						<div class="row" style="margin-left:0">
							<div style="margin-right:20px" class="product_row_content_price"<?=($showPriceOnRequest ? '' : ' id="'.$itemIds['PRICE_ID'].'"')?>>
								<?=($showPriceOnRequest ? 'Цена при запросе' : $price['PRINT_RATIO_PRICE'])?>
											</div>
<?if ($arParams['SHOW_OLD_PRICE'] === 'Y') {?>
                                    <div class="product_row_content_price" style="display: <?=($showDiscount && !$showPriceOnRequest ? '' : 'none')?>;"<?=($showPriceOnRequest ? '' : ' id="'.$itemIds['PRICE_ID'].'"')?>>
<div class="price-old">
										<?=($showDiscount && !$showPriceOnRequest ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
										</div>
                                    </div>
</div>
					<?}?>
                                    <?php
                                    break;
                                case 'priceRanges':
                                    if ($arParams['USE_PRICE_COUNT']) {
                                        $showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
                                        $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
                                        ?>
                                        <div class="mb-3"
                                            <?= $showRanges ? '' : 'style="display: none;"' ?>
                                             data-entity="price-ranges-block">
                                            <?php
                                            if ($arParams['MESS_PRICE_RANGES_TITLE']) {
                                                ?>
                                                <div class="product-item-detail-info-container-title text-center">
                                                    <?= $arParams['MESS_PRICE_RANGES_TITLE'] ?>
                                                    <span data-entity="price-ranges-ratio-header">
												(<?= (Loc::getMessage(
                                                            'CT_BCE_CATALOG_RATIO_PRICE',
                                                            array('#RATIO#' => ($useRatio ? $measureRatio : '1') . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                        )) ?>)
											</span>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <ul class="product-item-detail-properties" data-entity="price-ranges-body">
                                                <?php
                                                if ($showRanges) {
                                                    foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range) {
                                                        if ($range['HASH'] !== 'ZERO-INF') {
                                                            $itemPrice = false;

                                                            foreach ($arResult['ITEM_PRICES'] as $itemPrice) {
                                                                if ($itemPrice['QUANTITY_HASH'] === $range['HASH']) {
                                                                    break;
                                                                }
                                                            }

                                                            if ($itemPrice) {
                                                                ?>
                                                                <li class="product-item-detail-properties-item">
																<span class="product-item-detail-properties-name text-muted">
																	<?php
                                                                    echo Loc::getMessage(
                                                                            'CT_BCE_CATALOG_RANGE_FROM',
                                                                            array('#FROM#' => $range['SORT_FROM'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                                        ) . ' template.php';

                                                                    if (is_infinite($range['SORT_TO'])) {
                                                                        echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                                                                    } else {
                                                                        echo Loc::getMessage(
                                                                            'CT_BCE_CATALOG_RANGE_TO',
                                                                            array('#TO#' => $range['SORT_TO'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                                        );
                                                                    }
                                                                    ?>
																</span>
                                                                    <span class="product-item-detail-properties-dots"></span>
                                                                    <span class="product-item-detail-properties-value"><?= ($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']) ?></span>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                        unset($showRanges, $useRatio, $itemPrice, $range);
                                    }

                                    break;

                                case 'quantityLimit':
                                    if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                                        if ($haveOffers) {
                                            ?>
                                            <div class="mb-3" id="<?= $itemIds['QUANTITY_LIMIT'] ?>"
                                                 style="display: none;">
                                                <div class="product-item-detail-info-container-title text-center">
                                                    <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                                </div>
                                                <span class="product-item-quantity"
                                                      data-entity="quantity-limit-value"></span>
                                            </div>
                                            <?php
                                        } else {
                                            if (
                                                $measureRatio
                                                && (float)$actualItem['PRODUCT']['QUANTITY'] >= 0
                                                && $actualItem['CHECK_QUANTITY']
                                            ) {
                                                ?>
                                                <div class="mb-3 text-center nal"
                                                     id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
                                                    <?/*echo "<pre>"; var_dump($arParams);*/ ?>
                                                    <span class="product-item-detail-info-container-title"><?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:</span>
                                                    <span style="font-size: 18px;" class="product-item-quantity"
                                                          data-entity="quantity-limit-value">
													<?php
                                                    if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                                        if ((float)$actualItem['PRODUCT']['QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                            echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                        } else {
                                                            echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                        }
                                                    } else {
                                                        if ($actualItem['CAN_BUY']) {
                                                            echo 'В наличии';
                                                        } else {
                                                            echo 'Нет в наличии';
                                                        }
                                                    }

                                                    ?>
												</span>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }

 if (!empty($priceInfoText)) { ?>
    <div class="block" style="margin-bottom: 20px!important; margin: 0;">
        <a href="tel:+74852429560"
           rel="noopener noreferrer"
           style="font-weight: bold; color: green;"
           title="Информация о цене">
            <?= htmlspecialcharsbx($priceInfoText) ?>
        </a>
    </div>
<? }
                                    break;
                                case 'quantity':
                                    if ($arParams['USE_PRODUCT_QUANTITY']) {
                                        ?>
<? if ($noCart) {?>
<div class="mb-3 quant"
                                             data-entity="quantity-block">
                                            <div class="product_row_content_btn ml-0" data-entity="main-button-container">
                                                <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>">
                                                    <button type="button" rel="nofollow"
                                                       onclick="window.location.href='<?= MedcompanyPriceRequest::PHONE_LINK ?>';">
                                                        Сделать звонок
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
													<? } else {?>
                                        <div class="mb-3 quant" <?= (!$actualItem['CAN_BUY'] ? ' style="display: none;"' : '') ?>
                                             data-entity="quantity-block">

                                            <div class="product-item-amount">
                                                <div class="product-item-amount-field-container">
                                            <span class="product-item-amount-field-btn-minus no-select"
                                                  id="<?= $itemIds['QUANTITY_DOWN_ID'] ?>"></span>
                                                    <div class="product-item-amount-field-block">
                                                        <input class="product-item-amount-field"
                                                               id="<?= $itemIds['QUANTITY_ID'] ?>"
                                                               type="number" value="<?= $price['MIN_QUANTITY'] ?>">
                                                    </div>
                                                    <span class="product-item-amount-field-btn-plus no-select"
                                                          id="<?= $itemIds['QUANTITY_UP_ID'] ?>"></span>
                                                </div>
                                            </div>
                                            <div class="product_row_content_btn" data-entity="main-button-container">
                                                <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>">
                                                    <button id="<?= $itemIds['ADD_BASKET_LINK'] ?>" rel="nofollow"
                                                       href="javascript:void(0);">
                                                        <?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
						<? } ?>
                                        <?php
                                    }

                                    if (!$actualItem['CAN_BUY'] && !$noCart) { ?>
                                        <div class="product_row_content_btn-1" data-entity="main-button-container">
                                            <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>">
                                                <button id="<?= $itemIds['ADD_BASKET_LINK'] ?>" rel="nofollow"
                                                   href="tel: +74852429560">
                                                    УЗНАТЬ О НАЛИЧИИ
                                                </button>
                                            </div>
                                        </div>
                                    <? }

                                    break;
                            }
                        }

                        if ($arParams['DISPLAY_COMPARE']) {
                            ?>
                            <div class="product-item-detail-compare-container">
                                <div class="product-item-detail-compare">
                                    <div class="checkbox">
                                        <label class="m-0" id="<?= $itemIds['COMPARE_LINK'] ?>">
                                            <input type="checkbox" data-entity="compare-checkbox">
                                            <span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <? if (!empty($arResult["PROPERTIES"]['ATT_TEXT']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult["PROPERTIES"]['ATT_TEXT']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult["PROPERTIES"]['ATT_TEXT']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <? if (!empty($arResult["PROPERTIES"]['PURPOSE']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['PURPOSE']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['PURPOSE']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <? if (!empty($arResult["PROPERTIES"]['ADVANTAGES']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['ADVANTAGES']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['ADVANTAGES']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <? if (!empty($arResult["PROPERTIES"]['SHADES']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['SHADES']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['SHADES']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <? if (!empty($arResult["PROPERTIES"]['TECHNICAL_SPECIFICATIONS']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['TECHNICAL_SPECIFICATIONS']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['TECHNICAL_SPECIFICATIONS']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <? if (!empty($arResult["PROPERTIES"]['EQUIPMENT']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['EQUIPMENT']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['EQUIPMENT']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <? if (!empty($arResult["PROPERTIES"]['CONTRAINDICATIONS']['~VALUE']['TEXT'])) { ?>
            <div class="block-biography row" style="margin-bottom: 20px!important; margin: 0;">
                <div class="col-xl-3"></div>
                <div class="block col-xl-7">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['CONTRAINDICATIONS']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['CONTRAINDICATIONS']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
        <meta itemprop="name" content="<?= $name ?>"/>
        <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
        <meta itemprop="id" content="<?= $arResult['ID'] ?>"/>
        <?php
        if ($haveOffers) {
            foreach ($arResult['JS_OFFERS'] as $offer) {
                $currentOffersList = array();

                if (!empty($offer['TREE']) && is_array($offer['TREE'])) {
                    foreach ($offer['TREE'] as $propName => $skuId) {
                        $propId = (int)substr($propName, 5);

                        foreach ($skuProps as $prop) {
                            if ($prop['ID'] == $propId) {
                                foreach ($prop['VALUES'] as $propId => $propValue) {
                                    if ($propId == $skuId) {
                                        $currentOffersList[] = $propValue['NAME'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                $offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
                ?>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="sku" content="<?= htmlspecialcharsbx(implode('/', $currentOffersList)) ?>"/>
			<meta itemprop="price" content="<?= $offerPrice['RATIO_PRICE'] ?>"/>
			<meta itemprop="priceCurrency" content="<?= $offerPrice['CURRENCY'] ?>"/>
			<link itemprop="availability"
                  href="http://schema.org/<?= ($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
		</span>
                <?php
            }

            unset($offerPrice, $currentOffersList);
        } else {
            ?>
            <div class="product_info"></div>


            <meta itemprop="name" content="<?= $name ?>"/>
            <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
		<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
		<link itemprop="availability"
              href="http://schema.org/<?= ($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
	</span>
            <?php
        }
        ?>
    </div>
<?php
$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties) {
    ?>
    <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
        <?php
        if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
            foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo) {
                ?>
                <input type="hidden" name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                       value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                <?php
                unset($arResult['PRODUCT_PROPERTIES'][$propId]);
            }
        }

        $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
        if (!$emptyProductProperties) {
            ?>
            <table>
                <?php
                foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo) {
                    ?>
                    <tr>
                        <td><?= $arResult['PROPERTIES'][$propId]['NAME'] ?></td>
                        <td>
                            <?php
                            if (
                                $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
                                && $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
                            ) {
                                foreach ($propInfo['VALUES'] as $valueId => $value) {
                                    ?>
                                    <label>
                                        <input type="radio"
                                               name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                               value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"checked"' : '') ?>>
                                        <?= $value ?>
                                    </label>
                                    <br>
                                    <?php
                                }
                            } else {
                                ?>
                                <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
                                    <?php
                                    foreach ($propInfo['VALUES'] as $valueId => $value) {
                                        ?>
                                        <option value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"selected"' : '') ?>>
                                            <?= $value ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>
    <?php
}

$jsParams = array(
    'CONFIG' => array(
        'USE_CATALOG' => $arResult['CATALOG'],
        'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
        'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
        'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
        'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
        'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
        'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
        'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
        'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
        'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
        'USE_STICKERS' => true,
        'USE_SUBSCRIBE' => $showSubscribe,
        'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
        'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
        'ALT' => $alt,
        'TITLE' => $title,
        'MAGNIFIER_ZOOM_PERCENT' => 200,
        'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
        'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
        'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
            ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
            : null
    ),
    'VISUAL' => $itemIds,
    'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
    'PRODUCT' => array(
        'ID' => $arResult['ID'],
        'ACTIVE' => $arResult['ACTIVE'],
        'PICT' => reset($arResult['MORE_PHOTO']),
        'NAME' => $arResult['~NAME'],
        'SUBSCRIPTION' => true,
        'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
        'ITEM_PRICES' => $arResult['ITEM_PRICES'],
        'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
        'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
        'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
        'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
        'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
        'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
        'SLIDER' => $arResult['MORE_PHOTO'],
        'CAN_BUY' => $arResult['CAN_BUY'],
        'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
        'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
        'MAX_QUANTITY' => $arResult['PRODUCT']['QUANTITY'],
        'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
        'CATEGORY' => $arResult['CATEGORY_PATH']
    ),
    'BASKET' => array(
        'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
        'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'EMPTY_PROPS' => $emptyProductProperties,
        'BASKET_URL' => $arParams['BASKET_URL'],
        'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
        'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
    )
);
unset($emptyProductProperties);

if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['COMPARE'] = array(
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH']
    );
}

$jsParams["IS_FACEBOOK_CONVERSION_CUSTOMIZE_PRODUCT_EVENT_ENABLED"] =
    $arResult["IS_FACEBOOK_CONVERSION_CUSTOMIZE_PRODUCT_EVENT_ENABLED"];
?>
    </div>
    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_DETAIL_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_DETAIL_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
        });

        var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>

    <script>
        var currentType = '<?=$arResult["PROPERTIES"]["ATT_TYPE"]["VALUE"]?>';
        localStorage.setItem('selectedType', currentType);

        // Скрываем все свойства типов товаров
        document.querySelectorAll('.type-properties').forEach(function (el) {
            el.style.display = 'none';
        });

        // Добавляем обработчик кликов на каждый тип товара
        document.querySelectorAll('.type-link').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();

                // Получаем выбранный тип
                var type = this.getAttribute('data-type');

                // Находим URL первого товара этого типа
                var firstItemUrl = document.querySelector('.type-properties[data-type="' + type + '"] .url').href;

                // Сохраняем выбранный тип в localStorage
                localStorage.setItem('selectedType', type);

                // Переадресация на первый товар выбранного типа
                if (firstItemUrl) {
                    window.location.href = firstItemUrl;
                }

                // Добавляем класс active для выбранного типа
                document.querySelectorAll('.type-link').forEach(function (el) {
                    el.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // Проверяем, есть ли выбранный тип в localStorage
        var selectedType = localStorage.getItem('selectedType');
        if (selectedType) {
            // Если есть, показываем свойства этого типа
            document.querySelector('.type-properties[data-type="' + selectedType + '"]').style.display = 'grid';
            // Добавляем класс active для выбранного типа
            document.querySelector('.type-link[data-type="' + selectedType + '"]').classList.add('active');
        }

        // Получаем текущий URL
        var currentUrl = window.location.href;

        // Находим все ссылки с классом "url"
        var links = document.querySelectorAll('.url');

        // Для каждой ссылки проверяем, совпадает ли её href с текущим URL
        links.forEach(function (link) {
            if (link.href === currentUrl) {
                // Если совпадает, добавляем класс "active"
                link.classList.add('active');
            }
        });
    </script>

<?php
unset($actualItem, $itemIds, $jsParams);