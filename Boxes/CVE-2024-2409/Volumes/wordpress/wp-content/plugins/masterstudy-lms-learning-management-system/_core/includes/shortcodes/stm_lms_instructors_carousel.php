<?php
add_shortcode( 'stm_lms_instructors_carousel', 'stm_lms_instructors_carousel_shortcode' );

function stm_lms_instructors_carousel_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'       => '',
			'per_row'     => '6',
			'per_row_md'  => '4',
			'per_row_sm'  => '2',
			'per_row_xs'  => '1',
			'title_color' => '',
			'style'       => 'style_1',
			'sort'        => '',
			'prev_next'   => 'enable',
			'pagination'  => 'disable',
		),
		$atts
	);

	$uniq         = stm_lms_create_unique_id( $atts );
	$atts['uniq'] = $uniq;

	return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_lms_instructors_carousel', $atts );
}
