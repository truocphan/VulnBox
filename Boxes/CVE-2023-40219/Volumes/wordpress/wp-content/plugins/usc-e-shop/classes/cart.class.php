<?php
/**
 * Cart class
 */
class usces_cart {

	/**
	 * Serial key
	 *
	 * @var string
	 */
	var $serial;

	/**
	 * Constructor
	 */
	public function __construct() {

		if ( ! isset( $_SESSION['usces_cart'] ) ) {
			$_SESSION['usces_cart']  = array();
			$_SESSION['usces_entry'] = array();
		}
	}

	/**
	 * Create instance
	 *
	 * @param string $serial Serial key.
	 * @return object
	 */
	public static function create( $serial ) {
		$instance         = new self();
		$instance->serial = $serial;

		return $instance;
	}

	/**
	 * Add to Cart
	 */
	public function inCart() {
		global $usces;

		$_POST = $usces->stripslashes_deep_post( $_POST );
		if ( $_SERVER['HTTP_REFERER'] ) {
			$_SESSION['usces_previous_url'] = esc_url( $_SERVER['HTTP_REFERER'] );
		} else {
			$_SESSION['usces_previous_url'] = str_replace( 'https://', 'http://', get_home_url() ) . '/';
		}

		$ids     = array_keys( $_POST['inCart'] );
		$post_id = (int) $ids[0];

		$skus = array_keys( $_POST['inCart'][ $post_id ] );
		$sku  = $skus[0];

		$zaiko_id = $usces->getItemZaikoStatusId( $post_id, urldecode( $sku ) );
		if ( false === $zaiko_id ) {
			die( header( 'HTTP/1.0 400 Bad request' ) );
		}

		$this->in_serialize( $post_id, $sku );
		if ( ! isset( $_SESSION['usces_cart'][ $this->serial ]['quant'] ) ) {
			$_SESSION['usces_cart'][ $this->serial ]['quant'] = 0;
		}

		if ( isset( $_POST['quant'][ $post_id ][ $sku ] ) && ! WCUtils::is_blank( $_POST['quant'][ $post_id ][ $sku ] ) ) {

			$post_quant                                       = (int) $_POST['quant'][ $post_id ][ $sku ];
			$_SESSION['usces_cart'][ $this->serial ]['quant'] = apply_filters( 'usces_filter_post_quant', $post_quant, $_SESSION['usces_cart'][ $this->serial ]['quant'] );

		} else {

			if ( isset( $_SESSION['usces_cart'][ $this->serial ] ) ) {
				$_SESSION['usces_cart'][ $this->serial ]['quant'] = apply_filters( 'usces_filter_post_quant', 1, $_SESSION['usces_cart'][ $this->serial ]['quant'] );
			} else {
				$_SESSION['usces_cart'][ $this->serial ]['quant'] = 1;
			}
			$_SESSION['usces_cart'][ $this->serial ]['quant'] = apply_filters( 'usces_filter_inCart_quant', $_SESSION['usces_cart'][ $this->serial ]['quant'] );

		}

		$price = $this->get_realprice( $post_id, $sku, $_SESSION['usces_cart'][ $this->serial ]['quant'], null, $unit_price );
		$price = apply_filters( 'usces_filter_inCart_price', $price, $this->serial );

		$_SESSION['usces_cart'][ $this->serial ]['price']      = $price;
		$_SESSION['usces_cart'][ $this->serial ]['unit_price'] = $unit_price;

		if ( isset( $_POST['advance'] ) ) {
			$_SESSION['usces_cart'][ $this->serial ]['advance'] = $this->wc_serialize( $_POST['advance'] );
		}

		unset( $_SESSION['usces_entry']['order']['usedpoint'] );
		do_action( 'usces_action_after_inCart', $this->serial );
	}

