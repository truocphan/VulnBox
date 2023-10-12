jQuery(window).resize(function() {
  jQuery("body").each(function () {
    window.parent.fm_set_shortcode_popup_dimensions(jQuery(this).data("width"), jQuery(this).data("height"));
  });
});

jQuery(function () {
  var short_code = get_params("Form");
  var form = jQuery("select[name='form_maker_id']");

  if ( typeof jQuery().datepicker !== 'undefined' ) {
    // Add datepicker to start date and end date.
    jQuery(".wd-datepicker").datepicker();
    jQuery(".wd-datepicker").datepicker("option", "dateFormat", "yy-mm-dd");
  }

  if (short_code) {
    form.val(short_code['id']);
    if (short_code['type']) {
      jQuery("#startdate").val(short_code['startdate']);
      jQuery("#enddate").val(short_code['enddate']);
      jQuery("#submit_date").prop("checked", (short_code['submit_date'] == "1" ? true : false));
      jQuery("#submitter_ip").prop("checked", (short_code['submitter_ip'] == "1" ? true : false));
      jQuery("#username").prop("checked", (short_code['username'] == "1" ? true : false));
      jQuery("#useremail").prop("checked", (short_code['useremail'] == "1" ? true : false));
      jQuery("#form_fields").prop("checked", (short_code['form_fields'] == "1" ? true : false));


      show = short_code['show'].split(",");
      jQuery("#csv").prop("checked", (show[0] == "1" ? true : false));
      jQuery("#xml").prop("checked", (show[1] == "1" ? true : false));
      jQuery("#title").prop("checked", (show[2] == "1" ? true : false));
      jQuery("#search").prop("checked", (show[3] == "1" ? true : false));
      jQuery("#ordering").prop("checked", (show[4] == "1" ? true : false));
      jQuery("#entries").prop("checked", (show[5] == "1" ? true : false));
      jQuery("#views").prop("checked", (show[6] == "1" ? true : false));
      jQuery("#conversion_rate").prop("checked", (show[7] == "1" ? true : false));
      jQuery("#pagination").prop("checked", (show[8] == "1" ? true : false));
      jQuery("#stats").prop("checked", (show[9] == "1" ? true : false));
    }
    jQuery("input[name='insert']").val(form_maker.update);
  }


});

/**
 * Get shortcodes attributes.
 *
 * @param module_name
 * @returns {*}
 */
function get_params(module_name) {
  var selected_text = '';
  if (window.parent.window['wdg_cb_tw/fm-submissions_shortcode'] != undefined) {
    selected_text = window.parent.window['wdg_cb_tw/fm-submissions_shortcode'];
  }
  else if (top.tinyMCE.activeEditor) {
    selected_text = top.tinyMCE.activeEditor.selection.getContent();
  }
  else {
    selected_text = get_textarea_selection(top.wpActiveEditor);
  }
  var module_start_index = selected_text.indexOf("[" + module_name);
  var module_end_index = selected_text.indexOf("]", module_start_index);
  var module_str = "";
  if ((module_start_index == 0) && (module_end_index > 0)) {
    module_str = selected_text.substring(module_start_index + 1, module_end_index);
  }
  else {
    return false;
  }
  var params_str = module_str.substring(module_str.indexOf(" ") + 1);
  var key_values = params_str.split(" ");
  var short_code_attr = new Array();
  for (var key in key_values) {
    var short_code_index = key_values[key].split('=')[0];
    var short_code_value = key_values[key].split('=')[1];
    short_code_value = short_code_value.substring(1, short_code_value.length - 1);
    short_code_attr[short_code_index] = short_code_value;
  }
  return short_code_attr;
}

/**
 * Get selected text from textarea.
 *
 * @param id
 * @returns {*}
 */
function get_textarea_selection(id) {
  var textComponent = top.document.getElementById(id);
  var selectedText;
  if (textComponent.selectionStart !== undefined) {
    // Standards Compliant Version
    var startPos = textComponent.selectionStart;
    var endPos = textComponent.selectionEnd;
    selectedText = textComponent.value.substring(startPos, endPos);
  }
  else if (document.selection !== undefined) {
    // IE Version
    textComponent.focus();
    var sel = document.selection.createRange();
    selectedText = sel.text;
  }
  return selectedText;
}

/**
 * Insert shortcode.
 */
function insert_shortcode(type) {
  window.parent.window.jQuery(window.parent.document).trigger("onOpenShortcode");
  var form = jQuery("select[name='form_maker_id']");
  if ( form.val() != 0 ) {
    var shortcode = '[Form id="' + form.val() + '"';
    if (type != 'form') {
      shortcode += ' type="submission"';
      shortcode += ' startdate="' + jQuery("#startdate").val() + '"';
      shortcode += ' enddate="' + jQuery("#enddate").val() + '"';
      shortcode += ' submit_date="' + (jQuery("#submit_date").is(':checked') ? 1 : 0) + '"';
      shortcode += ' submitter_ip="' + (jQuery("#submitter_ip").is(':checked') ? 1 : 0) + '"';
      shortcode += ' username="' + (jQuery("#username").is(':checked') ? 1 : 0) + '"';
      shortcode += ' useremail="' + (jQuery("#useremail").is(':checked') ? 1 : 0) + '"';
      shortcode += ' form_fields="' + (jQuery("#form_fields").is(':checked') ? 1 : 0) + '"';

      var show = jQuery("#csv").is(':checked') ? "1," : "0,";
      show += jQuery("#xml").is(':checked')  ? "1," : "0,";
      show += jQuery("#title").is(':checked')  ? "1," : "0,";
      show += jQuery("#search").is(':checked')  ? "1," : "0,";
      show += jQuery("#ordering").is(':checked')  ? "1," : "0,";
      show += jQuery("#entries").is(':checked') ? "1," : "0,";
      show += jQuery("#views").is(':checked') ? "1," : "0,";
      show += jQuery("#conversion_rate").is(':checked') ? "1," : "0,";
      show += jQuery("#pagination").is(':checked') ? "1," : "0,";
      show += jQuery("#stats").is(':checked') ? "1" : "0";

      shortcode += ' show="' + show + '"';
    }
    shortcode += ']';

    if (window.parent.window['wdg_cb_tw/fm-submissions']) {
      window.parent['wdg_cb_tw/fm-submissions'](shortcode, 0);
      return;
    }
    else {
      window.parent.send_to_editor( shortcode );
      window.parent.tb_remove();
    }
  }
  else {
    alert(form_maker.insert_form);
  }
}