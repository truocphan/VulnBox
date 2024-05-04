<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

abstract class Division_Base_Widget_Piotnetforms extends Base_Widget_Piotnetforms {
	protected function add_repeater_trigger_controls() {
		$this->start_section( 'repeater_section', 'Repeater' );
		$this->add_control(
			'piotnetforms_repeater_enable',
			[
				'type'         => 'switch',
				'label'        => __( 'Enable', 'piotnetforms' ),
				'value'        => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'piotnetforms_repeater_form_id',
			[
				'type'        => 'text',
				'label'       => __( 'Form ID* (Required)', 'piotnetforms' ),
				'description' => __( 'Enter the same form id for all fields in a form, with latin character and no space. E.g order_form', 'piotnetforms' ),
				'conditions'  => [
					[
						'name'     => 'piotnetforms_repeater_enable',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			]
		);

		$this->add_control(
			'piotnetforms_repeater_id',
			[
				'type'        => 'text',
				'label'       => __( 'Repeater ID* (Required)', 'piotnetforms' ),
				'description' => __( 'Enter Repeater ID with latin character and no space, no comma. E.g products_repeater', 'piotnetforms' ),
				'conditions'  => [
					[
						'name'     => 'piotnetforms_repeater_enable',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			]
		);

		$this->add_control(
			'piotnetforms_repeater_label',
			[
				'type'       => 'text',
				'label'      => __( 'Repeater Label* (Required)', 'piotnetforms' ),
				'conditions' => [
					[
						'name'     => 'piotnetforms_repeater_enable',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			]
		);

		$this->add_control(
			'piotnetforms_repeater_limit',
			[
				'type'       => 'number',
				'label'      => __( 'Limit number of elements', 'piotnetforms' ),
				'default'    => 0,
				'conditions' => [
					[
						'name'     => 'piotnetforms_repeater_enable',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			]
		);

		$this->add_control(
			'piotnetforms_repeater_shortcode',
			[
				'type'       => 'html',
				'label'      => __( 'Repeater Shortcode', 'piotnetforms' ),
				'raw'        => '<input type="text" placeholder="Click here to get it" class="piotnetforms-repeater-shortcode" readonly />',
				'conditions' => [
					[
						'name'     => 'piotnetforms_repeater_enable',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			]
		);
	}
}
