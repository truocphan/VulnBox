<?php

/**
 * Class FMControllerFormmakereditcountryinpopup
 */
class FMControllerFormmakereditcountryinpopup extends FMAdminController {
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
    // Load FMViewFromeditcountryinpopup class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMEditCountryinPopup.php";
    $this->view = new FMViewFromeditcountryinpopup();
    // Set params for view.
    $params = array();
    $params['field_id'] = WDW_FM_Library(self::PLUGIN)->get('field_id', 0);
    $this->view->display($params);
  }
}
