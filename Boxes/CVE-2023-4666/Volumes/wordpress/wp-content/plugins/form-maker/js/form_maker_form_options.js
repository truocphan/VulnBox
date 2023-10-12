jQuery(document).on('fm_tab_loaded', function () {
  fm_options_ready();
  fm_document_ready();
});

jQuery(document).on('fm_tab_email_loaded', function () {
  fm_email_options_ready();
});
function fm_options_ready() {
  jQuery('.filed_label').each(function() {
    if(document.getElementById("frontend_submit_fields").value == document.getElementById("all_fields").value)
      document.getElementById("all_fields").checked = true;
    if(inArray(this.value, document.getElementById("frontend_submit_fields").value.split(","))) {
      this.checked = true;
    }
  });

  jQuery('.stats_filed_label').each(function() {
    if(document.getElementById("frontend_submit_stat_fields").value == document.getElementById("all_stats_fields").value)
      document.getElementById("all_stats_fields").checked = true;
    if(inArray(this.value, document.getElementById("frontend_submit_stat_fields").value.split(","))) {
      this.checked = true;
    }
  });

  jQuery(document).on('change','input[name="all_fields"]',function() {
    jQuery('.filed_label').prop("checked" , this.checked);
  });

  jQuery(document).on('change','input[name="all_stats_fields"]',function() {
    jQuery('.stats_filed_label').prop("checked" , this.checked);
  });
  fm_toggle_options('#div_gdpr_checkbox_text', jQuery('input[name=gdpr_checkbox]:checked').val() == '1' ? true : false);

  // Bind filter action on entering search key and when the user cancel the input.
  jQuery(".placeholders-filter").on("keyup input", function() { filter_placeholders(this); });
  jQuery('#placeholders_overlay').on("click", function() { fm_placeholders_popup_close(); });

  // Close popup on escape.
  jQuery(document).on('keydown', function (e) {
    if (e.keyCode === 27) { /* Esc.*/
      if (jQuery("#placeholders_overlay").is(":visible")) {
        fm_placeholders_popup_close();
      }
    }
  });

  jQuery('.fm_condition_is_select').change(function () {
    show_hide_condition_input_field(this)
  })

  fm_remove_validate_error_message();
}

function fm_email_options_ready() {
  fm_toggle_options('.fm_email_options', jQuery('input[name=sendemail]:checked').val() == '1' ? true : false);

  // Bind filter action on entering search key and when the user cancel the input.
  jQuery(".placeholders-filter").on("keyup input", function() { filter_placeholders(this); });
  jQuery('#placeholders_overlay').on("click", function() { fm_placeholders_popup_close(); });

  // Close popup on escape.
  jQuery(document).on('keydown', function (e) {
    if (e.keyCode === 27) { /* Esc.*/
      if (jQuery("#placeholders_overlay").is(":visible")) {
        fm_placeholders_popup_close();
      }
    }
  });
  fm_remove_validate_error_message();
}

function fm_document_ready() {
  var fieldset_id = jQuery("#fieldset_id").val();
  form_maker_options_tabs(fieldset_id);
  fm_change_payment_method(jQuery('input[name=paypal_mode]:checked').val());
  if ( typeof fieldset_id != 'undefined' && fieldset_id == 'javascript' ) {
    setTimeout(function () {
      codemirror_for_javascript();
    }, 300);
  }
  fm_popup();
}

/**
 * Filter placeholders.
 *
 * @param that
 */
