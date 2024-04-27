<?php
/*
 * Followig class handling checkbox input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Checkbox_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Checkbox Input', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular checkbox input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-check-square-o" aria-hidden="true"></i>', 'woocommerce-product-addon' );
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
			'options'         => array(
				'type'  => 'paired',
				'title' => __( 'Add options', 'woocommerce-product-addon' ),
				'desc'  => __( 'Type option with price (optionally)', 'woocommerce-product-addon' ),
			),
			'class'           => array(
				'type'        => 'text',
				'title'       => __( 'Class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'woocommerce-product-addon' ),
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
			'checked'         => array(
				'type'  => 'textarea',
				'title' => __( 'Checked option(s)', 'woocommerce-product-addon' ),
				'desc'  => __( 'Type option(s) name given in (Add Options) tab if you want already checked.', 'woocommerce-product-addon' ),
			),
			'min_checked'     => array(
				'type'        => 'text',
				'title'       => __( 'Min. Checked option(s)', 'woocommerce-product-addon' ),
				'desc'        => __( 'How many options can be checked by user e.g: 2. Leave blank for default.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_checked'     => array(
				'type'        => 'text',
				'title'       => __( 'Max. Checked option(s)', 'woocommerce-product-addon' ),
				'desc'        => __( 'How many options can be checked by user e.g: 3. Leave blank for default.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
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
			'onetime'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Fixed Fee', 'woocommerce-product-addon' ),
				'desc'        => __( 'Add one time fee to cart total.', 'woocommerce-product-addon' ),
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

		$type = 'checkbox';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
