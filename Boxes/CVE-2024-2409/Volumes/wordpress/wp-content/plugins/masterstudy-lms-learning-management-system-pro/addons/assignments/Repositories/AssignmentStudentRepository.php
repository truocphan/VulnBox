<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Repositories;

use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Pro\addons\assignments\Assignments;

final class AssignmentStudentRepository {
	const ATTACHMENT_META   = 'student_attachments';
	const STATUS_PASSED     = 'passed';
	const STATUS_NOT_PASSED = 'not_passed';

	public static function get_assignments( int $assignment_id ) {
		$paged = ! empty( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;

		$query_params = array(
			'post_type'      => PostType::USER_ASSIGNMENT,
			'posts_per_page' => intval( $_GET['per_page'] ?? 10 ),
			'post_status'    => array( 'pending', 'publish' ),
			'paged'          => $paged,
			'meta_query'     => array(
				array(
					'key'   => 'assignment_id',
					'value' => $assignment_id,
				),
			),
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['s'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query_params['s'] = sanitize_text_field( $_GET['s'] );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['status'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'pending' === $_GET['status'] ) {
				$query_params['post_status'] = array( 'pending' );
			} else {
				$query_params['meta_query']['relation'] = 'AND';
				$query_params['meta_query'][]           = array(
					'key'   => 'status',
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					'value' => sanitize_text_field( $_GET['status'] ),
				);
			}
		}

		return new \WP_Query( $query_params );
	}

	public static function enclose_attachment( int $assignment_id, int $attachment_id ): void {
		$attachments   = get_post_meta( $assignment_id, self::ATTACHMENT_META, true );
		$attachments   = ! empty( $attachments ) ? $attachments : array();
		$attachments[] = $attachment_id;

		update_post_meta( $assignment_id, self::ATTACHMENT_META, array_unique( $attachments ) );
	}

	public static function get_display_name( int $assignment_id ): string {
		$student_id   = get_post_meta( $assignment_id, 'student_id', true );
		$student      = get_userdata( $student_id );
		$display_name = \STM_LMS_User::display_name( $student );
		return $display_name ?? '';
	}

	public static function get_status( string $status, $show_icon = true ): string {
		$status = empty( $status ) ? 'pending' : $status;

		$statuses = Assignments::statuses();

		if ( $show_icon ) {
			return "{$statuses[ $status ]['icon']} {$statuses[ $status ]['title'] }";
		}

		return $statuses[ $status ]['title'] ?? '';
	}

	public static function count_by_status( string $status ): ?int {
		$query = new \WP_Query(
			array(
				'post_type'      => PostType::USER_ASSIGNMENT,
				'posts_per_page' => 1,
				'meta_key'       => 'status',
				'meta_value'     => $status,
			)
		);

		return $query->found_posts;
	}

	public static function get_all_students() {
		global $wpdb;

		return $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} AS pm INNER JOIN {$wpdb->posts} AS p 
                ON pm.post_id = p.ID WHERE pm.meta_key = 'student_id' AND CAST(pm.meta_value AS SIGNED) > 0 AND p.post_type = %s",
				PostType::USER_ASSIGNMENT
			)
		);
	}
}
