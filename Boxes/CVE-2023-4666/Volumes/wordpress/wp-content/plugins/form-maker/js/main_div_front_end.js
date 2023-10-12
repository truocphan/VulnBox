var fm_rated = false;

jQuery(window).on("load", function () {
  if (jQuery(".g-recaptcha").length > 0) {
    if (jQuery(".g-recaptcha").data("render") != 1) {
      fmRecaptchaInit( 0 );
    }
  }
  validate_received_data_from_url();

  /* Don't show payments with not succeeded status in Submissions table, when "After payment has been successfully completed." option is enabled */
  if ( jQuery(".submissions").length ) {
    jQuery(".submissions td").each(function() {
      var get_payment_status = jQuery(this).data("status");
      if ( get_payment_status == "0" ) {
        jQuery(this).parent().hide();
      }
    });
  }
  fm_run_cookie_init();
});

jQuery(document).ready(function() {
    /* Adding margin from top to fields if the field doesn't have label, to keep one line design */
    jQuery(".wdform_column").each(function () {
      if ((jQuery(this).find(".wdform-label-section.wd-hidden").length > 0 || jQuery(this).find(".wdform-label-section.wd-width-30").length > 0) && jQuery(this).find(".wdform-label-section.wd-flex-row").length > 0) {
        jQuery(this).find(".wdform-label-section.wd-hidden, .wdform-label-section.wd-width-30").parent().addClass("fm_empty_margin");
      }
    });
});

/* This function need as when content is cached cookie doesn't work and show submit error */
function fm_run_cookie_init() {
  var form_ids = {};
  var i =0;
  jQuery(".fm-form").each(function( index ) {
    var id = jQuery(this).attr("id");
    id = id.replace('form','');
    form_ids[i] = id;
    i++;
  });
  jQuery.ajax( {
    url: fm_objectL10n.fm_frontend_ajax_url,
    data: {
      action: "fm_init_cookies",
      method: "POST",
      dataType: "json",
      form_ids: form_ids,
    },
    success: function ( result ) {
      try {
        var res = JSON.parse(result);
        jQuery.each( res, function( index, value ) {
          var form_id = value.form_id;
          var field_validation_value = value.field_validation_value;
          jQuery(document).find("#fm_empty_field_validation" + form_id).attr("value", field_validation_value);
        });
      } catch (e) {
        return false;
      }
    }
  });
}

/**
 * Function called from fm_script_ready(+form_id) function which run during form load
 *
 * there are three actions in the function
 * -set format to datepickers
 * -scroll to form notice after submit if exists
 * -scroll to captcha message if exists
 *
 * */
function form_load_actions() {
  jQuery(".wd-datepicker").each(function () {
    jQuery(this).datepicker();
    jQuery(this).datepicker("option", "dateFormat", jQuery(this).data("format"));
  });

  // Scroll to form notice.
  var fm_message = jQuery(".fm-form").find(".fm-message");
  if ( fm_message.length !== 0 && fm_message.closest('.fm-popover-content').length == 0 && fm_message.closest('.fm-scrollbox-form').length == 0 && fm_message.closest('.fm-topbar').length == 0 ) {
    jQuery(window).scrollTop(jQuery(".fm-message").offset().top - 100);
    var is_safari = navigator.userAgent.toLowerCase().indexOf('safari/') > -1;
    if( is_safari ) {
      document.scrollingElement.scrollTop = jQuery(".fm-message").offset().top - 100; / For Safari./
    }
  }

  // Scroll to captcha field notice.
  if ( jQuery(".fm-form").find(".message_captcha").length !== 0 ) {
    var form_id = jQuery('.fm-form').attr('name').split("form")[1];
    if ( jQuery("#form" + form_id + " .message_captcha").length !== 0 ) {
      var element_offset = jQuery(jQuery("#form" + form_id + " .message_captcha")).offset().top;
      jQuery(".fm-form").find(".message_captcha").parents('.wdform-field').find('.wdform-label').addClass('error_label');
      jQuery('html').animate({scrollTop: element_offset-150 },500);
      document.scrollingElement.scrollTop = element_offset - 150; /* For Safari.*/
    }
  }

}

function set_total_value(form_id) {
  var getDataHideCurreny = jQuery('.paypal_total'+form_id).parent().parent().attr( "data-hide-currency" );
  if(getDataHideCurreny=="yes") {
    var toggle_currency = "wd-hidden";
    var FormCurrency = '';
  }
  else {
    var toggle_currency = "wd-inline-block";
    var FormCurrency = window["FormCurrency_" + form_id] + ' ';
  }
  if(jQuery('.paypal_total'+form_id).length==0) {
    return;
  }
  var div_paypal_show = jQuery('.paypal_total'+form_id);
  var div_paypal_products = jQuery('.paypal_products'+form_id);
  var div_paypal_tax = jQuery('.paypal_tax'+form_id);
  var input_paypal_total = jQuery('.input_paypal_total'+form_id);
  var total=0;
  var total_shipping=0;
  div_paypal_products.html('');
  div_paypal_tax.html('');
  n = parseInt(jQuery('#counter'+form_id).val());
  jQuery("#form" +form_id+ " div[type='type_paypal_checkbox'], #form" +form_id+ " div[type='type_paypal_radio']").each(function() {
    var parent_div = jQuery(this).parent();
    if ('none' != parent_div.css('display')) {
      var id = parent_div.attr('wdid');
      paypal_checkbox_qty = (jQuery('#wdform_' + id + "_element_quantity" + form_id).val()) ? jQuery('#wdform_' + id + "_element_quantity" + form_id).val() : 0;
      jQuery(this).find('input:checked').each(
        function () {
          label = jQuery("label[for='" + jQuery(this).attr('id') + "']").html();
          span_value = '<span class="' + toggle_currency + '">' + FormCurrency + '</span>' + jQuery(this).val() + (jQuery('#wdform_' + id + "_element_quantity" + form_id).length != 0 ? ' x ' + paypal_checkbox_qty : '');
          total = total + jQuery(this).val() * parseInt((jQuery('#wdform_' + id + "_element_quantity" + form_id).length != 0 ? paypal_checkbox_qty : 1));
          div_paypal_products.html(div_paypal_products.html() + "<div>" + label + ' - ' + span_value + "</div>");
        }
      );
    }
  });
  jQuery("#form" +form_id+ " div[type='type_paypal_shipping']").each(function() {
    var parent_div = jQuery(this).parent();
    if ('none' != parent_div.css('display')) {
      var id = parent_div.attr('wdid');
      paypal_shipping_qty = (jQuery('#wdform_' + id + "_element_quantity" + form_id).val()) ? jQuery('#wdform_' + id + "_element_quantity" + form_id).val() : 0;
      jQuery(this).find('input:checked').each(
        function () {
          label = jQuery("label[for='" + jQuery(this).attr('id') + "']").html();
          span_value = '<span class="' + toggle_currency + '">' + FormCurrency + '</span>' + jQuery(this).val() + (jQuery('#wdform_' + id + "_element_quantity" + form_id).length != 0 ? ' x ' + paypal_shipping_qty : '');
          total_shipping = total_shipping + jQuery(this).val() * parseInt((jQuery('#wdform_' + id + "_element_quantity" + form_id).length != 0 ? paypal_shipping_qty : 1));
          div_paypal_products.html(div_paypal_products.html() + "<div>" + label + ' - ' + span_value + "</div>");
        }
      );
    }
  });
  jQuery("#form" +form_id+ " div[type='type_paypal_select']").each(function() {
    var parent_div = jQuery(this).parent();
    if ('none' != parent_div.css('display')) {
      var id = parent_div.attr('wdid');
      paypal_select_qty = (jQuery('#wdform_' + id + "_element_quantity" + form_id).val()) ? jQuery('#wdform_' + id + "_element_quantity" + form_id).val() : 0;
      if (jQuery(this).find('select').val() != '') {
        label = jQuery(this).find('select option:selected').html();
        span_value = '<span class="' + toggle_currency + '">' + FormCurrency + '</span>' + jQuery(this).find('select').val() + (jQuery('#wdform_' + id + "_element_quantity" + form_id).length != 0 ? ' x ' + paypal_select_qty : '');
        total = total + jQuery(this).find('select').val() * parseInt((jQuery('#wdform_' + id + "_element_quantity" + form_id).length != 0 ? paypal_select_qty : 1));
        div_paypal_products.html(div_paypal_products.html() + "<div>" + label + ' - ' + span_value + "</div>");
      }
    }
  });
  jQuery("#form" +form_id+ " div[type='type_paypal_price']").each(function() {
    var parent_div = jQuery(this).parent();
    if ('none' != parent_div.css('display')) {
      var id = parent_div.attr('wdid');
      label = jQuery(this).find('.wdform-label').html();
      cents = '00';
      dollars = '0';
      if (jQuery('#wdform_' + id + "_element_dollars" + form_id).val() != '') {
        dollars = jQuery('#wdform_' + id + "_element_dollars" + form_id).val();
      }
      if (jQuery('#wdform_' + id + "_element_cents" + form_id).val() != '') {
        if (jQuery('#wdform_' + id + "_element_cents" + form_id).val().length == 1) {
          cents = '0' + jQuery('#wdform_' + id + "_element_cents" + form_id).val();
        }
        else {
          cents = jQuery('#wdform_' + id + "_element_cents" + form_id).val();
        }
        span_value = '<span class="' + toggle_currency + '">' + FormCurrency + '</span>' + dollars + '.' + cents;
        total = total + parseFloat(dollars + '.' + cents);
        div_paypal_products.html(div_paypal_products.html() + "<div>" + label + ' - ' + span_value + "</div>");
      }
    }
  });
  jQuery("#form" +form_id+ " div[type='type_paypal_price_new']").each(function() {
    var parent_div = jQuery(this).parent();
    if ('none' != parent_div.css('display')) {
      var id = parent_div.attr('wdid');
      label = jQuery(this).find('.wdform-label').html();
      dollars = '0';
      if (jQuery('#wdform_' + id + "_element" + form_id).val() != '') {
        dollars = jQuery('#wdform_' + id + "_element" + form_id).val();
      }
      span_value = '<span class="' + toggle_currency + '">' + FormCurrency + '</span>' + dollars;
      total = total + parseFloat(dollars);
      div_paypal_products.html(div_paypal_products.html() + "<div>" + label + ' - ' + span_value + "</div>");
    }
  });
  var FormPaypalTax = eval("FormPaypalTax_" + form_id);
  if ( FormPaypalTax != 0 ) {
    div_paypal_tax.html('Tax: ' + FormCurrency + (((total)*FormPaypalTax) / 100).toFixed(2));
  }

  jQuery('.div_total'+form_id).html('<span class="' + toggle_currency + '">' + FormCurrency + '</span>' + (parseFloat((total *(1+FormPaypalTax/100)).toFixed(2))+total_shipping).toFixed(2));
  input_paypal_total.val(FormCurrency + (parseFloat((total *(1+FormPaypalTax/100)).toFixed(2))+total_shipping).toFixed(2));

}

function check_isnum_or_minus(e) {
  var chCode1 = e.which || e.keyCode;
  if (chCode1 != 45) {
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {
      return false;
    }
  }
  return true;
}

function sum_grading_values(num,form_id) {
  var sum = 0;
  for(var k=0; k<100;k++) {
    if(document.getElementById(num+'_element'+form_id+'_'+k)) {
      if(document.getElementById(num+'_element'+form_id+'_'+k).value) {
        sum = sum+parseInt(document.getElementById(num+'_element'+form_id+'_'+k).value);
      }
    }
    if(document.getElementById(num+'_total_element'+form_id)) {
      if(sum > document.getElementById(num+'_total_element'+form_id).innerHTML) {
        document.getElementById(num+'_text_element'+form_id).innerHTML =" "+ fm_objectL10n.fm_grading_text + " " + document.getElementById(num+'_total_element'+form_id).innerHTML;
      }
      else {
        document.getElementById(num+'_text_element'+form_id).innerHTML="";
      }
    }
  }
  if(document.getElementById(num+'_sum_element'+form_id)) {
    document.getElementById(num+'_sum_element'+form_id).innerHTML = sum;
  }
}

function change_src(id,el_id,form_id,color) {
  if( fm_rated == false ) {
    for(var j=0;j<=id;j++) {
      document.getElementById(el_id+'_star_'+j+'_'+form_id).src=fm_objectL10n.plugin_url+"/images/star_"+color+'.png';
    }
  }
}

function reset_src(id,el_id, form_id) {
  if( fm_rated == false ) {
    for(var j=0;j<=id;j++) {
      document.getElementById(el_id+'_star_'+j+'_'+form_id).src=fm_objectL10n.plugin_url+"/images/star.png";
    }
  }
}

function select_star_rating(id,el_id,form_id, color,star_amount) {
  fm_rated = true;
  for(var j=0;j<=id;j++) {
    document.getElementById(el_id+'_star_'+j+'_'+form_id).src=fm_objectL10n.plugin_url+"/images/star_"+color+".png";
  }
  for(var k=id+1;k<=star_amount-1;k++) {
    document.getElementById(el_id+'_star_'+k+'_'+form_id).src=fm_objectL10n.plugin_url+"/images/star.png";
  }
  document.getElementById(el_id+'_selected_star_amount'+form_id).value=id+1;
  /* Trigger change event after rating change to make work condition.*/
  jQuery("#" + el_id+'_selected_star_amount'+form_id).trigger("change");
}

