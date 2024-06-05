<?php

//array( 'id', 'type', Title', extra args = array() )
$field_types =  array(
	'xoo_aff_text' => array(
		'xoo_aff_text',
		'text',
		'Text',
		array(
			'icon' => 'fas fa-font',
		)
	),

	'xoo_aff_textarea' => array(
		'xoo_aff_textarea',
		'textarea',
		'Text Area',
		array(
			'icon' => 'fas fa-font',
		)
	),
	'xoo_aff_number' => array(
		'xoo_aff_number',
		'number',
		'Number',
		array(
			'icon' => 'fas fa-sort-numeric-up',
		)
	),
	'xoo_aff_date' => array('xoo_aff_date',
		'date',
		'Date',
		array(
			'icon' => 'far fa-calendar-alt'
		),
	),
	'xoo_aff_checkbox_single' => array(
		'xoo_aff_checkbox_single',
		'checkbox_single',
		'Checkbox',
		array(
			'icon' => 'fas fa-check-square'
		)
	),
	'xoo_aff_checkbox_list' => array(
		'xoo_aff_checkbox_list',
		'checkbox_list',
		'Checkbox List',
		array(
			'icon' => 'fas fa-list-ul',
		)
	),
	'xoo_aff_radio' => array(
		'xoo_aff_radio',
		'radio', 'Radio List',
		array(
			'icon' => 'fas fa-dot-circle'
		)
	),
	'xoo_aff_select_list' => array(
		'xoo_aff_select_list',
		'select_list',
		'Select',
		array(
			'icon' => 'fas fa-angle-down',
		)
	),
	'xoo_aff_email' => array(
		'xoo_aff_email',
		'email',
		'Email',
		array(
			'icon' => 'fas fa-at',
		)
	),
	'xoo_aff_country' => array(
		'xoo_aff_country',
		'country',
		'Country',
		array(
			'icon' => 'fas fa-globe',
		)
	),
	'xoo_aff_states' => array(
		'xoo_aff_states',
		'states',
		'States',
		array(
			'icon' => 'fas fa-map-marker'
		)
	),
	'xoo_aff_phone_code' => array(
		'xoo_aff_phone_code',
		'phone_code',
		'Phone Code',
		array(
			'icon' => 'fas fa-code',
		)
	),
	'xoo_aff_phone' => array(
		'xoo_aff_phone',
		'phone',
		'Phone',
		array(
			'icon' => 'fas fa-phone',
		)
	),

	'xoo_aff_password' => array(
		'xoo_aff_password',
		'password',
		'Password',
		array(
			'icon' => 'fas fa-key',
		)
	),
);


return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_default_field_types', $field_types );