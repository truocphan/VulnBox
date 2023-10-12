<?php

/**
 * Class FMModelManage
 */
class FMModelManage_fm extends FMAdminModel {

  /**
   * Get forms.
   *
   * @param $params
   *
   * @return array|null|object
   */
  public function get_rows_data( $params = array() ) {
    $order = $params['order'];
    $orderby = $params['orderby'];
    $items_per_page = $params['items_per_page'];
    $search = WDW_FM_Library(self::PLUGIN)->get('s', '');
    $page = WDW_FM_Library(self::PLUGIN)->get('paged', 1, 'intval');
    $limit = $page ? ($page - 1) * $items_per_page : 0;

    global $wpdb;
    $query = "SELECT t1.* FROM " . $wpdb->prefix . "formmaker as t1 ";
    $query .= (!WDFMInstance(self::PLUGIN)->is_free ? '' : 'WHERE t1.id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option('contact_form_forms', '') != '' ? get_option('contact_form_forms') : 0) . ')');
    if ( $search ) {
      $query .= $wpdb->prepare((!WDFMInstance(self::PLUGIN)->is_free ? 'WHERE' : ' AND') . ' `t1`.`title` LIKE %s', '%' . $search . '%');
    }
    $query .= ' ORDER BY t1.`' . $orderby . '` ' . $order;
    $query .= " LIMIT " . $limit . "," . $items_per_page;
    $rows = $wpdb->get_results($query);
    if ( !empty($rows) ) {
      foreach ( $rows as $key => $row ) {
        $form_options = json_decode( $row->form_options, 1 );
        $save_db = isset($form_options['savedb']) ? $form_options['savedb'] : 1;
        if ( !isset($row->header_hide) ) {
          $row->header_hide = 1;
        }
        $submission_count_query = "SELECT count(DISTINCT group_id) FROM " . $wpdb->prefix . "formmaker_submits WHERE form_id=" . (int) $row->id;
        $submission_count = $wpdb->get_var($submission_count_query);
        if ( $save_db == 2 ) {
          $in_progress_count_query = $submission_count_query . " AND (element_value = 'In progress' or element_value = 'Pending')";
          $in_progress_count = $wpdb->get_var($in_progress_count_query);
          $submission_count = intval($submission_count) - intval($in_progress_count);
        }
        $row->submission_count = $submission_count;

        $rows[$key] = WDW_FM_Library::convert_json_options_to_old( $row, 'form_options');
      }
    }

    return $rows;
  }

