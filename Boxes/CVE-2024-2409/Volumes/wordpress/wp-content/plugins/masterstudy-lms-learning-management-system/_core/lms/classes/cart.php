<?php

STM_LMS_Cart::init();

class STM_LMS_Cart {


	public static function init() {
		 add_action( 'wp_ajax_stm_lms_add_to_cart', 'STM_LMS_Cart::add_to_cart' );
		add_action( 'wp_ajax_nopriv_stm_lms_add_to_cart', 'STM_LMS_Cart::add_to_cart' );

		add_action( 'wp_ajax_stm_lms_delete_from_cart', 'STM_LMS_Cart::delete_from_cart' );
		add_action( 'wp_ajax_nopriv_stm_lms_delete_from_cart', 'STM_LMS_Cart::delete_from_cart' );

		add_action( 'wp_ajax_stm_lms_purchase', 'STM_LMS_Cart::purchase_courses' );
		add_action( 'wp_ajax_nopriv_stm_lms_purchase', 'STM_LMS_Cart::purchase_courses' );
		add_action( 'masterstudy_lms_course_price_updated', array( self::class, 'course_price_updated' ), 10, 2 );
	}

	public static function course_price_updated( $item_id ) {
		$course_meta = STM_LMS_Helpers::parse_meta_field( $item_id );
		$price       = self::get_course_price( $course_meta );
		stm_lms_update_user_cart( $item_id, $price );
	}

	public static function woocommerce_checkout_enabled() {
		 return STM_LMS_Options::get_option( 'wocommerce_checkout', false ) && class_exists( 'WooCommerce' );
	}

	public static function _add_to_cart( $item_id, $user_id ) {

		$r = array();

		$not_salebale = get_post_meta( $item_id, 'not_single_sale', true );
		if ( $not_salebale ) {
			die;
		}

		$item_meta = STM_LMS_Helpers::parse_meta_field( $item_id );
		$quantity  = 1;
		$price     = self::get_course_price( $item_meta );

		$is_woocommerce = self::woocommerce_checkout_enabled();

		$item_added = count( stm_lms_get_item_in_cart( $user_id, $item_id, array( 'user_cart_id' ) ) );

		if ( ! $item_added ) {
			stm_lms_add_user_cart( compact( 'user_id', 'item_id', 'quantity', 'price' ) );
		}

		if ( ! $is_woocommerce ) {
			$r['text']     = esc_html__( 'Go to Cart', 'masterstudy-lms-learning-management-system' );
			$r['cart_url'] = esc_url( self::checkout_url() );
		} else {
			$r['added']    = STM_LMS_Woocommerce::add_to_cart( $item_id );
			$r['text']     = esc_html__( 'Go to Cart', 'masterstudy-lms-learning-management-system' );
			$r['cart_url'] = esc_url( wc_get_cart_url() );
		}

		$r['redirect'] = STM_LMS_Options::get_option( 'redirect_after_purchase', false );

		return apply_filters( 'stm_lms_add_to_cart_r', $r, $item_id );
	}

	public static function add_to_cart() {
		check_ajax_referer( 'stm_lms_add_to_cart', 'nonce' );

		if ( ! is_user_logged_in() || empty( $_GET['item_id'] ) ) {
			die;
		}

		$item_id = intval( $_GET['item_id'] );
		$user    = STM_LMS_User::get_current_user();
		$user_id = $user['id'];

		$r = self::_add_to_cart( $item_id, $user_id );

		wp_send_json( $r );
	}

	public static function delete_from_cart() {
		check_ajax_referer( 'stm_lms_delete_from_cart', 'nonce' );

		if ( ( ! is_user_logged_in() && empty( $_GET['guest'] ) ) || empty( $_GET['item_id'] ) ) {
			die;
		}

		if ( ! empty( $_GET['guest'] ) ) {
			wp_send_json( 'OK' );
		} else {
			$user = STM_LMS_User::get_current_user();

			if ( apply_filters( 'stm_lms_delete_from_cart_filter', true ) ) {
				stm_lms_get_delete_cart_item( $user['id'], intval( $_GET['item_id'] ) );
			}

			do_action( 'stm_lms_delete_from_cart', $user['id'] );

			wp_send_json( 'OK' );
		}
	}

	public static function get_course_price( $course_meta ) {
		$price = 0;
		if ( ! empty( $course_meta['price'] ) ) {
			$price = $course_meta['price'];
		}
		if ( ! empty( $course_meta['sale_price'] ) ) {
			$price = apply_filters( 'stm_lms_sale_price_meta', $course_meta['sale_price'], $course_meta, $price );
		}
		return apply_filters( 'stm_lms_get_course_price_in_meta', $price, $course_meta );
	}

	public static function checkout_url() {
		 $settings = get_option( 'stm_lms_settings', array() );

		if ( empty( $settings['checkout_url'] ) ) {
			return home_url( '/' );
		}

		return get_the_permalink( $settings['checkout_url'] );

	}

