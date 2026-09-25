<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);

$P = $arResult['PROPERTIES'];
$val = function ($code) use ($P) {
    return isset($P[$code]['VALUE']) && $P[$code]['VALUE'] !== '' && $P[$code]['VALUE'] !== false ? $P[$code]['VALUE'] : '';
};
$html = function ($code) use ($P) {
    return !empty($P[$code]['~VALUE']['TEXT']) ? $P[$code]['~VALUE']['TEXT'] : '';
};
$imgSrc = !empty($arResult["IMAGE"]["src"]) ? $arResult["IMAGE"]["src"] : (!empty($arResult["DETAIL_PICTURE"]["SRC"]) ? $arResult["DETAIL_PICTURE"]["SRC"] : '');
$isEvent = $val('SPEAKER') || $val('TYPE_OF_EVENT') || $html('THEORETICAL_PART');
$sections = array();
foreach (array('THEORETICAL_PART', 'DEMO_PART', 'PRACTICAL_PART') as $code) {
    if ($html($code)) {
        $sections[] = array($P[$code]['NAME'], $html($code));
    }
}
$costs = array();
foreach (array('CONFERENCE_COST', 'COURSE_COST', 'COST_LECTURE') as $code) {
    if ($val($code)) {
        $costs[] = array($P[$code]['NAME'], $val($code));
    }
}
$phone = $val('TELEPHONE');
$email = $val('EMAIL');
?>
<article class="bx-newsdetail mk-article<?= $isEvent ? ' mk-article--event' : '' ?>" id="<?= $this->GetEditAreaId($arResult['ID']) ?>">
    <header class="mk-article__hero">
        <? if ($imgSrc): ?>
            <div class="mk-article__media">
                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialcharsbx($arResult["NAME"]) ?>">
            </div>
        <? endif ?>
        <div class="mk-article__intro">
            <div class="mk-article__chips">
                <? if ($arResult["ACTIVE_FROM"]): ?><span class="mk-chip mk-chip--accent"><?= $arResult["ACTIVE_FROM"] ?></span><? endif ?>
                <? if ($val('CITY')): ?><span class="mk-chip"><?= $val('CITY') ?></span><? endif ?>
                <? if ($val('TYPE_OF_EVENT')): ?><span class="mk-chip"><?= $val('TYPE_OF_EVENT') ?></span><? endif ?>
            </div>
            <? if ($val('SPEAKER')): ?>
                <p class="mk-article__speaker-label"><?= $P['SPEAKER']['NAME'] ?: 'Лектор' ?></p>
                <h2 class="mk-article__speaker"><?= $val('SPEAKER') ?></h2>
            <? endif ?>
            <? if ($html('BIOGRAPHY')): ?>
                <div class="mk-article__bio mk-prose"><?= $html('BIOGRAPHY') ?></div>
            <? endif ?>
            <? if ($costs || $phone): ?>
                <div class="mk-article__cta">
                    <? foreach ($costs as $cost): ?>
                        <div class="mk-article__price"><span><?= $cost[0] ?></span><strong><?= $cost[1] ?> ₽</strong></div>
                    <? endforeach ?>
                    <? if ($phone): ?>
                        <a class="mk-pill mk-pill--solid" href="tel:<?= preg_replace('/[^\d+]/', '', $phone) ?>">Записаться по телефону</a>
                    <? endif ?>
                </div>
            <? endif ?>
        </div>
    </header>

    <? if ($arResult['DETAIL_TEXT']): ?>
        <section class="mk-article__section mk-prose"><?= $arResult['DETAIL_TEXT'] ?></section>
    <? endif ?>

    <? if ($sections): ?>
        <div class="mk-article__program">
            <? foreach ($sections as $i => $section): ?>
                <section class="mk-article__part">
                    <span class="mk-article__num" aria-hidden="true"><?= $i + 1 ?></span>
                    <h3><?= $section[0] ?></h3>
                    <div class="mk-prose"><?= $section[1] ?></div>
                </section>
            <? endforeach ?>
        </div>
    <? endif ?>

    <? if ($phone || $email): ?>
        <aside class="mk-article__contact">
            <div>
                <h3>Запись и вопросы по участию</h3>
                <p>Позвоните или напишите — ответим на вопросы и запишем на мероприятие.</p>
            </div>
            <div class="mk-article__contact-links">
                <? if ($phone): ?>
                    <a class="mk-contact-link" href="tel:<?= preg_replace('/[^\d+]/', '', $phone) ?>">
                        <span class="mk-contact-link__k">Телефон<?= $val('NAME') ? ' · ' . $val('NAME') : '' ?></span>
                        <span class="mk-contact-link__v"><?= $phone ?></span>
                    </a>
                <? endif ?>
                <? if ($email): ?>
                    <a class="mk-contact-link" href="mailto:<?= htmlspecialcharsbx(trim($email)) ?>">
                        <span class="mk-contact-link__k">Почта</span>
                        <span class="mk-contact-link__v"><?= trim($email) ?></span>
                    </a>
                <? endif ?>
            </div>
        </aside>
    <? endif ?>
</article>
