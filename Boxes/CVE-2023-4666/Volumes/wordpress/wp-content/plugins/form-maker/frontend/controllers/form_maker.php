<?php

/**
 * Class FMControllerForm_maker
 */
class FMControllerForm_maker {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  private $view;
  private $model;
  private $form_preview = false;

  /**
   * FMControllerVerify_email constructor.
   */
  public function __construct() {
    $queried_obj = get_queried_object();
    // check is custom post type in review page.
    if ($queried_obj && isset($queried_obj->post_type) && $queried_obj->post_type == 'form-maker' && $queried_obj->post_name == 'preview') {
      $this->form_preview = true;
    }
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/frontend/models/form_maker.php";
    $this->model = new FMModelForm_maker();

    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/frontend/views/form_maker.php";
    $this->view = new FMViewForm_maker($this->model);
  }

  /**
   * Execute.
   *
   * @param int $id
   * @param string $type
   *
   * @return string|void
   */
  public function execute( $id = 0, $type = 'embedded' ) {
    $action = WDW_FM_Library(self::PLUGIN)->get('action');
    if ( method_exists($this, $action) ) {
      $this->$action();
    }
    else {
      return $this->display($id, $type);
    }
  }

  /**
   * Display.
   *
   * @param int $id
   * @param string $type
   *
   * @return string|void
   */
  public function display( $id = 0, $type = '' ) {
    if ( !$this->form_preview && $id && !isset($_GET["succes"]) && !isset( $_POST["save_or_submit" . $id] ) ) {
      // Increment views count.
      $this->model->increment_views_count($id);
    }

    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    /* Use for ajax submit */
    if( WDW_FM_Library(self::PLUGIN)->get('formType') != '' ) {
      $type = WDW_FM_Library(self::PLUGIN)->get('formType');
      $id = WDW_FM_Library(self::PLUGIN)->get('current_id', 0, 'intval');
    }

    if ( $type == 'embedded' ) {
      $result = $this->model->showform($id, $type);
      if ( !$result ) {
        return;
      }
      if ( get_option('wd_form_maker_version', FALSE) ) {
        if ( !class_exists('Cookie_fm') ) {
          require_once(WDFMInstance(self::PLUGIN)->plugin_dir . '/framework/Cookie.php');
        }
        new Cookie_fm();
      }

      $this->model->savedata($result[0], $id);

      $page_id = (is_front_page() && !is_page()) ? 'homepage' : get_the_ID();
      $current_post_type = 'homepage' == $page_id ? 'home' : get_post_type($page_id);
      if ( !empty(WDW_FM_Library(self::PLUGIN)->get('fm_page_id')) && !empty(WDW_FM_Library(self::PLUGIN)->get('fm_current_post_type')) ) {
        $page_id = WDW_FM_Library(self::PLUGIN)->get('fm_page_id', '', 'intval');
        $current_post_type = WDW_FM_Library(self::PLUGIN)->get('fm_current_post_type');
      }
      $result['fm_page_id'] = $page_id;
      $result['fm_current_post_type'] = $current_post_type;
      $display = $this->view->display($result, $fm_settings, $id, $type);
    }
    else {
     // Get all forms.
      $forms = $this->model->all_not_embedded_forms();
      $display = $this->autoload_form($forms, $fm_settings);
    }

    Cookie_fm::saveCookieValue();
    return $display;
  }