function filter_placeholders(that) {
  // Get search key.
  var search = jQuery(that).val().toLowerCase();
  // Remove previous serach results from filtered fields section.
  jQuery(".filtered-placeholders .inside").html("");
  if (search != "") {
    var found = false;
    // Hide all field sections.
    jQuery(".placeholders_cont .postbox").addClass("hide");
    jQuery(".placeholders .postbox:not(.filtered-placeholders) .wd-button").each(function () {
      var field_name = jQuery(this).html().toLowerCase();
      if (field_name.indexOf(search) != '-1') {
        jQuery(".filtered-placeholders .inside").append(jQuery(this).clone());
        found = true;
      }
    });
    // If nothing found.
    if (!found) {
      jQuery(".filtered-placeholders .inside").html(form_maker.nothing_found);
    }
    // Show search results in filtered fields section.
    jQuery(".placeholders_cont .filtered-placeholders").removeClass("hide");
  }
  else {
    jQuery(".placeholders_cont .postbox").removeClass("hide");
    jQuery(".placeholders_cont .filtered-placeholders").addClass("hide");
  }
}

function wd_fm_apply_options() {
  var tabs_loaded = JSON.parse(jQuery('#fm_tabs_loaded').val());
  if ( !inArray('form_options_tab', tabs_loaded) && !inArray('form_email_options_tab', tabs_loaded)) {
    return true;
  }

	var success = true;
	jQuery(".fm-validate").each(function() {
		var type = jQuery(this).data("type");
		var message = form_maker_manage.not_valid_value;

		if ( type == 'required' ) {
		  message = form_maker_manage.required_field;
    }
    else if ( type == 'email' ) {
		  message = form_maker_manage.not_valid_email;
    }
    message = "<p class='description fm-validate-description'>" + message + "</p>";

    var callback = jQuery(this).data("callback");
		var callbackParameter = jQuery(this).data("callback-parameter");
		var tabId = jQuery(this).data("tab-id");
		var contentId = jQuery(this).data("content-id");
		var value = jQuery(this).val();

		if ( typeof window[callback] == "function" && !window[callback](value, callbackParameter ) ) { /* Check validation.*/
			/* Change to tab with error in it.*/
      var active_tab = jQuery("#" + contentId).closest(".ui-tabs-panel");
      jQuery("#fm-tabs").tabs({
        active: jQuery(".ui-tabs-panel").index(active_tab)
      });

      if ( jQuery(".fm_fieldset_active:visible").length !== 0 ) {
        /* Remove active class from all subtabs.*/
        jQuery(".fm_fieldset_active").addClass("fm_fieldset_deactive").removeClass("fm_fieldset_active");
        jQuery(".fm_fieldset_tab").removeClass("active");
        /* Add active class to required subtab.*/
        jQuery("#" + contentId).removeClass("fm_fieldset_deactive").addClass("fm_fieldset_active");
        jQuery("#" + tabId).addClass("active");
        /* Change to subtab with error in it.*/
        jQuery("#fieldset_id").val(tabId);
      }
      /* Add error message to the field.*/
			if ( jQuery(this).parent().parent().find(".fm-validate-description").length === 0 ) {
			  var description_container = jQuery(this).parent().find(".description");
			  if ( description_container.length ) {
			    /* Show error message before description, if description container exist.*/
          description_container.before(message);
        }
        else {
          jQuery(this).parent().append(message);
        }

				jQuery(this).addClass("fm-validate-field");
			}
      jQuery('html, body').animate({
        scrollTop: jQuery(this).offset().top - 200
      }, 500);
			success = false;
			return false; /* To break loop.*/
		}
	});

	if ( success ) {
    set_condition();
  }
  return success;
}

// Remove validation errors on key press.
function fm_remove_validate_error_message(){
  jQuery(".fm-validate").each(function() {
    jQuery(this).on("keypress change", function () {
      jQuery(this).parent().find(".fm-validate-description").remove();
      jQuery(this).removeClass("fm-validate-field");
    });
  });
}

