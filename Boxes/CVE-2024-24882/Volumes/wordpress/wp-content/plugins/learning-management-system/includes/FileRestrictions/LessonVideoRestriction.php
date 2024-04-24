<?php
/**
 * FileRestrictions class.
 *
 * @since 1.0.0
 */

namespace Masteriyo\FileRestrictions;

use Masteriyo\Abstracts\FileRestriction;
use Masteriyo\Enums\CourseAccessMode;

class LessonVideoRestriction extends FileRestriction {
	/**
	 * Run the lesson video file restriction.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		if ( ! isset( $_GET['masteriyo_lesson_vid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$this->validate_lesson_video_url();

		/**
		 * Action for validating video lesson URL. Current request URL is the video lesson URL.
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_validate_video_lesson_url' );

		$course = masteriyo_get_course( $_GET['course_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( CourseAccessMode::OPEN === $course->get_access_mode() ) {
			$this->send_lesson_video_file();
		}

		if ( ! is_user_logged_in() ) {
			$this->send_error( __( 'You are not allowed to access this file.', 'masteriyo' ), '', 403 );
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			$this->send_lesson_video_file();
		}

		if ( $course->get_author_id() === get_current_user_id() ) {
			$this->send_lesson_video_file();
		}

		if ( masteriyo_can_start_course( $course ) ) {
			$this->send_lesson_video_file();
		}

		$this->send_error( __( 'You are not allowed to access this file.', 'masteriyo' ), '', 403 );
	}

	/**
	 * Send the lesson video file.
	 *
	 * @since 1.0.0
	 */
	public function send_lesson_video_file() {
		$lesson = masteriyo_get_lesson( $_GET['lesson_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( is_null( $lesson ) ) {
			$this->send_error( __( 'File not found.', 'masteriyo' ) );
			return;
		}

		/**
		 * Fires before sending lesson video file to client.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
		 */
		do_action( 'masteriyo_before_send_lesson_video_file', $lesson );

		/**
		 * Filters boolean-like value: 'yes' if it should be redirected to the actual file URL of a lesson video, otherwise 'no'.
		 *
		 * @since 1.0.0
		 *
		 * @param string $is_redirect One of 'yes' or 'no'.
		 */
		if ( 'yes' === apply_filters( 'masteriyo_lesson_video_restriction_redirect_to_file', 'no' ) ) {
			$file_url = wp_get_attachment_url( $lesson->get_video_source_url( 'edit' ) );

			/**
			 * Filters self-hosted file URL of a lesson video.
			 *
			 * @since 1.0.0
			 *
			 * @param string $file_url Lesson video file URL.
			 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
			 */
			$file_url = apply_filters( 'masteriyo_self_hosted_lesson_video_fileurl', $file_url, $lesson );

			if ( ! is_string( $file_url ) || empty( $file_url ) ) {
				$this->send_error( __( 'File not found.', 'masteriyo' ) );
			}
			$this->redirect( $file_url );
		}

		$file_path = get_attached_file( $lesson->get_video_source_url( 'edit' ) );

		/**
		 * Filters self-hosted filepath of a lesson video.
		 *
		 * @since 1.0.0
		 *
		 * @param string $file_path Absolute path of lesson video file.
		 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
		 */
		$file_path = apply_filters( 'masteriyo_self_hosted_lesson_video_filepath', $file_path, $lesson );

		if ( ! is_string( $file_path ) || empty( $file_path ) ) {
			$this->send_error( __( 'File not found.', 'masteriyo' ) );
		}

		$this->send_file( $file_path );
	}

	/**
	 * Validate the lesson video URL.
	 *
	 * @since 1.0.0
	 */
	public function validate_lesson_video_url() {
		if ( empty( $_GET['course_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
		if ( empty( $_GET['lesson_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
		if ( is_null( masteriyo_get_course( $_GET['course_id'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
		if ( is_null( masteriyo_get_lesson( $_GET['lesson_id'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
	}
}
