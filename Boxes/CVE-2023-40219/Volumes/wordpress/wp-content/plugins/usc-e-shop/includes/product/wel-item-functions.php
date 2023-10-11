<?php
/**
 * Welcart Item Functions
 *
 * Functions for product related.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main function for returning products, uses the Welcart_Item class.
 *
 * @since 2.2.2
 *
 * @param mixed   $the_item Post object or post ID of the item.
 * @param boolean $cache Switch of cache.
 * @return ProductData|null|false
 */
function wel_get_product( $the_item = false, $cache = true ) {
	$WelItem = new Welcart\ItemData( $the_item, $cache );
	$product = $WelItem->get_product( $cache );
	$post_id = $WelItem->get_id();
	$product = apply_filters( 'wel_get_product', $product, $post_id );
	return $product;
}

/**
 * Function for returning products by item code.
 *
 * @since 2.2.2
 *
 * @param string  $item_code Item code of the item.
 * @param boolean $cache Switch of cache.
 * @return ProductData|null|false
 */
function wel_get_product_by_code( $item_code, $cache = true ) {
	$WelItem = new Welcart\ItemData( null, $cache );
	$post_id = $WelItem->get_id_by_item_code( $item_code, $cache );
	$WelItem->set_id( $post_id );
	$WelItem->set_data( $post_id, $cache );
	$product = $WelItem->get_product( $cache );
	$product = apply_filters( 'usces_filter_get_item', $product, $post_id );
	return $product;
}

/**
 * Function for returning Post ID by item code.
 *
 * @since 2.2.2
 *
 * @param string  $item_code Item code of the item.
 * @param boolean $cache Switch of cache.
 * @return Post ID|null|false
 */
function wel_get_id_by_item_code( $item_code, $cache = true ) {
	$WelItem = new Welcart\ItemData( null, $cache );
	$post_id = $WelItem->get_id_by_item_code( $item_code, $cache );
	return $post_id;
}

/**
 * The function for returning item data, uses the Welcart_Item class.
 *
 * @since 2.2.2
 *
 * @param mixed   $the_item Post ID of the item.
 * @param boolean $cache Switch of cache.
 * @return itemData|null|false
 */
function wel_get_item( $the_item, $cache = true ) {
	$WelItem = new Welcart\ItemData( $the_item, $cache );
	return $WelItem->get_item();
}

/**
 * The function for returning sku data, uses the Welcart_Item class.
 *
 * @since 2.2.2
 *
 * @param mixed   $the_item Post ID of the item.
 * @param string  $sku_code SKU code of the item.
 * @param boolean $cache Switch of cache.
 * @return skuData|null|false
 */
function wel_get_sku( $the_item, $sku_code, $cache = true ) {
	$WelItem = new Welcart\ItemData( $the_item, $cache );
	return $WelItem->get_sku_by_code( $sku_code );
}

/**
 * The function for returning sku data by meta_id, uses the Welcart_Item class.
 *
 * @since 2.6
 *
 * @param int     $meta_id meta_id of the item.
 * @param boolean $cache Switch of cache.
 * @return skuData|null|false
 */
function wel_get_sku_by_id( $meta_id, $cache = true ) {
	global $wpdb;
	$sku_table = $wpdb->prefix . 'usces_skus';

	$res = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$sku_table} WHERE meta_id = %d",
			$meta_id
		),
		ARRAY_A
	);
	$WelItem = new Welcart\ItemData( $res['post_id'], $cache );
	return $WelItem->get_sku_by_id( $meta_id );
}

/**
 * The function for returning all sku data in a product, uses the Welcart_Item class.
 *
 * @since 2.3.2
 *
 * @param mixed   $the_item Post object or post ID of the item.
 * @param string  $sortkey Key for sorting.
 * @param boolean $cache Switch of cache.
 * @return skuDatas|null|false
 */