	public static function purchase_courses() {
		check_ajax_referer( 'stm_lms_purchase', 'nonce' );

		$user = STM_LMS_User::get_current_user();
		if ( empty( $user['id'] ) ) {
			die;
		}
		$user_id = $user['id'];

		$payment_code = ( ! empty( $_GET['payment_code'] ) ) ? sanitize_text_field( $_GET['payment_code'] ) : '';

		$r = array(
			'status' => 'success',
		);

		if ( empty( $payment_code ) ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => esc_html__( 'Please, select payment method', 'masterstudy-lms-learning-management-system' ),
				)
			);
			die;
		}

		$cart_items = stm_lms_get_cart_items( $user_id, apply_filters( 'stm_lms_cart_items_fields', array( 'item_id', 'price' ) ) );
		$cart_total = self::get_cart_totals( $cart_items );
		$symbol     = STM_LMS_Options::get_option( 'currency_symbol', 'none' );

		/*Create ORDER*/
		$invoice = STM_LMS_Order::create_order(
			array(
				'user_id'         => $user_id,
				'cart_items'      => $cart_items,
				'payment_code'    => $payment_code,
				'_order_total'    => $cart_total['total'],
				'_order_currency' => $symbol,
			),
			true
		);

		do_action( 'order_created', $user_id, $cart_items, $payment_code, $invoice );

		/*If Paypal*/
		if ( 'paypal' === $payment_code ) {
			$paypal       = new STM_LMS_PayPal(
				$cart_total['total'],
				$invoice,
				$cart_total['item_name'],
				$invoice,
				$user['email']
			);
			$r['url']     = $paypal->generate_payment_url();
			$r['message'] = esc_html__( 'Order created, redirecting to PayPal', 'masterstudy-lms-learning-management-system' );
		} elseif ( 'stripe' === $payment_code ) {
			if ( ! empty( $_GET['token_id'] ) ) {
				$url     = 'https://api.stripe.com/v1/charges';
				$payment = STM_LMS_Options::get_option( 'payment_methods' );
				if ( empty( $payment['stripe'] )
					|| empty( $payment['stripe']['enabled'] )
					|| empty( $payment['stripe']['fields'] )
					|| empty( $payment['stripe']['fields']['secret_key'] )
				) {
					die;
				}

				$sk_key = $payment['stripe']['fields']['secret_key'];

				$headers = array(
					'Authorization' => 'Bearer ' . $sk_key,
				);

				$currency = ( ! empty( $payment['stripe']['fields']['currency'] ) ) ? $payment['stripe']['fields']['currency'] : 'usd';

				$increment = apply_filters( 'masterstudy_payment_increment', 100 );

				$args = array(
					'source'      => $_GET['token_id'],
					'amount'      => floatval( $cart_total['total'] ) * $increment,
					'description' => sprintf( esc_html__( '%1$s. Order key: %2$s', 'masterstudy-lms-learning-management-system' ), $cart_total['item_name'], get_the_title( $invoice ) ),
					'currency'    => $currency,
				);

				$req = wp_remote_post(
					$url,
					array(
						'headers' => $headers,
						'body'    => $args,
					)
				);
				$req = wp_remote_retrieve_body( $req );
				$req = json_decode( $req, true );

				/*Check if paid*/
				$r['message'] = esc_html__( 'Order created. Payment not completed.', 'masterstudy-lms-learning-management-system' );
				if ( ! empty( $req['paid'] ) && ! empty( $req['amount'] ) && $req['amount'] == $cart_total['total'] * $increment ) {
					update_post_meta( $invoice, 'status', 'completed' );
					STM_LMS_Order::accept_order( $user_id, $invoice );
					$r['message'] = esc_html__( 'Order created. Payment completed.', 'masterstudy-lms-learning-management-system' );
				} else {
					wp_delete_post( $invoice, true );
					$r['status']  = 'error';
					$r['message'] = esc_html__( 'Error occurred. Please try again.', 'masterstudy-lms-learning-management-system' );
					$r['url']     = false;
				}
				$r['url']   = STM_LMS_User::user_page_url( $user_id );
				$r['order'] = $req;
			} else {
				wp_send_json(
					array(
						'status'  => 'error',
						'message' => esc_html__( 'Please, select payment method', 'masterstudy-lms-learning-management-system' ),
					)
				);
				die;
			}
		} else {
			$r['message'] = esc_html__( 'Order created, redirecting', 'masterstudy-lms-learning-management-system' );
			$r['url']     = STM_LMS_User::user_page_url( $user_id );
		}

		wp_send_json( apply_filters( 'stm_lms_purchase_done', $r ) );
		die;

	}

	public static function payment_methods() {
		return apply_filters(
			'stm-lms-payment-methods',
			array(
				'cash'           => esc_html__( 'Offline Payment', 'masterstudy-lms-learning-management-system' ),
				'wire_transfer'  => esc_html__( 'Wire transfer', 'masterstudy-lms-learning-management-system' ),
				'paypal'         => esc_html__( 'Paypal', 'masterstudy-lms-learning-management-system' ),
				'stripe'         => esc_html__( 'Stripe', 'masterstudy-lms-learning-management-system' ),
				'account_number' => esc_html__( 'Account Number', 'masterstudy-lms-learning-management-system' ),
				'holder_name'    => esc_html__( 'Holder name', 'masterstudy-lms-learning-management-system' ),
				'bank_name'      => esc_html__( 'Bank name', 'masterstudy-lms-learning-management-system' ),
				'swift'          => esc_html__( 'Swift', 'masterstudy-lms-learning-management-system' ),
				'description'    => esc_html__( 'Description', 'masterstudy-lms-learning-management-system' ),
				'currency'       => esc_html__( 'Currency', 'masterstudy-lms-learning-management-system' ),
			)
		);
	}

	public static function get_cart_totals( $cart_items ) {
		 $r = array(
			 'total'     => 0,
			 'item_name' => array(),
		 );

		 foreach ( $cart_items as $cart_item ) {
			 $r['total']      += $cart_item['price'];
			 $r['item_name'][] = get_the_title( $cart_item['item_id'] );
		 }

		 $r['item_name'] = implode( ', ', $r['item_name'] );

		 return $r;
	}

}
