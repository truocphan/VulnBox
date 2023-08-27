<?php

namespace JupiterX_Core\Raven\Modules\Tooltip;

use JupiterX_Core\Raven\Base\Module_Base;

defined( 'ABSPATH' ) || die();

class module extends Module_Base {
	/**
	 * Widgets Data.
	 *
	 * @var array
	 */
	public $widgets_data = [];
	/**
	 * Defaults Data.
	 *
	 * @var array
	 */
	public $default_widget_settings = [
		'jupiter_widget_tooltip' => 'false',
		'jupiter_widget_tooltip_description' => 'This is Tooltip!',
		'jupiter_widget_tooltip_placement' => 'top',
		'jupiter_widget_tooltip_arrow' => true,
		'jupiter_widget_tooltip_x_offset' => 0,
		'jupiter_widget_tooltip_y_offset' => 0,
		'jupiter_widget_tooltip_animation' => 'fade',
		'jupiter_widget_tooltip_trigger' => 'mouseenter',
		'jupiter_widget_tooltip_z_index' => 999,
		'jupiter_widget_tooltip_custom_selector' => '',
		'jupiter_widget_tooltip_delay' => 0,
	];

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/element/common/_section_style/after_section_end', [
			$this,
			'after_common_section_responsive',
		], 10, 1 );

		add_action( 'elementor/frontend/widget/before_render', [ $this, 'widget_before_render' ] );

		add_filter( 'elementor/widget/render_content', [ $this, 'widget_before_render_content' ], 10, 2 );
	}

	/**
	 * After section_layout callback.
	 *
	 * @param object $obj
	 *
	 * @return void
	 */
	public function after_common_section_responsive( $obj ) {

		$obj->start_controls_section(
			'jupiterx_tooltip_section',
			[
				'label' => esc_html__( 'Tooltip', 'jupiterx-core' ),
				'tab' => 'advanced',
			]
		);

		$this->register_tooltip_ext_settings( $obj );

		$obj->end_controls_section();
	}

	/**
	 * Register Tooltip Extension Settings.
	 *
	 * @param  $obj [description]
	 *
	 * @return void [description]
	 */
	public function register_tooltip_ext_settings( $obj ) {

		$obj->add_control(
			'jupiter_widget_tooltip',
			[
				'label' => esc_html__( 'Show Tooltip', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'true',
				'default' => 'false',
				'render_type' => 'template',
			]
		);

		$obj->start_controls_tabs( 'jupiter_widget_tooltip_tabs' );

		$obj->start_controls_tab(
			'jupiter_widget_tooltip_settings_tab',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$this->settings_tab_controls( $obj );

		$obj->end_controls_tab();

		$obj->start_controls_tab(
			'jupiter_widget_tooltip_styles_tab',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$this->style_tab_controls( $obj );

		$obj->end_controls_tab();

		$obj->end_controls_tabs();
	}

	/**
	 * @param $obj
	 *
	 * @return void
	 */
	public function settings_tab_controls( $obj ) {
		$obj->add_control(
			'jupiter_widget_tooltip_description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'wysiwyg',
				'render_type' => 'template',
				'default' => esc_html__( 'This is Tooltip!', 'jupiterx-core' ),
				'dynamic' => [ 'active' => true ],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_placement',
			[
				'label' => esc_html__( 'Placement', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'top',
				'options' => [
					'top-start' => esc_html__( 'Top Start', 'jupiterx-core' ),
					'top' => esc_html__( 'Top', 'jupiterx-core' ),
					'top-end' => esc_html__( 'Top End', 'jupiterx-core' ),
					'right-start' => esc_html__( 'Right Start', 'jupiterx-core' ),
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
					'right-end' => esc_html__( 'Right End', 'jupiterx-core' ),
					'bottom-start' => esc_html__( 'Bottom Start', 'jupiterx-core' ),
					'bottom' => esc_html__( 'Bottom', 'jupiterx-core' ),
					'bottom-end' => esc_html__( 'Bottom End', 'jupiterx-core' ),
					'left-start' => esc_html__( 'Left Start', 'jupiterx-core' ),
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'left-end' => esc_html__( 'Left End', 'jupiterx-core' ),
				],
				'render_type' => 'template',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_arrow',
			[
				'label' => esc_html__( 'Use Arrow?', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_animation',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'jupiterx-core' ),
					'shift-away' => esc_html__( 'Shift-Away', 'jupiterx-core' ),
					'shift-toward' => esc_html__( 'Shift-Toward', 'jupiterx-core' ),
					'scale' => esc_html__( 'Scale', 'jupiterx-core' ),
					'perspective' => esc_html__( 'Perspective', 'jupiterx-core' ),
				],
				'render_type' => 'template',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_trigger',
			[
				'label' => esc_html__( 'Trigger', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'mouseenter',
				'options' => [
					'mouseenter' => esc_html__( 'Mouse Enter', 'jupiterx-core' ),
					'click' => esc_html__( 'Click', 'jupiterx-core' ),
					'focus' => esc_html__( 'Focus', 'jupiterx-core' ),
					'mouseenter click' => esc_html__( 'Mouse Enter + Click', 'jupiterx-core' ),
					'mouseenter focus' => esc_html__( 'Mouse Enter + Focus', 'jupiterx-core' ),
				],
				'render_type' => 'template',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_delay',
			[
				'label' => esc_html__( 'Delay', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 100,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_x_offset',
			[
				'label' => esc_html__( 'Offset', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
				'min' => - 1000,
				'max' => 1000,
				'step' => 1,
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_y_offset',
			[
				'label' => esc_html__( 'Distance', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
				'min' => - 1000,
				'max' => 1000,
				'step' => 1,
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_z_index',
			[
				'label' => esc_html__( 'Z-Index', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 999,
				'min' => 0,
				'max' => 999,
				'step' => 1,
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_custom_selector',
			[
				'label' => esc_html__( 'Custom Selector', 'jupiterx-core' ),
				'type' => 'text',
				'default' => '',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);
	}

	/**
	 * @param $obj
	 *
	 * @return void
	 */
	public function style_tab_controls( $obj ) {
		$obj->add_responsive_control(
			'jupiter_widget_tooltip_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [
					'px',
					'em',
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > [data-tippy-root] .tippy-box' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
				'render_type' => 'template',
			]
		);

		$obj->add_group_control(
			'typography',
			[
				'name' => 'jupiter_widget_tooltip_typography',
				'selector' => '{{WRAPPER}} > [data-tippy-root] .tippy-box .tippy-content',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} > [data-tippy-root] .tippy-box .tippy-content' => 'color: {{VALUE}}',
				],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_text_align',
			[
				'label' => esc_html__( 'Text Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} > [data-tippy-root] .tippy-box .tippy-content' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
				'classes' => 'jupiterx-core-text-align-control',
			]
		);

		$obj->add_group_control(
			'background',
			[
				'name' => 'jupiter_widget_tooltip_background',
				'selector' => '{{WRAPPER}} > [data-tippy-root] .tippy-box',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_control(
			'jupiter_widget_tooltip_arrow_color',
			[
				'label' => esc_html__( 'Arrow Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} > [data-tippy-root] .tippy-box[data-placement^=left] .tippy-arrow:before' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} > [data-tippy-root] .tippy-box[data-placement^=right] .tippy-arrow:before' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} > [data-tippy-root] .tippy-box[data-placement^=top] .tippy-arrow:before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} > [data-tippy-root] .tippy-box[data-placement^=bottom] .tippy-arrow:before' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_responsive_control(
			'jupiter_widget_tooltip_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} > [data-tippy-root] .tippy-box .tippy-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_group_control(
			'border',
			[
				'name' => 'jupiter_widget_tooltip_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} > [data-tippy-root] .tippy-box',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_responsive_control(
			'jupiter_widget_tooltip_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} > [data-tippy-root] .tippy-box ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);

		$obj->add_group_control(
			'box-shadow',
			[
				'name' => 'jupiter_widget_tooltip_box_shadow',
				'selector' => '{{WRAPPER}} > [data-tippy-root] .tippy-box',
				'condition' => [
					'jupiter_widget_tooltip' => 'true',
				],
			]
		);
	}

	/**
	 * Widget before render.
	 *
	 * @param  $widget
	 *
	 * @return void
	 */
	public function widget_before_render( $widget ) {
		$data     = $widget->get_data();
		$settings = $data['settings'];

		$settings = wp_parse_args( $settings, $this->default_widget_settings );

		$widget_settings = [];

		static $enqueue_tooltip_scripts = false;

		$widget_settings['tooltip']            = $settings['jupiter_widget_tooltip'];
		$widget_settings['tooltipDescription'] = $settings['jupiter_widget_tooltip_description'];
		$widget_settings['tooltipPlacement']   = $settings['jupiter_widget_tooltip_placement'];
		$widget_settings['tooltipArrow']       = $settings['jupiter_widget_tooltip_arrow'];
		$widget_settings['xOffset']            = $settings['jupiter_widget_tooltip_x_offset'];
		$widget_settings['yOffset']            = $settings['jupiter_widget_tooltip_y_offset'];
		$widget_settings['tooltipAnimation']   = $settings['jupiter_widget_tooltip_animation'];
		$widget_settings['tooltipTrigger']     = $settings['jupiter_widget_tooltip_trigger'];
		$widget_settings['zIndex']             = $settings['jupiter_widget_tooltip_z_index'];
		$widget_settings['customSelector']     = $settings['jupiter_widget_tooltip_custom_selector'];
		$widget_settings['delay']              = $settings['jupiter_widget_tooltip_delay'];

		if ( 'false' === $settings['jupiter_widget_tooltip'] ) {
			return;
		}

		$widget->add_render_attribute( '_wrapper', [
			'class' => 'jupiter-tooltip-widget',
		] );

		$this->tooltip_widgets[] = $data['id'];

		if ( ! $enqueue_tooltip_scripts ) {
			wp_enqueue_script( 'jupiterx-core-tippy-bundle' );
			$enqueue_tooltip_scripts = true;
		}

		if ( ! empty( $widget_settings ) ) {
			$widget->add_render_attribute( '_wrapper', [
				'data-jupiter-tooltip-settings' => wp_json_encode( $widget_settings ),
			] );
		}

		$this->widgets_data[ $data['id'] ] = $widget_settings;
	}

	/**
	 * Callback function for widget before render content.
	 *
	 * @param $widget_content
	 * @param $widget
	 *
	 * @return mixed
	 */
	public function widget_before_render_content( $widget_content, $widget ) {
		$data     = $widget->get_data();
		$settings = $widget->get_settings_for_display();

		$settings = wp_parse_args( $settings, $this->default_widget_settings );

		if ( 'true' === $settings['jupiter_widget_tooltip'] && ! empty( $settings['jupiter_widget_tooltip_description'] ) ) {
			$tooltip_html = sprintf(
				'<div id="jupiter-tooltip-content-%1$s" class="jupiter-tooltip-widget__content">%2$s</div>',
				$data['id'],
				$settings['jupiter_widget_tooltip_description']
			);

			echo wp_kses_post( $tooltip_html );
		}

		return $widget_content;
	}
}
