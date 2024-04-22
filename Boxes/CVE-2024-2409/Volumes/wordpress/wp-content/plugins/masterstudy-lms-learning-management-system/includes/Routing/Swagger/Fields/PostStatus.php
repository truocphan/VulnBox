<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class PostStatus extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Post Status',
		'enum'        => array( 'publish', 'pending', 'draft' ),
	);
}
