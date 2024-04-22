<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetCourses extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'offset' => array(
				'type'        => 'integer',
				'description' => 'Number of items to offset in the query. Default is 10.',
			),
			'status' => array(
				'type'        => 'string',
				'enum'        => array( 'publish', 'draft', 'pending', 'coming_soon_status' ),
				'description' => 'Filter courses by status (publish, draft, pending, coming_soon_status). Default is empty.',
			),
			'user'   => array(
				'type'        => 'integer',
				'description' => 'Filter courses by user ID. Default is null.',
			),
		);
	}

	public function response(): array {
		return array(
			'id'    => array(
				'type' => 'integer',
			),
			'title' => array(
				'type' => 'string',
			),
		);
	}

	public function get_summary(): string {
		return 'Get Courses';
	}

	public function get_description(): string {
		return 'Returns a list of courses based on the provided parameters.';
	}
}
