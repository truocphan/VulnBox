j = 2;
var fm_need_enable = true;
var is_addon_stripe_active;
var is_addon_calculator_active;

if (ajaxurl.indexOf("://") != -1) {
  var url_for_ajax = ajaxurl;
}
else {
  var url_for_ajax = location.protocol + '//' + location.host + ajaxurl;
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function disable_past_days(value, id) {
	var dis_past_days = value == true ? true : false;
	var input_p = document.getElementById(id+'_buttonform_id_temp');
		input_p.setAttribute("format", value);
}

function set_send(id)
{	
if(document.getElementById(id).value=="yes")
	document.getElementById(id).setAttribute("value", "no")
else
	document.getElementById(id).setAttribute("value", "yes")
}

function show_datepicker(id){

	jQuery("#"+id).datepicker("show");
}

function hide_time(id){

	if(document.getElementById(id+"form_id_temp").value=="no")
		{
			document.getElementById(id+"form_id_temp").value="yes";	
		}	
		else
		{
			document.getElementById(id+"form_id_temp").value="no";
		}
}

function show_week_days(id, week_day) {
	var w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
	if ( week_day ) {
		if( document.getElementById("el_show_" + week_day).checked == true )
			document.getElementById(id+"_show_week_days").setAttribute(week_day, "yes");
		else
			document.getElementById(id+"_show_week_days").setAttribute(week_day, "no");
	}
	jQuery("input[name^="+id+"_elementform_id_temp]").datepicker( "option", "beforeShowDay", function(date){
		var w_hide_sunday = jQuery("#" + id + "_show_week_days").attr('sunday') == 'yes' ? 'true' : 'day != 0';
		var w_hide_monday = jQuery("#" + id + "_show_week_days").attr('monday') == 'yes' ? 'true' : 'day != 1';
		var w_hide_tuesday = jQuery("#" + id + "_show_week_days").attr('tuesday') == 'yes' ? 'true' : 'day != 2';
		var w_hide_wednesday = jQuery("#" + id + "_show_week_days").attr('wednesday') == 'yes' ? 'true' : 'day != 3';
		var w_hide_thursday = jQuery("#" + id + "_show_week_days").attr('thursday') == 'yes' ? 'true' : 'day != 4';
		var w_hide_friday = jQuery("#" + id + "_show_week_days").attr('friday') == 'yes' ? 'true' : 'day != 5';
		var w_hide_saturday = jQuery("#" + id + "_show_week_days").attr('saturday') == 'yes' ? 'true' : 'day != 6';

		var invalid_dates = jQuery("#"+id+"_invalid_dates_id_temp").val();
		var invalid_dates_finish = [];
		var invalid_dates_start = invalid_dates.split(",");
		var invalid_date_range =[];
		
		for ( var i = 0; i < invalid_dates_start.length; i++ ) {
			invalid_dates_start[i] = invalid_dates_start[i].trim();
			if (invalid_dates_start[i].length < 11 ) {
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
		jQuery.each(invalid_date_range, function( index, value ) {
			for ( var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1) ) {
				invalid_dates_finish.push(jQuery.datepicker.formatDate(w_format, d));
			}
		});
		var string_days = jQuery.datepicker.formatDate(w_format, date);
		var day = date.getDay();
		return [invalid_dates_finish.indexOf(string_days) == -1 && eval(w_hide_sunday) && eval(w_hide_monday) && eval(w_hide_tuesday) && eval(w_hide_wednesday) && eval(w_hide_thursday) && eval(w_hide_friday) && eval(w_hide_saturday) ];
	});
}

function set_sel_am_pm(select_)
{
	if(select_.options[0].selected) 
	{
		select_.options[0].setAttribute("selected", "selected");
		select_.options[1].removeAttribute("selected");
	}
	else
	{
		select_.options[1].setAttribute("selected", "selected");
		select_.options[0].removeAttribute("selected");
	}

}

function change_captcha_digit(digit) {
	captcha=document.getElementById('_wd_captchaform_id_temp');
	if (document.getElementById('captcha_digit').value) {	
		captcha.setAttribute("digit", digit);
		captcha.setAttribute("src", url_for_ajax + "?action=formmakerwdcaptcha&digit="+digit+"&i=form_id_temp");
		document.getElementById('_wd_captcha_inputform_id_temp').style.width=(document.getElementById('captcha_digit').value*10+15)+"px";
	}
	else {
		captcha.setAttribute("digit", "6");
		captcha.setAttribute("src", url_for_ajax+"?action=formmakerwdcaptcha&digit=6&i=form_id_temp");
		document.getElementById('_wd_captcha_inputform_id_temp').style.width=(6*10+15)+"px";
	}
}

function check_isnum_interval(e, id, from, to)
{
	
   	var chCode1 = e.which || e.keyCode;
    	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	val=""+document.getElementById(id).value+String.fromCharCode(chCode1);

	if(val.length>2)
        	return false;
			
	if(val=='00')
        	return false;
			
	if((val<from) || (val>to))
        	return false;
	return true;

}


function check_isnum_point(e)
{
   	var chCode1 = e.which || e.keyCode;
	
	if (chCode1 ==46)
		return true;
	
	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}

function check_isnum_price(e, value)
{
	var chCode1 = e.which || e.keyCode;
	if(value == '' || value.indexOf(".") > -1){
		if (chCode1 > 31  && (chCode1 < 48 || chCode1 > 57))
			return false;
		}
    	if (chCode1 > 31  && chCode1!=46 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}

function check_isspacebar(e)
{
	
   	var chCode1 = e.which || e.keyCode;
	if (chCode1 == 32 )
        return false;	

	return true;
}

function change_w_label(id, w)
{
	if(document.getElementById(id))
	document.getElementById(id).innerHTML=w;
}

function change_w(id, w)
{
	document.getElementById(id).setAttribute("width", w)
}

function change_h(id, h)
{
	document.getElementById(id).setAttribute("height", h);
}

function change_key(value, attribute)
{
	document.getElementById('wd_recaptchaform_id_temp').setAttribute(attribute, value);
}

function captcha_refresh(id)
{	
	srcArr=document.getElementById(id+"form_id_temp").src.split("&r=");
	document.getElementById(id+"form_id_temp").src=srcArr[0]+'&r='+Math.floor(Math.random()*100);
	document.getElementById(id+"_inputform_id_temp").value='';
}

function up_row(id) {
  if (typeof event != "undefined") {
    event.stopPropagation();
  }
	wdform_field=document.getElementById("wdform_field"+id);
	wdform_row=wdform_field.parentNode;
	wdform_column=wdform_row.parentNode;
	wdform_section=wdform_column.parentNode;
	wdform_page=wdform_section.parentNode;

	k=0;
	
	while(wdform_column.childNodes[k])
	{
		if(wdform_column.childNodes[k].getAttribute("wdid"))
			if(id==wdform_column.childNodes[k].getAttribute("wdid"))
				break;
		k++;
	}

	if(k!=0)
	{
		up=wdform_column.childNodes[k-1];
		down=wdform_column.childNodes[k];
		wdform_column.removeChild(down);
		wdform_column.insertBefore(down, up);
		return;
	}
	
	///////////en depqum yerb section breaka
	
	if(wdform_section.previousSibling)
	{
		if(wdform_section.previousSibling.getAttribute('type'))
		{
			wdform_section.previousSibling.previousSibling.firstChild.appendChild(wdform_row);
			return;
		}
	}

	///////////pagei mej

	page_up(id);
}

function down_row(id) {
  if (typeof event != "undefined") {
    event.stopPropagation();
  }
	wdform_field=document.getElementById("wdform_field"+id);
	wdform_row=wdform_field.parentNode;
	wdform_column=wdform_row.parentNode;
	wdform_section=wdform_column.parentNode;
	wdform_page=wdform_section.parentNode;

	l=wdform_column.childNodes.length;
	/*
	form=wdform_column
	*/
	k=0;
	
	while(wdform_column.childNodes[k])
	{
		if(wdform_column.childNodes[k].getAttribute("wdid"))
			if(id==wdform_column.childNodes[k].getAttribute("wdid"))
				break;
		k++;
	}

	if(k!=l-1)
	{
	///////////ira mej
		up=wdform_column.childNodes[k];
		down=wdform_column.childNodes[k+2];
		wdform_column.removeChild(up);
		
		if(!down)
			down=null;

		wdform_column.insertBefore(up, down);
		return;
	}
	///////////en depqum yerb section breaka
	
	if(wdform_section.nextSibling.getAttribute('type'))
	{
		wdform_section.nextSibling.nextSibling.firstChild.appendChild(wdform_row);
		return;
	}

	///////////pagei mej
	page_down(id);
}

function right_row(id) {
  if (typeof event != "undefined") {
    event.stopPropagation();
  }
	wdform_field=document.getElementById("wdform_field"+id);
	wdform_row=wdform_field.parentNode;
	wdform_column=wdform_row.parentNode;
	wdform_section=wdform_column.parentNode;
	if(wdform_column.nextSibling!=null)
	{
		wdform_column_next=wdform_column.nextSibling;
		wdform_column_next.appendChild(wdform_row);
	}
	else
	{
	    var wdform_column_new = document.createElement('div');
			wdform_column_new.setAttribute("class", "wdform_column");

	    wdform_section.appendChild(wdform_column_new);
	
	    wdform_column_new.appendChild(wdform_row);
	
	    
	}
//	if(wdform_column.firstChild==null)
//		wdform_section.removeChild(wdform_column);	
	
	
	sortable_columns();
}

function left_row(id) {
  if (typeof event != "undefined") {
    event.stopPropagation();
  }
	wdform_field=document.getElementById("wdform_field"+id);
	wdform_row=wdform_field.parentNode;
	wdform_column=wdform_row.parentNode;
	wdform_section=wdform_column.parentNode;
	if(wdform_column.previousSibling!=null)
	{
		wdform_column_next=wdform_column.previousSibling;
		wdform_column_next.appendChild(wdform_row);
	
	}

//	if(wdform_column.firstChild==null)
//		wdform_section.removeChild(wdform_column);
	
	sortable_columns();
}

function page_up(id) {
  if (typeof event != "undefined") {
    event.stopPropagation();
  }
  wdform_field = document.getElementById("wdform_field" + id);
  wdform_row = wdform_field.parentNode;
  wdform_column = wdform_row.parentNode;
  wdform_section = wdform_column.parentNode;
  wdform_page = wdform_section.parentNode;
  wdform_page_and_images = wdform_page.parentNode;

  while (wdform_page_and_images) {
    wdform_page_and_images = wdform_page_and_images.previousSibling;
    if (!wdform_page_and_images) {
      alert('Unable to move');
      return;
    }
    if (jQuery(wdform_page_and_images.firstChild).is(":visible")) {
      break;
    }
  }
  n = wdform_page_and_images.getElementsByClassName("wdform_page")[0].childNodes.length;
  wdform_page_and_images.getElementsByClassName("wdform_page")[0].childNodes[n - 2].firstChild.appendChild(wdform_row);
  refresh_pages(id);
}

function page_down(id) {
  if (typeof event != "undefined") {
    event.stopPropagation();
  }
  wdform_field = document.getElementById("wdform_field" + id);
  wdform_row = wdform_field.parentNode;
  wdform_column = wdform_row.parentNode;
  wdform_section = wdform_column.parentNode;
  wdform_page = wdform_section.parentNode;
  wdform_page_and_images = wdform_page.parentNode;

  while (wdform_page_and_images) {
    wdform_page_and_images = wdform_page_and_images.nextSibling;

    if (!wdform_page_and_images || wdform_page_and_images.id == 'add_field_cont') {
      alert('Unable to move');
      return;
    }

    if (jQuery(wdform_page_and_images.firstChild).is(":visible")) {
      break;
    }
  }

  wdform_page_and_images.getElementsByClassName("wdform_page")[0].firstChild.firstChild.insertBefore(wdform_row, wdform_page_and_images.firstChild.firstChild.firstChild.firstChild);
  refresh_pages(id);
}

function remove_whitespace(node)
{
var ttt;
	for (ttt=0; ttt < node.childNodes.length; ttt++)
	{
        if( node.childNodes[ttt] && node.childNodes[ttt].nodeType == '3' && !/\S/.test(  node.childNodes[ttt].nodeValue ))
		{
			
			node.removeChild(node.childNodes[ttt]);
			 ttt--;
		}
		else
		{
			if(node.childNodes[ttt].childNodes.length)
				remove_whitespace(node.childNodes[ttt]);
		}
	}
	return
}

function Disable()
{	
	//select_=document.getElementById('sel_el_pos');
	//select_.setAttribute("disabled", "disabled");
	//select_.innerHTML="";
}

function all_labels()
{
	labels=new Array();
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
							if(wdform_row.nodeType==3)
								continue;
							wdid=wdform_row.getAttribute("wdid");
							if(!wdid)
								continue;

							labels.push( document.getElementById( wdid+'_element_labelform_id_temp').innerHTML);
						}
					}
				}
			}
		}
	
	return labels;
}

function set_checked(id,j) {
	checking = document.getElementById(id+"_elementform_id_temp"+j);

	jQuery(document).off('change').on('change', '#show_table input[type="checkbox"]', function() {
		limitOfChoice = document.getElementById(id + "_limitchoice_numform_id_temp").value;
		limitOfChoiceAlert = document.getElementById(id + "_limitchoicealert_numform_id_temp").value;
		numberOfChecked = document.querySelectorAll('#show_table input[type="checkbox"]:checked').length;
		if ( limitOfChoice !="" && numberOfChecked > limitOfChoice ) {
			this.checked = false;
			alert(limitOfChoiceAlert);
		}
	});

	if ( checking.checked ) {
		checking.setAttribute("checked", "checked");
	}
	if( !checking.checked ) {
		checking.removeAttribute("checked");
		if( checking.getAttribute('other') )
			if( checking.getAttribute('other')==1 )
			{
				if( document.getElementById(id+"_other_inputform_id_temp") )
				{
					document.getElementById(id+"_other_inputform_id_temp").parentNode.removeChild(document.getElementById(id+"_other_brform_id_temp"));
					document.getElementById(id+"_other_inputform_id_temp").parentNode.removeChild(document.getElementById(id+"_other_inputform_id_temp"));
				}
				return false;
			}
	}
	return true;
}

function set_default(id, j)
{
	for(k=0; k<100; k++)
		if(document.getElementById(id+"_elementform_id_temp"+k))
			if(!document.getElementById(id+"_elementform_id_temp"+k).checked)
				document.getElementById(id+"_elementform_id_temp"+k).removeAttribute("checked");
			else
				document.getElementById(id+"_elementform_id_temp"+j).setAttribute("checked", "checked");
	
	if(document.getElementById(id+"_other_inputform_id_temp"))
	{
		document.getElementById(id+"_other_inputform_id_temp").parentNode.removeChild(document.getElementById(id+"_other_brform_id_temp"));
		document.getElementById(id+"_other_inputform_id_temp").parentNode.removeChild(document.getElementById(id+"_other_inputform_id_temp"));
	}
}

function set_select(select_)
{
	for (p = select_.length - 1; p>=0; p--) 
	    if (select_.options[p].selected) 
		select_.options[p].setAttribute("selected", "selected");
	    else
  		select_.options[p].removeAttribute("selected");
}

function add_0(id)
{
	input=document.getElementById(id);
	if(input.value.length==1)
	{
		input.value='0'+input.value;
		input.setAttribute("value", input.value);
	}
}

function label_top_stripe(num) {
	if(document.getElementById(num+'_label_sectionform_id_temp') && document.getElementById(num+'_element_sectionform_id_temp')){
		if (document.getElementById(num + '_hide_labelform_id_temp').value == "no") {
			document.getElementById(num+'_label_sectionform_id_temp').style.display = "block";
			document.getElementById(num+'_element_sectionform_id_temp').style.display = "block";
		}
		else {
			document.getElementById(num+'_label_sectionform_id_temp').style.display = "none";
			document.getElementById(num+'_element_sectionform_id_temp').style.display = "block";
		}
	}
}
function label_left_stripe(num) {
	if(document.getElementById(num+'_label_sectionform_id_temp') && document.getElementById(num + '_element_sectionform_id_temp')) {
		if (document.getElementById(num + '_hide_labelform_id_temp').value == "no") {
				document.getElementById(num + '_label_sectionform_id_temp').style.display = "table-cell";
				document.getElementById(num + '_element_sectionform_id_temp').style.display = "table-cell";
		}
		else {
			document.getElementById(num + '_label_sectionform_id_temp').style.display = "none";
			document.getElementById(num + '_element_sectionform_id_temp').style.display = "table-cell";
		}
	}
}

function change_value_range(id, min_max, element_value)
{
 jQuery("#"+id).datepicker('option', min_max, element_value);
}

function change_func(id, label) {
	document.getElementById(id).setAttribute("onclick", label);
}

