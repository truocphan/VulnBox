<?php
add_shortcode( 'stm_lms_courses_carousel', 'stm_lms_courses_carousel_shortcode' );

/**
 * Shortcode function for rendering a course grid with customizable attributes.
 *
 * @param array $atts An array of attributes passed to the shortcode.
 * @return string The HTML output of the course grid.
 */
function stm_lms_courses_carousel_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'                => '',
			'title_color'          => '',
			'query'                => 'none',
			'prev_next'            => 'enable',
			'remove_border'        => 'disable',
			'pagination'           => 'disable',
			'show_categories'      => 'disable',
			'per_row'              => '6',
			'taxonomy'             => '',
			'taxonomy_default'     => '',
			'image_size'           => '',
			'course_card_info'     => 'center',
			'course_card_style'    => 'style_1',
			'img_container_height' => '',
		),
		$atts
	);

	$uniq         = stm_lms_create_unique_id( $atts );
	$atts['uniq'] = $uniq;

	return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_lms_courses_carousel', $atts );
}
