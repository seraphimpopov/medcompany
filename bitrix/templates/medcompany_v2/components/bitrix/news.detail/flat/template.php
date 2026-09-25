<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$this->addExternalCss($this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css');
CUtil::InitJSCore(array('fx'));

use Bitrix\Main\Context;

$server = Context::getCurrent()->getServer();
$request = Context::getCurrent()->getRequest();

$curPage = $request->getRequestedPageDirectory();
?>

<div class="bx-newsdetail">
    <div class="bx-newsdetail-block" id="<? echo $this->GetEditAreaId($arResult['ID']) ?>">
        <div class="newsdetail-date"><? echo $arResult["ACTIVE_FROM"] ?>
            | <? echo $arResult['PROPERTIES']['CITY']['VALUE'] ?>
            | <? echo $arResult['PROPERTIES']['TYPE_OF_EVENT']['VALUE'] ?>
            "<? echo $arResult["NAME"] ?>"
        </div>
        <div class="block-biography row" style="margin-bottom: 20px">
			<div class="bx-newsdetail-img <?= preg_match('#/news.*#', $curPage) ? 'col-xl-8 bx-newsdetail-img-1' : 'col-xl-3 col-md-4'?>">
                <img
				src="<?= $arResult["IMAGE"]["src"] ?: $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                        width="<?= $arResult["PREVIEW_PICTURE"]["WIDTH"] ?>"
                        height="<?= $arResult["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                        alt="<?= $arResult["PREVIEW_PICTURE"]["ALT"] ?>"
                        title="<?= $arResult["PREVIEW_PICTURE"]["TITLE"] ?>"
                />
            </div>
            <? if ($arResult['PROPERTIES']['BIOGRAPHY']['VALUE'] != ""): ?>
                <div class="col-xl-5 col-md-8 block">
                    <div>
                        <div class="title">
                            <?= $arResult['PROPERTIES']['SPEAKER']['VALUE'] ?>
                        </div>
                        <div class="text">
                            <?= $arResult['PROPERTIES']['BIOGRAPHY']['~VALUE']['TEXT'] ?>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </div>
		<? if ($arResult['DETAIL_TEXT']) {?>
<div class="block-biography row" style="margin-bottom: 20px">
             <div class="block col-xl-8">
				 <?= $arResult['DETAIL_TEXT']?>
</div>
            </div>
		<? } ?>
        <? if ($arResult['PROPERTIES']['THEORETICAL_PART']['VALUE'] != "") { ?>
            <div class="block-biography row" style="margin-bottom: 20px">
                <div class="block col-xl-8">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['THEORETICAL_PART']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['THEORETICAL_PART']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
        <? } ?>
        <? if ($arResult['PROPERTIES']['DEMO_PART']['VALUE'] != "") { ?>
            <div class="block-biography row" style="margin-bottom: 20px">
                <div class="block col-xl-8">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['DEMO_PART']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['DEMO_PART']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
        <? } ?>
        <? if ($arResult['PROPERTIES']['PRACTICAL_PART']['VALUE'] != "") { ?>
            <div class="block-biography row" style="margin-bottom: 20px">
                <div class="block col-xl-8">
                    <div class="title">
                        <?= $arResult['PROPERTIES']['PRACTICAL_PART']['NAME'] ?>
                    </div>
                    <div class="text">
                        <?= $arResult['PROPERTIES']['PRACTICAL_PART']['~VALUE']['TEXT'] ?>
                    </div>
                </div>
            </div>
        <? } ?>
		<? if (!empty($arResult['PROPERTIES'])) {?>
        <div class="row block-biography">
            <div class="block col-xl-8">
                <div class="text">
                    По вопросам участия обращаться по телефону <br> <span style="font-weight: bold"> <a
                                style="color: #333"
                                href="tel: +79615022374"> <?= $arResult['PROPERTIES']['TELEPHONE']['VALUE'] ?></a> <?= $arResult['PROPERTIES']['NAME']['VALUE'] ?> </span>
                    <br> электронная почта <br> <a style="color: #333; font-weight: bold"
                                                   href="mailto: manager1@mail.ru"> <?= $arResult['PROPERTIES']['EMAIL']['VALUE'] ?> </a>
                </div>
                <? if ($arResult['PROPERTIES']['CONFERENCE_COST']['VALUE'] != ""): ?>
                    <div class="title" style="margin-bottom: 0; margin-top: 10px">
                        <?= $arResult['PROPERTIES']['CONFERENCE_COST']['NAME'] . ' - ' . $arResult['PROPERTIES']['CONFERENCE_COST']['VALUE'] . '₽' ?>
                    </div>
                <? endif; ?>
                <? if ($arResult['PROPERTIES']['COURSE_COST']['VALUE'] != ""): ?>
                    <div class="title" style="margin-bottom: 0; margin-top: 10px">
                        <?= $arResult['PROPERTIES']['COURSE_COST']['NAME'] . ' - ' . $arResult['PROPERTIES']['COURSE_COST']['VALUE'] . '₽' ?>
                    </div>
                <? endif; ?>
                <? if ($arResult['PROPERTIES']['COST_LECTURE']['VALUE'] != ""): ?>
                    <div class="title" style="margin-bottom: 0; margin-top: 10px">
                        <?= $arResult['PROPERTIES']['COST_LECTURE']['NAME'] . ' - ' . $arResult['PROPERTIES']['COST_LECTURE']['VALUE'] . '₽' ?>
                    </div>
                <? endif; ?>
            </div>
        </div>
		<? } ?>
    </div>
</div>
<script type="text/javascript">
    BX.ready(function () {
        var slider = new JCNewsSlider('<?=CUtil::JSEscape($this->GetEditAreaId($arResult['ID']));?>', {
            imagesContainerClassName: 'bx-newsdetail-slider-container',
            leftArrowClassName: 'bx-newsdetail-slider-arrow-container-left',
            rightArrowClassName: 'bx-newsdetail-slider-arrow-container-right',
            controlContainerClassName: 'bx-newsdetail-slider-control'
        });
    });
</script>
