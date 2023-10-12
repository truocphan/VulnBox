jQuery(function() {
	jQuery('.theme-detail').click(function () {
		jQuery(this).siblings('.themedetaildiv').toggle();
		return false;
	});
	jQuery("#startdate, #startstats, #enddate, #endstats").datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});

	show_hide_columns_history();
	add_scroll_width();
	add_scroll_left();
	fm_Tooltip();
	filter_fields_submit();
});

jQuery(window).scroll(function () {
	var wrapperTop = 0;
	var scrollTop = jQuery(this).scrollTop();
	var tablenav = jQuery('.tablenav');
	if (tablenav.length) {
		wrapperTop = tablenav.offset().top;
	}
	if ( scrollTop > wrapperTop ) {
		jQuery('.table-wrapper-1').addClass('fixed').css({'width': jQuery('#admin_form').width() + 'px' });
	} else {
		jQuery('.table-wrapper-1').removeClass('fixed')
	}
});

jQuery(window).on('load', function (e) {
	fm_popup();
	if (typeof jQuery().fancybox !== 'undefined' && jQuery.isFunction(jQuery().fancybox)) {
	  jQuery(".fm_fancybox").fancybox({
		'maxWidth ': 600,
		'maxHeight': 500
	  });
	}
});

jQuery(window).on("resize", function () {
	var width = jQuery(window).width();
	if ( width <= 765 ) {
		document.getElementById('fm-fields-filter').style.display = 'none';
		remove_scroll_width();
	}
	else {
		if ( jQuery('.hide-filter.hide').is(":visible") ) {
			document.getElementById('fm-fields-filter').style.display = '';
		}
		add_scroll_width();
	}
});

function filter_fields_submit() {
	jQuery('#fm-fields-filter').find('input').keypress( function(e) {
		if ( e.which == 13 ) {
      jQuery('#fm-fields-filter').find('#fm_is_search').val(1);
		   fm_form_submit(false, 'admin_form');
		}
	});
}

function show_hide_filter() {
  jQuery(".show-filter").toggle();
  jQuery(".hide-filter").toggle();
  if (document.getElementById('fm-fields-filter').style.display == "none") {
	document.getElementById('fm-fields-filter').style.display = '';
	jQuery('.fm-bulk-actions .search_reset_button').show();
  }
  else {
	document.getElementById('fm-fields-filter').style.display = "none";
	jQuery('.fm-bulk-actions .search_reset_button').hide();
  }
  add_scroll_width();
  return false;
}

function add_scroll_width() {
	var wrapFullWidth = jQuery('.fm-table-submissions').width();
	var scrollWidth = 0;
	jQuery.each(jQuery("#fm-scroll table tr.fm_table_head td:visible, #fm-scroll table tr.fm_table_head th:visible"), function( index, value ) {
      scrollWidth += jQuery(this).width();
	});
	jQuery(".table-scroll-1, .table-scroll-2").css({"width": scrollWidth + "px"});
	jQuery(".table-wrapper-1, .table-wrapper-2").css({"overflow-x": 'scroll'});
	if( scrollWidth < wrapFullWidth ){
		remove_scroll_width();
	}
}

function remove_scroll_width() {
  jQuery(".table-wrapper-1, .table-wrapper-2").css('overflow-x', 'hidden');
  jQuery(".table-scroll-1, .table-scroll-2").css('width', 'auto');
}

function add_scroll_left() {
	jQuery(".table-wrapper-1").scroll(function () {
	  jQuery(".table-wrapper-2").scrollLeft(jQuery(".table-wrapper-1").scrollLeft());
	});
	jQuery(".table-wrapper-2").scroll(function () {
	  jQuery(".table-wrapper-1").scrollLeft(jQuery(".table-wrapper-2").scrollLeft());
	});
}

function tableOrdering(order, dir, task) {
  var form = document.admin_form;
  form.filter_order2.value = order;
  form.filter_order_Dir2.value = dir;
  submitform(task);
}

