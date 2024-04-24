/**
 * global _MASTERIYO_
 */
(function ($, _MASTERIYO_) {
	/**
	 * Login form submission handler.
	 */
	$(document.body).on('submit', 'form.masteriyo-login--form', function (e) {
		e.preventDefault();

		const $form = $(this);

		$form
			.find('button[type=submit]')
			.text(_MASTERIYO_.labels.signing_in)
			.siblings('.masteriyo-notify-message')
			.first()
			.remove();

		$(this).find('#masteriyo-login-error-msg').hide();

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: _MASTERIYO_.ajax_url,
			data: $form.serializeArray(),
			success: function (res) {
				if (res.success) {
					window.location.replace(res.data.redirect);
				} else {
					$('#masteriyo-login-error-msg').show().html(res.data.message);
				}
			},
			error: function (xhr, status, error) {
				var message = xhr.responseJSON.message
					? xhr.responseJSON.message
					: error;

				$('#masteriyo-login-error-msg').show().html(message);
			},
			complete: function () {
				$form.find('button[type=submit]').text(_MASTERIYO_.labels.sign_in);
			},
		});
	});
})(jQuery, window._MASTERIYO_);
