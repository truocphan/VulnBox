<?php

namespace MasterStudy\Lms\Http\Serializers;

abstract class AbstractSerializer {
	abstract public function toArray( $data ): array; // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

	public function collectionToArray( array $collection ): array { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		return array_map(
			array( $this, 'toArray' ),
			$collection
		);
	}

	protected function when( $condition, $then, $default = null ) {
		if ( $condition ) {
			return is_callable( $then ) ? $then( $condition ) : $then;
		}

		return $default;
	}
}
