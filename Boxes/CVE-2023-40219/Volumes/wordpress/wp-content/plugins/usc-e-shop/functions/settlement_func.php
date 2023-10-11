<?php
/**
 * 決済直前ログ、決済エラーログ、受注データ復帰関連関数
 *
 * @package Welcart
 */

/**
 * 決済直前ログ、決済エラーログ、受注データ復帰関連スクリプト
 */
function usces_order_list_js_settlement_dialog() {
	?>
	settlement_operation = {
		get_settlement_log: function() {
			$( "#settlement-log-response" ).html( '<img src="<?php echo esc_url( USCES_PLUGIN_URL ); ?>/images/loading-publish.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "get_settlement_log"
				}
			}).done( function( retVal, dataType ) {
				$( "#settlement-log-response" ).html( "" );
				if ( retVal.status == "OK" ) {
					$( "#settlement-log-list" ).html( retVal.result );
				}
			}).fail( function( retVal ) {
				$( "#settlement-log-response" ).html( "" );
			});
			return false;
		},

		get_settlement_log_detail: function( log_key ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "get_settlement_log_detail",
					log_key: log_key
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					$( "#settlement-log-detail" ).html( retVal.result );
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		search_settlement_log: function( log_key ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "search_settlement_log",
					log_key: log_key
				}
			}).done( function( retVal, dataType ) {
				$( "#settlement-log-response" ).html( "" );
				if ( retVal.status == "OK" ) {
					$( "#settlement-log-list" ).html( retVal.result );
					$( "#searchLogDialog" ).dialog( "close" );
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		delete_settlement_log: function( log_key ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "delete_settlement_log",
					log_key: log_key
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					$( "#settlement-log-list" ).html( "" );
					$( "#settlement-log-list" ).html( retVal.result );
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		delete_settlement_log_all: function() {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "delete_settlement_log_all"
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					$( "#settlement-log-list" ).html( "" );
					$( "#settlement-log-list" ).html( retVal.result );
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		revival_settlement_data: function( log_key, register_date ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "revival_order_data",
					log_key: log_key,
					register_date: register_date
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					alert( "<?php esc_html_e( 'The order data has been updated', 'usces' ); ?>" );
					$( "#settlementLogDetailDialog" ).dialog( "close" );
					$( "#settlementLogDialog" ).dialog( "close" );
					location.reload();
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		get_settlement_error_log: function() {
			$( "#settlement-error-log-response" ).html( '<img src="<?php echo esc_url( USCES_PLUGIN_URL ); ?>/images/loading-publish.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "get_settlement_error_log"
				}
			}).done( function( retVal, dataType ) {
				$( "#settlement-error-log-response" ).html( "" );
				if ( retVal.status == "OK" ) {
					$( "#settlement-error-log-list" ).html( retVal.result );
				}
			}).fail( function( retVal ) {
				$( "#settlement-error-log-response" ).html( "" );
			});
			return false;
		},

		get_settlement_error_log_detail: function( log_id ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "get_settlement_error_log_detail",
					log_id: log_id
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					$( "#settlement-error-log-detail" ).html( retVal.result );
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		delete_settlement_error_log: function( log_id ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "delete_settlement_error_log",
					log_id: log_id
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					if ( retVal.nodata == "nodata" ) {
						location.reload();
					} else {
						$( "#settlement-error-log-list" ).html( "" );
						$( "#settlement-error-log-list" ).html( retVal.result );
					}
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		delete_settlement_error_log_all: function() {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "delete_settlement_error_log_all"
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.status == "OK" ) {
					location.reload();
				}
			}).fail( function( retVal ) {
			});
			return false;
		},

		output_settlement_error_log: function( log_id ) {
			location.href = "<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_orderlist&order_action=dlsettlementerrorlog&log_id="+log_id+"&noheader=true";
		},

		reset_settlement_notice: function() {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: "json",
				data: {
					action: "order_item_ajax",
					mode: "reset_settlement_notice"
				}
			}).done( function( retVal, dataType ) {
			}).fail( function( retVal ) {
			});
			return false;
		}
	};

	$( document ).on( "click", "#settlementlog", function() {
		$( "#settlementLogDialog" ).dialog( "open" );
	});

	$( "#settlementLogDialog" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: 400,
		width: 800,
		resizable: true,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Search log', 'usces' ); ?>": function() {
				$( "#searchLogDialog" ).dialog( "open" );
			},
			"<?php esc_html_e( 'Clear log', 'usces' ); ?>": function() {
				var log_key = "";
				$( ".log-check" ).each( function( i ) {
					if ( $( this ).prop( 'checked' ) ) {
						log_key += $( this ).val()+':';
					}
				});
				if ( log_key != "" ) {
					if ( confirm( "<?php esc_html_e( 'Are you sure you want to delete selected log ?', 'usces' ); ?>" ) ) {
						log_key = log_key.substr( 0, ( log_key.length - 1 ) );
						settlement_operation.delete_settlement_log( log_key );
					}
				} else {
					if ( confirm( "<?php esc_html_e( 'Are you sure you want to delete all log ?', 'usces' ); ?>" ) ) {
						settlement_operation.delete_settlement_log_all();
					}
				}
			},
			"<?php esc_html_e( 'Close' ); ?>": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		},
		open: function() {
			$( "#settlement-log-list" ).html( "" );
			settlement_operation.get_settlement_log();
		}
	});

	$( document ).on( "click", ".log-detail", function() {
		var key = $( this ).attr( "id" );
		$( "#settlement-log-detail" ).html( "" );
		settlement_operation.get_settlement_log_detail( key );
		$( "#settlementLogDetailDialog" ).dialog( "open" );
	});

	$( "#settlementLogDetailDialog" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: 600,
		width: 800,
		resizable: true,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Create order data', 'usces' ); ?>": function() {
				if ( confirm( "<?php esc_html_e( 'Are you sure you want to create the order data ?', 'usces' ); ?>" ) ) {
					settlement_operation.revival_settlement_data( $( "#log_key" ).val(), $( "input[name='register_date']:checked" ).val() );
				}
			},
			"<?php esc_html_e( 'Close' ); ?>": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});

	$( "#searchLogDialog" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: 210,
		width: 300,
		resizable: false,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Search' ); ?>": function() {
				var log_key = $( "#search_log_key" ).val();
				settlement_operation.search_settlement_log( log_key );
				$( this ).dialog( "close" );
			},
			"<?php esc_html_e( 'Close' ); ?>": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		},
		open: function() {
			$( "#search_log_key" ).val( "" );
		}
	});

	$( document ).on( "click", "#settlement_errorlog", function() {
		$( "#settlementErrorLogDialog" ).dialog( "open" );
	});

	$( "#settlementErrorLogDialog" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: 400,
		width: 800,
		resizable: true,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Output log', 'usces' ); ?>": function() {
				var log_id = "";
				$( ".error-log-check" ).each( function( i ) {
					if ( $( this ).prop( 'checked' ) ) {
						log_id += $( this ).val()+':';
					}
				});
				if ( log_id != "" ) {
					log_id = log_id.substr( 0, ( log_id.length - 1 ) );
				}
				settlement_operation.output_settlement_error_log( log_id );
			},
			"<?php esc_html_e( 'Clear log', 'usces' ); ?>": function() {
				var log_id = "";
				$( ".error-log-check" ).each( function( i ) {
					if ( $( this ).prop( 'checked' ) ) {
						log_id += $( this ).val() + ':';
					}
				});
				if ( log_id != "" ) {
					if ( confirm( "<?php esc_html_e( 'Are you sure you want to delete selected log ?', 'usces' ); ?>" ) ) {
						log_id = log_id.substr( 0, ( log_id.length - 1 ) );
						settlement_operation.delete_settlement_error_log( log_id );
					}
				} else {
					if ( confirm( "<?php esc_html_e( 'Are you sure you want to delete all log ?', 'usces' ); ?>" ) ) {
						settlement_operation.delete_settlement_error_log_all();
					}
				}
			},
			"<?php esc_html_e( 'Close' ); ?>": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			settlement_operation.reset_settlement_notice();
		},
		open: function() {
			$( "#settlement-error-log-list" ).html( "" );
			settlement_operation.get_settlement_error_log();
		}
	});

	$( document ).on( "click", ".error-log-detail", function() {
		var key = $( this ).attr( "id" );
		$( "#settlement-error-log-detaily" ).html( "" );
		settlement_operation.get_settlement_error_log_detail( key );
		$( "#settlementErrorLogDetailDialog" ).dialog( "open" );
	});

	$( "#settlementErrorLogDetailDialog" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: 600,
		width: 600,
		resizable: true,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Output log', 'usces' ); ?>": function() {
				settlement_operation.output_settlement_error_log( $( "#log_id" ).val() );
			},
			"<?php esc_html_e( 'Close' ); ?>": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
	<?php
}

