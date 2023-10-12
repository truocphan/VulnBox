function remove_whitespace(node) {
  var ttt;
  for (ttt = 0; ttt < node.childNodes.length; ttt++) {
    if (node.childNodes[ttt] && node.childNodes[ttt].nodeType == '3' && !/\S/.test(node.childNodes[ttt].nodeValue)) {
      node.removeChild(node.childNodes[ttt]);
      ttt--;
    }
    else {
      if (node.childNodes[ttt].childNodes.length) {
        remove_whitespace(node.childNodes[ttt]);
      }
    }
  }
  return;
}

function fm_row_handle(section) {
  var fm_section = jQuery(section);
  fm_section.find('.wdform_row_handle').remove();
  var row_handle = jQuery('<div class="wdform_row_handle">' +
    '<span class="fm-ico-draggable"></span>' +
    '<span title="Remove the column" class="page_toolbar fm-ico-delete" onclick="fm_remove_row_popup(this);"></span>' +
    '<span class="add-new-field" onclick="jQuery(\'#cur_column\').removeAttr(\'id\');jQuery(this).parent().parent().attr(\'id\', \'cur_column\').val(1);popup_ready(); Enable(); return false;">' + form_maker_manage.add_new_field + '</span>' +
    '<div class="fm-divider"></div>' +
    '</div>');
  fm_section.prepend(row_handle);
  row_handle.after('<div class="fm-section-overlay"></div>');
}

function sortable_columns() {
  jQuery( "#take" ).sortable({
    cursor: 'move',
    placeholder: "highlight",
    tolerance: "pointer",
    handle: ".form_id_tempform_view_img .fm-ico-draggable",
    items: "> .wdform-page-and-images",
    axis: "y",
		update: function(event, ui) {
      refresh_page_numbers();
    },
  });
  jQuery( ".wdform_page" ).sortable({
    connectWith: ".wdform_page",
    cursor: 'move',
    placeholder: "highlight",
    tolerance: "pointer",
    handle: ".wdform_row_handle",
    cancel: ".add-new-field, .page_toolbar",
    items: "> .wdform_section",
    create: function( event, ui ) {
      jQuery(event.target).find('.wdform_section').each(function() {
        fm_row_handle(this);
      });
    },
    start: function( event, ui ) {
      jQuery('.wdform_row_empty').hide();
    },
    stop: function( event, ui ) {
      fm_rows_refresh();
      jQuery('.wdform_row_empty').show();
    },
  });
  jQuery( ".wdform_column" ).sortable({
		connectWith: ".wdform_column",
		cursor: 'move',
		placeholder: "highlight",
    tolerance: "pointer",
    cancel: ".wdform_section_handle",
    items: "> .wdform_row, #add_field",
		start: function(e, ui) {
      jQuery(".add-new-button").off("click");
      jQuery(".wdform_column").removeClass("fm-hidden");
			jQuery("#cur_column").removeAttr("id");
    },
		stop: function(event, ui) {
		  // Prevent dropping on "New Field" conatiner.
		  if (ui.item.parent().attr("id") == "add_field_cont") {
		    return false;
      }
      if (ui.item.attr("id") == "add_field" && ui.item.parent().attr("id") != "add_field_cont") {
        if (fm_check_something_really_important()) return false;
        nextID = jQuery("#add_field").next(".wdform_row").attr("wdid"); //find next row id for position
				jQuery("#add_field").parent().attr("id", "cur_column");  // add id cur_column to this column
        popup_ready();
				Enable();
        /* In Firfox and Safari click action is working during the drag and drop also */
				jQuery(".add-new-button").removeAttr("onclick");
				return false;
			}
      jQuery(".wdform_column:not(#add_field_cont):empty").addClass("fm-hidden");
      fm_columns_refresh();
		}
  });
}

function all_sortable_events() {
  fm_rows_refresh();
  fm_columns_refresh();
  jQuery(".wdform_row, .wdform_tr_section_break").off("mouseover touchstart").on("mouseover touchstart", function (event) {
    if (!jQuery(this).find('.wdform_arrows').is(':visible')) {
      jQuery('.wdform_arrows').hide();
      jQuery(this).find('.wdform_arrows').show();
      event.preventDefault();
      return false;
    }
  }).off("mouseleave").on("mouseleave", function () {
    jQuery(this).find('.wdform_arrows').hide();
  });
  jQuery(".wdform_section_handle, .wdform_row_handle").off("mouseover touchstart").on("mouseover touchstart", function (event) {
    jQuery(this).parent().addClass('fm-hover');
  }).off("mouseleave").on("mouseleave", function () {
    jQuery(this).parent().removeClass('fm-hover');
  });
}

jQuery(document).on( "dblclick", ".wdform_row, .wdform_tr_section_break", function() {
	edit(jQuery(this).attr("wdid"));
});

function fm_change_radio(elem) {
	if(jQuery( elem ).hasClass( "fm-yes" )) {
		jQuery( elem ).val('0');
		jQuery( elem ).next().val('0');
		jQuery( elem ).removeClass('fm-yes').addClass('fm-no');
		jQuery(elem).find("span").animate({
			right: parseInt(jQuery( elem ).css( "width")) - 14 + 'px'
		}, 400, function() {
		}); 
	}	
	else {
		jQuery( elem ).val('1');
		jQuery( elem ).next().val('1');
		jQuery(elem).find("span").animate({
			right: 0
		}, 400, function() {
			jQuery( elem ).removeClass('fm-no').addClass('fm-yes');
		}); 
	}	
	if(jQuery( elem ).next().attr('name') == 'mail_verify') {
		show_verify_options(jQuery( elem ).val() == 1 ? true : false);
	}	
}
		
