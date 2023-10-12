<?php

class FMControllerPaypal_info extends FMAdminController {
  /**
   * @var $model
   */
  private $model;
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
    // Load FMModelPaypal_info class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/models/FMPaypalInfo.php";
    $this->model = new FMModelPaypal_info();
    // Load FMViewPaypal_info class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMPaypalInfo.php";
    $this->view = new FMViewPaypal_info();
    $id = WDW_FM_Library(self::PLUGIN)->get('id', 0);
    // Get form session by id.
    $row = $this->model->get_form_session($id);
    // Set params for view.
    $params = array();
    $params['row'] = $row;
    $this->view->display($params);
  }
}
