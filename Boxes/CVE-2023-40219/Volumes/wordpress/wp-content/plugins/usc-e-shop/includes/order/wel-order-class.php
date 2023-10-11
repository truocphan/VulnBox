<?php
/**
 * Welcart order data class
 *
 * @package  Welcart
 */

namespace Welcart;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Item class
 *
 * The Welcart item class handles individual product data.
 *
 * @since 2.2.2
 */
class OrderData {

	/**
	 * ID for this object.
	 *
	 * @since 2.2.2
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Full data for this object.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $data = array();

	/**
	 * Customer data for this object.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $customer = array();

	/**
	 * Delivery data for this object.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $delivery = array();

	/**
	 * Cart data.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $cart = array();

	/**
	 * Cart meta data. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $cart_meta = array();

	/**
	 * Order meta data. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $order_meta = array();

	/**
	 * Extra data for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $extra_data = array();

	/**
	 * Get a order data if ID is passed, otherwise the order data is false.
	 * This class should not be instantiated.
	 * The wc_get_order() function should be used instead.
	 *
	 * @param int $order_id Order ID of the order.
	 */
	public function __construct( $order_id = 0 ) {
		if ( is_numeric( $order_id ) && $order_id > 0 ) {
			$this->set_id( $order_id );
		} else {
			$this->set_id( 0 );
		}

		$this->set_data( $this->id );
	}

	/**
	 * Set ID.
	 *
	 * @param int $id ID.
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Set data.
	 *
	 * @since  2.2.2
	 */
	public function set_data( $order_id ) {
		global $wpdb;
		$order_table = $wpdb->prefix . 'usces_order';

		// order data.
		$order_cache_key = 'wel_order_data_' . $order_id;
		$_data           = wp_cache_get( $order_cache_key );

		if ( false === $_data ) {
			$_data = $wpdb->get_row( 
				$wpdb->prepare(
					"SELECT * FROM {$order_table} WHERE ID = %d",
					$order_id
				)
			);
			if ( null !== $_data ) {
				wp_cache_set( $order_cache_key, $_data );
			}
		}

		if ( ! $_data ) {
			return false;
		}

		// order meta data.
		$order_meta_cache_key = 'wel_order_meta_' . $order_id;

		$_meta = wp_cache_get( $order_meta_cache_key );

		if ( false === $_meta ) {
			$order_meta_table_name = $wpdb->prefix . 'usces_order_meta';

			$query = $wpdb->prepare(
				"SELECT * FROM {$order_meta_table_name} WHERE order_id = %d",
				$order_id
			);

			$_meta = $wpdb->get_results( $query );
			if ( null !== $_meta ) {
				wp_cache_set( $order_meta_cache_key, $_meta );
			}
		}

		$csod  = array();
		$cscs  = array();
		$csde  = array();
		$order = array();
		foreach ( $_meta as $_meta_row ){
			if ( 0 === strpos( $_meta_row->meta_key, 'csod_' ) ) {
				$key          = str_replace( 'csod_', '', $_meta_row->meta_key );
				$csod[ $key ] = $_meta_row->meta_value;
			} elseif ( 0 === strpos( $_meta_row->meta_key, 'cscs_' ) ) {
				$key          = str_replace( 'cscs_', '', $_meta_row->meta_key );
				$cscs[ $key ] = $_meta_row->meta_value;
			} elseif ( 0 === strpos( $_meta_row->meta_key, 'csde_' ) ) {
				$key          = str_replace( 'csde_', '', $_meta_row->meta_key );
				$csde[ $key ] = $_meta_row->meta_value;
			} elseif ( 'customer_country' === $_meta_row->meta_key ) {
				$cscs['country'] = $_meta_row->meta_value;
			} else {
				$this->order_meta[ $_meta_row->meta_key ] = $_meta_row->meta_value;
			}
		}

		// customer data.
		$this->customer = array(
			'member_id' => $_data->mem_id,
			'email'     => $_data->order_email,
			'name1'     => $_data->order_name1,
			'name2'     => $_data->order_name2,
			'name3'     => $_data->order_name3,
			'name4'     => $_data->order_name4,
			'zip'       => $_data->order_zip,
			'pref'      => $_data->order_pref,
			'address1'  => $_data->order_address1,
			'address2'  => $_data->order_address2,
			'address3'  => $_data->order_address3,
			'tel'       => $_data->order_tel,
			'fax'       => $_data->order_fax,
		);
		$this->customer = array_merge( $this->customer, $cscs );

		// delivery data.
		$_delivery = unserialize( $_data->order_delivery );
		$this->delivery = array(
			'delivery_flag' => isset( $_delivery['delivery_flag'] ) ? $_delivery['delivery_flag'] : '',
			'name1'         => $_delivery['name1'],
			'name2'         => $_delivery['name2'],
			'name3'         => $_delivery['name3'],
			'name4'         => $_delivery['name4'],
			'zip'           => $_delivery['zipcode'],
			'pref'          => $_delivery['pref'],
			'address1'      => $_delivery['address1'],
			'address2'      => $_delivery['address2'],
			'address3'      => $_delivery['address3'],
			'tel'           => $_delivery['tel'],
			'country'       => $_delivery['country'],
		);
		$this->delivery = array_merge( $this->delivery, $csde );

		// cart data.
		$cart_cache_key = 'wel_order_cart_' . $order_id;

		$_cart = wp_cache_get( $cart_cache_key );

		if ( false === $_cart ) {
			$_cart = usces_get_ordercartdata( $order_id );
			if ( null !== $_cart ) {
				wp_cache_set( $cart_cache_key, $_cart );
			}
		}
		if ( ! $_cart ) {
			$_cart = array();
		}
		$this->cart = $_cart;

		$total_price = $_data->order_item_total_price - $_data->order_usedpoint + $_data->order_discount + $_data->order_shipping_charge + $_data->order_cod_fee + $_data->order_tax;
		if ( $total_price < 0 ) {
			$total_price = 0;
		}

		$order_data = array(
			'ID'              => $_data->ID,
			'getpoint'        => $_data->order_getpoint,
			'usedpoint'       => $_data->order_usedpoint,
			'discount'        => usces_crform( $_data->order_discount, false, false, 'return', false ),
			'payment_name'    => $_data->order_payment_name,
			'shipping_charge' => usces_crform( $_data->order_shipping_charge, false, false, 'return', false ),
			'cod_fee'         => usces_crform( $_data->order_cod_fee, false, false, 'return', false ),
			'tax'             => usces_crform( $_data->order_tax, false, false, 'return', false ),
			'total_price'     => usces_crform( $total_price, false, false, 'return', false ),
			'status'          => $_data->order_status,
			'date'            => mysql2date( __( 'Y/m/d' ), $_data->order_date ),
			'modified'        => mysql2date( __( 'Y/m/d' ), $_data->order_modified ),
			'condition'       => unserialize( $_data->order_condition ),
			'customer'        => $this->customer,
			'delivery'        => $this->delivery,
			'cart'            => $this->cart,
		);

		$this->data = array_merge( $order_data, $this->order_meta, $this->extra_data );
	}

	/**
	 * Returns all data for this object.
	 *
	 * @since  2.2.2
	 * @return array
	 */
	public function get_data() {
		if ( ! isset( $this->data['ID'] ) ) {
			return false;
		} else {
			return array_merge( $this->data );
		}
	}

}
