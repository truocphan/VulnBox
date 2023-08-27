<?php

namespace JupiterX_Core\Raven\Modules\Animated_Gradient;

use JupiterX_Core\Raven\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Controls_Stack;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/element/container/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/element/column/section_style/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/element/raven-button/section_content_style/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/element/raven-heading/section_heading/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/element/raven-text-marquee/text_style_section/after_section_end', [ $this, 'register_controls' ], 10 );

		add_action( 'elementor/frontend/section/before_render', [ $this, 'before_render' ] );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'before_render' ] );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_render' ] );

		add_action( 'elementor/section/print_template', [ $this, 'print_template' ], 10, 2 );
		add_action( 'elementor/container/print_template', [ $this, 'print_template' ], 10, 2 );
		add_action( 'elementor/column/print_template', [ $this, 'print_template' ], 10, 2 );
	}

	public function register_controls( Controls_Stack $controls_stack ) {
		$controls_stack->start_controls_section(
			'section_raven_animated_gradient',
			[
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => esc_html__( 'Animated Gradient', 'jupiterx-core' ),
			]
		);

		$controls_stack->add_control(
			'raven_animated_gradient_enable',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Animated Gradient', 'jupiterx-core' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'prefix_class' => 'raven-animated-gradient-',
				'render_type'  => 'template',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'raven_animated_gradient_color',
			[
				'label' => esc_html__( 'Add Color', 'jupiterx-core' ),
				'type'  => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
			]
		);

		$controls_stack->add_control(
			'raven_animated_gradient_color_list',
			[
				'label'       => esc_html__( 'Color', 'jupiterx-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => 'Color {{{raven_animated_gradient_color}}}',
				'show_label'  => true,
				'default'     => [
					[
						'raven_animated_gradient_color' => '#F6AD1F',
					],
					[
						'raven_animated_gradient_color' => '#F7496A',
					],
					[
						'raven_animated_gradient_color' => '#565AD8',
					],
				],
				'condition'   => [
					'raven_animated_gradient_enable' => 'yes',
				],
			]
		);

		$controls_stack->add_control(
			'raven_animated_gradient_direction',
			[
				'label'      => esc_html__( 'Direction', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
					'up' => esc_html__( 'Up', 'jupiterx-core' ),
					'down' => esc_html__( 'Down', 'jupiterx-core' ),
				],
				'default'    => 'left',
				'render_type' => 'template',
				'condition'  => [
					'raven_animated_gradient_enable' => 'yes',
				],
			]
		);

		$controls_stack->add_control(
			'raven_animated_gradient_speed',
			[
				'label'      => esc_html__( 'Animation Duration', 'jupiterx-core' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 3,
				],
				'render_type' => 'template',
				'selectors'  => [
					'{{WRAPPER}} .box' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'raven_animated_gradient_enable' => 'yes',
				],
			]
		);

		$controls_stack->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings();

		if ( 'yes' === $settings['raven_animated_gradient_enable'] ) {
			$direction           = $settings['raven_animated_gradient_direction'];
			$speed               = $settings['raven_animated_gradient_speed']['size'];
			$gradient_color_list = $settings['raven_animated_gradient_color_list'];

			$element->add_render_attribute( '_wrapper', 'data-speed', $speed . 's' );

			$animated_gradient_attributes = Utils::get_animated_gradient_attributes( $direction, $gradient_color_list );

			$element->add_render_attribute( '_wrapper', 'data-background-size', $animated_gradient_attributes['data_background_size'] );
			$element->add_render_attribute( '_wrapper', 'data-animation-name', $animated_gradient_attributes['data_animation_name'] );
			$element->add_render_attribute( '_wrapper', 'data-angle', $animated_gradient_attributes['angle'] );

			foreach ( $gradient_color_list as $gradient_color ) {
				$color[] = $gradient_color['raven_animated_gradient_color'];
			}

			array_push( $color, $gradient_color_list[0]['raven_animated_gradient_color'], $gradient_color_list[1]['raven_animated_gradient_color'] );

			$colors = implode( ',', $color );

			$element->add_render_attribute( '_wrapper', 'data-color', $colors );
		}
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function print_template( $template, $widget ) {
		if ( ! $template ) {
			return;
		}

		ob_start();

		$old_template = $template;
		?>
		<# if ( 'yes' === settings.raven_animated_gradient_enable ) {

			color_list = settings.raven_animated_gradient_color_list;
			direction = settings.raven_animated_gradient_direction;
			speed = settings.raven_animated_gradient_speed.size + 's';

			background = '';
			animation  = '';
			angle     = '';

			background_size_color_count = ( color_list.length + 1 ) * 100;

			if ( 'left' === direction ) {
				background = background_size_color_count + '%' + ' 100%';
				animation  = 'AnimatedGradientBgLeft';
				angle      = '90deg';
			} else if ( 'right' == direction ) {
				background = background_size_color_count + '%' + ' 100%';
				animation  = 'AnimatedGradientBgRight';
				angle      = '90deg';
			} else if ( 'up' == direction ) {
				background = '100% ' + background_size_color_count + '%';
				animation  = 'AnimatedGradientBgUp';
				angle      = '0deg';
			} else if ( 'down' == direction ) {
				background = '100% ' + background_size_color_count + '%';
				animation  = 'AnimatedGradientBgDown';
				angle      = '0deg';
			}

			view.addRenderAttribute('_wrapper', 'data-background-size', background );
			view.addRenderAttribute('_wrapper', 'data-animation-name', animation );
			view.addRenderAttribute('_wrapper', 'data-angle', angle );

			var color = [];
			var i = 0;
			_.each(color_list , function(color_list){
					color[i] = color_list.raven_animated_gradient_color;
					i = i+1;
			});

			color.push( color_list[0].raven_animated_gradient_color, color_list[1].raven_animated_gradient_color );

			color = color.join();

			view.addRenderAttribute('_wrapper', 'data-color', color);

			var gradientColorEditor = 'linear-gradient( ' + angle + ',' + color + ' )';
			var animationSpeedEditor = 'animation-duration: ' + speed;
			var backgroundSize = '';
			var animationName = '';

			if ( background ) {
				backgroundSize = 'background-size: ' + background;
			}

			if ( animation ) {
				animationName = 'animation-name: ' + animation;
			}
			#>
			<div class="raven-animated-gradient" data-angle="{{{ angle }}}" data-speed="{{{ speed }}}" data-color="{{{ color }}}" style="background-image : {{{ gradientColorEditor }}}; {{{ animationSpeedEditor }}}; {{{ backgroundSize }}}; {{{ animationName }}};"></div>
		<# } #>
		<?php
		$animated_gradient_content = ob_get_contents();

		ob_end_clean();

		$template = $animated_gradient_content . $old_template;

		return $template;
	}
}
