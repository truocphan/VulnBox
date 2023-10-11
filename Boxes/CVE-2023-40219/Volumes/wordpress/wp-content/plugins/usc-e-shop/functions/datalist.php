<?php
// member list download
function usces_download_member_list() {

	if ( ! usces_admin_user_can_download_list() ) {
		exit();
	}

	require_once( USCES_PLUGIN_DIR."/classes/dataList.class.php" );
	global $wpdb, $usces;
	global $usces_settings;

	$ext = $_REQUEST['ftype'];
	if( $ext == 'csv' ) {//CSV
		$table_h = "";
		$table_f = "";
		$tr_h = "";
		$tr_f = "";
		$th_h1 = '"';
		$th_h = ',"';
		$th_f = '"';
		$td_h1 = '"';
		$td_h = ',"';
		$td_f = '"';
		$lf = "\n";
	} else {
		exit();
	}

	$csmb_meta = usces_has_custom_field_meta( 'member' );
	$applyform = usces_get_apply_addressform( $usces->options['system']['addressform'] );

	//==========================================================================

	$usces_opt_member = get_option( 'usces_opt_member' );
	if( !is_array( $usces_opt_member ) ) {
		$usces_opt_member = array();
	}
	$usces_opt_member['ftype_mem'] = $ext;
	$chk_mem = array();
	$chk_mem['ID'] = 1;
	$chk_mem['email'] = ( isset( $_REQUEST['check']['email'] ) ) ? 1 : 0;
	if( !empty( $csmb_meta ) ) {
		foreach( $csmb_meta as $key => $entry ) {
			if( $entry['position'] == 'name_pre' ) {
				$name = $entry['name'];
				$csmb_key = 'csmb_'.$key;
				$chk_mem[$csmb_key] = ( isset( $_REQUEST['check'][$csmb_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_mem['name'] = 1;
	if( $applyform == 'JP' ) {
		$chk_mem['kana'] = ( isset( $_REQUEST['check']['kana'] ) ) ? 1 : 0;
	}
	if( !empty( $csmb_meta ) ) {
		foreach( $csmb_meta as $key => $entry ) {
			if( $entry['position'] == 'name_after' ) {
				$name = $entry['name'];
				$csmb_key = 'csmb_'.$key;
				$chk_mem[$csmb_key] = ( isset( $_REQUEST['check'][$csmb_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_mem['zip'] = ( isset( $_REQUEST['check']['zip'] ) ) ? 1 : 0;
	$chk_mem['country'] = ( isset( $_REQUEST['check']['country'] ) ) ? 1 : 0;
	$chk_mem['pref'] = ( isset( $_REQUEST['check']['pref'] ) ) ? 1 : 0;
	$chk_mem['address1'] = ( isset( $_REQUEST['check']['address1'] ) ) ? 1 : 0;
	$chk_mem['address2'] = ( isset( $_REQUEST['check']['address2'] ) ) ? 1 : 0;
	$chk_mem['address3'] = ( isset( $_REQUEST['check']['address3'] ) ) ? 1 : 0;
	$chk_mem['tel'] = ( isset( $_REQUEST['check']['tel'] ) ) ? 1 : 0;
	$chk_mem['fax'] = ( isset( $_REQUEST['check']['fax'] ) ) ? 1 : 0;
	if( !empty( $csmb_meta ) ) {
		foreach( $csmb_meta as $key => $entry ) {
			if( $entry['position'] == 'fax_after' ) {
				$name = $entry['name'];
				$csmb_key = 'csmb_'.$key;
				$chk_mem[$csmb_key] = ( isset( $_REQUEST['check'][$csmb_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_mem['date'] = ( isset( $_REQUEST['check']['date'] ) ) ? 1 : 0;
	$chk_mem['point'] = ( isset( $_REQUEST['check']['point'] ) ) ? 1 : 0;
	$chk_mem['rank'] = ( isset( $_REQUEST['check']['rank'] ) ) ? 1 : 0;
	$usces_opt_member['chk_mem'] = apply_filters( 'usces_filter_chk_mem', $chk_mem );
	update_option( 'usces_opt_member', $usces_opt_member );

	//==========================================================================

	$_REQUEST['searchIn'] = "searchIn";
	$usces_member_table = usces_get_tablename( 'usces_member' );
	$arr_column = array(
		__( 'membership number', 'usces' ) => 'ID', 
		__( 'name', 'usces' ) => 'name', 
		__( 'Address', 'usces' ) => 'address', 
		__( 'Phone number', 'usces' ) => 'tel', 
		__( 'e-mail', 'usces' ) => 'email', 
		__( 'Strated date', 'usces' ) => 'date', 
		__( 'current point', 'usces' ) => 'point' );
	$DT = new dataList( $usces_member_table, $arr_column );
	$DT->pageLimit = 'off';
	$res = $DT->MakeTable();
	$rows = $DT->rows;

	//==========================================================================

	$line = $table_h;
	$line .= $tr_h;
	$line .= $th_h1.__( 'membership number', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['email'] ) ) $line .= $th_h.__( 'e-mail', 'usces' ).$th_f;
	if( !empty( $csmb_meta ) ) {
		foreach( $csmb_meta as $key => $entry ) {
			if( $entry['position'] == 'name_pre' ) {
				$name = $entry['name'];
				$csmb_key = 'csmb_'.$key;
				if( isset( $_REQUEST['check'][$csmb_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	$line .= $th_h.__( 'name', 'usces' ).$th_f;
	if( $applyform == 'JP' ) {
		if( isset( $_REQUEST['check']['kana'] ) ) $line .= $th_h.__( 'furigana', 'usces' ).$th_f;
	}
	if( !empty( $csmb_meta ) ) {
		foreach( $csmb_meta as $key => $entry ) {
			if( $entry['position'] == 'name_after' ) {
				$name = $entry['name'];
				$csmb_key = 'csmb_'.$key;
				if( isset( $_REQUEST['check'][$csmb_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	switch( $applyform ) {
	case 'JP':
		if( isset( $_REQUEST['check']['zip'] ) ) $line .= $th_h.__( 'Zip/Postal Code', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['country'] ) ) $line .= $th_h.__( 'Country', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['pref'] ) ) $line .= $th_h.__( 'Province', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address1'] ) ) $line .= $th_h.__( 'city', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address2'] ) ) $line .= $th_h.__( 'numbers', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address3'] ) ) $line .= $th_h.__( 'building name', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['tel'] ) ) $line .= $th_h.__( 'Phone number', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['fax'] ) ) $line .= $th_h.__( 'FAX number', 'usces' ).$th_f;
		break;
	case 'US':
	default:
		if( isset( $_REQUEST['check']['address2'] ) ) $line .= $th_h.__( 'Address Line1', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address3'] ) ) $line .= $th_h.__( 'Address Line2', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address1'] ) ) $line .= $th_h.__( 'city', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['pref'] ) ) $line .= $th_h.__( 'State', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['country'] ) ) $line .= $th_h.__( 'Country', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['zip'] ) ) $line .= $th_h.__( 'Zip', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['tel'] ) ) $line .= $th_h.__( 'Phone number', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['fax'] ) ) $line .= $th_h.__( 'FAX number', 'usces' ).$th_f;
		break;
	}
	if( !empty( $csmb_meta ) ) {
		foreach( $csmb_meta as $key => $entry ) {
			if( $entry['position'] == 'fax_after' ) {
				$name = $entry['name'];
				$csmb_key = 'csmb_'.$key;
				if( isset( $_REQUEST['check'][$csmb_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	if( isset( $_REQUEST['check']['date'] ) ) $line .= $th_h.__( 'Strated date', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['point'] ) ) $line .= $th_h.__( 'current point', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['rank'] ) ) $line .= $th_h.__( 'Rank', 'usces' ).$th_f;
	$line .= apply_filters( 'usces_filter_chk_mem_label', NULL, $usces_opt_member, $rows );
	$line .= $tr_f.$lf;

	//==========================================================================

	foreach( (array)$rows as $array ) {
		$member_id = $array['ID'];
		$query = $wpdb->prepare( "SELECT * FROM $usces_member_table WHERE ID = %d", $member_id );
		$data = $wpdb->get_row( $query, ARRAY_A );

		$line .= $tr_h;
		$line .= $td_h1.$member_id.$td_f;
		if( isset( $_REQUEST['check']['email'] ) ) $line .= $td_h.usces_entity_decode( $array['email'], $ext ).$td_f;
		if( !empty( $csmb_meta ) ) {
			foreach( $csmb_meta as $key => $entry ) {
				if( $entry['position'] == 'name_pre' ) {
					$name = $entry['name'];
					$csmb_key = 'csmb_'.$key;
					if( isset( $_REQUEST['check'][$csmb_key] ) ) {
						$value = maybe_unserialize( $usces->get_member_meta_value( $csmb_key, $member_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}

		switch( $applyform ) {
		case 'JP':
			$line .= $td_h.usces_entity_decode( $data['mem_name1'].' '.$data['mem_name2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['kana'] ) ) $line .= $td_h.usces_entity_decode( $data['mem_name3'].' '.$data['mem_name4'], $ext ).$td_f;
			break;
		case 'US':
		default:
			$line .= $td_h.usces_entity_decode( $data['mem_name2'].' '.$data['mem_name1'], $ext ).$td_f;
			break;
		}

		if( !empty( $csmb_meta ) ) {
			foreach( $csmb_meta as $key => $entry ) {
				if( $entry['position'] == 'name_after' ) {
					$name = $entry['name'];
					$csmb_key = 'csmb_'.$key;
					if( isset( $_REQUEST['check'][$csmb_key] ) ) {
						$value = maybe_unserialize( $usces->get_member_meta_value( $csmb_key, $member_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}

		$address_info = '';
		switch( $applyform ) {
		case 'JP':
			if( isset( $_REQUEST['check']['zip'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_zip'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['country'] ) ) $address_info .= $td_h.$usces_settings['country'][$usces->get_member_meta_value( 'customer_country', $member_id )].$td_f;
			if( isset( $_REQUEST['check']['pref'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_pref'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address1'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_address1'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address2'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_address2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address3'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_address3'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['tel'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_tel'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['fax'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_fax'], $ext ).$td_f;
			break;
		case 'US':
		default:
			if( isset( $_REQUEST['check']['address2'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_address2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address3'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_address3'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address1'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_address1'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['pref'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_pref'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['country'] ) ) $address_info .= $td_h.$usces_settings['country'][$usces->get_member_meta_value( 'customer_country', $member_id )].$td_f;
			if( isset( $_REQUEST['check']['zip'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_zip'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['tel'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_tel'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['fax'] ) ) $address_info .= $td_h.usces_entity_decode( $data['mem_fax'], $ext ).$td_f;
			break;
		}
		$address_info_args = compact( 'td_h', 'td_f', 'ext', 'member_id', 'applyform' );
		$line .= apply_filters( 'usces_filter_mem_csv_address_info', $address_info, $data, $address_info_args );

		if( !empty( $csmb_meta ) ) {
			foreach( $csmb_meta as $key => $entry ) {
				if( $entry['position'] == 'fax_after' ) {
					$name = $entry['name'];
					$csmb_key = 'csmb_'.$key;
					if( isset( $_REQUEST['check'][$csmb_key] ) ) {
						$value = maybe_unserialize( $usces->get_member_meta_value( $csmb_key, $member_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}
		if( isset( $_REQUEST['check']['date'] ) ) $line .= $td_h.$data['mem_registered'].$td_f;
		if( isset( $_REQUEST['check']['point'] ) ) $line .= $td_h.$data['mem_point'].$td_f;
		if( isset( $_REQUEST['check']['rank'] ) ) {
			$rank = '';
			foreach( (array)$usces->member_status as $rk => $rv ) {
				if( $rk == $data['mem_status'] ) {
					$rank = $rv;
					break;
				}
			}
			$line .= $td_h.$rank.$td_f;
		}
		$line .= apply_filters( 'usces_filter_chk_mem_data', NULL, $usces_opt_member, $member_id, $data );
		$line .= $tr_f.$lf;
	}
	$line .= $table_f.$lf;

	//==========================================================================

	header( "Content-Type: application/octet-stream" );
	header( "Content-Disposition: attachment; filename=usces_member_list.".$ext );
	mb_http_output( 'pass' );
	print( mb_convert_encoding( $line, apply_filters( 'usces_filter_output_csv_encode', 'SJIS-win' ), "UTF-8" ) );
	exit();
}

// product list download
function usces_download_product_list() {

	if ( ! usces_admin_user_can_download_list() ) {
		exit();
	}

	require_once( USCES_PLUGIN_DIR . "/classes/orderList.class.php" );
	global $wpdb, $usces;

	$ext = $_REQUEST['ftype'];
	if( $ext == 'csv' ) {//CSV
		$table_h = "";
		$table_f = "";
		$tr_h = "";
		$tr_f = "";
		$th_h1 = '"';
		$th_h = ',"';
		$th_f = '"';
		$td_h1 = '"';
		$td_h = ',"';
		$td_f = '"';
		$sp = ":";
		$nb = " ";
		$lf = "\n";
	} else {
		exit();
	}

	//==========================================================================

	$usces_opt_order = get_option( 'usces_opt_order' );
	if( !is_array( $usces_opt_order ) ) {
		$usces_opt_order = array();
	}
	$usces_opt_order['ftype_pro'] = $ext;
	$chk_pro = array();
	$chk_pro['ID'] = 1;
	$chk_pro['deco_id'] = ( isset( $_REQUEST['check']['deco_id'] ) ) ? 1 : 0;
	$chk_pro['date'] = ( isset( $_REQUEST['check']['date'] ) ) ? 1 : 0;
	$chk_pro['mem_id'] = ( isset( $_REQUEST['check']['mem_id'] ) ) ? 1 : 0;
	$chk_pro['name'] = ( isset( $_REQUEST['check']['name'] ) ) ? 1 : 0;
	$chk_pro['delivery_method'] = ( isset( $_REQUEST['check']['delivery_method'] ) ) ? 1 : 0;
	$chk_pro['shipping_date'] = ( isset( $_REQUEST['check']['shipping_date'] ) ) ? 1 : 0;
	$chk_pro['item_code'] = 1;
	$chk_pro['sku_code'] = 1;
	$chk_pro['item_name'] = ( isset( $_REQUEST['check']['item_name'] ) ) ? 1 : 0;
	$chk_pro['sku_name'] = ( isset( $_REQUEST['check']['sku_name'] ) ) ? 1 : 0;
	$chk_pro['options'] = ( isset( $_REQUEST['check']['options'] ) ) ? 1 : 0;
	$chk_pro['quantity'] = 1;
	$chk_pro['price'] = 1;
	$chk_pro['unit'] = ( isset( $_REQUEST['check']['unit'] ) ) ? 1 : 0;
	$usces_opt_order['chk_pro'] = apply_filters( 'usces_filter_chk_pro', $chk_pro );
	update_option( 'usces_opt_order', $usces_opt_order );

	//==========================================================================

	$_REQUEST['searchIn'] = "searchIn";
	$usces_order_table = $wpdb->prefix."usces_order";
	$usces_ordercart_table = $wpdb->prefix."usces_ordercart";
	$usces_ordercart_meta_table = $wpdb->prefix."usces_ordercart_meta";

	$arr_column = array(
		__( 'ID', 'usces' ) => 'ID', 
		__( 'Order number', 'usces' ) => 'deco_id', 
		__( 'date', 'usces' ) => 'date', 
		__( 'membership number', 'usces' ) => 'mem_id', 
		__( 'name', 'usces' ) => 'name', 
		__( 'Region', 'usces' ) => 'pref', 
		__( 'shipping option', 'usces' ) => 'delivery_method', 
		__( 'Amount', 'usces' ) => 'total_price', 
		__( 'payment method', 'usces' ) => 'payment_name', 
		__( 'transfer statement', 'usces' ) => 'receipt_status', 
		__( 'Processing', 'usces' ) => 'order_status', 
		__( 'shpping date', 'usces' ) => 'order_modified' );
	$DT = new dataList( $usces_order_table, $arr_column );
	$DT->pageLimit = 'off';
	$res = $DT->MakeTable();
	$rows = $DT->rows;

	//==========================================================================

	$line = $table_h;
	$line .= $tr_h;
	$line .= $th_h1.__( 'ID', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['deco_id'] ) ) $line .= $th_h.__( 'Order number', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['date'] ) ) $line .= $th_h.__( 'order date', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['mem_id'] ) ) $line .= $th_h.__( 'membership number', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['name'] ) ) $line .= $th_h.__( 'name', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['delivery_method'] ) ) $line .= $th_h.__( 'shipping option', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['shipping_date'] ) ) $line .= $th_h.__( 'shpping date', 'usces' ).$th_f;
	$line .= apply_filters( 'usces_filter_chk_pro_label_head', NULL, $usces_opt_order, $rows );
	$line .= $th_h.__( 'item code', 'usces' ).$th_f;
	$line .= $th_h.__( 'SKU code', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['item_name'] ) ) $line .= $th_h.__( 'item name', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['sku_name'] ) ) $line .= $th_h.__( 'SKU display name ', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['options'] ) ) $line .= $th_h.__( 'options for items', 'usces' ).$th_f;
	$line .= $th_h.__( 'Quantity', 'usces' ).$th_f;
	$line .= $th_h.__( 'Unit price', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['unit'] ) ) $line .= $th_h.__( 'unit', 'usces' ).$th_f;
	$line .= apply_filters( 'usces_filter_chk_pro_label_detail', NULL, $usces_opt_order, $rows );
	$line .= $tr_f.$lf;

	//==========================================================================

	foreach( (array)$rows as $array ) {
		$order_id = $array['ID'];
		$query = $wpdb->prepare( "SELECT * FROM $usces_ordercart_table WHERE order_id = %d", $order_id );
		$cart = $wpdb->get_results( $query, ARRAY_A );
		foreach( $cart as $cart_row ) {
			$line .= $tr_h;
			$line .= $td_h1.$order_id.$td_f;
			if( isset( $_REQUEST['check']['deco_id'] ) ) $line .= $td_h.$array['deco_id'].$td_f;

			if( isset( $_REQUEST['check']['date'] ) ) $line .= $td_h.$array['date'].$td_f;
			if( isset( $_REQUEST['check']['mem_id'] ) ) $line .= $td_h.$array['mem_id'].$td_f;
			if( isset( $_REQUEST['check']['name'] ) ) $line .= $td_h.usces_entity_decode( $array['name'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_method'] ) ) {
				$delivery_method = '';
				if( strtoupper( $array['delivery_method'] ) == '#NONE#' ) {
					$delivery_method = __( 'No preference', 'usces' );
				} else {
					foreach( (array)$usces->options['delivery_method'] as $dkey => $delivery ) {
						if( $delivery['id'] == $array['delivery_method'] ) {
							$delivery_method = $delivery['name'];
							break;
						}
					}
				}
				$line .= $td_h.$delivery_method.$td_f;
			}
			if( isset( $_REQUEST['check']['shipping_date'] ) ) $line .= $td_h.$array['order_modified'].$td_f;
			$line .= apply_filters( 'usces_filter_chk_pro_data_head', NULL, $usces_opt_order, $array, $cart_row );

			$line .= $td_h.$cart_row['item_code'].$td_f;
			$line .= $td_h.$cart_row['sku_code'].$td_f;
			if( isset( $_REQUEST['check']['item_name'] ) ) $line .= $td_h.usces_entity_decode( $cart_row['item_name'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['sku_name'] ) ) $line .= $td_h.usces_entity_decode( $cart_row['sku_name'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['options'] ) ) {
				$query = $wpdb->prepare( "SELECT * FROM $usces_ordercart_meta_table WHERE cart_id = %d AND meta_type = %s ORDER BY cartmeta_id", $cart_row['cart_id'], 'option' );
				$options = $wpdb->get_results( $query, ARRAY_A );
				$optstr = '';
				if( $options && is_array( $options ) && count( $options ) > 0 ) {
					foreach( (array)$options as $key => $value ) {
						if( !empty( $value['meta_key'] ) ) {
							$meta_value = maybe_unserialize( $value['meta_value'] );
							if( is_array( $meta_value ) ) {
								$meta_vals = '';
								foreach( $meta_value as $array_val ) {
									$meta_vals .= $nb.urldecode( $array_val );
								}
								$optstr .= usces_entity_decode( urldecode( $value['meta_key'] ).$sp.$meta_vals, $ext ).$nb;
							} else {
								$optstr .= usces_entity_decode( urldecode( $value['meta_key'] ).$sp.urldecode( $value['meta_value'] ), $ext ).$nb;
							}
						}
					}
				}
				$line .= $td_h.$optstr.$td_f;
			}
			$line .= $td_h.$cart_row['quantity'].$td_f;
			$line .= $td_h.usces_crform( $cart_row['price'], false, false, 'return', false ).$td_f;
			if( isset( $_REQUEST['check']['unit'] ) ) $line .= $td_h.usces_entity_decode( $cart_row['unit'], $ext ).$td_f;
			$line .= apply_filters( 'usces_filter_chk_pro_data_detail', NULL, $usces_opt_order, $array, $cart_row );
			$line .= $tr_f.$lf;
		}
	}
	$line .= $table_f.$lf;
	$line = apply_filters( 'wc_filter_chk_pro_data_order', $line );

	//==========================================================================

	header( "Content-Type: application/octet-stream" );
	header( "Content-Disposition: attachment; filename=usces_product_list.".$ext );
	mb_http_output( 'pass' );
	print( mb_convert_encoding( $line, apply_filters( 'usces_filter_output_csv_encode', 'SJIS-win' ), "UTF-8" ) );
	exit();
}

// order list download
function usces_download_order_list() {

	if ( ! usces_admin_user_can_download_list() ) {
		exit();
	}

	require_once( USCES_PLUGIN_DIR."/classes/orderList.class.php" );
	global $wpdb, $usces;
	global $usces_settings;

	$ext = $_REQUEST['ftype'];
	if( $ext == 'csv' ) {//CSV
		$table_h = "";
		$table_f = "";
		$tr_h = "";
		$tr_f = "";
		$th_h1 = '"';
		$th_h = ',"';
		$th_f = '"';
		$td_h1 = '"';
		$td_h = ',"';
		$td_f = '"';
		$sp = ":";
		$lf = "\n";
	} else {
		exit();
	}
	$csod_meta = usces_has_custom_field_meta( 'order' );
	$cscs_meta = usces_has_custom_field_meta( 'customer' );
	$csde_meta = usces_has_custom_field_meta( 'delivery' );
	$applyform = usces_get_apply_addressform( $usces->options['system']['addressform'] );

	//==========================================================================

	$usces_opt_order = get_option( 'usces_opt_order' );
	if( !is_array( $usces_opt_order ) ) {
		$usces_opt_order = array();
	}
	$usces_opt_order['ftype_ord'] = $ext;
	$chk_ord = array();
	$chk_ord['ID'] = 1;
	$chk_ord['deco_id'] = ( isset( $_REQUEST['check']['deco_id'] ) ) ? 1 : 0;
	$chk_ord['date'] = ( isset( $_REQUEST['check']['date'] ) ) ? 1 : 0;
	$chk_ord['mem_id'] = ( isset( $_REQUEST['check']['mem_id'] ) ) ? 1 : 0;
	$chk_ord['email'] = ( isset( $_REQUEST['check']['email'] ) ) ? 1 : 0;
	if( !empty( $cscs_meta ) ) {
		foreach( $cscs_meta as $key => $entry ) {
			if( $entry['position'] == 'name_pre' ) {
				$name = $entry['name'];
				$cscs_key = 'cscs_'.$key;
				$chk_ord[$cscs_key] = ( isset( $_REQUEST['check'][$cscs_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_ord['name'] = 1;
	if( $applyform == 'JP' ) {
		$chk_ord['kana'] = ( isset( $_REQUEST['check']['kana'] ) ) ? 1 : 0;
	}
	if( !empty( $cscs_meta ) ) {
		foreach( $cscs_meta as $key => $entry ) {
			if( $entry['position'] == 'name_after' ) {
				$name = $entry['name'];
				$cscs_key = 'cscs_'.$key;
				$chk_ord[$cscs_key] = ( isset( $_REQUEST['check'][$cscs_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_ord['zip'] = ( isset( $_REQUEST['check']['zip'] ) ) ? 1 : 0;
	$chk_ord['country'] = ( isset( $_REQUEST['check']['country'] ) ) ? 1 : 0;
	$chk_ord['pref'] = ( isset( $_REQUEST['check']['pref'] ) ) ? 1 : 0;
	$chk_ord['address1'] = ( isset( $_REQUEST['check']['address1'] ) ) ? 1 : 0;
	$chk_ord['address2'] = ( isset( $_REQUEST['check']['address2'] ) ) ? 1 : 0;
	$chk_ord['address3'] = ( isset( $_REQUEST['check']['address3'] ) ) ? 1 : 0;
	$chk_ord['tel'] = ( isset( $_REQUEST['check']['tel'] ) ) ? 1 : 0;
	$chk_ord['fax'] = ( isset( $_REQUEST['check']['fax'] ) ) ? 1 : 0;
	if( !empty( $cscs_meta ) ) {
		foreach( $cscs_meta as $key => $entry ) {
			if( $entry['position'] == 'fax_after' ) {
				$name = $entry['name'];
				$cscs_key = 'cscs_'.$key;
				$chk_ord[$cscs_key] = ( isset( $_REQUEST['check'][$cscs_key] ) ) ? 1 : 0;
			}
		}
	}
	//--------------------------------------------------------------------------
	if( !empty( $csde_meta ) ) {
		foreach( $csde_meta as $key => $entry ) {
			if( $entry['position'] == 'name_pre' ) {
				$name = $entry['name'];
				$csde_key = 'csde_'.$key;
				$chk_ord[$csde_key] = ( isset( $_REQUEST['check'][$csde_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_ord['delivery_name'] = ( isset( $_REQUEST['check']['delivery_name'] ) ) ? 1 : 0;
	if( $applyform == 'JP' ) {
		$chk_ord['delivery_kana'] = ( isset( $_REQUEST['check']['delivery_kana'] ) ) ? 1 : 0;
	}
	if( !empty( $csde_meta ) ) {
		foreach( $csde_meta as $key => $entry ) {
			if( $entry['position'] == 'name_after' ) {
				$name = $entry['name'];
				$csde_key = 'csde_'.$key;
				$chk_ord[$csde_key] = ( isset( $_REQUEST['check'][$csde_key] ) ) ? 1 : 0;
			}
		}
	}
	$chk_ord['delivery_zip'] = ( isset( $_REQUEST['check']['delivery_zip'] ) ) ? 1 : 0;
	$chk_ord['delivery_country'] = ( isset( $_REQUEST['check']['delivery_country'] ) ) ? 1 : 0;
	$chk_ord['delivery_pref'] = ( isset( $_REQUEST['check']['delivery_pref'] ) ) ? 1 : 0;
	$chk_ord['delivery_address1'] = ( isset( $_REQUEST['check']['delivery_address1'] ) ) ? 1 : 0;
	$chk_ord['delivery_address2'] = ( isset( $_REQUEST['check']['delivery_address2'] ) ) ? 1 : 0;
	$chk_ord['delivery_address3'] = ( isset( $_REQUEST['check']['delivery_address3'] ) ) ? 1 : 0;
	$chk_ord['delivery_tel'] = ( isset( $_REQUEST['check']['delivery_tel'] ) ) ? 1 : 0;
	$chk_ord['delivery_fax'] = ( isset( $_REQUEST['check']['delivery_fax'] ) ) ? 1 : 0;
	if( !empty( $csde_meta ) ) {
		foreach( $csde_meta as $key => $entry ) {
			if( $entry['position'] == 'fax_after' ) {
				$name = $entry['name'];
				$csde_key = 'csde_'.$key;
				$chk_ord[$csde_key] = ( isset( $_REQUEST['check'][$csde_key] ) ) ? 1 : 0;
			}
		}
	}
	//--------------------------------------------------------------------------
	$chk_ord['shipping_date'] = ( isset( $_REQUEST['check']['shipping_date'] ) ) ? 1 : 0;
	$chk_ord['peyment_method'] = ( isset( $_REQUEST['check']['peyment_method'] ) ) ? 1 : 0;
	$chk_ord['delivery_method'] = ( isset( $_REQUEST['check']['delivery_method'] ) ) ? 1 : 0;
	$chk_ord['delivery_date'] = ( isset( $_REQUEST['check']['delivery_date'] ) ) ? 1 : 0;
	$chk_ord['delivery_time'] = ( isset( $_REQUEST['check']['delivery_time'] ) ) ? 1 : 0;
	$chk_ord['delidue_date'] = ( isset( $_REQUEST['check']['delidue_date'] ) ) ? 1 : 0;
	$chk_ord['status'] = ( isset( $_REQUEST['check']['status'] ) ) ? 1 : 0;
	$chk_ord['total_amount'] = 1;
	$chk_ord['getpoint'] = ( isset( $_REQUEST['check']['getpoint'] ) ) ? 1 : 0;
	$chk_ord['usedpoint'] = ( isset( $_REQUEST['check']['usedpoint'] ) ) ? 1 : 0;
	$chk_ord['discount'] = 1;
	$chk_ord['shipping_charge'] = 1;
	$chk_ord['cod_fee'] = 1;
	$chk_ord['tax'] = ( usces_is_tax_display() ) ? 1 : 0;
	$chk_ord['note'] = ( isset( $_REQUEST['check']['note'] ) ) ? 1 : 0;
	if( !empty( $csod_meta ) ) {
		foreach( $csod_meta as $key => $entry ) {
			$name = $entry['name'];
			$csod_key = 'csod_'.$key;
			$chk_ord[$csod_key] = ( isset( $_REQUEST['check'][$csod_key] ) ) ? 1 : 0;
		}
	}
	$usces_opt_order['chk_ord'] = apply_filters( 'usces_filter_chk_ord', $chk_ord );
	update_option( 'usces_opt_order', $usces_opt_order );

	//==========================================================================

	if( isset( $_REQUEST['check']['status'] ) ) {
		$usces_management_status = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
		$usces_management_status['new'] = __( 'new order', 'usces' );
	}

	$_REQUEST['searchIn'] = "searchIn";
	$usces_order_table = $wpdb->prefix."usces_order";
	$arr_column = array(
		__( 'ID', 'usces' ) => 'ID', 
		__( 'Order number', 'usces' ) => 'deco_id', 
		__( 'date', 'usces' ) => 'date', 
		__( 'membership number', 'usces' ) => 'mem_id', 
		__( 'name', 'usces' ) => 'name', 
		__( 'Region', 'usces' ) => 'pref', 
		__( 'shipping option', 'usces' ) => 'delivery_method', 
		__( 'Amount', 'usces' ) => 'total_price', 
		__( 'payment method', 'usces' ) => 'payment_name', 
		__( 'transfer statement', 'usces' ) => 'receipt_status', 
		__( 'Processing', 'usces' ) => 'order_status', 
		__( 'shpping date', 'usces' ) => 'order_modified' );
	$DT = new dataList( $usces_order_table, $arr_column );
	$DT->pageLimit = 'off';
	$res = $DT->MakeTable();
	$rows = $DT->rows;

	//==========================================================================

	$line = $table_h;
	$line .= $tr_h;
	$line .= $th_h1.__( 'ID', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['deco_id'] ) ) $line .= $th_h.__( 'Order number', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['date'] ) ) $line .= $th_h.__( 'order date', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['mem_id'] ) ) $line .= $th_h.__( 'membership number', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['email'] ) ) $line .= $th_h.__( 'e-mail', 'usces' ).$th_f;
	if( !empty( $cscs_meta ) ) {
		foreach( $cscs_meta as $key => $entry ) {
			if( $entry['position'] == 'name_pre' ) {
				$name = $entry['name'];
				$cscs_key = 'cscs_'.$key;
				if( isset( $_REQUEST['check'][$cscs_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	$line .= $th_h.__( 'name', 'usces' ).$th_f;
	if( $applyform == 'JP' ) {
		if( isset( $_REQUEST['check']['kana'] ) ) $line .= $th_h.__( 'furigana', 'usces' ).$th_f;
	}
	if( !empty( $cscs_meta ) ) {
		foreach( $cscs_meta as $key => $entry ) {
			if( $entry['position'] == 'name_after' ) {
				$name = $entry['name'];
				$cscs_key = 'cscs_'.$key;
				if( isset( $_REQUEST['check'][$cscs_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}

	switch( $applyform ) {
	case 'JP':
		if( isset( $_REQUEST['check']['zip'] ) ) $line .= $th_h.__( 'Zip/Postal Code', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['country'] ) ) $line .= $th_h.__( 'Country', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['pref'] ) ) $line .= $th_h.__( 'Province', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address1'] ) ) $line .= $th_h.__( 'city', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address2'] ) ) $line .= $th_h.__( 'numbers', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address3'] ) ) $line .= $th_h.__( 'building name', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['tel'] ) ) $line .= $th_h.__( 'Phone number', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['fax'] ) ) $line .= $th_h.__( 'FAX number', 'usces' ).$th_f;
		break;
	case 'US':
	default:
		if( isset( $_REQUEST['check']['address2'] ) ) $line .= $th_h.__( 'Address Line1', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address3'] ) ) $line .= $th_h.__( 'Address Line2', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['address1'] ) ) $line .= $th_h.__( 'city', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['pref'] ) ) $line .= $th_h.__( 'State', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['country'] ) ) $line .= $th_h.__( 'Country', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['zip'] ) ) $line .= $th_h.__( 'Zip', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['tel'] ) ) $line .= $th_h.__( 'Phone number', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['fax'] ) ) $line .= $th_h.__( 'FAX number', 'usces' ).$th_f;
		break;
	}

	if( !empty( $cscs_meta ) ) {
		foreach( $cscs_meta as $key => $entry ) {
			if( $entry['position'] == 'fax_after' ) {
				$name = $entry['name'];
				$cscs_key = 'cscs_'.$key;
				if( isset( $_REQUEST['check'][$cscs_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	$line .= apply_filters( 'usces_filter_chk_ord_label_customer', NULL, $usces_opt_order, $rows );
	//--------------------------------------------------------------------------
	if( !empty( $csde_meta ) ) {
		foreach( $csde_meta as $key => $entry ) {
			if( $entry['position'] == 'name_pre' ) {
				$name = $entry['name'];
				$csde_key = 'csde_'.$key;
				if( isset( $_REQUEST['check'][$csde_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	if( isset( $_REQUEST['check']['delivery_name'] ) ) $line .= $th_h.__( 'Shipping Name', 'usces' ).$th_f;
	if( $applyform == 'JP' ) {
		if( isset( $_REQUEST['check']['delivery_kana'] ) ) $line .= $th_h.__( 'Shipping Furigana', 'usces' ).$th_f;
	}
	if( !empty( $csde_meta ) ) {
		foreach( $csde_meta as $key => $entry ) {
			if( $entry['position'] == 'name_after' ) {
				$name = $entry['name'];
				$csde_key = 'csde_'.$key;
				if( isset( $_REQUEST['check'][$csde_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}

	switch( $applyform ) {
	case 'JP':
		if( isset( $_REQUEST['check']['delivery_zip'] ) ) $line .= $th_h.__( 'Shipping Zip', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_country'] ) ) $line .= $th_h.__( 'Shipping Country', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_pref'] ) ) $line .= $th_h.__( 'Shipping State', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_address1'] ) ) $line .= $th_h.__( 'Shipping City', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_address2'] ) ) $line .= $th_h.__( 'Shipping Address1', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_address3'] ) ) $line .= $th_h.__( 'Shipping Address2', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_tel'] ) ) $line .= $th_h.__( 'Shipping Phone', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_fax'] ) ) $line .= $th_h.__( 'Shipping FAX', 'usces' ).$th_f;
		break;
	case 'US':
	default:
		if( isset( $_REQUEST['check']['delivery_address2'] ) ) $line .= $th_h.__( 'Shipping Address1', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_address3'] ) ) $line .= $th_h.__( 'Shipping Address2', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_address1'] ) ) $line .= $th_h.__( 'Shipping City', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_pref'] ) ) $line .= $th_h.__( 'Shipping State', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_country'] ) ) $line .= $th_h.__( 'Shipping Country', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_zip'] ) ) $line .= $th_h.__( 'Shipping Zip', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_tel'] ) ) $line .= $th_h.__( 'Shipping Phone', 'usces' ).$th_f;
		if( isset( $_REQUEST['check']['delivery_fax'] ) ) $line .= $th_h.__( 'Shipping FAX', 'usces' ).$th_f;
		break;
	}

	if( !empty( $csde_meta ) ) {
		foreach( $csde_meta as $key => $entry ) {
			if( $entry['position'] == 'fax_after' ) {
				$name = $entry['name'];
				$csde_key = 'csde_'.$key;
				if( isset( $_REQUEST['check'][$csde_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
			}
		}
	}
	$line .= apply_filters( 'usces_filter_chk_ord_label_delivery', NULL, $usces_opt_order, $rows );
	//--------------------------------------------------------------------------
	if( isset( $_REQUEST['check']['shipping_date'] ) ) $line .= $th_h.__( 'shpping date', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['peyment_method'] ) ) $line .= $th_h.__( 'payment method', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['delivery_method'] ) ) $line .= $th_h.__( 'shipping option', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['delivery_date'] ) ) $line .= $th_h.__( 'Delivery date', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['delivery_time'] ) ) $line .= $th_h.__( 'delivery time', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['delidue_date'] ) ) $line .= $th_h.__( 'Shipping date', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['status'] ) ) $line .= $th_h.__( 'Status', 'usces' ).$th_f;
	$line .= $th_h.__( 'Total Amount', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['getpoint'] ) ) $line .= $th_h.__( 'granted points', 'usces' ).$th_f;
	if( isset( $_REQUEST['check']['usedpoint'] ) ) $line .= $th_h.__( 'Used points', 'usces' ).$th_f;
	$line .= $th_h.__( 'Discount', 'usces' ).$th_f;
	if( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
		$line .= $th_h.usces_tax_label( array(), 'return' ).$th_f;
	}
	$line .= $th_h.__( 'Shipping', 'usces' ).$th_f;
	$line .= $th_h.apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ).$th_f;
	if( usces_is_tax_display() && 'all' == usces_get_tax_target() ) {
		$line .= $th_h.usces_tax_label( array(), 'return' ).$th_f;
	}
	if( isset( $_REQUEST['check']['note'] ) ) $line .= $th_h.__( 'Notes', 'usces' ).$th_f;
	if( !empty( $csod_meta ) ) {
		foreach( $csod_meta as $key => $entry ) {
			$name = $entry['name'];
			$csod_key = 'csod_'.$key;
			if( isset( $_REQUEST['check'][$csod_key] ) ) $line .= $th_h.usces_entity_decode( $name, $ext ).$th_f;
		}
	}
	$line .= apply_filters( 'usces_filter_chk_ord_label_order', NULL, $usces_opt_order, $rows );
	$line .= $tr_f.$lf;

	//==========================================================================

	foreach( (array)$rows as $array ) {
		$order_id = $array['ID'];
		$query = $wpdb->prepare( "SELECT * FROM $usces_order_table WHERE ID = %d", $order_id );
		$data = $wpdb->get_row( $query, ARRAY_A );
		$deli = unserialize( $data['order_delivery'] );

		$line .= $tr_h;
		$line .= $td_h1.$order_id.$td_f;
		$line .= $td_h.usces_get_deco_order_id( $order_id ).$td_f;
		$line .= $td_h.$data['order_date'].$td_f;
		if( isset( $_REQUEST['check']['mem_id'] ) ) $line .= $td_h.$data['mem_id'].$td_f;
		if( isset( $_REQUEST['check']['email'] ) ) $line .= $td_h.usces_entity_decode( $data['order_email'], $ext ).$td_f;
		if( !empty( $cscs_meta ) ) {
			foreach( $cscs_meta as $key => $entry ) {
				if( $entry['position'] == 'name_pre' ) {
					$name = $entry['name'];
					$cscs_key = 'cscs_'.$key;
					if( isset( $_REQUEST['check'][$cscs_key] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}

		switch( $applyform ) {
		case 'JP': 
			$line .= $td_h.usces_entity_decode( $data['order_name1'].' '.$data['order_name2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['kana'] ) ) $line .= $td_h.usces_entity_decode( $data['order_name3'].' '.$data['order_name4'], $ext ).$td_f;
			break;
		case 'US':
		default:
			$line .= $td_h.usces_entity_decode( $data['order_name2'].' '.$data['order_name1'], $ext ).$td_f;
			break;
		}

		if( !empty( $cscs_meta ) ) {
			foreach( $cscs_meta as $key => $entry ) {
				if( $entry['position'] == 'name_after' ) {
					$name = $entry['name'];
					$cscs_key = 'cscs_'.$key;
					if( isset( $_REQUEST['check'][$cscs_key] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}

		$address_info = '';
		switch( $applyform ) {
		case 'JP':
			if( isset( $_REQUEST['check']['zip'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_zip'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['country'] ) ) $address_info .= $td_h.$usces_settings['country'][$usces->get_order_meta_value( 'customer_country', $order_id )].$td_f;
			if( isset( $_REQUEST['check']['pref'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_pref'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address1'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_address1'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address2'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_address2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address3'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_address3'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['tel'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_tel'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['fax'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_fax'], $ext ).$td_f;
			break;
		case 'US':
		default:
			if( isset( $_REQUEST['check']['address2'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_address2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address3'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_address3'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['address1'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_address1'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['pref'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_pref'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['country'] ) ) $address_info .= $td_h.$usces_settings['country'][$usces->get_order_meta_value( 'customer_country', $order_id )].$td_f;
			if( isset( $_REQUEST['check']['zip'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_zip'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['tel'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_tel'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['fax'] ) ) $address_info .= $td_h.usces_entity_decode( $data['order_fax'], $ext ).$td_f;
			break;
		}
		$address_info_args = compact( 'td_h', 'td_f', 'ext', 'order_id', 'applyform' );
		$line .= apply_filters( 'usces_filter_ord_csv_address_info', $address_info, $data, $address_info_args );

		if( !empty( $cscs_meta ) ) {
			foreach( $cscs_meta as $key => $entry ) {
				if( $entry['position'] == 'fax_after' ) {
					$name = $entry['name'];
					$cscs_key = 'cscs_'.$key;
					if( isset( $_REQUEST['check'][$cscs_key] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}
		$line .= apply_filters( 'usces_filter_chk_ord_data_customer', NULL, $usces_opt_order, $order_id, $data );
		//----------------------------------------------------------------------
		if( !empty( $csde_meta ) ) {
			foreach( $csde_meta as $key => $entry ) {
				if( $entry['position'] == 'name_pre' ) {
					$name = $entry['name'];
					$csde_key = 'csde_'.$key;
					if( isset( $_REQUEST['check'][$csde_key] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}

		switch( $applyform ) {
		case 'JP':
			if( isset( $_REQUEST['check']['delivery_name'] ) ) $line .= $td_h.usces_entity_decode( $deli['name1'].' '.$deli['name2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_kana'] ) ) $line .= $td_h.usces_entity_decode( $deli['name3'].' '.$deli['name4'], $ext ).$td_f;
			break;
		case 'US':
		default:
			if( isset( $_REQUEST['check']['delivery_name'] ) ) $line .= $td_h.usces_entity_decode( $deli['name2'].' '.$deli['name1'], $ext ).$td_f;
			break;
		}

		if( !empty( $csde_meta ) ) {
			foreach( $csde_meta as $key => $entry ) {
				if( $entry['position'] == 'name_after' ) {
					$name = $entry['name']."</td>";
					$csde_key = 'csde_'.$key;
					if( isset( $_REQUEST['check'][$csde_key] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}

		$address_info = '';
		switch( $applyform ) {
		case 'JP':
			if( isset( $_REQUEST['check']['delivery_zip'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['zipcode'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_country'] ) ) $address_info .= $td_h.$usces_settings['country'][$deli['country']].$td_f;
			if( isset( $_REQUEST['check']['delivery_pref'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['pref'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_address1'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['address1'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_address2'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['address2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_address3'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['address3'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_tel'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['tel'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_fax'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['fax'], $ext ).$td_f;
			break;
		case 'US':
		default:
			if( isset( $_REQUEST['check']['delivery_address2'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['address2'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_address3'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['address3'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_address1'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['address1'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_pref'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['pref'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_country'] ) ) $address_info .= $td_h.$usces_settings['country'][$deli['country']].$td_f;
			if( isset( $_REQUEST['check']['delivery_zip'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['zipcode'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_tel'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['tel'], $ext ).$td_f;
			if( isset( $_REQUEST['check']['delivery_fax'] ) ) $address_info .= $td_h.usces_entity_decode( $deli['fax'], $ext ).$td_f;
			break;
		}
		$line .= apply_filters( 'usces_filter_ord_csv_delivery_address_info', $address_info, $deli, $address_info_args );

		if( !empty( $csde_meta ) ) {
			foreach( $csde_meta as $key => $entry ) {
				if( $entry['position'] == 'fax_after' ) {
					$name = $entry['name'];
					$csde_key = 'csde_'.$key;
					if( isset( $_REQUEST['check'][$csde_key] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
						if( empty( $value ) ) {
							$value = '';
						} elseif( is_array( $value ) ) {
							$concatval = '';
							$c = '';
							foreach( $value as $v ) {
								$concatval .= $c.$v;
								$c = ' ';
							}
							$value = $concatval;
						}
						$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
					}
				}
			}
		}
		$line .= apply_filters( 'usces_filter_chk_ord_data_delivery', NULL, $usces_opt_order, $order_id, $deli );
		//----------------------------------------------------------------------
		if( isset( $_REQUEST['check']['shipping_date'] ) ) $line .= $td_h.$data['order_modified'].$td_f;
		if( isset( $_REQUEST['check']['peyment_method'] ) ) $line .= $td_h.$data['order_payment_name'].$td_f;
		if( isset( $_REQUEST['check']['delivery_method'] ) ) {
			$delivery_method = '';
			if( strtoupper( $data['order_delivery_method'] ) == '#NONE#' ) {
				$delivery_method = __( 'No preference', 'usces' );
			} else {
				foreach( (array)$usces->options['delivery_method'] as $dkey => $delivery ) {
					if( $delivery['id'] == $data['order_delivery_method'] ) {
						$delivery_method = $delivery['name'];
						break;
					}
				}
			}
			$line .= $td_h.$delivery_method.$td_f;
		}
		if( isset( $_REQUEST['check']['delivery_date'] ) ) $line .= $td_h.$data['order_delivery_date'].$td_f;
		if( isset( $_REQUEST['check']['delivery_time'] ) ) $line .= $td_h.$data['order_delivery_time'].$td_f;
		if( isset( $_REQUEST['check']['delidue_date'] ) ) {
			$order_delidue_date = ( strtoupper( $data['order_delidue_date'] ) == '#NONE#' ) ? '' : $data['order_delidue_date'];
			$line .= $td_h.$order_delidue_date.$td_f;
		}
		if( isset( $_REQUEST['check']['status'] ) ) {
			$order_status = explode( ',', $data['order_status'] );
			$status = '';
			foreach( (array)$order_status as $os ) {
				if( isset( $usces_management_status[$os] ) ) {
					$status .= $usces_management_status[$os].$sp;
				}
			}
			$line .= $td_h.trim( $status, $sp ).$td_f;
		}
		$total_price = ( 0 < $array['total_price'] ) ? $array['total_price'] : 0;
		$line .= $td_h.usces_crform( $total_price, false, false, 'return', false ).$td_f;
		if( isset( $_REQUEST['check']['getpoint'] ) ) $line .= $td_h.$data['order_getpoint'].$td_f;
		if( isset( $_REQUEST['check']['usedpoint'] ) ) $line .= $td_h.$data['order_usedpoint'].$td_f;
		$line .= $td_h.usces_crform( $data['order_discount'], false, false, 'return', false ).$td_f;
		if( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
			$line .= $td_h.usces_crform( $data['order_tax'], false, false, 'return', false ).$td_f;
		}
		$line .= $td_h.usces_crform( $data['order_shipping_charge'], false, false, 'return', false ).$td_f;
		$line .= $td_h.usces_crform( $data['order_cod_fee'], false, false, 'return', false ).$td_f;
		if( usces_is_tax_display() && 'all' == usces_get_tax_target() ) {
			$line .= $td_h.usces_crform( $data['order_tax'], false, false, 'return', false ).$td_f;
		}
		if( isset( $_REQUEST['check']['note'] ) ) $line .= $td_h.usces_entity_decode( $data['order_note'], $ext ).$td_f;
		if( !empty( $csod_meta ) ) {
			foreach( $csod_meta as $key => $entry ) {
				$name = $entry['name'];
				$csod_key = 'csod_'.$key;
				if( isset( $_REQUEST['check'][$csod_key] ) ) {
					$value = maybe_unserialize( $usces->get_order_meta_value( $csod_key, $order_id ) );
					if( empty( $value ) ) {
						$value = '';
					} elseif( is_array( $value ) ) {
						$concatval = '';
						$c = '';
						foreach( $value as $v ) {
							$concatval .= $c.$v;
							$c = ' ';
						}
						$value = $concatval;
					}
					$line .= $td_h.usces_entity_decode( $value, $ext ).$td_f;
				}
			}
		}
		$line .= apply_filters( 'usces_filter_chk_ord_data_order', NULL, $usces_opt_order, $order_id, $data );
		$line .= $tr_f.$lf;
	}
	$line .= $table_f.$lf;
	$line = apply_filters( 'wc_filter_chk_ord_data_order', $line );

	//==========================================================================

	header( "Content-Type: application/octet-stream" );
	header( "Content-Disposition: attachment; filename=usces_order_list.".$ext );
	mb_http_output( 'pass' );
	print( mb_convert_encoding( $line, apply_filters( 'usces_filter_output_csv_encode', 'SJIS-win' ), "UTF-8" ) );
	exit();
}