function ordering(name, as_or_desc) {
  document.getElementById('asc_or_desc').value = as_or_desc;
  document.getElementById('order_by').value = name;
  document.getElementById('admin_form').submit();
}

function renderColumns() {
  allTags = document.getElementsByTagName('*');
  for (curTag in allTags) {
    if (typeof(allTags[curTag].className) != "undefined") {
      if (allTags[curTag].className.indexOf('_fc') > 0) {
        curLabel = allTags[curTag].className.replace('_fc', '');
        curLabel = curLabel.replace('table_large_col ', '');
        if (document.forms.admin_form.hide_label_list.value.indexOf('@' + curLabel + '@') >= 0) {
          allTags[curTag].style.display = 'none';
        }
        else {
          allTags[curTag].style.display = '';
        }
      }
    }
    if (typeof(allTags[curTag].id) != "undefined") {
      if (allTags[curTag].id.indexOf('_fc') > 0) {
        curLabel = allTags[curTag].id.replace('_fc','');
        if (document.forms.admin_form.hide_label_list.value.indexOf('@' + curLabel + '@') >= 0) {
          allTags[curTag].style.display = 'none';
        }
        else {
          allTags[curTag].style.display = '';
        }
      }
    }
  }
}

function clickLabChB(label, ChB) { 
  document.forms.admin_form.hide_label_list.value = document.forms.admin_form.hide_label_list.value.replace('@' + label + '@', '');
  if ( document.forms.admin_form.hide_label_list.value == '' ) {
    document.getElementById('ChBAll').checked = true;
  }
  if ( ! (ChB.checked) ) {
    document.forms.admin_form.hide_label_list.value += '@' + label + '@';
    document.getElementById('ChBAll').checked = false;
  }
  renderColumns();
  set_all_column_checkbox_checked();
}

function toggleChBDiv(flag) {
  if (flag) {
    /* sizes = window.getSize().size;*/
    var width = jQuery(window).width();
    var height = jQuery(window).height();
    document.getElementById("sbox-overlay").style.width = width + "px";
    document.getElementById("sbox-overlay").style.height = height + "px";
    document.getElementById("ChBDiv").style.left = Math.floor((width - 350) / 2) + "px";

    document.getElementById("ChBDiv").style.display = "block";
    document.getElementById("sbox-overlay").style.display = "block";
	set_columns_history_checked();
 }
  else {
    document.getElementById("ChBDiv").style.display = "none";
    document.getElementById("sbox-overlay").style.display = "none";
	set_show_hide_column_ids();
	show_hide_columns_history();
  }
  add_scroll_width();
  add_scroll_left();
}

function set_columns_history_checked() {
	if ( getFormLocalStorage().show_hide_column_ids ) {
		var show_hide_column_ids = getFormLocalStorage().show_hide_column_ids;
		var checkboxs = jQuery('#ChBDiv input[type=checkbox]');
			checkboxs.filter(':checkbox').prop('checked', false);
		var ChBAll = document.getElementById('ChBAll');
		if ( checkboxs.length != show_hide_column_ids.length ) {
			jQuery('#ChBAll').addClass('fm-remove_before');
		}
		if ( checkboxs.length == show_hide_column_ids.length ) {
			jQuery('#ChBAll').removeClass('fm-remove_before');
		}
		jQuery.each( show_hide_column_ids, function ( i, val) {
			jQuery('#ChBDiv #'+ val).prop('checked',true).prop('checked');
		});
	}
}

function set_all_column_checkbox_checked() {
	var ChBAll = jQuery('#ChBAll');
	var checkboxs = jQuery('#ChBDiv input[type=checkbox]');
	if ( checkboxs.length == checkboxs.filter(":checked").length ) {
		ChBAll.removeClass('fm-remove_before').prop('checked', true);
	}
	else {
		ChBAll.addClass('fm-remove_before').prop('checked', false);
	}
}

