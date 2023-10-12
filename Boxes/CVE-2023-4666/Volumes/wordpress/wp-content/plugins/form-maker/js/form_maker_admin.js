jQuery( function () {
  fm_admin_ready();
});
jQuery(document).on('fm_tab_loaded, fm_tab_email_loaded', function () {
  fm_admin_ready();
});

jQuery(window).on('load', function () {
  jQuery('#fm_loading').hide();
  jQuery('#fm_admin_container').show();
  if ( typeof add_scroll_width == 'function' ) {
    add_scroll_width();
  }
  set_no_items();

  jQuery('#fm_ask_question').parent().attr('target','_blank');
});

function fm_admin_ready() {
  // Set click action to add new buttons.
  jQuery(".wd-header a").on("click", function () {
    jQuery("input[name='task']").val("add");
    jQuery(this).parents("form").submit();
  });

  // Set no items row width.
  set_no_items();

  jQuery(".wd-datepicker").each(function() {
    jQuery(this).datepicker();
    jQuery(this).datepicker("option", "dateFormat", jQuery(this).data("format"));
  });

  // Open/close section container on its header click.
  jQuery(".hndle:not(.readonly), .handlediv").each(function () {
    jQuery(this).off('click').on("click", function () {
      fm_toggle_postbox(this);
    });
  });

  jQuery(".wd-has-placeholder .dashicons.dashicons-list-view, .wd-editor-placeholder .dashicons.dashicons-list-view").each(function () {
    jQuery(this).attr("title", form_maker.add_placeholder);
  });

  jQuery(document).on("click", ".wd-has-placeholder .dashicons.dashicons-list-view, .wd-editor-placeholder .dashicons.dashicons-list-view", function(){
    fm_placeholders_popup(jQuery(this).data("id"));
  });


  fm_disabled_uninstall_btn();

  /* Add tooltip to elements with "wd-info" class. */
  if ( typeof jQuery(document).tooltip != "undefined" ) {
    jQuery(document).tooltip({
      show: null,
      items: ".wd-info",
      content: function () {
        var element = jQuery(this);
        if (element.is(".wd-info")) {
          var html = jQuery('#' + jQuery(this).data("id")).html();
          return html;
        }
      },
      open: function (event, ui) {
        if (typeof(event.originalEvent) === 'undefined') {
          return false;
        }
        var $id = jQuery(ui.tooltip).attr('id');
        /* close any lingering tooltips. */
        jQuery('div.ui-tooltip').not('#' + $id).remove();
      },
      close: function (event, ui) {
        ui.tooltip.hover(function () {
            jQuery(this).stop(true).fadeTo(400, 1);
          },
          function () {
            jQuery(this).fadeOut('400', function () {
              jQuery(this).remove();
            });
          });
      },
      position: {
        my: "center top+30",
        at: "center top",
        using: function (position, feedback) {
          jQuery(this).css(position);
          jQuery("<div>")
            .addClass("tooltip-arrow")
            .addClass(feedback.vertical)
            .addClass(feedback.horizontal)
            .appendTo(this);
        }
      }
    });
  }

  /* Don't show payments with not succeeded status in Submissions table, when "After payment has been successfully completed." option is enabled */
  if ( jQuery("#fm-submission-lists").length ) {
    jQuery("#fm-submission-lists td").each(function() {
      var get_payment_status = jQuery(this).data("status");
      if ( get_payment_status == "0" ) {
        jQuery(this).parent().hide();
      }
    });
  }

}

function wd_insert_placeholder(id, placeholder) {
  var field = document.getElementById(id);
  if ( typeof tinyMCE != "undefined" ) {
    if (tinyMCE.get(id)) {
      tinyMCE.get(id).focus();
    }
    if (field.style.display == "none") {
      tinyMCE.execCommand('mceInsertContent', false, "{" + placeholder + "}");
      return;
    }
  }
  field.focus();
  if (document.selection) {
    sel = document.selection.createRange();
    sel.text = placeholder;
  }
  else if (field.selectionStart || field.selectionStart == '0') {
    var startPos = field.selectionStart;
    var endPos = field.selectionEnd;
    field.value = field.value.substring(0, startPos)
      + "{" + placeholder + "}"
      + field.value.substring(endPos, field.value.length);
  }
  else {
    field.value += "{" + placeholder + "}";
  }
}

function fm_toggle_postbox(that) {
  jQuery(that).parent(".postbox").toggleClass("closed");
}

function fm_option_tabs_mail_validation() {
  return true;
}
/**
 * Set null value no input.
 */