function show_other_input(num, form_id) {
  var element_other = jQuery('.form' + form_id + ' [id^=' + num + '_element' + form_id + '][other="1"]');
  var parent_ = element_other.parent();
  var br = document.createElement('br');
  br.setAttribute("id", num + "_other_br" + form_id);
  var elem_id = num.split("_")[1];
  var el_other = document.createElement('input');
  el_other.setAttribute("id", num + "_other_input" + form_id);
  el_other.setAttribute("name", num + "_other_input" + form_id);
  el_other.setAttribute("type", "text");
  el_other.setAttribute("class", "other_input");
  el_other.setAttribute("onchange", "other_input_change(this, '" + form_id + "', '" + elem_id + "')");
  if ( parent_.children('input').length < 2) { // to prevent multiple additions of other input
  parent_.append(br);
  parent_.append(el_other);
  }
}

function other_input_change(elem, form_id, wdid) {
  if (jQuery(elem).val() == "") {
    wd_is_filled(form_id, wdid);
  }
  else {
    jQuery("#form" + form_id + " #wd_required_" + wdid).remove();
    jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:first .error_label").removeClass("error_label");
  }
}

function check_isnum(e) {
  var chCode1 = e.which || e.keyCode;
  if (jQuery.inArray(chCode1,[46,8,9,27,13,190]) != -1 || e.ctrlKey === true || (chCode1 >= 35 && chCode1 < 39) || chCode1 == 45) {
    return true;
  }
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {
    return false;
  }
  return true;
}

function captcha_refresh(id,genid) {
  srcArr=document.getElementById(id+genid).src.split("&r=");
  document.getElementById(id+genid).src=srcArr[0]+'&r='+Math.floor(Math.random()*100);
  document.getElementById(id+"_input"+genid).value='';
  document.getElementById(id+genid).style.display="inline-block";
}

function set_checked(id,j,form_id) {
  checking=document.getElementById(id+"_element"+form_id+j);
  if(checking.getAttribute('other')) {
    if(checking.getAttribute('other')==1) {
      if(!checking.checked) {
        if(document.getElementById(id+"_other_input"+form_id)) {
          document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_br"+form_id));
          document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_input"+form_id));
        }
        return false;
      }
    }
  }
  return true;
}

jQuery(document).on('change', '.wdform-element-section input[type="checkbox"],.wdform-element-section input[type="radio"], .wdform-quantity', function() {
  var getDataLimit = jQuery(this).closest(".wdform-field").attr( "data-limit" );
  var getDataLimitText = jQuery(this).closest(".wdform-field").attr( "data-limit-text" );
  var countCheckedOptions = jQuery(this).closest(".wdform-element-section").find("input[type='checkbox']:checked").length;
  var findOtherField = jQuery(this).closest(".wdform-element-section").find(".other_input");
  if (countCheckedOptions > getDataLimit) {
    this.checked = false;
    if(this.getAttribute('other')==1) {
      if(!countCheckedOptions.checked) {
        if (findOtherField) {
          findOtherField.prev("br").remove();
          findOtherField.remove();
        }
      }
    }
    jQuery(this).closest(".wdform-field").find(".wdform-label").addClass('error_label');
    if(jQuery(".fm-error").length == 0) {
      jQuery(this).closest(".wdform_row").append("<div class='fm-error'>" + getDataLimitText + "</div>");
    }
  }
  else{
    jQuery(this).closest(".wdform-field").find(".wdform-label").removeClass('error_label');
    jQuery('.fm-error').remove();
  }
});

function set_default(id, j, form_id) {
  if(document.getElementById(id+"_other_input"+form_id)) {
    document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_br"+form_id));
    document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_input"+form_id));
  }
}

function add_0(that) {
  if (jQuery(that).val().length == 1) {
    jQuery(that).val('0' + jQuery(that).val());
  }
}

/**
 * Check field validation.
 *
 * @param that
 * @param ids
 * @returns {boolean}
 */
function wd_validate(that, ids) {
  if ( !jQuery(that).is("input") ) {
    return true;
  }

  var error_message;
  var isValid;
  var reg_exp;


  var id = jQuery(that).attr("id");
  var type = jQuery(that).data("valid-type");

  var form_id = jQuery(that).data("form-id");
  var wdid = jQuery(that).data("wdid");
  var value = jQuery(that).val();
  var hidden_input = jQuery(that).parent().find(".hidden_date");
  var date_format = "MM/DD/YY";
  if (hidden_input.length != 0) {
    date_format = hidden_input.data("format").toUpperCase();
    /* In moment js format YY equal to calendar format Y ... */
    var Ycount = (date_format.match(/Y/g) || []).length;
    if (Ycount === 2) {
      date_format = date_format.replace("YY", "YYYY");
    }
    else if (Ycount === 1) {
      date_format = date_format.replace("Y", "YY");
    }
    var momentjs_value = value;
    if (typeof moment === "function") {
      momentjs_value = moment(value, date_format)._i;
    }
    var date_min = hidden_input.data("min"); /* Min date */
    var date_max = hidden_input.data("max"); /* Max date */
    /* Case when min/max value mantioned in format +1d, -5w, +3m ....*/
    date_min = min_max_date_with_operator(date_min, date_format);
    date_max = min_max_date_with_operator(date_max, date_format);
    var check_min_max = false;
    if (date_format === 'OO') {
      if (typeof moment !== "function") {
        check_min_max = true;
      }
      else if (date_min <= momentjs_value && date_max >= momentjs_value) {
        check_min_max = true;
      }
    }
    else {
      if (typeof moment !== "function") {
        check_min_max = true;
      }
      else if ((moment(date_min, date_format).isSameOrBefore(moment(value, date_format)) || date_min == '') && (moment(value, date_format).isSameOrBefore(moment(date_max, date_format)) || date_max == '')) {
        check_min_max = true;
      }
    }
    var start_year = parseInt(jQuery(that).attr("from"));
    var end_year = parseInt(jQuery(that).attr("to"));
  }

  if ( typeof ids == "undefined" ) {
    var ids = jQuery(that).data("addiotional-fields");
  }

  var cont_id = "#form" + form_id + " div[wdid='" + wdid + "']";
  var label_cont = jQuery(cont_id + " .wdform-label-section:first .wdform-label");
  var section_cont = jQuery(cont_id + " .wdform-element-section");

  switch (type) {
    case "hour24": {  /* time field */
      error_message = fm_objectL10n.time_validation;
      reg_exp = /^(0?[0-1]?[0-9]|2[0-3])?$/;
      break;
    }
    case "hour12": { /* time field */
      error_message = fm_objectL10n.time_validation;
      reg_exp = /^(0?[0-9]|1[0-2])?$/;
      break;
    }
    case "minute": /* time field */
    case "second": {
      error_message = fm_objectL10n.time_validation;
      reg_exp = /^([0-5]?[0-9])?$/;
      break;
    }
    case "number": {
      error_message = fm_objectL10n.number_validation;
      reg_exp = /^\-{0,1}\d+(.\d+){0,1}$/;
      break;
    }
    case "quantity": {
      error_message = fm_objectL10n.number_validation;
      reg_exp = /^[+]?\d+([.]\d+)?$/;
      break;
    }
    case "day": { /* Date of Birth field */
      error_message = fm_objectL10n.date_validation;
      reg_exp = /^(0?[0-2]?[0-9]|3[0-1])?$/;
      break;
    }
    case "month": { /* Date of Birth field */
      error_message = fm_objectL10n.date_validation;
      reg_exp = /^(0?[0-9]|1[0-2])?$/;
      break;
    }
    case "year": { /* Date of Birth field */
      error_message = fm_objectL10n.date_validation;
      reg_exp = /^([1-2]?[0-9]?[0-9]?[0-9])?$/;
      break;
    }
    case "date": { /* Date field */
      if ( date_format == "mm/dd/yy" ) {
        error_message = fm_objectL10n.date_validation;
        reg_exp = /^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[01])\/\d{4}$/;
      }  else {
        error_message = "";
        reg_exp = /^$/;
      }
      break;
    }
  }
  isValid = reg_exp.test(value);
  if ( value == '' || date_format != "MM/DD/YY") {
    isValid = true;
  }
  if ( isValid ) {
    for ( var i in ids ) {
      isValid = wd_validate("#" + ids[i], '');
      if ( !isValid ) {
        break;
      }
    }
  }

  if ( type == "year" ) {  /* Date of Birth field */
    if ( ( parseInt(value) < start_year ) || ( parseInt(value) > end_year ) ) {
      isValid = false;
      error_message = fm_objectL10n.year_validation.replace('%%start%%', start_year).replace('%%end%%', end_year);
    }
  }
  if ( type == "date" ) {
    if ( !check_min_max ) {
      isValid = false;
      error_message = fm_objectL10n.date_validation;
    }
  }

  jQuery("#check_email_" + wdid + "_" + form_id).remove();
  if ( !isValid ) {
    // Add error message.
    section_cont.parent().parent().append("<div id='check_email_" + wdid + "_" + form_id + "' class='fm-not-filled'>" + error_message + "</div>");
    // Add error class to label.
    label_cont.addClass("wd-error-label");
    window["check_before_submit" + form_id][wdid + "_" + form_id] = false;
  }
  else {
    // Remove error class from label.
    label_cont.removeClass("wd-error-label");
    delete window["check_before_submit" + form_id][wdid + "_" + form_id];
  }

  return isValid;
}

/*
* Function count date according format when min/max is +d, -m, +w ...
*
* @param date_min_max is date format like +7d
* @param date_format is date format
*
* @return date
*
* */
function min_max_date_with_operator( date_min_max, date_format ) {
  var count; /* used for cases min/max date for format +1d, -3m .... get value 1, 3 ....*/
  var dateOper; /* used for cases min/max date for format +1d, -3m .... get value d, m ....*/

  if ( typeof moment !== "function") {
    return;
  }

  var chars = '';
  /* Collect array like [0 => +1y, 1 => -3m, 2 => +5w] */
  var myArray = [];

  /* Case when there are + or - in min/max condition */
  if ( date_min_max.indexOf('+') >= 0 || date_min_max.indexOf('-') >= 0 ) {
      /* Checking every char and split to parts for y/m/w/d and add to array */
      for (var i = 0; i < date_min_max.length; i++) {

        if ( (date_min_max[i] === '+' || date_min_max[i] === '-') && i !== 0 ) {
          myArray.push(chars);
          chars = '';
        }
        chars += date_min_max[i];
      }
      myArray.push(chars);

      /* Collect object with moment format ex. {days:7,months:1} */
      var obj = {};
      for (var i = 0; i < myArray.length; i++) {
        count = parseInt(myArray[i].replace(/[^\d]/g, ''));
        dateOper = myArray[i].replace('+'+count, '').replace('-'+count, '').toLowerCase();

        switch( dateOper ) {
          case 'y':
            if( myArray[i].indexOf('+') === 0 ) {
                obj['years'] = count;
            } else if( myArray[i].indexOf('-') === 0) {
                obj['years'] = -count;
            }
            break;
          case 'm':
            if( myArray[i].indexOf('+') === 0 ) {
                obj['months'] = count;
            } else if( myArray[i].indexOf('-') === 0) {
                obj['months'] = -count;
            }
            break;
          case 'w':
            if( myArray[i].indexOf('+') === 0 ) {
                obj['weeks'] = count;
            } else if( myArray[i].indexOf('-') === 0) {
                obj['weeks'] = -count;
            }
            break;
          default:
            if( myArray[i].indexOf('+') === 0 ) {
                obj['days'] = count;
            } else if( myArray[i].indexOf('-') === 0) {
                obj['days'] = -count;
            }
        }
      }
      date_min_max = moment().add(obj).format(date_format);
  } else if ( date_min_max === 'today' ) {
      date_min_max = moment().format(date_format);
  } else {
      date_min_max = moment(date_min_max, date_format)._i;
  }

  return date_min_max;
}

function check_isnum_interval(e, x, from, to) {
  var chCode1 = e.which || e.keyCode;
  if (jQuery.inArray(chCode1,[46,8,9,27,13,190]) != -1 || e.ctrlKey === true || (chCode1 >= 35 && chCode1 < 39)) {
    return true;
  }
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {
    return false;
  }
  val1=""+jQuery(x).val()+String.fromCharCode(chCode1);
  if (val1.length>2) {
    return false;
  }
  if (val1=='00') {
    return false;
  }
  if ((val1<from) || (val1>to)) {
    return false;
  }
  return true;
}


function destroyChildren(node) {
  while (node.firstChild) {
    node.removeChild(node.firstChild);
  }
}

