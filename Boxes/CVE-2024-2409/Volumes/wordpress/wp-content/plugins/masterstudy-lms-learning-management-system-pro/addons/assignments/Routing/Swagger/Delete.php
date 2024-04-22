<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class Delete extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array();
	}

	public function response(): array {
		return array();
	}

	public function get_summary(): string {
		return 'Deletes assignment';
	}

	public function get_description(): string {
		return 'Deletes assignment';
	}
}
