<?php

/**
 * @var $course_id
 * @var $expired_popup
 */

stm_lms_register_style( 'expiration/main' );

$is_course_in_upcoming         = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $course_id );
$is_course_previously_upcoming = get_post_meta( $course_id, 'coming_soon_date', true );
$user_id                       = get_current_user_id();
$course_expiration_days        = STM_LMS_Course::get_course_expiration_days( $course_id );
$is_course_time_expired        = STM_LMS_Course::is_course_time_expired( $user_id, $course_id );
$user_course                   = STM_LMS_Course::get_user_course( $user_id, $course_id );

$has_course_time_limit   = get_transient( 'masterstudy_lms_course_upcoming_time_expiration' . $course_id );
$should_reset_start_time = ! empty( $is_course_previously_upcoming ) && $has_course_time_limit;

if ( $is_course_in_upcoming ) {
	$user_course = array();
	set_transient( 'masterstudy_lms_course_upcoming_time_expiration' . $course_id, true );
} elseif ( $should_reset_start_time ) {
	stm_lms_update_start_time_in_user_course( $user_id, $course_id );
	set_transient( 'masterstudy_lms_course_upcoming_time_expiration' . $course_id, false );
}

$course_duration_time = STM_LMS_Course::get_course_duration_time( $course_id );
$course_end_time      = ! empty( $course_duration_time ) && ! empty( $user_course['start_time'] )
	? intval( $user_course['start_time'] ) + $course_duration_time
	: null;

$expired_popup = ( isset( $expired_popup ) ) ? $expired_popup : true;

/*If we just telling about course expiration info*/
if ( empty( $user_course ) && ! empty( $course_expiration_days ) ) {
	STM_LMS_Templates::show_lms_template( 'expiration/info', compact( 'course_expiration_days' ) );

	/*If we have course and time is not expired*/
} elseif ( ! $is_course_time_expired && ! empty( $user_course ) && ! empty( $course_expiration_days ) ) {
	STM_LMS_Templates::show_lms_template( 'expiration/not_expired', compact( 'course_id', 'course_end_time' ) );

	/*If we have course and time is expired*/
} elseif ( $is_course_time_expired && ! empty( $user_course ) && ! empty( $course_expiration_days ) ) {
	STM_LMS_Templates::show_lms_template( 'expiration/info', compact( 'course_expiration_days' ) );

	if ( $expired_popup && ! $should_reset_start_time ) {
		STM_LMS_Templates::show_lms_template( 'expiration/expired', compact( 'course_id', 'course_end_time' ) );
	}
}
