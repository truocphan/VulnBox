<?php
/**
 * Utility
 *
 * @package Welcart
 */

/**
 * Upgrade 143
 *
 * @return bool
 */
function usces_upgrade_143() {

	$upgrade = (int) get_option( 'usces_upgrade3' );
	if ( 0 !== $upgrade ) {
		return true;
	}

	global $wpdb;
	$table = $wpdb->prefix . 'usces_ordercart';
	$wpdb->query( "ALTER TABLE $table CHANGE  `cprice`  `cprice` DECIMAL( 15, 2 ) NULL DEFAULT NULL" );
	$wpdb->query( "ALTER TABLE $table CHANGE  `price`  `price` DECIMAL( 15, 2 ) NULL DEFAULT NULL" );
	$wpdb->query( "ALTER TABLE $table CHANGE  `tax`  `tax` DECIMAL( 13, 2 ) NULL DEFAULT NULL" );

	$upgrade += USCES_UP143;
	update_option( 'usces_upgrade3', $upgrade );
	usces_log( 'USCES_UP143 : Completed', 'db' );

	return true;
}

/**
 * Upgrade 141
 *
 * @return bool
 */
function usces_upgrade_141() {

	$upgrade = (int) get_option( 'usces_upgrade2' );
	if ( 3 !== $upgrade ) {
		return true;
	}

	global $wpdb, $usces;
	$rets = array();

	$options = get_option( 'usces' );
	if ( empty( $options['tax_rate'] ) ) {
		$options['tax_mode'] = 'include';
	} else {
		$options['tax_mode'] = 'exclude';
	}
	$options['tax_target'] = 'all';
	update_option( 'usces', $options );

	$order_table     = $wpdb->prefix . 'usces_order';
	$cart_table      = $wpdb->prefix . 'usces_ordercart';
	$cart_meta_table = $wpdb->prefix . 'usces_ordercart_meta';

	$query   = "SELECT ID, order_cart, order_condition FROM $order_table";
	$results = $wpdb->get_results( $query );
	if ( $results ) {
		foreach ( $results as $order ) {

			$query    = $wpdb->prepare( "SELECT order_id FROM $cart_table WHERE order_id = %d", $order->ID );
			$order_id = $wpdb->get_var( $query );
			if ( $order_id == $order->ID ) {
				continue;
			}
			$condition = maybe_unserialize( $order->order_condition );
			if ( ! isset( $condition['tax_mode'] ) ) {
				$condition['tax_mode'] = $options['tax_mode'];
			}
			if ( ! isset( $condition['tax_rate'] ) ) {
				$condition['tax_rate'] = (int) $options['tax_rate'];
			}
			if ( ! isset( $condition['tax_target'] ) ) {
				$condition['tax_target'] = $options['tax_target'];
			}

			$cart = unserialize( $order->order_cart );
			foreach ( (array) $cart as $row_index => $value ) {
				$product     = wel_get_product( $value['post_id'] );
				$item_code   = $product['itemCode'];
				$item_name   = $product['itemName'];
				$skus        = $usces->get_skus( $value['post_id'], 'code' );
				$sku_code    = urldecode( $value['sku'] );
				$sku_encoded = $value['sku'];
				$sku         = isset( $skus[ $sku_code ] ) ? $skus[ $sku_code ] : array( 'name' => 'notfound', 'cprice' => 0, 'unit' => '' );
				$tax         = 0;
				$query       = $wpdb->prepare(
					"INSERT INTO $cart_table 
					(
					order_id, group_id, row_index, 
					post_id, item_code, item_name, 
					sku_code, sku_name, cprice, price, quantity, unit, 
					tax, destination_id, cart_serial 
					) VALUES (
					%d, %d, %d, 
					%d, %s, %s, 
					%s, %s, %f, %f, %d, %s, 
					%f, %d, %s 
					)",
					$order->ID, 0, $row_index,
					$value['post_id'], $item_code, $item_name,
					$sku_code, $sku['name'], $sku['cprice'], $value['price'], $value['quantity'], $sku['unit'],
					$tax, NULL, $value['serial']
				);
				$wpdb->query( $query );

				$cart_id = $wpdb->insert_id;
				if ( $cart_id ) {
					if ( $value['options'] ) {
						foreach ( (array) $value['options'] as $okey => $ovalue ) {
							$okey = urldecode( $okey );
							if ( is_array( $ovalue ) ) {
								$ovalue = serialize( $ovalue );
							} else {
								$ovalue = urldecode( $ovalue );
							}
							$oquery = $wpdb->prepare(
								"INSERT INTO $cart_meta_table 
								( 
								cart_id, meta_type, meta_key, meta_value 
								) VALUES (
								%d, 'option', %s, %s
								)",
								$cart_id, $okey, $ovalue
							);
							$wpdb->query( $oquery );
						}
					}
					if ( $value['advance'] ) {
						foreach ( (array) $value['advance'] as $akey => $avalue ) {
							$advance = maybe_unserialize( $avalue );
							if ( is_array( $advance ) ) {
								$post_id = $value['post_id'];
								if ( is_array( $advance[ $post_id ][ $sku_encoded ] ) ) {
									$akeys = array_keys( $advance[ $post_id ][ $sku_encoded ] );
									foreach ( (array) $akeys as $akey ) {
										$avalue = serialize( $advance[ $post_id ][ $sku_encoded ][ $akey ] );
										$aquery = $wpdb->prepare(
											"INSERT INTO $cart_meta_table 
											( 
											cart_id, meta_type, meta_key, meta_value 
											) VALUES (
											%d, 'advance', %s, %s
											)",
											$cart_id, $akey, $avalue
										);
										$wpdb->query( $aquery );
									}
								} else {
									$akeys  = array_keys( $advance );
									$akey   = ( empty( $akeys[0] ) ) ? 'advance' : $akeys[0];
									$avalue = serialize( $advance );
									$aquery = $wpdb->prepare(
										"INSERT INTO $cart_meta_table 
										( 
										cart_id, meta_type, meta_key, meta_value 
										) VALUES (
										%d, 'advance', %s, %s
										)",
										$cart_id, $akey, $avalue
									);
									$wpdb->query( $aquery );
								}
							} else {
								$avalue = urldecode( $avalue );
								$aquery = $wpdb->prepare(
									"INSERT INTO $cart_meta_table 
									( 
									cart_id, meta_type, meta_key, meta_value 
									) VALUES (
									%d, 'advance', 'advance', %s
									)",
									$cart_id, $avalue
								);
								$wpdb->query( $aquery );
							}
						}
					}
				}
			}

			$upquery = $wpdb->prepare(
				"UPDATE $order_table SET order_condition = %s WHERE ID = %d",
				serialize( $condition ), $order->ID
			);
			$wpdb->query( $upquery );
		}
	}
	usces_log( 'USCES_UP141 : Completed : from ' . $upgrade, 'db' );
	$upgrade += USCES_UP141;
	update_option( 'usces_upgrade2', $upgrade );

	return true;
}

