<?php
/**
 * Class FMViewBlocked_ips_fm
 */
class FMViewBlocked_ips_fm extends FMAdminView {
  /**
   * FMViewBlocked_ips_fm constructor.
   */
  public function __construct() {
   $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
	if ( $fm_settings['fm_developer_mode'] ) {
		wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-admin');
	}
	else {
		wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-styles');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-scripts');
	}
  }

  /**
   * Display.
   *
   * @param $params
   */
  public function display( $params = array() ) {
    ob_start();
    echo $this->body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => 'blocked_ips',
      'class' => 'wd-form',
      'action' => add_query_arg(array('page' => 'blocked_ips' . WDFMInstance(self::PLUGIN)->menu_postfix), 'admin.php'),
    );
    echo $this->form(ob_get_clean(), $form_attr);
  }

  /**
  * Generate page body.
  *
  * @param array $params
  * @return string Body html.
  */
  public function body( $params = array() ) {
    $rows_data = $params['rows_data'];
    $total = $params['total'];
    $order = $params['order'];
    $orderby = $params['orderby'];
    $items_per_page = $params['items_per_page'];
    $actions = $params['actions'];
    $page = $params['page'];

    $page_url = add_query_arg(array(
                                'page' => $page,
                                WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce),
                              ), admin_url('admin.php'));

    echo $this->title(array(
                        'title' => __('Blocked IPs', WDFMInstance(self::PLUGIN)->prefix),
                        'title_class' => 'wd-header',
                        'add_new_button' => FALSE,
                      ));

    echo $this->search();

    ?>
    <div class="tablenav top">
      <?php
      echo $this->bulk_actions($actions);
      echo $this->pagination($page_url, $total, $items_per_page);
      ?>
    </div>
    <table class="adminlist table table-striped wp-list-table widefat fixed pages">
      <thead>
        <tr>
          <td id="cb" class="manage-column column-cb check-column">
            <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select all', WDFMInstance(self::PLUGIN)->prefix); ?></label>
            <input id="check_all" type="checkbox" />
          </td>
          <?php echo WDW_FM_Library(self::PLUGIN)->ordering('ip', $orderby, $order, __('IP', WDFMInstance(self::PLUGIN)->prefix), $page_url, 'column-primary col_type wd-left'); ?>
        </tr>
        <tr id="tr">
          <th></th>
          
          <td>
            <input type="text" class="input_th" id="fm_ip" name="ip" onkeypress="fm_enter_ip(event); return fm_check_isnum(event)">
            <input type = button class="button action" id="add_ip" onclick="if (fm_check_required('fm_ip', '<?php _e('IP', WDFMInstance(self::PLUGIN)->prefix); ?>')) {return false;}  fm_insert_blocked_ip('blocked_ips'); " value="<?php _e('Add IP', WDFMInstance(self::PLUGIN)->prefix); ?>">
            <div class="loading"><img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url ?>/images/loading.gif"></div>
          </td>
        </tr>

      </thead>
      <tbody>
        <?php
        if ( $rows_data ) {
          foreach ( $rows_data as $row_data ) {
            $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
            ?>
            <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
              <th class="check-column">
                <input id="check_<?php echo $row_data->id; ?>" name="check[<?php echo $row_data->id; ?>]" type="checkbox" />
              </th>

              <td class="column-primary" id="td_ip_<?php echo $row_data->id; ?>" data-colname="<?php _e('IP', WDFMInstance(self::PLUGIN)->prefix); ?>">

                <strong class="wd_ip_name_<?php echo $row_data->id; ?>">
                  <a class="pointer" id="ip<?php echo $row_data->id; ?>" onclick="fm_edit_ip(<?php echo $row_data->id; ?>)" title="Edit"><?php echo $row_data->ip; ?></a>

                </strong>
                <div class="loading"><img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url ?>/images/loading.gif"></div>

                <div class="row-actions">
                  
                  <span id="td_edit_<?php echo $row_data->id; ?>">
                    <a class="pointer" onclick="fm_edit_ip(<?php echo $row_data->id; ?>);"> <?php _e('Edit', WDFMInstance(self::PLUGIN)->prefix); ?> </a>
                    |
                  </span>

                  <span class="trash" id="td_delete_<?php echo $row_data->id; ?>">
                    <a class="pointer" onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', WDFMInstance(self::PLUGIN)->prefix)); ?>')) {return false;}  fm_delete_ip(<?php echo $row_data->id; ?>) "><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                  </span>
                </div>
              </td>
            </tr>
            <?php
          }
        }
        else {
          echo WDW_FM_Library(self::PLUGIN)->no_items('IPs');
        }
        ?>
      </tbody>
    </table>
    <?php
  }
}
