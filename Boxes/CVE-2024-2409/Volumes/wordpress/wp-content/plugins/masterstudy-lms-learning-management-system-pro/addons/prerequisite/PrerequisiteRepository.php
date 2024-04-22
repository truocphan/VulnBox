<?php

namespace MasterStudy\Lms\Pro\addons\prerequisite;

use MasterStudy\Lms\Plugin\PostType;

class PrerequisiteRepository {
	private const PASSING_LEVEL_META_KEY = 'prerequisite_passing_level';
	private const COURSES_META_KEY       = 'prerequisites';

	public function get( int $course_id ): array {
		$passing_level = get_post_meta( $course_id, self::PASSING_LEVEL_META_KEY, true );
		$courses       = get_post_meta( $course_id, self::COURSES_META_KEY, true );

		$prerequisites = array(
			'courses'       => array(),
			'passing_level' => ! empty( $passing_level ) ? (float) $passing_level : null,
		);

		$courses = explode( ',', $courses ?? '' );
		if ( $courses ) {
			$query = new \WP_Query(
				array(
					'post__in'       => $courses,
					'posts_per_page' => - 1,
					'post_type'      => PostType::COURSE,
				)
			);

			$prerequisites['courses'] = array_map(
				function ( \WP_Post $post ) {
					return array(
						'id'    => $post->ID,
						'title' => $post->post_title,
					);
				},
				$query->get_posts()
			);
		}

		return $prerequisites;
	}

	public function save( int $course_id, array $data ): void {
		update_post_meta( $course_id, self::PASSING_LEVEL_META_KEY, $data['passing_level'] );

		$courses = implode( ',', array_column( $data['courses'] ?? array(), 'id' ) );
		update_post_meta( $course_id, self::COURSES_META_KEY, $courses );
	}
}
