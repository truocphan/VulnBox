<?php
/*
 * Followig class handling pre-uploaded image control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Image_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Images', 'woocommerce-product-addon' );
		$this->desc     = __( 'Images selection', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-picture-o" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	private function get_settings() {

		$input_meta = array(
			'title'                    => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'It will be shown as field label', 'woocommerce-product-addon' ),
			),
			'data_name'                => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'description'              => array(
				'type'  => 'textarea',
				'title' => __( 'Description', 'woocommerce-product-addon' ),
				'desc'  => __( 'Small description, it will be display near name title.', 'woocommerce-product-addon' ),
			),

			'error_message'            => array(
				'type'  => 'text',
				'title' => __( 'Error message', 'woocommerce-product-addon' ),
				'desc'  => __( 'Insert the error message for validation.', 'woocommerce-product-addon' ),
			),
			'class'                    => array(
				'type'        => 'text',
				'title'       => __( 'Class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'width'                    => array(
				'type'        => 'select',
				'title'       => __( 'Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select width column.', 'woocommerce-product-addon' ),
				'options'     => ppom_get_input_cols(),
				'default'     => 12,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'selected_img_bordercolor' => array(
				'type'        => 'color',
				'title'       => __( 'Selected Image Border Color', 'woocommerce-product-addon' ),
				'desc'        => __( 'Change selected images border color, e.g: #fff', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'images'                   => array(
				'type'  => 'pre-images',
				'title' => __( 'Select images', 'woocommerce-product-addon' ),
				'desc'  => __( 'Select images from media library', 'woocommerce-product-addon' ),
			),
			'selected'                 => array(
				'type'        => 'text',
				'title'       => __( 'Selected image', 'woocommerce-product-addon' ),
				'desc'        => __( 'Type option title given in (Add Images) tab if you want it already selected.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'image_width'              => array(
				'type'        => 'text',
				'title'       => __( 'Image Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Change image width e,g: 50px or 50%.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'image_height'             => array(
				'type'        => 'text',
				'title'       => __( 'Image Height', 'woocommerce-product-addon' ),
				'desc'        => __( 'Change image height e,g: 50px or 50%. ', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'min_checked'              => array(
				'type'        => 'text',
				'title'       => __( 'Min. Image Select', 'woocommerce-product-addon' ),
				'desc'        => __( 'How many Images can be checked by user e.g: 2. Leave blank for default.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_checked'              => array(
				'type'        => 'text',
				'title'       => __( 'Max. Image Select', 'woocommerce-product-addon' ),
				'desc'        => __( 'How many Images can be checked by user e.g: 3. Leave blank for default.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility'               => array(
				'type'    => 'select',
				'title'   => __( 'Visibility', 'woocommerce-product-addon' ),
				'desc'    => __( 'Set field visibility based on user.', 'woocommerce-product-addon' ),
				'options' => ppom_field_visibility_options(),
				'default' => 'everyone',
			),
			'visibility_role'          => array(
				'type'   => 'text',
				'title'  => __( 'User Roles', 'woocommerce-product-addon' ),
				'desc'   => __( 'Role separated by comma.', 'woocommerce-product-addon' ),
				'hidden' => true,
			),
			'legacy_view'              => array(
				'type'        => 'checkbox',
				'title'       => __( 'Enable legacy view', 'woocommerce-product-addon' ),
				'desc'        => __( 'Tick it to turn on old boxes view for images', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'multiple_allowed'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Multiple selections?', 'woocommerce-product-addon' ),
				'desc'        => __( 'Allow users to select more then one images?.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'show_popup'               => array(
				'type'        => 'checkbox',
				'title'       => __( 'Popup', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show big image on hover', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'desc_tooltip'             => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show tooltip (PRO)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Description in Tooltip with Help Icon', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'required'                 => array(
				'type'        => 'checkbox',
				'title'       => __( 'Required', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select this if it must be required.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'logic'                    => array(
				'type'  => 'checkbox',
				'title' => __( 'Enable Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
			'conditions'               => array(
				'type'  => 'html-conditions',
				'title' => __( 'Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
		);

		$type = 'image';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
