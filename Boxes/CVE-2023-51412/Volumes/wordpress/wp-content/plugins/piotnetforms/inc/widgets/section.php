<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Section extends Division_Base_Widget_Piotnetforms {
	public function get_type() {
		return 'section';
	}

	public function get_class_name() {
		return 'piotnetforms_Section';
	}

	public function get_title() {
		return 'Section';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-section.svg',
		];
	}

	public function get_categories() {
		return [ 'pafe-form-builder' ];
	}

	public function get_keywords() {
		return [ 'input', 'form', 'field' ];
	}

	public function register_controls() {
		$this->start_tab( 'settings', 'Settings' );

		$this->start_section( 'section_settings', 'Layout' );
		$this->add_setting_controls();

		$this->start_tab( 'style', 'Style' );
		$this->start_section( 'section_style_typography', 'Typography' );
		$this->add_style_typography_controls();

		$this->add_advanced_tab();

		return $this->structure;
	}

	private function add_setting_controls() {
		$this->add_control(
			'section_content_width_type',
			[
				'type'        => 'select',
				'label'       => __( 'Content Width', 'piotnetforms' ),
				'value'       => 'boxed',
				'label_block' => true,
				'options'     => [
					'boxed'      => 'Boxed',
					'full-width' => 'Full Width',
				],
			]
		);
		$this->add_control(
			'section_content_width',
			[
				'type'        => 'slider',
				'label'       => __( 'Width', 'piotnetforms' ),
				'value'       => [
					'unit' => 'px',
					'size' => 1140,
				],
				'label_block' => true,
				'size_units'  => [
					'px' => [
						'min'  => 500,
						'max'  => 1600,
						'step' => 1,
					],
				],
				'selectors'   => [
					'{{WRAPPER}}>.piotnet-section__container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'conditions'  => [
					[
						'name'     => 'section_content_width_type',
						'operator' => '==',
						'value'    => 'boxed',
					],
				],
			]
		); // FIXME conditions not working
	}

	private function add_style_typography_controls() {

		$this->add_responsive_control(
			'section_text_align',
			[
				'type'         => 'select',
				'label'        => 'Text Alignment',
				'label_block'  => true,
				'options'      => [
					''        => __( 'Default', 'piotnetforms' ),
					'left'    => __( 'Left', 'piotnetforms' ),
					'center'  => __( 'Center', 'piotnetforms' ),
					'right'   => __( 'Right', 'piotnetforms' ),
					'justify' => __( 'Justified', 'piotnetforms' ),
				],
				'prefix_class' => 'piotnetforms%s-align-',
				'selectors'    => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'section_text_color',
			[
				'type'      => 'color',
				'label'     => 'Text Color',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_text_typography_controls(
			'section_text_typography',
			[
				'selectors' => '{{WRAPPER}}',
			]
		);
	}

	public function render_start( $editor = false ) {
			$settings = $this->settings;
		?>
			<?php if ( $editor ) : ?>
				<div class="piotnet-section__controls">
					<div class="piotnet-section__controls-item piotnet-section__controls-item--edit" title="Edit" data-piotnet-control-edit>
						<i class="fas fa-th"></i>
					</div>
					<div class="piotnet-section__controls-item piotnet-section__controls-item--duplicate" title="Duplicate" data-piotnet-control-duplicate>
						<i class="far fa-clone"></i>
					</div>
					<div class="piotnet-section__controls-item piotnet-section__controls-item--remove" title="Delete" data-piotnet-control-remove>
						<i class="fas fa-times"></i>
					</div>
				</div>
			<?php endif; ?>
			<div class="piotnet-section__container<?php if ( $settings['section_content_width_type'] == 'full-width' ) { echo ' piotnet-section__container--full-width'; } ?>"
		<?php
		if ( $editor ) :
			?>
				 data-piotnet-section-container data-piotnet-inner-html<?php endif; ?>>
		<?php
	}

	public function render_end() {
		?>
			</div>
		<?php
	}

	public function render() {
		?>
		<?php
	}

	public function live_preview() {
		?>
		<?php
	}
}
