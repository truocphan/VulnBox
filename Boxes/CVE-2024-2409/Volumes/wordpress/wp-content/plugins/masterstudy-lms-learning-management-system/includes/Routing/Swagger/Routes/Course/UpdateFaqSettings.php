<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateFaqSettings extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'faq' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'question' => array(
							'type' => 'string',
						),
						'answer'   => array(
							'type' => 'string',
						),
					),
				),
			),
		);
	}

	public function response(): array {
		return array(
			'status' => 'ok',
		);
	}

	public function get_summary(): string {
		return 'Updates course FAQ settings';
	}

	public function get_description(): string {
		return 'Updates course FAQ settings';
	}
}