/**
 * 決済直前ログダイアログ、決済エラーログダイアログ
 */
function usces_order_list_form_settlement_dialog() {
	?>
<div id="settlementLogDialog" title="<?php esc_attr_e( 'Settlement previous log list', 'usces' ); ?>" style="display:none;">
	<div id="settlement-log-response"></div>
	<fieldset>
		<div id="settlement-log-list"></div>
	</fieldset>
</div>
<div id="settlementLogDetailDialog" title="<?php esc_attr_e( 'Settlement previous log detail', 'usces' ); ?>" style="display:none;">
	<fieldset>
		<div id="settlement-log-detail"></div>
	</fieldset>
</div>
<div id="searchLogDialog" title="<?php esc_attr_e( 'Search log', 'usces' ); ?>" style="display:none;">
	<fieldset>
		<div><?php wel_esc_script_e( __( 'Enter the link key, <br />please click on the "Search" button.', 'usces' ) ); ?></div>
		<p><input type="text" id="search_log_key" class="text" ></p>
	</fieldset>
</div>
<div id="settlementErrorLogDialog" title="<?php esc_attr_e( 'Settlement error log list', 'usces' ); ?>" style="display:none;">
	<div id="settlement-error-log-response"></div>
	<fieldset>
		<div id="settlement-error-log-list"></div>
	</fieldset>
</div>
<div id="settlementErrorLogDetailDialog" title="<?php esc_attr_e( 'Settlement error log detail', 'usces' ); ?>" style="display:none;">
	<fieldset>
		<div id="settlement-error-log-detail"></div>
	</fieldset>
</div>
	<?php
}

/**
 * 決済直前ログ保存
 *
 * @param string  $key Link key.
 * @param boolean $mobile Is mobile.
 * @return mixed
 */
