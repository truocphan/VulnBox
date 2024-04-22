<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Repositories;

use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Repositories\AbstractRepository;

final class AssignmentRepository extends AbstractRepository {

	protected static array $fields_post_map = array(
		'title'           => 'post_title',
		'content'         => 'post_content',
		'allow_questions' => 'comment_status',
	);

	protected static array $fields_meta_map = array(
		'attempts' => 'assignment_tries',
	);

	protected static array $casts = array(
		'attempts' => 'int',
	);

	protected static string $post_type = PostType::ASSIGNMENT;

	protected function map_post( \WP_Post $post ): array {
		$post_array = parent::map_post( $post );

		$post_array['allow_questions'] = 'open' === $post->comment_status;

		return $post_array;
	}

	public function create( array $data ): int {
		if ( ! isset( $data['allow_questions'] ) ) {
			$data['allow_questions'] = false;
		}

		$data['allow_questions'] = $data['allow_questions'] ? 'open' : 'closed';

		return parent::create( $data );
	}

	public function update( int $id, array $data ): void {
		if ( ! isset( $data['allow_questions'] ) ) {
			$data['allow_questions'] = false;
		}

		$data['allow_questions'] = $data['allow_questions'] ? 'open' : 'closed';

		parent::update( $id, $data );
	}
}
