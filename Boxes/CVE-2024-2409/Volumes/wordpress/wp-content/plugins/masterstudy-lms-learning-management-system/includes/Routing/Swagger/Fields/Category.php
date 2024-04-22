<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class Category extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'     => array(
			'type' => 'integer',
		),
		'name'   => array(
			'type' => 'string',
		),
		'parent' => array(
			'type' => 'integer',
		),
	);
}
