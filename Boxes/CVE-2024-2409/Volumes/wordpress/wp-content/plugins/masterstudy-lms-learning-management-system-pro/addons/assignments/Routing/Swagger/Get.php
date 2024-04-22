<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\Fields\Comment;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class Get extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array();
	}

	public function response(): array {
		return array(
			'assignment' => array(
				'type'        => 'object',
				'description' => 'Assignment',
				'properties'  => array(
					'id'              => array(
						'type'        => 'integer',
						'description' => 'Assignment ID',
					),
					'title'           => array(
						'type'        => 'string',
						'description' => 'Assignment title',
					),
					'content'         => array(
						'type'        => 'string',
						'description' => 'Assignment content',
					),
					'attempts'        => array(
						'type'        => 'integer',
						'description' => 'Assignment attempts',
					),
					'allow_questions' => array(
						'type'        => 'boolean',
						'description' => 'Allow questions',
					),
				),
			),
			'comments'   => Comment::as_array(),
		);
	}

	public function get_summary(): string {
		return 'Returns question';
	}

	public function get_description(): string {
		return 'Returns question';
	}
}
