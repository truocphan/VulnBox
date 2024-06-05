<?php

$settings = array(

	array(
		'callback' 		=> 'textarea',
		'title' 		=> 'Custom CSS',
		'id' 			=> 'm-custom-css',
		'section_id' 	=> 'av_main',
		'default' 		=> '',
		'args' 			=> array(
			'rows' => 20,
			'cols' => 70
		)
	),

	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Show error log messages',
		'id' 			=> 'm-error-log',
		'section_id' 	=> 'av_main',
		'default' 		=> 'yes',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Open Login popup class',
		'id' 			=> 'm-login-class',
		'section_id' 	=> 'av_main',
		'default' 		=> '',
		'desc' 			=> 'Add your custom class here to trigger login popup'
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Open Register popup class',
		'id' 			=> 'm-register-class',
		'section_id' 	=> 'av_main',
		'default' 		=> '',
		'desc' 			=> 'Add your custom class here to trigger signup popup'
	),

);


return apply_filters( 'xoo_el_admin_settings', $settings, 'advanced' );

?>