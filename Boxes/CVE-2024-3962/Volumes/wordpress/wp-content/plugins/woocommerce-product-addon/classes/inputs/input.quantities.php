<?php
/*
 * Followig class handling select input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Quantities_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Variation Quantity', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular select-box input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-list-ol" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	function variation_layout() {

		$layout_options = array(
			'simple_view' => __( 'Vertical Layout', 'woocommerce-product-addon' ),
			'horizontal'  => __( 'Horizontal Layout', 'woocommerce-product-addon' ),
			'grid'        => __( 'Grid Layout', 'woocommerce-product-addon' ),
		);

		return apply_filters( 'ppom_variation_layout_options', $layout_options );
	}

	private function get_settings() {
		$input_meta = array(
			'title'            => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'It will be shown as field label. See example for usage.', 'woocommerce-product-addon' ),
			),
			'data_name'        => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'description'      => array(
				'type'  => 'textarea',
				'title' => __( 'Description', 'woocommerce-product-addon' ),
				'desc'  => __( 'Small description, it will be display near name title.', 'woocommerce-product-addon' ),
			),
			'error_message'    => array(
				'type'  => 'text',
				'title' => __( 'Error message', 'woocommerce-product-addon' ),
				'desc'  => __( 'Insert the error message for validation.', 'woocommerce-product-addon' ),
			),
			'options'          => array(
				'type'  => 'paired-quantity',
				'title' => __( 'Add options', 'woocommerce-product-addon' ),
				'desc'  => __( 'Type option with price (optionally)', 'woocommerce-product-addon' ),
			),
			'view_control'     => array(
				'type'        => 'select',
				'title'       => __( 'Variation Layout', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select variation layout design', 'woocommerce-product-addon' ),
				'options'     => $this->variation_layout(),
				'default'     => 'simple_view',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'default_price'    => array(
				'type'        => 'text',
				'title'       => __( 'Default Price', 'woocommerce-product-addon' ),
				'desc'        => __( 'Default option price, if no prices is given in Options', 'woocommerce-product-addon' ),
				'options'     => $this->variation_layout(),
				'default'     => '',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'min_qty'          => array(
				'type'        => 'text',
				'title'       => __( 'Min Quantity', 'woocommerce-product-addon' ),
				'desc'        => __( 'Enter min quantity allowed.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_qty'          => array(
				'type'        => 'text',
				'title'       => __( 'Max Quantity', 'woocommerce-product-addon' ),
				'desc'        => __( 'Enter max quantity allowed.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'class'            => array(
				'type'        => 'text',
				'title'       => __( 'Class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'width'            => array(
				'type'        => 'select',
				'title'       => __( 'Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select width column', 'woocommerce-product-addon' ),
				'options'     => ppom_get_input_cols(),
				'default'     => 12,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility'       => array(
				'type'    => 'select',
				'title'   => __( 'Visibility', 'woocommerce-product-addon' ),
				'desc'    => __( 'Set field visibility based on user.', 'woocommerce-product-addon' ),
				'options' => ppom_field_visibility_options(),
				'default' => 'everyone',
			),
			'visibility_role'  => array(
				'type'   => 'text',
				'title'  => __( 'User Roles', 'woocommerce-product-addon' ),
				'desc'   => __( 'Role separated by comma.', 'woocommerce-product-addon' ),
				'hidden' => true,
			),
			'desc_tooltip'     => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show tooltip (PRO)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Description in Tooltip with Help Icon', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'enable_plusminus' => array(
				'type'  => 'checkbox',
				'title' => __( 'Enhance -/+ controls', 'woocommerce-product-addon' ),
				'desc'  => __( 'Add the -/+ buttons', 'woocommerce-product-addon' ),
			),
			'manage_stock'     => array(
				'type'  => 'checkbox',
				'title' => __( 'Manage Stock', 'woocommerce-product-addon' ),
				'desc'  => __( 'Check/update stock against each variation', 'woocommerce-product-addon' ),
			),
			'unlink_order_qty' => array(
				'type'  => 'checkbox',
				'title' => __( 'Unlink Order Quantity', 'woocommerce-product-addon' ),
				'desc'  => __( 'Order quantity will not be controlled by this.', 'woocommerce-product-addon' ),
			),
			'required'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Required', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select this if it must be required.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'logic'            => array(
				'type'  => 'checkbox',
				'title' => __( 'Enable Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
			'conditions'       => array(
				'type'  => 'html-conditions',
				'title' => __( 'Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
		);

		$type = 'quantities';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
