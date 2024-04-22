<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum;

use MasterStudy\Lms\Routing\Swagger\Fields\CurriculumMaterial;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class ImportMaterials extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'material_ids' => array(
				'type'        => 'array',
				'description' => 'Materials IDs (Post IDs) to import.',
				'items'       => array(
					'type' => 'integer',
				),
				'required'    => true,
			),
			'section_id'   => array(
				'type'     => 'integer',
				'required' => true,
			),
		);
	}

	public function response(): array {
		return array(
			'materials' => CurriculumMaterial::as_array(),
		);
	}

	public function get_summary(): string {
		return 'Import Materials';
	}

	public function get_description(): string {
		return 'Import Materials into Section.';
	}
}
