<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Create extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'title'    => array(
				'required' => true,
				'type'     => 'string',
			),
			'slug'     => array(
				'required' => true,
				'type'     => 'string',
			),
			'image_id' => array(
				'type' => 'integer',
			),
			'category' => array(
				'type'     => 'array',
				'items'    => array(
					'type' => 'integer',
				),
				'required' => true,
			),
			'level'    => array(
				'nullable' => true,
				'type'     => 'string',
			),
		);
	}

	public function response(): array {
		return array(
			'id' => array(
				'type' => 'integer',
			),
		);
	}

	public function get_summary(): string {
		return 'Create a New Course';
	}

	public function get_description(): string {
		return 'Returns created Course ID.';
	}
}
