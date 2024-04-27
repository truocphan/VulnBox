<?php
/*
 * Followig class handling text input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Divider_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Divider', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular didider input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	function ppom_divider_style() {

		return array(
			'style1' => __( 'Style 1', 'woocommerce-product-addon' ),
			'style2' => __( 'Style 2', 'woocommerce-product-addon' ),
			'style3' => __( 'Style 3', 'woocommerce-product-addon' ),
			'style4' => __( 'Style 4', 'woocommerce-product-addon' ),
			'style5' => __( 'Style 5', 'woocommerce-product-addon' ),
		);
	}

	function border_style() {

		return array(
			'solid'  => __( 'Solid', 'woocommerce-product-addon' ),
			'dotted' => __( 'Dotted', 'woocommerce-product-addon' ),
			'dashed' => __( 'Dashed', 'woocommerce-product-addon' ),
			'double' => __( 'Double', 'woocommerce-product-addon' ),
			'groove' => __( 'Groove', 'woocommerce-product-addon' ),
			'ridge'  => __( 'Ridge', 'woocommerce-product-addon' ),
			'inset'  => __( 'Inset', 'woocommerce-product-addon' ),
			'outset' => __( 'Outset', 'woocommerce-product-addon' ),
		);
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
			'divider_styles'  => array(
				'type'        => 'select',
				'title'       => __( 'Select style', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select style you want to render', 'woocommerce-product-addon' ),
				'options'     => $this->ppom_divider_style(),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'style1_border'   => array(
				'type'        => 'select',
				'title'       => __( 'Style border', 'woocommerce-product-addon' ),
				'desc'        => __( 'It will only apply on style 1.', 'woocommerce-product-addon' ),
				'options'     => $this->border_style(),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'divider_height'  => array(
				'type'        => 'text',
				'title'       => __( 'Divider height', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide the divider height e.g: 3px.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'divider_txtsize' => array(
				'type'        => 'text',
				'title'       => __( 'Font size', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide divider text font size e.g: 18px', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'divider_color'   => array(
				'type'        => 'color',
				'title'       => __( 'Divider color', 'woocommerce-product-addon' ),
				'desc'        => __( 'Choose the divider color.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'divider_txtclr'  => array(
				'type'        => 'color',
				'title'       => __( 'Divider text color', 'woocommerce-product-addon' ),
				'desc'        => __( 'Choose the divider text color.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),

		);

		$type = 'divider';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
