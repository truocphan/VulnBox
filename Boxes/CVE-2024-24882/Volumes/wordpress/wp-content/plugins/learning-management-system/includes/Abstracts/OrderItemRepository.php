<?php

/**
 * Class order item repository.
 *
 * @package Masteriyo\Abstracts
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Masteriyo\Abstracts;

use Masteriyo\Database\Model;
use Masteriyo\Repository\AbstractRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Order Item repository.
 */
class OrderItemRepository extends AbstractRepository {

	/**
	 * Meta type. This should match up with
	 * the types available at https://developer.wordpress.org/reference/functions/add_metadata/.
	 * WP defines 'post', 'user', 'comment', and 'term'.
	 *
	 * @var string
	 */
	protected $meta_type = 'order_item';

	/**
	 * This only needs set if you are using a custom metadata type (for example payment tokens.
	 * This should be the name of the field your table uses for associating meta with objects.
	 * For example, in payment_tokenmeta, this would be payment_token_id.
	 *
	 * @var string
	 */
	protected $object_id_field_for_meta = 'order_item_id';

	/**
	 * Create a new order item in the database.
	 *
	 * @since 1.0.0
	 * @param Masteriyo\Models\Order\OrderItem $item Order item object.
	 */
	public function create( &$item ) {
		global $wpdb;

		$is_success = $wpdb->insert(
			$wpdb->prefix . 'masteriyo_order_items',
			/**
			 * Filters new order item data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data The order item data.
			 * @param \Masteriyo\Models\Order\OrderItem $item The order item object.
			 */
			apply_filters(
				'masteriyo_new_order_item',
				array(
					'order_item_name' => $item->get_name(),
					'order_item_type' => $item->get_type(),
					'order_id'        => $item->get_order_id(),
				),
				$item
			)
		);

		if ( $is_success && $wpdb->insert_id ) {
			$item->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $item, true );
			$item->save_meta_data();
			$item->apply_changes();
			$this->clear_cache( $item );

			/**
			 * Fires after creating new order item.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $order_item_id Order item ID.
			 * @param \Masteriyo\Models\Order\OrderItem $order_item Order item object.
			 * @param integer $order_id Order ID.
			 */
			do_action( 'masteriyo_new_order_item', $item->get_id(), $item, $item->get_order_id() );
		}

	}

	/**
	 * Update a order item in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\OrderItem $item Order item object.
	 */
	public function update( \Masteriyo\Database\Model &$item ) {
		global $wpdb;

		$changes = $item->get_changes();

		if ( array_intersect( array( 'order_item_name', 'order_id' ), array_keys( $changes ) ) ) {
			$wpdb->update(
				$wpdb->prefix . 'masteriyo_order_items',
				array(
					'order_item_name' => $item->get_name(),
					'order_item_type' => $item->get_type(),
					'order_id'        => $item->get_order_id(),
				),
				array( 'order_item_id' => $item->get_id() )
			);
		}

		$this->update_custom_table_meta( $item );
		$item->save_meta_data();
		$item->apply_changes();
		$this->clear_cache( $item );

		/**
		 * Fires after updating an order item.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $order_item_id Order item ID.
		 * @param \Masteriyo\Models\Order\OrderItem $order_item Order item object.
		 * @param integer $order_id Order ID.
		 */
		do_action( 'masteriyo_update_order_item', $item->get_id(), $item, $item->get_order_id() );
	}

	/**
	 * Remove an order item from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\OrderItem $item Order item object.
	 * @param array $args Array of args to pass to the delete method.
	 */
	public function delete( &$item, $args = array() ) {
		if ( $item->get_id() ) {
			global $wpdb;

			/**
			 * Fires before deleting an order item.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $order_item_id Order item ID.
			 */
			do_action( 'masteriyo_before_delete_order_item', $item->get_id() );

			$wpdb->delete( $wpdb->prefix . 'masteriyo_order_items', array( 'order_item_id' => $item->get_id() ) );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_order_itemmeta', array( 'order_item_id' => $item->get_id() ) );

			/**
			 * Fires after deleting an order item.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $order_item_id Order item ID.
			 */
			do_action( 'masteriyo_delete_order_item', $item->get_id() );

			$this->clear_cache( $item );
		}
	}

	/**
	 * Read a order item from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Order\OrderItem $item Order item object.
	 *
	 * @throws Exception If invalid order item.
	 */
	public function read( &$item ) {
		global $wpdb;

		// Get from cache if available.
		$data = wp_cache_get( 'item-' . $item->get_id(), 'order-items' );

		if ( false === $data ) {
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT order_id, order_item_name FROM {$wpdb->prefix}masteriyo_order_items WHERE order_item_id = %d LIMIT 1;", $item->get_id() ) );
			wp_cache_set( 'item-' . $item->get_id(), $data, 'order-items' );
		}

		if ( ! $data ) {
			throw new \Exception( __( 'Invalid order item.', 'masteriyo' ) );
		}

		$item->set_props(
			array(
				'order_id'        => $data->order_id,
				'order_item_name' => $data->order_item_name,
			)
		);
		$item->read_meta_data();
	}

	/**
	 * Clear meta cache.
	 *
	 * @param Masteriyo\Models\Order\OrderItem $item Order item object.
	 */
	public function clear_cache( &$item ) {
		wp_cache_delete( 'item-' . $item->get_id(), 'masteriyo-order-items' );
		wp_cache_delete( 'order-items-' . $item->get_order_id(), 'masteriyo-orders' );
		wp_cache_delete( $item->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch courses.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 *
	 * @return Masteriyo\Models\Order\OrderItem[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$order_id = absint( $query_vars['order_id'] );

		$order_items = array();
		$order       = get_post( $order_id );

		if ( is_null( $order ) || 'mto-order' !== $order->post_type ) {
			return $order_items;
		}

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_order_items WHERE order_id = %d",
				$order_id
			)
		);

		$item_objects = array_filter( array_map( array( $this, 'get_order_item_object' ), $items ) );

		$item_objects = array_filter(
			array_map(
				function( $item_object ) {
					return $this->get_order_item_meta( $item_object );
				},
				$item_objects
			)
		);

		return $item_objects;
	}

	/**
	 * Get order item object.
	 *
	 * @since 1.0.0
	 *
	 * @param stdClass $item Order item
	 * @return Masteriyo\Models\Order\OrderItem
	 */
	public function get_order_item_object( $item ) {
		$type = trim( $item->order_item_type );
		$type = empty( $type ) ? 'course' : $type;

		try {
			$item_obj = masteriyo( "order-item.{$type}" );
			$item_obj->set_id( $item->order_item_id );
			$item_obj->set_props(
				array(
					'order_id' => $item->order_id,
					'name'     => $item->order_item_name,
				)
			);
		} catch ( \Exception $error ) {
			error_log( $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}

		return $item_obj;
	}

	/**
	 * Get order item meta.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Order\OrderItem $item Order item object.
	 * @param stdClass $item List of all order item meta.
	 *
	 * @return
	 */
	public function get_order_item_meta( $item ) {
		$meta_values = $this->read_meta( $item );

		foreach ( $meta_values  as $meta_value ) {
			$function = "set_{$meta_value->key}";

			if ( is_callable( array( $item, $function ) ) ) {
				$item->$function( maybe_unserialize( $meta_value->value ) );
			}
		}

		return $item;
	}

	/**
	 * Get table name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		return "{$GLOBALS['wpdb']->prefix}masteriyo_order_items";
	}
}
