<?php

namespace MasterStudy\Lms\Routing\Swagger;

abstract class Field {
	/**
	 * Object Properties
	 */
	public static array $properties = array();

	/**
	 * Response Object
	 */
	public static function as_response(): array {
		return static::$properties;
	}

	/**
	 * Single Object
	 */
	public static function as_object(): array {
		return array(
			'type'       => 'object',
			'properties' => static::$properties,
		);
	}

	/**
	 * List of Objects
	 */
	public static function as_array(): array {
		return array(
			'type'  => 'array',
			'items' => array(
				'type'       => 'object',
				'properties' => static::$properties,
			),
		);
	}

	/**
	 * List of Object Identity
	 */
	public static function as_list( $field = 'id' ): array {
		return array(
			'type'             => 'array',
			'uniqueItems'      => true,
			'collectionFormat' => 'multi',
			'items'            => array(
				'type'       => 'string',
				'properties' => array(
					$field,
				),
			),
		);
	}
}
