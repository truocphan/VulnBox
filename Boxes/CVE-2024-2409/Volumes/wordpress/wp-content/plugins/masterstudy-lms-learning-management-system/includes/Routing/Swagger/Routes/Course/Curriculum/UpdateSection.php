<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum;

use MasterStudy\Lms\Routing\Swagger\Fields\CurriculumSection;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateSection extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'id'    => array(
				'type'     => 'integer',
				'required' => true,
			),
			'title' => array(
				'type'     => 'string',
				'required' => false,
			),
			'order' => array(
				'type'     => 'integer',
				'required' => false,
				'min'      => 0,
			),
		);
	}

	public function response(): array {
		return array(
			'section' => CurriculumSection::as_object(),
		);
	}

	public function get_summary(): string {
		return 'Update Curriculum Section';
	}

	public function get_description(): string {
		return 'Creates Course Curriculum Section and returns created Section';
	}
}
