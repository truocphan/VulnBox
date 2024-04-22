<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum;

use MasterStudy\Lms\Routing\Swagger\Fields\CurriculumMaterial;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class CreateMaterial extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'post_id'    => array(
				'type'        => 'integer',
				'required'    => true,
				'description' => 'Related Post ID',
			),
			'section_id' => array(
				'type'     => 'integer',
				'required' => true,
			),
			'order'      => array(
				'type'     => 'integer',
				'required' => false,
				'min'      => 0,
			),
		);
	}

	public function response(): array {
		return array(
			'material' => CurriculumMaterial::as_object(),
		);
	}

	public function get_summary(): string {
		return 'Create Curriculum Material';
	}

	public function get_description(): string {
		return 'Creates Curriculum Material and returns created Curriculum Material';
	}
}
