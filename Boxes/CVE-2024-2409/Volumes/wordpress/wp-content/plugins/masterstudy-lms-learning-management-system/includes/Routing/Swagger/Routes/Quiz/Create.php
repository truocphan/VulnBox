<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Quiz;

use MasterStudy\Lms\Routing\Swagger\Fields\DurationMeasure;
use MasterStudy\Lms\Routing\Swagger\Fields\QuizStyle;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Create extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'title'            => array(
				'type' => 'string',
			),
			'content'          => array(
				'type' => 'string',
			),
			'coorect_answer'   => array(
				'type'        => 'boolean',
				'description' => 'Show correct answer',
			),
			'duration'         => array(
				'type'        => 'integer',
				'description' => 'Quiz duration',
			),
			'duration_measure' => DurationMeasure::as_response(),
			'excerpt'          => array(
				'type'        => 'string',
				'description' => 'Quiz Frontend description',
			),
			'passing_grade'    => array(
				'type'        => 'number',
				'description' => 'Passing grade (%)',
			),
			'random_questions' => array(
				'type'        => 'boolean',
				'description' => 'Randomize questions',
			),
			're_take_cut'      => array(
				'type'        => 'number',
				'description' => 'Points total cut after re-take (%)',
			),
			'style'            => QuizStyle::as_response(),
			'questions'        => array(
				'type'        => 'array',
				'items'       => array(
					'type' => 'integer',
				),
				'description' => 'Questions IDs',
			),
			'custom_fields'    => array(
				'type'        => 'object',
				'properties'  => array(
					'custom-field-key' => array(
						'description' => 'Custom Field key & value',
						'type'        => 'custom-field-value',
					),
				),
				'description' => 'List of Custom Fields',
			),
		);
	}

	public function response(): array {
		return array(
			'id' => array(
				'type' => 'integer',
			),
		);
	}

	public function get_summary(): string {
		return 'Create new quiz';
	}

	public function get_description(): string {
		return 'Create new quiz';
	}
}
