<?php

namespace stmLms\Classes\Models;

use stmLms\Classes\Vendor\StmBaseModel;

class StmOrderItems extends StmBaseModel {

	protected $fillable = array(
		'id',
		'order_id',
		'object_id',
		'payout_id',
		'quantity',
		'price',
		'transaction',
	);

	public $id;
	public $order_id;
	public $object_id;
	public $payout_id;
	public $quantity;
	public $price;
	public $transaction;

	public static function init() {
		add_action( 'order_created', array( self::class, 'order_created' ), 10, 4 );
		add_action(
			'woocommerce_checkout_update_order_meta',
			array( self::class, 'lms_woocommerce_checkout_update_order_meta' ),
			200,
			1
		);
	}

	public static function get_primary_key() {
		return 'id';
	}

	public static function get_table() {
		global $wpdb;

		return $wpdb->prefix . 'stm_lms_order_items';
	}

	public static function get_searchable_fields() {
		return array(
			'id',
			'order_id',
			'object_id',
			'payout_id',
			'quantity',
			'price',
			'transaction',
		);
	}

	/**
	 * @param $data
	 *
	 * @return StmOrderItems
	 */
	public static function load( $data ) {
		$model = new StmOrderItems();

		foreach ( $data as $key => $val ) {
			$model->$key = $val;
		}

		return $model;
	}

	/**
	 * @param $user_id
	 * @param $cart_items
	 * @param $payment_code
	 * @param $order_id
	 */
	public static function order_created( $user_id, $cart_items, $payment_code, $order_id ) {
		if ( ! is_array( $cart_items ) || empty( $cart_items ) || empty( $order_id ) ) {
			return;
		}

		foreach ( $cart_items as $item ) {
			$order_items              = new StmOrderItems();
			$order_items->order_id    = $order_id;
			$order_items->object_id   = $item['item_id'];
			$order_items->price       = $item['price'];
			$order_items->quantity    = 1;
			$order_items->transaction = 0;
			$order_items->save();
		}
	}

	/**
	 * @param $order_id
	 */
	public static function lms_woocommerce_checkout_update_order_meta( $order_id ) {
		$cart = WC()->cart->get_cart();

		foreach ( $cart as $cart_item ) {
			$order_items              = new StmOrderItems();
			$order_items->order_id    = $order_id;
			$order_items->object_id   = $cart_item['product_id'];
			$order_items->price       = ( isset( $cart_item['data'] ) ) ? $cart_item['data']->get_price() : 0;
			$order_items->quantity    = $cart_item['quantity'];
			$order_items->transaction = 0;
			$order_items->save();
		}
	}

	/**
	 * @return array|null|\WP_Post
	 */
	public function get_items_posts() {
		return get_post( $this->object_id );
	}

	public function get_items_posts_order() {
		return get_post( $this->order_id );
	}

	/**
	 * @return string
	 */
	public function get_items_author( $type = 'Lms' ) {
		$object_id = $this->object_id;

		if ( 'WooCommerce' === $type ) {
			$object_id = get_post_meta( $this->object_id, 'stm_lms_product_id', true );
		}

		$post = get_post( $object_id );

		return ! empty( $post ) ? get_userdata( $post->post_author ) : false;
	}
}