	/**
	 * Update Cart
	 */
	public function upCart() {

		if ( ! isset( $_POST['quant'] ) ) {
			return false;
		}

		global $usces;
		$_POST = $usces->stripslashes_deep_post( $_POST );

		foreach ( $_POST['quant'] as $index => $vs ) {

			if ( ! is_array( $vs ) ) {
				break;
			}

			$ids     = array_keys( $vs );
			$post_id = $ids[0];

			$skus = array_keys( $vs[ $post_id ] );
			$sku  = $skus[0];

			$this->up_serialize( $index, $post_id, $sku );

			if ( ! WCUtils::is_blank( $_POST['quant'][ $index ][ $post_id ][ $sku ] ) ) {

				$_SESSION['usces_cart'][ $this->serial ]['quant']   = (int) $_POST['quant'][ $index ][ $post_id ][ $sku ];
				$_SESSION['usces_cart'][ $this->serial ]['advance'] = isset( $_POST['advance'][ $index ][ $post_id ][ $sku ] ) ? $_POST['advance'][ $index ][ $post_id ][ $sku ] : array();
				if ( isset( $_POST['order_action'] ) ) {
					$price = (int) $_POST['skuPrice'][ $index ][ $post_id ][ $sku ];
				} else {
					$price = $this->get_realprice( $post_id, $sku, $_SESSION['usces_cart'][ $this->serial ]['quant'] );
					$price = apply_filters( 'usces_filter_upCart_price', $price, $this->serial, $index );
				}
				$_SESSION['usces_cart'][ $this->serial ]['price'] = $price;

			}
		}

		unset( $_SESSION['usces_entry']['order']['usedpoint'] );
		do_action( 'usces_action_after_upCart' );
	}

	/**
	 * Add to Cart additional information
	 *
	 * @param string $serial Serial key.
	 * @param string $name Advance name.
	 * @param string $key Advance key.
	 * @param mixed  $value Advance value.
	 */
	public function inCart_advance( $serial, $name, $key, $value ) {
		$_SESSION['usces_cart'][ $serial ]['advance'][ $name ][ $key ] = $value;
	}

	/**
	 * Serialize value
	 *
	 * @param array $value Value.
	 * @return string
	 */
	public function wc_serialize( $value ) {
		$out = null;
		if ( ! empty( $value ) ) {
			$out = urlencode( json_encode( $value ) );
		}
		return $out;
	}

	/**
	 * Unserialize value
	 *
	 * @param string $str Value.
	 * @return array
	 */
	public function wc_unserialize( $str ) {
		$out = array();
		if ( ! empty( $str ) ) {
			$out = json_decode( urldecode( $str ), true );
		}
		return $out;
	}

	/**
	 * Remove Cart
	 */
	public function del_row() {
		$indexs  = array_keys( $_POST['delButton'] );
		$index   = $indexs[0];
		$ids     = array_keys( $_POST['delButton'][ $index ] );
		$post_id = $ids[0];
		$skus    = array_keys( $_POST['delButton'][ $index ][ $post_id ] );
		$sku     = $skus[0];

		$this->up_serialize( $index, $post_id, $sku );
		do_action( 'usces_cart_del_row', $index );

		if ( isset( $_SESSION['usces_cart'][ $this->serial ] ) ) {
			unset( $_SESSION['usces_cart'][ $this->serial ] );
		}
		unset( $_SESSION['usces_entry']['order']['usedpoint'] );
		do_action( 'usces_action_after_cart_del_row', $index );
	}

	/**
	 * Number of rows in cart
	 *
	 * @return int|bool
	 */
	public function num_row() {
		if ( ! isset( $_SESSION['usces_cart'] ) ) {
			return false;
		}

		$num = ( is_array( $_SESSION['usces_cart'] ) ) ? count( $_SESSION['usces_cart'] ) : 0;

		if ( $num > 0 ) {
			return $num;
		} else {
			return false;
		}
	}

	/**
	 * Clear Cart
	 * alias crear_cart()
	 */
	public function clear_cart() {
		$this->crear_cart();
	}
	/**
	 * Clear Cart
	 */
	public function crear_cart() {
		$_SESSION['usces_cart']  = array();
		$_SESSION['usces_entry'] = array();
		do_action( 'usces_action_after_clear_cart' );
	}

	/**
	 * Get Cart
	 *
	 * @return array
	 */
	public function get_cart() {
		if ( ! isset( $_SESSION['usces_cart'] ) ) {
			return array();
		}

		$rows = array();

		$i = 0;
		foreach ( (array) $_SESSION['usces_cart'] as $serial => $qua ) {
			$rows[ $i ] = $this->key_unserialize( $serial );
			$i++;
		}

		return $rows;
	}