function generate_page_nav(id, form_id, form_view_count) {
  form_view = id;
  page_nav = document.getElementById(form_id + 'page_nav' + id);
  destroyChildren(page_nav);
  form_view_elemet = document.getElementById(form_id + 'form_view' + id);
  remove_whitespace(form_view_elemet.parentNode.parentNode);
  display_none_form_views_all(form_id);
  form_view_elemet.parentNode.style.display = "";
  var td = document.createElement("div");
  td.setAttribute("valign", "middle");
  td.setAttribute("align", "left");
  td.style.display = "table-cell";
  td.style.width = "40%";
  page_nav.appendChild(td);
  if (form_view_elemet.parentNode.previousSibling && form_view_elemet.parentNode.previousSibling.className.indexOf('wdform-page-and-images') != -1 && form_view_elemet.parentNode.previousSibling.previousSibling) {
    if (form_view_elemet.parentNode.previousSibling.tagName == "DIV") {
      table = form_view_elemet.parentNode.previousSibling;
    }
    else {
      if (form_view_elemet.parentNode.previousSibling.previousSibling.tagName == "DIV") {
        table = form_view_elemet.parentNode.previousSibling.previousSibling;
      }
      else {
        table = "none";
      }
    }
    if (table != "none") {
      if (!table.firstChild.tagName) {
        table.removeChild(table.firstChild);
      }
      previous_title = form_view_elemet.getAttribute('previous_title');
      previous_type = form_view_elemet.getAttribute('previous_type');
      if (previous_type == "text") {
        td.setAttribute("class", "previous-page");
      }
      previous_class = form_view_elemet.getAttribute('previous_class');
      previous_checkable = form_view_elemet.getAttribute('previous_checkable');
      next_or_previous = "previous";
      previous = make_pagebreak_button(next_or_previous, previous_title, previous_type, previous_class, previous_checkable, id, form_id, form_view_count);
      td.appendChild(previous);
    }
  }
  var td = document.createElement("div");
  td.setAttribute("id", form_id + "page_numbers" + form_view);
  td.setAttribute("valign", "middle");
  td.setAttribute("class", "page-numbers");
  td.setAttribute("align", "center");
  td.style.display = "table-cell";
  if (document.getElementById('fm-pages' + form_id).getAttribute('show_numbers') == "true") {
    var cur = document.createElement('span');
    cur.setAttribute("class", "page_numbers");
    td.appendChild(cur);
  }
  page_nav.appendChild(td);
  var td = document.createElement("div");
  td.setAttribute("valign", "middle");
  td.setAttribute("align", "right");
  td.style.cssText = "display:table-cell; width:40%; text-align:right;";
  page_nav.appendChild(td);
  not_next = false;
  if (form_view_elemet.parentNode.nextSibling) {
    if (form_view_elemet.parentNode.nextSibling.tagName == "DIV" && form_view_elemet.parentNode.nextSibling.className.indexOf('wdform-page-and-images') != -1) {
      table = form_view_elemet.parentNode.nextSibling;
    }
    else {
      if (form_view_elemet.parentNode.nextSibling.nextSibling) {
        if (form_view_elemet.parentNode.nextSibling.nextSibling.tagName == "DIV") {
          table = form_view_elemet.parentNode.nextSibling.nextSibling;
        }
        else {
          table = "none";
        }
      }
      else {
        table = "none";
      }
    }
    if (table != "none") {
      next_title = form_view_elemet.getAttribute('next_title');
      next_type = form_view_elemet.getAttribute('next_type');
      if (next_type == "text") {
        td.setAttribute("class", "next-page");
      }
      next_class = form_view_elemet.getAttribute('next_class');
      next_checkable = form_view_elemet.getAttribute('next_checkable');
      next_or_previous = "next";
      next = make_pagebreak_button(next_or_previous, next_title, next_type, next_class, next_checkable, id, form_id, form_view_count);
      td.appendChild(next);
    }
    else {
      not_next = true;
    }
  }
  else {
    not_next = true;
  }
  generate_page_bar(id, form_id, form_view_count);
  fm_initilize_form(form_id);
}

function fm_initilize_form(form_id) {
  jQuery("#form" + form_id + " div[type='type_map']").each(function()	{
    id=jQuery(this).parent().attr('wdid');
    if_gmap_init('wdform_'+id, form_id);
    for(q=0; q<20; q++) {
      if(jQuery("#wdform_"+id+"_element"+form_id)[0].getAttribute("long"+q)) {
        w_long=parseFloat(document.getElementById('wdform_'+id+"_element"+form_id).getAttribute("long"+q));
        w_lat=parseFloat(document.getElementById('wdform_'+id+"_element"+form_id).getAttribute("lat"+q));
        w_info=parseFloat(document.getElementById('wdform_'+id+"_element"+form_id).getAttribute("info"+q));
        add_marker_on_map('wdform_'+id, q, w_long, w_lat, w_info, form_id,false);
      }
    }
  });
  jQuery("#form" + form_id + " div[type='type_mark_map']").each(function() {
    id=jQuery(this).parent().attr('wdid');
    if_gmap_init('wdform_'+id, form_id);
    q=0;
    if(jQuery("#wdform_"+id+"_element"+form_id)[0].getAttribute("long"+q)) {
      w_long=parseFloat(document.getElementById('wdform_'+id+"_element"+form_id).getAttribute("long"+q));
      w_lat=parseFloat(document.getElementById('wdform_'+id+"_element"+form_id).getAttribute("lat"+q));
      w_info=parseFloat(document.getElementById('wdform_'+id+"_element"+form_id).getAttribute("info"+q));
      add_marker_on_map('wdform_'+id, q, w_long, w_lat, w_info, form_id,true);
    }
  });

  jQuery('.wdform-element-section').each( function() {
    if (jQuery(this).parent().attr('type') == "type_stripe") {
      var getStripeFields = jQuery(this).parent().parent().find(".stripe_more_info .wdform-label-section").removeClass("wd-hidden");
      
      return true;
    }

    if ( !jQuery(this).parent()[0].style.width
      && parseInt(jQuery(this).width()) != 0
      && jQuery(this).parent().find(jQuery(".wdform-label-section")).length != 0 ) {
      if (jQuery(this).css('display') == "table-cell") {
        if (jQuery(this).parent().attr('type') != "type_captcha") {
          jQuery(this).parent().css('width', parseInt(jQuery(this).width()) + parseInt(jQuery(this).parent().find(jQuery(".wdform-label-section"))[0].style.width) + 15);
        }
        else {
          jQuery(this).parent().css('width', (parseInt(jQuery(this).parent().find(jQuery(".captcha_input"))[0].style.width) * 2 + 50) + parseInt(jQuery(this).parent().find(jQuery(".wdform-label-section"))[0].style.width) + 15);
        }
      }
    }
  });
  if ( jQuery("#form" + form_id + " div[type='type_signature']").length && typeof fm_signature_init != 'undefined' ) {
    fm_signature_init();
  }
}

function display_none_form_views_all(form_id) {
  jQuery("#form"+form_id+" .wdform-page-and-images").css('display','none');
}

function generate_page_bar(form_view, form_id, form_view_count) {
  if (document.getElementById('fm-pages' + form_id).getAttribute('type') == 'steps') {
    make_page_steps_front(form_view, form_id, form_view_count);
  }
  else {
    if (document.getElementById('fm-pages' + form_id).getAttribute('type') == 'percentage') {
      make_page_percentage_front(form_view, form_id, form_view_count);
    }
    else {
      make_page_none_front(form_id);
    }
  }
  if (document.getElementById('fm-pages' + form_id).getAttribute('show_numbers') == 'true') {
    td = document.getElementById(form_id + 'page_numbers' + form_view);
    if (td) {
      destroyChildren(td);
      var k = 0;
      var cur_page_number = 0;
      jQuery('#form' + form_id + ' .wdform-page-and-images').each(function () {
        var index = jQuery(this).find('.wdform_page').attr('id');
        j = index.split("form_view")[1];
        if (document.getElementById(form_id + 'form_view' + j)) {
          k++;
          if (j == form_view) {
            cur_page_number = k;
          }
        }
      });
      var cur = document.createElement('span');
      cur.setAttribute("class", "page_numbers");
      cur.innerHTML = cur_page_number + '/' + k;
      td.appendChild(cur);
    }
  }
  else {
    td = document.getElementById(form_id + 'page_numbers' + form_view);
    if (td) {
      destroyChildren(document.getElementById(form_id + 'page_numbers' + form_view));
    }
  }
}

function make_page_steps_front(form_view, form_id, form_view_count) {
  destroyChildren(document.getElementById('fm-pages' + form_id));
  show_title = (document.getElementById('fm-pages' + form_id).getAttribute('show_title') == 'true');
  next_checkable = (document.getElementById(form_id + 'form_view' + form_view).getAttribute('next_checkable') == 'true');
  previous_checkable = (document.getElementById(form_id + 'form_view' + form_view).getAttribute('previous_checkable') == 'true');
  k = 0;
  jQuery('#form'+form_id+' .wdform-page-and-images').each(function () {
    var index = jQuery(this).find('.wdform_page').attr('id');
    j = index.split("form_view")[1];
    if (document.getElementById(form_id + 'form_view' + j)) {
      if (document.getElementById(form_id + 'form_view' + j).getAttribute('page_title')) {
        w_pages = document.getElementById(form_id + 'form_view' + j).getAttribute('page_title');
      }
      else {
        w_pages = "";
      }
      k++;
      page_number = document.createElement('span');
      page_number.setAttribute('id', 'page_' + j);
      if (j < form_view) {
        if (previous_checkable) {
          page_number.setAttribute('onClick', 'if(fm_check(' + form_view + ', ' + form_id + ', false)) generate_page_nav("' + j + '", "' + form_id + '", "' + form_view_count + '")');
        }
        else {
          page_number.setAttribute('onClick', 'generate_page_nav("' + j + '", "' + form_id + '", "' + form_view_count + '")');
        }
      }
      if (j > form_view) {
        if (next_checkable) {
          page_number.setAttribute('onClick', 'if(fm_check(' + form_view + ', ' + form_id + ', false)) generate_page_nav("' + j + '", "' + form_id + '", "' + form_view_count + '")');
        }
        else {
          page_number.setAttribute('onClick', 'generate_page_nav("' + j + '", "' + form_id + '", "' + form_view_count + '")');
        }
      }
      if (j == form_view) {
        page_number.setAttribute('class', "page_active");
      }
      else {
        page_number.setAttribute('class', "page_deactive");
      }
      if (show_title) {
        page_number.innerHTML = w_pages;
      }
      else {
        page_number.innerHTML = k;
      }
      document.getElementById('fm-pages' + form_id).appendChild(page_number);
    }
  });
}

function make_page_percentage_front(form_view, form_id, form_view_count) {
  destroyChildren(document.getElementById('fm-pages' + form_id));
  show_title = (document.getElementById('fm-pages' + form_id).getAttribute('show_title') == 'true');
  var div_parent = document.createElement('div');
  div_parent.setAttribute("class", "page_percentage_deactive");
  var div = document.createElement('div');
  div.setAttribute("id", "div_percentage");
  div.setAttribute("class", "page_percentage_active");
  div.setAttribute("align", "right");
  var div_arrow = document.createElement('div');
  div_arrow.setAttribute("class", "wdform_percentage_arrow");
  var b = document.createElement('b');
  b.setAttribute("class", "wdform_percentage_text");
  div.appendChild(b);
  k = 0;
  cur_page_title = '';
  jQuery('.form'+form_id+' .wdform-page-and-images').each(function () {
    var index = jQuery(this).find('.wdform_page').attr('id');
    j = index.split("form_view")[1];
    if (document.getElementById(form_id + 'form_view' + j)) {
      if (document.getElementById(form_id + 'form_view' + j).getAttribute('page_title')) {
        w_pages = document.getElementById(form_id + 'form_view' + j).getAttribute('page_title');
      }
      else {
        w_pages = "";
      }
      k++;
      if (j == form_view) {
        if (show_title) {
          cur_page_title = document.createElement('div');
          cur_page_title.innerHTML = w_pages;
          cur_page_title.innerHTML = w_pages;
          cur_page_title.setAttribute("class", "wdform_percentage_title");
        }
        page_number = k;
      }
    }
  });
  b.innerHTML = Math.round(((page_number - 1) / (k-1)) * 100) + '%';
  div.style.width = ((page_number - 1) / (k-1)) * 100 + '%';
  if (page_number == 1) {
    div_arrow.style.display = 'none';
  }
  div_parent.appendChild(div);
  div_parent.appendChild(div_arrow);
  if (cur_page_title) {
    div_parent.appendChild(cur_page_title);
  }
  document.getElementById('fm-pages' + form_id).appendChild(div_parent);
}

function make_page_none_front(form_id) {
  destroyChildren(document.getElementById('fm-pages' + form_id));
}

function make_pagebreak_button(next_or_previous,title,type, class_, checkable, id, form_id, form_view_count) {
  switch(type) {
    case 'text': {
      var element = document.createElement('div');
      element.setAttribute('id', "page_"+next_or_previous+"_"+id+"_"+form_id);
      element.setAttribute('class', class_);
      if(checkable=="true") {
        element.setAttribute('onClick', "if(fm_check("+id+", "+form_id+", false)) page_"+next_or_previous+"("+id+","+form_id+","+form_view_count+")");
      }
      else {
        element.setAttribute('onClick', "page_"+next_or_previous+"("+id+","+form_id+","+form_view_count+")");
      }
      element.innerHTML=title;
      return element;
    }
    case 'img':{
      var element = document.createElement('img');
      element.setAttribute('id', "page_"+next_or_previous+"_"+id);
      element.setAttribute('class', class_);
      if(checkable=="true") {
        element.setAttribute('onClick', "if(fm_check("+id+", "+form_id+", false)) page_"+next_or_previous+"("+id+","+form_id+","+form_view_count+")");
      }
      else {
        element.setAttribute('onClick', "page_"+next_or_previous+"("+id+","+form_id+","+form_view_count+")");
      }
      if(title.indexOf("http")==0) {
        element.src=title;
      }
      else {
        element.src=fm_objectL10n.plugin_url+'/'+title;
      }
      return element;
    }
  }
}

function form_maker_findPos(obj) {
  var curtop = 0;
  if (obj.offsetParent) {
    do {
      curtop += obj.offsetTop;
    } while (obj = obj.offsetParent);
    return [curtop];
  }
}

function page_previous(id, form_id, form_view_count) {
  form_view_elemet = document.getElementById(form_id + 'form_view' + id);
  if (form_view_elemet.parentNode.previousSibling && form_view_elemet.parentNode.previousSibling.previousSibling) {
    if (form_view_elemet.parentNode.previousSibling.tagName == "DIV") {
      table = form_view_elemet.parentNode.previousSibling;
    }
    else {
      table = form_view_elemet.parentNode.previousSibling.previousSibling;
    }
  }
  if (!table.firstChild.tagName) {
    table.removeChild(table.firstChild);
  }
  generate_page_nav(table.firstChild.id.replace(form_id + 'form_view', ""), form_id, form_view_count);
  form = jQuery("#form" + form_id);
  if (!form.parent().hasClass('fm-scrollbox-form')) {
    jQuery('html').animate({
      scrollTop: form.offset().top - 150
    }, 500);
    document.scrollingElement.scrollTop = form.offset().top - 150; /* For Safari.*/
  }
}

function page_next(id, form_id, form_view_count) {
  form_view_elemet = document.getElementById(form_id + 'form_view' + id);
  if (form_view_elemet.parentNode.nextSibling) {
    if (form_view_elemet.parentNode.nextSibling.tagName == "DIV") {
      table = form_view_elemet.parentNode.nextSibling;
    }
    else {
      table = form_view_elemet.parentNode.nextSibling.nextSibling;
    }
  }

  if (!table.firstChild.tagName) {
    table.removeChild(table.firstChild);
  }
  generate_page_nav(table.firstChild.id.replace(form_id + 'form_view', ""), form_id, form_view_count);
  form = jQuery("#form" + form_id);
  if (!form.parent().hasClass('fm-scrollbox-form')) {
    jQuery('html').animate({
      scrollTop: form.offset().top - 150
    }, 500);
    document.scrollingElement.scrollTop = form.offset().top - 150; /* For Safari.*/
  }
}

function fm_go_to_page(id, form_id, form_view_count) {
  form_view_elemet = document.getElementById(form_id + 'form_view' + id);
  table = form_view_elemet.parentNode;

  if (!table.firstChild.tagName) {
    table.removeChild(table.firstChild);
  }
  generate_page_nav(table.firstChild.id.replace(form_id + 'form_view', ""), form_id, form_view_count);
  form = jQuery("#form" + form_id);
  if (!form.parent().hasClass('fm-scrollbox-form')) {
    jQuery('html').animate({
      scrollTop: form.offset().top - 150
    }, 500);
    document.scrollingElement.scrollTop = form.offset().top - 150; /* For Safari.*/
  }
}

function getfileextension(filename, exten) {
  if( typeof filename == 'undefined' || filename.length == 0) {
    return true;
  }
  var dot = filename.lastIndexOf(".");
  var extension = filename.substr(dot+1,filename.length);
  exten=exten.split(',');
  for(var j=0 ; j<exten.length; j++) {
    exten[j]=exten[j].replace(/\./g,'');
    exten[j]=exten[j].replace(/ /g,'');
    if(extension.toLowerCase()==exten[j].toLowerCase())
      return true;
  }
  return false;
}

function reselect(select, addclass) {
  addclass = typeof(addclass) != 'undefined' ? addclass : '';
  jQuery(select).wrap('<div class="sel-wrap ' + addclass + '"/>');
  var sel_options = '';
  var selected_option = false;
  jQuery(select).children('option').each(function() {
    if(jQuery(this).is(':selected')){
      selected_option = jQuery(this).index();
    }
    sel_options = sel_options + '<div class="sel-option" value="' + jQuery(this).val() + '">' + jQuery(this).html() + '</div>';
  });
  w=jQuery(select)[0].style.width;
  if(w=='100%') {
    w='100%';
  }
  else {
    w=(jQuery(select).width()+32)+'px';
  }
  var sel_imul = '<div class="sel-imul" style="width:'+w+'">\
                <div class="sel-selected">\
                    <div class="selected-text">' + jQuery(select).children('option').eq(selected_option).html() + '</div>\
                    <div class="sel-arraw"></div>\
                </div>\
                <div class="sel-options">' + sel_options + '</div>\
            </div>';
  jQuery(select).addClass('no-width');
  jQuery(select).before(sel_imul);
}

jQuery(document).on('change','.wdform-element-section select', function() {
  var tektext = jQuery(this).children("option:selected").text();
  jQuery(this).parent('.sel-wrap ').children('.sel-imul').children('.sel-selected').children('.selected-text').html(tektext);
  jQuery(this).parent('.sel-wrap ').children('.sel-imul').children('.sel-options').children('.sel-option').removeClass('sel-ed');
  jQuery(this).addClass('sel-ed');
  jQuery(this).parent('.sel-wrap ').children('.sel-imul').children('.sel-options').each(function() {
    if (jQuery(this).html() == tektext) {
      jQuery(this).addClass('sel-ed');
    }
  });
});

jQuery(document).on('change','div[type="type_date_fields"]', function() {
  if ( jQuery(this).find("select.sel-ed").length > 2 ) {
    jQuery(this).next(".fm-not-valid-date, .fm-not-filled").remove();
    jQuery(this).find(".wdform-label").removeClass("error_label");
  }
});

jQuery(document).on('click','.sel-imul', function() {
  jQuery('.sel-imul').removeClass('act');
  jQuery(this).addClass('act');
  if (jQuery(this).children('.sel-options').is(':visible')) {
    jQuery('.sel-options').hide();
  }
  else {
    jQuery('.sel-options').hide();
    jQuery(this).children('.sel-options').show();
    jQuery(this).children('.sel-options').css('width',jQuery(this).width());
  }
});

jQuery(document).on('click','.sel-option', function() {
  var tektext = jQuery(this).html();
  jQuery(this).parent('.sel-options').parent('.sel-imul').children('.sel-selected').children('.selected-text').html(tektext);
  jQuery(this).parent('.sel-options').children('.sel-option').removeClass('sel-ed');
  jQuery(this).addClass('sel-ed');
  var tekval = jQuery(this).attr('value');
  tekval = typeof(tekval) != 'undefined' ? tekval : tektext;
  jQuery(this).parent('.sel-options').parent('.sel-imul').parent('.sel-wrap').children('select').children('option').prop('selected', false).each(function() {
    if (jQuery(this).html() == tektext) {
      jQuery(this).attr('selected', 'select');
    }
  });
  jQuery(this).parent('.sel-options').parent('.sel-imul').parent('.sel-wrap').children('select').change();
});

var selenter = false;
jQuery(document).on('mouseenter','.sel-imul', function() {
  selenter = true;
});

jQuery(document).on('mouseleave','.sel-imul', function() {
  selenter = false;
});

jQuery(document).click(function() {
  if (!selenter) {
    jQuery('.sel-options').hide();
    jQuery('.sel-imul').removeClass('act');
  }
});

function remove_whitespace(node) {
  var ttt;
  for (ttt=0; ttt < node.childNodes.length; ttt++) {
    if( node.childNodes[ttt] && node.childNodes[ttt].nodeType == '3' && !/\S/.test(  node.childNodes[ttt].nodeValue )) {
      node.removeChild(node.childNodes[ttt]);
      ttt--;
    }
    else {
      if(node.childNodes[ttt].childNodes.length) {
        remove_whitespace(node.childNodes[ttt]);
      }
    }
  }
  return;
}

function change_value_range(id, min_max, element_value, default_min_max, format, that) {
  format = format.toUpperCase();
  /* in moment js format YY equal to calendar format Y ... */
  var count = (format.match(/Y/g) || []).length;
  if( count === 2 ) {
      format = format.replace("YY", "YYYY");
  } else if( count === 1 ) {
      format = format.replace("Y", "YY");
  }

  default_min_max = min_max_date_with_operator( default_min_max, format );

  check_min_max = false;
  if ( format === 'OO' ) {
      if(min_max === 'minDate') {
        check_min = (default_min_max <= element_value) ? true : false;
      } else {
        check_max = (default_min_max < element_value) ? false : true;
      }
  } else {
      if( typeof moment !== "function") {
          check_min_max = true;
      } else {
          if ( default_min_max === "" || (moment(default_min_max, format).isSameOrBefore(moment(element_value, format)) && min_max === 'minDate') ) {
            check_min_max = true;
          }
          if ( default_min_max === "" || (moment(element_value, format).isSameOrBefore(moment(default_min_max, format)) && min_max === 'maxDate') ) {
            check_min_max = true;
          }
      }
  }

  var form_id = jQuery(that).data("form-id");
  var wdid = jQuery(that).data("wdid");
  var cont_id = "#form" + form_id + " div[wdid='" + wdid + "']";
  var label_cont = jQuery(cont_id + " .wdform-label-section:first .wdform-label");
  if ( !check_min_max ) {
    error_message = fm_objectL10n.date_validation;
    var section_cont = jQuery(cont_id + " .wdform-element-section");
    // Add error message.
    jQuery("#check_email_" + wdid + "_" + form_id).remove();
    section_cont.parent().parent().append("<div id='check_email_" + wdid + "_" + form_id + "' class='fm-not-filled'>" + error_message + "</div>");
    // Add error class to label.
    label_cont.addClass("wd-error-label");
  }
  else {
    // Remove error class from label.
    jQuery("#check_email_" + wdid + "_" + form_id).remove();
    label_cont.removeClass("wd-error-label");
  }

  if ( element_value ) {
    jQuery("#" + id).datepicker('option', min_max, element_value);
  }
  else {
    if( default_min_max == "today" ) {
      jQuery("#" + id).datepicker('option', min_max, new Date());
    }
    else {
      if ( default_min_max.indexOf("d") == -1 && default_min_max.indexOf("m") == -1 && default_min_max.indexOf("y") == -1 && default_min_max.indexOf("w") == -1 && default_min_max != "" ) {
        default_min_max = jQuery.datepicker.formatDate(format, new Date(default_min_max));
      }
      jQuery("#" + id).datepicker('option', min_max, default_min_max);
    }
  }
}

function wd_check_confirmation_email(wdid, form_id, message, type) {
  var element = jQuery("#wdform_" + wdid +"_element" + form_id);
  var element_confirm = jQuery("#wdform_" + wdid +"_1_element" + form_id);
  var condition_confirm_email = false;
  if ( false == window["check_before_submit" + form_id][wdid + "_" + form_id] ) {
    condition_confirm_email = true;
  }
  else {
    condition_confirm_email = jQuery(element).val() != jQuery(element_confirm).val() ? true : false;
  }
  if(condition_confirm_email) {
    jQuery("#confirm_" + wdid + "_" + form_id).remove();
    jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:eq( 1 ) .error_label_confirm").removeClass("error_label_confirm");
    if(jQuery(element).val() != jQuery(element_confirm).val()) {
      jQuery(element_confirm).parent().parent().parent().append("<div id='confirm_" + wdid + "_" + form_id + "' class='fm-not-filled'>" + message + "</div>");
      jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:eq( 1 ) .wdform-label").addClass("error_label_confirm");
    }
    window["check_before_submit" + form_id][wdid + "_" + form_id] = (wdid + "_" + form_id in window["check_before_submit" + form_id]) ? window["check_before_submit" + form_id][wdid + "_" + form_id] : true;
  }
  else {
    jQuery("#confirm_" + wdid + "_" + form_id).remove();
    jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:eq( 1 ) .error_label_confirm").removeClass("error_label_confirm");
    if(window["check_before_submit" + form_id][wdid + "_" + form_id] == true) {
      delete window["check_before_submit" + form_id][wdid + "_" + form_id];
    }
  }
}

