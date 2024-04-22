<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\PostStatus;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateStatus extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'status' => array_merge(
				PostStatus::as_response(),
				array(
					'required' => true,
				)
			),
		);
	}

	public function response(): array {
		return array(
			'status' => array(
				'type'    => 'string',
				'example' => 'publish',
			),
		);
	}

	public function get_summary(): string {
		return 'Update Course status';
	}

	public function get_description(): string {
		return 'Updates Course status (Publish, Pending, Draft).';
	}
}
