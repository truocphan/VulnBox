<?php

namespace MasterStudy\Lms\Routing\Middleware;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Repositories\CurriculumRepository;
use MasterStudy\Lms\Routing\MiddlewareInterface;

final class CommentGuard implements MiddlewareInterface {
	public function process( $request, callable $next ) {
		$comment_id = $request->get_param( 'comment_id' );
		$post_id    = $request->get_param( 'post_id' );

		if ( ! empty( $comment_id ) ) {
			$comment = get_comment( $comment_id );
			$post_id = $comment->comment_post_ID;
		}

		$post = get_post( $post_id );

		if ( null === $post ) {
			return WpResponseFactory::not_found();
		}

		if ( ! current_user_can( 'administrator' ) && ! ( new CoInstructor() )->passes( $post ) ) {
			return WpResponseFactory::forbidden();
		}

		return $next( $request );
	}
}