function wel_get_skus( $the_item = false, $sortkey = 'sort', $cache = true ) {
	$WelItem = new Welcart\ItemData( $the_item, $cache );
	$skus    = $WelItem->get_skus( $sortkey );
	$post_id = $WelItem->get_id();
	$skus    = apply_filters( 'usces_filter_get_skus', $skus, $post_id, $sortkey );
	return $skus;
}

/**
 * The function for returning all option data in a product, uses the Welcart_Item class.
 *
 * @since 2.3.2
 *
 * @param mixed   $the_item Post object or post ID of the item.
 * @param string  $sortkey Key for sorting.
 * @param boolean $cache Switch of cache.
 * @return OptionDatas|null|false
 */
function wel_get_opts( $the_item = false, $sortkey = 'sort', $cache = true ) {
	$WelItem = new Welcart\ItemData( $the_item, $cache );
	$opts    = $WelItem->get_opts( $sortkey, $cache );
	$post_id = $WelItem->get_id();
	$opts    = apply_filters( 'usces_filter_get_opts', $opts, $post_id, $sortkey );

	return $opts;
}

/**
 * The function for returning extra data in a product, uses the Welcart_Item class.
 *
 * @since 2.3.2
 *
 * @param mixed   $the_item Post object or post ID of the item.
 * @param boolean $cache Switch of cache.
 * @return ExtraDatas|null|false
 */
function wel_get_extra_data( $the_item = false, $cache = true ) {
	$WelItem = new Welcart\ItemData( $the_item, $cache );
	$extra   = $WelItem->get_ext();
	$post_id = $WelItem->get_id();
	$extra   = apply_filters( 'usces_filter_get_extra_data', $extra, $extra, $post_id );
	return $extra;
}

/**
 * Check if the item sku is out of stock.
 *
 * @since 2.2.2
 *
 * @param mixed   $the_item Post object or post ID of the item.
 * @param string  $sku_code SKU code of the item.
 * @param boolean $cache Switch of cache.
 * @return boolean Return true if in stock, false otherwise.
 */
function wel_has_stock( $the_item, $sku_code, $cache = true ) {
	global $usces;

	$WelItem = new Welcart\ItemData( $the_item, $cache );
	$product = $WelItem->get_product( $cache );
	$sku     = $WelItem->get_sku_by_code( $sku_code );
	$status  = (int) $sku['stock'];
	$stock   = $sku['stocknum'];
	$iOAp    = $product['itemOrderAcceptable'];

	if ( 1 !== $iOAp ) {

		if ( false !== $stock
			&& ( 0 < (int) $stock || WCUtils::is_blank( $stock ) )
			&& false !== $status
			&& 2 > $status
		) {
			$res = true;
		} else {
			$res = false;
		}
	} else {

		if ( false !== $status && 2 > $status ) {
			$res = true;
		} else {
			$res = false;
		}
	}

	return $res;
}

/**
 * The function for updating core data for the Item object.
 * The data should consist only of the keys you want to update, not the ones you don't want to update.
 * For details of data, refer to the member variable ($data) of ItemData class.
 *
 * @since 3.0.0
 *
 * @param array $data Core data associative array.
 * @param int   $post_id Post ID.
 * @param bool  $delete True if you want to delete before updating.
 * @return true|false Execution result.
 */
function wel_update_item_data( $data, $post_id, $delete = false ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->update_item_data( $data, $delete );
}

/**
 * The function for delete item data.
 *
 * @since 2.7
 *
 * @param int $post_id Post ID.
 * @return boolean Result.
 */
function wel_delete_item_data( $post_id ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->delete_item_data();
}

/**
 * The function for updating sku data by meta_id.
 *
 * @since 3.0.0
 *
 * @param array $meta_id Meta ID.
 * @param int   $post_id Post ID.
 * @param int   $sku SKU data associative array.
 * @return true|false Execution result.
 */
