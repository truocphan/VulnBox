<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

final class QuestionType extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Question Type',
		'enum'        => array(
			'fill_the_gap',
			'image_match',
			'item_match',
			'keywords',
			'multi_choice',
			'question_bank',
			'single_choice',
			'true_false',
		),
	);
}
