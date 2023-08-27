<?php
/**
 * Add Jupiter Go to Top popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.20.0
 */

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_go_to_top', [
	'title'    => __( 'Go to Top', 'jupiterx-core' ),
	'type'     => 'container',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'styles' => [
			'label' => __( 'Styles', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'empty_notice'      => [
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		],
	],
	'preview' => true,
	'group' => 'elements',
	'icon'  => 'go-to-top',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
