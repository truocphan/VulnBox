<?php

require_once STM_LMS_PRO_ADDONS . '/point_system/settings.php';
require_once STM_LMS_PRO_ADDONS . '/point_system/points.php';
require_once STM_LMS_PRO_ADDONS . '/point_system/db.php';
require_once STM_LMS_PRO_ADDONS . '/point_system/interface.php';
require_once STM_LMS_PRO_ADDONS . '/point_system/points_history.php';
require_once STM_LMS_PRO_ADDONS . '/point_system/statistics.php';
require_once STM_LMS_PRO_ADDONS . '/point_system/affiliate_points.php';

new STM_LMS_Point_System();

class STM_LMS_Point_System {

	public function __construct() {
		add_action( 'stm_lms_user_registered', array( $this, 'user_registered' ), 10, 1 );
		add_action( 'add_user_course', array( $this, 'add_user_course' ), 10, 2 );
		add_action( 'stm_lms_lesson_passed', array( $this, 'lesson_passed' ), 10, 2 );
		add_action( 'stm_lms_quiz_passed', array( $this, 'quiz_passed' ), 10, 3 );
		add_action( 'stm_lms_assignment_passed', array( $this, 'assignment_passed' ), 10, 2 );
		add_action( 'stm_lms_certificate_generated', array( $this, 'certificate_generated' ), 10, 2 );

		/*Buddypress*/
		add_action( 'groups_join_group', array( $this, 'groups_join_group' ), 10, 2 );
		add_action( 'friends_friendship_accepted', array( $this, 'friends_friendship_accepted' ), 10, 3 );

		add_action( 'wp_ajax_stm_lms_buy_for_points', array( $this, 'buy_for_points' ) );

		add_filter( 'stm_lms_template_name', array( $this, 'button' ), 100, 2 );

		add_action(
			'admin_init',
			function () {
				stm_lms_point_system_table();
			}
		);
	}

	public function user_registered( $user_id ) {
		self::add_points( $user_id, $user_id, 'user_registered' );
	}

	public function add_user_course( $user_id, $course_id ) {
		self::add_points( $user_id, $course_id, 'course_purchased' );
	}

	public function lesson_passed( $user_id, $lesson_id ) {
		self::add_points( $user_id, $lesson_id, 'lesson_passed' );
	}

	public function quiz_passed( $user_id, $quiz_id, $progress ) {
		self::add_points( $user_id, $quiz_id, 'quiz_passed' );

		if ( 100 === intval( $progress ) ) {
			self::add_points( $user_id, $quiz_id, 'perfect_quiz' );
		}
	}

	public function assignment_passed( $user_id, $assignment_id ) {
		self::add_points( $user_id, $assignment_id, 'assignment_passed' );
	}

	public function certificate_generated( $user_id, $course_id ) {
		$added = stm_lms_check_point_added( $user_id, $course_id, 'certificate_received' );
		if ( ! $added ) {
			self::add_points( $user_id, $course_id, 'certificate_received' );
		}

	}

	public function groups_join_group( $group_id, $user_id ) {
		$added = stm_lms_check_point_added( $user_id, $group_id, 'group_joined' );
		if ( ! $added ) {
			self::add_points( $user_id, $group_id, 'group_joined' );
		}

	}

	public function friends_friendship_accepted( $friendship_id, $initiator_id, $friend_id ) {
		/*First Friend points*/
		$added = stm_lms_check_point_added( $initiator_id, $friendship_id, 'friends_friendship_accepted' );
		if ( ! $added ) {
			self::add_points( $initiator_id, $friendship_id, 'friends_friendship_accepted' );
		}

		/*Second friend points*/
		$added = stm_lms_check_point_added( $friend_id, $friendship_id, 'friends_friendship_accepted' );
		if ( ! $added ) {
			self::add_points( $friend_id, $friendship_id, 'friends_friendship_accepted' );
		}

	}

	public static function add_points( $user_id, $id, $action_id ) {
		$actions = stm_lms_point_system();
		if ( empty( $actions[ $action_id ] ) ) {
			return;
		}

		$action = $actions[ $action_id ];

		do_action( "stm_lms_score_charge_{$action_id}", $user_id, $action_id, $action['score'], time() );

		stm_lms_add_user_points( $user_id, $id, $action_id, $action['score'], time() );

	}

	public static function total_points( $user_id ) {
		if ( empty( $user_id ) ) {
			$user = STM_LMS_User::get_current_user();
			if ( empty( $user['id'] ) ) {
				return 0;
			}
			$user_id = $user['id'];
		}

		$total = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_points( $user_id ) );
		$total = ( ! empty( $total['total'] ) ) ? $total['total'] : 0;

		return $total;

	}

	public static function get_label() {
		$options = get_option( 'stm_lms_point_system_settings', array() );

		return ( ! empty( $options['point_label'] ) ) ? $options['point_label'] : '';
	}

	public static function display_points( $points ) {
		$label = self::get_label();

		return apply_filters( 'stm_lms_display_points', "{$points} {$label}" );

	}

	public static function display_point_image() {
		$options = get_option( 'stm_lms_point_system_settings', array() );

		if ( ! empty( $options['point_image'] ) ) {
			$image_src = wp_get_attachment_image_src( $options['point_image'] );
			return "<img src='{$image_src[0]}' width='40' height='40' />";
		} else {
			return '<img src="' . esc_attr( STM_LMS_PRO_URL . '/assets/img/ms-coins-logo.svg' ) . '" width="40" height="40">';
		}

	}

	public static function course_price( $course_id ) {
		$course_price = get_post_meta( $course_id, 'points_price', true );

		if ( ! empty( $course_price ) || '0' === $course_price ) {
			return intval( $course_price );
		}

		$options = get_option( 'stm_lms_point_system_settings', array() );
		$rate    = ( ! empty( $options['point_rate'] ) ) ? $options['point_rate'] : '';
		$price   = STM_LMS_Course::get_course_price( $course_id );

		if ( ! empty( $rate ) ) {
			return $price * $rate;
		}
	}

	public function buy_for_points() {
		check_ajax_referer( 'stm_lms_buy_for_points', 'nonce' );

		$user      = STM_LMS_User::get_current_user();
		$user_id   = $user['id'];
		$course_id = intval( $_GET['course_id'] );

		$course_price = self::course_price( $course_id );
		$my_points    = intval( self::total_points( $user_id ) );

		$r = array(
			'url' => get_the_permalink( $course_id ),
		);

		$user_course = stm_lms_get_user_course( $user_id, $course_id, array( 'user_course_id' ) );

		if ( $my_points >= $course_price && empty( $user_course ) ) {
			STM_LMS_Course::add_user_course( $course_id, $user_id, 0, 0, false, '', '', '', 'for_points' );
			STM_LMS_Course::add_student( $course_id );

			stm_lms_add_user_points( $user_id, $course_id, 'course_bought', "-{$course_price}", time() );
		}

		$r['url'] = STM_LMS_Course::item_url( $course_id, STM_LMS_Lesson::get_first_lesson( $course_id ) );

		wp_send_json( $r );
	}

	public function button( $template_name, $vars ) {
		switch ( $template_name ) {
			case ( '/stm-lms-templates/global/buy-button.php' ):
				$template_name = '/stm-lms-templates/global/buy-button/mixed.php';
				break;
			default:
				break;
		}
		return $template_name;
	}

}
