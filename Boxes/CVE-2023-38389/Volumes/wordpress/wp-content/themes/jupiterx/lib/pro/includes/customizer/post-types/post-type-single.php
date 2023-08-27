<?php
/**
 * Customizer settings for post type single.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.10.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$post_types = jupiterx_get_post_types( 'objects' );

	if ( empty( $post_types ) ) {
		return;
	}

	foreach ( $post_types as $id => $post_type ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		// Type.
		JupiterX_Customizer::update_field( "jupiterx_{$post_type->name}_single_template_type", [
			'choices' => [
				''        => __( 'Default', 'jupiterx' ),
				'_custom' => __( 'Custom', 'jupiterx' ),
			],
		] );

		// Warning.
		JupiterX_Customizer::add_field( [
			'type'            => 'jupiterx-alert',
			'settings'        => "jupiterx_{$post_type->name}_single_custom_templates_notice",
			'section'         => "jupiterx_{$post_type->name}_single",
			'box'             => 'settings',
			'label'           => jupiterx_core_customizer_custom_templates_notice(),
			'active_callback' => [
				[
					'setting'  => "jupiterx_{$post_type->name}_single_template_type",
					'operator' => '===',
					'value'    => '_custom',
				],
			],
		] );

		// Template.
		JupiterX_Customizer::add_field( [
			'type'            => 'jupiterx-template',
			'settings'        => "jupiterx_{$post_type->name}_single_template",
			'section'         => "jupiterx_{$post_type->name}_single",
			'box'             => 'settings',
			'label'           => __( 'My Templates', 'jupiterx' ),
			'placeholder'     => __( 'Select one', 'jupiterx' ),
			'template_type'   => 'single',
			'active_callback' => [
				[
					'setting'  => "jupiterx_{$post_type->name}_single_template_type",
					'operator' => '===',
					'value'    => '_custom',
				],
			],
		] );

		// Pro Box.
		JupiterX_Customizer::remove_field( "jupiterx_{$post_type->name}_single_custom_pro_box" );
	}
} );