function usces_save_order_acting_data( $key, $mobile = false ) {
	global $usces, $wpdb;

	$log_table_name       = $wpdb->prefix . 'usces_log';
	$data                 = array();
	$data['usces_cart']   = ( isset( $_SESSION['usces_cart'] ) ) ? $_SESSION['usces_cart'] : array();
	$data['usces_entry']  = ( isset( $_SESSION['usces_entry'] ) ) ? $_SESSION['usces_entry'] : array();
	$data['usces_member'] = ( isset( $_SESSION['usces_member'] ) ) ? $_SESSION['usces_member'] : array();
	$data['remote_addr']  = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '';
	if ( $mobile ) {
		$data['wcex_mobile'] = $mobile;
	}
	$data = apply_filters( 'usces_filter_save_order_acting_data', $data );

	$order_data = usces_get_acting_data( $key );
	if ( empty( $order_data['datetime'] ) ) {
		$query = $wpdb->prepare(
			"INSERT INTO {$log_table_name} ( `datetime`, `log`, `log_type`, `log_key` ) VALUES ( %s, %s, %s, %s )",
			current_time( 'mysql' ),
			serialize( $data ),
			'acting_data',
			$key
		);
	} else {
		$query = $wpdb->prepare(
			"UPDATE {$log_table_name} SET `datetime` = %s,`log` = %s WHERE `log_type` = %s AND `log_key` = %s",
			current_time( 'mysql' ),
			serialize( $data ),
			'acting_data',
			$key
		);
	}
	$res = $wpdb->query( $query );
	return $res;
}

/**
 * 決済直前ログ取得
 *
 * @param string $key Log key.
 * @return array
 */
function usces_get_acting_data( $key ) {
	global $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$query          = $wpdb->prepare( "SELECT * FROM {$log_table_name} WHERE `log_type` = %s AND `log_key` = %s", 'acting_data', $key );
	$data           = $wpdb->get_row( $query, ARRAY_A );
	if ( $data ) {
		$order_data             = unserialize( $data['log'] );
		$order_data['key']      = $data['log_key'];
		$order_data['datetime'] = $data['datetime'];
	} else {
		$order_data = array(
			'usces_cart'   => array(),
			'usces_entry'  => array(),
			'usces_member' => array(),
			'remote_addr'  => '',
			'key'          => $key,
			'datetime'     => '',
		);
	}
	return $order_data;
}

/**
 * 決済直前データ取得
 *
 * @param string $key Log key.
 * @return array
 */
function usces_restore_order_acting_data( $key ) {
	$order_data               = usces_get_acting_data( $key );
	$_SESSION['usces_cart']   = $order_data['usces_cart'];
	$_SESSION['usces_entry']  = $order_data['usces_entry'];
	$_SESSION['usces_member'] = $order_data['usces_member'];
	do_action( 'usces_action_restore_order_acting_data', $order_data );
	return $order_data;
}

/**
 * 決済エラーログ保存
 *
 * @param array   $log Log data.
 * @param boolean $mobile Is mobile.
 * @return mixed
 */
function usces_save_order_acting_error( $log, $mobile = false ) {
	global $usces, $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$datetime       = current_time( 'mysql' );
	update_option( 'usces_settlement_notice', $datetime );

	do_action( 'usces_action_save_order_acting_error', $log, $datetime );

	$query = $wpdb->prepare( "INSERT INTO {$log_table_name} ( `datetime`, `log`, `log_type`, `log_key` ) VALUES ( %s, %s, %s, %s )",
		$datetime,
		serialize( $log ),
		'acting_error',
		$log['key']
	);
	$res = $wpdb->query( $query );
	return $res;
}

/**
 * 決済直前ログ一覧
 *
 * @param string $log_key Log key.
 * @return void
 */
function usces_get_settlement_log( $log_key = '' ) {
	global $usces, $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$html           = '';
	if ( ! empty( $log_key ) ) {
		$query = $wpdb->prepare( "SELECT * FROM {$log_table_name} WHERE `log_type` = 'acting_data' AND `log_key` = %s ORDER BY datetime DESC", $log_key );
	} else {
		$query = "SELECT * FROM {$log_table_name} WHERE `log_type` = 'acting_data' ORDER BY datetime DESC";
	}
	$log_data = $wpdb->get_results( $query, ARRAY_A );
	if ( $log_data ) {
		$html = '<table class="list"><tr><th></th><th></th><th>' . __( 'Register date', 'usces' ) . '</th><th>' . __( 'Link key', 'usces' ) . '</th><th>' . __( 'Name', 'usces' ) . '</th><th>' . __( 'Type of payment', 'usces' ) . '</th><th>IP</th></tr>';
		foreach ( (array) $log_data as $data ) {
			$log               = unserialize( $data['log'] );
			$name              = $log['usces_entry']['customer']['name1'] . $log['usces_entry']['customer']['name2'];
			$payment_name      = $log['usces_entry']['order']['payment_name'];
			$payments          = usces_get_payments_by_name( $payment_name );
			$settlement        = ( isset( $payments['settlement'] ) ) ? $payments['settlement'] : '';
			$payment_structure = ( isset( $usces->payment_structure[ $settlement ] ) ) ? '[' . $usces->payment_structure[ $settlement ] . ']' : '';
			$class             = ( ! empty( $log['revival'] ) ) ? ' class="revival"' : '';
			$ip                = ( ! empty( $log['remote_addr'] ) ) ? $log['remote_addr'] : '';
			$html             .= '<tr' . $class . '>
				<td class="check"><input type="checkbox" class="log-check" value="' . $data['log_key'] . '"></td>
				<td class="detail"><input type="button" class="log-detail button" id="' . $data['log_key'] . '" value="' . __( 'Detail', 'usces' ) . '"></td>
				<td class="datetime">' . $data['datetime'] . '</td>
				<td class="key">' . $data['log_key'] . '</td>
				<td class="name">' . $name . '</td>
				<td class="payment">' . $payment_name . $payment_structure . '</td>
				<td class="ip">' . $ip . '</td>
			</tr>';
		}
		$html .= '</table>';
	} else {
		$html = '<div class="nodata">' . __( 'There are no log data . ', 'usces' ) . '</div>';
	}

	$resdata           = array();
	$resdata['status'] = 'OK';
	$resdata['result'] = $html;
	$resdata['nodata'] = ( ! $log_data ) ? 'nodata' : '';
	wp_send_json( $resdata );
}

