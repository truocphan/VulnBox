<?php
/**
 * OrderItemCourseRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\OrderItem;
use Masteriyo\Repository\OrderItemRepository;
use Masteriyo\Repository\RepositoryInterface;

/**
 * OrderItemCourseRepository class.
 */
class OrderItemCourseRepository extends OrderItemRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'course_id' => 'course_id',
		'quantity'  => 'quantitiy',
		'subtotal'  => 'subtotal',
		'total'     => 'total',
	);

	/**
	 * Read an order item.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\OrderItemCourse $order_item Cource object.
	 *
	 * @throws \Exception If invalid order item.
	 */
	public function read( &$order_item ) {
		if ( ! $order_item->get_id() ) {
			throw new \Exception( __( 'Invalid order item.', 'masteriyo' ) );
		}

		global $wpdb;

		// Get from cache if available.
		// $order_item_obj = masteriyo( 'cache' )->get( 'masteriyo-order-item-' . $order_item->get_id(), 'masteriyo-order-items' );
		$order_item_obj = false;

		if ( false === $order_item_obj ) {
			$table_name = $order_item->get_table_name();

			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}masteriyo_order_items WHERE order_item_id = %d LIMIT 1",
					$order_item->get_id()
				)
			);

			if ( ! is_array( $results ) || count( $results ) === 0 ) {
				throw new \Exception( __( 'Order item not found.', 'masteriyo' ) );
			}

			$order_item_obj = $results[0];
			// masteriyo( 'cache' )->set( 'masteriyo-item-' . $order_item->get_id(), $order_item_obj, 'masteriyo-order-items' );
		}

		$order_item->set_props(
			array(
				'order_id' => $order_item_obj->order_id,
				'name'     => $order_item_obj->order_item_name,
				'type'     => $order_item_obj->order_item_type,
			)
		);

		$this->read_order_item_data( $order_item );
		$this->read_extra_data( $order_item );
		$order_item->set_object_read( true );

		/**
		 * Fires after creating a order item course.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The order item ID.
		 * @param \Masteriyo\Models\Order\OrderItemCourse $object The order item object.
		 */
		do_action( 'masteriyo_order_item_read', $order_item->get_id(), $order_item );
	}

	/**
	 * Update an order item in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\OrderItemCourse $order_item Order Item object.
	 *
	 * @return void
	 */
	public function update( &$order_item ) {
		$changes = $order_item->get_changes();

		// Only update when there are changes.
		if ( count( $changes ) > 0 ) {
			$GLOBALS['wpdb']->update(
				$this->get_table_name(),
				array(
					'order_id'        => $order_item->get_order_id( 'edit' ),
					'order_item_name' => $order_item->get_name( 'edit' ),
					'order_item_type' => $order_item->get_type( 'edit' ),
				),
				array(
					'order_item_id' => $order_item->get_id(),
				)
			);
		}

		$this->update_custom_table_meta( $order_item );

		$order_item->apply_changes();
		$this->clear_cache( $order_item );

		/**
		 * Fires after updating a order item course.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The order item ID.
		 * @param \Masteriyo\Models\Order\OrderItemCourse $object The order item object.
		 */
		do_action( 'masteriyo_update_order_item', $order_item->get_id(), $order_item );
	}

	/**
	 * Delete an order item course from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\OrderItemCourse $order_item Order Item object.
	 * @param  array $args Array of args to pass to the delete method.
	 */
	public function delete( &$order_item, $args = array() ) {
		$id          = $order_item->get_id();
		$object_type = $order_item->get_object_type();

		if ( ! $id ) {
			return;
		}

		/**
		 * Fires before deleting a order item course.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The order item ID.
		 * @param \Masteriyo\Models\Order\OrderItemCourse $object The order item object.
		 */
		do_action( 'masteriyo_before_delete_' . $object_type, $id, $order_item );

		/**
		 * Delete order item metadata.
		 */
		$meta_table_info = $this->get_meta_table_info();
		$GLOBALS['wpdb']->delete( $meta_table_info['table'], array( $meta_table_info['object_id_field'] => $order_item->get_id() ) );

		/**
		 * Delete order item.
		 */
		$GLOBALS['wpdb']->delete(
			$this->get_table_name(),
			array(
				'order_item_id' => $order_item->get_id(),
			)
		);
		$this->clear_cache( $order_item );
		$order_item->set_id( 0 );

		/**
		 * Fires after deleting a order item course.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The order item ID.
		 * @param \Masteriyo\Models\Order\OrderItemCourse $object The order item object.
		 */
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $order_item );
	}

	/**
	 * Read order item data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param OrderItem $order_item Order Item object.
	 */
	protected function read_order_item_data( &$order_item ) {
		$id          = $order_item->get_id();
		$meta_values = $this->read_meta( $order_item );

		$set_props = array();

		$meta_values = array_reduce(
			$meta_values,
			function ( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$order_item->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the order item.
	 *
	 * @since 1.0.0
	 *
	 * @param OrderItem $order_item Order Item object.
	 */
	protected function read_extra_data( &$order_item ) {
		$meta_values = $this->read_meta( $order_item );

		foreach ( $order_item->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;

			if ( is_callable( array( $order_item, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$order_item->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch order items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return OrderItem[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$table_name    = $this->get_table_name();
		$offset        = isset( $query_vars['offset'] ) && is_numeric( $query_vars['offset'] ) ? absint( $query_vars['offset'] ) : 0;
		$per_page      = isset( $query_vars['limit'] ) && is_numeric( $query_vars['limit'] ) ? absint( $query_vars['limit'] ) : 10;
		$where_values  = array();
		$where_args    = array(
			'order_id'  => array(
				'placeholder' => '%d',
				'key'         => 'order_id',
			),
			'course_id' => array(
				'placeholder' => '%d',
				'key'         => 'course_id',
			),
			'name'      => array(
				'placeholder' => '%s',
				'key'         => 'name',
			),
			'type'      => array(
				'placeholder' => '%s',
				'key'         => 'type',
			),
			'quantity'  => array(
				'placeholder' => '%d',
				'key'         => 'quantity',
			),
		);
		$select_clause = "SELECT id FROM {$table_name} ";
		$where_clause  = ' WHERE 1=1 ';

		/**
		 * Prepare where clause (using placeholders instead of values).
		 */
		foreach ( $where_args as $db_key => $where ) {
			if ( ! empty( $query_vars[ $where['key'] ] ) ) {
				$where_clause  .= " AND {$db_key} = {$where['placeholder']} ";
				$where_values[] = $query_vars[ $where['key'] ];
			}
		}

		$is_paginate = isset( $query_vars['paginate'] ) && $query_vars['paginate'];

		/**
		 * Re-calculate the offset value for pagination.
		 */
		if ( $is_paginate && $query_vars['page'] > 0 ) {
			$offset += ( absint( $query_vars['page'] ) - 1 ) * $per_page;
		}

		$limit_clause = " LIMIT {$offset}, {$per_page} ";

		/**
		 * Query for order items.
		 */
		$sql_to_fetch_rows = "{$select_clause} {$where_clause} {$limit_clause}";
		$sql_to_fetch_rows = empty( $where_values ) ? $sql_to_fetch_rows : $wpdb->prepare( $sql_to_fetch_rows, $where_values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$results           = $wpdb->get_results( $sql_to_fetch_rows ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		/**
		 * Query for counting rows.
		 */
		$sql_to_count_rows = "SELECT COUNT(id) FROM {$table_name} {$where_clause}";
		$sql_to_count_rows = empty( $where_values ) ? $sql_to_count_rows : $wpdb->prepare( $sql_to_count_rows, $where_values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$total_items       = $wpdb->get_var( $sql_to_count_rows ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$is_return_ids = isset( $query_vars['return'] ) && 'ids' === $query_vars['return'];
		$order_items   = $is_return_ids ? $results : array_filter( array_map( 'masteriyo_get_order_item', $results ) );

		if ( $is_paginate ) {
			return (object) array(
				'order_items'   => $order_items,
				'total'         => absint( $total_items ),
				'max_num_pages' => ceil( absint( $total_items ) / $per_page ),
			);
		}

		return $order_items;
	}

	/**
	 * Delete order items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 *
	 * @return \Masteriyo\Models\Order\OrderItemCourse[]
	 */
	public function delete_batch( $query_vars ) {
		global $wpdb;
		$where_args   = array(
			'order_id'  => array(
				'placeholder' => '%d',
				'key'         => 'order_id',
			),
			'course_id' => array(
				'placeholder' => '%d',
				'key'         => 'course_id',
			),
			'name'      => array(
				'placeholder' => '%s',
				'key'         => 'name',
			),
			'type'      => array(
				'placeholder' => '%s',
				'key'         => 'type',
			),
			'quantity'  => array(
				'placeholder' => '%d',
				'key'         => 'quantity',
			),
		);
		$where        = array();
		$where_format = array();
		$object_type  = masteriyo( 'order-item' )->get_object_type();
		$table_name   = $this->get_table_name();

		/**
		 * Prepare where args.
		 */
		foreach ( $where_args as $db_key => $where ) {
			if ( ! empty( $query_vars[ $where['key'] ] ) ) {
				$where[ $db_key ] = $query_vars[ $where['key'] ];
				$where_format[]   = $where['placeholder'];
			}
		}

		/**
		 * Get ids of the order items that will be deleted.
		 */
		$where_clause = ' WHERE 1=1 ';
		$where_values = array();
		$index        = 0;
		foreach ( $where as $db_key => $value ) {
			$where_clause  .= " AND {$db_key} = {$where_format[ $index ]} ";
			$where_values[] = $value;
			$index++;
		}
		$sql            = "SELECT order_item_id FROM {$table_name} {$where_clause}";
		$order_item_ids = $wpdb->get_results( $wpdb->prepare( $sql, $where_values ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$order_item_ids = wp_list_pluck( $order_item_ids, 'order_item_id' );

		/**
		 * Fires before batch deleting order item courses.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_vars Query vars.
		 */
		do_action( 'masteriyo_before_batch_delete_' . $object_type, $query_vars );

		/**
		 * Delete order item metadata.
		 */
		$meta_table_info = $this->get_meta_table_info();
		$item_ids_string = implode( ', ', $order_item_ids );
		$wpdb->query(
			$wpdb->prepare(
				'DELETE FROM %s WHERE order_item_id in (%s)',
				$meta_table_info['table'],
				$item_ids_string
			)
		);

		// Delete order items.
		$result = $wpdb->delete( $table_name, $where, $where_format );

		// Clear cache.
		foreach ( $order_item_ids as $order_item_id ) {
			masteriyo( 'cache' )->delete( 'masteriyo-item-' . $order_item_id, 'masteriyo-order-items' );
			masteriyo( 'cache' )->delete( $order_item_id, $this->meta_type . '_meta' );
		}

		/**
		 * Fires after batch deleting order item courses.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_vars Query vars.
		 * @param integer|false The number of rows updated, or false on error.
		 */
		do_action( 'masteriyo_after_batch_delete_' . $object_type, $query_vars, $result );
	}
}
