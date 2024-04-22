<?php

namespace MasterStudy\Lms\Http\Controllers\Course\Curriculum;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;

class DeleteSectionController {
	public function __invoke( $course_id, $section_id ): \WP_REST_Response {
		if ( ! ( new CourseRepository() )->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		( new CurriculumSectionRepository() )->delete( $section_id );

		return WpResponseFactory::ok();
	}
}
