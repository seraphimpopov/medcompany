<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="container">
    <?php
    if (!empty($arResult["NEWS"])): ?>
        <div class="row">
            <?php foreach ($arResult["NEWS"] as $newsItem): ?>
            <div class="col-xl-3 col-md-3 col-6">
                <div class="news-item product-item">
                    <h2><?= $newsItem['NAME'] ?></h2>
                    <p><?= $newsItem['PREVIEW_TEXT'] ?></p>
                    <a href="/newstest/<?= $newsItem['ID'] ?>/">Подробнее</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Новостей нет.</p>
    <?php endif; ?>
</div>