/**
 * 決済直前ログ詳細
 * from Order item ajax.
 *
 * @param string $log_key Log key.
 */
function usces_get_settlement_log_detail( $log_key ) {
	global $usces;

	$order_data    = usces_get_acting_data( $log_key );
	$usces_entries = $order_data['usces_entry'];

	$html  = '<table class="detail-head">';
	$html .= '<tr><th>' . __( 'Register date', 'usces' ) . '</th><td>' . $order_data['datetime'] . '</td><td>
		<label for="register_date_1"><input type="radio" name="register_date" id="register_date_1" value="1" checked="checked" /><span>' . __( 'Set the Registered Date to the Order Date.', 'usces' ) . '</span></label><br />
		<label for="register_date_0"><input type="radio" name="register_date" id="register_date_0" value="0" /><span>' . __( 'Set the current time to the Order Date.', 'usces' ) . '</span></label></td></tr>';
	$html .= '<tr><th>' . __( 'Link key', 'usces' ) . '</th><td colspan="2">' . $order_data['key'] . '</td></tr>';
	$html .= '</table>';

	$html .= '<table class="detail-customer">';
	$html .= '<tr class="title"><td colspan="2"><h3>' . __( 'Customer Information', 'usces' ) . '</h3></td></tr>';
	$html .= '<tr><th>' . __( 'e-mail adress', 'usces' ) . '</th><td>' . esc_html( $usces_entries['customer']['mailaddress1'] ) . '</td></tr>';
	$html .= uesces_addressform( 'confirm', $usces_entries );
	$html .= '</table>';

	$html .= '<table class="detail-other">';
	$html .= '<tr class="title"><td colspan="2"><h3>' . __( 'Others', 'usces' ) . '</h3></td></tr>';
	if ( isset( $usces_entries['delivery'] ) ) {
		$html .= '<tr><th>' . __( 'shipping option', 'usces' ) . '</th><td>' . esc_html( usces_delivery_method_name( $usces_entries['order']['delivery_method'], 'return' ) ) . '</td></tr>';
		$html .= '<tr><th>' . __( 'Delivery date', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['delivery_date'] ) . '</td></tr>';
		$html .= '<tr><th>' . __( 'Delivery Time', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['delivery_time'] ) . '</td></tr>';
	}
	$html .= '<tr><th>' . __( 'payment method', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['payment_name'] . usces_payment_detail( $usces_entries ) ) . '</td></tr>';
	$html .= usces_custom_field_info( $usces_entries, 'order', '', 'return' );
	$html .= '<tr><th>' . __( 'Notes', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['note'] ) . '</td></tr>';
	$html .= '</table>';

	$html .= '<table class="detail-cart">';
	$html .= '<thead>';
	$html .= '<tr class="title"><td colspan="4"><h3>' . __( 'Cart', 'usces' ) . '</h3></td></tr>';
	$html .= '<tr><th>No</th><th>' . __( 'Items', 'usces' ) . '</th><th>' . __( 'Quantity', 'usces' ) . '</th><th>' . __( 'Unit price', 'usces' ) . '</th></tr>';
	$html .= '</thead><tbody>';
	$num   = 1;
	foreach ( $order_data['usces_cart'] as $serial => $row ) {
		$array      = unserialize( $serial );
		$ids        = array_keys( $array );
		$skus       = array_keys( $array[ $ids[0] ] );
		$post_id    = $ids[0];
		$sku        = $skus[0];
		$options    = $array[ $ids[0] ][ $skus[0] ];
		$opt_fields = usces_get_opts( $post_id, 'sort' );
		$optstr     = '';
		foreach ( (array) $opt_fields as $key => $field ) {
			$name             = urlencode( $field['name'] );
			$options[ $name ] = ( isset( $options[ $name ] ) ) ? $options[ $name ] : '';
			if ( ! empty( $name ) ) {
				$key   = urldecode( $name );
				$value = maybe_unserialize( $options[ $name ] );
				if ( is_array( $value ) ) {
					$c       = '';
					$optstr .= esc_html( $key ) . ' : ';
					foreach ( $value as $v ) {
						$optstr .= $c . nl2br( esc_html( urldecode( $v ) ) );
						$c       = ', ';
					}
					$optstr .= '<br />';
				} else {
					$optstr .= esc_html( $key ) . ' : ' . nl2br( esc_html( urldecode( $value ) ) ) . '<br />';
				}
			}
		}
		$quantity       = ( isset( $row['quant'] ) ) ? $row['quant'] : 0;
		$price          = ( isset( $row['price'] ) ) ? $row['price'] : 0;
		$cart_item_name = $usces->getCartItemName( $post_id, $sku );

		$html .= '<tr>';
		$html .= '<td class="num">' . $num . '</td>';
		$html .= '<td class="item_name">' . esc_html( urldecode( $cart_item_name ) ) . '<br />' . $optstr . '</td>';
		$html .= '<td class="quantity">' . $quantity . '</td>';
		$html .= '<td class="price">' . usces_crform( $price, true, false, 'return' ) . '</td>';
		$html .= '</tr>';
		$num++;
	}
	$html .= '</tbody><tfoot>';
	$html .= '<tr><th colspan="3">' . __( 'total items', 'usces' ) . '</th><td class="total_items_price">' . usces_crform( $usces_entries['order']['total_items_price'], true, false, 'return' ) . '</td></tr>';
	if ( ! empty( $usces_entries['order']['discount'] ) ) {
		$html .= '<tr><th colspan="3">' . apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ) ) . '</th><td class="discount">' . usces_crform( $usces_entries['order']['discount'], true, false, 'return' ) . '</td></tr>';
	}
	if ( usces_is_tax_display() && 'products' === usces_get_tax_target() ) {
		$html .= '<tr><th colspan="3">' . usces_tax_label( array(), 'return' ) . '</th><td class="tax">' . usces_tax( $usces_entries, 'return' ) . '</td></tr>';
	}
	$html   .= '<tr><th colspan="3">' . __( 'Shipping', 'usces' ) . '</th><td class="shipping_charge">' . usces_crform( $usces_entries['order']['shipping_charge'], true, false, 'return' ) . '</td></tr>';
	$payment = usces_get_payments_by_name( $usces_entries['order']['payment_name'] );
	if ( 'COD' === $payment['settlement'] ) {
		$html .= '<tr><th colspan="3">' . apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ) . '</th><td class="cod_fee">' . usces_crform( $usces_entries['order']['cod_fee'], true, false, 'return' ) . '</td></tr>';
	}
	if ( usces_is_tax_display() && 'all' === usces_get_tax_target() ) {
		$html .= '<tr><th colspan="3">' . usces_tax_label( array(), 'return' ) . '</th><td class="tax">' . usces_tax( $usces_entries, 'return' ) . '</td></tr>';
	}
	if ( usces_is_member_system() && usces_is_member_system_point() && ! empty( $usces_entries['order']['usedpoint'] ) ) {
		$html .= '<tr><th colspan="3">' . __( 'Used points', 'usces' ) . '</th><td class="usedpoint">' . number_format( $usces_entries['order']['usedpoint'] ) . '</td></tr>';
	}
	$html .= '<tr><th colspan="3">' . __( 'Payment amount', 'usces' ) . '</th><td class="total_full_price">' . usces_crform( $usces_entries['order']['total_full_price'], true, false, 'return' ) . '</td></tr>';
	$html .= '</tfoot></table>';
	$html .= '<input type="hidden" id="log_key" value="' . $order_data['key'] . '" />';

	$resdata           = array();
	$resdata['status'] = 'OK';
	$resdata['result'] = $html;
	wp_send_json( $resdata );
}