function fm_clear_input_value(id) {
	jQuery('#'+ id).val('');
}

/**
 * Set no items row width.
 */
function set_no_items() {
  jQuery(".colspanchange").attr("colspan", jQuery(".wd-form table.adminlist>thead>tr>th:visible").length + jQuery(".wd-form table.adminlist>thead>tr>td:visible").length);
}

/**
 * Search.
 *
 * @param that
 */
function search(that) {
  var form = jQuery(that).parents("form");

  form.attr("action", form.attr("action") + "&paged=1&s=" + jQuery("input[name='s']").val());

  form.submit();
}

/**
 * Search on input enter.
 *
 * @param e
 * @param that
 * @returns {boolean}
 */
function input_search(e, that) {
  var key_code = (e.keyCode ? e.keyCode : e.which);
  if (key_code == 13) { /*Enter keycode*/
    search(that);
  }
  return true;
}

/**
 * Change page on input enter.
 *
 * @param e
 * @param that
 * @returns {boolean}
 */
function input_pagination(e, that) {
  var key_code = (e.keyCode ? e.keyCode : e.which);
  if (key_code == 13) { /*Enter keycode*/
    var form = jQuery(that).parents("form");
    var to_page = jQuery(that).val();
    var pages_count = jQuery(that).parents(".pagination-links").data("pages-count");
    if ( to_page <= pages_count && to_page > 0 ) {
		var nonceRE = new RegExp("&nonce_fm="+ nonce_fm +"","g");
		var action = form.attr("action").replace(nonceRE, '').replace(/&paged=(\d+)/, '') + '&paged=' + to_page;
		form.attr("action", action);
		location.href = action;
    }
  }
  return true;
}

function fm_select_value(obj) {
  obj.focus();
  obj.select();
}

function fm_doNothing(event) {
  var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
  if (keyCode == 13) {
    if (event.preventDefault) {
      event.preventDefault();
    }
    else {
      event.returnValue = false;
    }
  }
}
/**
 * Bulk actions.
 *
 * @param that
 */
function wd_bulk_action(that) {
  var action = jQuery("select[name='bulk_action']").val();

  if (action != -1) {
    if (!jQuery("input[name^='check']").is(':checked')) {
      alert(form_maker.select_at_least_one_item);
      return;
    }
    if (action == 'delete') {
      if (!confirm(form_maker.delete_confirmation)) {
        return false;
      }
    }
    jQuery("input[name='task']").val(action);
    jQuery(that).parents("form").submit();
  }
}

function fm_ajax_save(form_id) {
  var url = jQuery('#' + form_id).action;
  var search_value = jQuery("#search_value").val();
  var current_id = jQuery("#current_id").val();
  var page_number = jQuery("#page_number").val();
  var search_or_not = jQuery("#search_or_not").val();
  var ids_string = jQuery("#ids_string").val();
  var image_order_by = jQuery("#image_order_by").val();
  var asc_or_desc = jQuery("#asc_or_desc").val();
  var ajax_task = jQuery("#ajax_task").val();
  var image_current_id = jQuery("#image_current_id").val();
  ids_array = ids_string.split(",");

  jQuery.ajax({
    type: 'POST',
    url: url,
    data: {
      "search_value" : search_value,
      "current_id" : current_id,
      "page_number" : page_number,
      "image_order_by" : image_order_by,
      "asc_or_desc" : asc_or_desc,
      "ids_string" : ids_string,
      "task" : "ajax_search",
      "ajax_task" : ajax_task,
      "image_current_id" : image_current_id,
      "nonce": fm_ajax.ajaxnonce
    },
    success: function (data) {
      var str = jQuery(data).find('#images_table').html();
      jQuery('#images_table').html(str);
      var str = jQuery(data).find('#tablenav-pages').html();
      jQuery('#tablenav-pages').html(str);
      jQuery("#show_hide_weights").val("Hide order column");
      fm_show_hide_weights();
      fm_run_checkbox();
    },
  });
  return false;
}

