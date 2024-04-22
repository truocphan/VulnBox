<?php

namespace MasterStudy\Lms\Rest\SearchHandlers;

use MasterStudy\Lms\Plugin\PostType;
use WP_Query;
use WP_REST_Post_Search_Handler;
use WP_REST_Request;

final class CourseSearchHandler extends WP_REST_Post_Search_Handler {
	public function __construct() {
		$this->type     = PostType::COURSE;
		$this->subtypes = array( PostType::COURSE );
	}

	public function search_items( WP_REST_Request $request ) {
		$user_id = get_current_user_id();

		if ( 0 === $user_id ) {
			return array(
				self::RESULT_IDS   => array(),
				self::RESULT_TOTAL => 0,
			);
		}

		$query_args = array(
			'post_type'           => $this->type,
			'post_status'         => 'publish',
			'paged'               => (int) $request['page'],
			'posts_per_page'      => (int) $request['per_page'],
			'ignore_sticky_posts' => true,
			'fields'              => 'ids',
		);

		if ( ! current_user_can( 'administrator' ) ) {
			$query_args['author'] = $user_id;
		}

		if ( ! empty( $request['search'] ) ) {
			$query_args['s'] = $request['search'];
		}

		if ( ! empty( $request['orderby'] ) && 'latest' === $request['orderby'] ) {
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'DESC';
		}

		$query     = new WP_Query();
		$found_ids = $query->query( $query_args );
		$total     = $query->found_posts;

		return array(
			self::RESULT_IDS   => $found_ids,
			self::RESULT_TOTAL => $total,
		);
	}
}
