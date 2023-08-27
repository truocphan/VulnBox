<?php
/**
 * Add Jupiter Layout popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

JupiterX_Customizer::add_section( 'jupiterx_site', [
	'priority' => 330,
	'title'    => __( 'Layout', 'jupiterx-core' ),
	'type'     => 'container',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'body_border' => [
			'label' => __( 'Body Border', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'container' => [
			'label' => __( 'Container', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'empty_notice'      => [
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		],
	],
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/setting-container-width-in-jupiter-x',
		'title' => __( 'Setting container width in Jupiter X', 'jupiterx-core' ),
	],
	'group' => 'theme_style',
	'icon'  => 'layout',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
