<?php

namespace MasterStudy\Lms\Http\Controllers\Question;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Http\Serializers\QuestionCategorySerializer;
use MasterStudy\Lms\Repositories\QuestionCategoryRepository;
use WP_REST_Response;

final class GetCategoriesController {
	public function __invoke(): WP_REST_Response {
		$categories = ( new QuestionCategoryRepository() )->get_all();

		if ( is_wp_error( $categories ) ) {
			return WpResponseFactory::error( $categories->get_error_message() );
		}

		return new \WP_REST_Response(
			array( 'categories' => ( new QuestionCategorySerializer() )->collectionToArray( $categories ) )
		);
	}
}