function set_show_hide_column_ids() {
	var ids = [];
	var obj = {};
	jQuery('#ChBDiv input[type=checkbox]').each(function ( i, val ) {
		if ( this.checked ) {
		   var id = jQuery(this).attr('id');
		   ids.push( id );
		}
	});
	obj.show_hide_column_ids = ids;
	localStorage.setItem('fm_form' + formId, JSON.stringify(obj));
}
function show_hide_columns_history() {
	if ( getFormLocalStorage().show_hide_column_ids ) {
		jQuery('#fm-submission-lists th, #fm-submission-lists td').addClass('fm-hide-column');
		jQuery.each( getFormLocalStorage().show_hide_column_ids , function( key, val ) {
			var columnClassName = val.replace("fm_check_id_", "");
				columnClassName = columnClassName.replace("fm_check_", "");
			jQuery('#fm-submission-lists .' + columnClassName + '_fc' ).removeClass('fm-hide-column').addClass('fm-show-column');
		});
		jQuery('#fm-submission-lists .fm-column-not-hide').removeClass('fm-hide-column');
	}
}

function submit_del(href_in) {
  document.getElementById('admin_form').action = href_in;
  document.getElementById('admin_form').submit();
}

function submitbutton(pressbutton) {
  var form = document.adminForm;
  if (pressbutton == 'cancel_theme') {
    submitform(pressbutton);
    return;
  }
  if (document.getElementById('title').value == '') {
    alert('The theme must have a title.')
    return;
  }
  submitform(pressbutton);
}

function submitform(pressbutton) {
  document.getElementById('adminForm').action = document.getElementById('adminForm').action + "&task=" + pressbutton;
  document.getElementById('adminForm').submit();
}

function edit_star_rating(id, a) {
  fm_rated = true;
  star_amount = document.getElementById(a + '_star_amountform_id_temp').value;
  for (var j = 0; j <= id; j++) {
    document.getElementById(a + '_star_' + j).src = plugin_url + '/images/star_yellow.png';
  }
  for (var k = id + 1; k <= star_amount - 1; k++) {
    document.getElementById(a + '_star_' + k).src = plugin_url + '/images/star.png';
  }
  star_amount = id + 1;
  document.getElementById(a + '_selected_star_amountform_id_temp').value = star_amount;
  document.getElementById('submission_' + a).value = star_amount + '/' + document.getElementById(a + '_star_colorform_id_temp').value;
}

function edit_scale_rating(checked_value, a) {
  if (!checked_value) {
    var checked_radio_value = 0;
  }
  scale_amount = document.getElementById(a + '_scale_checkedform_id_temp').value;
  document.getElementById('submission_' + a).value = checked_value + '/' + scale_amount;
}

function edit_grading(num, items_count) {
  var sum = 0;
  var elements_to_add = "";
  for (var k = 0; k < 100; k++) {
    if (document.getElementById(num + '_element' + k)) {
      if (document.getElementById(num + '_element' + k).value) {
        sum = sum + parseInt(document.getElementById(num + '_element' + k).value);
      }
    }
    if (sum > document.getElementById(num + '_grading_totalform_id_temp').innerHTML) {
      document.getElementById(num + '_text_elementform_id_temp').innerHTML = " Your score should be less than " + document.getElementById(num + '_grading_totalform_id_temp').innerHTML;
    }
  }
  document.getElementById(num + '_grading_sumform_id_temp').innerHTML = sum;
  element = document.getElementById(num + '_element_valueform_id_temp').value;
  element = element.split(':');
  for (var k = 0; k < (element.length - 1) / 2; k++) {
    if (document.getElementById(num + '_element' + k).value) {
      elements_to_add += document.getElementById(num + '_element' + k).value + ":";
    }
    else {
      elements_to_add += ":";
    }
  }
  element = element.slice((element.length - 1) / 2);
  element = element.join(':');
  grading = elements_to_add + element;
  document.getElementById(num + '_element_valueform_id_temp').value = grading;
  document.getElementById('submission_' + num).value = grading + "***grading***";
}

