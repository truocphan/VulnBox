/**
 * global masteriyo_data
 */
(function ($, masteriyo_data) {
	$(document).on(
		'click',
		'.masteriyo-review-notice .masteriyo-leave-review',
		function (e) {
			$.post(masteriyo_data.ajax_url, {
				action: 'masteriyo_review_notice',
				nonce: masteriyo_data.nonce,
				masteriyo_action: 'review_received',
			});
			$('.masteriyo-review-notice').slideUp();
		}
	);
	$(document).on(
		'click',
		'.masteriyo-review-notice .masteriyo-remind-me-later',
		function (e) {
			e.preventDefault();

			$.post(masteriyo_data.ajax_url, {
				action: 'masteriyo_review_notice',
				nonce: masteriyo_data.nonce,
				masteriyo_action: 'remind_me_later',
			});
			$('.masteriyo-review-notice').slideUp();
		}
	);
	$(document).on(
		'click',
		'.masteriyo-review-notice .masteriyo-already-reviewed',
		function (e) {
			e.preventDefault();

			$.post(masteriyo_data.ajax_url, {
				action: 'masteriyo_review_notice',
				nonce: masteriyo_data.nonce,
				masteriyo_action: 'already_reviewed',
			});
			$('.masteriyo-review-notice').slideUp();
		}
	);
	$(document).on('click', '.masteriyo-x-icon-container svg', function (e) {
		$.post(masteriyo_data.ajax_url, {
			action: 'masteriyo_review_notice',
			nonce: masteriyo_data.nonce,
			masteriyo_action: 'close_notice',
		});
		$('.masteriyo-review-notice').slideUp();
	});
})(jQuery, window._MASTERIYO_ASK_REVIEW_DATA_);
