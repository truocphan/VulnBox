<?php
namespace JupiterX_Core\Raven\Modules\Categories\Skins;

use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Skin_Outer_Content extends Skin_Base {
	public function get_id() {
		return 'outer_content';
	}

	public function get_title() {
		return __( 'Outer Content', 'jupiterx-core' );
	}

	protected function register_image_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Featured Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
				'label' => __( 'Image Ratio', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img img' => 'height: calc( {{SIZE}} * 100px );',
				],
				'condition' => [
					$this->get_control_id( 'layout' ) => 'grid',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .raven-categories-item img' => 'opacity: {{SIZE}};',
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
					'{{WRAPPER}} .raven-categories-item:hover img' => 'opacity: {{SIZE}};',
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
				'selector' => '{{WRAPPER}} .raven-categories-img:hover::before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render_skin_image( $settings ) {
		?>
		<a href="<?php echo get_term_link( $this->term->term_id ); ?>" class="raven-categories-img">
			<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
		</a>
		<?php
	}
}