function wd_check_email(wdid, form_id, message_check) {
  var element = jQuery("#wdform_" + wdid +"_element" + form_id);
  var element_confirm = jQuery("#wdform_" + wdid +"_1_element" + form_id);
  /* Regexp is also for Cyrillic alphabet */
  var re = /^[\u0400-\u04FFa-zA-Z0-9'.+_-]+@[\u0400-\u04FFa-zA-Z0-9.-]+\.[\u0400-\u04FFa-zA-Z]{2,61}$/;
  if ( jQuery(element).val()!="" && !re.test(jQuery.trim(jQuery(element).val())) && jQuery(element).attr("title") != jQuery(element).val() ) {
    jQuery("#check_email_" + wdid + "_" + form_id).remove();
    var label_content = jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:first");
    var label_width = 0;
    if ( label_content.hasClass('wd-width-30') && !label_content.hasClass('wd-hidden') ) {
      label_width = label_content.width();
    }
    jQuery(element).parent().parent().parent().append("<div id='check_email_" + wdid + "_" + form_id + "'  class='fm-not-filled' style='margin-left: " + label_width + "px'>" + message_check + "</div>");
    jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:first .wdform-label").addClass("error_label_check_mail");
    if ( element.val() == element_confirm.val() ) {
      jQuery("#confirm_" + wdid + "_" + form_id).remove();
      jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:eq( 1 ) .error_label_confirm").removeClass("error_label_confirm");
    }
    delete window["check_before_submit" + form_id][wdid + "_" + form_id];
    window["check_before_submit" + form_id][wdid + "_" + form_id] = false;
  }
  else {
    jQuery("#check_email_" + wdid + "_" + form_id).remove();
    jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label_check_mail").removeClass("error_label_check_mail");
    window["check_before_submit" + form_id][wdid + "_" + form_id] = true;
    if ( typeof element_confirm.val() ==="undefined" ) {
      delete window["check_before_submit" + form_id][wdid + "_" + form_id];
    }
  }
}

function wd_check_confirmation_pass(wdid, form_id, message) {
  var element = jQuery("#wdform_" + wdid +"_element" + form_id);
  var element_confirm = jQuery("#wdform_" + wdid +"_1_element" + form_id);
  var condition_confirm_pass = false;
  condition_confirm_pass = element.val() !== element_confirm.val();
  var label_content = jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first");
  var label_width = 0;
  if ( label_content.hasClass('wd-width-30') && !label_content.hasClass('wd-hidden') ) {
    label_width = label_content.width();
  }
  if(condition_confirm_pass) {
    jQuery("#confirm_" + wdid + "_" + form_id).remove();
    jQuery(element_confirm).parent().parent().parent().append("<div id='confirm_" + wdid + "_" + form_id + "'   class='fm-not-filled' style='margin-left: " + label_width + "px'>" + message + "</div>");
    jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:eq( 1 ) .wdform-label").addClass("error_label_check_pass");
    window["check_before_submit" + form_id][wdid + "_" + form_id] = false;
  }
  else {
    jQuery("#confirm_" + wdid + "_" + form_id).remove();
    jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:eq( 1 ) .error_label_check_pass").removeClass("error_label_check_pass");
    delete window["check_before_submit" + form_id][wdid + "_" + form_id];
  }
}

function minimize_form(form_id) {
  jQuery("#fm-scrollbox"+form_id).removeClass("fm-animated fadeInUp").addClass("fm-animated fadeOutDown fm-minimized");
  jQuery("#fm-minimize-text"+form_id).removeClass("fm-animated fadeOutDown").addClass("fm-show fm-animated fadeInUp");
}

function fm_show_scrollbox(form_id) {
  jQuery("#fm-minimize-text"+form_id).removeClass("fm-animated fadeInUp").addClass("fm-animated fadeOutDown");
  jQuery("#fm-scrollbox"+form_id).removeClass("fm-animated fadeOutDown fm-minimized").addClass("fm-show fm-animated fadeInUp");
}

function fm_hide_form(form_id, hide_interval, close_callback) {
  var hide_date = new Date();
  hide_date.setDate(hide_date.getDate() + hide_interval);
  if( hide_interval > 0 ) {
    localStorage.setItem('hide-'+form_id, hide_date.getTime());
  }
  if(typeof close_callback === 'function') {
    close_callback();
  }
}

function wd_check_regExp(form_id, regExpObj) {
  var x = jQuery("#form" + form_id);
  var find_wrong_exp = false;
  var check_regExp = regExpObj ? regExpObj : window['check_regExp_all'+form_id];
  jQuery.each( check_regExp, function( wdid, exp ) {
    var element = "#wdform_" + wdid + "_element" + form_id;
    var RegExpression = "";
    var rules = unescape(exp[0]);
    var wdform_row = x.find(jQuery("div[wdid='"+wdid+"']"));
    (exp[1].length <= 0) ?  RegExpression = new RegExp(rules) : RegExpression = new RegExp(rules, exp[1]);
    if (x.find(jQuery("div[wdid='"+wdid+"']")).length != 0 && x.find(jQuery("div[wdid='"+wdid+"']")).css("display") != "none") {
      jQuery("#form"+form_id+" #wd_exp_"+wdid).remove();
      if ( jQuery(element).val() != '' && jQuery(element).val() != jQuery(element).attr('title') ) {
        if ( RegExpression.test(jQuery(element).val()) != true ) {
          x.find(jQuery("div[wdid='"+wdid+"'] .wdform-element-section")).parent().parent().append("<div  id='wd_exp_"+wdid+"' class='fm-not-filled'>" + exp[2] + "</div>");
          jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").addClass("error_label_exp");
          find_wrong_exp = true;
          if(!regExpObj){
            scroll_on_element(form_id);
          }
        }
      }
    }
  });

  if(find_wrong_exp === false) {
    return true;
  }
  return false;
}

function scroll_on_element(form_id) {
  if ( jQuery("#form" + form_id + " .fm-not-filled").length == 0 ) {
    return true;
  }
  var shake_option = true;
  if( jQuery("#fm_shake"+form_id).val() == 0 ) {
    shake_option = false;
  }
  var parent_div = jQuery("#form" + form_id + " .fm-not-filled").closest(".wdform_row");
  var parent_div_page = parent_div.closest('.wdform_page');
  if ('none' == parent_div_page.parent().css('display')) {
    var pagebreak_count = jQuery("#form"+form_id+" .wdform-page-and-images").length;
    var maxid = jQuery("#form"+form_id+" .wdform_page").last().attr("id");
    maxid = maxid.split("form_view");

    var page_with_error = parent_div_page.attr('id');
    page_with_error = page_with_error.split('form_view')[1];
    fm_go_to_page(page_with_error, form_id, pagebreak_count, maxid[1]);
  }

  var scrollTop = jQuery(document).scrollTop();
  var body_hight = document.body.clientHeight;
  var element_height = jQuery("#form" + form_id + " .fm-not-filled").closest("div[wdid]").height();
  var element_offset = jQuery("#form" + form_id + " .fm-not-filled").offset().top;

  var scrollChecker = function() {
    if(document.body.clientHeight !== body_hight ) {
      body_hight = document.body.clientHeight;
      element_height = jQuery("#form" + form_id + " .fm-not-filled").closest("div[wdid]").height();
      element_offset = jQuery("#form" + form_id + " .fm-not-filled").offset().top;
      jQuery('html').stop();
	  animateBodyToError();
    }
  };

  jQuery(window).on("scroll",scrollChecker);

  function shakeError( shake ) {
    if( shake ) {
    	old_bg=jQuery(parent_div).css("background-color");
    	jQuery(parent_div).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });
    }
  }

  function animateBodyToError() {
    jQuery('html').animate({
      scrollTop: element_offset - (element_height + 40)
    }, 500, function() {
      document.scrollingElement.scrollTop = element_offset - (element_height + 40); /* For Safari.*/
      jQuery(window).off("scroll", scrollChecker);
      shakeError(shake_option);
    });
  }

  if ( scrollTop > element_offset ) {
	animateBodyToError();
  } else {
	shakeError(shake_option);
  }
}

function wd_file_upload_check(form_id, upload_check_field){
  var x = jQuery("#form" + form_id);
  var find_wrong_type_upload = false;
  var upload_check = upload_check_field ? upload_check_field : window['file_upload_check'+form_id];
  jQuery.each( upload_check, function( wdid, upload_types ) {
    var element = "#wdform_" + wdid + "_element" + form_id;
    if(x.find(jQuery("div[wdid='"+wdid+"']")).length != 0 && x.find(jQuery("div[wdid='"+wdid+"']")).css("display") != "none") {
      var ext_available = getfileextension(jQuery(element).val(), upload_types['extension']);
      // The size (KB) of the file.
      var max_size = parseFloat(upload_types['max_size']);
      var file_size = ( typeof jQuery(element)[0] != 'undefined' && typeof jQuery(element)[0].files[0] != 'undefined' ) ? jQuery(element)[0].files[0].size / 1024 : 'undefined';
      var is_max_size_error = ( typeof file_size != 'undefined' && file_size > max_size ) ? true : false;
      if ( !ext_available || is_max_size_error ) {
        var error_msg = (is_max_size_error) ? fm_objectL10n.fm_file_type_allowed_size_error.replace(/%s/g, max_size) : fm_objectL10n.fm_file_type_error;
        jQuery("#form"+form_id+" #wd_upload_type_"+wdid).remove();
        x.find(jQuery("div[wdid='"+wdid+"'] .wdform-element-section")).parent().parent().append("<div id='wd_upload_type_"+wdid+"' class='fm-not-filled'>" + error_msg + "</div>");
        jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").addClass("error_label_upload");
        find_wrong_type_upload = true;
      }
      else {
        jQuery("#form"+form_id+" #wd_upload_type_"+wdid).remove();
        jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label_upload").removeClass("error_label_upload")
      }
    }
  });
  if(!upload_check_field && find_wrong_type_upload === true){
    scroll_on_element(form_id);
  }

  if(find_wrong_type_upload === false) {
    return true;
  }
  return false;
}

