<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class CurriculumMaterial extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'id'          => array(
			'type' => 'integer',
		),
		'title'       => array(
			'type' => 'string',
		),
		'post_id'     => array(
			'type'        => 'integer',
			'description' => 'Related Post ID',
		),
		'post_type'   => array(
			'type' => 'string',
			'enum' => array( 'stm-lessons', 'stm-quizzes', 'stm-assignments' ),
		),
		'lesson_type' => array(
			'type' => 'string',
			'enum' => array( 'text', 'video', 'stream', 'zoom_conference' ),
		),
		'section_id'  => array(
			'type' => 'integer',
		),
		'order'       => array(
			'type' => 'integer',
		),
	);
}
