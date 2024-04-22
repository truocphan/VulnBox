<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class FileMaterial extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'    => array(
			'type'        => 'integer',
			'description' => 'Attachment ID',
		),
		'label' => array(
			'type' => 'string',
		),
		'size'  => array(
			'type'        => 'integer',
			'description' => 'File size in bytes',
		),
		'type'  => array(
			'type'        => 'string',
			'description' => 'File mime type',
			'example'     => 'image/jpeg',
		),
		'url'   => array(
			'type'   => 'string',
			'format' => 'uri',
		),
	);
}
