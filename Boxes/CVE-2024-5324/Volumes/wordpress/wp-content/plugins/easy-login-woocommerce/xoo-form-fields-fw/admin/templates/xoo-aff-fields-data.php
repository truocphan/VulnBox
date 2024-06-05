<?php

$field_settings = array(

	'active' 	=> array(
		'type' 		=> 'checkbox',
		'id'		=> 'active',
		'title' 	=> 'Active',
		'width'		=> 'half',
		'value'		=> 'yes',	
	),

	'required' 	=> array(
		'type' 		=> 'checkbox',
		'id'		=> 'required',
		'title' 	=> 'Required (*)',
		'width'		=> 'half',
		'value'		=> 'no',
	),


	'show_label' => array(
		'type' 		=> 'checkbox',
		'id'		=> 'show_label',
		'title' 	=> 'Show Label',
		'width'		=> 'half',
		'value'		=> 'yes',
	),

	'label_text' => array(
		'type' 		=> 'text',
		'id'		=> 'label_text',
		'title' 	=> 'Label Text',
		'width'		=> 'half',
		'value'		=> '',
	),

	'default'  => array(
		'type' 		=> 'text',
		'id'		=> 'default',
		'title' 	=> 'Default',
		'width'		=> 'half',
		'value'		=> '',
	),

	'iconpicker' => array(
		'type' 			=> 'iconpicker',
		'id'			=> 'icon',
		'title' 		=> 'Input Icon',
		'width'			=> 'half',
		'placeholder' 	=> 'Click here'
	),

	'placeholder' => array(
		'type' 			=> 'text',
		'id'			=> 'placeholder',
		'title' 		=> 'Placeholder',
		'width'		=> 'half',
		'value'		=> '',
	),


	'min_char'	=> array(
		'type' 		=> 'number',
		'id'		=> 'minchar',
		'title' 	=> 'Minimum Characters',
		'width'		=> 'half',
		'value'		=> '',
	),

	'max_char'	=> array(
		'type' 		=> 'number',
		'id'		=> 'maxchar',
		'title' 	=> 'Maximum Characters',
		'width'		=> 'half',
		'value'		=> '',
	),

	'unique_id' => array(
		'type' 		=> 'text',
		'id'		=> 'uniqueid',
		'title' 	=> 'Unique ID/Name',
		'width'		=> 'half',
		'value'		=> '',
		'info'		=> 'Leave it default, if you don\'t know what you are using it for. Keep it very unique. Start it with xoo_aff_',
	),

	'class'		=> array(
		'type' 		=> 'text',
		'id'		=> 'class',
		'title' 	=> 'Extra CSS Class',
		'width'		=> 'half',
		'value'		=> '',
	),

	'date' 		=> array(
		'type' 		=> 'text',
		'id'		=> 'date',
		'title' 	=> 'Date',
		'width'		=> 'half',
		'value'		=> '',
	),


	'date_format' => array(
		'type' 		=> 'select',
		'id'		=> 'dateformat',
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
		'value'		=> '',
	),


	'cols' => array(
		'type' 		=> 'select',
		'id'		=> 'cols',
		'title' 	=> 'Use Column',
		'options' 	=> array(
			'one' 		=> '1',
			'onehalf' 	=> '1/2',
			'onethird'  => '1/3',
			'onefourth' => '1/4',
		),
		'width'		=> 'half',
		'value'		=> '',
	),


	'checkbox_single' => array(
		'type' 		=> 'checkbox_single',
		'id'		=> 'checkbox_single',
		'title' 	=> 'Checkbox',
		'width'		=> 'full',
		'value' 	=> array(
			'value' 	=> 'first',
			'label' 	=> 'First Checkbox Title',
			'checked' 	=> 'checked'
		)
	),

	'checkbox_list' => array(
		'type' 		=> 'checkbox_list',
		'id'		=> 'checkbox_list',
		'title' 	=> 'Checkboxes',
		'width'		=> 'full',
		'value' 	=> array(
			array(
				'value' 	=> 'first',
				'label' 	=> 'First Checkbox Title',
				'checked' 	=> 'checked'
			),
			array(
				'value' 	=> 'second',
				'label' 	=> 'Second Checkbox Title',
				'checked' 	=> ''
			)
		)
	),

	'radio' => array(
		'type' 		=> 'radio',
		'id'		=> 'radio',
		'title' 	=> 'Radio List',
		'width'		=> 'full',
		'value' 	=> array(
			array(
				'value' 	=> 'first',
				'label' 	=> 'First Radio Title',
				'checked' 	=> 'checked'
			),
			array(
				'value' 	=> 'second',
				'label' 	=> 'Second Radio Title',
				'checked' 	=> ''
			)
		)
	),


	'select_list' => array(
		'type' 		=> 'select_list',
		'id'		=> 'select_list',
		'title' 	=> 'Select',
		'width'		=> 'full',
		'value' 	=> array(
			array(
				'value' 	=> 'first',
				'label' 	=> 'First Select Title',
				'checked' 	=> 'checked'
			),
			array(
				'value' 	=> 'second',
				'label' 	=> 'Second Select Title',
				'checked' 	=> ''
			)
		)
	),
);

return apply_filters( 'admin_default_field_settings', $field_settings );
