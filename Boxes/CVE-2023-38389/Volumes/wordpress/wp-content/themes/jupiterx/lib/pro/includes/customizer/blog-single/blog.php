<?php
/**
 * Customizer settings for Blog.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_post_single_template_type_after_field', function() {
	$template_type = 'single';

	if ( class_exists( '\ElementorPro\Plugin' ) ) {
		$template_type = [ $template_type, 'single-post' ];
	}

	// Warning.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-alert',
		'settings'        => 'jupiterx_blog_single_custom_templates_notice',
		'section'         => 'jupiterx_blog_pages',
		'box'             => 'settings_single',
		'label'           => jupiterx_core_customizer_custom_templates_notice(),
		'active_callback' => [
			[
				'setting'  => 'jupiterx_post_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );

	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_post_single_template_custom',
		'section'         => 'jupiterx_blog_pages',
		'box'             => 'settings_single',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => $template_type,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_post_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

add_action( 'jupiterx_after_customizer_register', function() {

	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_post_single_template_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Template.
	JupiterX_Customizer::update_field( 'jupiterx_post_single_template', [
		'choices' => [
			'1' => 'blog-single-01',
			'2' => 'blog-single-02',
			'3' => 'blog-single-03',
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_custom_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_social_share_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_navigation_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_author_box_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_related_posts_pro_box' );
} );

// Social Share.
add_action( 'jupiterx_post_single_social_share_pro_box_after_field', function() {
	$social_share_condition = [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_post_single_elements',
			'operator' => 'contains',
			'value'    => 'social_share',
		],
	];

	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'          => 'jupiterx-multicheck',
		'settings'      => 'jupiterx_post_single_social_share_filter',
		'section'       => 'jupiterx_blog_pages',
		'box'           => 'social_share',
		'default'       => [
			'facebook',
			'twitter',
			'linkedin',
		],
		'icon_choices'  => [
			'facebook'  => 'share-facebook-f',
			'twitter'   => 'share-twitter',
			'pinterest' => 'share-pinterest-p',
			'linkedin'  => 'share-linkedin-in',
			'reddit'    => 'share-reddit-alien',
			'email'     => 'share-email',
			'whatsapp'  => 'share-whatsapp',
			'telegram'  => 'share-telegram',
			'vk'        => 'share-vk',
		],
		'active_callback' => $social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_social_share_divider',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'social_share',
		'active_callback' => $social_share_condition,
	] );

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_post_single_social_share_align',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'social_share',
		'css_var'   => 'post-single-social-share-align',
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
				'element'  => '.single-post .jupiterx-social-share-inner',
				'property' => 'justify-content',
			],
		],
		'active_callback' => $social_share_condition,
	] );

	// Name.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_post_single_social_share_name',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'social_share',
		'label'     => __( 'Name', 'jupiterx' ),
		'default'   => true,
		'active_callback' => $social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_social_share_divider_2',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'social_share',
		'active_callback' => $social_share_condition,
	] );

	// Link spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_social_share_link_spacing',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'social_share',
		'css_var'   => 'post-single-social-share-link',
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
				'element' => '.single-post .jupiterx-social-share-link',
			],
		],
		'active_callback' => $social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_social_share_divider_3',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'social_share',
		'active_callback' => $social_share_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_social_share_spacing',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'social_share',
		'css_var'   => 'post-single-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-social-share',
			],
		],
		'active_callback' => $social_share_condition,
	] );
} );

// Navigation.
add_action( 'jupiterx_post_single_navigation_pro_box_after_field', function() {
	$navigation_condition = [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_post_single_elements',
			'operator' => 'contains',
			'value'    => 'navigation',
		],
	];

	$navigation_image_condition = [
		[
			'setting'  => 'jupiterx_post_single_navigation_image',
			'operator' => '==',
			'value'    => true,
		],
	];

	$navigation_image_condition = array_merge( $navigation_condition, $navigation_image_condition );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_post_single_navigation_label',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'navigation',
		'label'    => __( 'Image', 'jupiterx' ),
		'active_callback' => $navigation_condition,
	] );

	// Image.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-toggle',
		'settings' => 'jupiterx_post_single_navigation_image',
		'section'  => 'jupiterx_blog_pages',
		'label'    => __( 'Image', 'jupiterx' ),
		'box'      => 'navigation',
		'default'  => true,
		'active_callback' => $navigation_condition,
	] );

	// Image border radius.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-input',
		'settings'        => 'jupiterx_post_single_navigation_image_border_radius',
		'section'         => 'jupiterx_blog_pages',
		'box'             => 'navigation',
		'css_var'         => 'post-single-navigation-image-border-radius',
		'label'           => __( 'Border Radius', 'jupiterx' ),
		'units'           => [ 'px', '%' ],
		'transport'       => 'postMessage',
		'output'          => [
			[
				'element'  => '.single-post .jupiterx-post-navigation-link img',
				'property' => 'border-radius',
			],
		],
		'active_callback' => $navigation_image_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_navigation_divider',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'navigation',
		'active_callback' => $navigation_image_condition,
	] );

	// Title label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_post_single_navigation_label_2',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'navigation',
		'active_callback' => $navigation_condition,
	] );

	// Title typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_navigation_title_typography',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'navigation',
		'css_var'   => 'post-single-navigation-title',
		'transport' => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-navigation-title',
			],
		],
		'active_callback' => $navigation_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_navigation_divider_2',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'navigation',
		'active_callback' => $navigation_condition,
	] );

	// Label label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Label', 'jupiterx' ),
		'settings' => 'jupiterx_post_single_navigation_label_3',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'navigation',
		'active_callback' => $navigation_condition,
	] );

	// Label typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_navigation_label_typography',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'navigation',
		'css_var'   => 'post-single-navigation-label',
		'transport' => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-navigation-label',
			],
		],
		'active_callback' => $navigation_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_navigation_divider_3',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'navigation',
		'active_callback' => $navigation_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_navigation_spacing',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'navigation',
		'css_var'   => 'post-single-navigation',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-navigation',
			],
		],
		'active_callback' => $navigation_condition,
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_navigation_pro_box' );
} );

// Author Box.
add_action( 'jupiterx_post_single_author_box_pro_box_after_field', function() {
	$author_box_condition = [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_post_single_elements',
			'operator' => 'contains',
			'value'    => 'author_box',
		],
	];

	// Avatar label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Avatar', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label',
		'section'    => 'jupiterx_blog_pages',
		'box'        => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Avatar border radius.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_post_single_author_box_avatar_border_radius',
		'section'     => 'jupiterx_blog_pages',
		'box'         => 'author_box',
		'css_var'     => 'post-single-author-box-avatar-border-radius',
		'label'       => __( 'Border Radius', 'jupiterx' ),
		'units'       => [ 'px', '%' ],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => '.single-post .jupiterx-post-author-box-avatar img',
				'property' => 'border-radius',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Name label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Name', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_2',
		'section'    => 'jupiterx_blog_pages',
		'box'        => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Name typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_author_box_name_typography',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box-name',
		'transport' => 'postMessage',
		'exclude'   => [ 'letter_spacing', 'text_transform', 'line_height' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box-link',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_2',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Description label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Description', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_3',
		'section'    => 'jupiterx_blog_pages',
		'box'        => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Description typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_author_box_description_typography',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box-description',
		'transport' => 'postMessage',
		'exclude'   => [ 'letter_spacing', 'text_transform' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box-content p',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_3',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Icons label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Social Network Icons', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_4',
		'section'    => 'jupiterx_blog_pages',
		'box'        => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Icons size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_post_single_author_box_icons_size',
		'section'     => 'jupiterx_blog_pages',
		'box'         => 'author_box',
		'css_var'     => 'post-single-author-box-icons-size',
		'label'       => __( 'Font Size', 'jupiterx' ),
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => '.single-post .jupiterx-post-author-icons a',
				'property' => 'font-size',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Icons gap.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-input',
		'settings'  => 'jupiterx_post_single_author_box_icons_gap',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box-icons-gap',
		'label'     => __( 'Space Between', 'jupiterx' ),
		'units'     => [ 'px', 'em' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.single-post .jupiterx-post-author-icons li',
				'property'      => 'margin-right',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Icons color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_post_single_author_box_icons_color',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box-icons-color',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-post .jupiterx-post-author-icons a',
				'property' => 'color',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_4',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Container label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Container', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_5',
		'section'    => 'jupiterx_blog_pages',
		'box'        => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Container align.
	JupiterX_Customizer::add_responsive_field( [
		'type'     => 'jupiterx-choose',
		'settings' => 'jupiterx_post_single_author_box_align',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'author_box',
		'label'    => __( 'Alignment', 'jupiterx' ),
		'inline'   => true,
		'css_var'  => 'post-single-author-box-align',
		'default'  => [
			'desktop' => '',
			'tablet'  => 'center',
			'mobile'  => 'center',
		],
		'choices'  => JupiterX_Customizer_Utils::get_align(),
		'active_callback' => $author_box_condition,
	] );

	// Container background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_post_single_author_box_background_color',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-post .jupiterx-post-author-box',
				'property' => 'background-color',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Container border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_post_single_author_box_border',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box',
			],
		],
		'active_callback' => $author_box_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_5',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'author_box',
		'active_callback' => $author_box_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_author_box_spacing',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'author_box',
		'css_var'   => 'post-single-author-box',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box',
			],
		],
		'active_callback' => $author_box_condition,
	] );
} );

// Related Posts.
add_action( 'jupiterx_post_single_related_posts_pro_box_after_field', function() {
	$related_post_condition = [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_post_single_elements',
			'operator' => 'contains',
			'value'    => 'related_posts',
		],
	];

	// Typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_related_posts_typography',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'recommended_posts',
		'css_var'   => 'post-single-related-posts',
		'transport' => 'postMessage',
		'exclude'   => [ 'text_transform' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related .card-title',
			],
		],
		'active_callback' => $related_post_condition,
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_post_single_related_posts_background_color',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'recommended_posts',
		'css_var'   => 'post-single-related-posts-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-post .jupiterx-post-related .card-body',
				'property' => 'background-color',
			],
		],
		'active_callback' => $related_post_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_post_single_related_posts_border',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'recommended_posts',
		'css_var'   => 'post-single-related-posts-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related .card',
			],
		],
		'active_callback' => $related_post_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_related_posts_spacing',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'recommended_posts',
		'css_var'   => 'post-single-related-posts',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related .card-body',
			],
		],
		'active_callback' => $related_post_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_related_posts_divider',
		'section'  => 'jupiterx_blog_pages',
		'box'      => 'recommended_posts',
		'active_callback' => $related_post_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_related_posts_container_spacing',
		'section'   => 'jupiterx_blog_pages',
		'box'       => 'recommended_posts',
		'css_var'   => 'post-single-related-posts-container',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related',
			],
		],
		'active_callback' => $related_post_condition,
	] );

} );
