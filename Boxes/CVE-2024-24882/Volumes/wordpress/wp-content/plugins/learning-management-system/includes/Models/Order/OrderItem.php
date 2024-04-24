<?php
/**
 * OrderItem model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models\Order;

use Masteriyo\Database\Model;
use Masteriyo\Repository\OrderItemRepository;
use Masteriyo\Helper\Utils;
use Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * OrderItem model.
 *
 * @since 1.0.0
 */
class OrderItem extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'order_item';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'order_items';

	/**
	 * Stores order item data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'order_id' => 0,
		'name'     => '',
	);

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the order ID.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_order_id( $context = 'view' ) {
		return $this->get_prop( 'order_id', $context );
	}

	/**
	 * Get the course name.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get order item type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return '';
	}

	/**
	 * Get quantity.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_quantity() {
		return 1;
	}

	/**
	 * Get parent order object.
	 *
	 * @since 1.0.0
	 *
	 * @return Order
	 */
	public function get_order() {
		return masteriyo_get_order( $this->get_order_id() );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $order_id Course ID.
	 */
	public function set_order_id( $order_id ) {
		$this->set_prop( 'order_id', absint( $order_id ) );
	}

	/**
	 * Set the course name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Course ID.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', wp_check_invalid_utf8( $name ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Other Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * OrderItem type checking.
	 *
	 * @since 1.0.0
	 *
	 * @param  string|array $type Type.
	 * @return boolean
	 */
	public function is_type( $type ) {
		return is_array( $type ) ? in_array( $this->get_type(), $type, true ) : $type === $this->get_type();
	}


	/*
	|--------------------------------------------------------------------------
	| Meta Data Handling
	|--------------------------------------------------------------------------
	*/

	/**
	 * Expands things like term slugs before return.
	 *
	 * @param string $hideprefix  Meta data prefix, (default: _).
	 * @param bool   $include_all Include all meta data, this stop skip items with values already in the course name.
	 * @return array
	 */
	public function get_formatted_meta_data( $hideprefix = '_', $include_all = false ) {
		$formatted_meta    = array();
		$meta_data         = $this->get_meta_data();
		$hideprefix_length = ! empty( $hideprefix ) ? strlen( $hideprefix ) : 0;
		$course            = is_callable( array( $this, 'get_course' ) ) ? $this->get_course() : false;
		$order_item_name   = $this->get_name();

		foreach ( $meta_data as $meta ) {
			if ( empty( $meta->id ) || '' === $meta->value || ! is_scalar( $meta->value ) || ( $hideprefix_length && substr( $meta->key, 0, $hideprefix_length ) === $hideprefix ) ) {
				continue;
			}

			$meta->key     = rawurldecode( (string) $meta->key );
			$meta->value   = rawurldecode( (string) $meta->value );
			$attribute_key = str_replace( 'attribute_', '', $meta->key );
			$display_value = wp_kses_post( $meta->value );

			if ( taxonomy_exists( $attribute_key ) ) {
				$term = get_term_by( 'slug', $meta->value, $attribute_key );
				if ( ! is_wp_error( $term ) && is_object( $term ) && $term->name ) {
					$display_value = $term->name;
				}
			}

			// Skip items with values already in the course details area of the course name.
			if ( ! $include_all && $course ) {
				continue;
			}

			/**
			 * Filters order item display meta value.
			 *
			 * @since 1.0.0
			 *
			 * @param string $display_value Display value.
			 * @param object $meta Meta object.
			 * @param Masteriyo\Models\Order\OrderItem $order_item Order item object.
			 */
			$display_value = apply_filters( 'masteriyo_order_item_display_meta_value', $display_value, $meta, $this );

			$formatted_meta[ $meta->id ] = (object) array(
				'key'           => $meta->key,
				'value'         => $meta->value,
				'display_value' => wpautop( make_clickable( $display_value ) ),
			);
		}

		/**
		 * Filters order item formatted meta data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $formatted_meta Order item formatted meta data
		 * @param Masteriyo\Models\Order\OrderItem $order_item Order item object.
		 */
		return apply_filters( 'masteriyo_order_item_get_formatted_meta_data', $formatted_meta, $this );
	}

	/**
	 * Get the order items table name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return "{$wpdb->prefix}masteriyo_order_items";
	}
}
