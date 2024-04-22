<?php

extract(
	shortcode_atts(
		array(
			'css'   => '',
			'style' => 'style_1',
		),
		$atts
	)
);


$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( empty( $style ) ) {
	$style = 'style_1';
}

$atts['css_class'] = $css_class;
$atts['style']     = $style;

STM_LMS_Templates::show_lms_template( 'shortcodes/stm_courses_searchbox', $atts );

