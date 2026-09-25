<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);

$mkChecked = 0;
foreach ($arResult["ITEMS"] as $arItem) {
    if (isset($arItem["PRICE"]) || $arItem["DISPLAY_TYPE"] === "A") {
        if (!empty($arItem["VALUES"]["MIN"]["HTML_VALUE"]) || !empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) {
            $mkChecked++;
        }
        continue;
    }
    foreach ((array)$arItem["VALUES"] as $ar) {
        if (!empty($ar["CHECKED"])) {
            $mkChecked++;
        }
    }
}
$mkCount = isset($arResult["ELEMENT_COUNT"]) ? (int)$arResult["ELEMENT_COUNT"] : null;

// Slider markup shared by price and numeric properties
$mkSlider = function ($arItem, $key) {
    ?>
    <div class="smart-filter-digits mk-range-inputs">
        <label class="mk-range-inputs__field">
            <span>от</span>
            <input type="number" inputmode="numeric"
                   name="<?= $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                   id="<?= $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                   value="<?= $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                   placeholder="<?= floor($arItem["VALUES"]["MIN"]["VALUE"]) ?>"
                   onkeyup="smartFilter.keyup(this)"/>
        </label>
        <label class="mk-range-inputs__field">
            <span>до</span>
            <input type="number" inputmode="numeric"
                   name="<?= $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                   id="<?= $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                   value="<?= $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                   placeholder="<?= ceil($arItem["VALUES"]["MAX"]["VALUE"]) ?>"
                   onkeyup="smartFilter.keyup(this)"/>
        </label>
    </div>
    <div class="smart-filter-slider-track-container">
        <div class="smart-filter-slider-track" id="drag_track_<?= $key ?>">
            <div class="smart-filter-slider-price-bar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?= $key ?>"></div>
            <div class="smart-filter-slider-price-bar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?= $key ?>"></div>
            <div class="smart-filter-slider-price-bar-v" style="left: 0;right: 0;" id="colorAvailableActive_<?= $key ?>"></div>
            <div class="smart-filter-slider-range" id="drag_tracker_<?= $key ?>" style="left: 0;right: 0;">
                <a class="smart-filter-slider-handle left" style="left:0;" href="javascript:void(0)" id="left_slider_<?= $key ?>" aria-label="Минимальное значение"></a>
                <a class="smart-filter-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?= $key ?>" aria-label="Максимальное значение"></a>
            </div>
        </div>
    </div>
    <?
    $arJsParams = array(
        "leftSlider" => 'left_slider_' . $key,
        "rightSlider" => 'right_slider_' . $key,
        "tracker" => "drag_tracker_" . $key,
        "trackerWrap" => "drag_track_" . $key,
        "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
        "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
        "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
        "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
        "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
        "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
        "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
        "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
        "precision" => !empty($arItem["DECIMALS"]) ? $arItem["DECIMALS"] : 0,
        "colorUnavailableActive" => 'colorUnavailableActive_' . $key,
        "colorAvailableActive" => 'colorAvailableActive_' . $key,
        "colorAvailableInactive" => 'colorAvailableInactive_' . $key,
    );
    ?>
    <script>
        BX.ready(function () {
            window['trackBar<?= $key ?>'] = new BX.Iblock.SmartFilter(<?= CUtil::PhpToJSObject($arJsParams) ?>);
        });
    </script>
    <?
};
?>

<button class="mobile_filter_button mk-filter-open" type="button" aria-controls="mk-filter-panel" aria-expanded="false">
    <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M4 6h16M7 12h10M10 18h4"/></svg>
    Фильтры<? if ($mkChecked): ?> <span class="mk-filter-open__badge"><?= $mkChecked ?></span><? endif ?>
</button>