function fm_validate_email(value, obj) {
  if ( obj != "" && !jQuery(obj).is(':checked') ) { /* No need to check, if option is disabled.*/
    return true;
  }

  var emails = value.split(',');

  /* Regexp is also for Cyrillic alphabet */
  var re = /^[\u0400-\u04FFa-zA-Z0-9'.+_-]+@[\u0400-\u04FFa-zA-Z0-9.-]+\.[\u0400-\u04FFa-zA-Z]{2,61}$/;
  var rePlaceholder = /^({)[0-9]+(})$/;

  var allowed_placeholders = ['{adminemail}', '{useremail}'];

  for ( var i in emails ) {
    var email = emails[i].replace(/^\s+|\s+$/g, '');
    if ( !allowed_placeholders.includes(email) ) {
      if ( email && !re.test(email) && !rePlaceholder.test(email) ) {
        return false;
      }
    }
  }

  return true;
}

/* Used with Conditional Emails addon */
function fm_add_inline_email_validation_message(elem) {
  var emailsInput = jQuery(elem).parent().parent().find('.fm_email_to');
  var errorMess = emailsInput.parent().parent().find('.fm-validate-description');
  var value = emailsInput.val();
  if ( !fm_validate_email(value, '') ) {
    if ( emailsInput.hasClass("fm_email_to") && !errorMess.length ) {
      emailsInput.parent().after("<p class='description fm-validate-description'>" + form_maker_manage.not_valid_email + "</p>");
    }
    emailsInput.addClass("fm-validate-field");
    jQuery('html, body').animate({
      scrollTop: emailsInput.offset().top - 200
    }, 500);
    return false;
  }
  else {
    jQuery(".fm-validate-description").remove();
  }
  return true;
}

function fm_placeholders_popup(input_id) {
  var active_input = jQuery('#' + input_id);
  var active_input_container = active_input.closest('.wd-group');
      active_input_container.addClass('placeholders-active');
  var exclude = active_input_container.data('exclude-placeholder');
  jQuery('.fm-placeholder-item').show();
  if ( exclude && exclude.length > 1 ) {
    jQuery.each(exclude, function(i, placeholder) {
      jQuery('#fm-placeholder-'+ placeholder).hide();
    });
  }
  jQuery('html').animate({scrollTop: active_input_container.offset().top - 50}, 500);
  var popup = jQuery('.placeholder-popup');
  active_input_container.prepend(jQuery('#placeholders_overlay'));
  if (active_input_container.find('.wp-editor-wrap').length) {
    active_input_container.find('.wp-editor-wrap').append(popup);
  }
  else {
    active_input.after( popup );
    var orig_val = active_input.val();
    active_input.focus().val('').val(orig_val);
  }
  popup.show();
}

function fm_placeholders_popup_close() {
  var active_input = jQuery('#placeholders_overlay').closest('.wd-group');
      active_input.removeClass('placeholders-active');
  var overlay = jQuery('#placeholders_overlay');
  var popup = jQuery('.placeholder-popup');
  jQuery(overlay).appendTo('.fm-placeholders-popup-wrap');
  jQuery(popup).appendTo('.fm-placeholders-popup-wrap');
  popup.hide();
}

function set_condition() {
  field_condition = '';
  for ( i = 0; i < 500; i++ ) {
    conditions = '';
    if ( document.getElementById("condition" + i) ) {
      field_condition += document.getElementById("show_hide" + i).value + "*:*show_hide*:*";
      field_condition += document.getElementById("fields" + i).value + "*:*field_label*:*";
      field_condition += document.getElementById("all_any" + i).value + "*:*all_any*:*";
      for ( k = 0; k < 500; k++ ) {
        if ( document.getElementById("condition_div" + i + "_" + k) ) {
          conditions += document.getElementById("field_labels" + i + "_" + k).value + "***";
          conditions += document.getElementById("is_select" + i + "_" + k).value + "***";
          if ( document.getElementById("field_value" + i + "_" + k).tagName == "SELECT" ) {
            if ( document.getElementById("field_value" + i + "_" + k).getAttribute('multiple') ) {
              var sel = document.getElementById("field_value" + i + "_" + k);
              var selValues = '';
              for ( m = 0; m < sel.length; m++ ) {
                if ( sel.options[m].selected ) {
                  selValues += sel.options[m].value + "@@@";
                }
              }
              conditions += selValues;
            }
            else {
              conditions += document.getElementById("field_value" + i + "_" + k).value;
            }
          }
          else {
            conditions += fm_html_entities(document.getElementById("field_value" + i + "_" + k).value);
          }
          conditions += "*:*next_condition*:*";
        }
      }
      field_condition += conditions;
      field_condition += "*:*new_condition*:*";
    }
  }
  if ( jQuery('#condition').length ) {
    document.getElementById('condition').value = field_condition;
  }
}

function show_verify_options(s){
	if(s){
		jQuery(".verification_div").removeAttr( "style" );
		jQuery(".expire_link").removeAttr( "style" );

	} else{
		jQuery(".verification_div").css( 'display', 'none' );
		jQuery(".expire_link").css( 'display', 'none' );
	}
}
		
function inArray(needle, myarray) {
	var length = myarray.length;
	for(var i = 0; i < length; i++) {
		if(myarray[i] == needle) return true;
	}
	return false;
}
		
function checkAllByParentId(id) {
	var checkboxes = document.getElementById(id).getElementsByTagName('input');					
	if (checkboxes[0].checked) {
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].type == 'checkbox') {
				 checkboxes[i].checked = true;
			}
		}
	} else {
		 for (var i = 0; i < checkboxes.length; i++) {
			 if (checkboxes[i].type == 'checkbox') {
				 checkboxes[i].checked = false;
			 }
		 }
	}
}
	