function enable_drag() {
	jQuery('.wdform_column').sortable( "enable" );
	jQuery('.wdform_arrows_advanced').hide();
	jQuery( ".wdform_field" ).css("cursor", "");
	jQuery( "#add_field .wdform_field" ).css("cursor", "");
	all_sortable_events();
}

function refresh_() {
	document.getElementById('counter').value = gen;
  jQuery('.wdform-page-and-images').each(function () {
    var cur_page = jQuery(this);
    cur_page.find('[id^=page_next_]').removeAttr('src');
    cur_page.find('[id^=page_previous_]').removeAttr('src');
    cur_page.find('.form_id_tempform_view_img').remove();
  });
  jQuery("#take div").removeClass("ui-sortable ui-sortable-disabled ui-sortable-handle");
	jQuery( "#add_field_cont" ).remove(); // remove add new button from div content
	document.getElementById('form_front').value = fm_base64EncodeUnicode(fm_htmlentities(document.getElementById('take').innerHTML));
}
function fm_base64EncodeUnicode(str) {
  // First we escape the string using encodeURIComponent to get the UTF-8 encoding of the characters,
  // then we convert the percent encodings into raw bytes, and finally feed it to btoa() function.
  utf8Bytes = encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
    return String.fromCharCode('0x' + p1);
  });
  return btoa(utf8Bytes);
}
function fm_htmlentities(s){
  var div = document.createElement('div');
  var text = document.createTextNode(s);
  div.style.cssText = "display:none";
  div.appendChild(text);
  return div.innerHTML;
}

function fm_add_submission_email(toAdd_id, value_id, parent_id, cfm_url) {
  var value = jQuery("#" + value_id).val();
  if (value) {
    var mail_div = jQuery("<p>").attr("class", "fm_mail_input").prependTo("#" + parent_id);
    jQuery("<span>").attr("class", "mail_name").text(value).appendTo(mail_div);
    jQuery("<span>").attr("class", "dashicons dashicons-trash").attr("onclick", "fm_delete_mail(this, '" + value + "')").attr("title", "Delete Email").appendTo(mail_div);
    jQuery("#" + value_id).val("");
    jQuery("#" + toAdd_id).val(jQuery("#" + toAdd_id).val() + value + ",");
  }
}

function fm_delete_mail(img, value) {
  jQuery(img).parent().remove();
  jQuery("#mail").val(jQuery("#mail").val().replace(value + ',', ''));
}

function form_maker_options_tabs(id) {
	jQuery("#fieldset_id").val(id);
	jQuery(".fm_fieldset_active").removeClass("fm_fieldset_active").addClass("fm_fieldset_deactive");
	jQuery("#" + id + "_fieldset").removeClass("fm_fieldset_deactive").addClass("fm_fieldset_active");
	jQuery(".fm_fieldset_tab").removeClass("active");
	jQuery("#" + id).addClass("active");

  return false;
}

function set_type(type) {
	switch (type) {
		case 'post':
			document.getElementById('post').removeAttribute('style');
			document.getElementById('page').setAttribute('style', 'display:none');
			document.getElementById('custom_text').setAttribute('style', 'display:none');
			document.getElementById('url_wrap').setAttribute('style', 'display:none');
			break;
		case 'page':
			document.getElementById('page').removeAttribute('style');
			document.getElementById('post').setAttribute('style', 'display:none');
			document.getElementById('custom_text').setAttribute('style', 'display:none');
			document.getElementById('url_wrap').setAttribute('style', 'display:none');
			break;
		case 'custom_text':
			document.getElementById('page').setAttribute('style', 'display:none');
			document.getElementById('post').setAttribute('style', 'display:none');
			document.getElementById('custom_text').removeAttribute('style');
			document.getElementById('url_wrap').setAttribute('style', 'display:none');
			break;
		case 'url_wrap':
			document.getElementById('page').setAttribute('style', 'display:none');
			document.getElementById('post').setAttribute('style', 'display:none');
			document.getElementById('custom_text').setAttribute('style', 'display:none');
			document.getElementById('url_wrap').removeAttribute('style');
			break;
		case 'none':
			document.getElementById('page').setAttribute('style', 'display:none');
			document.getElementById('post').setAttribute('style', 'display:none');
			document.getElementById('custom_text').setAttribute('style', 'display:none');
			document.getElementById('url_wrap').setAttribute('style', 'display:none');
			break;
	}
}

function check_isnum(e) {
  var chCode1 = e.which || e.keyCode;
  if ( chCode1 > 31
		&& (chCode1 < 48 || chCode1 > 57)
		&& (chCode1 != 46)
		&& (chCode1 != 45)
		&& (chCode1 < 35 || chCode1 > 40) ) {
    return false;
  }
  return true;
}

