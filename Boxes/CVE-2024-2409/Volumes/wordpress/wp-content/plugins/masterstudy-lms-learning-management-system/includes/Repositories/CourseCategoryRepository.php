<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Plugin\Taxonomy;

final class CourseCategoryRepository {
	/**
	 * @return array|int[]|\WP_Error
	 */
	public function create( array $data ) {
		$parent = ( ! empty( $data['parent_category'] ) ) ? intval( $data['parent_category'] ) : 0;

		return wp_insert_term(
			$data['category'],
			Taxonomy::COURSE_CATEGORY,
			compact( 'parent' )
		);
	}
}
