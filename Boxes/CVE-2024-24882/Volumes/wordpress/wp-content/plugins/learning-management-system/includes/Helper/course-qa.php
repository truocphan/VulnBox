<?php
/**
 * Course question answer functions.
 *
 * @since 1.0.0
 * @package Masteriyo\Helper
 */

/**
 * Get the course QA question count.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|Masteriyo\Models\Course $course Course object.
 *
 * @return int|array
 */
function masteriyo_get_course_question_count( $course = 0 ) {
	global $wpdb;

	if ( is_a( $course, 'WP_Post' ) ) {
		$course_id = $course->ID;
	} elseif ( is_a( $course, 'Masteriyo\Models\Course' ) ) {
		$course_id = $course->get_id();
	} else {
		$course_id = (int) $course;
	}

	$totals = (array) $wpdb->get_results(
		$wpdb->prepare(
			"SELECT comment_approved, COUNT(*) as total FROM {$wpdb->comments} WHERE comment_type = %s' AND comment_parent = 0 AND comment_post_ID = %d GROUP BY comment_approved",
			'mto_course_qa',
			$course_id
		),
		ARRAY_A
	);

	$qa_count = array(
		'spam'     => 0,
		'approved' => 0,
		'total'    => 0,
	);

	foreach ( $totals as $row ) {
		switch ( $row['comment_approved'] ) {
			case 'trash':
				$qa_count['trash'] = $row['total'];
				break;
			case 'post-trashed':
				$qa_count['post-trashed'] = $row['total'];
				break;
			case 'spam':
				$qa_count['spam']   = $row['total'];
				$qa_count['total'] += $row['total'];
				break;
			case '1':
				$qa_count['approved'] = $row['total'];
				$qa_count['total']   += $row['total'];
				break;
			case '0':
				$qa_count['hold']   = $row['total'];
				$qa_count['total'] += $row['total'];
				break;
			default:
				break;
		}
	}

	$qa_count = array_map( 'intval', $qa_count );

	return $qa_count['total'];
}

/**
 * Get the course QA answer count.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|Masteriyo\Models\Course $course Course object.
 *
 * @return int|array
 */
function masteriyo_get_course_answer_count( $course = 0, $question_id = 0 ) {
	global $wpdb;

	if ( is_a( $course, 'WP_Post' ) ) {
		$course_id = $course->ID;
	} elseif ( is_a( $course, 'Masteriyo\Models\Course' ) ) {
		$course_id = $course->get_id();
	} else {
		$course_id = (int) $course;
	}

	$totals = (array) $wpdb->get_results(
		$wpdb->prepare(
			"SELECT comment_approved, COUNT(*) as total FROM {$wpdb->comments} WHERE comment_type = %s AND comment_parent = %d AND comment_post_ID = %d GROUP BY comment_approved",
			'mto_course_qa',
			$question_id,
			$course_id
		),
		ARRAY_A
	);

	$qa_count = array(
		'spam'     => 0,
		'approved' => 0,
		'total'    => 0,
	);

	foreach ( $totals as $row ) {
		switch ( $row['comment_approved'] ) {
			case 'trash':
				$qa_count['trash'] = $row['total'];
				break;
			case 'post-trashed':
				$qa_count['post-trashed'] = $row['total'];
				break;
			case 'spam':
				$qa_count['spam']   = $row['total'];
				$qa_count['total'] += $row['total'];
				break;
			case '1':
				$qa_count['approved'] = $row['total'];
				$qa_count['total']   += $row['total'];
				break;
			case '0':
				$qa_count['hold']   = $row['total'];
				$qa_count['total'] += $row['total'];
				break;
			default:
				break;
		}
	}

	$qa_count = array_map( 'intval', $qa_count );

	return $qa_count['total'];
}
