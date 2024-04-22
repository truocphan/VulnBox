<?php

namespace MasterStudy\Lms\Routing\Middleware;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Repositories\CurriculumRepository;
use MasterStudy\Lms\Routing\MiddlewareInterface;

/**
 * Checks if user can access to the post
 */
final class CoInstructor implements MiddlewareInterface {
	public function process( $request, callable $next ) {
		$post_id = $request->get_param( 'post_id' );

		if ( current_user_can( 'administrator' ) || null === $post_id ) {
			return $next( $request );
		}

		$post = get_post( $post_id );

		if ( empty( $post ) || $this->passes( $post ) ) {
			return $next( $request );
		}

		return WpResponseFactory::forbidden();
	}

	public function passes( $post ): bool {
		$authors = array(
			(int) $post->post_author ?? null,
			(int) get_post_meta( $post->ID, 'co_instructor', true ),
		);

		if ( in_array( $post->post_type, array( PostType::LESSON, PostType::ASSIGNMENT, PostType::QUIZ ), true ) ) {
			$lesson_course_ids = ( new CurriculumRepository() )->get_lesson_course_ids( $post->ID );
			if ( ! empty( $lesson_course_ids ) ) {
				foreach ( $lesson_course_ids as $course_id ) {
					$authors[] = (int) get_post_meta( $course_id, 'co_instructor', true );
				}
			}
		}

		return in_array( get_current_user_id(), $authors, true );
	}
}