// Check Email.
function fm_check_email(id) {
  if (document.getElementById(id) && jQuery('#' + id).val() != '') {
    var email_array = jQuery('#' + id).val().split(',');
	/* Regexp is also for Cyrillic alphabet */
	var re = /^[\u0400-\u04FFa-zA-Z0-9.+_-]+@[\u0400-\u04FFa-zA-Z0-9.-]+\.[\u0400-\u04FFa-zA-Z]{2,61}$/;
    for (var email_id = 0; email_id < email_array.length; email_id++) {
      var email = email_array[email_id].replace(/^\s+|\s+$/g, '');
      if ( email && ! re.test( email ) && email.indexOf('{') === -1 ) {
        alert('This is not a valid email address.');

        /*  Do only if there is active class */
        if( jQuery('#submenu li a').hasClass('active') ) {
            var activeTabId = jQuery("#submenu .active").attr("id");
            var error_cont_id = jQuery('#' + id).closest(".fm_fieldset_deactive").attr("id");

            if(typeof error_cont_id != 'undefined') {
              var error_tab_id = error_cont_id.split("_fieldset");
              tab_id = error_tab_id[0];

              /* If current active and error active tabs are the same */
              if ( activeTabId !=  tab_id ) {
                var activeContentId = activeTabId +"_fieldset";
                jQuery("#"+activeContentId).removeClass("fm_fieldset_active");
                jQuery("#"+activeContentId).removeClass("fm_fieldset_deactive");
                jQuery("#" + error_cont_id).addClass("fm_fieldset_active");
                jQuery("#submenu .active").removeClass('active');
                jQuery("#" + tab_id).addClass("active");
              }
            }
        } else {
            var error_cont_id = jQuery('#' + id).closest(".fm_fieldset_deactive").attr("id");
            if(typeof error_cont_id != 'undefined') {
              var tab_id = error_cont_id.split("_fieldset");
              tab_id = tab_id[0];

              jQuery("#" + error_cont_id).removeClass("fm_fieldset_deactive");
              jQuery("#" + error_cont_id).addClass("fm_fieldset_active");
              jQuery("#" + tab_id).addClass("active");
            }
        }

        jQuery('#' + id).css('border', '1px solid #FF0000');
        jQuery('#' + id).focus();
        jQuery('html, body').animate({
          scrollTop:jQuery('#' + id).offset().top - 200
        }, 500);
        return true;
      }
    }
		jQuery('#' + id).css('border', '1px solid #ddd');
  }

  return false;
}

function wdhide(id) {
	document.getElementById(id).style.display = "none";
}

function wdshow(id) {
	document.getElementById(id).style.display = "block";
}

function delete_field_condition( id ) {
	var cond_id = id.split('_');
	document.getElementById('condition' + cond_id[0]).removeChild(document.getElementById('condition_div' + id));
	if ( jQuery('#condition' + cond_id[0] + ' .fm_condition_div').length == 0 ) {
		jQuery('#fields' + cond_id[0]).prop('disabled', false);
	}
}

function add_condition() {
	var conditions = jQuery('#fm-conditions-json').data('conditions-json');
	var	ids_index = conditions.ids_index;
	var	ids = conditions.ids;
	var	all_ids = conditions.all_ids;
	var	labels = conditions.labels;
	var	all_labels = conditions.all_labels;
	var	types = conditions.types;
	var	params = conditions.params;

	var	ids_for_match = conditions.ids_for_match;
	var	all_ids_for_match = conditions.all_ids_for_match;
	var	labels_for_match = conditions.labels_for_match;
	var	all_labels_for_match = conditions.all_labels_for_math;
	var	types_for_match = conditions.types_for_match;
	var	params_for_match = conditions.params_for_match;
	for ( i = 500; i >= 0; i-- ) {
		if ( document.getElementById('condition' + i) ) {
			break;
		}
	}
	num = i + 1;
	var condition_div = document.createElement('div');
			condition_div.setAttribute("id", "condition" + num);
			condition_div.setAttribute("class", "fm_condition");
	var conditional_fields_div = document.createElement('div');
			conditional_fields_div.setAttribute("id", "conditional_fileds" + num);
	var show_hide_select = document.createElement('select');
			show_hide_select.setAttribute("id", "show_hide" + num);
			show_hide_select.setAttribute("name", "show_hide" + num);
			show_hide_select.setAttribute("class", "fm_condition_show_hide");
	var show_option = document.createElement('option');
			show_option.setAttribute("value", "1");
			show_option.innerHTML = "Show";
	var hide_option = document.createElement('option');
			hide_option.setAttribute("value", "0");
			hide_option.innerHTML = "Hide";
			show_hide_select.appendChild(show_option);
			show_hide_select.appendChild(hide_option);
	var fields_select = document.createElement('select');
			fields_select.setAttribute("id", "fields" + num);
			fields_select.setAttribute("name", "fields" + num);
			fields_select.setAttribute("class", "fm_condition_fields");
			jQuery.each( labels, function( index, value ) {
				var fields_option = document.createElement('option');
					fields_option.setAttribute('value', ids[index]);
					fields_option.innerHTML = replaceQuote( value );
					fields_select.appendChild(fields_option);
			});
	var span = document.createElement('span');
			span.innerHTML = 'if';
	var all_any_select = document.createElement('select');
			all_any_select.setAttribute("id", "all_any" + num);
			all_any_select.setAttribute("name", "all_any" + num);
			all_any_select.setAttribute("class", "fm_condition_all_any");
	var all_option = document.createElement('option');
			all_option.setAttribute("value", "and");
			all_option.innerHTML = "all";
	var any_option = document.createElement('option');
			any_option.setAttribute("value", "or");
			any_option.innerHTML = "any";
			all_any_select.appendChild(all_option);
			all_any_select.appendChild(any_option);
	var span1 = document.createElement('span');
			span1.style.maxWidth = '235px';
			span1.style.width = '100%';
			span1.style.display = 'inline-block';
			span1.innerHTML = 'of the following match:';
	var span_json = document.createElement('span');
			span_json.setAttribute('id', 'fm-condition-fields-' + num);
			span_json.setAttribute('data-conditions-field-ids-index', JSON.stringify(ids_index));
			span_json.setAttribute('data-conditions-field-ids', JSON.stringify(ids));
			span_json.setAttribute('data-conditions-field-all-ids', JSON.stringify(all_ids));
			span_json.setAttribute('data-conditions-field-labels', JSON.stringify(labels) );
			span_json.setAttribute('data-conditions-field-all-labels', JSON.stringify(all_labels) );
			span_json.setAttribute('data-conditions-field-types', JSON.stringify(types));
			span_json.setAttribute('data-conditions-field-params', JSON.stringify(params));
			span_json.setAttribute('data-conditions-field-ids_for_match', JSON.stringify(ids_for_match));
			span_json.setAttribute('data-conditions-field-all-ids_for_match', JSON.stringify(all_ids_for_match));
			span_json.setAttribute('data-conditions-field-labels_for_match', JSON.stringify(labels_for_match) );
			span_json.setAttribute('data-conditions-field-types_for_match', JSON.stringify(types_for_match));
			span_json.setAttribute('data-conditions-field-params_for_match', JSON.stringify(params_for_match));

	var add_icon = document.createElement('span');
			add_icon.setAttribute('class', 'dashicons dashicons-plus-alt');
			add_icon.setAttribute('onClick', 'add_condition_fields("' + num + '")');
	var delete_icon = document.createElement('span');
			delete_icon.setAttribute('class', 'dashicons dashicons-trash');
			delete_icon.setAttribute('onClick', 'delete_condition("' + num + '")');
			conditional_fields_div.appendChild(show_hide_select);
			conditional_fields_div.appendChild(document.createTextNode(' '));
			conditional_fields_div.appendChild(fields_select);
			conditional_fields_div.appendChild(document.createTextNode(' '));
			conditional_fields_div.appendChild(span);
			conditional_fields_div.appendChild(document.createTextNode(' '));
			conditional_fields_div.appendChild(all_any_select);
			conditional_fields_div.appendChild(document.createTextNode(' '));
			conditional_fields_div.appendChild(span1);
			conditional_fields_div.appendChild(document.createTextNode(' '));
			conditional_fields_div.appendChild(delete_icon);
			conditional_fields_div.appendChild(document.createTextNode(' '));
			conditional_fields_div.appendChild(span_json);
			conditional_fields_div.appendChild(add_icon);
	condition_div.appendChild(conditional_fields_div);
	document.getElementById('conditions_fieldset_wrap').appendChild(condition_div);
}

