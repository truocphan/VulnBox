<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetAnnouncement extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array();
	}

	public function response(): array {
		return array(
			'announcement' => array(
				'type' => 'string',
			),
		);
	}

	public function get_summary(): string {
		return 'Returns course announcement';
	}

	public function get_description(): string {
		return 'Returns course announcement';
	}
}
