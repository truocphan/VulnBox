<?php

$settings = array(
	
	'style-section' => array(
		'type' 			=> 'section',
		'callback' 		=> 'section',
		'id' 			=> 'style-section',
		'title' 		=> 'Style',
	),

	's-show-icons' => array(
		'type' 			=> 'setting',
		'callback' 		=> 'checkbox',
		'section' 		=> 'style-section',
		'id'			=> 's-show-icons',
		'title' 		=> 'Show Icons',
		'default' 		=> 'yes',
	),


	's-icon-size' => array(
		'type' 			=> 'setting',
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'id'			=> 's-icon-size',
		'title' 		=> 'Icon Size',
		'default' 		=> '14',
		'desc'			=> 'in px'
	),


	's-icon-width' => array(
		'type' 			=> 'setting',
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'id'			=> 's-icon-width',
		'title' 		=> 'Icon Container Width',
		'default' 		=> '40',
		'desc'			=> 'in px'
	),


	's-icon-bgcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-icon-bgcolor',
		'title' 		=> 'Icon BG Color',
		'default' 		=> ' #eee'
	),


	's-icon-color' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-icon-color',
		'title' 		=> 'Icon Color',
		'default' 		=> '#555'
	),


	's-icon-borcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-icon-borcolor',
		'title' 		=> 'Icon Border Color',
		'default' 		=> '#cccccc',
	),

	's-icon-borwidth' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'id'			=> 's-icon-borwidth',
		'title' 		=> 'Icon Border Width',
		'default' 		=> '1',
	),



	's-field-bmargin' => array(
		'type' 			=> 'setting',
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'id'			=> 's-field-bmargin',
		'title' 		=> 'Field Bottom Margin',
		'default' 		=> '30',
		'desc'			=> 'gap between two field rows ( in px )'
	),

	's-input-height' => array(
		'type' 			=> 'setting',
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'id'			=> 's-input-height',
		'title' 		=> 'Field Height',
		'default' 		=> '50',
	),

	's-input-bgcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-input-bgcolor',
		'title' 		=> 'Input Fields Background Color',
		'default' 		=> '#fff'
	),

	's-input-txtcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-input-txtcolor',
		'title' 		=> 'Input Text Color',
		'default' 		=> '#777'
	),


	's-input-borcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-input-borcolor',
		'title' 		=> 'Input Border Color',
		'default' 		=> '#cccccc',
	),

	's-input-borwidth' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'id'			=> 's-input-borwidth',
		'title' 		=> 'Input Border Width',
		'default' 		=> '1',
	),

	's-input-focusbgcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-input-focusbgcolor',
		'title' 		=> 'Input Focus Background color',
		'default' 		=> '#ededed'
	),

	's-input-focustxtcolor' => array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'id'			=> 's-input-focustxtcolor',
		'title' 		=> 'Input Focus text color',
		'default' 		=> '#000'
	),


);

return $settings;


?>