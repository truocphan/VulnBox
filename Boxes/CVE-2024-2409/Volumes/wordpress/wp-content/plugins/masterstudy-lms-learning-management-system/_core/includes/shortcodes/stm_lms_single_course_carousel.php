<?php
add_shortcode( 'stm_lms_single_course_carousel', 'stm_lms_single_course_carousel_shortcode' );

function stm_lms_single_course_carousel_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'query'      => 'none',
			'prev_next'  => 'enable',
			'pagination' => 'disable',
			'taxonomy'   => '',
			'uniq'       => '',
		),
		$atts
	);

	return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_lms_single_course_carousel', $atts );
}
