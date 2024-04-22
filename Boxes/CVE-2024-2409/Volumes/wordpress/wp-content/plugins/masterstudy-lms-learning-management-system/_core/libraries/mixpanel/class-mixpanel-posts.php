<?php

namespace MasterStudy\Lms\Libraries;

use MasterStudy\Lms\Database\CurriculumMaterial;
use MasterStudy\Lms\Database\CurriculumSection;

class Mixpanel_Posts extends Mixpanel {

	private static $published_post_types = array(
		'stm-courses'     => 'Courses count',
		'stm-lessons'     => 'Lessons count',
		'stm-quizzes'     => 'Quizzes count',
		'stm-assignments' => 'Assignments count',
	);

	private static $draft_post_types = array(
		'stm-courses'     => 'Draft Courses Count',
		'stm-lessons'     => 'Draft Lessons Count',
		'stm-quizzes'     => 'Draft Quizzes Count',
		'stm-assignments' => 'Draft Assignments Count',
	);

	private static $question_types = array(
		'single_choice' => 'Questions Single Choice Count',
		'multi_choice'  => 'Questions Multi Choice Count',
		'true_false'    => 'Questions True/False Count',
		'item_match'    => 'Questions Item Match Count',
		'image_match'   => 'Questions Image Match Count',
		'keywords'      => 'Questions Keywords Count',
		'fill_the_gap'  => 'Questions Fill in the Gap Count',
	);

	private static $lesson_types = array(
		'video'           => 'Lesson Type Video Count',
		'text'            => 'Lesson Type Text Count',
		'stream'          => 'Lesson Type Stream Count',
		'zoom_conference' => 'Lesson Type Zoom Conference Count',
	);

	private static $lesson_video_types = array(
		'html'      => 'Video Type HTML Count',
		'youtube'   => 'Video Type YouTube Count',
		'vimeo'     => 'Video Type Vimeo Count',
		'ext_link'  => 'Video Type External Link Count',
		'embed'     => 'Video Type Embed Count',
		'shortcode' => 'Video Type Shortcode Count',
	);

	public static function register_data() {
		foreach ( self::$published_post_types as $slug => $label ) {
			self::add_data( $label, self::get_custom_posts_count( $slug ) );
		}
		foreach ( self::$draft_post_types as $slug => $label ) {
			self::add_data( $label, self::get_custom_posts_count( $slug, 'draft' ) );
		}

		self::add_data( 'First Course Creation Date', self::get_oldest_post( 'stm-courses' ) );

		if ( is_ms_lms_addon_enabled( 'sequential_drip_content' ) ) {
			self::add_data( 'Courses With Drip-Content', self::count_is_addon_used_in_courses( 'drip_content' ) );
		}

		if ( is_ms_lms_addon_enabled( 'prerequisite' ) ) {
			self::add_data( 'Courses With Prerequisites', self::count_is_addon_used_in_courses( 'prerequisites' ) );
		}

		if ( is_ms_lms_addon_enabled( 'certificate_builder' ) ) {
			self::add_data( 'Certificates Count', self::get_custom_posts_count( 'stm-certificates' ) );
		}

		self::add_data( 'Average Number of Questions in Quizzes', self::get_questions_in_quizzes_count() );

		foreach ( self::$question_types as $type => $label ) {
			self::add_data( $label, self::get_question_types_count( $type ) );
		}

		foreach ( self::$lesson_types as $type => $label ) {
			self::add_data( $label, self::get_lesson_types_count( $type ) );
		}

		foreach ( self::$lesson_video_types as $type => $label ) {
			self::add_data( $label, self::get_lesson_types_count( $type, true ) );
		}

		self::add_data( 'Average Number of Sections in Course', self::get_average_sections_count() );
		self::add_data( 'Lesson Multi Usage', self::get_lessons_multi_usage() );
	}

	public static function get_custom_posts_count( $post_type, $post_status = 'publish' ) {
		$posts_count = get_posts(
			array(
				'post_type'      => $post_type,
				'posts_per_page' => - 1,
				'post_status'    => $post_status,
			)
		);

		wp_reset_postdata();

		return count( $posts_count );
	}

	public static function get_oldest_post( $post_type ) {
		$oldest_post = get_posts(
			array(
				'post_type'   => $post_type,
				'order_by'    => 'publish_date',
				'order'       => 'ASC',
				'numberposts' => 1,
				'post_status' => 'publish',
			)
		);

		wp_reset_postdata();

		return $oldest_post[0]->post_date ?? '';
	}

	public static function count_is_addon_used_in_courses( $addon ) {
		$post_ids = self::get_custom_posts_ids( 'stm-courses' );
		$i        = 0;

		foreach ( $post_ids as $post_id ) {
			if ( ! empty( get_post_meta( $post_id, $addon, true ) ) ) {
				$i ++;
			}
		}

		wp_reset_postdata();

		return $i;
	}

	public static function get_questions_in_quizzes_count() {
		$posts_with_questions = array();
		$questions_count      = 0;
		$post_ids             = self::get_custom_posts_ids( 'stm-quizzes' );

		foreach ( $post_ids as $post_id ) {
			$posts_with_questions[] = get_post_meta( $post_id, 'questions', true );
		}

		foreach ( $posts_with_questions as $value ) {
			$questions_count += count( explode( ',', $value ) );
		}

		wp_reset_postdata();

		return ! empty( $posts_with_questions ) && 0 !== ( $questions_count ) ? $questions_count / count( $posts_with_questions ) : 0;
	}

	public static function get_question_types_count( $question_type ) {
		$questions_array = array();

		$post_ids = self::get_custom_posts_ids( 'stm-questions' );

		foreach ( $post_ids as $post_id ) {
			$questions_array[] = get_post_meta( $post_id, 'type', true );
		}

		$questions_array = array_count_values( $questions_array );

		wp_reset_postdata();

		return ! empty( $questions_array ) && isset( $questions_array[ $question_type ] ) ? $questions_array[ $question_type ] : 0;
	}

	public static function get_lesson_types_count( $lesson_type, $search_for_video_types = false ) {
		$lessons_array = array();
		$post_ids      = self::get_custom_posts_ids( 'stm-lessons' );

		foreach ( $post_ids as $post_id ) {
			$type            = ( true === $search_for_video_types && 'video' === get_post_meta( $post_id, 'type', true ) )
				? 'video_type'
				: 'type';
			$lessons_array[] = get_post_meta( $post_id, $type, true );
		}

		$lessons_array = array_count_values( $lessons_array );

		wp_reset_postdata();

		return ! empty( $lessons_array ) && isset( $lessons_array[ $lesson_type ] ) ? $lessons_array[ $lesson_type ] : 0;
	}

	public static function get_average_sections_count() {
		$course_ids     = self::get_custom_posts_ids( 'stm-courses' );
		$sections_count = ( new CurriculumSection() )->query()->find( true );

		wp_reset_postdata();

		return ! empty( $sections_count ) ? round( $sections_count / count( $course_ids ), 2 ) : 0;
	}

	public static function get_lessons_multi_usage() {
		$lessons  = ( new CurriculumMaterial() )->query()->select( 'post_id' )->find( false, 'ARRAY' );
		$post_ids = array_count_values( array_column( $lessons, 'post_id' ) );

		return ! empty( $post_ids ) ? max( $post_ids ) > 1 : 0;
	}
}
