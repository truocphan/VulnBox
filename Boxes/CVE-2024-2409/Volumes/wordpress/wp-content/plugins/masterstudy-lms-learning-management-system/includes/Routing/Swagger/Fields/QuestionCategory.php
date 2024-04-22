<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

final class QuestionCategory extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'               => array(
			'type' => 'integer',
		),
		'term_id'          => array(
			'type' => 'integer',
		),
		'name'             => array(
			'type' => 'string',
		),
		'slug'             => array(
			'type' => 'string',
		),
		'term_group'       => array(
			'type' => 'integer',
		),
		'term_taxonomy_id' => array(
			'type' => 'integer',
		),
		'taxonomy'         => array(
			'type' => 'string',
		),
		'description'      => array(
			'type' => 'string',
		),
		'parent'           => array(
			'type' => 'integer',
		),
		'count'            => array(
			'type' => 'integer',
		),
	);
}
