<?php
use \Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Custom_CSS {

	private static $instance = null;

	public function __construct() {
		// Add new controls to advanced tab globally
		add_action('elementor/element/after_section_end', [$this, 'add_section_custom_css_controls'], 25, 3);

		// Render the Custom CSS
		add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
	}

	public function add_section_custom_css_controls($widget, $section_id, $args) {
		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		$widget->start_controls_section(
			'wpr_section_custom_css',
			[
				'label' =>  sprintf(esc_html__('Custom CSS - %s', 'wpr-addons'), Utilities::get_plugin_name()),
				'tab' => Controls_Manager::TAB_ADVANCED
			]
		);

		$widget->add_control(
			'wpr_custom_css',
			[
				'type' => Controls_Manager::CODE,
				'label' => esc_html__('Custom CSS', 'wpr-addons'),
				'language' => 'css',
				'render_type' => 'ui',
				'label_block' => true,
			]
		);

		$widget->add_control(
			'wpr_custom_css_description',
			[
				'raw' => esc_html__('Use "selector" keyword to target wrapper element.', 'wpr-addons'),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator' => 'none'
			]
		);

		$widget->end_controls_section();
	}



	public function add_post_css($post_css, $element) {
		$element_settings = $element->get_settings();

		if ( empty($element_settings['wpr_custom_css']) ) {
			return;
		}

		$css = trim($element_settings['wpr_custom_css']);

		if ( empty($css) ) {
			return;
		}

		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		// Add a css comment
		$css = sprintf('/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector()) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css($css);
	}
}

new Wpr_Custom_CSS();
