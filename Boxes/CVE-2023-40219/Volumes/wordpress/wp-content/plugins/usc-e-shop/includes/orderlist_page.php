<?php
/**
 * Order List Page.
 * - New type
 *
 * @package Welcart
 */

global $usces_settings;

require_once USCES_PLUGIN_DIR . '/classes/orderList2.class.php';
// phpcs:disable
$DT         = new WlcOrderList();
$arr_column = $DT->get_column();
$res        = $DT->MakeTable();

$arr_search            = $DT->GetSearchs();
$arr_header            = $DT->GetListheaders();
$dataTableNavigation   = $DT->GetDataTableNavigation();
$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
$rows                  = $DT->rows;
$status                = apply_filters( 'usces_order_list_action_status', $DT->get_action_status() );
$message               = apply_filters( 'usces_order_list_action_message', $DT->get_action_message() );
// phpcs:enable

$usces_admin_path = '';
$admin_perse      = explode( '/', $_SERVER['REQUEST_URI'] ); // phpcs:ignore
$apct             = count( $admin_perse ) - 1;
for ( $ap = 0; $ap < $apct; $ap++ ) {
	$usces_admin_path .= $admin_perse[ $ap ] . '/';
}
$list_option       = get_option( 'usces_orderlist_option' );
$management_status = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
$usces_country     = $usces_settings['country'];
$pref              = array();
$target_market     = $this->options['system']['target_market'];
foreach ( (array) $target_market as $country ) {
	$prefs       = get_usces_states( $country );
	$prefs_count = count( $prefs );
	if ( is_array( $prefs ) && 0 < $prefs_count ) {
		$pos = strpos( $prefs[0], '--' );
		if ( false !== $pos ) {
			array_shift( $prefs );
		}
		foreach ( (array) $prefs as $state ) {
			$pref[] = $state;
		}
	}
}
$payment_name = array();
$payments     = usces_get_system_option( 'usces_payment_method', 'sort' );
foreach ( (array) $payments as $id => $payment ) { // phpcs:ignore
	$payment_name[ $id ] = $payment['name'];
}
foreach ( (array) $management_status as $key => $value ) {
	if ( 'noreceipt' == $key || 'receipted' == $key || 'pending' == $key ) {
		$receipt_status[ $key ] = $value;
	} elseif ( 'adminorder' == $key || 'estimate' == $key ) {
		$estimate_status[ $key ]       = $value;
		$estimate_status['frontorder'] = __( 'Order', 'usces' );
	} else {
		$process_status[ $key ]     = $value;
		$process_status['neworder'] = __( 'new order', 'usces' );
	}
}
$curent_url  = urlencode( esc_url( USCES_ADMIN_URL . '?' . $_SERVER['QUERY_STRING'] ) ); // phpcs:ignore
$server_name = $_SERVER['SERVER_NAME']; // phpcs:ignore

$csod_meta         = usces_has_custom_field_meta( 'order' );
$cscs_meta         = usces_has_custom_field_meta( 'customer' );
$csde_meta         = usces_has_custom_field_meta( 'delivery' );
$usces_opt_order   = get_option( 'usces_opt_order' );
$usces_opt_order   = apply_filters( 'usces_filter_opt_order', $usces_opt_order );
$chk_pro           = ( isset( $usces_opt_order['chk_pro'] ) ) ? $usces_opt_order['chk_pro'] : array();
$chk_ord           = ( isset( $usces_opt_order['chk_ord'] ) ) ? $usces_opt_order['chk_ord'] : array();
$applyform         = usces_get_apply_addressform( $this->options['system']['addressform'] );
$settlement_backup = ( isset( $this->options['system']['settlement_backup'] ) ) ? $this->options['system']['settlement_backup'] : 0;
$settlement_notice = get_option( 'usces_settlement_notice' );
$orderPeriod       = isset( $_COOKIE['orderPeriod'] ) ? $_COOKIE['orderPeriod'] : ''; // phpcs:ignore
if ( empty( $orderPeriod ) ) { // phpcs:ignore
	$period = array(
		'period' => 0,
		'start'  => '',
		'end'    => '',
	);
} else {
	parse_str( $orderPeriod, $period ); // phpcs:ignore
}
if ( 0 == $period['period'] ) {
	$datepic_title = __( 'All of period', 'usces' );
} elseif ( 1 == $period['period'] ) {
	$datepic_title = __( 'This month', 'usces' );
} elseif ( 2 == $period['period'] ) {
	$datepic_title = __( 'Last month', 'usces' );
} else {
	$datepic_title  = $period['start'] ? $period['start'] : __( 'first day', 'usces' );
	$datepic_title .= ' ' . __( 'to', 'usces' ) . ' ';
	$datepic_title .= $period['end'] ? $period['end'] : __( 'today', 'usces' );
}

$delivery_method = ( isset( $this->options['delivery_method'] ) ) ? $this->options['delivery_method'] : array();

