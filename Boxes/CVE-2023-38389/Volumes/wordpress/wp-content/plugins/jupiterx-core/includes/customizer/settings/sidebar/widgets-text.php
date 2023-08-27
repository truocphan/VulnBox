<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Widgets Text tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar';

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_sidebar_widgets_text_typography',
	'section'    => $section,
	'box'        => 'widgets_text',
	'responsive' => true,
	'css_var'    => 'sidebar-widgets-text',
	'transport'  => 'postMessage',
	'exclude'    => [ 'letter_spacing', 'text_transform' ],
	'output'     => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget .jupiterx-widget-content, .jupiterx-sidebar .jupiterx-widget .jupiterx-widget-content p',
		],
	],
] );