/**
 * Upgrade 14
 *
 * @return bool
 */
function usces_upgrade_14() {

	$upgrade = (int) get_option( 'usces_upgrade2' );
	if ( 0 < $upgrade ) {
		return true;
	}

	global $wpdb, $usces;
	$rets = array();

	$options = get_option( 'usces' );
	if ( empty( $options['tax_rate'] ) ) {
		$options['tax_mode'] = 'include';
	} else {
		$options['tax_mode'] = 'exclude';
	}
	$options['tax_target'] = 'all';
	update_option( 'usces', $options );

	$order_table     = $wpdb->prefix . 'usces_order';
	$cart_table      = $wpdb->prefix . 'usces_ordercart';
	$cart_meta_table = $wpdb->prefix . 'usces_ordercart_meta';

	$query   = "SELECT ID, order_cart, order_condition FROM $order_table";
	$results = $wpdb->get_results( $query );
	if ( $results ) {
		foreach ( $results as $order ) {
			$condition = maybe_unserialize( $order->order_condition );
			if ( ! isset( $condition['tax_mode'] ) ) {
				$condition['tax_mode'] = $options['tax_mode'];
			}
			if ( ! isset( $condition['tax_rate'] ) ) {
				$condition['tax_rate'] = (int) $options['tax_rate'];
			}
			if ( ! isset( $condition['tax_target'] ) ) {
				$condition['tax_target'] = $options['tax_target'];
			}

			$cart = unserialize( $order->order_cart );
			foreach ( (array) $cart as $row_index => $value ) {
				$product     = wel_get_product( $value['post_id'] );
				$item_code   = $product['itemCode'];
				$item_name   = $product['itemName'];
				$skus        = $usces->get_skus( $value['post_id'], 'code' );
				$sku_code    = urldecode( $value['sku'] );
				$sku_encoded = $value['sku'];
				$sku         = isset( $skus[ $sku_code ] ) ? $skus[ $sku_code ] : array( 'name' => 'notfound', 'cprice' => 0, 'unit' => '' );
				$tax         = 0;
				$query       = $wpdb->prepare(
					"INSERT INTO $cart_table 
					(
					order_id, group_id, row_index, 
					post_id, item_code, item_name, 
					sku_code, sku_name, cprice, price, quantity, unit, 
					tax, destination_id, cart_serial 
					) VALUES (
					%d, %d, %d, 
					%d, %s, %s, 
					%s, %s, %f, %f, %d, %s, 
					%f, %d, %s 
					)",
					$order->ID, 0, $row_index,
					$value['post_id'], $item_code, $item_name,
					$sku_code, $sku['name'], $sku['cprice'], $value['price'], $value['quantity'], $sku['unit'],
					$tax, NULL, $value['serial']
				);
				$wpdb->query( $query );

				$cart_id = $wpdb->insert_id;
				if ( $cart_id ) {
					if ( $value['options'] ) {
						foreach ( (array) $value['options'] as $okey => $ovalue ) {
							$okey = urldecode( $okey );
							if ( is_array( $ovalue ) ) {
								$ovalue = serialize( $ovalue );
							} else {
								$ovalue = urldecode( $ovalue );
							}
							$oquery = $wpdb->prepare(
								"INSERT INTO $cart_meta_table 
								( 
								cart_id, meta_type, meta_key, meta_value 
								) VALUES (
								%d, 'option', %s, %s
								)",
								$cart_id, $okey, $ovalue
							);
							$wpdb->query( $oquery );
						}
					}
					if ( $value['advance'] ) {
						foreach ( (array) $value['advance'] as $akey => $avalue ) {
							$advance = maybe_unserialize( $avalue );
							if ( is_array( $advance ) ) {
								$post_id = $value['post_id'];
								if ( is_array( $advance[ $post_id ][ $sku_encoded ] ) ) {
									$akeys = array_keys( $advance[ $post_id ][ $sku_encoded ] );
									foreach ( (array) $akeys as $akey ) {
										$avalue = serialize( $advance[ $post_id ][ $sku_encoded ][ $akey ] );
										$aquery = $wpdb->prepare(
											"INSERT INTO $cart_meta_table 
											( 
											cart_id, meta_type, meta_key, meta_value 
											) VALUES (
											%d, 'advance', %s, %s
											)",
											$cart_id, $akey, $avalue
										);
										$wpdb->query( $aquery );
									}
								} else {
									$akeys  = array_keys( $advance );
									$akey   = ( empty( $akeys[0] ) ) ? 'advance' : $akeys[0];
									$avalue = serialize( $advance );
									$aquery = $wpdb->prepare(
										"INSERT INTO $cart_meta_table 
										( 
										cart_id, meta_type, meta_key, meta_value 
										) VALUES (
										%d, 'advance', %s, %s
										)",
										$cart_id, $akey, $avalue
									);
									$wpdb->query( $aquery );
								}
							} else {
								$avalue = urldecode( $avalue );
								$aquery = $wpdb->prepare(
									"INSERT INTO $cart_meta_table 
									( 
									cart_id, meta_type, meta_key, meta_value 
									) VALUES (
									%d, 'advance', 'advance', %s
									)",
									$cart_id, $avalue
								);
								$wpdb->query( $aquery );
							}
						}
					}
				}
			}

			$upquery = $wpdb->prepare(
				"UPDATE $order_table SET order_condition = %s WHERE ID = %d",
				serialize( $condition ), $order->ID
			);
			$wpdb->query( $upquery );
		}
	}
	usces_log( 'USCES_UP14 : Completed : from ' . $upgrade, 'db' );
	$upgrade += USCES_UP14;
	update_option( 'usces_upgrade2', $upgrade );

	return true;
}

/**
 * Upgrade 07
 *
 * @return bool
 */
function usces_upgrade_07() {

	$upgrade = (int) get_option( 'usces_upgrade' );
	if ( 0 !== $upgrade ) {
		return true;
	}

	global $wpdb;
	$rets = array();

	$tablename = $wpdb->prefix . 'postmeta';
	$mquery    = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'iopt_', '_iopt_') WHERE meta_key LIKE 'iopt_%'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'isku_', '_isku_') WHERE meta_key LIKE 'isku_%'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemCode', '_itemCode') WHERE meta_key LIKE 'itemCode'";
	if( $wpdb->query( $mquery ) )
		$rets[] = 1;

	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemName', '_itemName') WHERE meta_key LIKE 'itemName'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemRestriction', '_itemRestriction') WHERE meta_key LIKE 'itemRestriction'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemPointrate', '_itemPointrate') WHERE meta_key LIKE 'itemPointrate'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemGpNum1', '_itemGpNum1') WHERE meta_key LIKE 'itemGpNum1'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemGpDis1', '_itemGpDis1') WHERE meta_key LIKE 'itemGpDis1'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemGpNum2', '_itemGpNum2') WHERE meta_key LIKE 'itemGpNum2'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemGpDis2', '_itemGpDis2') WHERE meta_key LIKE 'itemGpDis2'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemGpDis3', '_itemGpDis3') WHERE meta_key LIKE 'itemGpDis3'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemGpNum3', '_itemGpNum3') WHERE meta_key LIKE 'itemGpNum3'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemShipping', '_itemShipping') WHERE meta_key LIKE 'itemShipping'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemDeliveryMethod', '_itemDeliveryMethod') WHERE meta_key LIKE 'itemDeliveryMethod'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemShippingCharge', '_itemShippingCharge') WHERE meta_key LIKE 'itemShippingCharge'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	$mquery = "UPDATE $tablename SET meta_key = REPLACE(meta_key, 'itemIndividualSCharge', '_itemIndividualSCharge') WHERE meta_key LIKE 'itemIndividualSCharge'";
	if ( $wpdb->query( $mquery ) ) {
		$rets[] = 1;
	}
	usces_log( 'USCES_UP07 : ' . print_r( $rets, true ), 'db' );
	$upgrade += USCES_UP07;
	update_option( 'usces_upgrade', $upgrade );
	return true;
}

/**
 * Upgrade 11
 *
 * @return bool
 */
function usces_upgrade_11() {

	$options = get_option( 'usces' );
	$upgrade = (int) get_option( 'usces_upgrade' );
	if ( 1 !== $upgrade ) {
		return true;
	}

	global $wpdb;
	$rets = array();

	/* ITEM SKU DATA */
	$sort = (int) $options['system']['orderby_itemsku'];
	if ( $sort ) {
		$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key <> '_isku_' AND meta_key LIKE '_isku_%' ORDER BY post_id, meta_key";
	} else {
		$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key <> '_isku_' AND meta_key LIKE '_isku_%' ORDER BY post_id, meta_id";
	}
	$res = $wpdb->get_results( $query, ARRAY_A );

	$conclusion  = true;
	$pre_post_id = 0;
	$sort_id     = 0;
	$check_code  = array();
	$dep_num     = array();
	foreach ( (array) $res as $metarow ) {
		$meta_value       = unserialize( $metarow['meta_value'] );
		$newvalue         = array();
		$newvalue['code'] = substr( $metarow['meta_key'], 6 );
		if ( $pre_post_id == $metarow['post_id'] ) {
			if ( in_array( $newvalue['code'], $check_code ) ) {
				$newvalue['code'] .= 'dupricate_' . (int) $dep_num[ $newvalue['code'] ];
				$dep_num[ $newvalue['code'] ]++;
			}
		} else {
			$check_code = array();
			$dep_num    = array();
			$sort_id    = 0;
		}
		foreach ( (array) $meta_value as $k => $v ) {
			switch ( $k ) {
				case 'disp':
					$newvalue['name'] = $v;
					break;
				case 'cprice':
					$newvalue['cprice'] = $v;
					break;
				case 'price':
					$newvalue['price'] = $v;
					break;
				case 'unit':
					$newvalue['unit'] = $v;
					break;
				case 'zaikonum':
					$newvalue['stocknum'] = $v;
					break;
				case 'zaiko':
					$newvalue['stock'] = $v;
					break;
				case 'gptekiyo':
					$newvalue['gp'] = $v;
					break;
				case 'charging_type':
					break;
				default:
					$newvalue[ $k ] = $v;
			}
		}
		$newvalue['sort'] = $sort_id;

		$id           = usces_add_sku( $metarow['post_id'], $newvalue, false );
		$pre_post_id  = $metarow['post_id'];
		$check_code[] = $newvalue['code'];
		$sort_id++;

		$res_key = $metarow['post_id'] . '_' . $newvalue['code'];
		if ( $id ) {
			delete_post_meta( $metarow['post_id'], $metarow['meta_key'] );
			$rets['sku'][ $res_key ] = 1;
		} else {
			$rets['sku'][ $res_key ] = 0;
			usces_log( 'meta_id ' . $metarow['meta_id'] . ' : ' . __( 'This SKU-data has not been rebuilt.', 'usces' ), 'database_error.log' );
		}
	}

	/* ITEM OPTION DATA */
	$sort = (int) $options['system']['orderby_itemopt'];
	if ( $sort ) {
		$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key <> '_iopt_' AND meta_key LIKE '_iopt_%' ORDER BY post_id, meta_key";
	} else {
		$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key <> '_iopt_' AND meta_key LIKE '_iopt_%' ORDER BY post_id, meta_id";
	}
	$res = $wpdb->get_results( $query, ARRAY_A );

	$conclusion  = true;
	$pre_post_id = 0;
	$sort_id     = 0;
	$check_code  = array();
	$dep_num     = array();
	foreach ( (array) $res as $metarow ) {
		$meta_value       = unserialize( $metarow['meta_value'] );
		$newvalue         = array();
		$newvalue['name'] = substr( $metarow['meta_key'], 6 );
		if ( $pre_post_id == $metarow['post_id'] ) {
			if ( in_array( $newvalue['name'], $check_code ) ) {
				$newvalue['name'] .= 'dupricate_' . (int) $dep_num[ $newvalue['name'] ];
				$dep_num[ $newvalue['name'] ]++;
			}
		} else {
			$check_code = array();
			$dep_num    = array();
			$sort_id    = 0;
		}
		foreach ( (array) $meta_value as $k => $v ) {
			switch ( $k ) {
				case 'means':
					$newvalue['means'] = $v;
					break;
				case 'essential':
					$newvalue['essential'] = $v;
					break;
				case 'value':
					if ( is_array( $v ) ) {
						$nov = '';
						foreach ( (array) $v as $vs ) {
							if ( ! WCUtils::is_blank( $vs ) ) {
								$nov .= $vs . "\n";
							}
						}
						$newvalue['value'] = trim( $nov );
					} else {
						$newvalue['value'] = trim( $v );
					}
					break;
				default:
					$newvalue[ $k ] = trim( $v );
			}
		}
		$newvalue['sort'] = $sort_id;

		$id           = usces_add_opt( $metarow['post_id'], $newvalue, false );
		$pre_post_id  = $metarow['post_id'];
		$check_code[] = $newvalue['name'];
		$sort_id++;

		$res_key = $metarow['post_id'] . '_' . $newvalue['name'];
		if ( $id ) {
			delete_post_meta( $metarow['post_id'], $metarow['meta_key'] );
			$rets['opt'][ $res_key ] = 1;
		} else {
			$rets['opt'][ $res_key ] = 0;
			usces_log( 'meta_id ' . $metarow['meta_id'] . ' : ' . __( 'This Item-Option-data has not been rebuilt.', 'usces' ), 'database_error.log' );
		}
	}

	/* PAYMENT METHOD DATA */
	$payment = get_option( 'usces_payment_method' );
	if ( empty( $payment ) ) {

		$options     = get_option( 'usces' );
		$old_payment = isset( $options['payment_method'] ) ? $options['payment_method'] : '';
		usces_log( 'old_payment : ' . print_r( $old_payment, true ), 'database_error.log' );
		if ( ! empty( $old_payment ) && is_array( $old_payment ) ) {
			foreach ( $old_payment as $key => $value ) {
				$res_key = $key . '_' . $value['name'];
				$id      = usces_add_system_option( 'usces_payment_method', $value );
				if ( $id ) {
					$rets['payment'][ $res_key ] = 1;
				} else {
					$rets['payment'][ $res_key ] = 0;
					usces_log( 'payment_method ' . $value['name'] . ' : ' . __( 'This Payment-Method-data has not been rebuilt.', 'usces' ), 'database_error.log' );
				}
			}
		}
	}

	usces_log( 'USCES_UP11 : ' . print_r( $rets, true ), 'db' );

	$upgrade += USCES_UP11;
	update_option( 'usces_upgrade', $upgrade );

	return true;
}

