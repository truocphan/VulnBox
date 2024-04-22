<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\CourseStatus;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateSettings extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'category'          => array(
				'type'     => 'array',
				'items'    => array(
					'type' => 'integer',
				),
				'required' => true,
			),
			'co_instructor_id'  => array(
				'type'     => 'integer',
				'nullable' => true,
			),
			'content'           => array(
				'type'     => 'string',
				'required' => true,
			),
			'current_students'  => array(
				'type' => 'integer',
				'min'  => 0,
			),
			'duration_info'     => array(
				'type'        => 'string',
				'description' => 'Duration info',
				'example'     => '10 hours',
			),
			'excerpt'           => array(
				'nullable' => true,
				'type'     => 'string',
			),
			'image_id'          => array(
				'type' => 'integer',
			),
			'is_featured'       => array(
				'type'        => 'boolean',
				'description' => 'Mark this checkbox to add badge to course "Featured".',
			),
			'level'             => array(
				'nullable' => true,
				'type'     => 'string',
			),
			'slug'              => array(
				'required' => true,
				'type'     => 'string',
			),
			'status'            => array_merge(
				CourseStatus::as_response(),
				array(
					'nullable' => true,
				)
			),
			'status_date_end'   => array(
				'type'     => 'integer',
				'nullable' => true,
			),
			'status_date_start' => array(
				'type'     => 'integer',
				'nullable' => true,
			),
			'title'             => array(
				'required' => true,
				'type'     => 'string',
			),
			'video_duration'    => array(
				'type'        => 'string',
				'description' => 'Video Duration',
				'example'     => '10 hours',
			),
			'views'             => array(
				'type' => 'integer',
				'min'  => 0,
			),
			'access_duration'   => array(
				'type'        => 'string',
				'description' => 'Access Duration',
				'nullable'    => true,
			),
			'access_devices'    => array(
				'type'        => 'string',
				'description' => 'Access Device Types',
				'nullable'    => true,
			),
			'certificate_info'  => array(
				'type'        => 'string',
				'description' => 'Certificate Info',
				'nullable'    => true,
			),
		);
	}

	public function response(): array {
		return array(
			'status' => 'ok',
		);
	}

	public function get_summary(): string {
		return 'Updates course main settings';
	}

	public function get_description(): string {
		return 'Updates course main settings';
	}
}
