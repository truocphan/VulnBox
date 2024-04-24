<?php
/**
 * Abstract Model.
 *
 * @since 1.0.0
 * @class Model
 * @package Masteriyo\Database
 */

namespace Masteriyo\Database;

use Masteriyo\ModelException;
use Masteriyo\MetaData;
use Masteriyo\DateTime;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Model Class.
 *
 * @since 1.0.0
 * @package Masteriyo\Database
 */
abstract class Model {

	/**
	 * ID for this object.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Primary key column name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $primary_key = 'id';

	/**
	 * Core data for this object. Name value pairs.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array();

		/**
	 * Core data changes for this object.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $changes = array();

	/**
	 * This is false until the object is read from the DB.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $object_read = false;

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $object_type = 'data';

	/**
	 * Extra data for this object. Name value pairs (name + default value).
	 * Used as a standard way for sub classes (like product types) to add
	 * additional information to an inherited class.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $extra_data = array();

	/**
	 * Set to _data on construct so we can track and reset data if needed.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $default_data = array();

	/**
	 * Contains a reference to the data store for this class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	protected $repository;

	/**
	 * Stores meta in cache for future reads.
	 * A group must be set to to enable caching.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $cache_group = '';

	/**
	 * Stores additional meta data.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $meta_data = array();

	/**
	 * Features supported by the model.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $supports = array();

	/**
	 * Default constructor
	 *
	 * @param integer $read
	 */
	public function __construct( $read = 0 ) {
		$this->data         = array_merge( $this->data, $this->extra_data );
		$this->default_data = $this->data;

	}

	/**
	 * Only store the object ID to avoid serializing the data object instance.
	 *
	 * @return array
	 */
	public function __sleep() {
		return array( 'id' );
	}

	/**
	 * Re-run the constructor with the object ID.
	 *
	 * If the object no longer exists, remove the ID.
	 */
	public function __wakeup() {
		try {
			$this->__construct( absint( $this->id ) );
		} catch ( \Exception $e ) {
			$this->set_id( 0 );
			$this->set_object_read( true );
		}
	}

	/**
	 * Get the repository.
	 *
	 * @since 1.0.0
	 *
	 * @return \Masteriyo\Repository\RepositoryInterface
	 */
	public function get_repository() {
		return $this->repository;
	}

	/**
	 * Get ID.
	 *
	 * @since 1.0.0
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set ID.
	 *
	 * @since 1.0.0
	 * @param int $id ID.
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Set all props to default values.
	 *
	 * @since 1.0.0
	 */
	public function set_defaults() {
		$this->data    = $this->default_data;
		$this->changes = array();
		$this->set_object_read( false );
	}

	/**
	 * Set object read property.
	 *
	 * @since 1.0.0
	 * @param boolean $read Should read?.
	 */
	public function set_object_read( $read = true ) {
		$this->object_read = (bool) $read;
	}

	/**
	 * Get object read property.
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public function get_object_read() {
		return (bool) $this->object_read;
	}

	/**
	 * Return data changes only.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_changes() {
		return $this->changes;
	}

	/**
	 * Merge changes with data and clear.
	 *
	 * @since 1.0.0
	 */
	public function apply_changes() {
		$this->data    = array_replace_recursive( $this->data, $this->changes );
		$this->changes = array();
	}


	/**
	 * Prefix for action and filter hooks on data.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	protected function get_hook_prefix() {
		return 'masteriyo_' . $this->object_type . '_get_';
	}

	/**
	 * Gets a prop for a getter method.
	 *
	 * Gets the value from either current pending changes, or the data itself.
	 * Context controls what happens to the value before it's returned.
	 *
	 * @since  1.0.0
	 * @param  string $prop Name of prop to get.
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return mixed
	 */
	protected function get_prop( $prop, $context = 'view' ) {
		$value = null;

		if ( array_key_exists( $prop, $this->data ) ) {
			$value = array_key_exists( $prop, $this->changes ) ? $this->changes[ $prop ] : $this->data[ $prop ];

			if ( 'view' === $context ) {
				/**
				 * Filters model prop value, if context is 'view'.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $value Prop value.
				 * @param Model $model Model object.
				 */
				$value = apply_filters( $this->get_hook_prefix() . $prop, $value, $this );
			}
		}

		return $value;
	}

