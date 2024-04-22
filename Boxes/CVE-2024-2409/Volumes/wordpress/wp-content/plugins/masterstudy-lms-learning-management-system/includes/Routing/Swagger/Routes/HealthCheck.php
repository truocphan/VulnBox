<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes;

use MasterStudy\Lms\Routing\Swagger\Fields\Category;
use MasterStudy\Lms\Routing\Swagger\Fields\PostStatus;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class HealthCheck extends Route implements RequestInterface, ResponseInterface {
	/**
	 * Route Summary
	 */
	public function get_summary(): string {
		return 'Health Check';
	}

	/**
	 * Route Description
	 */
	public function get_description(): string {
		return 'Checking REST API';
	}

	/**
	 * Set Request Args
	 */
	public function request(): array {
		return array(
			'title'      => array(
				'type' => 'string',
			),
			'status'     => array(
				'type' => 'status',
			),
			'level'      => array(
				'type' => 'string',
			),
			'categories' => Category::as_list(),
		);
	}

	/**
	 * Set Response Body Properties
	 */
	public function response(): array {
		return array(
			'id'     => array(
				'type' => 'integer',
			),
			'title'  => array(
				'type' => 'string',
			),
			'status' => PostStatus::as_object(),
		);
	}
}
