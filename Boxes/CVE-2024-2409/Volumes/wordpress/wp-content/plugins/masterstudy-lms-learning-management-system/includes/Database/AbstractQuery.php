<?php

namespace MasterStudy\Lms\Database;

abstract class AbstractQuery {
	protected array $fillable;

	abstract public function get_table(): string;

	public function __construct( array $properties = array() ) {
		foreach ( $properties as $property => $value ) {
			$this->{$property} = maybe_unserialize( $value );
		}
	}

	public function __call( string $function, array $arguments ) {
		if ( 'get_' === substr( $function, 0, 4 ) ) {
			$model_props = $this->properties();
			$property    = substr( $function, 4 );

			if ( array_key_exists( $property, $model_props ) ) {
				return $this->{$property};
			}
		}

		if ( 'set_' === substr( $function, 0, 4 ) ) {
			$model_props = $this->properties();
			$property    = substr( $function, 4 );

			if ( array_key_exists( $property, $model_props ) ) {
				$this->{$property} = $arguments[0];
			}
		}
	}

	public function get_primary_key(): string {
		return 'id';
	}

	public function get_searchable_fields(): array {
		return $this->fillable;
	}

	/**
	 * Get all the properties of this model as an array.
	 */
	public function to_array(): array {
		return $this->properties();
	}

	/**
	 * Convert complex objects to strings to insert into the database.
	 */
	public function flatten_props( array $props ): array {
		foreach ( $props as $property => $value ) {
			if ( is_object( $value ) && 'DateTime' === get_class( $value ) ) {
				$props[ $property ] = $value->format( 'Y-m-d H:i:s' );
			} elseif ( is_array( $value ) ) {
				// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				$props[ $property ] = serialize( $value );
			} elseif ( $value instanceof AbstractQuery ) {
				$props[ $property ] = $value->get_primary_key();
			}
		}

		return $props;
	}

	public function fillable_props( array $props ): array {
		$fillable_props = array();

		foreach ( $this->fillable as $property ) {
			$fillable_props[ $property ] = $props[ $property ];
		}

		return $fillable_props;
	}

	/**
	 * Return an array of all the properties for this model. By default, returns every class variable.
	 */
	public function properties(): array {
		return get_object_vars( $this );
	}

	/**
	 *  Run before saves model
	 */
	public function before_save(): void {}

	/**
	 * Save this model to the database. Will create a new record if the ID
	 * property isn't set, or update an existing record if the ID property is set.
	 *
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function save() {
		global $wpdb;

		$this->before_save();

		// Get the model's properties
		$props = $this->properties();
		// Flatten complex objects
		$props = $this->flatten_props( $props );

		$props = $this->fillable_props( $props );

		// Insert or update?
		if ( is_null( $props[ $this->get_primary_key() ] ) ) {
			$wpdb->insert( $this->get_table(), $props );
			$this->{$this->get_primary_key()} = $wpdb->insert_id;
		} else {
			$wpdb->update( $this->get_table(), $props, array( $this->get_primary_key() => $this->{$this->get_primary_key()} ) );
		}

		return ( empty( $wpdb->last_error ) ) ? $this : false;
	}

	/**
	 * Create a new model from the given data.
	 *
	 * @return self
	 */
	public function create( $properties ) {
		return new static( $properties );
	}

	/**
	 *  Run before delete model
	 */
	public function before_delete(): void {}

	/**
	 * Delete the model from the database. Returns true if it was successful
	 * or false if it was not.
	 */
	public function delete(): bool {
		global $wpdb;

		$this->before_delete();

		return $wpdb->delete( $this->get_table(), array( $this->get_primary_key() => $this->{$this->get_primary_key()} ) );
	}

	/**
	 * Find a specific model by a given property value.
	 *
	 * @return false|self
	 */
	public function find_one_by( string $property, string $value ) {
		global $wpdb;

		// Escape the value
		$value = esc_sql( $value );

		// Get the table name
		$table = $this->get_table();

		// Get the item
		$obj = $wpdb->get_row(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare( "SELECT * FROM `{$table}` WHERE `{$property}` = %s", $value ),
			ARRAY_A
		);

		// Return false if no item was found, or a new model
		return ( $obj ? $this->create( $obj ) : false );
	}

	/**
	 * Find a specific model by it's unique ID.
	 *
	 * @return false|self
	 */
	public function find_one( int $id ) {
		return $this->find_one_by( $this->get_primary_key(), $id );
	}

	/**
	 * Start a query to find models matching specific criteria.
	 */
	public function query(): Query {
		$query = new Query( get_called_class() );
		$query->set_searchable_fields( $this->get_searchable_fields() );
		$query->set_primary_key( $this->get_primary_key() );

		if ( isset( $this->sort_by ) ) {
			$query->sort_by( $this->sort_by );
		}

		return $query;
	}

	/**
	 * Return EVERY instance of this model from the database, with NO filtering.
	 */
	public function all(): array {
		global $wpdb;

		// Get the table name
		$table = $this->get_table();

		// Get the items
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$results = $wpdb->get_results( "SELECT * FROM `{$table}`" );

		foreach ( $results as $index => $result ) {
			$results[ $index ] = $this->create( (array) $result );
		}

		return $results;
	}

	/**
	 * Return configured table prefix.
	 */
	public function get_table_prefix(): string {
		global $wpdb;

		return $wpdb->prefix;
	}

	/**
	 * @return $this
	 */
	public function load_data( $data ) {
		$model = $this;

		foreach ( $data as $key => $val ) {
			$model->$key = $val;
		}

		return $model;
	}
}