	/**
	 * Set a collection of props in one go, collect any errors, and return the result.
	 * Only sets using public methods.
	 *
	 * @since  1.0.0
	 *
	 * @param array  $props Key value pairs to set. Key is the prop and should map to a setter function name.
	 * @param string $context In what context to run this.
	 *
	 * @return bool|WP_Error
	 */
	public function set_props( $props, $context = 'set' ) {
		$errors = false;

		foreach ( $props as $prop => $value ) {
			try {
				/**
				 * Checks if the prop being set is allowed, and the value is not null.
				 */
				if ( is_null( $value ) || in_array( $prop, array( 'prop', 'date_prop', 'meta_data' ), true ) ) {
					continue;
				}
				$setter = "set_$prop";

				if ( is_callable( array( $this, $setter ) ) ) {
					$this->{$setter}( $value );
				}
			} catch ( ModelException $e ) {
				if ( ! $errors ) {
					$errors = new \WP_Error();
				}

				$errors->add( $e->getErrorCode(), $e->getMessage() );
			}
		}

		return $errors && count( $errors->get_error_codes() ) ? $errors : true;
	}

	/**
	 * Sets a prop for a setter method.
	 *
	 * This stores changes in a special array so we can track what needs saving
	 * the the DB later.
	 *
	 * @since 1.0.0
	 * @param string $prop Name of prop to set.
	 * @param mixed  $value Value of the prop.
	 */
	protected function set_prop( $prop, $value ) {
		if ( array_key_exists( $prop, $this->data ) ) {
			if ( true === $this->object_read ) {
				if ( $value !== $this->data[ $prop ] || array_key_exists( $prop, $this->changes ) ) {
					$this->changes[ $prop ] = $value;
				}
			} else {
				$this->data[ $prop ] = $value;
			}
		}
	}


	/**
	 * Delete an object, set the ID to 0, and return result.
	 *
	 * @since  1.0.0
	 * @since  1.4.5 $children parameter is removed and replaced with $args parameter.
	 *
	 * @param  bool $force_delete Should the date be deleted permanently.
	 * @return bool result
	 */
	public function delete( $force_delete = false, $args = array() ) {
		if ( $this->repository ) {
			$this->repository->delete(
				$this,
				wp_parse_args(
					$args,
					array(
						'force_delete' => $force_delete,
					)
				)
			);
			$this->set_id( 0 );
			return true;
		}
		return false;
	}

	/**
	 * Restore an object and return result.
	 *
	 * @since  1.4.1
	 * @return bool result
	 */
	public function restore() {
		if ( $this->repository ) {
			$this->repository->restore(
				$this
			);
			return true;
		}
		return false;
	}

	/**
	 * Create or update based on object existence.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function save() {
		if ( ! $this->repository ) {
			return $this->get_id();
		}

		/**
		 * Trigger action before saving to the DB. Allows you to adjust object props before save.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Database\Model $this The object being saved.
		 * @param \Masteriyo\Repository\RepositoryInterface $repository The data store persisting the data.
		 *
		 * @return Model|WP_Error
		 */
		do_action( 'masteriyo_before_' . $this->object_type . '_object_save', $this, $this->repository );

		if ( $this->get_id() ) {
			$this->repository->update( $this );
		} else {
			$res = $this->repository->create( $this );
			if ( is_wp_error( $res ) ) {
				return $res;
			}
		}

		/**
		 * Trigger action after saving to the DB.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Database\Model $this The object being saved.
		 * @param \Masteriyo\Repository\RepositoryInterface $repository The repository persisting the data.
		 */
		do_action( 'masteriyo_after_' . $this->object_type . '_object_save', $this, $this->repository );

