<?php

new STM_LMS_Course_Bundle_Cart();

class STM_LMS_Course_Bundle_Cart {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_add_bundle_to_cart', array( $this, 'ajax_add_to_cart_bundle' ) );
		add_action( 'stm_lms_order_accepted', array( $this, 'order_accepted' ), 10, 2 );

		add_filter( 'stm_lms_after_single_item_cart_title', array( $this, 'after_single_item_cart_title' ) );
		add_filter( 'stm_lms_cart_items_fields', array( $this, 'cart_items_fields' ) );
		add_filter( 'stm_lms_accept_order', array( $this, 'stm_lms_accept_order' ) );
		add_action( 'stm_lms_order_remove', array( $this, 'order_removed' ), 10, 3 );
	}

	public function ajax_add_to_cart_bundle() {
		$user_id = get_current_user_id();
		$item_id = intval( $_GET['item_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		wp_send_json( self::add_to_cart_bundle( $item_id, $user_id ) );
	}

	public static function add_to_cart_bundle( $item_id, $user_id ) {
		$bundle = intval( $item_id );

		if ( empty( $user_id ) || empty( $bundle ) ) {
			die;
		}

		$r = array();

		$quantity = 1;
		$price    = STM_LMS_Course_Bundle::get_bundle_price( $item_id );

		$is_woocommerce = STM_LMS_Cart::woocommerce_checkout_enabled();

		$item_added = count( stm_lms_get_item_in_cart( $user_id, $item_id, array( 'user_cart_id' ) ) );

		if ( ! $item_added ) {
			stm_lms_add_user_cart( compact( 'user_id', 'item_id', 'quantity', 'price', 'bundle' ) );
		}

		if ( ! $is_woocommerce ) {
			$r['text']     = esc_html__( 'Go to Cart', 'masterstudy-lms-learning-management-system-pro' );
			$r['cart_url'] = esc_url( STM_LMS_Cart::checkout_url() );
		} else {
			$product_id = STM_LMS_Woocommerce::create_product( $item_id );

			// Load cart functions which are loaded only on the front-end.
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
			include_once WC_ABSPATH . 'includes/class-wc-cart.php';

			if ( is_null( WC()->cart ) ) {
				wc_load_cart();
			}

			WC()->cart->add_to_cart( $product_id, 1, 0, array(), array( 'bundle_id' => $item_id ) );

			$r['text']     = esc_html__( 'Go to Cart', 'masterstudy-lms-learning-management-system-pro' );
			$r['cart_url'] = esc_url( wc_get_cart_url() );
		}

		$r['redirect'] = STM_LMS_Options::get_option( 'redirect_after_purchase', false );

		return apply_filters( 'stm_lms_add_to_cart_r', $r, $item_id );
	}

	public function after_single_item_cart_title( $item ) {
		$enterprise = '';
		if ( ! empty( $item['bundle'] ) ) {
			$enterprise = "<span class='enterprise-course-added'><label>" . esc_html__( 'Bundle', 'masterstudy-lms-learning-management-system-pro' ) . '</label></span>';
		}
		echo wp_kses_post( $enterprise );
	}

	public function cart_items_fields( $fields ) {
		$fields[] = 'bundle';
		return $fields;
	}

	public function stm_lms_accept_order() {
		return false;
	}

	public function order_accepted( $user_id, $cart_items ) {
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $cart_item ) {
				if ( ! empty( $cart_item['bundle'] ) ) {

					$courses = get_post_meta( $cart_item['bundle'], STM_LMS_My_Bundle::bundle_courses_key(), true );

					if ( ! empty( $courses ) ) {
						foreach ( $courses as $course_id ) {
							STM_LMS_Course::add_user_course( $course_id, $user_id, 0, 0, false, '', $cart_item['bundle'] );
							STM_LMS_Course::add_student( $course_id );
						}
					}
				} else {

					STM_LMS_Course::add_user_course( $cart_item['item_id'], $user_id, 0, 0 );

				}
			}
		}

		/*Delete Cart*/
		stm_lms_get_delete_cart_items( $user_id );
	}

	public function order_removed( $course_id, $cart_item, $user_id ) {
		if ( ! empty( $cart_item['bundle'] ) ) {
			$bundle_id = intval( $cart_item['bundle'] );

			$bundle_courses = get_post_meta( $bundle_id, STM_LMS_My_Bundle::bundle_courses_key(), true );

			if ( ! empty( $bundle_courses ) ) {
				foreach ( $bundle_courses as $id ) {
					global $wpdb;
					$table = stm_lms_user_courses_name( $wpdb );

					$wpdb->delete(
						$table,
						array(
							'user_id'   => $user_id,
							'course_id' => $id,
							'bundle_id' => $bundle_id,
						)
					);
				}
			}
		}
	}

}
