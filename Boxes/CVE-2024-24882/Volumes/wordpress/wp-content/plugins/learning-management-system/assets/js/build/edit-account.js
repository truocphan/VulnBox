/**
 * global masteriyo_data
 */
(function ($, masteriyo_data) {
	let isSaving = false;

	/**
	 * Tabs handler.
	 */
	$(document.body).on('click', '.masteriyo-tab', function () {
		$(this).siblings('.masteriyo-tab').removeClass('masteriyo-active-tab');
		$(this).addClass('masteriyo-active-tab');
		$('.masteriyo-tab-content').addClass('masteriyo-hidden');
		$('#' + $(this).data('tab')).removeClass('masteriyo-hidden');
	});

	/**
	 * Edit profile form submission handler.
	 */
	$(document.body).on(
		'submit',
		'form#masteriyo-edit-profile-form',
		function (e) {
			e.preventDefault();

			if (isSaving) return;

			isSaving = true;

			const userData = {
				display_name: $('#masteriyo-edit-profile-form #username').val().trim(),
				first_name: $('#masteriyo-edit-profile-form #user-first-name').val(),
				last_name: $('#masteriyo-edit-profile-form #user-last-name').val(),
				email: $('#masteriyo-edit-profile-form #user-email').val(),
				billing: {
					address_1: $('#masteriyo-edit-profile-form #user-address').val(),
					city: $('#masteriyo-edit-profile-form #user-city').val(),
					state: $('#masteriyo-edit-profile-form #user-state').val(),
					postcode: $('#masteriyo-edit-profile-form #user-zip-code').val(),
					country: $('#masteriyo-edit-profile-form #user-country').val(),
				},
			};

			// Show saving process indicator.
			$('#masteriyo-btn-submit-edit-profile-form')
				.text(masteriyo_data.labels.saving)
				.siblings('.masteriyo-notify-message')
				.remove();

			$.ajax({
				type: 'POST',
				dataType: 'json',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': masteriyo_data.nonce,
				},
				url:
					masteriyo_data.rootApiUrl +
					'masteriyo/v1/users/' +
					masteriyo_data.current_user_id,
				data: JSON.stringify(userData),
				success: function (res) {
					// Update username on the sidebar.
					$('#label-username').text(res.display_name);

					// Show success message.
					$('#masteriyo-btn-submit-edit-profile-form').after(
						'<div class="masteriyo-notify-message masteriyo-success-msg"><span>' +
							masteriyo_data.labels.profile_update_success +
							'</span></div>'
					);
				},
				error: function (xhr) {
					// Show failure message.
					$('#masteriyo-btn-submit-edit-profile-form').after(
						'<div class="masteriyo-notify-message masteriyo-error-msg masteriyo-text-red-700 masteriyo-bg-red-100 masteriyo-border-red-300"><span>' +
							xhr.responseJSON.message +
							'</span></div>'
					);
				},
				complete: function () {
					isSaving = false;

					// Remove saving process indicator.
					$('#masteriyo-btn-submit-edit-profile-form').text(
						masteriyo_data.labels.save
					);
				},
			});
		}
	);
})(jQuery, window.masteriyo_data);
