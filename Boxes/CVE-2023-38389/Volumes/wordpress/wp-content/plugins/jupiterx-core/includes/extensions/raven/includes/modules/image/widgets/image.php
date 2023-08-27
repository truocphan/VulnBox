<?php
namespace JupiterX_Core\Raven\Modules\Image\Widgets;

use JupiterX_Core\Raven\Utils;
use Elementor\Group_Control_Image_Size;
use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Image extends Base_Widget {

	public function get_name() {
		return 'raven-image';
	}

	public function get_title() {
		return __( 'Image', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-image';
	}

	public function get_script_depends() {
		return [ 'jupiterx-core-raven-parallax-scroll' ];
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_image();
		$this->register_section_caption();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'caption',
			[
				'label' => __( 'Caption', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => __( 'Enter your image caption', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link to', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'jupiterx-core' ),
					'file' => __( 'Media File', 'jupiterx-core' ),
					'custom' => __( 'Custom URL', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link to', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'https://your-link.com', 'jupiterx-core' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'lightbox',
			[
				'label' => __( 'Lightbox', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'jupiterx-core' ),
					'no' => __( 'No', 'jupiterx-core' ),
				],
				'condition' => [
					'link_to' => 'file',
				],
			]
		);

		$this->add_control(
			'switch_on_hover',
			[
				'label' => __( 'Switch on Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'switch_image',
			[
				'label' => __( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'condition' => [
					'switch_on_hover' => 'yes',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image', // Actually its `image_size`.
				'default' => 'full',
			]
		);

		$this->add_control(
			'hover_animation', // Image size class uses hover_animation.
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
				'condition' => [
					'switch_on_hover' => '',
					'loop_animation' => '',
				],
			]
		);

		$this->add_control(
			'loop_animation',
			[
				'label' => __( 'Loop Animation', 'jupiterx-core' ),
				'type' => 'raven_loop_animation',
				'condition' => [
					'switch_on_hover' => '',
					'hover_animation' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_image() {
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'size',
			[
				'label' => __( 'Max Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => '%',
				],
				'size_units' => [ 'px', '%', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => '%',
				],
				'size_units' => [ 'px', '%', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity (%)', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-image' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			'raven-parallax-scroll',
			[
				'name' => 'image_parallax_scroll',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .raven-image img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'image_tabs' );

		$this->start_controls_tab(
			'image_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-image img:first-of-type',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'image_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-image:hover img:first-of-type',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_section();
	}

	private function register_section_caption() {
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => __( 'Caption', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'caption!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'caption_align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type'  => 'color',
					'value' => '3',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .widget-image-caption',
				'scheme' => '3',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Temporary suppressed.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$settings['switch_image_size'] = $settings['image_size'];

		if ( ! empty( $settings['switch_on_hover'] ) ) {
			$settings['hover_animation'] = '';
		}

		$image          = Group_Control_Image_Size::get_attachment_image_html( $settings );
		$has_caption    = ! empty( $settings['caption'] );
		$loop_animation = $settings['loop_animation'];

		$this->add_render_attribute( 'wrapper', 'class', 'raven-image' );

		if ( ! empty( $settings['shape'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'raven-image-shape-' . $settings['shape'] );
		}

		if ( ! empty( $settings['switch_on_hover'] ) && ! empty( $settings['switch_image']['id'] ) ) {
			$switch_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'switch_image' );
			$this->add_render_attribute( 'wrapper', 'class', 'raven-switch-image' );
		}

		if ( ! empty( $settings['image_parallax_scroll_type'] ) ) {
			$this->add_render_attribute( 'wrapper', 'data-parallax', Utils::parallax_scroll(
				$settings['image_parallax_scroll_x'],
				$settings['image_parallax_scroll_y'],
				$settings['image_parallax_scroll_z'],
				$settings['image_parallax_scroll_smoothness']['size']
			) );
		}

		$link = $this->get_link_url( $settings );

		if ( $link ) {

			$this->add_render_attribute( 'link', [
				'href'                         => $link['url'],
				'class'                        => 'elementor-clickable',
				'data-elementor-open-lightbox' => $settings['lightbox'],
			] );

			$this->render_link_properties( $this, $link, 'link' );

		} ?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
		<?php if ( $has_caption ) : ?>
			<figure class="wp-caption">
		<?php endif; ?>

		<?php if ( $link ) : ?>
				<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
		<?php endif; ?>

		<?php
		if ( ! empty( $loop_animation ) && empty( $settings['switch_on_hover'] ) ) {
			$image = preg_replace(
				'/class=\"([^\"]+)\"/',
				'class="$1 raven-loop-animation ' . $loop_animation . '"',
				$image
			);
		}

		if ( ! empty( $settings['switch_on_hover'] ) ) {
			echo $switch_image;
		}

		echo $image;

		if ( $link ) :
			?>
			</a>
			<?php
		endif;

		if ( $has_caption ) :
			?>
			<figcaption class="widget-image-caption wp-caption-text"><?php echo $settings['caption']; ?></figcaption>
			<?php
		endif;

		if ( $has_caption ) :
			?>
			</figure>
			<?php endif; ?>
		</div>
		<?php
	}

	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		return [
			'url' => $settings['image']['url'],
		];
	}
}
