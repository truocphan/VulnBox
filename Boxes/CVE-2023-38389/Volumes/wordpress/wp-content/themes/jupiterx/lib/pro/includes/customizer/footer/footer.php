<?php
/**
 * Customizer settings for Footer.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_footer_type', [
		'choices'  => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Behavior.
	JupiterX_Customizer::update_field( 'jupiterx_footer_behavior', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'terms' => [
						[
							'setting'  => 'jupiterx_footer_type',
							'operator' => '===',
							'value'    => '',
						],
					],
				],
				[
					'terms' => [
						[
							'setting'  => 'jupiterx_footer_type',
							'operator' => '===',
							'value'    => '_custom',
						],
					],
				],
			],
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_footer_custom_pro_box' );
} );

add_action( 'jupiterx_footer_behavior_after_field', function() {
	// Warning.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-alert',
		'settings'        => 'jupiterx_footer_custom_templates_notice',
		'section'         => 'jupiterx_footer',
		'box'             => 'settings',
		'label'           => jupiterx_core_customizer_custom_templates_notice(),
		'priority'        => 10,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_footer_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );

	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_footer_template',
		'section'         => 'jupiterx_footer',
		'label'           => __( 'Template', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'footer',
		'box'             => 'settings',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_footer_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );
