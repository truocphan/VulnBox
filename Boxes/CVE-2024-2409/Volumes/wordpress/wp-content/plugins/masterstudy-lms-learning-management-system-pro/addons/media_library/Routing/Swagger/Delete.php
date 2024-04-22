<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\Route;

final class Delete extends Route {

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
