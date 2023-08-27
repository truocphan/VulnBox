<?php

namespace JupiterX_Core\Raven\Controls\Group;

defined( 'ABSPATH' ) || die();

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

class Box_Style extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'raven-box-style';
	}

	protected function init_fields() {
		$fields = [];

		$fields['background'] = [
			'label' => esc_html_x( 'Background Type', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'color' => [
					'title' => esc_html_x( 'Classic', 'Background Control', 'jupiterx-core' ),
					'icon' => 'fa fa-paint-brush',
				],
				'gradient' => [
					'title' => esc_html_x( 'Gradient', 'Background Control', 'jupiterx-core' ),
					'icon' => 'fa fa-barcode',
				],
			],
			'label_block' => false,
			'render_type' => 'ui',
		];

		$fields['color'] = [
			'label' => esc_html_x( 'Background Color', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'title' => esc_html_x( 'Background Color', 'Background Control', 'jupiterx-core' ),
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'background' => [
					'color',
					'gradient',
				],
			],
		];

		$fields['color_stop'] = [
			'label' => esc_html_x( 'Location', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b'] = [
			'label' => esc_html_x( 'Second Background Color', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f2295b',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label' => esc_html_x( 'Location', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_type'] = [
			'label' => esc_html_x( 'Type', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'linear' => esc_html_x( 'Linear', 'Background Control', 'jupiterx-core' ),
				'radial' => esc_html_x( 'Radial', 'Background Control', 'jupiterx-core' ),
			],
			'default' => 'linear',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label' => esc_html_x( 'Angle', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'deg' ],
			'default' => [
				'unit' => 'deg',
				'size' => 180,
			],
			'range' => [
				'deg' => [
					'step' => 10,
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_position'] = [
			'label' => esc_html_x( 'Position', 'Background Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'center center' => esc_html_x( 'Center Center', 'Background Control', 'jupiterx-core' ),
				'center left' => esc_html_x( 'Center Left', 'Background Control', 'jupiterx-core' ),
				'center right' => esc_html_x( 'Center Right', 'Background Control', 'jupiterx-core' ),
				'top center' => esc_html_x( 'Top Center', 'Background Control', 'jupiterx-core' ),
				'top left' => esc_html_x( 'Top Left', 'Background Control', 'jupiterx-core' ),
				'top right' => esc_html_x( 'Top Right', 'Background Control', 'jupiterx-core' ),
				'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'jupiterx-core' ),
				'bottom left' => esc_html_x( 'Bottom Left', 'Background Control', 'jupiterx-core' ),
				'bottom right' => esc_html_x( 'Bottom Right', 'Background Control', 'jupiterx-core' ),
			],
			'default' => 'center center',
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type' => 'gradient',
		];

		$fields['box_font_color'] = [
			'label' => esc_html__( 'Icon Color', 'jupiterx-core' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{SELECTOR}}' => 'color: {{VALUE}}',
				'{{SELECTOR}} svg *' => 'fill: {{VALUE}}',
				'{{SELECTOR}} i' => 'color: {{VALUE}}',
			],
		];

		$fields['box_font_size'] = [
			'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'em',
				'rem',
			],
			'responsive' => true,
			'range' => [
				'px' => [
					'min' => 5,
					'max' => 500,
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				'{{SELECTOR}} i' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				'{{SELECTOR}}:before' => 'font-size: {{SIZE}}{{UNIT}}',
				'{{SELECTOR}} svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
			],
		];

		$fields['box_size'] = [
			'label' => esc_html__( 'Box Size', 'jupiterx-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'range' => [
				'px' => [
					'min' => 5,
					'max' => 500,
				],
			],
			'responsive' => true,
			'selectors' => [
				'{{SELECTOR}}' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		];

		$fields['box_border'] = [
			'label' => esc_html_x( 'Border Type', 'Border Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'' => esc_html__( 'None', 'jupiterx-core' ),
				'solid' => esc_html_x( 'Solid', 'Border Control', 'jupiterx-core' ),
				'double' => esc_html_x( 'Double', 'Border Control', 'jupiterx-core' ),
				'dotted' => esc_html_x( 'Dotted', 'Border Control', 'jupiterx-core' ),
				'dashed' => esc_html_x( 'Dashed', 'Border Control', 'jupiterx-core' ),
			],
			'selectors' => [
				'{{SELECTOR}}' => 'border-style: {{VALUE}};',
			],
		];

		$fields['box_border_width'] = [
			'label' => esc_html_x( 'Width', 'Border Control', 'jupiterx-core' ),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'box_border!' => '',
			],
		];

		$fields['box_border_color'] = [
			'label' => esc_html_x( 'Color', 'Border Control', 'jupiterx-core' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{SELECTOR}}' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'box_border!' => '',
			],
		];

		$fields['box_border_radius'] = [
			'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{SELECTOR}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$fields['allow_box_shadow'] = [
			'label' => esc_html_x( 'Box Shadow', 'Box Shadow Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
			'label_off' => esc_html__( 'No', 'jupiterx-core' ),
			'return_value' => 'yes',
			'separator' => 'before',
			'render_type' => 'ui',
		];

		$fields['box_shadow'] = [
			'label' => esc_html_x( 'Box Shadow', 'Box Shadow Control', 'jupiterx-core' ),
			'type' => Controls_Manager::BOX_SHADOW,
			'condition' => [
				'allow_box_shadow!' => '',
			],
			'selectors' => [
				'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			],
		];

		$fields['box_shadow_position'] = [
			'label' => esc_html_x( 'Position', 'Box Shadow Control', 'jupiterx-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				' ' => esc_html_x( 'Outline', 'Box Shadow Control', 'jupiterx-core' ),
				'inset' => esc_html_x( 'Inset', 'Box Shadow Control', 'jupiterx-core' ),
			],
			'condition' => [
				'allow_box_shadow!' => '',
			],
			'default' => ' ',
			'render_type' => 'ui',
		];

		return $fields;
	}

	protected function prepare_fields( $fields ) {
		array_walk( $fields, function ( &$field, $field_name ) {
			if ( in_array( $field_name, [ 'popover_toggle' ], true ) ) {
				return;
			}

			$condition = [
				'popover_toggle!' => '',
			];

			if ( isset( $field['condition'] ) ) {
				$field['condition'] = array_merge( $field['condition'], $condition );
			} else {
				$field['condition'] = $condition;
			}
		} );

		return parent::prepare_fields( $fields );
	}
}
