<?php

namespace MasterStudy\Lms\Routing\Swagger;

class Schema {
	/**
	 * Route Request
	 */
	private array $request;

	/**
	 * Route Responses
	 */
	private array $responses;

	/**
	 * Current Route
	 */
	protected Route $route;

	public function __construct( Route $route ) {
		$this->route = $route;

		$this->set_request();
		$this->set_response();
	}

	/**
	 * Route Request
	 */
	protected function set_request(): void {
		$request = $this->route->get_properties( 'request' );

		if ( ! empty( $request ) ) {
			$this->request = array(
				'body' => array(
					'in'       => 'body',
					'required' => false,
					'type'     => 'object',
					'schema'   => array(
						'type'       => 'object',
						'properties' => $request,
						'example'    => $this->route->to_example( $request ),
					),
				),
			);
		}
	}

	/**
	 * Route Response
	 */
	protected function set_response(): void {
		$response = $this->route->get_properties( 'response' );

		if ( ! empty( $response ) ) {
			$success = array(
				'description' => 'Success',
				'schema'      => array(
					'type'       => 'object',
					'properties' => $response,
					'example'    => $this->route->to_example( $response ),
				),
			);

			$this->responses[200] = $success;
		}
	}

	/**
	 * Check if Property is Empty
	 */
	public function has( string $property ): bool {
		return ! empty( $this->{$property} );
	}

	/**
	 * Return Request Args
	 */
	public function get( string $type ): array {
		return $this->{$type} ?? array();
	}
}
