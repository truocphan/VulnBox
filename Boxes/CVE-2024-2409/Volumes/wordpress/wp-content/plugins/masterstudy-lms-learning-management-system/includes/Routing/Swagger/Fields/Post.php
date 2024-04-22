<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class Post extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'            => array(
			'type' => 'integer',
		),
		'slug'          => array(
			'type' => 'string',
		),
		'title'         => array(
			'type' => 'string',
		),
		'author'        => array(
			'type' => 'integer',
		),
		'excerpt'       => array(
			'type' => 'string',
		),
		'content'       => array(
			'type' => 'string',
		),
		'status'        => array(
			'type'        => 'string',
			'description' => 'Post Status',
			'enum'        => array( 'publish', 'pending', 'draft' ),
		),
		'post_date'     => array(
			'type'   => 'string',
			'format' => 'date-time',
		),
		'post_modified' => array(
			'type'   => 'string',
			'format' => 'date-time',
		),
		'post_parent'   => array(
			'type' => 'integer',
		),
		'guid'          => array(
			'type'   => 'string',
			'format' => 'uri',
		),
		'post_type'     => array(
			'type' => 'string',
		),
	);
}