$arr_mail_print_status = USCES_DATALIST_UPGRADE::get_value_print_mail();
?>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Management <?php esc_html_e( 'Order List', 'usces' ); ?></h1>
<p class="version_info">Version <?php echo esc_html( USCES_VERSION ); ?></p>
<?php usces_admin_action_status( $status, $message ); ?>
	<div id="datepic_navi" class="datepic">
		<div id="datepic_title"><span class="dashicons dashicons-calendar-alt"></span><?php echo esc_html( $datepic_title ); ?></div>
		<div id="datepic_form">
			<p class="datepic_select_date"><input type="text" name="startdate" id="startdate" value="<?php echo esc_attr( $period['start'] ); ?>"><?php esc_html_e( ' - ', 'usces' ); ?><input type="text" name="enddate" id="enddate" value="<?php echo esc_attr( $period['end'] ); ?>"></p>
			<p class="datepic_all_date">
				<select name="datepic_period" id="datepic_period" >
					<option value="0"<?php echo( '0' == $period['period'] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'All of period', 'usces' ); ?></option>
					<option value="1"<?php echo( '1' == $period['period'] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'This month', 'usces' ); ?></option>
					<option value="2"<?php echo( '2' == $period['period'] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Last month', 'usces' ); ?></option>
					<option value="3"<?php echo( '3' == $period['period'] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Specify', 'usces' ); ?></option>
				</select>
			</p>
			<p class="datepic_btn"><input type="button" name="apply_datepic" id="apply_datepic" class="button button-primary" value="<?php esc_attr_e( 'Apply' ); ?>"></p>
			<span id="period"></span>
		</div>
	</div>
<?php do_action( 'usces_action_order_list_header' ); ?>

<form action="<?php echo esc_url( USCES_ADMIN_URL . '?page=usces_orderlist' ); ?>" method="post" name="tablesearch" id="form_tablesearch">
<div id="datatable">
<div class="usces_tablenav usces_tablenav_top">
	<?php wel_esc_script_e( $dataTableNavigation ); ?>
	<div id="searchVisiLink" class="screen-field"><?php esc_html_e( 'Show the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-down"></span></div>
	<div class="refresh"><a href="<?php echo esc_url( admin_url( 'admin.php?page=usces_orderlist&refresh' ) ); ?>"><span class="dashicons dashicons-update"></span><?php esc_html_e( 'updates it to latest information', 'usces' ); ?></a></div>
</div>

<div id="tablesearch" class="usces_tablesearch">
<div id="searchBox">


	<table class="search_table">
	<tr>
		<td class="label"><?php esc_html_e( 'Order Search', 'usces' ); ?></td>
		<td>
			<div class="order_search_item search_item">
				<p class="search_item_label"><?php esc_html_e( 'From order information', 'usces' ); ?></p>
				<p>
					<select name="search[order_column][0]" id="searchorderselect_0" class="searchselect">
						<option value=""> </option>
<?php
foreach ( (array) $arr_column as $key => $value ) :
	if ( $key == $arr_search['order_column'][0] ) {
		$selected = ' selected="selected"';
	} else {
		$selected = '';
	}
	$attr_mail_print = '';
	if ( array_key_exists( $key, $arr_mail_print_fields ) ) {
		$attr_mail_print = ' data-mailprint="' . $arr_mail_print_fields[$key]['type'] . '"';
	}
	?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ).$attr_mail_print; ?>><?php echo esc_html( $value ); ?></option>
	<?php
endforeach;
?>
					</select>
					<span id="searchorderword_0">
					<input name="search[order_word][0]" type="text" value="<?php echo esc_attr( $arr_search['order_word'][0] ); ?>" class="regular-text" maxlength="50" />
					<select name="search[order_word_term][0]" class="termselect">
						<option value="contain"<?php echo ( 'contain' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Contain', 'usces' ); ?></option>
						<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Not Contain', 'usces' ); ?></option>
						<option value="equal"<?php echo ( 'equal' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Equal', 'usces' ); ?></option>
						<option value="morethan"<?php echo ( 'morethan' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'More than', 'usces' ); ?></option>
						<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Less than', 'usces' ); ?></option>
					</select>
					</span>
				</p>
				<p>
					<select name="search[order_term]" class="termselect">
						<option value="AND">AND</option>
						<option value="OR"<?php echo ( 'OR' == $arr_search['order_term'] ? ' selected="selected"' : '' ); ?>>OR</option>
					</select>
				</p>
				<p>
					<select name="search[order_column][1]" id="searchorderselect_1" class="searchselect">
						<option value=""> </option>
<?php
foreach ( (array) $arr_column as $key => $value ) :
	if ( $key == $arr_search['order_column'][1] ) {
		$selected = ' selected="selected"';
	} else {
		$selected = '';
	}
	$attr_mail_print = '';
	if ( array_key_exists( $key, $arr_mail_print_fields ) ) {
		$attr_mail_print = ' data-mailprint="' . $arr_mail_print_fields[$key]['type'] . '"';
	}
	?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ).$attr_mail_print; ?>><?php echo esc_html( $value ); ?></option>
	<?php
endforeach;
?>
					</select>
					<span id="searchorderword_1">
					<input name="search[order_word][1]" type="text" value="<?php echo esc_attr( $arr_search['order_word'][1] ); ?>" class="regular-text" maxlength="50" />
					<select name="search[order_word_term][1]" class="termselect">
						<option value="contain"<?php echo ( 'contain' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Contain', 'usces' ); ?></option>
						<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Not Contain', 'usces' ); ?></option>
						<option value="equal"<?php echo ( 'equal' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Equal', 'usces' ); ?></option>
						<option value="morethan"<?php echo ( 'morethan' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'More than', 'usces' ); ?></option>
						<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Less than', 'usces' ); ?></option>
					</select>
					</span>
				</p>
			</div>

			<div class="search_separate">AND</div>

			<div class="product_search_item search_item">
				<p class="search_item_label"><?php esc_html_e( 'From product information', 'usces' ); ?></p>
				<p>
					<select name="search[product_column][0]" id="searchproductselect_0" class="searchselect">
						<option value=""> </option>
						<option value="item_code"<?php echo( 'item_code' == $arr_search['product_column'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'item code', 'usces' ); ?></option>
						<option value="item_name"<?php echo( 'item_name' == $arr_search['product_column'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'item name', 'usces' ); ?></option>
						<option value="sku_code"<?php echo( 'sku_code' == $arr_search['product_column'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'SKU code', 'usces' ); ?></option>
						<option value="sku_name"<?php echo( 'sku_name' == $arr_search['product_column'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'SKU name', 'usces' ); ?></option>
						<option value="item_option"<?php echo( 'item_option' == $arr_search['product_column'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'options for items', 'usces' ); ?></option>
					</select>

					<span id="searchproductword_0"><input name="search[product_word][0]" type="text" value="<?php echo esc_attr( $arr_search['product_word'][0] ); ?>" class="regular-text" maxlength="50" /></span>
				</p>
				<p>
					<select name="search[product_term]" class="termselect">
						<option value="AND">AND</option>
						<option value="OR"<?php echo ( 'OR' == $arr_search['product_term'] ? ' selected="selected"' : '' ); ?>>OR</option>
					</select>
				</p>
				<p>
					<select name="search[product_column][1]" id="searchproductselect_1" class="searchselect">
						<option value=""> </option>
						<option value="item_code"<?php echo( 'item_code' == $arr_search['product_column'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'item code', 'usces' ); ?></option>
						<option value="item_name"<?php echo( 'item_name' == $arr_search['product_column'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'item name', 'usces' ); ?></option>
						<option value="sku_code"<?php echo( 'sku_code' == $arr_search['product_column'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'SKU code', 'usces' ); ?></option>
						<option value="sku_name"<?php echo( 'sku_name' == $arr_search['product_column'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'SKU name', 'usces' ); ?></option>
						<option value="item_option"<?php echo( 'item_option' == $arr_search['product_column'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'options for items', 'usces' ); ?></option>
					</select>

					<span id="searchproductword_1"><input name="search[product_word][1]" type="text" value="<?php echo esc_attr( $arr_search['product_word'][1] ); ?>" class="regular-text" maxlength="50" /></span>
				</p>
			</div>

			<div class="search_submit">
				<input name="searchIn" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Search', 'usces' ); ?>" />
				<input name="searchOut" type="submit" class="button" value="<?php esc_attr_e( 'Cancellation', 'usces' ); ?>" />
			</div>
		</td>
	</tr>
	<tr>
		<td class="label"><?php esc_html_e( 'Oparation in bulk', 'usces' ); ?></td>
		<td id="change_list_table">
			<div>
				<select name="allchange[column]" class="searchselect" id="changeselect">
					<option value=""> </option>
					<option value="receipt_status"><?php esc_html_e( 'Edit the receiving money status', 'usces' ); ?></option>
					<option value="estimate_status"><?php esc_html_e( 'Order type', 'usces' ); ?></option>
					<option value="process_status"><?php esc_html_e( 'Processing status', 'usces' ); ?></option>
					<option value="delete"><?php esc_html_e( 'Delete in bulk', 'usces' ); ?></option>
					<?php echo apply_filters( 'usces_filter_allchange_column', '' ); // phpcs:ignore ?>
				</select>
				<span id="changefield"></span>
				<input name="collective_change" type="button" class="button" id="collective_change" value="<?php esc_attr_e( 'Run updating', 'usces' ); ?>" />
				<input name="collective" id="orderlistaction" type="hidden" />
			</div>
		</td>
	</tr>
	<tr>
		<td class="label"><?php esc_html_e( 'Action', 'usces' ); ?></td>
		<td id="dl_list_table">
			<div class="action_button">
				<input type="button" id="dl_productlist" class="button" value="<?php esc_attr_e( 'Download Product List', 'usces' ); ?>" />
				<input type="button" id="dl_orderlist" class="button" value="<?php esc_attr_e( 'Download Order List', 'usces' ); ?>" />
<?php
if ( ! empty( $settlement_backup ) && 1 == $settlement_backup ) :
	?>
				<input type="button" id="settlementlog" class="button" value="<?php esc_attr_e( 'Settlement previous log list', 'usces' ); ?>" />
	<?php
endif;
?>
<?php
if ( ! empty( $settlement_notice ) ) :
	?>
				<input type="button" id="settlement_errorlog" class="button" value="<?php esc_attr_e( 'Settlement error log list', 'usces' ); ?>" />
	<?php
endif;
?>
				<?php do_action( 'usces_action_dl_list_table' ); ?>
			</div>
		</td>
	</tr>
	</table>

<div<?php if ( has_action( 'usces_action_order_list_searchbox_bottom' ) ) echo ' class="searchbox_bottom"'; // phpcs:ignore ?>>
<?php do_action( 'usces_action_order_list_searchbox_bottom' ); ?>
</div>
</div><!-- searchBox -->
<?php do_action( 'usces_action_order_list_searchbox' ); ?>
</div><!-- tablesearch -->

<table id="mainDataTable" class="new-table order-new-table">
<?php
$header_mail_print_fields = array();
$list_header = '<th scope="col"><input name="allcheck" type="checkbox" value="" /></th>';
foreach ( (array) $arr_header as $key => $value ) {
	if ( ! isset( $list_option['view_column'][ $key ] ) || ! $list_option['view_column'][ $key ] ) {
		continue;
	}
	if ( array_key_exists( $key, $arr_mail_print_fields ) ) {
		$header_mail_print_fields[$key] = $key;
	}
	$list_header .= '<th scope="col">' . $value . '</th>';
}

$usces_serchproduct_column = array( 'item_code', 'item_name', 'sku_code', 'sku_name', 'item_option' );
if ( ( in_array( $arr_search['product_column'][0], $usces_serchproduct_column ) && $arr_search['product_word'][0] != '' )
	|| ( in_array( $arr_search['product_column'][1], $usces_serchproduct_column ) && $arr_search['product_word'][1] != '' ) )
{
	$list_header .= '<th scope="col">' . __( 'item code', 'usces' ) . '</th>';
	$list_header .= '<th scope="col">' . __( 'item name', 'usces' ) . '</th>';
	$list_header .= '<th scope="col">' . __( 'SKU code', 'usces' ) . '</th>';
	$list_header .= '<th scope="col">' . __( 'SKU name', 'usces' ) . '</th>';
	$list_header .= '<th scope="col">' . __( 'option name', 'usces' ) . '</th>';
	$list_header .= '<th scope="col">' . __( 'option value', 'usces' ) . '</th>';
}

$list_header .= '<th scope="col">&nbsp;</th>';
?>
	<thead>
	<tr>
		<?php echo apply_filters( 'usces_filter_orderlist_header', $list_header, $arr_header ); // phpcs:ignore ?>
	</tr>
	</thead>
<?php
foreach ( (array) $rows as $data ) :
	if ( ! isset( $list_option['view_column']['admin_memo'] ) || ! $list_option['view_column']['admin_memo'] ) {
		$list_detail = '<td align="center"><input name="listcheck[]" type="checkbox" value="' . $data['ID'] . '" /></td>';
	} else {
		$list_detail = '<td align="center" rowspan="2"><input name="listcheck[]" type="checkbox" value="' . $data['ID'] . '" /></td>';
	}
	foreach ( (array) $data as $key => $value ) {

		if ( isset( $list_option['view_column'][ $key ] ) && ! $list_option['view_column'][ $key ] ) {
			continue;
		}
		if ( WCUtils::is_blank( $value ) ) {
			$value = '&nbsp;';
		}
		$tempkey = substr( $key, 0, 5 );
		if ( in_array( $tempkey, array( 'csod_', 'cscs_', 'csde_' ) ) ) {
			$multi_value = maybe_unserialize( $value );
			if ( is_array( $multi_value ) ) {
				$value = '';
				foreach ( $multi_value as $str ) {
					$value .= $str . ' ';
				}
				trim( $value );
			}
		}

		$detail = '';
		switch ( $key ) {
			case 'admin_memo':
				$detail = '';
				break;

			case 'ID':
			case 'deco_id':
				$detail = '<td><a href="' . USCES_ADMIN_URL . '?page=usces_orderlist&order_action=edit&order_id=' . $data['ID'] . '&wc_nonce=' . wp_create_nonce( 'order_list' ) . '">' . esc_html( $value ) . '</a></td>';
				break;

			case 'reg_id':
				if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
					if ( '&nbsp;' == $value || '-' == $value ) {
						$detail = '<td>' . esc_html( $value ) . '</td>';
					} else {
						$detail = '<td><a href="' . USCES_ADMIN_URL . '?page=usces_regularlist&regular_action=edit&regular_id=' . $value . '&usces_referer=' . $curent_url . '&wc_nonce=' . wp_create_nonce( 'regular_list' ) . '">' . esc_html( $value ) . '</a></td>';
					}
				}
				break;

			case 'estimate_status':
				$e_status       = '';
				$e_status_class = '';
				if ( $this->is_status( 'estimate', $value ) ) {
					$e_status = esc_html( $management_status['estimate'] );
				} elseif ( $this->is_status( 'adminorder', $value ) ) {
					$e_status = esc_html( $management_status['adminorder'] );
				} else {
					$e_status = esc_html( __( 'Order', 'usces' ) );
				}
				$e_status       = apply_filters( 'usces_filter_orderlist_estimate_status', $e_status, $value, $management_status, $data['ID'] );
				$e_status_class = apply_filters( 'usces_filter_orderlist_estimate_status_class', $e_status_class, $value, $data['ID'] );
				$detail         = '<td' . $e_status_class . '>' . $e_status . '</td>';
				break;

			case 'process_status':
				$p_status       = '';
				$p_status_class = '';
				if ( $this->is_status( 'duringorder', $value ) ) {
					$p_status = esc_html( $management_status['duringorder'] );
				} elseif ( $this->is_status( 'cancel', $value ) ) {
					$p_status = esc_html( $management_status['cancel'] );
				} elseif ( $this->is_status( 'completion', $value ) ) {
					$p_status = esc_html( $management_status['completion'] );
					$p_status_class = ' class="green"';
				} else {
					$p_status = esc_html( __( 'new order', 'usces' ) );
				}
				$p_status       = apply_filters( 'usces_filter_orderlist_process_status', $p_status, $value, $management_status, $data['ID'] );
				$p_status_class = apply_filters( 'usces_filter_orderlist_process_status_class', $p_status_class, $value, $data['ID'] );
				$detail         = '<td' . $p_status_class . '>' . $p_status . '</td>';
				break;

			case 'delidue_date':
			case 'payment_name':
				if ( '#none#' == $value ) {
					$detail = '<td>&nbsp;</td>';
				} else {
					$detail = '<td>' . esc_html( $value ) . '</td>';
				}
				break;

			case 'receipt_status':
				$r_status       = '';
				$r_status_class = '';
				if ( $this->is_status( 'noreceipt', $value ) ) {
					$r_status       = esc_html( $management_status['noreceipt'] );
					$r_status_class = ' class="red"';
				} elseif ( $this->is_status( 'pending', $value ) ) {
					$r_status       = esc_html( $management_status['pending'] );
					$r_status_class = ' class="red"';
				} elseif ( $this->is_status( 'receipted', $value ) ) {
					$r_status       = esc_html( $management_status['receipted'] );
					$r_status_class = ' class="green"';
				} else {
					$r_status = '&nbsp;';
				}
				$r_status       = apply_filters( 'usces_filter_orderlist_receipt_status', $r_status, $value, $management_status, $data['ID'] );
				$r_status_class = apply_filters( 'usces_filter_orderlist_receipt_status_class', $r_status_class, $value, $data['ID'] );
				$detail         = '<td' . $r_status_class . '>' . $r_status . '</td>';
				break;

			case 'item_total_price':
			case 'discount':
			case 'shipping_charge':
			case 'cod_fee':
			case 'tax':
				$detail = '<td class="price">' . usces_crform( $value, true, false, 'return' ) . '</td>';
				break;
			case 'total_price':
				if ( 0 > $value ) {
					$value = 0;
				}
				$detail = '<td class="price">' . usces_crform( $value, true, false, 'return' ) . '</td>';
				break;

			case 'deli_method':
				if ( -1 != $value ) {
					$delivery_method_index = $this->get_delivery_method_index( $value );
					$deli_method           = ( isset( $this->options['delivery_method'][ $delivery_method_index ]['name'] ) ) ? esc_html( $this->options['delivery_method'][ $delivery_method_index ]['name'] ) : '&nbsp;';
					$deli_method_class     = apply_filters( 'usces_filter_orderlist_deli_method_class', '', $value, $delivery_method_index );
					$detail                = '<td' . $deli_method_class . '>' . $deli_method . '</td>';
				} else {
					$detail = '<td>&nbsp;</td>';
				}
				break;

			case 'deli_name':
				$deliinfo       = unserialize( $value );
				$deliname       = $deliinfo['name1'] . $deliinfo['name2'];
				$deliname_class = apply_filters( 'usces_filter_orderlist_deliname_class', '', $deliinfo, $deliname );
				if ( $deliname ) {
					$detail = '<td' . $deliname_class . '>' . esc_html( $deliinfo['name1'] . $deliinfo['name2'] ) . '( ' . $deliinfo['pref'] . ')</td>';
				} elseif ( isset( $deliinfo['delivery_flag'] ) && 2 == $deliinfo['delivery_flag'] ) {
					$detail = '<td' . $deliname_class . '>' . esc_html( __( 'Multiple destinations', 'usces' ) ) . '</td>';
				} else {
					$detail = '<td>&nbsp;</td>';
				}
				break;

			case 'mem_id':
				if ( WCUtils::is_zero( $value ) ) {
					$value = '&nbsp;';
				}
				$detail = '<td>' . esc_html( $value ) . '</td>';
				break;

			case 'country':
				$detail = '<td>' . esc_html( $usces_country[ $value ] ) . '</td>';
				break;

			case 'pref':
				if ( __( '-- Select --', 'usces' ) == $value || '-- Select --' == $value ) {
					$detail = '<td>&nbsp;</td>';
				} else {
					$detail = '<td>' . esc_html( $value ) . '</td>';
				}
				break;

			case 'note':
				$value  = mb_substr( $value, 0, 15, 'UTF-8' );
				$detail = '<td>' . esc_html( $value ) . '</td>';
				break;

			case 'order_date':
				$detail = '<td>' . date_i18n( 'Y-m-d H:i', strtotime( $value ) ) . '</td>';
				break;

			/* hide columns */
			case 'reg_parent_id':
				break;

			case 'order_check':
				$p_status = '';
				$value    = maybe_unserialize( $value );
				if ( ! is_array( $value ) ) {
					$value = array();
				}
				foreach ( $header_mail_print_fields as $k_mp => $v_mp ) {
					$type_mps = $arr_mail_print_status[ $arr_mail_print_fields[ $k_mp ]['type'] ];
					if( in_array( $k_mp, $value ) ) {
						$p_status .= '<td>'.$type_mps[1]['alias'].'</td>';
					}else{
						$p_status .= '<td>'.$type_mps[0]['alias'].'</td>';
					}
				}
				$p_status = apply_filters( 'usces_filter_orderlist_order_check', $p_status, $value, $key );
				$detail = $p_status;
				break;

			case 'tracking_number':
				$delivery_company_url = '';

				if ( ! empty( $data['tracking_number'] ) ) {
					$delivery_company     = $this->get_order_meta_value( 'delivery_company', $data['ID'] );
					$delivery_company_url = usces_get_delivery_company_url( $delivery_company, $value );
				}

				if ( ! empty( $delivery_company_url ) ) {
					$detail = '<td><a href="' . esc_url( $delivery_company_url ) . '" target="_blank">' . esc_html( $data['tracking_number'] ) . '</a></td>';
				} else if ( ! empty( $data['tracking_number'] ) ) {
					$detail = '<td>' . esc_html( $data['tracking_number'] ) . '</td>';
				} else {
					$detail = '<td>' . esc_html( $value ) . '</td>';
				}
				break;

			case 'meta_value':
				$value = maybe_unserialize( $value );
				if ( is_array( $value ) ) {
					$opt_values = '';
					foreach ( $value as $opt_value ) {
						$opt_values .= $opt_value . ',';
					}
					$opt_values = rtrim( $opt_values, ',' );
					$detail     = '<td>' . esc_html( $opt_values ) . '</td>';
				} else {
					$detail = '<td>' . esc_html( $value ) . '</td>';
				}
				break;

			default:
				$detail = '<td>' . esc_html( $value ) . '</td>';
		}
		$list_detail .= apply_filters( 'usces_filter_orderlist_detail_value', $detail, $value, $key, $data['ID'] );
	}

	$list_detail .= '<td><a href="' . USCES_ADMIN_URL . '?page=usces_orderlist&order_action=delete&order_id=' . $data['ID'] . '&wc_nonce=' . wp_create_nonce( 'order_list' ) . '" onclick="return deleteconfirm(\'' . $data['ID'] . '\' );"><span style="color:#FF0000; font-size:9px;">' . __( 'Delete', 'usces' ) . '</span></a></td>';
	if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
		$trclass = ( ! empty( $data['reg_parent_id'] ) ) ? ' class="regular_parent_order"' : '';
	} else {
		$trclass = '';
	}
	?>
<tbody>
	<tr<?php echo apply_filters( 'usces_filter_orderlist_detail_trclass', $trclass, $data ); // phpcs:ignore ?>>
		<?php echo apply_filters( 'usces_filter_orderlist_detail', $list_detail, $data, $curent_url ); // phpcs:ignore ?>
	</tr>
	<?php
	if ( ! isset( $list_option['view_column']['admin_memo'] ) || ! $list_option['view_column']['admin_memo'] ) {
		$memo = '';
	} else {
		$colspan = 0;
		foreach ( $list_option['view_column'] as $vkey => $vvalue ) {
			if ( $vvalue ) {
				$colspan++;
			}
		}
		$memo       = $data['admin_memo'];
		$memo_class = empty( $memo ) ? 'passive' : 'active';
		$memo       = '<tr class="' . $memo_class . '"><td colspan="' . $colspan . '">' . esc_html( $memo ) . '</td></tr>';
		echo apply_filters( 'usces_filter_orderlist_memo', $memo, $data, $curent_url ); // phpcs:ignore
	}
	?>
</tbody>
	<?php
endforeach;
?>
</table>
<div class="usces_tablenav usces_tablenav_bottom" ><?php wel_esc_script_e( $dataTableNavigation ); ?></div>

</div><!-- datatable -->
<!-- [memory peak usage] <?php // echo round(memory_get_peak_usage()/1048576, 1); ?>Mb -->

<div id="dlProductListDialog" title="<?php esc_attr_e( 'Download Product List', 'usces' ); ?>" style="display:none;">
	<p><?php esc_html_e( 'Select the item you want, please press the download.', 'usces' ); ?></p>
	<input type="button" class="button" id="dl_pro" value="<?php esc_attr_e( 'Download', 'usces' ); ?>" />
	<fieldset><legend><?php esc_html_e( 'Header Information', 'usces' ); ?></legend>
	<fieldset><legend><?php esc_html_e( 'Customer Information', 'usces' ); ?></legend>
		<label for="chk_pro[ID]"><input type="checkbox" class="check_pro" id="chk_pro[ID]" value="ID"<?php usces_checked( $chk_pro, 'ID' ); ?> /><?php esc_html_e( 'ID', 'usces' ); ?></label>
		<label for="chk_pro[deco_id]"><input type="checkbox" class="check_pro" id="chk_pro[deco_id]" value="deco_id"<?php usces_checked( $chk_pro, 'deco_id' ); ?> /><?php esc_html_e( 'Order number', 'usces' ); ?></label>
		<label for="chk_pro[date]"><input type="checkbox" class="check_pro" id="chk_pro[date]" value="date"<?php usces_checked( $chk_pro, 'date' ); ?>  /><?php esc_html_e( 'order date', 'usces' ); ?></label>
		<label for="chk_pro[mem_id]"><input type="checkbox" class="check_pro" id="chk_pro[mem_id]" value="mem_id"<?php usces_checked( $chk_pro, 'mem_id' ); ?> /><?php esc_html_e( 'membership number', 'usces' ); ?></label>
		<label for="chk_pro[email]"><input type="checkbox" class="check_pro" id="chk_pro[email]" value="email"<?php usces_checked( $chk_pro, 'email' ); ?> /><?php esc_html_e( 'e-mail', 'usces' ); ?></label>
<?php
if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'name_pre' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_pro, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_pro[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<label for="chk_pro[name]"><input type="checkbox" class="check_pro" id="chk_pro[name]" value="name"<?php usces_checked( $chk_pro, 'name' ); ?> /><?php esc_html_e( 'name', 'usces' ); ?></label>
<?php
switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_pro[kana]"><input type="checkbox" class="check_pro" id="chk_pro[kana]" value="kana"<?php usces_checked( $chk_pro, 'kana' ); ?> /><?php esc_html_e( 'furigana', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'name_after' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_pro, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_pro[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}

switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_pro[zip]"><input type="checkbox" class="check_pro" id="chk_pro[zip]" value="zip"<?php usces_checked( $chk_pro, 'zip' ); ?> /><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></label>
		<label for="chk_pro[country]"><input type="checkbox" class="check_pro" id="chk_pro[country]" value="country"<?php usces_checked( $chk_pro, 'country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_pro[pref]"><input type="checkbox" class="check_pro" id="chk_pro[pref]" value="pref"<?php usces_checked( $chk_pro, 'pref' ); ?> /><?php esc_html_e( 'Province', 'usces' ); ?></label>
		<label for="chk_pro[address1]"><input type="checkbox" class="check_pro" id="chk_pro[address1]" value="address1"<?php usces_checked( $chk_pro, 'address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_pro[address2]"><input type="checkbox" class="check_pro" id="chk_pro[address2]" value="address2"<?php usces_checked( $chk_pro, 'address2' ); ?> /><?php esc_html_e( 'numbers', 'usces' ); ?></label>
		<label for="chk_pro[address3]"><input type="checkbox" class="check_pro" id="chk_pro[address3]" value="address3"<?php usces_checked( $chk_pro, 'address3' ); ?> /><?php esc_html_e( 'building name', 'usces' ); ?></label>
		<label for="chk_pro[tel]"><input type="checkbox" class="check_pro" id="chk_pro[tel]" value="tel"<?php usces_checked( $chk_pro, 'tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_pro[fax]"><input type="checkbox" class="check_pro" id="chk_pro[fax]" value="fax"<?php usces_checked( $chk_pro, 'fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
	case 'US':
	default:
		?>
		<label for="chk_pro[address2]"><input type="checkbox" class="check_pro" id="chk_pro[address2]" value="address2"<?php usces_checked( $chk_pro, 'address2' ); ?> /><?php esc_html_e( 'Address Line1', 'usces' ); ?></label>
		<label for="chk_pro[address3]"><input type="checkbox" class="check_pro" id="chk_pro[address3]" value="address3"<?php usces_checked( $chk_pro, 'address3' ); ?> /><?php esc_html_e( 'Address Line2', 'usces' ); ?></label>
		<label for="chk_pro[address1]"><input type="checkbox" class="check_pro" id="chk_pro[address1]" value="address1"<?php usces_checked( $chk_pro, 'address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_pro[pref]"><input type="checkbox" class="check_pro" id="chk_pro[pref]" value="pref"<?php usces_checked( $chk_pro, 'pref' ); ?> /><?php esc_html_e( 'State', 'usces' ); ?></label>
		<label for="chk_pro[country]"><input type="checkbox" class="check_pro" id="chk_pro[country]" value="country"<?php usces_checked( $chk_pro, 'country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_pro[zip]"><input type="checkbox" class="check_pro" id="chk_pro[zip]" value="zip"<?php usces_checked( $chk_pro, 'zip' ); ?> /><?php esc_html_e( 'Zip', 'usces' ); ?></label>
		<label for="chk_pro[tel]"><input type="checkbox" class="check_pro" id="chk_pro[tel]" value="tel"<?php usces_checked( $chk_pro, 'tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_pro[fax]"><input type="checkbox" class="check_pro" id="chk_pro[fax]" value="fax"<?php usces_checked( $chk_pro, 'fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'fax_after' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_pro, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_pro[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<?php do_action( 'usces_action_chk_pro_customer', $chk_pro ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Shipping address information', 'usces' ); ?></legend>
<?php
if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'name_pre' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_pro, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_pro[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<label for="chk_pro[delivery_name]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_name]" value="delivery_name"<?php usces_checked( $chk_pro, 'delivery_name' ); ?> /><?php esc_html_e( 'name', 'usces' ); ?></label>
<?php
switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_pro[delivery_kana]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_kana]" value="delivery_kana"<?php usces_checked( $chk_pro, 'delivery_kana' ); ?> /><?php esc_html_e( 'furigana', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'name_after' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_pro, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_pro[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}

switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_pro[delivery_zip]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_zip]" value="delivery_zip"<?php usces_checked( $chk_pro, 'delivery_zip' ); ?> /><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></label>
		<label for="chk_pro[delivery_country]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_country]" value="delivery_country"<?php usces_checked( $chk_pro, 'delivery_country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_pro[delivery_pref]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_pref]" value="delivery_pref"<?php usces_checked( $chk_pro, 'delivery_pref' ); ?> /><?php esc_html_e( 'Province', 'usces' ); ?></label>
		<label for="chk_pro[delivery_address1]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_address1]" value="delivery_address1"<?php usces_checked( $chk_pro, 'delivery_address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_pro[delivery_address2]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_address2]" value="delivery_address2"<?php usces_checked( $chk_pro, 'delivery_address2' ); ?> /><?php esc_html_e( 'numbers', 'usces' ); ?></label>
		<label for="chk_pro[delivery_address3]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_address3]" value="delivery_address3"<?php usces_checked( $chk_pro, 'delivery_address3' ); ?> /><?php esc_html_e( 'building name', 'usces' ); ?></label>
		<label for="chk_pro[delivery_tel]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_tel]" value="delivery_tel"<?php usces_checked( $chk_pro, 'delivery_tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_pro[delivery_fax]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_fax]" value="delivery_fax"<?php usces_checked( $chk_pro, 'delivery_fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
	case 'US':
	default:
		?>
		<label for="chk_pro[delivery_address2]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_address2]" value="delivery_address2"<?php usces_checked( $chk_pro, 'delivery_address2' ); ?> /><?php esc_html_e( 'Address Line1', 'usces' ); ?></label>
		<label for="chk_pro[delivery_address3]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_address3]" value="delivery_address3"<?php usces_checked( $chk_pro, 'delivery_address3' ); ?> /><?php esc_html_e( 'Address Line2', 'usces' ); ?></label>
		<label for="chk_pro[delivery_address1]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_address1]" value="delivery_address1"<?php usces_checked( $chk_pro, 'delivery_address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_pro[delivery_pref]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_pref]" value="delivery_pref"<?php usces_checked( $chk_pro, 'delivery_pref' ); ?> /><?php esc_html_e( 'State', 'usces' ); ?></label>
		<label for="chk_pro[delivery_country]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_country]" value="delivery_country"<?php usces_checked( $chk_pro, 'delivery_country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_pro[delivery_zip]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_zip]" value="delivery_zip"<?php usces_checked( $chk_pro, 'delivery_zip' ); ?> /><?php esc_html_e( 'Zip', 'usces' ); ?></label>
		<label for="chk_pro[delivery_tel]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_tel]" value="delivery_tel"<?php usces_checked( $chk_pro, 'delivery_tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_pro[delivery_fax]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_fax]" value="delivery_fax"<?php usces_checked( $chk_pro, 'delivery_fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'fax_after' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_pro, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_pro[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<?php do_action( 'usces_action_chk_pro_delivery', $chk_pro ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Order Infomation', 'usces' ); ?></legend>
		<label for="chk_pro[shipping_date]"><input type="checkbox" class="check_pro" id="chk_pro[shipping_date]" value="shipping_date"<?php usces_checked( $chk_pro, 'shipping_date' ); ?> /><?php esc_html_e( 'shpping date', 'usces' ); ?></label>
		<label for="chk_pro[peyment_method]"><input type="checkbox" class="check_pro" id="chk_pro[peyment_method]" value="peyment_method"<?php usces_checked( $chk_pro, 'peyment_method' ); ?> /><?php esc_html_e( 'payment method', 'usces' ); ?></label>
		<label for="chk_pro[wc_trans_id]"><input type="checkbox" class="check_pro" id="chk_pro[wc_trans_id]" value="wc_trans_id"<?php usces_checked( $chk_pro, 'wc_trans_id' ); ?> /><?php esc_html_e( 'Transaction ID', 'usces' ); ?></label>
		<label for="chk_pro[delivery_method]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_method]" value="delivery_method"<?php usces_checked( $chk_pro, 'delivery_method' ); ?> /><?php esc_html_e( 'shipping option', 'usces' ); ?></label>
		<label for="chk_pro[delivery_date]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_date]" value="delivery_date"<?php usces_checked( $chk_pro, 'delivery_date' ); ?> /><?php esc_html_e( 'Delivery date', 'usces' ); ?></label>
		<label for="chk_pro[delivery_time]"><input type="checkbox" class="check_pro" id="chk_pro[delivery_time]" value="delivery_time"<?php usces_checked( $chk_pro, 'delivery_time' ); ?> /><?php esc_html_e( 'delivery time', 'usces' ); ?></label>
		<label for="chk_pro[delidue_date]"><input type="checkbox" class="check_pro" id="chk_pro[delidue_date]" value="delidue_date"<?php usces_checked( $chk_pro, 'delidue_date' ); ?> /><?php esc_html_e( 'Shipping date', 'usces' ); ?></label>
		<label for="chk_pro[status]"><input type="checkbox" class="check_pro" id="chk_pro[status]" value="status"<?php usces_checked( $chk_pro, 'status' ); ?> /><?php esc_html_e( 'Status', 'usces' ); ?></label>
		<label for="chk_pro[tracking_number]"><input type="checkbox" class="check_pro" id="chk_pro[tracking_number]" value="tracking_number"<?php usces_checked( $chk_pro, 'tracking_number' ); ?> /><?php esc_html_e( 'Tracking number', 'usces' ); ?></label>
		<label for="chk_pro[total_amount]"><input type="checkbox" class="check_pro" id="chk_pro[total_amount]" value="total_amount"<?php usces_checked( $chk_pro, 'total_amount' ); ?> /><?php esc_html_e( 'Total Amount', 'usces' ); ?></label>
		<label for="chk_pro[item_total_amount]"><input type="checkbox" class="check_pro" id="chk_pro[item_total_amount]" value="item_total_amount"<?php usces_checked( $chk_pro, 'item_total_amount' ); ?> /><?php esc_html_e( 'total items', 'usces' ); ?></label>
<?php if ( usces_is_member_system() && usces_is_member_system_point() ) : ?>
		<label for="chk_pro[getpoint]"><input type="checkbox" class="check_pro" id="chk_pro[getpoint]" value="getpoint"<?php usces_checked( $chk_pro, 'getpoint' ); ?> /><?php esc_html_e( 'granted points', 'usces' ); ?></label>
		<label for="chk_pro[usedpoint]"><input type="checkbox" class="check_pro" id="chk_pro[usedpoint]" value="usedpoint"<?php usces_checked( $chk_pro, 'usedpoint' ); ?> /><?php esc_html_e( 'Used points', 'usces' ); ?></label>
<?php endif; ?>
		<label for="chk_pro[discount]"><input type="checkbox" class="check_pro" id="chk_pro[discount]" value="discount"<?php usces_checked( $chk_pro, 'discount' ); ?> /><?php esc_html_e( 'Discount', 'usces' ); ?></label>
		<label for="chk_pro[shipping_charge]"><input type="checkbox" class="check_pro" id="chk_pro[shipping_charge]" value="shipping_charge"<?php usces_checked( $chk_pro, 'shipping_charge' ); ?> /><?php esc_html_e( 'Shipping', 'usces' ); ?></label>
		<label for="chk_pro[cod_fee]"><input type="checkbox" class="check_pro" id="chk_pro[cod_fee]" value="cod_fee"<?php usces_checked( $chk_pro, 'cod_fee' ); ?> /><?php echo apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ); // phpcs:ignore ?></label>
<?php if ( usces_is_tax_display() ) : ?>
		<label for="chk_pro[tax]"><input type="checkbox" class="check_pro" id="chk_pro[tax]" value="tax"<?php usces_checked( $chk_pro, 'tax' ); ?> /><?php esc_html_e( 'consumption tax', 'usces' ); ?></label>
	<?php if ( usces_is_reduced_taxrate() ) : ?>
		<label for="chk_pro[subtotal_standard]"><input type="checkbox" class="check_pro" id="chk_pro[subtotal_standard]" value="subtotal_standard"<?php usces_checked( $chk_pro, 'subtotal_standard' ); ?> /><?php printf( __( "Applies to %s%%", 'usces' ), $this->options['tax_rate'] ); // phpcs:ignore ?></label>
		<label for="chk_pro[tax_standard]"><input type="checkbox" class="check_pro" id="chk_pro[tax_standard]" value="tax_standard"<?php usces_checked( $chk_pro, 'tax_standard' ); ?> /><?php printf( __( "%s%% consumption tax", 'usces' ), $this->options['tax_rate'] ); // phpcs:ignore ?></label>
		<label for="chk_pro[subtotal_reduced]"><input type="checkbox" class="check_pro" id="chk_pro[subtotal_reduced]" value="subtotal_reduced"<?php usces_checked( $chk_pro, 'subtotal_reduced' ); ?> /><?php printf( __( "Applies to %s%%", 'usces' ), $this->options['tax_rate_reduced'] ); // phpcs:ignore ?></label>
		<label for="chk_pro[tax_reduced]"><input type="checkbox" class="check_pro" id="chk_pro[tax_reduced]" value="tax_reduced"<?php usces_checked( $chk_pro, 'tax_reduced' ); ?> /><?php printf( __( "%s%% consumption tax", 'usces' ), $this->options['tax_rate_reduced'] ); // phpcs:ignore ?></label>
	<?php endif; ?>
<?php endif; ?>
		<label for="chk_pro[note]"><input type="checkbox" class="check_pro" id="chk_pro[note]" value="note"<?php usces_checked( $chk_pro, 'note' ); ?> /><?php esc_html_e( 'Notes', 'usces' ); ?></label>
<?php
if ( ! empty( $csod_meta ) ) {
	foreach ( $csod_meta as $key => $entry ) {
		$csod_key = 'csod_' . $key;
		$checked  = usces_checked( $chk_pro, $csod_key, 'return' );
		$name     = $entry['name'];
		echo '<label for="chk_pro[' . esc_attr( $csod_key ) . ']"><input type="checkbox" class="check_pro" id="chk_pro[' . esc_attr( $csod_key ) . ']" value="' . esc_attr( $csod_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
	}
}
?>
		<?php do_action( 'usces_action_chk_pro_order', $chk_pro ); ?>
	</fieldset>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Product Information', 'usces' ); ?></legend>
		<label for="chk_pro[item_code]"><input type="checkbox" class="check_pro" id="chk_pro[item_code]" value="item_code"<?php usces_checked( $chk_pro, 'item_code' ); ?> /><?php esc_html_e( 'item code', 'usces' ); ?></label>
		<label for="chk_pro[sku_code]"><input type="checkbox" class="check_pro" id="chk_pro[sku_code]" value="sku_code"<?php usces_checked( $chk_pro, 'sku_code' ); ?> /><?php esc_html_e( 'SKU code', 'usces' ); ?></label>
		<label for="chk_pro[item_name]"><input type="checkbox" class="check_pro" id="chk_pro[item_name]" value="item_name"<?php usces_checked( $chk_pro, 'item_name' ); ?> /><?php esc_html_e( 'item name', 'usces' ); ?></label>
		<label for="chk_pro[sku_name]"><input type="checkbox" class="check_pro" id="chk_pro[sku_name]" value="sku_name"<?php usces_checked( $chk_pro, 'sku_name' ); ?> /><?php esc_html_e( 'SKU display name ', 'usces' ); ?></label>
		<label for="chk_pro[options]"><input type="checkbox" class="check_pro" id="chk_pro[options]" value="options"<?php usces_checked( $chk_pro, 'options' ); ?> /><?php esc_html_e( 'options for items', 'usces' ); ?></label>
		<label for="chk_pro[quantity]"><input type="checkbox" class="check_pro" id="chk_pro[quantity]" value="quantity"<?php usces_checked( $chk_pro, 'quantity' ); ?> /><?php esc_html_e( 'Quantity', 'usces' ); ?></label>
		<label for="chk_pro[price]"><input type="checkbox" class="check_pro" id="chk_pro[price]" value="price"<?php usces_checked( $chk_pro, 'price' ); ?> /><?php esc_html_e( 'Unit price', 'usces' ); ?></label>
		<label for="chk_pro[unit]"><input type="checkbox" class="check_pro" id="chk_pro[unit]" value="unit"<?php usces_checked( $chk_pro, 'unit' ); ?> /><?php esc_html_e( 'unit', 'usces' ); ?></label>
		<?php do_action( 'usces_action_chk_pro_detail', $chk_pro ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Admin Information', 'usces' ); ?></legend>
		<label for="chk_pro[admin_memo]"><input type="checkbox" class="check_pro" id="chk_pro[admin_memo]" value="admin_memo"<?php usces_checked( $chk_pro, 'admin_memo' ); ?> /><?php esc_html_e( 'Admin Note', 'usces' ); ?></label>
		<?php do_action( 'usces_action_chk_pro_memo', $chk_pro ); ?>
	</fieldset>
</div>

<div id="dlOrderListDialog" title="<?php esc_attr_e( 'Download Order List', 'usces' ); ?>" style="display:none;">
	<p><?php esc_html_e( 'Select the item you want, please press the download.', 'usces' ); ?></p>
	<input type="button" class="button" id="dl_ord" value="<?php esc_attr_e( 'Download', 'usces' ); ?>" />
	<fieldset><legend><?php esc_html_e( 'Customer Information', 'usces' ); ?></legend>
		<label for="chk_ord[ID]"><input type="checkbox" class="check_order" id="chk_ord[ID]" value="ID"<?php usces_checked( $chk_pro, 'ID' ); ?> /><?php esc_html_e( 'ID', 'usces' ); ?></label>
		<label for="chk_ord[deco_id]"><input type="checkbox" class="check_order" id="chk_ord[deco_id]" value="deco_id"<?php usces_checked( $chk_pro, 'deco_id' ); ?> /><?php esc_html_e( 'Order number', 'usces' ); ?></label>
		<label for="chk_ord[date]"><input type="checkbox" class="check_order" id="chk_ord[date]" value="date"<?php usces_checked( $chk_pro, 'date' ); ?> /><?php esc_html_e( 'order date', 'usces' ); ?></label>
		<label for="chk_ord[mem_id]"><input type="checkbox" class="check_order" id="chk_ord[mem_id]" value="mem_id"<?php usces_checked( $chk_ord, 'mem_id' ); ?> /><?php esc_html_e( 'membership number', 'usces' ); ?></label>
		<label for="chk_ord[email]"><input type="checkbox" class="check_order" id="chk_ord[email]" value="email"<?php usces_checked( $chk_ord, 'email' ); ?> /><?php esc_html_e( 'e-mail', 'usces' ); ?></label>
<?php
if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'name_pre' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_ord, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<label for="chk_ord[name]"><input type="checkbox" class="check_order" id="chk_ord[name]" value="name"<?php usces_checked( $chk_ord, 'name' ); ?> /><?php esc_html_e( 'name', 'usces' ); ?></label>
<?php
switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[kana]"><input type="checkbox" class="check_order" id="chk_ord[kana]" value="kana"<?php usces_checked( $chk_ord, 'kana' ); ?> /><?php esc_html_e( 'furigana', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'name_after' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_ord, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}

switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[zip]"><input type="checkbox" class="check_order" id="chk_ord[zip]" value="zip"<?php usces_checked( $chk_ord, 'zip' ); ?> /><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></label>
		<label for="chk_ord[country]"><input type="checkbox" class="check_order" id="chk_ord[country]" value="country"<?php usces_checked( $chk_ord, 'country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[pref]"><input type="checkbox" class="check_order" id="chk_ord[pref]" value="pref"<?php usces_checked( $chk_ord, 'pref' ); ?> /><?php esc_html_e( 'Province', 'usces' ); ?></label>
		<label for="chk_ord[address1]"><input type="checkbox" class="check_order" id="chk_ord[address1]" value="address1"<?php usces_checked( $chk_ord, 'address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[address2]"><input type="checkbox" class="check_order" id="chk_ord[address2]" value="address2"<?php usces_checked( $chk_ord, 'address2' ); ?> /><?php esc_html_e( 'numbers', 'usces' ); ?></label>
		<label for="chk_ord[address3]"><input type="checkbox" class="check_order" id="chk_ord[address3]" value="address3"<?php usces_checked( $chk_ord, 'address3' ); ?> /><?php esc_html_e( 'building name', 'usces' ); ?></label>
		<label for="chk_ord[tel]"><input type="checkbox" class="check_order" id="chk_ord[tel]" value="tel"<?php usces_checked( $chk_ord, 'tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[fax]"><input type="checkbox" class="check_order" id="chk_ord[fax]" value="fax"<?php usces_checked( $chk_ord, 'fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
	case 'US':
	default:
		?>
		<label for="chk_ord[address2]"><input type="checkbox" class="check_order" id="chk_ord[address2]" value="address2"<?php usces_checked( $chk_ord, 'address2' ); ?> /><?php esc_html_e( 'Address Line1', 'usces' ); ?></label>
		<label for="chk_ord[address3]"><input type="checkbox" class="check_order" id="chk_ord[address3]" value="address3"<?php usces_checked( $chk_ord, 'address3' ); ?> /><?php esc_html_e( 'Address Line2', 'usces' ); ?></label>
		<label for="chk_ord[address1]"><input type="checkbox" class="check_order" id="chk_ord[address1]" value="address1"<?php usces_checked( $chk_ord, 'address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[pref]"><input type="checkbox" class="check_order" id="chk_ord[pref]" value="pref"<?php usces_checked( $chk_ord, 'pref' ); ?> /><?php esc_html_e( 'State', 'usces' ); ?></label>
		<label for="chk_ord[country]"><input type="checkbox" class="check_order" id="chk_ord[country]" value="country"<?php usces_checked( $chk_ord, 'country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[zip]"><input type="checkbox" class="check_order" id="chk_ord[zip]" value="zip"<?php usces_checked( $chk_ord, 'zip' ); ?> /><?php esc_html_e( 'Zip', 'usces' ); ?></label>
		<label for="chk_ord[tel]"><input type="checkbox" class="check_order" id="chk_ord[tel]" value="tel"<?php usces_checked( $chk_ord, 'tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[fax]"><input type="checkbox" class="check_order" id="chk_ord[fax]" value="fax"<?php usces_checked( $chk_ord, 'fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'fax_after' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_ord, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<?php do_action( 'usces_action_chk_ord_customer', $chk_ord ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Shipping address information', 'usces' ); ?></legend>
<?php
if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'name_pre' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_ord, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<label for="chk_ord[delivery_name]"><input type="checkbox" class="check_order" id="chk_ord[delivery_name]" value="delivery_name"<?php usces_checked( $chk_ord, 'delivery_name' ); ?> /><?php esc_html_e( 'name', 'usces' ); ?></label>
<?php
switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[delivery_kana]"><input type="checkbox" class="check_order" id="chk_ord[delivery_kana]" value="delivery_kana"<?php usces_checked( $chk_ord, 'delivery_kana' ); ?> /><?php esc_html_e( 'furigana', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'name_after' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_ord, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}

switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[delivery_zip]"><input type="checkbox" class="check_order" id="chk_ord[delivery_zip]" value="delivery_zip"<?php usces_checked( $chk_ord, 'delivery_zip' ); ?> /><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></label>
		<label for="chk_ord[delivery_country]"><input type="checkbox" class="check_order" id="chk_ord[delivery_country]" value="delivery_country"<?php usces_checked( $chk_ord, 'delivery_country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[delivery_pref]"><input type="checkbox" class="check_order" id="chk_ord[delivery_pref]" value="delivery_pref"<?php usces_checked( $chk_ord, 'delivery_pref' ); ?> /><?php esc_html_e( 'Province', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address1]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address1]" value="delivery_address1"<?php usces_checked( $chk_ord, 'delivery_address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address2]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address2]" value="delivery_address2"<?php usces_checked( $chk_ord, 'delivery_address2' ); ?> /><?php esc_html_e( 'numbers', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address3]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address3]" value="delivery_address3"<?php usces_checked( $chk_ord, 'delivery_address3' ); ?> /><?php esc_html_e( 'building name', 'usces' ); ?></label>
		<label for="chk_ord[delivery_tel]"><input type="checkbox" class="check_order" id="chk_ord[delivery_tel]" value="delivery_tel"<?php usces_checked( $chk_ord, 'delivery_tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[delivery_fax]"><input type="checkbox" class="check_order" id="chk_ord[delivery_fax]" value="delivery_fax"<?php usces_checked( $chk_ord, 'delivery_fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
	case 'US':
	default:
		?>
		<label for="chk_ord[delivery_address2]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address2]" value="delivery_address2"<?php usces_checked( $chk_ord, 'delivery_address2' ); ?> /><?php esc_html_e( 'Address Line1', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address3]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address3]" value="delivery_address3"<?php usces_checked( $chk_ord, 'delivery_address3' ); ?> /><?php esc_html_e( 'Address Line2', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address1]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address1]" value="delivery_address1"<?php usces_checked( $chk_ord, 'delivery_address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[delivery_pref]"><input type="checkbox" class="check_order" id="chk_ord[delivery_pref]" value="delivery_pref"<?php usces_checked( $chk_ord, 'delivery_pref' ); ?> /><?php esc_html_e( 'State', 'usces' ); ?></label>
		<label for="chk_ord[delivery_country]"><input type="checkbox" class="check_order" id="chk_ord[delivery_country]" value="delivery_country"<?php usces_checked( $chk_ord, 'delivery_country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[delivery_zip]"><input type="checkbox" class="check_order" id="chk_ord[delivery_zip]" value="delivery_zip"<?php usces_checked( $chk_ord, 'delivery_zip' ); ?> /><?php esc_html_e( 'Zip', 'usces' ); ?></label>
		<label for="chk_ord[delivery_tel]"><input type="checkbox" class="check_order" id="chk_ord[delivery_tel]" value="delivery_tel"<?php usces_checked( $chk_ord, 'delivery_tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[delivery_fax]"><input type="checkbox" class="check_order" id="chk_ord[delivery_fax]" value="delivery_fax"<?php usces_checked( $chk_ord, 'delivery_fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'fax_after' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_ord, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<?php do_action( 'usces_action_chk_ord_delivery', $chk_ord ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Order Infomation', 'usces' ); ?></legend>
		<label for="chk_ord[shipping_date]"><input type="checkbox" class="check_order" id="chk_ord[shipping_date]" value="shipping_date"<?php usces_checked( $chk_ord, 'shipping_date' ); ?> /><?php esc_html_e( 'shpping date', 'usces' ); ?></label>
		<label for="chk_ord[peyment_method]"><input type="checkbox" class="check_order" id="chk_ord[peyment_method]" value="peyment_method"<?php usces_checked( $chk_ord, 'peyment_method' ); ?> /><?php esc_html_e( 'payment method', 'usces' ); ?></label>
		<label for="chk_ord[wc_trans_id]"><input type="checkbox" class="check_order" id="chk_ord[wc_trans_id]" value="wc_trans_id"<?php usces_checked( $chk_ord, 'wc_trans_id' ); ?> /><?php esc_html_e( 'Transaction ID', 'usces' ); ?></label>
		<label for="chk_ord[delivery_method]"><input type="checkbox" class="check_order" id="chk_ord[delivery_method]" value="delivery_method"<?php usces_checked( $chk_ord, 'delivery_method' ); ?> /><?php esc_html_e( 'shipping option', 'usces' ); ?></label>
		<label for="chk_ord[delivery_date]"><input type="checkbox" class="check_order" id="chk_ord[delivery_date]" value="delivery_date"<?php usces_checked( $chk_ord, 'delivery_date' ); ?> /><?php esc_html_e( 'Delivery date', 'usces' ); ?></label>
		<label for="chk_ord[delivery_time]"><input type="checkbox" class="check_order" id="chk_ord[delivery_time]" value="delivery_time"<?php usces_checked( $chk_ord, 'delivery_time' ); ?> /><?php esc_html_e( 'delivery time', 'usces' ); ?></label>
		<label for="chk_ord[delidue_date]"><input type="checkbox" class="check_order" id="chk_ord[delidue_date]" value="delidue_date"<?php usces_checked( $chk_ord, 'delidue_date' ); ?> /><?php esc_html_e( 'Shipping date', 'usces' ); ?></label>
		<label for="chk_ord[status]"><input type="checkbox" class="check_order" id="chk_ord[status]" value="status"<?php usces_checked( $chk_ord, 'status' ); ?> /><?php esc_html_e( 'Status', 'usces' ); ?></label>
		<label for="chk_ord[tracking_number]"><input type="checkbox" class="check_order" id="chk_ord[tracking_number]" value="tracking_number"<?php usces_checked( $chk_ord, 'tracking_number' ); ?> /><?php esc_html_e( 'Tracking number', 'usces' ); ?></label>
		<label for="chk_ord[total_amount]"><input type="checkbox" class="check_order" id="chk_ord[total_amount]" value="total_amount"<?php usces_checked( $chk_ord, 'total_amount' ); ?> /><?php esc_html_e( 'Total Amount', 'usces' ); ?></label>
		<label for="chk_ord[item_total_amount]"><input type="checkbox" class="check_order" id="chk_ord[item_total_amount]" value="item_total_amount"<?php usces_checked( $chk_ord, 'item_total_amount' ); ?> /><?php esc_html_e( 'total items', 'usces' ); ?></label>
<?php if ( usces_is_member_system() && usces_is_member_system_point() ) : ?>
		<label for="chk_ord[getpoint]"><input type="checkbox" class="check_order" id="chk_ord[getpoint]" value="getpoint"<?php usces_checked( $chk_ord, 'getpoint' ); ?> /><?php esc_html_e( 'granted points', 'usces' ); ?></label>
		<label for="chk_ord[usedpoint]"><input type="checkbox" class="check_order" id="chk_ord[usedpoint]" value="usedpoint"<?php usces_checked( $chk_ord, 'usedpoint' ); ?> /><?php esc_html_e( 'Used points', 'usces' ); ?></label>
<?php endif; ?>
		<label for="chk_ord[discount]"><input type="checkbox" class="check_order" id="chk_ord[discount]" value="discount"<?php usces_checked( $chk_ord, 'discount' ); ?> /><?php esc_html_e( 'Discount', 'usces' ); ?></label>
		<label for="chk_ord[shipping_charge]"><input type="checkbox" class="check_order" id="chk_ord[shipping_charge]" value="shipping_charge"<?php usces_checked( $chk_ord, 'shipping_charge' ); ?> /><?php esc_html_e( 'Shipping', 'usces' ); ?></label>
		<label for="chk_ord[cod_fee]"><input type="checkbox" class="check_order" id="chk_ord[cod_fee]" value="cod_fee"<?php usces_checked( $chk_ord, 'cod_fee' ); ?> /><?php echo apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ); // phpcs:ignore ?></label>
<?php if ( usces_is_tax_display() ) : ?>
		<label for="chk_ord[tax]"><input type="checkbox" class="check_order" id="chk_ord[tax]" value="tax"<?php usces_checked( $chk_ord, 'tax' ); ?> /><?php esc_html_e( 'consumption tax', 'usces' ); ?></label>
	<?php if ( usces_is_reduced_taxrate() ) : ?>
		<label for="chk_ord[subtotal_standard]"><input type="checkbox" class="check_order" id="chk_ord[subtotal_standard]" value="subtotal_standard"<?php usces_checked( $chk_ord, 'subtotal_standard' ); ?> /><?php printf( __( "Applies to %s%%", 'usces' ), $this->options['tax_rate'] ); // phpcs:ignore ?></label>
		<label for="chk_ord[tax_standard]"><input type="checkbox" class="check_order" id="chk_ord[tax_standard]" value="tax_standard"<?php usces_checked( $chk_ord, 'tax_standard' ); ?> /><?php printf( __( "%s%% consumption tax", 'usces' ), $this->options['tax_rate'] ); // phpcs:ignore ?></label>
		<label for="chk_ord[subtotal_reduced]"><input type="checkbox" class="check_order" id="chk_ord[subtotal_reduced]" value="subtotal_reduced"<?php usces_checked( $chk_ord, 'subtotal_reduced' ); ?> /><?php printf( __( "Applies to %s%%", 'usces' ), $this->options['tax_rate_reduced'] ); // phpcs:ignore ?></label>
		<label for="chk_ord[tax_reduced]"><input type="checkbox" class="check_order" id="chk_ord[tax_reduced]" value="tax_reduced"<?php usces_checked( $chk_ord, 'tax_reduced' ); ?> /><?php printf( __( "%s%% consumption tax", 'usces' ), $this->options['tax_rate_reduced'] ); // phpcs:ignore ?></label>
	<?php endif; ?>
<?php endif; ?>
		<label for="chk_ord[note]"><input type="checkbox" class="check_order" id="chk_ord[note]" value="note"<?php usces_checked( $chk_ord, 'note' ); ?> /><?php esc_html_e( 'Notes', 'usces' ); ?></label>
<?php
if ( ! empty( $csod_meta ) ) {
	foreach ( $csod_meta as $key => $entry ) {
		$csod_key = 'csod_' . $key;
		$checked  = usces_checked( $chk_ord, $csod_key, 'return' );
		$name     = $entry['name'];
		echo '<label for="chk_ord[' . esc_attr( $csod_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csod_key ) . ']" value="' . esc_attr( $csod_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
	}
}
?>
		<?php do_action( 'usces_action_chk_ord_order', $chk_ord ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Admin Information', 'usces' ); ?></legend>
		<label for="chk_ord[admin_memo]"><input type="checkbox" class="check_order" id="chk_ord[admin_memo]" value="admin_memo"<?php usces_checked( $chk_ord, 'admin_memo' ); ?> /><?php esc_html_e( 'Admin Note', 'usces' ); ?></label>
		<?php do_action( 'usces_action_chk_ord_memo', $chk_ord ); ?>
	</fieldset>
</div>
<?php echo apply_filters( 'usces_filter_order_list_footer', '' ); // phpcs:ignore ?>
<?php wp_nonce_field( 'order_list', 'wc_nonce' ); ?>
</form>
<?php usces_order_list_form_settlement_dialog(); ?>
<?php do_action( 'usces_action_order_list_footer' ); ?>
</div><!--usces_admin-->
</div><!--wrap-->
<script type="text/javascript">
jQuery(function($){
	$('#tablesearch .search_submit input[name=searchIn][type=submit]').on('click', function () {
		var dateErrorMsg = '<?php esc_html_e( 'Please enter the date in the format yyyy-mm-dd.', 'usces' ); ?>';
		var dateFields = ['order_date', 'order_modified', 'deli_date', 'delidue_date'];
		var field0 = $('select[name="search[order_column][0]"').val();
		var field1 = $('select[name="search[order_column][1]"').val();
		var orderWordTerm0 = $('select[name="search[order_word_term][0]"]').val();
		var isNotContainOperator0 = 'contain' !== orderWordTerm0;
		var orderWordTerm1 = $('select[name="search[order_word_term][1]"]').val();
		var isNotContainOperator1 = 'contain' !== orderWordTerm1;

		if (dateFields.indexOf(field0) > -1 && isNotContainOperator0) {
			var word0 = $('input[name="search[order_word][0]"]').val().trim();
			if (!isValidDate(word0)) {
				alert(dateErrorMsg);
				return false;
			}
		}

		if (dateFields.indexOf(field1) > -1 && isNotContainOperator1) {
			var word1 = $('input[name="search[order_word][1]"]').val().trim();
			if (!isValidDate(word1)) {
				alert(dateErrorMsg);
				return false;
			}
		}

		return true;
	});

	function isValidDate(dateString) {
		var regEx = /^\d{4}-\d{2}-\d{2}$/;
		if (!dateString.match(regEx)) {
			return false;
		}

		var d = new Date(dateString);
		var dNum = d.getTime();
		if (!dNum && dNum !== 0) {
			return false;
		}

		return d.toISOString().slice(0, 10) === dateString;
	}

	$("input[name='allcheck']").click(function () {
		if( $(this).prop("checked") ){
			$("input[name*='listcheck']").prop({checked: true});
		}else{
			$("input[name*='listcheck']").prop({checked: false});
		}
	});

	operation = {
		change_order_search_field_0 :function (is_change = true){
			var html = '';
			var column = $("#searchorderselect_0").val();
			var mailprint = $("#searchorderselect_0 option:selected").attr('data-mailprint');

			if( column == 'estimate_status' ) {

				html = '<select name="search[order_word][0]" class="searchselect">';
<?php
foreach ( (array) $estimate_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['order_word'][0] ) && $rkey == $arr_search['order_word'][0] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rkey ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			}else if( column == 'process_status' ) {

				html = '<select name="search[order_word][0]" class="searchselect">';
<?php
foreach ( (array) $process_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['order_word'][0] ) && $rkey == $arr_search['order_word'][0] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rkey ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			}else if( column == 'receipt_status' ) {

				html = '<select name="search[order_word][0]" class="searchselect">';
<?php
foreach ( (array) $receipt_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['order_word'][0] ) && $rkey == $arr_search['order_word'][0] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rkey ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			} else if( column == 'deli_method' ) {

				html = '<select name="search[order_word][0]" class="searchselect">';
<?php
foreach ( (array) $delivery_method as $idx => $delivery ) :
	$rselected = ( isset( $arr_search['order_word'][0] ) && $delivery['id'] == $arr_search['order_word'][0] ) ? ' selected="selected"' : '';
	?>
				html += '<option value="<?php echo esc_attr( $delivery['id'] ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $delivery['name'] ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			} else if( mailprint ) {

				html = '<select name="search[order_word][0]" class="searchselect">';
				if ( mailprint === 'mail' ) {
<?php
	foreach( $arr_mail_print_status['mail'] as $k_pm => $v_pm ):
		$rselected = ( isset($arr_search['order_word'][0]) && $k_pm == $arr_search['order_word'][0] ) ? ' selected="selected"' : '';
?>
				html += '<option value="<?php echo esc_attr($k_pm); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $v_pm['alias'] ); ?></option>';
<?php
	endforeach;
?>
				} else if ( mailprint === 'print' ) {
<?php
	foreach( $arr_mail_print_status['print'] as $k_pm => $v_pm ):
		$rselected = ( isset($arr_search['order_word'][0]) && $k_pm == $arr_search['order_word'][0] ) ? ' selected="selected"' : '';
?>
				html += '<option value="<?php echo esc_attr($k_pm); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $v_pm['alias'] ); ?></option>';
<?php
	endforeach;
?>
				}
				html += '</select>';

			} else {
				html = '<input name="search[order_word][0]" type="text" value="'+ (!(is_change) ? "<?php echo esc_attr( $arr_search['order_word'][0] ); ?>" : "") +'" class="regular-text" maxlength="50" />';
			}

			html += '<select name="search[order_word_term][0]" class="termselect">';
			html += '<option value="contain"<?php echo ( 'contain' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Contain', 'usces' ); ?></option>';
			html += '<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Not Contain', 'usces' ); ?></option>';
			html += '<option value="equal"<?php echo ( 'equal' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Equal', 'usces' ); ?></option>';
			html += '<option value="morethan"<?php echo ( 'morethan' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'More than', 'usces' ); ?></option>';
			html += '<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['order_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Less than', 'usces' ); ?></option>';
			html += '</select>';

			$("#searchorderword_0").html( html );
		},

		change_order_search_field_1 :function (is_change = true){
			var html = '';
			var column = $("#searchorderselect_1").val();
			var mailprint = $("#searchorderselect_1 option:selected").attr('data-mailprint');

			if( column == 'estimate_status' ) {

				html = '<select name="search[order_word][1]" class="searchselect">';
<?php
foreach ( (array) $estimate_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['order_word'][1] ) && $rkey == $arr_search['order_word'][1] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rkey ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			}else if( column == 'process_status' ) {

				html = '<select name="search[order_word][1]" class="searchselect">';
<?php
foreach ( (array) $process_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['order_word'][1] ) && $rkey == $arr_search['order_word'][1] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rkey ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			}else if( column == 'receipt_status' ) {

				html = '<select name="search[order_word][1]" class="searchselect">';
<?php
foreach ( (array) $receipt_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['order_word'][1] ) && $rkey == $arr_search['order_word'][1] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rkey ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			} else if ( column == 'deli_method' ) {

				html = '<select name="search[order_word][1]" class="searchselect">';
<?php
foreach ( (array) $delivery_method as $idx => $delivery ) :
	$rselected = ( isset( $arr_search['order_word'][1] ) && $delivery['id'] == $arr_search['order_word'][1] ) ? ' selected="selected"' : '';
	?>
				html += '<option value="<?php echo esc_attr( $delivery['id'] ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $delivery['name'] ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';

			} else if( mailprint ) {

				html = '<select name="search[order_word][1]" class="searchselect">';
				if ( mailprint === 'mail' ) {
<?php
	foreach( $arr_mail_print_status['mail'] as $k_pm => $v_pm ):
		$rselected = ( isset($arr_search['order_word'][1]) && $k_pm == $arr_search['order_word'][1] ) ? ' selected="selected"' : '';
?>
				html += '<option value="<?php echo esc_attr($k_pm); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $v_pm['alias'] ); ?></option>';
<?php
	endforeach;
?>
				} else if ( mailprint === 'print' ) {
<?php
	foreach( $arr_mail_print_status['print'] as $k_pm => $v_pm ):
		$rselected = ( isset($arr_search['order_word'][1]) && $k_pm == $arr_search['order_word'][1] ) ? ' selected="selected"' : '';
?>
				html += '<option value="<?php echo esc_attr($k_pm); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $v_pm['alias'] ); ?></option>';
<?php
	endforeach;
?>
				}
				html += '</select>';

			} else {

				html = '<input name="search[order_word][1]" type="text" value="'+ (!(is_change) ? "<?php echo esc_attr( $arr_search['order_word'][1] ); ?>" : "") +'" class="regular-text" maxlength="50" />';
			}

			html += '<select name="search[order_word_term][1]" class="termselect">';
			html += '<option value="contain"<?php echo ( 'contain' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Contain', 'usces' ); ?></option>';
			html += '<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Not Contain', 'usces' ); ?></option>';
			html += '<option value="equal"<?php echo ( 'equal' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Equal', 'usces' ); ?></option>';
			html += '<option value="morethan"<?php echo ( 'morethan' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'More than', 'usces' ); ?></option>';
			html += '<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['order_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Less than', 'usces' ); ?></option>';
			html += '</select>';

			$("#searchorderword_1").html( html );
		},

		change_product_search_field_0 :function (){
			var html = '';
			var column = $("#searchproductselect_0").val();

			if( column == 'item_option' ) {

				html = '<?php esc_html_e( 'option name', 'usces' ); ?>:<input name="search[product_word][0]" type="text" value="<?php echo esc_attr( $arr_search['product_word'][0] ); ?>" class="text" maxlength="50" /> <?php esc_html_e( 'option value', 'usces' ); ?>:<input name="search[option_word][0]" type="text" value="<?php echo esc_attr( $arr_search['option_word'][0] ); ?>" class="text" maxlength="50" />';

			}else{

				html = '<input name="search[product_word][0]" type="text" value="<?php echo esc_attr( $arr_search['product_word'][0] ); ?>" class="regular-text" maxlength="50" />';
				html += '<select name="search[product_word_term][0]" class="termselect">';
				html += '<option value="contain"<?php echo ( 'contain' == $arr_search['product_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Contain', 'usces' ); ?></option>';
				html += '<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['product_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Not Contain', 'usces' ); ?></option>';
				html += '<option value="equal"<?php echo ( 'equal' == $arr_search['product_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Equal', 'usces' ); ?></option>';
				html += '<option value="morethan"<?php echo ( 'morethan' == $arr_search['product_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'More than', 'usces' ); ?></option>';
				html += '<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['product_word_term'][0] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Less than', 'usces' ); ?></option>';
				html += '</select>';
			}

			$("#searchproductword_0").html( html );
		},

		change_product_search_field_1 :function (){
			var html = '';
			var column = $("#searchproductselect_1").val();

			if( column == 'item_option' ) {

				html = '<?php esc_html_e( 'option name', 'usces' ); ?>:<input name="search[product_word][1]" type="text" value="<?php echo esc_attr( $arr_search['product_word'][1] ); ?>" class="text" maxlength="50" /> <?php esc_html_e( 'option value', 'usces' ); ?>:<input name="search[option_word][1]" type="text" value="<?php echo esc_attr( $arr_search['option_word'][1] ); ?>" class="text" maxlength="50" />';

			}else{

				html = '<input name="search[product_word][1]" type="text" value="<?php echo esc_attr( $arr_search['product_word'][1] ); ?>" class="regular-text" maxlength="50" />';
				html += '<select name="search[product_word_term][1]" class="termselect">';
				html += '<option value="contain"<?php echo ( 'contain' == $arr_search['product_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Contain', 'usces' ); ?></option>';
				html += '<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['product_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Not Contain', 'usces' ); ?></option>';
				html += '<option value="equal"<?php echo ( 'equal' == $arr_search['product_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Equal', 'usces' ); ?></option>';
				html += '<option value="morethan"<?php echo ( 'morethan' == $arr_search['product_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'More than', 'usces' ); ?></option>';
				html += '<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['product_word_term'][1] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Less than', 'usces' ); ?></option>';
				html += '</select>';
			}

			$("#searchproductword_1").html( html );
		},

		change_collective_field :function (){
			var label = '';
			var html = '';
			var column = $("#changeselect").val();

			if( column == 'receipt_status' ) {
				label = '';
				html = '<select name="change[word]" class="searchselect">';
<?php foreach ( (array) $receipt_status as $orkey => $orvalue ) : ?>
				html += '<option value="<?php echo esc_attr( $orkey ); ?>"><?php echo esc_html( $orvalue ); ?></option>';
<?php endforeach; ?>
				html += '</select>';
			}else if( column == 'estimate_status' ) {
				label = '';
				html = '<select name="change[word]" class="searchselect">';
<?php foreach ( (array) $estimate_status as $oskey => $osvalue ) : ?>
				html += '<option value="<?php echo esc_attr( $oskey ); ?>"><?php echo esc_html( $osvalue ); ?></option>';
<?php endforeach; ?>
				html += '</select>';
			}else if( column == 'process_status' ) {
				label = '';
				html = '<select name="change[word]" class="searchselect">';
<?php foreach ( (array) $process_status as $oskey => $osvalue ) : ?>
				html += '<option value="<?php echo esc_attr( $oskey ); ?>"><?php echo esc_html( $osvalue ); ?></option>';
<?php endforeach; ?>
				html += '</select>';
			}else if( column == 'delete' ) {
				label = '';
				html = '';
			}

			$("#changelabel").html( label );
			$("#changefield").html( html );
		}
	};

	$("#searchorderselect_0").change(function () {
		operation.change_order_search_field_0();
	});
	$("#searchorderselect_1").change(function () {
		operation.change_order_search_field_1();
	});
	$("#searchproductselect_0").change(function () {
		operation.change_product_search_field_0();
	});
	$("#searchproductselect_1").change(function () {
		operation.change_product_search_field_1();
	});
	$("#changeselect").change(function () {
		operation.change_collective_field();
	});
	operation.change_order_search_field_0(false);
	operation.change_order_search_field_1(false);
	operation.change_product_search_field_0();
	operation.change_product_search_field_1();
	operation.change_collective_field();

	$("#collective_change").click(function () {
		if( $("#changeselect option:selected").val() == '' ) {
			$("#orderlistaction").val('');
			return false;
		}
		if( $("input[name*='listcheck']:checked").length == 0 ) {
			alert("<?php esc_html_e( 'Choose the data.', 'usces' ); ?>");
			$("#orderlistaction").val('');
			return false;
		}
		var coll = $("#changeselect").val();
		var mes = '';
		if( coll == 'receipt_status' ){
			mes = <?php echo sprintf( __( "'Transfer status of the items which you have checked will be changed in to ' + %s + '. %sDo you agree?'", 'usces' ), '$("select\[name=\"change\[word\]\"\] option:selected").html()', '\n\n' ); // phpcs:ignore ?>;
		}else if( coll == 'estimate_status' ){
			mes = <?php echo sprintf( __( "'Data status which you have cheked will be changed in to ' + %s + '. %sDo you agree?'", 'usces' ), '$("select\[name=\"change\[word\]\"\] option:selected").html()', '\n\n' ); // phpcs:ignore ?>;
		}else if( coll == 'process_status' ){
			mes = <?php echo sprintf( __( "'Data status which you have cheked will be changed in to ' + %s + '. %sDo you agree?'", 'usces' ), '$("select\[name=\"change\[word\]\"\] option:selected").html()', '\n\n' ); // phpcs:ignore ?>;
		}else if(coll == 'delete'){
			mes = '<?php esc_html_e( 'Are you sure of deleting all the checked data in bulk?', 'usces' ); ?>';
		}
		if( mes != '' ) {
			if( !confirm(mes) ){
				$("#orderlistaction").val('');
				return false;
			}
		}
		<?php do_action( 'usces_action_order_list_collective_change_js' ); ?>
		$("#orderlistaction").val('collective');
		$('#form_tablesearch').submit();
	});

	$("#startdate").datepicker({
		dateFormat: "yy-mm-dd",
		onSelect: function(){$("#datepic_period").val(3);}
	});
	$("#enddate").datepicker({
		dateFormat: "yy-mm-dd",
		onSelect: function(){$("#datepic_period").val(3);}
	});
	$("#datepic_period").change( function(){
		if( '3' == $(this).val() ){
			$("#startdate").prop( 'disabled', false );
			$("#enddate").prop( 'disabled', false );
		}else{
			$("#startdate").prop( 'disabled', true );
			$("#enddate").prop( 'disabled', true );
		}
	});
	$("#apply_datepic").click(function(){
		$("#period").val('<input name="changePeriod" type="hidden" value="1" />');
		if( '3' == $("#datepic_period").val() ){
			period = '3';
			startdate = $("#startdate").val();
			enddate = $("#enddate").val();
		}else{
			period = $("#datepic_period").val();
			startdate = '';
			enddate = '';
		}
		cvalue = 'period=' + period + '&start=' + startdate + '&end=' + enddate;
		$.cookie("orderPeriod", cvalue, { expires: 30, path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"});
		$("#orderlistaction").val('');
		$("#form_tablesearch").append('<input name="searchIn" type="hidden" value="1" />');
		$('#form_tablesearch').submit();
	});

	$("#datepic_title").click(
		function(){
			$(this).parents("#datepic_navi").toggleClass("active");
		}
	);

	if( '0' == $("#datepic_period").val() ){
		$("#datepic_title").html('<span class="dashicons dashicons-calendar-alt"></span><?php esc_html_e( 'All of period', 'usces' ); ?>');
	}else if( '1' == $("#datepic_period").val() ){
		$("#datepic_title").html('<span class="dashicons dashicons-calendar-alt"></span><?php esc_html_e( 'This month', 'usces' ); ?>');
	}else if( '2' == $("#datepic_period").val() ){
		$("#datepic_title").html('<span class="dashicons dashicons-calendar-alt"></span><?php esc_html_e( 'Last month', 'usces' ); ?>');
	}else{
		if( '' == $("#startdate").val() ){
			sdate = '<?php esc_html_e( 'first day', 'usces' ); ?>';
		}else{
			sdate = $("#startdate").val();
		}
		if( '' == $("#enddate").val() ){
			edate = '<?php esc_html_e( 'today', 'usces' ); ?>';
		}else{
			edate = $("#enddate").val();
		}
		$("#datepic_title").html('<span class="dashicons dashicons-calendar-alt"></span>' + sdate + ' <?php esc_html_e( 'to', 'usces' ); ?> ' + edate);
	}

<?php usces_order_list_js_settlement_dialog(); ?>
<?php echo apply_filters( 'usces_filter_order_list_page_js', '', $DT ); // phpcs:ignore ?>

	$('table#mainDataTable tbody input[type=checkbox]').change(
		function() {
			$('input').closest('tbody').removeClass('select');
			$(':checked').closest('tbody').addClass('select');
		}
	).trigger('change');

	$("#searchVisiLink").click(function() {
		if ( $("#searchBox").css("display") != "block" ){
			$("#searchBox").slideDown(300);
			$("#searchVisiLink").html('<?php esc_html_e( 'Hide the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-up"></span>');
			$.cookie("orderSearchBox", 1, { path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"}) == true;
		}else{
			$("#searchBox").slideUp(300);
			$("#searchVisiLink").html('<?php esc_html_e( 'Show the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-down"></span>');
			$.cookie("orderSearchBox", 0, { path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"}) == true;
		}
	});

	(function setCookie() {
<?php
// phpcs:disable
$data_cookie                       = array();
$data_cookie['placeholder_escape'] = $DT->placeholder_escape;
$data_cookie['startRow']           = $DT->startRow;        /* 表示開始行番号 */
$data_cookie['sortColumn']         = $DT->sortColumn;      /* 現在ソート中のフィールド */
$data_cookie['totalRow']           = $DT->totalRow;        /* 全行数 */
$data_cookie['selectedRow']        = $DT->selectedRow;     /* 絞り込まれた行数 */
$data_cookie['currentPage']        = $DT->currentPage;     /* 現在のページNo */
$data_cookie['previousPage']       = $DT->previousPage;    /* 前のページNo */
$data_cookie['nextPage']           = $DT->nextPage;        /* 次のページNo */
$data_cookie['lastPage']           = $DT->lastPage;        /* 最終ページNo */
$data_cookie['userHeaderNames']    = $DT->userHeaderNames; /* 全てのフィールド */
$data_cookie['sortSwitchs']        = $DT->sortSwitchs;     /* 各フィールド毎の昇順降順スイッチ */
$data_cookie['arr_search']         = $DT->arr_search;
// phpcs:enable
?>
		$.cookie('<?php echo "{$DT->table}_path"; ?>', '<?php echo esc_url( $usces_admin_path ); ?>', { path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"});
		$.cookie('<?php echo "{$DT->table}"; ?>', '<?php echo str_replace( "'", "\'", json_encode( $data_cookie ) ); ?>', { path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"});
		// save list current_page_ids to localStorage.
		window.localStorage.setItem('wel_order_current_page_ids', <?php echo wp_json_encode( $DT->currentPageIds ); ?>);
	})();

	if ($.cookie("orderSearchBox") == true){
		$("#searchVisiLink").html('<?php esc_html_e( 'Hide the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-up"></span>');
		$("#searchBox").show();
	}else if ($.cookie("orderSearchBox") == false){
		$("#searchVisiLink").html('<?php esc_html_e( 'Show the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-down"></span>');
		$("#searchBox").hide();
	}

	$("#dlProductListDialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 700,
		width: 820,
		resizable: true,
		modal: true,
		buttons: {
			'<?php esc_html_e( 'close', 'usces' ); ?>': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	$('#dl_pro').click(function() {
		var args = "&ftype=csv&returnList=1";
		$(".check_pro").each(function(i) {
			if($(this).prop('checked')) {
				args += '&check['+$(this).val()+']=on';
			}
		});
		location.href = "<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_orderlist&order_action=dlproductnewlist&noheader=true"+args;
	});
	$('#dl_productlist').click(function() {
		$('#dlProductListDialog').dialog('open');
	});

	$("#dlOrderListDialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 600,
		width: 820,
		resizable: true,
		modal: true,
		buttons: {
			'<?php esc_html_e( 'close', 'usces' ); ?>': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	$('#dl_ord').click(function() {
		var args = "&ftype=csv&returnList=1";
		$(".check_order").each(function(i) {
			if($(this).prop('checked')) {
				args += '&check['+$(this).val()+']=on';
			}
		});
		location.href = "<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_orderlist&order_action=dlordernewlist&noheader=true"+args;
	});
	$('#dl_orderlist').click(function() {
		$('#dlOrderListDialog').dialog('open');
	});

<?php if ( isset( $_GET['order_action'] ) && 'settlement_notice' == $_GET['order_action'] ) : ?>
	$("#searchBox").css( "display","block" );
	$("#searchSwitchStatus").val( "ON" );
	$("#searchSwitchStatus").css( "display","none" );
	$("#settlement_errorlog").trigger( "click" );
<?php endif; ?>
<?php if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) : ?>
	$(".regular_parent_order").removeClass( "rowSelection_even" );
	$(".regular_parent_order").css( "background-color","#e6fe9e" );
<?php endif; ?>
<?php do_action( 'usces_action_orderlist_document_ready_js', $DT ); // phpcs:ignore ?>

});

function deleteconfirm(order_id){
	if(confirm(<?php _e( "'Are you sure of deleting an order number ' + order_id + ' ?'", 'usces' ); // phpcs:ignore ?>)){
		return true;
	}else{
		return false;
	}
}
</script>