function wel_update_sku_data_by_id( $meta_id, $post_id, $sku ) {
	$WelItem        = new Welcart\ItemData( $post_id, false );
	$sku['meta_id'] = $meta_id;
	return $WelItem->update_sku_data( $sku );
}

/**
 * The function for adding sku data.
 *
 * @since 3.0.0
 *
 * @param int $post_id Post ID.
 * @param int $sku SKU data associative array.
 * @return int New meta_id.
 */
function wel_add_sku_data( $post_id, $sku ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->add_sku_data( $sku );
}

/**
 * The function for deleting sku data.
 *
 * @since 3.0.0
 *
 * @param int $meta_id Meta ID.
 * @param int $post_id Post ID.
 * @return boolean Result.
 */
function wel_delete_sku_data_by_id( $meta_id, $post_id ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->delete_sku_data( $meta_id );
}

/**
 * The function for deleting all sku data in a product.
 *
 * @since 3.0.0
 *
 * @param int $post_id Post ID.
 * @return boolean Result.
 */
function wel_delete_all_sku_data( $post_id ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->delete_all_sku_data();
}

/**
 * The function for updating option data by meta_id.
 *
 * @since 3.0.0
 *
 * @param array $meta_id Meta ID.
 * @param int   $post_id Post ID.
 * @param int   $opt Option data associative array.
 * @return true|false Execution result.
 */
function wel_update_opt_data_by_id( $meta_id, $post_id, $opt ) {
	$WelItem        = new Welcart\ItemData( $post_id, false );
	$opt['meta_id'] = $meta_id;
	return $WelItem->update_opt_data( $opt );
}

/**
 * The function for adding option data.
 *
 * @since 3.0.0
 *
 * @param int $post_id Post ID.
 * @param int $opt Option data associative array.
 * @return int New meta_id.
 */
function wel_add_opt_data( $post_id, $opt ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->add_opt_data( $opt );
}

/**
 * The function for deleting option data.
 *
 * @since 3.0.0
 *
 * @param int $meta_id Meta ID.
 * @param int $post_id Post ID.
 * @return boolean Result.
 */
function wel_delete_opt_data_by_id( $meta_id, $post_id ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->delete_opt_data( $meta_id );
}

/**
 * The function for deleting all option data in a product.
 *
 * @since 3.0.0
 *
 * @param int $post_id Post ID.
 * @return boolean Result.
 */
function wel_delete_all_opt_data( $post_id ) {
	$WelItem = new Welcart\ItemData( $post_id, false );
	return $WelItem->delete_all_opt_data();
}

/**
 * Main product image id.
 *
 * @since 3.0.0
 *
 * @param int     $item_code Item Code.
 * @param boolean $cache Switch of cache.
 * @return int Pict ID.
 */
function wel_get_main_pict_id_by_code( $item_code, $cache = true ) {

	$product = wel_get_product_by_code( $item_code, $cache );
	$pctid   = 0;

	if ( NEW_PRODUCT_IMAGE_REGISTER::$opts['switch_flag'] ) {

		$pctid = isset( $product['itemPicts'][0] ) ? $product['itemPicts'][0] : 0;

	} else {

		global $usces, $wpdb;

		$cache_key = 'wel_main_pictid_by_code_' . $item_code;

		$pctid = wp_cache_get( $cache_key );
		if ( false === $pctid ) {
			if ( ! empty( $item_code ) ) {
				$pctid = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment' LIMIT 1",
						$item_code
					)
				);
				if ( null !== $pctid ) {
					wp_cache_set( $cache_key, $pctid );
				} else {
					$pctid = isset( $product['itemPicts'][0] ) ? $product['itemPicts'][0] : 0;
				}
			}
		}
		if ( null === $pctid ) {
			$pctid = false;
		} else {
			$pctid = (int) apply_filters( 'usces_filter_get_mainpictid', $pctid, $item_code );
		}
	}

	return $pctid;
}

