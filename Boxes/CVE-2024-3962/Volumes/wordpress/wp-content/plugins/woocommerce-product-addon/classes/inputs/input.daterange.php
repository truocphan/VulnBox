<?php
/*
 * Followig class handling date input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Daterange_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'DateRange Input', 'woocommerce-product-addon' );
		$this->desc     = __( '<a href="http://www.daterangepicker.com/" target="_blank">More detail</a>', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-table" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	private function get_settings() {

		$input_meta = array(
			'title'           => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'All about Daterangepicker, see daterangepicker', 'woocommerce-product-addon' ),
				'link'  => __( '<a href="http://www.daterangepicker.com/" target="_blank">Daterangepicker</a>', 'woocommerce-product-addon' ),
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
			'open_style'      => array(
				'type'        => 'select',
				'title'       => __( 'Open Style', 'woocommerce-product-addon' ),
				'desc'        => __( 'Default is down.', 'woocommerce-product-addon' ),
				'options'     => array(
					'down' => 'Down',
					'up'   => 'Up',
				),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'date_formats'    => array(
				'type'        => 'text',
				'title'       => __( 'Format', 'woocommerce-product-addon' ),
				'desc'        => __( 'e.g MM-DD-YYYY, DD-MMM-YYYY', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'tp_increment'    => array(
				'type'        => 'text',
				'title'       => __( 'Timepicker increment', 'woocommerce-product-addon' ),
				'desc'        => __( 'e.g: 30', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'start_date'      => array(
				'type'        => 'text',
				'title'       => __( 'Start Date', 'woocommerce-product-addon' ),
				'desc'        => __( 'Must be same format as defined in above (Format) field.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'end_date'        => array(
				'type'        => 'text',
				'title'       => __( 'End Date', 'woocommerce-product-addon' ),
				'desc'        => __( 'Must be same format as defined in above (Format) field.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'min_date'        => array(
				'type'        => 'text',
				'title'       => __( 'Min Date', 'woocommerce-product-addon' ),
				'desc'        => __( 'e.g: 2017-02-25', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_date'        => array(
				'type'        => 'text',
				'title'       => __( 'Max Date', 'woocommerce-product-addon' ),
				'desc'        => __( 'e.g: 2017-09-15', 'woocommerce-product-addon' ),
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
			'time_picker'     => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show Timepicker', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Timepicker.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'tp_24hours'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show Timepicker 24 Hours', 'woocommerce-product-addon' ),
				'desc'        => __( 'Left blank for default', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'tp_seconds'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show Timepicker Seconds', 'woocommerce-product-addon' ),
				'desc'        => __( 'Left blank for default', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'drop_down'       => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show Dropdown', 'woocommerce-product-addon' ),
				'desc'        => __( 'Left blank for default', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'show_weeks'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show Week Numbers', 'woocommerce-product-addon' ),
				'desc'        => __( 'Left blank for default.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'auto_apply'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Auto Apply Changes', 'woocommerce-product-addon' ),
				'desc'        => __( 'Hide the Apply/Cancel button.', 'woocommerce-product-addon' ),
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

		$type = 'daterange';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