function delete_condition(num) {
	document.getElementById('conditions_fieldset_wrap').removeChild(document.getElementById('condition'+num));
}

function replaceQuote( str ) {
	if ( typeof str !== 'undefined' && str !== '' ) {
		str = str.replaceAll('%quot%', "'");
		str = str.replaceAll('%dquot%', '"');
		return str;
	}
	return str;
}
/**
 * Return aveable is_select options for current type in array.
 *
 * @param {String} type field's type
 * @returns {Object[]} HTML tag array
 */
function is_select_options_HTML_array(type) {
	// @TODO. Returns the condition field values depending on the type.
	var is_select_options_html_array = [];
	var available_conditions = supported_is_select_conditions_of_type(type);
	// used for type_slider.
	if ( available_conditions[0] === true) {
		var is_option = document.createElement('option');
		is_option.setAttribute('id', 'is');
		is_option.setAttribute('value', "==");
		is_option.innerHTML = "is";
		is_select_options_html_array.push(is_option)
		var is_notoption = document.createElement('option');
		is_notoption.setAttribute('id', 'is_not');
		is_notoption.setAttribute('value', '!=');
		is_notoption.innerHTML = 'is not';
		is_select_options_html_array.push(is_notoption)
	}
	// used for type_slider.
	if ( available_conditions[1] === true) {
		var is_likoption = document.createElement('option');
		is_likoption.setAttribute("id", "like");
		is_likoption.setAttribute("value", "%");
		is_likoption.innerHTML = "like";
		is_select_options_html_array.push(is_likoption)
		var is_notlikoption = document.createElement('option');
		is_notlikoption.setAttribute("id", "not_like");
		is_notlikoption.setAttribute("value", "!%");
		is_notlikoption.innerHTML = "not like";
		is_select_options_html_array.push(is_notlikoption)
	}
	// used for type_file_upload.
	if ( available_conditions[2] === true) {
		var is_emptyoption = document.createElement('option');
		is_emptyoption.setAttribute("id", "empty");
		is_emptyoption.setAttribute("value", "=");
		is_emptyoption.innerHTML = "empty";
		is_select_options_html_array.push(is_emptyoption)
		var is_notemptyoption = document.createElement('option');
		is_notemptyoption.setAttribute("id", "not_empty");
		is_notemptyoption.setAttribute("value", "!");
		is_notemptyoption.innerHTML = "not empty";
		is_select_options_html_array.push(is_notemptyoption)
	}
	// used for type_send_copy.
	if ( available_conditions[3] === true) {
		var checked_option = document.createElement('option');
		checked_option.setAttribute("id", "checked");
		checked_option.setAttribute("value", "checked");
		checked_option.innerHTML = "Checked";
		is_select_options_html_array.push(checked_option)
		var unchecked_option = document.createElement('option');
		unchecked_option.setAttribute("id", "unchecked");
		unchecked_option.setAttribute("value", "unchecked");
		unchecked_option.innerHTML = "Unchecked";
		is_select_options_html_array.push(unchecked_option)
	}
	// used for type_range.
	if ( available_conditions[4] === true) {
		var in_range_option = document.createElement('option');
		in_range_option.setAttribute("id", "in_range");
		in_range_option.setAttribute("value", "in_range");
		in_range_option.innerHTML = "In Range";
		is_select_options_html_array.push(in_range_option);
		var out_range_option = document.createElement('option');
		out_range_option.setAttribute("id", "out_range");
		out_range_option.setAttribute("value", "out_range");
		out_range_option.innerHTML = "Out of range";
		is_select_options_html_array.push(out_range_option);
		var empty_option = document.createElement('option');
		empty_option.setAttribute("id", "empty");
		empty_option.setAttribute("value", "empty");
		empty_option.innerHTML = "Empty";
		is_select_options_html_array.push(empty_option);
	}

	return is_select_options_html_array;
}

