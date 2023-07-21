function pm_change_search_field(a)
{
    var group = a;
    var data ={'action':'pm_advance_search_get_search_fields_by_gid', 'gid' : group, 'match_fields': ' '};
    jQuery.post(pm_ajax_object.ajax_url, data, function (response){
       if(response){
           jQuery('#advance_seach_ul').empty();
         jQuery('#advance_seach_ul').append(response);
         pm_advance_user_search('');

     }else{
           //console.log("err");
       }

    });
}
function pm_remove_attachment(obj,key,value)
{
    jQuery('#pm-edit-group-popup, .pm-popup-mask, .pg-blog-dialog-mask').toggle();
    ( function($) {
        $( "#pg-remove-attachment-dialog" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Yes": function() {
                
                var data = {
                        'action': 'pm_remove_attachment',
                        'key': key,
                        'value':value
                };
                $.post(pm_ajax_object.ajax_url, data, function(response) {
                    if(response)
                    {
                        $(obj).parent('a').parent('span.pm_frontend_attachment').remove();
                    }
                });

                $( this ).dialog( "close" );
            },
            "No": function() {
              $( this ).dialog( "close" );
            }
          }
        });
    } )(jQuery);
}

function pm_expand_all_conent()
{
	jQuery("#pm-accordion .pm-accordian-content").show();	
}

function pm_collapse_all_conent()
{
	jQuery("#pm-accordion .pm-accordian-content").hide();	
}
 
function pm_show_hide(obj,primary,secondary,trinary)
{	
	a = jQuery(obj).is(':checked');
	if (a == true)
	 {
		jQuery('#'+primary).show(500);
		if(secondary!='')
		{
			jQuery('#'+secondary).hide(500);
		}
		if(trinary!='')
		{
			jQuery('#'+trinary).hide(500);
		}		
	}
	else 
	{
		jQuery('#'+primary).hide(500);
		if(secondary!='')
		{
			jQuery('#'+secondary).show(500);
		}
		if(trinary!='')
		{
			jQuery('#'+trinary).show(500);
		}
	}
	
}

function pm_add_repeat(obj)
{
	a= jQuery(obj).parent('a').parent('div.pm_repeat').clone();
	jQuery(a).children('input').val('');
	jQuery(obj).parent('a').parent('div.pm_repeat').parent('div.pm-field-input').append(a);
}

function pm_remove_repeat(obj)
{
	jQuery(obj).parent('a').parent('div.pm_repeat').remove();
}

function validate_phone_number2(number)
{
    var isnumber = jQuery.isNumeric(number);
    var regex = /^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/;
    var phone_num = number.replace(/[^\d]/g, '');
    if ( number != "")
    {
//        if(isnumber == false )
//        return false;
    
    if(phone_num.length <10 || phone_num.length > 13)
        return false;
    
//    if(!regex.test(number))
//        return false;
    
    return true;
    }else
    {
        return true;
    }
   

}

function validate_phone_number(number) {
    if(number!=""){
    var phone_num = number.replace(/[^\d]/g, '');
    var a = number;
       var phone_num = number.replace(/[^\d]/g, '');
    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    if (filter.test(a)&&(phone_num.length >=10 && phone_num.length <= 13)) {
        //console.log(phone_num);
        return true;
    }
    else {
        return false;
    }
    }else{
        return true;
    }
}

