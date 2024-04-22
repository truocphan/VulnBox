<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\PricingRepository;
use WP_REST_Request;
use WP_REST_Response;

class GetPricingSettingsController {
	private CourseRepository $course_repository;
	private PricingRepository $pricing_repository;

	public function __construct() {
		$this->course_repository  = new CourseRepository();
		$this->pricing_repository = new PricingRepository();
	}

	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		if ( ! $this->course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$pricing = $this->pricing_repository->get( $course_id );

		return new WP_REST_Response(
			array(
				'pricing' => $pricing,
			)
		);
	}
}