function fm_run_checkbox() {
  jQuery("tbody").children().children(".check-column").find(":checkbox").click(function (l) {
    if ("undefined" == l.shiftKey) {
      return true
    }
    if (l.shiftKey) {
      if (!i) {
        return true
      }
      d = jQuery(i).closest("form").find(":checkbox");
      f = d.index(i);
      j = d.index(this);
      h = jQuery(this).prop("checked");
      if (0 < f && 0 < j && f != j) {
        d.slice(f, j).prop("checked", function () {
          if (jQuery(this).closest("tr").is(":visible")) {
            return h
          }
          return false
        })
      }
    }
    i = this;
    var k = jQuery(this).closest("tbody").find(":checkbox").filter(":visible").not(":checked");
    jQuery(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked", function () {
      return (0 == k.length)
    });
    return true
  });
  jQuery("thead, tfoot").find(".check-column :checkbox").click(function (m) {
    var n = jQuery(this).prop("checked"), l = "undefined" == typeof toggleWithKeyboard ? false : toggleWithKeyboard, k = m.shiftKey || l;
    jQuery(this).closest("table").children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (jQuery(this).is(":hidden")) {
        return false
      }
      if (k) {
        return jQuery(this).prop("checked")
      }
      else {
        if (n) {
          return true
        }
      }
      return false
    });
    jQuery(this).closest("table").children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (k) {
        return false
      }
      else {
        if (n) {
          return true
        }
      }
      return false
    })
  });
}

// Set value by id.
function fm_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

// Submit form by id.
function fm_form_submit(event, form_id, task, id) {
  if (document.getElementById(form_id)) {
    document.getElementById(form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}

// Check if required field is empty.
function fm_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + ' field is required.');
    jQuery('#' + id).attr('style', 'border-color: #FF0000; border-style: solid; border-width: 1px;');
    jQuery('#' + id).focus();
    jQuery('html, body').animate({
      scrollTop: jQuery('#' + id).offset().top - 200
    }, 500);
    return true;
  }
  else {
    return false;
  }
}

// Show/hide order column and drag and drop column.
function fm_show_hide_weights() {
  if (jQuery("#show_hide_weights").val() == 'Show order column') {
    jQuery(".connectedSortable").css("cursor", "default");
    jQuery("#tbody_arr").find(".handle").hide(0);
    jQuery("#th_order").show(0);
    jQuery("#tbody_arr").find(".fm_order").show(0);
    jQuery("#show_hide_weights").val("Hide order column");
    if (jQuery("#tbody_arr").sortable()) {
      jQuery("#tbody_arr").sortable("disable");
    }
  }
  else {
    jQuery(".connectedSortable").css("cursor", "move");
    var page_number;
    if (jQuery("#page_number") && jQuery("#page_number").val() != '' && jQuery("#page_number").val() != 1) {
      page_number = (jQuery("#page_number").val() - 1) * 20 + 1;
    }
    else {
      page_number = 1;
    }
    jQuery("#tbody_arr").sortable({
      handle: ".connectedSortable",
      connectWith: ".connectedSortable",
      update: function (event, tr) {
        jQuery("#draganddrop").attr("style", "");
        jQuery("#draganddrop").html("<strong><p>Changes made in this table should be saved.</p></strong>");
        var i = page_number;
        jQuery('.fm_order').each(function (e) {
          if (jQuery(this).find('input').val()) {
            jQuery(this).find('input').val(i++);
          }
        });
      }
    });//.disableSelection();
    jQuery("#tbody_arr").sortable("enable");
    jQuery("#tbody_arr").find(".handle").show(0);
    jQuery("#tbody_arr").find(".handle").attr('class', 'handle connectedSortable');
    jQuery("#th_order").hide(0);
    jQuery("#tbody_arr").find(".fm_order").hide(0);
    jQuery("#show_hide_weights").val("Show order column");
  }
}

