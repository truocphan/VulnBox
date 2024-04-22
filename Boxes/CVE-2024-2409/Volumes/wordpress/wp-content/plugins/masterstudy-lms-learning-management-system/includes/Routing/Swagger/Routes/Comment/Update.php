<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Comment;

use MasterStudy\Lms\Routing\Swagger\Fields\Comment;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class Update extends Route implements RequestInterface, ResponseInterface {

	/**
	 * Response Schema Properties
	 * @return array
	 */
	public function request(): array {
		return array(
			'content' => array(
				'type'        => 'string',
				'description' => 'Comment content',
				'required'    => true,
			),
		);
	}

	/**
	 * Response Schema Properties
	 * @return array
	 */
	public function response(): array {
		return array(
			'comment' => Comment::as_object(),
		);
	}

	/**
	 * Route Summary
	 * @return string
	 */
	public function get_summary(): string {
		return 'Update comment';
	}

	/**
	 * Route Description
	 * @return string
	 */
	public function get_description(): string {
		return 'Update comment';
	}
}
