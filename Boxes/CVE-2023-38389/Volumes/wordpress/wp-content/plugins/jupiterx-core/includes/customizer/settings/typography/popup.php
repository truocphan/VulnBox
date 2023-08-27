<?php
/**
 * Add Jupiter Fonts & Typography popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */



// Popup.
JupiterX_Customizer::add_section( 'jupiterx_typography', [
	'priority' => 150,
	'title'    => __( 'Typography', 'jupiterx-core' ),
	'type'     => 'container',
	'boxes'    => [
		'body'  => [
			'label' => __( 'Body', 'jupiterx-core' ),
		],
		'links' => [
			'label' => __( 'Links', 'jupiterx-core' ),
		],
		'h1'    => [
			'label' => __( 'Heading 1', 'jupiterx-core' ),
		],
		'h2'    => [
			'label' => __( 'Heading 2', 'jupiterx-core' ),
		],
		'h3'    => [
			'label' => __( 'Heading 3', 'jupiterx-core' ),
		],
		'h4'    => [
			'label' => __( 'Heading 4', 'jupiterx-core' ),
		],
		'h5'    => [
			'label' => __( 'Heading 5', 'jupiterx-core' ),
		],
		'h6'    => [
			'label' => __( 'Heading 6', 'jupiterx-core' ),
		],
	],
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/changing-typography-for-body-headings-and-links',
		'title' => __( 'Changing typography for Body, Headings and Links', 'jupiterx-core' ),
	],
	'group' => 'theme_style',
	'icon'  => 'typography',
] );

// Typography warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_typography_styles_warning',
	'section'         => 'jupiterx_typography_styles',
	'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
	'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
	'active_callback' => 'jupiterx_is_help_links',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
