<?php
/**
 * Customizer settings for Title Bar.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.10.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_title_bar_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	$exceptions = JupiterX_Customizer::$settings['jupiterx_title_bar_exceptions'];

	$fields = $exceptions['fields'];

	foreach ( $fields as $id => $field ) {
		$fields[ $id ]['options']['type']['choices'] = [
			'' => [
				'label' => __( 'Default', 'jupiterx' ),
			],
			'_custom' => [
				'label' => __( 'Custom', 'jupiterx' ),
			],
		];

		$fields[ $id ]['options']['template'] = [
			'type'         => 'jupiterx-template',
			'settings'     => 'jupiterx_title_bar_template',
			'section'      => 'jupiterx_title_bar',
			'box'          => 'settings',
			'label'        => __( 'My Templates', 'jupiterx' ),
			'placeholder'  => __( 'Select one', 'jupiterx' ),
			'templateType' => 'section',
			'location'     => 'title-bar',
		];

		// Remove Pro box.
		unset( $fields[ $id ]['options']['pro_box'] );
	}

	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_title_bar_exceptions', [
		'fields' => $fields,
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_title_bar_custom_pro_box' );
} );

add_action( 'jupiterx_title_bar_type_after_field', function() {
	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_title_bar_template',
		'section'         => 'jupiterx_title_bar',
		'box'             => 'settings',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'section',
		'location'        => 'title-bar',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_title_bar_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );
