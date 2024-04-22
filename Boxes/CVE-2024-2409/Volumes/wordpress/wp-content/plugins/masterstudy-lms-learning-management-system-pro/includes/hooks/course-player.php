<?php
/**
 * Course Player Template Hooks
 */

/* Get zoom lesson data */
function masterstudy_course_player_lesson_zoom_data( $item_id ) {
	$settings = get_option( 'stm_lms_settings' );
	$user_id  = get_current_user_id();
	if ( is_user_logged_in() ) {
		$user_mode = get_user_meta( $user_id, 'masterstudy_course_player_theme_mode', true );
		$dark_mode = metadata_exists( 'user', $user_id, 'masterstudy_course_player_theme_mode' ) ? $user_mode : $settings['course_player_theme_mode'] ?? false;
	} else {
		$dark_mode = $settings['course_player_theme_mode'] ?? false;
	}

	$data = array(
		'meeting_id'  => get_post_meta( $item_id, 'meeting_created', true ),
		'content'     => masterstudy_course_player_get_content( $item_id, true ),
		'dark_mode'   => $dark_mode,
		'theme_fonts' => $settings['course_player_theme_fonts'] ?? false,
	);

	return $data;
}
add_filter( 'masterstudy_course_player_lesson_zoom_data', 'masterstudy_course_player_lesson_zoom_data', 10, 1 );

/* Get google meet lesson data */
function masterstudy_course_player_lesson_google_data( $item_id, $post_id ) {
	$settings = get_option( 'stm_lms_settings' );
	$user_id  = get_current_user_id();
	if ( is_user_logged_in() ) {
		$user_mode = get_user_meta( $user_id, 'masterstudy_course_player_theme_mode', true );
		$dark_mode = metadata_exists( 'user', $user_id, 'masterstudy_course_player_theme_mode' ) ? $user_mode : $settings['course_player_theme_mode'] ?? false;
	} else {
		$dark_mode = $settings['course_player_theme_mode'] ?? false;
	}

	return array(
		'meet_started' => masterstudy_lms_is_google_meet_started( $item_id ),
		'description'  => get_post_meta( $item_id, 'stm_gma_summary', true ),
		'start_date'   => masterstudy_lms_get_google_meet_date_time( $item_id, true ),
		'end_date'     => masterstudy_lms_get_google_meet_date_time( $item_id, false ),
		'start_time'   => masterstudy_lms_google_meet_start_time( $item_id ),
		'author_email' => get_the_author_meta( 'user_email', get_post_field( 'post_author', $post_id ) ),
		'meet_url'     => get_post_meta( $item_id, 'google_meet_link', true ),
		'theme_fonts'  => $settings['course_player_theme_fonts'] ?? false,
		'dark_mode'    => $dark_mode,
	);
}
add_filter( 'masterstudy_course_player_lesson_google_data', 'masterstudy_course_player_lesson_google_data', 10, 2 );

/* Get stream lesson data */
function masterstudy_course_player_lesson_stream_data( $item_id ) {
	$settings            = get_option( 'stm_lms_settings' );
	$user_id             = get_current_user_id();
	$data                = array(
		'stream_url'     => get_post_meta( $item_id, 'lesson_stream_url', true ),
		'stream_started' => STM_LMS_Live_Streams::is_stream_started( $item_id ),
		'start_date'     => STM_LMS_Live_Streams::get_stream_date( $item_id, true ),
		'end_date'       => STM_LMS_Live_Streams::get_stream_date( $item_id, false ),
		'start_time'     => STM_LMS_Live_Streams::stream_start_time( $item_id ),
		'content'        => masterstudy_course_player_get_content( $item_id ),
		'theme_fonts'    => $settings['course_player_theme_fonts'] ?? false,
	);
	$data['video_idx']   = apply_filters( 'ms_plugin_get_youtube_idx', $data['stream_url'] );
	$data['youtube_url'] = 'https://www.youtube.com/embed/' . $data['video_idx'] . '?&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1';
	if ( is_user_logged_in() ) {
		$user_mode         = get_user_meta( $user_id, 'masterstudy_course_player_theme_mode', true );
		$data['dark_mode'] = metadata_exists( 'user', $user_id, 'masterstudy_course_player_theme_mode' ) ? $user_mode : $settings['course_player_theme_mode'] ?? false;
	} else {
		$data['dark_mode'] = $settings['course_player_theme_mode'] ?? false;
	}
	if ( isset( $_SERVER['SERVER_NAME'] ) ) {
		$data['youtube_chat_url'] = 'https://www.youtube.com/live_chat?v=' . $data['video_idx'] . '&embed_domain=' . str_replace( 'www.', '', sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '&dark_theme=1';
	}

	return $data;
}
add_filter( 'masterstudy_course_player_lesson_stream_data', 'masterstudy_course_player_lesson_stream_data', 10, 1 );

/* Get assignment data */
function masterstudy_course_player_assignment_data( $item_id ) {
	$settings                 = get_option( 'stm_lms_settings' );
	$assignment_settings      = get_option( 'stm_lms_assignments_settings' );
	$allow_upload_attachments = $assignment_settings['assignments_allow_upload_attachments'] ?? true;
	if ( ! is_user_logged_in() ) {
		return array(
			'dark_mode'        => $settings['course_player_theme_mode'] ?? false,
			'current_template' => false,
			'user_id'          => '',
		);
	}
	$user_id = get_current_user_id();
	$data    = array(
		'user_id'                => $user_id,
		'student_attachments'    => array(),
		'instructor_attachments' => array(),
		'show_emoji'             => $settings['assignments_quiz_result_emoji_show'] ?? false,
		'template_data'          => array(
			'passed'    => STM_LMS_Assignments::has_passed_assignment( $item_id ),
			'reviewing' => STM_LMS_Assignments::has_reviewing_assignment( $item_id ),
			'draft'     => STM_LMS_Assignments::has_draft_assignment( $item_id ),
			'unpassed'  => STM_LMS_Assignments::has_unpassed_assignment( $item_id ),
		),
		'status_messages'        => array(
			'passed'    => __( 'You passed assignment.', 'masterstudy-lms-learning-management-system-pro' ),
			'reviewing' => __( 'Your assignment pending review', 'masterstudy-lms-learning-management-system-pro' ),
			'unpassed'  => __( 'You failed assignment.', 'masterstudy-lms-learning-management-system-pro' ),
		),
		'file_loader_readonly'   => array(
			'passed'    => true,
			'draft'     => false,
			'reviewing' => true,
			'unpassed'  => true,
		),
		'actual_link'            => STM_LMS_Assignments::get_current_url(),
		'retake'                 => STM_LMS_Assignments::get_attempts( $item_id ),
		'theme_fonts'            => $settings['course_player_theme_fonts'] ?? false,
	);

	$user_mode                = get_user_meta( $user_id, 'masterstudy_course_player_theme_mode', true );
	$data['dark_mode']        = metadata_exists( 'user', $user_id, 'masterstudy_course_player_theme_mode' ) ? $user_mode : $settings['course_player_theme_mode'] ?? false;
	$data['current_template'] = array_search( true, $data['template_data'] ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	$emoji_type               = 'unpassed' === $data['current_template'] ? 'assignments_quiz_failed_emoji' : 'assignments_quiz_passed_emoji';
	$data['emoji_name']       = STM_LMS_Options::get_option( $emoji_type );

	if ( ! empty( $data['current_template'] ) ) {
		$attachment_ids    = get_post_meta( $data['template_data'][ $data['current_template'] ]['id'], 'instructor_attachments', true );
		$data['editor_id'] = "masterstudy_course_player_assignments__{$data['template_data'][ $data['current_template'] ]['id']}";
		if ( $allow_upload_attachments ) {
			$data['student_attachments']    = STM_LMS_Assignments::get_draft_attachments( $data['template_data'][ $data['current_template'] ]['id'], 'student_attachments' );
			$data['instructor_attachments'] = STM_LMS_Assignments::get_draft_attachments( $data['template_data'][ $data['current_template'] ]['id'], 'instructor_attachments' );
		}
	}

	if ( 'passed' === $data['current_template'] || 'unpassed' === $data['current_template'] ) {
		$data['comment_author'] = STM_LMS_User::get_current_user( $data['template_data'][ $data['current_template'] ]['editor_id'] );
	}

	$data['content'] = masterstudy_course_player_get_content( $item_id, true );

	return $data;
}
add_filter( 'masterstudy_course_player_assignment_data', 'masterstudy_course_player_assignment_data', 10, 1 );

/* Get drip content data */
function masterstudy_course_player_drip_content_data() {
	$settings = get_option( 'stm_lms_settings' );
	$user_id  = get_current_user_id();
	if ( is_user_logged_in() ) {
		$user_mode = get_user_meta( $user_id, 'masterstudy_course_player_theme_mode', true );
		$dark_mode = metadata_exists( 'user', $user_id, 'masterstudy_course_player_theme_mode' ) ? $user_mode : $settings['course_player_theme_mode'] ?? false;
	} else {
		$dark_mode = $settings['course_player_theme_mode'] ?? false;
	}
	return array(
		'dark_mode'   => $dark_mode,
		'theme_fonts' => $settings['course_player_theme_fonts'] ?? false,
	);
}
add_filter( 'masterstudy_course_player_drip_content_data', 'masterstudy_course_player_drip_content_data', 10 );

function masterstudy_course_player_get_content( $post_id, $str_replace = false ) {
	$post    = get_post( $post_id );
	$content = '';

	if ( $post ) {
		$content = $post->post_content;
	}

	return $str_replace
		? str_replace( '../../', site_url() . '/', stm_lms_filtered_output( $content ) )
		: $content;
}
