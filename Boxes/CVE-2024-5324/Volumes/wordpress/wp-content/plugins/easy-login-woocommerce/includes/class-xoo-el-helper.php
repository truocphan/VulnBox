<?php

class Xoo_El_Helper extends Xoo_Helper{

	protected static $_instance = null;

	public static function get_instance( $slug, $path ){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $slug, $path );
		}
		return self::$_instance;
	}

	public function get_general_option( $subkey = '' ){
		return $this->get_option( 'xoo-el-gl-options', $subkey );
	}

	public function get_style_option( $subkey = '' ){
		return $this->get_option( 'xoo-el-sy-options', $subkey );
	}


	public function get_advanced_option( $subkey = '' ){
		return $this->get_option( 'xoo-el-av-options', $subkey );
	}

}

function xoo_el_helper(){
	return Xoo_El_Helper::get_instance( 'easy-login-woocommerce', XOO_EL_PATH );
}
xoo_el_helper();

?>