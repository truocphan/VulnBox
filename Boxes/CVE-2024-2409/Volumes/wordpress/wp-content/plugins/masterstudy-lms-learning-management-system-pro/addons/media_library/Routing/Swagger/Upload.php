<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class Upload extends Route implements RequestInterface, ResponseInterface {

	public function request(): array {
		return array(
			'file' => array(
				'type'        => 'file',
				'description' => 'File to upload',
			),
		);
	}

	public function response(): array {
		return array(
			'file' => AttachmentEntity::as_object(),
		);
	}

	/**
	 * Route Summary
	 * @return string
	 */
	public function get_summary(): string {
		return 'Delete media file by id';
	}

	/**
	 * Route Description
	 * @return string
	 */
	public function get_description(): string {
		return 'Delete media file by id';
	}
}
