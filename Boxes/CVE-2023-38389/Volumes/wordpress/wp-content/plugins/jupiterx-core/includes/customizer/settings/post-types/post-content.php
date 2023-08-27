<?php
/**
 * Add Jupiter settings for Post Type Single > Styles > Post Content tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

if ( ! function_exists( 'jupiterx_get_post_types' ) ) {
	return;
}

$jupiterx_post_type = jupiterx_get_post_types( 'objects' );

if ( empty( $jupiterx_post_type ) ) {
	return;
}

foreach ( $jupiterx_post_type as $post_type_id => $jupiterx_post_type_item ) {

	$section = "jupiterx_{$jupiterx_post_type_item->name}_single";

	$custom_posttype_post_content_condition = [
		[
			'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
			'operator' => '===',
			'value'    => '',
		],
	];

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => "{$section}_align",
		'box'       => 'post_content',
		'section'   => $section,
		'css_var'   => "{$jupiterx_post_type_item->name}-single-post-content-align",
		'label'     => __( 'Alignment', 'jupiterx-core' ),
		'inline'    => true,
		'transport' => 'postMessage',
		'choices'   => JupiterX_Customizer_Utils::get_align(),
		'output'    => [
			[
				'element'  => ".single-{$jupiterx_post_type_item->name} .jupiterx-post-content",
				'property' => 'text-align',
			],
		],
		'active_callback' => $custom_posttype_post_content_condition,
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => "{$section}_typography",
		'section'    => $section,
		'box'        => 'post_content',
		'responsive' => true,
		'css_var'    => "{$jupiterx_post_type_item->name}-single-post-content",
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => ".single-{$jupiterx_post_type_item->name} .jupiterx-post-content",
			],
		],
		'active_callback' => $custom_posttype_post_content_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => "{$section}_divider",
		'section'  => $section,
		'box'      => 'post_content',
		'active_callback' => $custom_posttype_post_content_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => "{$section}_spacing",
		'section'   => $section,
		'box'       => 'post_content',
		'css_var'   => "{$jupiterx_post_type_item->name}-single-post-content",
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => ".single-{$jupiterx_post_type_item->name} .jupiterx-post-content",
			],
		],
		'active_callback' => $custom_posttype_post_content_condition,
	] );
}
