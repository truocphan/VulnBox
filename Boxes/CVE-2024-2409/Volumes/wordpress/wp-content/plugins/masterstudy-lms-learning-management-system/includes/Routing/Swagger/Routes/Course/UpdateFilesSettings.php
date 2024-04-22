<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateFilesSettings extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'body' => array(
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'    => array(
							'description' => 'Attachment ID',
							'required'    => true,
							'type'        => 'integer',
						),
						'label' => array(
							'required' => true,
							'type'     => 'string',
						),
					),
				),
				'description' => 'List of files',
			),
		);
	}

	public function response(): array {
		return array(
			'status' => 'ok',
		);
	}

	public function get_summary(): string {
		return 'Updates course materials';
	}

	public function get_description(): string {
		return 'Updates course materials';
	}
}
