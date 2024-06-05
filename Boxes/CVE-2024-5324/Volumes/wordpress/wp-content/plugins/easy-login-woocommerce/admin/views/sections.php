<?php

$sections = array(

	/* General TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'gl_main',
		'tab' 	=> 'general',
	),


	array(
		'title' => 'WooCommerce Settings',
		'id' 	=> 'gl_wc',
		'tab' 	=> 'general',
	),


	array(
		'title' => 'Popup',
		'id' 	=> 'gl_popup',
		'tab' 	=> 'general',
	),


	array(
		'title' => 'Auto Open Popup',
		'id' 	=> 'gl_ao',
		'tab' 	=> 'general',
	),


	array(
		'title' => 'Redirects',
		'id' 	=> 'gl_red',
		'tab' 	=> 'general',
	),

	array(
		'title' => 'Texts',
		'id' 	=> 'gl_texts',
		'tab' 	=> 'general',
		'desc' 	=> 'Leave text empty to remove element'
	),


	/* Style TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'sy_main',
		'tab' 	=> 'style',
	),


	array(
		'title' => 'Pop-up',
		'id' 	=> 'sy_popup',
		'tab' 	=> 'style',
	),


	array(
		'title' => 'Form',
		'id' 	=> 'sy_form',
		'tab' 	=> 'style',
	),


	array(
		'title' => 'Header Tab Settings',
		'id' 	=> 'sy_tab',
		'tab' 	=> 'style',
	),



	array(
		'title' => 'Form Fields',
		'id' 	=> 'sy_fields',
		'tab' 	=> 'style',
	),

	/* Custom CSS TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'av_main',
		'tab' 	=> 'advanced',
	),
);

return apply_filters( 'xoo_el_admin_settings_sections', $sections );