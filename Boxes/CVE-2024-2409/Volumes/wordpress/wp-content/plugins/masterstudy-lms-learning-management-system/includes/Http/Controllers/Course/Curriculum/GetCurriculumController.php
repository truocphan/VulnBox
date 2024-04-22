<?php

namespace MasterStudy\Lms\Http\Controllers\Course\Curriculum;

use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\CurriculumRepository;
use MasterStudy\Lms\Http\WpResponseFactory;

class GetCurriculumController {
	public function __invoke( int $course_id ): \WP_REST_Response {
		if ( ! ( new CourseRepository() )->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		return new \WP_REST_Response(
			( new CurriculumRepository() )->get_curriculum( $course_id )
		);
	}
}
