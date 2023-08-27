<?php
/**
 * Add Jupiter Maintenance Page popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_maintenance_settings_after_section', 'jupiterx_dependency_notice_handler', 10 );

JupiterX_Customizer::add_section( 'jupiterx_maintenance', [
	'priority' => 340,
	'title' => __( 'Maintenance', 'jupiterx-core' ),
	'type'  => 'container',
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
		],
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/enabling-maintenance-mode-in-jupiter-x',
		'title' => __( 'Enabling Maintenance Mode in Jupiter X', 'jupiterx-core' ),
	],
	'group' => 'elements',
	'icon'  => 'maintenance',
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_maintenance_warning',
	'section'         => 'jupiterx_maintenance',
	'box'             => 'settings',
	'label'           => __( 'Maintenance Mode returns HTTP 503 code, so search engines know to come back a short time later. It is not recommended to use this mode for more than a couple of days.', 'jupiterx-core' ),
	'jupiterx_url'    => '',
] );

// Fields description.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_maintenance_label',
	'section'    => 'jupiterx_maintenance',
	'box'        => 'settings',
	'label'      => __( 'Maintenance page will be displayed to guests only.', 'jupiterx-core' ),
	'label_type' => 'description',
] );

// Enable maintenance.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-toggle',
	'settings'    => 'jupiterx_maintenance',
	'section'     => 'jupiterx_maintenance',
	'box'         => 'settings',
	'label'       => __( 'Maintenance', 'jupiterx-core' ),
	'default'     => false,
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_maintenance_custom_templates_notice',
	'section'         => 'jupiterx_maintenance',
	'box'             => 'settings',
	'label'           => jupiterx_customizer_custom_templates_notice(),
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_maintenance_template',
	'section'     => 'jupiterx_maintenance',
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