/**
 * 決済直前ログ削除
 *
 * @param string $log_key Log key.
 * @return mixed
 */
function usces_delete_settlement_log( $log_key = '' ) {
	global $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	if ( '' !== $log_key ) {
		$keys  = explode( ':', $log_key );
		$query = $wpdb->prepare( "DELETE FROM {$log_table_name} WHERE `log_type` = 'acting_data' AND `log_key` IN( %s )", implode( "','", $keys ) );
		$query = stripslashes( $query );
	} else {
		$query = "DELETE FROM {$log_table_name} WHERE `log_type` = 'acting_data'";
	}
	$res = $wpdb->query( $query );
	return $res;
}

/**
 * 受注データ再作成
 *
 * @param string $log_key Log key.
 * @param int    $register_date 0:受注日時に現在の時刻を設定 1:受注日時に登録日を設定.
 * @return void
 */
function usces_revival_order_data( $log_key, $register_date = 0 ) {
	global $usces;

	$order_data = usces_restore_order_acting_data( $log_key );
	$results    = array();
	if ( 1 === (int) $register_date ) {
		$results['order_date'] = $order_data['datetime'];
	}
	$order_id = usces_reg_orderdata( $results );

	$payments = $usces->getPayments( $order_data['usces_entry']['order']['payment_name'] );
	$acting   = ( 'acting' === $payments['settlement'] ) ? $payments['module'] : $payments['settlement'];
	$data     = array();
	switch ( $acting ) {
		case 'paypal.php':
			$usces->set_order_meta_value( 'settlement_id', $log_key, $order_id );
			break;

		case 'epsilon.php':
			$usces->set_order_meta_value( 'settlement_id', $log_key, $order_id );
			break;

		case 'acting_remise_card':
		case 'acting_remise_conv':
			$usces->set_order_meta_value( 'settlement_id', $log_key, $order_id );
			break;

		case 'acting_jpayment_card':
		case 'acting_jpayment_conv':
		case 'acting_jpayment_bank':
			$usces->set_order_meta_value( 'settlement_id', $log_key, $order_id );
			break;

		case 'acting_paypal_ec':
			break;

		case 'acting_paypal_wpp':
			$data['custom'] = $log_key;
			$usces->set_order_meta_value( $acting, serialize( $data ), $order_id );
			break;

		case 'acting_telecom_card':
		case 'acting_telecom_edy':
			$data['option'] = $log_key;
			$usces->set_order_meta_value( $acting, serialize( $data ), $order_id );
			break;

		case 'acting_digitalcheck_card':
		case 'acting_digitalcheck_conv':
			$data['SID'] = $log_key;
			$usces->set_order_meta_value( $acting, serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'SID', $log_key, $order_id );
			break;

		case 'acting_mizuho_card':
		case 'acting_mizuho_conv1':
		case 'acting_mizuho_conv2':
			$data['stran'] = $log_key;
			$usces->set_order_meta_value( $acting, serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'stran', $log_key, $order_id );
			break;

		case 'acting_anotherlane_card':
			$data['LINK_KEY'] = $log_key;
			$usces->set_order_meta_value( $acting, serialize( $data ), $order_id );
			break;

		case 'acting_veritrans_card':
		case 'acting_veritrans_conv':
			$data['orderId'] = $log_key;
			$usces->set_order_meta_value( $acting, serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'orderId', $log_key, $order_id );
			break;
	}

	usces_already_revival_order_data( $order_data );
	do_action( 'usces_action_revival_order_data', $order_id, $log_key, $acting );

	$resdata             = array();
	$resdata['status']   = 'OK';
	$resdata['order_id'] = $order_id;
	wp_send_json( $resdata );
}

