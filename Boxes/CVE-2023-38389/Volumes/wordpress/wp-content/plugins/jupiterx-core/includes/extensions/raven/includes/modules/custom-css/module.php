<?php

namespace JupiterX_Core\Raven\Modules\CUSTOM_CSS;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\DynamicTags\Dynamic_CSS;
use Elementor\Core\Files\CSS\Post;
use Elementor\Element_Base;
use Elementor\Plugin;

class Module extends Module_Base {

	public function __construct() {

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return;
		}

		$this->add_actions();
	}

	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/element/parse_css', [ $this, 'add_post_css' ], 10, 2 );
		add_action( 'elementor/css-file/post/parse', [ $this, 'add_page_settings_css' ] );
	}

	public function get_name() {
		return 'raven-custom-css';
	}

	public function register_controls( Controls_Stack $element, $section_id ) {
		// Remove Custom CSS Banner (From free version).
		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		$this->replace_go_pro_custom_css_controls( $element );
	}

	public function add_post_css( $post_css, $element ) {
		if ( $post_css instanceof Dynamic_CSS ) {
			return;
		}

		$element_settings = $element->get_settings();

		if ( empty( $element_settings['raven_custom_css_widget'] ) ) {
			return;
		}

		$css = trim( $element_settings['raven_custom_css_widget'] );

		if ( empty( $css ) ) {
			return;
		}

		$css = str_replace(
			'selector',
			$post_css->get_element_unique_selector( $element ),
			$css
		);
		$css = sprintf(
			'/* Start custom CSS for %s, class: %s */',
			$element->get_name(),
			$element->get_unique_selector()
		) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $css );
	}

	public function add_page_settings_css( $post_css ) {
		$document   = Plugin::$instance->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'raven_custom_css_widget' );
		$custom_css = ! empty( $custom_css ) ? trim( $custom_css ) : '';

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	public function replace_go_pro_custom_css_controls( $controls_stack ) {
		$old_section = Plugin::$instance->controls_manager->get_control_from_stack( $controls_stack->get_unique_name(), 'section_custom_css_pro' );

		Plugin::$instance->controls_manager->remove_control_from_stack( $controls_stack->get_unique_name(), [ 'section_custom_css_pro', 'custom_css_pro' ] );

		$controls_stack->start_controls_section(
			'section_custom_css',
			[
				'label' => esc_html__( 'Custom CSS', 'jupiterx-core' ),
				'tab' => $old_section['tab'],
			]
		);

		$controls_stack->add_control(
			'custom_css_title',
			[
				'raw' => esc_html__( 'Add your own custom CSS here', 'jupiterx-core' ),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$controls_stack->add_control(
			'raven_custom_css_widget',
			[
				'type' => Controls_Manager::CODE,
				'label' => esc_html__( 'Custom CSS', 'jupiterx-core' ),
				'language' => 'css',
				'render_type' => 'ui',
				'show_label' => false,
				'separator' => 'none',
			]
		);

		$controls_stack->add_control(
			'custom_css_description',
			[
				'raw' => sprintf(
				/* translators: 1: Break line tag. */
					esc_html__( 'Use "selector" to target wrapper element. Examples:%1$sselector {color: red;} // For main element%1$sselector .child-element {margin: 10px;} // For child element%1$s.my-class {text-align: center;} // Or use any custom selector', 'jupiterx-core' ),
					'<br>'
				),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$controls_stack->end_controls_section();
	}

	public function localize_settings() {
		Plugin::$instance->modules_manager->get_modules( 'dev-tools' )->deprecation->deprecated_function( __METHOD__, '3.1.0' );

		return [];
	}
}
