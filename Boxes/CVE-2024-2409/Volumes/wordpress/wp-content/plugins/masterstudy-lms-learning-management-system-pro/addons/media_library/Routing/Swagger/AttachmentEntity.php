<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Routing\Swagger;

use MasterStudy\Lms\Routing\Swagger\Field;

final class AttachmentEntity extends Field {
	public static array $properties = array(
		'id'       => array(
			'type'        => 'integer',
			'description' => 'File id',
			'example'     => 1,
		),
		'title'    => array(
			'type'        => 'string',
			'description' => 'File name',
			'example'     => 'image',
		),
		'url'      => array(
			'type'        => 'string',
			'description' => 'File url',
			'example'     => 'https://example.com/wp-content/uploads/2021/01/image.jpg',
		),
		'type'     => array(
			'type'        => 'string',
			'description' => 'Mime type',
			'example'     => 'image/jpeg',
		),
		'date'     => array(
			'type'        => 'string',
			'description' => 'Upload date',
			'example'     => '2021-01-01',
		),
		'modified' => array(
			'type'        => 'string',
			'description' => 'Last modified date',
			'example'     => '2021-01-01',
		),
		'size'     => array(
			'type'        => 'string',
			'description' => 'Human readable file size',
			'example'     => '1.2 MB',
		),
	);
}
