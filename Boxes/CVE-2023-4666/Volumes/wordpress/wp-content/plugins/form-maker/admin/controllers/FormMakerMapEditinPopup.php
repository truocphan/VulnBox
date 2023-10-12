<?php

/**
 * Class FMControllerFormmakermapeditinpopup
 */
class FMControllerFormmakermapeditinpopup extends FMAdminController {
  /**
   * @var view
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
    // Load FMViewFrommapeditinpopup class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMMapEditinPopup.php";
    $this->view = new FMViewFrommapeditinpopup();
    // Get form maker settings.
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    // Set params for view.
    $params = array();
    $params['map_key'] = !empty($fm_settings['map_key']) ? '&key=' . $fm_settings['map_key'] : '';
    $params['long'] = WDW_FM_Library(self::PLUGIN)->get('long', 0);
    $params['lat'] = WDW_FM_Library(self::PLUGIN)->get('lat', 0);
    $this->view->display($params);
  }
}
