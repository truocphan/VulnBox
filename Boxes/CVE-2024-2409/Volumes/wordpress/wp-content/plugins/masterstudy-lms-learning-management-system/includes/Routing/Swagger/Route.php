<?php

namespace MasterStudy\Lms\Routing\Swagger;

abstract class Route {
	/**
	 * Route Summary
	 */
	abstract public function get_summary(): string;

	/**
	 * Route Description
	 */
	abstract public function get_description(): string;

	/**
	 * Get Route Doc
	 */
	public function __invoke(): array {
		$schema = new Schema( $this );

		$swagger = array(
			'summary'     => $this->get_summary(),
			'description' => $this->get_description(),
		);

		if ( $schema->has( 'request' ) ) {
			$swagger['args'] = $schema->get( 'request' );
		}

		if ( $schema->has( 'responses' ) ) {
			$swagger['responses'] = $schema->get( 'responses' );
		}

		return $swagger;
	}

	/**
	 * Return Route Properties
	 */
	public function get_properties( string $type ): array {
		return method_exists( $this, $type ) ? $this->{$type}() : array();
	}

	/**
	 * Route Properties to Example
	 */
	public function to_example( array $properties ): array {
		$types = array_column( $properties, 'type' );

		if ( ! empty( $types ) ) {
			return array_combine(
				array_keys( $properties ),
				$types
			);
		}

		return array();
	}
}
