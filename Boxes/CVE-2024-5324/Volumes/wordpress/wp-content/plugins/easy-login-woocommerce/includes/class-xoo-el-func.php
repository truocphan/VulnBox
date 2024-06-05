<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_El_Func{

	private static $_instance = null;

	public static function get_instance(){

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){

		if( class_exists('wfWAF') && apply_filters( 'xoo_el_wordfence_support', true ) ){

			
		}


		add_action( 'xoo_el_login_add_fields', array( $this, 'custom_login_fields' ) );
		add_action( 'xoo_el_single_add_fields', array( $this, 'custom_single_fields' ) );

	}


	public function custom_login_fields( $args ){

		if( isset( $args['forms'] ) && isset( $args['forms']['single'] ) && $args['forms']['single']['enable'] === "yes" ){
			?>
			<input type="hidden" name="_xoo_el_login_has_single" value="yes">
			<?php
		}

	}

	public function custom_single_fields( $args ){
		if( isset( $args['forms'] ) && isset( $args['forms']['register'] ) && $args['forms']['register']['enable'] === "yes" ){
			?>
			<input type="hidden" name="_xoo_el_login_has_register" value="yes">
			<?php
		}
	}

}


function xoo_el_func(){
	return Xoo_El_Func::get_instance();
}
xoo_el_func();
