<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
var_dump(123);
if (!empty($arResult["NEWS"])): ?>
    <div class="news-list">
        <?php foreach ($arResult["NEWS"] as $newsItem): ?>
            <div class="news-item">
                <h2><a href="<?= $newsItem['DETAIL_PAGE_URL'] ?>"><?= $newsItem['NAME'] ?></a></h2>
                <p><?= $newsItem['PREVIEW_TEXT'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Новостей нет.</p>
<?php endif; ?>
