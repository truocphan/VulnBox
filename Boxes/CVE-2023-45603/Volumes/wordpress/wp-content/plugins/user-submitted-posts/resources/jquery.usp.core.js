/* 
	User Submitted Posts : Core JS : Version 2.0
	@ https://perishablepress.com/user-submitted-posts/
*/
jQuery(document).ready(function($) {
	
	$('.usp-callout-failure').addClass('usp-hidden').hide();
	$('#user-submitted-post').on('click', function(e) {
		if (usp_recaptcha_disp == 'show' && usp_recaptcha_vers == 3) {
			var validate = usp_validate();
			e.preventDefault();
			grecaptcha.ready(function() {
				grecaptcha.execute(usp_recaptcha_key, { action: 'submit' }).then(function(token) {
					$('#recaptcha_response').remove();
					$('#usp-submit').prepend('<input type="hidden" name="recaptcha_response" id="recaptcha_response" value="'+ token +'">');
					if (validate) $('#usp_form').unbind('submit').submit();
				});;
			});
		} else {
			usp_validate();
		}
	});
	function usp_validate() {
		// $('#usp_form').parsley().validate();
		if (true === $('#usp_form').parsley().isValid()) {
			$('.usp-callout-failure').addClass('usp-hidden').hide();
			
			// remove empty file inputs
			$('.usp-clone').each(function() {
				var opt = $(this).data('parsley-excluded');
				if (typeof opt !== 'undefined' && opt == true) {
					var val = $(this).val();
					if (!val.trim()) $(this).remove();
				}
			});
			return true;
		} else {
			$('.usp-callout-failure').removeClass('usp-hidden').show();
			return false;
		}
	};
	$('#usp_form').on('submit', function(e) {
		usp_captcha_check(e);
		if ($(this).parsley().isValid()) {
			$('.usp-submit').css('cursor', 'wait');
			$('.usp-submit').attr('disabled', true);
		}
		usp_remember();
	});
	$('.usp-captcha .usp-input').on('change', function(e) {
		usp_captcha_check(e);
	});
	function usp_captcha_check(e) {
		if (usp_case_sensitivity === 'true') var usp_casing = '';
		else var usp_casing = 'i';
		var usp_response = new RegExp(usp_challenge_response + '$', usp_casing);
		var usp_captcha = $('.user-submitted-captcha').val();
		if (typeof usp_captcha != 'undefined') {
			if (usp_captcha.match(usp_response)) {
				$('.usp-captcha-error').remove();
				$('.usp-captcha .usp-input').removeClass('parsley-error');
				$('.usp-captcha .usp-input').addClass('parsley-success');
			} else {
				if (e) e.preventDefault();
				$('.usp-captcha-error').remove();
				$('.usp-captcha').append('<ul class="usp-captcha-error parsley-errors-list filled"><li class="parsley-required">'+ usp_parsley_error +'</li></ul>');
				$('.usp-captcha .usp-input').removeClass('parsley-success');
				$('.usp-captcha .usp-input').addClass('parsley-error');
			}
		}
	}
	
	// cookies
	usp_remember();
	usp_forget();
	
	function usp_cookie(selector, type) {
		$(selector).each(function() {
			var name = $(this).attr('id');
			var cookie = Cookies.get(name);
			if (cookie) {
				cookie = decodeURIComponent(cookie);
				if (type == 'checkbox') {
					if (cookie == 1) {
						$(this).val(1).prop('checked', 1);
					} else {
						$(this).val(0).prop('checked', 0);
					}
				} else if (type == 'select') {
					if (name == 'user-submitted-tags' && window.usp_existing_tags == 1) {
						$.each(cookie.split(','), function(i,e) {
							$('#user-submitted-tags option[value="'+ e +'"]').attr('selected', 'selected');
						});
					} else if (name == 'user-submitted-category' && window.usp_multiple_cats == 1) {
						$.each(cookie.split(','), function(i,e) {
							$('#user-submitted-category option[value="'+ e +'"]').attr('selected', 'selected');
						});
					} else {
						$('option[value="'+ cookie +'"]', this).attr('selected', 'selected');
					}
				} else {
					$(this).val(cookie);
				}
			}
			$(this).on('change', function() {
				if (type == 'checkbox') {
					
					if ($(this).is(':checked')) {
						var value = 1;
						$(this).val(1).prop('checked', 1);
					} else {
						var value = 0;
						$(this).val(0).prop('checked', 0);
					}
				} else {
					var value = $(this).val();
				}
				Cookies.set(name, encodeURIComponent(value), { path: '/', expires: 365000, SameSite: 'strict' });
			});
		});
	}
	function usp_remember() {
		usp_cookie('#user-submitted-name',     'text');
		usp_cookie('#user-submitted-email',    'text');
		usp_cookie('#user-submitted-url',      'text');
		usp_cookie('#user-submitted-title',    'text');
		
		if (window.usp_existing_tags == 1) {
			usp_cookie('#user-submitted-tags', 'select');
		} else {
			usp_cookie('#user-submitted-tags', 'text');
		}
		usp_cookie('#user-submitted-custom',   'text');
		usp_cookie('#user-submitted-captcha',  'text');
		usp_cookie('#user-submitted-category', 'select');
		usp_cookie('#user-submitted-content',  'textarea');
		usp_cookie('#user-submitted-checkbox', 'checkbox');
	}
	function usp_forget() {
		
		if (window.location.href.indexOf('success=') > -1) {
			
			Cookies.remove('user-submitted-name');
			Cookies.remove('user-submitted-email');
			Cookies.remove('user-submitted-url');
			Cookies.remove('user-submitted-title');
			Cookies.remove('user-submitted-tags');
			Cookies.remove('user-submitted-category');
			Cookies.remove('user-submitted-content');
			Cookies.remove('user-submitted-custom');
			Cookies.remove('user-submitted-checkbox');
			Cookies.remove('user-submitted-captcha');
			$('#usp_form').find('input[type="text"], textarea').val('');
			$('#usp_form option[value=""]').attr('selected', '');
		}
	}
	
	// add another image
	$('#usp_add-another').removeClass('usp-no-js');
	$('#usp_add-another').addClass('usp-js');
	
	usp_add_another();
	
	function usp_add_another() {
		var x = parseInt($('#usp-min-images').val());
		var y = parseInt($('#usp-max-images').val());
		if (x === 0) x = 1;
		if (x >= y) $('#usp_add-another').hide();
		
		$('#usp_add-another').on('click', function(e) {
			e.preventDefault();
			x++;
			var link = $(this);
			var clone = $('#user-submitted-image').find('input:visible:last').clone().val('').attr('style', 'display:block;');
			var prev = '<img class="usp-file-preview" src="" alt="" style="display:none;">';
			$('#usp-min-images').val(x);
			if (x < y) {
				link.before(clone.fadeIn(300));
				link.before(prev);
			} else if (x = y) {
				link.before(clone.fadeIn(300));
				link.before(prev);
				link.hide();
			} else {
				link.hide();
			}
			clone.attr('data-parsley-excluded', 'true');
		});
	}
	
	// file preview
	$('.usp-input[type=file]').after('<img class="usp-file-preview" src="" alt="" style="display:none;">');
	$(document).on('change', '.usp-input[type=file]', function(x) {
		var f = x.target.files[0];
		var disable = (typeof window.usp_disable_previews !== 'undefined') ? window.usp_disable_previews : false;
		if (f && !disable) {
			var r = new FileReader();
			var prev = $(this).nextAll('.usp-file-preview:first');
			r.onload = function(e) {
				prev.attr('src', r.result);
				prev.css({ 'display':'block', 'height':'180px', 'width':'auto', 'margin':'10px 0', 'border':'0' });
			};
			r.readAsDataURL(f);
		}
	});
	
	// chosen
	var disable_chosen = (typeof window.usp_disable_chosen !== 'undefined') ? window.usp_disable_chosen : false;
	
	if (window.usp_multiple_cats == 1 && !disable_chosen) $('#user-submitted-category').chosen();
	if (window.usp_existing_tags == 1 && !disable_chosen) $('#user-submitted-tags').chosen();
	
});