<?php

namespace MasterStudy\Lms\Routing;

use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;
use WP_REST_Request;
use WP_REST_Response;

/**
 * @package MasterStudy\Lms
 */
class Router {

	/**
	 * Used for WP route registration
	 *
	 * @var string
	 */
	public const ROUTE_NAME_KEY = '_masterstudy_lms_route_name';

	/**
	 * Stores any created routes.
	 *
	 * @var RouteCollection<Route>
	 */
	protected RouteCollection $route_collection;

	/**
	 * @var array
	 */
	protected $middleware = array();

	/**
	 * Url prefix, used for namespace
	 *
	 * @var string
	 */
	protected string $prefix;

	/**
	 * Options for route group
	 */
	protected array $group_options = array();

	public function __construct( string $prefix, RouteCollection $route_collection ) {
		$this->prefix           = '/' . trim( $prefix, '/' );
		$this->route_collection = $route_collection;
	}

	/**
	 * Add route for GET method
	 *
	 * @param array|callable $handler
	 *
	 * @return Route
	 */
	public function get( string $uri, $handler, string $doc = '' ): Route {
		return $this->route( array( 'GET', 'HEAD' ), $uri, $handler, $doc );
	}

	/**
	 * Add route for DELETE method
	 *
	 * @param array|callable $handler
	 */
	public function delete( string $uri, $handler, string $doc = '' ): Route {
		return $this->route( 'DELETE', $uri, $handler, $doc );
	}

	/**
	 * Add route for PATCH method
	 *
	 * @param array|callable $handler
	 */
	public function patch( string $uri, $handler, string $doc = '' ): Route {
		return $this->route( 'PATCH', $uri, $handler, $doc );
	}

	/**
	 * Add route for POST method
	 *
	 * @param array|callable $handler
	 */
	public function post( string $uri, $handler, string $doc = '' ): Route {
		return $this->route( 'POST', $uri, $handler, $doc );
	}

	/**
	 * Add route for PUT method
	 *
	 * @param array|callable $handler
	 */
	public function put( string $uri, $handler, string $doc = '' ): Route {
		return $this->route( 'PUT', $uri, $handler, $doc );
	}

	/**
	 * Adds route to the Router.
	 *
	 * @param string|array $methods
	 * @param array{uses: callable|string, middleware: array}|callable $handler
	 */
	public function route( $methods, string $uri, $handler, string $doc ): Route {
		if ( ! empty( $this->group_options ) ) {
			if ( ! is_array( $handler ) ) {
				$handler = array( 'uses' => $handler );
			}

			if ( isset( $this->group_options['middleware'] ) ) {
				$handler['middleware'] = array_merge(
					(array) $this->group_options['middleware'],
					(array) ( $handler['middleware'] ?? array() )
				);
			}
		}

		$uri   = $this->prefix( $uri );
		$route = new Route( (array) $methods, $uri, $handler, $doc );

		if ( $route->get_name() === null ) {
			$route->name( $this->generate_route_name() );
		}

		$this->route_collection->add( $route );

		return $route;
	}

	/**
	 * @param class-string<MiddlewareInterface>|array<MiddlewareInterface>|array<callable> $middleware
	 */
	public function middleware( $middleware ): self {
		if ( ! is_array( $middleware ) ) {
			$middleware = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		}

		$this->middleware = array_merge(
			$this->middleware,
			$middleware
		);

		return $this;
	}

	/**
	 * @param array{prefix: string, middleware: array} $options
	 */
	public function group( array $options, callable $callback ): void {
		$this->group_options = $options;

		$old_prefix = $this->prefix;
		if ( isset( $options['prefix'] ) ) {
			$this->prefix .= '/' . trim( $options['prefix'], '/' );
		}

		$callback( $this );

		$this->prefix        = $old_prefix;
		$this->group_options = array();
	}

	public function get_prefix(): string {
		return $this->prefix;
	}

	public function get_routes(): RouteCollection {
		return $this->route_collection;
	}