/**
 * Output log data.
 *
 * @param mixed  $log Log.
 * @param string $file Output.
 * @param string $type Type.
 * @param string $key Key.
 */
function usces_log( $log, $file, $type = '', $key = '' ) {
	global $wpdb;

	if ( 'db' == $file ) {

		$table_name = $wpdb->prefix . 'usces_log';
		$query      = $wpdb->prepare( "INSERT INTO $table_name ( datetime, log, log_type, log_key ) VALUES( %s, %s, %s, %s )", current_time( 'mysql' ), $log, $type, $key );
		$wpdb->query( $query );

	} else {

		$logdir    = USCES_PLUGIN_DIR . '/logs';
		$file_path = $logdir . '/' . $file;
		if ( is_dir( $file_path ) ) {
			return;
		}

		$htaccess_path = $logdir . '/.htaccess';
		if ( ! file_exists( $htaccess_path ) ) {
			if ( $ht = @fopen( $htaccess_path, 'w' ) ) {
				fwrite( $ht, 'Order deny,allow' . "\n" );
				fwrite( $ht, 'Deny from all' . "\n" );
				fclose( $ht );
			}
		}

		if ( is_writable( $file_path ) || ( 'db' != $file && 'test' == $type ) ) {
			$log = date( '[Y-m-d H:i:s]', current_time( 'timestamp' ) ) . "\t" . $log . "\n";
			if ( $fp = @fopen( $file_path, 'a' ) ) {
				fwrite( $fp, $log );
				fclose( $fp );
			}
		} else {

			$type       = 'unwritable';
			$key        = $file;
			$table_name = $wpdb->prefix . 'usces_log';
			$query      = $wpdb->prepare( "INSERT INTO $table_name ( datetime, log, log_type, log_key ) VALUES( %s, %s, %s, %s )", current_time( 'mysql' ), $log, $type, $key );
			$wpdb->query( $query );

		}
	}
}

/**
 * Convenience store name
 *
 * @param string $code Convenience store code.
 * @return string
 */
function usces_get_conv_name( $code ) {
	switch ( $code ) {
		case 'D001':
			$name = 'セブン-イレブン';
			break;
		case '010':
			$name = 'セブンイレブン';
			break;
		case 'D002':
		case '020':
			$name = 'ローソン';
			break;
		case 'D015':
		case '760':
			$name = 'セイコーマート';
			break;
		case 'D405':
			$name = 'ペイジー';
			break;
		case 'D003':
			$name = 'サンクス';
			break;
		case 'D004':
			$name = 'サークルK';
			break;
		case 'D040':
			$name = 'サークルKサンクス';
			break;
		case 'D005':
		case '080':
		case 'D050':
			$name = 'ミニストップ';
			break;
		case 'D010':
		case 'D060':
			$name = 'デイリーヤマザキ';
			break;
		case 'D011':
			$name = 'ヤマザキデイリーストア';
			break;
		case 'D030':
		case '030':
			$name = 'ファミリーマート';
			break;
		case 'D401':
			$name = '楽天Ｅｄｙ';
			break;
		case 'D404':
			$name = '楽天銀行';
			break;
		case 'D406':
			$name = 'ジャパネット銀行';
			break;
		case 'D407':
			$name = 'Suicaインターネットサービス';
			break;
		case 'D451':
			$name = 'ウェブマネー';
			break;
		case 'D452':
			$name = 'ビットキャッシュ';
			break;
		case 'D453':
			$name = 'JCBプレモカード';
			break;
		case 'P901':
			$name = 'コンビニ払込票';
			break;
		case 'P902':
			$name = 'コンビニ払込票（郵便振替対応）';
			break;
		case '050':
			$name = 'デイリーヤマザキ・ヤマザキデイリーストア・タイムリー';
			break;
		case '060':
			$name = 'サークルK・サンクス';
			break;
		case '110':
			$name = 'am/pm';
			break;
		default:
			$name = '';
	}
	return $name;
}

/**
 * Payment Method Details
 *
 * @param array $usces_entries Entry data.
 * @return string
 */
