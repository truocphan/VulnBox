<?php

class FMViewManage_fm extends FMAdminView {
  /**
   * FMViewManage_fm constructor.
   */
  private $fm_nonce = null;
  public function __construct() {
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;

    $this->fm_nonce = wp_create_nonce('fm_ajax_nonce');

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-icons');
    if ( $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-admin');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '_jquery.ui.touch-punch.min');
    }
  }

  /**
   * Display page.
   *
   * @param array $params
   */
  public function display( $params = array() ) {
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    if ( ! $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage');
    }
    $this->import_popup_div();
    ob_start();
    echo $this->body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => 'manage_form',
      'class' => 'wd-form',
      'action' => add_query_arg(array( 'page' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix ), 'admin.php'),
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
    $page = $params['page'];
    $actions = $params['actions'];
    $form_preview_link = $params['form_preview_link'];
    $rows_data = $params['rows_data'];
    $total = $params['total'];
    $order = $params['order'];
    $orderby = $params['orderby'];
    $items_per_page = $params['items_per_page'];

    $page_url = add_query_arg(array(
                                'page' => $page,
                                WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce),
                              ), admin_url('admin.php'));
    echo $this->title(array(
                        'title' => __('Forms', WDFMInstance(self::PLUGIN)->prefix),
                        'title_class' => 'wd-header',
                        'add_new_button' => array(
                          'href' => add_query_arg(array( 'page' => $page, 'task' => 'add' ), admin_url('admin.php')),
                        ),
                      ));
    echo $this->search();
    ?>
    <div class="tablenav top">
      <?php
      echo $this->bulk_actions($actions);
      if (WDFMInstance(self::PLUGIN)->is_free != 2) {
        echo $this->exp_imp_buttons();
      }
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
          <?php echo WDW_FM_Library(self::PLUGIN)->ordering('title', $orderby, $order, __('Title', WDFMInstance(self::PLUGIN)->prefix), $page_url, 'col_title column-primary wd-left'); ?>
          <?php echo WDW_FM_Library(self::PLUGIN)->ordering('type', $orderby, $order, __('Type', WDFMInstance(self::PLUGIN)->prefix), $page_url, 'col_type wd-left'); ?>
          <th class="col_count wd-left"><?php _e('Submissions', WDFMInstance(self::PLUGIN)->prefix); ?></th>
          <?php echo WDW_FM_Library(self::PLUGIN)->ordering('id', $orderby, $order, __('Shortcode', WDFMInstance(self::PLUGIN)->prefix), $page_url, 'wd-center'); ?>
          <th class="col_function wd-center"><?php _e('PHP function', WDFMInstance(self::PLUGIN)->prefix); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ( $rows_data ) {
          foreach ( $rows_data as $row_data ) {
            $alternate = (!isset($alternate) || $alternate == '') ? 'class="alternate"' : '';
            $old = isset($row_data->form) && ($row_data->form != '');

            $edit_url = add_query_arg(array( 'page' => $page, 'task' => 'edit', 'current_id' => $row_data->id ), admin_url('admin.php'));
            $duplicate_url = add_query_arg(array('task' => 'duplicate', 'current_id' => $row_data->id), $page_url);
            $publish_url = add_query_arg(array('task' => ($row_data->published ? 'unpublish' : 'publish'), 'current_id' => $row_data->id), $page_url);
            $delete_url = add_query_arg(array('task' => 'delete', 'current_id' => $row_data->id), $page_url);
            $preview_url = add_query_arg( array('wdform_id' => $row_data->id), $form_preview_link );
            ?>
            <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
              <th class="check-column">
                <input id="check_<?php echo $row_data->id; ?>" name="check[<?php echo $row_data->id; ?>]" type="checkbox" class="form_title"  data-id="<?php echo $row_data->id; ?>" />
              </th>
              <td class="column-primary" data-colname="<?php _e('Title', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <strong>
                  <?php
                  if ( !$old ) {
                   ?>
                  <a href="<?php echo $edit_url; ?>">
                    <?php echo $row_data->title; ?>
                  </a>
                    <?php
                  }
                  else {
                    echo $row_data->title;
                  }
                  ?>
                  <?php
                  if ( !$row_data->published ) {
                    ?>
                    —
                    <span class="post-state"><?php _e('Unpublished', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                    <?php
                  }
                  ?>
                </strong>
                <div class="row-actions">
                  <?php
                  if ( !$old ) {
                    ?>
                  <span>
                    <a href="<?php echo $edit_url; ?>"><?php _e('Edit', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                    |
                  </span>
                    <?php
                  }
                  ?>
                  <span>
                    <a href="<?php echo $duplicate_url; ?>"><?php _e('Duplicate', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                    |
                  </span>
                  <span>
                    <a href="<?php echo $publish_url; ?>"><?php echo ($row_data->published ? __('Unpublish', WDFMInstance(self::PLUGIN)->prefix) : __('Publish', WDFMInstance(self::PLUGIN)->prefix)); ?></a>
                    |
                  </span>
                  <span class="trash">
                    <a onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', WDFMInstance(self::PLUGIN)->prefix)); ?>')) {return false;}" href="<?php echo $delete_url; ?>"><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                    |
                  </span>
                  <span>
                   <a href="<?php echo $preview_url; ?>" target="_blank"><?php _e('Preview', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                  </span>
                </div>
                <button class="toggle-row" type="button">
                  <span class="screen-reader-text"><?php _e('Show more details', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                </button>
              </td>
              <td data-colname="<?php _e('Type', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <?php echo ucfirst($row_data->type); ?>
                <div class="row-actions">
                  <span>
                    <a href="<?php echo add_query_arg(array('tab' => '3'), $edit_url); ?>"><?php _e('Set display options', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                  </span>
                </div>
              </td>
              <td data-colname="<?php _e('Submissions', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <?php
                if ($row_data->submission_count != 0) {
                  ?>
                <a title="<?php _e('View sumbissions', WDFMInstance(self::PLUGIN)->prefix); ?>" target="_blank" href="<?php echo add_query_arg(array(
                                                                    'page' => 'submissions' . WDFMInstance(self::PLUGIN)->menu_postfix,
                                                                    'task' => 'display',
                                                                    'current_id' => $row_data->id,
                                                                  ), admin_url('admin.php')); ?>">
                  <?php
                }
                echo $row_data->submission_count;
                if ($row_data->submission_count != 0) {
                  ?>
                </a>
                  <?php
                }
              ?>
              </td>
              <td data-colname="<?php _e('Shortcode', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <input type="text" value='<?php echo (WDFMInstance(self::PLUGIN)->is_free == 2 ? '[wd_contact_form id="' . $row_data->id . '"]' : '[Form id="' . $row_data->id . '"]'); ?>' onclick="fm_select_value(this)" size="12" readonly="readonly"  class="fm_shortcode" />
              </td>
              <td data-colname="<?php _e('PHP function', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <input type="text" value='<?php echo (WDFMInstance(self::PLUGIN)->is_free == 2 ? '&#60;?php if( function_exists("wd_contact_form_maker") ) { wd_contact_form_maker(' . $row_data->id . ', "' . $row_data->type . '"); } ?&#62;' : '&#60;?php if( function_exists("wd_form_maker") ) { wd_form_maker(' . $row_data->id . ', "' . $row_data->type . '"); } ?&#62;');  ?>' onclick="fm_select_value(this)"  readonly="readonly" class="fm_php_function" />
              </td>
            </tr>
            <?php
          }
        }
        else {
          echo WDW_FM_Library(self::PLUGIN)->no_items('forms');
        }
        ?>
      </tbody>
    </table>
	<?php
	}
	
	function exp_imp_buttons() {
		$buttons_action = apply_filters('imp_exp_buttons', array());
		$list = "<div class='ei_buttons'>";

		foreach( $buttons_action as $buttons_action_key => $buttons_action_value ) {
			$list .= '<a '.$buttons_action_value.' >' . $buttons_action_key . '</a>';
		}
		$list .= "</div>";
		return $list;
	}

  /**
   * Edit.
   *
   * @param array $params
   */
	public function edit( $params = array() ) {
		$fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
		// TODO: Change this function to standard.
		echo $this->topbar();

		wp_enqueue_style('thickbox');
		wp_enqueue_style('jquery-ui-tooltip');
		if ( $fm_settings['fm_developer_mode'] ) {
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-phone_field_css');
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-jquery-ui');
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-codemirror');
			if ( $fm_settings['fm_advanced_layout'] ) {
				wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-layout');
			}
		}
		else {
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage-edit');
		}
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-spinner');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-tooltip');
		if ( function_exists('wp_add_inline_script') ) { // Since Wordpress 4.5.0
		  wp_add_inline_script('jquery-ui-datepicker', WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker());
		}
		else {
		  echo '<script>' . WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker() . '</script>';
		}
		wp_enqueue_media();
		wp_enqueue_script('google-maps');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-codemirror');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-formatting');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-clike');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-css');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-javascript');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-xml');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-php');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-htmlmixed');
		if ( $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-gmap_form');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-signaturepad');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-phone_field');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-formmaker_div');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage-edit');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-add-fields');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-form-options');
			if ( $fm_settings['fm_advanced_layout'] ) {
				wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-form-advanced-layout');
			}
		}
		else {
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage-edit');
		}
		// enqueue add-ons style/scripts
		do_action('fm_admin_print_scripts');

		$id = $params['id'];
		$row = $params['row'];
		$page_title = $params['page_title'];
		$page_url = $params['page_url'];

		$themes 	= $params['themes'];
		$default_theme = $params['default_theme'];
		$labels = $params['labels'];
		$form_preview_link = $params['form_preview_link'];
		$animation_effects = $params['animation_effects'];

		$stripe_addon = $params['stripe_addon'];
		$form_active_tab = WDW_FM_Library(self::PLUGIN)->get('tab', '0', 'intval');
		$fieldset_id = WDW_FM_Library(self::PLUGIN)->get( 'fieldset_id', 'general' );
		?>
    <form class="wrap" id="manage_form" method="post" autocomplete="off" action="admin.php?page=manage<?php echo WDFMInstance(self::PLUGIN)->menu_postfix; ?>">
      <?php
      // Generate message container by message id or directly by message.

      $message_id = WDW_FM_Library(self::PLUGIN)->get('message', 0);
      $message = WDW_FM_Library(self::PLUGIN)->get('msg', '');
      if( isset($params['revisions_status']) && $params['revisions_status'] ) {
        if ( $params['revision_date'] == 0 ) {
          $rev_date = '';
        } else {
          $rev_date = 'at ' . date('Y-m-d H:i:s', $params['revision_date']);
        }
        $message = sprintf(__("You need to click the \"Restore this revision\" button to save the changes. Revision number %u %s", WDFMInstance(self::PLUGIN)->prefix), $params['rev_index'], $rev_date);
        echo WDW_FM_Library(self::PLUGIN)->message_id( 0, $message, 'notice notice-warning wd-notice' );

      } else {
        echo WDW_FM_Library(self::PLUGIN)->message_id($message_id, $message);
      }
      ?>
      <?php wp_nonce_field(WDFMInstance(self::PLUGIN)->nonce, WDFMInstance(self::PLUGIN)->nonce); ?>
      <h2 class="fm-h2-message"></h2>
      <div class="fm-page-header">
        <div class="wd-page-title wd-header">
          <h1 class="wp-heading-inline"><?php _e('Form Title', WDFMInstance(self::PLUGIN)->prefix); ?></h1>
          <input id="title" name="title" value="<?php echo esc_attr($row->title); ?>" data-initial-value="<?php echo esc_attr($row->title); ?>" class="fm-check-change" type="text" />
          <div class="fm-page-actions">
            <?php
            if ( isset($params['revisions_total']) && $params['revisions_total'] > 1 ) {
              ?>
              <button class="button button-large button_revisions" href="#" onclick="fm_popup_toggle('fm_revision_popup_container'); return false;"><?php _e('Revisions', WDFMInstance(self::PLUGIN)->prefix); ?></button>
              <?php
            }
            /* onclick function might seem strange, but it is required to submit the form when there is an html validation error to display error messages and avoid form corruption */
            ?>
            <button class="button button-primary button-large" onclick="if ( !jQuery('#manage_form')[0].checkValidity() ) return true; if (fm_check_required('title', '<?php _e('Form Title', WDFMInstance(self::PLUGIN)->prefix); ?>') || !wd_fm_apply_options() || <?php echo ( ( isset($fm_settings['fm_advanced_layout']) && $fm_settings['fm_advanced_layout'] ) ? '!fm_apply_advanced_layout() ||' : '' );?> !FormManageSubmitButton()) { return false;}; fm_set_input_value('task', 'apply');">
              <?php
              if ( $row->title && isset($params['revisions_total']) && !$params['revisions_status'] ) {
                _e('Update', WDFMInstance(self::PLUGIN)->prefix);
              } else if ( isset($params['revisions_total']) && $params['revisions_status'] ) {
                _e('Restore this revision', WDFMInstance(self::PLUGIN)->prefix);
              } else {
                _e('Publish', WDFMInstance(self::PLUGIN)->prefix);
              }
              ?>
            </button>
            <span class="fm_vr"></span>
            <button class="button preview-button button-large"<?php if (!$row->title) echo ' disabled="disabled"' ?> <?php echo ($row->title) ? 'onclick="window.open(\''. add_query_arg( array('wdform_id' => $id), $form_preview_link ) .'\', \'_blank\'); return false;"' : ''; ?>><?php _e('Preview', WDFMInstance(self::PLUGIN)->prefix); ?></button>
          </div>
        </div>
        <div class="fm-clear"></div>
      </div>
      <div id="fm-tabs">
        <input type="hidden" id="fm_tabs_loaded" name="fm_tabs_loaded" value="[]" />
        <input type="hidden" id="fm_tab_active" name="fm_tab_active" value="<?php echo esc_attr($form_active_tab); ?>" />
        <ul class="fm-edit-tabs">
          <li id="form_edit_tab" data-help-url="360015244232-Creating-a-Form-on-WordPress" ><a href="#form_edit_tab_content"><?php _e('Fields', WDFMInstance(self::PLUGIN)->prefix); ?></a>
          </li>
          <li id="form_header_tab" data-help-url = "360023072811-Editing-the-Form-Header" ><a href="#form_header_tab_content"><?php _e('Form Header', WDFMInstance(self::PLUGIN)->prefix); ?></a></li>

          <?php if( $id ) { ?>
            <li id="form_email_options_tab" data-help-url="360016082671-Email-Options" ><a href="<?php echo add_query_arg(array('action' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix, 'task' => 'email_options', 'current_id' => $id, 'nonce' => $this->fm_nonce), admin_url('admin-ajax.php')) ?>"><?php _e('Email Options', WDFMInstance(self::PLUGIN)->prefix); ?></a></li>
            <li id="form_display_tab" data-help-url="360015862692-Form-Appearance-and-Publishing" ><a href="<?php echo add_query_arg(array('action' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix, 'task' => 'display_options', 'current_id' => $id, 'nonce' => $this->fm_nonce), admin_url('admin-ajax.php')) ?>"><?php _e('Appearance', WDFMInstance(self::PLUGIN)->prefix); ?></a></li>
            <?php if (!empty($params['advanced_layout_url'])) { ?>
              <li id="form_layout_tab" data-help-url="360015244232-Creating-a-Form-on-WordPress" ><a href="<?php echo add_query_arg(array('action' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix, 'task' => 'form_layout', 'current_id' => $id, 'nonce' => $this->fm_nonce), admin_url('admin-ajax.php')) ?>"><?php _e('Layout', WDFMInstance(self::PLUGIN)->prefix); ?></a></li>
            <?php } ?>
          <?php } ?>
          <?php if( $id ) { ?>
            <li id="form_options_tab" data-help-url="360015862812-Settings-General-Options" ><a href="<?php echo add_query_arg(array('action' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix, 'task' => 'form_options', 'current_id' => $id, 'fieldset_id' => $fieldset_id, 'nonce' => $this->fm_nonce), admin_url('admin-ajax.php')) ?>"><span class="tab-label"><?php _e('Settings', WDFMInstance(self::PLUGIN)->prefix); ?></span></a></li>
          <?php } ?>
        </ul>
        <div id="form_edit_tab_content">
          <?php echo $this->add_fields($params); ?>
          <?php echo $this->limitation_alert(); ?>
          <?php if (!function_exists('the_editor')) { ?>
            <iframe id="tinymce" style="display: none;"></iframe>
          <?php } ?>
          <div id="fm_delete_page_popup_container" class="hidden fm_popup_container">
            <div class="fm-popup-overlay" onclick="fm_popup_toggle('fm_delete_page_popup_container'); return false;"></div>
            <div id="fm-delete-page-content" class="fm-popup-wrap">
              <input type="hidden" id="fm_delete_page_id" value="" />
              <div class="fm-alert-header">
                <label><?php _e('You are about to delete a page from your form.', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <label><?php _e('Do you want to also Remove all the fields on that page?', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <label><?php _e('If you decide to preserve the fields, they will be moved to the previous page.', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              </div>
              <div class="fm-alert-body">
                <button class="button button-primary button-large fm-button-delete" onclick="remove_page_only(); fm_popup_toggle('fm_delete_page_popup_container'); return false;"><?php _e('Delete page and preserve the fields', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                <button class="button button-primary button-large fm-button-delete" onclick="remove_page_all(); fm_popup_toggle('fm_delete_page_popup_container'); return false;"><?php _e('Delete page and the fields', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                <button class="button button-large" onclick="fm_popup_toggle('fm_delete_page_popup_container'); return false;"><?php _e('Cancel', WDFMInstance(self::PLUGIN)->prefix); ?></button>
              </div>
            </div>
          </div>
          <div id="fm_delete_field_popup_container" class="hidden fm_popup_container">
            <div class="fm-popup-overlay" onclick="fm_popup_toggle('fm_delete_field_popup_container'); return false;"></div>
            <div id="fm-delete-field-content" class="fm-popup-wrap">
              <input type="hidden" id="fm_delete_field_id" value="" />
              <div class="fm-alert-header">
                <label><?php _e('Are you sure you want to delete this field?', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              </div>
              <div class="fm-alert-body">
                <button class="button button-primary button-large fm-button-delete" onclick="remove_row(jQuery('#fm_delete_field_id').val()); fm_popup_toggle('fm_delete_field_popup_container'); return false;"><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                <button class="button button-large" onclick="fm_popup_toggle('fm_delete_field_popup_container'); return false;"><?php _e('Cancel', WDFMInstance(self::PLUGIN)->prefix); ?></button>
              </div>
            </div>
          </div>
          <div id="fm_delete_column_popup_container" class="hidden fm_popup_container">
            <div class="fm-popup-overlay" onclick="fm_popup_toggle('fm_delete_column_popup_container'); return false;"></div>
            <div id="fm-delete-column-content" class="fm-popup-wrap">
              <div class="fm-alert-header">
                <label><?php _e('Are you sure you want to delete this column?', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              </div>
              <div class="fm-alert-body">
                <button class="button button-primary button-large fm-button-delete" onclick="fm_remove_column(); fm_popup_toggle('fm_delete_column_popup_container'); return false;"><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                <button class="button button-large" onclick="fm_popup_toggle('fm_delete_column_popup_container'); return false;"><?php _e('Cancel', WDFMInstance(self::PLUGIN)->prefix); ?></button>
              </div>
            </div>
          </div>
          <div id="fm_delete_row_popup_container" class="hidden fm_popup_container">
            <div class="fm-popup-overlay" onclick="fm_popup_toggle('fm_delete_row_popup_container'); return false;"></div>
            <div id="fm-delete-row-content" class="fm-popup-wrap">
              <div class="fm-alert-header">
                <label><?php _e('You are about to delete a column from your form.', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <label><?php _e('Do you want to also Remove all the fields in that column?', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <label><?php _e('If you decide to preserve the fields, they will be moved to the previous column.', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              </div>
              <div class="fm-alert-body">
                <button class="button button-primary button-large fm-button-delete" onclick="fm_remove_section(false); fm_popup_toggle('fm_delete_row_popup_container'); return false;"><?php _e('Delete column and preserve the fields', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                <button class="button button-primary button-large fm-button-delete" onclick="fm_remove_section(true); fm_popup_toggle('fm_delete_row_popup_container'); return false;"><?php _e('Delete column and the fields', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                <button class="button button-large" onclick="fm_popup_toggle('fm_delete_row_popup_container'); return false;"><?php _e('Cancel', WDFMInstance(self::PLUGIN)->prefix); ?></button>
              </div>
            </div>
          </div>
          <div id="fm_revision_popup_container" class="hidden fm_popup_container">
            <div class="fm-popup-overlay" onclick="fm_popup_toggle('fm_revision_popup_container'); return false;"></div>
            <div id="fm_revision-content" class="fm-popup-wrap">
              <div class="fm-section-header">
                <div class="fm-section-header-title">
                  <label><?php _e('Revisions', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </div>
                <span class="fm-ico-delete" onclick="fm_popup_toggle('fm_revision_popup_container'); return false;"></span>
              </div>
              <div class="fm-section">
                <?php
                $revisions = '';
                $ind = 0;
                if( isset($params['revisions']) && isset($params['revisions_total']) ) {
                  $revisions = $params['revisions'];
                  $ind = $params['revisions_total']-1;
                }
                if( isset($params['current_revision']) && $revisions != '' ) {
                  $cur_rev = $params['current_revision'];
                  ?>
                  <div class="fm_row fm_rev_header">
                    <div class="fm_rev_index"><?php echo _e('Last saved:', WDFMInstance(self::PLUGIN)->prefix) ?></div>
                    <div class="fm_rev_date"><?php echo !empty($cur_rev->date) ? date('Y-m-d', $cur_rev->date) : _e('Unknown date', WDFMInstance(self::PLUGIN)->prefix) ?></div>
                    <div class="fm_rev_time"><?php echo !empty($cur_rev->date) ? date('H:i:s', $cur_rev->date) : '' ?></div>
                    <div class="fm_rev_button">
                      <?php if( $cur_rev->cur == 1 ) {
                        if ( $params['revisions_status'] ) {
                          $edit_url = add_query_arg(array(
                                                      'page' => 'manage_fm',
                                                      'task' => 'edit',
                                                      'current_id' => $row->id
                                                    ), admin_url('admin.php'));
                          ?>
                          <a class="button button-large"  href="<?php echo $edit_url; ?>"><?php _e('Go Back', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                          <?php
                        } else { ?>
                          <a class="fm_not-active" disabled href=""><?php _e('Current', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                        <?php }
                      }?>
                    </div>
                  </div>

                  <div class="fm_row fm_title_row">
                    <div class="fm_rev_index"><?php echo _e('Number', WDFMInstance(self::PLUGIN)->prefix) ?></div>
                    <div class="fm_rev_date"><?php echo _e('Date', WDFMInstance(self::PLUGIN)->prefix) ?></div>
                    <div class="fm_rev_time"><?php echo _e('Time', WDFMInstance(self::PLUGIN)->prefix) ?></div>
                  </div>
                  <div class="revision_content">
                    <?php
                    foreach ( $revisions as $revision ) {
                      if( $revision->cur != 1 ){
                        ?>
                        <div class="fm_row <?php if ( $revision->cur == 1 )
                          echo 'fm_cur' ?>">
                          <div class="fm_rev_index"><?php echo $ind ?></div>
                          <div class="fm_rev_date"><?php echo !empty($revision->date) ? date('Y-m-d', $revision->date) : _e('Unknown date', WDFMInstance(self::PLUGIN)->prefix) ?></div>
                          <div class="fm_rev_time"><?php echo !empty($revision->date) ? date('H:i:s', $revision->date) : '' ?></div>
                          <div class="fm_rev_button">
                            <?php
                            if ( $row->backup_id == $revision->backup_id && $revision->cur != 1 ) {
                              echo "<span class='curr_rev_button'>" . __('Current', WDFMInstance(self::PLUGIN)->prefix) . "</span>";
                            }
                            else { ?>
                              <button class="button undo-button button-large button-primary" onclick="if (fm_check_required('title', 'Form Title') || !FormManageSubmitButton()) {return false;}; jQuery('#saving_text').html('Undo'); fm_set_input_value('backup_id', <?php echo $revision->backup_id; ?>); fm_set_input_value('rev_id', <?php echo $ind; ?>); fm_set_input_value('task', 'undo');">
                                <span></span>
                                <?php
                                _e('View', WDFMInstance(self::PLUGIN)->prefix);
                                ?>
                              </button>
                            <?php } ?>
                          </div>
                        </div>
                        <?php
                        $ind--;
                      }
                    } ?>
                  </div>
                  <?php
                }
                ?>
              </div>
            </div>
          </div>

          <div class="fm-edit-content">
            <div style="display: table; width: 100%;" id="page_bar">
              <div id="page_navigation" style="display: table-row;">
                <div align="center" id="pages" show_title="<?php echo $row->show_title; ?>" show_numbers="<?php echo $row->show_numbers; ?>" type="<?php echo $row->pagination; ?>" style="display: table-cell;  width:90%;"></div>
                <div align="left" id="edit_page_navigation" title="<?php _e('Edit page navigation.', WDFMInstance(self::PLUGIN)->prefix); ?>"></div>
              </div>
            </div>
            <div id="take" class="main">
              <?php echo $row->form_front; ?>
              <div class="wdform_column ui-sortable" id="add_field_cont">

                <div id="add_field" class="ui-sortable-handle">
                  <div class="first-time-use">
                    <span class="first-time-use-close fm-ico-close"></span>
                    <?php _e('Drag icon to the form to add a field.', WDFMInstance(self::PLUGIN)->prefix); ?>
                  </div>

                  <div type="type_text" class="wdform_field">
                    <div class="add-new-button button-primary" title="<?php _e('Drag icon to the form or click here to add a field.', WDFMInstance(self::PLUGIN)->prefix); ?>">
                      <span class="fm-ico-draggable"></span>
                      <?php _e('New Field', WDFMInstance(self::PLUGIN)->prefix); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="wdform_row_empty"><div onclick="fm_add_page(); return false;"><span class="add_row_icon fm-ico-plus-circle"></span><span class="add_row_button"><?php _e('Add Page', WDFMInstance(self::PLUGIN)->prefix); ?></span></div></div>
            </div>
          </div>
        </div>
        <div id="form_header_tab_content">
          <div id="fm-header-content">
            <div class="wd-table">
              <div class="wd-table-col-100">
                <div class="wd-box-section">
                  <div class="wd-box-content">
                    <div class="wd-group">
                      <label class="wd-label"><?php _e('Show header', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <input type="radio" id="header_hide-1" class="wd-radio" name="header_hide" value="1" data-initial-value="<?php echo $row->header_hide; ?>" <?php echo $row->header_hide == '1' ? 'checked="checked"' : '' ?> />
                      <label class="wd-label-radio" for="header_hide-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <input type="radio" id="header_hide-0" class="wd-radio" name="header_hide" value="0" data-initial-value="<?php echo $row->header_hide; ?>" <?php echo $row->header_hide == '0' ? 'checked="checked"' : '' ?> />
                      <label class="wd-label-radio" for="header_hide-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wd-table meta-box-sortables">
              <div class="wd-table-col-100 wd-table-col-left">
                <div class="wd-box-section">
                  <div class="wd-box-content">
                    <div class="wd-group">
                      <label class="wd-label" for="header_title"><?php _e('Title', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <input type="text" id="header_title" name="header_title" class="fm-check-change" value="<?php echo $row->header_title; ?>" data-initial-value="<?php echo $row->header_title; ?>" />
                    </div>
                    <div class="wd-group" id="description_editor">
                      <label class="wd-label" for="header_description"><?php _e('Description', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <input type="hidden" id="header_description_initial_value" value="<?php echo rawurlencode($row->header_description); ?>" />
                      <?php if (user_can_richedit() && $params['fm_enable_wp_editor']) {
                        wp_editor($row->header_description, 'header_description', array('teeny' => TRUE, 'textarea_name' => 'header_description', 'media_buttons' => FALSE, 'textarea_rows' => 5));
                      }
                      else { ?>
                        <textarea name="header_description" id="header_description" class="mce_editable fm-check-change" aria-hidden="true" data-initial-value="<?php echo $row->header_description; ?>"><?php echo $row->header_description; ?></textarea>
                        <?php
                      }
                      ?>
                    </div>
                    <div class="wd-group">
                      <label class="wd-label" for="header_image_url"><?php _e('Image', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <input type="text" id="header_image_url" name="header_image_url" class="fm-check-change" value="<?php echo $row->header_image_url; ?>" data-initial-value="<?php echo $row->header_image_url; ?>" />
                      <button class="button add-button medium" onclick="fmOpenMediaUploader(event); return false;"><?php _e('Add Image', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                      <?php $header_bg = $row->header_image_url ? 'background-image: url('.$row->header_image_url.'); background-position: center;' : ''; ?>
                      <div id="header_image" class="header_img<?php if (!$row->header_image_url) echo ' fm-hide'; ?>" style="<?php echo $header_bg; ?>">
                        <button type="button" id="remove_header_img" onclick="fmRemoveHeaderImage(event); return false;">
                          <i class="mce-ico mce-i-dashicon dashicons-no"></i>
                        </button>
                      </div>
                    </div>
                    <div class="wd-group">
                      <label class="wd-label" for="header_image_animation"><?php _e('Image Animation', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <select name="header_image_animation" class="fm-check-change" data-initial-value="<?php echo $row->header_image_animation; ?>">
                        <?php
                        foreach($animation_effects as $anim_key => $animation_effect){
                          $selected = $row->header_image_animation == $anim_key ? 'selected="selected"' : '';
                          echo '<option value="'.$anim_key.'" '.$selected.'>'.$animation_effect.'</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="wd-group">
                      <label for="header_hide_image" class="wd-label wd-label-radio"><?php _e('Hide Image on Mobile', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      <input type="checkbox" id="header_hide_image" name="header_hide_image" value="1" data-initial-value="<?php echo $row->header_hide_image; ?>" <?php echo $row->header_hide_image == '1' ? 'checked="checked"' : '' ?> />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fm-placeholders-popup-wrap">
        <?php echo $this->placeholders_popup($params['label_label']); ?>
      </div>

      <input type="hidden" name="form_front" id="form_front" value="<?php echo htmlentities($row->form_front); ?>" />
      <input type="hidden" name="form_fields" id="form_fields" />
      <input type="hidden" name="pagination" id="pagination" value="<?php echo $row->pagination; ?>" />
      <input type="hidden" name="show_title" id="show_title" value="<?php echo $row->show_title; ?>" />
      <input type="hidden" name="show_numbers" id="show_numbers" value="<?php echo $row->show_numbers; ?>" />
      <input type="hidden" name="public_key" id="public_key" />
      <input type="hidden" name="private_key" id="private_key" />
      <input type="hidden" name="recaptcha_theme" id="recaptcha_theme" />
      <input type="hidden" id="label_order" name="label_order" value="<?php echo htmlentities($row->label_order); ?>" />
      <input type="hidden" id="label_order_current" name="label_order_current" value="<?php echo htmlentities($row->label_order_current); ?>" />
      <input type="hidden" name="counter" id="counter" value="<?php echo $row->counter; ?>" />
      <input type="hidden" name="backup_id" id="backup_id" value="<?php echo $row->backup_id;?>">
      <input type="hidden" name="rev_id" id="rev_id">
      <input type="hidden" name="option" value="com_formmaker" />
      <input type="hidden" name="id" value="<?php echo $id; ?>" />
      <input type="hidden" name="cid[]" value="<?php echo $id; ?>" />
      <input type="hidden" id="task" name="task" value=""/>
      <input type="hidden" id="current_id" name="current_id" value="<?php echo $row->id; ?>" />
    </form>
    <script type="text/javascript">
			gen = <?php echo $row->counter; ?>;
			is_sortable = <?php echo $row->sortable ?>;	
			is_addon_calculator_active = <?php echo (defined('WD_FM_CALCULATOR') && is_plugin_active(constant('WD_FM_CALCULATOR'))) ? 1 : 0; ?>;
			is_addon_stripe_active = <?php echo $stripe_addon['enable'] ? 1 : 0; ?>;
			is_stripe_enabled = <?php echo ($stripe_addon['enable'] && $stripe_addon['stripe_enable'] ? 1 : 0); ?>;
			labels_id_array = [<?php echo $labels['id']; ?>];
			labels_label_array = [<?php echo $labels['label']; ?>];
			labels_type_array = [<?php echo $labels['type']; ?>];

			form_view = 1;
			form_view_count = 1;
			form_view_max = 1;
			form_fields_initial = '<?php echo rawurlencode($row->form_fields); ?>';
			form_fields_initial = decodeURIComponent(form_fields_initial);

			default_theme  = '<?php echo $default_theme; ?>';
			theme_edit_url = '<?php echo add_query_arg( array('page' => 'themes' . WDFMInstance(self::PLUGIN)->menu_postfix, 'task' =>'edit'), $page_url); ?>';
			jQuery(function () {
				set_theme();
        jQuery( function() {
          jQuery( "#fm-tabs" ).tabs({
            active: "<?php echo $form_active_tab; ?>",
            beforeLoad: function( event, ui ) {
              /* Stop loading if tab content is already loaded. */
              if (ui.panel.html() != '' && ui.panel.html() != '<div class="fm-tab-loading-indicator"><?php echo addslashes(__('Loading...', WDFMInstance(self::PLUGIN)->prefix)); ?></div>') {
                event.stopPropagation();
                event.preventDefault();
              }
              else {
                ui.panel.html('<div class="fm-tab-loading-indicator"><?php echo addslashes(__('Loading...', WDFMInstance(self::PLUGIN)->prefix)); ?></div>');
              }
              if (ui.tab.index() != 0) {
                jQuery('.button_revisions').addClass('fm-hide');
              }
              else {
                jQuery('.button_revisions').removeClass('fm-hide');
              }
            },
            load: function( event, ui ) {
              var tabs_loaded_input = jQuery('#fm_tabs_loaded');
              var tabs_loaded = JSON.parse(tabs_loaded_input.val());
              tabs_loaded.push(ui.tab[0].id);
              tabs_loaded_input.val(JSON.stringify(tabs_loaded));
              //trigger custom event to pretend document ready
              if (ui.tab[0].id === 'form_options_tab') {
                jQuery(document).trigger('fm_tab_loaded');
              }
              if (ui.tab[0].id === 'form_layout_tab') {
                jQuery(document).trigger('fm_tab_layout_loaded');
              }
              if (ui.tab[0].id === 'form_email_options_tab') {
                jQuery(document).trigger('fm_tab_email_loaded');
              }
              if ( typeof tinymce != "undefined" ) {
                ui.panel.find('.wp-editor-area').each(function () {
                  var cur_id = jQuery(this).attr('id');
                  tinymce.execCommand('mceAddEditor', true, cur_id);
                  quicktags({id: cur_id});
                });
              }
            },
            create: function( event, ui ) {
              jQuery( '.fm-topbar_user_guid' ).attr( 'href','https://help.10web.io/hc/en-us/articles/'+ ui.tab.data( 'help-url' ) + '?utm_source=form_maker&utm_medium=free_plugin' );
            },
            activate: function( event, ui ) {
              jQuery('#fm_tab_active').val(ui.newTab.index());
              jQuery( '.fm-topbar_user_guid' ).attr( 'href','https://help.10web.io/hc/en-us/articles/'+ ui.newTab.data( 'help-url' ) + '?utm_source=form_maker&utm_medium=free_plugin' );
            },
            beforeActivate: function( event, ui ) {
              var uri = window.location.toString();
              if ( uri.indexOf('fieldset_id') && typeof ui.newTab.attr('id') != 'undefined' && ui.newTab.attr('id') != 'form_options_tab' ) {
                var regex = /(&fieldset_id=(.*)&)/;
                var new_url = uri.replace(regex, '&', uri );
                window.history.replaceState({}, document.title, new_url);
                form_maker_options_tabs('general');
              }
              if (ui.newTab.index() != 0) {
                jQuery('.button_revisions').addClass('fm-hide');
              }
              else {
                jQuery('.button_revisions').removeClass('fm-hide');
              }
            }
          });
        } );
			});
		</script>
		<?php
	}

  /**
   * Email options.
   *
   * @param array $params
   */
  public function email_options( $params = array() ) {
    echo $this->body_email_options( $params );
    die();
  }

  /**
   * Generate page body email options.
   *
   * @param array $params
   * @return string Body html.
   */
  public function body_email_options( $params = array() ) {
    $row = $params['row'];
    $payment_method = isset($row->paypal_mode) ? intval($row->paypal_mode) : 0;
    $mail_send_payment_info = !empty($row->mail_send_payment_info) ? $row->mail_send_payment_info : 1;
    $mail_send_email_payment = !empty($row->mail_send_email_payment) ? $row->mail_send_email_payment : 1;
    $fields = $params['fields'];
    $fields_count = $params['fields_count'];
    ?>
    <div id="form_email_options_tab_content" class="adminform js">
      <div class="wd-table">
        <div class="wd-table-col-100">
          <div class="wd-box-section">
            <div class="wd-box-content">
              <div class="wd-group">
                <label class="wd-label"><?php _e('Send E-mail', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input type="radio" name="sendemail" <?php echo $row->sendemail == 1 ? 'checked="checked"' : '' ?> id="fm_sendemail-1" class="wd-radio" value="1" onchange="fm_toggle_options('.fm_email_options', true)" />
                <label class="wd-label-radio" for="fm_sendemail-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input type="radio" name="sendemail" <?php echo $row->sendemail == 0 ? 'checked="checked"' : '' ?> id="fm_sendemail-0" class="wd-radio" value="0" onchange="fm_toggle_options('.fm_email_options', false)" />
                <label class="wd-label-radio" for="fm_sendemail-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <p class="description"><?php _e('Enable this setting to send submitted information to administrators and/or the submitter.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                <p class="description fm_email_options"><?php _e('In case you cannot find the submission email in your Inbox, make sure to check the Spam folder as well.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="wd-table meta-box-sortables fm_email_options" id="emailTab_fieldset">
        <div class="wd-table-col-100 wd-table-col-left">
          <div class="wd-box-section">
            <div class="wd-box-title">
              <strong><?php _e('Email to Administrator', WDFMInstance(self::PLUGIN)->prefix); ?></strong>
            </div>
            <div class="wd-box-content">
              <div class="wd-group wd-has-placeholder">
                <label class="wd-label" for="mail"><?php _e('Email to send submissions to', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input autocomplete="off" class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" id="mail" name="mail" value="<?php echo $row->mail; ?>" />
                <span class="dashicons dashicons-list-view" data-id="mail"></span>
                <p class="description"><?php _e('Specify the email address(es), to which submitted form information will be sent. For multiple email addresses separate with commas.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <?php if ( !empty($payment_method) ) {
                ?>
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Send Email', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <div class="fm-row">
                    <input type="radio" name="mail_send_email_payment" <?php echo $mail_send_email_payment == 1 ? 'checked="checked"' : '' ?> id="fm_send_email_payment-1" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_send_email_payment-1"><?php _e('Before completion of payment', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  </div>
                  <div class="fm-row">
                    <input type="radio" name="mail_send_email_payment" <?php echo $mail_send_email_payment == 2 ? 'checked="checked"' : '' ?> id="fm_send_email_payment-2" class="wd-radio" value="2">
                    <label class="wd-label-radio" for="fm_send_email_payment-2"><?php _e('After payment has been successfully completed', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  </div>
                  <?php if ( $payment_method === 1 ) { ?>
                    <div class="fm-row">
                      <input type="checkbox" name="mail_send_payment_info" <?php echo $mail_send_payment_info == 1 ? 'checked="checked"' : '' ?> id="fm_mail_send_payment_info" class="wd-checkbox" value="1">
                      <label class="wd-label-radio" for="fm_mail_send_payment_info"><?php _e('Send payment information after payment has been successfully completed', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    </div>
                  <?php } ?>
                </div>
              <?php } ?>
              <div class="wd-group">
                <label class="wd-label"><?php _e('Email From', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <?php
                $is_other = TRUE;
                for ( $i = 0; $i < $fields_count - 1; $i++ ) {
                  ?>
                  <input class="wd-radio" type="radio" name="from_mail" id="from_mail<?php echo $i; ?>" value="<?php echo(!is_numeric($fields[$i]) ? substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) : $fields[$i]); ?>" <?php echo((!is_numeric($fields[$i]) ? substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) : $fields[$i]) == $row->from_mail ? 'checked="checked"' : ''); ?> onclick="wdhide('mail_from_other_wrap'); fm_clear_input_value('mail_from_other');" />
                  <label class="wd-label-radio" for="from_mail<?php echo $i; ?>"><?php echo substr($fields[$i + 1], 0, strpos($fields[$i + 1], '*:*w_field_label*:*')); ?></label>
                  <?php
                  if ( !is_numeric($fields[$i]) ) {
                    if ( substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) == $row->from_mail ) {
                      $is_other = FALSE;
                    }
                  }
                  else {
                    if ( $fields[$i] == $row->from_mail ) {
                      $is_other = FALSE;
                    }
                  }
                }
                ?>
                <input style="<?php echo ($fields_count == 1) ? 'display:none;' : ''; ?>" class="wd-radio" type="radio" id="other" name="from_mail" value="other" <?php echo ($is_other) ? 'checked="checked"' : ''; ?> onclick="wdshow('mail_from_other_wrap')" />
                <label style="<?php echo ($fields_count == 1) ? 'display:none;' : ''; ?>" class="wd-label-radio" for="other"><?php _e('Other', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <p style="display: <?php echo ($is_other) ? 'block;' : 'none;'; ?>" id="mail_from_other_wrap">
                  <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" name="mail_from_other" id="mail_from_other" value="<?php echo ($is_other) ? $row->from_mail : ''; ?>" />
                </p>
                <p class="description"><?php _e('Specify the email address from which the administrator will receive the email.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                <p class="description"><?php _e('We recommend you to use an email address belonging to your website domain.', WDFMInstance(self::PLUGIN)->prefix); ?> <span class="dashicons dashicons-editor-help wd-info" data-id="fm-email-from-info"></span></p>
                <div id="fm-email-from-info" class="fm-hide">
                  <p><?php _e('If sender email address is not hosted on the same domain as your website, some hosting providers may not send the emails.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  <p><?php _e('In addition, relaying mail servers may consider the emails as phishing.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </div>
              </div>
              <div class="wd-group wd-has-placeholder">
                <label class="wd-label" for="from_name"><?php _e('From Name', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input autocomplete="off" type="text" name="from_name" value="<?php echo $row->from_name; ?>" id="from_name" />
                <span class="dashicons dashicons-list-view" data-id="from_name"></span>
                <p class="description"><?php _e('Set the name or search for a form field which is shown as the sender’s name in submission or confirmation emails.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <div class="wd-group wd-has-placeholder">
                <label class="wd-label" for="mail_subject"><?php _e('Subject', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input autocomplete="off" type="text" id="mail_subject" name="mail_subject" value="<?php echo !empty($row->mail_subject) ? $row->mail_subject : '{formtitle}'; ?>" />
                <span class="dashicons dashicons-list-view" data-id="mail_subject"></span>
                <p class="description"><?php _e('Add a custom subject or search for a form field for the submission email. In case it’s left blank, Form Title will be set as the subject of submission emails.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <div class="wd-group">
                <label class="wd-label" for="script_mail"><?php _e('Custom Text in Email For Administrator', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <div class="wd-editor-placeholder">
                  <span class="dashicons dashicons-list-view" data-id="script_mail"></span>
                </div>
                <?php
                if ( user_can_richedit() && $params['fm_enable_wp_editor'] ) {
                  wp_editor($row->script_mail, 'script_mail', array(
                    'teeny' => TRUE,
                    'textarea_name' => 'script_mail',
                    'media_buttons' => FALSE,
                    'textarea_rows' => 5
                  ));
                }
                else {
                  ?>
                  <textarea name="script_mail" id="script_mail" cols="20" rows="10" style="width:100%; height:200px;"><?php echo $row->script_mail; ?></textarea>
                  <?php
                }
                ?>
                <p class="description"><?php _e('Write custom content to the email message which is sent to administrator. Include All Fields List to forward all submitted information, or click on fields buttons to use individual field values in the content.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <div class="postbox closed">
                <button class="button-link handlediv" type="button" aria-expanded="true">
                  <span class="screen-reader-text"><?php _e('Toggle panel:', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                  <span class="toggle-indicator" aria-hidden="false"></span>
                </button>
                <h2 class="hndle">
                  <span><?php _e('Advanced', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                </h2>
                <div class="inside">
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Reply to (if different from "Email From")', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <?php
                    $is_other = TRUE;
                    for ( $i = 0; $i < $fields_count - 1; $i++ ) {
                      ?>
                      <input class="wd-radio" type="radio" name="reply_to" id="reply_to<?php echo $i; ?>" value="<?php echo(!is_numeric($fields[$i]) ? substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) : $fields[$i]); ?>" <?php echo((!is_numeric($fields[$i]) ? substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) : $fields[$i]) == $row->reply_to ? 'checked="checked"' : ''); ?> onclick="wdhide('reply_to_other_wrap'); fm_clear_input_value('reply_to_other');" />
                      <label class="wd-label-radio" for="reply_to<?php echo $i; ?>"><?php echo substr($fields[$i + 1], 0, strpos($fields[$i + 1], '*:*w_field_label*:*')); ?></label>
                      <?php
                      if ( !is_numeric($fields[$i]) ) {
                        if ( substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) == $row->reply_to ) {
                          $is_other = FALSE;
                        }
                      }
                      else {
                        if ( $fields[$i] == $row->reply_to ) {
                          $is_other = FALSE;
                        }
                      }
                    }
                    ?>
                    <input style="<?php echo ($fields_count == 1) ? 'display: none;' : ''; ?>" class="wd-radio" type="radio" id="other1" name="reply_to" value="other" <?php echo ($is_other) ? 'checked="checked"' : ''; ?> onclick="wdshow('reply_to_other_wrap')" />
                    <label style="<?php echo ($fields_count == 1) ? 'display: none;' : ''; ?>" class="wd-label-radio" for="other1"><?php _e('Other', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <div class="wd-group wd-has-placeholder" style="display: <?php echo ($is_other) ? 'block;' : 'none;'; ?> padding-top: 30px;" id="reply_to_other_wrap">
                      <input class="fm-validate" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" name="reply_to_other" value="<?php echo ($is_other && $row->reply_to) ? $row->reply_to : ''; ?>" id="reply_to_other" />
                      <span class="dashicons dashicons-list-view" data-id="reply_to_other"></span>
                    </div>
                    <p class="description"><?php _e('Specify an alternative email address, to which the administrator will be able to reply upon receiving the message.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label" for="mail_cc"><?php _e('CC', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" id="mail_cc" name="mail_cc" value="<?php echo $row->mail_cc ?>" />
                    <p class="description"><?php _e('Provide additional email addresses to send the submission or confirmation email to. The receiver will be able to view all other recipients.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label" for="mail_bcc"><?php _e('BCC', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" id="mail_bcc" name="mail_bcc" value="<?php echo $row->mail_bcc ?>" />
                    <p class="description"><?php _e('Write additional email addresses to send the submission or confirmation email to. The receiver will not be able to view other recipients.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Mode', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_mode" <?php echo $row->mail_mode == 1 ? 'checked="checked"' : '' ?> id="fm_mo_mail_mode-1" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_mo_mail_mode-1"><?php _e('HTML', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_mode" <?php echo $row->mail_mode == 0 ? 'checked="checked"' : '' ?> id="fm_mo_mail_mode-0" class="wd-radio" value="0">
                    <label class="wd-label-radio" for="fm_mo_mail_mode-0"><?php _e('Text', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <p class="description"><?php _e('Select the layout of the submission email, Text or HTML.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Attach File', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_attachment" <?php echo $row->mail_attachment == 1 ? 'checked="checked"' : '' ?> id="fm_mo_mail_attachment-1" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_mo_mail_attachment-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_attachment" <?php echo $row->mail_attachment == 0 ? 'checked="checked"' : '' ?> id="fm_mo_mail_attachment-0" class="wd-radio" value="0">
                    <label class="wd-label-radio" for="fm_mo_mail_attachment-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <p class="description"><?php _e('If you have File Upload fields on your form, enable this setting to attach uploaded files to submission or confirmation email.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Email empty fields', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_emptyfields" <?php echo $row->mail_emptyfields == 1 ? 'checked="checked"' : '' ?> id="fm_mo_mail_emptyfields-1" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_mo_mail_emptyfields-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_emptyfields" <?php echo $row->mail_emptyfields == 0 ? 'checked="checked"' : '' ?> id="fm_mo_mail_emptyfields-0" class="wd-radio" value="0">
                    <label class="wd-label-radio" for="fm_mo_mail_emptyfields-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <p class="description"><?php _e('Disable this setting, in case you do not want to include form fields, which are left empty by the submitter.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="wd-box-section">
            <div class="wd-box-title">
              <strong><?php _e('Email to User', WDFMInstance(self::PLUGIN)->prefix); ?></strong>
            </div>
            <div class="wd-box-content">
              <div class="wd-group">
                <label class="wd-label"><?php _e('Send to', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <?php
                $fields = explode('*:*id*:*type_submitter_mail*:*type*:*', $row->form_fields);
                $fields_count = count($fields);
                if ( $fields_count == 1 ) {
                  _e('There is no email field', WDFMInstance(self::PLUGIN)->prefix);
                }
                else {
                  for ( $i = 0; $i < $fields_count - 1; $i++ ) {
                    ?>
                    <div>
                      <input type="checkbox" name="send_to[]" id="send_to<?php echo $i; ?>" value="<?php echo(!is_numeric($fields[$i]) ? substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) : $fields[$i]); ?>" <?php echo(is_numeric(strpos($row->send_to, '*' . (!is_numeric($fields[$i]) ? substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) : $fields[$i]) . '*')) ? 'checked="checked"' : ''); ?> style="margin: 0px 5px 0px 0px;" />
                      <label for="send_to<?php echo $i; ?>"><?php echo substr($fields[$i + 1], 0, strpos($fields[$i + 1], '*:*w_field_label*:*')); ?></label>
                    </div>
                    <?php
                  }
                }
                ?>
                <p class="description"><?php _e('Use this setting to select the email field of your form, to which the submissions will be sent.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <?php if ( !empty($payment_method) ) { ?>
              <div class="wd-group">
                <label class="wd-label"><?php _e('Send Email', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <div class="fm-row">
                  <input type="radio" name="mail_send_email_payment_user" <?php echo $row->mail_send_email_payment_user == 1 ? 'checked="checked"' : '' ?> id="fm_send_email_payment_user-1" class="wd-radio" value="1">
                  <label class="wd-label-radio" for="fm_send_email_payment_user-1"><?php _e('Before completion of payment', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </div>
                <div class="fm-row">
                  <input type="radio" name="mail_send_email_payment_user" <?php echo $row->mail_send_email_payment_user == 2 ? 'checked="checked"' : '' ?> id="fm_send_email_payment_user-2" class="wd-radio" value="2">
                  <label class="wd-label-radio" for="fm_send_email_payment_user-2"><?php _e('After payment has been successfully completed', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </div>
              </div>
              <?php } ?>
              <div class="wd-group">
                <label class="wd-label" for="mail_from_user"><?php _e('Email From', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" id="mail_from_user" name="mail_from_user" value="<?php echo $row->mail_from_user; ?>" />
                <p class="description"><?php _e('Specify the email address from which the submitter will receive the email.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                <p class="description"><?php _e('We recommend you to use an email address belonging to your website domain.', WDFMInstance(self::PLUGIN)->prefix); ?> <span class="dashicons dashicons-editor-help wd-info" data-id="fm-user-email-from-info"></span></p>
                <div id="fm-user-email-from-info" class="fm-hide">
                  <p><?php _e('If sender email address is not hosted on the same domain as your website, some hosting providers may not send the emails.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  <p><?php _e('In addition, relaying mail servers may consider the emails as phishing.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </div>
              </div>
              <div class="wd-group wd-has-placeholder">
                <label class="wd-label" for="mail_from_name_user"><?php _e('From Name', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input autocomplete="off" type="text" name="mail_from_name_user" value="<?php echo $row->mail_from_name_user; ?>" id="mail_from_name_user" />
                <span class="dashicons dashicons-list-view" data-id="mail_from_name_user"></span>
                <p class="description"><?php _e('Set the name or search for a form field which is shown as the sender’s name in submission or confirmation emails.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <div class="wd-group wd-has-placeholder">
                <label class="wd-label" for="mail_subject_user"><?php _e('Subject', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <input autocomplete="off" type="text" name="mail_subject_user" value="<?php echo !empty($row->mail_subject_user) ? $row->mail_subject_user : '{formtitle}' ?>" id="mail_subject_user" class="mail_subject_user" />
                <span class="dashicons dashicons-list-view" data-id="mail_subject_user"></span>
                <p class="description"><?php _e('Add a custom subject or search for a form field for the submission email. In case it’s left blank, Form Title will be set as the subject of submission emails.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <div class="wd-group">
                <label class="wd-label" for="script_mail_user"><?php _e('Custom Text in Email For User', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                <div class="wd-editor-placeholder">
                  <span class="dashicons dashicons-list-view" data-id="script_mail_user"></span>
                </div>
                <?php
                if ( user_can_richedit() && $params['fm_enable_wp_editor'] ) {
                  wp_editor($row->script_mail_user, 'script_mail_user', array(
                    'teeny' => TRUE,
                    'textarea_name' => 'script_mail_user',
                    'media_buttons' => FALSE,
                    'textarea_rows' => 5
                  ));
                }
                else {
                  ?>
                  <textarea autocomplete="off" name="script_mail_user" id="script_mail_user" cols="20" rows="10" style="width:100%; height:200px;"><?php echo $row->script_mail_user; ?></textarea>
                  <?php
                }
                ?>
                <p class="description"><?php _e('Write custom content to the email message which is sent to submitter. Include All Fields List to forward all submitted information, or click on fields buttons to use individual field values in the content.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
              </div>
              <div class="postbox closed">
                <button class="button-link handlediv" type="button" aria-expanded="true">
                  <span class="screen-reader-text"><?php _e('Toggle panel:', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                  <span class="toggle-indicator" aria-hidden="false"></span>
                </button>
                <h2 class="hndle">
                  <span><?php _e('Advanced', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                </h2>
                <div class="inside">
                  <div class="wd-group wd-has-placeholder">
                    <label class="wd-label" for="reply_to_user"><?php _e('Reply to (if different from "Email From")', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="fm-validate" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" name="reply_to_user" value="<?php echo $row->reply_to_user; ?>" id="reply_to_user" />
                    <span class="dashicons dashicons-list-view" data-id="reply_to_user"></span>
                    <p class="description"><?php _e('Specify an alternative email address, to which the submitter will be able to reply upon receiving the message.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label" for="mail_cc_user"><?php _e('CC', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" name="mail_cc_user" value="<?php echo $row->mail_cc_user ?>" id="mail_cc_user" />
                    <p class="description"><?php _e('Provide additional email addresses to send the submission or confirmation email to. The receiver will be able to view all other recipients.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label" for="mail_bcc_user"><?php _e('BCC', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="" data-tab-id="emailTab" data-content-id="emailTab_fieldset" type="text" name="mail_bcc_user" value="<?php echo $row->mail_bcc_user ?>" id="mail_bcc_user" />
                    <p class="description"><?php _e('Write additional email addresses to send the submission or confirmation email to. The receiver will not be able to view other recipients.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Mode', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_mode_user" <?php echo $row->mail_mode_user == 1 ? 'checked="checked"' : '' ?> id="fm_mo_mail_mode_user-1" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_mo_mail_mode_user-1"><?php _e('HTML', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_mode_user" <?php echo $row->mail_mode_user == 0 ? 'checked="checked"' : '' ?> id="fm_mo_mail_mode_user-0" class="wd-radio" value="0">
                    <label class="wd-label-radio" for="fm_mo_mail_mode_user-0"><?php _e('Text', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <p class="description"><?php _e('Select the layout of the submission email, Text or HTML.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Attach File', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_attachment_user" <?php echo $row->mail_attachment_user == 1 ? 'checked="checked"' : '' ?> id="fm_mo_mail_attachment_user-1" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_mo_mail_attachment_user-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_attachment_user" <?php echo $row->mail_attachment_user == 0 ? 'checked="checked"' : '' ?> id="fm_mo_mail_attachment_user-0" class="wd-radio" value="0">
                    <label class="wd-label-radio" for="fm_mo_mail_attachment_user-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <p class="description"><?php _e('If you have File Upload fields on your form, enable this setting to attach uploaded files to submission or confirmation email.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Email verification', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_verify" <?php echo $row->mail_verify == 1 ? 'checked="checked"' : '' ?> id="fm_mo_mail_verify-1" onclick="wdshow('expire_link')" class="wd-radio" value="1">
                    <label class="wd-label-radio" for="fm_mo_mail_verify-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="mail_verify" <?php echo $row->mail_verify == 0 ? 'checked="checked"' : '' ?> id="fm_mo_mail_verify-0" onclick="wdhide('expire_link')" class="wd-radio" value="0">
                    <label class="wd-label-radio" for="fm_mo_mail_verify-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <p class="description"><?php _e('Activate this option, in case you would like the users to verify their email addresses. After enabling, please add {verificationlink} into the content of Custom Text in Email to User option.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group" <?php echo($row->mail_verify == 0 ? 'style="display:none;"' : '') ?> id="expire_link">
                    <label class="wd-label" for="mail_verify_expiretime"><?php _e('Verification link expires in', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="inputbox" type="text" name="mail_verify_expiretime" maxlength="10" value="<?php echo($row->mail_verify_expiretime ? $row->mail_verify_expiretime : 0); ?>" onkeypress="return check_isnum_point(event)" id="mail_verify_expiretime">
                    <small><?php _e(' -- hours (0 - never expires).', WDFMInstance(self::PLUGIN)->prefix); ?></small>
                    <a target="_blank" href="<?php echo add_query_arg(array(
                                                                        'post' => $params["mail_ver_id"],
                                                                        'action' => 'edit',
                                                                        'nonce'=>$this->fm_nonce,
																	), admin_url('post.php')); ?>"><?php _e('Edit post', WDFMInstance(self::PLUGIN)->prefix); ?></a>
                    <p class="description"><?php _e('Use this option to specify a time period (hours), during which the user will be able to verify their email address.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  /**
   * add fields.
   *
   * @param array $params
   * @return string
   */
  public function add_fields( $params = array() ) {
    $pro_fields1 = array('file_upload', 'map', 'paypal');
    $pro_fields2 = array('file_upload', 'paypal', 'checkbox', 'radio', 'survey', 'time_and_date', 'select');
    $fields = array(
      __('BASIC FIELDS', WDFMInstance(self::PLUGIN)->prefix) => array(
        array('type' => 'text', 'subtype' => 'text', 'title' => __('Single Line Text', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'textarea', 'title' => __('Paragraph Text', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'spinner', 'title' => __('Number', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'time_and_date', 'subtype' => 'date_new', 'title' => __('Date', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'select', 'subtype' => 'own_select', 'title' => __('Select', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'radio', 'subtype' => '', 'title' => __('Single Choice', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'checkbox', 'subtype' => '', 'title' => __('Multiple Choice', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'captcha', 'subtype' => 'recaptcha', 'title' => __('Recaptcha', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'button', 'subtype' => 'submit_reset', 'title' => __('Submit', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'captcha', 'subtype' => 'captcha', 'title' => __('Simple Captcha', WDFMInstance(self::PLUGIN)->prefix)),
      ),
      __('USER INFO FIELDS', WDFMInstance(self::PLUGIN)->prefix) => array(
        array('type' => 'text', 'subtype' => 'name', 'title' => __('Name', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'submitter_mail', 'title' => __('Email', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'phone_new', 'title' => __('Phone', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'address', 'title' => __('Address', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'mark_map', 'title' => __('Mark on Map', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'select', 'subtype' => 'country', 'title' => __('Country List', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'time_and_date', 'subtype' => 'date_fields', 'title' => __('Date of Birth', WDFMInstance(self::PLUGIN)->prefix)),
      ),
      __('ADVANCED', WDFMInstance(self::PLUGIN)->prefix) => array(
        array('type' => 'editor', 'subtype' => '', 'title' => __('HTML', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'file_upload', 'subtype' => '', 'title' => __('File Upload', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'map', 'subtype' => '', 'title' => __('Map', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'time_and_date', 'subtype' => 'time', 'title' => __('Time', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'send_copy', 'title' => __('Receive Copy', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'time_and_date', 'subtype' => 'date_range', 'title' => __('Date Range', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'star_rating', 'title' => __('Stars', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'scale_rating', 'title' => __('Rating', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'slider', 'title' => __('Slider', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'range', 'title' => __('Range', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'grading', 'title' => __('Grades', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'survey', 'subtype' => 'matrix', 'title' => __('Table of Fields', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'hidden', 'title' => __('Hidden Input', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'button', 'subtype' => 'button', 'title' => __('Custom Button', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'password', 'title' => __('Password', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'text', 'subtype' => 'phone', 'title' => __('Phone-Area Code', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'captcha', 'subtype' => 'arithmetic_captcha', 'title' => __('Arithmetic Captcha', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'signature', 'subtype' => 'signature', 'title' => __('Signature', WDFMInstance(self::PLUGIN)->prefix)),
      ),
      __('PAYMENT', WDFMInstance(self::PLUGIN)->prefix) => array(
        array('type' => 'paypal', 'subtype' => 'paypal_price_new', 'title' => __('Price', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'paypal', 'subtype' => 'paypal_select', 'title' => __('Payment Select', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'paypal', 'subtype' => 'paypal_radio', 'title' => __('Payment Single Choice', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'paypal', 'subtype' => 'paypal_checkbox', 'title' => __('Payment Multiple Choice', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'paypal', 'subtype' => 'paypal_shipping', 'title' => __('Shipping', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'paypal', 'subtype' => 'paypal_total', 'title' => __('Total', WDFMInstance(self::PLUGIN)->prefix)),
        array('type' => 'paypal', 'subtype' => 'stripe', 'title' => __('Stripe', WDFMInstance(self::PLUGIN)->prefix)),
      ),
    );
    ob_start();
    ?>
    <div class="add-popup js">
      <div class="popup-header">
        <span class="popup-title">
          <?php _e('Add field', WDFMInstance(self::PLUGIN)->prefix); ?>
        </span>
        <span title="<?php _e('Close', WDFMInstance(self::PLUGIN)->prefix); ?>" alt="<?php _e('Close', WDFMInstance(self::PLUGIN)->prefix); ?>" class="close-popup fm-ico-delete" onclick="close_window()"></span>
      </div>
      <div class="popup-body meta-box-sortables">
        <div class="popup-body-col field_types">
          <div class="field_types_cont">
            <h2 class="hndle field-types-filter_header"><span><?php _e('FIELD TYPES', WDFMInstance(self::PLUGIN)->prefix); ?></span></h2>
            <span class="field-types-filter-cont">
              <input class="field-types-filter" value="" placeholder="<?php _e('Filter', WDFMInstance(self::PLUGIN)->prefix); ?>" tabindex="-1" type="search" />
            </span>
            <div class="postbox filtered-fields hide">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text">Toggle panel</span>
                <span class="toggle-indicator" aria-hidden="true"></span>
              </button>
              <h2 class="hndle">
                <span><?php _e('Filtered fields', WDFMInstance(self::PLUGIN)->prefix); ?></span>
              </h2>
              <div class="inside"></div>
            </div>
            <?php
            foreach ($fields as $section => $field) {
              ?>
              <div class="postbox<?php echo $section != __('BASIC FIELDS', WDFMInstance(self::PLUGIN)->prefix) ? " closed" : ""; ?>">
                <button class="button-link handlediv" type="button" aria-expanded="true">
                  <span class="screen-reader-text"><?php echo __('Toggle panel:', WDFMInstance(self::PLUGIN)->prefix) , $section; ?></span>
                  <span class="toggle-indicator" aria-hidden="false"></span>
                </button>
                <h2 class="hndle">
                  <span><?php echo $section; ?></span>
                </h2>
                <div class="inside">
                  <?php
                  foreach ($field as $button) {
                    ?>
                    <button class="<?php echo ((WDFMInstance(self::PLUGIN)->is_free == 1 && in_array($button['type'], $pro_fields1)) || (WDFMInstance(self::PLUGIN)->is_free == 2 && in_array($button['type'], $pro_fields2))) ? 'wd-pro-fields ' : ''; ?>wd-button button-secondary" onclick="addRow(event, this, '<?php echo $button['type']; ?>', '<?php echo $button['subtype']; ?>'); return false;" data-type="type_<?php echo $button['subtype'] ? $button['subtype'] : $button['type']; ?>">
                      <span class="field-type-button wd<?php echo ($button['subtype'] == '' ? $button['type'] : $button['subtype']); ?>"></span>
                      <?php echo $button['title']; ?>
                    </button>
                    <?php
                  }
                  ?>
                </div>
              </div>
              <?php
            }
            ?>
          </div>
        </div>
        <div id="field_container">
          <?php
          if (WDFMInstance(self::PLUGIN)->is_free) {
            echo $this->free_message(__('This field type is available in Premium version', WDFMInstance(self::PLUGIN)->prefix), '', '', 'premium_message');
          }
          $stripe_addon = $params['stripe_addon'];
          if ( $stripe_addon['enable'] ) {
            if (WDFMInstance(self::PLUGIN)->is_free) {
              echo $this->free_message(__('STRIPE extension compatible with Premium version only', WDFMInstance(self::PLUGIN)->prefix), '', '', 'stripe_message');
            }
          }
          else {
            if (WDFMInstance(self::PLUGIN)->is_free) {
              echo $this->promo_box(__('This feature is available only in the Premium version', WDFMInstance(self::PLUGIN)->prefix), __('Requires STRIPE extension.', WDFMInstance(self::PLUGIN)->prefix), 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin#plugin_extensions', 'stripe_message');
            }
            else {
              echo $this->free_message(__('This feature requires STRIPE extension', WDFMInstance(self::PLUGIN)->prefix), 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin#plugin_extensions', __( 'Buy', WDFMInstance(self::PLUGIN)->prefix ), 'stripe_message');
            }
          }
          ?>
          <div class="popup-body-col field_options">
            <div id="edit_table"></div>
          </div>
          <div class="popup-body-col field_preview">
          <div id="add-button-cont" class="add-button-cont">
            <button class="button button-primary button-hero wd-add-button" onclick="add(0, false); return false;">
              <?php _e('Add', WDFMInstance(self::PLUGIN)->prefix);?>
            </button>
          </div>
          <div id="show_table">
          </div>
        </div>
        </div>
      </div>
      <input type="hidden" id="old" />
      <input type="hidden" id="old_selected" />
      <input type="hidden" id="element_type" />
      <input type="hidden" id="editing_id" />
      <input type="hidden" value="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>" id="form_plugins_url" />
      <div id="main_editor" style="position: fixed; display: none; z-index: 140;">
        <?php if ( user_can_richedit() && $params['fm_enable_wp_editor'] ) {
          wp_editor('', 'form_maker_editor', array(
            'teeny' => TRUE,
            'textarea_name' => 'form_maker_editor',
            'media_buttons' => FALSE,
            'textarea_rows' => 5,
          ));
        }
        else { ?>
          <textarea name="form_maker_editor" id="form_maker_editor" class="mce_editable" aria-hidden="true"></textarea>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }

  /**
   * Form options.
   *
   * @param array $params
   */
	public function form_options( $params = array() ) {
	  echo $this->body_form_options($params);
		die();
	}
	
	/**
	* Generate page body form options.
	*
	* @param array $params
	* @return string Body html.
	*/
	private function body_form_options( $params = array() ) {
    $id = $params['id'];
    $page = $params['page'];
    $page_title = $params['page_title'];
    $page_url = $params['page_url'];
    $back_url = $params['back_url'];
    $fieldset_id = WDW_FM_Library(self::PLUGIN)->get( 'fieldset_id', 'general' );
    $addons = $params['addons'];
    $row = $params['row'];
    $default_theme = $params['default_theme'];
    $queries = $params['queries'];
    $userGroups = $params['userGroups'];
    $stripe_addon = $params['stripe_addon'];
    $payment_method = $params['payment_method'];
    $label_titles_for_submissions = array();
    $labels_id_for_submissions = array();
    $payment_info = $params['payment_info'];
    $labels_for_submissions = $params['labels_for_submissions'];
    if ( $labels_for_submissions ) {
      $labels_id_for_submissions = $params['labels_id_for_submissions'];
      $label_titles_for_submissions = $params['label_titles_for_submissions'];
    }
    $stats_labels_ids = $params['stats_labels_ids'];
    $stats_labels = $params['stats_labels'];
    ?>
  <div class="fm-options-container">
    <div class="submenu-box">
    <ul id="submenu" class="configuration">
      <li>
        <a id="general" class="fm_fieldset_tab" onclick="form_maker_options_tabs('general'); return false;" href="#"><?php _e('General Options', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <li>
        <a id="actions" class="fm_fieldset_tab" onclick="form_maker_options_tabs('actions'); return false;" href="#"><?php _e('Actions after Submission', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <li>
        <a id="payment" class="fm_fieldset_tab" onclick="form_maker_options_tabs('payment'); return false;" href="#"><?php _e('Payment Options', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <li>
        <a id="javascript" class="fm_fieldset_tab" onclick="form_maker_options_tabs('javascript'); codemirror_for_javascript(); return false;" href="#"><?php _e('JavaScript', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <li>
        <a id="conditions" class="fm_fieldset_tab" onclick="form_maker_options_tabs('conditions'); return false;" href="#"><?php _e('Conditional Fields', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <li>
        <a id="mapping" class="fm_fieldset_tab" onclick="form_maker_options_tabs('mapping'); return false;" href="#"><?php _e('MySQL Mapping', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <li>
        <a id="privacy" class="fm_fieldset_tab" onclick="form_maker_options_tabs('privacy'); return false;" href="#"><?php _e('Privacy', WDFMInstance(self::PLUGIN)->prefix); ?></a>
      </li>
      <?php
      if ( !empty($addons['tabs']) ) {
        foreach ( $addons['tabs'] as $addon => $name ) {
          ?>
          <li>
            <a id="<?php echo $addon; ?>" class="fm_fieldset_tab" onclick="form_maker_options_tabs('<?php echo $addon; ?>'); return false;" href="#"><?php echo $name; ?></a>
          </li>
          <?php
        }
      }
      ?>
    </ul>
  </div>
    <div class="fm-options-body fm_form_options">
      <div id="general_fieldset" class="adminform fm_fieldset_deactive">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-100 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Published', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="published" <?php echo $row->published == 1 ? 'checked="checked"' : '' ?> id="fm_go-published-1" class="wd-radio" value="1">
                  <label class="wd-label-radio" for="fm_go-published-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="published" <?php echo $row->published == 0 ? 'checked="checked"' : '' ?> id="fm_go-published-0" class="wd-radio" value="0">
                  <label class="wd-label-radio" for="fm_go-published-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </span>
								<span class="wd-group">
                  <label class="wd-label"><?php _e('Save data(to database)', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="savedb" <?php echo ( $row->savedb == 1 || $payment_method != "paypal" )  ? 'checked="checked"' : '' ?> id="fm_go-savedb-1" class="wd-radio" value="1">
                  	<label class="wd-label-radio" for="fm_go-savedb-1"><?php echo $payment_method != "paypal" ? __('Yes', WDFMInstance(self::PLUGIN)->prefix) : __('Before completion of payment.', WDFMInstance(self::PLUGIN)->prefix); ?></label> <?php echo  $payment_method == "paypal" ? "<br>" : ""; ?>
									<?php if ( $payment_method == "paypal" ) { ?>
										<p><input type="radio" name="savedb" <?php echo $row->savedb == 2 ? 'checked="checked"' : '' ?> id="fm_go-savedb-2" class="wd-radio" value="2">
											<label class="wd-label-radio" for="fm_go-savedb-2"><?php _e('After payment has been successfully completed.', WDFMInstance(self::PLUGIN)->prefix); ?></label></p>
									<?php } ?>
									<input type="radio" name="savedb" <?php echo $row->savedb == 0  ? 'checked="checked"' : '' ?> id="fm_go-savedb-0" class="wd-radio" value="0">
                  	<label class="wd-label-radio" for="fm_go-savedb-0"><?php echo $payment_method != "paypal" ? __('No', WDFMInstance(self::PLUGIN)->prefix) : __('Do not save', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<p class="description"><?php _e('IMPORTANT! If you disable this option, the information submitted through this form will not be saved in the database and will not be displayed on the Submissions page.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="requiredmark"><?php _e('Required fields mark', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="text" id="requiredmark" name="requiredmark" value="<?php echo $row->requiredmark; ?>">
              <p class="description"><?php _e('Use this option to change the mark for required fields of your form.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Save Uploads', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="save_uploads" <?php echo $row->save_uploads == 1 ? 'checked="checked"' : '' ?> id="fm_go-save_uploads-1" class="wd-radio" value="1">
                  <label class="wd-label-radio" for="fm_go-save_uploads-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="save_uploads" <?php echo $row->save_uploads == 0 ? 'checked="checked"' : '' ?> id="fm_go-save_uploads-0" class="wd-radio" value="0">
                  <label class="wd-label-radio" for="fm_go-save_uploads-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              <p class="description"><?php _e('IMPORTANT! If you disable this option, the files uploaded through your form will not be saved on your site. The files will still be sent to emails and saved in Google Drive or Dropbox, if configured.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="submissions_limit"><?php _e('Submission Limit', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="number" id="submissions_limit" name="submissions_limit" value="<?php echo $row->submissions_limit; ?>">
              <p class="description"><?php _e( 'Limit number of submissions for form. In the case of a 0 (zero), submissions will be unlimited.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="submissions_limit_text"><?php _e('Submission Limit notification text', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="text" id="submissions_limit_text" name="submissions_limit_text" value="<?php echo $row->submissions_limit_text; ?>">
              <p class="description"><?php _e('Text to displays when submission limit has been reached.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </span>
              </div>
            </div>
            <div class="wd-box-section">
              <div class="wd-box-content">
                <?php
                if (WDFMInstance(self::PLUGIN)->is_free) {
                  echo $this->free_message(__('This functionality is available in Premium version', WDFMInstance(self::PLUGIN)->prefix));
                }
                ?>
                <span class="wd-group <?php if(WDFMInstance(self::PLUGIN)->is_free) { echo 'fm-free-option'; } ?>">
                  <label class="wd-label"><?php _e('Allow User to see submissions', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <?php
                    $checked_UserGroup = explode(',', $row->user_id_wd);
                    $i = 0;
                    foreach ( $userGroups as $val => $uG ) {
                    echo "\r\n" . '<input type="checkbox" value="' . $val . '"  id="user_' . $i . '" ';
                    if ( in_array($val, $checked_UserGroup) ) {
                      echo ' checked="checked"';
                    }
                    echo ' onchange="acces_level(' . count($userGroups) . ')" ' . disabled(WDFMInstance(self::PLUGIN)->is_free, true, false) . ' /><label for="user_' . $i . '">' . $uG["name"] . '</label><br>';
                    $i++;
                    }
                    ?>
                  <input type="checkbox" value="guest" id="user_<?php echo $i; ?>" onchange="acces_level(<?php echo count($userGroups); ?>)"<?php echo(in_array('guest', $checked_UserGroup) ? 'checked="checked"' : '') ?> <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> /><label for="user_<?php echo $i; ?>">Guest</label>
                  <input type="hidden" name="user_id_wd" value="<?php echo $row->user_id_wd ?>" id="user_id_wd" />
                  <p class="description"><?php _e('Mark all user roles which will be able to view front-end submissions, when you publish them on a post or page.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                 </span>
                <?php if ( count($label_titles_for_submissions) ) { ?>
                  <span class="wd-group <?php if(WDFMInstance(self::PLUGIN)->is_free) { echo 'fm-free-option'; } ?>">
                    <label class="wd-label"><?php _e('Fields to hide in frontend submissions', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <ul id="ul_form_fields">
                      <li>
                        <input type="checkbox" name="all_fields" id="all_fields" onclick="checkAllByParentId('ul_form_fields'); checked_labels('filed_label')" value="submit_id,<?php echo implode(',', $labels_id_for_submissions) . "," . ($payment_info ? "payment_info" : ""); ?>" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> />
                        <label for="all_fields"><?php _e('Select All', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                      </li>
                      <?php
                      echo "<li><input type=\"checkbox\" id=\"submit_id\" name=\"submit_id\" value=\"submit_id\" class=\"filed_label\"  onclick=\"checked_labels('filed_label')\" " . disabled(WDFMInstance(self::PLUGIN)->is_free, true, false) . " /><label for=\"submit_id\">ID</label></li>";
                      for ( $i = 0, $n = count($label_titles_for_submissions); $i < $n; $i++ ) {
                        $field_label = $label_titles_for_submissions[$i];
                        echo "<li><input type=\"checkbox\" id=\"filed_label" . $i . "\" name=\"filed_label" . $i . "\" value=\"" . $labels_id_for_submissions[$i] . "\" class=\"filed_label\" onclick=\"checked_labels('filed_label')\" " . disabled(WDFMInstance(self::PLUGIN)->is_free, true, false) . " /><label for=\"filed_label" . $i . "\">" . (strlen($field_label) > 80 ? substr($field_label, 0, 80) . '...' : $field_label) . "</label></li>";
                      }
                      if ( $payment_info ) {
                        echo "<li><input type=\"checkbox\" id=\"payment_info\" name=\"payment_info\" value=\"payment_info\" class=\"filed_label\" onclick=\"checked_labels('filed_label')\" " . disabled(WDFMInstance(self::PLUGIN)->is_free, true, false) . " /><label for=\"payment_info\">Payment Info</label></li>";
                      }
                      ?>
                    </ul>
                    <input type="hidden" name="frontend_submit_fields" value="<?php echo $row->frontend_submit_fields ?>" id="frontend_submit_fields" />
                    <p class="description"><?php _e('Select fields of the form and Stats Fields which will not to be displayed within front-end submissions, when you publish them.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </span>
                  <?php if ( $stats_labels ) { ?>
                    <span class="wd-group">
                      <label class="wd-label"><?php _e('Stats fields:', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                        <ul id="stats_fields">
                          <li>
                            <input type="checkbox" name="all_stats_fields" id="all_stats_fields" onclick="checkAllByParentId('stats_fields'); checked_labels('stats_filed_label');" value="<?php echo implode(',', $stats_labels_ids) . ","; ?>" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> />
                            <label for="all_stats_fields"><?php _e('Select All', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                          </li>
                           <?php
                            for ( $i = 0, $n = count($stats_labels); $i < $n; $i++ ) {
                              $field_label = $stats_labels[$i];
                              echo "<li><input type=\"checkbox\" id=\"stats_filed_label" . $i . "\" name=\"stats_filed_label" . $i . "\" value=\"" . $stats_labels_ids[$i] . "\" class=\"stats_filed_label\" onclick=\"checked_labels('stats_filed_label')\" " . disabled(WDFMInstance(self::PLUGIN)->is_free, true, false) . " /><label for=\"stats_filed_label" . $i . "\">" . (strlen($field_label) > 80 ? substr($field_label, 0, 80) . '...' : $field_label) . "</label></li>";
                            }
                            ?>
                         </ul>
                      <input type="hidden" name="frontend_submit_stat_fields" value="<?php echo $row->frontend_submit_stat_fields ?>" id="frontend_submit_stat_fields" />
                      <p class="description"><?php _e('Select fields of the form and Stats Fields which will not to be displayed within front-end submissions, when you publish them.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                    </span>
                  <?php }
                } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="actions_fieldset" class="adminform fm_fieldset_deactive">
        <div class="wd-table">
          <div class="wd-table-col-100">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Action type', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input class="wd-radio" type="radio" name="submit_text_type" id="text_type_none" onclick="set_type('none')" value="1" <?php echo ($row->submit_text_type != 2 && $row->submit_text_type != 3 && $row->submit_text_type != 4 && $row->submit_text_type != 5) ? "checked" : ""; ?> />
                  <label class="wd-label-radio" for="text_type_none"><?php _e('Stay on Form', WDFMInstance(self::PLUGIN)->prefix); ?></label><br />
                  <input class="wd-radio" type="radio" name="submit_text_type" id="text_type_post" onclick="set_type('post')" value="2" <?php echo ($row->submit_text_type == 2) ? "checked" : ""; ?> />
                  <label class="wd-label-radio" for="text_type_post"><?php _e('Post', WDFMInstance(self::PLUGIN)->prefix); ?></label><br />
                  <input class="wd-radio" type="radio" name="submit_text_type" id="text_type_page" onclick="set_type('page')" value="5" <?php echo ($row->submit_text_type == 5) ? "checked" : ""; ?> />
                  <label class="wd-label-radio" for="text_type_page"><?php _e('Page', WDFMInstance(self::PLUGIN)->prefix); ?></label><br />
                  <input class="wd-radio" type="radio" name="submit_text_type" id="text_type_custom_text" onclick="set_type('custom_text')" value="3" <?php echo ($row->submit_text_type == 3) ? "checked" : ""; ?> />
                  <label class="wd-label-radio" for="text_type_custom_text"><?php _e('Custom Text', WDFMInstance(self::PLUGIN)->prefix); ?></label><br />
                  <input class="wd-radio" type="radio" name="submit_text_type" id="text_type_url" onclick="set_type('url_wrap')" value="4" <?php echo ($row->submit_text_type == 4) ? "checked" : ""; ?> />
                  <label class="wd-label-radio" for="text_type_url"><?php _e('URL', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </div>
                <div id="post" <?php echo(($row->submit_text_type != 2) ? 'style="display:none"' : ''); ?>>
                  <label class="wd-label"><?php _e('Post', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <select id="post_name" name="post_name">
                    <option value="0">- Select Post -</option>
                      <?php
                      $args = array( 'posts_per_page' => 10000 );
                      query_posts($args);
                      while ( have_posts() ) : the_post();
                      ?>
                      <option value="<?php $x = get_permalink(get_the_ID());
                      echo $x; ?>" <?php echo(($row->article_id == $x) ? 'selected="selected"' : ''); ?>><?php the_title(); ?></option>
                      <?php
                      endwhile;
                      wp_reset_query();
                      ?>
                  </select>
                </div>
                <div id="page" <?php echo(($row->submit_text_type != 5) ? 'style="display:none"' : ''); ?>>
                  <label class="wd-label"><?php _e('Page', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <select id="page_name" name="page_name">
                    <option value="0">- Select Page -</option>
                    <?php
                      $pages = get_pages();
                      foreach ( $pages as $page ) {
                      $page_id = get_page_link($page->ID);
                      ?>
                        <option value="<?php echo $page_id; ?>" <?php echo(($row->article_id == $page_id) ? 'selected="selected"' : ''); ?>><?php echo $page->post_title; ?></option>
                      <?php
                    }
                    wp_reset_query();
                    ?>
                  </select>
                </div>
                <div class="wd-group">
                  <div id="custom_text" <?php echo(($row->submit_text_type != 3) ? 'style="display: none;"' : ''); ?>>
                    <label class="wd-label"><?php _e('Text', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <div class="wd-editor-placeholder">
                      <span class="dashicons dashicons-list-view" data-id="submit_text"></span>
                    </div>
                    <?php
                    if ( user_can_richedit() && $params['fm_enable_wp_editor'] ) {
                      wp_editor($row->submit_text, 'submit_text', array(
                      'teeny' => TRUE,
                      'textarea_name' => 'submit_text',
                      'media_buttons' => FALSE,
                      'textarea_rows' => 5
                      ));
                    }
                    else {
                      ?>
                      <textarea autocomplete="off" cols="36" rows="5" id="submit_text" name="submit_text" style="resize: vertical; width:100%">
                        <?php echo $row->submit_text; ?>
                      </textarea>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div class="wd-group">
                  <div id="url_wrap" class="wd-has-placeholder" <?php echo(($row->submit_text_type != 4) ? 'style="display:none"' : ''); ?>>
                  <label class="wd-label"><?php _e('URL', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="text" id="url" name="url" value="<?php echo $row->url; ?>" />
                  <span class="dashicons dashicons-list-view" data-id="url"></span>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="payment_fieldset" class="adminform fm_fieldset_deactive">
        <div class="wd-table">
          <div class="wd-table-col-100">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Payment Method', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="paypal_mode" id="paypal_mode0" value="none" class="wd-radio" <?php echo ($payment_method == "none") ? "checked" : ""; ?> onchange="fm_change_payment_method('none');" />
                  <label class="wd-label-radio" for="paypal_mode0"><?php _e('None', WDFMInstance(self::PLUGIN)->prefix); ?></label><br>
                  <input type="radio" name="paypal_mode" id="paypal_mode1" value="paypal" class="wd-radio" <?php echo ($payment_method == "paypal") ? "checked" : ""; ?> onchange="fm_change_payment_method('paypal');" />
                  <label class="wd-label-radio" for="paypal_mode1"><?php _e('Paypal', WDFMInstance(self::PLUGIN)->prefix); ?></label><br>
                  <input type="radio" name="paypal_mode" id="paypal_mode2" value="stripe" <?php echo ($payment_method == "stripe") ? "checked" : ""; ?> class="wd-radio" onchange="fm_change_payment_method('stripe');" />
                  <label class="wd-label-radio" for="paypal_mode2"><?php _e('Stripe', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </div>
                <div class="fm_payment_option">
                  <?php
                  if (WDFMInstance(self::PLUGIN)->is_free) {
                    echo $this->free_message(__('PAYPAL is available in Premium version', WDFMInstance(self::PLUGIN)->prefix));
                  }
                  ?>
                  <div class="wd-group <?php if(WDFMInstance(self::PLUGIN)->is_free) { echo 'fm-free-option'; } ?>">
                    <label class="wd-label" for="payment_currency"><?php _e('Payment Currency', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <select id="payment_currency " name="payment_currency" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?>>
                      <option value="USD" <?php echo(($row->payment_currency == 'USD') ? 'selected' : ''); ?>>$ &#8226; U.S. Dollar</option>
                      <option value="EUR" <?php echo(($row->payment_currency == 'EUR') ? 'selected' : ''); ?>>&#8364; &#8226; Euro</option>
                      <option value="GBP" <?php echo(($row->payment_currency == 'GBP') ? 'selected' : ''); ?>>&#163; &#8226; Pound Sterling</option>
                      <option value="JPY" <?php echo(($row->payment_currency == 'JPY') ? 'selected' : ''); ?>>&#165; &#8226; Japanese Yen</option>
                      <option value="CAD" <?php echo(($row->payment_currency == 'CAD') ? 'selected' : ''); ?>>C$ &#8226; Canadian Dollar</option>
                      <option value="MXN" <?php echo(($row->payment_currency == 'MXN') ? 'selected' : ''); ?>>Mex$ &#8226; Mexican Peso</option>
                      <option value="HKD" <?php echo(($row->payment_currency == 'HKD') ? 'selected' : ''); ?>>HK$ &#8226; Hong Kong Dollar</option>
                      <option value="HUF" <?php echo(($row->payment_currency == 'HUF') ? 'selected' : ''); ?>>Ft &#8226; Hungarian Forint</option>
                      <option value="NOK" <?php echo(($row->payment_currency == 'NOK') ? 'selected' : ''); ?>>kr &#8226; Norwegian Kroner</option>
                      <option value="NZD" <?php echo(($row->payment_currency == 'NZD') ? 'selected' : ''); ?>>NZ$ &#8226; New Zealand Dollar</option>
                      <option value="SGD" <?php echo(($row->payment_currency == 'SGD') ? 'selected' : ''); ?>>S$ &#8226; Singapore Dollar</option>
                      <option value="SEK" <?php echo(($row->payment_currency == 'SEK') ? 'selected' : ''); ?>>kr &#8226; Swedish Kronor</option>
                      <option value="PLN" <?php echo(($row->payment_currency == 'PLN') ? 'selected' : ''); ?>>zl &#8226; Polish Zloty</option>
                      <option value="AUD" <?php echo(($row->payment_currency == 'AUD') ? 'selected' : ''); ?>>A$ &#8226; Australian Dollar</option>
                      <option value="DKK" <?php echo(($row->payment_currency == 'DKK') ? 'selected' : ''); ?>>kr &#8226; Danish Kroner</option>
                      <option value="CHF" <?php echo(($row->payment_currency == 'CHF') ? 'selected' : ''); ?>>CHF &#8226; Swiss Francs</option>
                      <option value="CZK" <?php echo(($row->payment_currency == 'CZK') ? 'selected' : ''); ?>>Kc &#8226; Czech Koruny</option>
                      <option value="ILS" <?php echo(($row->payment_currency == 'ILS') ? 'selected' : ''); ?>>&#8362; &#8226; Israeli Sheqel</option>
                      <option value="BRL" <?php echo(($row->payment_currency == 'BRL') ? 'selected' : ''); ?>>R$ &#8226; Brazilian Real</option>
                      <option value="TWD" <?php echo(($row->payment_currency == 'TWD') ? 'selected' : ''); ?>>NT$ &#8226; Taiwan New Dollars</option>
                      <option value="MYR" <?php echo(($row->payment_currency == 'MYR') ? 'selected' : ''); ?>>RM &#8226; Malaysian Ringgit</option>
                      <option value="PHP" <?php echo(($row->payment_currency == 'PHP') ? 'selected' : ''); ?>>&#8369; &#8226; Philippine Peso</option>
                      <option value="THB" <?php echo(($row->payment_currency == 'THB') ? 'selected' : ''); ?>>&#xe3f; &#8226; Thai Bahtv</option>
                      <option value="HRK" <?php echo(($row->payment_currency == 'HRK') ? 'selected' : ''); ?>>kn &#8226; Croatian Kuna</option>
											<option value="PKR" <?php echo(($row->payment_currency == 'PKR') ? 'selected' : ''); ?>>Rs &#8226; Pakistani Rupee</option>
											<option value="KES" <?php echo(($row->payment_currency == 'KES') ? 'selected' : ''); ?>>KSh &#8226; Kenya Shillings</option>
                      <option value="UGX" <?php echo(($row->payment_currency == 'UGX') ? 'selected' : ''); ?>>USh &#8226; Uganda Shillings</option>
                      <option value="TZS" <?php echo(($row->payment_currency == 'TZS') ? 'selected' : ''); ?>>TSh &#8226; Tanzanian Shillings</option>
                      <option value="RWF" <?php echo(($row->payment_currency == 'RWF') ? 'selected' : ''); ?>>FRw &#8226; Rwandan Franc</option>
                      <option value="NGN" <?php echo(($row->payment_currency == 'NGN') ? 'selected' : ''); ?>>&#8358; &#8226; Nigerian Naira</option>
                      <option value="ZAR" <?php echo(($row->payment_currency == 'ZAR') ? 'selected' : ''); ?>>R &#8226; South African Rand</option>
                      <option value="GHS" <?php echo(($row->payment_currency == 'GHS') ? 'selected' : ''); ?>>GH&#8373; &#8226; Ghanaian cedi</option>
                    </select>
                    <p class="description"><?php _e('Choose the currency to be used for the payments made through your form.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group <?php if(WDFMInstance(self::PLUGIN)->is_free) { echo 'fm-free-option'; } ?>">
                    <label class="wd-label" for="tax"><?php _e('Tax', WDFMInstance(self::PLUGIN)->prefix); ?> (%)</label>
                    <input type="text" name="tax" id="tax" value="<?php echo $row->tax; ?>" class="text_area" onKeyPress="return check_isnum_point(event)" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> />
                    <p class="description"><?php _e('Specify the percentage of the tax. It will be calculated from the total payment amount of your form.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                  <div class="wd-group <?php if(WDFMInstance(self::PLUGIN)->is_free) { echo 'fm-free-option'; } ?>">
                    <label class="wd-label"><?php _e('Checkout Mode', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="checkout_mode" <?php echo ( isset($row->checkout_mode) && $row->checkout_mode == 1) ? 'checked="checked"' : '' ?> id="checkout_mode-1" class="wd-radio" value="1" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> />
                    <label class="wd-label-radio" for="checkout_mode-1"><?php _e('Production', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input type="radio" name="checkout_mode" <?php echo ( !isset($row->checkout_mode) || $row->checkout_mode == 0 || $row->checkout_mode == 'testmode') ? 'checked="checked"' : '' ?> id="checkout_mode-0" class="wd-radio" value="0" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> />
                    <label class="wd-label-radio" for="checkout_mode-0"><?php _e('Testmode', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  </div>
                  <div class="wd-group <?php if(WDFMInstance(self::PLUGIN)->is_free) { echo 'fm-free-option'; } ?>">
                    <label class="wd-label" for="paypal_email"><?php _e('Paypal email', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                    <input class="fm-validate" data-type="email" data-callback="fm_validate_email" data-callback-parameter="#paypal_mode1" data-tab-id="payment" data-content-id="payment_fieldset" type="text" name="paypal_email" id="paypal_email" value="<?php echo $row->paypal_email; ?>" class="text_area" <?php disabled(WDFMInstance(self::PLUGIN)->is_free, true) ?> />
                    <p class="description"><?php _e('Provide the email address of a valid PayPal account. It will receive the payments made through your form.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  </div>
                </div>
                <div class="fm_payment_option_stripe">
                  <?php
                  if ( $stripe_addon['enable'] && !empty($stripe_addon['html']) ) {
                    if (WDFMInstance(self::PLUGIN)->is_free) {
                      echo $this->free_message(__('STRIPE extension compatible with Premium version only', WDFMInstance(self::PLUGIN)->prefix));
                    }
                    else {
                      echo $stripe_addon[ 'html' ];
                    }
                  }
                  else {
                    if (WDFMInstance(self::PLUGIN)->is_free) {
                      echo $this->promo_box(__('This feature is available only in the Premium version', WDFMInstance(self::PLUGIN)->prefix), __('Requires STRIPE extension.', WDFMInstance(self::PLUGIN)->prefix), 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin#plugin_extensions');
                    }
                    else {
                      echo $this->free_message(__('This feature requires STRIPE extension', WDFMInstance(self::PLUGIN)->prefix), 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin#plugin_extensions', __( 'Buy', WDFMInstance(self::PLUGIN)->prefix ));
                    }
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="javascript_fieldset" class="adminform fm_fieldset_deactive">
        <div class="wd-table">
          <div class="wd-table-col-100">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <textarea cols="60" rows="30" name="javascript" id="form_javascript"><?php echo $row->javascript; ?></textarea>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="conditions_fieldset" class="adminform fm_fieldset_deactive">
        <?php
        $ids = array();
        $types = array();
        $labels = array();
        $paramss = array();
        $all_ids = array();
        $all_labels = array();
        // Can not be selected as a condition value
        $not_has_condition_logic = array(
          "type_recaptcha",
          "type_captcha",
          "type_arithmetic_captcha",
          "type_submit_reset",
          "type_mark_map",
          "type_editor",
          "type_map",
          "type_grading",
          "type_matrix",
          "type_button",
          "type_password",
          "type_signature",
          "type_paypal_total",
          "type_stripe"
        );
        $select_type_fields = array(
          "type_country",
          "type_address",
          "type_checkbox",
          "type_radio",
          "type_own_select",
          "type_paypal_select",
          "type_paypal_checkbox",
          "type_paypal_radio",
          "type_paypal_shipping"
        );
        $fields = explode('*:*new_field*:*', $row->form_fields);
        $fields = array_slice($fields, 0, count($fields) - 1);
        $address_fields_array = array();
        foreach ( $fields as $i => $field ) {
          $temp = explode('*:*id*:*', $field);
          $id_field = $temp[0];
          array_push($ids, $id_field);
          array_push($all_ids, $id_field);
          $temp = explode('*:*type*:*', $temp[1]);
          $type = $temp[0];
          if ( $type == 'type_address') {
            $address_fields_array[$i] = $field;
          }
          array_push($types, $type);
          // The label name is formed by type.
          if ($type != 'type_submit_reset') {
            $temp = explode('*:*w_field_label*:*', $temp[1]);
            $label_name = $temp[0];
            if ( $type == 'type_button' ) {
              $temp = explode('*:*w_title*:*', $temp[1]);
              $label_name = $temp[0];
            }
          }
          else {
            // Submit button case.
            $temp = explode('*:*w_field_label*:*', $temp[1]);
            $temp = explode('*:*w_submit_title*:*', $temp[1]);
            $label_name = $temp[0];
          }
          /* Check if data string has db_info data to run converter which is using in conditional fields */
          if ( strpos($temp[1], '[db_info]') !== false ) {
            $temp[1] = $this->get_label_values_from_db( $temp[1], $type );
          }
          array_push($labels, str_replace(array("'",'"'),array('%quot%','%dquot%'), $label_name));
          array_push($all_labels, str_replace(array("'",'"'),array('%quot%','%dquot%'), $label_name));
          array_push($paramss, str_replace(array("'",'"'),array('%quot%','%dquot%'), $temp[1]));
        }
        $params_names = array();
        $address_params_names = array(
          'id',
          'type',
          'w_field_label',
          'w_field_label_size',
          'w_field_label_pos',
          'w_hide_label',
          'w_size',
          'w_mini_labels',
          'w_disabled_fields',
          'w_required',
          'w_class',
        );
        if ( !empty($address_fields_array) ) {
          foreach ( $address_fields_array as $i => $fields ) {
            foreach ( $address_params_names as $address_params_key ) {
              $tmp_field = explode('*:*' . $address_params_key . '*:*', $fields);
              $params_names[$i][$address_params_key] = esc_html($tmp_field[0]);
              $fields = $tmp_field[1];
            }
          }
        }
        if ( !empty($params_names) ) {
          foreach ( $params_names as $val ) {
            $address_mini_labels = explode('***', $val['w_mini_labels']);
            foreach ( $address_mini_labels as $i => $label ) {
              $key = $val['id'] . '_address_' . $i;
              $type_key = ($i < 5) ? 'type_text' : 'type_address';
              $val['w_field_label'] = str_replace(array("'",'"'),array('%quot%','%dquot%'), htmlspecialchars_decode($val['w_field_label']));
              $label = str_replace(array("'",'"'),array('%quot%','%dquot%'), htmlspecialchars_decode($label));
              $value = $val['w_field_label'] . ' (' . $label . ')';
              array_push($ids, $key);
              array_push($all_ids, $key);
              array_push($labels, $value);
              array_push($all_labels, $value);
              array_push($types, $type_key);
            }
          }
          array_reverse($ids, true);
          array_reverse($all_ids, true);
          array_reverse($labels, true);
          array_reverse($all_labels, true);
          array_reverse($types, true);
        }
        $ids_index = array();
        foreach ( $ids as $index_key => $index_value ) {
          $ids_index[$index_value] = $index_key;
        }
        $ids_for_match = $ids;
        $all_ids_for_match = $all_ids;
        $labels_for_match = $labels;
        $all_labels_for_match = $all_labels;
        $paramss_for_match = $paramss;
        $types_for_match = $types;
        foreach ( $types as $key => $value ) {
          if ( in_array($value, $not_has_condition_logic) ) {
            unset($ids_for_match[$key]);
            unset($labels_for_match[$key]);
            unset($types_for_match[$key]);
            unset($paramss_for_match[$key]);
          }
        }
        if ( !empty($address_fields_array) ) {
          foreach ( $address_fields_array as $key => $val ) {
            unset($ids_for_match[$key]);
            unset($all_ids_for_match[$key]);
            unset($labels_for_match[$key]);
            unset($all_labels_for_match[$key]);
            unset($types_for_match[$key]);
          }
        }
        $json_conditions_data = json_encode(array(
          'ids_index' => $ids_index,
          'ids' => $ids,
          'ids_for_match' => $ids_for_match,
          'all_ids' => $all_ids,
          'all_ids_for_match' => $all_ids_for_match,
          'labels' =>  str_replace(array("'",'"'), array('%quot%','%dquot%'),$labels),
          'labels_for_match' => str_replace(array("'",'"'), array('%quot%','%dquot%'),$labels_for_match),
          'all_labels' => str_replace(array("'",'"'), array('%quot%','%dquot%'),$all_labels),
          'all_labels_for_match' => str_replace(array("'",'"'), array('%quot%','%dquot%'),$all_labels_for_match),
          'types' => $types,
          'types_for_match' => $types_for_match,
          'params' => str_replace(array("'",'"'), array('%quot%','%dquot%'),$paramss),
          'params_for_match' => str_replace(array("'",'"'), array('%quot%','%dquot%'),$paramss_for_match)));

        $show_hide = array();
        $field_label = array();
        $all_any = array();
        $condition_params = array();
        $count_of_conditions = 0;

        if ( $row->condition != '' ) {
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
        }
        else {
          $show_hide[0] = 1;
          $all_any[0] = 'and';
          $condition_params[0] = '';
          if ( $all_ids ) {
            $field_label[0] = $all_ids[0];
          }
        }
        ?>
        <div class="wd-table">
          <div class="wd-table-col-100">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <div class="wd-group" id="conditions_fieldset_wrap">
                  <p class="description"><?php _e('Press Add Condition button to configure the first condition of your form. Show/Hide select box represents the action which will be completed, if all or any of the condition statements are fulfilled. Use the second drop-down menu to select the field which will be shown or hidden. Click the little Plus (+) icon to add the statement of your form condition.', WDFMInstance(self::PLUGIN)->prefix); ?><br><br></p>
                  <div style="text-align: right;">
                    <span id="fm-conditions-json" data-conditions-json='<?php echo $json_conditions_data; ?>'></span>
                    <button class="wd-button button-primary" onclick="add_condition(); return false;"><?php _e('Add Condition', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                  </div>
                  <?php
                    for ( $k = 0; $k < $count_of_conditions; $k++ ) {
                      if ( in_array($field_label[$k], $all_ids) ) { ?>
                        <div id="condition<?php echo $k; ?>" class="fm_condition">
                          <div id="conditional_fileds<?php echo $k; ?>">
                            <select id="show_hide<?php echo $k; ?>" name="show_hide<?php echo $k; ?>" class="fm_condition_show_hide">
                              <option value="1" <?php if ( $show_hide[$k] == 1 ) {
                                echo 'selected="selected"';
                              } ?>><?php _e('Show', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option value="0" <?php if ( $show_hide[$k] == 0 ) {
                                echo 'selected="selected"';
                              } ?>><?php _e('Hide', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                            </select>
                            <?php
                              $disabled = ( !empty($condition_params[$k]) ) ? 'disabled="disabled"' : '';
                            ?>
                            <select id="fields<?php echo $k; ?>" name="fields<?php echo $k; ?>" class="fm_condition_fields" <?php echo $disabled?>>
                              <?php
                              foreach ( $labels as $key => $value ) {
                                $selected = ( $field_label[$k] == $all_ids[$key] ) ? 'selected="selected"' : '';
                                echo '<option value="' . $all_ids[$key] . '" ' . $selected . '>' . str_replace(array('%quot%','%dquot%'),array("'",'"'),$value) . '</option>';
                              }
                              ?>
                            </select>
                            <span>if</span>
                            <select id="all_any<?php echo $k; ?>" name="all_any<?php echo $k; ?>" class="fm_condition_all_any">
                              <option value="and" <?php if ( $all_any[$k] == "and" ) {
                                echo 'selected="selected"';
                              } ?>><?php _e('all', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option value="or" <?php if ( $all_any[$k] == "or" ) {
                                echo 'selected="selected"';
                              } ?>><?php _e('any', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                            </select>
                            <span style="display: inline-block; width: 100%; max-width: 235px;"><?php _e('of the following match:', WDFMInstance(self::PLUGIN)->prefix); ?></span>
                            <span class="dashicons dashicons-trash" onclick="delete_condition('<?php echo $k; ?>')"></span>
                            <span id="fm-condition-fields-<?php echo $k; ?>"
                                  data-conditions-field-ids='<?php echo json_encode($ids);?>'
                                  data-conditions-field-labels='<?php echo json_encode($labels);?>'
                                  data-conditions-field-types='<?php echo json_encode($types);?>'
                                  data-conditions-field-params='<?php echo json_encode($paramss);?>'
                                  data-conditions-field-ids_for_match='<?php echo json_encode($ids_for_match);?>'
                                  data-conditions-field-labels_for_match='<?php echo json_encode($labels_for_match);?>'
                                  data-conditions-field-types_for_match='<?php echo json_encode($types_for_match);?>'
                                  data-conditions-field-params_for_match='<?php echo json_encode($paramss_for_match);?>'
                            ></span>
                            <span class="dashicons dashicons-plus-alt" onclick="add_condition_fields(<?php echo $k; ?>)"></span>
                          </div>
                          <?php
                          if ( $condition_params[$k] ) {
                            $_params = explode('*:*next_condition*:*', $condition_params[$k]);
                            $_params = array_slice($_params, 0, count($_params) - 1);
                            foreach ( $_params as $key => $_param ) {
                              $key_select_or_input = '';
                              $param_values = explode('***', $_param);
                              $multiselect = explode('@@@', $param_values[2]);
                              // To remove the last element from array we are using array_pop() function
                              if ( count( $multiselect ) > 1 ) {
                                array_pop( $multiselect );
                              }
                              if ( in_array($param_values[0], $ids) ) { ?>
                                <div id="condition_div<?php echo $k; ?>_<?php echo $key; ?>" class="fm_condition_div">
                                <select id="field_labels<?php echo $k; ?>_<?php echo $key; ?>" class="fm_condition_field_labels" onchange="change_choices(this.options[this.selectedIndex].id+'_<?php echo $key; ?>')">
                                  <?php
                                  foreach ( $labels_for_match as $key_label => $value_label ) {
                                    $selected = '';
                                    if ( $param_values[0] == $ids[$key_label] ) {
                                      $key_select_or_input = $ids_index[$param_values[0]];
                                      $selected = 'selected="selected"';
                                      $multiple = ($types[$key_select_or_input] == "type_checkbox" || $types[$key_select_or_input] == "type_paypal_checkbox") ? 'multiple="multiple" class="multiple_select"' : '';
                                    }
                                    if ( $field_label[$k] != $ids[$key_label] ) {
                                      echo '<option id="' . $k . '_' . $key_label . '" value="' . $ids[$key_label] . '" ' . $selected . '>' . str_replace(array('%quot%','%dquot%'),array("'",'"'),$value_label) . '</option>';
                                    }
                                  }
                                  ?>
                                </select>
                                <?php
                                  WDW_FM_Library::is_select_condition_HTML($types[$key_select_or_input], $k, $key, $param_values[1]);
                                  switch ( $types[$key_select_or_input] ) {
                                    case "type_own_select":
                                    case "type_paypal_select":
                                      $w_size = explode('*:*w_size*:*', $paramss[$key_select_or_input]);
                                      break;
                                    case "type_radio":
                                    case "type_checkbox":
                                    case "type_paypal_radio":
                                    case "type_paypal_checkbox":
                                    case "type_paypal_shipping":
                                      $w_size = explode('*:*w_flow*:*', $paramss[$key_select_or_input]);
                                      break;
                                  }
                                  $w_choices_array = array();
                                  if ( !empty($w_size) ) {
                                    $w_choices = explode('*:*w_choices*:*', $w_size[1]);
                                    $w_choices_array = explode('***', $w_choices[0]);
                                    if ( $types[$key_select_or_input] == 'type_radio' || $types[$key_select_or_input] == 'type_checkbox' || $types[$key_select_or_input] == 'type_own_select' ) {
                                      if ( strpos($w_choices[1], 'w_value_disabled') > -1 ) {
                                        // case for enabled use_for_submission
                                        if ( strpos($w_choices[1], 'w_use_for_submission') === FALSE ) {
                                          $w_value_disabled = explode('*:*w_value_disabled*:*', $w_choices[1]);
                                        }
                                        else {
                                          $w_value_disabled = explode('*:*w_use_for_submission*:*', $w_choices[1]);
                                        }
                                        $w_choices_value = explode('*:*w_choices_value*:*', $w_value_disabled[1]);
                                        $w_choices_value = $w_choices_value[0];
                                      }
                                      if ( isset($w_choices_value) ) {
                                        $w_choices_value_array = explode('***', $w_choices_value);
                                      }
                                      else {
                                        $w_choices_value_array = $w_choices_array;
                                      }
                                    }
                                    else {
                                      $w_choices_price = explode('*:*w_choices_price*:*', $w_choices[1]);
                                      $w_choices_value = $w_choices_price[0];
                                      $w_choices_value_array = explode('***', $w_choices_value);
                                    }
                                  }
                                  // Hides fields for which the 'conditionfield_select_value' field is not applicable.
                                  $showHide = ( in_array( $param_values[1], array('empty', 'checked', 'unchecked', '=', '!') ) ) ? 'style="display:none"' : '';
                                ?>
                                <?php if ( $key_select_or_input !== '' && in_array($types[$key_select_or_input], $select_type_fields) ) { ?>
                                  <select id="field_value<?php echo $k; ?>_<?php echo $key; ?>" <?php echo $multiple; echo $showHide?> class="fm_condition_field_select_value">
                                    <?php
                                    for ( $m = 0; $m < count($w_choices_array); $m++ ) {
                                      if ( $types[$key_select_or_input] == "type_paypal_checkbox" || $types[$key_select_or_input] == "type_paypal_radio" || $types[$key_select_or_input] == "type_paypal_shipping" || $types[$key_select_or_input] == "type_paypal_select" ) {
                                        $w_choice = $w_choices_array[$m] . '*:*value*:*' . $w_choices_value_array[$m];
                                      }
                                      else {
                                        $w_choice = $w_choices_value_array[$m];
                                      }
                                      $selected = ( in_array($w_choice, $multiselect) ) ? 'selected="selected"' : '';
                                      if ( strpos($w_choices_array[$m], '[') === FALSE && strpos($w_choices_array[$m], ']') === FALSE ) {
                                        echo '<option id="choise_' . $k . '_' . $m . '" value="' . $w_choice . '" ' . $selected . '>' . str_replace(array('%quot%','%dquot%'),array("'",'"'),$w_choices_array[$m]) . '</option>';
                                      }
                                    }
                                    if ( in_array($types[$key_select_or_input], array('type_address', 'type_country')) ) {
                                      $w_countries = WDW_FM_Library(self::PLUGIN)->get_countries();
                                      foreach ( $w_countries as $w_country ) {
                                        $selected = ( in_array($w_country, $multiselect) ) ? 'selected="selected"' : '';
                                        echo '<option value="' . $w_country . '" ' . $selected . '>' . $w_country . '</option>';
                                      }
                                    }
                                    ?>
                                  </select>
                                <?php }
                                else {
                                    if ( $key_select_or_input != '' && ($types[$key_select_or_input] == "type_number" || $types[$key_select_or_input] == "type_phone") ) {
                                      $onkeypress_function = "onkeypress='return check_isnum_space(event)'";
                                    }
                                    else {
                                      if ( $key_select_or_input != '' && ($types[$key_select_or_input] == "type_paypal_price" || $types[$key_select_or_input] == "type_paypal_price_new") ) {
                                        $onkeypress_function = "onkeypress='return check_isnum_point(event)'";
                                      }
                                      else {
                                        $onkeypress_function = "";
                                      }
                                    }
                                    ?>
                                    <input <?php echo $showHide; ?> id="field_value<?php echo $k; ?>_<?php echo $key; ?>" type="text" value="<?php echo $param_values[2]; ?>" <?php echo $onkeypress_function; ?> class="fm_condition_field_input_value">
                                    <?php
                                    if($types[$key_select_or_input] == 'type_time') {
                                      ?>
                                      <label>Please use HH:MM format for 24-hour time (e.g. 22:15), and HH:MM:AM or HH:MM:PM for 12-hour time (e.g. 05:20:AM / 07:30:PM).</label>
                                      <?php
                                    }
                                    ?>
                                  <?php } ?>
                                  <span class="dashicons dashicons-trash" id="delete_condition<?php echo $k; ?>_<?php echo $key; ?>" onclick="delete_field_condition('<?php echo $k; ?>_<?php echo $key; ?>')"></span>
                                </div>
                                <?php
                                }
                              }
                            }
                          }
                          ?>
                        </div>
                        <?php
                      }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" id="condition" name="condition" value="<?php echo $row->condition; ?>" />
      </div>

      <div id="mapping_fieldset" class="adminform fm_fieldset_deactive">
        <?php
        if ( WDFMInstance(self::PLUGIN)->is_demo ) {
          echo WDW_FM_Library(self::PLUGIN)->message_id(0, 'This feature is disabled in demo.', 'error');
        }
        else {
        ?>
        <div class="wd-table">
          <div class="wd-table-col-100">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <div class="wd-group">
                  <p class="description"><?php _e('WordPress Form Maker stores the submitted information into [prefix]_formmaker_submits MySQL database table of your website by default. With MySQL Mapping functionality of Form Maker you can insert this data into any local or remote table of your choice, or perform other queries based on submitted values. Press Add Query button to configure the first query for this form.', WDFMInstance(self::PLUGIN)->prefix); ?></p><br><br>
                  <div style="text-align: right; padding-bottom: 20px;">
                    <button id="add_query" class="wd-button button-primary" onclick="tb_show('', '<?php echo add_query_arg(array(
                                                                                                                             'action' => 'FormMakerSQLMapping' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                                                                                                             'id' => 0,
                                                                                                                             'form_id' => $row->id,
                                                                                                                             'width' => '1000',
                                                                                                                             'height' => '500',
                                                                                                                              'nonce' => $this->fm_nonce,
                                                                                                                             'TB_iframe' => '1',
                                                                                                                           ), admin_url('admin-ajax.php')); ?>'); return false;"><?php _e('Add Query', WDFMInstance(self::PLUGIN)->prefix); ?></button>
                  </div>
                  <?php if ( $queries ) { ?>
                    <table class="wp-list-table widefat fixed posts table_content">
                      <thead>
                      <tr>
                        <th style="width:86%;" class="table_large_col"><?php _e('Query', WDFMInstance(self::PLUGIN)->prefix); ?></th>
                        <th style="width:14%;" class="table_large_col"><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></th>
                      </tr>
                      </thead>
                      <?php
                      for ( $i = 0, $n = count($queries); $i < $n; $i++ ) {
                        $query = $queries[$i];
                        $link = add_query_arg(array(
                                                'action' => 'FormMakerSQLMapping' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                                'id' => $query->id,
                                                'form_id' => $row->id,
                                                'width' => '1000',
                                                'height' => '500',
                                                'nonce'=>$this->fm_nonce,
                                                'TB_iframe' => '1',
                                              ), admin_url('admin-ajax.php'));
                        $remove_query = add_query_arg(array(
                                                        'task' => 'remove_query',
                                                        'current_id' => $id,
                                                        'query_id' => $query->id,
                                                        'fieldset_id' => 'mapping'
                                                      ), $page_url)
                        ?>
                        <tr <?php if ( !$k ) {
                          echo "class=\"alternate\"";
                        } ?>>
                          <td align="center">
                            <a rel="{handler: 'iframe', size: {x: 530, y: 370}}" onclick="tb_show('', '<?php echo $link; ?>'); return false;" style="cursor:pointer;">
                              <?php echo $query->query; ?>
                            </a>
                          </td>
                          <td align="center" class="table_small_col check-column">
                            <a href="<?php echo $remove_query; ?>"><span class="dashicons dashicons-trash"></span></a></td>
                        </tr>
                        <?php
                      }
                      ?>
                    </table>
                    <?php
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
          <?php
        }
        ?>
      </div>
      <div id="privacy_fieldset" class="adminform fm_fieldset_deactive">
        <div class="wd-table">
          <div class="wd-table-col-100">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Enable GDPR compliance checkbox.', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="gdpr_checkbox" <?php echo $row->gdpr_checkbox == 1 ? 'checked="checked"' : '' ?> id="fm_go-gdpr_checkbox-1" class="wd-radio" value="1" onchange="fm_toggle_options('#div_gdpr_checkbox_text', true)">
                  <label class="wd-label-radio" for="fm_go-gdpr_checkbox-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="gdpr_checkbox" <?php echo $row->gdpr_checkbox == 0 ? 'checked="checked"' : '' ?> id="fm_go-gdpr_checkbox-0" class="wd-radio" value="0" onchange="fm_toggle_options('#div_gdpr_checkbox_text', false)">
                  <label class="wd-label-radio" for="fm_go-gdpr_checkbox-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <p class="description"><?php _e('Use this setting to enable GDPR compliance checkbox on form.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </div>
                <div id="div_gdpr_checkbox_text" class="wd-group">
                  <label class="wd-label" for="fm_go-gdpr_checkbox_text"><?php _e('GDPR compliance text.', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="text" id="fm_go-gdpr_checkbox_text" name="gdpr_checkbox_text" value="<?php echo $row->gdpr_checkbox_text; ?>">
                  <p class="description"><?php _e('This text will be used for GDPR compliance checkbox. Place {{privacy_policy}} placeholder to place "Privacy Policy page" link.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                  <p class="description"><?php echo sprintf(__('You can manage your privacy settings %s.', WDFMInstance(self::PLUGIN)->prefix), '<a href="' . admin_url('options-privacy.php') . '" target="_blank">' . __('here', WDFMInstance(self::PLUGIN)->prefix)) . '</a>'; ?></p>
                </div>
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Save User IP Address to Database', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="save_ip" <?php echo $row->save_ip == 1 ? 'checked="checked"' : '' ?> id="fm_go-save_ip-1" class="wd-radio" value="1">
                  <label class="wd-label-radio" for="fm_go-save_ip-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="save_ip" <?php echo $row->save_ip == 0 ? 'checked="checked"' : '' ?> id="fm_go-save_ip-0" class="wd-radio" value="0">
                  <label class="wd-label-radio" for="fm_go-save_ip-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <p class="description"><?php _e('Use this setting to disable storing submitter IP address to the database of your website.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </div>
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Save User Data to Database', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="save_user_id" <?php echo $row->save_user_id == 1 ? 'checked="checked"' : '' ?> id="fm_go-save_user_id-1" class="wd-radio" value="1">
                  <label class="wd-label-radio" for="fm_go-save_user_id-1"><?php _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <input type="radio" name="save_user_id" <?php echo $row->save_user_id == 0 ? 'checked="checked"' : '' ?> id="fm_go-save_user_id-0" class="wd-radio" value="0">
                  <label class="wd-label-radio" for="fm_go-save_user_id-0"><?php _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <p class="description"><?php _e('Disable this option to stop saving logged in username and email address to the database of your website.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
			<?php
				if ( !empty ($addons['html']) ){
					foreach($addons['html'] as  $addon => $html) {
						echo $html;
					}
				}
			?>
		</div>
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="fieldset_id" id="fieldset_id" value="<?php echo esc_attr($fieldset_id); ?>" />
		<script>
			default_theme  = '<?php echo $default_theme; ?>';
			payment_method = '<?php echo $payment_method; ?>';
			theme_edit_url = '<?php echo add_query_arg( array('page' => 'themes' . WDFMInstance(self::PLUGIN)->menu_postfix, 'task' =>'edit'), $page_url); ?>';
			
			jQuery(function () {
				set_theme();
			});
		</script>
  </div>
		<?php		
	}

  /**
   * Convert Select field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
  */
  public function get_label_values_from_db_own_select( $field = '' ) {
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
    $temp = $field;
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
    if ( strpos($temp, 'w_use_for_submission') > -1 ) {
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
        'w_use_for_submission',
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

    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_value'] = explode('***', $param['w_choices_value']);
    $param['w_choices_params'] = explode('***', $param['w_choices_params']);

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                       '[',
                                                                                                       ']'
                                                                                                     ), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels( $db_info, $label_column, $table, $where, $order_by );
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;
        }
        $value_table_and_column = explode(':', str_replace(array(
                                                             '[',
                                                             ']'
                                                           ), '', $param['w_choices_value'][$key]));
        $value_column = $param['w_choices_disabled'][$key] == "true" ? '' : $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }

        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);

        $return_data = str_replace(array($param['w_choices'][$key], $param['w_choices_value'][$key]), array($choices_labels, $choices_values), $return_data);

      }
    }
    return $return_data;
  }


  /**
   * Convert Radio field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
  */
  public function get_label_values_from_db_radio( $field = '' ) {
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
    $temp = $field;
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
    if ( strpos($temp, 'w_use_for_submission') > -1 ) {
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
        'w_use_for_submission',
        'w_choices_value',
        'w_choices_params',
        'w_class',
      );
    }

    foreach ( $params_names as $params_name ) {
      $temp = explode('*:*' . $params_name . '*:*', $temp);
      if ( $params_name == 'w_choices' ) {
        $param[$params_name] = strip_tags($temp[0], "<b><strong><span><a>");
      } else {
        $param[$params_name] = esc_html($temp[0]);
      }

      $temp = $temp[1];
    }

    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
    if ( isset($param['w_choices_value']) ) {
      $param['w_choices_value'] = explode('***', $param['w_choices_value']);
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                       '[',
                                                                                                       ']',
                                                                                                     ), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;
        }
        $value_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $param['w_choices_value'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }
        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);
        $return_data = str_replace(array(
                                     $param['w_choices'][$key],
                                     $param['w_choices_value'][$key]
                                   ), array( $choices_labels, $choices_values ), $return_data);
      }
    }
    return $return_data;
  }


  /**
   * Convert Checkbox field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
  */
  public function get_label_values_from_db_checkbox( $field = '' ) {
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
    $temp = $field;
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
    if ( strpos($temp, 'w_use_for_submission') > -1 ) {
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
        'w_use_for_submission',
        'w_choices_value',
        'w_choices_params',
        'w_class',
      );
    }

    foreach ( $params_names as $params_name ) {
      $temp = explode('*:*' . $params_name . '*:*', $temp);
      if ( $params_name == 'w_choices' ) {
        $param[$params_name] = strip_tags($temp[0], "<b><strong><span><a>");
      } else {
        $param[$params_name] = esc_html($temp[0]);
      }
      $temp = $temp[1];
    }

    $param['w_choices'] = explode('***', $param['w_choices']);
    if ( isset($param['w_choices_value']) ) {
      $param['w_choices_value'] = explode('***', $param['w_choices_value']);
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                       '[',
                                                                                                       ']',
                                                                                                     ), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;

        }
        $value_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $param['w_choices_value'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }
        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);

        $return_data = str_replace(array($param['w_choices'][$key], $param['w_choices_value'][$key]), array($choices_labels, $choices_values), $return_data);
      }
    }
    return $return_data;
  }

  /**
   * Convert Payment Select field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
   */
  public function get_label_values_from_db_paypal_select( $field = '' ) {
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
    $temp = $field;
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
    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_price'] = explode('***', $param['w_choices_price']);
    $param['w_choices_disabled'] = explode('***', $param['w_choices_disabled']);
    if ( isset($param['w_choices_params']) ) {
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                       '[',
                                                                                                       ']'
                                                                                                     ), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;
        }
        $value_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $param['w_choices_price'][$key]));
        $value_column = $param['w_choices_disabled'][$key] == "true" ? '' : $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }
        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);

        $return_data = str_replace(array($param['w_choices'][$key], $param['w_choices_price'][$key]), array($choices_labels, $choices_values), $return_data);
      }
    }

    return $return_data;
  }

  /**
   * Convert Payment Checkbox field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
   */
  public function get_label_values_from_db_paypal_checkbox( $field = '' ) {
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
    $temp = $field;
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

    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_price'] = explode('***', $param['w_choices_price']);
    if ( isset($param['w_choices_params']) ) {
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array('[',']'), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array('[',']'), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;
        }
        $value_table_and_column = explode(':', str_replace(array(
                                                             '[',
                                                             ']',
                                                           ), '', $param['w_choices_price'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }
        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);

        $return_data = str_replace(array($param['w_choices'][$key], $param['w_choices_price'][$key]), array($choices_labels, $choices_values), $return_data);
      }
    }
    return $return_data;
  }

  /**
   * Convert Payment Radio field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
   */
  public function get_label_values_from_db_paypal_radio( $field = '' ) {
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
    $temp = $field;
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

    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_price'] = explode('***', $param['w_choices_price']);
    if ( isset($param['w_choices_params']) ) {
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array('[',']'), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;
        }
        $value_table_and_column = explode(':', str_replace(array(
                                                             '[',
                                                             ']',
                                                           ), '', $param['w_choices_price'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }
        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);
        $return_data = str_replace(array(
                                     $param['w_choices'][$key],
                                     $param['w_choices_price'][$key]
                                   ), array( $choices_labels, $choices_values ), $return_data);      }
    }
    return $return_data;
  }

  /**
   * Convert Payment Shipping Radio field DB placeholder to normal label/value
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   *
   * @return string
   */
  public function get_label_values_from_db_paypal_shipping( $field = '' ) {
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
    $temp = $field;
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

    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_price'] = explode('***', $param['w_choices_price']);
    if ( isset($param['w_choices_params']) ) {
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }

    $return_data = $field;
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        $where = (str_replace(array('[',']'), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array('[',']'), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
          $temp_choices_labels = array();
          foreach ( $choices_labels as $choices_label ) {
            $temp_choices_labels[] = $choices_label[0];
          }
          $choices_labels = $temp_choices_labels;
        }
        $value_table_and_column = explode(':', str_replace(array('[', ']'), '', $param['w_choices_price'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
          $temp_choices_values = array();
          foreach ( $choices_values as $choices_value ) {
            $temp_choices_values[] = $choices_value[0];
          }
          $choices_values = $temp_choices_values;
        }
        $choices_labels = implode('***', $choices_labels);
        $choices_values = implode('***', $choices_values);
        $return_data = str_replace(array(
                                     $param['w_choices'][$key],
                                     $param['w_choices_price'][$key]
                                   ), array( $choices_labels, $choices_values ), $return_data);
      }
    }
    return $return_data;
  }


  /**
   * Get from DB and convert to label/value if used option "From Database" for select, radio, checkbox
   * The main target of converting to make it usable in conditional fields
   *
   * @param string $field
   * @param string $type
   *
   * @return string
  */
  public function get_label_values_from_db( $field = '', $type = '' ) {
    switch ( $type ) {
      case 'type_own_select':
        return $this->get_label_values_from_db_own_select( $field );
        break;
      case 'type_radio':
        return $this->get_label_values_from_db_radio( $field );
        break;
      case 'type_checkbox':
        return $this->get_label_values_from_db_checkbox( $field );
        break;
      case 'type_paypal_select':
        return $this->get_label_values_from_db_paypal_select( $field );
        break;
      case 'type_paypal_checkbox':
        return $this->get_label_values_from_db_paypal_checkbox( $field );
        break;
      case 'type_paypal_radio':
        return $this->get_label_values_from_db_paypal_radio( $field );
        break;
      case 'type_paypal_shipping':
        return $this->get_label_values_from_db_paypal_shipping( $field );
        break;
    }
    return $field;
  }

  /**
   * Placeholders popup.
   *
   * @param array $placeholders
   * @return string
   */
  public function placeholders_popup( $placeholders = array() ) {
    ob_start();
    ?>
    <div id="placeholders_overlay"></div>
    <div class="placeholder-popup js">
      <div class="placeholder-body meta-box-sortables">
        <div class="placeholder-body-col placeholders">
          <div class="placeholders_cont">
            <p class="description"><?php _e('Select a field, the value of which will be used as the placeholder.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
            <span class="placeholders-filter-cont">
              <input class="placeholders-filter" value="" placeholder="<?php _e('Filter', WDFMInstance(self::PLUGIN)->prefix); ?>" tabindex="-1" type="search" />
            </span>
            <div class="postbox filtered-placeholders hide">
              <h2 class="hndle readonly">
                <span><?php _e('Filtered placeholders', WDFMInstance(self::PLUGIN)->prefix); ?></span>
              </h2>
              <div class="inside"></div>
            </div>
            <?php
            foreach ($placeholders as $section => $field) {
              ?>
              <div class="postbox<?php echo $section != __('Misc', WDFMInstance(self::PLUGIN)->prefix) ? " closed" : ""; ?>">
                <button class="button-link handlediv" type="button" aria-expanded="true">
                  <span class="screen-reader-text"><?php echo __('Toggle panel:', WDFMInstance(self::PLUGIN)->prefix) , $section; ?></span>
                  <span class="toggle-indicator" aria-hidden="false"></span>
                </button>
                <h2 class="hndle">
                  <span><?php echo $section; ?></span>
                </h2>
                <div class="inside">
                  <?php
                  foreach ($field as $button) {
                    ?>
                    <button id="fm-placeholder-<?php echo $button['value']; ?>" class="wd-button button-secondary fm-placeholder-item" onclick="wd_insert_placeholder(jQuery('.placeholders-active .dashicons-list-view').data('id'), '<?php echo $button['value']; ?>'); return false;" data-type="">
                      <?php echo $button['title']; ?>
                    </button>
                    <?php
                  }
                  ?>
                </div>
              </div>
              <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
	
	/**
	* Form layout.
	*
	* @param array $params
	*
	*/
	public function form_layout( $params = array() ) {
		echo $this->body_form_layout( $params );
		die();
	}
	
	/**
	* Generate page body form layout.
	*
	* @param array $params
	* @return string Body html.
	*/
	private function body_form_layout( $params = array() ) {
		$id = $params['id'];
		$row = $params['row'];
		$ids = $params['ids'];
		$types = $params['types'];
		$labels = $params['labels'];		
		?>
		<div class="wd-table">
			<div class="wd-table-col-100">
				<div class="wd-box-section">						  
					<div class="wd-box-content">
						<p><?php _e('To customize the layout of the form fields uncheck the Auto-Generate Layout box and edit the HTML.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
						<p><?php _e('You can change positioning, add in-line styles and etc. Click on the provided buttons to add the corresponding field.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
						<p><?php _e('This will add the following line:', WDFMInstance(self::PLUGIN)->prefix); ?>
						<b><span class="cm-tag">&lt;div</span> <span class="cm-attribute">wdid</span>=<span class="cm-string">"example_id"</span> <span class="cm-attribute">class</span>=<span class="cm-string">"wdform_row"</span><span class="cm-tag">&gt;</span>%example_id - Example%<span class="cm-tag">&lt;/div&gt;</span></b>	, where <b><span class="cm-tag">&lt;div&gt;</span></b> <?php _e('is used to set a row.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
						<p>
							<b style="color:red"><?php _e('Notice', WDFMInstance(self::PLUGIN)->prefix); ?></b><br>
							<?php _e('Make sure not to publish the same field twice. This will cause malfunctioning of the form.', WDFMInstance(self::PLUGIN)->prefix); ?>
						</p>
						<div class="wd-group">
							<label class="wd-label autogen_layout_label" for="autogen_layout"><?php _e('Auto Generate Layout?', WDFMInstance(self::PLUGIN)->prefix); ?></label>
							<input type="checkbox" value="1" name="autogen_layout" id="autogen_layout" <?php echo (($row->autogen_layout) ? 'checked="checked"' : ''); ?> />
						</div>
						<div class="wd-group">
							<div style="margin-bottom: 10px">
							<?php
								foreach($ids as $key => $id) {
									if ($types[$key] != "type_section_break") {
										?>
										<button type="button" onClick="insertAtCursor_form('<?php echo $ids[$key]; ?>','<?php echo $labels[$key]; ?>')" class="button" title="<?php echo $labels[$key]; ?>"><?php echo $labels[$key]; ?></button>
										<?php
									}
								}
							?>
							</div>
							<span class="button button-hero fm_auto_format_button" onclick="autoFormat()"><strong><?php _e('Apply Source Formatting', WDFMInstance(self::PLUGIN)->prefix); ?></strong> <em>(<?php _e('ctrl-enter', WDFMInstance(self::PLUGIN)->prefix); ?>)</em></span>
							<textarea id="source" name="source" style="display: none;"></textarea>
							<input type="hidden" name="custom_front" id="custom_front" value="" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			var form_front = '<?php echo addslashes($row->form_front);?>';
			var custom_front = '<?php echo addslashes($row->custom_front);?>';			
		</script>
		<?php 
	}

  /**
   * Display options.
   *
   * @param array $params
   */
	public function display_options( $params = array() ) {
		echo $this->body_display_options($params);
		die();
	}

  /**
   * Body display options.
   *
   * @param array $params
   */
	public function body_display_options( $params = array() ) {
		$row = $params['row'];
		$page_title = $params['page_title'];
		$posts_and_pages = $params['posts_and_pages'];
		$display_on_list = $params['display_on_list'];
		$animation_effects = $params['animation_effects'];
		$back_url  	= $params['back_url'];
    $themes = $params['themes'];
    $default_theme = $params['default_theme'];
		?>

		<div class="fm-clear"></div>
		<div class="display-options-container">
			<div id="type_settings_fieldset" class="adminform">
        <div id="themes_container" style="display: none">
          <div id="themes_icon" class="themes_container_col"><img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/css/images/themes.svg"></div>
          <div id="themes_descr" class="themes_container_col">
            <h3><?php  _e('Themes', WDFMInstance(self::PLUGIN)->prefix); ?></h3>
            <div id="old_theme_notice" class="error inline" style="display: none;"><p><?php _e('The theme you have selected is outdated. Please choose one from New Themes section.', WDFMInstance(self::PLUGIN)->prefix); ?></p></div>
            <p class="description"><?php _e('The appearance of your forms is controlled by the theme you select with this option. Press Edit button to open and modify your form theme.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
          </div>
          <div id="themes_select" class="">
            <div id="themes_select_row">
            <select id="theme" name="theme" onChange="set_theme()">
              <optgroup label="<?php _e('New Themes', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <option value="0" <?php echo $row->theme && $row->theme == 0 ? 'selected' : '' ?> data-version="2"><?php _e('Inherit From Website Theme', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                <?php
                $optiongroup = TRUE;
                foreach ($themes as $theme) {
                if ($optiongroup && $theme->version == 1) {
                $optiongroup = FALSE;
                ?>
              </optgroup>
              <optgroup label="<?php _e('Outdated Themes', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <?php } ?>
                <option value="<?php echo $theme->id; ?>" <?php echo(($theme->id == $row->theme) ? 'selected' : ''); ?> data-version="<?php echo $theme->version; ?>"><?php echo $theme->title; ?></option>
                <?php } ?>
              </optgroup>
            </select>
            <a id="edit_css" class="button options-edit-button" onclick="window.open('<?php echo add_query_arg(array(
                                                                                                          'current_id' => ($row->theme && $row->theme != '0' ? $row->theme : $default_theme),
                                                                                                          WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce)
                                                                                                        ), admin_url('admin.php?page=themes' . WDFMInstance(self::PLUGIN)->menu_postfix . '&task=edit')); ?>'); return false;"><?php _e('Edit Theme', WDFMInstance(self::PLUGIN)->prefix); ?></a>
            </div>
          </div>
        </div>


        <div class="wd-table">
					<div class="wd-table-col-100 wd-table-col-left">
						<div class="wd-box-section">
							<div class="wd-box-content display-options-header">
								<span>
									<div class="fm-row fm-form-types">
										<label  class="wd-label"><?php  _e('Form Type', WDFMInstance(self::PLUGIN)->prefix); ?></label>
										<label class="display_option_item">
											<input type="radio" name="form_type" value="embedded" onclick="change_form_type('embedded'); change_hide_show('fm-embedded');"
												<?php echo $row->type == 'embedded' ? 'checked="checked"' : '' ?>>
											<span class="fm-embedded <?php echo $row->type == 'embedded' ? ' active' : '' ?>"></span>
											<p><?php  _e('Embedded', WDFMInstance(self::PLUGIN)->prefix); ?></p>
										</label>
										<label class="display_option_item">
											<input type="radio" name="form_type" value="popover" onclick="change_form_type('popover'); change_hide_show('fm-popover');"
												<?php echo $row->type == 'popover' ? 'checked="checked"' : '' ?>>
											<span class="fm-popover <?php echo $row->type == 'popover' ? ' active' : '' ?>"></span>
											<p><?php  _e('Popup', WDFMInstance(self::PLUGIN)->prefix); ?></p>
										</label>
										<label class="display_option_item">
											<input type="radio" name="form_type" value="topbar" onclick="change_form_type('topbar'); change_hide_show('fm-topbar');"
												<?php echo $row->type == 'topbar' ? 'checked="checked"' : '' ?>>
											<span class="fm-topbar <?php echo $row->type == 'topbar' ? ' active' : '' ?>"></span>
											<p><?php  _e('Topbar', WDFMInstance(self::PLUGIN)->prefix); ?></p>
										</label>
										<label class="display_option_item">
											<input type="radio" name="form_type" value="scrollbox" onclick="change_form_type('scrollbox'); change_hide_show('fm-scrollbox');"<?php echo $row->type == 'scrollbox' ? 'checked="checked"' : '' ?>>
											<span class="fm-scrollbox <?php echo $row->type == 'scrollbox' ? ' active' : '' ?>"></span>
											<p><?php  _e('Scrollbox', WDFMInstance(self::PLUGIN)->prefix); ?></p>
										</label>
									</div>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="wd-table">
					<div class="wd-table-col-100 wd-table-col-left">
						<div class="wd-box-section">
							<div class="wd-box-content display-options-content">
								<span class="wd-group fm-embedded <?php echo $row->type == 'embedded' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Form Placement', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<div id="fm-embedded-element">
									<p><?php  _e('Use', WDFMInstance(self::PLUGIN)->prefix); ?></p>
									<input type="text" value='<?php echo (WDFMInstance(self::PLUGIN)->is_free == 2 ? '[wd_contact_form id="' . $row->form_id . '"]' : '[Form id="' . $row->form_id . '"]'); ?>' onclick="fm_select_value(this)"  readonly="readonly"/>
									<p><?php  _e('shortcode to display the form.', WDFMInstance(self::PLUGIN)->prefix); ?></p>
									</div>
								</span>
								<span class="wd-group fm-popover <?php echo $row->type == 'popover' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Animation Effect', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<select id="popover_animate_effect" name="popover_animate_effect">
										<?php
										foreach($animation_effects as $anim_key => $animation_effect){
											$selected = $row->popover_animate_effect == $anim_key ? 'selected="selected"' : '';
											echo '<option value="'.$anim_key.'" '.$selected.'>'.$animation_effect.'</option>';
										}
										?>
									</select>
								</span>
								<span class="wd-group fm-popover <?php echo $row->type != 'popover' ? 'fm-hide' : 'fm-show'; ?>">
									<label class="wd-label"><?php  _e('Loading Delay', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="number" name="popover_loading_delay" value="<?php echo $row->popover_loading_delay; ?>" /> seconds
									<div>
										<?php  _e('Define the amount of time before the form popup appears after the page loads.', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php  _e('Set 0 for no delay.', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php  _e('Set -1 for show only after show_popup_form_' . $row->form_id . '() function\'s call', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-popover <?php echo $row->type == 'popover' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Frequency', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="number" name="popover_frequency" value="<?php echo $row->popover_frequency; ?>" /> days
									<div>
										<?php  _e('Display the popup to the same visitor (who has closed the popup/submitted the form) after this period.', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php  _e('Set the value to 0 to always show.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-topbar <?php echo $row->type == 'topbar' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Position', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="topbar_position" <?php echo $row->topbar_position == 1 ? 'checked="checked"' : '' ?> id="fm_do-topbarpos-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-topbarpos-1"><?php  _e('Top', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="topbar_position" <?php echo $row->topbar_position == 0 ? 'checked="checked"' : '' ?> id="fm_do-topbarpos-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-topbarpos-0"><?php  _e('Bottom', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
								<span class="wd-group fm-topbar topbar_remain_top <?php echo $row->type != 'topbar' ? 'fm-hide' : ($row->topbar_position == 1 ? 'fm-show' : 'fm-hide') ?>">
									<label class="wd-label"><?php  _e('Remain at top when scrolling', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="topbar_remain_top" <?php echo $row->topbar_remain_top == 1 ? 'checked="checked"' : '' ?> id="fm_do-remaintop-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-remaintop-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="topbar_remain_top" <?php echo $row->topbar_remain_top == 0 ? 'checked="checked"' : '' ?> id="fm_do-remaintop-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-remaintop-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
								<span class="wd-group fm-topbar <?php echo $row->type == 'topbar' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Allow Closing the bar', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="topbar_closing" <?php echo $row->topbar_closing == 1 ? 'checked="checked"' : '' ?> id="fm_do-topbarclosing-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-topbarclosing-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="topbar_closing" <?php echo $row->topbar_closing == 0 ? 'checked="checked"' : '' ?> id="fm_do-topbarclosing-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-topbarclosing-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
								<span class="wd-group fm-topbar topbar_hide_duration <?php echo $row->type != 'topbar' ? 'fm-hide' : 'fm-show' ?>">
									<label class="wd-label"><?php  _e('Frequency', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="number" name="topbar_hide_duration" value="<?php echo $row->topbar_hide_duration; ?>"/>days
									<div>
										<?php  _e('Display the topbar to the same visitor (who has closed the popup/submitted the form) after this period.', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php  _e('Set the value to 0 to always show.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type == 'scrollbox' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Position', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_position" <?php echo $row->scrollbox_position == 0 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxposition-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-scrollboxposition-0"><?php  _e('Left', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_position" <?php echo $row->scrollbox_position == 1 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxposition-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-scrollboxposition-1"><?php  _e('Right', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type != 'scrollbox' ? 'fm-hide' : 'fm-show'; ?>">
									<label class="wd-label"><?php  _e('Loading Delay', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="number" name="scrollbox_loading_delay" value="<?php echo $row->scrollbox_loading_delay; ?>" /> seconds
									<div>
										<?php  _e('Define the amount of time before the form scrollbox appears after the page loads. Set 0 for no delay.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type == 'scrollbox' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Frequency', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="number" name="scrollbox_hide_duration" value="<?php echo $row->scrollbox_hide_duration; ?>"/>days
									<div>
										<?php  _e('Display the scrollbox to the same visitor (who has closed the popup/submitted the form) after this period.', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php  _e('Set the value to 0 to always show.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-popover fm-topbar fm-scrollbox <?php echo $row->type != 'embedded' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Always show for administrator', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="show_for_admin" <?php echo $row->show_for_admin == 1 ? 'checked="checked"' : '' ?> id="fm_do-showforadmin-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-showforadmin-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="show_for_admin" <?php echo $row->show_for_admin == 0 ? 'checked="checked"' : '' ?> id="fm_do-showforadmin-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-showforadmin-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<div>
										<?php  _e('If this option is enabled, website admins will always see the form.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type == 'scrollbox' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Trigger Point', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="number" name="scrollbox_trigger_point" value="<?php echo $row->scrollbox_trigger_point; ?>"/>%
									<div>
										<?php  _e('Set the percentage of the page height, where the scrollbox form will appear after scrolling down.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type == 'scrollbox' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Allow Closing the bar', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_closing" <?php echo $row->scrollbox_closing == 1 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxclosing-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-scrollboxclosing-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_closing" <?php echo $row->scrollbox_closing == 0 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxclosing-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-scrollboxclosing-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type == 'scrollbox' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Allow Minimize', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_minimize" <?php echo $row->scrollbox_minimize == 1 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxminimize-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-scrollboxminimize-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_minimize" <?php echo $row->scrollbox_minimize == 0 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxminimize-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-scrollboxminimize-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
								<span class="wd-group fm-scrollbox minimize_text <?php echo $row->type == 'scrollbox' && $row->scrollbox_minimize == 1 ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Minimize Text', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="text" name="scrollbox_minimize_text" value="<?php echo $row->scrollbox_minimize_text; ?>"/>
								</span>
								<span class="wd-group fm-scrollbox <?php echo $row->type == 'scrollbox' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Auto Hide', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_auto_hide" <?php echo $row->scrollbox_auto_hide == 1 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxautohide-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-scrollboxautohide-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="scrollbox_auto_hide" <?php echo $row->scrollbox_auto_hide == 0 ? 'checked="checked"' : '' ?> id="fm_do-scrollboxautohide-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-scrollboxautohide-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<div>
										<?php  _e('Hide the scrollbox form again when visitor scrolls back up.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
								</span>
								<span class="wd-group fm-popover fm-topbar fm-scrollbox <?php echo $row->type != 'embedded' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Display on', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<ul class="pp_display pp_display_on">
									<?php
										$def_post_types = array('post' => 'Post', 'page' => 'Page');
										$selected_types = explode(',', $row->display_on);
										$show_cats = in_array('post', $selected_types);
										$m = 0;
										foreach ( $display_on_list as $post_key => $post_type ) {
											$checked = in_array('everything', $selected_types) || in_array($post_key, $selected_types) ? 'checked="checked"' : '';
											$postclass = $post_key != 'page' && in_array($post_key, array_keys($def_post_types)) ? 'class="catpost"' : '';
											echo '<li><input id="pt' . $m . '" type="checkbox" name="display_on[]" value="' . $post_key . '" ' . $checked . ' ' . $postclass . '/><label for="pt'.$m.'">'.$post_type.'</label></li>';
											$m++;
										}
										?>
									</ul>
								</span>
								<span class="wd-group fm-popover fm-topbar fm-scrollbox fm-cat-show <?php echo $row->type != 'embedded' && $show_cats ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e("Display on these categories' posts", WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<ul class="pp_display pp_display_on_categories"><?php
										$categories = $params['categories'];
										$selected_categories = $params['selected_categories'];
										$current_categories_array = $params['current_categories_array'];
										$auto_check = ( in_array('select_all_categories', $selected_categories) ) ? 'checked="checked"' : '';
										$m = 0;
										echo '<li>
												<br/>
												<input id="cat'.$m.'" class="fm-display-all-categories" data-categories-count="' . count($categories) . '" type="checkbox" name="display_on_categories[]" value="select_all_categories" '. $auto_check .' />
												<label for="cat'.$m.'">' .  __('All categories', WDFMInstance(self::PLUGIN)->prefix) .' </label>
											</li>';
										foreach ( $categories as $cat_key => $category ) {
											$m++;
											$checked = ( $auto_check || in_array($cat_key, $selected_categories) ) ? 'checked="checked"' : '';
											echo '<li>
													<input id="cat'.$m.'" type="checkbox" name="display_on_categories[]" value="'.$cat_key.'" '.$checked.'/>
													<label for="cat'.$m.'">'.$category.'</label>
												</li>';
										}
										$current_categories = !$row->current_categories && !$row->display_on_categories ? implode(',', array_keys($categories)) : $row->current_categories;
										?>
									</ul>
									<input type="hidden" name="current_categories" value="<?php echo $current_categories; ?>"/>
								</span>
								<span class="wd-group fm-popover fm-topbar fm-scrollbox fm-posts-show <?php echo (in_array('everything', $selected_types) || in_array('post', $selected_types)) && $row->type != 'embedded' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Display on these posts', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<div class="fm-mini-heading">
										<?php _e('Click on input area to view the list of posts.', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php _e('If left empty the form will appear on all posts.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
									<ul class="fm-pp">
										<li class="pp_selected">
										<?php
										if ( $row->posts_include ) {
												$posts_include = explode(',', $row->posts_include);
												foreach($posts_include as $post_exclude){
													if(isset($posts_and_pages[$post_exclude])){
														$ptitle = $posts_and_pages[$post_exclude]['title'];
														$ptype = $posts_and_pages[$post_exclude]['post_type'];
														echo '<span data-post_id="'.$post_exclude.'">['. $ptype .'] - '. $ptitle .'<span class="pp_selected_remove">x</span></span>';
													}
												}
											} ?></li>
										<li>
											<input type="text" class="pp_search_posts" value="" data-post_type="only_posts" style="width: 100% !important;" />
											<input type="hidden" class="pp_exclude" name="posts_include" value="<?php echo $row->posts_include; ?>" />
											<span class="fm-loading"></span>
										</li>
										<li class="pp_live_search fm-hide">
											<ul class="pp_search_results"></ul>
										</li>
									</ul>
								</span>
								<span class="wd-group fm-popover fm-topbar fm-scrollbox fm-pages-show <?php echo (in_array('everything', $selected_types) || in_array('page', $selected_types)) && $row->type != 'embedded' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Display on these pages', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<div class="fm-mini-heading">
										<?php _e('Click on input area to view the list of pages. ', WDFMInstance(self::PLUGIN)->prefix); ?>
										<?php _e('If left empty the form will appear on all pages.', WDFMInstance(self::PLUGIN)->prefix); ?>
									</div>
									<ul class="fm-pp">
										<li class="pp_selected"><?php if($row->pages_include){
												$pages_include = explode(',', $row->pages_include);
												foreach($pages_include as $page_exclude){
													if(isset($posts_and_pages[$page_exclude])){
														$ptitle = $posts_and_pages[$page_exclude]['title'];
														$ptype = $posts_and_pages[$page_exclude]['post_type'];
														echo '<span data-post_id="'.$page_exclude.'">['.$ptype.'] - '.$ptitle.'<span class="pp_selected_remove">x</span></span>';
													}
												}
											} ?></li>
										<li>
											<input type="text" class="pp_search_posts" value="" data-post_type="only_pages" style="width: 100% !important;" />
											<input type="hidden" class="pp_exclude" name="pages_include" value="<?php echo $row->pages_include; ?>" />
											<span class="fm-loading"></span>
										</li>
										<li class="pp_live_search fm-hide">
											<ul class="pp_search_results"></ul>
										</li>
									</ul>
								</span>
								<span class="wd-group fm-popover fm-topbar fm-scrollbox <?php echo $row->type != 'embedded' ? 'fm-show' : 'fm-hide' ?>">
									<label class="wd-label"><?php  _e('Hide on Mobile', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="hide_mobile" <?php echo $row->hide_mobile == 1 ? 'checked="checked"' : '' ?> id="fm_do-hidemobile-1" class="wd-radio" value="1">
									<label class="wd-label-radio" for="fm_do-hidemobile-1"><?php  _e('Yes', WDFMInstance(self::PLUGIN)->prefix); ?></label>
									<input type="radio" name="hide_mobile" <?php echo $row->hide_mobile == 0 ? 'checked="checked"' : '' ?> id="fm_do-hidemobile-0" class="wd-radio" value="0">
									<label class="wd-label-radio" for="fm_do-hidemobile-0"><?php  _e('No', WDFMInstance(self::PLUGIN)->prefix); ?></label>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}