function change_in_value(id, label) {
  label = label.replace(/(<([^>]+)>)/ig, "");
  label = label.replace(/"/g, "&quot;");
	document.getElementById(id).setAttribute("value", label);
}

function change_size(size, num) {
	document.getElementById(num+'_elementform_id_temp').style.width=size+'px';
	if (document.getElementById(num+'_element_input')) {
		document.getElementById(num+'_element_input').style.width=size+'px';
  }
	switch(size) {
		case '111':
		{
			document.getElementById(num+'_elementform_id_temp').setAttribute("rows", "2"); break;
		}
		case '222':
		{
			document.getElementById(num+'_elementform_id_temp').setAttribute("rows", "4");break;
		}
		case '444':
		{
			document.getElementById(num+'_elementform_id_temp').setAttribute("rows", "8");break;
		}
	}
}

function getIFrameDocument(aID){ 
var rv = null; 
// if contentDocument exists, W3C compliant (Mozilla) 
if (document.getElementById(aID) && document.getElementById(aID).contentDocument){
rv = document.getElementById(aID).contentDocument; 
} else if (document.getElementById(aID)) {
// IE 
rv = document.frames[aID].document; 
} 
return rv; 
}

function format_extended(num,w_title_value,w_middle_value,w_title_title,w_middle_title)
{
	w_size=document.getElementById(num+'_element_firstform_id_temp').style.width;
    	tr_name1 = document.getElementById(num+'_tr_name1');
    	tr_name2 = document.getElementById(num+'_tr_name2');
	
   	var td_name_input1 = document.createElement('div');
        	td_name_input1.setAttribute("id", num+"_td_name_input_title");
			td_name_input1.style.display='table-cell';
		
   	var td_name_input4 = document.createElement('div');
        	td_name_input4.setAttribute("id", num+"_td_name_input_middle");
			td_name_input4.style.display='table-cell';
		
   	var td_name_label1 = document.createElement('div');
        	td_name_label1.setAttribute("id", num+"_td_name_label_title");
        	td_name_label1.setAttribute("align", "left");
			td_name_label1.style.display='table-cell';
		
   	var td_name_label4 = document.createElement('div');
        	td_name_label4.setAttribute("id", num+"_td_name_label_middle");
        	td_name_label4.setAttribute("align", "left");
			td_name_label4.style.display='table-cell';
		
	var title = document.createElement('input');
        title.setAttribute("type", 'text');
		
		
		
	    title.style.cssText = "margin: 0px 10px 0px 0px; padding: 0px; width:40px";
	    title.setAttribute("id", num+"_element_titleform_id_temp");
	    title.setAttribute("name", num+"_element_titleform_id_temp");
		if(w_title_value==w_title_title)
		{
		title.setAttribute("value", w_title_title);
		}
		else
		{
		title.setAttribute("value", w_title_value);
		}
		title.setAttribute("title", w_title_title);

	var title_label = document.createElement('label');
	    title_label.setAttribute("class", "mini_label");
	    title_label.setAttribute("id", num+"_mini_label_title");
	    title_label.innerHTML= w_mini_labels[0];
		
	var middle = document.createElement('input');
		middle.setAttribute("type", 'text');
		middle.style.cssText = "padding: 0px; width:"+w_size;
		middle.setAttribute("id", num+"_element_middleform_id_temp");
		middle.setAttribute("name", num+"_element_middleform_id_temp");
		if(w_middle_value==w_middle_title)
		{
		middle.setAttribute("value", w_middle_title);
		}
		else
		{
		middle.setAttribute("value", w_middle_value);
		}
		middle.setAttribute("title", w_middle_title);

	var middle_label = document.createElement('label');
		middle_label.setAttribute("class", "mini_label");
		middle_label.setAttribute("id", num+"_mini_label_middle");
		middle_label.innerHTML=w_mini_labels[3];
		
    	first_input = document.getElementById(num+'_td_name_input_first');
    	last_input = document.getElementById(num+'_td_name_input_last');
    	first_label = document.getElementById(num+'_td_name_label_first');
    	last_label = document.getElementById(num+'_td_name_label_last');
	
      	td_name_input1.appendChild(title);
      	td_name_input4.appendChild(middle);
		
		tr_name1.insertBefore(td_name_input1, first_input);
		tr_name1.insertBefore(td_name_input4, null);
		
      	td_name_label1.appendChild(title_label);
      	td_name_label4.appendChild(middle_label);
		tr_name2.insertBefore(td_name_label1, first_label);
		tr_name2.insertBefore(td_name_label4, null);
		
	var gic1 = document.createTextNode("-");
	var gic2 = document.createTextNode("-");

	var el_first_value_title = document.createElement('input');
                el_first_value_title.setAttribute("id", "el_first_value_title");
                el_first_value_title.setAttribute("type", "text");
                el_first_value_title.setAttribute("value", w_title_title);
                el_first_value_title.style.cssText = "width:50px; margin-left:4px; margin-right:4px";
                el_first_value_title.setAttribute("onKeyUp", "change_input_value(this.value,'"+num+"_element_titleform_id_temp')");

	var el_first_value_middle = document.createElement('input');
                el_first_value_middle.setAttribute("id", "el_first_value_middle");
                el_first_value_middle.setAttribute("type", "text");
                el_first_value_middle.setAttribute("value", w_middle_title);
                el_first_value_middle.style.cssText = "width:100px; margin-left:4px";
                el_first_value_middle.setAttribute("onKeyUp", "change_input_value(this.value,'"+num+"_element_middleform_id_temp')");
				
    el_first_value_first = document.getElementById('el_first_value_first');
	parent=el_first_value_first.parentNode;
	parent.insertBefore(gic1, el_first_value_first);
	parent.insertBefore(el_first_value_title, gic1);
    parent.appendChild(gic2);
    parent.appendChild(el_first_value_middle);
		
refresh_attr(num, 'type_name');
refresh_id_name(num, 'type_name');

jQuery(function() {
	jQuery("label#"+num+"_mini_label_title").on("click", function() {		
		if (jQuery(this).children('input').length == 0) {				
			var title = "<input type='text' class='title' size='10' style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";	
				jQuery(this).html(title);							
				jQuery("input.title").focus();			
				jQuery("input.title").blur(function() {	
			var value = jQuery(this).val();			


		jQuery("#"+num+"_mini_label_title").text(value);		
		});	
	}	
	});		


	jQuery("label#"+num+"_mini_label_middle").on("click", function() {	
	if (jQuery(this).children('input').length == 0) {		
		var middle = "<input type='text' class='middle'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";	
			jQuery(this).html(middle);			
			jQuery("input.middle").focus();					
			jQuery("input.middle").blur(function() {			
			var value = jQuery(this).val();			
			
			jQuery("#"+num+"_mini_label_middle").text(value);	
		});	
	}	
	});
	});	


}

function format_normal(num)
{
    	tr_name1 = document.getElementById(num+'_tr_name1');
    	tr_name2 = document.getElementById(num+'_tr_name2');
   	 	td_name_input1 = document.getElementById(num+'_td_name_input_title');
		
   		td_name_input4 = document.getElementById(num+'_td_name_input_middle');
		
   		td_name_label1 = document.getElementById(num+'_td_name_label_title');
		
   	 	td_name_label4 =document.getElementById(num+'_td_name_label_middle');
		
		tr_name1.removeChild(td_name_input1);
		tr_name1.removeChild(td_name_input4);
		tr_name2.removeChild(td_name_label1);
		tr_name2.removeChild(td_name_label4);
		
		el_first_value_first = document.getElementById('el_first_value_first');
		parent=el_first_value_first.parentNode;
		
		parent.removeChild( document.getElementById('el_first_value_title').nextSibling);
		parent.removeChild( document.getElementById('el_first_value_title'));
		parent.removeChild( document.getElementById('el_first_value_middle').previousSibling);
		parent.removeChild( document.getElementById('el_first_value_middle'));
refresh_attr(num, 'type_name');
refresh_id_name(num, 'type_name');

}

function type_number(i, w_field_label, w_field_label_size, w_field_label_pos, w_size, w_first_val, w_title, w_required, w_unique, w_class, w_attr_name, w_attr_value) {

    document.getElementById("element_type").value="type_number";
	delete_last_child();
	
	var edit_div  = document.createElement('div');
		edit_div.setAttribute("id", "edit_div");
		
		
	var edit_main_table  = document.createElement('table');
		edit_main_table.setAttribute("id", "edit_main_table");
		edit_main_table.setAttribute("cellpadding", "3");
		edit_main_table.setAttribute("cellspacing", "0");
		
	var edit_main_tr1  = document.createElement('tr');
	var edit_main_tr2  = document.createElement('tr');
	var edit_main_tr3  = document.createElement('tr');
	var edit_main_tr4  = document.createElement('tr');
	var edit_main_tr5  = document.createElement('tr');
	var edit_main_tr6  = document.createElement('tr');
	var edit_main_tr7  = document.createElement('tr');
	var edit_main_tr8  = document.createElement('tr');
	var edit_main_tr9  = document.createElement('tr');
			
	var edit_main_td1 = document.createElement('td');
	var edit_main_td1_1 = document.createElement('td');
	
	var edit_main_td2 = document.createElement('td');
	var edit_main_td2_1 = document.createElement('td');
	var edit_main_td3 = document.createElement('td');
	var edit_main_td3_1 = document.createElement('td');
	
	var edit_main_td4 = document.createElement('td');
	var edit_main_td4_1 = document.createElement('td');
	var edit_main_td5 = document.createElement('td');
	var edit_main_td5_1 = document.createElement('td');
	var edit_main_td6 = document.createElement('td');
	var edit_main_td6_1 = document.createElement('td');
		
	var edit_main_td7 = document.createElement('td');
	var edit_main_td7_1 = document.createElement('td');
	var edit_main_td8 = document.createElement('td');
	var edit_main_td8_1 = document.createElement('td');
	var edit_main_td9 = document.createElement('td');
	var edit_main_td9_1 = document.createElement('td');
	
	var el_label_label = document.createElement('label');
		el_label_label.setAttribute("class", "fm-field-label");
	    el_label_label.setAttribute("for", "edit_for_label");
		el_label_label.innerHTML = "Field label";
	
	var el_label_textarea = document.createElement('textarea');
                el_label_textarea.setAttribute("id", "edit_for_label");
                el_label_textarea.setAttribute("rows", "4");
                
                el_label_textarea.setAttribute("onKeyUp", "change_label('"+i+"_element_labelform_id_temp', this.value)");
		el_label_textarea.innerHTML = w_field_label;
	

	var el_label_size_label = document.createElement('label');
		el_label_size_label.setAttribute("class", "fm-field-label");
	    el_label_size_label.setAttribute("for", "edit_for_label_size");
		el_label_size_label.innerHTML = "Field label size(px) ";
		
	var el_label_size = document.createElement('input');
	    el_label_size.setAttribute("id", "edit_for_label_size");
	    el_label_size.setAttribute("type", "text");
	    el_label_size.setAttribute("value", w_field_label_size);
		el_label_size.setAttribute("onKeyPress", "return check_isnum(event)");
        el_label_size.setAttribute("onKeyUp", "change_w_style('"+i+"_label_sectionform_id_temp', this.value)");
	
	var el_label_position_label = document.createElement('label');
		el_label_position_label.setAttribute("class", "fm-field-label");
		el_label_position_label.innerHTML = "Field label position";

	var el_label_position1 = document.createElement('input');
		el_label_position1.setAttribute("id", "edit_for_label_position_top");
		el_label_position1.setAttribute("type", "radio");
		el_label_position1.setAttribute("name", "edit_for_label_position");
		el_label_position1.setAttribute("onchange", "label_left("+i+")");

	var el_label_left = document.createElement('label');
		el_label_left.setAttribute("for", "edit_for_label_position_top");
		el_label_left.innerHTML = "Left";	

	var el_label_position2 = document.createElement('input');
		el_label_position2.setAttribute("id", "edit_for_label_position_left");
		el_label_position2.setAttribute("type", "radio");
		el_label_position2.setAttribute("name", "edit_for_label_position");
		el_label_position2.setAttribute("onchange", "label_top("+i+")");

	var el_label_top = document.createElement('label');
		el_label_top.setAttribute("for", "edit_for_label_position_left");
		el_label_top.innerHTML = "Top";
		
	if(w_field_label_pos=="top")
		el_label_position2.setAttribute("checked", "checked");
	else
		el_label_position1.setAttribute("checked", "checked");

	var el_size_label = document.createElement('label');
		el_size_label.setAttribute("class", "fm-field-label");
	    el_size_label.setAttribute("for", "edit_for_input_size");
		el_size_label.innerHTML = "Field size(px) ";
	var el_size = document.createElement('input');
		el_size.setAttribute("id", "edit_for_input_size");
		el_size.setAttribute("type", "text");
		el_size.setAttribute("value", w_size);
		el_size.setAttribute("onKeyPress", "return check_isnum(event)");
        el_size.setAttribute("onKeyUp", "change_w_style('"+i+"_elementform_id_temp', this.value)");

	var el_first_value_label = document.createElement('label');
		el_first_value_label.setAttribute("class", "fm-field-label");
		el_first_value_label.setAttribute("for", "el_first_value_input");
		el_first_value_label.innerHTML = "Placeholder ";
	
	var el_first_value_input = document.createElement('input');
        el_first_value_input.setAttribute("id", "el_first_value_input");
		el_first_value_input.setAttribute("type", "text");
		el_first_value_input.setAttribute("value", w_title);
		el_first_value_input.setAttribute("onKeyUp", "change_input_value(this.value,'"+i+"_elementform_id_temp');");
		
	var el_required_label = document.createElement('label');
		el_required_label.setAttribute("class", "fm-field-label");
	    el_required_label.setAttribute("for", "el_required");
		el_required_label.innerHTML = "Required";
	
	var el_required = document.createElement('input');
		el_required.setAttribute("id", "el_required");
		el_required.setAttribute("type", "checkbox");
		el_required.setAttribute("onclick", "set_required('"+i+"_required')");
	if(w_required=="yes")
		el_required.setAttribute("checked", "checked");
	
	var el_unique_label = document.createElement('label');
		el_unique_label.setAttribute("class", "fm-field-label");
	    el_unique_label.setAttribute("for", "el_unique");
		el_unique_label.innerHTML = "Allow only unique values";
	
	var el_unique = document.createElement('input');
		el_unique.setAttribute("id", "el_unique");
		el_unique.setAttribute("type", "checkbox");
		el_unique.setAttribute("onclick", "set_unique('"+i+"_uniqueform_id_temp')");
	if(w_unique=="yes")
        el_unique.setAttribute("checked", "checked");
							
	var el_style_label = document.createElement('label');
		el_style_label.setAttribute("class", "fm-field-label");
	    el_style_label.setAttribute("for", "el_style_textarea");
		el_style_label.innerHTML = "Class name";

	var el_style_textarea = document.createElement('input');
        el_style_textarea.setAttribute("id", "el_style_textarea");
		el_style_textarea.setAttribute("type", "text");
		el_style_textarea.setAttribute("value", w_class);
        
        el_style_textarea.setAttribute("onChange", "change_class(this.value,'"+i+"')");

	var el_attr_label = document.createElement('label');
		el_attr_label.setAttribute("class", "fm-field-label");
		el_attr_label.innerHTML = "Additional Attributes";
			
	var el_attr_add = document.createElement('img');
      	el_attr_add.setAttribute("src", plugin_url + '/images/add.png?ver=1.8.0');
        el_attr_add.style.cssText = 'cursor:pointer; margin-left:68px';
        el_attr_add.setAttribute("title", 'add');
        el_attr_add.setAttribute("onClick", "add_attr("+i+", 'type_text')");
		
	var el_attr_table = document.createElement('table');
        el_attr_table.setAttribute("id", 'attributes');
        el_attr_table.setAttribute("border", '0');
        el_attr_table.style.cssText = 'margin-left:0px';
		
	var el_attr_tr_label = document.createElement('tr');
        el_attr_tr_label.setAttribute("idi", '0');
	var el_attr_td_name_label = document.createElement('th');
        el_attr_td_name_label.style.cssText = 'width:100px';
	var el_attr_td_value_label = document.createElement('th');
        el_attr_td_value_label.style.cssText = 'width:100px';
	var el_attr_td_X_label = document.createElement('th');
        el_attr_td_X_label.style.cssText = 'width:10px';
	var el_attr_name_label = document.createElement('label');
	    el_attr_name_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
		el_attr_name_label.innerHTML = "Name";
	var el_attr_value_label = document.createElement('label');
	    el_attr_value_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
		el_attr_value_label.innerHTML = "Value";
			
	el_attr_table.appendChild(el_attr_tr_label);
	el_attr_tr_label.appendChild(el_attr_td_name_label);
	el_attr_tr_label.appendChild(el_attr_td_value_label);
	el_attr_tr_label.appendChild(el_attr_td_X_label);
	el_attr_td_name_label.appendChild(el_attr_name_label);
	el_attr_td_value_label.appendChild(el_attr_value_label);
	
	n=w_attr_name.length;
	for(j=1; j<=n; j++)
	{	
		var el_attr_tr = document.createElement('tr');
			el_attr_tr.setAttribute("id", "attr_row_"+j);
			el_attr_tr.setAttribute("idi", j);
		var el_attr_td_name = document.createElement('td');
			el_attr_td_name.style.cssText = 'width:100px';
		var el_attr_td_value = document.createElement('td');
			el_attr_td_value.style.cssText = 'width:100px';
		
		var el_attr_td_X = document.createElement('td');
		var el_attr_name = document.createElement('input');
			el_attr_name.setAttribute("type", "text");
			el_attr_name.setAttribute("class", "fm-field-choice");
			el_attr_name.setAttribute("value", w_attr_name[j-1]);
			el_attr_name.setAttribute("id", "attr_name"+j);
			el_attr_name.setAttribute("onChange", "change_attribute_name("+i+", this, 'type_text')");
			
		var el_attr_value = document.createElement('input');
			el_attr_value.setAttribute("type", "text");
			el_attr_value.setAttribute("class", "fm-field-choice");
			el_attr_value.setAttribute("value", w_attr_value[j-1]);
			el_attr_value.setAttribute("id", "attr_value"+j);
			el_attr_value.setAttribute("onChange", "change_attribute_value("+i+", "+j+", 'type_text')");
	
		var el_attr_remove = document.createElement('img');
			el_attr_remove.setAttribute("id", "el_choices"+j+"_remove");
			el_attr_remove.setAttribute("src", plugin_url + '/images/delete.png?ver=1.8.0');
			el_attr_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:2px';
			el_attr_remove.setAttribute("onClick", "remove_attr("+j+", "+i+", 'type_text')");
	
		el_attr_table.appendChild(el_attr_tr);
		el_attr_tr.appendChild(el_attr_td_name);
		el_attr_tr.appendChild(el_attr_td_value);
		el_attr_tr.appendChild(el_attr_td_X);
		el_attr_td_name.appendChild(el_attr_name);
		el_attr_td_value.appendChild(el_attr_value);
		el_attr_td_X.appendChild(el_attr_remove);
		
	}

	var t  = document.getElementById('edit_table');
	
	var br = document.createElement('br');
	var br6 = document.createElement('br');
	
	edit_main_td1.appendChild(el_label_label);
	edit_main_td1_1.appendChild(el_label_textarea);

	edit_main_td9.appendChild(el_label_size_label);
	edit_main_td9_1.appendChild(el_label_size);
	
	edit_main_td2.appendChild(el_label_position_label);
	edit_main_td2_1.appendChild(el_label_position1);
	edit_main_td2_1.appendChild(el_label_left);
	edit_main_td2_1.appendChild(br);
	edit_main_td2_1.appendChild(el_label_position2);
	edit_main_td2_1.appendChild(el_label_top);
	
	edit_main_td3.appendChild(el_size_label);
	edit_main_td3_1.appendChild(el_size);
	
	edit_main_td4.appendChild(el_first_value_label);
	edit_main_td4_1.appendChild(el_first_value_input);
	
	edit_main_td5.appendChild(el_style_label);
	edit_main_td5_1.appendChild(el_style_textarea);
	edit_main_td6.appendChild(el_required_label);
	edit_main_td6_1.appendChild(el_required);
				
	edit_main_td8.appendChild(el_unique_label);
	edit_main_td8_1.appendChild(el_unique);
	
	
	
	
	edit_main_td7.appendChild(el_attr_label);
	edit_main_td7.appendChild(el_attr_add);
	edit_main_td7.appendChild(br6);
	edit_main_td7.appendChild(el_attr_table);
	edit_main_td7.setAttribute("colspan", "2");

	edit_main_tr1.appendChild(edit_main_td1);
	edit_main_tr1.appendChild(edit_main_td1_1);
	edit_main_tr9.appendChild(edit_main_td9);
	edit_main_tr9.appendChild(edit_main_td9_1);
	edit_main_tr2.appendChild(edit_main_td2);
	edit_main_tr2.appendChild(edit_main_td2_1);
	edit_main_tr3.appendChild(edit_main_td3);
	edit_main_tr3.appendChild(edit_main_td3_1);
	edit_main_tr4.appendChild(edit_main_td4);
	edit_main_tr4.appendChild(edit_main_td4_1);
	edit_main_tr5.appendChild(edit_main_td5);
	edit_main_tr5.appendChild(edit_main_td5_1);
	edit_main_tr6.appendChild(edit_main_td6);
	edit_main_tr6.appendChild(edit_main_td6_1);
	edit_main_tr8.appendChild(edit_main_td8);
	edit_main_tr8.appendChild(edit_main_td8_1);
	edit_main_tr7.appendChild(edit_main_td7);
	edit_main_table.appendChild(edit_main_tr1);
	edit_main_table.appendChild(edit_main_tr9);
	edit_main_table.appendChild(edit_main_tr2);
	edit_main_table.appendChild(edit_main_tr3);
	edit_main_table.appendChild(edit_main_tr4);
	edit_main_table.appendChild(edit_main_tr5);
	edit_main_table.appendChild(edit_main_tr6);
	edit_main_table.appendChild(edit_main_tr8);
	edit_main_table.appendChild(edit_main_tr7);
	edit_div.appendChild(edit_main_table);
	
	t.appendChild(edit_div);
	add_id_and_name(i, 'type_text');
	
//show table

	element='input';	type='text'; 
	var adding_type = document.createElement("input");
            adding_type.setAttribute("type", "hidden");
            adding_type.setAttribute("value", "type_number");
            adding_type.setAttribute("name", i+"_typeform_id_temp");
            adding_type.setAttribute("id", i+"_typeform_id_temp");
	    
	var adding_required= document.createElement("input");
            adding_required.setAttribute("type", "hidden");
            adding_required.setAttribute("value", w_required);
            adding_required.setAttribute("name", i+"_requiredform_id_temp");
            adding_required.setAttribute("id", i+"_requiredform_id_temp");
			
	var adding_unique= document.createElement("input");
            adding_unique.setAttribute("type", "hidden");
            adding_unique.setAttribute("value", w_unique);
            adding_unique.setAttribute("name", i+"_uniqueform_id_temp");
            adding_unique.setAttribute("id", i+"_uniqueform_id_temp");
			
			
	var adding = document.createElement(element);
			adding.setAttribute("type", type);
		
		if(w_title==w_first_val)
		{
			adding.style.cssText = "width:"+w_size+"px;";
		}
		else
		{
			adding.style.cssText = "width:"+w_size+"px;";
		}
			adding.setAttribute("id", i+"_elementform_id_temp");
			adding.setAttribute("name", i+"_elementform_id_temp");
			adding.setAttribute("value", w_first_val);
			adding.setAttribute("title", w_title);
			adding.setAttribute("placeholder", w_title);
			adding.setAttribute("onKeyPress", "return check_isnum(event)");

     	var div = document.createElement('div');
      	    div.setAttribute("id", "main_div");
					
      	var div_field = document.createElement('div');
           	div_field.setAttribute("id", i+"_elemet_tableform_id_temp");
						
      	var div_label = document.createElement('div');
         	div_label.setAttribute("align", 'left');
         	div_label.style.display="table-cell";
			div_label.style.width=w_field_label_size+"px";
           	div_label.setAttribute("id", i+"_label_sectionform_id_temp");
			
      	var div_element = document.createElement('div');
         	div_element.setAttribute("align", 'left');
          	div_element.style.display="table-cell";
          	div_element.setAttribute("id", i+"_element_sectionform_id_temp");
			
      	var label = document.createElement('span');
			label.setAttribute("id", i+"_element_labelform_id_temp");
			label.innerHTML = w_field_label;
			label.setAttribute("class", "label");
			label.style.verticalAlign="top";
	    
      	var required = document.createElement('span');
			required.setAttribute("id", i+"_required_elementform_id_temp");
			required.innerHTML = "";
			required.setAttribute("class", "required");
			required.style.verticalAlign="top";
	if(w_required=="yes")
			required.innerHTML = " *";
      	var main_td  = document.getElementById('show_table');
      
      	div_label.appendChild(label);
      	div_label.appendChild(required);
      	div_element.appendChild(adding_type);
      	div_element.appendChild(adding_required);
      	div_element.appendChild(adding_unique);
      	div_element.appendChild(adding);
      	div_field.appendChild(div_label);
      	div_field.appendChild(div_element);
      	
      
      	div.appendChild(div_field);
      	main_td.appendChild(div);
		
		if(w_field_label_pos=="top")
			label_top(i);
			
		change_class(w_class, i);
		refresh_attr(i, 'type_text');
}

function type_wdeditor(i, w_field_label,  w_field_label_size, w_field_label_pos, w_size_w, w_size_h, w_title, w_required, w_class, w_attr_name, w_attr_value){
	document.getElementById("element_type").value="type_wdeditor";

	delete_last_child();
// edit table	
	var edit_div  = document.createElement('div');
		edit_div.setAttribute("id", "edit_div");
		
		
	var edit_main_table  = document.createElement('table');
		edit_main_table.setAttribute("id", "edit_main_table");
		edit_main_table.setAttribute("cellpadding", "3");
		edit_main_table.setAttribute("cellspacing", "0");
		
	var edit_main_tr1  = document.createElement('tr');
      	var edit_main_tr2  = document.createElement('tr');

	var edit_main_tr3  = document.createElement('tr');
  	var edit_main_tr4  = document.createElement('tr');
	
	var edit_main_tr5  = document.createElement('tr');
	var edit_main_tr6  = document.createElement('tr');

	var edit_main_tr7  = document.createElement('tr');


	var edit_main_tr8  = document.createElement('tr');


	var edit_main_td1 = document.createElement('td');
	var edit_main_td1_1 = document.createElement('td');	
	var edit_main_td2 = document.createElement('td');
	var edit_main_td2_1 = document.createElement('td');var edit_main_td3 = document.createElement('td');
	var edit_main_td3_1 = document.createElement('td');
		edit_main_td3_1.style.cssText = "padding-top:10px";
	var edit_main_td4 = document.createElement('td');
	var edit_main_td4_1 = document.createElement('td');
		edit_main_td4_1.style.cssText = "padding-top:10px";
		
	var edit_main_td5 = document.createElement('td');
	var edit_main_td5_1 = document.createElement('td');
		edit_main_td5_1.style.cssText = "padding-top:10px";
				
	var edit_main_td6 = document.createElement('td');
	var edit_main_td6_1 = document.createElement('td');
		edit_main_td6_1.style.cssText = "padding-top:10px";

	var edit_main_td7 = document.createElement('td');
	var edit_main_td7_1 = document.createElement('td');
		  
	var edit_main_td8 = document.createElement('td');
	var edit_main_td8_1 = document.createElement('td');
		  
	var el_label_label = document.createElement('label');
			        el_label_label.setAttribute("class", "fm-field-label");
		el_label_label.setAttribute("for", "edit_for_label");
			el_label_label.innerHTML = "Field label";
	
	var el_label_textarea = document.createElement('textarea');
                el_label_textarea.setAttribute("id", "edit_for_label");
                el_label_textarea.setAttribute("rows", "4");
                
                el_label_textarea.setAttribute("onKeyUp", "change_label('"+i+"_element_labelform_id_temp', this.value)");
				el_label_textarea.innerHTML = w_field_label;
	
	var el_label_size_label = document.createElement('label');
		el_label_size_label.setAttribute("class", "fm-field-label");
	    el_label_size_label.setAttribute("for", "edit_for_label_size");
		el_label_size_label.innerHTML = "Field label size(px) ";
		
	var el_label_size = document.createElement('input');
	    el_label_size.setAttribute("id", "edit_for_label_size");
	    el_label_size.setAttribute("type", "text");
	    el_label_size.setAttribute("value", w_field_label_size);
		el_label_size.setAttribute("onKeyPress", "return check_isnum(event)");
        el_label_size.setAttribute("onKeyUp", "change_w_style('"+i+"_label_sectionform_id_temp', this.value)");
	
	var el_label_position_label = document.createElement('label');
			        el_label_position_label.setAttribute("class", "fm-field-label");
	    el_label_position_label.setAttribute("for", "edit_for_label_position_top");
		el_label_position_label.innerHTML = "Field label position";
	
	var el_label_position1 = document.createElement('input');
                el_label_position1.setAttribute("id", "edit_for_label_position_top");
                el_label_position1.setAttribute("type", "radio");
                el_label_position1.setAttribute("value", "left");
                

                el_label_position1.setAttribute("name", "edit_for_label_position");
                el_label_position1.setAttribute("onchange", "label_left("+i+")");
		Left = document.createTextNode("Left");
		
	var el_label_position2 = document.createElement('input');
                el_label_position2.setAttribute("id", "edit_for_label_position_left");
                el_label_position2.setAttribute("type", "radio");
                el_label_position2.setAttribute("value", "top");
	

                el_label_position2.setAttribute("name", "edit_for_label_position");
                el_label_position2.setAttribute("onchange", "label_top("+i+")");
		Top = document.createTextNode("Top");
		
	if(w_field_label_pos=="top")
				el_label_position2.setAttribute("checked", "checked");
	else
				el_label_position1.setAttribute("checked", "checked");

	var el_size_label = document.createElement('label');
	        el_size_label.style.cssText ="color:#000; font-weight:bold; font-size: 13px";
		el_size_label.innerHTML = "Field size(px) ";
		
	var el_size_w = document.createElement('input');
		   el_size_w.setAttribute("id", "edit_for_input_size");
		   el_size_w.setAttribute("type", "text");
		   el_size_w.setAttribute("value", w_size_w);
		   el_size_w.style.cssText = "margin-right:2px; width: 60px";
		   el_size_w.setAttribute("name", "edit_for_size");
		   el_size_w.setAttribute("onKeyPress", "return check_isnum(event)");
           el_size_w.setAttribute("onKeyUp", "change_w_style('"+i+"_elementform_id_temp', this.value)");
		   
		X = document.createTextNode("x");
		
	var el_size_h = document.createElement('input');
		   el_size_h.setAttribute("id", "edit_for_input_size");
		   el_size_h.setAttribute("type", "text");
		   el_size_h.setAttribute("value", w_size_h);
		   el_size_h.style.cssText = "margin-left:2px;  width:60px";
			el_size_h.setAttribute("name", "edit_for_size");
			el_size_h.setAttribute("onKeyPress", "return check_isnum(event)");
            el_size_h.setAttribute("onKeyUp", "change_h_style('"+i+"_elementform_id_temp', this.value)");
			
	var el_first_value_label = document.createElement('label');
	        el_first_value_label.style.cssText ="color:#000; font-weight:bold; font-size: 13px";
		el_first_value_label.innerHTML = "Placeholder";
	
	var el_first_value_input = document.createElement('input');
                el_first_value_input.setAttribute("id", "el_first_value_input");
                el_first_value_input.setAttribute("type", "text");
                el_first_value_input.setAttribute("value", w_title);
                
                el_first_value_input.setAttribute("onKeyUp", "change_input_value(this.value,'"+i+"_elementform_id_temp')");
	var el_required_label = document.createElement('label');
	        el_required_label.setAttribute("class", "fm-field-label");
		el_required_label.setAttribute("for", "el_send");
		el_required_label.innerHTML = "Required";
	
	var el_required = document.createElement('input');
                el_required.setAttribute("id", "el_send");
                el_required.setAttribute("type", "checkbox");
                el_required.setAttribute("value", "yes");
                el_required.setAttribute("onclick", "set_required('"+i+"_required')");
	if(w_required=="yes")
			    el_required.setAttribute("checked", "checked");
		

		
	var el_style_label = document.createElement('label');
	        el_style_label.setAttribute("class", "fm-field-label");
		el_style_label.setAttribute("for", "element_style");
			el_style_label.innerHTML = "Class name";
	
	var el_style_textarea = document.createElement('input');
                el_style_textarea.setAttribute("id", "element_style");
				el_style_textarea.setAttribute("type", "text");
				el_style_textarea.setAttribute("value", w_class);
                
                el_style_textarea.setAttribute("onChange", "change_class(this.value,'"+i+"')");

	var el_attr_label = document.createElement('label');
	                el_attr_label.setAttribute("class", "fm-field-label");
		el_attr_label.setAttribute("for", "el_choices_add");
			el_attr_label.innerHTML = "Additional Attributes";
			
	var el_attr_add = document.createElement('img');
                el_attr_add.setAttribute("id", "el_choices_add");
           	el_attr_add.setAttribute("src", plugin_url + '/images/add.png?ver=1.8.0');
            	el_attr_add.style.cssText = 'cursor:pointer; margin-left:68px';
            	el_attr_add.setAttribute("title", 'add');
                el_attr_add.setAttribute("onClick", "add_attr("+i+", 'type_text')");
	var el_attr_table = document.createElement('table');
                el_attr_table.setAttribute("id", 'attributes');
                el_attr_table.setAttribute("border", '0');
        	el_attr_table.style.cssText = 'margin-left:0px';
	var el_attr_tr_label = document.createElement('tr');
                el_attr_tr_label.setAttribute("idi", '0');
	var el_attr_td_name_label = document.createElement('th');
            	el_attr_td_name_label.style.cssText = 'width:100px';
	var el_attr_td_value_label = document.createElement('th');
            	el_attr_td_value_label.style.cssText = 'width:100px';
	var el_attr_td_X_label = document.createElement('th');
            	el_attr_td_X_label.style.cssText = 'width:10px';
	var el_attr_name_label = document.createElement('label');
	                el_attr_name_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
			el_attr_name_label.innerHTML = "Name";
			
	var el_attr_value_label = document.createElement('label');
	                el_attr_value_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
			el_attr_value_label.innerHTML = "Value";
			
	el_attr_table.appendChild(el_attr_tr_label);
	el_attr_tr_label.appendChild(el_attr_td_name_label);
	el_attr_tr_label.appendChild(el_attr_td_value_label);
	el_attr_tr_label.appendChild(el_attr_td_X_label);
	el_attr_td_name_label.appendChild(el_attr_name_label);
	el_attr_td_value_label.appendChild(el_attr_value_label);
	
	n=w_attr_name.length;
	for(j=1; j<=n; j++)
	{	
		var el_attr_tr = document.createElement('tr');
			el_attr_tr.setAttribute("id", "attr_row_"+j);
			el_attr_tr.setAttribute("idi", j);
		var el_attr_td_name = document.createElement('td');
			el_attr_td_name.style.cssText = 'width:100px';
		var el_attr_td_value = document.createElement('td');
			el_attr_td_value.style.cssText = 'width:100px';
		
		var el_attr_td_X = document.createElement('td');
		var el_attr_name = document.createElement('input');
	
			el_attr_name.setAttribute("type", "text");
	
			el_attr_name.setAttribute("class", "fm-field-choice");
			el_attr_name.setAttribute("value", w_attr_name[j-1]);
			el_attr_name.setAttribute("id", "attr_name"+j);
			el_attr_name.setAttribute("onChange", "change_attribute_name("+i+", this, 'type_text')");
			
		var el_attr_value = document.createElement('input');
	
			el_attr_value.setAttribute("type", "text");
	
			el_attr_value.setAttribute("class", "fm-field-choice");
			el_attr_value.setAttribute("value", w_attr_value[j-1]);
			el_attr_value.setAttribute("id", "attr_value"+j);
			el_attr_value.setAttribute("onChange", "change_attribute_value("+i+", "+j+", 'type_text')");
	
		var el_attr_remove = document.createElement('img');
			el_attr_remove.setAttribute("id", "el_choices"+j+"_remove");
			el_attr_remove.setAttribute("src", plugin_url + '/images/delete.png?ver=1.8.0');
			el_attr_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:2px';
			el_attr_remove.setAttribute("align", 'top');
			el_attr_remove.setAttribute("onClick", "remove_attr("+j+", "+i+", 'type_text')");
		el_attr_table.appendChild(el_attr_tr);
		el_attr_tr.appendChild(el_attr_td_name);
		el_attr_tr.appendChild(el_attr_td_value);
		el_attr_tr.appendChild(el_attr_td_X);
		el_attr_td_name.appendChild(el_attr_name);
		el_attr_td_value.appendChild(el_attr_value);
		el_attr_td_X.appendChild(el_attr_remove);
		
	}

	var t  = document.getElementById('edit_table');
	
	var br = document.createElement('br');
	var br1 = document.createElement('br');
	var br2 = document.createElement('br');
	var br3 = document.createElement('br');
	var br4 = document.createElement('br');
	var br5 = document.createElement('br');
	var br6 = document.createElement('br');
	
	edit_main_td1.appendChild(el_label_label);
	edit_main_td1_1.appendChild(el_label_textarea);

	edit_main_td8.appendChild(el_label_size_label);
	edit_main_td8_1.appendChild(el_label_size);
	
	
	edit_main_td2.appendChild(el_label_position_label);
	edit_main_td2_1.appendChild(el_label_position1);
	edit_main_td2_1.appendChild(el_label_left);
	edit_main_td2_1.appendChild(br);
	edit_main_td2_1.appendChild(el_label_position2);
	edit_main_td2_1.appendChild(el_label_top);
	
	edit_main_td3.appendChild(el_size_label);
	
	edit_main_td3_1.appendChild(el_size_w);
	edit_main_td3_1.appendChild(X);
	edit_main_td3_1.appendChild(el_size_h);
	
	edit_main_td4.appendChild(el_first_value_label);
	edit_main_td4_1.appendChild(el_first_value_input);
	
	edit_main_td5.appendChild(el_style_label);
	edit_main_td5_1.appendChild(el_style_textarea);
	
	edit_main_td6.appendChild(el_required_label);
	edit_main_td6_1.appendChild(el_required);
	
	
	
	
	edit_main_td7.appendChild(el_attr_label);
	edit_main_td7.appendChild(el_attr_add);
	edit_main_td7.appendChild(br6);
	edit_main_td7.appendChild(el_attr_table);
	edit_main_td7.setAttribute("colspan", "2");

	
	edit_main_tr1.appendChild(edit_main_td1);
	edit_main_tr1.appendChild(edit_main_td1_1);
	edit_main_tr8.appendChild(edit_main_td8);
	edit_main_tr8.appendChild(edit_main_td8_1);
	edit_main_tr2.appendChild(edit_main_td2);
	edit_main_tr2.appendChild(edit_main_td2_1);
	edit_main_tr3.appendChild(edit_main_td3);
	edit_main_tr3.appendChild(edit_main_td3_1);
	edit_main_tr4.appendChild(edit_main_td4);
	edit_main_tr4.appendChild(edit_main_td4_1);
	edit_main_tr5.appendChild(edit_main_td5);
	edit_main_tr5.appendChild(edit_main_td5_1);
	edit_main_tr6.appendChild(edit_main_td6);
	edit_main_tr6.appendChild(edit_main_td6_1);
	edit_main_tr7.appendChild(edit_main_td7);
	edit_main_tr7.appendChild(edit_main_td7_1);

	edit_main_table.appendChild(edit_main_tr1);
	edit_main_table.appendChild(edit_main_tr8);
	edit_main_table.appendChild(edit_main_tr2);
	edit_main_table.appendChild(edit_main_tr3);
	edit_main_table.appendChild(edit_main_tr4);
	edit_main_table.appendChild(edit_main_tr5);
	edit_main_table.appendChild(edit_main_tr6);

	edit_main_table.appendChild(edit_main_tr7);
	edit_div.appendChild(edit_main_table);
	
	t.appendChild(edit_div);
	add_id_and_name(i, 'type_text');

//show table

	element='editor';
	var adding_type = document.createElement("input");
            adding_type.setAttribute("type", "hidden");
            adding_type.setAttribute("value", "type_wdeditor");
            adding_type.setAttribute("name", i+"_typeform_id_temp");
            adding_type.setAttribute("id", i+"_typeform_id_temp");
	var adding_required= document.createElement("input");
            adding_required.setAttribute("type", "hidden");
            adding_required.setAttribute("value", w_required);
            adding_required.setAttribute("name", i+"_requiredform_id_temp");			
            adding_required.setAttribute("id", i+"_requiredform_id_temp");
			
	
			
	var div = document.createElement('div');
      	div.setAttribute("id", "main_div");
		

		var div_field = document.createElement('div');
           	div_field.setAttribute("id", i+"_elemet_tableform_id_temp");
						
      	var div_label = document.createElement('div');
         	div_label.setAttribute("align", 'left');
         	div_label.style.display="table-cell";
			div_label.style.width=w_field_label_size+"px";
           	div_label.setAttribute("id", i+"_label_sectionform_id_temp");
			
      	var div_element = document.createElement('div');
         	div_element.setAttribute("align", 'left');
          	div_element.style.display="table-cell";
          	div_element.setAttribute("id", i+"_element_sectionform_id_temp");
		
		
			
      	var br1 = document.createElement('br');
      	var br2 = document.createElement('br');
     	var br3 = document.createElement('br');
      	var br4 = document.createElement('br');
      
      	var label = document.createElement('span');
			label.setAttribute("id", i+"_element_labelform_id_temp");
			label.innerHTML = w_field_label;
			label.setAttribute("class", "label");
	    
      	var required = document.createElement('span');
			required.setAttribute("id", i+"_required_elementform_id_temp");
			required.innerHTML = "";
			required.setAttribute("class", "required");
	if(w_required=="yes")
			required.innerHTML = " *";
			
			
			
	var adding = document.createElement('input');
		adding.setAttribute("id", i+"_elementform_id_temp");
		adding.setAttribute("name", i+"_elementform_id_temp");
		adding.setAttribute("type", "hidden");
		adding.style.width = w_size_w+"px";
		adding.style.height = w_size_h+"px";
		adding.setAttribute("title", w_title);

	var adding_text = document.createElement('span');
		adding_text.style.color="red";
		adding_text.style.fontStyle="italic";
		adding_text.innerHTML="Editor doesn't display in back end";		
	
	Left = document.createTextNode(i+"_editorform_id_temp");
	var div_for_editor = document.createElement('div');
		div_for_editor.style.display="none";
	
		var main_td  = document.getElementById('show_table');
	
      
      	div_label.appendChild(label);
      	div_label.appendChild(required);
      	div_element.appendChild(adding_type);
	
      	div_element.appendChild(adding_required);

      	div_element.appendChild(adding);
		div_element.appendChild(adding_text);
		div_for_editor.appendChild(Left);
		div_element.appendChild(div_for_editor);
      	div_field.appendChild(div_label);
      	div_field.appendChild(div_element);
      
      
      	div.appendChild(div_field);
      	div.appendChild(br3);
      	main_td.appendChild(div);
	if(w_field_label_pos=="top")
				label_top(i);
change_class(w_class, i);
refresh_attr(i, 'type_text');
}

function change_input_range(type, id)
{
	var s='';
	if(document.getElementById('el_range_'+type+'1').value!='')
		s=document.getElementById('el_range_'+type+'1').value;

	if(document.getElementById('el_range_'+type+'2').value!='')
	{
		if(document.getElementById('el_range_'+type+'1').value=='')
			s='0';			

		s=s+'.'+document.getElementById('el_range_'+type+'2').value;
	}
	
	document.getElementById(id+'_range_'+type+'form_id_temp').value=s;
}

function explode( delimiter, string ) {	
	var emptyArray = { 0: '' };

	if ( arguments.length != 2	|| typeof arguments[0] == 'undefined'	|| typeof arguments[1] == 'undefined' )
	{
		return null;
	}

	if ( delimiter === '' || delimiter === false	|| delimiter === null )
	{
		return false;
	}

	if ( typeof delimiter == 'function'	|| typeof delimiter == 'object'	|| typeof string == 'function'	|| typeof string == 'object' )
	{
		return emptyArray;
	}

	if ( delimiter === true ) {
		delimiter = '1';
	}

	return string.toString().split ( delimiter.toString() );
}



function type_paypal_price(i, w_field_label, w_field_label_size, w_field_label_pos, w_first_val, w_title, w_mini_labels, w_size, w_required, w_hide_cents, w_class, w_attr_name, w_attr_value, w_range_min, w_range_max) {
	
	document.getElementById("element_type").value="type_paypal_price";
	delete_last_child();
	var edit_div  = document.createElement('div');
		edit_div.setAttribute("id", "edit_div");
		
	var edit_main_table  = document.createElement('table');
		edit_main_table.setAttribute("id", "edit_main_table");
		edit_main_table.setAttribute("cellpadding", "3");
		edit_main_table.setAttribute("cellspacing", "0");
		
	var edit_main_tr1  = document.createElement('tr');
	var edit_main_tr2  = document.createElement('tr');
	var edit_main_tr3  = document.createElement('tr');
	var edit_main_tr4  = document.createElement('tr');
	var edit_main_tr5  = document.createElement('tr');
	var edit_main_tr6  = document.createElement('tr');
	var edit_main_tr7  = document.createElement('tr');
	var edit_main_tr8  = document.createElement('tr');
	var edit_main_tr9  = document.createElement('tr');
	var edit_main_tr10  = document.createElement('tr');
	var edit_main_td1 = document.createElement('td');
	var edit_main_td1_1 = document.createElement('td');
	var edit_main_td2 = document.createElement('td');
	var edit_main_td2_1 = document.createElement('td');
	var edit_main_td3 = document.createElement('td');
	var edit_main_td3_1 = document.createElement('td');
		edit_main_td3_1.style.cssText = "line-height:20px";
	var edit_main_td4 = document.createElement('td');
	var edit_main_td4_1 = document.createElement('td');
	var edit_main_td5 = document.createElement('td');
	var edit_main_td5_1 = document.createElement('td');
	var edit_main_td6 = document.createElement('td');
	var edit_main_td6_1 = document.createElement('td');
	var edit_main_td7 = document.createElement('td');
	var edit_main_td7_1 = document.createElement('td');
	var edit_main_td8 = document.createElement('td');
	var edit_main_td8_1 = document.createElement('td');
	var edit_main_td9 = document.createElement('td');
	var edit_main_td9_1 = document.createElement('td');
	var edit_main_td10 = document.createElement('td');
	var edit_main_td10_1 = document.createElement('td');
		  
	var el_label_label = document.createElement('label');
		el_label_label.setAttribute("class", "fm-field-label");
		el_label_label.setAttribute("for", "edit_for_label");
		el_label_label.innerHTML = "Field label";
	
	var el_label_textarea = document.createElement('textarea');
		el_label_textarea.setAttribute("id", "edit_for_label");
		el_label_textarea.setAttribute("rows", "4");
		el_label_textarea.setAttribute("onKeyUp", "change_label('"+i+"_element_labelform_id_temp', this.value)");
		el_label_textarea.innerHTML = w_field_label;
	
	var el_label_size_label = document.createElement('label');
		el_label_size_label.setAttribute("class", "fm-field-label");
	    el_label_size_label.setAttribute("for", "edit_for_label_size");
		el_label_size_label.innerHTML = "Field label size(px) ";
		
	var el_label_size = document.createElement('input');
	    el_label_size.setAttribute("id", "edit_for_label_size");
	    el_label_size.setAttribute("type", "text");
	    el_label_size.setAttribute("value", w_field_label_size);
		el_label_size.setAttribute("onKeyPress", "return check_isnum(event)");
        el_label_size.setAttribute("onKeyUp", "change_w_style('"+i+"_label_sectionform_id_temp', this.value)");
	
	var el_label_position_label = document.createElement('label');
		el_label_position_label.setAttribute("class", "fm-field-label");
		el_label_position_label.innerHTML = "Field label position";

	var el_label_position1 = document.createElement('input');
		el_label_position1.setAttribute("id", "edit_for_label_position_top");
		el_label_position1.setAttribute("type", "radio");
		el_label_position1.setAttribute("name", "edit_for_label_position");
		el_label_position1.setAttribute("onchange", "label_left("+i+")");

	var el_label_left = document.createElement('label');
		el_label_left.setAttribute("for", "edit_for_label_position_top");
		el_label_left.innerHTML = "Left";	

	var el_label_position2 = document.createElement('input');
		el_label_position2.setAttribute("id", "edit_for_label_position_left");
		el_label_position2.setAttribute("type", "radio");
		el_label_position2.setAttribute("name", "edit_for_label_position");
		el_label_position2.setAttribute("onchange", "label_top("+i+")");

	var el_label_top = document.createElement('label');
		el_label_top.setAttribute("for", "edit_for_label_position_left");
		el_label_top.innerHTML = "Top";
		
	if(w_field_label_pos=="top")
		el_label_position2.setAttribute("checked", "checked");
	else
		el_label_position1.setAttribute("checked", "checked");

	w_range_minarray = explode('.', w_range_min);
	w_range_maxarray = explode('.', w_range_max);
				
	var el_range_label = document.createElement('label');
		el_range_label.setAttribute("class", "fm-field-label");
		el_range_label.innerHTML = "Range ";
	
	var min = document.createTextNode("Min");
	
	var el_range_min1 = document.createElement('input');
        el_range_min1.setAttribute("type", "text");
        el_range_min1.setAttribute("id", "el_range_min1");
	if(w_range_minarray[0])
        el_range_min1.setAttribute("value", w_range_minarray[0]);
        el_range_min1.style.cssText = "width:60px; margin-right:4px;margin-left:8px";
		el_range_min1.setAttribute("onKeyPress", "return check_isnum(event)");
        el_range_min1.setAttribute("onChange", "change_input_range('min', '"+i+"')");

	var ket_min = document.createTextNode(".");

	var el_range_min2 = document.createElement('input');
        el_range_min2.setAttribute("type", "text");
        el_range_min2.setAttribute("id", "el_range_min2");
	if(w_range_minarray[1])
        el_range_min2.setAttribute("value", w_range_minarray[1]);
        el_range_min2.style.cssText = "width:30px; margin-left:4px";
		el_range_min2.setAttribute("onKeyPress", "return check_isnum(event)");
        el_range_min2.setAttribute("onChange", "change_input_range('min', '"+i+"')");

	var max = document.createTextNode("Max");
	
	var el_range_max1 = document.createElement('input');
        el_range_max1.setAttribute("type", "text");
        el_range_max1.setAttribute("id", "el_range_max1");
	if(w_range_maxarray[0])
        el_range_max1.setAttribute("value", w_range_maxarray[0]);
        el_range_max1.style.cssText = "width:60px; margin-right:4px; margin-left:7px";
		el_range_max1.setAttribute("onKeyPress", "return check_isnum(event)");
        el_range_max1.setAttribute("onChange", "change_input_range('max', '"+i+"')");

	var ket_max = document.createTextNode(".");

	var el_range_max2 = document.createElement('input');
        el_range_max2.setAttribute("type", "text");
        el_range_max2.setAttribute("id", "el_range_max2");
	if(w_range_maxarray[1])
        el_range_max2.setAttribute("value", w_range_maxarray[1]);
        el_range_max2.style.cssText = "width:30px; margin-left:4px";
		el_range_max2.setAttribute("onKeyPress", "return check_isnum(event)");
        el_range_max2.setAttribute("onChange", "change_input_range('max', '"+i+"')");

	var gic = document.createTextNode("-");

	var el_first_value_label = document.createElement('label');
		el_first_value_label.setAttribute("class", "fm-field-label");
		el_first_value_label.setAttribute("for", "el_first_value_first");
		el_first_value_label.innerHTML = "Placeholder ";
	
	var el_first_value_first = document.createElement('input');
		el_first_value_first.setAttribute("id", "el_first_value_first");
		el_first_value_first.setAttribute("type", "text");
		el_first_value_first.setAttribute("value", w_title[0]);
		el_first_value_first.style.cssText = "width:120px; margin-right:4px";
		el_first_value_first.setAttribute("onKeyPress", "return check_isnum(event)");
		el_first_value_first.setAttribute("onKeyUp", "change_input_value(this.value,'"+i+"_element_dollarsform_id_temp')");

	var el_first_value_last = document.createElement('input');
		el_first_value_last.setAttribute("id", "el_first_value_last");
		el_first_value_last.setAttribute("type", "text");
		el_first_value_last.setAttribute("value", w_title[1]);
		el_first_value_last.style.cssText = "width:67px; margin-left:4px; margin-right:4px";
		el_first_value_last.setAttribute("onKeyPress", "return check_isnum_interval(event,'"+i+"_element_centsform_id_temp',0,99)");
		el_first_value_last.setAttribute("onKeyUp", "change_input_value(this.value,'"+i+"_element_centsform_id_temp')");

	var el_size_label = document.createElement('label');
		el_size_label.setAttribute("class", "fm-field-label");
		el_size_label.setAttribute("for", "edit_for_input_size");
		el_size_label.innerHTML = "Field size(px) ";
	var el_size = document.createElement('input');
		el_size.setAttribute("id", "edit_for_input_size");
		el_size.setAttribute("type", "text");
		el_size.setAttribute("value", w_size);
		el_size.setAttribute("onKeyPress", "return check_isnum(event)");
		el_size.setAttribute("onKeyUp", "change_w_style('"+i+"_element_dollarsform_id_temp', this.value);");

	var el_required_label = document.createElement('label');
		el_required_label.setAttribute("class", "fm-field-label");
		el_required_label.setAttribute("for", "el_required");
		el_required_label.innerHTML = "Required";
	
	var el_required = document.createElement('input');
		el_required.setAttribute("id", "el_required");
		el_required.setAttribute("type", "checkbox");
		el_required.setAttribute("onclick", "set_required('"+i+"_required')");
		if(w_required=="yes")
			el_required.setAttribute("checked", "checked");	
				
	var el_hide_cents_label = document.createElement('label');
		el_hide_cents_label.setAttribute("class", "fm-field-label");
	    el_hide_cents_label.setAttribute("for", "el_hide_cents");
		el_hide_cents_label.innerHTML = "Hide Cents";

	var el_hide_cents = document.createElement('input');
		el_hide_cents.setAttribute("id", "el_hide_cents");
		el_hide_cents.setAttribute("type", "checkbox");
		el_hide_cents.setAttribute("onclick", "hide_show_cents(this.checked, "+i+")");
		if(w_hide_cents=="yes")
			el_hide_cents.setAttribute("checked", "checked");	
				
	var el_style_label = document.createElement('label');
		el_style_label.setAttribute("class", "fm-field-label");
		el_style_label.setAttribute("for", "el_style_textarea");
		el_style_label.innerHTML = "Class name";
	
	var el_style_textarea = document.createElement('input');
		el_style_textarea.setAttribute("id", "el_style_textarea");
		el_style_textarea.setAttribute("type", "text");
		el_style_textarea.setAttribute("value", w_class);
		el_style_textarea.setAttribute("onChange", "change_class(this.value,'"+i+"')");

	var el_attr_label = document.createElement('label');
		el_attr_label.setAttribute("class", "fm-field-label");
		el_attr_label.innerHTML = "Additional Attributes";
	var el_attr_add = document.createElement('img');
		el_attr_add.setAttribute("src", plugin_url + '/images/add.png?ver=1.8.0');
		el_attr_add.style.cssText = 'cursor:pointer; margin-left:68px';
		el_attr_add.setAttribute("title", 'add');
		el_attr_add.setAttribute("onClick", "add_attr("+i+", 'type_paypal_price')");
	var el_attr_table = document.createElement('table');
		el_attr_table.setAttribute("id", 'attributes');
		el_attr_table.setAttribute("border", '0');
		el_attr_table.style.cssText = 'margin-left:0px';
	var el_attr_tr_label = document.createElement('tr');
		el_attr_tr_label.setAttribute("idi", '0');
	var el_attr_td_name_label = document.createElement('th');
		el_attr_td_name_label.style.cssText = 'width:100px';
	var el_attr_td_value_label = document.createElement('th');
		el_attr_td_value_label.style.cssText = 'width:100px';
	var el_attr_td_X_label = document.createElement('th');
		el_attr_td_X_label.style.cssText = 'width:10px';
	var el_attr_name_label = document.createElement('label');
		el_attr_name_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
		el_attr_name_label.innerHTML = "Name";
	var el_attr_value_label = document.createElement('label');
		el_attr_value_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
		el_attr_value_label.innerHTML = "Value";
			
	el_attr_table.appendChild(el_attr_tr_label);
	el_attr_tr_label.appendChild(el_attr_td_name_label);
	el_attr_tr_label.appendChild(el_attr_td_value_label);
	el_attr_tr_label.appendChild(el_attr_td_X_label);
	el_attr_td_name_label.appendChild(el_attr_name_label);
	el_attr_td_value_label.appendChild(el_attr_value_label);
	
	n=w_attr_name.length;
	for(j=1; j<=n; j++)
	{	
		var el_attr_tr = document.createElement('tr');
			el_attr_tr.setAttribute("id", "attr_row_"+j);
			el_attr_tr.setAttribute("idi", j);
		var el_attr_td_name = document.createElement('td');
			el_attr_td_name.style.cssText = 'width:100px';
		var el_attr_td_value = document.createElement('td');
			el_attr_td_value.style.cssText = 'width:100px';
		
		var el_attr_td_X = document.createElement('td');
		var el_attr_name = document.createElement('input');
			el_attr_name.setAttribute("type", "text");
			el_attr_name.setAttribute("class", "fm-field-choice");
			el_attr_name.setAttribute("value", w_attr_name[j-1]);
			el_attr_name.setAttribute("id", "attr_name"+j);
			el_attr_name.setAttribute("onChange", "change_attribute_name("+i+", this, 'type_paypal_price')");
			
		var el_attr_value = document.createElement('input');
			el_attr_value.setAttribute("type", "text");
			el_attr_value.setAttribute("class", "fm-field-choice");
			el_attr_value.setAttribute("value", w_attr_value[j-1]);
			el_attr_value.setAttribute("id", "attr_value"+j);
			el_attr_value.setAttribute("onChange", "change_attribute_value("+i+", "+j+", 'type_paypal_price')");
	
		var el_attr_remove = document.createElement('img');
			el_attr_remove.setAttribute("id", "el_choices"+j+"_remove");
			el_attr_remove.setAttribute("src", plugin_url + '/images/delete.png?ver=1.8.0');
			el_attr_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:2px';
			el_attr_remove.setAttribute("onClick", "remove_attr("+j+", "+i+", 'type_paypal_price')");
		el_attr_table.appendChild(el_attr_tr);
		el_attr_tr.appendChild(el_attr_td_name);
		el_attr_tr.appendChild(el_attr_td_value);
		el_attr_tr.appendChild(el_attr_td_X);
		el_attr_td_name.appendChild(el_attr_name);
		el_attr_td_value.appendChild(el_attr_value);
		el_attr_td_X.appendChild(el_attr_remove);
		
	}

	var t  = document.getElementById('edit_table');
	
	var br = document.createElement('br');
	var br1 = document.createElement('br');
	var br2 = document.createElement('br');
	var br3 = document.createElement('br');
	var br4 = document.createElement('br');
	var br5 = document.createElement('br');
	var br6 = document.createElement('br');
	
	edit_main_td1.appendChild(el_label_label);
	edit_main_td1_1.appendChild(el_label_textarea);

	edit_main_td10.appendChild(el_label_size_label);
	edit_main_td10_1.appendChild(el_label_size);
	
	edit_main_td2.appendChild(el_label_position_label);
	edit_main_td2_1.appendChild(el_label_position1);
	edit_main_td2_1.appendChild(el_label_left);
	edit_main_td2_1.appendChild(br);
	edit_main_td2_1.appendChild(el_label_position2);
	edit_main_td2_1.appendChild(el_label_top);
	
	edit_main_td3.appendChild(el_range_label);
	edit_main_td3_1.appendChild(min);
	edit_main_td3_1.appendChild(el_range_min1);
	edit_main_td3_1.appendChild(ket_min);
	edit_main_td3_1.appendChild(el_range_min2);
	edit_main_td3_1.appendChild(br1);
	edit_main_td3_1.appendChild(max);
	edit_main_td3_1.appendChild(el_range_max1);
	edit_main_td3_1.appendChild(ket_max);
	edit_main_td3_1.appendChild(el_range_max2);

	edit_main_td9.appendChild(el_first_value_label);
	edit_main_td9_1.appendChild(el_first_value_first);
	edit_main_td9_1.appendChild(gic);
	edit_main_td9_1.appendChild(el_first_value_last);
	
	edit_main_td7.appendChild(el_size_label);
	edit_main_td7_1.appendChild(el_size);
	
	edit_main_td4.appendChild(el_style_label);
	edit_main_td4_1.appendChild(el_style_textarea);
	
	edit_main_td5.appendChild(el_required_label);
	edit_main_td5_1.appendChild(el_required);
	
	edit_main_td8.appendChild(el_hide_cents_label);
	edit_main_td8_1.appendChild(el_hide_cents);
	
	
	
	
	edit_main_td6.appendChild(el_attr_label);
	edit_main_td6.appendChild(el_attr_add);
	edit_main_td6.appendChild(br3);
	edit_main_td6.appendChild(el_attr_table);
	edit_main_td6.setAttribute("colspan", "2");
	
	edit_main_tr1.appendChild(edit_main_td1);
	edit_main_tr1.appendChild(edit_main_td1_1);
	
	edit_main_tr10.appendChild(edit_main_td10);
	edit_main_tr10.appendChild(edit_main_td10_1);
	edit_main_tr2.appendChild(edit_main_td2);
	edit_main_tr2.appendChild(edit_main_td2_1);
	edit_main_tr3.appendChild(edit_main_td3);
	edit_main_tr3.appendChild(edit_main_td3_1);
	edit_main_tr7.appendChild(edit_main_td7);
	edit_main_tr7.appendChild(edit_main_td7_1);
	edit_main_tr4.appendChild(edit_main_td4);
	edit_main_tr4.appendChild(edit_main_td4_1);
	edit_main_tr5.appendChild(edit_main_td5);
	edit_main_tr5.appendChild(edit_main_td5_1);
	edit_main_tr6.appendChild(edit_main_td6);
	edit_main_tr6.appendChild(edit_main_td6_1);
	edit_main_tr8.appendChild(edit_main_td8);
	edit_main_tr8.appendChild(edit_main_td8_1);
	edit_main_tr9.appendChild(edit_main_td9);
	edit_main_tr9.appendChild(edit_main_td9_1);
	edit_main_table.appendChild(edit_main_tr1);
	edit_main_table.appendChild(edit_main_tr10);
	edit_main_table.appendChild(edit_main_tr2);
	edit_main_table.appendChild(edit_main_tr3);
	edit_main_table.appendChild(edit_main_tr9);
	edit_main_table.appendChild(edit_main_tr7);
	edit_main_table.appendChild(edit_main_tr4);
	edit_main_table.appendChild(edit_main_tr5);
	edit_main_table.appendChild(edit_main_tr8);
	edit_main_table.appendChild(edit_main_tr6);
	edit_div.appendChild(edit_main_table);
	
	t.appendChild(edit_div);
	add_id_and_name(i, 'type_name');
	
//show table

	var adding_type = document.createElement("input");
            adding_type.setAttribute("type", "hidden");
            adding_type.setAttribute("value", "type_paypal_price");
            adding_type.setAttribute("name", i+"_typeform_id_temp");
            adding_type.setAttribute("id", i+"_typeform_id_temp");
			
	var adding_required= document.createElement("input");
            adding_required.setAttribute("type", "hidden");
            adding_required.setAttribute("value", w_required);
            adding_required.setAttribute("name", i+"_requiredform_id_temp");
            adding_required.setAttribute("id", i+"_requiredform_id_temp");
			
	var adding_range_min= document.createElement("input");
            adding_range_min.setAttribute("type", "hidden");
            adding_range_min.setAttribute("value", w_range_min);
            adding_range_min.setAttribute("name", i+"_range_minform_id_temp");
            adding_range_min.setAttribute("id", i+"_range_minform_id_temp");
			
	var adding_range_max= document.createElement("input");
            adding_range_max.setAttribute("type", "hidden");
            adding_range_max.setAttribute("value", w_range_max);
            adding_range_max.setAttribute("name", i+"_range_maxform_id_temp");
            adding_range_max.setAttribute("id", i+"_range_maxform_id_temp");
			
     	var div = document.createElement('div');
      	    div.setAttribute("id", "main_div");
		var div_for_editable_labels = document.createElement('div');
			div_for_editable_labels.setAttribute("class", "fm-editable-label");
			
      	edit_labels = document.createTextNode("The labels of the fields are editable. Please, click on the label to edit.");

		div_for_editable_labels.appendChild(edit_labels);  

		
					
      	var div_field = document.createElement('div');
           	div_field.setAttribute("id", i+"_elemet_tableform_id_temp");
						
      	var div_label = document.createElement('div');
         	div_label.setAttribute("align", 'left');
         	div_label.style.display="table-cell";
			div_label.style.width=w_field_label_size+"px";
           	div_label.setAttribute("id", i+"_label_sectionform_id_temp");
			
      	var div_element = document.createElement('div');
         	div_element.setAttribute("align", 'left');
          	div_element.style.display="table-cell";
          	div_element.setAttribute("id", i+"_element_sectionform_id_temp");
			
      	var table_price = document.createElement('div');
           	table_price.setAttribute("id", i+"_table_price");
			table_price.style.display="table";
			
      	var tr_price1 = document.createElement('div');
           	tr_price1.setAttribute("id", i+"_tr_price1");
			tr_price1.style.display="table-row";
			
      	var tr_price2 = document.createElement('div');
           	tr_price2.setAttribute("id", i+"_tr_price2");
			tr_price2.style.display="table-row";
			
      	var td_name_currency = document.createElement('div');
           	td_name_currency.setAttribute("id", i+"_td_name_currency");
			td_name_currency.style.display="table-cell";
			
      	var td_name_dollars = document.createElement('div');
           	td_name_dollars.setAttribute("id", i+"_td_name_dollars");
			td_name_dollars.style.display="table-cell";
			
       	var td_name_ket = document.createElement('div');
           	td_name_ket.setAttribute("id", i+"_td_name_divider");
			td_name_ket.style.display="table-cell";
		
     	var td_name_cents = document.createElement('div');
           	td_name_cents.setAttribute("id", i+"_td_name_cents");
			td_name_cents.style.display="table-cell";
			
      	var td_name_label_currency = document.createElement('div');
			td_name_label_currency.style.display="table-cell";
			
      	var td_name_label_dollars = document.createElement('div');
           	td_name_label_dollars.setAttribute("align", "left");
			td_name_label_dollars.style.display="table-cell";
			
      	var td_name_label_ket = document.createElement('div');
           	td_name_label_ket.setAttribute("id", i+"_td_name_label_divider");
			td_name_label_ket.style.display="table-cell";
			
      	var td_name_label_cents = document.createElement('div');
	        td_name_label_cents.setAttribute("align", "left");
           	td_name_label_cents.setAttribute("id", i+"_td_name_label_cents");
			td_name_label_cents.style.display="table-cell";
		
    var label = document.createElement('span');
		label.setAttribute("id", i+"_element_labelform_id_temp");
		label.innerHTML = w_field_label;
		label.setAttribute("class", "label");
		label.style.verticalAlign="top";
	    
    var required = document.createElement('span');
		required.setAttribute("id", i+"_required_elementform_id_temp");
		required.innerHTML = "";
		required.setAttribute("class", "required");
		required.style.verticalAlign="top";
		
	if(w_required=="yes")
		required.innerHTML = " *";	
		
	var currency = document.createElement('span');
		currency.setAttribute("class", 'wdform_colon');
		currency.style.cssText = "font-style:bold; vertical-align:middle";
		currency.innerHTML="<!--repstart-->&nbsp;$&nbsp;<!--repend-->";

	var currency_label = document.createElement('label');
	    currency_label.setAttribute("class", "mini_label");

	var dollars = document.createElement('input');
        dollars.setAttribute("type", 'text');
	    dollars.style.cssText = "width:"+w_size+"px";
	    dollars.setAttribute("id", i+"_element_dollarsform_id_temp");
	    dollars.setAttribute("name", i+"_element_dollarsform_id_temp");
		dollars.setAttribute("value", w_first_val[0]);
		dollars.setAttribute("title", w_title[0]);
		dollars.setAttribute("onKeyPress", "return check_isnum(event)");
			
	var dollars_label = document.createElement('label');
	    dollars_label.setAttribute("class", "mini_label");
	    dollars_label.setAttribute("id", i+"_mini_label_dollars");
		dollars_label.innerHTML= w_mini_labels[0];
		
	var ket = document.createElement('span');
        ket.setAttribute("class", 'wdform_colon');
		ket.style.cssText = "font-style:bold; vertical-align:middle";
		ket.innerHTML="&nbsp;.&nbsp;";

	var ket_label = document.createElement('label');
	    ket_label.setAttribute("class", "mini_label");

	var cents = document.createElement('input');
        cents.setAttribute("type", 'text');
		cents.style.cssText = "width:30px";
		cents.setAttribute("id", i+"_element_centsform_id_temp");
	   	cents.setAttribute("name", i+"_element_centsform_id_temp");
		cents.setAttribute("value", w_first_val[1]);
		cents.setAttribute("title", w_title[1]);
		cents.setAttribute("onBlur", 'add_0("'+i+'_element_centsform_id_temp")');
		cents.setAttribute("onKeyPress", "return check_isnum_interval(event,'"+i+"_element_centsform_id_temp',0,99)");

	var cents_label = document.createElement('label');
		cents_label.setAttribute("class", "mini_label");
		cents_label.setAttribute("id", i+"_mini_label_cents");
		cents_label.innerHTML= w_mini_labels[1];
			
    var main_td  = document.getElementById('show_table');
      	div_label.appendChild(label);
      	div_label.appendChild(required );
      	td_name_currency.appendChild(currency);
      	td_name_dollars.appendChild(dollars);
      	td_name_ket.appendChild(ket);
      	td_name_cents.appendChild(cents);
      	tr_price1.appendChild(td_name_currency);
      	tr_price1.appendChild(td_name_dollars);
      	tr_price1.appendChild(td_name_ket);
      	tr_price1.appendChild(td_name_cents);
      	td_name_label_currency.appendChild(currency_label);
      	td_name_label_dollars.appendChild(dollars_label);
      	td_name_label_ket.appendChild(ket_label);
      	td_name_label_cents.appendChild(cents_label);
      	tr_price2.appendChild(td_name_label_currency);
      	tr_price2.appendChild(td_name_label_dollars);
      	tr_price2.appendChild(td_name_label_ket);
      	tr_price2.appendChild(td_name_label_cents);
      	table_price.appendChild(tr_price1);
      	table_price.appendChild(tr_price2);
       	div_element.appendChild(adding_type);
       	div_element.appendChild(adding_required);
       	div_element.appendChild(adding_range_min);
       	div_element.appendChild(adding_range_max);
    	div_element.appendChild(table_price);
      	div_field.appendChild(div_label);
      	div_field.appendChild(div_element);
      	div.appendChild(div_field);
		div.appendChild(br3);
		div.appendChild(div_for_editable_labels);
      	main_td.appendChild(div);
		
	if(w_field_label_pos=="top")
				label_top(i);
				
	if(w_hide_cents=="yes")				
		hide_show_cents(true, i)
		
change_class(w_class, i);
refresh_attr(i, 'type_paypal_price');

jQuery(function() {
	jQuery("label#"+i+"_mini_label_dollars").on("click", function() {		
		if (jQuery(this).children('input').length == 0) {				
			var dollars = "<input type='text' class='dollars' style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";	
				jQuery(this).html(dollars);							
				jQuery(dollars).focus();			
				jQuery("input.dollars").blur(function() {	
			var value = jQuery(this).val();			


		jQuery("#"+i+"_mini_label_dollars").text(value);		
		});	
	}	
	});		


	jQuery("label#"+i+"_mini_label_cents").on("click", function() {	
	if (jQuery(this).children('input').length == 0) {		
		var cents = "<input type='text' class='cents'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";	
			jQuery(this).html(cents);			
			jQuery("input.cents").focus();					
			jQuery("input.cents").blur(function() {			
			var value = jQuery(this).val();			
			
			jQuery("#"+i+"_mini_label_cents").text(value);	
		});	
	}	
	});
	});
}

function hide_show_cents(hide, id)
{
	td_divider			=document.getElementById(id+"_td_name_divider");
	td_cents			=document.getElementById(id+"_td_name_cents");
	td_divider_label	=document.getElementById(id+"_td_name_label_divider");
	td_cents_label		=document.getElementById(id+"_td_name_label_cents");
	change_input_value('',id+'_element_centsform_id_temp');
	document.getElementById("el_first_value_last").value="";
	document.getElementById(id+'_element_centsform_id_temp').value="";

	if(hide)
	{
		td_divider.style.display="none";
		td_cents.style.display="none";
		td_divider_label.style.display="none";
		td_cents_label.style.display="none";

	}
	else
	{
		td_divider.style.display="table-cell";
		td_cents.style.display="table-cell";
		td_divider_label.style.display="table-cell";
		td_cents_label.style.display="table-cell";
	}
}

function type_date(i, w_field_label, w_field_label_size, w_field_label_pos, w_date, w_required, w_class, w_format, w_but_val, w_attr_name, w_attr_value,w_disable_past_days) { 

	document.getElementById("element_type").value="type_date";
	delete_last_child();
	var edit_div  = document.createElement('div');
		edit_div.setAttribute("id", "edit_div");
			
	var edit_main_table  = document.createElement('table');
		edit_main_table.setAttribute("id", "edit_main_table");
		edit_main_table.setAttribute("cellpadding", "3");
		edit_main_table.setAttribute("cellspacing", "0");
		
	var edit_main_tr1  = document.createElement('tr');
	var edit_main_tr2  = document.createElement('tr');
	var edit_main_tr3  = document.createElement('tr');
	var edit_main_tr4  = document.createElement('tr');
		edit_main_tr4.style.cssText = "display:none;";
	var edit_main_tr5  = document.createElement('tr');
	var edit_main_tr6  = document.createElement('tr');
	var edit_main_tr7  = document.createElement('tr');
	var edit_main_tr8  = document.createElement('tr');
	var edit_main_tr9  = document.createElement('tr');
      				
	var edit_main_td1 = document.createElement('td');
	var edit_main_td1_1 = document.createElement('td');
	var edit_main_td2 = document.createElement('td');
	var edit_main_td2_1 = document.createElement('td');
	var edit_main_td3 = document.createElement('td');
	var edit_main_td3_1 = document.createElement('td');
	var edit_main_td4 = document.createElement('td');
	var edit_main_td4_1 = document.createElement('td');
	var edit_main_td5 = document.createElement('td');
	var edit_main_td5_1 = document.createElement('td');
	var edit_main_td6 = document.createElement('td');
	var edit_main_td6_1 = document.createElement('td');
	var edit_main_td7 = document.createElement('td');
	var edit_main_td7_1 = document.createElement('td');
	var edit_main_td8 = document.createElement('td');
	var edit_main_td8_1 = document.createElement('td');
	var edit_main_td9 = document.createElement('td');
	var edit_main_td9_1 = document.createElement('td');
	
	var el_label_label = document.createElement('label');
		el_label_label.setAttribute("for", "edit_for_label");
		el_label_label.setAttribute("class", "fm-field-label");
		el_label_label.innerHTML = "Field label";
	
	var el_label_textarea = document.createElement('textarea');
		el_label_textarea.setAttribute("id", "edit_for_label");
		el_label_textarea.setAttribute("rows", "4");
		el_label_textarea.setAttribute("onKeyUp", "change_label('"+i+"_element_labelform_id_temp', this.value)");
		el_label_textarea.innerHTML = w_field_label;
	
	var el_label_size_label = document.createElement('label');
		el_label_size_label.setAttribute("class", "fm-field-label");
	    el_label_size_label.setAttribute("for", "edit_for_label_size");
		el_label_size_label.innerHTML = "Field label size(px) ";
		
	var el_label_size = document.createElement('input');
	    el_label_size.setAttribute("id", "edit_for_label_size");
	    el_label_size.setAttribute("type", "text");
	    el_label_size.setAttribute("value", w_field_label_size);
		el_label_size.setAttribute("onKeyPress", "return check_isnum(event)");
        el_label_size.setAttribute("onKeyUp", "change_w_style('"+i+"_label_sectionform_id_temp', this.value)");

	var el_label_position_label = document.createElement('label');
		el_label_position_label.setAttribute("class", "fm-field-label");
		el_label_position_label.innerHTML = "Field label position";

	var el_label_position1 = document.createElement('input');
		el_label_position1.setAttribute("id", "edit_for_label_position_top");
		el_label_position1.setAttribute("type", "radio");
		el_label_position1.setAttribute("name", "edit_for_label_position");
		el_label_position1.setAttribute("onchange", "label_left("+i+")");

	var el_label_left = document.createElement('label');
		el_label_left.setAttribute("for", "edit_for_label_position_top");
		el_label_left.innerHTML = "Left";	

	var el_label_position2 = document.createElement('input');
		el_label_position2.setAttribute("id", "edit_for_label_position_left");
		el_label_position2.setAttribute("type", "radio");
		el_label_position2.setAttribute("name", "edit_for_label_position");
		el_label_position2.setAttribute("onchange", "label_top("+i+")");

	var el_label_top = document.createElement('label');
		el_label_top.setAttribute("for", "edit_for_label_position_left");
		el_label_top.innerHTML = "Top";
		
	if(w_field_label_pos=="top")
		el_label_position2.setAttribute("checked", "checked");
	else
		el_label_position1.setAttribute("checked", "checked");

	var el_format_label = document.createElement('label');
		el_format_label.setAttribute("class", "fm-field-label");
		el_format_label.setAttribute("for", "date_format");
		el_format_label.innerHTML = "Date format";
	
	var el_format_textarea = document.createElement('input');
		el_format_textarea.setAttribute("id", "date_format");
		el_format_textarea.setAttribute("type", "text");
		el_format_textarea.setAttribute("value", w_format);
		el_format_textarea.setAttribute("onChange", "change_date_format(this.value,'"+i+"', 'format')");

	var el_button_value_label = document.createElement('label');
		el_button_value_label.setAttribute("class", "fm-field-label");
		el_button_value_label.setAttribute("for", "button_value");
		el_button_value_label.innerHTML = "Date Picker label";
	
	var el_button_value_textarea = document.createElement('input');
		el_button_value_textarea.setAttribute("id", "button_value");
		el_button_value_textarea.setAttribute("type", "text");
		el_button_value_textarea.setAttribute("value", w_but_val);
		el_button_value_textarea.style.cssText = "width:150px;";
		el_button_value_textarea.setAttribute("onKeyUp", "change_file_value(this.value,'"+i+"_buttonform_id_temp')");

	var el_disable_past_days_label = document.createElement('label');
		el_disable_past_days_label.setAttribute("class", "fm-field-label");
		el_disable_past_days_label.setAttribute("for", "el_disable_past_days");
		el_disable_past_days_label.innerHTML = "Allow selecting dates starting from current day";
	
	var el_disable_past_days = document.createElement('input');
		el_disable_past_days.setAttribute("id", "el_disable_past_days");
		el_disable_past_days.setAttribute("type", "checkbox");
        el_disable_past_days.setAttribute("onclick", "change_date_format(this.checked, '"+i+"', 'dis_days')");
		if(w_disable_past_days == "yes")
            el_disable_past_days.setAttribute("checked", "checked");
		
	var el_style_label = document.createElement('label');
		el_style_label.setAttribute("class", "fm-field-label");
		el_style_label.setAttribute("for", "el_style_textarea");
		el_style_label.innerHTML = "Class name";
	
	var el_style_textarea = document.createElement('input');
		el_style_textarea.setAttribute("id", "el_style_textarea");
		el_style_textarea.setAttribute("type", "text");
		el_style_textarea.setAttribute("value", w_class);
		
		el_style_textarea.setAttribute("onChange", "change_class(this.value,'"+i+"')");

	var el_required_label = document.createElement('label');
		el_required_label.setAttribute("class", "fm-field-label");
		el_required_label.setAttribute("for", "el_required");
		el_required_label.innerHTML = "Required";
	
	var el_required = document.createElement('input');
		el_required.setAttribute("id", "el_required");
		el_required.setAttribute("type", "checkbox");
		el_required.setAttribute("onclick", "set_required('"+i+"_required')");
		if(w_required == "yes")
			el_required.setAttribute("checked", "checked");
		
	var el_attr_label = document.createElement('label');
		el_attr_label.setAttribute("class", "fm-field-label");
		el_attr_label.innerHTML = "Additional Attributes";
	var el_attr_add = document.createElement('img');
		el_attr_add.setAttribute("src", plugin_url + '/images/add.png?ver=1.8.0');
		el_attr_add.style.cssText = 'cursor:pointer; margin-left:68px';
		el_attr_add.setAttribute("title", 'add');
		el_attr_add.setAttribute("onClick", "add_attr("+i+", 'type_date')");
	var el_attr_table = document.createElement('table');
		el_attr_table.setAttribute("id", 'attributes');
		el_attr_table.setAttribute("border", '0');
		el_attr_table.style.cssText = 'margin-left:0px';
	var el_attr_tr_label = document.createElement('tr');
		el_attr_tr_label.setAttribute("idi", '0');
	var el_attr_td_name_label = document.createElement('th');
		el_attr_td_name_label.style.cssText = 'width:100px';
	var el_attr_td_value_label = document.createElement('th');
		el_attr_td_value_label.style.cssText = 'width:100px';
	var el_attr_td_X_label = document.createElement('th');
		el_attr_td_X_label.style.cssText = 'width:10px';
	var el_attr_name_label = document.createElement('label');
		el_attr_name_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
		el_attr_name_label.innerHTML = "Name";
			
	var el_attr_value_label = document.createElement('label');
		el_attr_value_label.style.cssText ="color:#000; font-weight:bold; font-size: 11px";
		el_attr_value_label.innerHTML = "Value";
			
	el_attr_table.appendChild(el_attr_tr_label);
	el_attr_tr_label.appendChild(el_attr_td_name_label);
	el_attr_tr_label.appendChild(el_attr_td_value_label);
	el_attr_tr_label.appendChild(el_attr_td_X_label);
	el_attr_td_name_label.appendChild(el_attr_name_label);
	el_attr_td_value_label.appendChild(el_attr_value_label);
	
	n=w_attr_name.length;
	for(j=1; j<=n; j++)
	{	
		var el_attr_tr = document.createElement('tr');
			el_attr_tr.setAttribute("id", "attr_row_"+j);
			el_attr_tr.setAttribute("idi", j);
		var el_attr_td_name = document.createElement('td');
			el_attr_td_name.style.cssText = 'width:100px';
		var el_attr_td_value = document.createElement('td');
			el_attr_td_value.style.cssText = 'width:100px';
		
		var el_attr_td_X = document.createElement('td');
		var el_attr_name = document.createElement('input');
			el_attr_name.setAttribute("type", "text");
			el_attr_name.setAttribute("class", "fm-field-choice");
			el_attr_name.setAttribute("value", w_attr_name[j-1]);
			el_attr_name.setAttribute("id", "attr_name"+j);
			el_attr_name.setAttribute("onChange", "change_attribute_name("+i+", this, 'type_date')");
			
		var el_attr_value = document.createElement('input');
			el_attr_value.setAttribute("type", "text");
			el_attr_value.setAttribute("class", "fm-field-choice");
			el_attr_value.setAttribute("value", w_attr_value[j-1]);
			el_attr_value.setAttribute("id", "attr_value"+j);
			el_attr_value.setAttribute("onChange", "change_attribute_value("+i+", "+j+", 'type_date')");
	
		var el_attr_remove = document.createElement('img');
			el_attr_remove.setAttribute("id", "el_choices"+j+"_remove");
			el_attr_remove.setAttribute("src", plugin_url + '/images/delete.png?ver=1.8.0');
			el_attr_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:2px';
			
		el_attr_remove.setAttribute("onClick", "remove_attr("+j+", "+i+", 'type_date')");
		el_attr_table.appendChild(el_attr_tr);
		el_attr_tr.appendChild(el_attr_td_name);
		el_attr_tr.appendChild(el_attr_td_value);
		el_attr_tr.appendChild(el_attr_td_X);
		el_attr_td_name.appendChild(el_attr_name);
		el_attr_td_value.appendChild(el_attr_value);
		el_attr_td_X.appendChild(el_attr_remove);
	}

	var t  = document.getElementById('edit_table');
	var br = document.createElement('br');
	var br1 = document.createElement('br');
	
	edit_main_td1.appendChild(el_label_label);
	edit_main_td1_1.appendChild(el_label_textarea);
	edit_main_td8.appendChild(el_label_size_label);
	edit_main_td8_1.appendChild(el_label_size);

edit_main_td2.appendChild(el_label_position_label);
	edit_main_td2_1.appendChild(el_label_position1);
	edit_main_td2_1.appendChild(el_label_left);
	edit_main_td2_1.appendChild(br);
	edit_main_td2_1.appendChild(el_label_position2);
	edit_main_td2_1.appendChild(el_label_top);

	edit_main_td3.appendChild(el_format_label);
	edit_main_td3_1.appendChild(el_format_textarea);
	
	edit_main_td4.appendChild(el_button_value_label);
	edit_main_td4_1.appendChild(el_button_value_textarea);
	
	edit_main_td9.appendChild(el_disable_past_days_label);
	edit_main_td9_1.appendChild(el_disable_past_days);
	
	edit_main_td5.appendChild(el_style_label);
	edit_main_td5_1.appendChild(el_style_textarea);
	
	edit_main_td6.appendChild(el_required_label);
	edit_main_td6_1.appendChild(el_required);
	
	
	
	edit_main_td7.appendChild(el_attr_label);
	edit_main_td7.appendChild(el_attr_add);
	edit_main_td7.appendChild(br1);
	edit_main_td7.appendChild(el_attr_table);
	edit_main_td7.setAttribute("colspan", "2");
	
	edit_main_tr1.appendChild(edit_main_td1);
	edit_main_tr1.appendChild(edit_main_td1_1);
	edit_main_tr8.appendChild(edit_main_td8);
	edit_main_tr8.appendChild(edit_main_td8_1);
	edit_main_tr2.appendChild(edit_main_td2);
	edit_main_tr2.appendChild(edit_main_td2_1);
	edit_main_tr3.appendChild(edit_main_td3);
	edit_main_tr3.appendChild(edit_main_td3_1);
	edit_main_tr4.appendChild(edit_main_td4);
	edit_main_tr4.appendChild(edit_main_td4_1);
	edit_main_tr9.appendChild(edit_main_td9);
	edit_main_tr9.appendChild(edit_main_td9_1);
	edit_main_tr5.appendChild(edit_main_td5);
	edit_main_tr5.appendChild(edit_main_td5_1);
	edit_main_tr6.appendChild(edit_main_td6);
	edit_main_tr6.appendChild(edit_main_td6_1);
	edit_main_tr7.appendChild(edit_main_td7);
	edit_main_tr7.appendChild(edit_main_td7_1);
	edit_main_table.appendChild(edit_main_tr1);
	edit_main_table.appendChild(edit_main_tr8);
	edit_main_table.appendChild(edit_main_tr2);
	edit_main_table.appendChild(edit_main_tr3);
	edit_main_table.appendChild(edit_main_tr4);
	edit_main_table.appendChild(edit_main_tr9);
	edit_main_table.appendChild(edit_main_tr5);
	edit_main_table.appendChild(edit_main_tr6);
	edit_main_table.appendChild(edit_main_tr7);
	edit_div.appendChild(edit_main_table);
	
	t.appendChild(edit_div);
	add_id_and_name(i, 'type_text');
	
	var adding_type = document.createElement("input");
		adding_type.setAttribute("type", "hidden");
		adding_type.setAttribute("value", "type_date");
		adding_type.setAttribute("name", i+"_typeform_id_temp");
		adding_type.setAttribute("id", i+"_typeform_id_temp");
	var adding_required = document.createElement("input");
		adding_required.setAttribute("type", "hidden");
		adding_required.setAttribute("value", w_required);
		adding_required.setAttribute("name", i+"_requiredform_id_temp");
		adding_required.setAttribute("id", i+"_requiredform_id_temp");
	var adding_dis_past_days = document.createElement('input');
		adding_dis_past_days.setAttribute("type", 'hidden');
		adding_dis_past_days.setAttribute("value", w_disable_past_days);
		adding_dis_past_days.setAttribute("id", i+"_dis_past_daysform_id_temp");
		adding_dis_past_days.setAttribute("name", i+"_dis_past_daysform_id_temp");
			
	var div = document.createElement('div');
		div.setAttribute("id", "main_div");
				
	var div_field = document.createElement('div');
		div_field.setAttribute("id", i+"_elemet_tableform_id_temp");
					
	var div_label = document.createElement('div');
		div_label.setAttribute("align", 'left');
		div_label.style.display="table-cell";
		div_label.style.width=w_field_label_size+"px";
		div_label.setAttribute("id", i+"_label_sectionform_id_temp");
		
	var div_element = document.createElement('div');
		div_element.setAttribute("align", 'left');
		div_element.style.display="table-cell";
		div_element.setAttribute("id", i+"_element_sectionform_id_temp");
		
	var table_date = document.createElement('div');
		table_date.setAttribute("id", i+"_table_date");
		table_date.style.display="table";
		
	var tr_date1 = document.createElement('div');
		tr_date1.setAttribute("id", i+"_tr_date1");
		tr_date1.style.display="table-row";
		
	var tr_date2 = document.createElement('div');
		tr_date2.setAttribute("id", i+"_tr_date2");
		tr_date2.style.display="table-row";
		
	var td_date_input1 = document.createElement('div');
		td_date_input1.setAttribute("id", i+"_td_date_input1");
		td_date_input1.style.display="table-cell";
			
	var td_date_input2 = document.createElement('div');
		td_date_input2.setAttribute("id", i+"_td_date_input2");
		td_date_input2.style.display="table-cell";
		
	var td_date_input3 = document.createElement('div');
		td_date_input3.setAttribute("id", i+"_td_date_input3");
		td_date_input3.style.display="table-cell";

	var td_date_label1 = document.createElement('div');
		td_date_label1.setAttribute("id", i+"_td_date_label1");
		td_date_label1.style.display="table-cell";
			
	var td_date_label2 = document.createElement('div');
		td_date_label2.setAttribute("id", i+"_td_date_label2");
		td_date_label2.style.display="table-cell";
		
	var td_date_label3 = document.createElement('div');
		td_date_label3.setAttribute("id", i+"_td_date_label3");
		td_date_label3.style.display="table-cell";
		
	var br3 = document.createElement('br');
	var br4 = document.createElement('br');
      

    var label = document.createElement('span');
		label.setAttribute("id", i+"_element_labelform_id_temp");
		label.innerHTML = w_field_label;
		label.setAttribute("class", "label");
		label.style.verticalAlign="top";
		
	var required = document.createElement('span');
		required.setAttribute("id", i+"_required_elementform_id_temp");
		required.innerHTML = "";
		required.setAttribute("class", "required");
		required.style.verticalAlign="top";
		if(w_required=="yes")
			required.innerHTML = " *";
			
	var adding = document.createElement('input');
		adding.setAttribute("type", 'text');
		adding.setAttribute("value", w_date);
		adding.setAttribute("class", 'wdform-date');
		adding.setAttribute("id", i+"_elementform_id_temp");
		adding.setAttribute("name", i+"_elementform_id_temp");
		adding.setAttribute("maxlength", "10");
		adding.setAttribute("size", "10");

	var dis_past_days = w_disable_past_days == 'yes' ? true : false;
	
	var adding_button = document.createElement('input');
		adding_button.setAttribute("id", i+"_buttonform_id_temp");
		adding_button.setAttribute("class", "button");
		adding_button.setAttribute("type", 'reset');
		adding_button.setAttribute("value", w_but_val);
		adding_button.setAttribute("format", w_format);
			
	var main_td  = document.getElementById('show_table');
      
	div_label.appendChild(label);
	div_label.appendChild(required);
	div_element.appendChild(adding_type);
	div_element.appendChild(adding_required);
	div_element.appendChild(adding_dis_past_days);
	div_element.appendChild(adding);
	div_element.appendChild(adding_button);
	div_field.appendChild(div_label);
	div_field.appendChild(div_element);
	div.appendChild(div_field);
	div.appendChild(br3);
	main_td.appendChild(div);

	if(w_field_label_pos=="top")
		label_top(i);
	change_class(w_class, i);
	refresh_attr(i, 'type_date');
}

function form_maker_getElementsByAttribute(node,tag,attr,value){
  var elems = (tag=="*" && node.all) ? node.all : node.getElementsByTagName(tag),
    returnElems = new Array(),
    nValue = (typeof value!="undefined") ? new RegExp("(^|\\s)" + value + "(\\s|$)") : null,
    nAttr,
    cur;
  for (var i = 0; i < elems.length; i++) {
    cur = elems[i];
    nAttr = cur.getAttribute && cur.getAttribute(attr);
    if (typeof nAttr == "string" && nAttr.length > 0) {
      if (typeof value == "undefined" || (nValue && nValue.test(nAttr))) {
        returnElems.push(cur);
      }
    }
  }
  return returnElems;
}

function change_element_attribute(value, id, type){
	if(type == 'w_readonly' || type == 'w_hide_field'){
		if(document.getElementById(id+'_elementform_id_temp').getAttribute(type) == 'yes')
			document.getElementById(id+'_elementform_id_temp').setAttribute(type, 'no');
				
		else
			document.getElementById(id+'_elementform_id_temp').setAttribute(type, 'yes');
	}
	else {
		document.getElementById(id+'_elementform_id_temp').setAttribute(type, value);
	}
	
}

function change_input_style(id){
	if(!document.getElementById(id+'_elementform_id_temp').getAttribute('readonly'))
		document.getElementById(id+'_elementform_id_temp').setAttribute('readonly', 'readonly');
	else
		document.getElementById(id+'_elementform_id_temp').removeAttribute('readonly');
}

function change_src(b,id,form_id)
{
	for(var j=0;j<=b;j++)
	document.getElementById(id+'_star_'+j).src=plugin_url + "/images/star_"+document.getElementById(id+'_star_colorform_id_temp').value+".png";
}


function reset_src(b,id)
{
	for(var j=0;j<=b;j++)
	document.getElementById(id+'_star_'+j).src=plugin_url + "/images/star.png";
}

function select_star_rating(id,a,form_id){}

function change_range_width(a,id,form_id)
{
	document.getElementById( id+"_elementform_id_temp0" ).style.cssText="width:"+a+"px";
	document.getElementById( id+"_elementform_id_temp1" ).style.cssText="width:"+a+"px";
	document.getElementById( id+"_range_widthform_id_temp" ).value=a;
}
//////////////////////////////////////////////
///////////  type_page_break   //////////////////
///////////////////////////////////////////////

function go_to_type_paypal_price(new_id)
{
 	w_attr_name=[];
 	w_attr_value=[];
 	w_first_val=['',''];
 	w_title=['',''];
	w_mini_labels=['Dollars','Cents'];
	type_paypal_price(new_id,'Amount', '100', 'left', w_first_val, w_title, w_mini_labels, '100', 'no', 'no', '',w_attr_name, w_attr_value, '', '')
}

function go_to_type_number(new_id)
{
 	w_attr_name=[];
 	w_attr_value=[];
	type_number(new_id,'Number:', '100', 'left', '200', '', '', 'no', 'no', '',w_attr_name, w_attr_value);
}

function go_to_type_wdeditor(new_id)
{
 	w_attr_name=[];
 	w_attr_value=[];
	type_wdeditor(new_id,'Editor:', '100', 'left', '380', '200', '', 'no', '',w_attr_name, w_attr_value)
}

function go_to_type_date(new_id)
{
 	w_attr_name=[];
 	w_attr_value=[];
	
	type_date(new_id, 'Date:', '100', 'left', '', 'no', '', '%Y-%m-%d', '...',w_attr_name, w_attr_value, 'no');
}
///////////////////////////////////////////////
///////////  el_page_break   //////////////////
///////////////////////////////////////////////

function remove_section_break(id) {
  var wdform_section_break = jQuery("#wdform_field" + id).parent();
  var move = wdform_section_break.next();
  var to = wdform_section_break.prev();

	move.find('.wdform_column').each(function(col_index, column) {
		var to_col = to.children().eq(col_index);
		if (!to_col || to_col.hasClass('wdform_column_empty')) {
			to.find('.wdform_column_empty').before(column);
		}
		else {
			jQuery(column).find('.wdform_row').each(function(row_index, row) {
				to_col.append(row);
			});
		}
	});
  wdform_section_break.remove();
  move.remove();
}

function fm_remove_section(remove_childs) {
  var section = jQuery('.fm-row-deleting').first().closest('.wdform_section');
  var wdform_section_break = section.prev('.wdform_tr_section_break');
  if (!remove_childs) {
    var to = section.prevAll('.wdform_section:first');
    if (!to.length) {
    	to = section.nextAll('.wdform_section:first');
		}
		if (!to.length) {
    	return;
		}
    section.find('.wdform_column').each(function(col_index, column) {
      to.append(column);
    });
  }
  wdform_section_break.remove();
  section.remove();
}

function remove_row(id)
{
	var wdform_row=document.getElementById( "wdform_field"+id).parentNode;
	var wdform_column=wdform_row.parentNode;

	wdform_column.removeChild(wdform_row);
	
}

function destroyChildren(node)
{
  while (node.firstChild)
      node.removeChild(node.firstChild);
}

function show_or_hide(id) {
	if (!jQuery("#form_id_tempform_view"+id).is(":visible")) {
		show_form_view(id);
  }
	else {
		hide_form_view(id);
  }
}

function show_form_view(id) {
	jQuery("#form_id_tempform_view"+id).show();
}

function hide_form_view(id) {
	jQuery("#form_id_tempform_view"+id).hide();
}

function generate_buttons(id) {
  form_view_elemet = document.getElementById("form_id_tempform_view" + id);
  var td = document.createElement("div");
  td.setAttribute("valign", "middle");
  td.setAttribute("align", "left");
  td.style.display = "inline-block";
  td.style.width = "40%";
  page_nav.appendChild(td);

  if (form_view_elemet.parentNode.previousSibling) {
    if (form_view_elemet.parentNode.previousSibling.tagName == "DIV") {
      table = true;
    }
    else if (form_view_elemet.parentNode.previousSibling.previousSibling) {
      if (form_view_elemet.parentNode.previousSibling.previousSibling.tagName == "DIV") {
        table = true;
      }
      else {
        table = false;
      }
    }
    else {
      table = false;
    }

    if (table) {
      if (form_view_elemet.getAttribute('previous_title')) {
        previous_title = form_view_elemet.getAttribute('previous_title');
        previous_type = form_view_elemet.getAttribute('previous_type');
        previous_class = form_view_elemet.getAttribute('previous_class');
      }
      else {
        previous_title = "Previous";
        previous_type = "button";
        previous_class = "";
      }
      next_or_previous = "previous";
      previous = make_pagebreak_button(next_or_previous, previous_title, previous_type, previous_class, id);
      td.appendChild(previous);
    }
  }

  var td = document.createElement("div");
  td.setAttribute("id", "page_numbersform_id_temp" + id);
  td.setAttribute("valign", "middle");
  td.setAttribute("align", "center");
  td.style.display = "inline-block";
  td.style.width = "20%";
  page_nav.appendChild(td);

  var td = document.createElement("div");
  td.setAttribute("valign", "middle");
  td.setAttribute("align", "right");
  td.style.display = "inline-block";
  td.style.width = "40%";
  page_nav.appendChild(td);

  if (form_view_elemet.parentNode.nextSibling) {
    if (form_view_elemet.parentNode.nextSibling.tagName == "DIV") {
      table = true;
    }
    else if (form_view_elemet.parentNode.nextSibling.nextSibling) {
      if (form_view_elemet.parentNode.nextSibling.nextSibling.tagName == "DIV") {
        table = true;
      }
      else {
        table = false;
      }
    }
    else {
      table = false;
    }

    if (table) {
      if (form_view_elemet.getAttribute('previous_title')) {
        next_title = form_view_elemet.getAttribute('next_title');
        next_type = form_view_elemet.getAttribute('next_type');
        next_class = form_view_elemet.getAttribute('next_class');
      }
      else {
        next_title = "Next";
        next_type = "button";
        next_class = "";
      }
      next_or_previous = "next";
      next = make_pagebreak_button(next_or_previous, next_title, next_type, next_class, id);
      td.appendChild(next);
    }
  }
}

function generate_page_nav(id) {
  form_view = id;
  document.getElementById('form_id_tempform_view' + id).parentNode.style.borderWidth = "1px";

	jQuery('.wdform-page-and-images').each(function(){
		var index = jQuery(this).find('.form_id_tempform_view_img').attr('id').split("form_id_tempform_view_img");
		t = index[1];
		page_nav = document.getElementById("form_id_temppage_nav" + t);
		destroyChildren(page_nav);
		generate_buttons(t);
	});

  generate_page_bar();
  refresh_page_numbers();
}

function remove_page(id) {
	jQuery('#fm_delete_page_id').val(id);
  fm_popup_toggle('fm_delete_page_popup_container');
}

function remove_field(id, e) {
  jQuery('#fm_delete_field_id').val(id);
  fm_popup_toggle('fm_delete_field_popup_container');
  if ( typeof e != "undefined" ) {
    e.stopPropagation();
    e.preventDefault();
  }
}

function fm_remove_column_popup(that) {
  jQuery('.wdform_column').removeClass('fm-column-deleting');
	jQuery(that).closest('.wdform_column').addClass('fm-column-deleting');
  fm_popup_toggle('fm_delete_column_popup_container');
}

function fm_remove_row_popup(that) {
  jQuery('.wdform_section').removeClass('fm-row-deleting');
  jQuery(that).closest('.wdform_section').addClass('fm-row-deleting');
  if (jQuery(that).closest('.wdform_section').find('.wdform_row').length) {
      fm_popup_toggle('fm_delete_row_popup_container');
  }
  else {
      fm_remove_section(false);
  }
}

function fm_remove_column() {
  jQuery('.fm-column-deleting').remove();
}

function remove_page_only() {
  id = jQuery('#fm_delete_page_id').val();
  refresh_pages_without_deleting(id);
}

function remove_page_all() {
  id = jQuery('#fm_delete_page_id').val();
  form_view_elemet = document.getElementById("form_id_tempform_view" + id);
  form_view_count = 0;
  var form_view_count = jQuery(".wdform-page-and-images").length;

  if (form_view_count == 2) {
    jQuery(".form_id_tempform_view_img").removeClass('form_view_show').addClass('form_view_hide');
    jQuery('*[id*=form_id_temppage_nav]').empty();
  }

  if (form_view_count == 1) {
    form_view_elemet.innerHTML = '';
    tbody = form_view_elemet;
    tr = document.createElement('div');
    tr.setAttribute('class', 'wdform_section');
    tr.style.display = "table-row";

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

    tr.appendChild(table_min);

    tbody_min_page_nav.appendChild(tr_min_page_nav);
    table_min_page_nav.appendChild(tbody_min_page_nav);
    td_page_nav.appendChild(table_min_page_nav);
    tr_page_nav.appendChild(td_page_nav);
    tbody.appendChild(tr);
    tbody.appendChild(tr_page_nav);

    return;
  }
  form_view_table = form_view_elemet.parentNode;
  document.getElementById("take").removeChild(form_view_table);
  refresh_pages(id);
}

function refresh_pages(id) {
  temp = 1;
  form_view_count = 0;
  destroyChildren(document.getElementById("pages"));
  var form_view_count = jQuery(".wdform-page-and-images").length;
  generate_page_bar();
  if (form_view_count > 1) {
    jQuery('#page_bar').removeClass('form_view_hide');
  }
  else {
		destroyChildren(document.getElementById("edit_page_navigation"));
		jQuery('#page_bar').addClass('form_view_hide');
		jQuery(".wdform_page").removeAttr('style');
  }
}

function refresh_pages_without_deleting(id) {
  var form_view_elemet = jQuery("#form_id_tempform_view" + id);
  var wdform_row = form_view_elemet.find('.wdform_row');
  var form_view_count = jQuery(".wdform-page-and-images").length;
  if (form_view_count == 2) {
    jQuery(".form_id_tempform_view_img").removeClass('form_view_show').addClass('form_view_hide');
    jQuery('*[id*=form_id_temppage_nav]').empty();
  }

  var table = form_view_elemet.parent();
  var to = table.prevAll('.wdform-page-and-images:first');
  if (!to.length) {
    to = table.nextAll('.wdform-page-and-images:first');
  }
  if (!to.length) {
    return;
  }
  table.find('.wdform_section').each(function (col_index, column) {
    var to_col = to.find('.wdform_section').eq(col_index);
    if (!to_col.length) {
      to.find('.wdform_row_empty').before(column);
    }
    else {
      jQuery(column).find('.wdform_column:not(:empty)').each(function (row_index, row) {
        to_col.append(row);
      });
    }
  });
  table.remove();

  refresh_pages(id);
  all_sortable_events();
}

function make_page_steps_front() {
  destroyChildren(document.getElementById("pages"));
  show_title = document.getElementById('el_show_title_input').checked;
  k = 0;

  jQuery('.wdform-page-and-images').each(function () {
    var index = jQuery(this).find('.wdform_page').attr('id');
    j = index.split("form_id_tempform_view")[1];

    if (document.getElementById('form_id_tempform_view' + j).getAttribute('page_title')) {
      w_pages = document.getElementById('form_id_tempform_view' + j).getAttribute('page_title');
    }
    else {
      w_pages = "";
    }
    k++;

    page_number = document.createElement('span');
    page_number.setAttribute('id', 'page_' + j);
    page_number.setAttribute('onClick', 'generate_page_nav("' + j + '")');
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

    document.getElementById("pages").appendChild(page_number);
  });
}

function make_page_percentage_front() {
  destroyChildren(document.getElementById("pages"));
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
  jQuery('.wdform-page-and-images').each(function () {
    var index = jQuery(this).find('.wdform_page').attr('id');
    j = index.split("form_id_tempform_view")[1];

    if (document.getElementById('form_id_tempform_view' + j).getAttribute('page_title')) {
      w_pages = document.getElementById('form_id_tempform_view' + j).getAttribute('page_title');
    }
    else {
      w_pages = "";
    }
    k++;

    if (j == form_view) {
      if (show_title) {
        var cur_page_title = document.createElement('span');
        if (k == 1) {
          cur_page_title.style.paddingLeft = "30px";
        }
        else {
          cur_page_title.style.paddingLeft = "5px";
        }
        cur_page_title.innerHTML = w_pages;
      }
      page_number = k;
    }
  });
  b.innerHTML = Math.round(((page_number - 1) / k) * 100) + '%';
  div.style.width = ((page_number - 1) / k) * 100 + '%';
  div_parent.appendChild(div);
  if (cur_page_title) {
    div_parent.appendChild(cur_page_title);
  }
  document.getElementById("pages").appendChild(div_parent);
}

function make_page_none_front() {
  var no_pagbar = document.createElement('div');
  no_pagbar.innerHTML = "NO PAGE BAR";

  jQuery('#pages').empty();
  jQuery('#pages').append(no_pagbar);
}

function generate_page_bar() {
	fm_need_enable = false;
  el_page_navigation();
  add(0, false);
	fm_need_enable = true;
}

function remove_add_(id)
{
			attr_name= new Array();
			attr_value= new Array();
			var input = document.getElementById(id); 
			atr=input.attributes;
			for(v=0;v<30;v++)
				if(atr[v] )
				{
					if(atr[v].name.indexOf("add_")==0)
					{
						attr_name.push(atr[v].name.replace('add_',''));
						attr_value.push(atr[v].value);
						input.removeAttribute(atr[v].name);
						v--;
					}
				}
			for(v=0;v<attr_name.length; v++)
			{
				input.setAttribute(attr_name[v],attr_value[v])
			}
}

function duplicate( id, e ) {
	jQuery("#wdform_field" + id).closest(".wdform_column").after("<div id='cur_column' class='wdform_column'></div>");
	//document.getElementById('pos_end').checked = true;
	type = document.getElementById("wdform_field" + id).getAttribute('type');
	//////////////////////////////parameter take
	if ( document.getElementById(id + '_element_labelform_id_temp').innerHTML ) {
		w_field_label = document.getElementById(id + '_element_labelform_id_temp').innerHTML;
	}
	labels = all_labels();
	m = 0;
	t = true;
	if ( type != "type_section_break" ) {
		while ( t ) {
			m++;
			for ( k = 0; k < labels.length; k++ ) {
				t = true;
				if ( labels[k] == w_field_label + '(' + m + ')' ) {
					break;
				}
				t = false;
			}
		}
		w_field_label = w_field_label + '(' + m + ')';
	}
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
	if ( document.getElementById(id + '_label_sectionform_id_temp') ) {
		if ( document.getElementById(id + '_label_sectionform_id_temp').style.display == "block" ) {
			w_field_label_pos = "top";
		}
		else {
			w_field_label_pos = "left";
		}
	}
	if ( document.getElementById(id + "_elementform_id_temp") ) {
		s = document.getElementById(id + "_elementform_id_temp").style.width;
		w_size = s.substring(0, s.length - 2);
	}
	if ( document.getElementById(id + "_label_sectionform_id_temp") ) {
		s = document.getElementById(id + "_label_sectionform_id_temp").style.width;
		w_field_label_size = s.substring(0, s.length - 2);
	}
	if ( document.getElementById(id + "_requiredform_id_temp") ) {
		w_required = document.getElementById(id + "_requiredform_id_temp").value;
	}
	if ( document.getElementById(id + "_uniqueform_id_temp") ) {
		w_unique = document.getElementById(id + "_uniqueform_id_temp").value;
	}
	if ( document.getElementById(id + '_label_sectionform_id_temp') ) {
		w_class = document.getElementById(id + '_label_sectionform_id_temp').getAttribute("class");
		if ( !w_class ) {
			w_class = "";
		}
	}
	switch ( type ) {
		case 'type_editor': {
			w_editor = document.getElementById("wdform_field" + id).innerHTML;
			type_editor(gen, w_editor);
			break;
		}
		case 'type_section_break': {
			w_editor = document.getElementById(id + "_element_sectionform_id_temp").innerHTML;
			type_section_break(gen, w_editor);
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
			type_text(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_required, w_regExp_status, w_regExp_value, w_regExp_common, w_regExp_arg, w_regExp_alert, w_unique, w_attr_name, w_attr_value, w_readonly);
			break;
		}
		case 'type_number': {
			w_first_val = document.getElementById(id + "_elementform_id_temp").value;
			w_title = document.getElementById(id + "_elementform_id_temp").title;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_number(gen, w_field_label, w_field_label_size, w_field_label_pos, w_size, w_first_val, w_title, w_required, w_unique, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_password': {
			w_placeholder_value = document.getElementById(id + "_elementform_id_temp").placeholder;
			w_verification = document.getElementById(id + "_verification_id_temp").value;
			if ( document.getElementById(id + '_1_element_labelform_id_temp').innerHTML ) {
				w_verification_label = document.getElementById(id + '_1_element_labelform_id_temp').innerHTML;
			}
			else {
				w_verification_label = " ";
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_password(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_required, w_unique, w_class, w_verification, w_verification_label, w_placeholder_value, w_attr_name, w_attr_value);
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
			type_textarea(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_size_h, w_first_val, w_characters_limit, w_title, w_required, w_unique, w_class, w_attr_name, w_attr_value);
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
			type_wdeditor(gen, w_field_label, w_field_label_size, w_field_label_pos, w_size_w, w_size_h, w_title, w_required, w_class, w_attr_name, w_attr_value);
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
			type_phone(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_mini_labels, w_required, w_unique, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_phone_new': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_first_val = document.getElementById(id + "_elementform_id_temp").value;
			w_top_country = document.getElementById(id + "_elementform_id_temp").getAttribute("top-country");
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_phone_new(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_top_country, w_required, w_unique, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_name': {
			if ( document.getElementById(id + "_enable_fieldsform_id_temp") ) {
				w_name_format = "normal";
				w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
				w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
				var title_middle = ['title', 'middle'];
				for ( var l = 0; l < 2; l++ ) {
					w_first_val.push(document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp') ? document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp').value : '');
					w_title.push(document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp') ? document.getElementById(id + '_element_' + title_middle[l] + 'form_id_temp').title : '');
				}
			}
			else {
				if ( document.getElementById(id + '_element_middleform_id_temp') ) {
					w_name_format = "extended";
				}
				else {
					w_name_format = "normal";
				}
				if ( w_name_format == "normal" ) {
					w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value];
					w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title];
				}
				else {
					w_first_val = [document.getElementById(id + "_element_firstform_id_temp").value, document.getElementById(id + "_element_lastform_id_temp").value, document.getElementById(id + "_element_titleform_id_temp").value, document.getElementById(id + "_element_middleform_id_temp").value];
					w_title = [document.getElementById(id + "_element_firstform_id_temp").title, document.getElementById(id + "_element_lastform_id_temp").title, document.getElementById(id + "_element_titleform_id_temp").title, document.getElementById(id + "_element_middleform_id_temp").title];
				}
			}
			if ( document.getElementById(id + "_mini_label_title") ) {
				w_mini_title = document.getElementById(id + "_mini_label_title").innerHTML;
			}
			else {
				w_mini_title = "Title";
			}
			if ( document.getElementById(id + "_mini_label_middle") ) {
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
			type_name(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_title, w_mini_labels, w_size, w_name_format, w_required, w_unique, w_class, w_attr_name, w_attr_value, w_name_fields, w_autofill);
			break;
		}
		case 'type_paypal_price': {
			w_first_val = [document.getElementById(id + "_element_dollarsform_id_temp").value, document.getElementById(id + "_element_centsform_id_temp").value];
			w_title = [document.getElementById(id + "_element_dollarsform_id_temp").title, document.getElementById(id + "_element_centsform_id_temp").title];
			if ( document.getElementById(id + "_td_name_cents").style.display == "none" ) {
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
			type_paypal_price(gen, w_field_label, w_field_label_size, w_field_label_pos, w_first_val, w_title, w_mini_labels, w_size, w_required, w_hide_cents, w_class, w_attr_name, w_attr_value, w_range_min, w_range_max);
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
			if ( document.getElementById(id + "_td_name_currency").style.display == "none" ) {
				w_currency = 'yes';
			}
			else {
				w_currency = 'no';
			}
			type_paypal_price_new(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_first_val, w_title, w_size, w_required, w_class, w_attr_name, w_attr_value, w_range_min, w_range_max, w_readonly, w_currency);
			break;
		}
		case 'type_address': {
			s = document.getElementById(id + "_div_address").style.width;
			w_size = s.substring(0, s.length - 2);
			if ( document.getElementById(id + "_mini_label_street1") ) {
				w_street1 = document.getElementById(id + "_mini_label_street1").innerHTML;
			}
			else {
				w_street1 = document.getElementById(id + "_street1form_id_temp").value;
			}
			if ( document.getElementById(id + "_mini_label_street2") ) {
				w_street2 = document.getElementById(id + "_mini_label_street2").innerHTML;
			}
			else {
				w_street2 = document.getElementById(id + "_street2form_id_temp").value;
			}
			if ( document.getElementById(id + "_mini_label_city") ) {
				w_city = document.getElementById(id + "_mini_label_city").innerHTML;
			}
			else {
				w_city = document.getElementById(id + "_cityform_id_temp").value;
			}
			if ( document.getElementById(id + "_mini_label_state") ) {
				w_state = document.getElementById(id + "_mini_label_state").innerHTML;
			}
			else {
				w_state = document.getElementById(id + "_stateform_id_temp").value;
			}
			if ( document.getElementById(id + "_mini_label_postal") ) {
				w_postal = document.getElementById(id + "_mini_label_postal").innerHTML;
			}
			else {
				w_postal = document.getElementById(id + "_postalform_id_temp").value;
			}
			if ( document.getElementById(id + "_mini_label_country") ) {
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
			type_address(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_mini_labels, w_disabled_fields, w_required, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_submitter_mail': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_first_val = document.getElementById(id + "_elementform_id_temp").value;
			w_title = document.getElementById(id + "_elementform_id_temp").title;
			w_autofill = document.getElementById(id + "_autofillform_id_temp").value;
			w_verification = document.getElementById(id + "_verification_id_temp").value;
			w_verification_placeholder = document.getElementById(id + "_1_elementform_id_temp").title;
			if ( document.getElementById(id + '_1_element_labelform_id_temp').innerHTML ) {
				w_verification_label = document.getElementById(id + '_1_element_labelform_id_temp').innerHTML;
			}
			else {
				w_verification_label = " ";
			}
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_submitter_mail(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_first_val, w_title, w_required, w_unique, w_class, w_verification, w_verification_label, w_verification_placeholder, w_attr_name, w_attr_value, w_autofill);
			break;
		}
		case 'type_checkbox': {
			w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
			w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
			w_limit_choice = document.getElementById(id + "_limitchoice_numform_id_temp").value;
			w_limit_choice_alert = document.getElementById(id + "_limitchoicealert_numform_id_temp").value;
			if ( document.getElementById(id + "_rowcol_numform_id_temp").value ) {
				if ( document.getElementById(id + '_table_little').getAttribute('for_hor') ) {
					w_flow = "hor"
				}
				else {
					w_flow = "ver";
				}
				w_rowcol = document.getElementById(id + "_rowcol_numform_id_temp").value;
			}
			else {
				if ( document.getElementById(id + '_hor') ) {
					w_flow = "hor"
				}
				else {
					w_flow = "ver";
				}
				w_rowcol = 1;
			}
			v = 0;
			if ( w_flow == "ver" ) {
				var table_little = document.getElementById(id + '_table_little');
				for ( k = 0; k < table_little.childNodes.length; k++ ) {
					var td_little = table_little.childNodes[k];
					for ( m = 0; m < td_little.childNodes.length; m++ ) {
						var idi = td_little.childNodes[m].getAttribute('idi');
						if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') ) {
							if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1' ) {
								w_allow_other_num = t;
							}
						}
						w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
						w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
						w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
						if ( document.getElementById(id + "_label_element" + idi).getAttribute('where') ) {
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
				for ( k = 0; k < td_max.length; k++ ) {
					for ( m = 0; m < tr_little.length; m++ ) {
						if ( tr_little[m].childNodes[k] ) {
							var td_little = tr_little[m].childNodes[k];
							var idi = td_little.getAttribute('idi');
							if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') ) {
								if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1' ) {
									w_allow_other_num = t;
								}
							}
							w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
							w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
							w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
							if ( document.getElementById(id + "_label_element" + idi).getAttribute('where') ) {
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
			if ( document.getElementById(id + "_option_left_right") ) {
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
			type_checkbox(gen, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_checked, w_rowcol, w_limit_choice, w_limit_choice_alert, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_value_disabled, w_choices_value, w_choices_params, w_use_for_submission);
			break;
		}
		case 'type_paypal_checkbox': {
			if ( document.getElementById(id + '_hor') ) {
				w_flow = "hor"
			}
			else {
				w_flow = "ver";
			}
			w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
			w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
			v = 0;
			for ( k = 0; k < 100; k++ ) {
				if ( document.getElementById(id + "_elementform_id_temp" + k) ) {
					if ( document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') ) {
						if ( document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') == '1' ) {
							w_allow_other_num = t;
						}
					}
					w_choices[t] = document.getElementById(id + "_label_element" + k).innerHTML;
					w_choices_price[t] = document.getElementById(id + "_elementform_id_temp" + k).value;
					w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + k).checked;
					if ( document.getElementById(id + "_label_element" + k).getAttribute('where') ) {
						w_choices_params[t] = document.getElementById(id + "_label_element" + k).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + k).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + k).getAttribute('db_info');
					}
					else {
						w_choices_params[t] = '';
					}
					t++;
					v = k;
				}
				if ( document.getElementById(id + "_propertyform_id_temp" + k) ) {
					w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
					if ( document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length ) {
						w_property_values[w_property.length - 1] = new Array();
						for ( m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++ ) {
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
			if ( document.getElementById(id + "_element_quantityform_id_temp") ) {
				w_quantity = 'yes';
				w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
			}
			if ( document.getElementById(id + "_option_left_right") ) {
				w_field_option_pos = document.getElementById(id + "_option_left_right").value;
			}
			else {
				w_field_option_pos = 'left';
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp' + v);
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_paypal_checkbox(gen, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_property, w_property_values, w_quantity, w_quantity_value, w_choices_params);
			break;
		}
		case 'type_radio': {
			w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
			w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
			if ( document.getElementById(id + "_rowcol_numform_id_temp").value ) {
				if ( document.getElementById(id + '_table_little').getAttribute('for_hor') ) {
					w_flow = "hor"
				}
				else {
					w_flow = "ver";
				}
				w_rowcol = document.getElementById(id + "_rowcol_numform_id_temp").value;
			}
			else {
				if ( document.getElementById(id + '_table_little').getAttribute('for_hor') ) {
					w_flow = "hor"
				}
				else {
					w_flow = "ver";
				}
				w_rowcol = 1;
			}
			v = 0;
			if ( w_flow == "ver" ) {
				var table_little = document.getElementById(id + '_table_little');
				for ( k = 0; k < table_little.childNodes.length; k++ ) {
					var td_little = table_little.childNodes[k];
					for ( m = 0; m < td_little.childNodes.length; m++ ) {
						var idi = td_little.childNodes[m].getAttribute('idi');
						if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') ) {
							if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1' ) {
								w_allow_other_num = t;
							}
						}
						w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
						w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
						w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
						if ( document.getElementById(id + "_label_element" + idi).getAttribute('where') ) {
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
				for ( k = 0; k < td_max.length; k++ ) {
					for ( m = 0; m < tr_little.length; m++ ) {
						if ( tr_little[m].childNodes[k] ) {
							var td_little = tr_little[m].childNodes[k];
							var idi = td_little.getAttribute('idi');
							if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') ) {
								if ( document.getElementById(id + "_elementform_id_temp" + idi).getAttribute('other') == '1' ) {
									w_allow_other_num = t;
								}
							}
							w_choices[t] = document.getElementById(id + "_label_element" + idi).innerHTML;
							w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + idi).checked;
							w_choices_value[t] = document.getElementById(id + "_elementform_id_temp" + idi).value;
							if ( document.getElementById(id + "_label_element" + idi).getAttribute('where') ) {
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
			if ( document.getElementById(id + "_option_left_right") ) {
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
			type_radio(gen, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_checked, w_rowcol, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_value_disabled, w_choices_value, w_choices_params,w_use_for_submission);
			break;
		}
		case 'type_paypal_radio': {
			if ( document.getElementById(id + '_hor') ) {
				w_flow = "hor"
			}
			else {
				w_flow = "ver";
			}
			w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
			w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
			v = 0;
			for ( k = 0; k < 100; k++ ) {
				if ( document.getElementById(id + "_elementform_id_temp" + k) ) {
					if ( document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') ) {
						if ( document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') == '1' ) {
							w_allow_other_num = t;
						}
					}
					w_choices[t] = document.getElementById(id + "_label_element" + k).innerHTML;
					w_choices_price[t] = document.getElementById(id + "_elementform_id_temp" + k).value;
					w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + k).checked;
					if ( document.getElementById(id + "_label_element" + k).getAttribute('where') ) {
						w_choices_params[t] = document.getElementById(id + "_label_element" + k).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + k).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + k).getAttribute('db_info');
					}
					else {
						w_choices_params[t] = '';
					}
					t++;
					v = k;
				}
				if ( document.getElementById(id + "_propertyform_id_temp" + k) ) {
					w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
					if ( document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length ) {
						w_property_values[w_property.length - 1] = new Array();
						for ( m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++ ) {
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
			if ( document.getElementById(id + "_element_quantityform_id_temp") ) {
				w_quantity = 'yes';
				w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
			}
			if ( document.getElementById(id + "_option_left_right") ) {
				w_field_option_pos = document.getElementById(id + "_option_left_right").value;
			}
			else {
				w_field_option_pos = 'left';
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp' + v);
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_paypal_radio(gen, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_property, w_property_values, w_quantity, w_quantity_value, w_choices_params);
			break;
		}
		case 'type_paypal_shipping': {
			if ( document.getElementById(id + '_hor') ) {
				w_flow = "hor"
			}
			else {
				w_flow = "ver";
			}
			w_randomize = document.getElementById(id + "_randomizeform_id_temp").value;
			w_allow_other = document.getElementById(id + "_allow_otherform_id_temp").value;
			v = 0;
			for ( k = 0; k < 100; k++ ) {
				if ( document.getElementById(id + "_elementform_id_temp" + k) ) {
					if ( document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') ) {
						if ( document.getElementById(id + "_elementform_id_temp" + k).getAttribute('other') == '1' ) {
							w_allow_other_num = t;
						}
					}
					w_choices[t] = document.getElementById(id + "_label_element" + k).innerHTML;
					w_choices_price[t] = document.getElementById(id + "_elementform_id_temp" + k).value;
					w_choices_checked[t] = document.getElementById(id + "_elementform_id_temp" + k).checked;
					if ( document.getElementById(id + "_label_element" + k).getAttribute('where') ) {
						w_choices_params[t] = document.getElementById(id + "_label_element" + k).getAttribute('where') + '[where_order_by]' + document.getElementById(id + "_label_element" + k).getAttribute('order_by') + '[db_info]' + document.getElementById(id + "_label_element" + k).getAttribute('db_info');
					}
					else {
						w_choices_params[t] = '';
					}
					t++;
					v = k;
				}
				if ( document.getElementById(id + "_propertyform_id_temp" + k) ) {
					w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
					if ( document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length ) {
						w_property_values[w_property.length - 1] = new Array();
						for ( m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++ ) {
							w_property_values[w_property.length - 1].push(document.getElementById(id + "_propertyform_id_temp" + k).childNodes[m].value);
						}
					}
					else {
						w_property_values.push('');
					}
				}
			}
			if ( document.getElementById(id + "_option_left_right") ) {
				w_field_option_pos = document.getElementById(id + "_option_left_right").value;
			}
			else {
				w_field_option_pos = 'left';
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp' + v);
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_paypal_shipping(gen, w_field_label, w_field_label_size, w_field_label_pos, w_field_option_pos, w_hide_label, w_flow, w_choices, w_choices_price, w_choices_checked, w_required, w_randomize, w_allow_other, w_allow_other_num, w_class, w_attr_name, w_attr_value, w_property, w_property_values, w_choices_params);
			break;
		}
		case 'type_paypal_total': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_size = jQuery('#' + id + "paypal_totalform_id_temp").css('width') ? jQuery('#' + id + "paypal_totalform_id_temp").css('width').substring(0, jQuery('#' + id + "paypal_totalform_id_temp").css('width').length - 2) : '300';
			w_hide_total_currency = document.getElementById(id + "_hide_totalcurrency_id_temp").value;
			type_paypal_total(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_class, w_size, w_hide_total_currency);
			break;
		}
		case 'type_stripe': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			type_stripe(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_class);
			break;
		}
		case 'type_star_rating': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_star_amount = document.getElementById(id + "_star_amountform_id_temp").value;
			w_field_label_col = document.getElementById(id + "_star_colorform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_star_rating(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_label_col, w_star_amount, w_required, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_scale_rating': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_mini_labels = [document.getElementById(id + "_mini_label_worst").innerHTML, document.getElementById(id + "_mini_label_best").innerHTML];
			w_scale_amount = document.getElementById(id + "_scale_amountform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_scale_rating(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_mini_labels, w_scale_amount, w_required, w_class, w_attr_name, w_attr_value);
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
			type_spinner(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_width, w_field_min_value, w_field_max_value, w_field_step, w_field_value, w_required, w_class, w_attr_name, w_attr_value);
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
			type_slider(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_width, w_field_min_value, w_field_max_value, w_field_step, w_field_value, w_required, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_range': {
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_field_range_width = document.getElementById(id + "_range_widthform_id_temp").value;
			w_field_range_step = document.getElementById(id + "_range_stepform_id_temp").value;
			w_field_value1 = document.getElementById(id + "_elementform_id_temp0").getAttribute("aria-valuenow");
			w_field_value2 = document.getElementById(id + "_elementform_id_temp1").getAttribute("aria-valuenow");
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			w_mini_labels = [document.getElementById(id + "_mini_label_from").innerHTML, document.getElementById(id + "_mini_label_to").innerHTML];
			type_range(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_range_width, w_field_range_step, w_field_value1, w_field_value2, w_mini_labels, w_required, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_grading': {
			w_total = document.getElementById(id + "_grading_totalform_id_temp").value;
			w_items = [];
			for ( k = 0; k < 100; k++ ) {
				if ( document.getElementById(id + "_label_elementform_id_temp" + k) ) {
					w_items.push(document.getElementById(id + "_label_elementform_id_temp" + k).innerHTML);
				}
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_grading(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_items, w_total, w_required, w_class, w_attr_name, w_attr_value);
			refresh_grading_items(id);
			break;
		}
		case 'type_matrix': {
			w_rows = [];
			w_rows[0] = "";
			for ( k = 1; k < 100; k++ ) {
				if ( document.getElementById(id + "_label_elementform_id_temp" + k + "_0") ) {
					w_rows.push(document.getElementById(id + "_label_elementform_id_temp" + k + "_0").innerHTML);
				}
			}
			w_columns = [];
			w_columns[0] = "";
			for ( k = 1; k < 100; k++ ) {
				if ( document.getElementById(id + "_label_elementform_id_temp0_" + k) ) {
					w_columns.push(document.getElementById(id + "_label_elementform_id_temp0_" + k).innerHTML);
				}
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			w_field_input_type = document.getElementById(id + "_input_typeform_id_temp").value;
			w_textbox_size = document.getElementById(id + "_textbox_sizeform_id_temp") ? document.getElementById(id + "_textbox_sizeform_id_temp").value : '100';
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_matrix(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_field_input_type, w_rows, w_columns, w_required, w_class, w_attr_name, w_attr_value, w_textbox_size);
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
			if ( document.getElementById(id + '_ssform_id_temp') ) {
				w_ss = document.getElementById(id + '_ssform_id_temp').value;
				w_sec = "1";
				w_mini_label_ss = document.getElementById(id + '_mini_label_ss').innerHTML;
			}
			else {
				w_ss = "";
				w_sec = "0";
				w_mini_label_ss = '';
			}
			if ( document.getElementById(id + '_am_pm_select') ) {
				w_am_pm = document.getElementById(id + '_am_pmform_id_temp').value;
				w_time_type = "12";
				w_mini_labels = [document.getElementById(id + '_mini_label_hh').innerHTML, document.getElementById(id + '_mini_label_mm').innerHTML, w_mini_label_ss, document.getElementById(id + '_mini_label_am_pm').innerHTML];
			}
			else {
				w_am_pm = 0;
				w_time_type = "24";
				w_mini_labels = [document.getElementById(id + '_mini_label_hh').innerHTML, document.getElementById(id + '_mini_label_mm').innerHTML, w_mini_label_ss, 'AM/PM'];
			}
			type_time(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_time_type, w_am_pm, w_sec, w_hh, w_mm, w_ss, w_mini_labels, w_required, w_class, w_attr_name, w_attr_value);
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
			type_date(gen, w_field_label, w_field_label_size, w_field_label_pos, w_date, w_required, w_class, w_format, w_but_val, w_attr_name, w_attr_value, w_disable_past_days);
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
			type_date_new(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_date, w_required, w_show_image, w_class, w_format, w_start_day, w_default_date, w_min_date, w_max_date, w_invalid_dates, w_show_days, w_hide_time, w_but_val, w_attr_name, w_attr_value, w_disable_past_days);
			break;
		}
		case 'type_date_range': {
			atrs = return_attributes(id + '_elementform_id_temp0');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			w_date = '';
			w_format = document.getElementById(id + '_buttonform_id_temp').getAttribute("format");
			w_but_val = document.getElementById(id + '_buttonform_id_temp').value;
			w_default_date_start = document.getElementById(id + '_default_date_id_temp_start').value;
			w_default_date_end = document.getElementById(id + '_default_date_id_temp_end').value;
			w_min_date = document.getElementById(id + '_min_date_id_temp').value;
			w_start_day = document.getElementById(id + '_start_dayform_id_temp').value;
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
			type_date_range(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_date, w_required, w_show_image, w_class, w_format, w_start_day, w_default_date_start, w_default_date_end, w_min_date, w_max_date, w_invalid_dates, w_show_days, w_hide_time, w_but_val, w_attr_name, w_attr_value, w_disable_past_days);
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
			type_date_fields(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_day, w_month, w_year, w_day_type, w_month_type, w_year_type, w_day_label, w_month_label, w_year_label, w_day_size, w_month_size, w_year_size, w_required, w_class, w_from, w_to, w_min_day, w_min_month, w_min_year, w_min_dob_alert, w_divider, w_attr_name, w_attr_value);
			break;
		}
		case 'type_own_select': {
			jQuery('#' + id + '_elementform_id_temp option').each(function () {
				w_choices[t] = jQuery(this).html();
				w_choices_value[t] = jQuery(this).val();
				w_choices_checked[t] = jQuery(this)[0].selected;
				if ( jQuery(this).attr('where') ) {
					w_choices_params[t] = jQuery(this).attr('where') + '[where_order_by]' + jQuery(this).attr('order_by') + '[db_info]' + jQuery(this).attr('db_info');
				}
				else {
					w_choices_params[t] = '';
				}
				if ( jQuery(this).val() ) {
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
			type_own_select(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_choices, w_choices_checked, w_required, w_value_disabled, w_class, w_attr_name, w_attr_value, w_choices_disabled, w_choices_value, w_choices_params, w_use_for_submission);
			break;
		}
		case 'type_paypal_select': {
			jQuery('#' + id + '_elementform_id_temp option').each(function () {
				w_choices[t] = jQuery(this).html();
				w_choices_price[t] = jQuery(this).val();
				w_choices_checked[t] = jQuery(this)[0].selected;
				if ( jQuery(this).attr('where') ) {
					w_choices_params[t] = jQuery(this).attr('where') + '[where_order_by]' + jQuery(this).attr('order_by') + '[db_info]' + jQuery(this).attr('db_info');
				}
				else {
					w_choices_params[t] = '';
				}
				if ( jQuery(this)[0].value == "" ) {
					w_choices_disabled[t] = true;
				}
				else {
					w_choices_disabled[t] = false;
				}
				t++;
			});
			for ( k = 0; k < 100; k++ ) {
				if ( document.getElementById(id + "_propertyform_id_temp" + k) ) {
					w_property.push(document.getElementById(id + "_property_label_form_id_temp" + k).innerHTML);
					if ( document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length ) {
						w_property_values[w_property.length - 1] = new Array();
						for ( m = 0; m < document.getElementById(id + "_propertyform_id_temp" + k).childNodes.length; m++ ) {
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
			if ( document.getElementById(id + "_element_quantityform_id_temp") ) {
				w_quantity = 'yes';
				w_quantity_value = document.getElementById(id + "_element_quantityform_id_temp").value;
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_paypal_select(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_size, w_choices, w_choices_price, w_choices_checked, w_required, w_quantity, w_quantity_value, w_class, w_attr_name, w_attr_value, w_choices_disabled, w_property, w_property_values, w_choices_params);
			break;
		}
		case 'type_country': {
			w_countries = [];
			select_ = document.getElementById(id + '_elementform_id_temp');
			n = select_.childNodes.length;
			for ( i = 0; i < n; i++ ) {
				w_countries.push(select_.childNodes[i].value);
			}
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_country(gen, w_field_label, w_field_label_size, w_hide_label, w_countries, w_field_label_pos, w_size, w_required, w_class, w_attr_name, w_attr_value);
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
			type_file_upload(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_destination, w_extension, w_max_size, w_required, w_multiple, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_map': {
			w_lat = [];
			w_long = [];
			w_info = [];
			w_center_x = document.getElementById(id + "_elementform_id_temp").getAttribute("center_x");
			w_center_y = document.getElementById(id + "_elementform_id_temp").getAttribute("center_y");
			w_zoom = document.getElementById(id + "_elementform_id_temp").getAttribute("zoom");
			w_width = parseInt(document.getElementById(id + "_elementform_id_temp").style.width);
			w_height = parseInt(document.getElementById(id + "_elementform_id_temp").style.height);
			for ( j = 0; j <= 20; j++ ) {
				if ( document.getElementById(id + "_elementform_id_temp").getAttribute("lat" + j) ) {
					w_lat.push(document.getElementById(id + "_elementform_id_temp").getAttribute("lat" + j));
					w_long.push(document.getElementById(id + "_elementform_id_temp").getAttribute("long" + j));
					w_info.push(document.getElementById(id + "_elementform_id_temp").getAttribute("info" + j));
				}
			}
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_map(gen, w_center_x, w_center_y, w_long, w_lat, w_zoom, w_width, w_height, w_class, w_info, w_attr_name, w_attr_value);
			break;
		}
		case 'type_mark_map': {
			w_info = document.getElementById(id + "_elementform_id_temp").getAttribute("info0");
			w_long = document.getElementById(id + "_elementform_id_temp").getAttribute("long0");
			w_lat = document.getElementById(id + "_elementform_id_temp").getAttribute("lat0");
			w_zoom = document.getElementById(id + "_elementform_id_temp").getAttribute("zoom");
			w_width = parseInt(document.getElementById(id + "_elementform_id_temp").style.width);
			w_height = parseInt(document.getElementById(id + "_elementform_id_temp").style.height);
			w_center_x = document.getElementById(id + "_elementform_id_temp").getAttribute("center_x");
			w_center_y = document.getElementById(id + "_elementform_id_temp").getAttribute("center_y");
			w_hide_label = document.getElementById(id + "_hide_labelform_id_temp").value;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_mark_map(gen, w_field_label, w_field_label_size, w_field_label_pos, w_hide_label, w_center_x, w_center_y, w_long, w_lat, w_zoom, w_width, w_height, w_class, w_info, w_attr_name, w_attr_value);
			break;
		}
		case 'type_submit_reset': {
			atrs = return_attributes(id + '_element_submitform_id_temp');
			w_act = !(document.getElementById(id + "_element_resetform_id_temp").style.display == "none");
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			w_submit_title = document.getElementById(id + "_element_submitform_id_temp").value;
			w_reset_title = document.getElementById(id + "_element_resetform_id_temp").value;
			type_submit_reset(gen, w_submit_title, w_reset_title, w_class, w_act, w_attr_name, w_attr_value);
			break;
		}
		case 'type_button': {
			w_title = new Array();
			w_func = new Array();
			t = 0;
			v = 0;
			for ( k = 0; k < 100; k++ ) {
				if ( document.getElementById(id + "_elementform_id_temp" + k) ) {
					w_title[t] = document.getElementById(id + "_elementform_id_temp" + k).value;
					w_func[t] = document.getElementById(id + "_elementform_id_temp" + k).getAttribute("onclick");
					t++;
					v = k;
				}
			}
			atrs = return_attributes(id + '_elementform_id_temp' + v);
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_button(gen, w_title, w_func, w_class, w_attr_name, w_attr_value);
			break;
		}
		case 'type_hidden': {
			w_value = document.getElementById(id + "_elementform_id_temp").value;
			w_name = document.getElementById(id + "_elementform_id_temp").name;
			atrs = return_attributes(id + '_elementform_id_temp');
			w_attr_name = atrs[0];
			w_attr_value = atrs[1];
			type_hidden(gen, w_name, w_value, w_attr_name, w_attr_value);
			break;
		}
		case 'type_signature': {
			var w_hide_label = document.getElementById(id + '_hide_labelform_id_temp').value;
			var w_width = document.getElementById(id + '_canvasform_id_temp').style.width;
			var w_height = document.getElementById(id + '_canvasform_id_temp').style.height;
			var w_destination = document.getElementById(id + '_destination').value.replace('***destinationskizb' + id + '***', '').replace('***destinationverj' + id + '***', '');
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
			type_signature( gen, params );
			break;
		}
	}
	fm_need_enable = false;
	add(0, false);
	fm_need_enable = true;
	if ( typeof e != "undefined" ) {
		e.stopPropagation();
		e.preventDefault();
	}
}
