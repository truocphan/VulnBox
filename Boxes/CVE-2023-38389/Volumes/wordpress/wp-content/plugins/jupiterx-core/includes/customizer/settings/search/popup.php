<?php
/**
 * Add Jupiter Search Page popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_search', [
	'priority' => 310,
	'title' => __( 'Search Page', 'jupiterx-core' ),
	'type'  => 'container',
	'tabs'  => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/displaying-search-results-from-specific-post-types',
		'title' => __( 'Displaying Search Results from specific Post Types', 'jupiterx-core' ),
	],
	'group' => 'specific_pages',
	'icon'  => 'search-page',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