function fm_popup(id) {
  if (typeof id === 'undefined') {
    var id = '';
  }
  var thickDims, tbWidth, tbHeight;
  thickDims = function () {
    var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
    w = (tbWidth && tbWidth < W - 90) ? tbWidth : W - 40;
    h = (tbHeight && tbHeight < H - 60) ? tbHeight : H - 40;
    if (tbWindow.length) {
      tbWindow.width(w).height(h);
      jQuery('#TB_iframeContent').width(w).height(h - 27);
      tbWindow.css({'margin-left': '-' + parseInt((w / 2), 10) + 'px'});
      if (typeof document.body.style.maxWidth != 'undefined') {
        tbWindow.css({'top': (H - h) / 2, 'margin-top': '0'});
      }
    }
  };
  thickDims();
  jQuery(window).resize(function () {
    thickDims()
  });
  jQuery('a.thickbox-preview' + id).click(function () {
    tb_click.call(this);
    var alink = jQuery(this).parents('.available-theme').find('.activatelink'), link = '', href = jQuery(this).attr('href'), url, text;
    if (tbWidth = href.match(/&width=[0-9]+/)) {
      tbWidth = parseInt(tbWidth[0].replace(/[^0-9]+/g, ''), 10);
    }
    else {
      tbWidth = jQuery(window).width() - 120;
    }

    if (tbHeight = href.match(/&height=[0-9]+/)) {
      tbHeight = parseInt(tbHeight[0].replace(/[^0-9]+/g, ''), 10);
    }
    else {
      tbHeight = jQuery(window).height() - 120;
    }
    if (alink.length) {
      url = alink.attr('href') || '';
      text = alink.attr('title') || '';
      link = '&nbsp; <a href="' + url + '" target="_top" class="tb-theme-preview-link">' + text + '</a>';
    }
    else {
      text = jQuery(this).attr('title') || '';
      link = '&nbsp; <span class="tb-theme-preview-link">' + text + '</span>';
    }
    jQuery('#TB_title').css({'background-color': '#222', 'color': '#dfdfdf'});
    jQuery('#TB_closeAjaxWindow').css({'float': 'right'});
    jQuery('#TB_ajaxWindowTitle').css({'float': 'left'}).html(link);
    jQuery('#TB_iframeContent').width('100%');
    thickDims();
    return false;
  });
  // Theme details
  jQuery('.theme-detail').click(function () {
    jQuery(this).siblings('.themedetaildiv').toggle();
    return false;
  });
}

function bwg_inputs() {
  jQuery(".fm_int_input").keypress(function (event) {
    var chCode1 = event.which || event.paramlist_keyCode;
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
      return false;
    }
    return true;
  });
}

function fm_check_isnum(e) {
  var chCode1 = e.which || e.paramlist_keyCode;
  if (chCode1 > 31
    && (chCode1 < 48 || chCode1 > 58)
    && (chCode1 != 46)
    && (chCode1 != 45)
    && (chCode1 < 65 || chCode1 > 70)
    && (chCode1 < 97 || chCode1 > 102)
  ) {
    return false;
  }
  return true;
}

function fm_change_payment_method(payment_method) {
  switch (payment_method) {
    case 'paypal':
      jQuery('.fm_payment_option').show();
      jQuery('.fm_paypal_option').show();
      jQuery('.fm_payment_option_stripe').hide();
      break;
    case 'stripe':
      jQuery('.fm_payment_option').hide();
      jQuery('.fm_paypal_option').hide();
      jQuery('.fm_payment_option_stripe').show();
      break;
    default:
      jQuery('.fm_payment_option').hide();
      jQuery('.fm_paypal_option').hide();
      jQuery('.fm_payment_option_stripe').hide();
  }
}

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type == "text" || node.type == "search")) {
    return false;
  }
}

document.onkeypress = stopRKey;

function fmRemoveHeaderImage(e, callback) {
  jQuery('#header_image_url').val('');
  jQuery("#header_image").css("background-image", '');
  jQuery("#header_image").addClass("fm-hide");
}
function fmOpenMediaUploader(e, callback) {
  if (typeof callback == "undefined") {
    callback = false;
  }
  e.preventDefault();
  var custom_uploader = wp.media({
    title: 'Upload',
    button: {
      text: 'Add Image'
    },
    multiple: false
  }).on('select', function () {
    var attachment = custom_uploader.state().get('selection').first().toJSON();
    jQuery('#header_image_url').val(attachment.url);
    jQuery("#header_image").css("background-image", 'url("' + attachment.url + '")');
    jQuery("#header_image").css("background-position", 'center');
    jQuery("#header_image").removeClass("fm-hide");
  }).open();
  return false;
}