/**
 * @param {String} type field's type
 * @returns {boolean[]} array with aveable conditions
 */
function supported_is_select_conditions_of_type( type ) {
	/*
		@TODO.
		The true key of the array defines the operation of that type.
		This means that if a new type is added, the false key must be added to all types already.
		To make this new operation work only for that type.
	*/
	switch ( type ) {
		case 'type_range':
			available_conditions = [ false, false, false, false, true ];
			break;
			case 'type_send_copy':
			available_conditions = [ false, false, false, true, false ];
			break;
		case 'type_file_upload':
			available_conditions = [ false, false, true, false, false ];
			break;
		case 'type_slider':
			available_conditions = [ true, true, false, false, false ];
			break;
		case 'type_date_fields':
			available_conditions = [ true, false, true, false, false ];
			break;
		default:
			available_conditions = [ true, true, true, false, false ];
	}

	return available_conditions;
}

function add_condition_fields( num ) {
	var index_of_field = 0;
	var condition_field_value = document.getElementById('fields' + num).value;
	var ids_index = jQuery('#fm-condition-fields-' + num).data('conditions-field-ids-index');
	var ids = jQuery('#fm-condition-fields-' + num).data('conditions-field-ids_for_match');
	var all_ids = jQuery('#fm-condition-fields-' + num).data('conditions-field-all-ids_for_match');
	var labels = jQuery('#fm-condition-fields-' + num).data('conditions-field-labels_for_match');
	var types = jQuery('#fm-condition-fields-' + num).data('conditions-field-types_for_match');
	var params = jQuery('#fm-condition-fields-' + num).data('conditions-field-params_for_match');

	var select_fields = jQuery('#fields' + num);
			select_fields.prop('disabled', true);
	for ( i = 500; i >= 0; i-- ) {
		if ( document.getElementById('condition_div' + num + '_' + i) ) {
			break;
		}
	}
	m = i + 1;

	var condition_div = document.createElement('div');
			condition_div.setAttribute('id', 'condition_div' + num + '_' + m);
			condition_div.setAttribute('class', 'fm_condition_div');
	var labels_select = document.createElement('select');
			labels_select.setAttribute('id', 'field_labels' + num + '_' + m);
			labels_select.setAttribute('onchange', "change_choices(options[selectedIndex].id+'_" + m + "')");
			labels_select.setAttribute('class', 'fm_condition_field_labels');
	var label_counter = 0;
	var types_for_match = [];
			jQuery.each(labels, function ( key, value ) {
				value = replaceQuote( value );
				if ( condition_field_value != ids[key] ) {
					types_for_match.push(types[Object.keys(types)[key]]);
					if ( label_counter == 0 ) {
						index_of_field = key;
					}
					var labels_option = document.createElement('option');
							labels_option.setAttribute('id', num + '_' + key);
							labels_option.setAttribute('value', ids[key]);
							labels_option.innerHTML = value;
							labels_select.appendChild(labels_option);
					label_counter++;
				}
			});
			condition_div.appendChild(labels_select);
			condition_div.appendChild(document.createTextNode(' '));
			var is_select = document.createElement('select');
			is_select.setAttribute('id', 'is_select' + num + '_' + m);
			is_select.setAttribute('class', 'fm_condition_is_select');

			var is_select_options_html_array = is_select_options_HTML_array(types_for_match[0])

			jQuery.each(is_select_options_html_array, function(key, value) {
				is_select.appendChild(value)
			})

			condition_div.appendChild(is_select);
			condition_div.appendChild(document.createTextNode(' '));
			params[index_of_field] = replaceQuote(params[index_of_field]);
	switch ( types[index_of_field] ) {
		case "type_text":
		case "type_star_rating":
		case "type_password":
		case "type_textarea":
		case "type_name":
		case "type_submitter_mail":
		case "type_phone":
		case "type_number":
		case "type_paypal_price":
		case "type_paypal_price_new":
		case "type_spinner":
		case "type_date_new":
		case "type_date_fields":
		case "type_phone_new":
		case "type_time":
		case "type_editor":
		case "type_send_copy":
		case "type_range":
		case "type_file_upload":
		case "type_slider":
		case "type_hidden":
			if ( types[index_of_field] == "type_number" || types[index_of_field] == "type_phone" ) {
				var keypress_function = "return check_isnum_space(event)";
			}
			else if ( types[index_of_field] == "type_paypal_price" || types[index_of_field] == "type_paypal_price_new" ) {
				var keypress_function = "return check_isnum_point(event)";
			}
			else {
				var keypress_function = "";
			}
			var label_input = document.createElement('input');
			label_input.setAttribute("id", "field_value" + num + '_' + m);
			label_input.setAttribute("type", "text");
			label_input.setAttribute("value", "");
			label_input.setAttribute("class", "fm_condition_field_input_value");
			var supported_conditions = supported_is_select_conditions_of_type(types[index_of_field]);
			// Hides the 'condition_field_input_value' field depending on the type.
			if ( supported_conditions[0] === false && supported_conditions[1] === false ) {
				label_input.setAttribute("style", "display: none");
			}
			if ( types[index_of_field] == "type_range" ) {
				label_input.setAttribute("style", "display: inline-block");
			}
			label_input.setAttribute("onKeyPress", keypress_function);
			condition_div.appendChild(label_input);
			if ( types[index_of_field] == "type_time" ) {
				var hint = document.createElement('label');
				hint.innerText = "Please use HH:MM format for 24-hour time (e.g. 22:15), and HH:MM:AM or HH:MM:PM for 12-hour time (e.g. 05:20:AM / 07:30:PM).";
				hint.style.margin = "0 3px";
				condition_div.appendChild(hint);
			}
			break;

		case "type_checkbox":
		case "type_radio":
		case "type_own_select":
			if ( types[index_of_field] == "type_own_select" ) {
				w_size = params[index_of_field].split('*:*w_size*:*');
			}
			else {
				w_size = params[index_of_field].split('*:*w_flow*:*');
			}
			w_choices = w_size[1].split('*:*w_choices*:*');
			w_choices_array = w_choices[0].split('***');
			// case for enabled use_for_submission
			if ( w_size[1].indexOf('*:*w_use_for_submission*:*') !== -1 ) {
				w_value_disabled = w_size[1].split('*:*w_use_for_submission*:*');
				w_choices_value = w_value_disabled[1].split('*:*w_choices_value*:*');
				w_choices_value_array = w_choices_value[0].split('***');
			}
			else if ( w_size[1].indexOf('*:*w_value_disabled*:*') !== -1 ) {
				w_value_disabled = w_size[1].split('*:*w_value_disabled*:*');
				w_choices_value = w_value_disabled[1].split('*:*w_choices_value*:*');
				w_choices_value_array = w_choices_value[0].split('***');
			}
			else {
				w_choices_value_array = w_choices_array;
			}
			var choise_select = document.createElement('select');
			choise_select.setAttribute("class", "fm_condition_field_select_value");
			choise_select.setAttribute("id", "field_value" + num + '_' + m);
			choise_select.style.cssText = "vertical-align: top; width:200px;";
			if ( types[index_of_field] == "type_checkbox" ) {
				choise_select.setAttribute('multiple', 'multiple');
				choise_select.setAttribute('class', 'multiple_select');
			}
			for ( k = 0; k < w_choices_array.length; k++ ) {
				var choise_option = document.createElement('option');
				choise_option.setAttribute("id", "choise_" + num + '_' + k);
				choise_option.setAttribute("value", w_choices_value_array[k]);
				choise_option.innerHTML = w_choices_array[k];
				if ( w_choices_array[k].indexOf('[') === -1 && w_choices_array[k].indexOf(']') === -1 ) {
					choise_select.appendChild(choise_option);
				}
			}
			condition_div.appendChild(choise_select);
			break;

		case "type_paypal_select":
		case "type_paypal_checkbox":
		case "type_paypal_radio":
		case "type_paypal_shipping":
			if ( types[index_of_field] == "type_paypal_select" ) {
				w_size = params[index_of_field].split('*:*w_size*:*');
			}
			else {
				w_size = params[index_of_field].split('*:*w_flow*:*');
			}
			w_choices = w_size[1].split('*:*w_choices*:*');
			w_choices_array = w_choices[0].split('***');
			w_choices_price = w_choices[1].split('*:*w_choices_price*:*');
			w_choices_price_array = w_choices_price[0].split('***');
			var choise_select = document.createElement('select');
			choise_select.setAttribute("id", "field_value" + num + '_' + m);
			choise_select.style.cssText = "vertical-align: top; width:200px;";
			if ( types[index_of_field] == "type_paypal_checkbox" ) {
				choise_select.setAttribute('multiple', 'multiple');
				choise_select.setAttribute('class', 'multiple_select');
			}
			for ( k = 0; k < w_choices_array.length; k++ ) {
				var choise_option = document.createElement('option');
				choise_option.setAttribute("id", "choise_" + num + '_' + k);
				choise_option.setAttribute("value", w_choices_array[k] + '*:*value*:*' + w_choices_price_array[k]);
				choise_option.innerHTML = w_choices_array[k];
				if ( w_choices_array[k].indexOf('[') === -1 && w_choices_array[k].indexOf(']') === -1 ) {
					choise_select.appendChild(choise_option);
				}
			}
			condition_div.appendChild(choise_select);
			break;
		case "type_country":
		case "type_address":
			countries = form_maker.countries;
			var choise_select = document.createElement('select');
			choise_select.setAttribute("id", "field_value" + num + '_' + m);
			choise_select.setAttribute("class", "fm_condition_field_select_value");
			jQuery.each(countries, function ( key, value ) {
				var choise_option = document.createElement('option');
				choise_option.setAttribute("id", "choise_" + num + '_' + key);
				choise_option.setAttribute("value", value);
				choise_option.innerHTML = value;
				choise_select.appendChild(choise_option);
			});
			condition_div.appendChild(choise_select);
			break;
	}

	condition_div.appendChild(document.createTextNode(' '));
	var trash_icon = document.createElement('span');
			trash_icon.setAttribute('class', 'dashicons dashicons-trash');
			trash_icon.setAttribute('id', 'delete_condition' + num + '_' + m);
			trash_icon.setAttribute('onClick', 'delete_field_condition("' + num + '_' + m + '")');
			trash_icon.style.cssText = "vertical-align: middle";
			condition_div.appendChild(trash_icon);
	document.getElementById('condition' + num).appendChild(condition_div);
	jQuery('.fm_condition_is_select').change(function () {
		show_hide_condition_input_field(this)
	});
}