function wd_is_filled(form_id, field_id, all_pages) {
  if (undefined == all_pages) {
    all_pages = true;
  }
  var x = jQuery("#form" + form_id);
  var req_fields = field_id ? field_id.split() : window['required_fields'+form_id];
  var not_filled = {};
  jQuery(req_fields).each(function(index, wdid) {
	if ( x.find(jQuery("div[wdid='"+wdid+"']")).css('display') === 'none' ) {
		x.find(jQuery("div[wdid='"+wdid+"']")).find('.fm-not-filled').remove();
		x.find(jQuery("div[wdid='"+wdid+"']")).css("background-color", "");
		x.find(jQuery("div[wdid='"+wdid+"'] label")).removeClass("error_label");
	}
  if ( x.find(jQuery("div[wdid='"+wdid+"']")).length != 0 && x.find(jQuery("div[wdid='"+wdid+"']")).css('display') !== 'none' ) {
      switch(window['labels_and_ids'+form_id][wdid]) {
        case 'type_text':
        case 'type_textarea':
        case 'type_paypal_price_new':
        case 'type_spinner':
        case 'type_number':
        case 'type_phone_new':
        case 'type_submitter_mail': {
          var element = "#wdform_" + wdid + "_element" + form_id;
          var element_value = jQuery(element).val();
            element_value = jQuery.trim(element_value);
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              if ( element_value == "" ) {
                not_filled[wdid] = element;
              }
            }
            if(!field_id && !window['check_submit'+form_id]) {
              jQuery(element).focus(function() {
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          break;
        }
        case 'type_own_select':
        case 'type_country':
        case 'type_paypal_select': {
          var element = "#wdform_" + wdid + "_element" + form_id;
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if (jQuery(element).val() == "" || (jQuery("#wdform_"+ wdid +"_element_quantity" + form_id).length > 0 && !parseInt(jQuery("#wdform_"+ wdid +"_element_quantity" + form_id).val()))) {
              not_filled[wdid] = element;
            }
          }
          if(!field_id && !window['check_submit'+form_id]) {
            jQuery(element).focus(function() {
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
            }).blur(function() {
              wd_is_filled(form_id, wdid);
            }).change(function() {
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              wd_is_filled(form_id, wdid);
            });
          }
          break;
        }
        case 'type_phone': {
          var element = ["#wdform_" + wdid + "_element_first" + form_id, "#wdform_" + wdid + "_element_last" + form_id];
          jQuery.each(element, function(i, elem){
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              if(jQuery(elem).val() == ""){
                not_filled[wdid] = elem;
              }
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery(elem).focus(function() {
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_name': {
          var element = ["#wdform_" + wdid + "_element_title" + form_id, "#wdform_" + wdid + "_element_first" + form_id, "#wdform_" + wdid + "_element_last" + form_id, "#wdform_" + wdid + "_element_middle" + form_id];
          jQuery.each(element, function(i, elem){
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              if ( jQuery(elem).length) {
                var element_value = jQuery(elem).val();
                  element_value = jQuery.trim(element_value);
                if( ( element_value == "" ) && typeof element_value != "undefined") {
                not_filled[wdid] = elem;
                }
              }
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery(elem).focus(function() {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_address': {
          var element = [
            '#wdform_' + wdid + '_street1' + form_id,
            '#wdform_' + wdid + '_city' + form_id,
            '#wdform_' + wdid + '_state' + form_id,
            '#wdform_' + wdid + '_postal' + form_id,
            '#wdform_' + wdid + '_country' + form_id
          ];
          jQuery.each(element, function(i, elem){
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              var element_value = jQuery(elem).val();
              if (typeof element_value != "undefined") {
                element_value = jQuery.trim(element_value);
              }
              if ( element_value == '' && typeof jQuery(elem).closest('.wd-address').css('display') !== 'undefined' && jQuery(elem).closest('.wd-address').css('display') != 'none' && jQuery(elem).length > 0 ) {
                not_filled[wdid] = elem;
              }
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery(elem).focus(function() {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_checkbox':
        case 'type_radio':
        case 'type_scale_rating':
        case 'type_paypal_checkbox':
        case 'type_paypal_radio':
        case 'type_paypal_shipping': {
          if (all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if (x.find(jQuery("div[wdid='"+ wdid +"'] input:checked")).length == 0 || jQuery("#wdform_"+ wdid +"_other_input" + form_id).val() == "" || (jQuery("#wdform_"+ wdid +"_element_quantity" + form_id).length > 0 && !parseInt(jQuery("#wdform_"+ wdid +"_element_quantity" + form_id).val()))) {
              not_filled[wdid] = true;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input"), function( i, val ){
              jQuery(this).change(function() {
                if (x.find(jQuery("div[wdid='"+ wdid +"'] input:checked")).length == 0 || jQuery("#wdform_"+ wdid +"_other_input" + form_id).val() == "" || (jQuery("#wdform_"+ wdid +"_element_quantity" + form_id).length > 0 && !parseInt(jQuery("#wdform_"+ wdid +"_element_quantity" + form_id).val())) ){
                  wd_is_filled(form_id, wdid);
                }
                else{
                  jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                  jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                }
              });
            });
          }
          break;
        }
        case 'type_star_rating': {
          var element = "#wdform_" + wdid + "_selected_star_amount" + form_id;
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery(element).val() == ""){
              not_filled[wdid] = true;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery("#wdform_" + wdid + "_element" + form_id).click(function(){
              if(jQuery(element).val() != ""){
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }
            });
          }
          break;
        }
        case 'type_range': {
          var element = ["#wdform_" + wdid + "_element" + form_id + "0", "#wdform_" + wdid + "_element" + form_id + "1"];
          jQuery.each(element, function(i, elem){
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              if(jQuery(elem).val() == ""){
                not_filled[wdid] = elem;
              }
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery(elem).focus(function() {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_grading': {
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            var count_grading = 0;
            jQuery.each(jQuery("#wdform_" + wdid + "_element" + form_id + " input"), function( i, val ){
              if(jQuery(this).val() != "")
                count_grading ++;

            });
            if(count_grading == 0)
              not_filled[wdid] = true;
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery.each(jQuery("#wdform_" + wdid + "_element" + form_id + " input"), function( i, val ){
              jQuery(this).focus(function() {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            });
          }
          break;
        }
        case 'type_slider': {
          var slider_element = "#wdform_" + wdid + "_element" + form_id;
          var element = "#wdform_" + wdid + "_slider_value" + form_id;
          var min_value = "#wdform_" + wdid + "_element_min" + form_id;
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery(element).val() == jQuery(min_value).html()){
              not_filled[wdid] = true;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery(slider_element).slider({
              change: function( event, ui ) {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                wd_is_filled(form_id, wdid);
              }
            });
          }
          break;
        }
        case 'type_date':
        case 'type_date_new': {
          var element = "#wdform_" + wdid + "_element" + form_id;
          if ( all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none" ) {
            if( jQuery(element).val() == "" ){
              not_filled[wdid] = element;
            }
          }
          if ( !field_id  && !window['check_submit'+form_id] ) {
            jQuery(element).focus(function() {
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
            }).change(function() {
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              wd_is_filled(form_id, wdid);
            });
          }
          break;
        }
        case 'type_date_range': {
          var element = ["#wdform_" + wdid + "_element" + form_id + "0", "#wdform_" + wdid + "_element" + form_id + "1"];
          jQuery.each(element, function(i, elem){
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              if(jQuery(elem).val() == ""){
                not_filled[wdid] = elem;
              }
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery(elem).focus(function() {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }).change(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_date_fields': {
          var element = ["#wdform_" + wdid + "_day" + form_id, "#wdform_" + wdid + "_month" + form_id, "#wdform_" + wdid + "_year" + form_id];
          jQuery.each(element, function(i, elem) {
            if ( all_pages || x.find(jQuery("div[wdid='" + wdid + "']")).closest(".wdform-page-and-images").css('display') != "none" ) {
              if ( jQuery(elem).val() == "" ) {
                not_filled[wdid] = elem;
              }
            }
            if ( !field_id  && !window['check_submit' + form_id] ) {
              jQuery(elem).focus(function() {
                jQuery("#form" + form_id + "div[wdid='" + wdid + "'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form" + form_id + "#wd_required_" + wdid).remove();
                jQuery("#check_min_date_" + wdid + "_" + form_id).remove();
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              }).change(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_time': {
          var element = ["#wdform_" + wdid + "_hh" + form_id, "#wdform_" + wdid + "_mm" + form_id, "#wdform_" + wdid + "_ss" + form_id];
          jQuery.each(element, function(i, elem){
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              if(jQuery(elem).val() == "" && typeof jQuery(elem).val() != "undefined"){
                not_filled[wdid] = elem;
              }
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery(elem).focus(function() {
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              }).blur(function() {
                wd_is_filled(form_id, wdid);
              });
            }
          });
          break;
        }
        case 'type_password': {
          var element = "#wdform_" + wdid + "_element" + form_id;
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery(element).val() == ""){
              not_filled[wdid] = element;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery(element).focus(function() {
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
            }).blur(function() {
              wd_is_filled(form_id, wdid);
            });
          }
          break;
        }
        case 'type_file_upload': {
          var element = "#wdform_" + wdid + "_element" + form_id;
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery(element).val() == ""){
              not_filled[wdid] = element;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]) {
            jQuery(element).focus(function() {
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
            }).change(function() {
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              wd_is_filled(form_id, wdid);
            });
          }
          break;
        }
        case 'type_matrix': {
          /* remove error message for matrix */
          jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
          jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
          if ( jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input").attr('type') == 'radio' || jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input").attr('type') == 'checkbox' ) {

            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {

              if(x.find(jQuery("div[wdid='"+ wdid +"'] input:checked")).length == 0){
                not_filled[wdid] = true;
              }

              if ( jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input").attr('type') == 'radio' ) {
                jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] div[class^='wdform-matrix-row']"), function( i, rows ) {
                  if ( jQuery(rows).find('input[type="radio"]:checked').length == 0 ) {
                    not_filled[wdid] = true;
                  }
                });
              }
            }

            if ( jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input").attr('type') == 'checkbox' ) {
              if ( !field_id  && !window['check_submit'+form_id] ) {
                jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input"), function( i, val ){
                  jQuery(this).change(function() {
                    if(x.find(jQuery("div[wdid='"+ wdid +"'] input:checked")).length == 0){
                      wd_is_filled(form_id, wdid);
                    }
                    else{
                      jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                      jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                    }
                  });
                });
              }
            }
          }
          else if(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input").attr('type') =="text") {
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              var count_input_matrix = 0;
              jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input"), function( i, val ){
                if(jQuery(this).val() != "")
                  count_input_matrix ++;
              });
              if(count_input_matrix == 0)
                not_filled[wdid] = true;
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input"), function( i, val ){
                jQuery(this)
                  .focus(function() {
                    jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                    jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                  })
                  .blur(function() {
                    wd_is_filled(form_id, wdid);
                  });
              });
            }
          }
          else {
            if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
              var count_select_matrix = 0;

              jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] select"), function( i, val ){
                if(jQuery(this).val() != "")
                  count_select_matrix ++;
              });
              if(count_select_matrix == 0)
                not_filled[wdid] = true;
            }
            if(!field_id  && !window['check_submit'+form_id]){
              jQuery.each(jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] select"), function( i, val ){
                jQuery(this).focus(function() {
                  if(jQuery(this).val() == ""){
                    jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                    jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
                  }
                }).change(function() {
                  wd_is_filled(form_id, wdid);
                }).blur(function() {
                  wd_is_filled(form_id, wdid);
                });
              });
            }
          }
          break;
        }
        case 'type_send_copy': {
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery("div[wdid='"+ wdid +"'] input:checked").length == 0){
              not_filled[wdid] = true;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery("#form" + form_id + " div[wdid='"+ wdid +"'] input").change(function() {
              if(jQuery("div[wdid='"+ wdid +"'] input:checked").length == 0){
                wd_is_filled(form_id, wdid);
              }
              else {
                jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
                jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
              }
            });
          }
          break;
        }
        case 'type_captcha':
        case 'type_arithmetic_captcha': {
          var element = "";
          if(window['labels_and_ids'+form_id][wdid] == 'type_captcha') {
            element = '#wd_captcha_input' + form_id;
          }
          else {
            element = '#wd_arithmetic_captcha_input' + form_id;
          }
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery(element).val() == ""){
              jQuery(".message_captcha").html("");
              not_filled[wdid] = element;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]){
            jQuery(element).focus(function() {
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .error_label").removeClass("error_label");
            }).blur(function() {
              wd_is_filled(form_id, wdid);
            });
          }
          break;
        }
        case 'type_signature': {
          jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
          jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").removeClass("error_label");
          var element = "#signature-file-wdform_" + wdid + "_element" + form_id;
          if(all_pages || x.find(jQuery("div[wdid='"+wdid+"']")).closest(".wdform-page-and-images").css('display') != "none") {
            if(jQuery(element).val() == ""){
              not_filled[wdid] = element;
            }
          }
          if(!field_id  && !window['check_submit'+form_id]) {
            jQuery(element).focus(function() {
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").removeClass("error_label");
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
            }).change(function() {
              jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
              jQuery("#form"+form_id+" div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").removeClass("error_label");
              wd_is_filled(form_id, wdid);
            });
          }
          break;
        }
      }
    }
  });
  if (Object.keys(not_filled).length === 0 && Object.keys(window["check_before_submit" + form_id]).length === 0) {
    return true;
  }
  else {
    if (Object.keys(not_filled).length !== 0) {
      jQuery.each( not_filled, function( wdid, elem ) {
        var label_content = jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first");
        var label_width = 0;
        if ( label_content.hasClass('wd-width-30') && !label_content.hasClass('wd-hidden') ) {
          label_width = label_content.width();
        }
        jQuery("#form"+form_id+" #wd_required_"+wdid).remove();
        jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").addClass("error_label");
        if( window['labels_and_ids'+form_id][wdid] === 'type_password' || window['labels_and_ids'+form_id][wdid] === 'type_submitter_mail') {
          jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-element-section:first").parent().after("<div id='wd_required_" + wdid + "' class='fm-not-filled fm-password-not-filled' style='margin-left: " + label_width + "px'>" + fm_objectL10n.fm_field_is_required + "</div>");
        } else {
          jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-element-section:first").parent().parent().append("<div id='wd_required_" + wdid + "' class='fm-not-filled' style='margin-left: " + label_width + "px'>" + fm_objectL10n.fm_field_is_required + "</div>");
        }
      });
    }
    if(!field_id){
      scroll_on_element(form_id);
    }
    window['check_submit'+form_id] = 1;
    return false;
  }
}

function wd_check_price_min_max(form_id, price_nim_max) {
  var x = jQuery("#form" + form_id);
  var check_price_min_max = price_nim_max ? price_nim_max : window['check_paypal_price_min_max'+form_id];
  var find_wrong_price = false;
  jQuery.each( check_price_min_max, function( wdid, min_max_option ) {
    var element = "#wdform_" + wdid + "_element" + form_id;
    var range_min = min_max_option[3] ? min_max_option[3] : 0;
    var range_max = min_max_option[4] ? min_max_option[4] : -1;
    if((min_max_option[2] ? true : false) || jQuery(element).val()!=min_max_option[1]) {
      if((range_max!=-1 && parseFloat(jQuery(element).val()) > range_max) || parseFloat(jQuery(element).val()) < range_min) {
        jQuery("#form"+form_id+" #wd_price_"+wdid).remove();
        x.find(jQuery("div[wdid='"+wdid+"'] .wdform-element-section")).parent().parent().append("<div  id='wd_price_"+wdid+"' class='fm-not-filled'>" + fm_objectL10n.fm_min_max_check_1 + min_max_option[0] + fm_objectL10n.fm_min_max_check_2 + (min_max_option[3] ? min_max_option[3] : 0) + '-' + (min_max_option[4] ? min_max_option[4] : "any") + "</div>");
        jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").addClass("error_label_price");
        find_wrong_price = true;
        if(!price_nim_max) {
          scroll_on_element(form_id);
        }
      }
    }
  });
  if(find_wrong_price === false) {
    return true;
  }
  return false;
}

/* Check Min Values for Date Of Birth Field */
function wd_check_min_date_dob(form_id){
  var success = true;
  jQuery("#form" +form_id+ " div[type='type_date_fields']").each(function() {
    var get_data_min_day = parseInt(jQuery(this).data("min-day"));
    /* if the element hasn't Min Value - break */
    if ( !get_data_min_day ) {
      return true;
    }
    else {
      var parent_div = jQuery(this).parent();
      var wdid = parent_div.attr('wdid');
      var get_data_day = parseInt(jQuery("#wdform_" + wdid + "_day" + form_id).val());
      var get_data_month = parseInt(jQuery("#wdform_" + wdid + "_month" + form_id).val());
      var get_data_year = parseInt(jQuery("#wdform_" + wdid + "_year" + form_id).val());

      /* if all elements are empty - break */
      if ( !get_data_day && !get_data_month && !get_data_year ) {
        return true;
      }
      else {
        var get_data_min_month = parseInt(jQuery(this).data("min-month"));
        var get_data_min_year = parseInt(jQuery(this).data("min-year"));

        var get_data_full_convert = new Date( get_data_year + "-" + get_data_month + "-" + get_data_day );
        var get_min_data_full_convert = new Date( get_data_min_year + "-" + get_data_min_month + "-" + get_data_min_day );

        /* if the converted date is Invalid or does not meet specified requirements, return an error message */
        if ( isNaN(get_data_full_convert) || isNaN(get_data_year) || get_data_full_convert - get_min_data_full_convert < 0 ) {
          success = false;
          jQuery("#check_min_date_" + wdid + "_" + form_id).remove();
          parent_div.append("<div id='check_min_date_" + wdid + "_" + form_id + "' class='fm-not-valid-date'>" + jQuery(this).attr("data-min-date-alert") + "</div>");
          parent_div.find(".wdform-label").addClass("error_label");
          scroll_on_element(form_id);
        }
      }
    }
  });
  return success;
}

function wd_spinner_check(form_id, spinner_check_field) {
  var x = jQuery("#form" + form_id);
  var find_wrong_values = false;
  var spinner_check = spinner_check_field ? spinner_check_field : window['spinner_check'+form_id];
  jQuery.each( spinner_check, function( wdid, spinner_values ) {
    var element = "#wdform_" + wdid + "_element" + form_id;
    if(x.find(jQuery("div[wdid='"+wdid+"']")).length != 0 && x.find(jQuery("div[wdid='"+wdid+"']")).css("display") != "none") {
      if(parseInt(jQuery(element).val()) < parseInt(spinner_values[0]) || parseInt(jQuery(element).val()) > parseInt(spinner_values[1])) {
        jQuery("#form"+form_id+" #wd_price_"+wdid).remove();
        x.find(jQuery("div[wdid='"+wdid+"'] .wdform-element-section")).parent().parent().append("<div  id='wd_price_"+wdid+"' class='fm-not-filled'>" + fm_objectL10n.fm_spinner_check + (spinner_values[0] ? spinner_values[0] : 0) + '-' + (spinner_values[1] ? spinner_values[1] : "any") + "</div>");
        jQuery("#form"+form_id+ " div[wdid='"+wdid+"'] .wdform-label-section:first .wdform-label").addClass("error_label_price");
        find_wrong_values = true;
        if(!spinner_check_field) {
          scroll_on_element(form_id);
        }
      }
    }
  });
  if(find_wrong_values === false) {
    return true;
  }
  return false;
}

