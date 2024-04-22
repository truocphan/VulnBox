<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\Category;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class CreateCategory extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'category'        => array(
				'type'     => 'string',
				'required' => true,
			),
			'parent_category' => array(
				'type'     => 'integer',
				'required' => false,
			),
		);
	}

	public function response(): array {
		return array(
			'category' => Category::as_object(),
		);
	}

	public function get_summary(): string {
		return 'Create a New Category';
	}

	public function get_description(): string {
		return 'Returns created Category Object.';
	}
}
