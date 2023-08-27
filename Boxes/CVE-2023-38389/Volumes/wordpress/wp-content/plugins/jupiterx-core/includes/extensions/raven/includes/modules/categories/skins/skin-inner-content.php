<?php
namespace JupiterX_Core\Raven\Modules\Categories\Skins;

use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Skin_Inner_Content extends Skin_Base {
	public function get_id() {
		return 'inner_content';
	}

	public function get_title() {
		return __( 'Inner Content', 'jupiterx-core' );
	}

	protected function register_image_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Featured Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'image_background_position',
			[
				'label' => __( 'Background Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'center center',
				'options' => [
					'center center' => __( 'Center Center', 'jupiterx-core' ),
					'center left' => __( 'Center Left', 'jupiterx-core' ),
					'center right' => __( 'Center Right', 'jupiterx-core' ),
					'top center' => __( 'Top Center', 'jupiterx-core' ),
					'top left' => __( 'Top Left', 'jupiterx-core' ),
					'top right' => __( 'Top Right', 'jupiterx-core' ),
					'bottom center' => __( 'Bottom Center', 'jupiterx-core' ),
					'bottom left' => __( 'Bottom Left', 'jupiterx-core' ),
					'bottom right' => __( 'Bottom Right', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'background-position: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_background_size',
			[
				'label' => __( 'Background Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'cover',
				'options' => [
					'auto' => __( 'Auto', 'jupiterx-core' ),
					'cover' => __( 'Cover', 'jupiterx-core' ),
					'contain' => __( 'Contain', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'background-size: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => __( 'None', 'jupiterx-core' ),
					'slide-right' => __( 'Slide Right', 'jupiterx-core' ),
					'slide-down' => __( 'Slide Down', 'jupiterx-core' ),
					'scale-down' => __( 'Scale Down', 'jupiterx-core' ),
					'scale-up' => __( 'Scale Up', 'jupiterx-core' ),
					'blur' => __( 'Blur', 'jupiterx-core' ),
					'grayscale-reverse' => __( 'Grayscale to Color', 'jupiterx-core' ),
					'grayscale' => __( 'Color to Grayscale', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-hover-',
			]
		);

		$this->start_controls_tabs( 'image_tabs' );

		$this->start_controls_tab(
			'image_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'image_opacity_normal',
			[
				'label' => __( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'opacity: {{SIZE}};',
				],
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
						'label' => __( 'Overlay Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-img::before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' => __( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item:hover .raven-categories-img' => 'opacity: {{SIZE}};',
				],
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
						'label' => __( 'Overlay Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-item:hover .raven-categories-img::before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	protected function render_skin_image( $settings ) {
		?>
		<div class="raven-categories-img" style="background-image: url('<?php echo Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image', $settings ); ?>')"></div>
		<?php
	}
}
