<?php

namespace MasterStudy\Lms\Routing\Swagger\Fields;

use MasterStudy\Lms\Routing\Swagger\Field;

class CustomFields extends Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array(
		'type'    => array(
			'type'        => 'string',
			'description' => 'Custom Field type.',
			'enum'        => array( 'text', 'number', 'textarea', 'checkbox', 'radio', 'select' ),
		),
		'name'    => array(
			'type'        => 'string',
			'description' => 'Custom Field name.',
		),
		'label'   => array(
			'type'        => 'string',
			'description' => 'Custom Field label.',
		),
		'value'   => array(
			'type'        => 'string',
			'description' => 'Custom Field value.',
		),
		'options' => array(
			'type'        => 'array',
			'description' => 'Select & Radio field options.',
			'items'       => array(
				'type'       => 'object',
				'properties' => array(
					'value' => array(
						'type'        => 'string',
						'description' => 'Option value.',
					),
					'label' => array(
						'type'        => 'string',
						'description' => 'Option label.',
					),
				),
			),
		),
	);
}
