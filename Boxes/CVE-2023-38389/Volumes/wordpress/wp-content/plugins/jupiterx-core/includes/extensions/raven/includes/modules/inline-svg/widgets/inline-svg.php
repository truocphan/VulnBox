<?php

namespace JupiterX_Core\Raven\Modules\Inline_Svg\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Controls_Manager;

/**
 * Inline SVG.
 *
 * @since 2.5.9
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Inline_Svg extends Base_Widget {
	public function get_name() {
		return 'raven-inline-svg';
	}

	public function get_title() {
		return esc_html__( 'Inline SVG', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-inline-svg';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'SVG', 'jupiterx-core' ),
			]
		);

		$this->content_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'svg_style',
			[
				'label' => esc_html__( 'SVG', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->style_controls();

		$this->end_controls_section();
	}

	private function content_controls() {
		$this->add_control(
			'svg',
			[
				'label' => esc_html__( 'SVG', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'svg' ],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'URL', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Paste URL or type', 'jupiterx-core' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
	}

	private function style_controls() {
		$this->add_control(
			'custom_width',
			[
				'label' => esc_html__( 'Use Custom Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => esc_html__( 'Makes SVG responsive and allows to change its width.', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label' => esc_html__( 'Use Aspect Ratio', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'custom_width' => 'yes',
				],
				'description' => esc_html__( 'This option allows your SVG item to be scaled up exactly as your bitmap image, at the same time saving its width compared to the height.', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-inline-svg-wrapper svg' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_width' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-inline-svg-wrapper svg' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_width' => 'yes',
					'aspect_ratio!' => 'yes',
				],
			]
		);

		$this->add_control(
			'remove_inline_css',
			[
				'label' => esc_html__( 'Remove Inline CSS', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => esc_html__( 'Use this option to delete the inline styles in the loaded SVG.', 'jupiterx-core' ),
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
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-inline-svg-wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	/**
	 * Prepare link attributes.
	 *
	 * @param array $settings widget settings.
	 * @since 2.5.9
	 */
	private function manage_link( $settings ) {
		if ( method_exists( $this, 'add_link_attributes' ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		} else {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( 'on' === $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( 'on' === $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}

		$this->link_open  = '<a ' . $this->get_render_attribute_string( 'link' ) . ' >';
		$this->link_close = '</a>';
	}

	protected function render() {
		$settings         = $this->get_settings_for_display();
		$svg              = $settings['svg'];
		$this->link_open  = '';
		$this->link_close = '';

		if ( empty( $svg['url'] ) ) {
			return;
		}

		$svg     = file_get_contents( $svg['url'] ); //phpcs:ignore
		$classes = [ 'svg-wrapper' ];

		if ( isset( $settings['aspect_ratio'] ) && 'yes' === $settings['aspect_ratio'] ) {
			$classes[] = 'jupiterx-svg-with-auto-aspect';
		}

		if ( ! isset( $settings['aspect_ratio'] ) || 'yes' !== $settings['aspect_ratio'] ) {
			$svg = str_replace( '<svg', '<svg preserveAspectRatio="none" ', $svg );
		}

		if ( isset( $settings['remove_inline_css'] ) && 'yes' === $settings['remove_inline_css'] ) {
			$svg = preg_replace( '[style\s*?=\s*?"\s*?.*?\s*?"]', '', $svg );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->manage_link( $settings );
		}

		// Check if custom-width is not set and SVG has no width, we set a default width for it.
		if ( ! isset( $settings['custom_width'] ) || 'yes' !== $settings['custom_width'] ) {
			$classes = $this->check_svg_width( $svg, $classes );
		}

		$this->add_render_attribute(
			'inner_wrapper',
			[
				'class' => $classes,
			]
		);

		?>
			<div class="jupiterx-inline-svg-wrapper" id="jupiterx-inline-svg-wrapper">
				<?php echo $this->link_open; ?>
				<div <?php echo $this->get_render_attribute_string( 'inner_wrapper' ); ?>>
					<?php echo $svg; ?>
				</div>
				<?php echo $this->link_close; ?>
			</div>
		<?php
	}

	/**
	 * Checks if svg tag has a default width attribute.
	 *
	 * @param string $svg SVG html.
	 * @param array  $classes array of classes.
	 * @since 2.5.9
	 */
	private function check_svg_width( $svg, $classes ) {
		$svg_       = simplexml_load_string( $svg );
		$attributes = $svg_->attributes();

		if ( empty( (string) $attributes->width ) ) {
			$classes[] = 'jupiterx-inline-svg-default-svg-width';
		}

		return $classes;
	}
}
