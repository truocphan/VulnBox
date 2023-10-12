<?php
class WDW_FM_Library {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  /**
   * The single instance of the class.
   */
  protected static $_instance = null;

  /**
   * Main WDW_FM_Library Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return WDW_FM_Library - Main instance.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public static $qpKeys = array(
    "\x00",
    "\x01",
    "\x02",
    "\x03",
    "\x04",
    "\x05",
    "\x06",
    "\x07",
    "\x08",
    "\x09",
    "\x0A",
    "\x0B",
    "\x0C",
    "\x0D",
    "\x0E",
    "\x0F",
    "\x10",
    "\x11",
    "\x12",
    "\x13",
    "\x14",
    "\x15",
    "\x16",
    "\x17",
    "\x18",
    "\x19",
    "\x1A",
    "\x1B",
    "\x1C",
    "\x1D",
    "\x1E",
    "\x1F",
    "\x7F",
    "\x80",
    "\x81",
    "\x82",
    "\x83",
    "\x84",
    "\x85",
    "\x86",
    "\x87",
    "\x88",
    "\x89",
    "\x8A",
    "\x8B",
    "\x8C",
    "\x8D",
    "\x8E",
    "\x8F",
    "\x90",
    "\x91",
    "\x92",
    "\x93",
    "\x94",
    "\x95",
    "\x96",
    "\x97",
    "\x98",
    "\x99",
    "\x9A",
    "\x9B",
    "\x9C",
    "\x9D",
    "\x9E",
    "\x9F",
    "\xA0",
    "\xA1",
    "\xA2",
    "\xA3",
    "\xA4",
    "\xA5",
    "\xA6",
    "\xA7",
    "\xA8",
    "\xA9",
    "\xAA",
    "\xAB",
    "\xAC",
    "\xAD",
    "\xAE",
    "\xAF",
    "\xB0",
    "\xB1",
    "\xB2",
    "\xB3",
    "\xB4",
    "\xB5",
    "\xB6",
    "\xB7",
    "\xB8",
    "\xB9",
    "\xBA",
    "\xBB",
    "\xBC",
    "\xBD",
    "\xBE",
    "\xBF",
    "\xC0",
    "\xC1",
    "\xC2",
    "\xC3",
    "\xC4",
    "\xC5",
    "\xC6",
    "\xC7",
    "\xC8",
    "\xC9",
    "\xCA",
    "\xCB",
    "\xCC",
    "\xCD",
    "\xCE",
    "\xCF",
    "\xD0",
    "\xD1",
    "\xD2",
    "\xD3",
    "\xD4",
    "\xD5",
    "\xD6",
    "\xD7",
    "\xD8",
    "\xD9",
    "\xDA",
    "\xDB",
    "\xDC",
    "\xDD",
    "\xDE",
    "\xDF",
    "\xE0",
    "\xE1",
    "\xE2",
    "\xE3",
    "\xE4",
    "\xE5",
    "\xE6",
    "\xE7",
    "\xE8",
    "\xE9",
    "\xEA",
    "\xEB",
    "\xEC",
    "\xED",
    "\xEE",
    "\xEF",
    "\xF0",
    "\xF1",
    "\xF2",
    "\xF3",
    "\xF4",
    "\xF5",
    "\xF6",
    "\xF7",
    "\xF8",
    "\xF9",
    "\xFA",
    "\xFB",
    "\xFC",
    "\xFD",
    "\xFE",
    "\xFF"
  );
  public static $qpReplaceValues = array(
    "=00",
    "=01",
    "=02",
    "=03",
    "=04",
    "=05",
    "=06",
    "=07",
    "=08",
    "=09",
    "=0A",
    "=0B",
    "=0C",
    "=0D",
    "=0E",
    "=0F",
    "=10",
    "=11",
    "=12",
    "=13",
    "=14",
    "=15",
    "=16",
    "=17",
    "=18",
    "=19",
    "=1A",
    "=1B",
    "=1C",
    "=1D",
    "=1E",
    "=1F",
    "=7F",
    "=80",
    "=81",
    "=82",
    "=83",
    "=84",
    "=85",
    "=86",
    "=87",
    "=88",
    "=89",
    "=8A",
    "=8B",
    "=8C",
    "=8D",
    "=8E",
    "=8F",
    "=90",
    "=91",
    "=92",
    "=93",
    "=94",
    "=95",
    "=96",
    "=97",
    "=98",
    "=99",
    "=9A",
    "=9B",
    "=9C",
    "=9D",
    "=9E",
    "=9F",
    "=A0",
    "=A1",
    "=A2",
    "=A3",
    "=A4",
    "=A5",
    "=A6",
    "=A7",
    "=A8",
    "=A9",
    "=AA",
    "=AB",
    "=AC",
    "=AD",
    "=AE",
    "=AF",
    "=B0",
    "=B1",
    "=B2",
    "=B3",
    "=B4",
    "=B5",
    "=B6",
    "=B7",
    "=B8",
    "=B9",
    "=BA",
    "=BB",
    "=BC",
    "=BD",
    "=BE",
    "=BF",
    "=C0",
    "=C1",
    "=C2",
    "=C3",
    "=C4",
    "=C5",
    "=C6",
    "=C7",
    "=C8",
    "=C9",
    "=CA",
    "=CB",
    "=CC",
    "=CD",
    "=CE",
    "=CF",
    "=D0",
    "=D1",
    "=D2",
    "=D3",
    "=D4",
    "=D5",
    "=D6",
    "=D7",
    "=D8",
    "=D9",
    "=DA",
    "=DB",
    "=DC",
    "=DD",
    "=DE",
    "=DF",
    "=E0",
    "=E1",
    "=E2",
    "=E3",
    "=E4",
    "=E5",
    "=E6",
    "=E7",
    "=E8",
    "=E9",
    "=EA",
    "=EB",
    "=EC",
    "=ED",
    "=EE",
    "=EF",
    "=F0",
    "=F1",
    "=F2",
    "=F3",
    "=F4",
    "=F5",
    "=F6",
    "=F7",
    "=F8",
    "=F9",
    "=FA",
    "=FB",
    "=FC",
    "=FD",
    "=FE",
    "=FF"
  );
  const LINELENGTH = 72;
  const LINEEND = "\n";

  /**
   * Get request value.
   *
   * @param string $key
   * @param string $default_value
   * @param bool $esc_html
   *
   * @return string|array
   */
  public static function get( $key, $default_value = '', $callback = 'sanitize_text_field' ) {
    if ( isset($_GET[$key]) ) {
      $value = $_GET[$key];
    }
    elseif ( isset($_POST[$key]) ) {
      $value = $_POST[$key];
    }
    elseif ( isset($_REQUEST[$key]) ) {
      $value = $_REQUEST[$key];
    }
    else {
      if ( $default_value === NULL ) {
        return NULL;
      }
      else {
        $value = $default_value;
      }
    }
    if ( strpos('wdc_equation', $key) !== FALSE ) {
      if ( is_array($value) ) {
        array_walk_recursive($value, array( 'self', 'validate_data' ), $callback);
      }
      else {
        self::validate_data($value, 0, $callback);
      }

      return $value;
    }
    else {
      $value = str_replace("%", "~", $value);
      if ( is_array($value) ) {
        array_walk_recursive($value, array( 'self', 'validate_data' ), $callback);
      }
      else {
        self::validate_data($value, 0, $callback);
      }
      $value = str_replace("~", "%", $value);

      return $value;
    }
  }

  /**
   * Validate data.
   *
   * @param $value
   * @param $esc_html
   */
  private static function validate_data(&$value, $key, $callback) {
    $value = stripslashes($value);
    if ( !empty($callback) && method_exists(__CLASS__, $callback) ) {
      $value = self::$callback($value);
    }
    else if (!empty($callback) && function_exists($callback)) {
      $value = $callback($value);
    }
  }

  /**
   * Validate function zero or one.
   *
   * @param string $value
   *
   * @return int
   */
  public static function zero_or_one( $value = '' ) {
    $value = !empty($value) ? 1 : 0;
    return $value;
  }

  /**
   * Generate Random string
   *
   * Using: Rename Uploaded file's Name;
   *
   * @param int $length
   * @param bool $type
   *
   * @return string|bool  return 'String' if $type is false, and 'Number' when $type is true
   */
  public static function generateRandomStrOrNum($length=6, $type=false) {
    $randomStrOrNum = '';
  	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    for ( $i = 0; $i < $length; $i++ ) {
    	if($type){
	   		$randomStrOrNum .= rand(0, 9);
			}
	 		else {
	   		$randomStrOrNum .= $characters[rand(0, $charactersLength - 1)];
			}
    }
    return $randomStrOrNum;
  }

  /**
   * Generate message container  by message id or directly by message.
   *
   * @param int $message_id
   * @param string $message If message_id is 0
   * @param string $type
   * @param function
   *
   * @return mixed|string|void
   */
  public static function message_id( $message_id, $message = '', $type = 'updated', $message_close_function = NULL ) {
    if ( $message_id ) {
      switch ( $message_id ) {
        case 1: {
          $message = 'Item Successfully Saved.';
          $type = 'updated';
          break;
        }
        case 2: {
          $message = 'Failed.';
          $type = 'error';
          break;
        }
        case 3: {
          $message = 'Item successfully deleted.';
          $type = 'updated';
          break;
        }
        case 4: {
          $message = "You can't delete default theme";
          $type = 'error';
          break;
        }
        case 5: {
          // Todo: delete message.
          $message = 'Items successfully deleted.';
          $type = 'updated';
          break;
        }
        case 6: {
          // Todo: delete message.
          $message = 'You must select at least one item.';
          $type = 'error';
          break;
        }
        case 7: {
          $message = 'The item is successfully set as default.';
          $type = 'updated';
          break;
        }
        case 8: {
          $message = 'Options Successfully Saved.';
          $type = 'updated';
          break;
        }
        case 9:
          $message = 'Item successfully published.';
          $type = 'updated';
          break;
        case 10:
          $message = 'Item successfully unpublished.';
          $type = 'updated';
          break;
        case 11:
          $message = 'Item successfully duplicated.';
          $type = 'updated';
          break;
        case 12:
          $message = 'IP Successfully Blocked.';
          $type = 'updated';
          break;
        case 13:
          $message = 'IP Successfully Unblocked.';
          $type = 'updated';
          break;
        case 14:
          $message = 'Submission Successfully Saved.';
          $type = 'updated';
          break;
        case 15:
          $message = 'Form was corrupted. Previous working revision is restored.';
          $type = 'error';
          break;
        case 16:
          $message = 'Form is corrupted.';
          $type = 'error';
          break;
        case 17:
          $message = 'Database user has no privilege to change database table structure.';
          break;
        case 18:
          $message = 'MySQL is in Read only mode. Unable to change database table structure.';
          break;
        default:
        {
          $message = '';
          break;
        }
      }
    }
    if ( $message ) {
      ob_start();
      ?>
      <div id="fm-notice" class="<?php echo $type; ?> inline fm-notice">
      <p>
        <strong><?php echo esc_html($message); ?></strong>
      </p>
      <?php if ( $message_close_function != NULL ) { ?>
        <button type="button" id="fm-notice-dismiss-button" class="fm-notice-dismiss-button">&#10006;</button>
      <?php } ?>
      </div>
      <?php
      if ( $message_close_function != NULL ) {
        $message_close_function();
      }
      $message = ob_get_clean();
    }
    else {
      $message = '';
    }

    return $message;
  }

  /**
   * Generate message.
   *
   * @param string $message
   * @param string $type
   *
   * @return mixed|string|void
   */
  public static function message($message, $type) {
    if ( $message ) {
      ob_start();
      ?><div class="fm-message <?php echo $type; ?>"><?php echo $message; ?></div><?php
      $message = ob_get_clean();
    }
    return $message;
  }

  public static function fm_container($theme_id, $form_body) {
    return '<div style="display:none" class="fm-form-container fm-theme' . $theme_id . '">' . $form_body . '</div>';
  }

  /**
   * Ordering.
   *
   * @param        $id
   * @param        $orderby
   * @param        $order
   * @param        $text
   * @param        $page_url
   * @param string $additional_class
   *
   * @return string
   */
  public static function ordering($id, $orderby, $order, $text, $page_url, $additional_class = '') {
    $class = array(
      ($orderby == $id ? 'sorted': 'sortable'),
      $order,
      $additional_class,
      'col_' . $id,
    );
    $order = (($orderby == $id) && ($order == 'asc')) ? 'desc' : 'asc';
    ob_start();
    ?>
    <th id="<?php echo $id; ?>" class="<?php echo implode(' ', $class); ?>">
      <a href="<?php echo add_query_arg( array('orderby' => $id, 'order' => $order), $page_url ); ?>"
         title="<?php _e('Click to sort by this item', WDFMInstance(self::PLUGIN)->prefix); ?>">
        <span><?php echo $text; ?></span><span class="sorting-indicator"></span>
      </a>
    </th>
    <?php
    return ob_get_clean();
  }

