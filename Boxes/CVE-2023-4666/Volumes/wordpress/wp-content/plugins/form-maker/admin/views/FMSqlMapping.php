<?php

class FMViewFormMakerSQLMapping extends FMAdminView {

  private $fm_nonce = null;
  public function __construct() {
    $this->fm_nonce = wp_create_nonce('fm_ajax_nonce');
    wp_print_scripts('jquery');
    wp_print_scripts('jquery-ui-tooltip');
    wp_print_styles(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
    wp_print_styles(WDFMInstance(self::PLUGIN)->handle_prefix . '-jquery-ui');
    wp_print_styles('dashicons');
  }

  /**
   * Edit query.
   *
   * @param array $params
   */
  public function edit_query( $params = array() ) {
    $id = $params['id'];
    $form_id = $params['form_id'];
    $label = $params['label'];
    $query_obj = $params['query_obj'];
    $tables = $params['tables'];
    $table_struct = $params['table_struct'];
    $temp = explode('***wdfcon_typewdf***', $query_obj->details);
    $con_type = $temp[0];
    $temp = explode('***wdfcon_methodwdf***', $temp[1]);
    $con_method = $temp[0];
    $temp = explode('***wdftablewdf***', $temp[1]);
    $table_cur = $temp[0];
    $temp = explode('***wdfhostwdf***', $temp[1]);
    $host = $temp[0];
    $temp = explode('***wdfportwdf***', $temp[1]);
    $port = $temp[0];
    if ($port) {
      $host .= ':' . $port;
    }
    $temp = explode('***wdfusernamewdf***', $temp[1]);
    $username = $temp[0];
    $temp = explode('***wdfpasswordwdf***', $temp[1]);
    $password = $temp[0];
    $temp = explode('***wdfdatabasewdf***', $temp[1]);
    $database = $temp[0];
    $details = $temp[1];
    $filter_types = array(
      "type_submit_reset",
      "type_map",
      "type_editor",
      "type_captcha",
      "type_recaptcha",
      "type_button",
      "type_paypal_total",
      "type_send_copy",
    );
    $label_id = array();
    $label_order = array();
    $label_order_original = array();
    $label_type = array();
    $label_all = explode('#****#', $label);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      if ( in_array($label_oder_each[1], $filter_types) ) {
        continue;
      }
      array_push($label_id, $label_id_each[0]);
      array_push($label_order_original, $label_oder_each[0]);
      $ptn = "/[^a-zA-Z0-9_]/";
      $rpltxt = "";
      $label_temp = preg_replace($ptn, $rpltxt, $label_oder_each[0]);
      array_push($label_order, $label_temp);
      array_push($label_type, $label_oder_each[1]);
    }
    $form_fields = '';
    foreach ( $label_id as $key => $lid ) {
      $form_fields .= '<a onclick="insert_field(' . $lid . '); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">' . $label_order_original[$key] . '</a>';
    }
    $user_fields = array(
      "subid" => "Submission ID",
      "ip" => "Submitter's IP",
      "userid" => "User ID",
      "username" => "Username",
      "useremail" => "User Email",
    );
    foreach ( $user_fields as $user_key => $user_field ) {
      $form_fields .= '<a onclick="insert_field(\'' . $user_key . '\'); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">' . $user_field . '</a>';
    }
    $cond = '<div id="condid"><select id="sel_condid" style="width: 160px">';
    foreach ( $table_struct as $col ) {
      $cond .= '<option>' . str_replace("'", "SingleQuot", $col->Field) . '</option>';
    }
    $cond .= '</select>';
    $cond .= '<select id="op_condid"><option value="=" selected="selected">=</option><option value="!=">!=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option><option value="%..%">Like</option><option value="..%">Starts with</option><option value="%..">Ends with</option></select>';
	$cond .= '<input autocomplete="off" id="val_condid" style="width:170px" type="text" class="fm-where-input" />';
	$cond .= '<select id="andor_condid" style="visibility: hidden;"><option value="AND">AND</option><option value="OR">OR</option></select>';
	$cond .= '<img src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/delete.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" onclick="delete_cond(&quot;condid&quot;)" style="vertical-align: middle;"></div>';
    ?>
    <script>
      jQuery(function() {
        // Add tooltip to elements with "wd-info" class.
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
              // close any lingering tooltips
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
      });
      function connect() {
        jQuery("input[type='radio']").attr('disabled', '');
        jQuery('#struct').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
        jQuery("#struct").load('index.php?option=com_formmaker&task=db_tables&con_type=' + jQuery('input[name=con_type]:checked').val() + '&con_method=' + jQuery('input[name=con_method]:checked').val() + '&format=row');
      }
      jQuery(function () {
        jQuery("#tables").change(function () {
          jQuery("#struct").removeClass("fm_loading");
          jQuery("#table_struct").load('index.php?option=com_formmaker&task=db_table_struct&name=' + jQuery(this).val() + '&con_type=' + jQuery('input[name=con_type]:checked').val() + '&con_method=' + jQuery('input[name=con_method]:checked').val() + '&host=' + jQuery('#host_rem').val() + '&port=' + jQuery('#port_rem').val() + '&username=' + jQuery('#username_rem').val() + '&password=' + encodeURIComponent( jQuery('#password_rem').val() ) + '&database=' + jQuery('#database_rem').val() + '&format=row&id=' + jQuery("#form_id").val());
        });
        jQuery('html').click(function () {
          if (jQuery("#fieldlist").css('display') == "block") {
            jQuery("#fieldlist").hide();
          }
        });
        jQuery('.cols input[type="text"]').on('click', function (event) {
          event.stopPropagation();
          jQuery("#fieldlist").css("top", jQuery(this).offset().top + jQuery(this).height() + 2);
          jQuery("#fieldlist").css("left", jQuery(this).offset().left);
          jQuery("#fieldlist").slideDown('fast');
          selected_field = this.id;
        });
        jQuery('#query_txt').click(function (event) {
          event.stopPropagation();
          jQuery("#fieldlist").css("top", jQuery(this).offset().top + jQuery(this).height() + 2);
          jQuery("#fieldlist").css("left", jQuery(this).offset().left);
          jQuery("#fieldlist").slideDown('fast');
          selected_field = this.id;
        });
        jQuery('#fieldlist').click(function (event) {
          event.stopPropagation();
        });
        jQuery('.add_cond').click(function () {
          jQuery('.cols').append(conds.replace(/condid/g, cond_id++).replace('SingleQuot', "'"));
          update_vis();
        });
      });
      var selected_field = '';
      var isvisible = 1;
      var cond_id = 1;
      conds = '<?php echo $cond ?>';
      fields = new Array(<?php
        $fields = "";
        if ( $table_struct ) {
          foreach ( $table_struct as $col ) {
            $fields .= ' "' . $col->Field . '",';
          }
          echo substr($fields, 0, -1);
        }
        ?>);
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
      function delete_cond(id) {
        jQuery('#' + id).remove();
        update_vis();
      }
      function save_query() {
        con_type = jQuery('input[name=con_type]:checked').val();
        con_method = jQuery('input[name=con_method]:checked').val();
        table = jQuery('#tables').val();
        host = jQuery('#host_rem').val();
        port = jQuery('#port_rem').val();
        username = jQuery('#username_rem').val();
        password = jQuery('#password_rem').val();
        database = jQuery('#database_rem').val();
        str = con_type + "***wdfcon_typewdf***" + con_method + "***wdfcon_methodwdf***" + table + "***wdftablewdf***" + host + "***wdfhostwdf***" + port + "***wdfportwdf***" + username + "***wdfusernamewdf***" + password + "***wdfpasswordwdf***" + database + "***wdfdatabasewdf***";
        if (fields.length) {
          for (i = 0; i < fields.length; i++) {
            str += fields[i] + '***wdfnamewdf***' + jQuery('#' + fields[i]).val() + '***wdfvaluewdf***' + jQuery('#ch_' + fields[i] + ":checked").length + '***wdffieldwdf***';
          }
        }
        for (i = 1; i < cond_id; i++) {
          if (jQuery('#' + i).html()) {
            str += jQuery('#sel_' + i).val() + '***sel***' + jQuery('#op_' + i).val() + '***op***' + jQuery('#val_' + i).val() + '***val***' + jQuery('#andor_' + i).val() + '***where***';
          }
        }
        if (!jQuery('#query_txt').val()) {
          gen_query();
        }
        jQuery('#details').val(str);
        var datatxt = jQuery("#query_form").serialize() + '&form_id=' + jQuery("#form_id").val();
        if (jQuery('#query_txt').val()) {
          jQuery('.c1').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
          jQuery.ajax({
            type: "POST",
            url: "<?php echo add_query_arg(array(
                                             'action' => 'FormMakerSQLMapping' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                             'id' => $id,
                                             'form_id' => $form_id,
                                             'task' => 'update_query',
                                             'width' => '1000',
                                             'height' => '500',
                                             'nonce' => $this->fm_nonce,
                                             'TB_iframe' => '1',
                                           ), admin_url('admin-ajax.php')); ?>",
            data: datatxt,
            success: function (data) {
              window.parent.wd_fm_apply_options('apply_form_options');
              if (typeof window.parent.set_role_hidden_value === "function") {
                window.parent.set_role_hidden_value();
              }
              window.parent.FormManageSubmitButton();
              window.parent.fm_set_input_value('task', 'apply');
              window.parent.document.getElementById('manage_form').submit();
            }
          });
        }
        else {
          alert('The query is empty.');
        }
        return false;
      }
      function gen_query() {
        if (jQuery('#query_txt').val()) {
          if (!confirm('Are you sure you want to replace the Query? All the modifications to the Query will be lost.')) {
            return;
          }
        }
        query = "";
        fields = new Array(<?php
          $fields = "";
          if ( $table_struct ) {
            foreach ( $table_struct as $col ) {
              $fields .= ' "' . str_replace("'", "SingleQuot", $col->Field) . '",';
            }
            echo substr($fields, 0, -1);
          }
          ?>);
        con_type = jQuery('input[name=con_type]:checked').val();
        con_method = jQuery('input[name=con_method]:checked').val();
        table = jQuery('#tables').val();
        fls = '';
        vals = '';
        valsA = new Array();
        flsA = new Array();
        if (fields.length) {
          for (i = 0; i < fields.length; i++) {
            if (jQuery('#ch_' + fields[i] + ":checked").length) {
              flsA.push(fields[i]);
              valsA.push(jQuery('#' + fields[i]).val());
            }
          }
        }
        if (con_method == "insert") {
          if (flsA.length) {
            for (i = 0; i < flsA.length - 1; i++) {
              fls += '`' + flsA[i] + '`, ';
              vals += '"' + valsA[i] + '", ';
            }
            fls += '`' + flsA[i] + '`';
            vals += '"' + valsA[i] + '"';
          }
          if (fls) {
            query = "INSERT INTO `" + jQuery('#tables').val() + "` (" + fls + ") VALUES (" + vals + ")";
          }
        }
        if (con_method == "update") {
          if (flsA.length) {
            for (i = 0; i < flsA.length - 1; i++) {
              vals += '`' + flsA[i] + '`="' + valsA[i] + '", ';
            }
            vals += '`' + flsA[i] + '`="' + valsA[i] + '"';
          }
          where = "";
          previous = '';
          for (i = 1; i < cond_id; i++) {
            if (jQuery('#' + i).html()) {
              if (jQuery('#op_' + i).val() == "%..%") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "..%") {
                op_val = ' LIKE "' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "%..") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '"';
              }

              else {
                op_val = ' ' + jQuery('#op_' + i).val() + ' "' + jQuery('#val_' + i).val() + '"';
              }
              where += previous + ' `' + jQuery('#sel_' + i).val() + '`' + op_val;
              previous = ' ' + jQuery('#andor_' + i).val();
            }
          }
          if (vals) {
            query = "UPDATE `" + jQuery('#tables').val() + "` SET " + vals + (where ? ' WHERE' + where : '');
          }
        }
        if (con_method == "delete") {
          where = "";
          previous = '';
          for (i = 1; i < cond_id; i++) {
            if (jQuery('#' + i).html()) {
              if (jQuery('#op_' + i).val() == "%..%") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "..%") {
                op_val = ' LIKE "' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "%..") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '"';
              }

              else {
                op_val = ' ' + jQuery('#op_' + i).val() + ' "' + jQuery('#val_' + i).val() + '"';
              }
              where += previous + ' ' + jQuery('#sel_' + i).val() + op_val;
              previous = ' ' + jQuery('#andor_' + i).val();
            }
          }
          if (where) {
            query = "DELETE FROM `" + jQuery('#tables').val() + '` WHERE' + where;
          }
        }
        jQuery('#query_txt').val(query.replace("SingleQuot", "'"));
      }

      function insert_field(myValue) {
        if (!selected_field) {
          return;
        }
        myField = document.getElementById(selected_field);
        if (document.selection) {
          myField.focus();
          sel = document.selection.createRange();
          sel.text = myValue;
        }
        else {
          if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            myField.value = myField.value.substring(0, startPos)
              + "{" + myValue + "}"
              + myField.value.substring(endPos, myField.value.length);
          }
          else {
            myField.value += "{" + myValue + "}";
          }
        }
      }
    </script>
    <style>
      .ui-tooltip {
        padding: 0 10px !important;
        position: absolute;
        z-index: 9999;
        max-width: 300px;
        white-space: pre-line;
      }

      .ui-tooltip .ui-tooltip-content {
        font-weight: normal;
      }

      .ui-tooltip, .tooltip-arrow:after {
        background: #23282D;
      }

      .ui-tooltip {
        color: white;
        border-radius: 10px;
        font: bold 14px "Helvetica Neue", Sans-Serif;
        box-shadow: 0 0 7px black;
      }

      .ui-tooltip p {
        margin: 0;
      }

      .tooltip-arrow {
        width: 70px;
        height: 16px;
        overflow: hidden;
        position: absolute;
        left: 50%;
        margin-left: -35px;
        bottom: -16px;
      }

      .tooltip-arrow.top {
        top: -16px;
        bottom: auto;
      }

      .tooltip-arrow.left {
        left: 20%;
      }

      .tooltip-arrow:after {
        content: "";
        position: absolute;
        left: 20px;
        top: -20px;
        width: 25px;
        height: 25px;
        box-shadow: 6px 5px 9px -9px #23282D;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
      }

      .tooltip-arrow.top:after {
        bottom: -20px;
        top: auto;
      }
      .screen-reader-text, .screen-reader-text span, .ui-helper-hidden-accessible {
        border: 0;
        clip: rect(1px,1px,1px,1px);
        -webkit-clip-path: inset(50%);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
        word-wrap: normal!important;
      }
      .field-name {
        vertical-align: middle;
      }
      .wd-info {
        cursor: pointer;
        vertical-align: middle;
        color: #777777;
      }
      .wd-hide {
        display: none;
      }
      .c1 {
        padding: 0 10px;
      }

      .main_func {
        font-family: Segoe UI;
        display: inline-block;
        width: 550px;
      }

      .main_func .admintable {
        width: 100%;
        padding: 10px 0;
        margin-bottom: 15px;
      }

      .main_func .admintable .key {
        width: 36%;
      }

      .main_func .admintable input[type='text'],
      .main_func .admintable input[type='password'] {
        padding: 4px 6px;
        width: 244px;
      }

      .btn.connect {
        background: #4EC0D9;
        width: 78px;
        height: 32px;
        border: 1px solid #4EC0D9;
        border-radius: 0px;
        color: #fff;
        cursor: pointer;
      }

      .desc {
        font-size: 13px;
        display: inline-block;
        width: 100%;
        height: 100%;
        position: fixed;
        margin: 15px;
        margin-left: 45px;
        overflow: scroll;
      }

      .desc > div {
        margin-top: 3px;
      }

      .desc div span {
        width: 62px;
        display: inline-block;
      }

      .desc button {
        max-width: 190px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        padding: 4px;
        background: #eee;
        border: 1px solid #ccc;
        border-radius: 0px;
        height: 27px;
      }

      .key label {
        display: inline-block;
        width: 150px;
      }

      .select-db-label {
        display: inline-block;
        font-size: 16px;
        width: 201px;

      }

      .select-db-select {
        width: 325px;
        padding: 6px;
      }

      .struct {
        border: 3px solid red;
        padding: 4px 6px;
        display: inline-block;
        margin-top: 10px;
      }

      .cols div:nth-child(even) {
        background: #FFF
      }

      .cols div:nth-child(odd) {
        background: #F5F5F5
      }

      .cols div {
        height: 28px;
        padding: 5px
      }

      .cols label {
        display: inline-block;
        width: 175px;
        font-size: 15px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        vertical-align: middle;
      }

      .cols input[type="text"] {
        width: 295px;
        line-height: 18px;
        height: 28px;
        border: 1px solid #ccc;
        padding: 0 3px;
        margin-right: 2px;
      }

      .cols input[type="text"]:disabled {
        cursor: not-allowed;
        background-color: #eee;
      }

      .cols input[type="checkbox"] {
        width: 20px;
        line-height: 18px;
        height: 20px;
        vertical-align: middle;
      }

      .cols select {
        line-height: 18px;
        height: 28px;
        margin-right: 2px;
      }

      #fieldlist {
        position: absolute;
        width: 225px;
        background: #fff;
        border: solid 1px #c7c7c7;
        top: 0;
        left: 0;
        z-index: 1000;
      }

      #fieldlist a {
        padding: 5px;
        cursor: pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      #fieldlist a:hover {
        background: #ccc;
      }

      .gen_query, .gen_query:focus {
        width: 148px;
        height: 38px;
        background: #4EC0D9;
        color: white;
        cursor: pointer;
        border: 0px;
        font-size: 14px;
        font-weight: bold;
        margin: 20px 0;
        border-radius: 0px;
      }

      .gen_query:active {
        background: #ccc;
      }

      .fm-query-save {
        float: right;
        font-size: 13px;
        margin: 0 20px;
        background: #4EC0D9;
        width: 83px;
        height: 34px;
        border: 1px solid #4EC0D9;
        border-radius: 0px;
        color: #fff;
        cursor: pointer;
      }

      .select-db-op {
        display: inline-block;
        text-align: right;
        font-weight: bold;
        font-size: 18px;
        padding: 5px 0;
        margin: 5px 0;
        background: none !important;
      }

      .select-db-op.where {
        width: 272px;
      }

      .select-db-op img {
        float: left;
      }

      .select-db-label {
        display: inline-block;
        font-size: 16px;
        width: 201px;

      }

      .select-db-select {
        width: 325px;
        padding: 6px;
      }

      .fm-query-save {
        float: right;
        font-size: 13px;
        margin: 0 20px;
        background: #4EC0D9;
        width: 83px;
        height: 34px;
        border: 1px solid #4EC0D9;
        border-radius: 0px;
        color: #fff;
        cursor: pointer;
      }
    </style>
    <div class="c1">
      <div class="main_func">
        <table class="admintable">
          <tr valign="top">
            <td class="key">
              <label style="font-size:20px;">Connection type: </label>
            </td>
            <td>
              <input type="radio" name="con_type" id="local" value="local" <?php if ( $con_type == 'local' )
                echo 'checked="checked"' ?> disabled>
              <label for="local">Local</label>
              <input type="radio" name="con_type" id="remote" value="remote" <?php if ( $con_type == 'remote' )
                echo 'checked="checked"' ?> disabled>
              <label for="remote">Remote</label>
            </td>
            <td rowspan="2">
              <input type="button" value="Connect" onclick="connect()" class="wd-button button-primary" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if ( $con_type == 'local' )
            echo 'style="display:none"' ?>>
            <td class="key">Host</td>
            <td>
              <input type="text" name="host" id="host_rem" style="width:150px" value="<?php echo $host; ?>" disabled>
              Port :
              <input type="text" name="port" id="port_rem" value="<?php echo $port; ?>" style="width:48px" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if ( $con_type == 'local' )
            echo 'style="display:none"' ?>>
            <td class="key">Username</td>
            <td>
              <input type="text" name="username" id="username_rem" value="<?php echo $username; ?>" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if ( $con_type == 'local' )
            echo 'style="display:none"' ?>>
            <td class="key">Password</td>
            <td>
              <input type="password" name="password" id="password_rem" value="<?php echo $password; ?>" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if ( $con_type == 'local' )
            echo 'style="display:none"' ?>>
            <td class="key">Database</td>
            <td>
              <input type="text" name="database" id="database_rem" value="<?php echo $database; ?>" disabled>
            </td>
          </tr>
          <tr valign="top">
            <td class="key">
              <label style="font-size:20px;">Type: </label>
            </td>
            <td>
              <input type="radio" name="con_method" id="insert" value="insert" <?php if ( $con_method == 'insert' )
                echo 'checked="checked"' ?> disabled>
              <label for="insert">Insert</label>
              <input type="radio" name="con_method" id="update" value="update" <?php if ( $con_method == 'update' )
                echo 'checked="checked"' ?> disabled>
              <label for="update">Update</label>
              <input type="radio" name="con_method" id="delete" value="delete" <?php if ( $con_method == 'delete' )
                echo 'checked="checked"' ?> disabled>
              <label for="delete">Delete</label>
            </td>
          </tr>
        </table>
        <div id="struct">
          <label for="tables" class="select-db-label">Select a table</label>
          <select name="tables" id="tables" class="select-db-select" disabled>
            <option value=""></option>
            <?php
            foreach ( $tables as $table ) {
              echo '<option value="' . $table . '" ' . ($table_cur == $table ? 'selected' : '') . ' >' . $table . '</option>';
            }
            ?>
          </select>
          <br />
          <div id="table_struct">
            <?php
            if ( $table_struct ) {
              ?>
              <div class="struct">
                <div class="cols">
                  <?php
                  $temps = explode('***wdffieldwdf***', $details);
                  $wheres = $temps[count($temps) - 1];
                  $temps = array_slice($temps, 0, count($temps) - 1);
                  $col_names = array();
                  $col_vals = array();
                  $col_checks = array();
                  foreach ( $temps as $temp ) {
                    $temp = explode('***wdfnamewdf***', $temp);
                    array_push($col_names, $temp[0]);
                    $temp = explode('***wdfvaluewdf***', $temp[1]);
                    array_push($col_vals, $temp[0]);
                    array_push($col_checks, $temp[1]);
                  }
                  if ( $con_method == 'insert' or $con_method == 'update' ) {
                    echo '<div style="background: none;text-align: center;font-size: 20px;color: #000;font-weight: bold;"> SET </div>';
                    foreach ( $table_struct as $key => $col ) {
                      $title = $col->Field;
                      $title .= "<ul style='padding-left: 17px;'>";
                      if ( $col->Type ) {
                        $title .= "<li>" . __('Type', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Type . "</li>";
                      }
                      if ( $col->Null ) {
                        $title .= "<li>" . __('Null', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Null . "</li>";
                      }
                      if ( $col->Key ) {
                        $title .= "<li>" . __('Key', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Key . "</li>";
                      }
                      if ( $col->Default ) {
                        $title .= "<li>" . __('Default', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Default . "</li>";
                      }
                      if ( $col->Extra ) {
                        $title .= "<li>" . __('Extra', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Extra . "</li>";
                      }
                      $title .= "</ul>";
                      ?>
                      <div>
                        <label>
                          <b><?php echo $col->Field; ?></b>
                          <i class="wd-info dashicons dashicons-info" data-id="wd-info-<?php echo $key; ?>"></i>
                          <div id="wd-info-<?php echo $key; ?>" class="wd-hide">
                            <?php echo $title; ?>
                          </div>
                        </label>
                        <input autocomplete="off" type="text" id="<?php echo str_replace("'", "SingleQuot", $col->Field); ?>" <?php if ( !$col_checks[$key] )
                          echo 'disabled="disabled"' ?> value="<?php echo $col_vals[$key]; ?>" />
                        <input id="ch_<?php echo str_replace("'", "SingleQuot", $col->Field); ?>" type="checkbox" onClick="dis('<?php echo str_replace("'", "SingleQuot", $col->Field); ?>', this.checked)" <?php if ( $col_checks[$key] )
                          echo 'checked="checked"' ?> />
                      </div>
                      <?php
                    }
                  }
                  if ( $con_method == 'delete' or $con_method == 'update' ) {
                    echo '<div class="select-db-op where"><img src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/add_condition.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" title="ADD" class="add_cond"/>WHERE</div>';
                    if ( $wheres ) {
                      echo '<script>';
                      $wheres = explode('***where***', $wheres);
                      $wheres = array_slice($wheres, 0, count($wheres) - 1);
                      foreach ( $wheres as $where ) {
                        $temp = explode('***sel***', $where);
                        $sel = $temp[0];
                        $temp = explode('***op***', $temp[1]);
                        $op = $temp[0];
                        $temp = explode('***val***', $temp[1]);
                        $val = $temp[0];
                        $andor = $temp[1];
                        echo 'jQuery(".cols").append(conds.replace(/condid/g, cond_id++).replace(\'SingleQuot\', "\'")); update_vis();
											jQuery("#sel_"+(cond_id-1)).val("' . html_entity_decode($sel, ENT_QUOTES) . '");
											jQuery("#op_"+(cond_id-1)).val("' . $op . '");
											jQuery("#val_"+(cond_id-1)).val("' . $val . '");
											jQuery("#andor_"+(cond_id-1)).val("' . $andor . '");';
                      }
                      echo '</script>';
                    }
                  }
                  ?>
                </div>
                <div style="color:red; background: none">The changes above will not affect the query until you click "Generate query".</div>
              </div>
              <br />
              <input type="button" value="Generate Query" class="gen_query" onclick="gen_query()">
              <br />
              <form name="query_form" id="query_form">
                <label style="vertical-align: top; width: 102px; display: inline-block;" for="query_txt"> Query: </label><textarea id="query_txt" name="query" rows=5 style="width:428px"><?php echo $query_obj->query; ?></textarea>
                <input type="hidden" name="details" id="details">
                <input type="hidden" name="id" value="<?php echo $query_obj->id; ?>">
              </form>
              <input type="button" value="Save" class="fm-query-save" onclick="save_query()">
              <div id="fieldlist" style="display: none;">
                <?php echo $form_fields; ?>
              </div>
              <?php
            }
            ?>
          </div>
        </div>
        <input type="hidden" value="<?php echo $form_id ?>" id="form_id">
      </div>
      <div class="desc">
        <?php
        foreach ( $label_id as $key => $lid ) {
          echo '<div>{' . $lid . '} - <button onclick="insert_field(' . $lid . ');">' . $label_order_original[$key] . '</button></div>';
        }
        $user_fields = array(
          "subid" => "Submission ID",
          "ip" => "Submitter's IP",
          "userid" => "User ID",
          "username" => "Username",
          "useremail" => "User Email",
        );
        foreach ( $user_fields as $user_key => $user_field ) {
          echo '<div>{' . $user_key . '} - <button onclick="insert_field(\'' . $user_key . '\');">' . $user_field . '</button></div>';
        }
        ?>
      </div>
    </div>
    <?php
    die();
  }

  /**
   * Add query.
   *
   * @param array $params
   */
  public function add_query( $params = array() ) {
    $label = $params['label'];
    $form_id = $params['form_id'];
    $filter_types = array(
      "type_submit_reset",
      "type_map",
      "type_editor",
      "type_captcha",
      "type_recaptcha",
      "type_button",
      "type_paypal_total",
      "type_send_copy",
    );
    $label_id = array();
    $label_order = array();
    $label_order_original = array();
    $label_type = array();
    $label_all = explode('#****#', $label);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      if ( in_array($label_oder_each[1], $filter_types) ) {
        continue;
      }
      array_push($label_id, $label_id_each[0]);
      array_push($label_order_original, $label_oder_each[0]);
      $ptn = "/[^a-zA-Z0-9_]/";
      $rpltxt = "";
      $label_temp = preg_replace($ptn, $rpltxt, $label_oder_each[0]);
      array_push($label_order, $label_temp);
      array_push($label_type, $label_oder_each[1]);
    }
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
                                           'action' => 'FormMakerSQLMapping' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                           'id' => 0,
                                           'form_id' => $form_id,
                                           'task' => 'db_tables',
                                           'width' => '1000',
                                           'height' => '500',
                                           'nonce' => $this->fm_nonce,
                                           'TB_iframe' => '1',
                                         ), admin_url('admin-ajax.php')); ?>",
          data: 'con_type=' + jQuery('input[name=con_type]:checked').val() + '&con_method=' + jQuery('input[name=con_method]:checked').val() + '&host=' + jQuery('#host_rem').val() + '&port=' + jQuery('#port_rem').val() + '&username=' + jQuery('#username_rem').val() + '&password=' + encodeURIComponent(jQuery('#password_rem').val()) + '&database=' + jQuery('#database_rem').val() + '&format=row&nonce=<?php echo $this->fm_nonce; ?>',
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
        display: inline-block;
        width: 550px;
      }

      .main_func .admintable {
        width: 100%;
        padding: 10px 0;
        margin-bottom: 15px;
      }

      .main_func .admintable .key {
        width: 36%;
      }

      .main_func .admintable input[type='text'],
      .main_func .admintable input[type='password'] {
        padding: 4px 6px;
        width: 244px;
      }

      .btn.connect {
        background: #4EC0D9;
        width: 78px;
        height: 32px;
        border: 1px solid #4EC0D9;
        border-radius: 0px;
        color: #fff;
        cursor: pointer;
      }

      .desc {
        font-size: 13px;
        display: inline-block;
        width: 100%;
        height: 100%;
        position: fixed;
        margin: 15px;
        margin-left: 45px;
        overflow: scroll;
      }

      .desc > div {
        margin-top: 3px;
      }

      .desc div span {
        width: 62px;
        display: inline-block;
      }

      .desc button {
        max-width: 190px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        padding: 4px;
        background: #eee;
        border: 1px solid #ccc;
        border-radius: 0px;
        height: 27px;
      }

      .key label {
        display: inline-block;
        width: 150px;
      }

      .select-db-label {
        display: inline-block;
        font-size: 16px;
        width: 201px;

      }

      .select-db-select {
        width: 325px;
        padding: 6px;
      }
    </style>
    <div class="c1">
      <div class="main_func">
        <table class="admintable">
          <tr valign="top">
            <td class="key">
              <label style="font-size:20px;">Connection type: </label>
            </td>
            <td>
              <input type="radio" name="con_type" id="local" value="local" checked="checked" onclick="shh(false)">
              <label for="local">Local</label>
              <input type="radio" name="con_type" id="remote" value="remote" onclick="shh(true)">
              <label for="remote">Remote</label>
            </td>
            <td rowspan="2">
              <input type="button" value="Connect" onclick="connect()" class="btn connect">
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td class="key">Host</td>
            <td>
              <input type="text" name="host" id="host_rem" style="width:150px">
              Port : <input type="text" name="port" id="port_rem" value="3306" style="width:48px">
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td class="key">Username</td>
            <td>
              <input type="text" name="username" id="username_rem">
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td class="key">Password</td>
            <td>
              <input type="password" name="password" id="password_rem">
            </td>
          </tr>
          <tr class="remote_info" style="display:none">
            <td class="key">Database</td>
            <td>
              <input type="text" name="database" id="database_rem">
            </td>
          </tr>
          <tr valign="top">
            <td class="key">
              <label style="font-size:20px;">Type: </label>
            </td>
            <td>
              <input type="radio" name="con_method" id="insert" value="insert" checked="checked">
              <label for="insert">Insert</label>
              <input type="radio" name="con_method" id="update" value="update">
              <label for="update">Update</label>
              <input type="radio" name="con_method" id="delete" value="delete">
              <label for="delete">Delete</label>
            </td>
          </tr>
        </table>
        <div id="struct">
        </div>
        <input type="hidden" value="<?php echo $form_id ?>" id="form_id">
      </div>
      <div class="desc">
        <?php
        foreach ( $label_id as $key => $lid ) {
          echo '<div><span>{' . $lid . '}</span> - <button onclick="insert_field(' . $lid . ');">' . $label_order_original[$key] . '</button></div>';
        }
        $user_fields = array(
          "subid" => "Submission ID",
          "ip" => "Submitter's IP",
          "userid" => "User ID",
          "username" => "Username",
          "useremail" => "User Email",
        );
        foreach ( $user_fields as $user_key => $user_field ) {
          echo '<div><span>{' . $user_key . '}</span> - <button onclick="insert_field(\'' . $user_key . '\');">' . $user_field . '</button></div>';
        }
        ?>
      </div>
    </div>
    <?php
    die();
  }

  /**
   * db tables.
   *
   * @param array $params
   */
  public function db_tables( $params = array() ) {
    $tables = $params['tables'];
    $form_id = intval($params['form_id']);
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
    <br />
    <div id="table_struct">
    </div>
    <script>
      jQuery("#tables").change(function () {
        jQuery('#table_struct').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
        jQuery.ajax({
          type: "POST",
          url: "<?php echo add_query_arg(array(
                                           'action' => 'FormMakerSQLMapping' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                           'id' => 0,
                                           'form_id' => $form_id,
                                           'task' => 'db_table_struct',
                                           'width' => '1000',
                                           'height' => '500',
                                           'nonce' => $this->fm_nonce,
                                           'TB_iframe' => '1',
                                         ), admin_url('admin-ajax.php')); ?>",
          data: 'name=' + jQuery(this).val() + '&con_type=' + jQuery('input[name=con_type]:checked').val() + '&con_method=' + jQuery('input[name=con_method]:checked').val() + '&host=' + jQuery('#host_rem').val() + '&port=' + jQuery('#port_rem').val() + '&username=' + jQuery('#username_rem').val() + '&password=' + encodeURIComponent( jQuery('#password_rem').val()) + '&database=' + jQuery('#database_rem').val() + '&format=row&nonce=<?php echo $this->fm_nonce; ?>',
          success: function (data) {
            jQuery("#table_struct").removeClass("fm_loading");
            jQuery("#table_struct").html(data);
          }
        });
      })
    </script>
    <?php
    die();
  }

  /**
   * db table struct.
   *
   * @param array $params
   */
  public function db_table_struct( $params = array() ) {
    $label = $params['label'];
    $form_id = $params['form_id'];
    $con_method = $params['con_method'];
    $table_struct = $params['table_struct'];
    $filter_types = array(
      "type_submit_reset",
      "type_map",
      "type_editor",
      "type_captcha",
      "type_recaptcha",
      "type_button",
      "type_paypal_total",
      "type_send_copy",
    );
    $label_id = array();
    $label_order = array();
    $label_order_original = array();
    $label_type = array();
    // stexic
    $label_all = explode('#****#', $label);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      if ( in_array($label_oder_each[1], $filter_types) ) {
        continue;
      }
      array_push($label_id, $label_id_each[0]);
      array_push($label_order_original, $label_oder_each[0]);
      $ptn = "/[^a-zA-Z0-9_]/";
      $rpltxt = "";
      $label_temp = preg_replace($ptn, $rpltxt, $label_oder_each[0]);
      array_push($label_order, $label_temp);
      array_push($label_type, $label_oder_each[1]);
    }
    $form_fields = '';
    foreach ( $label_id as $key => $id ) {
      $form_fields .= '<a onclick="insert_field(' . $id . '); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">' . $label_order_original[$key] . '</a>';
    }
    $user_fields = array(
      "subid" => "Submission ID",
      "ip" => "Submitter's IP",
      "userid" => "User ID",
      "username" => "Username",
      "useremail" => "User Email",
    );
    foreach ( $user_fields as $user_key => $user_field ) {
      $form_fields .= '<a onclick="insert_field(\'' . $user_key . '\'); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">' . $user_field . '</a>';
    }
    $cond = '<div id="condid"><select id="sel_condid" style="width: 160px">';
    foreach ( $table_struct as $col ) {
      $cond .= '<option>' . str_replace("'", "SingleQuot", $col->Field) . '</option>';
    }
    $cond .= '</select>';
    $cond .= '<select id="op_condid"><option value="=" selected="selected">=</option><option value="!=">!=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option><option value="%..%">Like</option><option value="..%">Starts with</option><option value="%..">Ends with</option></select>';
	$cond .= '<input autocomplete="off" id="val_condid" style="width:170px" class="fm-where-input" type="text" />';
	$cond .= '<select id="andor_condid" style="visibility: hidden;"><option value="AND">AND</option><option value="OR">OR</option></select>';
	$cond .= '<img src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/delete.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" onclick="delete_cond(&quot;condid&quot;)" style="vertical-align: middle;"></div>';
    ?>
    <script>
      jQuery(function() {
        // Add tooltip to elements with "wd-info" class.
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
              // close any lingering tooltips
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
	  });
      var selected_field = '';
      var isvisible = 1;
      var cond_id = 1;
      conds = '<?php echo $cond ?>';
      fields = new Array(<?php
        $fields = "";
        if ( $table_struct ) {
          foreach ( $table_struct as $col ) {
            $fields .= ' "' . $col->Field . '",';
          }
          echo substr($fields, 0, -1);
        }
        ?>);
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

	  function delete_cond(id) {
        jQuery('#' + id).remove();
        update_vis();
      }

		jQuery('.add_cond').click(function () {
			jQuery('.cols').append(conds.replace(/condid/g, cond_id++).replace('SingleQuot', "'"));
			update_vis();
		});
		jQuery('html').click(function () {
			if (jQuery("#fieldlist").css('display') == "block") {
				jQuery("#fieldlist").hide();
			}
		});
		jQuery(document).on("click", ".fm-where-input", function(e) {
			e.stopPropagation();
			jQuery("#fieldlist").css("top", jQuery(this).offset().top + jQuery(this).height() + 2);
			jQuery("#fieldlist").css("left", jQuery(this).offset().left);
			jQuery("#fieldlist").slideDown('fast');
			selected_field = this.id;
		});
		jQuery('.cols input[type="text"]').on('click', function (event) {
			event.stopPropagation();
			jQuery("#fieldlist").css("top", jQuery(this).offset().top + jQuery(this).height() + 2);
			jQuery("#fieldlist").css("left", jQuery(this).offset().left);
			jQuery("#fieldlist").slideDown('fast');
			selected_field = this.id;
		});
		jQuery('#query_txt').click(function (event) {
			event.stopPropagation();
			jQuery("#fieldlist").css("top", jQuery(this).offset().top + jQuery(this).height() + 2);
			jQuery("#fieldlist").css("left", jQuery(this).offset().left);
			jQuery("#fieldlist").slideDown('fast');
			selected_field = this.id;
		});
		jQuery('#fieldlist').click(function (event) {
			event.stopPropagation();
		});
      function save_query() {
        con_type = jQuery('input[name=con_type]:checked').val();
        con_method = jQuery('input[name=con_method]:checked').val();
        table = jQuery('#tables').val();
        table = jQuery('#tables').val();
        host = jQuery('#host_rem').val();
        port = jQuery('#port_rem').val();
        username = jQuery('#username_rem').val();
        password = jQuery('#password_rem').val();
        database = jQuery('#database_rem').val();
        str = con_type + "***wdfcon_typewdf***" + con_method + "***wdfcon_methodwdf***" + table + "***wdftablewdf***" + host + "***wdfhostwdf***" + port + "***wdfportwdf***" + username + "***wdfusernamewdf***" + password + "***wdfpasswordwdf***" + database + "***wdfdatabasewdf***";
        if (fields.length) {
          for (i = 0; i < fields.length; i++) {
            str += fields[i] + '***wdfnamewdf***' + jQuery('#' + fields[i]).val() + '***wdfvaluewdf***' + jQuery('#ch_' + fields[i] + ":checked").length + '***wdffieldwdf***';
          }
        }
        for (i = 1; i < cond_id; i++) {
          if (jQuery('#' + i).html()) {
            str += jQuery('#sel_' + i).val() + '***sel***' + jQuery('#op_' + i).val() + '***op***' + jQuery('#val_' + i).val() + '***val***' + jQuery('#andor_' + i).val() + '***where***';
          }
        }
        if (!jQuery('#query_txt').val()) {
          gen_query();
        }
        jQuery('#details').val(str);
        var datatxt = jQuery("#query_form").serialize() + '&form_id=' + jQuery("#form_id").val()+'&nonce=<?php echo $this->fm_nonce?>';
        if (jQuery('#query_txt').val()) {
          jQuery('.c1').html('<div class="fm-loading-container"><div class="fm-loading-content"></div></div>');
          jQuery.ajax({
            type: "POST",
            url: "<?php echo add_query_arg(array(
                                             'action' => 'FormMakerSQLMapping' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                             'id' => 0,
                                             'form_id' => $form_id,
                                             'task' => 'save_query',
                                             'width' => '1000',
                                             'height' => '500',
                                             'nonce' => $this->fm_nonce,
                                             'TB_iframe' => '1',
                                           ), admin_url('admin-ajax.php')); ?>",
            data: datatxt,
            success: function (data) {
              window.parent.wd_fm_apply_options('apply_form_options');
              if (typeof window.parent.set_role_hidden_value === "function") {
                window.parent.set_role_hidden_value();
              }
              window.parent.FormManageSubmitButton();
              window.parent.fm_set_input_value('task', 'apply');
              window.parent.document.getElementById('manage_form').submit();
            }
          });
        }
        else {
          alert('The query is empty.');
        }
        return false;
      }
      function gen_query() {
        if (jQuery('#query_txt').val()) {
          if (!confirm('Are you sure you want to replace the Query? All the modifications to the Query will be lost.')) {
            return;
          }
        }
        query = "";
        fields = new Array(<?php
          $fields = "";
          if ( $table_struct ) {
            foreach ( $table_struct as $col ) {
              $fields .= ' "' . str_replace("'", "SingleQuot", $col->Field) . '",';
            }
            echo substr($fields, 0, -1);
          }
          ?>);
        con_type = jQuery('input[name=con_type]:checked').val();
        con_method = jQuery('input[name=con_method]:checked').val();
        table = jQuery('#tables').val();
        fls = '';
        vals = '';
        valsA = new Array();
        flsA = new Array();
        if (fields.length) {
          for (i = 0; i < fields.length; i++) {
            if (jQuery('#ch_' + fields[i] + ":checked").length) {
              flsA.push(fields[i]);
              valsA.push(jQuery('#' + fields[i]).val());
            }
          }
        }
        if (con_method == "insert") {
          if (flsA.length) {
            for (i = 0; i < flsA.length - 1; i++) {
              fls += '`' + flsA[i] + '`, ';
              vals += '"' + valsA[i] + '", ';
            }
            fls += '`' + flsA[i] + '`';
            vals += '"' + valsA[i] + '"';
          }
          if (fls) {
            query = "INSERT INTO `" + jQuery('#tables').val() + "` (" + fls + ") VALUES (" + vals + ")";
          }
        }
        if (con_method == "update") {
          if (flsA.length) {
            for (i = 0; i < flsA.length - 1; i++) {
              vals += '`' + flsA[i] + '`="' + valsA[i] + '", ';
            }
            vals += '`' + flsA[i] + '`="' + valsA[i] + '"';
          }
          where = "";
          previous = '';
          for (i = 1; i < cond_id; i++) {
            if (jQuery('#' + i).html()) {
              if (jQuery('#op_' + i).val() == "%..%") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "..%") {
                op_val = ' LIKE "' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "%..") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '"';
              }
              else {
                op_val = ' ' + jQuery('#op_' + i).val() + ' "' + jQuery('#val_' + i).val() + '"';
              }
              where += previous + ' `' + jQuery('#sel_' + i).val() + '`' + op_val;
              previous = ' ' + jQuery('#andor_' + i).val();
            }
          }
          if (vals) {
            query = "UPDATE `" + jQuery('#tables').val() + "` SET " + vals + (where ? ' WHERE' + where : '');
          }
        }
        if (con_method == "delete") {
          where = "";
          previous = '';
          for (i = 1; i < cond_id; i++) {
            if (jQuery('#' + i).html()) {
              if (jQuery('#op_' + i).val() == "%..%") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "..%") {
                op_val = ' LIKE "' + jQuery('#val_' + i).val() + '%"';
              }
              else if (jQuery('#op_' + i).val() == "%..") {
                op_val = ' LIKE "%' + jQuery('#val_' + i).val() + '"';
              }
              else {
                op_val = ' ' + jQuery('#op_' + i).val() + ' "' + jQuery('#val_' + i).val() + '"';
              }
              where += previous + ' ' + jQuery('#sel_' + i).val() + op_val;
              previous = ' ' + jQuery('#andor_' + i).val();
            }
          }
          if (where) {
            query = "DELETE FROM `" + jQuery('#tables').val() + '` WHERE' + where;
          }
        }
        jQuery('#query_txt').val(query.replace("SingleQuot", "'"));
      }
      function insert_field(myValue) {
        if (!selected_field) {
          return;
        }
        myField = document.getElementById(selected_field);
        if (document.selection) {
          myField.focus();
          sel = document.selection.createRange();
          sel.text = myValue;
        }
        else {
          if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            myField.value = myField.value.substring(0, startPos)
              + "{" + myValue + "}"
              + myField.value.substring(endPos, myField.value.length);
          }
          else {
            myField.value += "{" + myValue + "}";
          }
        }
      }
    </script>
    <style>
      .ui-tooltip {
        padding: 0 10px !important;
        position: absolute;
        z-index: 9999;
        max-width: 300px;
        white-space: pre-line;
      }

      .ui-tooltip .ui-tooltip-content {
        font-weight: normal;
      }

      .ui-tooltip, .tooltip-arrow:after {
        background: #23282D;
      }

      .ui-tooltip {
        color: white;
        border-radius: 10px;
        font: bold 14px "Helvetica Neue", Sans-Serif;
        box-shadow: 0 0 7px black;
      }

      .ui-tooltip p {
        margin: 0;
      }

      .tooltip-arrow {
        width: 70px;
        height: 16px;
        overflow: hidden;
        position: absolute;
        left: 50%;
        margin-left: -35px;
        bottom: -16px;
      }

      .tooltip-arrow.top {
        top: -16px;
        bottom: auto;
      }

      .tooltip-arrow.left {
        left: 20%;
      }

      .tooltip-arrow:after {
        content: "";
        position: absolute;
        left: 20px;
        top: -20px;
        width: 25px;
        height: 25px;
        box-shadow: 6px 5px 9px -9px #23282D;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
      }

      .tooltip-arrow.top:after {
        bottom: -20px;
        top: auto;
      }
      .screen-reader-text, .screen-reader-text span, .ui-helper-hidden-accessible {
        border: 0;
        clip: rect(1px,1px,1px,1px);
        -webkit-clip-path: inset(50%);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
        word-wrap: normal!important;
      }
      .field-name {
        vertical-align: middle;
      }
      .wd-info {
        cursor: pointer;
        vertical-align: middle;
        color: #777777;
      }
      .wd-hide {
        display: none;
      }
      .cols div:nth-child(even) {
        background: #FFF;
      }

      .cols div:nth-child(odd) {
        background: #F5F5F5;
      }

      .cols div {
        height: 28px;
        padding: 5px;
      }

      .cols {
        display: inline-block;
      }

      .cols label {
        display: inline-block;
        width: 200px;
        font-size: 15px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        vertical-align: middle;
      }

      .cols input[type="text"] {
        width: 295px;
        line-height: 18px;
        height: 28px;
        border: 1px solid #ccc;
        padding: 0 3px;
        margin-right: 2px;
      }

      .cols input[type="text"]:disabled {
        cursor: not-allowed;
        background-color: #eee;
      }

      .cols input[type="checkbox"] {
        width: 20px;
        line-height: 18px;
        height: 20px;
        vertical-align: middle;
      }

      .cols select {
        line-height: 18px;
        height: 28px;
        margin-right: 2px;
      }

      #fieldlist {
        position: absolute;
        width: 225px;
        background: #fff;
        border: solid 1px #c7c7c7;
        top: 0;
        left: 0;
        z-index: 1000;
      }

      #fieldlist a {
        padding: 5px;
        cursor: pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      #fieldlist a:hover {
        background: #ccc;
      }

      .gen_query, .gen_query:focus {
        width: 148px;
        height: 38px;
        background: #4EC0D9;
        color: white;
        cursor: pointer;
        border: 0px;
        font-size: 14px;
        font-weight: bold;
        margin: 20px 0;
        border-radius: 0px;
      }

      .gen_query:active {
        background: #ccc;
      }

      .fm-query-save {
        float: right;
        font-size: 13px;
        margin: 0 20px;
        background: #4EC0D9;
        width: 83px;
        height: 34px;
        border: 1px solid #4EC0D9;
        border-radius: 0px;
        color: #fff;
        cursor: pointer;
      }

      .select-db-op {
        display: inline-block;
        text-align: right;
        font-weight: bold;
        font-size: 18px;
        padding: 5px 0;
        margin: 5px 0;
        background: none !important;
      }

      .select-db-op.where {
        width: 272px;
      }

      .select-db-op img {
        float: left;
      }
    </style>
    <?php
    if ( $table_struct ) { ?>
      <div class="cols">
        <?php
        if ( $con_method == 'insert' or $con_method == 'update' ) {
          echo '<div style="background: none;text-align: center;font-size: 20px;color: #000;font-weight: bold;"> SET </div>';
          foreach ( $table_struct as $key => $col ) {
            $title = $col->Field;
            $title .= "<ul style='padding-left: 17px;'>";
            if ( $col->Type ) {
              $title .= "<li>" . __('Type', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Type . "</li>";
            }
            if ( $col->Null ) {
              $title .= "<li>" . __('Null', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Null . "</li>";
            }
            if ( $col->Key ) {
              $title .= "<li>" . __('Key', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Key . "</li>";
            }
            if ( $col->Default ) {
              $title .= "<li>" . __('Default', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Default . "</li>";
            }
            if ( $col->Extra ) {
              $title .= "<li>" . __('Extra', WDFMInstance(self::PLUGIN)->prefix) . " - " . $col->Extra . "</li>";
            }
            $title .= "</ul>";
            ?>
            <div>
              <label>
                <b class="field-name"><?php echo $col->Field; ?></b>
                <i class="wd-info dashicons dashicons-info" data-id="wd-info-<?php echo $key; ?>"></i>
                <div id="wd-info-<?php echo $key; ?>" class="wd-hide">
                  <?php echo $title; ?>
                </div>
              </label>
              <input autocomplete="off" type="text" id="<?php echo str_replace("'", "SingleQuot", $col->Field); ?>" disabled="disabled" /><input id="ch_<?php echo str_replace("'", "SingleQuot", $col->Field); ?>" type="checkbox" onClick="dis('<?php echo str_replace("'", "SingleQuot", $col->Field); ?>', this.checked)" />
            </div>
            <?php
          }
        }
        if ( $con_method == 'delete' or $con_method == 'update' ) {
          echo '<div class="select-db-op where"><img src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/add_condition.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" title="ADD" class="add_cond"/>WHERE</div>';
        }
        ?>
      </div>
      <br />
      <input type="button" value="Generate Query" class="gen_query" onclick="gen_query()">
      <br />
      <form name="query_form" id="query_form">
        <label style="vertical-align: top; width: 102px; display: inline-block;" for="query_txt"> Query: </label><textarea id="query_txt" name="query" rows=5 style="width:428px"></textarea>
        <input type="hidden" name="details" id="details">
      </form>
      <input type="button" value="Save" class="fm-query-save" onclick="save_query()">
      <div id="fieldlist" style="display: none;">
        <?php echo $form_fields ?>
      </div>
      <?php
    }
    die();
  }
}
