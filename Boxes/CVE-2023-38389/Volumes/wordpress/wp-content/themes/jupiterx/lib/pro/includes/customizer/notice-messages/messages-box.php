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

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'notice_messages_box_tab',
		'section'    => $section_styles,
		'box'        => 'message_box',
		'choices'    => [
			'success'  => [
				'label' => __( 'Success', 'jupiterx' ),
			],
			'info' => [
				'label' => __( 'Info', 'jupiterx' ),
			],
			'error' => [
				'label' => __( 'Error', 'jupiterx' ),
			],
		],
		'default' => 'success',
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Container', 'jupiterx' ),
		'settings' => 'notice_messages_box_success_container_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_box_success_background_color',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'transport' => 'postMessage',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-box-success-container-background-color',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-message',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_notice_messages_success_box_border',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'css_var'   => 'notice-messages-box-success-container-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-notices-wrapper .woocommerce-message',
				'property' => 'border-top',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_notice_messages_success_box_spacing',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'css_var'   => 'notice-messages-box-success-container',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-message',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'notice_messages_box_success_divider_1',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Text', 'jupiterx' ),
		'settings' => 'notice_messages_box_success_text_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'notice_messages_box_success_text_typography',
		'section'    => $section_styles,
		'box'        => 'message_box',
		'responsive' => true,
		'css_var'    => 'notice-messages-box-success-text',
		'transport'  => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-message',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'notice_messages_box_success_divider_2',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'notice_messages_box_success_icon_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'notice_messages_box_success_icon_typography',
		'section'    => $section_styles,
		'box'        => 'message_box',
		'responsive' => true,
		'css_var'    => 'notice-messages-box-success-icon',
		'transport'  => 'postMessage',
		'exclude'   => [
			'line_height',
			'text_decoration',
			'font_weight',
			'font_family',
			'font_style',
			'letter_spacing',
			'text_transform',
		],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-message::before',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'success',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Container', 'jupiterx' ),
		'settings' => 'notice_messages_box_info_container_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_box_info_background_color',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'transport' => 'postMessage',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-box-info-container-background-color',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-info',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_notice_messages_info_box_border',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'css_var'   => 'notice-messages-box-info-container-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-notices-wrapper .woocommerce-info',
				'property' => 'border-top',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_notice_messages_info_box_spacing',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'css_var'   => 'notice-messages-box-info-container',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-info',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'notice_messages_box_info_divider_1',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Text', 'jupiterx' ),
		'settings' => 'notice_messages_box_info_text_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'notice_messages_box_info_text_typography',
		'section'    => $section_styles,
		'box'        => 'message_box',
		'responsive' => true,
		'css_var'    => 'notice-messages-box-info-text',
		'transport'  => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-info',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'notice_messages_box_info_divider_2',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'notice_messages_box_info_icon_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'notice_messages_box_info_icon_typography',
		'section'    => $section_styles,
		'box'      => 'message_box',
		'responsive' => true,
		'css_var'    => 'notice-messages-box-info-icon',
		'transport'  => 'postMessage',
		'exclude'   => [
			'line_height',
			'text_decoration',
			'font_weight',
			'font_family',
			'font_style',
			'letter_spacing',
			'text_transform',
		],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-info::before',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'info',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Container', 'jupiterx' ),
		'settings' => 'notice_messages_box_error_container_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_notice_messages_box_error_background_color',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'transport' => 'postMessage',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'css_var'   => 'notice-messages-box-error-container-background-color',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-error',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_notice_messages_error_box_border',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'css_var'   => 'notice-messages-box-error-container-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-notices-wrapper .woocommerce-error',
				'property' => 'border-top',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_notice_messages_error_box_spacing',
		'section'   => $section_styles,
		'box'       => 'message_box',
		'css_var'   => 'notice-messages-box-error-container',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-error',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'notice_messages_box_error_divider_1',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Text', 'jupiterx' ),
		'settings' => 'notice_messages_box_error_text_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'notice_messages_box_error_text_typography',
		'section'    => $section_styles,
		'box'        => 'message_box',
		'responsive' => true,
		'css_var'    => 'notice-messages-box-error-text',
		'transport'  => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-error',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'notice_messages_box_error_divider_2',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Heading.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'notice_messages_box_error_icon_heading',
		'section'  => $section_styles,
		'box'      => 'message_box',
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'notice_messages_box_error_icon_typography',
		'section'    => $section_styles,
		'box'        => 'message_box',
		'responsive' => true,
		'css_var'    => 'notice-messages-box-error-icon',
		'transport'  => 'postMessage',
		'exclude'   => [
			'line_height',
			'text_decoration',
			'font_weight',
			'font_family',
			'font_style',
			'letter_spacing',
			'text_transform',
		],
		'output'     => [
			[
				'element' => '.woocommerce-notices-wrapper .woocommerce-error::before',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'notice_messages_box_tab',
				'operator' => '===',
				'value'    => 'error',
			],
		],
	] );

} );
