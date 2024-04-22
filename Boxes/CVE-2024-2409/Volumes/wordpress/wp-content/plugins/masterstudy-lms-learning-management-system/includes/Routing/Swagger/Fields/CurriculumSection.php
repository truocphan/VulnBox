<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class CurriculumSection extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'    => array(
			'type' => 'integer',
		),
		'title' => array(
			'type' => 'string',
		),
		'order' => array(
			'type' => 'integer',
		),
	);
}
