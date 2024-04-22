<?php

namespace MasterStudy\Lms\Pro\addons\scorm\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Upload extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'file' => array(
				'required'    => true,
				'type'        => 'file',
				'description' => 'Scorm package file',
			),
		);
	}

	public function response(): array {
		return array(
			'error'         => array(
				'type'        => 'string',
				'description' => 'Error message if any',
			),
			'path'          => array(
				'type'        => 'string',
				'description' => 'Path to the scorm package',
			),
			'url'           => array(
				'type'        => 'string',
				'description' => 'Url to the scorm package',
			),
			'scorm_version' => array(
				'type'        => 'string',
				'description' => 'Scorm version',
			),
		);
	}

	public function get_summary(): string {
		return 'Uploads scorm package';
	}

	public function get_description(): string {
		return 'Uploads scorm package and attaches it to the course';
	}
}
