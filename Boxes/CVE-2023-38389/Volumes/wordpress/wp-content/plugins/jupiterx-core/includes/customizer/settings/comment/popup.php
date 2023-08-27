<?php
/**
 * Add Jupiter Comment popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

// Comment popup.
JupiterX_Customizer::add_section( 'jupiterx_comment', [
	'title'  => __( 'Comment', 'jupiterx-core' ),
	'type'   => 'container',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'title' => [
			'label' => __( 'Title', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'field' => [
			'label' => __( 'Field', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'button' => [
			'label' => __( 'Button', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'avatar' => [
			'label' => __( 'Avatar', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'name' => [
			'label' => __( 'Name', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'date' => [
			'label' => __( 'Date', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'comment_text' => [
			'label' => __( 'Comment Text', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'action_link' => [
			'label' => __( 'Action Link', 'jupiterx-core' ),
			'tab' => 'styles',
		],
	],
	'preview' => true,
	'group' => 'elements',
	'icon'  => 'comment',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