jQuery(function () {
  jQuery('.pp_display_on #pt0').click(function () {
    var isChecked = jQuery(this).prop('checked');
    jQuery('.pp_display_on input[type="checkbox"]').prop('checked', isChecked);
    if (isChecked) {
      jQuery('.fm-posts-show, .fm-pages-show, .fm-cat-show').removeClass('fm-hide').addClass('fm-show');
    }
    else {
      jQuery('.fm-posts-show, .fm-pages-show, .fm-cat-show').removeClass('fm-show').addClass('fm-hide');
    }
  });

  jQuery('.pp_display_on input[type="checkbox"]:not("#pt0")').click(function () {
    var isChecked = jQuery(this).prop('checked');
    var everythingChecked = jQuery('.pp_display_on #pt0').prop('checked');
    if (everythingChecked && !isChecked) {
      jQuery('.pp_display_on #pt0').prop('checked', false);
    }
  });

  jQuery('.pp_display_on #pt4').click(function () {
    fm_toggle_pages(this);
  });

  jQuery('.pp_display_on #pt3').click(function () {
    fm_toggle_posts(this);
  });

  jQuery('body').on('focusin', '.pp_search_posts', function () {
    var this_input = jQuery(this);
    this_input.closest('ul').find('.pp_live_search').removeClass('fm-hide');
    if (!this_input.hasClass('already_triggered')) {
      this_input.addClass('already_triggered');
      pp_live_search(this_input, 500, true);
    }
  });

  jQuery(document).click(function () {
    jQuery('.pp_live_search').addClass('fm-hide');
  });

  jQuery('body').on('click', '.fm-pp', function () {
    return false;
  });

  jQuery('body').on('input', '.pp_search_posts', function () {
    pp_live_search(jQuery(this), 500, true);
  });

  jQuery('body').on('click', '.pp_search_results li', function () {
    var this_item = jQuery(this);

    if (!this_item.hasClass('pp_no_res')) {
      var text = this_item.text(),
        id = this_item.data('post_id'),
        main_container = this_item.closest('.fm-pp'),
        display_box = main_container.find('.pp_selected'),
        value_field = main_container.find('.pp_exclude'),
        new_item = '<span data-post_id="' + id + '">' + text + '<span class="pp_selected_remove">x</span></span>';

      if (-1 === display_box.html().indexOf('data-post_id="' + id + '"')) {
        display_box.append(new_item);
        if ('' === value_field.val()) {
          value_field.val(id);
        }
        else {
          value_field.val(function (index, value) {
            return value + "," + id;
          });
        }
      }
    }

    return false;
  });

  jQuery('body').on('click', '.pp_selected span.pp_selected_remove', function () {
    var this_item = jQuery(this).parent(),
      value_field = this_item.closest('.fm-pp').find('.pp_exclude'),
      value_string = value_field.val(),
      id = this_item.data('post_id');
    if (-1 !== value_string.indexOf(id)) {
      var str_toreplace = -1 !== value_string.indexOf(',' + id) ? ',' + id : id + ',',
        str_toreplace = -1 !== value_string.indexOf(',') ? str_toreplace : id,
        new_value = value_string;

      new_value = value_string.replace(str_toreplace, '');
      value_field.val(new_value);
    }

    this_item.remove();
    return false;
  });
	jQuery('body').on('click', '.pp_display_on_categories input[name="display_on_categories[]"]', function () {
		var all_checkbox   = jQuery('.pp_display_on_categories input[name="display_on_categories[]"]');
		var all_categories = jQuery('.fm-display-all-categories');
		var val = jQuery(this).val();
		if ( val == 'select_all_categories') {
			if ( jQuery(this).is(':checked') ) {
				all_checkbox.prop('checked', true);
			} else {
				all_checkbox.prop('checked', false);
			}
		}
		else {
			var checked_count = jQuery('input[name="display_on_categories[]"]:checked').length;
			var all_categories_count = parseInt(all_categories.attr('data-categories-count'));
			if ( !jQuery(this).prop('checked') ) {
				checked_count = checked_count - 1;
			}
			all_categories.prop('checked', false);
			if ( checked_count == all_categories_count) {
				all_categories.prop('checked', true);
			}
		}
	});
});

function fm_toggle_posts(that) {
  var isChecked = jQuery(that).prop('checked');
  if (isChecked) {
    jQuery('.fm-posts-show, .fm-cat-show').removeClass('fm-hide').addClass('fm-show');
  }
  else {
    jQuery('.fm-posts-show, .fm-cat-show').removeClass('fm-show').addClass('fm-hide');
  }
}

function fm_toggle_pages(that) {
  var isChecked = jQuery(that).prop('checked');
  if (isChecked) {
    jQuery('.fm-pages-show').removeClass('fm-hide').addClass('fm-show');
  }
  else {
    jQuery('.fm-pages-show').removeClass('fm-show').addClass('fm-hide');
  }
}

function fm_apply_options(task) {
  fm_set_input_value('task', task);
  document.getElementById('manage_form').submit();
}

function pp_live_search(input, delay, full_content) {
  var this_input = input,
    search_value = this_input.val(),
    post_type = this_input.data('post_type');

  setTimeout(function () {
    if (search_value === this_input.val()) {
      jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'manage_fm',
          task: 'fm_live_search',
          nonce: fm_ajax.ajaxnonce,
          pp_live_search: search_value,
          pp_post_type: post_type,
          pp_full_content: full_content
        },
        beforeSend: function (data) {
          this_input.css('width', '95%');
          this_input.parent().find('.fm-loading').css('display', 'inline-block');
        },
        success: function (data) {
          this_input.css('width', '100%');
          this_input.parent().find('.fm-loading').css('display', 'none');
          /* if ( true === full_content ) { */
          this_input.closest('.fm-pp').find('.pp_search_results').replaceWith(data);
          /* } else {
           this_input.closest('.fm-pp').find('.pp_search_results').append(data);
           } */
        },
        error: function (err) {
          console.log(err);
        }
      });
    }
  }, delay);
}

