<?php
/*
 * Followig class handling text input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Text_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Text Input', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular text input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	private function get_settings() {

		$regex_help_url = 'https://github.com/RobinHerbots/Inputmask#any-option-can-also-be-passed-through-the-use-of-a-data-attribute-use-data-inputmask-the-name-of-the-optionvalue';

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
			'placeholder'     => array(
				'type'  => 'text',
				'title' => __( 'Placeholder', 'woocommerce-product-addon' ),
				'desc'  => __( 'Optionally placeholder.', 'woocommerce-product-addon' ),
			),
			'error_message'   => array(
				'type'  => 'text',
				'title' => __( 'Error message', 'woocommerce-product-addon' ),
				'desc'  => __( 'Insert the error message for validation.', 'woocommerce-product-addon' ),
			),
			'maxlength'       => array(
				'type'        => 'text',
				'title'       => __( 'Max. Length', 'woocommerce-product-addon' ),
				'desc'        => __( 'Max. characters allowed, leave blank for default', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),

			'minlength'       => array(
				'type'        => 'text',
				'title'       => __( 'Min. Length', 'woocommerce-product-addon' ),
				'desc'        => __( 'Min. characters allowed, leave blank for default', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'default_value'   => array(
				'type'        => 'text',
				'title'       => __( 'Set default value', 'woocommerce-product-addon' ),
				'desc'        => __( 'Pre-defined value for text input', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),

			'price'           => array(
				'type'        => 'text',
				'title'       => __( 'Add-on Price', 'woocommerce-product-addon' ),
				'desc'        => __( 'Price will be added as Add-on if text provided', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'class'           => array(
				'type'        => 'text',
				'title'       => __( 'Class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'input_mask'      => array(
				'type'        => 'text',
				'title'       => __( 'Input Masking', 'woocommerce-product-addon' ),
				'desc'        => __( 'Click options to see all Masking Options', 'woocommerce-product-addon' ),
				'link'        => __( '<a href="https://github.com/RobinHerbots/Inputmask" target="_blank">Options</a>', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'width'           => array(
				'type'        => 'select',
				'title'       => __( 'Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type field width in % e.g: 50%', 'woocommerce-product-addon' ),
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
			'use_regex'       => array(
				'type'        => 'checkbox',
				'title'       => __( 'Use Regex Expresession', 'woocommerce-product-addon' ),
				'link'        => __( '<a target="_blank" href="' . esc_url( $regex_help_url ) . '">See More</a>', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'onetime'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'One Time Fee/Charge', 'woocommerce-product-addon' ),
				'desc'        => __( 'Will not multiply with quantity', 'woocommerce-product-addon' ),
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

		$type = 'text';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
