<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}

/** @global CMain $APPLICATION */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $templateFolder */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load(['clipboard', 'fx']);

if ($arParams['GUEST_MODE'] !== 'Y')
{
	Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
	Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
}

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach ($arResult['ERRORS']['FATAL'] as $error)
	{
		ShowError($error);
	}
	$component = $this->__component;
	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		$APPLICATION->AuthForm('', false, false, 'N', false);
	}
	return;
}

if (!empty($arResult['ERRORS']['NONFATAL']))
{
	foreach ($arResult['ERRORS']['NONFATAL'] as $error)
	{
		ShowError($error);
	}
}

$mkGoods = function ($n) {
	$n = (int)$n;
	$mod10 = $n % 10;
	$mod100 = $n % 100;
	if ($mod10 === 1 && $mod100 !== 11)
		return $n.' товар';
	if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14))
		return $n.' товара';
	return $n.' товаров';
};
$mkEsc = function ($v) { return htmlspecialcharsbx((string)$v); };
$isGuest = $arParams['GUEST_MODE'] === 'Y';
$isCanceled = $arResult['CANCELED'] === 'Y';
$statusName = $isCanceled ? Loc::getMessage('SPOD_ORDER_CANCELED') : $arResult['STATUS']['NAME'];
$statusId = isset($arResult['STATUS']['ID']) ? $arResult['STATUS']['ID'] : ($arResult['STATUS_ID'] ?? '');
$statusTone = $isCanceled ? 'alert' : ($statusId === 'F' ? 'ok' : 'wait');
$buyerName = $arResult['USER_NAME'] <> '' ? $arResult['USER_NAME'] : ($arResult['FIO'] <> '' ? $arResult['FIO'] : $arResult['USER']['LOGIN']);
$noPhoto = $this->GetFolder().'/images/no_photo.png';

$APPLICATION->SetTitle('Заказ №'.$arResult['ACCOUNT_NUMBER'].' от '.$arResult['DATE_INSERT_FORMATED']);

// buyer details (hidden until "Данные покупателя" is opened)
$details = array();
if ($arResult['USER']['LOGIN'] <> '' && !in_array('LOGIN', (array)$arParams['HIDE_USER_INFO']))
	$details[] = array(Loc::getMessage('SPOD_LOGIN'), $mkEsc($arResult['USER']['LOGIN']));
if ($arResult['USER']['EMAIL'] <> '' && !in_array('EMAIL', (array)$arParams['HIDE_USER_INFO']))
	$details[] = array('E-mail', '<a href="mailto:'.$mkEsc($arResult['USER']['EMAIL']).'">'.$mkEsc($arResult['USER']['EMAIL']).'</a>');
if ($arResult['USER']['PERSON_TYPE_NAME'] <> '' && !in_array('PERSON_TYPE_NAME', (array)$arParams['HIDE_USER_INFO']))
	$details[] = array(Loc::getMessage('SPOD_PERSON_TYPE_NAME'), $mkEsc($arResult['USER']['PERSON_TYPE_NAME']));