function fm_toggle(elem) {
  jQuery(elem).parent().next().toggleClass('hide');
}

function change_tab(elem) {
  jQuery('.fm-subscriber-header .fm-button').removeClass('active-button');
  jQuery('.fm-subscriber-header .' + elem).addClass('active-button');
  jQuery('.fm-subscriber-content').hide();
  jQuery('.' + elem + '-tab').show();
}

function change_form_type(type) {
  jQuery('.fm-form-types span').removeClass('active');
  jQuery('.fm-form-types').find('.fm-' + type).addClass('active');
  jQuery('#type_settings_fieldset .wd-group').removeClass('fm-show').addClass('fm-hide');
}

function change_hide_show(className) {
  jQuery('.' + className + '.fm-hide').removeClass('fm-hide').addClass('fm-temporary');
  jQuery('.' + className + '.fm-show').removeClass('fm-show').addClass('fm-hide');
  jQuery('.' + className + '.fm-show-table').removeClass('fm-show-table').addClass('fm-hide');
  jQuery('.' + className + '.fm-temporary').removeClass('fm-temporary').addClass('fm-show');
  if (className != 'fm-embedded') {
    fm_toggle_posts(jQuery('.pp_display_on #pt3'));
    fm_toggle_pages(jQuery('.pp_display_on #pt4'));
  }
}

function fm_change_radio_checkbox_text(elem) {
  var labels_array = [];
  labels_array['stripemode'] = ['Test', 'Live'];
  labels_array['checkout_mode'] = ['Testmode', 'Production'];
  labels_array['mail_mode'] = ['Text', 'HTML'];
  labels_array['mail_mode_user'] = ['Text', 'HTML'];
  labels_array['value'] = ['1', '0'];
  labels_array['popover_show_on'] = ['Page Exit', 'Page Load'];
  labels_array['topbar_position'] = ['Bottom', 'Top'];
  labels_array['scrollbox_position'] = ['Left', 'Right'];

  jQuery(elem).val(labels_array['value'][jQuery(elem).val()]);
  jQuery(elem).next().val(jQuery(elem).val());

  var clicked_element = labels_array[jQuery(elem).attr('name')];
  jQuery(elem).find('label').html(clicked_element[jQuery(elem).val()]);
  if (jQuery(elem).hasClass("fm-text-yes")) {
    jQuery(elem).removeClass('fm-text-yes').addClass('fm-text-no');
    jQuery(elem).find("span").animate({
      right: parseInt(jQuery(elem).css("width")) - 14 + 'px'
    }, 400, function () {
    });
  }
  else {
    jQuery(elem).removeClass('fm-text-no').addClass('fm-text-yes');
    jQuery(elem).find("span").animate({
      right: 0
    }, 400, function () {
    });
  }
}

function fm_show_hide(class_name) {
  if (jQuery('.' + class_name).hasClass('fm-hide')) {
    jQuery('.' + class_name).removeClass('fm-hide').addClass('fm-show');
  }
  else {
    jQuery('.' + class_name).removeClass('fm-show').addClass('fm-hide');
  }
}

function fm_delete_ip( id ) {
  jQuery("#td_ip_"+id+" .loading").css("display","initial");
  var url = jQuery("#blocked_ips").attr("action");
  jQuery.ajax({
    type: 'POST',
    url: url,
    data: {
      "current_id" : id,
      "nonce_fm" : jQuery("#nonce_fm").val(),
      "task" : "delete_blocked_ip",
    },
    success: function (response) {
      var paged = parseInt( jQuery(response).find("#total_for_paging").html() );
      if(!isNaN(paged)) {
        url = url.replace(/(paged=)[^\&]+/, '$1' + paged);
      }
      jQuery('#blocked_ips').parent('.wrap').load(url + ' #blocked_ips',function(){
        window.history.pushState(null, null, url);
        if (jQuery(".updated").length != 0) {
          jQuery(" .updated p strong").html("Items Successfully Deleted");
        } else {
          jQuery("<div class='updated below-h2'><p><strong>Items Successfully Deleted.</strong></p></div>").insertBefore("#blocked_ips");
        }
        // Set no items row width.
        set_no_items();
      }); //reload the form
    }
  });

}