/**
 * 受注データ再作成済み
 *
 * @param array $order_data Order data.
 */
function usces_already_revival_order_data( $order_data ) {
	global $wpdb;

	$log_table_name        = $wpdb->prefix . 'usces_log';
	$order_data['revival'] = 'done';
	$query                 = $wpdb->prepare( "UPDATE {$log_table_name} SET `log` = %s WHERE `datetime` = %s AND `log_type` = %s AND `log_key` = %s", serialize( $order_data ), $order_data['datetime'], 'acting_data', $order_data['key'] );
	$res                   = $wpdb->query( $query );
}

/**
 * 決済エラーログ一覧
 */
function usces_get_settlement_error_log() {
	global $usces, $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$html           = '';
	$query          = "SELECT * FROM {$log_table_name} WHERE `log_type` = 'acting_error' ORDER BY datetime DESC";
	$log_data       = $wpdb->get_results( $query, ARRAY_A );
	if ( $log_data ) {
		$html = '<table class="list"><tr><th></th><th></th><th>' . __( 'Register date', 'usces' ) . '</th><th>' . __( 'Link key', 'usces' ) . '</th><th>' . __( 'Type of payment', 'usces' ) . '</th><th>' . __( 'Status', 'usces' ) . '</th></tr>';
		foreach ( (array) $log_data as $data ) {
			$log    = unserialize( $data['log'] );
			$result = ( ! empty( $log['result'] ) ) ? $log['result'] : '';
			$acting = ( ! empty( $log['acting'] ) ) ? $log['acting'] : '';
			$html  .= '<tr>
				<td class="check"><input type="checkbox" class="error-log-check" value="' . $data['ID'] . '"></td>
				<td class="detail"><input type="button" class="error-log-detail button" id="' . $data['ID'] . '" value="' . __( 'Detail', 'usces' ) . '"></td>
				<td class="datetime">' . $data['datetime'] . '</td>
				<td class="key">' . $data['log_key'] . '</td>
				<td class="payment">' . $acting . '</td>
				<td class="status">' . $result . '</td>
			</tr>';
		}
		$html .= '</table>';
	} else {
		$html = '<div class="nodata">' . __( 'There are no log data . ', 'usces' ) . '</div>';
		usces_reset_settlement_notice();
	}

	$resdata           = array();
	$resdata['status'] = 'OK';
	$resdata['result'] = $html;
	$resdata['nodata'] = ( ! $log_data ) ? 'nodata' : '';
	wp_send_json( $resdata );
}

/**
 * 決済エラーログ付加情報
 *
 * @return array
 */
function usces_get_settlement_error_log_exemption() {
	$exemption = array( 'uscesid', 'acting', 'acting_return', 'purchase', 'purchase_ali', 'purchase_jpayment', 'page_id', 'CardNo' );
	return $exemption;
}

/**
 * 決済エラーログ詳細
 *
 * @param string $log_id Log ID.
 * @return void
 */
function usces_get_settlement_error_log_detail( $log_id ) {
	global $usces, $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$exemption      = usces_get_settlement_error_log_exemption();
	$html           = '';
	$query          = $wpdb->prepare( "SELECT * FROM {$log_table_name} WHERE `log_type` = %s AND `ID` = %s", 'acting_error', $log_id );
	$data           = $wpdb->get_row( $query, ARRAY_A );
	if ( $data ) {
		$log   = unserialize( $data['log'] );
		$html  = '<table class="detail">';
		$html .= '<tr><th>' . __( 'Register date', 'usces' ) . '</th><td>' . $data['datetime'] . '</td></tr>';
		$html .= '<tr><th>' . __( 'Link key', 'usces' ) . '</th><td>' . $log['key'] . '</td></tr>';
		$html .= '<tr><th>' . __( 'Result', 'usces' ) . '</th><td>' . $log['result'] . '</td></tr>';
		foreach ( (array) $log['data'] as $key => $value ) {
			if ( in_array( $key, $exemption ) ) {
				continue;
			}
			if ( is_array( $value ) ) {
				foreach ( $value as $key2 => $value2 ) {
					if ( in_array( $key2, $exemption ) ) {
						continue;
					}
					if ( is_array( $value2 ) ) {
						$html .= '<tr><th>' . $key . ':' . $key2 . '</th><td>' . esc_html( serialize( $value2 ) ) . '</td></tr>';
					} else {
						$html .= '<tr><th>' . $key . ':' . $key2 . '</th><td>' . esc_html( $value2 ) . '</td></tr>';
					}
				}
			} else {
				$html .= '<tr><th>' . $key . '</th><td>' . esc_html( $value ) . '</td></tr>';
			}
		}
		$html .= '</table>';
		$html .= '<input type="hidden" id="log_id" value="' . $data['ID'] . '" />';
	}

	$resdata           = array();
	$resdata['status'] = 'OK';
	$resdata['result'] = $html;
	wp_send_json( $resdata );
}

/**
 * 決済エラーログ削除
 *
 * @param string $log_id Log ID.
 * @return mixed
 */
