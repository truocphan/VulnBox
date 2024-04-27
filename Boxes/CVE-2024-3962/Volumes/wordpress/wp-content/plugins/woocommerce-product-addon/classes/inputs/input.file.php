<?php
/*
 * Followig class handling file input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_File_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'File Input', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular file input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-file" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	private function get_settings() {

		$input_meta = array(
			'title'               => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'It will be shown as field label', 'woocommerce-product-addon' ),
			),
			'data_name'           => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'description'         => array(
				'type'  => 'textarea',
				'title' => __( 'Description', 'woocommerce-product-addon' ),
				'desc'  => __( 'Small description, it will be display near name title.', 'woocommerce-product-addon' ),
			),
			'error_message'       => array(
				'type'  => 'text',
				'title' => __( 'Error message', 'woocommerce-product-addon' ),
				'desc'  => __( 'Insert the error message for validation.', 'woocommerce-product-addon' ),
			),
			'file_cost'           => array(
				'type'        => 'text',
				'title'       => __( 'File cost/price', 'woocommerce-product-addon' ),
				'desc'        => __( 'This will be added into cart', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'class'               => array(
				'type'        => 'text',
				'title'       => __( 'Class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'width'               => array(
				'type'        => 'select',
				'title'       => __( 'Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select width column.', 'woocommerce-product-addon' ),
				'options'     => ppom_get_input_cols(),
				'default'     => 12,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'button_label_select' => array(
				'type'        => 'text',
				'title'       => __( 'Button label (select files)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type button label e.g: Select Photos', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'button_class'        => array(
				'type'        => 'text',
				'title'       => __( 'Button class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type class for both (select, upload) buttons', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'files_allowed'       => array(
				'type'        => 'text',
				'title'       => __( 'Files allowed', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type number of files allowed per upload by user, e.g: 3', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'file_types'          => array(
				'type'        => 'text',
				'title'       => __( 'File types', 'woocommerce-product-addon' ),
				'desc'        => __( 'File types allowed seperated by comma, e.g: jpg,pdf,zip', 'woocommerce-product-addon' ),
				'default'     => 'jpg,pdf,zip',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'file_size'           => array(
				'type'        => 'text',
				'title'       => __( 'File size', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type size with units in kb|mb per file uploaded by user, e.g: 3mb', 'woocommerce-product-addon' ),
				'default'     => '1mb',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'min_img_h'           => array(
				'type'        => 'text',
				'title'       => __( 'Min Height', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide minimum image height.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_img_h'           => array(
				'type'        => 'text',
				'title'       => __( 'Max Height', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide maximum image height.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'min_img_w'           => array(
				'type'        => 'text',
				'title'       => __( 'Min Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide minimum image width.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_img_w'           => array(
				'type'        => 'text',
				'title'       => __( 'Max Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide maximum image width.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'img_dimension_error' => array(
				'type'        => 'text',
				'title'       => __( 'Error Message', 'woocommerce-product-addon' ),
				'desc'        => __( 'Provide image dimension error message. It will display on frontend while uploading the image.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility'          => array(
				'type'    => 'select',
				'title'   => __( 'Visibility', 'woocommerce-product-addon' ),
				'desc'    => __( 'Set field visibility based on user.', 'woocommerce-product-addon' ),
				'options' => ppom_field_visibility_options(),
				'default' => 'everyone',
			),
			'visibility_role'     => array(
				'type'   => 'text',
				'title'  => __( 'User Roles', 'woocommerce-product-addon' ),
				'desc'   => __( 'Role separated by comma.', 'woocommerce-product-addon' ),
				'hidden' => true,
			),
			'desc_tooltip'        => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show tooltip (PRO)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Description in Tooltip with Help Icon', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'required'            => array(
				'type'        => 'checkbox',
				'title'       => __( 'Required', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select this if it must be required.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'onetime'             => array(
				'type'        => 'checkbox',
				'title'       => __( 'Fixed Fee', 'woocommerce-product-addon' ),
				'desc'        => __( 'Add one time fee to cart total.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'logic'               => array(
				'type'  => 'checkbox',
				'title' => __( 'Enable Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
			'conditions'          => array(
				'type'  => 'html-conditions',
				'title' => __( 'Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
		);

		$type = 'file';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
