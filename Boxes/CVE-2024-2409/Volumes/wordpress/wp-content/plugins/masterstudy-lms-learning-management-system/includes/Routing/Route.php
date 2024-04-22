<?php

namespace MasterStudy\Lms\Routing;

use Closure;
use LogicException;

class Route {

	/**
	 * List of HTTP methods
	 * @var array<string>
	 */
	public array $methods;

	/**
	 * Route URI
	 * @var string
	 */
	public string $uri;

	/**
	 * Route will be registered in WP
	 */
	public string $wp_uri;

	/**
	 * @var array{uses: string|callable, as: string, middleware: array}
	 */
	public array $handler;

	/**
	 * Documentation Handler
	 */
	public string $doc;

	/**
	 * @param array|callable $handler
	 */
	public function __construct( array $methods, string $uri, $handler, string $doc ) {
		$this->methods = $methods;
		$this->uri     = $uri;
		$this->wp_uri  = preg_replace( '/{([^}]+)}/', '(?P<$1>\w+)', $uri );
		$this->handler = $this->parse_handler( $handler );
		$this->doc     = $doc;
	}

	/**
	 * @param array|callable $handler
	 *
	 * @return array
	 */
	private function parse_handler( $handler ): array {
		$defaults = array(
			'uses'       => null,
			'middleware' => array(),
		);

		if ( is_callable( $handler ) ) {
			$defaults['uses'] = $handler;

			return $defaults;
		}

		if ( is_string( $handler ) ) {
			if ( strpos( $handler, '@' ) !== false ) {
				$defaults['uses'] = $handler;
			} else {
				$defaults['uses'] = "$handler@__invoke";
			}

			return $defaults;
		}

		if ( isset( $handler['uses'] ) ) {
			$defaults['uses'] = $handler['uses'];
		}

		if ( null === $defaults['uses'] ) {
			$defaults['uses'] = $this->missing_handler( $this->uri );
		}

		if ( isset( $handler['middleware'] ) ) {
			$defaults['middleware'] = (array) $handler['middleware'];
		}

		return $defaults;
	}

	private function missing_handler( string $uri ): Closure {
		return static function () use ( $uri ) {
			throw new LogicException( "Missed handler for $uri" );
		};
	}

	public function get_name() {
		return $this->handler['as'] ?? null;
	}

	public function name( string $name ): Route {
		$this->handler['as'] = $name;

		return $this;
	}

	public function get_middleware(): array {
		return $this->handler['middleware'] ?? array();
	}

	/**
	 * @param class-string<MiddlewareInterface>|array<MiddlewareInterface>|array<callable> $middleware
	 */
	public function middleware( $middleware ): self {
		if ( ! is_array( $middleware ) ) {
			$middleware = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		}

		$this->handler['middleware'] = array_merge(
			( $this->handler['middleware'] ?? array() ),
			(array) $middleware
		);
		return $this;
	}

	public function get_doc(): array {
		return class_exists( 'WP_API_SwaggerUI' ) && class_exists( $this->doc )
			? call_user_func( new $this->doc ) // phpcs:ignore WordPress.Classes.ClassInstantiation.MissingParenthesis
			: array();
	}
}
