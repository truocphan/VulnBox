<?php

/**
 * Class FMViewForm_maker
 */
class FMViewForm_maker {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  private $model;

  /**
   * FMViewForm_maker constructor.
   *
   * @param $model
   */
  private $fm_nonce = null;

  public function __construct( $model = array() ) {
    $this->fm_nonce = wp_create_nonce('fm_ajax_nonce');
    $this->model = $model;
  }

  /**
   * Display.
   *
   * @param array  $result
   * @param array  $fm_settings
   * @param int    $form_id
   * @param string $formType
   *
   * @return string
   */
  public function display( $result = array(), $fm_settings = array(), $form_id = 0, $formType = 'embedded' ) {
    $fm_page_id = !empty($result['fm_page_id']) ? $result['fm_page_id'] : '';
    $fm_current_post_type = !empty($result['fm_current_post_type']) ? $result['fm_current_post_type'] : '';
    if ( $form_id ) {
      Cookie_fm::setCookieValueByKey($form_id, 'fm_empty_field_validation', md5('uniqid(rand(), TRUE)'));
    }
    if ( $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-icons', WDFMInstance(self::PLUGIN)->plugin_url . '/css/fonts.css', array(), '1.0.1');
    }

    if ( !WDW_FM_Library(self::PLUGIN)->elementor_is_active() ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend');
    }
    $current_user = wp_get_current_user();
    if ( $current_user->ID != 0 ) {
      $wp_username = $current_user->display_name;
      $wp_useremail = $current_user->user_email;
    }
    else {
      $wp_username = '';
      $wp_useremail = '';
    }
    $current_url = htmlentities($_SERVER['REQUEST_URI']);
    $row = $result[0];
    if ( !isset($row->header_hide) ) {
      $row->header_hide = 1;
    }
    $form_theme = $result[4];

    $theme_id = WDW_FM_Library(self::PLUGIN)->get('test_theme', $row->theme);
    if ( $theme_id == '' ) {
      $theme_id = $row->theme;
    }

    $header_pos = isset($form_theme['HPAlign']) && ($form_theme['HPAlign'] == 'left' || $form_theme['HPAlign'] == 'right') ? (($row->header_title || $row->header_description || $row->header_image_url) ? 'header_left_right' : 'no_header') : '';
    $pagination_align = $row->pagination == 'steps' && isset($form_theme['PSAPAlign']) ? 'fm-align-' . $form_theme['PSAPAlign'] : '';
    $form_currency = '$';
    if ( $row->payment_currency ) {
      $form_currency = WDW_FM_Library(self::PLUGIN)->replace_currency_code( $row->payment_currency );
    }
    $form_maker_front_end = '';
    $form_maker_front_end .= '<div id="fm-pages' . $form_id . '" class="fm-pages wdform_page_navigation ' . $pagination_align . '" show_title="' . $row->show_title . '" show_numbers="' . $row->show_numbers . '" type="' . $row->pagination . '"></div>';
    $form_maker_front_end .= '<form name="form' . $form_id . '" action="' . $current_url . '" method="post" id="form' . $form_id . '" class="fm-form form' . $form_id . ' ' . $header_pos . ' ' . ( Cookie_fm::getCookieByKey($form_id, 'form_submit_type') || Cookie_fm::getCookieByKey($form_id, 'massage_after_submit') ? 'fm-form-submitted' : '') . '" enctype="multipart/form-data">';
    // Form messages.
    $fm_hide_form_after_submit = 0;
    $limsub = 0;
    if ( Cookie_fm::getCookieByKey($form_id, 'form_submit_type') ) {
      $type_and_id = Cookie_fm::getCookieByKey($form_id, 'form_submit_type');
      $type_and_id = explode(',', $type_and_id);
      $form_get_type = $type_and_id[0];
      $form_get_id = isset($type_and_id[1]) ? $type_and_id[1] : '';
      $group_id = isset($type_and_id[2]) ? $type_and_id[2] : '';
      Cookie_fm::getCookieByKey($form_id, 'form_submit_type', true);
      if ( $form_get_type == 3 ) {
        Cookie_fm::getCookieByKey($form_id, 'massage_after_submit', true);
        $after_submission_text = $this->model->get_after_submission_text($form_get_id, $group_id);
        $form_maker_front_end .= WDW_FM_Library(self::PLUGIN)->message(wpautop(html_entity_decode($after_submission_text)), '', $form_id);
        $fm_hide_form_after_submit = 1;
      }
    }
    if ( Cookie_fm::getCookieByKey($form_id, 'redirect_paypal') == 1 ) {
      Cookie_fm::getCookieByKey($form_id, 'redirect_paypal', true);
      if ( isset($_GET['succes']) ) {
        if ( $_GET['succes'] == 0 ) {
          $form_maker_front_end .= WDW_FM_Library(self::PLUGIN)->message(__('Error, email was not sent.', WDFMInstance(self::PLUGIN)->prefix), 'fm-notice-error');
        }
        else {
          $form_maker_front_end .= WDW_FM_Library(self::PLUGIN)->message(__('Your form was successfully submitted.', WDFMInstance(self::PLUGIN)->prefix), 'fm-notice-success');
          $limsub = 1;
        }
      }
    }
    elseif ( Cookie_fm::getCookieByKey($form_id, 'massage_after_submit') != '' ) {
      $message = Cookie_fm::getCookieByKey($form_id, 'massage_after_submit');
      Cookie_fm::getCookieByKey($form_id, 'massage_after_submit', true);
      if ( Cookie_fm::getCookieByKey($form_id, 'error_or_no') ) {
        $error = 'fm-notice-error';
      }
      else {
        $error = 'fm-notice-success';
        $limsub = 1;
      }
      if ( !Cookie_fm::getCookieByKey($form_id, 'message_captcha') || $message != Cookie_fm::getCookieByKey($form_id, 'message_captcha') ) {
        if ( is_array($message) ) {
          foreach( $message as $msg ) {
            $form_maker_front_end .= WDW_FM_Library(self::PLUGIN)->message($msg, $error, $form_id);
          }
        }
        else {
          $form_maker_front_end .= WDW_FM_Library(self::PLUGIN)->message($message, $error, $form_id);
        }
      }
    }

    if ( Cookie_fm::getCookieByKey($form_id, 'massage_after_save') != '' ) {
      $save_message = Cookie_fm::getCookieByKey($form_id, 'massage_after_save', true);
      Cookie_fm::getCookieByKey($form_id, 'massage_after_submit', true);
      Cookie_fm::getCookieByKey($form_id, 'massage_after_save', true);
      $save_error = Cookie_fm::getCookieByKey($form_id, 'save_error');
      Cookie_fm::getCookieByKey($form_id, 'save_error', true);
      if ( $save_error == 2 ) {
        echo $save_message;
      }
      else {
        $save_error = ($save_error) ? 'fm-notice-error' : 'fm-notice-success';
        $form_maker_front_end .= WDW_FM_Library(self::PLUGIN)->message($save_message, $save_error, $form_id);
      }
    }
    if ( Cookie_fm::getCookieByKey($form_id, 'show_submit_text') == 1 ) {
      Cookie_fm::getCookieByKey($form_id, 'show_submit_text', true);
      $form_maker_front_end .= $row->submit_text;
    }
    if ( Cookie_fm::getCookieByKey($form_id, 'fm_hide_form_after_submit') == 1 ) {
      Cookie_fm::getCookieByKey($form_id, 'fm_hide_form_after_submit', true);
      $fm_hide_form_after_submit = 1;
    }
    $form_maker_front_end .= '<input type="hidden" id="fm_page_id' . $form_id . '" value="' . $fm_page_id . '" name="fm_page_id' . $form_id . '" />';
    $form_maker_front_end .= '<input type="hidden" id="fm_current_post_type' . $form_id . '" value="' . $fm_current_post_type . '" name="fm_current_post_type' . $form_id . '" />';
    $form_maker_front_end .= '<input type="hidden" id="counter' . $form_id . '" value="' . $row->counter . '" name="counter' . $form_id . '" />';
    $form_maker_front_end .= '<input type="hidden" id="Itemid' . $form_id . '" value="" name="Itemid' . $form_id . '" />';
    $form_maker_front_end .= '<input type="hidden" id="fm_shake' . $form_id . '" value="'. ((isset($form_theme['OPShake']) && $form_theme['OPShake'] == 'no') ? 0 : 1) .'" name="fm_shake' . $form_id . '" />';
    if ($fm_settings['fm_antispam_bot_validation']) {
      $form_maker_front_end .= '<input type="text" class="fm-hide" id="fm_bot_validation' . $form_id . '" value="" name="fm_bot_validation' . $form_id . '" />';
    }
    $form_maker_front_end .= '<input type="text" class="fm-hide" id="fm_empty_field_validation' . $form_id . '" value="" name="fm_empty_field_validation' . $form_id . '" data-value="'. Cookie_fm::getCookieByKey($form_id, 'fm_empty_field_validation') .'" />';
    if (isset($fm_settings['fm_ajax_submit']) && $fm_settings['fm_ajax_submit']) {
      $form_submit_url = add_query_arg( array(
        'action' => 'fm_submit_form',
        'current_id' => $form_id,
        'formType' =>  $formType ), admin_url('admin-ajax.php'));
      $form_maker_front_end .= '<input type="hidden" id="fm_ajax_url' . $form_id.'" data-ajax_url="'. $form_submit_url .'" />';
      if ( $row->submit_text_type == 1 || $row->submit_text_type == 3 ) {
        $action_after_sub = 0;
      }
      elseif( $row->submit_text_type == 4 && $row->url ) {
        $action_after_sub = $row->url;
      }
      else {
        $action_after_sub = $row->article_id;
      }
      $form_maker_front_end .= '<input type="hidden" id="fm_ajax_redirect_url' . $form_id.'" data-ajax_redirect_url="'. $action_after_sub .'" />';
    }
    if ($fm_settings['fm_antispam_nonce']) {
      $form_maker_front_end .= $this->get_nonce_field($form_id);
    }
    if ( !$fm_hide_form_after_submit ) {
      if( $row->header_hide ) {
        // Form header.
        $image_pos = isset($form_theme['HIPAlign']) && ($form_theme['HIPAlign'] == 'left' || $form_theme['HIPAlign'] == 'right') ? 'image_left_right' : '';
        $image_width = isset($form_theme['HIPWidth']) && $form_theme['HIPWidth'] ? 'width="' . $form_theme['HIPWidth'] . 'px"' : '';
        $image_height = isset($form_theme['HIPHeight']) && $form_theme['HIPHeight'] ? 'height="' . $form_theme['HIPHeight'] . 'px"' : '';
        $hide_header_image_class = wp_is_mobile() && $row->header_hide_image ? 'fm_hide_mobile' : '';
        $header_image_animation = $formType == 'embedded' ? $row->header_image_animation : '';
        if ( !isset($form_theme['HPAlign']) || ($form_theme['HPAlign'] == 'left' || $form_theme['HPAlign'] == 'top') ) {
          if ( $row->header_title || $row->header_description || $row->header_image_url ) {
            $form_maker_front_end .= '<div class="fm-header-bg"><div class="fm-header ' . $image_pos . '">';
            if ( !isset($form_theme['HIPAlign']) || $form_theme['HIPAlign'] == 'left' || $form_theme['HIPAlign'] == 'top' ) {
              if ( $row->header_image_url ) {
                $form_maker_front_end .= '<div class="fm-header-img ' . $hide_header_image_class . ' fm-animated ' . $header_image_animation . '"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
              }
            }
            if ( $row->header_title || $row->header_description ) {
              $form_maker_front_end .= '<div class="fm-header-text">
          <div class="fm-header-title">
            ' . $row->header_title . '
          </div>
          <div class="fm-header-description">
            ' . do_shortcode($row->header_description) . '
          </div>
        </div>';
            }
            if ( isset($form_theme['HIPAlign']) && ($form_theme['HIPAlign'] == 'right' || $form_theme['HIPAlign'] == 'bottom') ) {
              if ( $row->header_image_url ) {
                $form_maker_front_end .= '<div class="fm-header-img"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
              }
            }
            $form_maker_front_end .= '</div></div>';
          }
        }
      }
    }

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

    $symbol_begin = array();
    $symbol_end = array();

    // Get extension Calculator data.
    $calculator_data = array();
    if (WDFMInstance(self::PLUGIN)->is_free != 2) {
      $calculator_data = apply_filters('fm_calculator_get_data_init', $calculator_data, $form_id);
    }
    if ( !empty($calculator_data) ) {
      $symbol_end = json_decode($calculator_data->symbol_end, TRUE);
      $symbol_begin = json_decode($calculator_data->symbol_begin, TRUE);
    }

    if ( $row->autogen_layout == 0 ) {
      $form = $row->custom_front;
    }
    else {
      $form = $row->form_front;
    }

    $privacy = json_decode($row->privacy);
    $row->gdpr_checkbox = isset($privacy->gdpr_checkbox) ? $privacy->gdpr_checkbox : 0;
    $row->gdpr_checkbox_text = isset($privacy->gdpr_checkbox_text) ? $privacy->gdpr_checkbox_text : __('I consent collecting this data and processing it according to {{privacy_policy}} of this website.', WDFMInstance(self::PLUGIN)->prefix);

    // Remove unnecessary classes.
    $form = str_replace(array('ui-sortable-handle', 'ui-sortable-disabled', 'ui-sortable'), '', $form);
    foreach ( $id1s as $id1s_key => $id1 ) {
      $label = $labels[$id1s_key];
      $type = $types[$id1s_key];
      $params = $paramss[$id1s_key];
      if ( strpos($form, '%' . $id1 . ' - ' . $label . '%') || strpos($form, '%' . $id1 . ' -' . $label . '%') ) {
        $rep = '';
        $param = array();
        $param['label'] = $label;
        $param['attributes'] = '';
        $is_type[$type] = TRUE;
        switch ( $type ) {
          case 'type_section_break': {
            $params_names = array( 'w_editor' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $rep = '<div type="type_section_break" class="wdform-field-section-break"><div class="wdform_section_break">' . html_entity_decode($param['w_editor']) . '</div></div>';
            break;
          }
          case 'type_editor': {
            $params_names = array( 'w_editor' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $rep = '<div type="type_editor" class="wdform-field">' . html_entity_decode(do_shortcode($param['w_editor'])) . '</div>';
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
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $input_active = ($param['w_first_val'] == 'true' ? "checked='checked'" : "");
            if ( isset($_POST["counter" . $form_id]) ) {
              $input_active = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? "checked='checked'" : "");
            }
            $param['id'] = $id1;
            $param['w_class'] = ' checkbox-div wd-flex-row-reverse wd-align-items-center wd-justify-content-right';

            // Use label size as field size.
            $param['w_size'] = $param['w_field_label_size'];
            $html = '<input type="checkbox"
                             class="wd-flex-row"
                             id="wdform_' . $id1 . '_element' . $form_id . '"
                             name="wdform_' . $id1 . '_element' . $form_id . '"
                             ' . $input_active . '
                             ' . $param['attributes'] . ' />';
            $html .= '<label class="wd-align-items-center wd-flex wd-flex-row-reverse" for="wdform_' . $id1 . '_element' . $form_id . '" class="wdform-label"><span></span>' . $param['label'] . '</label>';
            if ( isset($param['w_required']) && $param['w_required'] == "yes" ) {
              $requiredmark = isset($row->requiredmark) ? $row->requiredmark : '';
              $html .= '<span class="wdform-required">' . $requiredmark . '</span>';
            }


            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html, FALSE);

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
              $param[$params_name] = esc_html($temp[0]);
		    			$temp = $temp[1];
            }

            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $param['w_first_val'], 'esc_html' );
            $param['w_regExp_status'] = (isset($param['w_regExp_status']) ? $param['w_regExp_status'] : "no");
            $readonly = (isset($param['w_readonly']) && $param['w_readonly'] == "yes" ? "readonly='readonly'" : '');
            $param['w_class'] = (isset($param['w_class']) ? $param['w_class'] : "");
            $param['id'] = $id1;
            $param['w_class'] .= ' wd-flex-row wd-align-items-center';

            $html = '';
            if ( isset($symbol_begin[$id1]) ) {
              $html .= '<span>' . $symbol_begin[$id1] . '</span>&nbsp;';
            }
            $html .= '<input type="text"
                           class="wd-width-100"
                           id="wdform_' . $id1 . '_element' . $form_id . '"
                           name="wdform_' . $id1 . '_element' . $form_id . '"
                           value="' . $param['w_first_val'] . '"
                           data-value="' . $param['w_first_val'] . '"
                           title="' . $param['w_title'] . '"
                           placeholder="' . $param['w_title'] . '"
                           ' . $readonly . '
                           ' . $param['attributes'] . ' />';
            if ( isset($symbol_end[$id1]) ) {
              $html .= '&nbsp;<span>' . $symbol_end[$id1] . '</span>';
            }

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

            break;
          }
          case 'type_number': { // To do: deprecated
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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $param['w_first_val'], 'esc_html' );
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? $param['w_field_label_size'] + $param['w_size'] + 10 : max($param['w_field_label_size'], $param['w_size']));
            $param['id'] = $id1;

            $rep = '<div type="type_number" class="wdform-field" style="width: ' . $wdformfieldsize . 'px">';

            $rep .= $this->field_label($param, $row);

            $classes = array('wdform-element-section');
            if ( isset($param['w_class']) ) {
              $classes[] = $param['w_class'];
            }
            if ( isset($param['w_field_label_pos']) && $param['w_field_label_pos'] != "left" ) {
              $classes[] = 'wd-block';
            }

            $rep .= '<div class="' . implode(' ', $classes) . '" style="width: ' . $param['w_size'] . 'px;">
              <input type="text"
                     class="wd-width-100"
                     id="wdform_' . $id1 . '_element' . $form_id . '"
                     name="wdform_' . $id1 . '_element' . $form_id . '"
                     value="' . $param['w_first_val'] . '"
                     data-value="' . $param['w_first_val'] . '"
                     title="' . $param['w_title'] . '"
                     ' . $param['attributes'] . '>
              </div>';
            $rep .= '</div>';
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
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['id'] = $id1;

            $message_confirm = addslashes(__("Password values don't match", WDFMInstance(self::PLUGIN)->prefix));
            $onchange = (isset($param['w_verification']) && $param['w_verification'] == "yes") ? ' onchange="wd_check_confirmation_pass(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')"' : "";

