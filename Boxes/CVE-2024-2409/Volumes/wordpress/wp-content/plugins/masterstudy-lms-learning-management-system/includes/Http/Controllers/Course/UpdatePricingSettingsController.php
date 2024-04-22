<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\PricingRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdatePricingSettingsController {
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

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'single_sale'            => 'required|boolean',
				'price'                  => 'required_if_accepted,single_sale|nullable|float',
				'sale_price'             => 'nullable|float',
				'sale_price_dates_start' => 'required_with,sale_price_dates_end|nullable|integer',
				'sale_price_dates_end'   => 'required_with,sale_price_dates_start|nullable|integer',
				'points_price'           => 'nullable|float',
				'enterprise_price'       => 'nullable|float',
				'not_membership'         => 'required|boolean',
				'affiliate_course'       => 'required|boolean',
				'affiliate_course_text'  => 'required_if_accepted,affiliate_course|string',
				'affiliate_course_link'  => 'required_if_accepted,affiliate_course|string',
				'price_info'             => 'nullable|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();
		$this->pricing_repository->save( $course_id, $data );

		return WpResponseFactory::ok();
	}
}
