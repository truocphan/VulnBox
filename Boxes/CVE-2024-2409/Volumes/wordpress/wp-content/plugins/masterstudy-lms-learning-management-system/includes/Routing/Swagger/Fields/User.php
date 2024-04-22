<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class User extends Field {

	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'   => array(
			'type' => 'integer',
		),
		'name' => array(
			'type' => 'string',
		),
	);
}
