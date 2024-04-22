<?php

namespace MasterStudy\Lms\Pro\addons\prerequisite\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateSettings extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'passing_level' => array(
				'type'     => 'integer',
				'nullable' => true,
				'min'      => 0,
				'max'      => 100,
				'required' => true,
			),
			'courses'       => array(
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'    => array(
							'type'     => 'string',
							'required' => true,
						),
						'title' => array(
							'type'     => 'string',
							'required' => false,
						),
					),
				),
				'description' => 'Required if passing_level is greater than 0.',
			),
		);
	}

	public function response(): array {
		return array(
			'status' => 'ok',
		);
	}

	public function get_summary(): string {
		return 'Updates course prerequisites settings';
	}

	public function get_description(): string {
		return 'Updates course prerequisites settings';
	}
}
