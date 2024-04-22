(function ($) {
	$(document).ready(function () {
		var currentTab = 0;
		showTab(currentTab);
		// $('body').css('overflow', 'hidden');
		var countSteps = $('#meetSteps span.active').index() + 1;
		var OAuthUrl = '';
		
		function showTab(n) {
			if (currentTab == 2) {
				$(".gm-next-btn").prop("disabled", true);
				$(".gm-next-btn").css('background-color', '#cacaca')
			} else {
				$(".gm-next-btn").prop("disabled", false);
				$(".gm-next-btn").css('background-color', '#2985F7')
			}
			var x = $(".tab");
			x.eq(n).css("display", "block");
			if (n == 0) {
				$("#prevBtn").css("opacity", "0");
			} else {
				$("#prevBtn").css("opacity", "1");
			}
			if (n == (x.length - 1) || n == (x.length)) {
				$(".gm-prev-btn").css('background-color', 'rgb(41, 133, 247)')
				$(".gm-prev-btn").css('cursor', 'pointer');
				$(".gm-prev-btn").css('border-color', 'rgb(41, 133, 247)');
				$('#prevBtn').prop('disabled', false);
				$("#nextBtn").html("Grant Permissions");
				
				$("#prevBtn").html("Reset credential");
			} else {
				$("#nextBtn").html("Next");
				$("#prevBtn").html("Back");
			}
			//... and run a function that will display the correct step indicator:
			fixStepIndicator(n);
		}
		
		$( '.cancel-uploaded-file' ).on('click', function () {
			$("#lms-gm-upload-file").val("");
			$("#lms-gm-upload-file-label").text("Select File");
			$(".cancel-uploaded-file").css("opacity", "0");
			$(".gm-json-config-upload").css("width", "auto");
			$(".gm-next-btn").css('background-color', '#cacaca')
			$(".gm-next-btn").css('cursor', 'default');
			$('#nextBtn').prop('disabled', true);

			var formData = new FormData();
			var vm = $(this);
			formData.append('action', 'stm_gm_reset_credentials_action');
			formData.append('nonce', stm_google_meet_ajax_variable.nonce);
			$.ajax({
				url: stm_google_meet_ajax_variable.url,
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success(response) {
					if (typeof response.url !== 'undefined') {
						window.location.href = response.url;
					}
				},
				error(xhr, ajaxOptions, thrownError) {
					console.log(xhr.responseJSON)
				}
			});
		});
		$('#prevBtn').on('click', function () {
			if ((currentTab + 1) >= $(".tab").length) {
				if (confirm('Are you sure you want to delete this permanently from the site? Please confirm your choice?')) {
					$(".tab").css('display', 'none');
					$("#lms-gm-upload-file").val("");
					$("#lms-gm-upload-file-label").text("Select File");
					$(".cancel-uploaded-file").css("opacity", "0");
					$(".gm-json-config-upload").css("width", "auto");
					$(".gm-json-config-upload").css("border", "1px solid #2985F7");
					$(".gm-json-config-upload").css("border-radius", "4px");
					currentTab = 0;
					showTab(currentTab);
				} else {
					console.log('Not saved to the database.');
				}
			} else {
				nextPrev(-1)
			}
			countSteps = $('#meetSteps span.active').index() + 1;
		});
		$('#lms-gm-upload-file').on('change', function (e) {
			var credentialJson = $('#lms-gm-upload-file')[0].files[0];
			var formData = new FormData();
			formData.append('file', credentialJson);
			formData.append('action', 'gm_upload_credentials_ajax');
			formData.append('nonce', stm_google_meet_ajax_variable.nonce);
			
			$.ajax({
				url: stm_google_meet_ajax_variable.url,
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success(response) {
					$('.gm-json-config-upload label').css('width', '88%');
					$('.gm-json-config-upload label').css('padding', '0');
					$('.gm-json-config-upload').css('width', '88%');
					$('.gm-json-config-upload label').css('border', 'none');
					$('.gm-json-config-upload ').css('border', '1px solid #2985F7');
					$('.gm-json-config-upload ').css('padding', '10px 10px 13px');
					$('.gm-json-config-upload ').css('border-radius', '4px');
					
					
					$(".gm-prev-btn").css('background-color', '#cacaca')
					$(".gm-prev-btn").css('cursor', 'default');
					$(".gm-prev-btn").css('border-color', '#cacaca');
					$(".gm-prev-btn").css('color', 'white');
					$('#prevBtn').prop('disabled', true);
					
					if (response.success) {
						$("#lms-gm-upload-file-label").text(credentialJson['name']);
						$('.cancel-uploaded-file').css('opacity', '1');
						OAuthUrl = response.url;
						$(".gm-next-btn").prop("disabled", false);
						$(".gm-next-btn").css('background-color', '#2985F7')
					} else {
						$("#lms-gm-upload-file-label").text(response.data?.error);
					}
				},
				error(xhr, ajaxOptions, thrownError) {
					console.log(xhr.responseJSON)
				}
			});
		});
		$('.lms-gm-btn-copy').on('click', function () {
			var input = $(this).siblings('#gm-copy-url');
			var value = input.val();
			
			var tempInput = $('<input>');
			$('body').append(tempInput);
			tempInput.val(value).select();
			document.execCommand('copy');
			tempInput.remove();
			
			$('.lms-gm-btn-copy').text('Copied');
			
		});
		$('#nextBtn').on('click', function () {
			nextPrev(1)
			countSteps = $('#meetSteps span.active').index() + 1;
		});
		
		function nextPrev(n) {
			var x = $(".tab");
			if (currentTab !== 3) {
				x.eq(currentTab).css("display", "none");
			}
			currentTab = currentTab + n;
			if (currentTab >= x.length && typeof OAuthUrl !== 'undefined') {
				window.location.href = OAuthUrl;
			}
			showTab(currentTab);
		}
		
		function fixStepIndicator(n) {
			var x = $(".step");
			x.removeClass("active");
			x.eq(n).addClass("active");
		}
	})
	
})(jQuery);
document.addEventListener("DOMContentLoaded", () => {

});