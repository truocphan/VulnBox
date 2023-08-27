<?php
namespace JupiterX_Core\Raven\Modules\Progress_Tracker\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Progress_Tracker extends Base_Widget {
	public function get_name() {
		return 'raven-progress-tracker';
	}

	public function get_title() {
		return esc_html__( 'Progress Tracker', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-progress-tracker';
	}

	public function get_script_depends() {
		return [ 'jupiterx-core-raven-circular-progress' ];
	}

	protected function register_controls() {
		$this->register_content();
		$this->register_tracker_style();
		$this->register_content_style();
	}

	private function register_content() {
		$this->start_controls_section(
			'section_content_scrolling_tracker',
			[
				'label' => esc_html__( 'Progress Tracker', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Tracker Type', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'jupiterx-core' ),
					'circular' => esc_html__( 'Circular', 'jupiterx-core' ),
				],
				'default' => 'horizontal',
			]
		);

		$this->add_control(
			'relative_to',
			[
				'label' => esc_html__( 'Progress relative to', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'entire_page' => esc_html__( 'Entire Page', 'jupiterx-core' ),
					'post_content' => esc_html__( 'Post Content', 'jupiterx-core' ),
					'selector' => esc_html__( 'Selector', 'jupiterx-core' ),
				],
				'default' => 'entire_page',
			]
		);

		$this->add_control(
			'selector',
			[
				'label' => esc_html__( 'Selector', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add the CSS ID or Class of a specific element on this page to track its progress separately', 'jupiterx-core' ),
				'frontend_available' => true,
				'condition' => [
					'relative_to' => 'selector',
				],
				'placeholder' => '#id, .class',
			]
		);

		$this->add_control(
			'relative_to_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Note: You can only track progress relative to Post Content on a single post template.', 'jupiterx-core' ),
				'separator' => 'none',
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'relative_to' => 'post_content',
				],
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'rtl' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'ltr',
				'selectors' => [
					'{{WRAPPER}}' => '--horizontal-progress-direction: {{VALUE}};',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'percentage',
			[
				'label' => esc_html__( 'Percentage', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'default' => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'percentage_position',
			[
				'label' => esc_html__( 'Percentage Position', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'rtl' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'type' => 'horizontal',
					'percentage' => 'yes',
				],
				'default' => 'ltr',
				'selectors' => [
					'{{WRAPPER}}' => '--percentage-position: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_tracker_style() {
		$this->register_circular_style();
		$this->register_horizontal_style();
	}

	private function register_circular_style() {
		$this->start_controls_section(
			'section_style_scrolling_tracker',
			[
				'label' => esc_html__( 'Tracker', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'circular_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--circular-width: {{SIZE}}{{UNIT}}; --circular-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'circular',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'heading_progress_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Progress Indicator', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'circular_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#61ce70',
				'selectors' => [
					'{{WRAPPER}}' => '--circular-color: {{VALUE}}',
				],
				'condition' => [
					'type' => 'circular',
				],
			]
		);

		$this->add_responsive_control(
			'circular_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--circular-progress-width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'condition' => [
					'type' => 'circular',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'condition' => [
					'type' => 'circular',
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}}' => '--svg-wrapper-justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_tracker_background_style_circular',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Tracker Background', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'type' => 'circular',
				],
			]
		);

		$this->add_control(
			'circular_background_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--circular-background-color: {{VALUE}}',
				],
				'default' => '#eee',
				'condition' => [
					'type' => 'circular',
				],
			]
		);

		$this->add_responsive_control(
			'circular_background_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--circular-background-width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'condition' => [
					'type' => 'circular',
				],
			]
		);
	}

	private function register_horizontal_style() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'horizontal_color',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .progress-indicator',
				'condition' => [
					'type' => 'horizontal',
				],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Progress Color', 'jupiterx-core' ),
					],
				],
			]
		);

		$this->add_control(
			'horizontal_border_style',
			[
				'label' => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html_x( 'None', 'jupiterx-core' ),
					'solid' => esc_html_x( 'Solid', 'Border Control', 'jupiterx-core' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'jupiterx-core' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'jupiterx-core' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'jupiterx-core' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .progress-indicator' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_border_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .progress-indicator' => 'border-top-width: {{TOP}}{{UNIT}}; border-right-width: {{RIGHT}}{{UNIT}}; border-bottom-width: {{BOTTOM}}{{UNIT}}; border-left-width: {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'horizontal_border_style!' => 'none',
					'type' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'horizontal_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progress-indicator' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'horizontal_border_style!' => 'none',
					'type' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}' => '--horizontal-indicator-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'heading_tracker_background_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Tracker Background', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'horizontal_background_color',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
					],
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'horizontal_tracker_border_style',
			[
				'label' => esc_html__( 'Border Type', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'solid' => _x( 'Solid', 'Border Control', 'jupiterx-core' ),
					'double' => _x( 'Double', 'Border Control', 'jupiterx-core' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'jupiterx-core' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'jupiterx-core' ),
					'groove' => _x( 'Groove', 'Border Control', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_tracker_border_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper' => 'border-top-width: {{TOP}}{{UNIT}}; border-right-width: {{RIGHT}}{{UNIT}}; border-bottom-width: {{BOTTOM}}{{UNIT}}; border-left-width: {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'horizontal_tracker_border_style!' => 'none',
					'type' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'horizontal_tracker_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'horizontal_tracker_border_style!' => 'none',
					'type' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_tracker_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper',
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-progress-tracker-horizontal-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_content_style() {
		$this->start_controls_section(
			'section__content_style_scrolling_tracker',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'percentage' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_percentage_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Percentage', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'percentage_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--progress-percentage-color: {{VALUE}}',
				],
				'default' => '#212529',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'percentage_typography',
				'selector' => '{{WRAPPER}} svg > text, {{WRAPPER}} .percentage-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'exclude' => [
					'line_height',
					'word_spacing',
					'text_transform',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'percentage_text_shadow',
				'selector' => '{{WRAPPER}} svg > text, {{WRAPPER}} .percentage-text',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => esc_html__( 'Text Shadow', 'jupiterx-core' ),
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
			<div class="jupiterx-progress-tracker-widget">
				<?php if ( 'circular' === $settings['type'] ) : ?>
					<?php $this->progress_tracker_circle(); ?>
				<?php else : ?>
					<?php $this->progress_tracker_horizontal( $settings ); ?>
				<?php endif; ?>
			</div>
		<?php
	}

	private function progress_tracker_circle() {
		$class = 'jupiterx-progress-tracker-circular-' . $this->get_id();
		?>
			<div class="<?php echo esc_attr( $class ); ?>" data-pie='{ "percent": 0 }'></div>
		<?php
	}

	private function progress_tracker_horizontal() {
		$classes = [
			'jupiterx-progress-tracker-horizontal-wrapper',
			'jupiterx-progress-tracker-horizontal-' . $this->get_id(),
		];

		$this->add_render_attribute( 'bar', 'class', $classes );
		?>
			<div <?php echo $this->get_render_attribute_string( 'bar' ); ?>>
				<div class="progress-indicator" >
					<span class="percentage-text"></span>
				</div>
			</div>
		<?php
	}
}