function validate_facebook_url(val)
{
    if (val != "") {
        if (/(?:https?:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*?(\/)?([\w\-\.]*)/i.test(val))
        {
            return true;
        } else
        {
            return false;
        }
    } else {
        return true;
    }

}

function validate_twitter_url(val)
{
    if (val != '') {
        if (/(ftp|http|https):\/\/?((www|\w\w)\.)?twitter.com(\w+:{0,1}\w*@)?(\S+)(:([0-9])+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/i.test(val)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function validate_google_url(val)
{
    if (val != '') {
        if (/((http:\/\/(plus\.google\.com\/.*|www\.google\.com\/profiles\/.*|google\.com\/profiles\/.*))|(https:\/\/(plus\.google\.com\/.*)))/i.test(val)) {
            return true;
        } else {
            return false;
        }

    } else {
        return true;
    }
}

function validate_linked_in_url(val)
{
    if (val != '') {
        if (/(ftp|http|https):\/\/?((www|\w\w)\.)?linkedin.com(\w+:{0,1}\w*@)?(\S+)(:([0-9])+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/i.test(val)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function validate_youtube_url(val)
{
    if (val != '') {
        if (/(ftp|http|https):\/\/?((www|\w\w)\.)?youtube.com(\w+:{0,1}\w*@)?(\S+)(:([0-9])+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/i.test(val)) {
            return true;
        } else {
            return false;
        }

    } else {
        return true;
    }
}

function validate_soundcloud_url(val)
{
    if (val != '') {
        if (/(ftp|http|https):\/\/?((www|\w\w)\.)?soundcloud.com(\w+:{0,1}\w*@)?(\S+)(:([0-9])+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/i.test(val)) {
            return true;
        } else {
            return false;
        }

    } else {
        return true;
    }
}

function validate_mixcloud_url(val)
{
    if (val != '') {
        if (/(ftp|http|https):\/\/?((www|\w\w)\.)?mixcloud.com(\w+:{0,1}\w*@)?(\S+)(:([0-9])+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/i.test(val)) {
            return true;
        } else {
            return false;
        }

    } else {
        return true;
    }
}

function validate_instagram_url(val)
{
    if (val != '') {
        var regex = /(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/([A-Za-z0-9-_]+)/;
        if (val.match(regex)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function profile_magic_frontend_validation(form)
{
	
	var email_val = "";
	var formid = form.id;
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('.errortext').html('');
	jQuery('.errortext').hide();
	jQuery('.all_errors').html('');
	jQuery('.warning').removeClass('warning');
        jQuery('.pg-form-validation-error').removeClass('pg-form-validation-error');

        jQuery('#'+formid+' .pm_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_email);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
		var isnumber = jQuery.isNumeric(number);
		if (isnumber == false && number != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_number);
			jQuery(this).children('.errortext').show();
		}
	});
	
        	
	jQuery('#'+formid+' .pm_phone_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_phone_number(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_phone_number);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_mobile_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_phone_number(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_mobile_number);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_facebook_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_facebook_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_facebook_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_twitter_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_twitter_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_twitter_url);
			jQuery(this).children('.errortext').show();
		}
	});

            
        jQuery('#'+formid+' .pm_google_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_google_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_google_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_linked_in_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_linked_in_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_linked_in_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_youtube_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_youtube_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_youtube_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_mixcloud_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_mixcloud_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_mixcloud_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_soundcloud_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_soundcloud_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_soundcloud_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_instagram_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_instagram_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_instagram_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
	jQuery('#'+formid+' .pm_datepicker').each(function (index, element) {
		var date = jQuery(this).children('input').val();
		var pattern = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;
                if (date != "" && !pattern.test(date)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_date);
			jQuery(this).children('.errortext').show();
		}
            
	});
	
	jQuery('#'+formid+' .pm_required').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_select_required').each(function (index, element) {
		var value = jQuery(this).children('select').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('select').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
        
	jQuery('#'+formid+' .pm_rich_editor_required').each(function (index, element) {
           
	});
        
	jQuery('#'+formid+' .pm_textarearequired').each(function (index, element) {
		var value = jQuery(this).children('textarea').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_checkboxrequired').each(function (index, element) {
		var checkboxlenght = jQuery(this).children('.pmradio').children('.pm-radio-option').children('input[type="checkbox"]:checked');
		var atLeastOneIsChecked = checkboxlenght.length > 0;
		if (atLeastOneIsChecked == true) {
		}else{
			//jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_radiorequired').each(function (index, element) {
		var checkboxlenght = jQuery(this).children('.pmradio').children('.pm-radio-option').children('input[type="radio"]:checked');
		var atLeastOneIsChecked = checkboxlenght.length > 0;
		if (atLeastOneIsChecked == true) {
		}else{
			//jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_fileinput .pm_repeat').each(function (index, element) {
		var val = jQuery(this).children('input').val().toLowerCase();
		var allowextensions = jQuery(this).children('input').attr('data-filter-placeholder');
		if(allowextensions=='')
		{
			allowextensions = pm_error_object.allow_file_ext;
		}
		
		allowextensions = allowextensions.toLowerCase();
		var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
		if(!(regex.test(val)) && val!="") {
		
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.file_type);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_repeat_required .pm_repeat').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_user_pass').each(function (index, element) {
		var password = jQuery(this).children('input').val();
		var passwordlength = password.length;
		if(password !="")
		{
			if(passwordlength < 7)
			{
				jQuery(this).children('input').addClass('warning');
				jQuery(this).children('.errortext').html(pm_error_object.short_password);
				jQuery(this).children('.errortext').show();
			}
		}
	});
	
	jQuery('#'+formid+' .pm_confirm_pass').each(function (index, element) {
		var confirm_pass = jQuery(this).children('input').val();
		var password = password = jQuery('#'+formid+' .pm_user_pass').children('input').val();
		if(password != confirm_pass)
		{
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.pass_not_match);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_recaptcha').each(function (index, element) {
		var response = grecaptcha.getResponse();
				//recaptcha failed validation
		if (response.length == 0) {
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	var b = '';
	 jQuery('#'+formid+' .errortext').each(function () {
		var a = jQuery(this).html();
                if(a!=='')
                {
                    jQuery(this).addClass('pg-form-validation-error');
                }
		b = a + b;
	});
	
	if (jQuery('#'+formid+' .usernameerror').length > 0) 
		{
			c = jQuery('#'+formid+' .usernameerror').html();
                        if(c!=='')
                        {
                            jQuery('#'+formid+' .usernameerror').addClass('pg-form-validation-error');
                        }
			b = c + b;
		}
                else
                {
                    c = '';
                }
		
		if (jQuery('#'+formid+' .useremailerror').length > 0) 
		{
			d = jQuery('.useremailerror').html();
                         if(d!=='')
                        {
                            jQuery('#'+formid+' .useremailerror').addClass('pg-form-validation-error');
                        }
			b = c + b;
			b = d + b;
		}
	jQuery('#'+formid+' .all_errors').html(b);
	var error = jQuery('#'+formid+' .all_errors').html();
	if (error == '') {
		return true;
	} else {
            jQuery('html, body').animate({
                scrollTop: jQuery('#'+formid+' .pg-form-validation-error').first().offset().top-40
            }, 500);
            jQuery('.pg-edit-group-popup-loader').remove();
            jQuery('input[type=submit][name=reg_form_submit]').removeClass('pg-stripe-submit-disabled');
		return false;
	}
}

function profile_magic_frontend_validation_edit_profile(form)
{
	
	var email_val = "";
	var formid = form.id;
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('.errortext').html('');
	jQuery('.errortext').hide();
	jQuery('.all_errors').html('');
	jQuery('.warning').removeClass('warning');

        jQuery('#'+formid+' .pm_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_email);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
		var isnumber = jQuery.isNumeric(number);
		if (isnumber == false && number != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_number);
			jQuery(this).children('.errortext').show();
		}
	});
	
        	
	jQuery('#'+formid+' .pm_phone_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_phone_number(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_phone_number);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_mobile_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_phone_number(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_mobile_number);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_facebook_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_facebook_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_facebook_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_twitter_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_twitter_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_twitter_url);
			jQuery(this).children('.errortext').show();
		}
	});

            
        jQuery('#'+formid+' .pm_google_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_google_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_google_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_linked_in_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_linked_in_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_linked_in_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_youtube_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_youtube_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_youtube_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_mixcloud_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_mixcloud_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_mixcloud_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_soundcloud_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_soundcloud_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_soundcloud_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_instagram_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_instagram_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_instagram_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
	jQuery('#'+formid+' .pm_datepicker').each(function (index, element) {
		var date = jQuery(this).children('input').val();
		var pattern = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;
                if (date != "" && !pattern.test(date)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_date);
			jQuery(this).children('.errortext').show();
		}
            
	});
	
	jQuery('#'+formid+' .pm_required').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_select_required').each(function (index, element) {
		var value = jQuery(this).children('select').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('select').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
        
	jQuery('#'+formid+' .pm_rich_editor_required').each(function (index, element) {
           
	});
        
	jQuery('#'+formid+' .pm_textarearequired').each(function (index, element) {
		var value = jQuery(this).children('textarea').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_checkboxrequired').each(function (index, element) {
		var checkboxlenght = jQuery(this).children('.pmradio').children('.pm-radio-option').children('input[type="checkbox"]:checked');
		var atLeastOneIsChecked = checkboxlenght.length > 0;
		if (atLeastOneIsChecked == true) {
		}else{
			//jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_radiorequired').each(function (index, element) {
		var checkboxlenght = jQuery(this).children('.pmradio').children('.pm-radio-option').children('input[type="radio"]:checked');
		var atLeastOneIsChecked = checkboxlenght.length > 0;
		if (atLeastOneIsChecked == true) {
		}else{
			//jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_fileinput .pm_repeat').each(function (index, element) {
		var val = jQuery(this).children('input').val().toLowerCase();
		var allowextensions = jQuery(this).children('input').attr('data-filter-placeholder');
		if(allowextensions=='')
		{
			allowextensions = pm_error_object.allow_file_ext;
		}
		
		allowextensions = allowextensions.toLowerCase();
		var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
		if(!(regex.test(val)) && val!="") {
		
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.file_type);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_repeat_required .pm_repeat').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_user_pass').each(function (index, element) {
		var password = jQuery(this).children('input').val();
		var passwordlength = password.length;
		if(password !="")
		{
			if(passwordlength < 7)
			{
				jQuery(this).children('input').addClass('warning');
				jQuery(this).children('.errortext').html(pm_error_object.short_password);
				jQuery(this).children('.errortext').show();
			}
		}
	});
	
	jQuery('#'+formid+' .pm_confirm_pass').each(function (index, element) {
		var confirm_pass = jQuery(this).children('input').val();
		var password = password = jQuery('#'+formid+' .pm_user_pass').children('input').val();
		if(password != confirm_pass)
		{
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.pass_not_match);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_recaptcha').each(function (index, element) {
		var response = grecaptcha.getResponse();
				//recaptcha failed validation
		if (response.length == 0) {
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	var b = '';
	 jQuery('#'+formid+' .errortext').each(function () {
		var a = jQuery(this).html();
		b = a + b;
	});
	
	if (jQuery('#'+formid+' .usernameerror').length > 0) 
		{
			c = jQuery('.usernameerror').html();
			b = c + b;
		}
		
		if (jQuery('#'+formid+' .useremailerror').length > 0) 
		{
			d = jQuery('.useremailerror').html();
			b = d + b;
		}
	jQuery('#'+formid+' .all_errors').html(b);
	var error = jQuery('#'+formid+' .all_errors').html();
	if (error == '') {
		return true;
	} else {
            pm_expand_all_conent();
            jQuery(window).scrollTop( jQuery(".warning:first").offset().top);
            console.log(jQuery(".warning:first").offset().top);
		return false;
	}
}

function pm_frontend_check_username(formid)
{
	jQuery('.pm_user_name').each(function (index, element) {
			var field = this;
			var username = jQuery(this).children('input').val();
			var data = {
							'action': 'pm_check_user_exist',
                                                        'nonce': pm_ajax_object.nonce,
							'type': 'validateUserName',
							'userdata' : username
						};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(pm_ajax_object.ajax_url, data, function(response) {
				if(response=="true")
				{
					jQuery(field).children('input').addClass('warning');
					jQuery(field).children('.usernameerror').html(pm_error_object.user_exist);
					jQuery(field).children('.usernameerror').show();
				}
				else
				{
					jQuery(field).children('input').removeClass('warning');
					jQuery(field).children('.usernameerror').html('');
					jQuery(field).children('.usernameerror').hide();
				}
				
			});		
		});	
}

function pm_frontend_check_useremail(previous)
{
	jQuery('.pm_user_email').each(function (index, element) {
		var field = this;
		var username = jQuery(this).children('input').val();
		var data = {
						'action': 'pm_check_user_exist',
                                                'nonce': pm_ajax_object.nonce, 
						'type': 'validateUserEmail',
						'userdata' : username,
                                                'previous_data':previous
					};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(pm_ajax_object.ajax_url, data, function(response) {
			if(response=="true")
			{
				jQuery(field).children('input').addClass('warning');
				jQuery(field).children('.useremailerror').html(pm_error_object.email_exist);
				jQuery(field).children('.useremailerror').show();
			}
			else
			{
				jQuery(field).children('input').removeClass('warning');
				jQuery(field).children('.useremailerror').html('');
				jQuery(field).children('.useremailerror').hide();	
			}
		});		
	});
}

function pm_frontend_change_password(form)
{
	var pass1 = jQuery(form).children('.pmrow').children('.pm-col').children('.pm-field-input').children('#pass1').val();	
        var pass2 = jQuery(form).children('.pmrow').children('.pm-col').children('.pm-field-input').children('#pass2').val();	
	var userid = jQuery(form).children('#user_id').val();
        jQuery('#pm_reset_passerror').removeClass('pm_password_error');
        jQuery('#pm_reset_passerror').removeClass('pm_password_success');
	var data = {'action': 'pm_change_frontend_user_pass','pass1': pass1,'pass2' : pass2, 'nonce': pm_ajax_object.nonce};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(pm_ajax_object.ajax_url, data, function(response) {
			if(response==true)
			{
                            jQuery('#pm_reset_passerror').addClass('pm_password_success');
				jQuery('#pm_reset_passerror').html(pm_error_object.password_change_successfully);
				jQuery('#pm_reset_passerror').show();
                                window.location = pm_error_object.login_url;
                        }
                        else
                        {
                            
                            jQuery('#pm_reset_passerror').addClass('pm_password_error');
                            jQuery('#pm_reset_passerror').html(response);
			    jQuery('#pm_reset_passerror').show();
                        }
		});		
	return false;
}

var searchRequest = null; 
function pm_advance_user_search(pagenum)
{


    var form = jQuery("#pm-advance-search-form");
    jQuery("#pm_result_pane").html('<div class="pm-loader"></div>');
    var pmDomColor = jQuery(".pmagic").find("a").css('color');
    jQuery(".pm-loader").css('border-top-color', pmDomColor);
  


       
       
    if(pagenum!== '')
    {
            if(pagenum=='Reset')
            {
                form.trigger('reset');
                jQuery('#advance_search_pane').hide(200);
                jQuery('#pagenum').attr("value",1);
                jQuery('input[type=checkbox]').attr("checked",false);
                pm_change_search_field('');
            }
            else
            {
                jQuery('#pagenum').attr("value",pagenum);
            }
        
    }
    else
    {
         jQuery('#pagenum').attr("value",1);
    }
    var form_values = form.serializeArray();

    var data = {'nonce': pm_ajax_object.nonce};

    //creating data in object format and array for multiple checkbox
    jQuery.each(form_values, function () {
        if (data[this.name] !== undefined) {
            if (!data[this.name].push) {
                data[this.name] = [data[this.name]];
            }
            data[this.name].push(this.value);
        } else {
            data[this.name] = this.value;
        }
    });
    //console.log(data);
   
    if(searchRequest != null)
        searchRequest.abort();
        //ajax call start
    searchRequest =    jQuery.post(pm_ajax_object.ajax_url, data, function (resp) 
        {
        
                if (resp)
                {   
                    jQuery("#pm_result_pane").html(resp);
                    
        var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery(".pm-color").css('color', pmDomColor);
        jQuery( ".page-numbers.current" ).css('background', pmDomColor); 
                } 
                else
                {
                    //console.log("err");
                }
            
         });
         //ajax call ends here
         
         


}

function profile_magic_send_email(userid)
{
    var data = {'action': 'pm_send_change_pass_email','userid': userid};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(pm_ajax_object.ajax_url, data, function(response) {
                    
		});		
	return false;
}

function profile_magic_multistep_form_validation(form)
{
	
	var email_val = "";
	var formid = form.attr('id');
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('.errortext').html('');
	jQuery('.errortext').hide();
	jQuery('.all_errors').html('');
	jQuery('.warning').removeClass('warning');
jQuery('#'+formid+' .pm_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_email);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
		var isnumber = jQuery.isNumeric(number);
		if (isnumber == false && number != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_number);
			jQuery(this).children('.errortext').show();
		}
	});
	
        jQuery('#'+formid+' .pm_phone_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_phone_number(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_phone_number);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_mobile_number').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_phone_number(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_mobile_number);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_facebook_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_facebook_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_facebook_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_twitter_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_twitter_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_twitter_url);
			jQuery(this).children('.errortext').show();
		}
	});

            
        jQuery('#'+formid+' .pm_google_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_google_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_google_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_linked_in_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_linked_in_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_linked_in_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
                
        jQuery('#'+formid+' .pm_youtube_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_youtube_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_youtube_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_mixcloud_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_mixcloud_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_mixcloud_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_soundcloud_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_soundcloud_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_soundcloud_url);
			jQuery(this).children('.errortext').show();
		}
	});
                
        jQuery('#'+formid+' .pm_instagram_url').each(function (index, element) {
		var number = jQuery(this).children('input').val();
                if (!validate_instagram_url(number)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_instagram_url);
			jQuery(this).children('.errortext').show();
		}
	});
        
	jQuery('#'+formid+' .pm_datepicker').each(function (index, element) {
		var date = jQuery(this).children('input').val();
		var pattern = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;
    	if (date != "" && !pattern.test(date)) {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_date);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_required').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_select_required').each(function (index, element) {
		var value = jQuery(this).children('select').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('select').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_textarearequired').each(function (index, element) {
		var value = jQuery(this).children('textarea').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_checkboxrequired').each(function (index, element) {
		var checkboxlenght = jQuery(this).children('.pmradio').children('.pm-radio-option').children('input[type="checkbox"]:checked');
		var atLeastOneIsChecked = checkboxlenght.length > 0;
		if (atLeastOneIsChecked == true) {
		}else{
			//jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_radiorequired').each(function (index, element) {
		var checkboxlenght = jQuery(this).children('.pmradio').children('.pm-radio-option').children('input[type="radio"]:checked');
		var atLeastOneIsChecked = checkboxlenght.length > 0;
		if (atLeastOneIsChecked == true) {
		}else{
			//jQuery(this).children('textarea').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_fileinput .pm_repeat').each(function (index, element) {
		var val = jQuery(this).children('input').val().toLowerCase();
		var allowextensions = jQuery(this).children('input').attr('data-filter-placeholder');
		if(allowextensions=='')
		{
			allowextensions = pm_error_object.allow_file_ext;
		}
		
		allowextensions = allowextensions.toLowerCase();
		var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
		if(!(regex.test(val)) && val!="") {
		
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.file_type);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_repeat_required .pm_repeat').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_user_pass').each(function (index, element) {
		var password = jQuery(this).children('input').val();
		var passwordlength = password.length;
		if(password !="")
		{
			if(passwordlength < 7)
			{
				jQuery(this).children('input').addClass('warning');
				jQuery(this).children('.errortext').html(pm_error_object.short_password);
				jQuery(this).children('.errortext').show();
			}
		}
	});
	
	jQuery('#'+formid+' .pm_confirm_pass').each(function (index, element) {
		var confirm_pass = jQuery(this).children('input').val();
		var password = password = jQuery('#'+formid+' .pm_user_pass').children('input').val();
		if(password != confirm_pass)
		{
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.pass_not_match);
			jQuery(this).children('.errortext').show();
		}
	});
	
	jQuery('#'+formid+' .pm_recaptcha').each(function (index, element) {
		var response = grecaptcha.getResponse();
				//recaptcha failed validation
		if (response.length == 0) {
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
	
	var all_errors = '';
	jQuery('#'+formid+' .errortext').each(function () {
		var a = jQuery(this).html();
		all_errors = a + all_errors;
	});
		if (jQuery('#'+formid+' .usernameerror').length > 0) 
		{
			c = jQuery('.usernameerror').html();
			if(jQuery.trim(c)!='')
			jQuery('.pm_user_name').children('input').addClass('warning');
			all_errors = c + all_errors;
		}
		
		if (jQuery('#'+formid+' .useremailerror').length > 0) 
		{
			d = jQuery('.useremailerror').html();
			if(jQuery.trim(d)!='')
			jQuery('.pm_user_email').children('input').addClass('warning');
			all_errors = d + all_errors;
		}
	jQuery('#'+formid+' .all_errors').html(all_errors);
	var error = jQuery('#'+formid+' .all_errors').html();
	if (error == '') {
		return true;
	} else {
		return false;
	}
}

function openParentTab() 
{
	locationHash = location.hash.substring( 1 );
	//console.log(locationHash);
	// Check if we have an location Hash
	if (locationHash) {
		// Check if the location hash exsist.
		var hash = jQuery('#'+locationHash);
		if (hash.length) {
			 var t = hash;
                        jQuery('li.pm-profile-tab a').removeClass('active');         
                        jQuery(this).addClass('active');
                        jQuery('.pg-profile-tab-content').hide();
                        jQuery(t).find('.pm-section-content:first').show();
                        jQuery('li.hideshow ul').hide();
                        jQuery(t).fadeIn('slow');
                        return false;
		}
	}
}

function generateTabs(tabs) { 

	html = '';
	for (var i in tabs) { 
		tab = tabs[i];
		html = html + '<li class="multipage_tab"><a href="#" onclick="return jQuery(\'#multipage\').gotopage(' + tab.number + ');">' + tab.title + '</a></li>';				
	}
	jQuery('<ul class="multipage_tabs" id="multipage_tabs">'+html+'<div class="clearer"></div></ul>').insertBefore('#multipage');
}
function setActiveTab(selector,page) { 
	jQuery('#multipage_tabs li').each(function(index){ 
		if ((index+1)==page) { 
			jQuery(this).addClass('active');
		} else {
			jQuery(this).removeClass('active');
		}
	});			
}
		
function transition(from,to) {
	jQuery(from).fadeOut('fast',function(){jQuery(to).fadeIn('fast');});

}
function textpages(obj,page,pages) { 
	jQuery(obj).html(page + ' of ' + pages);
}

function pm_user_image_validation(a)
{
	var val = jQuery(a).children('.pm-user-image').val().toLowerCase();
	if(val=='')
	{
		jQuery(a).children('pm-user-image').addClass('warning');
		jQuery(a).children('.pm-popup-error').html(pm_error_object.required_field);
		jQuery(a).children('.pm-popup-error').show();
		return false;
	}
	
	var allowextensions = 'jpg|jpeg|png|gif';
	if(allowextensions=='')
	{
		allowextensions = pm_error_object.allow_file_ext;
	}
	allowextensions = allowextensions.toLowerCase();
	var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
	if(!(regex.test(val)) && val!="") {
		jQuery(a).children('pm-user-image').addClass('warning');
		jQuery(a).children('.pm-popup-error').html(pm_error_object.file_type);
		jQuery(a).children('.pm-popup-error').show();
		return false;
	}
	else
	{
		jQuery(a).children('.pm-popup-error').html('');
		jQuery(a).children('.pm-popup-error').hide();
		return true;
	}
}





//GUI Functions
function callPmPopup(dialog) {
    var pmId = dialog + "-dialog";
    jQuery(pmId).siblings('.pm-popup-mask').show();
    jQuery(pmId).show();
    jQuery('.pm-popup-container').css("animation", "pm-popup-in 0.3s ease-out 1");
}




function profile_magic_blogpost_validation()
{
	jQuery('.errortext').html('');
	jQuery('.errortext').hide();
	jQuery('.all_errors').html('');
	jQuery('.warning').removeClass('warning');
        jQuery('#pm_add_blog_post .pm_required').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#pm_add_blog_post .pm_fileinput .pm_repeat').each(function (index, element) {
		var val = jQuery(this).children('input').val().toLowerCase();
		var allowextensions = 'jpg|jpeg|png|gif';
		if(allowextensions=='')
		{
			allowextensions = pm_error_object.allow_file_ext;
		}
		
		allowextensions = allowextensions.toLowerCase();
		var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
		if(!(regex.test(val)) && val!="") {
		
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.file_type);
			jQuery(this).children('.errortext').show();
		}
	});
        var all_errors = '';
	jQuery('#pm_add_blog_post .errortext').each(function () {
		var a = jQuery(this).html();
		all_errors = a + all_errors;
	});
        jQuery('#pm_add_blog_post .all_errors').html(all_errors);
	var error = jQuery('#pm_add_blog_post .all_errors').html();
	if (error == '') {
            
            jQuery('input[name="pg_blog_submit"]').attr('disabled','disabled');
		return true;
	} else {
		return false;
	}
}

function load_more_pg_blogs(uid)
{
    jQuery('.pm-load-more-blogs').hide();
    jQuery('.pg-load-more-container .pm-loader').show();
    var page = parseInt(jQuery('#pg_next_blog_page').val());
    var nextpage = page +1;
    var data = {action: 'pm_load_pg_blogs', 'uid': uid,'page':page,'nonce': pm_ajax_object.nonce};
    jQuery.post(pm_ajax_object.ajax_url, data, function (response) {
        if(response)
        {
            jQuery('.pg-load-more-container .pm-loader').hide();
            jQuery('#pg_next_blog_page').val(nextpage);
            jQuery('#pg-blog-container').append(response);
        }
    });

}

function load_more_user_blogs_shortcode_posts(authors,posttypes)
{
    jQuery('.pm-load-more-blogs').hide();
    jQuery('.pg-load-more-container .pm-loader').show();
    var page = parseInt(jQuery('#pg_next_blog_page').val());
    var nextpage = page +1;
    var data = {action: 'pm_load_user_blogs_shortcode_posts',authors: authors, posttypes:posttypes, page:page, 'nonce': pm_ajax_object.nonce};
    jQuery.post(pm_ajax_object.ajax_url, data, function (response) {
        if(response)
        {
            jQuery('.pg-load-more-container .pm-loader').hide();
            jQuery('#pg_next_blog_page').val(nextpage);
            jQuery('#pg-user-blog-container').append(response);
        }
    });

}



function pm_delete_account_setting(form)
{
        var formid = form.id;
        jQuery('#'+formid+' .errortext').html('');
	jQuery('#'+formid+' .errortext').hide();
	jQuery('#'+formid+' .all_errors').html('');
	jQuery('#'+formid+' .warning').removeClass('warning');
        jQuery('#'+formid+' .pm_required').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
        var all_errors = '';
	jQuery('#'+formid+' .errortext').each(function () {
		var a = jQuery(this).html();
		all_errors = a + all_errors;
	});
        
        jQuery('#'+formid+' .all_errors').html(all_errors);
	var error = jQuery('#'+formid+' .all_errors').html();
	if (error == '') 
        {
		return true;
	} 
        else 
        {
		return false;
	}
        
}
function pm_save_account_setting(form)
{
    var email_val = "";
    var formid = form.id;
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    jQuery('#'+formid+' .errortext').html('');
	jQuery('#'+formid+' .errortext').hide();
	jQuery('#'+formid+' .all_errors').html('');
	jQuery('#'+formid+' .warning').removeClass('warning');
        jQuery('#'+formid+' .pm_required').each(function (index, element) {
		var value = jQuery(this).children('input').val();
		var value = jQuery.trim(value);
		if (value == "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('.errortext').show();
		}
	});
        
        jQuery('#'+formid+' .pm_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').html(pm_error_object.valid_email);
			jQuery(this).children('.errortext').show();
		}
	});
	
        var all_errors = '';
	jQuery('#'+formid+' .errortext').each(function () {
		var a = jQuery(this).html();
		all_errors = a + all_errors;
	});
        
        jQuery('#'+formid+' .all_errors').html(all_errors);
	var error = jQuery('#'+formid+' .all_errors').html();
	if (error == '') 
        {
		return true;
	} 
        else 
        {
		return false;
	}
        
}

(function PG_mobile_resposve ($) {
    var $window = $(window),
        $html = $('html'),
        $is_resize = 0;
        

    $window.resize(function resize() {
        if ($window.width() < 479) {
            if($is_resize==0)
            {
                show_pg_section_left_panel();
                $is_resize = 1;
            }
            
            return $html.addClass('pg-mobile-479');
        }
        else
        {
            $('.pm-section-left-panel').show();
            $('.pm-section-right-panel').show();
        }

        $html.removeClass('pg-mobile-479');
        
        if ($window.width() < 760) {
            return $html.addClass('pg-mobile-760');
            
        }
        
        $html.removeClass('pg-mobile-760');
        
        
         if ($window.width() < 979) {
            return $html.addClass('pg-mobile-979');
            
        }

        $html.removeClass('pg-mobile-979');
        
    }).trigger('resize');
})(jQuery);

function show_pg_section_right_panel()
{
    jQuery(".pg-mobile-479 .pm-section-right-panel").show();
    jQuery(".pg-mobile-479 .pm-section-left-panel").hide();
    jQuery(".pg-mobile-479 .pg-left-panel-icon").show();
}

function show_pg_section_left_panel()
{
    jQuery(".pg-mobile-479 .pm-section-right-panel").hide();
    jQuery(".pg-mobile-479 .pm-section-left-panel").show();
    jQuery(".pg-mobile-479 .pg-left-panel-icon").hide();
}

function pg_remove_user_group(uid,gid)
{
    var boxid = '#pg-user-group-box-'+gid;
    var data = {action:'pm_remove_user_group',uid:uid,gid:gid};
    jQuery('#pg_edit_group_html_container').html('<div class="pg-edit-group-popup-loader"><div class="pm-loader"></div></div>');
     var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery(".pm-loader").css('border-top-color', pmDomColor);
    jQuery.post(pm_ajax_object.ajax_url, data, function (resp) 
    {
        window.location.reload(true);
       /*if(resp=='success')
       {
            
            jQuery(boxid).remove();
            jQuery('.pm-popup-mask, #pm-edit-group-popup, .pg-blog-dialog-mask').hide();
            

       }*/
    });

}

function pg_open_group_tab()
{
  var child =  jQuery('.pm-profile-tab ul li.pg-group-tab');
var parent = jQuery('ul.pm-profile-tab-wrap');

     var i = 0;
      var tabindex=0;
       jQuery('ul.pm-profile-tab-wrap').children('li').each(function () {
           jQuery(this).find('a').removeClass("active");
        var obj = jQuery(this).find('a[href="#pg-groups"]'); // "this" is the current element in the loop
     if(obj.length)
        {
          jQuery('.pg-profile-tab-content').css("display","none");
          obj.addClass("active");
          tabindex = i;
       }
      
    i++;
  
});
 
 jQuery('#pg-groups').css("display","block");
}
