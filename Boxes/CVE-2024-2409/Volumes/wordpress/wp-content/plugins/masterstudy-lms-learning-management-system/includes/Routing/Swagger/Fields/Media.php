<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class Media extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'            => array(
			'type' => 'integer',
		),
		'date'          => array(
			'type'   => 'string',
			'format' => 'date-time',
		),
		'slug'          => array(
			'type' => 'string',
		),
		'status'        => array(
			'type' => 'string',
		),
		'type'          => array(
			'type' => 'string',
		),
		'link'          => array(
			'type'   => 'string',
			'format' => 'uri',
		),
		'source_url'    => array(
			'type'   => 'string',
			'format' => 'uri',
		),
		'title'         => array(
			'type'       => 'object',
			'properties' => array(
				'raw'      => array(
					'type' => 'string',
				),
				'rendered' => array(
					'type' => 'string',
				),
			),
		),
		'author'        => array(
			'type' => 'integer',
		),
		'caption'       => array(
			'type'       => 'object',
			'properties' => array(
				'raw'      => array(
					'type' => 'string',
				),
				'rendered' => array(
					'type' => 'string',
				),
			),
		),
		'media_type'    => array(
			'type' => 'string',
		),
		'mime_type'     => array(
			'type' => 'string',
		),
		'media_details' => array(
			'type'       => 'object',
			'properties' => array(
				'width'    => array(
					'type' => 'integer',
				),
				'height'   => array(
					'type' => 'integer',
				),
				'file'     => array(
					'type' => 'string',
				),
				'filesize' => array(
					'type' => 'integer',
				),
			),
		),
	);
}
