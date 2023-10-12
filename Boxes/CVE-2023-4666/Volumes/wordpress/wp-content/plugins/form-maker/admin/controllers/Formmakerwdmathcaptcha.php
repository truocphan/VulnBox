<?php

/**
 * Class FMControllerFormmakerwdmathcaptcha
 */
class FMControllerFormmakerwdmathcaptcha extends FMAdminController {
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
    // Load FMViewFormmakerwdmathcaptcha class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMMathCaptcha.php";
    $this->view = new FMViewFormmakerwdmathcaptcha();
    // Set params for view.
    $params = array();
    $this->view->display($params);
  }
}
