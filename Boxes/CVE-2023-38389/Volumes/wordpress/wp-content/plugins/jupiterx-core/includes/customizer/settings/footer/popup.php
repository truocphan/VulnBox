<?php
/**
 * Add Jupiter Footer popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_footer_settings_after_section', 'jupiterx_dependency_notice_handler', 10 );

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_footer', [
	'title'    => __( 'Footer', 'jupiterx-core' ),
	'type'     => 'container',
	'priority' => 130,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings'         => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'widgets_title'         => [
			'label' => __( 'Widgets Title', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_text'          => [
			'label' => __( 'Widgets Text', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_link'          => [
			'label' => __( 'Widgets Link', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_thumbnail'     => [
			'label' => __( 'Widgets Thumbnail', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_container'     => [
			'label' => __( 'Widgets Container', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_divider'       => [
			'label' => __( 'Widgets Divider', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widget_area_container' => [
			'label' => __( 'Widget Area Container', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'sub_copyright'         => [
			'label' => __( 'Sub Footer Copyright', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'sub_menu'              => [
			'label' => __( 'Sub Footer Menu', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'sub_container'         => [
			'label' => __( 'Sub Footer Container', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'empty_notice'      => array(
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
	],
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/assigning-the-footer-globally',
		'title' => __( 'Assigning the Footer Globally', 'jupiterx-core' ),
	],
	'group' => 'template_parts',
	'icon'  => 'footer',
] );

// Styles warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_footer_styles_warning',
	'section'         => 'jupiterx_footer_styles',
	'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
	'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
	'active_callback' => function() {
		return class_exists( '\ElementorPro\Plugin' ) && jupiterx_is_help_links();
	},
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
