<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
$e = ['MedcompanySearch', 'escape'];
$meta = $item['MED_SEARCH_META'] ?? [];
$photo = $item['MED_SEARCH_PHOTO'] ?? '';
$requestPrice = MedcompanySearch::priceOnRequest($meta['brand'] ?? '', $meta['section'] ?? 0);
$phoneOnly = MedcompanySearch::noCart($meta['brand'] ?? '', $meta['section'] ?? 0);
$canBuy = !$phoneOnly && !empty($price) && $item['CAN_BUY'];
?>
<article class="ms-card" data-product-id="<?=(int)$item['ID']?>">
    <a class="ms-card-image" href="<?=$e($item['DETAIL_PAGE_URL'])?>" data-entity="image-wrapper" aria-label="<?=$e($productTitle)?>">
        <span id="<?=$e($itemIds['PICT'])?>"><?php if ($photo): ?><img src="<?=$e($photo)?>" alt="<?=$e($productTitle)?>" width="360" height="360" loading="lazy" decoding="async"><?php else: ?><span class="ms-no-photo"><svg width="42" height="42" viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="6" y="9" width="36" height="30" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="17" cy="19" r="4" stroke="currentColor" stroke-width="2"/><path d="m8 35 11-10 8 7 5-5 9 8" stroke="currentColor" stroke-width="2"/></svg><span>Фото пока нет</span></span><?php endif; ?></span>
        <span id="<?=$e($itemIds['PICT_SLIDER'])?>" hidden></span>
    </a>
    <div class="ms-card-body">
        <p class="ms-card-brand"><?=$e($meta['brand'] ?? '')?></p>
        <h2 class="ms-card-title"><a href="<?=$e($item['DETAIL_PAGE_URL'])?>"><?=$e($productTitle)?></a></h2>
        <p class="ms-card-article"><?=!empty($meta['article']) ? 'Арт. '.$e($meta['article']) : 'Код товара: '.(int)$item['ID']?></p>
        <p class="ms-stock <?=!empty($meta['stock']) ? 'ms-stock-yes' : ''?>"><?=!empty($meta['stock']) ? 'В наличии' : ($item['CAN_BUY'] ? 'Доступен под заказ' : 'Нет в наличии')?></p>
        <div class="ms-card-bottom">
            <div class="ms-card-price" id="<?=$e($itemIds['PRICE'])?>" data-entity="price-block"><?=$requestPrice || !$price ? 'Цена по запросу' : $price['PRINT_RATIO_PRICE']?></div>
            <?php if ($canBuy): ?>
                <div class="ms-buy-row" id="<?=$e($itemIds['BASKET_ACTIONS'])?>">
                    <div class="ms-quantity" data-entity="quantity-block">
                        <button type="button" id="<?=$e($itemIds['QUANTITY_DOWN'])?>" aria-label="Уменьшить количество">−</button>
                        <input type="number" id="<?=$e($itemIds['QUANTITY'])?>" name="quantity" value="<?=$e($measureRatio)?>" min="<?=$e($measureRatio)?>" step="<?=$e($measureRatio)?>" aria-label="Количество товара <?=$e($productTitle)?>">
                        <button type="button" id="<?=$e($itemIds['QUANTITY_UP'])?>" aria-label="Увеличить количество">+</button>
                    </div>
                    <button type="button" class="ms-primary ms-buy" id="<?=$e($itemIds['BUY_LINK'])?>">В корзину</button>
                </div>
            <?php else: ?>
                <?php if ($requestPrice || !$price): ?>
                    <a class="ms-contact" href="tel:+74852429560">Уточнить цену</a>
                <?php elseif ($phoneOnly): ?>
                    <a class="ms-contact" href="tel:+74852429560">Заказать по телефону</a>
                <?php else: ?>
                    <a class="ms-contact" href="<?=$e($item['DETAIL_PAGE_URL'])?>">Подробнее о товаре</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</article>