function fm_save_ip( id ) {
  var ip = jQuery("#ip"+id).val();
  url = jQuery("#blocked_ips").attr("action");
  jQuery.ajax({
    type: 'POST',
    url: url,
    data: {
      "ip" : ip,
      "nonce_fm" : jQuery("#nonce_fm").val(),
      "task" : "insert_blocked_ip",
    },
    success: function (response) {
      jQuery("#td_ip_" + id).html('<a id="ip' + id + '" class="pointer" title="Edit" onclick="fm_edit_ip(' + id + ')">' + ip + '</a>');
      jQuery(".insert, .error").hide();
      jQuery("#fm_blocked_ips_message").html("<div class='updated'><strong><p>Items Successfully Inserted.</p></strong></div>");
      jQuery("#fm_blocked_ips_message").show();
    }
  });
}

function fm_insert_blocked_ip() {
  jQuery( '#tr .loading' ).css( "display","initial" );
  var ip = jQuery("#fm_ip").val();
  var url = window.location.href;

  jQuery.ajax({
    type: 'POST',
    url: url,
    data: {
      "ip" : ip,
      "nonce_fm" : jQuery("#nonce_fm").val(),
      "task" : "insert_blocked_ip",
      "last_id" : 1
    },
    success: function (response) {
      jQuery('#blocked_ips').parent('.wrap').load(url + ' #blocked_ips',function() {
        window.history.pushState(null, null, url);
        if (jQuery(".updated").length != 0) {
          jQuery(".updated p strong").html("Items Successfully Saved");

        } else {
          jQuery("<div class='updated below-h2'><p><strong>Items Successfully Saved.</strong></p></div>").insertBefore("#blocked_ips");
        }
      });//reload the form
    }
  });
}

function fm_edit_ip( id ) {
  var ip = jQuery("#ip" + id).html();
  var html ='<input id="ip' + id + '" class="input_th' + id + ' ip_input" type="text" onkeypress="if(event.keyCode == 13){ if (fm_check_required(\'ip' + id + '\', \'IP\')) {return false;} fm_update_blocked_ip(' + id + '); } return fm_check_isnum(event); "  value="' + ip + '" name="ip' + id + '"/>';
  html +='<input type="button" class="button ip_save" style="margin-left: 5px" onclick="if (fm_check_required(\'ip' + id + '\', \'IP\')) {return false;} fm_update_blocked_ip(' + id + '); return false;" value="Save"><div class="loading">';
  html +='<img src='+plugin_url + '/images/loading.gif></div>';
  jQuery("#td_ip_" + id).html(html);
}

function fm_enter_ip(event) {
  if (event.which == 13) {
    if (fm_check_required('fm_ip', 'IP')) {
      return false;
    }
    event.preventDefault();
    fm_insert_blocked_ip();
  }
}

function fm_update_blocked_ip(id) {
  jQuery("#td_ip_" + id + " .loading").css("display","initial");
  var ip = jQuery("#ip" + id).val();
  var url = window.location.href;
  jQuery.ajax({
    type: 'POST',
    url: url,
    data: {
      "ip" : ip,
      "current_id" : id,
      "nonce_fm" : jQuery("#nonce_fm").val(),
      "task" : "update_blocked_ip",
    },
    success: function (response) {
      jQuery('#fm-form-admin').load(url + ' #blocked_ips',function() {
        if (jQuery(".updated").length != 0) {
          window.history.pushState(null, null, url);
          jQuery(".updated p strong").html("Items Successfully Updated");

        } else {
          jQuery("<div class='updated below-h2'><p><strong>Items Successfully Updated.</strong></p></div>").insertBefore("#blocked_ips");
        }
      });//reload the form
    }
  });

}

(function(jQuery){
  jQuery.fn.serializeObject = function(){
    var self = this,
      json = {},
      push_counters = {},
      patterns = {
        "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
        "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
        "push":     /^$/,
        "fixed":    /^\d+$/,
        "named":    /^[a-zA-Z0-9_]+$/
      };
    this.build = function(base, key, value){
      base[key] = value;
      return base;
    };
    this.push_counter = function(key){
      if(push_counters[key] === undefined){
        push_counters[key] = 0;
      }
      return push_counters[key]++;
    };

    jQuery.each(jQuery(this).serializeArray(), function(){
      // skip invalid keys
      if(!patterns.validate.test(this.name)){
        return;
      }

      var k,
        keys = this.name.match(patterns.key),
        merge = this.value,
        reverse_key = this.name;

      while((k = keys.pop()) !== undefined){
        // adjust reverse_key
        reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
        // push
        if(k.match(patterns.push)){
          merge = self.build([], self.push_counter(reverse_key), merge);
        }
        // fixed
        else if(k.match(patterns.fixed)){
          merge = self.build([], k, merge);
        }
        // named
        else if(k.match(patterns.named)){
          merge = self.build({}, k, merge);
        }
      }
      json = jQuery.extend(true, json, merge);
    });
    return json;
  };
})(jQuery);

