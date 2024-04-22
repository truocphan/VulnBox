<?php

namespace MasterStudy\Lms\Pro\addons\sequential_drip_content\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetSettings extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array();
	}

	public function response(): array {
		return array(
			'drip_content' => array(
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

	public function get_summary(): string {
		return 'Get course sequential drip content';
	}

	public function get_description(): string {
		return 'Get course sequential drip content';
	}
}
