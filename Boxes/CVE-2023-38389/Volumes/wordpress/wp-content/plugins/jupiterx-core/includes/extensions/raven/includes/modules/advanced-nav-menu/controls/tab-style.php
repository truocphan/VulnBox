<?php
namespace JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Controls;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Tab_Style {
	public function __construct( $widget ) {
		$this->add_section_offcanvas( $widget );
		$this->add_section_menu_items( $widget );
		$this->add_section_submenu( $widget );
		$this->add_section_dropdown_menu( $widget );
		$this->add_section_dropdown_toggle_button( $widget );
		$this->add_section_close_button( $widget );
		$this->add_section_logo( $widget );
		$this->add_section_content_effects( $widget );
	}

	private function add_section_menu_items( $widget ) {
		$widget->start_controls_section(
			'section_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'terms' => [
						[
							'name' => 'layout',
							'operator' => 'in',
							'value' => [ 'horizontal', 'vertical' ],
						],
					],
				],
			]
		);

		$widget->add_group_control( 'typography',
			[
				'name'     => 'menu_item_typography',
				'scheme'   => '3',
				'selector' => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.raven-menu-item span.link-label',
				'fields_options' => [
					'font_size' => [
						'selectors' => [
							'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.raven-menu-item' => 'font-size: {{SIZE}}{{UNIT}}',
							'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.raven-menu-item svg.sub-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.raven-menu-item .sub-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
						],
					],
				],
			]
		);

		$widget->add_responsive_control( 'menu_item_space_between',
			[
				'label'      => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--menu-item-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'menu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'     => 'px',
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.raven-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'menu_item_text_alignment',
			[
				'label'        => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'         => 'choose',
				'toggle'       => false,
				'prefix_class' => 'raven%s-nav-menu-align-',
				'default'      => 'flex-start',
				'options'      => [
					'flex-start'    => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-horizontal ul.raven-adnav-menu > li.menu-item > a.raven-link-item' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-vertical ul.raven-adnav-menu > li.menu-item > a.raven-link-item' => 'justify-content: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'vertical',
						],
						[
							'name'     => 'alignment',
							'operator' => '===',
							'value'    => 'stretch',
						],
					],
				],
			]
		);

		$widget->add_responsive_control( 'pointer_width',
			[
				'label'      => esc_html__( 'Pointer Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [ '{{WRAPPER}}' => '--pointer-width: {{SIZE}}{{UNIT}};' ],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'pointer_type',
							'operator' => 'in',
							'value'    => [ 'underline', 'overline', 'doubleline', 'framed' ],
						],
					],
				],
			]
		);

		$widget->start_controls_tabs( 'menu_item_tabs' );

		// ▬ ▬ ▬ Menu Item NORMAL State ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'menu_item_color',
			[
				'label'    => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'     => 'color',
				'scheme'   => [
					'type'  => 'color',
					'value' => '3',
				],
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a:not(.active-link)' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'menu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a:not(.active-link)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}' => '--menu-items-bg-normal: {{VALUE}}',
				],
			]
		);

		// << Border >>
		$widget->add_control( 'menu_item_border_heading',
			[
				'label'     => esc_html__( 'Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'menu_item_border',
				'placeholder'    => '1px',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a:not(.active-link)',
				'condition'      => [ 'pointer_type!' => 'framed' ],
			]
		);

		$widget->add_control( 'menu_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item)::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item) > a:not(.active-link)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item) > a:not(.active-link)::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item) > a:not(.active-link)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ Menu Item HOVER State ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'hover_menu_item_color',
			[
				'label'    => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'     => 'color',
				'default'  => '#666666',
				'scheme'   => [
					'type'  => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:hover > a:not(.active-link)' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'hover_menu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:hover > a:not(.active-link)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'pointer_type!' => 'background',
				],
			]
		);

		$widget->add_control( 'hover_menu_item_pointer_color',
			[
				'label'      => esc_html__( 'Pointer Color', 'jupiterx-core' ),
				'type'       => 'color',
				'default'    => '#0077ff',
				'selectors'  => [ '{{WRAPPER}}' => '--pointer-color-hover: {{VALUE}};' ],
				'conditions' => [
					'terms' => [
						[
							'name' => 'pointer_type',
							'operator' => '!in',
							'value' => [ 'none', 'text' ],
						],
					],
				],
			]
		);

		// << Border >>
		$widget->add_control( 'hover_menu_item_border_heading',
			[
				'label'     => esc_html__( 'Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'hover_menu_item_border',
				'placeholder'    => '1px',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:hover > a:not(.active-link)',
				'condition'      => [ 'pointer_type!' => 'framed' ],
			]
		);

		$widget->add_control( 'hover_menu_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item):hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item):hover::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item):hover::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item):hover > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item):hover > a::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li:not(.current-menu-item):hover > a::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ Menu Item ACTIVE State ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_menu_item_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'active_menu_item_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#666666',
				'scheme'    => [
					'type'  => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.active-link' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'active_menu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.active-link' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'pointer_type!' => 'background',
				],
			]
		);

		$widget->add_control( 'active_menu_item_pointer_color',
			[
				'label'     => esc_html__( 'Pointer Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#0077ff',
				'selectors' => [ '{{WRAPPER}}' => '--pointer-color-active: {{VALUE}}' ],
				'conditions' => [
					'terms' => [
						[
							'name' => 'pointer_type',
							'operator' => '!in',
							'value' => [ 'none', 'text' ],
						],
					],
				],
			]
		);

		// << Border >>
		$widget->add_control( 'active_menu_item_border_heading',
			[
				'label'     => esc_html__( 'Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'active_menu_item_border',
				'placeholder'    => '1px',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.active-link',
				'condition'      => [ 'pointer_type!' => 'framed' ],
			]
		);

		$widget->add_control( 'active_menu_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.active-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.active-link::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li > a.active-link::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li.current-menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li.current-menu-item::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) .raven-adnav-menu > li.current-menu-item::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		// << Icons >>
		$widget->add_control( 'menu_icons_heading',
			[
				'label'     => esc_html__( 'Icons', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_responsive_control( 'menu_icons_size',
			[
				'label'      => esc_html__( 'Size', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--menu-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'menu_icons_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--menu-icon-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->start_controls_tabs( 'menu_icons_tabs' );

		$widget->start_controls_tab( 'menu_tab_icons_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'menu_icons_color_normal',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}}' => '--menu-icon-color-normal: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'menu_tab_icons_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'menu_icons_color_hover',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}}' => '--menu-icon-color-hover: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'menu_tab_icons_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'menu_icons_color_active',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}}' => '--menu-icon-color-active: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	private function add_section_submenu( $widget ) {
		$widget->start_controls_section( 'section_submenu',
			[
				'label'      => esc_html__( 'Submenu', 'jupiterx-core' ),
				'tab'        => 'style',
				'conditions' => [
					'terms' => [
						[
							'name' => 'layout',
							'operator' => 'in',
							'value' => [ 'horizontal', 'vertical' ],
						],
					],
				],
			]
		);

		$widget->add_group_control( 'typography',
			[
				'name'     => 'submenu_item_typography',
				'scheme'   => '3',
				'selector' => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a.raven-submenu-item span.link-label',
			]
		);

		$widget->add_responsive_control( 'submenu_item_text_alignment',
			[
				'label'        => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'         => 'choose',
				'toggle'       => false,
				'prefix_class' => 'raven%s-nav-menu-align-',
				'default'      => 'center',
				'options'      => [
					'flex-start'    => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-horizontal ul.submenu a.raven-link-item' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-vertical ul.submenu a.raven-link-item' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'submenu_space_between',
			[
				'label'       => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type'        => 'slider',
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'render_type' => 'ui',
				'selectors'   => [
					'{{WRAPPER}}' => '--submenu-spacing: {{SIZE}}',
				],
			]
		);

		$widget->add_responsive_control( 'submenu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'     => 'px',
					'top'      => '10',
					'right'    => '10',
					'bottom'   => '10',
					'left'     => '10',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a.raven-submenu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control( 'submenu_opening_position',
			[
				'label'        => esc_html__( 'Position', 'jupiterx-core' ),
				'type'         => 'select',
				'render_type'  => 'template',
				'default'      => 'bottom',
				'prefix_class' => 'submenu-position-',
				'options'      => [
					'top'    => esc_html__( 'Top', 'jupiterx-core' ),
					'bottom' => esc_html__( 'Bottom', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		// << Divider >>
		$widget->add_control( 'submenu_item_divider_heading',
			[
				'label'     => esc_html__( 'Divider', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_control( 'submenu_item_divider_type',
			[
				'label'   => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type'    => 'select',
				'default' => 'none',
				'options' => [
					'none'   => esc_html__( 'None', 'jupiterx-core' ),
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'groove' => esc_html__( 'Groove', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-vertical ul.submenu > li:not(:last-of-type)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}}.submenu-position-bottom  nav.raven-adnav-menu-main.raven-adnav-menu-horizontal ul.submenu > li:not(:last-of-type)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}}.submenu-position-top  nav.raven-adnav-menu-main.raven-adnav-menu-horizontal ul.submenu > li:not(:first-of-type)' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'submenu_item_divider_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#808080',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-vertical ul.submenu > li:not(:last-of-type)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.submenu-position-bottom  nav.raven-adnav-menu-main.raven-adnav-menu-horizontal ul.submenu > li:not(:last-of-type)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.submenu-position-top  nav.raven-adnav-menu-main.raven-adnav-menu-horizontal ul.submenu > li:not(:first-of-type)' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'submenu_item_divider_type!' => 'none',
				],
			]
		);

		$widget->add_responsive_control( 'submenu_item_divider_width',
			[
				'label'      => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [ 'size' => 5 ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-vertical ul.submenu > li:not(:last-of-type)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.submenu-position-bottom  nav.raven-adnav-menu-main.raven-adnav-menu-horizontal ul.submenu > li:not(:last-of-type)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.submenu-position-top  nav.raven-adnav-menu-main.raven-adnav-menu-horizontal ul.submenu > li:not(:first-of-type)' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'submenu_item_divider_type!' => 'none',
				],
			]
		);

		$widget->add_control( 'submenu_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'submenu_border',
				'placeholder'    => '1px',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li.menu-item a.raven-submenu-item',
			]
		);

		$widget->add_control( 'submenu_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li.menu-item a.raven-submenu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_group_control( 'box-shadow',
			[
				'name'      => 'submenu_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li.menu-item a.raven-submenu-item',
			]
		);

		$widget->start_controls_tabs( 'submenu_item_tabs' );

		// ▬ ▬ ▬ Submenu Item NORMAL State ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_submenu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'submenu_item_color',
			[
				'label'   => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'    => 'color',
				'default' => '#FFFFFF',
				'scheme'  => [
					'type' => 'color',
					'value' => '3',
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a.raven-submenu-item' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'submenu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a.raven-submenu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ Submenu Item HOVER State ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_submenu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'hover_submenu_item_color',
			[
				'label'   => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'    => 'color',
				'default' => '#BBBBBB',
				'scheme'  => [
					'type'  => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a:hover:not(.active-link)' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'hover_submenu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a:hover:not(.active-link)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ Submenu Item ACTIVE State ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_submenu_item_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'active_submenu_item_color',
			[
				'label'   => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'    => 'color',
				'default' => '#BBBBBB',
				'scheme'  => [
					'type'  => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a.active-link' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'active_submenu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main:not(.raven-adnav-menu-dropdown):not(.raven-adnav-menu-offcanvas) ul.submenu > li > a.active-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	private function add_section_logo( $widget ) {
		$center_logo_terms = [
			[
				'name'     => 'layout',
				'operator' => '===',
				'value'    => 'horizontal',
			],
			[
				'name'     => 'center_logo',
				'operator' => '===',
				'value'    => 'yes',
			],
		];

		$side_logo_terms = [
			[
				'name'     => 'mobile_layout',
				'operator' => '===',
				'value'    => 'side',
			],
			[
				'name'     => 'side_logo',
				'operator' => '===',
				'value'    => 'yes',
			],
		];

		$offcanvas_logo_terms = [
			[
				'name'     => 'layout',
				'operator' => '===',
				'value'    => 'offcanvas',
			],
			[
				'name'     => 'side_logo',
				'operator' => '===',
				'value'    => 'yes',
			],
		];

		$widget->start_controls_section( 'section_logo',
			[
				'label' => esc_html__( 'Logo', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[ 'terms' => $center_logo_terms ],
						[ 'terms' => $side_logo_terms ],
						[ 'terms' => $offcanvas_logo_terms ],
					],
				],
			]
		);

		// << Center Logo >>
		$widget->add_control('center_logo_heading',
			[
				'label'      => esc_html__( 'Center Logo', 'jupiterx-core' ),
				'type'       => 'heading',
				'conditions' => [ 'terms' => $center_logo_terms ],
			]
		);

		$widget->add_responsive_control( 'center_logo_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ '%', 'px' ],
				'default'    => [ 'unit' => '%' ],
				'range'      => [
					'%'  => [ 'max' => 100 ],
					'px' => [ 'max' => 1000 ],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--adnav-center-logo-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [ 'terms' => $center_logo_terms ],
			]
		);

		$widget->add_responsive_control( 'center_logo_margin',
			[
				'label'      => esc_html__( 'Margin', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-adnav-center-logo' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
				'conditions' => [ 'terms' => $center_logo_terms ],
			]
		);

		$widget->add_responsive_control( 'logo_controls_divider',
			[
				'type'       => 'divider',
				'conditions' => [ 'terms' => $center_logo_terms ],
			]
		);

		// << Side Logo >>
		$widget->add_control('side_logo_heading',
			[
				'label'      => esc_html__( 'Side Logo', 'jupiterx-core' ),
				'type'       => 'heading',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[ 'terms' => $side_logo_terms ],
						[ 'terms' => $offcanvas_logo_terms ],
					],
				],
			]
		);

		$widget->add_responsive_control( 'side_logo_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ '%', 'px' ],
				'default'    => [ 'unit' => '%' ],
				'range'      => [
					'%'  => [ 'max' => 100 ],
					'px' => [ 'max' => 1000 ],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--adnav-side-logo-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[ 'terms' => $side_logo_terms ],
						[ 'terms' => $offcanvas_logo_terms ],
					],
				],
			]
		);

		$widget->add_responsive_control( 'side_logo_margin',
			[
				'label'      => esc_html__( 'Margin', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-adnav-side-logo' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[ 'terms' => $side_logo_terms ],
						[ 'terms' => $offcanvas_logo_terms ],
					],
				],
			]
		);

		$widget->end_controls_section();
	}

	private function add_section_dropdown_menu( $widget ) {
		$widget->start_controls_section( 'section_dropdown_menu',
			[
				'label'      => esc_html__( 'Dropdown Menu', 'jupiterx-core' ),
				'tab'        => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'dropdown', 'offcanvas' ],
						],
						[
							'name'     => 'mobile_breakpoint',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			]
		);

		$widget->add_control( 'dropdown_menu_section_notice',
			[
				'type' => 'raw_html',
				'raw'  => sprintf(
					'<span class="elementor-control-field-description">%1$s<br>%2$s</span>',
					esc_html__( 'On desktop, this will affect the Drowdown and Off Canvas layouts.', 'jupiterx-core' ),
					esc_html__( 'On mobile, this will affect all layouts.', 'jupiterx-core' )
				),
			]
		);

		// << Container Layout >>
		$widget->add_control('menu_container_layout_heading',
			[
				'label'      => esc_html__( 'Container Layout', 'jupiterx-core' ),
				'type'       => 'heading',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'horizontal', 'vertical' ],
						],
						[
							'name'     => 'mobile_layout',
							'operator' => '===',
							'value'    => 'side',
						],
					],
				],
			]
		);

		$widget->add_control( 'side_menu_alignment',
			[
				'label'              => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'               => 'choose',
				'default'            => 'right',
				'frontend_available' => true,
				'options'            => [
					'left'  => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'right' => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'conditions'         => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'horizontal', 'vertical' ],
						],
						[
							'name'     => 'mobile_layout',
							'operator' => '===',
							'value'    => 'side',
						],
					],
				],
			]
		);

		$widget->add_responsive_control( 'menu_container_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [ 'size' => 300 ],
				'range'      => [
					'px' => [
						'min' => 150,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile.raven-adnav-menu-side' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}' => '--menu-container-width: {{SIZE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'horizontal', 'vertical' ],
						],
						[
							'name'     => 'mobile_layout',
							'operator' => '===',
							'value'    => 'side',
						],
					],
				],
			]
		);

		$widget->add_control( 'side_menu_overlay_color',
			[
				'label'      => esc_html__( 'Overlay Color', 'jupiterx-core' ),
				'type'       => 'color',
				'default'    => '#80808080',
				'selectors'  => [
					'main' => '--adnav-menu-overlay-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'horizontal', 'vertical' ],
						],
						[
							'name'     => 'mobile_layout',
							'operator' => '===',
							'value'    => 'side',
						],
					],
				],
			]
		);

		$widget->add_control( 'menu_container_layout_divider',
			[
				'type'       => 'divider',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'horizontal', 'vertical' ],
						],
						[
							'name'     => 'mobile_layout',
							'operator' => '===',
							'value'    => 'side',
						],
					],
				],
			]
		);

		// << Container Background >>
		$widget->add_control('menu_container_background_heading',
			[
				'label'      => esc_html__( 'Container Background', 'jupiterx-core' ),
				'type'       => 'heading',
				'condition'  => [ 'layout!' => 'offcanvas' ],
			]
		);

		$widget->add_group_control( 'background',
			[
				'name'           => 'menu_container_background',
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-mobile,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color'      => [
						'default' => '#FFFFFF',
					],
				],
				'condition'      => [ 'layout!' => 'offcanvas' ],
			]
		);

		$widget->add_responsive_control( 'menu_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ 'layout!' => 'offcanvas' ],
			]
		);

		// << Container Border >>
		$widget->add_control( 'menu_container_border_heading',
			[
				'label'     => esc_html__( 'Container Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => '!==',
							'value'    => 'dropdown',
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'offcanvas',
								],
								[
									'name'     => 'mobile_layout',
									'operator' => '!==',
									'value'    => 'dropdown',
								],
							],
						],
					],
				],
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'menu_container_border',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-mobile,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => '!==',
							'value'    => 'dropdown',
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'offcanvas',
								],
								[
									'name'     => 'mobile_layout',
									'operator' => '!==',
									'value'    => 'dropdown',
								],
							],
						],
					],
				],
			]
		);

		$widget->add_control( 'menu_container_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => '!==',
							'value'    => 'dropdown',
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'offcanvas',
								],
								[
									'name'     => 'mobile_layout',
									'operator' => '!==',
									'value'    => 'dropdown',
								],
							],
						],
					],
				],
			]
		);

		// << Items >>
		$widget->add_group_control( 'typography',
			[
				'name'           => 'mobile_menu_item_typography',
				'scheme'         => '3',
				'fields_options' => [
					'font_size' => [
						'selectors' => [
							'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu li.menu-item > a' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu li.menu-item > a' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu li.menu-item > a' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu li > a svg.sub-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu li > a .sub-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu li > a svg.sub-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu li > a .sub-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu li > a svg.sub-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu li > a .sub-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
						],
					],
				],
				'selector'      => '
					{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu li > a span.link-label,
					{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu li > a span.link-label,
					{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu li > a span.link-label
				',
			]
		);

		$widget->add_responsive_control( 'mobile_menu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 8,
					'right'    => 32,
					'bottom'   => 8,
					'left'     => 32,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.dropdown-item-align-flex-end .raven-adnav-menu-dropdown .raven-menu-item.has-submenu .sub-arrow' => 'left: {{LEFT}}{{UNIT}};right:100%;margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-flex-start .raven-adnav-menu-dropdown .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-center .raven-adnav-menu-dropdown .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.dropdown-item-align-flex-end .raven-adnav-menu-offcanvas .raven-menu-item.has-submenu .sub-arrow' => 'left: {{LEFT}}{{UNIT}};right:100%;margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-flex-start .raven-adnav-menu-offcanvas .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-center .raven-adnav-menu-offcanvas .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-flex-end .raven-adnav-menu-full-screen .raven-menu-item.has-submenu .sub-arrow' => 'left: {{LEFT}}{{UNIT}};right:100%;margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-flex-start .raven-adnav-menu-full-screen .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-center .raven-adnav-menu-full-screen .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-flex-end .raven-adnav-menu-side .raven-menu-item.has-submenu .sub-arrow' => 'left: {{LEFT}}{{UNIT}};right:100%;margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-flex-start .raven-adnav-menu-side .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
					'{{WRAPPER}}.dropdown-item-align-center .raven-adnav-menu-side .raven-menu-item.has-submenu .sub-arrow' => 'right: {{RIGHT}}{{UNIT}};margin-top: calc( calc( {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} ) / 2 );',
				],
			]
		);

		$widget->add_responsive_control( 'mobile_menu_subitem_indentation',
			[
				'label'      => esc_html__( 'Sub Items Indentation', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [ 'size' => 15 ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.submenu > li.menu-item > a > *:first-child' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.submenu > li.menu-item > a > *:first-child' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.submenu > li.menu-item > a > *:first-child' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'mobile_menu_distance',
			[
				'label'      => esc_html__( 'Distance', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}:not(.raven-nav-menu-stretch) nav.raven-adnav-menu-mobile' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-nav-menu-stretch nav.raven-adnav-menu-mobile' => 'top: auto !important; margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}:not(.raven-nav-menu-stretch) nav.raven-adnav-menu-main.raven-adnav-menu-dropdown' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-nav-menu-stretch nav.raven-adnav-menu-main.raven-adnav-menu-dropdown' => 'top: auto !important; margin-top: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'dropdown',
						],
						[
							'terms'    => [
								[
									'name'     => 'layout',
									'operator' => 'in',
									'value'    => [ 'horizontal', 'vertical' ],
								],
								[
									'name'     => 'mobile_layout',
									'operator' => '===',
									'value'    => 'dropdown',
								],
							],
						],
					],
				],
			]
		);

		$widget->add_group_control( 'box-shadow',
			[
				'label' => esc_html__( 'Container Box Shadow', 'jupiterx-core' ),
				'name'      => 'submenu_container_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown, {{WRAPPER}} nav.raven-adnav-menu-mobile.raven-adnav-menu-dropdown',
				[
					'terms'    => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'horizontal', 'vertical' ],
						],
						[
							'name'     => 'mobile_layout',
							'operator' => '===',
							'value'    => 'dropdown',
						],
					],
				],
			]
		);

		// << Divider >>
		$widget->add_control( 'mobile_menu_item_divider_heading',
			[
				'label'     => esc_html__( 'Items Divider', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_control( 'mobile_menu_item_divider_type',
			[
				'label'     => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					''       => esc_html__( 'None', 'jupiterx-core' ),
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'groove' => esc_html__( 'Groove', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.submenu' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.submenu' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.submenu' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'mobile_menu_item_divider_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li:not(:last-child)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.submenu' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li:not(:last-child)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.submenu' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li:not(:last-child)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.submenu' => 'border-color: {{VALUE}};',
				],
				'condition' => [ 'mobile_menu_item_divider_type!' => '' ],
			]
		);

		$widget->add_responsive_control( 'mobile_menu_item_divider_width',
			[
				'label'      => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [ 'size' => 1 ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.submenu' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.submenu' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.submenu' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'mobile_menu_item_divider_type!' => '' ],
			]
		);

		// << Menu Border >>
		$widget->add_control( 'mobile_menu_border_heading',
			[
				'label'     => esc_html__( 'Menu Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'mobile_menu_border',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu',
			]
		);

		$widget->add_control( 'mobile_menu_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile div.raven-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// << Other Controls >>
		$widget->add_group_control( 'box-shadow',
			[
				'name'           => 'mobile_menu_box_shadow',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'before',
					],
				],
				'selector'       => '{{WRAPPER}} nav.raven-adnav-menu-mobile div.raven-container,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu,{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu',
			]
		);

		$widget->add_control( 'mobile_menu_item_align',
			[
				'label'        => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'         => 'choose',
				'toggle'       => true,
				'prefix_class' => 'dropdown-item-align-',
				'options'      => [
					'flex-start'    => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center'        => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'      => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors'   => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile ul.raven-adnav-menu li.menu-item > a' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown ul.raven-adnav-menu li.menu-item > a' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas ul.raven-adnav-menu li.menu-item > a' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$widget->start_controls_tabs( 'mobile_menu_item_tabs' );

		// ▬ ▬ ▬ tab menu items NORMAL state ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_mobile_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'mobile_menu_item_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'scheme'    => [
					'type'  => 'color',
					'value' => '3',
				],
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'mobile_menu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}' => '--adnav-scrollbar-bg-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ tab menu items HOVER state ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_mobile_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'hover_mobile_menu_item_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'scheme'    => [
					'type'  => 'color',
					'value' => '4',
				],
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item:not(.active-link):hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item:not(.active-link):hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item:not(.active-link):hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item:not(.active-link).highlighted' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item:not(.active-link).highlighted' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item:not(.active-link).highlighted' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'hover_mobile_menu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item:not(.active-link):hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item:not(.active-link):hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item:not(.active-link):hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-mobile .raven-adnav-menu li > a.raven-menu-item:not(.active-link).highlighted' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.raven-menu-item:not(.active-link).highlighted' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.raven-menu-item:not(.active-link).highlighted' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ tab menu items ACTIVE state ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_mobile_menu_item_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'active_mobile_menu_item_color',
			[
				'label'     => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'scheme'    => [
					'type'  => 'color',
					'value' => '4',
				],
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-mobile .raven-adnav-menu li > a.active-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.active-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.active-link' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'active_mobile_menu_item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-mobile .raven-adnav-menu li > a.active-link' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-dropdown .raven-adnav-menu li > a.active-link' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas .raven-adnav-menu li > a.active-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		// << Icons >>
		$widget->add_control( 'dropdown_icons_heading',
			[
				'label'     => esc_html__( 'Icons', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_responsive_control( 'dropdown_icons_size',
			[
				'label'      => esc_html__( 'Size', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--dropdown-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'dropdown_icons_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--dropdown-icon-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->start_controls_tabs( 'dropdown_icons_tabs' );

		$widget->start_controls_tab( 'dropdown_tab_icons_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'dropdown_icons_color_normal',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--dropdown-icon-color-normal: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'dropdown_tab_icons_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'dropdown_icons_color_hover',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--dropdown-icon-color-hover: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'dropdown_tab_icons_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'dropdown_icons_color_active',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--dropdown-icon-color-active: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	private function add_section_dropdown_toggle_button( $widget ) {
		$widget->start_controls_section( 'section_toggle_button',
			[
				'label'      => esc_html__( 'Dropdown Toggle Button', 'jupiterx-core' ),
				'tab'        => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'layout',
							'operator' => 'in',
							'value'    => [ 'dropdown', 'offcanvas' ],
						],
						[
							'name'     => 'mobile_breakpoint',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			]
		);

		$widget->add_control( 'dropdown_toggle_button_section_notice',
			[
				'type' => 'raw_html',
				'raw'  => sprintf(
					'<span class="elementor-control-field-description">%1$s<br>%2$s</span>',
					esc_html__( 'On desktop, this will affect the Drowdown and Off Canvas layouts.', 'jupiterx-core' ),
					esc_html__( 'On mobile, this will affect all layouts.', 'jupiterx-core' )
				),
			]
		);

		$widget->add_responsive_control( 'toggle_button_size',
			[
				'label'      => esc_html__( 'Size', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--toggle-button-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'toggle_button_line_thickness',
			[
				'label'      => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .raven-adnav-menu-toggle .raven-adnav-menu-toggle-button div.hamburger .hamburger-box .hamburger-inner' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-adnav-menu-toggle .raven-adnav-menu-toggle-button div.hamburger .hamburger-box .hamburger-inner::before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-adnav-menu-toggle .raven-adnav-menu-toggle-button div.hamburger .hamburger-box .hamburger-inner::after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'toggle_button_animation!' => 'none',
					'custom_toggle_button!'    => 'yes',
				],
			]
		);

		$widget->add_responsive_control( 'toggle_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'     => 'px',
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .raven-adnav-menu-toggle-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control( 'toggle_button_align',
			[
				'label'     => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'      => 'choose',
				'default'   => 'center',
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-toggle' => 'text-align: {{VALUE}};',
				],
			]
		);

		$widget->start_controls_tabs( 'toggle_button_tabs' );

		// ▬ ▬ ▬ toggle button NORMAL state ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_toggle_button_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'toggle_button_color',
			[
				'label'       => esc_html__( 'Color', 'jupiterx-core' ),
				'type'        => 'color',
				'device_args' => [
					'desktop' => [
						'scheme' => [
							'type'  => 'color',
							'value' => '2',
						],
					],
				],
				'selectors'   => [
					'{{WRAPPER}}' => '--toggle-button-color-normal: {{VALUE}}',
				],
			]
		);

		$widget->add_control( 'toggle_button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-toggle-button:not(:hover)' => 'background-color: {{VALUE}};',
				],
			]
		);

		// << Border >>
		$widget->add_control( 'toggle_button_border_heading',
			[
				'label'     => esc_html__( 'Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'toggle_button_border',
				'fields_options' => [
					'width' => [
						'label'      => esc_html__( 'Border Width', 'jupiterx-core' ),
						'responsive' => true,
					],
				],
				'selector'       => '{{WRAPPER}} .raven-adnav-menu-toggle-button:not(:hover)',
			]
		);

		$widget->add_responsive_control( 'toggle_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-adnav-menu-toggle-button:not(:hover)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ ▬ toggle button HOVER state ▬ ▬ ▬
		$widget->start_controls_tab( 'tab_toggle_button_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'hover_toggle_button_color',
			[
				'label'       => esc_html__( 'Color', 'jupiterx-core' ),
				'type'        => 'color',
				'device_args' => [
					'desktop' => [
						'scheme' => [
							'type'  => 'color',
							'value' => '4',
						],
					],
				],
				'selectors'   => [
					'{{WRAPPER}}' => '--toggle-button-color-hover: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'hover_toggle_button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-toggle-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		// << Border >>
		$widget->add_control( 'hover_toggle_button_border_heading',
			[
				'label'     => esc_html__( 'Border', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_group_control( 'border',
			[
				'name'           => 'hover_toggle_button_border',
				'fields_options' => [
					'width' => [
						'label'      => esc_html__( 'Border Width', 'jupiterx-core' ),
						'responsive' => true,
					],
				],
				'selector'       => '{{WRAPPER}} .raven-adnav-menu-toggle-button:hover',
			]
		);

		$widget->add_responsive_control( 'hover_toggle_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-adnav-menu-toggle-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	private function add_section_offcanvas( $widget ) {
		$widget->start_controls_section( 'section_offcanvas',
			[
				'label'     => esc_html__( 'Off-Canvas', 'jupiterx-core' ),
				'tab'       => 'style',
				'condition' => [ 'layout' => 'offcanvas' ],
			]
		);

		$widget->add_responsive_control( 'offcanvas_box_width',
			[
				'label'              => esc_html__( 'Box Width', 'jupiterx-core' ),
				'type'               => 'slider',
				'size_units'         => [ 'px' ],
				'default'            => [ 'size' => 300 ],
				'range'              => [
					'px' => [
						'min' => 150,
						'max' => 1000,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}' => '--offcanvas-box-width: {{SIZE}};',
				],
			]
		);

		$widget->add_responsive_control( 'offcanvas_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control( 'offcanvas_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} nav.raven-adnav-menu-main.raven-adnav-menu-offcanvas' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->add_control( 'offcanvas_overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#80808080',
				'frontend_available' => true,
			]
		);

		$widget->end_controls_section();
	}

	private function add_section_close_button( $widget ) {
		$widget->start_controls_section( 'section_close_button',
			[
				'label'      => esc_html__( 'Close Button', 'jupiterx-core' ),
				'tab'        => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'offcanvas',

						],
						[
							'terms' => [
								[
									'name'     => 'layout',
									'operator' => 'in',
									'value'    => [ 'horizontal', 'vertical' ],
								],
								[
									'name'     => 'mobile_layout',
									'operator' => 'in',
									'value'    => [ 'side', 'full-screen' ],
								],
							],

						],
					],
				],
			]
		);

		$widget->add_control( 'close_button_section_notice',
			[
				'type' => 'raw_html',
				'raw'  => sprintf(
					'<span class="elementor-control-field-description">%s</span>',
					esc_html__( 'Close button is used in Off Canvas layout and mobile menu\'s Full Screen and Side layouts.', 'jupiterx-core' )
				),
			]
		);

		$widget->add_responsive_control( 'close_button_size',
			[
				'label'      => esc_html__( 'Size', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--close-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// ▬ ▬ close button NORMAL state ▬ ▬
		$widget->start_controls_tabs( 'close_button_tabs' );

		$widget->start_controls_tab( 'tab_close_button_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$widget->add_control( 'close_button_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-close-button' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		// ▬ ▬ close button HOVER state ▬ ▬
		$widget->start_controls_tab( 'tab_close_button_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$widget->add_control('hover_close_button_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#BBBBBB',
				'selectors' => [
					'{{WRAPPER}} .raven-adnav-menu-close-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	private function add_section_content_effects( $widget ) {
		$widget->start_controls_section( 'section_content_effect_heading',
			[
				'label' => esc_html__( 'Content Effects', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$widget->add_control( 'content_effect_notice',
			[
				'type' => 'raw_html',
				'raw'  => sprintf(
					'<span class="elementor-control-field-description">%s</span>',
					esc_html__( 'Using blur and overlay effects on content can enhance the user experience by highlighting menu options and making it clear to the user that they are viewing a submenu/mega-menu. Please note that this option is only compatible with the Horizontal Layout and must be used in a header template. It will not work for the Mobile Menu.', 'jupiterx-core' )
				),
			]
		);

		$widget->add_control( 'content_effect_blur_content', [
			'label'              => esc_html__( 'Blur Content', 'jupiterx-core' ),
			'type'               => 'switcher',
			'description'        => esc_html__( 'Blur content when the submenu/mega-menu is opened.', 'jupiterx-core' ),
			'prefix_class'       => 'raven-blur-content-',
			'return_value'       => 'enabled',
			'render_type'        => 'template',
			'frontend_available' => true,
		] );

		$widget->add_control( 'content_effect_blur_intensity', [
			'label'      => esc_html__( 'Blur Intensity', 'jupiterx-core' ),
			'type'       => 'slider',
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 20,
				],
			],
			'default'    => [ 'size' => 5 ],
			'frontend_available' => true,
			'condition'   => [
				'content_effect_blur_content' => 'enabled',
			],
		] );

		$widget->add_control( 'content_effect_content_overlay', [
			'label'              => esc_html__( 'Content Overlay', 'jupiterx-core' ),
			'type'               => 'switcher',
			'description'        => esc_html__( 'Add an overlay to the content when the submenu/mega-menu is opened.', 'jupiterx-core' ),
			'prefix_class'       => 'raven-blur-content-overlay-',
			'return_value'       => 'enabled',
			'render_type'        => 'template',
			'frontend_available' => true,
		] );

		$widget->add_control( 'content_effect_content_overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => 'rgba(0,0,0,0.3)',
				'condition'   => [
					'content_effect_content_overlay' => 'enabled',
				],
				'selectors' => [
					'.jupiterx-advanced-nav-content-effect-enabled-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_section();
	}
}
