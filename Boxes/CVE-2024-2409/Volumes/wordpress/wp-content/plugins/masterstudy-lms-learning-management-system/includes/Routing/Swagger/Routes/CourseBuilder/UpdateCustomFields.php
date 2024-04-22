<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\CourseBuilder;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class UpdateCustomFields extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'custom_fields' => array(
				'type'        => 'object',
				'properties'  => array(
					'custom-field-key' => array(
						'description' => 'Custom Field key & value',
						'type'        => 'custom-field-value',
					),
				),
				'description' => 'List of Custom Fields',
			),
		);
	}

	public function response(): array {
		return array(
			'status' => array(
				'type'    => 'string',
				'example' => 'ok',
			),
		);
	}

	public function get_summary(): string {
		return 'Update Course Builder Custom Fields.';
	}

	public function get_description(): string {
		return 'Updates Course Custom Fields.';
	}
}
