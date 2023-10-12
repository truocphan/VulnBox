<?php
class FMControllerManage_fm extends FMAdminController {

  private $model;
  private $view;
  private $page;
  private $page_url;
  private $bulk_action_name = '';
  private $items_per_page = 20;
  private $animation_effects = array();
  private $actions = array();
  private $revision = 0;

  function __construct() {
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/models/Manage_fm.php";
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/Manage_fm.php";
    $this->model = new FMModelManage_fm();
    $this->view = new FMViewManage_fm();
    $this->page = WDW_FM_Library(self::PLUGIN)->get('page', 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix);
    $this->page_url = add_query_arg(array(
      'page' => $this->page,
      WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce),
    ), admin_url('admin.php')
    );
    $this->bulk_action_name = 'bulk_action';

    $this->actions = array(
      'publish' => array(
        'title' => __('Publish', WDFMInstance(self::PLUGIN)->prefix),
        $this->bulk_action_name => __('published', WDFMInstance(self::PLUGIN)->prefix),
      ),
      'unpublish' => array(
        'title' => __('Unpublish', WDFMInstance(self::PLUGIN)->prefix),
        $this->bulk_action_name => __('unpublished', WDFMInstance(self::PLUGIN)->prefix),
      ),
      'duplicate' => array(
        'title' => __('Duplicate', WDFMInstance(self::PLUGIN)->prefix),
        $this->bulk_action_name => __('duplicated', WDFMInstance(self::PLUGIN)->prefix),
      ),
      'delete' => array(
        'title' => __('Delete', WDFMInstance(self::PLUGIN)->prefix),
        $this->bulk_action_name => __('deleted', WDFMInstance(self::PLUGIN)->prefix),
      ),
    );

