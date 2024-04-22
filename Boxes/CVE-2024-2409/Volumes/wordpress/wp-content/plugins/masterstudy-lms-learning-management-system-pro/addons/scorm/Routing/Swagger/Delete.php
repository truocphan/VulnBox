<?php

namespace MasterStudy\Lms\Pro\addons\scorm\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Delete extends Route implements ResponseInterface {
	public function response(): array {
		return array(
			'status' => array(
				'type'    => 'string',
				'example' => 'ok',
			),
		);
	}

	public function get_summary(): string {
		return 'Deletes scorm package';
	}

	public function get_description(): string {
		return 'Deletes scorm package from the course';
	}
}
