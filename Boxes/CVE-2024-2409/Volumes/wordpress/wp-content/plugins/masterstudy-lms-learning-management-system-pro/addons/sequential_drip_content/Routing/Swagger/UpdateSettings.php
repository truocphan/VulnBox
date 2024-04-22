<?php

namespace MasterStudy\Lms\Pro\addons\sequential_drip_content\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateSettings extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'body' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'parent' => array(
							'type'       => 'object',
							'properties' => array(
								'id'        => array(
									'type' => 'integer',
								),
								'post_type' => array(
									'type' => 'string',
								),
								'title'     => array(
									'type' => 'string',
								),
							),
						),
						'childs' => array(
							'type'  => 'array',
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'id'        => array(
										'type' => 'integer',
									),
									'post_type' => array(
										'type' => 'string',
									),
									'title'     => array(
										'type' => 'string',
									),
								),
							),
						),
					),
				),
			),
		);
	}

	public function response(): array {
		return array(
			'status' => 'ok',
		);
	}

	public function get_summary(): string {
		return 'Updates course drip content';
	}

	public function get_description(): string {
		return 'Updates course drip content';
	}
}
