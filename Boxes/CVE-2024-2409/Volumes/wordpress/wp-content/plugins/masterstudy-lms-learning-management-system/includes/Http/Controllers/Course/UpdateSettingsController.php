<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Enums\CourseStatus;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Models\Course;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdateSettingsController {
	private CourseRepository $course_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
	}

	public function __invoke( int $course_id, WP_REST_Request $request ): WP_REST_Response {
		$course = $this->course_repository->find( $course_id );

		if ( null === $course ) {
			return WpResponseFactory::not_found();
		}

		$levels    = array_keys( \STM_LMS_Helpers::get_course_levels() );
		$validator = new Validator(
			$request->get_json_params(),
			array(
				'category'          => 'required|array',
				'co_instructor_id'  => 'nullable|integer',
				'content'           => 'nullable|string',
				'current_students'  => 'integer|min,0',
				'duration_info'     => 'string',
				'excerpt'           => 'nullable|string',
				'image_id'          => 'nullable|integer',
				'is_featured'       => 'required|boolean',
				'level'             => 'nullable|string|contains_list,' . implode( ';', $levels ),
				'slug'              => 'required|string',
				'status'            => 'nullable|string|contains_list,' . implode( ';', CourseStatus::cases() ),
				'status_date_end'   => 'nullable|integer',
				'status_date_start' => 'nullable|integer',
				'title'             => 'required|string',
				'video_duration'    => 'string',
				'views'             => 'integer|min,0',
				'access_duration'   => 'nullable|string',
				'access_devices'    => 'nullable|string',
				'certificate_info'  => 'nullable|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$this->course_repository->save( $this->fill_course_data( $course, $data ) );

		return WpResponseFactory::ok();
	}

	private function fill_course_data( Course $course, array $data ): Course {
		$course->category = $data['category'];
		if ( array_key_exists( 'co_instructor_id', $data ) ) {
			if ( null === $data['co_instructor_id'] ) {
				$course->co_instructor = null;
			} else {
				$course->co_instructor = get_user_by( 'id', $data['co_instructor_id'] );
			}
		}

		$course->content           = $data['content'] ?? null;
		$course->current_students  = $data['current_students'] ?? null;
		$course->duration_info     = $data['duration_info'] ?? null;
		$course->excerpt           = $data['excerpt'] ?? null;
		$course->image             = ! isset( $data['image_id'] ) ? null : array( 'id' => $data['image_id'] );
		$course->is_featured       = $data['is_featured'];
		$course->level             = $data['level'] ?? null;
		$course->slug              = $data['slug'];
		$course->status            = $data['status'] ?? null;
		$course->status_date_end   = $data['status_date_end'] ?? null;
		$course->status_date_start = $data['status_date_start'] ?? null;
		$course->title             = $data['title'];
		$course->video_duration    = $data['video_duration'] ?? null;
		$course->views             = $data['views'] ?? null;
		$course->access_duration   = $data['access_duration'] ?? null;
		$course->access_devices    = $data['access_devices'] ?? null;
		$course->certificate_info  = $data['certificate_info'] ?? null;

		return $course;
	}
}
