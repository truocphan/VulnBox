<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Repositories;

use MasterStudy\Lms\Plugin\PostType;

final class AssignmentTeacherRepository {
	public static function get_assignments() {
		$assignments_args = array(
			'post_type'      => PostType::ASSIGNMENT,
			'fields'         => 'ids',
			'author'         => get_current_user_id(),
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'posts_per_page' => intval( $_GET['per_page'] ?? 10 ),
			'paged'          => get_query_var( 'page' ) ?? 1,
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['s'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$assignments_args['s'] = sanitize_text_field( $_GET['s'] );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['status'] ) ) {
			$user_assignment_args = array(
				'post_type'      => PostType::USER_ASSIGNMENT,
				'fields'         => 'ids',
				'posts_per_page' => -1,
			);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$assignment_status = sanitize_text_field( $_GET['status'] );

			if ( 'pending' === $assignment_status ) {
				$user_assignment_args['post_status'] = array( $assignment_status );
			} else {
				$user_assignment_args['meta_key']   = 'status';
				$user_assignment_args['meta_value'] = $assignment_status;
			}

			$filtered_assignments = array( 0 );
			$user_assignment_ids  = get_posts( $user_assignment_args );

			if ( ! empty( $user_assignment_ids ) ) {
				foreach ( $user_assignment_ids as $user_assignment_id ) {
					$assignment_id = get_post_meta( $user_assignment_id, 'assignment_id', true );

					if ( $assignment_id > 0 ) {
						$filtered_assignments[] = intval( $assignment_id );
					}
				}
			}

			$assignments_args['post__in'] = array_unique( $filtered_assignments );
		}

		return new \WP_Query( $assignments_args );
	}

	public static function user_assignments_count( int $assignment_id, string $assigment_status = '', bool $only_pending = false ): ?int {
		$args = array(
			'post_type'      => PostType::USER_ASSIGNMENT,
			'post_status'    => $only_pending ? array( 'pending' ) : array( 'pending', 'publish', 'future', 'draft' ),
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => 'assignment_id',
					'value' => $assignment_id,
				),
			),
		);

		if ( ! empty( $assigment_status ) ) {
			$args['meta_query'][] = array(
				'key'   => 'status',
				'value' => $assigment_status,
			);
		}

		$query = new \WP_Query( $args );

		return $query->found_posts;
	}

	public static function total_pending_assignments( int $user_id ): ?int {
		$assignment_ids = get_posts(
			array(
				'fields'         => 'ids',
				'author'         => $user_id,
				'post_type'      => PostType::ASSIGNMENT,
				'posts_per_page' => -1,
			)
		);

		if ( empty( $assignment_ids ) ) {
			return 0;
		}

		$args = array(
			'post_type'      => PostType::USER_ASSIGNMENT,
			'posts_per_page' => 1,
			'post_status'    => array( 'pending' ),
			'meta_query'     => array(
				array(
					'key'     => 'assignment_id',
					'value'   => $assignment_ids,
					'compare' => 'IN',
				),
			),
		);

		$user_assignments = new \WP_Query( $args );

		return $user_assignments->found_posts;
	}
}