            $html = '<input type="password"
                   class="wd-width-100"
                   id="wdform_' . $id1 . '_element' . $form_id . '"
                   name="wdform_' . $id1 . '_element' . $form_id . '"
                   ' . $param['attributes'] . $onchange . ' />';
            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);
            if ( isset($param['w_verification']) && $param['w_verification'] == "yes" ) {
              $param['label'] = $param['w_verification_label'];
              $param['id'] = 'wdform_' . $id1 . '_1_element' . $form_id;
              $param['label'] = $param['w_verification_label'];

              $html = '<input type="password"
                     class="wd-width-100"
                     id="wdform_' . $id1 . '_1_element' . $form_id . '"
                     name="wdform_' . $id1 . '_1_element' . $form_id . '"
                     ' . $param['attributes'] . '
                     onchange="wd_check_confirmation_pass(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')" />';
              // Generate field.
              $rep .= $this->wdform_field('type_password_confirmation', $param, $row, $html);
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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $param['w_first_val'], 'esc_html' );
            $textarea_value = str_replace(array( "\r\n", "\n\r", "\n", "\r" ), "&#13;", $param['w_first_val']);

            $param['id'] = $id1;
            $param['w_size'] = $param['w_size_w'];
		  			$param['w_characters_limit'] = (isset($param['w_characters_limit']) ? $param['w_characters_limit'] : "");
		  			
            $html = '<textarea class="wd-width-100"
                      id="wdform_' . $id1 . '_element' . $form_id . '"
                      name="wdform_' . $id1 . '_element' . $form_id . '"
                      placeholder="' . $param['w_title']. '"
                      data-value="' . $textarea_value . '"
                      maxlength="' . $param['w_characters_limit']. '"
                      style="height: ' . $param['w_size_h'] . 'px;"
                      ' . $param['attributes'] . '>' . $textarea_value . '</textarea>';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $param['w_first_val'] = stripslashes(WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_first' . $form_id, $w_first_val[0], 'esc_html' ) ) . '***' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_last' . $form_id, $w_first_val[1], 'esc_html' ) );
            $w_first_val = explode('***', $param['w_first_val']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);

            $param['id'] = 'wdform_' . $id1 . '_element_first' . $form_id;
            $param['w_class'] .= ' wd-flex-row';

            $html = '<div class="wd-flex wd-flex-column wd-width-20">
                <input type="text" class="wd-phone-first" id="wdform_' . $id1 . '_element_first' . $form_id . '" name="wdform_' . $id1 . '_element_first' . $form_id . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" placeholder="' . $w_title[0] . '" ' . $param['attributes'] . '>
                <label for="wdform_' . $id1 . '_element_first' . $form_id . '" class="mini_label wd-flex-column">' . $w_mini_labels[0] . '</label>
              </div>
              <div>
                <div class="wd-flex wd-flex-column ">&nbsp;-&nbsp;</div>
              </div>
              <div class="wd-flex wd-flex-column wd-width-80">
                <input type="text" class="wd-flex-column wd-width-100" id="wdform_' . $id1 . '_element_last' . $form_id . '" name="wdform_' . $id1 . '_element_last' . $form_id . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" placeholder="' . $w_title[1] . '" ' . $param['attributes'] . ' />
                <label for="wdform_' . $id1 . '_element_last' . $form_id . '" class="wd-flex-column mini_label">' . $w_mini_labels[1] . '</label>
              </div>';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

            break;
          }
          case 'type_phone_new': {
            if ( $fm_settings['fm_developer_mode'] ) {
              wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-phone_field_css');
              wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-phone_field');
            }
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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $param['w_first_val'], 'esc_html' ) );
            $param['id'] = $id1;

            $html = '<input type="text"
                       class="wd-width-100"
                       id="wdform_' . $id1 . '_element' . $form_id . '"
                       name="wdform_' . $id1 . '_element' . $form_id . '"
                       value="' . $param['w_first_val'] . '"
                       data-value="' . $param['w_first_val'] . '"
                       placeholder="" ' . $param['attributes'] . ' />';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $param['w_name_fields'] = isset($param['w_name_fields']) ? $param['w_name_fields'] : ($param['w_name_format'] == 'normal' ? 'no***no' : 'yes***yes');
            $w_name_fields = explode('***', $param['w_name_fields']);
            $param['w_autofill'] = isset($param['w_autofill']) ? $param['w_autofill'] : 'no';
            $element_title = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_title' . $form_id, NULL, 'esc_html' );
            $element_middle = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_middle' . $form_id, NULL, 'esc_html' );
            $element_first = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_first' . $form_id, NULL, 'esc_html' );
            if ( isset($element_title) || isset($element_middle) ) {
              $param['w_first_val'] = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_first' . $form_id, $w_first_val[0], 'esc_html' ) . '***' . WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_last' . $form_id, $w_first_val[1], 'esc_html' ) . '***' . WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_title' . $form_id, $w_first_val[2], 'esc_html' ) . '***' . WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_middle' . $form_id, $w_first_val[3], 'esc_html' );
            }
            else {
              if ( isset($element_first) ) {
                $param['w_first_val'] = WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_first' . $form_id, $w_first_val[0], 'esc_html' ) . '***' . WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_last' . $form_id, $w_first_val[1], 'esc_html' );
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            if ( $param['w_autofill'] == 'yes' && $wp_username ) {
              $user_display_name = explode(' ', $wp_username);
              $w_first_val[0] = $user_display_name[0];
              $w_first_val[1] = isset($user_display_name[1]) ? $user_display_name[1] : $w_first_val[1];
            }

            $first_field_id = 'wdform_' . $id1 . '_element_first' . $form_id;
            $html = '';
            if ( $w_name_fields[0] == 'yes' ) {
              $first_field_id = 'wdform_' . $id1 . '_element_title' . $form_id;
              $html .= '<div class="wd-flex wd-flex-column wd-width-10">';
              $html .= '<input type="text" id="wdform_' . $id1 . '_element_title' . $form_id . '" name="wdform_' . $id1 . '_element_title' . $form_id . '" data-value="' . $w_first_val[2]. '" value="' . $w_first_val[2]. '" title="' . $w_title[2] . '" placeholder="' . $w_title[2] . '" />';
              $html .= '<label class="mini_label" for="wdform_' . $id1 . '_element_title' . $form_id . '">' . $w_mini_labels[0] . '</label>';
              $html .= '</div>';
              $html .= '<div class="wd-flex wd-flex-column wd-name-separator"></div>';
            }
            $html .= '<div class="wd-flex wd-flex-column wd-width-50">';
            $html .= '<input type="text" class="wd-width-100" id="wdform_' . $id1 . '_element_first' . $form_id . '" name="wdform_' . $id1 . '_element_first' . $form_id . '" data-value="' . $w_first_val[0] . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" placeholder="' . $w_title[0] . '" ' . $param['attributes'] . ' />';
            $html .= '<label class="mini_label" for="wdform_' . $id1 . '_element_first' . $form_id . '">' . $w_mini_labels[1] . '</label>';
            $html .= '</div>';
            $html .= '<div class="wd-flex wd-flex-column wd-name-separator"></div>';
            $html .= '<div class="wd-flex wd-flex-column wd-width-50">';
            $html .= '<input type="text" class="wd-width-100" id="wdform_' . $id1 . '_element_last' . $form_id . '" name="wdform_' . $id1 . '_element_last' . $form_id . '" data-value="' . $w_first_val[1] . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" placeholder="' . $w_title[1] . '" ' . $param['attributes'] . ' />';
            $html .= '<label class="mini_label" for="wdform_' . $id1 . '_element_last' . $form_id . '">' . $w_mini_labels[2] . '</label>';
            $html .= '</div>';

            if ( $w_name_fields[1] == 'yes' ) {
              $html .= '<div class="wd-flex wd-flex-column wd-name-separator"></div>';
              $html .= '<div class="wd-flex wd-flex-column wd-width-50">';
              $html .= '<input type="text" class="wd-width-100" id="wdform_' . $id1 . '_element_middle' . $form_id . '" name="wdform_' . $id1 . '_element_middle' . $form_id . '" data-value="' . $w_first_val[3] . '" value="' . $w_first_val[3] . '" title="' . $w_title[3] . '" placeholder="' . $w_title[3] . '" />';
              $html .= '<label class="mini_label" for="wdform_' . $id1 . '_element_middle' . $form_id . '">' . $w_mini_labels[3] . '</label>';
              $html .= '</div>';
            }

            $param['id'] = $first_field_id;
            $param['w_class'] .= ' wd-flex-row';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

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
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $w_disabled_fields = explode('***', $param['w_disabled_fields']);

            $param['id'] = 'wdform_' . $id1 . '_street1' . $form_id;
            $param['w_class'] .= ' wd-flex-column';

            $html = '';
            if ( isset($w_disabled_fields[0]) && $w_disabled_fields[0] == 'no' ) {
              $html .= '<span class="wd-width-100 wd-address" id="wdform_' . $id1 . '_address_0">
                <input class="wd-width-100 wdform_' . $id1 . '_address_0" type="text" id="wdform_' . $id1 . '_street1' . $form_id . '" name="wdform_' . $id1 . '_street1' . $form_id . '" value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_street1' . $form_id, "", 'esc_html' ) ). '" ' . $param['attributes'] . ' />
                <label for="wdform_' . $id1 . '_street1' . $form_id . '" class="mini_label">' . $w_mini_labels[0] . '</label></span>';
            }
            if ( isset($w_disabled_fields[1]) && $w_disabled_fields[1] == 'no' ) {
              $html .= '<span class="wd-width-100 wd-address" id="wdform_' . $id1 . '_address_1">
                <input class="wd-width-100 wdform_' . $id1 . '_address_1" type="text" id="wdform_' . $id1 . '_street2' . $form_id . '" name="wdform_' . ($id1 + 1) . '_street2' . $form_id . '" value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 1) . '_street2' . $form_id, "", 'esc_html' ) ) . '" ' . $param['attributes'] . ' />
                <label for="wdform_' . $id1 . '_street2' . $form_id . '" class="mini_label">' . $w_mini_labels[1] . '</label></span>';
            }
            $html .= '<span class="wd-width-100 wd-flex wd-flex-row wd-flex-wrap wd-justify-content">';
            if ( isset($w_disabled_fields[2]) && $w_disabled_fields[2] == 'no' ) {
              $html .= '<span class="wd-width-49 wd-address" id="wdform_' . $id1 . '_address_2">
                <input class="wd-width-100 wdform_' . $id1 . '_address_2" type="text" id="wdform_' . $id1 . '_city' . $form_id . '" name="wdform_' . ($id1 + 2) . '_city' . $form_id . '" value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 2) . '_city' . $form_id, "", 'esc_html' ) ) . '" ' . $param['attributes'] . ' />
                <label for="wdform_' . $id1 . '_city' . $form_id . '" class="mini_label">' . $w_mini_labels[2] . '</label></span>';
            }
            $post_country = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 5) . '_country' . $form_id, "", 'esc_html' ) );
            if ( isset($w_disabled_fields[3]) && $w_disabled_fields[3] == 'no' ) {
              if ( isset($w_disabled_fields[5]) && $w_disabled_fields[5] == 'no'
                && isset($w_disabled_fields[6]) && $w_disabled_fields[6] == 'yes'
                && $post_country == 'United States' ) {
                $w_states = WDW_FM_Library(self::PLUGIN)->get_states();
                $w_state_options = '';
                $post_state = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 3) . '_state' . $form_id, "", 'esc-html' ) );
                foreach ( $w_states as $w_state_key => $w_state ) {
                  $selected = (($w_state_key == $post_state) ? 'selected="selected"' : '');
                  $w_state_options .= '<option value="' . $w_state_key . '" ' . $selected . '>' . $w_state . '</option>';
                }
                $html .= '<span class="wd-width-49 wd-address" id="wdform_' . $id1 . '_address_3">
                <select class="wd-width-100 wdform_' . $id1 . '_address_3" type="text" id="wdform_' . $id1 . '_state' . $form_id . '" name="wdform_' . ($id1 + 3) . '_state' . $form_id . '" ' . $param['attributes'] . '>' . $w_state_options . '</select>
                <label for="wdform_' . $id1 . '_state' . $form_id . '" class="mini_label wd-block" id="' . $id1 . '_mini_label_state">' . $w_mini_labels[3] . '</label></span>';
              }
              else if ( isset($w_disabled_fields[5]) && $w_disabled_fields[5] == 'no'
                && isset($w_disabled_fields[6]) && $w_disabled_fields[6] == 'yes'
                && $post_country == 'Canada' ) {
                $w_states = WDW_FM_Library(self::PLUGIN)->get_provinces_canada();
                $w_state_options = '';
                $post_state = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 3) . '_state' . $form_id, "", 'esc_html' ) );
                foreach ( $w_states as $w_state_key => $w_state ) {
                  $selected = (($w_state_key == $post_state) ? 'selected="selected"' : '');
                  $w_state_options .= '<option value="' . $w_state_key . '" ' . $selected . '>' . $w_state . '</option>';
                }
                $html .= '<span class="wd-width-49 wd-address" id="wdform_' . $id1 . '_address_3">
                <select class="wd-width-100 wdform_' . $id1 . '_address_3" type="text" id="wdform_' . $id1 . '_state' . $form_id . '" name="wdform_' . ($id1 + 3) . '_state' . $form_id . '" ' . $param['attributes'] . '>' . $w_state_options . '</select>
                <label for="wdform_' . $id1 . '_state' . $form_id . '" class="mini_label wd-block" id="' . $id1 . '_mini_label_state">' . $w_mini_labels[3] . '</label></span>';
              }
              else {
                $html .= '<span class="wd-width-49 wd-address" id="wdform_' . $id1 . '_address_3">
                <input class="wd-width-100 wdform_' . $id1 . '_address_3" type="text" id="wdform_' . $id1 . '_state' . $form_id . '" name="wdform_' . ($id1 + 3) . '_state' . $form_id . '" value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 3) . '_state' . $form_id, "", 'esc_html' ) ) . '" ' . $param['attributes'] . ' />
                <label for="wdform_' . $id1 . '_state' . $form_id . '" class="mini_label">' . $w_mini_labels[3] . '</label></span>';
              }
            }
            if ( isset($w_disabled_fields[4]) && $w_disabled_fields[4] == 'no' ) {
              $html .= '<span class="wd-width-49 wd-address" id="wdform_' . $id1 . '_address_4">
              <input class="wd-width-100 wdform_' . $id1 . '_address_4" type="text" id="wdform_' . $id1 . '_postal' . $form_id . '" name="wdform_' . ($id1 + 4) . '_postal' . $form_id . '" value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . ($id1 + 4) . '_postal' . $form_id, "", 'esc_html' ) ) . '" ' . $param['attributes'] . ' />
              <label for="wdform_' . $id1 . '_postal' . $form_id . '" class="mini_label">' . $w_mini_labels[4] . '</label></span>';
            }
            if ( isset($w_disabled_fields[5]) && $w_disabled_fields[5] == 'no' ) {
              $w_countries = WDW_FM_Library(self::PLUGIN)->get_countries();
              $w_options = '';
              foreach ( $w_countries as $w_country_key => $w_country ) {
                if ( $w_country_key == $post_country ) {
                  $selected = 'selected="selected"';
                }
                else {
                  $selected = '';
                }
                $w_options .= '<option value="' . $w_country_key . '" ' . $selected . '>' . $w_country . '</option>';
              }
              $html .= '<span class="wd-width-49 wd-address" id="wdform_' . $id1 . '_address_5">
              <select class="wd-width-100 wdform_' . $id1 . '_address_5"
                      type="text"
                      id="wdform_' . $id1 . '_country' . $form_id . '"
                      name="wdform_' . ($id1 + 5) . '_country' . $form_id . '"
                      ' . (( isset($w_disabled_fields[6]) && $w_disabled_fields[6] == 'yes' ) ? 'onchange="wd_change_state_input(\'wdform_' . $id1 . '\', \'' . $form_id . '\')"' : '') . '
                      ' . $param['attributes'] . '>' . $w_options . '</select>
              <label for="wdform_' . $id1 . '_country' . $form_id . '" class="mini_label">' . $w_mini_labels[5] . '</label></span>';
            }
            $html .= '</span>';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }

            $param['w_autofill'] = isset($param['w_autofill']) ? $param['w_autofill'] : 'no';
            if ( $param['w_autofill'] == 'yes' && $wp_useremail ) {
              $param['w_first_val'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $wp_useremail, 'esc_html' ) );
            }
            else {
              $param['w_first_val'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $param['w_first_val'], 'esc_html' ) );
            }

            $param['id'] = $id1;
            $param['w_class'] .= ' wd-flex-row';

            $message_confirm = addslashes(__("The email addresses don't match", WDFMInstance(self::PLUGIN)->prefix));
            $message_check_email = addslashes(__('This is not a valid email address.', WDFMInstance(self::PLUGIN)->prefix));
            $onchange = (isset($param['w_verification']) && $param['w_verification'] == "yes") ? '; wd_check_confirmation_email(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')' : '';

            $html = '<input type="text" class="wd-width-100" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" data-value="' . $param['w_first_val'] . '" value="' . $param['w_first_val'] . '" title="' . $param['w_title'] . '" placeholder="' . $param['w_title'] . '"  ' . $param['attributes'] . ' onchange="wd_check_email(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_check_email . '\')' . $onchange . '" />';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

            if ( isset($param['w_verification']) && $param['w_verification'] == "yes" ) {
              $param['w_verification_placeholder'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_1_element' . $form_id, $param['w_verification_placeholder'], 'esc_html' ) );
              $param['label'] = $param['w_verification_label'];
              $param['id'] = 'wdform_' . $id1 . '_1_element' . $form_id;

              $html = '<input type="text" class="wd-width-100" id="wdform_' . $id1 . '_1_element' . $form_id . '" name="wdform_' . $id1 . '_1_element' . $form_id . '" placeholder="' . $param['w_verification_placeholder'] . '" title="' . $param['w_verification_placeholder'] . '"  ' . $param['attributes'] . ' onchange="wd_check_confirmation_email(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')" />';

              // Generate field.
              $rep .= $this->wdform_field($type, $param, $row, $html);
            }
            break;
          }
          case 'type_checkbox': {
            $rep = $this->type_checkbox($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_radio': {
            $rep = $this->type_radio($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_own_select': {
            $rep = $this->type_own_select($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_country': {
            $rep = $this->type_country($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_time': {
            $rep = $this->type_time($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_date': { //Todo: Depricated.
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend-momentjs');
            if ( function_exists('wp_add_inline_script') ) { // Since Wordpress 4.5.0
              wp_add_inline_script('jquery-ui-datepicker', WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker());
            }
            else {
              echo '<script>' . WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker() . '</script>';
            }

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
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_disable_past_days'] = isset($param['w_disable_past_days']) ? $param['w_disable_past_days'] : 'no';
            $param['w_date'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_element" . $form_id, $param['w_date'], 'esc_html' ) );

            $rep = '<div type="type_date" class="wdform-field">';

            $rep .= $this->field_label($param, $row, $id1);

            $classes = array('wdform-element-section');
            if ( isset($param['w_class']) ) {
              $classes[] = $param['w_class'];
            }
            if ( isset($param['w_field_label_pos']) && $param['w_field_label_pos'] != "left" ) {
              $classes[] = 'wd-block';
            }

            $rep .= '<div class="' . implode(' ', $classes) . '">
            <input type="text" autocomplete="off" data-value="' . $param['w_date'] . '" value="' . $param['w_date'] . '" class="wdform-date wd-datepicker" data-format="' . $param['w_format'] . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" maxlength="10" ' . $param['attributes'] . ' /></div></div>';
            break;
          }
          case 'type_date_new': {
            $rep = $this->type_date_new($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_date_range': {
            $rep = $this->type_date_range($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_date_fields': {
            $rep = $this->type_date_fields($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_file_upload': {
            $rep = $this->type_file_upload($params, $row, $id1, $form_id, $param);
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
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }

            $param['id'] = 'wd_captcha_input' . $form_id;
            $param['w_class'] .= ' wd-flex-row wd-align-items-center';

            $html = '<img type="captcha"
                           digit="' . $param['w_digit'] . '"
                           src=" ' . add_query_arg(array('action' => 'formmakerwdcaptcha' . WDFMInstance(self::PLUGIN)->plugin_postfix, 'digit' => $param['w_digit'], 'i' => $form_id), admin_url('admin-ajax.php')) . '"
                           id="wd_captcha' . $form_id . '"
                           class="captcha_img wd-hidden"
                           ' . $param['attributes'] . ' />';
            $html .= '<div class="captcha_refresh" id="_element_refresh' . $form_id . '" ' . $param['attributes'] . '></div>';
            $html .= '<input type="text"
                              class="captcha_input"
                              id="wd_captcha_input' . $form_id . '"
                              name="captcha_input"
                              style="width: ' . ($param['w_digit'] * 10 + 20) . 'px;"
                              ' . $param['attributes'] . ' />';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);
            if ( Cookie_fm::getCookieByKey($form_id, 'message_captcha') != "" ) {
              $rep .= '<div class="fm-not-filled message_captcha">' . Cookie_fm::getCookieByKey($form_id, 'message_captcha') . '</div>';
              Cookie_fm::getCookieByKey($form_id, 'message_captcha', true);
            }

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
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }

            $param['w_count'] = $param['w_count'] ? $param['w_count'] : 1;
            $param['w_operations'] = $param['w_operations'] ? $param['w_operations'] : '+, -, *, /';
            $param['w_input_size'] = $param['w_input_size'] ? $param['w_input_size'] : 60;

            $param['id'] = 'wd_arithmetic_captcha_input' . $form_id;
            $param['w_class'] .= ' wd-flex-row wd-align-items-center';

            $html = '<img type="captcha"
                          operations_count="' . $param['w_count'] . '"
                          operations="' . $param['w_operations'] . '"
                          src="' . add_query_arg(array('action' => 'formmakerwdmathcaptcha' . WDFMInstance(self::PLUGIN)->plugin_postfix, 'operations_count' => $param['w_count'], 'operations' => urlencode($param['w_operations']), 'i' => $form_id), admin_url('admin-ajax.php')) . '"
                          id="wd_arithmetic_captcha' . $form_id . '"
                          class="arithmetic_captcha_img"
                          ' . $param['attributes'] . ' />';
            $html .= '<div class="captcha_refresh" id="_element_refresh' . $form_id . '" ' . $param['attributes'] . '></div>';
            $html .= '<input type="text"
                              class="arithmetic_captcha_input"
                              id="wd_arithmetic_captcha_input' . $form_id . '"
                              name="arithmetic_captcha_input"
                              onkeypress="return check_isnum(event)"
                              style="width: ' . $param['w_input_size'] . 'px;"
                              ' . $param['attributes'] . ' />';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);
            if ( Cookie_fm::getCookieByKey($form_id, 'message_captcha') != '' ) {
              $rep .= '<div class="fm-not-filled message_captcha">' . Cookie_fm::getCookieByKey($form_id, 'message_captcha') . '</div>';
              Cookie_fm::getCookieByKey($form_id, 'message_captcha', true);
            }

            break;
          }
          case 'type_recaptcha': {
            if ( WDW_FM_Library(self::PLUGIN)->elementor_is_active() ) {
              $html = '<span style="color: red; font-style: italic;">' . __('No preview available for reCAPTCHA.', WDFMInstance(self::PLUGIN)->prefix) . '</span>';
              $rep = '<div class="fm-not-filled message_captcha">' . $html . '</div>';
              break;
            }
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_public', 'w_private', 'w_class' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_public',
                'w_private',
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
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            $invisible = isset($param['w_type']) && $param['w_type'] == 'invisible' ? 1 : 0;
            $badge = isset($param['w_position']) ? $param['w_position'] : 0;
            $class = '';
            if ('hidden' == $badge && $invisible) {
              $badge = 'inline';
              $class = ' fm-hide';
            }
            if ($invisible) {
              $param['w_hide_label'] = 'yes';
            }
            $publickey = isset($fm_settings['public_key']) && $fm_settings['public_key'] ? $fm_settings['public_key'] : 'invalid sitekey';
            if ('invalid sitekey' == $publickey) {
              $badge = 'inline';
              $class = '';
            }

            if($param['w_type'] == 'v3' && $publickey != "invalid sitekey") {
              wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-g-recaptcha-v3');
              $html = '<input type="hidden" name="recaptcha_response' . $form_id . '" class="g-recaptcha" data-size="v3" id="recaptchaV3Response_' . $form_id . $id1 . '" data-id="'.$id1.'" data-form-id="'.$form_id.'" data-badge="inline" data-sitekey="' . $publickey . '">';
            } else {
              wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-g-recaptcha');
              $html = '<div id="recaptcha' . $form_id . $id1 . '" class="g-recaptcha' . $class . '" data-sitekey="' . $publickey . '" data-form_id="' . $form_id . '"' . ($invisible ? ' data-size="invisible"' : '') . ($badge ? ' data-badge="' . $badge . '"' : '') . '></div>';
            }

            $param['id'] = '';
            $param['w_class'] = 'wd-flex-row';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);
            if ( Cookie_fm::getCookieByKey($form_id, 'message_captcha') != '' ) {
              $rep .= '<div class="fm-not-filled message_captcha"' . ( (Cookie_fm::getCookieByKey($form_id, 'recaptcha_score')) ? ' data-score="' . Cookie_fm::getCookieByKey($form_id, 'recaptcha_score') . '"' : '') . '>' . Cookie_fm::getCookieByKey($form_id, 'message_captcha') . '</div>';
              Cookie_fm::getCookieByKey($form_id, 'message_captcha', true);
            }

            break;
          }
          case 'type_hidden': {
            $params_names = array( 'w_name', 'w_value' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }

            $param['id'] = '';

            // todo: remove hidden input label
            // $rep .= '<div class="wdform-label-section" class="wd-table-cell"></div>';
            $html = '<input type="hidden" data-value="' . $param['w_value'] . '" value="' . $param['w_value'] . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="' . $param['w_name'] . '" ' . $param['attributes'] . ' />';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html, FALSE);

            break;
          }
          case 'type_mark_map': {
            if ( $fm_settings['fm_developer_mode'] ) {
              wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-gmap_form');
            }
            else {
              wp_enqueue_script('google-maps');
            }
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
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }

            $param['id'] = '';
            $param['w_class'] .= ' wd-flex-row';

            $html = '<input type="hidden" id="wdform_' . $id1 . '_long' . $form_id . '" name="wdform_' . $id1 . '_long' . $form_id . '" value="' . $param['w_long'] . '" />';
            $html .= '<input type="hidden" id="wdform_' . $id1 . '_lat' . $form_id . '" name="wdform_' . $id1 . '_lat' . $form_id . '" value="' . $param['w_lat'] . '" />';
            $html .= '<div class="wd-width-100" id="wdform_' . $id1 . '_element' . $form_id . '" long0="' . $param['w_long'] . '" lat0="' . $param['w_lat'] . '" zoom="' . $param['w_zoom'] . '" info0="' . str_replace(array("\r\n", "\n", "\r"), '<br />', $param['w_info']) . '" center_x="' . $param['w_center_x'] . '" center_y="' . $param['w_center_y'] . '" style="' . ($param['w_width'] != '' ? 'max-width: ' . $param['w_width'] . 'px; ' : '') . 'height: ' . $param['w_height'] . 'px;" ' . $param['attributes'] . '></div>';

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html);

            break;
          }
          case 'type_map': {
            $rep = $this->type_map($params, $id1, $row, $param);
            break;
          }
          case 'type_paypal_price': { // Todo: Depricated.
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
              $param[$params_name] = esc_html( $temp[0] );
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $param['w_first_val'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_dollars' . $form_id, $w_first_val[0], 'esc_html' ) ) . '***' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element_cents' . $form_id, $w_first_val[1], 'esc_html' ) );
            $hide_cents = ($param['w_hide_cents'] == "yes" ? "wd-hidden" : "wd-table-cell");
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);

            $rep = '<div type="type_paypal_price" class="wdform-field">';

            $rep .= $this->field_label($param, $row, 'wdform_' . $id1 . '_element_dollars' . $form_id);

            $classes = array('wdform-element-section');
            if ( isset($param['w_class']) ) {
              $classes[] = $param['w_class'];
            }
            if ( isset($param['w_field_label_pos']) && $param['w_field_label_pos'] != "left" ) {
              $classes[] = 'wd-block';
            }

            $rep .= '<div class="' . implode(' ', $classes) . '">';

            $rep .= '<input type="hidden" value="' . $param['w_range_min'] . '" name="wdform_' . $id1 . '_range_min' . $form_id . '" id="wdform_' . $id1 . '_range_min' . $form_id . '" />';
            $rep .= '<input type="hidden" value="' . $param['w_range_max'] . '" name="wdform_' . $id1 . '_range_max' . $form_id . '" id="wdform_' . $id1 . '_range_max' . $form_id . '" />';
            $rep .= '<div id="wdform_' . $id1 . '_table_price" class="wd-table">';
            $rep .= '<div id="wdform_' . $id1 . '_tr_price1" class="wd-table-row">';
            $rep .= '<div id="wdform_' . $id1 . '_td_name_currency" class="wd-table-cell">';
            $rep .= '<span class="wdform_colon wd-vertical-middle">&nbsp;' . $form_currency . '&nbsp;</span>';
            $rep .= '</div>';
            $rep .= '<div id="wdform_' . $id1 . '_td_name_dollars" class="wd-table-cell">';
            $rep .= '<input type="text" id="wdform_' . $id1 . '_element_dollars' . $form_id . '" name="wdform_' . $id1 . '_element_dollars' . $form_id . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" onkeypress="return check_isnum(event)" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . ' />';
            $rep .= '</div>';
            $rep .= '<div id="wdform_' . $id1 . '_td_name_divider" class="' . $hide_cents . '">';
            $rep .= '<span class="wdform_colon wd-vertical-middle">&nbsp;.&nbsp;</span>';
            $rep .= '</div>';
            $rep .= '<div id="wdform_' . $id1 . '_td_name_cents" class="' . $hide_cents . '">';
            $rep .= '<input type="text" class="wd-paypal-cent" id="wdform_' . $id1 . '_element_cents' . $form_id . '" name="wdform_' . $id1 . '_element_cents' . $form_id . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" ' . $param['attributes'] . ' />';
            $rep .= '</div></div>';
            $rep .= '<div id="wdform_' . $id1 . '_tr_price2" class="wd-table-row">';
            $rep .= '<div class="wd-table-cell"><label class="mini_label"></label></div>';
            $rep .= '<div align="left" class="wd-table-cell"><label class="mini_label">' . $w_mini_labels[0] . '</label></div>';
            $rep .= '<div id="wdform_' . $id1 . '_td_name_label_divider" class="' . $hide_cents . '"><label class="mini_label"></label></div>';
            $rep .= '<div align="left" id="wdform_' . $id1 . '_td_name_label_cents" class="' . $hide_cents . '"><label class="mini_label">' . $w_mini_labels[1] . '</label></div>';
            $rep .= '</div></div></div></div>';
            break;
          }
          case 'type_paypal_price_new': {
            $rep = $this->type_paypal_price_new($params, $row, $id1, $form_id, $param, $form_currency, $symbol_begin, $symbol_end);
            break;
          }
          case 'type_paypal_select': {
            $rep = $this->type_paypal_select($params, $row, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_checkbox': {
            $rep = $this->type_paypal_checkbox($params, $row, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_radio': {
            $rep = $this->type_paypal_radio($params, $row, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_shipping': {
            $rep = $this->type_paypal_shipping($params, $row, $id1, $form_id, $param);
            break;
          }
          case 'type_submit_reset': {
            $params_names = array( 'w_submit_title', 'w_reset_title', 'w_class', 'w_act' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_act'] = ($param['w_act'] == "false" ? 'wd-hidden' : "");

            $param['id'] = $id1;
            $param['w_class'] .= ' wd-flex-row';
            if ( isset($form_theme['SPAlign']) && $form_theme['SPAlign'] != '' ) {
              $param['w_class'] .= ' wd-justify-content-' . $form_theme['SPAlign'];
            }

            $submit_onclick = 'onclick="fm_submit_form(\'' . $form_id . '\');"';
            $reset_onclick  = 'onclick="fm_reset_form(' . $form_id . ');"';
            $disabled_submit = '';
            $disabled_reset = '';

            if ( WDW_FM_Library(self::PLUGIN)->elementor_is_active() ) {
              $submit_onclick = '';
              $reset_onclick = '';
              $disabled_submit = 'disabled="disabled"';
              $disabled_reset = 'disabled="disabled"';
            }

            $rep = '';
            if ( $row->gdpr_checkbox && $row->gdpr_checkbox_text ) {
              $privacy_policy_page = WDW_FM_Library(self::PLUGIN)->get_privacy_policy_url();
              $privacy_policy_link = $privacy_policy_page['title'];
              if ( !empty($privacy_policy_page['url']) ) {
                $privacy_policy_link = ' <a href="' . $privacy_policy_page['url'] . '" target="_blank">' . $privacy_policy_page['title'] . '</a>';
              }
              $row->gdpr_checkbox_text = str_replace('{{privacy_policy}}', $privacy_policy_link, $row->gdpr_checkbox_text);
              $gdpr_checkbox_html = '<label for="fm_privacy_policy' . $form_id . '" class="wdform-label">
                                       <input id="fm_privacy_policy' . $form_id . '" name="fm_privacy_policy' . $form_id . '" class="wd-flex-row fm-gdpr-checkbox" onclick="' . ($disabled_submit ? '' : 'fm_privacy_policy_check(this)') . '" type="checkbox" value="1">'
                                        . $row->gdpr_checkbox_text .
                                     '</label>';
              $rep = $this->wdform_field('type_gdpr_compliance_checkbox', $param, $row, $gdpr_checkbox_html, FALSE);
              $disabled_submit = 'disabled="disabled"';
            }
            $ajax_submit_status = isset($fm_settings['fm_ajax_submit']) ? $fm_settings['fm_ajax_submit'] : 0;
            $html = '<button ' . $disabled_submit . ' type="button" class="button-submit" ' . $submit_onclick . ' ' . $param['attributes'] . ' data-ajax="'.$ajax_submit_status.'">';
            $html .= '<span class="fm-submit-loading spinner fm-ico-spinner"></span>' . $param['w_submit_title'];
            $html .= '</button>';
            $html .= '<button ' . $disabled_reset . ' type="button" class="button-reset ' . $param['w_act'] . '" ' . $reset_onclick . ' ' . $param['attributes'] . '>' . $param['w_reset_title'] . '</button>';

            // Generate field.
            $rep .= $this->wdform_field($type, $param, $row, $html, FALSE);

            break;
          }
          case 'type_button': {
            $params_names = array( 'w_title', 'w_func', 'w_class' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = esc_html($temp[0]);
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_title'] = explode('***', $param['w_title']);
            $param['w_func'] = explode('***', $param['w_func']);

            $param['id'] = '';
            $param['w_class'] .= ' wd-flex-row';

            // Todo: Delete field label.
            //            $rep .= '<div class="wdform-label-section wd-table-cell">';
            //            $rep .= '<span class="wd-hidden">button_' . $id1 . '</span>';
            //            $rep .= '</div>';
            $html = '';
            foreach ( $param['w_title'] as $key => $title ) {
              $html .= '<button type="button" name="wdform_' . $id1 . '_element' . $form_id . $key . '" onclick="' . $param['w_func'][$key] . '" ' . $param['attributes'] . '>' . $title . '</button>';
            }

            // Generate field.
            $rep = $this->wdform_field($type, $param, $row, $html, FALSE);

            break;
          }
          case 'type_star_rating': {
            $rep = $this->type_star_rating($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_scale_rating': {
            $rep = $this->type_scale_rating($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_spinner': {
            $rep = $this->type_spinner($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_slider': {
            $rep = $this->type_slider($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_range': {
            $rep = $this->type_range($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_grading': {
            $rep = $this->type_grading($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_matrix': {
            $rep = $this->type_matrix($params, $row, $form_id, $id1, $type, $param);
            break;
          }
          case 'type_paypal_total': {
            $rep = $this->type_paypal_total($params, $row, $id1, $form_id, $param);
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
							$param[$params_name] = esc_html($temp[0]);
		    			$temp = ( isset( $temp[1] ) ? $temp[1] : '' );
						}
						$param['w_field_label_pos'] = (isset($param['w_field_label_pos']) ? $param['w_field_label_pos'] : "top");
						$param['w_class'] = (isset($param['w_class']) ? $param['w_class'] : "");
						$param['w_class'] .= ' wd-flex-row wd-align-items-center';
						$param['id'] = $id1;
            /* get stripe extension form */
            $stripe_data = array(
            	'form_view' => $this,
							'form' => $row,
							'attributes' => $params,
							'input_index' => $id1,
							'form_id' => $form_id,
							'html' => '',
							'general_param' => $param
						);
            if ( WDFMInstance(self::PLUGIN)->is_free != 2 && $row->paypal_mode == 2 ) {
              $stripe_data = apply_filters('fm_addon_stripe_form_init', $stripe_data);
            }
		  			// Generate field.
            $rep .= !empty($stripe_data['html']) ? $stripe_data['html'] : '';
            break;
          }
          case 'type_signature': {
            if ( $fm_settings['fm_developer_mode'] ) {
              wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-signaturepad');
            }
            $rep = $this->type_signature($params, $row, $form_id, $id1, $type, $param);
            break;
          }
        }
        $form = str_replace('%' . $id1 . ' - ' . $labels[$id1s_key] . '%', $rep, $form);
        $form = str_replace('%' . $id1 . ' -' . $labels[$id1s_key] . '%', $rep, $form);
      }
    }
    $rep1 = array( 'form_id_temp' );
    $rep2 = array( $form_id );
    $form = str_replace($rep1, $rep2, $form);
    if ( !$fm_hide_form_after_submit ) {
      $form_maker_front_end .= $form;
      if ( isset($form_theme['HPAlign']) && ($form_theme['HPAlign'] == 'right' || $form_theme['HPAlign'] == 'bottom') ) {
        if ( $row->header_hide && $row->header_title || $row->header_description || $row->header_image_url ) {
          $form_maker_front_end .= '<div class="fm-header-bg"><div class="fm-header ' . $image_pos . '">';
          if ( $form_theme['HIPAlign'] == 'left' || $form_theme['HIPAlign'] == 'top' ) {
            if ( $row->header_image_url ) {
              $form_maker_front_end .= '<div class="fm-header-img ' . $hide_header_image_class . ' fm-animated ' . $header_image_animation . '"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
            }
          }
          if ( $row->header_title || $row->header_description ) {
            $form_maker_front_end .= '<div class="fm-header-text">
                <div class="fm-header-title">
                  ' . $row->header_title . '
                </div>
                <div class="fm-header-description">
                  ' . do_shortcode($row->header_description) . '
                </div>
              </div>';
          }
          if ( $form_theme['HIPAlign'] == 'right' || $form_theme['HIPAlign'] == 'bottom' ) {
            if ( $row->header_image_url ) {
              $form_maker_front_end .= '<div class="fm-header-img"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
            }
          }
          $form_maker_front_end .= '</div></div>';
        }
      }
    }
    $form_maker_front_end .= '<div class="wdform_preload"></div>';
    $form_maker_front_end .= '<input type="hidden" name="fm-current-page" value="' . esc_url(WDW_FM_Library(self::PLUGIN)->get_current_page_url()) . '" />';
    $form_maker_front_end .= '</form>';
    $jsversion = $row->jsversion ? $row->jsversion : 1;
    $front_urls = WDFMInstance(self::PLUGIN)->front_urls;
    $frontend_dir ='/form-maker-frontend/';
    $wp_upload_dir = wp_upload_dir();
    $fm_script_dir = $wp_upload_dir['basedir'] . $frontend_dir . 'js/fm-script-' . $form_id . '.js';
    $fm_script_url = $front_urls['upload_url'] . $frontend_dir . 'js/fm-script-' . $form_id . '.js';
    if ( WDFMInstance(self::PLUGIN)->fm_settings['fm_file_read'] == '1' || !file_exists($fm_script_dir) ) {
      $fm_js_content = WDW_FM_Library(self::PLUGIN)->get_fm_js_content($form_id);
      $handle = "";
      if ( wp_script_is(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend', 'registered') ) {
        $handle = WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend';
      }
      elseif ( wp_script_is('jquery', 'registered') ) {
        $handle = "jquery";
      }
      if ( function_exists('wp_add_inline_script') && $handle ) {
        wp_add_inline_script($handle, $fm_js_content);
      }
      else {
        echo '<script id="'. WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend-inline-js">' . $fm_js_content . '</script>';
      }
    }
    else {
      wp_register_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-script-' . $form_id, $fm_script_url, array( 'jquery', 'fm-frontend'  ), $jsversion);
      if ( WDW_FM_Library(self::PLUGIN)->elementor_is_active() ) {
        wp_print_scripts(WDFMInstance(self::PLUGIN)->handle_prefix . '-script-' . $form_id);
      }
      else {
        wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-script-' . $form_id);
      }
    }
    $_GET['addon_view'] = 'frontend';
    $_GET['form_id'] = $form_id;
    if (WDFMInstance(self::PLUGIN)->is_free != 2) {
      $save_progress_params = array();
      $save_progress_params['form'] = $row;
      $save_progress_params['form_id'] = $form_id;

      do_action('WD_FM_SAVE_PROG_init', $save_progress_params);
    }
    
     /*
    Check if the Limit of submissions is set and reached.
    */
    $submission_count = $result[5];
    if ( $submission_count != 0 && $submission_count >= intval($row->submissions_limit) ) {
      $submissions_limit_text = $row->submissions_limit_text;

      // Check if the form is after submittions
      if( $limsub == 1 ) {
        $form_maker_front_end = '<div id="fm-pages' . $form_id . '" class="fm-pages wdform_page_navigation ' . $pagination_align . '" show_title="' . $row->show_title . '" show_numbers="' . $row->show_numbers . '" type="' . $row->pagination . '"></div><form name="form' . $form_id . '" action="' . $current_url . '" method="post" id="form' . $form_id . '" class="fm-form form' . $form_id . ' ' . $header_pos . ' ' . ( ( Cookie_fm::getCookieByKey($form_id, 'form_submit_type') || Cookie_fm::getCookieByKey($form_id, 'massage_after_submit') ) ? 'fm-form-submitted' : '' ) . '" enctype="multipart/form-data"><div class="fm-message fm-notice-success">' . __( 'Your form was successfully submitted.', WDFMInstance(self::PLUGIN)->prefix ) . '</div></form>';
      }
      else {
        if( $submissions_limit_text == '' ){
          $form_maker_front_end = '';
        }
        else {
          $form_maker_front_end = '<div id="fm-pages' . $form_id . '" class="fm-pages wdform_page_navigation ' . $pagination_align . '" show_title="' . $row->show_title . '" show_numbers="' . $row->show_numbers . '" type="' . $row->pagination . '"></div><form name="form' . $form_id . '" action="' . $current_url . '" method="post" id="form' . $form_id . '" class="fm-form form' . $form_id . ' ' . $header_pos . ' ' . ( ( Cookie_fm::getCookieByKey($form_id, 'form_submit_type') || Cookie_fm::getCookieByKey($form_id, 'massage_after_submit') ) ? 'fm-form-submitted' : '' ) . '" enctype="multipart/form-data"><div class="fm-message ' . ( (Cookie_fm::getCookieByKey($form_id, 'error_or_no')) ? 'fm-notice-error' : 'fm-notice-success' ) . '">' . __( $submissions_limit_text, WDFMInstance(self::PLUGIN)->prefix ) . '</div></form>';
        }
      }
    }
    return $formType == 'embedded' ? WDW_FM_Library(self::PLUGIN)->fm_container($theme_id, $form_maker_front_end) : $form_maker_front_end;
  }

  /**
   * Autoload form.
   *
   * @param array $params
   * @return string
   */
  public function autoload_form( $params = array() ) {
    $id = $params['id'];
    $type = $params['type'];
    $form = $params['form'];
    $display_on_this = $params['display_on_this'];
    $show_for_admin = $params['show_for_admin'];
    $form_result = $params['form_result'];
    $form_result['fm_page_id'] = $params['fm_page_id'];
    $form_result['fm_current_post_type'] = $params['fm_current_post_type'];
    $fm_settings = $params['fm_settings'];
    $error = $params['error'];
    $message = $params['message'];
    $onload_js = '';
    $fm_form = '';
    $submit_text_type = ($form->submit_text_type == 1 || $form->submit_text_type == 3) ? TRUE : FALSE;
    switch ($type) {
      case 'topbar': {
        $top_bottom = $form->topbar_position ? 'top' : 'bottom';
        $fixed_relative = !$form->topbar_remain_top && $form->topbar_position ? 'absolute' : 'fixed';
        $closing = $form->topbar_closing;
        $hide_duration = $form->topbar_hide_duration;
        $hide_mobile = wp_is_mobile() && $form->hide_mobile ? FALSE : TRUE;
        /**
         *  Third argument after 'or' is checking if ajax_submit option enabled and this is action after form submited
         * which keeped in $_SESSION['fm_hide_form_after_submit' . $id] variable
         */
        if ( $display_on_this && $hide_mobile || ( $fm_settings['fm_ajax_submit'] && Cookie_fm::getCookieByKey($id, 'fm_hide_form_after_submit') == 1 ) ) {
          if ( Cookie_fm::getCookieByKey($id, 'fm_hide_form_after_submit') == 1 ) {
            if ( $error == 'success' ) {
              if ($message) {
                $onload_js .= '
								jQuery("#fm-form' . $id . '").css("display", "none");
								jQuery("#fm-pages' . $id . '").css("display", "none");
								jQuery("#fm-topbar' . $id . '").css("visibility", "");
								fm_hide_form(' . $id . ', ' . $hide_duration . ');';
              }
              else {
                $onload_js .= '
								fm_hide_form(' . $id . ', ' . $hide_duration . ');';
              }
            }
          }
          else {
            $onload_js .= '
								if (' . $hide_duration . ' == 0) {
									localStorage.removeItem("hide-"+' . $id . ');
								}
								var hide_topbar = localStorage.getItem("hide-"+' . $id . ');
								if(hide_topbar == null || fm_currentDate.getTime() >= hide_topbar || ' . $show_for_admin . '){
									jQuery("#fm-topbar' . $id . '").css("visibility", "");
									jQuery("#fm-topbar' . $id . ' .fm-header-img").addClass("fm-animated ' . ($form->header_image_animation) . '");
								}';
          }

          $fm_form .= '<div id="fm-topbar' . $id . '" class="fm-topbar" style="position: ' . $fixed_relative . '; ' . $top_bottom . ': 0px; visibility:hidden;">';
          $fm_form .= $this->display($form_result, $fm_settings, $id, $type);
          $fm_form .= '<div id="fm-action-buttons' . $id . '" class="fm-action-buttons">';
          if ($closing) {
            $fm_form .= '<span id="closing-form' . $id . '" class="closing-form fm-ico-delete" onclick="fm_hide_form(' . $id . ', ' . $hide_duration . ', function(){
									jQuery(\'#fm-topbar' . $id . '\').css(\'display\', \'none\');
								})">
							  </span>';
          }
          $fm_form .= '</div>';
          $fm_form .= '</div>';
          /* one more closing div for closing buttons */
        }
        break;
      }
      case 'scrollbox': {
        $left_right = $form->scrollbox_position ? 'right' : 'left';
        $trigger_point = (int)$form->scrollbox_trigger_point;
        $closing = $form->scrollbox_closing;
        $minimize = $form->scrollbox_minimize;
        $minimize_text = $form->scrollbox_minimize_text;
        $hide_duration = $form->scrollbox_hide_duration;
        $hide_mobile_class = wp_is_mobile() ? 'fm_mobile_full' : '';
        $hide_mobile = wp_is_mobile() && $form->hide_mobile ? FALSE : TRUE;
        $left_right_class = $form->scrollbox_position ? 'float-right' : 'float-left';
        /**
         *  Third argument after 'or' is checking if ajax_submit option enabled and this is action after form submited
         * which keeped in $_SESSION['fm_hide_form_after_submit' . $id] variable
         */
        if ($display_on_this && $hide_mobile || ( $fm_settings['fm_ajax_submit'] && Cookie_fm::getCookieByKey($id, 'fm_hide_form_after_submit') == 1) ) {
          if ( Cookie_fm::getCookieByKey($id, 'fm_hide_form_after_submit') == 1 ) {
            if ( $error == 'success' ) {
              if ( $message ) {
                $onload_js .= '
									jQuery("#fm-form' . $id . ', #fm-pages' . $id . '").addClass("fm-hide");
									jQuery("#fm-scrollbox' . $id . '").removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
									jQuery("#fm-scrollbox' . $id . '").css("visibility", "");
									jQuery("#minimize-form' . $id . '").css("visibility", "hidden");
								';
              }
              $onload_js .= 'fm_hide_form(' . $id . ', ' . $hide_duration . ');';
            }
          }
          else {
            if ( Cookie_fm::getCookieByKey($id, 'error_occurred') == 1 ) {
              Cookie_fm::getCookieByKey($id, 'error_occurred', true);
              if ( $message ) {
                $onload_js .= '
									jQuery("#fm-scrollbox' . $id . '").removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
									jQuery("#fm-scrollbox' . $id . '").removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
									jQuery("#fm-scrollbox' . $id . '").css("visibility", "");
								';
              }
            }
            else {
              $onload_js .= '
								if (' . $hide_duration . ' == 0) {
									localStorage.removeItem("hide-"+' . $id . ');
								}
								var hide_scrollbox = localStorage.getItem("hide-"+' . $id . ');';
              if ($trigger_point > 0) {
                $onload_js .= '
									if(hide_scrollbox == null || fm_currentDate.getTime() >= hide_scrollbox || ' . $show_for_admin . '){
										jQuery(window).scroll(function () {
											fmscrollHandler(' . $id . ');
										  });
										}';
              }
              else {
                $onload_js .= '
								if(hide_scrollbox == null || fm_currentDate.getTime() >= hide_scrollbox || ' . $show_for_admin . '){
									fmscrollHandler(' . $id . ');
								}';
              }
            }
          }
          if ($minimize) {
            $fm_form .= '<div id="fm-minimize-text' . $id . '" class="fm-minimize-text ' . $hide_mobile_class . '" onclick="fm_show_scrollbox(' . $id . ');" style="' . $left_right . ': 0px; display:none;">
								<div>' . $minimize_text . '</div>
							</div>';
          }

          $fm_form .= '<div id="fm-scrollbox' . $id . '" class="fm-scrollbox ' . $hide_mobile_class . '" style="' . $left_right . ': 0px; visibility:hidden;">';
          $fm_form .= '<div class="fm-scrollbox-form ' . $left_right_class . '">';
          $fm_form .= $this->display($form_result, $fm_settings, $id, $type);
          $fm_form .= '<div id="fm-action-buttons' . $id . '" class="fm-action-buttons">';
          if ($minimize) {
            $fm_form .= '<span id="minimize-form' . $id . '" class="minimize-form fm-ico-expand" onclick="minimize_form(' . $id . ')"></span>';
          }
          if ($closing) {
            $fm_form .= '<span id="closing-form' . $id . '" class="closing-form fm-ico-delete" onclick="fm_hide_form(' . $id . ', ' . $hide_duration . ', function(){ jQuery(\'#fm-scrollbox' . $id . '\').removeClass(\'fm-show\').addClass(\'fm-hide\'); });"></span>';
          }
          $fm_form .= '</div>';
          $fm_form .= '</div>';
          $fm_form .= '</div>';
          /* one more closing div for cloasing buttons */
        }
        break;
      }
      case 'popover': {
        $animate_effect = $form->popover_animate_effect;
        $loading_delay = (int)$form->popover_loading_delay;
        $frequency = $form->popover_frequency;
        $hide_mobile = wp_is_mobile() && $form->hide_mobile ? FALSE : TRUE;
        $hide_mobile_class = wp_is_mobile() ? 'fm_mobile_full' : '';
        /**
         *  Third argument after 'or' is checking if ajax_submit option enabled and this is action after form submited
         * which keeped in $_SESSION['fm_hide_form_after_submit' . $id] variable
         */
        if ($display_on_this && $hide_mobile || ($fm_settings['fm_ajax_submit'] && Cookie_fm::getCookieByKey($id, 'fm_hide_form_after_submit') == 1)) {
          if ( Cookie_fm::getCookieByKey($id, 'fm_hide_form_after_submit') == 1) {
            if ( $error == 'success' ) {
              if ( $message || $submit_text_type ) {
                $onload_js .= '
									jQuery("#fm-form' . $id . '").addClass("fm-hide");
									jQuery("#fm-pages' . $id . '").addClass("fm-hide");
									jQuery("#fm-popover-background' . $id . '").css("display", "block");
									jQuery("#fm-popover' . $id . '").css("display", "block");

									fm_hide_form(' . $id . ', ' . $frequency . ');
								';
                if (($form_result[0]->submit_text) == ""){
                  $onload_js .= '
                  jQuery("#form' . $id . '").css("padding-top", "0");
                  ';
                }
              }
              else {
                $onload_js .= '
									jQuery("#fm-form' . $id . '").addClass("fm-hide");
									jQuery("#fm-pages' . $id . '").addClass("fm-hide");
									fm_hide_form(' . $id . ', ' . $frequency . ', function(){
										jQuery("#fm-popover-background' . $id . '").css("display", "none");
										jQuery("#fm-popover' . $id . '").css("display", "none");
									});
								';
              }
            }
          }
          else {
            if ( Cookie_fm::getCookieByKey($id, 'error_occurred') == 1 ) {
              Cookie_fm::getCookieByKey($id, 'error_occurred', true);
              if ( $message ) {
                $onload_js .= '
									jQuery("#fm-popover-background' . $id . '").css("display", "block");
									jQuery("#fm-popover' . $id . '").css("display", "block");
								';
              }
            }
            else {
              $onload_js .= 'if (' . $frequency . ' == 0) {
                  localStorage.removeItem("hide-"+' . $id . ');
                }
								var hide_popover = localStorage.getItem("hide-"+' . $id . ');
								if((hide_popover == null || fm_currentDate.getTime() >= hide_popover || ' . $show_for_admin . ') && ' . ((int)$form->popover_loading_delay >= 0 ? 'true' : 'false') . '){
                setTimeout(function(){
                  show_popup_form_'. $id . '()
									}, ' . ($loading_delay * 1000) . ');
              }';
            }
          }
          $onload_js .= '
              function show_popup_form_'. $id . '() {
                jQuery("#fm-popover-background' . $id . '").css("display", "block");
                jQuery("#fm-popover' . $id . '").css("display", "block");
                jQuery(".fm-popover-content").addClass("fm-animated ' . ($animate_effect) . '");
                jQuery("#fm-popover' . $id . ' .fm-header-img").addClass("fm-animated ' . ($form->header_image_animation) . '");
							}
							jQuery("#fm-popover-inner-background' . $id . '").on("click", function(){
								fm_hide_form(' . $id . ', ' . $frequency . ', function(){
								  jQuery("#fm-popover-background' . $id . '").css("display", "none");
								  jQuery("#fm-popover' . $id . '").css("display", "none");
								});
							});
						';

          $fm_form .= '<div class="fm-popover-background" id="fm-popover-background' . $id . '" style="display:none;"></div>
						<div id="fm-popover' . $id . '" class="fm-popover ' . $hide_mobile_class . '" style="display:none;">
							<div class="fm-popover-container" id="fm-popover-container' . $id . '">
								<div class="fm-popover-inner-background" id="fm-popover-inner-background' . $id . '"></div>
								<div class="fm-popover-content">';
		              $fm_form .= $this->display($form_result, $fm_settings, $id, $type);
		              $fm_form .= '<div id="fm-action-buttons' . $id . '" class="fm-action-buttons">';
                  $fm_form .= '<span id="closing-form' . $id . '" class="closing-form fm-ico-delete" onclick="fm_hide_form(' . $id . ', ' . $frequency . ', function(){
												jQuery(\'#fm-popover-background' . $id . '\').css(\'display\', \'none\');
												jQuery(\'#fm-popover' . $id . '\').css(\'display\', \'none\');
											});"></span>
								</div>
							</div>
						</div>';

          /* one more closing div for cloasing buttons */
        }
        break;
      }
    }

    $wp_upload_dir = wp_upload_dir();
    $fm_script_dir = $wp_upload_dir['basedir'] . '/form-maker-frontend/js/fm-script-' . $id . '.js';
    if ( WDFMInstance(self::PLUGIN)->fm_settings['fm_file_read'] == '1'
      || !file_exists($fm_script_dir) ) {
      $handle = WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend';
    }
    else {
      $handle = WDFMInstance(self::PLUGIN)->handle_prefix . '-script-' . $id;
    }

    if ( !wp_script_is($handle, 'registered') ) {
      if ( wp_script_is('jquery', 'registered') ) {
        $handle = 'jquery';
      }
      else {
        $handle = '';
      }
    }

    if ( function_exists('wp_add_inline_script') && $handle ) {
      wp_add_inline_script($handle, $onload_js);
    }
    else {
      echo '<script>' . $onload_js . '</script>';
    }

    return WDW_FM_Library(self::PLUGIN)->fm_container($form->theme, $fm_form);
  }

  /**
   * Type file upload.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @return string
   */
  private function type_file_upload( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array() ) {
    return '';
  }

  /**
   * Type paypal price new.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @param string $form_currency
   * @param string $symbol_begin
   * @param string $symbol_end
   * @return string
   */
  private function type_paypal_price_new( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array(), $form_currency = '', $symbol_begin = '', $symbol_end = '' ) {
    return '';
  }

  /**
   * Type paypal select.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @return string
   */
  private function type_paypal_select( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array() ) {
    return '';
  }

  /**
   * Type paypal radio.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @return string
   */
  private function type_paypal_radio( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array() ) {
    return '';
  }

  /**
   * Type paypal checkbox.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @return string
   */
  private function type_paypal_checkbox( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array() ) {
    return '';
  }

  /**
   * Type paypal shipping.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @return string
   */
  private function type_paypal_shipping( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array() ) {
    return '';
  }

  /**
   * Type paypal total.
   *
   * @param array $params
   * @param array $row
   * @param int $id1
   * @param int $form_id
   * @param array $param
   * @return string
   */
  private function type_paypal_total( $params = array(), $row = array(), $id1 = 0, $form_id = 0, $param = array() ) {
    return '';
  }

  /**
   * Type map.
   *
   * @param array $params
   * @param int $id1
   * @param array $row
   * @param array $param
   * @return string
   */
  private function type_map( $params = array(), $id1 = 0, $row = array(), $param = array() ) {
    return '';
  }

  /**
   * Type checkbox.
   *
   * @param array  $params
   * @param array  $row
   * @param int    $form_id
   * @param int    $id1
   * @param string $type
   * @param array  $param
   *
   * @return string
   */
  function type_checkbox( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    //ToDo custom_fields add to params array key next version.
    $custom_fields = WDW_FM_Library::get_custom_fields();
    $select_data_from_db = TRUE;
    if ( !empty($param['reset_fields']) && in_array($id1, $param['reset_fields']) ) {
      $select_data_from_db = FALSE;
    }
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
      if ( $params_name == 'w_choices' ) {
        $param[$params_name] = strip_tags($temp[0], "<b><strong><span><a>");
      } else {
         $param[$params_name] = esc_html($temp[0]);
      }
      $temp = $temp[1];
    }
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    if ( !isset($param['w_value_disabled']) ) {
      $param['w_value_disabled'] = 'no';
    }
    if ( !isset($param['w_field_option_pos']) ) {
      $param['w_field_option_pos'] = 'left';
    }
    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
    if ( isset($param['w_choices_value']) ) {
      $param['w_choices_value'] = explode('***', $param['w_choices_value']);
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }
    $post_value = WDW_FM_Library(self::PLUGIN)->get("counter" . $form_id, NULL, 'esc_html');
    $param['id'] = '';
    $param['w_class'] .= ' wd-flex-wrap' . ($param['w_flow'] != 'hor' ? ' wd-flex-column' : ' wd-flex-row');
    $html = '<div class="wd-flex ' . ($param['w_flow'] == 'hor' ? ($param['w_rowcol'] > 1 ? 'wd-flex-column' : 'wd-flex-row') : ($param['w_rowcol'] > 1 ? 'wd-flex-row' : 'wd-flex-column')) . '">';
    $html .= '<input type="hidden" name="wdform_' . $id1 . '_element' . $form_id . '" value="" />';
    $total_queries = 0;
    foreach ( $param['w_choices'] as $key => $choice ) {
      $key1 = $key + $total_queries;
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        foreach ( $custom_fields as $custom_key => $custom_val ) {
          $key_replace = array( '%' . $custom_key . '%', '{' . $custom_key . '}' );
          $where_str = str_replace($key_replace, $custom_val, $where_str);
        }
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
        if ( $select_data_from_db && $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
        }
        $value_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $param['w_choices_value'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $select_data_from_db && $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
        }
        $columns_count_checkbox = count($choices_labels) > 0 ? count($choices_labels) : count($choices_values);
        if ( array_filter($choices_labels) || array_filter($choices_values) ) {
          $total_queries = $total_queries + $columns_count_checkbox - 1;
          for ( $k = 0; $k < $columns_count_checkbox; $k++ ) {
            $choice_label = isset($choices_labels[$k]) ? $choices_labels[$k] : '';
            $choice_value = isset($choices_values[$k]) ? $choices_values[$k] : $choice_label;
            if ( $param['w_rowcol'] > 1 && ($key1 + $k) % $param['w_rowcol'] == 0 && ($key1 + $k) > 0 ) {
              $html .= '</div><div class="wd-flex ' . ($param['w_flow'] == 'hor' ? 'wd-flex-column' : 'wd-flex-row') . '">';
            }
            if ( isset($post_value) ) {
              $param['w_choices_checked'][$key] = (isset($_POST['wdform_' . $id1 . "_element" . $form_id . ($key1 + $k)]) ? 'checked="checked"' : '');
            }
            else {
              $param['w_choices_checked'][$key] = (($param['w_choices_checked'][$key] == 'true' && !array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . ($key1 + $k), $_GET)) || array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . ($key1 + $k), $_GET) ? 'checked="checked"' : '');
            }
            $html .= '<div class="checkbox-div wd-choice wd-flex ' . (($param['w_field_option_pos'] == "right") ? 'wd-flex-row' : 'wd-flex-row-reverse wd-justify-content') . '">';
            $html .= '<input type="checkbox" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '" name="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '" value="' . htmlspecialchars($choice_value[0]) . '" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'onclick="if(set_checked(&quot;wdform_' . $id1 . '&quot;,&quot;' . ($key1 + $k) . '&quot;,&quot;' . $form_id . '&quot;)) show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);"' : '') . ' ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' />';
            $html .= '<label class="wd-align-items-center wd-flex ' . ($param['w_field_option_pos'] == "right" ? "wd-flex-row" : "wd-flex-row-reverse wd-justify-content wd-width-100") . '" for="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '"><span></span>' . $choice_label[0] . '</label>';
            $html .= '</div>';
          }
        }
      }
      else {
        if ( $param['w_rowcol'] > 1 && (($key1 % $param['w_rowcol']) == 0) && $key1 > 0 ) {
          $html .= '</div><div class="wd-flex ' . ($param['w_flow'] == 'hor' ? 'wd-flex-column' : 'wd-flex-row') . '">';
        }
        if ( !isset($post_value) ) {
          $param['w_choices_checked'][$key] = (($param['w_choices_checked'][$key] == 'true' && !array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_POST)) || array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_GET) ? 'checked="checked"' : '');
        }
        else {
          $param['w_choices_checked'][$key] = array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_GET) ? 'checked="checked"' : '';
        }
        $choice_value = isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice;
        $html .= '<div class="checkbox-div wd-choice wd-flex ' . (($param['w_field_option_pos'] == "right") ? 'wd-flex-row' : 'wd-flex-row-reverse wd-justify-content') . '">';
        $html .= '<input type="checkbox" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '" name="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '" value="' . htmlspecialchars(html_entity_decode($choice_value)) . '" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'onclick="if(set_checked(&quot;wdform_' . $id1 . '&quot;,&quot;' . $key1 . '&quot;,&quot;' . $form_id . '&quot;)) show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);"' : '') . ' ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' />';
        $html .= '<label class="wd-align-items-center wd-flex ' . ($param['w_field_option_pos'] == "right" ? "wd-flex-row" : "wd-flex-row-reverse wd-justify-content wd-width-100") . '" for="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '"><span></span>' . $choice . '</label>';
        if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key && array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_GET) ) {
          $html .= '<br id="wdform_' . $id1 . '_other_br' . $form_id . '"/>';
          $html .= '<input id="wdform_' . $id1 . '_other_input' . $form_id . '" name="wdform_' . $id1 . '_other_input' . $form_id . '" value="' . WDW_FM_Library(self::PLUGIN)->get('wdform_' . $id1 . "_element" . $form_id . '' . $key1, "", 'esc_html') . '" type="text" class="other_input" onchange="other_input_change(this, ' . $form_id . ', ' . $id1 . ')">';
        }
        $html .= '</div>';
        $param['w_allow_other_num'] = $param['w_allow_other_num'] == $key ? $key1 : $param['w_allow_other_num'];
      }
    }
    $html .= '</div>';
    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);

    return $rep;
  }

  /**
   * Type radio.
   *
   * @param array  $params
   * @param array  $row
   * @param int    $form_id
   * @param int    $id1
   * @param string $type
   * @param array  $param
   *
   * @return string
   */
  function type_radio( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    //ToDo custom_fields add to params array key next version.
    $custom_fields = WDW_FM_Library::get_custom_fields();
    $select_data_from_db = TRUE;
    if ( !empty($param['reset_fields']) && in_array($id1, $param['reset_fields']) ) {
      $select_data_from_db = FALSE;
    }
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
      if ( $params_name == 'w_choices' ) {
        $param[$params_name] = strip_tags($temp[0], "<b><strong><span><a>");
      } else {
        $param[$params_name] = esc_html($temp[0]);
      }

      $temp = $temp[1];
    }
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    if ( !isset($param['w_value_disabled']) ) {
      $param['w_value_disabled'] = 'no';
    }
    if ( !isset($param['w_field_option_pos']) ) {
      $param['w_field_option_pos'] = 'left';
    }
    $param['w_choices'] = explode('***', $param['w_choices']);
    $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
    if ( isset($param['w_choices_value']) ) {
      $param['w_choices_value'] = explode('***', $param['w_choices_value']);
      $param['w_choices_params'] = explode('***', $param['w_choices_params']);
    }
    $post_value = WDW_FM_Library(self::PLUGIN)->get("counter" . $form_id, NULL, 'esc_html');
    $param['id'] = '';
    $param['w_class'] .= ' wd-flex-wrap' . ($param['w_flow'] != 'hor' ? ' wd-flex-column' : ' wd-flex-row');
    $html = '<div class="wd-flex ' . ($param['w_flow'] == 'hor' ? ($param['w_rowcol'] > 1 ? 'wd-flex-column' : 'wd-flex-row') : ($param['w_rowcol'] > 1 ? 'wd-flex-row' : 'wd-flex-column')) . '">';
    $html .= '<input type="hidden" name="wdform_' . $id1 . '_element' . $form_id . '" value="" />';
    $total_queries = 0;
    foreach ( $param['w_choices'] as $key => $choice ) {
      $key1 = $key + $total_queries;
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        foreach ( $custom_fields as $custom_key => $custom_val ) {
          $key_replace = array( '%' . $custom_key . '%', '{' . $custom_key . '}' );
          $where_str = str_replace($key_replace, $custom_val, $where_str);
        }
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
        if ( $select_data_from_db && $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
        }
        $value_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $param['w_choices_value'][$key]));
        $value_column = $value_table_and_column[1];
        if ( $select_data_from_db && $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
        }
        $columns_count_radio = count($choices_labels) > 0 ? count($choices_labels) : count($choices_values);
        if ( array_filter($choices_labels) || array_filter($choices_values) ) {
          $total_queries = $total_queries + $columns_count_radio - 1;
          for ( $k = 0; $k < $columns_count_radio; $k++ ) {
            $choice_label = isset($choices_labels[$k]) ? $choices_labels[$k] : '';
            $choice_value = isset($choices_values[$k]) ? $choices_values[$k] : $choice_label;
            if ( $param['w_rowcol'] > 1 && ($key1 + $k) % $param['w_rowcol'] == 0 && ($key1 + $k) > 0 ) {
              $html .= '</div><div class="wd-flex ' . ($param['w_flow'] == 'hor' ? 'wd-flex-column' : 'wd-flex-row') . '">';
            }
            if ( isset($post_value) ) {
              //              $param['w_choices_checked'][$key] = (isset($_POST['wdform_' . $id1 . "_element" . $form_id]) ? 'checked="checked"' : '');
              //              ToDo how take out which of radiobutton's is selected if POST contains same [name] for all buttons?
              $param['w_choices_checked'][$key] = '';
            }
            else {
              $param['w_choices_checked'][$key] = (($param['w_choices_checked'][$key] == 'true' && !array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . ($key1 + $k), $_GET)) || array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . ($key1 + $k), $_GET) ? 'checked="checked"' : '');
            }
            $html .= '<div class="radio-div wd-choice wd-flex ' . (($param['w_field_option_pos'] == "right") ? 'wd-flex-row' : 'wd-flex-row-reverse wd-justify-content') . '">';
            $html .= '<input type="radio" ';
            $html .= (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '');
            $html .= ' id="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k);
            $html .= '" name="wdform_' . $id1 . '_element' . $form_id;
            $html .= '" value="' . htmlspecialchars($choice_value[0]);
            $html .= '" onclick="set_default(&quot;wdform_' . $id1 . '&quot;,&quot;' . ($key1 + $k) . '&quot;,&quot;' . $form_id . '&quot;); ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);' : '') . '" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' />';
            $html .= '<label class="wd-align-items-center wd-flex ' . ($param['w_field_option_pos'] == "right" ? "wd-flex-row" : "wd-flex-row-reverse wd-justify-content wd-width-100") . '" for="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '"><span></span>' . $choice_label[0] . '</label>';
            $html .= '</div>';
          }
        }
      }
      else {
        if ( $param['w_rowcol'] > 1 && (($key1 % $param['w_rowcol']) == 0) && $key1 > 0 ) {
          $html .= '</div><div class="wd-flex ' . ($param['w_flow'] == 'hor' ? 'wd-flex-column' : 'wd-flex-row') . '">';
        }
        if ( !isset($post_value) ) {
          $param['w_choices_checked'][$key] = (($param['w_choices_checked'][$key] == 'true' && !array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_GET)) || array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_GET) ? 'checked="checked"' : '');
        }
        else {
          $param['w_choices_checked'][$key] = (htmlspecialchars($choice) == htmlspecialchars(stripslashes(WDW_FM_Library(self::PLUGIN)->get('wdform_' . $id1 . "_element" . $form_id . '' . $key1, "", 'esc_html'))) ? 'checked="checked"' : '');
        }
        $choice_value = isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice;
        $html .= '<div class="radio-div wd-choice wd-flex ' . (($param['w_field_option_pos'] == "right") ? 'wd-flex-row' : 'wd-flex-row-reverse wd-justify-content') . '">';
        $html .= '<input type="radio" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . htmlspecialchars(html_entity_decode($choice_value)) . '" onclick="set_default(&quot;wdform_' . $id1 . '&quot;,&quot;' . $key1 . '&quot;,&quot;' . $form_id . '&quot;); ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);' : '') . '" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . ' />';
        $html .= '<label class="wd-align-items-center wd-flex ' . ($param['w_field_option_pos'] == "right" ? "wd-flex-row" : "wd-flex-row-reverse wd-justify-content wd-width-100") . '" for="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '"><span></span>' . $choice . '</label>';
        if ( $param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key && array_key_exists('wdform_' . $id1 . "_element" . $form_id . '' . $key1, $_GET) ) {
          $html .= '<br id="wdform_' . $id1 . '_other_br' . $form_id . '"/>';
          $html .= '<input id="wdform_' . $id1 . '_other_input' . $form_id . '" name="wdform_' . $id1 . '_other_input' . $form_id . '" value="' . WDW_FM_Library(self::PLUGIN)->get('wdform_' . $id1 . "_element" . $form_id . '' . $key1, "", 'esc_html') . '" type="text" class="other_input" onchange="other_input_change(this, ' . $form_id . ', ' . $id1 . ')">';
        }
        $html .= '</div>';
        $param['w_allow_other_num'] = $param['w_allow_other_num'] == $key ? $key1 : $param['w_allow_other_num'];
      }
    }
    $html .= '</div>';
    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);

    return $rep;
  }

  /**
   * Type own select.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  function type_own_select( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    //ToDo custom_fields add to params array key next version.
    $custom_fields = WDW_FM_Library::get_custom_fields();
    $select_data_from_db = TRUE;
    if ( !empty($param['reset_fields']) && in_array($id1, $param['reset_fields']) ){
      $select_data_from_db = FALSE;
    }
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
      $param[$params_name] = esc_html($temp[0]);
      $temp = $temp[1];
    }
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
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
    $post_value = WDW_FM_Library(self::PLUGIN)->get( "counter" . $form_id, NULL, 'esc_html' );
    $param['id'] = $id1;
    $param['w_class'] .= ' wd-flex-row';
    $html = '<select class="wd-width-100" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" ' . $param['attributes'] . '>';
    foreach ( $param['w_choices'] as $key => $choice ) {
      if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
        $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
        $where_str = $w_choices_params[0];
        foreach ( $custom_fields as $custom_key => $custom_val ) {
          $key_replace = array( '%' . $custom_key . '%', '{' . $custom_key . '}' );
          $where_str = str_replace( $key_replace, $custom_val, $where_str );
        }
        $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array('[', ']'), '', $where_str) : '');
        $w_choices_params = explode('[db_info]', $w_choices_params[1]);
        $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
        $db_info = $w_choices_params[1];
        $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
        $table = $label_table_and_column[0];
        $label_column = $label_table_and_column[1];
        if ( $select_data_from_db && $label_column ) {
          $choices_labels = WDW_FM_Library::select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
        }
        $value_table_and_column = explode(':', str_replace(array('[', ']'), '', $param['w_choices_value'][$key]));
        $value_column = $param['w_choices_disabled'][$key] == "true" ? '' : $value_table_and_column[1];
        if ( $select_data_from_db && $value_column ) {
          $choices_values = WDW_FM_Library::select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
        }
        $columns_count = count($choices_labels) > 0 ? count($choices_labels) : count($choices_values);
        if ( array_filter($choices_labels) || array_filter($choices_values) ) {
          for ( $k = 0; $k < $columns_count; $k++ ) {
            $choice_label = isset($choices_labels[$k]) ? $choices_labels[$k] : '';
            $choice_value = isset($choices_values[$k]) ? $choices_values[$k] : ($param['w_choices_disabled'][$key] == "true" ? '' : $choice_label);
            if ( !isset($post_value) ) {
              $param['w_choices_checked'][$key] = (($param['w_choices_checked'][$key] == 'true' && $k == 0) ? 'selected="selected"' : '');
            }
            else {
              $param['w_choices_checked'][$key] = ((isset($_POST['wdform_' . $id1 . "_element" . $form_id]) && $choice_value == htmlspecialchars($_POST['wdform_' . $id1 . "_element" . $form_id])) ? 'selected="selected"' : '');
            }
            $html .= '<option value="' . htmlspecialchars($choice_value[0]) . '" ' . $param['w_choices_checked'][$key] . '>' . $choice_label[0] . '</option>';
          }
        }
      }
      else {
        if ( !isset($post_value) ) {
          $param['w_choices_checked'][$key] = ($param['w_choices_checked'][$key] == 'true' ? 'selected="selected"' : '');
        }
        else {
          $param['w_choices_checked'][$key] = ((isset($_POST['wdform_' . $id1 . "_element" . $form_id]) && htmlspecialchars($choice) == htmlspecialchars($_POST['wdform_' . $id1 . "_element" . $form_id])) ? 'selected="selected"' : '');
        }
        $choice_value = $param['w_choices_disabled'][$key] == "true" ? '' : (isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice);
        $html .= '<option value="' . htmlspecialchars(html_entity_decode($choice_value)) . '" ' . $param['w_choices_checked'][$key] . '>' . $choice . '</option>';
      }
    }
    $html .= '</select>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type date new.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_date_new( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend-momentjs');
    if ( function_exists('wp_add_inline_script') ) { // Since Wordpress 4.5.0
      wp_add_inline_script('jquery-ui-datepicker', WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker());
    }
    else {
      echo '<script>' . WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker() . '</script>';
    }

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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $default_date = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_element" . $form_id, $param['w_default_date'], 'esc_html' ) );
    $param['id'] = $id1;
    $param['w_class'] .= ' wd-flex-row';

    $html = '<input class="wd-width-100" autocomplete="off" onchange="wd_validate(this)" data-form-id="' . $form_id . '" data-wdid="' . $id1 . '" data-valid-type="date" type="text" id="wdform_' . $id1 . '_element' . $form_id . '" data-value="' . $default_date . '" value="' . $default_date . '" name="wdform_' . $id1 . '_element' . $form_id . '"  ' . $param['attributes'] . ' />';
    $html .= '<span id="fm-calendar-' . $id1 . '" class="dashicons dashicons-calendar-alt wd-calendar-button ' . ($param['w_show_image'] == "yes" ? "wd-inline-block" : "wd-hidden") . '"></span>';
    $html .= '<input type="hidden" class="hidden_date" data-format="' . $param['w_format'] . '" data-min="' . $param['w_min_date'] . '" data-max="' . $param['w_max_date'] . '" id="wdform_' . $id1 . '_button' . $form_id . '" value="' . $default_date . '" />';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type date fields.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_date_fields( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend-momentjs');
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
    if ( strpos($temp, 'w_min_year') > -1 && strpos($temp, 'w_min_month') > -1 && strpos($temp, 'w_min_day') > -1 && strpos($temp, 'w_min_dob_alert') > -1 ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $param['w_day'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_day" . $form_id, $param['w_day'], 'esc_html' ) );
    $param['w_month'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_month" . $form_id, $param['w_month'], 'esc_html' ) );
    $param['w_year'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_year" . $form_id, $param['w_year'], 'esc_html' ) );

    if ( $param['w_day_type'] == "SELECT" ) {
      $w_day_type = '<select id="wdform_' . $id1 . '_day' . $form_id . '" name="wdform_' . $id1 . '_day' . $form_id . '" class="wdform_select_day" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . '><option value=""></option>';
      for ( $k = 1; $k <= 31; $k++ ) {
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
      $w_day_type = '<input type="number"
													 class="wd-type-number wdform_input_day"
													 id="wdform_' . $id1 . '_day' . $form_id . '"
													 name="wdform_' . $id1 . '_day' . $form_id . '"
													 onchange="wd_validate(this)"
													 data-addiotional-fields=\'' . json_encode(array('wdform_' . $id1 . '_month' . $form_id, 'wdform_' . $id1 . '_year' . $form_id)) . '\'
													 data-valid-type="day"
													 data-form-id="' . $form_id . '"
													 data-wdid="' . $id1 . '"
													 style="width: ' . $param['w_day_size'] . 'px;"
													 value="' . $param['w_day'] . '"
													' . $param['attributes'] . ' />';
    }
    $w_day_type .= '<label for="wdform_' . $id1 . '_day' . $form_id . '" class="mini_label">' . $param['w_day_label'] . '</label>';

    if ( $param['w_month_type'] == "SELECT" ) {
      $w_month_type = '<select id="wdform_' . $id1 . '_month' . $form_id . '" name="wdform_' . $id1 . '_month' . $form_id . '" class="wdform_select_month" style="width: ' . $param['w_month_size'] . 'px;" ' . $param['attributes'] . '><option value=""></option><option value="01" ' . ($param['w_month'] == "01" ? "selected=\"selected\"" : "") . '  >' . (__("January", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="02" ' . ($param['w_month'] == "02" ? "selected=\"selected\"" : "") . '>' . (__("February", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="03" ' . ($param['w_month'] == "03" ? "selected=\"selected\"" : "") . '>' . (__("March", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="04" ' . ($param['w_month'] == "04" ? "selected=\"selected\"" : "") . ' >' . (__("April", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="05" ' . ($param['w_month'] == "05" ? "selected=\"selected\"" : "") . ' >' . (__("May", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="06" ' . ($param['w_month'] == "06" ? "selected=\"selected\"" : "") . ' >' . (__("June", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="07" ' . ($param['w_month'] == "07" ? "selected=\"selected\"" : "") . ' >' . (__("July", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="08" ' . ($param['w_month'] == "08" ? "selected=\"selected\"" : "") . ' >' . (__("August", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="09" ' . ($param['w_month'] == "09" ? "selected=\"selected\"" : "") . ' >' . (__("September", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="10" ' . ($param['w_month'] == "10" ? "selected=\"selected\"" : "") . ' >' . (__("October", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="11" ' . ($param['w_month'] == "11" ? "selected=\"selected\"" : "") . '>' . (__("November", WDFMInstance(self::PLUGIN)->prefix)) . '</option><option value="12" ' . ($param['w_month'] == "12" ? "selected=\"selected\"" : "") . ' >' . (__("December", WDFMInstance(self::PLUGIN)->prefix)) . '</option></select>';
    }
    else {
      $w_month_type = '<input type="number"
															class="wd-type-number wdform_input_month"
															id="wdform_' . $id1 . '_month' . $form_id . '"
															name="wdform_' . $id1 . '_month' . $form_id . '"
															onchange="wd_validate(this)"
															data-addiotional-fields=\'' . json_encode(array('wdform_' . $id1 . '_day' . $form_id, 'wdform_' . $id1 . '_year' . $form_id)) . '\'
															data-valid-type="month"
															data-form-id="' . $form_id . '"
															data-wdid="' . $id1 . '"
															style="width: ' . $param['w_month_size'] . 'px;"
															value="' . $param['w_month'] . '"
															' . $param['attributes'] . ' />';
    }
    $w_month_type .= '<label for="wdform_' . $id1 . '_month' . $form_id . '" class="mini_label">' . $param['w_month_label'] . '</label>';

    $param['w_to'] = isset($param['w_to']) && $param['w_to'] != "NaN" ? $param['w_to'] : date("Y");
    $param['w_from'] = isset($param['w_from']) && $param['w_from'] != "NaN" ? $param['w_from'] : date("Y");
    if ( $param['w_year_type'] == "SELECT" ) {
      $w_year_type = '<select id="wdform_' . $id1 . '_year' . $form_id . '" name="wdform_' . $id1 . '_year' . $form_id . '"  from="' . $param['w_from'] . '" to="' . $param['w_to'] . '" class="wdform_select_year" style="width: ' . $param['w_year_size'] . 'px;" ' . $param['attributes'] . '><option value=""></option>';
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
      $w_year_type = '<input type="number"
														 class="wd-type-number  wdform_input_year"
														 id="wdform_' . $id1 . '_year' . $form_id . '"
														 name="wdform_' . $id1 . '_year' . $form_id . '"
														 onchange="wd_validate(this)"
														 data-addiotional-fields=\'' . json_encode(array('wdform_' . $id1 . '_day' . $form_id, 'wdform_' . $id1 . '_month' . $form_id)) . '\'
														 data-valid-type="year"
														 data-form-id="' . $form_id . '"
														 data-wdid="' . $id1 . '"
														 value="' . $param['w_year'] . '"
														 from="' . $param['w_from'] . '"
														 to="' . $param['w_to'] . '"
														 style="width: ' . $param['w_year_size'] . 'px;"
														 ' . $param['attributes'] . ' />';
    }
    $w_year_type .= '<label for="wdform_' . $id1 . '_year' . $form_id . '" class="mini_label">' . $param['w_year_label'] . '</label>';

    $param['id'] = 'wdform_' . $id1 . '_day' . $form_id;
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div class="wd-flex wd-flex-row wd-width-100 wd-align-items">';
    $html .= '<div class="wd-flex wd-flex-column">';
    $html .= $w_day_type;
    $html .= '</div>';
    $html .= '<span class="wdform_separator">' . $param['w_divider'] . '</span>';
    $html .= '<div class="wd-flex wd-flex-column">';
    $html .= $w_month_type;
    $html .= '</div>';
    $html .= '<span class="wdform_separator">' . $param['w_divider'] . '</span>';
    $html .= '<div class="wd-flex wd-flex-column">';
    $html .= $w_year_type;
    $html .= '</div>';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type date range
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_date_range( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-frontend-momentjs');
    if ( function_exists('wp_add_inline_script') ) { // Since Wordpress 4.5.0
      wp_add_inline_script('jquery-ui-datepicker', WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker());
    }
    else {
      echo '<script>' . WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker() . '</script>';
    }

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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }

    $param['id'] = 'wdform_' . $id1 . '_element' . $form_id . '0';
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div class="wd-flex wd-flex-row wd-align-items-center wd-width-50">';
    $html .= '<input class="wd-width-100" autocomplete="off" type="text" id="wdform_' . $id1 . '_element' . $form_id . '0" name="wdform_' . $id1 . '_element' . $form_id . '0" ' . $param['attributes'] . ' onchange="change_value_range(\'wdform_' . $id1 . '_element' . $form_id . '1\', \'minDate\', this.value, \'' . $param['w_min_date'] . '\', \'' . $param['w_format'] . '\', this)" data-form-id="' . $form_id . '" data-wdid="' . $id1 . '" />';
    $html .= '<span id="button_calendar_' . $id1 . '0" class="dashicons dashicons-calendar-alt wd-calendar-button ' . ($param['w_show_image'] == "yes" ? "wd-inline-block" : "wd-hidden") . '"></span>';
    $html .= '</div>';
    $html .= '<div class="wd-flex wd-flex-row wd-align-items-center wd-name-separator"></div>';
    $html .= '<div class="wd-flex wd-flex-row wd-align-items-center wd-width-50">';
    $html .= '<input class="wd-width-100" autocomplete="off" type="text" id="wdform_' . $id1 . '_element' . $form_id . '1" name="wdform_' . $id1 . '_element' . $form_id . '1" ' . $param['attributes'] . ' onchange="change_value_range(\'wdform_' . $id1 . '_element' . $form_id . '0\', \'maxDate\', this.value, \'' . $param['w_max_date'] . '\', \'' . $param['w_format'] . '\', this)"  data-form-id="' . $form_id . '" data-wdid="' . $id1 . '" />';
    $html .= '<span id="button_calendar_' . $id1 . '1" class="dashicons dashicons-calendar-alt wd-calendar-button ' . ($param['w_show_image'] == "yes" ? "wd-inline-block" : "wd-hidden") . '"></span>';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type time.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_time( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $w_mini_labels = explode('***', $param['w_mini_labels']);

    $param['id'] = 'wdform_' . $id1 . '_hh' . $form_id;
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div class="wd-flex wd-flex-column">';
    $html .= '<input type="number"
                           class="time_box wd-type-number"
                           id="wdform_' . $id1 . '_hh' . $form_id . '"
                           name="wdform_' . $id1 . '_hh' . $form_id . '"
                           onkeyup="wd_validate(this)"
                           data-addiotional-fields=\'' . json_encode(array('wdform_' . $id1 . '_mm' . $form_id, 'wdform_' . $id1 . '_ss' . $form_id)) . '\'
                           data-valid-type="' . ($param['w_time_type'] == '12' ? 'hour12' : 'hour24') . '"
                           data-form-id="' . $form_id . '"
                           data-wdid="' . $id1 . '"
                           data-value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_hh" . $form_id, $param['w_hh'], 'esc_html' ) ) . '"
                           value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_hh" . $form_id, $param['w_hh'], 'esc_html' ) ) . '"
                           ' . $param['attributes'] . ' />';
    $html .= '<label for="wdform_' . $id1 . '_hh' . $form_id . '" class="mini_label">' . $w_mini_labels[0] . '</label>';
    $html .= '</div>';
    $html .= '<div class="wd-flex wd-flex-column">&nbsp;:&nbsp;</div>';
    $html .= '<div class="wd-flex wd-flex-column">';
    $html .= '<input type="number"
                           class="time_box wd-type-number"
                           id="wdform_' . $id1 . '_mm' . $form_id . '"
                           name="wdform_' . $id1 . '_mm' . $form_id . '"
                           onkeyup="wd_validate(this)"
                           data-addiotional-fields=\'' . json_encode(array('wdform_' . $id1 . '_hh' . $form_id, 'wdform_' . $id1 . '_ss' . $form_id)) . '\'
                           data-valid-type="minute"
                           data-form-id="' . $form_id . '"
                           data-wdid="' . $id1 . '"
                           data-value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_mm" . $form_id, $param['w_mm'], 'esc_html' ) ) . '"
                           value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_mm" . $form_id, $param['w_mm'], 'esc_html' ) ) . '"
                           ' . $param['attributes'] . ' />';
    $html .= '<label for="wdform_' . $id1 . '_mm' . $form_id . '" class="mini_label">' . $w_mini_labels[1] . '</label>';
    $html .= '</div>';
    if ( $param['w_sec'] == '1' ) {
      $html .= '<div class="wd-flex wd-flex-column">&nbsp;:&nbsp;</div>';
      $html .= '<div class="wd-flex wd-flex-column">';
      $html .= '<input type="number"
                       class="time_box wd-type-number"
                       id="wdform_' . $id1 . '_ss' . $form_id . '"
                       name="wdform_' . $id1 . '_ss' . $form_id . '"
                       onkeyup="wd_validate(this)"
                       data-addiotional-fields=\'' . json_encode(array('wdform_' . $id1 . '_mm' . $form_id, 'wdform_' . $id1 . '_hh' . $form_id)) . '\'
                       data-valid-type="second"
                       data-form-id="' . $form_id . '"
                       data-wdid="' . $id1 . '"
                       data-value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_ss" . $form_id, $param['w_ss'], 'esc_html' ) ) . '"
                       value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_ss" . $form_id, $param['w_ss'], 'esc_html' ) ) . '"
                       ' . $param['attributes'] . ' />';
      $html .= '<label for="wdform_' . $id1 . '_ss' . $form_id . '" class="mini_label">' . $w_mini_labels[2] . '</label>';
      $html .= '</div>';
    }
    if ( $param['w_time_type'] == '12' ) {
      if ( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_am_pm" . $form_id, $param['w_am_pm'], 'esc_html') == 'am' ) {
        $am_ = "selected=\"selected\"";
        $pm_ = "";
      }
      else {
        $am_ = "";
        $pm_ = "selected=\"selected\"";
      }
      $html .= '<div class="wd-flex wd-flex-column">&nbsp;&nbsp;</div>';
      $html .= '<div class="wd-flex wd-flex-column">';
      $html .= '<select class="am_pm_select" name="wdform_' . $id1 . '_am_pm' . $form_id . '" id="wdform_' . $id1 . '_am_pm' . $form_id . '" ' . $param['attributes'] . '>';
      $html .= '<option value="am" ' . $am_ . '>' . __('AM', WDFMInstance(self::PLUGIN)->prefix) . '</option>';
      $html .= '<option value="pm" ' . $pm_ . '>' . __('PM', WDFMInstance(self::PLUGIN)->prefix) . '</option>';
      $html .= '</select>';
      $html .= '<label class="mini_label">' . $w_mini_labels[3] . '</label>';
      $html .= '</div>';
    }

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type country.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_country( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $param['w_countries'] = explode('***', $param['w_countries']);
    $post_value = WDW_FM_Library(self::PLUGIN)->get( "counter" . $form_id, NULL, 'esc_html' );
    $param['id'] = $id1;
    $param['w_class'] .= ' wd-flex-row';

    $selected = '';

    $html = '<select class="wd-width-100" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" ' . $param['attributes'] . '>';
    foreach ( $param['w_countries'] as $key => $choice ) {
      if ( isset($post_value) ) {
        $selected = (htmlspecialchars($choice) == htmlspecialchars(stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . "_element" . $form_id, "", 'esc_html' )  ) ) ? 'selected="selected"' : '');
      }
      $choice_value = $choice;
      $html .= '<option value="' . $choice_value . '" ' . $selected . '>' . $choice . '</option>';
    }
    $html .= '</select>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type spinner.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_spinner( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    wp_enqueue_script('jquery-ui-spinner');

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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $param['w_field_value'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id, $param['w_field_value'], 'esc_html' ) );

    $param['id'] = $id1;
    $param['w_class'] .= ' wd-flex-row';

    $html = '<input
                            name="wdform_' . $id1 . '_element' . $form_id . '"
                            id="wdform_' . $id1 . '_element' . $form_id . '"
                            class="wd-type-number"
                            onkeyup="wd_validate(this)"
                            data-valid-type="number"
                            data-form-id="' . $form_id . '"
                            data-wdid="' . $id1 . '"
                            style="width: ' . $param['w_field_width'] . 'px;"
                            data-value="' . ($param['w_field_value'] != 'null' ? $param['w_field_value'] : '') . '"
                            value="' . ($param['w_field_value'] != 'null' ? $param['w_field_value'] : '') . '"
                            ' . $param['attributes'] . ' />';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type star rating.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_star_rating( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $images = '';
    for ( $i = 0; $i < $param['w_star_amount']; $i++ ) {
      $images .= '<img id="wdform_' . $id1 . '_star_' . $i . '_' . $form_id . '" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star.png" />';
    }

    $param['id'] = '';
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div id="wdform_' . $id1 . '_element' . $form_id . '" ' . $param['attributes'] . '>';
    for ( $i = 0; $i < $param['w_star_amount']; $i++ ) {
      $html .= '<img id="wdform_' . $id1 . '_star_' . $i . '_' . $form_id . '" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star.png" />';
    }
    $html .= '</div>';
    $html .= '<input type="hidden" value="" id="wdform_' . $id1 . '_selected_star_amount' . $form_id . '" name="wdform_' . $id1 . '_selected_star_amount' . $form_id . '" />';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type scale rating.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_scale_rating( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $w_mini_labels = explode('***', $param['w_mini_labels']);
    $numbers = '';
    $radio_buttons = '';
    $to_check = 0;
    $post_value = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_scale_radio' . $form_id, NULL, 'esc_html' ) );
    if ( isset($post_value) ) {
      $to_check = $post_value;
    }
    for ( $i = 1; $i <= $param['w_scale_amount']; $i++ ) {
      $numbers .= '<div class="wd-table-cell fm-align-center"><span>' . $i . '</span></div>';
      $radio_buttons .= '<div class="wd-table-cell fm-align-center"><div class="radio-div"><input id="wdform_' . $id1 . '_scale_radio' . $form_id . '_' . $i . '" name="wdform_' . $id1 . '_scale_radio' . $form_id . '" value="' . $i . '" type="radio" ' . ($to_check == $i ? 'checked="checked"' : '') . ' /><label for="wdform_' . $id1 . '_scale_radio' . $form_id . '_' . $i . '"><span></span></label></div></div>';
    }

    $param['id'] = '';
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div id="wdform_' . $id1 . '_element' . $form_id . '" class="wd-flex wd-flex-row wd-align-items-center wd-flex-wrap" ' . $param['attributes'] . '>';
    $html .= '<label class="mini_label">' . $w_mini_labels[0] . '</label>';
    for ( $i = 1; $i <= $param['w_scale_amount']; $i++ ) {
      $html .= '<div class="wd-flex wd-flex-column wd-align-items-center">';
      $html .= '<span>' . $i . '</span>';
      $html .= '<div class="radio-div">';
      $html .= '<input id="wdform_' . $id1 . '_scale_radio' . $form_id . '_' . $i . '" name="wdform_' . $id1 . '_scale_radio' . $form_id . '" value="' . $i . '" type="radio" ' . ($to_check == $i ? 'checked="checked"' : '') . ' />';
      $html .= '<label for="wdform_' . $id1 . '_scale_radio' . $form_id . '_' . $i . '"><span></span></label>';
      $html .= '</div>';
      $html .= '</div>';
    }
    $html .= '<label class="mini_label">' . $w_mini_labels[1] . '</label>';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type slider.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_slider( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-jquery-ui-touch-punch');

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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $param['w_field_value'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_slider_value' . $form_id, $param['w_field_value'], 'esc_html' ) );

    $param['id'] = '';
    $param['w_class'] .= ' wd-flex-column';

    $html = '<input type="hidden" value="' . $param['w_field_value'] . '" id="wdform_' . $id1 . '_slider_value' . $form_id . '" name="wdform_' . $id1 . '_slider_value' . $form_id . '" />';
    $html .= '<div class="wd-width-100" name="' . $id1 . '_element' . $form_id . '" id="wdform_' . $id1 . '_element' . $form_id . '" style="max-width: ' . $param['w_field_width'] . 'px;" ' . $param['attributes'] . '></div>';
    $html .= '<div class="wd-flex wd-flex-row wd-width-100" style="max-width: ' . $param['w_field_width'] . 'px;" >';
    $html .= '<div class="wd-width-33 fm-align-left">';
    $html .= '<span id="wdform_' . $id1 . '_element_min' . $form_id . '" class="label">' . $param['w_field_min_value'] . '</span>';
    $html .= '</div>';
    $html .= '<div class="wd-width-33 fm-align-center">';
    $html .= '<span id="wdform_' . $id1 . '_element_value' . $form_id . '" class="label">' . $param['w_field_value'] . '</span>';
    $html .= '</div>';
    $html .= '<div class="wd-width-33 fm-align-right">';
    $html .= '<span id="wdform_' . $id1 . '_element_max' . $form_id . '" class="label">' . $param['w_field_max_value'] . '</span>';
    $html .= '</div>';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type range.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_range( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
    wp_enqueue_script('jquery-ui-spinner');

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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $param['w_field_value1'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id . '0', $param['w_field_value1'], 'esc_html' ) );
    $param['w_field_value2'] = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id . '1', $param['w_field_value2'], 'esc_html' ) );
    $w_mini_labels = explode('***', $param['w_mini_labels']);

    $param['id'] = 'wdform_' . $id1 . '_element' . $form_id . '0';
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div class="wd-flex wd-flex-row wd-flex-wrap">';
    $html .= '<div class="wd-flex wd-flex-column">';
    $html .= '<input type="text" value="' . ($param['w_field_value1'] != 'null' ? $param['w_field_value1'] : '') . '" name="wdform_' . $id1 . '_element' . $form_id . '0" id="wdform_' . $id1 . '_element' . $form_id . '0" style="width: ' . $param['w_field_range_width'] . 'px;"  ' . $param['attributes'] . ' />';
    $html .= '<label class="mini_label" id="wdform_' . $id1 . '_mini_label_from0" for="wdform_' . $id1 . '_element' . $form_id . '0">' . $w_mini_labels[0] . '</label>';
    $html .= '</div>';
    $html .= '<div class="wd-name-separator"></div>';
    $html .= '<div class="wd-flex wd-flex-column">';
    $html .= '<input type="text" value="' . ($param['w_field_value2'] != 'null' ? $param['w_field_value2'] : '') . '" name="wdform_' . $id1 . '_element' . $form_id . '1" id="wdform_' . $id1 . '_element' . $form_id . '1" style="width: ' . $param['w_field_range_width'] . 'px;" ' . $param['attributes'] . ' />';
    $html .= '<label class="mini_label" id="wdform_' . $id1 . '_mini_label_to" for="wdform_' . $id1 . '_element' . $form_id . '1">' . $w_mini_labels[1] . '</label>';
    $html .= '</div>';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type grading.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_grading( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $w_items = explode('***', $param['w_items']);

    $param['id'] = 'wdform_' . $id1 . '_element' . $form_id . '_0';
    $param['w_class'] .= ' wd-flex-row';

    $html = '<input type="hidden" value="' . $param['w_total'] . '" name="wdform_' . $id1 . '_grading_total' . $form_id . '" id="wdform_' . $id1 . '_grading_total' . $form_id . '" />';
    $html .= '<div id="wdform_' . $id1 . '_element' . $form_id . '">';
    $html .= '<input type="hidden" name="wdform_' . $id1 . '_element' . $form_id . '" value="" />';
    for ( $i = 0; $i < count($w_items); $i++ ) {
      $value = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_element' . $form_id . '_' . $i, '', 'esc_html' ) );
      $html .= '<div class="wdform_grading">';
      $html .= '<input type="text" id="wdform_' . $id1 . '_element' . $form_id . '_' . $i . '" name="wdform_' . $id1 . '_element' . $form_id . '_' . $i . '"  value="' . $value . '" ' . $param['attributes'] . ' />';
      $html .= '<label class="wdform-ch-rad-label" for="wdform_' . $id1 . '_element' . $form_id . '_' . $i . '">' . $w_items[$i] . '</label>';
      $html .= '</div>';
    }
    $html .= '<div id="wdform_' . $id1 . '_element_total_div' . $form_id . '" class="grading_div' . ($param['w_total'] == '0' ? ' wd-hidden' : '') . '">';
    $html .= __('Total', WDFMInstance(self::PLUGIN)->prefix) . ': ';
    $html .= '<span id="wdform_' . $id1 . '_sum_element' . $form_id . '">0</span>';
    $html .= '/';
    $html .= '<span id="wdform_' . $id1 . '_total_element' . $form_id . '">' . $param['w_total'] . '</span>';
    $html .= '<span id="wdform_' . $id1 . '_text_element' . $form_id . '"></span>';
    $html .= '</div>';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type matrix.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_matrix( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
    if ( $temp ) {
      $temp = explode('*:*w_attr_name*:*', $temp);
      $attrs = array_slice($temp, 0, count($temp) - 1);
      foreach ( $attrs as $attr ) {
        $param['attributes'] = $param['attributes'] . ' ' . $attr;
      }
    }
    $param['w_textbox_size'] = isset($param['w_textbox_size']) ? $param['w_textbox_size'] : '120';
    $w_rows = explode('***', $param['w_rows']);
    $w_columns = explode('***', $param['w_columns']);
    $column_labels = '';
    for ( $i = 1; $i < count($w_columns); $i++ ) {
      $column_labels .= '<div><label class="wdform-ch-rad-label">' . $w_columns[$i] . '</label></div>';
    }
    $rows_columns = '';
    for ( $i = 1; $i < count($w_rows); $i++ ) {
      $rows_columns .= '<div class="wdform-matrix-row' . ($i % 2) . '" row="' . $i . '"><div class="wdform-matrix-column"><label class="wdform-ch-rad-label" >' . $w_rows[$i] . '</label></div>';
      for ( $k = 1; $k < count($w_columns); $k++ ) {
        $rows_columns .= '<div class="wdform-matrix-cell">';
        if ( $param['w_field_input_type'] == 'radio' ) {
          $to_check = 0;
          $post_value = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_input_element' . $form_id . '' . $i, NULL, 'esc_html' ) );
          if ( isset($post_value) ) {
            $to_check = $post_value;
          }
          $rows_columns .= '<div class="radio-div"><input id="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '"  type="radio" name="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '" value="' . $i . '_' . $k . '" ' . ($to_check == $i . '_' . $k ? 'checked="checked"' : '') . '><label for="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '"><span></span></label></div>';
        }
        else {
          if ( $param['w_field_input_type'] == 'checkbox' ) {
            $to_check = 0;
            $post_value = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k, NULL, 'esc_html' ) );
            if ( isset($post_value) ) {
              $to_check = $post_value;
            }
            $rows_columns .= '<div class="checkbox-div"><input id="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" type="checkbox" name="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" value="1" ' . ($to_check == "1" ? 'checked="checked"' : '') . '><label for="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '"><span></span></label></div>';
          }
          else {
            if ( $param['w_field_input_type'] == 'text' ) {
              $rows_columns .= '<input id="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" type="text" name="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" value="' . stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k, "", 'esc_html' ) ) . '" style="width:' . $param['w_textbox_size'] . 'px">';
            }
            else {
              if ( $param['w_field_input_type'] == 'select' ) {
                $rows_columns .= '<select id="wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k . '" name="wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k . '" ><option value="" ' . (isset($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) && esc_html($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) == "" ? "selected=\"selected\"" : "") . '> </option><option value="yes" ' . (isset($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) && esc_html($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) == "yes" ? "selected=\"selected\"" : "") . '>Yes</option><option value="no" ' . (isset($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) && esc_html($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) == "no" ? "selected=\"selected\"" : "") . '>No</option></select>';
              }
            }
          }
        }
        $rows_columns .= '</div>';
      }
      $rows_columns .= '</div>';
    }

    $param['id'] = '';
    $param['w_class'] .= ' wd-flex-row';

    $html = '<div id="wdform_' . $id1 . '_element' . $form_id . '" class="wd-width-100 wdform-matrix-table" ' . $param['attributes'] . '>';
    $html .= '<div class="wd-table-group">';
    $html .= '<div class="wdform-matrix-head">';
    $html .= '<div class="wd-table-cell"></div>';
    $html .= $column_labels;
    $html .= '</div>';
    $html .= $rows_columns;
    $html .= '</div>';
    $html .= '<input type="hidden" name="wdform_' . $id1 . '_element' . $form_id . '" value="" />';
    $html .= '</div>';

    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }

  /**
   * Type signature.
   *
   * @param array $params
   * @param array $row
   * @param int $form_id
   * @param int $id1
   * @param string $type
   * @param array $param
   * @return string
   */
  private function type_signature( $params = array(), $row = array(), $form_id = 0, $id1 = 0, $type = '', $param = array() ) {
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
      $param[$params_name] = esc_html($temp[0]);
      $temp = $temp[1];
    }

    $param['id'] = $id1;
    $param['w_class'] .= ' wd-flex-column wdform_signature wdform_' . $param['id'] . '_signature-' . $form_id;
    $field_id = 'wdform_' . $param['id'] . '_element' . $form_id;

    $html = '<div class="wd-width-100 wdform_signature-canvas wdform_' . $param['id'] . '_signature-canvas-' . $form_id . '">';
    $html .= '<canvas id="' . $field_id . '" class="fm-signature fm-signature-' . $param['id'] . '" style="max-width: ' . $param['w_canvas_width'] . 'px; width:100%; height: ' . $param['w_canvas_height'] . 'px;"></canvas>';
    $html .= '<input type="hidden" id="signature-file-' . $field_id . '" name="' . $field_id . '"  class="fm-hide" />';
    $html .= '<input type="hidden" id="signature-signs-' . $field_id . '" name="' . $field_id . '-signature"  class="fm-hide" />';
    $html .= '<button id="signature-clear-' . $field_id . '" onclick="jQuery(\'#signature-file-' . $field_id . '\').val(\'\'); jQuery(\'#signature-signs-' . $field_id . '\').val(\'\'); return false;">' . __('Clear', WDFMInstance(self::PLUGIN)->prefix) . '</button>';
    $html .= '</div>';
    // Generate field.
    $rep = $this->wdform_field($type, $param, $row, $html);
    return $rep;
  }
  /**
   * WD form field.
   *
   * @param string $type
   * @param array $param
   * @param array $row
   * @param string $html
   * @param bool $label
   * @return string
   */
  public function wdform_field( $type = '', $param = array(), $row = array(), $html = '', $label = TRUE) {
    ob_start();
    $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
    $param['w_size'] = (isset($param['w_size']) ? $param['w_size'] : "");
    $param['w_field_label_pos'] = (isset($param['w_field_label_pos']) ? $param['w_field_label_pos'] : "top");

    /* Limit Choices for Multiple Choice field */
    $param['w_limit_choice'] = (isset($param['w_limit_choice']) ? $param['w_limit_choice'] : "");
    $param['w_limit_choice_alert'] = (isset($param['w_limit_choice_alert']) ? $param['w_limit_choice_alert'] : "You have exceeded the selection limit.");

    $param['w_hide_total_currency'] = (isset($param['w_hide_total_currency']) ? $param['w_hide_total_currency'] : "");

    /* Date Of Birth Field - min values */
    $param['w_min_day'] = (isset($param['w_min_day']) ? $param['w_min_day'] : "");
    $param['w_min_month'] = (isset($param['w_min_month']) ? $param['w_min_month'] : "");
    $param['w_min_year'] = (isset($param['w_min_year']) ? $param['w_min_year'] : "");
    $param['w_min_dob_alert'] = (isset($param['w_min_dob_alert']) ? $param['w_min_dob_alert'] : "Date of birth does not meet specified requirements.");

    $classes = array('wdform-field', 'wd-width-100', 'wd-flex');
    $classes[] = ($param['w_field_label_pos'] ==  "top" ? 'wd-flex-column' : 'wd-flex-row');
    ?><div type="<?php echo $type; ?>" class="<?php echo implode(' ', $classes); ?> fm-<?php echo $type; ?>" <?php if ( $param['w_limit_choice'] !="" ) { echo 'data-limit="' . $param['w_limit_choice'] . '"'; echo 'data-limit-text="' . $param['w_limit_choice_alert'] . '"'; } if ( $param['w_hide_total_currency'] !="" ) { echo 'data-hide-currency="' . $param['w_hide_total_currency'] . '"'; } if ( $param['w_min_day'] !="" && $param['w_min_month'] !="" && $param['w_min_year'] !="" && $param['w_min_dob_alert'] !="" ) { echo 'data-min-day="' . $param['w_min_day'] . '"';  echo 'data-min-month="' . $param['w_min_month'] . '"';  echo 'data-min-year="' . $param['w_min_year'] . '"';  echo 'data-min-date-alert="' . $param['w_min_dob_alert'] . '"'; } ?>><?php
    if ( $label ) {
      echo $this->field_label($param, $row);
    }
    echo $this->field_section($html, $param);
    ?></div><?php

    return ob_get_clean();
  }
  /**
   * Return form field label HTML.
   *
   * @param array $param
   * @param array $form
   *
   * @return string
   */
  private function field_label( $param = array(), $form = array() ) {
    ob_start();
    $label = isset($param['label']) ? $param['label'] : '';
    $field_id = $param['id'];
    if ( is_numeric($field_id) ) {
      $field_id = 'wdform_' . $field_id . '_element' . $form->id;
    }
    $classes = array('wdform-label-section');
    $classes[] = isset($param['w_field_label_pos']) && $param['w_field_label_pos'] == "left" ? 'wd-width-30' : 'wd-width-100';
    if ( isset($param['w_hide_label']) && $param['w_hide_label'] == 'yes' ) {
      $classes[] = 'wd-hidden';
    }
    if ( isset($param['label_class']) ) {
      $classes[] = $param['label_class'];
    }
    if ( isset($param['w_class']) ) {
      $classes[] = $param['w_class'];
    }
    $label_width = (isset($param['w_field_label_size']) && $param['w_field_label_size'] != '') ? ' style="max-width: ' . $param['w_field_label_size'] . 'px;"' : '';
    ?><div class="<?php echo implode(' ', $classes); ?>"<?php echo $label_width; ?>>
    <label <?php echo ($field_id ? ' for="' . $field_id . '"' : ''); ?> class="wdform-label"><?php echo $label; ?></label><?php
    if ( isset($param['w_required']) && $param['w_required'] == 'yes' ) {
      $requiredmark = isset($form->requiredmark) ? $form->requiredmark : '';
      ?><span class="wdform-required"><?php echo $requiredmark; ?></span><?php
    }
    ?></div><?php

    return ob_get_clean();
  }

  /**
   * Return form field section HTML.
   *
   * @param string $html
   * @param array $param
   *
   * @return string
   */
  private function field_section( $html = '', $param = array() ) {
    ob_start();
    $classes = array( 'wdform-element-section', 'wd-flex' );
    if ( isset($param['w_class']) ) {
      $classes[] = $param['w_class'];
    }
    $classes[] = (($param['w_field_label_pos'] == "top" || $param['w_hide_label'] == "yes") ? 'wd-width-100' : 'wd-width-70');
    ?><div class="<?php echo implode(' ', $classes); ?>" <?php echo ($param['w_size'] != '' ? 'style="max-width: ' . $param['w_size'] . 'px;"' : ''); ?>><?php echo $html; ?></div><?php

    return ob_get_clean();
  }

  /**
   * Get nonce field
   *
   * @return false|string
   */
  private function get_nonce_field( $form_id = 0 ) {
    ob_start();
    wp_nonce_field(WDFMInstance(self::PLUGIN)->fm_form_nonce, WDFMInstance(self::PLUGIN)->fm_form_nonce . $form_id );

    return ob_get_clean();
  }
}
