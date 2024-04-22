<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Media;

use MasterStudy\Lms\Routing\Swagger\Fields\Media;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Upload extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'file'    => array(
				'required' => true,
				'type'     => 'file',
			),
			'title'   => array(
				'required' => false,
				'type'     => 'string',
			),
			'caption' => array(
				'required' => false,
				'type'     => 'string',
			),
		);
	}

	public function response(): array {
		return Media::as_response();
	}

	public function get_summary(): string {
		return 'Upload Media File';
	}

	public function get_description(): string {
		return 'Uploads a new Media File using multipart/form-data.';
	}
}
