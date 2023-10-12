<?php

/**
 * Class FMControllerProduct_option
 */
class FMControllerProduct_option extends FMAdminController {
  /**
   * @var $view
   */
  private $view;

  /**
   * Execute.
   */
  public function execute() {
    $this->display();
  }

  /**
   * Display.
   */
  public function display() {
    // Load FMViewProduct_option class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMProductOption.php";
    $this->view = new FMViewProduct_option();
    $field_id = WDW_FM_Library(self::PLUGIN)->get('field_id', 0, 'intval');
    $property_id = WDW_FM_Library(self::PLUGIN)->get('property_id', '-1', 'intval');
    $url_for_ajax = WDW_FM_Library(self::PLUGIN)->get('url_for_ajax', '', 'esc_url');
    // Set params for view.
    $params = array();
    $params['field_id'] = $field_id;
    $params['property_id'] = $property_id;
    $params['url_for_ajax'] = $url_for_ajax;
    $this->view->display($params);
  }
}
