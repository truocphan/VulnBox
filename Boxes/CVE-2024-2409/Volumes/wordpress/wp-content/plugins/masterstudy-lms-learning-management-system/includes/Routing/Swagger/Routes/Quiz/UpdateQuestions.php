<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Quiz;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateQuestions extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'questions' => array(
				'type'        => 'array',
				'items'       => array(
					'type' => 'integer',
				),
				'description' => 'Questions IDs',
			),
		);
	}

	public function response(): array {
		return array();
	}

	public function get_summary(): string {
		return 'Update quiz questions';
	}

	public function get_description(): string {
		return 'Update quiz questions';
	}
}