function checked_labels(class_name) {								
	var checked_ids ='';
	jQuery('.'+class_name).each(function() {
	  if(this.checked) {
		checked_ids += this.value+',';
	  }
	});

	if(class_name == 'filed_label') {
		document.getElementById("frontend_submit_fields").value = checked_ids;
		if(checked_ids == document.getElementById("all_fields").value)
			document.getElementById("all_fields").checked = true;
		else
			document.getElementById("all_fields").checked = false;
	}
	else {
	  document.getElementById("frontend_submit_stat_fields").value = checked_ids;
	  if(checked_ids == document.getElementById("all_stats_fields").value)
		document.getElementById("all_stats_fields").checked = true;
	  else
		document.getElementById("all_stats_fields").checked = false;
	}
}			

function codemirror_for_javascript() {
  if ( !jQuery("#form_javascript").next().length && typeof CodeMirror !== 'undefined' ) {
    var editor = CodeMirror.fromTextArea(document.getElementById("form_javascript"), {
      lineNumbers: true,
      lineWrapping: true,
      mode: "javascript"
    });
    if( typeof editor.autoFormatRange !== 'undefined' ) {
      CodeMirror.commands["selectAll"](editor);
      editor.autoFormatRange(editor.getCursor(true), editor.getCursor(false));
      editor.scrollTo(0, 0);
    }
  }
}

function fm_toggle_options(selector, show) {
  if (show) {
    jQuery(selector).show();
  }
  else {
    jQuery(selector).hide();
  }
}

/**
 * Show/hide condition's input field.
 *
 * @param {Object} context element
 */
function show_hide_condition_input_field(context) {
  var key = jQuery(context).val();
  var selects = new Array('empty', 'checked', 'unchecked', '!', '=');
  if ( jQuery.inArray(key, selects) !== -1 ) {
    jQuery(context).parent().find('.fm_condition_field_input_value').hide();
    jQuery(context).parent().find('.fm_condition_field_select_value').hide();
    jQuery(context).parent().find('.fm_condition_field_input_notice').hide();
  }
  else {
    jQuery(context).parent().find('.fm_condition_field_input_value').show();
    jQuery(context).parent().find('.fm_condition_field_select_value').show();
    jQuery(context).parent().find('.fm_condition_field_input_notice').show();
  }
}