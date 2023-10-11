<?php
/**
 * Template functions
 *
 * @package Welcart
 */

/**
 * Tax guid
 *
 * @param string $out Return value or echo.
 * @param bool   $tag HTML tag.
 * @return string|void
 */
function usces_guid_tax( $out = '', $tag = true ) {
	global $usces;

	$guid_tax = '';
	if ( $tag ) {
		$guid_tax = $usces->getGuidTax();
	} else {
		if ( isset( $usces->options['tax_display'] ) && 'deactivate' === $usces->options['tax_display'] ) {
			$guid_tax = '';
		} else {
			$tax_rate = (int) $usces->options['tax_rate'];
			if ( isset( $usces->options['tax_mode'] ) ) {
				if ( 'exclude' === $usces->options['tax_mode'] ) {
					$guid_tax = __( '(Excl. Tax)', 'usces' );
				} else {
					$guid_tax = __( '(Incl. Tax)', 'usces' );
				}
			} else {
				if ( 0 < $tax_rate ) {
					$guid_tax = __( '(Excl. Tax)', 'usces' );
				} else {
					$guid_tax = __( '(Incl. Tax)', 'usces' );
				}
			}
		}
	}

	$guid_tax = apply_filters( 'usces_filter_guid_tax', $guid_tax );

	if ( 'return' === $out ) {
		return wel_esc_script( $guid_tax );
	} else {
		wel_esc_script_e( $guid_tax );
	}
}

/**
 * Tax label
 *
 * @param array  $data Order data.
 * @param string $out  Return value or echo.
 * @return string|void
 */
function usces_tax_label( $data = array(), $out = '' ) {
	global $usces;

	if ( empty( $data ) || ! array_key_exists( 'order_condition', $data ) ) {
		$condition = $usces->get_condition();
		$tax_mode  = $usces->options['tax_mode'];
	} else {
		$condition = maybe_unserialize( $data['order_condition'] );
		$tax_mode  = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : $usces->options['tax_mode'];
	}
	if ( 'exclude' === $tax_mode ) {
		$label = __( 'consumption tax', 'usces' );
	} else {
		if ( isset( $condition['tax_mode'] ) && ! empty( $data['ID'] ) ) {
			$materials = array(
				'total_items_price' => $data['order_item_total_price'],
				'discount'          => $data['order_discount'],
				'shipping_charge'   => $data['order_shipping_charge'],
				'cod_fee'           => $data['order_cod_fee'],
				'use_point'         => $data['order_usedpoint'],
				'carts'             => usces_get_ordercartdata( $data['ID'] ),
				'condition'         => $condition,
				'order_id'          => $data['ID'],
			);
			$label     = __( 'Internal tax', 'usces' ) . '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
		} else {
			$label = __( 'Internal tax', 'usces' );
		}
	}
	$label = apply_filters( 'usces_filter_tax_label', $label );

	if ( 'return' === $out ) {
		return wel_esc_script( $label );
	} else {
		wel_esc_script_e( $label );
	}
}

/**
 * Tax Calculation
 *
 * @param array  $data When the 'order' key in $data exist, entry data, unless order data.
 * @param string $out  Return value or echo.
 * @return string|void
 */
function usces_tax( $data, $out = '' ) {
	global $usces;

	if ( empty( $data ) || ! array_key_exists( 'order_condition', $data ) ) {
		$condition = $usces->get_condition();
		$tax_mode  = $usces->options['tax_mode'];
	} else {
		$condition = maybe_unserialize( $data['order_condition'] );
		$tax_mode  = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : $usces->options['tax_mode'];
	}

	if ( ! usces_is_tax_display() ) {
		$tax_str = '';
	} else {
		if ( 'exclude' === $tax_mode ) {
			if ( array_key_exists( 'order', $data ) && array_key_exists( 'tax', $data['order'] ) ) { /* from Entry */
				$tax = $data['order']['tax'];
			} elseif ( array_key_exists( 'order_tax', $data ) ) { /* from Order Data */
				$tax = $data['order_tax'];
			} elseif ( array_key_exists( 'tax', $data ) ) {
				$tax = $data['tax'];
			} else {
				$tax = 0;
			}
			$tax_str = usces_crform( $tax, true, false, 'return' );
		} else {
			if ( array_key_exists( 'order', $data ) ) { /* from Entry */
				$materials = array(
					'total_items_price' => $data['order']['total_items_price'],
					'discount'          => ( isset( $data['order']['discount'] ) ) ? $data['order']['discount'] : 0,
					'shipping_charge'   => ( isset( $data['order']['shipping_charge'] ) ) ? $data['order']['shipping_charge'] : 0,
					'cod_fee'           => ( isset( $data['order']['cod_fee'] ) ) ? $data['order']['cod_fee'] : 0,
					'use_point'         => ( isset( $data['order']['use_point'] ) ) ? $data['order']['use_point'] : 0,
				);
				$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
			} elseif ( array_key_exists( 'order_tax', $data ) ) { /* from Order Data */
				$materials = array(
					'total_items_price' => $data['order_item_total_price'],
					'discount'          => $data['order_discount'],
					'shipping_charge'   => $data['order_shipping_charge'],
					'cod_fee'           => $data['order_cod_fee'],
					'use_point'         => $data['order_usedpoint'],
					'carts'             => usces_get_ordercartdata( $data['ID'] ),
					'condition'         => unserialize( $data['order_condition'] ),
				);
				$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
			} elseif ( array_key_exists( 'tax', $data ) ) {
				$item_total_price = $usces->get_total_price( $data['cart'] );
				$materials        = array(
					'total_items_price' => $item_total_price,
					'discount'          => ( isset( $data['discount'] ) ) ? $data['discount'] : 0,
					'shipping_charge'   => ( isset( $data['shipping_charge'] ) ) ? $data['shipping_charge'] : 0,
					'cod_fee'           => ( isset( $data['cod_fee'] ) ) ? $data['cod_fee'] : 0,
					'use_point'         => ( isset( $data['usedpoint'] ) ) ? $data['usedpoint'] : 0,
					'carts'             => $data['cart'],
				);
				$tax_str          = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
			} else {
				$materials = array();
				$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
			}
		}
		$tax_str = apply_filters( 'usces_filter_tax', $tax_str );
	}

	if ( 'return' === $out ) {
		return wel_esc_script( $tax_str );
	} else {
		wel_esc_script_e( $tax_str );
	}
}

/**
 * Order data tax
 *
 * @param array  $data Order data.
 * @param string $tax_mode 'exclude' or 'include'.
 * @return string
 */
function usces_order_history_tax( $data, $tax_mode ) {
	global $usces;

	if ( 'exclude' === $tax_mode ) {
		$tax_str = usces_crform( $data['tax'], true, false, 'return' );
	} else {
		$materials = array(
			'total_items_price' => $data['total_items_price'],
			'discount'          => $data['discount'],
			'shipping_charge'   => $data['shipping_charge'],
			'cod_fee'           => $data['cod_fee'],
			'use_point'         => $data['usedpoint'],
			'carts'             => $data['cart'],
			'condition'         => $data['condition'],
		);
		$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
	}
	$tax_str = apply_filters( 'usces_filter_order_history_tax', $tax_str, $data, $tax_mode );
	return $tax_str;
}

/**
 * Tax-inclusive Calculation
 *
 * @param array  $materials Breakdown of Amounts.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_internal_tax( $materials, $out = '' ) {
	global $usces;

	if ( ! usces_is_tax_display() ) {
		$tax = 0;
	} else {
		if ( ! empty( $materials['condition'] ) ) {
			$condition = $materials['condition'];
		} else {
			$condition = $usces->get_condition();
		}
		$reduced_taxrate = ( isset( $condition['applicable_taxrate'] ) && 'reduced' === $condition['applicable_taxrate'] ) ? true : false;
		if ( $reduced_taxrate ) {
			$usces_tax = Welcart_Tax::get_instance();
			$usces_tax->get_order_tax( $materials );
			$tax = apply_filters( 'usces_filter_internal_tax', $usces_tax->tax, $materials );
		} else {
			if ( 1 === (int) usces_point_coverage() ) {
				if ( 'products' === $condition['tax_target'] ) {
					$total = $materials['total_items_price'] + $materials['discount'];
				} else {
					$total = $materials['total_items_price'] + $materials['discount'] + $materials['shipping_charge'] + $materials['cod_fee'];
				}
			} else {
				if ( 'products' === $condition['tax_target'] ) {
					$total = $materials['total_items_price'] + $materials['discount'];
				} else {
					$use_point = ( empty( $materials['use_point'] ) ) ? 0 : $materials['use_point'];
					$total     = $materials['total_items_price'] + $materials['discount'] - $use_point + $materials['shipping_charge'] + $materials['cod_fee'];
				}
			}
			$total    = apply_filters( 'usces_filter_internal_tax_total', $total, $materials );
			$tax_rate = (float) $condition['tax_rate'];
			$tax      = $total * $tax_rate / 100;
			$tax      = $total - $total / ( 1 + ( $tax_rate / 100 ) );
			$tax      = usces_tax_rounding_off( $tax, $condition['tax_method'] );
			$tax      = apply_filters( 'usces_filter_internal_tax', $tax, $materials );
		}
	}

	if ( 'return' === $out ) {
		return wel_esc_script( $tax );
	} else {
		wel_esc_script_e( $tax );
	}
}

/**
 * Currency Symbol
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_currency_symbol( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->getCurrencySymbol();
	} else {
		echo esc_html( $usces->getCurrencySymbol() );
	}
}

/**
 * Error Verification
 *
 * @return bool
 */
