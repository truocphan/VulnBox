<?php
namespace JupiterX_Core\Raven\Modules\Tabs\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Controls\Query;
use Elementor\Controls_Manager;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class Tabs extends Base_Widget {

	public function get_name() {
		return 'raven-tabs';
	}

	public function get_title() {
		return esc_html__( 'Advanced Tab', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-tabs';
	}

	protected function register_controls() {
		$this->register_content_general_section();
		$this->register_content_alignment_section();
		$this->register_content_settings_section();
		$this->register_styles_content_tab();
		$this->register_styles_hover_tab();
		$this->register_styles_ative_tab();
	}

	private function register_content_general_section() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Items', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Tab Title', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Tab Title', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'tab_icon_new',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'tab_icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'tab_content_type',
			[
				'label' => esc_html__( 'Content Type', 'jupiterx-core' ),
				'label_block' => true,
				'type' => 'select',
				'options' => [
					'editor' => esc_html__( 'Editor', 'jupiterx-core' ),
					'template' => esc_html__( 'Template', 'jupiterx-core' ),
				],
				'default' => 'editor',
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'default' => esc_html__( 'Tab Content', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Tab Content', 'jupiterx-core' ),
				'type' => 'wysiwyg',
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'tab_content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'tab_custom_template',
			[
				'label'       => esc_html__( 'Choose a template', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'multiple'    => false,
				'query'       => [
					'source'         => Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default'     => false,
				'condition'   => [
					'tab_content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'tab_css_id',
			[
				'label' => esc_html__( 'Control CSS ID', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Tab #1', 'jupiterx-core' ),
						'tab_content' => esc_html__( 'I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
					],
					[
						'tab_title' => esc_html__( 'Tab #2', 'jupiterx-core' ),
						'tab_content' => esc_html__( 'I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Tabs Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Top', 'jupiterx-core' ),
					'vertical' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
					'reversed-horizontal' => esc_html__( 'Bottom', 'jupiterx-core' ),
					'reversed-vertical' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'type_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					],
					'space_evenly' => [
						'title' => esc_html__( 'Space Evenly', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'selectors_dictionary' => [
					'left' => 'justify-content: flex-start;',
					'center' => 'justify-content: center;flex-direction: row;',
					'right' => 'justify-content: flex-end;flex-direction: row;',
					'space_evenly' => 'flex-grow: 1;flex-basis: 0;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-horizontal .raven-tabs-list, {{WRAPPER}} .raven-tabs-reversed-horizontal .raven-tabs-list' => '{{VALUE}}',
					'{{WRAPPER}} .raven-tabs-horizontal .raven-tabs-list .raven-tabs-title, {{WRAPPER}} .raven-tabs-reversed-horizontal .raven-tabs-list .raven-tabs-title' => '{{VALUE}}',
				],
				'condition' => [
					'type' => [ 'horizontal' ],
				],
			]
		);
	}

	/**
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	private function register_content_alignment_section() {
		$this->add_control(
			'reversed_horizontal_type_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					],
					'space_evenly' => [
						'title' => esc_html__( 'Space Evenly', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'selectors_dictionary' => [
					'left' => 'justify-content: flex-start;',
					'center' => 'justify-content: center;flex-direction: row;',
					'right' => 'justify-content: flex-end;flex-direction: row;',
					'space_evenly' => 'flex-grow: 1;flex-basis: 1;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-reversed-horizontal .raven-tabs-list' => '{{VALUE}}',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal .raven-tabs-list .raven-tabs-title' => '{{VALUE}}',
				],
				'condition' => [
					'type' => [ 'reversed-horizontal' ],
				],
			]
		);

		$this->add_control(
			'vertical_type_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'space_evenly' => [
						'title' => esc_html__( 'Space Evenly', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-flow: column;justify-content: flex-start;',
					'middle' => 'flex-flow: column;justify-content: center;',
					'bottom' => 'flex-flow: column;justify-content: flex-end;',
					'space_evenly' => 'flex-grow: 1;flex-basis: 0;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-vertical .raven-tabs-list, {{WRAPPER}} .raven-tabs-reversed-vertical .raven-tabs-list' => '{{VALUE}}',
					'{{WRAPPER}}.raven-tabs-v-align-space_evenly .raven-tabs-vertical .raven-tabs-title' => '{{VALUE}}',
					'{{WRAPPER}}.raven-tabs-v-align-space_evenly .raven-tabs-reversed-vertical .raven-tabs-title' => '{{VALUE}}',
				],
				'prefix_class' => 'raven-tabs-v-align-',
				'condition' => [
					'type' => [ 'vertical', 'reversed-vertical' ],
				],
			]
		);

		$this->add_responsive_control(
			'label_alignment',
			[
				'label' => esc_html__( 'Label Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs .raven-tabs-list .raven-tabs-title' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs .raven-tabs-content-wrapper .raven-tabs-title' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tabs_type',
			[
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'card',
				'options' => [
					'card' => esc_html__( 'Card', 'jupiterx-core' ),
					'line' => esc_html__( 'Line', 'jupiterx-core' ),
					'button' => esc_html__( 'Button', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'mobile_stacking',
			[
				'label'     => esc_html__( 'Mobile Stacking', 'jupiterx-core' ),
				'type'      => 'switcher',
				'label_on'  => esc_html__( 'ON', 'jupiterx-core' ),
				'label_off' => esc_html__( 'OFF', 'jupiterx-core' ),
				'default'   => 'yes',
				'render_type' => 'template',
				'prefix_class' => 'raven-tabs-mobile-stacking-',
			]
		);

		$this->end_controls_section();
	}

	private function register_content_settings_section() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'tabs_pane_animation',
			[
				'label' => esc_html__( 'Tabs Pane Animation', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'fade' => esc_html__( 'Fade', 'jupiterx-core' ),
					'zoom-in' => esc_html__( 'Zoom in', 'jupiterx-core' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'jupiterx-core' ),
					'move-up' => esc_html__( 'Move Up', 'jupiterx-core' ),
					'slide-left' => esc_html__( 'Slide in Left', 'jupiterx-core' ),
					'slide-right' => esc_html__( 'Slide in Right', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tabs_event',
			[
				'label' => esc_html__( 'Tabs Event', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'click' => esc_html__( 'Click', 'jupiterx-core' ),
					'mouseover' => esc_html__( 'Hover', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'default' => 'click',
			]
		);

		$this->add_control(
			'auto_switch',
			[
				'label' => esc_html__( 'Auto Switch', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'default' => 'label_off',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'auto_swtich_delay',
			[
				'label' => esc_html__( 'Auto Switch Delay', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( '3000', 'jupiterx-core' ),
				'frontend_available' => true,
				'render_type' => 'template',
				'classes' => 'raven-switch-delay',
				'condition' => [
					'auto_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'use_ajax_loading',
			[
				'label' => esc_html__( 'Use Ajax Loading for Template', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	private function register_styles_content_tab() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => esc_html__( 'Tab Controls', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'tabs_normal_style',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-tabs-title',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs-title-icon > svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs-title-icon svg > *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-title-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_main',
				'label'     => esc_html__( 'Background Type', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-tabs-title, {{WRAPPER}} .raven-tabs-title:after',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-button .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-button .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-button .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-button .raven-tabs-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type!' => 'line',
				],
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-card .raven-tabs-title, {{WRAPPER}} .raven-tabs-button .raven-tabs-title, {{WRAPPER}} .raven-tabs-content, {{WRAPPER}} .raven-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-content, {{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-content-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};border-top-left-radius: unset;border-top-right-radius: unset;',
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-title' => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-top-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-button .raven-tabs-title' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-content, {{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-content-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};border-top-left-radius: unset;border-bottom-left-radius: unset;',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-title' => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-bottom-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-button .raven-tabs-title' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-content, {{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-content-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};border-bottom-right-radius: unset;border-bottom-left-radius: unset;',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-title' => 'border-bottom-right-radius: {{SIZE}}{{UNIT}};border-bottom-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-button .raven-tabs-title' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-content, {{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-content-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};border-top-right-radius: unset;border-bottom-right-radius: unset;',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-title' => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-bottom-right-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-button .raven-tabs-title' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type!' => 'line',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-card .raven-tabs-title, {{WRAPPER}} .raven-tabs-button .raven-tabs-title, {{WRAPPER}} .raven-tabs-mobile-title, {{WRAPPER}} .raven-tabs-content, {{WRAPPER}} .raven-tabs-content-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
	}

	private function register_styles_hover_tab() {
		$this->start_controls_tab(
			'tabs_hover_style',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title:hover:not(.raven-tabs-active.raven-tabs-title:hover)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_hover_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-tabs-title:hover:not(.raven-tabs-active.raven-tabs-title:hover)',
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Icon Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title:hover .raven-tabs-title-icon:not({{WRAPPER}} .raven-tabs-active.raven-tabs-title:hover .raven-tabs-title-icon) i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs-title:hover .raven-tabs-title-icon > svg:not({{WRAPPER}} .raven-tabs-active.raven-tabs-title:hover .raven-tabs-title-icon > svg)' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs-title:hover .raven-tabs-title-icon svg > *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_hover',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title:hover .raven-tabs-title-icon:not({{WRAPPER}} .raven-tabs-active.raven-tabs-title:hover .raven-tabs-title-icon)' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-title:hover .raven-tabs-title-icon > svg:not({{WRAPPER}} .raven-tabs-active.raven-tabs-title:hover .raven-tabs-title-icon > svg)' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_hover',
				'label'     => esc_html__( 'Background Type', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-tabs-title:hover:not(.raven-tabs-active.raven-tabs-title:hover)',
			]
		);

		$this->add_responsive_control(
			'title_padding_hover',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title:hover:not(.raven-tabs-active.raven-tabs-title:hover)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_width_hover',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-active.raven-tabs-title:hover)' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-active.raven-tabs-title:hover)' => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-top-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-button .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-horizontal.raven-tabs-button .raven-tabs-active.raven-tabs-title:hover)' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-active.raven-tabs-title:hover)' => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-bottom-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-button .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-vertical.raven-tabs-button .raven-tabs-active.raven-tabs-title:hover)' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-active.raven-tabs-title:hover)' => 'border-bottom-right-radius: {{SIZE}}{{UNIT}};border-bottom-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-button .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-button .raven-tabs-active.raven-tabs-title:hover)' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-active.raven-tabs-title:hover)' => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-bottom-right-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-button .raven-tabs-title:hover:not({{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-button .raven-tabs-active.raven-tabs-title:hover)' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type!' => 'line',
				],
			]
		);

		$this->add_control(
			'border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title:hover:not({{WRAPPER}}:hover .raven-tabs-active.raven-tabs-title:hover)' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
	}

	private function register_styles_ative_tab() {
		$this->start_controls_tab(
			'tabs_active_style',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_active_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title.raven-tabs-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_active_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-tabs-title.raven-tabs-active',
			]
		);

		$this->add_control(
			'icon_color_active',
			[
				'label' => esc_html__( 'Icon Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-active .raven-tabs-title-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs-active .raven-tabs-title-icon > svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .raven-tabs-active .raven-tabs-title-icon svg > *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_active',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-active .raven-tabs-title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-active .raven-tabs-title-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_active',
				'label'     => esc_html__( 'Background Type', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-tabs-title.raven-tabs-active, {{WRAPPER}} .raven-tabs-active.raven-tabs-title:after',
			]
		);

		$this->add_responsive_control(
			'title_padding_active',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title.raven-tabs-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_width_active',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title.raven-tabs-active, {{WRAPPER}} .raven-tabs-card .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-button .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-card .raven-tabs-content, {{WRAPPER}} .raven-tabs-button .raven-tabs-content, {{WRAPPER}} .raven-tabs-card .raven-tabs-content-wrapper, {{WRAPPER}} .raven-tabs-button .raven-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius_active',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-card .raven-tabs-title.raven-tabs-active' => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-top-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-horizontal.raven-tabs-button .raven-tabs-title.raven-tabs-active' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-card .raven-tabs-title.raven-tabs-active' => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-bottom-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-vertical.raven-tabs-button .raven-tabs-title.raven-tabs-active' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-card .raven-tabs-title.raven-tabs-active' => 'border-bottom-right-radius: {{SIZE}}{{UNIT}};border-bottom-left-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-reversed-horizontal.raven-tabs-button .raven-tabs-title.raven-tabs-active' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-card .raven-tabs-title.raven-tabs-active' => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-bottom-right-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-tabs-reversed-vertical.raven-tabs-button .raven-tabs-title.raven-tabs-active' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type!' => 'line',
				],
			]
		);

		$this->add_control(
			'border_color_active',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title.raven-tabs-active, {{WRAPPER}} .raven-tabs-card .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-button .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-card .raven-tabs-content, {{WRAPPER}} .raven-tabs-button .raven-tabs-content, {{WRAPPER}} .raven-tabs-card .raven-tabs-content-wrapper, {{WRAPPER}} .raven-tabs-button .raven-tabs-content-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_styles',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'right' => esc_html( 'Right', 'jupiterx-core' ),
					'top' => esc_html( 'Top', 'jupiterx-core' ),
				],
				'default' => 'left',
				'selectors_dictionary' => [
					'left' => 'flex-direction: row;justify-content: center;',
					'right' => 'flex-direction: row-reverse;justify-content: center;',
					'top' => 'flex-direction: column;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Icon Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title-icon i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-tabs-title-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description',
			[
				'label' => esc_html__( 'Tab Content', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-tabs-content .is-simple-content:not(.is-template-content)',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_content',
				'label'     => esc_html__( 'Background Type', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-tabs-title:after, {{WRAPPER}} .raven-tabs-content-wrapper',
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$tabs    = $this->get_settings_for_display( 'tabs' );
		$tabs_id = substr( $this->get_id_int(), 0, 3 );

		$this->add_render_attribute( 'tabs', 'class', 'raven-tabs raven-tabs-' . $this->get_settings( 'type' ) . ' raven-tabs-' . $this->get_settings( 'tabs_type' ) );
		?>
		<div class="raven-widget-wrapper">
			<div <?php echo $this->get_render_attribute_string( 'tabs' ); ?>>
				<div class="raven-tabs-list" role="tablist">
					<?php
					$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();

					foreach ( $tabs as $index => $item ) :
						$tab_count             = $index + 1;
						$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

						$tab_icon     = ! empty( $item['tab_icon'] ) ? $item['tab_icon'] : null;
						$tab_icon_new = ! empty( $item['tab_icon_new'] ) ? $item['tab_icon_new'] : null;
						$migrated     = isset( $item['__fa4_migrated']['tab_icon_new'] );
						$is_new       = empty( $item['tab_icon'] ) && $migration_allowed;

						$this->add_render_attribute( $tab_title_setting_key, [
							'id' => 'raven-tabs-title-' . $tabs_id . $tab_count,
							'class' => [ 'raven-tabs-title', 'raven-tabs-desktop-title' ],
							'role' => 'tab',
							'aria-controls' => 'raven-tabs-content-' . $tabs_id . $tab_count,
							'tabindex' => '-1',
							'data-tab' => $tab_count,
						] );

						// Set initial active to avoid jumpy render.
						if ( 1 === $tab_count ) {
							$this->add_render_attribute( $tab_title_setting_key, 'class', 'raven-tabs-active' );
						}

						if ( ! empty( $item['tab_icon'] ) || ! empty( $item['tab_icon_new'] ) ) {
							$this->add_render_attribute( $tab_title_setting_key, 'class', 'raven-tabs-has-icon' );
						}
						?>
						<div <?php echo $this->get_render_attribute_string( $tab_title_setting_key ); ?>>
							<?php $this->render_tab_icon( $tab_icon, $tab_icon_new, $migrated, $is_new ); ?>
							<span class="raven-tabs-title-text"><?php echo $item['tab_title']; ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="raven-tabs-content-wrapper">
						<?php $this->render_tab_content(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_tab_icon( $tab_icon, $tab_icon_new, $migrated, $is_new ) {
		if ( ! empty( $tab_icon ) || ! empty( $tab_icon_new ) ) :
			?>
			<span class="raven-tabs-title-icon">
				<?php
				if ( $is_new || $migrated ) {
					Elementor::$instance->icons_manager->render_icon( $tab_icon_new );
				} else {
					?>
					<i class="<?php echo $tab_icon; ?>" aria-hidden="true"></i>
				<?php } ?>
			</span>
			<?php
		endif;
	}

	/**
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	protected function render_tab_content() {
		$tabs     = $this->get_settings_for_display( 'tabs' );
		$tabs_id  = substr( $this->get_id_int(), 0, 3 );
		$settings = $this->get_settings_for_display();

		$migration_allowed    = Elementor::$instance->icons_manager->is_migration_allowed();
		$tabs_animation_name  = $this->get_settings_for_display( 'tabs_pane_animation' );
		$tabs_animation_class = 'raven-animations-' . $tabs_animation_name;
		$use_ajax_loading     = $this->get_settings_for_display( 'use_ajax_loading' );

		foreach ( $tabs as $index => $item ) :
			$tab_count                    = $index + 1;
			$tab_content_setting_key      = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
			$tab_title_mobile_setting_key = ! empty( $settings['mobile_stacking'] ) ? $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $tab_count ) : '';

			$tab_icon            = ! empty( $item['tab_icon'] ) ? $item['tab_icon'] : null;
			$tab_icon_new        = ! empty( $item['tab_icon_new'] ) ? $item['tab_icon_new'] : null;
			$migrated            = isset( $item['__fa4_migrated']['tab_icon_new'] );
			$is_new              = empty( $item['tab_icon'] ) && $migration_allowed;
			$template_id         = $item['tab_custom_template'];
			$tabs_css_id         = $item['tab_css_id'];
			$is_tempalte_content = ' is-simple-content';

			if ( ! empty( $template_id ) ) {
				$is_tempalte_content = ' is-template-content';
			}

			if ( ! empty( $tabs_css_id ) ) {
				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => $tabs_css_id,
					'class' => [ 'raven-tabs-content', 'elementor-clearfix' ],
					'role' => 'tabpanel',
					'aria-labelledby' => 'raven-tabs-title-' . $tabs_id . $tab_count,
					'data-tab' => $tab_count,
				] );
			} else {
				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'raven-tabs-content-' . $tabs_id . $tab_count,
					'class' => [ 'raven-tabs-content', 'elementor-clearfix' ],
					'role' => 'tabpanel',
					'aria-labelledby' => 'raven-tabs-title-' . $tabs_id . $tab_count,
					'data-tab' => $tab_count,
				] );
			}

			if ( ! empty( $settings['mobile_stacking'] ) ) {
				$this->add_render_attribute( $tab_title_mobile_setting_key, [
					'class' => [ 'raven-tabs-title', 'raven-tabs-mobile-title' ],
					'role' => 'tab',
					'tabindex' => '-1',
					'data-tab' => $tab_count,
				] );
			}

			// Set initial active to avoid jumpy render.
			if ( 1 === $tab_count ) {
				$this->add_render_attribute( $tab_content_setting_key, 'class', 'raven-tabs-active' );

				if ( ! empty( $settings['mobile_stacking'] ) ) {
					$this->add_render_attribute( $tab_title_mobile_setting_key, 'class', 'raven-tabs-active' );
				}
			}

			if ( ! empty( $settings['mobile_stacking'] ) && ( ! empty( $item['tab_icon'] ) || ! empty( $item['tab_icon_new'] ) ) ) {
				$this->add_render_attribute( $tab_title_mobile_setting_key, 'class', 'raven-tabs-has-icon' );
			}

			$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
			?>

			<?php if ( $settings['mobile_stacking'] ) : ?>
			<div <?php echo $this->get_render_attribute_string( $tab_title_mobile_setting_key ); ?>>
				<?php $this->render_tab_icon( $tab_icon, $tab_icon_new, $migrated, $is_new ); ?>
				<span class="raven-tabs-title-text"><?php echo $item['tab_title']; ?></span>
			</div>
			<?php endif; ?>
			<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
				<div class="<?php echo esc_attr( $tabs_animation_class . $is_tempalte_content ); ?>">
				<?php if ( ! empty( $item['tab_content'] ) ) {
					echo $this->parse_text_editor( $item['tab_content'] );
				}

				if ( empty( $item['tab_content'] ) && ! empty( $template_id ) && empty( $use_ajax_loading ) ) {
					echo do_shortcode( sprintf( '[elementor-template id="%s"]', $template_id ) );

				}

				if ( empty( $item['tab_content'] ) && ! empty( $template_id ) && 'yes' === $use_ajax_loading ) {
					$template_content = do_shortcode( sprintf( '[elementor-template id="%s"]', $template_id ) );

					if ( 1 < $tab_count ) {
						$template_content = '<div class="raven-ajax-content-template" data-id="' . $template_id . '"></div>';
					}
					echo $template_content;
				}
				?>
				</div>
			</div>
			<?php
		endforeach;
	}
}


