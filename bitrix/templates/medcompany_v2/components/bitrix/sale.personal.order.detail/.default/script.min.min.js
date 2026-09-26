BX.namespace('BX.Sale.PersonalOrderComponent');

(function () {
	BX.Sale.PersonalOrderComponent.PersonalOrderDetail = {
		init: function (params) {
			params = params || {};
			params.paymentList = params.paymentList || {};

			// "Данные покупателя" and other disclosure buttons
			document.querySelectorAll('.mk-od [data-mk-toggle]').forEach(function (button) {
				button.addEventListener('click', function () {
					var target = document.getElementById(button.getAttribute('aria-controls'));
					var open = button.getAttribute('aria-expanded') !== 'true';
					button.setAttribute('aria-expanded', open ? 'true' : 'false');
					if (target) target.hidden = !open;
				});
			});

			// copy a tracking number
			document.querySelectorAll('.mk-od .mk-od__copy').forEach(function (button) {
				if (BX.clipboard) {
					BX.clipboard.bindCopyClick(button, { text: button.getAttribute('data-copy') });
				}
			});

			document.querySelectorAll('.mk-od .mk-od-pay').forEach(function (row) {
				var change = row.querySelector('.mk-od-pay__change');
				var changeBody = row.querySelector('.mk-od-pay__change-body');
				var form = row.querySelector('.mk-od-pay__form');

				// online payment form printed by the pay system
				row.querySelectorAll('[data-mk-pay]').forEach(function (button) {
					button.addEventListener('click', function () {
						if (!form) return;
						var open = form.hidden;
						form.hidden = !open;
						button.setAttribute('aria-expanded', open ? 'true' : 'false');
					});
				});

				// change the payment system: the options come from sale.order.payment.change
				row.querySelectorAll('[data-mk-change-payment]').forEach(function (button) {
					button.addEventListener('click', function (event) {
						event.preventDefault();
						if (!change || button.disabled) return;
						button.disabled = true;
						row.classList.add('is-loading');
						BX.ajax({
							method: 'POST',
							dataType: 'html',
							url: params.url,
							data: {
								sessid: BX.bitrix_sessid(),
								orderData: params.paymentList[button.getAttribute('data-mk-change-payment')],
								templateName: params.templateName,
								returnUrl: params.returnUrl
							},
							onsuccess: function (html) {
								row.classList.remove('is-loading');
								changeBody.innerHTML = html;
								change.hidden = false;
								row.classList.add('is-changing');
							},
							onfailure: function () {
								row.classList.remove('is-loading');
								button.disabled = false;
							}
						});
					});
				});

				// back: the payment block is re-rendered from the server, as in the stock template
				row.querySelectorAll('[data-mk-change-back]').forEach(function (button) {
					button.addEventListener('click', function () {
						window.location.reload();
					});
				});
			});
		}
	};
})();
