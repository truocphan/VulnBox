<?php
/**
 * Assignment filters.
 */

use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Pro\addons\assignments\Assignments;
use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentStudentRepository;

/**
 * Adds custom columns to assignments post types.
 *
 * @param  array $posts - post types args to be registered.
 * @return array - returns post types args to be registered.
 */
function masterstudy_lms_assignments_post_type( $posts ) {
	$posts['stm-assignments'] = array(
		'single' => esc_html__( 'Assignment', 'masterstudy-lms-learning-management-system-pro' ),
		'plural' => esc_html__( 'Assignments', 'masterstudy-lms-learning-management-system-pro' ),
		'args'   => array(
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_menu'        => 'admin.php?page=stm-lms-settings',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'author' ),
		),
	);
	return $posts;
}
add_filter( 'stm_lms_post_types_array', 'masterstudy_lms_assignments_post_type', 10, 1 );

/**
 * Filters the columns displayed in the Posts list table for a specific post type.
 *
 * @param  array $columns - An associative array of column headings.
 * @return array - returns an associative array of column headings.
 */
function masterstudy_lms_assignments_columns( $columns ) {
	$columns['title']          = esc_html__( 'Assignment', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_course']     = esc_html__( 'Course', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_total']      = esc_html__( 'Total', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_passed']     = esc_html__( 'Passed', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_not_passed'] = esc_html__( 'Non Passed', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_pending']    = esc_html__( 'Pending', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_view']       = '';

	unset( $columns['author'] );
	unset( $columns['date'] );

	return $columns;
}
add_filter( 'manage_stm-assignments_posts_columns', 'masterstudy_lms_assignments_columns' );
add_filter( 'manage_edit-stm-assignments_sortable_columns', 'masterstudy_lms_assignments_columns' );

/**
 * Adds custom columns to assignments post types.
 *
 * @param  array $posts - post types args to be registered.
 * @return array - returns post types args to be registered.
 */
function masterstudy_lms_student_assignments_post_type( $posts ) {
	$posts['stm-user-assignment'] = array(
		'single' => esc_html__( 'Student Assignment', 'masterstudy-lms-learning-management-system-pro' ),
		'plural' => esc_html__( 'Student Assignments', 'masterstudy-lms-learning-management-system-pro' ),
		'args'   => array(
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_menu'        => 'admin.php?page=stm-lms-settings',
			'supports'            => array( 'title', 'editor' ),
		),
	);

	return $posts;
}
add_filter( 'stm_lms_post_types_array', 'masterstudy_lms_student_assignments_post_type', 10, 1 );

/**
 * Filters the columns displayed in the Posts list table for a specific post type.
 *
 * @param  array $columns - An associative array of column headings.
 * @return array - returns an associative array of column headings.
 */
function masterstudy_lms_student_assignments_columns( $columns ) {
	$columns['lms_student'] = esc_html__( 'Student name', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_course']  = esc_html__( 'Course', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_date']    = esc_html__( 'Date', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_attempt'] = esc_html__( 'Attempt', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_status']  = esc_html__( 'Status', 'masterstudy-lms-learning-management-system-pro' );
	$columns['lms_review']  = '';

	unset( $columns['title'] );
	unset( $columns['date'] );

	return $columns;
}
add_filter( 'manage_stm-user-assignment_posts_columns', 'masterstudy_lms_student_assignments_columns' );
add_filter( 'manage_edit-stm-user-assignment_sortable_columns', 'masterstudy_lms_student_assignments_columns' );

add_filter(
	'wpcfto_field_assignment_files',
	function () {
		return STM_LMS_PRO_ADDONS . '/assignments/templates/files.php';
	}
);

function masterstudy_lms_student_assignments_filter_assignments( $query ) {
	if ( is_admin() && PostType::USER_ASSIGNMENT === $query->query['post_type'] && ! wp_doing_ajax() ) {
		$query_vars = &$query->query_vars;

		if ( ! isset( $query_vars['meta_query'] ) ) {
			$query_vars['meta_query'] = array();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['lms_student_id'] ) ) {
			$query_vars['meta_query'][] = array(
				'field' => 'student_id',
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'value' => intval( $_GET['lms_student_id'] ),
			);
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['lms_course_id'] ) ) {
			$query_vars['meta_query'][] = array(
				'field' => 'course_id',
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'value' => intval( $_GET['lms_course_id'] ),
			);
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['status'] ) ) {
			$query_vars['meta_query'][] = array(
				'field' => 'status',
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'value' => wp_unslash( $_GET['status'] ),
			);
		}
	}
}
add_filter( 'parse_query', 'masterstudy_lms_student_assignments_filter_assignments' );

function masterstudy_lms_student_assignments_views_edit_filter( $views ) {
	$statuses = Assignments::statuses();

	foreach ( $statuses as $status => $args ) {
		if ( ! in_array( $status, array( 'pending', 'draft' ), true ) ) {
			$link             = 'edit.php?post_type=stm-user-assignment&status=' . $status;
			$count            = AssignmentStudentRepository::count_by_status( $status );
			$views[ $status ] = "<a href='{$link}'>{$args['title']} <span class='count'>({$count})</span></a>";
		}
	}

	return $views;
}
add_filter( 'views_edit-stm-user-assignment', 'masterstudy_lms_student_assignments_views_edit_filter' );
