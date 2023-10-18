<?php

namespace WprAddons\Admin\Includes;

use Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Templates_Shortcode setup
 *
 * @since 1.0
 */
class WPR_Templates_Shortcode {

	public function __construct() {
		add_shortcode( 'wpr-template', [ $this, 'shortcode' ] );

		add_action('elementor/element/after_section_start', [ $this, 'extend_shortcode' ], 10, 3 );
	}

	public function shortcode( $attributes = [] ) {
		if ( empty( $attributes['id'] ) ) {
			return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. esc_url(get_permalink($attributes['id'])) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $attributes['id'], $has_css ) . $edit_link;
	}

	public function extend_shortcode( $section, $section_id, $args ) {
		if ( $section->get_name() == 'shortcode' && $section_id == 'section_shortcode' ) {
			$section->add_control(
				'select_template' ,
				[
					'label' => esc_html__( 'Select Template', 'wpr-addons' ),
					'type' => 'wpr-ajax-select2',
					'options' => 'ajaxselect2/get_elementor_templates',
					'label_block' => true,
				]
			);
		}
	}

}