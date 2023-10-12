<?php

/**
 * Class FMViewProduct_option
 */
class FMViewProduct_option extends FMAdminView {
  /**
   * Display.
   *
   * @param array $params
   */
  public function display( $params = array() ) {
    $fm_nonce = wp_create_nonce('fm_ajax_nonce');
    $field_id = $params['field_id'];
    $property_id = $params['property_id'];
    $url_for_ajax = $params['url_for_ajax'];
    ?>
    <style>
      #fm-paypal-properties {
        padding: 0 10px;
        font-family: Segoe UI !important;
      }

      .main {
        padding: 10px 0 15px 0;
      }

      .main span {
        float: left;
        font-size: 20px;
        font-weight: bold;
      }

      .fm-save {
        background: #4EC0D9;
        width: 78px;
        height: 32px;
        border: 1px solid #4EC0D9;
        border-radius: 6px;
        color: #fff;
        cursor: pointer;
        float: right;
      }

      .main:after {
        clear: both;
        content: '';
        display: block;
      }

      .fm-property-label {
        color: #000;
        font-size: 16px;
        margin-right: 20px;
      }

      .fm-property-value {
        width: 200px;
        border: 1px solid #ccc;
        padding: 4px;
      }

      .fm-properties > div {
        margin-top: 8px;
      }
    </style>
    <div id="fm-paypal-properties">
      <div class="main">
        <span>Properties</span>
        <button class="fm-save" onclick="save_options(); return false;">
          Save
          <span></span>
        </button>
      </div>
      <div class="fm-properties">
        <div>
          <label class="fm-property-label">Type </label>
          <select id="option_type" class="fm-property-value" onchange="type_add_predefined(this.value)">
            <option value="Custom" selected="selected">Custom</option>
            <option value="Color">Color</option>
            <option value="T-Shirt Size">T-Shirt Size</option>
            <option value="Print Size">Print Size</option>
            <option value="Screen Resolution">Screen Resolution</option>
            <option value="Shoe Size">Shoe Size</option>
          </select>
        </div>
        <div>
          <label class="fm-property-label" style="margin-right: 24px;">Title </label>
          <input type="text" class="fm-property-value" id="option_name" />
        </div>
        <div>
          <label class="fm-property-label">Properties</label> &nbsp;
          <img id="el_choices_add" src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/add.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . ''; ?>" style="vertical-align:middle; cursor: pointer;" title="add" onclick="add_choise_option()" />
        </div>
        <div style="margin-left:0px;" id="options"></div>
      </div>
    </div>
    <script>
      var j = 0;
      function save_options() {
        if (document.getElementById('option_name').value == '') {
          alert('The option must have a title.')
          return;
        }
        <?php
        if ( $property_id == '-1' ) {
        ?>
        for (i = 30; i >= 0; i--) {
          if (window.parent.document.getElementById(<?php echo $field_id; ?>+"_propertyform_id_temp" + i)) {
            i = i + 1;
            select_ = document.createElement('select');
            select_.setAttribute("id", <?php echo $field_id; ?>+"_propertyform_id_temp" + i);
            select_.setAttribute("name", <?php echo $field_id; ?>+"_propertyform_id_temp" + i);
            select_.style.cssText = "width:auto; margin:2px 0px";
            break;
          }
        }
        if (i == -1) {
          i = 0;
          select_ = document.createElement('select');
          select_.setAttribute("id", <?php echo $field_id; ?>+"_propertyform_id_temp" + i);
          select_.setAttribute("name", <?php echo $field_id; ?>+"_propertyform_id_temp" + i);
          select_.style.cssText = "width:auto; margin:2px 0px";
        }
        for (k = 0; k <= 50; k++) {
          if (document.getElementById('el_option' + k)) {
            var option = document.createElement('option');
            option.setAttribute("id", "<?php echo $field_id; ?>_" + i + "_option" + k);
            option.setAttribute("value", document.getElementById('el_option' + k).value);
            option.innerHTML = document.getElementById('el_option' + k).value;
            select_.appendChild(option);
          }
        }
        var select_label = document.createElement('label');
        select_label.innerHTML = document.getElementById('option_name').value;
        select_label.style.cssText = "margin-right:5px";
        select_label.setAttribute("class", 'mini_label');
        select_label.setAttribute("id", '<?php echo $field_id; ?>_property_label_form_id_temp' + i);
        var span_ = document.createElement('span');
        span_.style.cssText = "margin-right:15px";
        span_.setAttribute("id", '<?php echo $field_id; ?>_property_' + i);
        div_ = window.parent.document.getElementById("<?php echo $field_id; ?>_divform_id_temp");
        span_.appendChild(select_label);
        span_.appendChild(select_);
        div_.appendChild(span_);
        var li_ = document.createElement('li');
        li_.setAttribute("id", 'property_li_' + i);
        var li_label = document.createElement('label');
        li_label.innerHTML = document.getElementById('option_name').value;
        li_label.setAttribute("id", 'label_property_' + i);
        li_label.style.cssText = "font-weight:bold; font-size: 13px";
        var li_edit = document.createElement('a');
        li_edit.setAttribute("onclick", "tb_show('', 'admin-ajax.php?action=product_option&nonce=<?php echo $fm_nonce;?>&field_id=<?php echo $field_id; ?>&property_id=" + i + "&width=530&height=370&TB_iframe=1')");
        li_edit.setAttribute("class", "thickbox-preview" + i);
        var li_edit_img = document.createElement('span');
        li_edit_img.setAttribute("class", 'fm-edit-attribute fm-ico-edit');
        li_edit.appendChild(li_edit_img);
        var li_x = document.createElement('span');
        li_x.setAttribute("class", 'fm-remove-attribute dashicons dashicons-dismiss');
        li_x.setAttribute("onClick", 'remove_property(<?php echo $field_id; ?>,' + i + ')');
        ul_ = window.parent.document.getElementById("option_ul");
        li_.appendChild(li_label);
        li_.appendChild(li_edit);
        li_.appendChild(li_x);
        ul_.appendChild(li_);
        window.parent.tb_remove();
        <?php
        }
        else {
        ?>
        i = '<?php echo $property_id ?>';
        var select_ = window.parent.document.getElementById('<?php echo $field_id; ?>_propertyform_id_temp<?php echo $property_id; ?>');
        select_.innerHTML = "";
        for (k = 0; k <= j; k++) {
          if (document.getElementById('el_option' + k)) {
            var option = document.createElement('option');
            option.setAttribute("id", "<?php echo $field_id; ?>_" + i + "_option" + k);
            option.setAttribute("value", document.getElementById('el_option' + k).value);
            option.innerHTML = document.getElementById('el_option' + k).value;
            select_.appendChild(option);
          }
        }
        var select_label = document.createElement('label');
        select_label.innerHTML = document.getElementById('option_name').value;
        select_label.style.cssText = "margin-right:5px";
        select_label.setAttribute("class", 'mini_label');
        select_label.setAttribute("id", '<?php echo $field_id; ?>_property_label_form_id_temp' + i);
        var span_ = window.parent.document.getElementById('<?php echo $field_id; ?>_property_<?php echo $property_id; ?>');
        span_.innerHTML = '';
        span_.appendChild(select_label);
        span_.appendChild(select_);
        window.parent.document.getElementById('label_property_<?php echo $property_id; ?>').innerHTML = document.getElementById('option_name').value;
        <?php
        }
        ?>
        window.parent.tb_remove();
      }
      function type_add_predefined(type) {
        document.getElementById('options').innerHTML = '';
        switch (type) {
          case 'Custom': {
            w_choices = [];
            break;
          }
          case 'Color': {
            w_choices = ["Red", "Blue", "Green", "Yellow", "Black"];
            break;
          }
          case 'T-Shirt Size': {
            w_choices = ["XS", "S", "M", "L", "XL", "XXL", "XXXL"];
            break;
          }
          case 'Print Size': {
            w_choices = ["A4", "A3", "A2", "A1"];
            break;
          }
          case 'Screen Resolution': {
            w_choices = ["1024x768", "1152x864", "1280x768", "1280x800", "1280x960", "1280x1024", "1366x768", "1440x900", "1600x1200", "1680x1050", "1920x1080", "1920x1200"];
            break;
          }
          case 'Shoe Size': {
            w_choices = ["8", "8.5", "9", "9.5", "10", "10.5", "11", "11.5", "12", "13", "14"];
            break;
          }
        }
        type_add_options(w_choices);
      }
      function delete_options() {
        document.getElementById('options').innerHTML = '';
      }
      function type_add_options(w_choices) {
        i = 0;
        edit_main_td3 = document.getElementById('options');
        n = w_choices.length;
        for (j = 0; j < n; j++) {
          var br = document.createElement('br');
          br.setAttribute("id", "br" + j);
          var el_choices = document.createElement('input');
          el_choices.setAttribute("id", "el_option" + j);
          el_choices.setAttribute("type", "text");
          el_choices.setAttribute("value", w_choices[j]);
          el_choices.style.cssText = "width:100px; margin:0; padding:4px; border: 1px solid #ccc; vertical-align: middle;";
          var el_choices_remove = document.createElement('img');
          el_choices_remove.setAttribute("id", "el_option" + j + "_remove");
          el_choices_remove.setAttribute("src", '<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/delete.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . ''; ?>');
          el_choices_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:2px';
          el_choices_remove.setAttribute("align", 'top');
          el_choices_remove.setAttribute("onClick", "remove_option(" + j + "," + i + ")");
          edit_main_td3.appendChild(br);
          edit_main_td3.appendChild(el_choices);
          edit_main_td3.appendChild(el_choices_remove);
        }
      }
      function add_choise_option() {
        num = 0;
        j++;
        var choices_td = document.getElementById('options');
        var br = document.createElement('br');
        br.setAttribute("id", "br" + j);
        var el_choices = document.createElement('input');
        el_choices.setAttribute("id", "el_option" + j);
        el_choices.setAttribute("type", "text");
        el_choices.setAttribute("value", "");
        el_choices.style.cssText = "width:100px; margin:0; padding:4px; border: 1px solid #ccc; vertical-align: middle;";
        var el_choices_remove = document.createElement('img');
        el_choices_remove.setAttribute("id", "el_option" + j + "_remove");
        el_choices_remove.setAttribute("src", '<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/delete.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . ''; ?>');
        el_choices_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:2px';
        el_choices_remove.setAttribute("align", 'top');
        el_choices_remove.setAttribute("onClick", "remove_option('" + j + "','" + num + "')");
        choices_td.appendChild(br);
        choices_td.appendChild(el_choices);
        choices_td.appendChild(el_choices_remove);
      }
      function remove_option(id, num) {
        var choices_td = document.getElementById('options');
        var el_choices = document.getElementById('el_option' + id);
        var el_choices_remove = document.getElementById('el_option' + id + '_remove');
        var br = document.getElementById('br' + id);
        choices_td.removeChild(el_choices);
        choices_td.removeChild(el_choices_remove);
        choices_td.removeChild(br);
      }
      <?php if ($property_id != '-1') { ?>
      label_ = window.parent.document.getElementById('<?php echo $field_id; ?>_property_label_form_id_temp<?php echo $property_id; ?>').innerHTML;
      select_ = window.parent.document.getElementById('<?php echo $field_id; ?>_propertyform_id_temp<?php echo $property_id; ?>');
      n = select_.childNodes.length;
      delete_options();
      w_choices = [];
      document.getElementById('option_name').value = label_;
      for (k = 0; k < n; k++) {
        w_choices.push(select_.childNodes[k].value);
      }
      type_add_options(w_choices);
      <?php } ?>
    </script>
    <?php

    die();
  }
}
