<?php
/**
 * Customizer settings for Portfolio.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_portfolio_single_template_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_custom_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_navigation_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_social_share_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_related_posts_pro_box' );
} );

add_action( 'jupiterx_portfolio_single_template_type_after_field', function() {
	// Warning.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-alert',
		'settings'        => 'jupiterx_portfolio_single_custom_templates_notice',
		'section'         => 'jupiterx_portfolio_pages',
		'box'             => 'settings_single',
		'label'           => jupiterx_core_customizer_custom_templates_notice(),
		'active_callback' => [
			[
				'setting'  => 'jupiterx_portfolio_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );

	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_portfolio_single_template',
		'section'         => 'jupiterx_portfolio_pages',
		'box'             => 'settings_single',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'single',
		'box'             => 'settings_single',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_portfolio_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

// Navigation.
add_action( 'jupiterx_portfolio_single_navigation_pro_box_after_field', function() {
	$portfolio_navigation_condition = [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_portfolio_single_elements',
			'operator' => 'contains',
			'value'    => 'navigation',
		],
	];

	$portfolio_navigation_image_condition = [
		[
			'setting'  => 'jupiterx_portfolio_single_navigation_image',
			'operator' => '==',
			'value'    => true,
		],
	];

	$portfolio_navigation_image_condition = array_merge( $portfolio_navigation_condition, $portfolio_navigation_image_condition );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_portfolio_single_navigation_label',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'navigation',
		'label'    => __( 'Image', 'jupiterx' ),
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Image.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-toggle',
		'settings' => 'jupiterx_portfolio_single_navigation_image',
		'section'  => 'jupiterx_portfolio_pages',
		'label'    => __( 'Image', 'jupiterx' ),
		'box'      => 'navigation',
		'default'  => true,
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Image border radius.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-input',
		'settings'        => 'jupiterx_portfolio_single_navigation_image_border_radius',
		'section'         => 'jupiterx_portfolio_pages',
		'box'             => 'navigation',
		'css_var'         => 'portfolio-single-navigation-image-border-radius',
		'label'           => __( 'Border Radius', 'jupiterx' ),
		'units'           => [ 'px', '%' ],
		'transport'       => 'postMessage',
		'output'          => [
			[
				'element'  => '.single-portfolio .jupiterx-post-navigation-link img',
				'property' => 'border-radius',
			],
		],
		'active_callback' => $portfolio_navigation_image_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_navigation_divider',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'navigation',
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Title label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_portfolio_single_navigation_label_2',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'navigation',
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Title typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_portfolio_single_navigation_title_typography',
		'section'    => 'jupiterx_portfolio_pages',
		'box'        => 'navigation',
		'responsive' => true,
		'css_var'    => 'portfolio-single-navigation-title',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-portfolio .jupiterx-post-navigation-title',
			],
		],
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_navigation_divider_2',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'navigation',
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Label label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Label', 'jupiterx' ),
		'settings' => 'jupiterx_portfolio_single_navigation_label_3',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'navigation',
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Label typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_portfolio_single_navigation_label_typography',
		'section'    => 'jupiterx_portfolio_pages',
		'box'        => 'navigation',
		'responsive' => true,
		'css_var'    => 'portfolio-single-navigation-label',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-portfolio .jupiterx-post-navigation-label',
			],
		],
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_navigation_divider_3',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'navigation',
		'active_callback' => $portfolio_navigation_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_navigation_spacing',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'navigation',
		'css_var'   => 'portfolio-single-navigation',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-navigation',
			],
		],
		'active_callback' => $portfolio_navigation_condition,
	] );
} );

// Social Share.
add_action( 'jupiterx_portfolio_single_social_share_pro_box_after_field', function() {
	$portfolio_social_share_condition = [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_portfolio_single_elements',
			'operator' => 'contains',
			'value'    => 'social_share',
		],
	];

	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-multicheck',
		'settings' => 'jupiterx_portfolio_single_social_share_filter',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'social_share',
		'default'       => [
			'facebook',
			'twitter',
			'linkedin',
		],
		'icon_choices'  => [
			'facebook'    => 'share-facebook-f',
			'twitter'     => 'share-twitter',
			'pinterest'   => 'share-pinterest-p',
			'linkedin'    => 'share-linkedin-in',
			'reddit'      => 'share-reddit-alien',
			'email'       => 'share-email',
		],
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_social_share_divider',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'social_share',
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_portfolio_single_social_share_align',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'social_share',
		'css_var'   => 'portfolio-single-social-share-align',
		'label'     => __( 'Alignment', 'jupiterx' ),
		'inline'    => true,
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => '',
			'tablet'  => 'center',
			'mobile'  => 'center',
		],
		'choices'   => JupiterX_Customizer_Utils::get_align( 'justify-content' ),
		'output'    => [
			[
				'element'  => '.single-portfolio .jupiterx-social-share-inner',
				'property' => 'justify-content',
			],
		],
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Name.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_portfolio_single_social_share_name',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'social_share',
		'label'     => __( 'Name', 'jupiterx' ),
		'default'   => true,
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_social_share_divider_2',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'social_share',
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Link spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_social_share_link_spacing',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'social_share',
		'css_var'   => 'portfolio-single-social-share-link',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'default'   => [
			'desktop' => [
				'padding_top'    => 0.4,
				jupiterx_get_direction( 'padding_right' ) => 0.75,
				'padding_bottom' => 0.4,
				jupiterx_get_direction( 'padding_left' ) => 0.75,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-social-share-link',
			],
		],
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_social_share_divider_3',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'social_share',
		'active_callback' => $portfolio_social_share_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_social_share_spacing',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'social_share',
		'css_var'   => 'portfolio-single-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-social-share',
			],
		],
		'active_callback' => $portfolio_social_share_condition,
	] );
} );

// Related works.
add_action( 'jupiterx_portfolio_single_related_posts_pro_box_after_field', function() {
	$portfolio_related_posts_condition = [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_portfolio_single_elements',
			'operator' => 'contains',
			'value'    => 'related_posts',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_portfolio_single_related_posts_typography',
		'section'    => 'jupiterx_portfolio_pages',
		'box'        => 'related_works',
		'responsive' => true,
		'css_var'    => 'portfolio-single-related-posts',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-portfolio .jupiterx-post-related .card-title',
			],
		],
		'active_callback' => $portfolio_related_posts_condition,
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_portfolio_single_related_posts_background_color',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'related_works',
		'css_var'   => 'portfolio-single-related-posts-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-portfolio .jupiterx-post-related .card-body',
				'property' => 'background-color',
			],
		],
		'active_callback' => $portfolio_related_posts_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_portfolio_single_related_posts_border',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'related_works',
		'css_var'   => 'portfolio-single-related-posts-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-related .card',
			],
		],
		'active_callback' => $portfolio_related_posts_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_related_posts_spacing',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'related_works',
		'css_var'   => 'portfolio-single-related-posts',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-related .card-body',
			],
		],
		'active_callback' => $portfolio_related_posts_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_related_posts_divider',
		'section'  => 'jupiterx_portfolio_pages',
		'box'      => 'related_works',
		'active_callback' => $portfolio_related_posts_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_related_posts_container_spacing',
		'section'   => 'jupiterx_portfolio_pages',
		'box'       => 'related_works',
		'css_var'   => 'portfolio-single-related-posts-container',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-related',
			],
		],
		'active_callback' => $portfolio_related_posts_condition,
	] );
} );