function usces_payment_detail( $usces_entries ) {
	global $usces;

	$payments    = usces_get_payments_by_name( $usces_entries['order']['payment_name'] );
	$acting_flag = ( 'acting' == $payments['settlement'] ) ? $payments['module'] : $payments['settlement'];
	$str         = '';
	switch ( $acting_flag ) {
		case 'paypal.php':
			break;

		case 'epsilon.php':
			break;

		case 'acting_remise_card':
			if ( isset( $usces_entries['order']['div'] ) ) {
				switch ( $usces_entries['order']['div'] ) {
					case '0':
						$str = '　一括払い';
						break;
					case '1':
						$str = '　分割（2回）';
						break;
					case '2':
						$str = '　分割（リボ払い）';
						break;
				}
			}
			break;

		case 'acting_remise_conv':
			break;

		case 'transferAdvance':
			if ( ! empty( $usces->options['tatransfer_limit'] ) ) {
				$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $usces->options['tatransfer_limit'] ) . __( ')', 'usces' );
				$str            = apply_filters( 'usces_filter_transferAdvance_payment_limit', $payment_detail, $usces->options['tatransfer_limit'] );
			}
			break;

		case 'transferDeferred':
			if ( ! empty( $usces->options['tatransfer_limit'] ) ) {
				$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from after the item arrives.', 'usces' ), $usces->options['tatransfer_limit'] ) . __( ')', 'usces' );
				$str            = apply_filters( 'usces_filter_transferDeferred_payment_limit', $payment_detail, $usces->options['tatransfer_limit'] );
			}
			break;
	}

	$str = apply_filters( 'usces_filter_payment_detail', $str, $usces_entries );
	return $str;
}

/**
 * Payment Method Details
 *
 * @param array $order_data Order data.
 * @return string
 */
function usces_payment_detail_confirm( $order_data ) {
	global $usces;

	$payments    = usces_get_payments_by_name( $order_data['order_payment_name'] );
	$acting_flag = ( 'acting' == $payments['settlement'] ) ? $payments['module'] : $payments['settlement'];
	$str         = '';
	switch ( $acting_flag ) {
		case 'paypal.php':
			break;
		case 'epsilon.php':
			break;
		case 'acting_remise_card':
			break;
		case 'acting_remise_conv':
			break;

		case 'transferAdvance':
			if ( ! empty( $usces->options['tatransfer_limit'] ) && $usces->is_status( 'noreceipt', $order_data['order_status'] ) ) {
				$payment_detail = "\n" . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $usces->options['tatransfer_limit'] );
				$str            = apply_filters( 'usces_filter_transferAdvance_payment_limit_confirm', $payment_detail, $usces->options['tatransfer_limit'] );
			}
			break;

		case 'transferDeferred':
			if ( ! empty( $usces->options['tatransfer_limit'] ) && $usces->is_status( 'noreceipt', $order_data['order_status'] ) ) {
				$payment_detail = "\n" . sprintf( __( 'Payment is valid for %s days from after the item arrives.', 'usces' ), $usces->options['tatransfer_limit'] );
				$str            = apply_filters( 'usces_filter_transferDeferred_payment_limit_confirm', $payment_detail, $usces->options['tatransfer_limit'] );
			}
			break;
	}

	$str = apply_filters( 'usces_filter_payment_detail_confirm', $str, $order_data );
	return $str;
}

/**
 * Check custom order
 *
 * @param string $mes Message.
 * @return string
 */
function usces_filter_delivery_check_custom_order( $mes ) {

	$meta = usces_has_custom_field_meta( 'order' );
	if ( is_array( $meta ) ) {
		unset( $_SESSION['usces_entry']['custom_order'] );
		if ( isset( $_POST['custom_order'] ) ) {
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
	}

	foreach ( $meta as $key => $entry ) {
		$essential = $entry['essential'];
		if ( 1 === (int) $essential ) {
			$name  = $entry['name'];
			$means = $entry['means'];
			if ( 2 === (int) $means ) { /* Text */
				if ( isset( $_POST['custom_order'][ $key ] ) && WCUtils::is_blank( $_POST['custom_order'][ $key ] ) ) {
					$mes .= sprintf( __( 'Input the %s', 'usces' ), $name ) . '<br />';
				}
			} else {
				if ( ! isset( $_POST['custom_order'][ $key ] ) || '#NONE#' == $_POST['custom_order'][ $key ] ) {
					$mes .= sprintf( __( 'Chose the %s', 'usces' ), $name ) . '<br />';
				}
			}
		}
	}

	return $mes;
}

/**
 * Check custom customer
 *
 * @param string $mes Message.
 * @return string
 */
function usces_filter_customer_check_custom_customer( $mes ) {

	$meta = usces_has_custom_field_meta( 'customer' );
	foreach ( $meta as $key => $entry ) {
		$essential = $entry['essential'];
		if ( 1 === (int) $essential ) {
			$name  = $entry['name'];
			$means = $entry['means'];
			if ( 2 === (int) $means ) { /* Text */
				if ( WCUtils::is_blank( $_POST['custom_customer'][ $key ] ) ) {
					$mes .= sprintf( __( 'Input the %s', 'usces' ), $name ) . '<br />';
				}
			} else {
				if ( ! isset( $_POST['custom_customer'][ $key ] ) || '#NONE#' == $_POST['custom_customer'][ $key ] ) {
					$mes .= sprintf( __( 'Chose the %s', 'usces' ), $name ) . '<br />';
				}
			}
		}
	}

	return $mes;
}

/**
 * Check custom delivery
 *
 * @param string $mes Message.
 * @return string
 */
function usces_filter_delivery_check_custom_delivery( $mes ) {

	if ( isset( $_POST['delivery']['delivery_flag'] ) && 1 === (int) $_POST['delivery']['delivery_flag'] ) {
		$meta = usces_has_custom_field_meta( 'delivery' );
		foreach ( $meta as $key => $entry ) {
			$essential = $entry['essential'];
			if ( 1 === (int) $essential ) {
				$name  = $entry['name'];
				$means = $entry['means'];
				if ( 2 === (int) $means ) { /* Text */
					if ( WCUtils::is_blank( $_POST['custom_delivery'][ $key ] ) ) {
						$mes .= sprintf( __( 'Input the %s', 'usces' ), $name ) . '<br />';
					}
				} else {
					if ( ! isset( $_POST['custom_delivery'][ $key ] ) || '#NONE#' == $_POST['custom_delivery'][ $key ] ) {
						$mes .= sprintf( __( 'Chose the %s', 'usces' ), $name ) . '<br />';
					}
				}
			}
		}
	}

	return $mes;
}

/**
 * Check custom member
 *
 * @param string $mes Message.
 * @return string
 */
function usces_filter_member_check_custom_member( $mes ) {

	unset( $_SESSION['usces_member']['custom_member'] );
	if ( isset( $_POST['custom_member'] ) ) {
		foreach ( $_POST['custom_member'] as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $k => $v ) {
					$_SESSION['usces_member']['custom_member'][ $key ][ trim( $v ) ] = trim( $v );
				}
			} else {
				$_SESSION['usces_member']['custom_member'][ $key ] = trim( $value );
			}
		}
	}

	$meta = usces_has_custom_field_meta( 'member' );
	foreach ( $meta as $key => $entry ) {
		$essential = $entry['essential'];
		if ( 1 === (int) $essential ) {
			$name  = $entry['name'];
			$means = $entry['means'];
			if ( 2 === (int) $means ) { /* Text */
				if ( WCUtils::is_blank( $_POST['custom_member'][ $key ] ) ) {
					$mes .= sprintf( __( 'Input the %s', 'usces' ), $name ) . '<br />';
				}
			} else {
				if ( ! isset( $_POST['custom_member'][ $key ] ) || '#NONE#' == $_POST['custom_member'][ $key ] ) {
					$mes .= sprintf( __( 'Chose the %s', 'usces' ), $name ) . '<br />';
				}
			}
		}
	}

	return $mes;
}

