<?php
namespace JupiterX_Core\Raven\Modules\Search_Form\Skins;

use Elementor\Plugin as Elementor;
use Elementor\Widget_Base as ElementorWidgetBase;

defined( 'ABSPATH' ) || die();

class Full extends \Elementor\Skin_Base {

	public function get_id() {
		return 'full';
	}

	public function get_title() {
		return __( 'Full Screen', 'jupiterx-core' );
	}

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-search-form/section_content/after_section_end', [ $this, 'register_controls' ], 10 );
	}

	public function register_controls( ElementorWidgetBase $widget ) {
		$this->set_parent( $widget );

		$this->register_input_controls();
		$this->register_overlay_controls();
		$this->register_icon_controls();
		$this->register_close_button_controls();
	}

	public function register_input_controls() {
		$this->start_controls_section(
			'section_input',
			[
				'label' => __( 'Input', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'input_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-search-form-input',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_input' );

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'color: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'input_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'input_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-input',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_s' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'input_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-input',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => __( 'Focus', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'focus_input_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'focus_input_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'focus_input_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'focus_input_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'focus_input_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'focus_input_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-input:focus',
			]
		);

		$this->add_control(
			'focus_input_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'focus_input_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-input:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_overlay_controls() {
		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'overlay_background',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-search-form-lightbox',
			]
		);

		$this->end_controls_section();
	}

	public function register_icon_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-search-form-button > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} svg.raven-search-form-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'right',
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
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-search-form-button > svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} svg.raven-search-form-button' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'icon_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'icon_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-button',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'icon_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_icon_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-search-form-button:hover > svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} svg.raven-search-form-button:hover' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_icon_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_icon_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_icon_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'hover_icon_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_icon_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-button:hover',
			]
		);

		$this->add_control(
			'hover_icon_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_icon_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_close_button_controls() {

		$this->start_controls_section(
			'section_close',
			[
				'label' => __( 'Close Button', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'tabs_close' );

		$this->start_controls_tab(
			'close_icon_normal',
			[
				'label' => __( 'NORMAL', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'close_icon_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form .raven-search-form-close' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'close_icon_hover',
			[
				'label' => __( 'HOVER', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'close_icon_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form .raven-search-form-close:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function render() {
		$icon     = $this->parent->get_settings( 'icon' );
		$icon_new = $this->parent->get_settings( 'icon_new' );

		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $this->parent->get_settings['__fa4_migrated']['icon_new'] );
		$is_new            = empty( $icon ) && $migration_allowed;

		if ( empty( $icon ) && empty( $icon_new['value'] ) ) {
			$icon_new = [
				'value' => 'fas fa-search',
				'library' => 'fa-solid',
			];
		}
		?>
		<form class="raven-search-form raven-search-form-full" method="get" action="<?php echo esc_url( $this->parent->form_home_url() ); ?>" role="search">
			<div class="raven-search-form-container">
				<?php

				if ( $is_new || $migrated ) {
					if ( 'svg' === $icon_new['library'] || 'active' === get_option( 'elementor_experiment-e_font_icon_svg' ) ) {
						?>
						<span class="raven-search-form-button raven-search-form-toggle raven-search-form-button-svg" tabindex="-1">
							<?php Elementor::$instance->icons_manager->render_icon( $icon_new ); ?>
						</span>
						<?php
					} else {
						Elementor::$instance->icons_manager->render_icon(
							$icon_new,
							[
								'class' =>
								[
									'raven-search-form-button',
									'raven-search-form-toggle',
								],
								'tabindex' => '-1',
							],
							'span'
						);
					}
				} else {
					?>
				<span class="raven-search-form-button raven-search-form-toggle <?php echo esc_attr( $icon ); ?>" tabindex="-1"></span>
				<?php } ?>
			</div>
			<div class="raven-search-form-lightbox">
				<span class="raven-search-form-close" tabindex="-1">&times;</span>
				<input class="raven-search-form-input" type="search" name="s" placeholder="<?php echo $this->parent->get_settings_for_display( 'placeholder' ); ?>" />
			</div>
			<?php if ( class_exists( 'SitePress' ) ) : ?>
				<input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/>
			<?php endif; ?>
		</form>
		<?php
	}
}
