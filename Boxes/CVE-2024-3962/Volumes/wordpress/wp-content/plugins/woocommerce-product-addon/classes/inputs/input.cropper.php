<?php
/*
 * Followig class handling image cropping
*/

class NM_Cropper_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Image Cropper', 'woocommerce-product-addon' );
		$this->desc     = __( 'Crop images', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-crop" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}


	// 'link' => __ ( '<a href="https://github.com/RobinHerbots/Inputmask" target="_blank">Options</a>', 'woocommerce-product-addon' ) 

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
				'default'     => 1,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'file_types'          => array(
				'type'        => 'text',
				'title'       => __( 'Image types', 'woocommerce-product-addon' ),
				'desc'        => __( 'Image types allowed seperated by comma, e.g: jpg,png', 'woocommerce-product-addon' ),
				'default'     => 'jpg,png',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'file_size'           => array(
				'type'        => 'text',
				'title'       => __( 'Image size', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type size with units in kb|mb per file uploaded by user, e.g: 3mb', 'woocommerce-product-addon' ),
				'default'     => '1mb',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'selected'            => array(
				'type'        => 'text',
				'title'       => __( 'Selected option', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type option name given in (Add Options) tab if you want already selected.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'first_option'        => array(
				'type'        => 'text',
				'title'       => __( 'First option', 'woocommerce-product-addon' ),
				'desc'        => __( 'Just for info e.g: Select your option.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'options'             => array(
				'type'  => 'paired-cropper',
				'title' => __( 'Viewport Size', 'woocommerce-product-addon' ),
				'desc'  => __( 'Add Options', 'woocommerce-product-addon' ),
			),
			'viewport_type'       => array(
				'type'        => 'select',
				'title'       => __( 'Viewport type', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select Squar or circle, see help', 'woocommerce-product-addon' ),
				'options'     => array(
					'square' => 'Square',
					'circle' => 'Circle',
				),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'boundary'            => array(
				'type'        => 'text',
				'title'       => __( 'Boundary height,width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Separated by command h,w e.g: 200,200, see help', 'woocommerce-product-addon' ),
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
			'onetime_taxable'     => array(
				'type'        => 'checkbox',
				'title'       => __( 'Fee Taxable?', 'woocommerce-product-addon' ),
				'desc'        => __( 'Calculate Tax for Fixed Fee', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'enforce_boundary'    => array(
				'type'        => 'checkbox',
				'title'       => __( 'Enforce Boundary', 'woocommerce-product-addon' ),
				'desc'        => __( 'Restricts zoom so image cannot be smaller than viewport.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'resize'              => array(
				'type'        => 'checkbox',
				'title'       => __( 'Allow Resize', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show cropping handler resize.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'enable_zoom'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Enable Zoom', 'woocommerce-product-addon' ),
				'desc'        => __( 'Enable zooming functionality. If set to false - scrolling and pinching would not zoom.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'show_zoomer'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show Zoomer', 'woocommerce-product-addon' ),
				'desc'        => __( 'Hide or Show the zoom slider.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'enable_exif'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Enable Exif', 'woocommerce-product-addon' ),
				'desc'        => __( 'Enable zooming functionality. If set to false - scrolling and pinching would not zoom.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
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

		$type = 'cropper';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
