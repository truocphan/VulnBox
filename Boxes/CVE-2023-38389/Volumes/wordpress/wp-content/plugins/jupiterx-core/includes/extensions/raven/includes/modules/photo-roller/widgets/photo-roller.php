<?php
namespace JupiterX_Core\Raven\Modules\Photo_Roller\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Photo_Roller extends Base_Widget {

	public function get_name() {
		return 'raven-photo-roller';
	}

	public function get_title() {
		return esc_html__( 'Photo Roller', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-photo-roller';
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'render_type' => 'template',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image',
				'default' => 'full',
			]
		);

		$this->add_responsive_control(
			'max_height',
			[
				'label' => esc_html__( 'Max Height', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'rolling_speed',
			[
				'label' => esc_html__( 'Rolling Speed (px/s)', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame, {{WRAPPER}} .raven-photo-roller-frame:after' => 'animation-duration: calc( 301s - {{SIZE}}s );',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'reverse_direction',
			[
				'label' => esc_html__( 'Reverse direction', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame' => 'animation-direction: reverse;',
				],
			]
		);

		$this->add_control(
			'pause_hover',
			[
				'label' => esc_html__( 'Pause on hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame:hover' => 'animation-play-state: paused;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$css_selector = '.raven-photo-roller-' . $this->get_id() . ' .raven-photo-roller-frame';

		$this->add_render_attribute(
			'wrapper',
			'class',
			'raven-photo-roller raven-photo-roller-' . $this->get_id()
		);

		$image = $this->get_image_src( $settings );

		$settings['image']['width']  = $image[1];
		$settings['image']['height'] = $image[2];

		if ( 'custom' === $settings['image_size'] ) {
			$settings['image']['width']  = $settings['image_custom_dimension']['width'];
			$settings['image']['height'] = $settings['image_custom_dimension']['height'];
		}

		foreach ( Elementor::$instance->breakpoints->get_active_breakpoints() as $breakpoint ) {
			$breakpoint_name = $breakpoint->get_name();

			if ( empty( $settings[ "image_{$breakpoint_name}" ]['id'] ) ) {
				continue;
			}

			$image = $this->get_image_src( $settings, $breakpoint_name );

			$settings[ "image_{$breakpoint_name}" ]['width']  = $image[1];
			$settings[ "image_{$breakpoint_name}" ]['height'] = $image[2];

			if ( 'custom' === $settings['image_size'] ) {
				$settings[ "image_{$breakpoint_name}" ]['width']  = $settings['image_custom_dimension']['width'];
				$settings[ "image_{$breakpoint_name}" ]['height'] = $settings['image_custom_dimension']['height'];
			}
		}

		// Render custom styles for this element.
		$this->render_styles( $css_selector, $settings );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="raven-photo-roller-frame">
				<picture>
					<?php
					$image_url = $this->get_image_src( $settings );

					if ( 'custom' !== $settings['image_size'] && \Elementor\Utils::get_placeholder_image_src() !== $settings['image']['url'] ) {
						$image_url = $image_url[0];
					}

					foreach ( Elementor::$instance->breakpoints->get_active_breakpoints() as $breakpoint ) {
						$breakpoint_name = $breakpoint->get_name();

						if ( empty( $settings[ "image_{$breakpoint_name}" ]['url'] ) ) {
							continue;
						}

						$image_url_breakpoint = $this->get_image_src( $settings, $breakpoint_name );

						if ( 'custom' !== $settings['image_size'] && \Elementor\Utils::get_placeholder_image_src() !== $settings[ "image_{$breakpoint_name}" ]['url'] ) {
							$image_url_breakpoint = $image_url_breakpoint[0];
						}

						echo "<source media='({$breakpoint->get_direction()}-width:{$breakpoint->get_value()}px)' srcset='{$image_url_breakpoint}'>";
					} ?>
					<img class="raven-photo-roller-frame-img" src="<?php echo $image_url; ?>" alt="" itemprop="image">
				</picture>
			</div>
		</div>
		<?php
	}

	/**
	 * @todo Refactor to use a better way.
	 */
	protected function render_styles( $selector, $settings ) {
		$selector_after = $selector . ':after';

		echo '<style type="text/css">';

		$image_url = $settings['image']['url'];

		if ( isset( $settings['image']['url'] ) && \Elementor\Utils::get_placeholder_image_src() !== $settings['image']['url'] ) {
			$image_url = $this->get_image_src( $settings );

			if ( 'custom' !== $settings['image_size'] ) {
				$image_url = $image_url[0];
			}
		}

		printf(
			'%1$s { background-image: url(%2$s); }',
			$selector_after,
			$image_url
		);

		if ( isset( $settings['image']['width'] ) ) {
			printf( '%1$s, %1$s img { width: %2$spx; height: %3$spx; }', $selector, $settings['image']['width'], $settings['image']['height'] );
		}

		foreach ( array_reverse( Elementor::$instance->breakpoints->get_active_breakpoints() ) as $breakpoint ) {
			$breakpoint_name = $breakpoint->get_name();

			if ( empty( $settings[ "image_{$breakpoint_name}" ]['width'] ) ) {
				continue;
			}

			printf(
				'@media (%1$s-width: %2$spx) { %3$s, %3$s img { width: %4$spx; height: %5$spx; } }',
				$breakpoint->get_direction(),
				$breakpoint->get_value(),
				$selector,
				$settings[ "image_{$breakpoint_name}" ]['width'],
				$settings[ "image_{$breakpoint_name}" ]['height']
			);

			$image_url = $settings[ "image_{$breakpoint_name}" ]['url'];

			if ( isset( $settings[ "image_{$breakpoint_name}" ]['url'] ) && \Elementor\Utils::get_placeholder_image_src() !== $settings[ "image_{$breakpoint_name}" ]['url'] ) {
				$image_url = $this->get_image_src( $settings, $breakpoint_name );

				if ( 'custom' !== $settings['image_size'] ) {
					$image_url = $image_url[0];
				}
			}

			printf(
				'@media (%1$s-width: %2$spx) { %3$s { background-image: url(%4$s); } }',
				$breakpoint->get_direction(),
				$breakpoint->get_value(),
				$selector_after,
				$image_url
			);
		}

		echo '</style>';
	}

	protected function get_image_src( $settings, $breakpoint_name = null ) {
		if ( \Elementor\Utils::get_placeholder_image_src() === $settings['image']['url'] ) {
			return esc_url( $settings['image']['url'] );
		}

		$image_size = $settings['image_size'];
		$image_src  = wp_get_attachment_image_src( $settings['image']['id'], $image_size );

		if ( 'custom' === $image_size ) {
			$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image', $settings );
		}

		if ( $breakpoint_name && isset( $settings[ "image_{$breakpoint_name}" ] ) ) {
			$image_src = wp_get_attachment_image_src( $settings[ "image_{$breakpoint_name}" ]['id'], $image_size );

			if ( 'custom' === $image_size ) {
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings[ "image_{$breakpoint_name}" ]['id'], 'image', $settings );
			}
		}

		return $image_src;
	}
}
