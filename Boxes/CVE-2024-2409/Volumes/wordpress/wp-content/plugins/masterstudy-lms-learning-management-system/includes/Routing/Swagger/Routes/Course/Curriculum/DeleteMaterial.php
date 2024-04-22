<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum;

use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class DeleteMaterial extends Route implements ResponseInterface {
	public function response(): array {
		return array(
			'status' => array(
				'type'    => 'string',
				'example' => 'ok',
			),
		);
	}

	public function get_summary(): string {
		return 'Delete Curriculum Material';
	}

	public function get_description(): string {
		return 'Deletes Curriculum Material and returns boolean value of the action and deleted Material ID.';
	}
}
