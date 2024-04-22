<?php
function masterstudy_authorization_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'modal'               => false,
			'type'                => 'login',
			'is_instructor'       => STM_LMS_Instructor::is_instructor(),
			'only_for_instructor' => false,
			'dark_mode'           => false,
		),
		$atts
	);

	return STM_LMS_Templates::load_lms_template( 'components/authorization/main', $atts );
}
add_shortcode( 'masterstudy_authorization_form', 'masterstudy_authorization_form_shortcode' );

function masterstudy_instructor_registration_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'modal'               => false,
			'type'                => 'register',
			'is_instructor'       => STM_LMS_Instructor::is_instructor(),
			'only_for_instructor' => true,
			'dark_mode'           => false,
		),
		$atts
	);

	return STM_LMS_Templates::load_lms_template( 'components/authorization/main', $atts );
}
add_shortcode( 'masterstudy_instructor_registration', 'masterstudy_instructor_registration_shortcode' );
