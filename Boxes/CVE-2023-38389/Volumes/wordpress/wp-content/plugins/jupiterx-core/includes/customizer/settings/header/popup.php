<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_header_settings_after_section', 'jupiterx_dependency_notice_handler', 10 );

// Header popup.
JupiterX_Customizer::add_section(
	'jupiterx_header',
	array(
		'priority' => 50,
		'title'    => __( 'Header', 'jupiterx-core' ),
		'type'     => 'container',
		'tabs'     => array(
			'settings' => __( 'Settings', 'jupiterx-core' ),
			'styles'   => __( 'Styles', 'jupiterx-core' ),
		),
		'boxes' => array(
			'settings'             => array(
				'label' => __( 'Settings', 'jupiterx-core' ),
				'tab'   => 'settings',
			),
			'logo'             => array(
				'label' => __( 'Logo', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'menu'             => array(
				'label' => __( 'Menu', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'submenu'          => array(
				'label' => __( 'Submenu', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'search'           => array(
				'label' => __( 'Search', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'container'        => array(
				'label' => __( 'Container', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'sticky_container' => array(
				'label' => __( 'Sticky Container', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'sticky_logo'      => array(
				'label' => __( 'Sticky Logo', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'empty_notice'      => array(
				'label' => __( 'Notice', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
		),
		'help'     => array(
			'url'   => 'https://themes.artbees.net/docs/assigning-the-header-globally',
			'title' => __( 'Assigning the Header Globally', 'jupiterx-core' ),
		),
		'group'    => 'template_parts',
		'icon'     => 'header',
	)
);

// Styles warning.
JupiterX_Customizer::add_field(
	array(
		'type'            => 'jupiterx-alert',
		'settings'        => 'jupiterx_header_styles_warning',
		'section'         => 'jupiterx_header_styles',
		'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
		'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
		'active_callback' => function() {
			return class_exists( '\ElementorPro\Plugin' ) && jupiterx_is_help_links();
		},
	)
);

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
