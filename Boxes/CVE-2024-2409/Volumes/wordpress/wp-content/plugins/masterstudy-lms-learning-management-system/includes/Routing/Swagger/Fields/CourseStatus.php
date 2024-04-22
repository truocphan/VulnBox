<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class CourseStatus extends Field {

	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Course Status',
		'enum'        => array( 'hot', 'new', 'special' ),
	);
}
