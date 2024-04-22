<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class GetById extends Route implements ResponseInterface {

	/**
	 * Response Schema Properties
	 * @return array
	 */
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
		return 'Get media file by id';
	}

	/**
	 * Route Description
	 * @return string
	 */
	public function get_description(): string {
		return 'Get media file by id';
	}
}