function edit_range(value, id, num) {
  document.getElementById(id + '_element' + num).value = value;
  document.getElementById('submission_' + id).value = document.getElementById(id + '_element0').value + "-" + document.getElementById(id + '_element1').value;
}

function change_radio_values(a, id, rows_count, columns_count) {
  var annnn = "";
  var not_found = true;
  for (var j = 1; j <= rows_count; j++) {
    for (var k = 1; k <= columns_count; k++) {
      if (document.getElementById(id + '_input_elementform_id_temp' + j + '_' + k).checked == true) {
        annnn += j + '_' + k + '***';
        not_found = false;
        break;
      }
    }
    if (not_found == true) {
      annnn += '0' + '***';
    }
    not_found = true;
  }
  var element = document.getElementById(id + '_matrixform_id_temp').value;
  element = element.split('***');
  element = element.slice(0, -(rows_count + 1));
  element = element.join('***');
  element += '***' + annnn;
  document.getElementById('submission_' + id).value = element + '***matrix***';
  document.getElementById(id + '_matrixform_id_temp').value = element;
}

function change_text_values(a,id,rows_count,columns_count){
    var annnn="";
    for(var j=1;j<=rows_count;j++)
    {
        for(var k=1;k<=columns_count;k++)
        {
            annnn += document.getElementById(id+'_input_elementform_id_temp'+j+'_'+k).value+'***';
        }
    }
    var element = document.getElementById(id+'_matrixform_id_temp').value;
    element = element.split('***');
    element = element.slice(0,-(rows_count*columns_count+1));
    element = element.join('***');
    element += '***'+annnn;
    document.getElementById('submission_'+id).value=element+'***matrix***';
    document.getElementById(id+'_matrixform_id_temp').value=element;
}

function change_checkbox_values(a,id,rows_count,columns_count){
    var annnn="";
    for(var j=1;j<=rows_count;j++)
    {
        for(var k=1;k<=columns_count;k++)
        {
            if(document.getElementById(id+'_input_elementform_id_temp'+j+'_'+k).checked==true)
                annnn += 1+'***';
            else
                annnn += 0+'***';
        }
    }
    var element = document.getElementById(id+'_matrixform_id_temp').value;
    element = element.slice(0,-(4*rows_count*columns_count));
    element += annnn;
    document.getElementById('submission_'+id).value=element+'***matrix***';
    document.getElementById(id+'_matrixform_id_temp').value=element;
}

function change_option_values(a,id,rows_count,columns_count){
    var annnn="";
    for(var j=1;j<=rows_count;j++)
    {
        for(var k=1;k<=columns_count;k++)
        {
            annnn += document.getElementById(id+'_select_yes_noform_id_temp'+j+'_'+k).value+'***';
        }
    }
    var element = document.getElementById(id+'_matrixform_id_temp').value;
    element = element.split('***');
    element = element.slice(0,-(rows_count*columns_count+1));
    element = element.join('***');
    element += '***'+annnn;
    document.getElementById('submission_'+id).value=element+'***matrix***';
    document.getElementById(id+'_matrixform_id_temp').value=element;
}

// Tooltip for long labels
function fm_Tooltip(){
// Tooltip only Text
    jQuery('.fm_masterTooltip').hover(function(){
        // Hover over code
        var title = jQuery(this).attr('title');
        jQuery(this).data('tipText', title).removeAttr('title');
        jQuery('<p class="fm_tooltip"></p>')
            .text(title)
            .appendTo('body')
            .fadeIn('slow');
    }, function() {
        // Hover out code
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.fm_tooltip').remove();
    }).mousemove(function(e) {
        var mousex = e.pageX -100; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        jQuery('.fm_tooltip')
            .css({ top: mousey, left: mousex })
    });
}
