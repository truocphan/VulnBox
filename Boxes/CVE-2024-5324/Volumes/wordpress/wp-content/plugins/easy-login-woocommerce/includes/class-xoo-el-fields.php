<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_El_Fields{

	protected static $_instance = null;

	public $skipFields = array(
		'xoo_el_reg_username', 'xoo-el-sing-user', 'xoo_el_reg_email', 'xoo_el_reg_fname', 'xoo_el_reg_lname', 'xoo_el_reg_pass', 'xoo_el_reg_pass_again',
	);

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
		
	}

	public function get_fields( $type = 'register' ){

		$fields = (array) xoo_el()->aff->fields->get_fields_data();

		if( $type === 'register' ){
			foreach ( $fields as $field_id => $field_data )  {
				//Skip all other fields
				if( isset( $field_data['settings']['elType'] ) ){
					unset( $fields[ $field_id ] );
				}
			}
		}
		else if( $type === 'myaccount' ){
			foreach ( $fields as $field_id => $field_data )  {
				//Skip if predefined field
				if( !isset( $field_data['settings']['display_myacc'] ) || $field_data['settings']['display_myacc'] !== 'yes' ){
					unset( $fields[ $field_id ] );
				}
			}

		}
		else{
			foreach ( $fields as $field_id => $field_data )  {
				//Skip other fields
				if( !isset( $field_data['settings']['elType'] ) || $field_data['settings']['elType'] !== $type ){
					unset( $fields[ $field_id ] );
				}
			}
		}
		
		return apply_filters( 'xoo_el_'.$type.'_fields', $fields );

	}


	public function get_fields_html( $type ){

		$fields = $this->get_fields( $type );

		echo '<div class="xoo-el-fields-cont">';

		foreach ( $fields as $field_id => $field_args ) {
			xoo_el()->aff->fields->get_field_html( $field_id );
		}

		echo '</div>';

	}


}

function xoo_el_fields(){
	return Xoo_El_Fields::get_instance();
}

xoo_el_fields();

?>