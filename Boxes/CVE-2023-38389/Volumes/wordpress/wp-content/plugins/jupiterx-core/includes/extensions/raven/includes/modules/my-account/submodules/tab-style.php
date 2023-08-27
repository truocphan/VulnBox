<?php
namespace JupiterX_Core\Raven\Modules\My_Account\Submodules;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Tab_Style {
	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public static function add_section_tabs( Base_Widget $widget ) {
		$widget->start_controls_section(
			'tabs_style',
			[
				'label' => esc_html__( 'Tabs', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$widget->add_responsive_control(
			'tabs_container_heading',
			[
				'label' => esc_html__( 'Container', 'jupiterx-core' ),
				'type'  => 'heading',
			]
		);

		$widget->add_responsive_control(
			'tabs_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [ 'px' => 0 ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-MyAccount-navigation ul' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'tabs_container_background',
				'selector'       => '{{WRAPPER}} .woocommerce .woocommerce-MyAccount-navigation ul',
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background', 'jupiterx-core' ),
					],
				],
			]
		);

		$widget->add_responsive_control(
			'tabs_links_heading',
			[
				'label'     => esc_html__( 'Links', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'tabs_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
			]
		);

		$widget->start_controls_tabs( 'tabs_section' );

		//------------TAB NORMAL--------------
		$widget->start_controls_tab( 'tabs_normal', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tabs_normal_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a' => 'color: {{VALUE}}',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tabs_normal_background',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tabs_normal_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
			]
		);

		$widget->end_controls_tab();

		//------------TAB HOVER--------------
		$widget->start_controls_tab( 'tabs_hover', [ 'label' => esc_html__( 'Hover', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tabs_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tabs_hover_background',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a:hover',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tabs_hover_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a:hover',
			]
		);

		$widget->add_control(
			'tabs_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'tabs_border_type!' => '',
				],
			]
		);

		$widget->end_controls_tab();