	/**
	 * Handles request and calls matched route controller
	 *
	 * @return mixed
	 */
	public function handle( WP_REST_Request $request ) {
		$route = $this->get_route( $request );

		if ( null === $route ) {
			return new WP_REST_Response(
				array(
					'error_code' => 'route_not_found',
					'message'    => esc_html__( 'Route not found', 'masterstudy-lms-learning-management-system' ),
				),
				404
			);
		}

		$controller = function () use ( $route, $request ) {
			list( $controller, $arguments ) = $this->resolve_controller( $route, $request );

			return call_user_func_array( $controller, $arguments );
		};

		$stack = $route->get_middleware();

		if ( empty( $stack ) ) {
			$stack = $this->middleware;
		}

		return $this->process_pipeline( $request, $stack, $controller );
	}

	/**
	 * Load routes from file
	 *
	 * @param string $routes path to routes file
	 */
	public function load_routes( string $routes ): void {
		( function ( $router ) use ( $routes ) {
			require $routes;
		} )( $this );
	}

	/**
	 * Resolves and instantiate controller class with dependencies
	 *
	 * @return array{0: callable, 1: array}
	 * @throws ReflectionException
	 */
	protected function resolve_controller( Route $route, WP_REST_Request $request ): array {
		$callback = $this->resolve_callback( $route );

		$params                          = $request->get_url_params();
		$params[ get_class( $request ) ] = $request;

		$arguments = $this->resolve_dependencies( $callback, $params );

		return array(
			$callback,
			$arguments,
		);
	}

	/**
	 * Trying to find matching rout
	 */
	protected function get_route( $request ): ?Route {
		$attrs = $request->get_attributes();

		$name = $attrs[ self::ROUTE_NAME_KEY ] ?? null;
		if ( $name ) {
			return $this->route_collection->get_by_name( $name );
		}

		return null;
	}

	/**
	 * Returns prefixed url
	 */
	protected function prefix( string $uri ): string {
		return "$this->prefix/" . trim( $uri, '/' );
	}

	/**
	 * Generate random route name
	 */
	protected function generate_route_name(): string {
		return uniqid( 'route:', true );
	}

	/**
	 * Create and process pipeline
	 * Based on laravel pipeline
	 *
	 * @return mixed
	 */
	protected function process_pipeline( $request, array $stack, callable $controller ) {
		$pipeline = new RouterPipeline( $request, $stack );

		return $pipeline->then( $controller );
	}


	/**
	 * @param callable $callback
	 * @param array<string, mixed> $parameters
	 *
	 * @return array
	 * @throws ReflectionException
	 */
	protected function resolve_dependencies(
		callable $callback,
		array $parameters
	): array {
		$dependencies = array();

		if ( is_array( $callback ) ) {
			$reflection = new ReflectionMethod( $callback[0], $callback[1] );
		} else {
			$reflection = new ReflectionFunction( $callback );
		}

		foreach ( $reflection->getParameters() as $parameter ) {
			$param_name = $parameter->getName();
			if ( array_key_exists( $param_name, $parameters ) ) {
				$dependencies[] = $parameters[ $param_name ];
				continue;
			}

			// todo add DI container
			$parameter_type = $parameter->getType();
			if ( $parameter_type instanceof ReflectionNamedType && ! $parameter_type->isBuiltin() ) {
				$class_name = $parameter_type->getName();

				if ( isset( $parameters[ $class_name ] ) ) {
					$dependencies[] = $parameters[ $class_name ];
					continue;
				}
			}

			if ( $parameter->isDefaultValueAvailable() ) {
				$dependencies[] = $parameter->getDefaultValue();
				continue;
			}

			throw new RuntimeException( "Unable to resolve dependency [$parameter] in class {$parameter->getDeclaringClass()->getName()}" );
		}

		return $dependencies;
	}

	/**
	 * @param Route $route
	 *
	 * @return callable
	 */
	protected function resolve_callback( Route $route ) {
		if ( ! is_string( $route->handler['uses'] ) ) {
			return $route->handler['uses'];
		}

		$method = '__invoke';
		if ( strpos( $route->handler['uses'], '@' ) !== false ) {
			list( $class, $method ) = explode( '@', $route->handler['uses'], 2 );
		} else {
			$class = $route->handler['uses'];
		}

		// todo add ability instantiate controller by DI container
		$controller = new $class();

		return array( $controller, $method );
	}
}
