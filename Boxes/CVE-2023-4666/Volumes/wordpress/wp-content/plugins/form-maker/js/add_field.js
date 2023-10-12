var nextID; // next field id

jQuery(window).on('load', function () {
  if (!storageAvailable("localStorage")
      || !localStorage.getItem("wd-form-first-time-use")) {
    jQuery(".first-time-use").show();
  }
  // Space between element and top of screen (when scrolling)
  jQuery(window).on('scroll', function (event) {
    if ( ! jQuery("#add_field").hasClass("ui-sortable-helper") ) { // Check if not in draggable process.
      var edit_content_position = jQuery(".fm-edit-content").offset().top;
      var scrollTop = jQuery(window).scrollTop();
      if ( scrollTop > (edit_content_position - 75) ) {
        var scrollToTop = scrollTop - edit_content_position + 75;
      }
      else {
        // Do not move.
        var scrollToTop = 10;
      }
      jQuery("#add_field_cont").css("top", scrollToTop);
    }
  });
  jQuery('#placeholders_overlay').on("click", function() {
    var value = jQuery('.fm-input-container.placeholders-active input').val();
    jQuery('.fm-input-container.placeholders-active input').val(value).change();
    fm_placeholders_popup_close();
  });
});

jQuery(function () {
  // Add close event to first time use message.
  jQuery(".first-time-use-close").on("click", function () {
    if (storageAvailable("localStorage")) {
      localStorage.setItem("wd-form-first-time-use", true);
    }
    jQuery(".first-time-use").hide();
  });
  // Hide first time use message on add field button drag.
  jQuery(".wdform_column").on( "sortstart", function( event, ui ) {
    jQuery(".first-time-use").hide();
  });
  // Bind filter action on entering search key and when the user cancel the input.
  jQuery(".field-types-filter").on("keyup input", function() { filter(this); });
});

/**
 * Detects whether localStorage is both supported and available.
 *
 * @param type
 * @returns {boolean}
 */
function storageAvailable(type) {
  try {
    var storage = window[type],
      x = '__storage_test__';
    storage.setItem(x, x);
    storage.removeItem(x);
    return true;
  }
  catch(e) {
    return e instanceof DOMException && (
        // everything except Firefox
      e.code === 22 ||
      // Firefox
      e.code === 1014 ||
      // test name field too, because code might not be present
      // everything except Firefox
      e.name === 'QuotaExceededError' ||
      // Firefox
      e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
      // acknowledge QuotaExceededError only if there's something already stored
      storage.length !== 0;
  }
}

// Close popup on escape.
jQuery(document).on('keydown', function (e) {
  if (e.keyCode === 27) { /* Esc.*/
    if (jQuery("#placeholders_overlay").is(":visible")) {
      var value = jQuery('.fm-input-container.placeholders-active input').val();
      jQuery('.fm-input-container.placeholders-active input').val(value).change();
      fm_placeholders_popup_close();
    }
    else {
      if (jQuery(".add-popup").is(":visible")) {
       close_window();
      }
    }
  }
});

function fm_check_something_really_important() {
  is7 = jQuery('.wdform_field:not([type=type_section_break])').length;
  if (is7 >= 9) {
    fm_limitation_alert(true);
    return true;
  }
  return false;
}

/**
 * Filter fields.
 *
 * @param that
 */
function filter(that) {
  // Get search key.
  var search = jQuery(that).val().toLowerCase();
  // Remove previous serach results from filtered fields section.
  jQuery(".filtered-fields .inside").html("");
  if (search != "") {
    var found = false;
    // Hide all field sections.
    jQuery(".field_types_cont .postbox").addClass("hide");
    jQuery(".field_types .postbox:not(.filtered-fields) .wd-button").each(function () {
      var field_name = jQuery(this).html().toLowerCase();
      if (field_name.indexOf(search) != '-1') {
        jQuery(".filtered-fields .inside").append(jQuery(this).clone());
        found = true;
      }
    });
    // If nothing found.
    if (!found) {
      jQuery(".filtered-fields .inside").html(form_maker.nothing_found);
    }
    // Show search results in filtered fields section.
    jQuery(".field_types_cont .filtered-fields").removeClass("hide");
  }
  else {
    jQuery(".field_types_cont .postbox").removeClass("hide");
    jQuery(".field_types_cont .filtered-fields").addClass("hide");
  }
}

function Enable() {
  //var pos=document.getElementsByName("el_pos");
  //		pos[0].setAttribute("checked", "checked");

  //select_ = document.getElementById('sel_el_pos');
  //select_.innerHTML="";

  for(k=1;k<=form_view_max;k++)
    if(document.getElementById('form_id_tempform_view'+k))
    {
      wdform_page=document.getElementById('form_id_tempform_view'+k);
      remove_whitespace(wdform_page);
      n=wdform_page.childNodes.length-2;

      for(z=0;z<=n;z++)
      {
        if(!wdform_page.childNodes[z].getAttribute("wdid"))
        {
          wdform_section=wdform_page.childNodes[z];
          for (x=0; x < wdform_section.childNodes.length; x++)
          {
            wdform_column=wdform_section.childNodes[x];

            if(wdform_column.firstChild)
              for (y=0; y < wdform_column.childNodes.length; y++)
              {
                wdform_row=wdform_column.childNodes[y];
                wdid=wdform_row.getAttribute("wdid");

                if(wdid)
                {
                  //var option = document.createElement('option');
                  //		option.setAttribute("id", wdid+"_sel_el_pos");
                  //		option.setAttribute("value", wdid);
                  //	option.innerHTML=document.getElementById( wdid+'_element_labelform_id_temp').innerHTML;
                  //select_.appendChild(option);
                }
              }
          }
        }
      }
    }

  //select_.removeAttribute("disabled");
}

function enable2() {
  fm_add_field_button();
  // Open popup.
  jQuery(".add-popup").slideToggle(200);

  // Change button name.
  jQuery(".popup-title").html(form_maker.edit_field);
  jQuery(".wd-add-button").html(form_maker.update);

  // Hide field types container.
  jQuery(".field_types").hide();
  jQuery("#field_container").addClass('field_container_full');
}

function edit(id, e) {
  if ( fm_need_enable ) {
    enable2();
  }
  document.getElementById('editing_id').value = id;
  type = document.getElementById("wdform_field" + id).getAttribute('type');
  //////////////////////////////parameter take
  k = 0;
  w_choices = new Array();
  w_choices_value = new Array();
  w_choices_params = new Array();
  w_choices_checked = new Array();
  w_choices_disabled = new Array();
  w_allow_other_num = 0;
  w_property = new Array();
  w_property_values = new Array();
  w_choices_price = new Array();
  t = 0;
  /////////shat handipox
  if (document.getElementById(id + '_element_labelform_id_temp').innerHTML) {
    w_field_label = document.getElementById(id + '_element_labelform_id_temp').innerHTML;
  }
  else {
    w_field_label = " ";
  }
  if (document.getElementById(id + '_label_sectionform_id_temp')) {
    if (document.getElementById(id + '_label_sectionform_id_temp').style.display == "block") {
      w_field_label_pos = "top";
    }
    else {
      w_field_label_pos = "left";
    }
  }
  if (document.getElementById(id + "_elementform_id_temp")) {
    s = document.getElementById(id + "_elementform_id_temp").style.width;
    if ( s == "" ) {
      w_size = s;
    }
    else {
      w_size = s.substring(0, s.length - 2);
    }
    //w_size = "";
  }

  if (document.getElementById(id + "_label_sectionform_id_temp")) {
    s = document.getElementById(id + "_label_sectionform_id_temp").style.width;
    if ( s == "" ) {
      w_field_label_size = s;
    }
    else {
      w_field_label_size = s.substring(0, s.length - 2);
    }
    //w_field_label_size = "";
  }
  if (document.getElementById(id + "_requiredform_id_temp")) {
    w_required = document.getElementById(id + "_requiredform_id_temp").value;
  }
  if (document.getElementById(id + "_uniqueform_id_temp")) {
    w_unique = document.getElementById(id + "_uniqueform_id_temp").value;
  }
  if (document.getElementById(id + '_label_sectionform_id_temp')) {
    w_class = document.getElementById(id + '_label_sectionform_id_temp').getAttribute("class");
    if (!w_class) {
      w_class = "";
    }
  }
  switch (type) {
    case 'type_editor': {
      w_editor = document.getElementById("wdform_field" + id).innerHTML;
      type_editor(id, w_editor);
      break;
    }
    case 'type_section_break': {
      w_editor = document.getElementById(id + "_element_sectionform_id_temp").innerHTML;
      type_section_break(id, w_editor);
      break;
    }
    case 'type_send_copy': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").checked;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_send_copy(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_required, w_attr_name, w_attr_value);
      break;
    }
    case 'type_text': {
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      w_regExp_status = document.getElementById(id + "_regExpStatusform_id_temp").value;
      w_regExp_value = unescape(document.getElementById(id + "_regExp_valueform_id_temp").value);
      w_regExp_common = document.getElementById(id + "_regExp_commonform_id_temp").value;
      w_regExp_arg = document.getElementById(id + "_regArgumentform_id_temp").value;
      w_regExp_alert = document.getElementById(id + "_regExp_alertform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_readonly = document.getElementById(id + "_readonlyform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      type_text(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_required, w_regExp_status, w_regExp_value, w_regExp_common, w_regExp_arg, w_regExp_alert, w_unique, w_attr_name, w_attr_value, w_readonly, w_class);
      break;
    }
    case 'type_number': {
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_number(id, w_field_label, w_field_label_size, w_field_label_pos, w_size, w_first_val, w_title, w_required, w_unique, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_password': {
      w_placeholder_value = document.getElementById(id + "_elementform_id_temp").placeholder;
      w_verification = document.getElementById(id + "_verification_id_temp").value;
      if (document.getElementById(id + '_1_element_labelform_id_temp').innerHTML) {
        w_verification_label = document.getElementById(id + '_1_element_labelform_id_temp').innerHTML;
      }
      else {
        w_verification_label = " ";
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_password(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_required, w_unique, w_class, w_verification, w_verification_label, w_placeholder_value, w_attr_name, w_attr_value);
      break;
    }
    case 'type_textarea': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_characters_limit = document.getElementById(id + "_charlimitform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      s = document.getElementById(id + "_elementform_id_temp").style.height;
      w_size_h = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_textarea(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_size_h, w_first_val, w_characters_limit, w_title, w_required, w_unique, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_wdeditor': {
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      s = document.getElementById(id + "_elementform_id_temp").style.height;
      w_size_h = s.substring(0, s.length - 2);
      w = document.getElementById(id + "_elementform_id_temp").style.width;
      w_size_w = w.substring(0, w.length - 2);
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_wdeditor(id, w_field_label, w_field_label_size, w_field_label_pos, w_size_w, w_size_h, w_title, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_phone': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
      w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
      s = document.getElementById(id + "_element_lastform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      w_mini_labels = [document.getElementById(id + "_mini_label_area_code").innerHTML, document.getElementById(id + "_mini_label_phone_number").innerHTML];
      atrs = return_attributes(id + '_element_firstform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_phone(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_mini_labels, w_required, w_unique, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_phone_new': {
      var telinput = jQuery("#" + id + "_elementform_id_temp");
      w_top_country = telinput.attr('top-country');
      window.intlTelInput(telinput[0], {
        nationalMode: false,
        formatOnDisplay: true,
        initialCountry: w_top_country,
        utilsScript: form_maker.plugin_url + '/js/intlTelInput-utils.js'
      });
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_phone_new(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_top_country, w_required, w_unique, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_name': {
      if (document.getElementById(id + "_enable_fieldsform_id_temp")) {
        w_name_format = "normal";
        w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
        w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
        var title_middle = ['title', 'middle'];
        for (var l = 0; l < 2; l++) {
          w_first_val.push(document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp') ? document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp').value : '');
          w_title.push(document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp') ? document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp').title : '');
        }
      }
      else {
        if (document.getElementById(id + '_element_middleform_id_temp')) {
          w_name_format = "extended";
        }
        else {
          w_name_format = "normal";
        }
        if (w_name_format == "normal") {
          w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
          w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
        }
        else {
          w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value, document.getElementById(id + "_element_titleform_id_temp").value, document.getElementById(id + "_element_middleform_id_temp").value];
          w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title, document.getElementById(id + "_element_titleform_id_temp").title, document.getElementById(id + "_element_middleform_id_temp").title];
        }
      }
      if (document.getElementById(id + "_mini_label_title")) {
        w_mini_title = document.getElementById(id + "_mini_label_title").innerHTML;
      }
      else {
        w_mini_title = "Title";
      }
      if (document.getElementById(id + "_mini_label_middle")) {
        w_mini_middle = document.getElementById(id + "_mini_label_middle").innerHTML;
      }
      else {
        w_mini_middle = "Middle";
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_mini_labels = [w_mini_title, document.getElementById(id + "_mini_label_first").innerHTML, document.getElementById(id + "_mini_label_last").innerHTML, w_mini_middle];
      w_name_title = document.getElementById(id + '_enable_fieldsform_id_temp') ? document.getElementById(id + '_enable_fieldsform_id_temp').getAttribute('title') : (w_name_format == "normal" ? 'no' : 'yes');
      w_name_middle = document.getElementById(id + '_enable_fieldsform_id_temp') ? document.getElementById(id + '_enable_fieldsform_id_temp').getAttribute('middle') : (w_name_format == "normal" ? 'no' : 'yes');
      w_name_fields = [w_name_title, w_name_middle];
      w_autofill = document.getElementById(id + "_autofillform_id_temp").value;
      s = document.getElementById(id + "_element_firstform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_element_firstform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_name(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_title, w_mini_labels, w_size, w_name_format, w_required, w_unique, w_class, w_attr_name, w_attr_value, w_name_fields, w_autofill);
      break;
    }
    case 'type_paypal_price': {
      w_first_val = [document.getElementById(id + "_element_dollarsform_id_temp").value, document.getElementById(id + "_element_centsform_id_temp").value];
      w_title = [document.getElementById(id + "_element_dollarsform_id_temp").title, document.getElementById(id + "_element_centsform_id_temp").title];
      if (document.getElementById(id + "_td_name_cents").style.display == "none") {
        w_hide_cents = 'yes';
      }
      else {
        w_hide_cents = 'no';
      }
      s = document.getElementById(id + "_element_dollarsform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_element_dollarsform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_range_min = document.getElementById(id + "_range_minform_id_temp").value;
      w_range_max = document.getElementById(id + "_range_maxform_id_temp").value;
      w_mini_labels = [document.getElementById(id + "_mini_label_dollars").innerHTML, document.getElementById(id + "_mini_label_cents").innerHTML];
      type_paypal_price(id, w_field_label, w_field_label_size, w_field_label_pos, w_first_val, w_title, w_mini_labels, w_size, w_required, w_hide_cents, w_class, w_attr_name, w_attr_value, w_range_min, w_range_max);
      break;
    }
    case 'type_paypal_price_new': {
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      s = document.getElementById(id + "_elementform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_range_min = document.getElementById(id + "_range_minform_id_temp").value;
      w_range_max = document.getElementById(id + "_range_maxform_id_temp").value;
      w_readonly = document.getElementById(id + "_readonlyform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      if (document.getElementById(id + "_td_name_currency").style.display == "none") {
        w_currency = 'yes';
      }
      else {
        w_currency = 'no';
      }
      type_paypal_price_new(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_title, w_size, w_required, w_class, w_attr_name, w_attr_value, w_range_min, w_range_max, w_readonly, w_currency);
      break;
    }
    case 'type_address': {
      s = document.getElementById(id + "_div_address").style.width;
      w_size = s.substring(0, s.length - 2);
      if (document.getElementById(id + "_mini_label_street1")) {
        w_street1 = document.getElementById(id + "_mini_label_street1").innerHTML;
      }
      else {
        w_street1 = document.getElementById(id + "_street1form_id_temp").value;
      }
      if (document.getElementById(id + "_mini_label_street2")) {
        w_street2 = document.getElementById(id + "_mini_label_street2").innerHTML;
      }
      else {
        w_street2 = document.getElementById(id + "_street2form_id_temp").value;
      }
      if (document.getElementById(id + "_mini_label_city")) {
        w_city = document.getElementById(id + "_mini_label_city").innerHTML;
      }
      else {
        w_city = document.getElementById(id + "_cityform_id_temp").value;
      }
      if (document.getElementById(id + "_mini_label_state")) {
        w_state = document.getElementById(id + "_mini_label_state").innerHTML;
      }
      else {
        w_state = document.getElementById(id + "_stateform_id_temp").value;
      }
      if (document.getElementById(id + "_mini_label_postal")) {
        w_postal = document.getElementById(id + "_mini_label_postal").innerHTML;
      }
      else {
        w_postal = document.getElementById(id + "_postalform_id_temp").value;
      }
      if (document.getElementById(id + "_mini_label_country")) {
        w_country = document.getElementById(id + "_mini_label_country").innerHTML;
      }
      else {
        w_country = document.getElementById(id + "_countryform_id_temp").value;
      }
      w_mini_labels = [w_street1, w_street2, w_city, w_state, w_postal, w_country];
      var disabled_input = document.getElementById(id + "_disable_fieldsform_id_temp");
      w_street1_dis = disabled_input.getAttribute('street1');
      w_street2_dis = disabled_input.getAttribute('street2');
      w_city_dis = disabled_input.getAttribute('city');
      w_state_dis = disabled_input.getAttribute('state');
      w_us_states_dis = disabled_input.getAttribute('us_states');
      w_postal_dis = disabled_input.getAttribute('postal');
      w_country_dis = disabled_input.getAttribute('country');
      w_disabled_fields = [w_street1_dis, w_street2_dis, w_city_dis, w_state_dis, w_postal_dis, w_country_dis, w_us_states_dis];
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_street1form_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_address(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_mini_labels, w_disabled_fields, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_submitter_mail': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      w_autofill = document.getElementById(id + "_autofillform_id_temp").value;
      w_verification = document.getElementById(id + "_verification_id_temp").value;
      w_verification_placeholder = document.getElementById(id + "_1_elementform_id_temp").title;
      if (document.getElementById(id + '_1_element_labelform_id_temp').innerHTML) {
        w_verification_label = document.getElementById(id + '_1_element_labelform_id_temp').innerHTML;
      }
      else {
        w_verification_label = " ";
      }
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_submitter_mail(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_required, w_unique, w_class, w_verification, w_verification_label, w_verification_placeholder, w_attr_name, w_attr_value, w_autofill);
      break;
    }
    case 'type_checkbox': {
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      w_limit_choice = document.getElementById(id + "_limitchoice_numform_id_temp").value;
      w_limit_choice_alert = document.getElementById(id + "_limitchoicealert_numform_id_temp").value;
      if (document.getElementById(id + "_rowcol_numform_id_temp").value) {
        if (document.getElementById(id + '_table_little').getAttribute('for_hor')) {
          w_flow = "hor"
        }
        else {
          w_flow = "ver";
        }
        w_rowcol = document.getElementById(id + "_rowcol_numform_id_temp").value;
      }
      else {
        if (document.getElementById(id + '_hor')) {
          w_flow = "hor"
        }
        else {
          w_flow = "ver";
        }
        w_rowcol = 1;
      }
      v = 0;
      if (w_flow == "ver") {
        var table_little = document.getElementById(id + '_table_little');
        for (k = 0; k < table_little.childNodes.length; k++) {
          var td_little = table_little.childNodes[k];
          for (m = 0; m < td_little.childNodes.length; m++) {
            var idi = td_little.childNodes[m].getAttribute('idi');
            if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other')) {
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1') {
                w_allow_other_num = t;
              }
            }
            w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
            w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
            w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
            w_choices_value[t] = w_choices_value[t].replace(/[\'\"]/g, "");
            if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
              w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
            }
            else {
              w_choices_params[t] = '';
            }
            t++;
            v = idi;
          }
        }
      }
      else {
        var table_little = document.getElementById(id + '_table_little');
        var tr_little = table_little.childNodes;
        var td_max = tr_little[0].childNodes;
        for (k = 0; k < td_max.length; k++) {
          for (m = 0; m < tr_little.length; m++) {
            if (tr_little[m].childNodes[k]) {
              var td_little = tr_little[m].childNodes[k];
              var idi = td_little.getAttribute('idi');
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other')) {
                if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1') {
                  w_allow_other_num = t;
                }
              }
              w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
              w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
              w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
              w_choices_value[t] = w_choices_value[t].replace(/[\'\"]/g, "");
              if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
                w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
              }
              else {
                w_choices_params[t] = '';
              }
              t++;
              v = idi;
            }
          }
        }
      }
      if (document.getElementById(id + "_option_left_right")) {
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      }
      else {
        w_field_option_pos = 'left';
      }
      w_value_disabled = document.getElementById(id + "_value_disabledform_id_temp").value;
      w_use_for_submission = document.getElementById(id + "_use_for_submissionform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_checkbox(id, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_checked, w_rowcol, w_limit_choice, w_limit_choice_alert, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_value_disabled, w_choices_value, w_choices_params, w_use_for_submission);
      break;
    }
    case 'type_paypal_checkbox': {
      if (document.getElementById(id + '_hor')) {
        w_flow = "hor"
      }
      else {
        w_flow = "ver";
      }
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      v = 0;
      var w_allow_other_num = 0;
      var table_little = document.getElementById(id + '_table_little');
      for (k = 0; k < table_little.childNodes.length; k++) {
        var td_little = table_little.childNodes[k];
        for (m = 0; m < td_little.childNodes.length; m++) {
          var idi = td_little.childNodes[m].getAttribute('idi');
          w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
          w_choices_price[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
          w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
          if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
            w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
          }
          else {
            w_choices_params[t] = '';
          }
          t++;
          v = idi;
        }
      }
      if (w_flow == 'hor') {
        flow_hor(id);
      }
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = new Array();
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1].push(document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }
      }
      w_quantity = "no";
      w_quantity_value = 1;
      if (document.getElementById(id + "_element_quantityform_id_temp")) {
        w_quantity = 'yes';
        w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
      }
      if (document.getElementById(id + "_option_left_right")) {
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      }
      else {
        w_field_option_pos = 'left';
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_paypal_checkbox(id, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_property, w_property_values, w_quantity, w_quantity_value, w_choices_params);
      break;
    }
    case 'type_radio': {
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      if (document.getElementById(id + "_rowcol_numform_id_temp").value) {
        if (document.getElementById(id + '_table_little').getAttribute('for_hor')) {
          w_flow = "hor"
        }
        else {
          w_flow = "ver";
        }
        w_rowcol = document.getElementById(id + "_rowcol_numform_id_temp").value;
      }
      else {
        if (document.getElementById(id + '_table_little').getAttribute('for_hor')) {
          w_flow = "hor"
        }
        else {
          w_flow = "ver";
        }
        w_rowcol = 1;
      }
      v = 0;
      if (w_flow == "ver") {
        var table_little = document.getElementById(id + '_table_little');
        for (k = 0; k < table_little.childNodes.length; k++) {
          var td_little = table_little.childNodes[k];
          for (m = 0; m < td_little.childNodes.length; m++) {
            var idi = td_little.childNodes[m].getAttribute('idi');
            if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other')) {
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1') {
                w_allow_other_num = t;
              }
            }
            w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
            w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
            w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
            w_choices_value[t] = w_choices_value[t].replace(/[\'\"]/g, "");
            if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
              w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
            }
            else {
              w_choices_params[t] = '';
            }
            t++;
            v = idi;
          }
        }
      }
      else {
        var table_little = document.getElementById(id + '_table_little');
        var tr_little = table_little.childNodes;
        var td_max = tr_little[0].childNodes;
        for (k = 0; k < td_max.length; k++) {
          for (m = 0; m < tr_little.length; m++) {
            if (tr_little[m].childNodes[k]) {
              var td_little = tr_little[m].childNodes[k];
              var idi = td_little.getAttribute('idi');
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other')) {
                if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1') {
                  w_allow_other_num = t;
                }
              }
              w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
              w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
              w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
              w_choices_value[t] = w_choices_value[t].replace(/[\'\"]/g, "");
              if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
                w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
              }
              else {
                w_choices_params[t] = '';
              }
              t++;
              v = idi;
            }
          }
        }
      }
      if (document.getElementById(id + "_option_left_right")) {
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      }
      else {
        w_field_option_pos = 'left';
      }
      w_value_disabled = document.getElementById(id + "_value_disabledform_id_temp").value;
      w_use_for_submission = document.getElementById(id + "_use_for_submissionform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_radio(id, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_checked, w_rowcol, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_value_disabled, w_choices_value, w_choices_params, w_use_for_submission);
      break;
    }
    case 'type_paypal_radio': {
      if (document.getElementById(id + '_hor')) {
        w_flow = "hor"
      }
      else {
        w_flow = "ver";
      }
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      v = 0;
      var w_allow_other_num = 0;
      var table_little = document.getElementById(id + '_table_little');
      for (k = 0; k < table_little.childNodes.length; k++) {
        var td_little = table_little.childNodes[k];
        for (m = 0; m < td_little.childNodes.length; m++) {
          var idi = td_little.childNodes[m].getAttribute('idi');
          w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
          w_choices_price[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
          w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
          if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
            w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
          }
          else {
            w_choices_params[t] = '';
          }
          t++;
          v = idi;
        }
      }
      if (w_flow == 'hor') {
        flow_hor(id);
      }
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = new Array();
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1].push(document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }
      }
      w_quantity = "no";
      w_quantity_value = 1;
      if (document.getElementById(id + "_element_quantityform_id_temp")) {
        w_quantity = 'yes';
        w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
      }
      if (document.getElementById(id + "_option_left_right")) {
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      }
      else {
        w_field_option_pos = 'left';
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_paypal_radio(id, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_property, w_property_values, w_quantity, w_quantity_value, w_choices_params);
      break;
    }
    case 'type_paypal_shipping': {
      if (document.getElementById(id + '_hor')) {
        w_flow = "hor"
      }
      else {
        w_flow = "ver";
      }
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      v = 0;
      var w_allow_other_num = 0;
      var table_little = document.getElementById(id + '_table_little');
      for (k = 0; k < table_little.childNodes.length; k++) {
        var td_little = table_little.childNodes[k];
        for (m = 0; m < td_little.childNodes.length; m++) {
          var idi = td_little.childNodes[m].getAttribute('idi');
          w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
          w_choices_price[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
          w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
          if (document.getElementById(id + "_label_element" + idi).getAttribute('where')) {
            w_choices_params[t] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
          }
          else {
            w_choices_params[t] = '';
          }
          t++;
          v = idi;
        }
      }
      if (w_flow == 'hor') {
        flow_hor(id);
      }
      var w_property = [];
      var w_property_values = [];
      if (document.getElementById(id + "_option_left_right")) {
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      }
      else {
        w_field_option_pos = 'left';
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_paypal_shipping(id, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_property, w_property_values, w_choices_params);
      break;
    }
    case 'type_paypal_total': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_size = jQuery('#' + id + "paypal_totalform_id_temp").css('width') ? jQuery('#' + id + "paypal_totalform_id_temp").css('width').substring(0, jQuery('#' + id + "paypal_totalform_id_temp").css('width').length - 2) : '300';
      w_hide_total_currency = document.getElementById(id + "_hide_totalcurrency_id_temp").value;
      type_paypal_total(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_class, w_size, w_hide_total_currency);
      break;
    }
    case 'type_stripe': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      type_stripe(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_class);
      break;
    }
    case 'type_star_rating': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_star_amount = document.getElementById(id + "_star_amountform_id_temp").value;
      w_field_label_col = document.getElementById(id + "_star_colorform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_star_rating(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_label_col, w_star_amount, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_scale_rating': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_mini_labels = [document.getElementById(id + "_mini_label_worst").innerHTML, document.getElementById(id + "_mini_label_best").innerHTML];
      w_scale_amount = document.getElementById(id + "_scale_amountform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_scale_rating(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_mini_labels, w_scale_amount, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_spinner': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_min_value = document.getElementById(id + "_min_valueform_id_temp").value;
      w_field_max_value = document.getElementById(id + "_max_valueform_id_temp").value;
      w_field_width = document.getElementById(id + "_spinner_widthform_id_temp").value;
      w_field_step = document.getElementById(id + "_stepform_id_temp").value;
      w_field_value = document.getElementById(id + "_elementform_id_temp").getAttribute("aria-valuenow");
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_spinner(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_width, w_field_min_value, w_field_max_value, w_field_step, w_field_value, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_slider': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_min_value = document.getElementById(id + "_slider_min_valueform_id_temp").value;
      w_field_max_value = document.getElementById(id + "_slider_max_valueform_id_temp").value;
      w_field_step = document.getElementById(id + "_slider_stepform_id_temp") && document.getElementById(id + "_slider_stepform_id_temp").value ? document.getElementById(id + "_slider_stepform_id_temp").value : 1;
      w_field_width = document.getElementById(id + "_slider_widthform_id_temp").value;
      w_field_value = document.getElementById(id + "_slider_valueform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_slider(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_width, w_field_min_value, w_field_max_value, w_field_step, w_field_value, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_range': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_range_width = document.getElementById(id + "_range_widthform_id_temp").value;
      w_field_range_step = document.getElementById(id + "_range_stepform_id_temp").value;
      w_field_value1 = document.getElementById(id + "_elementform_id_temp0").getAttribute("aria-valuenow");
      w_field_value1 = (w_field_value1) ? w_field_value1 : '';
      w_field_value2 = document.getElementById(id + "_elementform_id_temp1").getAttribute("aria-valuenow");
      w_field_value2 = (w_field_value2) ? w_field_value2 : '';
      atrs = return_attributes(id + '_elementform_id_temp0');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_mini_labels = [document.getElementById(id + "_mini_label_from").innerHTML, document.getElementById(id + "_mini_label_to").innerHTML];
      type_range(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_range_width, w_field_range_step, w_field_value1, w_field_value2, w_mini_labels, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_grading': {
      w_total = document.getElementById(id + "_grading_totalform_id_temp").value;
      w_items = [];
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_label_elementform_id_temp" + k)) {
          w_items.push(document.getElementById(id + "_label_elementform_id_temp" + k).innerHTML);
        }
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_grading(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_items, w_total, w_required, w_class, w_attr_name, w_attr_value);
      refresh_grading_items(id);
      break;
    }
    case 'type_matrix': {
      w_rows = [];
      w_rows[0] = "";
      for (k = 1; k < 100; k++) {
        if (document.getElementById(id + "_label_elementform_id_temp" + k + "_0")) {
          w_rows.push(document.getElementById(id + "_label_elementform_id_temp" + k + "_0").innerHTML);
        }
      }
      w_columns = [];
      w_columns[0] = "";
      for (k = 1; k < 100; k++) {
        if (document.getElementById(id + "_label_elementform_id_temp0_" + k)) {
          w_columns.push(document.getElementById(id + "_label_elementform_id_temp0_" + k).innerHTML);
        }
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_input_type = document.getElementById(id + "_input_typeform_id_temp").value;
      w_textbox_size = document.getElementById(id + "_textbox_sizeform_id_temp") ? document.getElementById(id + "_textbox_sizeform_id_temp").value : '100';
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_matrix(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_input_type, w_rows, w_columns, w_required, w_class, w_attr_name, w_attr_value, w_textbox_size);
      refresh_matrix(id);
      break;
    }
    case 'type_time': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_hhform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_hh = document.getElementById(id + '_hhform_id_temp').value;
      w_mm = document.getElementById(id + '_mmform_id_temp').value;
      if (document.getElementById(id + '_ssform_id_temp')) {
        w_ss = document.getElementById(id + '_ssform_id_temp').value;
        w_sec = "1";
        w_mini_label_ss = document.getElementById(id + '_mini_label_ss').innerHTML;
      }
      else {
        w_ss = "";
        w_sec = "0";
        w_mini_label_ss = '';
      }
      if (document.getElementById(id + '_am_pm_select')) {
        w_am_pm = document.getElementById(id + '_am_pmform_id_temp').value;
        w_time_type = "12";
        w_mini_labels = [document.getElementById(id + '_mini_label_hh').innerHTML, document.getElementById(id + '_mini_label_mm').innerHTML, w_mini_label_ss, document.getElementById(id + '_mini_label_am_pm').innerHTML];
      }
      else {
        w_am_pm = 0;
        w_time_type = "24";
        w_mini_labels = [document.getElementById(id + '_mini_label_hh').innerHTML, document.getElementById(id + '_mini_label_mm').innerHTML, w_mini_label_ss, 'AM/PM'];
      }
      type_time(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_time_type, w_am_pm, w_sec, w_hh, w_mm, w_ss, w_mini_labels, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_date': {
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_date = document.getElementById(id + '_elementform_id_temp').value;
      w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
      w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
      w_disable_past_days = document.getElementById(id + '_dis_past_daysform_id_temp') ? document.getElementById(id + '_dis_past_daysform_id_temp').value : 'no';
      type_date(id, w_field_label, w_field_label_size, w_field_label_pos, w_date, w_required, w_class, w_format, w_but_val, w_attr_name, w_attr_value, w_disable_past_days);
      break;
    }
    case 'type_date_new': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_date = document.getElementById(id + '_elementform_id_temp').value;
      w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
      w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
      w_start_day = document.getElementById(id + '_start_dayform_id_temp').value;
      w_default_date = document.getElementById(id + '_default_date_id_temp').value;
      w_min_date = document.getElementById(id + '_min_date_id_temp').value;
      w_max_date = document.getElementById(id + '_max_date_id_temp').value;
      w_invalid_dates = document.getElementById(id + '_invalid_dates_id_temp').value;
      w_hide_time = document.getElementById(id + '_hide_timeform_id_temp').value;
      w_show_image = document.getElementById(id + '_show_imageform_id_temp').value;
      w_disable_past_days = document.getElementById(id + '_dis_past_daysform_id_temp') ? document.getElementById(id + '_dis_past_daysform_id_temp').value : 'no';
      var show_week_days_input = document.getElementById(id + "_show_week_days");
      w_sunday = show_week_days_input.getAttribute('sunday');
      w_monday = show_week_days_input.getAttribute('monday');
      w_tuesday = show_week_days_input.getAttribute('tuesday');
      w_wednesday = show_week_days_input.getAttribute('wednesday');
      w_thursday = show_week_days_input.getAttribute('thursday');
      w_friday = show_week_days_input.getAttribute('friday');
      w_saturday = show_week_days_input.getAttribute('saturday');
      w_show_days = [w_sunday, w_monday, w_tuesday, w_wednesday, w_thursday, w_friday, w_saturday];
      type_date_new(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_date, w_required, w_show_image, w_class, w_format, w_start_day, w_default_date, w_min_date, w_max_date, w_invalid_dates, w_show_days, w_hide_time, w_but_val, w_attr_name, w_attr_value, w_disable_past_days);
      break;
    }
    case 'type_date_range': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp0');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_date = '';
      w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
      w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
      s = document.getElementById(id + "_elementform_id_temp0").style.width;
      w_size = s.substring(0, s.length - 2);
      w_start_day = document.getElementById(id + '_start_dayform_id_temp').value;
      w_default_date_start = document.getElementById(id + '_default_date_id_temp_start').value;
      w_default_date_end = document.getElementById(id + '_default_date_id_temp_end').value;
      w_min_date = document.getElementById(id + '_min_date_id_temp').value;
      w_max_date = document.getElementById(id + '_max_date_id_temp').value;
      w_invalid_dates = document.getElementById(id + '_invalid_dates_id_temp').value;
      w_hide_time = document.getElementById(id + '_hide_timeform_id_temp').value;
      w_show_image = document.getElementById(id + '_show_imageform_id_temp').value;
      w_disable_past_days = document.getElementById(id + '_dis_past_daysform_id_temp') ? document.getElementById(id + '_dis_past_daysform_id_temp').value : 'no';

      var show_week_days_input = document.getElementById(id + "_show_week_days");
      w_sunday = show_week_days_input.getAttribute('sunday');
      w_monday = show_week_days_input.getAttribute('monday');
      w_tuesday = show_week_days_input.getAttribute('tuesday');
      w_wednesday = show_week_days_input.getAttribute('wednesday');
      w_thursday = show_week_days_input.getAttribute('thursday');
      w_friday = show_week_days_input.getAttribute('friday');
      w_saturday = show_week_days_input.getAttribute('saturday');
      w_show_days = [w_sunday, w_monday, w_tuesday, w_wednesday, w_thursday, w_friday, w_saturday];
      type_date_range(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_date, w_required, w_show_image, w_class, w_format, w_start_day, w_default_date_start, w_default_date_end, w_min_date, w_max_date, w_invalid_dates, w_show_days, w_hide_time, w_but_val, w_attr_name, w_attr_value, w_disable_past_days);
      break;
    }
    case 'type_date_fields': {
      atrs = return_attributes(id + '_dayform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_day = document.getElementById(id + '_dayform_id_temp').value;
      w_month = document.getElementById(id + '_monthform_id_temp').value;
      w_year = document.getElementById(id + '_yearform_id_temp').value;
      w_day_type = document.getElementById(id + '_dayform_id_temp').tagName;
      w_month_type = document.getElementById(id + '_monthform_id_temp').tagName;
      w_year_type = document.getElementById(id + '_yearform_id_temp').tagName;
      w_day_label = document.getElementById(id + '_day_label').innerHTML;
      w_month_label = document.getElementById(id + '_month_label').innerHTML;
      w_year_label = document.getElementById(id + '_year_label').innerHTML;
      w_min_day = document.getElementById(id + '_min_day_id_temp').value;
      w_min_month = document.getElementById(id + '_min_month_id_temp').value;
      w_min_year = document.getElementById(id + '_min_year_id_temp').value;
      w_min_dob_alert = document.getElementById(id + '_min_dob_alert_id_temp').value;

      s = document.getElementById(id + '_dayform_id_temp').style.width;
      w_day_size = s.substring(0, s.length - 2);
      s = document.getElementById(id + '_monthform_id_temp').style.width;
      w_month_size = s.substring(0, s.length - 2);
      s = document.getElementById(id + '_yearform_id_temp').style.width;
      w_year_size = s.substring(0, s.length - 2);
      w_from = document.getElementById(id + '_yearform_id_temp').getAttribute('from');
      w_to = document.getElementById(id + '_yearform_id_temp').getAttribute('to');
      w_divider = document.getElementById(id + '_separator1').innerHTML;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      type_date_fields(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_day, w_month, w_year, w_day_type, w_month_type, w_year_type, w_day_label, w_month_label, w_year_label, w_day_size, w_month_size, w_year_size, w_required, w_class, w_from, w_to, w_min_day, w_min_month, w_min_year, w_min_dob_alert, w_divider, w_attr_name, w_attr_value);
      break;
    }
    case 'type_own_select': {
      jQuery('#' + id + '_elementform_id_temp option').each(function () {
        w_choices[t] = jQuery(this).html();
        w_choices_value[t] = jQuery(this).val();
        w_choices_checked[t] = jQuery(this)[0].selected;
        if (jQuery(this).attr('where')) {
          w_choices_params[t] = jQuery(this).attr('where') + '[where_order_by]' + jQuery(this).attr('order_by') + '[db_info]' + jQuery(this).attr('db_info');
        }
        else {
          w_choices_params[t] = '';
        }
        if (jQuery(this).val()) {
          w_choices_disabled[t] = false;
        }
        else {
          w_choices_disabled[t] = true;
        }
        t++;
      });
      w_value_disabled = document.getElementById(id + '_value_disabledform_id_temp').value;
      w_use_for_submission = document.getElementById(id + "_use_for_submissionform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_own_select(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_choices, w_choices_checked, w_required, w_value_disabled, w_class, w_attr_name, w_attr_value, w_choices_disabled, w_choices_value, w_choices_params, w_use_for_submission);
      break;
    }
    case 'type_paypal_select': {
      jQuery('#' + id + '_elementform_id_temp option').each(function () {
        w_choices[t] = jQuery(this).html();
        w_choices_price[t] = jQuery(this).val();
        w_choices_checked[t] = jQuery(this)[0].selected;
        if (jQuery(this).attr('where')) {
          w_choices_params[t] = jQuery(this).attr('where') + '[where_order_by]' + jQuery(this).attr('order_by') + '[db_info]' + jQuery(this).attr('db_info');
        }
        else {
          w_choices_params[t] = '';
        }
        if (jQuery(this)[0].value == "") {
          w_choices_disabled[t] = true;
        }
        else {
          w_choices_disabled[t] = false;
        }
        t++;
      });
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = new Array();
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1].push(document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }
      }
      w_quantity = "no";
      w_quantity_value = 1;
      if (document.getElementById(id + "_element_quantityform_id_temp")) {
        w_quantity = 'yes';
        w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_paypal_select(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_choices, w_choices_price, w_choices_checked, w_required, w_quantity, w_quantity_value, w_class, w_attr_name, w_attr_value, w_choices_disabled, w_property, w_property_values, w_choices_params);
      break;
    }
    case 'type_country': {
      w_countries = [];
      select_ = document.getElementById(id + '_elementform_id_temp');
      n = select_.childNodes.length;
      for (i = 0; i < n; i++) {
        w_countries.push(select_.childNodes[i].value);
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_country(id, w_field_label, w_field_label_size, w_hide_label, w_countries, w_field_label_pos, w_size, w_required, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_file_upload': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_destination = document.getElementById(id + "_destination").value.replace("***destinationverj" + id + "***", "").replace("***destinationskizb" + id + "***", "");
      w_extension = document.getElementById(id + "_extension").value.replace("***extensionverj" + id + "***", "").replace("***extensionskizb" + id + "***", "");
      w_max_size = document.getElementById(id + "_max_size").value.replace("***max_sizeverj" + id + "***", "").replace("***max_sizeskizb" + id + "***", "");
      w_multiple = (document.getElementById(id + "_elementform_id_temp").getAttribute('multiple') ? 'yes' : 'no');
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_file_upload(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_destination, w_extension, w_max_size, w_required, w_multiple, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_captcha': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_digit = document.getElementById("_wd_captchaform_id_temp").getAttribute("digit");
      atrs = return_attributes('_wd_captchaform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_captcha(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_digit, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_arithmetic_captcha': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_count = document.getElementById("_wd_arithmetic_captchaform_id_temp").getAttribute("operations_count");
      w_operations = document.getElementById("_wd_arithmetic_captchaform_id_temp").getAttribute("operations");
      w_input_size = document.getElementById("_wd_arithmetic_captchaform_id_temp").getAttribute("input_size");
      atrs = return_attributes('_wd_captchaform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_arithmetic_captcha(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_count, w_operations, w_class, w_input_size, w_attr_name, w_attr_value);
      break;
    }
    case 'type_recaptcha': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_type = document.getElementById("wd_recaptchaform_id_temp").getAttribute("w_type");
      w_position = document.getElementById("wd_recaptchaform_id_temp").getAttribute("position");
      type_recaptcha(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_type, w_position);
      break;
    }
    case 'type_map': {
      w_lat = [];
      w_long = [];
      w_info = [];
      w_center_x = document.getElementById(id + "_elementform_id_temp").getAttribute("center_x");
      w_center_y = document.getElementById(id + "_elementform_id_temp").getAttribute("center_y");
      w_zoom = document.getElementById(id + "_elementform_id_temp").getAttribute("zoom");
      w_width = document.getElementById(id + "_elementform_id_temp").style.width == "" ? "" : parseInt(document.getElementById(id + "_elementform_id_temp").style.width);
      w_height = parseInt(document.getElementById(id + "_elementform_id_temp").style.height);
      for (j = 0; j <= 20; j++) {
        if (document.getElementById(id + "_elementform_id_temp").getAttribute("lat" + j)) {
          w_lat.push(document.getElementById(id + "_elementform_id_temp").getAttribute("lat" + j));
          w_long.push(document.getElementById(id + "_elementform_id_temp").getAttribute("long" + j));
          w_info.push(document.getElementById(id + "_elementform_id_temp").getAttribute("info" + j));
        }
      }
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_map(id, w_center_x, w_center_y, w_long, w_lat, w_zoom, w_width, w_height, w_class, w_info, w_attr_name, w_attr_value);
      break;
    }
    case 'type_mark_map': {
      w_info = document.getElementById(id + "_elementform_id_temp").getAttribute("info0");
      w_long = document.getElementById(id + "_elementform_id_temp").getAttribute("long0");
      w_lat = document.getElementById(id + "_elementform_id_temp").getAttribute("lat0");
      w_zoom = document.getElementById(id + "_elementform_id_temp").getAttribute("zoom");
      w_width = document.getElementById(id + "_elementform_id_temp").style.width == "" ? "" : parseInt(document.getElementById(id + "_elementform_id_temp").style.width);
      w_height = parseInt(document.getElementById(id + "_elementform_id_temp").style.height);
      w_center_x = document.getElementById(id + "_elementform_id_temp").getAttribute("center_x");
      w_center_y = document.getElementById(id + "_elementform_id_temp").getAttribute("center_y");
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_mark_map(id, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_center_x, w_center_y, w_long, w_lat, w_zoom, w_width, w_height, w_class, w_info, w_attr_name, w_attr_value);
      break;
    }
    case 'type_submit_reset': {
      atrs = return_attributes(id + '_element_submitform_id_temp');
      w_act = !(document.getElementById(id + "_element_resetform_id_temp").style.display == "none");
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_submit_title = document.getElementById(id + "_element_submitform_id_temp").value;
      w_reset_title = document.getElementById(id + "_element_resetform_id_temp").value;
      type_submit_reset(id, w_submit_title, w_reset_title, w_class, w_act, w_attr_name, w_attr_value);
      break;
    }
    case 'type_button': {
      w_title = new Array();
      w_func = new Array();
      t = 0;
      v = 0;
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_elementform_id_temp" + k)) {
          w_title[t] = document.getElementById(id + "_elementform_id_temp" + k).value;
          w_func[t] = document.getElementById(id + "_elementform_id_temp" + k).getAttribute("onclick");
          t++;
          v = k;
        }
      }
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_button(id, w_title, w_func, w_class, w_attr_name, w_attr_value);
      break;
    }
    case 'type_hidden': {
      w_value = document.getElementById(id + "_elementform_id_temp").value;
      w_name = document.getElementById(id + "_elementform_id_temp").name;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      type_hidden(id, w_name, w_value, w_attr_name, w_attr_value);
      break;
    }
    case 'type_signature': {
      var w_hide_label = document.getElementById(id + '_hide_labelform_id_temp').value;
      var w_width = document.getElementById(id + '_canvasform_id_temp').style.width;
      var w_height = document.getElementById(id + '_canvasform_id_temp').style.height;
      var w_destination = document.getElementById(id + '_destination').value;
      var params = {
        'field_type' : 'type_signature',
        'field_label': w_field_label,
        'field_label_pos': w_field_label_pos,
        'field_label_hide': w_hide_label,
        'required': w_required,
        'field_label_size': w_field_label_size,
        'canvas' : {
          'width': parseInt(w_width),
          'height': parseInt(w_height)
        },
        'class': w_class,
        'destination': w_destination
      };
      type_signature( id, params );
      break;
    }
  }
  if ( typeof e != "undefined" ) {
    e.stopPropagation();
    e.preventDefault();
  }
}

function fm_add_page() {
  for (t = form_view_max; t > 0; t--) {
    if (document.getElementById('form_id_tempform_view' + t)) {
      form_view = t;
      break;
    }
  }
  form_view_count = jQuery('.wdform-page-and-images').length;
  if (form_view_count == 1) {
    var icon_edit = document.createElement("span");
    icon_edit.setAttribute('title', 'Edit the pagination options');
    icon_edit.setAttribute("class", "page_toolbar fm-ico-edit");
    icon_edit.setAttribute("onclick", 'el_page_navigation()');
    var edit_page_navigation = document.getElementById("edit_page_navigation");
    edit_page_navigation.appendChild(icon_edit);
    document.getElementById('page_navigation').appendChild(edit_page_navigation);
  }
  jQuery('#page_bar').removeClass('form_view_hide');
  old_to_gen = form_view;
  form_view_max++;

  form_view = form_view_max;

  if (form_view > 1) {
    jQuery(".form_id_tempform_view_img").removeClass('form_view_hide');
  }
  table = document.createElement('div');
  table.setAttribute('class', 'wdform-page-and-images fm-form-builder');
  form_tempform_view = document.createElement('div');
  form_tempform_view.setAttribute('id', 'form_id_tempform_view' + form_view);
  form_tempform_view.setAttribute('page_title', 'Untitled Page');
  form_tempform_view.setAttribute('class', 'wdform_page');

  page_toolbar_wrap = document.createElement('div');
  page_toolbar_wrap.setAttribute('id', 'form_id_tempform_view_img' + form_view);
  page_toolbar_wrap.setAttribute('class', 'form_id_tempform_view_img');

  page_title_div = document.createElement('div');
  page_title_div.setAttribute('class', 'wdform_page_title');
  page_toolbar_wrap.appendChild(page_title_div);

  page_toolbar = document.createElement('div');
  var icon_show_hide = document.createElement('span');
  icon_show_hide.setAttribute('title', 'Show or hide the page');
  icon_show_hide.setAttribute("class", "page_toolbar fm-ico-collapse");
  icon_show_hide.setAttribute('id', 'show_page_img_' + form_view);
  icon_show_hide.setAttribute('onClick', 'show_or_hide("' + form_view + '"); change_show_hide_icon(this);');

  var icon_remove = document.createElement("span");
  icon_remove.setAttribute('title', 'Delete the page');
  icon_remove.setAttribute("class", "page_toolbar fm-ico-delete");
  icon_remove.setAttribute("onclick", 'remove_page("' + form_view + '")');

  var icon_edit = document.createElement("span");
  icon_edit.setAttribute('title', 'Edit the page');
  icon_edit.setAttribute("class", "page_toolbar fm-ico-edit");
  icon_edit.setAttribute("onclick", 'edit_page_break("' + form_view + '")');

  page_toolbar.appendChild(icon_remove);
  page_toolbar.appendChild(icon_edit);
  page_toolbar.appendChild(icon_show_hide);
  page_toolbar_wrap.appendChild(page_toolbar);

  tr = document.createElement('div');
  tr.setAttribute('class', 'wdform_section');
  tr_page_nav = document.createElement('div');
  tr_page_nav.setAttribute('valign', 'top');
  tr_page_nav.setAttribute('class', 'wdform_footer');
  tr_page_nav.style.width = "100%";
  td_page_nav = document.createElement('div');
  td_page_nav.style.width = "100%";
  table_min_page_nav = document.createElement('div');
  table_min_page_nav.style.width = "100%";
  table_min_page_nav.style.display = "table";
  tbody_min_page_nav = document.createElement('div');
  tbody_min_page_nav.style.display = "table-row-group";
  tr_min_page_nav = document.createElement('div');
  tr_min_page_nav.setAttribute('id', 'form_id_temppage_nav' + form_view);
  tr_min_page_nav.style.display = "table-row";
  table_min = document.createElement('div');
  table_min.setAttribute('class', 'wdform_column');
  table_min1 = document.createElement('div');
  table_min1.setAttribute('class', 'wdform_column');
  tr.appendChild(table_min);
  // tr.appendChild(table_min1);
  tbody_min_page_nav.appendChild(tr_min_page_nav);
  table_min_page_nav.appendChild(tbody_min_page_nav);
  td_page_nav.appendChild(table_min_page_nav);
  tr_page_nav.appendChild(td_page_nav);
  form_tempform_view.appendChild(tr);
  form_tempform_view.appendChild(tr_page_nav);
  table.appendChild(page_toolbar_wrap);
  table.appendChild(form_tempform_view);
  document.getElementById('take').insertBefore(table, document.getElementById("add_field_cont"));

  form_view_element = document.getElementById('form_id_tempform_view' + form_view);
  form_view_element.setAttribute('next_title', 'Next');
  form_view_element.setAttribute('next_type', 'text');
  form_view_element.setAttribute('next_class', 'wdform-page-button');
  form_view_element.setAttribute('next_checkable', 'true');
  form_view_element.setAttribute('previous_title', 'Previous');
  form_view_element.setAttribute('previous_type', 'text');
  form_view_element.setAttribute('previous_class', 'wdform-page-button');
  form_view_element.setAttribute('previous_checkable', 'false');
  form_view_element.setAttribute('page_title', 'Untitled Page');
  page_title_div.innerHTML = '<span class="fm-ico-draggable"></span>Untitled Page';
  if (form_view_count == 2) {
    generate_page_nav(form_view);
    generate_page_nav(old_to_gen);
  }
  else {
    generate_page_nav(form_view);
  }
  all_sortable_events();
  jQuery(".wdform_arrows").hide();
  draggable_page_break(nextID, form_view_max);
  nextID = "";
  form_view_element.scrollIntoView();
}

function add(key, after_edit, wdid) {
  if (document.getElementById("element_type").value == "type_grading") {
    for (k = 100; k > 0; k--) {
      if (document.getElementById("el_items" + k)) {
        break;
      }
    }
    m = k;
    var items_input = "";
    for (i = 0; i <= m; i++) {
      if (document.getElementById("el_items" + i)) {
        items_input = items_input + document.getElementById("el_items" + i).value + ":";
      }
    }
    items_input += document.getElementById("element_total").value;
    if (document.getElementById('editing_id').value) {
      id = document.getElementById('editing_id').value;
    }
    else {
      id = gen;
    }
    var hidden_input_item = document.createElement('input');
    hidden_input_item.setAttribute("id", id + "_hidden_itemform_id_temp");
    hidden_input_item.setAttribute("name", id + "_hidden_itemform_id_temp");
    hidden_input_item.setAttribute("type", "hidden");
    hidden_input_item.setAttribute("value", items_input);
    var td_for_hidden = document.getElementById(id + "_element_sectionform_id_temp");
    td_for_hidden.appendChild(hidden_input_item);
  }
  if (document.getElementById("element_type").value == "type_matrix") {
    for (i = 100; i > 0; i--) {
      if (document.getElementById("el_rows" + i)) {
        break;
      }
    }
    m = i;
    for (i = 100; i > 0; i--) {
      if (document.getElementById("el_columns" + i)) {
        break;
      }
    }
    n = i;
    var row_input = "";
    var column_input = "";
    var row_num = "";
    var column_num = "";
    for (i = 1; i <= m; i++) {
      if (document.getElementById("el_rows" + i)) {
        row_input = row_input + document.getElementById("el_rows" + i).value + "***";
        row_num += i + ',';
      }
    }
    for (i = 1; i <= n; i++) {
      if (document.getElementById("el_columns" + i)) {
        column_input = column_input + document.getElementById("el_columns" + i).value + "***";
        column_num += i + ',';
      }
    }
    if (document.getElementById('editing_id').value) {
      id = document.getElementById('editing_id').value;
    }
    else {
      id = gen;
    }
    var td_for_hidden = document.getElementById(id + "_element_sectionform_id_temp");
    var hidden_input_row = document.createElement('input');
    hidden_input_row.setAttribute("id", id + "_hidden_rowform_id_temp");
    hidden_input_row.setAttribute("name", id + "_hidden_rowform_id_temp");
    hidden_input_row.setAttribute("type", "hidden");
    hidden_input_row.setAttribute("value", row_input);
    var hidden_ids_row = document.createElement('input');
    hidden_ids_row.setAttribute("id", id + "_row_idsform_id_temp");
    hidden_ids_row.setAttribute("name", id + "_row_idsform_id_temp");
    hidden_ids_row.setAttribute("type", "hidden");
    hidden_ids_row.setAttribute("value", row_num);
    var hidden_input_column = document.createElement('input');
    hidden_input_column.setAttribute("id", id + "_hidden_columnform_id_temp");
    hidden_input_column.setAttribute("name", id + "_hidden_columnform_id_temp");
    hidden_input_column.setAttribute("type", "hidden");
    hidden_input_column.setAttribute("value", column_input);
    var hidden_ids_column = document.createElement('input');
    hidden_ids_column.setAttribute("id", id + "_column_idsform_id_temp");
    hidden_ids_column.setAttribute("name", id + "_column_idsform_id_temp");
    hidden_ids_column.setAttribute("type", "hidden");
    hidden_ids_column.setAttribute("value", column_num);
    td_for_hidden.appendChild(hidden_input_row);
    td_for_hidden.appendChild(hidden_ids_row);
    td_for_hidden.appendChild(hidden_input_column);
    td_for_hidden.appendChild(hidden_ids_column);
  }
  if (document.getElementById("element_type").value == "type_section_break") {
    form_view = 0;
    for (t = form_view_max; t > 0; t--) {
      if (document.getElementById('form_id_tempform_view' + t)) {
        if (jQuery("#form_id_tempform_view" + t).is(":visible")) {
          form_view = t;
          break;
        }
      }
    }
    if (form_view == 0) {
      alert("The pages are closed");
      return;
    }
    if (document.getElementById('editing_id').value) {
      i = document.getElementById('editing_id').value;
      document.getElementById('editing_id').value = "";
      wdform_field_in_editor = document.getElementById(i + "_element_sectionform_id_temp");
      ifr_id = "form_maker_editor_ifr";
      ifr = getIFrameDocument(ifr_id);
      if (document.getElementById('form_maker_editor').style.display == "none") {
        wdform_field_in_editor.innerHTML = ifr.body.innerHTML;
      }
      else {
        wdform_field_in_editor.innerHTML = document.getElementById('form_maker_editor').value;
      }
    }
    else {
      i = gen;
      gen++;
      var wdform_row = document.createElement('div');
      wdform_row.setAttribute("wdid", i);
      wdform_row.setAttribute("type", "type_section_break");
      wdform_row.setAttribute("class", "wdform_tr_section_break");
      var wdform_field = document.createElement('div');
      wdform_field.setAttribute("id", "wdform_field" + i);
      wdform_field.setAttribute("type", "type_section_break");
      wdform_field.setAttribute("class", "wdform_field_section_break");
      var wdform_arrows = document.createElement('div');
      wdform_arrows.setAttribute("id", "wdform_arrows" + i);
      wdform_arrows.setAttribute("class", "wdform_arrows");
      wdform_arrows.style.display = 'none';
      wdform_field.appendChild(wdform_arrows);
      //	wdform_row.appendChild(wdform_arrows);
      wdform_row.appendChild(wdform_field);
      //var select_ = document.getElementById('sel_el_pos');
      var option = document.createElement('div');
      option.setAttribute("id", i + "_element_labelform_id_temp");
      option.style.color = 'red';
      option.innerHTML = "Section Break";
      wdform_row.appendChild(option);
      wdform_page = document.getElementById('form_id_tempform_view' + form_view);
      var arrows_body = '<span class="wdform_arrows_basic wdform_arrows_container">' +
                          '<span id="edit_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Edit the field" class="page_toolbar fm-ico-edit" ontouchend="edit(&quot;' + i + '&quot;, event)" onclick="edit(&quot;' + i + '&quot;, event)"></span>' +
                          '</span>' +
                          '<span id="duplicate_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Duplicate the field" class="page_toolbar fm-ico-duplicate" ontouchend="duplicate(&quot;' + i + '&quot;, event)" onclick="duplicate(&quot;' + i + '&quot;, event)"></span>' +
                          '</span>' +
                          '<span id="X_' + i + '" valign="middle" align="right" class="element_toolbar">' +
                            '<span title="Remove the field" class="page_toolbar fm-ico-delete" onclick="remove_section_break(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                        '</span>';
      wdform_arrows.innerHTML = arrows_body;
      var in_editor = document.createElement("div");
      in_editor.setAttribute("id", i + "_element_sectionform_id_temp");
      in_editor.setAttribute("align", 'left');
      in_editor.setAttribute("class", 'wdform_section_break');
      ifr_id = "form_maker_editor_ifr";
      ifr = getIFrameDocument(ifr_id)
      if (document.getElementById('form_maker_editor').style.display == "none") {
        in_editor.innerHTML = ifr.body.innerHTML;
      }
      else {
        in_editor.innerHTML = document.getElementById('form_maker_editor').value;
      }
      var label = document.createElement('span');
      label.setAttribute("id", i + "_element_labelform_id_temp");
      label.innerHTML = "Custom HTML" + i; //"custom_" + i;
      label.style.cssText = 'display:none';
      wdform_field.appendChild(in_editor);
      beforeTr = wdform_page.lastChild;
      wdform_page.insertBefore(wdform_row, beforeTr);
      wdform_section_new = document.createElement('div');
      wdform_section_new.setAttribute('class', 'wdform_section');
      wdform_column_new = document.createElement('div');
      wdform_column_new.setAttribute('class', 'wdform_column');
      wdform_column_new1 = document.createElement('div');
      wdform_column_new1.setAttribute('class', 'wdform_column');
      // wdform_section_new.appendChild(wdform_column_new);

      wdform_section_new.appendChild(wdform_column_new1);
      draggable_section_break(nextID, wdform_row);
      nextID = "";
      j = 2;
    }
    jQuery(".wdform_arrows").hide();
    close_window();
    all_sortable_events();
    return;
  }
  if (document.getElementById("element_type").value == "type_page_navigation") {
    document.getElementById("pages").setAttribute('show_title', document.getElementById("el_show_title_input").checked);
    document.getElementById("pages").setAttribute('show_numbers', document.getElementById("el_show_numbers_input").checked);
    if (document.getElementById("el_pagination_steps").checked) {
      document.getElementById("pages").setAttribute('type', 'steps');
      make_page_steps_front();
    }
    else if (document.getElementById("el_pagination_percentage").checked) {
      document.getElementById("pages").setAttribute('type', 'percentage');
      make_page_percentage_front();
    }
    else {
      document.getElementById("pages").setAttribute('type', 'none');
      make_page_none_front();
    }
    refresh_page_numbers();
    close_window();
    return;
  }
  if (document.getElementById("element_type").value == "type_page_break") {
    if (document.getElementById("editing_id").value) {
      i = document.getElementById("editing_id").value;
      form_view_element = document.getElementById('form_id_tempform_view' + i);
      page_title = document.getElementById('_div_between').getAttribute('page_title');
      next_title = document.getElementById('_div_between').getAttribute('next_title');
      next_type = document.getElementById('_div_between').getAttribute('next_type');
      next_class = document.getElementById('_div_between').getAttribute('next_class');
      next_checkable = document.getElementById('_div_between').getAttribute('next_checkable');
      previous_title = document.getElementById('_div_between').getAttribute('previous_title');
      previous_type = document.getElementById('_div_between').getAttribute('previous_type');
      previous_class = document.getElementById('_div_between').getAttribute('previous_class');
      previous_checkable = document.getElementById('_div_between').getAttribute('previous_checkable');
      form_view_element.setAttribute('next_title', next_title);
      form_view_element.setAttribute('next_type', next_type);
      form_view_element.setAttribute('next_class', next_class);
      form_view_element.setAttribute('next_checkable', next_checkable);
      form_view_element.setAttribute('previous_title', previous_title);
      form_view_element.setAttribute('previous_type', previous_type);
      form_view_element.setAttribute('previous_class', previous_class);
      form_view_element.setAttribute('previous_checkable', previous_checkable);
      form_view_element.setAttribute('page_title', page_title);
      document.getElementById('form_id_tempform_view_img' + i).firstChild.innerHTML = '<span class="fm-ico-draggable"></span>' + page_title;
      var input = document.getElementById('_div_between');
      atr = input.attributes;
      for (v = 0; v < 30; v++) {
        if (atr[v]) {
          if (atr[v].name.indexOf("add_") == 0) {
            form_view_element.setAttribute(atr[v].name, atr[v].value);
          }
        }
      }
      form_view_count = jQuery('.wdform-page-and-images').length;
      if (form_view_count != 1) {
        generate_page_nav(form_view);
      }
      sortable_columns();
      jQuery(".wdform_arrows").hide();
      close_window();
      return;
    }
  }

  form_view = 0;
  for (t = form_view_max; t > 0; t--) {
    if (document.getElementById('form_id_tempform_view' + t)) {
      if (jQuery("#form_id_tempform_view" + t).is(":visible")) {
        form_view = t;
        break;
      }
    }
  }
  if (form_view == 0) {
    alert("The pages are closed");
    return;
  }
  if (!document.getElementById('editing_id').value) if (key == 0) if (fm_check_something_really_important(key)) { return };
  if (document.getElementById('main_editor').style.display == "block") {
    if (document.getElementById('editing_id').value) {
      i = document.getElementById('editing_id').value;
      document.getElementById('editing_id').value = "";
      wdform_field = document.getElementById("wdform_field" + i);
      destroyChildren(wdform_field);
      ifr_id = "form_maker_editor_ifr";
      ifr = getIFrameDocument(ifr_id);
      if (document.getElementById('form_maker_editor').style.display == "none") {
        wdform_field.innerHTML = ifr.body.innerHTML;
      }
      else {
        wdform_field.innerHTML = document.getElementById('form_maker_editor').value;
      }
      j = 2;
    }
    else {
      i = gen;
      gen++;
      //var select_ = document.getElementById('sel_el_pos');
      var option = document.createElement('option');
      option.setAttribute("id", i + "_sel_el_pos");
      option.setAttribute("value", i);
      option.innerHTML = "Custom HTML" + i; //"custom_" + i;
      l = document.getElementById('form_id_tempform_view' + form_view).childNodes.length;
      wdform_column = document.getElementById('form_id_tempform_view' + form_view).childNodes[l - 2].firstChild;
      var wdform_row = document.createElement('div');
      wdform_row.setAttribute("wdid", i);
      wdform_row.setAttribute("class", "wdform_row ui-sortable-handle");
      var wdform_field = document.createElement('div');
      wdform_field.setAttribute("id", "wdform_field" + i);
      wdform_field.setAttribute("type", "type_editor");
      wdform_field.setAttribute("class", "wdform_field");
      wdform_field.style.cssText = 'margin-top:0px';
      var wdform_arrows = document.createElement('div');
      wdform_arrows.setAttribute("id", "wdform_arrows" + i);
      wdform_arrows.setAttribute("class", "wdform_arrows");
      wdform_arrows.style.display = 'none';
      wdform_row.appendChild(wdform_field);
      wdform_row.appendChild(wdform_arrows);

      var arrows_body = '<span class="wdform_arrows_advanced wdform_arrows_container">' +
                          '<span id="left_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Move the field to the left" class="page_toolbar dashicons dashicons-arrow-left-alt" onclick="left_row(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                          '<span id="up_' + i + '" valign="middle" class="element_toolbar">' +
                             '<span title="Move the field up" class="page_toolbar dashicons dashicons-arrow-up-alt" onclick="up_row(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                          '<span id="down_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Move the field down" class="page_toolbar dashicons dashicons-arrow-down-alt" onclick="down_row(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                          '<span id="right_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Move the field to the right" class="page_toolbar dashicons dashicons-arrow-right-alt" onclick="right_row(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                          '<span id="page_up_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Move the field to the upper page" class="page_toolbar dashicons dashicons-upload" onclick="page_up(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                          '<span id="page_down_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Move the field to the lower page" class="page_toolbar dashicons dashicons-download" onclick="page_down(&quot;' + i + '&quot;)"></span>' +
                          '</span>' +
                        '</span>' +
                        '<span class="wdform_arrows_basic wdform_arrows_container">' +
                          '<span id="edit_' + i + '" valign="middle" class="element_toolbar">' +
                            '<span title="Edit the field" class="page_toolbar fm-ico-edit" ontouchend="edit(&quot;' + i + '&quot;, event)" onclick="edit(&quot;' + i + '&quot;, event)"></span>' +
                          '</span>' +
                          '<span id="duplicate_' + i + '" valign="middle" class="element_toolbar">' +
                           '<span title="Duplicate the field" class="page_toolbar fm-ico-duplicate" ontouchend="duplicate(duplicate(&quot;' + i + '&quot;, event))" onclick="duplicate(&quot;' + i + '&quot;, event)"></span>' +
                          '</span>' +
                          '<span id="X_' + i + '" valign="middle" align="right" class="element_toolbar">' +
                            '<span title="Remove the field" class="page_toolbar fm-ico-delete" ontouchend="remove_field(&quot;' + i + '&quot;, event)" onclick="remove_field(&quot;' + i + '&quot;, event)"></span>' +
                          '</span>' +
                        '</span>';
      wdform_arrows.innerHTML = arrows_body;

      ifr_id = "form_maker_editor_ifr";
      ifr = getIFrameDocument(ifr_id)
      if (document.getElementById('form_maker_editor').style.display == "none") {
        wdform_field.innerHTML = ifr.body.innerHTML;
      }
      else {
        wdform_field.innerHTML = document.getElementById('form_maker_editor').value;
      }
      var label = document.createElement('span');
      label.setAttribute("id", i + "_element_labelform_id_temp");
      label.innerHTML = "Custom HTML" + i; //"custom_" + i;
      label.style.color = 'red';
      wdform_row.appendChild(label);

      add_field_in_position(nextID, wdform_row);
      nextID = "";

      j = 2;
    }
    close_window();
  }
  else if (document.getElementById('show_table').innerHTML) {
    if (document.getElementById('editing_id').value) {
      i = document.getElementById('editing_id').value;
    }
    else {
      i = gen;
    }
    type = document.getElementById("element_type").value;
    if (type == "type_hidden") {
      if (document.getElementById(i + '_elementform_id_temp').name == "") {
        alert("The name of the field is required.");
        return;
      }
    }
    if (type == "type_map") {
      if (typeof gmapdata[i] == "undefined" || typeof gmapdata[i].getCenter() == "undefined") {
        alert("Please go to Global Options to setup the Map API key. It may take up to 5 minutes for API key change to take effect.");
        return false;
      }
      else {
        if_gmap_updateMap(i);
      }
    }
    if (type == "type_mark_map") {
      if (typeof gmapdata[i] == "undefined" || typeof gmapdata[i].getCenter() == "undefined") {
        alert("Please go to Global Options to setup the Map API key. It may take up to 5 minutes for API key change to take effect.");
        return false;
      }
      else {
        if_gmap_updateMap(i);
      }
    }
    if (type == "type_date_fields") {
      w_min_day = document.getElementById('edit_for_min_day').value;
      w_min_month = document.getElementById('edit_for_min_month').value;
      w_min_year = document.getElementById('edit_for_min_year').value;

      year_interval_from = document.getElementById('edit_for_year_interval_from').value;
      year_interval_to = document.getElementById('edit_for_year_interval_to').value;

      if (  w_min_day != "" || w_min_month != "" || w_min_year != "" ) {
        if ( w_min_day.length < 1 || w_min_month.length < 1  || w_min_year.length < 1 ) {
          alert("Please fill in all 3 fields of Min Value of Date.");
          return false;
        }
        else {
          if ( w_min_year < year_interval_from || w_min_year > year_interval_to ) {
            alert("Min Year does not correspond with Year Interval.");
            return false;
          }
          if ( w_min_day == "00" || w_min_day.length != 2 || w_min_month == "00" || w_min_month.length != 2 || w_min_year.length != 4 ) {
            alert("Wrong format of min date");
            return false;
          }
        }
      }
    }
    if (document.getElementById(i + '_element_labelform_id_temp').innerHTML) {
      if (document.getElementById('editing_id').value) {
        Disable();
        i = document.getElementById('editing_id').value;
        in_lab = false;
        labels_array = new Array();
        for (w = 0; w < gen; w++) {
          if (w != i) {
            if (document.getElementById(w + '_element_labelform_id_temp')) {
              labels_array.push(document.getElementById(w + '_element_labelform_id_temp').innerHTML);
            }
          }
        }
        for (t = 0; t < labels_array.length; t++) {
          if (document.getElementById(i + '_element_labelform_id_temp').innerHTML == labels_array[t]) {
            in_lab = true;
            break;
          }
        }
        if (in_lab) {
          alert('Sorry, the labels must be unique.');
          return;
        }
        else {
          document.getElementById('editing_id').value = "";
          wdform_field = document.getElementById("wdform_field" + i);
          wdform_arrows = document.getElementById("wdform_arrows" + i);
          destroyChildren(wdform_field);
          var add1 = document.getElementById(i + '_label_sectionform_id_temp');
          var add2 = document.getElementById(i + '_element_sectionform_id_temp');
          wdform_field.appendChild(wdform_arrows);
          wdform_field.appendChild(add1);
          wdform_field.appendChild(add2);
          if (type == "type_submitter_mail" || type == "type_password") {
            var br_submitter_mail = document.createElement('br');
            var add1_1 = document.getElementById(i + '_1_label_sectionform_id_temp');
            var add2_2 = document.getElementById(i + '_1_element_sectionform_id_temp');
            wdform_field.appendChild(br_submitter_mail);
            wdform_field.appendChild(add1_1);
            wdform_field.appendChild(add2_2);
          }
          j = 2;
          close_window();
          call(i, key);
        }
      }
      else {
        i = gen;
        in_lab = false;
        labels_array = new Array();
        for (w = 0; w < gen; w++) {
          if (document.getElementById(w + '_element_labelform_id_temp')) {
            labels_array.push(document.getElementById(w + '_element_labelform_id_temp').innerHTML);
          }
        }
        for (t = 0; t < labels_array.length; t++) {
          if (document.getElementById(i + '_element_labelform_id_temp').innerHTML == labels_array[t]) {
            in_lab = true;
            break;
          }
        }
        if (in_lab) {
          alert('Sorry, the labels must be unique.');
          return
        }
        else {
          if (type == "type_address") {
            gen = gen + 6;
          }
          else {
            gen++;
          }
          l = document.getElementById('form_id_tempform_view' + form_view).childNodes.length;
          wdform_column = document.getElementById('form_id_tempform_view' + form_view).childNodes[l - 2].firstChild;
          var wdform_row = document.createElement('div');
          wdform_row.setAttribute("wdid", i);
          wdform_row.setAttribute("class", "wdform_row ui-sortable-handle");
          var wdform_field = document.createElement('div');
          wdform_field.setAttribute("id", "wdform_field" + i);
          wdform_field.setAttribute("type", type);
          wdform_field.setAttribute("class", "wdform_field");
          wdform_field.style.display = "table-cell";
          var wdform_arrows = document.createElement('div');
          wdform_arrows.setAttribute("id", "wdform_arrows" + i);
          wdform_arrows.setAttribute("class", "wdform_arrows");
          wdform_arrows.style.display = 'none';
          wdform_row.appendChild(wdform_arrows);
          wdform_row.appendChild(wdform_field);

          var arrows_body = '<span class="wdform_arrows_advanced wdform_arrows_container">' +
                              '<span id="left_' + i + '" valign="middle" class="element_toolbar">' +
                                '<span title="Move the field to the left" class="page_toolbar dashicons dashicons-arrow-left-alt" onclick="left_row(&quot;' + i + '&quot;)"></span>' +
                              '</span>' +
                              '<span id="up_' + i + '" valign="middle" class="element_toolbar">' +
                               '<span title="Move the field up" class="page_toolbar dashicons dashicons-arrow-up-alt" onclick="up_row(&quot;' + i + '&quot;)"></span>' +
                              '</span>' +
                              '<span id="down_' + i + '" valign="middle" class="element_toolbar">' +
                               '<span title="Move the field down" class="page_toolbar dashicons dashicons-arrow-down-alt" onclick="down_row(&quot;' + i + '&quot;)"></span>' +
                              '</span>' +
                              '<span id="right_' + i + '" valign="middle" class="element_toolbar">' +
                               '<span title="Move the field to the right" class="page_toolbar dashicons dashicons-arrow-right-alt" onclick="right_row(&quot;' + i + '&quot;)"></span>' +
                              '</span>' +
                              '<span id="page_up_' + i + '" valign="middle" class="element_toolbar">' +
                                '<span title="Move the field to the upper page" class="page_toolbar dashicons dashicons-upload" onclick="page_up(&quot;' + i + '&quot;)"></span>' +
                              '</span>' +
                              '<span id="page_down_' + i + '" valign="middle" class="element_toolbar">' +
                                '<span title="Move the field to the lower page" class="page_toolbar dashicons dashicons-download" onclick="page_down(&quot;' + i + '&quot;)"></span>' +
                              '</span>' +
                            '</span>' +
                            '<span class="wdform_arrows_basic wdform_arrows_container">' +
                              '<span id="edit_' + i + '" valign="middle" class="element_toolbar">' +
                                '<span title="Edit the field" class="page_toolbar fm-ico-edit" ontouchend="edit(&quot;' + i + '&quot;, event)" onclick="edit(&quot;' + i + '&quot;, event)"></span>' +
                              '</span>' +
                              (type != "type_captcha" && type != "type_arithmetic_captcha" && type != "type_recaptcha" && type != "type_send_copy" && type != "type_stripe" ?
                              '<span id="duplicate_' + i + '" valign="middle" class="element_toolbar">' +
                                '<span title="Duplicate the field" class="page_toolbar fm-ico-duplicate" ontouchend="duplicate(&quot;' + i + '&quot;, event)" onclick="duplicate(&quot;' + i + '&quot;, event)"></span>' +
                              '</span>' : '')+
                              '<span id="X_' + i + '" valign="middle" align="right" class="element_toolbar">' +
                               '<span title="Remove the field" class="page_toolbar fm-ico-delete" ontouchend="remove_field(&quot;' + i + '&quot;, event)" onclick="remove_field(&quot;' + i + '&quot;, event)"></span>' +
                              '</span>' +
                            '</span>';
          wdform_arrows.innerHTML = arrows_body;
          var add1 = document.getElementById(i + '_label_sectionform_id_temp');
          var add2 = document.getElementById(i + '_element_sectionform_id_temp');
          wdform_field.appendChild(add1);
          wdform_field.appendChild(add2);
          if (type == "type_submitter_mail" || type == "type_password") {
            var br_submitter_mail = document.createElement('br');
            var add1_1 = document.getElementById(i + '_1_label_sectionform_id_temp');
            var add2_2 = document.getElementById(i + '_1_element_sectionform_id_temp');
            wdform_field.appendChild(br_submitter_mail);
            wdform_field.appendChild(add1_1);
            wdform_field.appendChild(add2_2);
          }

          add_field_in_position(nextID, wdform_row);
          nextID = "";

          j = 2;
          close_window();
          call(i, key);
        }
      }
    }
    else {
      alert("The field label is required.");
      return;
    }
  }
  else {
    alert("Please select an element to add.");
  }
  jQuery(".wdform_arrows_advanced").hide();

  jQuery(".wdform_page input[type='text'], .wdform_page input[type='password'], .wdform_page input[type='file'], .wdform_page textarea, .wdform_page input[type='checkbox'], .wdform_page input[type='radio'], .wdform_page select").prop("disabled", true);
  all_sortable_events();
}

/**
 * Add new field before submit button.
 */
function move_submit_to_end(column) {
  var last_child = jQuery(column).children(':not(.fm-hidden)').last();
  if (last_child.find('[type=type_submit_reset]').length) {
    return last_child;
  }
  return false;
}

/**
 * Add field on drag and drop position.
 *
 * @param nextID
 * @param wdform_row
 */
function add_field_in_position( nextID, wdform_row ) {
  if( typeof nextID === 'undefined' || nextID === null || nextID == "" ) {
    var wdform_col = jQuery('#cur_column');           // getting current column for insert
    if ( wdform_col.val() == 1 ) {  // when add field button submitted not moved
      var column = jQuery('<div class="wdform_column"></div>').append(wdform_row);
      var submit_button_parent = move_submit_to_end(wdform_col);
      if ( submit_button_parent !== false ) {
        jQuery(column).insertBefore( submit_button_parent );
      } else {
        wdform_col.append(column);
      }
    }
    else {
        wdform_col.append(wdform_row);
    }
  }
  else {
    beforeTr = document.getElementById("wdform_field" + nextID).parentNode;
    wdform_column = beforeTr.parentNode;
    wdform_column.insertBefore( wdform_row, beforeTr );
  }

  jQuery(window).scrollTop(jQuery(wdform_row).offset().top - 100);
  jQuery("#cur_column").removeAttr("id");
}

/**
 * Add page break on drag and drop position.
 *
 * @param nextID
 * @param form_view_max
 */
function draggable_page_break( nextID, form_view_max ) {
  var boundary = jQuery(".wdform_row[wdid='" + nextID + "']");                    // break point
  var currFormview = "form_id_tempform_view" + (form_view_max);                   // get current page container  
  if( typeof nextID === 'undefined' || nextID === null || nextID == "" || get_child_count() > 2) {         // when add field button submitted not moved
    return;
  }
  var str = jQuery("#cur_column").parent().parent().attr("id");
  var endPoint = parseInt(str.substr(str.indexOf("form_id_tempform_view") + 21)); // break conteiner number

  if ( form_view_max == 2 ) {                                                     // if break first time
    jQuery("#form_id_tempform_view" + form_view_max + " .wdform_section .wdform_column:first-child").append(jQuery(boundary.nextAll().andSelf()));
  }
  else if ( str == currFormview ) {                                               // if break container is last
    jQuery("#form_id_tempform_view" + form_view_max + " .wdform_section .wdform_column:first-child").append(jQuery(boundary.nextAll().andSelf()));
  }
  else {
    var diff = form_view_max - endPoint;                                        // get cycle count
    var fView = form_view_max;                                                  // get container counts including new one
    for ( var k = 1; k < diff; k++ ) {
      jQuery("#form_id_tempform_view" + fView + " .wdform_section .wdform_column:first-child").append(jQuery("#form_id_tempform_view" + (fView - 1) + " .wdform_section .wdform_column:first-child .wdform_row"));
      fView--;
    }
    jQuery("#form_id_tempform_view" + (fView) + " .wdform_section .wdform_column:first-child").append(jQuery(boundary.nextAll().andSelf()));
  }
  jQuery("#cur_column").removeAttr("id");  
}

// Get child column in row container
function get_child_count() {
  var temp = document.getElementById('cur_column').parentNode;
  child = temp.children;
  return child.length;
}

/**
 * Add section break on drag and drop position.
 *
 * @param nextID
 * @param wdform_row
 */
function draggable_section_break( nextID, wdform_row ) {
  if( typeof nextID === 'undefined' || nextID === null || nextID == "" ) {
    beforeTr = wdform_page.lastChild;
    wdform_page.insertBefore(wdform_section_new, beforeTr);
    return;
  }
  else if( get_child_count() > 2 ) {
    beforeTr = wdform_page.lastChild;
    wdform_page.insertBefore(wdform_section_new, beforeTr);
    return;
  }

  beforeTr = document.getElementById("wdform_field" + nextID).parentNode;
  wdform_column = beforeTr.parentNode;
  wdform_section = wdform_column.parentNode;
  wdform_column.insertBefore(wdform_row, beforeTr);

  var boundary = jQuery("#cur_column").find("[wdid='" + i + "']");  // breake point
  jQuery("<div class='wdform_section curr'><div class='wdform_column ui-sortable'>").insertAfter(boundary.parent().parent()).append(boundary.nextAll().andSelf());

  jQuery(".wdform_section .wdform_tr_section_break").each(function () { // wdform_tr_section_break move before section
    jQuery(this).insertBefore(jQuery(this).parent());
  });

  jQuery("#cur_column").removeAttr("id");
  jQuery('.curr').children().appendTo(".curr .wdform_column");
  jQuery('.curr').append("<div class='wdform_column ui-sortable'></div>");
  jQuery(".curr").removeClass( "curr" );
}

function call(i, key) {
  fm_need_enable = false;
  after_edit = false;
  if (key == 0) {
    //if (document.getElementById("pos_end").getAttribute('disabled') == 'disabled') {
    after_edit = true;
    //}
    edit(i);
    add('1', after_edit, i);
  }
  fm_need_enable = true;
}

/**
 * Actions to do on popup open.
 */
function popup_ready() {
  // Change button name.
  jQuery(".popup-title").html(form_maker.add_field);
  jQuery('#add-button-cont').html('');
  jQuery('#field_container .fm-free-message').addClass('fm-hidden');
  // Reset filter.
  jQuery(".field-types-filter").val("");
  filter(jQuery(".field-types-filter"));

  // Remove seleceted button class from all buttons.
  jQuery(".field_types .postbox button.wd-button").removeClass("button-primary");

  // Show field types container.
  jQuery(".field_types").show();
  jQuery("#field_container").removeClass('field_container_full');

  jQuery(".add-popup").slideToggle(200);
}

/**
 * Close popup.
 */
function close_window() {
  if ( fm_need_enable ) {
    popup_ready();
    /* In Firfox and Safari click action is working during the drag and drop also */
    /*jQuery(".add-new-button").attr("onclick","popup_ready(); Enable(); return false;");*/
  }
  fm_need_enable = true;
  document.getElementById('edit_table').innerHTML = "";
  document.getElementById('show_table').innerHTML = "";
  document.getElementById('main_editor').style.display = "none";
  if (document.getElementById("form_maker_editor_ifr")) {
    ifr_id = "form_maker_editor_ifr";
    ifr = getIFrameDocument(ifr_id);
    ifr.body.innerHTML = "";
  }
  document.getElementById('form_maker_editor').value = "";
  document.getElementById('editing_id').value = "";
  document.getElementById('element_type').value = "";
}

/**
 * Add field by type.
 *
 * @param event
 * @param type
 * @param subtype
 */
function addRow(event, that, type, subtype) {
  jQuery(".field_types .postbox button.wd-button").removeClass("button-primary");
  jQuery(that).addClass("button-primary");
  if (typeof subtype == "undefined") {
    var subtype = "";
  }
  if (document.getElementById('show_table').innerHTML) {
    document.getElementById('show_table').innerHTML = "";
    document.getElementById('edit_table').innerHTML = "";
  }

  var new_id = jQuery("#editing_id").val() == "" ? gen : jQuery("#editing_id").val();
  // Add field by type.
  window["el_" + type](subtype, new_id);

  fm_add_field_button(that, subtype);
}

function fm_add_field_button(that, subtype) {
  if (jQuery(that).hasClass('wd-pro-fields') && subtype != 'stripe') {
    jQuery('#edit_main_table input').prop('disabled', true);
    jQuery('#edit_main_table textarea').prop('disabled', true);
    jQuery('#edit_main_table .fm-input-container span.dashicons').attr('onclick', '');
    jQuery('#add-button-cont').removeClass('add-button-cont').html('');
    jQuery('#premium_message').removeClass('fm-hidden');
    jQuery('#stripe_message').addClass('fm-hidden');
    jQuery('#field_container .popup-body-col').addClass('fm-opacity-40');
  }
  else {
    if (subtype == 'stripe' && (is_addon_stripe_active == 0 || is_stripe_enabled == 0)) {
      jQuery('#edit_main_table input').prop('disabled', true);
      jQuery('#edit_main_table textarea').prop('disabled', true);
      jQuery('#edit_main_table .fm-input-container span.dashicons').attr('onclick', '');
      if (!jQuery(that).hasClass('wd-pro-fields') && is_stripe_enabled == 0 && is_addon_stripe_active == 1) {
        jQuery('#add-button-cont').removeClass('add-button-cont').html('<div class="error"><p>' + form_maker.stripe3 + '</p></div>');
        jQuery( '#premium_message' ).addClass( 'fm-hidden' );
        jQuery( '#stripe_message' ).addClass( 'fm-hidden' );
        jQuery('#field_container .popup-body-col').removeClass('fm-opacity-40');
      }
      else {
        jQuery('#add-button-cont').removeClass('add-button-cont').html('');
        jQuery( '#premium_message' ).addClass( 'fm-hidden' );
        jQuery( '#stripe_message' ).removeClass( 'fm-hidden' );
        jQuery('#field_container .popup-body-col').addClass('fm-opacity-40');
      }
    }
    else {
      jQuery('#add-button-cont').addClass('add-button-cont').html('<button class="button button-primary button-hero wd-add-button" onclick="add(0, false); return false;">' + form_maker.add + '</button>');
      jQuery('#premium_message').addClass('fm-hidden');
      jQuery('#stripe_message').addClass('fm-hidden');
      jQuery('#field_container .popup-body-col').removeClass('fm-opacity-40');
    }
  }
}

function el_text(subtype, new_id) {
  window["go_to_type_" + subtype](new_id);
}

function el_checkbox(subtype, new_id) {
  w_choices = ["option 1", "option 2"];
  w_choices_checked = [false, false];
  w_choices_value = ["option 1", "option 2"];
  w_choices_params = ["", ""];
  w_attr_name = [];
  w_attr_value = [];
  type_checkbox(new_id, 'Multiple Choice', '', 'top', 'right', 'no', 'ver', w_choices, w_choices_checked, '1', '', 'You have exceeded the selection limit.', 'no', 'no', 'no', '0', '', w_attr_name, w_attr_value, 'no', w_choices_value, w_choices_params, 'no');
}

function el_radio(subtype, new_id) {
  w_choices = ["option 1", "option 2"];
  w_choices_checked = [false, false];
  w_choices_value = ["option 1", "option 2"];
  w_choices_params = ["", ""];
  w_attr_name = [];
  w_attr_value = [];
  type_radio(new_id, 'Single Choice', '', 'top', 'right', 'no', 'ver', w_choices, w_choices_checked, '1', 'no', 'no', 'no', '0', '', w_attr_name, w_attr_value, 'no', w_choices_value, w_choices_params, 'no');
}

function el_survey(subtype, new_id) {
  window["go_to_type_" + subtype](new_id);
}

function el_time_and_date(subtype, new_id) {
  window["go_to_type_" + subtype](new_id);
}

function el_select(subtype, new_id) {
  window["go_to_type_" + subtype](new_id);
}

function el_file_upload(subtype, new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_file_upload(new_id, 'Upload a File', '', 'top', 'no', "form-maker", 'jpg, jpeg, png, gif, bmp, tif, tiff, svg, pdf, txt, log, doc, docx, csv, xls, xlsx, pps, ppt, pptx, xml, mp3, mp4, wma, wav, mpg, wmv', '2000', 'no', 'no', '', w_attr_name, w_attr_value);
}

function el_section_break(subtype, new_id) {
  type_section_break(new_id, "<div class='wdform-section-break-div' style='min-width: 300px; border-top:1px solid'></div>");
}

function el_page_break(subtype, new_id) {
  w_page_title = 'Untitled Page';
  w_title = ["Next", "Previous"];
  w_type = ["text", "text"];
  w_class = ["wdform-page-button", "wdform-page-button"];
  w_check = ['true', 'false'];
  w_attr_name = [];
  w_attr_value = [];
  type_page_break("0", w_page_title, w_title, w_type, w_class, w_check, w_attr_name, w_attr_value);
}

function el_map(subtype, new_id) {
  w_long = ['2.294254'];
  w_lat = ['48.858334'];
  w_info = [''];
  w_attr_name = [];
  w_attr_value = [];
  type_map(new_id, '2.294254', '48.858334', w_long, w_lat, "13", "370", "300", 'wdform_map', w_info, w_attr_name, w_attr_value);
}

function el_paypal(subtype, new_id) {
  window["go_to_type_" + subtype](new_id);
}

function el_captcha(subtype, new_id) {
  if (document.getElementById('_wd_captchaform_id_temp')
      || document.getElementById('_wd_arithmetic_captchaform_id_temp')
      || document.getElementById('wd_recaptchaform_id_temp')) {
    alert(form_maker.captcha_created);
    return;
  }
  window["go_to_type_" + subtype](new_id);
}

function el_button(subtype, new_id) {
  window["go_to_type_" + subtype](new_id);
}

function el_editor(subtype, new_id) {
  type_editor(new_id, '');
}

function el_signature(subtype, new_id) {
  var params = {
    'field_type' : 'type_signature',
    'field_label': 'Signature',
    'field_label_pos': 'top',
    'field_label_hide': 'no',
    'required': 'no',
    'field_label_size': '',
    'canvas' : {
      'width': 200,
      'height': 150
    },
    'class': '',
    'destination': 'form-maker'
  };
  type_signature( new_id, params );
}

function create_option_container(label, input, id, visible) {
  var option_div = jQuery('<div class="fm-option-container"' + (typeof id != 'undefined' ? ' id="' + id + '"' : '') + (visible == false ? ' style="display: none;"' : '') + '></div>');
  if (label != null) {
    var label_div = jQuery('<div class="fm-label-container"></div>');
    label_div.append(label);
    option_div.append(label_div);
  }
  if (input != null) {
    var input_div = jQuery('<div class="wd-group fm-input-container wd-has-placeholder' + (label == null ? ' fm-width-100' : '') + '"></div>');
    input_div.append(input);
    option_div.append(input_div);
  }
  return option_div;
}

function create_double_option_container(label1, input1, label2, input2, val_disabled) {
  var option_div = jQuery('<div class="fm-option-container fm-double-option-container"' + '></div>');
  var wrapper1 = jQuery('<div class="fm-option-wrapper1"></div>');
  var wrapper2 = jQuery('<div class="fm-option-wrapper2" ' + (val_disabled === 'yes' ? '' : 'style="display:none;"') +  '></div>');

  if (label1 != null) {
    var label_1_div = jQuery('<div class="fm-label-container"></div>');
    wrapper1.append(label_1_div)
    label_1_div.append(label1);
    option_div.append(wrapper1);
  }
  if (input1 != null) {
    var input_1_div = jQuery('<div class="wd-group fm-input-container wd-has-placeholder' + (label1 == null ? ' fm-width-100' : '') + '"></div>');
    wrapper1.append(input_1_div)
    input_1_div.append(input1);
    option_div.append(wrapper1);
  }

  if (label2 != null) {
    var label_2_div = jQuery('<div class="fm-label-container"></div>');
    wrapper2.append(label_2_div);
    label_2_div.append(label2);
    option_div.append(wrapper2);
  }
  if (input2 != null) {
    var input_2_div = jQuery('<div class="wd-group fm-input-container wd-has-placeholder' + (label2 == null ? ' fm-width-100' : '') + '"></div>');
    wrapper2.append(input_2_div);
    input_2_div.append(input2);
    option_div.append(wrapper2);
  }

  return option_div;
}

function create_advanced_options_container(advanced_options_container, id, visible) {
  var element = jQuery('<div' + (id != undefined ? ' id="' + id + '"' : '') + ' class="postbox closed"' + (visible == false ? ' style="display: none;"' : '') + '></div>');
  var content = jQuery('<button class="handlediv" type="button" aria-expanded="true" onclick="fm_toggle_postbox(this);"><span class="screen-reader-text">Toggle panel</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle" onclick="fm_toggle_postbox(this);"><span>Advanced options</span></h2>');
  element.append(content);
  element.append(advanced_options_container);
  return element;
}

function create_field_type(type) {
  var input = jQuery('<span class="fm-field-label">' + jQuery('.wd-button[data-type=' + type + ']').first().text() + '</span>');
  return create_option_container(null, input);
}

function create_label(i, w_field_label) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_label">Label</label>');
  var input = jQuery('<textarea class="fm-width-100" id="edit_for_label" rows="4" onKeyUp="change_label(\'' + i + '_element_labelform_id_temp\', this.value)">' + w_field_label + '</textarea>');
  return create_option_container(label, input);
}

function change_label(id, label, type) {
  label = label.replace(/<\/?(?!(?:\/*b|\/*p|\/*a|\/*strong|\/*span|\/*br|\/*ul|\/*ol|\/*li|\/*i)\b)(?:[^>"']|"[^"]*"|'[^']*')*>/ig, "");
  if (!type) {
    var label_value = label.replace(/[\'\"]/g, "");
    document.getElementById(id).innerHTML = label;
    document.getElementById(id).value = label_value;
  }
  else {
    label = label.replaceAll('"',"'");
    document.getElementById(type).innerHTML = label;
  }
}

function create_label_position(i, w_field_label_pos) {
  var label = jQuery('<label class="fm-field-label">Label position</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_label_position_left" name="edit_for_label_position" onchange="label_left(' + i + ')"' + (w_field_label_pos == 'top' ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="edit_for_label_position_left">Left</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_label_position_top" name="edit_for_label_position" onchange="label_top(' + i + ')"' + (w_field_label_pos == 'top' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_label_position_top">Top</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function create_label_position_stripe(i, w_field_label_pos) {
  var label = jQuery('<label class="fm-field-label">Label position</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_label_position_left" name="edit_for_label_position" onchange="label_left_stripe(' + i + ')"' + (w_field_label_pos == 'top' ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="edit_for_label_position_left">Left</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_label_position_top" name="edit_for_label_position" onchange="label_top_stripe(' + i + ')"' + (w_field_label_pos == 'top' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_label_position_top">Top</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function label_left(num) {
  if (document.getElementById(num + '_hide_labelform_id_temp').value == "no") {
    document.getElementById(num + '_label_sectionform_id_temp').style.display = "table-cell";
    document.getElementById(num + '_element_sectionform_id_temp').style.display = "table-cell";
    if (document.getElementById(num + '_1_label_sectionform_id_temp')) {
      if (document.getElementById(num + '_verification_id_temp').value == "yes") {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = document.getElementById(num + '_label_sectionform_id_temp').style.display;
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = document.getElementById(num + '_element_sectionform_id_temp').style.display;
      }
      else {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = "none";
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = "none";
      }
    }
  }
  else {
    document.getElementById(num + '_label_sectionform_id_temp').style.display = "none";
    document.getElementById(num + '_element_sectionform_id_temp').style.display = "table-cell";
    if (document.getElementById(num + '_1_label_sectionform_id_temp')) {
      if (document.getElementById(num + '_verification_id_temp').value == "yes") {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = document.getElementById(num + '_label_sectionform_id_temp').style.display;
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = document.getElementById(num + '_element_sectionform_id_temp').style.display;
      }
      else {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = "none";
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = "none";
      }
    }
  }
}

function label_top(num) {
  if (document.getElementById(num + '_hide_labelform_id_temp').value == "no") {
    document.getElementById(num + '_label_sectionform_id_temp').style.display = "block";
    document.getElementById(num + '_element_sectionform_id_temp').style.display = "block";
    if (document.getElementById(num + '_1_label_sectionform_id_temp')) {
      if (document.getElementById(num + '_verification_id_temp').value == "yes") {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = document.getElementById(num + '_label_sectionform_id_temp').style.display;
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = document.getElementById(num + '_element_sectionform_id_temp').style.display;
      }
      else {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = "none";
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = "none";
      }
    }
  }
  else {
    document.getElementById(num + '_label_sectionform_id_temp').style.display = "none";
    document.getElementById(num + '_element_sectionform_id_temp').style.display = "block";
    if (document.getElementById(num + '_1_label_sectionform_id_temp')) {
      if (document.getElementById(num + '_verification_id_temp').value == "yes") {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = "none";
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = "block";
      }
      else {
        document.getElementById(num + '_1_label_sectionform_id_temp').style.display = "none";
        document.getElementById(num + '_1_element_sectionform_id_temp').style.display = "none";
      }
    }
  }
}

function create_hide_label(i, w_hide_label) {
  var label = jQuery('<label class="fm-field-label" for="el_hide_label">Hide label</label>');
  var input = jQuery('<input type="checkbox" id="el_hide_label" onchange="hide_label(' + i + ')"' + (w_hide_label == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function hide_label(id) {
  if (document.getElementById(id + "_hide_labelform_id_temp").value == "no") {
    document.getElementById(id + "_hide_labelform_id_temp").value = "yes";
    document.getElementById(id + "_label_sectionform_id_temp").style.display = "none";
    if (document.getElementById(id + "_1_elementform_id_temp") && document.getElementById(id + "_1_elementform_id_temp").offsetParent) {
      document.getElementById(id + "_1_label_sectionform_id_temp").style.display = "none";
    }
  }
  else {
    if (document.getElementById("edit_for_label_position_left").checked) {
      document.getElementById(id + "_label_sectionform_id_temp").style.display = "table-cell";
      document.getElementById(id + "_element_sectionform_id_temp").style.display = "table-cell";
      if (document.getElementById(id + "_1_elementform_id_temp") && document.getElementById(id + "_1_elementform_id_temp").offsetParent) {
        document.getElementById(id + "_1_label_sectionform_id_temp").style.display = "table-cell";
        document.getElementById(id + "_1_element_sectionform_id_temp").style.display = "table-cell";
      }
    }
    else {
      document.getElementById(id + "_label_sectionform_id_temp").style.display = "block";
      document.getElementById(id + "_element_sectionform_id_temp").style.display = "block";
      if (document.getElementById(id + "_1_elementform_id_temp") && document.getElementById(id + "_1_elementform_id_temp").offsetParent) {
        document.getElementById(id + "_1_label_sectionform_id_temp").style.display = "block";
        document.getElementById(id + "_1_element_sectionform_id_temp").style.display = "block";
      }
    }
    document.getElementById(id + "_hide_labelform_id_temp").value = "no";
  }
}

function create_placeholder(i, w_title) {
  var label = jQuery('<label class="fm-field-label" for="el_first_value_input">Placeholder</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_first_value_input" onKeyUp="change_input_value(this.value,\'' + i + '_elementform_id_temp\')" value="' + w_title.replace(/"/g, "&quot;") + '" />');
  return create_option_container(label, input);
}

function change_input_value(first_value, id) {
  input = document.getElementById(id);
  input.title = first_value;
  input.placeholder = first_value;
}

function create_required(i, w_required) {
  var label = jQuery('<label class="fm-field-label" for="el_required">Required</label>');
  var input = jQuery('<input type="checkbox" id="el_required" onchange="set_required(\'' + i + '_required\')"' + (w_required == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_required(id, type) {
  if (document.getElementById(id + "form_id_temp").value == "yes") {
    document.getElementById(id + "form_id_temp").setAttribute("value", "no");
    document.getElementById(id + "_elementform_id_temp").innerHTML = "";
    if (typeof(type) != 'undefined') {
      document.getElementById(type + "_elementform_id_temp").innerHTML = "";
    }
  }
  else {
    document.getElementById(id + "form_id_temp").setAttribute("value", "yes");
    document.getElementById(id + "_elementform_id_temp").innerHTML = " *";
    if (typeof(type) != 'undefined') {
      document.getElementById(type + "_elementform_id_temp").innerHTML = " *";
    }
  }
}

function create_field_size(i, w_size, first_field_id, second_field_id) {
  if (first_field_id == null) {
    first_field_id = '\'' + i + '_elementform_id_temp\'';
  }
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(' + first_field_id + ', this.value, ' + second_field_id + ')" value="' + w_size + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function create_field_label_size(i, w_field_label_size, first_field_id, second_field_id) {
  if (first_field_id == null) {
    first_field_id = '\'' + i + '_label_sectionform_id_temp\'';
  }
  var label = jQuery('<label class="fm-field-label" for="edit_for_label_size">Label width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_label_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(' + first_field_id + ', this.value, ' + second_field_id + ')" value="' + w_field_label_size + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function change_w_style(id, w, type) {
  if ( w == "" ) {
    jQuery("#" + id).css("width", w);
  }
  else {
    jQuery("#" + id).css("width", w + "px");
    if (type) {
      document.getElementById(type).style.width = w + "px";
    }
  }
}

function change_h_style(id, h) {
  document.getElementById(id).style.height = h + "px";
}

function create_readonly(i, w_readonly) {
  var label = jQuery('<label class="fm-field-label" for="el_readonly">Readonly</label>');
  var input = jQuery('<input type="checkbox" id="el_readonly" onchange="set_readonly(' + i + ')"' + (w_readonly == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_readonly(id) {
  if (document.getElementById(id + "_readonlyform_id_temp").value == "no") {
    document.getElementById(id + "_readonlyform_id_temp").value = "yes";
    document.getElementById(id + "_elementform_id_temp").setAttribute("readonly", "readonly");
  }
  else {
    document.getElementById(id + "_elementform_id_temp").removeAttribute("readonly");
    document.getElementById(id + "_readonlyform_id_temp").value = "no";
  }
}

function create_unique_values(i, w_unique) {
  var label = jQuery('<label class="fm-field-label" for="el_unique">Allow only unique values</label>');
  var input = jQuery('<input type="checkbox" id="el_unique" onchange="set_unique(\'' + i + '_uniqueform_id_temp\')"' + (w_unique == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_unique(id) {
  if (document.getElementById(id).value == "yes") {
    document.getElementById(id).setAttribute("value", "no");
  }
  else {
    document.getElementById(id).setAttribute("value", "yes")
  }
}

function create_regexp(i, w_regExp_status) {
  var label = jQuery('<label class="fm-field-label" for="el_regExp_' + i + '">Validation (RegExp.)</label>');
  var input = jQuery('<input type="checkbox" id="el_regExp_' + i + '" onchange="set_regExpStatus(\'' + i + '_regExpStatus\')"' + (w_regExp_status == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_regExpStatus(id) {
  jQuery('#edit_main_tr12, #edit_main_tr13, #edit_main_tr14, #edit_main_tr15').toggle(200);
  if (document.getElementById(id + "form_id_temp").value == "yes") {
    document.getElementById(id + "form_id_temp").setAttribute("value", "no");
  }
  else {
    document.getElementById(id + "form_id_temp").setAttribute("value", "yes");
  }
}

function create_custom_regexp(i, w_regExp_status, w_regExp_value) {
  var label = jQuery('<label class="fm-field-label regExp_cell" for="regExp_value' + i + '">Regular Expression</label>');
  var input = jQuery('<textarea id="regExp_value' + i + '" class="regExp_cell fm-width-100" onKeyUp="change_regExpValue(' + i + ', this.value , \'' + i + '_regExp_valueform_id_temp\', \'\')">' + w_regExp_value + '</textarea>');
  return create_option_container(label, input, 'edit_main_tr12', w_regExp_status == 'yes');
}

function change_regExpValue(i, regValue, regVal_id, com_option) {
  if (com_option.length > 0) {
    document.getElementById("regExp_value" + i).value = com_option;
    document.getElementById(regVal_id).value = com_option;
    document.getElementById(i + "_regExp_commonform_id_temp").value = document.getElementById("common_RegExp" + i).selectedIndex;
  }
  else {
    document.getElementById(regVal_id).value = regValue;
    document.getElementById(i + "_regExp_commonform_id_temp").value = regValue;
  }
}

function create_common_regexp(i, w_regExp_status, w_regExp_common) {
  var label = jQuery('<label class="fm-field-label regExp_cell">Common Regular Expressions</label>');
  var input = jQuery('<select class="fm-width-100" id="common_RegExp' + i + '" name="common_RegExp' + i + '" onChange="change_regExpValue(' + i + ', \' + w_regExp_value + \', \'' + i + '_regExp_valueform_id_temp\', this.value)"></select>');

  var index = 0;
  var common_val_arr = [];
  common_val_arr["Select"] = "";
  common_val_arr["Name(Latin letters and some symbols)"] = "^[a-zA-Z'-'\\s]+$";
  common_val_arr["Phone Number(Digits and dashes)"] = "^(\\+)?[0-9]+(-[0-9]+)?(-[0-9]+)?(-[0-9]+)?$";
  common_val_arr["Integer Number"] = "^(-)?[0-9]+$";
  common_val_arr["Decimal Number"] = "^(-)?[0-9]+(\\.[0-9]+)?$";
  common_val_arr["Latin letters and Numbers"] = "^[a-z&A-Z0-9]*$";
  common_val_arr["Credit Card (16 Digits)"] = "^([0-9](\\.)?){15}[0-9]$";
  common_val_arr["Zip Code"] = "^(\\d{5}-\\d{4}|\\d{5}|\\d{9})$|^([a-zA-Z]\\d[a-zA-Z] \\d[a-zA-Z]\\d)$";
  common_val_arr["IP Address"] = "^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$";
  common_val_arr["Date m/d/y (e.g. 12/21/2013)"] = "^([0-9]|1[0,1,2])/([0-9]|[0,1,2][0-9]|3[0,1])/[0-9]{4}$";
  common_val_arr["Date d.m.y (e.g. 21.12.2013)"] = "^([0-9]|[0,1,2][0-9]|3[0,1])\\.([0-9]|1[0,1,2])\\.[0-9]{4}$";
  common_val_arr["MySQL Date Format (2013-12-21)"] = "^\\d{4}-(0[0-9]|1[0,1,2])-([0,1,2][0-9]|3[0,1])$";

  for (var keys  in common_val_arr) {
    if (!common_val_arr.hasOwnProperty(keys)) {
      continue;
    }
    var el_option = jQuery('<option id="edit_for_label_common' + index + '" value="' + common_val_arr[keys] + '"' + (w_regExp_common == index ? ' selected="selected"' : '') + '>' + keys + '</option>');
    input.append(el_option);
    index++;
  }

  return create_option_container(label, input, 'edit_main_tr13', w_regExp_status == 'yes');
}

function create_case_sensitive(i, w_regExp_status, w_regExp_arg) {
  var label = jQuery('<label class="fm-field-label" for="el_regArg_' + i + '">Case Insensitive</label>');
  var input = jQuery('<input type="checkbox" id="el_regArg_' + i + '" onchange="set_regExpArgument(\'' + i + '_regArgument\')"' + (w_regExp_arg == 'i' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input, 'edit_main_tr14', w_regExp_status == 'yes');
}

function set_regExpArgument(id) {
  if (document.getElementById(id + "form_id_temp").value.length <= 0) {
    document.getElementById(id + "form_id_temp").setAttribute("value", "i");
  }
  else {
    document.getElementById(id + "form_id_temp").setAttribute("value", "");
  }
}

function create_alert_message(i, w_regExp_status, w_regExp_alert) {
  var label = jQuery('<label class="fm-field-label regExp_cell" for="regExp_alert' + i + '">Alert Message</label>');
  var input = jQuery('<textarea id="regExp_alert' + i + '" class="regExp_cell fm-width-100" onKeyUp="change_regExpAlert(this.value, \'' + i + '_regExp_alertform_id_temp\')">' + w_regExp_alert + '</textarea>');
  return create_option_container(label, input, 'edit_main_tr15', w_regExp_status == 'yes');
}

function change_regExpAlert(regAlert, id) {
  document.getElementById(id).value = regAlert;
}

function create_additional_attributes(i, w_attr_name, type) {
  var label = jQuery('<label class="fm-field-label">Additional Attributes</label>');
  var button = jQuery('<span class="fm-add-attribute dashicons dashicons-plus-alt" onClick="add_attr(' + i + ', \'' + type + '\')" title="Add"></span>');
  var attr_table = jQuery('<div id="attributes" class="fm-width-100"></div>');
  var attr_header = jQuery('<div idi="0" class="fm-width-100"><div class="fm-header-label fm-width-45">Name</div><div class="fm-header-label fm-width-45">Value</div><div></div></div>');
  attr_table.append(attr_header);
  attr_table.hide();
  n = w_attr_name.length;
  for (j = 1; j <= n; j++) {
    var attr = jQuery('<div idi="' + j + '" id="attr_row_' + j + '" class="fm-width-100"><div class="fm-table-col fm-width-45"><input type="text" class="fm-field-choice" id="attr_name' + j + '" value="' + w_attr_name[j - 1] + '" onChange="change_attribute_name(\'' + i + '\', this, \'' + type + '\')" /></div><div class="fm-table-col fm-width-45"><input type="text" class="fm-field-choice" id="attr_value' + j + '" value="' + w_attr_value[j - 1] + '" onChange="change_attribute_value(' + i + ', ' + j + ', \'' + type + '\')" /></div><div class="fm-table-col"><span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_choices' + j + '_remove" onClick="remove_attr(' + j + ', ' + i + ', \'' + type + '\')"></span></div></div>');
    attr_table.append(attr);
    attr_table.show();
  }

  var input = label;
  input = input.add(button);
  input = input.add(attr_table);
  return create_option_container(null, input);
}

function add_attr(i, type) {
  var el_attr_table = jQuery('#attributes');
  el_attr_table.show();
  j = parseInt(el_attr_table.children().last().attr('idi')) + 1;
  w_attr_name = "attribute";
  w_attr_value = "value";
  var attr = jQuery('<div idi="' + j + '" id="attr_row_' + j + '" class="fm-width-100"><div class="fm-table-col fm-width-45"><input type="text" class="fm-field-choice" id="attr_name' + j + '" value="' + w_attr_name + '" onChange="change_attribute_name(\'' + i + '\', this, \'' + type + '\')" /></div><div class="fm-table-col fm-width-45"><input type="text" class="fm-field-choice" id="attr_value' + j + '" value="' + w_attr_value + '" onChange="change_attribute_value(' + i + ', ' + j + ', \'' + type + '\')" /></div><div class="fm-table-col"><span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_choices' + j + '_remove" onClick="remove_attr(' + j + ', ' + i + ', \'' + type + '\')"></span></div></div>');
  el_attr_table.append(attr);
  refresh_attr(i, type);

  jQuery('#edit_table').scrollTop(jQuery("#attributes").offset().top);
}

function change_attribute_name(id, x, type) {
  value = x.value;
  if (!value) {
    alert('The name of the attribute is required.');
    return;
  }
  if (value.toLowerCase() == "style") {
    alert('Sorry, you cannot add a style attribute here. Use "Class name" instead.');
    return;
  }
  if (value == parseInt(value)) {
    alert('The name of the attribute cannot be a number.');
    return;
  }
  if (value.indexOf(" ") != -1) {
    var regExp = /\s+/g;
    value = value.replace(regExp, '');
    x.value = value;
    alert("The name of the attribute cannot contain a space.");
    refresh_attr(id, type);
    return;
  }
  refresh_attr(id, type);
}

function change_attribute_value(id, x, type) {
  if (!document.getElementById("attr_name" + x).value) {
    alert('The name of the attribute is required.');
    return
  }
  if (document.getElementById("attr_name" + x).value.toLowerCase() == "style") {
    alert('Sorry, you cannot add a style attribute here. Use "Class name" instead.');
    return
  }
  refresh_attr(id, type);
}

function remove_attr(id, el_id,type) {
  tr = jQuery("#attr_row_" + id);
  table = jQuery("#attributes");
  tr.remove();
  if (table.children().length == 1) {
    table.hide();
  }
  refresh_attr(el_id, type);
}

function refresh_attr(x, type) {
  switch (type) {
    case "type_text":
    case "type_paypal_price_new":
    case "type_star_rating":
    case "type_scale_rating":
    case "type_spinner":
    case "type_slider":
    case "type_grading":
    case "type_matrix": {
      id_array = Array();
      id_array[0] = x + '_elementform_id_temp';
      break;
    }
    case "type_paypal_price": {
      id_array = Array();
      id_array[0] = x + '_element_dollarsform_id_temp';
      id_array[1] = x + '_element_centsform_id_temp';
      break;
    }
    case "type_range": {
      id_array = Array();
      id_array[0] = x + '_elementform_id_temp0';
      id_array[1] = x + '_elementform_id_temp1';
      break;
    }
    case "type_name": {
      id_array = Array();
      id_array[0] = x + '_element_firstform_id_temp';
      id_array[1] = x + '_element_lastform_id_temp';
      id_array[2] = x + '_element_titleform_id_temp';
      id_array[3] = x + '_element_middleform_id_temp';
      break;
    }
    case "type_address": {
      id_array = Array();
      id_array[0] = x + '_street1form_id_temp';
      id_array[1] = x + '_street2form_id_temp';
      id_array[2] = x + '_cityform_id_temp';
      id_array[3] = x + '_stateform_id_temp';
      id_array[4] = x + '_postalform_id_temp';
      id_array[5] = x + '_countryform_id_temp';
      break;
    }
    case "type_checkbox":
    case "type_radio": {
      id_array = Array();
      for (z = 0; z < 50; z++) {
        id_array[z] = x + '_elementform_id_temp' + z;
      }
      break;
    }
    case "type_time": {
      id_array = Array();
      id_array[0] = x + '_hhform_id_temp';
      id_array[1] = x + '_mmform_id_temp';
      id_array[2] = x + '_ssform_id_temp';
      id_array[3] = x + '_am_pmform_id_temp';
      break;
    }
    case "type_date": {
      id_array = Array();
      id_array[0] = x + '_elementform_id_temp';
      id_array[1] = x + '_buttonform_id_temp';
      break;
    }
    case "type_date_fields": {
      id_array = Array();
      id_array[0] = x + '_dayform_id_temp';
      id_array[1] = x + '_monthform_id_temp';
      id_array[2] = x + '_yearform_id_temp';
      break;
    }
    case "type_captcha": {
      id_array = Array();
      id_array[0] = '_wd_captchaform_id_temp';
      id_array[1] = '_wd_captcha_inputform_id_temp';
      id_array[2] = '_element_refreshform_id_temp';
      break;
    }
    case "type_arithmetic_captcha": {
      id_array = Array();
      id_array[0] = '_wd_arithmetic_captchaform_id_temp';
      id_array[1] = '_wd_arithmetic_captcha_inputform_id_temp';
      id_array[2] = '_element_refreshform_id_temp';
      break;
    }
    case "type_recaptcha": {
      id_array = Array();
      id_array[0] = 'wd_recaptchaform_id_temp';
      break;
    }
    case "type_submit_reset": {
      id_array = Array();
      id_array[0] = x + '_element_submitform_id_temp';
      id_array[1] = x + '_element_resetform_id_temp';
      break;
    }
    case "type_page_break": {
      id_array = Array();
      id_array[0] = '_div_between';
      break;
    }
  }

  for (q = 0; q < id_array.length; q++) {
    id = id_array[q];
    var input = document.getElementById(id);
    if (input) {
      atr = input.attributes;
      for (i = 0; i < 30; i++) {
        if (atr[i]) {
          if (atr[i].name.indexOf("add_") == 0) {
            input.removeAttribute(atr[i].name);
            i--;
          }
        }
      }
      for (i = 0; i < 10; i++) {
        if (document.getElementById("attr_name" + i)) {
          try {
            input.setAttribute("add_" + document.getElementById("attr_name" + i).value, document.getElementById("attr_value" + i).value)
          }
          catch (err) {
            alert('Only letters, numbers, hyphens and underscores are allowed.');
          }
        }
      }
    }
  }
}

function return_attributes(id) {
  attr_names = new Array();
  attr_values = new Array();
  var input = document.getElementById(id);
  if (input) {
    atr = input.attributes;
    for (i = 0; i < 30; i++) {
      if (atr[i]) {
        if (atr[i].name.indexOf("add_") == 0) {
          attr_names.push(atr[i].name.replace('add_', ''));
          attr_values.push(atr[i].value);
        }
      }
    }
  }
  return Array(attr_names, attr_values);
}

function go_to_type_text(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_text(new_id, 'Text', '', 'top', 'no', '', '', '', 'no', 'no', '', '', '', 'Incorrect Value', 'no', w_attr_name, w_attr_value, 'no', '');
}

function delete_last_child() {
  if (document.getElementById("form_maker_editor_ifr")) {
    ifr_id = "form_maker_editor_ifr";
    ifr = getIFrameDocument(ifr_id);
    ifr.body.innerHTML = "";
  }
  document.getElementById('main_editor').style.display = "none";
  jQuery('#form_maker_editor').val('');
  jQuery('#show_table').empty();
  jQuery('#edit_table').empty();
}

function type_text(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_required, w_regExp_status, w_regExp_value, w_regExp_common, w_regExp_arg, w_regExp_alert, w_unique, w_attr_name, w_attr_value, w_readonly, w_class) {
  jQuery("#element_type").val("type_text");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_text'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_placeholder(i, w_title));
  edit_main_table.append(create_field_size(i, w_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_readonly(i, w_readonly));
  advanced_options_container.append(create_unique_values(i, w_unique));
  advanced_options_container.append(create_regexp(i, w_regExp_status));
  advanced_options_container.append(create_common_regexp(i, w_regExp_status, w_regExp_common));
  advanced_options_container.append(create_custom_regexp(i, w_regExp_status, w_regExp_value));
  advanced_options_container.append(create_case_sensitive(i, w_regExp_status, w_regExp_arg));
  advanced_options_container.append(create_alert_message(i, w_regExp_status, w_regExp_alert));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_text'));

  // Preview
  element = 'input';
  cur_type = 'text';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_text");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_readonly = document.createElement("input");
  adding_readonly.setAttribute("type", "hidden");
  adding_readonly.setAttribute("value", w_readonly);
  adding_readonly.setAttribute("name", i + "_readonlyform_id_temp");
  adding_readonly.setAttribute("id", i + "_readonlyform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", cur_type);

  if (w_title == w_first_val) {
    adding.style.cssText = "width:" + w_size + "px;";
  }
  else {
    adding.style.cssText = "width:" + w_size + "px;";
  }
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_elementform_id_temp");
  adding.setAttribute("value", w_first_val);
  adding.setAttribute("title", w_title);
  adding.setAttribute("placeholder", w_title);
  if (w_readonly == 'yes')
    adding.setAttribute("readonly", "readonly");

  var adding_regExp_status = document.createElement("input");
  adding_regExp_status.setAttribute("type", "hidden");
  adding_regExp_status.setAttribute("value", w_regExp_status);
  adding_regExp_status.setAttribute("name", i + "_regExpStatusform_id_temp");
  adding_regExp_status.setAttribute("id", i + "_regExpStatusform_id_temp");

  var adding_regArg = document.createElement("input");
  adding_regArg.setAttribute("type", "hidden");
  adding_regArg.setAttribute("value", w_regExp_arg);
  adding_regArg.setAttribute("name", i + "_regArgumentform_id_temp");
  adding_regArg.setAttribute("id", i + "_regArgumentform_id_temp");

  var adding_regExp_common = document.createElement("input");
  adding_regExp_common.setAttribute("type", "hidden");
  adding_regExp_common.setAttribute("value", w_regExp_common);
  adding_regExp_common.setAttribute("name", i + "_regExp_commonform_id_temp");
  adding_regExp_common.setAttribute("id", i + "_regExp_commonform_id_temp");

  var adding_regExp_value = document.createElement("input");
  adding_regExp_value.setAttribute("type", "hidden");
  adding_regExp_value.setAttribute("value", escape(w_regExp_value));
  adding_regExp_value.setAttribute("name", i + "_regExp_valueform_id_temp");
  adding_regExp_value.setAttribute("id", i + "_regExp_valueform_id_temp");

  var adding_regExp_alert = document.createElement("input");
  adding_regExp_alert.setAttribute("type", "hidden");
  adding_regExp_alert.setAttribute("value", w_regExp_alert);
  adding_regExp_alert.setAttribute("name", i + "_regExp_alertform_id_temp");
  adding_regExp_alert.setAttribute("id", i + "_regExp_alertform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.cssText = 'display:' + display_label_div;
  div_label.style.width = w_field_label_size + 'px';
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_readonly);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_regExp_status);
  div_element.appendChild(adding_regExp_value);
  div_element.appendChild(adding_regExp_common);
  div_element.appendChild(adding_regExp_alert);
  div_element.appendChild(adding_regArg);
  div_element.appendChild(adding_unique);
  div_element.appendChild(adding);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br);
  main_td.appendChild(div);

  jQuery("#main_div").append( form_maker.type_text_description );

  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function create_upload_max_size(i, w_max_size) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_max_size">Maximum size(KB)</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_max_size" onKeyPress="return check_isnum(event)" onChange="change_file_value(this.value,\'' + i + '_max_size\', \'***max_sizeskizb' + i + '***\', \'***max_sizeverj' + i + '***\')" value="' + w_max_size + '" /><p class="description">' + form_maker.upload_max_size + '</p>');
  return create_option_container(label, input);
}

function change_file_value(destination, id, prefix , postfix ) {
  if (typeof(prefix) == 'undefined') {
    prefix = '';
    postfix = ''
  }
  input = document.getElementById(id);
  input.value = prefix + destination + postfix;
  input.setAttribute("value", prefix + destination + postfix);
}

function create_upload_destination(i, w_destination) {
  var label = jQuery('<label class="fm-field-label" for="el_destination_input">Destination</label>');
  var input = jQuery('<b id="el_destination_input_info">'+ upload_url.replace(fm_site_url, '') +'/</b><input type="text" class="fm-width-100" id="el_destination_input" onChange="change_file_value(this.value,\'' + i + '_destination\', \'***destinationskizb' + i + '***\', \'***destinationverj' + i + '***\')" value="' + w_destination.replace(upload_url, '') + '" />');
  return create_option_container(label, input);
}

function create_upload_extensions(i, w_extension) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_extension">Allowed file extensions</label>');
  var input = jQuery('<textarea class="fm-width-100" id="edit_for_extension" rows="4" onChange="change_file_value(this.value,\'' + i + '_extension\', \'***extensionskizb' + i + '***\', \'***extensionverj' + i + '***\')">' + w_extension + '</textarea>');
  return create_option_container(label, input);
}

function create_class(i, w_class) {
  var label = jQuery('<label class="fm-field-label" for="el_style_textarea">Class name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_style_textarea" onChange="change_class(this.value,' + i + ')" value="' + w_class + '" />');
  return create_option_container(label, input);
}

function change_class(x,id) {
  if (document.getElementById(id + '_label_sectionform_id_temp')) {
    document.getElementById(id + '_label_sectionform_id_temp').setAttribute("class", x);
  }
  if (document.getElementById(id + '_element_sectionform_id_temp')) {
    document.getElementById(id + '_element_sectionform_id_temp').setAttribute("class", x);
  }
}

function create_multiple_upload(i, w_multiple) {
  var label = jQuery('<label class="fm-field-label" for="el_multiple">Allow Uploading Multiple Files</label>');
  var input = jQuery('<input type="checkbox" id="el_multiple" onchange="set_multiple(' + i + ', this.checked)"' + (w_multiple == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_multiple(i, status) {
  if (status) {
    document.getElementById(i + "_elementform_id_temp").setAttribute('multiple', 'multiple');
  }
  else {
    document.getElementById(i + "_elementform_id_temp").removeAttribute('multiple');
  }
}

function type_file_upload(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_destination, w_extension, w_max_size, w_required, w_multiple, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_file_upload");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_file_upload'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_upload_extensions(i, w_extension));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_upload_max_size(i, w_max_size));
  advanced_options_container.append(create_upload_destination(i, w_destination));

  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_multiple_upload(i, w_multiple));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_file_upload'));

  // Preview
  element = 'input';
  type = 'file';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_file_upload");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", type);
  adding.setAttribute("class", "file_upload");
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_fileform_id_temp");
  if (w_multiple == "yes")
    adding.setAttribute("multiple", "multiple");

  var adding_max_size = document.createElement("input");
  adding_max_size.setAttribute("type", "hidden");
  adding_max_size.setAttribute("value", '***max_sizeskizb' + i + '***' + w_max_size + '***max_sizeverj' + i + '***');
  adding_max_size.setAttribute("id", i + "_max_size");
  adding_max_size.setAttribute("name", i + "_max_size");

  var adding_destination = document.createElement("input");
  adding_destination.setAttribute("type", "hidden");
  adding_destination.setAttribute("value", '***destinationskizb' + i + '***' + w_destination + '***destinationverj' + i + '***');
  adding_destination.setAttribute("id", i + "_destination");
  adding_destination.setAttribute("name", i + "_destination");
  var adding_extension = document.createElement("input");
  adding_extension.setAttribute("type", "hidden");
  adding_extension.setAttribute("value", '***extensionskizb' + i + '***' + w_extension + '***extensionverj' + i + '***');
  adding_extension.setAttribute("id", i + "_extension");
  adding_extension.setAttribute("name", i + "_extension");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);

  div_element.appendChild(adding_max_size);
  div_element.appendChild(adding_destination);
  div_element.appendChild(adding_extension);
  div_element.appendChild(adding);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_file_upload_description );
  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function go_to_type_stripe(new_id) {
  if (document.getElementById('is_stripe')) return false;
  type_stripe(new_id, 'Stripe', '', 'top', 'yes', '', '');
}

function type_stripe(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_class ) {
  jQuery("#element_type").val("type_stripe");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_stripe'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position_stripe(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_field_size(i, w_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));

//show table

  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_stripe");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.cssText = 'display:' + display_label_div;
  div_label.style.width = w_field_label_size + 'px';
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);

  div_element.innerHTML = "<div id='" + i + "_elementform_id_temp' style='width:" + w_size + "px; margin:10px; border: 1px solid #000; min-width:80px;text-align:center;'> Stripe Section</div><input type='hidden' id='is_stripe' />";
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_hide_label);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_stripe_description );

  if (w_field_label_pos == "top") {
    label_top_stripe(i);
  }
  else {
    label_left_stripe();
  }

  change_class(w_class, i);
}

function create_field_size_2(i, w_size_w, w_size_h) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Size(px)</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_elementform_id_temp\', this.value)" value="' + w_size_w + '" />x<input type="text" class="fm-width-40" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_h_style(\'' + i + '_elementform_id_temp\', this.value)" value="' + w_size_h + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function go_to_type_textarea(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_textarea(new_id, 'Textarea', '', 'top', 'no', '', '100', '', '', '', 'no', 'no', '', w_attr_name, w_attr_value)
}

function type_textarea(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size_w, w_size_h, w_first_val, w_characters_limit, w_title, w_required, w_unique, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_textarea");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_textarea'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_placeholder(i, w_title));
  edit_main_table.append(create_field_size_2(i, w_size_w, w_size_h));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_unique_values(i, w_unique));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_characters_limit(i, w_characters_limit, 'type_textarea'));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_textarea'));

  // Preview
  element = 'textarea';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_textarea");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_characters_limit = document.createElement("input");
  adding_characters_limit.setAttribute("type", "hidden");
  adding_characters_limit.setAttribute("value", w_characters_limit);
  adding_characters_limit.setAttribute("name", i + "_charlimitform_id_temp");
  adding_characters_limit.setAttribute("id", i + "_charlimitform_id_temp");

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.style.verticalAlign = "top";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  var adding = document.createElement(element);
  if (w_title == w_first_val) {
    adding.style.cssText = "width:" + w_size_w + "px; height:" + w_size_h + "px;";
  }
  else {
    adding.style.cssText = "width:" + w_size_w + "px; height:" + w_size_h + "px;";
  }

  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_elementform_id_temp");
  adding.setAttribute("title", w_title);
  adding.setAttribute("placeholder", w_title);
  adding.setAttribute("value", w_first_val);
  adding.setAttribute("maxlength", w_characters_limit);
  adding.innerHTML = w_first_val;

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_characters_limit);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_unique);
  div_element.appendChild(adding);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  main_td.appendChild(div);

  jQuery("#main_div").append( '<br>'+form_maker.type_textarea_description );

  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function create_spinner_width(i, w_field_width) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_spinner_width">Width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_spinner_width" onKeyPress="return check_isnum(event)" onKeyUp="change_spinner_width(this.value,' + i + ',\'form_id_temp\')" value="' + w_field_width + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function change_spinner_width(a, id, form_id) {
  document.getElementById(id + "_elementform_id_temp").style.cssText = "width:" + a + "px";
  document.getElementById(id + "_spinner_widthform_id_temp").value = a;
}

function create_spinner_step(i, w_field_step) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_spinner_step">Step</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_spinner_step" onKeyPress="return check_isnum(event)" onKeyUp="change_spinner_step(this.value,' + i + ',\'form_id_temp\')" value="' + w_field_step + '" />');
  return create_option_container(label, input);
}

function change_spinner_step(a, id, form_id) {
  jQuery("#" + id + "_elementform_id_temp").spinner({step: a});
  document.getElementById(id + "_stepform_id_temp").value = a;
}

function create_spinner_minvalue(i, w_field_min_value) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_spinner_min_value">Min Value</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_spinner_min_value" onKeyPress="return check_isnum_or_minus(event)" onKeyUp="change_spinner_min_value(this.value,' + i + ',\'form_id_temp\')" value="' + w_field_min_value + '" />');
  return create_option_container(label, input);
}

function change_spinner_min_value(a, id, form_id) {
  jQuery("#" + id + "_elementform_id_temp").spinner({min: a});
  document.getElementById(id + "_min_valueform_id_temp").value = a;
}

function create_spinner_maxvalue(i, w_field_max_value) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_spinner_max_value">Max Value</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_spinner_max_value" onKeyPress="return check_isnum_or_minus(event)" onKeyUp="change_spinner_max_value(this.value,' + i + ',\'form_id_temp\')" value="' + w_field_max_value + '" />');
  return create_option_container(label, input);
}

function change_spinner_max_value(a, id, form_id) {
  jQuery("#" + id + "_elementform_id_temp").spinner({max: a});
  document.getElementById(id + "_max_valueform_id_temp").value = a;
}

function go_to_type_spinner(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_spinner(new_id, 'Number', '', 'top', 'no', '60', '', '', '1', '', 'no', '', w_attr_name, w_attr_value);
}

function type_spinner(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_width, w_field_min_value, w_field_max_value, w_field_step, w_field_value, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_spinner");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_spinner'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_spinner_step(i, w_field_step));
  edit_main_table.append(create_spinner_minvalue(i, w_field_min_value));
  edit_main_table.append(create_spinner_maxvalue(i, w_field_max_value));
  edit_main_table.append(create_spinner_width(i, w_field_width));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_spinner'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_spinner");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_width = document.createElement("input");
  adding_width.setAttribute("type", "hidden");
  adding_width.setAttribute("value", w_field_width);
  adding_width.setAttribute("name", i + "_spinner_widthform_id_temp");
  adding_width.setAttribute("id", i + "_spinner_widthform_id_temp");

  var adding_min_value = document.createElement("input");
  adding_min_value.setAttribute("type", "hidden");
  adding_min_value.setAttribute("value", w_field_min_value);
  adding_min_value.setAttribute("id", i + "_min_valueform_id_temp");
  adding_min_value.setAttribute("name", i + "_min_valueform_id_temp");

  var adding_max_value = document.createElement("input");
  adding_max_value.setAttribute("type", "hidden");
  adding_max_value.setAttribute("value", w_field_max_value);
  adding_max_value.setAttribute("name", i + "_max_valueform_id_temp");
  adding_max_value.setAttribute("id", i + "_max_valueform_id_temp");

  var adding_step = document.createElement("input");
  adding_step.setAttribute("type", "hidden");
  adding_step.setAttribute("value", w_field_step);
  adding_step.setAttribute("name", i + "_stepform_id_temp");
  adding_step.setAttribute("id", i + "_stepform_id_temp");

  var adding_spinner_input = document.createElement("input");
  adding_spinner_input.setAttribute("type", "");
  adding_spinner_input.style.cssText = "width:" + w_field_width + "px";
  adding_spinner_input.setAttribute("name", i + "_elementform_id_temp");
  adding_spinner_input.setAttribute("id", i + "_elementform_id_temp");
  adding_spinner_input.setAttribute("value", w_field_value);
  adding_spinner_input.setAttribute("onClick", "check_isnum_or_minus(event)");
  adding_spinner_input.setAttribute("onKeyPress", "return check_isnum_or_minus(event)");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell")
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_width);
  div_element.appendChild(adding_min_value);
  div_element.appendChild(adding_max_value);
  div_element.appendChild(adding_step);
  div_element.appendChild(adding_spinner_input);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br1);
  main_td.appendChild(div);
  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_spinner');

  jQuery("#" + i + "_elementform_id_temp").spinner();
  var spinner = jQuery("#" + i + "_elementform_id_temp").spinner();
  if ( w_field_value == null ) {
    spinner.spinner("value", "");
  }

  jQuery("#" + i + "_elementform_id_temp").spinner({min: w_field_min_value});
  jQuery("#" + i + "_elementform_id_temp").spinner({max: w_field_max_value});
  jQuery("#" + i + "_elementform_id_temp").spinner({step: w_field_step});

  jQuery("#main_div").append( form_maker.type_number_description );
}

function create_date_format(i, w_format) {
  var label = jQuery('<label class="fm-field-label" for="date_format">Date Format</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="date_format" onChange="change_date_format(this.value, ' + i + ', \'format\')" value="' + w_format + '" />');
  return create_option_container(label, input);
}

function change_date_format(value, id, element) {
  var input_p = document.getElementById(id + '_buttonform_id_temp');
  var default_date_start = document.getElementById('default_date_start');
  var default_date_end = document.getElementById('default_date_end');
  var min_date = document.getElementById('min_date');
  var max_date = document.getElementById('max_date');

  if ( element == 'format' ) {
    var dis_past_days = document.getElementById(id + '_dis_past_daysform_id_temp').value == 'yes' ? true : false;
    input_p.setAttribute("format", value);
    min_date.setAttribute("placeholder", value);
    max_date.setAttribute("placeholder", value);
    if ( default_date_start || default_date_start ) {
      default_date_start.setAttribute("placeholder", value);
      default_date_end.setAttribute("placeholder", value);
    }
  }
  else {
    document.getElementById(id + '_dis_past_daysform_id_temp').value = (value == true ? 'yes' : 'no');
    var dis_past_days = value == true ? true : false;
    var value = document.getElementById('date_format').value;
  }
  jQuery("[name^=" + id + "_elementform_id_temp]").datepicker('option', 'dateFormat', value);
}

function create_week_start(i, w_start_day) {
  var label = jQuery('<label class="fm-field-label">First Day of the Week</label>');
  var input = jQuery('<select class="fm-width-100" id="start_day" name="start_day" onChange="change_start_day(this.value,' + i + ')"></select>');

  var index = 0;
  var week_days = [];
  week_days[0] = form_maker.sunday;
  week_days[1] = form_maker.monday;
  week_days[2] = form_maker.tuesday;
  week_days[3] = form_maker.wednesday;
  week_days[4] = form_maker.thursday;
  week_days[5] = form_maker.friday;
  week_days[6] = form_maker.saturday;

  for (var keys  in week_days) {
    if (!week_days.hasOwnProperty(keys)) {
      continue;
    }
    var el_option = jQuery('<option value="' + keys + '"' + (w_start_day == index ? ' selected="selected"' : '') + '>' + week_days[keys] + '</option>');
    input.append(el_option);
    index++;
  }

  return create_option_container(label, input);
}

function change_start_day(day_number, id) {
  document.getElementById(id + '_start_dayform_id_temp').value = day_number;
  jQuery("input[name^=" + id + "_elementform_id_temp]").datepicker('option', 'firstDay', day_number);
}

function create_default_date(i, w_default_date) {
  var w_format = jQuery("#date_format").val();
  var label = jQuery('<label class="fm-field-label" for="default_date">Default Date</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="default_date" placeholder="' + w_format + '" onChange="change_hidden_input_value(this.value, \'default_date\', ' + i + ', \'' + i + '_default_date_id_temp\')" value="' + w_default_date + '" />');
  return create_option_container(label, input);
}

function change_hidden_input_value(element_value, date_fields, id_int, id) {
  document.getElementById(id).value = element_value;
  var date_format = jQuery("#" + id_int + "_buttonform_id_temp").attr('format');
  if ( date_fields == "default_date" ) {
    if ( element_value == "today" ) {
      jQuery("#" + id_int + "_elementform_id_temp").datepicker("setDate", new Date());
    }
    else if ( element_value.indexOf("d") == -1 && element_value.indexOf("m") == -1 && element_value.indexOf("y") == -1 && element_value.indexOf("w") == -1 ) {
      if ( element_value !== "") {
        element_value = jQuery.datepicker.formatDate(date_format, new Date(element_value));
      }
      jQuery("#" + id_int + "_elementform_id_temp").datepicker("setDate", element_value);
    }
    else {
      jQuery("#" + id_int + "_elementform_id_temp").datepicker("setDate", element_value);
    }
  }
  else if ( date_fields == "minDate" || date_fields == "maxDate" ) {
    if ( element_value == "today" ) {
      jQuery("#" + id_int + "_elementform_id_temp").datepicker('option', date_fields, new Date());
    }
    else if ( element_value.indexOf("d") == -1 && element_value.indexOf("m") == -1 && element_value.indexOf("y") == -1 && element_value.indexOf("w") == -1 ) {
      if ( element_value !== "" ) {
        element_value = jQuery.datepicker.formatDate(date_format, new Date(element_value));
      }
      jQuery("#" + id_int + "_elementform_id_temp").datepicker('option', date_fields, element_value);
    }
    else {
      jQuery("#" + id_int + "_elementform_id_temp").datepicker('option', date_fields, element_value);
    }
  }
  else {
    jQuery("#" + id_int + "_elementform_id_temp").datepicker("option", "beforeShowDay", function (date) {
      var invalid_dates = element_value;
      var invalid_dates_finish = [];
      var invalid_dates_start = invalid_dates.split(",");
      var invalid_date_range = [];
      for ( var i = 0; i < invalid_dates_start.length; i++ ) {
        invalid_dates_start[i] = invalid_dates_start[i].trim();
        if ( invalid_dates_start[i].length < 11 ) {
          invalid_dates_finish.push(invalid_dates_start[i]);
        }
        else {
          if ( invalid_dates_start[i].indexOf("-") > 4 )
            invalid_date_range.push(invalid_dates_start[i].split("-"));
          else {
            var invalid_date_array = invalid_dates_start[i].split("-");
            var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
            var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
            invalid_date_range.push([start_invalid_day, end_invalid_day]);
          }
        }
      }
      jQuery.each(invalid_date_range, function (index, value) {
        for (var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
          invalid_dates_finish.push(jQuery.datepicker.formatDate(date_format, d));
        }
      });
      var w_hide_sunday = jQuery("#" + id_int + "_show_week_days").attr('sunday') == 'yes' ? 'true' : 'day != 0';
      var w_hide_monday = jQuery("#" + id_int + "_show_week_days").attr('monday') == 'yes' ? 'true' : 'day != 1';
      var w_hide_tuesday = jQuery("#" + id_int + "_show_week_days").attr('tuesday') == 'yes' ? 'true' : 'day != 2';
      var w_hide_wednesday = jQuery("#" + id_int + "_show_week_days").attr('wednesday') == 'yes' ? 'true' : 'day != 3';
      var w_hide_thursday = jQuery("#" + id_int + "_show_week_days").attr('thursday') == 'yes' ? 'true' : 'day != 4';
      var w_hide_friday = jQuery("#" + id_int + "_show_week_days").attr('friday') == 'yes' ? 'true' : 'day != 5';
      var w_hide_saturday = jQuery("#" + id_int + "_show_week_days").attr('saturday') == 'yes' ? 'true' : 'day != 6';

      var day = date.getDay();
      var string_days = jQuery.datepicker.formatDate(date_format, date);
      return [invalid_dates_finish.indexOf(string_days) == -1 && eval(w_hide_sunday) && eval(w_hide_monday) && eval(w_hide_tuesday) && eval(w_hide_wednesday) && eval(w_hide_thursday) && eval(w_hide_friday) && eval(w_hide_saturday)];
    });
  }
}

function create_minimum_date(i, w_min_date, range) {
  var w_format = jQuery("#date_format").val();
  var label = jQuery('<label class="fm-field-label" for="min_date">Minimum Date</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="min_date" placeholder="' + w_format + '" onChange="' + (range == true ? 'change_hidden_input_value_range(this.value, \'minDate\', \'start\', \'' + i + '\', \'' + i + '_min_date_id_temp\')' : 'change_hidden_input_value(this.value, \'minDate\', ' + i + ', \'' + i + '_min_date_id_temp\')') + '" value="' + w_min_date + '" />');
  return create_option_container(label, input);
}

function create_maximum_date(i, w_max_date, range) {
  var w_format = jQuery("#date_format").val();
  var label = jQuery('<label class="fm-field-label" for="max_date">Maximum Date</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="max_date" placeholder="' + w_format + '" onChange="' + (range == true ? 'change_hidden_input_value_range(this.value, \'maxDate\', \'end\', ' + i + ', \'' + i + '_max_date_id_temp\')' : 'change_hidden_input_value(this.value, \'maxDate\', ' + i + ', \'' + i + '_max_date_id_temp\')') + '" value="' + w_max_date + '" />');
  return create_option_container(label, input);
}

function create_excluded_dates(i, w_invalid_dates, range) {
  var label = jQuery('<label class="fm-field-label" for="invalid_dates">Dates to Exclude</label>');
  var input = jQuery('<textarea class="fm-width-100" id="invalid_dates" rows="4" onChange="' + (range == true ? 'change_hidden_input_value_range(this.value, \'invalide_date\', \'\', ' + i + ', \'' + i + '_invalid_dates_id_temp\')' : 'change_hidden_input_value(this.value, \'invalide_date\', ' + i + ', \'' + i + '_invalid_dates_id_temp\')') + '">' + w_invalid_dates + '</textarea>');
  return create_option_container(label, input);
}

function create_show_date_picker_button(i, w_show_image, type) {
  var label = jQuery('<label class="fm-field-label" for="el_show_image">Show Date Picker Button</label>');
  var input = jQuery('<input type="checkbox" id="el_show_image" onchange="show_image_datepicker(\'' + i + '_show_image\', \'' + type + '\')"' + (w_show_image == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function show_image_datepicker(id, type) {
  if (document.getElementById(id + "form_id_temp").value == "yes") {
    if (type == "date_range") {
      jQuery("#" + id + "dateform_id_temp0").removeClass("wd-inline-block");
      jQuery("#" + id + "dateform_id_temp0").addClass("wd-hidden");
      jQuery("#" + id + "dateform_id_temp1").removeClass("wd-inline-block");
      jQuery("#" + id + "dateform_id_temp1").addClass("wd-hidden");
    }
    else {
      jQuery("#" + id + "dateform_id_temp").removeClass("wd-inline-block");
      jQuery("#" + id + "dateform_id_temp").addClass("wd-hidden");
    }
    document.getElementById(id + "form_id_temp").setAttribute("value", "no");
  }
  else {
    if (type == "date_range") {
      jQuery("#" + id + "dateform_id_temp0").removeClass("wd-hidden");
      jQuery("#" + id + "dateform_id_temp0").addClass("wd-inline-block");
      jQuery("#" + id + "dateform_id_temp1").removeClass("wd-hidden");
      jQuery("#" + id + "dateform_id_temp1").addClass("wd-inline-block");
    }
    else {
      jQuery("#" + id + "dateform_id_temp").removeClass("wd-hidden");
      jQuery("#" + id + "dateform_id_temp").addClass("wd-inline-block");
    }
    document.getElementById(id + "form_id_temp").setAttribute("value", "yes");
  }
}

function create_selectable_week_days(i, w_show_days) {
  var label = jQuery('<label class="fm-field-label">Selectable Week Days</label>');
  var input1 = jQuery('<input type="checkbox" id="el_show_sunday" onclick="show_week_days(' + i + ', \'sunday\')"' + (w_show_days[0] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_show_sunday">' + form_maker.sunday + '</label>');
  var input2 = jQuery('<input type="checkbox" id="el_show_monday" onclick="show_week_days(' + i + ', \'monday\')"' + (w_show_days[1] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_show_monday">' + form_maker.monday + '</label>');
  var input3 = jQuery('<input type="checkbox" id="el_show_tuesday" onclick="show_week_days(' + i + ', \'tuesday\')"' + (w_show_days[2] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label3 = jQuery('<label for="el_show_tuesday">' + form_maker.tuesday + '</label>');
  var input4 = jQuery('<input type="checkbox" id="el_show_wednesday" onclick="show_week_days(' + i + ', \'wednesday\')"' + (w_show_days[3] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label4 = jQuery('<label for="el_show_wednesday">' + form_maker.wednesday + '</label>');
  var input5 = jQuery('<input type="checkbox" id="el_show_thursday" onclick="show_week_days(' + i + ', \'thursday\')"' + (w_show_days[4] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label5 = jQuery('<label for="el_show_thursday">' + form_maker.thursday + '</label>');
  var input6 = jQuery('<input type="checkbox" id="el_show_friday" onclick="show_week_days(' + i + ', \'friday\')"' + (w_show_days[5] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label6 = jQuery('<label for="el_show_friday">' + form_maker.friday + '</label>');
  var input7 = jQuery('<input type="checkbox" id="el_show_saturday" onclick="show_week_days(' + i + ', \'saturday\')"' + (w_show_days[6] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label7 = jQuery('<label for="el_show_saturday">' + form_maker.saturday + '</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(jQuery('<br />'));
  input = input.add(input2);
  input = input.add(label2);
  input = input.add(jQuery('<br />'));
  input = input.add(input3);
  input = input.add(label3);
  input = input.add(jQuery('<br />'));
  input = input.add(input4);
  input = input.add(label4);
  input = input.add(jQuery('<br />'));
  input = input.add(input5);
  input = input.add(label5);
  input = input.add(jQuery('<br />'));
  input = input.add(input6);
  input = input.add(label6);
  input = input.add(jQuery('<br />'));
  input = input.add(input7);
  input = input.add(label7);
  return create_option_container(label, input);
}

function go_to_type_date_new(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_show_days = ['yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'];
  type_date_new(new_id, 'Date', '', 'top', 'no', '', '', 'no', 'no', '', 'mm/dd/yy', '0', '', '', '', '', w_show_days, 'yes', '...', w_attr_name, w_attr_value, 'no');
}

function type_date_new(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_date, w_required, w_show_image, w_class, w_format, w_start_day, w_default_date, w_min_date, w_max_date,  w_invalid_dates, w_show_days, w_hide_time,  w_but_val, w_attr_name, w_attr_value,w_disable_past_days) {
  jQuery("#element_type").val("type_date_new");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_date_new'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size));
  edit_main_table.append(create_date_format(i, w_format));
  edit_main_table.append(create_week_start(i, w_start_day));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_default_date(i, w_default_date));
  advanced_options_container.append(create_minimum_date(i, w_min_date));
  advanced_options_container.append(create_maximum_date(i, w_max_date));
  advanced_options_container.append(create_excluded_dates(i, w_invalid_dates));
  advanced_options_container.append(create_selectable_week_days(i, w_show_days));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_show_date_picker_button(i, w_show_image, 'new_date'));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_date_new'));

  // Preview.
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_date_new");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_dis_past_days = document.createElement('input');
  adding_dis_past_days.setAttribute("type", 'hidden');
  adding_dis_past_days.setAttribute("value", w_disable_past_days);
  adding_dis_past_days.setAttribute("id", i + "_dis_past_daysform_id_temp");
  adding_dis_past_days.setAttribute("name", i + "_dis_past_daysform_id_temp");

  /* adding hidden inputs new date  */

  var adding_start_day = document.createElement("input");
  adding_start_day.setAttribute("type", "hidden");
  adding_start_day.setAttribute("value", w_start_day);
  adding_start_day.setAttribute("name", i + "_start_dayform_id_temp");
  adding_start_day.setAttribute("id", i + "_start_dayform_id_temp");

  var adding_default_date = document.createElement("input");
  adding_default_date.setAttribute("type", "hidden");
  adding_default_date.setAttribute("name", i + "_default_date_id_temp");
  adding_default_date.setAttribute("id", i + "_default_date_id_temp");
  adding_default_date.setAttribute("value", w_default_date);

  var adding_min_date = document.createElement("input");
  adding_min_date.setAttribute("type", "hidden");
  adding_min_date.setAttribute("name", i + "_min_date_id_temp");
  adding_min_date.setAttribute("id", i + "_min_date_id_temp");
  adding_min_date.setAttribute("value", w_min_date);

  var adding_max_date = document.createElement("input");
  adding_max_date.setAttribute("type", "hidden");
  adding_max_date.setAttribute("name", i + "_max_date_id_temp");
  adding_max_date.setAttribute("id", i + "_max_date_id_temp");
  adding_max_date.setAttribute("value", w_max_date);

  var adding_invalid_dates = document.createElement("input");
  adding_invalid_dates.setAttribute("type", "hidden");
  adding_invalid_dates.setAttribute("name", i + "_invalid_dates_id_temp");
  adding_invalid_dates.setAttribute("id", i + "_invalid_dates_id_temp");
  adding_invalid_dates.setAttribute("value", w_invalid_dates);

  var adding_show_days = document.createElement("input");
  adding_show_days.setAttribute("type", "hidden");
  adding_show_days.setAttribute("name", i + "_show_week_days");
  adding_show_days.setAttribute("id", i + "_show_week_days");
  adding_show_days.setAttribute("sunday", w_show_days[0]);
  adding_show_days.setAttribute("monday", w_show_days[1]);
  adding_show_days.setAttribute("tuesday", w_show_days[2]);
  adding_show_days.setAttribute("wednesday", w_show_days[3]);
  adding_show_days.setAttribute("thursday", w_show_days[4]);
  adding_show_days.setAttribute("friday", w_show_days[5]);
  adding_show_days.setAttribute("saturday", w_show_days[6]);

  var adding_show_image = document.createElement("input");
  adding_show_image.setAttribute("type", "hidden");
  adding_show_image.setAttribute("value", w_show_image);
  adding_show_image.setAttribute("name", i + "_show_imageform_id_temp");
  adding_show_image.setAttribute("id", i + "_show_imageform_id_temp");

  var adding_hide_time = document.createElement("input");
  adding_hide_time.setAttribute("type", "hidden");
  adding_hide_time.setAttribute("value", w_hide_time);
  adding_hide_time.setAttribute("name", i + "_hide_timeform_id_temp");
  adding_hide_time.setAttribute("id", i + "_hide_timeform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.style.position = "relative";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_date = document.createElement('div');
  table_date.setAttribute("id", i + "_table_date");
  table_date.style.display = "table";

  var tr_date1 = document.createElement('div');
  tr_date1.setAttribute("id", i + "_tr_date1");
  tr_date1.style.display = "table-row";

  var tr_date2 = document.createElement('div');
  tr_date2.setAttribute("id", i + "_tr_date2");
  tr_date2.style.display = "table-row";

  var td_date_input1 = document.createElement('div');
  td_date_input1.setAttribute("id", i + "_td_date_input1");
  td_date_input1.style.display = "table-cell";

  var td_date_input2 = document.createElement('div');
  td_date_input2.setAttribute("id", i + "_td_date_input2");
  td_date_input2.style.display = "table-cell";

  var td_date_input3 = document.createElement('div');
  td_date_input3.setAttribute("id", i + "_td_date_input3");
  td_date_input3.style.display = "table-cell";

  var td_date_label1 = document.createElement('div');
  td_date_label1.setAttribute("id", i + "_td_date_label1");
  td_date_label1.style.display = "table-cell";

  var td_date_label2 = document.createElement('div');
  td_date_label2.setAttribute("id", i + "_td_date_label2");
  td_date_label2.style.display = "table-cell";

  var td_date_label3 = document.createElement('div');
  td_date_label3.setAttribute("id", i + "_td_date_label3");
  td_date_label3.style.display = "table-cell";

  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var adding = document.createElement('input');
  adding.setAttribute("type", 'text');
  adding.setAttribute("value", w_date);
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_elementform_id_temp");
  adding.style.cssText = "width:" + w_size + "px;"

  var adding_image = document.createElement('span');
  adding_image.setAttribute("id", i + "_show_imagedateform_id_temp");
  adding_image.setAttribute("class", "dashicons dashicons-calendar-alt wd-calendar-button " + (w_show_image == "yes" ? "wd-inline-block" : "wd-hidden"));
  adding_image.setAttribute("onClick", "show_datepicker('" + i + "_elementform_id_temp')");

  var adding_desc_p = document.createElement('p');
  var adding_desc_b = document.createElement('b');

  var text_format_1 = document.createTextNode("The format can be combinations of the following:");
  var text_format_2 = document.createTextNode("d - day of month (no leading zero)");
  var text_format_3 = document.createTextNode("dd - day of month (two digit)");
  var text_format_4 = document.createTextNode("o - day of the year (no leading zeros)");
  var text_format_5 = document.createTextNode("oo - day of the year (three digit)");
  var text_format_6 = document.createTextNode("D - day name short");
  var text_format_7 = document.createTextNode("DD - day name long");
  var text_format_8 = document.createTextNode("m - month of year (no leading zero)");
  var text_format_9 = document.createTextNode("mm - month of year (two digit)");
  var text_format_10 = document.createTextNode("M - month name short");
  var text_format_11 = document.createTextNode("MM - month name long");
  var text_format_12 = document.createTextNode("y - year (two digit)");
  var text_format_13 = document.createTextNode("yy - year (four digit)");

  var format_br_1 = document.createElement('br');
  var format_br_2 = document.createElement('br');
  var format_br_3 = document.createElement('br');
  var format_br_4 = document.createElement('br');
  var format_br_5 = document.createElement('br');
  var format_br_6 = document.createElement('br');
  var format_br_7 = document.createElement('br');
  var format_br_8 = document.createElement('br');
  var format_br_9 = document.createElement('br');
  var format_br_10 = document.createElement('br');
  var format_br_11 = document.createElement('br');
  var format_br_12 = document.createElement('br');
  var format_br_13 = document.createElement('br');

  var adding_desc_p_2 = document.createElement('p');
  var adding_desc_b_2 = document.createElement('b');

  var text_default_1 = document.createTextNode("Accepted values of Default, Minimum and Maximum:");
  var text_default_2 = document.createTextNode("Empty: No default / minimum / maximum");
  var text_default_4 = document.createTextNode("Current date : 'today'");
  var text_default_5 = document.createTextNode("Relative date: A number of days/weeks/months/years from today, e.g. '-1d' will be yesterday, '+1y+3m+2w+3d' will be  1 year, 3 months, 2 weeks and 3 days from today.");

  var adding_desc_p_3 = document.createElement('p');
  var adding_desc_b_3 = document.createElement('b');

  var text_default_6 = document.createTextNode("Dates to exclude:");
  var text_default_7 = document.createTextNode("Enter comma-separated list of dates and date ranges using the date format 'mm/dd/yy', e.g. 08/15/2016, 06/15/2016-06/20/2016");

  var default_br_1 = document.createElement('br');
  var default_br_2 = document.createElement('br');
  var default_br_4 = document.createElement('br');
  var default_br_5 = document.createElement('br');
  var default_br_6 = document.createElement('br');
  var default_br_7 = document.createElement('br');

  var dis_past_days = w_disable_past_days == 'yes' ? true : false;

  var adding_button = document.createElement('input');
  adding_button.setAttribute("id", i + "_buttonform_id_temp");
  adding_button.setAttribute("class", "button");
  adding_button.setAttribute("type", 'hidden');
  adding_button.setAttribute("value", w_but_val);
  adding_button.setAttribute("format", w_format);

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_show_image);
  div_element.appendChild(adding_dis_past_days);
  div_element.appendChild(adding);
  div_element.appendChild(adding_image);

  adding_desc_b.appendChild(text_format_1);
  adding_desc_p.appendChild(adding_desc_b);
  adding_desc_p.appendChild(format_br_1);
  adding_desc_p.appendChild(text_format_2);
  adding_desc_p.appendChild(format_br_2);
  adding_desc_p.appendChild(text_format_3);
  adding_desc_p.appendChild(format_br_3);
  adding_desc_p.appendChild(text_format_4);
  adding_desc_p.appendChild(format_br_4);
  adding_desc_p.appendChild(text_format_5);
  adding_desc_p.appendChild(format_br_5);
  adding_desc_p.appendChild(text_format_6);
  adding_desc_p.appendChild(format_br_6);
  adding_desc_p.appendChild(text_format_7);
  adding_desc_p.appendChild(format_br_7);
  adding_desc_p.appendChild(text_format_8);
  adding_desc_p.appendChild(format_br_8);
  adding_desc_p.appendChild(text_format_9);
  adding_desc_p.appendChild(format_br_9);
  adding_desc_p.appendChild(text_format_10);
  adding_desc_p.appendChild(format_br_10);
  adding_desc_p.appendChild(text_format_11);
  adding_desc_p.appendChild(format_br_11);
  adding_desc_p.appendChild(text_format_12);
  adding_desc_p.appendChild(format_br_12);
  adding_desc_p.appendChild(text_format_13);
  adding_desc_p.appendChild(format_br_13);

  adding_desc_b_2.appendChild(text_default_1);
  adding_desc_p_2.appendChild(adding_desc_b_2);
  adding_desc_p_2.appendChild(default_br_1);
  adding_desc_p_2.appendChild(text_default_2);
  adding_desc_p_2.appendChild(default_br_2);
  adding_desc_p_2.appendChild(text_default_4);
  adding_desc_p_2.appendChild(default_br_4);
  adding_desc_p_2.appendChild(text_default_5);

  adding_desc_b_3.appendChild(text_default_6);
  adding_desc_p_3.appendChild(adding_desc_b_3);
  adding_desc_p_3.appendChild(default_br_6);
  adding_desc_p_3.appendChild(text_default_7);
  adding_desc_p_3.appendChild(default_br_7);

  /* adding hidden  inputs(new date) in div */

  div_element.appendChild(adding_default_date);
  div_element.appendChild(adding_start_day);
  div_element.appendChild(adding_min_date);
  div_element.appendChild(adding_max_date);
  div_element.appendChild(adding_invalid_dates);
  div_element.appendChild(adding_hide_time);
  div_element.appendChild(adding_show_days);
  div_element.appendChild(adding_button);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  div.appendChild(adding_desc_p);
  div.appendChild(adding_desc_p_2);
  div.appendChild(adding_desc_p_3);
  main_td.appendChild(div);

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_text');

  jQuery("#" + i + "_elementform_id_temp").datepicker({
    dateFormat: w_format,
    minDate: w_min_date,
    maxDate: w_max_date,
    firstDay: w_start_day,
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+50",
    showOtherMonths: true,
    selectOtherMonths: true,
    beforeShowDay: function (date) {
      var invalid_dates = w_invalid_dates;
      var invalid_dates_finish = [];
      var invalid_dates_start = invalid_dates.split(",");
      var invalid_date_range = [];

      for (var i = 0; i < invalid_dates_start.length; i++) {
        invalid_dates_start[i] = invalid_dates_start[i].trim();
        if (invalid_dates_start[i].length < 11) {
          invalid_dates_finish.push(invalid_dates_start[i]);
        }
        else {
          if (invalid_dates_start[i].indexOf("-") > 4)
            invalid_date_range.push(invalid_dates_start[i].split("-"));
          else {
            var invalid_date_array = invalid_dates_start[i].split("-");
            var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
            var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
            invalid_date_range.push([start_invalid_day, end_invalid_day]);
          }
        }
      }

      jQuery.each(invalid_date_range, function (index, value) {
        for (var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
          invalid_dates_finish.push(jQuery.datepicker.formatDate(w_format, d));
        }
      });
      var string_days = jQuery.datepicker.formatDate(w_format, date);
      var day = date.getDay();

      var w_hide_sunday = w_show_days[0] == 'yes' ? 'true' : 'day != 0';
      var w_hide_monday = w_show_days[1] == 'yes' ? 'true' : 'day != 1';
      var w_hide_tuesday = w_show_days[2] == 'yes' ? 'true' : 'day != 2';
      var w_hide_wednesday = w_show_days[3] == 'yes' ? 'true' : 'day != 3';
      var w_hide_thursday = w_show_days[4] == 'yes' ? 'true' : 'day != 4';
      var w_hide_friday = w_show_days[5] == 'yes' ? 'true' : 'day != 5';
      var w_hide_saturday = w_show_days[6] == 'yes' ? 'true' : 'day != 6';

      return [invalid_dates_finish.indexOf(string_days) == -1 && eval(w_hide_sunday) && eval(w_hide_monday) && eval(w_hide_tuesday) && eval(w_hide_wednesday) && eval(w_hide_thursday) && eval(w_hide_friday) && eval(w_hide_saturday)];
    }
  });

  jQuery("#" + i + "_elementform_id_temp").datepicker('option', 'dateFormat', w_format);

  if ( w_default_date == 'today' ) {
    jQuery("#" + i + "_elementform_id_temp").datepicker("setDate", new Date());
  }
  else if ( w_default_date.indexOf("d") == -1 && w_default_date.indexOf("m") == -1 && w_default_date.indexOf("y") == -1 && w_default_date.indexOf("w") == -1 ) {
    if ( w_default_date !== "" ) {
      w_default_date = jQuery.datepicker.formatDate(w_format, new Date(w_default_date));
    }
    jQuery("#" + i + "_elementform_id_temp").datepicker("setDate", w_default_date);
  }
  else {
    jQuery("#" + i + "_elementform_id_temp").datepicker("setDate", w_default_date);
  }
}

function create_enable_options_value(i, w_value_disabled, w_use_for_submission, type) {
  var label = jQuery('<label class="fm-field-label" for="el_disable_value">Enable option\'s value</label>');
  var input = jQuery('<input type="checkbox" id="el_disable_value" onchange="refresh_sel_options(' + i + ', \'' + type + '\')"' + (w_value_disabled == 'yes' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label class="fm-field-label" for="el_use_for_submission">Use for submission</label>');
  var input2 = jQuery('<input type="checkbox" id="el_use_for_submission" onchange="refresh_for_sub_options(' + i + ', \'' + type + '\')"' + (w_use_for_submission == "yes" ? ' checked="checked"' : '')  +' />');
  return create_double_option_container(label, input, label2, input2, w_value_disabled);
}

function refresh_sel_options(id, type) {
  if (type == 'checkbox' || type == 'radio') {
    if (jQuery('#el_disable_value').prop('checked')) {
      jQuery('#' + id + '_value_disabledform_id_temp').val('yes');
      jQuery('.el_option_value').prop('disabled', false);
      jQuery('.fm-option-wrapper2').show();
    }
    else {
      jQuery('#' + id + '_value_disabledform_id_temp').val('no');
      jQuery('.el_option_value').prop('disabled', true);
      jQuery('.fm-option-wrapper2').hide();
    }
    refresh_rowcol(id, type);
  }
  if (type == 'select') {
    if (jQuery('#el_disable_value').prop('checked')) {
      jQuery('#' + id + '_value_disabledform_id_temp').val('yes');
      jQuery('.el_option_value').prop('disabled', false);
      jQuery('.el_option_dis').prop('disabled', true);
      jQuery('.fm-option-wrapper2').show();
    }
    else {
      jQuery('#' + id + '_value_disabledform_id_temp').val('no');
      jQuery('.el_option_value').prop('disabled', true);
      jQuery('.el_option_dis').prop('disabled', false);
      jQuery('.fm-option-wrapper2').hide();
    }
    var select = document.getElementById(id + '_elementform_id_temp');
    select.innerHTML = '';
    jQuery('.change_pos').each(function () {
      var idi = jQuery(this)[0].id;
      var option = document.createElement('option');
      option.setAttribute("id", id + "_option" + idi);
      if (jQuery('#el_disable_value').prop('checked')) {
        option.setAttribute("value", jQuery(this).find(jQuery("input[type='text']"))[1].value);
      }
      else {
        if (jQuery(this).find(jQuery("input[type='checkbox']")).prop('checked')) {
          option.value = "";
        }
        else {
          option.setAttribute("value", jQuery(this).find(jQuery("input[type='text']"))[0].value);
        }
      }
      if (jQuery(this).find(jQuery(".el_option_params")).val()) {
        w_params = jQuery(this).find(jQuery(".el_option_params")).val().split("[where_order_by]");
        option.setAttribute("where", w_params[0]);
        w_params = w_params[1].split("[db_info]");
        option.setAttribute("order_by", w_params[0]);
        option.setAttribute("db_info", w_params[1]);
      }
      option.setAttribute("onselect", "set_select('" + id + "_option" + idi + "')");
      option.innerHTML = jQuery(this).find(jQuery("input[type='text']"))[0].value;
      select.appendChild(option);
    });
  }
  if ( form_maker.is_demo ) {
    jQuery('#el_choices_add').next().attr("onclick", "alert('This feature is disabled in demo.')");
  }
  else {
    jQuery('#el_choices_add').next().attr("onclick", "tb_show('', 'admin-ajax.php?action=select_data_from_db&field_id=" + id + "&nonce=" + fm_ajax.ajaxnonce + "&field_type=" + type + "&value_disabled=" + jQuery("#" + id + "_value_disabledform_id_temp").val() + "&width=530&height=370&TB_iframe=1');return false;");
  }
}

function refresh_for_sub_options(id, type) {
  if (type == 'checkbox' || type == 'radio') {
    if (jQuery('#el_use_for_submission').prop('checked')) {
      jQuery('#' + id + '_use_for_submissionform_id_temp').val('yes');
    }
    else {
      jQuery('#' + id + '_use_for_submissionform_id_temp').val('no');
    }
    refresh_rowcol(id, type);
  }
  if (type == 'select') {
    if (jQuery('#el_use_for_submission').prop('checked')) {
      jQuery('#' + id + '_use_for_submissionform_id_temp').val('yes');
    }
    else {
      jQuery('#' + id + '_use_for_submissionform_id_temp').val('no');
    }
    var select = document.getElementById(id + '_elementform_id_temp');
    select.innerHTML = '';
    jQuery('.change_pos').each(function () {
      var idi = jQuery(this)[0].id;
      var option = document.createElement('option');
      option.setAttribute("id", id + "_option" + idi);
      if (jQuery('#el_disable_value').prop('checked')) {
        option.setAttribute("value", jQuery(this).find(jQuery("input[type='text']"))[1].value);
      }
      else {
        if (jQuery(this).find(jQuery("input[type='checkbox']")).prop('checked')) {
          option.value = "";
        }
        else {
          option.setAttribute("value", jQuery(this).find(jQuery("input[type='text']"))[0].value);
        }
      }
      if (jQuery(this).find(jQuery(".el_option_params")).val()) {
        w_params = jQuery(this).find(jQuery(".el_option_params")).val().split("[where_order_by]");
        option.setAttribute("where", w_params[0]);
        w_params = w_params[1].split("[db_info]");
        option.setAttribute("order_by", w_params[0]);
        option.setAttribute("db_info", w_params[1]);
      }
      option.setAttribute("onselect", "set_select('" + id + "_option" + idi + "')");
      option.innerHTML = jQuery(this).find(jQuery("input[type='text']"))[0].value;
      select.appendChild(option);
    });
  }
  if ( form_maker.is_demo ) {
    jQuery('#el_choices_add').next().attr("onclick", "alert('This feature is disabled in demo.')");
  }
  else {
    jQuery('#el_choices_add').next().attr("onclick", "tb_show('', 'admin-ajax.php?action=select_data_from_db&field_id=" + id + "&nonce=" + fm_ajax.ajaxnonce + "&field_type=" + type + "&value_disabled=" + jQuery("#" + id + "_value_disabledform_id_temp").val() + "&width=530&height=370&TB_iframe=1');return false;");
  }
}

function create_select_options(i, w_value_disabled, w_choices, w_choices_params, w_choices_value, w_choices_disabled) {
  var label = jQuery('<label class="fm-field-label">Options</label>');
  var button1 = jQuery('<button id="el_choices_add" class="fm-add-option button-secondary" onClick="add_choise(\'select\', ' + i + '); return false;" title="Add option"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>Option</button>');
  if ( form_maker.is_demo ) {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="alert(\'This feature is disabled in demo.\') return false;" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  else {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="tb_show(\'\', \'admin-ajax.php?action=select_data_from_db&nonce=' + fm_ajax.ajaxnonce + '&field_id=' + i + '&field_type=select&value_disabled=' + w_value_disabled + '&width=530&height=370&TB_iframe=1\'); return false;" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  var note = jQuery('<div class="fm-width-100 error">IMPORTANT! Check the "Empty value" checkbox only if you want the option to be considered as empty.</div>');
  var attr_table = jQuery('<div id="choices" class="fm-width-100 ui-sortable"></div>');
  var attr_header = jQuery('<div class="fm-width-100"><div class="fm-header-label fm-width-30">Name</div><div class="fm-header-label fm-width-30">Value</div><div class="fm-header-label fm-width-20">Empty value</div><div class="fm-header-label fm-width-10">Delete</div><div class="fm-header-label fm-width-10">Move</div></div>');
  attr_table.append(attr_header);

  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var attr = jQuery('<div id="' + j + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-30">' +
       '<input type="text" class="fm-field-choice" id="el_option' + j + '" value="' + w_choices[j].replace(/"/g, "&quot;") + '" onKeyUp="change_label_name(' + j + ', \'' + i + '_option' + j + '\',  this.value, \'select\')" onpaste="elem=this; change_label_name_on_paste(' + j + ', \'' + i + '_option' + j + '\', \'select\')"' + (w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-30">' +
      '<input type="text" class="fm-field-choice' + (!w_choices_params[j] ? ' el_option_value' : '') + '" id="el_option_value' + j + '" value="' + w_choices_value[j].replace(/"/g, "&quot;") + '" onKeyUp="change_label_value(\'' + i + '_option' + j + '\',  this.value)" onpaste="change_label_value_on_paste(\'' + i + '_option' + j + '\', this)"' + (w_value_disabled == 'no' || w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
        '<input type="checkbox" title="Empty value" class="el_option_dis" id="el_option' + j + '_dis" onClick="dis_option(\'' + i + '_option' + j + '\', this.checked, ' + j + ')"' + (w_value_disabled == 'yes' ? ' disabled="disabled"' : '') + (w_choices_disabled[j] ? ' checked="checked"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
        '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + j + '_remove" onClick="remove_option(' + j + ', ' + i + ')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
        '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '<input type="hidden" class="el_option_params" id="el_option_params' + j + '" value="' + w_choices_params[j] + '" />' +
    '</div>');
    attr_table.append(attr);
  }

  var input = label;
  input = input.add(button1);
  input = input.add(button2);
  input = input.add(attr_table);
  input = input.add(note);
  return create_option_container(null, input);
}

function add_choise(type, num) {
  var max_value = 0;
  jQuery('.change_pos').each(function () {
    var value = parseInt(jQuery(this)[0].id);
    max_value = (value > max_value) ? value : max_value;
  });

  max_value = max_value + 1;
  if (type == 'radio' || type == 'checkbox') {
    var attr_table = jQuery('#choices');
    var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice" id="el_choices' + max_value + '" value="" onKeyUp="change_label_name(\'' + max_value + '\', \'' + num + '_label_element' + max_value + '\', this.value, \'' + type + '\'); change_label_value(\'' + num + '_elementform_id_temp' + max_value + '\', jQuery(\'#el_option_value' + max_value + '\').val())" onpaste="elem=this; change_label_name_on_paste(' + max_value + ', \'' + num + '_label_element' + max_value + '\', \'' + type + '\'); change_label_value_on_paste(\'' + num + '_elementform_id_temp' + max_value + '\', this)" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice el_option_value" id="el_option_value' + max_value + '" value="" onKeyUp="change_label_value(\'' + num + '_elementform_id_temp' + max_value + '\', this.value)" onpaste="change_label_value_on_paste(\'' + num + '_elementform_id_temp' + max_value + '\', this)"' + (jQuery('#el_disable_value').prop('checked') ? '' : 'disabled="disabled"') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_choices' + max_value + '_remove" onClick="remove_choise(' + max_value + ',' + num + ',\'' + type + '\')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);

    refresh_rowcol(num, type);
  }

  if (type == 'select') {
    var select_ = jQuery('#' + num + '_elementform_id_temp');
    var option = jQuery('<option id="' + num + '_option' + max_value + '"></option>');
    select_.append(option);

    var attr_table = jQuery('#choices');
    var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-30">' +
      '<input type="text" class="fm-field-choice" id="el_option' + max_value + '" value="" onKeyUp="change_label_name(' + max_value + ', \'' + num + '_option' + max_value + '\',  this.value, \'select\')" onpaste="elem=this; change_label_name_on_paste(' + max_value + ', \'' + num + '_option' + max_value + '\', \'select\')" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-30">' +
      '<input type="text" class="fm-field-choice el_option_value" id="el_option_value' + max_value + '" value="" onKeyUp="change_label_value(\'' + num + '_option' + max_value + '\',  this.value)" onpaste="change_label_value_on_paste(\'' + num + '_option' + max_value + '\', this)"' + (!jQuery('#el_disable_value').prop('checked') ? 'disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="checkbox" title="Empty value" class="el_option_dis" id="el_option' + max_value + '_dis" onClick="dis_option(\'' + num + '_option' + max_value + '\', this.checked, ' + max_value + ')"' + (jQuery('#el_disable_value').prop('checked') ? 'disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + max_value + '_remove" onClick="remove_option(' + max_value + ', ' + num + ')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '<input type="hidden" id="el_option_params' +  max_value + '" class="el_option_params" value=""></div>');
    attr_table.append(attr);
  }
}

function change_label_name(num, id, label, type) {
  jQuery('#' + id).html(label);
  if (!jQuery('#el_disable_value').prop('checked')) {
    if (!jQuery('#el_choices' + num).attr('other')) {
      jQuery('#el_option_value' + num).val(label);
    }
    if (type == 'select') {
      jQuery('#' + id).val(label);
    }
  }
}

function change_label_name_on_paste(num, id, label, type) {
  setTimeout(function () {
    label = elem.value;
    jQuery('#' + id).html(label);
    if (!jQuery('#el_disable_value').prop('checked')) {
      if (!jQuery('#el_choices' + num).attr('other')) {
        jQuery('#el_option_value' + num).val(label);
      }
      if (type == 'select') {
        jQuery('#' + id).val(label);
      }
    }
  }, 100);
}

function change_label_value(id, label) {
  var label_value = label.replaceAll("'","");
  jQuery(this).val(label_value);
  document.getElementById(id).value = label_value;
}

function change_label_value_on_paste(id, elem) {
  setTimeout(function () {
    label = elem.value;
    document.getElementById(id).value = label;
  }, 100);
}

function dis_option(id, value, num) {
  if (value) {
    jQuery('#' + id).val('');
    jQuery('#el_option_value' + num).val('');
  }
  else {
    jQuery('#' + id).val(jQuery('#' + id).html());
    jQuery('#el_option_value' + num).val(jQuery('#el_option' + num).val());
  }
}

function remove_option(id, num) {
  var select_ = document.getElementById(num + '_elementform_id_temp');
  var option = document.getElementById(num + '_option' + id);
  select_.removeChild(option);
  var choices_td = document.getElementById('choices');
  var div = document.getElementById(id);
  choices_td.removeChild(div);
}

function go_to_type_own_select(new_id) {
  w_choices = ["Select value", "option 1", "option 2"];
  w_choices_value = ["", "option 1", "option 2"];
  w_choices_params = ["", "", ""];
  w_choices_checked = ["1", "0", "0"];
  w_choices_disabled = [true, false, false];
  w_attr_name = [];
  w_attr_value = [];
  type_own_select(new_id, 'Select', '', 'top', 'no', '', w_choices, w_choices_checked, 'no', 'no', 'wdform_select', w_attr_name, w_attr_value, w_choices_disabled, w_choices_value, w_choices_params, 'no');
}

function type_own_select(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_choices, w_choices_checked, w_required, w_value_disabled, w_class, w_attr_name, w_attr_value, w_choices_disabled, w_choices_value, w_choices_params, w_use_for_submission) {
  jQuery("#element_type").val("type_own_select");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_own_select'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size));
  edit_main_table.append(create_select_options(i, w_value_disabled, w_choices, w_choices_params, w_choices_value, w_choices_disabled));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_enable_options_value(i, w_value_disabled, w_use_for_submission, 'select'));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_own_select'));

  // Preview.
  n = w_choices.length;
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_own_select");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_value_disabled = document.createElement("input");
  adding_value_disabled.setAttribute("type", "hidden");
  adding_value_disabled.setAttribute("value", w_value_disabled);
  adding_value_disabled.setAttribute("name", i + "_value_disabledform_id_temp");
  adding_value_disabled.setAttribute("id", i + "_value_disabledform_id_temp");

  var use_for_submission = document.createElement("input");
  use_for_submission.setAttribute("type", "hidden");
  use_for_submission.setAttribute("value", w_use_for_submission);
  use_for_submission.setAttribute("name", i + "_use_for_submissionform_id_temp");
  use_for_submission.setAttribute("id", i + "_use_for_submissionform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table";

  var tr_little1 = document.createElement('div');
  tr_little1.setAttribute("id", i + "_element_tr1");
  tr_little1.style.display = "table-row";

  var tr_little2 = document.createElement('div');
  tr_little2.setAttribute("id", i + "_element_tr2");
  tr_little2.style.display = "table-row";

  var td_little1 = document.createElement('div');
  td_little1.setAttribute("valign", 'top');
  td_little1.setAttribute("id", i + "_td_little1");
  td_little1.style.display = "table-cell";

  var td_little2 = document.createElement('div');
  td_little2.setAttribute("valign", 'top');
  td_little2.setAttribute("id", i + "_td_little2");
  td_little2.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  var select_ = document.createElement('select');
  select_.setAttribute("id", i + "_elementform_id_temp");
  select_.setAttribute("name", i + "_elementform_id_temp");
  select_.style.cssText = "width:" + w_size + "px";
  select_.setAttribute("onchange", "set_select(this)");

  for (j = 0; j < n; j++) {
    var option = document.createElement('option');
    option.setAttribute("id", i + "_option" + j);
    if (w_value_disabled == 'yes')
      option.setAttribute("value", w_choices_value[j].replace(/[\'\"]/g, ""));
    else {
      if (w_choices_disabled[j])
        option.value = "";
      else
        option.setAttribute("value", w_choices[j].replace(/[\'\"]/g, ""));
    }

    if (w_choices_params[j]) {
      w_params = w_choices_params[j].split("[where_order_by]");
      option.setAttribute("where", w_params[0]);
      w_params = w_params[1].split("[db_info]");
      option.setAttribute("order_by", w_params[0]);
      option.setAttribute("db_info", w_params[1]);
    }
    option.setAttribute("onselect", "set_select('" + i + "_option" + j + "')");
    option.innerHTML = w_choices[j].replace(/"/g, "'");
    if (w_choices_checked[j] == 1)
      option.setAttribute("selected", "selected");
    select_.appendChild(option);
  }

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_value_disabled);
  div_element.appendChild(use_for_submission);
  div_element.appendChild(select_);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);

  if (w_field_label_pos == "top") {
    label_top(i);
  }
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
//enable_modals();
  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_sel_options(i, 'select');
      }
    });
  });
  jQuery("#main_div").append( form_maker.type_select_description );
}

function create_relative_position(i, w_flow, type) {
  var label = jQuery('<label class="fm-field-label">Relative Position</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_flow_vertical" name="edit_for_flow" value="ver" onchange="refresh_rowcol(' + i + ',\'' + type + '\')"' + (w_flow == "hor" ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="edit_for_flow_vertical">Vertical</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_flow_horizontal" name="edit_for_flow" value="hor" onchange="refresh_rowcol(' + i + ',\'' + type + '\')"' + (w_flow == "hor" ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_flow_horizontal">Horizontal</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function refresh_limit_choice(num, type) {
  if (!document.getElementById('edit_for_limitchoice').value) {
    document.getElementById('edit_for_limitchoice').value = "";
  }
  document.getElementById(num + '_limitchoice_numform_id_temp').value = document.getElementById('edit_for_limitchoice').value;
  refresh_attr(num, 'type_' + type);
}
function refresh_limit_choice_alert(num, type) {
  if (!document.getElementById('edit_for_limitchoicealert').value) {
    document.getElementById('edit_for_limitchoicealert').value = "You have exceeded the selection limit.";
  }
  document.getElementById(num + '_limitchoicealert_numform_id_temp').value = document.getElementById('edit_for_limitchoicealert').value;
  refresh_attr(num, 'type_' + type);
}

function refresh_characters_limit(num, type) {
  if (!document.getElementById('edit_for_charlimit').value) {
    document.getElementById('edit_for_charlimit').value = "";
  }
  document.getElementById(num + '_charlimitform_id_temp').value = document.getElementById('edit_for_charlimit').value;
  refresh_attr(num, 'type_' + type);
}

function refresh_rowcol(num, type) {
  if (!document.getElementById('edit_for_rowcol').value) {
    document.getElementById('edit_for_rowcol').value = 1;
  }
  document.getElementById(num + '_rowcol_numform_id_temp').value = document.getElementById('edit_for_rowcol').value;
  var table = document.getElementById(num + '_table_little');
  table.removeAttribute("for_hor");
  table.innerHTML = "";
  choeices = jQuery('.change_pos').length;
  if (document.getElementById('edit_for_flow_vertical').checked == true) {
    var columns = document.getElementById('edit_for_rowcol').value;
    var rows = parseInt((choeices + 1) / columns);
    var gago = 0;
    var vaxo = 1;
    tr_row = document.createElement('div');
    tr_row.setAttribute("id", num + "_element_tr0");
    tr_row.style.display = 'table-row';
    jQuery('.change_pos').each(function () {
      var index = jQuery(this)[0].id;
      if (gago >= columns) {
        gago = 0;
        tr_row = document.createElement('div');
        tr_row.setAttribute("id", num + "_element_tr" + vaxo);
        tr_row.style.display = 'table-row';
        vaxo++;
      }
      var td = document.createElement('div');
      td.setAttribute("valign", "top");
      td.setAttribute("id", num + "_td_little" + index);
      td.setAttribute("idi", index);
      td.style.display = 'table-cell';
      var adding = document.createElement('input');
      adding.setAttribute("type", type);
      adding.setAttribute("id", num + "_elementform_id_temp" + index);
      if ( jQuery("#"+num + "_elementform_id_temp" + index).prop("checked") )
        adding.setAttribute("checked", "checked");
      if (document.getElementById(num + "_option_left_right").value == "right")
        adding.style.cssText = "float: left !important";
      if (type == 'checkbox') {
        adding.setAttribute("name", num + "_elementform_id_temp" + index);
        if (document.getElementById(num + "_allow_otherform_id_temp").value == "yes" && jQuery(this).find('#el_choices' + index).attr("other") == '1') {
          adding.setAttribute("value", "");
          adding.setAttribute("other", "1");
          adding.setAttribute("onclick", "if(set_checked('" + num + "','" + index + "','form_id_temp')) show_other_input('" + num + "','form_id_temp');");
        }
        else {
          if (document.getElementById(num + "_value_disabledform_id_temp").value == "no") {
            var val = jQuery(this).find('#el_choices' + index).val();
            val = val.replace(/[\"\']/g, "");
            adding.setAttribute("value", val);
          }
          else {
            var val = jQuery(this).find('#el_option_value' + index).val();
            val = val.replace(/[\"\']/g, "");
            adding.setAttribute("value", val);
          }
          adding.setAttribute("onclick", "set_checked('" + num + "','" + index + "','form_id_temp')");
        }

      }
      if (type == 'radio') {
        adding.setAttribute("name", num + "_elementform_id_temp");
        if (document.getElementById(num + "_allow_otherform_id_temp").value == "yes" && jQuery(this).find('#el_choices' + index).attr("other") == '1') {
          adding.setAttribute("value", "");
          adding.setAttribute("other", "1");
          adding.setAttribute("onClick", "set_default('" + num + "','" + index + "','form_id_temp'); show_other_input('" + num + "','form_id_temp');");
        }
        else {
          if (document.getElementById(num + "_value_disabledform_id_temp").value == "no") {
            var val = jQuery(this).find('#el_choices' + index).val();
            val = val.replace(/[\"\']/g, "");
            adding.setAttribute("value", val);
          }
          else {
            var val = jQuery(this).find('#el_option_value' + index).val();
            val = val.replace(/[\"\']/g, "");
            adding.setAttribute("value", val);
          }
          adding.setAttribute("onClick", "set_default('" + num + "','" + index + "','form_id_temp')");
        }
      }
      var label_adding = document.createElement('label');
      label_adding.setAttribute("id", num + "_label_element" + index);
      label_adding.setAttribute("class", "ch-rad-label");
      label_adding.setAttribute("for", num + "_elementform_id_temp" + index);
      label_adding.innerHTML = jQuery(this).find('#el_choices' + index).val().replaceAll('"',"'");
      if (document.getElementById(num + "_option_left_right").value == "right")
        label_adding.style.cssText = "float: none !important";
      if (jQuery(this).find('#el_option_params' + index).val()) {
        w_params = jQuery(this).find('#el_option_params' + index).val().split("[where_order_by]");
        label_adding.setAttribute("where", w_params[0]);
        w_params = w_params[1].split("[db_info]");
        label_adding.setAttribute("order_by", w_params[0]);
        label_adding.setAttribute("db_info", w_params[1]);
      }
      td.appendChild(label_adding);
      td.appendChild(adding);
      tr_row.appendChild(td);
      table.appendChild(tr_row);
      gago++;
    });
  }
  else {
    var rows = document.getElementById('edit_for_rowcol').value;
    var columns = parseInt((choeices + 1) / rows);
    var gago = 0;
    var vaxo = 0;
    jQuery('.change_pos').each(function (key) {
      var index = jQuery(this)[0].id;
      if (gago < rows) {
        tr_row = document.createElement('div');
        tr_row.setAttribute("id", num + "_element_tr" + key);
        tr_row.style.display = 'table-row';

        if(type == 'radio' || type == 'checkbox') {
          tr_row.style.display = 'inline-flex';
          tr_row.style.flexWrap = 'wrap';
        }
      }
      var td = document.createElement('div');
      td.setAttribute("valign", "top");
      td.setAttribute("id", num + "_td_little" + index);
      td.setAttribute("idi", index);
      td.style.display = 'table-cell';
      var adding = document.createElement('input');
      adding.setAttribute("type", type);
      adding.setAttribute("id", num + "_elementform_id_temp" + index);
      if ( jQuery("#"+num + "_elementform_id_temp" + index).prop("checked") )
        adding.setAttribute("checked", "checked");
      if (document.getElementById(num + "_option_left_right").value == "right")
        adding.style.cssText = "float: left !important";
      if (type == 'checkbox') {
        adding.setAttribute("name", num + "_elementform_id_temp" + index);
        if (document.getElementById(num + "_allow_otherform_id_temp").value == "yes" && jQuery(this).find('#el_choices' + index).attr('other') == '1') {
          adding.setAttribute("value", "");
          adding.setAttribute("other", "1");
          adding.setAttribute("onclick", "if(set_checked('" + num + "','" + index + "','form_id_temp')) show_other_input('" + num + "','form_id_temp');");
        }
        else {
          if (document.getElementById(num + "_value_disabledform_id_temp").value == "no")
            adding.setAttribute("value", jQuery(this).find('#el_choices' + index).val());
          else
            adding.setAttribute("value", jQuery(this).find('#el_option_value' + index).val());
          adding.setAttribute("onclick", "set_checked('" + num + "','" + index + "','form_id_temp')");
        }
      }
      if (type == 'radio') {
        adding.setAttribute("name", num + "_elementform_id_temp");
        if (document.getElementById(num + "_allow_otherform_id_temp").value == "yes" && jQuery(this).find('#el_choices' + index).attr('other') == '1') {
          adding.setAttribute("other", "1");
          adding.setAttribute("onClick", "set_default('" + num + "','" + index + "','form_id_temp'); show_other_input('" + num + "','form_id_temp')");
        }
        else {
          if (document.getElementById(num + "_value_disabledform_id_temp").value == "no") {
            var val = jQuery(this).find('#el_choices' + index).val();
            val = val.replaceAll("'", "");
            adding.setAttribute("value", val);
          }
          else {
            var val = jQuery(this).find('#el_option_value' + index).val();
            val = val.replaceAll("'", "");
            adding.setAttribute("value", val);
          }
          adding.setAttribute("onClick", "set_default('" + num + "','" + index + "','form_id_temp')");
        }
      }
      var label_adding = document.createElement('label');
      label_adding.setAttribute("id", num + "_label_element" + index);
      label_adding.setAttribute("class", "ch-rad-label");
      label_adding.setAttribute("for", num + "_elementform_id_temp" + index);
      label_adding.innerHTML = jQuery(this).find('#el_choices' + index).val().replaceAll('"',"'");
      if (document.getElementById(num + "_option_left_right").value == "right")
        label_adding.style.cssText = "float: none !important";
      if (jQuery(this).find('#el_option_params' + index).val()) {
        w_params = jQuery(this).find('#el_option_params' + index).val().split("[where_order_by]");
        label_adding.setAttribute("where", w_params[0]);
        w_params = w_params[1].split("[db_info]");
        label_adding.setAttribute("order_by", w_params[0]);
        label_adding.setAttribute("db_info", w_params[1]);
      }
      td.appendChild(label_adding);
      td.appendChild(adding);
      if (gago < rows) {
        tr_row.appendChild(td);
        table.appendChild(tr_row);
      }
      else {
        if (vaxo == rows) {
          vaxo = 0;
        }
        tr_row = document.getElementById(num + '_table_little').childNodes[vaxo];
        tr_row.appendChild(td);
        vaxo++;
      }
      gago++;
    });
    table.setAttribute("for_hor", num + "_hor");
  }
  refresh_attr(num, 'type_' + type);
}

function show_other_input(num) {
  jQuery('.change_pos').each(function () {
    var k = jQuery(this)[0].id;
    if (document.getElementById(num + "_elementform_id_temp" + k))
      if (document.getElementById(num + "_elementform_id_temp" + k).getAttribute('other'))
        if (document.getElementById(num + "_elementform_id_temp" + k).getAttribute('other') == 1) {
          element_other = document.getElementById(num + "_elementform_id_temp" + k);
          return false;
        }
  });

  var parent = element_other.parentNode;
  var br = document.createElement('br');
  br.setAttribute("id", num + "_other_brform_id_temp");

  var el_other = document.createElement('input');
  el_other.setAttribute("id", num + "_other_inputform_id_temp");
  el_other.setAttribute("name", num + "_other_inputform_id_temp");
  el_other.setAttribute("type", "text");
  el_other.setAttribute("class", "other_input");
  parent.appendChild(br);
  parent.appendChild(el_other);
}

function create_option_label_position(i, w_field_option_pos, type) {
  var label = jQuery('<label class="fm-field-label">Option Label Position</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_option_position_left" name="edit_for_option_position" onchange="option_left(' + i + ',\'' + type + '\')"' + (w_field_option_pos == "right" ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="edit_for_option_position_left">Left</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_option_position_right" name="edit_for_option_position" onchange="option_right(' + i + ',\'' + type + '\')"' + (w_field_option_pos == "right" ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_option_position_right">Right</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function option_right(id, type) {
  jQuery('#' + id + '_table_little').find(jQuery('.ch-rad-label')).css("cssText", "float: none !important;");
  jQuery('#' + id + '_table_little').find(jQuery('#main_div input[type="' + type + '"]')).css("cssText", "float: left !important;");
  jQuery('#' + id + '_option_left_right').val('right');
}

function option_left(id, type) {
  jQuery('#' + id + '_table_little').find(jQuery('.ch-rad-label')).css("cssText", "float: left !important;");
  jQuery('#' + id + '_table_little').find(jQuery('#main_div input[type="' + type + '"]')).css("cssText", "float: right !important;");
  jQuery('#' + id + '_option_left_right').val('left');
}

function create_rowcol(i, w_rowcol, type) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_rowcol">Rows/Columns</label>');
  var input = jQuery('<input type="number" class="fm-width-100" min="1" id="edit_for_rowcol" oninput="validity.valid||(value=\'\')"; onChange="refresh_rowcol(' + i + ',\'' + type + '\')" value="' + w_rowcol + '" />');
  return create_option_container(label, input);
}

function create_limit_choice(i, w_limit_choice, type) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_limitchoice">Limit of Selected Choices</label>');
  var input = jQuery('<input type="number" class="fm-width-100" id="edit_for_limitchoice" min="0" oninput="validity.valid||(value=\'\')"; onChange="refresh_limit_choice(' + i + ',\'' + type + '\')" value="' + w_limit_choice + '" />');
  return create_option_container(label, input);
}

function create_limit_choice_alert(i, w_limit_choice_alert, type) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_limitchoicealert">Alert for Selected Choice Limit</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_limitchoicealert" onChange="refresh_limit_choice_alert(' + i + ',\'' + type + '\')" value="' + w_limit_choice_alert.replace(/"/g, "&quot;") + '" />');
  return create_option_container(label, input);
}

function create_characters_limit(i, w_characters_limit, type) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_charlimit">Limit of characters</label>');
  var input = jQuery('<input type="number" class="fm-width-100" id="edit_for_charlimit" min="0" oninput="validity.valid||(value=\'\');" onChange="refresh_characters_limit(' + i + ',\'' + type + '\')" value="' + w_characters_limit + '" />');
  return create_option_container(label, input);
}

function create_randomize(i, w_randomize) {
  var label = jQuery('<label class="fm-field-label" for="el_randomize">Randomize in frontend</label>');
  var input = jQuery('<input type="checkbox" id="el_randomize" onclick="set_randomize(\'' + i + '_randomizeform_id_temp\')"' + (w_randomize == "yes" ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_randomize(id) {
  if (document.getElementById(id).value == "yes") {
    document.getElementById(id).setAttribute("value", "no");
  }
  else {
    document.getElementById(id).setAttribute("value", "yes")
  }
}

function create_allow_other(i, w_allow_other, type) {
  var label = jQuery('<label class="fm-field-label" for="el_allow_other">Allow other</label>');
  var input = jQuery('<input type="checkbox" id="el_allow_other" onclick="set_allow_other(' + i + ',\'' + type + '\')"' + (w_allow_other == "yes" ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_allow_other(num, type) {
  if (document.getElementById(num + '_allow_otherform_id_temp').value == "yes") {
    document.getElementById(num + '_allow_otherform_id_temp').setAttribute("value", "no");
    jQuery('.change_pos').each(function () {
      var k = jQuery(this)[0].id;
      if (document.getElementById("el_choices" + k)) {
        if (document.getElementById("el_choices" + k).getAttribute('other')) {
          if (document.getElementById("el_choices" + k).getAttribute('other') == 1) {
            remove_choise(k, num, type);
            return false;
          }
        }
      }
    });
  }
  else {
    document.getElementById(num + '_allow_otherform_id_temp').setAttribute("value", "yes");
    var max_value = 0;
    jQuery('.change_pos').each(function () {
      var value = parseInt(jQuery(this)[0].id);
      max_value = (value > max_value) ? value : max_value;
    });

    max_value = max_value + 1;
    var attr_table = jQuery('#choices');
    var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice" id="el_choices' + max_value + '" value="other" other="1" onKeyUp="change_label(\'' + num + '_label_element' + max_value + '\', this.value); change_in_value(\'' + num + '_elementform_id_temp' + max_value + '\', this.value)" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice" id="el_option_value' + max_value + '" value="" disabled="disabled" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);
    refresh_rowcol(num, type);
  }
}

function create_radio_options(i, w_value_disabled, w_choices, w_choices_params, w_choices_value, w_allow_other, w_allow_other_num, w_choices_checked, type) {
  var label = jQuery('<label class="fm-field-label">Options</label>');
  var button1 = jQuery('<button id="el_choices_add" class="fm-add-option button-secondary" onClick="add_choise(\'' + type + '\', ' + i + '); return false;" title="Add option"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>Option</button>');
  if ( form_maker.is_demo ) {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="alert(\'This feature is disabled in demo.\')" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  else {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="tb_show(\'\', \'admin-ajax.php?action=select_data_from_db&field_id=' + i + '&nonce=' + fm_ajax.ajaxnonce + '&field_type=radio&value_disabled=' + w_value_disabled + '&width=530&height=370&TB_iframe=1\'); return false;" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  var attr_table = jQuery('<div id="choices" class="fm-width-100 ui-sortable"></div>');
  var attr_header = jQuery('<div class="fm-width-100"><div class="fm-header-label fm-width-40">Name</div><div class="fm-header-label fm-width-40">Value</div><div class="fm-header-label fm-width-10">Delete</div><div class="fm-header-label fm-width-10">Move</div></div>');
  attr_table.append(attr_header);

  aaa = false;
  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var attr = jQuery('<div id="' + j + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice" id="el_choices' + j + '"' + (w_allow_other == "yes" && j == w_allow_other_num ? ' other="1"' : '') + ' value="' + w_choices[j].replace(/"/g, "&quot;") + '" onKeyUp="change_label(\'' + i + '_label_element' + j + '\', this.value)" onpaste="elem=this; change_label_name_on_paste(' + j + ', \'' + i + '_label_element' + j + '\', \'' + type + '\'); change_label_value_on_paste(\'' + i + '_elementform_id_temp' + j + '\', this)"' + (w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice' + (!w_choices_params[j] && (w_allow_other != "yes" || j != w_allow_other_num) ? ' el_option_value' : '') + '" id="el_option_value' + j + '" value="' + w_choices_value[j].replace(/[\"\']/g, "") + '" onKeyUp="change_label_value(\'' + i + '_elementform_id_temp' + j + '\', this.value)" onpaste="change_label_value_on_paste(\'' + i + '_elementform_id_temp' + j + '\', this)"' + (w_value_disabled == 'no' || w_choices_params[j] || (w_allow_other == "yes" && j == w_allow_other_num) ? ' disabled="disabled"' : '') + ' />' +
      '<input type="hidden" id="el_option_params' + j + '" class="el_option_params" value="' + w_choices_params[j] + '" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      (w_allow_other == "yes" && j == w_allow_other_num ? '' : '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_choices' + j + '_remove" onClick="remove_choise(' + j + ',' + i + ',\'' + type + '\')"></span>') +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);
    if (w_choices_checked[j] == true) {
      if (w_allow_other == "yes" && j == w_allow_other_num) {
        aaa = true;
      }
    }
  }

  var input = label;
  input = input.add(button1);
  input = input.add(button2);
  input = input.add(attr_table);
  return create_option_container(null, input);
}

function remove_choise(id, num, type) {
  var choices_td = document.getElementById('choices');
  var div = document.getElementById(id);
  choices_td.removeChild(div);
  refresh_rowcol(num, type);
}

function type_radio(i, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_checked, w_rowcol, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value,w_value_disabled, w_choices_value, w_choices_params, w_use_for_submission ) {
  jQuery("#element_type").val("type_radio");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_radio'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_relative_position(i, w_flow, 'radio'));
  edit_main_table.append(create_option_label_position(i, w_field_option_pos, 'radio'));
  edit_main_table.append(create_radio_options(i, w_value_disabled, w_choices, w_choices_params, w_choices_value, w_allow_other, w_allow_other_num, w_choices_checked, 'radio'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_rowcol(i, w_rowcol, 'radio'));
  advanced_options_container.append(create_randomize(i, w_randomize));
  advanced_options_container.append(create_enable_options_value(i, w_value_disabled, w_use_for_submission, 'radio'));
  advanced_options_container.append(create_allow_other(i, w_allow_other, 'radio'));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_radio'));

  // Preview.
  element = 'input';
  type = 'radio';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_radio");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_randomize = document.createElement("input");
  adding_randomize.setAttribute("type", "hidden");
  adding_randomize.setAttribute("value", w_randomize);
  adding_randomize.setAttribute("name", i + "_randomizeform_id_temp");
  adding_randomize.setAttribute("id", i + "_randomizeform_id_temp");

  var adding_allow_other = document.createElement("input");
  adding_allow_other.setAttribute("type", "hidden");
  adding_allow_other.setAttribute("value", w_allow_other);
  adding_allow_other.setAttribute("name", i + "_allow_otherform_id_temp");
  adding_allow_other.setAttribute("id", i + "_allow_otherform_id_temp");

  var adding_rowcol = document.createElement("input");
  adding_rowcol.setAttribute("type", "hidden");
  adding_rowcol.setAttribute("value", w_rowcol);
  adding_rowcol.setAttribute("name", i + "_rowcol_numform_id_temp");
  adding_rowcol.setAttribute("id", i + "_rowcol_numform_id_temp");

  var adding_option_left_right = document.createElement("input");
  adding_option_left_right.setAttribute("type", "hidden");
  adding_option_left_right.setAttribute("value", w_field_option_pos);
  adding_option_left_right.setAttribute("id", i + "_option_left_right");

  var adding_value_disabled = document.createElement("input");
  adding_value_disabled.setAttribute("type", "hidden");
  adding_value_disabled.setAttribute("value", w_value_disabled);
  adding_value_disabled.setAttribute("name", i + "_value_disabledform_id_temp");
  adding_value_disabled.setAttribute("id", i + "_value_disabledform_id_temp");

  var use_for_submission = document.createElement("input");
  use_for_submission.setAttribute("type", "hidden");
  use_for_submission.setAttribute("value", w_use_for_submission);
  use_for_submission.setAttribute("name", i + "_use_for_submissionform_id_temp");
  use_for_submission.setAttribute("id", i + "_use_for_submissionform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little_t = document.createElement('div');
  table_little_t.style.display = "table";

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table-row-group";

  table_little_t.appendChild(table_little);

  var tr_little1 = document.createElement('div');
  tr_little1.setAttribute("id", i + "_element_tr1");
  tr_little1.style.display = "table-row";

  var tr_little2 = document.createElement('div');
  tr_little2.setAttribute("id", i + "_element_tr2");
  tr_little2.style.display = "table-row";

  var td_little1 = document.createElement('div');
  td_little1.setAttribute("valign", 'top');
  td_little1.setAttribute("id", i + "_td_little1");
  td_little1.style.display = "table-cell";

  var td_little2 = document.createElement('div');
  td_little2.setAttribute("valign", 'top');
  td_little2.setAttribute("id", i + "_td_little2");
  td_little2.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_randomize);
  div_element.appendChild(adding_allow_other);
  div_element.appendChild(adding_rowcol);
  div_element.appendChild(adding_option_left_right);
  div_element.appendChild(adding_value_disabled);
  div_element.appendChild(use_for_submission);
  div_element.appendChild(table_little_t);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);

  jQuery("#main_div").append( form_maker.type_radio_description );

  if (w_field_label_pos == "top") {
    label_top(i);
  }
  change_class(w_class, i);
  refresh_rowcol(i, 'radio');
  if (aaa) {
    show_other_input(i);
  }
  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_rowcol(i, 'radio');
      }
    });
  });
}

function type_checkbox(i, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_checked, w_rowcol, w_limit_choice, w_limit_choice_alert,  w_required, w_randomize, w_allow_other,w_allow_other_num, w_class, w_attr_name, w_attr_value, w_value_disabled, w_choices_value, w_choices_params, w_use_for_submission) {
  jQuery("#element_type").val("type_checkbox");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_checkbox'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_relative_position(i, w_flow, 'checkbox'));
  edit_main_table.append(create_option_label_position(i, w_field_option_pos, 'checkbox'));
  edit_main_table.append(create_radio_options(i, w_value_disabled, w_choices, w_choices_params, w_choices_value, w_allow_other, w_allow_other_num, w_choices_checked, 'checkbox'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_rowcol(i, w_rowcol, 'checkbox'));
  advanced_options_container.append(create_limit_choice(i, w_limit_choice, 'checkbox'));
  advanced_options_container.append(create_limit_choice_alert(i, w_limit_choice_alert, 'checkbox'));
  advanced_options_container.append(create_randomize(i, w_randomize));
  advanced_options_container.append(create_enable_options_value(i, w_value_disabled, w_use_for_submission, 'checkbox'));
  advanced_options_container.append(create_allow_other(i, w_allow_other, 'checkbox'));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_checkbox'));

  // Preview.
  element = 'input';
  type = 'checkbox';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_checkbox");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_randomize = document.createElement("input");
  adding_randomize.setAttribute("type", "hidden");
  adding_randomize.setAttribute("value", w_randomize);
  adding_randomize.setAttribute("name", i + "_randomizeform_id_temp");
  adding_randomize.setAttribute("id", i + "_randomizeform_id_temp");

  var adding_allow_other = document.createElement("input");
  adding_allow_other.setAttribute("type", "hidden");
  adding_allow_other.setAttribute("value", w_allow_other);
  adding_allow_other.setAttribute("name", i + "_allow_otherform_id_temp");
  adding_allow_other.setAttribute("id", i + "_allow_otherform_id_temp");

  var adding_allow_other_id = document.createElement("input");
  adding_allow_other_id.setAttribute("type", "hidden");
  adding_allow_other_id.setAttribute("value", w_allow_other_num);
  adding_allow_other_id.setAttribute("name", i + "_allow_other_numform_id_temp");
  adding_allow_other_id.setAttribute("id", i + "_allow_other_numform_id_temp");

  var adding_limit_choice = document.createElement("input");
  adding_limit_choice.setAttribute("type", "hidden");
  adding_limit_choice.setAttribute("value", w_limit_choice);
  adding_limit_choice.setAttribute("name", i + "_limitchoice_numform_id_temp");
  adding_limit_choice.setAttribute("id", i + "_limitchoice_numform_id_temp");

  var adding_limit_choice_alert = document.createElement("input");
  adding_limit_choice_alert.setAttribute("type", "hidden");
  adding_limit_choice_alert.setAttribute("value", w_limit_choice_alert.replace(/"/g, "&quot;"));
  adding_limit_choice_alert.setAttribute("name", i + "_limitchoicealert_numform_id_temp");
  adding_limit_choice_alert.setAttribute("id", i + "_limitchoicealert_numform_id_temp");

  var adding_rowcol = document.createElement("input");
  adding_rowcol.setAttribute("type", "hidden");
  adding_rowcol.setAttribute("value", w_rowcol);
  adding_rowcol.setAttribute("name", i + "_rowcol_numform_id_temp");
  adding_rowcol.setAttribute("id", i + "_rowcol_numform_id_temp");

  var adding_option_left_right = document.createElement("input");
  adding_option_left_right.setAttribute("type", "hidden");
  adding_option_left_right.setAttribute("value", w_field_option_pos);
  adding_option_left_right.setAttribute("id", i + "_option_left_right");

  var adding_value_disabled = document.createElement("input");
  adding_value_disabled.setAttribute("type", "hidden");
  adding_value_disabled.setAttribute("value", w_value_disabled);
  adding_value_disabled.setAttribute("name", i + "_value_disabledform_id_temp");
  adding_value_disabled.setAttribute("id", i + "_value_disabledform_id_temp");

  var use_for_submission = document.createElement("input");
  use_for_submission.setAttribute("type", "hidden");
  use_for_submission.setAttribute("value", w_use_for_submission);
  use_for_submission.setAttribute("name", i + "_use_for_submissionform_id_temp");
  use_for_submission.setAttribute("id", i + "_use_for_submissionform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little_t = document.createElement('div');
  table_little_t.style.display = "table";

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table-row-group";
  table_little_t.appendChild(table_little);

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_randomize);
  div_element.appendChild(adding_allow_other);
  div_element.appendChild(adding_allow_other_id);
  div_element.appendChild(adding_rowcol);
  div_element.appendChild(adding_limit_choice);
  div_element.appendChild(adding_limit_choice_alert);
  div_element.appendChild(adding_option_left_right);
  div_element.appendChild(adding_value_disabled);
  div_element.appendChild(use_for_submission);
  div_element.appendChild(table_little_t);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);

  jQuery("#main_div").append( form_maker.type_checkbox_description );

  if (w_field_label_pos == "top") {
    label_top(i);
  }
  change_class(w_class, i);
  refresh_rowcol(i, 'checkbox');
  if (aaa) {
    show_other_input(i);
  }
  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_rowcol(i, 'checkbox');
      }
    });
  });
}

function create_keys(i, message) {
  var label = jQuery('<label class="fm-field-label">Keys</label>');
  var input = jQuery('<a href="' + admin_url + '?page=options_fm" target="_blank" class="fm-field-recaptcha-label">' + message + '</a>');
  return create_option_container(null, input);
}

function create_recaptcha_invisible(i, w_type) {
  var label = jQuery('<label class="fm-field-label">Type</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_recaptcha_type_v2" name="edit_for_recaptcha_type" value="v2" onchange="fm_recaptcha_type(' + i + ', this.value)"' + (w_type == 'v2' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="edit_for_recaptcha_type_v2">reCAPTCHA v2 (checkbox)</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_recaptcha_type_invisible" name="edit_for_recaptcha_type" value="invisible" onchange="fm_recaptcha_type(' + i + ', this.value)"' + (w_type == 'invisible' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_recaptcha_type_invisible">reCAPTCHA v2 (invisible)</label>');
  var input3 = jQuery('<input type="radio" id="edit_for_recaptcha_type_v3" name="edit_for_recaptcha_type" value="v3" onchange="fm_recaptcha_type(' + i + ', this.value)"' + (w_type == 'v3' ? ' checked="checked"' : '') + ' />');
  var label3 = jQuery('<label for="edit_for_recaptcha_type_v3">reCAPTCHA v3</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(jQuery('<br />'));
  input = input.add(jQuery('<span class="fm-description">Validate users with the "I\'m not a robot" checkbox.</span>'));
  input = input.add(jQuery('<br />'));
  input = input.add(input2);
  input = input.add(label2);
  input = input.add(jQuery('<br />'));
  input = input.add(jQuery('<span class="fm-description">Validate users in the background.</span>'));

  input = input.add(jQuery('<br />'));
  input = input.add(input3);
  input = input.add(label3);
  input = input.add(jQuery('<br />'));
  input = input.add(jQuery('<span class="fm-description">Verify requests with a score.</span>'));

  return create_option_container(label, input);
}

function fm_recaptcha_type(id, value) {
  jQuery('#wd_recaptchaform_id_temp').attr('w_type', value);
  if (value == 'invisible') {
      jQuery('#recaptcha_position').show();
      jQuery('#recaptcha_keys_message').show();
      jQuery('#recaptcha_advanced').hide();
      jQuery('#recaptcha_score').hide();

      jQuery('#'+ id + '_hide_labelform_id_temp').val('no');
      jQuery('#el_hide_label').prop('checked', true);
      hide_label(id);
  } else if(value == 'v3') {
      jQuery('#recaptcha_score').show();
      jQuery('#recaptcha_keys_message').show();
      jQuery('#recaptcha_advanced').hide();
      jQuery('#recaptcha_position').hide();

      jQuery('#'+ id + '_hide_labelform_id_temp').val('no');
      jQuery('#el_hide_label').prop('checked', true);
      hide_label(id);
  } else {
      jQuery('#recaptcha_position').hide();
      jQuery('#recaptcha_score').hide();
      jQuery('#recaptcha_keys_message').hide();
      jQuery('#recaptcha_advanced').show();
  }
}

function create_recaptcha_score(i, w_score, w_type) {
  var label = jQuery('<label class="fm-field-label">Score</label>');
  var input = 'ReCaptcha v3 returns a score based on the user interactions with your forms. Scores range from 0.0 to 1.0, with 0.0 indicating abusive traffic and 1.0 indicating good traffic. ' + '<a href="' + admin_url + '?page=options_fm" target="_blank" class="fm-field-recaptcha-label">To change recaptcha score click here</a>';
  return create_option_container(label, input, 'recaptcha_score', w_type == 'v3');
}

function create_recaptcha_position(i, w_position, w_type) {
  var label = jQuery('<label class="fm-field-label">Display</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_recaptcha_position_hidden" name="edit_for_recaptcha_position" value="hidden" onchange="fm_recaptcha_position(' + i + ', this.value)"' + (w_position == 'hidden' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="edit_for_recaptcha_position_hidden">Hidden</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_recaptcha_position_inline" name="edit_for_recaptcha_position" value="inline" onchange="fm_recaptcha_position(' + i + ', this.value)"' + (w_position == 'inline' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_recaptcha_position_inline">Inline</label>');
  var input3 = jQuery('<input type="radio" id="edit_for_recaptcha_position_bottomright" name="edit_for_recaptcha_position" value="bottomright" onchange="fm_recaptcha_position(' + i + ', this.value)"' + (w_position == 'bottomright' ? ' checked="checked"' : '') + ' />');
  var label3 = jQuery('<label for="edit_for_recaptcha_position_bottomright">Bottom-Right</label>');
  var input4 = jQuery('<input type="radio" id="edit_for_recaptcha_position_bottomleft" name="edit_for_recaptcha_position" value="bottomleft" onchange="fm_recaptcha_position(' + i + ', this.value)"' + (w_position == 'bottomleft' ? ' checked="checked"' : '') + ' />');
  var label4 = jQuery('<label for="edit_for_recaptcha_position_bottomleft">Bottom-Left</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(jQuery('<br />'));
  input = input.add(input2);
  input = input.add(label2);
  input = input.add(jQuery('<br />'));
  input = input.add(input3);
  input = input.add(label3);
  input = input.add(jQuery('<br />'));
  input = input.add(input4);
  input = input.add(label4);
  return create_option_container(label, input, 'recaptcha_position', w_type == 'invisible');
}

function fm_recaptcha_position(id, value) {
  jQuery('#wd_recaptchaform_id_temp').attr('position', value);
}

function go_to_type_recaptcha(new_id) {
  type_recaptcha(new_id, 'reCAPTCHA', '', 'top', 'yes', 'invisible', 'bottomright', 0.5);
}

function type_recaptcha(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_type, w_position, w_score) {
  jQuery("#element_type").val("type_recaptcha");
  delete_last_child();
  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_recaptcha'));
  edit_main_table.append(create_recaptcha_invisible(i, w_type));
  edit_main_table.append(create_recaptcha_position(i, w_position, w_type));
  edit_main_table.append(create_recaptcha_score(i, w_score, w_type));
  edit_main_table.append(create_keys(i, 'To set up recaptcha keys click here'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  var advanced_options = false;
  if(w_type != 'invisible' && w_type != 'v3') {
    advanced_options = true;
  }
  edit_main_table.append(create_advanced_options_container(advanced_options_container, 'recaptcha_advanced', advanced_options));
  advanced_options_container.append(create_label(i, w_field_label));
  advanced_options_container.append(create_label_position(i, w_field_label_pos));
  advanced_options_container.append(create_hide_label(i, w_hide_label));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));

  // Preview.
  element = 'img';
  type = 'captcha';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_recaptcha");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

	var adding_error = document.createElement('div');
	adding_error.setAttribute("id", "recaptcha_keys_message");
	adding_error.setAttribute("class","error");
	adding_error.innerHTML = form_maker.invisible_recaptcha_error;

  var adding = document.createElement('div');
  adding.setAttribute("id", "wd_recaptchaform_id_temp");
  adding.setAttribute("w_type", w_type);
  adding.setAttribute("position", w_position);

  var adding_text = document.createElement('span');
  adding_text.style.color = "red";
  adding_text.style.fontStyle = "italic";
  adding_text.innerHTML = form_maker.no_preview;

  adding.appendChild(adding_text);

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding);
  div_field.appendChild(adding_error);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);

  jQuery("#main_div").append( form_maker.type_recaptcha_description );
  if (w_field_label_pos == "top") {
    label_top(i);
  }
  if ( jQuery("input[name='edit_for_recaptcha_type']:checked").val() == 'v2' ) {
	jQuery("#recaptcha_keys_message").hide();
  }
}

function create_submit_label(i, w_submit_title) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_title">Submit label</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_title" onKeyUp="change_label(\'' + i + '_element_submitform_id_temp\', this.value)" value="' + w_submit_title + '" />');
  return create_option_container(label, input);
}

function create_display_reset(i, w_act) {
  var label = jQuery('<label class="fm-field-label" for="el_reset_active">Display Reset</label>');
  var input = jQuery('<input type="checkbox" id="el_reset_active" onclick="active_reset(this.checked, ' + i + ')"' + (w_act ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function active_reset(val, id) {
  if (val) {
    document.getElementById(id + '_element_resetform_id_temp').style.display = "inline";
  }
  else {
    document.getElementById(id + '_element_resetform_id_temp').style.display = "none";
  }
}

function create_reset_label(i, w_reset_title) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_title_textarea">Reset label</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_title_textarea" onKeyUp="change_label(\'' + i + '_element_resetform_id_temp\', this.value)" value="' + w_reset_title + '" />');
  return create_option_container(label, input);
}

function go_to_type_submit_reset(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_submit_reset(new_id, 'Submit', 'Reset', '', true, w_attr_name, w_attr_value);
}

function type_submit_reset(i, w_submit_title , w_reset_title , w_class, w_act, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_submit_reset");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_submit_reset'));
  edit_main_table.append(create_submit_label(i, w_submit_title));
  edit_main_table.append(create_display_reset(i, w_act));
  edit_main_table.append(create_reset_label(i, w_reset_title));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_submit_reset'));

  // Preview.
  var br = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_submit_reset");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_submit = document.createElement('button');
  adding_submit.setAttribute("type", 'button');
  adding_submit.setAttribute("class", "button button-secondary button-hero button-submit");
  adding_submit.setAttribute("id", i + "_element_submitform_id_temp");
  adding_submit.setAttribute("value", w_submit_title);
  adding_submit.innerHTML = w_submit_title;
  adding_submit.setAttribute("disabled", "disabled");

  var adding_reset = document.createElement('button');
  adding_reset.setAttribute("type", 'button');
  adding_reset.setAttribute("class", "button button-secondary button-hero button-reset");
  if (!w_act)
    adding_reset.style.display = "none";
  adding_reset.setAttribute("id", i + "_element_resetform_id_temp");
  adding_reset.setAttribute("value", w_reset_title);
  adding_reset.setAttribute("disabled", "disabled");
  adding_reset.innerHTML = w_reset_title;

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = "table-cell";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.style.cssText = 'display:none';
  label.innerHTML = "type_submit_reset_" + i;
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_submit);
  div_element.appendChild(adding_reset);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_submit_description );
  change_class(w_class, i);
  refresh_attr(i, 'type_submit_reset');
}

function create_canvas_width_height(i, w_width, w_height) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Canvas Width / Height (px)</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_canvasform_id_temp\', this.value)" value="' + w_width + '" /> x <input type="text" class="fm-width-40" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_h_style(\'' + i + '_canvasform_id_temp\', this.value)" value="' + w_height + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function type_signature(id, params ) {
  jQuery('#element_type').val(params.field_type);
  delete_last_child();

  var edit_table = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
      edit_table.append(edit_div);

  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
      edit_div.append(edit_main_table);
      edit_main_table.append(create_field_type(params.field_type));
      edit_main_table.append(create_label(id, params.field_label));
      edit_main_table.append(create_label_position(id, params.field_label_pos));
      edit_main_table.append(create_hide_label(id, params.field_label_hide));
      edit_main_table.append(create_required(id, params.required));

  var advanced_options_container = jQuery('<div class="inside"></div>');
      edit_main_table.append(create_advanced_options_container(advanced_options_container));
      // @TODO need reset signature option.
      advanced_options_container.append(create_field_label_size(id, params.field_label_size));
      advanced_options_container.append(create_canvas_width_height(id, params.canvas.width, params.canvas.height));
      advanced_options_container.append(create_class(id, params.class));
      advanced_options_container.append(create_upload_destination(id, params.destination));

  // Preview.
  element = 'signature';
  var br = document.createElement('br');

  var label = document.createElement('span');
      label.setAttribute('id', id + '_element_labelform_id_temp');
      label.innerHTML = params.field_label;
      label.setAttribute('class', 'label');
      label.style.verticalAlign = 'top';

  var required = document.createElement('span');
      required.setAttribute('id', id + '_required_elementform_id_temp');
      required.innerHTML = '';
      required.setAttribute('class', 'required');
      required.style.verticalAlign = 'top';
      if ( params.required == 'yes' ) {
        required.innerHTML = ' *';
      }

  var div_label = document.createElement('div');
      div_label.setAttribute('id', id + '_label_sectionform_id_temp');
      div_label.setAttribute('align', 'left');
      div_label.style.display = ( params.field_label_hide == 'yes' ) ? 'none' : 'table-cell';
      div_label.style.width = params.field_label_size + 'px';
      div_label.style.verticalAlign = 'top';
      div_label.appendChild(label);
      div_label.appendChild(required);

  var div_canvas = document.createElement('canvas');
      div_canvas.setAttribute('id', id + '_canvasform_id_temp');
      div_canvas.setAttribute('class', 'fm-signature');
      //div_canvas.setAttribute('width', params.canvas.width);
      //div_canvas.setAttribute('height', params.canvas.height);
      div_canvas.style.width = params.canvas.width + 'px';
      div_canvas.style.height = params.canvas.height + 'px';
      div_canvas.style.border = '1px solid';

  var adding_type = document.createElement('input');
      adding_type.setAttribute('type', 'hidden');
      adding_type.setAttribute('value', params.field_type);
      adding_type.setAttribute('name', id + '_typeform_id_temp');
      adding_type.setAttribute('id', id + '_typeform_id_temp');

  var adding_option_left_right = document.createElement('input');
      adding_option_left_right.setAttribute('type', 'hidden');
      adding_option_left_right.setAttribute('value', params.field_label_pos);
      adding_option_left_right.setAttribute('name', id + '_option_left_right');
      adding_option_left_right.setAttribute('id', id + '_option_left_right');

  var adding_hide_label = document.createElement('input');
      adding_hide_label.setAttribute('type', 'hidden');
      adding_hide_label.setAttribute('value', params.field_label_hide);
      adding_hide_label.setAttribute('name', id + '_hide_labelform_id_temp');
      adding_hide_label.setAttribute('id', id + '_hide_labelform_id_temp');

  var adding_required = document.createElement('input');
      adding_required.setAttribute('type', 'hidden');
      adding_required.setAttribute('value', params.required);
      adding_required.setAttribute('name', id + '_requiredform_id_temp');
      adding_required.setAttribute('id', id + '_requiredform_id_temp');

  var adding_canvas_width = document.createElement('input');
      adding_canvas_width.setAttribute('type', 'hidden');
      adding_canvas_width.setAttribute('value', params.canvas.width);
      adding_canvas_width.setAttribute('name', id + '_canvas_widthform_id_temp');
      adding_canvas_width.setAttribute('id', id + '_canvas_widthform_id_temp');

  var adding_canvas_height = document.createElement('input');
      adding_canvas_height.setAttribute('type', 'hidden');
      adding_canvas_height.setAttribute('value', params.canvas.height);
      adding_canvas_height.setAttribute('name', id + '_canvas_heightform_id_temp');
      adding_canvas_height.setAttribute('id', id + '_canvas_heightform_id_temp');

  var adding_destination = document.createElement('input');
      adding_destination.setAttribute('type', 'hidden');
      adding_destination.setAttribute('value', params.destination);
      adding_destination.setAttribute('name', id + '_destination');
      adding_destination.setAttribute('id', id + '_destination');

  var div_element = document.createElement('div');
      div_element.setAttribute('align', 'left');
      div_element.style.display = 'table-cell';
      div_element.setAttribute('id', id + '_element_sectionform_id_temp');
      div_element.appendChild(div_canvas);
      div_element.appendChild(adding_type);
      div_element.appendChild(adding_option_left_right);
      div_element.appendChild(adding_hide_label);
      div_element.appendChild(adding_required);
      div_element.appendChild(adding_canvas_width);
      div_element.appendChild(adding_canvas_height);
      div_element.appendChild(adding_destination);

  var div_field = document.createElement('div');
      div_field.setAttribute('id', id + '_elemet_tableform_id_temp');
      div_field.appendChild(div_label);
      div_field.appendChild(div_element);

  var div = document.createElement('div');
      div.setAttribute('id', 'main_div');
      div.appendChild(div_field);
      div.appendChild(br);

  var main_div = document.getElementById('show_table');
      main_div.appendChild(div);

  if ( params.field_label_pos == 'top' ) {
    label_top( id );
  }
  change_class( params.class, id );

  (function(window) {
    var $canvas,
      onResize = function( obj ) {
        $canvas.attr({
          width: obj.width, //window.innerWidth
          height: obj.height // window.innerHeight,
        });
      };

    jQuery(document).ready(function() {
      $canvas = jQuery('#'+ id + '_canvasform_id_temp');
      window.addEventListener('orientationchange', onResize, false);
      window.addEventListener('resize', onResize, false);
      onResize( params.canvas );

      jQuery('#' + id + '_element_sectionform_id_temp').signaturePad({
        drawOnly: true,
        defaultAction: 'drawIt',
        validateFields: false,
        lineWidth: 0,
        output: null,
        sigNav: null,
        name: null,
        typed: null,
        //clear: 'input[type=reset]',
        typeIt: null,
        drawIt: null,
        typeItDesc: null,
        drawItDesc: null
      });
    });
  }(this));
}

function type_editor(i, w_editor) {
  jQuery("#element_type").val("type_editor");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_editor'));

  iReturnLeft = jQuery('#edit_table').offset().left;

  document.getElementById('main_editor').style.display = "block";
  document.getElementById('main_editor').style.left = iReturnLeft + 15 + "px";
  document.getElementById('main_editor').style.top = "120px";
  document.getElementById('main_editor').style.width = jQuery('#edit_table').width() - 30 + "px";

  if (document.getElementById("form_maker_editor_ifr") && document.getElementById('form_maker_editor').style.display == "none") {
    ifr_id = "form_maker_editor_ifr";
    ifr = getIFrameDocument(ifr_id);
    ifr.body.innerHTML = w_editor;
  }
  else {
    document.getElementById('form_maker_editor').value = w_editor;
  }

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");
  var main_td = document.getElementById('show_table');
  main_td.appendChild(div);

  var div = document.createElement('div');
  div.style.width = "500px";
  document.getElementById('edit_table').appendChild(div);
}

function type_section_break(i, w_editor) {
  jQuery("#element_type").val("type_section_break");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_section_break'));

  iReturnLeft = jQuery('#edit_table').offset().left;

  document.getElementById('main_editor').style.display = "block";
  document.getElementById('main_editor').style.left = iReturnLeft + 15 + "px";
  document.getElementById('main_editor').style.top = "120px";
  document.getElementById('main_editor').style.width = jQuery('#edit_table').width() - 30 + "px";

  if (document.getElementById("form_maker_editor_ifr") && document.getElementById('form_maker_editor').style.display == "none") {
    ifr_id = "form_maker_editor_ifr";
    ifr = getIFrameDocument(ifr_id);
    ifr.body.innerHTML = w_editor;
  }
  else {
    document.getElementById('form_maker_editor').value = w_editor;
  }
  element = 'div';
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");
  var main_td = document.getElementById('show_table');
  main_td.appendChild(div);

  var div = document.createElement('div');
  div.style.width = "500px";
  document.getElementById('edit_table').appendChild(div);
}

function create_page_title(i, w_page_title) {
  var label = jQuery('<label class="fm-field-label" for="el_page_title_input">Page Title</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_page_title_input" name="el_page_title_input" onKeyup="pagebreak_title_change(this.value,' + i + ')" value="' + w_page_title + '" />');
  return create_option_container(label, input);
}

function pagebreak_title_change(val) {
  val = val.replace(/(<([^>]+)>)/ig, "");
  document.getElementById("_div_between").setAttribute('page_title', val);
  document.getElementById("div_page_title").innerHTML = val + '<br/><br/>';
}

function create_next_type(i, w_type) {
  var label = jQuery('<label class="fm-field-label">Next Type</label>');
  var input1 = jQuery('<input type="radio" id="el_type_next_text" name="el_type_next" value="text" onclick="pagebreak_type_change(\'next\',\'text\')"' + (w_type[0] == 'text' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_type_next_text">Text</label>');
  var input2 = jQuery('<input type="radio" id="el_type_next_img" name="el_type_next" value="img" onclick="pagebreak_type_change(\'next\',\'img\')"' + (w_type[0] == 'text' ? '' : ' checked="checked"') + ' />');
  var label2 = jQuery('<label for="el_type_next_img">Image</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function pagebreak_type_change( pagebreak_type, button_type) {
  document.getElementById("_div_between").setAttribute(pagebreak_type + '_type', button_type);
  switch (button_type) {
    case 'button': {
      document.getElementById("_div_between").setAttribute(pagebreak_type + '_title', pagebreak_type);

      var el_title_label = document.createElement('label');
      el_title_label.setAttribute("class", "fm-field-label");
      el_title_label.setAttribute("for", "el_title_" + pagebreak_type);
      el_title_label.setAttribute('id', pagebreak_type + "_label");
      el_title_label.setAttribute('type', "button");
      el_title_label.innerHTML = pagebreak_type[0].toUpperCase() + pagebreak_type.slice(1) + " " + button_type + " name";

      document.getElementById(pagebreak_type + "_label").parentNode.replaceChild(el_title_label, document.getElementById(pagebreak_type + "_label"));

      document.getElementById("el_title_" + pagebreak_type).value = pagebreak_type;

      var element = document.createElement('button');
      element.setAttribute('id', "page_" + pagebreak_type + '_0');
      element.setAttribute('class', document.getElementById("_div_between").getAttribute(pagebreak_type + '_class'));
      element.style.cursor = "pointer";
      element.innerHTML = pagebreak_type;

      document.getElementById("_element_section_" + pagebreak_type).replaceChild(element, document.getElementById("page_" + pagebreak_type + '_0'));
      break;
    }
    case 'text': {
      document.getElementById("_div_between").setAttribute(pagebreak_type + '_title', pagebreak_type);

      var el_title_label = document.createElement('label');
      el_title_label.setAttribute("class", "fm-field-label");
      el_title_label.setAttribute("for", "el_title_" + pagebreak_type);
      el_title_label.setAttribute('id', pagebreak_type + "_label");
      el_title_label.innerHTML = pagebreak_type[0].toUpperCase() + pagebreak_type.slice(1) + " " + button_type + " name";

      document.getElementById(pagebreak_type + "_label").parentNode.replaceChild(el_title_label, document.getElementById(pagebreak_type + "_label"));

      document.getElementById("el_title_" + pagebreak_type).value = pagebreak_type[0].toUpperCase() + pagebreak_type.slice(1);

      var element = document.createElement('span');
      element.setAttribute('id', "page_" + pagebreak_type + '_0');
      element.setAttribute('class', document.getElementById("_div_between").getAttribute(pagebreak_type + '_class'));
      element.style.cursor = "pointer";
      element.innerHTML = pagebreak_type[0].toUpperCase() + pagebreak_type.slice(1);

      document.getElementById("_element_section_" + pagebreak_type).replaceChild(element, document.getElementById("page_" + pagebreak_type + '_0'));
      break;
    }
    case 'img': {
      document.getElementById("_div_between").setAttribute(pagebreak_type + '_title', plugin_url + '/images/' + pagebreak_type + '.png');

      var el_title_label = document.createElement('label');
      el_title_label.setAttribute("class", "fm-field-label");
      el_title_label.setAttribute("for", "el_title_" + pagebreak_type);
      el_title_label.setAttribute('id', pagebreak_type + "_label");
      el_title_label.innerHTML = pagebreak_type[0].toUpperCase() + pagebreak_type.slice(1) + " " + button_type + " src";

      document.getElementById(pagebreak_type + "_label").parentNode.replaceChild(el_title_label, document.getElementById(pagebreak_type + "_label"));

      document.getElementById("el_title_" + pagebreak_type).value = plugin_url + '/images/' + pagebreak_type + '.png';

      var element = document.createElement('img');
      element.setAttribute('id', "page_" + pagebreak_type + '_0');
      element.setAttribute('class', document.getElementById("_div_between").getAttribute(pagebreak_type + '_class'));
      element.style.cursor = "pointer";
      element.src = plugin_url + '/images/' + pagebreak_type + '.png';

      document.getElementById("_element_section_" + pagebreak_type).replaceChild(element, document.getElementById("page_" + pagebreak_type + '_0'));
      break;
    }
  }
}

function create_next_text_name(i, w_title) {
  var label = jQuery('<label id="next_label" class="fm-field-label" for="el_title_next">Next text name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_title_next" onKeyup="change_pagebreak_label( this.value, \'next\')" onChange="change_pagebreak_label( this.value, \'next\')" value="' + w_title[0] + '" />');
  return create_option_container(label, input);
}

function change_pagebreak_label(val, type) {
  button_type = document.getElementById("_div_between").getAttribute(type + '_type');
  if (button_type != "img") {
    document.getElementById("page_" + type + '_0').value = val;
    document.getElementById("page_" + type + '_0').innerHTML = val;
  }
  else {
    document.getElementById("page_" + type + '_0').src = val;
  }
  document.getElementById("_div_between").setAttribute(type + '_title', val);
}

function create_check_required_next(i, w_check) {
  var label = jQuery('<label class="fm-field-label" for="el_check_next_input">Check the required fields</label>');
  var input = jQuery('<input type="checkbox" id="el_check_next_input" onclick="set_checkable(\'next\')"' + (w_check[0] == "true" ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_checkable(type) {
  document.getElementById("_div_between").setAttribute(type + '_checkable', document.getElementById("el_check_" + type + "_input").checked);
}

function create_previous_type(i, w_type) {
  var label = jQuery('<label class="fm-field-label">Previous Type</label>');
  var input1 = jQuery('<input type="radio" id="el_type_previous_text" name="el_type_previous" value="text" onclick="pagebreak_type_change(\'previous\',\'text\')"' + (w_type[1] == 'text' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_type_previous_text">Text</label>');
  var input2 = jQuery('<input type="radio" id="el_type_previous_img" name="el_type_previous" value="img" onclick="pagebreak_type_change(\'previous\',\'img\')"' + (w_type[1] == 'text' ? '' : ' checked="checked"') + ' />');
  var label2 = jQuery('<label for="el_type_previous_img">Image</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function create_previous_text_name(i, w_title) {
  var label = jQuery('<label id="previous_label" class="fm-field-label" for="el_title_previous">Previous text name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_title_previous" onKeyup="change_pagebreak_label( this.value, \'previous\')" onChange="change_pagebreak_label( this.value, \'previous\')" value="' + w_title[1] + '" />');
  return create_option_container(label, input);
}

function create_check_required_previous(i, w_check) {
  var label = jQuery('<label class="fm-field-label" for="el_check_previous_input">Check the required fields</label>');
  var input = jQuery('<input type="checkbox" id="el_check_previous_input" onclick="set_checkable(\'previous\')"' + (w_check[1] == "true" ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function create_next_class(i, w_class) {
  var label = jQuery('<label class="fm-field-label" for="next_element_style">Next class</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="next_element_style" onChange="change_pagebreak_class(this.value, \'next\')" value="' + w_class[0] + '" />');
  return create_option_container(label, input);
}

function create_previous_class(i, w_class) {
  var label = jQuery('<label class="fm-field-label" for="previous_element_style">Previous class</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="previous_element_style" onChange="change_pagebreak_class(this.value, \'previous\')" value="' + w_class[1] + '" />');
  return create_option_container(label, input);
}

function change_pagebreak_class(val, type) {
  document.getElementById("page_" + type + '_0').setAttribute('class', val);
  document.getElementById("_div_between").setAttribute(type + '_class', val);
}

function type_page_break(i,w_page_title, w_title , w_type , w_class, w_check, w_attr_name, w_attr_value) {
  //var pos = document.getElementsByName("el_pos");
  //	pos[0].setAttribute("disabled", "disabled");
  //	pos[1].setAttribute("disabled", "disabled");
  //	pos[2].setAttribute("disabled", "disabled");

  //var sel_el_pos = document.getElementById("sel_el_pos");
  //	sel_el_pos.setAttribute("disabled", "disabled");

  jQuery("#element_type").val("type_page_break");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_page_break'));
  edit_main_table.append(create_page_title(i, w_page_title));
  edit_main_table.append(create_next_type(i, w_type));
  edit_main_table.append(create_next_text_name(i, w_title));
  edit_main_table.append(create_check_required_next(i, w_check));
  edit_main_table.append(create_previous_type(i, w_type));
  edit_main_table.append(create_previous_text_name(i, w_title));
  edit_main_table.append(create_check_required_previous(i, w_check));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_next_class(i, w_class));
  advanced_options_container.append(create_previous_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_page_break'));

  // Preview.
  var br = document.createElement('br');
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = "table-cell";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var adding_next = document.createElement('div');
  adding_next.setAttribute("align", "right");
  adding_next.setAttribute("id", "_element_section_next");

  var adding_next_button = make_pagebreak_button('next', w_title[0], w_type[0], w_class[0], 0);

  adding_next.appendChild(adding_next_button);

  var adding_previous = document.createElement('div');
  adding_previous.setAttribute("align", "left");
  adding_previous.setAttribute("id", "_element_section_previous");

  var adding_previous_button = make_pagebreak_button('previous', w_title[1], w_type[1], w_class[1], 0);

  adding_previous.appendChild(adding_previous_button);

  var div_fields = document.createElement('div');
  div_fields.setAttribute("align", "center");
  div_fields.setAttribute("style", "border:2px solid blue;padding:20px; margin:20px");
  div_fields.innerHTM = 'FIELDS';

  var div_page_title = document.createElement('div');
  div_page_title.innerHTML = w_page_title + '<br/><br/>';
  div_page_title.setAttribute("id", "div_page_title");
  div_page_title.setAttribute("align", "center");

  var div_between = document.createElement('div');
  div_between.setAttribute("page_title", w_page_title);
  div_between.setAttribute("next_type", w_type[0]);
  div_between.setAttribute("next_title", w_title[0]);
  div_between.setAttribute("next_class", w_class[0]);
  div_between.setAttribute("next_checkable", w_check[0]);
  div_between.setAttribute("previous_type", w_type[1]);
  div_between.setAttribute("previous_title", w_title[1]);
  div_between.setAttribute("previous_class", w_class[1]);
  div_between.setAttribute("previous_checkable", w_check[1]);
  div_between.setAttribute("align", "center");
  div_between.setAttribute("id", "_div_between");
  div_between.innerHTML = "--------------------------------------<br />P A G E B R E A K<br />--------------------------------------"

  div_element.appendChild(div_page_title);
  div_element.appendChild(div_fields);
  div_element.appendChild(adding_next);
  div_element.appendChild(div_between);
  div_element.appendChild(adding_previous);

  var main_td = document.getElementById('show_table');

  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br);
  main_td.appendChild(div);

  refresh_attr(i, 'type_page_break');
}

function make_pagebreak_button(next_or_previous, title, type, class_, id) {
  switch (type) {
    case 'button': {
      var element = document.createElement('button');
      element.setAttribute('id', "page_" + next_or_previous + "_" + id);
      element.setAttribute('type', "button");
      element.setAttribute('class', class_);
      element.style.cursor = "pointer";
      element.innerHTML = title;
      return element;
    }
    case 'text': {
      var element = document.createElement('span');
      element.setAttribute('id', "page_" + next_or_previous + "_" + id);
      element.setAttribute('class', class_);
      element.style.cursor = "pointer";
      element.innerHTML = title;
      return element;
    }
    case 'img': {
      var element = document.createElement('img');
      element.setAttribute('id', "page_" + next_or_previous + "_" + id);
      element.setAttribute('class', class_);
      element.style.cursor = "pointer";
      element.src = title;
      return element;
    }
  }
}

function create_placeholder_name(i, w_title) {
  var label = jQuery('<label class="fm-field-label" for="el_first_value_input">Placeholder</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="el_first_value_first" onKeyUp="change_input_value(this.value,\'' + i + '_element_firstform_id_temp\')" value="' + w_title[0].replace(/"/g, "&quot;") + '" />-<input type="text" class="fm-width-40" id="el_first_value_last" onKeyUp="change_input_value(this.value,\'' + i + '_element_lastform_id_temp\')" value="' + w_title[1].replace(/"/g, "&quot;") + '" />');
  return create_option_container(label, input);
}

function create_field_size_name(i, w_size) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_element_firstform_id_temp\', this.value); change_w_style(\'' + i + '_element_middleform_id_temp\', this.value); change_w_style(\'' + i + '_element_lastform_id_temp\', this.value)" value="' + w_size + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function create_enable_name_fields(i, w_name_fields, w_mini_labels) {
  var label = jQuery('<label class="fm-field-label">Enable Field(s)</label>');
  var input1 = jQuery('<input type="checkbox" id="el_title" value="no" onclick="enable_name_fields(' + i + ',\'title\')"' + (w_name_fields[0] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_title" id="el_title_label">' + w_mini_labels[0] + '</label>');
  var input2 = jQuery('<input type="checkbox" id="el_middle" value="no" onclick="enable_name_fields(' + i + ',\'middle\')"' + (w_name_fields[1] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_middle" id="el_middle_label">' + w_mini_labels[3] + '</label>');
  var label_hidden = jQuery('<label id="el_first_label" class="fm-hide">' + w_mini_labels[1] + '</label><label id="el_last_label" class="fm-hide">' + w_mini_labels[2] + '</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  input = input.add(label_hidden);
  return create_option_container(label, input);
}

function enable_name_fields(id, field) {
  var index = field == 'title' ? 2 : 3;
  tr_name1 = document.getElementById(id + '_tr_name1');
  tr_name2 = document.getElementById(id + '_tr_name2');
  first_input = document.getElementById(id + '_td_name_input_first');
  first_label = document.getElementById(id + '_td_name_label_first');

  var input_width = field == 'title' ? '40' : document.getElementById('edit_for_input_size').value;
  if (document.getElementById("el_" + field).checked == true)
    document.getElementById(id + "_enable_fieldsform_id_temp").setAttribute(field, "yes");
  else
    document.getElementById(id + "_enable_fieldsform_id_temp").setAttribute(field, "no");

  if (document.getElementById(id + '_enable_fieldsform_id_temp').getAttribute(field) == 'yes') {
    var name_field_td = document.createElement('div');
    name_field_td.setAttribute("id", id + "_td_name_input_" + field);
    name_field_td.style.cssText = "display:table-cell";

    var name_field = document.createElement('input');
    name_field.setAttribute("type", 'text');
    if (w_title[index] == w_first_val[index]) {
      name_field.setAttribute("value", w_first_val[index]);
      name_field.setAttribute("placeholder", w_title[index]);
    }
    else {
      name_field.setAttribute("value", w_first_val[index]);
    }
    name_field.setAttribute("id", id + "_element_" + field + "form_id_temp");
    name_field.setAttribute("name", id + "_element_" + field + "form_id_temp");
    name_field.setAttribute("value", w_first_val[index]);
    name_field.setAttribute("title", w_title[index]);
    name_field.setAttribute("placeholder", w_title[index]);
    name_field.style.cssText = "margin-right: 10px; width: " + input_width + "px";

    var name_field_label_td = document.createElement('div');
    name_field_label_td.setAttribute("id", id + "_td_name_label_" + field);
    name_field_label_td.style.cssText = "display:table-cell";

    var name_field_label = document.createElement('label');
    name_field_label.setAttribute("class", "mini_label");
    name_field_label.setAttribute("id", id + "_mini_label_" + field);
    name_field_label.innerHTML = document.getElementById('el_' + field + "_label").innerHTML;
    //	w_mini_labels[0] = document.getElementById('el_'+field+"_label").innerHTML;

    name_field_td.appendChild(name_field);
    name_field_label_td.appendChild(name_field_label);
    if (field == 'title') {
      tr_name1.insertBefore(name_field_td, first_input);
      tr_name2.insertBefore(name_field_label_td, first_label);
    }
    else {
      tr_name1.appendChild(name_field_td);
      tr_name2.appendChild(name_field_label_td);
    }
  }
  else {
    if (document.getElementById(id + '_td_name_input_' + field)) {
      tr_name1.removeChild(document.getElementById(id + '_td_name_input_' + field));
      tr_name2.removeChild(document.getElementById(id + '_td_name_label_' + field));
    }
  }

  var gic1 = document.createTextNode("-");
  var gic2 = document.createTextNode("-");

  value_if_empty_width = field == 'title' ? '60' : '95';
  var el_first_value = document.createElement('input');
  el_first_value.setAttribute("id", "el_first_value_" + field);
  el_first_value.setAttribute("type", "text");
  el_first_value.setAttribute("value", w_title[index]);
  el_first_value.style.cssText = "width:" + value_if_empty_width + "px;";
  el_first_value.setAttribute("onKeyUp", "change_input_value(this.value,'" + id + "_element_" + field + "form_id_temp')");

  el_first_value_first = document.getElementById('el_first_value_first');
  parent = el_first_value_first.parentNode;
  if (document.getElementById(id + '_enable_fieldsform_id_temp').getAttribute(field) == 'yes') {
    if (field == 'title') {
      parent.insertBefore(gic1, el_first_value_first);
      parent.insertBefore(el_first_value, gic1);
    }
    else {
      parent.appendChild(gic2);
      parent.appendChild(el_first_value);
    }
  }
  else {
    if (document.getElementById('el_first_value_' + field)) {
      if (field == 'title')
        parent.removeChild(document.getElementById('el_first_value_title').nextSibling);
      else
        parent.removeChild(document.getElementById('el_first_value_middle').previousSibling);
      parent.removeChild(document.getElementById('el_first_value_' + field));
    }
  }

  refresh_attr(id, 'type_name');

  jQuery(function () {
    jQuery("label#" + id + "_mini_label_title").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var title = "<input type='text' class='title' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(title);
        jQuery("input.title").focus();
        jQuery("input.title").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_title").text(value);
          document.getElementById('el_title_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + id + "_mini_label_middle").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var middle = "<input type='text' class='middle' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(middle);
        jQuery("input.middle").focus();
        jQuery("input.middle").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_middle").text(value);
          document.getElementById('el_middle_label').innerHTML = value;
        });
      }
    });
  });
}

function create_autofill_user_name(i, w_autofill) {
  var label = jQuery('<label class="fm-field-label" for="el_autofill">Autofill with user name</label>');
  var input = jQuery('<input type="checkbox" id="el_autofill" onclick="set_autofill(\'' + i + '_autofillform_id_temp\')"' + (w_autofill == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function set_autofill(element) {
  if (document.getElementById(element).value == 'yes') {
    document.getElementById(element).value = 'no';
  }
  else {
    document.getElementById(element).value = 'yes';
  }
}

function go_to_type_name(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_first_val = ['', '', '', ''];
  w_title = ['', '', '', ''];
  w_mini_labels = ['Title', 'First', 'Last', 'Middle'];
  w_name_fields = ['no', 'no'];
  type_name(new_id, 'Name', '', 'top', 'no', w_first_val, w_title, w_mini_labels, '', 'normal', 'no', 'no', '', w_attr_name, w_attr_value, w_name_fields, 'no');
}

function type_name(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_title, w_mini_labels, w_size, w_name_format, w_required, w_unique, w_class, w_attr_name, w_attr_value, w_name_fields, w_autofill) {
  jQuery("#element_type").val("type_name");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_name'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_placeholder_name(i, w_title));
  edit_main_table.append(create_field_size_name(i, w_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_enable_name_fields(i, w_name_fields, w_mini_labels));
  advanced_options_container.append(create_autofill_user_name(i, w_autofill));
  advanced_options_container.append(create_unique_values(i, w_unique));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_name'));

  // Preview.
  var br = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_name");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_autofill = document.createElement("input");
  adding_autofill.setAttribute("type", "hidden");
  adding_autofill.setAttribute("value", w_autofill);
  adding_autofill.setAttribute("name", i + "_autofillform_id_temp");
  adding_autofill.setAttribute("id", i + "_autofillform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_fields = document.createElement("input");
  adding_fields.setAttribute("type", "hidden");
  adding_fields.setAttribute("name", i + "_enable_fieldsform_id_temp");
  adding_fields.setAttribute("id", i + "_enable_fieldsform_id_temp");
  adding_fields.setAttribute("title", w_name_fields[0]);
  adding_fields.setAttribute("first", 'yes');
  adding_fields.setAttribute("last", 'yes');
  adding_fields.setAttribute("middle", w_name_fields[1]);

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");
  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("class", "fm-editable-label");
  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_name = document.createElement('div');
  table_name.style.display = "table";
  table_name.setAttribute("id", i + "_table_name");
  table_name.setAttribute("cellpadding", '0');
  table_name.setAttribute("cellspacing", '0');

  var tr_name1 = document.createElement('div');
  tr_name1.style.display = "table-row";
  tr_name1.setAttribute("id", i + "_tr_name1");

  var tr_name2 = document.createElement('div');
  tr_name2.style.display = "table-row";
  tr_name2.setAttribute("id", i + "_tr_name2");

  var td_name_input1 = document.createElement('div');
  td_name_input1.style.display = "table-cell";
  td_name_input1.setAttribute("id", i + "_td_name_input_first");

  var td_name_input2 = document.createElement('div');
  td_name_input2.style.display = "table-cell";
  td_name_input2.setAttribute("id", i + "_td_name_input_last");

  var td_name_label1 = document.createElement('div');
  td_name_label1.style.display = "table-cell";
  td_name_label1.setAttribute("id", i + "_td_name_label_first");
  td_name_label1.setAttribute("align", "left");

  var td_name_label2 = document.createElement('div');
  td_name_label2.style.display = "table-cell";
  td_name_label2.setAttribute("id", i + "_td_name_label_last");
  td_name_label2.setAttribute("align", "left");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var first = document.createElement('input');
  first.setAttribute("type", 'text');
  first.style.cssText = "margin-right: 10px; width:" + w_size + "px";
  first.setAttribute("id", i + "_element_firstform_id_temp");
  first.setAttribute("name", i + "_element_firstform_id_temp");
  first.setAttribute("value", w_first_val[0]);
  first.setAttribute("title", w_title[0]);
  first.setAttribute("placeholder", w_title[0]);

  var first_label = document.createElement('label');
  first_label.setAttribute("class", "mini_label");
  first_label.setAttribute("id", i + "_mini_label_first");
  first_label.innerHTML = w_mini_labels[1];

  var last = document.createElement('input');
  last.setAttribute("type", 'text');
  last.style.cssText = "margin-right: 10px; width:" + w_size + "px";
  last.setAttribute("id", i + "_element_lastform_id_temp");
  last.setAttribute("name", i + "_element_lastform_id_temp");
  last.setAttribute("value", w_first_val[1]);
  last.setAttribute("title", w_title[1]);
  last.setAttribute("placeholder", w_title[1]);

  var last_label = document.createElement('label');
  last_label.setAttribute("class", "mini_label");
  last_label.setAttribute("id", i + "_mini_label_last");
  last_label.innerHTML = w_mini_labels[2];

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  td_name_input1.appendChild(first);
  td_name_input2.appendChild(last);
  tr_name1.appendChild(td_name_input1);
  tr_name1.appendChild(td_name_input2);

  td_name_label1.appendChild(first_label);
  td_name_label2.appendChild(last_label);
  tr_name2.appendChild(td_name_label1);
  tr_name2.appendChild(td_name_label2);
  table_name.appendChild(tr_name1);
  table_name.appendChild(tr_name2);

  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_unique);
  div_element.appendChild(adding_autofill);
  div_element.appendChild(adding_fields);
  div_element.appendChild(table_name);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br);
  div.appendChild(div_for_editable_labels);
  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_name_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  if (w_name_fields[0] == 'yes')
    enable_name_fields(i, 'title');

  if (w_name_fields[1] == 'yes')
    enable_name_fields(i, 'middle');

  jQuery(function () {
    jQuery("label#" + i + "_mini_label_first").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var first = "<input type='text' class='first' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(first);
        jQuery("input.first").focus();
        jQuery("input.first").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_first").text(value);
          document.getElementById('el_first_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + i + "_mini_label_last").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var last = "<input type='text' class='last'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(last);
        jQuery("input.last").focus();
        jQuery("input.last").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_last").text(value);
          document.getElementById('el_last_label').innerHTML = value;
        });
      }
    });
  });

  refresh_attr(i, 'type_name');
}

function create_autofill_user_email(i, w_autofill) {
  var label = jQuery('<label class="fm-field-label" for="el_autofill">Autofill with user email</label>');
  var input = jQuery('<input type="checkbox" id="el_autofill" onclick="set_autofill(\'' + i + '_autofillform_id_temp\')"' + (w_autofill == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function create_confirmation_email(i, w_verification) {
  var label = jQuery('<label class="fm-field-label" for="el_verification_mail">Confirmation Email</label>');
  var input = jQuery('<input type="checkbox" id="el_verification_mail" onclick="verification_mail(' + i + ')"' + (w_verification == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function verification_mail(id) {
  if (document.getElementById("el_verification_mail").checked) {
    document.getElementById('confirm_validation_label').style.display = "block";
    document.getElementById('confirm_validation_empty').style.display = "block";
    document.getElementById(id + "_verification_id_temp").value = "yes";
    document.getElementById(id + "_1_label_sectionform_id_temp").style.display = document.getElementById(id + "_label_sectionform_id_temp").style.display;
    document.getElementById(id + "_1_element_sectionform_id_temp").style.display = document.getElementById(id + "_element_sectionform_id_temp").style.display;
  }
  else {
    document.getElementById('confirm_validation_label').style.display = "none";
    document.getElementById('confirm_validation_empty').style.display = "none";
    document.getElementById(id + "_verification_id_temp").value = "no";
    document.getElementById(id + "_1_label_sectionform_id_temp").style.display = "none";
    document.getElementById(id + "_1_element_sectionform_id_temp").style.display = "none";
  }
}

function create_confirmation_email_label(i, w_verification, w_verification_label) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_label">Confirmation label</label>');
  var input = jQuery('<textarea id="edit_for_label" class="fm-width-100" onKeyUp="change_label(\'' + i + '_element_labelform_id_temp\', this.value, \'' + i + '_1_element_labelform_id_temp\')" rows="4">' + w_verification_label + '</textarea>');
  return create_option_container(label, input, 'confirm_validation_label', w_verification == 'yes');
}

function create_confirmation_email_placeholder(i, w_verification, w_verification_placeholder) {
  var label = jQuery('<label class="fm-field-label" for="el_first_value_verification_input">Confirmation placeholder</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_first_value_verification_input" onKeyUp="change_input_value(this.value,\'' + i + '_1_elementform_id_temp\')" value="' + w_verification_placeholder.replace(/"/g, "&quot;") + '" />');
  return create_option_container(label, input, 'confirm_validation_empty', w_verification == 'yes');
}

function go_to_type_submitter_mail(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_submitter_mail(new_id, 'Email', '', 'top', 'no', '', '', '', 'no', 'no', '', 'no', 'Email confirmation', '', w_attr_name, w_attr_value, 'no');
}

function type_submitter_mail(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_required, w_unique,  w_class, w_verification, w_verification_label, w_verification_placeholder, w_attr_name, w_attr_value, w_autofill) {
  jQuery("#element_type").val("type_submitter_mail");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_submitter_mail'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_placeholder(i, w_title));
  edit_main_table.append(create_field_size(i, w_size, '\'' + i + '_elementform_id_temp\'', '\'' + i + '_1_elementform_id_temp\''));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_confirmation_email(i, w_verification));
  advanced_options_container.append(create_confirmation_email_label(i, w_verification, w_verification_label));
  advanced_options_container.append(create_confirmation_email_placeholder(i, w_verification, w_verification_placeholder));
  advanced_options_container.append(create_autofill_user_email(i, w_autofill));
  advanced_options_container.append(create_unique_values(i, w_unique));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size, '\'' + i + '_label_sectionform_id_temp\'', '\'' + i + '_1_label_sectionform_id_temp\''));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_submitter_mail'));

  // Preview.
  element = 'input';
  type = 'text';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_submitter_mail");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_autofill = document.createElement("input");
  adding_autofill.setAttribute("type", "hidden");
  adding_autofill.setAttribute("value", w_autofill);
  adding_autofill.setAttribute("name", i + "_autofillform_id_temp");
  adding_autofill.setAttribute("id", i + "_autofillform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  var adding_verification = document.createElement("input");
  adding_verification.setAttribute("type", "hidden");
  adding_verification.setAttribute("value", w_verification);
  adding_verification.setAttribute("name", i + "_verification_id_temp");
  adding_verification.setAttribute("id", i + "_verification_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", type);

  if (w_title == w_first_val) {
    adding.style.cssText = "width:" + w_size + "px;";
  }
  else {
    adding.style.cssText = "width:" + w_size + "px;";
  }
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_elementform_id_temp");
  adding.setAttribute("value", w_first_val);
  adding.setAttribute("title", w_title);
  adding.setAttribute("placeholder", w_title);

  var adding_verification_input = document.createElement(element);
  adding_verification_input.setAttribute("type", type);

  adding_verification_input.style.cssText = "width:" + w_size + "px;";

  adding_verification_input.setAttribute("id", i + "_1_elementform_id_temp");
  adding_verification_input.setAttribute("name", i + "_1_elementform_id_temp");
  adding_verification_input.setAttribute("placeholder", w_verification_placeholder);
  adding_verification_input.setAttribute("title", w_verification_placeholder);

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var display_label_div_verification = ((w_hide_label == "yes" || w_verification == "no") ? "none" : "table-cell");
  var div_label_verification = document.createElement('div');
  div_label_verification.setAttribute("align", 'left');
  div_label_verification.style.display = display_label_div_verification;
  div_label_verification.style.width = w_field_label_size + "px";
  div_label_verification.setAttribute("id", i + "_1_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var display_element_verification = (w_verification == "no" ? "none" : "table-cell");
  var div_element_verification = document.createElement("div");
  div_element_verification.setAttribute("align", "left");
  div_element_verification.style.display = display_element_verification;
  div_element_verification.setAttribute("id", i + "_1_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');
  var br5 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var label_verification = document.createElement('span');
  label_verification.setAttribute("id", i + "_1_element_labelform_id_temp");
  label_verification.innerHTML = w_verification_label;
  label_verification.setAttribute("class", "label");
  label_verification.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var required_confirm = document.createElement('span');
  required_confirm.setAttribute("id", i + "_1_required_elementform_id_temp");
  required_confirm.innerHTML = "";
  required_confirm.setAttribute("class", "required");
  required_confirm.style.verticalAlign = "top";
  if (w_required == "yes")
    required_confirm.innerHTML = " *";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  div_label_verification.appendChild(label_verification);
  div_label_verification.appendChild(required_confirm);

  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_unique);
  div_element.appendChild(adding_verification);
  div_element.appendChild(adding_autofill);
  div_element.appendChild(adding);
  div_element_verification.appendChild(adding_verification_input);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div_field.appendChild(br5);
  div_field.appendChild(div_label_verification);
  div_field.appendChild(div_element_verification);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_email_description );
  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function go_to_type_phone_new(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_phone_new(new_id, 'Phone', '', 'top', 'no', '', '', 'us', 'no', 'no', '', w_attr_name, w_attr_value);
}

function type_phone_new(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_top_country, w_required, w_unique, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_phone_new");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_phone_new'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_unique_values(i, w_unique));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_phone_new'));

  // Preview.
  var br = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_phone_new");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_name = document.createElement('div');
  table_name.style.display = "table";
  table_name.setAttribute("id", i + "_table_name");

  var tr_name1 = document.createElement('div');
  tr_name1.style.display = "table-row";
  tr_name1.setAttribute("id", i + "_tr_name1");

  var td_name_input1 = document.createElement('div');
  td_name_input1.style.display = "table-cell";
  td_name_input1.setAttribute("id", i + "_td_name_input_first");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";

  var first = document.createElement('input');
  first.setAttribute("type", 'text');
  first.style.cssText = "width:" + w_size + "px";
  first.setAttribute("id", i + "_elementform_id_temp");
  first.setAttribute("name", i + "_elementform_id_temp");
  first.setAttribute("value", w_first_val);
  first.setAttribute("top-country", w_top_country);
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  td_name_input1.appendChild(first);
  tr_name1.appendChild(td_name_input1);
  table_name.appendChild(tr_name1);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_unique);
  div_element.appendChild(table_name);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br);

  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_phone_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_text');

  var telinput = jQuery('#' + i + '_elementform_id_temp');
  var iti = window.intlTelInput(telinput[0], {
    nationalMode: false,
    formatOnDisplay: true,
    initialCountry: w_top_country,
    utilsScript: form_maker.plugin_url +'/js/intlTelInput-utils.js'
  });
  telinput[0].addEventListener('close:countrydropdown', function() {
    var CountryData = iti.getSelectedCountryData();
    var iso_country = CountryData['iso2'];
    iti.setCountry(iso_country);
    telinput.attr('top-country', iso_country );
  });
}

function create_address_size(i, w_size) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Overall width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_div_address\', this.value)" value="' + w_size + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function create_use_us_states_list(i, w_disabled_fields) {
  var label = jQuery('<label class="fm-field-label" for="el_us_states">Use list for US states and Canada provinces</label>');
  var input = jQuery('<input type="checkbox" id="el_us_states" onclick="disable_fields(' + i + ',\'us_states\');"' + (w_disabled_fields[6] == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function disable_fields(id, field) {
  var div = document.getElementById(id + "_div_address");
  if (field) {
    if (document.getElementById("el_" + field).checked == true) {
      document.getElementById(id + "_disable_fieldsform_id_temp").setAttribute(field, "yes");
    }
    else {
      document.getElementById(id + "_disable_fieldsform_id_temp").setAttribute(field, "no");
    }
  }

  if (document.getElementById(id + "_disable_fieldsform_id_temp").getAttribute("state") == 'yes') {
    document.getElementById("el_us_states").disabled = true;
  }
  else {
    document.getElementById("el_us_states").disabled = false;
    if (field == 'us_states') {
      change_state_input(id, 'form_id_temp');
      return;
    }
  }

  div.innerHTML = '';
  var hidden_labels = new Array();
  var address_fields = ['street1', 'street2', 'city', 'state', 'postal', 'country'];
  var left_right = 0;

  for (l = 0; l < 6; l++) {
    if (document.getElementById(id + '_disable_fieldsform_id_temp').getAttribute(address_fields[l]) == 'no') {
      if (address_fields[l] == 'street1' || address_fields[l] == 'street2') {
        var street = document.createElement('input');
        street.setAttribute("type", 'text');
        street.style.cssText = "width:100%";
        street.setAttribute("id", id + "_" + address_fields[l] + "form_id_temp");
        street.setAttribute("name", (parseInt(id) + l) + "_" + address_fields[l] + "form_id_temp");

        var street_label = document.createElement('label');
        street_label.setAttribute("class", "mini_label");
        street_label.setAttribute("id", id + "_mini_label_" + address_fields[l]);
        street_label.style.cssText = "display:block;";
        street_label.innerHTML = document.getElementById('el_' + address_fields[l] + "_label").innerHTML;
        w_mini_labels[l] = document.getElementById('el_' + address_fields[l] + "_label").innerHTML;

        var span_addres = document.createElement('span');
        span_addres.style.cssText = "float:left; width:100%; padding-bottom: 8px; display:block";

        span_addres.appendChild(street);
        span_addres.appendChild(street_label);
        div.appendChild(span_addres);
      }
      else {
        left_right++;
        if (address_fields[l] != 'country') {
          var field = document.createElement('input');
          field.setAttribute("type", 'text');
          field.style.cssText = "width:100%";
          field.setAttribute("id", id + "_" + address_fields[l] + "form_id_temp");
          field.setAttribute("name", (parseInt(id) + l) + "_" + address_fields[l] + "form_id_temp");

          var field_label = document.createElement('label');
          field_label.setAttribute("class", "mini_label");
          field_label.setAttribute("id", id + "_mini_label_" + address_fields[l]);
          field_label.style.cssText = "display:block;";
          field_label.innerHTML = document.getElementById('el_' + address_fields[l] + "_label").innerHTML;
          w_mini_labels[l] = document.getElementById('el_' + address_fields[l] + "_label").innerHTML;
        }
        else {
          var field = document.createElement('select');
          field.setAttribute("type", 'text');
          field.style.cssText = "width:100%";
          field.setAttribute("id", id + "_countryform_id_temp");
          field.setAttribute("name", (parseInt(id) + l) + "_countryform_id_temp");
          field.setAttribute("onChange", "change_state_input('" + id + "', 'form_id_temp')");

          var field_label = document.createElement('label');
          field_label.setAttribute("class", "mini_label");
          field_label.setAttribute("id", id + "_mini_label_country");
          field_label.style.cssText = "display:block;";
          field_label.innerHTML = document.getElementById('el_' + address_fields[l] + "_label").innerHTML;
          w_mini_labels[l] = document.getElementById('el_' + address_fields[l] + "_label").innerHTML;

          countries = form_maker.countries;
          jQuery.each( countries, function( key, value ) {
            var option_ = document.createElement('option');
            option_.setAttribute("value", value);
            option_.innerHTML = value;
            field.appendChild(option_);
          });
        }

        if (left_right % 2 != 0) {
          var span_addres = document.createElement('span');
          span_addres.style.cssText = "float:left; width:48%; padding-bottom: 8px;";
        }
        else {
          var span_addres = document.createElement('span');
          span_addres.style.cssText = "float:right; width:48%; padding-bottom: 8px;";
        }

        span_addres.appendChild(field);
        span_addres.appendChild(field_label);
        div.appendChild(span_addres);
      }
    }
    else {
      var hidden_field = document.createElement('input');
      hidden_field.setAttribute("type", 'hidden');
      hidden_field.setAttribute("id", id + "_" + address_fields[l] + "form_id_temp");
      hidden_field.setAttribute("value", document.getElementById("el_" + address_fields[l] + "_label").innerHTML);
      hidden_field.setAttribute("id_for_label", parseInt(id) + l);

      hidden_labels.push(hidden_field);
    }

    for (k = 0; k < hidden_labels.length; k++) {
      div.appendChild(hidden_labels[k]);
    }
  }

  if (document.getElementById(id + "_disable_fieldsform_id_temp").getAttribute("state") == 'no' && document.getElementById(id + "_disable_fieldsform_id_temp").getAttribute("country") == 'yes') {
    change_state_input(id, 'form_id_temp');
  }

  jQuery(function (jQuery) {
    jQuery("label#" + id + "_mini_label_street1").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var street1 = "<input type='text' class='street1' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(street1);
        jQuery("input.street1").focus();
        jQuery("input.street1").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_street1").text(value);
          document.getElementById('el_street1_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + id + "_mini_label_street2").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var street2 = "<input type='text' class='street2'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(street2);
        jQuery("input.street2").focus();
        jQuery("input.street2").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_street2").text(value);
          document.getElementById('el_street2_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + id + "_mini_label_city").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var city = "<input type='text' class='city'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(city);
        jQuery("input.city").focus();
        jQuery("input.city").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_city").text(value);
          document.getElementById('el_city_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + id + "_mini_label_state").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var state = "<input type='text' class='state'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(state);
        jQuery("input.state").focus();
        jQuery("input.state").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_state").text(value);
          document.getElementById('el_state_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + id + "_mini_label_postal").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var postal = "<input type='text' class='postal'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(postal);
        jQuery("input.postal").focus();
        jQuery("input.postal").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_postal").text(value);
          document.getElementById('el_postal_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + id + "_mini_label_country").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var country = "<input type='text' class='country'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(country);
        jQuery("input.country").focus();
        jQuery("input.country").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_country").text(value);
          document.getElementById('el_country_label').innerHTML = value;
        });
      }
    });
  });
  refresh_attr(id, 'type_address');
}

function change_state_input(id, form_id) {
  if ( document.getElementById(id + "_country" + form_id)
    && document.getElementById(id + "_state" + form_id)
    && !document.getElementById("el_state").checked
    && !document.getElementById("el_us_states").disabled ) {
    var flag = false;
    var state_input = document.getElementById(id + "_state" + form_id);
    if ( (document.getElementById(id + "_country" + form_id).value == "United States")
      && document.getElementById("el_us_states").checked ) {
      var state = document.createElement('select');
      var states = form_maker.states;
      for (var r in states) {
        var option_ = document.createElement('option');
        option_.setAttribute("value", r);
        option_.innerHTML = states[r];
        state.appendChild(option_);
      }
      flag = true;
    } 
    else if ( (document.getElementById(id + "_country" + form_id).value == "Canada")
      && document.getElementById("el_us_states").checked ) {
      var state = document.createElement('select');
      var states = form_maker.provinces;
      for (var r in states) {
        var option_ = document.createElement('option');
        option_.setAttribute("value", r);
        option_.innerHTML = states[r];
        state.appendChild(option_);
      }
      flag = true;
    }
    else {
      if ( document.getElementById(id + "_state" + form_id).tagName == 'SELECT' ) {
        var state = document.createElement('input');
        flag = true;
      }
    }
    if ( flag ) {
      state.setAttribute("type", 'text');
      state.style.cssText = "width: 100%";
      state.setAttribute("id", id + "_state" + form_id);
      state.setAttribute("name", (parseInt(id) + 3) + "_state" + form_id);
      var state_input_parent = state_input.parentNode;
      state_input_parent.removeChild(state_input);
      state_input_parent.insertBefore(state, state_input_parent.firstChild);
    }
  }
}

function create_disable_address_fields(i, w_disabled_fields, w_mini_labels) {
  var label = jQuery('<label class="fm-field-label">Disable Field(s)</label>');
  var input1 = jQuery('<input type="checkbox" id="el_street1" value="no" onclick="disable_fields(' + i + ',\'street1\')"' + (w_disabled_fields[0] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_street1" id="el_street1_label">' + w_mini_labels[0] + '</label>');
  var input2 = jQuery('<input type="checkbox" id="el_street2" value="no" onclick="disable_fields(' + i + ',\'street2\')"' + (w_disabled_fields[1] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_street2" id="el_street2_label">' + w_mini_labels[1] + '</label>');
  var input3 = jQuery('<input type="checkbox" id="el_city" value="no" onclick="disable_fields(' + i + ',\'city\')"' + (w_disabled_fields[2] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label3 = jQuery('<label for="el_city" id="el_city_label">' + w_mini_labels[2] + '</label>');
  var input4 = jQuery('<input type="checkbox" id="el_state" value="no" onclick="disable_fields(' + i + ',\'state\')"' + (w_disabled_fields[3] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label4 = jQuery('<label for="el_state" id="el_state_label">' + w_mini_labels[3] + '</label>');
  var input5 = jQuery('<input type="checkbox" id="el_postal" value="no" onclick="disable_fields(' + i + ',\'postal\')"' + (w_disabled_fields[4] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label5 = jQuery('<label for="el_postal" id="el_postal_label">' + w_mini_labels[4] + '</label>');
  var input6 = jQuery('<input type="checkbox" id="el_country" value="no" onclick="disable_fields(' + i + ',\'country\')"' + (w_disabled_fields[5] == 'yes' ? ' checked="checked"' : '') + ' />');
  var label6 = jQuery('<label for="el_country" id="el_country_label">' + w_mini_labels[5] + '</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(jQuery('<br />'));
  input = input.add(input2);
  input = input.add(label2);
  input = input.add(jQuery('<br />'));
  input = input.add(input3);
  input = input.add(label3);
  input = input.add(jQuery('<br />'));
  input = input.add(input4);
  input = input.add(label4);
  input = input.add(jQuery('<br />'));
  input = input.add(input5);
  input = input.add(label5);
  input = input.add(jQuery('<br />'));
  input = input.add(input6);
  input = input.add(label6);
  return create_option_container(label, input);
}

function go_to_type_address(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_mini_labels = ['Street Address', 'Street Address Line 2', 'City', 'State / Province / Region', 'Postal / Zip Code', 'Country',];
  w_disabled_fields = ['no', 'no', 'no', 'no', 'no', 'no', 'yes'];
  type_address(new_id, 'Address', '', 'top', 'no', '', w_mini_labels, w_disabled_fields, 'no', 'wdform_address', w_attr_name, w_attr_value)
}

function type_address(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_mini_labels, w_disabled_fields, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_address");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_address'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_address_size(i, w_size));
  edit_main_table.append(create_use_us_states_list(i, w_disabled_fields));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_disable_address_fields(i, w_disabled_fields, w_mini_labels));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_address'));

  // Preview.
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_address");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_country = document.createElement("input");
  adding_country.setAttribute("type", "hidden");
  adding_country.setAttribute("name", i + "_disable_fieldsform_id_temp");
  adding_country.setAttribute("id", i + "_disable_fieldsform_id_temp");
  adding_country.setAttribute("street1", w_disabled_fields[0]);
  adding_country.setAttribute("street2", w_disabled_fields[1]);
  adding_country.setAttribute("city", w_disabled_fields[2]);
  adding_country.setAttribute("state", w_disabled_fields[3]);
  adding_country.setAttribute("us_states", w_disabled_fields[6]);
  adding_country.setAttribute("postal", w_disabled_fields[4]);
  adding_country.setAttribute("country", w_disabled_fields[5]);

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");
  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("style", "margin-left:4px; color:red; display:inline-block;");
  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.style.verticalAlign = "top";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var div_address = document.createElement('div');
  div_address.setAttribute("id", i + "_div_address");
  div_address.style.cssText = "width:" + w_size + "px";

  var span_addres1 = document.createElement('span');
  span_addres1.style.cssText = "float:left; width:100%;  padding-bottom: 8px; display:block";

  var span_addres2 = document.createElement('span');
  span_addres2.style.cssText = "float:left; width:100%;  padding-bottom: 8px; display:block";

  var span_addres3_1 = document.createElement('span');
  span_addres3_1.style.cssText = "float:left; width:48%; padding-bottom: 8px;";

  var span_addres3_2 = document.createElement('span');
  span_addres3_2.style.cssText = "float:right; width:48%; padding-bottom: 8px;";

  var span_addres4_1 = document.createElement('span');
  span_addres4_1.style.cssText = "float:left; width:48%; padding-bottom: 8px;";

  var span_addres4_2 = document.createElement('span');
  span_addres4_2.style.cssText = "float:right; width:48%; padding-bottom: 8px;";

  var br = document.createElement('br');
  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "wd_form_label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var street1 = document.createElement('input');
  street1.setAttribute("type", 'text');
  street1.style.cssText = "width:100%";
  street1.setAttribute("id", i + "_street1form_id_temp");
  street1.setAttribute("name", i + "_street1form_id_temp");

  var street1_label = document.createElement('label');
  street1_label.setAttribute("class", "mini_label");
  street1_label.setAttribute("id", i + "_mini_label_street1");
  street1_label.style.cssText = "display:block;";
  street1_label.innerHTML = w_mini_labels[0];

  var street2 = document.createElement('input');
  street2.setAttribute("type", 'text');
  street2.style.cssText = "width:100%";
  street2.setAttribute("id", i + "_street2form_id_temp");
  street2.setAttribute("name", (parseInt(i) + 1) + "_street2form_id_temp");

  var street2_label = document.createElement('label');
  street2_label.setAttribute("class", "mini_label");
  street2_label.setAttribute("id", i + "_mini_label_street2");
  street2_label.style.cssText = "display:block;";
  street2_label.innerHTML = w_mini_labels[1];

  var city = document.createElement('input');
  city.setAttribute("type", 'text');
  city.style.cssText = "width:100%";
  city.setAttribute("id", i + "_cityform_id_temp");
  city.setAttribute("name", (parseInt(i) + 2) + "_cityform_id_temp");

  var city_label = document.createElement('label');
  city_label.setAttribute("class", "mini_label");
  city_label.setAttribute("id", i + "_mini_label_city");
  city_label.style.cssText = "display:block;";
  city_label.innerHTML = w_mini_labels[2];

  var state = document.createElement('input');
  state.setAttribute("type", 'text');
  state.style.cssText = "width:100%";
  state.setAttribute("id", i + "_stateform_id_temp");
  state.setAttribute("name", (parseInt(i) + 3) + "_stateform_id_temp");

  var state_label = document.createElement('label');
  state_label.setAttribute("class", "mini_label");
  state_label.setAttribute("id", i + "_mini_label_state");
  state_label.style.cssText = "display:block;";
  state_label.innerHTML = w_mini_labels[3];

  var postal = document.createElement('input');
  postal.setAttribute("type", 'text');
  postal.style.cssText = "width:100%";
  postal.setAttribute("id", i + "_postalform_id_temp");
  postal.setAttribute("name", (parseInt(i) + 4) + "_postalform_id_temp");

  var postal_label = document.createElement('label');
  postal_label.setAttribute("class", "mini_label");
  postal_label.setAttribute("id", i + "_mini_label_postal");
  postal_label.style.cssText = "display:block;";
  postal_label.innerHTML = w_mini_labels[4];

  var country = document.createElement('select');
  country.setAttribute("type", 'text');
  country.style.cssText = "width:100%";
  country.setAttribute("id", i + "_countryform_id_temp");
  country.setAttribute("name", (parseInt(i) + 5) + "_countryform_id_temp");
  country.setAttribute("onChange", "change_state_input('" + i + "','form_id_temp')");

  var country_label = document.createElement('label');
  country_label.setAttribute("class", "mini_label");
  country_label.setAttribute("id", i + "_mini_label_country");
  country_label.style.cssText = "display:block;";
  country_label.innerHTML = w_mini_labels[5];

  countries = form_maker.countries;
  jQuery.each( countries, function( key, value ) {
    var option_ = document.createElement('option');
    option_.setAttribute("value", value);
    option_.innerHTML = value;

    country.appendChild(option_);
  });

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  span_addres1.appendChild(street1);
  span_addres1.appendChild(street1_label);

  span_addres2.appendChild(street2);
  span_addres2.appendChild(street2_label);

  span_addres3_1.appendChild(city);
  span_addres3_1.appendChild(city_label);
  span_addres3_2.appendChild(state);
  span_addres3_2.appendChild(state_label);

  span_addres4_1.appendChild(postal);
  span_addres4_1.appendChild(postal_label);
  span_addres4_2.appendChild(country);
  span_addres4_2.appendChild(country_label);

  div_address.appendChild(span_addres1);
  div_address.appendChild(span_addres2);
  div_address.appendChild(span_addres3_1);
  div_address.appendChild(span_addres3_2);
  div_address.appendChild(span_addres4_1);
  div_address.appendChild(span_addres4_2);

  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_country);
  div_element.appendChild(div_address);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br);
  div.appendChild(div_for_editable_labels);
  main_td.appendChild(div);

  jQuery("#main_div").append( '<br><br>'+form_maker.type_address_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_address');

  if (w_disabled_fields[0] == "yes")
    disable_fields(i, 'street1');
  if (w_disabled_fields[1] == "yes")
    disable_fields(i, 'street2');
  if (w_disabled_fields[2] == "yes")
    disable_fields(i, 'city');
  if (w_disabled_fields[3] == "yes")
    disable_fields(i, 'state');
  if (w_disabled_fields[4] == "yes")
    disable_fields(i, 'postal');
  if (w_disabled_fields[5] == "yes")
    disable_fields(i, 'country');
  if (w_disabled_fields[6] == "yes")
    disable_fields(i, 'us_states');

  jQuery(function (jQuery) {
    jQuery("label#" + i + "_mini_label_street1").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var street1 = "<input type='text' class='street1 fm-mini-labe-input' style='outline:none; border:none; background:none; width:130px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(street1);
        jQuery("input.street1").focus();
        jQuery("input.street1").blur(function () {
          var value = jQuery(this).val();
          value = value.replaceAll('"',"'");
          jQuery("#" + i + "_mini_label_street1").text(value);
          document.getElementById('el_street1_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + i + "_mini_label_street2").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var street2 = "<input type='text' class='street2 fm-mini-labe-input'  style='outline:none; border:none; background:none; width:130px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(street2);
        jQuery("input.street2").focus();
        jQuery("input.street2").blur(function () {
          var value = jQuery(this).val();
          value = value.replaceAll('"',"'");
          jQuery("#" + i + "_mini_label_street2").text(value);
          document.getElementById('el_street2_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + i + "_mini_label_city").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var city = "<input type='text' class='city fm-mini-labe-input'  style='outline:none; border:none; background:none; width:130px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(city);
        jQuery("input.city").focus();
        jQuery("input.city").blur(function () {
          var value = jQuery(this).val();
          value = value.replaceAll('"',"'");
          jQuery("#" + i + "_mini_label_city").text(value);
          document.getElementById('el_city_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + i + "_mini_label_state").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var state = "<input type='text' class='state fm-mini-labe-input'  style='outline:none; border:none; background:none; width:130px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(state);
        jQuery("input.state").focus();
        jQuery("input.state").blur(function () {
          var value = jQuery(this).val();
          value = value.replaceAll('"',"'");
          jQuery("#" + i + "_mini_label_state").text(value);
          document.getElementById('el_state_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + i + "_mini_label_postal").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var postal = "<input type='text' class='postal fm-mini-labe-input'  style='outline:none; border:none; background:none; width:130px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(postal);
        jQuery("input.postal").focus();
        jQuery("input.postal").blur(function () {
          var value = jQuery(this).val();
          value = value.replaceAll('"',"'");
          jQuery("#" + i + "_mini_label_postal").text(value);
          document.getElementById('el_postal_label').innerHTML = value;
        });
      }
    });

    jQuery("label#" + i + "_mini_label_country").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var country = "<input type='text' class='country fm-mini-labe-input'  style='outline:none; border:none; background:none; width:130px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(country);
        jQuery("input.country").focus();
        jQuery("input.country").blur(function () {
          var value = jQuery(this).val();
          value = value.replaceAll('"',"'");
          jQuery("#" + i + "_mini_label_country").text(value);
          document.getElementById('el_country_label').innerHTML = value;
        });
      }
    });
  });
}

function create_markmap_address(i, j) {
  var label = jQuery('<label class="fm-field-label" for="addrval' + j + '">Address</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="addrval' + j + '" onChange="changeAddress(' + i + ', ' + j + ')" value="" />');
  return create_option_container(label, input);
}

function create_markmap_longitude(i, w_long, j) {
  var label = jQuery('<label class="fm-field-label" for="longval' + j + '">Longitude</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="longval' + j + '" onkeyup="update_position(' + i + ', ' + j + ')" value="' + w_long + '" />');
  return create_option_container(label, input);
}

function create_markmap_latitude(i, w_lat, j) {
  var label = jQuery('<label class="fm-field-label" for="latval' + j + '">Latitude</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="latval' + j + '" onkeyup="update_position(' + i + ', ' + j + ')" value="' + w_lat + '" />');
  return create_option_container(label, input);
}

function create_markmap_size(i, w_width, w_height) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Size(px)</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_elementform_id_temp\', this.value)" value="' + w_width + '" />x<input type="text" class="fm-width-40" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_h_style(\'' + i + '_elementform_id_temp\', this.value)" value="' + w_height + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function create_markmap_info(i, w_info, j) {
  var label = jQuery('<label class="fm-field-label" for="info' + j + '">Marker Info</label>');
  var input = jQuery('<textarea class="fm-width-100" id="info' + j + '" rows="3" onKeyUp="change_info(this.value,' + i + ', ' + j + ')">' + w_info + '</textarea>');
  return create_option_container(label, input);
}

function go_to_type_mark_map(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_mark_map(new_id, 'Mark your place on map', '', 'top', 'no', '2.294254', '48.858334', "2.294254", "48.858334", "13", "370", "300", 'wdform_map', '', w_attr_name, w_attr_value);
}

function type_mark_map(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_center_x, w_center_y, w_long, w_lat, w_zoom, w_width, w_height, w_class, w_info, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_mark_map");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_mark_map'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(jQuery('<div class="notice notice-info"><p>Drag the marker to change default marker position.</p></div>'));
  edit_main_table.append(create_markmap_address(i, 0));
  edit_main_table.append(create_markmap_longitude(i, w_long, 0));
  edit_main_table.append(create_markmap_latitude(i, w_lat, 0));
  edit_main_table.append(create_markmap_info(i, w_info, 0));
  edit_main_table.append(create_markmap_size(i, w_width, w_height));
  edit_main_table.append(create_keys(i, 'To set up map key click here'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_mark_map'));

  // Preview.
  element = 'div';
  var br = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_mark_map");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding = document.createElement('div');
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("long0", w_long);
  adding.setAttribute("lat0", w_lat);
  adding.setAttribute("zoom", w_zoom);
  adding.style.cssText = "width:" + w_width + "px; height: " + w_height + "px";
  adding.setAttribute("info0", w_info);
  adding.setAttribute("center_x", w_center_x);
  adding.setAttribute("center_y", w_center_y);

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "wd_form_label");
  label.style.verticalAlign = "top";

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.style.verticalAlign = "top";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding);
  div_element.appendChild(adding_hide_label);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_mark_on_map_description );
  if (w_field_label_pos == "top") {
    label_top(i);
  }
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
  if_gmap_init(i);
  add_marker_on_map(i, 0, w_long, w_lat, w_info, true);
}

function create_edit_country_list(i) {
  var label = jQuery('<label class="fm-field-label">Edit country list</label>');
  var input = jQuery('<a href="" onclick="tb_show(\'\', \'admin-ajax.php?action=FormMakerEditCountryinPopup&nonce=' + fm_ajax.ajaxnonce + '&field_id=' + i + '&width=530&height=370&TB_iframe=1\'); return false;" class="thickbox-preview fm-field-recaptcha-label">Edit country list</a>');
  return create_option_container(null, input);
}

function go_to_type_country(new_id) {
  w_countries = form_maker.countries;
  w_attr_name = [];
  w_attr_value = [];
  type_country(new_id, 'Country', '', 'no', w_countries, 'top', '', 'no', 'wdform_select', w_attr_name, w_attr_value);
}

function type_country(i, w_field_label, w_field_label_size, w_hide_label, w_countries, w_field_label_pos, w_size, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_country");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_country'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_edit_country_list(i));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_country'));

  // Preview.
  var br = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_country");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.style.width = w_field_label_size + "px";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table";

  var tr_little1 = document.createElement('div');
  tr_little1.setAttribute("id", i + "_element_tr1");
  tr_little1.style.display = "table-row";

  var tr_little2 = document.createElement('div');
  tr_little2.setAttribute("id", i + "_element_tr2");
  tr_little2.style.display = "table-row";

  var td_little1 = document.createElement('div');
  td_little1.setAttribute("valign", 'top');
  td_little1.setAttribute("id", i + "_td_little1");
  td_little1.style.display = "table-cell";

  var td_little2 = document.createElement('div');
  td_little2.setAttribute("valign", 'top');
  td_little2.setAttribute("id", i + "_td_little2");
  td_little2.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var select_ = document.createElement('select');
  select_.setAttribute("id", i + "_elementform_id_temp");
  select_.setAttribute("name", i + "_elementform_id_temp");
  select_.style.cssText = "width:" + w_size + "px";

  jQuery.each( w_countries, function( key, value ) {
    var option_ = document.createElement('option');
    option_.setAttribute("value", value);
    option_.innerHTML = value;
    select_.appendChild(option_);
  });

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(select_);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_country_list_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function create_datefields_separator(i, w_divider) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_fields_divider">Fields separator</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_fields_divider" onKeyUp="set_divider(' + i + ', this.value)" value="' + w_divider + '" />');
  return create_option_container(label, input);
}

function create_datefield_day_type(i, w_day_type) {
  var label = jQuery('<label class="fm-field-label">Day field type</label>');
  var input1 = jQuery('<input type="radio" id="el_day_field_type_text" name="edit_for_day_field_type" onchange="field_to_text(' + i + ', \'day\')"' + (w_day_type == "SELECT" ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="el_day_field_type_text">Input</label>');
  var input2 = jQuery('<input type="radio" id="el_day_field_type_select" name="edit_for_day_field_type" onchange="field_to_select(' + i + ', \'day\')"' + (w_day_type == "SELECT" ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_day_field_type_select">Select</label>');;
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function create_datefields_day_size(i, w_day_size) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_day_size">Day field width(px)</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_day_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_dayform_id_temp\', this.value)" value="' + w_day_size + '" />');
  return create_option_container(label, input);
}

function create_datefield_month_type(i, w_month_type) {
  var label = jQuery('<label class="fm-field-label">Month field type</label>');
  var input1 = jQuery('<input type="radio" id="el_month_field_type_text" name="edit_for_month_field_type" onchange="field_to_text(' + i + ', \'month\')"' + (w_month_type == "SELECT" ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="el_month_field_type_text">Input</label>');
  var input2 = jQuery('<input type="radio" id="el_month_field_type_select" name="edit_for_month_field_type" onchange="field_to_select(' + i + ', \'month\')"' + (w_month_type == "SELECT" ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_month_field_type_select">Select</label>');;
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function create_datefields_month_size(i, w_month_size) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_month_size">Month field width(px)</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_month_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_monthform_id_temp\', this.value)" value="' + w_month_size + '" />');
  return create_option_container(label, input);
}

function create_datefield_year_type(i, w_year_type) {
  var label = jQuery('<label class="fm-field-label">Year field type</label>');
  var input1 = jQuery('<input type="radio" id="el_year_field_type_text" name="edit_for_year_field_type" onchange="field_to_text(' + i + ', \'year\')"' + (w_year_type == "SELECT" ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="el_year_field_type_text">Input</label>');
  var input2 = jQuery('<input type="radio" id="el_year_field_type_select" name="edit_for_year_field_type" onchange="field_to_select(' + i + ', \'year\')"' + (w_year_type == "SELECT" ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_year_field_type_select">Select</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function create_datefields_year_size(i, w_year_size) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_year_size">Year field width(px)</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_year_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_yearform_id_temp\', this.value)" value="' + w_year_size + '" />');
  return create_option_container(label, input);
}

function create_datefields_year_interval(i, w_from, w_to) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_year_interval_from">Year interval</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="edit_for_year_interval_from" onKeyPress="return check_isnum(event)" onFocusOut="year_interval(' + i + ')" value="' + w_from + '" />-<input type="text" class="fm-width-40" id="edit_for_year_interval_to" onKeyPress="return check_isnum(event)" onFocusOut="year_interval(' + i + ')" value="' + w_to + '" />');
  return create_option_container(label, input);
}

function create_datefields_min_birthdate(i, w_min_day, w_min_month, w_min_year) {
  var current_date = new Date();
  var label = jQuery('<label class="fm-field-label" for="edit_for_min_day">Min value of date</label>');
  var input = jQuery('<input type="number" class="fm-width-30 hide_number_arrow" id="edit_for_min_day" placeholder="Day" min="0" max="31" oninput="validity.valid||(value=\'\')"; onKeyPress="return check_isnum(event)" onKeyUp="min_date_field_birthdate(' + i + ')" value="' + w_min_day + '" /> - <input type="number" class="fm-width-30 hide_number_arrow" id="edit_for_min_month" placeholder="Month" min="0" max="12" oninput="validity.valid||(value=\'\')"; onKeyPress="return check_isnum(event)" onKeyUp="min_date_field_birthdate(' + i + ')" value="' + w_min_month + '" /> - <input type="number" class="fm-width-20 hide_number_arrow" id="edit_for_min_year" placeholder="Year" min="1" oninput="validity.valid||(value=\'\')"; onKeyPress="return check_isnum(event)" onKeyUp="min_date_field_birthdate(' + i + ')" value="' + w_min_year + '" />');
  return create_option_container(label, input);
}

function create_datefields_min_alert(i, w_min_dob_alert, type) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_min_dob_alert">Alert for Min value of date</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="edit_for_min_dob_alert" onChange="refresh_datefields_min_alert(' + i + ',\'' + type + '\')" value="' + w_min_dob_alert.replace(/"/g, "&quot;") + '" />');
  return create_option_container(label, input);
}

function field_to_select(id, type) {
  switch (type) {
    case 'day': {
      w_width = document.getElementById('edit_for_day_size').value != '' ? document.getElementById('edit_for_day_size').value : 30;
      w_day = document.getElementById(id + "_dayform_id_temp").value;
      document.getElementById(id + "_td_date_input1").innerHTML = '';

      var select_day = document.createElement('select');
      select_day.setAttribute("id", id + '_dayform_id_temp');
      select_day.setAttribute("name", id + '_dayform_id_temp');
      select_day.setAttribute("onChange", 'set_select(this)');
      select_day.style.width = w_width + 'px';

      var options = document.createElement('option');
      options.setAttribute("value", '');
      options.innerHTML = '';
      select_day.appendChild(options);

      for (k = 1; k <= 31; k++) {
        if (k < 10)
          k = '0' + k;
        var options = document.createElement('option');
        options.setAttribute("value", k);
        options.innerHTML = k;
        if (k == w_day)
          options.setAttribute("selected", "selected");

        select_day.appendChild(options);

      }

      document.getElementById(id + "_td_date_input1").appendChild(select_day);

      break;
    }
    case 'month': {
      w_width = document.getElementById('edit_for_month_size').value != '' ? document.getElementById('edit_for_month_size').value : 60;
      w_month = document.getElementById(id + "_monthform_id_temp").value;

      document.getElementById(id + "_td_date_input2").innerHTML = '';

      var select_month = document.createElement('select');
      select_month.setAttribute("id", id + '_monthform_id_temp');
      select_month.setAttribute("name", id + '_monthform_id_temp');
      select_month.setAttribute("onChange", 'set_select(this)');
      select_month.style.width = w_width + 'px';

      var options = document.createElement('option');
      options.setAttribute("value", '');
      options.innerHTML = '';
      select_month.appendChild(options);

      var myMonths = new Array("<!--repstart-->January<!--repend-->", "<!--repstart-->February<!--repend-->", "<!--repstart-->March<!--repend-->", "<!--repstart-->April<!--repend-->", "<!--repstart-->May<!--repend-->", "<!--repstart-->June<!--repend-->", "<!--repstart-->July<!--repend-->", "<!--repstart-->August<!--repend-->", "<!--repstart-->September<!--repend-->", "<!--repstart-->October<!--repend-->", "<!--repstart-->November<!--repend-->", "<!--repstart-->December<!--repend-->");
      for (k = 1; k <= 12; k++) {
        if (k < 10)
          k = '0' + k;
        var options = document.createElement('option');
        options.setAttribute("value", k);
        options.innerHTML = myMonths[k - 1];
        if (k == w_month)
          options.setAttribute("selected", "selected");

        select_month.appendChild(options);

      }
      document.getElementById(id + "_td_date_input2").appendChild(select_month);
      break;
    }
    case 'year': {
      w_width = document.getElementById('edit_for_year_size').value != '' ? document.getElementById('edit_for_year_size').value : 60;
      w_year = document.getElementById(id + "_yearform_id_temp").value;

      document.getElementById(id + "_td_date_input3").innerHTML = '';
      var select_year = document.createElement('select');
      select_year.setAttribute("id", id + '_yearform_id_temp');
      select_year.setAttribute("name", id + '_yearform_id_temp');
      select_year.setAttribute("onChange", 'set_select(this)');
      select_year.style.width = w_width + 'px';

      var options = document.createElement('option');
      options.setAttribute("value", '');
      options.innerHTML = '';
      select_year.appendChild(options);

      var current_date = new Date();
      from = parseInt(document.getElementById("edit_for_year_interval_from").value);
      to = document.getElementById("edit_for_year_interval_to").value != '' ? parseInt(document.getElementById("edit_for_year_interval_to").value) : current_date.getFullYear();

      for (k = to; k >= from; k--) {
        var options = document.createElement('option');
        options.setAttribute("value", k);
        options.innerHTML = k;
        if (k == w_year)
          options.setAttribute("selected", "selected");

        select_year.appendChild(options);
      }
      select_year.value = w_year;
      select_year.setAttribute('from', from);
      select_year.setAttribute('to', to);
      document.getElementById(id + "_td_date_input3").appendChild(select_year);
      break;
    }
  }
  refresh_attr(id, 'type_date_fields');
}

function field_to_text(id, type) {
  switch (type) {
    case 'day': {
      w_width = document.getElementById('edit_for_day_size').value != '' ? document.getElementById('edit_for_day_size').value : 30;
      w_day = document.getElementById(id + "_dayform_id_temp").value;
      document.getElementById(id + "_td_date_input1").innerHTML = '';

      var day = document.createElement('input');
      day.setAttribute("type", 'text');
      day.setAttribute("value", w_day);
      //day.setAttribute("class", "time_box");
      day.setAttribute("id", id + "_dayform_id_temp");
      day.setAttribute("name", id + "_dayform_id_temp");
      day.setAttribute("onBlur", "if (this.value=='0') this.value=''; else add_0('" + id + "_dayform_id_temp')");

      day.style.width = w_width + 'px';

      document.getElementById(id + "_td_date_input1").appendChild(day);
      break;
    }
    case 'month': {
      w_width = document.getElementById('edit_for_month_size').value != '' ? document.getElementById('edit_for_month_size').value : 60;
      w_month = document.getElementById(id + "_monthform_id_temp").value;
      document.getElementById(id + "_td_date_input2").innerHTML = '';
      var month = document.createElement('input');
      month.setAttribute("type", 'text');
      month.setAttribute("value", w_month);
      month.setAttribute("id", id + "_monthform_id_temp");
      month.setAttribute("name", id + "_monthform_id_temp");
      month.style.width = w_width + 'px';
      month.setAttribute("onBlur", "if (this.value=='0') this.value=''; else add_0('" + id + "_monthform_id_temp')");

      document.getElementById(id + "_td_date_input2").appendChild(month);
      break;
    }
    case 'year': {
      w_width = document.getElementById('edit_for_year_size').value != '' ? document.getElementById('edit_for_year_size').value : 60;
      w_year = document.getElementById(id + "_yearform_id_temp").value;

      document.getElementById(id + "_td_date_input3").innerHTML = '';

      var current_date = new Date();
      from = parseInt(document.getElementById("edit_for_year_interval_from").value);
      to = document.getElementById("edit_for_year_interval_to").value != '' ? parseInt(document.getElementById("edit_for_year_interval_to").value) : current_date.getFullYear();
      if ((parseInt(w_year) < from) || (parseInt(w_year) > to))
        w_year = '';
      var year = document.createElement('input');
      year.setAttribute("type", 'text');
      year.setAttribute("value", w_year);
      //year.setAttribute("class", "time_box");
      year.setAttribute("id", id + "_yearform_id_temp");
      year.setAttribute("name", id + "_yearform_id_temp");
      year.style.width = w_width + 'px';
      year.setAttribute('from', from);
      year.setAttribute('to', to);
      document.getElementById(id + "_td_date_input3").appendChild(year);
      break;
    }
  }
  refresh_attr(id, 'type_date_fields');
}

function set_divider(id, divider) {
  document.getElementById(id + "_separator1").innerHTML = divider;
  document.getElementById(id + "_separator2").innerHTML = divider;
}

function year_interval(id) {
  var current_date = new Date();
  from = parseInt(document.getElementById("edit_for_year_interval_from").value);
  to = document.getElementById("edit_for_year_interval_to").value != '' ? parseInt(document.getElementById("edit_for_year_interval_to").value) : current_date.getFullYear();
  if (to - from < 0) {
    alert('Invalid interval of years.');
    document.getElementById("edit_for_year_interval_from").value = to;
  }
  else {
    if (document.getElementById(id + "_yearform_id_temp").tagName == 'SELECT')
      field_to_select(id, 'year');
    else
      field_to_text(id, 'year');
  }
}


function min_date_field_birthdate(id) {
  if ( !document.getElementById('edit_for_min_day').value ) {
    document.getElementById('edit_for_min_day').value = "";
  }
  if ( !document.getElementById('edit_for_min_month').value ) {
    document.getElementById('edit_for_min_month').value = "";
  }
  if ( !document.getElementById('edit_for_min_year').value ) {
    document.getElementById('edit_for_min_year').value = "";
  }
  document.getElementById(id + '_min_day_id_temp').value = document.getElementById('edit_for_min_day').value;
  document.getElementById(id + '_min_month_id_temp').value = document.getElementById('edit_for_min_month').value;
  document.getElementById(id + '_min_year_id_temp').value = document.getElementById('edit_for_min_year').value;
  refresh_attr(id, 'type_date_fields');
}

function refresh_datefields_min_alert(num, type) {
  if ( !document.getElementById('edit_for_min_dob_alert').value ) {
    document.getElementById('edit_for_min_dob_alert').value = "Date of birth does not meet specified requirements.";
  }
  document.getElementById(num + '_min_dob_alert_id_temp').value = document.getElementById('edit_for_min_dob_alert').value;
  refresh_attr(num, 'type_' + type);
}

function go_to_type_date_fields(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  var current_date = new Date();
  w_to = current_date.getFullYear();
  type_date_fields(new_id, 'Date of Birth', '', 'top', 'no', '', '', '', 'SELECT', 'SELECT', 'SELECT', 'day', 'month', 'year', '60', '100', '80', 'no', 'wdform_date_fields', '1901', w_to, '', '', '', 'Date of birth does not meet specified requirements.', '&nbsp;/&nbsp;', w_attr_name, w_attr_value);
}

function type_date_fields(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_day, w_month, w_year, w_day_type, w_month_type, w_year_type, w_day_label, w_month_label, w_year_label, w_day_size, w_month_size, w_year_size, w_required, w_class, w_from, w_to, w_min_day, w_min_month, w_min_year, w_min_dob_alert, w_divider, w_attr_name, w_attr_value) {
  current_date = new Date();
  jQuery("#element_type").val("type_date_fields");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_date_fields'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_datefields_separator(i, w_divider));
  advanced_options_container.append(create_datefield_day_type(i, w_day_type));
  advanced_options_container.append(create_datefields_day_size(i, w_day_size));
  advanced_options_container.append(create_datefield_month_type(i, w_month_type));
  advanced_options_container.append(create_datefields_month_size(i, w_month_size));
  advanced_options_container.append(create_datefield_year_type(i, w_year_type));
  advanced_options_container.append(create_datefields_year_size(i, w_year_size));
  advanced_options_container.append(create_datefields_year_interval(i, w_from, w_to));
  advanced_options_container.append(create_datefields_min_birthdate(i, w_min_day, w_min_month, w_min_year));
  advanced_options_container.append(create_datefields_min_alert(i, w_min_dob_alert, 'type_date_fields'));
  advanced_options_container.append(jQuery('<div class="notice notice-info"><p>Leave the second field empty and the current year will be used automatically. For a specific year (other than current) fill out both the start and finish points of the range.</p></div>'));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_date_fields'));

  // Preview.
  var br = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_date_fields");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("style", "margin-left:4px; color:red;");
  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.cssText = 'display:' + display_label_div + '; vertical-align:top; width:' + w_field_label_size + 'px';
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_date = document.createElement('div');
  table_date.setAttribute("id", i + "_table_date");
  table_date.style.display = "table";

  var tr_date1 = document.createElement('div');
  tr_date1.setAttribute("id", i + "_tr_date1");
  tr_date1.style.display = "table-row";

  var tr_date2 = document.createElement('div');
  tr_date2.setAttribute("id", i + "_tr_date2");
  tr_date2.style.display = "table-row";

  var td_date_input1 = document.createElement('div');
  td_date_input1.setAttribute("id", i + "_td_date_input1");
  td_date_input1.style.display = "table-cell";

  var td_date_separator1 = document.createElement('div');
  td_date_separator1.setAttribute("id", i + "_td_date_separator1");
  td_date_separator1.style.display = "table-cell";

  var td_date_input2 = document.createElement('div');
  td_date_input2.setAttribute("id", i + "_td_date_input2");
  td_date_input2.style.display = "table-cell";

  var td_date_separator2 = document.createElement('div');
  td_date_separator2.setAttribute("id", i + "_td_date_separator2");
  td_date_separator2.style.display = "table-cell";

  var td_date_input3 = document.createElement('div');
  td_date_input3.setAttribute("id", i + "_td_date_input3");
  td_date_input3.style.display = "table-cell";

  var td_date_label1 = document.createElement('div');
  td_date_label1.setAttribute("id", i + "_td_date_label1");
  td_date_label1.style.display = "table-cell";

  var td_date_label_empty1 = document.createElement('div');
  td_date_label_empty1.style.display = "table-cell";

  var td_date_label2 = document.createElement('div');
  td_date_label2.setAttribute("id", i + "_td_date_label2");
  td_date_label2.style.display = "table-cell";
  var td_date_label_empty2 = document.createElement('div');
  td_date_label_empty2.style.display = "table-cell";

  var td_date_label3 = document.createElement('div');
  td_date_label3.setAttribute("id", i + "_td_date_label3");
  td_date_label3.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "wd_form_label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var day = document.createElement('input');
  day.setAttribute("type", 'text');
  day.setAttribute("value", w_day);
  day.setAttribute("id", i + "_dayform_id_temp");
  day.setAttribute("name", i + "_dayform_id_temp");
  day.setAttribute("onBlur", "if (this.value=='0') this.value=''; else add_0('" + i + "_dayform_id_temp')");
  day.style.width = w_day_size + 'px';

  var day_label = document.createElement('label');
  day_label.setAttribute("class", "mini_label");
  day_label.setAttribute("id", i + "_day_label");
  day_label.innerHTML = w_day_label;

  var day_ = document.createElement('span');
  day_.setAttribute("id", i + "_separator1");
  day_.setAttribute("class", "wdform_separator");
  day_.innerHTML = w_divider;

  var month = document.createElement('input');
  month.setAttribute("type", 'text');
  month.setAttribute("value", w_month);
  month.setAttribute("id", i + "_monthform_id_temp");
  month.setAttribute("name", i + "_monthform_id_temp");
  month.style.width = w_month_size + 'px';
  month.setAttribute("onBlur", "if (this.value=='0') this.value=''; else add_0('" + i + "_monthform_id_temp')");

  var month_label = document.createElement('label');
  month_label.setAttribute("class", "mini_label");
  month_label.setAttribute("class", "mini_label");
  month_label.setAttribute("id", i + "_month_label");
  month_label.innerHTML = w_month_label;

  var month_ = document.createElement('span');
  month_.setAttribute("id", i + "_separator2");
  month_.setAttribute("class", "wdform_separator");
  month_.innerHTML = w_divider;

  if (w_to == '')
    w_to = current_date.getFullYear();
  var year = document.createElement('input');
  year.setAttribute("type", 'text');
  year.setAttribute("from", w_from);
  year.setAttribute("to", w_to);
  year.setAttribute("value", w_year);
  year.setAttribute("id", i + "_yearform_id_temp");
  year.setAttribute("name", i + "_yearform_id_temp");
  year.style.width = w_year_size + 'px';

  var year_label = document.createElement('label');
  year_label.setAttribute("class", "mini_label");
  year_label.setAttribute("id", i + "_year_label");
  year_label.innerHTML = w_year_label;

  var adding_min_day = document.createElement("input");
  adding_min_day.setAttribute("type", "hidden");
  adding_min_day.setAttribute("value", w_min_day);
  adding_min_day.setAttribute("name", i + "_min_day_id_temp");
  adding_min_day.setAttribute("id", i + "_min_day_id_temp");
  var adding_min_month = document.createElement("input");
  adding_min_month.setAttribute("type", "hidden");
  adding_min_month.setAttribute("value", w_min_month);
  adding_min_month.setAttribute("name", i + "_min_month_id_temp");
  adding_min_month.setAttribute("id", i + "_min_month_id_temp");
  var adding_min_year = document.createElement("input");
  adding_min_year.setAttribute("type", "hidden");
  adding_min_year.setAttribute("value", w_min_year);
  adding_min_year.setAttribute("name", i + "_min_year_id_temp");
  adding_min_year.setAttribute("id", i + "_min_year_id_temp");
  var adding_min_dob_alert = document.createElement("input");
  adding_min_dob_alert.setAttribute("type", "hidden");
  adding_min_dob_alert.setAttribute("value", w_min_dob_alert.replace(/"/g, "&quot;"));
  adding_min_dob_alert.setAttribute("name", i + "_min_dob_alert_id_temp");
  adding_min_dob_alert.setAttribute("id", i + "_min_dob_alert_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  td_date_input1.appendChild(day);
  td_date_separator1.appendChild(day_);
  td_date_input2.appendChild(month);
  td_date_separator2.appendChild(month_);
  td_date_input3.appendChild(year);
  tr_date1.appendChild(td_date_input1);
  tr_date1.appendChild(td_date_separator1);
  tr_date1.appendChild(td_date_input2);
  tr_date1.appendChild(td_date_separator2);
  tr_date1.appendChild(td_date_input3);
  td_date_label1.appendChild(day_label);
  td_date_label2.appendChild(month_label);
  td_date_label3.appendChild(year_label);
  tr_date2.appendChild(td_date_label1);
  tr_date2.appendChild(td_date_label_empty1);
  tr_date2.appendChild(td_date_label2);
  tr_date2.appendChild(td_date_label_empty2);
  tr_date2.appendChild(td_date_label3);
  table_date.appendChild(tr_date1);
  table_date.appendChild(tr_date2);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(table_date);
  div_element.appendChild(adding_min_day);
  div_element.appendChild(adding_min_month);
  div_element.appendChild(adding_min_year);
  div_element.appendChild(adding_min_dob_alert);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br);
  div.appendChild(div_for_editable_labels);
  main_td.appendChild(div);

  jQuery("#main_div").append( '<br>'+form_maker.type_date_of_birth_description );

  if (w_field_label_pos == "top")
    label_top(i);

  if (w_day_type == "SELECT")
    field_to_select(i, 'day');

  if (w_month_type == "SELECT")
    field_to_select(i, 'month');

  if (w_year_type == "SELECT")
    field_to_select(i, 'year');

  change_class(w_class, i);
  refresh_attr(i, 'type_date_fields');

  jQuery(function (jQuery) {
    jQuery("label#" + i + "_day_label").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var day = "<input type='text' class='day' style='outline:none; border:none; background:none; width:100px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(day);
        jQuery("input.day").focus();
        jQuery("input.day").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_day_label").text(value);
        });
      }
    });

    jQuery("label#" + i + "_month_label").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var month = "<input type='text' class='month'  style='outline:none; border:none; background:none; width:100px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(month);
        jQuery("input.month").focus();
        jQuery("input.month").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_month_label").text(value);
        });
      }
    });

    jQuery("label#" + i + "_year_label").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var year = "<input type='text' class='year' size='8' style='outline:none; border:none; background:none; width:100px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(year);
        jQuery("input.year").focus();
        jQuery("input.year").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_year_label").text(value);
        });
      }
    });
  });
}

function create_payment_amount_range(i, w_range_min, w_range_max) {
  var label = jQuery('<label class="fm-field-label">Range</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="el_range_min1" onKeyPress="return check_isnum(event)" onChange="change_input_range_new(\'min\', ' + i + ')" value="' + w_range_min + '" placeholder="Min" />-<input type="text" class="fm-width-40" id="el_range_max1" onKeyPress="return check_isnum(event)" onChange="change_input_range_new(\'max\', ' + i + ')" value="' + w_range_max + '" placeholder="Max" />');
  return create_option_container(label, input);
}

function create_hide_payment_currency(i, w_currency) {
  var label = jQuery('<label class="fm-field-label" for="el_currency">Hide payment currency</label>');
  var input = jQuery('<input type="checkbox" id="el_currency" onchange="hide_currency(this.checked, ' + i + ')"' + (w_currency == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function create_hide_total_currency(i, w_hide_total_currency) {
  var label = jQuery('<label class="fm-field-label" for="el_hide_total_currency">Hide currency</label>');
  var input = jQuery('<input type="checkbox" id="el_hide_total_currency" onchange="hide_total_currency(' + i + ')"' + (w_hide_total_currency == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function hide_total_currency(id) {
  if(jQuery("#" + id + "_hide_totalcurrency_id_temp").val()=="no"){
    jQuery("#" + id + "_hide_totalcurrency_id_temp").val("yes");
    jQuery("#" + id + "toggle_currency").removeClass("wd-inline-block").addClass("wd-hidden");
  }
  else {
    jQuery("#" + id + "_hide_totalcurrency_id_temp").val("no");
    jQuery("#" + id + "toggle_currency").removeClass("wd-hidden").addClass("wd-inline-block");
  }
}

function change_input_range_new(type, id) {
  var s = '';
  if (document.getElementById('el_range_' + type + '1').value != '') {
    s = document.getElementById('el_range_' + type + '1').value;
  }
  document.getElementById(id + '_range_' + type + 'form_id_temp').value = s;
}

function hide_currency(hide, id) {
  if (hide) {
    document.getElementById(id + '_td_name_currency').style.display = "none";
  }
  else {
    document.getElementById(id + '_td_name_currency').style.display = "table-cell";
  }
}

function go_to_type_paypal_price_new(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_paypal_price_new(new_id, 'Amount', '', 'top', 'no', '', '', '', 'no', '', w_attr_name, w_attr_value, '', '', 'no', 'no')
}

function type_paypal_price_new(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_title, w_size, w_required, w_class, w_attr_name, w_attr_value, w_range_min, w_range_max, w_readonly, w_currency) {
  jQuery("#element_type").val("type_paypal_price_new");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_paypal_price_new'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_readonly(i, w_readonly));
  edit_main_table.append(create_field_size(i, w_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_payment_amount_range(i, w_range_min, w_range_max));
  advanced_options_container.append(create_hide_payment_currency(i, w_currency));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_paypal_price_new'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_paypal_price_new");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_readonly = document.createElement("input");
  adding_readonly.setAttribute("type", "hidden");
  adding_readonly.setAttribute("value", w_readonly);
  adding_readonly.setAttribute("name", i + "_readonlyform_id_temp");
  adding_readonly.setAttribute("id", i + "_readonlyform_id_temp");

  var adding_range_min = document.createElement("input");
  adding_range_min.setAttribute("type", "hidden");
  adding_range_min.setAttribute("value", w_range_min);
  adding_range_min.setAttribute("name", i + "_range_minform_id_temp");
  adding_range_min.setAttribute("id", i + "_range_minform_id_temp");

  var adding_range_max = document.createElement("input");
  adding_range_max.setAttribute("type", "hidden");
  adding_range_max.setAttribute("value", w_range_max);
  adding_range_max.setAttribute("name", i + "_range_maxform_id_temp");
  adding_range_max.setAttribute("id", i + "_range_maxform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_price = document.createElement('div');
  table_price.setAttribute("id", i + "_table_price");
  table_price.style.display = "table";

  var tr_price1 = document.createElement('div');
  tr_price1.setAttribute("id", i + "_tr_price1");
  tr_price1.style.display = "table-row";

  var td_name_currency = document.createElement('div');
  td_name_currency.setAttribute("id", i + "_td_name_currency");
  if (w_currency == 'no')
    td_name_currency.style.display = "table-cell";
  else
    td_name_currency.style.display = "none";

  var td_name_dollars = document.createElement('div');
  td_name_dollars.setAttribute("id", i + "_td_name_dollars");
  td_name_dollars.style.display = "table-cell";

  var td_name_label_currency = document.createElement('div');
  td_name_label_currency.style.display = "table-cell";

  var td_name_label_dollars = document.createElement('div');
  td_name_label_dollars.setAttribute("align", "left");
  td_name_label_dollars.style.display = "table-cell";

  var td_name_label_ket = document.createElement('div');
  td_name_label_ket.setAttribute("id", i + "_td_name_label_divider");
  td_name_label_ket.style.display = "table-cell";

  var td_name_label_cents = document.createElement('div');
  td_name_label_cents.setAttribute("align", "left");
  td_name_label_cents.setAttribute("id", i + "_td_name_label_cents");
  td_name_label_cents.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";

  if (w_required == "yes")
    required.innerHTML = " *";

  var currency = document.createElement('span');
  currency.setAttribute("class", 'wdform_colon');
  currency.style.cssText = "font-style:bold; vertical-align:middle";
  currency.innerHTML = "<!--repstart-->&nbsp;$&nbsp;<!--repend-->";

  var currency_label = document.createElement('label');
  currency_label.setAttribute("class", "mini_label");

  var dollars = document.createElement('input');
  dollars.setAttribute("type", 'text');
  dollars.style.cssText = "width:" + w_size + "px";
  dollars.setAttribute("id", i + "_elementform_id_temp");
  dollars.setAttribute("name", i + "_elementform_id_temp");
  dollars.setAttribute("value", w_first_val);
  dollars.setAttribute("title", w_title);
  dollars.setAttribute("placeholder", w_title);
  if (w_readonly == 'yes')
    dollars.setAttribute("readonly", "readonly");
  dollars.setAttribute("onKeyPress", "return check_isnum(event)");

  var dollars_label = document.createElement('label');
  dollars_label.setAttribute("class", "mini_label");
  dollars_label.setAttribute("id", i + "_mini_label_dollars");

  var main_td = document.getElementById('show_table');
  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  td_name_currency.appendChild(currency);
  td_name_dollars.appendChild(dollars);
  tr_price1.appendChild(td_name_currency);
  tr_price1.appendChild(td_name_dollars);
  td_name_label_currency.appendChild(currency_label);
  td_name_label_dollars.appendChild(dollars_label);
  table_price.appendChild(tr_price1);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_readonly);
  div_element.appendChild(adding_range_min);
  div_element.appendChild(adding_range_max);
  div_element.appendChild(table_price);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_price_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function create_paypal_select_options(i, w_choices, w_choices_params, w_choices_price, w_choices_disabled) {
  var label = jQuery('<label class="fm-field-label">Options</label>');
  var button1 = jQuery('<button id="el_choices_add" class="fm-add-option button-secondary" onClick="add_choise_price(\'select\', ' + i + '); return false;" title="Add option"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>Option</button>');
  if ( form_maker.is_demo ) {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="alert(\'This feature is disabled in demo.\')" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  else {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="tb_show(\'\', \'admin-ajax.php?action=select_data_from_db&nonce=' + fm_ajax.ajaxnonce + '&field_id=' + i + '&field_type=paypal_select&width=530&height=370&TB_iframe=1\'); return false;" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  var note = jQuery('<div class="fm-width-100 error">IMPORTANT! Check the "Empty value" checkbox only if you want the option to be considered as empty.</div>');
  var attr_table = jQuery('<div id="choices" class="fm-width-100 ui-sortable"></div>');
  var attr_header = jQuery('<div class="fm-width-100"><div class="fm-header-label fm-width-40">Product name</div><div class="fm-header-label fm-width-20">Price</div><div class="fm-header-label fm-width-20">Empty value</div><div class="fm-header-label fm-width-10">Delete</div><div class="fm-header-label fm-width-10">Move</div></div>');
  attr_table.append(attr_header);

  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var attr = jQuery('<div id="' + j + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice" id="el_option' + j + '" value="' + w_choices[j].replace(/"/g, "&quot;") + '" onKeyUp="change_label_price(\'' + i + '_option' + j + '\', this.value)"' + (w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="text" class="fm-field-choice" id="el_option_price' + j + '" value="' + w_choices_price[j] + '" onKeyPress="return check_isnum_point(event)" onKeyUp="change_value_price(\'' + i + '_option' + j + '\', this.value)"' + (w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="hidden" id="el_option_params' + j + '" value="' + w_choices_params[j] + '" />' +
      '<input type="checkbox" title="Empty value" class="el_option_dis" id="el_option' + j + '_dis" onClick="dis_option_price(' + i + ',' + j + ', this.checked)"' + (w_choices_disabled[j] ? ' checked="checked"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + j + '_remove" onClick="remove_option_price(' + j + ',' + i + ')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);
  }

  var input = label;
  input = input.add(button1);
  input = input.add(button2);
  input = input.add(attr_table);
  input = input.add(note);
  return create_option_container(null, input);
}

function create_payment_quantity(i, w_quantity_value, w_quantity) {
  var label = jQuery('<label class="fm-field-label" for="el_quantity">Quantity property</label>');
  var input = jQuery('<input type="checkbox" id="el_quantity" value="yes" onchange="add_quantity(' + i + ',' + w_quantity_value + ')"' + (w_quantity == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function add_quantity(i, w_quantity_value) {
  div_ = document.getElementById(i + "_divform_id_temp");
  if (form_maker_getElementsByAttribute(div_, "*", "id", i + "_element_quantityform_id_temp") != '') {
    div_.removeChild(document.getElementById(i + "_element_quantity_spanform_id_temp"));
    return;
  }
  select_ = document.createElement('input');
  select_.setAttribute("type", 'number');
  select_.setAttribute("min", '1');
  select_.setAttribute("required", '1');
  select_.setAttribute("value", w_quantity_value);
  select_.setAttribute("id", i + "_element_quantityform_id_temp");
  select_.setAttribute("name", i + "_element_quantityform_id_temp");
  select_.setAttribute("onKeyPress", "return check_isnum(event)");
  select_.setAttribute("onfocusout", "validity.valid||(value=\'1\')");
  select_.style.cssText = "width:50px; margin:2px 0px";

  var select_label = document.createElement('label');
  select_label.innerHTML = "<!--repstart-->Quantity<!--repend-->";
  select_label.style.cssText = "margin-right:5px";
  select_label.setAttribute("class", 'mini_label');
  select_label.setAttribute("id", i + '_element_quantity_label_form_id_temp');

  var span_ = document.createElement('span');
  span_.style.cssText = "margin-right:15px";
  span_.setAttribute("id", i + '_element_quantity_spanform_id_temp');

  span_.appendChild(select_label);
  span_.appendChild(select_);
  if (div_.firstChild)
    div_.insertBefore(span_, div_.firstChild);
  else
    div_.appendChild(span_);
}

function create_payment_property(i) {
  var label = jQuery('<label class="fm-field-label">Product properties</label>');
  var button = jQuery('<a class="thickbox-preview" onClick="tb_show(\'\', \'admin-ajax.php?action=product_option&nonce=' + fm_ajax.ajaxnonce + '&field_id=' + i + '&width=530&height=370&TB_iframe=1\')"><span class="fm-add-attribute dashicons dashicons-plus-alt" title="Add"></span></a>');
  var attr_table = jQuery('<ul id="option_ul" class="fm-width-100"></ul>');

  var input = label;
  input = input.add(button);
  input = input.add(attr_table);
  return create_option_container(null, input);
}

function add_choise_price(type, num) {
  var q = 0;
  if (document.getElementById(num + '_hor')) {
    q = 1;
    flow_ver(num);
  }
  var max_value = 0;
  jQuery('.change_pos').each(function () {
    var value = parseInt(jQuery(this)[0].id);
    max_value = (value > max_value) ? value : max_value;
  });

  max_value = max_value + 1;
  if (type == 'radio' || type == 'checkbox') {
    element = 'input';

    var table = document.getElementById(num + '_table_little');
    var tr = document.createElement('div');
    tr.setAttribute("id", num + "_element_tr" + max_value);
    tr.style.display = "table-row";
    var td = document.createElement('div');
    td.setAttribute("valign", "top");
    td.setAttribute("id", num + "_td_little" + max_value);
    td.setAttribute("idi", max_value);
    td.style.display = "table-cell";

    var adding = document.createElement(element);
    adding.setAttribute("type", type);
    adding.setAttribute("value", "");
    adding.setAttribute("id", num + "_elementform_id_temp" + max_value);
    if (document.getElementById(num + "_option_left_right").value == "right")
      adding.style.cssText = "float: left !important";
    if (type == 'checkbox') {
      adding.setAttribute("onClick", "set_checked('" + num + "','" + max_value + "','form_id_temp')");
      adding.setAttribute("name", num + "_elementform_id_temp" + max_value);
    }

    if (type == 'radio') {
      adding.setAttribute("onClick", "set_default('" + num + "','" + max_value + "','form_id_temp')");
      adding.setAttribute("name", num + "_elementform_id_temp");
    }

    var label_adding = document.createElement('label');
    label_adding.setAttribute("id", num + "_label_element" + max_value);
    label_adding.setAttribute("class", "ch-rad-label");
    label_adding.setAttribute("for", num + "_elementform_id_temp" + max_value);
    if (document.getElementById(num + "_option_left_right").value == "right")
      label_adding.style.cssText = "float: none !important";

    var adding_ch_label = document.createElement('input');
    adding_ch_label.setAttribute("type", "hidden");
    adding_ch_label.setAttribute("id", num + "_elementlabel_form_id_temp" + max_value);
    adding_ch_label.setAttribute("name", num + "_elementform_id_temp" + max_value + "_label");
    adding_ch_label.setAttribute("value", "");

    td.appendChild(adding);
    td.appendChild(label_adding);
    td.appendChild(adding_ch_label);
    tr.appendChild(td);
    table.appendChild(tr);

    var attr_table = jQuery('#choices');
    var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-60">' +
      '<input type="text" class="fm-field-choice" id="el_choices' + max_value + '" value="" onKeyUp="change_label(\'' + num + '_label_element' + max_value + '\', this.value); change_label_1(\'' + num + '_elementlabel_form_id_temp' + max_value + '\', this.value);" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="text" class="fm-field-choice" id="el_option_price' + max_value + '" value="" onKeyPress="return check_isnum_point(event)" onKeyUp="change_value_price(\'' + num + '_elementform_id_temp' + max_value + '\', this.value)" />' +
      '</div>' +
      '<input type="hidden" id="el_option_params' + max_value + '" value="" />' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + max_value + '_remove" onClick="remove_choise_price(' + max_value + ',' + num + ')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);
    refresh_attr(num, 'type_checkbox');
  }
  if (type == 'select') {
    var select_ = document.getElementById(num + '_elementform_id_temp');
    var option = document.createElement('option');
    option.setAttribute("id", num + "_option" + max_value);

    select_.appendChild(option);
    var attr_table = jQuery('#choices');
    var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-40">' +
      '<input type="text" class="fm-field-choice" id="el_option' + max_value + '" value="" onKeyUp="change_label_price(\'' + num + '_option' + max_value + '\', this.value)" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="text" class="fm-field-choice" id="el_option_price' + max_value + '" value="" onKeyPress="return check_isnum_point(event)" onKeyUp="change_value_price(\'' + num + '_option' + max_value + '\', this.value)" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="hidden" id="el_option_params' + max_value + '" value="" />' +
      '<input type="checkbox" title="Empty value" class="el_option_dis" id="el_option' + max_value + '_dis" onClick="dis_option_price(' + num + ',' + max_value + ', this.checked)" />' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + max_value + '_remove" onClick="remove_option_price(' + max_value + ',' + num + ')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);
  }
  if (q == 1) {
    flow_hor(num);
  }
}

function flow_hor(id) {
  tbody = document.getElementById(id + '_table_little');
  td_array = new Array();
  n = tbody.childNodes.length;
  for (k = 0; k < n; k++) {
    td_array[k] = tbody.childNodes[k].childNodes[0];
  }
  for (k = 0; k < n; k++) {
    tbody.removeChild(tbody.childNodes[0]);
  }
  var tr = document.createElement('div');
  tr.style.display = "table-row";
  tr.setAttribute("id", id + "_hor");

  tbody.appendChild(tr);
  for (k = 0; k < n; k++) {
    tr.appendChild(td_array[k]);
  }
}

function flow_ver(id) {
  tbody = document.getElementById(id + '_table_little');
  tr = document.getElementById(id + '_hor');
  td_array = new Array();
  n = tr.childNodes.length;
  for (k = 0; k < n; k++) {
    td_array[k] = tr.childNodes[k];
  }
  tbody.removeChild(tr);
  for (k = 0; k < n; k++) {
    var tr_little = document.createElement('div');
    tr_little.setAttribute("id", id + "_element_tr" + td_array[k].getAttribute("idi"));
    tr_little.style.display = "table-row";
    tr_little.appendChild(td_array[k]);
    tbody.appendChild(tr_little);
  }
}

function change_label_price(id, label) {
  document.getElementById(id).innerHTML = label;
}

function change_value_price(id, label) {
  document.getElementById(id).value = label;
}

function dis_option_price(id, i, value) {
  if (value) {
    document.getElementById(id + '_option' + i).value = '';
  }
  else {
    document.getElementById(id + '_option' + i).value = document.getElementById('el_option_price' + i).value;
  }
}

function remove_option_price(id, num) {
  var select_ = document.getElementById(num + '_elementform_id_temp');
  var option = document.getElementById(num + '_option' + id);
  select_.removeChild(option);
  var choices_td = document.getElementById('choices');
  var div = document.getElementById(id);
  choices_td.removeChild(div);
}

function add_properties(id, w_property, w_property_values) {
  n = w_property.length;
  for (i = 0; i < n; i++) {
    select_ = document.createElement('select');
    select_.setAttribute("id", id + "_propertyform_id_temp" + i);
    select_.setAttribute("name", id + "_propertyform_id_temp" + i);

    select_.style.cssText = "width:auto; margin:2px 0px";

    for (k = 0; k < w_property_values[i].length; k++) {
      var option = document.createElement('option');
      option.setAttribute("id", id + "_" + i + "_option" + k);
      option.setAttribute("value", w_property_values[i][k]);
      option.innerHTML = w_property_values[i][k];
      select_.appendChild(option);
    }

    var select_label = document.createElement('label');
    select_label.innerHTML = w_property[i];
    select_label.style.cssText = "margin-right:5px";
    select_label.setAttribute("class", 'mini_label');
    select_label.setAttribute("id", id + '_property_label_form_id_temp' + i);

    var span_ = document.createElement('span');
    span_.style.cssText = "margin-right:15px";
    span_.setAttribute("id", id + '_property_' + i);

    div_ = document.getElementById(id + "_divform_id_temp");
    span_.appendChild(select_label);
    span_.appendChild(select_);
    div_.appendChild(span_);

    var li_ = document.createElement('li');
    li_.setAttribute("id", 'property_li_' + i);

    var li_label = document.createElement('label');
    li_label.innerHTML = w_property[i];
    li_label.setAttribute("id", 'label_property_' + i);
    li_label.style.cssText = "font-weight:bold; font-size: 13px";

    var li_edit = document.createElement('a');
    li_edit.setAttribute("onclick", "tb_show('', 'admin-ajax.php?action=product_option&nonce=" + fm_ajax.ajaxnonce + "&field_id=" + id + "&property_id=" + i + "&width=530&height=370&TB_iframe=1')");
    li_edit.setAttribute("class", "thickbox-preview");

    var li_edit_img = document.createElement('span');
    li_edit_img.setAttribute("class", 'fm-edit-attribute fm-ico-edit');
    li_edit.appendChild(li_edit_img);

    var li_x = document.createElement('span');
    li_x.setAttribute("class", 'fm-remove-attribute dashicons dashicons-dismiss');
    li_x.setAttribute("onClick", 'remove_property(' + id + ',' + i + ')');

    ul_ = document.getElementById("option_ul");

    li_.appendChild(li_label);
    li_.appendChild(li_edit);
    li_.appendChild(li_x);
    ul_.appendChild(li_);
  }
}

function remove_property(id, i) {
  property_ = document.getElementById(id + '_property_' + i);
  property_.parentNode.removeChild(property_);
  property_li_ = document.getElementById('property_li_' + i);
  property_li_.parentNode.removeChild(property_li_);
}

function go_to_type_paypal_select(new_id) {
  w_choices = ["Select product", "product 1", "product 2"];
  w_choices_price = ["", "100", "200"];
  w_choices_checked = ["1", "0", "0"];
  w_choices_params = ["", "", ""];
  w_choices_disabled = [true, false, false];
  w_attr_name = [];
  w_attr_value = [];
  w_property = [];
  w_property_values = [];
  type_paypal_select(new_id, 'Select Product', '', 'top', 'no', '', w_choices, w_choices_price, w_choices_checked, 'no', 'no', '1', 'wdform_select', w_attr_name, w_attr_value, w_choices_disabled, w_property, w_property_values, w_choices_params);
}

function type_paypal_select(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_choices, w_choices_price, w_choices_checked, w_required, w_quantity, w_quantity_value, w_class, w_attr_name, w_attr_value, w_choices_disabled, w_property, w_property_values, w_choices_params) {
  jQuery("#element_type").val("type_paypal_select");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_paypal_select'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size));
  edit_main_table.append(create_paypal_select_options(i, w_choices, w_choices_params, w_choices_price, w_choices_disabled));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_payment_quantity(i, w_quantity_value, w_quantity));
  advanced_options_container.append(create_payment_property(i));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_paypal_select'));

  // Preview.
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_paypal_select");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");

  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table";

  var tr_little1 = document.createElement('div');
  tr_little1.setAttribute("id", i + "_element_tr1");
  tr_little1.style.display = "table-row";

  var tr_little2 = document.createElement('div');
  tr_little2.setAttribute("id", i + "_element_tr2");
  tr_little2.style.display = "table-row";

  var td_little1 = document.createElement('div');
  td_little1.setAttribute("valign", 'top');
  td_little1.setAttribute("id", i + "_td_little1");
  td_little1.style.display = "table-cell";

  var td_little2 = document.createElement('div');
  td_little2.setAttribute("valign", 'top');
  td_little2.setAttribute("id", i + "_td_little2");
  td_little2.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  var select_ = document.createElement('select');
  select_.setAttribute("id", i + "_elementform_id_temp");
  select_.setAttribute("name", i + "_elementform_id_temp");
  select_.style.cssText = "width:" + w_size + "px";
  select_.setAttribute("onchange", "set_select(this)");
  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var option = document.createElement('option');
    option.setAttribute("id", i + "_option" + j);
    if (w_choices_disabled[j])
      option.value = "";
    else
      option.setAttribute("value", w_choices_price[j]);

    if (w_choices_params[j]) {
      w_params = w_choices_params[j].split("[where_order_by]");
      option.setAttribute("where", w_params[0]);
      w_params = w_params[1].split("[db_info]");
      option.setAttribute("order_by", w_params[0]);
      option.setAttribute("db_info", w_params[1]);
    }

    option.setAttribute("onselect", "set_select('" + i + "_option" + j + "')");
    option.innerHTML = w_choices[j].replace(/"/g, "&quot;");
    if (w_choices_checked[j] == 1)
      option.setAttribute("selected", "selected");
    select_.appendChild(option);
  }

  var div_ = document.createElement('div');
  div_.setAttribute("id", i + "_divform_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(select_);
  div_element.appendChild(div_);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_payment_select_description );
  if (w_field_label_pos == "top")
    label_top(i);

  if (w_quantity == "yes")
    add_quantity(i, w_quantity_value);

  change_class(w_class, i);
  refresh_attr(i, 'type_text');
  add_properties(i, w_property, w_property_values);
  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_paypal_fields(i, 'select');
      }
    });
  });
}

function refresh_paypal_fields(id, type) {
  if (type == 'radio' || type == 'checkbox') {
    var table_little = document.getElementById(id + '_table_little');
    table_little.innerHTML = '';

    jQuery('.change_pos').each(function () {
      var idi = jQuery(this)[0].id;

      var tr_little = document.createElement('div');
      tr_little.setAttribute("id", id + "_element_tr" + idi);
      tr_little.style.display = "table-row";

      var td_little = document.createElement('div');
      td_little.setAttribute("valign", 'top');
      td_little.setAttribute("id", id + "_td_little" + idi);
      td_little.setAttribute("idi", idi);
      td_little.style.display = "table-cell";

      var adding = document.createElement('input');
      adding.setAttribute("type", type);
      adding.setAttribute("id", id + "_elementform_id_temp" + idi);
      adding.setAttribute("value", jQuery(this).find(jQuery("#el_option_price" + idi))[0].value);
      if (type == 'checkbox') {
        adding.setAttribute("onClick", "set_checked('" + id + "','" + idi + "','form_id_temp')");
        adding.setAttribute("name", id + "_elementform_id_temp" + idi);
      }

      if (type == 'radio') {
        adding.setAttribute("onClick", "set_default('" + id + "','" + idi + "','form_id_temp')");
        adding.setAttribute("name", id + "_elementform_id_temp");
      }

      var label_adding = document.createElement('label');
      label_adding.setAttribute("id", id + "_label_element" + idi);
      label_adding.setAttribute("class", "ch-rad-label");
      label_adding.setAttribute("for", id + "_elementform_id_temp" + idi);
      label_adding.innerHTML = jQuery(this).find(jQuery("#el_choices" + idi))[0].value;

      if (jQuery(this).find(jQuery(".el_option_params")).val()) {
        w_params = jQuery(this).find(jQuery(".el_option_params")).val().split("[where_order_by]");
        label_adding.setAttribute("where", w_params[0]);
        w_params = w_params[1].split("[db_info]");
        label_adding.setAttribute("order_by", w_params[0]);
        label_adding.setAttribute("db_info", w_params[1]);
      }

      var adding_ch_label = document.createElement('input');
      adding_ch_label.setAttribute("type", "hidden");
      adding_ch_label.setAttribute("id", id + "_elementlabel_form_id_temp" + idi);
      adding_ch_label.setAttribute("name", id + "_elementform_id_temp" + idi + "_label");
      adding_ch_label.setAttribute("value", jQuery(this).find(jQuery("#el_choices" + idi))[0].value);

      td_little.appendChild(adding);
      td_little.appendChild(label_adding);
      td_little.appendChild(adding_ch_label);
      tr_little.appendChild(td_little);
      table_little.appendChild(tr_little);
    });
    if (document.getElementById('edit_for_flow_horizontal').checked) {
      flow_hor(id);
    }
  }

  if (type == 'select') {
    var select = document.getElementById(id + '_elementform_id_temp');
    select.innerHTML = '';
    jQuery('.change_pos').each(function () {
      var idi = jQuery(this)[0].id;
      var option = document.createElement('option');
      option.setAttribute("id", id + "_option" + idi);
      if (jQuery(this).find(jQuery("input[type='checkbox']")).prop('checked')) {
        option.value = "";
      }
      else {
        option.setAttribute("value", jQuery(this).find(jQuery("#el_option_price" + idi))[0].value);
      }
      if (jQuery(this).find(jQuery(".el_option_params")).val()) {
        w_params = jQuery(this).find(jQuery(".el_option_params")).val().split("[where_order_by]");
        option.setAttribute("where", w_params[0]);
        w_params = w_params[1].split("[db_info]");
        option.setAttribute("order_by", w_params[0]);
        option.setAttribute("db_info", w_params[1]);
      }
      option.setAttribute("onselect", "set_select('" + id + "_option" + idi + "')");
      option.innerHTML = jQuery(this).find(jQuery("#el_option" + idi))[0].value;
      select.appendChild(option);
    });
  }
}

function create_payment_relative_position(i, w_flow, type) {
  var label = jQuery('<label class="fm-field-label">Relative Position</label>');
  var input1 = jQuery('<input type="radio" id="edit_for_flow_vertical" name="edit_for_flow" value="ver" onchange="flow_ver(' + i + ')"' + (w_flow == "hor" ? '' : ' checked="checked"') + ' />');
  var label1 = jQuery('<label for="edit_for_flow_vertical">Vertical</label>');
  var input2 = jQuery('<input type="radio" id="edit_for_flow_horizontal" name="edit_for_flow" value="hor" onchange="flow_hor(' + i + ',)"' + (w_flow == "hor" ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="edit_for_flow_horizontal">Horizontal</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function create_paypal_radio_options(i, w_choices, w_choices_params, w_choices_price, type) {
  var label = jQuery('<label class="fm-field-label">Options</label>');
  var button1 = jQuery('<button id="el_choices_add" class="fm-add-option button-secondary" onClick="add_choise_price(\'' + type + '\', ' + i + '); return false;" title="Add option"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>Option</button>');
  if ( form_maker.is_demo ) {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="alert(\'This feature is disabled in demo.\')" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  else {
    var button2 = jQuery('<button class="fm-add-option button-secondary" onClick="tb_show(\'\', \'admin-ajax.php?action=select_data_from_db&field_id=' + i + '&nonce=' + fm_ajax.ajaxnonce + '&field_type=paypal_' + type + '&width=530&height=370&TB_iframe=1\'); return false;" title="Add options from database"><span class="field-type-button fm-add-attribute dashicons dashicons-plus-alt"></span>From Database</button>');
  }
  var attr_table = jQuery('<div id="choices" class="fm-width-100 ui-sortable"></div>');
  var attr_header = jQuery('<div class="fm-width-100"><div class="fm-header-label fm-width-60">Product name</div><div class="fm-header-label fm-width-20">Price</div><div class="fm-header-label fm-width-10">Delete</div><div class="fm-header-label fm-width-10">Move</div></div>');
  attr_table.append(attr_header);

  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var attr = jQuery('<div id="' + j + '" class="change_pos fm-width-100">' +
      '<div class="fm-table-col fm-width-60">' +
      '<input type="text" class="fm-field-choice" id="el_choices' + j + '" value="' + w_choices[j].replace(/"/g, "&quot;") + '" onKeyUp="change_label(\'' + i + '_label_element' + j + '\', this.value); change_label_1(\'' + i + '_elementlabel_form_id_temp' + j + '\', this.value);"' + (w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<div class="fm-table-col fm-width-20">' +
      '<input type="text" class="fm-field-choice" id="el_option_price' + j + '" value="' + w_choices_price[j] + '" onKeyPress="return check_isnum_point(event)" onKeyUp="change_value_price(\'' + i + '_elementform_id_temp' + j + '\', this.value)"' + (w_choices_params[j] ? ' disabled="disabled"' : '') + ' />' +
      '</div>' +
      '<input type="hidden" id="el_option_params' + j + '" value="' + w_choices_params[j] + '" />' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + j + '_remove" onClick="remove_choise_price(' + j + ',' + i + ')"></span>' +
      '</div>' +
      '<div class="fm-table-col fm-width-10">' +
      '<span class="fm-move-attribute fm-ico-draggable el_choices_sortable"></span>' +
      '</div>' +
      '</div>');
    attr_table.append(attr);
  }

  var input = label;
  input = input.add(button1);
  input = input.add(button2);
  input = input.add(attr_table);
  return create_option_container(null, input);
}

function change_label_1(id, label) {
  document.getElementById(id).value = label;
}

function remove_choise_price(id, num) {
  var q = 0;
  if (document.getElementById(num + '_hor')) {
    q = 1;
    flow_ver(num);
  }
  var table = document.getElementById(num + '_table_little');
  var tr = document.getElementById(num + '_element_tr' + id);
  table.removeChild(tr);
  var choices_td = document.getElementById('choices');
  var div = document.getElementById(id);
  choices_td.removeChild(div);
  if (q == 1) {
    flow_hor(num);
  }
}

function go_to_type_paypal_radio(new_id) {
  w_choices = ["product 1", "product 2"];
  w_choices_price = ["100", "200"];
  w_choices_checked = ["0", "0"];
  w_choices_params = ["", "", ""];
  w_attr_name = [];
  w_attr_value = [];
  w_property = [];
  w_property_values = [];
  type_paypal_radio(new_id, 'Payment Single Choice', '', 'top', 'right', 'no', 'ver', w_choices, w_choices_price, w_choices_checked, 'no', 'no', 'no', '0', '', w_attr_name, w_attr_value, w_property, w_property_values, 'no', '1', w_choices_params);
}

function type_paypal_radio(i, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value,  w_property,  w_property_values, w_quantity, w_quantity_value, w_choices_params) {
  jQuery("#element_type").val("type_paypal_radio");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_paypal_radio'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_payment_relative_position(i, w_flow, 'radio'));
  edit_main_table.append(create_option_label_position(i, w_field_option_pos, 'radio'));
  edit_main_table.append(create_paypal_radio_options(i, w_choices, w_choices_params, w_choices_price, 'radio'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_payment_quantity(i, w_quantity_value, w_quantity));
  advanced_options_container.append(create_payment_property(i));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_paypal_radio'));

  // Preview.
  var br3 = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_paypal_radio");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_randomize = document.createElement("input");
  adding_randomize.setAttribute("type", "hidden");
  adding_randomize.setAttribute("value", w_randomize);
  adding_randomize.setAttribute("name", i + "_randomizeform_id_temp");
  adding_randomize.setAttribute("id", i + "_randomizeform_id_temp");

  var adding_allow_other = document.createElement("input");
  adding_allow_other.setAttribute("type", "hidden");
  adding_allow_other.setAttribute("value", w_allow_other);
  adding_allow_other.setAttribute("name", i + "_allow_otherform_id_temp");
  adding_allow_other.setAttribute("id", i + "_allow_otherform_id_temp");

  var adding_option_left_right = document.createElement("input");
  adding_option_left_right.setAttribute("type", "hidden");
  adding_option_left_right.setAttribute("value", w_field_option_pos);
  adding_option_left_right.setAttribute("id", i + "_option_left_right");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_little_t = document.createElement('div');
  table_little_t.style.display = "table";

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table-row-group";

  table_little_t.appendChild(table_little);

  var tr_little1 = document.createElement('div');
  tr_little1.setAttribute("id", i + "_element_tr1");
  tr_little1.style.display = "table-row";

  var tr_little2 = document.createElement('div');
  tr_little2.setAttribute("id", i + "_element_tr2");
  tr_little2.style.display = "table-row";

  var td_little1 = document.createElement('div');
  td_little1.setAttribute("valign", 'top');
  td_little1.setAttribute("id", i + "_td_little1");
  td_little1.style.display = "table-cell";

  var td_little2 = document.createElement('div');
  td_little2.setAttribute("valign", 'top');
  td_little2.setAttribute("id", i + "_td_little2");
  td_little2.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "wd_form_label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var tr_little = document.createElement('div');
    tr_little.setAttribute("id", i + "_element_tr" + j);
    tr_little.style.display = "table-row";

    var td_little = document.createElement('div');
    td_little.setAttribute("valign", 'top');
    td_little.setAttribute("id", i + "_td_little" + j);
    td_little.setAttribute("idi", j);
    td_little.style.display = "table-cell";

    var adding = document.createElement('input');
    adding.setAttribute("type", 'radio');
    adding.setAttribute("id", i + "_elementform_id_temp" + j);
    adding.setAttribute("name", i + "_elementform_id_temp");
    adding.setAttribute("value", w_choices_price[j]);
    if (w_field_option_pos == "right")
      adding.style.cssText = "float: left !important";
    adding.setAttribute("onclick", "set_default('" + i + "','" + j + "','form_id_temp')");
    if (w_choices_checked[j] == '1')
      adding.setAttribute("checked", "checked");

    var label_adding = document.createElement('label');
    label_adding.setAttribute("id", i + "_label_element" + j);
    label_adding.setAttribute("class", "ch-rad-label");
    label_adding.setAttribute("for", i + "_elementform_id_temp" + j);
    label_adding.innerHTML = w_choices[j].replace(/"/g, "&quot;");
    if (w_field_option_pos == "right")
      label_adding.style.cssText = "float: none !important";
    if (w_choices_params[j]) {
      w_params = w_choices_params[j].split("[where_order_by]");
      label_adding.setAttribute("where", w_params[0]);
      w_params = w_params[1].split("[db_info]");
      label_adding.setAttribute("order_by", w_params[0]);
      label_adding.setAttribute("db_info", w_params[1]);
    }

    var adding_ch_label = document.createElement('input');
    adding_ch_label.setAttribute("type", "hidden");
    adding_ch_label.setAttribute("id", i + "_elementlabel_form_id_temp" + j);
    adding_ch_label.setAttribute("name", i + "_elementform_id_temp" + j + "_label");
    adding_ch_label.setAttribute("value", w_choices[j].replace(/"/g, "&quot;"));

    td_little.appendChild(adding);
    td_little.appendChild(label_adding);
    td_little.appendChild(adding_ch_label);
    tr_little.appendChild(td_little);
    table_little.appendChild(tr_little);
  }

  var div_ = document.createElement('div');
  div_.setAttribute("id", i + "_divform_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_randomize);
  div_element.appendChild(adding_allow_other);
  div_element.appendChild(adding_option_left_right);
  div_element.appendChild(table_little_t);
  div_element.appendChild(div_);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_payment_radio_description );

  if (w_field_label_pos == "top")
    label_top(i);

  if (w_flow == "hor")
    flow_hor(i);

  if (w_quantity == "yes")
    add_quantity(i, w_quantity_value);

  change_class(w_class, i);
  refresh_attr(i, 'type_checkbox');

  add_properties(i, w_property, w_property_values);
  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_paypal_fields(i, 'radio');
      }
    });
  });
}

function go_to_type_paypal_checkbox(new_id) {
  w_choices = ["product 1", "product 2"];
  w_choices_price = ["100", "200"];
  w_choices_checked = ["0", "0"];
  w_choices_params = ["", "", ""];
  w_attr_name = [];
  w_attr_value = [];
  w_property = [];
  w_property_values = [];
  type_paypal_checkbox(new_id, 'Payment Multiple Choice', '', 'top', 'right', 'no', 'ver', w_choices, w_choices_price, w_choices_checked, 'no', 'no', 'no', '0', '', w_attr_name, w_attr_value, w_property, w_property_values, 'no', '1', w_choices_params);
}

function type_paypal_checkbox(i, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other,w_allow_other_num, w_class, w_attr_name, w_attr_value,  w_property,  w_property_values, w_quantity, w_quantity_value, w_choices_params) {
  jQuery("#element_type").val("type_paypal_checkbox");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_paypal_checkbox'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_payment_relative_position(i, w_flow, 'checkbox'));
  edit_main_table.append(create_option_label_position(i, w_field_option_pos, 'checkbox'));
  edit_main_table.append(create_paypal_radio_options(i, w_choices, w_choices_params, w_choices_price, 'checkbox'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_payment_quantity(i, w_quantity_value, w_quantity));
  advanced_options_container.append(create_payment_property(i));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_paypal_checkbox'));

  // Preview.
  element = 'input';
  type = 'checkbox';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_paypal_checkbox");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_randomize = document.createElement("input");
  adding_randomize.setAttribute("type", "hidden");
  adding_randomize.setAttribute("value", w_randomize);
  adding_randomize.setAttribute("name", i + "_randomizeform_id_temp");
  adding_randomize.setAttribute("id", i + "_randomizeform_id_temp");

  var adding_allow_other = document.createElement("input");
  adding_allow_other.setAttribute("type", "hidden");
  adding_allow_other.setAttribute("value", w_allow_other);
  adding_allow_other.setAttribute("name", i + "_allow_otherform_id_temp");
  adding_allow_other.setAttribute("id", i + "_allow_otherform_id_temp");

  var adding_allow_other_id = document.createElement("input");
  adding_allow_other_id.setAttribute("type", "hidden");
  adding_allow_other_id.setAttribute("value", w_allow_other_num);
  adding_allow_other_id.setAttribute("name", i + "_allow_other_numform_id_temp");
  adding_allow_other_id.setAttribute("id", i + "_allow_other_numform_id_temp");
  var adding_option_left_right = document.createElement("input");
  adding_option_left_right.setAttribute("type", "hidden");
  adding_option_left_right.setAttribute("value", w_field_option_pos);
  adding_option_left_right.setAttribute("id", i + "_option_left_right");
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little_t = document.createElement('div');
  table_little_t.style.display = "table";

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table-row-group";
  table_little_t.appendChild(table_little);

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var tr_little = document.createElement('div');
    tr_little.setAttribute("id", i + "_element_tr" + j);
    tr_little.style.display = "table-row";

    var td_little = document.createElement('div');
    td_little.setAttribute("valign", 'top');
    td_little.setAttribute("id", i + "_td_little" + j);
    td_little.setAttribute("idi", j);
    td_little.style.display = "table-cell";

    var adding = document.createElement(element);
    adding.setAttribute("type", type);
    adding.setAttribute("id", i + "_elementform_id_temp" + j);
    adding.setAttribute("name", i + "_elementform_id_temp" + j);
    adding.setAttribute("value", w_choices_price[j]);
    if (w_field_option_pos == "right")
      adding.style.cssText = "float: left !important";
    if (w_allow_other == "yes" && j == w_allow_other_num) {
      adding.setAttribute("other", "1");
      adding.setAttribute("onclick", "if(set_checked('" + i + "','" + j + "','form_id_temp')) show_other_input('" + i + "','form_id_temp');");
    }
    else
      adding.setAttribute("onclick", "set_checked('" + i + "','" + j + "','form_id_temp')");

    if (w_choices_checked[j] == '1')
      adding.setAttribute("checked", "checked");

    var label_adding = document.createElement('label');
    label_adding.setAttribute("id", i + "_label_element" + j);
    label_adding.setAttribute("class", "ch-rad-label");
    label_adding.setAttribute("for", i + "_elementform_id_temp" + j);
    label_adding.innerHTML = w_choices[j].replace(/"/g, "&quot;");
    if (w_field_option_pos == "right")
      label_adding.style.cssText = "float: none !important";
    if (w_choices_params[j]) {
      w_params = w_choices_params[j].split("[where_order_by]");
      label_adding.setAttribute("where", w_params[0]);
      w_params = w_params[1].split("[db_info]");
      label_adding.setAttribute("order_by", w_params[0]);
      label_adding.setAttribute("db_info", w_params[1]);
    }

    var adding_ch_label = document.createElement('input');
    adding_ch_label.setAttribute("type", "hidden");
    adding_ch_label.setAttribute("id", i + "_elementlabel_form_id_temp" + j);
    adding_ch_label.setAttribute("name", i + "_elementform_id_temp" + j + "_label");
    adding_ch_label.setAttribute("value", w_choices[j].replace(/"/g, "&quot;"));

    td_little.appendChild(adding);
    td_little.appendChild(label_adding);
    td_little.appendChild(adding_ch_label);
    tr_little.appendChild(td_little);
    table_little.appendChild(tr_little);
  }

  var div_ = document.createElement('div');
  div_.setAttribute("id", i + "_divform_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_randomize);
  div_element.appendChild(adding_allow_other);
  div_element.appendChild(adding_allow_other_id);
  div_element.appendChild(adding_option_left_right);
  div_element.appendChild(table_little_t);
  div_element.appendChild(div_);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_payment_checkbox_description );

  if (w_field_label_pos == "top")
    label_top(i);

  if (w_flow == "hor")
    flow_hor(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_checkbox');

  if (w_quantity == "yes")
    add_quantity(i, w_quantity_value);

  add_properties(i, w_property, w_property_values);

//	fm_popup();
  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_paypal_fields(i, 'checkbox');
      }
    });
  });
}

function go_to_type_paypal_shipping(new_id) {
  w_choices = ["type 1", "type 2"];
  w_choices_price = ["100", "200"];
  w_choices_checked = ["0", "0"];
  w_choices_params = ["", "", ""];
  w_attr_name = [];
  w_attr_value = [];
  w_property = [];
  w_property_values = [];
  type_paypal_shipping(new_id, 'Shipping', '', 'top', 'right', 'no', 'ver', w_choices, w_choices_price, w_choices_checked, 'no', 'no', 'no', '0', '', w_attr_name, w_attr_value, w_property, w_property_values, w_choices_params);
}

function type_paypal_shipping(i, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value,  w_property,  w_property_values, w_choices_params ) {
  jQuery("#element_type").val("type_paypal_shipping");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_paypal_shipping'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_payment_relative_position(i, w_flow, 'radio'));
  edit_main_table.append(create_option_label_position(i, w_field_option_pos, 'radio'));
  edit_main_table.append(create_paypal_radio_options(i, w_choices, w_choices_params, w_choices_price, 'radio'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_paypal_shipping'));

  // Preview.
  element = 'input';
  type = 'radio';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_paypal_shipping");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_randomize = document.createElement("input");
  adding_randomize.setAttribute("type", "hidden");
  adding_randomize.setAttribute("value", w_randomize);
  adding_randomize.setAttribute("name", i + "_randomizeform_id_temp");
  adding_randomize.setAttribute("id", i + "_randomizeform_id_temp");

  var adding_allow_other = document.createElement("input");
  adding_allow_other.setAttribute("type", "hidden");
  adding_allow_other.setAttribute("value", w_allow_other);
  adding_allow_other.setAttribute("name", i + "_allow_otherform_id_temp");
  adding_allow_other.setAttribute("id", i + "_allow_otherform_id_temp");
  var adding_option_left_right = document.createElement("input");
  adding_option_left_right.setAttribute("type", "hidden");
  adding_option_left_right.setAttribute("value", w_field_option_pos);
  adding_option_left_right.setAttribute("id", i + "_option_left_right");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little_t = document.createElement('div');
  table_little_t.style.display = "table";

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table-row-group";

  table_little_t.appendChild(table_little);

  var tr_little1 = document.createElement('div');
  tr_little1.setAttribute("id", i + "_element_tr1");
  tr_little1.style.display = "table-row";

  var tr_little2 = document.createElement('div');
  tr_little2.setAttribute("id", i + "_element_tr2");
  tr_little2.style.display = "table-row";

  var td_little1 = document.createElement('div');
  td_little1.setAttribute("valign", 'top');
  td_little1.setAttribute("id", i + "_td_little1");
  td_little1.style.display = "table-cell";

  var td_little2 = document.createElement('div');
  td_little2.setAttribute("valign", 'top');
  td_little2.setAttribute("id", i + "_td_little2");
  td_little2.style.display = "table-cell";

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  n = w_choices.length;
  for (j = 0; j < n; j++) {
    var tr_little = document.createElement('div');
    tr_little.setAttribute("id", i + "_element_tr" + j);
    tr_little.style.display = "table-row";

    var td_little = document.createElement('td');
    td_little.setAttribute("valign", 'top');
    td_little.setAttribute("id", i + "_td_little" + j);
    td_little.setAttribute("idi", j);
    td_little.style.display = "table-cell";

    var adding = document.createElement(element);
    adding.setAttribute("type", type);
    adding.setAttribute("id", i + "_elementform_id_temp" + j);
    adding.setAttribute("name", i + "_elementform_id_temp");
    adding.setAttribute("value", w_choices_price[j]);
    if (w_field_option_pos == "right")
      adding.style.cssText = "float: left !important";
    adding.setAttribute("onclick", "set_default('" + i + "','" + j + "','form_id_temp')");
    if (w_choices_checked[j] == '1')
      adding.setAttribute("checked", "checked");

    var label_adding = document.createElement('label');
    label_adding.setAttribute("id", i + "_label_element" + j);
    label_adding.setAttribute("class", "ch-rad-label");
    label_adding.setAttribute("for", i + "_elementform_id_temp" + j);
    label_adding.innerHTML = w_choices[j].replace(/"/g, "&quot;");
    if (w_field_option_pos == "right")
      label_adding.style.cssText = "float: none !important";
    if (w_choices_params[j]) {
      w_params = w_choices_params[j].split("[where_order_by]");
      label_adding.setAttribute("where", w_params[0]);
      w_params = w_params[1].split("[db_info]");
      label_adding.setAttribute("order_by", w_params[0]);
      label_adding.setAttribute("db_info", w_params[1]);
    }
    var adding_ch_label = document.createElement('input');
    adding_ch_label.setAttribute("type", "hidden");
    adding_ch_label.setAttribute("id", i + "_elementlabel_form_id_temp" + j);
    adding_ch_label.setAttribute("name", i + "_elementform_id_temp" + j + "_label");
    adding_ch_label.setAttribute("value", w_choices[j].replace(/"/g, "&quot;"));

    td_little.appendChild(adding);
    td_little.appendChild(label_adding);
    td_little.appendChild(adding_ch_label);
    tr_little.appendChild(td_little);
    table_little.appendChild(tr_little);
  }

  var div_ = document.createElement('div');
  div_.setAttribute("id", i + "_divform_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_randomize);
  div_element.appendChild(adding_allow_other);
  div_element.appendChild(adding_option_left_right);
  div_element.appendChild(table_little_t);
  div_element.appendChild(div_);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_shipping_description );

  if (w_field_label_pos == "top")
    label_top(i);

  if (w_flow == "hor")
    flow_hor(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_checkbox');

  add_properties(i, w_property, w_property_values);

  jQuery(function () {
    jQuery("#choices").sortable({
      items: ".change_pos",
      handle: ".el_choices_sortable",
      update: function (event, ui) {
        refresh_paypal_fields(i, 'radio');
      }
    });
  });
}

function go_to_type_paypal_total(new_id) {
  type_paypal_total(new_id, 'Total', '', 'top', 'no', '', '', 'no');
}

function type_paypal_total(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_class, w_size, w_hide_total_currency ) {
  jQuery("#element_type").val("type_paypal_total");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_paypal_total'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_hide_total_currency(i, w_hide_total_currency));
  advanced_options_container.append(create_class(i, w_class));

  // Preview.
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_paypal_total");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_hide_total_currency = document.createElement("input");
  adding_hide_total_currency.setAttribute("type", "hidden");
  adding_hide_total_currency.setAttribute("value", w_hide_total_currency);
  adding_hide_total_currency.setAttribute("name", i + "_hide_totalcurrency_id_temp");
  adding_hide_total_currency.setAttribute("id", i + "_hide_totalcurrency_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var div_paypal = document.createElement('div');
  div_paypal.setAttribute("id", i + "paypal_totalform_id_temp");
  div_paypal.setAttribute("class", "wdform_paypal_total paypal_totalform_id_temp");
  div_paypal.style.cssText = 'width:' + w_size + 'px;';

  var div_total = document.createElement('div');
  div_total.setAttribute("id", i + "div_totalform_id_temp");
  div_total.setAttribute("class", "div_totalform_id_temp");
  div_total.style.cssText = 'margin-bottom:10px;';
  div_total.innerHTML = '300';

  var display_total_currency = (w_hide_total_currency == "yes" ? "wd-hidden" : "wd-inline-block");
  var span_toggle_currency = document.createElement('span');
  span_toggle_currency.setAttribute("id", i + "toggle_currency");
  span_toggle_currency.setAttribute("class", display_total_currency);
  span_toggle_currency.style.float = "left";
  span_toggle_currency.innerHTML = '$';

  var div_products = document.createElement('div');
  div_products.setAttribute("id", i + "paypal_productsform_id_temp");
  div_products.setAttribute("class", "paypal_productsform_id_temp");
  div_products.style.cssText = 'border-spacing: 2px;';

  var div_product1 = document.createElement('div');
  div_product1.style.cssText = 'border-spacing: 2px;';
  div_product1.innerHTML = '<!--repstart-->product 1 $100<!--repend-->';

  var div_product2 = document.createElement('div');
  div_product2.style.cssText = 'border-spacing: 2px;';
  div_product2.innerHTML = '<!--repstart-->product 2 $200<!--repend-->';

  var div_tax = document.createElement('div');
  div_tax.style.cssText = 'border-spacing: 2px; margin-top:7px;';
  div_tax.setAttribute("id", i + "paypal_taxform_id_temp");
  div_tax.setAttribute("class", "paypal_taxform_id_temp");

  var input_for_total = document.createElement("input");
  input_for_total.setAttribute("type", "hidden");
  input_for_total.setAttribute("value", '');
  input_for_total.setAttribute("name", i + "_paypal_totalform_id_temp");
  input_for_total.setAttribute("class", "input_paypal_totalform_id_temp");

  div_paypal.appendChild(input_for_total);
  div_products.appendChild(div_product1);
  div_products.appendChild(div_product2);
  div_paypal.appendChild(div_total);
  div_paypal.appendChild(div_products);
  div_paypal.appendChild(div_tax);
  div_total.appendChild(span_toggle_currency);

  var main_td = document.getElementById('show_table');

  var p_desc_total = input_for_total = document.createElement("p");
  p_desc_total.style.cssText = 'color: red;';
  var desc_total = document.createTextNode("This is sample calculation. On front-end you will see the calculation based on your Payment field values.");
  p_desc_total.appendChild(desc_total);

  div_label.appendChild(label);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_hide_total_currency);
  div_element.appendChild(adding_type);

  div_element.appendChild(div_paypal);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br3);
  div.appendChild(p_desc_total);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_total_description );

  if (w_field_label_pos == "top") {
    label_top(i);
  }

  change_class(w_class, i);
}

function create_map_locations(i) {
  var label = jQuery('<label class="fm-field-label">Location</label>');
  var button = jQuery('<span class="fm-add-attribute dashicons dashicons-plus-alt" title="Add" onClick="add_marker(' + i + ', -1)"></span>');
  var notice = jQuery('<div class="notice notice-info"><p>Drag the marker to change default marker position.</p></div>');
  var attr_table = jQuery('<div id="markers" class="fm-width-100"></div>');

  var input = label;
  input = input.add(button);
  input = input.add(notice);
  input = input.add(attr_table);
  return create_option_container(null, input);
}

function add_marker(id, i, w_long, w_lat, w_info) {
  var markers = jQuery('#markers');
  if (i == -1) {
    var last_child = markers.children().last();
    if (last_child.length > 0) {
      i = parseInt(last_child.prop("idi")) + 1;
    }
    else {
      i = 0;
    }
    w_long = null;
    w_lat = null;
    w_info = '';
  }

  var marker = jQuery('<div class="fm-width-100 fm-fields-set" id="marker_opt' + i + '" idi="' + i + '"></div>');
  var marker_body = jQuery('<div class="fm-width-90"></div>');
  marker_body.append(create_markmap_address(id, i));
  marker_body.append(create_markmap_longitude(id, w_long, i));
  marker_body.append(create_markmap_latitude(id, w_lat, i));
  marker_body.append(create_markmap_info(id, w_info, i));
  marker.append(marker_body);
  var marker_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_map(' + id + ',' + i + ')"></span></div>');
  marker.append(marker_remove);
  markers.append(marker);

  var adding = document.getElementById(id + "_elementform_id_temp")
  adding.setAttribute("long" + i, w_long);
  adding.setAttribute("lat" + i, w_lat);
  adding.setAttribute("info" + i, w_info);

  add_marker_on_map(id, i, w_long, w_lat, w_info, true);
}

function remove_map(id,i) {
  table = document.getElementById('marker_opt' + i);
  table.parentNode.removeChild(table);
  map = document.getElementById(id + "_elementform_id_temp");
  map.removeAttribute("long" + i);
  map.removeAttribute("lat" + i);
  map.removeAttribute("info" + i);
  remove_marker(id, i);
}

function type_map(i, w_center_x, w_center_y, w_long, w_lat, w_zoom, w_width, w_height, w_class, w_info, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_map");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_map'));
  edit_main_table.append(create_map_locations(i));
  edit_main_table.append(create_markmap_size(i, w_width, w_height));
  edit_main_table.append(create_keys(i, 'To set up map key click here'));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_map'));

  // Preview.
  var br2 = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_map");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding = document.createElement('div');
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.style.cssText = "width:" + w_width + "px; height: " + w_height + "px;";
  adding.setAttribute("zoom", w_zoom);
  adding.setAttribute("center_x", w_center_x);
  adding.setAttribute("center_y", w_center_y);

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = "map_" + i;
  label.style.cssText = 'display:none';

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = "table-cell";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "block";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br2);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_map_description );
  change_class(w_class, i);
  refresh_attr(i, 'type_text');

  if_gmap_init(i);

  n = w_long.length;

  for (j = 0; j < n; j++) {
    add_marker(i, j, w_long[j], w_lat[j], w_info[j]);
  }
}

function create_time_format(i, w_time_type) {
  var label = jQuery('<label class="fm-field-label">Time Format</label>');
  var input1 = jQuery('<input type="radio" id="el_label_time_type1" name="edit_for_time_type" value="format_24" onchange="format_24(' + i + ')"' + (w_time_type == "24" ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_label_time_type1">24 hour</label>');
  var input2 = jQuery('<input type="radio" id="el_label_time_type2" name="edit_for_time_type" value="format_12" onchange="format_12(' + i + ', \'am\', \'\', \'\', \'\')"' + (w_time_type == "24" ? '' : ' checked="checked"') + ' />');
  var label2 = jQuery('<label for="el_label_time_type2">12 hour</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function format_24(num) {
  tr_time1 = document.getElementById(num + '_tr_time1')
  td1 = document.getElementById(num + '_am_pm_select')
  tr_time2 = document.getElementById(num + '_tr_time2')
  td2 = document.getElementById(num + '_am_pm_label')
  tr_time1.removeChild(td1);
  tr_time2.removeChild(td2);
  document.getElementById(num + '_hhform_id_temp').value = "";
  document.getElementById(num + '_mmform_id_temp').value = "";
  if (document.getElementById(num + '_ssform_id_temp')) {
    document.getElementById(num + '_ssform_id_temp').value = "";
  }
}

function format_12(num, am_or_pm, w_hh, w_mm, w_ss) {
  tr_time1 = document.getElementById(num + '_tr_time1')
  tr_time2 = document.getElementById(num + '_tr_time2')
  var td1 = document.createElement('div');
  td1.setAttribute("id", num + "_am_pm_select");
  td1.setAttribute("class", "td_am_pm_select");
  td1.style.display = "table-cell";
  var td2 = document.createElement('div');
  td2.setAttribute("id", num + "_am_pm_label");
  td2.setAttribute("class", "td_am_pm_select");
  td2.style.display = "table-cell";

  var am_pm_select = document.createElement('select');
  am_pm_select.setAttribute("class", "am_pm_select");
  am_pm_select.setAttribute("name", num + "_am_pmform_id_temp");
  am_pm_select.setAttribute("id", num + "_am_pmform_id_temp");
  am_pm_select.setAttribute("onchange", "set_sel_am_pm(this)");

  var am_option = document.createElement('option');
  am_option.setAttribute("value", "am");
  am_option.innerHTML = "AM";

  var pm_option = document.createElement('option');
  pm_option.setAttribute("value", "pm");
  pm_option.innerHTML = "PM";

  if (am_or_pm == "pm") {
    pm_option.setAttribute("selected", "selected");
  }
  else {
    am_option.setAttribute("selected", "selected");
  }

  var am_pm_label = document.createElement('label');
  am_pm_label.setAttribute("class", "mini_label");
  am_pm_label.setAttribute("id", num + "_mini_label_am_pm");
  am_pm_label.innerHTML = w_mini_labels[3];

  am_pm_select.appendChild(am_option);
  am_pm_select.appendChild(pm_option);
  td1.appendChild(am_pm_select);
  td2.appendChild(am_pm_label);
  tr_time1.appendChild(td1);
  tr_time2.appendChild(td2);

  document.getElementById(num + '_hhform_id_temp').value = w_hh;
  document.getElementById(num + '_mmform_id_temp').value = w_mm;
  if (document.getElementById(num + '_ssform_id_temp')) {
    document.getElementById(num + '_ssform_id_temp').value = w_ss;
  }

  refresh_attr(num, 'type_time');

  jQuery(function () {
    jQuery("label#" + num + "_mini_label_am_pm").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var am_pm = "<input type='text' class='am_pm' size='4' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(am_pm);
        jQuery("input.am_pm").focus();
        jQuery("input.am_pm").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + num + "_mini_label_am_pm").text(value);
        });
      }
    });
  });
}

function create_display_seconds(i, w_ss, w_sec) {
  var label = jQuery('<label class="fm-field-label">Display Seconds</label>');
  var input1 = jQuery('<input type="radio" id="el_second_yes" name="edit_for_time_second" value="yes" onchange="second_yes(' + i + ',\'' + w_ss + '\')"' + (w_sec == "1" ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_second_yes">Yes</label>');
  var input2 = jQuery('<input type="radio" id="el_second_no" name="edit_for_time_second" value="no" onchange="second_no(' + i + ')"' + (w_sec == "1" ? '' : ' checked="checked"') + ' />');
  var label2 = jQuery('<label for="el_second_no">No</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  return create_option_container(label, input);
}

function second_yes(id, w_ss) {
  time_box = document.getElementById(id + '_tr_time1');
  text_box = document.getElementById(id + '_tr_time2');

  var td_time_input2_ket = document.createElement('div');
  td_time_input2_ket.setAttribute("align", "center");
  td_time_input2_ket.style.display = "table-cell";
  var td_time_input3 = document.createElement('div');
  td_time_input3.setAttribute("id", id + "_td_time_input3");
  td_time_input3.style.display = "table-cell";

  var td_time_label2_ket = document.createElement('div');
  td_time_label2_ket.style.display = "table-cell";

  var td_time_label3 = document.createElement('div');
  td_time_label3.setAttribute("id", id + "_td_time_label3");
  td_time_label3.style.display = "table-cell";

  var mm_ = document.createElement('span');
  mm_.setAttribute("class", 'wdform_colon');
  mm_.style.cssText = "font-style:bold; vertical-align:middle";
  mm_.innerHTML = "&nbsp;:&nbsp;";
  td_time_input2_ket.appendChild(mm_);

  var ss = document.createElement('input');

  ss.setAttribute("type", 'text');
  ss.setAttribute("value", w_ss);
  ss.setAttribute("class", "time_box");
  ss.setAttribute("id", id + "_ssform_id_temp");
  ss.setAttribute("name", id + "_ssform_id_temp");
  ss.setAttribute("onBlur", "add_0('" + id + "_ssform_id_temp')");
  var ss_label = document.createElement('label');
  ss_label.setAttribute("class", "mini_label");
  ss_label.innerHTML = "SS";
  ss_label.setAttribute("id", id + "_mini_label_ss");

  td_time_input3.appendChild(ss);
  td_time_label3.appendChild(ss_label);

  if (document.getElementById(id + '_am_pm_select')) {
    select_ = document.getElementById(id + "_am_pm_select");
    select_text = document.getElementById(id + "_am_pm_label");
    time_box.insertBefore(td_time_input3, select_);
    time_box.insertBefore(td_time_input2_ket, td_time_input3);
    text_box.insertBefore(td_time_label3, select_text);
    text_box.insertBefore(td_time_label2_ket, td_time_label3);
  }
  else {
    time_box.appendChild(td_time_input2_ket);
    time_box.appendChild(td_time_input3);
    text_box.appendChild(td_time_label2_ket);
    text_box.appendChild(td_time_label3);
  }

  jQuery(function () {
    jQuery("label#" + id + "_mini_label_ss").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var ss = "<input type='text' class='ss' style='outline:none; border:none; background:none; width:40px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(ss);
        jQuery("input.ss").focus();
        jQuery("input.ss").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + id + "_mini_label_ss").text(value);
        });
      }
    });
  });
  refresh_attr(id, 'type_time');
}

function second_no(id) {
  time_box = document.getElementById(id + '_tr_time1');
  text_box = document.getElementById(id + '_tr_time2');
  second_box = document.getElementById(id + '_td_time_input3');
  second_text = document.getElementById(id + '_td_time_label3');
  document.getElementById(id + '_td_time_input2').parentNode.removeChild(document.getElementById(id + '_td_time_input2').nextSibling);
  time_box.removeChild(second_box);
  text_box.removeChild(second_text.previousSibling);
  text_box.removeChild(second_text);
}

function go_to_type_time(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_mini_labels = ['HH', 'MM', 'SS', 'AM/PM'];
  type_time(new_id, 'Time', '', 'top', 'no', '24', '0', '1', '', '', '', w_mini_labels, 'no', '', w_attr_name, w_attr_value);
}

function type_time(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_time_type, w_am_pm, w_sec, w_hh, w_mm, w_ss, w_mini_labels, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_time");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_time'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_time_format(i, w_time_type));
  edit_main_table.append(create_display_seconds(i, w_ss, w_sec));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_time'));

  // Preview.
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_time");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  adding_required.setAttribute("id", i + "_requiredform_id_temp");
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("class", "fm-editable-label");

  edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");

  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_time = document.createElement('div');
  table_time.setAttribute("id", i + "_table_time");
  table_time.style.display = "table";

  var tr_time1 = document.createElement('div');
  tr_time1.setAttribute("id", i + "_tr_time1");
  tr_time1.style.display = "table-row";

  var tr_time2 = document.createElement('div');
  tr_time2.setAttribute("id", i + "_tr_time2");
  tr_time2.style.display = "table-row";

  var td_time_input1 = document.createElement('div');
  td_time_input1.setAttribute("id", i + "_td_time_input1");
  td_time_input1.style.cssText = "width:32px";
  td_time_input1.style.display = "table-cell";

  var td_time_input1_ket = document.createElement('div');
  td_time_input1_ket.setAttribute("align", "center");
  td_time_input1_ket.style.display = "table-cell";

  var td_time_input2 = document.createElement('div');
  td_time_input2.setAttribute("id", i + "_td_time_input2");
  td_time_input2.style.cssText = "width:32px";
  td_time_input2.style.display = "table-cell";

  var td_time_input2_ket = document.createElement('div');
  td_time_input2_ket.setAttribute("align", "center");
  td_time_input2_ket.style.display = "table-cell";

  var td_time_input3 = document.createElement('div');
  td_time_input3.setAttribute("id", i + "_td_time_input3");
  td_time_input3.style.cssText = "width:32px";
  td_time_input3.style.display = "table-cell";

  var td_time_label1 = document.createElement('div');
  td_time_label1.setAttribute("id", i + "_td_time_label1");
  td_time_label1.style.display = "table-cell";

  var td_time_label1_ket = document.createElement('div');
  td_time_label1_ket.style.display = "table-cell";
  var td_time_label2 = document.createElement('div');
  td_time_label2.setAttribute("id", i + "_td_time_label2");
  td_time_label2.style.display = "table-cell";
  var td_time_label2_ket = document.createElement('div');
  td_time_label2_ket.style.display = "table-cell";
  var td_time_label3 = document.createElement('div');
  td_time_label3.setAttribute("id", i + "_td_time_label3");
  td_time_label3.style.display = "table-cell";

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var hh = document.createElement('input');
  hh.setAttribute("type", 'text');
  hh.setAttribute("value", w_hh);
  hh.setAttribute("class", "time_box");
  hh.setAttribute("id", i + "_hhform_id_temp");
  hh.setAttribute("name", i + "_hhform_id_temp");
  hh.setAttribute("onBlur", "add_0('" + i + "_hhform_id_temp')");

  var hh_label = document.createElement('label');
  hh_label.setAttribute("class", "mini_label");
  hh_label.setAttribute("id", i + "_mini_label_hh");
  hh_label.innerHTML = w_mini_labels[0];

  var hh_ = document.createElement('span');
  hh_.setAttribute("class", 'wdform_colon');
  hh_.style.cssText = "font-style:bold; vertical-align:middle";
  hh_.innerHTML = "&nbsp;:&nbsp;";

  var mm = document.createElement('input');
  mm.setAttribute("type", 'text');
  mm.setAttribute("value", w_mm);
  mm.setAttribute("class", "time_box");

  mm.setAttribute("id", i + "_mmform_id_temp");
  mm.setAttribute("name", i + "_mmform_id_temp");
  mm.setAttribute("onBlur", "add_0('" + i + "_mmform_id_temp')");

  var mm_label = document.createElement('label');
  mm_label.setAttribute("class", "mini_label");
  mm_label.setAttribute("id", i + "_mini_label_mm");
  mm_label.innerHTML = w_mini_labels[1];

  var mm_ = document.createElement('span');
  mm_.style.cssText = "font-style:bold; vertical-align:middle";
  mm_.innerHTML = "&nbsp;:&nbsp;";
  mm_.setAttribute("class", 'wdform_colon');

  var ss = document.createElement('input');
  ss.setAttribute("type", 'text');
  ss.setAttribute("value", w_ss);
  ss.setAttribute("class", "time_box");

  ss.setAttribute("id", i + "_ssform_id_temp");
  ss.setAttribute("name", i + "_ssform_id_temp");
  ss.setAttribute("onBlur", "add_0('" + i + "_ssform_id_temp')");

  var ss_label = document.createElement('label');
  ss_label.setAttribute("class", "mini_label");
  ss_label.setAttribute("id", i + "_mini_label_ss");
  ss_label.innerHTML = w_mini_labels[2];

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  td_time_input1.appendChild(hh);
  td_time_input1_ket.appendChild(hh_);
  td_time_input2.appendChild(mm);
  td_time_input2_ket.appendChild(mm_);
  td_time_input3.appendChild(ss);
  tr_time1.appendChild(td_time_input1);
  tr_time1.appendChild(td_time_input1_ket);
  tr_time1.appendChild(td_time_input2);
  tr_time1.appendChild(td_time_input2_ket);
  tr_time1.appendChild(td_time_input3);

  td_time_label1.appendChild(hh_label);
  td_time_label2.appendChild(mm_label);
  td_time_label3.appendChild(ss_label);
  tr_time2.appendChild(td_time_label1);
  tr_time2.appendChild(td_time_label1_ket);
  tr_time2.appendChild(td_time_label2);
  tr_time2.appendChild(td_time_label2_ket);
  tr_time2.appendChild(td_time_label3);
  table_time.appendChild(tr_time1);
  table_time.appendChild(tr_time2);

  div_element.appendChild(adding_type);

  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(table_time);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  div.appendChild(div_for_editable_labels);
  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_time_description );

  if (w_field_label_pos == "top") {
    label_top(i);
  }
  if (w_time_type == "12") {
    format_12(i, w_am_pm, w_hh, w_mm, w_ss);
  }
  if (w_sec == "0") {
    second_no(i);
  }
  change_class(w_class, i);
  refresh_attr(i, 'type_time');

  jQuery(function () {
    jQuery("label#" + i + "_mini_label_hh").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var hh = "<input type='text' class='hh' size='4' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(hh);
        jQuery("input.hh").focus();
        jQuery("input.hh").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_hh").text(value);
        });
      }
    });

    jQuery("label#" + i + "_mini_label_mm").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var mm = "<input type='text' class='mm' size='4' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(mm);
        jQuery("input.mm").focus();
        jQuery("input.mm").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_mm").text(value);
        });
      }
    });

    jQuery("label#" + i + "_mini_label_ss").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var ss = "<input type='text' class='ss' size='4' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(ss);
        jQuery("input.ss").focus();
        jQuery("input.ss").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_ss").text(value);
        });
      }
    });
  });
}

function go_to_type_send_copy(new_id) {
  if (jQuery('#take').find(jQuery("div[type='type_send_copy']")).length != 0) {
    alert("This field already has been created.");
    delete_last_child();
    return;
  }
  w_attr_name = [];
  w_attr_value = [];
  type_send_copy(new_id, 'Send a copy of this message to yourself', '', 'top', 'no', false, 'no', w_attr_name, w_attr_value);
}

function type_send_copy(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_required, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_send_copy");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_send_copy'));
  edit_main_table.append(create_label(i, w_field_label));
  //edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_send_copy'));

  // Preview.
  element = 'input';
  type = 'checkbox';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_send_copy");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", type);
  if (w_first_val)
    adding.setAttribute("checked", "checked");
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_elementform_id_temp");
  adding.setAttribute("onclick", "set_checked('" + i + "','','form_id_temp')");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("class", "fm-editable-label");

  edit_labels = document.createTextNode("Use the field to allow the user to choose whether to receive a copy of the submitted form or not. Do not forget to fill in User Email section in Email Options in advance.");

  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + 'px';
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  div.appendChild(div_for_editable_labels);

  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_send_copy_description );
  if (w_field_label_pos == "top")
    label_top(i);
  refresh_attr(i, 'type_text');
}

function create_default_date_range(i, w_default_date_start, w_default_date_end) {
  var w_format = jQuery("#date_format").val();
  var label = jQuery('<label class="fm-field-label" for="default_date_start">Default range</label>');
  var input = jQuery('<input type="text" class="fm-width-40" id="default_date_start" placeholder="' + w_format + '" onFocusOut="change_hidden_input_value_range(this.value, \'default_date\', \'start\', ' + i + ', \'' + i + '_default_date_id_temp_start\')" value="' + w_default_date_start + '" />-<input type="text" class="fm-width-40" id="default_date_end" placeholder="' + w_format + '" onFocusOut="change_hidden_input_value_range(this.value, \'default_date\', \'end\', ' + i + ', \'' + i + '_default_date_id_temp_end\')" value="' + w_default_date_end + '" />');
  return create_option_container(label, input);
}

function change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, new_element_value) {
  if ( typeof new_element_value === 'undefined' ) {
    var new_element_value = element_value;
  }
  var date_format = jQuery("#" + id_int + "_buttonform_id_temp").attr('format');
  document.getElementById(id).value = new_element_value;
  var element_value_new = element_value;
  if ( date_fields == "default_date" ) {
    if ( start_end == "start" ) {
      if ( element_value == "today" ) {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker("setDate", new Date());
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', 'minDate', new Date());
      }
      else if ( element_value.indexOf("d") == -1 && element_value.indexOf("m") == -1 && element_value.indexOf("y") == -1 && element_value.indexOf("w") == -1 ) {
        if ( element_value !== "" ) {
          jQuery("#" + id_int + "_elementform_id_temp0").datepicker("setDate", element_value);
          jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', 'minDate', element_value);
        }
        else {
          jQuery("#" + id_int + "_elementform_id_temp0").datepicker("setDate", element_value);
          date_fields = "minDate";
          var element_value = jQuery("#min_date").val();
          change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, element_value_new);
        }
      }
      else {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker("setDate", element_value);
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', 'minDate', element_value);
      }
    }
    else {
      if ( element_value == "today" ) {
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker("setDate", new Date());
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', 'maxDate', new Date());
      }
      else if ( element_value.indexOf("d") == -1 && element_value.indexOf("m") == -1 && element_value.indexOf("y") == -1 && element_value.indexOf("w") == -1 ) {
        if ( element_value !== "" ) {
          jQuery("#" + id_int + "_elementform_id_temp1").datepicker("setDate", element_value);
          jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', 'maxDate', new Date(element_value));
        }
        else {
          jQuery("#" + id_int + "_elementform_id_temp1").datepicker("setDate", element_value);
          date_fields = "maxDate";
          element_value = jQuery("#max_date").val();
          change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, element_value_new);
        }
      }
      else {
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker("setDate", element_value);
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', 'maxDate', element_value);
      }
    }
  }
  if ( date_fields == "minDate" || date_fields == "maxDate" ) {
    if ( element_value == "today" ) {
      if ( date_fields == "minDate" && jQuery("#default_date_start").val() == "" ) {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', date_fields, new Date());
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', date_fields, new Date());
      }
      if ( date_fields == "minDate" && jQuery("#default_date_start").val() != "" ) {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', date_fields, new Date());
        element_value = jQuery("#default_date_start").val();
        date_fields = "default_date";
        start_end = "start";
        change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, element_value_new);
      }
      if ( date_fields == "maxDate" && jQuery("#default_date_end").val() == "" ) {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', date_fields, new Date());
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', date_fields, new Date());
      }
      if ( date_fields == "maxDate" && jQuery("#default_date_end").val() != "" ) {
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', date_fields, new Date());
        element_value = jQuery("#default_date_end").val();
        date_fields = "default_date";
        start_end = "end";
        change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, element_value_new);
      }
    }
    else {
      if ( element_value.indexOf("d") == -1 && element_value.indexOf("m") == -1 && element_value.indexOf("w") == -1 && element_value.indexOf("y") == -1 && element_value !== "" ) {
        element_value = jQuery.datepicker.formatDate(date_format, new Date(element_value));
      }
      if ( (date_fields == "minDate" && jQuery("#default_date_start").val() == "") || (date_fields == "maxDate" && jQuery("#default_date_end").val() == "") ) {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', date_fields, element_value);
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', date_fields, element_value);
      }
      if ( date_fields == "minDate" && jQuery("#default_date_start").val() != "" ) {
        jQuery("#" + id_int + "_elementform_id_temp0").datepicker('option', date_fields, element_value);
        element_value = jQuery("#default_date_start").val();
        date_fields = "default_date";
        start_end = "start";
        change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, element_value_new);
      }
      if ( date_fields == "maxDate" && jQuery("#default_date_end").val() != "" ) {
        jQuery("#" + id_int + "_elementform_id_temp1").datepicker('option', date_fields, element_value);
        element_value = jQuery("#default_date_end").val();
        date_fields = "default_date";
        start_end = "end";
        change_hidden_input_value_range(element_value, date_fields, start_end, id_int, id, element_value_new);
      }
    }
  }
  if ( date_fields == "invalide_date" ) {
    jQuery("input[id^=" + id_int + "_elementform_id_temp]").datepicker("option", "beforeShowDay", function (date) {
      var invalid_dates = element_value;
      var invalid_dates_finish = [];
      var invalid_dates_start = invalid_dates.split(",");
      var invalid_date_range = [];

      for ( var i = 0; i < invalid_dates_start.length; i++ ) {
        invalid_dates_start[i] = invalid_dates_start[i].trim();
        if (invalid_dates_start[i].length < 11) {
          invalid_dates_finish.push(invalid_dates_start[i]);
        }
        else {
          if ( invalid_dates_start[i].indexOf("-") > 4 )
            invalid_date_range.push(invalid_dates_start[i].split("-"));
          else {
            var invalid_date_array = invalid_dates_start[i].split("-");
            var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
            var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
            invalid_date_range.push([start_invalid_day, end_invalid_day]);
          }
        }
      }

      jQuery.each(invalid_date_range, function (index, value) {
        for (var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
          invalid_dates_finish.push(jQuery.datepicker.formatDate(date_format, d));
        }
      });

      var w_hide_sunday = jQuery("#" + id_int + "_show_week_days").attr('sunday') == 'yes' ? 'true' : 'day != 0';
      var w_hide_monday = jQuery("#" + id_int + "_show_week_days").attr('monday') == 'yes' ? 'true' : 'day != 1';
      var w_hide_tuesday = jQuery("#" + id_int + "_show_week_days").attr('tuesday') == 'yes' ? 'true' : 'day != 2';
      var w_hide_wednesday = jQuery("#" + id_int + "_show_week_days").attr('wednesday') == 'yes' ? 'true' : 'day != 3';
      var w_hide_thursday = jQuery("#" + id_int + "_show_week_days").attr('thursday') == 'yes' ? 'true' : 'day != 4';
      var w_hide_friday = jQuery("#" + id_int + "_show_week_days").attr('friday') == 'yes' ? 'true' : 'day != 5';
      var w_hide_saturday = jQuery("#" + id_int + "_show_week_days").attr('saturday') == 'yes' ? 'true' : 'day != 6';
      var day = date.getDay();
      var string_days = jQuery.datepicker.formatDate(date_format, date);
      return [invalid_dates_finish.indexOf(string_days) == -1 && eval(w_hide_sunday) && eval(w_hide_monday) && eval(w_hide_tuesday) && eval(w_hide_wednesday) && eval(w_hide_thursday) && eval(w_hide_friday) && eval(w_hide_saturday)];
    });
  }
}

function go_to_type_date_range(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_show_days = ['yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'];
  type_date_range(new_id, 'Date range', '', 'top', 'no', '', '', 'no', 'no', '', 'mm/dd/yy', '0', '', '', '', '', '', w_show_days, 'yes', '...', w_attr_name, w_attr_value, 'no');
}

function type_date_range(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_date, w_required, w_show_image, w_class, w_format, w_start_day, w_default_date_start, w_default_date_end, w_min_date, w_max_date,  w_invalid_dates, w_show_days, w_hide_time,  w_but_val, w_attr_name, w_attr_value,w_disable_past_days) {
  jQuery("#element_type").val("type_date_range");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_date_range'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size, '\'' + i + '_elementform_id_temp0\'', '\'' + i + '_elementform_id_temp1\''));
  edit_main_table.append(create_date_format(i, w_format));
  edit_main_table.append(create_week_start(i, w_start_day));


  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_default_date_range(i, w_default_date_start, w_default_date_end));
  advanced_options_container.append(create_minimum_date(i, w_min_date, true));
  advanced_options_container.append(create_maximum_date(i, w_max_date, true));
  advanced_options_container.append(create_excluded_dates(i, w_invalid_dates, true));
  advanced_options_container.append(create_selectable_week_days(i, w_show_days));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_show_date_picker_button(i, w_show_image, 'date_range'));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_date_range'));

  // Preview.
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_date_range");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_dis_past_days = document.createElement('input');
  adding_dis_past_days.setAttribute("type", 'hidden');
  adding_dis_past_days.setAttribute("value", w_disable_past_days);
  adding_dis_past_days.setAttribute("id", i + "_dis_past_daysform_id_temp");
  adding_dis_past_days.setAttribute("name", i + "_dis_past_daysform_id_temp");

  /////////   adding hidden inputs new date   /////////////////

  var adding_start_day = document.createElement("input");
  adding_start_day.setAttribute("type", "hidden");
  adding_start_day.setAttribute("value", w_start_day);
  adding_start_day.setAttribute("name", i + "_start_dayform_id_temp");
  adding_start_day.setAttribute("id", i + "_start_dayform_id_temp");

  var adding_default_date_start = document.createElement("input");
  adding_default_date_start.setAttribute("type", "hidden");
  adding_default_date_start.setAttribute("name", i + "_default_date_id_temp_start");
  adding_default_date_start.setAttribute("id", i + "_default_date_id_temp_start");
  adding_default_date_start.setAttribute("value", w_default_date_start);

  var adding_default_date_end = document.createElement("input");
  adding_default_date_end.setAttribute("type", "hidden");
  adding_default_date_end.setAttribute("name", i + "_default_date_id_temp_end");
  adding_default_date_end.setAttribute("id", i + "_default_date_id_temp_end");
  adding_default_date_end.setAttribute("value", w_default_date_end);

  var adding_min_date = document.createElement("input");
  adding_min_date.setAttribute("type", "hidden");
  adding_min_date.setAttribute("name", i + "_min_date_id_temp");
  adding_min_date.setAttribute("id", i + "_min_date_id_temp");
  adding_min_date.setAttribute("value", w_min_date);

  var adding_max_date = document.createElement("input");
  adding_max_date.setAttribute("type", "hidden");
  adding_max_date.setAttribute("name", i + "_max_date_id_temp");
  adding_max_date.setAttribute("id", i + "_max_date_id_temp");
  adding_max_date.setAttribute("value", w_max_date);

  var adding_invalid_dates = document.createElement("input");
  adding_invalid_dates.setAttribute("type", "hidden");
  adding_invalid_dates.setAttribute("name", i + "_invalid_dates_id_temp");
  adding_invalid_dates.setAttribute("id", i + "_invalid_dates_id_temp");
  adding_invalid_dates.setAttribute("value", w_invalid_dates);

  var adding_show_days = document.createElement("input");
  adding_show_days.setAttribute("type", "hidden");
  adding_show_days.setAttribute("name", i + "_show_week_days");
  adding_show_days.setAttribute("id", i + "_show_week_days");
  adding_show_days.setAttribute("sunday", w_show_days[0]);
  adding_show_days.setAttribute("monday", w_show_days[1]);
  adding_show_days.setAttribute("tuesday", w_show_days[2]);
  adding_show_days.setAttribute("wednesday", w_show_days[3]);
  adding_show_days.setAttribute("thursday", w_show_days[4]);
  adding_show_days.setAttribute("friday", w_show_days[5]);
  adding_show_days.setAttribute("saturday", w_show_days[6]);

  var adding_show_image = document.createElement("input");
  adding_show_image.setAttribute("type", "hidden");
  adding_show_image.setAttribute("value", w_show_image);
  adding_show_image.setAttribute("name", i + "_show_imageform_id_temp");
  adding_show_image.setAttribute("id", i + "_show_imageform_id_temp");

  var adding_hide_time = document.createElement("input");
  adding_hide_time.setAttribute("type", "hidden");
  adding_hide_time.setAttribute("value", w_hide_time);
  adding_hide_time.setAttribute("name", i + "_hide_timeform_id_temp");
  adding_hide_time.setAttribute("id", i + "_hide_timeform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.style.position = "relative";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_date = document.createElement('div');
  table_date.setAttribute("id", i + "_table_date");
  table_date.style.display = "table";

  var tr_date1 = document.createElement('div');
  tr_date1.setAttribute("id", i + "_tr_date1");
  tr_date1.style.display = "table-row";

  var tr_date2 = document.createElement('div');
  tr_date2.setAttribute("id", i + "_tr_date2");
  tr_date2.style.display = "table-row";

  var td_date_input1 = document.createElement('div');
  td_date_input1.setAttribute("id", i + "_td_date_input1");
  td_date_input1.style.display = "table-cell";

  var td_date_input2 = document.createElement('div');
  td_date_input2.setAttribute("id", i + "_td_date_input2");
  td_date_input2.style.display = "table-cell";

  var td_date_input3 = document.createElement('div');
  td_date_input3.setAttribute("id", i + "_td_date_input3");
  td_date_input3.style.display = "table-cell";

  var td_date_label1 = document.createElement('div');
  td_date_label1.setAttribute("id", i + "_td_date_label1");
  td_date_label1.style.display = "table-cell";

  var td_date_label2 = document.createElement('div');
  td_date_label2.setAttribute("id", i + "_td_date_label2");
  td_date_label2.style.display = "table-cell";

  var td_date_label3 = document.createElement('div');
  td_date_label3.setAttribute("id", i + "_td_date_label3");
  td_date_label3.style.display = "table-cell";

  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";

  var adding_desc_p = document.createElement('p');
  var adding_desc_b = document.createElement('b');

  var text_format_1 = document.createTextNode("The format can be combinations of the following:");
  var text_format_2 = document.createTextNode("d - day of month (no leading zero)");
  var text_format_3 = document.createTextNode("dd - day of month (two digit)");
  var text_format_4 = document.createTextNode("o - day of the year (no leading zeros)");
  var text_format_5 = document.createTextNode("oo - day of the year (three digit)");
  var text_format_6 = document.createTextNode("D - day name short");
  var text_format_7 = document.createTextNode("DD - day name long");
  var text_format_8 = document.createTextNode("m - month of year (no leading zero)");
  var text_format_9 = document.createTextNode("mm - month of year (two digit)");
  var text_format_10 = document.createTextNode("M - month name short");
  var text_format_11 = document.createTextNode("MM - month name long");
  var text_format_12 = document.createTextNode("y - year (two digit)");
  var text_format_13 = document.createTextNode("yy - year (four digit)");

  var format_br_1 = document.createElement('br');
  var format_br_2 = document.createElement('br');
  var format_br_3 = document.createElement('br');
  var format_br_4 = document.createElement('br');
  var format_br_5 = document.createElement('br');
  var format_br_6 = document.createElement('br');
  var format_br_7 = document.createElement('br');
  var format_br_8 = document.createElement('br');
  var format_br_9 = document.createElement('br');
  var format_br_10 = document.createElement('br');
  var format_br_11 = document.createElement('br');
  var format_br_12 = document.createElement('br');
  var format_br_13 = document.createElement('br');

  var adding_desc_p_2 = document.createElement('p');
  var adding_desc_b_2 = document.createElement('b');

  var text_default_1 = document.createTextNode("Accepted values of Default, Minimum and Maximum:");
  var text_default_2 = document.createTextNode("Empty: No default / minimum / maximum");
  var text_default_4 = document.createTextNode("Current date : 'today'");
  var text_default_5 = document.createTextNode("Relative date: A number of days/weeks/months/years from today, e.g. '-1d' will be yesterday, '+1y+3m+2w+3d' will be  1 year, 3 months, 2 weeks and 3 days from today.");

  var default_br_1 = document.createElement('br');
  var default_br_2 = document.createElement('br');
  var default_br_4 = document.createElement('br');
  var default_br_6 = document.createElement('br');
  var default_br_7 = document.createElement('br');

  var adding_desc_p_3 = document.createElement('p');
  var adding_desc_b_3 = document.createElement('b');

  var text_default_6 = document.createTextNode("Dates to exclude:");
  var text_default_7 = document.createTextNode("Enter comma-separated list of dates and date ranges using the date format 'mm/dd/yy', e.g. 08/15/2016, 06/15/2016-06/20/2016");

  var adding_0 = document.createElement('input');
  adding_0.setAttribute("type", 'text');
  adding_0.setAttribute("value", w_default_date_start);
  adding_0.setAttribute("id", i + "_elementform_id_temp0");
  adding_0.setAttribute("name", i + "_elementform_id_temp0");
  adding_0.style.cssText = "width:" + w_size + "px;"
  adding_0.setAttribute("onChange", "change_value_range('" + i + "_elementform_id_temp1', 'minDate', this.value)");

  var adding_1 = document.createElement('input');
  adding_1.setAttribute("type", 'text');
  adding_1.setAttribute("value", w_default_date_end);
  adding_1.setAttribute("id", i + "_elementform_id_temp1");
  adding_1.setAttribute("name", i + "_elementform_id_temp1");
  adding_1.style.cssText = "width:" + w_size + "px;"
  adding_1.setAttribute("onChange", "change_value_range('" + i + "_elementform_id_temp0', 'maxDate', this.value)");

  var adding_from = document.createTextNode("-");

  var adding_image_start = document.createElement('span');
  adding_image_start.setAttribute("id", i + "_show_imagedateform_id_temp0");
  adding_image_start.setAttribute("class", "dashicons dashicons-calendar-alt wd-calendar-button " + (w_show_image == "yes" ? "wd-inline-block" : "wd-hidden"));
  adding_image_start.setAttribute("onClick", "show_datepicker('" + i + "_elementform_id_temp0')");

  var adding_image_end = document.createElement('span');
  adding_image_end.setAttribute("id", i + "_show_imagedateform_id_temp1");
  adding_image_end.setAttribute("class", "dashicons dashicons-calendar-alt wd-calendar-button " + (w_show_image == "yes" ? "wd-inline-block" : "wd-hidden"));
  adding_image_end.setAttribute("onClick", "show_datepicker('" + i + "_elementform_id_temp1')");

  var dis_past_days = w_disable_past_days == 'yes' ? true : false;

  var adding_button = document.createElement('input');
  adding_button.setAttribute("id", i + "_buttonform_id_temp");
  adding_button.setAttribute("class", "button");
  adding_button.setAttribute("type", 'hidden');
  adding_button.setAttribute("value", w_but_val);
  adding_button.setAttribute("format", w_format);

  var main_td = document.getElementById('show_table');

  adding_desc_b.appendChild(text_format_1);
  adding_desc_p.appendChild(adding_desc_b);
  adding_desc_p.appendChild(format_br_1);
  adding_desc_p.appendChild(text_format_2);
  adding_desc_p.appendChild(format_br_2);
  adding_desc_p.appendChild(text_format_3);
  adding_desc_p.appendChild(format_br_3);
  adding_desc_p.appendChild(text_format_4);
  adding_desc_p.appendChild(format_br_4);
  adding_desc_p.appendChild(text_format_5);
  adding_desc_p.appendChild(format_br_5);
  adding_desc_p.appendChild(text_format_6);
  adding_desc_p.appendChild(format_br_6);
  adding_desc_p.appendChild(text_format_7);
  adding_desc_p.appendChild(format_br_7);
  adding_desc_p.appendChild(text_format_8);
  adding_desc_p.appendChild(format_br_8);
  adding_desc_p.appendChild(text_format_9);
  adding_desc_p.appendChild(format_br_9);
  adding_desc_p.appendChild(text_format_10);
  adding_desc_p.appendChild(format_br_10);
  adding_desc_p.appendChild(text_format_11);
  adding_desc_p.appendChild(format_br_11);
  adding_desc_p.appendChild(text_format_12);
  adding_desc_p.appendChild(format_br_12);
  adding_desc_p.appendChild(text_format_13);
  adding_desc_p.appendChild(format_br_13);

  adding_desc_b_2.appendChild(text_default_1);
  adding_desc_p_2.appendChild(adding_desc_b_2);
  adding_desc_p_2.appendChild(default_br_1);
  adding_desc_p_2.appendChild(text_default_2);
  adding_desc_p_2.appendChild(default_br_2);
  adding_desc_p_2.appendChild(text_default_4);
  adding_desc_p_2.appendChild(default_br_4);
  adding_desc_p_2.appendChild(text_default_5);

  adding_desc_b_3.appendChild(text_default_6);
  adding_desc_p_3.appendChild(adding_desc_b_3);
  adding_desc_p_3.appendChild(default_br_6);
  adding_desc_p_3.appendChild(text_default_7);
  adding_desc_p_3.appendChild(default_br_7);

  div_label.appendChild(label);
  div_label.appendChild(required);

  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_show_image);
  div_element.appendChild(adding_dis_past_days);
  div_element.appendChild(adding_0);
  div_element.appendChild(adding_image_start);
  div_element.appendChild(adding_from);
  div_element.appendChild(adding_1);
  div_element.appendChild(adding_image_end);
  /*  adding hidden  inputs(new date) in div */
  div_element.appendChild(adding_start_day);
  div_element.appendChild(adding_default_date_start);
  div_element.appendChild(adding_default_date_end);
  div_element.appendChild(adding_min_date);
  div_element.appendChild(adding_max_date);
  div_element.appendChild(adding_invalid_dates);
  div_element.appendChild(adding_hide_time);
  div_element.appendChild(adding_show_days);
  div_element.appendChild(adding_button);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  div.appendChild(adding_desc_p);
  div.appendChild(adding_desc_p_2);
  div.appendChild(adding_desc_p_3);
  main_td.appendChild(div);

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_range');

  var w_min_date_start, w_max_date_end;

  /*------------------- Date range - start */
  if ( w_default_date_start && w_default_date_start != "today" ) {
    w_min_date_start = w_default_date_start;
  }
  else if ( w_default_date_start == "today" ) {
    w_min_date_start = jQuery.datepicker.formatDate(w_format, new Date());
  }
  else {
    w_min_date_start = w_min_date;
  }

  if ( w_default_date_start == 'today' ) {
    jQuery("#" + i + "_elementform_id_temp0").datepicker("setDate", new Date());
  }
  else if ( w_default_date_start.indexOf("d") == -1 && w_default_date_start.indexOf("m") == -1 && w_default_date_start.indexOf("y") == -1 && w_default_date_start.indexOf("w") == -1 ) {
    if (w_default_date_start !== "") {
      w_default_date_start = jQuery.datepicker.formatDate(w_format, new Date(w_default_date_start));
    }
    jQuery("#" + i + "_elementform_id_temp0").datepicker("setDate", w_default_date_start);
  }
  else {
    jQuery("#" + i + "_elementform_id_temp0").datepicker("setDate", w_default_date_start);
  }

  /*------------------- Date range - end */
  if ( w_default_date_end && w_default_date_end != "today" ) {
    w_max_date_end = w_default_date_end;
  }
  else if ( w_default_date_end == "today" ) {
    w_max_date_end = jQuery.datepicker.formatDate(w_format, new Date());
  }
  else {
    w_max_date_end = w_max_date;
  }

  if ( w_default_date_end == 'today' ) {
    jQuery("#" + i + "_elementform_id_temp1").datepicker("setDate", new Date());
  }
  else if ( w_default_date_end.indexOf("d") == -1 && w_default_date_end.indexOf("m") == -1 && w_default_date_end.indexOf("y") == -1 && w_default_date_end.indexOf("w") == -1 ) {
    if (w_default_date_end !== "") {
      w_default_date_end = jQuery.datepicker.formatDate(w_format, new Date(w_default_date_end));
    }
    jQuery("#" + i + "_elementform_id_temp1").datepicker("setDate", w_default_date_end);
  }
  else {
    jQuery("#" + i + "_elementform_id_temp1").datepicker("setDate", w_default_date_end);
  }

  jQuery("#" + i + "_elementform_id_temp0").datepicker({
    dateFormat: w_format,
    minDate: w_min_date,
    maxDate: w_max_date,
    firstDay: w_start_day,
    setDate: w_default_date_start,
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+50",
    showOtherMonths: true,
    selectOtherMonths: true,
    beforeShowDay: function (date) {
      var invalid_dates = w_invalid_dates;
      var invalid_dates_finish = [];
      var invalid_dates_start = invalid_dates.split(",");
      var invalid_date_range = [];

      for (var i = 0; i < invalid_dates_start.length; i++) {
        invalid_dates_start[i] = invalid_dates_start[i].trim();
        if (invalid_dates_start[i].length < 11) {
          invalid_dates_finish.push(invalid_dates_start[i]);
        }
        else {
          if (invalid_dates_start[i].indexOf("-") > 4)
            invalid_date_range.push(invalid_dates_start[i].split("-"));
          else {
            var invalid_date_array = invalid_dates_start[i].split("-");
            var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
            var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
            invalid_date_range.push([start_invalid_day, end_invalid_day]);
          }
        }
      }

      jQuery.each(invalid_date_range, function (index, value) {
        for (var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
          invalid_dates_finish.push(jQuery.datepicker.formatDate(w_format, d));
        }
      });
      var string_days = jQuery.datepicker.formatDate(w_format, date);
      var day = date.getDay();

      var w_hide_sunday = w_show_days[0] == 'yes' ? 'true' : 'day != 0';
      var w_hide_monday = w_show_days[1] == 'yes' ? 'true' : 'day != 1';
      var w_hide_tuesday = w_show_days[2] == 'yes' ? 'true' : 'day != 2';
      var w_hide_wednesday = w_show_days[3] == 'yes' ? 'true' : 'day != 3';
      var w_hide_thursday = w_show_days[4] == 'yes' ? 'true' : 'day != 4';
      var w_hide_friday = w_show_days[5] == 'yes' ? 'true' : 'day != 5';
      var w_hide_saturday = w_show_days[6] == 'yes' ? 'true' : 'day != 6';

      return [invalid_dates_finish.indexOf(string_days) == -1 && eval(w_hide_sunday) && eval(w_hide_monday) && eval(w_hide_tuesday) && eval(w_hide_wednesday) && eval(w_hide_thursday) && eval(w_hide_friday) && eval(w_hide_saturday)];
    }
  });
  jQuery("#" + i + "_elementform_id_temp0").datepicker('option', 'dateFormat', w_format);

  jQuery("#" + i + "_elementform_id_temp1").datepicker({
    dateFormat: w_format,
    minDate: w_min_date,
    maxDate: w_max_date,
    firstDay: w_start_day,
    setDate: w_default_date_end,
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+50",
    showOtherMonths: true,
    selectOtherMonths: true,
    beforeShowDay: function (date) {
      var invalid_dates = w_invalid_dates;
      var invalid_dates_finish = [];
      var invalid_dates_start = invalid_dates.split(",");
      var invalid_date_range = [];

      for (var i = 0; i < invalid_dates_start.length; i++) {
        invalid_dates_start[i] = invalid_dates_start[i].trim();
        if (invalid_dates_start[i].length < 11) {
          invalid_dates_finish.push(invalid_dates_start[i]);
        }
        else {
          if (invalid_dates_start[i].indexOf("-") > 4)
            invalid_date_range.push(invalid_dates_start[i].split("-"));
          else {
            var invalid_date_array = invalid_dates_start[i].split("-");
            var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
            var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
            invalid_date_range.push([start_invalid_day, end_invalid_day]);
          }
        }
      }

      jQuery.each(invalid_date_range, function (index, value) {
        for (var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
          invalid_dates_finish.push(jQuery.datepicker.formatDate(w_format, d));
        }
      });
      var string_days = jQuery.datepicker.formatDate(w_format, date);
      var day = date.getDay();

      var w_hide_sunday = w_show_days[0] == 'yes' ? 'true' : 'day != 0';
      var w_hide_monday = w_show_days[1] == 'yes' ? 'true' : 'day != 1';
      var w_hide_tuesday = w_show_days[2] == 'yes' ? 'true' : 'day != 2';
      var w_hide_wednesday = w_show_days[3] == 'yes' ? 'true' : 'day != 3';
      var w_hide_thursday = w_show_days[4] == 'yes' ? 'true' : 'day != 4';
      var w_hide_friday = w_show_days[5] == 'yes' ? 'true' : 'day != 5';
      var w_hide_saturday = w_show_days[6] == 'yes' ? 'true' : 'day != 6';

      return [invalid_dates_finish.indexOf(string_days) == -1 && eval(w_hide_sunday) && eval(w_hide_monday) && eval(w_hide_tuesday) && eval(w_hide_wednesday) && eval(w_hide_thursday) && eval(w_hide_friday) && eval(w_hide_saturday)];
    }
  });
  jQuery("#" + i + "_elementform_id_temp1").datepicker('option', 'dateFormat', w_format);

}


function create_star_amount(i, w_star_amount) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_star_size">Number of Stars</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_star_size" onKeyPress="return check_isnum(event)" onKeyUp="change_star_amount(this.value, ' + i + ', \'form_id_temp\')" value="' + w_star_amount + '" />');
  return create_option_container(label, input);
}

function change_star_amount(b,id,form_id) {
  var td = document.getElementById(id + "_element_sectionform_id_temp");
  var div = document.getElementById(id + "_elementform_id_temp");
  td.removeChild(div);
  var div1 = document.createElement('div');
  div1.setAttribute("id", id + "_elementform_id_temp");
  for (var j = 0; j < b; j++) {
    var adding_img = document.createElement("img");
    adding_img.setAttribute('id', id + '_star_' + j);
    adding_img.setAttribute('src', plugin_url + '/images/star.png');
    adding_img.setAttribute('onmouseover', "change_src(" + j + "," + id + ",'form_id_temp')");
    adding_img.setAttribute('onmouseout', "reset_src(" + j + "," + id + ")");
    adding_img.setAttribute('onclick', "select_star_rating(" + j + "," + id + ",'form_id_temp')");
    div1.appendChild(adding_img);
  }
  td.appendChild(div1);
  document.getElementById(id + '_star_amountform_id_temp').value = b;
}

function create_star_color(i, w_field_label_col) {
  var label = jQuery('<label class="fm-field-label">Star Color</label>');
  var input = jQuery('<select class="fm-width-100" id="edit_for_label_color" name="edit_for_label_color" onChange="label_color(this.value, ' + i + ')"></select>');

  var colors = [];
  colors['yellow'] = "Yellow";
  colors['green'] = "Green";
  colors['blue'] = "Blue";
  colors['red'] = "Red";

  for (var keys  in colors) {
    if (!colors.hasOwnProperty(keys)) {
      continue;
    }
    var el_option = jQuery('<option value="' + keys + '"' + (w_field_label_col == keys ? ' selected="selected"' : '') + '>' + colors[keys] + '</option>');
    input.append(el_option);
  }

  return create_option_container(label, input);
}

function label_color(b, id) {
  document.getElementById(id + '_star_colorform_id_temp').value = b;
}

function go_to_type_star_rating(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_star_rating(new_id, 'Star Rating', '', 'top', 'no', 'yellow', '5', 'no', 'wdform_star_rating', w_attr_name, w_attr_value);
}

function type_star_rating(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_label_col, w_star_amount, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_star_rating");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_star_rating'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_star_amount(i, w_star_amount));
  edit_main_table.append(create_star_color(i, w_field_label_col));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_star_rating'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_star_rating");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_star_amount = document.createElement("input");
  adding_star_amount.setAttribute("type", "hidden");
  adding_star_amount.setAttribute("value", w_star_amount);
  adding_star_amount.setAttribute("id", i + "_star_amountform_id_temp");
  adding_star_amount.setAttribute("name", i + "_star_amountform_id_temp");

  var adding_star_color = document.createElement("input");
  adding_star_color.setAttribute("type", "hidden");
  adding_star_color.setAttribute("value", w_field_label_col);
  adding_star_color.setAttribute("name", i + "_star_colorform_id_temp");
  adding_star_color.setAttribute("id", i + "_star_colorform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.cssText = "display:" + display_label_div + "; vertical-align:top; width:" + w_field_label_size + "px;";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var div1 = document.createElement('div');
  div1.setAttribute("id", i + "_elementform_id_temp");
  div1.setAttribute("class", "wdform_stars");

  var br1 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_star_amount);
  div_element.appendChild(adding_star_color);

  for (var j = 0; j < w_star_amount; j++) {
    var adding = document.createElement("img");
    adding.setAttribute('id', i + '_star_' + j);
    adding.setAttribute('src', plugin_url + '/images/star.png');
    adding.setAttribute('onmouseover', "change_src(" + j + "," + i + ",'form_id_temp')");
    adding.setAttribute('onmouseout', "reset_src(" + j + "," + i + ")");
    adding.setAttribute('onclick', "select_star_rating(" + j + "," + i + ",'form_id_temp')");

    div1.appendChild(adding);
  }

  div_element.appendChild(div1);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br1);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_stars_description );
  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_star_rating');
}

function create_scale_amount(i, w_scale_amount) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_scale_amount">Scale Range</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_scale_amount" onKeyPress="return check_isnum(event)" onKeyUp="change_scale_amount(this.value, ' + i + ', \'form_id_temp\')" value="' + w_scale_amount + '" />');
  return create_option_container(label, input);
}

function change_scale_amount(b,id,form_id) {
  var table = document.getElementById(id + "_scale_tableform_id_temp");
  var div = document.getElementById(id + "_elementform_id_temp");
  div.removeChild(table);
  var scale_table = document.createElement('div');
  scale_table.setAttribute("id", id + "_scale_tableform_id_temp");
  scale_table.style.cssText = "display:inline-table;";
  var tr0 = document.createElement('div');
  tr0.setAttribute("id", id + "_scale_tr1form_id_temp");
  tr0.style.cssText = "display:table-row;";
  var tr1 = document.createElement('div');
  tr1.setAttribute("id", id + "_scale_tr2form_id_temp");
  tr1.style.cssText = "display:table-row;";
  scale_table.appendChild(tr0);
  for (var l = 1; l <= b; l++) {
    adding_num = document.createElement("span");
    adding_num.innerHTML = l;
    adding_td = document.createElement('div');
    adding_td.setAttribute("id", id + "_scale_td1_" + l + "form_id_temp");
    adding_td.style.cssText = 'text-align:center; display:table-cell;';
    adding_td.appendChild(adding_num);
    tr0.appendChild(adding_td);
  }
  for (var k = 1; k <= b; k++) {
    var adding_radio = document.createElement("input");
    adding_radio.setAttribute('id', id + '_scale_radioform_id_temp_' + k);
    adding_radio.setAttribute('name', id + '_scale_radioform_id_temp');
    adding_radio.setAttribute('value', k);
    adding_radio.setAttribute('type', 'radio');
    var adding_td_for_radio = document.createElement("div");
    adding_td_for_radio.setAttribute('id', id + '_scale_td2_' + k + 'form_id_temp');
    adding_td_for_radio.style.cssText = ' display:table-cell;';
    adding_td_for_radio.appendChild(adding_radio);
    tr1.appendChild(adding_td_for_radio);
  }
  scale_table.appendChild(tr1);
  div.insertBefore(scale_table, div.childNodes[1])
  document.getElementById(id + '_scale_amountform_id_temp').value = b;
}

function go_to_type_scale_rating(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_mini_labels = ['Worst', 'Best'];
  type_scale_rating(new_id, 'Scale Rating', '', 'top', 'no', w_mini_labels, '5', 'no', 'wdform_scale_rating', w_attr_name, w_attr_value);
}

function type_scale_rating(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_mini_labels, w_scale_amount, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_scale_rating");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_scale_rating'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_scale_amount(i, w_scale_amount));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_scale_rating'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_scale_rating");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_scale_amount = document.createElement("input");
  adding_scale_amount.setAttribute("type", "hidden");
  adding_scale_amount.setAttribute("value", w_scale_amount);
  adding_scale_amount.setAttribute("id", i + "_scale_amountform_id_temp");
  adding_scale_amount.setAttribute("name", i + "_scale_amountform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");

  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("class", "fm-editable-label");

  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.cssText = "display:" + display_label_div + "; vertical-align:top; width:" + w_field_label_size + "px;";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var div1 = document.createElement('div');
  div1.setAttribute("id", i + "_elementform_id_temp");

  var scale_table = document.createElement('div');
  scale_table.setAttribute("id", i + "_scale_tableform_id_temp");
  scale_table.style.cssText = "display:inline-table;";

  var scale_tr0 = document.createElement('div');
  scale_tr0.setAttribute("id", i + "_scale_tr1form_id_temp");
  scale_tr0.style.display = "table-row";

  var scale_tr1 = document.createElement('div');
  scale_tr1.setAttribute("id", i + "_scale_tr2form_id_temp");
  scale_tr1.style.display = "table-row";

  var br1 = document.createElement('br');
  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var label1 = document.createElement('label');
  label1.setAttribute("class", "mini_label");
  label1.setAttribute("id", i + "_mini_label_worst");
  label1.innerHTML = w_mini_labels[0];
  label1.style.cssText = "position:relative; top:6px; font-size:11px; display:inline-table;";

  var label2 = document.createElement('label');
  label2.setAttribute("class", "mini_label");
  label2.setAttribute("id", i + "_mini_label_best");
  label2.innerHTML = w_mini_labels[1];
  label2.style.cssText = "position:relative; top:6px; font-size:11px; display:inline-table;";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_scale_amount);

  div1.appendChild(label1);
  scale_table.appendChild(scale_tr0);

  for (var l = 1; l <= w_scale_amount; l++) {
    adding_num = document.createElement("span");
    adding_num.innerHTML = l;

    adding_td = document.createElement('td');
    adding_td.setAttribute("id", i + "_scale_td1_" + l + "form_id_temp");
    adding_td.style.cssText = 'text-align:center;';
    adding_td.style.display = "table-cell";

    adding_td.appendChild(adding_num);
    scale_tr0.appendChild(adding_td);
  }

  for (var k = 1; k <= w_scale_amount; k++) {
    var adding_radio = document.createElement("input");
    adding_radio.setAttribute('id', i + '_scale_radioform_id_temp_' + k);
    adding_radio.setAttribute('name', i + '_scale_radioform_id_temp');
    adding_radio.setAttribute('value', k);
    adding_radio.setAttribute('type', 'radio');

    var adding_td_for_radio = document.createElement("div");
    adding_td_for_radio.setAttribute('id', i + '_scale_td2_' + k + 'form_id_temp');
    adding_td_for_radio.style.display = "table-cell";

    adding_td_for_radio.appendChild(adding_radio);
    scale_tr1.appendChild(adding_td_for_radio);
    scale_table.appendChild(scale_tr1);
    div1.appendChild(scale_table);
  }

  scale_table.appendChild(scale_tr1);
  div1.appendChild(scale_table);
  div1.appendChild(label2);

  div_element.appendChild(div1);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br1);
  div.appendChild(div_for_editable_labels);
  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_rating_description );
  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_scale_rating');

  jQuery(function () {
    jQuery("label#" + i + "_mini_label_worst").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var worst = "<input type='text' class='worst' size='6' style='outline:none; border:none; background:none; font-size:11px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(worst);
        jQuery("input.worst").focus();
        jQuery("input.worst").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_worst").text(value);
        });
      }
    });

    jQuery("label#" + i + "_mini_label_best").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var best = "<input type='text' class='best' size='6'  style='outline:none; border:none; background:none; font-size:11px;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(best);
        jQuery("input.best").focus();
        jQuery("input.best").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_best").text(value);
        });
      }
    });
  });
}

function create_slider_minvalue(i, w_field_min_value) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_slider_min_value">Min Value</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_slider_min_value" onKeyPress="return check_isnum_or_minus(event)" onKeyUp="change_slider_min_or_max_value(this.value, ' + i + ', \'form_id_temp\', \'min\')" onChange="change_slider_min_value(this.value, ' + i + ', \'form_id_temp\')" value="' + w_field_min_value + '" />');
  return create_option_container(label, input);
}

function change_slider_min_or_max_value(a,id,form_id, min_or_max) {
  document.getElementById(id + "_element_" + min_or_max + "form_id_temp").innerHTML = a;
}

function change_slider_min_value(a,id,form_id) {
  document.getElementById(id + "_slider_min_valueform_id_temp").value = a;
  if (eval(a) > document.getElementById(id + "_element_valueform_id_temp").innerHTML) {
    document.getElementById(id + "_element_valueform_id_temp").innerHTML = a;
    document.getElementById(id + "_slider_valueform_id_temp").value = a;
    jQuery("#" + id + "_elementform_id_temp").slider({
      min: eval(a),
      slide: function (event, ui) {
        document.getElementById(id + "_element_valueform_id_temp").innerHTML = "" + ui.value;
        document.getElementById(id + "_slider_valueform_id_temp").value = "" + ui.value;
      }
    });
  }
  else {
    jQuery("#" + id + "_elementform_id_temp").slider({
      min: eval(a),
      slide: function (event, ui) {
        document.getElementById(id + "_element_valueform_id_temp").innerHTML = "" + ui.value;
        document.getElementById(id + "_slider_valueform_id_temp").value = "" + ui.value;
      }
    });
  }
}

function create_slider_maxvalue(i, w_field_max_value) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_slider_max_value">Max Value</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_slider_max_value" onKeyPress="return check_isnum_or_minus(event)" onKeyUp="change_slider_min_or_max_value(this.value, ' + i + ', \'form_id_temp\', \'max\')" onChange="change_slider_max_value(this.value, ' + i + ', \'form_id_temp\')" value="' + w_field_max_value + '" />');
  return create_option_container(label, input);
}

function change_slider_max_value(a, id, form_id) {
  document.getElementById(id + "_slider_max_valueform_id_temp").value = a;
  if (eval(a) < parseInt(document.getElementById(id + "_slider_valueform_id_temp").value)) {
    document.getElementById(id + "_element_valueform_id_temp").innerHTML = a;
    document.getElementById(id + "_slider_valueform_id_temp").value = a;
    jQuery("#" + id + "_elementform_id_temp").slider({
      min: eval(document.getElementById(id + "_slider_min_valueform_id_temp").value),
      max: eval(a),
      value: eval(document.getElementById(id + "_slider_valueform_id_temp").value),
      slide: function (event, ui) {
        document.getElementById(id + "_element_valueform_id_temp").innerHTML = "" + ui.value;
        document.getElementById(id + "_slider_valueform_id_temp").value = "" + ui.value;
      }
    });
  }
  else {
    jQuery("#" + id + "_elementform_id_temp").slider({
      min: eval(document.getElementById(id + "_slider_min_valueform_id_temp").value),
      max: eval(a),
      value: eval(document.getElementById(id + "_slider_valueform_id_temp").value),
      slide: function (event, ui) {
        document.getElementById(id + "_element_valueform_id_temp").innerHTML = "" + ui.value;
        document.getElementById(id + "_slider_valueform_id_temp").value = "" + ui.value;
      }
    });
  }
}

function create_slider_step(i, w_field_slider_step) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_slider_step">Step</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_slider_step" onKeyPress="return check_isnum(event)" onChange="change_slider_step(this.value, ' + i + ', \'form_id_temp\')" value="' + w_field_slider_step + '" />');

  return create_option_container(label, input);
}

function change_slider_step(a, id, form_id) {
  jQuery("#" + id + "_elementform_id_temp").slider({step: eval(a)});
  document.getElementById(id + "_slider_stepform_id_temp").value = a;
}

function create_slider_width(i, w_field_width) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_slider_width">Width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_slider_width" onKeyPress="return check_isnum(event)" onKeyUp="change_slider_width(this.value,' + i + ',\'form_id_temp\')" value="' + w_field_width + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function change_slider_width(a, id, form_id) {
  document.getElementById(id + "_elementform_id_temp").style.cssText = "width:" + a + "px";
  document.getElementById(id + "_slider_table2form_id_temp").style.cssText = "width:" + a + "px";
  document.getElementById(id + "_slider_widthform_id_temp").value = a;
}

function go_to_type_slider(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_slider(new_id, 'Slider', '', 'top', 'no', '', '0', '100', '1', '0', 'no', '', w_attr_name, w_attr_value);
}

function type_slider(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_width, w_field_min_value, w_field_max_value, w_field_step, w_field_value, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_slider");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_slider'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_slider_minvalue(i, w_field_min_value));
  edit_main_table.append(create_slider_maxvalue(i, w_field_max_value));
  edit_main_table.append(create_slider_step(i, w_field_step));
  edit_main_table.append(create_slider_width(i, w_field_width));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_slider'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_slider");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_slider_min_value = document.createElement("input");
  adding_slider_min_value.setAttribute("type", "hidden");
  adding_slider_min_value.setAttribute("value", w_field_min_value);
  adding_slider_min_value.setAttribute("id", i + "_slider_min_valueform_id_temp");
  adding_slider_min_value.setAttribute("name", i + "_slider_min_valueform_id_temp");

  var adding_slider_max_value = document.createElement("input");
  adding_slider_max_value.setAttribute("type", "hidden");
  adding_slider_max_value.setAttribute("value", w_field_max_value);
  adding_slider_max_value.setAttribute("id", i + "_slider_max_valueform_id_temp");
  adding_slider_max_value.setAttribute("name", i + "_slider_max_valueform_id_temp");

  var adding_slider_step = document.createElement("input");
  adding_slider_step.setAttribute("type", "hidden");
  adding_slider_step.setAttribute("value", w_field_step);
  adding_slider_step.setAttribute("id", i + "_slider_stepform_id_temp");
  adding_slider_step.setAttribute("name", i + "_slider_stepform_id_temp");

  var adding_slider_value = document.createElement("input");
  adding_slider_value.setAttribute("type", "hidden");
  adding_slider_value.setAttribute("value", w_field_value);
  adding_slider_value.setAttribute("id", i + "_slider_valueform_id_temp");
  adding_slider_value.setAttribute("name", i + "_slider_valueform_id_temp");

  var adding_slider_width = document.createElement("input");
  adding_slider_width.setAttribute("type", "hidden");
  adding_slider_width.setAttribute("value", w_field_width);
  adding_slider_width.setAttribute("name", i + "_slider_widthform_id_temp");
  adding_slider_width.setAttribute("id", i + "_slider_widthform_id_temp");

  var adding_slider_div = document.createElement("div");
  adding_slider_div.style.cssText = "width:" + w_field_width + "px";
  adding_slider_div.setAttribute("name", i + "_elementform_id_temp");
  adding_slider_div.setAttribute("id", i + "_elementform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.cssText = "display:" + display_label_div + "; vertical-align:top; width:" + w_field_label_size + "px;";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var slider_table = document.createElement('div');
  slider_table.setAttribute("id", i + "_slider_tableform_id_temp");

  var slider_tr1 = document.createElement('div');
  var slider_tr2 = document.createElement('div');
  slider_tr2.setAttribute("id", i + "_slider_table2form_id_temp");
  slider_tr2.style.cssText = "width:" + w_field_width + "px";

  var slider_td1 = document.createElement('div');
  slider_td1.setAttribute("id", i + "_slider_td1form_id_temp");

  var slider_td2 = document.createElement('div');
  slider_td2.setAttribute("align", 'left');
  slider_td2.setAttribute("id", i + "_slider_td2form_id_temp");
  slider_td2.style.cssText = "display:inline-table; width:33.3%; text-align:left;";

  var slider_td3 = document.createElement('div');
  slider_td3.setAttribute("align", 'right');
  slider_td3.setAttribute("id", i + "_slider_td3form_id_temp");
  slider_td3.style.cssText = "display:inline-table; width:33.3%; text-align:center;";

  var slider_td4 = document.createElement('div');
  slider_td4.setAttribute("align", 'right');
  slider_td4.setAttribute("id", i + "_slider_td4form_id_temp");
  slider_td4.style.cssText = "display:inline-table; width:33.3%; text-align:right; ";

  var br1 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_slider_width);
  div_element.appendChild(adding_slider_min_value);
  div_element.appendChild(adding_slider_max_value);
  div_element.appendChild(adding_slider_step);
  div_element.appendChild(adding_slider_value);

  var slider_min = document.createElement('span');
  slider_min.setAttribute("id", i + "_element_minform_id_temp");
  slider_min.innerHTML = w_field_min_value;
  slider_min.setAttribute("class", "label");

  var slider_max = document.createElement('span');
  slider_max.setAttribute("id", i + "_element_maxform_id_temp");
  slider_max.innerHTML = w_field_max_value;
  slider_max.setAttribute("class", "label");

  var slider_value = document.createElement('span');
  slider_value.setAttribute("id", i + "_element_valueform_id_temp");
  slider_value.innerHTML = w_field_value;
  slider_value.setAttribute("class", "label");

  slider_td1.appendChild(adding_slider_div);
  slider_tr1.appendChild(slider_td1);
  slider_table.appendChild(slider_tr1);

  slider_td2.appendChild(slider_min);
  slider_tr2.appendChild(slider_td2);
  slider_table.appendChild(slider_tr2);

  slider_td3.appendChild(slider_value);
  slider_tr2.appendChild(slider_td3);
  slider_table.appendChild(slider_tr2);

  slider_td4.appendChild(slider_max);
  slider_tr2.appendChild(slider_td4);
  slider_table.appendChild(slider_tr2);

  div_element.appendChild(slider_table);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br1);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_slider_description );
  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_slider');
  jQuery("#" + i + "_elementform_id_temp")[0].slide = null;

  jQuery(function () {
    jQuery("#" + i + "_elementform_id_temp").slider({
      step: eval(w_field_step),
      range: "min",
      value: eval(w_field_value),
      min: eval(w_field_min_value),
      max: eval(w_field_max_value),
      slide: function (event, ui) {
        document.getElementById(i + "_element_valueform_id_temp").innerHTML = "" + ui.value;
        document.getElementById(i + "_slider_valueform_id_temp").value = "" + ui.value;
      }
    });
  });
}

function create_range_step(i, w_field_range_step) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_spinner_step">Step</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_spinner_step" onKeyPress="return check_isnum(event)" onChange="change_range_step(this.value, ' + i + ', \'form_id_temp\')" value="' + w_field_range_step + '" />');
  return create_option_container(label, input);
}

function change_range_step(a, id, form_id) {
  jQuery("#" + id + "_elementform_id_temp0").spinner({step: a});
  jQuery("#" + id + "_elementform_id_temp1").spinner({step: a});
  document.getElementById(id + "_range_stepform_id_temp").value = a;
}

function create_range_width(i, w_field_range_width) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_spinner_width">Width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_spinner_width" onKeyPress="return check_isnum(event)" onKeyUp="change_range_width(this.value, ' + i + ', \'form_id_temp\')" value="' + w_field_range_width + '" />');
  return create_option_container(label, input);
}

function go_to_type_range(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_mini_labels = ['From', 'To'];
  type_range(new_id, 'Range', '', 'top', 'no', '70', '1', '', '', w_mini_labels, 'no', '', w_attr_name, w_attr_value);
}

function type_range(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_range_width, w_field_range_step, w_field_value1, w_field_value2, w_mini_labels, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_range");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_range'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_range_step(i, w_field_range_step));
  edit_main_table.append(create_range_width(i, w_field_range_width));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_range'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_range");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_width = document.createElement("input");
  adding_width.setAttribute("type", "hidden");
  adding_width.setAttribute("value", w_field_range_width);
  adding_width.setAttribute("name", i + "_range_widthform_id_temp");
  adding_width.setAttribute("id", i + "_range_widthform_id_temp");

  var adding_step = document.createElement("input");
  adding_step.setAttribute("type", "hidden");
  adding_step.setAttribute("value", w_field_range_step);
  adding_step.setAttribute("name", i + "_range_stepform_id_temp");
  adding_step.setAttribute("id", i + "_range_stepform_id_temp");

  var adding_range_input_from = document.createElement("input");
  adding_range_input_from.setAttribute("type", "");
  adding_range_input_from.setAttribute("value", w_field_value1);
  adding_range_input_from.style.cssText = "width:" + w_field_range_width + "px";
  adding_range_input_from.setAttribute("name", i + "_elementform_id_temp0");
  adding_range_input_from.setAttribute("id", i + "_elementform_id_temp0");
  adding_range_input_from.setAttribute("onKeyPress", "return check_isnum_or_minus(event)");

  var adding_range_input_to = document.createElement("input");
  adding_range_input_to.setAttribute("type", "");
  adding_range_input_to.setAttribute("value", w_field_value2);
  adding_range_input_to.style.cssText = "width:" + w_field_range_width + "px";
  adding_range_input_to.setAttribute("name", i + "_elementform_id_temp1");
  adding_range_input_to.setAttribute("id", i + "_elementform_id_temp1");
  adding_range_input_to.setAttribute("onKeyPress", "return check_isnum_or_minus(event)");

  var adding_range_label_from = document.createElement("label");
  adding_range_label_from.setAttribute("class", "mini_label mini_label_from");
  adding_range_label_from.setAttribute("id", i + "_mini_label_from");
  adding_range_label_from.innerHTML = w_mini_labels[0];

  var adding_range_label_to = document.createElement("label");
  adding_range_label_to.setAttribute("class", "mini_label mini_label_to");
  adding_range_label_to.setAttribute("id", i + "_mini_label_to");
  adding_range_label_to.innerHTML = w_mini_labels[1];

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");
  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("class", "fm-editable-label");

  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_elemet_table_littleform_id_temp");
  table_little.style.display = "table";

  var tr1 = document.createElement('div');
  tr1.style.display = "table-row";
  var tr2 = document.createElement('div');
  tr2.style.display = "table-row";

  var td1_1 = document.createElement('div');
  td1_1.setAttribute("valign", 'middle');
  td1_1.setAttribute("align", 'left');
  td1_1.style.display = "table-cell";

  var td1_2 = document.createElement('div');
  td1_2.setAttribute("valign", 'middle');
  td1_2.setAttribute("align", 'left');
  td1_2.style.cssText = "display:table-cell; padding-left:4px;";

  var td2_1 = document.createElement('div');
  td2_1.setAttribute("valign", 'top');
  td2_1.setAttribute("align", 'left');
  td2_1.style.display = "table-cell";

  var td2_2 = document.createElement('div');
  td2_2.setAttribute("valign", 'top');
  td2_2.setAttribute("align", 'left');
  td2_2.style.display = "table-cell";

  var br1 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_width);
  div_element.appendChild(adding_step);

  td1_1.appendChild(adding_range_input_from);
  td1_2.appendChild(adding_range_input_to);
  td2_1.appendChild(adding_range_label_from);
  td2_2.appendChild(adding_range_label_to);

  tr1.appendChild(td1_1);
  tr1.appendChild(td1_2);
  tr2.appendChild(td2_1);
  tr2.appendChild(td2_2);

  table_little.appendChild(tr1);
  table_little.appendChild(tr2);

  div_element.appendChild(table_little);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br1);
  div.appendChild(div_for_editable_labels);

  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_range_description );
  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_range');

  jQuery("#" + i + "_elementform_id_temp0").spinner();
  var spinner1 = jQuery("#" + i + "_elementform_id_temp0").spinner();
  /*spinner1.spinner("value", w_field_value1);*/
  jQuery("#" + i + "_elementform_id_temp0").spinner({step: w_field_range_step});

  jQuery("#" + i + "_elementform_id_temp1").spinner();
  var spinner2 = jQuery("#" + i + "_elementform_id_temp1").spinner();
  /*spinner2.spinner("value", w_field_value2);*/
  jQuery("#" + i + "_elementform_id_temp1").spinner({step: w_field_range_step});

  jQuery(function () {
    jQuery("label#" + i + "_mini_label_from").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var form = "<input type='text' class='form' size='8' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(form);
        jQuery("input.form").focus();
        jQuery("input.form").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_from").text(value);
        });
      }
    });

    jQuery("label#" + i + "_mini_label_to").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var to = "<input type='text' class='to' size='8' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(to);
        jQuery("input.to").focus();
        jQuery("input.to").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_to").text(value);
        });
      }
    });
  });
}

function create_hidden_name(i, w_name) {
  var label = jQuery('<label class="fm-field-label" for="el_hidden_name">Name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_hidden_name" onChange="change_field_name(' + i + ', this)" value="' + w_name + '" />');
  return create_option_container(label, input);
}

function change_field_name(id, x) {
  value = x.value;
  if (value == parseInt(value)) {
    alert('The name of the field cannot be a number.');
    x.value = "";
    document.getElementById(id + '_elementform_id_temp').name = '';
    document.getElementById(id + '_element_labelform_id_temp').innerHTML = '';
    document.getElementById(id + '_hidden_nameform_id_temp').innerHTML = '';
    return;
  }
  if (value == id + "_elementform_id_temp") {
    alert('"Field Name" should differ from "Field Id".')
    x.value = "";
  }
  else {
    document.getElementById(id + '_elementform_id_temp').name = value;
    document.getElementById(id + '_element_labelform_id_temp').innerHTML = value;
    document.getElementById(id + '_hidden_nameform_id_temp').innerHTML = value;
  }
}

function create_hidden_value(i, w_value) {
  var label = jQuery('<label class="fm-field-label" for="el_hidden_value">Value</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_hidden_value" onChange="change_field_value(' + i + ', this.value)" value="' + w_value + '" /><span class="dashicons dashicons-list-view" data-id="el_hidden_value"></span>');
  return create_option_container(label, input);
}

function change_field_value(id, value) {
  document.getElementById(id + '_elementform_id_temp').value = value;
  document.getElementById(id + '_hidden_valueform_id_temp').innerHTML = value;
}

function go_to_type_hidden(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_hidden(new_id, '', '', '', w_attr_name, w_attr_value);
}

function type_hidden(i, w_name, w_value, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_hidden");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table" class="js"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_hidden'));
  edit_main_table.append(create_hidden_name(i, w_name));
  edit_main_table.append(create_hidden_value(i, w_value));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_hidden'));

  // Preview
  element = 'input';
  type = 'hidden';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_hidden");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", type);
  adding.setAttribute("value", w_value);
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", w_name);

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = "table-cell";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.cssText = 'display:table-cell; padding-left: 7px;';
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.style.cssText = 'display:none';
  label.innerHTML = w_name;

  var label_hidden = document.createElement('span');
  label_hidden.style.cssText = 'color:red; font-size:13px;';
  label_hidden.innerHTML = 'Hidden field';

  var div_hidden_name = document.createElement('div');

  var span_hidden_name_label = document.createElement('span');
  span_hidden_name_label.setAttribute("align", 'left');
  span_hidden_name_label.innerHTML = 'Name: ';

  var span_hidden_name = document.createElement('span');
  span_hidden_name.setAttribute("align", 'left');
  span_hidden_name.innerHTML = w_name;
  span_hidden_name.setAttribute("id", i + "_hidden_nameform_id_temp");

  var div_hidden_value = document.createElement('div');

  var span_hidden_value_label = document.createElement('span');
  span_hidden_value_label.setAttribute("align", 'left');
  span_hidden_value_label.innerHTML = 'Value: ';

  var span_hidden_value = document.createElement('span');
  span_hidden_value.setAttribute("align", 'left');
  span_hidden_value.innerHTML = w_value;
  span_hidden_value.setAttribute("id", i + "_hidden_valueform_id_temp");

  div_hidden_name.appendChild(span_hidden_name_label);
  div_hidden_name.appendChild(span_hidden_name);
  div_hidden_value.appendChild(span_hidden_value_label);
  div_hidden_value.appendChild(span_hidden_value);

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(label_hidden);
  div_element.appendChild(adding);
  div_element.appendChild(adding_type);
  div_element.appendChild(div_hidden_name);
  div_element.appendChild(div_hidden_value);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_hidden_description );
  refresh_attr(i, 'type_text');
}

function create_captcha_digits(i, w_digit) {
  var label = jQuery('<label class="fm-field-label" for="captcha_digit">Symbols (3 - 9)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="captcha_digit" onKeyPress="return check_isnum_3_10(event)" onKeyUp="change_captcha_digit(this.value, ' + i + ')" value="' + w_digit + '" />');
  return create_option_container(label, input);
}

function check_isnum_3_10(e) {
  var chCode1 = e.which || e.keyCode;
  if (chCode1 > 31 && (chCode1 < 51 || chCode1 > 57)) {
    return false;
  }
  else if ((document.getElementById('captcha_digit').value + (chCode1 - 48)) > 9) {
    return false;
  }
  return true;
}

function go_to_type_captcha(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_captcha(new_id, 'Word Verification', '', 'top', 'yes', '6', '', w_attr_name, w_attr_value);
}

function type_captcha(i,w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_digit, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_captcha");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_captcha'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_captcha_digits(i, w_digit));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_captcha'));

  // Preview
  element = 'img';
  type = 'captcha';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_captcha");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", type);
  adding.setAttribute("digit", w_digit);
  adding.setAttribute("src", url_for_ajax + "?action=formmakerwdcaptcha&digit=" + w_digit + "&i=form_id_temp");
  adding.setAttribute("id", "_wd_captchaform_id_temp");
  adding.setAttribute("class", "captcha_img");
  adding.setAttribute("onClick", "captcha_refresh('_wd_captcha','form_id_temp')");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var refresh_captcha = document.createElement("div");
  refresh_captcha.setAttribute("class", "captcha_refresh");
  refresh_captcha.setAttribute("id", "_element_refreshform_id_temp");
  refresh_captcha.setAttribute("onClick", "captcha_refresh('_wd_captcha','form_id_temp')");

  var input_captcha = document.createElement("input");
  input_captcha.setAttribute("type", "text");
  input_captcha.style.cssText = "width:" + (w_digit * 10 + 15) + "px;";
  input_captcha.setAttribute("class", "captcha_input");
  input_captcha.setAttribute("id", "_wd_captcha_inputform_id_temp");
  input_captcha.setAttribute("name", "captcha_input");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var captcha_table = document.createElement('div');
  captcha_table.style.display = "table";

  var captcha_tr1 = document.createElement('div');
  captcha_tr1.style.display = "table-row";
  var captcha_tr2 = document.createElement('div');
  captcha_tr2.style.display = "table-row";

  var captcha_td1 = document.createElement('div');
  captcha_td1.setAttribute("valign", 'middle');
  captcha_td1.style.display = "table-cell";

  var captcha_td2 = document.createElement('div');
  captcha_td2.setAttribute("valign", 'middle');
  captcha_td2.style.display = "table-cell";
  var captcha_td3 = document.createElement('div');
  captcha_td3.style.display = "table-cell";

  captcha_table.appendChild(captcha_tr1);
  captcha_table.appendChild(captcha_tr2);
  captcha_tr1.appendChild(captcha_td1);
  captcha_tr1.appendChild(captcha_td2);
  captcha_tr2.appendChild(captcha_td3);

  captcha_td1.appendChild(adding);
  captcha_td2.appendChild(refresh_captcha);
  captcha_td3.appendChild(input_captcha);

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(captcha_table);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_captcha_description );
  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_captcha');
}

function create_arithmetic_operations(i, w_operations) {
  var label = jQuery('<label class="fm-field-label" for="el_operations">Operations</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_operations" onKeyPress="return check_is_operation_valid(event)" onChange="change_arithmetic_captcha(this.value, \'operations\')" value="' + w_operations + '" />');
  return create_option_container(label, input);
}

function check_is_operation_valid(e) {
  var chCode1 = e.which || e.keyCode;
  if (chCode1 == 46 || chCode1 < 42 || chCode1 > 47) {
    return false;
  }
  return true;
}

function change_arithmetic_captcha(value, field) {
  arithmetic_captcha = document.getElementById('_wd_arithmetic_captchaform_id_temp');
  if (field == 'oper_count') {
    oper_count = value ? value : 1;
    operations = document.getElementById('el_operations') ? document.getElementById('el_operations').value : '+, -, *, /';
  }
  else if (field == 'el_operations') {
    operations = value ? value : '+, -, *, /';
    oper_count = document.getElementById('el_oper_count') ? document.getElementById('el_oper_count').value : 1;
  }
  else {
    operations = document.getElementById('el_operations') ? document.getElementById('el_operations').value : '+, -, *, /';
    oper_count = document.getElementById('el_oper_count') ? document.getElementById('el_oper_count').value : 1;
  }
  input_size = document.getElementById('el_captcha_input_size') ? document.getElementById('el_captcha_input_size').value : 60;
  arithmetic_captcha.setAttribute("operations_count", oper_count);
  arithmetic_captcha.setAttribute("operations", operations);
  arithmetic_captcha.setAttribute("input_size", input_size);
  arithmetic_captcha.setAttribute("src", url_for_ajax + "?action=formmakerwdmathcaptcha&operations_count=" + oper_count + "&nonce=" + fm_ajax.ajaxnonce + "&operations=" + operations.replace('+', '@') + "&i=form_id_temp");
}

function create_arithmetic_operations_count(i, w_count) {
  var label = jQuery('<label class="fm-field-label" for="el_oper_count">Operations count (1 - 5)</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_oper_count" onKeyPress="return check_isnum_less_then_5(event)" onChange="change_arithmetic_captcha(this.value, \'oper_count\')" value="' + w_count + '" />');
  return create_option_container(label, input);
}

function check_isnum_less_then_5(e) {
  var chCode1 = e.which || e.keyCode;
  if (chCode1 > 31 && (chCode1 < 49 || chCode1 > 57)) {
    return false;
  }
  else if ((document.getElementById('el_oper_count').value + (chCode1 - 48)) > 5) {
    return false;
  }
  return true;
}

function create_arithmetic_width(i, w_input_size) {
  var label = jQuery('<label class="fm-field-label" for="el_captcha_input_size">Input width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="el_captcha_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'_wd_arithmetic_captcha_inputform_id_temp\', this.value); change_arithmetic_captcha();" value="' + w_input_size + '" />');
  return create_option_container(label, input);
}

function go_to_type_arithmetic_captcha(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  type_arithmetic_captcha(new_id, 'Word Verification', '', 'top', 'yes', '1', '+, -, *, /', '', '60', w_attr_name, w_attr_value);
}

function type_arithmetic_captcha(i,w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_count, w_operations, w_class, w_input_size, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_arithmetic_captcha");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_arithmetic_captcha'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_arithmetic_operations(i, w_operations));
  edit_main_table.append(create_arithmetic_operations_count(i, w_count));
  edit_main_table.append(create_arithmetic_width(i, w_input_size));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_arithmetic_captcha'));

  // Preview
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_arithmetic_captcha");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding = document.createElement('img');
  adding.setAttribute("type", 'captcha');
  adding.setAttribute("operations_count", w_count);
  adding.setAttribute("operations", w_operations);
  adding.setAttribute("input_size", w_input_size);
  adding.setAttribute("src", url_for_ajax + "?action=formmakerwdmathcaptcha&operations_count=" + w_count + "&operations=" + w_operations.replace("+", "@") + "&i=form_id_temp");
  adding.setAttribute("id", "_wd_arithmetic_captchaform_id_temp");
  adding.setAttribute("class", "arithmetic_captcha_img");
  adding.setAttribute("onClick", "captcha_refresh('_wd_arithmetic_captcha','form_id_temp')");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var refresh_captcha = document.createElement("div");
  refresh_captcha.setAttribute("class", "captcha_refresh");
  refresh_captcha.setAttribute("id", "_element_refreshform_id_temp");
  refresh_captcha.setAttribute("onClick", "captcha_refresh('_wd_arithmetic_captcha','form_id_temp')");

  var input_captcha = document.createElement("input");
  input_captcha.setAttribute("type", "text");
  input_captcha.style.cssText = "width:" + w_input_size + "px;";
  input_captcha.setAttribute("class", "arithmetic_captcha_input");
  input_captcha.setAttribute("id", "_wd_arithmetic_captcha_inputform_id_temp");
  input_captcha.setAttribute("name", "arithmetic_captcha_input");
  input_captcha.setAttribute("onKeyPress", "return check_isnum(event)");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var captcha_table = document.createElement('div');
  captcha_table.style.display = "table";

  var captcha_tr1 = document.createElement('div');
  captcha_tr1.style.display = "table-row";
  var captcha_tr2 = document.createElement('div');
  captcha_tr2.style.display = "table-row";

  var captcha_td1 = document.createElement('div');
  captcha_td1.style.display = "table-cell";

  var captcha_td2 = document.createElement('div');
  captcha_td2.style.cssText = "display:table-cell; vertical-align:middle;";
  var captcha_td3 = document.createElement('div');
  captcha_td3.style.display = "table-cell";

  captcha_table.appendChild(captcha_tr1);
  captcha_table.appendChild(captcha_tr2);
  captcha_tr1.appendChild(captcha_td1);
  captcha_tr1.appendChild(captcha_td3);
  captcha_tr1.appendChild(captcha_td2);
  captcha_td1.appendChild(adding);
  captcha_td2.appendChild(refresh_captcha);
  captcha_td3.appendChild(input_captcha);

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(captcha_table);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_arithmetic_captcha_description );

  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_arithmetic_captcha');
}

function create_field_size_phone(i, w_size, fields_count) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_input_size">Width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_input_size" onKeyPress="return check_isnum(event)" onKeyUp="change_w_style(\'' + i + '_element_lastform_id_temp\', this.value)" value="' + w_size + '" /><p class="description">' + form_maker.leave_empty + '</p>');
  return create_option_container(label, input);
}

function create_placeholder_phone(i, w_title) {
  var label = jQuery('<label class="fm-field-label" for="el_first_value_area">Placeholder</label>');
  var input = jQuery('<input type="text" class="fm-width-20" id="el_first_value_area" onKeyUp="change_input_value(this.value,\'' + i + '_element_firstform_id_temp\')" value="' + w_title[0].replace(/"/g, "&quot;") + '" />-<input type="text" class="fm-width-60" id="el_first_value_phone" onKeyUp="change_input_value(this.value,\'' + i + '_element_lastform_id_temp\')" value="' + w_title[1].replace(/"/g, "&quot;") + '" />');
  return create_option_container(label, input);
}

function go_to_type_phone(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_first_val = ['', ''];
  w_title = ['', ''];
  w_mini_labels = ['Area Code', 'Phone Number'];
  type_phone(new_id, 'Phone-Area Code', '', 'top', 'no', '', w_first_val, w_title, w_mini_labels, 'no', 'no', '', w_attr_name, w_attr_value);
}

function type_phone(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_mini_labels, w_required, w_unique, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_phone");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_phone'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size_phone(i, w_size));
  edit_main_table.append(create_placeholder_phone(i, w_title));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_unique_values(i, w_unique));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_phone'));

  // Preview.
  var br1 = document.createElement('br');
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_phone");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");

  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_for_editable_labels = document.createElement('div');
  div_for_editable_labels.setAttribute("class", "fm-editable-label");

  edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");

  div_for_editable_labels.appendChild(edit_labels);

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var table_name = document.createElement('div');
  table_name.style.display = "table";
  table_name.setAttribute("id", i + "_table_name");

  var tr_name1 = document.createElement('div');
  tr_name1.style.display = "table-row";
  tr_name1.setAttribute("id", i + "_tr_name1");

  var tr_name2 = document.createElement('div');
  tr_name2.style.display = "table-row";
  tr_name2.setAttribute("id", i + "_tr_name2");

  var td_name_input1 = document.createElement('div');
  td_name_input1.style.display = "table-cell";
  td_name_input1.setAttribute("id", i + "_td_name_input_first");

  var td_name_input2 = document.createElement('div');
  td_name_input2.style.display = "table-cell";
  td_name_input2.setAttribute("id", i + "_td_name_input_last");

  var td_name_label1 = document.createElement('div');
  td_name_label1.style.display = "table-cell";
  td_name_label1.setAttribute("id", i + "_td_name_label_first");
  td_name_label1.setAttribute("align", "left");

  var td_name_label2 = document.createElement('div');
  td_name_label2.style.display = "table-cell";
  td_name_label2.setAttribute("id", i + "_td_name_label_last");
  td_name_label2.setAttribute("align", "left");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";

  var first = document.createElement('input');
  first.setAttribute("type", 'text');
  first.style.cssText = "width:50px";
  first.setAttribute("id", i + "_element_firstform_id_temp");
  first.setAttribute("name", i + "_element_firstform_id_temp");
  first.setAttribute("value", w_first_val[0]);
  first.setAttribute("title", w_title[0]);
  first.setAttribute("placeholder", w_title[0]);
  first.setAttribute("onKeyPress", "return check_isnum(event)");

  var gic = document.createElement('span');
  gic.setAttribute("class", "wdform_line");
  gic.style.cssText = "margin: 0px 4px 0px 4px; padding: 0px;";
  gic.innerHTML = "-";

  var first_label = document.createElement('label');
  first_label.setAttribute("class", "mini_label mini_label_area_code");
  first_label.setAttribute("id", i + "_mini_label_area_code");
  first_label.innerHTML = w_mini_labels[0];

  var last = document.createElement('input');
  last.setAttribute("type", 'text');
  last.style.cssText = "width:" + w_size + "px";
  last.setAttribute("id", i + "_element_lastform_id_temp");
  last.setAttribute("name", i + "_element_lastform_id_temp");
  last.setAttribute("value", w_first_val[1]);
  last.setAttribute("title", w_title[1]);
  last.setAttribute("placeholder", w_title[1]);
  last.setAttribute("onKeyPress", "return check_isnum(event)");

  var last_label = document.createElement('label');
  last_label.setAttribute("class", "mini_label mini_label_phone_number");
  last_label.setAttribute("id", i + "_mini_label_phone_number");
  last_label.innerHTML = w_mini_labels[1];

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);

  td_name_input1.appendChild(first);
  td_name_input1.appendChild(gic);
  td_name_input2.appendChild(last);
  tr_name1.appendChild(td_name_input1);
  tr_name1.appendChild(td_name_input2);
  td_name_label1.appendChild(first_label);
  td_name_label2.appendChild(last_label);
  tr_name2.appendChild(td_name_label1);
  tr_name2.appendChild(td_name_label2);
  table_name.appendChild(tr_name1);
  table_name.appendChild(tr_name2);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_unique);
  div_element.appendChild(table_name);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div.appendChild(div_field);
  div.appendChild(br1);
  div.appendChild(div_for_editable_labels);
  main_td.appendChild(div);
  jQuery("#main_div").append( '<br>'+form_maker.type_phone_area_code_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_name');

  jQuery(function () {
    jQuery("label#" + i + "_mini_label_area_code").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var area_code = "<input type='text' class='area_code' size='10' style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(area_code);
        jQuery("input.area_code").focus();
        jQuery("input.area_code").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_area_code").text(value);
        });
      }
    });

    jQuery("label#" + i + "_mini_label_phone_number").click(function () {
      if (jQuery(this).children('input').length == 0) {
        var phone_number = "<input type='text' class='phone_number'  style='outline:none; border:none; background:none;' value=\"" + jQuery(this).text() + "\">";
        jQuery(this).html(phone_number);
        jQuery("input.phone_number").focus();
        jQuery("input.phone_number").blur(function () {
          var value = jQuery(this).val();
          jQuery("#" + i + "_mini_label_phone_number").text(value);
        });
      }
    });
  });
}

function create_confirmation_password(i, w_verification) {
  var label = jQuery('<label class="fm-field-label" for="el_verification_password">Password Confirmation</label>');
  var input = jQuery('<input type="checkbox" id="el_verification_password" onclick="verification_password(' + i + ')"' + (w_verification == 'yes' ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function verification_password(id) {
  if (document.getElementById("el_verification_password").checked) {
    document.getElementById('confirm_validation_label').style.display = "block";
    document.getElementById(id + "_verification_id_temp").value = "yes";
    document.getElementById(id + "_1_label_sectionform_id_temp").style.display = document.getElementById(id + "_label_sectionform_id_temp").style.display;
    document.getElementById(id + "_1_element_sectionform_id_temp").style.display = document.getElementById(id + "_element_sectionform_id_temp").style.display;
  }
  else {
    document.getElementById('confirm_validation_label').style.display = "none";
    document.getElementById(id + "_verification_id_temp").value = "no";
    document.getElementById(id + "_1_label_sectionform_id_temp").style.display = "none";
    document.getElementById(id + "_1_element_sectionform_id_temp").style.display = "none";
  }
}

function create_confirmation_password_label(i, w_verification, w_verification_label) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_label">Confirmation label</label>');
  var input = jQuery('<textarea id="edit_for_label" class="fm-width-100" onKeyUp="change_label(\'' + i + '_element_labelform_id_temp\', this.value, \'' + i + '_1_element_labelform_id_temp\')" rows="4">' + w_verification_label + '</textarea>');
  return create_option_container(label, input, 'confirm_validation_label', w_verification == 'yes');
}

function go_to_type_password(new_id) {
  w_placeholder_value = '';
  w_attr_name = [];
  w_attr_value = [];
  type_password(new_id, 'Password', '', 'top', 'no', '', 'no', 'no', 'wdform_input', 'no', 'Password confirmation', w_placeholder_value, w_attr_name, w_attr_value);
}

function type_password(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_required, w_unique, w_class, w_verification, w_verification_label, w_placeholder_value, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_password");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_password'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_field_size(i, w_size, '\'' + i + '_elementform_id_temp\'', '\'' + i + '_1_elementform_id_temp\''));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_confirmation_password(i, w_verification));
  advanced_options_container.append(create_confirmation_password_label(i, w_verification, w_verification_label));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size, '\'' + i + '_label_sectionform_id_temp\'', '\'' + i + '_1_label_sectionform_id_temp\''));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_password'));

  // Preview.
  element = 'input';
  type = 'password';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_password");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_unique = document.createElement("input");
  adding_unique.setAttribute("type", "hidden");
  adding_unique.setAttribute("value", w_unique);
  adding_unique.setAttribute("name", i + "_uniqueform_id_temp");
  adding_unique.setAttribute("id", i + "_uniqueform_id_temp");

  var adding = document.createElement(element);
  adding.setAttribute("type", type);
  adding.setAttribute("autocomplete", "new-password");
  adding.setAttribute("id", i + "_elementform_id_temp");
  adding.setAttribute("name", i + "_elementform_id_temp");
  adding.setAttribute("placeholder", w_placeholder_value);
  adding.style.cssText = "width:" + w_size + "px;";

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  /////////////////////////     confirm password       ///////////////////////////////

  var adding_verification = document.createElement("input");
  adding_verification.setAttribute("type", "hidden");
  adding_verification.setAttribute("value", w_verification);
  adding_verification.setAttribute("name", i + "_verification_id_temp");
  adding_verification.setAttribute("id", i + "_verification_id_temp");

  var adding_verification_input = document.createElement(element);
  adding_verification_input.setAttribute("type", type);
  adding_verification_input.setAttribute("autocomplete", "new-password");
  adding_verification_input.style.cssText = "width:" + w_size + "px;";
  adding_verification_input.setAttribute("id", i + "_1_elementform_id_temp");
  adding_verification_input.setAttribute("name", i + "_1_elementform_id_temp");

  var display_label_div_verification = ((w_hide_label == "yes" || w_verification == "no") ? "none" : "table-cell");
  var div_label_verification = document.createElement('div');
  div_label_verification.setAttribute("align", 'left');
  div_label_verification.style.display = display_label_div_verification;
  div_label_verification.style.width = w_field_label_size + "px";
  div_label_verification.setAttribute("id", i + "_1_label_sectionform_id_temp");

  var display_element_verification = (w_verification == "no" ? "none" : "table-cell");
  var div_element_verification = document.createElement("div");
  div_element_verification.setAttribute("align", "left");
  div_element_verification.style.display = display_element_verification;
  div_element_verification.setAttribute("id", i + "_1_element_sectionform_id_temp");

  var label_verification = document.createElement('span');
  label_verification.setAttribute("id", i + "_1_element_labelform_id_temp");
  label_verification.innerHTML = w_verification_label;
  label_verification.setAttribute("class", "label");
  label_verification.style.verticalAlign = "top";

  var required_confirm = document.createElement('span');
  required_confirm.setAttribute("id", i + "_1_required_elementform_id_temp");
  required_confirm.innerHTML = "";
  required_confirm.setAttribute("class", "required");
  required_confirm.style.verticalAlign = "top";
  if (w_required == "yes")
    required_confirm.innerHTML = " *";

  div_label_verification.appendChild(label_verification);
  div_label_verification.appendChild(required_confirm);

  ///////////////////////////////// END  Confirm Password///////////////////////////////////////////

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + "px";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");
  label.style.verticalAlign = "top";

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  required.style.verticalAlign = "top";
  if (w_required == "yes")
    required.innerHTML = " *";
  var main_td = document.getElementById('show_table');

  var br5 = document.createElement('br');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_verification);
  div_element.appendChild(adding_unique);
  div_element.appendChild(adding);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);
  div_element_verification.appendChild(adding_verification_input);
  div_field.appendChild(br5);
  div_field.appendChild(div_label_verification);
  div_field.appendChild(div_element_verification);

  div.appendChild(div_field);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_password_description );
  if (w_field_label_pos == "top")
    label_top(i);
  change_class(w_class, i);
  refresh_attr(i, 'type_text');
}

function create_custom_button_add(i, w_title, w_func) {
  var label = jQuery('<label class="fm-field-label">Add a new button</label>');
  var button_add = jQuery('<span class="fm-add-attribute dashicons dashicons-plus-alt" title="Add" onClick="add_button(' + i + ')"></span>');
  var buttons = jQuery('<div id="buttons" class="fm-width-100"></div>');
  n = w_title.length;
  for (j = 0; j < n; j++) {
    var button = jQuery('<div class="fm-width-100 fm-fields-set" id="button_opt' + j + '" idi="' + j + '"></div>');
    var marker_body = jQuery('<div class="fm-width-90"></div>');
    marker_body.append(create_custom_button_name(i, j, w_title[j]));
    marker_body.append(create_custom_button_function(i, j, w_func[j]));
    button.append(marker_body);
    var marker_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_button(' + j + ', ' + i + ')"></span></div>');
    button.append(marker_remove);
    buttons.append(button);
  }

  var input = label;
  input = input.add(button_add);
  input = input.add(buttons);
  return create_option_container(null, input);
}

function create_custom_button_name(i, j, w_title) {
  var label = jQuery('<label class="fm-field-label" for="el_title' + j + '">Name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_title' + j + '" onChange="change_label(\'' + i + '_elementform_id_temp' + j + '\', this.value)" value="' + w_title + '" />');
  return create_option_container(label, input);
}

function create_custom_button_function(i, j, w_func) {
  var label = jQuery('<label class="fm-field-label" for="el_func' + j + '">OnClick</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_func' + j + '" onChange="change_func(\'' + i + '_elementform_id_temp' + j + '\', this.value)" value="' + w_func + '" />');
  return create_option_container(label, input);
}

function add_button(i) {
  var buttons = jQuery('#buttons');
  var last_child = buttons.children().last();
  if (last_child.length > 0) {
    j = parseInt(last_child.prop("idi")) + 1;
  }
  else {
    j = 0;
  }

  var button = jQuery('<div class="fm-width-100 fm-fields-set" id="button_opt' + j + '" idi="' + j + '"></div>');
  var marker_body = jQuery('<div class="fm-width-90"></div>');
  marker_body.append(create_custom_button_name(i, j, 'Button'));
  marker_body.append(create_custom_button_function(i, j, ''));
  button.append(marker_body);
  var marker_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_button(' + j + ', ' + i + ')"></span></div>');
  button.append(marker_remove);
  buttons.append(button);

  // Preview
  element = 'button';
  type = 'button';

  td2 = document.getElementById(i + "_element_sectionform_id_temp");
  var adding = document.createElement(element);
  adding.setAttribute("type", type);
  adding.setAttribute("id", i + "_elementform_id_temp" + j);
  adding.setAttribute("name", i + "_elementform_id_temp" + j);
  adding.setAttribute("value", "Button");
  adding.innerHTML = "Button";
  adding.setAttribute("onclick", "");

  td2.appendChild(adding);
  refresh_attr(i, 'type_checkbox');
}

function remove_button(j, i) {
  table = document.getElementById('button_opt' + j);
  button = document.getElementById(i + '_elementform_id_temp' + j);
  table.parentNode.removeChild(table);
  button.parentNode.removeChild(button);
}

function go_to_type_button(new_id) {
  w_title = ["Button"];
  w_func = [""];
  w_attr_name = [];
  w_attr_value = [];
  type_button(new_id, w_title, w_func, 'wdform_button', w_attr_name, w_attr_value);
}

function type_button(i, w_title , w_func , w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_button");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_button'));
  edit_main_table.append(create_custom_button_add(i, w_title, w_func));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_button'));

  // Preview.
  element = 'button';
  type = 'button';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_button");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");
//tbody sarqac

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = "table-cell";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');
  //	table_little -@ sarqaca tbody table_little darela table_little_t
  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = "button_" + i;
  label.style.cssText = 'display:none';

  n = w_title.length;
  for (j = 0; j < n; j++) {

    var adding = document.createElement(element);
    adding.setAttribute("type", type);
    adding.setAttribute("id", i + "_elementform_id_temp" + j);
	adding.setAttribute("class", "button button-secondary button-large");
    adding.setAttribute("name", i + "_elementform_id_temp" + j);
    adding.setAttribute("value", w_title[j]);
    adding.innerHTML = w_title[j];
    adding.setAttribute("onclick", w_func[j]);

    div_element.appendChild(adding);
  }
  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);

  div_element.appendChild(adding_type);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br1);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_button_description );
  change_class(w_class, i);
  refresh_attr(i, 'type_checkbox');
}

function create_grading_total(i, w_total) {
  var label = jQuery('<label class="fm-field-label" for="element_total">Total</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="element_total" onKeyPress="return check_isnum_or_minus(event)" onKeyUp="change_total(this.value,' + i + ')" value="' + w_total + '" />');
  return create_option_container(label, input);
}

function check_isnum_or_minus(e) {
  /*var chCode1 = e.which || e.keyCode;
  if (chCode1 != 45) {
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
      return false;
  }
  else if(e.target.selectionStart != 0 || e.target.value[0] == '-') {
      return false;
  }
  return true;*/
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

function change_total(value, id) {
  if (value == '') {
    value = 0;
  }
  document.getElementById(id + "_grading_totalform_id_temp").value = value;
  document.getElementById(id + "_total_elementform_id_temp").innerHTML = value;
  if (value == 0) {
    jQuery("#" + id + "_element_total_divform_id_temp").hide();
  }
  else {
    jQuery("#" + id + "_element_total_divform_id_temp").show();
  }
}

function create_grading_items(i, w_items) {
  var label = jQuery('<label class="fm-field-label">Items</label>');
  var button_add = jQuery('<span class="fm-add-attribute dashicons dashicons-plus-alt" title="Add" id="el_items_add" onClick="add_grading_items(' + i + ')"></span>');
  var items = jQuery('<div id="items" class="fm-width-100"></div>');
  n = w_items.length;
  for (j = 0; j < n; j++) {
    var button = jQuery('<div class="fm-width-100 fm-fields-set" id="item_row_' + j + '" idi="' + j + '"></div>');
    var item_body = jQuery('<div class="fm-width-90"></div>');
    item_body.append(create_grading_item(i, j, w_items[j]));
    button.append(item_body);
    var marker_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_grading_items(' + j + ', ' + i + ')"></span></div>');
    button.append(marker_remove);
    items.append(button);
  }

  var input = label;
  input = input.add(button_add);
  input = input.add(items);
  return create_option_container(null, input);
}

function create_grading_item(i, j, w_items) {
  var label = jQuery('<label class="fm-field-label" for="el_title' + j + '">Name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_items' + j + '" onChange="change_label(\'' + i + '_label_elementform_id_temp' + j + '\', this.value); change_in_value(\'' + i + '_label_elementform_id_temp' + j + '\', this.value)" value="' + w_items + '" />');
  return create_option_container(null, input);
}

function add_grading_items(num) {
  for (i = 100; i > 0; i--) {
    if (document.getElementById("el_items" + i)) {
      break;
    }
  }
  m = i + 1;
  var items = jQuery('#items');
  var button = jQuery('<div class="fm-width-100 fm-fields-set" id="item_row_' + m + '" idi="' + m + '"></div>');
  var item_body = jQuery('<div class="fm-width-90"></div>');
  item_body.append(create_grading_item(num, m, ''));
  button.append(item_body);
  var marker_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_grading_items(' + m + ', ' + num + ')"></span></div>');
  button.append(marker_remove);
  items.append(button);

  refresh_grading_items(num);
}

function remove_grading_items(id, num) {
  var choices_td = document.getElementById("items");
  var el_choices = document.getElementById('item_row_' + id);
  choices_td.removeChild(el_choices);
  refresh_grading_items(num);
}

function refresh_grading_items(num) {
  for (i = 100; i > 0; i--) {
    if (document.getElementById("el_items" + i)) {
      break;
    }
  }
  m = i;

  var div = document.getElementById(num + '_elementform_id_temp');
  div.innerHTML = '';
  for (i = 0; i <= m; i++) {
    if (document.getElementById("el_items" + i)) {
      var div_grading = document.createElement('div');
      div_grading.setAttribute("id", num + "_element_div" + i);
      div_grading.setAttribute("class", "grading");

      var input_item = document.createElement('input');
      input_item.setAttribute("id", num + "_elementform_id_temp_" + i);
      input_item.setAttribute("name", num + "_elementform_id_temp_" + i);
      input_item.setAttribute("onKeyPress", "return check_isnum_or_minus(event)");
      input_item.setAttribute("value", "");
      input_item.setAttribute("size", "5");
      input_item.setAttribute("type", "text");
      input_item.setAttribute("onKeyUp", "sum_grading_values(" + num + ",'form_id_temp')");
      input_item.setAttribute("onChange", "sum_grading_values(" + num + ",'form_id_temp')");

      var label_item = document.createElement('label');
      label_item.setAttribute("id", num + "_label_elementform_id_temp" + i);
      label_item.setAttribute("class", "ch-rad-label");
      label_item.innerHTML = document.getElementById("el_items" + i).value;

      div_grading.appendChild(input_item);
      div_grading.appendChild(label_item);
      div.appendChild(div_grading);
    }
  }
  var div_total = document.createElement('div');
  div_total.setAttribute("id", num + "_element_total_divform_id_temp");
  div_total.setAttribute("class", "grading_div");
  var total_value = document.getElementById(num + '_grading_totalform_id_temp').value;
  if (total_value != "" && total_value != "0") {
    div_total.style.display = "block";
  }
  else {
    div_total.style.display = "none";
  }
  var Total = document.createTextNode("Total:");
  var Seperator = document.createTextNode("/");

  var span_total = document.createElement('span');
  span_total.setAttribute("id", num + "_total_elementform_id_temp");
  span_total.setAttribute("name", num + "_total_elementform_id_temp");
  span_total.innerHTML = total_value;

  var span_gum = document.createElement('span');
  span_gum.setAttribute("id", num + "_sum_elementform_id_temp");
  span_gum.setAttribute("name", num + "_sum_elementform_id_temp");
  span_gum.innerHTML = 0;

  var span_of_text = document.createElement('span');
  span_of_text.setAttribute("id", num + "_text_elementform_id_temp");
  span_of_text.setAttribute("name", num + "_text_elementform_id_temp");
  span_of_text.innerHTML = "";

  div_total.appendChild(Total);
  div_total.appendChild(span_gum);
  div_total.appendChild(Seperator);
  div_total.appendChild(span_total);
  div_total.appendChild(span_of_text);
  div.appendChild(div_total);
}

function sum_grading_values(num,form_id) {
  var sum = 0;
  for (var k = 0; k < 100; k++) {
    if (document.getElementById(num + '_element' + form_id + '_' + k)) {
      if (document.getElementById(num + '_element' + form_id + '_' + k).value) {
        sum = sum + parseInt(document.getElementById(num + '_element' + form_id + '_' + k).value);
      }
    }
    if (document.getElementById(num + '_total_element' + form_id)) {
      if (sum > document.getElementById(num + '_total_element' + form_id).innerHTML) {

        document.getElementById(num + '_text_element' + form_id).innerHTML = " Total should be less than " + document.getElementById(num + '_total_element' + form_id).innerHTML;
      }
      else {
        document.getElementById(num + '_text_element' + form_id).innerHTML = "";
      }
    }
  }
  if (document.getElementById(num + '_sum_element' + form_id)) {
    document.getElementById(num + '_sum_element' + form_id).innerHTML = sum;
  }
}

function go_to_type_grading(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_items = ['item1', 'item2', 'item3'];
  type_grading(new_id, 'Grading', '', 'top', 'no', w_items, '100', 'no', 'wdform_grading', w_attr_name, w_attr_value);
}

function type_grading(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_items, w_total, w_required, w_class, w_attr_name, w_attr_value) {
  jQuery("#element_type").val("type_grading");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_grading'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_grading_total(i, w_total));
  edit_main_table.append(create_grading_items(i, w_items));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_grading'));

  // Preview.
  element = 'input';
  type = 'grading';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_grading");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_total = document.createElement("input");
  adding_total.setAttribute("type", "hidden");
  adding_total.setAttribute("value", w_total);
  adding_total.setAttribute("name", i + "_grading_totalform_id_temp");
  adding_total.setAttribute("id", i + "_grading_totalform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = "table-cell";
  div_label.style.cssText = "display:" + display_label_div + "; vertical-align:top; width:" + w_field_label_size + "px;";
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var div_grading = document.createElement('div');
  div_grading.setAttribute("id", i + "_elementform_id_temp");

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_total);
  div_element.appendChild(div_grading);

  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_grades_description );
  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_grading');
  refresh_grading_items(i);
}

function create_matrix_input_type(i, w_field_input_type) {
  var label = jQuery('<label class="fm-field-label">Input Type</label>');
  var input = jQuery('<select class="fm-width-100" id="edit_for_select_input_type" name="edit_for_select_input_type" onChange="change_input_type(' + i + ',this.value); refresh_matrix(' + i + ')"></select>');

  input.append(jQuery('<option id="edit_for_input_type_radio" value="radio"' + (w_field_input_type == 'radio' ? ' selected="selected"' : '') + '>Radio Button</option>'));
  input.append(jQuery('<option id="edit_for_input_type_checkbox" value="checkbox"' + (w_field_input_type == 'checkbox' ? ' selected="selected"' : '') + '>Check Box</option>'));
  input.append(jQuery('<option id="edit_for_input_type_text" value="text"' + (w_field_input_type == 'text' ? ' selected="selected"' : '') + '>Text Box</option>'));
  input.append(jQuery('<option id="edit_for_input_type_select" value="select"' + (w_field_input_type == 'select' ? ' selected="selected"' : '') + '>Drop Down</option>'));

  return create_option_container(label, input);
}

function change_input_type(id, value) {
  document.getElementById(id + "_input_typeform_id_temp").value = value;
}

function refresh_matrix(num) {
  for (i = 100; i > 0; i--) {
    if (document.getElementById("el_rows" + i))
      break;
  }
  m = i;
  for (i = 100; i > 0; i--) {
    if (document.getElementById("el_columns" + i))
      break;
  }
  n = i;

  var table = document.getElementById(num + '_table_little');
  table.innerHTML = '';

  var tr0 = document.createElement('div');
  tr0.setAttribute("id", num + "_element_tr0");
  tr0.style.display = "table-row";

  table.appendChild(tr0);

  var td0 = document.createElement('div');
  td0.setAttribute("id", num + "_element_td0_0");
  td0.style.display = "table-cell";
  td0.innerHTML = "";
  tr0.appendChild(td0);

  for (k = 1; k <= n; k++) {
    if (document.getElementById("el_columns" + k)) {
      var td = document.createElement('div');
      td.setAttribute("id", num + "_element_td0_" + k);
      td.setAttribute("class", "matrix_");
      td.style.display = "table-cell";

      var label_column = document.createElement('label');
      label_column.setAttribute("id", num + "_label_elementform_id_temp" + "0_" + k);
      label_column.setAttribute("name", num + "_label_elementform_id_temp" + "0_" + k);
      label_column.setAttribute("class", "ch-rad-label");
      label_column.setAttribute("for", num + "_elementform_id_temp" + k);
      label_column.innerHTML = document.getElementById("el_columns" + k).value;

      td.appendChild(label_column);
      tr0.appendChild(td);
    }
  }

  for (i = 1; i <= m; i++) {
    if (document.getElementById("el_rows" + i)) {
      var tr = document.createElement('div');
      tr.setAttribute("id", num + "_element_tr" + i);
      tr.style.display = "table-row";
      var td0 = document.createElement('div');
      td0.setAttribute("id", num + "_element_td" + i + "_0");
      td0.setAttribute("class", "matrix_");
      td0.style.display = "table-cell";

      var label_row = document.createElement('label');
      label_row.setAttribute("id", num + "_label_elementform_id_temp" + i + "_0");
      label_row.setAttribute("class", "ch-rad-label");
      label_row.setAttribute("for", num + "_elementform_id_temp" + i);
      label_row.innerHTML = document.getElementById("el_rows" + i).value;

      td0.appendChild(label_row);
      tr.appendChild(td0);
      table.appendChild(tr);

      if (document.getElementById("edit_for_select_input_type").value == "text")
        document.getElementById("el_textbox").removeAttribute("style");
      else
        document.getElementById("el_textbox").style.display = "none";

      for (k = 1; k <= n; k++) {
        if (document.getElementById("el_columns" + k)) {
          var td = document.createElement('div');
          td.setAttribute("id", num + "_element_td" + i + "_" + k);
          td.style.cssText = "display:table-cell; text-align:center; padding:5px 0 0 5px;";

          if (document.getElementById("edit_for_select_input_type").value == "select") {
            var select_yes_no = document.createElement('select');
            select_yes_no.setAttribute("id", num + "_select_yes_noform_id_temp" + i + "_" + k);
            select_yes_no.setAttribute("name", num + "_select_yes_noform_id_temp" + i + "_" + k);
            var option_yes_no1 = document.createElement('option');
            option_yes_no1.setAttribute("value", "");
            Nothing = document.createTextNode(" ");

            var option_yes_no2 = document.createElement('option');
            option_yes_no2.setAttribute("value", "yes");
            Yes = document.createTextNode("Yes");

            var option_yes_no3 = document.createElement('option');
            option_yes_no3.setAttribute("value", "no");
            No = document.createTextNode("No");

            option_yes_no1.appendChild(Nothing);
            option_yes_no2.appendChild(Yes);
            option_yes_no3.appendChild(No);
            select_yes_no.appendChild(option_yes_no1);
            select_yes_no.appendChild(option_yes_no2);
            select_yes_no.appendChild(option_yes_no3);
            td.appendChild(select_yes_no);
          }
          else {
            var input_of_matrix = document.createElement('input');
            input_of_matrix.setAttribute("id", num + "_input_elementform_id_temp" + i + "_" + k);
            input_of_matrix.setAttribute("align", "center");
            input_of_matrix.setAttribute("size", "14");
            input_of_matrix.setAttribute("type", document.getElementById("edit_for_select_input_type").value);

            if (document.getElementById("edit_for_select_input_type").value == "radio") {
              input_of_matrix.setAttribute("name", num + "_input_elementform_id_temp" + i);
              input_of_matrix.setAttribute("value", i + "_" + k);
            }
            else {
              if (document.getElementById("edit_for_select_input_type").value == "checkbox") {
                input_of_matrix.setAttribute("name", num + "_input_elementform_id_temp" + i + "_" + k);
                input_of_matrix.setAttribute("value", 1);
              }
              else {
                document.getElementById(num + "_textbox_sizeform_id_temp").value = document.getElementById("edit_for_label_textbox_size").value;

                input_of_matrix.setAttribute("name", num + "_input_elementform_id_temp" + i + "_" + k);
                input_of_matrix.setAttribute("value", '');
                input_of_matrix.style.cssText = "width:" + document.getElementById("edit_for_label_textbox_size").value + "px;";
              }
            }
            td.appendChild(input_of_matrix);
          }
          tr.appendChild(td);
        }
      }
    }
  }
}

function create_matrix_input_size(i, w_textbox_size, w_field_input_type) {
  var label = jQuery('<label class="fm-field-label" for="edit_for_label_textbox_size">Text Box width(px)</label>');
  var input = jQuery('<input class="fm-width-100" type="text" id="edit_for_label_textbox_size" onKeyPress="return check_isnum(event)" onKeyUp="refresh_matrix(' + i + ')" value="' + w_textbox_size + '" />');
  return create_option_container(label, input, 'el_textbox', w_field_input_type == "text");
}

function create_matrix_rows(i, w_rows) {
  var label = jQuery('<label class="fm-field-label">Rows</label>');
  var button_add = jQuery('<span class="fm-add-attribute dashicons dashicons-plus-alt" title="Add" id="el_rows_add" onClick="add_to_matrix(\'rows\', ' + i + ')"></span>');
  var rows = jQuery('<div id="rows" class="fm-width-100"></div>');
  n = w_rows.length;
  for (j = 1; j < n; j++) {
    var button = jQuery('<div class="fm-width-100 fm-fields-set" id="el_row' + j + '" idi="' + j + '"></div>');
    var item_body = jQuery('<div class="fm-width-90"></div>');
    item_body.append(create_matrix_row_item(i, j, w_rows[j]));
    button.append(item_body);
    var row_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_rows' + j + '_remove" onClick="remove_rowcols(' + j + ', ' + i + ', \'row\')"></span></div>');
    button.append(row_remove);
    rows.append(button);
  }

  var input = label;
  input = input.add(button_add);
  input = input.add(rows);
  return create_option_container(null, input);
}

function create_matrix_row_item(i, j, w_rows) {
  var label = jQuery('<label class="fm-field-label" for="el_title' + j + '">Name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_rows' + j + '" onChange="change_label(\'' + i + '_label_elementform_id_temp' + j + '_0\', this.value); change_in_value(\'' + i + '_label_elementform_id_temp' + j + '_0\', this.value)" value="' + w_rows + '" />');
  return create_option_container(null, input);
}

function create_matrix_columns(i, w_columns) {
  var label = jQuery('<label class="fm-field-label">Columns</label>');
  var button_add = jQuery('<span class="fm-add-attribute dashicons dashicons-plus-alt" title="Add" id="el_columns_add" onClick="add_to_matrix(\'columns\', ' + i + ')"></span>');
  var rows = jQuery('<div id="columns" class="fm-width-100"></div>');
  n = w_columns.length;
  for (j = 1; j < n; j++) {
    var button = jQuery('<div class="fm-width-100 fm-fields-set" id="el_column' + j + '" idi="' + j + '"></div>');
    var item_body = jQuery('<div class="fm-width-90"></div>');
    item_body.append(create_matrix_column_item(i, j, w_columns[j]));
    button.append(item_body);
    var column_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_rows' + j + '_remove" onClick="remove_rowcols(' + j + ', ' + i + ', \'column\')"></span></div>');
    button.append(column_remove);
    rows.append(button);
  }

  var input = label;
  input = input.add(button_add);
  input = input.add(rows);
  return create_option_container(null, input);
}

function create_matrix_column_item(i, j, w_columns) {
  var label = jQuery('<label class="fm-field-label" for="el_title' + j + '">Name</label>');
  var input = jQuery('<input type="text" class="fm-width-100" id="el_columns' + j + '" onChange="change_label(\'' + i + '_label_elementform_id_temp0_' + j + '\', this.value); change_in_value(\'' + i + '_label_elementform_id_temp0_' + j + '\', this.value)" value="' + w_columns + '" />');
  return create_option_container(null, input);
}

function add_to_matrix(type, num) {
  for (i = 100; i > 0; i--) {
    if (document.getElementById("el_rows" + i))
      break;
  }

  if (type == "rows")
    m = i + 1;
  else
    m = i;

  for (i = 100; i > 0; i--) {
    if (document.getElementById("el_columns" + i))
      break;
  }
  if (type == "columns")
    n = i + 1;
  else
    n = i;

  if (type == "rows") {
    var rows = jQuery('#rows');
    var button = jQuery('<div class="fm-width-100 fm-fields-set" id="item_row_' + m + '" idi="' + m + '"></div>');
    var item_body = jQuery('<div class="fm-width-90"></div>');
    item_body.append(create_matrix_row_item(num, m, ''));
    button.append(item_body);
    var row_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_rowcols(' + m + ', ' + num + ', \'row\')"></span></div>');
    button.append(row_remove);
    rows.append(button);
  }
  else {
    var columns = jQuery('#columns');
    var button = jQuery('<div class="fm-width-100 fm-fields-set" id="item_column_' + n + '" idi="' + n + '"></div>');
    var item_body = jQuery('<div class="fm-width-90"></div>');
    item_body.append(create_matrix_column_item(num, n, ''));
    button.append(item_body);
    var column_remove = jQuery('<div class="fm-width-10 fm-remove-button"><span class="fm-remove-attribute dashicons dashicons-dismiss" onClick="remove_rowcols(' + n + ', ' + num + ', \'column\')"></span></div>');
    button.append(column_remove);
    columns.append(button);
  }
  refresh_matrix(num);
}

function remove_rowcols(id, num, type) {
  jQuery('#el_'+ type + id).remove();
  jQuery('#item_'+ type + '_' + id).remove();
  refresh_matrix(num);
}

function go_to_type_matrix(new_id) {
  w_attr_name = [];
  w_attr_value = [];
  w_rows = ['', 'row1', 'row2'];
  w_columns = ['', 'column1', 'column2'];
  type_matrix(new_id, 'Matrix', '', 'top', 'no', 'radio', w_rows, w_columns, 'no', 'wdform_matrix', w_attr_name, w_attr_value, '100');
}

function type_matrix(i, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_input_type, w_rows, w_columns, w_required, w_class, w_attr_name, w_attr_value, w_textbox_size) {
  jQuery("#element_type").val("type_matrix");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_matrix'));
  edit_main_table.append(create_label(i, w_field_label));
  edit_main_table.append(create_label_position(i, w_field_label_pos));
  edit_main_table.append(create_hide_label(i, w_hide_label));
  edit_main_table.append(create_required(i, w_required));
  edit_main_table.append(create_matrix_input_type(i, w_field_input_type));
  edit_main_table.append(create_matrix_input_size(i, w_textbox_size, w_field_input_type));
  edit_main_table.append(create_matrix_rows(i, w_rows));
  edit_main_table.append(create_matrix_columns(i, w_columns));

  var advanced_options_container = jQuery('<div class="inside"></div>');
  edit_main_table.append(create_advanced_options_container(advanced_options_container));
  advanced_options_container.append(create_field_label_size(i, w_field_label_size));
  advanced_options_container.append(create_class(i, w_class));
  advanced_options_container.append(create_additional_attributes(i, w_attr_name, 'type_matrix'));

  // Preview.
  element = 'input';
  type = 'matrix';
  var adding_type = document.createElement("input");
  adding_type.setAttribute("type", "hidden");
  adding_type.setAttribute("value", "type_matrix");
  adding_type.setAttribute("name", i + "_typeform_id_temp");
  adding_type.setAttribute("id", i + "_typeform_id_temp");
  var adding_required = document.createElement("input");
  adding_required.setAttribute("type", "hidden");
  adding_required.setAttribute("value", w_required);
  adding_required.setAttribute("name", i + "_requiredform_id_temp");
  adding_required.setAttribute("id", i + "_requiredform_id_temp");

  var adding_hide_label = document.createElement("input");
  adding_hide_label.setAttribute("type", "hidden");
  adding_hide_label.setAttribute("value", w_hide_label);
  adding_hide_label.setAttribute("name", i + "_hide_labelform_id_temp");
  adding_hide_label.setAttribute("id", i + "_hide_labelform_id_temp");

  var adding_input_type = document.createElement("input");
  adding_input_type.setAttribute("type", "hidden");
  adding_input_type.setAttribute("value", w_field_input_type);
  adding_input_type.setAttribute("name", i + "_input_typeform_id_temp");
  adding_input_type.setAttribute("id", i + "_input_typeform_id_temp");

  var adding_textbox_size = document.createElement("input");
  adding_textbox_size.setAttribute("type", "hidden");
  adding_textbox_size.setAttribute("value", w_textbox_size);
  adding_textbox_size.setAttribute("name", i + "_textbox_sizeform_id_temp");
  adding_textbox_size.setAttribute("id", i + "_textbox_sizeform_id_temp");

  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var div_field = document.createElement('div');
  div_field.setAttribute("id", i + "_elemet_tableform_id_temp");

  var display_label_div = (w_hide_label == "yes" ? "none" : "table-cell");
  var div_label = document.createElement('div');
  div_label.setAttribute("align", 'left');
  div_label.style.display = display_label_div;
  div_label.style.width = w_field_label_size + 'px';
  div_label.setAttribute("id", i + "_label_sectionform_id_temp");

  var div_element = document.createElement('div');
  div_element.setAttribute("align", 'left');
  div_element.style.display = "table-cell";
  div_element.setAttribute("id", i + "_element_sectionform_id_temp");

  var br1 = document.createElement('br');
  var br2 = document.createElement('br');
  var br3 = document.createElement('br');
  var br4 = document.createElement('br');

  var table_little_t = document.createElement('div');
  table_little_t.setAttribute("id", i + "_elementform_id_temp");
  table_little_t.style.display = "table";

  var table_little = document.createElement('div');
  table_little.setAttribute("id", i + "_table_little");
  table_little.style.display = "table-row-group";

  table_little_t.appendChild(table_little);

  var label = document.createElement('span');
  label.setAttribute("id", i + "_element_labelform_id_temp");
  label.innerHTML = w_field_label;
  label.setAttribute("class", "label");

  var required = document.createElement('span');
  required.setAttribute("id", i + "_required_elementform_id_temp");
  required.innerHTML = "";
  required.setAttribute("class", "required");
  if (w_required == "yes")
    required.innerHTML = " *";

  var main_td = document.getElementById('show_table');

  div_label.appendChild(label);
  div_label.appendChild(required);
  div_element.appendChild(adding_type);
  div_element.appendChild(adding_required);
  div_element.appendChild(adding_hide_label);
  div_element.appendChild(adding_input_type);
  div_element.appendChild(adding_textbox_size);
  div_element.appendChild(table_little_t);
  div_field.appendChild(div_label);
  div_field.appendChild(div_element);

  div.appendChild(div_field);
  div.appendChild(br3);
  main_td.appendChild(div);
  jQuery("#main_div").append( form_maker.type_matrix_description );

  if (w_field_label_pos == "top")
    label_top(i);

  change_class(w_class, i);
  refresh_attr(i, 'type_matrix');
  refresh_matrix(i);
}

function create_pagination_type(w_type) {
  var label = jQuery('<label class="fm-field-label">Pagination Options</label>');
  var input1 = jQuery('<input type="radio" id="el_pagination_steps" name="el_pagination" onclick="pagination_type(\'steps\')"' + (w_type == 'steps' ? ' checked="checked"' : '') + ' />');
  var label1 = jQuery('<label for="el_pagination_steps">Steps</label>');
  var input2 = jQuery('<input type="radio" id="el_pagination_percentage" name="el_pagination" onclick="pagination_type(\'percentage\')"' + (w_type == 'percentage' ? ' checked="checked"' : '') + ' />');
  var label2 = jQuery('<label for="el_pagination_percentage">Percentage</label>');
  var input3 = jQuery('<input type="radio" id="el_pagination_none" name="el_pagination" onclick="pagination_type(\'none\')"' + (w_type != 'steps' && w_type != 'percentage' ? ' checked="checked"' : '') + ' />');
  var label3 = jQuery('<label for="el_pagination_none">No Context</label>');
  var input = input1;
  input = input.add(label1);
  input = input.add(input2);
  input = input.add(label2);
  input = input.add(input3);
  input = input.add(label3);
  return create_option_container(label, input);
}

function pagination_type(type) {
  document.getElementById("pages_div").innerHTML = "";
  w_pages = [];
  k = 0;
  for (j = 1; j <= form_view_max; j++) {
    if (document.getElementById('form_id_tempform_view' + j)) {
      k++;
      if (document.getElementById('form_id_tempform_view' + j).getAttribute('page_title')) {
        w_pages[j] = document.getElementById('form_id_tempform_view' + j).getAttribute('page_title');
      }
      else {
        w_pages[j] = "none";
      }
    }
  }
  if (type == 'steps') {
    make_page_steps(w_pages);
  }
  else if (type == 'percentage') {
    make_page_percentage(w_pages);
  }
  else {
    make_page_none();
  }
}

function make_page_none() {
  document.getElementById("pages_div").innerHTML = "";
}

function make_page_percentage(w_pages) {
  document.getElementById("pages_div").innerHTML = "";
  show_title = document.getElementById('el_show_title_input').checked;
  var div_parent = document.createElement('div');
  div_parent.setAttribute("class", "page_percentage_deactive");
  var div = document.createElement('div');
  div.setAttribute("id", "div_percentage");
  div.setAttribute("class", "page_percentage_active");
  var b = document.createElement('b');
  div.appendChild(b);
  k = 0;
  cur_page_title = '';
  for (j = 1; j <= form_view_max; j++) {
    if (w_pages[j]) {
      k++;
      if (w_pages[j] == "none") {
        w_pages[j] = '';
      }
      if (j == form_view) {
        if (show_title) {
          var cur_page_title = document.createElement('span');
          if (k == 1) {
            cur_page_title.style.paddingLeft = "40px";
          }
          else {
            cur_page_title.style.paddingLeft = "5px";
          }
          cur_page_title.innerHTML = w_pages[j];
        }
        page_number = k;
      }
    }
  }
  b.innerHTML = Math.round(((page_number - 1) / k) * 100) + '%';
  div.style.width = ((page_number - 1) / k) * 100 + '%';
  div_parent.appendChild(div);
  if (cur_page_title) {
    div_parent.appendChild(cur_page_title);
  }
  document.getElementById("pages_div").appendChild(div_parent);
}

function make_page_steps(w_pages) {
  document.getElementById("pages_div").innerHTML = "";
  show_title = document.getElementById('el_show_title_input').checked;
  k = 0;
  for (j = 1; j <= form_view_max; j++) {
    if (w_pages[j]) {
      k++;
      if (w_pages[j] == "none")
        w_pages[j] = '';
      page_number = document.createElement('span');
      if (j == form_view)
        page_number.setAttribute('class', "page_active");
      else
        page_number.setAttribute('class', "page_deactive");
      if (show_title) {
        page_number.innerHTML = w_pages[j];
      }
      else {
        page_number.innerHTML = k;
      }
      document.getElementById("pages_div").appendChild(page_number);
    }
  }
}

function create_pagination_title(w_show_title) {
  var label = jQuery('<label class="fm-field-label" for="el_show_title_input">Show Page Titles in Progress Bar</label>');
  var input = jQuery('<input type="checkbox" id="el_show_title_input" onClick="show_title_pagebreak()"' + (w_show_title ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function show_title_pagebreak() {
  document.getElementById("pages_div").innerHTML = "";
  if (document.getElementById("el_pagination_steps").checked) {
    pagination_type('steps');
  }
  else if (document.getElementById("el_pagination_percentage").checked) {
    pagination_type('percentage');
  }
}

function create_pagination_numbers(w_show_numbers) {
  var label = jQuery('<label class="fm-field-label" for="el_show_numbers_input">Show Page Numbers in Footer</label>');
  var input = jQuery('<input type="checkbox" id="el_show_numbers_input" onClick="show_numbers_pagebreak()"' + (w_show_numbers ? ' checked="checked"' : '') + ' />');
  return create_option_container(label, input);
}

function show_numbers_pagebreak() {
  document.getElementById("numbers_div").innerHTML = "";
  if (document.getElementById("el_show_numbers_input").checked) {
    k = 0;
    for (j = 1; j <= form_view_max; j++) {
      if (document.getElementById('form_id_tempform_view' + j)) {
        k++;
        if (j == form_view) {
          page_number = k;
        }
      }
    }
    var cur = document.createElement('span');
    cur.setAttribute("class", "page_numbersform_id_temp");
    cur.innerHTML = page_number + '/' + k;
    document.getElementById("numbers_div").appendChild(cur);
  }
}

function create_page_titles() {
  var label = jQuery('<label class="fm-field-label">Pages Titles</label>');
  var pages = jQuery('<div id="items" class="fm-width-100"></div>');
  k = 0;
  for (j = 1; j <= form_view_max; j++) {
    var page_body = jQuery('#form_id_tempform_view' + j);
    if (page_body.length > 0) {
      var page = jQuery('<div class="fm-width-100"></div>');
      var title = page_body.prop('page_title');
      if (typeof title == undefined) {
        title = 'Untitled Page';
      }
      var input = jQuery('<input type="text" class="fm-width-90" id="page_title_' + j + '" onKeyUp="set_page_title(this.value, ' + j + ')" value="' + title + '" />');
      page.append(jQuery('<label>' + j + '. </label>'));
      page.append(input);
      pages.append(page);
    }
  }

  var input = label;
  input = input.add(pages);
  return create_option_container(null, input);
}

function set_page_title(title, id) {
  title = title.replace(/(<([^>]+)>)/ig, "");
  document.getElementById("form_id_tempform_view" + id).setAttribute('page_title', title);
  show_title_pagebreak();
}

function el_page_navigation() {
  w_type = document.getElementById('pages').getAttribute('type');
  w_show_numbers = false;
  w_show_title = false;
  if (document.getElementById('pages').getAttribute('show_numbers') == "true") {
    w_show_numbers = true;
  }
  if (document.getElementById('pages').getAttribute('show_title') == "true") {
    w_show_title = true;
  }
  w_attr_name = [];
  w_attr_value = [];
  type_page_navigation(w_type, w_show_title, w_show_numbers, w_attr_name, w_attr_value);
}

function type_page_navigation(w_type, w_show_title, w_show_numbers, w_attr_name, w_attr_value) {
  if ( fm_need_enable ) {
    enable2();
  }
  jQuery("#element_type").val("type_page_navigation");
  delete_last_child();

  var t = jQuery('#edit_table');
  var edit_div = jQuery('<div id="edit_div"></div>');
  t.append(edit_div);
  var edit_main_table = jQuery('<div id="edit_main_table"></div>');
  edit_div.append(edit_main_table);
  edit_main_table.append(create_field_type('type_page_navigation'));
  edit_main_table.append(create_pagination_type(w_type));
  edit_main_table.append(create_pagination_title(w_show_title));
  edit_main_table.append(create_pagination_numbers(w_show_numbers));
  edit_main_table.append(create_page_titles());

  // Preview.
  w_pages = [];
  k = 0;
  for (j = 1; j <= form_view_max; j++) {
    if (document.getElementById('form_id_tempform_view' + j)) {
      if (document.getElementById('form_id_tempform_view' + j).getAttribute('page_title'))
        w_pages[j] = document.getElementById('form_id_tempform_view' + j).getAttribute('page_title');
      else
        w_pages[j] = "Untitled Page";
    }
  }
  var div = document.createElement('div');
  div.setAttribute("id", "main_div");

  var table = document.createElement('table');
  table.setAttribute("id", "_elemet_tableform_id_temp");
  table.setAttribute("width", "90%");

  var tr = document.createElement('tr');

  var td2 = document.createElement('td');
  td2.setAttribute("valign", 'top');
  td2.setAttribute("align", 'left');
  td2.setAttribute("id", "_element_sectionform_id_temp");
  td2.setAttribute("width", "100%");

  var br1 = document.createElement('br');

  var pages_div = document.createElement('div');
  pages_div.setAttribute("align", "left");
  pages_div.setAttribute("id", "pages_div");
  pages_div.style.width = '100%';
  pages_div.innerHTML = "";

  var numbers_div = document.createElement('div');
  numbers_div.setAttribute("align", "center");
  numbers_div.setAttribute("id", "numbers_div");
  numbers_div.style.width = '100%';
  numbers_div.style.paddingTop = '100px';
  numbers_div.innerHTML = "";

  td2.appendChild(pages_div);
  td2.appendChild(numbers_div);
  var main_td = document.getElementById('show_table');

  tr.appendChild(td2);
  table.appendChild(tr);

  div.appendChild(table);
  div.appendChild(br1);
  main_td.appendChild(div);

  if (w_type == 'steps') {
    make_page_steps(w_pages);
  }
  else if (w_type == 'percentage') {
    make_page_percentage(w_pages);
  }
  else {
    make_page_none(w_pages);
  }
  if (w_show_numbers) {
    show_numbers_pagebreak();
  }
}

function refresh_page_numbers() {
  var pages = jQuery('.wdform-page-and-images');
  var show_numbers = document.getElementById('pages').getAttribute('show_numbers') == 'true';
  pages.each(function(index){
    var id = jQuery(this).find('.form_id_tempform_view_img').attr('id').split("form_id_tempform_view_img");
    j = id[1];
    if (document.getElementById('page_numbersform_id_temp' + j)) {
      document.getElementById("page_numbersform_id_temp" + j).innerHTML = '';
      if (show_numbers) {
        var cur = document.createElement('span');
        cur.setAttribute("class", "page_numbersform_id_temp");
        cur.innerHTML = (index + 1) + '/' + pages.length;
        document.getElementById("page_numbersform_id_temp" + j).appendChild(cur);
      }
    }
  });
}

function gen_form_fields() {
  switch (wdtype) {
    case 'type_editor': {
      w_editor = document.getElementById("wdform_field" + id).innerHTML;
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_editor + "*:*w_editor*:*";
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_send_copy': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").checked;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_required + "*:*w_required*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_text': {
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      w_regExp_status = document.getElementById(id + "_regExpStatusform_id_temp").value;
      w_regExp_value = document.getElementById(id + "_regExp_valueform_id_temp").value;
      w_regExp_common = document.getElementById(id + "_regExp_commonform_id_temp").value;
      w_regExp_arg = document.getElementById(id + "_regArgumentform_id_temp").value;
      w_regExp_alert = document.getElementById(id + "_regExp_alertform_id_temp").value;
      w_readonly = document.getElementById(id + "_readonlyform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_title + "*:*w_title*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_regExp_status + "*:*w_regExp_status*:*";
      form_fields += w_regExp_value + "*:*w_regExp_value*:*";
      form_fields += w_regExp_common + "*:*w_regExp_common*:*";
      form_fields += w_regExp_arg + "*:*w_regExp_arg*:*";
      form_fields += w_regExp_alert + "*:*w_regExp_alert*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_readonly + "*:*w_readonly*:*";
      form_fields += w_class + "*:*w_class*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_number': {
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_title + "*:*w_title*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_password': {
      w_verification = document.getElementById(id + "_verification_id_temp").value;
      w_placeholder = document.getElementById(id + "_elementform_id_temp").placeholder; // 'w_placeholder 123';
      if (document.getElementById(id + '_1_element_labelform_id_temp').innerHTML) {
        w_verification_label = document.getElementById(id + '_1_element_labelform_id_temp').innerHTML;
      }
      else {
        w_verification_label = " ";
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_verification + "*:*w_verification*:*";
      form_fields += w_verification_label + "*:*w_verification_label*:*";
      form_fields += w_placeholder + "*:*w_placeholder*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_textarea': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_characters_limit = document.getElementById(id + "_charlimitform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      s = document.getElementById(id + "_elementform_id_temp").style.height;
      w_size_h = s.substring(0, s.length - 2);
      w = document.getElementById(id + "_elementform_id_temp").style.width;
      w_size_w = w.substring(0, w.length - 2);
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size_w*:*";
      form_fields += w_size_h + "*:*w_size_h*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_characters_limit + "*:*w_characters_limit*:*";
      form_fields += w_title + "*:*w_title*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_wdeditor': {
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      s = document.getElementById(id + "_elementform_id_temp").style.height;
      w_size_h = s.substring(0, s.length - 2);
      w = document.getElementById(id + "_elementform_id_temp").style.width;
      w_size_w = w.substring(0, w.length - 2);
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_size + "*:*w_size_w*:*";
      form_fields += w_size_h + "*:*w_size_h*:*";
      form_fields += w_title + "*:*w_title*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_phone': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
      w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
      s = document.getElementById(id + "_element_lastform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      w_mini_labels = [document.getElementById(id + "_mini_label_area_code").innerHTML, document.getElementById(id + "_mini_label_phone_number").innerHTML];
      atrs = return_attributes(id + '_element_firstform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_first_val.join('***') + "*:*w_first_val*:*";
      form_fields += w_title.join('***') + "*:*w_title*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_phone_new': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_top_country = document.getElementById(id + "_elementform_id_temp").getAttribute("top-country");
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_top_country + "*:*w_top_country*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_name': {
      if (document.getElementById(id + "_enable_fieldsform_id_temp")) {
        w_name_format = "normal";
        w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
        w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
        var title_middle = ['title', 'middle'];
        for (var l = 0; l < 2; l++) {
          w_first_val.push(document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp') ? document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp').value : '');
          w_title.push(document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp') ? document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp').title : '');
        }
      }
      else {
        if (document.getElementById(id + '_element_middleform_id_temp')) {
          w_name_format = "extended";
        }
        else {
          w_name_format = "normal";
        }
        if (w_name_format == "normal") {
          w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
          w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
        }
        else {
          w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value, document.getElementById(id + "_element_titleform_id_temp").value, document.getElementById(id + "_element_middleform_id_temp").value];
          w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title, document.getElementById(id + "_element_titleform_id_temp").title, document.getElementById(id + "_element_middleform_id_temp").title];
        }
      }
      if (document.getElementById(id + "_mini_label_title"))
        w_mini_title = document.getElementById(id + "_mini_label_title").innerHTML;
      else
        w_mini_title = "Title";

      if (document.getElementById(id + "_mini_label_middle"))
        w_mini_middle = document.getElementById(id + "_mini_label_middle").innerHTML;
      else
        w_mini_middle = "Middle";

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_mini_labels = [w_mini_title, document.getElementById(id + "_mini_label_first").innerHTML, document.getElementById(id + "_mini_label_last").innerHTML, w_mini_middle];
      w_name_title = document.getElementById(id + '_enable_fieldsform_id_temp') ? document.getElementById(id + '_enable_fieldsform_id_temp').getAttribute('title') : (w_name_format == "normal" ? 'no' : 'yes');
      w_name_middle = document.getElementById(id + '_enable_fieldsform_id_temp') ? document.getElementById(id + '_enable_fieldsform_id_temp').getAttribute('middle') : (w_name_format == "normal" ? 'no' : 'yes');
      w_name_fields = [w_name_title, w_name_middle];
      w_autofill = document.getElementById(id + "_autofillform_id_temp").value;
      s = document.getElementById(id + "_element_firstform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_element_firstform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_first_val.join('***') + "*:*w_first_val*:*";
      form_fields += w_title.join('***') + "*:*w_title*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_name_format + "*:*w_name_format*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_name_fields.join('***') + "*:*w_name_fields*:*";

      form_fields += w_autofill + "*:*w_autofill*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_price': {
      w_first_val = [document.getElementById(id + "_element_dollarsform_id_temp").value, document.getElementById(id + "_element_centsform_id_temp").value];
      w_title = [document.getElementById(id + "_element_dollarsform_id_temp").title, document.getElementById(id + "_element_centsform_id_temp").title];

      if (document.getElementById(id + "_td_name_cents").style.display == "none")
        w_hide_cents = 'yes';
      else
        w_hide_cents = 'no';

      s = document.getElementById(id + "_element_dollarsform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_element_dollarsform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_range_min = document.getElementById(id + "_range_minform_id_temp").value;
      w_range_max = document.getElementById(id + "_range_maxform_id_temp").value;

      w_mini_labels = [document.getElementById(id + "_mini_label_dollars").innerHTML, document.getElementById(id + "_mini_label_cents").innerHTML];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_first_val.join('***') + "*:*w_first_val*:*";
      form_fields += w_title.join('***') + "*:*w_title*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_hide_cents + "*:*w_hide_cents*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_range_min + "*:*w_range_min*:*";
      form_fields += w_range_max + "*:*w_range_max*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_price_new': {
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;

      s = document.getElementById(id + "_elementform_id_temp").style.width;
      w_size = s.substring(0, s.length - 2);
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_range_min = document.getElementById(id + "_range_minform_id_temp").value;
      w_range_max = document.getElementById(id + "_range_maxform_id_temp").value;
      w_readonly = document.getElementById(id + "_readonlyform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      if (document.getElementById(id + "_td_name_currency").style.display == "none")
        w_currency = 'yes';
      else
        w_currency = 'no';

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_title + "*:*w_title*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_range_min + "*:*w_range_min*:*";
      form_fields += w_range_max + "*:*w_range_max*:*";
      form_fields += w_readonly + "*:*w_readonly*:*";
      form_fields += w_currency + "*:*w_currency*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_address': {
      s = document.getElementById(id + "_div_address").style.width;
      w_size = s.substring(0, s.length - 2);

      if (document.getElementById(id + "_mini_label_street1"))
        w_street1 = document.getElementById(id + "_mini_label_street1").innerHTML;
      else
        w_street1 = document.getElementById(id + "_street1form_id_temp").value;

      if (document.getElementById(id + "_mini_label_street2"))
        w_street2 = document.getElementById(id + "_mini_label_street2").innerHTML;
      else
        w_street2 = document.getElementById(id + "_street2form_id_temp").value;

      if (document.getElementById(id + "_mini_label_city"))
        w_city = document.getElementById(id + "_mini_label_city").innerHTML;
      else
        w_city = document.getElementById(id + "_cityform_id_temp").value;

      if (document.getElementById(id + "_mini_label_state"))
        w_state = document.getElementById(id + "_mini_label_state").innerHTML;
      else
        w_state = document.getElementById(id + "_stateform_id_temp").value;

      if (document.getElementById(id + "_mini_label_postal"))
        w_postal = document.getElementById(id + "_mini_label_postal").innerHTML;
      else
        w_postal = document.getElementById(id + "_postalform_id_temp").value;

      if (document.getElementById(id + "_mini_label_country"))
        w_country = document.getElementById(id + "_mini_label_country").innerHTML;
      else
        w_country = document.getElementById(id + "_countryform_id_temp").value;

      w_mini_labels = [w_street1, w_street2, w_city, w_state, w_postal, w_country];

      var disabled_input = document.getElementById(id + "_disable_fieldsform_id_temp");

      w_street1_dis = disabled_input.getAttribute('street1');
      w_street2_dis = disabled_input.getAttribute('street2');
      w_city_dis = disabled_input.getAttribute('city');
      w_state_dis = disabled_input.getAttribute('state');
      w_us_states_dis = disabled_input.getAttribute('us_states');
      w_postal_dis = disabled_input.getAttribute('postal');
      w_country_dis = disabled_input.getAttribute('country');

      w_disabled_fields = [w_street1_dis, w_street2_dis, w_city_dis, w_state_dis, w_postal_dis, w_country_dis, w_us_states_dis];

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_street1form_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_disabled_fields.join('***') + "*:*w_disabled_fields*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_submitter_mail': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_first_val = document.getElementById(id + "_elementform_id_temp").value;
      w_title = document.getElementById(id + "_elementform_id_temp").title;
      w_autofill = document.getElementById(id + "_autofillform_id_temp").value;
      w_verification = document.getElementById(id + "_verification_id_temp").value;
      w_verification_placeholder = document.getElementById(id + "_1_elementform_id_temp").title;
      if (document.getElementById(id + '_1_element_labelform_id_temp').innerHTML)
        w_verification_label = document.getElementById(id + '_1_element_labelform_id_temp').innerHTML;
      else
        w_verification_label = " ";

      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_first_val + "*:*w_first_val*:*";
      form_fields += w_title + "*:*w_title*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_unique + "*:*w_unique*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_verification + "*:*w_verification*:*";
      form_fields += w_verification_label + "*:*w_verification_label*:*";
      form_fields += w_verification_placeholder + "*:*w_verification_placeholder*:*";
      form_fields += w_autofill + "*:*w_autofill*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_checkbox': {
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      w_limit_choice = document.getElementById(id + "_limitchoice_numform_id_temp").value;
      w_limit_choice_alert = document.getElementById(id + "_limitchoicealert_numform_id_temp").value;
      tt = 0;
      v = 0;

      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;

      if (document.getElementById(id + "_rowcol_numform_id_temp").value) {

        if (document.getElementById(id + '_table_little').getAttribute('for_hor'))
          w_flow = "hor"
        else
          w_flow = "ver";
        w_rowcol = document.getElementById(id + "_rowcol_numform_id_temp").value;
      }
      else {
        if (document.getElementById(id + '_hor'))
          w_flow = "hor"
        else
          w_flow = "ver";

        w_rowcol = 1;
      }

      if (w_flow == "ver") {
        var table_little = document.getElementById(id + '_table_little');
        for (k = 0; k < table_little.childNodes.length; k++) {
          var td_little = table_little.childNodes[k];
          for (m = 0; m < td_little.childNodes.length; m++) {
            var idi = td_little.childNodes[m].getAttribute('idi');
            if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other'))
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1')
                w_allow_other_num = tt;
            w_choices[tt] = document.getElementById(id + "_label_element" + idi).innerHTML;
            w_choices_checked[tt] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
            w_choices_value[tt] = document.getElementById(id + "_elementform_id_temp" + idi).value;
            w_choices_value[tt] =  w_choices_value[tt].replaceAll("'","");
            if (document.getElementById(id + "_label_element" + idi).getAttribute('where'))
              w_choices_params[tt] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
            else
              w_choices_params[tt] = '';
            tt++;
            v = idi;
          }
        }

      }
      else {
        var table_little = document.getElementById(id + '_table_little');
        var tr_little = table_little.childNodes;
        var td_max = tr_little[0].childNodes;

        for (k = 0; k < td_max.length; k++) {
          for (m = 0; m < tr_little.length; m++) {
            if (tr_little[m].childNodes[k]) {
              var td_little = tr_little[m].childNodes[k];
              var idi = td_little.getAttribute('idi');
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other'))
                if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1')
                  w_allow_other_num = tt;
              w_choices[tt] = document.getElementById(id + "_label_element" + idi).innerHTML;
              w_choices_checked[tt] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
              w_choices_value[tt] = document.getElementById(id + "_elementform_id_temp" + idi).value;
              w_choices_value[tt] =  w_choices_value[tt].replaceAll("'","");
              if (document.getElementById(id + "_label_element" + idi).getAttribute('where'))
                w_choices_params[tt] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
              else
                w_choices_params[tt] = '';
              tt++;
              v = idi;
            }
          }
        }

      }

      if (document.getElementById(id + "_option_left_right"))
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      else
        w_field_option_pos = 'left';

      w_value_disabled = document.getElementById(id + "_value_disabledform_id_temp").value;
      w_use_for_submission = document.getElementById(id + "_use_for_submissionform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_field_option_pos + "*:*w_field_option_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_flow + "*:*w_flow*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_rowcol + "*:*w_rowcol*:*";
      form_fields += w_limit_choice + "*:*w_limit_choice*:*";
      form_fields += w_limit_choice_alert + "*:*w_limit_choice_alert*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_randomize + "*:*w_randomize*:*";
      form_fields += w_allow_other + "*:*w_allow_other*:*";
      form_fields += w_allow_other_num + "*:*w_allow_other_num*:*";
      form_fields += w_value_disabled + "*:*w_value_disabled*:*";
      form_fields += w_use_for_submission + "*:*w_use_for_submission*:*";
      form_fields += w_choices_value.join('***') + "*:*w_choices_value*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_checkbox': {
      if (document.getElementById(id + '_hor'))
        w_flow = "hor"
      else
        w_flow = "ver";

      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
      tt = 0;
      v = 0;
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_elementform_id_temp" + k)) {
          if (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other'))
            if (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') == '1')
              w_allow_other_num = tt;
          w_choices[tt] = document.getElementById(id + "_label_element" + k).innerHTML;
          if (w_choices[tt][w_choices[tt].length - 1] == ' ')
            w_choices[tt] = w_choices[tt].substring(0, w_choices[tt].length - 1);
          w_choices_price[tt] = document.getElementById(id + "_elementform_id_temp" + k).value;
          w_choices_checked[tt] = document.getElementById(id + "_elementform_id_temp" + k).checked;
          if (document.getElementById(id + "_label_element" + k).getAttribute('where'))
            w_choices_params[tt] = document.getElementById(id + "_label_element" + k).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + k).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + k).getAttribute('db_info');
          else
            w_choices_params[tt] = '';
          tt++;
          v = k;
        }

        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);

          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = '';
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1] += '###' + (document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }

      }

      w_quantity = "no";
      w_quantity_value = 1;
      if (document.getElementById(id + "_element_quantityform_id_temp")) {
        w_quantity = 'yes';
        w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
      }
      if (document.getElementById(id + "_option_left_right"))
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      else
        w_field_option_pos = 'left';

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;

      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_field_option_pos + "*:*w_field_option_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_flow + "*:*w_flow*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_price.join('***') + "*:*w_choices_price*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_randomize + "*:*w_randomize*:*";
      form_fields += w_allow_other + "*:*w_allow_other*:*";
      form_fields += w_allow_other_num + "*:*w_allow_other_num*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_property.join('***') + "*:*w_property*:*";

      form_fields += w_property_values.join('***') + "*:*w_property_values*:*";
      form_fields += w_quantity + "*:*w_quantity*:*";
      form_fields += w_quantity_value + "*:*w_quantity_value*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_radio': {
      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;

      if (document.getElementById(id + "_rowcol_numform_id_temp").value) {
        if (document.getElementById(id + '_table_little').getAttribute('for_hor'))
          w_flow = "hor";
        else
          w_flow = "ver";
        w_rowcol = document.getElementById(id + "_rowcol_numform_id_temp").value;
      }
      else {
        if (document.getElementById(id + '_table_little').getAttribute('for_hor'))
          w_flow = "hor";
        else
          w_flow = "ver";

        w_rowcol = 1;
      }

      v = 0;
      tt = 0;
      if (w_flow == "ver") {
        var table_little = document.getElementById(id + '_table_little');
        for (k = 0; k < table_little.childNodes.length; k++) {
          var td_little = table_little.childNodes[k];
          for (m = 0; m < td_little.childNodes.length; m++) {
            var idi = td_little.childNodes[m].getAttribute('idi');
            if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other'))
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1')
                w_allow_other_num = tt;
            w_choices[tt] = document.getElementById(id + "_label_element" + idi).innerHTML;
            w_choices_checked[tt] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
            w_choices_value[tt] = document.getElementById(id + "_elementform_id_temp" + idi).value;
            if (document.getElementById(id + "_label_element" + idi).getAttribute('where'))
              w_choices_params[tt] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
            else
              w_choices_params[tt] = '';
            tt++;
            v = idi;
          }
        }
      }
      else {
        var table_little = document.getElementById(id + '_table_little');
        var tr_little = table_little.childNodes;
        var td_max = tr_little[0].childNodes;

        for (k = 0; k < td_max.length; k++) {
          for (m = 0; m < tr_little.length; m++) {
            if (tr_little[m].childNodes[k]) {
              var td_little = tr_little[m].childNodes[k];
              var idi = td_little.getAttribute('idi');
              if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other'))
                if (document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1')
                  w_allow_other_num = tt;
              w_choices[tt] = document.getElementById(id + "_label_element" + idi).innerHTML;
              w_choices_checked[tt] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
              w_choices_value[tt] = document.getElementById(id + "_elementform_id_temp" + idi).value;
              if (document.getElementById(id + "_label_element" + idi).getAttribute('where'))
                w_choices_params[tt] = document.getElementById(id + "_label_element" + idi).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + idi).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + idi).getAttribute('db_info');
              else
                w_choices_params[tt] = '';
              tt++;
              v = idi;
            }
          }
        }

      }

      if (document.getElementById(id + "_option_left_right"))
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      else
        w_field_option_pos = 'left';

      w_value_disabled = document.getElementById(id + "_value_disabledform_id_temp").value;
      w_use_for_submission = document.getElementById(id + "_use_for_submissionform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_field_option_pos + "*:*w_field_option_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_flow + "*:*w_flow*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_rowcol + "*:*w_rowcol*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_randomize + "*:*w_randomize*:*";
      form_fields += w_allow_other + "*:*w_allow_other*:*";
      form_fields += w_allow_other_num + "*:*w_allow_other_num*:*";
      form_fields += w_value_disabled + "*:*w_value_disabled*:*";
      form_fields += w_use_for_submission + "*:*w_use_for_submission*:*";
      form_fields += w_choices_value.join('***') + "*:*w_choices_value*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_radio': {
      if (document.getElementById(id + '_hor'))
        w_flow = "hor"
      else
        w_flow = "ver";

      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;

      v = 0;
      tt = 0;
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_elementform_id_temp" + k)) {
          if (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other'))
            if (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') == '1')
              w_allow_other_num = tt;
          w_choices[tt] = document.getElementById(id + "_label_element" + k).innerHTML;
          if (w_choices[tt][w_choices[tt].length - 1] == ' ')
            w_choices[tt] = w_choices[tt].substring(0, w_choices[tt].length - 1);
          w_choices_price[tt] = document.getElementById(id + "_elementform_id_temp" + k).value;
          w_choices_checked[tt] = (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('checked') == 'checked');
          if (document.getElementById(id + "_label_element" + k).getAttribute('where'))
            w_choices_params[tt] = document.getElementById(id + "_label_element" + k).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + k).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + k).getAttribute('db_info');
          else
            w_choices_params[tt] = '';
          tt++;
          v = k;
        }

        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);

          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = '';
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1] += '###' + (document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }

      }

      w_quantity = "no";
      w_quantity_value = 1;
      if (document.getElementById(id + "_element_quantityform_id_temp")) {
        w_quantity = 'yes';
        w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
      }
      if (document.getElementById(id + "_option_left_right"))
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      else
        w_field_option_pos = 'left';

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_field_option_pos + "*:*w_field_option_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_flow + "*:*w_flow*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_price.join('***') + "*:*w_choices_price*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_randomize + "*:*w_randomize*:*";
      form_fields += w_allow_other + "*:*w_allow_other*:*";
      form_fields += w_allow_other_num + "*:*w_allow_other_num*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_property.join('***') + "*:*w_property*:*";

      form_fields += w_property_values.join('***') + "*:*w_property_values*:*";
      form_fields += w_quantity + "*:*w_quantity*:*";
      form_fields += w_quantity_value + "*:*w_quantity_value*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_shipping': {
      if (document.getElementById(id + '_hor'))
        w_flow = "hor";
      else
        w_flow = "ver";

      w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
      w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;

      v = 0;
      tt = 0;
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_elementform_id_temp" + k)) {
          if (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other'))
            if (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') == '1')
              w_allow_other_num = tt;
          w_choices[tt] = document.getElementById(id + "_label_element" + k).innerHTML;
          if (w_choices[tt][w_choices[tt].length - 1] == ' ')
            w_choices[tt] = w_choices[tt].substring(0, w_choices[tt].length - 1);
          w_choices_price[tt] = document.getElementById(id + "_elementform_id_temp" + k).value;
          w_choices_checked[tt] = (document.getElementById(id + "_elementform_id_temp" + k).getAttribute('checked') == 'checked');
          if (document.getElementById(id + "_label_element" + k).getAttribute('where'))
            w_choices_params[tt] = document.getElementById(id + "_label_element" + k).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + k).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + k).getAttribute('db_info');
          else
            w_choices_params[tt] = '';
          tt++;
          v = k;
        }

        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);

          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = new Array();
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1].push(document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }

      }
      if (document.getElementById(id + "_option_left_right"))
        w_field_option_pos = document.getElementById(id + "_option_left_right").value;
      else
        w_field_option_pos = 'left';

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_field_option_pos + "*:*w_field_option_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_flow + "*:*w_flow*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_price.join('***') + "*:*w_choices_price*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_randomize + "*:*w_randomize*:*";
      form_fields += w_allow_other + "*:*w_allow_other*:*";
      form_fields += w_allow_other_num + "*:*w_allow_other_num*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_total': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_size = jQuery('#' + id + "paypal_totalform_id_temp").css('width') ? jQuery('#' + id + "paypal_totalform_id_temp").css('width').substring(0, jQuery('#' + id + "paypal_totalform_id_temp").css('width').length - 2) : '300';
      w_hide_total_currency = document.getElementById(id + "_hide_totalcurrency_id_temp").value;
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_hide_total_currency + "*:*w_hide_total_currency*:*";
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_stripe': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_star_rating': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_star_amount = document.getElementById(id + "_star_amountform_id_temp").value;
      w_field_label_col = document.getElementById(id + "_star_colorform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_field_label_col + "*:*w_field_label_col*:*";
      form_fields += w_star_amount + "*:*w_star_amount*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_scale_rating': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_mini_labels = [document.getElementById(id + "_mini_label_worst").innerHTML, document.getElementById(id + "_mini_label_best").innerHTML];

      w_scale_amount = document.getElementById(id + "_scale_amountform_id_temp").value;

      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_scale_amount + "*:*w_scale_amount*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_spinner': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_min_value = document.getElementById(id + "_min_valueform_id_temp").value;
      w_field_max_value = document.getElementById(id + "_max_valueform_id_temp").value;
      w_field_width = document.getElementById(id + "_spinner_widthform_id_temp").value;
      w_field_step = document.getElementById(id + "_stepform_id_temp").value;
      w_field_value = document.getElementById(id + "_elementform_id_temp").getAttribute("aria-valuenow");
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_field_width + "*:*w_field_width*:*";
      form_fields += w_field_min_value + "*:*w_field_min_value*:*";
      form_fields += w_field_max_value + "*:*w_field_max_value*:*";
      form_fields += w_field_step + "*:*w_field_step*:*";
      form_fields += w_field_value + "*:*w_field_value*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_slider': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_min_value = document.getElementById(id + "_slider_min_valueform_id_temp").value;
      w_field_max_value = document.getElementById(id + "_slider_max_valueform_id_temp").value;
      w_field_step = document.getElementById(id + "_slider_stepform_id_temp") && document.getElementById(id + "_slider_stepform_id_temp").value ? document.getElementById(id + "_slider_stepform_id_temp").value : 1;
      w_field_width = document.getElementById(id + "_slider_widthform_id_temp").value;
      w_field_value = document.getElementById(id + "_slider_valueform_id_temp").value;

      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_field_width + "*:*w_field_width*:*";
      form_fields += w_field_min_value + "*:*w_field_min_value*:*";
      form_fields += w_field_max_value + "*:*w_field_max_value*:*";
      form_fields += w_field_step + "*:*w_field_step*:*";
      form_fields += w_field_value + "*:*w_field_value*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_range': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_range_width = document.getElementById(id + "_range_widthform_id_temp").value;
      w_field_range_step = document.getElementById(id + "_range_stepform_id_temp").value;

      w_field_value1 = document.getElementById(id + "_elementform_id_temp0").getAttribute("aria-valuenow");
      w_field_value2 = document.getElementById(id + "_elementform_id_temp1").getAttribute("aria-valuenow");

      atrs = return_attributes(id + '_elementform_id_temp0');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      w_mini_labels = [document.getElementById(id + "_mini_label_from").innerHTML, document.getElementById(id + "_mini_label_to").innerHTML];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_field_range_width + "*:*w_field_range_width*:*";
      form_fields += w_field_range_step + "*:*w_field_range_step*:*";
      form_fields += w_field_value1 + "*:*w_field_value1*:*";
      form_fields += w_field_value2 + "*:*w_field_value2*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_grading': {
      w_total = document.getElementById(id + "_grading_totalform_id_temp").value;
      w_items = [];

      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_label_elementform_id_temp" + k)) {
          w_items.push(document.getElementById(id + "_label_elementform_id_temp" + k).innerHTML);
        }
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_items.join('***') + "*:*w_items*:*";
      form_fields += w_total + "*:*w_total*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_matrix': {
      w_rows = [];
      w_rows[0] = "";
      for (k = 1; k < 100; k++) {
        if (document.getElementById(id + "_label_elementform_id_temp" + k + "_0")) {
          w_rows.push(document.getElementById(id + "_label_elementform_id_temp" + k + "_0").innerHTML);
        }
      }
      w_columns = [];
      w_columns[0] = "";
      for (k = 1; k < 100; k++) {
        if (document.getElementById(id + "_label_elementform_id_temp0_" + k)) {
          w_columns.push(document.getElementById(id + "_label_elementform_id_temp0_" + k).innerHTML);
        }
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_field_input_type = document.getElementById(id + "_input_typeform_id_temp").value;
      w_textbox_size = document.getElementById(id + "_textbox_sizeform_id_temp") ? document.getElementById(id + "_textbox_sizeform_id_temp").value : '100';
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_field_input_type + "*:*w_field_input_type*:*";
      form_fields += w_rows.join('***') + "*:*w_rows*:*";
      form_fields += w_columns.join('***') + "*:*w_columns*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_textbox_size + "*:*w_textbox_size*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_time': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_hhform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_hh = document.getElementById(id + '_hhform_id_temp').value;
      w_mm = document.getElementById(id + '_mmform_id_temp').value;
      if (document.getElementById(id + '_ssform_id_temp')) {
        w_ss = document.getElementById(id + '_ssform_id_temp').value;
        w_sec = "1";
        w_sec_label = document.getElementById(id + '_mini_label_ss').innerHTML;
      }
      else {
        w_ss = "";
        w_sec = "0";
        w_sec_label = 'SS';
      }
      if (document.getElementById(id + '_am_pm_select')) {
        w_am_pm = document.getElementById(id + '_am_pmform_id_temp').value;
        w_time_type = "12";
        w_mini_labels = [document.getElementById(id + '_mini_label_hh').innerHTML, document.getElementById(id + '_mini_label_mm').innerHTML, w_sec_label, document.getElementById(id + '_mini_label_am_pm').innerHTML];
      }
      else {
        w_am_pm = 0;
        w_time_type = "24";
        w_mini_labels = [document.getElementById(id + '_mini_label_hh').innerHTML, document.getElementById(id + '_mini_label_mm').innerHTML, w_sec_label, 'AM/PM'];
      }
      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_time_type + "*:*w_time_type*:*";
      form_fields += w_am_pm + "*:*w_am_pm*:*";
      form_fields += w_sec + "*:*w_sec*:*";
      form_fields += w_hh + "*:*w_hh*:*";
      form_fields += w_mm + "*:*w_mm*:*";
      form_fields += w_ss + "*:*w_ss*:*";
      form_fields += w_mini_labels.join('***') + "*:*w_mini_labels*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_date': {
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_date = document.getElementById(id + '_elementform_id_temp').value;
      w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
      w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
      w_disable_past_days = document.getElementById(id + '_dis_past_daysform_id_temp') ? document.getElementById(id + '_dis_past_daysform_id_temp').value : 'no';

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_date + "*:*w_date*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_format + "*:*w_format*:*";
      form_fields += w_but_val + "*:*w_but_val*:*";
      form_fields += w_disable_past_days + "*:*w_disable_past_days*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_date_new': {
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
      w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
      w_start_day = document.getElementById(id + '_start_dayform_id_temp').value;
      w_default_date = document.getElementById(id + '_default_date_id_temp').value;
      w_min_date = document.getElementById(id + '_min_date_id_temp').value;
      w_max_date = document.getElementById(id + '_max_date_id_temp').value;
      w_invalid_dates = document.getElementById(id + '_invalid_dates_id_temp').value;
      w_hide_time = document.getElementById(id + '_hide_timeform_id_temp').value;
      w_show_image = document.getElementById(id + '_show_imageform_id_temp').value;

      w_date = document.getElementById(id + '_elementform_id_temp').value;

      w_disable_past_days = document.getElementById(id + '_dis_past_daysform_id_temp') ? document.getElementById(id + '_dis_past_daysform_id_temp').value : 'no';

      var show_week_days_input = document.getElementById(id + "_show_week_days");

      w_sunday = show_week_days_input.getAttribute('sunday');
      w_monday = show_week_days_input.getAttribute('monday');
      w_tuesday = show_week_days_input.getAttribute('tuesday');
      w_wednesday = show_week_days_input.getAttribute('wednesday');
      w_thursday = show_week_days_input.getAttribute('thursday');
      w_friday = show_week_days_input.getAttribute('friday');
      w_saturday = show_week_days_input.getAttribute('saturday');

      w_show_days = [w_sunday, w_monday, w_tuesday, w_wednesday, w_thursday, w_friday, w_saturday];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_date + "*:*w_date*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_show_image + "*:*w_show_image*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_format + "*:*w_format*:*";
      form_fields += w_start_day + "*:*w_start_day*:*";

      form_fields += w_default_date + "*:*w_default_date*:*";
      form_fields += w_min_date + "*:*w_min_date*:*";
      form_fields += w_max_date + "*:*w_max_date*:*";
      form_fields += w_invalid_dates + "*:*w_invalid_dates*:*";
      form_fields += w_show_days.join('***') + "*:*w_show_days*:*";
      form_fields += w_hide_time + "*:*w_hide_time*:*";

      form_fields += w_but_val + "*:*w_but_val*:*";
      form_fields += w_disable_past_days + "*:*w_disable_past_days*:*";
      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_date_range': {
      atrs = return_attributes(id + '_elementform_id_temp0');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_date = document.getElementById(id + '_elementform_id_temp0').value + ',' + document.getElementById(id + '_elementform_id_temp1').value;
      w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
      w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
      w_start_day = document.getElementById(id + '_start_dayform_id_temp').value;
      w_default_date_start = document.getElementById(id + '_default_date_id_temp_start').value;
      w_default_date_end = document.getElementById(id + '_default_date_id_temp_end').value;
      w_min_date = document.getElementById(id + '_min_date_id_temp').value;
      w_max_date = document.getElementById(id + '_max_date_id_temp').value;
      w_invalid_dates = document.getElementById(id + '_invalid_dates_id_temp').value;
      w_hide_time = document.getElementById(id + '_hide_timeform_id_temp').value;
      w_show_image = document.getElementById(id + '_show_imageform_id_temp').value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;

      s = document.getElementById(id + "_elementform_id_temp0").style.width;
      w_size = s.substring(0, s.length - 2);

      w_disable_past_days = document.getElementById(id + '_dis_past_daysform_id_temp') ? document.getElementById(id + '_dis_past_daysform_id_temp').value : 'no';

      var show_week_days_input = document.getElementById(id + "_show_week_days");

      w_sunday = show_week_days_input.getAttribute('sunday');
      w_monday = show_week_days_input.getAttribute('monday');
      w_tuesday = show_week_days_input.getAttribute('tuesday');
      w_wednesday = show_week_days_input.getAttribute('wednesday');
      w_thursday = show_week_days_input.getAttribute('thursday');
      w_friday = show_week_days_input.getAttribute('friday');
      w_saturday = show_week_days_input.getAttribute('saturday');

      w_show_days = [w_sunday, w_monday, w_tuesday, w_wednesday, w_thursday, w_friday, w_saturday];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_date + "*:*w_date*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_show_image + "*:*w_show_image*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_format + "*:*w_format*:*";
      form_fields += w_start_day + "*:*w_start_day*:*";

      form_fields += w_default_date_start + "*:*w_default_date_start*:*";
      form_fields += w_default_date_end + "*:*w_default_date_end*:*";
      form_fields += w_min_date + "*:*w_min_date*:*";
      form_fields += w_max_date + "*:*w_max_date*:*";
      form_fields += w_invalid_dates + "*:*w_invalid_dates*:*";
      form_fields += w_show_days.join('***') + "*:*w_show_days*:*";
      form_fields += w_hide_time + "*:*w_hide_time*:*";
      form_fields += w_but_val + "*:*w_but_val*:*";

      form_fields += w_disable_past_days + "*:*w_disable_past_days*:*";
      for ( j = 0; j < w_attr_name.length; j++ ) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }

      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_date_fields': {
      atrs = return_attributes(id + '_dayform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_day = document.getElementById(id + '_dayform_id_temp').value;
      w_month = document.getElementById(id + '_monthform_id_temp').value;
      w_year = document.getElementById(id + '_yearform_id_temp').value;
      w_day_type = document.getElementById(id + '_dayform_id_temp').tagName;
      w_month_type = document.getElementById(id + '_monthform_id_temp').tagName;
      w_year_type = document.getElementById(id + '_yearform_id_temp').tagName;
      w_day_label = document.getElementById(id + '_day_label').innerHTML;
      w_month_label = document.getElementById(id + '_month_label').innerHTML;
      w_year_label = document.getElementById(id + '_year_label').innerHTML;
      w_min_day = document.getElementById(id + '_min_day_id_temp').value;
      w_min_month = document.getElementById(id + '_min_month_id_temp').value;
      w_min_year = document.getElementById(id + '_min_year_id_temp').value;
      w_min_dob_alert = document.getElementById(id + '_min_dob_alert_id_temp').value;

      s = document.getElementById(id + '_dayform_id_temp').style.width;
      w_day_size = s.substring(0, s.length - 2);

      s = document.getElementById(id + '_monthform_id_temp').style.width;
      w_month_size = s.substring(0, s.length - 2);

      s = document.getElementById(id + '_yearform_id_temp').style.width;
      w_year_size = s.substring(0, s.length - 2);

      w_from = document.getElementById(id + '_yearform_id_temp').getAttribute('from');
      w_to = document.getElementById(id + '_yearform_id_temp').getAttribute('to');

      w_divider = document.getElementById(id + '_separator1').innerHTML;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_day + "*:*w_day*:*";
      form_fields += w_month + "*:*w_month*:*";
      form_fields += w_year + "*:*w_year*:*";
      form_fields += w_day_type + "*:*w_day_type*:*";
      form_fields += w_month_type + "*:*w_month_type*:*";
      form_fields += w_year_type + "*:*w_year_type*:*";
      form_fields += w_day_label + "*:*w_day_label*:*";
      form_fields += w_month_label + "*:*w_month_label*:*";
      form_fields += w_year_label + "*:*w_year_label*:*";
      form_fields += w_day_size + "*:*w_day_size*:*";
      form_fields += w_month_size + "*:*w_month_size*:*";
      form_fields += w_year_size + "*:*w_year_size*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_from + "*:*w_from*:*";
      form_fields += w_to + "*:*w_to*:*";
      form_fields += w_min_day + "*:*w_min_day*:*";
      form_fields += w_min_month + "*:*w_min_month*:*";
      form_fields += w_min_year + "*:*w_min_year*:*";
      form_fields += w_min_dob_alert + "*:*w_min_dob_alert*:*";
      form_fields += w_divider + "*:*w_divider*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_own_select': {
      tt = 0;
      jQuery('#' + id + '_elementform_id_temp option').each(function () {
        w_choices[tt] = jQuery(this).html();
        w_choices_value[tt] = jQuery(this).val();
        w_choices_checked[tt] = jQuery(this)[0].selected;
        if (jQuery(this).attr('where'))
          w_choices_params[tt] = jQuery(this).attr('where') + '[where_order_by]' + jQuery(this).attr('order_by') + '[db_info]' + jQuery(this).attr('db_info');
        else
          w_choices_params[tt] = '';

        if (jQuery(this).val())
          w_choices_disabled[tt] = false;
        else
          w_choices_disabled[tt] = true;

        tt++;
      });

      w_value_disabled = document.getElementById(id + '_value_disabledform_id_temp').value;
      w_use_for_submission = document.getElementById(id + "_use_for_submissionform_id_temp").value;
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_choices_disabled.join('***') + "*:*w_choices_disabled*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_value_disabled + "*:*w_value_disabled*:*";
      form_fields += w_use_for_submission + "*:*w_use_for_submission*:*";
      form_fields += w_choices_value.join('***') + "*:*w_choices_value*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_paypal_select': {
      tt = 0;
      jQuery('#' + id + '_elementform_id_temp option').each(function () {
        w_choices[tt] = jQuery(this).html();
        w_choices_price[tt] = jQuery(this).val();
        w_choices_checked[tt] = jQuery(this)[0].selected;
        if (jQuery(this).attr('where'))
          w_choices_params[tt] = jQuery(this).attr('where') + '[where_order_by]' + jQuery(this).attr('order_by') + '[db_info]' + jQuery(this).attr('db_info');
        else
          w_choices_params[tt] = '';

        if (jQuery(this)[0].value == "")
          w_choices_disabled[tt] = true;
        else
          w_choices_disabled[tt] = false;

        tt++;
      });

      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_propertyform_id_temp" + k)) {
          w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
          if (document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length) {
            w_property_values[w_property.length - 1] = '';
            for (m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++) {
              w_property_values[w_property.length - 1] += '###' + (document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
            }
          }
          else {
            w_property_values.push('');
          }
        }
      }

      w_quantity = "no";
      w_quantity_value = 1;
      if (document.getElementById(id + "_element_quantityform_id_temp")) {
        w_quantity = 'yes';
        w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_choices.join('***') + "*:*w_choices*:*";
      form_fields += w_choices_price.join('***') + "*:*w_choices_price*:*";
      form_fields += w_choices_checked.join('***') + "*:*w_choices_checked*:*";
      form_fields += w_choices_disabled.join('***') + "*:*w_choices_disabled*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_quantity + "*:*w_quantity*:*";
      form_fields += w_quantity_value + "*:*w_quantity_value*:*";
      form_fields += w_choices_params.join('***') + "*:*w_choices_params*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_property.join('***') + "*:*w_property*:*";
      form_fields += w_property_values.join('***') + "*:*w_property_values*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_country': {
      w_countries = [];
      select_ = document.getElementById(id + '_elementform_id_temp');
      k = select_.childNodes.length;
      for (j = 0; j < k; j++) {
        w_countries.push(select_.childNodes[j].value);
      }
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_size + "*:*w_size*:*";
      form_fields += w_countries.join('***') + "*:*w_countries*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_file_upload': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_destination = document.getElementById(id + "_destination").value.replace("***destinationverj" + id + "***", "").replace("***destinationskizb" + id + "***", "");
      w_extension = document.getElementById(id + "_extension").value.replace("***extensionverj" + id + "***", "").replace("***extensionskizb" + id + "***", "");
      w_max_size = document.getElementById(id + "_max_size").value.replace("***max_sizeverj" + id + "***", "").replace("***max_sizeskizb" + id + "***", "");
      w_multiple = (document.getElementById(id + "_elementform_id_temp").getAttribute('multiple') ? 'yes' : 'no');

      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_destination + "*:*w_destination*:*";
      form_fields += w_extension + "*:*w_extension*:*";
      form_fields += w_max_size + "*:*w_max_size*:*";
      form_fields += w_required + "*:*w_required*:*";
      form_fields += w_multiple + "*:*w_multiple*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_captcha': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_digit = document.getElementById("_wd_captchaform_id_temp").getAttribute("digit");
      atrs = return_attributes('_wd_captchaform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_digit + "*:*w_digit*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_arithmetic_captcha': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_count = document.getElementById("_wd_arithmetic_captchaform_id_temp").getAttribute("operations_count");
      w_operations = document.getElementById("_wd_arithmetic_captchaform_id_temp").getAttribute("operations");
      w_input_size = document.getElementById("_wd_arithmetic_captchaform_id_temp").getAttribute("input_size");
      atrs = return_attributes('_wd_captchaform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_count + "*:*w_count*:*";
      form_fields += w_operations + "*:*w_operations*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_input_size + "*:*w_input_size*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_recaptcha': {
      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      w_type = document.getElementById("wd_recaptchaform_id_temp").getAttribute("w_type");
      w_position = document.getElementById("wd_recaptchaform_id_temp").getAttribute("position");

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_type + "*:*w_type*:*";
      form_fields += w_position + "*:*w_position*:*";

      form_fields += "*:*new_field*:*";
      document.getElementById("public_key").value = document.getElementById("wd_recaptchaform_id_temp").getAttribute("public_key");
      document.getElementById("private_key").value = document.getElementById("wd_recaptchaform_id_temp").getAttribute("private_key");

      break;
    }
    case 'type_map': {
      w_lat = [];
      w_long = [];
      w_info = [];

      w_center_x = document.getElementById(id + "_elementform_id_temp").getAttribute("center_x");
      w_center_y = document.getElementById(id + "_elementform_id_temp").getAttribute("center_y");
      w_zoom = document.getElementById(id + "_elementform_id_temp").getAttribute("zoom");
      w_width = document.getElementById(id + "_elementform_id_temp").style.width == "" ? "" : parseInt(document.getElementById(id + "_elementform_id_temp").style.width);
      w_height = parseInt(document.getElementById(id + "_elementform_id_temp").style.height);

      for (j = 0; j <= 20; j++) {
        if (document.getElementById(id + "_elementform_id_temp").getAttribute("lat" + j)) {
          w_lat.push(document.getElementById(id + "_elementform_id_temp").getAttribute("lat" + j));
          w_long.push(document.getElementById(id + "_elementform_id_temp").getAttribute("long" + j));
          w_info.push(document.getElementById(id + "_elementform_id_temp").getAttribute("info" + j));
        }
      }
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_center_x + "*:*w_center_x*:*";
      form_fields += w_center_y + "*:*w_center_y*:*";
      form_fields += w_long.join('***') + "*:*w_long*:*";
      form_fields += w_lat.join('***') + "*:*w_lat*:*";
      form_fields += w_zoom + "*:*w_zoom*:*";
      form_fields += w_width + "*:*w_width*:*";
      form_fields += w_height + "*:*w_height*:*";
      form_fields += w_info.join('***') + "*:*w_info*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_mark_map': {
      w_info = document.getElementById(id + "_elementform_id_temp").getAttribute("info0");
      w_long = document.getElementById(id + "_elementform_id_temp").getAttribute("long0");
      w_lat = document.getElementById(id + "_elementform_id_temp").getAttribute("lat0");
      w_zoom = document.getElementById(id + "_elementform_id_temp").getAttribute("zoom");
      w_width = document.getElementById(id + "_elementform_id_temp").style.width == "" ? "" : parseInt(document.getElementById(id + "_elementform_id_temp").style.width);
      w_height = parseInt(document.getElementById(id + "_elementform_id_temp").style.height);
      w_center_x = document.getElementById(id + "_elementform_id_temp").getAttribute("center_x");
      w_center_y = document.getElementById(id + "_elementform_id_temp").getAttribute("center_y");

      w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_field_label_size + "*:*w_field_label_size*:*";
      form_fields += w_field_label_pos + "*:*w_field_label_pos*:*";
      form_fields += w_hide_label + "*:*w_hide_label*:*";
      form_fields += w_center_x + "*:*w_center_x*:*";
      form_fields += w_center_y + "*:*w_center_y*:*";
      form_fields += w_long + "*:*w_long*:*";
      form_fields += w_lat + "*:*w_lat*:*";
      form_fields += w_zoom + "*:*w_zoom*:*";
      form_fields += w_width + "*:*w_width*:*";
      form_fields += w_height + "*:*w_height*:*";
      form_fields += w_info + "*:*w_info*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_submit_reset': {
      atrs = return_attributes(id + '_element_submitform_id_temp');
      w_act = !(document.getElementById(id + "_element_resetform_id_temp").style.display == "none");
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];
      w_submit_title = document.getElementById(id + "_element_submitform_id_temp").value;
      w_reset_title = document.getElementById(id + "_element_resetform_id_temp").value;

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_submit_title + "*:*w_submit_title*:*";
      form_fields += w_reset_title + "*:*w_reset_title*:*";
      form_fields += w_class + "*:*w_class*:*";
      form_fields += w_act + "*:*w_act*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_button': {
      w_title = new Array();

      w_func = new Array();
      tt = 0;
      v = 0;
      for (k = 0; k < 100; k++) {
        if (document.getElementById(id + "_elementform_id_temp" + k)) {
          w_title[tt] = document.getElementById(id + "_elementform_id_temp" + k).value;
          w_func[tt] = document.getElementById(id + "_elementform_id_temp" + k).getAttribute("onclick");
          tt++;
          v = k;
        }
      }
      atrs = return_attributes(id + '_elementform_id_temp' + v);
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_field_label + "*:*w_field_label*:*";
      form_fields += w_title.join('***') + "*:*w_title*:*";
      form_fields += w_func.join('***') + "*:*w_func*:*";
      form_fields += w_class + "*:*w_class*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_hidden': {
      w_value = document.getElementById(id + "_elementform_id_temp").value;
      w_name = document.getElementById(id + "_elementform_id_temp").name;

      atrs = return_attributes(id + '_elementform_id_temp');
      w_attr_name = atrs[0];
      w_attr_value = atrs[1];

      form_fields += w_name + "*:*w_field_label*:*";
      form_fields += w_name + "*:*w_name*:*";
      form_fields += w_value + "*:*w_value*:*";

      for (j = 0; j < w_attr_name.length; j++) {
        form_fields += w_attr_name[j] + "=" + w_attr_value[j] + "*:*w_attr_name*:*";
      }
      form_fields += "*:*new_field*:*";
      break;
    }
    case 'type_signature': {
      w_hide_label = document.getElementById(id + '_hide_labelform_id_temp').value;
      w_canvas_width = document.getElementById(id + '_canvas_widthform_id_temp').value;
      w_canvas_height = document.getElementById(id + '_canvas_heightform_id_temp').value;
      w_destination = document.getElementById(id + '_destination').value.replace('***destinationskizb' + id + '***', '').replace('***destinationverj' + id + '***', '');

      form_fields += w_field_label + '*:*w_field_label*:*';
      form_fields += w_field_label_pos + '*:*w_field_label_pos*:*';
      form_fields += w_hide_label + '*:*w_hide_label*:*';
      form_fields += w_required + '*:*w_required*:*';
      form_fields += w_field_label_size + '*:*w_field_label_size*:*';
      form_fields += w_canvas_width + '*:*w_canvas_width*:*';
      form_fields += w_canvas_height + '*:*w_canvas_height*:*';
      form_fields += w_class + '*:*w_class*:*';
      form_fields += w_destination + '*:*w_destination*:*';
      form_fields += '*:*new_field*:*';
      break
    }
  }
}
