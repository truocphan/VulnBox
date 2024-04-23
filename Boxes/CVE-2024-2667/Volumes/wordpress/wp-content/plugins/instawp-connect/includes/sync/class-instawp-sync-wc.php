<?php

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_WC {

    public function __construct() {
		// Hooks
        add_filter( 'INSTAWP_CONNECT/Filters/two_way_sync_post_data', array( $this, 'add_post_data' ), 10, 3 );
        add_action( 'INSTAWP_CONNECT/Actions/process_two_way_sync_post', array( $this, 'process_gallery' ), 10, 2 );

	    // Order actions
	    add_action( 'woocommerce_new_order', array( $this, 'create_order' ) );
	    add_action( 'woocommerce_update_order', array( $this, 'update_order' ) );
	    add_action( 'woocommerce_before_trash_order', array( $this, 'trash_order' ) );
	    add_action( 'woocommerce_before_delete_order', array( $this, 'delete_order' ) );

		// Attributes actions
	    add_action( 'woocommerce_attribute_added', array( $this, 'attribute_added' ), 10, 2 );
	    add_action( 'woocommerce_attribute_updated', array( $this, 'attribute_updated' ), 10, 3 );
	    add_action( 'woocommerce_attribute_deleted', array( $this, 'attribute_deleted' ), 10, 2 );

	    // Process event
	    add_filter( 'INSTAWP_CONNECT/Filters/process_two_way_sync', array( $this, 'parse_event' ), 10, 2 );
    }

	public function create_order( $order_id ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$event_name  = __('Order created', 'instawp-connect' );
		$this->add_event( $event_name, 'woocommerce_order_created', $order->get_id(), $order->get_order_key() );
	}

	public function update_order( $order_id ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$event_name = __('Order updated', 'instawp-connect' );
		$this->add_event( $event_name, 'woocommerce_order_updated', $order->get_id(), $order->get_order_key() );
	}

	public function trash_order( $order_id ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$event_name = __('Order trashed', 'instawp-connect' );
		$this->add_event( $event_name, 'woocommerce_order_trashed', $order->get_id(), $order->get_order_key() );
	}

	public function delete_order( $order_id ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$event_name = __('Order trashed', 'instawp-connect' );
		$this->add_event( $event_name, 'woocommerce_order_deleted', $order->get_id(), $order->get_order_key() );
	}

	public function add_post_data( $data, $type, $post ) {
		if ( $this->can_sync() && $type === 'product' ) {
			$data['product_gallery'] = $this->get_product_gallery( $post->ID );
		}

		return $data;
	}
	
	public function process_gallery( $post, $data ) {
		if ( $post['post_type'] === 'product' ) {
			$product_gallery = isset( $data['product_gallery'] ) ? $data['product_gallery'] : array();
			$gallery_ids     = array();

			foreach ( $product_gallery as $gallery_item ) {
				$gallery_ids[] = InstaWP_Sync_Helpers::string_to_attachment( $gallery_item );
			}

			$this->set_product_gallery( $post['ID'], $gallery_ids );
		}
	}

	/**
	 * Attribute added (hook).
	 *
	 * @param int   $id   Added attribute ID.
	 * @param array $data Attribute data.
	 */
	public function attribute_added( $id, $data ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$event_name = __( 'Woocommerce attribute added', 'instawp-connect' );

		$data['attribute_id'] = $id;
		$this->add_event( $event_name, 'woocommerce_attribute_added', $data, $data['attribute_name'] );
	}

	/**
	 * Attribute Updated (hook).
	 *
	 * @param int    $id       Added attribute ID.
	 * @param array  $data     Attribute data.
	 * @param string $old_slug Attribute old name.
	 */
	public function attribute_updated( $id, $data, $old_slug ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$event_name = __('Woocommerce attribute updated', 'instawp-connect' );
		$data['attribute_id'] = $id;
		$this->add_event( $event_name, 'woocommerce_attribute_updated', $data, $old_slug );
	}

	/**
	 * Attribute Deleted (hook).
	 *
	 * @param int $id Attribute ID.
	 * @param string $name Attribute name.
	 */
	public function attribute_deleted( $id, $name ) {
		if ( ! $this->can_sync() ) {
			return;
		}

		$event_name = __( 'Woocommerce attribute deleted', 'instawp-connect' );
		$this->add_event( $event_name, 'woocommerce_attribute_deleted', array( 'attribute_id' => $id ), $name );
	}

	public function parse_event( $response, $v ) {
		if ( $v->event_type !== 'woocommerce' || empty( $v->source_id ) || ! class_exists( 'WooCommerce' ) ) {
			return $response;
		}

		$reference_id = $v->source_id;
		$details      = InstaWP_Sync_Helpers::object_to_array( $v->details );
		$log_data     = array();

		// add or update order
		if ( in_array( $v->event_slug, array( 'woocommerce_order_created', 'woocommerce_order_updated' ), true ) ) {
			$order_id = wc_get_order_id_by_order_key( $reference_id );

			if ( $order_id ) {
				$order = wc_get_order( $order_id );
				$types = array( 'line_item', 'fee', 'shipping', 'coupon', 'tax' );

				foreach ( $order->get_items( $types ) as $item_id => $item ) {
					wc_delete_order_item( $item_id );
				}
			} else {
				$order = wc_create_order();

				try {
					$order->set_order_key( $reference_id );
				} catch ( Exception $e ) {
					return InstaWP_Sync_Helpers::sync_response( $v, array(), array(
						'status'  => 'pending',
						'message' => $e->getMessage(),
					) );
				}
			}

			kses_remove_filters();
			foreach ( $details['line_items'] as $line_item ) {
				if ( empty( $line_item ) ) {
					continue;
				}

				$product_id = InstaWP_Sync_Helpers::get_post_by_reference( $line_item['post_data']['post_type'], $line_item['reference_id'], $line_item['post_data']['post_name'] );
				if ( ! $product_id ) {
					$product_id = InstaWP_Sync_Helpers::create_or_update_post( $line_item['post_data'], $line_item['post_meta'], $line_item['reference_id'] );
				}
				$order->add_product( wc_get_product( $product_id ), $line_item['quantity'] );
			}
			kses_init_filters();

			foreach ( $details['shipping_lines'] as $shipping_item ) {
				if ( empty( $shipping_item ) ) {
					continue;
				}

				$shipping = new \WC_Order_Item_Shipping();
				$shipping->set_props( $shipping_item );
				$order->add_item( $shipping );
			}

			foreach ( $details['fee_lines'] as $fee_item ) {
				if ( empty( $fee_item ) ) {
					continue;
				}
				unset( $fee_item['order_id'] );

				$fee = new \WC_Order_Item_Fee();
				$fee->set_props( $fee_item );
				$order->add_item( $fee );
			}

			foreach ( $details['tax_lines'] as $tax_item ) {
				if ( empty( $tax_item ) ) {
					continue;
				}
				unset( $tax_item['order_id'] );

				$tax = new \WC_Order_Item_Tax();
				$tax->set_props( $tax_item );
				$order->add_item( $tax );
			}

			foreach ( $details['coupon_lines'] as $coupon_item ) {
				if ( empty( $coupon_item ) ) {
					continue;
				}

				kses_remove_filters();
				InstaWP_Sync_Helpers::create_or_update_post( $coupon_item['post_data'], $coupon_item['post_meta'], $coupon_item['reference_id'] );
				kses_init_filters();

				$coupon_code    = $coupon_item['data']['code'];
				$coupon         = new \WC_Coupon( $coupon_code );
				$discount_total = $coupon->get_amount();

				$item = new \WC_Order_Item_Coupon();
				$item->set_props( array(
					'code'     => $coupon_code,
					'discount' => $discount_total,
				) );
				$order->add_item( $item );
			}

			$order->set_address( $details['billing'], 'billing' );
			$order->set_address( $details['shipping'], 'shipping' );

			try {
				$order->set_payment_method( $details['payment_method'] );
				$order->set_payment_method_title( $details['payment_method_title'] );
			} catch ( Exception $e ) {
				return InstaWP_Sync_Helpers::sync_response( $v, array(), array(
					'status'  => 'pending',
					'message' => $e->getMessage(),
				) );
			}

			$order->set_status( $details['status'] );
			$order->set_customer_ip_address( $details['customer_ip_address'] );
			$order->set_customer_user_agent( $details['customer_user_agent'] );
			$order->set_transaction_id( $details['transaction_id'] );
			$order->set_customer_note( $details['customer_note'] );
			$order->set_customer_id( $details['customer_id'] );

			$order->calculate_totals();
			$order->save();
		}

		// delete order
		if ( in_array( $v->event_slug, array( 'woocommerce_order_trashed', 'woocommerce_order_deleted' ), true ) ) {
			$order_id = wc_get_order_id_by_order_key( $reference_id );

			if ( $order_id ) {
				$order = wc_get_order( $order_id );
				$order->delete( $v->event_slug === 'woocommerce_order_deleted' );
			} else {
				return InstaWP_Sync_Helpers::sync_response( $v, array(), array(
					'status'  => 'pending',
					'message' => 'Order not found',
				) );
			}
		}

		// add or update attribute
		if ( in_array( $v->event_slug, array( 'woocommerce_attribute_added', 'woocommerce_attribute_updated' ), true ) ) {
			$attribute_id   = wc_attribute_taxonomy_id_by_name( $v->source_id );
			$attribute_data = array(
				'name'         => $details['attribute_label'],
				'slug'         => $details['attribute_name'],
				'type'         => $details['attribute_type'],
				'order_by'     => $details['attribute_orderby'],
				'has_archives' => isset( $details['attribute_public'] ) ? (int) $details['attribute_public'] : 0,
			);

			if ( $attribute_id ) {
				$attribute = wc_update_attribute( $attribute_id, $attribute_data );
			} else {
				$attribute = wc_create_attribute( $attribute_data );
			}

			if ( is_wp_error( $attribute ) ) {
				$log_data[ $v->id ] = $attribute->get_error_message();

				return InstaWP_Sync_Helpers::sync_response( $v, $log_data, array(
					'status'  => 'pending',
					'message' => $attribute->get_error_message(),
				) );
			}
		}

		if ( $v->event_slug === 'woocommerce_attribute_deleted' ) {
			$attribute_id = wc_attribute_taxonomy_id_by_name( $v->source_id );

			if ( $attribute_id ) {
				$response = wc_delete_attribute( $attribute_id );

				if ( ! $response ) {
					return InstaWP_Sync_Helpers::sync_response( $v, array(), array(
						'status'  => 'pending',
						'message' => 'Failed',
					) );
				}
			} else {
				return InstaWP_Sync_Helpers::sync_response( $v, array(), array(
					'status'  => 'pending',
					'message' => 'Attribute not found',
				) );
			}
		}

		return InstaWP_Sync_Helpers::sync_response( $v, $log_data );
	}

	/*
	 * Function add_event
	 * @param $event_name
	 * @param $event_slug
	 * @param $details
	 * @param $type
	 * @param $source_id
	 * @return void
	 */
	private function add_event( $event_name, $event_slug, $details, $source_id ) {
		switch ( $event_slug ) {
			case 'woocommerce_attribute_added':
			case 'woocommerce_attribute_updated':
				$title = $details['attribute_label'];
				break;
			case 'woocommerce_attribute_deleted':
				$title = ucfirst( str_replace( array( '-', '_' ), ' ', $source_id ) );
				break;
			case 'woocommerce_order_created':
			case 'woocommerce_order_updated':
				$title   = sprintf( __('Order %s', 'instawp-connect' ), '#' . $details );
				$details = $this->order_data( $details );
				break;
			case 'woocommerce_order_trashed':
			case 'woocommerce_order_deleted':
				$title = sprintf( __('Order %s', 'instawp-connect' ), '#' . $details );
				break;
			default:
				$title = $details;
		}
		InstaWP_Sync_DB::insert_update_event( $event_name, $event_slug, 'woocommerce', $source_id, $title, $details );
	}

	private function can_sync() {
		return InstaWP_Sync_Helpers::can_sync( 'wc' ) && class_exists( 'WooCommerce' );
	}

	/*
     * Get product gallery images
     */
	private function get_product_gallery( $product_id ) {
		$gallery = array();
		$product = $this->get_product( $product_id );

		if ( $product ) {
			$attachment_ids = ! empty( $product->get_gallery_image_ids() ) ? $product->get_gallery_image_ids() : array();

			foreach ( $attachment_ids as $attachment_id ) {
				$gallery[] = InstaWP_Sync_Helpers::attachment_to_string( $attachment_id, 'full' );
			}
		}

		return $gallery;
	}

	/**
	 * Set product gallery
	 */
	private function set_product_gallery( $product_id, $gallery_ids ) {
		$product = $this->get_product( $product_id );
		if ( $product && $gallery_ids ) {
			$product->set_gallery_image_ids( $gallery_ids );
			$product->save();
		}
	}

	/**
	 * Set product gallery
	 */
	private function get_product( $product_id ) {
		return function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;
	}

	private function order_data( $order_id ) {
		$order      = wc_get_order( $order_id );
		$order_data = $order->get_data();
		$data       = $order_data;

		foreach ( $order_data['meta_data'] as $meta ) {
			if ( in_array( $meta->key, array( '_edit_lock' ) ) ) {
				continue;
			}

			$data['meta_data'][ $meta->key ] = $meta->value;
		}

		foreach ( $order_data['fee_lines'] as $fee ) {
			$data['fee_lines'][] = $fee->get_data();
		}

		foreach ( $order_data['shipping_lines'] as $shipping ) {
			$data['shipping_lines'][] = $shipping->get_data();
		}

		foreach ( $order_data['tax_lines'] as $tax ) {
			$data['tax_lines'][] = $tax->get_data();
		}

		foreach ( $order_data['coupon_lines'] as $coupon ) {
			$post_id = wc_get_coupon_id_by_code( $coupon['code'] );
			$post    = get_post( $post_id );

			if ( ! $post ) {
				continue;
			}

			$reference_id           = InstaWP_Sync_Helpers::get_post_reference_id( $post->ID );
			$data['coupon_lines'][] = array(
				'reference_id' => $reference_id,
				'post_id'      => $post->ID,
				'post_data'    => $post,
				'meta_data'    => get_post_meta( $post->ID ),
				'data'         => $coupon->get_data(),
			);
		}

		foreach ( $order_data['line_items'] as $product ) {
			$product_data = $product->get_data();
			$post_id      = $product_data['product_id'];
			$post         = get_post( $post_id );

			if ( ! $post ) {
				continue;
			}

			$reference_id         = InstaWP_Sync_Helpers::get_post_reference_id( $post->ID );
			$data['line_items'][] = array(
				'reference_id' => $reference_id,
				'post_id'      => $post->ID,
				'quantity'     => $product_data['quantity'],
				'post_data'    => $post,
				'meta_data'    => get_post_meta( $post->ID ),
				'data'         => $product_data,
			);
		}

		return $data;
	}
}

new InstaWP_Sync_WC();