function change_choices(value) {
	value = value.split("_");
	global_index = value[0];
	id = value[1];
	index = value[2];
	var ids = jQuery('#fm-condition-fields-' + global_index).data('conditions-field-ids');
	var types = jQuery('#fm-condition-fields-' + global_index).data('conditions-field-types');
	var params = jQuery('#fm-condition-fields-' + global_index).data('conditions-field-params');
	ids_array = ids;
	types_array = types;
	params_array = params;

	jQuery('#is_select' + global_index + '_' + index).empty();
	var is_select_array = is_select_options_HTML_array(types_array[id]);
	jQuery('#is_select' + global_index + '_' + index).html(is_select_array);
	show_hide_condition_input_field(jQuery('#is_select' + global_index + '_' + index));
	switch(types_array[id]) {
		case "type_text":
		case "type_password":
		case "type_textarea":
		case "type_name":
		case "type_submitter_mail":
		case "type_number":
		case "type_phone":
		case "type_paypal_price":
		case "type_paypal_price_new":
		case "type_slider":
		case "type_spinner":
		case "type_range":
		case "type_date_new":
		case "type_date_fields":
		case "type_phone_new":
		case "type_time":
		case "type_editor":
		case "type_hidden":
			if(types_array[id]=="type_number" || types_array[id]=="type_phone")
				var keypress_function = "return check_isnum_space(event)";
			else
			if(types_array[id]=="type_paypal_price" || types_array[id]=="type_paypal_price_new")
				var keypress_function = "return check_isnum_point(event)";
			else
				var keypress_function = "";

			if(document.getElementById("field_value"+global_index+"_"+index).tagName=="SELECT") {
				document.getElementById("condition_div"+global_index+"_"+index).removeChild(document.getElementById("field_value"+global_index+"_"+index));
				var label_input = document.createElement('input');
				label_input.setAttribute("id", "field_value"+global_index+'_'+index);
				label_input.setAttribute("type", "text");
				label_input.setAttribute("value", "");
				label_input.setAttribute("class", "fm_condition_field_input_value");

				label_input.setAttribute("onKeyPress", keypress_function);

				document.getElementById("condition_div"+global_index+"_"+index).insertBefore(label_input,document.getElementById("delete_condition"+global_index+"_"+index));
				document.getElementById("condition_div"+global_index+"_"+index).insertBefore(document.createTextNode(' '),document.getElementById("delete_condition"+global_index+"_"+index));
			}
			else {
				document.getElementById("field_value"+global_index+'_'+index).value="";
				document.getElementById("field_value"+global_index+'_'+index).setAttribute("onKeyPress", keypress_function);
			}
			break;
		case "type_own_select":
		case "type_radio":
		case "type_checkbox":
			if ( types_array[id] == 'type_own_select' ) {
				w_size = params_array[id].split('*:*w_size*:*');
			}
			else {
				w_size = params_array[id].split('*:*w_flow*:*');
			}

			w_choices = w_size[1].split('*:*w_choices*:*');
			w_choices_array = w_choices[0].split('***');
			// case for enabled use_for_submission
			if ( w_size[1].indexOf('*:*w_use_for_submission*:*') !== -1 ) {
				w_value_disabled = w_size[1].split('*:*w_use_for_submission*:*');
				w_choices_value = w_value_disabled[1].split('*:*w_choices_value*:*');
				w_choices_value_array = w_choices_value[0].split('***');
			}
			else if ( w_size[1].indexOf('*:*w_value_disabled*:*') !== -1 ) {
				w_value_disabled = w_size[1].split('*:*w_value_disabled*:*');
				w_choices_value = w_value_disabled[1].split('*:*w_choices_value*:*');
				w_choices_value_array = w_choices_value[0].split('***');
			}
			else{
				w_choices_value_array = w_choices_array;
			}

			var choise_select = document.createElement('select');
			choise_select.setAttribute("id", "field_value"+global_index+'_'+index);
			choise_select.setAttribute("class", "fm_condition_field_select_value");

			if(types_array[id]== "type_checkbox") {
				choise_select.setAttribute('multiple', 'multiple');
				choise_select.setAttribute('class', 'multiple_select');
			}

			for(k=0; k<w_choices_array.length; k++) {
				var choise_option = document.createElement('option');
				choise_option.setAttribute("id", "choise_"+global_index+'_'+k);
				choise_option.setAttribute("value", w_choices_value_array[k]);
				choise_option.innerHTML = replaceQuote(w_choices_array[k]);
				if(w_choices_array[k].indexOf('[') === -1 && w_choices_array[k].indexOf(']') === -1) {
					choise_select.appendChild(choise_option);
				}
			}

			document.getElementById("condition_div"+global_index+"_"+index).removeChild(document.getElementById("field_value"+global_index+"_"+index));
			document.getElementById("condition_div"+global_index+"_"+index).insertBefore(choise_select,document.getElementById("delete_condition"+global_index+"_"+index));
			document.getElementById("condition_div"+global_index+"_"+index).insertBefore(document.createTextNode(' '),document.getElementById("delete_condition"+global_index+"_"+index));

			break;
		case "type_paypal_select":
		case "type_paypal_radio":
		case "type_paypal_checkbox":
		case "type_paypal_shipping":
			if(types_array[id]=="type_paypal_select")
				w_size = params_array[id].split('*:*w_size*:*');
			else
				w_size = params_array[id].split('*:*w_flow*:*');

			w_choices = w_size[1].split('*:*w_choices*:*');
			w_choices_array = w_choices[0].split('***');

			w_choices_price = w_choices[1].split('*:*w_choices_price*:*');
			w_choices_price_array = w_choices_price[0].split('***');

			var choise_select = document.createElement('select');
			choise_select.setAttribute("id", "field_value"+global_index+'_'+index);
			choise_select.setAttribute("class", "fm_condition_field_select_value");

			if(types_array[id]== "type_paypal_checkbox") {
				choise_select.setAttribute('multiple', 'multiple');
				choise_select.setAttribute('class', 'multiple_select');
			}

			for(k=0; k<w_choices_array.length; k++) {
				var choise_option = document.createElement('option');
				choise_option.setAttribute("id", "choise_"+global_index+'_'+k);
				choise_option.setAttribute("value", w_choices_array[k]+'*:*value*:*'+w_choices_price_array[k]);
				choise_option.innerHTML = w_choices_array[k];
				if(w_choices_array[k].indexOf('[') === -1 && w_choices_array[k].indexOf(']') === -1) {
					choise_select.appendChild(choise_option);
				}
			}

			document.getElementById("condition_div"+global_index+"_"+index).removeChild(document.getElementById("field_value"+global_index+"_"+index));
			document.getElementById("condition_div"+global_index+"_"+index).insertBefore(choise_select,document.getElementById("delete_condition"+global_index+"_"+index));
			document.getElementById("condition_div"+global_index+"_"+index).insertBefore(document.createTextNode(' '),document.getElementById("delete_condition"+global_index+"_"+index));
			break;
		case "type_country":
		case "type_address":
			var countries = form_maker.countries;
			var choise_select = document.createElement('select');
			choise_select.setAttribute('id', 'field_value' + global_index + '_' + index);
			choise_select.setAttribute('class', 'fm_condition_field_select_value');
			jQuery.each( countries, function( key, value ) {
				var choise_option = document.createElement('option');
				choise_select.setAttribute("id", "field_value" + global_index + '_' + index);
				choise_option.setAttribute("value", value);
				choise_option.innerHTML = value;
				choise_select.appendChild(choise_option);
			});

			document.getElementById("condition_div"+global_index+"_"+index).removeChild(document.getElementById("field_value"+global_index+"_"+index));
			document.getElementById("condition_div"+global_index+"_"+index).insertBefore(choise_select,document.getElementById("delete_condition"+global_index+"_"+index));
			document.getElementById("condition_div"+global_index+"_"+index).insertBefore(document.createTextNode(' '),document.getElementById("delete_condition"+global_index+"_"+index));

			break;
	}
}

