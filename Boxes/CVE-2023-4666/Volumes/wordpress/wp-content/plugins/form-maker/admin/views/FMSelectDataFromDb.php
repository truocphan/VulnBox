<?php

/**
 * Class FMViewSelect_data_from_db
 */
class FMViewSelect_data_from_db extends FMAdminView {
  /**
   * Display.
   *
   * @param array $params
   */
  private $fm_nonce = null;
  public function display( $params = array() ) {
    $this->fm_nonce = wp_create_nonce('fm_ajax_nonce');
    wp_print_scripts('jquery');
    wp_print_styles(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
    wp_print_styles(WDFMInstance(self::PLUGIN)->handle_prefix . '-jquery-ui');
    $id = $params['id'];
    $form_id = $params['form_id'];
    $field_id = $params['field_id'];
    $field_type = $params['field_type'];
    $value_disabled = $params['value_disabled'];
    ?>
    <script>
      function insert_field() {
      }
      function connect() {
        jQuery("input[type='radio']").attr('disabled', '');
        jQuery(".connect").attr('disabled', '');
        jQuery('#struct').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
        jQuery.ajax({
          type: "POST",
          url: "<?php echo add_query_arg(array(
                                           'action' => 'select_data_from_db' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                           'form_id' => $form_id,
                                           'field_type' => $field_type,
                                           'task' => 'db_tables',
                                           'width' => '1000',
                                           'height' => '500',
                                           'nonce' => $this->fm_nonce,
                                           'TB_iframe' => '1',
                                         ), admin_url('admin-ajax.php')); ?>",
          data: 'con_type=' + jQuery('input[name=con_type]:checked').val() + '&con_method=' + jQuery('input[name=con_method]:checked').val() + '&host=' + jQuery('#host_rem').val() + '&port=' + jQuery('#port_rem').val() + '&username=' + jQuery('#username_rem').val() + '&password=' + encodeURIComponent( jQuery('#password_rem').val()) + '&database=' + jQuery('#database_rem').val() + '&field_type=' + jQuery('#field_type').val() + '&format=row',
          success: function (data) {
            jQuery("#struct").removeClass("fm_loading");
            if (data == 1) {
              jQuery("#struct").html('<div style="font-size: 22px; text-align: center; padding-top: 15px;">Could not connect to MySQL.</div>')
              jQuery(".connect").prop('disabled', false);
              jQuery("input[type='radio']").prop('disabled', false);
            }
            else {
              jQuery("#struct").html(data.replace("<a", "<a target='_blank'"));
            }
          }
        });
      }
      function shh(x) {
        if (x) {
          jQuery(".remote_info").show();
        }
        else {
          jQuery(".remote_info").hide();
        }
      }
    </script>
    <style>
      .c1 {
        padding: 0 10px;
      }

      .main_func {
        font-family: Segoe UI;
      }

      .main_func .admintable {
        width: 520px;
        padding: 10px 0;
        margin-bottom: 15px;
      }

      .main_func .admintable input[type='text'],
      .main_func .admintable input[type='password'] {
        padding: 4px 6px;
        width: 244px;
      }

      .btn.connect, .gen_query {
        background: #4EC0D9;
        width: 78px;
        height: 32px;
        border: 1px solid #4EC0D9;
        border-radius: 0px;
        color: #fff;
        cursor: pointer;
      }

      .select-db-label {
        display: inline-block;
        font-size: 16px;
        width: 206px;

      }

      .select-db-select {
        width: 300px;
      }

      .select-db-op {
        display: inline-block;
        text-align: right;
        font-weight: bold;
        font-size: 18px;
        padding: 5px 0;
      }

      .select-db-op.where {
        width: 272px;
      }

      .select-db-op img {
        float: left;
      }

      .select-db-op.orderby {
        width: 295px;
      }

