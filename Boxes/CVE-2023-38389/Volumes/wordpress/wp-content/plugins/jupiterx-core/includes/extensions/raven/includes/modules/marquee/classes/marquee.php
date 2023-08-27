<?php
namespace JupiterX_Core\Raven\Modules\Marquee\Classes;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;
use JupiterX_Core\Raven\Utils;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class Marquee extends Base_Widget {
	public function get_group_name() {
		return 'marquee';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-elements' ];
	}

	protected function register_controls() {
		$this->register_marquee_gradient_overlay_controls();
	}

	protected function register_marquee_content_controls( $type ) {
		$separator = 'text' !== $type ? 'before' : 'none';
		$classes   = 'text' !== $type ? '' : 'elementor-control-type-hidden';

		$this->add_control(
			'orientation',
			[
				'label' => esc_html__( 'Orientation', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'jupiterx-core' ),
					'horizontal' => esc_html__( 'Horizontal', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-marquee-',
				'classes' => $classes,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'vertical_direction',
			[
				'label' => esc_html__( 'Direction', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'bottom' => esc_html__( 'Bottom', 'jupiterx-core' ),
					'top' => esc_html__( 'Top', 'jupiterx-core' ),
				],
				'condition' => [
					'orientation' => 'vertical',
				],
				'prefix_class' => 'raven-marquee-direction-',
			]
		);

		$this->add_control(
			'horizontal_direction',
			[
				'label' => esc_html__( 'Direction', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
				],
				'condition' => [
					'orientation' => 'horizontal',
				],
				'prefix_class' => 'raven-marquee-direction-',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-marquee-horizontal .raven-marquee-item' => 'margin:0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-marquee-vertical .raven-marquee-item' => 'margin:{{SIZE}}{{UNIT}} 0;',
				],
				'separator' => $separator,
			]
		);

		$this->add_responsive_control(
			'animation_speed',
			[
				'label' => esc_html__( 'Animation Speed', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => '0.3',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-marquee-items-wrapper' => 'animation-duration:calc({{SIZE}}s * 100);',
				],
			]
		);

		if ( 'text' !== $type ) {
			$this->add_control(
				'pause_hover',
				[
					'label' => esc_html__( 'Pause on Hover', 'jupiterx-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .raven-content-marquee:hover .raven-content-marquee-items-wrapper' => ' animation-play-state:paused;',
					],
				]
			);
		}
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_marquee_gradient_overlay_controls() {
		$this->start_controls_section(
			'gradient_overlay_section',
			[
				'label' => esc_html__( 'Gradient Overlays', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'top_gradient_overlay_heading',
			[
				'label' => esc_html__( 'Top Gradient Overlay', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'orientation' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'top_gradient_overlay_show',
			[
				'label' => esc_html__( 'Show Gradient', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'block',
					'' => 'none',
				],
				'condition' => [
					'orientation' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-top-gradient-overlay' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'top_gradient_overlay_color',
			[
				'label' => esc_html__( 'Gradient Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'condition' => [
					'orientation' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-top-gradient-overlay' => 'background:linear-gradient(to bottom, {{VALUE}} 0%, transparent 100%);',
				],
			]
		);

		$this->add_responsive_control(
			'top_gradient_size',
			[
				'label' => esc_html__( 'Gradient Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
						'min' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '200',
				],
				'condition' => [
					'orientation' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-top-gradient-overlay' => 'height: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'bottom_gradient_overlay_heading',
			[
				'label' => esc_html__( 'Bottom Gradient Overlay', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'orientation' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'bottom_gradient_overlay_show',
			[
				'label' => esc_html__( 'Show Gradient', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'block',
					'' => 'none',
				],
				'condition' => [
					'orientation' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-bottom-gradient-overlay' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bottom_gradient_overlay_color',
			[
				'label' => esc_html__( 'Gradient Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'condition' => [
					'orientation' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-bottom-gradient-overlay' => 'background:linear-gradient(to top, {{VALUE}} 0%, transparent 100%);',
				],
			]
		);

		$this->add_responsive_control(
			'bottom_gradient_size',
			[
				'label' => esc_html__( 'Gradient Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
						'min' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '200',
				],
				'condition' => [
					'orientation' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-bottom-gradient-overlay' => 'height: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'left_gradient_overlay_heading',
			[
				'label' => esc_html__( 'Left Gradient Overlay', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'orientation' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'left_gradient_overlay_show',
			[
				'label' => esc_html__( 'Show Gradient', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'block',
					'' => 'none',
				],
				'condition' => [
					'orientation' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-left-gradient-overlay' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'left_gradient_overlay_color',
			[
				'label' => esc_html__( 'Gradient Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'condition' => [
					'orientation' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-left-gradient-overlay' => 'background:linear-gradient(to right, {{VALUE}} 0%, transparent 100%);',
				],
			]
		);

		$this->add_responsive_control(
			'left_gradient_size',
			[
				'label' => esc_html__( 'Gradient Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
						'min' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '200',
				],
				'condition' => [
					'orientation' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-left-gradient-overlay' => 'width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'right_gradient_overlay_heading',
			[
				'label' => esc_html__( 'Right Gradient Overlay', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'orientation' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'right_gradient_overlay_show',
			[
				'label' => esc_html__( 'Show Gradient', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'block',
					'' => 'none',
				],
				'condition' => [
					'orientation' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-right-gradient-overlay' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'right_gradient_overlay_color',
			[
				'label' => esc_html__( 'Gradient Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'condition' => [
					'orientation' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-right-gradient-overlay' => 'background:linear-gradient(to left, {{VALUE}} 0%, transparent 100%);',
				],
			]
		);

		$this->add_responsive_control(
			'right_gradient_size',
			[
				'label' => esc_html__( 'Gradient Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
						'min' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '200',
				],
				'condition' => [
					'orientation' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-right-gradient-overlay' => 'width: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render rating stars.
	 *
	 * @param array $item each repeater item.
	 * @since 3.0.0
	 * @return string
	 */
	protected function render_stars( $item ) {
		$icon        = 'trustpilot' !== $item['type'] ? '&#xE933;' : '';
		$icon_active = 'trustpilot' !== $item['type'] ? '&#xE934;' : '';
		$icon_class  = "rating-{$item['type']}";
		$rating      = $item['rating'];
		$stars_html  = '';

		for ( $stars = 1; $stars <= 5; $stars++ ) {
			if ( $stars <= $rating ) {
				$stars_html .= sprintf(
					'<i class="elementor-star-empty active %1$s">%2$s</i>',
					$icon_class,
					$icon_active
				);

				continue;
			}

			$stars_html .= sprintf(
				'<i class="elementor-star-empty %1$s">%2$s</i>',
				$icon_class,
				$icon
			);
		}

		$output_stars_html = sprintf(
			'<div class="elementor-star-rating">%s</div>',
			wp_kses_post( $stars_html )
		);

		return $output_stars_html;
	}

	/**
	 * Render static icon.
	 *
	 * @param array $item each repeater item.
	 * @since 3.0.0
	 * @return string
	 */
	protected function render_icon( $item ) {
		$tag = 'span';

		if ( ! empty( $item['link']['url'] ) ) {
			$this->add_link_attributes( 'url_' . $item['_id'], $item['link'] );
			$tag = 'a';
		}

		if ( ! empty( $item['twitter_url']['url'] ) ) {
			$this->add_link_attributes( 'url_' . $item['_id'], $item['twitter_url'] );
			$tag = 'a';
		}

		return sprintf(
			'<%1$s class="raven-marquee-card-link" %2$s></%1$s>',
			$tag,
			$this->get_render_attribute_string( 'url_' . $item['_id'] )
		);
	}


	/**
	 * Render testimonial content.
	 *
	 * @param array $item each repeater item.
	 * @since 3.0.0
	 * @return void
	 */
	protected function render_testimonial_content( $item ) {
		$settings = $this->get_settings_for_display();

		$image          = ! empty( $settings['show_profile'] ) && ! empty( $item['image']['url'] ) ? Group_Control_Image_Size::get_attachment_image_html( $item, 'image', 'image' ) : '';
		$name           = ! empty( $item['name'] ) ? '<h4 class="raven-marquee-card-name">' . esc_html( $item['name'] ) . '</h4>' : '';
		$content        = ! empty( $item['content'] ) ? '<p class="raven-marquee-card-content">' . esc_html( $item['content'] ) . '</p>' : '';
		$heading        = ! empty( $item['heading'] ) ? '<h5 class="raven-marquee-card-heading">' . esc_html( $item['heading'] ) . '</h5>' : '';
		$rating         = ! empty( $item['rating'] ) ? $this->render_stars( $item ) : '';
		$link           = 'custom' !== $item['type'] ? $this->render_icon( $item ) : '';
		$twitter_handle = ! empty( $item['twitter_handle'] ) ? '<span class="raven-marquee-card-twitter-handle">' . esc_html( $item['twitter_handle'] ) . '</span>' : '';

		printf(
			'<div class="raven-marquee-card-wrapper"><div class="raven-marquee-card-header">%1$s <div class="raven-marquee-card-name-rating">%2$s %3$s %4$s</div></div><div class="raven-marquee-card-content-wrapper">%5$s %6$s</div> %7$s</div>',
			$image,
			$name,
			$rating,
			$twitter_handle,
			$heading,
			$content,
			$link
		);
	}

	/**
	 * Render repeater item content.
	 *
	 * @param array $item each repeater item.
	 * @since 3.0.0
	 * @return void
	 */
	protected function render_item_content( $item, $key ) {
		if ( 'testimonial' === $item['content_type'] ) {
			echo $this->render_testimonial_content( $item );
			return;
		}

		if ( 'text' === $item['content_type'] ) {
			printf(
				'<span %1$s>%2$s</span>',
				$this->get_render_attribute_string( 'item_content_' . $key ),
				wp_kses_post( $item['text'] )
			);

			return;
		}

		if ( 'image' === $item['content_type'] ) {
			echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'image', 'image' ) );
			return;
		}

		$frontend = Plugin::instance()->frontend;

		echo $frontend->get_builder_content_for_display( (int) $item['template'], true );
	}

	/**
	 * Render item.
	 *
	 * @param array $item each repeater item.
	 * @param int   $key each repeater key.
	 * @since 3.0.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render_item( $item, $key ) {
		$css_id     = ! empty( $item['css_id'] ) ? esc_attr( $item['css_id'] ) : esc_attr( 'item_' . $item['_id'] );
		$link_start = '';
		$link_end   = '';

		$is_testimonial = 'testimonial' === $item['content_type'];

		$item_content = ! $is_testimonial ? $item[ $item['content_type'] ] : '';

		if (
			( ! $is_testimonial && is_array( $item_content ) && empty( $item_content['url'] ) ) ||
			( ! $is_testimonial && ! is_array( $item_content ) && empty( $item_content ) )
		) {
			return;
		}

		if ( ! empty( $item['link']['url'] ) && ! $is_testimonial ) {
			$this->add_link_attributes( 'url_' . $key, $item['link'] );

			$link_start = '<a ' . $this->get_render_attribute_string( 'url_' . $key ) . '>';
			$link_end   = '</a>';

			$this->add_render_attribute( 'item_' . $key, [
				'class' => [
					'raven-marquee-item-has-link',
				],
			] );
		}

		$settings = $this->get_settings_for_display();

		$raven_animated_gradient = '';

		if ( 'text' === $item['content_type'] ) {
			if ( array_key_exists( 'raven_animated_gradient_enable', $settings ) && 'yes' === $settings['raven_animated_gradient_enable'] ) {
				$raven_animated_gradient = 'raven-animated-gradient';

				$color_list = $settings['raven_animated_gradient_color_list'];
				$speed      = '';
				$direction  = '';

				if ( array_key_exists( 'raven_animated_gradient_direction', $settings ) ) {
					$direction = $settings['raven_animated_gradient_direction'];
				}

				if ( array_key_exists( 'raven_animated_gradient_speed', $settings ) ) {
					$speed = $settings['raven_animated_gradient_speed']['size'] . 's';
				}

				$animated_gradient_attributes = Utils::get_animated_gradient_attributes( $direction, $color_list );

				$data_background_size = $animated_gradient_attributes['data_background_size'];
				$data_animation_name  = $animated_gradient_attributes['data_animation_name'];
				$angle                = $animated_gradient_attributes['angle'];

				$color = [];
				$count = count( $color_list );

				for ( $i = 0; $i < $count; $i++ ) {
					$color[ $i ] = $color_list[ $i ]['raven_animated_gradient_color'];
				}

				array_push( $color, $color_list[0]['raven_animated_gradient_color'], $color_list[1]['raven_animated_gradient_color'] );

				$color = implode( ',', $color );

				$this->add_render_attribute( 'item_content_' . $key, 'data-color', $color );
				$this->add_render_attribute( 'item_content_' . $key, 'data-angle', $angle );
				$this->add_render_attribute( 'item_content_' . $key, 'data-speed', $speed );
				$this->add_render_attribute( 'item_content_' . $key, 'data-animation-name', $data_animation_name );
				$this->add_render_attribute( 'item_content_' . $key, 'data-background-size', $data_background_size );
			}
		}

		$this->add_render_attribute( 'item_' . $key, [
			'class' => [
				'raven-marquee-item',
				'elementor-repeater-item-' . $item['_id'],
				'elementor-repeater-item-type-' . $item['content_type'],
			],
			'id' => $css_id,
		] );

		$this->add_render_attribute( 'item_content_' . $key, [
			'class' => [
				'raven-marquee-text-item',
				$raven_animated_gradient,
			],
		] );

		if ( $is_testimonial ) {
			$this->add_render_attribute( 'item_' . $key, [
				'class' => [
					'raven-marquee-testimonial-type-' . $item['type'],
				],
			] );
		}
		ob_start();
		?>
		<div <?php echo $this->get_render_attribute_string( 'item_' . $key ); ?>>
			<?php
				echo $link_start;
				$this->render_item_content( $item, $key );
				echo $link_end;
			?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Add common attributes for widgets.
	 *
	 * @since 3.0.0
	 */
	protected function add_marquee_render_attribute() {
		$this->add_render_attribute(
			'content-container',
			'class',
			'raven-content-marquee-container'
		);

		$this->add_render_attribute(
			'content',
			'class',
			'raven-content-marquee'
		);

		$this->add_render_attribute(
			'content-wrapper',
			'class',
			'raven-content-marquee-items-wrapper'
		);

		$this->add_render_attribute(
			'duplicated-content-wrapper',
			'class',
			'raven-content-marquee-items-wrapper raven-duplicated-content'
		);
	}

	/**
	 * Render all marquee items.
	 *
	 * @param array $items all repeater items.
	 * @since 3.0.0
	 * @return string
	 */
	protected function render_marquee_content( $items ) {
		$content = '';

		if ( empty( $items ) ) {
			return $content;
		}

		foreach ( $items as $key => $item ) {
			$content .= $this->render_item( $item, $key );
		}

		return $content;
	}

	/**
	 * Handle top gradinet overlay html.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	protected function handle_top_gradient_overlay() {
		echo '<div class="raven-marquee-top-gradient-overlay"></div>';
	}

	/**
	 * Handle bottom gradinet overlay html.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	protected function handle_bottom_gradient_overlay() {
		echo '<div class="raven-marquee-bottom-gradient-overlay"></div>';
	}

	/**
	 * Handle left gradinet overlay html.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	protected function handle_left_gradient_overlay() {
		echo '<div class="raven-marquee-left-gradient-overlay"></div>';
	}

	/**
	 * Handle right gradinet overlay html.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	protected function handle_right_gradient_overlay() {
		echo '<div class="raven-marquee-right-gradient-overlay"></div>';
	}
}
