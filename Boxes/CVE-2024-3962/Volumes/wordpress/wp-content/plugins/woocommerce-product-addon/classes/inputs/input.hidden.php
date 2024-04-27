<?php
/*
 * Followig class handling hidden input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Hidden_wooproduct extends PPOM_Inputs {

	/*
	 * input control settings
	 */
	var $title, $desc, $settings;

	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;

	function __construct() {

		$this->plugin_meta = ppom_get_plugin_meta();

		$this->title    = __( 'Hidden Input', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular hidden input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-hashtag" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}


	private function get_settings() {

		$input_meta = array(

			'title'           => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'Label will show in cart', 'woocommerce-product-addon' ),
			),
			'data_name'       => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'field_value'     => array(
				'type'  => 'text',
				'title' => __( 'Field value', 'woocommerce-product-addon' ),
				'desc'  => __( 'you can pre-set the value of this hidden input.', 'woocommerce-product-addon' ),
			),
			'visibility'      => array(
				'type'    => 'select',
				'title'   => __( 'Visibility', 'woocommerce-product-addon' ),
				'desc'    => __( 'Set field visibility based on user.', 'woocommerce-product-addon' ),
				'options' => ppom_field_visibility_options(),
				'default' => 'everyone',
			),
			'visibility_role' => array(
				'type'   => 'text',
				'title'  => __( 'User Roles', 'woocommerce-product-addon' ),
				'desc'   => __( 'Role separated by comma.', 'woocommerce-product-addon' ),
				'hidden' => true,
			),
			'cart_display'    => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show in Cart', 'woocommerce-product-addon' ),
				'desc'        => __( 'Display Field Value in Cart', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
		);

		$type = 'hidden';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
