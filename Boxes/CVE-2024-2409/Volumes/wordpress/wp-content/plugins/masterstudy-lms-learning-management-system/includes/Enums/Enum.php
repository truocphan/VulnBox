<?php

namespace MasterStudy\Lms\Enums;

/**
 * PHP-8 basic enum polyfill.
 *
 * @property-read string $value
 * @property-read string $name
 */
abstract class Enum {
	protected $name;
	protected $value;

	private static array $definition_cache = array();
	private static array $instances        = array();

	/**
	 * @return array<static>
	 */
	public static function cases(): array {
		$instances = array_map(
			static function ( $value ) {
				return static::from( $value );
			},
			static::find_definition()
		);

		return array_values( $instances );
	}

	/**
	 * @param string|int $value
	 *
	 * @return static
	 */
	public static function from( $value ) {
		$enum = new static( $value );

		if ( ! isset( self::$instances[ static::class ][ $enum->value ] ) ) {
			self::$instances[ static::class ][ $enum->value ] = $enum;
		}

		return self::$instances[ static::class ][ $enum->value ];
	}

	private static function find_definition() {
		if ( ! isset( static::$definition_cache[ static::class ] ) ) {
			$r = new \ReflectionClass( static::class );

			static::$definition_cache[ static::class ] = $r->getConstants();
		}

		return static::$definition_cache[ static::class ];
	}

	private function __construct( $value ) {
		if ( $value instanceof static ) {
			$this->name  = $value->name;
			$this->value = $value->value;
			return;
		}

		$definition = self::find_definition();
		$name       = array_search( $value, $definition, true );

		if ( false === $name ) {
			throw new \InvalidArgumentException( sprintf( 'Value "%s" is not defined.', $value ) );
		}

		$this->name  = $name;
		$this->value = $value;
	}

	public function __get( $name ) {
		if ( 'value' === $name || 'name' === $name ) {
			return $this->$name;
		}

		throw new \RuntimeException( "Property $name does not exist." );
	}

	public function __toString() {
		return (string) $this->value;
	}

	public static function __callStatic( $name, $arguments ) {
		$definition = self::find_definition();
		return static::from( $definition[ $name ] ?? null );
	}
}
