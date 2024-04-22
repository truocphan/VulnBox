<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum;

use MasterStudy\Lms\Routing\Swagger\Fields\CurriculumMaterial;
use MasterStudy\Lms\Routing\Swagger\Fields\CurriculumSection;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetCurriculum extends Route implements ResponseInterface {
	public function response(): array {
		return array(
			'sections'  => CurriculumSection::as_array(),
			'materials' => CurriculumMaterial::as_array(),
			'scorm'     => array(
				'type'        => 'object',
				'properties'  => array(
					'error'         => array(
						'type'        => 'string',
						'description' => 'Error message if any',
					),
					'path'          => array(
						'type'        => 'string',
						'description' => 'Path to the scorm package',
					),
					'url'           => array(
						'type'        => 'string',
						'description' => 'Url to the scorm package',
					),
					'scorm_version' => array(
						'type'        => 'string',
						'description' => 'Scorm version',
					),
				),
				'description' => 'Scorm package data if addon is active and course has scorm package attached',
			),
		);
	}

	public function get_summary(): string {
		return 'Get Course Curriculum';
	}

	public function get_description(): string {
		return 'Returns Course Curriculum Sections and Materials';
	}
}
