/**
 * global masteriyo_data
 */
(function ($, masteriyo_data) {
	var masteriyoDeactivationFeedback = {
		/**
		 * Initializes the deactivation feedback.
		 */
		init: function () {
			this.bindUIActions();
		},

		/**
		 * Binds event handlers to elements.
		 */
		bindUIActions: function () {
			var $popupWrapper = $('#masteriyo-deactivate-feedback-popup-wrapper');

			var showPopup = function () {
				$popupWrapper.fadeIn('slow').css('display', 'block');
			};

			var hidePopup = function () {
				$popupWrapper.fadeOut('slow', function () {
					$(this).css('display', 'none');
				});
			};

			$(document.body).on(
				'click',
				'tr[data-plugin^="learning-management-system"][data-plugin$="lms.php"] span.deactivate a',
				function (e) {
					e.preventDefault();
					showPopup();
				}
			);

			$popupWrapper.click(function (event) {
				if (
					!$(event.target).closest('.masteriyo-deactivate-feedback-popup-inner').length
				) {
					hidePopup();
				}
			});

			$('form.masteriyo-deactivate-feedback-form').on('submit', function (e) {
				e.preventDefault();
				masteriyoDeactivationFeedback.sendFeedback($(this));
			});

			$popupWrapper.on('click', '.close-deactivate-feedback-popup', function () {
				hidePopup();
			});

			$popupWrapper.on(
				'click',
				'input.masteriyo-deactivate-feedback-input',
				function () {
					var $this = $(this);
					var inputTextBox = $('input[name="reason_found_bug_in_the_plugin"]');
					var inputValue = $this.val();

					switch (inputValue) {
						case 'found_bug_in_the_plugin':
							inputTextBox.attr('required', 'required');
							break;
						default:
							inputTextBox.removeAttr('required');
							break;
					}
				}
			);
		},

		/**
		 * Sends the deactivation feedback.
		 *
		 * @param {jQuery} form - The feedback form.
		 */
		sendFeedback: function (form) {
			var reasonSlug = form.find('input[name="reason_slug"]:checked').val();

			if (reasonSlug === undefined) {
				alert(masteriyo_data.error_messages.select_at_least_one);
				return;
			}

			if (form.find('button.submit').hasClass('button-disabled')) {
				return;
			}

			$.ajax({
				url: masteriyo_data.ajax_url,
				data: form.serializeArray(),
				type: 'POST',
				beforeSend: function () {
					form
						.find('button.submit')
						.addClass('button-disabled button updating-message');
				},
			}).done(function () {
				window.location = form.find('a.skip').attr('href');
			});
		},
	};

	masteriyoDeactivationFeedback.init();
})(jQuery, window._MASTERIYO_DEACTIVATION_FEEDBACK_DATA_);