  //Todo: remove this function.
  public static function search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function fm_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
        function fm_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          if (document.getElementById("search_select_value")) {
            document.getElementById("search_select_value").value = 0;
          }
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
      </script>
      <div class="fm-search">
        <label for="search_value"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" value="<?php echo esc_html($search_value); ?>"/>
        <button class="fm-icon search-icon" onclick="fm_search()">
        </button>
        <button class="fm-icon reset-icon" onclick="fm_reset()">
        </button>
      </div>
    </div>
    <?php
  }


  //Todo: remove this function.
  public static function html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function fm_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }

        jQuery('#pagination_clicked').val('1');
        document.getElementById('<?php echo $form_id; ?>').submit();
      }

      function check_enter_key(e) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
            document.getElementById('page_number').value = items_county;
          }
          else {
            document.getElementById('page_number').value = jQuery('#current_page').val();
          }
          jQuery('#pagination_clicked').val('1');
          document.getElementById('<?php echo $form_id; ?>').submit();
        }
        return true;
      }
    </script>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $items_per_page) {
      $first_page = "first-page";
      $prev_page = "prev-page";
      $next_page = "next-page";
      $last_page = "last-page";
      if ($page_number == 1) {
        $first_page = "first-page disabled";
        $prev_page = "prev-page disabled";
        $next_page = "next-page";
        $last_page = "last-page";
      }
      if ($page_number >= $items_county) {
        $first_page = "first-page ";
        $prev_page = "prev-page";
        $next_page = "next-page disabled";
        $last_page = "last-page disabled";
      }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" href="javascript:fm_page(<?php echo $page_number; ?>,-2);">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" href="javascript:fm_page(<?php echo $page_number; ?>,-1);">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" href="javascript:fm_page(<?php echo $page_number; ?>,1);">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" href="javascript:fm_page(<?php echo $page_number; ?>,2);">»</a>
        <?php
        }
        ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo self::get( 'page_number', 1, 'intval' ); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo self::get( 'search_or_not', '', 'esc_html' ); ?>"/>
    <?php
  }

  public static function ajax_search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function fm_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          fm_ajax_save('<?php echo $form_id; ?>');
        }
        function fm_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          fm_ajax_save('<?php echo $form_id; ?>');
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:60px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" class="fm_search_value" value="<?php echo esc_html($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="fm_search()" class="button-secondary action">
        <input type="button" value="Reset" onclick="fm_reset()" class="button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function ajax_html_page_nav($count_items, $page_number, $form_id) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function fm_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        fm_ajax_save('<?php echo $form_id; ?>');
      }
      function check_enter_key(e) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
            document.getElementById('page_number').value = items_county;
          }
          else {
            document.getElementById('page_number').value = jQuery('#current_page').val();
          }

          fm_ajax_save('<?php echo $form_id; ?>');
          return false;
        }
        return true;
      }
    </script>
    <div id="tablenav-pages" class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $limit) {
      $first_page = "first-page";
      $prev_page = "prev-page";
      $next_page = "next-page";
      $last_page = "last-page";
      if ($page_number == 1) {
        $first_page = "first-page disabled";
        $prev_page = "prev-page disabled";
        $next_page = "next-page";
        $last_page = "last-page";
      }
      if ($page_number >= $items_county) {
        $first_page = "first-page ";
        $prev_page = "prev-page";
        $next_page = "next-page disabled";
        $last_page = "last-page disabled";
      }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" onclick="fm_page(<?php echo $page_number; ?>,-2)">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" onclick="fm_page(<?php echo $page_number; ?>,-1)">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" onclick="fm_page(<?php echo $page_number; ?>,1)">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" onclick="fm_page(<?php echo $page_number; ?>,2)">»</a>
        <?php
        }
        ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo self::get( 'page_number', 1, 'intval' ); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo self::get( 'search_or_not', '', 'esc_html' ); ?>"/>
    <?php
  }

  /**
   * FM Redirect.
   *
   * @param $url
   * @param bool $nonce
   */
  public static function fm_redirect( $url, $nonce = true ) {
    $url = html_entity_decode($url);
    if ( $nonce ) {
      $url = html_entity_decode(wp_nonce_url($url, WDFMInstance(self::PLUGIN)->nonce, WDFMInstance(self::PLUGIN)->nonce));
    }
    ?>
    <script>
      window.location = "<?php echo $url; ?>";
    </script>
    <?php
    exit();
  }

  public static function get_google_fonts() {
    $google_fonts = array( 'Open Sans' => 'Open Sans', 'Oswald' => 'Oswald', 'Droid Sans' => 'Droid Sans', 'Lato' => 'Lato', 'Open Sans Condensed' => 'Open Sans Condensed', 'PT Sans' => 'PT Sans', 'Ubuntu' => 'Ubuntu', 'PT Sans Narrow' => 'PT Sans Narrow', 'Yanone Kaffeesatz' => 'Yanone Kaffeesatz', 'Roboto Condensed' => 'Roboto Condensed', 'Source Sans Pro' => 'Source Sans Pro', 'Nunito' => 'Nunito', 'Francois One' => 'Francois One', 'Roboto' => 'Roboto', 'Raleway' => 'Raleway', 'Arimo' => 'Arimo', 'Cuprum' => 'Cuprum', 'Play' => 'Play', 'Dosis' => 'Dosis', 'Abel' => 'Abel', 'Droid Serif' => 'Droid Serif', 'Arvo' => 'Arvo', 'Lora' => 'Lora', 'Rokkitt' => 'Rokkitt', 'PT Serif' => 'PT Serif', 'Bitter' => 'Bitter', 'Merriweather' => 'Merriweather', 'Vollkorn' => 'Vollkorn', 'Cantata One' => 'Cantata One', 'Kreon' => 'Kreon', 'Josefin Slab' => 'Josefin Slab', 'Playfair Display' => 'Playfair Display', 'Bree Serif' => 'Bree Serif', 'Crimson Text' => 'Crimson Text', 'Old Standard TT' => 'Old Standard TT', 'Sanchez' => 'Sanchez', 'Crete Round' => 'Crete Round', 'Cardo' => 'Cardo', 'Noticia Text' => 'Noticia Text', 'Judson' => 'Judson', 'Lobster' => 'Lobster', 'Unkempt' => 'Unkempt', 'Changa One' => 'Changa One', 'Special Elite' => 'Special Elite', 'Chewy' => 'Chewy', 'Comfortaa' => 'Comfortaa', 'Boogaloo' => 'Boogaloo', 'Fredoka One' => 'Fredoka One', 'Luckiest Guy' => 'Luckiest Guy', 'Cherry Cream Soda' => 'Cherry Cream Soda', 'Lobster Two' => 'Lobster Two', 'Righteous' => 'Righteous', 'Squada One' => 'Squada One', 'Black Ops One' => 'Black Ops One', 'Happy Monkey' => 'Happy Monkey', 'Passion One' => 'Passion One', 'Nova Square' => 'Nova Square', 'Metamorphous' => 'Metamorphous', 'Poiret One' => 'Poiret One', 'Bevan' => 'Bevan', 'Shadows Into Light' => 'Shadows Into Light', 'The Girl Next Door' => 'The Girl Next Door', 'Coming Soon' => 'Coming Soon', 'Dancing Script' => 'Dancing Script', 'Pacifico' => 'Pacifico', 'Crafty Girls' => 'Crafty Girls', 'Calligraffitti' => 'Calligraffitti', 'Rock Salt' => 'Rock Salt', 'Amatic SC' => 'Amatic SC', 'Leckerli One' => 'Leckerli One', 'Tangerine' => 'Tangerine', 'Reenie Beanie' => 'Reenie Beanie', 'Satisfy' => 'Satisfy', 'Gloria Hallelujah' => 'Gloria Hallelujah', 'Permanent Marker' => 'Permanent Marker', 'Covered By Your Grace' => 'Covered By Your Grace', 'Walter Turncoat' => 'Walter Turncoat', 'Patrick Hand' => 'Patrick Hand', 'Schoolbell' => 'Schoolbell', 'Indie Flower' => 'Indie Flower' );
    return $google_fonts;
  }

  /**
   * Get google fonts used in themes and options.
   *
   * @return string
   */

  public static function get_all_used_google_fonts() {
    global $wpdb;
    $url = '';
    $google_array = array();
    $google_fonts = self::get_google_fonts();
    $sql = 'SELECT `fmt`.`css` FROM `' . $wpdb->prefix . 'formmaker` fm
			INNER JOIN `' . $wpdb->prefix . 'formmaker_themes` fmt ON (`fm`.`theme` = `fmt`.`id`)
			GROUP BY `fmt`.`id`';
    $results = $wpdb->get_results($sql, 'OBJECT');
    if ( $results ) {
      foreach ( $results as $row ) {
        if ( isset($row->css) ) {
          $options = json_decode($row->css);
          if ( !empty($options) ) {
            foreach ( $options as $option ) {
              $is_google_fonts = in_array((string) $option, $google_fonts) ? TRUE : FALSE;
              if ( TRUE == $is_google_fonts ) {
                $google_array[$option] = $option;
              }
            }
          }
        }
      }
    }
    if ( !empty($google_array) ) {
      $query = implode("|", str_replace(' ', '+', $google_array));
      $url = 'https://fonts.googleapis.com/css?family=' . $query;
      $url .= '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic&display=swap';
    }

    return $url;
  }

  public static function cleanData( &$str ) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if ( strstr($str, '"') ) {
      $str = '"' . str_replace('"', '""', $str) . '"';
    }
    $str = ltrim($str, '=+-@');
  }

  /**
   * Get display options.
   *
   * @param $id
   *
   * @return array|null|object|stdClass|void
   */
  public static function display_options( $id ) {
    global $wpdb;
	  $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_display_options as display WHERE form_id = ' . '%d', $id) );
	  $row = WDW_FM_Library::convert_json_options_to_old($row, 'display_options');
    if ( !$row ) {
      $row = new stdClass();
      $row->form_id = $id;
      $row->display_options = array(
        'type' => 'embedded',
        'scrollbox_loading_delay' => 0,
        'scrollbox_position' => 1,
        'scrollbox_trigger_point' => 20,
        'scrollbox_hide_duration' => 0,
        'scrollbox_auto_hide' => 1,
        'scrollbox_closing' => 1,
        'scrollbox_minimize' => 1,
        'scrollbox_minimize_text' => 'The form is minimized',
        'popover_animate_effect' => '',
        'popover_loading_delay' => 0,
        'popover_frequency' => 0,
        'topbar_position' => 1,
        'topbar_remain_top' => 1,
        'topbar_closing' => 1,
        'topbar_hide_duration' => 0,
        'display_on' => 'home,post,page',
        'posts_include' => '',
        'pages_include' => '',
        'display_on_categories' => 'select_all_categories',
        'current_categories' => 'select_all_categories',
        'show_for_admin' => 0,
        'hide_mobile' => 0,
      );
      $row->display_options = json_encode($row->display_options);
    }

    return $row;
  }

  /**
   * Get JS content.
   *
   * @param $form_id
   *
   * @return false|string|void
   *
   */
  public static function get_fm_js_content( $form_id = 0 ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker WHERE id=%d', $form_id));
    if ( !$row ) {
      return;
    }
    $row = self::convert_json_options_to_old( $row, 'form_options' );
    $pattern = '/^\/\/(.*)(\r\n|\r|\n)$/';
    $row_display = self::display_options($form_id);
    $row_display = self::convert_json_options_to_old($row_display, 'display_options');
    $row->javascript = str_replace('function before_reset()', 'function before_reset' . $form_id . '()', $row->javascript);
    $row->javascript = str_replace('function before_load()', 'function before_load' . $form_id . '()', $row->javascript);
    $row->javascript = str_replace('function before_submit()', 'function before_submit' . $form_id . '()', $row->javascript);
    $row->javascript = str_replace('function after_submit()', 'function after_submit' . $form_id . '()', $row->javascript);
    $check_js = '';
    $onload_js = '';
    $signature_tmp_resize_js = '';
    $signature_tmp_ready_js = '';
    $signature_js = '';
    $onsubmit_js = '';
    $form_currency = '$';
    if ( $row->payment_currency ) {
      $form_currency = $row->payment_currency;
    }
    $form_paypal_tax = 0;
    if ( $row->paypal_mode && $row->paypal_mode == 1 ) {
      $form_paypal_tax = $row->tax;
    }
    if ( $row->paypal_mode && $row->paypal_mode == 2 ) {
    	$form_currency = apply_filters('fm_form_currency', $form_currency, $form_id);
      $stripe_data = apply_filters('fm_addon_stripe_get_data_init', array('form_id' => $form_id));
      $form_paypal_tax = $stripe_data->stripe_tax;
    }
    $form_currency = self::replace_currency_code( $form_currency );
    $is_type = array();
    $id1s = array();
    $types = array();
    $labels = array();
    $paramss = array();
    $fields = explode('*:*new_field*:*', $row->form_fields);
    $fields = array_slice($fields, 0, count($fields) - 1);
    foreach ( $fields as $field ) {
      $temp = explode('*:*id*:*', $field);
      array_push($id1s, $temp[0]);
      $temp = explode('*:*type*:*', $temp[1]);
      array_push($types, $temp[0]);
      $temp = explode('*:*w_field_label*:*', $temp[1]);
      array_push($labels, $temp[0]);
      array_push($paramss, $temp[1]);
    }
    $labels_and_ids = array_combine($id1s, $types);
    $show_hide = array();
    $field_label = array();
    $all_any = array();
    $condition_params = array();
    $type_and_id = array();
    $condition_js = '';
    if ( $row->condition != "" ) {
      $conditions = explode('*:*new_condition*:*', $row->condition);
      $conditions = array_slice($conditions, 0, count($conditions) - 1);
      $count_of_conditions = count($conditions);
      foreach ( $conditions as $condition ) {
        $temp = explode('*:*show_hide*:*', $condition);
        array_push($show_hide, $temp[0]);
        $temp = explode('*:*field_label*:*', $temp[1]);
        array_push($field_label, $temp[0]);
        $temp = explode('*:*all_any*:*', $temp[1]);
        array_push($all_any, $temp[0]);
        array_push($condition_params, $temp[1]);
      }
      foreach ( $id1s as $id1s_key => $id1 ) {
        $type_and_id[$id1] = $types[$id1s_key];
      }
      for ( $k = 0; $k < $count_of_conditions; $k++ ) {
        if ( $show_hide[$k] ) {
          $display = 'removeAttr("style")';
          $display_none = 'css("display", "none")';
        }
        else {
          $display = 'css("display", "none")';
          $display_none = 'removeAttr("style")';
        }
        if ( $all_any[$k] == "and" ) {
          $or_and = '&&';
        }
        else {
          $or_and = '||';
        }
        if ( $condition_params[$k] ) {
          $cond_params = explode('*:*next_condition*:*', $condition_params[$k]);
          $cond_params = array_slice($cond_params, 0, count($cond_params) - 1);
          for ( $l = 0; $l < count($cond_params); $l++ ) {
            $params_value = explode('***', $cond_params[$l]);
            if ( !isset($type_and_id[$params_value[0]]) && strpos($params_value[0], '_address_') < 0 ) {
              unset($cond_params[$l]);
            }
          }
          $cond_params = array_values($cond_params);
          $documentOn = '';
          $if = '';
          $keyup = '';
          $change = '';
          $click = '';
          $blur = '';
          $ui_slider = '';

          preg_match ('/^\d+\_address_\d+$/', trim($field_label[$k]), $field_matches);
          $selector = ' div[wdid=' . $field_label[$k] . ']';
          if ( !empty($field_matches) ) {
            $selector = ' #wdform_' . $field_label[$k];
          }
          for ( $m = 0; $m < count($cond_params); $m++ ) {
            $params_value = explode('***', wp_specialchars_decode($cond_params[$m], 'single'));
            $type_key = $type_and_id[$params_value[0]];
            preg_match ('/^\d+\_address_\d+$/', trim($params_value[0]), $key_matches);
            if ( !empty($key_matches) ) {
              $type_key = 'type_address';
            }
            switch ( $type_key ) {
              case "type_text":
              case "type_hidden":
              case "type_star_rating":
              case "type_password":
              case "type_textarea":
              case "type_number":
              case "type_submitter_mail":
              case "type_spinner":
              case "type_paypal_price_new":
              case "type_date_new":
              case "type_phone_new":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 ';
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $params_value[2] = "";
                    $params_value[1] = $params_value[1] . "=";
                  }
                  if ( $type_and_id[$params_value[0]] == "type_star_rating" ) {
                    $if .= ' jQuery("#wdform_' . $params_value[0] . '_selected_star_amount' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                  }
                  else {
                    $if .= ' fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val() )' . $params_value[1] . '"' . $params_value[2] . '" ';
                  }
                }
                $keyup .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                if ( $type_and_id[$params_value[0]] == "type_date_new" ) {
                  $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                }
                if ( $type_and_id[$params_value[0]] == "type_spinner" ) {
                  $click .= '#wdform_' . $params_value[0] . '_element' . $form_id . ' ~ a, ';
                }
                if ( $type_and_id[$params_value[0]] == "type_star_rating" ) {
                  $change .= '#wdform_' . $params_value[0] . '_selected_star_amount' . $form_id . ', ';
                }
                if ( $type_and_id[$params_value[0]] == "type_phone_new" ) {
                  $blur = '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                }
                break;
              case "type_slider":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_slider_value' . $form_id . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 ';
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $params_value[2] = "";
                    $params_value[1] = $params_value[1] . "=";
                  }
                  $if .= ' fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_slider_value' . $form_id . '").val() )' . $params_value[1] . '"' . $params_value[2] . '" ';
                }
                $ui_slider .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                break;
              case "type_file_upload":
                if ( $params_value[1] == "!" ) {
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").get(0).files.length !== 0 ';
                }
                else {
                  if ( $params_value[1] == "=" ) {
                    $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").get(0).files.length === 0 ';
                  }
                }
                $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                break;
              case "type_time":
                  if($params_value[1] == "==") {
                    $if .= '!jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_mm' . $form_id . '").val() ).padStart(2, "0")' . $params_value[1] . '"' . $params_value[2] . '"' . '||' . '
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_mm' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").find(":selected").val()' . $params_value[1] . '"' . $params_value[2] . '"';
                  } else if ($params_value[1] == "!=") {
                    $if .= '!jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_mm' . $form_id . '").val() ).padStart(2, "0")' . $params_value[1] . '"' . $params_value[2] . '"' . '||' . '
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_mm' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").find(":selected").val()' . $params_value[1] . '"' . $params_value[2] . '"';
                  }
                  else if($params_value[1] == "%") {
                    $if .= '!jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0")'
                    . '==' . '"' . $params_value[2] . '"' . '.split(' . '":"' .')[0]' . '||' . '
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").find(":selected").val()' . '==' . '"' . $params_value[2]. '"'  .'.split(' . '":"' .')[0] ' . ' + '. '":"' . ' + ' . '"' .$params_value[2]. '"' . '.split(' . '":"' .')[2]';
                  }
                  else if($params_value[1] == "!%") {
                    $if .= '!jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0")'
                    . '!=' . '"' . $params_value[2] . '"' . '.split(' . '":"' .')[0]' . '||' . '
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").length
                    && fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ).padStart(2, "0") + ":" +
                    jQuery("#wdform_'. $params_value[0] .'_am_pm' . $form_id . '").find(":selected").val()' . '!=' . '"' . $params_value[2]. '"'  .'.split(' . '":"' .')[0] ' . ' + '. '":"' . ' + ' . '"' .$params_value[2]. '"' . '.split(' . '":"' .')[2]';
                  }
                  else if($params_value[1] == "=") {
                    $if .= 'fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ) == ""' . '&&' . '
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_mm' . $form_id . '").val() ) == ""' . '&&' . '
                    (jQuery("#wdform_' . $params_value[0] . '_ss' . $form_id . '").length ? fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_ss' . $form_id . '").val() )  == "" : true)';
                  } else if($params_value[1] == "!"){
                    $if .= '!(fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_hh' . $form_id . '").val() ) == ""' . '||' . '
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_mm' . $form_id . '").val() ) == ""' . '||' . '
                    (jQuery("#wdform_' . $params_value[0] . '_ss' . $form_id . '").length ? fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_ss' . $form_id . '").val() )  == "" : false))';
                  }
                $keyup .= '#wdform_' . $params_value[0] . '_hh' . $form_id . ', ' . '#wdform_' . $params_value[0] . '_mm' . $form_id . ', '. '#wdform_' . $params_value[0] . '_am_pm' . $form_id . ', ' . '#wdform_' . $params_value[0] . '_ss' . $form_id . ', ';
                break;
              case "type_name":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $extended0 = '';
                  $extended1 = '';
                  $extended2 = '';
                  $extended3 = '';
                  $normal0 = '';
                  $normal1 = '';
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $name_fields = explode(' ', $params_value[2]);
                  if ( $name_fields[0] != '' ) {
                    $extended0 = 'jQuery("#wdform_' . $params_value[0] . '_element_title' . $form_id . '").val().indexOf("' . $name_fields[0] . '")' . $like_or_not . '-1 ';
                    $normal0 = 'jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val().indexOf("' . $name_fields[0] . '")' . $like_or_not . '-1 ';
                  }
                  if ( isset($name_fields[1]) && $name_fields[1] != '' ) {
                    $extended1 = 'jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val().indexOf("' . $name_fields[1] . '")' . $like_or_not . '-1 ';
                    $normal1 = 'jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val().indexOf("' . $name_fields[1] . '")' . $like_or_not . '-1 ';
                  }
                  if ( isset($name_fields[2]) && $name_fields[2] != '' ) {
                    $extended2 = 'jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val().indexOf("' . $name_fields[2] . '")' . $like_or_not . '-1 ';
                  }
                  if ( isset($name_fields[3]) && $name_fields[3] != '' ) {
                    $extended3 = 'jQuery("#wdform_' . $params_value[0] . '_element_middle' . $form_id . '").val().indexOf("' . $name_fields[3] . '")' . $like_or_not . '-1 ';
                  }
                  if ( isset($name_fields[3]) ) {
                    $extended = '';
                    $normal = '';
                    if ( $extended0 ) {
                      $extended = $extended0;
                      if ( $extended1 ) {
                        $extended .= ' && ' . $extended1;
                        if ( $extended2 ) {
                          $extended .= ' && ' . $extended2;
                        }
                        if ( $extended3 ) {
                          $extended .= ' && ' . $extended3;
                        }
                      }
                      else {
                        if ( $extended2 ) {
                          $extended .= ' && ' . $extended2;
                        }
                        if ( $extended3 ) {
                          $extended .= ' && ' . $extended3;
                        }
                      }
                    }
                    else {
                      if ( $extended1 ) {
                        $extended = $extended1;
                        if ( $extended2 ) {
                          $extended .= ' && ' . $extended2;
                        }
                        if ( $extended3 ) {
                          $extended .= ' && ' . $extended3;
                        }
                      }
                      else {
                        if ( $extended2 ) {
                          $extended = $extended2;
                          if ( $extended3 ) {
                            $extended .= ' && ' . $extended3;
                          }
                        }
                        else {
                          if ( $extended3 ) {
                            $extended = $extended3;
                          }
                        }
                      }
                    }
                    if ( $normal0 ) {
                      $normal = $normal0;
                      if ( $normal1 ) {
                        $normal .= ' && ' . $normal1;
                      }
                    }
                    else {
                      if ( $normal1 ) {
                        $normal = $normal1;
                      }
                    }
                  }
                  else {
                    if ( isset($name_fields[2]) ) {
                      $extended = "";
                      $normal = "";
                      if ( $extended0 ) {
                        $extended = $extended0;
                        if ( $extended1 ) {
                          $extended .= ' && ' . $extended1;
                        }
                        if ( $extended2 ) {
                          $extended .= ' && ' . $extended2;
                        }
                      }
                      else {
                        if ( $extended1 ) {
                          $extended = $extended1;
                          if ( $extended2 ) {
                            $extended .= ' && ' . $extended2;
                          }
                        }
                        else {
                          if ( $extended2 ) {
                            $extended = $extended2;
                          }
                        }
                      }
                      if ( $normal0 ) {
                        $normal = $normal0;
                        if ( $normal1 ) {
                          $normal .= ' && ' . $normal1;
                        }
                      }
                      else {
                        if ( $normal1 ) {
                          $normal = $normal1;
                        }
                      }
                    }
                    else {
                      if ( isset($name_fields[1]) ) {
                        $extended = '';
                        $normal = '';
                        if ( $extended0 ) {
                          if ( $extended1 ) {
                            $extended = $extended0 . ' && ' . $extended1;
                          }
                          else {
                            $extended = $extended0;
                          }
                        }
                        else {
                          if ( $extended1 ) {
                            $extended = $extended1;
                          }
                        }
                        if ( $normal0 ) {
                          if ( $normal1 ) {
                            $normal = $normal0 . ' && ' . $normal1;
                          }
                          else {
                            $normal = $normal0;
                          }
                        }
                        else {
                          if ( $normal1 ) {
                            $normal = $normal1;
                          }
                        }
                      }
                      else {
                        $extended = $extended0;
                        $normal = $normal0;
                      }
                    }
                  }
                  if ( $extended != "" && $normal != "" ) {
                    $if .= ' ((jQuery("#wdform_' . $params_value[0] . '_element_title' . $form_id . '").length != 0 || jQuery("#wdform_' . $params_value[0] . '_element_middle' . $form_id . '").length != 0) ?  ' . $extended . ' : ' . $normal . ') ';
                  }
                  else {
                    $if .= ' true';
                  }
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $name_and_or = $params_value[1] == "=" ? "&&" : "||";
                    $name_empty_or_not = $params_value[1] . "=";
                    $extended = ' (jQuery("#wdform_' . $params_value[0] . '_element_title' . $form_id . '").val()' . $name_empty_or_not . '"" ' . $name_and_or . ' jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $name_empty_or_not . '"" ' . $name_and_or . ' jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $name_empty_or_not . '"" ' . $name_and_or . ' jQuery("#wdform_' . $params_value[0] . '_element_middle' . $form_id . '").val()' . $name_empty_or_not . '"") ';
                    $normal = ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $name_empty_or_not . '"" ' . $name_and_or . ' jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $name_empty_or_not . '"") ';
                    $if .= ' ((jQuery("#wdform_' . $params_value[0] . '_element_title' . $form_id . '").length != 0 || jQuery("#wdform_' . $params_value[0] . '_element_middle' . $form_id . '").length != 0) ?  ' . $extended . ' : ' . $normal . ') ';
                  }
                  else {
                    $extended0 = '';
                    $extended1 = '';
                    $extended2 = '';
                    $extended3 = '';
                    $normal0 = '';
                    $normal1 = '';
                    $name_fields = explode(' ', $params_value[2]);
                    if ( $name_fields[0] != '' ) {
                      $extended0 = 'jQuery("#wdform_' . $params_value[0] . '_element_title' . $form_id . '").val()' . $params_value[1] . '"' . $name_fields[0] . '"';
                      $normal0 = 'jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $params_value[1] . '"' . $name_fields[0] . '"';
                    }
                    if ( isset($name_fields[1]) && $name_fields[1] != '' ) {
                      $extended1 = 'jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $params_value[1] . '"' . $name_fields[1] . '"';
                      $normal1 = 'jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $params_value[1] . '"' . $name_fields[1] . '"';
                    }
                    if ( isset($name_fields[2]) && $name_fields[2] != '' ) {
                      $extended2 = 'jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $params_value[1] . '"' . $name_fields[2] . '"';
                    }
                    if ( isset($name_fields[3]) && $name_fields[3] != '' ) {
                      $extended3 = 'jQuery("#wdform_' . $params_value[0] . '_element_middle' . $form_id . '").val()' . $params_value[1] . '"' . $name_fields[3] . '"';
                    }
                    if ( isset($name_fields[3]) ) {
                      $extended = '';
                      $normal = '';
                      if ( $extended0 ) {
                        $extended = $extended0;
                        if ( $extended1 ) {
                          $extended .= ' && ' . $extended1;
                          if ( $extended2 ) {
                            $extended .= ' && ' . $extended2;
                          }
                          if ( $extended3 ) {
                            $extended .= ' && ' . $extended3;
                          }
                        }
                        else {
                          if ( $extended2 ) {
                            $extended .= ' && ' . $extended2;
                          }
                          if ( $extended3 ) {
                            $extended .= ' && ' . $extended3;
                          }
                        }
                      }
                      else {
                        if ( $extended1 ) {
                          $extended = $extended1;
                          if ( $extended2 ) {
                            $extended .= ' && ' . $extended2;
                          }
                          if ( $extended3 ) {
                            $extended .= ' && ' . $extended3;
                          }
                        }
                        else {
                          if ( $extended2 ) {
                            $extended = $extended2;
                            if ( $extended3 ) {
                              $extended .= ' && ' . $extended3;
                            }
                          }
                          else {
                            if ( $extended3 ) {
                              $extended = $extended3;
                            }
                          }
                        }
                      }
                      if ( $normal0 ) {
                        $normal = $normal0;
                        if ( $normal1 ) {
                          $normal .= ' && ' . $normal1;
                        }
                      }
                      else {
                        if ( $normal1 ) {
                          $normal = $normal1;
                        }
                      }
                    }
                    else {
                      if ( isset($name_fields[2]) ) {
                        $extended = "";
                        $normal = "";
                        if ( $extended0 ) {
                          $extended = $extended0;
                          if ( $extended1 ) {
                            $extended .= ' && ' . $extended1;
                          }
                          if ( $extended2 ) {
                            $extended .= ' && ' . $extended2;
                          }
                        }
                        else {
                          if ( $extended1 ) {
                            $extended = $extended1;
                            if ( $extended2 ) {
                              $extended .= ' && ' . $extended2;
                            }
                          }
                          else {
                            if ( $extended2 ) {
                              $extended = $extended2;
                            }
                          }
                        }
                        if ( $normal0 ) {
                          $normal = $normal0;
                          if ( $normal1 ) {
                            $normal .= ' && ' . $normal1;
                          }
                        }
                        else {
                          if ( $normal1 ) {
                            $normal = $normal1;
                          }
                        }
                      }
                      else {
                        if ( isset($name_fields[1]) ) {
                          $extended = '';
                          $normal = '';
                          if ( $extended0 ) {
                            if ( $extended1 ) {
                              $extended = $extended0 . ' && ' . $extended1;
                            }
                            else {
                              $extended = $extended0;
                            }
                          }
                          else {
                            if ( $extended1 ) {
                              $extended = $extended1;
                            }
                          }
                          if ( $normal0 ) {
                            if ( $normal1 ) {
                              $normal = $normal0 . ' && ' . $normal1;
                            }
                            else {
                              $normal = $normal0;
                            }
                          }
                          else {
                            if ( $normal1 ) {
                              $normal = $normal1;
                            }
                          }
                        }
                        else {
                          $extended = $extended0;
                          $normal = $normal0;
                        }
                      }
                    }
                    if ( $extended != "" && $normal != "" ) {
                      $if .= ' ((jQuery("#wdform_' . $params_value[0] . '_element_title' . $form_id . '").length != 0 || jQuery("#wdform_' . $params_value[0] . '_element_middle' . $form_id . '").length != 0) ?  ' . $extended . ' : ' . $normal . ') ';
                    }
                    else {
                      $if .= ' true';
                    }
                  }
                }
                $keyup .= '#wdform_' . $params_value[0] . '_element_title' . $form_id . ', #wdform_' . $params_value[0] . '_element_first' . $form_id . ', #wdform_' . $params_value[0] . '_element_last' . $form_id . ', #wdform_' . $params_value[0] . '_element_middle' . $form_id . ', ';
                break;
              case "type_phone":
                if ( $params_value[1] == "==" || $params_value[1] == "!=" ) {
                  $phone_fields = explode(' ', $params_value[2]);
                  if ( isset($phone_fields[1]) ) {
                    if ( $phone_fields[0] != '' && $phone_fields[1] != '' ) {
                      $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $params_value[1] . '"' . $phone_fields[0] . '" && jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $params_value[1] . '"' . $phone_fields[1] . '") ';
                    }
                    else {
                      if ( $phone_fields[0] == '' ) {
                        $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $params_value[1] . '"' . $phone_fields[1] . '") ';
                      }
                      else {
                        if ( $phone_fields[1] == '' ) {
                          $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $params_value[1] . '"' . $phone_fields[1] . '") ';
                        }
                      }
                    }
                  }
                  else {
                    $if .= ' jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                  }
                }
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $phone_fields = explode(' ', $params_value[2]);
                  if ( isset($phone_fields[1]) ) {
                    if ( $phone_fields[0] != '' && $phone_fields[1] != '' ) {
                      $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val().indexOf("' . $phone_fields[0] . '")' . $like_or_not . '-1 && jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val().indexOf("' . $phone_fields[1] . '")' . $like_or_not . '-1)';
                    }
                    else {
                      if ( $phone_fields[0] == '' ) {
                        $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val().indexOf("' . $phone_fields[1] . '")' . $like_or_not . '-1) ';
                      }
                      else {
                        if ( $phone_fields[1] == '' ) {
                          $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val().indexOf("' . $phone_fields[0] . '")' . $like_or_not . '-1) ';
                        }
                      }
                    }
                  }
                  else {
                    $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val().indexOf("' . $phone_fields[0] . '")' . $like_or_not . '-1) ';
                  }
                }
                if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                  $params_value[2] = "";
                  $and_or_phone = ($params_value[1] == "=" ? "&&" : "||");
                  $params_value[1] = $params_value[1] . "=";
                  $if .= ' (jQuery("#wdform_' . $params_value[0] . '_element_first' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ' . $and_or_phone . ' jQuery("#wdform_' . $params_value[0] . '_element_last' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '") ';
                }
                $keyup .= '#wdform_' . $params_value[0] . '_element_first' . $form_id . ', #wdform_' . $params_value[0] . '_element_last' . $form_id . ', ';
                break;
              case "type_paypal_price":
                if ( $params_value[1] == "==" || $params_value[1] == "!=" ) {
                  $if .= ' (jQuery("#wdform_' . $params_value[0] . '_td_name_cents").attr("style") == "display: none;" ? jQuery("#wdform_' . $params_value[0] . '_element_dollars' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" : parseFloat(jQuery("#wdform_' . $params_value[0] . '_element_dollars' . $form_id . '").val()+"."+jQuery("#wdform_' . $params_value[0] . '_element_cents' . $form_id . '").val())' . $params_value[1] . 'parseFloat("' . str_replace('.0', '.', $params_value[2]) . '"))';
                }
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' (jQuery("#wdform_' . $params_value[0] . '_td_name_cents").attr("style") == "display: none;" ? jQuery("#wdform_' . $params_value[0] . '_element_dollars' . $form_id . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 : (jQuery("#wdform_' . $params_value[0] . '_element_dollars' . $form_id . '").val()+"."+jQuery("#wdform_' . $params_value[0] . '_element_cents' . $form_id . '").val()).indexOf("' . str_replace('.0', '.', $params_value[2]) . '")' . $like_or_not . '-1) ';
                }
                if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                  $params_value[2] = "";
                  $and_or_price = ($params_value[1] == "=" ? "&&" : "||");
                  $params_value[1] = $params_value[1] . "=";
                  $if .= ' (jQuery("#wdform_' . $params_value[0] . '_td_name_cents").attr("style") == "display: none;" ? jQuery("#wdform_' . $params_value[0] . '_element_dollars' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" : (jQuery("#wdform_' . $params_value[0] . '_element_dollars' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ' . $and_or_price . ' jQuery("#wdform_' . $params_value[0] . '_element_cents' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '"))';
                }
                $keyup .= '#wdform_' . $params_value[0] . '_element_dollars' . $form_id . ', #wdform_' . $params_value[0] . '_element_cents' . $form_id . ', ';
                break;
              case "type_own_select":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 ';
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $params_value[2] = "";
                    $params_value[1] = $params_value[1] . "=";
                  }
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                }
                $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                break;
              case "type_paypal_select":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 ';
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $params_value[2] = "";
                    $params_value[1] = $params_value[1] . "=";
                    $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '"';
                  }
                  else {
                    if ( strpos($params_value[2], '*:*value*:*') > -1 ) {
                      $and_or = $params_value[1] == "==" ? '&&' : '||';
                      $choise_and_value = explode("*:*value*:*", $params_value[2]);
                      $params_value[2] = $choise_and_value[1];
                      $params_label = $choise_and_value[0];
                      $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ' . $and_or . ' jQuery("div[wdid=' . $params_value[0] . '] select option:selected").text()' . $params_value[1] . '"' . $params_label . '" ';
                    }
                    else {
                      $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                    }
                  }
                }
                $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                break;
              case "type_address":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' && jQuery(".wdform_' . $params_value[0] . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 ';
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $params_value[2] = "";
                    $params_value[1] = $params_value[1] . "=";
                  }
                  $if .= ' jQuery(".wdform_' . $params_value[0] . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                }
                $change .= '.wdform_' . $params_value[0] . ', ';
                break;
              case "type_country":
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 ';
                }
                else {
                  if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                    $params_value[2] = "";
                    $params_value[1] = $params_value[1] . "=";
                  }
                  $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                }
                $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                break;
              case "type_radio":
              case "type_paypal_radio":
              case "type_paypal_shipping":
                if ( $params_value[1] == "==" || $params_value[1] == "!=" ) {
                  if ( strpos($params_value[2], '*:*value*:*') > -1 ) {
                    $and_or = $params_value[1] == "==" ? '&&' : '||';
                    $choise_and_value = explode("*:*value*:*", $params_value[2]);
                    $params_value[2] = $choise_and_value[1];
                    $params_label = $choise_and_value[0];
                    $if .= ' jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").val()' . $params_value[1] . '"' . $params_value[2] . '" ' . $and_or . ' jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").attr("title")' . $params_value[1] . '"' . $params_label . '" ';
                  }
                  else {
                    $if .= ' jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").val()' . $params_value[1] . '"' . $params_value[2] . '" ';
                  }
                  $click .= 'div[wdid=' . $params_value[0] . '] input[type=\'radio\'], ';
                }
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $click .= 'div[wdid=' . $params_value[0] . '] input[type=\'radio\'], ';
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  $if .= ' (jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").val() ? (jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").attr("other") ? false  : (jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").val().indexOf("' . $params_value[2] . '")' . $like_or_not . '-1 )) : false) ';
                }
                if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                  $ckecked_or_no = ($params_value[1] == "=" ? "!" : "");
                  $if .= ' ' . $ckecked_or_no . 'jQuery("input[name^=\'wdform_' . $params_value[0] . '_element' . $form_id . '\']:checked").val()';
                  $click .= 'div[wdid=' . $params_value[0] . '] input[type=\'radio\'], ';
                }
                break;
              case "type_checkbox":
              case "type_paypal_checkbox":
                if ( $params_value[1] == "==" || $params_value[1] == "!=" ) {
                  if ( $params_value[2] ) {
                    $choises = explode('@@@', $params_value[2]);
                    $choises = array_slice($choises, 0, count($choises) - 1);
                    if ( $params_value[1] == "!=" ) {
                      $is = "!";
                    }
                    else {
                      $is = "";
                    }
                    foreach ( $choises as $key1 => $choise ) {
                      if ( $type_and_id[$params_value[0]] == "type_paypal_checkbox" ) {
                        $choise_and_value = explode("*:*value*:*", $choise);
                        $if .= ' ' . $is . '(jQuery("#form' . $form_id . ' div[wdid=' . $params_value[0] . '] input[value=\"' . $choise_and_value[1] . '\"]").is(":checked") && jQuery("div[wdid=' . $params_value[0] . '] input[title=\"' . $choise_and_value[0] . '\"]"))';
                      }
                      else {
                        $if .= ' ' . $is . 'jQuery("#form' . $form_id . ' div[wdid=' . $params_value[0] . '] input[value=\"' . $choise . '\"]").is(":checked") ';
                      }
                      if ( $key1 != count($choises) - 1 ) {
                        $if .= '&&';
                      }
                    }
                    $click .= 'div[wdid=' . $params_value[0] . '] input[type=\'checkbox\'], ';
                  }
                  else {
                    if ( $or_and == '&&' ) {
                      $if .= ' true';
                    }
                    else {
                      $if .= ' false';
                    }
                  }
                }
                if ( $params_value[1] == "%" || $params_value[1] == "!%" ) {
                  $like_or_not = ($params_value[1] == "%" ? ">" : "==");
                  if ( $params_value[2] ) {
                    $choises = explode('@@@', $params_value[2]);
                    $choises = array_slice($choises, 0, count($choises) - 1);
                    if ( $type_and_id[$params_value[0]] == "type_paypal_checkbox" ) {
                      foreach ( $choises as $key1 => $choise ) {
                        $choise_and_value = explode("*:*value*:*", $choise);
                        $if .= ' jQuery("div[wdid=' . $params_value[0] . ']  input[type=\"checkbox\"]:checked").serialize().indexOf("' . $choise_and_value[1] . '")' . $like_or_not . '-1 ';
                        if ( $key1 != count($choises) - 1 ) {
                          $if .= '&&';
                        }
                      }
                    }
                    else {
                      foreach ( $choises as $key1 => $choise ) {
                        $if .= ' jQuery("div[wdid=' . $params_value[0] . ']  input[type=\"checkbox\"]:checked").serialize().indexOf("' . str_replace(" ", "+", $choise) . '")' . $like_or_not . '-1 ';
                        if ( $key1 != count($choises) - 1 ) {
                          $if .= '&&';
                        }
                      }
                    }
                    $click .= 'div[wdid=' . $params_value[0] . '] input[type=\'checkbox\'], ';
                  }
                  else {
                    if ( $or_and == '&&' ) {
                      $if .= ' true';
                    }
                    else {
                      $if .= ' false';
                    }
                  }
                }
                if ( $params_value[1] == "=" || $params_value[1] == "!" ) {
                  $ckecked_or_no = ($params_value[1] == "=" ? "==" : ">");
                  $if .= ' jQuery("div[wdid=' . $params_value[0] . '] input[type=\"checkbox\"]:checked").length' . $ckecked_or_no . '0 ';
                  $click .= 'div[wdid=' . $params_value[0] . '] input[type=\'checkbox\'], ';
                }
                break;
              case "type_send_copy":
                $if .= ' jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '").is(":checked") ';
                if ( $params_value[1] == "unchecked" ) {
                  $display = 'css("display", "none")';
                  $display_none = 'removeAttr("style")';
                }
                $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . ', ';
                break;
              case "type_range":
                $value  = $params_value[2];
                $for = 'jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '0").val() ';
                $to = 'jQuery("#wdform_' . $params_value[0] . '_element' . $form_id . '1").val() ';
                $condition = $params_value[1];
                if ( $condition == 'in_range' ) {
                  $if .= ' ' . $value . ' >= ' . $for . ' && ' . $value . ' <= ' . $to . ' ';
                }
                else if ( $condition == 'out_range' ) {
                  $if .= ' (' . $value . ' <= ' . $for . ' && ' . $to . ' >= ' . $value . ') || (' . $value . ' >= ' . $for . ' && ' . $value .' >= ' . $to . ' ) ';
                }
                else if ( $condition == 'empty' ) {
                  $if .= ' (' . $for . ' == "" || ' . $for . ' == null) && (' . $to . ' == "" || ' . $to . ' == null) ';
                }

                $change .= '#wdform_' . $params_value[0] . '_element' . $form_id . '0, #wdform_' . $params_value[0] . '_element' . $form_id . '1, ';
                $documentOn .= '"click", ".wdform_row[wdid=' . $params_value[0] . '] .ui-spinner .ui-widget.ui-spinner-button"';
                break;
              case "type_date_fields":
                if($params_value[1] == "==") {
                  $if .= ' fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_day' . $form_id . '").val() ).padStart(2, "0") + "/" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_month' . $form_id . '").val() ).padStart(2, "0") + "/" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_year' . $form_id . '").val() ).padStart(4, "0")' . $params_value[1] . '"' . $params_value[2]. '"';
                } else if ($params_value[1] == "!=") {
                  $if .= ' fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_day' . $form_id . '").val() ).padStart(2, "0") + "/" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_month' . $form_id . '").val() ).padStart(2, "0") + "/" +
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_year' . $form_id . '").val() ).padStart(4, "0")' . $params_value[1] . '"' . $params_value[2]. '"';
                } else if($params_value[1] == "=") {
                  $if .= ' (fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_day' . $form_id . '").val() ) == "" && ' . '
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_month' . $form_id . '").val() ) == "" && ' . '
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_year' . $form_id . '").val() ) == "")';
                } else if($params_value[1] == "!"){
                  $if .= ' (fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_day' . $form_id . '").val() ) != "" && ' . '
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_month' . $form_id . '").val() ) != "" && ' . '
                    fm_html_entities( jQuery("#wdform_' . $params_value[0] . '_year' . $form_id . '").val() ) != "")';
                }
                $change .= '#wdform_' . $params_value[0] . '_day' . $form_id . ', ' . '#wdform_' . $params_value[0] . '_month' . $form_id . ', '. '#wdform_' . $params_value[0] . '_year' . $form_id . ', ';
                break;
            }

            if ( $m != count($cond_params) - 1 ) {
              $params_value_next = explode('***', $cond_params[$m + 1]);
              if ( strpos($params_value_next[0], '_address_') > 0 ) {
                $field_id = explode('_address_', $params_value_next[0]);
                $params_value_next[0] = $field_id[0];
              }
              if ( isset($type_and_id[$params_value_next[0]]) ) {
                $if .= $or_and;
              }
            }
          }
          $if = ltrim($if, ' && ');
          if ( $if ) {
            $condition_js .= '
            if(' . $if . ') {
              jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
            }
            else {
              jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
            }';
          }
          if ( $keyup ) {
            $condition_js .= '
            jQuery("' . substr($keyup, 0, -2) . '").on("keyup change", function() {
              if(' . $if . ') {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
              }
              else {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
              }
              set_total_value(' . $form_id . ');
              if ( jQuery("#form' . $form_id . ' div[type=\'type_signature\']").length && typeof fm_signature_init != \'undefined\' ) {
                fm_signature_init();
              }
            });';
          }
          if ( $change ) {
            $condition_js .= '
            jQuery("' . substr($change, 0, -2) . '").change(function() {
              if(' . $if . ') {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
              }
              else {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
              }
              set_total_value(' . $form_id . ');
              if ( jQuery("#form' . $form_id . ' div[type=\'type_signature\']").length && typeof fm_signature_init != \'undefined\' ) {
                fm_signature_init();
              }
            });';
          }
          if ( $blur ) {
            $condition_js .= '
            jQuery("' . substr($blur, 0, -2) . '").blur(function() {
              if(' . $if . ') {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
              }
              else {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
              }
              set_total_value(' . $form_id . ');
              if ( jQuery("#form' . $form_id . ' div[type=\'type_signature\']").length && typeof fm_signature_init != \'undefined\' ) {
                fm_signature_init();
              }
            });';
          }
          if ( $click ) {
            $condition_js .= '
            jQuery("' . substr($click, 0, -2) . '").click(function() {
              if(' . $if . ') {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
              }
              else {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
              }
              set_total_value(' . $form_id . ');
              if ( jQuery("#form' . $form_id . ' div[type=\'type_signature\']").length && typeof fm_signature_init != \'undefined\' ) {
                fm_signature_init();
              }
            });';
          }
          if ( $ui_slider ) {
            $condition_js .= '
            jQuery("' . substr($ui_slider, 0, -2) . '").on( "slidestop", function() {
              if(' . $if . ') {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
              }
              else {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
              }
              set_total_value(' . $form_id . ');
              if ( jQuery("#form' . $form_id . ' div[type=\'type_signature\']").length && typeof fm_signature_init != \'undefined\' ) {
                fm_signature_init();
              }
            });';
          }
          if ( $documentOn ) {
            $condition_js .= '
            jQuery(document).on('. $documentOn .', function(e) {
              if(' . $if . ') {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display . ';
              }
              else {
                jQuery("#form' . $form_id . '' . $selector . '").' . $display_none . ';
              }
              set_total_value(' . $form_id . ');
            });';
          }
        }
      }
    }
    $form = $row->form_front;
    $req_fields = array();
    $check_regExp_all = array();
    $check_paypal_price_min_max = array();
    $file_upload_check = array();
    $inputIds = array();
    $spinner_check = array();
    foreach ( $id1s as $id1s_key => $id1 ) {
      $label = $labels[$id1s_key];
      $type = $types[$id1s_key];
      $params = $paramss[$id1s_key];
      $id_type = $id1 . '|' . $type;
      if ( strpos($form, '%' . $id1 . ' - ' . $label . '%') || strpos($form, '%' . $id1 . ' -' . $label . '%') ) {
        $required = FALSE;
        $param = array();
        $param['attributes'] = '';
        $is_type[$type] = TRUE;
        switch ( $type ) {
          case 'type_send_copy': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_first_val', 'w_required' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_first_val',
                'w_required',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $onsubmit_js .= 'if(!jQuery("#wdform_' . $id1 . '_element' . $form_id . '").is(":checked")) {
              jQuery("<input type=\"hidden\" name=\"wdform_send_copy_' . $form_id . '\" value=\"1\" />").appendTo("#form' . $form_id . '");}';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_text': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
            );
            $temp = $params;
            if ( strpos($temp, 'w_regExp_status') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_regExp_status',
                'w_regExp_value',
                'w_regExp_common',
                'w_regExp_arg',
                'w_regExp_alert',
                'w_unique',
              );
            }
            if ( strpos($temp, 'w_readonly') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_regExp_status',
                'w_regExp_value',
                'w_regExp_common',
                'w_regExp_arg',
                'w_regExp_alert',
                'w_unique',
                'w_readonly',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_regExp_status',
                'w_regExp_value',
                'w_regExp_common',
                'w_regExp_arg',
                'w_regExp_alert',
                'w_unique',
                'w_readonly',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_regExp_status'] = (isset($param['w_regExp_status']) ? $param['w_regExp_status'] : "no");
            $check_regExp = '';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            if ( $param['w_regExp_status'] == 'yes' ) {
              $check_regExp_all[$id1] = array(
                $param["w_regExp_value"],
                $param["w_regExp_arg"],
                $param["w_regExp_alert"],
              );
            }
            break;
          }
          case 'type_number': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_password': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_required',
                'w_unique',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_verification') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_required',
                'w_unique',
                'w_class',
                'w_verification',
                'w_verification_label',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_textarea': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size_w',
              'w_size_h',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size_w',
                'w_size_h',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
              );
            }
						if ( strpos($temp, 'w_characters_limit') > -1 ) {
							$params_names = array(
							 'w_field_label_size',
							 'w_field_label_pos',
							 'w_hide_label',
							 'w_size_w',
							 'w_size_h',
							 'w_first_val',
							 'w_characters_limit',
							 'w_title',
							 'w_required',
							 'w_unique',
							 'w_class',
							);
						}
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_phone': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_mini_labels',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_required',
                'w_unique',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_phone_new': {
            $front_urls = WDFMInstance(self::PLUGIN)->get_front_urls();
            $plugin_url = $front_urls['plugin_url'];
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_hide_label',
              'w_size',
              'w_first_val',
              'w_top_country',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onload_js .= '
                var telinput'. $id1 .' = jQuery("#wdform_' . $id1 . '_element' . $form_id . '");
								var iti'. $id1 .' = window.intlTelInput(telinput'. $id1 .'[0], {
									nationalMode: false,
									preferredCountries: [ "' . $param["w_top_country"] . '" ],
									utilsScript: "'. $plugin_url . '/js/intlTelInput-utils.js"
								});
							';
            break;
          }
          case 'type_name': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_first_val',
              'w_title',
              'w_mini_labels',
              'w_size',
              'w_name_format',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_name_fields') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_size',
                'w_name_format',
                'w_required',
                'w_unique',
                'w_class',
                'w_name_fields',
              );
            }
            if ( strpos($temp, 'w_autofill') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_size',
                'w_name_format',
                'w_required',
                'w_unique',
                'w_class',
                'w_name_fields',
                'w_autofill',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_size',
                'w_name_format',
                'w_required',
                'w_unique',
                'w_class',
                'w_name_fields',
                'w_autofill',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_address': {
            $w_states = self::get_states();
            $w_provinces = self::get_provinces_canada();
            $w_state_options = '';
            foreach ( $w_states as $w_state ) {
              $w_state_options .= '<option value="' . $w_state . '">' . $w_state . '</option>';
            }
            $w_provinces_options = '';
            foreach ( $w_provinces as $w_province ) {
              $w_provinces_options .= '<option value="' . $w_province . '">' . $w_province . '</option>';
            }
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_mini_labels',
              'w_disabled_fields',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_mini_labels',
                'w_disabled_fields',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $post = self::get( 'wdform_' . ($id1 + 5) . '_country' . $form_id, NULL, 'esc_html');
            if ( isset($post) ) {
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_country' . $form_id . '").val("' . self::get( 'wdform_' . ($id1 + 5) . "_country" . $form_id, '', 'esc_html' ) . '");';
            }
            if ( isset($w_disabled_fields[6]) && $w_disabled_fields[6] == 'yes' ) {
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_country' . $form_id . '").change(function() {
    if( jQuery(this).val() == "United States" ) {
      jQuery("#wdform_' . $id1 . '_state' . $form_id . '").parent().append("<select type=\"text\" id=\"wdform_' . $id1 . '_state' . $form_id . '\" name=\"wdform_' . ($id1 + 3) . '_state' . $form_id . '\" style=\"width: 100%;\" ' . $param['attributes'] . '>' . addslashes($w_state_options) . '</select><label class=\"mini_label\" id=\"' . $id1 . '_mini_label_state\">' . $w_mini_labels[3] . '</label>");
      jQuery("#wdform_' . $id1 . '_state' . $form_id . '").parent().children("input:first, label:first").remove();
    }
    else if ( jQuery(this).val() == "Canada" ) {
      jQuery("#wdform_' . $id1 . '_state' . $form_id . '").parent().append("<select type=\"text\" id=\"wdform_' . $id1 . '_state' . $form_id . '\" name=\"wdform_' . ($id1 + 3) . '_state' . $form_id . '\" style=\"width: 100%;\" ' . $param['attributes'] . '>' . addslashes($w_provinces_options) . '</select><label class=\"mini_label\" id=\"' . $id1 . '_mini_label_state\">' . $w_mini_labels[3] . '</label>");
      jQuery("#wdform_' . $id1 . '_state' . $form_id . '").parent().children("input:first, label:first").remove();
    }
    else {
      if(jQuery("#wdform_' . $id1 . '_state' . $form_id . '").attr("tagName") == "SELECT") {
        jQuery("#wdform_' . $id1 . '_state' . $form_id . '").parent().append("<input type=\"text\" id=\"wdform_' . $id1 . '_state' . $form_id . '\" name=\"wdform_' . ($id1 + 3) . '_state' . $form_id . '\" value=\"' . self::get( 'wdform_' . ($id1 + 3) . '_state' . $form_id, "", 'esc_html' ) . '\" style=\"width: 100%;\" ' . $param['attributes'] . '><label class=\"mini_label\">' . $w_mini_labels[3] . '</label>");
        jQuery("#wdform_' . $id1 . '_state' . $form_id . '").parent().children("select:first, label:first").remove();
      }
    }
  });';
            }
            break;
          }
          case 'type_submitter_mail': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_autofill') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
                'w_autofill',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
                'w_autofill',
              );
            }
            if ( strpos($temp, 'w_verification') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
                'w_verification',
                'w_verification_label',
                'w_verification_placeholder',
                'w_autofill',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_checkbox': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_checked',
              'w_rowcol',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
		  			if ( strpos($temp, 'w_limit_choice') > -1 ) {
							$params_names = array(
								 'w_field_label_size',
								 'w_field_label_pos',
								 'w_field_option_pos',
								 'w_hide_label',
								 'w_flow',
								 'w_choices',
								 'w_choices_checked',
								 'w_rowcol',
								 'w_limit_choice',
								 'w_limit_choice_alert',
								 'w_required',
								 'w_randomize',
								 'w_allow_other',
								 'w_allow_other_num',
								 'w_value_disabled',
								 'w_choices_value',
								 'w_choices_params',
								 'w_class',
							);
						}
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }

            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }

            foreach ( $param['w_choices_params'] as $choices ) {
              if ( !empty($choices) ) {
                preg_match_all('/{\d+}/', $choices, $matches);
                if ( !empty($matches[0][0]) ) {
                  $inputIds[$id_type][] = str_replace( array('{','}'), array('',''), $matches[0][0] );
                }
              }
            }

            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $post_value = self::get( "counter" . $form_id, NULL, 'esc_html' );
            $is_other = FALSE;
            if ( isset($post_value) ) {
              if ( $param['w_allow_other'] == "yes" ) {
                $is_other = FALSE;
                $other_element = self::get( 'wdform_' . $id1 . "_other_input" . $form_id, NULL, 'esc_html' );
                if ( isset($other_element) ) {
                  $is_other = TRUE;
                }
              }
            }
            else {
              $is_other = ($param['w_allow_other'] == "yes" && $param['w_choices_checked'][$param['w_allow_other_num']] == 'true');
            }
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            if ( $is_other ) {
              $onload_js .= 'show_other_input("wdform_' . $id1 . '","' . $form_id . '"); jQuery("#wdform_' . $id1 . '_other_input' . $form_id . '").val("' . self::get( 'wdform_' . $id1 . "_other_input" . $form_id, '', 'esc_html' ) . '");';
            }
            if ( $param['w_randomize'] == 'yes' ) {
              $onload_js .= 'jQuery("#form' . $form_id . ' div[wdid=' . $id1 . '] .wdform-element-section> div").shuffle();';
            }
            $onsubmit_js .= '
				  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_allow_other' . $form_id . '\" value=\"' . $param['w_allow_other'] . '\" />").appendTo("#form' . $form_id . '");
				  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_allow_other_num' . $form_id . '\" value=\"' . $param['w_allow_other_num'] . '\" />").appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_radio': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_checked',
              'w_rowcol',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }

            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }

            foreach ( $param['w_choices_params'] as $choices ) {
              if ( !empty($choices) ) {
                preg_match_all('/{\d+}/', $choices, $matches);
                if ( !empty($matches[0][0]) ) {
                  $inputIds[$id_type][] = str_replace( array('{','}'), array('',''), $matches[0][0] );
                }
              }
            }

            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $post_value = self::get( "counter" . $form_id, NULL, 'esc_html' );
            $is_other = FALSE;
            if ( isset($post_value) ) {
              if ( $param['w_allow_other'] == "yes" ) {
                $is_other = FALSE;
                $other_element = self::get( 'wdform_' . $id1 . "_other_input" . $form_id, "", 'esc_html' );
                if ( isset($other_element) ) {
                  $is_other = TRUE;
                }
              }
            }
            else {
              $is_other = ($param['w_allow_other'] == "yes" && $param['w_choices_checked'][$param['w_allow_other_num']] == 'true');
            }
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            if ( $is_other ) {
              $onload_js .= '
  show_other_input("wdform_' . $id1 . '","' . $form_id . '"); jQuery("#wdform_' . $id1 . '_other_input' . $form_id . '").val("' . self::get( 'wdform_' . $id1 . "_other_input" . $form_id, '', 'esc_html' ) . '");';
            }
            if ( $param['w_randomize'] == 'yes' ) {
              $onload_js .= '
  jQuery("#form' . $form_id . ' div[wdid=' . $id1 . '] .wdform-element-section> div").shuffle();';
            }
            $onsubmit_js .= '
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_allow_other' . $form_id . '\" value=\"' . $param['w_allow_other'] . '\" />").appendTo("#form' . $form_id . '");
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_allow_other_num' . $form_id . '\" value=\"' . $param['w_allow_other_num'] . '\" />").appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_own_select': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_choices',
              'w_choices_checked',
              'w_choices_disabled',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_choices_value') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_choices',
                'w_choices_checked',
                'w_choices_disabled',
                'w_required',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_choices',
                'w_choices_checked',
                'w_choices_disabled',
                'w_required',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }

            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }

            foreach ( $param['w_choices_params'] as $choices ) {
              if ( !empty($choices) ) {
                preg_match_all('/{\d+}/', $choices, $matches);
                if ( !empty($matches[0][0]) ) {
                  $inputIds[$id_type][] = str_replace( array('{','}'), array('',''), $matches[0][0] );
                }
              }
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_country': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_countries',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_countries',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_time': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_time_type',
              'w_am_pm',
              'w_sec',
              'w_hh',
              'w_mm',
              'w_ss',
              'w_mini_labels',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_time_type',
                'w_am_pm',
                'w_sec',
                'w_hh',
                'w_mm',
                'w_ss',
                'w_mini_labels',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_date': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_date',
              'w_required',
              'w_class',
              'w_format',
              'w_but_val',
            );
            $temp = $params;
            if ( strpos($temp, 'w_disable_past_days') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_date',
                'w_required',
                'w_class',
                'w_format',
                'w_but_val',
                'w_disable_past_days',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_disable_past_days'] = isset($param['w_disable_past_days']) ? $param['w_disable_past_days'] : 'no';
            $disable_past_days = $param['w_disable_past_days'] == 'yes' ? 'true' : 'false';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            if ( $disable_past_days == 'true' ) {
              $check_js .= '
  if( Date.parse(jQuery("#wdform_' . $id1 . '_element' . $form_id . '").val() + " 23:59:59") < fm_currentDate.getTime() ) {
    alert("' . __('You cannot select former dates. Choose a date starting from the current one.', WDFMInstance(self::PLUGIN)->prefix) . '");
    return false;
  }';
            }
            $date_format= str_replace('%', '', $param['w_format']);

            $onsubmit_js .= '
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_date_format' . $form_id . '\" value=\"' . $date_format . '\" />").appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_date_new': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_date',
              'w_required',
              'w_show_image',
              'w_class',
              'w_format',
              'w_start_day',
              'w_default_date',
              'w_min_date',
              'w_max_date',
              'w_invalid_dates',
              'w_show_days',
              'w_hide_time',
              'w_but_val',
              'w_disable_past_days',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_date',
                'w_required',
                'w_show_image',
                'w_class',
                'w_format',
                'w_start_day',
                'w_default_date',
                'w_min_date',
                'w_max_date',
                'w_invalid_dates',
                'w_show_days',
                'w_hide_time',
                'w_but_val',
                'w_disable_past_days',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $default_date = self::get( 'wdform_' . $id1 . "_element" . $form_id, $param['w_default_date'], 'esc_html');
            $w_show_week_days = explode('***', $param['w_show_days']);
            $w_hide_sunday = $w_show_week_days[0] == 'yes' ? '' : ' && day != 0';
            $w_hide_monday = $w_show_week_days[1] == 'yes' ? '' : ' && day != 1';
            $w_hide_tuesday = $w_show_week_days[2] == 'yes' ? '' : ' && day != 2';
            $w_hide_wednesday = $w_show_week_days[3] == 'yes' ? '' : ' && day != 3';
            $w_hide_thursday = $w_show_week_days[4] == 'yes' ? '' : ' && day != 4';
            $w_hide_friday = $w_show_week_days[5] == 'yes' ? '' : ' && day != 5';
            $w_hide_saturday = $w_show_week_days[6] == 'yes' ? '' : '&& day != 6';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onload_js .= '
  jQuery("#button_calendar_' . $id1 . ', #fm-calendar-' . $id1 . '").click(function() {
    jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker("show");
  }); 
  jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker({
    dateFormat: "' . $param['w_format'] . '",
    minDate: "' . $param['w_min_date'] . '",
    maxDate: "' . $param['w_max_date'] . '",
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+50",
    showOtherMonths: true,
    selectOtherMonths: true,
    firstDay: "' . $param['w_start_day'] . '",
    beforeShow: function(input, inst) {
      jQuery("#ui-datepicker-div").addClass("fm_datepicker");
    },
    beforeShowDay: function(date) {
      var invalid_dates = "' . $param["w_invalid_dates"] . '";
      var invalid_dates_finish = [];
      var invalid_dates_start = invalid_dates.split(",");
      var invalid_date_range =[];
      for(var i = 0; i < invalid_dates_start.length; i++ ) {
        invalid_dates_start[i] = invalid_dates_start[i].trim();
        if(invalid_dates_start[i].length < 11 || invalid_dates_start[i].indexOf("-") == -1){
          invalid_dates_finish.push(invalid_dates_start[i]);
        }
        else{
          if(invalid_dates_start[i].indexOf("-") > 4) {
            invalid_date_range.push(invalid_dates_start[i].split("-"));
          }
          else {
            var invalid_date_array = invalid_dates_start[i].split("-");
            var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
            var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
            invalid_date_range.push([start_invalid_day, end_invalid_day]);
          }
        }
      }
      jQuery.each(invalid_date_range, function( index, value ) {
        for(var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
          invalid_dates_finish.push(jQuery.datepicker.formatDate("' . $param['w_format'] . '", d));
        }
      });
      var string_days = jQuery.datepicker.formatDate("' . $param['w_format'] . '", date);
      var day = date.getDay();
      return [ invalid_dates_finish.indexOf(string_days) == -1 ' . $w_hide_sunday . $w_hide_monday . $w_hide_tuesday . $w_hide_wednesday . $w_hide_thursday . $w_hide_friday . $w_hide_saturday . '];
    }
  });
  var default_date;
  var date_value = jQuery("#wdform_' . $id1 . '_element' . $form_id . '").val();
  (date_value != "") ? default_date = date_value : default_date = "' . $default_date . '";
  jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker("option", "dateFormat", "' . $param['w_format'] . '");
  if(default_date == "today") {
    jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker("setDate", new Date());
  }
  else if (default_date.indexOf("d") == -1 && default_date.indexOf("m") == -1 && default_date.indexOf("y") == -1 && default_date.indexOf("w") == -1) {
    jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker("setDate", default_date);
  }
  else {
    jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker("setDate", default_date);
  }';
            break;
          }
          case 'type_date_range': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_date',
              'w_required',
              'w_show_image',
              'w_class',
              'w_format',
              'w_start_day',
              'w_default_date_start',
              'w_default_date_end',
              'w_min_date',
              'w_max_date',
              'w_invalid_dates',
              'w_show_days',
              'w_hide_time',
              'w_but_val',
              'w_disable_past_days',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_date',
                'w_required',
                'w_show_image',
                'w_class',
                'w_format',
                'w_start_day',
                'w_default_date_start',
                'w_default_date_end',
                'w_min_date',
                'w_max_date',
                'w_invalid_dates',
                'w_show_days',
                'w_hide_time',
                'w_but_val',
                'w_disable_past_days',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $param['w_default_date_start'] == 'today' ) {
              $default_date_start = 'new Date()';
            }
            else {
              $default_date_start = $param['w_default_date_start'];
            }

            $w_show_week_days = explode('***', $param['w_show_days']);
            $w_hide_sunday = $w_show_week_days[0] == 'yes' ? '' : ' && day != 0';
            $w_hide_monday = $w_show_week_days[1] == 'yes' ? '' : ' && day != 1';
            $w_hide_tuesday = $w_show_week_days[2] == 'yes' ? '' : ' && day != 2';
            $w_hide_wednesday = $w_show_week_days[3] == 'yes' ? '' : ' && day != 3';
            $w_hide_thursday = $w_show_week_days[4] == 'yes' ? '' : ' && day != 4';
            $w_hide_friday = $w_show_week_days[5] == 'yes' ? '' : ' && day != 5';
            $w_hide_saturday = $w_show_week_days[6] == 'yes' ? '' : '&& day != 6';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onload_js .= '
						jQuery("#button_calendar_' . $id1 . '0").click(function() {
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("show");
						});
						jQuery("#button_calendar_' . $id1 . '1").click(function() {
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("show");
						});
						
						var default_date_start; 
						var date_start_value = jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").val();
						(date_start_value != "") ? default_date_start = date_start_value : default_date_start = "' . $param['w_default_date_start'] . '";
					
						jQuery("#wdform_' . $id1 . '_element' . $form_id . '").datepicker("option", "dateFormat", "' . $param['w_format'] . '");
						if ( default_date_start =="today" ) {
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("setDate", new Date());
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("option", "minDate", new Date());
						}
						else if ( default_date_start.indexOf("d") == -1 && default_date_start.indexOf("m") == -1 && default_date_start.indexOf("y") == -1 && default_date_start.indexOf("w") == -1 ) {
							if ( default_date_start !== "" ) {
								default_date_start = jQuery.datepicker.formatDate("' . $param['w_format'] . '", new Date(default_date_start));
								jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("setDate", default_date_start);
								jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("option", "minDate", default_date_start);
							}
							else {
								jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("setDate", default_date_start);
							}
						}
						else {
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("setDate", default_date_start);
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("option", "minDate", default_date_start);
						}
						
						var default_date_end;
						var date_end_value = jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").val();
						(date_end_value != "") ? default_date_end = date_end_value : default_date_end = "' . $param['w_default_date_end'] . '";
						jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("option", "dateFormat", "' . $param['w_format'] . '");
						jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("option", "dateFormat", "' . $param['w_format'] . '");
						
						if ( default_date_end =="today" ) {
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("setDate", new Date());
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("option", "maxDate", new Date());
						}
						else if ( default_date_end.indexOf("d") == -1 && default_date_end.indexOf("m") == -1 && default_date_end.indexOf("y") == -1 && default_date_end.indexOf("w") == -1 ) {
							if ( default_date_end !== "" ) {
								default_date_end = jQuery.datepicker.formatDate("' . $param['w_format'] . '", new Date(default_date_end));
								jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("setDate", default_date_end);
								jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("option", "maxDate", default_date_end);
							}
							else {
								jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("setDate", default_date_end);
							}
						}
						else {
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("setDate", default_date_end);
							jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("option", "maxDate", default_date_end);
						}
						
						jQuery("input[id^=\'wdform_' . $id1 . '_element' . $form_id . '\']").datepicker({
							dateFormat: "' . $param['w_format'] . '",
							minDate: "' . $param['w_min_date'] . '",
							maxDate: "' . $param['w_max_date'] . '",
							firstDay: "' . $param['w_start_day'] . '",
							changeMonth: true,
							changeYear: true,
							yearRange: "-100:+50",
							showOtherMonths: true,
							selectOtherMonths: true,
							beforeShow: function(input, inst) {
								jQuery("#ui-datepicker-div").addClass("fm_datepicker");
							},
							beforeShowDay: function(date){
								var invalid_dates = "' . $param["w_invalid_dates"] . '";
								var invalid_dates_finish = [];
								var invalid_dates_start = invalid_dates.split(",");
								var invalid_date_range =[];
								for(var i = 0; i < invalid_dates_start.length; i++ ){
									invalid_dates_start[i] = invalid_dates_start[i].trim();
									if(invalid_dates_start[i].length < 11 || invalid_dates_start[i].indexOf("-") == -1) {
										invalid_dates_finish.push(invalid_dates_start[i]);
									}
									else {
										if(invalid_dates_start[i].indexOf("-") > 4) {
											invalid_date_range.push(invalid_dates_start[i].split("-"));
										}
										else {
											var invalid_date_array = invalid_dates_start[i].split("-");
											var start_invalid_day = invalid_date_array[0] + "-" + invalid_date_array[1] + "-" + invalid_date_array[2];
											var end_invalid_day = invalid_date_array[3] + "-" + invalid_date_array[4] + "-" + invalid_date_array[5];
											invalid_date_range.push([start_invalid_day, end_invalid_day]);
										}
									}
								}
								jQuery.each(invalid_date_range, function( index, value ) {
									for(var d = new Date(value[0]); d <= new Date(value[1]); d.setDate(d.getDate() + 1)) {
										invalid_dates_finish.push(jQuery.datepicker.formatDate("' . $param['w_format'] . '", d));
									}
								});
								var string_days = jQuery.datepicker.formatDate("' . $param['w_format'] . '", date);
								var day = date.getDay();
								return [ invalid_dates_finish.indexOf(string_days) == -1 ' . $w_hide_sunday . $w_hide_monday . $w_hide_tuesday . $w_hide_wednesday . $w_hide_thursday . $w_hide_friday . $w_hide_saturday . '];
							}
						});
						jQuery("#wdform_' . $id1 . '_element' . $form_id . '0").datepicker("setDate", "' . $param['w_default_date_start'] . '");
						jQuery("#wdform_' . $id1 . '_element' . $form_id . '1").datepicker("setDate", "' . $param['w_default_date_end'] . '"); ';
            break;
          }
          case 'type_date_fields': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_day',
              'w_month',
              'w_year',
              'w_day_type',
              'w_month_type',
              'w_year_type',
              'w_day_label',
              'w_month_label',
              'w_year_label',
              'w_day_size',
              'w_month_size',
              'w_year_size',
              'w_required',
              'w_class',
              'w_from',
              'w_to',
              'w_divider',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_day',
                'w_month',
                'w_year',
                'w_day_type',
                'w_month_type',
                'w_year_type',
                'w_day_label',
                'w_month_label',
                'w_year_label',
                'w_day_size',
                'w_month_size',
                'w_year_size',
                'w_required',
                'w_class',
                'w_from',
                'w_to',
                'w_divider',
              );
            }
						if ( strpos($temp, 'w_min_day') > -1 && strpos($temp, 'w_min_month') > -1 && strpos($temp, 'w_min_year') > -1 && strpos($temp, 'w_min_dob_alert') > -1 ) {
							$params_names = array(
							 'w_field_label_size',
							 'w_field_label_pos',
							 'w_hide_label',
							 'w_day',
							 'w_month',
							 'w_year',
							 'w_day_type',
							 'w_month_type',
							 'w_year_type',
							 'w_day_label',
							 'w_month_label',
							 'w_year_label',
							 'w_day_size',
							 'w_month_size',
							 'w_year_size',
							 'w_required',
							 'w_class',
							 'w_from',
							 'w_to',
							 'w_min_day',
							 'w_min_month',
							 'w_min_year',
							 'w_min_dob_alert',
							 'w_divider',
							);
						}
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $param['w_day_type'] != "SELECT" ) {
              $w_day_type = '<input type="text" value="' . $param['w_day'] . '" id="wdform_' . $id1 . '_day' . $form_id . '" name="wdform_' . $id1 . '_day' . $form_id . '" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . '>';
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_day' . $form_id . '").blur(function() {if (jQuery(this).val() == "0") jQuery(this).val(""); else add_0(this)});';
            }
            if ( $param['w_month_type'] != "SELECT" ) {
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_month' . $form_id . '").blur(function() {if (jQuery(this).val() == "0") jQuery(this).val(""); else add_0(this)});';
            }
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_file_upload': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_destination',
              'w_extension',
              'w_max_size',
              'w_required',
              'w_multiple',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_destination',
                'w_extension',
                'w_max_size',
                'w_required',
                'w_multiple',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              if ( isset($temp[1]) ) {
                $temp = $temp[1];
              }
              else {
                $temp = '';
              }
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $file_upload_check[$id1] = array('extension' => $param['w_extension'], 'max_size' => $param['w_max_size'] );
            if ( WDFMInstance(self::PLUGIN)->is_demo ) {
              $onsubmit_js .= 'alert( "You can\'t upload file in demo.");';
            }
            break;
          }
          case 'type_captcha': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_digit', 'w_class' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_hide_label', 'w_digit', 'w_class' );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $onload_js .= '
  jQuery("#wd_captcha' . $form_id . '").click(function() {captcha_refresh("wd_captcha","' . $form_id . '")});';
            $onload_js .= '
  jQuery("#_element_refresh' . $form_id . '").click(function() {captcha_refresh("wd_captcha","' . $form_id . '")});';
            array_push($req_fields, $id1);
            $onload_js .= '
  captcha_refresh("wd_captcha", "' . $form_id . '");';
            break;
          }
          case 'type_arithmetic_captcha': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_count',
              'w_operations',
              'w_class',
              'w_input_size',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_count',
                'w_operations',
                'w_class',
                'w_input_size',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $onload_js .= '
  jQuery("#wd_arithmetic_captcha' . $form_id . '").click(function() { captcha_refresh("wd_arithmetic_captcha","' . $form_id . '") });';
            $onload_js .= '
  jQuery("#_element_refresh' . $form_id . '").click(function() {captcha_refresh("wd_arithmetic_captcha","' . $form_id . '")});';
            array_push($req_fields, $id1);
            $onload_js .= '
  captcha_refresh("wd_arithmetic_captcha", "' . $form_id . '");';
            break;
          }
          case 'type_mark_map': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_center_x',
              'w_center_y',
              'w_long',
              'w_lat',
              'w_zoom',
              'w_width',
              'w_height',
              'w_info',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_center_x',
                'w_center_y',
                'w_long',
                'w_lat',
                'w_zoom',
                'w_width',
                'w_height',
                'w_info',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $onload_js .= '
  if_gmap_init("wdform_' . $id1 . '", ' . $form_id . ');';
            $onload_js .= '
  add_marker_on_map("wdform_' . $id1 . '", 0, "' . $param['w_long'] . '", "' . $param['w_lat'] . '", "' . str_replace(array(
                                                                                                                                                  "\r\n",
                                                                                                                                                  "\n",
                                                                                                                                                  "\r",
                                                                                                                                                ), '<br />', $param['w_info']) . '", ' . $form_id . ',true);';
            break;
          }
          case 'type_map': {
            $params_names = array(
              'w_center_x',
              'w_center_y',
              'w_long',
              'w_lat',
              'w_zoom',
              'w_width',
              'w_height',
              'w_info',
              'w_class',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $param['w_long'] = explode('***', $param['w_long']);
            $param['w_lat'] = explode('***', $param['w_lat']);
            $param['w_info'] = explode('***', $param['w_info']);
            foreach ( $param['w_long'] as $key => $w_long ) {
              $onload_js .= '
  add_marker_on_map("wdform_' . $id1 . '",' . $key . ', "' . $w_long . '", "' . $param['w_lat'][$key] . '", "' . str_replace(array(
                                                                                                                                                           "\r\n",
                                                                                                                                                           "\n",
                                                                                                                                                           "\r",
                                                                                                                                                         ), '<br />', $param['w_info'][$key]) . '", ' . $form_id . ',false);';
            }
            break;
          }
          case 'type_paypal_price': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_first_val',
              'w_title',
              'w_mini_labels',
              'w_size',
              'w_required',
              'w_hide_cents',
              'w_class',
              'w_range_min',
              'w_range_max',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $onload_js .= '
  jQuery("#wdform_' . $id1 . '_element_cents' . $form_id . '").blur(function() {add_0(this)});';
            $onload_js .= '
  jQuery("#wdform_' . $id1 . '_element_cents' . $form_id . '").keypress(function(event) {return check_isnum_interval(event,this,0,99)});';
            if ( $required ) {
              $check_js .= '
  if(x.find(jQuery("div[wdid=' . $id1 . ']")).length != 0 && x.find(jQuery("div[wdid=' . $id1 . ']")).css("display") != "none") {
    if(jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").val() == "' . $w_title[0] . '" || jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").val() == "") {
      alert("' . addslashes(sprintf(__('%s field is required.', WDFMInstance(self::PLUGIN)->prefix), $label)) . '");
      old_bg=x.find(jQuery("div[wdid=' . $id1 . ']")).css("background-color");
      x.find(jQuery("div[wdid=' . $id1 . ']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });
      jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").focus();
      return false;
    }
  }';
            }
            $check_js .= '
  if(x.find(jQuery("div[wdid=' . $id1 . ']")).length != 0 && x.find(jQuery("div[wdid=' . $id1 . ']")).css("display") != "none") {
    dollars=0;
    cents=0;
    if(jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").val() != "' . $w_title[0] . '" || jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").val()) {
      dollars =jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").val();
    }
    if(jQuery("#wdform_' . $id1 . '_element_cents' . $form_id . '").val() != "' . $w_title[1] . '" || jQuery("#wdform_' . $id1 . '_element_cents' . $form_id . '").val()) {
      cents =jQuery("#wdform_' . $id1 . '_element_cents' . $form_id . '").val();
    }
    var price=dollars+"."+cents;
    if(isNaN(price)) {
      alert("Invalid value of number field");
      old_bg=x.find(jQuery("div[wdid=' . $id1 . ']")).css("background-color");
      x.find(jQuery("div[wdid=' . $id1 . ']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });
      jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").focus();
      return false;
    }
    var range_min=' . ($param['w_range_min'] ? $param['w_range_min'] : 0) . ';
    var range_max=' . ($param['w_range_max'] ? $param['w_range_max'] : -1) . ';
    if(' . ($required ? 'true' : 'false') . ' || jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").val() != "' . $w_title[0] . '" || jQuery("#wdform_' . $id1 . '_element_cents' . $form_id . '").val() != "' . $w_title[1] . '") {
      if((range_max!=-1 && parseFloat(price)>range_max) || parseFloat(price)<range_min) {
        alert("' . addslashes(sprintf(__('The %s value must be between %f - %f', WDFMInstance(self::PLUGIN)->prefix), $label, ($param['w_range_min'] ? $param['w_range_min'] : 0), ($param['w_range_max'] ? $param['w_range_max'] : "any"))).'");
        old_bg=x.find(jQuery("div[wdid=' . $id1 . ']")).css("background-color");
        x.find(jQuery("div[wdid=' . $id1 . ']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });
        jQuery("#wdform_' . $id1 . '_element_dollars' . $form_id . '").focus();
        return false;
      }
    }
  }';
            break;
          }
          case 'type_paypal_price_new': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_first_val',
              'w_title',
              'w_size',
              'w_required',
              'w_class',
              'w_range_min',
              'w_range_max',
              'w_readonly',
              'w_currency',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_first_val',
                'w_title',
                'w_size',
                'w_required',
                'w_class',
                'w_range_min',
                'w_range_max',
                'w_readonly',
                'w_currency',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $check_paypal_price_min_max[$id1] = array(
              $label,
              $param['w_title'],
              $required,
              $param['w_range_min'],
              $param['w_range_max'],
            );
            break;
          }
          case 'type_paypal_select': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_choices',
              'w_choices_price',
              'w_choices_checked',
              'w_choices_disabled',
              'w_required',
              'w_quantity',
              'w_quantity_value',
              'w_class',
              'w_property',
              'w_property_values',
            );
            $temp = $params;
            if ( strpos($temp, 'w_choices_params') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_choices_disabled',
                'w_required',
                'w_quantity',
                'w_quantity_value',
                'w_choices_params',
                'w_class',
                'w_property',
                'w_property_values',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_choices_disabled',
                'w_required',
                'w_quantity',
                'w_quantity_value',
                'w_choices_params',
                'w_class',
                'w_property',
                'w_property_values',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }

            if ( isset($param['w_choices_params']) ) {
              $w_choices_params = explode('***', $param['w_choices_params']);
              foreach ( $w_choices_params as $choices ) {
                if ( !empty($choices) ) {
                  preg_match_all('/{\d+}/', $choices, $matches);
                  if ( !empty($matches[0][0]) ) {
                    $inputIds[$id_type][] = str_replace( array('{','}'), array('',''), $matches[0][0] );
                  }
                }
              }
            }

            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_property'] = explode('***', $param['w_property']);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_label' . $form_id . '\" />").val(jQuery("#wdform_' . $id1 . '_element' . $form_id . ' option:selected").text()).appendTo("#form' . $form_id . '");';
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_quantity_label' . $form_id . '\" />").val("' . (__("Quantity", WDFMInstance(self::PLUGIN)->prefix)) . '").appendTo("#form' . $form_id . '");';
            foreach ( $param['w_property'] as $key => $property ) {
              $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_property_label' . $form_id . $key . '\" />").val("' . $property . '").appendTo("#form' . $form_id . '");';
            }
            break;
          }
          case 'type_paypal_checkbox': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_price',
              'w_choices_checked',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
              'w_property',
              'w_property_values',
              'w_quantity',
              'w_quantity_value',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_choices_params',
                'w_class',
                'w_property',
                'w_property_values',
                'w_quantity',
                'w_quantity_value',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_choices_params',
                'w_class',
                'w_property',
                'w_property_values',
                'w_quantity',
                'w_quantity_value',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }

            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_property'] = explode('***', $param['w_property']);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_label' . $form_id . '\" />").val((jQuery("#wdform_' . $id1 . '_element' . $form_id . ' input:checked").length != 0) ? jQuery("#wdform_' . $id1 . '_element' . $form_id . ' input:checked").attr("id").replace("element", "elementlabel_") : "").appendTo("#form' . $form_id . '");';
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_quantity_label' . $form_id . '\" />").val("' . (__("Quantity", WDFMInstance(self::PLUGIN)->prefix)) . '").appendTo("#form' . $form_id . '");';
            foreach ( $param['w_property'] as $key => $property ) {
              $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_property_label' . $form_id . $key . '\" />").val("' . $property . '").appendTo("#form' . $form_id . '");';
            }
            break;
          }
          case 'type_paypal_radio': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_price',
              'w_choices_checked',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
              'w_property',
              'w_property_values',
              'w_quantity',
              'w_quantity_value',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_choices_params',
                'w_class',
                'w_property',
                'w_property_values',
                'w_quantity',
                'w_quantity_value',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_choices_params',
                'w_class',
                'w_property',
                'w_property_values',
                'w_quantity',
                'w_quantity_value',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_property'] = explode('***', $param['w_property']);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_label' . $form_id . '\" />").val(jQuery("label[for=\'"+jQuery("input[name^=\'wdform_' . $id1 . '_element' . $form_id . '\']:checked").attr("id")+"\']").eq(0).text()).appendTo("#form' . $form_id . '");';
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_quantity_label' . $form_id . '\" />").val("' . (__("Quantity", WDFMInstance(self::PLUGIN)->prefix)) . '").appendTo("#form' . $form_id . '");';
            foreach ( $param['w_property'] as $key => $property ) {
              $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_property_label' . $form_id . $key . '\" />").val("' . $property . '").appendTo("#form' . $form_id . '");';
            }
            break;
          }
          case 'type_paypal_shipping': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_price',
              'w_choices_checked',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_price',
                'w_choices_checked',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_choices_params',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onsubmit_js .= '
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_element_label' . $form_id . '\" />").val(jQuery("label[for=\'"+jQuery("input[name^=\'wdform_' . $id1 . '_element' . $form_id . '\']:checked").attr("id")+"\']").eq(0).text()).appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_star_rating': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_label_col',
              'w_star_amount',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_label_col',
                'w_star_amount',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            for ( $i = 0; $i < $param['w_star_amount']; $i++ ) {
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_star_' . $i . '_' . $form_id . '").mouseover(function() {change_src(' . $i . ',"wdform_' . $id1 . '", ' . $form_id . ', "' . $param['w_field_label_col'] . '");});';
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_star_' . $i . '_' . $form_id . '").mouseout(function() {reset_src(' . $i . ',"wdform_' . $id1 . '", ' . $form_id . ');});';
              $onload_js .= '
  jQuery("#wdform_' . $id1 . '_star_' . $i . '_' . $form_id . '").click(function() {select_star_rating(' . $i . ',"wdform_' . $id1 . '", ' . $form_id . ',"' . $param['w_field_label_col'] . '", "' . $param['w_star_amount'] . '");});';
            }
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $post = self::get( 'wdform_' . $id1 . '_selected_star_amount' . $form_id, NULL, 'esc_html' );
            if ( isset($post) ) {
              $onload_js .= '
  select_star_rating(' . ($post - 1) . ',"wdform_' . $id1 . '", ' . $form_id . ',"' . $param['w_field_label_col'] . '", "' . $param['w_star_amount'] . '");';
            }
            $onsubmit_js .= '
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_star_amount' . $form_id . '\" value=\"' . $param['w_star_amount'] . '\" />").appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_scale_rating': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_mini_labels',
              'w_scale_amount',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_mini_labels',
                'w_scale_amount',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $onsubmit_js .= '
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_scale_amount' . $form_id . '\" value=\"' . $param['w_scale_amount'] . '\" />").appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_spinner': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_width',
              'w_field_min_value',
              'w_field_max_value',
              'w_field_step',
              'w_field_value',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_width',
                'w_field_min_value',
                'w_field_max_value',
                'w_field_step',
                'w_field_value',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $onload_js .= '
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '")[0].spin = null;
  spinner = jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '").spinner();
  if ("' . $param['w_field_value'] . '" == "null" && jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '").val() == "") { spinner.spinner("value", ""); }
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '").spinner({ min: "' . $param['w_field_min_value'] . '"});
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '").spinner({ max: "' . $param['w_field_max_value'] . '"});
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '").spinner({ step: "' . $param['w_field_step'] . '"});';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $spinner_check[$id1] = array( $param['w_field_min_value'], $param['w_field_max_value'] );
            break;
          }
          case 'type_slider': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_width',
              'w_field_min_value',
              'w_field_max_value',
              'w_field_value',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_width',
                'w_field_min_value',
                'w_field_max_value',
                'w_field_value',
                'w_required',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_field_step') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_width',
                'w_field_min_value',
                'w_field_max_value',
                'w_field_step',
                'w_field_value',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $onload_js .= '
  jQuery("#wdform_' . $id1 . '_element' . $form_id . '")[0].slide = null;
  jQuery("#wdform_' . $id1 . '_element' . $form_id . '").slider({
    step: parseFloat(' . $param['w_field_step'] . '),
    range: "min",
    value: eval(' . $param['w_field_value'] . '),
    min: eval(' . $param['w_field_min_value'] . '),
    max: eval(' . $param['w_field_max_value'] . '),
    slide: function( event, ui ) {
      jQuery("#wdform_' . $id1 . '_element_value' . $form_id . '").html("" + ui.value);
      jQuery("#wdform_' . $id1 . '_slider_value' . $form_id . '").val("" + ui.value);
    }
  });';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_range': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_range_width',
              'w_field_range_step',
              'w_field_value1',
              'w_field_value2',
              'w_mini_labels',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_range_width',
                'w_field_range_step',
                'w_field_value1',
                'w_field_value2',
                'w_mini_labels',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $onload_js .= '
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '0")[0].spin = null;
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '1")[0].spin = null;
  spinner0 = jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '0").spinner();
  if ("' . $param['w_field_value1'] . '" == "null" && jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '0").val() == "") { spinner0.spinner("value", ""); }
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '0").spinner({ step: ' . $param['w_field_range_step'] . '});
  spinner1 = jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '1").spinner();
  if ("' . $param['w_field_value2'] . '" == "null" && jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '1").val() == "") { spinner1.spinner("value", ""); }
  jQuery("#form' . $form_id . ' #wdform_' . $id1 . '_element' . $form_id . '1").spinner({ step: ' . $param['w_field_range_step'] . '});';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_grading': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_items',
              'w_total',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_items',
                'w_total',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $w_items = explode('***', $param['w_items']);
            $w_items_labels = implode(':', $w_items);
            $onload_js .= '
  jQuery("#wdform_' . $id1 . '_element' . $form_id . ' input").change(function() {sum_grading_values("wdform_' . $id1 . '",' . $form_id . ');});';
            $onload_js .= '
  jQuery("#wdform_' . $id1 . '_element' . $form_id . ' input").keyup(function() {sum_grading_values("wdform_' . $id1 . '",' . $form_id . ');});';
            $onload_js .= '
  jQuery("#wdform_' . $id1 . '_element' . $form_id . ' input").keyup(function() {sum_grading_values("wdform_' . $id1 . '",' . $form_id . ');});';
            $onload_js .= '
  sum_grading_values("wdform_' . $id1 . '",' . $form_id . ');';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            if ($param['w_total'] != '' && $param['w_total'] != '0') {
              $check_js .= '
  var isAllowdedSubmit = true;
  if(x.find(jQuery("div[wdid=' . $id1 . ']")).length != 0 && x.find(jQuery("div[wdid=' . $id1 . ']")).css("display") != "none") {
    if(parseInt(jQuery("#wdform_' . $id1 . '_sum_element' . $form_id . '").html()) > ' . $param['w_total'] . ') {
      alert("' . addslashes(sprintf(__("Your score should be less than %f", WDFMInstance(self::PLUGIN)->prefix), $param['w_total'])). '");
      return false;
    }
  }';
            }
            $onsubmit_js .= '
  jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_hidden_item' . $form_id . '\" value=\"' . $w_items_labels . ':' . $param['w_total'] . '\" />").appendTo("#form' . $form_id . '");';
            break;
          }
          case 'type_matrix': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_input_type',
              'w_rows',
              'w_columns',
              'w_required',
              'w_class',
              'w_textbox_size',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_input_type',
                'w_rows',
                'w_columns',
                'w_required',
                'w_class',
                'w_textbox_size',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $onsubmit_js .= 'jQuery("<input type=\"hidden\" name=\"wdform_' . $id1 . '_input_type' . $form_id . '\" value=\"' . $param['w_field_input_type'] . '\" /><input type=\"hidden\" name=\"wdform_' . $id1 . '_hidden_row' . $form_id . '\" value=\"' . esc_html(addslashes($param['w_rows'])) . '\" /><input type=\"hidden\" name=\"wdform_' . $id1 . '_hidden_column' . $form_id . '\" value=\"' . esc_html(addslashes($param['w_columns'])) . '\" />").appendTo("#form' . $form_id . '");';
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            break;
          }
          case 'type_paypal_total': {
            $onload_js .= 'set_total_value(' . $form_id . ');';
            break;
          }
          case 'type_signature': {
            $params_names = array(
                'w_field_label_pos',
                'w_hide_label',
                'w_required',
                'w_field_label_size',
                'w_canvas_width',
                'w_canvas_height',
                'w_class',
                'w_destination'
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }

            $required = ($param['w_required'] == 'yes' ? TRUE : FALSE);
            if ( $required ) {
              array_push($req_fields, $id1);
            }
            $signature_tmp_resize_js .='
              var $canvas_' . $id1 . ';
              var onResize_' . $id1 . ' = function(event) {
                $canvas_' . $id1 . ' = jQuery("#wdform_' . $id1 . '_element' . $form_id . '")[0];
                var signature_resize_check_interval_' . $id1 . '_' . $form_id . ' = setInterval(function() {
                  if ( $canvas_' . $id1 . ' && $canvas_' . $id1 . '.offsetWidth ) {
                    var signs = jQuery("#signature-signs-wdform_' . $id1 . '_element' . $form_id . '").val();
                    if(signs !== "") {
                     signs = JSON.parse(signs);
                    } else {
                      signs = {};
                    }
                    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                    $canvas_' . $id1 . '.width = $canvas_' . $id1 . '.offsetWidth * ratio;
                    $canvas_' . $id1 . '.height = $canvas_' . $id1 . '.offsetHeight * ratio;
                    $canvas_' . $id1 . '.getContext("2d").scale(ratio, ratio);
                    jQuery(".wdform_' . $id1 . '_signature-canvas-' . $form_id . '").signaturePad({displayOnly:true}).regenerate(signs);
                    clearInterval(signature_resize_check_interval_' . $id1 . '_' . $form_id . ');
                  }
                },300)
              };';
              $signature_tmp_ready_js .='
                window.addEventListener("orientationchange", onResize_' . $id1 . ', false);
                window.addEventListener("resize", onResize_' . $id1 . ', false);
                setTimeout(function(){  onResize_' . $id1 . '(); }, 300);
                jQuery(".wdform_' . $id1 . '_signature-canvas-' . $form_id . '").signaturePad({
                  drawOnly: true,
                  defaultAction: "drawIt",
                  validateFields: false,
                  lineWidth: 0,
                  output: "",
                  sigNav: null,
                  name: null,
                  typed: null,
                  lineColour: "#000",
                  penColour: "#000",
                  clear: "#signature-clear-wdform_' . $id1 . '_element' . $form_id . '",
                  typeIt: null,
                  drawIt: null,
                  typeItDesc: null,
                  drawItDesc: null,
                  onDrawEnd: function(){
									  jQuery("#signature-file-wdform_' . $id1 . '_element' . $form_id . '").val(this.getSignatureImage());
									  jQuery("#signature-signs-wdform_' . $id1 . '_element' . $form_id . '").val(JSON.stringify(this.getSignature()));
								  }
                });
              ';
            break;
          }
        }
      }
    }
    $signature_js = (!empty($signature_tmp_resize_js) && !empty($signature_tmp_ready_js) ) ? 'function fm_signature_init() { ' . $signature_tmp_resize_js . ' jQuery(document).ready(function() {' . $signature_tmp_ready_js . '}) }' : '';
    $onsubmit_js .= '
    var disabled_fields = "";
    jQuery("#form' . $form_id . ' div[wdid]").each(function() {
      if(jQuery(this).css("display") == "none") {
      
          if( jQuery(this).children().first().attr("type") === "type_address" ) {
              var ids = get_adress_fields_ids( this );
              if( ids.length > 0 ) {
                disabled_fields += ids.join(",");
                disabled_fields += ",";
              }
          } else {
              disabled_fields += jQuery(this).attr("wdid");
              disabled_fields += ",";
          }
      } else if( jQuery(this).children().first().attr("type") === "type_address" ) {
          var ids = get_adress_fields_ids( this );
          if( ids.length > 0 ) {
            disabled_fields += ids.join(",");
            disabled_fields += ",";
          }
      }
    })
    if(disabled_fields) {
      jQuery("<input type=\"hidden\" name=\"disabled_fields' . $form_id . '\" value =\""+disabled_fields+"\" />").appendTo("#form' . $form_id . '");
    };';
    ob_start();
    ?>
    var fm_currentDate = new Date();
    var FormCurrency_<?php echo $form_id; ?> = '<?php echo $form_currency ?>';
    var FormPaypalTax_<?php echo $form_id; ?> = '<?php echo $form_paypal_tax ?>';
    var check_submit<?php echo $form_id; ?> = 0;
    var check_before_submit<?php echo $form_id; ?> = {};
    var required_fields<?php echo $form_id; ?> = <?php echo json_encode($req_fields) ?>;
    var labels_and_ids<?php echo $form_id; ?> = <?php echo json_encode($labels_and_ids) ?>;
    var check_regExp_all<?php echo $form_id; ?> = <?php echo json_encode($check_regExp_all) ?>;
    var check_paypal_price_min_max<?php echo $form_id; ?> = <?php echo json_encode($check_paypal_price_min_max) ?>;
    var file_upload_check<?php echo $form_id; ?> = <?php echo json_encode($file_upload_check) ?>;
    var spinner_check<?php echo $form_id; ?> = <?php echo json_encode($spinner_check) ?>;
    var scrollbox_trigger_point<?php echo $form_id; ?> = '<?php echo $row_display->scrollbox_trigger_point; ?>';
    var header_image_animation<?php echo $form_id; ?> = '<?php echo $row->header_image_animation; ?>';
    var scrollbox_loading_delay<?php echo $form_id; ?> = '<?php echo $row_display->scrollbox_loading_delay; ?>';
    var scrollbox_auto_hide<?php echo $form_id; ?> = '<?php echo $row_display->scrollbox_auto_hide; ?>';
    var inputIds<?php echo $form_id; ?> = '<?php echo json_encode($inputIds); ?>';
    <?php
    reset($inputIds);
    $first_field_id = 0;
    if ( !empty($inputIds) ) {
      $first_field_id_arr = explode('|',  key($inputIds));
      if ( !empty($first_field_id_arr[0]) ) {
        $first_field_id = (int) $first_field_id_arr[0];
      }
    }
    ?>
    var update_first_field_id<?php echo $form_id; ?> = <?php echo $first_field_id; ?>;
    var form_view_count<?php echo $form_id ?> = 0;
    <?php echo preg_replace($pattern, ' ', $row->javascript); ?>

    function get_adress_fields_ids( that ) {
      var id = jQuery(that).attr("wdid");
      var disabled = [];
      /* This is the case when the address field is completely closed by condition. */
      if ( jQuery(that).css('display') == 'none' ) {
        for (var i = 0; i <= 5; i++) {
          var name = jQuery(that).find(".wdform_" + id + "_address_" + i).attr("name");
          if (typeof name !== "undefined") {
            var temp = name.split("_");
            disabled.push(temp[1]);
          }
        }
      }
     /* This is the case when the fields in the address are closed with a condition. */
      else {
        for (var i = 0; i <= 5; i++) {
          var field = jQuery(that).find(".wdform_" + id + "_address_" + i);
          if( field.parent().css('display') == 'none' ) {
            var name = field.attr("name");
            if (typeof name !== "undefined") {
              var temp = name.split("_");
              disabled.push(temp[1]);
            }
          }
        }
      }
      return disabled;
    }


    function onload_js<?php echo $form_id ?>() {<?php
      echo $onload_js; ?>
    }

    function condition_js<?php echo $form_id ?>() {<?php
      echo $condition_js; ?>
    }

    function check_js<?php echo $form_id ?>(id, form_id) {
      if (id != 0) {
        x = jQuery("#" + form_id + "form_view"+id);
      }
      else {
        x = jQuery("#form"+form_id);
      }
      <?php echo $check_js; ?>
    }

    function onsubmit_js<?php echo $form_id ?>() {
      <?php echo $onsubmit_js; ?>
    }

    function unset_fields<?php echo $form_id ?>( values, id, i ) {
      rid = 0;
      if ( i > 0 ) {
        jQuery.each( values, function( k, v ) {
          if ( id == k.split('|')[2] ) {
            rid = k.split('|')[0];
            values[k] = '';
          }
        });
        return unset_fields<?php echo $form_id ?>(values, rid, i - 1);
      }
      else {
        return values;
      }
    }

    function ajax_similarity<?php echo $form_id ?>( obj, changing_field_id ) {
      jQuery.ajax({
        type: "POST",
        url: fm_objectL10n.form_maker_admin_ajax,
        dataType: "json",
        data: {
          nonce: fm_ajax.ajaxnonce,
          action: 'fm_reload_input',
          page: 'form_maker',
          form_id: <?php echo $form_id ?>,
          inputs: obj.inputs
        },
        beforeSend: function() {
          if ( !jQuery.isEmptyObject(obj.inputs) ) {
            jQuery.each( obj.inputs, function( key, val ) {
              wdid = key.split('|')[0];
              if ( val != '' && parseInt(wdid) == parseInt(changing_field_id) ) {
                jQuery("#form<?php echo $form_id ?> div[wdid='"+ wdid +"']").append( '<div class="fm-loading"></div>' );
              }
            });
          }
        },
        success: function (res) {
          if ( !jQuery.isEmptyObject(obj.inputs) ) {
            jQuery.each( obj.inputs, function( key, val ) {
              wdid = key.split('|')[0];
              jQuery("#form<?php echo $form_id ?> div[wdid='"+ wdid +"'] .fm-loading").remove();
              if ( !jQuery.isEmptyObject(res[wdid]) && ( !val || parseInt(wdid) == parseInt(changing_field_id) ) ) {
                jQuery("#form<?php echo $form_id ?> div[wdid='"+ wdid +"']").html( res[wdid].html );
              }
            });
          }
        },
        complete: function() {
        }
      });
    }

    function fm_script_ready<?php echo $form_id ?>() {
      if (jQuery('#form<?php echo $form_id ?> .wdform_section').length > 0) {
        fm_document_ready( <?php echo $form_id ?> );
      }
      else {
        jQuery("#form<?php echo $form_id ?>").closest(".fm-form-container").removeAttr("style")
      }
      if (jQuery('#form<?php echo $form_id ?> .wdform_section').length > 0) {
        formOnload(<?php echo $form_id ?>);
      }
      var ajaxObj<?php echo $form_id ?> = {};
      var value_ids<?php echo $form_id ?> = {};
      jQuery.each( jQuery.parseJSON( inputIds<?php echo $form_id ?> ), function( key, values ) {
        jQuery.each( values, function( index, input_id ) {
          tagName =  jQuery('#form<?php echo $form_id ?> [id^="wdform_'+ input_id +'_elemen"]').attr("tagName");
          type =  jQuery('#form<?php echo $form_id ?> [id^="wdform_'+ input_id +'_elemen"]').attr("type");
          if ( tagName == 'INPUT' ) {
            input_value = jQuery('#form<?php echo $form_id ?> [id^="wdform_'+ input_id +'_elemen"]').val();
            if ( jQuery('#form<?php echo $form_id ?> [id^="wdform_'+ input_id +'_elemen"]').is(':checked') ) {
              if ( input_value ) {
                value_ids<?php echo $form_id ?>[key + '|' + input_id] = input_value;
              }
            }
            else if ( type == 'text' ) {
              if ( input_value ) {
                value_ids<?php echo $form_id ?>[key + '|' + input_id] = input_value;
              }
            }
          }
          else if ( tagName == 'SELECT' ) {
            select_value = jQuery('#form<?php echo $form_id ?> [id^="wdform_'+ input_id +'_elemen"] option:selected').val();
            if ( select_value ) {
              value_ids<?php echo $form_id ?>[key + '|' + input_id] = select_value;
            }
          }
          ajaxObj<?php echo $form_id ?>.inputs = value_ids<?php echo $form_id ?>;
          jQuery(document).on('change', '#form<?php echo $form_id ?> [id^="wdform_'+ input_id +'_elemen"]', function() {
          var id = '';
          var changing_field_id = '';
          if( jQuery(this).attr("tagName") == 'INPUT' ) {
            if( jQuery(this).is(':checked') ) {
              id = jQuery(this).val();
            }
            if( jQuery(this).attr('type') == 'text' ) {
              id = jQuery(this).val();
            }
          }
          else {
            id = jQuery(this).val();
          }
          value_ids<?php echo $form_id ?>[key + '|' + input_id] = id;
          jQuery.each( value_ids<?php echo $form_id ?>, function( k, v ) {
            key_arr = k.split('|');
            if ( input_id == key_arr[2] ) {
              changing_field_id = key_arr[0];
              count = Object.keys(value_ids<?php echo $form_id ?>).length;
              value_ids<?php echo $form_id ?> = unset_fields<?php echo $form_id ?>( value_ids<?php echo $form_id ?>, changing_field_id, count );
            }
          });
          ajaxObj<?php echo $form_id ?>.inputs = value_ids<?php echo $form_id ?>;
          ajax_similarity<?php echo $form_id ?>( ajaxObj<?php echo $form_id ?>, changing_field_id );
          });
        });
      });
      if ( update_first_field_id<?php echo $form_id; ?> && !jQuery.isEmptyObject(ajaxObj<?php echo $form_id ?>.inputs) ) {
        ajax_similarity<?php echo $form_id ?>( ajaxObj<?php echo $form_id ?>, update_first_field_id<?php echo $form_id; ?> );
      }
      form_load_actions();
      <?php echo !empty($signature_js) ? 'fm_signature_init();' : ''; ?>
	  }
    jQuery(function () {
      fm_script_ready<?php echo $form_id ?>();
    });
    <?php echo $signature_js; ?>
    <?php
    return ob_get_clean();
  }

  /**
   * Create js file.
   *
   * @param        $form_id
   * @param bool   $force_rewrite
   */
  public static function create_js( $form_id = 0, $force_rewrite = FALSE ) {
    $front_urls = WDFMInstance(self::PLUGIN)->front_urls;
    $wp_upload_dir = wp_upload_dir();
    $frontend_dir = '/form-maker-frontend/';
    if ( !is_dir($wp_upload_dir['basedir'] . $frontend_dir) ) {
      mkdir($wp_upload_dir['basedir'] . $frontend_dir);
      file_put_contents($wp_upload_dir['basedir'] . $frontend_dir . 'index.html', self::forbidden_template());
    }
    if ( !is_dir($wp_upload_dir['basedir'] . $frontend_dir . 'js') ) {
      mkdir($wp_upload_dir['basedir'] . $frontend_dir . 'js');
      file_put_contents($wp_upload_dir['basedir'] . $frontend_dir . 'js/index.html', self::forbidden_template());
    }
    $fm_script_dir = $wp_upload_dir['basedir'] . $frontend_dir . 'js/fm-script-' . $form_id . '.js';
    $fm_script_url = $front_urls['upload_url'] . $frontend_dir . 'js/fm-script-' . $form_id . '.js';

    $file_is_readable = file_get_contents($fm_script_dir);
    if ( !$force_rewrite && $file_is_readable ) {
      WDW_FM_Library(self::PLUGIN)->update_file_read_option(0);
      return;
    }

    clearstatcache();
    file_put_contents( $fm_script_dir, self::get_fm_js_content($form_id) );

    if ( WDFMInstance(self::PLUGIN)->fm_settings['fm_file_read'] == '0' ) {
      if ( !$file_is_readable ) {
        WDW_FM_Library(self::PLUGIN)->update_file_read_option(1);
      }
    }
    else if ( WDFMInstance(self::PLUGIN)->fm_settings['fm_file_read'] == '1' ) {
      if ( $file_is_readable ) {
        WDW_FM_Library(self::PLUGIN)->update_file_read_option(0);
      }
    }
  }

  /**
   * Sanitize array.
   *
   * @return array
   */
  public static function sanitize_array( $array, $callback = 'sanitize_text_field' ) {
    if (is_array($array)) {
      array_walk_recursive($array, array('self', 'validate_data'), $callback);
    }
    else {
      self::validate_data($value, 0, $callback);
    }
    return $value;

  }

  /**
   * Get submissions to export.
   *
   * @return array
   */
  public static function get_submissions_to_export() {
    global $wpdb;
    $params = array();
    $form_id = self::get( 'form_id', 0, 'intval' );
    $limitstart = self::get( 'limitstart', 0, 'intval' );
    $page_num   = self::get( 'page_num', 0, 'intval' );
    $search_labels = self::get( 'search_labels' );
    $gr_ids = self::get('groupids');
    $groupids = !empty($gr_ids) ? explode(',', $gr_ids) : array(); //TODO??//

    $verified_emails = isset($_REQUEST['verified_emails']) ? self::sanitize_array(json_decode(stripslashes($_REQUEST['verified_emails']), TRUE)) : array();

    $paypal_info_fields = array(
      'currency' => 'Currency',
      'ord_last_modified' => 'Last modified',
      'status' => 'Status',
      'full_name' => 'Full Name',
      'fax' => 'Fax',
      'mobile_phone' => 'Mobile phone',
      'email' => 'Email',
      'phone' => 'Phone',
      'address' => 'Address',
      'paypal_info' => 'Paypal info',
      'ipn' => 'IPN',
      'tax' => 'Tax',
      'shipping' => 'Shipping'
    );
    $query = $wpdb->prepare("SELECT distinct element_label FROM " . $wpdb->prefix . "formmaker_submits where form_id=%d", $form_id);
    $labels = $wpdb->get_col($query);
    $query_lable = $wpdb->prepare("SELECT label_order,title FROM " . $wpdb->prefix . "formmaker where id=%d", $form_id);
    $mail_verify = WDW_FM_Library::get_single_var_from_db($form_id, 'mail_verify');
    $send_to = WDW_FM_Library::get_single_var_from_db($form_id, 'send_to');

    $rows_lable = $wpdb->get_row($query_lable);
    $ptn = "/[^a-zA-Z0-9_]/";
    $rpltxt = "";
    $title = !empty($rows_lable) ? preg_replace($ptn, $rpltxt, $rows_lable->title) : '';
    $sorted_labels_id = array();
    $sorted_labels = array();
    $sorted_types = array();
    $label_titles = array();
    $label_id = array();
    $label_order = array();
    $label_order_original = array();
    $label_type = array();
    if ( $labels ) {
      $label_all = explode('#****#', $rows_lable->label_order);
      $label_all = array_slice($label_all, 0, count($label_all) - 1);
      foreach ( $label_all as $key => $label_each ) {
        $label_id_each = explode('#**id**#', $label_each);
        array_push($label_id, $label_id_each[0]);
        $label_oder_each = explode('#**label**#', $label_id_each[1]);
        array_push($label_order_original, $label_oder_each[0]);
        $label_temp = preg_replace($ptn, $rpltxt, $label_oder_each[0]);
        array_push($label_order, $label_temp);
        array_push($label_type, $label_oder_each[1]);
      }
      foreach ( $label_id as $key => $label ) {
        if ( in_array($label, $labels) && $label_type[$key] != 'type_arithmetic_captcha'
          && in_array($label, $labels) && $label_type[$key] != 'type_stripe' ) {
          array_push($sorted_labels, $label_order[$key]);
          array_push($sorted_labels_id, $label);
          array_push($label_titles, stripslashes($label_order_original[$key]));
          array_push($sorted_types, $label_type[$key]);
        }
      }
    }
    $m = count($sorted_labels);
    $wpdb->query("SET SESSION group_concat_max_len = 1000000");
    $rows = array();
    $data = array();
    $is_paypal_info = FALSE;
    if ( !empty($groupids) ) {
      $query = $wpdb->prepare("SELECT `group_id`, `ip`, `date`, `user_id_wd`, GROUP_CONCAT( element_label SEPARATOR ',') AS `element_label`, GROUP_CONCAT( element_value SEPARATOR '*:*el_value*:*') AS `element_value` FROM " . $wpdb->prefix . "formmaker_submits WHERE `form_id` = %d and `group_id` IN(" . implode(',', $groupids) . ") GROUP BY `group_id` ORDER BY `date` ASC", $form_id);
      $rows = $wpdb->get_results($query, OBJECT_K);
      for ( $www = 0; $www < count($groupids); $www++ ) {
        $i = $groupids[$www];
        $field_key = array_search($i, $label_id);
        if ( $label_type[$field_key] != 'type_arithmetic_captcha' && $label_type[$field_key] != 'type_stripe' ) {
          $data_temp = array();
          $tt = $rows[$i];
          $date = get_date_from_gmt( $tt->date );
          $ip = $tt->ip;
          $user_id = get_userdata($tt->user_id_wd);
          $username = $user_id ? $user_id->display_name : "";
          $useremail = $user_id ? $user_id->user_email : "";
          $data_temp['ID'] = $i;
          $data_temp['Submit date'] = $date;
          $data_temp['Submitter\'s IP'] = $ip;
          $data_temp['Submitter\'s Username'] = $username;
          $data_temp['Submitter\'s Email Address'] = $useremail;
          $element_labels = explode(',', $tt->element_label);
          $element_values = explode('*:*el_value*:*', $tt->element_value);
          // The 'Verified' key is displayed on all verified emails.
          if ( !empty($mail_verify) && !empty($send_to) ) {
            $send_mail_ids = explode('**', $send_to);
            if ( !empty($send_mail_ids) ) {
              foreach ( $send_mail_ids as $send_mail_id ) {
                $send_mail_id = str_replace('*', '', $send_mail_id);
                foreach ( $element_values as $v_index => $v_value ) {
                  if ( $v_value == 'verified**' . $send_mail_id ) {
                    $label_id_by_send_mail_id = array_search($send_mail_id, $element_labels);
                    $element_values[$label_id_by_send_mail_id] .= ' | Verified';
                  }
                }
              }
            }
          }

          for ( $h = 0; $h < $m; $h++ ) {
            if ( isset($data_temp[$label_titles[$h]]) ) {
              $label_titles[$h] .= '(1)';
            }
            if ( in_array($sorted_labels_id[$h], $element_labels) ) {
              $element_value = $element_values[array_search($sorted_labels_id[$h], $element_labels)];
              if ( strpos($element_value, "*@@url@@*") ) {
                $file_names = '';
                $new_files = explode("*@@url@@*", $element_value);
                foreach ( $new_files as $new_file ) {
                  if ( $new_file ) {
                    $file_names .= $new_file . ", ";
                  }
                }
                $data_temp[stripslashes($label_titles[$h])] = $file_names;
              }
              elseif ( strpos($element_value, "***br***") ) {
                $element_value = str_replace("***br***", ', ', $element_value);
                if ( strpos($element_value, "***quantity***") ) {
                  $element_value = str_replace("***quantity***", '', $element_value);
                }
                if ( strpos($element_value, "***property***") ) {
                  $element_value = str_replace("***property***", '', $element_value);
                }
                if ( substr($element_value, -2) == ', ' ) {
                  $data_temp[stripslashes($label_titles[$h])] = substr($element_value, 0, -2);
                }
                else {
                  $data_temp[stripslashes($label_titles[$h])] = $element_value;
                }
              }
              elseif ( strpos($element_value, "***map***") ) {
                $data_temp[stripslashes($label_titles[$h])] = 'Longitude:' . str_replace("***map***", ', Latitude:', $element_value);
              }
              elseif ( strpos($element_value, "***star_rating***") ) {
                $element = str_replace("***star_rating***", '', $element_value);
                $element = explode("***", $element);
                $data_temp[stripslashes($label_titles[$h])] = ' ' . $element[1] . '/' . $element[0];
              }
              elseif ( strpos($element_value, "@@@") !== FALSE ) {
                $data_temp[stripslashes($label_titles[$h])] = str_replace("@@@", ' ', $element_value);
              }
              elseif ( strpos($element_value, "***grading***") ) {
                $element = str_replace("***grading***", '', $element_value);
                $grading = explode(":", $element);
                $items_count = sizeof($grading) - 1;
                $items = "";
                $total = "";
                for ( $k = 0; $k < $items_count / 2; $k++ ) {
                  $items .= $grading[$items_count / 2 + $k] . ": " . $grading[$k] . ", ";
                  $total += $grading[$k];
                }
                $items .= "Total: " . $total;
                $data_temp[stripslashes($label_titles[$h])] = $items;
              }
              elseif ( strpos($element_value, "***matrix***") ) {
                $element = str_replace("***matrix***", '', $element_value);
                $matrix_value = explode('***', $element);
                $matrix_value = array_slice($matrix_value, 0, count($matrix_value) - 1);
                $mat_rows = $matrix_value[0];
                $mat_columns = $matrix_value[$mat_rows + 1];
                $matrix = "";
                $aaa = array();
                $var_checkbox = 1;
                $selected_value = "";
                $selected_value_yes = "";
                $selected_value_no = "";
                for ( $k = 1; $k <= $mat_rows; $k++ ) {
                  if ( $matrix_value[$mat_rows + $mat_columns + 2] == "radio" ) {
                    if ( $matrix_value[$mat_rows + $mat_columns + 2 + $k] == 0 ) {
                      $checked = "0";
                      $aaa[1] = "";
                    }
                    else {
                      $aaa = explode("_", $matrix_value[$mat_rows + $mat_columns + 2 + $k]);
                    }
                    for ( $l = 1; $l <= $mat_columns; $l++ ) {
                      $checked = $aaa[1] == $l ? '1' : '0';
                      $matrix .= '[' . $matrix_value[$k] . ',' . $matrix_value[$mat_rows + 1 + $l] . ']=' . $checked . "; ";
                    }
                  }
                  else {
                    if ( $matrix_value[$mat_rows + $mat_columns + 2] == "checkbox" ) {
                      for ( $l = 1; $l <= $mat_columns; $l++ ) {
                        $checked = $matrix_value[$mat_rows + $mat_columns + 2 + $var_checkbox] == 1 ? '1' : '0';
                        $matrix .= '[' . $matrix_value[$k] . ',' . $matrix_value[$mat_rows + 1 + $l] . ']=' . $checked . "; ";
                        $var_checkbox++;
                      }
                    }
                    else {
                      if ( $matrix_value[$mat_rows + $mat_columns + 2] == "text" ) {
                        for ( $l = 1; $l <= $mat_columns; $l++ ) {
                          $text_value = $matrix_value[$mat_rows + $mat_columns + 2 + $var_checkbox];
                          $matrix .= '[' . $matrix_value[$k] . ',' . $matrix_value[$mat_rows + 1 + $l] . ']=' . $text_value . "; ";
                          $var_checkbox++;
                        }
                      }
                      else {
                        for ( $l = 1; $l <= $mat_columns; $l++ ) {
                          $selected_text = $matrix_value[$mat_rows + $mat_columns + 2 + $var_checkbox];
                          $matrix .= '[' . $matrix_value[$k] . ',' . $matrix_value[$mat_rows + 1 + $l] . ']=' . $selected_text . "; ";
                          $var_checkbox++;
                        }
                      }
                    }
                  }
                }
                $data_temp[stripslashes($label_titles[$h])] = $matrix;
              }
              else {
                $val = strip_tags(html_entity_decode($element_value));
                $val = stripslashes(str_replace('&#039;', "'", $val));
                $data_temp[stripslashes($label_titles[$h])] = $val;
              }
            }
            else {
              $data_temp[stripslashes($label_titles[$h])] = '';
            }
            if ( isset($verified_emails[$sorted_labels_id[$h]]) && $sorted_types[$h] == "type_submitter_mail" ) {
              if ( $data_temp[stripslashes($label_titles[$h])] == '' ) {
                $data_temp[stripslashes($label_titles[$h]) . '(verified)'] = '';
              }
              else {
                if ( in_array($i, $verified_emails[$sorted_labels_id[$h]]) ) {
                  $data_temp[stripslashes($label_titles[$h]) . '(verified)'] = 'yes';
                }
                else {
                  $data_temp[stripslashes($label_titles[$h]) . '(verified)'] = 'no';
                }
              }
            }
          }
          $item_total = $wpdb->get_var($wpdb->prepare("SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=%d AND element_label=%s", $i, 'item_total'));
          $total = $wpdb->get_var($wpdb->prepare("SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=%d AND element_label=%s", $i, 'total'));
          $payment_status = $wpdb->get_var($wpdb->prepare("SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=%d AND element_label=%s", $i, '0'));
          $user_email = $wpdb->get_var($wpdb->prepare("SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=%d AND element_label=%s", $i, 'user_email'));
          if ( $item_total ) {
            $data_temp['Item Total'] = $item_total;
          }
          if ( $total ) {
            $data_temp['Total'] = $total;
          }
          if ( $payment_status ) {
            $data_temp['Payment Status'] = $payment_status;
          }
          if ( $user_email ) {
            $data_temp['user_email'] = $user_email;
          }
          $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "formmaker_sessions where group_id=%d", $i);
          $paypal_info = $wpdb->get_results($query);
          if ( $paypal_info ) {
            $is_paypal_info = TRUE;
          }
          if ( $is_paypal_info ) {
            foreach ( $paypal_info_fields as $key => $paypal_info_field ) {
              if ( $paypal_info ) {
                $data_temp['PAYPAL_' . $paypal_info_field ] = $paypal_info[0]->$key;
              }
              else {
                $data_temp['PAYPAL_' . $paypal_info_field ] = '';
              }
            }
          }
          $data[$i] = $data_temp;
        }
      }
    }
    array_push($params, $data);
    array_push($params, $title);
    array_push($params, $is_paypal_info);

    return $params;
  }

  /**
   * No items.
   *
   * @param $title
   *
   * @return string
   */
  public static function no_items($title) {
    $title = ($title != '') ? strtolower($title) : 'items';
    ob_start();
    ?><tr class="no-items">
    <td class="colspanchange fm-column-not-hide" colspan="0"><?php echo sprintf(__('No %s found.', WDFMInstance(self::PLUGIN)->prefix), $title); ?></td>
    </tr><?php
    return ob_get_clean();
  }

  /**
   * Get current page url.
   *
   * @param $ajax_submit bool
   *
   * @return string
  */
  public static function get_current_page_url( $ajax_submit = 0 ) {
    $referer = wp_get_referer();
    if( $ajax_submit && $referer != false ) {
      return sanitize_url(wp_get_referer());
    } else {
      return sanitize_url(trim((is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
    }
  }

  /**
   * Get all addons.
   *
   * @return array $addons
   */
  public static function get_all_addons_path() {
    $addons = array(
      'form-maker-export-import/fm_exp_imp.php',
      'form-maker-save-progress/fm_save.php',
      'form-maker-conditional-emails/fm_conditional_emails.php',
      'form-maker-pushover/fm_pushover.php',
      'form-maker-mailchimp/fm_mailchimp.php',
      'form-maker-reg/fm_reg.php',
      'form-maker-post-generation/fm_post_generation.php',
      'form-maker-dropbox-integration/fm_dropbox_integration.php',
      'form-maker-gdrive-integration/fm_gdrive_integration.php',
      'form-maker-pdf-integration/fm_pdf_integration.php',
      'form-maker-stripe/fm_stripe.php',
      'form-maker-calculator/fm_calculator.php',
      'form-maker-webhooks/fm_webhooks.php',
    );

    return $addons;
  }

  /**
   * Deactivate all addons with given additional plugin.
   *
   * @param bool $additional_plugin
   */
  public static function deactivate_all_addons($additional_plugin = FALSE) {
    include_once( WDFMInstance(self::PLUGIN)->abspath . 'wp-admin/includes/plugin.php' );
    $addons = self::get_all_addons_path();
    if ( $additional_plugin ) {
      array_push($addons, $additional_plugin);
    }
    foreach ( $addons as $addon ) {
      if ( is_plugin_active( $addon ) ) {
        deactivate_plugins( $addon, false, false );
      }
    }
  }

  /**
   * Get countries list.
   *
   * @return array
   */
  public static function get_countries() {
    $countries = array(
      "" => "",
      "Afghanistan" => __("Afghanistan", WDFMInstance(self::PLUGIN)->prefix),
      "Albania" => __("Albania", WDFMInstance(self::PLUGIN)->prefix),
      "Algeria" => __("Algeria", WDFMInstance(self::PLUGIN)->prefix),
      "Andorra" => __("Andorra", WDFMInstance(self::PLUGIN)->prefix),
      "Angola" => __("Angola", WDFMInstance(self::PLUGIN)->prefix),
      "Antigua and Barbuda" => __("Antigua and Barbuda", WDFMInstance(self::PLUGIN)->prefix),
      "Argentina" => __("Argentina", WDFMInstance(self::PLUGIN)->prefix),
      "Armenia" => __("Armenia", WDFMInstance(self::PLUGIN)->prefix),
      "Australia" => __("Australia", WDFMInstance(self::PLUGIN)->prefix),
      "Austria" => __("Austria", WDFMInstance(self::PLUGIN)->prefix),
      "Azerbaijan" => __("Azerbaijan", WDFMInstance(self::PLUGIN)->prefix),
      "Bahamas" => __("Bahamas", WDFMInstance(self::PLUGIN)->prefix),
      "Bahrain" => __("Bahrain", WDFMInstance(self::PLUGIN)->prefix),
      "Bangladesh" => __("Bangladesh", WDFMInstance(self::PLUGIN)->prefix),
      "Barbados" => __("Barbados", WDFMInstance(self::PLUGIN)->prefix),
      "Belarus" => __("Belarus", WDFMInstance(self::PLUGIN)->prefix),
      "Belgium" => __("Belgium", WDFMInstance(self::PLUGIN)->prefix),
      "Belize" => __("Belize", WDFMInstance(self::PLUGIN)->prefix),
      "Benin" => __("Benin", WDFMInstance(self::PLUGIN)->prefix),
      "Bhutan" => __("Bhutan", WDFMInstance(self::PLUGIN)->prefix),
      "Bolivia" => __("Bolivia", WDFMInstance(self::PLUGIN)->prefix),
      "Bosnia and Herzegovina" => __("Bosnia and Herzegovina", WDFMInstance(self::PLUGIN)->prefix),
      "Botswana" => __("Botswana", WDFMInstance(self::PLUGIN)->prefix),
      "Brazil" => __("Brazil", WDFMInstance(self::PLUGIN)->prefix),
      "Brunei" => __("Brunei", WDFMInstance(self::PLUGIN)->prefix),
      "Bulgaria" => __("Bulgaria", WDFMInstance(self::PLUGIN)->prefix),
      "Burkina" => __("Burkina Faso", WDFMInstance(self::PLUGIN)->prefix),
      "Burundi" => __("Burundi", WDFMInstance(self::PLUGIN)->prefix),
      "Cambodia" => __("Cambodia", WDFMInstance(self::PLUGIN)->prefix),
      "Cameroon" => __("Cameroon", WDFMInstance(self::PLUGIN)->prefix),
      "Canada" => __("Canada", WDFMInstance(self::PLUGIN)->prefix),
      "Cape Verde" => __("Cape Verde", WDFMInstance(self::PLUGIN)->prefix),
      "Central African Republic" => __("Central African Republic", WDFMInstance(self::PLUGIN)->prefix),
      "Chad" => __("Chad", WDFMInstance(self::PLUGIN)->prefix),
      "Chile" => __("Chile", WDFMInstance(self::PLUGIN)->prefix),
      "China" => __("China", WDFMInstance(self::PLUGIN)->prefix),
      "Colombia" => __("Colombia", WDFMInstance(self::PLUGIN)->prefix),
      "Comoros" => __("Comoros", WDFMInstance(self::PLUGIN)->prefix),
      "Congo (Brazzaville)" => __("Congo (Brazzaville)", WDFMInstance(self::PLUGIN)->prefix),
      "Congo" => __("Congo", WDFMInstance(self::PLUGIN)->prefix),
      "Costa Rica" => __("Costa Rica", WDFMInstance(self::PLUGIN)->prefix),
      "Cote d'Ivoire" => __("Cote d'Ivoire", WDFMInstance(self::PLUGIN)->prefix),
      "Croatia" => __("Croatia", WDFMInstance(self::PLUGIN)->prefix),
      "Cuba" => __("Cuba", WDFMInstance(self::PLUGIN)->prefix),
      "Curacao" => __("Curacao", WDFMInstance(self::PLUGIN)->prefix),
      "Cyprus" => __("Cyprus", WDFMInstance(self::PLUGIN)->prefix),
      "Czech Republic" => __("Czech Republic", WDFMInstance(self::PLUGIN)->prefix),
      "Denmark" => __("Denmark", WDFMInstance(self::PLUGIN)->prefix),
      "Djibouti" => __("Djibouti", WDFMInstance(self::PLUGIN)->prefix),
      "Dominica" => __("Dominica", WDFMInstance(self::PLUGIN)->prefix),
      "Dominican Republic" => __("Dominican Republic", WDFMInstance(self::PLUGIN)->prefix),
      "East Timor (Timor Timur" => __("East Timor (Timor Timur)", WDFMInstance(self::PLUGIN)->prefix),
      "Ecuador" => __("Ecuador", WDFMInstance(self::PLUGIN)->prefix),
      "Egypt" => __("Egypt", WDFMInstance(self::PLUGIN)->prefix),
      "El Salvador" => __("El Salvador", WDFMInstance(self::PLUGIN)->prefix),
      "Equatorial" => __("Equatorial Guinea", WDFMInstance(self::PLUGIN)->prefix),
      "Eritrea" => __("Eritrea", WDFMInstance(self::PLUGIN)->prefix),
      "Estonia" => __("Estonia", WDFMInstance(self::PLUGIN)->prefix),
      "Ethiopia" => __("Ethiopia", WDFMInstance(self::PLUGIN)->prefix),
      "Fiji" => __("Fiji", WDFMInstance(self::PLUGIN)->prefix),
      "Finland" => __("Finland", WDFMInstance(self::PLUGIN)->prefix),
      "France" => __("France", WDFMInstance(self::PLUGIN)->prefix),
      "Gabon" => __("Gabon", WDFMInstance(self::PLUGIN)->prefix),
      "Gambia, The" => __("Gambia, The", WDFMInstance(self::PLUGIN)->prefix),
      "Georgia" => __("Georgia", WDFMInstance(self::PLUGIN)->prefix),
      "Germany" => __("Germany", WDFMInstance(self::PLUGIN)->prefix),
      "Ghana" => __("Ghana", WDFMInstance(self::PLUGIN)->prefix),
      "Greece" => __("Greece", WDFMInstance(self::PLUGIN)->prefix),
      "Grenada" => __("Grenada", WDFMInstance(self::PLUGIN)->prefix),
      "Guatemala" => __("Guatemala", WDFMInstance(self::PLUGIN)->prefix),
      "Guinea" => __("Guinea", WDFMInstance(self::PLUGIN)->prefix),
      "Guinea-Bissau" => __("Guinea-Bissau", WDFMInstance(self::PLUGIN)->prefix),
      "Guyana" => __("Guyana", WDFMInstance(self::PLUGIN)->prefix),
      "Haiti" => __("Haiti", WDFMInstance(self::PLUGIN)->prefix),
      "Honduras" => __("Honduras", WDFMInstance(self::PLUGIN)->prefix),
      "Hong Kong" => __("Hong Kong", WDFMInstance(self::PLUGIN)->prefix),
      "Hungary" => __("Hungary", WDFMInstance(self::PLUGIN)->prefix),
      "Iceland" => __("Iceland", WDFMInstance(self::PLUGIN)->prefix),
      "India" => __("India", WDFMInstance(self::PLUGIN)->prefix),
      "Indonesia" => __("Indonesia", WDFMInstance(self::PLUGIN)->prefix),
      "Iran" => __("Iran", WDFMInstance(self::PLUGIN)->prefix),
      "Iraq" => __("Iraq", WDFMInstance(self::PLUGIN)->prefix),
      "Ireland" => __("Ireland", WDFMInstance(self::PLUGIN)->prefix),
      "Israel" => __("Israel", WDFMInstance(self::PLUGIN)->prefix),
      "Italy" => __("Italy", WDFMInstance(self::PLUGIN)->prefix),
      "Jamaica" => __("Jamaica", WDFMInstance(self::PLUGIN)->prefix),
      "Japan" => __("Japan", WDFMInstance(self::PLUGIN)->prefix),
      "Jordan" => __("Jordan", WDFMInstance(self::PLUGIN)->prefix),
      "Kazakhstan" => __("Kazakhstan", WDFMInstance(self::PLUGIN)->prefix),
      "Kenya" => __("Kenya", WDFMInstance(self::PLUGIN)->prefix),
      "Kiribati" => __("Kiribati", WDFMInstance(self::PLUGIN)->prefix),
      "Korea, North" => __("Korea, North", WDFMInstance(self::PLUGIN)->prefix),
      "Korea, South" => __("Korea, South", WDFMInstance(self::PLUGIN)->prefix),
      "Kuwait" => __("Kuwait", WDFMInstance(self::PLUGIN)->prefix),
      "Kyrgyzstan" => __("Kyrgyzstan", WDFMInstance(self::PLUGIN)->prefix),
      "Laos" => __("Laos", WDFMInstance(self::PLUGIN)->prefix),
      "Latvia" => __("Latvia", WDFMInstance(self::PLUGIN)->prefix),
      "Lebanon" => __("Lebanon", WDFMInstance(self::PLUGIN)->prefix),
      "Lesotho" => __("Lesotho", WDFMInstance(self::PLUGIN)->prefix),
      "Liberia" => __("Liberia", WDFMInstance(self::PLUGIN)->prefix),
      "Libya" => __("Libya", WDFMInstance(self::PLUGIN)->prefix),
      "Liechtenstein" => __("Liechtenstein", WDFMInstance(self::PLUGIN)->prefix),
      "Lithuania" => __("Lithuania", WDFMInstance(self::PLUGIN)->prefix),
      "Luxembourg" => __("Luxembourg", WDFMInstance(self::PLUGIN)->prefix),
      "North Macedonia" => __("North Macedonia", WDFMInstance(self::PLUGIN)->prefix),
      "Madagascar" => __("Madagascar", WDFMInstance(self::PLUGIN)->prefix),
      "Malawi" => __("Malawi", WDFMInstance(self::PLUGIN)->prefix),
      "Malaysia" => __("Malaysia", WDFMInstance(self::PLUGIN)->prefix),
      "Maldives" => __("Maldives", WDFMInstance(self::PLUGIN)->prefix),
      "Mali" => __("Mali", WDFMInstance(self::PLUGIN)->prefix),
      "Malta" => __("Malta", WDFMInstance(self::PLUGIN)->prefix),
      "Marshall Islands" => __("Marshall Islands", WDFMInstance(self::PLUGIN)->prefix),
      "Mauritania" => __("Mauritania", WDFMInstance(self::PLUGIN)->prefix),
      "Mauritius" => __("Mauritius", WDFMInstance(self::PLUGIN)->prefix),
      "Mexico" => __("Mexico", WDFMInstance(self::PLUGIN)->prefix),
      "Micronesia" => __("Micronesia", WDFMInstance(self::PLUGIN)->prefix),
      "Moldova" => __("Moldova", WDFMInstance(self::PLUGIN)->prefix),
      "Monaco" => __("Monaco", WDFMInstance(self::PLUGIN)->prefix),
      "Mongolia" => __("Mongolia", WDFMInstance(self::PLUGIN)->prefix),
      "Morocco" => __("Morocco", WDFMInstance(self::PLUGIN)->prefix),
      "Mozambique" => __("Mozambique", WDFMInstance(self::PLUGIN)->prefix),
      "Myanmar" => __("Myanmar", WDFMInstance(self::PLUGIN)->prefix),
      "Namibia" => __("Namibia", WDFMInstance(self::PLUGIN)->prefix),
      "Nauru" => __("Nauru", WDFMInstance(self::PLUGIN)->prefix),
      "Nepal" => __("Nepal", WDFMInstance(self::PLUGIN)->prefix),
      "Netherlands" => __("Netherlands", WDFMInstance(self::PLUGIN)->prefix),
      "New Zealand" => __("New Zealand", WDFMInstance(self::PLUGIN)->prefix),
      "Nicaragua" => __("Nicaragua", WDFMInstance(self::PLUGIN)->prefix),
      "Niger" => __("Niger", WDFMInstance(self::PLUGIN)->prefix),
      "Nigeria" => __("Nigeria", WDFMInstance(self::PLUGIN)->prefix),
      "Norway" => __("Norway", WDFMInstance(self::PLUGIN)->prefix),
      "Oman" => __("Oman", WDFMInstance(self::PLUGIN)->prefix),
      "Pakistan" => __("Pakistan", WDFMInstance(self::PLUGIN)->prefix),
      "Palau" => __("Palau", WDFMInstance(self::PLUGIN)->prefix),
      "Palestine" => __("Palestine", WDFMInstance(self::PLUGIN)->prefix),
      "Panama" => __("Panama", WDFMInstance(self::PLUGIN)->prefix),
      "Papua New Guinea" => __("Papua New Guinea", WDFMInstance(self::PLUGIN)->prefix),
      "Paraguay" => __("Paraguay", WDFMInstance(self::PLUGIN)->prefix),
      "Peru" => __("Peru", WDFMInstance(self::PLUGIN)->prefix),
      "Philippines" => __("Philippines", WDFMInstance(self::PLUGIN)->prefix),
      "Poland" => __("Poland", WDFMInstance(self::PLUGIN)->prefix),
      "Portugal" => __("Portugal", WDFMInstance(self::PLUGIN)->prefix),
      "Puerto Rico" => __("Puerto Rico", WDFMInstance(self::PLUGIN)->prefix),
      "Qatar" => __("Qatar", WDFMInstance(self::PLUGIN)->prefix),
      "Romania" => __("Romania", WDFMInstance(self::PLUGIN)->prefix),
      "Russia" => __("Russia", WDFMInstance(self::PLUGIN)->prefix),
      "Rwanda" => __("Rwanda", WDFMInstance(self::PLUGIN)->prefix),
      "Saint Kitts and Nevis" => __("Saint Kitts and Nevis", WDFMInstance(self::PLUGIN)->prefix),
      "Saint Lucia" => __("Saint Lucia", WDFMInstance(self::PLUGIN)->prefix),
      "Saint Vincent" => __("Saint Vincent", WDFMInstance(self::PLUGIN)->prefix),
      "Samoa" => __("Samoa", WDFMInstance(self::PLUGIN)->prefix),
      "San Marino" => __("San Marino", WDFMInstance(self::PLUGIN)->prefix),
      "Sao Tome and Principe" => __("Sao Tome and Principe", WDFMInstance(self::PLUGIN)->prefix),
      "Saudi Arabia" => __("Saudi Arabia", WDFMInstance(self::PLUGIN)->prefix),
      "Senegal" => __("Senegal", WDFMInstance(self::PLUGIN)->prefix),
      "Serbia" => __("Serbia", WDFMInstance(self::PLUGIN)->prefix),
      "Montenegro" => __("Montenegro", WDFMInstance(self::PLUGIN)->prefix),
      "Seychelles" => __("Seychelles", WDFMInstance(self::PLUGIN)->prefix),
      "Sierra Leone" => __("Sierra Leone", WDFMInstance(self::PLUGIN)->prefix),
      "Singapore" => __("Singapore", WDFMInstance(self::PLUGIN)->prefix),
      "Slovakia" => __("Slovakia", WDFMInstance(self::PLUGIN)->prefix),
      "Slovenia" => __("Slovenia", WDFMInstance(self::PLUGIN)->prefix),
      "Solomon Islands" => __("Solomon Islands", WDFMInstance(self::PLUGIN)->prefix),
      "Somalia" => __("Somalia", WDFMInstance(self::PLUGIN)->prefix),
      "South Africa" => __("South Africa", WDFMInstance(self::PLUGIN)->prefix),
      "South Sudan" => __("South Sudan", WDFMInstance(self::PLUGIN)->prefix),
      "Spain" => __("Spain", WDFMInstance(self::PLUGIN)->prefix),
      "Sri Lanka" => __("Sri Lanka", WDFMInstance(self::PLUGIN)->prefix),
      "Sudan" => __("Sudan", WDFMInstance(self::PLUGIN)->prefix),
      "Suriname" => __("Suriname", WDFMInstance(self::PLUGIN)->prefix),
      "Eswatini" => __("Eswatini", WDFMInstance(self::PLUGIN)->prefix),
      "Sweden" => __("Sweden", WDFMInstance(self::PLUGIN)->prefix),
      "Switzerland" => __("Switzerland", WDFMInstance(self::PLUGIN)->prefix),
      "Syria" => __("Syria", WDFMInstance(self::PLUGIN)->prefix),
      "Taiwan" => __("Taiwan", WDFMInstance(self::PLUGIN)->prefix),
      "Tajikistan" => __("Tajikistan", WDFMInstance(self::PLUGIN)->prefix),
      "Tanzania" => __("Tanzania", WDFMInstance(self::PLUGIN)->prefix),
      "Thailand" => __("Thailand", WDFMInstance(self::PLUGIN)->prefix),
      "Togo" => __("Togo", WDFMInstance(self::PLUGIN)->prefix),
      "Tonga" => __("Tonga", WDFMInstance(self::PLUGIN)->prefix),
      "Trinidad and Tobago" => __("Trinidad and Tobago", WDFMInstance(self::PLUGIN)->prefix),
      "Tunisia" => __("Tunisia", WDFMInstance(self::PLUGIN)->prefix),
      "Turkey" => __("Turkey", WDFMInstance(self::PLUGIN)->prefix),
      "Turkmenistan" => __("Turkmenistan", WDFMInstance(self::PLUGIN)->prefix),
      "Tuvalu" => __("Tuvalu", WDFMInstance(self::PLUGIN)->prefix),
      "Uganda" => __("Uganda", WDFMInstance(self::PLUGIN)->prefix),
      "Ukraine" => __("Ukraine", WDFMInstance(self::PLUGIN)->prefix),
      "United Arab Emirates" => __("United Arab Emirates", WDFMInstance(self::PLUGIN)->prefix),
      "United Kingdom" => __("United Kingdom", WDFMInstance(self::PLUGIN)->prefix),
      "United States" => __("United States", WDFMInstance(self::PLUGIN)->prefix),
      "Uruguay" => __("Uruguay", WDFMInstance(self::PLUGIN)->prefix),
      "Uzbekistan" => __("Uzbekistan", WDFMInstance(self::PLUGIN)->prefix),
      "Vanuatu" => __("Vanuatu", WDFMInstance(self::PLUGIN)->prefix),
      "Vatican City" => __("Vatican City", WDFMInstance(self::PLUGIN)->prefix),
      "Venezuela" => __("Venezuela", WDFMInstance(self::PLUGIN)->prefix),
      "Vietnam" => __("Vietnam", WDFMInstance(self::PLUGIN)->prefix),
      "Wales" => __("Wales", WDFMInstance(self::PLUGIN)->prefix),
      "Yemen" => __("Yemen", WDFMInstance(self::PLUGIN)->prefix),
      "Zambia" => __("Zambia", WDFMInstance(self::PLUGIN)->prefix),
      "Zimbabwe" => __("Zimbabwe", WDFMInstance(self::PLUGIN)->prefix),
    );
    asort( $countries );
    return $countries;
  }

  /**
   * Get states list.
   *
   * @return array
   */
  public static function get_states() {
    $states = array(
      "" => "",
      "Alabama" => __("Alabama", WDFMInstance(self::PLUGIN)->prefix),
      "Alaska" => __("Alaska", WDFMInstance(self::PLUGIN)->prefix),
      "Arizona" => __("Arizona", WDFMInstance(self::PLUGIN)->prefix),
      "Arkansas" => __("Arkansas", WDFMInstance(self::PLUGIN)->prefix),
      "California" => __("California", WDFMInstance(self::PLUGIN)->prefix),
      "Colorado" => __("Colorado", WDFMInstance(self::PLUGIN)->prefix),
      "Connecticut" => __("Connecticut", WDFMInstance(self::PLUGIN)->prefix),
      "Delaware" => __("Delaware", WDFMInstance(self::PLUGIN)->prefix),
      "District Of Columbia" => __("District Of Columbia", WDFMInstance(self::PLUGIN)->prefix),
      "Florida" => __("Florida", WDFMInstance(self::PLUGIN)->prefix),
      "Georgia" => __("Georgia", WDFMInstance(self::PLUGIN)->prefix),
      "Hawaii" => __("Hawaii", WDFMInstance(self::PLUGIN)->prefix),
      "Idaho" => __("Idaho", WDFMInstance(self::PLUGIN)->prefix),
      "Illinois" => __("Illinois", WDFMInstance(self::PLUGIN)->prefix),
      "Indiana" => __("Indiana", WDFMInstance(self::PLUGIN)->prefix),
      "Iowa" => __("Iowa", WDFMInstance(self::PLUGIN)->prefix),
      "Kansas" => __("Kansas", WDFMInstance(self::PLUGIN)->prefix),
      "Kentucky" => __("Kentucky", WDFMInstance(self::PLUGIN)->prefix),
      "Louisiana" => __("Louisiana", WDFMInstance(self::PLUGIN)->prefix),
      "Maine" => __("Maine", WDFMInstance(self::PLUGIN)->prefix),
      "Maryland" => __("Maryland", WDFMInstance(self::PLUGIN)->prefix),
      "Massachusetts" => __("Massachusetts", WDFMInstance(self::PLUGIN)->prefix),
      "Michigan" => __("Michigan", WDFMInstance(self::PLUGIN)->prefix),
      "Minnesota" => __("Minnesota", WDFMInstance(self::PLUGIN)->prefix),
      "Mississippi" => __("Mississippi", WDFMInstance(self::PLUGIN)->prefix),
      "Missouri" => __("Missouri", WDFMInstance(self::PLUGIN)->prefix),
      "Montana" => __("Montana", WDFMInstance(self::PLUGIN)->prefix),
      "Nebraska" => __("Nebraska", WDFMInstance(self::PLUGIN)->prefix),
      "Nevada" => __("Nevada", WDFMInstance(self::PLUGIN)->prefix),
      "New Hampshire" => __("New Hampshire", WDFMInstance(self::PLUGIN)->prefix),
      "New Jersey" => __("New Jersey", WDFMInstance(self::PLUGIN)->prefix),
      "New Mexico" => __("New Mexico", WDFMInstance(self::PLUGIN)->prefix),
      "New York" => __("New York", WDFMInstance(self::PLUGIN)->prefix),
      "North Carolina" => __("North Carolina", WDFMInstance(self::PLUGIN)->prefix),
      "North Dakota" => __("North Dakota", WDFMInstance(self::PLUGIN)->prefix),
      "Ohio" => __("Ohio", WDFMInstance(self::PLUGIN)->prefix),
      "Oklahoma" => __("Oklahoma", WDFMInstance(self::PLUGIN)->prefix),
      "Oregon" => __("Oregon", WDFMInstance(self::PLUGIN)->prefix),
      "Pennsylvania" => __("Pennsylvania", WDFMInstance(self::PLUGIN)->prefix),
      "Rhode Island" => __("Rhode Island", WDFMInstance(self::PLUGIN)->prefix),
      "South Carolina" => __("South Carolina", WDFMInstance(self::PLUGIN)->prefix),
      "South Dakota" => __("South Dakota", WDFMInstance(self::PLUGIN)->prefix),
      "Tennessee" => __("Tennessee", WDFMInstance(self::PLUGIN)->prefix),
      "Texas" => __("Texas", WDFMInstance(self::PLUGIN)->prefix),
      "Utah" => __("Utah", WDFMInstance(self::PLUGIN)->prefix),
      "Vermont" => __("Vermont", WDFMInstance(self::PLUGIN)->prefix),
      "Virginia" => __("Virginia", WDFMInstance(self::PLUGIN)->prefix),
      "Washington" => __("Washington", WDFMInstance(self::PLUGIN)->prefix),
      "West Virginia" => __("West Virginia", WDFMInstance(self::PLUGIN)->prefix),
      "Wisconsin" => __("Wisconsin", WDFMInstance(self::PLUGIN)->prefix),
      "Wyoming" => __("Wyoming", WDFMInstance(self::PLUGIN)->prefix),
    );
    asort( $states );
    return $states;
  }

    /**
   * Get Canada states list.
   *
   * @return array
   */
  public static function get_provinces_canada() {
    $province = array(
      "" => "",
      "Alberta" => __("Alberta", WDFMInstance(self::PLUGIN)->prefix),
      "British Columbia" => __("British Columbia", WDFMInstance(self::PLUGIN)->prefix),
      "Manitoba" => __("Manitoba", WDFMInstance(self::PLUGIN)->prefix),
      "New Brunswick" => __("New Brunswick", WDFMInstance(self::PLUGIN)->prefix),
      "Newfoundland and Labrador" => __("Newfoundland and Labrador", WDFMInstance(self::PLUGIN)->prefix),
      "Northwest Territories" => __("Northwest Territories", WDFMInstance(self::PLUGIN)->prefix),
      "Nova Scotia" => __("Nova Scotia", WDFMInstance(self::PLUGIN)->prefix),
      "Nunavut" => __("Nunavut", WDFMInstance(self::PLUGIN)->prefix),
      "Ontario" => __("Ontario", WDFMInstance(self::PLUGIN)->prefix),
      "Prince Edward Island" => __("Prince Edward Island", WDFMInstance(self::PLUGIN)->prefix),
      "Quebec" => __("Quebec", WDFMInstance(self::PLUGIN)->prefix),
      "Saskatchewan" => __("Saskatchewan", WDFMInstance(self::PLUGIN)->prefix),
      "Yukon" => __("Yukon", WDFMInstance(self::PLUGIN)->prefix),
    );
    asort( $province );
    return $province;
  }

  /**
   * Localize ui datepicker.
   *
   * @return string
   */
  public static function localize_ui_datepicker() {
    return 'jQuery(function(jQuery){
      jQuery.datepicker.setDefaults( {
        "closeText":"' . __('Done', WDFMInstance(self::PLUGIN)->prefix) . '",
        "prevText":"' . __('Prev', WDFMInstance(self::PLUGIN)->prefix) . '",
        "nextText":"' . __('Next', WDFMInstance(self::PLUGIN)->prefix) . '",
        "currentText":"' . __('Today', WDFMInstance(self::PLUGIN)->prefix) . '",
        "monthNames":["' . __('January', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('February', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('March', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('April', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('May', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('June', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('July', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('August', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('September', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('October', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('November', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('December', WDFMInstance(self::PLUGIN)->prefix) . '"],
        "monthNamesShort":["' . __('Jan', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Feb', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Mar', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Apr', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('May', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Jun', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Jul', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Aug', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Sep', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Oct', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Nov', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Dec', WDFMInstance(self::PLUGIN)->prefix) . '"],
        "dayNames":["' . __('Sunday', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Monday', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Tuesday', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Wednesday', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Thursday', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Friday', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Saturday', WDFMInstance(self::PLUGIN)->prefix) . '"],
        "dayNamesShort":["' . __('Sun', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Mon', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Tue', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Wed', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Thu', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Fri', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Sat', WDFMInstance(self::PLUGIN)->prefix) . '"],
       "dayNamesMin":["' . __('Su', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Mo', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Tu', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('We', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Th', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Fr', WDFMInstance(self::PLUGIN)->prefix) . '","' . __('Sa', WDFMInstance(self::PLUGIN)->prefix) . '"]
      });
    });';
  }

  /**
   * Forbidden template.
   *
   * @return string
   */
  public static function forbidden_template() {
    return '<!DOCTYPE html>
				<html>
				<head>
					<title>403 Forbidden</title>
				</head>
				<body>
					<p>Directory access is forbidden.</p>
				</body>
				</html>';
  }

  /**
   * Sanitize parameters and send email.
   *
   * @param string $recipient
   * @param string $subject
   * @param string $message
   * @param array  $header_arr
   * @param array  $attachment
   *
   * @return bool
   */
  public static function mail($recipient, $subject, $message = '', $header_arr = array(), $attachment = array(), $save_uploads = 1 ) {
    $recipient = trim($recipient, ',');
    $recipient = explode(',', $recipient);
    $recipient = array_map('trim', $recipient);
    if ( empty($recipient) ) {
      return FALSE;
    }

    $subject = html_entity_decode($subject, ENT_QUOTES);
    $subject = stripslashes(strip_tags($subject));

    $headers = array();
    if ( isset($header_arr['from_name']) && $header_arr['from_name'] ) {
      $from_name = $header_arr['from_name'];
      $from_name = html_entity_decode($from_name, ENT_QUOTES);
      $from_name = stripslashes($from_name);
      self::$email_from_name = $from_name;
      add_filter('wp_mail_from_name', array('WDW_FM_Library', 'mail_from_name'));
    }

    if ( isset($header_arr['from']) && $header_arr['from'] ) {
      $from = $header_arr['from'];
      $from = trim($from);
      $from = trim($from, ',');
      self::$email_from = $from;
      if ( self::is_email( $from ) ) {
        add_filter('wp_mail_from', array('WDW_FM_Library', 'mail_from'));
      }
    }

    if ( isset($header_arr['content_type']) && $header_arr['content_type'] ) {
      self::$email_content_type = $header_arr['content_type'];
      add_filter('wp_mail_content_type', array('WDW_FM_Library', 'mail_content_type'));
    }

    if ( isset($header_arr['charset']) && $header_arr['charset'] ) {
      self::$email_charset = $header_arr['charset'];
      add_filter('wp_mail_charset', array('WDW_FM_Library', 'mail_charset'));
    }

    if ( isset($header_arr['reply_to']) && $header_arr['reply_to'] ) {
      $reply_to = $header_arr['reply_to'];
      $reply_to = trim($reply_to);
      $reply_to = trim($reply_to, ',');
      if ( self::is_email( $reply_to ) ) {
        $headers[] = "Reply-To: <" . $reply_to . ">";
      }
    }

    if ( isset($header_arr['cc']) && $header_arr['cc'] ) {
      $cc = $header_arr['cc'];
      $cc = trim($cc);
      $cc = trim($cc, ',');
      $headers[] = "Cc: " . $cc;
    }

    if ( isset($header_arr['bcc']) && $header_arr['bcc'] ) {
      $bcc = $header_arr['bcc'];
      $bcc = trim($bcc);
      $bcc = trim($bcc, ',');
      $headers[] = "Bcc: " . $bcc;
    }

    $temp = array();
    if ( !empty( $attachment ) ) {
      foreach ( $attachment as $att ) {
        if( $save_uploads ) {
          $temp[] = WDFMInstance(self::PLUGIN)->abspath . str_replace( array(home_url() . '/', site_url() .'/' ), array('', ''), $att );
        } else {
          $temp[] = $att;
        }
      }
      $attachment = $temp;
    }
    $sent = wp_mail($recipient, $subject, $message, $headers, $attachment);

    remove_filter('wp_mail_content_type', array('WDW_FM_Library', 'mail_content_type'));
    remove_filter('wp_mail_charset', array('WDW_FM_Library', 'mail_charset'));
    remove_filter('wp_mail_from', array('WDW_FM_Library', 'mail_from'));
    remove_filter('wp_mail_from_name', array('WDW_FM_Library', 'mail_from_name'));

    return $sent;
  }

  public static $email_content_type;
  public static $email_charset;
  public static $email_from;
  public static $email_from_name;
  public static function mail_content_type() {
    return self::$email_content_type;
  }

  public static function mail_charset() {
    return self::$email_charset;
  }

  public static function mail_from() {
    return self::$email_from;
  }
  public static function mail_from_name() {
    return self::$email_from_name;
  }

  /**
   * Get labels parameters.
   *
   * @param int $form_id
   * @param int $page_num
   * @param int $per_num
   *
   * @return array $labels_parameters
   */
  public static function get_labels_parameters($form_id, $page_num = 0, $per_num = 0) {
    global $wpdb;
    $labels = array();
    $labels_id = array();
    $form_labels = array();
    $sorted_labels_id = array();
    $label_names = array();
    $label_types = array();
    $sorted_label_types = array();
    $label_names_original = array();
    $labels_parameters = array();
    $join_query = array();
    $join_where = array();
    $join_verified = array();
    $rows_ord = array();
    $join = '';
    $query = $wpdb->prepare("SELECT `group_id`,`element_value` FROM " . $wpdb->prefix . "formmaker_submits  WHERE `form_id`='%d' and `element_label` = 'verifyinfo' ", $form_id);
    $ver_emails_data = $wpdb->get_results($query);
    $ver_emails_array = array();
    if ( $ver_emails_data ) {
      foreach ( $ver_emails_data as $ver_email ) {
        $elem_label = str_replace('verified**', '', $ver_email->element_value);
        $query = $wpdb->prepare("SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits  WHERE `form_id`='%d' AND `group_id` = '%d' AND `element_label` = '%s' ", $form_id, (int) $ver_email->group_id, $elem_label);
        if ( !isset($ver_emails_array[$elem_label]) ) {
          $ver_emails_array[$elem_label] = array();
        }
        if ( !in_array($wpdb->get_var($query), $ver_emails_array[$elem_label]) ) {
          $ver_emails_array[$elem_label][] = $wpdb->get_var($query);
        }
      }
    }
    for ( $i = 0; $i < 9; $i++ ) {
      array_push($labels_parameters, NULL);
    }
    $sorted_label_names = array();
    $sorted_label_names_original = array();
    $where_labels = array();
    $where2 = array();
    $order_by = self::get( 'order_by', 'group_id' );
    if( $order_by == '' ) {
      $order_by = 'group_id';
    }
    $asc_or_desc = self::get( 'asc_or_desc' ) == 'asc' ? 'asc' : 'desc';

    $lists['hide_label_list'] = self::get( 'hide_label_list' );
    $lists['startdate'] = self::get( 'startdate' );
    $lists['enddate'] = self::get( 'enddate' );
    $lists['ip_search'] = self::get( 'ip_search' );
    $lists['username_search'] = self::get( 'username_search' );
    $lists['useremail_search'] = self::get( 'useremail_search' );
    $lists['id_search'] = self::get( 'id_search' );
    if ( $lists['ip_search'] ) {
      $where[] = $wpdb->prepare('ip LIKE "%%' . '%s' . '%%"', $lists['ip_search']);
    }
    if ( $lists['startdate'] != '' ) {
      $where[] = $wpdb->prepare(" `date`>='" . '%s' . " 00:00:00' ",$lists['startdate']);
    }
    if ( $lists['enddate'] != '' ) {
      $where[] = $wpdb->prepare(" `date`<='" . '%s' . " 23:59:59' ", $lists['enddate']);
    }
    if ( $lists['username_search'] ) {
      $where[] = $wpdb->prepare('user_id_wd IN (SELECT ID FROM ' . $wpdb->prefix . 'users WHERE display_name LIKE "%%' . '%s' . '%%")', $lists['username_search']);
    }
    if ( $lists['useremail_search'] ) {
      $where[] = $wpdb->prepare('user_id_wd IN (SELECT ID FROM ' . $wpdb->prefix . 'users WHERE user_email LIKE "%%' . '%s' . '%%")', $lists['useremail_search']);
    }
    if ( $lists['id_search'] ) {
      $where[] = $wpdb->prepare('group_id =' . '%d', $lists['id_search']);
    }
    $where[] = $wpdb->prepare('form_id=' . '%d' . '', $form_id);
    $where = (count($where) ? ' ' . implode(' AND ', $where) : '');
    if ( $order_by == 'group_id' or $order_by == 'date' or $order_by == 'ip' ) {
      $orderby = ' ORDER BY ' . $order_by . ' ' . $asc_or_desc . '';
    }
    elseif ( $order_by == 'display_name' or $order_by == 'user_email' ) {
      $orderby = ' ORDER BY (SELECT ' . $order_by . ' FROM ' . $wpdb->prefix . 'users WHERE ID=user_id_wd) ' . $asc_or_desc . '';
    }
    else {
      $orderby = "";
    }
    if ( $form_id ) {
      for ( $i = 0; $i < 9; $i++ ) {
        array_pop($labels_parameters);
      }
      $query = "SELECT distinct element_label FROM " . $wpdb->prefix . "formmaker_submits WHERE " . $where;
      $results = $wpdb->get_results($query);
      for ( $i = 0; $i < count($results); $i++ ) {
        array_push($labels, $results[$i]->element_label);
      }
      $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $form_id));
      $form = WDW_FM_Library::convert_json_options_to_old( $form, 'form_options');
      if ( !empty($form->label_order) && strpos($form->label_order, 'type_paypal_') ) {
        $form->label_order = $form->label_order . "item_total#**id**#Item Total#**label**#type_paypal_payment_total#****#total#**id**#Total#**label**#type_paypal_payment_total#****#0#**id**#Payment Status#**label**#type_paypal_payment_status#****#";
      }
      if ( !empty($form->label_order) && strpos($form->label_order, 'type_submitter_mail') ) {
        $form->label_order = $form->label_order . 'user_email#**id**#user_email#**label**#type_submitter_mail#****';
        $form->label_order = $form->label_order . 'verifyInfo#**id**#verify_info#**label**#type_user_email_verify#****#';
              }
      if ( !empty($form->label_order)) {
        $form_labels = explode('#****#', $form->label_order);
      }
      $form_labels = array_slice($form_labels, 0, count($form_labels) - 1);
      foreach ( $form_labels as $key => $form_label ) {
        $label_id = explode('#**id**#', $form_label);
        array_push($labels_id, $label_id[0]);
        $label_name_type = explode('#**label**#', $label_id[1]);
        array_push($label_names_original, $label_name_type[0]);
        $ptn = "/[^a-zA-Z0-9_]/";
        $rpltxt = "";
        $label_name = preg_replace($ptn, $rpltxt, $label_name_type[0]);
        array_push($label_names, $label_name);
        array_push($label_types, $label_name_type[1]);
      }
      foreach ( $labels_id as $key => $label_id ) {
        if ( in_array($label_id, $labels) ) {
          if ( !in_array($label_id, $sorted_labels_id) ) {
            array_push($sorted_labels_id, $label_id);
          }
          array_push($sorted_label_names, $label_names[$key]);
          array_push($sorted_label_types, $label_types[$key]);
          array_push($sorted_label_names_original, $label_names_original[$key]);
          $search_temp = self::get( $form_id . '_' . $label_id . '_search' );
          $search_temp = strtolower($search_temp);
          $lists[$form_id . '_' . $label_id . '_search'] = $search_temp;
          if ( $search_temp ) {
            $join_query[] = 'search';
            $join_where[] = array( 'label' => $label_id, 'search' => $search_temp );
          }
          $search_verified = self::get( $form_id . '_' . $label_id . '_search_verified' );
          if ( $search_verified != '' ) {
            $lists[$form_id . '_' . $label_id . '_search_verified'] = $search_verified;
          }
          if ( $search_verified && isset($ver_emails_array[$label_id]) ) {
            $join_query[] = 'search';
            $join_where[] = NULL;
            $join_verified[] = array(
              'label' => $label_id,
              'ver_search' => implode('|', $ver_emails_array[$label_id]),
            );
          }
        }
      }
      if ( strpos($order_by, "_field") ) {
        if ( in_array(str_replace("_field", "", $order_by), $labels) ) {
          $join_query[] = 'sort';
          $join_where[] = array( 'label' => str_replace("_field", "", $order_by) );
        }
      }
      $cols = 'group_id';
      if ( $order_by == 'date' or $order_by == 'ip' ) {
        $cols = 'group_id, date, ip';
      }
      $ver_where = '';
      if ( !empty($join_verified) ) {
        foreach ( $join_verified as $key_ver => $join_ver ) {
          $ver_where .= '(element_label="' . $join_ver['label'] . '" AND element_value REGEXP "' . $join_ver['ver_search'] . '" ) AND';
        }
      }
      switch ( count($join_query) ) {
        case 0:
          $join = 'SELECT distinct group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where;
          break;
        case 1:
          if ( $join_query[0] == 'sort' ) {
            $join = $wpdb->prepare('SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . '%s' . '" ', $join_where[0]['label']);
            $join_count = $wpdb->prepare('SELECT count(group_id) FROM ' . $wpdb->prefix . 'formmaker_submits WHERE form_id="' . '%d' . '" AND element_label="' . '%s' . '" ', $form_id, $join_where[0]['label']);
            $orderby = $wpdb->prepare(' ORDER BY `element_value` ' . '%s', $asc_or_desc);
          }
          else {
            if ( isset($join_where[0]['search']) ) {
              $join = $wpdb->prepare('SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE element_label="' . '%s' . '" AND  (element_value LIKE "%%' . '%s' . '%%" OR element_value LIKE "%%' . '%s' . '%%")  AND ' . $where, $join_where[0]['label'], $join_where[0]['search'], str_replace(' ', '@@@', $join_where[0]['search']));
            }
            else {
              $join = 'SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE  ' . $ver_where . $where;
            }
          }
          break;
        default:
          if ( !empty($join_verified) ) {
            if ( isset($join_where[0]['search']) ) {
              $join = $wpdb->prepare('SELECT t.group_id from (SELECT t1.group_id from (SELECT ' . $cols . ' FROM ' . $wpdb->prefix . 'formmaker_submits WHERE (element_label="' . '%s' . '" AND (element_value LIKE "%%' . '%s' . '%%" OR element_value LIKE "%%' .'%s' . '%%")) AND ' . $where . ' ) as t1 JOIN (SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE  ' . $ver_where . $where . ') as t2 ON t1.group_id = t2.group_id) as t ',$join_where[0]['label'], $join_where[0]['search'], str_replace(' ', '@@@', $join_where[0]['search']));
            }
            else {
              $join = 'SELECT t.group_id FROM (SELECT ' . $cols . '  FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $ver_where . $where . ') as t ';
            }
          }
          else {
            $join = $wpdb->prepare('SELECT t.group_id FROM (SELECT ' . $cols . '  FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . '%s' . '" AND  (element_value LIKE "%%' . '%s' . '%%" OR element_value LIKE "%%' . '%s' . '%%" )) as t ', $join_where[0]['label'], $join_where[0]['search'], str_replace(' ', '@@@', $join_where[0]['search']));
          }
          for ( $key = 1; $key < count($join_query); $key++ ) {
            if ( $join_query[$key] == 'sort' ) {
              if ( isset($join_where[$key]) ) {
                $join .= $wpdb->prepare('LEFT JOIN (SELECT group_id as group_id' . '%s' . ', element_value   FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . '%s' . '") as t' . '%s' . ' ON t' . '%s' . '.group_id' . '%s' . '=t.group_id ', $key, $join_where[$key]['label'], $key, $key, $key);
                $orderby = $wpdb->prepare(' ORDER BY t' . '%s' . '.`element_value` ' . '%s' . '', $key, $asc_or_desc);
              }
            }
            else {
              if ( isset($join_where[$key]) ) {
                $join .= $wpdb->prepare('INNER JOIN (SELECT group_id as group_id' . '%s' . ' FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . '%s' . '" AND  (element_value LIKE "%%' . $join_where[$key]['search'] . '%%" OR element_value LIKE "%%' . '%s' . '%%")) as t' . '%s' . ' ON t' . '%s' . '.group_id' . '%s' . '=t.group_id ', $key, $join_where[$key]['label'], str_replace(' ', '@@@', $join_where[$key]['search']), $key, $key, $key);
              }
            }
          }
          break;
      }
      $pos = strpos($join, 'SELECT t.group_id');
      if ( $pos === FALSE ) {
        $query = str_replace(array(
                               'SELECT group_id',
                               'SELECT distinct group_id',
                             ), array( 'SELECT count(distinct group_id)', 'SELECT count(distinct group_id)' ), $join);
      }
      else {
        $query = str_replace('SELECT t.group_id', 'SELECT count(t.group_id)', $join);
      }
      $total = $wpdb->get_var($query);
      $query_sub_count = "SELECT count(distinct group_id) from " . $wpdb->prefix . "formmaker_submits";
      $sub_count = (int) $wpdb->get_var($query_sub_count);
      $query = $join . ' ' . $orderby . ' LIMIT ' . $page_num . ', '.$per_num;
      $results = $wpdb->get_results($query);
      for ( $i = 0; $i < count($results); $i++ ) {
        array_push($rows_ord, $results[$i]->group_id);
      }
      $query1 = $join . ' ' . $orderby;
      $searched_group_ids = $wpdb->get_results($query1);
      $searched_ids = array();
      for ( $i = 0; $i < count($searched_group_ids); $i++ ) {
        array_push($searched_ids, $searched_group_ids[$i]->group_id);
      }
      $where2 = array();
      $where2[] = "group_id='0'";
      foreach ( $rows_ord as $rows_ordd ) {
        $where2[] = $wpdb->prepare( "group_id='%s'", $rows_ordd );
      }
      $where2 = (count($where2) ? ' WHERE ' . implode(' OR ', $where2) . '' : '');
      $query = 'SELECT * FROM ' . $wpdb->prefix . 'formmaker_submits ' . $where2;
      $rows = $wpdb->get_results($query);
      $group_ids = $rows_ord;
      $lists['total'] = $total;
      $lists['limit'] = $per_num;
      $where_choices = $where;
      array_push($labels_parameters, $sorted_labels_id);
      array_push($labels_parameters, $sorted_label_types);
      array_push($labels_parameters, $lists);
      array_push($labels_parameters, $sorted_label_names);
      array_push($labels_parameters, $sorted_label_names_original);
      array_push($labels_parameters, $rows);
      array_push($labels_parameters, $group_ids);
      array_push($labels_parameters, $where_choices);
      array_push($labels_parameters, $searched_ids);
    }

    return $labels_parameters;
  }

  /**
   * Encode a given string with the QUOTED_PRINTABLE mechanism and wrap the lines.
   *
   * @param  string $str
   * @param  int    $lineLength Line length; defaults to {@link LINELENGTH}
   * @param  string $lineEnd    Line end; defaults to {@link LINEEND}
   *
   * @return string
   */
  public static function encodeQuotedPrintable( $str, $lineLength = self::LINELENGTH, $lineEnd = self::LINEEND ) {
    $out = '';
    $str = self::_encodeQuotedPrintable($str);
    // Split encoded text into separate lines
    while ( strlen($str) > 0 ) {
      $ptr = strlen($str);
      if ( $ptr > $lineLength ) {
        $ptr = $lineLength;
      }
      // Ensure we are not splitting across an encoded character
      $pos = strrpos(substr($str, 0, $ptr), '=');
      if ( $pos !== FALSE && $pos >= $ptr - 2 ) {
        $ptr = $pos;
      }
      // Check if there is a space at the end of the line and rewind
      if ( $ptr > 0 && $str[$ptr - 1] == ' ' ) {
        --$ptr;
      }
      // Add string and continue
      $out .= substr($str, 0, $ptr) . '=' . $lineEnd;
      $str = substr($str, $ptr);
    }
    $out = rtrim($out, $lineEnd);
    $out = rtrim($out, '=');

    return $out;
  }

  /**
   * Converts a string into quoted printable format.
   *
   * @param  string $str
   *
   * @return string
   */
  private static function _encodeQuotedPrintable( $str ) {
    $str = str_replace('=', '=3D', $str);
    $str = str_replace(self::$qpKeys, self::$qpReplaceValues, $str);
    $str = rtrim($str);

    return $str;
  }

  /**
   * decode a quoted printable encoded string
   * The charset of the returned string depends on your iconv settings.
   *
   * @param  string $string Encoded string
   *
   * @return string         Decoded string
   */
  public static function decodeQuotedPrintable( $string ) {
    return quoted_printable_decode($string);
  }

  /**
   * Replace currencycode.
   *
   * @param  string $key
   *
   * @return string
   */
  public static function replace_currency_code( $currency ) {
    $currency_code = array('USD', 'EUR', 'GBP', 'JPY', 'CAD', 'MXN', 'HKD', 'HUF', 'NOK', 'NZD', 'SGD', 'SEK', 'PLN', 'AUD', 'DKK', 'CHF', 'CZK', 'ILS', 'BRL', 'TWD', 'MYR', 'PHP', 'THB', 'HRK', 'PKR', 'KES', 'UGX', 'TZS', 'RWF', 'NGN', 'ZAR');
    $currency_sign = array('$', '&#8364;', '&#163;', '&#165;', 'C$', 'Mex$', 'HK$', 'Ft', 'kr', 'NZ$', 'S$', 'kr', 'zl', 'A$', 'kr', 'CHF', 'Kc', '&#8362;', 'R$', 'NT$', 'RM', '&#8369;', '&#xe3f;', 'kn', 'Rs', 'KSh', 'USh', 'TSh', 'FRw', '&#8358;', 'R');
    if (isset($currency_code[$currency])) {
      $currency = $currency_sign[array_search($currency, $currency_code)];
    }
    return $currency;
  }

  /**
   * Create Email options placeholders.
   *
   * @param array $labels
   * @return array $data
   */
	public static function create_email_options_placeholders( $labels = array() ) {
		$data = array();
		$form_inputs = array();
		$continue_types = self::get_fields_continue_types();
		$inputs = array(
			array('value' => 'all', 'title' => __('All fields list', WDFMInstance(self::PLUGIN)->prefix)),
		);
		if ( !empty($labels) ) {
			foreach($labels as $key => $label) {
				if ( empty($key) || in_array($label['type'], $continue_types) ) {
				  continue;
				}
				if ( $label['type'] == "type_file_upload" ) {
					$key = $key . '(link)';
				}
				$form_inputs[] = array('value' => $key, 'title' => $label['name'] );
			}
		}
		$data[__('Form fields', WDFMInstance(self::PLUGIN)->prefix)] = array_merge($inputs, $form_inputs);
		$data[__('Misc', WDFMInstance(self::PLUGIN)->prefix)] = array(
			array('value' => 'formtitle', 'title' => __('Form Title', WDFMInstance(self::PLUGIN)->prefix)),
      array('value' => 'ip', 'title' => __('IP', WDFMInstance(self::PLUGIN)->prefix)),
      array('value' => 'subid', 'title' => __('Submission ID', WDFMInstance(self::PLUGIN)->prefix)),
      array('value' => 'subdate', 'title' => __('Submission Date', WDFMInstance(self::PLUGIN)->prefix)),
			array('value' => 'adminemail', 'title' => __('Admin Email', WDFMInstance(self::PLUGIN)->prefix)),
			array('value' => 'useremail', 'title' => __('User Email', WDFMInstance(self::PLUGIN)->prefix)),
			array('value' => 'username', 'title' => __('User Name', WDFMInstance(self::PLUGIN)->prefix)),
			array('value' => 'pageurl', 'title' => __('Page Url', WDFMInstance(self::PLUGIN)->prefix)),
			array('value' => 'verificationlink', 'title' => __('Verification Link', WDFMInstance(self::PLUGIN)->prefix)),
		);
    $data[__('Misc', WDFMInstance(self::PLUGIN)->prefix)] = apply_filters('fm_placeholders_misc', $data[__('Misc', WDFMInstance(self::PLUGIN)->prefix)]);
		return $data;
	}

  /**
   * Get shortcode data.
   *
   * @return json $data
   */
  public static function get_shortcode_data() {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT `id`, `title` as name, `published`  FROM `" . $wpdb->prefix . "formmaker`" . (!WDFMInstance(self::PLUGIN)->is_free ? '' : ' WHERE id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option('contact_form_forms', '') != '' ? get_option('contact_form_forms') : 0) . ') ORDER BY `title`'));
    foreach ($rows as $row) {
      if( $row->published == 0) {
        $row->name .= ' - ' . __('Unpublished', WDFMInstance(self::PLUGIN)->prefix);
      }
    }
    $data = array();
    $data['shortcode_prefix'] = WDFMInstance(self::PLUGIN)->is_free == 2 ? 'wd_contact_form' : 'Form';
    $data['inputs'][] = array(
      'type' => 'select',
      'id' => WDFMInstance(self::PLUGIN)->prefix . '_id',
      'name' => WDFMInstance(self::PLUGIN)->prefix . '_id',
      'shortcode_attibute_name' => 'id',
      'options'  => $rows,
    );
    return json_encode($data);
  }

  /**
   * Session start if not started.
  */
  public static function start_session() {
    if (session_id() == '' || (function_exists('session_status') && (session_status() == PHP_SESSION_NONE))) {
      @session_start();
    }
  }

  public static function get_user_submission_ids( $email_address ) {
    global $wpdb;
    $user = get_user_by( 'email', $email_address );
    $query = $wpdb->prepare('SELECT DISTINCT submits.`form_id`, form.`title`, submits.`group_id` FROM ' . $wpdb->prefix . 'formmaker_submits as submits INNER JOIN ' . $wpdb->prefix . 'formmaker as form ON submits.form_id=form.id WHERE submits.`element_value`=\'' . '%s' . '\'' . ($user ? ' OR submits.`user_id_wd`=' . '%d' : '') . (!WDFMInstance(self::PLUGIN)->is_free ? '' : ' AND submits.form_id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option( 'contact_form_forms', '' ) != '' ? get_option( 'contact_form_forms' ) : 0) . ')') . ' ORDER BY submits.`form_id`',$email_address, $user->ID);
    $results = $wpdb->get_results($query);
    return $results;
  }

  public static function get_submission_by_id( $group_id ) {
    global $wpdb;
    $query = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_submits WHERE `group_id`=' . '%d', $group_id);
    $results = $wpdb->get_results($query);
    return $results;
  }

  public static function delete_user_submissions( $email_address ) {
    global $wpdb;
    $submission_ids = self::get_user_submission_ids( $email_address );
    $ids = array();
    foreach ($submission_ids as $id) {
      $ids[] = $id->group_id;
    }
    $ids = implode(',', $ids);
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_sessions WHERE group_id IN (' . '%s' . ')', $ids);
    $deleted = $wpdb->query($query);
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id IN (' . '%s' . ')', $ids);
    $deleted = $deleted || $wpdb->query($query);
    return $deleted;
  }

  public static function privacy_personal_data_export ( $email_address, $page = 1 ) {
    $export_items = array();

    $submission_ids = self::get_user_submission_ids( $email_address );
    if ($submission_ids) {
      require_once WDFMInstance(self::PLUGIN)->plugin_dir . '/admin/models/model.php';
      require_once WDFMInstance(self::PLUGIN)->plugin_dir . '/admin/models/Submissions_fm.php';
      $model_class = self::PLUGIN == 2 ? 'FMModelSubmissions_fmc' : 'FMModelSubmissions_fm';
      $model = new $model_class();
      $form_id = 0;
      $label_parameters = array();
      foreach ($submission_ids as $submission_id) {
        if ($submission_id->form_id != $form_id) {
          $form_id = $submission_id->form_id;
          $label_parameters = $model->get_labels_parameters($form_id);
        }
        $data = array();
        $submission = self::get_submission_by_id($submission_id->group_id);
        if ( $submission ) {
          $data[] = array(
            'name' => __( 'Form Title', WDFMInstance(self::PLUGIN)->prefix ),
            'value' => $submission_id->title
          );
          // Selecting this each time as any user could submit a data containing $email_address.
          $user = get_user_by( 'id', $submission[0]->user_id_wd );
          if ($user) {
            $data[] = array(
              'name' => __( 'Submitter Name', WDFMInstance( self::PLUGIN )->prefix ),
              'value' => $user->display_name
            );
            $data[] = array(
              'name' => __( 'Submitter Email', WDFMInstance( self::PLUGIN )->prefix ),
              'value' => $user->user_email
            );
          }
          $data[] = array(
            'name' => __( 'Submitter IP', WDFMInstance(self::PLUGIN)->prefix ),
            'value' => $submission[0]->ip
          );
          $data[] = array(
            'name' => __( 'Submission Date', WDFMInstance(self::PLUGIN)->prefix ),
            'value' => get_date_from_gmt( $submission[0]->date )
          );
          foreach ( $submission as $row ) {
            $element_label = $label_parameters[3][array_search($row->element_label, $label_parameters[0])];
            $data[] = array(
              'name' => $element_label,
              'value' => $row->element_value
            );
          }
        }

        $item_id = WDFMInstance(self::PLUGIN)->slug . '-submission-' . $submission_id->group_id;
        // This is nor submission group_id.
        $group_id = WDFMInstance(self::PLUGIN)->slug;
        $group_label = WDFMInstance(self::PLUGIN)->nicename . __( ' Submissions', WDFMInstance(self::PLUGIN)->prefix );
        $export_items[] = array(
          'group_id' => $group_id,
          'group_label' => $group_label,
          'item_id' => $item_id,
          'data' => $data,
        );
      }
    }

    return array(
      'data' => $export_items,
      'done' => true,
    );
  }

  public static function privacy_personal_data_erase( $email_address, $page = 1 ) {
    $items_removed = self::delete_user_submissions( $email_address );

    return array(
      'items_removed' => $items_removed,
      'items_retained' => false,
      'messages' => array(sprintf(__('All personal data and submissions of this user found in %s plugin were removed.', WDFMInstance(self::PLUGIN)->prefix), WDFMInstance(self::PLUGIN)->nicename)),
      'done' => true,
    );
  }

  /**
   * Array remove keys.
   *
   * @param array $array
   * @param array $keys
   * @return array
   */
  public static function array_remove_keys( $array = array(), $keys = array() ) {
	if ( !empty($array) ) {
		foreach ( $array as $key => $val ) {
			if ( isset($keys[$key]) ) {
				unset($array[$key]);
			}
		}
	}
    return $array;
  }

  /**
   * Check if is preview of Elementor builder.
   *
   * @return bool
   */
  public static function elementor_is_active() {
    if ( in_array(WDW_FM_Library(self::PLUGIN)->get('action', ''), array(
        'elementor',
        'elementor_ajax',
      )) || WDW_FM_Library(self::PLUGIN)->get('elementor-preview', '') ) {

      return TRUE;
    }

    return FALSE;
  }

  // TODO. This function should be replaced with WP functionality in another version. At the moment it is not.
  /**
   *  Get privacy_policy_url
   *
   * @return string $url
   */
  public static function get_privacy_policy_url() {
    $permalink = '';
    $title = __('Privacy Policy', WDFMInstance(self::PLUGIN)->prefix);
    $post_id = get_option( 'wp_page_for_privacy_policy' );
    if ( $post_id ) {
      $post = get_post( $post_id, OBJECT );
      if ( !empty($post) && $post->post_status == 'publish' ) {
        $permalink = get_permalink( $post_id );
        $title = $post->post_title;
      }
    }
    return array('url' => $permalink, 'title' => $title);
  }

  public static function unique_number() {
    $use_random_number = ( WDW_FM_Library(self::PLUGIN)->elementor_is_active() ) ? TRUE : FALSE;
    if ($use_random_number) {
      return mt_rand();
    }
    else {
      global $fm;
      $unique = $fm;
      $fm++;
      return $unique;
    }
  }

  /**
   * Get forms.
   *
   * @return array
   */
  public static function get_forms() {
    global $wpdb;
    $query = "SELECT `id`, `title`, `published` FROM `" . $wpdb->prefix . "formmaker`" . (!WDFMInstance(self::PLUGIN)->is_free ? '' : ' WHERE id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option('contact_form_forms', '') != '' ? get_option('contact_form_forms') : 0) . ') ORDER BY `title`');

    $rows = $wpdb->get_results($query);

    $forms = array();
    $forms[0] = __('Select a form', WDFMInstance(self::PLUGIN)->prefix);
    foreach ( $rows as $row ) {
      if( $row->published == 0) {
        $row->title .= ' - ' . __('Unpublished', WDFMInstance(self::PLUGIN)->prefix);
      }
      $forms[$row->id] = $row->title;
    }

    return $forms;
  }

  public static function is_email( $email ) {
    if ( $email != '' ) {
      if ( !preg_match("/^[a-zA-Z'\x{0400}-\x{04FF}0-9.+_-]+@[a-zA-Z\x{0400}-\x{04FF}0-9.-]+\.[a-zA-Z\x{0400}-\x{04FF}]{2,61}$/u", $email) ) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * Get Form fields continue types.
   * @return array
   */
  public static function get_fields_continue_types() {
    $types = array('type_submit_reset', 'type_editor', 'type_map', 'type_mark_map', 'type_captcha', 'type_recaptcha', 'type_button', 'type_send_copy');

    return $types;
  }
  public static function set_exclude_placeholder( $params = array() ) {
    $str = '';
    if ( !empty($params) ) {
      $str = 'data-exclude-placeholder=\'' . json_encode($params) . '\'';
    }
    return $str;
  }
  /**
   * Get custom fields
   * @return array
   */
  public static function get_custom_fields() {
    $userid = '';
    $username = '';
    $useremail = '';
    $adminemail = get_option( 'admin_email' );
    $current_user = wp_get_current_user();
    if ( $current_user->ID != 0 ) {
      $userid = $current_user->ID;
      $username = $current_user->display_name;
      $useremail = $current_user->user_email;
    }
    $custom_fields = array(
      "ip" => $_SERVER['REMOTE_ADDR'],
      "userid" => $userid,
      'adminemail' => $adminemail,
      "useremail" => $useremail,
      "username" => $username
    );
    return $custom_fields;
  }

  /**
   * Update file read option.
   * @param int $val
   */
  public static function update_file_read_option( $val = 0 )  {
    $option_key = (WDFMInstance(self::PLUGIN)->is_free == 2 ? 'fmc_settings' : 'fm_settings');
    WDFMInstance(self::PLUGIN)->fm_settings['fm_file_read'] = $val;
    update_option( $option_key, WDFMInstance(self::PLUGIN)->fm_settings );
  }


  /**
   * Replace wp_formaker table row array data values with form_option field json data values
   *
   * @param object        $row data from table row
   * @param string|array  $columns json option column | columns array
   * @param string        $key_row a separate output field
   *
   * @return object
   */
  public static function convert_json_options_to_old( $row, $columns, $key_row = '' ) {
    if ( gettype($columns) === 'array' ) {
      foreach ( $columns as $column_key => $column_value ) {
        if ( isset($row->$column_value) ) {
          if ( !self::fm_isJSON($row->$column_value) || empty($row) ) {
            continue;
          }
          $json_data = json_decode($row->$column_value, 1);
          foreach ( $json_data as $key => $value ) {
            $row->$key = $value;
          }
        }
      }
    }
    else {
      if ( isset($row->$columns) ) {
        if ( !self::fm_isJSON($row->$columns) || empty($row) ) {
          return $row;
        }
        $json_data = json_decode($row->$columns, 1);
        foreach ( $json_data as $key => $value ) {
          $row->$key = $value;
        }
        if ( !empty($key_row) && isset($row->$key_row) ) {
          return $row->$key_row;
        }
        return $row;
      }
    }

    if ( !empty($key_row) && isset($row->$key_row) ) {
      return $row->$key_row;
    }

    return $row;
  }

  /**
   * Getting form_options json data for current form
   *
   * @param string $table   current table
   * @param int    $form_id current form id
   *
   * @return array decoded json data
   */
  public static function get_form_options_json( $table, $form_id ) {
    global $wpdb;
    $table_full_name = $wpdb->prefix . $table;
    $prepared = $wpdb->prepare("SELECT form_options FROM " . $table_full_name . ' WHERE id=%d' ,$form_id);
    $row = $wpdb->get_var($prepared);
    if( $row && !empty($row) && self::fm_isJSON($row) ) {

      return json_decode( $row, 1 );
    }

    return array();
  }

  /**
   * Update json column data in db
   *
   * @param string $table         table that is being updated
   * @param string $column        name of column with json that is being updated
   * @param array  $data          new json data for merge with old
   * @param array  $where_array   condition to update - ['column' => 'value']
   *
   * @return int|bool number of affected rows or FALSE on error
   */
  public static function update_json_column( $table, $column, $data, $where_array) {
    global $wpdb;
    $full_table = $wpdb->prefix . $table;

    $where_str = self::parse_associate_array_to_SQLWHERE($where_array);

    $get_query = 'SELECT ' . $column . ' FROM ' . $full_table . $where_str;
    $old_data = json_decode($wpdb->get_var($get_query), 1);
    if(gettype($old_data) === 'array') {
      $updated_data = json_encode(array_merge($old_data, $data));
    }
    else {
      $updated_data = json_encode($data);
    }
    $updated_array = array($column => $updated_data);

    return $wpdb->update($full_table, $updated_array, $where_array);
  }

  /**
   * Update data and row's json column data in db
   *
   * @param string $table table name without prefix
   * @param array  $data  data array - ['column' => 'data']
   * @param string $json_column name of column with json that is being updated
   * @param array  $json_data new json data for merge with old
   * @param array  $where_array condition to update - ['column' => 'value']
   * @param string $wpdb_function which db function should be called?
   *
   * @return int|bool number of affected rows or FALSE on error
   */
  public static function update_columns_and_json_column( $table, $data, $json_column, $json_data, $where_array, $wpdb_function = 'update' ) {
    global $wpdb;
    $full_table = $wpdb->prefix . $table;

    $where_str = self::parse_associate_array_to_SQLWHERE($where_array);

    $get_query = 'SELECT ' . $json_column . ' FROM ' . $full_table . $where_str;
    $json_old_data = json_decode($wpdb->get_var($get_query), 1);
    if(gettype($json_old_data) === 'array') {
      $json_updated_data = json_encode(array_merge($json_old_data, $json_data));
    } else {
      $json_updated_data = json_encode($json_data);
    }
    $json_updated_array = array($json_column => $json_updated_data);

    $updated_data = array_merge($data, $json_updated_array);

    if ($wpdb_function === 'update') {
      return $wpdb->$wpdb_function($full_table, $updated_data, $where_array);
    } else {
      return $wpdb->$wpdb_function($full_table, $updated_data);
    }
  }

  /**
   * Checking if string is json format
   *
   * @param string $string string which need to check if it is json
   *
   * @return bool true if string is json / false if string is not json
   */
  public static function fm_isJSON( $string ) {
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
  }

  /**
   * Update data and json column in db
   *
   * @param array  $array
   *
   * @return string WHERE condition for SQL query
   */
  public static function parse_associate_array_to_SQLWHERE($array) {
    $where_str = '';
    $counter = 0;
    foreach ($array as $key => $value) {
      $where_str = $key . "='" . $value . "'";
      $counter++;
      if (count($array) !== $counter ) {
        $where_str .= ' AND ';
      }
    }
    return ' WHERE ' . $where_str;
  }

  /**
   * Get single var from db.
   *
   * @param $form_id
   * @param $col
   *
   * @return mixed|string|null
   */
  public static function get_single_var_from_db( $form_id, $col = '' ) {
    global $wpdb;
    $get_form_options = self::get_form_options_json("formmaker", $form_id);
    if ( !empty($get_form_options) && isset($get_form_options[$col]) ) {
      $data = $get_form_options[$col];
    }
    else {
      $data = $wpdb->get_var($wpdb->prepare("SELECT `" . $col . "` FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $form_id));
    }

    return $data;
  }
  
	/**
	 * Update file read option.
	 *
	 * @param string $type
	 * @param string $global_index
	 * @param string $index
	 * @param string $value
	 */
	public static function is_select_condition_HTML( $type, $global_index, $index, $value = '==' ) {
    // @TODO. Returns the condition field values depending on the type.
		$available_conditions = self::supported_is_select_conditions_of_type( $type );
		$html = "<select id='is_select" . $global_index . "_" . $index . "' class='fm_condition_is_select'>";
    // used for type_slider.
		if ( $available_conditions[0] === TRUE ) {
			$html .= "<option value='=='" . ( $value === "==" ? "selected='selected'" : '' ) . ">is</option>";
			$html .= "<option value='!='" . ( $value === "!=" ? "selected='selected'" : '' ) . ">is not</option>";
		}
    // used for type_slider.
		if ( $available_conditions[1] === TRUE ) {
			$html .= "<option value='%'" . ( $value === "%" ? "selected='selected'" : '' ) . ">like</option>";
			$html .= "<option value='!%'" . ( $value === "!%" ? "selected='selected'" : '' ) . ">not like</option>";
		}
    // used for type_file_upload.
		if ( $available_conditions[2] === TRUE ) {
			$html .= "<option value='='" . ( $value === "=" ? "selected='selected'" : '' ) . ">empty</option>";
			$html .= "<option value='!'" . ( $value === "!" ? "selected='selected'" : '' ) . ">not empty</option>";
		}
		// used for type_send_copy.
		if ( $available_conditions[3] === TRUE ) {
			$html .= "<option value='checked'" . ( $value === "checked" ? "selected='selected'" : '' ) . ">Checked</option>";
			$html .= "<option value='unchecked'" . ( $value === "unchecked" ? "selected='selected'" : '' ) . ">Unchecked</option>";
		}
    // used for type_range.
    if ( $available_conditions[4] === TRUE ) {
      $html .= "<option value='in_range'" . ( $value === "in_range" ? "selected='selected'" : '' ) . ">In Range</option>";
      $html .= "<option value='out_range'" . ( $value === "out_range" ? "selected='selected'" : '' ) . ">Out of range</option>";
      $html .= "<option value='empty'" . ( $value === "empty" ? "selected='selected'" : '' ) . ">Empty</option>";
    }
		$html .= "</select>";
		echo $html;
	}

	/**
	 * Update file read option.
	 *
	 * @param string $type
	 */
	public static function supported_is_select_conditions_of_type( $type ) {
    /*
      @TODO.
		  The true key of the array defines the operation of that type.
		  This means that if a new type is added, the false key must be added to all types already.
      To make this new operation work only for that type.
    */
		switch ( $type ) {
      case 'type_range':
        $available_conditions = [ FALSE, FALSE, FALSE, FALSE, TRUE ];
        break;
      case 'type_send_copy':
        $available_conditions = [ FALSE, FALSE, FALSE, TRUE, FALSE ];
        break;
      case 'type_file_upload':
        $available_conditions = array( FALSE, FALSE, TRUE, FALSE, FALSE );
        break;
      case 'type_slider':
        $available_conditions = array( TRUE, TRUE, FALSE, FALSE, FALSE );
        break;
			default:
				$available_conditions = array( TRUE, TRUE, TRUE, FALSE, FALSE );
		}

		return $available_conditions;
	}

  public static function notice_dismiss_button() {
    ?>
    <script>
      jQuery(document).ready(function () {
        var data = {
          action: 'dismiss_db_full_privileged_notice',
          db_full_privileged_notice: 0
        };
        jQuery('#fm-notice-dismiss-button').click(function () {
          jQuery.get(ajaxurl, data, function () {
            jQuery('#fm-notice').remove()
          });
        })
      });
    </script>
    <?php
  }

  /**
   * Get submitted form's first email field value.
   *
   * @param $form_id form's id
   * @param $form_fields form's form_fields column
   */
  public static function get_form_first_email_field($form_id, $form_fields) {
    $user_email = NULL;
    $form_fields_array = explode( '**:*new_field*:*', $form_fields );
    foreach ( $form_fields_array as $i => $field_data ) {
      $field_id_and_data = explode( '*:*id*:*', $field_data );
      //check if there is email field in form
      if ( !empty($field_id_and_data[1]) && strpos( $field_id_and_data[1], 'type_submitter_mail' ) === 0 ) {
        $recipient = WDW_FM_Library( self::PLUGIN )->get( 'wdform_' . $field_id_and_data[0] . "_element" . $form_id, NULL );
        if ( ! empty( $recipient ) ) {
          $user_email = $recipient;
          break;
        }
      }
    }
    return $user_email;
  }

  /**
   * Array of allowed html tags
   *
   */
  public static function allowed_html_tags() {
    $allowed_attr = array(
      "class" => TRUE,
      "style" => TRUE,
      "width" => TRUE,
      "height" => TRUE,
      "cellspacing" => TRUE,
      "cellpadding" => TRUE,
    );
    $allowed_html = array(
      "a" => array(
        "href" => TRUE,
        "title" => TRUE,
      ),
      "h1" => array(),
      "h2" => array(),
      "h3" => array(),
      "h4" => array(),
      "h5" => array(),
      "h6" => array(),
      "i" => array(),
      "em" => array(),
      "strong" => array(),
      "br" => array(),
      "hr" => array(),
      "del" => array(
        "datetime" => TRUE,
      ),
      "ins" => array(
        "datetime" => TRUE,
      ),
      "ul" => array(),
      "ol" => array(),
      "li" => array(),
      "table" => $allowed_attr,
      "tbody" => $allowed_attr,
      "th" => $allowed_attr,
      "tr" => $allowed_attr,
      "td" => $allowed_attr,
      "p" => array(),
      "code" => array(),
      "div" => $allowed_attr,
      "span" => $allowed_attr,
      "img" => array( "class" => TRUE, "src" => TRUE, "alt" => TRUE),
      "video" => array( "class" => TRUE, "src" => TRUE, "controls" => TRUE),
      "source" => array( "src" => TRUE, "type" => TRUE ),
    );

    return $allowed_html;
  }

  /**
   * Select data from db for labels.
   *
   * @param string $db_info
   * @param string $label_column
   * @param string $table
   * @param string $where
   * @param string $order_by
   * @return mixed
   */
  public static function select_data_from_db_for_labels( $db_info = '', $label_column = '', $table = '', $where = '', $order_by = '' ) {
    global $wpdb;
    $where = html_entity_decode($where, ENT_QUOTES);
    $query = "SELECT `" . $label_column . "` FROM " . $table . $where . " ORDER BY " . $order_by;
    $db_info = trim($db_info, '[]');
    if ( $db_info ) {
      $temp = explode( '@@@wdfhostwdf@@@', $db_info );
      $host = $temp[ 0 ];
      $temp = explode( '@@@wdfportwdf@@@', $temp[1] );
      $port = $temp[ 0 ];
      if ($port) {
        $host .= ':' . $port;
      }
      $temp = explode( '@@@wdfusernamewdf@@@', $temp[ 1 ] );
      $username = $temp[ 0 ];
      $temp = explode( '@@@wdfpasswordwdf@@@', $temp[ 1 ] );
      $password = $temp[ 0 ];
      $temp = explode( '@@@wdfdatabasewdf@@@', $temp[ 1 ] );
      $database = $temp[ 0 ];
      $wpdb_temp = new wpdb( $username, $password, $database, $host );
      $choices_labels = $wpdb_temp->get_results( $query, ARRAY_N );
    } else {
      $choices_labels = $wpdb->get_results( $query, ARRAY_N );
    }

    return $choices_labels;
  }

  /**
   * Select data from db for values.
   *
   * @param string $db_info
   * @param string $value_column
   * @param string $table
   * @param string $where
   * @param string $order_by
   *
   * @return array|null|object
   */
  public static function select_data_from_db_for_values( $db_info = '', $value_column = '', $table = '', $where = '', $order_by = '' ) {
    global $wpdb;
    $where = html_entity_decode($where, ENT_QUOTES);
    $query = "SELECT `" . $value_column . "` FROM " . $table . $where . " ORDER BY " . $order_by;
    $db_info = trim($db_info, '[]');
    if ( $db_info ) {
      $temp = explode( '@@@wdfhostwdf@@@', $db_info );
      $host = $temp[ 0 ];
      $temp = explode( '@@@wdfportwdf@@@', $temp[ 1 ] );
      $port = $temp[0];
      if ($port) {
        $host .= ':' . $port;
      }
      $temp = explode( '@@@wdfusernamewdf@@@', $temp[ 1 ] );
      $username = $temp[ 0 ];
      $temp = explode( '@@@wdfpasswordwdf@@@', $temp[ 1 ] );
      $password = $temp[ 0 ];
      $temp = explode( '@@@wdfdatabasewdf@@@', $temp[ 1 ] );
      $database = $temp[ 0 ];
      $wpdb_temp = new wpdb( $username, $password, $database, $host );
      $choices_values = $wpdb_temp->get_results( $query, ARRAY_N );
    } else {
      $choices_values = $wpdb->get_results( $query, ARRAY_N );
    }

    return $choices_values;
  }

}