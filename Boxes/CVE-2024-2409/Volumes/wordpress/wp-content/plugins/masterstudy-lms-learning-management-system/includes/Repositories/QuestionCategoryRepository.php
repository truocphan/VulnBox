<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Plugin\Taxonomy;

final class QuestionCategoryRepository {
	/**
	 * @return array|\WP_Error|\WP_Term|null
	 */
	public function create( array $data ) {
		$parent = ( ! empty( $data['parent_category'] ) ) ? intval( $data['parent_category'] ) : 0;
		$term   = wp_insert_term(
			$data['category'],
			Taxonomy::QUESTION_CATEGORY,
			compact( 'parent' )
		);

		if ( is_wp_error( $term ) ) {
			return $term;
		}

		return get_term( $term['term_id'] ?? null );
	}

	/**
	 * @return array<\WP_Term>
	 */
	public function get_all(): array {
		return get_terms(
			array(
				'hide_empty' => false,
				'taxonomy'   => Taxonomy::QUESTION_CATEGORY,
			)
		);
	}
}
