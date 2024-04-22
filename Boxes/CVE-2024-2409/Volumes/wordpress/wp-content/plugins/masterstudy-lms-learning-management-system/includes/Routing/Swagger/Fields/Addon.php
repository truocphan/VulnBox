<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class Addon extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'addon' => array(
			'type' => 'boolean',
		),
	);
}
