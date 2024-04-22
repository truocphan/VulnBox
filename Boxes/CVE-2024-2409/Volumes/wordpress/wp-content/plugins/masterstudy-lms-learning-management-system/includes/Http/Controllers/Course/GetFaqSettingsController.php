<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\FaqRepository;
use WP_REST_Request;
use WP_REST_Response;

class GetFaqSettingsController {
	private CourseRepository $course_repository;
	private FaqRepository $faq_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
		$this->faq_repository    = new FaqRepository();
	}

	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		if ( ! $this->course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}
		$faq = $this->faq_repository->find_for_course( $course_id );

		return new WP_REST_Response(
			array(
				'faq' => $faq,
			)
		);
	}
}
