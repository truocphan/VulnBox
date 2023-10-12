<?php

class FMViewFormmakerwdcaptcha extends FMAdminView {
	
	/**
	* Display.
	*	
	* @param array $params
	*/
	public function display( $params = array() ) {
		if (isset($_GET['action']) && esc_html($_GET['action']) == 'formmakerwdcaptcha' . WDFMInstance(self::PLUGIN)->plugin_postfix) {
			$i = WDW_FM_Library(self::PLUGIN)->get( "i", '', 'esc_html' );
			$r2 = WDW_FM_Library(self::PLUGIN)->get( "r2", 0, 'intval' );
			$rrr = WDW_FM_Library(self::PLUGIN)->get( "rrr", 0, 'intval' );
			$randNum = 0 + $r2 + $rrr;
			$digit = WDW_FM_Library(self::PLUGIN)->get( "digit", 6, 'intval' );
			$cap_width = $digit * 10 + 15;
			$cap_height = 26;
			$cap_quality = 100;
			$cap_length_min = $digit;
			$cap_length_max = $digit;
			$cap_digital = 1;
			$cap_latin_char = 1;
			function code_generic($_length, $_digital = 1, $_latin_char = 1) {
				$dig = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
				$lat = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
				$main = array();
				if ($_digital) {
				  $main = array_merge($main, $dig);
				}
				if ($_latin_char) {
				  $main = array_merge($main, $lat);
				}
				shuffle($main);
				$pass = substr(implode('', $main), 0, $_length);
				return $pass;
			}
			
			$l = rand($cap_length_min, $cap_length_max);
			$code = code_generic($l, $cap_digital, $cap_latin_char);
      if ( get_option( 'wd_form_maker_version', FALSE ) ) {
        if( !class_exists('Cookie_fm') ) {
          require_once($this->plugin_dir . '/framework/Cookie.php');
        }
        new Cookie_fm();
      }
      Cookie_fm::saveCookieValueByKey($i, '_wd_captcha_code', md5($code));
			$canvas = imagecreatetruecolor($cap_width, $cap_height);
			$c = imagecolorallocate($canvas, rand(150, 255), rand(150, 255), rand(150, 255));
			imagefilledrectangle($canvas, 0, 0, $cap_width, $cap_height, $c);
			$count = strlen($code);
			$color_text = imagecolorallocate($canvas, 0, 0, 0);
			
			for ($it = 0; $it < $count; $it++) {
				$letter = $code[$it];
				imagestring($canvas, 6, (10 * $it + 10), $cap_height / 4, $letter, $color_text);
			}
			
			for ($c = 0; $c < 150; $c++) {
				$x = rand(0, $cap_width - 1);
				$y = rand(0, 29);
        $col = intval( '0x' . rand(0, 9) . '0' . rand(0, 9) . '0' . rand(0, 9) . '0' );
				imagesetpixel($canvas, $x, $y, $col);
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
}