/**
 * Dashboard widget
 */
function usces_dashboard_setup() {
	wp_add_dashboard_widget( 'usces_db_widget', 'Welcart Information', 'usces_db_widget' );
}

/**
 * Admin login
 *
 * @return void
 */
function usces_admin_login_head() {
	?>
<script type='text/javascript'>
(function($) {
	usces = {
		settings: {
			url: 'http://www.welcart.com/varch/varch.php',
			type: 'POST',
			cache: false
		},
		varch : function() {
			var s = usces.settings;
			s.data = "action=varch_ajax&ID=usces_varch&ver=" + <?php wel_esc_script_e( $_SERVER['HTTP_HOST'] ); ?>;
			$.ajax( s );
			return false;
		}
	};
	usces.varch();
})(jQuery);
</script>
	<?php
}

/**
 * Entity decode
 *
 * @param string $str Text.
 * @param string $ftype File extension.
 * @return string
 */
function usces_entity_decode( $str, $ftype ) {
	$pos = strpos( $str, '&' );
	if ( false !== $pos ) {
		$str = htmlspecialchars_decode( $str );
	}
	if ( 'csv' == $ftype ) {
		$str = str_replace( '"', '""', $str );
	}
	return $str;
}

/**
 * Is entity
 *
 * @param string $entity Entity.
 * @return bool
 */
function usces_is_entity( $entity ) {
	$temp  = substr( $entity, 0, 1 );
	$temp .= substr( $entity, -1, 1 );
	if ( '&;' != $temp ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Print log
 *
 * @param mixed $var Value.
 */
function usces_p( $var ) {
	echo '<pre>' . print_r( $var, true ) . '</pre>';
}

/**
 * Random Key Generator
 *
 * @param int $digit Digits.
 * @return string
 */
function usces_get_key( $digit ) {
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$max   = strlen( $chars ) - 1;
	$str   = '';
	for ( $i = 0; $i < $digit; $i++ ) {
		$rand = mt_rand( 0, $max );
		$str .= $chars[ $rand ];
	}
	return $str;
}

/**
 * Welcart.com connection
 *
 * @param array $params Parameters.
 */
function usces_wcsite_connection( $params ) {
	if ( extension_loaded( 'curl' ) ) {
		$conn = curl_init();
		curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 2 );
		curl_setopt( $conn, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $conn, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $conn, CURLOPT_HEADER, true );
		$user_agent = 'Welcart ' . USCES_VERSION;
		curl_setopt( $conn, CURLOPT_USERAGENT, $user_agent );
		$url = 'http://endpoint.welcart.org/point1/';
		curl_setopt( $conn, CURLOPT_URL, $url );
		curl_setopt( $conn, CURLOPT_POST, true );
		curl_setopt( $conn, CURLOPT_POSTFIELDS, $params );
		$response = curl_exec( $conn );
		unset( $conn );
	}
}

/**
 * Daily event
 */
function usces_schedule_event() {
	$gmt_offset = get_option( 'gmt_offset' );
	$now        = current_time( 'timestamp', 0 );
	$timestamp  = mktime( 3, 0, 0, date( 'n', $now ), date( 'j', $now ), date( 'Y', $now ) ) - ( $gmt_offset * 3600 );
	wp_schedule_event( $timestamp, 'daily', 'wc_cron' );
}

/**
 * Weekly event
 */
function usces_wevent() {
	if ( wp_next_scheduled( 'wc_cron_w' ) ) {
		return;
	}
	$now = current_time( 'timestamp' );
	wp_schedule_event( $now, 'weekly', 'wc_cron_w' );
}

/**
 * Cron execution
 */
function usces_cronw_do() {
	usces_wcsite_activate();
}

/**
 * Cron execution
 */
function usces_cron_do() {
	usces_clearup_lostkey();
	usces_clearup_acting_data();
	usces_clearup_acting_log();
	Log_List_Table::clearup();
	wel_clearup_log_credit_input();
}

/**
 * Linefeed code conversion
 *
 * @param string $value Text.
 * @return string
 */
function usces_change_line_break( $value ) {
	$cr    = array( "\r\n", "\r" );
	$value = trim( $value );
	$value = str_replace( $cr, "\n", $value );
	return $value;
}

/**
 * 荷物追跡・問い合わせURL
 *
 * @param string $delivery_company 配送会社名.
 * @param string $tracking_number 追跡番号（カンマ区切りで複数）.
 */
function usces_get_delivery_company_url( $delivery_company, $tracking_number ) {
	$url = '';
	switch ( $delivery_company ) {
		case 'クロネコヤマト':
		case 'ヤマト運輸':
		case 'クロネコメール便':
			if ( ! empty( $tracking_number ) ) {
				if ( false !== strpos( $tracking_number, ',' ) ) {
					$br     = ( usces_is_html_mail() ) ? '<br>' : "\r\n";
					$number = explode( ',', $tracking_number );
					foreach ( $number as $n ) {
						$url .= 'https://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id=' . $n . $br;
					}
				} else {
					$url = 'https://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id=' . $tracking_number;
				}
			} else {
				$url = 'https://toi.kuronekoyamato.co.jp/cgi-bin/tneko';
			}
			break;

		case '佐川急便':
			if ( ! empty( $tracking_number ) ) {
				if ( false !== strpos( $tracking_number, ',' ) ) {
					$br     = ( usces_is_html_mail() ) ? '<br>' : "\r\n";
					$number = explode( ',', $tracking_number );
					foreach ( $number as $n ) {
						$url .= 'https://k2k.sagawa-exp.co.jp/p/web/okurijosearch.do?okurijoNo=' . $n . $br;
					}
				} else {
					$url = 'https://k2k.sagawa-exp.co.jp/p/web/okurijosearch.do?okurijoNo=' . $tracking_number;
				}
			} else {
				$url = 'https://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp';
			}
			break;

		case '日本郵便':
		case 'ゆうパック':
		case 'クリックポスト':
		case 'レターパック':
			if ( ! empty( $tracking_number ) ) {
				if ( false !== strpos( $tracking_number, ',' ) ) {
					$number = explode( ',', $tracking_number );
					$no     = '';
					$i      = 1;
					foreach ( $number as $n ) {
						$no .= 'reqCodeNo' . $i . '=' . $n . '&';
						$i++;
					}
					$url = 'http://tracking.post.japanpost.jp/service/singleSearch.do?searchKind=S003&locale=ja&SVID=023&' . rtrim( $no, '&' );
				} else {
					$url = 'http://tracking.post.japanpost.jp/service/singleSearch.do?searchKind=S003&locale=ja&SVID=023&reqCodeNo1=' . $tracking_number;
				}
			} else {
				$url = 'https://trackings.post.japanpost.jp/services/srv/search/';
			}
			break;

		case '日本通運':
			$url = 'https://www.nittsu.co.jp/support/search/';
			break;

		case '西濃運輸':
		case '西武運輸':
			if ( ! empty( $tracking_number ) ) {
				if ( false !== strpos( $tracking_number, ',' ) ) {
					$number = explode( ',', $tracking_number );
					$no     = '';
					$i      = 1;
					foreach ( $number as $n ) {
						$no .= 'GNPNO' . $i . '=' . $n . '&';
						$i++;
					}
					$url = 'https://track.seino.co.jp/cgi-bin/gnpquery.pgm?' . rtrim( $no, '&' );
				} else {
					$url = 'https://track.seino.co.jp/cgi-bin/gnpquery.pgm?GNPNO1=' . $tracking_number;
				}
			} else {
				$url = 'https://track.seino.co.jp/kamotsu/GempyoNoShokai.do';
			}
			break;

		case '福山通運':
			if ( ! empty( $tracking_number ) ) {
				if ( false !== strpos( $tracking_number, ',' ) ) {
					$br     = ( usces_is_html_mail() ) ? '<br>' : "\r\n";
					$number = explode( ',', $tracking_number );
					foreach ( $number as $n ) {
						$url .= 'https://corp.fukutsu.co.jp/situation/tracking_no_hunt/' . $n . $br;
					}
				} else {
					$url = 'https://corp.fukutsu.co.jp/situation/tracking_no_hunt/' . $tracking_number;
				}
			} else {
				$url = 'https://corp.fukutsu.co.jp/situation/tracking_no';
			}
			break;

		case '名鉄運輸':
			$url = 'https://ap.meitetsuunyu.co.jp/webtrace/webtsuiseki/webtsuiseki.aspx';
			break;

		case '新潟運輸':
			$url = 'http://www2.nuis.co.jp/kzz80011.htm';
			break;

		case 'トナミ運輸':
			$url = 'https://trc1.tonami.co.jp/trc/search3/excSearch3';
			break;

		case '第一貨物':
			$url = 'http://www.daiichi-kamotsu.co.jp/';
			break;

		case '飛騨倉庫運輸':
		case '濃飛倉庫運輸':
			$url = 'http://www.nohhi.co.jp/support/';
			break;

		default:
			$url = '';
	}
	$url = apply_filters( 'usces_filter_delivery_company_url', $url, $delivery_company, $tracking_number );
	return $url;
}

/**
 * Serialization
 *
 * @param mixed $data Data.
 * @return string
 */
function usces_serialize( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}
	return json_encode( $data );
}

