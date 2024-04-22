<?php
add_shortcode( 'stm_lms_courses_grid', 'stm_lms_courses_grid_shortcode' );

/**
 * Shortcode function for rendering a course grid with customizable attributes.
 *
 * @param array $atts An array of attributes passed to the shortcode.
 * @return string The HTML output of the course grid.
 */
function stm_lms_courses_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'hide_top_bar'         => 'showing',
			'title'                => '',
			'hide_load_more'       => 'showing',
			'hide_sort'            => 'showing',
			'per_row'              => '6',
			'image_size'           => '',
			'taxonomy_default'     => '',
			'posts_per_page'       => '',
			'course_card_info'     => 'center',
			'course_card_style'    => 'style_1',
			'img_container_height' => '',
		),
		$atts
	);

	return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_lms_courses_grid', $atts );
}