  /**
   * Get row data.
   *
   * @param int $id
   * @return stdClass
   */
  public function get_row_data( $id = 0 ) {
    global $wpdb;
    if ( $id != 0 ) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker WHERE id=%d', $id));
      if ( $row ) {
        $row = WDW_FM_Library::convert_json_options_to_old( $row, 'form_options' );
        $row->gdpr_checkbox = 0;
        $row->gdpr_checkbox_text = __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);
        $row->save_ip = 1;
        $row->save_user_id = 1;
        if ( isset($row->privacy) ) {
          if ( $row->privacy ) {
            $privacy = json_decode($row->privacy);
            $row->gdpr_checkbox = isset($privacy->gdpr_checkbox) ? $privacy->gdpr_checkbox : 0;
            $row->gdpr_checkbox_text = isset($privacy->gdpr_checkbox_text) ? $privacy->gdpr_checkbox_text : __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);
            $row->save_ip = isset($privacy->save_ip) ? $privacy->save_ip : 1;
            $row->save_user_id = isset($privacy->save_user_id) ? $privacy->save_user_id : 1;
          }
        }
      }
    }
    else {
      // Add "Submit" button to new forms.
      $row = new stdClass();
      $row->id = 0;
      $row->title = '';
      $row->mail = '';
      $row->form = '';
      $row->form_front = '<div class="wdform-page-and-images fm-form-builder"><div id="form_id_tempform_view1" class="wdform_page" page_title="Untitled page" next_title="Next" next_type="text" next_class="wdform-page-button" next_checkable="true" previous_title="Previous" previous_type="text" previous_class="wdform-page-button" previous_checkable="false"><div class="wdform_section"><div class="wdform_column"><div wdid="1" class="wdform_row ui-sortable-handle">%1 - type_submit_reset_1%</div></div></div><div valign="top" class="wdform_footer wd-width-100"><div class="wd-width-100"><div class="wd-width-100 wd-table" style="padding-top:10px;"><div class="wd-table-group"><div id="form_id_temppage_nav1" class="wd-table-row"></div></div></div></div></div></div></div>';
      $row->theme = 0;
      $row->javascript = '';
      $row->submit_text = '';
      $row->url = '';
      $row->submit_text_type = 0;
      $row->script1 = '';
      $row->script2 = '';
      $row->script_user1 = '';
      $row->script_user2 = '';
      $row->counter = 2;
      $row->label_order = '1#**id**#type_submit_reset_1#**label**#type_submit_reset#****##**id**##**label**##****#';
      $row->article_id = '';
      $row->pagination = '';
      $row->show_title = '';
      $row->show_numbers = '';
      $row->public_key = '';
      $row->private_key = '';
      $row->recaptcha_theme = '';
      $row->from_name = '';
      $row->from_mail = '';
      $row->label_order_current = '1#**id**#type_submit_reset_1#**label**#type_submit_reset#****#';
      $row->script_mail_user = '';
      $row->script_mail = '';
      $row->tax = 0;
      $row->payment_currency = '$';
      $row->paypal_email = '';
      $row->checkout_mode = 'testmode';
      $row->paypal_mode = 0;
      $row->published = 1;
      $row->form_fields = '1*:*id*:*type_submit_reset*:*type*:*type_submit_reset_1*:*w_field_label*:*Submit*:*w_submit_title*:*Reset*:*w_reset_title*:**:*w_class*:*false*:*w_act*:**:*new_field*:*';
      $row->savedb = 1;
      $row->sendemail = 1;
      $row->requiredmark = '*';
      $row->submissions_limit = 0;
      $row->submissions_limit_text = __('The limit of submissions for this form has been reached.', WDFMInstance(self::PLUGIN)->prefix);
      $row->reply_to = 0;
      $row->send_to = 0;
      $row->autogen_layout = 1;
      $row->custom_front = '';
      $row->mail_from_user = '';
      $row->mail_from_name_user = '';
      $row->reply_to_user = '';
      $row->save_uploads = 1;
      $row->header_title = '';
      $row->header_description = '';
      $row->header_image_url = '';
      $row->header_image_animation = '';
      $row->header_hide_image = '';
      $row->header_hide = 1;
      $row->gdpr_checkbox = 0;
      $row->gdpr_checkbox_text = __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);
      $row->save_ip = 1;
      $row->save_user_id = 1;
    }

    return $row;
  }

  /**
   * get row data new.
   *
   * @param int $id
   * @return stdClass
   */
  public function get_row_data_new( $id = 0 ) {
    $fm_nonce = wp_create_nonce('fm_ajax_nonce');
    global $wpdb;
    if ( $id != 0 ) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_backup WHERE backup_id=%d', $id));
      if ( $row ) {
        $row = WDW_FM_Library::convert_json_options_to_old( $row, 'form_options' );
        $row->gdpr_checkbox = 0;
        $row->gdpr_checkbox_text = __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);
        $row->save_ip = 1;
        $row->save_user_id = 1;
        if ( isset($row->privacy) ) {
          if ( $row->privacy ) {
            $privacy = json_decode($row->privacy);
            $row->gdpr_checkbox = isset($privacy->gdpr_checkbox) ? $privacy->gdpr_checkbox : 0;
            $row->gdpr_checkbox_text = isset($privacy->gdpr_checkbox_text) ? $privacy->gdpr_checkbox_text : __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);
            $row->save_ip = isset($privacy->save_ip) ? $privacy->save_ip : 1;
            $row->save_user_id = isset($privacy->save_user_id) ? $privacy->save_user_id : 1;
          }
        }
      }
    }
    else {
      // Add "Submit" button to new forms.
      $row = new stdClass();
      $row->id = 0;
      $row->backup_id = '';
      $row->title = '';
      $row->mail = '';
      $row->form = '';
      $row->form_front = '<div class="wdform-page-and-images fm-form-builder"><div id="form_id_tempform_view1" class="wdform_page" page_title="Untitled page" next_title="Next" next_type="text" next_class="wdform-page-button" next_checkable="true" previous_title="Previous" previous_type="text" previous_class="wdform-page-button" previous_checkable="false"><div class="wdform_section"><div class="wdform_column"><div wdid="1" class="wdform_row ui-sortable-handle">%1 - type_submit_reset_1%</div></div></div><div valign="top" class="wdform_footer wd-width-100"><div class="wd-width-100"><div class="wd-width-100 wd-table" style="padding-top:10px;"><div class="wd-table-group"><div id="form_id_temppage_nav1" class="wd-table-row"></div></div></div></div></div></div></div>';
      $row->theme = $wpdb->get_var("SELECT id FROM " . $wpdb->prefix . "formmaker_themes WHERE `default`='1'");
      $row->javascript = '';
      $row->submit_text = '';
      $row->url = '';
      $row->submit_text_type = 1;
      $row->script1 = '{all}';
      $row->script2 = '{all}';
      $row->script_user1 = '';
      $row->script_user2 = '';
      $row->counter = 2;
      $row->label_order = '1#**id**#type_submit_reset_1#**label**#type_submit_reset#****##**id**##**label**##****#';
      $row->article_id = 0;
      $row->pagination = 'none';
      $row->show_title = false;
      $row->show_numbers = true;
      $row->public_key = '';
      $row->private_key = '';
      $row->recaptcha_theme = '';
      $row->from_name = '';
      $row->from_mail = '';
      $row->label_order_current = '1#**id**#type_submit_reset_1#**label**#type_submit_reset#****#';
      $row->script_mail_user = '';
      $row->script_mail = '';
      $row->tax = 0;
      $row->payment_currency = '$';
      $row->paypal_email = '';
      $row->checkout_mode = 'testmode';
      $row->paypal_mode = 0;
      $row->published = 1;
      $row->form_fields = '1*:*id*:*type_submit_reset*:*type*:*type_submit_reset_1*:*w_field_label*:*Submit*:*w_submit_title*:*Reset*:*w_reset_title*:**:*w_class*:*false*:*w_act*:**:*new_field*:*';
      $row->savedb = 1;
      $row->sendemail = 1;
      $row->requiredmark = '*';
      $row->submissions_limit = 0;
      $row->submissions_limit_text = __('The limit of submissions for this form has been reached.', WDFMInstance(self::PLUGIN)->prefix);
      $row->reply_to = 0;
      $row->send_to = 0;
      $row->autogen_layout = 1;
      $row->custom_front = '';
      $row->mail_from_user = '';
      $row->mail_from_name_user = '';
      $row->reply_to_user = '';
      $row->save_uploads = 1;
      $row->header_title = '';
      $row->header_description = '';
      $row->header_image_url = '';
      $row->header_image_animation = 'none';
      $row->header_hide_image = 0;
      $row->header_hide = 1;
      $row->condition = '';
      $row->mail_cc = '';
      $row->mail_cc_user = '';
      $row->mail_bcc = '';
      $row->mail_bcc_user = '';
      $row->mail_subject = '';
      $row->mail_subject_user = '';
      $row->mail_mode = 1;
      $row->mail_mode_user = 1;
      $row->mail_send_email_payment = 1;
      $row->mail_send_payment_info = 1;
      $row->mail_send_email_payment_user = 1;
      $row->mail_attachment = 1;
      $row->mail_attachment_user = 1;
      $row->user_id_wd = '';
      $row->sortable = 1;
      $row->frontend_submit_fields = '';
      $row->frontend_submit_stat_fields = '';
      $row->mail_emptyfields = 0;
      $row->mail_verify = 0;
      $row->mail_verify_expiretime = 0;
      $row->mail_verification_post_id	= 0;
      $row->gdpr_checkbox	= 0;
      $row->gdpr_checkbox_text	= __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);
      $row->save_ip	= 1;
      $row->save_user_id = 1;
    }
    $labels2 = array();
    $label_id = array();
    $label_order_original = array();
    $label_type = array();
    $label_all = explode('#****#', $row->label_order);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      array_push($label_id, $label_id_each[0]);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      array_push($label_order_original, addslashes($label_oder_each[0]));
      array_push($label_type, $label_oder_each[1]);
    }
    $labels2['id'] = '"' . implode('","', $label_id) . '"';
    $labels2['label'] = '"' . implode('","', $label_order_original) . '"';
    $labels2['type'] = '"' . implode('","', $label_type) . '"';
    $ids = array();
    $types = array();
    $labels = array();
    $paramss = array();
    $fields = explode('*:*new_field*:*', $row->form_fields);
    $fields = array_slice($fields, 0, count($fields) - 1);
    foreach ( $fields as $field ) {
      $temp = explode('*:*id*:*', $field);
      array_push($ids, $temp[0]);
      $temp = explode('*:*type*:*', $temp[1]);
      array_push($types, $temp[0]);
      $temp = explode('*:*w_field_label*:*', $temp[1]);
      array_push($labels, $temp[0]);
      array_push($paramss, $temp[1]);
    }
    $form = $row->form_front;
    foreach ( $ids as $ids_key => $id ) {
      $label = $labels[$ids_key];
      $type = $types[$ids_key];
      $params = $paramss[$ids_key];
      if ( strpos($form, '%' . $id . ' - ' . $label . '%') || strpos($form, '%' . $id . ' -' . $label . '%') ) {
        $rep = '';
        $arrows = '';
        $param = array();
        $param['attributes'] = '';
        switch ( $type ) {
          case 'type_section_break': {
            $arrows = '<div id="wdform_arrows' . $id . '" class="wdform_arrows" style="display: none;">
                        <span class="wdform_arrows_basic wdform_arrows_container">
                          <span id="edit_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Edit the field" class="page_toolbar fm-ico-edit" ontouchend="edit(&quot;' . $id . '&quot;, event)" onclick="edit(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                          <span id="duplicate_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Duplicate the field" class="page_toolbar fm-ico-duplicate" ontouchend="duplicate(&quot;' . $id . '&quot;, event)" onclick="duplicate(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                          <span id="X_' . $id . '" valign="middle" align="right" class="element_toolbar">
                            <span title="Remove the field" class="page_toolbar fm-ico-delete" onclick="remove_section_break(&quot;' . $id . '&quot;)"></span>
                          </span>
                        </span>
                      </div>';
            break;
          }
          case 'type_send_copy':
          case 'type_stripe':
          case 'type_captcha':
          case 'type_arithmetic_captcha':
          case 'type_recaptcha': {
            $arrows = '<div id="wdform_arrows' . $id . '" class="wdform_arrows" style="display: none;">
                        <div class="wdform_arrows_basic wdform_arrows_container">
						            <span id="edit_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Edit the field" class="page_toolbar fm-ico-edit" ontouchend="edit(&quot;' . $id . '&quot;, event)" onclick="edit(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                          <span id="duplicate_' . $id . '" valign="middle" class="element_toolbar"></span>
                          <span id="X_' . $id . '" valign="middle" align="right" class="element_toolbar">
                            <span title="Remove the field" class="page_toolbar fm-ico-delete" ontouchend="remove_field(&quot;' . $id . '&quot;, event)" onclick="remove_field(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                        </div>
						            <div class="wdform_arrows_advanced wdform_arrows_container" style="display: none;">
                          <span id="left_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the left" class="page_toolbar dashicons dashicons-arrow-left-alt" onclick="left_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="up_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field up" class="page_toolbar dashicons dashicons-arrow-up-alt" onclick="up_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="down_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field down" class="page_toolbar dashicons dashicons-arrow-down-alt" onclick="down_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="right_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the right" class="page_toolbar dashicons dashicons-arrow-right-alt" onclick="right_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="page_up_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the upper page" class="page_toolbar dashicons dashicons-upload" onclick="page_up(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="page_down_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the lower page" class="page_toolbar dashicons dashicons-download" onclick="page_down(&quot;' . $id . '&quot;)"></span>
                          </span>
                        </div>
                      </div>';
            break;
          }
          default : {
            $arrows = '<div id="wdform_arrows' . $id . '" class="wdform_arrows" style="display: none;">
                        <div class="wdform_arrows_basic wdform_arrows_container">                          <span id="edit_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Edit the field" class="page_toolbar fm-ico-edit" ontouchend="edit(&quot;' . $id . '&quot;, event)" onclick="edit(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                          <span id="duplicate_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Duplicate the field" class="page_toolbar fm-ico-duplicate" ontouchend="duplicate(&quot;' . $id . '&quot;, event)" onclick="duplicate(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                          <span id="X_' . $id . '" valign="middle" align="right" class="element_toolbar">
                            <span title="Remove the field" class="page_toolbar fm-ico-delete" ontouchend="remove_field(&quot;' . $id . '&quot;, event)" onclick="remove_field(&quot;' . $id . '&quot;, event)"></span>
                          </span>
                        </div>
						            <div class="wdform_arrows_advanced wdform_arrows_container" style="display: none;">
                          <span id="left_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the left" class="page_toolbar dashicons dashicons-arrow-left-alt" onclick="left_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="up_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field up" class="page_toolbar dashicons dashicons-arrow-up-alt" onclick="up_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="down_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field down" class="page_toolbar dashicons dashicons-arrow-down-alt" onclick="down_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="right_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the right" class="page_toolbar dashicons dashicons-arrow-right-alt" onclick="right_row(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="page_up_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the upper page" class="page_toolbar dashicons dashicons-upload" onclick="page_up(&quot;' . $id . '&quot;)"></span>
                          </span>
                          <span id="page_down_' . $id . '" valign="middle" class="element_toolbar">
                            <span title="Move the field to the lower page" class="page_toolbar dashicons dashicons-download" onclick="page_down(&quot;' . $id . '&quot;)"></span>
                          </span>
                        </div>
                      </div>';
            break;
          }
        }
        switch ( $type ) {
          case 'type_section_break': {
            $params_names = array( 'w_editor' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_section_break" class="wdform_field_section_break">' . $arrows . '<span id="' . $id . '_element_labelform_id_temp" style="display: none;">' . __('Section Break', WDFMInstance(self::PLUGIN)->prefix) . '</span><div id="' . $id . '_element_sectionform_id_temp" align="left" class="wdform_section_break">' . $param['w_editor'] . '</div></div><div id="' . $id . '_element_labelform_id_temp" style="color:red;">' . __('Section Break', WDFMInstance(self::PLUGIN)->prefix) . '</div>';
            break;
          }
          case 'type_editor': {
            $params_names = array( 'w_editor' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_editor" class="wdform_field" >' . $param['w_editor'] . '</div>' . $arrows . '<div id="' . $id . '_element_labelform_id_temp" style="color: red;">' . __('Custom HTML', WDFMInstance(self::PLUGIN)->prefix) . $id . '</div>';
            break;
          }
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $input_active = ($param['w_first_val'] == 'true' ? "checked='checked'" : "");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_send_copy" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" style="display: ' . $param['w_field_label_pos'] . '"><input type="hidden" value="type_send_copy" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp" /><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp" /><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="checkbox" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" onclick="set_checked(&quot;' . $id . '&quot;,&quot;&quot;,&quot;form_id_temp&quot;)" ' . $input_active . ' ' . $param['attributes'] . ' disabled /></div></div>';
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
            if ( strpos($temp, 'w_class') > -1 ) {
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
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_regExp_status'] = (isset($param['w_regExp_status']) ? $param['w_regExp_status'] : "no");
            $param['w_regExp_value'] = (isset($param['w_regExp_value']) ? $param['w_regExp_value'] : "");
            $param['w_regExp_common'] = (isset($param['w_regExp_common']) ? $param['w_regExp_common'] : "");
            $param['w_regExp_arg'] = (isset($param['w_regExp_arg']) ? $param['w_regExp_arg'] : "");
            $param['w_regExp_alert'] = (isset($param['w_regExp_alert']) ? $param['w_regExp_alert'] : "Incorrect Value");
            $param['w_readonly'] = (isset($param['w_readonly']) ? $param['w_readonly'] : "no");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $param['w_class'] = (isset($param['w_class']) ? $param['w_class'] : "");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $rep = '<div id="wdform_field' . $id . '" type="type_text" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '"><input type="hidden" value="type_text" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp" /><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp" /><input type="hidden" value="' . $param['w_readonly'] . '" name="' . $id . '_readonlyform_id_temp" id="' . $id . '_readonlyform_id_temp"/><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_regExp_status'] . '" name="' . $id . '_regExpStatusform_id_temp" id="' . $id . '_regExpStatusform_id_temp"><input type="hidden" value="' . $param['w_regExp_value'] . '" name="' . $id . '_regExp_valueform_id_temp" id="' . $id . '_regExp_valueform_id_temp"><input type="hidden" value="' . $param['w_regExp_common'] . '" name="' . $id . '_regExp_commonform_id_temp" id="' . $id . '_regExp_commonform_id_temp"><input type="hidden" value="' . $param['w_regExp_alert'] . '" name="' . $id . '_regExp_alertform_id_temp" id="' . $id . '_regExp_alertform_id_temp"><input type="hidden" value="' . $param['w_regExp_arg'] . '" name="' . $id . '_regArgumentform_id_temp" id="' . $id . '_regArgumentform_id_temp"><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp" /><input type="text" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" value="' . htmlentities($param['w_first_val'], ENT_COMPAT) . '" title="' . htmlentities($param['w_title'], ENT_COMPAT) . '" placeholder="' . htmlentities($param['w_title'], ENT_COMPAT) . '" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_number" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp"  class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '"><input type="hidden" value="type_number" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><input type="text" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" value="' . htmlentities($param['w_first_val'], ENT_COMPAT) . '" title="' . htmlentities($param['w_title'], ENT_COMPAT) . '" onkeypress="return check_isnum(event)" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div></div>';
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
            if ( strpos($temp, 'w_placeholder') > -1 ) {
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
                'w_placeholder',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            if ( isset($param['w_verification']) && $param['w_verification'] == "yes" ) {
              $display_label_confirm = $display_label;
              $display_element_confirm = $param['w_field_label_pos'];
            }
            else {
              $display_label_confirm = "none";
              $display_element_confirm = "none";
            }
            $param['w_verification'] = isset($param['w_verification']) ? $param['w_verification'] : "no";
            $param['w_verification_label'] = isset($param['w_verification_label']) ? $param['w_verification_label'] : "Password confirmation:";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $confirm_password = '<br><div align="left" id="' . $id . '_1_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label_confirm . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_1_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $param['w_verification_label'] . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_1_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_element_confirm . ';"><input type="hidden" value="' . $param['w_verification'] . '" name="' . $id . '_verification_id_temp" id="' . $id . '_verification_id_temp"><input type="text" id="' . $id . '_1_elementform_id_temp" name="' . $id . '_1_elementform_id_temp" placeholder="' . $param['w_placeholder'] . '" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div>';
            $rep = '<div id="wdform_field' . $id . '" type="type_password" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp"  class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '"><input type="hidden" value="type_password" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><input type="password" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" placeholder="' . $param['w_placeholder'] . '" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div>' . $confirm_password . '</div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $param['w_characters_limit'] = (isset($param['w_characters_limit']) ? $param['w_characters_limit'] : "");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $rep = '<div id="wdform_field' . $id . '" type="type_textarea" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '"><input type="hidden" value="type_textarea" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_characters_limit'] . '" name="' . $id . '_charlimitform_id_temp" id="' . $id . '_charlimitform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><textarea id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" title="' . htmlentities($param['w_title'], ENT_COMPAT) . '" placeholder="' . htmlentities($param['w_title'], ENT_COMPAT) . '" style="width: ' . $param['w_size_w'] . 'px; height: ' . $param['w_size_h'] . 'px;" ' . $param['attributes'] . ' disabled>' . htmlentities($param['w_first_val'], ENT_COMPAT) . '</textarea></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_phone" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_phone" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><div id="' . $id . '_table_name" style="display: table;"><div id="' . $id . '_tr_name1" style="display: table-row;"><div id="' . $id . '_td_name_input_first" style="display: table-cell;"><input type="text" id="' . $id . '_element_firstform_id_temp" name="' . $id . '_element_firstform_id_temp" value="' . htmlentities($w_first_val[0], ENT_COMPAT) . '" title="' . htmlentities($w_title[0], ENT_COMPAT) . '" placeholder="' . htmlentities($w_title[0], ENT_COMPAT) . '" onkeypress="return check_isnum(event)"style="width: 50px;" ' . $param['attributes'] . ' disabled /><span class="wdform_line" style="margin: 0px 4px; padding: 0px;">-</span></div><div id="' . $id . '_td_name_input_last" style="display: table-cell;"><input type="text" id="' . $id . '_element_lastform_id_temp" name="' . $id . '_element_lastform_id_temp" value="' . htmlentities($w_first_val[1], ENT_COMPAT) . '" title="' . htmlentities($w_title[1], ENT_COMPAT) . '" placeholder="' . htmlentities($w_title[1], ENT_COMPAT) . '" onkeypress="return check_isnum(event)"style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div></div><div id="' . $id . '_tr_name2" style="display: table-row;"><div id="' . $id . '_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_area_code">' . $w_mini_labels[0] . '</label></div><div id="' . $id . '_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_phone_number">' . $w_mini_labels[1] . '</label></div></div></div></div></div>';
            break;
          }
          case 'type_phone_new': {
            $temp = $params;
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
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_phone_new" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_phone_new" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><div id="' . $id . '_table_name" style="display: table;"><div id="' . $id . '_tr_name1" style="display: table-row;"><div id="' . $id . '_td_name_input_first" style="display: table-cell;"><input type="text"  id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" value="' . htmlentities($param['w_first_val'], ENT_COMPAT) . '" top-country = "' . $param['w_top_country'] . '" onkeypress="return check_isnum(&quot;' . $id . '_elementform_id_temp&quot;)" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $param['w_name_fields'] = isset($param['w_name_fields']) ? $param['w_name_fields'] : ($param['w_name_format'] == 'normal' ? 'no***no' : 'yes***yes');
            $w_name_fields = explode('***', $param['w_name_fields']);
            $param['w_autofill'] = isset($param['w_autofill']) ? $param['w_autofill'] : 'no';
            $w_name_format = '<div id="' . $id . '_td_name_input_first" style="display: table-cell;"><input type="text" id="' . $id . '_element_firstform_id_temp" name="' . $id . '_element_firstform_id_temp" value="' . htmlentities($w_first_val[0], ENT_COMPAT) . '" title="' . htmlentities($w_title[0], ENT_COMPAT) . '" placeholder="' . htmlentities($w_title[0], ENT_COMPAT) . '" style="margin-right: 10px; width: ' . $param['w_size'] . 'px;"' . $param['attributes'] . ' disabled /></div><div id="' . $id . '_td_name_input_last" style="display: table-cell;"><input type="text" id="' . $id . '_element_lastform_id_temp" name="' . $id . '_element_lastform_id_temp" value="' . htmlentities($w_first_val[1], ENT_COMPAT) . '" title="' . htmlentities($w_title[1], ENT_COMPAT) . '" placeholder="' . htmlentities($w_title[1], ENT_COMPAT) . '" style="margin-right: 10px; width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div>';
            $w_name_format_mini_labels = '<div id="' . $id . '_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_first">' . $w_mini_labels[1] . '</label></div><div id="' . $id . '_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_last">' . $w_mini_labels[2] . '</label></div>';
            if ( $w_name_fields[0] == 'yes' ) {
              $w_name_format = '<div id="' . $id . '_td_name_input_title" style="display: table-cell;"><input type="text" id="' . $id . '_element_titleform_id_temp" name="' . $id . '_element_titleform_id_temp" value="' . htmlentities($w_first_val[2], ENT_COMPAT) . '" title="' . htmlentities($w_title[2], ENT_COMPAT) . '" style="margin: 0px 10px 0px 0px; width: 40px;" disabled /></div>' . $w_name_format;
              $w_name_format_mini_labels = '<div id="' . $id . '_td_name_label_title" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_title">' . $w_mini_labels[0] . '</label></div>' . $w_name_format_mini_labels;
            }
            if ( $w_name_fields[1] == 'yes' ) {
              $w_name_format = $w_name_format . '<div id="' . $id . '_td_name_input_middle" style="display: table-cell;"><input type="text" id="' . $id . '_element_middleform_id_temp" name="' . $id . '_element_middleform_id_temp" value="' . htmlentities($w_first_val[3], ENT_COMPAT) . '" title="' . htmlentities($w_title[3], ENT_COMPAT) . '" style="width: ' . $param['w_size'] . 'px;" disabled /></div>';
              $w_name_format_mini_labels = $w_name_format_mini_labels . '<div id="' . $id . '_td_name_label_middle" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_middle">' . $w_mini_labels[3] . '</label></div>';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_name" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_name" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><input type="hidden" value="' . $param['w_autofill'] . '" name="' . $id . '_autofillform_id_temp" id="' . $id . '_autofillform_id_temp"><input type="hidden" name="' . $id . '_enable_fieldsform_id_temp" id="' . $id . '_enable_fieldsform_id_temp" title="' . $w_name_fields[0] . '" first="yes" last="yes" middle="' . $w_name_fields[1] . '"><div id="' . $id . '_table_name" cellpadding="0" cellspacing="0" style="display: table;"><div id="' . $id . '_tr_name1" style="display: table-row;">' . $w_name_format . '</div><div id="' . $id . '_tr_name2" style="display: table-row;">' . $w_name_format_mini_labels . '</div></div></div></div>';
            break;
          }
          case 'type_address': {
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $w_disabled_fields = explode('***', $param['w_disabled_fields']);
            $hidden_inputs = '';
            $labels_for_id = array( 'street1', 'street2', 'city', 'state', 'postal', 'country' );
            foreach ( $w_disabled_fields as $key => $w_disabled_field ) {
              if ( $key != 6 ) {
                if ( $w_disabled_field == 'yes' ) {
                  $hidden_inputs .= '<input type="hidden" id="' . $id . '_' . $labels_for_id[$key] . 'form_id_temp" value="' . $w_mini_labels[$key] . '" id_for_label="' . ($id + $key) . '">';
                }
              }
            }
            $address_fields = '';
            $g = 0;
            if ( $w_disabled_fields[0] == 'no' ) {
              $g += 2;
              $address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="' . $id . '_street1form_id_temp" name="' . $id . '_street1form_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled /><label class="mini_label" id="' . $id . '_mini_label_street1" style="display: block;">' . $w_mini_labels[0] . '</label></span>';
            }
            if ( $w_disabled_fields[1] == 'no' ) {
              $g += 2;
              $address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="' . $id . '_street2form_id_temp" name="' . ($id + 1) . '_street2form_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled /><label class="mini_label" style="display: block;" id="' . $id . '_mini_label_street2">' . $w_mini_labels[1] . '</label></span>';
            }
            if ( $w_disabled_fields[2] == 'no' ) {
              $g++;
              $address_fields .= '<span style="float: left; width: 48%; padding-bottom: 8px;"><input type="text" id="' . $id . '_cityform_id_temp" name="' . ($id + 2) . '_cityform_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled /><label class="mini_label" style="display: block;" id="' . $id . '_mini_label_city">' . $w_mini_labels[2] . '</label></span>';
            }
            if ( $w_disabled_fields[3] == 'no' ) {
              $g++;
              if ( $w_disabled_fields[5] == 'yes' && $w_disabled_fields[6] == 'yes' ) {
                $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;"><select type="text" id="' . $id . '_stateform_id_temp" name="' . ($id + 3) . '_stateform_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled >';
                $states = WDW_FM_Library(self::PLUGIN)->get_states();
                foreach ($states as $st => $state) {
                  $address_fields .= '<option value="' . $st . '">' . $state . '</option>';
                }
                $address_fields .= '</select><label class="mini_label" style="display: block;" id="' . $id . '_mini_label_state">' . $w_mini_labels[3] . '</label></span>';
              }
              else {
                $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;"><input type="text" id="' . $id . '_stateform_id_temp" name="' . ($id + 3) . '_stateform_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled><label class="mini_label" style="display: block;" id="' . $id . '_mini_label_state">' . $w_mini_labels[3] . '</label></span>';
              }
            }
            if ( $w_disabled_fields[4] == 'no' ) {
              $g++;
              $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;"><input type="text" id="' . $id . '_postalform_id_temp" name="' . ($id + 4) . '_postalform_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled><label class="mini_label" style="display: block;" id="' . $id . '_mini_label_postal">' . $w_mini_labels[4] . '</label></span>';
            }
            if ( $w_disabled_fields[5] == 'no' ) {
              $g++;
              $countries_list = WDW_FM_Library(self::PLUGIN)->get_countries();
              $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;">
                                    <select type="text" id="' . $id . '_countryform_id_temp" name="' . ($id + 5) . '_countryform_id_temp" style="width: 100%;" ' . $param['attributes'] . ' disabled>';
              foreach ($countries_list as $value => $item) {
                $address_fields .= '<option value="' . $value . '">' . $item . '</option>';
              }
              $address_fields .= '</select><label class="mini_label" style="display: block;" id="' . $id . '_mini_label_country">' . $w_mini_labels[5] . '</span>';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_address" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px; vertical-align:top;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_address" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" name="' . $id . '_disable_fieldsform_id_temp" id="' . $id . '_disable_fieldsform_id_temp" street1="' . $w_disabled_fields[0] . '" street2="' . $w_disabled_fields[1] . '" city="' . $w_disabled_fields[2] . '" state="' . $w_disabled_fields[3] . '" postal="' . $w_disabled_fields[4] . '" country="' . $w_disabled_fields[5] . '" us_states="' . $w_disabled_fields[6] . '"><div id="' . $id . '_div_address" style="width: ' . $param['w_size'] . 'px;">' . $address_fields . $hidden_inputs . '</div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_autofill'] = isset($param['w_autofill']) ? $param['w_autofill'] : 'no';
            if ( isset($param['w_verification']) && $param['w_verification'] == "yes" ) {
              $display_label_confirm = $display_label;
              $display_element_confirm = $param['w_field_label_pos'];
            }
            else {
              $display_label_confirm = "none";
              $display_element_confirm = "none";
            }
            $param['w_verification'] = isset($param['w_verification']) ? $param['w_verification'] : "no";
            $param['w_verification_label'] = isset($param['w_verification_label']) ? $param['w_verification_label'] : "E-mail confirmation:";
            $param['w_verification_placeholder'] = isset($param['w_verification_placeholder']) ? $param['w_verification_placeholder'] : "";
            $confirm_emeil = '<div align="left" id="' . $id . '_1_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label_confirm . '; width: ' . $param['w_field_label_size'] . 'px;"></br><span id="' . $id . '_1_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $param['w_verification_label'] . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_1_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_element_confirm . ';"><input type="text" id="' . $id . '_1_elementform_id_temp" name="' . $id . '_1_elementform_id_temp" value="' . $param['w_verification_placeholder'] . '" title="' . $param['w_verification_placeholder'] . '" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div>';
            $rep = '<div id="wdform_field' . $id . '" type="type_submitter_mail" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_submitter_mail" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_unique'] . '" name="' . $id . '_uniqueform_id_temp" id="' . $id . '_uniqueform_id_temp"><input type="hidden" value="' . $param['w_autofill'] . '" name="' . $id . '_autofillform_id_temp" id="' . $id . '_autofillform_id_temp"><input type="hidden" value="' . $param['w_verification'] . '" name="' . $id . '_verification_id_temp" id="' . $id . '_verification_id_temp"><input type="text" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" value="' . htmlentities($param['w_first_val'], ENT_COMPAT) . '" title="' . htmlentities($param['w_title'], ENT_COMPAT) . '" placeholder="' . htmlentities($param['w_title'], ENT_COMPAT) . '" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled /></div>' . $confirm_emeil . '</div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            if ( !isset($param['w_value_disabled']) ) {
              $param['w_value_disabled'] = 'no';
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'checked="checked"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $param['w_use_for_submission'] = isset($param['w_use_for_submission']) ? $param['w_use_for_submission'] : 'no';
            $rep = '<div id="wdform_field' . $id . '" type="type_checkbox" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_checkbox" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_randomize'] . '" name="' . $id . '_randomizeform_id_temp" id="' . $id . '_randomizeform_id_temp"><input type="hidden" value="' . $param['w_allow_other'] . '" name="' . $id . '_allow_otherform_id_temp" id="' . $id . '_allow_otherform_id_temp"><input type="hidden" value="' . $param['w_allow_other_num'] . '" name="' . $id . '_allow_other_numform_id_temp" id="' . $id . '_allow_other_numform_id_temp"><input type="hidden" value="' . $param['w_rowcol'] . '" name="' . $id . '_rowcol_numform_id_temp" id="' . $id . '_rowcol_numform_id_temp"><input type="hidden" value="' . $param['w_limit_choice'] . '" name="' . $id . '_limitchoice_numform_id_temp" id="' . $id . '_limitchoice_numform_id_temp"><input type="hidden" value="' . $param['w_limit_choice_alert'] . '" name="' . $id . '_limitchoicealert_numform_id_temp" id="' . $id . '_limitchoicealert_numform_id_temp"><input type="hidden" value="' . $param['w_field_option_pos'] . '" id="' . $id . '_option_left_right"><input type="hidden" value="' . $param['w_value_disabled'] . '" name="' . $id . '_value_disabledform_id_temp" id="' . $id . '_value_disabledform_id_temp"><input type="hidden" value="' . $param['w_use_for_submission'] . '" name="' . $id . '_use_for_submissionform_id_temp" id="' . $id . '_use_for_submissionform_id_temp"><div style="display: table;"><div id="' . $id . '_table_little" style="display: table-row-group;" ' . ($param['w_flow'] == 'hor' ? 'for_hor="' . $id . '_hor"' : '') . '>';
            if ( $param['w_flow'] == 'hor' ) {
              $j = 0;
              for ( $i = 0; $i < (int) $param['w_rowcol']; $i++ ) {
                $rep .= '<div id="' . $id . '_element_tr' . $i . '" style="display: flex;flex-wrap:wrap;">';
                for ( $l = 0; $l <= (int) (count($param['w_choices']) / $param['w_rowcol']); $l++ ) {
                  if ( $j >= count($param['w_choices']) % $param['w_rowcol'] && $l == (int) (count($param['w_choices']) / $param['w_rowcol']) ) {
                    continue;
                  }
                  if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $l + $i ) {
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $l + $i) . '" idi="' . ((int) $param['w_rowcol'] * $l + $i) . '" style="display: table-cell;"><input type="checkbox" value="' . $param['w_choices'][(int) $param['w_rowcol'] * $l + $i] . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $l + $i) . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $l + $i] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $l + $i) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '">' . $param['w_choices'][(int) $param['w_rowcol'] * $l + $i] . '</label></div>';
                  }
                  else {
                    $where = '';
                    $order_by = '';
                    $db_info = '';
                    if ( isset($param['w_choices_value']) ) {
                      $choise_value = $param['w_choices_value'][(int) $param['w_rowcol'] * $l + $i];
                    }
                    else {
                      $choise_value = $param['w_choices'][(int) $param['w_rowcol'] * $l + $i];
                    }
                    $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                    if ( isset($param['w_choices_params']) && $param['w_choices_params'][(int) $param['w_rowcol'] * $l + $i] ) {
                      $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][(int) $param['w_rowcol'] * $l + $i]);
                      $where = 'where="' . $w_choices_params[0] . '"';
                      $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                      $order_by = "order_by='" . $w_choices_params[0] . "'";
                      $db_info = "db_info='" . $w_choices_params[1] . "'";
                    }
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $l + $i) . '" idi="' . ((int) $param['w_rowcol'] * $l + $i) . '" style="display: table-cell;"><input type="checkbox" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" name="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" onclick="set_checked(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $l + $i) . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $l + $i] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $l + $i) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][(int) $param['w_rowcol'] * $l + $i] . '</label></div>';
                  }
                }
                $j++;
                $rep .= '</div>';
              }
            }
            else {
              for ( $i = 0; $i < (int) (count($param['w_choices']) / $param['w_rowcol']); $i++ ) {
                $rep .= '<div id="' . $id . '_element_tr' . $i . '" style="display: table-row;">';
                if ( count($param['w_choices']) > (int) $param['w_rowcol'] ) {
                  for ( $l = 0; $l < $param['w_rowcol']; $l++ ) {
                    if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l ) {
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="checkbox" value="' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . '  ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '">' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                    else {
                      $where = '';
                      $order_by = '';
                      $db_info = '';
                      if ( isset($param['w_choices_value']) ) {
                        $choise_value = $param['w_choices_value'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      else {
                        $choise_value = $param['w_choices'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                      if ( isset($param['w_choices_params']) && $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l] ) {
                        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l]);
                        $where = 'where="' . $w_choices_params[0] . '"';
                        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                        $order_by = "order_by='" . $w_choices_params[0] . "'";
                        $db_info = "db_info='" . $w_choices_params[1] . "'";
                      }
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="checkbox" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" onclick="set_checked(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '"
                  ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                  }
                }
                else {
                  for ( $l = 0; $l < count($param['w_choices']); $l++ ) {
                    if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l ) {
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="checkbox" value="' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . '  disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '">' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                    else {
                      $where = '';
                      $order_by = '';
                      $db_info = '';
                      if ( isset($param['w_choices_value']) ) {
                        $choise_value = $param['w_choices_value'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      else {
                        $choise_value = $param['w_choices'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                      if ( isset($param['w_choices_params']) && $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l] ) {
                        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l]);
                        $where = 'where="' . $w_choices_params[0] . '"';
                        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                        $order_by = "order_by='" . $w_choices_params[0] . "'";
                        $db_info = "db_info='" . $w_choices_params[1] . "'";
                      }
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="checkbox" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" onclick="set_checked(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                  }
                }
                $rep .= '</div>';
              }
              if ( count($param['w_choices']) % $param['w_rowcol'] != 0 ) {
                $rep .= '<div id="' . $id . '_element_tr' . ((int) (count($param['w_choices']) / (int) $param['w_rowcol'])) . '" style="display: table-row;">';
                for ( $k = 0; $k < count($param['w_choices']) % $param['w_rowcol']; $k++ ) {
                  $l = count($param['w_choices']) - count($param['w_choices']) % $param['w_rowcol'] + $k;
                  if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $l ) {
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . $l . '" idi="' . $l . '" style="display: table-cell;"><input type="checkbox" value="' . $param['w_choices'][$l] . '" id="' . $id . '_elementform_id_temp' . $l . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $l . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][$l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $l . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $l . '">' . $param['w_choices'][$l] . '</label></div>';
                  }
                  else {
                    $where = '';
                    $order_by = '';
                    $db_info = '';
                    if ( isset($param['w_choices_value']) ) {
                      $choise_value = $param['w_choices_value'][$l];
                    }
                    else {
                      $choise_value = $param['w_choices'][$l];
                    }
                    $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                    if ( isset($param['w_choices_params']) && $param['w_choices_params'][$l] ) {
                      $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$l]);
                      $where = 'where="' . $w_choices_params[0] . '"';
                      $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                      $order_by = "order_by='" . $w_choices_params[0] . "'";
                      $db_info = "db_info='" . $w_choices_params[1] . "'";
                    }
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . $l . '" idi="' . $l . '" style="display: table-cell;"><input type="checkbox" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . $l . '" name="' . $id . '_elementform_id_temp' . $l . '" onclick="set_checked(&quot;' . $id . '&quot;,&quot;' . $l . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $l . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $l . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][$l] . '</label></div>';
                  }
                }
                $rep .= '</div>';
              }
            }
            $rep .= '</div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            if ( !isset($param['w_value_disabled']) ) {
              $param['w_value_disabled'] = 'no';
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'checked="checked"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $param['w_use_for_submission'] = isset($param['w_use_for_submission']) ? $param['w_use_for_submission'] : 'no';
            $rep = '<div id="wdform_field' . $id . '" type="type_radio" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_radio" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_randomize'] . '" name="' . $id . '_randomizeform_id_temp" id="' . $id . '_randomizeform_id_temp"><input type="hidden" value="' . $param['w_allow_other'] . '" name="' . $id . '_allow_otherform_id_temp" id="' . $id . '_allow_otherform_id_temp"><input type="hidden" value="' . $param['w_allow_other_num'] . '" name="' . $id . '_allow_other_numform_id_temp" id="' . $id . '_allow_other_numform_id_temp"><input type="hidden" value="' . $param['w_rowcol'] . '" name="' . $id . '_rowcol_numform_id_temp" id="' . $id . '_rowcol_numform_id_temp"><input type="hidden" value="' . $param['w_field_option_pos'] . '" id="' . $id . '_option_left_right"><input type="hidden" value="' . $param['w_value_disabled'] . '" name="' . $id . '_value_disabledform_id_temp" id="' . $id . '_value_disabledform_id_temp"><input type="hidden" value="' . $param['w_use_for_submission'] . '" name="' . $id . '_use_for_submissionform_id_temp" id="' . $id . '_use_for_submissionform_id_temp"><div style="display: table;"><div id="' . $id . '_table_little" style="display: table-row-group;" ' . ($param['w_flow'] == 'hor' ? 'for_hor="' . $id . '_hor"' : '') . '>';
            if ( $param['w_flow'] == 'hor' ) {
              $j = 0;
              for ( $i = 0; $i < (int) $param['w_rowcol']; $i++ ) {
                $rep .= '<div id="' . $id . '_element_tr' . $i . '" style="display: flex;flex-wrap:wrap;">';
                for ( $l = 0; $l <= (int) (count($param['w_choices']) / $param['w_rowcol']); $l++ ) {
                  if ( $j >= count($param['w_choices']) % $param['w_rowcol'] && $l == (int) (count($param['w_choices']) / $param['w_rowcol']) ) {
                    continue;
                  }
                  if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $l + $i ) {
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $l + $i) . '" idi="' . ((int) $param['w_rowcol'] * $l + $i) . '" style="display: table-cell;"><input type="radio" value="' . $param['w_choices'][(int) $param['w_rowcol'] * $l + $i] . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $l + $i) . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $l + $i] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $l + $i) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '">' . $param['w_choices'][(int) $param['w_rowcol'] * $l + $i] . '</label></div>';
                  }
                  else {
                    $where = '';
                    $order_by = '';
                    $db_info = '';
                    if ( isset($param['w_choices_value']) ) {
                      $choise_value = $param['w_choices_value'][(int) $param['w_rowcol'] * $l + $i];
                    }
                    else {
                      $choise_value = $param['w_choices'][(int) $param['w_rowcol'] * $l + $i];
                    }
                    $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                    if ( isset($param['w_choices_params']) && $param['w_choices_params'][(int) $param['w_rowcol'] * $l + $i] ) {
                      $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][(int) $param['w_rowcol'] * $l + $i]);
                      $where = 'where="' . $w_choices_params[0] . '"';
                      $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                      $order_by = "order_by='" . $w_choices_params[0] . "'";
                      $db_info = "db_info='" . $w_choices_params[1] . "'";
                    }
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $l + $i) . '" idi="' . ((int) $param['w_rowcol'] * $l + $i) . '" style="display: table-cell;"><input type="radio" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" name="' . $id . '_elementform_id_temp" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $l + $i) . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $l + $i] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $l + $i) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $l + $i) . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][(int) $param['w_rowcol'] * $l + $i] . '</label></div>';
                  }
                }
                $j++;
                $rep .= '</div>';
              }
            }
            else {
              for ( $i = 0; $i < (int) (count($param['w_choices']) / $param['w_rowcol']); $i++ ) {
                $rep .= '<div id="' . $id . '_element_tr' . $i . '" style="display: table-row;">';
                if ( count($param['w_choices']) > (int) $param['w_rowcol'] ) {
                  for ( $l = 0; $l < $param['w_rowcol']; $l++ ) {
                    if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l ) {
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="radio" value="' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . '  ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '">' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                    else {
                      $where = '';
                      $order_by = '';
                      $db_info = '';
                      if ( isset($param['w_choices_value']) ) {
                        $choise_value = $param['w_choices_value'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      else {
                        $choise_value = $param['w_choices'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                      if ( isset($param['w_choices_params']) && $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l] ) {
                        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l]);
                        $where = 'where="' . $w_choices_params[0] . '"';
                        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                        $order_by = "order_by='" . $w_choices_params[0] . "'";
                        $db_info = "db_info='" . $w_choices_params[1] . "'";
                      }
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="radio" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                  }
                }
                else {
                  for ( $l = 0; $l < count($param['w_choices']); $l++ ) {
                    if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l ) {
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="radio" value="' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . '  disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '">' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                    else {
                      $where = '';
                      $order_by = '';
                      $db_info = '';
                      if ( isset($param['w_choices_value']) ) {
                        $choise_value = $param['w_choices_value'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      else {
                        $choise_value = $param['w_choices'][(int) $param['w_rowcol'] * $i + $l];
                      }
                      $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                      if ( isset($param['w_choices_params']) && $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l] ) {
                        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][(int) $param['w_rowcol'] * $i + $l]);
                        $where = 'where="' . $w_choices_params[0] . '"';
                        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                        $order_by = "order_by='" . $w_choices_params[0] . "'";
                        $db_info = "db_info='" . $w_choices_params[1] . "'";
                      }
                      $rep .= '<div valign="top" id="' . $id . '_td_little' . ((int) $param['w_rowcol'] * $i + $l) . '" idi="' . ((int) $param['w_rowcol'] * $i + $l) . '" style="display: table-cell;"><input type="radio" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" name="' . $id . '_elementform_id_temp" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . ((int) $param['w_rowcol'] * $i + $l) . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][(int) $param['w_rowcol'] * $i + $l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . ((int) $param['w_rowcol'] * $i + $l) . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . ((int) $param['w_rowcol'] * $i + $l) . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][(int) $param['w_rowcol'] * $i + $l] . '</label></div>';
                    }
                  }
                }
                $rep .= '</div>';
              }
              if ( count($param['w_choices']) % $param['w_rowcol'] != 0 ) {
                $rep .= '<div id="' . $id . '_element_tr' . ((int) (count($param['w_choices']) / (int) $param['w_rowcol'])) . '" style="display: table-row;">';
                for ( $k = 0; $k < count($param['w_choices']) % $param['w_rowcol']; $k++ ) {
                  $l = count($param['w_choices']) - count($param['w_choices']) % $param['w_rowcol'] + $k;
                  if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $l ) {
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . $l . '" idi="' . $l . '" style="display: table-cell;"><input type="radio" value="' . $param['w_choices'][$l] . '" id="' . $id . '_elementform_id_temp' . $l . '" name="' . $id . '_elementform_id_temp" other="1" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $l . '&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;' . $id . '&quot;,&quot;form_id_temp&quot;);" ' . $param['w_choices_checked'][$l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $l . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $l . '">' . $param['w_choices'][$l] . '</label></div>';
                  }
                  else {
                    $where = '';
                    $order_by = '';
                    $db_info = '';
                    if ( isset($param['w_choices_value']) ) {
                      $choise_value = $param['w_choices_value'][$l];
                    }
                    else {
                      $choise_value = $param['w_choices'][$l];
                    }
                    $choise_value = htmlentities($choise_value, ENT_COMPAT, "UTF-8");
                    if ( isset($param['w_choices_params']) && $param['w_choices_params'][$l] ) {
                      $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$l]);
                      $where = 'where="' . $w_choices_params[0] . '"';
                      $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                      $order_by = "order_by='" . $w_choices_params[0] . "'";
                      $db_info = "db_info='" . $w_choices_params[1] . "'";
                    }
                    $rep .= '<div valign="top" id="' . $id . '_td_little' . $l . '" idi="' . $l . '" style="display: table-cell;"><input type="radio" value="' . $choise_value . '" id="' . $id . '_elementform_id_temp' . $l . '" name="' . $id . '_elementform_id_temp" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $l . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$l] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $l . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $l . '"
                ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $param['w_choices'][$l] . '</label></div>';
                  }
                }
                $rep .= '</div>';
              }
            }
            $rep .= '</div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            $param['w_choices_disabled'] = explode('***', $param['w_choices_disabled']);
            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            if ( !isset($param['w_value_disabled']) ) {
              $param['w_value_disabled'] = 'no';
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'selected="selected"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $param['w_use_for_submission'] = isset($param['w_use_for_submission']) ? $param['w_use_for_submission'] : 'no';
            $rep = '<div id="wdform_field' . $id . '" type="type_own_select" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; "><input type="hidden" value="type_own_select" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_value_disabled'] . '" name="' . $id . '_value_disabledform_id_temp" id="' . $id . '_value_disabledform_id_temp"><input type="hidden" value="' . $param['w_use_for_submission'] . '" name="' . $id . '_use_for_submissionform_id_temp" id="' . $id . '_use_for_submissionform_id_temp"><select id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" onchange="set_select(this)" style="width: ' . $param['w_size'] . 'px;"  ' . $param['attributes'] . ' disabled>';
            foreach ( $param['w_choices'] as $key => $choice ) {
              $where = '';
              $order_by = '';
              $db_info = '';
              $choice_value = $param['w_choices_disabled'][$key] == 'true' ? '' : (isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice);
              if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                $where = 'where="' . $w_choices_params[0] . '"';
                $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                $order_by = "order_by='" . $w_choices_params[0] . "'";
                $db_info = "db_info='" . $w_choices_params[1] . "'";
              }
              $rep .= '<option id="' . $id . '_option' . $key . '" value="' . $choice_value . '" onselect="set_select(&quot;' . $id . '_option' . $key . '&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</option>';
            }
            $rep .= '</select></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_countries'] = explode('***', $param['w_countries']);
            $rep = '<div id="wdform_field' . $id . '" type="type_country" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; "><input type="hidden" value="type_country" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><select id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" style="width: ' . $param['w_size'] . 'px;"  ' . $param['attributes'] . ' disabled>';
            foreach ( $param['w_countries'] as $key => $choice ) {
              $choice_value = $choice;
              $rep .= '<option value="' . $choice_value . '">' . $choice . '</option>';
            }
            $rep .= '</select></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            if ( $param['w_sec'] == '1' ) {
              $w_sec = '<div align="center" style="display: table-cell;"><span class="wdform_colon" style="vertical-align: middle;">&nbsp;:&nbsp;</span></div><div id="' . $id . '_td_time_input3" style="width: 32px; display: table-cell;"><input type="text" value="' . $param['w_ss'] . '" class="time_box" id="' . $id . '_ssform_id_temp" name="' . $id . '_ssform_id_temp" onblur="add_0(&quot;' . $id . '_ssform_id_temp&quot;)" ' . $param['attributes'] . ' disabled /></div>';
              $w_sec_label = '<div style="display: table-cell;"></div><div id="' . $id . '_td_time_label3" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_ss">' . $w_mini_labels[2] . '</label></div>';
            }
            else {
              $w_sec = '';
              $w_sec_label = '';
            }
            if ( $param['w_time_type'] == '12' ) {
              if ( $param['w_am_pm'] == 'am' ) {
                $am_ = "selected=\"selected\"";
                $pm_ = "";
              }
              else {
                $am_ = "";
                $pm_ = "selected=\"selected\"";
              }
              $w_time_type = '<div id="' . $id . '_am_pm_select" class="td_am_pm_select" style="display: table-cell;"><select class="am_pm_select" name="' . $id . '_am_pmform_id_temp" id="' . $id . '_am_pmform_id_temp" onchange="set_sel_am_pm(this)" ' . $param['attributes'] . '><option value="am" ' . $am_ . '>AM</option><option value="pm" ' . $pm_ . '>PM</option></select></div>';
              $w_time_type_label = '<div id="' . $id . '_am_pm_label" class="td_am_pm_select" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_am_pm">' . $w_mini_labels[3] . '</label></div>';
            }
            else {
              $w_time_type = '';
              $w_time_type_label = '';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_time" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_time" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><div id="' . $id . '_table_time" style="display: table;"><div id="' . $id . '_tr_time1" style="display: table-row;"><div id="' . $id . '_td_time_input1" style="width: 32px; display: table-cell;"><input type="text" value="' . $param['w_hh'] . '" class="time_box" id="' . $id . '_hhform_id_temp" name="' . $id . '_hhform_id_temp" onblur="add_0(&quot;' . $id . '_hhform_id_temp&quot;)" ' . $param['attributes'] . ' disabled/></div><div align="center" style="display: table-cell;"><span class="wdform_colon" style="vertical-align: middle;">&nbsp;:&nbsp;</span></div><div id="' . $id . '_td_time_input2" style="width: 32px; display: table-cell;"><input type="text" value="' . $param['w_mm'] . '" class="time_box" id="' . $id . '_mmform_id_temp" name="' . $id . '_mmform_id_temp" onblur="add_0(&quot;' . $id . '_mmform_id_temp&quot;)" ' . $param['attributes'] . ' disabled/></div>' . $w_sec . $w_time_type . '</div><div id="' . $id . '_tr_time2" style="display: table-row;"><div id="' . $id . '_td_time_label1" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_hh">' . $w_mini_labels[0] . '</label></div><div style="display: table-cell;"></div><div id="' . $id . '_td_time_label2" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_mm">' . $w_mini_labels[1] . '</label></div>' . $w_sec_label . $w_time_type_label . '</div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_disable_past_days'] = isset($param['w_disable_past_days']) ? $param['w_disable_past_days'] : 'no';
            $disable_past_days = $param['w_disable_past_days'] == 'yes' ? 'true' : 'false';
            $rep = '<div id="wdform_field' . $id . '" type="type_date" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_date" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_disable_past_days'] . '" name="' . $id . '_dis_past_daysform_id_temp" id="' . $id . '_dis_past_daysform_id_temp"><input type="text" value="' . $param['w_date'] . '" class="wdform-date wd-datepicker" data-format="' . $param['w_format'] . '" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" maxlength="10" size="10" ' . $param['attributes'] . ' disabled/></div></div>';
            break;
          }
          /////////////////////////  type_date_new ////////////////////////////
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $w_show_week_days = explode('***', $param['w_show_days']);
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_disable_past_days'] = isset($param['w_disable_past_days']) ? $param['w_disable_past_days'] : 'no';
            $disable_past_days = $param['w_disable_past_days'] == 'yes' ? 'true' : 'false';
            $display_image_date = $param['w_show_image'] == 'yes' ? 'inline' : 'none';
            $rep = '<div id="wdform_field' . $id . '" type="type_date_new" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_date_new" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp">
      <input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp">
      
      <input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/>
      
      <input type="hidden" value="' . $param['w_show_image'] . '" name="' . $id . '_show_imageform_id_temp" id="' . $id . '_show_imageform_id_temp">
      
      <input type="hidden" value="' . $param['w_disable_past_days'] . '" name="' . $id . '_dis_past_daysform_id_temp" id="' . $id . '_dis_past_daysform_id_temp">
      
      <input type="hidden" value="' . $param['w_default_date'] . '" name="' . $id . '_default_date_id_temp" id="' . $id . '_default_date_id_temp">
      <input type="hidden" value="' . $param['w_min_date'] . '" name="' . $id . '_min_date_id_temp" id="' . $id . '_min_date_id_temp">
      <input type="hidden" value="' . $param['w_max_date'] . '" name="' . $id . '_max_date_id_temp" id="' . $id . '_max_date_id_temp">
      <input type="hidden" value="' . $param['w_invalid_dates'] . '" name="' . $id . '_invalid_dates_id_temp" id="' . $id . '_invalid_dates_id_temp">
      
      <input type="hidden" value="' . $param['w_start_day'] . '" name="' . $id . '_start_dayform_id_temp" id="' . $id . '_start_dayform_id_temp">

     <input type="hidden" value="' . $param['w_hide_time'] . '" name="' . $id . '_hide_timeform_id_temp" id="' . $id . '_hide_timeform_id_temp">
     
     <input type="hidden"  name="' . $id . '_show_week_days" id="' . $id . '_show_week_days" sunday="' . $w_show_week_days[0] . '" monday="' . $w_show_week_days[1] . '" tuesday="' . $w_show_week_days[2] . '" wednesday="' . $w_show_week_days[3] . '" thursday="' . $w_show_week_days[4] . '" friday="' . $w_show_week_days[5] . '" saturday="' . $w_show_week_days[6] . '" />
     <input type="text"  id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled />
     <span id="' . $id . '_show_imagedateform_id_temp" class="dashicons dashicons-calendar-alt wd-calendar-button ' . ($param['w_show_image'] == "yes" ? "wd-inline-block" : "wd-hidden") . '"></span>
     <input id="' . $id . '_buttonform_id_temp" type="hidden" value="' . $param['w_but_val'] . '" format="' . $param['w_format'] . '" ></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $w_show_week_days = explode('***', $param['w_show_days']);
            $default_day_array = explode(',', $param['w_date']);
            $default_day_start = $default_day_array[0];
            $default_day_end = $default_day_array[1];
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_disable_past_days'] = isset($param['w_disable_past_days']) ? $param['w_disable_past_days'] : 'no';
            $disable_past_days = $param['w_disable_past_days'] == 'yes' ? 'true' : 'false';
            $display_image_date = $param['w_show_image'] == 'yes' ? 'inline' : 'none';
            $rep = '<div id="wdform_field' . $id . '" type="type_date_range" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';">
  <input type="hidden" value="type_date_range" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp">
  <input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp">   
  <input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/>   
  <input type="hidden" value="' . $param['w_show_image'] . '" name="' . $id . '_show_imageform_id_temp" id="' . $id . '_show_imageform_id_temp">   
  <input type="hidden" value="' . $param['w_disable_past_days'] . '" name="' . $id . '_dis_past_daysform_id_temp" id="' . $id . '_dis_past_daysform_id_temp">   
  <input type="hidden" value="' . $param['w_default_date_start'] . '" name="' . $id . '_default_date_id_temp_start" id="' . $id . '_default_date_id_temp_start">   
  <input type="hidden" value="' . $param['w_default_date_end'] . '" name="' . $id . '_default_date_id_temp_end" id="' . $id . '_default_date_id_temp_end">    
  <input type="hidden" value="' . $param['w_min_date'] . '" name="' . $id . '_min_date_id_temp" id="' . $id . '_min_date_id_temp">   
  <input type="hidden" value="' . $param['w_max_date'] . '" name="' . $id . '_max_date_id_temp" id="' . $id . '_max_date_id_temp">
  <input type="hidden" value="' . $param['w_invalid_dates'] . '" name="' . $id . '_invalid_dates_id_temp" id="' . $id . '_invalid_dates_id_temp">   
  <input type="hidden" value="' . $param['w_start_day'] . '" name="' . $id . '_start_dayform_id_temp" id="' . $id . '_start_dayform_id_temp">
  <input type="hidden" value="' . $param['w_hide_time'] . '" name="' . $id . '_hide_timeform_id_temp" id="' . $id . '_hide_timeform_id_temp"> 
  <input type="hidden"  name="' . $id . '_show_week_days" id="' . $id . '_show_week_days" sunday="' . $w_show_week_days[0] . '" monday="' . $w_show_week_days[1] . '" tuesday="' . $w_show_week_days[2] . '" wednesday="' . $w_show_week_days[3] . '" thursday="' . $w_show_week_days[4] . '" friday="' . $w_show_week_days[5] . '" saturday="' . $w_show_week_days[6] . '">
  <input type="text"   value="' . $param['w_default_date_start'] . '" id="' . $id . '_elementform_id_temp0" name="' . $id . '_elementform_id_temp0" style="width: ' . $param['w_size'] . 'px;"  ' . $param['attributes'] . ' disabled />
  <span id="' . $id . '_show_imagedateform_id_temp0" class="dashicons dashicons-calendar-alt wd-calendar-button ' . ($param['w_show_image'] == "yes" ? "wd-inline-block" : "wd-hidden") . '"></span>
  <span>-</span>
  <input type="text" value="' . $param['w_default_date_end'] . '" id="' . $id . '_elementform_id_temp1" name="' . $id . '_elementform_id_temp1" style="width: ' . $param['w_size'] . 'px;"  ' . $param['attributes'] . ' disabled />
  <span id="' . $id . '_show_imagedateform_id_temp1" class="dashicons dashicons-calendar-alt wd-calendar-button ' . ($param['w_show_image'] == "yes" ? "wd-inline-block" : "wd-hidden") . '"></span>
  <input id="' . $id . '_buttonform_id_temp" type="hidden" value="' . $param['w_but_val'] . '" format="' . $param['w_format'] . '" />
  </div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");

		  $param['w_min_day'] = (isset($param['w_min_day']) ? $param['w_min_day'] : "");
            $param['w_min_month'] = (isset($param['w_min_month']) ? $param['w_min_month'] : "");
		  $param['w_min_year'] = (isset($param['w_min_year']) ? $param['w_min_year'] : "");
		  $param['w_min_dob_alert'] = (isset($param['w_min_dob_alert']) ? $param['w_min_dob_alert'] : "Date of birth does not meet specified requirements.");

            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            if ( $param['w_day_type'] == "SELECT" ) {
              $w_day_type = '<select id="' . $id . '_dayform_id_temp" name="' . $id . '_dayform_id_temp" onchange="set_select(this)" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . ' disabled><option value=""></option>';
              for ( $k = 0; $k <= 31; $k++ ) {
                if ( $k < 10 ) {
                  if ( $param['w_day'] == '0' . $k ) {
                    $selected = "selected=\"selected\"";
                  }
                  else {
                    $selected = "";
                  }
                  $w_day_type .= '<option value="0' . $k . '" ' . $selected . '>0' . $k . '</option>';
                }
                else {
                  if ( $param['w_day'] == '' . $k ) {
                    $selected = "selected=\"selected\"";
                  }
                  else {
                    $selected = "";
                  }
                  $w_day_type .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
                }
              }
              $w_day_type .= '</select>';
            }
            else {
              $w_day_type = '<input type="text" value="' . $param['w_day'] . '" id="' . $id . '_dayform_id_temp" name="' . $id . '_dayform_id_temp" onblur="if (this.value==&quot;0&quot;) this.value=&quot;&quot;; else add_0(&quot;' . $id . '_dayform_id_temp&quot;)" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . ' disabled/>';
            }
            if ( $param['w_month_type'] == "SELECT" ) {
              $w_month_type = '<select id="' . $id . '_monthform_id_temp" name="' . $id . '_monthform_id_temp" onchange="set_select(this)" style="width: ' . $param['w_month_size'] . 'px;" ' . $param['attributes'] . ' disabled><option value=""></option><option value="01" ' . ($param['w_month'] == "01" ? "selected=\"selected\"" : "") . '  ><!--repstart-->January<!--repend--></option><option value="02" ' . ($param['w_month'] == "02" ? "selected=\"selected\"" : "") . '><!--repstart-->February<!--repend--></option><option value="03" ' . ($param['w_month'] == "03" ? "selected=\"selected\"" : "") . '><!--repstart-->March<!--repend--></option><option value="04" ' . ($param['w_month'] == "04" ? "selected=\"selected\"" : "") . ' ><!--repstart-->April<!--repend--></option><option value="05" ' . ($param['w_month'] == "05" ? "selected=\"selected\"" : "") . ' ><!--repstart-->May<!--repend--></option><option value="06" ' . ($param['w_month'] == "06" ? "selected=\"selected\"" : "") . ' ><!--repstart-->June<!--repend--></option><option value="07" ' . ($param['w_month'] == "07" ? "selected=\"selected\"" : "") . ' ><!--repstart-->July<!--repend--></option><option value="08" ' . ($param['w_month'] == "08" ? "selected=\"selected\"" : "") . ' ><!--repstart-->August<!--repend--></option><option value="09" ' . ($param['w_month'] == "09" ? "selected=\"selected\"" : "") . ' ><!--repstart-->September<!--repend--></option><option value="10" ' . ($param['w_month'] == "10" ? "selected=\"selected\"" : "") . ' ><!--repstart-->October<!--repend--></option><option value="11" ' . ($param['w_month'] == "11" ? "selected=\"selected\"" : "") . '><!--repstart-->November<!--repend--></option><option value="12" ' . ($param['w_month'] == "12" ? "selected=\"selected\"" : "") . ' ><!--repstart-->December<!--repend--></option></select>';
            }
            else {
              $w_month_type = '<input type="text" value="' . $param['w_month'] . '" id="' . $id . '_monthform_id_temp" name="' . $id . '_monthform_id_temp" onblur="if (this.value==&quot;0&quot;) this.value=&quot;&quot;; else add_0(&quot;' . $id . '_monthform_id_temp&quot;)" style="width: ' . $param['w_month_size'] . 'px;" ' . $param['attributes'] . ' disabled/>';
            }
            $param['w_to'] = isset($param['w_to']) && $param['w_to'] != "NaN" ? $param['w_to'] : date("Y");
            if ( $param['w_year_type'] == "SELECT" ) {
              $w_year_type = '<select id="' . $id . '_yearform_id_temp" name="' . $id . '_yearform_id_temp" onchange="set_select(this)" from="' . $param['w_from'] . '" to="' . $param['w_to'] . '" style="width: ' . $param['w_year_size'] . 'px;" ' . $param['attributes'] . ' disabled><option value=""></option>';
              for ( $k = $param['w_to']; $k >= $param['w_from']; $k-- ) {
                if ( $param['w_year'] == $k ) {
                  $selected = "selected=\"selected\"";
                }
                else {
                  $selected = "";
                }
                $w_year_type .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
              }
              $w_year_type .= '</select>';
            }
            else {
              $w_year_type = '<input type="text" value="' . $param['w_year'] . '" id="' . $id . '_yearform_id_temp" name="' . $id . '_yearform_id_temp" from="' . $param['w_from'] . '" to="' . $param['w_to'] . '" style="width: ' . $param['w_year_size'] . 'px;" ' . $param['attributes'] . ' disabled/>';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_date_fields" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_date_fields" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_min_year'] . '" name="' . $id . '_min_year_id_temp" id="' . $id . '_min_year_id_temp"/><input type="hidden" value="' . $param['w_min_month'] . '" name="' . $id . '_min_month_id_temp" id="' . $id . '_min_month_id_temp"/><input type="hidden" value="' . $param['w_min_day'] . '" name="' . $id . '_min_day_id_temp" id="' . $id . '_min_day_id_temp"/><input type="hidden" value="' . $param['w_min_dob_alert'] . '" name="' . $id . '_min_dob_alert_id_temp" id="' . $id . '_min_dob_alert_id_temp"><div id="' . $id . '_table_date" style="display: table;"><div id="' . $id . '_tr_date1" style="display: table-row;"><div id="' . $id . '_td_date_input1" style="display: table-cell;">
            ' . $w_day_type . '
            
            </div><div id="' . $id . '_td_date_separator1" style="display: table-cell;"><span id="' . $id . '_separator1" class="wdform_separator">' . $param['w_divider'] . '</span></div><div id="' . $id . '_td_date_input2" style="display: table-cell;">' . $w_month_type . '</div><div id="' . $id . '_td_date_separator2" style="display: table-cell;"><span id="' . $id . '_separator2" class="wdform_separator">' . $param['w_divider'] . '</span></div><div id="' . $id . '_td_date_input3" style="display: table-cell;">' . $w_year_type . '</div></div><div id="' . $id . '_tr_date2" style="display: table-row;"><div id="' . $id . '_td_date_label1" style="display: table-cell;"><label class="mini_label" id="' . $id . '_day_label">' . $param['w_day_label'] . '</label></div><div style="display: table-cell;"></div><div id="' . $id . '_td_date_label2" style="display: table-cell;"><label class="mini_label" id="' . $id . '_month_label">' . $param['w_month_label'] . '</label></div><div style="display: table-cell;"></div><div id="' . $id . '_td_date_label3" style="display: table-cell;"><label class="mini_label" id="' . $id . '_year_label">' . $param['w_year_label'] . '</label></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              if ( isset($temp[1]) ) {
                $temp = $temp[1];
              }
              else {
                $temp = '';
              }
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $multiple = ($param['w_multiple'] == "yes" ? "multiple='multiple'" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_file_upload" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_file_upload" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="***max_sizeskizb' . $id . '***' . $param['w_max_size'] . '***max_sizeverj' . $id . '***" id="' . $id . '_max_size" name="' . $id . '_max_size"><input type="hidden" value="***destinationskizb' . $id . '***' . $param['w_destination'] . '***destinationverj' . $id . '***" id="' . $id . '_destination" name="' . $id . '_destination"><input type="hidden" value="***extensionskizb' . $id . '***' . $param['w_extension'] . '***extensionverj' . $id . '***" id="' . $id . '_extension" name="' . $id . '_extension"><input type="file" class="file_upload" id="' . $id . '_elementform_id_temp" name="' . $id . '_fileform_id_temp"  ' . $multiple . ' ' . $param['attributes'] . ' disabled/></div></div>';
            break;
          }
          case 'type_captcha': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_digit', 'w_class' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_digit',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $rep = '<div id="wdform_field' . $id . '" type="type_captcha" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display:' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_captcha" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><div style="display: table;"><div style="display: table-row;"><div valign="middle" style="display: table-cell;"><img type="captcha" digit="' . $param['w_digit'] . '" src="' . add_query_arg(array(
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              'action' => 'formmakerwdcaptcha' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              'digit' => $param['w_digit'],
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              'i' => 'form_id_temp',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ), admin_url('admin-ajax.php')) . '" id="_wd_captchaform_id_temp" class="captcha_img" onclick="captcha_refresh(&quot;_wd_captcha&quot;,&quot;form_id_temp&quot;)" ' . $param['attributes'] . '></div><div valign="middle" style="display: table-cell;"><div class="captcha_refresh" id="_element_refreshform_id_temp" onclick="captcha_refresh(&quot;_wd_captcha&quot;,&quot;form_id_temp&quot;)" ' . $param['attributes'] . '></div></div></div><div style="display: table-row;"><div style="display: table-cell;"><input type="text" class="captcha_input" id="_wd_captcha_inputform_id_temp" name="captcha_input" style="width: ' . ($param['w_digit'] * 10 + 15) . 'px;" ' . $param['attributes'] . ' disabled/><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $param['w_count'] = $param['w_count'] ? $param['w_count'] : 1;
            $param['w_operations'] = $param['w_operations'] ? $param['w_operations'] : '+, -, *, /';
            $param['w_input_size'] = $param['w_input_size'] ? $param['w_input_size'] : 60;
            $rep = '<div id="wdform_field' . $id . '" type="type_arithmetic_captcha" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display:' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_captcha" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><div style="display: table;"><div style="display: table-row;"><div style="display: table-cell;"><img type="captcha" operations_count="' . $param['w_count'] . '" operations="' . $param['w_operations'] . '" input_size="' . $param['w_input_size'] . '" src="' . add_query_arg(array(
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'action' => 'formmakerwdmathcaptcha' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'operations_count' => $param['w_count'],
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'operations' => urlencode($param['w_operations']),
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'i' => 'form_id_temp',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          ), admin_url('admin-ajax.php')) . '" id="_wd_arithmetic_captchaform_id_temp" class="arithmetic_captcha_img" onclick="captcha_refresh(&quot;_wd_arithmetic_captcha&quot;,&quot;form_id_temp&quot;)" ' . $param['attributes'] . '></div><div style="display: table-cell;"><input type="text" class="arithmetic_captcha_input" id="_wd_arithmetic_captcha_inputform_id_temp" name="arithmetic_captcha_input" onkeypress="return check_isnum(event)" style="width: ' . $param['w_input_size'] . 'px;" ' . $param['attributes'] . ' disabled/><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/></div><div style="display: table-cell; vertical-align: middle;"><div class="captcha_refresh" id="_element_refreshform_id_temp" onclick="captcha_refresh(&quot;_wd_arithmetic_captcha&quot;,&quot;form_id_temp&quot;)" ' . $param['attributes'] . '></div></div></div></div></div></div>';
            break;
          }
          case 'type_recaptcha': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_public',
              'w_private',
              'w_theme',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_public',
                'w_private',
                'w_theme',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_type') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_type',
                'w_position',
              );
            }
            $param['w_type'] = 'v2';
            $param['w_position'] = 'hidden';
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $rep = '<div id="wdform_field' . $id . '" type="type_recaptcha" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_recaptcha" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><div id="wd_recaptchaform_id_temp" w_type="' . $param['w_type'] . '" position="' . $param['w_position'] . '"><span style="color: red; font-style: italic;">' . __('No preview available for reCAPTCHA.', WDFMInstance(self::PLUGIN)->prefix) . '</span></div></div></div>';
            break;
          }
          case 'type_hidden': {
            $params_names = array( 'w_name', 'w_value' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_name'] = str_replace('&nbsp;', '', $param['w_name']);
            $rep = '<div id="wdform_field' . $id . '" type="type_hidden" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" style="display: table-cell;"><span id="' . $id . '_element_labelform_id_temp" style="display: none;">' . $param['w_name'] . '</span><span style="color: red; font-size: 13px;">Hidden field</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" style="display: table-cell; padding-left:7px;"><input type="hidden" value="' . $param['w_value'] . '" id="' . $id . '_elementform_id_temp" name="' . $param['w_name'] . '" ' . $param['attributes'] . '><input type="hidden" value="type_hidden" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><div><span align="left">Name: </span><span align="left" id="' . $id . '_hidden_nameform_id_temp">' . $param['w_name'] . '</span></div><div><span align="left">Value: </span><span align="left" id="' . $id . '_hidden_valueform_id_temp">' . $param['w_value'] . '</span></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $rep = '<div id="wdform_field' . $id . '" type="type_mark_map" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="type_mark_map" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><div id="' . $id . '_elementform_id_temp" long0="' . $param['w_long'] . '" lat0="' . $param['w_lat'] . '" zoom="' . $param['w_zoom'] . '" info0="' . $param['w_info'] . '" center_x="' . $param['w_center_x'] . '" center_y="' . $param['w_center_y'] . '" style="width: ' . $param['w_width'] . 'px; height: ' . $param['w_height'] . 'px;" ' . $param['attributes'] . '></div></div></div>	';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $marker = '';
            $param['w_long'] = explode('***', $param['w_long']);
            $param['w_lat'] = explode('***', $param['w_lat']);
            $param['w_info'] = explode('***', $param['w_info']);
            foreach ( $param['w_long'] as $key => $w_long ) {
              $marker .= 'long' . $key . '="' . $w_long . '" lat' . $key . '="' . $param['w_lat'][$key] . '" info' . $key . '="' . $param['w_info'][$key] . '"';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_map" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: table-cell;"><span id="' . $id . '_element_labelform_id_temp" style="display: none;">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: table-cell;"><input type="hidden" value="type_map" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><div id="' . $id . '_elementform_id_temp" zoom="' . $param['w_zoom'] . '" center_x="' . $param['w_center_x'] . '" center_y="' . $param['w_center_y'] . '" style="width: ' . $param['w_width'] . 'px; height: ' . $param['w_height'] . 'px;" ' . $marker . ' ' . $param['attributes'] . '></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $hide_cents = ($param['w_hide_cents'] == "yes" ? "none;" : "table-cell;");
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_price" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required"style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_paypal_price" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_range_min'] . '" name="' . $id . '_range_minform_id_temp" id="' . $id . '_range_minform_id_temp"><input type="hidden" value="' . $param['w_range_max'] . '" name="' . $id . '_range_maxform_id_temp" id="' . $id . '_range_maxform_id_temp"><div id="' . $id . '_table_price" style="display: table;"><div id="' . $id . '_tr_price1" style="display: table-row;"><div id="' . $id . '_td_name_currency" style="display: table-cell;"><span class="wdform_colon" style="vertical-align: middle;"><!--repstart-->&nbsp;$&nbsp;<!--repend--></span></div><div id="' . $id . '_td_name_dollars" style="display: table-cell;"><input type="text" id="' . $id . '_element_dollarsform_id_temp" name="' . $id . '_element_dollarsform_id_temp" value="' . htmlentities($w_first_val[0], ENT_COMPAT) . '" title="' . htmlentities($w_title[0], ENT_COMPAT) . '" onkeypress="return check_isnum(event)" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled/></div><div id="' . $id . '_td_name_divider" style="display: ' . $hide_cents . ';"><span class="wdform_colon" style="vertical-align: middle;">&nbsp;.&nbsp;</span></div><div id="' . $id . '_td_name_cents" style="display: ' . $hide_cents . '"><input type="text" id="' . $id . '_element_centsform_id_temp" name="' . $id . '_element_centsform_id_temp" value="' . htmlentities($w_first_val[1], ENT_COMPAT) . '" title="' . htmlentities($w_title[1], ENT_COMPAT) . '" onblur="add_0(&quot;' . $id . '_element_centsform_id_temp&quot;)" onkeypress="return check_isnum_interval(event,&quot;' . $id . '_element_centsform_id_temp&quot;,0,99)"style="width: 30px;" ' . $param['attributes'] . ' disabled/></div></div><div id="' . $id . '_tr_price2" style="display: table-row;"><div style="display: table-cell;"><label class="mini_label"></label></div><div align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_dollars">' . $w_mini_labels[0] . '</label></div><div id="' . $id . '_td_name_label_divider" style="display: ' . $hide_cents . '"><label class="mini_label"></label></div><div align="left" id="' . $id . '_td_name_label_cents" style="display: ' . $hide_cents . '"><label class="mini_label" id="' . $id . '_mini_label_cents">' . $w_mini_labels[1] . '</label></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $currency_sumbol = ($param['w_currency'] == "yes" ? "display:none;" : "display: table-cell;");
            $param['w_readonly'] = (isset($param['w_readonly']) ? $param['w_readonly'] : "no");
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_price_new" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required"style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_paypal_price_new" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_readonly'] . '" name="' . $id . '_readonlyform_id_temp" id="' . $id . '_readonlyform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_range_min'] . '" name="' . $id . '_range_minform_id_temp" id="' . $id . '_range_minform_id_temp"><input type="hidden" value="' . $param['w_range_max'] . '" name="' . $id . '_range_maxform_id_temp" id="' . $id . '_range_maxform_id_temp"><div id="' . $id . '_table_price" style="display: table;"><div id="' . $id . '_tr_price1" style="display: table-row;"><div id="' . $id . '_td_name_currency" style="' . $currency_sumbol . '"><span class="wdform_colon" style="vertical-align: middle;"><!--repstart-->&nbsp;$&nbsp;<!--repend--></span></div><div id="' . $id . '_td_name_dollars" style="display: table-cell;"><input type="text" id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" value="' . htmlentities($param['w_first_val'], ENT_COMPAT) . '" title="' . htmlentities($param['w_title'], ENT_COMPAT) . '" placeholder="' . htmlentities($param['w_title'], ENT_COMPAT) . '" onkeypress="return check_isnum(event)" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' disabled/></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_price'] = explode('***', $param['w_choices_price']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            $param['w_choices_disabled'] = explode('***', $param['w_choices_disabled']);
            $param['w_property'] = explode('***', $param['w_property']);
            $param['w_property_values'] = explode('***', $param['w_property_values']);
            if ( isset($param['w_choices_params']) ) {
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'selected="selected"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_select" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; "><input type="hidden" value="type_paypal_select" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><select id="' . $id . '_elementform_id_temp" name="' . $id . '_elementform_id_temp" onchange="set_select(this)" style="width: ' . $param['w_size'] . 'px;"  ' . $param['attributes'] . ' disabled>';
            foreach ( $param['w_choices'] as $key => $choice ) {
              $where = '';
              $order_by = '';
              $db_info = '';
              $choice_value = $param['w_choices_disabled'][$key] == "true" ? '' : $param['w_choices_price'][$key];
              if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                $where = 'where="' . $w_choices_params[0] . '"';
                $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                $order_by = "order_by='" . $w_choices_params[0] . "'";
                $db_info = "db_info='" . $w_choices_params[1] . "'";
              }
              $rep .= '<option id="' . $id . '_option' . $key . '" value="' . $choice_value . '" onselect="set_select(&quot;' . $id . '_option' . $key . '&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</option>';
            }
            $rep .= '</select><div id="' . $id . '_divform_id_temp">';
            if ( $param['w_quantity'] == "yes" ) {
              $rep .= '<span id="' . $id . '_element_quantity_spanform_id_temp" style="margin-right: 15px;"><label class="mini_label" id="' . $id . '_element_quantity_label_form_id_temp" style="margin-right: 5px;"><!--repstart-->Quantity<!--repend--></label><input type="text" value="' . $param['w_quantity_value'] . '" id="' . $id . '_element_quantityform_id_temp" name="' . $id . '_element_quantityform_id_temp" onkeypress="return check_isnum(event)" style="width: 30px; margin: 2px 0px;" disabled /></span>';
            }
            if ( $param['w_property'][0] ) {
              foreach ( $param['w_property'] as $key => $property ) {
                $rep .= '
        <span id="' . $id . '_property_' . $key . '" style="margin-right: 15px;">
        
        <label class="mini_label" id="' . $id . '_property_label_form_id_temp' . $key . '" style="margin-right: 5px;">' . $property . '</label>
        <select id="' . $id . '_propertyform_id_temp' . $key . '" name="' . $id . '_propertyform_id_temp' . $key . '" style="width: auto; margin: 2px 0px;" disabled>';
                $param['w_property_values'][$key] = explode('###', $param['w_property_values'][$key]);
                $param['w_property_values'][$key] = array_slice($param['w_property_values'][$key], 1, count($param['w_property_values'][$key]));
                foreach ( $param['w_property_values'][$key] as $subkey => $property_value ) {
                  $rep .= '<option id="' . $id . '_' . $key . '_option' . $subkey . '" value="' . $property_value . '">' . $property_value . '</option>';
                }
                $rep .= '</select></span>';
              }
            }
            $rep .= '</div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_price'] = explode('***', $param['w_choices_price']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            $param['w_property'] = explode('***', $param['w_property']);
            $param['w_property_values'] = explode('***', $param['w_property_values']);
            if ( isset($param['w_choices_params']) ) {
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'checked="checked"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_checkbox" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_paypal_checkbox" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_randomize'] . '" name="' . $id . '_randomizeform_id_temp" id="' . $id . '_randomizeform_id_temp"><input type="hidden" value="' . $param['w_allow_other'] . '" name="' . $id . '_allow_otherform_id_temp" id="' . $id . '_allow_otherform_id_temp"><input type="hidden" value="' . $param['w_allow_other_num'] . '" name="' . $id . '_allow_other_numform_id_temp" id="' . $id . '_allow_other_numform_id_temp"><input type="hidden" value="' . $param['w_field_option_pos'] . '" id="' . $id . '_option_left_right"><div style="display: table;"><div id="' . $id . '_table_little" style="display: table-row-group;">';
            if ( $param['w_flow'] == 'hor' ) {
              $rep .= '<div id="' . $id . '_hor" style="display: table-row;">';
              foreach ( $param['w_choices'] as $key => $choice ) {
                $where = '';
                $order_by = '';
                $db_info = '';
                if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                  $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                  $where = 'where="' . $w_choices_params[0] . '"';
                  $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                  $order_by = "order_by='" . $w_choices_params[0] . "'";
                  $db_info = "db_info='" . $w_choices_params[1] . "'";
                }
                $rep .= '<div valign="top" id="' . $id . '_td_little' . $key . '" idi="' . $key . '" style="display: table-cell;"><input type="checkbox" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp' . $key . '" value="' . $param['w_choices_price'][$key] . '" onclick="set_checked(&quot;' . $id . '&quot;,&quot;' . $key . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $key . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $key . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</label></div>';
              }
              $rep .= '</div>';
            }
            else {
              foreach ( $param['w_choices'] as $key => $choice ) {
                $where = '';
                $order_by = '';
                $db_info = '';
                if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                  $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                  $where = 'where="' . $w_choices_params[0] . '"';
                  $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                  $order_by = "order_by='" . $w_choices_params[0] . "'";
                  $db_info = "db_info='" . $w_choices_params[1] . "'";
                }
                $rep .= '<div id="' . $id . '_element_tr' . $key . '" style="display: table-row;"><div valign="top" id="' . $id . '_td_little' . $key . '" idi="' . $key . '" style="display: table-cell;"><input type="checkbox" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp' . $key . '" value="' . $param['w_choices_price'][$key] . '" onclick="set_checked(&quot;' . $id . '&quot;,&quot;' . $key . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $key . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $key . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</label></div></div>';
              }
            }
            $rep .= '</div></div>';
            $rep .= '<div id="' . $id . '_divform_id_temp">';
            if ( $param['w_quantity'] == "yes" ) {
              $rep .= '<span id="' . $id . '_element_quantity_spanform_id_temp" style="margin-right: 15px;"><label class="mini_label" id="' . $id . '_element_quantity_label_form_id_temp" style="margin-right: 5px;"><!--repstart-->Quantity<!--repend--></label><input type="text" value="' . $param['w_quantity_value'] . '" id="' . $id . '_element_quantityform_id_temp" name="' . $id . '_element_quantityform_id_temp" onkeypress="return check_isnum(event)" style="width: 30px; margin: 2px 0px;" disabled/></span>';
            }
            if ( $param['w_property'][0] ) {
              foreach ( $param['w_property'] as $key => $property ) {
                $rep .= '
        <span id="' . $id . '_property_' . $key . '" style="margin-right: 15px;">
        
        <label class="mini_label" id="' . $id . '_property_label_form_id_temp' . $key . '" style="margin-right: 5px;">' . $property . '</label>
        <select id="' . $id . '_propertyform_id_temp' . $key . '" name="' . $id . '_propertyform_id_temp' . $key . '" style="width: auto; margin: 2px 0px;" disabled>';
                $param['w_property_values'][$key] = explode('###', $param['w_property_values'][$key]);
                $param['w_property_values'][$key] = array_slice($param['w_property_values'][$key], 1, count($param['w_property_values'][$key]));
                foreach ( $param['w_property_values'][$key] as $subkey => $property_value ) {
                  $rep .= '<option id="' . $id . '_' . $key . '_option' . $subkey . '" value="' . $property_value . '">' . $property_value . '</option>';
                }
                $rep .= '</select></span>';
              }
            }
            $rep .= '</div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_price'] = explode('***', $param['w_choices_price']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            $param['w_property'] = explode('***', $param['w_property']);
            $param['w_property_values'] = explode('***', $param['w_property_values']);
            if ( isset($param['w_choices_params']) ) {
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'checked="checked"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_radio" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_paypal_radio" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_randomize'] . '" name="' . $id . '_randomizeform_id_temp" id="' . $id . '_randomizeform_id_temp"><input type="hidden" value="' . $param['w_allow_other'] . '" name="' . $id . '_allow_otherform_id_temp" id="' . $id . '_allow_otherform_id_temp"><input type="hidden" value="' . $param['w_allow_other_num'] . '" name="' . $id . '_allow_other_numform_id_temp" id="' . $id . '_allow_other_numform_id_temp"><input type="hidden" value="' . $param['w_field_option_pos'] . '" id="' . $id . '_option_left_right"><div style="display: table;"><div id="' . $id . '_table_little" style="display: table-row-group;">';
            if ( $param['w_flow'] == 'hor' ) {
              $rep .= '<div id="' . $id . '_hor" style="display: table-row;">';
              foreach ( $param['w_choices'] as $key => $choice ) {
                $where = '';
                $order_by = '';
                $db_info = '';
                if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                  $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                  $where = 'where="' . $w_choices_params[0] . '"';
                  $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                  $order_by = "order_by='" . $w_choices_params[0] . "'";
                  $db_info = "db_info='" . $w_choices_params[1] . "'";
                }
                $rep .= '<div valign="top" id="' . $id . '_td_little' . $key . '" idi="' . $key . '" style="display: table-cell;"><input type="radio" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp" value="' . $param['w_choices_price'][$key] . '" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $key . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $key . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $key . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</label></div>';
              }
              $rep .= '</div>';
            }
            else {
              foreach ( $param['w_choices'] as $key => $choice ) {
                $where = '';
                $order_by = '';
                $db_info = '';
                if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                  $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                  $where = 'where="' . $w_choices_params[0] . '"';
                  $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                  $order_by = "order_by='" . $w_choices_params[0] . "'";
                  $db_info = "db_info='" . $w_choices_params[1] . "'";
                }
                $rep .= '<div id="' . $id . '_element_tr' . $key . '" style="display: table-row;"><div valign="top" id="' . $id . '_td_little' . $key . '" idi="' . $key . '" style="display: table-cell;"><input type="radio" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp" value="' . $param['w_choices_price'][$key] . '" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $key . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $key . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $key . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</label></div></div>';
              }
            }
            $rep .= '</div></div>';
            $rep .= '<div id="' . $id . '_divform_id_temp">';
            if ( $param['w_quantity'] == "yes" ) {
              $rep .= '<span id="' . $id . '_element_quantity_spanform_id_temp" style="margin-right: 15px;"><label class="mini_label" id="' . $id . '_element_quantity_label_form_id_temp" style="margin-right: 5px;"><!--repstart-->Quantity<!--repend--></label><input type="text" value="' . $param['w_quantity_value'] . '" id="' . $id . '_element_quantityform_id_temp" name="' . $id . '_element_quantityform_id_temp" onkeypress="return check_isnum(event)" style="width: 30px; margin: 2px 0px;" disabled/></span>';
            }
            if ( $param['w_property'][0] ) {
              foreach ( $param['w_property'] as $key => $property ) {
                $rep .= '
        <span id="' . $id . '_property_' . $key . '" style="margin-right: 15px;">
        
        <label class="mini_label" id="' . $id . '_property_label_form_id_temp' . $key . '" style="margin-right: 5px;">' . $property . '</label>
        <select id="' . $id . '_propertyform_id_temp' . $key . '" name="' . $id . '_propertyform_id_temp' . $key . '" style="width: auto; margin: 2px 0px;" disabled>';
                $param['w_property_values'][$key] = explode('###', $param['w_property_values'][$key]);
                $param['w_property_values'][$key] = array_slice($param['w_property_values'][$key], 1, count($param['w_property_values'][$key]));
                foreach ( $param['w_property_values'][$key] as $subkey => $property_value ) {
                  $rep .= '<option id="' . $id . '_' . $key . '_option' . $subkey . '" value="' . $property_value . '">' . $property_value . '</option>';
                }
                $rep .= '</select></span>';
              }
            }
            $rep .= '</div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_price'] = explode('***', $param['w_choices_price']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            if ( isset($param['w_choices_params']) ) {
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            foreach ( $param['w_choices_checked'] as $key => $choices_checked ) {
              if ( $choices_checked == 'true' ) {
                $param['w_choices_checked'][$key] = 'checked="checked"';
              }
              else {
                $param['w_choices_checked'][$key] = '';
              }
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_shipping" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '; vertical-align:top;"><input type="hidden" value="type_paypal_shipping" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_randomize'] . '" name="' . $id . '_randomizeform_id_temp" id="' . $id . '_randomizeform_id_temp"><input type="hidden" value="' . $param['w_allow_other'] . '" name="' . $id . '_allow_otherform_id_temp" id="' . $id . '_allow_otherform_id_temp"><input type="hidden" value="' . $param['w_allow_other_num'] . '" name="' . $id . '_allow_other_numform_id_temp" id="' . $id . '_allow_other_numform_id_temp"><input type="hidden" value="' . $param['w_field_option_pos'] . '" id="' . $id . '_option_left_right"><div style="display: table;"><div id="' . $id . '_table_little" style="display: table-row-group;">';
            if ( $param['w_flow'] == 'hor' ) {
              $rep .= '<div id="' . $id . '_hor" style="display: table-row;">';
              foreach ( $param['w_choices'] as $key => $choice ) {
                $where = '';
                $order_by = '';
                $db_info = '';
                if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                  $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                  $where = 'where="' . $w_choices_params[0] . '"';
                  $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                  $order_by = "order_by='" . $w_choices_params[0] . "'";
                  $db_info = "db_info='" . $w_choices_params[1] . "'";
                }
                $rep .= '<div valign="top" id="' . $id . '_td_little' . $key . '" idi="' . $key . '" style="display: table-cell;"><input type="radio" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp" value="' . $param['w_choices_price'][$key] . '" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $key . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $key . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $key . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</label></div>';
              }
              $rep .= '</div>';
            }
            else {
              foreach ( $param['w_choices'] as $key => $choice ) {
                $where = '';
                $order_by = '';
                $db_info = '';
                if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                  $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                  $where = 'where="' . $w_choices_params[0] . '"';
                  $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                  $order_by = "order_by='" . $w_choices_params[0] . "'";
                  $db_info = "db_info='" . $w_choices_params[1] . "'";
                }
                $rep .= '<div id="' . $id . '_element_tr' . $key . '" style="display: table-row;"><div valign="top" id="' . $id . '_td_little' . $key . '" idi="' . $key . '" style="display: table-cell;"><input type="radio" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp" value="' . $param['w_choices_price'][$key] . '" onclick="set_default(&quot;' . $id . '&quot;,&quot;' . $key . '&quot;,&quot;form_id_temp&quot;)" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' ' . ($param['w_field_option_pos'] == 'right' ? 'style="float:left !important;"' : "") . ' disabled/><label id="' . $id . '_label_element' . $key . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $key . '" ' . $where . ' ' . $order_by . ' ' . $db_info . '>' . $choice . '</label></div></div>';
              }
            }
            $rep .= '</div></div>';
            $rep .= '</div></div>';
            break;
          }
          case 'type_paypal_total': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_class' );
            $temp = $params;
            if ( strpos($temp, 'w_size') > -1 ) {
              $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_class', 'w_size' );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_hide_label', 'w_class', 'w_size' );
            }
            if ( strpos($temp, 'w_hide_total_currency') > -1 ) {
              $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_hide_label', 'w_class', 'w_size' , 'w_hide_total_currency' );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $param['w_hide_total_currency'] = (isset($param['w_hide_total_currency']) ? $param['w_hide_total_currency'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $display_total_currency = $param['w_hide_total_currency'] == "no" ? "wd-inline-block" : "wd-hidden";
            $param['w_size'] = isset($param['w_size']) ? $param['w_size'] : '300';
            $rep = '<div id="wdform_field' . $id . '" type="type_paypal_total" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_paypal_total" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_hide_total_currency'] . '" name="' . $id . '_hide_totalcurrency_id_temp" id="' . $id . '_hide_totalcurrency_id_temp"/><div id="' . $id . 'paypal_totalform_id_temp" class="wdform_paypal_total paypal_totalform_id_temp" style="width:' . $param['w_size'] . 'px;"><input type="hidden" value="" name="' . $id . '_paypal_totalform_id_temp" class="input_paypal_totalform_id_temp"><div id="' . $id . 'div_totalform_id_temp" class="div_totalform_id_temp" style="margin-bottom: 10px;"><span id="' . $id . 'toggle_currency" class="' . $display_total_currency . '">$</span>300</div><div id="' . $id . 'paypal_productsform_id_temp" class="paypal_productsform_id_temp" style="border-spacing: 2px;"><div style="border-spacing: 2px;"><!--repstart-->product 1 $100<!--repend--></div><div style="border-spacing: 2px;"><!--repstart-->product 2 $200<!--repend--></div></div><div id="' . $id . 'paypal_taxform_id_temp" class="paypal_taxform_id_temp" style="border-spacing: 2px; margin-top: 7px;"></div></div></div></div>';
            break;
          }
          case 'type_stripe': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_class',
            );
                  $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
             'w_field_label_size',
             'w_field_label_pos',
             'w_hide_label',
             'w_size',
             'w_class',
              );
            }
                  foreach ( $params_names as $params_name ) {
                    $temp = explode('*:*' . $params_name . '*:*', $temp);
                    $param[$params_name] = $temp[0];
              $temp = ( isset( $temp[1] ) ? $temp[1] : '' );
                  }
            $param['w_size'] = (isset($param['w_size']) ? $param['w_size'] : "");
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
		        $rep = '<div id="wdform_field' . $id . '" type="type_stripe" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . ';  width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><div id="' . $id . '_elementform_id_temp" style="width:' . $param['w_size'] . 'px; margin:10px; border: 1px solid #000; min-width:80px;text-align:center;"> Stripe Section</div><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" id="is_stripe" /><input type="hidden" value="type_stripe" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $images = '';
            for ( $i = 0; $i < $param['w_star_amount']; $i++ ) {
              $images .= '<img id="' . $id . '_star_' . $i . '" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" onmouseover="change_src(' . $i . ',' . $id . ',&quot;form_id_temp&quot;)" onmouseout="reset_src(' . $i . ',' . $id . ')" onclick="select_star_rating(' . $i . ',' . $id . ', &quot;form_id_temp&quot;)">';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_star_rating" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_star_rating" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_star_amount'] . '" id="' . $id . '_star_amountform_id_temp" name="' . $id . '_star_amountform_id_temp"><input type="hidden" value="' . $param['w_field_label_col'] . '" name="' . $id . '_star_colorform_id_temp" id="' . $id . '_star_colorform_id_temp"><div id="' . $id . '_elementform_id_temp" class="wdform_stars" ' . $param['attributes'] . '>' . $images . '</div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $numbers = '';
            for ( $i = 1; $i <= $param['w_scale_amount']; $i++ ) {
              $numbers .= '<div id="' . $id . '_scale_td1_' . $i . 'form_id_temp" style="text-align: center; display: table-cell;"><span>' . $i . '</span></div>';
            }
            $radio_buttons = '';
            for ( $k = 1; $k <= $param['w_scale_amount']; $k++ ) {
              $radio_buttons .= '<div id="' . $id . '_scale_td2_' . $k . 'form_id_temp" style="display: table-cell;"><input id="' . $id . '_scale_radioform_id_temp_' . $k . '" name="' . $id . '_scale_radioform_id_temp" value="' . $k . '" type="radio"></div>';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_scale_rating" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; vertical-align: top; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_scale_rating" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_scale_amount'] . '" id="' . $id . '_scale_amountform_id_temp" name="' . $id . '_scale_amountform_id_temp"><div id="' . $id . '_elementform_id_temp" style="float: left;" ' . $param['attributes'] . '><label class="mini_label" id="' . $id . '_mini_label_worst" style="position: relative; top: 6px; font-size: 11px; display: inline-table;">' . $w_mini_labels[0] . '</label><div id="' . $id . '_scale_tableform_id_temp" style="display: inline-table;"><div id="' . $id . '_scale_tr1form_id_temp" style="display: table-row;">' . $numbers . '</div><div id="' . $id . '_scale_tr2form_id_temp" style="display: table-row;">' . $radio_buttons . '</div></div><label class="mini_label" id="' . $id . '_mini_label_best" style="position: relative; top: 6px; font-size: 11px; display: inline-table;">' . $w_mini_labels[1] . '</label></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_spinner" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_spinner" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_field_width'] . '" name="' . $id . '_spinner_widthform_id_temp" id="' . $id . '_spinner_widthform_id_temp"><input type="hidden" value="' . $param['w_field_min_value'] . '" id="' . $id . '_min_valueform_id_temp" name="' . $id . '_min_valueform_id_temp"><input type="hidden" value="' . $param['w_field_max_value'] . '" name="' . $id . '_max_valueform_id_temp" id="' . $id . '_max_valueform_id_temp"><input type="hidden" value="' . $param['w_field_step'] . '" name="' . $id . '_stepform_id_temp" id="' . $id . '_stepform_id_temp"><input type="" value="' . ($param['w_field_value'] != 'null' ? $param['w_field_value'] : '') . '" name="' . $id . '_elementform_id_temp" id="' . $id . '_elementform_id_temp" onkeypress="return check_isnum_or_minus(event)" style="width: ' . $param['w_field_width'] . 'px;" ' . $param['attributes'] . ' disabled/></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $param['w_field_step'] = (isset($param['w_field_step']) ? $param['w_field_step'] : 1);
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_slider" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; vertical-align: top; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_slider" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_field_width'] . '" name="' . $id . '_slider_widthform_id_temp" id="' . $id . '_slider_widthform_id_temp"><input type="hidden" value="' . $param['w_field_min_value'] . '" id="' . $id . '_slider_min_valueform_id_temp" name="' . $id . '_slider_min_valueform_id_temp"><input type="hidden" value="' . $param['w_field_max_value'] . '" id="' . $id . '_slider_max_valueform_id_temp" name="' . $id . '_slider_max_valueform_id_temp"><input type="hidden" value="' . $param['w_field_step'] . '" id="' . $id . '_slider_stepform_id_temp" name="' . $id . '_slider_stepform_id_temp" /><input type="hidden" value="' . $param['w_field_value'] . '" id="' . $id . '_slider_valueform_id_temp" name="' . $id . '_slider_valueform_id_temp"><div id="' . $id . '_slider_tableform_id_temp"><div><div id="' . $id . '_slider_td1form_id_temp"><div name="' . $id . '_elementform_id_temp" id="' . $id . '_elementform_id_temp" style="width: ' . $param['w_field_width'] . 'px;" ' . $param['attributes'] . '"></div></div></div><div><div align="left" id="' . $id . '_slider_td2form_id_temp" style="display: inline-table; width: 33.3%; text-align: left;"><span id="' . $id . '_element_minform_id_temp" class="label">' . $param['w_field_min_value'] . '</span></div><div align="right" id="' . $id . '_slider_td3form_id_temp" style="display: inline-table; width: 33.3%; text-align: center;"><span id="' . $id . '_element_valueform_id_temp" class="label">' . $param['w_field_value'] . '</span></div><div align="right" id="' . $id . '_slider_td4form_id_temp" style="display: inline-table; width: 33.3%; text-align: right;"><span id="' . $id . '_element_maxform_id_temp" class="label">' . $param['w_field_max_value'] . '</span></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $rep = '<div id="wdform_field' . $id . '" type="type_range" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_range" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_field_range_width'] . '" name="' . $id . '_range_widthform_id_temp" id="' . $id . '_range_widthform_id_temp"><input type="hidden" value="' . $param['w_field_range_step'] . '" name="' . $id . '_range_stepform_id_temp" id="' . $id . '_range_stepform_id_temp"><div id="' . $id . '_elemet_table_littleform_id_temp" style="display: table;"><div style="display: table-row;"><div valign="middle" align="left" style="display: table-cell;"><input type="" value="' . ($param['w_field_value1'] != 'null' ? $param['w_field_value1'] : '') . '" name="' . $id . '_elementform_id_temp0" id="' . $id . '_elementform_id_temp0" onkeypress="return check_isnum_or_minus(event)" style="width: ' . $param['w_field_range_width'] . 'px;"  ' . $param['attributes'] . ' disabled/></div><div valign="middle" align="left" style="display: table-cell; padding-left: 4px;"><input type="" value="' . ($param['w_field_value2'] != 'null' ? $param['w_field_value2'] : '') . '" name="' . $id . '_elementform_id_temp1" id="' . $id . '_elementform_id_temp1" onkeypress="return check_isnum_or_minus(event)" style="width: ' . $param['w_field_range_width'] . 'px;" ' . $param['attributes'] . ' disabled/></div></div><div style="display: table-row;"><div valign="top" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_from">' . $w_mini_labels[0] . '</label></div><div valign="top" align="left" style="display: table-cell;"><label class="mini_label" id="' . $id . '_mini_label_to">' . $w_mini_labels[1] . '</label></div></div></div></div></div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $w_items = explode('***', $param['w_items']);
            $grading_items = '';
            for ( $i = 0; $i < count($w_items); $i++ ) {
              $grading_items .= '<div id="' . $id . '_element_div' . $i . '" class="grading"><input id="' . $id . '_elementform_id_temp_' . $i . '" name="' . $id . '_elementform_id_temp_' . $i . '" onkeypress="return check_isnum_or_minus(event)" value="" type="text" size="5" onkeyup="sum_grading_values(' . $id . ',&quot;form_id_temp&quot;)" onchange="sum_grading_values(' . $id . ',&quot;form_id_temp&quot;)" ' . $param['attributes'] . ' disabled/><label id="' . $id . '_label_elementform_id_temp' . $i . '" class="ch-rad-label">' . $w_items[$i] . '</label></div>';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_grading" class="wdform_field" style="display: table-cell;">'
                      . $arrows . '
                      <div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; vertical-align: top; width: ' . $param['w_field_label_size'] . 'px;">
                        <span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span>
                        <span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span>
                      </div>
                      <div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';">
                        <input type="hidden" value="type_grading" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp">
                        <input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp">
                        <input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/>
                        <input type="hidden" value="' . $param['w_total'] . '" name="' . $id . '_grading_totalform_id_temp" id="' . $id . '_grading_totalform_id_temp">
                        <div id="' . $id . '_elementform_id_temp">' . $grading_items . '
                          <div id="' . $id . '_element_total_divform_id_temp" class="grading_div" style="display: ' . (!$param['w_total'] ? 'none' : 'block') . ';">Total:<span id="' . $id . '_sum_elementform_id_temp" name="' . $id . '_sum_elementform_id_temp">0</span>/<span id="' . $id . '_total_elementform_id_temp" name="' . $id . '_total_elementform_id_temp">' . $param['w_total'] . '</span>
                            <span id="' . $id . '_text_elementform_id_temp" name="' . $id . '_text_elementform_id_temp"></span>
                          </div>
                        </div>
                      </div>
                    </div>';
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
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            $display_label = $param['w_hide_label'] == "no" ? $param['w_field_label_pos'] : "none";
            $required_sym = ($param['w_required'] == "yes" ? " *" : "");
            $param['w_textbox_size'] = isset($param['w_textbox_size']) ? $param['w_textbox_size'] : '100';
            $w_rows = explode('***', $param['w_rows']);
            $w_columns = explode('***', $param['w_columns']);
            $column_labels = '';
            for ( $i = 1; $i < count($w_columns); $i++ ) {
              $column_labels .= '<div id="' . $id . '_element_td0_' . $i . '" class="matrix_" style="display: table-cell;"><label id="' . $id . '_label_elementform_id_temp0_' . $i . '" name="' . $id . '_label_elementform_id_temp0_' . $i . '" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $i . '" value="' . $w_columns[$i] . '">' . $w_columns[$i] . '</label></div>';
            }
            $rows_columns = '';
            for ( $i = 1; $i < count($w_rows); $i++ ) {
              $rows_columns .= '<div id="' . $id . '_element_tr' . $i . '" style="display: table-row;"><div id="' . $id . '_element_td' . $i . '_0" class="matrix_" style="display: table-cell;"><label id="' . $id . '_label_elementform_id_temp' . $i . '_0" class="ch-rad-label" for="' . $id . '_elementform_id_temp' . $i . '" value="' . $w_rows[$i] . '">' . $w_rows[$i] . '</label></div>';
              for ( $k = 1; $k < count($w_columns); $k++ ) {
                if ( $param['w_field_input_type'] == 'radio' ) {
                  $rows_columns .= '<div id="' . $id . '_element_td' . $i . '_' . $k . '" style="text-align: center; display: table-cell;  padding: 5px 0 0 5px;"><input id="' . $id . '_input_elementform_id_temp' . $i . '_' . $k . '" align="center" size="14" type="radio" name="' . $id . '_input_elementform_id_temp' . $i . '" value="' . $i . '_' . $k . '" disabled/></div>';
                }
                else {
                  if ( $param['w_field_input_type'] == 'checkbox' ) {
                    $rows_columns .= '<div id="' . $id . '_element_td' . $i . '_' . $k . '" style="text-align: center; display: table-cell;  padding: 5px 0 0 5px;"><input id="' . $id . '_input_elementform_id_temp' . $i . '_' . $k . '" align="center" size="14" type="checkbox" name="' . $id . '_input_elementform_id_temp' . $i . '_' . $k . '" value="1" disabled/></div>';
                  }
                  else {
                    if ( $param['w_field_input_type'] == 'text' ) {
                      $rows_columns .= '<div id="' . $id . '_element_td' . $i . '_' . $k . '" style="text-align: center; display: table-cell; padding: 5px 0 0 5px;"><input id="' . $id . '_input_elementform_id_temp' . $i . '_' . $k . '" align="center" type="text" name="' . $id . '_input_elementform_id_temp' . $i . '_' . $k . '" value="" style="width:' . $param['w_textbox_size'] . 'px" disabled/></div>';
                    }
                    else {
                      if ( $param['w_field_input_type'] == 'select' ) {
                        $rows_columns .= '<div id="' . $id . '_element_td' . $i . '_' . $k . '" style="text-align: center; display: table-cell; padding: 5px 0 0 5px;"><select id="' . $id . '_select_yes_noform_id_temp' . $i . '_' . $k . '" name="' . $id . '_select_yes_noform_id_temp' . $i . '_' . $k . '" disabled><option value=""> </option><option value="yes">Yes</option><option value="no">No</option></select></div>';
                      }
                    }
                  }
                }
              }
              $rows_columns .= '</div>';
            }
            $rep = '<div id="wdform_field' . $id . '" type="type_matrix" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $display_label . '; width: ' . $param['w_field_label_size'] . 'px;"><span id="' . $id . '_element_labelform_id_temp" class="label">' . $label . '</span><span id="' . $id . '_required_elementform_id_temp" class="required">' . $required_sym . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . ';"><input type="hidden" value="type_matrix" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp"><input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp"/><input type="hidden" value="' . $param['w_field_input_type'] . '" name="' . $id . '_input_typeform_id_temp" id="' . $id . '_input_typeform_id_temp"><input type="hidden" value="' . $param['w_textbox_size'] . '" name="' . $id . '_textbox_sizeform_id_temp" id="' . $id . '_textbox_sizeform_id_temp"><div id="' . $id . '_elementform_id_temp" style="display: table;" ' . $param['attributes'] . '><div id="' . $id . '_table_little" style="display: table-row-group;"><div id="' . $id . '_element_tr0" style="display: table-row;"><div id="' . $id . '_element_td0_0" style="display: table-cell;"></div>' . $column_labels . '</div>' . $rows_columns . '</div></div></div></div>';
            break;
          }
          case 'type_submit_reset': {
            $params_names = array( 'w_submit_title', 'w_reset_title', 'w_class', 'w_act' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_act'] = ($param['w_act'] == "false" ? 'style="display: none;"' : "");
            $rep = '<div id="wdform_field' . $id . '" type="type_submit_reset" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: table-cell;"><span id="' . $id . '_element_labelform_id_temp" style="display: none;">type_submit_reset_' . $id . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: table-cell;"><input type="hidden" value="type_submit_reset" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp"><button type="button" class="button button-hero button-submit" id="' . $id . '_element_submitform_id_temp" value="' . $param['w_submit_title'] . '" disabled ' . $param['attributes'] . '>' . $param['w_submit_title'] . '</button><button type="button" class="button button-secondary button-hero button-reset" id="' . $id . '_element_resetform_id_temp" value="' . $param['w_reset_title'] . '" disabled ' . $param['w_act'] . ' ' . $param['attributes'] . '>' . $param['w_reset_title'] . '</button></div></div>';
            break;
          }
          case 'type_button': {
            $params_names = array( 'w_title', 'w_func', 'w_class' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_title'] = explode('***', $param['w_title']);
            $param['w_func'] = explode('***', $param['w_func']);
            $rep .= '<div id="wdform_field' . $id . '" type="type_button" class="wdform_field" style="display: table-cell;">' . $arrows . '<div align="left" id="' . $id . '_label_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: table-cell;"><span id="' . $id . '_element_labelform_id_temp" style="display: none;">button_' . $id . '</span></div><div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: table-cell;"><input type="hidden" value="type_button" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp">';
            foreach ( $param['w_title'] as $key => $title ) {
              $rep .= '<button type="button" class="button button-secondary button-large" id="' . $id . '_elementform_id_temp' . $key . '" name="' . $id . '_elementform_id_temp' . $key . '" value="' . htmlentities($title, ENT_COMPAT) . '" onclick="' . $param['w_func'][$key] . '" ' . $param['attributes'] . '>' . $title . '</button>';
            }
            $rep .= '</div></div>';
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
            $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
            $display_label = ($param['w_hide_label'] == 'no') ? $param['w_field_label_pos'] : 'none';
            $required_sym = ($param['w_required'] == 'yes') ? ' *' : '';
            $rep = '<div id="wdform_field' . $id . '" type="type_signature" class="wdform_field" style="display: table-cell;">' . $arrows .
                      '<div align="left" id="' . $id . '_label_sectionform_id_temp"  class="' . $param['w_class'] . '" style="display: ' . $display_label. '; vertical-align: top; width: ' . $param['w_field_label_size'] . 'px;">
                          <span id="' . $id . '_element_labelform_id_temp" class="label" style="vertical-align: top;">' . $label . '</span>
                          <span id="' . $id . '_required_elementform_id_temp" class="required" style="vertical-align: top;">' . $required_sym . '</span>
                      </div>
                      <div align="left" id="' . $id . '_element_sectionform_id_temp" class="' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos'] . '">
                        <canvas id="' . $id . '_canvasform_id_temp" class="fm-signature" style="width: ' . $param['w_canvas_width'] . 'px; height: ' . $param['w_canvas_height'] . 'px; border: 1px solid;" width="' . $param['w_canvas_width'] . '" height="' . $param['w_canvas_height'] . '"></canvas>
                        <input type="hidden" value="type_signature" name="' . $id . '_typeform_id_temp" id="' . $id . '_typeform_id_temp">
                        <input type="hidden" value="' . $param['w_field_label_pos'] . '" name="' . $id . '_option_left_right" id="' . $id . '_option_left_right">                        
                        <input type="hidden" value="' . $param['w_hide_label'] . '" name="' . $id . '_hide_labelform_id_temp" id="' . $id . '_hide_labelform_id_temp">
                        <input type="hidden" value="' . $param['w_required'] . '" name="' . $id . '_requiredform_id_temp" id="' . $id . '_requiredform_id_temp">
                        <input type="hidden" value="' . $param['w_canvas_width'] . '" name="' . $id . '_canvas_widthform_id_temp" id="' . $id . '_canvas_widthform_id_temp">
                        <input type="hidden" value="' . $param['w_canvas_height'] . '" name="' . $id . '_canvas_heightform_id_temp" id="' . $id . '_canvas_heightform_id_temp">
                        <input type="hidden" value="' . $param['w_destination'] . '" name="' . $id . '_destination" id="' . $id . '_destination">
                      </div>
                    </div>';
            break;
          }
        }
        $form = str_replace('%' . $id . ' - ' . $labels[$ids_key] . '%', $rep, $form);
        $form = str_replace('%' . $id . ' -' . $labels[$ids_key] . '%', $rep, $form);
        $row->form_front = $form;
      }
    }

    return $row;
  }

  /**
   * Get theme rows data.
   *
   * @param string $old
   * @return mixed
   */
  public function get_theme_rows_data( $old = '' ) {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "formmaker_themes ORDER BY `default` DESC, `version` DESC, `id` ASC");

    return $rows;
  }

  /**
   * Get queries rows data.
   *
   * @param int $id
   * @return mixed
   */
  public function get_queries_rows_data( $id = 0 ) {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "formmaker_query WHERE form_id=" . $id . " ORDER BY id ASC");

    return $rows;
  }

  /**
   * Get labels.
   *
   * @param $id
   * @return mixed
   */
  public function get_labels( $id = 0 ) {
    global $wpdb;
    $rows = $wpdb->get_col("SELECT DISTINCT `element_label` FROM " . $wpdb->prefix . "formmaker_submits WHERE form_id=" . $id);

    return $rows;
  }

  /**
   * Is paypal.
   *
   * @param int $id
   * @return mixed
   */
  public function is_paypal( $id = 0 ) {
    global $wpdb;
    $rows = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "formmaker_sessions WHERE form_id=" . $id);

    return $rows;
  }

  /**
   * Return total count of forms.
   *
   * @return null|string
   */
  public function total() {
    global $wpdb;
    $query = "SELECT COUNT(*) FROM `" . $wpdb->prefix . "formmaker`";

    $search = WDW_FM_Library(self::PLUGIN)->get('s', '');

    $query .= (!WDFMInstance(self::PLUGIN)->is_free ? '' : 'WHERE id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option('contact_form_forms', '') != '' ? get_option('contact_form_forms') : 0) . ')');
    if ( $search ) {
      $query .= $wpdb->prepare((!WDFMInstance(self::PLUGIN)->is_free ? 'WHERE' : ' AND') . ' `title` LIKE %s', '%' . $search . '%');
    }

    $total = $wpdb->get_var($query);

    return $total;
  }

  /**
   * Get display options.
   *
   * @param int $id
   *
   * @return string
   */
  public function get_display_options( $id = 0 ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_display_options WHERE form_id=%d', $id));
    if ( !$row ) {
      $row = new stdClass();
      $row->form_id = $id;
      $display_options = array(
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
      $row->display_options = json_encode($display_options);
    }

    return WDW_FM_Library::convert_json_options_to_old($row, 'display_options');
  }

  public function fm_posts_query() {
    $default_post_types = array( 'post', 'page' );
    $query = array(
      'post_type' => $default_post_types,
      'suppress_filters' => TRUE,
      'update_post_term_cache' => FALSE,
      'update_post_meta_cache' => FALSE,
      'post_status' => 'publish',
      'posts_per_page' => -1,
    );
    $get_posts = new WP_Query;
    $posts = $get_posts->query($query);
    if ( !$get_posts->post_count ) {
      return FALSE;
    }
    $results = array();
    foreach ( $posts as $post ) {
		$post_id = (int) $post->ID;
		$post_type	= $post->post_type;
		$post_title	= trim(esc_html(strip_tags(get_the_title($post))));
		$results[$post_id] = array(
			'title' => $post_title,
			'post_type' => $post->post_type,
		);
    }

    wp_reset_postdata();
    return $results;
  }

  public function fm_categories_query() {
    $categories = get_categories(array(
                                   'hide_empty' => 0,
                                 ));
    $final_categories = array();
    foreach ( $categories as $key => $value ) {
      $final_categories[$value->term_id] = $value->name;
    }

    return $final_categories;
  }

  /**
   * Get all revisions from formmaker_backup.
   *
   * @param int $id
   *
   * @return array
   */
  public function get_revisions( $id ) {
    global $wpdb;
    $result = array();
    $result['total'] = 0;
    $query = "SELECT backup_id, cur, date FROM " . $wpdb->prefix . "formmaker_backup WHERE id = $id ORDER BY backup_id DESC";
    $result['data'] = $wpdb->get_results($query);
    if($result['data']) {
      $result['total'] = $wpdb->num_rows;
    }
    return $result;
  }

  /**
   * Get current form data from backup.
   *
   * @return array
   */
  public function get_current_revision( $id ) {
    global $wpdb;
    $query = "SELECT backup_id, cur, date FROM " . $wpdb->prefix . "formmaker_backup WHERE cur = 1 && id =" . $id;
    $result = $wpdb->get_row($query);
    return $result;
  }

  /**
   * Get revision date.
   *
   * @param int $backup_id
   *
   * @return int
   */
  public function get_revision_date( $backup_id = 0 ) {
    global $wpdb;
    $query = "SELECT date FROM " . $wpdb->prefix . "formmaker_backup WHERE backup_id = $backup_id";
    return $wpdb->get_var($query);
  }

  /**
   * Get max row.
   *
   * @param string $table
   * @param string $column
   *
   * @return int
   */
  public function get_max_row( $table = '', $column = '' ) {
    global $wpdb;
    $query = "SELECT max(" . $column . ") FROM " . $wpdb->prefix . $table;

    return $wpdb->get_var($query);
  }

  /**
   * Delete row.
   *
   * @param int $id
   *
   * @return bool
   */
  public function delete_formmaker_query( $id = 0 ) {
    global $wpdb;

    return $wpdb->query($wpdb->prepare('DELETE FROM `' . $wpdb->prefix . 'formmaker_query` WHERE id =%d', $id));
  }

  /**
   * Get mail verification post_id.
   *
   * @param int $form_id
   *
   * @return int|WP_Error
   */
  public function get_mail_verification_post_id( $form_id = 0 ) {
    $email_verification_key = (WDFMInstance(self::PLUGIN)->is_free == 2) ? 'cfmemailverification' : 'fmemailverification';
    $post_name_key = 'email-verification-' . $form_id;
    $email_verification_args = array(
      'name' => $post_name_key,
      'post_type' => $email_verification_key,
      'post_status' => 'publish',
      'post_author' => 1,
    );
    $my_posts = get_posts($email_verification_args);
    if ( empty($my_posts) ) {
      $email_verification_args['post_name'] = $post_name_key;
      $email_verification_args['post_title'] = 'Email Verification';
      $email_verification_args['post_content'] = '[email_verification]';
      $mail_verification_post_id = wp_insert_post($email_verification_args);
      if ( !is_wp_error($mail_verification_post_id) ) {
        flush_rewrite_rules();
      }
    }
    else {
      $mail_verification_post_id = $my_posts[0]->ID;
    }

    return $mail_verification_post_id;
  }

  /**
   * Update row(s) in db.
   *
   * @param string $table
   * @param array  $data_params
   * @param array  $where_data_params
   * params = [where]
   *
   * @return bool
   */
  public function update_data( $table = '', $data_params = array(), $where_data_params = array() ) {
    global $wpdb;
    return $wpdb->update($wpdb->prefix . $table, $data_params, $where_data_params);
  }

  /**
   * Get form option
   *
   * @param $table
   * @param $id
   *
   * @return mixed
   */
  public function get_form_options($table, $id) {
    global $wpdb;
    $query = "SELECT form_options FROM " . $wpdb->prefix . $table . " WHERE id = '" . $id . "'";
    return $wpdb->get_var($query);
  }

  /**
   * Add form options to db
   *
   * @param $json_data
   * @param $id form id
   *
   * @return bool
   */
  public function add_form_options( $json_data, $id ) {
    global $wpdb;
    $update = $wpdb->update($wpdb->prefix."formmaker", array("form_options" => $json_data), array("id" => $id));
    return $update;
  }

  /**
   * Get request value.
   *
   * @param string $table
   * @param array $data
   *
   * @return false|int
   */
  public function insert_data_to_db( $table = '', $data = array() ) {
    global $wpdb;
    $query = $wpdb->insert($wpdb->prefix . $table, $data);

    return $query;
  }

  /**
   * Get id of default theme.
   *
   * @return int (id)
   */
  public function get_default_theme_id() {
    global $wpdb;

    return $wpdb->get_var("SELECT id FROM " . $wpdb->prefix . "formmaker_themes WHERE `default`='1'");
  }

 /**
  * Get id of current theme
  * else return id of default theme
  *
  * @param int $id form_id
  *
  * @return int (id)
  */
    public function get_current_theme_id( $id ) {
      if ( $id == 0 ) {
        return $this->get_default_theme_id();
      }
      global $wpdb;

      return $wpdb->get_var($wpdb->prepare("SELECT theme FROM " . $wpdb->prefix . "formmaker WHERE `id`=%d", $id));
    }

  /**
   * Replace data.
   *
   * @param array $data
   *
   * @return bool
   */
  public function replace_display_options( $data = array() ) {
    global $wpdb;

    return $wpdb->replace($wpdb->prefix . 'formmaker_display_options', $data);
  }

  /**
   * Get previous backup_id.
   *
   * @param int $backup_id
   * @param int $id
   *
   * @return int
   */
  public function get_prev_backup_id( $backup_id = 0, $id = 0 ) {
    global $wpdb;
    $query = "SELECT backup_id FROM " . $wpdb->prefix . "formmaker_backup WHERE backup_id < " . $backup_id . " AND id = " . $id . " ORDER BY backup_id DESC LIMIT 0 , 1 ";

    return $wpdb->get_var($query);
  }

  /**
   * Get next backup_id.
   *
   * @param int $backup_id
   * @param int $id
   *
   * @return int
   */
  public function get_backup_id( $backup_id = 0, $id = 0 ) {
    global $wpdb;
    $query = "SELECT backup_id FROM " . $wpdb->prefix . "formmaker_backup WHERE backup_id > " . $backup_id . " AND id = " . $id . " ORDER BY backup_id ASC LIMIT 0 , 1 ";

    return $wpdb->get_var($query);
  }

  /**
   * @param int $backup_id
   * @param int $id
   */
  public function restore_backup( $backup_id = 0, $id = 0 ) {
    $this->update_data('formmaker_backup', array( 'cur' => 0 ), array( 'id' => $id ));
    $this->update_data('formmaker_backup', array( 'cur' => 1 ), array( 'backup_id' => $backup_id ));
  }

  public function get_autogen_layout( $id = 0 ) {
    global $wpdb;
    $form_options = WDW_FM_Library::get_form_options_json('formmaker', $id);
    $autogen_layout = isset($form_options['autogen_layout']) ? $form_options['autogen_layout'] : 1;
    return $autogen_layout == '1';
  }

  /**
   * Get count of rows from table.
   *
   * @param array $params
   * params = [selection, table, where]
   *
   * @return int
   */
  public function get_count( $params = array() ) {
    global $wpdb;
    $query = "SELECT count(" . $params['selection'] . ") FROM " . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $query .= " WHERE " . $params['where'];
    }

    return $wpdb->get_var($query);
  }

  /**
   * Delete row(s) from db.
   *
   * @param array $params
   * params = [selection, table, where, order_by, limit]
   *
   * @return array
   */
  public function delete_rows( $params = array() ) {
    global $wpdb;
    $query = "DELETE FROM " . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $query .= " WHERE " . $params['where'];
    }
    if ( isset($params['order_by']) ) {
      $query .= " " . $params['order_by'];
    }
    if ( isset($params['limit']) ) {
      $query .= " " . $params['limit'];
    }

    return $wpdb->query($query);
  }

  /**
   * Get row(s) from db.
   *
   * @param string $get_type
   * @param array $params
   * params = [selection, table, where, order_by, limit]
   *
   * @return array
   */
  public function select_rows( $get_type = '', $params = array() ) {
    global $wpdb;
    $query = "SELECT " . $params['selection'] . " FROM " . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $query .= " WHERE " . $params['where'];
    }
    if ( isset($params['order_by']) ) {
      $query .= " " . $params['order_by'];
    }
    if ( isset($params['limit']) ) {
      $query .= " " . $params['limit'];
    }
    if ( $get_type == "get_col" ) {
      return $wpdb->get_col($query);
    }
    elseif ( $get_type == "get_var" ) {
      return $wpdb->get_var($query);
    }
    elseif ( $get_type == "get_results" ) {
      return $wpdb->get_results($query);
    }

    return $wpdb->get_row($query);
  }

  /**
   * @param int $form_id
   */
  public function create_js( $form_id = 0) {
    WDW_FM_Library(self::PLUGIN)->create_js($form_id, true);
    $jsversion = rand();
    global $wpdb;
    $wpdb->update($wpdb->prefix . 'formmaker', array(
      'jsversion' => $jsversion,
    ), array( 'id' => $form_id ));
  }

  /**
   * Insert to DB.
   *
   * @param int $backup_id
   * @param int $id
   *
   * @return bool
   */
  public function insert_formmaker_backup( $backup_id = 0, $id = 0 ) {
    global $wpdb;
    $query = "INSERT INTO " . $wpdb->prefix . "formmaker_backup
    (backup_id, cur, id, title, `type`, form_front, theme, counter, published, label_order, label_order_current, pagination, show_title, show_numbers, public_key, private_key, recaptcha_theme, form_fields, sortable,  header_title, header_description, header_image_url, header_image_animation, header_hide_image, header_hide, privacy, date, form_options) 
    SELECT " . $backup_id . ", 1, formmakerbkup.id, formmakerbkup.title, formmakerbkup.type, formmakerbkup.form_front, formmakerbkup.theme, formmakerbkup.counter, formmakerbkup.published, formmakerbkup.label_order, formmakerbkup.label_order_current, formmakerbkup.pagination, formmakerbkup.show_title, formmakerbkup.show_numbers, formmakerbkup.public_key, formmakerbkup.private_key, formmakerbkup.recaptcha_theme, formmakerbkup.form_fields, formmakerbkup.sortable, formmakerbkup.header_title, formmakerbkup.header_description, formmakerbkup.header_image_url, formmakerbkup.header_image_animation, formmakerbkup.header_hide_image, formmakerbkup.header_hide, formmakerbkup.privacy, '" . current_time('timestamp') . "', formmakerbkup.form_options FROM " . $wpdb->prefix . "formmaker as formmakerbkup WHERE id=" . $id;
    return $wpdb->query($query);
  }

  /**
   * Create Preview Form post.
   *
   * @return string $guid
   */
  public function get_form_preview_post() {
    $post_type = 'form-maker' . WDFMInstance(self::PLUGIN)->plugin_postfix;
    $row = get_posts(array( 'post_type' => $post_type ));
    if ( !empty($row[0]) ) {
      return get_post_permalink($row[0]->ID);
    }
    else {
      $post_params = array(
        'post_author' => 1,
        'post_status' => 'publish',
        'post_content' => '[FormPreview' . WDFMInstance(self::PLUGIN)->plugin_postfix . ']',
        'post_title' => 'Preview',
        'post_type' => 'form-maker' . WDFMInstance(self::PLUGIN)->plugin_postfix,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_parent' => 0,
        'menu_order' => 0,
        'import_id' => 0,
      );
      // Create new post by fmformpreview type.
      $insert_id = wp_insert_post($post_params);
      if ( !is_wp_error($insert_id) ) {
        flush_rewrite_rules();

        return get_post_permalink($insert_id);
      }
      else {
        return "";
      }
    }
  }
}