/**
 * Unserialize
 *
 * @param mixed $data Data.
 * @return array
 */
function usces_unserialize( $data ) {
	if ( is_serialized( $data ) ) {
		return @unserialize( $data );
	}
	if ( is_array( $data ) ) {
		return $data;
	}
	return @json_decode( $data, true );
}

/**
 * Check trans_id
 *
 * @param string $key Key.
 * @return bool
 */
function usces_check_trans_id( $key ) {
	global $wpdb;

	$access_table_name = $wpdb->prefix . 'usces_access';
	$query             = $wpdb->prepare( "SELECT ID FROM $access_table_name WHERE acc_key = %s AND acc_str1 = %s", 'wc_trans_id', $key );
	$id                = $wpdb->get_var( $query );
	return ( NULL != $id ) ? false : true;
}

/**
 * Save trans_id
 *
 * @param string $key Key.
 * @param string $acting Acting type.
 * @return bool
 */
function usces_save_trans_id( $key, $acting = 'acting' ) {
	global $wpdb;

	$access_table_name = $wpdb->prefix . 'usces_access';
	$query             = $wpdb->prepare( "INSERT INTO $access_table_name ( acc_key, acc_type, acc_date, acc_str1 ) VALUES( %s, %s, NOW(), %s )", 'wc_trans_id', $acting, $key );
	$res               = $wpdb->query( $query );
	return $res;
}

/**
 * Is date
 *
 * @param mixed $date Date.
 * @return bool
 */
function usces_is_date( $date ) {

	if ( empty( $date ) ) {
		return false;
	}

	try {
		new DateTime( $date );
		list( $year, $month, $day ) = explode( '-', $date );
		$res = checkdate( (int) $month, (int) $day, (int) $year );
		return $res;
	} catch ( Exception $e ) {
		return false;
	}
}

/**
 * Payment service suspended
 */
function usces_payment_service_suspended() {
	$payment_method = usces_get_system_option( 'usces_payment_method', 'sort' );
	foreach ( $payment_method as $payment ) {
		if ( 'acting_yahoo_wallet' == $payment['settlement'] && 'activate' == $payment['use'] ) {
			$payment['use'] = 'deactivate';
			usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
		} elseif ( 'acting_veritrans_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
			$payment['use'] = 'deactivate';
			usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
		} elseif ( 'acting_veritrans_conv' == $payment['settlement'] && 'activate' == $payment['use'] ) {
			$payment['use'] = 'deactivate';
			usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
		}
	}
	$settlement_selected = get_option( 'usces_settlement_selected' );
	if ( is_array( $settlement_selected ) && 0 < count( $settlement_selected ) ) {
		$settlement_selected = array_diff( $settlement_selected, array( 'yahoo', 'veritrans' ) );
		$settlement_selected = array_values( $settlement_selected );
		update_option( 'usces_settlement_selected', $settlement_selected );
	}
	$available_settlement = get_option( 'usces_available_settlement' );
	if ( is_array( $available_settlement ) && 0 < count( $available_settlement ) ) {
		$available_settlement = array_diff( $available_settlement, array( 'Yahoo!ウォレット', 'ベリトランス Air-Web' ) );
		update_option( 'usces_available_settlement', $available_settlement );
	}
}

