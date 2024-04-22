<?php
add_shortcode( 'stm_lms_courses_categories', 'stm_lms_courses_categories_shortcode' );

function stm_lms_courses_categories_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'taxonomy' => '',
			'style'    => 'style_1',
		),
		$atts
	);

	ob_start();
	STM_LMS_Templates::stm_lms_load_vc_element( 'courses_categories', $atts, $atts['style'] );

	return ob_get_clean();
}