      #order_by {
        width: 221px;
      }

      img, input[type="button"] {
        cursor: pointer;
      }

      select {
        padding: 4px 6px;
        margin-right: 4px;
      }

      #struct {
        width: 520px;
      }

      #table_struct, .cols > div {
        margin-top: 5px;

      }

      .cols input {
        width: 147px;
        margin: 0px 4px 0 0 !important;
        padding: 4px 6px;
      }

      .select-db-save {
        text-align: right;
        margin-right: 10px;
      }
    </style>
    <div class="c1">
      <div class="main_func">
        <table class="admintable">
          <tr valign="top">
            <td class="key" width="40%">
              <label style="font-size:20px;"> <?php echo __('Connection type', 'form_maker'); ?>: </label>
            </td>
            <td width="50%" style="vertical-align: middle;">
              <input type="radio" name="con_type" id="local" value="local" checked="checked" onclick="shh(false)">
              <label for="local">Local</label>
              <input type="radio" name="con_type" id="remote" value="remote" onclick="shh(true)">
              <label for="remote">Remote</label>
            </td>
            <td width="10%" style="text-align:right;">
              <input type="button" value="Connect" onclick="connect()" class="btn connect">
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td width="40%">Host</td>
            <td>
              <input type="text" name="host" id="host_rem" style="width:150px">
              Port : <input type="text" name="port" id="port_rem" value="3306" style="width:48px">
            </td>
            <td>
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td width="40%">Username</td>
            <td>
              <input type="text" name="username" id="username_rem">
            </td>
            <td>
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td width="40%">Password</td>
            <td>
              <input type="password" name="password" id="password_rem">
            </td>
            <td>
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td width="40%">Database</td>
            <td>
              <input type="text" name="database" id="database_rem">
            </td>
            <td>
            </td>
          </tr>
        </table>
        <div id="struct" style="margin-left:5px;">
        </div>
        <input type="hidden" id="form_id" value="<?php echo esc_attr($id) ?>">
        <input type="hidden" id="form_field_id" value="<?php echo esc_attr($field_id) ?>">
        <input type="hidden" id="field_type" value="<?php echo esc_attr($field_type) ?>">
        <input type="hidden" id="value_disabled" value="<?php echo esc_attr($value_disabled) ?>">
      </div>
    </div>
    <?php
    die();
  }

  /**
   * DB table struct select.
   *
   * @param array $params
   */
  public function db_table_struct_select( $params = array() ) {
    $form_id = $params['form_id'];
    $field_type = $params['field_type'];
    $table_struct = $params['table_struct'];
    $label = $params['label'];
	$html_placeholders = $params['html_placeholders'];
    $cond = '<div id="condid"><select id="sel_condid" style="width: 130px">';
    foreach ( $table_struct as $col ) {
      $cond .= '<option>' . $col->Field . '</option>';
    }
    $cond .= '</select>';
    $cond .= '<select id="op_condid" style="width: 110px"><option value="=" selected="selected">=</option><option value="!=">!=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option><option value="%..%">Like</option><option value="..%">Starts with</option><option value="%..">Ends with</option></select>';
	$cond .= '<input autocomplete="off" id="val_condid" type="text" class="fm-where-input" />';
	$cond .= '<select id="andor_condid" style="visibility: hidden; width:70px;"><option value="AND">AND</option><option value="OR">OR</option></select><img src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/delete.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" onclick="delete_cond(&quot;condid&quot;)" style="vertical-align: middle;"></div>';
    ?>
    <script>
      var selected_field = '';
      var isvisible = 1;
      var cond_id = 1;
      conds = '<?php echo $cond ?>';
      if (window.parent.document.getElementById('el_disable_value') && !window.parent.document.getElementById('el_disable_value').checked) {
        document.getElementById('db_field_value').style.display = "none";
      }
      if (jQuery('#value_disabled').val() == 'no') {
        jQuery('.ch_rad_value_disabled').hide();
      }
		jQuery('.add_cond').on("click", function () {
			jQuery('.cols').append(conds.replace(/condid/g, cond_id++));
			update_vis();
		});

		jQuery('html').click(function () {
			if ( jQuery("#fm-placeholder").css('display') == "block" ) {
				jQuery("#fm-placeholder").hide();
			}
		});

		jQuery('#fm-placeholder').click(function (event) {
          event.stopPropagation();
        });

		jQuery(document).on("click", ".fm-where-input", function(e) {
			e.stopPropagation();
			jQuery("#fm-placeholder").css("top", jQuery(this).offset().top + jQuery(this).height() + 12);
			jQuery("#fm-placeholder").css("left", jQuery(this).offset().left);
			jQuery("#fm-placeholder").slideDown('fast');
			selected_field = this.id;
		});

		function dis(id, x) {
			if (x) {
				jQuery('#' + id).prop('disabled', false);
			}
			else {
				jQuery('#' + id).prop('disabled', true);
			}
		}

		function update_vis() {
			previous = 0;
			for (i = 1; i < cond_id; i++) {
				if (jQuery('#' + i).html()) {
					jQuery('#andor_' + i).css('visibility', 'hidden');
					if (previous) {
						jQuery('#andor_' + previous).css('visibility', 'visible');
					}
					previous = i;
				}
			}
		}

		function insert_placeholder(key) {
			if (!selected_field) {
				return;
			}
			myField = document.getElementById(selected_field);
			if (document.selection) {
				myField.focus();
				sel = document.selection.createRange();
				sel.text = key;
			}
			else {
				if (myField.selectionStart || myField.selectionStart == '0') {
				var startPos = myField.selectionStart;
				var endPos = myField.selectionEnd;
				myField.value = myField.value.substring(0, startPos)
				  + "{" + key + "}"
				  + myField.value.substring(endPos, myField.value.length);
				}
				else {
					myField.value += "{" + key + "}";
				}
			}
		}

		function delete_cond( id ) {
			jQuery('#' + id).remove();
			update_vis();
		}

		function save_query() {
			str = '';
			plugin_url = '<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>';
			plugin_version = '<?php echo WDFMInstance(self::PLUGIN)->plugin_version; ?>';
			product_name = jQuery('#product_name').val();
			product_price = jQuery('#product_price').val();
			con_type = jQuery('input[name=con_type]:checked').val();
			table = jQuery('#tables').val();
			host = jQuery('#host_rem').val();
			port = jQuery('#port_rem').val();
			username = jQuery('#username_rem').val();
			password = jQuery('#password_rem').val();
			database = jQuery('#database_rem').val();
			if (con_type == 'remote') {
			  str += host + "@@@wdfhostwdf@@@" + port + "@@@wdfportwdf@@@" + username + "@@@wdfusernamewdf@@@" + password + "@@@wdfpasswordwdf@@@" + database + "@@@wdfdatabasewdf@@@";
			}
			gen_query();
			var where = jQuery('#where').val();
			var order = jQuery('#order').val();
			var value_disabled = jQuery('#value_disabled').val();
			var num = jQuery("#form_field_id").val();
			var field_type = jQuery("#field_type").val();
			if (product_name || product_price) {
			  jQuery('.c1').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
			  var max_value = 0;
			  window.parent.jQuery('.change_pos').each(function () {
				var value = jQuery(this)[0].id;
				max_value = (value > max_value) ? value : max_value;
			  });
			  max_value = parseInt(max_value) + 1;
			  if (field_type == "checkbox" || field_type == "radio") {
				var attr_table = window.parent.jQuery('#' + 'choices');
				var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
				  '<div class="fm-table-col fm-width-40">' +
				  '<input type="text" class="fm-field-choice" id="el_choices' + max_value + '" value="[' + table + ':' + product_name + ']" disabled="disabled" onKeyUp="change_label(\'' + num + '_label_element' + max_value + '\', this.value); change_in_value(\'' + num + '_elementform_id_temp' + max_value + '\', this.value)" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-40">' +
				  '<input type="text" class="fm-field-choice" id="el_option_value' + max_value + '" value="' + (value_disabled == 'no' ? '[' + table + ':' + product_name + ']' : '[' + table + ':' + product_price + ']') + '" disabled="disabled" onKeyUp="change_label_value(\'' + num + '_elementform_id_temp' + max_value + '\', this.value)" />' +
				  '</div>' +
				  '<input type="hidden" id="el_option_params' + max_value + '" value="' + where + '[where_order_by]' + order + '[db_info]' + '[' + str + ']" />' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_choices' + max_value + '_remove" onClick="remove_choise(' + max_value + ',' + num + ',\'' + field_type + '\')"></span>' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-move-attribute dashicons dashicons-move el_choices_sortable"></span>' +
				  '</div>' +
				  '</div>');
				attr_table.append(attr);
				window.parent["refresh_rowcol"](num, field_type);
				if (field_type == 'checkbox') {
				  window.parent["refresh_attr"](num, 'type_checkbox');
				}
				if (field_type == 'radio') {
				  window.parent["refresh_attr"](num, 'type_radio');
				}
			  }
			  if (field_type == "select") {
				var select_ = window.parent.jQuery('#' + num + '_elementform_id_temp');
				var option = jQuery('<option id="' + num + '_option' + max_value + '" onselect="set_select(\'' + num + '_option' + max_value + '\')" where="' + where + '" order_by="' + order + '" db_info="[' + str + ']"' + (value_disabled == 'no' ? ' value="[' + table + ':' + product_name + ']"' : ' value="[' + table + ':' + product_price + ']"') + '>[' + table + ':' + product_name + ']</option>');
				select_.append(option);
				var attr_table = window.parent.jQuery('#' + 'choices');
				var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
				  '<div class="fm-table-col fm-width-30">' +
				  '<input type="text" class="fm-field-choice" id="el_option' + max_value + '" value="[' + table + ':' + product_name + ']" onKeyUp="change_label_name(' + max_value + ', \'' + num + '_option' + max_value + '\',  this.value, \'select\')" disabled="disabled" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-30">' +
				  '<input type="text" class="fm-field-choice el_option_value" id="el_option_value' + max_value + '" value="' + (value_disabled == 'no' ? '[' + table + ':' + product_name + ']' : '[' + table + ':' + product_price + ']') + '" onKeyUp="change_label_value(\'' + num + '_option' + max_value + '\',  this.value)" disabled="disabled" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-20">' +
				  '<input type="checkbox" title="Empty value" class="el_option_dis" id="el_option' + max_value + '_dis" onClick="dis_option(\'' + num + '_option' + max_value + '\', this.checked, ' + max_value + ')"' + (value_disabled == 'yes' ? 'disabled="disabled"' : '') + ' />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + max_value + '_remove" onClick="remove_option(' + max_value + ', ' + num + ')"></span>' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-move-attribute dashicons dashicons-move el_choices_sortable"></span>' +
				  '</div>' +
				  '<input type="hidden" id="el_option_params' +  max_value + '" class="el_option_params" value="' + where + '[where_order_by]' + order + '[db_info]' + '[' + str + ']"></div>');
				attr_table.append(attr);
			  }
			  if (field_type == 'paypal_select') {
				var select_ = window.parent.document.getElementById(num + '_elementform_id_temp');
				var option = document.createElement('option');
				option.setAttribute("id", num + "_option" + max_value);
				option.setAttribute("onselect", "set_select('" + num + "_option" + max_value + "')");
				option.setAttribute("where", where);
				option.setAttribute("order_by", order);
				option.setAttribute("db_info", '[' + str + ']');
				option.innerHTML = '[' + table + ':' + product_name + ']';
				option.setAttribute("value", '[' + table + ':' + product_price + ']');
				select_.appendChild(option);

				var attr_table = window.parent.jQuery('#' + 'choices');
				var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
				  '<div class="fm-table-col fm-width-40">' +
				  '<input type="text" class="fm-field-choice" id="el_option' + max_value + '" value="[' + table + ':' + product_name + ']" disabled="disabled" onKeyUp="change_label_price(\'' + num + '_option' + max_value + '\', this.value)" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-20">' +
				  '<input type="text" class="fm-field-choice" id="el_option_price' + max_value + '" value="[' + table + ':' + product_price + ']" disabled="disabled" onKeyPress="return check_isnum_point(event)" onKeyUp="change_value_price(\'' + num + '_option' + max_value + '\', this.value)" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-20">' +
				  '<input type="hidden" id="el_option_params' + max_value + '" value="' + where + '[where_order_by]' + order + '[db_info]' + '[' + str + ']" />' +
				  '<input type="checkbox" title="Empty value" class="el_option_dis" disabled="disabled" id="el_option' + max_value + '_dis" onClick="dis_option_price(' + num + ',' + max_value + ', this.checked)" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + max_value + '_remove" onClick="remove_option_price(' + max_value + ',' + num + ')"></span>' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-move-attribute dashicons dashicons-move el_choices_sortable"></span>' +
				  '</div>' +
				  '</div>');
				attr_table.append(attr);
			  }
			  if (field_type == 'paypal_radio' || field_type == 'paypal_checkbox' || field_type == 'paypal_shipping') {
				if (field_type == 'paypal_shipping') {
				  field_type = 'paypal_radio';
				}
				var c_table = window.parent.document.getElementById(num + '_table_little');
				var tr = document.createElement('div');
				tr.setAttribute("id", num + "_element_tr" + max_value);
				tr.style.display = "table-row";
				var td = document.createElement('div');
				td.setAttribute("valign", "top");
				td.setAttribute("id", num + "_td_little" + max_value);
				td.setAttribute("idi", max_value);
				td.style.display = "table-cell";
				var adding = document.createElement('input');
				adding.setAttribute("type", field_type.replace('paypal_', ''));
				adding.setAttribute("value", '[' + table + ':' + product_price + ']');
				adding.setAttribute("id", num + "_elementform_id_temp" + max_value);
				if (field_type == 'paypal_checkbox') {
				  adding.setAttribute("onClick", "set_checked('" + num + "','" + max_value + "','form_id_temp')");
				  adding.setAttribute("name", num + "_elementform_id_temp" + max_value);
				}
				if (field_type == 'paypal_radio') {
				  adding.setAttribute("onClick", "set_default('" + num + "','" + max_value + "','form_id_temp')");
				  adding.setAttribute("name", num + "_elementform_id_temp");
				}
				var label_adding = document.createElement('label');
				label_adding.setAttribute("id", num + "_label_element" + max_value);
				label_adding.setAttribute("class", "ch-rad-label");
				label_adding.setAttribute("for", num + "_elementform_id_temp" + max_value);
				label_adding.innerHTML = '[' + table + ':' + product_name + ']';
				label_adding.setAttribute("where", where);
				label_adding.setAttribute("order_by", order);
				label_adding.setAttribute("db_info", '[' + str + ']');
				var adding_ch_label = document.createElement('input');
				adding_ch_label.setAttribute("type", "hidden");
				adding_ch_label.setAttribute("id", num + "_elementlabel_form_id_temp" + max_value);
				adding_ch_label.setAttribute("name", num + "_elementform_id_temp" + max_value + "_label");
				adding_ch_label.setAttribute("value", '[' + table + ':' + product_name + ']');
				td.appendChild(adding);
				td.appendChild(label_adding);
				td.appendChild(adding_ch_label);
				tr.appendChild(td);
				c_table.appendChild(tr);

				var attr_table = window.parent.jQuery('#' + 'choices');
				var attr = jQuery('<div id="' + max_value + '" class="change_pos fm-width-100">' +
				  '<div class="fm-table-col fm-width-60">' +
				  '<input type="text" class="fm-field-choice" id="el_choices' + max_value + '" value="[' + table + ':' + product_name + ']" disabled="disabled" onKeyUp="change_label(\'' + num + '_label_element' + max_value + '\', this.value); change_label_1(\'' + num + '_elementlabel_form_id_temp' + max_value + '\', this.value);" />' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-20">' +
				  '<input type="text" class="fm-field-choice" id="el_option_price' + max_value + '" value="[' + table + ':' + product_price + ']" disabled="disabled" onKeyPress="return check_isnum_point(event)" onKeyUp="change_value_price(\'' + num + '_elementform_id_temp' + max_value + '\', this.value)" />' +
				  '</div>' +
				  '<input type="hidden" id="el_option_params' + max_value + '" value="' + where + '[where_order_by]' + order + '[db_info]' + '[' + str + ']" />' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-remove-attribute dashicons dashicons-dismiss" id="el_option' + max_value + '_remove" onClick="remove_choise_price(' + max_value + ',' + num + ')"></span>' +
				  '</div>' +
				  '<div class="fm-table-col fm-width-10">' +
				  '<span class="fm-move-attribute dashicons dashicons-move el_choices_sortable"></span>' +
				  '</div>' +
				  '</div>');
				attr_table.append(attr);
				window.parent["refresh_attr"](num, 'type_checkbox');
			  }
			  window.parent.tb_remove();
			}
			else {
			  if (field_type == "checkbox" || field_type == "radio" || field_type == "select") {
				alert('Select an option(s).');
			  }
			  else {
				alert('Select a product name or product price.');
			  }
			}
			return false;
  }

  function escape_quotes(text) {
    return text
      .replace(/"/g, "")
      .replace(/'/g, "");
  }

	function gen_query() {
        query = '';
        query_price = '';
        where = '';
        previous = '';
        op_val = '';
        for (i = 1; i < cond_id; i++) {
          if ( jQuery('#' + i).html() ) {
            val = escape_quotes(jQuery('#val_' + i).val());
            if (jQuery('#op_' + i).val() == "%..%") {
              op_val = ' LIKE \'%' + val + '%\'';
            }
		        else if (jQuery('#op_' + i).val() == "..%") {
              op_val = ' LIKE \'' + val + '%\'';
            }
            else if (jQuery('#op_' + i).val() == "%..") {
              op_val = ' LIKE \'%' + val + '\'';
            }
            else {
              op_val = ' ' + jQuery('#op_' + i).val() + ' \'' + val + '\'';
            }
            where += previous + ' `' + jQuery('#sel_' + i).val() + '`' + op_val;
            previous = ' ' + jQuery('#andor_' + i).val();
          }
        }
        query = '[' + where + ']';
        query_price = '[' + (jQuery('#order_by').val() ? '`' + jQuery('#order_by').val() + '`' + ' ' + jQuery('#order_by_asc').val() : jQuery('#product_name').val() ? '`' + jQuery('#product_name').val() + '`' + ' ' + jQuery('#order_by_asc').val() : jQuery('#product_price').val() ? '`' + jQuery('#product_price').val() + '`' + ' ' + jQuery('#order_by_asc').val() : '' ) + ']';
        jQuery('#where').val(query);
        jQuery('#order').val(query_price);
	}
    </script>
    <?php if ( $table_struct ): ?>
      <div class="cols">
        <div>
          <label for="product_name" class="select-db-label"><?php echo(strpos($field_type, 'paypal_') === FALSE ? 'Select a name' : ($field_type == 'paypal_shipping' ? 'Select a shipping type' : 'Select a product name')); ?></label>
          <select name="product_name" id="product_name" class="select-db-select">
            <option value=""></option>
            <?php
            foreach ( $table_struct as $col ) {
              echo '<option value="' . $col->Field . '">' . $col->Field . '</option>';
            }
            ?>
          </select>
        </div>
        <div id="db_field_value">
          <label for="product_price" class="select-db-label"><?php echo(strpos($field_type, 'paypal_') === FALSE ? 'Select a value' : 'Select a product price'); ?></label>
          <select name="product_price" id="product_price" class="select-db-select">
            <option value=""></option>
            <?php
            foreach ( $table_struct as $col ) {
              echo '<option value="' . $col->Field . '" >' . $col->Field . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="select-db-op where">
          <img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/add_condition.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . ''; ?>" title="ADD" class="add_cond" />WHERE
        </div>

      </div>
      <div class="select-db-op orderby">ORDER BY</div>
      <div>
        <label for="order_by" class="select-db-label">Select an option</label>
        <select name="order_by" id="order_by">
          <option value=""></option>
          <?php
          foreach ( $table_struct as $col ) {
            echo '<option value="' . $col->Field . '" >' . $col->Field . '</option>';
          }
          ?>
        </select>
        <select name="order_by_asc" id="order_by_asc" style="width:70px;">
          <option value="asc">asc</option>
          <option value="desc">desc</option>
        </select>
      </div>
      <br />
      <div class="select-db-save">
        <input type="button" value="Save" class="gen_query" onclick="save_query()">
      </div>
      <form name="query_form" id="query_form" style="display:none;">
        <textarea id="where" name="where"></textarea>
        <textarea id="order" name="order"></textarea>
      </form>
	  <div id="fm-placeholder">
        <?php echo $html_placeholders ?>
      </div>
	  <style>
		#fm-placeholder {
			display: none;
			position: absolute;
			width: 225px;
			background: #fff;
			border: solid 1px #c7c7c7;
			top: 0;
			left: 0;
			z-index: 1000;
		}
		#fm-placeholder a {
			padding: 5px;
			cursor: pointer;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}
		#fm-placeholder a:hover {
			background: #ccc;
		}
	  </style>
    <?php endif;
    die();
  }

  /**
   * DB tables.
   *
   * @param array $params
   */
  public function db_tables( $params = array() ) {
    $this->fm_nonce = wp_create_nonce('fm_ajax_nonce');
    $form_id = intval($params['form_id']);
    $field_type = $params['field_type'];
    $tables = $params['tables'];
    ?>
    <label for="tables" class="select-db-label">Select a table</label>
    <select name="tables" id="tables" class="select-db-select">
      <option value=""></option>
      <?php
      foreach ( $tables as $table ) {
        echo '<option value="' . $table . '" >' . $table . '</option>';
      }
      ?>
    </select>
    <div id="table_struct"></div>
    <script>
      jQuery("#tables").change(function () {
        jQuery('#table_struct').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
        jQuery.ajax({
          type: "POST",
          url: "<?php echo add_query_arg(array(
                                           'action' => 'select_data_from_db' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                           'form_id' => $form_id,
                                           'field_type' => esc_html($field_type),
                                           'task' => 'db_table_struct_select',
                                           'width' => '1000',
                                           'height' => '500',
                                           'nonce' => $this->fm_nonce,
                                           'TB_iframe' => '1',
                                         ), admin_url('admin-ajax.php')); ?>",
          data: 'name=' + jQuery(this).val() + '&con_type=' + jQuery('input[name=con_type]:checked').val() + '&con_method=' + jQuery('input[name=con_method]:checked').val() + '&host=' + jQuery('#host_rem').val() + '&port=' + jQuery('#port_rem').val() + '&username=' + jQuery('#username_rem').val() + '&password=' + encodeURIComponent(jQuery('#password_rem').val()) + '&database=' + jQuery('#database_rem').val() + '&format=row&field_type=' + jQuery('#field_type').val(),
          success: function (data) {
            jQuery('#table_struct').removeClass("fm_loading");
            jQuery("#table_struct").html(data);
          }
        });
      })
    </script>
    <?php

    die();
  }
}
