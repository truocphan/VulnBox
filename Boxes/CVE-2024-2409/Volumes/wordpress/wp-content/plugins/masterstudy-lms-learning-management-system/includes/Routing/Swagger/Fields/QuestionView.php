<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

final class QuestionView extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Question View Type',
		'enum'        => array( 'grid', 'list', 'image' ),
	);
}