<div class="mobile_filter_panel" id="mk-filter-panel">
    <div class="mk-filter-sheet__head">
        <span>Фильтры</span>
        <button type="button" class="mk-icon-btn mk-filter-close" aria-label="Закрыть фильтры">
            <svg class="mk-ico" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <form name="<?= $arResult["FILTER_NAME"] . "_form" ?>" action="<?= $arResult["FORM_ACTION"] ?>" method="get" class="mk-filter">

        <? foreach ($arResult["HIDDEN"] as $arItem): ?>
            <input type="hidden" name="<?= $arItem["CONTROL_NAME"] ?>" id="<?= $arItem["CONTROL_ID"] ?>" value="<?= $arItem["HTML_VALUE"] ?>"/>
        <? endforeach; ?>

        <div class="smart-filter">
            <?
            // prices first
            foreach ($arResult["ITEMS"] as $arItem):
                if (!isset($arItem["PRICE"])) continue;
                if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0) continue;
                $key = $arItem["ENCODED_ID"];
                ?>
                <div class="smart-filter-parameters-box bx-active">
                    <span class="smart-filter-container-modef"></span>
                    <button type="button" class="smart-filter_title" aria-expanded="true" onclick="smartFilter.hideFilterProps(this)">
                        Цена, ₽
                    </button>
                    <div class="mk-collapse" data-role="bx_filter_block">
                        <div class="mk-collapse__inner">
                            <? $mkSlider($arItem, $key) ?>
                        </div>
                    </div>
                </div>
            <? endforeach;

            // other properties
            foreach ($arResult["ITEMS"] as $key => $arItem):
                if (empty($arItem["VALUES"]) || isset($arItem["PRICE"])) continue;
                if ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)) continue;

                $mkVisible = array();
                if ($arItem["DISPLAY_TYPE"] != "A") {
                    foreach ($arItem["VALUES"] as $val => $ar) {
                        if (empty($ar["DISABLED"]) || !empty($ar["CHECKED"])) {
                            $mkVisible[$val] = $ar;
                        }
                    }
                    if (!$mkVisible) continue;
                }
                $mkOpen = $arItem["DISPLAY_EXPANDED"] == "Y" || array_filter($mkVisible, function ($v) { return !empty($v["CHECKED"]); });
                ?>
                <div class="smart-filter-parameters-box<?= $mkOpen ? ' bx-active' : '' ?>">
                    <span class="smart-filter-container-modef"></span>
                    <button type="button" class="smart-filter_title" aria-expanded="<?= $mkOpen ? 'true' : 'false' ?>" onclick="smartFilter.hideFilterProps(this)">
                        <?= $arItem["NAME"] ?>
                    </button>
                    <div class="mk-collapse" data-role="bx_filter_block">
                        <div class="mk-collapse__inner">
                            <? if ($arItem["DISPLAY_TYPE"] == "A"): ?>
                                <? $mkSlider($arItem, $arItem["ENCODED_ID"] ?: $key) ?>
                            <? else: ?>
                                <? if (count($mkVisible) > 8): ?>
                                    <input type="search" class="mk-filter-search" placeholder="Найти: <?= htmlspecialcharsbx(mb_strtolower($arItem["NAME"])) ?>"
                                           aria-label="Поиск по списку «<?= htmlspecialcharsbx($arItem["NAME"]) ?>»" autocomplete="off">
                                <? endif ?>
                                <div class="mk-checklist" role="group" aria-label="<?= htmlspecialcharsbx($arItem["NAME"]) ?>">
                                    <? foreach ($mkVisible as $val => $ar): ?>
                                        <label class="mk-check<?= !empty($ar["DISABLED"]) ? ' disabled' : '' ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>" for="<?= $ar["CONTROL_ID"] ?>">
                                            <input type="checkbox"
                                                   value="<?= $ar["HTML_VALUE"] ?>"
                                                   name="<?= $ar["CONTROL_NAME"] ?>"
                                                   id="<?= $ar["CONTROL_ID"] ?>"
                                                <?= !empty($ar["CHECKED"]) ? 'checked' : '' ?>
                                                   onclick="smartFilter.click(this)"/>
                                            <span class="mk-check__box" aria-hidden="true"></span>
                                            <span class="mk-check__text"><?= $ar["VALUE"] ?></span>
                                            <? if (isset($ar["ELEMENT_COUNT"])): ?>
                                                <span class="mk-check__count" data-role="count_<?= $ar["CONTROL_ID"] ?>"><?= $ar["ELEMENT_COUNT"] ?></span>
                                            <? endif ?>
                                        </label>
                                    <? endforeach ?>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        </div>

        <div class="smart-filter_bottons">
            <button type="submit" id="set_filter" name="set_filter" value="Y" class="btn mk-filter-apply">
                Показать<span class="mk-filter-apply__count" id="mk_filter_count" aria-live="polite"></span>
            </button>
            <? if ($mkChecked): ?>
                <button type="submit" id="del_filter" name="del_filter" value="Y" class="mk-filter-reset">Сбросить фильтры</button>
            <? else: ?>
                <button type="submit" id="del_filter" name="del_filter" value="Y" class="mk-filter-reset" hidden>Сбросить фильтры</button>
            <? endif ?>
            <div class="smart-filter-popup-result" id="modef" aria-hidden="true">
                <span id="modef_num"><?= intval($arResult["ELEMENT_COUNT"]) ?></span>
                <a href="<?= $arResult["FILTER_URL"] ?>">Показать</a>
            </div>
        </div>
    </form>
