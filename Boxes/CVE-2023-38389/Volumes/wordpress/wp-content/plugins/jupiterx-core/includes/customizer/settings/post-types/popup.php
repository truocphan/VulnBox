<?php
/**
 * Add custom post type popups to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

if ( ! function_exists( 'jupiterx_get_post_types' ) ) {
	return;
}

$jupiterx_post_types = jupiterx_get_post_types( 'objects' );

if ( empty( $jupiterx_post_types ) ) {
	return;
}

/**
 * Start registering section and fields to customizer.
 */
foreach ( $jupiterx_post_types as $post_type_id => $jupiterx_post_type_item ) {

	// Single popup.
	JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_single", [
		'title'   => $jupiterx_post_type_item->label . ' ' . __( 'Single', 'jupiterx-core' ),
		'type'    => 'container',
		'tabs'    => [
			'settings' => __( 'Settings', 'jupiterx-core' ),
			'styles'   => __( 'Styles', 'jupiterx-core' ),
		],
		'boxes' => [
			'settings' => [
				'label' => __( 'Settings', 'jupiterx-core' ),
				'tab' => 'settings',
			],
			'post_content' => [
				'label' => __( 'Post Content', 'jupiterx-core' ),
				'tab'   => 'styles',
			],
		],
		'preview' => [
			'post_type' => $jupiterx_post_type_item->name,
			'single'    => true,
		],
		'group' => 'specific_pages',
		'icon'  => 'portfolio',
	] );

	add_action( "jupiterx_{$jupiterx_post_type_item->name}_single_after_section", 'jupiterx_dependency_notice_handler', 10 );

	// Type.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-choose',
		'settings' => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
		'section'  => "jupiterx_{$jupiterx_post_type_item->name}_single",
		'label'    => __( 'Type', 'jupiterx-core' ),
		'box'      => 'settings',
		'default'  => '',
		'choices'  => [
			'' => [
				'label' => __( 'Default', 'jupiterx-core' ),
			],
			'_custom' => [
				'label' => __( 'Custom', 'jupiterx-core' ),
				'pro'   => true,
			],
		],
	] );

	// Pro Box.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-pro-box',
		'settings'        => "jupiterx_{$jupiterx_post_type_item->name}_single_custom_pro_box",
		'section'         => "jupiterx_{$jupiterx_post_type_item->name}_single",
		'box'             => 'settings',
		'active_callback' => [
			[
				'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );

	// Display elements.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-multicheck',
		'settings' => "jupiterx_{$jupiterx_post_type_item->name}_single_elements",
		'section'  => "jupiterx_{$jupiterx_post_type_item->name}_single",
		'box'      => 'settings',
		'label'    => __( 'Display Elements', 'jupiterx-core' ),
		'default'  => [
			'featured_image',
		],
		'choices'  => [
			'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
			'title'          => __( 'Title', 'jupiterx-core' ),
			'date'           => __( 'Date', 'jupiterx-core' ),
			'author'         => __( 'Author', 'jupiterx-core' ),
			'social_share'   => __( 'Social Share', 'jupiterx-core' ),
			'navigation'     => __( 'Navigation', 'jupiterx-core' ),
			'author_box'     => __( 'Author Box', 'jupiterx-core' ),
			'comments'       => __( 'Comments', 'jupiterx-core' ),
		],
		'active_callback' => [
			[
				'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
				'operator' => '===',
				'value'    => '',
			],
		],
	] );

	if ( $jupiterx_post_type_item->has_archive ) :
		// Archive popup.
		JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_archive_settings", [
			'title'   => $jupiterx_post_type_item->label . ' ' . __( 'Archive', 'jupiterx-core' ),
			'type'    => 'container',
			'tabs'    => [
				'settings' => __( 'Settings', 'jupiterx-core' ),
			],
			'boxes' => [
				'settings' => [
					'label' => __( 'Settings', 'jupiterx-core' ),
					'tab' => 'settings',
				],
			],
			'preview' => [
				'post_type' => $jupiterx_post_type_item->name,
				'archive'   => true,
			],
			'group' => 'specific_pages',
			'icon'  => 'portfolio',
		] );

		// Template.
		JupiterX_Customizer::add_field( [
			'type'            => 'jupiterx-template',
			'settings'        => "jupiterx_{$jupiterx_post_type_item->name}_archive_template",
			'section'         => "jupiterx_{$jupiterx_post_type_item->name}_archive_settings",
			'box'             => 'settings',
			'label'           => __( 'My Templates', 'jupiterx-core' ),
			'placeholder'     => __( 'Select one', 'jupiterx-core' ),
			'template_type'   => 'archive',
			'locked'          => true,
		] );

		// Spacing.
		JupiterX_Customizer::add_responsive_field( [
			'type'      => 'jupiterx-box-model',
			'settings'  => "jupiterx_{$jupiterx_post_type_item->name}_archive",
			'section'   => "jupiterx_{$jupiterx_post_type_item->name}_archive_settings",
			'box'       => 'settings',
			'css_var'   => "{$jupiterx_post_type_item->name}-archive-spacing",
			'transport' => 'postMessage',
			'output'    => [
				[
					'element' => ".post-type-archive-{$jupiterx_post_type_item->name} .jupiterx-main-content, .post-type-archive-{$jupiterx_post_type_item->name} .jupiterx-main-content",
				],
			],
		] );
	endif;

	// Load all the settings.
	foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
		require_once $setting;
	}
}
