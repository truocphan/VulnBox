<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class GetAll extends Route implements RequestInterface, ResponseInterface {

	/**
	 * Response Schema Properties
	 * @return array
	 */
	public function request(): array {
		return array(
			'filter'   => array(
				'type'        => 'object',
				'properties'  => array(
					'search'    => array(
						'type'        => 'string',
						'description' => 'Search by file name',
					),
					'file_type' => array(
						'type'        => 'string',
						'enum'        => array(
							'audio',
							'application',
							'image',
							'video',
						),
						'description' => 'Filter by file type',
					),
				),
				'description' => 'Filter params',
			),
			'sort_by'  => array(
				'type'        => 'string',
				'enum'        => array(
					'date',
					'title',
				),
				'description' => 'Sort by',
			),
			'per_page' => array(
				'type'        => 'integer',
				'description' => 'Number of files to return',
			),
			'offset'   => array(
				'type'        => 'integer',
				'description' => 'Pagination offset',
			),
		);
	}

	/**
	 * Response Schema Properties
	 * @return array
	 */
	public function response(): array {
		return array(
			'count' => array(
				'type'        => 'integer',
				'description' => 'Count of uploaded files',
			),
			'files' => AttachmentEntity::as_array(),
		);
	}

	/**
	 * Route Summary
	 * @return string
	 */
	public function get_summary(): string {
		return 'Get all uploaded media files';
	}

	/**
	 * Route Description
	 * @return string
	 */
	public function get_description(): string {
		return 'Get all uploaded media files';
	}
}
