<?php

/**
 * Class FMViewFromeditcountryinpopup
 */
class FMViewFromeditcountryinpopup extends FMAdminView {

  /**
   * Display.
   *
   * @param array $params
   */
  public function display( $params = array() ) {
    $field_id = $params['field_id'];
    wp_print_scripts('jquery');
    wp_print_scripts('jquery-ui-core');
    wp_print_scripts('jquery-ui-widget');
    wp_print_scripts('jquery-ui-mouse');
    wp_print_scripts('jquery-ui-slider');
    wp_print_scripts('jquery-ui-sortable');
    wp_print_styles('wp-admin');
    wp_print_styles('buttons');
    ?>
    <style>
      .handle {
        border: none;
        color: #aaaaaa;
        cursor: move;
        vertical-align: middle;
      }
      input[type="checkbox"] {
        margin: 5px;
      }
      .country-list {
        padding: 10px;
      }
      .country-list ul {
        font-family: Segoe UI !important;
        font-size: 13px;
        margin: 0;
      }
      .country-list > div {
        display: inline-block;
      }
      .save-cancel {
        float: right;
      }
      body {
        overflow-x: unset;
      }
    </style>
    <div class="country-list wp-core-ui">
      <div class="select-remove">
        <button class="button" onclick="toggleCheck(true); return false;">
          <?php _e('Select all', WDFMInstance(self::PLUGIN)->prefix); ?>
        </button>
        <button class="button" onclick="toggleCheck(false); return false;">
          <?php _e('Remove all', WDFMInstance(self::PLUGIN)->prefix); ?>
        </button>
      </div>
      <div class="save-cancel">
        <button class="button button-primary" onclick="save_list(); return false;">
          <?php _e('Save', WDFMInstance(self::PLUGIN)->prefix); ?>
        </button>
      </div>
      <ul id="countries_list" class="ui-sortable" style="list-style: none; padding: 0px;"></ul>
    </div>
    <script>
      var countries = '<?php echo addslashes(json_encode(WDW_FM_Library(self::PLUGIN)->get_countries())); ?>';
      countries = JSON.parse(countries);
      var select_ = window.parent.document.getElementById('<?php echo $field_id ?>_elementform_id_temp');
      var n = select_.childNodes.length;
      var saved_list = []
      for ( var i = 0; i < n; i++ ) {
        saved_list.push(select_.options[i].value);
      }
      var j = 0;
      for ( var i in countries ) {
        var drag = document.createElement('div');
        drag.setAttribute("class", "wd-drag handle dashicons dashicons-move");
        var ch = document.createElement('input');
        ch.setAttribute("type", "checkbox");
        if ( isValueInArray(saved_list, countries[i]) ) {
          ch.setAttribute("checked", "checked");
        }
        ch.value = i;
        ch.id = j + "ch";
        var p = document.createElement('span');
        p.style.cssText = "color: #000000; font-size: 13px; cursor: move; vertical-align: middle;";
        p.innerHTML = countries[i];
        var li = document.createElement('li');
        li.style.cssText = "margin:3px; vertical-align:middle";
        li.id = j;
        li.appendChild(drag);
        li.appendChild(ch);
        li.appendChild(p);
        document.getElementById('countries_list').appendChild(li);
        j++;
      }
      jQuery(function () {
        jQuery("#countries_list").sortable();
        jQuery("#countries_list").disableSelection();
      });
      function isValueInArray(arr, val) {
        inArray = false;
        for (x = 0; x < arr.length; x++) {
          if (val == arr[x]) {
            inArray = true;
          }
        }
        return inArray;
      }
      function save_list() {
        select_.innerHTML = ""
        ul = document.getElementById('countries_list');
        n = ul.childNodes.length;
        for (i = 0; i < n; i++) {
          if (ul.childNodes[i].tagName == "LI") {
            id = ul.childNodes[i].id;
            if (document.getElementById(id + 'ch').checked) {
              var option_ = document.createElement('option');
              option_.setAttribute("value", document.getElementById(id + 'ch').value);
              option_.innerHTML = document.getElementById(id + 'ch').value;
              select_.appendChild(option_);
            }
          }
        }
        window.parent.tb_remove();
      }
      function toggleCheck(toggle) {
        jQuery(".ui-sortable-handle input").each(function () {
          jQuery(this).prop("checked", toggle);
        });
      }
    </script>
    <?php

    die();
  }
}