</div>
<div class="mobile_filter_panel_over"></div>

<script>
    var smartFilter = new JCSmartFilter('<?= CUtil::JSEscape($arResult["FORM_ACTION"]) ?>', '<?= CUtil::JSEscape($arParams["FILTER_VIEW_MODE"]) ?>', <?= CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"]) ?>);

    (function () {
        var plural = function (n) {
            var a = n % 10, b = n % 100;
            if (a === 1 && b !== 11) return 'товар';
            if (a >= 2 && a <= 4 && (b < 10 || b >= 20)) return 'товара';
            return 'товаров';
        };
        var setCount = function (n) {
            var el = document.getElementById('mk_filter_count');
            if (el && n !== null && n !== undefined && n !== '') el.textContent = ' ' + n + ' ' + plural(parseInt(n, 10) || 0);
            var reset = document.getElementById('del_filter');
            if (reset) reset.hidden = false;
        };
        <? if ($mkCount !== null): ?>setCount(<?= $mkCount ?>);<? endif ?>

        // Expand/collapse with a CSS transition instead of the fixed-height JS animation
        JCSmartFilter.prototype.hideFilterProps = function (el) {
            var box = el.closest('.smart-filter-parameters-box');
            var open = !box.classList.contains('bx-active');
            box.classList.toggle('bx-active', open);
            el.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        // Found-items count goes onto the apply button
        var post = JCSmartFilter.prototype.postHandler;
        JCSmartFilter.prototype.postHandler = function (result, fromCache) {
            post.call(this, result, fromCache);
            if (result && result.ELEMENT_COUNT !== undefined) setCount(result.ELEMENT_COUNT);
        };

        // Quick search inside long value lists
        document.querySelectorAll('.mk-filter-search').forEach(function (input) {
            input.addEventListener('input', function () {
                var q = input.value.trim().toLowerCase();
                input.nextElementSibling.querySelectorAll('.mk-check').forEach(function (label) {
                    label.hidden = q !== '' && label.textContent.toLowerCase().indexOf(q) === -1;
                });
            });
        });

        // Mobile: filter opens as a sheet
        var openBtn = document.querySelector('.mk-filter-open');
        var panel = document.getElementById('mk-filter-panel');
        var over = document.querySelector('.mobile_filter_panel_over');
        var toggle = function (open) {
            panel.classList.toggle('mobile_filter_panel_show', open);
            over.classList.toggle('mobile_filter_panel_over_show', open);
            document.body.classList.toggle('mk-lock', open);
            openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        };
        if (openBtn) openBtn.addEventListener('click', function () { toggle(true); });
        over.addEventListener('click', function () { toggle(false); });
        document.querySelector('.mk-filter-close').addEventListener('click', function () { toggle(false); });
    })();
</script>
