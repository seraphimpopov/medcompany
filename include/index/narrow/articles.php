<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var string $code
 * @var array $blocks
 * @var integer $banner
 * @var Closure $templateInclude($template)
 */

$display = ArrayHelper::getValue($blocks, ['active', $code], 'Y') === 'Y';

if ($display) { ?>
    <?= Html::beginTag('div', ['style' => [
        'margin-top' => '50px',
        'margin-bottom' => '50px'
    ]]) ?>
        <?php $APPLICATION->IncludeComponent(
            "intec.universe:widget",
            "articles",
            array(
                "IBLOCK_TYPE" => "content",
                "IBLOCK_ID" => "4",
                "ELEMENTS_ID" => [],
                "ELEMENTS_COUNT" => 3,
                "HEADER_SHOW" => "Y",
                "HEADER_CENTER" => "N",
                "HEADER" => "Статьи",
                "DESCRIPTION_SHOW" => "Y",
                "DESCRIPTION_CENTER" => "N",
                "DESCRIPTION" => "В нашем каталоге представлены последние линейки спецтехники, систем Закажите консультацию по любому товару у наших специалистов или соберите свой заказ прямо на сайте. Мы подготовим для вас индивидуальное коммерческое предложение и вышлем персональный блок бонусов и скидок.",
                "BIG_FIRST_BLOCK" => "Y",
                "HEADER_ELEMENT_SHOW" => "Y",
                "DESCRIPTION_ELEMENT_SHOW" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 0
            ),
            false
        ); ?>
    <?= Html::endTag('div') ?>
<?php }