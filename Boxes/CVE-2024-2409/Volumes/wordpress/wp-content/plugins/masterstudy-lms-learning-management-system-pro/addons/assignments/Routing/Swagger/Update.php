<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class Update extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'title'           => array(
				'type'        => 'string',
				'description' => 'Assignment title',
			),
			'content'         => array(
				'type'        => 'string',
				'description' => 'Assignment content',
			),
			'attempts'        => array(
				'type'        => 'integer',
				'description' => 'Assignment attempts',
			),
			'allow_questions' => array(
				'type'        => 'boolean',
				'description' => 'Allow questions',
			),
		);
	}

	public function response(): array {
		return array();
	}

	public function get_summary(): string {
		return 'Update assignment';
	}

	public function get_description(): string {
		return 'Update assignment';
	}
}
