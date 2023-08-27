<?php
namespace JupiterX_Core\Raven\Modules\Marquee\Widgets;

defined( 'ABSPATH' ) || die();

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use JupiterX_Core\Raven\Modules\Marquee\Classes\Marquee;
use JupiterX_Core\Raven\Plugin as RavenPlugin;

class Text extends Marquee {
	public function get_name() {
		return 'raven-text-marquee';
	}

	public static function is_active() {
		return RavenPlugin::is_active( 'text-marquee' );
	}

	public function get_title() {
		return esc_html__( 'Text Marquee', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-text-marquee';
	}

	protected function register_controls() {
		$this->register_content_settings();
		$this->register_text_style_settings();

		parent::register_controls();
	}

	protected function register_content_settings() {
		$this->start_controls_section(
			'general_section',
			[
				'label' => esc_html__( 'General', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'text',
			]
		);

		$this->add_control(
			'content_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => esc_html__( 'Dream and Create', 'jupiterx-core' ),
					],
					[
						'text' => esc_html__( 'Stunning Websites', 'jupiterx-core' ),
					],
					[
						'text' => esc_html__( 'Without Limits', 'jupiterx-core' ),
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->add_control(
			'divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->register_marquee_content_controls( 'text' );

		$this->end_controls_section();
	}

	protected function register_text_style_settings() {
		$this->start_controls_section(
			'text_style_section',
			[
				'label' => esc_html__( 'Text', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->add_control(
			'text_outline',
			[
				'label' => esc_html__( 'Outline Text', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => '-webkit-text-fill-color: transparent;',
				],
				'render_type' => 'template',
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'text_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-marquee-item:not(.raven-animated-gradient)',
				'condition' => [
					'text_outline' => '',
				],
			]
		);

		/**
		 * Use HIDDEN control to hack style.
		 */
		$this->add_control(
			'text_color_styles',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'styles',
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:not(.raven-animated-gradient)' => '-webkit-background-clip: text; background-clip: text; color: transparent;',
				],
				'condition' => [
					'text_color_background' => 'gradient',
					'text_outline' => '',
				],
			]
		);

		$this->add_control(
			'text_stroke_width',
			[
				'label' => esc_html__( 'Stroke Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'text_outline!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; stroke-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_stroke_color',
			[
				'label' => esc_html__( 'Stroke Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E9E9E9',
				'condition' => [
					'text_outline!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => '-webkit-text-stroke-color: {{VALUE}}; stroke: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => esc_html__( 'Background Type', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_shadow_normal',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = $settings['content_list'];

		$this->add_marquee_render_attribute();

		$content = $this->render_marquee_content( $items );
		?>
		<div <?php echo $this->get_render_attribute_string( 'content-container' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
				<?php $this->handle_left_gradient_overlay(); ?>
				<div <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php echo $content; ?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'duplicated-content-wrapper' ); ?>>
					<?php echo $content; ?>
				</div>
				<?php $this->handle_right_gradient_overlay(); ?>
			</div>
		</div>
		<?php
	}
}
