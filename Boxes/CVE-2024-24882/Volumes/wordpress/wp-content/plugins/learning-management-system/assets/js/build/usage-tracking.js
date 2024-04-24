/**
 * global masteriyo_data
 */
(function ($, masteriyo_data) {
	var allowUsageNotice = $('.masteriyo-allow-usage-notice');
	var ajaxUrl = masteriyo_data.ajax_url;
	var nonce = masteriyo_data.nonce;

	allowUsageNotice.on('click', '.masteriyo-allow-usage-tracking', function (e) {
		$.post(ajaxUrl, {
			action: 'masteriyo_allow_usage_notice',
			nonce,
			masteriyo_action: 'allow',
		});
		allowUsageNotice.slideUp();
	});

	allowUsageNotice.on('click', '.masteriyo-deny-usage-tracking', function (e) {
		$.post(ajaxUrl, {
			action: 'masteriyo_allow_usage_notice',
			nonce,
			masteriyo_action: 'deny',
		});
		allowUsageNotice.slideUp();
	});

	allowUsageNotice.on('click', '.masteriyo-x-icon-container svg', function (e) {
		$.post(ajaxUrl, {
			action: 'masteriyo_allow_usage_notice',
			nonce,
			masteriyo_action: 'close',
		});
		allowUsageNotice.slideUp();
	});
})(jQuery, window._MASTERIYO_ASK_ALLOW_USAGE_DATA_);
