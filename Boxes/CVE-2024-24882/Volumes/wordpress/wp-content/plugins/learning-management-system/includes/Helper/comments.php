<?php
/**
 * Comments related functions.
 *
 * @since 1.4.10
 */

use Masteriyo\Enums\CommentStatus;
use PHP_CodeSniffer\Util\Common;

/**
 * Return an array of comments count by status for specific comment type and post ID.
 *
 * @since 1.4.10
 *
 * @param string $type Comment type.
 * @param integer $post_id Post ID
 * @return array
 */
function masteriyo_count_comments( $type = 'comment', $post_id = 0 ) {
	global $wpdb;

	if ( $post_id ) {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments } WHERE comment_type = %s AND comment_post_ID = %d AND comment_parent = 0 GROUP BY comment_approved",
				$type,
				$post_id
			),
			ARRAY_A
		);
	} else {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments } WHERE comment_type = %s AND comment_parent = 0 GROUP BY comment_approved",
				$type
			),
			ARRAY_A
		);
	}

	$counts = array_fill_keys( CommentStatus::readable(), 0 );

	foreach ( $results as $row ) {
		if ( CommentStatus::APPROVE === $row['comment_approved'] ) {
			$row['comment_approved'] = CommentStatus::APPROVE_STR;
		} elseif ( CommentStatus::HOLD === $row['comment_approved'] ) {
			$row['comment_approved'] = CommentStatus::HOLD_STR;
		}

		$counts[ $row['comment_approved'] ] = $row['num_comments'];
	}

	$counts = array_map( 'absint', $counts );

	/**
	 * Filter comments count by comment type and post ID.
	 *
	 * @since 1.4.10
	 *
	 * @param integer[] $counts Comment counts by status.
	 * @param string $type Comment type.
	 * @param int $post_id Post ID.
	 */
	return apply_filters( 'masteriyo_count_comments', $counts, $type, $post_id );
}

/**
 * Return array of comments replies with counts for specific parent comment and post ID.
 *
 * @since 1.5.0
 *
 * @param string $type Comment type.
 * @param int $parent Parent comment ID.
 * @param int $post Post ID.
 *
 *
 * @return int|array
 */
function masteriyo_count_comment_replies( $type = 'comment', $parent = 0, $post = 0 ) {
	global $wpdb;

	if ( $parent ) {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT(*) as num_comments FROM {$wpdb->comments} WHERE comment_type = %s AND comment_parent = %d AND comment_post_ID = %d GROUP BY comment_approved",
				$type,
				$parent,
				$post
			),
			ARRAY_A
		);
	} else {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT(*) as num_comments FROM {$wpdb->comments} WHERE comment_type = %s AND comment_parent > 0 AND comment_post_ID = %d GROUP BY comment_approved",
				$type,
				$post
			),
			ARRAY_A
		);
	}

	$counts = array_fill_keys( CommentStatus::readable(), 0 );

	foreach ( $results as $row ) {
		if ( CommentStatus::APPROVE === $row['comment_approved'] ) {
			$row['comment_approved'] = CommentStatus::APPROVE_STR;
		} elseif ( CommentStatus::HOLD === $row['comment_approved'] ) {
			$row['comment_approved'] = CommentStatus::HOLD_STR;
		}

		$counts[ $row['comment_approved'] ] = $row['num_comments'];
	}

	$counts = array_map( 'absint', $counts );

	/**
	 * Filter comments count by comment type and post ID.
	 *
	 * @since 1.5.0
	 *
	 * @param string $counts Comment counts by status.
	 * @param string $type Comment type.
	 * @param int $parent Parent comment.
	 * @param int $post Post ID.
	 */
	return apply_filters( 'masteriyo_count_comment_replies', $counts, $type, $parent, $post );
}

/**
 * Delete all replies of a comment.
 *
 * @since 1.5.0
 *
 * @param integer $comment_id
 *
 * @return int|false The number of rows updated, or false on error.
 */
function masteriyo_delete_comment_replies( $comment_id ) {
	global $wpdb;

	return $wpdb->delete(
		$wpdb->comments,
		array(
			'comment_parent' => $comment_id,
		),
		'%d'
	);
}
