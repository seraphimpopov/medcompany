<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Привязываем режим фрейма для AJAX
$this->setFrameMode(true);

// === PRELOAD и Inline критического CSS для быстрого рендера первого слайда ===
if (!empty($arResult["ITEMS"])) {
    $firstItem = reset($arResult["ITEMS"]);
    $firstResized = CFile::ResizeImageGet(
        $firstItem["PREVIEW_PICTURE"],
        ['width' => 1200, 'height' => 600],
        BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        false,
        ["webp", "quality" => 70]
    );
    // Preload LCP‑изображения
    $APPLICATION->AddHeadString(
        '<link rel="preload" as="image" href="' . htmlspecialchars($firstResized['src']) . '" ' .
        'imagesrcset="' . htmlspecialchars($firstResized['src']) . ' 1200w" imagesizes="1200px">'
    );
    // Inline критического CSS (показываем только первый слайд)
    $criticalCss = <<<CSS
<style>
  .slider_area {position:relative; overflow:hidden; width:100%; min-height:162px; max-height:503px;}
  .slider_area__item {position:absolute; top:0; left:0; width:100%; opacity:0; transition:opacity .3s ease;}
  .slider_area__item:first-child {position:relative; opacity:1;}
  .slider_area__img {width:100%; height:auto; display:block;}
</style>
CSS;
    $APPLICATION->AddHeadString($criticalCss);
}

?>

<?php $isFirst = true; ?>

<div class="slider_area">
  <?php foreach ($arResult["ITEMS"] as $arItem): ?>
    <?php
      // Включаем режим правки/удаления в админке
      $this->AddEditAction(
          $arItem['ID'],
          $arItem['EDIT_LINK'],
          CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT")
      );
      $this->AddDeleteAction(
          $arItem['ID'],
          $arItem['DELETE_LINK'],
          CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
          ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]
      );
      $arProps = $arItem['PROPERTIES'];

      // Генерируем основную WebP‑миниатюру под слайдер
      $resized = CFile::ResizeImageGet(
          $arItem["PREVIEW_PICTURE"],
          ['width'=>1200, 'height'=>600],
          BX_RESIZE_IMAGE_PROPORTIONAL,
          true,
          false,
          ["webp", "quality"=>70]
      );

      // Формируем srcset для адаптивных устройств
      $breakpoints = [
          ['width'=>480,'height'=>240],
          ['width'=>800,'height'=>400],
          ['width'=>1200,'height'=>600],
      ];
      $srcset = [];
      foreach ($breakpoints as $bp) {
          $img = CFile::ResizeImageGet(
              $arItem["PREVIEW_PICTURE"],
              $bp,
              BX_RESIZE_IMAGE_PROPORTIONAL,
              true,
              false,
              ["webp","quality"=>80]
          );
          $srcset[] = htmlspecialchars($img['src']) . " {$bp['width']}w";
      }
    ?>
    <div class="slider_area__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
      <?php if ($arParams["DISPLAY_PICTURE"] !== "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
        <a href="<?= htmlspecialchars($arProps["LINK"]["VALUE"]) ?>">
          <picture>
            <source
              type="image/webp"
              <?= $isFirst
                  ? 'srcset="' . implode(', ', $srcset) . '" sizes="(max-width:600px) 480px, (max-width:960px) 800px, 1200px"'
                  : 'data-srcset="' . implode(', ', $srcset) . '" data-sizes="(max-width:600px) 480px, (max-width:960px) 800px, 1200px"'
              ?>
            >
            <img
              class="slider_area__img"
              <?= $isFirst
                  ? 'src="' . htmlspecialchars($resized['src']) . '" loading="eager"'
                  : 'data-lazy="' . htmlspecialchars($resized['src']) . '" loading="lazy"'
              ?>
              alt="<?= htmlspecialchars($arItem["PREVIEW_PICTURE"]["ALT"]) ?>"
              title="<?= htmlspecialchars($arItem["PREVIEW_PICTURE"]["TITLE"]) ?>"
            />
          </picture>
        </a>
        <?php $isFirst = false; ?>
      <?php endif; ?>

      <?php if (!empty($arProps['DESCRIPTION']['VALUE'])): ?>
        <div class="header__text">
          <span class="header__text-middle">
            <?= htmlspecialchars($arProps['DESCRIPTION']['VALUE']['TEXT']) ?>
          </span>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<script defer type="text/javascript">
document.addEventListener('DOMContentLoaded', function(){
  $('.slider_area')
    .on('init', function(){ $(this).addClass('slick-initialized'); })
    .slick({
      arrows: false,
      dots: true,
      autoplay: true,
      autoplaySpeed: 3000,
      infinite: true,
      speed: 500,
      fade: true,
      cssEase: 'linear',
      adaptiveHeight: false,
      lazyLoad: 'ondemand'
    });
});
</script>