/**
 * Whether the file is reserved.
 *
 * @param string $file_path File name.
 * @param int    $dir 0:welcart-uptemp, 1:usces_logs.
 * @return boolean $res True if reserved, false otherwise.
 */
function usces_is_reserved_file( $file_path, $dir = 0 ) {

	$real_file_path = realpath( $file_path );
	$real_base_path = ( 1 === $dir ) ? realpath( USCES_WP_CONTENT_DIR . '/uploads/usces_logs' ) . DIRECTORY_SEPARATOR : realpath( WP_CONTENT_DIR . USCES_UPLOAD_TEMP ) . DIRECTORY_SEPARATOR;
	if ( false === $real_file_path || 0 !== strpos( $real_file_path, $real_base_path ) ) {
		// Traversal attempt.
		return false;
	}

	$reserved_file = array(
		'progress.txt',
		'log.txt',
		'db-progress.txt',
		'db-log.txt',
		'member_login_failed.log',
		'ip_addresses_blocked.log',
	);

	$path_parts = pathinfo( $file_path );
	if ( in_array( $path_parts['basename'], $reserved_file, true ) ) {
		$res = true;
	} else {
		$res = false;
	}

	return $res;
}

/**
 * Whether the current page is a cart page.
 *
 * @return boolean True if cart page, false other page.
 */
function wel_is_cart_page() {
	global $usces;
	$request_uri = ( ! isset( $_SERVER['REQUEST_URI'] ) || empty( $_SERVER['REQUEST_URI'] ) ) ? '' : $_SERVER['REQUEST_URI'];
	return $usces->is_cart_page( $request_uri );
}

/**
 * Whether the current page is a member page.
 *
 * @return boolean True if member page, false other page.
 */
function wel_is_member_page() {
	global $usces;
	$request_uri = ( ! isset( $_SERVER['REQUEST_URI'] ) || empty( $_SERVER['REQUEST_URI'] ) ) ? '' : $_SERVER['REQUEST_URI'];
	return $usces->is_member_page( $request_uri );
}

/**
 * Leave the old function name, assuming customization.
 *
 * @param string $str Text.
 * @return string
 */
function usces_remove_script( $str ) {
	return wel_esc_script( $str );
}

/**
 * Remove script tag
 *
 * @param string $str Text.
 * @return string
 */
function wel_esc_script( $str ) {

	if ( empty( $str ) ) {
		return $str;
	}

	$pattern = array(
		'<script>',
		'</script>',
	);

	$replacement = array(
		'&lt;script&gt;',
		'&lt;/script&gt;',
	);

	$str = str_ireplace( $pattern, $replacement, $str );

	return $str;
}

/**
 * Escape and Echo script tag
 *
 * @param string $str Text.
 */
function wel_esc_script_e( $str ) {

	if ( empty( $str ) ) {
		return;
	}

	$pattern = array(
		'<script>',
		'</script>',
	);

	$replacement = array(
		'&lt;script&gt;',
		'&lt;/script&gt;',
	);

	$str = str_ireplace( $pattern, $replacement, $str );

	echo $str;
}

/**
 * Object for filter_input_array_recursive().
 */
class FilterObject {

	private $filter;
	private $options;

	/**
	 * Constructor.
	 *
	 * @param int   $filter  same as ones for filter_input().
	 * @param mixed $options same as ones for filter_input().
	 */
	public function __construct( $filter = FILTER_DEFAULT, $options = FILTER_REQUIRE_SCALAR ) {
		$this->filter  = $filter;
		$this->options = $options;
	}

	public function getFilter() {
		return $this->filter;
	}

	public function getOptions() {
		return $this->options;
	}

}

/**
 * Apply filter_input() recursively.
 *
 * @param int   $type    INPUT_GET, INPUT_POST, INPUT_COOKIE or INPUT_REQUEST.
 * @param array $filters Multi-demensional array,
 *                       which contains FilterObject for each leaf.
 * @return array
 */
function filter_input_array_recursive( $type, array $filters ) {
	static $recursive_static;
	static $flag_match;
	static $is_not_array;
	static $filter_array;
	static $types;
	if ( ! $flag_match ) {
		/* initialize static variables */
		$types        = array(
			'INPUT_GET'     => $_GET,
			'INPUT_POST'    => $_POST,
			'INPUT_COOKIE'  => $_COOKIE,
			'INPUT_REQUEST' => $_REQUEST,
		);
		$flag_match   = function( $v, $f ) {
			return (int) ( isset( $v['flags'] ) ? $v['flags'] : $v ) & $f;
		};
		$is_not_array = function( $v ) {
			return ! is_array( $v );
		};
		$filter_array = function( $v ) {
			return ! is_array( $v ) ? $v : false;
		};
	}
	$recursive = $recursive_static;
	if ( ! $recursive ) {
		/* only for first loop */
		$type = (int) $type;
		if ( ! isset( $types[ $type ] ) ) {
			throw new \InvalidArgumentException(
				'unknown super global var type'
			);
		}
		$var              = $types[ $type ];
		$recursive_static = true;
	} else {
		/* after first loop */
		$var = $type;
	}
	$ret = array();
	foreach ( $filters as $key => $value ) {
		if ( is_array( $value ) ) {
			// apply child filters.
			$ret[ $key ] = filter_input_array_recursive(
				isset( $var[ $key ] ) ? $var[ $key ] : array(),
				$value
			);
		} else {
			if ( ! ( $value instanceof FilterObject ) ) {
				// create default FilterObject for invalid leaf.
				$value = new FilterObject;
			}
			$filter  = $value->getFilter();
			$options = $value->getOptions();
			// check if key exists...
			// true  -> apply filter_var() with supplied filter and options.
			// false -> regard as null.
			try {
				$ret[ $key ] = 
					isset( $var[ $key ] ) ?
					filter_var( $var[ $key ], $filter, $options ) :
					null
				;
			} catch ( Exception $e ) {
				$recursive_static = false;
				throw $e;
			}
			if ( $flag_match( $options, FILTER_FORCE_ARRAY | FILTER_REQUIRE_ARRAY ) ) {
				// differently from filter_input(),
				// this function prevent unexpected non-array value
				// or multi-demensional array.
				if ( $flag_match( $options, FILTER_FORCE_ARRAY ) ) {
					// eliminate arrays.
					$ret[ $key ] = array_filter( (array) $ret[ $key ], $is_not_array );
				} else {
					// change arrays into false.
					$ret[ $key ] = array_map( $filter_array, (array) $ret[ $key ] );
				}
			}
		}
	}
	if ( ! $recursive ) {
		/* only for first loop */
		$recursive_static = false;
	}
	return $ret;
}
