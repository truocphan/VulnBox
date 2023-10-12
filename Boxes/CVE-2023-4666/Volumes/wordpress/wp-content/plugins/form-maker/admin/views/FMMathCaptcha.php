<?php

/**
 * Class FMViewFormmakerwdmathcaptcha
 */
class FMViewFormmakerwdmathcaptcha extends FMAdminView {
  /**
   * Display.
   */
  public function display() {
    if ( isset($_GET['action']) && esc_html($_GET['action']) == 'formmakerwdmathcaptcha' . WDFMInstance(self::PLUGIN)->plugin_postfix ) {
      $i = WDW_FM_Library(self::PLUGIN)->get( "i", '', 'esc_html' );
      $r2 = WDW_FM_Library(self::PLUGIN)->get( "r2", 0, 'intval' );
      $rrr = WDW_FM_Library(self::PLUGIN)->get( "rrr", 0, 'intval' );
      $randNum = 0 + $r2 + $rrr;
      $operations_count = ((WDW_FM_Library(self::PLUGIN)->get("operations_count", 'intval') != '') ? (WDW_FM_Library(self::PLUGIN)->get("operations_count", 'intval') > 5 ? 5 : WDW_FM_Library(self::PLUGIN)->get("operations_count", 'intval')) : 1); //TODO??//
      $operations = ((WDW_FM_Library(self::PLUGIN)->get("operations") != "") ? str_replace('@', '+', WDW_FM_Library(self::PLUGIN)->get("operations")) : '+,-'); //TODO??//
      $operations = preg_replace('/\s+/', '', $operations);
      $cap_width = 2 * ($operations_count + 1) * 20 + 10;
      $cap_height = 26;
      $cap_quality = 100;
      $code = $this->code_generic($operations_count, $operations);
      if ( get_option( 'wd_form_maker_version', FALSE ) ) {
        if( !class_exists('Cookie_fm') ) {
          require_once($this->plugin_dir . '/framework/Cookie.php');
        }
        new Cookie_fm();
      }
      Cookie_fm::saveCookieValueByKey($i, '_wd_arithmetic_captcha_code', md5($code[1]));
      $canvas = imagecreatetruecolor($cap_width, $cap_height);
      $c = imagecolorallocate($canvas, rand(150, 255), rand(150, 255), rand(150, 255));
      imagefilledrectangle($canvas, 0, 0, $cap_width, $cap_height, $c);
      $code = $code[0];
      $count = strlen($code);
      $color_text = imagecolorallocate($canvas, 0, 0, 0);
      for ( $it = 0; $it < $count; $it++ ) {
        $letter = $code[$it];
        imagestring($canvas, 6, (10 * $it + 10), $cap_height / 4, $letter, $color_text);
      }
      for ( $c = 0; $c < 150; $c++ ) {
        $x = rand(0, $cap_width - 1);
        $y = rand(0, 29);
        $col = '0x' . rand(0, 9) . '0' . rand(0, 9) . '0' . rand(0, 9) . '0';
        imagesetpixel($canvas, $x, $y, hexdec($col));
      }
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header('Cache-Control: no-store, no-cache, must-revalidate');
      header('Cache-Control: post-check=0, pre-check=0', FALSE);
      header('Pragma: no-cache');
      header('Content-Type: image/jpeg');
      imagejpeg($canvas, NULL, $cap_quality);
    }
    die('');
  }

  /**
   * Code generic.
   *
   * @param array $_length
   * @param array $_operations
   * @return array
   */
  private function code_generic( $_length = array(), $_operations = array() ) {
    $cap = '';
    $dig = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );
    $valid_oprations = array( '+', '-', '*', '/' );
    $operations_array = array_filter(explode(',', $_operations));
    foreach ( $operations_array as $key => $operation ) {
      if ( !in_array($operation, $valid_oprations) ) {
        unset($operations_array[$key]);
      }
    }
    for ( $k = 0; $k <= $_length; $k++ ) {
      if ( substr($cap, -1) == '/' ) {
        $operations_array = array_diff($operations_array, array( '/' ));
        $num_divisors = $this->arrayOfNumberDivisors(substr($cap, -2, 1));
        $cap .= $num_divisors[array_rand($num_divisors)];
      }
      else {
        $cap .= $dig[array_rand($dig)];
      }
      if ( $k != $_length ) {
        $cap .= $operations_array[array_rand($operations_array)];
      }
    }
    $pass = eval('return ' . $cap . ';');
    if ( $pass < 0 ) {
      return $this->code_generic($_length, $_operations);
    }
    $cap .= '=';
    $cap = implode(' ', str_split($cap));

    return array( $cap, $pass );
  }

  /**
   * array Of Number Divisors.
   *
   * @param int $x
   * @return array
   */
  public function arrayOfNumberDivisors( $x = 0 ) {
    $divisors = array();
    if ( $x == 0 ) {
      $divisors[] = rand(1, 9);
    }
    for ( $i = 1; $i <= $x; $i++ ) {
      if ( $x % $i == 0 ) {
        $divisors [] = $i;
      }
    }
    return $divisors;
  }
}
