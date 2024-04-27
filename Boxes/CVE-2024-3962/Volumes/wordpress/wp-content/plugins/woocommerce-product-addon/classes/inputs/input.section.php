<?php
/*
 * Followig class handling text input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Section_wooproduct extends PPOM_Inputs {

	/*
	 * input control settings
	 */
	var $title, $desc, $settings;


	/*
	 * check if section is started
	 */
	var $is_section_stared;
	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;

	function __construct() {

		$this->plugin_meta = ppom_get_plugin_meta();

		$this->title    = __( 'HTML', 'woocommerce-product-addon' );
		$this->desc     = __( 'HTML content', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-code" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}


	private function get_settings() {

		$input_meta = array(
			'data_name'       => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'width'           => array(
				'type'    => 'select',
				'title'   => __( 'Width', 'woocommerce-product-addon' ),
				'desc'    => __( 'Select width column.', 'woocommerce-product-addon' ),
				'options' => ppom_get_input_cols(),
				'default' => 12,
			),
			'description'     => array(
				'type'  => 'textarea',
				'title' => __( 'Description', 'woocommerce-product-addon' ),
				'desc'  => __( 'Small description, it will be display near name title.', 'woocommerce-product-addon' ),
			),
			'html'            => array(
				'type'  => 'textarea',
				'title' => __( 'Content', 'woocommerce-product-addon' ),
				'desc'  => __( 'Add your text/HTML here.', 'woocommerce-product-addon' ),
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
			'desc_tooltip'    => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show tooltip (PRO)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Description in Tooltip with Help Icon', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'cart_display'    => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show in Cart', 'woocommerce-product-addon' ),
				'desc'        => __( 'Display Field Value in Cart', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'logic'           => array(
				'type'  => 'checkbox',
				'title' => __( 'Enable Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
			'conditions'      => array(
				'type'  => 'html-conditions',
				'title' => __( 'Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
		);

		$type = 'section';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
