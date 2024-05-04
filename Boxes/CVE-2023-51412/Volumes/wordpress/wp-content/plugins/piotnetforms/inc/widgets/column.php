<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once __DIR__ . '/../widgets/division_base.php';

class piotnetforms_Column extends Division_Base_Widget_Piotnetforms {
	public function get_type() {
		return 'column';
	}

	public function get_class_name() {
		return 'piotnetforms_Column';
	}

	public function get_title() {
		return 'Column';
	}

	public function get_icon() {
		return 'fas fa-columns';
	}

	public function get_categories() {
		return [ 'pafe-form-builder' ];
	}

	public function get_keywords() {
		return [ 'input', 'form', 'field' ];
	}

	public function register_controls() {
		$this->start_tab( 'settings', 'Settings' );

		$this->start_section( 'column_settings', 'Layout' );
		$this->add_setting_controls();

		$this->start_tab( 'style', 'Style' );
		$this->start_section( 'column_typography_section', 'Typography' );
		$this->add_style_controls();

		$this->add_advanced_tab();

		return $this->structure;
	}

	private function add_setting_controls() {
		$this->add_responsive_control(
			'column_width',
			[
				'type'        => 'number',
				'label'       => __( 'Column Width (%)', 'piotnetforms' ),
				'value'       => '',
				'label_block' => true,
				'selectors'   => [
					'{{WRAPPER}}' => 'width: {{VALUE}}%;',
				],
			]
		);
		$this->add_responsive_control(
			'justify_content',
			[
				'type'        => 'select',
				'label'       => __( 'Justify Content', 'piotnetforms' ),
				'value'       => '',
				'label_block' => true,
				'options'     => [
					''              => __( 'Default', 'piotnetforms' ),
					'center'        => __( 'center', 'piotnetforms' ),
					'flex-start'    => __( 'flex-start', 'piotnetforms' ),
					'flex-end'      => __( 'flex-end', 'piotnetforms' ),
					'space-around'  => __( 'space-around', 'piotnetforms' ),
					'space-between' => __( 'space-between', 'piotnetforms' ),
				],
				'selectors'   => [
					'{{WRAPPER}}>.piotnet-column__inner' => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	private function add_style_controls() {
		$this->add_text_typography_controls(
			'column_typography',
			[
				'selectors' => '{{WRAPPER}}',
			]
		);
		$this->add_responsive_control(
			'column_text_align',
			[
				'type'         => 'select',
				'label'        => __( 'Text Align', 'piotnetforms' ),
				'value'        => '',
				'label_block'  => true,
				'options'      => [
					''              => __( 'Default', 'piotnetforms' ),
					'left'          => __( 'Left', 'piotnetforms' ),
					'center'        => __( 'Center', 'piotnetforms' ),
					'right'         => __( 'Right', 'piotnetforms' ),
					'justify'       => __( 'Justified', 'piotnetforms' ),
					'space-between' => __( 'space-between', 'piotnetforms' ),
				],
				'prefix_class' => 'piotnetforms%s-align-',
				'selectors'    => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);
	}

	protected function add_advanced_controls() {
		$this->add_responsive_control(
			'advanced_margin',
			[
				'type'        => 'dimensions',
				'label'       => __( 'Margin', 'piotnetforms' ),
				'value'       => [
					'unit'   => 'px',
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}}>.piotnet-column__inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'advanced_padding',
			[
				'type'        => 'dimensions',
				'label'       => __( 'Padding', 'piotnetforms' ),
				'value'       => [
					'unit'   => 'px',
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}}>.piotnet-column__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_box_shadow_and_custom_id();
	}

	public function render_start( $editor = false ) {
		?>
			<?php if ( $editor ) : ?>
				<div class="piotnet-column__controls" data-piotnet-column-controls>
					<div class="piotnet-column__controls-item piotnet-column__controls-item--edit" title="Edit" data-piotnet-control-edit>
						<i class="fas fa-th"></i>
					</div>
					<div class="piotnet-column__controls-item piotnet-column__controls-item--duplicate" title="Duplicate" data-piotnet-control-duplicate>
						<i class="far fa-clone"></i>
					</div>
					<div class="piotnet-column__controls-item piotnet-column__controls-item--remove" title="Delete" data-piotnet-control-remove>
						<i class="fas fa-times"></i>
					</div>
				</div>
			<?php endif; ?>
			<div class="piotnet-column__inner"
			<?php
			if ( $editor ) :
				?>
				 data-piotnet-sortable data-piotnet-inner-html<?php endif; ?>>
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
