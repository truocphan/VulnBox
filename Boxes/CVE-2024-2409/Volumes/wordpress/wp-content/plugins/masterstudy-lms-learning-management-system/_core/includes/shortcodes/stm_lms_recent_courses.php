<?php
add_shortcode( 'stm_lms_recent_courses', 'stm_lms_recent_courses_shortcode' );
/**
 * Shortcode function for rendering a course grid with customizable attributes.
 *
 * @param array $atts An array of attributes passed to the shortcode.
 * @return string The HTML output of the course grid.
 */
function stm_lms_recent_courses_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'posts_per_page'       => '',
			'image_size'           => '',
			'per_row'              => '6',
			'course_card_info'     => 'center',
			'course_card_style'    => 'style_1',
			'img_container_height' => '',
		),
		$atts
	);

	return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_lms_recent_courses', $atts );
}
