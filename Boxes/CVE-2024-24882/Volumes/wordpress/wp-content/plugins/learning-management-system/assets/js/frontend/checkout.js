/* eslint-disable */
/* global _MASTERIYO_CHECKOUT_ */

jQuery(function ($) {
	// Bail if the global checkout parameters doesn't exits.
	if (typeof _MASTERIYO_CHECKOUT_ === 'undefined') {
		return false;
	}

	/**
	 * Return WordPress spinner.
	 *
	 * @returns string
	 */
	function getSpinner() {
		return '<span class="spinner" style="visibility:visible"></span>';
	}

	function getBlockLoadingConfiguration() {
		return {
			message: getSpinner(),
			css: {
				border: '',
				width: '0%',
			},
			overlayCSS: {
				background: '#fff',
				opacity: 0.6,
			},
		};
	}

	var checkoutForm = {
		$form: $('form.masteriyo-checkout'),

		/**
		 * Return ajax URL.
		 *
		 * @returns string
		 */
		getAjaxURL: function () {
			return _MASTERIYO_CHECKOUT_.ajaxURL;
		},

		/**
		 * Return checkout URL.
		 *
		 * @returns string
		 */
		getCheckoutURL: function () {
			return _MASTERIYO_CHECKOUT_.checkoutURL;
		},

		/**
		 * Initialize.
		 */
		init: function () {
			$(document.body).on('update_checkout', this.updateCheckout);
			$(document.body).on('init_checkout', this.initCheckout);

			// Payment methods
			this.$form.on(
				'click',
				'input[name="payment_method"]',
				this.paymentMethodSelected
			);

			// Prevent HTML5 validation which can conflict.
			this.$form.attr('novalidate', 'novalidate');

			// Form submission
			this.$form.on('submit', this.submit);

			this.initPaymentMethods();

			// Update on page load
			if (true === _MASTERIYO_CHECKOUT_.is_checkout) {
				$(document.body).trigger('initCheckout');
			}
		},
		initPaymentMethods: function () {
			var $payment_methods = this.$form.find('input[name="payment_method"]');

			// If there is one method, we can hide the radio input
			if (1 === $payment_methods.length) {
				$payment_methods.eq(0).hide();
			}

			// If there was a previously selected method, check that one.
			if (checkoutForm.selectedPaymentMethod) {
				$('#' + checkoutForm.selectedPaymentMethod).prop('checked', true);
			}

			// If there are none selected, select the first.
			if (0 === $payment_methods.filter(':checked').length) {
				$payment_methods.eq(0).prop('checked', true);
			}

			// Get name of new selected method.
			var checked_payment_method = $payment_methods
				.filter(':checked')
				.eq(0)
				.prop('id');

			if ($payment_methods.length > 1) {
				// Hide open descriptions.
				$('div.payment-box:not(".' + checked_payment_method + '")')
					.filter(':visible')
					.slideUp(0);
			}

			// Trigger click event for selected method
			$payment_methods.filter(':checked').eq(0).trigger('click');
		},

		/**
		 * Return payment method title.
		 *
		 * @return Payment method title.
		 */
		getPaymentMethod: function () {
			return this.$form.find('input[name="payment_method"]:checked').val();
		},

		paymentMethodSelected: function (e) {
			e.stopPropagation();

			if ($('.payment-methods input.input-radio').length > 1) {
				var target_payment_box = $('div.payment-box.' + $(this).attr('ID')),
					is_checked = $(this).is(':checked');

				if (is_checked && !target_payment_box.is(':visible')) {
					$('div.payment-box').filter(':visible').slideUp(230);

					if (is_checked) {
						target_payment_box.slideDown(230);
					}
				}
			} else {
				$('div.payment-box').show();
			}

			if ($(this).data('order_button_text')) {
				$('#masteriyo-place-order').text($(this).data('order_button_text'));
			} else {
				$('#masteriyo-place-order').text(
					$('#masteriyo-place-order').data('value')
				);
			}

			var selectedPaymentMethod = $(
				'.masteriyo-checkout input[name="payment_method"]:checked'
			).attr('id');

			if (selectedPaymentMethod !== this.selectedPaymentMethod) {
				$(document.body).trigger('paymentMethodSelected');
			}

			this.selectedPaymentMethod = selectedPaymentMethod;
		},

		/**
		 * Initialize checkout.
		 */
		initCheckout: function () {
			$(document.body).trigger('update_checkout');
		},

		/**
		 * Reset update checkout timer.
		 */
		resetUpdateCheckoutTimer: function () {
			clearTimeout(checkoutForm.updateTimer);
		},

		/**
		 * Return true if the json is valid.
		 *
		 * @param {string} raw_json
		 * @returns
		 */
		isValidJson: function (raw_json) {
			try {
				var json = JSON.parse(raw_json);

				return json && 'object' === typeof json;
			} catch (e) {
				return false;
			}
		},

		/**
		 * Update checkout.
		 *
		 * @param {} event
		 * @param {*} args
		 */
		updateCheckout: function (event, args) {
			// Small timeout to prevent multiple requests when several fields update at the same time
			checkoutForm.resetUpdateCheckoutTimer();
			checkoutForm.updateTimer = setTimeout(
				checkoutForm.updateCheckoutAction,
				'5',
				args
			);
		},

		/**
		 * Modern browsers have their own standard generic messages that they will display.
		 * Confirm, alert, prompt or custom message are not allowed during the unload event
		 * Browsers will display their own standard messages
		 *
		 * @param {*} event
		 * @returns
		 */
		handleUnloadEvent: function (event) {
			// Check if the browser is Internet Explorer
			if (
				navigator.userAgent.indexOf('MSIE') !== -1 ||
				!!document.documentMode
			) {
				// IE handles unload events differently than modern browsers
				event.preventDefault();
				return undefined;
			}

			return true;
		},

		/**
		 * Attach unload events on submit.
		 */
		attachUnloadEventsOnSubmit: function () {
			$(window).on('beforeunload', this.handleUnloadEvent);
		},

		/**
		 * Detach unload events on submit.
		 */
		detachUnloadEventsOnSubmit: function () {
			$(window).off('beforeunload', this.handleUnloadEvent);
		},

		/**
		 * Display error message.
		 */
		submitError: function (errorMessage) {
			$(
				'.masteriyo-NoticeGroup-checkout, .masteriyo-error, .masteriyo-message'
			).remove();

			checkoutForm.$form.prepend(
				'<div class="masteriyo-NoticeGroup masteriyo-NoticeGroup-checkout">' +
					errorMessage +
					'</div>'
			); // eslint-disable-line max-len

			checkoutForm.$form
				.find('.input-text, select, input:checkbox')
				.trigger('validate')
				.trigger('blur');

			checkoutForm.scrollToNotices();

			$(document.body).trigger('checkout_error', [errorMessage]);
		},

		/**
		 * Scroll to notices.
		 */
		scrollToNotices: function () {
			var scrollElement = $(
				'.masteriyo-NoticeGroup-updateOrderReview, .masteriyo-NoticeGroup-checkout'
			);

			if (!scrollElement.length) {
				scrollElement = $('form.masteriyo-checkout');
			}

			if (scrollElement.length) {
				$('html, body').animate(
					{
						scrollTop: scrollElement.offset().top - 100,
					},
					1000
				);
			}
		},

		/**
		 * Handle fail checkout form submission.
		 *
		 * @param {*} jqXHR
		 * @param {*} textStatus
		 * @param {*} errorThrown
		 */
		handleFormSubmissionFailure: function (jqXHR, textStatus, errorThrown) {
			// Detach the unload handler that prevents a reload / redirect.
			checkoutForm.detachUnloadEventsOnSubmit();

			try {
				error = jqXHR.responseJSON;
				checkoutForm.submitError(
					'<div class="masteriyo-error">' + error.data.messages + '</div>'
				);
			} catch (error) {
				console.log(error);
			}
		},

		/**
		 * Handle successful checkout form submission.
		 *
		 * @param {*} response
		 * @param {*} textStatus
		 * @param {*} jqXHR
		 */
		handleFormSubmissionSuccess: function (response, textStatus, jqXHR) {
			// Detach the unload handler that prevents a reload / redirect
			checkoutForm.detachUnloadEventsOnSubmit();

			try {
				if (
					'success' === response.result &&
					checkoutForm.$form.triggerHandler(
						'checkout_place_order_success',
						response
					) !== false
				) {
					if (
						-1 === response.redirect.indexOf('https://') ||
						-1 === response.redirect.indexOf('http://')
					) {
						window.location = response.redirect;
					} else {
						window.location = decodeURI(response.redirect);
					}
				} else if ('failure' === response.result) {
					throw 'Result failure';
				} else {
					throw 'Invalid response';
				}
			} catch (err) {
				// Reload page
				if (true === response.reload) {
					window.location.reload();
					return;
				}

				// Trigger update in case we need a fresh nonce
				if (true === response.refresh) {
					$(document.body).trigger('update_checkout');
				}

				// Add new errors
				if (response.messages) {
					checkoutForm.submitError(response.messages);
				}
			}
		},
		/**
		 * Handle checkout form submission.
		 */
		submit: function (event) {
			console.log('Checkout form submission');

			if (checkoutForm.$form.is('.processing')) {
				return false;
			}

			if (
				false !== checkoutForm.$form.triggerHandler('checkout_place_order') &&
				false !==
					checkoutForm.$form.triggerHandler(
						'checkout_place_order_' + checkoutForm.getPaymentMethod()
					)
			) {
				// Attach event to block reloading the page when the form has been submitted
				checkoutForm.attachUnloadEventsOnSubmit();

				// Perform checkout operation.
				$.ajax({
					type: 'POST',
					url: checkoutForm.getCheckoutURL(),
					dataType: 'json',
					data: checkoutForm.$form.serialize(),
					beforeSend: function (jqXHR) {
						checkoutForm.$form
							.addClass('processing')
							.block(getBlockLoadingConfiguration());
					},
					success: function (response, textStatus, jqXHR) {
						checkoutForm.handleFormSubmissionSuccess(
							response,
							textStatus,
							jqXHR
						);
					},
					error: function (jqXHR, textStatus, errorThrown) {
						checkoutForm.handleFormSubmissionFailure(
							jqXHR,
							textStatus,
							errorThrown
						);
					},
					complete: function (jqXHR, textStatus) {
						// Detach the unload handler that prevents a reload / redirect
						checkoutForm.detachUnloadEventsOnSubmit();
						checkoutForm.$form.removeClass('processing').unblock();
					},
				});
			}

			return false;
		},
	};

	checkoutForm.init();

	// Loop through the options and append them to the countries field
	$.each(_MASTERIYO_CHECKOUT_.countries, function (code, name) {
		$('#billing-county').append(
			$('<option>', {
				value: code,
				text: name,
			})
		);
	});

	$('#billing-county').on('change', function () {
		var $billingState = $('#billing-state');
		var $billingStateWrapper = $billingState.parent(
			'.masteriyo-checkout----state'
		);

		// Get the selected country value
		var selectedCountry = $(this).val();
		var countries = _MASTERIYO_CHECKOUT_.countries;
		var states = _MASTERIYO_CHECKOUT_.states;
		if (!countries || !states) {
			return;
		}

		if (!countries[selectedCountry]) {
			$billingState.html('<option value="">Select state</option>');
			return;
		}

		var selectedCountryStates = states[selectedCountry];

		if (!selectedCountryStates) {
			$billingStateWrapper.hide();
			return;
		}

		$billingState.empty();
		$billingState.append(
			$('<option>', {
				value: '',
				text: 'Select state',
			})
		);

		// Loop through the options and append them to the countries field
		$.each(selectedCountryStates, function (code, name) {
			$billingState.append(
				$('<option>', {
					value: code,
					text: name,
				})
			);
		});

		$billingStateWrapper.show();
	});
});
