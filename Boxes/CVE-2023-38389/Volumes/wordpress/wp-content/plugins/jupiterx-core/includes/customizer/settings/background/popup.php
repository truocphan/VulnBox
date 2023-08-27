<?php
/**
 * Add Jupiter Backgrounds popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.20.0
 */

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_background', [
	'title'    => __( 'Backgrounds', 'jupiterx-core' ),
	'type'     => 'container',
	'priority' => 300,
	'tabs'     => [
		'styles' => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => array(
		'main_style' => array(
			'label' => __( 'Main', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'body_style' => array(
			'label' => __( 'Body', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
	),
	'group'    => 'theme_style',
	'icon'     => 'backgrounds',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
