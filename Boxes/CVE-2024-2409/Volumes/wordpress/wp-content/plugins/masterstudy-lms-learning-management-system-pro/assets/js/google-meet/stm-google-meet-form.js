(function ($) {
	$(document).ready(function () {
		var isEdit = false;
		var editMeetID = 0;
		var isSaved = 0;
		var meetTrashUrl = '';
		$(".meet-delete-btn-cl").click(function () {
			window.location.href = meetTrashUrl;
		})

		$(document).on( 'click', '.meet-link-trash-btn', function() {
			meetTrashUrl = $(this).attr('data-meet-trash-url');
			$('#delete-meeting-mw-id').toggleClass('show');
			$("p.gm-modal-title").html ('Confirm delete');
			var formData = new FormData();
			formData.append('action', 'gm_get_meet_by_id_ajax');
			formData.append('post_id', $(this).attr('data-meet-id'));
			formData.append('nonce', stm_gm_front_ajax_variable.nonce);

			$.ajax({
				url: stm_gm_front_ajax_variable.url,
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success(response) {
					var data = [
						{label: 'Name:', value: response.meet_title},
						{label: 'Summary:', value: response.meetData.stm_gma_summary},
						{
							label: 'Starts:',
							value: (setDate(response.meetData.stm_gma_start_date / 1000)) + ' ' + (response.meetData.stm_gma_start_time)
						},
						{
							label: 'Ends:',
							value: (setDate(response.meetData.stm_gma_end_date / 1000)) + ' ' + (response.meetData.stm_gma_end_time)
						},
						{label: 'Timezone:', value: response.meetData.stm_gma_timezone},
						{label: 'Host email:', value: response.meet_host},
						// Add more objects as needed for other properties
					];
					
					var html = '';
					for (var i = 0; i < data.length; i++) {
						html += '<div class="meet-delete-row">';
						html += '<span class="names">' + data[i].label + '</span> <span class="values">' + data[i].value + '</span>';
						html += '</div>';
					}
					$('#delete-meeting-mw-id .meet-delete-data p').html(html);
				},
				error(xhr, ajaxOptions, thrownError) {
					console.log(xhr)
				}
			});
		});

		$(".create-meeting-header-btn").click(function (e) {
			e.preventDefault();
			var fields = [
				{ selector: '#front-meeting-name' },
				{ selector: '#front-meeting-summary' },
				{ selector: '#front-meeting-start-date' },
				{ selector: '#front-meeting-end-date' },
			];
			fields.forEach(function(field) {
				$(field.selector).val('');
			});
			$('#create-meeting-mw-id').toggleClass('show');
			
			$("p.gm-modal-title").html ('Add a new meeting');
		});
		$(".create-meeting-mw .gm-modal-close, .create-meeting-mw .button-cancel").click(function (e) {
			e.preventDefault();
			$('#create-meeting-mw-id').toggleClass('show');
		});
		$(".delete-meeting-mw .gm-modal-close, .delete-meeting-mw .button-cancel ").click(function (e) {
			e.preventDefault();
			$('#delete-meeting-mw-id').toggleClass('show');
		});

		$('#settings').find('input, select').on( 'change', function() {
			$(".save-gm-front-settings").removeClass('disabled').attr('disabled', false);
			$("#saveNotificationGM").fadeOut();
			$("#saveNotificationGM").find('.downloaded').fadeOut();
		});

		$(".save-gm-front-settings").click(function (e) {
			e.preventDefault();
			$(this).addClass('disabled').attr('disabled', 'disabled');
			$('#saveNotificationGM').css('display', 'block');
			$('#saveNotificationGM .installing').css('display', 'inline-block');
			$('#saveNotificationGM div').html('Saving...');
			var formData = new FormData();
			formData.append('action', 'gm_save_settings_ajax');
			formData.append('nonce', stm_gm_front_ajax_variable.nonce);
			formData.append('timezone', $('#front-meeting-timezone-settings').val());
			formData.append('reminder', $('.frontend-reminder-settings').val());
			formData.append('send_updates', $('#front-send-updates-settings').val());

			$.ajax({
				url: stm_gm_front_ajax_variable.url,
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success(response) {
					console.log(response);
					$('#saveNotificationGM div').html('Settings saved successfully');
					$('#saveNotificationGM .installing').css('display', 'none');
					$('#saveNotificationGM .downloaded').css('display', 'inline-block');
					setTimeout(function() {
						$('#saveNotificationGM').fadeOut();
					}, 3000);
				},
				error(xhr, ajaxOptions, thrownError) {
					console.log(xhr)
				}
			});
		});
		$("#front-settings-reset-credentials").click(function (e) {
			e.preventDefault();
			var formData = new FormData();
			formData.append('action', 'gm_front_reset_settings_ajax');
			formData.append('nonce', stm_gm_front_ajax_variable.nonce);
			
			if (confirm('Are you sure you want to delete this permanently from the site? Please confirm your choice?')) {
				
				$.ajax({
					url: stm_gm_front_ajax_variable.url,
					type: 'post',
					data: formData,
					dataType: 'json',
					processData: false,
					contentType: false,
					success(response) {
						location.reload();
					},
					error(xhr, ajaxOptions, thrownError) {
						console.log(xhr)
					}
				});
			}
		});
		$("#front-settings-change-account").click(function (e) {
			e.preventDefault();
			var formData = new FormData();
			formData.append('action', 'gm_front_reset_settings_ajax');
			formData.append('nonce', stm_gm_front_ajax_variable.nonce);
			formData.append('changeAccount', true);

			$.ajax({
				url: stm_gm_front_ajax_variable.url,
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success(response) {
					window.location.href = response.url;
				},
				error(xhr, ajaxOptions, thrownError) {
					console.log(xhr)
				}
			});
		});
		$('.lms-gm-validation-input').on('input', function() {
			var input = $(this);
			var errorMsg = input.next('.gm-validation-error-message');
			if (input.val().trim() !== '') {
				input.css('border-color', '#DBE0E9');
				errorMsg.remove();
			} else {
				input.css('border-color', 'red');
				if (errorMsg.length === 0) {
					errorMsg = $('<p class="gm-validation-error-message">This field is required.</p>');
					input.after(errorMsg);
					var elementToDisplay = input.next('.gm-validation-error-message');
					elementToDisplay.css('display', 'block');
				}
			}
		});
		$(".create-meeting-mw-save").on('click', function (e) {
			e.preventDefault();
			var fields = [
				{ selector: '#front-meeting-name', message: 'Please enter a meeting name' },
				{ selector: '#front-meeting-summary', message: 'Please enter a meeting summary' },
				{ selector: '#front-meeting-start-date', message: 'Please enter a start date' },
				{ selector: '#front-meeting-end-date', message: 'Please enter an end date' },
				{ selector: '#front-meeting-timezone', message: 'Please select a timezone' },
			];

			var isError = false;
			var meetingBtnText = $('.create-meeting-mw-save').html();

			for (var i = 0; i < fields.length; i++) {
				var field = $(fields[i].selector);
				if (field.val() === '') {
					field.addClass('gm-validation-error');
					isError = true;
				}
			}
			if(isError) {
				$('.gm-validation-error-message').css('display', 'block');
				return false;
			}
			
			$(this).addClass('disabled').attr('disabled', 'disabled');
			$('.create-meeting-mw-save').html('Saving...');
			$(".create-meeting-mw-save").css('cursor', 'default')
			$(".create-meeting-mw-save").css('box-shadow', 'none')
			var formData = new FormData();
			if (isEdit) {
				formData.append('is_edit', isEdit);
				formData.append('google_meet_id', editMeetID);
				formData.append('original_post_status', 'publish');
			}

			formData.append('action', 'gm_create_new_event_front');
			formData.append('isInstructorMeet', true);
			formData.append('nonce', stm_gm_front_ajax_variable.nonce);
			formData.append('name', $('#front-meeting-name').val());
			formData.append('stm_gma_summary', $('#front-meeting-summary').val());
			formData.append('front_start_date_time', $('#front-meeting-start-date').val());
			formData.append('front_end_date_time', $('#front-meeting-end-date').val());
			formData.append('stm_gma_timezone', $('#front-meeting-timezone').val());
			$.ajax({
				url: stm_gm_front_ajax_variable.url,
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success(response) {
					if ( response.success ) {
						if ( response.is_reload ) {
							location.reload();
						} else {
							$("#meetings table").removeClass('hidden');
							$("#meetings .not-found-meetings").addClass('hidden');
							$("#meetings tbody").prepend(response.table_data);
							$('.create-meeting-mw-save').css( 'background', 'green' );
							$('.create-meeting-mw-save').html(response.success);

							setTimeout(function() {
								$('#create-meeting-mw-id').toggleClass('show');
								$('.create-meeting-mw-save').html(meetingBtnText);
								$('.create-meeting-mw-save').attr('style', false);
								$('.create-meeting-mw-save').removeClass('disabled').attr('disabled', false);
							}, 3000);
						}						
					} else {
						location.reload();
					}
				},
				error(xhr, ajaxOptions, thrownError) {
					console.log(xhr)
				}
			});
			isEdit = false;
		});
		$("#meetingsList").click(function (e) {
			if (window.location.search.indexOf('?paged=') !== -1) {
				window.location.href = ($('.float_menu_item_active').attr('href') + $(this).attr('href'));
			}
			})
		$(document).on( 'click', '.meet-links-edit-meeting', function(e) {
			e.preventDefault();
			$("p.gm-modal-title").html ('Edit Meeting');
			if (e.currentTarget.dataset.meetId !== null) {
				isEdit = true;
				editMeetID = $(this).attr('data-meet-id');
				var formData = new FormData();
				formData.append('action', 'gm_get_meet_by_id_ajax');
				formData.append('post_id', editMeetID);
				formData.append('nonce', stm_gm_front_ajax_variable.nonce);

				$.ajax({
					url: stm_gm_front_ajax_variable.url,
					type: 'post',
					data: formData,
					dataType: 'json',
					processData: false,
					contentType: false,
					success(response) {
						$('#front-meeting-name').val(response.meet_title);
						$('#front-meeting-summary').val(response.meetData.stm_gma_summary);
						$('#front-meeting-start-date').val(setDateAndTime(response.meetData.stm_gma_start_date / 1000, response.meetData.stm_gma_start_time));
						$('#front-meeting-end-date').val(setDateAndTime(response.meetData.stm_gma_end_date / 1000, response.meetData.stm_gma_end_time));
						$('#front-meeting-timezone').val(response.meetData.stm_gma_timezone);
						$('#create-meeting-mw-id').toggleClass('show');
					},
					error(xhr, ajaxOptions, thrownError) {
						console.log(xhr)
					}
				});
			}
		});

		function setDateAndTime(timestamp, time) {
			var date = new Date(timestamp * 1000);

			var dateString = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
			var timeString = String(time); // Convert to string to ensure it can be split
			var hours = timeString.split(':')[0];
			var minutes = timeString.split(':')[1];
			var timeValue = dateString + 'T' + hours + ':' + minutes;
			return timeValue;
		}
		function setDate(timestamp) {
			const date = new Date(timestamp * 1000);

			const year = date.getFullYear();
			const month = String(date.getMonth() + 1).padStart(2, '0');
			const day = String(date.getDate()).padStart(2, '0');

			return `${year}-${month}-${day}`;
		}


	})
})(jQuery);