function acces_level(length) {
	var value='';
	for(i=0; i<=parseInt(length); i++) {
    if (document.getElementById('user_'+i).checked) {
      value=value+document.getElementById('user_'+i).value+',';			
    }	
  }
	document.getElementById('user_id_wd').value=value;
}

function check_isnum_space(e) {
	var chCode1 = e.which || e.keyCode;	
	if (chCode1 ==32) {
		return true;
  }
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {
		return false;
  }
	return true;
}

function check_isnum_point(e) {
  var chCode1 = e.which || e.keyCode;	
	if (chCode1 ==46) {
		return true;
	}
	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {
    return false;
  }
	return true;
}

function check_stripe_required_fields() {
  if ( jQuery('#paypal_mode2').prop('checked') ) {
    if ( jQuery('#stripemode').val() == '1' ) {
      fields = ['live_sec', 'live_pub'];
      fields_titles = ['Live secret key', 'Live publishable key'];
    }
    else {
      fields = ['test_sec', 'test_pub'];
      fields_titles = ['Test secret key', 'Test publishable key'];
    }
    for ( i=0; i < fields.length; i++ ) {
      if ( !jQuery('#' + fields[i]).val() ) {
        jQuery('#' + fields[i]).focus();
        alert(fields_titles[i] + ' is required.');
        return true;
      }
    }
  }
  return false;
}