/**
 * Sub product image ids.
 *
 * @since 3.0.0
 *
 * @param int     $item_code Item Code.
 * @param boolean $cache Switch of cache.
 * @return array Pict IDs.
 */
function wel_get_sub_pict_ids_by_code( $item_code, $cache = true ) {

	$product = wel_get_product_by_code( $item_code, $cache );
	$pctids  = array();

	if ( NEW_PRODUCT_IMAGE_REGISTER::$opts['switch_flag'] ) {

		if ( isset( $product['itemPicts'] ) ) {
			if ( is_array( $product['itemPicts'] ) && 0 < count( $product['itemPicts'] ) ) {
				array_shift( $product['itemPicts'] );
			}
			$pctids = $product['itemPicts'];
		}

	} else {

		global $usces, $wpdb;

		$cache_key = 'wel_sub_pictids_' . $item_code;

		$pctids = wp_cache_get( $cache_key );
		if ( false === $pctids ) {
			if ( ! empty( $item_code ) ) {
				if ( ! $usces->options['system']['subimage_rule'] ) {
					$codestr = $wpdb->esc_like( $item_code ) . '-%';
					$query   = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s AND post_title <> %s AND post_type = 'attachment' ORDER BY post_title", $codestr, $item_code );
				} else {
					$codestr  = $wpdb->esc_like( $item_code ) . '--%';
					$codestr2 = $wpdb->esc_like( $item_code ) . '\_\_%';
					$query    = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE (post_title LIKE %s OR post_title LIKE %s) AND post_type = 'attachment' ORDER BY post_title", $codestr, $codestr2 );
				}
				$pctids = $wpdb->get_col( $query );
				if ( null !== $pctids ) {
					wp_cache_set( $cache_key, $pctids );
				} else {
					if ( is_array( $product['itemPicts'] ) && 0 < count( $product['itemPicts'] ) ) {
						array_shift( $product['itemPicts'] );
					}
					$pctids = $product['itemPicts'];
				}
			}
		}
		if ( null === $pctids ) {
			$pctids = false;
		} else {
			$pctids = (array) apply_filters( 'usces_filter_get_pictids', $pctids, $item_code );
		}
	}

	return $pctids;
}

/**
 * Main product image id.
 *
 * @since 3.0.0
 *
 * @param int     $post_id item id.
 * @param boolean $cache Switch of cache.
 * @return int Pict ID.
 */
function wel_get_main_pict_id( $post_id, $cache = true ) {

	if ( 0 === $post_id ) {
		return false;
	}

	$product   = wel_get_product( $post_id, $cache );
	$item_code = isset( $product['itemCode'] ) ? $product['itemCode'] : '';
	$pctid     = isset( $product['itemPicts'][0] ) ? $product['itemPicts'][0] : 0;

	if ( NEW_PRODUCT_IMAGE_REGISTER::$opts['switch_flag'] ) {
			$pctid = isset( $product['itemPicts'][0] ) ? $product['itemPicts'][0] : 0;

	} else {

		global $wpdb;

		$cache_key = 'wel_main_pictid_by_code_' . $item_code;

		$pctid = wp_cache_get( $cache_key );
		if ( false === $pctid ) {
			if ( ! empty( $item_code ) ) {
				$pctid = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment' LIMIT 1",
						$item_code
					)
				);
				if ( null !== $pctid && $cache ) {
					wp_cache_set( $cache_key, $pctid );
				}
			}
		}
		if ( null === $pctid ) {
			$pctid = false;
		} else {
			$pctid = (int) apply_filters( 'usces_filter_get_mainpictid', $pctid, $item_code );
		}
	}

	return $pctid;
}

/**
 * Sub product image ids.
 *
 * @since 3.0.0
 *
 * @param int     $post_id Item id.
 * @param boolean $cache Switch of cache.
 * @return array Pict IDs.
 */
