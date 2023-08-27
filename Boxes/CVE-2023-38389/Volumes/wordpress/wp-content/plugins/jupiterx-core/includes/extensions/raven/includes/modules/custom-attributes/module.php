<?php

namespace JupiterX_Core\Raven\Modules\Custom_Attributes;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Element_Base;
use Elementor\Utils;

class Module extends Module_Base {

	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes', [ $this, 'render_attributes' ] );
	}

	public function register_controls( \Elementor\Controls_Stack $controls_stack, $section_id ) {
		if ( 'section_advanced' !== $section_id && '_section_style' !== $section_id ) {
			return;
		}

		$controls_stack->start_controls_section(
			'section_raven_custom_attributes',
			[
				'label' => __( 'Attributes', 'jupiterx-core' ),
				'tab' => 'advanced',
			]
		);

		$controls_stack->add_control(
			'raven_custom_attributes',
			[
				'type' => 'textarea',
				'label' => __( 'Custom Attributes', 'jupiterx-core' ),
				'placeholder' => 'key|value',
				'render_type' => 'ui',
				'show_label' => true,
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$controls_stack->add_control(
			'raven_custom_attributes_how_to_use',
			[
				'type' => 'raw_html',
				'raw' => __( 'Set custom attributes for the wrapper element. Each attribute in a separate line. Separate attribute key from the value using | character.', 'jupiterx-core' ),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$controls_stack->end_controls_section();
	}

	private function get_black_list_attributes() {
		return [ 'id', 'class', 'data-id', 'data-settings', 'data-element_type', 'data-widget_type', 'data-model-cid' ];
	}

	public function render_attributes( Element_Base $element ) {
		$settings = $element->get_settings_for_display();

		if ( empty( $settings['raven_custom_attributes'] ) ) {
			return;
		}

		$attributes = Utils::parse_custom_attributes( $settings['raven_custom_attributes'], "\n" );
		$black_list = $this->get_black_list_attributes();

		foreach ( $attributes as $attribute => $value ) {
			if ( ! in_array( $attribute, $black_list, true ) ) {
				$element->add_render_attribute( '_wrapper', $attribute, $value );
			}
		}
	}
}
