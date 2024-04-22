<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class QuizStyle extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Quiz style',
		'enum'        => array( 'pagination', 'default', 'global' ),
	);
}