function usces_delete_settlement_error_log( $log_id = '' ) {
	global $usces, $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	if ( '' !== $log_id ) {
		$ids   = explode( ':', $log_id );
		$query = $wpdb->prepare( "DELETE FROM {$log_table_name} WHERE `log_type` = 'acting_error' AND `ID` IN( %s )", implode( "','", $ids ) );
		$query = stripslashes( $query );
	} else {
		$query = "DELETE FROM {$log_table_name} WHERE `log_type` = 'acting_error'";
	}
	$res = $wpdb->query( $query );
	return $res;
}

/**
 * 決済エラー通知リセット
 */
function usces_reset_settlement_notice() {
	global $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$query          = "SELECT * FROM {$log_table_name} WHERE `log_type` = 'acting_error'";
	$log_data       = $wpdb->get_results( $query, ARRAY_A );
	if ( ! $log_data ) {
		update_option( 'usces_settlement_notice', false );
	}

	$resdata           = array();
	$resdata['status'] = 'OK';
	wp_send_json( $resdata );
}

/**
 * 決済エラー通知
 */
function usces_display_settlement_notice() {
	$datetime = get_option( 'usces_settlement_notice' );
	echo '<div class="message error"><p>' . sprintf( __( "Settlement error has occurred. Please check <a href=\"admin.php?page=usces_orderlist&order_action=settlement_notice\">the settlement error log</a>. The date of occurrence:[ %s ]", 'usces' ), $datetime ) . '</p></div>';
}

/**
 * 決済エラーログダウンロード
 */
function usces_download_settlement_error_log() {
	global $usces, $wpdb;

	$log_table_name = $wpdb->prefix . 'usces_log';
	$exemption      = usces_get_settlement_error_log_exemption();
	$line           = '';
	if ( ! empty( $_GET['log_id'] ) ) {
		$ids   = explode( ':', wp_unslash( $_GET['log_id'] ) );
		$query = $wpdb->prepare( "SELECT * FROM {$log_table_name} WHERE `log_type` = 'acting_error' AND `ID` IN( %s )", implode( "','", $ids ) );
		$query = stripslashes( $query );
	} else {
		$query = "SELECT * FROM {$log_table_name} WHERE `log_type` = 'acting_error' ORDER BY datetime DESC";
	}
	$log_data = $wpdb->get_results( $query, ARRAY_A );
	foreach ( (array) $log_data as $data ) {
		$log   = unserialize( $data['log'] );
		$line .= __( 'Register date', 'usces' ) . ' = ' . $data['datetime'] . "\r\n";
		$line .= __( 'Link key', 'usces' ) . ' = ' . $log['key'] . "\r\n";
		$line .= __( 'Result', 'usces' ) . ' = ' . $log['result'] . "\r\n";
		foreach ( (array) $log['data'] as $key => $value ) {
			if ( in_array( $key, $exemption ) ) {
				continue;
			}
			if ( is_array( $value ) ) {
				$line .= $key . ' = ' . esc_html( implode( ' ', $value ) ) . "\r\n";
			} else {
				$line .= $key . ' = ' . esc_html( $value ) . "\r\n";
			}
		}
		$line .= "--------------------------------------------------\r\n\r\n";
	}
	$line = mb_convert_encoding( $line, 'SJIS-win', 'UTF-8' );

	$filename = 'settlement_error_' . wp_date( 'YmdHis' ) . '.log';
	header( 'Content-type: text/plain;charset=Shift_JIS' );
	header( "Content-Disposition: attachment; filename=$filename" );
	print( $line );
	exit();
}

/**
 * Verify that the card information update action is correct.
 * Members who request renewal more than 10 times an hour
 * will be locked and will not be able to renew their card information
 * until the administrator unlocks it.
 *
 * @since 2.5.8
 *
 * @param int $member_id Member ID.
 * @return boolean Return true if there is no problem, false otherwise.
 */
function wel_verify_update_settlement( $member_id ) {
	global $usces;

	$rightfulness = true;
	if ( 1 === USCES_CREDITCARD_SECURITY::$option['active'] ) {
		$is_lock = $usces->get_member_meta_value( 'settlement_action_lock', $member_id );
		// wel_log_card_update_action( $member_id );

		// $number_limit = apply_filters( 'usces_filter_update_settlement_number_limit', 10 );

		if ( $is_lock ) {
			$rightfulness = false;
		} else {
			$number_limit = apply_filters( 'usces_filter_update_settlement_number_limit', USCES_CREDITCARD_SECURITY::$option['set_count'] );
			$action_num   = wel_count_card_update_action( $member_id );
			if ( $number_limit > $action_num ) {
				$rightfulness = true;
				wel_log_card_update_action( $member_id );
			} else {
				$usces->set_member_meta_value( 'settlement_action_lock', current_time( 'mysql' ), $member_id );
				$rightfulness = false;
			}
		}
	}
	return $rightfulness;
}

/**
 * Log card information update action.
 *
 * @since 2.5.8
 *
 * @param int $member_id Member ID.
 */
function wel_log_card_update_action( $member_id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'usces_log';
	$wpdb->query(
		$wpdb->prepare(
			"INSERT INTO $table_name ( `datetime`, `log_type`, `log_key` ) VALUES ( %s, %s, %s )",
			current_time( 'Y-m-d H:i:s' ),
			'card_update_action',
			$member_id
		)
	);
}

/**
 * Count card information update action.
 *
 * @since 2.5.8
 *
 * @param int $member_id Member ID.
 * @return int Return number of action.
 */