function usces_is_error_message() {
	global $usces;

	if ( '' !== $usces->error_message ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Is item
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function usces_is_item( $post_id = null ) {
	if ( null === $post_id ) {
		global $post;
		if ( empty( $post->ID ) ) {
			return false;
		}
		$post_id = $post->ID;
	}
	$product = wel_get_product( $post_id );
	if ( isset( $product['_pst'] ) && 'item' === $product['_pst']->post_mime_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Item code
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemCode( $out = '' ) {
	global $post;

	$product = wel_get_product( $post->ID );
	$str     = $product['itemCode'];

	if ( 'return' === $out ) {
		return $str;
	} else {
		echo esc_html( $str );
	}
}

/**
 * Item name
 *
 * @param string $out  Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_itemName( $out = '', $post = null ) {
	if ( null === $post ) {
		global $post;
	}

	$product = wel_get_product( $post );
	$str     = $product['itemName'];

	if ( 'return' === $out ) {
		return $str;
	} else {
		echo esc_html( $str );
	}
}

/**
 * Point rate
 *
 * @param string $out  Return value or echo.
 * @return string|void
 */
function usces_the_point_rate( $out = '' ) {
	global $post;

	$product = wel_get_product( $post );
	$str     = $product['itemPointrate'];
	$rate    = (int) $str;

	if ( 'return' === $out ) {
		return $rate;
	} else {
		echo esc_html( $rate );
	}
}

/**
 * Shipment
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_shipment_aim( $out = '' ) {
	global $post;

	$product = wel_get_product( $post );
	$str     = $product['itemShipping'];
	$no      = (int) $str;
	if ( 0 === $no ) {
		return '';
	}
	$rules = get_option( 'usces_shipping_rule' );

	if ( 'return' === $out ) {
		return $rules[ $no ];
	} else {
		echo esc_html( $rules[ $no ] );
	}
}

/**
 * Current item
 */
function usces_the_item() {
	global $usces, $post;

	$usces->itemskus        = wel_get_skus( $post );
	$usces->current_itemsku = -1;
	$usces->itemopts        = wel_get_opts( $post );
	$usces->current_itemopt = -1;

	return;
}

/**
 * Get item meta
 *
 * @param string $metakey Meta key.
 * @param int    $post_id Post ID.
 * @param string $out     Return value or echo.
 * @return string|void
 */
function usces_get_itemMeta( $metakey, $post_id, $out = '' ) {
	$product      = wel_get_product( $post_id );
	$reserved_key = ltrim( $metakey, '_' );

	if ( array_key_exists( $reserved_key, $product ) ) {
		$meta = $product[ $reserved_key ];
	} else {
		$meta = $product[ $metakey ];
	}
	if ( is_array( $meta ) ) {
		$value = $meta[0];
	} else {
		$value = $meta;
	}

	if ( 'return' === $out ) {
		return $value;
	} else {
		echo esc_html( $value );
	}
}

/**
 * Count SKU
 *
 * @return int
 */
function usces_sku_num() {
	global $usces;

	$sku_num = ( ! empty( $usces->itemskus ) && is_array( $usces->itemskus ) ) ? count( $usces->itemskus ) : 0;
	return $sku_num;
}

/**
 * Is SKU
 *
 * @return bool
 */
function usces_is_skus() {
	global $usces;

	if ( ! empty( $usces->itemskus ) && is_array( $usces->itemskus ) && 0 < count( $usces->itemskus ) ) {
		$usces->current_itemsku = -1;
		reset( $usces->itemskus );
		$usces->itemsku = array();
		return true;
	} else {
		return false;
	}
}

/**
 * Reset SKU
 */
function usces_reset_skus() {
	global $usces;

	$usces->current_itemsku = -1;
	reset( $usces->itemskus );
}

/**
 * Have SKU
 *
 * @return bool
 */
function usces_have_skus() {
	global $usces;

	if ( null === $usces->current_itemsku ) {
		$usces->current_itemsku = -1;
	}

	if ( $usces->current_itemsku + 1 < usces_sku_num() ) {
		$usces->current_itemsku++;
		$usces->itemsku = $usces->itemskus[ $usces->current_itemsku ];
		return true;
	} else {
		return false;
	}
}

/**
 * SKU data
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemSku( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->itemsku['code'];
	} else {
		echo esc_attr( $usces->itemsku['code'] );
	}
}

/**
 * SKU price
 *
 * @param string $out Return value or echo.
 * @return int|void
 */
function usces_the_itemPrice( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->itemsku['price'];
	} else {
		echo number_format( $usces->itemsku['price'] );
	}
}

/**
 * SKU normal price
 *
 * @param string $out Return value or echo.
 * @return int|void
 */
function usces_the_itemCprice( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->itemsku['cprice'];
	} else {
		echo number_format( $usces->itemsku['cprice'] );
	}
}

/**
 * Formatted SKU price
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemPriceCr( $out = '' ) {
	global $usces;

	$res = $usces->get_currency( $usces->itemsku['price'], true, false );
	$res = apply_filters( 'usces_filter_the_item_price_cr', $res, $usces->itemsku['price'], $out );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo esc_html( $res );
	}
}

/**
 * Formatted normal SKU price
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemCpriceCr( $out = '' ) {
	global $usces;

	$res = $usces->get_currency( $usces->itemsku['cprice'], true, false );
	$res = apply_filters( 'usces_filter_the_item_cprice_cr', $res, $usces->itemsku['cprice'], $out );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo esc_html( $res );
	}
}

/**
 * Currency code
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_crcode( $out = '' ) {
	global $usces;

	$res = esc_html( $usces->get_currency_code() );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo __( $res, 'usces' );
	}
}

/**
 * Currency symbol code
 *
 * @param string $out Return value or echo.
 * @param string $js  Use js.
 * @return string|void
 */
function usces_crsymbol( $out = '', $js = '' ) {
	global $usces;

	$res = $usces->getCurrencySymbol();
	if ( 'js' === $js && '&yen;' == $res ) {
		$res = mb_convert_encoding( $res, 'UTF-8', 'HTML-ENTITIES' );
	}
	if ( 'return' === $out ) {
		return $res;
	} else {
		echo esc_html( $res );
	}
}

/**
 * Stock
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemZaiko( $out = '' ) {
	global $usces, $post;

	$item_order_acceptable = $usces->getItemOrderAcceptable( $post->ID );
	$num                   = (int) $usces->itemsku['stock'];
	$stocknum              = $usces->itemsku['stocknum'];

	if ( 1 !== $item_order_acceptable || WCUtils::is_blank( $stocknum ) ) {
		if ( 1 < $num || ( 0 === (int) $usces->itemsku['stocknum'] && ! WCUtils::is_blank( $usces->itemsku['stocknum'] ) ) ) {
			$res = $usces->zaiko_status[ $num ];
		} elseif ( 1 >= $num && ( 0 === (int) $usces->itemsku['stocknum'] && ! WCUtils::is_blank( $usces->itemsku['stocknum'] ) ) ) {
			$res = $usces->zaiko_status[2];
		} else {
			$res = $usces->zaiko_status[ $num ];
		}
	} else {
		if ( 1 < $num ) {
			$res = $usces->zaiko_status[ $num ];
		} elseif ( 1 >= $num && 0 >= (int) $stocknum ) {
			$res = ( ! empty( $usces->options['order_acceptable_label'] ) ) ? $usces->options['order_acceptable_label'] : __( 'Order acceptable', 'usces' );
		} else {
			$res = $usces->zaiko_status[ $num ];
		}
	}

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo esc_html( $res );
	}
}

/**
 * Stock status
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemZaikoStatus( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return usces_get_itemZaiko( 'name' );
	} else {
		echo esc_html( usces_get_itemZaiko( 'name' ) );
	}
}

/**
 * SKU stock
 *
 * @param string $field Key.
 * @param int    $post_id Post ID.
 * @param string $sku SKU code.
 * @return string
 */
function usces_get_itemZaiko( $field = 'name', $post_id = null, $sku = null ) {
	global $usces;

	if ( null === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	if ( empty( $sku ) && ! empty( $usces->itemsku ) ) {
		$num      = (int) $usces->itemsku['stock'];
		$stocknum = $usces->itemsku['stocknum'];
	} else {
		$skus     = wel_get_skus( $post_id, 'code' );
		$num      = (int) $skus[ $sku ]['stock'];
		$stocknum = $skus[ $sku ]['stocknum'];
	}

	if ( 'id' === $field ) {
		$res = $num;
	} else {
		$item_order_acceptable = $usces->getItemOrderAcceptable( $post_id );
		if ( 1 !== (int) $item_order_acceptable || WCUtils::is_blank( $stocknum ) ) {
			$res = $usces->zaiko_status[ $num ];
		} else {
			if ( 2 > $num && 0 >= (int) $stocknum ) {
				$res = ( ! empty( $usces->options['order_acceptable_label'] ) ) ? $usces->options['order_acceptable_label'] : __( 'Order acceptable', 'usces' );
			} else {
				$res = $usces->zaiko_status[ $num ];
			}
		}
	}
	return $res;
}

/**
 * SKU stock
 *
 * @param string $out Return value or echo.
 * @return int|void
 */
function usces_the_itemZaikoNum( $out = '' ) {
	global $usces;

	$num = $usces->itemsku['stocknum'];

	if ( 'return' === $out ) {
		return $num;
	} else {
		echo number_format( $num );
	}
}

/**
 * SKU title
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemSkuDisp( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->itemsku['name'];
	} else {
		echo esc_html( $usces->itemsku['name'] );
	}
}

/**
 * SKU unit
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemSkuUnit( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->itemsku['unit'];
	} else {
		echo esc_html( $usces->itemsku['unit'] );
	}
}

/**
 * Top SKU code
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_firstSku( $out = '' ) {
	global $post, $usces;

	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );

	if ( 'return' === $out ) {
		return $skus[0]['code'];
	} else {
		echo esc_html( $skus[0]['code'] );
	}
}

/**
 * Top SKU price
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_firstPrice( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );

	if ( empty( $skus ) ) {
		$price = null;
	} else {
		$price = $skus[0]['price'];
	}

	$price = apply_filters( 'usces_filter_the_first_price', $price, $post_id, $skus, $out );

	if ( 'return' === $out ) {
		return $price;
	} else {
		echo number_format( $price );
	}
}

/**
 * Top SKU normal price
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_firstCprice( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );

	if ( 'return' === $out ) {
		return $skus[0]['cprice'];
	} else {
		echo number_format( $skus[0]['cprice'] );
	}
}

/**
 * Formatted top SKU price
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_firstPriceCr( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );
	$res     = $usces->get_currency( $skus[0]['price'], true, false );

	$price = apply_filters( 'usces_filter_the_first_price_cr', $res, $skus[0]['price'], $post_id, $skus, $out );

	if ( 'return' === $out ) {
		return $price;
	} else {
		echo esc_html( $price );
	}
}

/**
 * Formatted top SKU normal price
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_firstCpriceCr( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );
	$res     = $usces->get_currency( $skus[0]['cprice'], true, false );

	$cprice = apply_filters( 'usces_filter_the_first_cprice_cr', $res, $skus[0]['cprice'], $post_id, $skus, $out );

	if ( 'return' === $out ) {
		return $cprice;
	} else {
		echo esc_html( $cprice );
	}
}

/**
 * Top SKU stock
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_firstZaiko( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );

	if ( 'return' === $out ) {
		return $skus[0]['stock'];
	} else {
		echo esc_html( $skus[0]['stock'] );
	}
}

/**
 * Last SKU
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_lastSku( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );
	$sku     = end( $skus );

	if ( 'return' === $out ) {
		return $sku['code'];
	} else {
		echo esc_html( $sku['code'] );
	}
}

/**
 * Last SKU price
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_lastPrice( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );
	$sku     = end( $skus );

	if ( 'return' === $out ) {
		return $sku['price'];
	} else {
		echo number_format( $sku['price'] );
	}
}

/**
 * Last SKU stock
 *
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_lastZaiko( $out = '', $post = null ) {
	global $usces;

	if ( null === $post ) {
		global $post;
	}
	$post_id = $post->ID;
	$skus    = wel_get_skus( $post_id );
	$sku     = end( $skus );

	if ( 'return' === $out ) {
		return $sku['stock'];
	} else {
		echo esc_html( $sku['stock'] );
	}
}

/**
 * In stock
 *
 * @return bool
 */
function usces_have_zaiko() {
	global $post, $usces;
	return $usces->is_item_zaiko( $post->ID, $usces->itemsku['code'] );
}

/**
 * In stock
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function usces_have_zaiko_anyone( $post_id = null ) {
	global $post, $usces;

	if ( null === $post_id ) {
		$post_id = $post->ID;
	}

	$skus   = wel_get_skus( $post_id );
	$status = false;

	foreach ( $skus as $sku ) {
		if ( $usces->is_item_zaiko( $post_id, $sku['code'] ) ) {
			$status = true;
			break;
		}
	}
	return apply_filters( 'usces_have_zaiko_anyone', $status, $post_id, $skus );
}

/**
 * Low or not stock
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function usces_have_fewstock( $post_id = null ) {
	global $post, $usces;

	if ( null === $post_id ) {
		$post_id = $post->ID;
	}

	$skus = wel_get_skus( $post_id );
	$res  = false;
	foreach ( $skus as $sku ) {
		if ( 1 === (int) $sku['stock'] ) {
			$res = true;
			break;
		}
	}
	return $res;
}

/**
 * Is applied large-lot discount
 *
 * @param int    $post_id Post ID.
 * @param string $sku SKU code.
 * @param int    $quant Quantity.
 * @return bool
 */
function usces_is_gptekiyo( $post_id, $sku, $quant ) {
	global $usces;
	return $usces->is_gptekiyo( $post_id, $sku, $quant );
}

/**
 * Large-lot discount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemGpExp( $out = '' ) {
	global $post, $usces;

	$post_id = $post->ID;
	$sku     = $usces->itemsku['code'];
	$gpn1    = $usces->getItemGpNum1( $post_id );
	$gpn2    = $usces->getItemGpNum2( $post_id );
	$gpn3    = $usces->getItemGpNum3( $post_id );
	$gpd1    = $usces->getItemGpDis1( $post_id );
	$gpd2    = $usces->getItemGpDis2( $post_id );
	$gpd3    = $usces->getItemGpDis3( $post_id );
	$unit    = $usces->getItemSkuUnit( $post_id, $sku );
	$price   = $usces->getItemPrice( $post_id, $sku );

	if ( ( 0 === (int) $usces->itemsku['gp'] ) || empty( $gpn1 ) || empty( $gpd1 ) ) {
		return;
	}

	if ( ( isset( $usces->options['tax_display'] ) && 'deactivate' === $usces->options['tax_display'] ) || ( isset( $usces->options['tax_mode'] ) && 'include' === $usces->options['tax_mode'] ) ) {
		$tax_rate = 0;
	} else {
		$usces_tax = Welcart_Tax::get_instance();
		$tax_rate  = $usces_tax->get_sku_tax_rate( $post_id, $sku );
	}

	$html = "<dl class='itemGpExp'>\n<dt>" . apply_filters( 'usces_filter_itemGpExp_title', __( 'Business package discount', 'usces' ) ) . "</dt>\n<dd>\n<ul>\n";
	if ( ! empty( $gpn1 ) && ! empty( $gpd1 ) ) {
		if ( empty( $gpn2 ) || empty( $gpd2 ) ) {
			$price1 = wel_gp_price_discount( $price, $gpd1 );
			$html  .= '<li>';
			$html  .= sprintf( __( '<span class=%5$s>%1$s</span>%2$s par 1%3$s for more than %4$s%3$s', 'usces' ),
				$usces->get_currency( $price1, true, false ),
				$usces->getGuidTax(),
				esc_html( $unit ),
				$gpn1,
				"'price'"
			);
			if ( 0 < $tax_rate ) {
				$html .= usces_crform_the_itemGpExp_taxincluded( $price1, $tax_rate );
			}
			$html .= "</li>\n";
		} else {
			$price1 = wel_gp_price_discount( $price, $gpd1 );
			$html  .= '<li>';
			$html  .= sprintf( __( '<span class=%6$s>%1$s</span>%2$s par 1%3$s for %4$s-%5$s%3$s', 'usces' ),
				$usces->get_currency( $price1, true, false ),
				$usces->getGuidTax(),
				esc_html( $unit ),
				$gpn1,
				$gpn2 - 1,
				"'price'"
			);
			if ( 0 < $tax_rate ) {
				$html .= usces_crform_the_itemGpExp_taxincluded( $price1, $tax_rate );
			}
			$html .= "</li>\n";
			if ( empty( $gpn3 ) || empty( $gpd3 ) ) {
				$price2 = wel_gp_price_discount( $price, $gpd2 );
				$html  .= '<li>';
				$html  .= sprintf( __( '<span class=%5$s>%1$s</span>%2$s par 1%3$s for more than %4$s%3$s', 'usces' ),
					$usces->get_currency( $price2, true, false ),
					$usces->getGuidTax(),
					esc_html( $unit ),
					$gpn2,
					"'price'"
				);
				if ( 0 < $tax_rate ) {
					$html .= usces_crform_the_itemGpExp_taxincluded( $price2, $tax_rate );
				}
				$html .= "</li>\n";
			} else {
				$price2 = wel_gp_price_discount( $price, $gpd2 );
				$html  .= '<li>';
				$html  .= sprintf( __( '<span class=%6$s>%1$s</span>%2$s par 1%3$s for %4$s-%5$s%3$s', 'usces' ),
					$usces->get_currency( $price2, true, false ),
					$usces->getGuidTax(),
					esc_html( $unit ),
					$gpn2,
					$gpn3 - 1,
					"'price'"
				);
				if ( 0 < $tax_rate ) {
					$html .= usces_crform_the_itemGpExp_taxincluded( $price2, $tax_rate );
				}
				$html  .= "</li>\n";
				$price3 = wel_gp_price_discount( $price, $gpd3 );
				$html  .= '<li>';
				$html  .= sprintf( __( '<span class=%5$s>%1$s</span>%2$s par 1%3$s for more than %4$s%3$s', 'usces' ),
					$usces->get_currency( $price3, true, false ),
					$usces->getGuidTax(),
					esc_html( $unit ),
					$gpn3,
					"'price'"
				);
				if ( 0 < $tax_rate ) {
					$html .= usces_crform_the_itemGpExp_taxincluded( $price3, $tax_rate );
				}
				$html .= "</li>\n";
			}
		}
	}
	$html .= '</ul></dd></dl>';

	$html = apply_filters( 'usces_filter_itemGpExp', $html );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Calculation of the discount based on the unit price per float.
 *
 * @param float $price Price.
 * @param int   $rate  Discount rate.
 * @return float
 */
function wel_gp_price_discount( $price, $rate ) {
	global $usces;

	$decimal = $usces->get_currency_decimal();
	if ( 0 === (int) $decimal ) {
		$discount = round( $price * ( 100 - $rate ) / 100 );
	} else {
		$discount = (float) sprintf( "%.{$decimal}f", (float) $price * ( 100 - $rate ) / 100 );
	}
	return $discount;
}

/**
 * Formatted tax included amount
 *
 * @param float  $price Price.
 * @param float  $tax_rate Tax rate.
 * @param bool   $label_pre Forward label.
 * @param string $label Label.
 * @param string $start_tag Start tag.
 * @param string $end_tag End tag.
 * @param bool   $symbol_pre Forward symbol.
 * @param bool   $symbol_post Backward symbol.
 * @param bool   $seperator_flag Seperator.
 * @return string
 */
function usces_crform_the_itemGpExp_taxincluded( $price, $tax_rate, $label_pre = true, $label = '', $start_tag = '', $end_tag = '', $symbol_pre = true, $symbol_post = false, $seperator_flag = true ) {
	global $usces;

	if ( ( isset( $usces->options['tax_display'] ) && 'deactivate' === $usces->options['tax_display'] ) || ( isset( $usces->options['tax_mode'] ) && 'include' === $usces->options['tax_mode'] ) ) {
		$res = '';
	} else {
		$tax         = (float) sprintf( '%.3f', (float) $price * (float) $tax_rate / 100 );
		$tax         = usces_tax_rounding_off( $tax );
		$price_gpexp = esc_html( $usces->get_currency( $price + $tax, $symbol_pre, $symbol_post, $seperator_flag ) );
		if ( empty( $label ) ) {
			$label_tag = '<em class="tax tax_inc_label">' . __( 'tax-included', 'usces' ) . '</em>';
		} else {
			$label_tag = '<em class="tax tax_inc_label">' . $label . '</em>';
		}
		if ( empty( $start_tag ) ) {
			$start_tag = '<span class="tax_inc_block">( ';
		}
		if ( $label_pre ) {
			$start_tag = $start_tag . $label_tag;
		}
		if ( empty( $end_tag ) ) {
			$end_tag = ' )</span>';
		}
		if ( true !== $label_pre ) {
			$end_tag = $label_tag . $end_tag;
		}
		$res = apply_filters( 'usces_filter_crform_the_itemGpExp_taxincluded', $start_tag . $price_gpexp . $end_tag, $price, $tax_rate, $label_pre, $label, $symbol_pre, $symbol_post, $seperator_flag );
	}
	return $res;
}

/**
 * Quantity field
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemQuant( $out = '' ) {
	global $usces, $post;

	$post_id = $post->ID;
	$sku     = urlencode( $usces->itemsku['code'] );
	$value   = isset( $_SESSION['usces_singleitem']['quant'][ $post_id ][ $sku ] ) ? $_SESSION['usces_singleitem']['quant'][ $post_id ][ $sku ] : 1;
	$quant   = "<input name=\"quant[{$post_id}][" . $sku . "]\" type=\"text\" id=\"quant[{$post_id}][" . $sku . "]\" class=\"skuquantity\" value=\"" . esc_attr( $value ) . "\" onKeyDown=\"if (event.keyCode == 13) {return false;}\" />";
	$html    = apply_filters( 'usces_filter_the_itemQuant', $quant, $post );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Add to Cart
 *
 * @param mixed  $value Value.
 * @param int    $type Submit or button.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemSkuButton( $value, $type = 0, $out = '' ) {
	global $usces, $post;

	$post_id      = (int) $post->ID;
	$zaikonum     = esc_attr( $usces->itemsku['stocknum'] );
	$zaiko_status = esc_attr( $usces->itemsku['stock'] );
	$gptekiyo     = esc_attr( $usces->itemsku['gp'] );
	$skuprice     = esc_attr( $usces->getItemPrice( $post_id, $usces->itemsku['code'] ) );
	$value        = esc_attr( apply_filters( 'usces_filter_incart_button_label', $value ) );
	$sku          = esc_attr( urlencode( $usces->itemsku['code'] ) );

	if ( 1 === (int) $type ) {
		$type = 'button';
	} else {
		$type = 'submit';
	}
	$html  = "<input name=\"zaikonum[{$post_id}][{$sku}]\" type=\"hidden\" id=\"zaikonum[{$post_id}][{$sku}]\" value=\"{$zaikonum}\" />\n";
	$html .= "<input name=\"zaiko[{$post_id}][{$sku}]\" type=\"hidden\" id=\"zaiko[{$post_id}][{$sku}]\" value=\"{$zaiko_status}\" />\n";
	$html .= "<input name=\"gptekiyo[{$post_id}][{$sku}]\" type=\"hidden\" id=\"gptekiyo[{$post_id}][{$sku}]\" value=\"{$gptekiyo}\" />\n";
	$html .= "<input name=\"skuPrice[{$post_id}][{$sku}]\" type=\"hidden\" id=\"skuPrice[{$post_id}][{$sku}]\" value=\"{$skuprice}\" />\n";
	if ( $usces->use_js ) {
		$html .= "<input name=\"inCart[{$post_id}][{$sku}]\" type=\"{$type}\" id=\"inCart[{$post_id}][{$sku}]\" class=\"skubutton\" value=\"{$value}\" onclick=\"return uscesCart.intoCart( '{$post_id}','{$sku}' )\" />";
	} else {
		$html .= "<a name=\"cart_button\"></a><input name=\"inCart[{$post_id}][{$sku}]\" type=\"{$type}\" id=\"inCart[{$post_id}][{$sku}]\" class=\"skubutton\" value=\"{$value}\" />";
	}
	$html .= "<input name=\"usces_referer\" type=\"hidden\" value=\"" . esc_url( $_SERVER['REQUEST_URI'] ) . "\" />\n";
	$html  = apply_filters( 'usces_filter_item_sku_button', $html, $value, $type );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Add to Cart ( direct mode )
 *
 * @param int    $post_id Post ID.
 * @param string $sku SKU code.
 * @param bool   $force Force.
 * @param mixed  $value Value.
 * @param bool   $options Have options.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_direct_intoCart( $post_id, $sku, $force = false, $value = null, $options = null, $out = '' ) {
	global $usces;

	if ( empty( $value ) ) {
		$value = __( 'Add To Cart', 'usces' );
	}
	$skus = wel_get_skus( $post_id, 'code' );

	$zaikonum = $skus[ $sku ]['stocknum'];
	$zaiko    = $skus[ $sku ]['stock'];
	$gptekiyo = $skus[ $sku ]['gp'];
	$skuprice = $skus[ $sku ]['price'];
	$enc_sku  = urlencode( $sku );

	$usces->itemopts        = wel_get_opts( $post_id, 'sort' );
	$usces->current_itemopt = -1;

	$usces->itemsku = $skus[ $sku ];

	$html  = "<form action=\"" . USCES_CART_URL . "\" method=\"post\" name=\"" . $post_id . '-' . $enc_sku . "\">\n";
	$html .= "<input name=\"zaikonum[{$post_id}][{$enc_sku}]\" type=\"hidden\" id=\"zaikonum[{$post_id}][{$enc_sku}]\" value=\"" . esc_attr( $zaikonum ) . "\" />\n";
	$html .= "<input name=\"zaiko[{$post_id}][{$enc_sku}]\" type=\"hidden\" id=\"zaiko[{$post_id}][{$enc_sku}]\" value=\"" . esc_attr( $zaiko ) . "\" />\n";
	$html .= "<input name=\"gptekiyo[{$post_id}][{$enc_sku}]\" type=\"hidden\" id=\"gptekiyo[{$post_id}][{$enc_sku}]\" value=\"" . esc_attr( $gptekiyo ) . "\" />\n";
	$html .= "<input name=\"skuPrice[{$post_id}][{$enc_sku}]\" type=\"hidden\" id=\"skuPrice[{$post_id}][{$enc_sku}]\" value=\"" . esc_attr( $skuprice ) . "\" />\n";

	if ( $options && usces_is_options() ) {
		while ( usces_have_options() ) {
			$html .= '<div class="itemopt_row">' . usces_get_itemopt_filed( $post_id, $sku, $usces->itemopt ) . "</div>\n";
		}
	}

	$html .= "<a name=\"cart_button\"></a><input name=\"inCart[{$post_id}][{$enc_sku}]\" type=\"submit\" id=\"inCart[{$post_id}][{$enc_sku}]\" class=\"skubutton\" value=\"" . esc_attr( $value ) . "\" " . apply_filters( 'usces_filter_direct_intocart_button', null, $post_id, $sku, $force, $options ) . ' />';
	$html .= "<input name=\"usces_referer\" type=\"hidden\" value=\"" . esc_url( $_SERVER['REQUEST_URI'] ) . "\" />\n";
	if ( $force ) {
		$html .= "<input name=\"usces_force\" type=\"hidden\" value=\"incart\" />\n";
	}
	$html = apply_filters( 'usces_filter_single_item_inform', $html );

	$html .= '</form>';
	$html .= '<div class="direct_error_message">' . usces_singleitem_error_message( $post_id, $sku, 'return' ) . '</div>' . "\n";

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Product Image
 *
 * @param int    $number Image number.
 * @param int    $width Width.
 * @param int    $height Height.
 * @param object $post Post data.
 * @param string $out Return value or echo.
 * @param string $media Media.
 * @return string|void
 */
function usces_the_itemImage( $number = 0, $width = 60, $height = 60, $post = '', $out = '', $media = 'item' ) {
	global $usces;

	if ( '' === $post ) {
		global $post;
	}
	$post_id = $post->ID;

	$ptitle = '';
	if ( is_string( $number ) ) {
		$ptitle = $number;
	}
	if ( $ptitle ) {

		$picposts = new WP_Query( array( 'post_type' => 'attachment', 'name' => $ptitle ) );
		$pictid   = ( $picposts->have_posts() ) ? $picposts[0]->ID : 0;
		$html     = wp_get_attachment_image( $pictid, array( $width, $height ), false );
		if ( 'item' === $media ) {
			$product = wel_get_product( $post_id );
			$code    = $product['itemCode'];
			if ( ! $code ) {
				return false;
			}
			$name = $product['itemName'];

			$alt = 'alt="' . esc_attr( $code ) . '"';
			$alt = apply_filters( 'usces_filter_img_alt', $alt, $post_id, $pictid, $width, $height );

			$html = preg_replace( '/alt=\"[^\"]*\"/', $alt, $html );

			$title = 'title="' . esc_attr( $name ) . '"';
			$title = apply_filters( 'usces_filter_img_title', $title, $post_id, $pictid, $width, $height );

			$html = preg_replace( '/title=\"[^\"]+\"/', $title, $html );
			$html = apply_filters( 'usces_filter_main_img', $html, $post_id, $pictid, $width, $height );
		}

	} else {

		$product = wel_get_product( $post_id );
		$code    = $product['itemCode'];
		if ( ! $code ) {
			return false;
		}
		$name = $product['itemName'];

		if ( 0 === (int) $number ) {

			$pictid = (int) $usces->get_mainpictid( $code );
			$html   = wp_get_attachment_image( $pictid, array( $width, $height ), true );/* '<img src="#" height="60" width="60" alt="" />'; */
			if ( 'item' === $media ) {
				$alt = 'alt="' . esc_attr( $code ) . '"';
				$alt = apply_filters( 'usces_filter_img_alt', $alt, $post_id, $pictid, $width, $height );

				$html = preg_replace( '/alt=\"[^\"]*\"/', $alt, $html );

				$title = 'title="' . esc_attr( $name ) . '"';
				$title = apply_filters( 'usces_filter_img_title', $title, $post_id, $pictid, $width, $height );

				$html = preg_replace( '/title=\"[^\"]+\"/', $title, $html );
				$html = apply_filters( 'usces_filter_main_img', $html, $post_id, $pictid, $width, $height );
			}

		} else {

			$pictids = $usces->get_pictids( $code );
			$ind     = $number - 1;
			$pictid  = ( isset( $pictids[ $ind ] ) && (int) $pictids[ $ind ] ) ? $pictids[ $ind ] : 0;
			$html    = wp_get_attachment_image( $pictid, array( $width, $height ), false );/* '<img src="#" height="60" width="60" alt="" />'; */
			if ( 'item' === $media ) {
				$alt = 'alt="' . esc_attr( $code ) . '"';
				$alt = apply_filters( 'usces_filter_img_alt', $alt, $post_id, $pictid, $width, $height );

				$html = preg_replace( '/alt=\"[^\"]*\"/', $alt, $html );

				$title = 'title="' . esc_attr( $name ) . '"';
				$title = apply_filters( 'usces_filter_img_title', $title, $post_id, $pictid, $width, $height );

				$html = preg_replace( '/title=\"[^\"]+\"/', $title, $html );
				$html = apply_filters( 'usces_filter_sub_img', $html, $post_id, $pictid, $width, $height );
			}
		}
	}

	if ( 'return' === $out ) {
		return wel_esc_script( $html );
	} else {
		wel_esc_script_e( $html );
	}
}

/**
 * Product Image URL
 *
 * @param int    $number Image number.
 * @param string $out Return value or echo.
 * @param object $post Post data.
 * @return string|void
 */
function usces_the_itemImageURL( $number = 0, $out = '', $post = '' ) {
	global $usces;

	$ptitle = $number;
	if ( $ptitle && is_string( $number ) ) {

		$picposts = new WP_Query( array( 'post_type' => 'attachment', 'name' => $ptitle ) );
		$pictid   = ( $picposts->have_posts() ) ? $picposts[0]->ID : 0;
		$html     = wp_get_attachment_url( $pictid );

	} else {

		if ( '' === $post ) {
			global $post;
		}
		$post_id = $post->ID;
		$product = wel_get_product( $post_id );
		$code    = $product['itemCode'];
		if ( ! $code ) {
			return false;
		}
		$name = $product['itemName'];
		if ( 0 === (int) $number ) {
			$pictid = (int) $usces->get_mainpictid( $code );
			$html   = wp_get_attachment_url( $pictid );
		} else {
			$pictids = $usces->get_pictids( $code );
			$ind     = $number - 1;
			$pictid  = ( isset( $pictids[ $ind ] ) && (int) $pictids[ $ind ] ) ? $pictids[ $ind ] : 0;
			$html    = wp_get_attachment_url( $pictid );
		}
	}

	if ( 'return' === $out ) {
		return wel_esc_script( $html );
	} else {
		wel_esc_script_e( $html );
	}
}

/**
 * Product Image caption
 *
 * @param int    $number Image number.
 * @param object $post Post data.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemImageCaption( $number = 0, $post = '', $out = '' ) {
	global $usces;

	$ptitle = $number;
	if ( $ptitle && 0 === (int) $number ) {

		$picposts = new WP_Query( array( 'post_type' => 'attachment', 'name' => $ptitle ) );
		$excerpt  = ( $picposts->have_posts() ) ? $picposts[0]->post_excerpt : '';

	} else {

		if ( '' === $post ) {
			global $post;
		}
		$post_id = $post->ID;
		$product = wel_get_product( $post_id );

		$code = $product['itemCode'];
		if ( ! $code ) {
			return false;
		}
		$name = $product['itemName'];

		if ( 0 === (int) $number ) {
			$pictid    = $usces->get_mainpictid( $code );
			$attach_ob = get_post( $pictid );
		} else {
			$pictids   = $usces->get_pictids( $code );
			$ind       = $number - 1;
			$attach_ob = get_post( $pictids[ $ind ] );
		}
		$excerpt = $attach_ob->post_excerpt;
	}

	if ( 'return' === $out ) {
		return $excerpt;
	} else {
		echo esc_html( $excerpt );
	}
}

/**
 * Product Image description
 *
 * @param int    $number Image number.
 * @param object $post Post data.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemImageDescription( $number = 0, $post = '', $out = '' ) {
	global $usces;

	$ptitle = $number;
	if ( $ptitle && 0 === (int) $number ) {

		$picposts = new WP_Query( array( 'post_type' => 'attachment', 'name' => $ptitle ) );
		$excerpt  = ( $picposts->have_posts() ) ? $picposts[0]->post_content : '';

	} else {

		if ( '' === $post ) {
			global $post;
		}
		$post_id = $post->ID;
		$product = wel_get_product( $post_id );

		$code = $product['itemCode'];
		if ( ! $code ) {
			return false;
		}
		$name = $product['itemName'];

		if ( 0 === (int) $number ) {
			$pictid    = $usces->get_mainpictid( $code );
			$attach_ob = get_post( $pictid );
		} else {
			$pictids   = $usces->get_pictids( $code );
			$ind       = $number - 1;
			$attach_ob = get_post( $pictids[ $ind ] );
		}
		$excerpt = $attach_ob->post_content;
	}

	if ( 'return' === $out ) {
		return $excerpt;
	} else {
		echo esc_html( $excerpt );
	}
}

/**
 * Product sub image
 *
 * @return array
 */
function usces_get_itemSubImageNums() {
	global $post, $usces;

	$post_id = $post->ID;
	$res     = array();

	$product = wel_get_product( $post_id );
	$code    = $product['itemCode'];
	if ( ! $code ) {
		return false;
	}
	$name = $product['itemName'];

	$pictids       = $usces->get_pictids( $code );
	$pictids_count = ( $pictids && is_array( $pictids ) ) ? count( $pictids ) : 0;
	for ( $i = 1; $i <= $pictids_count; $i++ ) {
		$res[] = $i;
	}
	return $res;
}

/**
 * Is item option
 *
 * @return bool
 */
function usces_is_options() {
	global $usces;

	if ( ! empty( $usces->itemopts ) && is_array( $usces->itemopts ) && 0 < count( $usces->itemopts ) ) {
		reset( $usces->itemopts );
		$usces->itemopt         = array();
		$usces->current_itemopt = -1;
		return true;
	} else {
		return false;
	}
}

/**
 * Have item option
 *
 * @return bool
 */
function usces_have_options() {
	global $usces;

	if ( null === $usces->current_itemopt ) {
		$usces->current_itemopt = -1;
	}

	if ( ! empty( $usces->itemopts ) && is_array( $usces->itemopts ) && ( $usces->current_itemopt + 1 < count( $usces->itemopts ) ) ) {
		$usces->current_itemopt++;
		$usces->itemopt = ( isset( $usces->itemopts[ $usces->current_itemopt ] ) ) ? $usces->itemopts[ $usces->current_itemopt ] : array();
		return true;
	} else {
		return false;
	}
}

/**
 * Item option name
 *
 * @return string
 */
function usces_getItemOptName() {
	global $usces;
	return $usces->itemopt['name'];
}

/**
 * Item option name
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemOptName( $out = '' ) {
	global $usces;

	if ( 'return' === $out ) {
		return $usces->itemopt['name'];
	} else {
		echo esc_html( $usces->itemopt['name'] );
	}
}

/**
 * Item option field
 *
 * @param string $name Option name.
 * @param string $label Label.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemOption( $name, $label = '#default#', $out = '' ) {
	global $post, $usces;
	$post_id = $post->ID;

	if ( '#default#' === $label ) {
		$label = $name;
	}

	$opts = wel_get_opts( $post_id, 'name' );
	if ( ! $opts ) {
		return false;
	}

	$opt          = $opts[ $name ];
	$opt['value'] = usces_change_line_break( $opt['value'] );
	$means        = (int) $opt['means'];
	$essential    = (int) $opt['essential'];

	$sku     = esc_attr( urlencode( $usces->itemsku['code'] ) );
	$optcode = esc_attr( urlencode( $name ) );
	$name    = esc_attr( $name );
	$label   = esc_attr( $label );

	$session_value = isset( $_SESSION['usces_singleitem']['itemOption'][ $post_id ][ $sku ][ $optcode ] ) ? $_SESSION['usces_singleitem']['itemOption'][ $post_id ][ $sku ][ $optcode ] : null;

	$html  = '';
	$html .= "\n<label for='itemOption[{$post_id}][{$sku}][{$optcode}]' class='iopt_label'>{$label}</label>\n";

	switch ( $means ) {
		case 0: // Single-select.
		case 1: // Multi-select.
			$selects        = explode( "\n", $opt['value'] );
			$multiple       = ( 0 === $means ) ? '' : ' multiple';
			$multiple_array = ( 0 === $means ) ? '' : '[]';

			$html .= "\n<select name='itemOption[{$post_id}][{$sku}][{$optcode}]{$multiple_array}' id='itemOption[{$post_id}][{$sku}][{$optcode}]' class='iopt_select'{$multiple} onKeyDown=\"if (event.keyCode == 13) {return false;}\">\n";
			if ( 1 === $essential ) {
				if ( 0 === $means && ( '#NONE#' === $session_value || null === $session_value ) ) {
					$selected = ' selected="selected"';
				} else {
					$selected = '';
				}
				$html .= "\t<option value='#NONE#'{$selected}>" . __( 'Choose', 'usces' ) . "</option>\n";
			}
			$i = 0;
			foreach ( (array) $selects as $v ) {
				$v = trim( $v );
				if ( ( 0 === $i && 0 === $essential && null === $session_value ) || esc_attr( $v ) === $session_value ) {
					$selected = ' selected="selected"';
				} else {
					$selected = '';
				}
				$html .= "\t<option value='" . esc_attr( $v ) . "'{$selected}>" . esc_attr( $v ) . "</option>\n";
				$i++;
			}
			$html .= "</select>\n";
			break;
		case 2: // Text.
			$html .= "\n<input name='itemOption[{$post_id}][{$sku}][{$optcode}]' type='text' id='itemOption[{$post_id}][{$sku}][{$optcode}]' class='iopt_text' onKeyDown=\"if (event.keyCode == 13) {return false;}\" value=\"" . esc_attr( $session_value ) . "\" />\n";
			break;
		case 3: // Radio-button.
			$selects = explode( "\n", $opt['value'] );
			$i       = 0;
			foreach ( (array) $selects as $v ) {
				$v = trim( $v );
				if ( $v === $session_value ) {
					$checked = ' checked="checked"';
				} else {
					$checked = '';
				}
				$html .= "\t<label for='itemOption[{$post_id}][{$sku}][{$optcode}]{$i}' class='iopt_radio_label'><input name='itemOption[{$post_id}][{$sku}][{$optcode}]' id='itemOption[{$post_id}][{$sku}][{$optcode}]{$i}' class='iopt_radio' type='radio' value='" . urlencode( $v ) . "'{$checked}>" . esc_html( $v ) . "</label>\n";
				$i++;
			}
			break;
		case 4: // Check-box.
			$selects = explode( "\n", $opt['value'] );
			$i       = 0;
			foreach ( (array) $selects as $v ) {
				$v = trim( $v );
				if ( $v === $session_value ) {
					$checked = ' checked="checked"';
				} else {
					$checked = '';
				}
				$html .= "\t<label for='itemOption[{$post_id}][{$sku}][{$optcode}]{$i}' class='iopt_checkbox_label'><input name='itemOption[{$post_id}][{$sku}][{$optcode}][]' id='itemOption[{$post_id}][{$sku}][{$optcode}]{$i}' class='iopt_checkbox' type='checkbox' value='" . urlencode( $v ) . "'{$checked}>" . esc_html( $v ) . "</label><br />\n";
				$i++;
			}
			break;
		case 5: // Text-area.
			$html .= "\n<textarea name='itemOption[{$post_id}][{$sku}][{$optcode}]' id='itemOption[{$post_id}][{$sku}][{$optcode}]' class='iopt_textarea'>" . esc_attr( $session_value ) . "</textarea>\n";
			break;
	}

	$html = apply_filters( 'usces_filter_the_itemOption', $html, $opts, $name, $label, $post_id, $usces->itemsku['code'] );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Display cart
 */
function usces_the_cart() {
	global $usces;
	$usces->display_cart();
}

/**
 * Is cart page
 *
 * @return bool
 */
function usces_is_cart_page() {
	global $usces;

	if ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
		if ( 'cart' === $usces->page ) {
			return true;
		}
		if ( 'customer' !== $usces->page && 'delivery' !== $usces->page && 'confirm' !== $usces->page && 'ordercompletion' !== $usces->page && 'error' !== $usces->page && 'search_item' !== $usces->page ) {
			return true;
		}
	}
	return false;
}

/**
 * Is cart
 *
 * @return bool
 */
function usces_is_cart() {
	global $usces;

	if ( 0 < $usces->cart->num_row() ) {
		if ( apply_filters( 'usces_is_cart_check', true ) ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Is category
 *
 * @param string $str categpry slug.
 * @return bool
 */
function usces_is_category( $str ) {

	$cat   = get_the_category();
	$slugs = array();
	foreach ( $cat as $value ) {
		$slugs[] = $value->slug;
	}

	$str = utf8_uri_encode( $str );

	if ( in_array( $str, $slugs, true ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Pref field
 *
 * @param string $flag Member or not.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_pref( $flag, $out = '' ) {
	global $usces;

	$usces_entries = $usces->cart->get_entry();
	$name          = esc_attr( $flag ) . '[pref]';
	$pref          = $usces_entries[ $flag ]['pref'];
	if ( 'member' === $flag ) {
		$usces_members = $usces->get_member();
		$pref          = $usces_members['pref'];
	}
	$html  = "<select name=\"" . esc_attr( $name ) . "\" id=\"pref\" class=\"pref\">\n";
	$prefs = get_usces_states( usces_get_local_addressform() );
	foreach ( $prefs as $value ) {
		$selected = ( $pref === $value ) ? ' selected="selected"' : '';
		$html    .= "\t<option value=\"" . esc_attr( $value ) . "\"{$selected}>" . esc_html( $value ) . "</option>\n";
	}
	$html .= "</select>\n";

	if ( 'return' === $out ) {
		return wel_esc_script( $html );
	} else {
		wel_esc_script_e( $html );
	}
}

/**
 * Company name
 */
function usces_the_company_name() {
	global $usces;
	echo esc_html( $usces->options['company_name'] );
}

/**
 * Zip code
 */
function usces_the_zip_code() {
	global $usces;
	echo esc_html( $usces->options['zip_code'] );
}

/**
 * Address1
 */
function usces_the_address1() {
	global $usces;
	echo esc_html( $usces->options['address1'] );
}

/**
 * Address2
 */
function usces_the_address2() {
	global $usces;
	echo esc_html( $usces->options['address2'] );
}

/**
 * Tel number
 */
function usces_the_tel_number() {
	global $usces;
	echo esc_html( $usces->options['tel_number'] );
}

/**
 * Fax number
 */
function usces_the_fax_number() {
	global $usces;
	echo esc_html( $usces->options['fax_number'] );
}

/**
 * Inquiry email address
 */
function usces_the_inquiry_mail() {
	global $usces;
	echo esc_html( $usces->options['inquiry_mail'] );
}

/**
 * Postage privilege
 */
function usces_the_postage_privilege() {
	global $usces;
	echo esc_html( $usces->options['postage_privilege'] );
}

/**
 * Initial point
 */
function usces_the_start_point() {
	global $usces;
	echo esc_html( $usces->options['start_point'] );
}

/**
 * Point rate
 *
 * @param int    $post_id Post ID.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_point_rate( $post_id = null, $out = '' ) {
	global $usces;

	if ( null === $post_id ) {
		$rate = $usces->options['point_rate'];
	} else {
		$product = wel_get_product( $post_id );
		$str     = $product['itemPointrate'];
		$rate    = (int) $str;
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $rate );
	} else {
		wel_esc_script_e( $rate );
	}
}

/**
 * Point discount
 *
 * @param int    $post_id Post ID.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_point_rate_discount( $post_id = null, $out = '' ) {
	global $post, $usces;

	if ( null === $post_id ) {
		$post_id = $post->ID;
	}

	$product = wel_get_product( $post_id );
	$str     = $product['itemPointrate'];
	$rate    = (int) $str;
	if ( 'point' === $usces->options['campaign_privilege'] ) {
		if ( in_category( (int) $usces->options['campaign_category'], $post_id ) ) {
			$rate *= $usces->options['privilege_point'];
		}
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $rate );
	} else {
		wel_esc_script_e( $rate );
	}
}

/**
 * Item point
 *
 * @param int    $post_id Post ID.
 * @param string $sku_code SKU code.
 * @return int
 */
function usces_get_point( $post_id, $sku_code ) {
	global $usces;

	if ( null === $post_id ) {
		$rate = $usces->options['point_rate'];
	} else {
		$product = wel_get_product( $post_id );
		$str     = $product['itemPointrate'];
		$rate    = (int) $str;
	}

	$skus  = wel_get_skus( $post_id, 'code' );
	$point = ceil( $skus[ $sku_code ]['price'] * $rate / 100 );

	return $point;
}

/**
 * Payment method field
 *
 * @param string $value Value.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_payment_method( $value = '', $out = '' ) {
	global $usces;

	$payments = usces_get_system_option( 'usces_payment_method', 'sort' );
	$payments = apply_filters( 'usces_fiter_the_payment_method', $payments, $value );

	if ( defined( 'WCEX_DLSELLER_VERSION' ) && version_compare( WCEX_DLSELLER_VERSION, '2.2-beta', '<=' ) ) {
		$cart                    = $usces->cart->get_cart();
		$have_continue_charge    = usces_have_continue_charge( $cart );
		$continue_payment_method = apply_filters( 'usces_filter_the_continue_payment_method', array( 'acting_remise_card', 'acting_paypal_ec' ) );
	}

	$list       = '';
	$payment_ct = ( $payments && is_array( $payments ) ) ? count( $payments ) : 0;
	foreach ( (array) $payments as $id => $payment ) {
		if ( defined( 'WCEX_DLSELLER_VERSION' ) && version_compare( WCEX_DLSELLER_VERSION, '2.2-beta', '<=' ) ) {
			if ( $have_continue_charge ) {
				if ( ! in_array( $payment['settlement'], $continue_payment_method, true ) ) {
					$payment_ct--;
					continue;
				}
				if ( isset( $usces->options['acting_settings']['remise']['continuation'] ) && 'on' !== $usces->options['acting_settings']['remise']['continuation'] && 'acting_remise_card' === $payment['settlement'] ) {
					$payment_ct--;
					continue;
				} elseif ( isset( $usces->options['acting_settings']['paypal']['continuation'] ) && 'on' !== $usces->options['acting_settings']['paypal']['continuation'] && 'acting_paypal_ec' === $payment['settlement'] ) {
					$payment_ct--;
					continue;
				}
			}
		}
		if ( '' !== $payment['name'] && 'deactivate' !== $payment['use'] ) {
			$module = trim( $payment['module'] );
			if ( ! WCUtils::is_blank( $value ) ) {
				$checked = ( $payment['name'] === $value ) ? ' checked' : '';
			} elseif ( 1 === $payment_ct ) {
				$checked = ' checked';
			} else {
				$checked = '';
			}
			$payment_row = '';
			$checked     = apply_filters( 'usces_fiter_the_payment_method_checked', $checked, $payment, $value );
			$explanation = apply_filters( 'usces_fiter_the_payment_method_explanation', $payment['explanation'], $payment, $value );
			if ( ( empty( $module ) || ! file_exists( $usces->options['settlement_path'] . $module ) ) && 'acting' === $payment['settlement'] ) {
				$checked      = '';
				$payment_row .= '<dt class="payment_' . $id . '"><label for="payment_name_' . $id . '"><input name="offer[payment_name]" id="payment_name_' . $id . '" type="radio" value="' . esc_attr( $payment['name'] ) . '"' . $checked . ' disabled onKeyDown="if (event.keyCode == 13) {return false;}" />' . esc_attr( $payment['name'] ) . '</label> <b>(' . __( 'cannot use this payment method now.', 'usces' ) . ')</b></dt>';
			} else {
				$payment_row .= '<dt class="payment_' . $id . '"><label for="payment_name_' . $id . '"><input name="offer[payment_name]" id="payment_name_' . $id . '" type="radio" value="' . esc_attr( $payment['name'] ) . '"' . $checked . ' onKeyDown="if (event.keyCode == 13) {return false;}" />' . esc_attr( $payment['name'] ) . '</label></dt>';
			}
			if ( ! empty( $explanation ) ) {
				$explanation = wel_esc_script( $explanation );
				$payment_row .= '<dd class="payment_' . $id . '">' . $explanation . '</dd>';
			}
			$payment_row = apply_filters( 'usces_filter_the_payment_method_row', $payment_row, $id, $payment, $checked, $module, $value, $explanation );
			if ( ! empty( $payment_row ) ) {
				$list .= $payment_row;
			}
		}
	}

	if ( ! empty( $list ) ) {
		$html = '<dl>' . $list . '</dl>';
	} else {
		$html = '<div>' . __( 'Not yet ready for the payment method. Please refer to a manager.', 'usces' ) . '</div>';
	}

	$html = apply_filters( 'usces_filter_the_payment_method_choices', $html, $payments );
	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Payment method
 *
 * @param string $name Payment method name.
 * @return array
 */
function usces_get_payments_by_name( $name ) {

	$init     = array(
		'id'          => null,
		'name'        => null,
		'explanation' => null,
		'settlement'  => null,
		'module'      => null,
		'sort'        => null,
		'use'         => null,
	);
	$payments = usces_get_system_option( 'usces_payment_method', 'name' );
	if ( empty( $payments ) ) {
		return $init;
	}
	if ( isset( $payments[ $name ] ) ) {
		return $payments[ $name ];
	}

	return $init;
}

/**
 * Delivery method field
 *
 * @param string $value Value.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_delivery_method( $value = '', $out = '' ) {
	global $usces;

	$deli_id = apply_filters( 'usces_filter_get_available_delivery_method', $usces->get_available_delivery_method() );
	if ( empty( $deli_id ) ) {
		$html = '<p>' . __( 'No valid shipping methods.', 'usces' ) . '</p>';
	} else {
		$cdeliid = ( is_array( $deli_id ) ) ? count( $deli_id ) : 0;
		$html    = '<select name="offer[delivery_method]" id="delivery_method_select" class="delivery_time" onKeyDown="if (event.keyCode == 13) {return false;}">' . "\n";
		foreach ( (array) $deli_id as $id ) {
			$index = $usces->get_delivery_method_index( $id );
			if ( 0 <= $index ) {
				$selected = ( (string) $id === (string) $value || 1 === $cdeliid ) ? ' selected="selected"' : '';
				$html    .= "\t<option value=\"{$id}\"{$selected}>" . esc_html( $usces->options['delivery_method'][ $index ]['name'] ) . "</option>\n";
			}
		}
		$html .= "</select>\n";
	}

	$html = apply_filters( 'usces_filter_the_delivery_method', $html, $deli_id );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Delivery date field
 *
 * @param string $value Value.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_delivery_date( $value = '', $out = '' ) {

	$html  = "<select name=\"offer[delivery_date]\" id=\"delivery_date_select\" class=\"delivery_date\">\n";
	$html .= "</select>\n";
	$html  = apply_filters( 'the_delivery_date', $html );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Delivery time field
 *
 * @param string $value Value.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_delivery_time( $value = '', $out = '' ) {

	$html  = "<div id=\"delivery_time_limit_message\"></div>\n";
	$html .= "<select name=\"offer[delivery_time]\" id=\"delivery_time_select\" class=\"delivery_time\">\n";
	$html .= "</select>\n";
	$html  = apply_filters( 'the_delivery_time', $html );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Campaign Schedule
 *
 * @param string $flag Flag.
 * @param string $kind Kind.
 */
function usces_the_campaign_schedule( $flag, $kind ) {
	global $usces;

	$startdate = $usces->options['campaign_schedule']['start']['year'] . __( 'year', 'usces' ) . $usces->options['campaign_schedule']['start']['month'] . __( 'month', 'usces' ) . $usces->options['campaign_schedule']['start']['day'] . __( 'day', 'usces' );
	$starttime = $usces->options['campaign_schedule']['start']['hour'] . __( 'hour', 'usces' ) . $usces->options['campaign_schedule']['start']['min'] . __( 'min', 'usces' );
	$enddate   = $usces->options['campaign_schedule']['end']['year'] . __( 'year', 'usces' ) . $usces->options['campaign_schedule']['end']['month'] . __( 'month', 'usces' ) . $usces->options['campaign_schedule']['end']['day'] . __( 'day', 'usces' );
	$endtime   = $usces->options['campaign_schedule']['end']['hour'] . __( 'hour', 'usces' ) . $usces->options['campaign_schedule']['end']['min'] . __( 'min', 'usces' );
	if ( 'start' === $flag ) {
		if ( 'date' === $kind ) {
			echo esc_html( $startdate );
		} elseif ( 'datetime' === $kind ) {
			echo esc_html( $startdate . ' ' . $starttime );
		}
	} elseif ( 'end' === $flag ) {
		if ( 'date' === $kind ) {
			echo esc_html( $enddate );
		} elseif ( 'datetime' === $kind ) {
			echo esc_html( $enddate . ' ' . $endtime );
		}
	}
}

/**
 * Display cart confirm
 */
function usces_the_confirm() {
	global $usces;
	$usces->display_cart_confirm();
}

/**
 * Inquiry
 */
function usces_inquiry_condition() {
	global $error_message, $reserve, $inq_name, $inq_mailaddress, $inq_contents;
	require USCES_PLUGIN_DIR . '/includes/inquiry_condition.php';
}

/**
 * Inquiry form
 */
function usces_the_inquiry_form() {
	global $usces;

	$error_message = '';
	if ( isset( $_POST['inq_name'] ) && ! WCUtils::is_blank( $_POST['inq_name'] ) ) {
		$inq_name = trim( wp_unslash( $_POST['inq_name'] ) );
	} else {
		$inq_name = '';
		if ( 'deficiency' === $usces->page ) {
			$error_message .= __( 'Please input your name.', 'usces' ) . '<br />';
		}
	}
	if ( isset( $_POST['inq_mailaddress'] ) && is_email( trim( wp_unslash( $_POST['inq_mailaddress'] ) ) ) ) {
		$inq_mailaddress = trim( wp_unslash( $_POST['inq_mailaddress'] ) );
	} elseif ( isset( $_POST['inq_mailaddress'] ) && ! is_email( trim( wp_unslash( $_POST['inq_mailaddress'] ) ) ) ) {
		$inq_mailaddress = trim( wp_unslash( $_POST['inq_mailaddress'] ) );
		if ( 'deficiency' === $usces->page ) {
			$error_message .= __( 'E-mail address is not correct', 'usces' ) . '<br />';
		}
	} else {
		$inq_mailaddress = '';
		if ( 'deficiency' === $usces->page ) {
			$error_message .= __( 'Please input your e-mail address.', 'usces' ) . '<br />';
		}
	}
	if ( isset( $_POST['inq_contents'] ) && ! WCUtils::is_blank( $_POST['inq_contents'] ) ) {
		$inq_contents = trim( wp_unslash( $_POST['inq_contents'] ) );
	} else {
		$inq_contents = '';
		if ( 'deficiency' === $usces->page ) {
			$error_message .= __( 'Please input contents.', 'usces' );
		}
	}

	if ( 'inquiry_comp' === $usces->page ) :
		$inq_message = apply_filters( 'usces_filter_inquiry_message_completion', __( 'I send a reply email to a visitor. I ask in a few minutes to be able to have you refer in there being the fear that e-mail address is different again when the email from this shop does not arrive.', 'usces' ) );
		?>
	<div class="inquiry_comp"><?php esc_html_e( 'sending completed', 'usces' ); ?></div>
	<div class="compbox"><?php wel_esc_script_e( $inq_message ); ?></div>
		<?php
	elseif ( 'inquiry_error' === $usces->page ) :
		?>
	<div class="inquiry_comp"><?php esc_html_e( 'Failure in sending', 'usces' ); ?></div>
		<?php
	else :
		?>
		<?php if ( ! empty( $error_message ) ) : ?>
<div class="error_message"><?php wel_esc_script_e( $error_message ); ?></div>
		<?php endif; ?>
<form name="inquiry_form" method="post">
<input type="hidden" name="kakuninyou" />
<table border="0" cellpadding="0" cellspacing="0" class="inquiry_table">
<tr>
<th scope="row"><?php esc_html_e( 'Full name', 'usces' ); ?></th>
<td><input name="inq_name" type="text" class="inquiry_name" value="<?php echo esc_attr( $inq_name ); ?>" /></td>
</tr>
<tr>
<th scope="row"><?php esc_html_e( 'e-mail adress', 'usces' ); ?></th>
<td><input name="inq_mailaddress" type="text" class="inquiry_mailaddress" value="<?php echo esc_attr( $inq_mailaddress ); ?>" /></td>
</tr>
<tr>
<th scope="row"><?php esc_html_e( 'contents', 'usces' ); ?></th>
<td><textarea name="inq_contents" class="inquiry_contents"><?php echo esc_attr( $inq_contents ); ?></textarea></td>
</tr>
</table>
<div class="send"><input name="inquiry_button" type="submit" value="<?php esc_attr_e( 'Admit to send it with this information.', 'usces' ); ?>" /></div>
</form>
		<?php
	endif;
}

/**
 * Get Category ID
 *
 * @param string $slug Slag.
 * @return int
 */
function usces_get_cat_id( $slug ) {
	$cat = get_category_by_slug( $slug );
	return $cat->term_id;
}

/**
 * Widget Calendar
 */
function usces_the_calendar() {
	global $usces;
	include USCES_PLUGIN_DIR . '/includes/widget_calendar.php';
}

/**
 * Login/Logout link
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_loginout( $out = '' ) {
	global $usces;

	if ( ! $usces->is_member_logged_in() ) {
		$res = '<a href="' . apply_filters( 'usces_filter_login_uri', USCES_LOGIN_URL ) . '" class="usces_login_a">' . apply_filters( 'usces_filter_loginlink_label', __( 'Log-in', 'usces' ) ) . '</a>';
	} else {
		$res = '<a href="' . apply_filters( 'usces_filter_logout_uri', USCES_LOGOUT_URL ) . '" class="usces_logout_a">' . apply_filters( 'usces_filter_logoutlink_label', __( 'Log out', 'usces' ) ) . '</a>';
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $res );
	} else {
		wel_esc_script_e( $res );
	}
}

/**
 * Is Login
 *
 * @return bool
 */
function usces_is_login() {
	global $usces;

	if ( false === $usces->is_member_logged_in() ) {
		$res = false;
	} else {
		$res = true;
	}
	return $res;
}

/**
 * Member Name
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_member_name( $out = '' ) {
	global $usces;

	$usces->get_current_member();
	$res = esc_html( $usces->current_member['name'] );
	if ( 'return' === $out ) {
		return wel_esc_script( $res );
	} else {
		wel_esc_script_e( $res );
	}
}

/**
 * Membership Point
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_member_point( $out = '' ) {
	global $usces;

	if ( ! $usces->is_member_logged_in() ) {
		return;
	}

	$member = $usces->get_member();

	if ( 'return' === $out ) {
		return $member['point'];
	} else {
		echo number_format( $member['point'] );
	}
}

/**
 * Membership Rank
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_member_status( $out = '' ) {
	global $usces;

	if ( ! $usces->is_member_logged_in() ) {
		return;
	}

	$usces->get_current_member();
	$member      = $usces->get_member_info( $usces->current_member['id'] );
	$status_name = $usces->member_status[ $member['mem_status'] ];

	if ( 'return' === $out ) {
		return $status_name;
	} else {
		echo esc_html( $status_name );
	}
}

/**
 * Related item list
 *
 * @param int $post_id Post ID.
 * @return array
 */
function usces_get_assistance_id_list( $post_id ) {
	global $usces;

	$names = $usces->get_tag_names( $post_id );
	$list  = '';
	foreach ( (array) $names as $itemname ) {
		$list .= $usces->get_ID_byItemName( $itemname, 'publish' ) . ',';
	}
	$list = trim( $list, ',' );
	return $list;
}

/**
 * Related item
 *
 * @param int $post_id Post ID.
 * @return array
 */
function usces_get_assistance_ids( $post_id ) {
	global $usces;

	$names = $usces->get_tag_names( $post_id );
	$ids   = array();
	foreach ( $names as $itemname ) {
		$ids[] = $usces->get_ID_byItemName( $itemname, 'publish' );
	}
	return $ids;
}

/**
 * Forgot account
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_remembername( $out = '' ) {
	global $usces;

	$value = $usces->get_cookie();
	if ( 'return' === $out ) {
//		if ( isset( $value['name'] ) )
//			return $value['name'];
//		else
			return '';
	} else {
//		if ( isset( $value['name'] ) )
//			echo esc_html( $value['name'] );
//		else
			echo '';
	}
}

/**
 * Forgot password
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_rememberpass( $out = '' ) {
	global $usces;

	$value = $usces->get_cookie();
	if ( 'return' === $out ) {
//		if ( isset( $value['pass'] ) )
//			return $value['pass'];
//		else
			return '';
	} else {
//		if ( isset( $value['pass'] ) )
//			echo esc_html( $value['pass'] );
//		else
			echo '';
	}
}

/**
 * Forgot account check
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_remembercheck( $out = '' ) {
	global $usces;

	$value = $usces->get_cookie();
	if ( 'return' === $out ) {
//		if ( isset( $value['name'] ) && $value['name'] != '' )
//			return ' checked="checked"';
//		else
			return '';
	} else {
//		if ( isset( $value['name'] ) && $value['name'] != '' )
//			echo ' checked="checked"';
//		else
			echo '';
	}
}

/**
 * Shipping charge table
 *
 * @param string $index Index.
 */
function usces_shippingchargeTR( $index = '' ) {
	global $usces;

	if ( '' === $index ) {
		$index = 0;
	}
	$list = '';
	if ( ! isset( $usces->options['shipping_charge'][ $index ] ) ) {
		return;
	}
	$shipping_charge = $usces->options['shipping_charge'][ $index ];
	$entry           = $usces->cart->get_entry();
	$country         = ( isset( $entry['delivery']['country'] ) && ! empty( $entry['delivery']['country'] ) ) ? $entry['delivery']['country'] : $entry['customer']['country'];
	foreach ( $shipping_charge[ $country ] as $pref => $value ) {
		$list .= '<tr><th>' . esc_html( $pref ) . "</th>\n";
		$list .= '<td class="rightnum">' . number_format( $value ) . "</td>\n";
		$list .= "</tr>\n";
	}
	wel_esc_script_e( $list );
}

/**
 * Shipping charge
 */
function usces_sc_shipping_charge() {
	global $usces;
	echo esc_html( $usces->sc_shipping_charge() );
}

/**
 * Postage privilege
 */
function usces_sc_postage_privilege() {
	global $usces;
	echo esc_html( $usces->sc_postage_privilege() );
}

/**
 * Payment title
 */
function usces_sc_payment_title() {
	global $usces;
	wel_esc_script_e( $usces->sc_payment_title() );
}

/**
 * Posts offset
 *
 * @param object $posts Post data.
 * @return int
 */
function usces_posts_random_offset( $posts ) {
	$ids = array();
	foreach ( (array) $posts as $post ) {
		$ids[] = $post->ID;
	}
	$ct    = count( $ids );
	$index = rand( 0, ( $ct - 1 ) );
	return $index;
}

/**
 * Get category link
 *
 * @param string $slug Slug.
 */
function usces_get_category_link_by_slug( $slug ) {
	$category = get_category_by_slug( $slug );
	echo get_category_link( $category->term_id );
}

/**
 * Get page ID
 *
 * @param string $post_name Post name.
 * @param string $return Return value or echo.
 * @return string|void
 */
function usces_get_page_ID_by_pname( $post_name, $return = 'echo' ) {
	$page = get_page_by_path( $post_name );
	if ( 'return' === $return ) {
		return $page->ID;
	} else {
		echo esc_attr( $page->ID );
	}
}

/**
 * Bestseller
 *
 * @param int    $num Number.
 * @param string $days Days.
 */
function usces_list_bestseller( $num, $days = '' ) {
	global $usces;

	$ids = $usces->get_bestseller_ids( $days );
	$htm = '';
	for ( $i = 0; $i < $num; $i++ ) {
		if ( isset( $ids[ $i ] ) ) {
			$post_id = (int) $ids[ $i ];
			$product = wel_get_product( $post_id );
			$post    = $product['_pst'];

			if ( false === $product ) {
				continue;
			}

			$disp_text = apply_filters( 'usces_widget_bestseller_auto_text', esc_html( $post->post_title ), $post_id );
			$list      = '<li><a href="' . get_permalink( $post_id ) . '">' . $disp_text . "</a></li>\n";
			$htm      .= apply_filters( 'usces_filter_bestseller', $list, $post_id, $i );
		}
	}
	wp_reset_postdata();
	wel_esc_script_e( $htm );
}

/**
 * Post list
 *
 * @param string $slug Slug.
 * @param string $rownum Page number.
 * @param string $widget_id Widget ID.
 */
function usces_list_post( $slug, $rownum, $widget_id = null ) {
	global $post;

	usces_remove_filter();

	$li       = '';
	$infolist = new WP_Query( array( 'category_name' => $slug, 'post_status' => 'publish', 'posts_per_page' => $rownum, 'order' => 'DESC', 'orderby' => 'date' ) );
	if ( null !== $widget_id && $infolist->have_posts() ) {
		remove_filter( 'excerpt_length', 'welcart_excerpt_length' );
		remove_filter( 'excerpt_mblength', 'welcart_excerpt_mblength' );
		remove_filter( 'excerpt_more', 'welcart_auto_excerpt_more' );
		if ( function_exists( 'welcart_widget_post_excerpt_length_' . $widget_id ) ) {
			add_filter( 'excerpt_length', 'welcart_widget_post_excerpt_length_' . $widget_id );
		}
		if ( function_exists( 'welcart_widget_post_excerpt_mblength_' . $widget_id ) ) {
			add_filter( 'excerpt_mblength', 'welcart_widget_post_excerpt_mblength_' . $widget_id );
		}
	}
	$list_index = 0;
	while ( $infolist->have_posts() ) {
		$infolist->the_post();
		$list  = '<li class="post_list' . apply_filters( 'usces_filter_post_list_class', null, $list_index, $infolist->post_count ) . '">' . "\n";
		$list .= "<div class='title'><a href='" . get_permalink( $post->ID ) . "'>" . get_the_title() . "</a></div>\n";
		$list .= '<p>' . get_the_excerpt() . "</p>\n";
		$list .= "</li>\n";
		$li   .= apply_filters( 'usces_filter_widget_post', $list, $post, $slug, $list_index );
		$list_index++;
	}
	wp_reset_postdata();
	usces_reset_filter();
	if ( null !== $widget_id && $infolist->have_posts() ) {
		add_filter( 'excerpt_length', 'welcart_excerpt_length' );
		add_filter( 'excerpt_mblength', 'welcart_excerpt_mblength' );
		add_filter( 'excerpt_more', 'welcart_auto_excerpt_more' );
	}
	wel_esc_script_e( $li );
}

/**
 * Category checkbox
 *
 * @param string $output Return value or echo.
 * @return string|void
 */
function usces_categories_checkbox( $output = '' ) {

	$retcats   = apply_filters( 'usces_search_retcats', usces_search_categories() );
	$parent_id = apply_filters( 'usces_search_categories_checkbox_parent', USCES_ITEM_CAT_PARENT_ID );
	$htm       = usces_get_categories_checkbox( $parent_id );
	$htm       = apply_filters( 'usces_filter_categories_checkbox', $htm, $parent_id );

	if ( '' === $output || 'echo' === $output ) {
		wel_esc_script_e( $htm );
	} else {
		return wel_esc_script( $htm );
	}
}

/**
 * Category checkbox
 *
 * @param int $parent_id Parent category ID.
 */
function usces_get_categories_checkbox( $parent_id ) {
	global $usces;

	$htm        = '';
	$retcats    = usces_search_categories();
	$parent_cat = get_category( $parent_id );
	$categories = get_categories( 'parent=' . $parent_id . '&hide_empty=0&orderby=' . $usces->options['fukugo_category_orderby'] . '&order=' . $usces->options['fukugo_category_order'] );
	$htm       .= '<fieldset class="catfield-' . $parent_cat->term_id . '"><legend>' . $parent_cat->cat_name . "</legend><ul>\n";
	foreach ( $categories as $cat ) {
		$children = get_categories( 'parent=' . $cat->term_id . '&hide_empty=0' );
		if ( 0 === count( $children ) ) {
			$checked = in_array( $cat->term_id, $retcats ) ? ' checked="checked"' : '';
			$htm    .= '<li><input name="category[' . $cat->term_id . ']" type="checkbox" id="category[' . $cat->term_id . ']" value="' . $cat->term_id . '"' . $checked . ' /><label for="category[' . $cat->term_id . ']" class="catlabel-' . $cat->term_id . '">' . esc_html( $cat->cat_name ) . "</label></li>\n";
		}
	}
	$htm .= "</ul>\n";
	foreach ( $categories as $cat ) {
		$children = get_categories( 'parent=' . $cat->term_id . '&hide_empty=0' );
		if ( 0 < count( $children ) ) {
			$htm .= usces_get_categories_checkbox( $cat->term_id );
		}
	}
	$htm .= "</fieldset>\n";

	return $htm;
}

/**
 * Search categories
 *
 * @return array
 */
function usces_search_categories() {
	$cats = array();
	if ( isset( $_REQUEST['category'] ) ) {
		$cats = wp_unslash( $_REQUEST['category'] );
	} else {
		$cats[] = USCES_ITEM_CAT_PARENT_ID;
	}
	sort( $cats );
	return $cats;
}

/**
 * Delivery method name
 *
 * @param int    $id Delivery ID.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_delivery_method_name( $id, $out = '' ) {
	global $usces;

	$id = $usces->get_delivery_method_index( $id );
	if ( $id > -1 ) {
		$name = $usces->options['delivery_method'][ $id ]['name'];
	} else {
		$name = __( 'No preference', 'usces' );
	}

	if ( 'return' === $out ) {
		return $name;
	} else {
		echo esc_html( $name );
	}
}

/**
 * Use membership system
 *
 * @return bool
 */
function usces_is_membersystem_state() {
	global $usces;

	if ( 'activate' === $usces->options['membersystem_state'] ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Use membership point
 *
 * @return bool
 */
function usces_is_membersystem_point() {
	global $usces;

	if ( 'activate' === $usces->options['membersystem_point'] ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Copyright
 */
function usces_copyright() {
	global $usces;
	echo esc_html( $usces->options['copyright'] );
}

/**
 * Total amount in cart
 */
function usces_totalprice_in_cart() {
	global $usces;
	echo number_format( $usces->get_total_price() );
}

/**
 * Total quantities in cart
 */
function usces_totalquantity_in_cart() {
	global $usces;
	echo number_format( $usces->get_total_quantity() );
}

/**
 * Page mode
 *
 * @return string
 */
function usces_get_page_mode() {
	global $usces;
	return $usces->page;
}

/**
 * Is in item category
 *
 * @param int $cat_id Category ID.
 * @return bool
 */
function usces_is_cat_of_item( $cat_id ) {
	global $usces;

	$ids   = $usces->get_item_cat_ids();
	$ids[] = USCES_ITEM_CAT_PARENT_ID;
	if ( in_array( $cat_id, $ids ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Item custom field
 *
 * @param int    $post_id Post ID.
 * @param string $type Type.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_get_item_custom( $post_id, $type = 'list', $out = '' ) {
	global $usces;

	$cfields = wel_get_extra_data( $post_id );
	$html    = '';

	switch ( $type ) {
		case 'list':
			$list = '';
			$html = '<ul class="item_custom_field">' . "\n";
			foreach ( $cfields as $key => $values ) {
				if ( 'wccs_' === substr( $key, 0, 5 ) ) {
					if ( is_array( $values ) ) {
						foreach ( $values as $value ) {
							$list .= '<li>' . esc_html( substr( $key, 5 ) ) . ' : ' . nl2br( esc_html( $value ) ) . '</li>' . "\n";
						}
					} else {
						$list .= '<li>' . esc_html( substr( $key, 5 ) ) . ' : ' . nl2br( esc_html( $values ) ) . '</li>' . "\n";
					}
				}
			}
			if ( empty( $list ) ) {
				$html = '';
			} else {
				$html .= $list . '</ul>' . "\n";
			}
			break;

		case 'table':
			$list = '';
			$html = '<table class="item_custom_field">' . "\n";
			foreach ( $cfields as $key => $values ) {
				if ( 'wccs_' === substr( $key, 0, 5 ) ) {
					if ( is_array( $values ) ) {
						foreach ( $values as $value ) {
							$list .= '<tr><th>' . esc_html( substr( $key, 5 ) ) . '</th><td>' . nl2br( esc_html( $value ) ) . '</td></tr>' . "\n";
						}
					} else {
						$list .= '<tr><th>' . esc_html( substr( $key, 5 ) ) . '</th><td>' . nl2br( esc_html( $values ) ) . '</td></tr>' . "\n";
					}
				}
			}
			if ( empty( $list ) ) {
				$html = '';
			} else {
				$html .= $list . '</table>' . "\n";
			}
			break;

		case 'notag':
			$list = '';
			foreach ( $cfields as $key => $values ) {
				if ( 'wccs_' === substr( $key, 0, 5 ) ) {
					if ( is_array( $values ) ) {
						foreach ( $values as $value ) {
							$list .= esc_html( substr( $key, 5 ) ) . ' : ' . nl2br( esc_html( $value ) ) . "\r\n";
						}
					} else {
						$list .= esc_html( substr( $key, 5 ) ) . ' : ' . nl2br( esc_html( $values ) ) . "\r\n";
					}
				}
			}
			if ( empty( $list ) ) {
				$html = '';
			} else {
				$html = $list;
			}
			break;

		case 'mail_html':
			$list = '';
			foreach ( $cfields as $key => $values ) {
				if ( 'wccs_' === substr( $key, 0, 5 ) ) {
					if ( is_array( $values ) ) {
						foreach ( $values as $value ) {
							$list .= '<tr>
							<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . esc_html( substr( $key, 5 ) ) . '</td>
							<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . nl2br( esc_html( $value ) ) . '</td>
							</tr>';
						}
					} else {
						$list .= '<tr>
						<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . esc_html( substr( $key, 5 ) ) . '</td>
						<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . nl2br( esc_html( $values ) ) . '</td>
						</tr>';
					}
				}
			}
			if ( empty( $list ) ) {
				$html = '';
			} else {
				$html  = '<table style="font-size: 14px; width: 100%; border-collapse: collapse;">';
				$html .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
				$html .= '<table style="width: 100%;"><tbody>';
				$html .= $list;
				$html .= '</tbody></table></td></tr></tbody></table>';
			}
			break;
	}
	$html = apply_filters( 'usces_filter_item_custom', $html, $post_id, $type );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Settlement information
 *
 * @param int    $order_id Order ID.
 * @param string $type Type.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_settle_info_field( $order_id, $type = 'nl', $out = 'echo' ) {
	global $usces;

	$str    = '';
	$fields = $usces->get_settle_info_field( $order_id );
	$acting = isset( $fields['acting'] ) ? $fields['acting'] : '';
	$keys   = array(
		'acting',
		'order_no',
		'tracking_no',
		'status',
		'error_message',
		'money',
		'pay_cvs',
		'pay_no1',
		'pay_no2',
		'pay_limit',
		'error_code',
		'settlement_id',
		'RECDATE',
		'JOB_ID',
		'S_TORIHIKI_NO',
		'TOTAL',
		'CENDATE',
		'gid',
		'rst',
		'ap',
		'ec',
		'god',
		'ta',
		'cv',
		'no',
		'cu',
		'mf',
		'nk',
		'nkd',
		'bank',
		'exp',
		'txn_id',
		'order_number',
		'res_tracking_id',
		'res_payment_date',
		'res_payinfo_key',
		'settltment_status',
		'settltment_errmsg',
		'stran',
		'mbtran',
		'bktrans',
		'tranid',
		'TransactionId',
		'mStatus',
		'vResultCode',
		'orderId',
		'cvsType',
		'receiptNo',
		'receiptDate',
		'rcvAmount',
		'trading_id',
		'payment_type',
		'seq_payment_id',
		'sendpoint',
		'option',
		'LINK_KEY',
	);
	$keys   = apply_filters( 'usces_filter_settle_info_field_keys', $keys, $fields );
	foreach ( $fields as $key => $value ) {
		if ( ! in_array( $key, $keys, true ) ) {
			continue;
		}

		if ( 'jpayment_conv' === $acting ) {
			if ( 'rst' === $key ) {
				if ( '1' === $value ) {
					$value = 'OK';
				} elseif ( '2' === $value ) {
					$value = 'NG';
				}
			} elseif ( 'ap' === $key ) {
				if ( 'CPL_PRE' === $value ) {
					$value = 'コンビニペーパーレス決済識別コード';
				} elseif ( 'CPL' === $value ) {
					$value = '入金確定';
				} elseif ( 'CVS_CAN' === $value ) {
					$value = '入金取消';
				}
			} elseif ( 'cv' === $key ) {
				$value = esc_html( usces_get_conv_name( $value ) );
			} else {
				continue;
			}
		} elseif ( 'jpayment_bank' === $acting ) {
			if ( 'rst' === $key ) {
				if ( '1' === $value ) {
					$value = 'OK';
				} elseif ( '2' === $value ) {
					$value = 'NG';
				}
			} elseif ( 'ap' === $key ) {
				if ( 'BANK' === $value ) {
					$value = '受付完了';
				} elseif ( 'BAN_SAL' === $value ) {
					$value = '入金完了';
				}
			} elseif ( 'mf' === $key ) {
				if ( '1' === $value ) {
					$value = 'マッチ';
				} elseif ( '2' === $value ) {
					$value = '過少';
				} elseif ( '3' === $value ) {
					$value = '過剰';
				}
			} elseif ( 'nkd' === $key ) {
				$value = substr( $value, 0, 4 ) . '年' . substr( $value, 4, 2 ) . '月' . substr( $value, 6, 2 ) . '日';
			} elseif ( 'exp' === $key ) {
				$value = substr( $value, 0, 4 ) . '年' . substr( $value, 4, 2 ) . '月' . substr( $value, 6, 2 ) . '日';
			} else {
				continue;
			}
		} elseif ( 'veritrans_conv' === $acting ) {
			if ( 'cvsType' === $key ) {
				switch ( $value ) {
					case 'sej':
						$value = 'セブン－イレブン';
						break;
					case 'econ-lw':
						$value = 'ローソン';
						break;
					case 'econ-fm':
						$value = 'ファミリーマート';
						break;
					case 'econ-mini':
						$value = 'ミニストップ';
						break;
					case 'econ-other':
						$value = 'セイコーマート';
						break;
					case 'econ-sn':
						$value = 'サンクス';
						break;
					case 'econ-ck':
						$value = 'サークルK';
						break;
				}
			}
		}
		$value = apply_filters( 'usces_filter_settle_info_field_value', $value, $key, $acting );
		switch ( $type ) {
			case 'nl':
				$str .= $key . ' : ' . $value . "<br />\n";
				break;
			case 'tr':
				$str .= '<tr><td class="label">' . $key . '</td><td>' . $value . "</td></tr>\n";
				break;
			case 'li':
				$str .= '<li>' . $key . ' : ' . $value . "</li>\n";
				break;
		}
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $str );
	} else {
		wel_esc_script_e( $str );
	}
}

/**
 * Custom field
 *
 * @param array  $data Order data.
 * @param string $custom_field Custom field.
 * @param string $position Position.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_custom_field_input( $data, $custom_field, $position, $out = '' ) {

	$html = '';

	switch ( $custom_field ) {
		case 'order':
			$label = 'custom_order';
			$field = 'usces_custom_order_field';
			break;
		case 'customer':
			$label = 'custom_customer';
			$field = 'usces_custom_customer_field';
			break;
		case 'delivery':
			$label = 'custom_delivery';
			$field = 'usces_custom_delivery_field';
			break;
		case 'member':
			$label = 'custom_member';
			$field = 'usces_custom_member_field';
			break;
		default:
			return;
	}

	$meta = usces_has_custom_field_meta( $custom_field );

	if ( ! empty( $meta ) && is_array( $meta ) ) {
		foreach ( $meta as $key => $entry ) {
			if ( 'order' === $custom_field || $entry['position'] === $position ) {
				$name      = $entry['name'];
				$means     = (int) $entry['means'];
				$essential = (int) $entry['essential'];
				$value     = '';
				if ( is_array( $entry['value'] ) ) {
					foreach ( $entry['value'] as $k => $v ) {
						$value .= $v . "\n";
					}
				}
				$value = usces_change_line_break( $value );

				$e     = ( 1 === $essential ) ? '<em>' . __( '*', 'usces' ) . '</em>' : '';
				$html .= '
					<tr class="customkey_' . $key . '">
					<th scope="row">' . $e . esc_html( $name ) . apply_filters( 'usces_filter_custom_field_input_label', null, $key, $entry ) . '</th>';
				$html .= apply_filters( 'usces_filter_custom_field_input_td', '<td colspan="2">', $key, $entry );
				switch ( $means ) {
					case 0: // Single-select.
					case 1: // Multi-select.
						$selects        = explode( "\n", $value );
						$multiple       = ( 0 === $means ) ? '' : ' multiple';
						$multiple_array = ( 0 === $means ) ? '' : '[]';
						$html          .= '<select name="' . $label . '[' . esc_attr( $key ) . ']' . $multiple_array . '" class="iopt_select"' . $multiple . '>';
						if ( 1 === $essential ) {
							$html .= '
								<option value="#NONE#">' . __( 'Choose', 'usces' ) . '</option>';
						}
						foreach ( (array) $selects as $v ) {
							$selected = ( isset( $data[ $label ][ $key ] ) && $data[ $label ][ $key ] == $v ) ? ' selected' : '';
							$html    .= '
								<option value="' . esc_attr( $v ) . '"' . $selected . '>' . esc_html( $v ) . '</option>';
						}
						$html .= '
							</select>';
						break;
					case 2: // Text.
						$text  = isset( $data[ $label ][ $key ] ) ? $data[ $label ][ $key ] : '';
						$html .= '<input type="text" name="' . $label . '[' . esc_attr( $key ) . ']" class="iopt_text" value="' . esc_attr( $text ) . '" />';
						break;
					case 3: // Radio-button.
						$selects = explode( "\n", $value );
						foreach ( (array) $selects as $v ) {
							$checked = ( isset( $data[ $label ][ $key ] ) && $data[ $label ][ $key ] == $v ) ? ' checked' : '';
							$html   .= '
							<label for="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" class="iopt_label"><input type="radio" name="' . $label . '[' . esc_attr( $key ) . ']" id="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" value="' . esc_attr( $v ) . '"' . $checked . '>' . esc_html( $v ) . '</label>';
						}
						break;
					case 4: // Check-box.
						$selects = explode( "\n", $value );
						foreach ( $selects as $v ) {
							if ( isset( $data[ $label ][ $key ] ) && is_array( $data[ $label ][ $key ] ) ) {
								$checked = ( isset( $data[ $label ][ $key ] ) && array_key_exists( $v, $data[ $label ][ $key ] ) ) ? ' checked' : '';
							} else {
								$checked = ( isset( $data[ $label ][ $key ] ) && $data[ $label ][ $key ] == $v ) ? ' checked' : '';
							}
							$html .= '
							<label for="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" class="iopt_label"><input type="checkbox" name="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" id="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" value="' . esc_attr( $v ) . '"' . $checked . '>' . esc_html( $v ) . '</label>';
						}
						break;
					case 5: // Text-area.
						$text  = ( isset( $data[ $label ][ $key ] ) ) ? $data[ $label ][ $key ] : '';
						$html .= '<textarea name="' . $label . '[' . esc_attr( $key ) . ']" class="iopt_textarea">' . esc_attr( $text ) . '</textarea>';
						break;
				}
				$html .= apply_filters( 'usces_filter_custom_field_input_value', null, $key, $entry ) . '</td>';
				$html .= '
					</tr>';
			}
		}
	}

	$html = apply_filters( 'usces_filter_custom_field_input', $html, $data, $custom_field, $position );

	if ( 'return' === $out ) {
		return stripslashes( $html );
	} else {
		echo stripslashes( $html );
	}
}

/**
 * Custom field information
 *
 * @param array  $data Order data.
 * @param string $custom_field Custom field.
 * @param string $position Position.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_custom_field_info( $data, $custom_field, $position, $out = '' ) {

	$html = '';
	switch ( $custom_field ) {
		case 'order':
			$label = 'custom_order';
			$field = 'usces_custom_order_field';
			break;
		case 'customer':
			$label = 'custom_customer';
			$field = 'usces_custom_customer_field';
			break;
		case 'delivery':
			$label = 'custom_delivery';
			$field = 'usces_custom_delivery_field';
			break;
		case 'member':
			$label = 'custom_member';
			$field = 'usces_custom_member_field';
			break;
		default:
			return;
	}

	$meta = usces_has_custom_field_meta( $custom_field );

	if ( ! empty( $meta ) && is_array( $meta ) ) {
		foreach ( $meta as $key => $entry ) {
			if ( 'order' === $custom_field || $entry['position'] === $position ) {
				$name  = $entry['name'];
				$means = (int) $entry['means'];

				$html .= '<tr>
					<th>' . esc_html( $name ) . '</th>
					<td>';
				if ( ! empty( $data[ $label ][ $key ] ) ) {
					switch ( $means ) {
						case 0: // Single-select.
						case 2: // Text.
						case 3: // Radio-button.
						case 5: // Text-area.
							$html .= esc_html( $data[ $label ][ $key ] );
							break;
						case 1: // Multi-select.
						case 4: // Check-box.
							if ( is_array( $data[ $label ][ $key ] ) ) {
								$c = '';
								foreach ( $data[ $label ][ $key ] as $v ) {
									$html .= $c . esc_html( $v );
									$c     = ', ';
								}
							} else {
								if ( ! empty( $data[ $label ][ $key ] ) ) {
									$html .= esc_html( $data[ $label ][ $key ] );
								}
							}
							break;
					}
				}
				$html .= '
					</td>
					</tr>';
			}
		}
	}

	$html = apply_filters( 'usces_filter_custom_field_info', $html, $data, $custom_field, $position );

	if ( 'return' === $out ) {
		return stripslashes( $html );
	} else {
		echo stripslashes( $html );
	}
}

/**
 * Admin Custom field
 *
 * @param array  $meta Order meta data.
 * @param string $custom_field Custom field.
 * @param string $position Position.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_admin_custom_field_input( $meta, $custom_field, $position, $out = '' ) {

	$html = '';
	switch ( $custom_field ) {
		case 'order':
			$label = 'custom_order';
			$class = '';
			break;
		case 'customer':
			$label = 'custom_customer';
			$class = ' class="col2"';
			break;
		case 'delivery':
			$label = 'custom_delivery';
			$class = ' class="col3"';
			break;
		case 'member':
			$label = 'custom_member';
			$class = '';
			break;
		case 'admin_member':
			$label = 'admin_custom_member';
			$class = '';
			break;
		default:
			return;
	}

	if ( ! empty( $meta ) && is_array( $meta ) ) {
		foreach ( $meta as $key => $entry ) {
			if ( 'order' === $custom_field || $entry['position'] === $position ) {
				$name      = $entry['name'];
				$means     = (int) $entry['means'];
				$essential = (int) $entry['essential'];
				$value     = '';
				if ( is_array( $entry['value'] ) ) {
					foreach ( $entry['value'] as $k => $v ) {
						$value .= $v . "\n";
					}
				}
				$value = usces_change_line_break( $value );
				$value = apply_filters( 'usces_filter_admin_custom_field_input_value', $value, $key, $entry, $custom_field );
				$data  = ( isset( $entry['data'] ) ) ? $entry['data'] : null;

				$html .= '
					<tr>
					<td class="label">' . esc_html( $name ) . '</td>';
				switch ( $means ) {
					case 0: /* Single-select */
					case 1: /* Multi-select */
						$selects        = explode( "\n", $value );
						$multiple       = ( 0 === $means ) ? '' : ' multiple';
						$multiple_array = ( 0 === $means ) ? '' : '[]';
						$html          .= '
							<td' . $class . '>
							<select name="' . $label . '[' . esc_attr( $key ) . ']' . $multiple_array . '" id="' . $label . '[' . esc_attr( $key ) . ']" class="iopt_select"' . $multiple . '>';
						if ( 1 === $essential ) {
							$html .= '
								<option value="#NONE#">' . __( 'Choose', 'usces' ) . '</option>';
						}
						foreach ( $selects as $v ) {
							$selected = ( $data == $v ) ? ' selected' : '';
							$html    .= '
								<option value="' . esc_attr( $v ) . '"' . $selected . '>' . esc_html( $v ) . '</option>';
						}
						$html .= '
							</select></td>';
						break;
					case 2: /* Text */
						$html .= '
							<td' . $class . '><input type="text" name="' . $label . '[' . esc_attr( $key ) . ']" id="' . $label . '[' . esc_attr( $key ) . ']" size="30" value="' . esc_attr( $data ) . '" /></td>';
						break;
					case 3: /* Radio-button */
						$selects = explode( "\n", $value );
						$html   .= '
							<td' . $class . '>';
						foreach ( $selects as $v ) {
							$checked = ( $data == $v ) ? ' checked' : '';
							$html   .= '
							<input type="radio" name="' . $label . '[' . esc_attr( $key ) . ']" id="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" value="' . esc_attr( $v ) . '"' . $checked . '><label for="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" class="iopt_label">' . esc_html( $v ) . '</label>';
						}
						$html .= '
							</td>';
						break;
					case 4: /* Check-box */
						$selects = explode( "\n", $value );
						$html   .= '
							<td' . $class . '>';
						foreach ( $selects as $v ) {
							if ( is_array( $data ) ) {
								$checked = ( array_key_exists( $v, $data ) ) ? ' checked' : '';
							} else {
								$checked = ( $data == $v ) ? ' checked' : '';
							}
							$html .= '
							<input type="checkbox" name="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" id="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" value="' . esc_attr( $v ) . '"' . $checked . '><label for="' . $label . '[' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" class="iopt_label">' . esc_html( $v ) . '</label>';
						}
						$html .= '
							</td>';
						break;
					case 5: /* Text-area */
						$html .= '
							<td' . $class . '><textarea name="' . $label . '[' . esc_attr( $key ) . ']" id="' . $label . '[' . esc_attr( $key ) . ']" >' . esc_attr( $data ) . '</textarea></td>';
						break;
				}
				$html .= '
					</tr>';
			}
		}
	}
	$html = apply_filters( 'usces_filter_admin_custom_field_input', $html, $meta, $custom_field, $position, $out );

	if ( 'return' === $out ) {
		return stripslashes( $html );
	} else {
		echo stripslashes( $html );
	}
}

/**
 * Required Custom Field
 *
 * @return string
 */
function has_custom_customer_field_essential() {

	$mes       = '';
	$essential = array();

	$csmb_meta = usces_has_custom_field_meta( 'member' );
	if ( ! empty( $csmb_meta ) && is_array( $csmb_meta ) ) {
		foreach ( $csmb_meta as $key => $entry ) {
			if ( 1 === (int) $entry['essential'] ) {
				$essential[ $key ] = $key;
			}
		}
	}
	if ( ! empty( $essential ) ) {
		$cscs_meta = usces_has_custom_field_meta( 'customer' );
		if ( ! empty( $cscs_meta ) && is_array( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 1 === (int) $entry['essential'] ) {
					if ( ! array_key_exists( $key, $essential ) ) {
						if ( 2 === (int) $entry['means'] ) { // Text.
							$mes .= sprintf( __( "Input the %s", 'usces' ), esc_html( $entry['name'] ) ) . '<br />';
						} else {
							$mes .= sprintf( __( "Chose the %s", 'usces' ), esc_html( $entry['name'] ) ) . '<br />';
						}
					}
				}
			}
		}
	}
	return $mes;
}

/**
 * Discount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_order_discount( $out = '' ) {
	global $usces;

	$res = abs( $usces->get_order_discount() );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo number_format( $res );
	}
}

/**
 * Item discount
 *
 * @param string $out Return value or echo.
 * @param int    $post_id Post ID.
 * @param string $sku SKU code.
 * @return string|void
 */
function usces_item_discount( $out = '', $post_id = '', $sku = '' ) {
	global $usces, $post;

	if ( '' === $post_id ) {
		$post_id = $post->ID;
	}
	if ( '' === $sku ) {
		$sku = $usces->itemsku['code'];
	}
	$res = $usces->getItemDiscount( $post_id, $sku );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo number_format( $res );
	}
}

/**
 * Item page error message
 *
 * @param int    $post_id Post ID.
 * @param string $skukey SKU code.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_singleitem_error_message( $post_id, $skukey, $out = '' ) {
	if ( ! isset( $_SESSION['usces_singleitem']['error_message'][ $post_id ][ $skukey ] ) ) {
		$ret = '';
	} else {
		$ret = $_SESSION['usces_singleitem']['error_message'][ $post_id ][ $skukey ];
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $ret );
	} else {
		wel_esc_script_e( $ret );
	}
}

/**
 * Amount Formatting
 *
 * @param float  $float Price.
 * @param bool   $symbol_pre Forward symbol.
 * @param bool   $symbol_post Backward symbol.
 * @param string $out Return value or echo.
 * @param bool   $seperator_flag Seperator.
 * @return string|void
 */
function usces_crform( $float, $symbol_pre = true, $symbol_post = true, $out = '', $seperator_flag = true ) {
	global $usces;

	$price = esc_html( $usces->get_currency( $float, $symbol_pre, $symbol_post, $seperator_flag ) );
	$res   = apply_filters( 'usces_filter_crform', $price, $float );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo $res; // no escape due to filter.
	}
}

/**
 * Member Information
 *
 * @param string $key Key.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_memberinfo( $key, $out = '' ) {
	global $usces, $wpdb;

	$info = $usces->get_member();

	if ( empty( $key ) ) {
		return $info;
	}

	switch ( $key ) {
		case 'registered':
			$res = mysql2date( __( 'Mj, Y', 'usces' ), $info['registered'] );
			break;
		case 'point':
			$member_table = usces_get_tablename( 'usces_member' );
			$query        = $wpdb->prepare( "SELECT mem_point FROM $member_table WHERE ID = %d", $info['ID'] );
			$res          = $wpdb->get_var( $query );
			break;
		default:
			$res = isset( $info[ $key ] ) ? $info[ $key ] : '';
	}

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo esc_html( $res );
	}
}

/**
 * Member Purchase History
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_member_history( $out = '' ) {
	global $usces;

	$usces_members        = $usces->get_member();
	$history              = $usces->get_member_history( $usces_members['ID'], true );
	$usces_member_history = apply_filters( 'usces_filter_get_member_history', $history, $usces_members['ID'] );

	$usces_member_history_count = ( $usces_member_history && is_array( $usces_member_history ) ) ? count( $usces_member_history ) : 0;

	$html  = usces_load_filter_purchase_date();
	$html .= '<div class="history-area">';
	if ( 0 === $usces_member_history_count ) {
		$html .= '<table id="history_head"><tr>
		<td>' . __( 'There is no purchase history for this moment.', 'usces' ) . '</td>
		</tr></table>';
	} else {
		$management_status = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
		foreach ( $usces_member_history as $umhs ) {
			$condition           = $umhs['condition'];
			$tax_display         = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
			$tax_mode            = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : usces_get_tax_mode();
			$tax_target          = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : usces_get_tax_target();
			$tax_label           = ( 'exclude' === $tax_mode ) ? __( 'consumption tax', 'usces' ) : __( 'Internal tax', 'usces' );
			$member_system_point = ( isset( $condition['membersystem_point'] ) && 'activate' === $condition['membersystem_point'] ) ? true : usces_is_membersystem_point();
			$point_coverage      = ( isset( $condition['point_coverage'] ) ) ? (int) $condition['point_coverage'] : (int) usces_point_coverage();
			$cart                = $umhs['cart'];
			$value               = $umhs['order_status'];

			$p_status = '';
			if ( $usces->is_status( 'duringorder', $value ) ) {
				$p_status = isset( $management_status['duringorder'] ) ? esc_html( $management_status['duringorder'] ) : '';
			} elseif ( $usces->is_status( 'cancel', $value ) ) {
				$p_status = isset( $management_status['cancel'] ) ? esc_html( $management_status['cancel'] ) : '';
			} elseif ( $usces->is_status( 'completion', $value ) ) {
				$p_status = isset( $management_status['completion'] ) ? esc_html( $management_status['completion'] ) : '';
			} else {
				$p_status = esc_html( __( 'new order', 'usces' ) );
			}
			$p_status = apply_filters( 'usces_filter_orderlist_process_status', $p_status, $value, $management_status, $umhs['ID'] );

			$r_status = '';
			if ( $usces->is_status( 'noreceipt', $value ) ) {
				$r_status = isset( $management_status['noreceipt'] ) ? esc_html( $management_status['noreceipt'] ) : '';
			} elseif ( $usces->is_status( 'pending', $value ) ) {
				$r_status = isset( $management_status['pending'] ) ? esc_html( $management_status['pending'] ) : '';
			} elseif ( $usces->is_status( 'receipted', $value ) ) {
				$r_status = isset( $management_status['receipted'] ) ? esc_html( $management_status['receipted'] ) : '';
			}
			$r_status = apply_filters( 'usces_filter_orderlist_receipt_status', $r_status, $value, $management_status, $umhs['ID'] );

			$shipping             = usces_have_shipped( $cart );
			$delivery_company     = '';
			$tracking_number      = '';
			$delivery_company_url = '';
			if ( $shipping ) {
				$tracking_number      = $usces->get_order_meta_value( apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number' ), $umhs['ID'] );
				$delivery_company     = $usces->get_order_meta_value( 'delivery_company', $umhs['ID'] );
				$delivery_company_url = usces_get_delivery_company_url( $delivery_company, $tracking_number );
			}

			$history_member_head = '<table id="history_head"><thead>
				<tr class="order_head_label">
				<th class="historyrow order_number">' . __( 'Order number', 'usces' ) . '</th>
				<th class="historyrow purchase_date">' . __( 'Purchase date', 'usces' ) . '</th>';
			if ( ! empty( $p_status ) ) {
				$history_member_head .= '<th class="historyrow processing_status">' . __( 'Processing status', 'usces' ) . '</th>';
			}
			if ( ! empty( $r_status ) ) {
				$history_member_head .= '<th class="historyrow transfer_statement">' . __( 'transfer statement', 'usces' ) . '</th>';
			}
			$history_member_head .= '<th class="historyrow purchase_price">' . __( 'Purchase price', 'usces' ) . '</th>
				<th class="historyrow discount">' . apply_filters( 'usces_member_discount_label', __( 'Discount', 'usces' ), $umhs['ID'] ) . '</th>';
			if ( $tax_display && 'products' === $tax_target ) {
				$history_member_head .= '<th class="historyrow tax">' . $tax_label . '</th>';
			}
			if ( $member_system_point && 0 === $point_coverage ) {
				$history_member_head .= '<th class="historyrow used_point">' . __( 'Used points', 'usces' ) . '</th>';
			}
			$history_member_head .= '<th class="historyrow shipping">' . __( 'Shipping', 'usces' ) . '</th>
				<th class="historyrow cod">' . apply_filters( 'usces_filter_member_history_cod_label', __( 'C.O.D', 'usces' ), $umhs['ID'] ) . '</th>';
			if ( $tax_display && 'all' === $tax_target ) {
				$history_member_head .= '<th class="historyrow tax">' . $tax_label . '</th>';
			}
			if ( $member_system_point && 1 === $point_coverage ) {
				$history_member_head .= '<th class="historyrow used_point">' . __( 'Used points', 'usces' ) . '</th>';
			}
			if ( $member_system_point ) {
				$history_member_head .= '<th class="historyrow get_point">' . __( 'Acquired points', 'usces' ) . '</th>';
			}
			if ( ! empty( $tracking_number ) ) {
				$history_member_head .= '<th class="historyrow">' . __( 'Tracking number', 'usces' ) . '</th>';
			}
			$total_price = $umhs['total_items_price'] - $umhs['usedpoint'] + $umhs['discount'] + $umhs['shipping_charge'] + $umhs['cod_fee'] + $umhs['tax'];
			if ( $total_price < 0 ) {
				$total_price = 0;
			}
			$history_member_head .= '</tr></thead>
				<tbody>
				<tr class="order_head_value">
				<td class="order_number" data-label="' . __( 'Order number', 'usces' ) . '">' . usces_get_deco_order_id( $umhs['ID'] ) . '</td>
				<td class="date purchase_date" data-label="' . __( 'Purchase date', 'usces' ) . '">' . $umhs['date'] . '</td>';
			if ( ! empty( $p_status ) ) {
				$history_member_head .= '<td class="rightnum" data-label="' . __( 'Processing status', 'usces' ) . '">' . $p_status . '</td>';
			}
			if ( ! empty( $r_status ) ) {
				$history_member_head .= '<td class="rightnum" data-label="' . __( 'transfer statement', 'usces' ) . '">' . $r_status . '</td>';
			}
			$history_member_head .= '<td class="rightnum purchase_price" data-label="' . __( 'Purchase price', 'usces' ) . '">' . usces_crform( $total_price, true, false, 'return' ) . '</td>
				<td class="rightnum discount" data-label="' . apply_filters( 'usces_filter_discount_label', __( 'Discount', 'usces' ) ) . '">' . usces_crform( $umhs['discount'], true, false, 'return' ) . '</td>';
			if ( $tax_display && 'products' === $tax_target ) {
				$history_member_head .= '<td class="rightnum tax" data-label="' . $tax_label . '">' . usces_order_history_tax( $umhs, $tax_mode ) . '</td>';
			}
			if ( $member_system_point && 0 === $point_coverage ) {
				$history_member_head .= '<td class="rightnum used_point" data-label="' . __( 'Used points', 'usces' ) . '">' . number_format( $umhs['usedpoint'] ) . '</td>';
			}
			$history_member_head .= '<td class="rightnum shipping" data-label="' . __( 'Shipping', 'usces' ) . '">' . usces_crform( $umhs['shipping_charge'], true, false, 'return' ) . '</td>
				<td class="rightnum cod" data-label="' . apply_filters( 'usces_filter_cod_label', __( 'C.O.D', 'usces' ) ) . '">' . usces_crform( $umhs['cod_fee'], true, false, 'return' ) . '</td>';
			if ( $tax_display && 'all' === $tax_target ) {
				$history_member_head .= '<td class="rightnum tax" data-label="' . $tax_label . '">' . usces_order_history_tax( $umhs, $tax_mode ) . '</td>';
			}
			if ( $member_system_point && 1 === $point_coverage ) {
				$history_member_head .= '<td class="rightnum used_point" data-label="' . __( 'Used points', 'usces' ) . '">' . number_format( $umhs['usedpoint'] ) . '</td>';
			}
			if ( $member_system_point ) {
				$history_member_head .= '<td class="rightnum get_point" data-label="' . __( 'Acquired points', 'usces' ) . '">' . number_format( $umhs['getpoint'] ) . '</td>';
			}
			if ( ! empty( $tracking_number ) ) {
				if ( ! empty( $delivery_company_url ) ) {
					$history_member_head .= '<td data-label="' . __( 'Tracking number', 'usces' ) . '"><a href="' . esc_url( $delivery_company_url ) . '">' . esc_attr( $tracking_number ) . '</a></td>';
				} else {
					$history_member_head .= '<td data-label="' . __( 'Tracking number', 'usces' ) . '">' . esc_attr( $tracking_number ) . '</td>';
				}
			}
			$history_member_head .= '</tr>';
			$html                .= apply_filters( 'usces_filter_history_member_head', $history_member_head, $umhs );
			$html                .= apply_filters( 'usces_filter_member_history_header', null, $umhs );
			$html                .= '</tbody></table>
					<table id="retail_table_' . $umhs['ID'] . '" class="retail">';
			$history_cart_head    = '<thead><tr>
					<th scope="row" class="cartrownum">No.</th>
					<th class="thumbnail">&nbsp;</th>
					<th class="productname">' . __( 'Items', 'usces' ) . '</th>
					<th class="price">' . __( 'Unit price', 'usces' ) . '</th>
					<th class="quantity">' . __( 'Quantity', 'usces' ) . '</th>
					<th class="subtotal">' . __( 'Amount', 'usces' ) . '</th>
					</tr></thead><tbody>';
			$html                .= apply_filters( 'usces_filter_history_cart_head', $history_cart_head, $umhs );
			$cart_count           = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
			for ( $i = 0; $i < $cart_count; $i++ ) {
				$cart_row     = $cart[ $i ];
				$ordercart_id = $cart_row['cart_id'];
				$post_id      = $cart_row['post_id'];
				$sku          = $cart_row['sku'];
				$sku_code     = urldecode( $cart_row['sku'] );
				$quantity     = $cart_row['quantity'];
				$options      = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
				$itemCode     = $cart_row['item_code'];
				$itemName     = $cart_row['item_name'];
				$cartItemName = $usces->getCartItemName_byOrder( $cart_row );
				$skuPrice     = $cart_row['price'];
				$pictid       = (int) $usces->get_mainpictid( $itemCode );
				$optstr       = '';
				if ( is_array( $options ) && count( $options ) > 0 ) {
					foreach ( $options as $key => $value ) {
						if ( ! empty( $key ) ) {
							$key   = urldecode( $key );
							$value = maybe_unserialize( $value );
							if ( is_array( $value ) ) {
								$c       = '';
								$optstr .= esc_html( $key ) . ' : ';
								foreach ( $value as $v ) {
									$optstr .= $c . nl2br( esc_html( rawurldecode( $v ) ) );
									$c       = ', ';
								}
								$optstr .= "<br />\n";
							} else {
								$optstr .= esc_html( $key ) . ' : ' . nl2br( esc_html( rawurldecode( $value ) ) ) . "<br />\n";
							}
						}
					}
					$optstr = apply_filters( 'usces_filter_option_history', $optstr, $options );
				}
				$optstr = apply_filters( 'usces_filter_option_info_history', $optstr, $umhs, $cart_row, $i );
				$args   = compact( 'cart', 'i', 'cart_row', 'post_id', 'sku' );

				$cart_item_name = '<a href="' . get_permalink( $post_id ) . '">' . apply_filters( 'usces_filter_cart_item_name', esc_html( $cartItemName ), $args ) . '<br />' . $optstr . '</a>' . apply_filters( 'usces_filter_history_item_name', null, $umhs, $cart_row, $i );
				$cart_item_name = apply_filters( 'usces_filter_history_cart_item_name', $cart_item_name, $cartItemName, $optstr, $cart_row, $i, $umhs );

				$history_cart_row  = '<tr>
					<td class="cartrownum">' . ( $i + 1 ) . '</td>
					<td class="thumbnail">';
				$cart_thumbnail    = '<a href="' . get_permalink( $post_id ) . '">' . wp_get_attachment_image( $pictid, array( 60, 60 ), true ) . '</a>';
				$history_cart_row .= apply_filters( 'usces_filter_cart_thumbnail', $cart_thumbnail, $post_id, $pictid, $i, $cart_row );
				$history_cart_row .= '</td>
					<td class="aleft productname" data-label="' . __( 'Items', 'usces' ) . '">' . $cart_item_name . '</td>
					<td class="rightnum price" data-label="' . __( 'Unit price', 'usces' ) . '">' . usces_crform( $skuPrice, true, false, 'return' ) . '</td>
					<td class="rightnum quantity" data-label="' . __( 'Quantity', 'usces' ) . '">' . number_format( $cart_row['quantity'] ) . '</td>
					<td class="rightnum subtotal" data-label="' . __( 'Amount', 'usces' ) . '">' . usces_crform( $skuPrice * $cart_row['quantity'], true, false, 'return' ) . '</td>
					</tr>';
				$materials         = compact( 'cart_thumbnail', 'post_id', 'pictid', 'cartItemName', 'optstr' );
				$html             .= apply_filters( 'usces_filter_history_cart_row', $history_cart_row, $umhs, $cart_row, $i, $materials );
			}
			$html .= '</tbody></table>';
			$html .= apply_filters( 'usces_filter_member_history_row', '', $umhs, $cart );
		}
	}
	$html .= '</div>';
	$html  = apply_filters( 'usces_filter_member_history', $html, $usces_member_history );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo $html; // no escape due to filter.
	}
}

/**
 * Load select list filter purchase history.
 */
function usces_load_filter_purchase_date() {
	global $usces;

	$pur_date     = $usces->usces_get_member_cookies( 'pur-date' );
	$current_year = (int) gmdate( 'Y' );
	$year_filter  = 11;
	$arr_filter = array(
		''     => __( 'All of period', 'usces' ),
		'm_0'  => __( 'This month', 'usces' ),
		'm_1'  => __( 'Previous month', 'usces' ),
		'm_2'  => __( 'Two months ago', 'usces' ),
		'd_30' => __( 'Last 30 days', 'usces' ),
		'd_60' => __( 'Last 60 days', 'usces' ),
	);
	for ( $i = 0; $i < $year_filter; $i++ ) {
		$year                       = $current_year - $i;
		$arr_filter[ 'y_' . $year ] = $year . __( 'year', 'usces' );
	}
	$exclude_cancel = $usces->usces_get_member_cookies( 'ord_ex_cancel' );
	$add_ex_args    = array(
		'ord_ex_cancel' => ( 'on' === (string) $exclude_cancel ) ? 'off' : 'on',
		'pur-date'      => $pur_date,
	);
	$url_ex_filter = add_query_arg( $add_ex_args, USCES_MEMBER_URL ) . '#usces_history';
	$html          = '<div id="usces_history" class="usces_filter_history">';
	$content       = '<div class="exclude_cancel">';
	$content      .= '<label for="ord_exclude_cancel">';
	$content      .= '<input type="checkbox" id="ord_exclude_cancel" name="ord_exclude_cancel" onchange="memberOrderHistory.onChangeFilter(this);" value="' . esc_url( $url_ex_filter ) . '" ' . checked( $exclude_cancel, 'on', false ) . ' />';
	$content      .= __( 'Exclude cancellations', 'usces' ) . '</label>';
	$content      .= '</div>';
	$content      .= '<div class="usce_period">';
	$content      .= '<span>' . __( 'Period', 'usces' ) . '</span>';
	$content      .= '<select name="usces_purdate" id="usces_purdate" onchange="memberOrderHistory.onChangeFilter(this);">';
	foreach ( $arr_filter as $key => $name ) {
		$selected = '';
		$add_args = array(
			'pur-date' => $key,
		);
		if ( (string) $key === (string) $pur_date ) {
			$selected = 'selected';
		}
		$url_filter = add_query_arg( $add_args, USCES_MEMBER_URL ) . '#usces_history';
		$content .= '<option cvalue="' . $key . '" value="' . esc_url( $url_filter ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $name ) . '</option>';
	}
	$content .= '</select>';
	$content .= '</div>';
	$html    .= apply_filters( 'usces_filter_member_history_top_navi', $content );
	$html    .= '</div>';
	return $html;
}

/**
 * New registration button
 *
 * @param string $member_regmode Registration mode.
 */
function usces_newmember_button( $member_regmode ) {
	$html            = '<input name="member_regmode" type="hidden" value="' . $member_regmode . '" />';
	$newmemberbutton = '<input name="regmember" type="submit" value="' . __( 'transmit a message', 'usces' ) . '" />';
	$html           .= apply_filters( 'usces_filter_newmember_button', $newmemberbutton );
	wel_esc_script_e( $html );
}

/**
 * Login button
 */
function usces_login_button() {
	$loginbutton = '<input type="submit" name="member_login" id="member_login" class="member_login_button" value="' . __( 'Log-in', 'usces' ) . '" />';
	$html        = apply_filters( 'usces_filter_login_button', $loginbutton );
	wel_esc_script_e( $html );
}

/**
 * Related item
 *
 * @param int    $post_id Post ID.
 * @param string $title Title.
 */
function usces_assistance_item( $post_id, $title ) {
	if ( usces_get_assistance_id_list( $post_id ) ) :
		global $post;
		$r = new WP_Query( array( 'post__in' => usces_get_assistance_ids( $post_id ), 'ignore_sticky_posts' => 1 ) );
		if ( $r->have_posts() ) :
			add_filter( 'excerpt_length', 'welcart_assistance_excerpt_length' );
			add_filter( 'excerpt_mblength', 'welcart_assistance_excerpt_mblength' );
			$width  = apply_filters( 'usces_filter_assistance_item_width', 100 );
			$height = apply_filters( 'usces_filter_assistance_item_height', 100 );
			?>
	<div class="assistance_item">
		<h3><?php wel_esc_script_e( $title ); ?></h3>
		<ul class="clearfix">
			<?php
			while ( $r->have_posts() ) :
				$r->the_post();
				usces_remove_filter();
				usces_the_item();
				ob_start();
				?>
			<li>
			<div class="listbox clearfix">
				<div class="slit">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo wp_filter_nohtml_kses( get_the_title() ); ?>"><?php usces_the_itemImage( 0, $width, $height, $post ); ?></a>
				</div>
				<div class="detail">
					<div class="assist_excerpt">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo wp_filter_nohtml_kses( get_the_title() ); ?>"><h4><?php usces_the_itemName(); ?></h4></a>
					<?php the_excerpt(); ?>
					</div>
				<?php if ( usces_is_skus() ) : ?>
					<div class="assist_price">
						<?php usces_crform( usces_the_firstPrice( 'return' ), true, false ); ?>
					</div>
					<?php usces_crform_the_itemPriceCr_taxincluded(); ?>
				<?php endif; ?>
				</div>
			</div>
			</li>
				<?php
				$list = ob_get_contents();
				ob_end_clean();
				echo apply_filters( 'usces_filter_assistance_item_list', $list, $post );
			endwhile;
			?>
		</ul>
	</div><!-- end of assistance_item -->
			<?php
			wp_reset_postdata();
			usces_reset_filter();
			remove_filter( 'excerpt_length', 'welcart_assistance_excerpt_length' );
			remove_filter( 'excerpt_mblength', 'welcart_assistance_excerpt_mblength' );
		endif;
	endif;
}

/**
 * Cart Item Details
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_get_cart_rows( $out = '' ) {
	global $usces, $usces_gp;

	$cart       = $usces->cart->get_cart();
	$usces_gp   = 0;
	$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
	$res        = '';

	for ( $i = 0; $i < $cart_count; $i++ ) {
		$cart_row            = $cart[ $i ];
		$post_id             = (int) $cart_row['post_id'];
		$sku                 = $cart_row['sku'];
		$sku_code            = urldecode( $cart_row['sku'] );
		$quantity            = $cart_row['quantity'];
		$options             = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		$advance             = ( ! empty( $cart_row['advance'] ) ) ? $cart_row['advance'] : '';
		$itemCode            = $usces->getItemCode( $post_id );
		$itemName            = $usces->getItemName( $post_id );
		$cartItemName        = $usces->getCartItemName( $post_id, $sku_code );
		$itemRestriction     = $usces->getItemRestriction( $post_id );
		$itemOrderAcceptable = $usces->getItemOrderAcceptable( $post_id );
		$skuPrice            = $cart_row['price'];
		$skuZaikonum         = $usces->getItemZaikonum( $post_id, $sku_code );
		$stockid             = $usces->getItemZaikoStatusId( $post_id, $sku_code );
		$stock               = $usces->getItemZaiko( $post_id, $sku_code );
		$red                 = ( 1 < $stockid ) ? 'class="signal_red stock"' : 'class="stock"';
		$pictid              = (int) $usces->get_mainpictid( $itemCode );
		$args                = compact( 'cart', 'i', 'cart_row', 'post_id', 'sku' );

		$row            = '';
		$row           .= '<tr>
			<td class="num">' . ( $i + 1 ) . '</td>
			<td class="thumbnail">';
		$cart_thumbnail = '<a href="' . get_permalink( $post_id ) . '">' . wp_get_attachment_image( $pictid, array( 60, 60 ), true ) . '</a>';
		$row           .= apply_filters( 'usces_filter_cart_thumbnail', $cart_thumbnail, $post_id, $pictid, $i, $cart_row );
		$row           .= '</td>
		<td class="aleft productname" data-label="' . esc_html__( 'Items', 'usces' ) . '">' . apply_filters( 'usces_filter_cart_item_name', esc_html( $cartItemName ), $args ) . '<br />';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$optstr = '';
			foreach ( $options as $key => $value ) {
				if ( ! empty( $key ) ) {
					$key = urldecode( $key );
					if ( is_array( $value ) ) {
						$c       = '';
						$optstr .= esc_html( $key ) . ' : ';
						foreach ( $value as $v ) {
							$optstr .= $c . nl2br( esc_html( urldecode( $v ) ) );
							$c       = ', ';
						}
						$optstr .= "<br />\n";
					} else {
						$optstr .= esc_html( $key ) . ' : ' . nl2br( esc_html( urldecode( $value ) ) ) . "<br />\n";
					}
				}
			}
			$row .= apply_filters( 'usces_filter_option_cart', $optstr, $options, $args );
		}
		$row .= apply_filters( 'usces_filter_option_info_cart', '', $cart_row, $args );
		$row .= '</td>
			<td class="aright unitprice" data-label="' . esc_html__( 'Unit price', 'usces' ) . '">';
		if ( usces_is_gptekiyo( $post_id, $sku_code, $quantity ) ) {
			$usces_gp           = 1;
			$gp_src             = file_exists( get_template_directory() . '/images/gp.gif' ) ? get_template_directory_uri() . '/images/gp.gif' : USCES_PLUGIN_URL . '/images/gp.gif';
			$Business_pack_mark = '<img src="' . $gp_src . '" alt="' . __( 'Business package discount', 'usces' ) . '" /><br />';
			$row               .= apply_filters( 'usces_filter_itemGpExp_cart_mark', $Business_pack_mark );
		}
		$row         .= usces_crform( $skuPrice, true, false, 'return' ) . '
			</td>
			<td class="quantity" data-label="' . esc_html__( 'Quantity', 'usces' ) . '">';
		$row_quant    = '<input name="quant[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . ']" class="quantity" type="text" value="' . esc_attr( $cart_row['quantity'] ) . '" />';
		$row         .= apply_filters( 'usces_filter_cart_rows_quant', $row_quant, $args );
		$row         .= '</td>
			<td class="aright subtotal" data-label="' . esc_html__( 'Amount', 'usces' ) . usces_guid_tax( 'return', false ) . '">' . usces_crform( ( $skuPrice * $cart_row['quantity'] ), true, false, 'return' ) . '</td>';
		$stock_column = '<td ' . $red . ' data-label="' . esc_html__( 'stock status', 'usces' ) . '">' . esc_html( $stock ) . '</td>';
		$row         .= apply_filters( 'usces_filter_cart_rows_stock', $stock_column, $args, $red, $stock );
		$row         .= '<td class="action">';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			foreach ( $options as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						$row .= '<input name="itemOption[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . '][' . esc_attr( $key ) . '][' . esc_attr( $v ) . ']" type="hidden" value="' . esc_attr( $v ) . '" />' . "\n";
					}
				} else {
					$row .= '<input name="itemOption[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . '][' . esc_attr( $key ) . ']" type="hidden" value="' . esc_attr( $value ) . '" />' . "\n";
				}
			}
		}
		$row      .= '<input name="itemRestriction[' . $i . ']" type="hidden" value="' . esc_attr( $itemRestriction ) . '" />
			<input name="itemOrderAcceptable[' . $i . ']" type="hidden" value="' . $itemOrderAcceptable . '" />
			<input name="stockid[' . $i . ']" type="hidden" value="' . esc_attr( $stockid ) . '" />
			<input name="itempostid[' . $i . ']" type="hidden" value="' . esc_attr( $post_id ) . '" />
			<input name="itemsku[' . $i . ']" type="hidden" value="' . esc_attr( $sku ) . '" />
			<input name="zaikonum[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . ']" type="hidden" value="' . esc_attr( $skuZaikonum ) . '" />
			<input name="skuPrice[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . ']" type="hidden" value="' . esc_attr( $skuPrice ) . '" />
			<input name="advance[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . ']" type="hidden" value="' . esc_attr( $advance ) . '" />
			<input name="delButton[' . $i . '][' . $post_id . '][' . esc_attr( $sku ) . ']" class="delButton" type="submit" value="' . __( 'Delete', 'usces' ) . '" />
			</td>
		</tr>';
		$materials = compact( 'i', 'cart_row', 'post_id', 'sku', 'sku_code', 'quantity', 'options', 'advance', 'itemCode', 'itemName', 'cartItemName', 'itemRestriction', 'skuPrice', 'skuZaikonum', 'stockid', 'stock', 'red', 'pictid' );
		$res      .= apply_filters( 'usces_filter_cart_row', $row, $cart, $materials );
	}

	$res = apply_filters( 'usces_filter_cart_rows', $res, $cart );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo $res; // no escape due to filter.
	}
}

/**
 * Confirm Cart Item Details
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_get_confirm_rows( $out = '' ) {
	global $usces, $usces_members, $usces_entries;

	$memid = ( empty( $usces_members['ID'] ) ) ? 999999999 : $usces_members['ID'];
	$usces->set_cart_fees( $usces_members, $usces_entries );
	$usces_entries = $usces->cart->get_entry();

	$cart       = $usces->cart->get_cart();
	$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
	$res        = '';

	for ( $i = 0; $i < $cart_count; $i++ ) {
		$cart_row     = $cart[ $i ];
		$post_id      = (int) $cart_row['post_id'];
		$sku          = $cart_row['sku'];
		$sku_code     = urldecode( $cart_row['sku'] );
		$quantity     = $cart_row['quantity'];
		$options      = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		$advance      = $cart_row['advance'];
		$itemCode     = $usces->getItemCode( $post_id );
		$itemName     = $usces->getItemName( $post_id );
		$cartItemName = $usces->getCartItemName( $post_id, $sku_code );
		$skuPrice     = $cart_row['price'];
		$pictid       = $usces->get_mainpictid( $itemCode );
		$args         = compact( 'cart', 'i', 'cart_row', 'post_id', 'sku' );

		$row            = '';
		$row           .= '<tr>
			<td class="num">' . ( $i + 1 ) . '</td>
			<td class="thumbnail">';
		$cart_thumbnail = wp_get_attachment_image( $pictid, array( 60, 60 ), true );
		$row           .= apply_filters( 'usces_filter_cart_thumbnail', $cart_thumbnail, $post_id, $pictid, $i, $cart_row );
		$row           .= '</td><td class="productname" data-label="' . esc_html__( 'Items', 'usces' ) . '">' . apply_filters( 'usces_filter_cart_item_name', esc_html( $cartItemName ), $args ) . '<br />';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$optstr = '';
			foreach ( $options as $key => $value ) {
				if ( ! empty( $key ) ) {
					$key = urldecode( $key );
					if ( is_array( $value ) ) {
						$c       = '';
						$optstr .= esc_html( $key ) . ' : ';
						foreach ( $value as $v ) {
							$optstr .= $c . nl2br( esc_html( urldecode( $v ) ) );
							$c       = ', ';
						}
						$optstr .= "<br />\n";
					} else {
						$optstr .= esc_html( $key ) . ' : ' . nl2br( esc_html( urldecode( $value ) ) ) . "<br />\n";
					}
				}
			}
			$row .= apply_filters( 'usces_filter_option_confirm', $optstr, $options, $args );
		}
		$row .= apply_filters( 'usces_filter_option_info_confirm', '', $cart_row, $args );
		$row .= '</td>
			<td class="unitprice" data-label="' . esc_html__( 'Unit price', 'usces' ) . '">' . esc_html( usces_crform( $skuPrice, true, false, 'return' ) ) . '</td>
			<td class="quantity" data-label="' . esc_html__( 'Quantity', 'usces' ) . '">' . esc_html( $cart_row['quantity'] ) . '</td>
			<td class="subtotal" data-label="' . esc_html__( 'Amount', 'usces' ) . '">' . esc_html( usces_crform( ( $skuPrice * $cart_row['quantity'] ), true, false, 'return' ) ) . '</td>
			<td class="action">';
		$row .= apply_filters( 'usces_additional_confirm', '', array( $i, $post_id, $sku_code ) );
		$row .= '</td>
		</tr>';

		$materials = compact( 'i', 'cart_row', 'post_id', 'sku', 'sku_code', 'quantity', 'options', 'advance', 'itemCode', 'itemName', 'cartItemName', 'skuPrice', 'pictid' );
		$res      .= apply_filters( 'usces_filter_confirm_row', $row, $cart, $materials );
	}

	$res = apply_filters( 'usces_filter_confirm_rows', $res, $cart );

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo $res; // no escape due to filter.
	}
}

/**
 * Address Information
 *
 * @param string $type Type.
 * @param array  $data Entry data.
 * @param string $out Return value or echo.
 * @return string|void
 */
function uesces_addressform( $type, $data, $out = 'return' ) {
	global $usces, $usces_settings;

	$options   = get_option( 'usces' );
	$form      = $options['system']['addressform'];
	$nameform  = $usces_settings['nameform'][ $form ];
	$applyform = usces_get_apply_addressform( $form );
	$formtag   = '';
	switch ( $type ) {
		case 'confirm':
		case 'member':
			$values = $data;
			break;
		case 'customer':
		case 'delivery':
			$values = $data[ $type ];
			break;
	}
	$data['type']        = $type;
	$values['country']   = ! empty( $values['country'] ) ? $values['country'] : usces_get_local_addressform();
	$values              = $usces->stripslashes_deep_post( $values );
	$target_market_count = ( isset( $options['system']['target_market'] ) && is_array( $options['system']['target_market'] ) ) ? count( $options['system']['target_market'] ) : 1;

	if ( 'confirm' === $type ) {

		switch ( $applyform ) {

			case 'JP':
				$formtag          .= usces_custom_field_info( $data, 'customer', 'name_pre', 'return' );
				$formtag          .= '<tr class="name-row member-name-row"><th>' . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th><td>' . esc_html( sprintf( _x( '%s', 'honorific', 'usces' ), ( esc_html( $values['customer']['name1'] ) . ' ' . esc_html( $values['customer']['name2'] ) ) ) ) . '</td></tr>';
				$furigana          = ( '' === ( trim( $values['customer']['name3'] ) . trim( $values['customer']['name4'] ) ) ) ? '' : sprintf( _x( '%s', 'honorific', 'usces' ), ( esc_html( $values['customer']['name3'] ) . ' ' . esc_html( $values['customer']['name4'] ) ) );
				$furigana_customer = '<tr class="furikana-row member-furikana-row"><th>' . __( 'furigana', 'usces' ) . '</th><td>' . $furigana . '</td></tr>';
				$formtag          .= apply_filters( 'usces_filter_furigana_confirm_customer', $furigana_customer, $type, $values );
				$formtag          .= usces_custom_field_info( $data, 'customer', 'name_after', 'return' );
				$formtag          .= '<tr class="zipcode-row member-zipcode-row"><th>' . __( 'Zip/Postal Code', 'usces' ) . '</th><td>' . esc_html( $values['customer']['zipcode'] ) . '</td></tr>';
				if ( 1 < $target_market_count ) {
					$customer_country = ( ! empty( $usces_settings['country'][ $values['customer']['country'] ] ) ) ? $usces_settings['country'][ $values['customer']['country'] ] : '';
					$formtag         .= '<tr class="country-row member-country-row"><th>' . __( 'Country', 'usces' ) . '</th><td>' . esc_html( $customer_country ) . '</td></tr>';
				}
				$customer_pref = ( __( '-- Select --', 'usces' ) === $values['customer']['pref'] || '-- Select --' === $values['customer']['pref'] ) ? '' : $values['customer']['pref'];
				$formtag      .= '
				<tr class="states-row member-states-row"><th>' . __( 'Province', 'usces' ) . '</th><td>' . esc_html( $customer_pref ) . '</td></tr>
				<tr class="address1-row member-address1-row"><th>' . __( 'city', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address1'] ) . '</td></tr>
				<tr class="address2-row member-address2-row"><th>' . __( 'numbers', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address2'] ) . '</td></tr>
				<tr class="address3-row member-address3-row"><th>' . __( 'building name', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address3'] ) . '</td></tr>
				<tr class="tel-row member-tel-row"><th>' . __( 'Phone number', 'usces' ) . '</th><td>' . esc_html( $values['customer']['tel'] ) . '</td></tr>
				<tr class="fax-row member-fax-row"><th>' . __( 'FAX number', 'usces' ) . '</th><td>' . esc_html( $values['customer']['fax'] ) . '</td></tr>';
				$formtag      .= usces_custom_field_info( $data, 'customer', 'fax_after', 'return' );

				$shipping_address_info = '';
				if ( isset( $values['delivery'] ) ) {
					$shipping_address_info  = '<tr class="ttl"><td colspan="2"><h3>' . __( 'Shipping address information', 'usces' ) . '</h3></td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'name_pre', 'return' );
					$shipping_address_info .= '<tr class="name-row delivery-name-row"><th>' . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th><td>' . sprintf( _x( '%s', 'honorific', 'usces' ), ( esc_html( $values['delivery']['name1'] ) . ' ' . esc_html( $values['delivery']['name2'] ) ) ) . '</td></tr>';
					$deli_furigana          = ( '' === ( trim( $values['delivery']['name3'] ) . trim( $values['delivery']['name4'] ) ) ) ? '' : sprintf( _x( '%s', 'honorific', 'usces' ), ( esc_html( $values['delivery']['name3'] ) . ' ' . esc_html( $values['delivery']['name4'] ) ) );
					$furigana_delivery      = '<tr class="furikana-row delivery-furikana-row"><th>' . __( 'furigana', 'usces' ) . '</th><td>' . $deli_furigana . '</td></tr>';
					$shipping_address_info .= apply_filters( 'usces_filter_furigana_confirm_delivery', $furigana_delivery, $type, $values );
					$shipping_address_info .= usces_custom_field_info( $values, 'delivery', 'name_after', 'return' );
					$shipping_address_info .= '<tr class="zipcode-row delivery-zipcode-row"><th>' . __( 'Zip/Postal Code', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['zipcode'] ) . '</td></tr>';
					if ( 1 < $target_market_count ) {
						$shipping_country       = ( ! empty( $usces_settings['country'][ $values['delivery']['country'] ] ) ) ? $usces_settings['country'][ $values['delivery']['country'] ] : '';
						$shipping_address_info .= '<tr class="country-row delivery-country-row"><th>' . __( 'Country', 'usces' ) . '</th><td>' . esc_html( $shipping_country ) . '</td></tr>';
					}
					$delivery_pref          = ( __( '-- Select --', 'usces' ) === $values['delivery']['pref'] || '-- Select --' === $values['delivery']['pref'] ) ? '' : $values['delivery']['pref'];
					$shipping_address_info .= '
					<tr class="states-row delivery-states-row"><th>' . __( 'Province', 'usces' ) . '</th><td>' . esc_html( $delivery_pref ) . '</td></tr>
					<tr class="address1-row delivery-address1-row"><th>' . __( 'city', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address1'] ) . '</td></tr>
					<tr class="address2-row delivery-address2-row"><th>' . __( 'numbers', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address2'] ) . '</td></tr>
					<tr class="address3-row delivery-address3-row"><th>' . __( 'building name', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address3'] ) . '</td></tr>
					<tr class="tel-row delivery-tel-row"><th>' . __( 'Phone number', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['tel'] ) . '</td></tr>
					<tr class="fax-row delivery-fax-row"><th>' . __( 'FAX number', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['fax'] ) . '</td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'fax_after', 'return' );
				}
				$formtag .= apply_filters( 'usces_filter_shipping_address_info', $shipping_address_info );
				break;

			case 'CN':
				$formtag .= usces_custom_field_info( $data, 'customer', 'name_pre', 'return' );
				$formtag .= '<tr class="name-row member-name-row"><th>' . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th><td>' . sprintf( _x( '%s', 'honorific', 'usces' ), esc_html( usces_localized_name( $values['customer']['name1'], $values['customer']['name2'], 'return' ) ) ) . '</td></tr>';
				$formtag .= usces_custom_field_info( $data, 'customer', 'name_after', 'return' );
				if ( 1 < $target_market_count ) {
					$customer_country = ( ! empty( $usces_settings['country'][ $values['customer']['country'] ] ) ) ? $usces_settings['country'][ $values['customer']['country'] ] : '';
					$formtag         .= '<tr class="country-row member-country-row"><th>' . __( 'Country', 'usces' ) . '</th><td>' . esc_html( $customer_country ) . '</td></tr>';
				}
				$customer_pref = ( __( '-- Select --', 'usces' ) === $values['customer']['pref'] || '-- Select --' === $values['customer']['pref'] ) ? '' : $values['customer']['pref'];
				$formtag      .= '
				<tr class="states-row member-states-row"><th>' . __( 'State', 'usces' ) . '</th><td>' . esc_html( $customer_pref ) . '</td></tr>
				<tr class="address1-row member-address1-row"><th>' . __( 'city', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address1'] ) . '</td></tr>
				<tr class="address2-row member-address2-row"><th>' . __( 'Address Line1', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address2'] ) . '</td></tr>
				<tr class="address3-row member-address3-row"><th>' . __( 'Address Line2', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address3'] ) . '</td></tr>
				<tr class="zipcode-row member-zipcode-row"><th>' . __( 'Zip', 'usces' ) . '</th><td>' . esc_html( $values['customer']['zipcode'] ) . '</td></tr>
				<tr class="tel-row member-tel-row"><th>' . __( 'Phone number', 'usces' ) . '</th><td>' . esc_html( $values['customer']['tel'] ) . '</td></tr>
				<tr class="fax-row member-fax-row"><th>' . __( 'FAX number', 'usces' ) . '</th><td>' . esc_html( $values['customer']['fax'] ) . '</td></tr>';
				$formtag      .= usces_custom_field_info( $data, 'customer', 'fax_after', 'return' );

				$shipping_address_info = '';
				if ( isset( $values['delivery'] ) ) {
					$shipping_address_info  = '<tr class="ttl"><td colspan="2"><h3>' . __( 'Shipping address information', 'usces' ) . '</h3></td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'name_pre', 'return' );
					$shipping_address_info .= '<tr class="name-row delivery-name-row"><th>' . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th><td>' . sprintf( _x( '%s', 'honorific', 'usces' ), esc_html( usces_localized_name( $values['delivery']['name1'], $values['delivery']['name2'], 'return' ) ) ) . '</td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'name_after', 'return' );
					if ( 1 < $target_market_count ) {
						$shipping_country       = ( ! empty( $usces_settings['country'][ $values['delivery']['country'] ] ) ) ? $usces_settings['country'][ $values['delivery']['country'] ] : '';
						$shipping_address_info .= '<tr class="country-row delivery-country-row"><th>' . __( 'Country', 'usces' ) . '</th><td>' . esc_html( $shipping_country ) . '</td></tr>';
					}
					$delivery_pref          = ( __( '-- Select --', 'usces' ) === $values['delivery']['pref'] || '-- Select --' === $values['delivery']['pref'] ) ? '' : $values['delivery']['pref'];
					$shipping_address_info .= '
					<tr class="states-row delivery-states-row"><th>' . __( 'State', 'usces' ) . '</th><td>' . esc_html( $delivery_pref ) . '</td></tr>
					<tr class="address1-row delivery-address1-row"><th>' . __( 'city', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address1'] ) . '</td></tr>
					<tr class="address2-row delivery-address2-row"><th>' . __( 'Address Line1', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address2'] ) . '</td></tr>
					<tr class="address3-row delivery-address3-row"><th>' . __( 'Address Line2', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address3'] ) . '</td></tr>
					<tr class="zipcode-row delivery-zipcode-row"><th>' . __( 'Zip', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['zipcode'] ) . '</td></tr>
					<tr class="tel-row delivery-tel-row"><th>' . __( 'Phone number', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['tel'] ) . '</td></tr>
					<tr class="fax-row delivery-fax-row"><th>' . __( 'FAX number', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['fax'] ) . '</td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'fax_after', 'return' );
				}
				$formtag .= apply_filters( 'usces_filter_shipping_address_info', $shipping_address_info );
				break;

			case 'US':
			default:
				$customer_pref = ( __( '-- Select --', 'usces' ) === $values['customer']['pref'] || '-- Select --' === $values['customer']['pref'] ) ? '' : $values['customer']['pref'];
				$formtag      .= usces_custom_field_info( $data, 'customer', 'name_pre', 'return' );
				$formtag      .= '<tr class="name-row member-name-row"><th>' . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th><td>' . sprintf( _x( '%s', 'honorific', 'usces' ), ( esc_html( $values['customer']['name2'] ) . ' ' . esc_html( $values['customer']['name1'] ) ) ) . '</td></tr>';
				$formtag      .= usces_custom_field_info( $data, 'customer', 'name_after', 'return' );
				$formtag      .= '
				<tr class="address2-row member-address2-row"><th>' . __( 'Address Line1', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address2'] ) . '</td></tr>
				<tr class="address3-row member-address3-row"><th>' . __( 'Address Line2', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address3'] ) . '</td></tr>
				<tr class="address1-row member-address1-row"><th>' . __( 'city', 'usces' ) . '</th><td>' . esc_html( $values['customer']['address1'] ) . '</td></tr>
				<tr class="states-row member-states-row"><th>' . __( 'State', 'usces' ) . '</th><td>' . esc_html( $customer_pref ) . '</td></tr>';
				if ( 1 < $target_market_count ) {
					$customer_country = ( ! empty( $usces_settings['country'][ $values['customer']['country'] ] ) ) ? $usces_settings['country'][ $values['customer']['country'] ] : '';
					$formtag         .= '<tr class="country-row member-country-row"><th>' . __( 'Country', 'usces' ) . '</th><td>' . esc_html( $customer_country ) . '</td></tr>';
				}
				$formtag .= '
				<tr class="zipcode-row member-zipcode-row"><th>' . __( 'Zip', 'usces' ) . '</th><td>' . esc_html( $values['customer']['zipcode'] ) . '</td></tr>
				<tr class="tel-row member-tel-row"><th>' . __( 'Phone number', 'usces' ) . '</th><td>' . esc_html( $values['customer']['tel'] ) . '</td></tr>
				<tr class="fax-row member-fax-row"><th>' . __( 'FAX number', 'usces' ) . '</th><td>' . esc_html( $values['customer']['fax'] ) . '</td></tr>';
				$formtag .= usces_custom_field_info( $data, 'customer', 'fax_after', 'return' );

				$shipping_address_info = '';
				if ( isset( $values['delivery'] ) ) {
					$delivery_pref          = ( __( '-- Select --', 'usces' ) === $values['delivery']['pref'] || '-- Select --' === $values['delivery']['pref'] ) ? '' : $values['delivery']['pref'];
					$shipping_address_info  = '<tr class="ttl"><td colspan="2"><h3>' . __( 'Shipping address information', 'usces' ) . '</h3></td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'name_pre', 'return' );
					$shipping_address_info .= '<tr class="name-row delivery-name-row"><th>' . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th><td>' . sprintf( _x( '%s', 'honorific', 'usces' ), ( esc_html( $values['delivery']['name2'] ) . ' ' . esc_html( $values['delivery']['name1'] )) ) . '</td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'name_after', 'return' );
					$shipping_address_info .= '
					<tr class="address2-row delivery-address2-row"><th>' . __( 'Address Line1', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address2'] ) . '</td></tr>
					<tr class="address3-row delivery-address3-row"><th>' . __( 'Address Line2', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address3'] ) . '</td></tr>
					<tr class="address1-row delivery-address1-row"><th>' . __( 'city', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['address1'] ) . '</td></tr>
					<tr class="states-row delivery-states-row"><th>' . __( 'State', 'usces' ) . '</th><td>' . esc_html( $delivery_pref ) . '</td></tr>';
					if ( 1 < $target_market_count ) {
						$shipping_country       = ( ! empty( $usces_settings['country'][ $values['delivery']['country'] ] ) ) ? $usces_settings['country'][ $values['delivery']['country'] ] : '';
						$shipping_address_info .= '<tr class="country-row delivery-country-row"><th>' . __( 'Country', 'usces' ) . '</th><td>' . esc_html( $shipping_country ) . '</td></tr>';
					}
					$shipping_address_info .= '
					<tr class="zipcode-row delivery-zipcode-row"><th>' . __( 'Zip', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['zipcode'] ) . '</td></tr>
					<tr class="tel-row delivery-tel-row"><th>' . __( 'Phone number', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['tel'] ) . '</td></tr>
					<tr class="fax-row delivery-fax-row"><th>' . __( 'FAX number', 'usces' ) . '</th><td>' . esc_html( $values['delivery']['fax'] ) . '</td></tr>';
					$shipping_address_info .= usces_custom_field_info( $data, 'delivery', 'fax_after', 'return' );
				}
				$formtag .= apply_filters( 'usces_filter_shipping_address_info', $shipping_address_info );
				break;
		}
		$res = apply_filters( 'usces_filter_apply_addressform_confirm', $formtag, $type, $data );

	} else {

		switch ( $applyform ) {

			case 'JP':
				$formtag .= usces_custom_field_input( $data, $type, 'name_pre', 'return' );
				$formtag .= '<tr id="name_row" class="inp1">
				<th width="127" scope="row">' . usces_get_essential_mark( 'name1', $data ) . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th>';
				if ( $nameform ) {
					$formtag .= '<td class="name_td"><span class="member_name">' . __( 'Given name', 'usces' ) . '</span><input name="' . $type . '[name2]" id="name2" type="text" value="' . esc_attr( $values['name2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
					$formtag .= '<td class="name_td"><span class="member_name">' . __( 'Familly name', 'usces' ) . '</span><input name="' . $type . '[name1]" id="name1" type="text" value="' . esc_attr( $values['name1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
				} else {
					$formtag .= '<td class="name_td"><span class="member_name">' . __( 'Familly name', 'usces' ) . '</span><input name="' . $type . '[name1]" id="name1" type="text" value="' . esc_attr( $values['name1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
					$formtag .= '<td class="name_td"><span class="member_name">' . __( 'Given name', 'usces' ) . '</span><input name="' . $type . '[name2]" id="name2" type="text" value="' . esc_attr( $values['name2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
				}
				$formtag .= '</tr>';
				$furigana = '<tr id="furikana_row" class="inp1">
				<th scope="row">' . usces_get_essential_mark( 'name3', $data ) . __( 'furigana', 'usces' ) . '</th>';
				if ( $nameform ) {
					$furigana .= '<td><span class="member_furigana">' . _x( 'Given name', 'furigana', 'usces' ) . '</span><input name="' . $type . '[name4]" id="name4" type="text" value="' . esc_attr( $values['name4'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
					$furigana .= '<td><span class="member_furigana">' . _x( 'Familly name', 'furigana', 'usces' ) . '</span><input name="' . $type . '[name3]" id="name3" type="text" value="' . esc_attr( $values['name3'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
				} else {
					$furigana .= '<td><span class="member_furigana">' . _x( 'Familly name', 'furigana', 'usces' ) . '</span><input name="' . $type . '[name3]" id="name3" type="text" value="' . esc_attr( $values['name3'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
					$furigana .= '<td><span class="member_furigana">' . _x( 'Given name', 'furigana', 'usces' ) . '</span><input name="' . $type . '[name4]" id="name4" type="text" value="' . esc_attr( $values['name4'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" /></td>';
				}
				$furigana .= '</tr>';
				$formtag  .= apply_filters( 'usces_filter_furigana_form', $furigana, $type, $values );
				$formtag  .= usces_custom_field_input( $data, $type, 'name_after', 'return' );
				$formtag  .= '<tr id="zipcode_row">
				<th scope="row">' . usces_get_essential_mark( 'zipcode', $data ) . __( 'Zip/Postal Code', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[zipcode]" id="zipcode" type="text" value="' . esc_attr( $values['zipcode'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: inactive" />' . usces_postal_code_address_search( $type ) . apply_filters( 'usces_filter_addressform_zipcode', null, $type ) . apply_filters( 'usces_filter_after_zipcode', '100-1000', $applyform ) . '</td>
				</tr>';
				if ( 1 < $target_market_count ) {
					$formtag .= '<tr id="country_row">
					<th scope="row">' . usces_get_essential_mark( 'country', $data ) . __( 'Country', 'usces' ) . '</th>
					<td colspan="2">' . uesces_get_target_market_form( $type, $values['country'] ) . apply_filters( 'usces_filter_after_country', null, $applyform ) . '</td>
					</tr>';
				} else {
					$formtag .= '<input type="hidden" name="' . $type . '[country]" id="' . $type . '_country" value="' . $options['system']['target_market'][0] . '">';
				}
				$formtag .= '<tr id="states_row">
				<th scope="row">' . usces_get_essential_mark( 'states', $data ) . __( 'Province', 'usces' ) . '</th>
				<td colspan="2">' . usces_pref_select( $type, $values ) . apply_filters( 'usces_filter_after_states', null, $applyform ) . '</td>
				</tr>
				<tr id="address1_row" class="inp2">
				<th scope="row">' . usces_get_essential_mark( 'address1', $data ) . __( 'city', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[address1]" id="address1" type="text" value="' . esc_attr( $values['address1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" />' . apply_filters( 'usces_filter_after_address1', __( 'Kitakami Yokohama', 'usces' ), $applyform ) . '</td>
				</tr>
				<tr id="address2_row">
				<th scope="row">' . usces_get_essential_mark( 'address2', $data ) . __( 'numbers', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[address2]" id="address2" type="text" value="' . esc_attr( $values['address2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" />' . apply_filters( 'usces_filter_after_address2', '3-24-555', $applyform ) . '</td>
				</tr>
				<tr id="address3_row">
				<th scope="row">' . usces_get_essential_mark( 'address3', $data ) . __( 'building name', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[address3]" id="address3" type="text" value="' . esc_attr( $values['address3'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: active" />' . apply_filters( 'usces_filter_after_address3', __( 'tuhanbuild 4F', 'usces' ), $applyform ) . '</td>
				</tr>
				<tr id="tel_row">
				<th scope="row">' . usces_get_essential_mark( 'tel', $data ) . __( 'Phone number', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[tel]" id="tel" type="text" value="' . esc_attr( $values['tel'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: inactive" />' . apply_filters( 'usces_filter_after_tel', '1000-10-1000', $applyform ) . '</td>
				</tr>
				<tr id="fax_row">
				<th scope="row">' . usces_get_essential_mark( 'fax', $data ) . __( 'FAX number', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[fax]" id="fax" type="text" value="' . esc_attr( $values['fax'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" style="ime-mode: inactive" />' . apply_filters( 'usces_filter_after_fax', '1000-10-1000', $applyform ) . '</td>
				</tr>';
				$formtag .= usces_custom_field_input( $data, $type, 'fax_after', 'return' );
				break;

			case 'CN':
				$formtag .= usces_custom_field_input( $data, $type, 'name_pre', 'return' );
				$formtag .= '<tr id="name_row" class="inp1">
				<th scope="row">' . usces_get_essential_mark( 'name1', $data ) . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th>';
				if ( $nameform ) {
					$formtag .= '<td>' . __( 'Given name', 'usces' ) . '<input name="' . $type . '[name2]" id="name2" type="text" value="' . esc_attr( $values['name2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
					$formtag .= '<td>' . __( 'Familly name', 'usces' ) . '<input name="' . $type . '[name1]" id="name1" type="text" value="' . esc_attr( $values['name1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
				} else {
					$formtag .= '<td>' . __( 'Familly name', 'usces' ) . '<input name="' . $type . '[name1]" id="name1" type="text" value="' . esc_attr( $values['name1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
					$formtag .= '<td>' . __( 'Given name', 'usces' ) . '<input name="' . $type . '[name2]" id="name2" type="text" value="' . esc_attr( $values['name2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
				}
				$formtag .= '</tr>';
				$formtag .= usces_custom_field_input( $data, $type, 'name_after', 'return' );
				if ( 1 < $target_market_count ) {
					$formtag .= '<tr id="country_row">
					<th scope="row">' . usces_get_essential_mark( 'country', $data ) . __( 'Country', 'usces' ) . '</th>
					<td colspan="2">' . uesces_get_target_market_form( $type, $values['country'] ) . apply_filters( 'usces_filter_after_country', null, $applyform ) . '</td>
					</tr>';
				} else {
					$formtag .= '<input type="hidden" name="' . $type . '[country]" id="' . $type . '_country" value="' . $options['system']['target_market'][0] . '">';
				}
				$formtag .= '<tr id="states_row">
				<th scope="row">' . usces_get_essential_mark( 'states', $data ) . __( 'State', 'usces' ) . '</th>
				<td colspan="2">' . usces_pref_select( $type, $values ) . apply_filters( 'usces_filter_after_states', null, $applyform ) . '</td>
				</tr>
				<tr id="address1_row" class="inp2">
				<th scope="row">' . usces_get_essential_mark( 'address1', $data ) . __( 'city', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[address1]" id="address1" type="text" value="' . esc_attr( $values['address1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_address1', null, $applyform ) . '</td>
				</tr>
				<tr id="address2_row">
				<th scope="row">' . usces_get_essential_mark( 'address2', $data ) . __( 'Address Line1', 'usces' ) . '</th>
				<td colspan="2">' . __( 'Street address', 'usces' ) . '<br /><input name="' . $type . '[address2]" id="address2" type="text" value="' . esc_attr( $values['address2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_address2', null, $applyform ) . '</td>
				</tr>
				<tr id="address3_row">
				<th scope="row">' . usces_get_essential_mark( 'address3', $data ) . __( 'Address Line2', 'usces' ) . '</th>
				<td colspan="2">' . __( 'Apartment, building, etc.', 'usces' ) . '<br /><input name="' . $type . '[address3]" id="address3" type="text" value="' . esc_attr( $values['address3'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_address3', null, $applyform ) . '</td>
				</tr>
				<tr id="zipcode_row">
				<th scope="row">' . usces_get_essential_mark( 'zipcode', $data ) . __( 'Zip', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[zipcode]" id="zipcode" type="text" value="' . esc_attr( $values['zipcode'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_zipcode', null, $applyform ) . '</td>
				</tr>
				<tr id="tel_row">
				<th scope="row">' . usces_get_essential_mark( 'tel', $data ) . __( 'Phone number', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[tel]" id="tel" type="text" value="' . esc_attr( $values['tel'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_tel', null, $applyform ) . '</td>
				</tr>
				<tr id="fax_row">
				<th scope="row">' . usces_get_essential_mark( 'fax', $data ) . __( 'FAX number', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[fax]" id="fax" type="text" value="' . esc_attr( $values['fax'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_fax', null, $applyform ) . '</td>
				</tr>';
				$formtag .= usces_custom_field_input( $data, $type, 'fax_after', 'return' );
				break;

			case 'US':
			default:
				$formtag .= usces_custom_field_input( $data, $type, 'name_pre', 'return' );
				$formtag .= '<tr id="name_row" class="inp1">
				<th scope="row">' . usces_get_essential_mark( 'name1', $data ) . apply_filters( 'usces_filters_addressform_name_label', __( 'Full name', 'usces' ), $type, $values, $applyform ) . '</th>';
				if ( $nameform ) {
					$formtag .= '<td>' . __( 'Given name', 'usces' ) . '<input name="' . $type . '[name2]" id="name2" type="text" value="' . esc_attr( $values['name2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
					$formtag .= '<td>' . __( 'Familly name', 'usces' ) . '<input name="' . $type . '[name1]" id="name1" type="text" value="' . esc_attr( $values['name1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
				} else {
					$formtag .= '<td>' . __( 'Familly name', 'usces' ) . '<input name="' . $type . '[name1]" id="name1" type="text" value="' . esc_attr( $values['name1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
					$formtag .= '<td>' . __( 'Given name', 'usces' ) . '<input name="' . $type . '[name2]" id="name2" type="text" value="' . esc_attr( $values['name2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" /></td>';
				}
				$formtag .= '</tr>';
				$formtag .= usces_custom_field_input( $data, $type, 'name_after', 'return' );
				$formtag .= '
				<tr id="address2_row">
				<th scope="row">' . usces_get_essential_mark( 'address2', $data ) . __( 'Address Line1', 'usces' ) . '</th>
				<td colspan="2">' . __( 'Street address', 'usces' ) . '<br /><input name="' . $type . '[address2]" id="address2" type="text" value="' . esc_attr( $values['address2'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_address2', null, $applyform ) . '</td>
				</tr>
				<tr id="address3_row">
				<th scope="row">' . usces_get_essential_mark( 'address3', $data ) . __( 'Address Line2', 'usces' ) . '</th>
				<td colspan="2">' . __( 'Apartment, building, etc.', 'usces' ) . '<br /><input name="' . $type . '[address3]" id="address3" type="text" value="' . esc_attr( $values['address3'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_address3', null, $applyform ) . '</td>
				</tr>
				<tr id="address1_row" class="inp2">
				<th scope="row">' . usces_get_essential_mark( 'address1', $data ) . __( 'city', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[address1]" id="address1" type="text" value="' . esc_attr( $values['address1'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_address1', null, $applyform ) . '</td>
				</tr>
				<tr id="states_row">
				<th scope="row">' . usces_get_essential_mark( 'states', $data ) . __( 'State', 'usces' ) . '</th>
				<td colspan="2">' . usces_pref_select( $type, $values ) . apply_filters( 'usces_filter_after_states', null, $applyform ) . '</td>
				</tr>';
				if ( 1 < $target_market_count ) {
					$formtag .= '<tr id="country_row">
					<th scope="row">' . usces_get_essential_mark( 'country', $data ) . __( 'Country', 'usces' ) . '</th>
					<td colspan="2">' . uesces_get_target_market_form( $type, $values['country'] ) . apply_filters( 'usces_filter_after_country', null, $applyform ) . '</td>
					</tr>';
				} else {
					$formtag .= '<input type="hidden" name="' . $type . '[country]" id="' . $type . '_country" value="' . esc_attr( $options['system']['target_market'][0] ) . '">';
				}
				$formtag .= '<tr id="zipcode_row">
				<th scope="row">' . usces_get_essential_mark( 'zipcode', $data ) . __( 'Zip', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[zipcode]" id="zipcode" type="text" value="' . esc_attr( $values['zipcode'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}"  />' . apply_filters( 'usces_filter_after_zipcode', null, $applyform ) . '</td>
				</tr>
				<tr id="tel_row">
				<th scope="row">' . usces_get_essential_mark( 'tel', $data ) . __( 'Phone number', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[tel]" id="tel" type="text" value="' . esc_attr( $values['tel'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_tel', null, $applyform ) . '</td>
				</tr>
				<tr id="fax_row">
				<th scope="row">' . usces_get_essential_mark( 'fax', $data ) . __( 'FAX number', 'usces' ) . '</th>
				<td colspan="2"><input name="' . $type . '[fax]" id="fax" type="text" value="' . esc_attr( $values['fax'] ) . '" onKeyDown="if (event.keyCode == 13) {return false;}" />' . apply_filters( 'usces_filter_after_fax', null, $applyform ) . '</td>
				</tr>';
				$formtag .= usces_custom_field_input( $data, $type, 'fax_after', 'return' );
				break;
		}
		$res = apply_filters( 'usces_filter_apply_addressform', $formtag, $type, $data );
	}

	if ( 'return' === $out ) {
		return $res;
	} else {
		echo $res; // no escape due to filter.
	}
}

/**
 * Item option field
 *
 * @param int    $post_id Post ID.
 * @param string $sku SKU code.
 * @param int    $label Tabel.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_item_option_fileds( $post_id, $sku, $label = 1, $out = 'echo' ) {
	$options = wel_get_opts( $post_id, 'sort' );
	if ( ! $options || ! is_array( $options ) ) {
		return false;
	}
	if ( 0 === count( $options ) ) {
		return false;
	}
	$sku_enc = urlencode( $sku );

	$html = '';
	foreach ( $options as $opt ) {
		$name     = $opt['name'];
		$opt_code = urlencode( $name );
		$html    .= '<div class="opt_field" id="opt_' . $post_id . '_' . $sku_enc . '_' . $opt_code . '">';
		if ( $label ) {
			$html .= '<label for="itemOption[' . $post_id . '][' . $sku_enc . '][' . $opt_code . ']">' . esc_html( $name ) . '</label>';
		}
		$html .= usces_get_itemopt_filed( $post_id, $sku, $opt );
		$html .= '</div>';
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $html );
	} else {
		wel_esc_script_e( $html );
	}
}

/**
 * Facebook
 */
function usces_facebook_like() {
	global $post, $usces;
	$like = array(
		'url'        => urlencode( get_permalink( $post->ID ) ),
		'send'       => 'false',
		'layout'     => 'button_count', /* standard, button_count, box_count */
		'width'      => '450',
		'height'     => '35',
		'show_faces' => 'false',
		'action'     => 'like', /* like, recommend */
	);
	$like = apply_filters( 'usces_filter_facebook_like', $like, $post->ID );
?>
<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo esc_url( $like['url'] ); ?>&amp;send=<?php echo esc_attr( $like['send'] ); ?>&amp;layout=<?php echo esc_attr( $like['layout'] ); ?>&amp;width=<?php echo esc_attr( $like['width'] ); ?>&amp;show_faces=<?php echo esc_attr( $like['show_faces'] ); ?>&amp;action=<?php echo esc_attr( $like['action'] ); ?>&amp;colorscheme=light&amp;font=arial&amp;height=<?php echo esc_attr( $like['height'] ); ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo esc_attr( $like['width'] ); ?>px; height:<?php echo esc_attr( $like['height'] ); ?>px;" allowTransparency="true"></iframe>
<?php
}

/**
 * Checked
 *
 * @param string $chk Checked.
 * @param string $key Key.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_checked( $chk, $key, $out = '' ) {
	$checked = ( isset( $chk[ $key ] ) && 1 === (int) $chk[ $key ] ) ? ' checked="checked"' : '';
	if ( 'return' === $out ) {
		return $checked;
	} else {
		echo esc_html( $checked );
	}
}

/**
 * Custom field value
 *
 * @param string $field Field.
 * @param string $key Key.
 * @param string $id ID.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_get_custom_field_value( $field, $key, $id, $out = '' ) {
	global $usces;

	$value = '';
	switch ( $field ) {
		case 'order':
			$value = $usces->get_order_meta_value( 'csod_' . $key, $id );
			break;
		case 'customer':
			$value = $usces->get_order_meta_value( 'cscs_' . $key, $id );
			break;
		case 'delivery':
			$value = $usces->get_order_meta_value( 'csde_' . $key, $id );
			break;
		case 'member':
			$value = $usces->get_member_meta_value( 'csmb_' . $key, $id );
			break;
	}

	if ( 'return' === $out ) {
		return $value;
	} else {
		echo esc_html( $value );
	}
}

/**
 * Address search button
 *
 * @param string $type Type.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_postal_code_address_search( $type, $out = 'return' ) {
	global $usces;

	$html = '';
	if ( isset( $usces->options['address_search'] ) && 'activate' === $usces->options['address_search'] ) {
		$address_search_label = apply_filters( 'usces_filter_postal_code_address_search_label', '住所検索' );
		$html                 = '<input type="button" id="search_zipcode" class="search-zipcode button" value="' . $address_search_label . '" onClick="AjaxZip3.zip2addr(\'' . esc_js( $type . '[zipcode]' ) . '\', \'\', \'' . esc_js( $type . '[pref]' ) . '\', \'' . esc_js( $type . '[address1]' ) . '\' );" >';
		if ( 'delivery' === $type ) {
			$entry   = $usces->cart->get_entry();
			$zipcode = ( isset( $entry['delivery']['zipcode'] ) ) ? $entry['delivery']['zipcode'] : '';
			$html   .= '<input type="hidden" id="search_zipcode_change" value="' . esc_attr( $zipcode ) . '">';
		}
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $html );
	} else {
		wel_esc_script_e( $html );
	}
}

/**
 * Agreement Approval Field
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_agree_member_field( $out = '' ) {
	global $usces;

	$html = '';
	if ( usces_is_membersystem_state() ) {
		$row = '';
		if ( isset( $usces->options['agree_member'] ) && 'activate' === $usces->options['agree_member'] ) {
			$row .= '<div class="agree_member_area">';
			if ( isset( $usces->options['member_page_data']['agree_member_exp'] ) ) {
				$row .= '<div class="at_exp_text">' . stripslashes( nl2br( $usces->options['member_page_data']['agree_member_exp'] ) ) . '</div>';
			}
			if ( isset( $usces->options['member_page_data']['agree_member_cont'] ) ) {
				$row .= '<textarea name="at_cont_text" class="at_cont_text" readonly="readonly">' . $usces->options['member_page_data']['agree_member_cont'] . '</textarea>
					<div class="at_check_area"><input name="agree_member_check" value="1" id="agree_member_check" class="at_check" type="checkbox"><label for="agree_member_check" style="cursor:pointer;"> ' . __( 'Accept the membership agreement', 'usces' ) . '</label></div>';
			}
			$row .= '</div>';
		}
		$html = apply_filters( 'usces_filter_agree_member_field', $row );
	}

	if ( 'return' === $out ) {
		return wel_esc_script( $html );
	} else {
		wel_esc_script_e( $html );
	}
}

/**
 * No image
 *
 * @param array  $size Size.
 * @param string $alt Alt.
 * @return string
 */
function usces_get_attachment_noimage( $size = array( 60, 60 ), $alt = '' ) {
	$size_class = join( 'x', $size );
	return '<img width="' . $size[0] . '" height="' . $size[1] . '" src="' . USCES_PLUGIN_URL . '/images/default.png" class="attachment-' . $size_class . ' size-' . $size_class . '" alt="' . $alt . '">';
}

/**
 * Tax rate
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_taxrate( $out = '' ) {
	global $usces;

	if ( isset( $usces->itemsku['taxrate'] ) ) {
		$taxrate = ( 'reduced' === $usces->itemsku['taxrate'] ) ? $usces->options['tax_rate_reduced'] : $usces->options['tax_rate'];
	} else {
		$taxrate = $usces->options['tax_rate'];
	}
	$the_taxrate = '<em class="tax">' . sprintf( __( "Tax Rate %s%%", 'usces' ), $taxrate ) . '</em>';
	$the_taxrate = apply_filters( 'usces_filter_the_taxrate', $the_taxrate, $usces->itemsku, $out );

	if ( 'return' === $out ) {
		return wel_esc_script( $the_taxrate );
	} else {
		wel_esc_script_e( $the_taxrate );
	}
}

/**
 * Tax amount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_cart_tax( $out = '' ) {
	global $usces;

	$total_items_price = $usces->get_total_price();
	$materials         = array(
		'total_items_price' => $total_items_price,
		'discount'          => 0,
		'shipping_charge'   => 0,
		'cod_fee'           => 0,
		'use_point'         => 0,
	);
	$tax               = $usces->getTax( $total_items_price, $materials );

	if ( 'return' === $out ) {
		return wel_esc_script( $tax );
	} else {
		wel_esc_script_e( $tax );
	}
}

/**
 * Total tax amount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_confirm_tax( $out = '' ) {
	global $usces;

	if ( $usces->is_reduced_taxrate() ) {
		if ( 'include' === $usces->options['tax_mode'] ) {
			$po = '( ';
			$pc = ' )';
		} else {
			$po = '';
			$pc = '';
		}
		$usces_tax       = Welcart_Tax::get_instance();
		$entry           = wel_get_entry();
		$shipping_charge = isset( $entry['order']['shipping_charge'] ) ? $entry['order']['shipping_charge'] : 0;
		$cod_fee         = isset( $entry['order']['cod_fee'] ) ? $entry['order']['cod_fee'] : 0;
		$materials       = compact( 'shipping_charge', 'cod_fee');
		$usces_tax->get_order_tax( $materials );
		$tax  = sprintf( __( "Applies to %s%%", 'usces' ), $usces->options['tax_rate'] ) . '&nbsp;&nbsp;' . usces_crform( $usces_tax->subtotal_standard + $usces_tax->discount_standard, true, false, 'return' ) . '&nbsp;&nbsp;';
		$tax .= sprintf( __( "%s%% consumption tax", 'usces' ), $usces->options['tax_rate'] ) . '&nbsp;&nbsp;' . $po . usces_crform( $usces_tax->tax_standard, true, false, 'return' ) . $pc . '<br />';
		$tax .= sprintf( __( "Applies to %s%%", 'usces' ), $usces->options['tax_rate_reduced'] ) . '&nbsp;&nbsp;' . usces_crform( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced, true, false, 'return' ) . '&nbsp;&nbsp;';
		$tax .= sprintf( __( "%s%% consumption tax", 'usces' ), $usces->options['tax_rate_reduced'] ) . '&nbsp;&nbsp;' . $po . usces_crform( $usces_tax->tax_reduced, true, false, 'return' ) . $pc;
	} else {
		$tax = '';
	}

	$tax = apply_filters( 'usces_filter_confirm_tax', $tax, $out );

	if ( 'return' === $out ) {
		return wel_esc_script( $tax );
	} else {
		wel_esc_script_e( $tax );
	}
}

/**
 * Tax-included amount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemPrice_taxincluded( $out = '' ) {
	global $post, $usces;
	$usces_tax = Welcart_Tax::get_instance();

	if ( empty( $usces->itemsku['code'] ) ) {
		$skus     = $usces->get_skus( $post->ID );
		$skucode  = $skus[0]['code'];
		$skuprice = $skus[0]['price'];
	} else {
		$skucode  = $usces->itemsku['code'];
		$skuprice = $usces->itemsku['price'];
	}
	$tax_rate = $usces_tax->get_sku_tax_rate( $post->ID, $skucode );
	$tax      = (float) sprintf( '%.3f', (float) $skuprice * (float) $tax_rate / 100 );
	$tax      = usces_tax_rounding_off( $tax );
	$price    = $skuprice + $tax;

	if ( 'return' === $out ) {
		return $price;
	} else {
		echo number_format( $price );
	}
}

/**
 * Tax-included normal amount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemCprice_taxincluded( $out = '' ) {
	global $post, $usces;
	$usces_tax = Welcart_Tax::get_instance();

	if ( empty( $usces->itemsku['code'] ) ) {
		$skus      = $usces->get_skus( $post->ID );
		$skucode   = $skus[0]['code'];
		$skucprice = $skus[0]['cprice'];
	} else {
		$skucode   = $usces->itemsku['code'];
		$skucprice = $usces->itemsku['cprice'];
	}
	$tax_rate = $usces_tax->get_sku_tax_rate( $post->ID, $skucode );
	$tax      = (float) sprintf( '%.3f', (float) $skucprice * (float) $tax_rate / 100 );
	$tax      = usces_tax_rounding_off( $tax );
	$price    = $skucprice + $tax;
	if ( 'return' === $out ) {
		return $price;
	} else {
		echo number_format( $price );
	}
}

/**
 * Formatted tax included amount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemPriceCr_taxincluded( $out = '' ) {
	global $usces;
	$usces_tax = Welcart_Tax::get_instance();

	$price_taxincluded = usces_the_itemPrice_taxincluded( 'return' );
	$price             = $usces->get_currency( $price_taxincluded, true, false );
	$price             = apply_filters( 'usces_filter_the_item_price_cr_taxincluded', $price, $price_taxincluded, $out );
	if ( 'return' === $out ) {
		return $price;
	} else {
		echo esc_html( $price );
	}
}

/**
 * Formatted tax included normal amount
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_the_itemCpriceCr_taxincluded( $out = '' ) {
	global $usces;
	$usces_tax = Welcart_Tax::get_instance();

	$price_taxincluded = usces_the_itemCprice_taxincluded( 'return' );
	$price             = $usces->get_currency( $price_taxincluded, true, false );
	$price             = apply_filters( 'usces_filter_the_item_cprice_cr_taxincluded', $price, $price_taxincluded, $out );
	if ( 'return' === $out ) {
		return $price;
	} else {
		echo esc_html( $price );
	}
}

/**
 * Password policy
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_password_policy_message( $out = '' ) {

	$usces_options = get_option( 'usces' );
	$rules         = array();
	$sep           = '';

	if ( ! empty( $usces_options['system']['member_pass_rule_max'] ) ) {
		if ( $usces_options['system']['member_pass_rule_max'] === $usces_options['system']['member_pass_rule_min'] ) {
			$rules[] = sprintf( __( '%s characters long', 'usces' ), $usces_options['system']['member_pass_rule_min'] );
		} else {
			$rules[] = sprintf( __( '%1$s characters and no more than %2$s characters', 'usces' ), $usces_options['system']['member_pass_rule_min'], $usces_options['system']['member_pass_rule_max'] );
			$sep     = __( 'use', 'usces' );
		}
	} else {
		$rules[] = sprintf( __( '%1$s characters and no more than %2$s characters', 'usces' ), $usces_options['system']['member_pass_rule_min'], '30' );
		$sep     = __( 'use', 'usces' );
	}

	$and = __( 'and', 'usces' );

	if ( ! empty( $usces_options['system']['member_pass_rule_digit'] ) ) {
		$rules[] = __( 'numeric character', 'usces' );
	}

	if ( ! empty( $usces_options['system']['member_pass_rule_lowercase'] ) ) {
		$rules[] = __( 'lower-case alphabetics', 'usces' );
	}

	if ( ! empty( $usces_options['system']['member_pass_rule_upercase'] ) ) {
		$rules[] = __( 'upper-case alphabetics', 'usces' );
	}

	if ( ! empty( $usces_options['system']['member_pass_rule_symbol'] ) ) {
		$rules[] = __( 'symbolic character', 'usces' );
	}

	$first = array_shift( $rules );
	$ret   = '';
	if ( 0 === count( $rules ) ) {
		$ret = sprintf( __( 'Password must be at least %s.', 'usces' ), $first );
	} elseif ( 1 === count( $rules ) ) {
		$rule1 = sprintf( __( ' and include one or more %s', 'usces' ), $rules[0] );
		$ret   = sprintf( __( 'Password must be at least %1$s%2$s.', 'usces' ), $first, $rule1 );
	} else {
		$rule2 = '';
		foreach ( $rules as $rule ) {
			if ( '' !== $rule2 ) {
				$rule2 .= __( ',', 'usces' );
			}
			$rule2 .= $rule;
		}
		$rule1 = sprintf( __( ' and include one or more %s', 'usces' ), $rule2 );
		$ret   = sprintf( __( 'Password must be at least %1$s%2$s.', 'usces' ), $first, $rule1 );
	}

	$ret = apply_filters( 'usces_filter_password_policy_message', $ret );
	if ( 'return' === $out ) {
		return $ret;
	}

	echo '<p class="password_policy">' . esc_html( $ret ) . '</p>';
}

/**
 * Formatted tax included amount
 *
 * @param bool   $label_pre Forward label.
 * @param string $label Label.
 * @param string $start_tag Start tag.
 * @param string $end_tag End tag.
 * @param bool   $symbol_pre Forward symbol.
 * @param bool   $symbol_post Backward symbol.
 * @param bool   $seperator_flag Seperator.
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_crform_the_itemPriceCr_taxincluded( $label_pre = true, $label = '', $start_tag = '', $end_tag = '', $symbol_pre = true, $symbol_post = false, $seperator_flag = true, $out = '' ) {
	global $usces;

	if ( ( isset( $usces->options['tax_display'] ) && 'deactivate' === $usces->options['tax_display'] ) || ( isset( $usces->options['tax_mode'] ) && 'include' === $usces->options['tax_mode'] ) ) {
		$res = '';
	} else {
		$price_taxincluded = usces_the_itemPrice_taxincluded( 'return' );
		$price             = esc_html( $usces->get_currency( $price_taxincluded, $symbol_pre, $symbol_post, $seperator_flag ) );
		if ( empty( $label ) ) {
			$label_tag = '<em class="tax tax_inc_label">' . __( 'tax-included', 'usces' ) . '</em>';
		} else {
			$label_tag = '<em class="tax tax_inc_label">' . $label . '</em>';
		}
		if ( empty( $start_tag ) ) {
			$start_tag = '<p class="tax_inc_block">( ';
		}
		if ( $label_pre ) {
			$start_tag = $start_tag . $label_tag;
		}
		if ( empty( $end_tag ) ) {
			$end_tag = ' )</p>';
		}
		if ( true !== $label_pre ) {
			$end_tag = $label_tag . $end_tag;
		}
		$res = apply_filters( 'usces_filter_crform_the_itemPriceCr_taxincluded', $start_tag . $price . $end_tag, $label_pre, $label, $symbol_pre, $symbol_post, $seperator_flag );
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $res );
	} else {
		wel_esc_script_e( $res );
	}
}

/**
 * Tax-included amount
 *
 * @param  int $post_id Post ID.
 * @return float
 */
function usces_itemPrice_taxincluded( $post_id = null ) {
	global $usces;

	if ( null === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}
	$usces_tax = Welcart_Tax::get_instance();

	$skus     = $usces->get_skus( $post_id );
	$skucode  = $skus[0]['code'];
	$skuprice = $skus[0]['price'];
	$tax_rate = $usces_tax->get_sku_tax_rate( $post_id, $skucode );
	$tax      = (float) sprintf( '%.3f', (float) $skuprice * (float) $tax_rate / 100 );
	$tax      = usces_tax_rounding_off( $tax );
	$price    = $skuprice + $tax;
	return $price;
}

/**
 * Tax-included normal amount
 *
 * @param int    $post_id Post ID.
 * @param bool   $label_pre Forward label.
 * @param string $label Label.
 * @param string $start_tag Start tag.
 * @param string $end_tag End tag.
 * @param bool   $symbol_pre Forward symbol.
 * @param bool   $symbol_post Backward symbol.
 * @param bool   $seperator_flag Seperator.
 * @return string
 */
function usces_crform_itemPriceCr_taxincluded( $post_id = null, $label_pre = true, $label = '', $start_tag = '', $end_tag = '', $symbol_pre = true, $symbol_post = false, $seperator_flag = true ) {
	global $usces;

	if ( ( isset( $usces->options['tax_display'] ) && 'deactivate' === $usces->options['tax_display'] ) || ( isset( $usces->options['tax_mode'] ) && 'include' === $usces->options['tax_mode'] ) ) {
		$res = '';
	} else {
		$price_taxincluded = usces_itemPrice_taxincluded( $post_id );
		$price             = esc_html( $usces->get_currency( $price_taxincluded, $symbol_pre, $symbol_post, $seperator_flag ) );
		if ( empty( $label ) ) {
			$label_tag = '<em class="tax tax_inc_label">' . __( 'tax-included', 'usces' ) . '</em>';
		} else {
			$label_tag = '<em class="tax tax_inc_label">' . $label . '</em>';
		}
		if ( empty( $start_tag ) ) {
			$start_tag = '<p class="tax_inc_block">( ';
		}
		if ( $label_pre ) {
			$start_tag = $start_tag . $label_tag;
		}
		if ( empty( $end_tag ) ) {
			$end_tag = ' )</p>';
		}
		if ( true !== $label_pre ) {
			$end_tag = $label_tag . $end_tag;
		}
		$res = apply_filters( 'usces_filter_crform_itemPriceCr_taxincluded', $start_tag . $price . $end_tag, $post_id, $label_pre, $label, $symbol_pre, $symbol_post, $seperator_flag );
	}
	return $res;
}

/**
 * Cart Totals
 *
 * @param string $out Return value or echo.
 * @return string|void
 */
function usces_get_cart_total_rows( $out = '' ) {
	global $usces;

	$res = '';
	if ( isset( $usces->options['tax_display'] ) && 'deactivate' !== $usces->options['tax_display'] ) {
		if ( isset( $usces->options['tax_mode'] ) && 'include' !== $usces->options['tax_mode'] ) {
			$total = usces_total_price( 'return' );
			$tax   = usces_cart_tax( 'return' );
			$res  .= '							<tr>
								<th class="num"></th>
								<th class="thumbnail"></th>
								<th colspan="3" scope="row" class="aright">' . __( 'Total', 'usces' ) . '</th>
								<th class="aright amount">' . usces_crform( $total, true, false, true ) . '</th>
								<th class="stock"></th>
								<th class="action"></th>
							</tr>' . "\n";
			$res  .= '							<tr class="tax">
								<th class="num"></th>
								<th class="thumbnail"></th>
								<th colspan="3" scope="row" class="aright tax">' . __( 'Tax', 'usces' ) . '</th>
								<th class="aright amount tax">' . usces_crform( usces_cart_tax( 'return' ), true, false, true ) . '</th>
								<th class="stock"></th>
								<th class="action"></th>
							</tr>' . "\n";
			$res  .= '							<tr>
								<th class="num"></th>
								<th class="thumbnail"></th>
								<th colspan="3" scope="row" class="aright">' . __( 'total items', 'usces' ) . '</th>
								<th class="aright amount">' . usces_crform( $total + $tax, true, false, true ) . '</th>
								<th class="stock"></th>
								<th class="action"></th>
							</tr>' . "\n";
		} else {
			$total = usces_total_price( 'return' );
			$tax   = usces_internal_tax( array( 'total_items_price' => $total ), 'return' );
			$res  .= '							<tr class="tax">
								<th class="num"></th>
								<th class="thumbnail"></th>
								<th colspan="3" scope="row" class="aright tax">' . __( 'Internal tax', 'usces' ) . '</th>
								<th class="aright amount tax">( ' . usces_crform( $tax, true, false, true ) . ' )</th>
								<th class="stock"></th>
								<th class="action"></th>
							</tr>' . "\n";
			$res  .= '							<tr>
								<th class="num"></th>
								<th class="thumbnail"></th>
								<th colspan="3" scope="row" class="aright">' . __( 'total items', 'usces' ) . '</th>
								<th class="aright amount">' . usces_crform( $total, true, false, true ) . '</th>
								<th class="stock"></th>
								<th class="action"></th>
							</tr>' . "\n";
		}
	}
	if ( 'return' === $out ) {
		return wel_esc_script( $res );
	} else {
		wel_esc_script_e( $res );
	}
}

if ( ! function_exists( 'welcart_assistance_excerpt_length' ) ) {
	/**
	 * Assistance excerpt length
	 *
	 * @param int $length Length.
	 * @return int
	 */
	function welcart_assistance_excerpt_length( $length ) {
		return 10;
	}
}

if ( ! function_exists( 'welcart_assistance_excerpt_mblength' ) ) {
	/**
	 * Assistance excerpt mobile length
	 *
	 * @param int $length Length.
	 * @return int
	 */
	function welcart_assistance_excerpt_mblength( $length ) {
		return 40;
	}
}
