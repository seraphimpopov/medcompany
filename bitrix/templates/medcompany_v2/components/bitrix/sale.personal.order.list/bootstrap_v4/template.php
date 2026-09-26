<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}

/** @var CBitrixPersonalOrderListComponent $component */
/** @var array $arParams */
/** @var array $arResult */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/bootstrap_v4/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/bootstrap_v4/style.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

$mkGoods = function ($n) {
	$n = (int)$n;
	$mod10 = $n % 10;
	$mod100 = $n % 100;
	if ($mod10 === 1 && $mod100 !== 11)
		$word = Loc::getMessage('SPOL_TPL_GOOD');
	elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14))
		$word = Loc::getMessage('SPOL_TPL_TWO_GOODS');
	else
		$word = Loc::getMessage('SPOL_TPL_GOODS');
	return $n.' '.$word;
};

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach($arResult['ERRORS']['FATAL'] as $code => $error)
	{
		if ($code !== $component::E_NOT_AUTHORIZED)
			ShowError($error);
	}
	$component = $this->__component;
	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		?>
		<div class="mk-orders__auth">
			<div class="alert alert-danger"><?=$arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]?></div>
			<?$APPLICATION->AuthForm('', false, false, 'N', false);?>
		</div>
		<?
	}
}
else
{
	$filterHistory = ($_REQUEST['filter_history'] ?? '');
	$filterShowCanceled = ($_REQUEST["show_canceled"] ?? '');
	$clearFromLink = array("filter_history", "filter_status", "show_all", "show_canceled");

	if (!empty($arResult['ERRORS']['NONFATAL']))
	{
		foreach($arResult['ERRORS']['NONFATAL'] as $error)
		{
			ShowError($error);
		}
	}
	?>
	<div class="mk-orders">
	<?
	// history: switch between finished and canceled orders
	if ($filterHistory === 'Y')
	{
		?>
		<div class="mk-orders__switch" role="navigation" aria-label="Фильтр истории заказов">
			<a class="mk-orders__chip<?= $filterShowCanceled !== 'Y' ? ' is-active' : '' ?>" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">Выполненные</a>
			<a class="mk-orders__chip<?= $filterShowCanceled === 'Y' ? ' is-active' : '' ?>" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>">Отменённые</a>
		</div>
		<?
	}

	if (empty($arResult['ORDERS']))
	{
		if ($filterHistory === 'Y')
			$emptyText = $filterShowCanceled === 'Y' ? Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER') : Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST');
		else
			$emptyText = Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST');
		?>
		<div class="mk-orders__empty">
			<span class="mk-orders__empty-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" d="M4 7h16l-1.5 12.5a1.7 1.7 0 0 1-1.7 1.5H7.2a1.7 1.7 0 0 1-1.7-1.5z"/><path fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" d="M9 10V6a3 3 0 0 1 6 0v4"/></svg></span>
			<p class="mk-orders__empty-text"><?= $emptyText ?></p>
			<a class="mk-pill mk-pill--solid" href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>"><?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?></a>
		</div>
		<?
	}

	if ($filterHistory !== 'Y')
	{
		$paymentChangeData = array();
		$orderHeaderStatus = null;

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $arResult['SORT_TYPE'] == 'STATUS')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
				?>
				<h2 class="mk-orders__status">
					<span class="mk-orders__status-dot" aria-hidden="true"></span>
					<?= htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME']) ?>
				</h2>
				<?
			}
			?>
			<article class="mk-order">
				<header class="mk-order__head">
					<div>
						<h3 class="mk-order__title">
							<?=Loc::getMessage('SPOL_TPL_ORDER')?> <?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER'])?>
						</h3>
						<p class="mk-order__meta">
							<?=Loc::getMessage('SPOL_TPL_FROM_DATE')?> <?=$order['ORDER']['DATE_INSERT_FORMATED']?>
							<span aria-hidden="true">·</span>
							<?= $mkGoods(count($order['BASKET_ITEMS'])) ?>
						</p>
					</div>
					<div class="mk-order__sum"><?=$order['ORDER']['FORMATED_PRICE']?></div>
				</header>

				<div class="mk-order__grid">
					<section class="mk-order__block">
						<h4 class="mk-order__label"><?=Loc::getMessage('SPOL_TPL_PAYMENT')?></h4>
						<?
						foreach ($order['PAYMENT'] as $payment)
						{
							if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
							{
								$paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
									"order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
									"payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
									"allow_inner" => $arParams['ALLOW_INNER'],
									"refresh_prices" => $arParams['REFRESH_PRICES'],
									"path_to_payment" => $arParams['PATH_TO_PAYMENT'],
									"only_inner_full" => $arParams['ONLY_INNER_FULL'],
									"return_url" => $arResult['RETURN_URL'],
								);
							}
							?>
							<div class="sale-order-list-inner-row mk-order__row">
								<div class="sale-order-list-inner-row-body">
									<div class="mk-order__line">
										<strong><?=Loc::getMessage('SPOL_TPL_BILL')?> <?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($payment['ACCOUNT_NUMBER'])?></strong>
										<?
										if ($payment['PAID'] === 'Y')
										{
											?><span class="mk-badge mk-badge--ok"><?=Loc::getMessage('SPOL_TPL_PAID')?></span><?
										}
										elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
										{
											?><span class="mk-badge mk-badge--wait"><?=Loc::getMessage('SPOL_TPL_RESTRICTED_PAID')?></span><?
										}
										else
										{
											?><span class="mk-badge mk-badge--alert"><?=Loc::getMessage('SPOL_TPL_NOTPAID')?></span><?
										}
										?>
									</div>
									<dl class="mk-order__facts">
										<div><dt>Способ</dt><dd><?=$payment['PAY_SYSTEM_NAME']?></dd></div>
										<div><dt>К оплате</dt><dd><?=$payment['FORMATED_SUM']?></dd></div>
										<? if (isset($payment['DATE_BILL'])): ?>
											<div><dt>Выставлен</dt><dd><?=$payment['DATE_BILL_FORMATED']?></dd></div>
										<? endif ?>
									</dl>
									<?
									if (!empty($payment['CHECK_DATA']))
									{
										$listCheckLinks = "";
										foreach ($payment['CHECK_DATA'] as $checkInfo)
										{
											$title = Loc::getMessage('SPOL_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID']))." - ". htmlspecialcharsbx($checkInfo['TYPE_NAME']);
											if($checkInfo['LINK'] <> '')
											{
												$listCheckLinks .= "<a href='".$checkInfo['LINK']."' target='_blank'>".$title."</a>";
											}
										}
										if ($listCheckLinks <> '')
										{
											?>
											<div class="mk-order__checks"><span><?= Loc::getMessage('SPOL_CHECK_TITLE')?>:</span> <?=$listCheckLinks?></div>
											<?
										}
									}
									if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' && $payment['PAID'] !== 'Y')
									{
										?>
										<p class="mk-order__note"><?=Loc::getMessage('SOPL_TPL_RESTRICTED_PAID_MESSAGE')?></p>
										<?
									}
									$canPayOnline = $payment['PAID'] === 'N' && $payment['IS_CASH'] !== 'Y' && $payment['ACTION_FILE'] !== 'cash';
									$canChange = $payment['PAID'] !== 'Y' && $order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y';
									if ($canPayOnline || $canChange)
									{
										?>
										<div class="mk-order__row-actions">
											<?
											if ($canPayOnline)
											{
												if ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
												{
													?><a class="mk-pill mk-pill--solid is-disabled" aria-disabled="true"><?=Loc::getMessage('SPOL_TPL_PAY')?></a><?
												}
												elseif ($payment['NEW_WINDOW'] === 'Y')
												{
													?><a class="mk-pill mk-pill--solid" target="_blank" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>"><?=Loc::getMessage('SPOL_TPL_PAY')?></a><?
												}
												else
												{
													?><a class="mk-pill mk-pill--solid ajax_reload" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>"><?=Loc::getMessage('SPOL_TPL_PAY')?></a><?
												}
											}
											if ($canChange)
											{
												?><a href="#" class="sale-order-list-change-payment mk-order__link" id="<?= htmlspecialcharsbx($payment['ACCOUNT_NUMBER']) ?>">Сменить способ оплаты</a><?
											}
											?>
										</div>
										<?
									}
									?>
								</div>
								<div class="sale-order-list-inner-row-template">
									<a class="sale-order-list-cancel-payment mk-order__link" href="">← Назад</a>
								</div>
							</div>
							<?
						}
						?>
					</section>

					<? if (!empty($order['SHIPMENT'])): ?>
						<section class="mk-order__block">
							<h4 class="mk-order__label"><?=Loc::getMessage('SPOL_TPL_DELIVERY')?></h4>
							<?
							foreach ($order['SHIPMENT'] as $shipment)
							{
								if (empty($shipment))
								{
									continue;
								}
								?>
								<div class="mk-order__row">
									<div class="mk-order__line">
										<strong><?=Loc::getMessage('SPOL_TPL_LOAD')?> <?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($shipment['ACCOUNT_NUMBER'])?></strong>
										<? if ($shipment['DEDUCTED'] == 'Y'): ?>
											<span class="mk-badge mk-badge--ok"><?=Loc::getMessage('SPOL_TPL_LOADED')?></span>
										<? else: ?>
											<span class="mk-badge mk-badge--alert"><?=Loc::getMessage('SPOL_TPL_NOTLOADED')?></span>
										<? endif ?>
									</div>
									<dl class="mk-order__facts">
										<? if (!empty($shipment['DELIVERY_ID'])): ?>
											<div><dt>Способ</dt><dd><?=$arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME']?></dd></div>
										<? endif ?>
										<div><dt>Статус</dt><dd><?=htmlspecialcharsbx($shipment['DELIVERY_STATUS_NAME'])?></dd></div>
										<? if ($shipment['FORMATED_DELIVERY_PRICE']): ?>
											<div><dt>Стоимость</dt><dd><?=$shipment['FORMATED_DELIVERY_PRICE']?></dd></div>
										<? endif ?>
										<? if ($shipment['DATE_DEDUCTED']): ?>
											<div><dt>Отгружено</dt><dd><?=$shipment['DATE_DEDUCTED_FORMATED']?></dd></div>
										<? endif ?>
										<? if (!empty($shipment['TRACKING_NUMBER'])): ?>
											<div class="sale-order-list-inner-row">
												<dt><?=Loc::getMessage('SPOL_TPL_POSTID')?></dt>
												<dd><span class="sale-order-list-shipment-id"><?=htmlspecialcharsbx($shipment['TRACKING_NUMBER'])?></span><span class="sale-order-list-shipment-id-icon" title="Скопировать"></span></dd>
											</div>
										<? endif ?>
									</dl>
									<? if ($shipment['TRACKING_URL'] <> ''): ?>
										<div class="mk-order__row-actions">
											<a class="mk-order__link" target="_blank" href="<?=$shipment['TRACKING_URL']?>">Отследить отправление</a>
										</div>
									<? endif ?>
								</div>
								<?
							}
							?>
						</section>
					<? endif ?>
				</div>

				<footer class="mk-order__foot">
					<a class="mk-pill mk-pill--solid" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
					<a class="mk-pill mk-pill--outline" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
					<? if ($order['ORDER']['CAN_CANCEL'] !== 'N'): ?>
						<a class="mk-order__cancel" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"])?>"><?=Loc::getMessage('SPOL_TPL_CANCEL_ORDER')?></a>
					<? endif ?>
				</footer>
			</article>
			<?
		}
	}
	else
	{
		foreach ($arResult['ORDERS'] as $key => $order)
		{
			?>
			<article class="mk-order mk-order--compact">
				<header class="mk-order__head">
					<div>
						<h3 class="mk-order__title">
							<?= Loc::getMessage('SPOL_TPL_ORDER') ?> <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER'])?>
						</h3>
						<p class="mk-order__meta">
							<?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?> <?= $order['ORDER']['DATE_INSERT'] ?>
							<span aria-hidden="true">·</span>
							<?= $mkGoods(count($order['BASKET_ITEMS'])) ?>
						</p>
					</div>
					<div class="mk-order__sum"><?= $order['ORDER']['FORMATED_PRICE'] ?></div>
				</header>
				<footer class="mk-order__foot">
					<? if ($filterShowCanceled !== 'Y'): ?>
						<span class="mk-badge mk-badge--ok"><?= Loc::getMessage('SPOL_TPL_ORDER_FINISHED') ?> <?= $order['ORDER']['DATE_STATUS_FORMATED'] ?></span>
					<? else: ?>
						<span class="mk-badge mk-badge--alert"><?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED') ?> <?= $order['ORDER']['DATE_STATUS_FORMATED'] ?></span>
					<? endif ?>
					<span class="mk-order__spacer"></span>
					<a class="mk-pill mk-pill--outline" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
					<a class="mk-pill mk-pill--solid" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
				</footer>
			</article>
			<?
		}
	}
	?>
	</div>
	<?
	echo $arResult["NAV_STRING"];

	if ($filterHistory !== 'Y')
	{
		$javascriptParams = array(
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"templateName" => $this->__component->GetTemplateName(),
			"paymentList" => $paymentChangeData,
			"returnUrl" => CUtil::JSEscape($arResult["RETURN_URL"]),
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
		</script>
		<?
	}
}
