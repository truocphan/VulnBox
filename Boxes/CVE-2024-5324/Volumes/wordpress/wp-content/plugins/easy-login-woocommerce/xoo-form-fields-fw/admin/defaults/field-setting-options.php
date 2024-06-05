<?php

$countries = (array) include XOO_AFF_DIR.'/countries/countries.php';

$field_settings = array(

	'active' 	=> array(
		'type' 		=> 'checkbox',
		'id'		=> 'active',
		'section' 	=> 'basic',	
		'title' 	=> 'Active',
		'width'		=> 'half',
		'value'		=> 'yes',
	),

	'required' 	=> array(
		'type' 		=> 'checkbox',
		'id'		=> 'required',
		'section' 	=> 'basic',
		'title' 	=> 'Required (*)',
		'width'		=> 'half',
		'value'		=> 'no',
	),


	'phone_code_display_type' => array(
		'type' 		=> 'select',
		'id'		=> 'phone_code_display_type',
		'section' 	=> 'basic',
		'title' 	=> 'Display Type',
		'width'		=> 'half',
		'value'		=> 'select',
		'options' 	=> array(
			'input' 	=> 'Input',
			'select' 	=> 'Select' 
		)
	),

	'label' => array(
		'type' 		=> 'text',
		'id'		=> 'label',
		'section' 	=> 'basic',
		'title' 	=> 'Label',
		'width'		=> 'half',
		'value'		=> '',
		'translate' => 'yes'
	),

	'default'  => array(
		'type' 		=> 'text',
		'id'		=> 'default',
		'section' 	=> 'basic',
		'title' 	=> 'Default',
		'width'		=> 'half',
		'value'		=> '',
	),

	'icon' => array(
		'type' 			=> 'iconpicker',
		'id'			=> 'icon',
		'section' 		=> 'basic',
		'title' 		=> 'Input Icon',
		'width'			=> 'half',
		'placeholder' 	=> 'Click here'
	),

	'placeholder' => array(
		'type' 			=> 'text',
		'id'			=> 'placeholder',
		'section' 		=> 'basic',
		'title' 		=> 'Placeholder',
		'width'			=> 'half',
		'value'			=> '',
		'translate' 	=> 'yes'
	),


	'minlength'	=> array(
		'type' 		=> 'number',
		'id'		=> 'minlength',
		'section' 	=> 'basic',
		'title' 	=> 'Minimum Characters',
		'width'		=> 'half',
		'value'		=> '',
	),

	'maxlength'	=> array(
		'type' 		=> 'number',
		'id'		=> 'maxlength',
		'section' 	=> 'basic',
		'title' 	=> 'Maximum Characters',
		'width'		=> 'half',
		'value'		=> '',
	),


	'min' => array(
		'type' 		=> 'number',
		'id'		=> 'min',
		'section' 	=> 'basic',
		'title' 	=> 'Minimum Value',
		'width'		=> 'half',
		'value'		=> '1',
	),


	'max' => array(
		'type' 		=> 'number',
		'id'		=> 'max',
		'section' 	=> 'basic',
		'title' 	=> 'Maximum Value',
		'width'		=> 'half',
		'value'		=> '',
	),


	'step' => array(
		'type' 		=> 'text',
		'id'		=> 'step',
		'section' 	=> 'basic',
		'title' 	=> 'Step',
		'width'		=> 'half',
		'value'		=> 'any',
		'info' 		=> 'Value by which number should increase. Type "any" for any value'
	),


	'date' 		=> array(
		'type' 		=> 'text',
		'id'		=> 'date',
		'section' 	=> 'basic',
		'title' 	=> 'Date',
		'width'		=> 'half',
		'value'		=> '',
	),


	'date_format' => array(
		'type' 		=> 'select',
		'id'		=> 'date_format',
		'section' 	=> 'basic',
		'title' 	=> 'Date Format',
		'options' 	=> array(
			'dd/mm/yy' 	=> 'dd/mm/yy',
			'mm/dd/yy' 		=> 'mm/dd/yy',
			'yy-mm-dd' 		=> 'yy-mm-dd',
			'd M, y'   		=> 'd M, y',
			'd MM, y'  		=> 'd MM, y',
			'DD, d MM, yy' 	=> 'DD, d MM, yy',
			"'day' d 'of' MM 'in the year' yy" => "'day' d 'of' MM 'in the year' yy"
		),
		'width'		=> 'half',
		'value'		=> 'dd/mm/yy',
	),


	'cols' => array(
		'type' 		=> 'select',
		'id'		=> 'cols',
		'section' 	=> 'basic',
		'title' 	=> 'Use Column',
		'options' 	=> array(
			'one' 			=> '1',
			'onehalf' 		=> '1/2',
			'onethird'  	=> '1/3',
			'onefourth' 	=> '1/4',
			'twothird' 		=> '2/3',
			'threefourth'	=> '3/4',
		),
		'width'		=> 'half',
		'value'		=> 'one',
	),


	'checkbox_single' => array(
		'type' 		=> 'checkbox_single',
		'id'		=> 'checkbox_single',
		'section' 	=> 'basic',
		'title' 	=> 'Checkbox',
		'width'		=> 'full',
		'value' 	=> array(
			'first' => array(
				'value' 	=> 'first',
				'label' 	=> 'First Checkbox Title',
				'checked' 	=> 'checked'
			)
		),
		'translate' => 'yes'
	),

	'checkbox_list' => array(
		'type' 		=> 'checkbox_list',
		'id'		=> 'checkbox_list',
		'section' 	=> 'basic',
		'title' 	=> 'Checkboxes',
		'width'		=> 'full',
		'value' 	=> array(
			'first' => array(
				'value' 	=> 'first',
				'label' 	=> 'First Checkbox Title',
				'checked' 	=> 'checked'
			),
			'second' => array(
				'value' 	=> 'second',
				'label' 	=> 'Second Checkbox Title',
				'checked' 	=> ''
			)
		),
		'sort' 		=> 'yes',
		'translate' => 'yes'
	),

	'radio' => array(
		'type' 		=> 'radio',
		'id'		=> 'radio',
		'section' 	=> 'basic',
		'title' 	=> 'Radio List',
		'width'		=> 'full',
		'value' 	=> array(
			'first' => array(
				'value' 	=> 'first',
				'label' 	=> 'First Radio Title',
				'checked' 	=> 'checked'
			),
			'second' => array(
				'value' 	=> 'second',
				'label' 	=> 'Second Radio Title',
				'checked' 	=> ''
			)
		),
		'sort' 		=> 'yes',
		'translate' => 'yes'
	),


	'select_list' => array(
		'type' 		=> 'select_list',
		'id'		=> 'select_list',
		'section' 	=> 'basic',
		'title' 	=> 'Select',
		'width'		=> 'full',
		'value' 	=> array(
			'first' => array(
				'value' 	=> 'first',
				'label' 	=> 'First Select Title',
				'checked' 	=> 'checked'
			),
			'second' => array(
				'value' 	=> 'second',
				'label' 	=> 'Second Select Title',
				'checked' 	=> ''
			)
		),
		'sort' 		=> 'yes',
		'translate' => 'yes'
	),
	
	'country_list' => array(
		'type' 		=> 'select',
		'id'		=> 'country_list',
		'section' 	=> 'basic',
		'title' 	=> 'Countries',
		'options' 	=> array(
			'all' 		=> 'All countries',
			'all_but' 	=> 'All countries except..',
			'only'  	=> 'Specific countries',
		),
		'width'		=> 'half',
		'value'		=> 'all',
		'new_row' 	=> "yes"
	),

	'country_choose' => array(
		'type' 			=> 'select_multiple',
		'id'			=> 'country_choose',
		'section' 		=> 'basic',
		'title' 		=> 'Choose Countries',
		'placeholder'	=> 'Start typing..',
		'options' 		=> $countries,
		'width'			=> 'half',
		'value'			=> '',
		'translate' 	=> 'yes'
	),


	'for_country_id' => array(
		'type' 		=> 'text',
		'id'		=> 'for_country_id',
		'section' 	=> 'basic',
		'title' 	=> 'Country Field ID',
		'width'		=> 'half',
		'value'		=> '',
		'info'		=> 'If you have a country field & wants to auto fill states based on the country selected by user on frontend. Place the country field ID here',
		'required' 	=> 'yes'
	),

	'linked_to' => array(
		'type' 		=> 'text',
		'id'		=> 'linked_to',
		'section' 	=> 'basic',
		'title' 	=> 'Linked Field',
		'width'		=> 'half',
		'value'		=> '',
		'info'		=> 'Put linked Field ID here',
		'required' 	=> 'yes'
	),


	'strength_meter' => array(
		'type' 		=> 'checkbox',
		'id'		=> 'strength_meter',
		'section' 	=> 'basic',	
		'title' 	=> 'Strength Meter',
		'width'		=> 'half',
		'value'		=> 'yes',
	),


	'strength_meter_pass' => array(
		'type' 		=> 'select',
		'id'		=> 'strength_meter_pass',
		'section' 	=> 'basic',
		'title' 	=> 'Password acceptance strength',
		'width'		=> 'half',
		'value'		=> '3',
		'options' 	=> array(
			'0' 	=> 'Short(0)',
			'1' 	=> 'Bad(1)',
			'2'		=> 'Bad(2)',
			'3' 	=> 'Good(3)',
			'4' 	=> 'Strong(4)'
		),
		'info'		=> 'Accept password if strength is equals to or above this status',
	),


	'password_visibility' => array(
		'type' 		=> 'checkbox',
		'id'		=> 'password_visibility',
		'section' 	=> 'basic',	
		'title' 	=> 'Password Visibility Toggle',
		'width'		=> 'half',
		'value'		=> 'yes',
	),

	'ta_rows'	=> array(
		'type' 		=> 'number',
		'id'		=> 'rows',
		'section' 	=> 'basic',
		'title' 	=> 'Rows',
		'width'		=> 'half',
		'value'		=> '2',
	),

	'ta_cols'	=> array(
		'type' 		=> 'number',
		'id'		=> 'ta_cols',
		'section' 	=> 'basic',
		'title' 	=> 'Columns',
		'width'		=> 'half',
		'value'		=> '40',
	),


	/**
	  * Advanced section
	**/

	'unique_id' => array(
		'type' 		=> 'text',
		'id'		=> 'unique_id',
		'section' 	=> 'advanced',
		'title' 	=> 'Unique ID/Name',
		'width'		=> 'half',
		'value'		=> '',
		'info'		=> 'Leave it default, if you don\'t know what you are using it for. Keep it very unique. Start it with xoo_aff_',
	),

	'class'		=> array(
		'type' 		=> 'text',
		'id'		=> 'class',
		'section' 	=> 'advanced',
		'title' 	=> 'Extra CSS Class',
		'width'		=> 'half',
		'value'		=> '',
	),

	

);

return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_field_setting_options', $field_settings );
