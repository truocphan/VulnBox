<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class Comment extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'                => array(
			'type' => 'integer',
		),
		'author'            => array(
			'type'        => 'integer',
			'description' => 'Author name',
		),
		'author_email'      => array(
			'type'        => 'string',
			'description' => 'Author email',
		),
		'author_url'        => array(
			'type'        => 'string',
			'description' => 'Author url',
		),
		'content'           => array(
			'type'        => 'string',
			'description' => 'Comment content',
		),
		'approved'          => array(
			'type'        => 'string',
			'description' => 'Comment status',
			'enum'        => array( '0', '1', 'spam', 'trash' ),
		),
		'parent'            => array(
			'type'        => 'integer',
			'description' => 'Parent comment id',
		),
		'user_id'           => array(
			'type'        => 'integer',
			'description' => 'Author id',
		),
		'post_id'           => array(
			'type'        => 'integer',
			'description' => 'Post id',
		),
		'post_type'         => array(
			'type'        => 'string',
			'description' => 'Post type',
		),
		'date'              => array(
			'type'        => 'string',
			'format'      => 'date-time',
			'description' => 'Comment date',
		),
		'date_gmt'          => array(
			'type'        => 'string',
			'format'      => 'date-time',
			'description' => 'Comment date GMT',
		),
		'author_avatar_url' => array(
			'type'        => 'string',
			'nullable'    => true,
			'description' => 'Author avatar url',
		),
	);
}
