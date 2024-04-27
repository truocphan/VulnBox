<?php
/*
 * Followig class handling date input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Color_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Color picker', 'woocommerce-product-addon' );
		$this->desc     = __( 'Color pallete input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-modx" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	private function get_settings() {

		$input_meta = array(
			'title'           => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'It will be shown as field label', 'woocommerce-product-addon' ),
			),
			'data_name'       => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'description'     => array(
				'type'  => 'textarea',
				'title' => __( 'Description', 'woocommerce-product-addon' ),
				'desc'  => __( 'Small description, it will be display near name title.', 'woocommerce-product-addon' ),
			),
			'error_message'   => array(
				'type'  => 'text',
				'title' => __( 'Error message', 'woocommerce-product-addon' ),
				'desc'  => __( 'Insert the error message for validation.', 'woocommerce-product-addon' ),
			),
			'default_color'   => array(
				'type'        => 'text',
				'title'       => __( 'Default color', 'woocommerce-product-addon' ),
				'desc'        => __( 'Define default color e.g: #effeff', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'palettes_colors' => array(
				'type'        => 'text',
				'title'       => __( 'Palettes colors', 'woocommerce-product-addon' ),
				'desc'        => __( 'Color codes seperated by comma e.g: #125, #459, #78b', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'palettes_width'  => array(
				'type'        => 'text',
				'title'       => __( 'Palettes width', 'woocommerce-product-addon' ),
				'desc'        => __( 'e.g: 500', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'palettes_mode'   => array(
				'type'        => 'select',
				'title'       => __( 'Palettes mode', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select Mode', 'woocommerce-product-addon' ),
				'options'     => array(
					'hsl' => 'Hue, Saturation, Lightness',
					'hsv' => 'Hue, Saturation, Value',
				),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'width'           => array(
				'type'        => 'select',
				'title'       => __( 'Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select width column.', 'woocommerce-product-addon' ),
				'options'     => ppom_get_input_cols(),
				'default'     => 12,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility'      => array(
				'type'        => 'select',
				'title'       => __( 'Visibility', 'woocommerce-product-addon' ),
				'desc'        => __( 'Set field visibility based on user.', 'woocommerce-product-addon' ),
				'options'     => ppom_field_visibility_options(),
				'default'     => 'everyone',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility_role' => array(
				'type'   => 'text',
				'title'  => __( 'User Roles', 'woocommerce-product-addon' ),
				'desc'   => __( 'Role separated by comma.', 'woocommerce-product-addon' ),
				'hidden' => true,
			),
			'show_palettes'   => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show palettes', 'woocommerce-product-addon' ),
				'desc'        => __( 'Tick if need to show a group of common colors beneath the square', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'show_onload'     => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show on load', 'woocommerce-product-addon' ),
				'desc'        => __( 'Display color picker by default, otherwise show on click', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'desc_tooltip'    => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show tooltip (PRO)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Description in Tooltip with Help Icon', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'required'        => array(
				'type'        => 'checkbox',
				'title'       => __( 'Required', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select this if it must be required.', 'woocommerce-product-addon' ),
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

		$type = 'color';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
