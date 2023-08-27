<?php
/**
 * Add Jupiter settings for Go To Top > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.20.0
 */

// Enable Scroll top button.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_site_scroll_top',
	'section'  => 'jupiterx_go_to_top',
	'box'      => 'settings',
	'css_var'  => 'site-scroll-top',
	'label'    => __( 'Go to Top', 'jupiterx-core' ),
	'default'  => true,
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_site_scroll_empty_notice',
	'section'         => 'jupiterx_go_to_top',
	'box'             => 'empty_notice',
	'label'           => __( 'There are no style settings available when Go to Top is disabled.', 'jupiterx-core' ),
	'priority'        => 10,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_site_scroll_top',
			'operator' => '!=',
			'value'    => true,
		],
	],
] );