		//------------TAB ACTIVE--------------
		$widget->start_controls_tab( 'tabs_active', [ 'label' => esc_html__( 'Active', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tabs_active_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tabs_active_background',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tabs_active_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a',
			]
		);

		$widget->add_control(
			'tabs_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'tabs_border_type!' => '',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control(
			'tabs_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					'none'   => esc_html__( 'None', 'jupiterx-core' ),
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'groove' => esc_html__( 'Groove', 'jupiterx-core' ),
				],
				'default'   => 'solid',
				'selectors' => [
					'{{WRAPPER}}' => '--tabs-border-type: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$widget->add_responsive_control(
			'tabs_border_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'tabs_border_type!' => 'none',
				],
			]
		);

		$widget->add_control(
			'tabs_border_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#cccccc',
				'selectors' => [
					'{{WRAPPER}}' => '--tabs-border-color: {{VALUE}};',
				],
				'condition' => [
					'tabs_border_type!' => 'none',
				],
			]
		);

		$widget->add_responsive_control(
			'tabs_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tabs-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'tabs_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tabs-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'tabs_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [ 'px' => 0 ],
				'selectors' => [
					'{{WRAPPER}}' => '--tabs-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'tabs_divider_title',
			[
				'type'      => 'heading',
				'label'     => esc_html__( 'Dividers', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$widget->add_control(
			'tabs_divider_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--tabs-divider-color: {{VALUE}};',
				],
			]
		);

		$widget->add_responsive_control(
			'tabs_divider_weight',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tabs-divider-weight: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_section();
	}

	public static function add_section_tabs_icon( Base_Widget $widget ) {
		$widget->start_controls_section(
			'tabs_icon_style',
			[
				'label' => esc_html__( 'Tabs Icon', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$widget->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--tab-icon-color: {{VALUE}};',
				],
			]
		);

		$widget->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--tab-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'icon_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tab-icon-spacing: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'icon_alignment',
			[
				'label'        => esc_html__( 'Placement', 'jupiterx-core' ),
				'type'         => 'choose',
				'default'      => 'left',
				'toggle'       => false,
				'options'      => [
					'left'  => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-right',
					],
					'above' => [
						'title' => esc_html__( 'Above', 'jupiterx-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'below' => [
						'title' => esc_html__( 'Below', 'jupiterx-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'icon-aligned-',
				'render_type'  => 'template',
			]
		);

		$widget->end_controls_section();
	}

	public static function add_section_sections( Base_Widget $widget ) {
		$widget->start_controls_section(
			'sections_title',
			[
				'label' => esc_html__( 'Sections', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$widget->add_control(
			'my_account_sections_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--sections-background-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'my_account_sections_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => implode( ', ', [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper',
					'{{WRAPPER}} address',
					'{{WRAPPER}} .raven-my-account-tab__view-order .order_details',
					'{{WRAPPER}} .woocommerce-form-login',
					'{{WRAPPER}} .woocommerce-form-register',
					'{{WRAPPER}} .woocommerce-ResetPassword',
				] ),
			]
		);

		$widget->add_control(
			'sections_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					'none'   => esc_html__( 'None', 'jupiterx-core' ),
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'groove' => esc_html__( 'Groove', 'jupiterx-core' ),
				],
				'default'   => 'solid',
				'selectors' => [
					'{{WRAPPER}}' => '--sections-border-type: {{VALUE}};',
				],
			]
		);

		$widget->add_responsive_control(
			'sections_border_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--sections-border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'sections_border_type!' => 'none',
				],
			]
		);

		$widget->add_control(
			'sections_border_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#cccccc',
				'selectors' => [
					'{{WRAPPER}}' => '--sections-border-color: {{VALUE}};',
				],
				'condition' => [
					'sections_border_type!' => 'none',
				],
			]
		);

		$widget->add_responsive_control(
			'sections_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'sections_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}'                                                                  => '--sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --edit-link-margin-top: {{TOP}}{{UNIT}}; --edit-link-margin-start: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-my-account-tab__edit-address .woocommerce-Address address' => 'padding-top: calc( {{TOP}}{{UNIT}} + 40px );',
					'{{WRAPPER}} .woocommerce-pagination'                                          => 'padding-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_section();
	}

	public static function add_section_typography( Base_Widget $widget ) {
		$widget->start_controls_section(
			'typography_title',
			[
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$widget->add_control(
			'typography_titles',
			[
				'type'      => 'heading',
				'label'     => esc_html__( 'Section Titles', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$widget->add_control(
			'typography_section_titles_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--typography-section-titles-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'section_titles_typography',
				'selector' => '{{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} .woocommerce-EditAccountForm fieldset legend',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'section_titles_typography_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} .woocommerce-EditAccountForm fieldset legend',
			]
		);

		$widget->add_responsive_control(
			'section_title_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--section-title-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'typography_secondary_titles',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'General Text', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'general_text_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#555555',
				'selectors' => [
					'{{WRAPPER}}' => '--general-text-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'general_text_typography',
				'selector' => implode( ', ', [
					'{{WRAPPER}} .woocommerce-MyAccount-content > div > p',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-Message--info.woocommerce-info',
					'{{WRAPPER}} address',
					'{{WRAPPER}} .woocommerce-ResetPassword p:nth-child(1)',
					'{{WRAPPER}} .woocommerce-OrderUpdate',
				] ),
			]
		);

		$widget->add_control(
			'typography_login_messages_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Login Messages', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'login_messages_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--login-messages-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'login_messages_typography',
				'selector' => '{{WRAPPER}} .woocommerce-privacy-policy-text, {{WRAPPER}} em, {{WRAPPER}} .register p',
			]
		);

		$widget->add_control(
			'checkboxes_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Checkboxes', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'checkboxes_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--checkboxes-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'checkboxes_typography',
				'selector' => '{{WRAPPER}} .woocommerce-form__label-for-checkbox span',
			]
		);

		$widget->add_control(
			'links_title',
			[
				'type'      => 'heading',
				'label'     => esc_html__( 'Links', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$widget->start_controls_tabs( 'links_colors' );

		$widget->start_controls_tab( 'links_normal_colors', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$widget->add_control(
			'links_normal_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--links-normal-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'links_hover_colors', [ 'label' => esc_html__( 'Hover', 'jupiterx-core' ) ] );

		$widget->add_control(
			'links_hover_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--links-hover-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public static function add_section_forms( Base_Widget $widget ) {
		$widget->start_controls_section(
			'forms_section',
			[
				'label' => esc_html__( 'Forms', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$widget->add_responsive_control(
			'forms_columns_gap',
			[
				'label'      => esc_html__( 'Columns Gap', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [ 'px' => 0 ],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-columns-gap-padding-right: calc( {{SIZE}}{{UNIT}}/2 ); --forms-columns-gap-padding-left: calc( {{SIZE}}{{UNIT}}/2 ); --forms-columns-gap-margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); --forms-columns-gap-margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$widget->add_responsive_control(
			'forms_rows_gap',
			[
				'label'      => esc_html__( 'Rows Gap', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [ 'px' => 0 ],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'forms_label_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Labels', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'forms_label_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-labels-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'forms_label_typography',
				'selector' => '{{WRAPPER}} .woocommerce-form-row label',
			]
		);

		$widget->add_responsive_control(
			'forms_label_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [ 'px' => 0 ],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-label-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'forms_field_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Fields', 'jupiterx-core' ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'forms_field_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'yes',
					],
					'line_height' => [
						'default' => [
							'size' => 24,
							'unit' => 'px',
						],
					],
				],
				'selector' => implode( ', ', [
					'{{WRAPPER}} form .input-text',
					'{{WRAPPER}} form select',
					'{{WRAPPER}} form ::placeholder',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single',
					'{{WRAPPER}} form .select2-results__option',
				] ),
			]
		);

		$widget->start_controls_tabs( 'forms_fields_styles' );

		$widget->start_controls_tab( 'forms_fields_normal_styles', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$widget->add_control(
			'forms_fields_normal_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-normal-color: {{VALUE}};',
					'.e-woo-select2-wrapper .select2-results__option' => 'color: {{VALUE}};',
					// style select2 arrow
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow b' => 'border-color: {{VALUE}} transparent transparent transparent;',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'forms_fields_normal_background',
				'selector' => implode( ', ', [
					'{{WRAPPER}} form .input-text',
					'{{WRAPPER}} form select',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single',
					'{{WRAPPER}} form .select2-results__option',
				] ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'forms_fields_normal_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => implode( ', ', [
					'{{WRAPPER}} form  .input-text',
					'{{WRAPPER}} form  select',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single',
				] ),
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'forms_fields_focus_styles', [ 'label' => esc_html__( 'Focus', 'jupiterx-core' ) ] );

		$widget->add_control(
			'forms_fields_focus_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-focus-color: {{VALUE}}',
					'.e-woo-select2-wrapper .select2-results__option:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'forms_fields_focus_background',
				'selector' => implode( ', ', [
					'{{WRAPPER}} form .input-text:focus',
					'{{WRAPPER}} form select:focus',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single:focus',
				] ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'forms_fields_focus_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => implode( ', ', [
					'{{WRAPPER}} form .input-text:focus',
					'{{WRAPPER}} form select:focus',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single:focus',
				] ),
			]
		);

		$widget->add_control(
			'forms_fields_focus_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-focus-border-color: {{VALUE}}',
				],
				'condition' => [
					'forms_fields_border_border!' => '',
				],
			]
		);

		$widget->add_control(
			'forms_fields_focus_transition_duration',
			[
				'label'     => esc_html__( 'Transition Duration', 'jupiterx-core' ) . ' (ms)',
				'type'      => 'slider',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-focus-transition-duration: {{SIZE}}ms',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'forms_fields_border',
				'selector'  => implode( ', ', [
					'{{WRAPPER}} form .input-text',
					'{{WRAPPER}} form select',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single',
					'{{WRAPPER}} form .select2-results__option',
				] ),
				'separator' => 'before',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'unit'     => 'px',
							'top'      => 1,
							'right'    => 1,
							'bottom'   => 1,
							'left'     => 1,
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#CCCCCC',
					],
				],
			]
		);

		$widget->add_responsive_control(
			'forms_fields_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'unit'     => 'px',
					'top'      => 4,
					'right'    => 4,
					'bottom'   => 4,
					'left'     => 4,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-fields-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'forms_fields_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-fields-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// Style select2.
					'{{WRAPPER}} form .select2-container--default .select2-selection--single .select2-selection__rendered' => 'line-height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single .select2-selection__arrow' => 'height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} form .select2-container--default .select2-selection--single' => 'height: auto;',
				],
			]
		);

		$widget->add_control(
			'forms_button_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Buttons', 'jupiterx-core' ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'forms_button_typography',
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view)',
					'{{WRAPPER}} button.button',
				] ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'forms_button_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'jupiterx-core' ),
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view)',
					'{{WRAPPER}} button.button',
				] ),
			]
		);

		$widget->start_controls_tabs( 'forms_buttons_styles' );

		$widget->start_controls_tab( 'forms_buttons_normal_styles', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$widget->add_control(
			'forms_buttons_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-normal-text-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'forms_buttons_background',
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view)',
					'{{WRAPPER}} button.button',
				] ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'forms_buttons_normal_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view)',
					'{{WRAPPER}} button.button',
				] ),
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'forms_buttons_hover_styles', [ 'label' => esc_html__( 'Hover', 'jupiterx-core' ) ] );

		$widget->add_control(
			'forms_buttons_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-hover-text-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'forms_buttons_hover_background',
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view):hover',
					'{{WRAPPER}} button.button:hover',
				] ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'forms_buttons_focus_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view):hover',
					'{{WRAPPER}} button.button:hover',
				] ),
			]
		);

		$widget->add_control(
			'forms_buttons_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} a.button:not(.view):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} button.button:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'forms_buttons_border_border!' => '',
				],
			]
		);

		$widget->add_control(
			'forms_buttons_hover_transition_duration',
			[
				'label'     => esc_html__( 'Transition Duration', 'jupiterx-core' ) . ' (ms)',
				'type'      => 'slider',
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-hover-transition-duration: {{SIZE}}ms',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
			]
		);

		$widget->add_control(
			'forms_buttons_hover_animation',
			[
				'label'              => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type'               => 'hover_animation',
				'frontend_available' => true,
				'render_type'        => 'template',
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'forms_buttons_border',
				'separator' => 'before',
				'selector' => implode( ', ', [
					'{{WRAPPER}} a.button:not(.view)',
					'{{WRAPPER}} button.button',
				] ),
			]
		);

		$widget->add_responsive_control(
			'forms_buttons_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-buttons-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'forms_buttons_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--forms-buttons-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'forms_buttons_margin',
			[
				'label'      => esc_html__( 'Margin', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-orders-table__cell-order-actions a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public static function add_section_tables( Base_Widget $widget ) {
		$widget->start_controls_section(
			'tables_section',
			[
				'label' => esc_html__( 'Order Details', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$widget->add_responsive_control(
			'tables_rows_gap',
			[
				'label'      => esc_html__( 'Rows Gap', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [ 'px' => 0 ],
				'selectors'  => [
					'{{WRAPPER}}' => '--order-summary-rows-gap-top: calc( {{SIZE}}{{UNIT}}/2 ); --order-summary-rows-gap-bottom: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$widget->add_control(
			'tables_titles',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Titles &amp; Totals', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'tables_title_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => 'black',
				'selectors' => [
					'{{WRAPPER}} .woocommerce .shop_table thead' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .shop_table tr' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .shop_table th' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .shop_table thead span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .shop_table tr span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .shop_table th span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .order_details tfoot th' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .order_details tfoot td' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'tables_titles_typography',
				'selector' => '{{WRAPPER}} .order_details thead th, {{WRAPPER}} .order_details tfoot td, {{WRAPPER}} .order_details tfoot th, {{WRAPPER}} .nobr',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'tables_titles_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .order_details thead th, {{WRAPPER}} .order_details tfoot td, {{WRAPPER}} .order_details tfoot th, {{WRAPPER}} .nobr',
			]
		);

		$widget->add_control(
			'tables_items_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Items', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'tables_items_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--tables-items-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'tables_items_typography',
				'selector' => '{{WRAPPER}} .raven-my-account-tab__orders tbody td, {{WRAPPER}} .raven-my-account-tab__downloads tbody td, {{WRAPPER}} .product-quantity, {{WRAPPER}} .woocommerce-table--order-downloads tbody td, {{WRAPPER}} .woocommerce-table--order-details td a, {{WRAPPER}} td.product-total',
			]
		);

		$widget->add_control(
			'variations_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Variations', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'variations_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--variations-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'variations_typography',
				'selector' => '{{WRAPPER}} .wc-item-meta',
			]
		);

		$widget->add_control(
			'sections_links_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Product Link', 'jupiterx-core' ),
			]
		);

		$widget->start_controls_tabs( 'tables_links_colors' );

		$widget->start_controls_tab( 'tables_links_normal_colors', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tables_links_normal_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#1890FF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce .order_details .download-product a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .order_details .product-name a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce tbody .woocommerce-orders-table__cell.woocommerce-orders-table__cell-order-number > a' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'tables_links_hover_colors', [ 'label' => esc_html__( 'Hover', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tables_links_hover_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .woocommerce tbody .woocommerce-orders-table__cell.woocommerce-orders-table__cell-order-number > a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .order_details .download-product a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce .order_details .product-name a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control(
			'tables_divider_title',
			[
				'type'      => 'heading',
				'label'     => esc_html__( 'Dividers', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$widget->add_control(
			'tables_divider_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					'none'   => esc_html__( 'None', 'jupiterx-core' ),
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'groove' => esc_html__( 'Groove', 'jupiterx-core' ),
				],
				'default'   => 'solid',
				'selectors' => [
					'{{WRAPPER}} .woocommerce .shop_table tbody tr > *' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$widget->add_responsive_control(
			'tables_divider_border_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [ 'px' => 1 ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .shop_table tbody tr > *' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'tables_divider_border_type!' => 'none',
				],
			]
		);

		$widget->add_control(
			'tables_divider_border_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .woocommerce .shop_table tbody tr > *' => 'border-top-color: {{VALUE}};',
				],
				'default'   => '#D4D4D4',
				'condition' => [
					'tables_divider_border_type!' => 'none',
				],
			]
		);

		$widget->add_control(
			'tables_button_title',
			[
				'type'      => 'heading',
				'separator' => 'before',
				'label'     => esc_html__( 'Buttons', 'jupiterx-core' ),
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'tables_button_typography',
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button, {{WRAPPER}} .woocommerce-pagination .button',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'tables_button_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button, {{WRAPPER}} .woocommerce-pagination .button',
			]
		);

		$widget->start_controls_tabs( 'tables_button_styles' );

		$widget->start_controls_tab( 'tables_button_styles_normal', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tables_button_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#1890FF',
				'selectors' => [
					'{{WRAPPER}}' => '--tables-button-normal-text-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tables_button_normal_background',
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button, {{WRAPPER}} .woocommerce-pagination .button',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tables_button_normal_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button, {{WRAPPER}} .woocommerce-pagination .button',
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'tables_button_styles_hover', [ 'label' => esc_html__( 'Hover', 'jupiterx-core' ) ] );

		$widget->add_control(
			'tables_button_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--tables-button-hover-text-color: {{VALUE}};',
				],
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tables_button_hover_background',
				'selector' => '{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .order-again .button:hover, {{WRAPPER}} .woocommerce-pagination .button:hover',
			]
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tables_button_hover_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .order-again .button:hover, {{WRAPPER}} .woocommerce-pagination .button:hover',
			]
		);

		$widget->add_control(
			'tables_button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .order-again .button:hover, {{WRAPPER}} .woocommerce-pagination .button:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'tables_button_border_type!' => 'none',
				],
			]
		);

		$widget->add_control(
			'tables_button_hover_transition_duration',
			[
				'label'     => esc_html__( 'Transition Duration', 'jupiterx-core' ) . ' (ms)',
				'type'      => 'slider',
				'selectors' => [
					'{{WRAPPER}}' => '--tables-button-hover-transition-duration: {{SIZE}}ms',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
			]
		);

		$widget->add_control(
			'tables_button_hover_animation',
			[
				'label'              => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type'               => 'hover_animation',
				'frontend_available' => true,
				'render_type'        => 'template',
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control(
			'tables_button_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					'none'   => esc_html__( 'None', 'jupiterx-core' ),
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'groove' => esc_html__( 'Groove', 'jupiterx-core' ),
				],
				'default'   => 'none',
				'selectors' => [
					'{{WRAPPER}}' => '--tables-buttons-border-type: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$widget->add_responsive_control(
			'tables_button_border_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button, {{WRAPPER}} .woocommerce-pagination .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'tables_button_border_type!' => 'none',
				],
			]
		);

		$widget->add_control(
			'tables_button_border_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} ' => '--tables-buttons-border-color: {{VALUE}};',
				],
				'condition' => [
					'tables_button_border_type!' => 'none',
				],
			]
		);

		$widget->add_responsive_control(
			'tables_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tables-button-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'tables_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tables-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_section();
	}
}