function wel_count_card_update_action( $member_id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'usces_log';
	// $due_time   = (int) current_time( 'timestamp' ) - 3600;
	$time_frame = USCES_CREDITCARD_SECURITY::$option['time_frame'];
	$due_time   = (int) current_time( 'timestamp' ) - ( $time_frame * 60 );
	$res        = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT( `ID` ) FROM $table_name WHERE `datetime` > %s AND `log_type` = %s AND `log_key` = %s",
			date_i18n( 'Y-m-d H:i:s', $due_time ),
			'card_update_action',
			$member_id
		)
	);
	return (int) $res;
}

/**
 * Release the card information update lock.
 *
 * @since 2.5.8
 *
 * @param int $member_id Member ID.
 */
function wel_release_card_update_lock( $member_id ) {
	global $wpdb, $usces;

	$usces->del_member_meta( 'settlement_action_lock', $member_id );

	$table_name = $wpdb->prefix . 'usces_log';
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM $table_name WHERE `log_type` = %s AND `log_key` = %s",
			'card_update_action',
			$member_id
		)
	);
}

/**
 * All credit security unlocked.
 */
function wel_all_unlock() {
	global $wpdb;

	$member_meta_table = usces_get_tablename( 'usces_member_meta' );
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$member_meta_table} WHERE `meta_key` = %s",
			'settlement_action_lock'
		)
	);
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}usces_log WHERE `log_type` = %s",
			'card_update_action'
		)
	);
}

/**
 * Is lockout credit card input per terminal.
 *
 * @param string $remote_addr IP address.
 * @return bool
 */
function wel_is_lockout_credit_input( $remote_addr ) {
	global $wpdb;

	$lockout = false;

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(`ID`) FROM {$wpdb->prefix}usces_log WHERE `log` IS NOT NULL AND `log` > %s AND `log_type` = %s AND `log_key` = %s",
			current_time( 'Y-m-d H:i:s' ),
			'card_update_action',
			$remote_addr
		)
	);
	if ( 0 < $count ) {
		$lockout = true;
	}
	return $lockout;
}

/**
 * Count credit card input per terminal.
 *
 * @param string $remote_addr IP address.
 * @return int
 */
function wel_count_credit_input( $remote_addr ) {
	global $wpdb;

	$time_frame = USCES_CREDITCARD_SECURITY::$option['time_frame'];
	$due_time   = (int) current_time( 'timestamp' ) - ( $time_frame * 60 );
	$count      = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(`ID`) FROM {$wpdb->prefix}usces_log WHERE `datetime` > %s AND `log_type` = %s AND `log_key` = %s",
			date_i18n( 'Y-m-d H:i:s', $due_time ),
			'card_update_action',
			$remote_addr
		)
	);
	return (int) $count;
}

/**
 * Log credit card input per terminal.
 *
 * @param string $remote_addr IP address.
 */
function wel_log_credit_input( $remote_addr ) {
	global $wpdb;

	$wpdb->query(
		$wpdb->prepare(
			"INSERT INTO {$wpdb->prefix}usces_log ( `datetime`, `log_type`, `log_key` ) VALUES ( %s, %s, %s )",
			current_time( 'Y-m-d H:i:s' ),
			'card_update_action',
			$remote_addr
		)
	);
}

/**
 * Log credit card lockout per terminal.
 *
 * @param string $remote_addr IP address.
 */
function wel_lockout_credit_input( $remote_addr ) {
	global $wpdb;

	$lockout_time = USCES_CREDITCARD_SECURITY::$option['lockout_time'];
	$due_time     = (int) current_time( 'timestamp' ) + ( $lockout_time * 60 * 60 );
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->prefix}usces_log SET `log` = %s WHERE `log_type` = %s AND `log_key` = %s",
			date_i18n( 'Y-m-d H:i:s', $due_time ),
			'card_update_action',
			$remote_addr
		)
	);
}

/**
 * Check credit security.
 *
 * @return bool
 */
function wel_check_credit_security() {
	global $usces;

	$check = true;
	if ( 1 === USCES_CREDITCARD_SECURITY::$option['active'] ) {
		if ( $usces->is_member_logged_in() ) {
			$member  = $usces->get_member();
			$is_lock = $usces->get_member_meta_value( 'settlement_action_lock', $member['ID'] );
			if ( $is_lock ) {
				$check = false;
			}
		}
		if ( $check ) {
			$remote_addr = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '';
			if ( empty( $remote_addr ) ) {
				$check = false;
			} else {
				if ( wel_is_lockout_credit_input( $remote_addr ) ) {
					$check = false;
				} else {
					$count = wel_count_credit_input( $remote_addr );
					if ( USCES_CREDITCARD_SECURITY::$option['set_count'] > $count ) {
						wel_log_credit_input( $remote_addr );
					} else {
						wel_lockout_credit_input( $remote_addr );
						$check = false;
					}
				}
			}
		}
	}
	return $check;
}

/**
 * Credit card input log clearup.
 */
function wel_clearup_log_credit_input() {
	global $wpdb;

	if ( 1 === USCES_CREDITCARD_SECURITY::$option['active'] ) {
		$time_frame = USCES_CREDITCARD_SECURITY::$option['time_frame'];
		$due_time   = (int) current_time( 'timestamp' ) - ( $time_frame * 60 );
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}usces_log WHERE `datetime` < %s AND ( `log` IS NULL OR `log` < %s ) AND `log_type` = %s",
				date_i18n( 'Y-m-d H:i:s', $due_time ),
				current_time( 'Y-m-d H:i:s' ),
				'card_update_action'
			)
		);
	}
}
