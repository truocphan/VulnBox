<?php
$atts               = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_plan_container = '';

extract(
	shortcode_atts(
		array(
			'css_plan_container' => '',
		),
		$atts
	)
);

$atts['css_plan_container'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css_plan_container, ' ' ), $atts );

STM_LMS_Templates::show_lms_template( 'shortcodes/stm_membership_levels', $atts );