    $this->animation_effects = array(
      'none' => 'None',
      'bounce' => 'Bounce',
      'tada' => 'Tada',
      'bounceInDown' => 'BounceInDown',
      'fadeInLeft' => 'FadeInLeft',
      'flash' => 'Flash',
      'pulse' => 'Pulse',
      'rubberBand' => 'RubberBand',
      'shake' => 'Shake',
      'swing' => 'Swing',
      'wobble' => 'Wobble',
      'hinge' => 'Hinge',
      'lightSpeedIn' => 'LightSpeedIn',
      'rollIn' => 'RollIn',
      'bounceIn' => 'BounceIn',
      'bounceInLeft' => 'BounceInLeft',
      'bounceInRight' => 'BounceInRight',
      'bounceInUp' => 'BounceInUp',
      'fadeIn' => 'FadeIn',
      'fadeInDown' => 'FadeInDown',
      'fadeInDownBig' => 'FadeInDownBig',
      'fadeInLeftBig' => 'FadeInLeftBig',
      'fadeInRight' => 'FadeInRight',
      'fadeInRightBig' => 'FadeInRightBig',
      'fadeInUp' => 'FadeInUp',
      'fadeInUpBig' => 'FadeInUpBig',
      'flip' => 'Flip',
      'flipInX' => 'FlipInX',
      'flipInY' => 'FlipInY',
      'rotateIn' => 'RotateIn',
      'rotateInDownLeft' => 'RotateInDownLeft',
      'rotateInDownRight' => 'RotateInDownRight',
      'rotateInUpLeft' => 'RotateInUpLeft',
      'rotateInUpRight' => 'RotateInUpRight',
      'zoomIn' => 'ZoomIn',
      'zoomInDown' => 'ZoomInDown',
      'zoomInLeft' => 'ZoomInLeft',
      'zoomInRight' => 'ZoomInRight',
      'zoomInUp' => 'ZoomInUp',
    );
  }

  public function execute() {
    $id = WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval');
    $task = WDW_FM_Library(self::PLUGIN)->get('task', '', 'sanitize_key');
    if ( method_exists($this, $task) ) {
      if ( $task != 'add' && $task != 'edit' && $task != 'display' && $task != 'form_options' && $task != 'email_options' && $task != 'display_options' && $task != 'form_layout' && $task != 'fm_live_search' ) {
        check_admin_referer(WDFMInstance(self::PLUGIN)->nonce, WDFMInstance(self::PLUGIN)->nonce);
      }
      $block_action = $this->bulk_action_name;
      $action = WDW_FM_Library(self::PLUGIN)->get($block_action, -1, 'sanitize_key');
      if ( $action != -1 ) {
        $this->$block_action( $action );
      }
      else {
        $this->$task( $id );
      }
    }
    else {
      $this->display();
    }
  }

  public function display() {
    $params = array();
    $params['order'] = WDW_FM_Library(self::PLUGIN)->get('order', 'asc');
    $params['orderby'] = WDW_FM_Library(self::PLUGIN)->get('orderby', 'id');
    // To prevent SQL injections.
    if ( !in_array($params['orderby'], array( 'id', 'title', 'type' )) ) {
      $params['orderby'] = 'id';
    }
    $params['order'] = $params['order'] == 'desc' ? 'desc' : 'asc';

    $params['items_per_page'] = $this->items_per_page;

    $params['rows_data'] = $this->model->get_rows_data($params);
    $params['total'] = $this->model->total();

    $params['actions']  = $this->actions;
    $params['page'] 	= $this->page;
  	$params['form_preview_link'] = $this->model->get_form_preview_post();

    $this->view->display($params);
  }

  /**
   * Bulk actions.
   *
   * @param string $task
   */
  public function bulk_action( $task = '' ) {
    $message = 0;
    if ( method_exists($this, $task) ) {
      $check = WDW_FM_Library(self::PLUGIN)->get('check', '');
      if ( !empty($check) ) {
        $successfully_updated = 0;
        foreach ( $check as $id => $item ) {
          $message = $this->$task( intval($id), TRUE );
          if ( $message != 2 ) {
            // Increase successfully updated items count, if action doesn't failed.
            $successfully_updated++;
          }
        }
        if ( $successfully_updated ) {
          $block_action = $this->bulk_action_name;
          $message = sprintf(_n('%s item successfully %s.', '%s items successfully %s.', $successfully_updated, WDFMInstance(self::PLUGIN)->prefix), $successfully_updated, $this->actions[$task][$block_action]);
        }
      }
    }
    WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                'page' => $this->page,
                                                'task' => 'display',
                                                ($message === 2 ? 'message' : 'msg') => $message,
                                              ), admin_url('admin.php')));

  }

  /**
   * Delete form by id.
   *
   * @param int $id
   * @param bool $bulk
   *
   * @return int
   */
  public function delete( $id = 0, $bulk = FALSE ) {
    if ( $this->model->delete_rows(array( "table" => "formmaker", "where" => "id = " . $id )) ) {
      $this->model->delete_rows(array( "table" => "formmaker_views", "where" => "form_id = " . $id ));
      $this->model->delete_rows(array( "table" => "formmaker_submits", "where" => "form_id = " . $id ));
      $this->model->delete_rows(array( "table" => "formmaker_sessions", "where" => "form_id = " . $id ));
      $this->model->delete_rows(array( "table" => "formmaker_backup", "where" => "id = " . $id ));
      $this->model->delete_rows(array( "table" => "formmaker_display_options", "where" => "form_id = " . $id ));
      $this->model->delete_rows(array( "table" => "formmaker_query", "where" => "form_id = " . $id ));
      if (WDFMInstance(self::PLUGIN)->is_free == 2) {
        $arr = explode(',', get_option('contact_form_forms'));
        $arr = array_diff($arr, array($id));
        $arr = implode(',', $arr);
        update_option('contact_form_forms', $arr);
      }
      // To delete DB rows with form ids from extensions.
      if (WDFMInstance(self::PLUGIN)->is_free != 2) {
        do_action('fm_delete_addon_init', $id);
      }
      $message = 3;
    }
    else {
      $message = 2;
    }

    if ( $bulk ) {
      return $message;
    }
    else {
      WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                  'page' => $this->page,
                                                  'task' => 'display',
                                                  'message' => $message,
                                                ), admin_url('admin.php')));
    }
  }

  /**
   * Publish by id.
   *
   * @param int $id
   * @param bool $bulk
   *
   * @return int
   */
  public function publish( $id = 0, $bulk = FALSE ) {
    $updated = $this->model->update_data('formmaker', array('published' => 1), array('id' => $id));
    if ( $updated !== FALSE ) {
      $message = 9;
    }
    else {
      $message = 2;
    }

    if ( $bulk ) {
      return $message;
    }
    else {
      WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                  'page' => $this->page,
                                                  'task' => 'display',
                                                  'message' => $message,
                                                ), admin_url('admin.php')));
    }
  }

  /**
   * Unpublish by id.
   *
   * @param int $id
   * @param bool $bulk
   *
   * @return int
   */
  public function unpublish( $id = 0, $bulk = FALSE ) {
    $updated = $this->model->update_data('formmaker', array('published' => 0), array('id' => $id));
    if ( $updated !== FALSE ) {
      $message = 10;
    }
    else {
      $message = 2;
    }

    if ( $bulk ) {
      return $message;
    }
    else {
      WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                  'page' => $this->page,
                                                  'task' => 'display',
                                                  'message' => $message,
                                                ), admin_url('admin.php')));
    }
  }

  /**
   * Duplicate by id.
   *
   * @param int $id
   * @param bool $bulk
   *
   * @return int
   */
  public function duplicate( $id = 0, $bulk = FALSE ) {
    $message = 2;
    $row = $this->model->select_rows("get_row", array(
      "selection" => "*",
      "table" => "formmaker",
      "where" => "id=" . (int) $id,
    ));
    if ( $row ) {
      $row = (array) $row;
      unset($row['id']);
      $row['title'] = $row['title'] . ' - ' . __('Copy', WDFMInstance(self::PLUGIN)->prefix);
      $inserted = $this->model->insert_data_to_db("formmaker", (array) $row);
      $new_id = (int) $this->model->get_max_row('formmaker', 'id');
      $row_display_options = $this->model->select_rows("get_row", array(
        "selection" => "*",
        "table" => "formmaker_display_options",
        "where" => "form_id=" . (int) $id,
      ));
      if ( $row_display_options ) {
        $row_display_options = (array) $row_display_options;
        unset($row_display_options['id']);
        $row_display_options['form_id'] = $new_id;
        $inserted = $this->model->insert_data_to_db("formmaker_display_options", (array) $row_display_options);
      }
      $row_query = $this->model->select_rows("get_results", array(
        "selection" => "*",
        "table" => "formmaker_query",
        "where" => "form_id=" . (int) $id,
      ));
      if ( $row_query ) {
        foreach ( $row_query as $query ) {
          $query = (array) $query;
          unset($query['id']);
          $query['form_id'] = $new_id;
          $inserted = $this->model->insert_data_to_db("formmaker_query", (array) $query);
        }
      }
      if ( WDFMInstance(self::PLUGIN)->is_free == 2 ) {
        update_option('contact_form_forms', ((get_option('contact_form_forms')) ? (get_option('contact_form_forms')) . ',' . $new_id : $new_id));
      }
      else {
        // Duplicate extensions data.
        do_action('fm_duplicate_form', $id, $new_id);
      }
      if ( $inserted !== FALSE ) {
        $message = 11;
      }
    }
    if ( $bulk ) {
      return $message;
    }
    else {
      WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                                'page' => $this->page,
                                                                'task' => 'display',
                                                                'message' => $message,
                                                              ), admin_url('admin.php')));
    }
  }

  public function add() {
    $backup_id = 0;
    $params = array();
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    $fm_enable_wp_editor = !isset( $fm_settings['fm_enable_wp_editor'] ) ? 1 : $fm_settings['fm_enable_wp_editor'];
    $params['fm_enable_wp_editor'] = $fm_enable_wp_editor;
    $params['id']  = $backup_id;
    $params['row'] = $this->model->get_row_data_new($backup_id);
    $params['page_url']		= $this->page_url;
    // Check if Stripe extension is active.
    $stripe_addon = array('enable' => 0);
    $addon_stripe = $this->get_stripe_addon(0);
    if( !empty($addon_stripe['html']) ) {
      $stripe_addon = $addon_stripe;
    }
    $params['stripe_addon'] = $stripe_addon;

    $params['themes'] = $this->model->get_theme_rows_data();
    $params['default_theme'] = $this->model->get_default_theme_id();
    $params['form_preview_link'] = "";

    $params['autogen_layout'] = 1;
    $labels = array();
    $label_id = array();
    $label_order_original = array();
    $label_type = array();
    $label_all = explode('#****#', $params['row']->label_order);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      array_push($label_id, $label_id_each[0]);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      array_push($label_order_original, addslashes($label_oder_each[0]));
      array_push($label_type, $label_oder_each[1]);
    }

    $params[ 'label_label' ] = array();

    $labels['id'] = '"' . implode('","', $label_id) . '"';
    $labels['label'] = '"' . implode('","', $label_order_original) . '"';
    $labels['type'] = '"' . implode('","', $label_type) . '"';
    $params['labels'] = $labels;

    $params['page_title'] = __('Create new form', WDFMInstance(self::PLUGIN)->prefix);
    $params['animation_effects'] = $this->animation_effects;

    $this->view->edit($params);
  }

  /**
   * Edit.
   *
   * @param int $id
   * @param int $backup_id
   */
  public function edit( $id = 0, $backup_id = 0 ) {
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    $fm_advanced_layout = isset($fm_settings['fm_advanced_layout']) && $fm_settings['fm_advanced_layout'] == '1' ? 1 : 0;
    $fm_enable_wp_editor = !isset( $fm_settings['fm_enable_wp_editor'] ) ? 1 : $fm_settings['fm_enable_wp_editor'];
    if ( $id && !$fm_advanced_layout ) {
      $fm_advanced_layout = !$this->model->get_autogen_layout($id);
    }

    if ( !$backup_id ) {
      $backup_id = $this->model->select_rows("get_var", array(
        "selection" => "backup_id",
        "table" => "formmaker_backup",
        "where" => "cur=1 and id=" . $id,
      ));

      if ( !$backup_id ) {
        $backup_id = $this->model->get_max_row("formmaker_backup", "backup_id");
        if ( $backup_id ) {
          $backup_id++;
        }
        else {
          $backup_id = 1;
        }
        $this->model->insert_formmaker_backup($backup_id, $id);
      }
    }

    $params = array();
    $params['id'] = $id;
    $params['backup_id'] = $backup_id;
    $params['row'] = $this->model->get_row_data_new($backup_id);
    if ( empty($params['row']) ) {
      WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array('page' => $this->page, 'task' => 'display'), admin_url('admin.php') ) );
    }
    // If form data is corrupted.
    if ( ( '' == $params[ 'row' ]->form_fields || '' == $params[ 'row' ]->label_order_current ) && 0 < strpos( $params[ 'row' ]->form_front, 'class="wdform_row"' ) ) {
      $backup_id = $this->model->get_prev_backup_id($backup_id, $id);
      if ( $backup_id ) {
        // Restore previous revision.
        $this->model->restore_backup($backup_id, $id);
        WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg(array( 'page' => $this->page, 'task' => 'edit', 'current_id' => $id, 'message' => 15 ), admin_url('admin.php')) );
      }
      else {
        // Redirect to form list with error message.
        WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array('page' => $this->page, 'task' => 'display', 'message' => 16), admin_url('admin.php') ) );
      }
    }

    // Check stripe extension is active.
    $stripe_addon = array( 'enable' => 0 );
    $addon_stripe = $this->get_stripe_addon($id);
    if ( !empty($addon_stripe['html']) ) {
      $stripe_addon = $addon_stripe;
    }
    $params['stripe_addon'] = $stripe_addon;
    $params['page_url']		= $this->page_url;
    $params['themes'] = $this->model->get_theme_rows_data();
    $params['default_theme'] = $this->model->get_default_theme_id();

    $params['form_preview_link'] = $this->model->get_form_preview_post();
    $params['fm_enable_wp_editor'] = $fm_enable_wp_editor;

    if ( $id ) {
      $params['form_options_url'] = add_query_arg( array( 'page' => $this->page , 'task' => 'form_options', 'current_id' => $id ), $this->page_url );
      $params['display_options_url'] = add_query_arg( array( 'page' => $this->page , 'task' => 'display_options', 'current_id' => $id ), $this->page_url );
      $params['advanced_layout_url'] = $fm_advanced_layout ? add_query_arg( array( 'page' => $this->page , 'task' => 'form_layout', 'current_id' => $id ), $this->page_url ) : '';
    }

    $labels = array();
    $labelIds = array();
    $label_id = array();
    $label_label = array();
    $label_order_original = array();
    $label_type = array();
    $label_all = explode('#****#', $params['row']->label_order);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode( '#**id**#', $label_each );
      $label_order_each = explode( '#**label**#', $label_id_each[ 1 ] );

      array_push($label_id, $label_id_each[0]);
      array_push($label_order_original, addslashes($label_order_each[0]));
      array_push($label_type, $label_order_each[1]);
      array_push( $label_label, $label_order_each[ 0 ] );
    }
    $label_current = explode('#****#', $params['row']->label_order_current);
    $label_current = array_slice($label_current, 0, count($label_current) - 1);
    foreach ( $label_current as $key => $label ) {
      $label_id_each = explode( '#**id**#', $label );
      $label_order_each = explode( '#**label**#', $label_id_each[ 1 ] );
      $labelIds[ $label_id_each[ 0 ] ] = array( 'type' => $label_order_each[ 1 ], 'name' => $label_order_each[ 0 ] );
    }
    $params[ 'label_label' ] = WDW_FM_Library(self::PLUGIN)->create_email_options_placeholders( $labelIds );

    $labels['id'] = '"' . implode('","', $label_id) . '"';
    $labels['label'] = '"' . implode('","', $label_order_original) . '"';
    $labels['type'] = '"' . implode('","', $label_type) . '"';
    $params['labels'] = $labels;

    $params[ 'fields' ] = explode( '*:*id*:*type_submitter_mail*:*type*:*', $params[ 'row' ]->form_fields );
    $params[ 'fields_count' ] = count( $params[ 'fields' ] );
    $params['page_title'] = (($params['id'] != 0) ? 'Edit form ' . $params['row']->title : 'Create new form');
	  $params['animation_effects'] = $this->animation_effects;

    $rev = $this->model->get_revisions( $id );
    $params['revisions'] = $rev['data'];
    $params['revisions_total'] = $rev['total'];
    $params['revisions_status'] = $this->revision;
    $params['current_revision'] = $this->model->get_current_revision( $id );
    $params['revision_date'] = $this->model->get_revision_date( $backup_id );
    $params['rev_index'] = WDW_FM_Library(self::PLUGIN)->get('rev_id');

    $this->view->edit($params);
  }

  public function undo() {
    $backup_id = WDW_FM_Library(self::PLUGIN)->get('backup_id', 0, 'intval');
    $id = WDW_FM_Library(self::PLUGIN)->get('id', 0, 'intval');
    $this->revision = 1;
    $this->edit($id, $backup_id);
  }

  /**
   * Form options.
   *
   * @param int $id
   */
  public function form_options( $id = 0 ) {
    // Set params for view.
    $params = array();
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    $params['fm_enable_wp_editor'] = !isset( $fm_settings['fm_enable_wp_editor'] ) ? 1 : $fm_settings['fm_enable_wp_editor'];
    $params['id'] = $id;
    $params['page'] = $this->page;
    $params['page_url'] = $this->page_url;

    $params[ 'back_url' ] = add_query_arg( array(
                                             'page' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix,
                                             'task' => 'edit',
                                             'current_id' => $id
                                           ), admin_url( 'admin.php' )
    );

    $params['fieldset_id'] = WDW_FM_Library(self::PLUGIN)->get( 'fieldset_id', 'general' );

    $params['row'] = $this->model->get_row_data( $id );
    if ( empty( $params[ 'row' ] ) ) {
      WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array( 'page' => $this->page ), admin_url( 'admin.php' ) ) );
    }

    $params['themes'] = $this->model->get_theme_rows_data();
    $params['default_theme'] = $this->model->get_default_theme_id();
    $params['queries'] = $this->model->get_queries_rows_data( $id );
    $params['userGroups'] = get_editable_roles();
    $params['page_title'] = '"' . $params[ 'row' ]->title . '" ' . __( 'options', WDFMInstance(self::PLUGIN)->prefix );

    $label_id = array();
    $label_label = array();
    $labelIds = array();
    $label_type = array();
    $label_all = explode( '#****#', $params[ 'row' ]->label_order_current );
    $label_all = array_slice( $label_all, 0, count( $label_all ) - 1 );
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode( '#**id**#', $label_each );
      $label_order_each = explode( '#**label**#', $label_id_each[ 1 ] );
      $name_field = 'wdform_' . $label_id_each[0] . '_element'. $id;
      $labelIds[ $label_id_each[0] ] = array( 'type' => $label_order_each[1], 'name' => $label_order_each[0], 'name_field' => $name_field);
      array_push( $label_id, $label_id_each[0] );
      array_push( $label_label, $label_order_each[0] );
      array_push( $label_type, $label_order_each[1] );
    }

    // chechk stripe addon is active.
    $stripe_addon = array( 'enable' => 0 );
    $addon_stripe = $this->get_stripe_addon( $id );
    if ( !empty( $addon_stripe[ 'html' ] ) ) {
      $stripe_addon = $addon_stripe;
    }
    $params['stripe_addon'] = $stripe_addon;

    /*
      @TODO. Remember paypal_mode status. 0 => none, 1 => paypal, 2  => stripe
      Change. Rename paypal_mode name to payment_method of wp_formmaker table.
    */
    $paypal_mode = $params[ 'row' ]->paypal_mode;
    $payment_method = 'none';
    if ( $paypal_mode == 1 ) {
      $payment_method = 'paypal';
    }
    if ( $paypal_mode == 2 && isset( $stripe_addon[ 'stripe_enable' ] ) && $stripe_addon[ 'stripe_enable' ] == 1 ) {
      $payment_method = 'stripe';
    }

    $params[ 'payment_method' ] = $payment_method;
    $params[ 'labels_for_submissions' ] = $this->model->get_labels( $id );
    $params[ 'payment_info' ] = $this->model->is_paypal( $id );

    $labels_id_for_submissions = array();
    $label_titles_for_submissions = array();
    $labels_type_for_submissions = array();
    if ( $params['labels_for_submissions'] ) {
      $label_id_for_submissions = array();
      $label_order_original_for_submissions = array();
      $label_type_for_submissions = array();
      if ( strpos( $params['row']->label_order, 'type_paypal_' ) ) {
        $params['row']->label_order = $params[ 'row' ]->label_order . "item_total#**id**#Item Total#**label**#type_paypal_payment_total#****#total#**id**#Total#**label**#type_paypal_payment_total#****#0#**id**#Payment Status#**label**#type_paypal_payment_status#****#";
      }
      if ( strpos($params['row']->label_order, 'type_submitter_mail') ) {
        $params['row']->label_order = $params['row']->label_order . 'user_email#**id**#user_email#**label**#type_submitter_mail#****';
        $params['row']->label_order = $params['row']->label_order . 'verifyInfo#**id**#verify_info#**label**#type_user_email_verify#****#';
      }
      $label_all_for_submissions = explode( '#****#', $params[ 'row' ]->label_order );
      $label_all_for_submissions = array_slice( $label_all_for_submissions, 0, count( $label_all_for_submissions ) - 1 );
      foreach ( $label_all_for_submissions as $key => $label_each ) {
        $label_id_each = explode( '#**id**#', $label_each );
        array_push( $label_id_for_submissions, $label_id_each[ 0 ] );
        $label_order_each = explode( '#**label**#', $label_id_each[ 1 ] );
        array_push( $label_order_original_for_submissions, $label_order_each[ 0 ] );
        array_push( $label_type_for_submissions, $label_order_each[ 1 ] );
      }
      foreach ( $label_id_for_submissions as $key => $label ) {
        if ( in_array( $label, $params[ 'labels_for_submissions' ] ) ) {
          array_push( $labels_type_for_submissions, $label_type_for_submissions[ $key ] );
          array_push( $labels_id_for_submissions, $label );
          array_push( $label_titles_for_submissions, $label_order_original_for_submissions[ $key ] );
        }
      }
      $params['labels_id_for_submissions'] = $labels_id_for_submissions;
      $params['label_titles_for_submissions'] = $label_titles_for_submissions;
    }
    $stats_labels = array();
    $stats_labels_ids = array();
    foreach ( $labels_type_for_submissions as $key => $label_type_cur ) {
      if ( $label_type_cur == "type_checkbox" || $label_type_cur == "type_radio" || $label_type_cur == "type_own_select" || $label_type_cur == "type_country" || $label_type_cur == "type_paypal_select" || $label_type_cur == "type_paypal_radio" || $label_type_cur == "type_paypal_checkbox" || $label_type_cur == "type_paypal_shipping" ) {
        $stats_labels_ids[] = $labels_id_for_submissions[ $key ];
        $stats_labels[] = $label_titles_for_submissions[ $key ];
      }
    }

    $params['stats_labels_ids'] = $stats_labels_ids;
    $params['stats_labels'] = $stats_labels;
    $params['mail_ver_id'] = $params['row']->mail_verification_post_id;
    $params['addons'] = $this->get_addon_tabs( array( 'form_id' => $id, 'labelIds' => $labelIds ) );

    $this->view->form_options( $params );
  }

  /**
   * Email options.
   *
   * @param int $id
   */
  public function email_options( $id = 0 ) {
    // Set params for view.
    $params = array();
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    $params['fm_enable_wp_editor'] = !isset( $fm_settings['fm_enable_wp_editor'] ) ? 1 : $fm_settings['fm_enable_wp_editor'];
    $params['id'] = $id;
    $params['page'] = $this->page;
    $params['page_url'] = $this->page_url;

    $params[ 'back_url' ] = add_query_arg( array(
                                             'page' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix,
                                             'task' => 'edit',
                                             'current_id' => $id
                                           ), admin_url( 'admin.php' )
    );

    $params['fieldset_id'] = WDW_FM_Library(self::PLUGIN)->get( 'fieldset_id', 'general' );

    $params['row'] = $this->model->get_row_data( $id );
    if ( empty( $params['row'] ) ) {
      WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array( 'page' => $this->page ), admin_url( 'admin.php' ) ) );
    }
    $params['fields'] = explode( '*:*id*:*type_submitter_mail*:*type*:*', $params[ 'row' ]->form_fields );
    $params['fields_count'] = count( $params[ 'fields' ] );
    $params['mail_ver_id'] = $params['row']->mail_verification_post_id;
    $params['row']->from_mail = $this->check_mail_field_exists( $id, $params['row']->from_mail, $params['row']->form_fields );
    $params['row']->reply_to = $this->check_mail_field_exists( $id, $params['row']->reply_to, $params['row']->form_fields );
    $this->view->email_options( $params );
  }

  /**
   * Get active addons.
   *
   * @param $params
   * @return mixed|void
   */
	private function get_addon_tabs( $params = array() ) {
    $addons = array('tabs' => array(), 'html' => array());
    if (WDFMInstance(self::PLUGIN)->is_free != 2) {
      $addons = apply_filters('fm_get_addon_init', $addons, $params);
    }
    return $addons;
	}

  /**
   * Apply form options.
   *
   * @param int $id
   */
  public function apply_form_options( $id = 0 ) {
    $fieldset_id = WDW_FM_Library(self::PLUGIN)->get('fieldset_id', 'general');
    $backup_id = (int) $this->model->get_max_row('formmaker_backup', 'backup_id');
    $message = $this->save_db_form_options( $id, $backup_id );
    WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                'page' => $this->page,
                                                'task' => 'edit',
                                                'current_id' => $id,
                                                'fieldset_id' => $fieldset_id,
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  /**
   * Check mail field id exists in fields string.
   *
   * @param int $id form id
   * @param int $field_id from_mail, reply_to field id
   * @param string $fields form fields
   *
   *  return int field id or empty if not exists
  */
  public function check_mail_field_exists( $id, $field_id, $fields = '' ) {
    if (is_numeric($field_id)) {
      if( $fields === '' ) {
        $fields = $this->model->select_rows('get_var', array(
          'selection' => 'form_fields',
          'table' => 'formmaker',
          'where' => 'id=' . $id
        ));
      }
      if( strpos( $fields, $field_id."*:*id*" ) > -1 ) {
        return $field_id;
      }
      return '';
    } else {
      return $field_id;
    }
  }

  /**
   * Save db Email options.
   *
   * @param int $id
   * @param int $backup_id
   *
   * @return int $id_message
   */
  public function save_db_email_options( $id = 0, $backup_id = 0 ) {
    $sendemail = stripslashes(WDW_FM_Library(self::PLUGIN)->get('sendemail', ''));
    $mail = stripslashes(WDW_FM_Library(self::PLUGIN)->get('mail', ''));
    $from_mail = stripslashes(WDW_FM_Library(self::PLUGIN)->get('from_mail', ''));
    $from_mail = $this->check_mail_field_exists( $id, $from_mail );
    $from_name = stripslashes(WDW_FM_Library(self::PLUGIN)->get('from_name', ''));
    $reply_to = stripslashes(WDW_FM_Library(self::PLUGIN)->get('reply_to', ''));
    if ( $from_mail == "other" ) {
      $from_mail = stripslashes(WDW_FM_Library(self::PLUGIN)->get('mail_from_other', ''));
    }
    if ( $reply_to == "other" ) {
      $reply_to = stripslashes(WDW_FM_Library(self::PLUGIN)->get('reply_to_other', ''));
    }
    $script_mail = WDW_FM_Library(self::PLUGIN)->get('script_mail', '{all}', FALSE);
    if ( $script_mail == '' ) {
      $script_mail = '{all}';
    }
    $mail_from_user = WDW_FM_Library(self::PLUGIN)->get('mail_from_user', '');
    $mail_from_name_user = WDW_FM_Library(self::PLUGIN)->get('mail_from_name_user', '');
    $reply_to_user = WDW_FM_Library(self::PLUGIN)->get('reply_to_user', '');
    $mail_cc = WDW_FM_Library(self::PLUGIN)->get('mail_cc', '');
    $mail_cc_user = WDW_FM_Library(self::PLUGIN)->get('mail_cc_user', '');
    $mail_bcc = WDW_FM_Library(self::PLUGIN)->get('mail_bcc', '');
    $mail_bcc_user = WDW_FM_Library(self::PLUGIN)->get('mail_bcc_user', '');
    $mail_subject = WDW_FM_Library(self::PLUGIN)->get('mail_subject', '');
    $mail_subject_user = WDW_FM_Library(self::PLUGIN)->get('mail_subject_user', '');
    $mail_mode = WDW_FM_Library(self::PLUGIN)->get('mail_mode', '');
    $mail_send_email_payment = WDW_FM_Library(self::PLUGIN)->get('mail_send_email_payment', '');
    $mail_send_payment_info = WDW_FM_Library(self::PLUGIN)->get('mail_send_payment_info', 1);
    $mail_send_email_payment_user = WDW_FM_Library(self::PLUGIN)->get('mail_send_email_payment_user', '');
    $mail_mode_user = WDW_FM_Library(self::PLUGIN)->get('mail_mode_user', '');
    $mail_attachment = WDW_FM_Library(self::PLUGIN)->get('mail_attachment', '');
    $mail_attachment_user = WDW_FM_Library(self::PLUGIN)->get('mail_attachment_user', '');
    $script_mail_user = WDW_FM_Library(self::PLUGIN)->get('script_mail_user', '{all}', FALSE);
    if ( $script_mail_user == '' ) {
      $script_mail_user = '{all}';
    }

    $mail_emptyfields = stripslashes(WDW_FM_Library(self::PLUGIN)->get('mail_emptyfields', 0));
    $mail_verify = stripslashes(WDW_FM_Library(self::PLUGIN)->get('mail_verify', 0));
    $mail_verify_expiretime = stripslashes(WDW_FM_Library(self::PLUGIN)->get('mail_verify_expiretime', ''));
    $send_to = '';
    $multiple_user_mail = WDW_FM_Library(self::PLUGIN)->get('send_to', array() );
    foreach ( $multiple_user_mail as $value ) {
      $send_to .= '*' . $value . '*';
    }
    $form_options = array(
      'sendemail' => $sendemail,
      'mail' => $mail,
      'from_mail' => $from_mail,
      'from_name' => $from_name,
      'reply_to' => $reply_to,
      'script_mail' => $script_mail,
      'mail_from_user' => $mail_from_user,
      'mail_from_name_user' => $mail_from_name_user,
      'reply_to_user' => $reply_to_user,
      'mail_cc' => $mail_cc,
      'mail_cc_user' => $mail_cc_user,
      'mail_bcc' => $mail_bcc,
      'mail_bcc_user' => $mail_bcc_user,
      'mail_subject' => $mail_subject,
      'mail_subject_user' => $mail_subject_user,
      'mail_mode' => $mail_mode,
      'mail_mode_user' => $mail_mode_user,
      'mail_send_email_payment' => $mail_send_email_payment,
      'mail_send_payment_info' => $mail_send_payment_info,
      'mail_send_email_payment_user' => $mail_send_email_payment_user,
      'mail_attachment' => $mail_attachment,
      'mail_attachment_user' => $mail_attachment_user,
      'script_mail_user' => $script_mail_user,
      'send_to' => $send_to,
      'mail_emptyfields' => $mail_emptyfields,
      'mail_verify' => $mail_verify,
      'mail_verify_expiretime' => $mail_verify_expiretime,
    );

    $message_id = 2;
    $data = array();
    if ( $mail_verify ) {
      $mail_verification_post_id = $this->model->get_mail_verification_post_id( $id );
      $data['mail_verification_post_id'] = $mail_verification_post_id;
      $form_options['mail_verification_post_id'] = $mail_verification_post_id;
    }

    $save_fm = WDW_FM_Library::update_columns_and_json_column('formmaker', $data, 'form_options', $form_options, array('id' => $id));
    $save_fm_backup = WDW_FM_Library::update_columns_and_json_column('formmaker_backup', $data, 'form_options', $form_options, array('backup_id' => $backup_id));
    if ( $save_fm !== FALSE && $save_fm_backup !== FALSE ) {
      $this->model->create_js($id);
      $message_id = 8;
    }

    return $message_id;
  }

   /**
   * Save db Form options.
   *
   * @param  int $id
   * @return int $id_message
   */
  public function save_db_form_options( $id = 0, $backup_id = 0 ) {
    $javascript = "// Occurs before the form is loaded\r\nfunction before_load() {	\r\n}	\r\n// Occurs just before submitting  the form\r\nfunction before_submit() {\r\n	// IMPORTANT! If you want to interrupt (stop) the submitting of the form, this function should return true. You don\'t need to return any value if you don\'t want to stop the submission.\r\n}	\r\n// Occurs just before resetting the form\r\nfunction before_reset() {	\r\n}\r\n// Occurs after form is submitted and reloaded\r\nfunction after_submit() {\r\n  \r\n}";

    $published = stripslashes(WDW_FM_Library(self::PLUGIN)->get('published', ''));
    $savedb = stripslashes(WDW_FM_Library(self::PLUGIN)->get('savedb', ''));
    $requiredmark = stripslashes(WDW_FM_Library(self::PLUGIN)->get('requiredmark', '*'));
    $submissions_limit = stripslashes(WDW_FM_Library(self::PLUGIN)->get('submissions_limit', 0));
    $submissions_limit_text = stripslashes(WDW_FM_Library(self::PLUGIN)->get('submissions_limit_text', ''));
    $save_uploads = stripslashes(WDW_FM_Library(self::PLUGIN)->get('save_uploads', ''));

    $allowed_html_tags = WDW_FM_Library(self::PLUGIN)->allowed_html_tags();
    $submit_text = html_entity_decode(WDW_FM_Library(self::PLUGIN)->get('submit_text', '', FALSE));
    $submit_text = htmlentities(wp_kses($submit_text, $allowed_html_tags));

    $url = WDW_FM_Library(self::PLUGIN)->get('url', '');
    $tax = WDW_FM_Library(self::PLUGIN)->get('tax', 0);
    $paypal_email = WDW_FM_Library(self::PLUGIN)->get('paypal_email', '', 'esc_attr');
    $checkout_mode = WDW_FM_Library(self::PLUGIN)->get('checkout_mode', 'testmode');
    $paypal_mode = WDW_FM_Library(self::PLUGIN)->get('paypal_mode', 0, 'parseInt');
    $autogen_layout = WDW_FM_Library(self::PLUGIN)->get('autogen_layout', '1');

    // TODO Seclude payment method and payment status.
    $payment_currency = '';
    if ( $paypal_mode == 'paypal' ) {
      $paypal_mode = 1;
      $payment_currency = WDW_FM_Library(self::PLUGIN)->get('payment_currency', '');
    }
    elseif ( $paypal_mode == 'stripe' ) {
      $paypal_mode = 2;
      $payment_currency = WDW_FM_Library(self::PLUGIN)->get('stripe_currency', '');
    }

    $javascript = stripslashes(WDW_FM_Library(self::PLUGIN)->get('javascript', $javascript, false));
    $condition = WDW_FM_Library(self::PLUGIN)->get('condition', '', 'strip_tags');
    $user_id_wd = stripslashes(WDW_FM_Library(self::PLUGIN)->get('user_id_wd', 'administrator,'));
    $frontend_submit_fields = stripslashes(WDW_FM_Library(self::PLUGIN)->get('frontend_submit_fields', ''));
    $frontend_submit_stat_fields = stripslashes(WDW_FM_Library(self::PLUGIN)->get('frontend_submit_stat_fields', ''));

    if ( WDW_FM_Library(self::PLUGIN)->get('submit_text_type', 0) ) {
      $submit_text_type = WDW_FM_Library(self::PLUGIN)->get('submit_text_type', 0);
      if ( $submit_text_type == 5 ) {
        $article_id = WDW_FM_Library(self::PLUGIN)->get('page_name', 0);
      }
      else {
        $article_id = WDW_FM_Library(self::PLUGIN)->get('post_name', 0);
      }
    }
    else {
      $submit_text_type = 1;
      $article_id = 0;
    }

    $privacy_arr = array(
      'gdpr_checkbox' => WDW_FM_Library(self::PLUGIN)->get('gdpr_checkbox', 0),
      'gdpr_checkbox_text' => WDW_FM_Library(self::PLUGIN)->get('gdpr_checkbox_text', __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix)),
      'save_ip' => WDW_FM_Library(self::PLUGIN)->get('save_ip', 1),
      'save_user_id' => WDW_FM_Library(self::PLUGIN)->get('save_user_id', 1),
    );

    $privacy = json_encode($privacy_arr);

    $form_options = array(
      'savedb' => $savedb,
      'requiredmark' => $requiredmark,
      'submissions_limit' => $submissions_limit,
      'submissions_limit_text' => $submissions_limit_text,
      'save_uploads' => $save_uploads,
      'submit_text' => $submit_text,
      'url' => $url,
      'submit_text_type' => $submit_text_type,
      'article_id' => $article_id,
      'tax' => $tax,
      'payment_currency' => $payment_currency,
      'paypal_email' => $paypal_email,
      'checkout_mode' => $checkout_mode,
	    'paypal_mode' => $paypal_mode,
      'javascript' => $javascript,
      'condition' => $condition,
      'user_id_wd' => $user_id_wd,
      'frontend_submit_fields' => $frontend_submit_fields,
      'frontend_submit_stat_fields' => $frontend_submit_stat_fields,
      'privacy' => $privacy,
      'autogen_layout' => $autogen_layout,
    );

    // Keep the chosen Payment currency value with None Payment method also.
    if ( ($paypal_mode != 1 && $paypal_mode != 2) && $payment_currency == '' ) {
      unset($form_options['payment_currency']);
    }
    $data = array();

    $data['privacy'] = $privacy;
    $data['published'] = $published;
	  $message_id = 2;

    $save = WDW_FM_Library::update_columns_and_json_column( 'formmaker', $data, 'form_options', $form_options, array( 'id' => $id ));
    $save_backup = WDW_FM_Library::update_columns_and_json_column('formmaker_backup', $data, 'form_options', $form_options, array( 'backup_id' => $backup_id ));
    if ( $save !== FALSE && $save_backup !== FALSE) {
      //save theme in backup
      if ( WDFMInstance(self::PLUGIN)->is_free != 2 ) {
        $save_addon = do_action('fm_save_addon_init', $id);
      }
      $this->model->create_js($id);
      $message_id = 8;
    }

	  return $message_id;
  }

  /**
   * Form layout.
   *
   * @param int $id
   */
  public function form_layout( $id = 0 ) {
    $ids = array();
    $types = array();
    $labels = array();

    $row = $this->model->get_row_data( $id );
    if ( empty($row) ) {
      WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array('page' => $this->page, 'task' => 'display'), admin_url('admin.php') ) );
    }
    $fields = explode('*:*new_field*:*', $row->form_fields);
    $fields = array_slice($fields, 0, count($fields) - 1);
    foreach ( $fields as $field ) {
      $temp = explode('*:*id*:*', $field);
      array_push($ids, $temp[0]);
      $temp = explode('*:*type*:*', $temp[1]);
      array_push($types, $temp[0]);
      $temp = explode('*:*w_field_label*:*', $temp[1]);
      array_push($labels, $temp[0]);
    }

    // Set params for view.
    $params = array();
    $params['id'] = $id;
    $params['row'] = $row;
    $params['page'] = $this->page;
    $params['page_url'] = $this->page_url;
    $params['page_title'] = '"'. $row->title . '" ' .  __('layout', WDFMInstance(self::PLUGIN)->prefix);
    $params['back_url'] = add_query_arg( array ('page' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix,'task' => 'edit','current_id' => $id ), admin_url('admin.php'));
    $params['ids'] = $ids;
    $params['types'] = $types;
    $params['labels'] = $labels;
	  $this->view->form_layout($params);
  }

  /**
   * Apply layout.
   *
   * @param int $id
   */
  public function apply_layout( $id = 0 ) {
	  $message = $this->save_db_layout(  $id );
    WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array('page' => $this->page, 'task' => 'form_layout', 'current_id' => $id, 'message' => $message), admin_url('admin.php') ) );
  }

  /**
   * Save db layout.
   *
   * @param  int $id
   * @return int $id_message
   */
  public function save_db_layout( $id = 0 ) {
    $custom_front   = WDW_FM_Library(self::PLUGIN)->get('custom_front', '', false);
    $autogen_layout = WDW_FM_Library(self::PLUGIN)->get('autogen_layout', '0');
    $form_options = array(
      'custom_front' => $custom_front,
      'autogen_layout' => $autogen_layout,
    );
    $update = WDW_FM_Library::update_json_column('formmaker', 'form_options', $form_options, array('id' => $id));
	  if ( $update !== FALSE ) {
      return 1;
    }
    else {
      return 2;
    }
  }

  public function display_options() {
    $id = WDW_FM_Library(self::PLUGIN)->get('current_id', $this->model->get_max_row('formmaker', 'id'), 'intval');
    $params = array();
    $row_data = $this->model->get_row_data( $id );
    if ( empty($row_data) ) {
      WDW_FM_Library(self::PLUGIN)->fm_redirect( add_query_arg( array('page' => $this->page, 'task' => 'display'), admin_url('admin.php') ) );
    }

    $params['row_form'] = $row_data;
    $params['row'] = $this->model->get_display_options($id);
    $params['row'] = WDW_FM_Library::convert_json_options_to_old($params['row'], 'display_options');

    $params['row']->theme = $row_data->theme;
    $params['page_title'] = '"'. $params['row_form']->title . '" ' . __('display options', WDFMInstance(self::PLUGIN)->prefix);
    $params['animation_effects'] = $this->animation_effects;
    $params['display_on_list'] 	= array('everything' => 'All', 'home' => 'Homepage', 'archive' => 'Archives', 'post' => 'Post', 'page' => 'Page');
    $params['posts_and_pages']	= $this->model->fm_posts_query();
    $params['categories'] = $this->model->fm_categories_query();
    $params['selected_categories'] = explode(',', $params['row']->display_on_categories);
    $params['current_categories_array'] = explode(',', $params['row']->current_categories);
    $params['id'] = $id;
    $params['page'] = $this->page;
    $params['page_url']	= $this->page_url;
    $params['back_url'] = add_query_arg( array (
        'page' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix,
        'task' => 'edit',
        'current_id' => $id,
    ), admin_url('admin.php')
    );
    $params['themes'] = $this->model->get_theme_rows_data();
    $params['default_theme'] = $this->model->get_default_theme_id();
    $params['fieldset_id'] = WDW_FM_Library(self::PLUGIN)->get('fieldset_id', 'embedded');
    $this->view->display_options($params);
  }

  public function save_display_options() {
    $message = $this->save_dis_options();
    $page = WDW_FM_Library(self::PLUGIN)->get('page');

    $current_id = WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval');
    WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
        'page' => $page,
        'task' => 'edit',
        'current_id' => $current_id,
        'message' => $message,
    ), admin_url('admin.php')));
  }

  public function apply_display_options($id = 0) {
    $message = $this->save_dis_options();
    $page = WDW_FM_Library(self::PLUGIN)->get('page');
    $fieldset_id = WDW_FM_Library(self::PLUGIN)->get('fieldset_id', 'embedded');
    $current_id = WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval');
    WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
        'page' => $page,
        'task' => 'display_options',
        'current_id' => $id,
        'fieldset_id'=> $fieldset_id,
        'message' => $message,
    ), admin_url('admin.php')));
  }

	/**
	* Remove query for MySQL Mapping.
	* @param int $id
	*/
	public function remove_query( $id = 0 ) {
		$fieldset_id = WDW_FM_Library(self::PLUGIN)->get('fieldset_id', 'general');
    $query_id = WDW_FM_Library(self::PLUGIN)->get('query_id', 0, 'intval');
		$message = 2;
    if ( $this->model->delete_formmaker_query($query_id) ) {
      $message = 3;
    }

		WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
													'page' => $this->page,
													'task' => 'edit',
													'current_id'  => $id,
													'fieldset_id' => $fieldset_id,
													'message' => $message,
												  ), admin_url('admin.php')));
	}

  /**
   * Check if loading_delay or frequency is positive numbers
   *
   * @param int $delay
   * @return int
   */
  public function set_delay_freq_positive_val( $delay = 0 ) {
    if( $delay < 0 ) return 0;
    return $delay;
  }

  public function set_delay_freq_positive_val_or_minus_one( $delay = 0 ) {
    if( $delay < -1 ) return 0;
    return $delay;
  }

  public function save_dis_options() {
    $theme = WDW_FM_Library(self::PLUGIN)->get('theme', $this->model->get_default_theme_id(), 'intval');
    $where = array('form_id' => WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval'));

    $display_options = array(
      'type' => WDW_FM_Library(self::PLUGIN)->get('form_type', 'embedded'),
      'scrollbox_loading_delay' => $this->set_delay_freq_positive_val(WDW_FM_Library(self::PLUGIN)->get('scrollbox_loading_delay', 0, 'intval')),
      'scrollbox_position' => WDW_FM_Library(self::PLUGIN)->get('scrollbox_position', 1, 'intval'),
      'scrollbox_trigger_point' =>WDW_FM_Library(self::PLUGIN)->get('scrollbox_trigger_point', 20, 'intval'),
      'scrollbox_hide_duration' => $this->set_delay_freq_positive_val(WDW_FM_Library(self::PLUGIN)->get('scrollbox_hide_duration', 0, 'intval')),
      'scrollbox_auto_hide' => WDW_FM_Library(self::PLUGIN)->get('scrollbox_auto_hide', 1, 'intval'),
      'scrollbox_closing' => WDW_FM_Library(self::PLUGIN)->get('scrollbox_closing', 1, 'intval'),
      'scrollbox_minimize' => WDW_FM_Library(self::PLUGIN)->get('scrollbox_minimize', 1, 'intval'),
      'scrollbox_minimize_text' => WDW_FM_Library(self::PLUGIN)->get('scrollbox_minimize_text', ''),
      'popover_animate_effect' => WDW_FM_Library(self::PLUGIN)->get('popover_animate_effect', ''),
      'popover_loading_delay' => $this->set_delay_freq_positive_val_or_minus_one(WDW_FM_Library(self::PLUGIN)->get('popover_loading_delay', 0, 'intval')),
      'popover_frequency' => $this->set_delay_freq_positive_val(WDW_FM_Library(self::PLUGIN)->get('popover_frequency', 0, 'intval')),
      'topbar_position' => WDW_FM_Library(self::PLUGIN)->get('topbar_position', 1, 'intval'),
      'topbar_remain_top' => WDW_FM_Library(self::PLUGIN)->get('topbar_remain_top', 1, 'intval'),
      'topbar_closing' => WDW_FM_Library(self::PLUGIN)->get('topbar_closing', 1),
      'topbar_hide_duration' => $this->set_delay_freq_positive_val(WDW_FM_Library(self::PLUGIN)->get('topbar_hide_duration', 0, 'intval')),
      'display_on' => implode(',', WDW_FM_Library(self::PLUGIN)->get('display_on', array())),
      'posts_include' => WDW_FM_Library(self::PLUGIN)->get('posts_include', ''),
      'pages_include' => WDW_FM_Library(self::PLUGIN)->get('pages_include', ''),
      'display_on_categories' => implode(',', WDW_FM_Library(self::PLUGIN)->get('display_on_categories', array())),
      'current_categories' => WDW_FM_Library(self::PLUGIN)->get('current_categories', ''),
      'show_for_admin' => WDW_FM_Library(self::PLUGIN)->get('show_for_admin', 0, 'intval'),
      'hide_mobile' => WDW_FM_Library(self::PLUGIN)->get('hide_mobile', 0, 'intval'),
    );

    $save = WDW_FM_Library::update_columns_and_json_column('formmaker_display_options', $where, 'display_options', $display_options, $where, 'replace');

    //success
    if ( $save !== FALSE ) {

      $this->model->update_data('formmaker_backup', array(
          'type' => $display_options['type'],
          'theme' => $theme,
      ), array( 'id' => $where['form_id'], 'cur' => 1 ));

      $this->model->update_data('formmaker', array(
          'type' => $display_options['type'],
          'theme' => $theme,
      ), array( 'id' => $where['form_id'] ));

      $this->model->create_js($where['form_id']);

      return 8;
    }
    else {
      return 2;
    }
  }

  public function save() {
    $message = $this->save_db();
    $page = WDW_FM_Library(self::PLUGIN)->get('page');
    WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
                                                'page' => $page,
                                                'task' => 'display',
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  /**
   * Add form options field if it is empty
   * usable for old forms
   *
   * @param $form_id
  */
  public function set_form_options_for_old( $form_id ) {
    $result = $this->model->get_row_data( $form_id );
    $res = (array) $result;
    if( !empty($res['form_options']) ) {
      return;
    }
    $form_options = array(
        'savedb' => $res['savedb'],
        'requiredmark' => $res['requiredmark'],
        'submissions_limit' => $res['submissions_limit'],
        'submissions_limit_text' => $res['submissions_limit_text'],
        'save_uploads' => $res['save_uploads'],
        'submit_text' => $res['submit_text'],
        'url' => $res['url'],
        'submit_text_type' => $res['submit_text_type'],
        'article_id' => $res['article_id'],
        'tax' => $res['tax'],
        'payment_currency' => $res['payment_currency'],
        'paypal_email' => $res['paypal_email'],
        'checkout_mode' => $res['checkout_mode'],
        'paypal_mode' => $res['paypal_mode'],
        'javascript' => $res['javascript'],
        'condition' => $res['condition'],
        'user_id_wd' => $res['user_id_wd'],
        'frontend_submit_fields' => $res['frontend_submit_fields'],
        'frontend_submit_stat_fields' => $res['frontend_submit_stat_fields'],
        'autogen_layout' => $res['autogen_layout'],
        'custom_front' => $res['custom_front'],
        'mail' => $res['mail'],
        'sendemail' => $res['sendemail'],
        'from_mail' => $res['from_mail'],
        'from_name' => $res['from_name'],
        'reply_to' => $res['reply_to'],
        'send_to' => $res['send_to'],
        'mail_from_user' => $res['mail_from_user'],
        'mail_from_name_user' => $res['mail_from_name_user'],
        'reply_to_user' => $res['reply_to_user'],
        'mail_cc' => $res['mail_cc'],
        'mail_cc_user' => $res['mail_cc_user'],
        'mail_bcc' => $res['mail_bcc'],
        'mail_bcc_user' => $res['mail_bcc_user'],
        'mail_subject' => $res['mail_subject'],
        'mail_subject_user' => $res['mail_subject_user'],
        'mail_mode' => $res['mail_mode'],
        'mail_mode_user' => $res['mail_mode_user'],
        'mail_send_email_payment' => $res['mail_send_email_payment'],
        'mail_send_payment_info' => $res['mail_send_payment_info'],
        'mail_send_email_payment_user' => $res['mail_send_email_payment_user'],
        'mail_attachment' => $res['mail_attachment'],
        'mail_attachment_user' => $res['mail_attachment_user'],
        'mail_emptyfields' => $res['mail_emptyfields'],
        'mail_verify' => $res['mail_verify'],
        'mail_verify_expiretime' => $res['mail_verify_expiretime'],
        'mail_verification_post_id' => $res['mail_verification_post_id'],
        'script_mail' => $res['script_mail'],
        'script_mail_user' => $res['script_mail_user'],
    );
    $form_options = json_encode($form_options);
    $this->model->add_form_options($form_options, $form_id);
  }

  public function apply() {
    $tabs_loaded = json_decode(WDW_FM_Library(self::PLUGIN)->get('fm_tabs_loaded', '[]', false));
    $message = $this->save_db();
    $current_id = WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval');
    if ( !$current_id ) {
      $current_id = $this->model->get_max_row('formmaker', 'id', 'intval');
    }
    $backup_id = $this->model->get_max_row('formmaker_backup', 'backup_id', 'intval');
    if (array_search('form_options_tab', $tabs_loaded) !== false) {
        $this->save_db_form_options($current_id, $backup_id);
    } else {
        /* Case is working when settings tab not opened and form_options field json data is empty
         * For old forms where form_options field is empty yet
        */
        $this->set_form_options_for_old( $current_id );
    }
    if (array_search('form_display_tab', $tabs_loaded) !== false) {
      $this->save_dis_options();
    }
    if (array_search('form_layout_tab', $tabs_loaded) !== false) {
      $this->save_db_layout($current_id);
    }
    if (array_search('form_email_options_tab', $tabs_loaded) !== false) {
      $this->save_db_email_options($current_id, $backup_id);
    }
    $active_tab = WDW_FM_Library(self::PLUGIN)->get('fm_tab_active', 0, 'intval');
    $fieldset_id = WDW_FM_Library(self::PLUGIN)->get('fieldset_id', 'general');
    $page = WDW_FM_Library(self::PLUGIN)->get('page');
    WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
      'page' => $page,
      'task' => 'edit',
      'current_id' => $current_id,
      'message' => $message,
      'tab' => $active_tab,
      'fieldset_id' => $fieldset_id,
    ), admin_url('admin.php')));
  }

  public function save_db() {
    $javascript = "// Occurs before the form is loaded\r\nfunction before_load() {	\r\n}	\r\n// Occurs just before submitting  the form\r\nfunction before_submit() {\r\n	// IMPORTANT! If you want to interrupt (stop) the submitting of the form, this function should return true. You don\'t need to return any value if you don\'t want to stop the submission.\r\n}	\r\n// Occurs just before resetting the form\r\nfunction before_reset() {	\r\n}\r\n// Occurs after form is submitted and reloaded\r\nfunction after_submit() {\r\n  \r\n}";
    $id = WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval');
    $title = WDW_FM_Library(self::PLUGIN)->get('title', '');

    $form_front = WDW_FM_Library(self::PLUGIN)->get('form_front', '', false);
    $form_front = str_replace('onerror', '', html_entity_decode(base64_decode($form_front)));
    $form_front = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $form_front);
    $theme = WDW_FM_Library(self::PLUGIN)->get('theme', $this->model->get_current_theme_id($id), 'intval');
    $sortable = WDW_FM_Library(self::PLUGIN)->get('sortable', 0);
    $counter = WDW_FM_Library(self::PLUGIN)->get('counter', 0, 'intval');
    $label_order = WDW_FM_Library(self::PLUGIN)->get('label_order', '');
    $label_order = html_entity_decode(base64_decode($label_order));
    $label_order = str_replace('onerror', '', $label_order);
    $label_order = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $label_order);

    $pagination = WDW_FM_Library(self::PLUGIN)->get('pagination', '');
    $show_title = WDW_FM_Library(self::PLUGIN)->get('show_title', '');
    $show_numbers = WDW_FM_Library(self::PLUGIN)->get('show_numbers', '');
    $public_key = WDW_FM_Library(self::PLUGIN)->get('public_key', '');
    $private_key = WDW_FM_Library(self::PLUGIN)->get('private_key', '');
    $recaptcha_score = WDW_FM_Library(self::PLUGIN)->get('recaptcha_score', '');
    $recaptcha_theme = WDW_FM_Library(self::PLUGIN)->get('recaptcha_theme', '');

    $label_order_current = WDW_FM_Library(self::PLUGIN)->get('label_order_current', '');
    $label_order_current = html_entity_decode(base64_decode($label_order_current));
    $label_order_current = str_replace('onerror', '', $label_order_current);
    $label_order_current = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $label_order_current);

    $form_fields = WDW_FM_Library(self::PLUGIN)->get('form_fields', '', false);
    $form_fields = html_entity_decode(base64_decode($form_fields));
    $form_fields = str_replace('onerror', '', $form_fields);
    $form_fields = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $form_fields);

    $header_title = WDW_FM_Library(self::PLUGIN)->get('header_title', '');
    $header_description = WDW_FM_Library(self::PLUGIN)->get('header_description', '', FALSE);
    $header_image_url = WDW_FM_Library(self::PLUGIN)->get('header_image_url', '');
    $header_image_animation = WDW_FM_Library(self::PLUGIN)->get('header_image_animation', '');
    $header_hide_image = WDW_FM_Library(self::PLUGIN)->get('header_hide_image', 0);
    $header_hide = WDW_FM_Library(self::PLUGIN)->get('header_hide', 1);
    $type = WDW_FM_Library(self::PLUGIN)->get('form_type', 'embedded');
    $scrollbox_minimize_text = $header_title ? $header_title : 'The form is minimized.';
    if ( $id != 0 ) {
      $save = $this->model->update_data('formmaker', array(
        'title' => $title,
        'form_front' => $form_front,
        'sortable' => $sortable,
        'counter' => $counter,
        'label_order' => $label_order,
        'label_order_current' => $label_order_current,
        'pagination' => $pagination,
        'show_title' => $show_title,
        'show_numbers' => $show_numbers,
        'public_key' => $public_key,
        'private_key' => $private_key,
        'recaptcha_theme' => $recaptcha_theme,
        'form_fields' => $form_fields,
        'header_title' => $header_title,
        'header_description' => $header_description,
        'header_image_url' => $header_image_url,
        'header_image_animation' => $header_image_animation,
        'header_hide_image' => $header_hide_image,
        'header_hide' => $header_hide,
      ), array( 'id' => $id ));
    }
    else {
      $form_options = array(
        'savedb' => 1,
        'requiredmark' => '*',
        'submissions_limit' => 0,
        'submissions_limit_text' => __('The limit of submissions for this form has been reached.', WDFMInstance(self::PLUGIN)->prefix),
        'save_uploads' => 1,
        'submit_text' => '',
        'url' => '',
        'submit_text_type' => 1,
        'article_id' => 0,
        'tax' => 0,
        'payment_currency' => '',
        'paypal_email' => '',
        'checkout_mode' => 'testmode',
        'paypal_mode' => 0,
        'javascript' => $javascript,
        'condition' => '',
        'user_id_wd' => 'administrator,',
        'frontend_submit_fields' => '',
        'frontend_submit_stat_fields' => '',
        'autogen_layout' => 1,
        'custom_front' => '',
        'mail' => '{adminemail}',
        'sendemail' => 1,
        'from_mail' => '',
        'from_name' => '',
        'reply_to' => '',
        'send_to' => '',
        'mail_from_user' => '',
        'mail_from_name_user' => '',
        'reply_to_user' => '',
        'mail_cc' => '',
        'mail_cc_user' => '',
        'mail_bcc' => '',
        'mail_bcc_user' => '',
        'mail_subject' => '',
        'mail_subject_user' => '',
        'mail_mode' => 1,
        'mail_mode_user' => 1,
        'mail_send_email_payment' => 1,
        'mail_send_payment_info' => 1,
        'mail_send_email_payment_user' => 1,
        'mail_attachment' => 1,
        'mail_attachment_user' => 1,
        'mail_emptyfields' => 0,
        'mail_verify' => 0,
        'mail_verify_expiretime' => 0,
        'mail_verification_post_id' => 0,
        'script_mail' => '{all}',
        'script_mail_user' => '{all}',
      );
      $form_options = json_encode($form_options);

      $theme = WDW_FM_Library(self::PLUGIN)->get('theme', $this->model->get_default_theme_id(), 'intval');
      $this->model->insert_data_to_db('formmaker', array(
        'title' => $title,
        'type' => $type,
        'form_front' => $form_front,
        'theme' => $theme,
        'counter' => $counter,
        'label_order' => $label_order,
        'pagination' => $pagination,
        'show_title' => $show_title,
        'show_numbers' => $show_numbers,
        'public_key' => $public_key,
        'private_key' => $private_key,
        'recaptcha_theme' => $recaptcha_theme,
        'label_order_current' => $label_order_current,
        'form_fields' => $form_fields,
        'sortable' => $sortable,
        'header_title' => $header_title,
        'header_description' => $header_description,
        'header_image_url' => $header_image_url,
        'header_image_animation' => $header_image_animation,
        'header_hide_image' => $header_hide_image,
        'header_hide' => $header_hide,
        'privacy' => '',
        'form_options' => $form_options
      ));
      $id = (int) $this->model->get_max_row('formmaker', 'id');
      if (WDFMInstance(self::PLUGIN)->is_free == 2) {
        update_option('contact_form_forms', ((get_option('contact_form_forms')) ? (get_option('contact_form_forms')) . ',' . $id : $id));
      }
      $display_options = array(
        'type' => $type,
        'scrollbox_loading_delay' => 0,
        'scrollbox_position' => 1,
        'scrollbox_trigger_point' => 20,
        'scrollbox_hide_duration' => 0,
        'scrollbox_auto_hide' => 1,
        'scrollbox_closing' => 1,
        'scrollbox_minimize' => 1,
        'scrollbox_minimize_text' => $scrollbox_minimize_text,
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
      $display_options = json_encode($display_options);
      $this->model->insert_data_to_db('formmaker_display_options', array(
        'form_id' => $id,
        'display_options' => $display_options,
      ));

      $this->model->insert_data_to_db('formmaker_views', array(
        'form_id' => $id,
        'views' => 0,
      ));
    }

    $backup_id = WDW_FM_Library(self::PLUGIN)->get('backup_id', '', 'intval');
    if ( $backup_id ) {
      // Set cur 0 before add new current
      $this->model->update_data('formmaker_backup', array( 'cur' => 0 ), array( 'id' => $id ));
      // Get form_fields, form_front
      $row1 = $this->model->select_rows("get_row", array(
        "selection" => "form_fields, form_front",
        "table" => "formmaker_backup",
        "where" => "backup_id = " . $backup_id,
      ));
      if ( $row1->form_fields == $form_fields and $row1->form_front == $form_front ) {
        $save = $this->model->update_data('formmaker_backup', array(
          'cur' => 1,
          'title' => $title,
          'type' => $type,
          'form_front' => $form_front,
          'sortable' => $sortable,
          'counter' => $counter,
          'label_order' => $label_order,
          'label_order_current' => $label_order_current,
          'pagination' => $pagination,
          'show_title' => $show_title,
          'show_numbers' => $show_numbers,
          'public_key' => $public_key,
          'private_key' => $private_key,
          'recaptcha_theme' => $recaptcha_theme,
          'form_fields' => $form_fields,
          'header_title' => $header_title,
          'header_description' => $header_description,
          'header_image_url' => $header_image_url,
          'header_image_animation' => $header_image_animation,
          'header_hide_image' => $header_hide_image,
          'header_hide' => $header_hide,
        ), array( 'backup_id' => $backup_id ));
        if ( $save !== FALSE ) {
          $this->model->create_js($id);

          return 1;
        }
        else {
          return 2;
        }
      }
    }
    $this->model->update_data('formmaker_backup', array( 'cur' => 0 ), array( 'id' => $id ));
    $fm_cur_date = current_time('timestamp');


    $form_options = array(
      'savedb' => 1,
      'requiredmark' => '*',
      'submissions_limit' => 0,
      'submissions_limit_text' => __('The limit of submissions for this form has been reached.', WDFMInstance(self::PLUGIN)->prefix),
      'save_uploads' => 1,
      'submit_text' => '',
      'url' => '',
      'submit_text_type' => 1,
      'article_id' => 0,
      'tax' => 0,
      'payment_currency' => '',
      'paypal_email' => '',
      'checkout_mode' => 'testmode',
      'paypal_mode' => 0,
      'javascript' => $javascript,
      'condition' => '',
      'user_id_wd' => 'administrator,',
      'frontend_submit_fields' => '',
      'frontend_submit_stat_fields' => '',
      'autogen_layout' => 1,
      'custom_front' => '',
      'mail' => '{adminemail}',
      'sendemail' => 1,
      'from_mail' => '',
      'from_name' => '',
      'reply_to' => '',
      'send_to' => '',
      'mail_from_user' => '',
      'mail_from_name_user' => '',
      'reply_to_user' => '',
      'mail_cc' => '',
      'mail_cc_user' => '',
      'mail_bcc' => '',
      'mail_bcc_user' => '',
      'mail_subject' => '',
      'mail_subject_user' => '',
      'mail_mode' => 1,
      'mail_mode_user' => 1,
      'mail_send_email_payment' => 1,
      'mail_send_payment_info' => 1,
      'mail_send_email_payment_user' => 1,
      'mail_attachment' => 1,
      'mail_attachment_user' => 1,
      'mail_emptyfields' => 0,
      'mail_verify' => 0,
      'mail_verify_expiretime' => 0,
      'mail_verification_post_id' => 0,
      'script_mail' => '{all}',
      'script_mail_user' => '{all}',
    );
    $form_options = json_encode($form_options);

    $message_id = $this->model->insert_data_to_db('formmaker_backup', array(
      'cur' => 1,
      'id' => $id,
      'title' => $title,
      'type' => $type,
      'form_front' => $form_front,
      'theme' => $theme,
      'counter' => $counter,
      'published' => 1,
      'label_order' => $label_order,
      'pagination' => $pagination,
      'show_title' => $show_title,
      'show_numbers' => $show_numbers,
      'public_key' => $public_key,
      'private_key' => $private_key,
      'recaptcha_theme' => $recaptcha_theme,
      'label_order_current' => $label_order_current,
      'form_fields' => $form_fields,
      'sortable' => $sortable,
      'header_title' => $header_title,
      'header_description' => $header_description,
      'header_image_url' => $header_image_url,
      'header_image_animation' => $header_image_animation,
      'header_hide_image' => $header_hide_image,
      'header_hide' => $header_hide,
      'privacy' => '',
      'date' => $fm_cur_date,
      'form_options' => $form_options
    ));
    if ( $message_id !== FALSE ) {
      $this->model->create_js($id);
      return 1;
    }
    else {
      return 2;
    }
  }

  public function fm_live_search() {
    $search_string	= WDW_FM_Library(self::PLUGIN)->get( 'pp_live_search' );
    $post_type		= WDW_FM_Library(self::PLUGIN)->get( 'pp_post_type', 'any' );
    $full_content	= WDW_FM_Library(self::PLUGIN)->get( 'pp_full_content', 'true' );
    $args['s'] = $search_string;
    $results = $this->fm_posts_query($args, $post_type);
    $output = '<ul class="pp_search_results">';
    if ( empty($results) ) {
      $output .= sprintf('<li class="pp_no_res">%1$s</li>', esc_html__('No results found', 'fm-text'));
    }
    else {
      foreach ( $results as $single_post ) {
        $output .= sprintf('<li data-post_id="%2$s">[%3$s] - %1$s</li>', esc_html($single_post['title']), esc_attr($single_post['id']), esc_html($single_post['post_type']));
      }
    }
    $output .= '</ul>';
    die($output);
  }

  /**
   * @param array $args
   * @param string $include_post_type
   * @return array|bool
   */
  public function fm_posts_query( $args = array(), $include_post_type = '' ) {
    if ( 'only_pages' === $include_post_type ) {
      $post_type = array('page');
    }
    elseif ( 'any' === $include_post_type || 'only_posts' === $include_post_type ) {
	  $post_type = array('post');
    }
    else {
      $post_type = $include_post_type;
    }
    $query = array(
      'post_type' => $post_type,
      'suppress_filters' => TRUE,
      'update_post_term_cache' => FALSE,
      'update_post_meta_cache' => FALSE,
      'post_status' => 'publish',
      'posts_per_page' => -1,
    );
    if ( isset($args['s']) ) {
      $query['s'] = $args['s'];
    }
    $get_posts = new WP_Query;
    $posts = $get_posts->query($query);
    if ( !$get_posts->post_count ) {
      return FALSE;
    }
    $results = array();
    foreach ( $posts as $post ) {
      $results[] = array(
        'id' => intval($post->ID),
        'title' => trim(esc_html(strip_tags(get_the_title($post)))),
        'post_type' => $post->post_type,
      );
    }
    wp_reset_postdata();

    return $results;
  }

  /**
   * @param int $id
   * @return int
   */
  public function update_form_title( $id = 0 ) {
    $page = WDW_FM_Library(self::PLUGIN)->get('page');
    $form_name = WDW_FM_Library(self::PLUGIN)->get('form_name', '');

    // check  id for db
    if(isset($id) && $id != "") {
      $id = intval($id);
      $update = $this->model->update_data( "forms", array('title' => $form_name,), array('id' => $id) );
      if( !$update ){
        $message = 2;
        WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
            'page' => $page,
            'task' => 'display',
            'message' => $message,
        ), admin_url('admin.php')));
      }

    }
    else { // return message Failed
      $message = 2;
      WDW_FM_Library(self::PLUGIN)->fm_redirect(add_query_arg(array(
          'page' => $page,
          'task' => 'display',
          'message' => $message,
      ), admin_url('admin.php')));
    }

    return $message = 1;
  }

	/**
	* Get stripe addon.
	*
	* @param  int $id
  * @return array $data
	*/
	private function get_stripe_addon( $id = 0 ) {
		return  apply_filters('fm_stripe_display_init', array('form_id' => $id) );
	}
}