function check_calculator_required_fields() {
	var empty_textarea = 0;
	jQuery(jQuery('#wd_calculated_field_table').find('[id^="wdc_equation_"]')).each(function() {
		if(jQuery( this ).val() == ''){
			var field_id = jQuery( this ).attr('id').replace('wdc_equation_','');
			var label_name = jQuery(jQuery('#wd_calculated_field_table').find("[data-field='" + field_id + "']")).html();
			empty_textarea = 1;
			jQuery( this ).focus();
			alert('Set equation for the field ' + label_name);
		}
		if(empty_textarea == 1)
			return false;
		});
	if(empty_textarea == 1)
		return true;
		
	return false;
}

function set_theme() {
  theme_id = jQuery('#theme').val() == '0' ? default_theme : jQuery('#theme').val();
  jQuery("#edit_css").attr("onclick", "window.open('"+ theme_edit_url +"&current_id=" + theme_id + "'); return false;");
  if (jQuery('#theme option:selected').attr('data-version') == 1) {
    jQuery("#old_theme_notice").show();
  }
  else {
    jQuery("#old_theme_notice").hide();
  }
}

/* Send ajax action to Stripe addon to capture payment */
function change_stripe_status(that) {
	var $this = jQuery(that);
	var form_id = jQuery("#form_id").val();
	$this.next(".fm-capture-loading").removeClass("fm-hidden");
	$this.next(".fm-error").remove();
	$this.prop('disabled', true);

	var group_id = $this.parents('tr').attr('id');
	group_id = group_id.replace("tr_","");
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			"form_id" : form_id,
			"group_id" : group_id,
			"action": "fm_stripe_status_update",
			"nonce": fm_ajax.ajaxnonce
		},
		success: function (response) {
			var response = JSON.parse(response);
			if( typeof response.code !== 'undefined' && response.code === 200 && response.message === 'OK') {
				jQuery(that).parents('td').empty().text(form_maker_stripe_statuses.succeeded);
			} else if( typeof response.code !== 'undefined' && response.code === 200 && response.message === 'Already exist' ) {
				var parent = jQuery(that).parents('td');
				parent.empty().text('Already succeeded');
				setTimeout(function() {parent.text(form_maker_stripe_statuses.succeeded);}, 1500)
			} else {
				jQuery(that).parents('td').append('<span class="fm-error">'+form_maker_stripe_statuses.failed+'</span>');
			}
		},
		complete: function() {
			jQuery(that).next(".fm-capture-loading").addClass("fm-hidden");
			jQuery(that).prop('disabled', false);
		},
		error: function (jqXHR, exception) {
			jQuery(that).parents('td').append('<span class="fm-error">'+form_maker_stripe_statuses.failed+'</span>');
		},

	});
}

