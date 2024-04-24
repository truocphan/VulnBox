<?php
/**
 * Wraps an array (meta data for now) and tells if there was any changes.
 *
 * The main idea behind this class is to avoid doing unneeded
 * SQL updates if nothing changed.
 *
 * @version 1.0.0
 * @package Masteriyo
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Meta data class.
 *
 * @since 1.0.0
 */
class MetaData implements \JsonSerializable {

	/**
	 * Current data for metadata
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $current_data;

	/**
	 * Metadata data
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $data;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $meta Data to wrap behind this function.
	 */
	public function __construct( $meta = array() ) {
		$this->current_data = $meta;
		$this->apply_changes();
	}

	/**
	 * When converted to JSON.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function jsonSerialize() : array {
		return $this->get_data();
	}

	/**
	 * Merge changes with data and clear.
	 *
	 * @since 1.0.0
	 */
	public function apply_changes() {
		$this->data = $this->current_data;
	}

	/**
	 * Creates or updates a property in the metadata object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to set.
	 * @param mixed  $value Value to set.
	 */
	public function __set( $key, $value ) {
		$this->current_data[ $key ] = $value;
	}

	/**
	 * Checks if a given key exists in our data. This is called internally
	 * by `empty` and `isset`.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to check if set.
	 *
	 * @return bool
	 */
	public function __isset( $key ) {
		return array_key_exists( $key, $this->current_data );
	}

	/**
	 * Returns the value of any property.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to get.
	 * @return mixed Property value or NULL if it does not exists
	 */
	public function __get( $key ) {
		if ( array_key_exists( $key, $this->current_data ) ) {
			return $this->current_data[ $key ];
		}
		return null;
	}

	/**
	 * Return data changes only.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_changes() {
		$changes = array();
		foreach ( $this->current_data as $id => $value ) {
			if ( ! array_key_exists( $id, $this->data ) || $value !== $this->data[ $id ] ) {
				$changes[ $id ] = $value;
			}
		}
		return $changes;
	}

	/**
	 * Return all data as an array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_data() : array {
		return $this->data;
	}
}
