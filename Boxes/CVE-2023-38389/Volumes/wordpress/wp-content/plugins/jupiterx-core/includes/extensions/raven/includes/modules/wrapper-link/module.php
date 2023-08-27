<?php

namespace JupiterX_Core\Raven\Modules\Wrapper_Link;

use JupiterX_Core\Raven\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/container/section_effects/after_section_end', [ $this, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'add_controls_section' ], 1 );

		add_action( 'elementor/frontend/before_render', [ $this, 'before_section_render' ], 1 );
	}

	public function add_controls_section( Element_Base $element ) {
		$element->start_controls_section(
			'_section_raven_wrapper_link',
			[
				'label' => esc_html__( 'Wrapper Link', 'jupiterx-core' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'raven_element_link',
			[
				'label'       => esc_html__( 'Link', 'jupiterx-core' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => 'https://example.com',
			]
		);

		$element->end_controls_section();
	}

	public static function before_section_render( Element_Base $element ) {
		$link_settings = $element->get_settings_for_display( 'raven_element_link' );

		if ( $link_settings && ! empty( $link_settings['url'] ) ) {
			$element->add_render_attribute(
				'_wrapper',
				[
					'data-raven-element-link' => wp_json_encode( $link_settings ),
					'style' => 'cursor: pointer',
				]
			);
		}
	}
}
