<?php

$tabs = array(
	'general' => array(
		'title'			=> 'General',
		'id' 			=> 'general',
		'option_key' 	=> 'xoo-el-gl-options'
	),

	'style' => array(
		'title'			=> 'Style',
		'id' 			=> 'style',
		'option_key' 	=> 'xoo-el-sy-options'
	),

	'advanced' => array(
		'title'			=> 'Advanced',
		'id' 			=> 'advanced',
		'option_key' 	=> 'xoo-el-av-options'
	),
);

return apply_filters( 'xoo_el_admin_settings_tabs', $tabs );