function fmscrollHandler(form_id) {
  var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
  if ( !jQuery("#fm-scrollbox" + form_id).hasClass("fm-minimized") && scrollPercent >= window["scrollbox_trigger_point" + form_id] ) {
    setTimeout(function() {
      jQuery("#fm-scrollbox" + form_id).removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
      jQuery("#fm-scrollbox" + form_id).css("visibility", "");
      jQuery("#fm-scrollbox" + form_id + " .fm-header-img").addClass("fm-animated " + window["header_image_animation" + form_id]);
    }, window["scrollbox_loading_delay" + form_id] * 1000);
  }
  else {
   if ( window["scrollbox_auto_hide" + form_id] == "1") {
      jQuery("#fm-scrollbox" + form_id + " .fm-header-img").removeClass("fm-animated " + window["header_image_animation" + form_id]);
      jQuery("#fm-scrollbox" + form_id).removeClass("fm-animated fadeInUp").addClass("fm-animated fadeOutDown");
    }
  }
}

function fm_submit_form(form_id) {

  if (typeof window["before_submit" + form_id] == 'function') {
    if (window["before_submit" + form_id]()) {
      return false;
    }
  }

  if (!fm_check(0, form_id)) {
    return false;
  }

  jQuery("#form" + form_id + " button").each(function () {
    jQuery(this).attr('disabled', 'disabled');
  });

  jQuery("<input type=\"hidden\" name=\"save_or_submit"+form_id+"\" value =\"submit\" />").appendTo("#form"+form_id);
  window["onsubmit_js" + form_id]();

  if ( window['checkStripe' + form_id] == 1 ) {
    var jq_mainForm = jQuery("form[id='form"+form_id+"']");
    if (jq_mainForm.find(".StripeElement").first().parents(".wdform_row").css('display') != 'none') {
      wdfm_call_stripe(true);
    }
    else {
      if (jQuery("#form"+form_id).find('.g-recaptcha[data-size=invisible]').length > 0) {
        grecaptcha.execute();
      }
      else {
        fm_submit(form_id);
      }
    }
  }
  else {
    if (jQuery("#form"+form_id).find('.g-recaptcha[data-size=invisible]').length > 0) {
      grecaptcha.execute();
    }
    else {
      fm_submit(form_id);
    }
  }
}

function getHostName(url) {
  var match = url.match(/:\/\/(www[0-9]?\.)?(.[^/:]+)/i);
  if (match != null && match.length > 2 && typeof match[2] === 'string' && match[2].length > 0) {
    return match[2];
  }
  else {
    return null;
  }
}

/* Check if response is json */
function isJson(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

function fm_submit(form_id) {
  fm_set_input_value('fm_empty_field_validation' + form_id, jQuery('#fm_empty_field_validation' + form_id).attr('data-value') );
  var ajax_submit = jQuery("#form" + form_id + " .button-submit:not(.save_button)").attr("data-ajax");
  if( ajax_submit != '0' ) {
    jQuery('#form'+form_id+' .fm-submit-loading').css('display','inline-block');
    var form = jQuery('#form'+form_id)[0];
    var formData = new FormData(form);
    var ajax_url = jQuery('#fm_ajax_url' + form_id).data("ajax_url");
    var after_submit_redirect_url = jQuery('#form' + form_id + ' #fm_ajax_redirect_url'+ form_id).data('ajax_redirect_url');
    jQuery.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: ajax_url,
      data:  formData,
      contentType: false,
      processData: false,
      success: function(response) {
        /* Using for paypal ajax redirect */
        if ( isJson(response) ) {
          var data = JSON.parse(response);
          if ( typeof data.after_submit_redirect_url != 'undefined' && jQuery.trim(data.after_submit_redirect_url) != '' ) {
            window.location.replace(data.after_submit_redirect_url);
            return;
          }
          var d = new Date();
          var locationHref = window.location.href;
          var success_time = d.getTime();
          if( typeof data.success !== 'undefined' ) {
            success_time = data.success;
          }

          if( locationHref.indexOf("&succes=") > -1 ) {
              var array = locationHref.split("&succes=");
              locationHref = array[0];
          } else if ( locationHref.indexOf("?succes=") > -1 ) {
              var array = locationHref.split("?succes=");
              locationHref = array[0];
          }
          if( locationHref.indexOf("?") > -1 ) {
            locationHref = locationHref+'&succes=' + success_time;
          } else {
            locationHref = locationHref+'?succes=' + success_time;
          }
          var return_url = '&return='+encodeURIComponent(locationHref);
          if ( typeof data.paypal_url != 'undefined' ) {
            var url = data.paypal_url+return_url;
            window.location.replace(url);
            return;
          }
        }
        jQuery("#form" + form_id + " .button-submit").prop("disabled", false);
        jQuery('#form'+form_id+' .fm-submit-loading').hide();
        var result = jQuery(response).find('#form'+form_id).html();
        if ( after_submit_redirect_url != 0 && jQuery(result).find('.fm-not-filled.message_captcha').length == 0 && jQuery(result).filter('.fm-notice-error').length == 0 ) {
          window.location.replace(after_submit_redirect_url);
          return;
        }
        jQuery('#form'+form_id).html(result);
        /* Using for save progress addon */
        if (typeof window['fm_save_progress_buttons_' + form_id] === 'function') {
          window['fm_save_progress_buttons_' + form_id]();
        }
        if (jQuery(document).find(".g-recaptcha").length > 0) {
            fmRecaptchaInit(1);
        }
        var fm_func_name = 'fm_script_ready' + form_id;
        if ( typeof( window[fm_func_name] ) !== 'undefined' ) {
          window[fm_func_name]();
        }
        if( jQuery(result).find('.fm-not-filled.message_captcha').length > 0 ) {
            var msg = jQuery(result).find('.fm-not-filled.message_captcha').text();
            jQuery('#form'+form_id+' .fm-message').remove();
            jQuery('#form'+form_id).prepend('<div class="fm-message fm-notice-error">'+msg+'</div>');
        }
        window['check_submit' + form_id] = 0;
      },
      complete: function() {
        if ( jQuery('#form' + form_id + ' .fm-message').length == 0 ) {
          jQuery('#closing-form' + form_id).remove();
          jQuery('#fm-popover-background' + form_id).css("display", "none");
        } else {
          jQuery('#fm-popover-container' + form_id).addClass("fm-submit-message");
        }
        if ( typeof window['after_submit' + form_id] === 'function' && !jQuery("#form"+form_id).find('.message_captcha').length) {
          window['after_submit' + form_id]();
        }
      }
    });
  }
  else {
    jQuery("#form" + form_id).submit();
  }
}

function fm_reset_form(form_id) {
  if ( typeof window["before_reset" + form_id] == 'function' ) {
    window["before_reset" + form_id]();
  }
  var privacy_policy_check = jQuery("#fm_privacy_policy" + form_id);
  if (privacy_policy_check) {
    privacy_policy_check.prop('checked', false);
    fm_privacy_policy_check(privacy_policy_check);
  }
  jQuery.each(window['labels_and_ids'+form_id], function (index, elem) {
    switch(elem) {
      case "type_text":
      case "type_textarea":
      case "type_number":
      case "type_spinner":
      case 'type_own_select':
      case 'type_country':
      case 'type_date':
      case 'type_date_new':
      case 'type_hidden':
      case 'type_paypal_price_new':
      case 'type_phone_new':
      case 'type_time':
        var field = jQuery("#wdform_" + index + "_element" + form_id);
        var default_value = typeof field.data("value") != "undefined" ? field.data("value") : "";
        jQuery("#wdform_" + index + "_element" + form_id).val(default_value);
        break;
      case 'type_submitter_mail':
      case 'type_password':
        var field = jQuery("#wdform_" + index + "_element" + form_id);
        var default_value = typeof field.data("value") != "undefined" ? field.data("value") : "";
        jQuery("#wdform_" + index + "_element" + form_id).val(default_value);
        if(jQuery("#wdform_" + index + "_1_element" + form_id)){
          jQuery("#wdform_"+index+"_1_element" + form_id).val('');

          if(jQuery("#confirm_" + index + "_" + form_id))
            jQuery("#confirm_"+index+"_"  +form_id).remove();

          if(elem == 'type_submitter_mail' && jQuery("#check_email_"+index+"_"+form_id))
            jQuery("#check_email_"+index+"_"+form_id).remove();
        }
        break;
      case 'type_date_range':
        jQuery("#wdform_"+index+"_element"+form_id+"0").val('');
        jQuery("#wdform_"+index+"_element"+form_id+"1").val('');
        break;

      case 'type_send_copy':
        jQuery("#wdform_"+index+"_element"+form_id).prop('checked', false);
        break;
      case 'type_phone':
        jQuery("#wdform_"+index+"_element_first"+form_id+", #wdform_"+index+"_element_last"+form_id).val('');
        break;

      case 'type_name':
        jQuery("#wdform_"+index+"_element_first"+form_id+", #wdform_"+index+"_element_last"+form_id+", #wdform_"+index+"_element_title"+form_id+", #wdform_"+index+"_element_middle"+form_id).each(function () {
          var default_value = typeof jQuery(this).data("value") != "undefined" ? jQuery(this).data("value") : "";
          jQuery(this).val(default_value);
        });
        break;

      case 'type_address':
        jQuery("#wdform_"+index+"_street1"+form_id+", #wdform_"+index+"_street2"+form_id+", #wdform_"+index+"_city"+form_id+", #wdform_"+index+"_state"+form_id+", #wdform_"+index+"_postal"+form_id+", #wdform_"+index+"_country"+form_id).val('');
        break;

      case 'type_checkbox':
        jQuery("#form"+form_id+" div[wdid='"+index+"'] .checkbox-div input").prop('checked', false);
        jQuery("#wdform_"+index+"_other_br"+form_id).remove();
        jQuery("#wdform_"+index+"_other_input"+form_id).remove();
        break;

      case 'type_radio':
        jQuery("#form"+form_id+" div[wdid='"+index+"'] .radio-div input").prop('checked', false);
        jQuery("#wdform_"+index+"_other_br"+form_id).remove();
        jQuery("#wdform_"+index+"_other_input"+form_id).remove();
        break;

      case 'type_time':
        jQuery("#wdform_"+index+"_hh"+form_id+", #wdform_"+index+"_mm"+form_id+", #wdform_"+index+"_ss"+form_id+", #wdform_"+index+"_am_pm"+form_id).val('');
        break;

      case 'type_date_fields':
        jQuery("#wdform_"+index+"_day"+form_id+", #wdform_"+index+"_month"+form_id+", #wdform_"+index+"_year"+form_id).val('');
        break;

      case 'type_file_upload':
        jQuery("#wdform_"+index+"_element"+form_id+"_save").remove();
        break;

      case 'type_paypal_price':
        jQuery("#wdform_"+index+"_element_dollars"+form_id+", #wdform_"+index+"_element_cents"+form_id).val('');
        break;

      case 'type_paypal_select':
        jQuery("#wdform_"+index+"_element"+form_id+", #wdform_"+index+"_element_quantity"+form_id+", #form"+form_id+" div[wdid='"+index+"'] .paypal-property select").val('');
        break;

      case 'type_paypal_radio':
        jQuery("#wdform_"+index+"_element_quantity"+form_id+",#form"+form_id+" div[wdid='"+index+"'] .paypal-property select").val('');
        jQuery("#form"+form_id+" div[wdid='"+index+"'] .radio-div input").prop('checked', false);
        break;

      case 'type_paypal_shipping':
        jQuery("#form"+form_id+" div[wdid='"+index+"'] .radio-div input").prop('checked', false);
        break;

      case 'type_paypal_checkbox':
        jQuery("#wdform_"+index+"_element_quantity"+form_id+",#form"+form_id+" div[wdid='"+index+"'] .paypal-property select").val('');
        jQuery("#form"+form_id+" div[wdid='"+index+"'] .checkbox-div input").prop('checked', false);
        break;

      case 'type_star_rating':
        jQuery("#wdform_"+index+"_selected_star_amount"+form_id).val('');
        jQuery("#wdform_"+index+"_element"+form_id+" img").attr('src', fm_objectL10n.plugin_url + '/images/star.png');
        break;

      case 'type_scale_rating':
        jQuery("#form"+form_id+" div[wdid='"+index+"'] .radio-div input").prop('checked', false);
        break;

      case 'type_slider':
        jQuery("#wdform_"+index+"_element"+form_id).slider({
          value: eval(0),
        });
        jQuery("#wdform_"+index+"_element_value"+form_id).html('0');
        break;

      case 'type_range':
        jQuery("#wdform_"+index+"_element"+form_id+"0, #wdform_"+index+"_element"+form_id+"1").val('');
        break;

      case 'type_grading':
        jQuery("#wdform_"+index+"_element"+form_id+" input").val('');
        break;

      case 'type_matrix':
        jQuery("#wdform_"+index+"_element"+form_id+" .radio-div input").prop('checked', false);
        jQuery("#wdform_"+index+"_element"+form_id+" .checkbox-div input").prop('checked', false);
        jQuery("#wdform_"+index+"_element"+form_id+" input[type='text']").val('');
        jQuery("#wdform_"+index+"_element"+form_id+" select").val('');
        break;

      case 'type_paypal_total':
        jQuery("#wdform_"+index+"div_total"+form_id).html('$0');
        jQuery("#wdform_"+index+"paypal_products"+form_id).empty();
        break;

      case 'type_captcha':
        jQuery('#wd_captcha_input'+form_id).val('');
      case 'type_arithmetic_captcha':
        jQuery('#wd_arithmetic_captcha_input'+form_id).val('');
      case 'type_signature':
        jQuery('#signature-file-wdform_' + index + '_element' + form_id).val('');
        jQuery('#signature-signs-wdform_' + index + '_element' + form_id).val('');
        jQuery('#signature-clear-wdform_'+ index +'_element' + form_id).trigger('click');
        break;
      default:
        break;
    }
  });
  window["condition_js" + form_id]();
}

