<?php

/**
 * Class FMControllerFormmakeripinfoinpopup
 */
class FMControllerFormmakeripinfoinpopup extends FMAdminController {
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
    // Load FMViewFromipinfoinpopup class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMIpinfoinPopup.php";
    $this->view = new FMViewFromipinfoinpopup();
    // Get IP
    $ip = WDW_FM_Library(self::PLUGIN)->get('data_ip', '');
    // Connect to IP api service and get IP info.
    $ipinfo = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
    $city = '-';
    $country = '-';
    $countryCode = '-';
    $country_flag = '-';
    $timezone = '-';
    $lat = '-';
    $lon = '-';
    if ( $ipinfo && $ipinfo['status'] == 'success' && $ipinfo['countryCode'] ) {
      $city = $ipinfo['city'];
      $country = $ipinfo['country'];
      $countryCode = $ipinfo['countryCode'];
      $country_flag = '<img width="16px" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/flags/' . strtolower($ipinfo['countryCode']) . '.png" class="sub-align" alt="' . $ipinfo['country'] . '" title="' . $ipinfo['country'] . '" />';
      $timezone = $ipinfo['timezone'];
      $lat = $ipinfo['lat'];
      $lon = $ipinfo['lon'];
    }
    // Set params for view.
    $params = array();
    $params['ip'] = $ip;
    $params['city'] = $city;
    $params['country'] = $country;
    $params['country_flag'] = $country_flag;
    $params['countryCode'] = $countryCode;
    $params['timezone'] = $timezone;
    $params['lat'] = $lat;
    $params['lon'] = $lon;
    $this->view->display($params);
  }
}
