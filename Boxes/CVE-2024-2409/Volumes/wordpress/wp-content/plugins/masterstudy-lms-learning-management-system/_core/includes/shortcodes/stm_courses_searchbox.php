<?php
add_shortcode( 'stm_courses_searchbox', 'stm_courses_searchbox_shortcode' );

function stm_courses_searchbox_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'style' => 'style_1',
		),
		$atts
	);

	return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_courses_searchbox', $atts );
}