		return $this->get_id();
	}


	/**
	 * Change data to JSON format.
	 *
	 * @since  1.0.0
	 * @return string Data in JSON format.
	 */
	public function __toString() {
		return wp_json_encode( $this->get_data() );
	}

	/**
	 * Returns all data for this object.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_data() {
		return array_merge(
			array( $this->primary_key => $this->get_id() ),
			$this->data,
			array( 'meta_data' => $this->get_meta_data() )
		);
	}

	/**
	 * Returns array of expected data keys for this object.
	 *
	 * @since   1.0.0
	 * @return array
	 */
	public function get_data_keys() {
		return array_keys( $this->data );
	}

	/**
	 * Returns all "extra" data keys for an object (for sub objects like product types).
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_extra_data_keys() {
		return array_keys( $this->extra_data );
	}

	/**
	 * Get meta data by key.
	 *
	 * @since  1.0.0
	 * @param  string $key Meta Key.
	 * @param  bool   $single return first found meta with key, or all with $key.
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed
	 */
	public function get_meta( $key = '', $single = true, $context = 'view' ) {
		$function = 'get_' . $key;
		if ( is_callable( array( $this, $function ) ) ) {
			return $this->{$function}();
		}

		$meta_data  = $this->get_meta_data();
		$array_keys = array_keys( wp_list_pluck( $meta_data, 'key' ), $key, true );
		$value      = $single ? '' : array();

		if ( ! empty( $array_keys ) ) {
			// We don't use the $this->meta_data property directly here because we don't want meta with a null value (i.e. meta which has been deleted via $this->delete_meta_data()).
			if ( $single ) {
				$value = $meta_data[ current( $array_keys ) ]->value;
			} else {
				$value = array_intersect_key( $meta_data, array_flip( $array_keys ) );
				$value = wp_list_pluck( $value, 'value' );
			}
		}

		if ( 'view' === $context ) {
			/**
			 * Filters model meta data value, if context is 'view'.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $value Meta data value.
			 * @param Masteriyo\Database\Model $model Model object.
			 */
			$value = apply_filters( $this->get_hook_prefix() . $key, $value, $this );
		}

		return $value;
	}

	/**
	 * Get All Meta Data.
	 *
	 * @since 1.0.0
	 * @return array of objects.
	 */
	public function get_meta_data() {
		$this->maybe_read_meta_data();

		if ( is_null( $this->meta_data ) ) {
			return array();
		}

		return array_values( array_filter( $this->meta_data, array( $this, 'filter_null_meta' ) ) );
	}

	/**
	 * Filter null meta values from array.
	 *
	 * @since  1.0.0
	 * @param mixed $meta Meta value to check.
	 * @return bool
	 */
	protected function filter_null_meta( $meta ) {
		return ! is_null( $meta->value );
	}

	/**
	 * Read meta data if null.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function maybe_read_meta_data() {
		if ( is_null( $this->meta_data ) ) {
			$this->read_meta_data();
		}
	}

	/**
	 * Read meta data from the database. Ignore internal properties.
	 * Uses it's own caches because get_metadata doesn't provide meta_ids.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $force_read
	 * @return void
	 */
	public function read_meta_data( $force_read = false ) {
		// TODO Implement caching system here.

		// Bail early if the id is zero.
		if ( ! $this->get_id() ) {
			return;
		}

		// Bail early if there is not repository.
		if ( ! $this->repository ) {
			return;
		}

		$raw_meta_data = $this->repository->read_meta( $this );

		if ( $raw_meta_data ) {
			$this->meta_data = $raw_meta_data;
		}
	}

	/**
	 * Add meta data.
	 *
	 * @since 1.0.0
	 *
	 * @param string       $key Meta key.
	 * @param string|array $value Meta value.
	 * @param bool         $unique Should this be a unique key?.
	 */
	public function add_meta_data( $key, $value, $unique = false ) {
		$function = 'set_' . $key;

		if ( is_callable( array( $this, $function ) ) ) {
			return $this->{$function}( $value );
		}

		$this->maybe_read_meta_data();

		if ( $unique ) {
			$this->delete_meta_data( $key );
		}

		$this->meta_data[] = new MetaData(
			array(
				'key'   => $key,
				'value' => $value,
			)
		);
	}

	/**
	 * Update Meta Data in the database.
	 *
	 * @since 1.0.0
	 */
	public function save_meta_data() {
		if ( ! $this->repository || is_null( $this->meta_data ) ) {
			return;
		}

		foreach ( $this->meta_data as $array_key => $meta ) {
			if ( is_null( $meta->value ) ) {
				if ( ! empty( $meta->id ) ) {
					$this->repository->delete_meta( $this, $meta );
					unset( $this->meta_data[ $array_key ] );
				}
			} elseif ( empty( $meta->id ) ) {
				$meta->id = $this->repository->add_meta( $this, $meta );
				$meta->apply_changes();
			} else {
				if ( $meta->get_changes() ) {
					$this->repository->update_meta( $this, $meta );
					$meta->apply_changes();
				}
			}
		}

		// TODO Invalidate cache.
	}

	/**
	 * Delete meta data.
	 *
	 * @since 1.0.0
	 * @param string $key Meta key.
	 */
	public function delete_meta_data( $key ) {
		$this->maybe_read_meta_data();
		$array_keys = array_keys( wp_list_pluck( $this->meta_data, 'key' ), $key, true );

		if ( $array_keys ) {
			foreach ( $array_keys as $array_key ) {
				$this->meta_data[ $array_key ]->value = null;
			}
		}
	}

	/**
	 * Delete meta data.
	 *
	 * @since 1.4.8
	 * @param int $mid Meta ID.
	 */
	public function delete_meta_data_by_mid( $mid ) {
		$this->maybe_read_meta_data();
		$array_keys = array_keys( wp_list_pluck( $this->meta_data, 'id' ), (int) $mid, true );

		if ( $array_keys ) {
			foreach ( $array_keys as $array_key ) {
				$this->meta_data[ $array_key ]->value = null;
			}
		}
	}

	/**
	 * Get object type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Sets a date prop whilst handling formatting and datetime objects.
	 *
	 * @since 1.0.0
	 * @param string         $prop Name of prop to set.
	 * @param string|integer $value Value of the prop.
	 */
	protected function set_date_prop( $prop, $value ) {
		try {
			if ( empty( $value ) ) {
				$this->set_prop( $prop, null );
				return;
			}

			if ( is_a( $value, 'Masteriyo\DateTime' ) ) {
				$datetime = $value;
			} elseif ( is_numeric( $value ) ) {
				// Timestamps are handled as UTC timestamps in all cases.
				$datetime = new DateTIme( "@{$value}", new \DateTimeZone( 'UTC' ) );
			} else {
				// Strings are defined in local WP timezone. Convert to UTC.
				if ( 1 === preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|((-|\+)\d{2}:\d{2}))$/', $value, $date_bits ) ) {
					$offset    = ! empty( $date_bits[7] ) ? iso8601_timezone_to_offset( $date_bits[7] ) : masteriyo_timezone_offset();
					$timestamp = gmmktime( $date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1] ) - $offset;
				} else {
					$timestamp = masteriyo_string_to_timestamp( get_gmt_from_date( gmdate( 'Y-m-d H:i:s', masteriyo_string_to_timestamp( $value ) ) ) );
				}
				$datetime = new DateTime( "@{$timestamp}", new \DateTimeZone( 'UTC' ) );
			}

			// Set local timezone or offset.
			if ( get_option( 'timezone_string' ) ) {
				$datetime->setTimezone( new \DateTimeZone( masteriyo_timezone_string() ) );
			} else {
				$datetime->set_utc_offset( masteriyo_timezone_offset() );
			}

			$this->set_prop( $prop, $datetime );
		} catch ( \Exception $e ) {
			// TODO Log error message.
			$error = $e->getMessage();
		}
	}

	/**
	 * When invalid data is found, throw an exception unless reading from the DB.
	 *
	 * @throws ModelException Data Exception.
	 * @since 1.0.0
	 * @param string $code             Error code.
	 * @param string $message          Error message.
	 * @param int    $http_status_code HTTP status code.
	 * @param array  $data             Extra error data.
	 */
	protected function error( $code, $message, $http_status_code = 400, $data = array() ) {
		throw new ModelException( $code, $message, $http_status_code, $data );
	}

	/**
	 * Check if the key is an internal one.
	 *
	 * @since  1.4.8
	 * @param  string $key Key to check.
	 * @return bool   true if it's an internal key, false otherwise
	 */
	protected function is_internal_meta_key( $key ) {
		$internal_meta_key = ! empty( $key ) && $this->repository && in_array( $key, $this->repository->get_internal_meta_keys(), true );

		if ( ! $internal_meta_key ) {
			return false;
		}

		$has_setter_or_getter = is_callable( array( $this, 'set_' . $key ) ) || is_callable( array( $this, 'get_' . $key ) );

		if ( ! $has_setter_or_getter ) {
			return false;
		}
		masteriyo_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: %s: $key Key to check */
				__( 'Generic add/update/get meta methods should not be used for internal meta data, including "%s". Use getters and setters.', 'masteriyo' ),
				$key
			),
			'1.4.8'
		);

		return true;
	}

	/**
	 * Update meta data by key or ID, if provided.
	 *
	 * @since  1.4.8
	 *
	 * @param  string       $key Meta key.
	 * @param  string|array $value Meta value.
	 * @param  int          $meta_id Meta ID.
	 */
	public function update_meta_data( $key, $value, $meta_id = 0 ) {
		if ( $this->is_internal_meta_key( $key ) ) {
			$function = 'set_' . $key;

			if ( is_callable( array( $this, $function ) ) ) {
				return $this->{$function}( $value );
			}
		}

		$this->maybe_read_meta_data();

		$array_key = false;

		if ( $meta_id ) {
			$array_keys = array_keys( wp_list_pluck( $this->meta_data, 'id' ), $meta_id, true );
			$array_key  = $array_keys ? current( $array_keys ) : false;
		} else {
			// Find matches by key.
			$matches = array();
			foreach ( $this->meta_data as $meta_data_array_key => $meta ) {
				if ( $meta->key === $key ) {
					$matches[] = $meta_data_array_key;
				}
			}

			if ( ! empty( $matches ) ) {
				// Set matches to null so only one key gets the new value.
				foreach ( $matches as $meta_data_array_key ) {
					$this->meta_data[ $meta_data_array_key ]->value = null;
				}
				$array_key = current( $matches );
			}
		}

		if ( false !== $array_key ) {
			$meta        = $this->meta_data[ $array_key ];
			$meta->key   = $key;
			$meta->value = $value;
		} else {
			$this->add_meta_data( $key, $value, true );
		}
	}
}
