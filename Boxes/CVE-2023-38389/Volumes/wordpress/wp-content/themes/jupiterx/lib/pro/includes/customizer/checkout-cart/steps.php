<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Steps.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	$section = 'jupiterx_checkout_cart';

	$checkout_cart_steps_condition = [
		[
			'setting'  => 'jupiterx_jupiterx_checkout_cart_elements',
			'operator' => 'contains',
			'value'    => 'steps',
		],
	];

	$checkout_cart_steps_step_style_number_condition = [
		[
			'setting'  => 'jupiterx_checkout_cart_steps_step_style',
			'operator' => '==',
			'value'    => 'number',
		],
	];

	$checkout_cart_steps_step_style_icon_condition = [
		[
			'setting'  => 'jupiterx_checkout_cart_steps_step_style',
			'operator' => '==',
			'value'    => 'icon',
		],
	];

	$checkout_cart_steps_container_tab_normal_condition = [
		[
			'setting'  => 'jupiterx_checkout_cart_steps_container_tab',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$checkout_cart_steps_container_tab_active_condition = [
		[
			'setting'  => 'jupiterx_checkout_cart_steps_container_tab',
			'operator' => '===',
			'value'    => 'active',
		],
	];

	$checkout_cart_steps_step_style_number_condition    = array_merge( $checkout_cart_steps_condition, $checkout_cart_steps_step_style_number_condition );
	$checkout_cart_steps_step_style_icon_condition      = array_merge( $checkout_cart_steps_condition, $checkout_cart_steps_step_style_icon_condition );
	$checkout_cart_steps_container_tab_normal_condition = array_merge( $checkout_cart_steps_condition, $checkout_cart_steps_container_tab_normal_condition );
	$checkout_cart_steps_container_tab_active_condition = array_merge( $checkout_cart_steps_condition, $checkout_cart_steps_container_tab_active_condition );

	// Step 1.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-text',
		'settings' => 'jupiterx_checkout_cart_steps_1',
		'text'     => __( 'Step 1', 'jupiterx' ),
		'section'  => $section,
		'box'      => 'steps',
		'default'  => __( 'Cart', 'jupiterx' ),
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step 2.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-text',
		'settings' => 'jupiterx_checkout_cart_steps_2',
		'text'     => __( 'Step 2', 'jupiterx' ),
		'section'  => $section,
		'box'      => 'steps',
		'default'  => __( 'Delivery & Payment', 'jupiterx' ),
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step 3.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-text',
		'settings' => 'jupiterx_checkout_cart_steps_3',
		'text'     => __( 'Step 3', 'jupiterx' ),
		'section'  => $section,
		'box'      => 'steps',
		'default'  => __( 'Complete Order', 'jupiterx' ),
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_1',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Step', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_step',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step style.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_checkout_cart_steps_step_style',
		'section'   => $section,
		'box'       => 'steps',
		'text'      => __( 'Style', 'jupiterx' ),
		'default'   => 'number',
		'choices'   => [
			'default' => __( 'Default', 'jupiterx' ),
			'number'  => __( 'Number', 'jupiterx' ),
			'icon'    => __( 'Icon', 'jupiterx' ),
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_step_bg_color',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-step-bg-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step',
				'property' => 'background-color',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_steps_step_border',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-step-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step',
			],
			[
				'element'       => '.jupiterx-wc-steps-inner:after',
				'property'      => 'height',
				'value_pattern' => '$',
				'choice'        => 'width',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Step spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_steps_spacing',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'padding_right' => 1.5,
				'padding_left'  => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_2',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '!=',
				'value'    => 'default',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Number.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Number', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Number typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_checkout_cart_steps_number_typography',
		'section'   => $section,
		'box'      => 'steps',
		'css_var'   => 'checkout-cart-steps-number',
		'transport' => 'postMessage',
		'exclude'   => [ 'letter_spacing', 'line_height', 'text_transform' ],
		'default'   => [
			'desktop' => [
				'color' => '#fff',
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step-number',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_number_condition,
	] );

	// Number background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_number_background_color',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-number-bg-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'default'   => '#adb5bd',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-number',
				'property' => 'background-color',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_number_condition,
	] );

	// Icon.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_11',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_step_style_icon_condition,
	] );

	// Icon size.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-input',
		'settings'  => 'jupiterx_checkout_cart_steps_icon_size',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-icon-size',
		'label'     => __( 'Font Size', 'jupiterx' ),
		'units'     => [ 'px', 'em', 'rem' ],
		'default'     => [
			'size' => 1.5,
			'unit' => 'rem',
		],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-icon',
				'property' => 'font-size',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_icon_condition,
	] );

	// Icon color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_icon_color',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-icon-color',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'default'   => '#adb5bd',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-icon',
				'property' => 'color',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_icon_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_3',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,

	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_2',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Title typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_checkout_cart_steps_title_typography',
		'section'   => $section,
		'box'      => 'steps',
		'css_var'   => 'checkout-cart-steps-title',
		'transport' => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'default'   => [
			'desktop' => [
				'color'     => '#adb5bd',
				'font_size' => [
					'size' => 1.25,
					'unit' => 'rem',
				],
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step-title',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_4',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,

	] );

	// Container.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Divider', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_divider',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_steps_step_divider',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-step-divider',
		'transport' => 'postMessage',
		'exclude'   => [ 'radius' ],
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-divider',
				'property' => 'border',
			],
			[
				'element'  => '.jupiterx-wc-step-divider',
				'property' => 'height',
				'choice'   => 'size',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_5',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Container.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Container', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_container',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_checkout_cart_steps_container_tab',
		'section'    => $section,
		'box'        => 'steps',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'active' => [
				'label' => __( 'Active', 'jupiterx' ),
			],
		],
		'default' => 'normal',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Container background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_container_bg_color',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-container-bg-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-steps',
				'property' => 'background-color',
			],
		],
		'active_callback' => $checkout_cart_steps_container_tab_normal_condition,
	] );

	// Container border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_steps_container_border',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-container-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.jupiterx-wc-steps',
			],
		],
		'active_callback' => $checkout_cart_steps_container_tab_normal_condition,
	] );

	// Step.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Step', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_6',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_container_tab_active_condition,
	] );

	// Step background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_box_bg_color_active',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-step-bg-color-active',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step.jupiterx-wc-step-active',
				'property' => 'background-color',
			],
		],
		'active_callback' => $checkout_cart_steps_container_tab_active_condition,
	] );

	// Step border width.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-input',
		'settings' => 'jupiterx_checkout_cart_steps_box_border_width_active',
		'section'  => $section,
		'box'      => 'steps',
		'css_var'  => 'checkout-cart-steps-step-border-width-active',
		'label'    => __( 'Border', 'jupiterx' ),
		'units'    => [ 'px' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step.jupiterx-wc-step-active',
				'property' => 'border-width',
			],
		],
		'active_callback' => $checkout_cart_steps_container_tab_active_condition,
	] );

	// Step border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_step_border_color_active',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-step-border-color-active',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step.jupiterx-wc-step-active',
				'property' => 'border-color',
			],
		],
		'active_callback' => $checkout_cart_steps_container_tab_active_condition,
	] );

	// Container spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_steps_container_spacing',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-container',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'padding_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-steps',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_6',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Number.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Number', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_4',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_step_style_number_condition,
	] );

	// Number color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_number_color_active',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-number-color-active',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',

		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-number',
				'property' => 'color',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_number_condition,
	] );

	// Number background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_number_background_color_active',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-number-bg-color-active',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'default'   => '#007bff',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-number',
				'property' => 'background-color',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_number_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_7',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_step_style_number_condition,
	] );

	// Icon.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_8',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_step_style_icon_condition,
	] );

	// Icon color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_icon_color_active',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-icon-color-active',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'default'   => '#212529',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-icon',
				'property' => 'color',
			],
		],
		'active_callback' => $checkout_cart_steps_step_style_icon_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_8',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_step_style_icon_condition,
	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_5',
		'section'  => $section,
		'box'      => 'steps',
		'active_callback' => $checkout_cart_steps_condition,
	] );

	// Title color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_title_color_active',
		'section'   => $section,
		'box'       => 'steps',
		'css_var'   => 'checkout-cart-steps-title-color-active',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'default'   => '#212529',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-title',
				'property' => 'color',
			],
		],
		'active_callback' => $checkout_cart_steps_condition,
	] );
} );
