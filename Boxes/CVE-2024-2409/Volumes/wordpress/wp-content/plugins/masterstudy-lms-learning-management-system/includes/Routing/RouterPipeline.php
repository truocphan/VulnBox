<?php

namespace MasterStudy\Lms\Routing;

use Closure;

/**
 * Inspired by Laravel Pipeline
 */
class RouterPipeline {
	/**
	 * @var mixed
	 */
	private $passable;
	private array $pipes;

	public function __construct( $passable, array $pipes ) {
		$this->passable = $passable;
		$this->pipes    = $pipes;
	}

	public function then( $destination ) {
		$pipeline = array_reduce(
			array_reverse( $this->pipes ),
			$this->prepare_stack(),
			$this->prepare_destination( $destination )
		);

		return $pipeline( $this->passable );
	}

	private function prepare_destination( callable $destination ): Closure {
		return function ( $passable ) use ( $destination ) {
			return $destination( $passable );
		};
	}


	/**
	 * @return Closure
	 */
	private function prepare_stack(): Closure {
		return function ( $stack, $pipe ) {
			return function ( $passable ) use ( $stack, $pipe ) {
				if ( is_callable( $pipe ) ) {
					return $pipe( $passable, $stack );
				}
				if ( is_string( $pipe ) ) {
					$pipe = new $pipe();
				}

				if ( $pipe instanceof MiddlewareInterface ) {
					return $pipe->process( $passable, $stack );
				}

				return $pipe( $passable, $stack );
			};
		};
	}
}
