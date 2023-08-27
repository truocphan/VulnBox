<?php
/**
 * Add Jupiter settings for Pages > 404 > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_404_settings_after_section', 'jupiterx_dependency_notice_handler', 10 );

JupiterX_Customizer::add_section( 'jupiterx_404', [
	'priority' => 330,
	'title' => __( '404', 'jupiterx-core' ),
	'type'  => 'container',
	'preview' => true,
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
		],
	],
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/setting-custom-template-for-404-page',
		'title' => __( 'Setting custom template for 404 page', 'jupiterx-core' ),
	],
	'group' => 'specific_pages',
	'icon'  => '404',
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_404_warning',
	'section'         => 'jupiterx_404',
	'box'             => 'settings',
	'label'           => __( 'Set the selected 404 page to "Private" to hide the page from search engines. Setting to private, does not affect the 404 functionality.', 'jupiterx-core' ),
	'jupiterx_url'    => '',
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_404_custom_templates_notice',
	'section'         => 'jupiterx_404',
	'box'             => 'settings',
	'label'           => jupiterx_customizer_custom_templates_notice(),
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_404_template',
	'section'     => 'jupiterx_404',
	'box'         => 'settings',
	'label'       => __( 'Template', 'jupiterx-core' ),
	'default'     => '',
	'placeholder' => __( 'None', 'jupiterx-core' ),
	'transport'   => 'postMessage',
	'preview'     => true,
	'jupiterx'    => [
		'select2' => [
			'action'    => 'jupiterx_core_customizer_get_select2_options',
			'post_type' => 'page',
		],
	],
] );