	/**
	 * Serial key creation
	 *
	 * @param int    $id Post ID.
	 * @param string $sku SKU code.
	 */
	public function in_serialize( $id, $sku ) {
		global $usces;

		$_POST        = $usces->stripslashes_deep_post( $_POST );
		$pots         = array();
		$option_field = usces_get_opts( $id, 'name' );

		foreach ( $option_field as $opkey => $opval ) {
			if ( ! isset( $_POST['itemOption'][ $id ][ $sku ][ urlencode( $opkey ) ] ) ) {
				if ( 3 === (int) $opval['means'] || 4 === (int) $opval['means'] ) {
					$pots[ urlencode( $opkey ) ] = '';
				}
			}
		}

		if ( isset( $_POST['itemOption'][ $id ][ $sku ] ) ) {
			foreach ( $_POST['itemOption'][ $id ][ $sku ] as $key => $value ) {
				if ( ! isset( $option_field[ urldecode( $key ) ] ) ) {
					continue;
				}

				$option = $option_field[ urldecode( $key ) ];
				if ( 3 === (int) $option['means'] || 4 === (int) $option['means'] ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $k => $v ) {
							$pots[ $key ][ trim( $v ) ] = trim( $v );
						}
					} else {
						$pots[ $key ] = $value;
					}
				} else {
					if ( is_array( $value ) ) {
						foreach ( $value as $k => $v ) {
							$pots[ $key ][ urlencode( trim( $v ) ) ] = urlencode( trim( $v ) );
						}
					} else {
						$pots[ $key ] = urlencode( $value );
					}
				}
			}
			ksort( $pots );
			$sels[ $id ][ $sku ] = $pots;
		} else {
			if ( empty( $pots ) ) {
				$sels[ $id ][ $sku ] = 0;
			} else {
				$sels[ $id ][ $sku ] = $pots;
			}
		}
		$sels         = apply_filters( 'usces_filter_in_serialize', $sels, $id, $sku );
		$this->serial = serialize( $sels );
	}

	/**
	 * Serial key recreation
	 *
	 * @param int    $index Cart index.
	 * @param int    $id Post ID.
	 * @param string $sku SKU code.
	 */
	public function up_serialize( $index, $id, $sku ) {
		global $usces;

		$_POST        = $usces->stripslashes_deep_post( $_POST );
		$pots         = array();
		$option_field = usces_get_opts( $id, 'name' );

		if ( isset( $_POST['itemOption'][ $index ][ $id ][ $sku ] ) && is_array( $_POST['itemOption'][ $index ][ $id ][ $sku ] ) ) {
			foreach ( $_POST['itemOption'][ $index ][ $id ][ $sku ] as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						$pots[ $key ][ $v ] = $v;
					}
				} else {
					$pots[ $key ] = $value;
				}
			}
			ksort( $pots );
			$sels[ $id ][ $sku ] = $pots;
		} else {
			$sels[ $id ][ $sku ] = 0;
		}
		$sels         = apply_filters( 'usces_filter_up_serialize', $sels, $index, $id, $sku );
		$this->serial = serialize( $sels );
	}

	/**
	 * Serial key decompression
	 *
	 * @param string $serial Serial key.
	 * @return array
	 */
	public function key_unserialize( $serial ) {
		$array = unserialize( $serial );
		$ids   = array_keys( $array );
		$skus  = array_keys( $array[ $ids[0] ] );

		$row['serial']  = $serial;
		$row['post_id'] = $ids[0];
		$row['sku']     = $skus[0];
		$options        = $array[ $ids[0] ][ $skus[0] ];
		$opt_fields     = usces_get_opts( $row['post_id'], 'sort' );
		$new_opt        = array();
		foreach ( $opt_fields as $key => $field ) {
			$name             = urlencode( $field['name'] );
			$new_opt[ $name ] = isset( $options[ $name ] ) ? $options[ $name ] : '';
		}
		$row['options']    = apply_filters( 'usces_filter_key_unserialize_options', $new_opt, $ids[0], $skus[0], $serial );
		$row['price']      = isset( $_SESSION['usces_cart'][ $serial ]['price'] ) ? $_SESSION['usces_cart'][ $serial ]['price'] : 0;
		$row['unit_price'] = isset( $_SESSION['usces_cart'][ $serial ]['unit_price'] ) ? $_SESSION['usces_cart'][ $serial ]['unit_price'] : 0;
		$row['quantity']   = isset( $_SESSION['usces_cart'][ $serial ]['quant'] ) ? $_SESSION['usces_cart'][ $serial ]['quant'] : 0;
		$row['advance']    = isset( $_SESSION['usces_cart'][ $serial ]['advance'] ) ? $_SESSION['usces_cart'][ $serial ]['advance'] : '';

		return $row;
	}

	/**
	 * Is condition
	 *
	 * @return bool
	 */
	public function is_order_condition() {
		if ( isset( $_SESSION['usces_entry']['condition'] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Set condition
	 *
	 * @param array $conditions Condition.
	 */
	public function set_order_condition( $conditions ) {
		foreach ( $conditions as $key => $value ) {
			$_SESSION['usces_entry']['condition'][ $key ] = $value;
		}
	}

	/**
	 * Get condition
	 *
	 * @return mixed
	 */
	public function get_order_condition() {
		if ( isset( $_SESSION['usces_entry']['condition'] ) ) {
			return $_SESSION['usces_entry']['condition'];
		} else {
			return null;
		}
	}

	/**
	 * Set entry information
	 *
	 * @param array $array Entry data.
	 */
	public function set_order_entry( $array ) {
		foreach ( $array as $key => $value ) {
			$_SESSION['usces_entry']['order'][ $key ] = $value;
		}
	}

	/**
	 * Get entry information
	 *
	 * @param string $key Key.
	 * @return mixed
	 */
	public function get_order_entry( $key ) {
		if ( isset( $_SESSION['usces_entry']['order'][ $key ] ) ) {
			return $_SESSION['usces_entry']['order'][ $key ];
		} else {
			return null;
		}
	}

	/**
	 * Set entry data
	 */
	public function entry() {
		global $usces;
		$_POST = $usces->stripslashes_deep_post( $_POST );

		if ( isset( $_SESSION['usces_member']['ID'] ) && ! empty( $_SESSION['usces_member']['ID'] ) ) {

			$usces->set_member_session_data( $_SESSION['usces_member']['ID'] );

			if ( 'confirm' !== $usces->page ) {
				foreach ( $_SESSION['usces_member'] as $key => $value ) {
					if ( 'custom_member' === $key ) {
						unset( $_SESSION['usces_entry']['custom_member'] );
						foreach ( $_SESSION['usces_member']['custom_member'] as $mbkey => $mbvalue ) {
							if ( empty( $_SESSION['usces_entry']['custom_customer'][ $mbkey] ) ) {
								if ( is_array( $mbvalue ) ) {
									foreach ( $mbvalue as $k => $v ) {
										$_SESSION['usces_entry']['custom_customer'][ $mbkey ][ $v ] = $v;
									}
								} else {
									$_SESSION['usces_entry']['custom_customer'][ $mbkey ] = $mbvalue;
								}
							}
						}
					} else {
						if ( 'country' === $key && empty( $value ) ) {
							$_SESSION['usces_entry']['customer'][ $key ] = usces_get_base_country();
						} else {
							$_SESSION['usces_entry']['customer'][ $key ] = trim( $value );
						}
					}
				}
			}
		}
		if ( isset( $_POST['customer'] ) ) {
			foreach ( $_POST['customer'] as $key => $value ) {
				if ( 'country' === $key && empty( $value ) ) {
					$_SESSION['usces_entry']['customer'][ $key ] = usces_get_base_country();
				} else {
					$_SESSION['usces_entry']['customer'][ $key ] = trim( $value );
				}
			}
		}

		if ( isset( $_POST['delivery'] ) ) {
			foreach ( $_POST['delivery'] as $key => $value ) {
				if ( 'country' === $key && empty( $value ) ) {
					$_SESSION['usces_entry']['delivery'][ $key ] = usces_get_base_country();
				} else {
					$_SESSION['usces_entry']['delivery'][ $key ] = trim( $value );
				}
			}
		}
		if ( isset( $_POST['delivery']['delivery_flag'] ) && 0 === (int) $_POST['delivery']['delivery_flag'] ) {
			foreach ( $_SESSION['usces_entry']['customer'] as $key => $value ) {
				if ( 'country' === $key && empty( $value ) ) {
					$_SESSION['usces_entry']['delivery'][ $key ] = usces_get_base_country();
				} else {
					$_SESSION['usces_entry']['delivery'][ $key ] = trim( $value );
				}
			}
		}

		if ( isset( $_POST['offer'] ) ) {
			foreach ( $_POST['offer'] as $key => $value ) {
				$_SESSION['usces_entry']['order'][ $key ] = trim( $value );
			}
		}

		if ( isset( $_POST['reserve'] ) ) {
			foreach ( $_POST['reserve'] as $key => $value ) {
				$_SESSION['usces_entry']['reserve'][ $key ] = trim( $value );
			}
		}
		if ( isset( $_POST['custom_order'] ) ) {
			unset( $_SESSION['usces_entry']['custom_order'] );
			foreach ( $_POST['custom_order'] as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						$_SESSION['usces_entry']['custom_order'][ $key ][ trim( $v ) ] = trim( $v );
					}
				} else {
					$_SESSION['usces_entry']['custom_order'][ $key ] = trim( $value );
				}
			}
		}
		if ( isset( $_SESSION['usces_entry']['delivery']['delivery_flag'] ) && 0 === (int) $_SESSION['usces_entry']['delivery']['delivery_flag'] ) {
			$this->set_custom_customer_delivery();
		}
		if ( isset( $_POST['custom_customer'] ) ) {
			unset( $_SESSION['usces_entry']['custom_customer'] );
			foreach ( $_POST['custom_customer'] as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						$_SESSION['usces_entry']['custom_customer'][ $key ][ trim( $v ) ] = trim( $v );
					}
				} else {
					$_SESSION['usces_entry']['custom_customer'][ $key ] = trim( $value );
				}
			}
		}
		if ( isset( $_POST['custom_delivery'] ) ) {
			unset( $_SESSION['usces_entry']['custom_delivery'] );
			foreach ( $_POST['custom_delivery'] as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						$_SESSION['usces_entry']['custom_delivery'][ $key ][ trim( $v ) ] = trim( $v );
					}
				} else {
					$_SESSION['usces_entry']['custom_delivery'][ $key ] = trim( $value );
				}
			}
		}
		if ( isset( $_POST['delivery']['delivery_flag'] ) && 0 === (int) $_POST['delivery']['delivery_flag'] ) {
			$this->set_custom_customer_delivery();
		}
	}

	/**
	 * Get entry data
	 *
	 * @return array
	 */
	public function get_entry() {
		$res['customer'] = array(
			'mailaddress1' => '',
			'mailaddress2' => '',
			'password1'    => '',
			'password2'    => '',
			'name1'        => '',
			'name2'        => '',
			'name3'        => '',
			'name4'        => '',
			'zipcode'      => '',
			'address1'     => '',
			'address2'     => '',
			'address3'     => '',
			'tel'          => '',
			'fax'          => '',
			'country'      => '',
			'pref'         => '',
		);
		if ( isset( $_SESSION['usces_entry']['customer'] ) ) {
			foreach ( (array) $_SESSION['usces_entry']['customer'] as $key => $val ) {
				$res['customer'][ $key ] = $val;
			}
		}

		$res['delivery'] = array(
			'name1'         => '',
			'name2'         => '',
			'name3'         => '',
			'name4'         => '',
			'zipcode'       => '',
			'address1'      => '',
			'address2'      => '',
			'address3'      => '',
			'tel'           => '',
			'fax'           => '',
			'country'       => '',
			'pref'          => '',
			'delivery_flag' => '0',
		);
		if ( isset( $_SESSION['usces_entry']['delivery'] ) ) {
			foreach ( (array) $_SESSION['usces_entry']['delivery'] as $key => $val ) {
				$res['delivery'][ $key ] = $val;
			}
		}

		$res['order'] = array(
			'usedpoint'         => '',
			'total_items_price' => '',
			'discount'          => '',
			'shipping_charge'   => '',
			'cod_fee'           => '',
			'shipping_charge'   => '',
			'payment_name'      => '',
			'delivery_method'   => '',
			'delivery_date'     => '',
			'delivery_time'     => '',
			'total_full_price'  => '',
			'note'              => '',
			'tax'               => '',
			'payment_name'      => '',
			'delidue_date'      => '',
		);
		if ( isset( $_SESSION['usces_entry']['order'] ) ) {
			foreach ( (array) $_SESSION['usces_entry']['order'] as $key => $val ) {
				$res['order'][ $key ] = $val;
			}
		}
		if ( isset( $_SESSION['usces_entry']['reserve'] ) ) {
			$res['reserve'] = $_SESSION['usces_entry']['reserve'];
		} else {
			$res['reserve'] = null;
		}
		if ( isset( $_SESSION['usces_entry']['condition'] ) ) {
			$res['condition'] = $_SESSION['usces_entry']['condition'];
		} else {
			$res['condition'] = null;
		}
		if ( isset( $_SESSION['usces_entry']['custom_order'] ) ) {
			$res['custom_order'] = $_SESSION['usces_entry']['custom_order'];
		} else {
			$res['custom_order'] = null;
		}
		if ( isset( $_SESSION['usces_entry']['custom_customer'] ) ) {
			$res['custom_customer'] = $_SESSION['usces_entry']['custom_customer'];
		} else {
			$res['custom_customer'] = null;
		}
		if ( isset( $_SESSION['usces_entry']['custom_delivery'] ) ) {
			$res['custom_delivery'] = $_SESSION['usces_entry']['custom_delivery'];
		} else {
			$res['custom_delivery'] = null;
		}
		return $res;
	}

	/**
	 * Get sales price
	 *
	 * @param int    $post_id Post ID.
	 * @param string $sku SKU code.
	 * @param int    $quant Quantity.
	 * @param float  $price Sales price.
	 * @param float  $unit_price Unit price.
	 * @return float
	 */
	public function get_realprice( $post_id, $sku, $quant, $price = null, &$unit_price = null ) {
		global $usces;

		$sku  = urldecode( $sku );
		$skus = $usces->get_skus( $post_id, 'code' );

		if ( null === $price ) {
			$p = isset( $skus[ $sku ]['price'] ) ? $skus[ $sku ]['price'] : '';
		} else {
			$p = $price;
		}
		$p          = apply_filters( 'usces_filter_realprice', $p, $this->serial );
		$unit_price = $p;
		if ( isset( $skus[ $sku ]['price'] ) && ! $skus[ $sku ]['gp'] ) {
			return $p;
		}

		$realprice = usces_get_gp_price( $post_id, $p, $quant );

		return $realprice;
	}

	/**
	 * Set Pre-Order ID
	 *
	 * @param string $id Pre-Order ID.
	 * @return void
	 */
	public function set_pre_order_id( $id ) {
		$_SESSION['usces_entry']['reserve']['pre_order_id'] = $id;
	}

	/**
	 * Transcribe membership information to shipping address information
	 *
	 * @return void
	 */
	public function set_custom_customer_delivery() {
		if ( isset( $_SESSION['usces_entry']['custom_customer'] ) ) {
			$delivery  = array();
			$csde_meta = usces_has_custom_field_meta( 'delivery' );
			if ( ! empty( $csde_meta ) && is_array( $csde_meta ) ) {
				foreach ( $csde_meta as $key => $entry ) {
					$delivery[ $key ] = $key;
				}
			}
			foreach ( $_SESSION['usces_entry']['custom_customer'] as $mbkey => $mbvalue ) {
				if ( array_key_exists( $mbkey, $delivery ) ) {
					if ( is_array( $mbvalue ) ) {
						foreach ( $mbvalue as $k => $v ) {
							$_SESSION['usces_entry']['custom_delivery'][ $mbkey ][ $v ] = $v;
						}
					} else {
						$_SESSION['usces_entry']['custom_delivery'][ $mbkey ] = $mbvalue;
					}
				}
			}
		}
		if ( ! empty( $_SESSION['usces_entry']['custom_delivery'] ) ) {
			foreach ( $_SESSION['usces_entry']['custom_delivery'] as $key => $value ) {
				if ( ! isset( $_SESSION['usces_entry']['custom_customer'] ) || ! array_key_exists( $key, $_SESSION['usces_entry']['custom_customer'] ) ) {
					// Set item empty.
					if ( is_array( $value ) ) {
						foreach ( $value as $k => $v ) {
							$_SESSION['usces_entry']['custom_delivery'][ $key ][ $v ] = '';
						}
					} else {
						$_SESSION['usces_entry']['custom_delivery'][ $key ] = '';
					}
				}
			}
		}
	}
}
