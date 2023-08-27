<?php
/**
 * Add Jupiter Styles for Notice Messages > Syles tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.23.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	$section_styles = 'jupiterx_notice_messages';

	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'notice_messages_button_tab',
		'section'    => $section_styles,
		'box'        => 'message_button',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'default' => 'normal',
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_notice_messages_button_typography',
		'section'    => $section_styles,
		'box'        => 'message_button',
		'responsive' => true,
		'css_var'    => 'notice-messages-button',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper a.button',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_button_background_color',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'transport' => 'postMessage',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-button-background-color',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper a.button',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Border Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Border', 'jupiterx' ),
		'settings' => 'jupiterx_notice_messages_button_border_label',
		'section'  => $section_styles,
		'box'      => 'message_button',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_notice_messages_button_border',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'css_var'   => 'notice-messages-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-notices-wrapper a.button',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Box Shadow Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Box Shadow', 'jupiterx' ),
		'settings' => 'jupiterx_notice_messages_button_box_shadow_label',
		'section'  => $section_styles,
		'box'      => 'message_button',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Box shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_notice_messages_button_box_shadow',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'css_var'   => 'notice-messages-button-box-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper a.button',
				'units'   => 'px',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Color on hover.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_button_color_hover',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'transport' => 'postMessage',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-button-color-hover',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper a.button:hover',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Background color on hover.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_button_background_color_hover',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'transport' => 'postMessage',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-button-background-color-hover',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper a.button:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Border', 'jupiterx' ),
		'settings' => 'jupiterx_notice_messages_button_border_hover_label',
		'section'  => $section_styles,
		'box'      => 'message_button',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Border on hover.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_button_border_hover',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'transport' => 'postMessage',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-button-border-hover',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper a.button:hover',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Box Shadow Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Box Shadow', 'jupiterx' ),
		'settings' => 'jupiterx_notice_messages_button_box_shadow_hover_label',
		'section'  => $section_styles,
		'box'      => 'message_button',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// box shadow on hover.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_notice_messages_button_box_shadow_hover',
		'section'   => $section_styles,
		'box'       => 'message_button',
		'css_var'   => 'notice-messages-button-box-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'exclude'   => [ 'position', 'color' ],
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper a.button:hover',
				'units'   => 'px',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

} );