function onEnableChange(fieldset_id, content_id, input_value) {
  var fieldsetPostingOptions = jQuery("#" + fieldset_id + " #" + content_id);
  if (input_value == "1") {
    fieldsetPostingOptions.removeClass("hidden");
  }
  else {
    fieldsetPostingOptions.addClass("hidden");
  }
}

function show_stats() {  
	var select_error = jQuery('.fm-statistics .fm_error_sorted_label_key');
	if (jQuery('#sorted_label_key').val() != "") {
		select_error.hide();
		jQuery('.fm-div_stats-loading').addClass('is-active');
		jQuery.ajax({
			dataType: 'json',
			type: 'POST',
			url: show_stats_url,
			data: {
				sorted_label_key: jQuery('#sorted_label_key').val(),
				startdate: jQuery('#startstats').val(),
				enddate: jQuery('#endstats').val(),
                nonce:fm_ajax.ajaxnonce
			},
			beforeSend: function (response) {},
			error: function (err) {},
			success: function (response) {
				jQuery('.fm-div_stats-loading').removeClass('is-active');
				if(response.html){
					jQuery('#div_stats').html(response.html);
				}
			}			
		});					
	}
	else {
		select_error.show();
	}               
}

function fm_loading_show() {
	jQuery('#fm_loading').show();
}
function fm_loading_hide() {
	jQuery('#fm_loading').hide();
	if ( typeof add_scroll_width == 'function' ) {
    add_scroll_width();
  }
  set_no_items();
}

/**
 * Get form local storage.
 *
 * @returns object
 */
function getFormLocalStorage(){
	var formStoragObj = {};
	formId = getAllUrlParams().current_id;
	var formStorag = localStorage.getItem('fm_form' + formId);
	if ( formStorag != null ) {
		formStoragObj = jQuery.parseJSON(formStorag);
	}
	return formStoragObj;
}

/**
 * Get all Url params.
 *
 * @param url
 * @returns object
 */
function getAllUrlParams(url) {
  // get query string from url (optional) or window
  var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
  // we'll store the parameters here
  var obj = {};
  // if query string exists
  if (queryString) {
    // stuff after # is not part of query string, so get rid of it
    queryString = queryString.split('#')[0];
    // split our query string into its component parts
    var arr = queryString.split('&');
    for (var i=0; i<arr.length; i++) {
      // separate the keys and the values
      var a = arr[i].split('=');
      // in case params look like: list[]=thing1&list[]=thing2
      var paramNum = undefined;
      var paramName = a[0].replace(/\[\d*\]/, function(v) {
        paramNum = v.slice(1,-1);
        return '';
      });

      // set parameter value (use 'true' if empty)
      var paramValue = typeof(a[1]) === 'undefined' ? true : a[1];
      // (optional) keep case consistent
      paramName = paramName.toLowerCase();
      paramValue = paramValue.toLowerCase();

      // if parameter name already exists
      if (obj[paramName]) {
        // convert value to array (if still string)
        if (typeof obj[paramName] === 'string') {
          obj[paramName] = [obj[paramName]];
        }
        // if no array index number specified...
        if (typeof paramNum === 'undefined') {
          // put the value on the end of the array
          obj[paramName].push(paramValue);
        }
        // if array index number specified...
        else {
          // put the value at that index number
          obj[paramName][paramNum] = paramValue;
        }
      }
      // if param name doesn't exist yet, set it
      else {
        obj[paramName] = paramValue;
      }
    }
  }
  return obj;
}
function fm_disabled_uninstall_btn() {
  jQuery('.form_maker_uninstall .fm-uninstall-btn').prop('disabled', true);
  jQuery('.form_maker_uninstall #check_yes').on("click", function () {
    if ( jQuery(this).is(':checked') ) {
      jQuery('.form_maker_uninstall .fm-uninstall-btn').prop('disabled', false);
    } else {
      jQuery('.form_maker_uninstall .fm-uninstall-btn').prop('disabled', true);
    }
  });
}
function fm_html_entities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}