if (!empty($arResult['ORDER_PROPS']))
{
	foreach ($arResult['ORDER_PROPS'] as $property)
	{
		if ($property['TYPE'] == 'Y/N')
			$value = Loc::getMessage('SPOD_'.($property['VALUE'] == 'Y' ? 'YES' : 'NO'));
		elseif ($property['MULTIPLE'] == 'Y' && $property['TYPE'] !== 'FILE' && $property['TYPE'] !== 'LOCATION')
			$value = implode('<br>', array_map($mkEsc, (array)unserialize($property['VALUE'], ['allowed_classes' => false])));
		elseif ($property['TYPE'] == 'FILE')
			$value = $property['VALUE'];
		else
			$value = $mkEsc($property['VALUE']);
		if ($value !== '' && $value !== null)
			$details[] = array($mkEsc($property['NAME']), $value);
	}
}
?>
<div class="mk-od">
	<?php if (!$isGuest): ?>
		<a class="mk-od__back" href="<?= $mkEsc($arResult['URL_TO_LIST']) ?>"><span aria-hidden="true">←</span> К списку заказов</a>
	<?php endif ?>

	<!-- summary -->
	<section class="mk-od-card mk-od-summary" aria-label="Информация о заказе">
		<div class="mk-od-summary__main">
			<div class="mk-od-summary__line">
				<span class="mk-badge mk-badge--<?= $statusTone ?>"><?= $mkEsc($statusName) ?></span>
				<span class="mk-od-summary__since">с <?= $arResult['DATE_STATUS_FORMATED'] ?></span>
			</div>
			<dl class="mk-od-facts mk-od-facts--row">
				<div><dt>Покупатель</dt><dd><?= $mkEsc($buyerName) ?></dd></div>
				<div><dt>Дата заказа</dt><dd><?= $arResult['DATE_INSERT_FORMATED'] ?></dd></div>
				<div><dt>Состав</dt><dd><?= $mkGoods(count($arResult['BASKET'])) ?></dd></div>
				<?php if (!empty($arResult['SUM_REST']) && !empty($arResult['SUM_PAID'])): ?>
					<div><dt><?= Loc::getMessage('SPOD_ORDER_SUM_PAID') ?></dt><dd><?= $arResult['SUM_PAID_FORMATED'] ?></dd></div>
					<div><dt><?= Loc::getMessage('SPOD_ORDER_SUM_REST') ?></dt><dd><?= $arResult['SUM_REST_FORMATED'] ?></dd></div>
				<?php endif ?>
			</dl>
		</div>
		<div class="mk-od-summary__side">
			<div class="mk-od-summary__sum-label">Сумма заказа</div>
			<div class="mk-od-summary__sum"><?= $arResult['PRICE_FORMATED'] ?></div>
			<?php if (!$isGuest): ?>
				<div class="mk-od-summary__actions">
					<a class="mk-pill mk-pill--solid" href="<?= $arResult['URL_TO_COPY'] ?>"><?= Loc::getMessage('SPOD_ORDER_REPEAT') ?></a>
					<?php if ($arResult['CAN_CANCEL'] === 'Y'): ?>
						<a class="mk-od__cancel" href="<?= $arResult['URL_TO_CANCEL'] ?>">Отменить заказ</a>
					<?php endif ?>
				</div>
			<?php endif ?>
		</div>
		<?php if ($details || $arResult['USER_DESCRIPTION'] <> ''): ?>
			<div class="mk-od-summary__more">
				<button type="button" class="mk-od__toggle" data-mk-toggle aria-expanded="false" aria-controls="mk-od-buyer">Данные покупателя</button>
				<div class="mk-od-buyer" id="mk-od-buyer" hidden>
					<dl class="mk-od-facts mk-od-facts--grid">
						<?php foreach ($details as $row): ?>
							<div><dt><?= $row[0] ?></dt><dd><?= $row[1] ?></dd></div>
						<?php endforeach ?>
						<?php if ($arResult['USER_DESCRIPTION'] <> ''): ?>
							<div class="mk-od-facts__wide"><dt>Комментарий к заказу</dt><dd><?= nl2br($mkEsc($arResult['USER_DESCRIPTION'])) ?></dd></div>
						<?php endif ?>
					</dl>
				</div>
			</div>
		<?php endif ?>
	</section>

	<!-- payment -->
	<section class="mk-od-card" aria-labelledby="mk-od-pay-title">
		<h2 class="mk-od-card__title" id="mk-od-pay-title">Оплата</h2>
		<?php
		$paymentData = array();
		foreach ($arResult['PAYMENT'] as $payment)
		{
			$paymentData[$payment['ACCOUNT_NUMBER']] = array(
				"payment" => $payment['ACCOUNT_NUMBER'],
				"order" => $arResult['ACCOUNT_NUMBER'],
				"allow_inner" => $arParams['ALLOW_INNER'],
				"only_inner_full" => $arParams['ONLY_INNER_FULL'],
				"refresh_prices" => $arParams['REFRESH_PRICES'],
				"path_to_payment" => $arParams['PATH_TO_PAYMENT']
			);
			$logo = $payment['PAY_SYSTEM']['SRC_LOGOTIP'] <> '' ? $payment['PAY_SYSTEM']['SRC_LOGOTIP'] : '/bitrix/images/sale/nopaysystem.gif';
			$isPaid = $payment['PAID'] === 'Y';
			$isCash = $payment['PAY_SYSTEM']['IS_CASH'] === 'Y' || $payment['PAY_SYSTEM']['ACTION_FILE'] === 'cash';
			$canChange = !$isPaid && !$isCanceled && !$isGuest && $arResult['LOCK_CHANGE_PAYSYSTEM'] !== 'Y';
			$canPayInline = !$isPaid && !$isCash && $payment['PAY_SYSTEM']['PSA_NEW_WINDOW'] !== 'Y' && !$isCanceled && $arResult['IS_ALLOW_PAY'] !== 'N';
			?>
			<div class="mk-od-row mk-od-pay">
				<div class="mk-od-row__logo"><span style="background-image: url('<?= $mkEsc($logo) ?>')"></span></div>
				<div class="mk-od-row__body">
					<div class="mk-od-row__head">
						<strong>Счёт №<?= $mkEsc($payment['ACCOUNT_NUMBER']) ?></strong>
						<?php if ($isPaid): ?>
							<span class="mk-badge mk-badge--ok"><?= Loc::getMessage('SPOD_PAYMENT_PAID') ?></span>
						<?php elseif ($arResult['IS_ALLOW_PAY'] == 'N'): ?>
							<span class="mk-badge mk-badge--wait">На проверке у менеджера</span>
						<?php else: ?>
							<span class="mk-badge mk-badge--alert"><?= Loc::getMessage('SPOD_PAYMENT_UNPAID') ?></span>
						<?php endif ?>
					</div>
					<dl class="mk-od-facts">
						<div><dt>Способ</dt><dd><?= $payment['PAY_SYSTEM_NAME'] ?></dd></div>
						<div><dt>К оплате</dt><dd><?= $payment['PRICE_FORMATED'] ?></dd></div>
						<?php if (isset($payment['DATE_BILL'])): ?>
							<div><dt>Выставлен</dt><dd><?= $payment['DATE_BILL_FORMATED'] ?></dd></div>
						<?php endif ?>
					</dl>
					<?php
					if (!empty($payment['CHECK_DATA']))
					{
						$checks = array();
						foreach ($payment['CHECK_DATA'] as $checkInfo)
						{
							if ($checkInfo['LINK'] <> '')
								$checks[] = '<a href="'.$mkEsc($checkInfo['LINK']).'" target="_blank" rel="noopener">'.Loc::getMessage('SPOD_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID'])).' — '.$mkEsc($checkInfo['TYPE_NAME']).'</a>';
						}
						if ($checks)
						{
							?><div class="mk-od-row__checks"><span>Чеки:</span> <?= implode(' ', $checks) ?></div><?php
						}
					}
					if ($arResult['IS_ALLOW_PAY'] === 'N' && !$isPaid)
					{
						?><p class="mk-od-note">Оплата станет доступна после подтверждения заказа менеджером.</p><?php
					}
					if ($canChange || (!$isPaid && !$isCash))
					{
						?>
						<div class="mk-od-row__actions">
							<?php
							if (!$isPaid && !$isCash)
							{
								if ($payment['PAY_SYSTEM']['PSA_NEW_WINDOW'] === 'Y' && $arResult['IS_ALLOW_PAY'] !== 'N')
								{
									?><a class="mk-pill mk-pill--solid" target="_blank" rel="noopener" href="<?= $mkEsc($payment['PAY_SYSTEM']['PSA_ACTION_FILE']) ?>"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></a><?php
								}
								elseif ($canPayInline)
								{
									?><button type="button" class="mk-pill mk-pill--solid" data-mk-pay aria-expanded="false"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></button><?php
								}
							}
							if ($canChange)
							{
								?><button type="button" class="mk-od__link" data-mk-change-payment="<?= $mkEsc($payment['ACCOUNT_NUMBER']) ?>">Сменить способ оплаты</button><?php
							}
							?>
						</div>
						<?php
					}
					?>
					<div class="mk-od-pay__change" hidden>
						<div class="mk-od-pay__change-head">
							<span>Выберите новый способ оплаты</span>
							<button type="button" class="mk-od__link" data-mk-change-back>← Назад</button>
						</div>
						<div class="mk-od-pay__change-body"></div>
					</div>
					<?php if ($canPayInline): ?>
						<div class="mk-od-pay__form" hidden><?= $payment['BUFFERED_OUTPUT'] ?></div>
					<?php endif ?>
				</div>
			</div>
			<?php
		}
		?>
	</section>

	<!-- shipment -->
	<?php if (!empty($arResult['SHIPMENT'])): ?>
		<section class="mk-od-card" aria-labelledby="mk-od-ship-title">
			<h2 class="mk-od-card__title" id="mk-od-ship-title">Доставка</h2>
			<?php
			$shipmentCount = count($arResult['SHIPMENT']);
			foreach ($arResult['SHIPMENT'] as $shipment)
			{
				$store = null;
				if (isset($shipment['STORE_ID'], $arResult['DELIVERY']['STORE_LIST'][$shipment['STORE_ID']]))
				{
					$store = $arResult['DELIVERY']['STORE_LIST'][$shipment['STORE_ID']];
				}
				$deducted = $shipment['DEDUCTED'] === 'Y';
				?>
				<div class="mk-od-row mk-od-ship">
					<div class="mk-od-row__logo">
						<?php if ($shipment['DELIVERY']['SRC_LOGOTIP'] <> ''): ?>
							<span style="background-image: url('<?= $mkEsc($shipment['DELIVERY']['SRC_LOGOTIP']) ?>')"></span>
						<?php endif ?>
					</div>
					<div class="mk-od-row__body">
						<div class="mk-od-row__head">
							<strong>Отгрузка №<?= $mkEsc($shipment['ACCOUNT_NUMBER']) ?></strong>
							<span class="mk-badge mk-badge--<?= $deducted ? 'ok' : 'wait' ?>"><?= $mkEsc($shipment['STATUS_NAME']) ?></span>
						</div>
						<dl class="mk-od-facts">
							<?php if ($shipment['DELIVERY_NAME'] <> ''): ?>
								<div><dt>Способ</dt><dd><?= $mkEsc($shipment['DELIVERY_NAME']) ?></dd></div>
							<?php endif ?>
							<div><dt>Стоимость</dt><dd><?= $shipment['PRICE_DELIVERY_FORMATED'] <> '' ? $shipment['PRICE_DELIVERY_FORMATED'] : '0 ₽' ?></dd></div>
							<?php if ($shipment['DATE_DEDUCTED']): ?>
								<div><dt>Отгружено</dt><dd><?= $shipment['DATE_DEDUCTED_FORMATED'] ?></dd></div>
							<?php endif ?>
							<?php if ($shipment['TRACKING_NUMBER'] <> ''): ?>
								<div><dt>Трек-номер</dt><dd>
									<span class="mk-od__track"><?= $mkEsc($shipment['TRACKING_NUMBER']) ?></span>
									<button type="button" class="mk-od__copy" data-copy="<?= $mkEsc($shipment['TRACKING_NUMBER']) ?>" aria-label="Скопировать трек-номер"></button>
								</dd></div>
							<?php endif ?>
						</dl>
						<?php if ($shipment['TRACKING_URL'] <> ''): ?>
							<div class="mk-od-row__actions">
								<a class="mk-pill mk-pill--outline" target="_blank" rel="noopener" href="<?= $mkEsc($shipment['TRACKING_URL']) ?>">Отследить отправление</a>
							</div>
						<?php endif ?>

						<?php if ($store): ?>
							<div class="mk-od-store">
								<div class="mk-od-store__info">
									<div class="mk-od-store__label">Пункт самовывоза</div>
									<?php if ($store['TITLE'] <> ''): ?><div class="mk-od-store__name"><?= $mkEsc($store['TITLE']) ?></div><?php endif ?>
									<dl class="mk-od-facts">
										<?php if ($store['ADDRESS'] <> ''): ?><div><dt>Адрес</dt><dd><?= $mkEsc($store['ADDRESS']) ?></dd></div><?php endif ?>
										<?php if (!empty($store['PHONE'])): ?><div><dt>Телефон</dt><dd><a href="tel:<?= preg_replace('/[^\d+]/', '', $store['PHONE']) ?>"><?= $mkEsc($store['PHONE']) ?></a></dd></div><?php endif ?>
										<?php if (!empty($store['SCHEDULE'])): ?><div><dt>Режим работы</dt><dd><?= $mkEsc($store['SCHEDULE']) ?></dd></div><?php endif ?>
									</dl>
								</div>
								<?php if ($store['GPS_N'] && $store['GPS_S']): ?>
									<div class="mk-od-store__map">
										<?php
										$APPLICATION->IncludeComponent(
											"bitrix:map.yandex.view",
											"",
											array(
												"INIT_MAP_TYPE" => "MAP",
												"MAP_DATA" => serialize(array(
													'yandex_lon' => $store['GPS_S'],
													'yandex_lat' => $store['GPS_N'],
													'yandex_scale' => 15,
													'PLACEMARKS' => array(array("LON" => $store['GPS_S'], "LAT" => $store['GPS_N'], "TEXT" => $mkEsc($store['TITLE']))),
												)),
												"MAP_WIDTH" => "100%",
												"MAP_HEIGHT" => "240",
												"CONTROLS" => array("SMALLZOOM"),
												"OPTIONS" => array("ENABLE_DRAGGING", "ENABLE_DBLCLICK_ZOOM"),
												"MAP_ID" => "mk_od_store_".(int)$shipment['ID'],
											),
											false,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
								<?php endif ?>
							</div>
						<?php endif ?>

						<?php if ($shipmentCount > 1 && !empty($shipment['ITEMS'])): ?>
							<div class="mk-od-ship__items">
								<div class="mk-od-ship__items-title">Состав отгрузки</div>
								<ul>
									<?php foreach ($shipment['ITEMS'] as $item):
										$basketItem = $arResult['BASKET'][$item['BASKET_ID']];
										?>
										<li><span><?= $mkEsc($basketItem['NAME']) ?></span><span><?= $item['QUANTITY'] ?>&nbsp;<?= $mkEsc($item['MEASURE_NAME']) ?></span></li>
									<?php endforeach ?>
								</ul>
							</div>
						<?php endif ?>
					</div>
				</div>
				<?php
			}
			?>
		</section>
	<?php endif ?>

	<!-- items + totals -->
	<section class="mk-od-card" aria-labelledby="mk-od-items-title">
		<h2 class="mk-od-card__title" id="mk-od-items-title">Состав заказа</h2>
		<div class="mk-od-items" role="table" aria-label="Товары в заказе">
			<div class="mk-od-items__row mk-od-items__row--head" role="row">
				<span role="columnheader">Товар</span>
				<span role="columnheader">Цена</span>
				<span role="columnheader">Кол-во</span>
				<span role="columnheader">Сумма</span>
			</div>
			<?php foreach ($arResult['BASKET'] as $basketItem):
				$imageSrc = is_array($basketItem['PICTURE']) ? $basketItem['PICTURE']['SRC'] : $noPhoto;
				$measure = $basketItem['MEASURE_NAME'] <> '' ? $basketItem['MEASURE_NAME'] : Loc::getMessage('SPOD_DEFAULT_MEASURE');
				?>
				<div class="mk-od-items__row" role="row">
					<div class="mk-od-item" role="cell">
						<a class="mk-od-item__img" href="<?= $mkEsc($basketItem['DETAIL_PAGE_URL']) ?>" tabindex="-1" aria-hidden="true">
							<img src="<?= $mkEsc($imageSrc) ?>" alt="" loading="lazy">
						</a>
						<div class="mk-od-item__body">
							<a class="mk-od-item__name" href="<?= $mkEsc($basketItem['DETAIL_PAGE_URL']) ?>"><?= $mkEsc($basketItem['NAME']) ?></a>
							<?php if (!empty($basketItem['PROPS']) && is_array($basketItem['PROPS'])): ?>
								<div class="mk-od-item__props">
									<?php foreach ($basketItem['PROPS'] as $itemProps): ?>
										<span><?= $mkEsc($itemProps['NAME']) ?>: <?= $mkEsc($itemProps['VALUE']) ?></span>
									<?php endforeach ?>
								</div>
							<?php endif ?>
							<?php if ($basketItem['DISCOUNT_PRICE_PERCENT_FORMATED'] <> ''): ?>
								<span class="mk-badge mk-badge--ok">Скидка <?= $basketItem['DISCOUNT_PRICE_PERCENT_FORMATED'] ?></span>
							<?php endif ?>
						</div>
					</div>
					<div class="mk-od-items__cell" role="cell" data-label="Цена"><?= $basketItem['BASE_PRICE_FORMATED'] ?></div>
					<div class="mk-od-items__cell" role="cell" data-label="Кол-во"><?= $basketItem['QUANTITY'] ?>&nbsp;<?= $mkEsc($measure) ?></div>
					<div class="mk-od-items__cell mk-od-items__cell--sum" role="cell" data-label="Сумма"><?= $basketItem['FORMATED_SUM'] ?></div>
				</div>
			<?php endforeach ?>
		</div>
		<dl class="mk-od-total">
			<?php if ($arResult['PRODUCT_SUM_FORMATED'] != $arResult['PRICE_FORMATED'] && !empty($arResult['PRODUCT_SUM_FORMATED'])): ?>
				<div><dt>Товары</dt><dd><?= $arResult['PRODUCT_SUM_FORMATED'] ?></dd></div>
			<?php endif ?>
			<?php if ($arResult['PRICE_DELIVERY_FORMATED'] <> ''): ?>
				<div><dt>Доставка</dt><dd><?= $arResult['PRICE_DELIVERY_FORMATED'] ?></dd></div>
			<?php endif ?>
			<?php if ((float)$arResult['TAX_VALUE'] > 0): ?>
				<div><dt><?= Loc::getMessage('SPOD_TAX') ?></dt><dd><?= $arResult['TAX_VALUE_FORMATED'] ?></dd></div>
			<?php endif ?>
			<?php if (floatval($arResult['ORDER_WEIGHT'])): ?>
				<div><dt><?= Loc::getMessage('SPOD_TOTAL_WEIGHT') ?></dt><dd><?= $arResult['ORDER_WEIGHT_FORMATED'] ?></dd></div>
			<?php endif ?>
			<div class="mk-od-total__sum"><dt>Итого</dt><dd><?= $arResult['PRICE_FORMATED'] ?></dd></div>
		</dl>
	</section>

	<?php if (!$isGuest): ?>
		<a class="mk-od__back mk-od__back--bottom" href="<?= $mkEsc($arResult['URL_TO_LIST']) ?>"><span aria-hidden="true">←</span> К списку заказов</a>
	<?php endif ?>
</div>
<?php
$javascriptParams = array(
	"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
	"templateFolder" => CUtil::JSEscape($templateFolder),
	"templateName" => $this->__component->GetTemplateName(),
	"paymentList" => $paymentData,
	"returnUrl" => $arResult['RETURN_URL'],
);
?>
<script>
	BX.Sale.PersonalOrderComponent.PersonalOrderDetail.init(<?= CUtil::PhpToJSObject($javascriptParams) ?>);
</script>
