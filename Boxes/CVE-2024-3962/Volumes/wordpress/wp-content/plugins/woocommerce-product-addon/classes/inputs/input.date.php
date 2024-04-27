<?php
/*
 * Followig class handling date input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Date_wooproduct extends PPOM_Inputs {

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

		$this->title    = __( 'Date Input', 'woocommerce-product-addon' );
		$this->desc     = __( 'regular date input', 'woocommerce-product-addon' );
		$this->icon     = __( '<i class="fa fa-calendar" aria-hidden="true"></i>', 'woocommerce-product-addon' );
		$this->settings = self::get_settings();

	}

	private function get_settings() {

		$input_meta = array(
			'title'             => array(
				'type'  => 'text',
				'title' => __( 'Title', 'woocommerce-product-addon' ),
				'desc'  => __( 'It will be shown as field label', 'woocommerce-product-addon' ),
			),
			'data_name'         => array(
				'type'  => 'text',
				'title' => __( 'Data name', 'woocommerce-product-addon' ),
				'desc'  => __( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note: Use only lowercase characters and underscores.', 'woocommerce-product-addon' ),
			),
			'description'       => array(
				'type'  => 'textarea',
				'title' => __( 'Description', 'woocommerce-product-addon' ),
				'desc'  => __( 'Small description, it will be display near name title.', 'woocommerce-product-addon' ),
			),
			'placeholder'       => array(
				'type'  => 'text',
				'title' => __( 'Placeholder', 'woocommerce-product-addon' ),
				'desc'  => __( 'Optional.', 'woocommerce-product-addon' ),
			),
			'error_message'     => array(
				'type'  => 'text',
				'title' => __( 'Error message', 'woocommerce-product-addon' ),
				'desc'  => __( 'Insert the error message for validation.', 'woocommerce-product-addon' ),
			),
			'class'             => array(
				'type'        => 'text',
				'title'       => __( 'Class', 'woocommerce-product-addon' ),
				'desc'        => __( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'width'             => array(
				'type'        => 'select',
				'title'       => __( 'Width', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select width column.', 'woocommerce-product-addon' ),
				'options'     => ppom_get_input_cols(),
				'default'     => 12,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'date_formats'      => array(
				'type'        => 'select',
				'title'       => __( 'Date format', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] Select your preferred date format.', 'woocommerce-product-addon' ),
				'options'     => ppom_get_date_formats(),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'default_value'     => array(
				'type'        => 'text',
				'title'       => __( 'Default Date', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] The default highlighted date if the date field is blank.  Enter a date or use shortcode (examples: +10d, +17d, +1m +7d). Full dates should follow the same date format you have selected for this field.', 'woocommerce-product-addon' ),
				'link'        => __( '<a target="_blank" href="https://api.jqueryui.com/datepicker/#option-defaultDate">Example</a>', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'min_date'          => array(
				'type'        => 'text',
				'title'       => __( 'Min Date', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] The earliest selectable date. Enter a date or use shortcode (examples: +10d, +17d, +1m +7d). Full dates should follow the same date format you have selected for this field.', 'woocommerce-product-addon' ),
				'link'        => __( '<a target="_blank" href="https://api.jqueryui.com/datepicker/#option-minDate">Example</a>', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'max_date'          => array(
				'type'        => 'text',
				'title'       => __( 'Max Date', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] The maximum selectable date. Enter a date or use shortcode (examples: +10d, +17d, +1m +7d). Full dates should follow the same date format you have selected for this field.', 'woocommerce-product-addon' ),
				'link'        => __( '<a target="_blank" href="https://api.jqueryui.com/datepicker/#option-maxDate">Example</a>', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'year_range'        => array(
				'type'        => 'text',
				'title'       => __( 'Year Range', 'woocommerce-product-addon' ),
				'desc'        => sprintf( esc_html__( '[ This feature requires jQuery datePicker ] Years to allow date selections. Example: c-10:c+10. TIP: The letter "c" indicates the current year so "c+1" will indicate next year.  Thus c:c+1 will be %s:%s', 'woocommerce-product-addon'), date( 'Y' ), date( 'Y', strtotime( '+1 year' ) ) ),
				'link'        => __( '<a target="_blank" href="https://api.jqueryui.com/datepicker/#option-yearRange">Example</a>', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'first_day_of_week' => array(
				'type'        => 'select',
				'title'       => __( 'First day of week', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] First day of the week to show on the popup calendar.', 'woocommerce-product-addon' ),
				'link'        => __( '<a target="_blank" href="https://api.jqueryui.com/datepicker/#option-firstDay">Example</a>', 'woocommerce-product-addon' ),
				'options'     => array(
					0 => 'Sunday',
					1 => 'Monday',
					2 => 'Tuesday',
					3 => 'Wednesday',
					4 => 'Thursday',
					5 => 'Friday',
					6 => 'Saturday',
				),
				'default'     => 0,
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility'        => array(
				'type'        => 'select',
				'title'       => __( 'Visibility', 'woocommerce-product-addon' ),
				'desc'        => __( 'Set field visibility based on user.', 'woocommerce-product-addon' ),
				'options'     => ppom_field_visibility_options(),
				'default'     => 'everyone',
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'visibility_role'   => array(
				'type'   => 'text',
				'title'  => __( 'User Roles', 'woocommerce-product-addon' ),
				'desc'   => __( 'Role separated by comma.', 'woocommerce-product-addon' ),
				'hidden' => true,
			),
			'jquery_dp'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'jQuery datePicker', 'woocommerce-product-addon' ),
				'desc'        => __( 'Enable jQuery datePicker over HTML5 date field.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'no_weekends'       => array(
				'type'        => 'checkbox',
				'title'       => __( 'Disable Weekends', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] Prevent display &amp; selection of weekends.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'past_dates'        => array(
				'type'        => 'checkbox',
				'title'       => __( 'Disable Past Dates', 'woocommerce-product-addon' ),
				'desc'        => __( '[ This feature requires jQuery datePicker ] Prevent selection of dates prior to today&rsquo;s date.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'desc_tooltip'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Show tooltip (PRO)', 'woocommerce-product-addon' ),
				'desc'        => __( 'Show Description in Tooltip with Help Icon', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'required'          => array(
				'type'        => 'checkbox',
				'title'       => __( 'Required', 'woocommerce-product-addon' ),
				'desc'        => __( 'Select this if it must be required.', 'woocommerce-product-addon' ),
				'col_classes' => array( 'col-md-3', 'col-sm-12' ),
			),
			'logic'             => array(
				'type'  => 'checkbox',
				'title' => __( 'Enable Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
			'conditions'        => array(
				'type'  => 'html-conditions',
				'title' => __( 'Conditions', 'woocommerce-product-addon' ),
				'desc'  => __( 'Tick it to turn conditional logic to work below', 'woocommerce-product-addon' ),
			),
		);

		$type = 'date';

		return apply_filters( "poom_{$type}_input_setting", $input_meta, $this );
	}
}
