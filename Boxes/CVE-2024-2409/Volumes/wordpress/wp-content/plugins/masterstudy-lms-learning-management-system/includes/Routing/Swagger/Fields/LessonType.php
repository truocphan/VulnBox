<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class LessonType extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Lesson type',
		'enum'        => array( 'text', 'video', 'steam', 'zoom_conference' ),
	);
}
