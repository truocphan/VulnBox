<?php
namespace JupiterX_Core\Raven\Modules\Photo_Album\Skins;

use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Skin_Cover extends Skin_Base {
	public function get_id() {
		return 'cover';
	}

	public function get_title() {
		return __( 'Cover', 'jupiterx-core' );
	}

	protected function add_skin_hover_effect() {
		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
			]
		);
	}

	protected function register_cover_border_controls() {
		$this->add_control(
			'cover_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cover_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'cover_border!' ) => '',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'cover',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-photo-album-item',
			]
		);

		$this->add_control(
			'cover_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'cover_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-photo-album-item',
			]
		);
	}

	protected function register_overlay_controls() {
		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'overlay_tabs' );

		$this->start_controls_tab(
			'overlay_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'overlay_tab_background_normal',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-photo-album-img:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'overlay_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'overlay_tab_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-img:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'overlay_hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'jupiterx-core' ),
					'fading' => __( 'Fading In', 'jupiterx-core' ),
					'ripple' => __( 'Ripple', 'jupiterx-core' ),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_thumbnail_controls() {
		$this->start_controls_section(
			'section_thumbnail',
			[
				'label' => __( 'Thumbnail', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'thumbnail_style',
			[
				'label' => __( 'Style', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'rectangular',
				'options' => [
					'rectangular' => __( 'Rectangular', 'jupiterx-core' ),
					'circle' => __( 'Circle', 'jupiterx-core' ),
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_tabs' );

		$this->start_controls_tab(
			'thumbnail_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'thumbnail_tab_opacity_normal',
			[
				'label' => __( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_tab_size_normal',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail_style' ) => 'circle',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_tab_width_normal',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					$this->get_control_id( 'thumbnail_style' ) => 'rectangular',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_tab_height_normal',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					$this->get_control_id( 'thumbnail_style' ) => 'rectangular',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnail_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'thumbnail_tab_opacity_hover',
			[
				'label' => __( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-thumbnails' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_tab_size_hover',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-thumbnails img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					$this->get_control_id( 'thumbnail_style' ) => 'circle',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_tab_width_hover',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-thumbnails img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					$this->get_control_id( 'thumbnail_style' ) => 'rectangular',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_tab_height_hover',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-thumbnails img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					$this->get_control_id( 'thumbnail_style' ) => 'rectangular',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'thumbnail_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'thumbnail_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails img' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'thumbnail',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-photo-album-thumbnails img',
			]
		);

		$this->add_control(
			'thumbnail_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-thumbnails img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render_skin_image( $settings ) {
		$overlay_hover_effect = $this->get_instance_value( 'overlay_hover_effect' );
		$img                  = 'img-' . $this->item['_id'];

		$this->parent->add_render_attribute( $img, [
			'class' => 'raven-photo-album-img',
			'style' => 'background-image: url(' . Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image', $settings ) . ');',
		] );

		if ( 'none' !== $overlay_hover_effect ) {
			$this->parent->add_render_attribute( $img, 'class', 'raven-photo-album-overlay-' . $overlay_hover_effect );
		}

		?>
			<div <?php echo $this->parent->get_render_attribute_string( $img ); ?>></div>
		<?php
	}
}
