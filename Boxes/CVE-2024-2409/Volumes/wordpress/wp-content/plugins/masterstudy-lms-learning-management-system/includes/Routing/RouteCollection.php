<?php

namespace MasterStudy\Lms\Routing;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class RouteCollection implements IteratorAggregate {

	/**
	 * List of routes grouped by HTTP method
	 *
	 * @var array<string, array<string, Route>>
	 */
	protected array $routes = array();

	/**
	 * List of routes grouped by route name
	 *
	 * @var array<string, Route>
	 */
	protected array $names = array();

	/**
	 * Add a route to the collection
	 */
	public function add( Route $route ): void {
		foreach ( $route->methods as $method ) {
			$this->routes[ $method ][ $route->uri ] = $route;
		}
		$name = $route->get_name();
		if ( $name ) {
			$this->names[ $name ] = $route;
		}
	}

	/**
	 * Get routes for a give HTTP method
	 */
	public function get( string $method ): array {
		return $this->routes[ $method ] ?? array();
	}

	/**
	 * Get route with given name
	 */
	public function get_by_name( $name ): ?Route {
		return $this->names[ $name ] ?? null;
	}

	/**
	 * @return Traversable<Route>
	 */
	public function getIterator(): Traversable {
		return new ArrayIterator( array_values( $this->names ) );
	}
}
