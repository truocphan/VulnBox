<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class LessonVideoType extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'        => 'string',
		'description' => 'Lesson video type',
		'enum'        => array( 'embed', 'ext_link', 'html', 'presto_player', 'shortcode', 'vimeo', 'youtube' ),
	);
}
