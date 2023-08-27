<?php
/**
 * Add Jupiter Product Message Styles to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Notice Message popup.
JupiterX_Customizer::add_section( 'jupiterx_notice_messages', [
	'title'   => __( 'Notice Messages', 'jupiterx-core' ),
	'type'    => 'container',
	'boxes' => array(
		'message_box' => array(
			'label' => __( 'Messages', 'jupiterx-core' ),
		),
		'upgrade_to_pro' => array(
			'label' => __( 'Upgrade to Pro', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
	),
	'preview'    => true,
	'pro'        => true,
	'group'      => 'shop',
	'icon'       => 'notice-messages',
	'front_icon' => true,
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_notice_messages_styles_pro_box',
	'section'  => 'jupiterx_notice_messages',
	'box'      => 'upgrade_to_pro',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