  /**
   * Autoload form.
   *
   * @param array $forms
   * @param array $fm_settings
   * @return string
   */
  public function autoload_form( $forms = array(), $fm_settings = array() ) {
    $fm_forms = array();
    foreach ($forms as $key => $form) {
      $id = (int)$form->id;
      $form = WDW_FM_Library::convert_json_options_to_old($form, array('form_options', "display_options"));

      $display_on_this = FALSE;
      $error = 'success';
      $message = FALSE;
      $type = $form->type;

      $display_on = explode(',', $form->display_on);
      $posts_include = explode(',', $form->posts_include);
      $pages_include = explode(',', $form->pages_include);
      $categories_display = explode(',', $form->display_on_categories);
      $current_categories = explode(',', $form->current_categories);
      $posts_include = array_filter($posts_include);
      $pages_include = array_filter($pages_include);
      $page_id = (is_front_page() && !is_page()) ? 'homepage' : get_the_ID();
      $current_post_type = 'homepage' == $page_id ? 'home' : get_post_type($page_id);
      if ($display_on) {
        wp_reset_query();
        if (in_array('everything', $display_on)) {
          if (is_singular()) {
            if ((is_singular('page') && (!$pages_include || in_array(get_the_ID(), $pages_include))) || (!is_singular('page') && (!$posts_include || in_array(get_the_ID(), $posts_include)))) {
              $display_on_this = TRUE;
            }
          }
          else {
            $display_on_this = TRUE;
          }
        }
        else {
          if (is_archive()) {
            if (in_array('archive', $display_on)) {
              $display_on_this = TRUE;
            }
          }
          else {
            // We use both of these parameters from GET, as "page_id" and "current_post_type" do not get value after ajax submit.
            $after_ajax_submit = false;
            if ( !empty(WDW_FM_Library(self::PLUGIN)->get('fm_page_id')) && !empty(WDW_FM_Library(self::PLUGIN)->get('fm_current_post_type')) ) {
              $after_ajax_submit = true;
              $page_id = WDW_FM_Library(self::PLUGIN)->get('fm_page_id', '', 'intval');
              $current_post_type = WDW_FM_Library(self::PLUGIN)->get('fm_current_post_type');
            }

            if (is_singular() || 'home' == $current_post_type) {
              if (in_array('home', $display_on) && is_front_page()) {
                $display_on_this = TRUE;
              }
            }
            $posts_and_pages = array();
            foreach ($display_on as $dis) {
              if (!in_array($dis, array('everything', 'home', 'archive', 'category'))) {
                $posts_and_pages[] = $dis;
              }
            }
            if ( ($posts_and_pages && is_singular($posts_and_pages)) || $after_ajax_submit ) {
              switch ($current_post_type) {
                case 'page' :
                case 'home' :
                  if (!$pages_include || in_array($page_id, $pages_include)) {
                    $display_on_this = TRUE;
                  }
                  break;
                case 'post':
                  if ( !empty($posts_include) && in_array($page_id, $posts_include) ) {
                    $display_on_this = TRUE;
                  }
                  else {
                    $categories = get_the_terms($page_id, 'category');
                    $post_cats = array();
                    if ( $categories ) {
                      foreach ( $categories as $category ) {
                        $post_cats[] = $category->term_id;
                      }
                    }
                    foreach ( $post_cats as $single_cat ) {
                      if ( in_array('select_all_categories', $categories_display) || in_array($single_cat, $categories_display) ) {
                        $display_on_this = TRUE;
                      }
                    }
                  }
                break;
                default:
                  if (in_array($current_post_type, $display_on) || $current_post_type == 'form-maker') {
                    $display_on_this = TRUE;
                  }
				        break;
              }
            }
          }
        }
      }
      $show_for_admin = current_user_can('administrator') && $form->show_for_admin ? 'true' : 'false';

      if ( $this->form_preview && ($id == WDW_FM_Library(self::PLUGIN)->get('wdform_id', 0)) ) {
        $display_on_this = TRUE;
      }

      $form_result = $this->model->showform($id, $type);
      if ( !$form_result ) {
        continue;
      }
      if ( get_option( 'wd_form_maker_version', FALSE ) && $display_on_this ) {
        if ( !class_exists('Cookie_fm') ) {
          require_once(WDFMInstance(self::PLUGIN)->plugin_dir . '/framework/Cookie.php');
        }
        new Cookie_fm();
      }

      if ( Cookie_fm::getCookieByKey($id, 'redirect_paypal') == 1 ) {
        Cookie_fm::getCookieByKey($id, 'redirect_paypal', true);
      }
      elseif ( Cookie_fm::getCookieByKey($id, 'massage_after_submit') != '' ) {
        $massage_after_submit = Cookie_fm::getCookieByKey($id, 'massage_after_submit');
        if ($massage_after_submit) {
          $message = TRUE;
        }
      }

      $this->model->savedata($form_result[0], $id);
      $params = array();
      $params['id'] = $id;
      $params['type'] = $type;
      $params['form'] = $form;
      $params['display_on_this'] = $display_on_this;
      $params['show_for_admin'] = $show_for_admin;
      $params['form_result'] = $form_result;
      $params['fm_settings'] = $fm_settings;
      $params['error'] = $error;
      $params['message'] = $message;
      $params['fm_page_id'] = $page_id;
      $params['fm_current_post_type'] = $current_post_type;
      $fm_forms[$id] = $this->view->autoload_form( $params );
    }
    return implode('', $fm_forms);
  }

  public function fm_reload_input() {
    global $wpdb;
    $form_id  = WDW_FM_Library::get('form_id','','intval');
    $inputs = WDW_FM_Library::get('inputs');
    $json = array();
    if ( !empty($form_id) && !empty($inputs) ) {
      $prepare = array();
      $prepare[] = $form_id;
      $where_in_prepare = '%d';
      if ( WDFMInstance(self::PLUGIN)->is_free ) {
        $contact_form_forms = get_option( 'contact_form_forms', '' ) != '' ? get_option( 'contact_form_forms' ) : 0;
        $contact_form_forms_arr =  explode(',', $contact_form_forms);
        if ( !empty($contact_form_forms_arr) ) {
          $in_prepare = '';
          foreach ( $contact_form_forms_arr as $val ) {
            $in_prepare .= '%d,';
            array_push($prepare, $val);
          }
          $where_in_prepare =  rtrim($in_prepare, ',');
        }
      }
      $query = 'SELECT * FROM ' . $wpdb->prefix . 'formmaker WHERE id = %d ' . (!WDFMInstance(self::PLUGIN)->is_free ? '' : 'AND id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . $where_in_prepare . ')');
      $row = $wpdb->get_row( $wpdb->prepare( $query , $prepare ) );
      $row = WDW_FM_Library::convert_json_options_to_old( $row, 'form_options' );

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

      $ids = array();
      $reset_fields = array();
      foreach ( $inputs as $input_key => $input_val ) {
          list( $row_id, $type, $input_id) = explode('|', $input_key);
          $key = $row_id . '|'. $type;
          $ids[$key][] = $input_id.'|'.$input_val;

          if ( empty($input_val) ) {
            $reset_fields[] = $row_id;
          }
      }
      if ( !empty($ids) ) {
        foreach ( $ids as $row_key => $row_values ) {
          list($row_id, $type) = explode('|', $row_key);

          $index = array_search($row_id, $id1s);
          $label = $labels[$index];
          $params = $paramss[$index];
          $param = array();
          $param['label'] = $label;
          $param['attributes'] = '';
          $param['reset_fields'] = $reset_fields;
          foreach ( $row_values as $val ) {
            list($input_id, $input_val) = explode('|', $val);
            $str_key = '{'. $input_id .'}';
            if ( strpos($params, $str_key) > -1 ) {
              $params = str_replace( $str_key, $input_val, $params );
            }
          }
          $html = $this->view->$type( $params, $row, $form_id, $row_id, $type, $param );
          $json[$row_id] = array('html' => $html);
        }
      }
    } else {
      $json['error'] = 1;
    }
    echo json_encode($json); exit;
    }
}