function fm_save_form(form_id) {
  jQuery("<input type=\"hidden\" name=\"save_or_submit"+form_id+"\" value =\"save\" />").appendTo("#form"+form_id);
  window["onsubmit_js" + form_id]();
  jQuery("#form" + form_id + " button").each(function () {
    jQuery(this).attr('disabled', 'disabled');
  });

  fm_set_input_value('fm_empty_field_validation' + form_id, jQuery('#fm_empty_field_validation' + form_id).attr('data-value') );
  jQuery("#form"+form_id).submit();
}

function fm_clear_form(form_id) {
  var clear_data = confirm(fm_objectL10n.fm_clear_data);
  if (clear_data == true) {
    jQuery("#form" + form_id + " button").each(function () {
      jQuery(this).attr('disabled', 'disabled');
    });
    jQuery.get(fm_objectL10n.form_maker_admin_ajax + '?action=FMClearProg&addon_task=clear_data&nonce=' + fm_ajax.ajaxnonce + '&form_id=' + form_id).done(function() {
      window.location = jQuery('#form' + form_id).attr('action');
    });
  }
}

function fm_set_input_value( input_id, value ){
  jQuery('#' + input_id).val(value);
}
function formOnload(form_id) {
  if (navigator.userAgent.toLowerCase().indexOf('msie') != -1 && parseInt(navigator.userAgent.toLowerCase().split('msie')[1]) === 8) {
    jQuery("#form" + form_id).find(jQuery("input[type='radio']")).click(function() {
      jQuery("input[type='radio']+label").removeClass('if-ie-div-label');
      jQuery("input[type='radio']:checked+label").addClass('if-ie-div-label')
    });
    jQuery("#form" + form_id).find(jQuery("input[type='radio']:checked+label")).addClass('if-ie-div-label');
    jQuery("#form" + form_id).find(jQuery("input[type='checkbox']")).click(function() {
      jQuery("input[type='checkbox']+label").removeClass('if-ie-div-label');
      jQuery("input[type='checkbox']:checked+label").addClass('if-ie-div-label')
    });
    jQuery("#form" + form_id).find(jQuery("input[type='checkbox']:checked+label")).addClass('if-ie-div-label');
  }

  jQuery.each(window["check_regExp_all" + form_id], function( wdid, exp ) {
    var exp_array = {};
    exp_array[wdid] = exp;
    jQuery("div[wdid='" + wdid + "'] input").blur(function() {
      wd_check_regExp(form_id, exp_array);
    }).focus(function() {
      jQuery("#form" + form_id + " #wd_exp_"+wdid).remove();
      jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:first .error_label_exp").removeClass("error_label_exp")
    });
  });

  jQuery.each(window["check_paypal_price_min_max" + form_id], function( wdid, price_min_max_option ) {
    var price_min_max_array = {};
    price_min_max_array[wdid] = price_min_max_option;
    jQuery("div[wdid='" + wdid + "'] input").blur(function() {
      wd_check_price_min_max(form_id, price_min_max_array)
    }).focus(function() {
      jQuery("#form" + form_id + " #wd_price_" + wdid).remove();
      jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:first .error_label_price").removeClass("error_label_price")
    });
  });

  jQuery.each(window["spinner_check" + form_id], function( wdid, spinner_min_max ) {
    var spinner_min_max_array = {};
    spinner_min_max_array[wdid] = spinner_min_max;
    jQuery("div[wdid='" + wdid + "'] input").blur(function() {
      wd_spinner_check(form_id, spinner_min_max_array)
    }).focus(function() {
      jQuery("#form" + form_id + " #wd_price_" + wdid).remove();
      jQuery("#form" + form_id + " div[wdid='" + wdid + "'] .wdform-label-section:first .error_label_price").removeClass("error_label_price")
    });
  });

  jQuery.each(window["file_upload_check" + form_id], function( wdid, validation_object ) {
    var upload_types_validation_array = {};
    upload_types_validation_array[wdid] = validation_object;
    jQuery("div[wdid='" + wdid + "'] input").change(function() {
      wd_file_upload_check(form_id, upload_types_validation_array);
    });
  });

  /* Prevent form from being submitted by hitting enter key on inputs. */
  jQuery('#form' + form_id + ' input').on('keypress', function (e) {
    var key_code = (e.keyCode ? e.keyCode : e.which);
    if (key_code == 13) { /*Enter keycode*/
      /*fm_submit_form(form_id);*/
      return false;
    }
  });

  jQuery("div[type='type_number'] input, div[type='type_phone'] input, div[type='type_phone_new'] input, div[type='type_spinner'] input, div[type='type_range'] input, .wdform-quantity, div[type='type_paypal_price_new'] input").keypress(function(evt) {
    return check_isnum(evt);
  });

  jQuery("div[type='type_grading'] input").keypress(function(evt) {
    return check_isnum_or_minus(evt);
  });

  jQuery("div[type='type_paypal_checkbox'] input[type='checkbox'], div[type='type_paypal_radio'] input[type='radio'], div[type='type_paypal_shipping'] input[type='radio']").click(function() {
    set_total_value(form_id);
  });
  jQuery("div[type='type_paypal_select'] select, div[type='type_paypal_price'] input, div[type='type_paypal_price_new'] input").change(function() {
    set_total_value(form_id);
  });
  jQuery(".wdform-quantity").change(function() {
    set_total_value(form_id);
  });

  jQuery("div[type='type_address'] select").change(function() {
    set_total_value(form_id);
  });

  jQuery("div[type='type_time'] input").blur(function() {
    add_0(this);
  });

  jQuery('.wdform-element-section').each(function () {
    if (jQuery(this).parent().parent().attr('type') == "type_stripe") {
      return true;
    }
    if ( !jQuery(this).parent()[0].style.width
      && parseInt(jQuery(this).width()) != 0
      && jQuery(this).parent().find(jQuery(".wdform-label-section")).length != 0 ) {
      if (jQuery(this).css('display') == "table-cell") {
        if (jQuery(this).parent().attr('type') != "type_captcha") {
          jQuery(this).parent().css('width', parseInt(jQuery(this).width()) + parseInt(jQuery(this).parent().find(jQuery(".wdform-label-section"))[0].style.width) + 15);
        }
        else {
          jQuery(this).parent().css('width', (parseInt(jQuery(this).parent().find(jQuery(".captcha_input"))[0].style.width) * 2 + 50) + parseInt(jQuery(this).parent().find(jQuery(".wdform-label-section"))[0].style.width) + 15);
        }
      }
    }
    if (parseInt(jQuery(this)[0].style.width.replace('px', '')) < parseInt(jQuery(this).css('min-width').replace('px', ''))) {
      jQuery(this).css('min-width', parseInt(jQuery(this)[0].style.width.replace('px', '')) - 10);
    }
  });

  jQuery('.wdform-label').each(function() {
    if(parseInt(jQuery(this).height()) >= 2*parseInt(jQuery(this).css('line-height').replace('px', ''))) {
      jQuery(this).parent().css('max-width', jQuery(this).parent().width());
      jQuery(this).parent().css('width', '');
    }
  });

  (function(jQuery) {
    jQuery.fn.shuffle = function() {
      var allElems = jQuery(this).find('.wd-choice'),
        getRandom = function(max) {
          return Math.floor(Math.random() * max);
        },
        shuffled = jQuery.map(allElems, function() {
          var random = getRandom(allElems.length),
            randEl = jQuery(allElems[parseInt(random)]).clone(true)[0];
          allElems.splice(random, 1);
          return randEl;
        });
      jQuery(this).find('.wd-choice').each(function(i) {
        jQuery(this).replaceWith(jQuery(shuffled[i]));
      });
      return jQuery(shuffled);
    };
  })(jQuery);

  if (typeof window["onload_js" + form_id] == 'function') {
    window["onload_js" + form_id]();
  }
  if (typeof window["before_load" + form_id] == 'function') {
    window["before_load" + form_id]();
  }
}

function fm_document_ready(form_id) {
  // Form after submit event.
  if (jQuery("#form"+form_id).hasClass('fm-form-submitted') && !jQuery("#form"+form_id).find('.message_captcha').length && typeof window["after_submit" + form_id] == 'function') {
    window["after_submit" + form_id]();
  }

  var pagebreak_count = jQuery("#form"+form_id+" .wdform-page-and-images").length;
  window['form_view_count' + form_id] = pagebreak_count;

  if (window['form_view_count' + form_id] > 1) {
    firstid = jQuery("#form"+form_id+" .wdform_page").first().attr("id");
    firstid = firstid.split("form_view");
    window['first_form_view' + form_id] = firstid[1];
    generate_page_nav(window['first_form_view' + form_id], form_id, window['form_view_count' + form_id]);
  }
  fm_initilize_form(form_id);
  window["condition_js" + form_id]();

  jQuery(document).trigger('fm_document_ready');
}

function fm_check(id, form_id, all_pages) {
  if ( !wd_is_filled(form_id, undefined, all_pages) ) {
    return false;
  }
  /* Check Stripe fields when changing page. */
  if (window['checkStripe' + form_id] == 1) {
    var jq_mainForm = jQuery("form[id='form" + form_id + "']");
    if ('none' != jq_mainForm.find(".StripeElement").first().closest('.wdform-page-and-images').css('display')) {
      if (jq_mainForm.find(".StripeElement").first().parents(".wdform_row").css('display') != 'none') {
        if (wdfm_call_stripe(false) == false) {
          return false;
        }
      }
    }
  }
  if(!wd_check_regExp(form_id)) {
    return false;
  }
  if(!wd_check_price_min_max(form_id)) {
    return false;
  }
  if(!wd_spinner_check(form_id)) {
    return false;
  }
  if(!wd_file_upload_check(form_id)) {
    return false;
  }
  if(!wd_check_min_date_dob(form_id)) {
    return false;
  }
  if (false == window["check_js" + form_id](id, form_id)) {
    return false;
  }
  return true;
}

function fmRecaptchaInit( already_rendered ) {
  if (already_rendered === undefined) {
    already_rendered = 0;
  }
  jQuery(".g-recaptcha").each(function () {
    type = jQuery(this).attr('data-size');
    jQuery(this).attr("data-render", 1);
    if (type == 'invisible') {
      form_id = jQuery(this).attr('data-form_id');
      grecaptcha.render(jQuery(this).attr('id'), {
        'sitekey': jQuery(this).attr('data-sitekey'),
        'badge': jQuery(this).attr('data-badge'),
        'callback': function () {
          fm_submit(form_id);
        }
      });
    }
    else if(type == 'v3') {
      if(jQuery(this).attr('data-sitekey') == "undefined" || jQuery(this).attr('data-sitekey') == "") return;
      var id = jQuery(this).attr("data-id");
      var form_id = jQuery(this).attr("data-form-id");
      var sitekey = jQuery(this).attr('data-sitekey');
      grecaptcha.ready(function () {
        grecaptcha.execute(sitekey).then(function (token) {
          var recaptchaResponse = document.getElementById('recaptchaV3Response_'+form_id+id);
          recaptchaResponse.value = token;
        });
      });
    } else {
      grecaptcha.render(jQuery(this).attr('id'), {
        'sitekey': jQuery(this).attr('data-sitekey'),
        'theme': 'light'
      });
    }
  });
}

/**
 * Chnage state input.
 *
 * @param id
 * @param form_id
 */
function wd_change_state_input(id, form_id) {
  if ( document.getElementById(id + "_country" + form_id)
    && document.getElementById(id + "_state" + form_id) ) {
    var flag = false;
    var state_input = document.getElementById(id + "_state" + form_id);
    if ( document.getElementById(id + "_country" + form_id).value == "United States" ) {
      var state = document.createElement('select');
      var states = fm_objectL10n.states;
      for (var r in states) {
        var option_ = document.createElement('option');
        option_.setAttribute("value", r);
        option_.innerHTML = states[r];
        state.appendChild(option_);
      }
      flag = true;
    }
    else if ( document.getElementById(id + "_country" + form_id).value == "Canada" ) {
      var state = document.createElement('select');
      var states = fm_objectL10n.provinces;
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
      state.setAttribute("id", id + "_state" + form_id);
      state.setAttribute("name", 'wdform_' + (parseInt(id.replace('wdform_', '')) + 3) + "_state" + form_id);
      state.setAttribute("class", "wd-width-100");
      var state_input_parent = state_input.parentNode;
      state_input_parent.removeChild(state_input);
      state_input_parent.insertBefore(state, state_input_parent.firstChild);
    }
  }
}

function fm_privacy_policy_check(that) {
  var element = jQuery(that);
  var button = element.parents('.wdform_row').find('.button-submit');
  if (element.is(':checked')) {
    button.prop('disabled', false);
  }
  else {
    button.prop('disabled', true);
  }
}
function fm_html_entities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function validate_received_data_from_url() {
  var params = new URLSearchParams(window.location.search);
  params.forEach(function ( key, value ) {
    jQuery("#" + value).each(function () {
      jQuery(this).keyup();
      jQuery(this).keydown();
      jQuery(this).trigger('change')
    })
  })
}