function wel_get_sub_pict_ids( $post_id, $cache = true ) {

	if ( 0 === $post_id ) {
		return false;
	}
	$pctids    = array();
	$product   = wel_get_product( $post_id, $cache );
	$item_code = isset( $product['itemCode'] ) ? $product['itemCode'] : '';

	if ( NEW_PRODUCT_IMAGE_REGISTER::$opts['switch_flag'] ) {

		if ( isset( $product['itemPicts'] ) ) {
			if ( is_array( $product['itemPicts'] ) && 0 < count( $product['itemPicts'] ) ) {
				array_shift( $product['itemPicts'] );
			}
			$pctids = $product['itemPicts'];
		}

	} else {

		global $usces, $wpdb;

		$cache_key = 'wel_sub_pictids_' . $item_code;

		$pctids = wp_cache_get( $cache_key );
		if ( false === $pctids ) {
			if ( ! empty( $item_code ) ) {
				if ( ! $usces->options['system']['subimage_rule'] ) {
					$codestr = $wpdb->esc_like( $item_code ) . '-%';
					$query   = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s AND post_title <> %s AND post_type = 'attachment' ORDER BY post_title", $codestr, $item_code );
				} else {
					$codestr  = $wpdb->esc_like( $item_code ) . '--%';
					$codestr2 = $wpdb->esc_like( $item_code ) . '\_\_%';
					$query    = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE (post_title LIKE %s OR post_title LIKE %s) AND post_type = 'attachment' ORDER BY post_title", $codestr, $codestr2 );
				}
				$pctids = $wpdb->get_col( $query );
				if ( null !== $pctids && $cache ) {
					wp_cache_set( $cache_key, $pctids );
				}
			}
		}
		if ( null === $pctids ) {
			$pctids = false;
		} else {
			$pctids = (array) apply_filters( 'usces_filter_get_pictids', $pctids, $item_code );
		}
	}
	return $pctids;
}

/**
 * List all item pict ID by item post id.
 *
 * @param integer $post_id item post id.
 * @param boolean $cache Switch of cache.
 * @return array
 */
function wel_get_item_pict_ids( $post_id, $cache = false ) {
	$arr_pict_ids = array();
	$main_pict_id = (int) wel_get_main_pict_id( $post_id, $cache );

	if ( false !== $main_pict_id && 0 < $main_pict_id ) {
		$arr_pict_ids[] = $main_pict_id;
	}

	$sub_item_pict_ids = wel_get_sub_pict_ids( $post_id, $cache );

	if ( is_array( $sub_item_pict_ids ) && ! empty( $sub_item_pict_ids ) ) {
		$arr_pict_ids = array_merge( $arr_pict_ids, $sub_item_pict_ids );
	}

	$result = array();
	foreach ( $arr_pict_ids as $pict_id ) {
		if ( 0 < $pict_id ) {
			$result[] = (int) $pict_id;
		}
	}
	return $result;
}

/**
 * Get image id by title.
 *
 * @since 3.0.0
 *
 * @param integer $post_id item post id.
 * @param string  $title Image post_title.
 * @param boolean $cache Switch of cache.
 * @return array $pctid Pict ID.
 */
function wel_pict_id_by_title( $post_id, $title, $cache = true ) {
	if ( empty( $title ) ) {
		return false;
	}

	global $usces, $wpdb;

	$cache_key = 'wel_pict_id_by_title_' . $post_id . $title;

	$pctid = wp_cache_get( $cache_key );
	if ( false === $pctid ) {

		$candidate = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment' ORDER BY post_title",
				$title
			)
		);
		if ( ! empty( $candidate ) ) {
			$item_ids = wel_get_item_pict_ids( $post_id, $cache );
			foreach ( $candidate as $can ) {
				if ( in_array( $can, $item_ids ) ) {
					$pctid = $can;
					break;
				}
			}
		}
		if ( false !== $pctid && $cache ) {
			wp_cache_set( $cache_key, $pctid );
		}
	}
	return $pctid;
}
