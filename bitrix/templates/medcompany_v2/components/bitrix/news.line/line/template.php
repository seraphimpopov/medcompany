<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
	<div class="slider_area">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <div class="slider_area__item">
            <a href="<? echo $arItem['LINK']; ?>">
                <img class="slider_area__img" src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                     alt="<?= $arItem["NAME"] ?>"/>
            </a>
            <? if (!empty($arItem['PREVIEW_TEXT'])): ?>
                <div class="header__text">
                    <span class="header__text-middle">
                        <? echo $arItem['PREVIEW_TEXT']; ?>
                    </span>
                </div>
            <? endif; ?>
        </div>
    <? endforeach; ?>
	</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.slider_area').slick({
            dots: true,
            autoplay: true,
            autoplaySpeed: 3000,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });
    });
</script>