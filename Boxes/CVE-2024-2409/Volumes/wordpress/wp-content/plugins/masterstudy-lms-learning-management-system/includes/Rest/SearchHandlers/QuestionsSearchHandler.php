<?php

namespace MasterStudy\Lms\Rest\SearchHandlers;

use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Plugin\Taxonomy;
use MasterStudy\Lms\Repositories\QuestionRepository;
use WP_Query;
use WP_REST_Search_Handler;

final class QuestionsSearchHandler extends WP_REST_Search_Handler {
	private $repo;

	public function __construct() {
		$this->type     = PostType::QUESTION;
		$this->subtypes = array( PostType::QUESTION );
	}

	public function prepare_item( $id, array $fields ) {
		return $this->get_repo()->get( $id );
	}

	public function prepare_item_links( $id ) {
		return array(
			'self' => array(
				'href' => rest_url( 'masterstudy-lms/v2/questions/' . $id ),
			),
		);
	}

	public function search_items( \WP_REST_Request $request ) {
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

		$categories = (array) $request->get_param( 'category' );
		if ( ! empty( $categories ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => Taxonomy::QUESTION_CATEGORY,
					'field'    => 'term_id',
					'terms'    => $categories,
				),
			);
		}

		if ( ! empty( $request['search'] ) ) {
			$query_args['s'] = $request['search'];
		}

		$query     = new WP_Query();
		$found_ids = $query->query( $query_args );
		$total     = $query->found_posts;

		return array(
			self::RESULT_IDS   => $found_ids,
			self::RESULT_TOTAL => $total,
		);
	}

	private function get_repo() {
		if ( null === $this->repo ) {
			$this->repo = new QuestionRepository();
		}

		return $this->repo;
	}
}
