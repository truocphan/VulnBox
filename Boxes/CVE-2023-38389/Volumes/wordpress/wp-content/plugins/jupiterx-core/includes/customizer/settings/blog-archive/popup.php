<?php
/**
 * Add Jupiter blog archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_blog_pages_after_section', 'jupiterx_dependency_notice_handler', 10 );

// Blog popup.
JupiterX_Customizer::add_section( 'jupiterx_blog_pages', [
	'title' => __( 'Blog', 'jupiterx-core' ),
	'type'  => 'container',
	'tabs'  => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Single Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings_archive' => [
			'label' => __( 'Blog Archive', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'settings_single' => [
			'label' => __( 'Blog Single', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'featured_image' => array(
			'label' => __( 'Featured Image', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'title' => [
			'label' => __( 'Title', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'avatar' => [
			'label' => __( 'Avatar', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'meta' => [
			'label' => __( 'Meta', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'post_content' => [
			'label' => __( 'Post Content', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'tags' => [
			'label' => __( 'Tags', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'social_share' => [
			'label' => __( 'Social Share', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'navigation' => [
			'label' => __( 'Navigation', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'author_box' => [
			'label' => __( 'Author Box', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'recommended_posts' => [
			'label' => __( 'Recommended Posts', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'empty_notice'      => [
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		],
	],
	'preview' => true,
	'group'   => 'specific_pages',
	'icon'    => 'blog',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
