<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Media;

use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Delete extends Route implements ResponseInterface {
	public function response(): array {
		return array(
			'status' => array(
				'type'        => 'boolean',
				'description' => 'True if the record deleted, False it not',
			),
		);
	}

	public function get_summary(): string {
		return 'Delete Media File';
	}

	public function get_description(): string {
		return 'Deletes Media File/Attachment and returns boolean value of the action.';
	}
}
