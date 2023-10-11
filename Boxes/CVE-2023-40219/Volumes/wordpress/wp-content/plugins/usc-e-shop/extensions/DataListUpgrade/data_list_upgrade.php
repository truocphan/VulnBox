<?php
/**
 * Order & Member List Upgrade.
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @since    1.8.0
 */
class USCES_DATALIST_UPGRADE {

	/**
	 * Extended Options.
	 *
	 * @var object
	 */
	public static $opts;

	/**
	 * Construct.
	 */
	public function __construct() {

		self::initialize_data();

		if ( is_admin() ) {

			add_action( 'usces_action_admin_system_extentions', array( $this, 'setting_form' ) );
			add_action( 'init', array( $this, 'save_data' ) );

			if ( self::$opts['orderlist_flag'] ) {
				if ( isset( $_REQUEST['order_action'] ) && 'dlordernewlist' == $_REQUEST['order_action'] ) {
					add_action( 'admin_init', array( $this, 'download_order_list' ) );
				} elseif ( isset( $_REQUEST['order_action'] ) && 'dlproductnewlist' == $_REQUEST['order_action'] ) {
					add_action( 'admin_init', array( $this, 'download_orderdetail_list' ) );
				}
				add_action( 'load-toplevel_page_usces_orderlist', 'usces_admin_order_list_hook' );
				add_filter( 'screen_settings', 'usces_orderlist_screen_settings', 10, 2 );
				add_filter( 'usces_admin_order_list', array( $this, 'change_orderlist' ) );
				add_action( 'usces_action_chk_pro_order', array( $this, 'self_action_chk_pro_order' ), 30 );
				add_action( 'usces_action_chk_ord_order', array( $this, 'self_action_chk_ord_order' ), 30 );
				add_filter( 'usces_filter_chk_pro', array( $this, 'self_filter_chk_pro' ), 30 );
				add_filter( 'usces_filter_chk_pro_label_order', array( $this, 'self_filter_chk_pro_label_order' ), 30, 3 );
				add_filter( 'usces_filter_chk_pro_data_order', array( $this, 'self_filter_chk_pro_data_order' ), 30, 5 );
				add_filter( 'usces_filter_chk_ord', array( $this, 'self_filter_chk_ord' ), 30 );
				add_filter( 'usces_filter_chk_ord_label_order', array( $this, 'self_filter_chk_ord_label_order' ), 30, 4 );
				add_filter( 'usces_filter_chk_ord_data_order', array( $this, 'self_filter_chk_ord_data_order' ), 30, 5 );
			}
			if ( self::$opts['memberlist_flag'] ) {
				if ( isset( $_REQUEST['member_action'] ) && 'dlmembernewlist' == $_REQUEST['member_action'] ) {
					add_action( 'admin_init', array( $this, 'download_member_list' ) );
				}
				add_action( 'load-welcart-management_page_usces_memberlist', 'usces_admin_member_list_hook' );
				add_filter( 'screen_settings', 'usces_memberlist_screen_settings', 10, 2 );
				add_filter( 'usces_admin_member_list', array( $this, 'change_memberlist' ) );
			}
		}
	}

	public static function get_value_print_mail() {
		return array(
			'mail' => array(
				0 => array(
					'label' => __("Don't send", 'usces'),
					'alias' => '',
				),
				1 => array(
					'label' => __('Send', 'usces'),
					'alias' => __('Done', 'usces'),
				),
			),
			'print' => array(
				0 => array(
					'label' => __("Don't print", 'usces'),
					'alias' => '',
				),
				1 => array(
					'label' => __('Print', 'usces'),
					'alias' => __('Done', 'usces'),
				),
			),

		);
	}

	/**
	 * Initialize
	 * Modified:2 Nov.2015
	 */
	public function initialize_data() {
		global $usces;
		$options = get_option( 'usces_ex' );
		$options['system']['datalistup']['orderlist_flag']  = ( ! isset( $options['system']['datalistup']['orderlist_flag'] ) ) ? 1 : (int) $options['system']['datalistup']['orderlist_flag'];
		$options['system']['datalistup']['memberlist_flag'] = ( ! isset( $options['system']['datalistup']['memberlist_flag'] ) ) ? 1 : (int) $options['system']['datalistup']['memberlist_flag'];
		update_option( 'usces_ex', $options );
		self::$opts = $options['system']['datalistup'];
	}

	/**
	 * Save option data
	 * Modified:10 Oct.2015
	 */
	public function save_data() {
		global $usces;

		if ( isset( $_POST['usces_datalistup_option_update'] ) ) {

			check_admin_referer( 'admin_system', 'wc_nonce' );

			self::$opts['orderlist_flag']  = ( isset( $_POST['datalistup_orderlist_flag'] ) ) ? (int) $_POST['datalistup_orderlist_flag'] : 1;
			self::$opts['memberlist_flag'] = ( isset( $_POST['datalistup_memberlist_flag'] ) ) ? (int) $_POST['datalistup_memberlist_flag'] : 1;

			$options                         = get_option( 'usces_ex' );
			$options['system']['datalistup'] = self::$opts;
			update_option( 'usces_ex', $options );
		}
	}

	/**
	 * Setting_form
	 * Modified:10 Oct.2015
	 */
	public function setting_form() {
		$status                      = ( self::$opts['orderlist_flag'] || self::$opts['memberlist_flag'] ) ? '<span class="running">' . __( 'Running', 'usces' ) . '</span>' : '<span class="stopped">' . __( 'Stopped', 'usces' ) . '</span>';
		$datalistup_orderlist_flag0  = ( 0 === self::$opts['orderlist_flag'] ) ? ' checked="checked"' : '';
		$datalistup_orderlist_flag1  = ( 1 === self::$opts['orderlist_flag'] ) ? ' checked="checked"' : '';
		$datalistup_memberlist_flag0 = ( 0 === self::$opts['memberlist_flag'] ) ? ' checked="checked"' : '';
		$datalistup_memberlist_flag1 = ( 1 === self::$opts['memberlist_flag'] ) ? ' checked="checked"' : '';
		?>
	<form action="" method="post" name="option_form" id="datalistup_form">
	<div class="postbox">
		<div class="postbox-header">
			<h2><span><?php esc_attr_e( 'Data List Upgrade', 'usces' ); ?></span><?php wel_esc_script_e( $status ); ?></h2>
			<div class="handle-actions"><button type="button" class="handlediv" id="datalistup"><span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Toggle panel: %s' ), __( 'Data List Upgrade', 'usces' ) ) ); ?></span><span class="toggle-indicator"></span></button></div>
		</div>
		<div class="inside">
		<table class="form_table">
			<tr height="35">
				<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility( 'ex_datalistup_orderlist_flag' );"><?php esc_attr_e( 'New Order List', 'usces' ); ?></a></th>
				<td width="10"><input name="datalistup_orderlist_flag" id="datalistup_orderlist_flag0" type="radio" value="0"<?php echo esc_html( $datalistup_orderlist_flag0 ); ?> /></td><td width="100"><label for="datalistup_orderlist_flag0"><?php esc_attr_e( 'disable', 'usces' ); ?></label></td>
				<td width="10"><input name="datalistup_orderlist_flag" id="datalistup_orderlist_flag1" type="radio" value="1"<?php echo esc_html( $datalistup_orderlist_flag1 ); ?> /></td><td width="100"><label for="datalistup_orderlist_flag1"><?php esc_attr_e( 'enable', 'usces' ); ?></label></td>
				<td><div id="ex_datalistup_orderlist_flag" class="explanation"></div></td>
			</tr>
			<tr height="35">
				<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility( 'ex_datalistup_memberlist_flag' );"><?php esc_attr_e( 'New Member List', 'usces' ); ?></a></th>
				<td width="10"><input name="datalistup_memberlist_flag" id="datalistup_memberlist_flag0" type="radio" value="0"<?php echo esc_html( $datalistup_memberlist_flag0 ); ?> /></td><td width="100"><label for="datalistup_memberlist_flag0"><?php esc_attr_e( 'disable', 'usces' ); ?></label></td>
				<td width="10"><input name="datalistup_memberlist_flag" id="datalistup_memberlist_flag1" type="radio" value="1"<?php echo esc_html( $datalistup_memberlist_flag1 ); ?> /></td><td width="100"><label for="datalistup_memberlist_flag1"><?php esc_attr_e( 'enable', 'usces' ); ?></label></td>
				<td><div id="ex_datalistup_memberlist_flag" class="explanation"></div></td>
			</tr>
		</table>
		<hr />
		<input name="usces_datalistup_option_update" type="submit" class="button button-primary" value="<?php esc_html_e( 'change decision', 'usces' ); ?>" />
		</div>
	</div><!--postbox-->
		<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
	</form>
		<?php
	}

	/**
	 * Change member list.
	 */
	public function change_memberlist() {
		return USCES_PLUGIN_DIR . '/includes/memberlist_page.php';
	}

	/**
	 * Change order list.
	 */
	public function change_orderlist() {
		return USCES_PLUGIN_DIR . '/includes/orderlist_page.php';
	}

	/**
	 * Member list download.
	 */
	public function download_member_list() {
		global $wpdb, $usces, $usces_settings;

		if ( ! usces_admin_user_can_download_list() ) {
			exit();
		}

		require_once USCES_PLUGIN_DIR . '/classes/memberList.class.php';

		$DT            = new WlcMemberList();
		$DT->pageLimit = 'off';
		$arr_column    = $DT->get_column();
		$res           = $DT->MakeTable();
		$arr_search    = $DT->GetSearchs();
		$rows          = $DT->rows;

		$ext = wp_unslash( $_REQUEST['ftype'] );
		if ( 'csv' == $ext ) {
			$table_h = '';
			$table_f = '';
			$tr_h    = '';
			$tr_f    = '';
			$th_h1   = '"';
			$th_h    = ',"';
			$th_f    = '"';
			$td_h1   = '"';
			$td_h    = ',"';
			$td_f    = '"';
			$lf      = "\n";
		} else {
			exit();
		}

		// ==========================================================================

		$usces_opt_member = get_option( 'usces_opt_member' );
		if ( ! is_array( $usces_opt_member ) ) {
			$usces_opt_member = array();
		}
		$usces_opt_member['ftype_mem'] = $ext;
		$chk_mem                       = array();
		foreach ( $arr_column as $key => $label ) {
			if ( 'csod_' == substr( $key, 0, 5 ) ) {
				continue;
			}
			$chk_mem[ $key ] = ( isset( $_REQUEST['check'][ $key ] ) ) ? 1 : 0;
		}
		$usces_opt_member['chk_mem'] = apply_filters( 'usces_filter_chk_mem', $chk_mem );
		update_option( 'usces_opt_member', $usces_opt_member );

		// ==========================================================================

		$line     = $table_h;
		$line    .= $tr_h;
		$line_col = '';
		foreach ( $arr_column as $key => $label ) {
			if ( 'csod_' == substr( $key, 0, 5 ) ) {
				continue;
			}
			if ( isset( $_REQUEST['check'][ $key ] ) ) {
				$line_col .= $th_h . usces_entity_decode( $label, $ext ) . $th_f;
			}
		}
		$line_col = ltrim( $line_col, ',' );
		$line    .= $line_col;
		$line     = apply_filters( 'usces_filter_mem_label', $line, $line, $line_col );
		$line    .= apply_filters( 'usces_filter_chk_mem_label', null, $usces_opt_member, $rows, $line_col );
		$line    .= $tr_f . $lf;

		// ==========================================================================

		foreach ( (array) $rows as $array ) {
			$line    .= $tr_h;
			$line_row = '';
			foreach ( $arr_column as $key => $label ) {
				if ( 'csod_' == substr( $key, 0, 5 ) ) {
					continue;
				}
				if ( 'csmb_' == substr( $key, 0, 5 ) ) {
					$multi_value = maybe_unserialize( $array[ $key ] );
					if ( is_array( $multi_value ) ) {
						$value = '';
						foreach ( $multi_value as $str ) {
							$value .= $str . ' ';
						}
						$array[ $key ] = trim( $value );
					}
				}
				if ( isset( $_REQUEST['check'][ $key ] ) ) {
					$line_row .= $td_h . usces_entity_decode( $array[ $key ], $ext ) . $td_f;
				}
			}
			$line_row = preg_replace( "/\n,/", "\n", ltrim( $line_row, ',' ) );
			$line    .= $line_row;
			$line     = apply_filters( 'usces_filter_mem_data', $line, $line, $line_row );
			$line    .= apply_filters( 'usces_filter_chk_mem_data', null, $usces_opt_member, $array['ID'], $array, $line_row );
			$line    .= $tr_f . $lf;
		}
		$line .= $table_f;

		// ==========================================================================

		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=usces_member_list.' . $ext );
		mb_http_output( 'pass' );
		print( mb_convert_encoding( $line, apply_filters( 'usces_filter_output_csv_encode', 'SJIS-win' ), 'UTF-8' ) );
		exit();
	}

	/**
	 * Product list download.
	 */
	public function download_orderdetail_list() {
		global $wpdb, $usces, $usces_settings;

		if ( ! usces_admin_user_can_download_list() ) {
			exit();
		}

		require_once USCES_PLUGIN_DIR . '/classes/orderList2.class.php';

		$all_column    = true;
		$DT            = new WlcOrderList( $all_column );
		$DT->pageLimit = 'off';
		$arr_column    = $DT->get_column();
		$res           = $DT->MakeTable();
		$arr_search    = $DT->GetSearchs();
		$rows          = $DT->rows;

		$ext = wp_unslash( $_REQUEST['ftype'] );
		if ( 'csv' == $ext ) {
			$table_h = '';
			$table_f = '';
			$tr_h    = '';
			$tr_f    = '';
			$th_h1   = '"';
			$th_h    = ',"';
			$th_f    = '"';
			$td_h1   = '"';
			$td_h    = ',"';
			$td_f    = '"';
			$sp      = ':';
			$nb      = ' ';
			$lf      = "\n";
		} else {
			exit();
		}

		$csod_meta = usces_has_custom_field_meta( 'order' );
		$cscs_meta = usces_has_custom_field_meta( 'customer' );
		$csde_meta = usces_has_custom_field_meta( 'delivery' );
		$applyform = usces_get_apply_addressform( $usces->options['system']['addressform'] );
		$usces_tax = Welcart_Tax::get_instance();

		// ==========================================================================

		$usces_opt_order = get_option( 'usces_opt_order' );
		if ( ! is_array( $usces_opt_order ) ) {
			$usces_opt_order = array();
		}
		$usces_opt_order['ftype_pro'] = $ext;
		$chk_pro                      = array();
		$chk_pro['ID']                = ( isset( $_REQUEST['check']['ID'] ) ) ? 1 : 0;
		$chk_pro['deco_id']           = ( isset( $_REQUEST['check']['deco_id'] ) ) ? 1 : 0;
		$chk_pro['date']              = ( isset( $_REQUEST['check']['date'] ) ) ? 1 : 0;
		$chk_pro['mem_id']            = ( isset( $_REQUEST['check']['mem_id'] ) ) ? 1 : 0;
		$chk_pro['email']             = ( isset( $_REQUEST['check']['email'] ) ) ? 1 : 0;
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name                 = $entry['name'];
					$cscs_key             = 'cscs_' . $key;
					$chk_pro[ $cscs_key ] = ( isset( $_REQUEST['check'][ $cscs_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_pro['name'] = ( isset( $_REQUEST['check']['name'] ) ) ? 1 : 0;
		if ( 'JP' == $applyform ) {
			$chk_pro['kana'] = ( isset( $_REQUEST['check']['kana'] ) ) ? 1 : 0;
		}
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$cscs_key             = 'cscs_' . $key;
					$chk_pro[ $cscs_key ] = ( isset( $_REQUEST['check'][ $cscs_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_pro['zip']      = ( isset( $_REQUEST['check']['zip'] ) ) ? 1 : 0;
		$chk_pro['country']  = ( isset( $_REQUEST['check']['country'] ) ) ? 1 : 0;
		$chk_pro['pref']     = ( isset( $_REQUEST['check']['pref'] ) ) ? 1 : 0;
		$chk_pro['address1'] = ( isset( $_REQUEST['check']['address1'] ) ) ? 1 : 0;
		$chk_pro['address2'] = ( isset( $_REQUEST['check']['address2'] ) ) ? 1 : 0;
		$chk_pro['address3'] = ( isset( $_REQUEST['check']['address3'] ) ) ? 1 : 0;
		$chk_pro['tel']      = ( isset( $_REQUEST['check']['tel'] ) ) ? 1 : 0;
		$chk_pro['fax']      = ( isset( $_REQUEST['check']['fax'] ) ) ? 1 : 0;
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$cscs_key             = 'cscs_' . $key;
					$chk_pro[ $cscs_key ] = ( isset( $_REQUEST['check'][ $cscs_key ] ) ) ? 1 : 0;
				}
			}
		}
		// --------------------------------------------------------------------------
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name                 = $entry['name'];
					$csde_key             = 'csde_' . $key;
					$chk_pro[ $csde_key ] = ( isset( $_REQUEST['check'][ $csde_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_pro['delivery_name'] = ( isset( $_REQUEST['check']['delivery_name'] ) ) ? 1 : 0;
		if ( 'JP' == $applyform ) {
			$chk_pro['delivery_kana'] = ( isset( $_REQUEST['check']['delivery_kana'] ) ) ? 1 : 0;
		}
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$csde_key             = 'csde_' . $key;
					$chk_pro[ $csde_key ] = ( isset( $_REQUEST['check'][ $csde_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_pro['delivery_zip']      = ( isset( $_REQUEST['check']['delivery_zip'] ) ) ? 1 : 0;
		$chk_pro['delivery_country']  = ( isset( $_REQUEST['check']['delivery_country'] ) ) ? 1 : 0;
		$chk_pro['delivery_pref']     = ( isset( $_REQUEST['check']['delivery_pref'] ) ) ? 1 : 0;
		$chk_pro['delivery_address1'] = ( isset( $_REQUEST['check']['delivery_address1'] ) ) ? 1 : 0;
		$chk_pro['delivery_address2'] = ( isset( $_REQUEST['check']['delivery_address2'] ) ) ? 1 : 0;
		$chk_pro['delivery_address3'] = ( isset( $_REQUEST['check']['delivery_address3'] ) ) ? 1 : 0;
		$chk_pro['delivery_tel']      = ( isset( $_REQUEST['check']['delivery_tel'] ) ) ? 1 : 0;
		$chk_pro['delivery_fax']      = ( isset( $_REQUEST['check']['delivery_fax'] ) ) ? 1 : 0;
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$csde_key             = 'csde_' . $key;
					$chk_pro[ $csde_key ] = ( isset( $_REQUEST['check'][ $csde_key ] ) ) ? 1 : 0;
				}
			}
		}
		// --------------------------------------------------------------------------
		$chk_pro['shipping_date']     = ( isset( $_REQUEST['check']['shipping_date'] ) ) ? 1 : 0;
		$chk_pro['peyment_method']    = ( isset( $_REQUEST['check']['peyment_method'] ) ) ? 1 : 0;
		$chk_pro['wc_trans_id']       = ( isset( $_REQUEST['check']['wc_trans_id'] ) ) ? 1 : 0;
		$chk_pro['delivery_method']   = ( isset( $_REQUEST['check']['delivery_method'] ) ) ? 1 : 0;
		$chk_pro['delivery_date']     = ( isset( $_REQUEST['check']['delivery_date'] ) ) ? 1 : 0;
		$chk_pro['delivery_time']     = ( isset( $_REQUEST['check']['delivery_time'] ) ) ? 1 : 0;
		$chk_pro['delidue_date']      = ( isset( $_REQUEST['check']['delidue_date'] ) ) ? 1 : 0;
		$chk_pro['status']            = ( isset( $_REQUEST['check']['status'] ) ) ? 1 : 0;
		$chk_pro['tracking_number']   = ( isset( $_REQUEST['check']['tracking_number'] ) ) ? 1 : 0;
		$chk_pro['total_amount']      = ( isset( $_REQUEST['check']['total_amount'] ) ) ? 1 : 0;
		$chk_pro['item_total_amount'] = ( isset( $_REQUEST['check']['item_total_amount'] ) ) ? 1 : 0;
		if ( usces_is_member_system() && usces_is_member_system_point() ) {
			$chk_pro['getpoint']  = ( isset( $_REQUEST['check']['getpoint'] ) ) ? 1 : 0;
			$chk_pro['usedpoint'] = ( isset( $_REQUEST['check']['usedpoint'] ) ) ? 1 : 0;
		}
		$chk_pro['discount']        = ( isset( $_REQUEST['check']['discount'] ) ) ? 1 : 0;
		$chk_pro['shipping_charge'] = ( isset( $_REQUEST['check']['shipping_charge'] ) ) ? 1 : 0;
		$chk_pro['cod_fee']         = ( isset( $_REQUEST['check']['cod_fee'] ) ) ? 1 : 0;
		if ( usces_is_tax_display() ) {
			$chk_pro['tax'] = ( isset( $_REQUEST['check']['tax'] ) ) ? 1 : 0;
			if ( usces_is_reduced_taxrate() ) {
				$chk_pro['subtotal_standard'] = ( isset( $_REQUEST['check']['subtotal_standard'] ) ) ? 1 : 0;
				$chk_pro['tax_standard']      = ( isset( $_REQUEST['check']['tax_standard'] ) ) ? 1 : 0;
				$chk_pro['subtotal_reduced']  = ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) ? 1 : 0;
				$chk_pro['tax_reduced']       = ( isset( $_REQUEST['check']['tax_reduced'] ) ) ? 1 : 0;
			}
		}
		$chk_pro['note'] = ( isset( $_REQUEST['check']['note'] ) ) ? 1 : 0;
		if ( ! empty( $csod_meta ) ) {
			foreach ( $csod_meta as $key => $entry ) {
				$name                 = $entry['name'];
				$csod_key             = 'csod_' . $key;
				$chk_pro[ $csod_key ] = ( isset( $_REQUEST['check'][ $csod_key ] ) ) ? 1 : 0;
			}
		}
		$usces_opt_order['chk_pro'] = apply_filters( 'usces_filter_chk_pro', $chk_pro );
		update_option( 'usces_opt_order', $usces_opt_order );

		// ==========================================================================

		if ( isset( $_REQUEST['check']['status'] ) ) {
			$usces_management_status        = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
			$usces_management_status['new'] = __( 'new order', 'usces' );
		}
		$chk_pro['item_code']       = ( isset( $_REQUEST['check']['item_code'] ) ) ? 1 : 0;
		$chk_pro['sku_code']        = ( isset( $_REQUEST['check']['sku_code'] ) ) ? 1 : 0;
		$chk_pro['item_name']       = ( isset( $_REQUEST['check']['item_name'] ) ) ? 1 : 0;
		$chk_pro['sku_name']        = ( isset( $_REQUEST['check']['sku_name'] ) ) ? 1 : 0;
		$chk_pro['options']         = ( isset( $_REQUEST['check']['options'] ) ) ? 1 : 0;
		$chk_pro['quantity']        = ( isset( $_REQUEST['check']['quantity'] ) ) ? 1 : 0;
		$chk_pro['price']           = ( isset( $_REQUEST['check']['price'] ) ) ? 1 : 0;
		$chk_pro['unit']            = ( isset( $_REQUEST['check']['unit'] ) ) ? 1 : 0;
		$chk_pro['admin_memo']      = ( isset( $_REQUEST['check']['admin_memo'] ) ) ? 1 : 0;
		$usces_opt_order['chk_pro'] = apply_filters( 'usces_filter_chk_pro', $chk_pro );
		update_option( 'usces_opt_order', $usces_opt_order );

		// ==========================================================================

		$line     = $table_h;
		$line    .= $tr_h;
		$line_col = '';
		if ( isset( $_REQUEST['check']['ID'] ) ) {
			$line_col .= $th_h1 . __( 'ID', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['deco_id'] ) ) {
			$line_col .= $th_h . __( 'Order number', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['date'] ) ) {
			$line_col .= $th_h . __( 'order date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['mem_id'] ) ) {
			$line_col .= $th_h . __( 'membership number', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['email'] ) ) {
			$line_col .= $th_h . __( 'e-mail', 'usces' ) . $th_f;
		}
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name     = $entry['name'];
					$cscs_key = 'cscs_' . $key;
					if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		if ( isset( $_REQUEST['check']['name'] ) ) {
			$line_col .= $th_h . __( 'name', 'usces' ) . $th_f;
		}
		if ( 'JP' == $applyform ) {
			if ( isset( $_REQUEST['check']['kana'] ) ) {
				$line_col .= $th_h . __( 'furigana', 'usces' ) . $th_f;
			}
		}
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$cscs_key = 'cscs_' . $key;
					if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}

		switch ( $applyform ) {
			case 'JP':
				if ( isset( $_REQUEST['check']['zip'] ) ) {
					$line_col .= $th_h . __( 'Zip/Postal Code', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['country'] ) ) {
					$line_col .= $th_h . __( 'Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['pref'] ) ) {
					$line_col .= $th_h . __( 'Province', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address1'] ) ) {
					$line_col .= $th_h . __( 'city', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address2'] ) ) {
					$line_col .= $th_h . __( 'numbers', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address3'] ) ) {
					$line_col .= $th_h . __( 'building name', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tel'] ) ) {
					$line_col .= $th_h . __( 'Phone number', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['fax'] ) ) {
					$line_col .= $th_h . __( 'FAX number', 'usces' ) . $th_f;
				}
				break;
			case 'US':
			default:
				if ( isset( $_REQUEST['check']['address2'] ) ) {
					$line_col .= $th_h . __( 'Address Line1', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address3'] ) ) {
					$line_col .= $th_h . __( 'Address Line2', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address1'] ) ) {
					$line_col .= $th_h . __( 'city', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['pref'] ) ) {
					$line_col .= $th_h . __( 'State', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['country'] ) ) {
					$line_col .= $th_h . __( 'Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['zip'] ) ) {
					$line_col .= $th_h . __( 'Zip', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tel'] ) ) {
					$line_col .= $th_h . __( 'Phone number', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['fax'] ) ) {
					$line_col .= $th_h . __( 'FAX number', 'usces' ) . $th_f;
				}
				break;
		}

		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$cscs_key = 'cscs_' . $key;
					if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		$line_col .= apply_filters( 'usces_filter_chk_pro_label_customer', null, $usces_opt_order, $rows );
		// --------------------------------------------------------------------------
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name     = $entry['name'];
					$csde_key = 'csde_' . $key;
					if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		if ( isset( $_REQUEST['check']['delivery_name'] ) ) {
			$line_col .= $th_h . __( 'Shipping Name', 'usces' ) . $th_f;
		}
		if ( 'JP' == $applyform ) {
			if ( isset( $_REQUEST['check']['delivery_kana'] ) ) {
				$line_col .= $th_h . __( 'Shipping Furigana', 'usces' ) . $th_f;
			}
		}
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$csde_key = 'csde_' . $key;
					if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}

		switch ( $applyform ) {
			case 'JP':
				if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
					$line_col .= $th_h . __( 'Shipping Zip', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
					$line_col .= $th_h . __( 'Shipping Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
					$line_col .= $th_h . __( 'Shipping State', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
					$line_col .= $th_h . __( 'Shipping City', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address1', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address2', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
					$line_col .= $th_h . __( 'Shipping Phone', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
					$line_col .= $th_h . __( 'Shipping FAX', 'usces' ) . $th_f;
				}
				break;
			case 'US':
			default:
				if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address1', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address2', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
					$line_col .= $th_h . __( 'Shipping City', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
					$line_col .= $th_h . __( 'Shipping State', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
					$line_col .= $th_h . __( 'Shipping Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
					$line_col .= $th_h . __( 'Shipping Zip', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
					$line_col .= $th_h . __( 'Shipping Phone', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
					$line_col .= $th_h . __( 'Shipping FAX', 'usces' ) . $th_f;
				}
				break;
		}

		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$csde_key = 'csde_' . $key;
					if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		$line_col .= apply_filters( 'usces_filter_chk_pro_label_delivery', null, $usces_opt_order, $rows );
		// --------------------------------------------------------------------------
		if ( isset( $_REQUEST['check']['shipping_date'] ) ) {
			$line_col .= $th_h . __( 'shpping date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['peyment_method'] ) ) {
			$line_col .= $th_h . __( 'payment method', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['wc_trans_id'] ) ) {
			$line_col .= $th_h . __( 'Transaction ID', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delivery_method'] ) ) {
			$line_col .= $th_h . __( 'shipping option', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delivery_date'] ) ) {
			$line_col .= $th_h . __( 'Delivery date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delivery_time'] ) ) {
			$line_col .= $th_h . __( 'delivery time', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delidue_date'] ) ) {
			$line_col .= $th_h . __( 'Shipping date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['status'] ) ) {
			$line_col .= $th_h . __( 'Status', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['tracking_number'] ) ) {
			$line_col .= $th_h . __( 'Tracking number', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['total_amount'] ) ) {
			$line_col .= $th_h . __( 'Total Amount', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['item_total_amount'] ) ) {
			$line_col .= $th_h . __( 'total items', 'usces' ) . $th_f;
		}
		if ( usces_is_member_system() && usces_is_member_system_point() ) {
			if ( isset( $_REQUEST['check']['getpoint'] ) ) {
				$line_col .= $th_h . __( 'granted points', 'usces' ) . $th_f;
			}
			if ( isset( $_REQUEST['check']['usedpoint'] ) ) {
				$line_col .= $th_h . __( 'Used points', 'usces' ) . $th_f;
			}
		}
		if ( isset( $_REQUEST['check']['discount'] ) ) {
			$line_col .= $th_h . __( 'Discount', 'usces' ) . $th_f;
		}
		if ( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
			if ( isset( $_REQUEST['check']['tax'] ) ) {
				$line_col .= $th_h . usces_tax_label( array(), 'return' ) . $th_f;
			}
			if ( usces_is_reduced_taxrate() ) {
				if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
			}
		}
		if ( isset( $_REQUEST['check']['shipping_charge'] ) ) {
			$line_col .= $th_h . __( 'Shipping', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['cod_fee'] ) ) {
			$line_col .= $th_h . apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ) . $th_f;
		}
		if ( usces_is_tax_display() && 'products' != usces_get_tax_target() ) {
			if ( isset( $_REQUEST['check']['tax'] ) ) {
				$line_col .= $th_h . usces_tax_label( array(), 'return' ) . $th_f;
			}
			if ( usces_is_reduced_taxrate() ) {
				if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
			}
		}
		if ( isset( $_REQUEST['check']['note'] ) ) {
			$line_col .= $th_h . __( 'Notes', 'usces' ) . $th_f;
		}
		if ( ! empty( $csod_meta ) ) {
			foreach ( $csod_meta as $key => $entry ) {
				$name     = $entry['name'];
				$csod_key = 'csod_' . $key;
				if ( isset( $_REQUEST['check'][ $csod_key ] ) ) {
					$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
				}
			}
		}
		$line_col .= apply_filters( 'usces_filter_chk_pro_label_order', null, $usces_opt_order, $rows );
		if ( isset( $_REQUEST['check']['item_code'] ) ) {
			$line_col .= $th_h . __( 'item code', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['sku_code'] ) ) {
			$line_col .= $th_h . __( 'SKU code', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['item_name'] ) ) {
			$line_col .= $th_h . __( 'item name', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['sku_name'] ) ) {
			$line_col .= $th_h . __( 'SKU display name ', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['options'] ) ) {
			$line_col .= apply_filters( 'usces_filter_csvpro_itemopt_label', ( $th_h . __( 'options for items', 'usces' ) . $th_f ), $usces_opt_order, $rows );
		}
		if ( isset( $_REQUEST['check']['quantity'] ) ) {
			$line_col .= $th_h . __( 'Quantity', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['price'] ) ) {
			$line_col .= $th_h . __( 'Unit price', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['unit'] ) ) {
			$line_col .= $th_h . __( 'unit', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['admin_memo'] ) ) {
			$line_col .= $th_h . __( 'Administrator Note', 'usces' ) . $th_f;
		}
		$line_col = ltrim( $line_col, ',' );
		$line    .= $line_col;
		$line    .= apply_filters( 'usces_filter_chk_pro_label_detail', null, $usces_opt_order, $rows, $line_col );
		$line    .= $tr_f . $lf;

		// ==========================================================================

		foreach ( (array) $rows as $data ) {
			$order_id        = $data['ID'];
			$deli            = unserialize( $data['deli_name'] );
			$cart            = usces_get_ordercartdata( $order_id );
			$cart_count      = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
			$reduced_taxrate = usces_is_reduced_taxrate( $order_id );

			if ( usces_is_tax_display() && $reduced_taxrate ) {
				$condition = usces_get_order_condition( $order_id );
				$materials = array(
					'total_items_price' => $data['item_total_price'],
					'discount'          => $data['discount'],
					'shipping_charge'   => $data['shipping_charge'],
					'cod_fee'           => $data['cod_fee'],
					'use_point'         => $data['usedpoint'],
					'carts'             => $cart,
					'condition'         => $condition,
					'order_id'          => $order_id,
				);
				$usces_tax->get_order_tax( $materials );
			}

			for ( $i = 0; $i < $cart_count; $i++ ) {
				$cart_row = $cart[ $i ];

				$line    .= $tr_h;
				$line_row = '';
				if ( isset( $_REQUEST['check']['ID'] ) ) {
					$line_row .= $td_h1 . $order_id . $td_f;
				}
				if ( isset( $_REQUEST['check']['deco_id'] ) ) {
					$line_row .= $td_h . usces_get_deco_order_id( $order_id ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['date'] ) ) {
					$line_row .= $td_h . $data['order_date'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['mem_id'] ) ) {
					$line_row .= $td_h . $data['mem_id'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['email'] ) ) {
					$line_row .= $td_h . usces_entity_decode( $data['email'], $ext ) . $td_f;
				}
				if ( ! empty( $cscs_meta ) ) {
					foreach ( $cscs_meta as $key => $entry ) {
						if ( 'name_pre' == $entry['position'] ) {
							$name     = $entry['name'];
							$cscs_key = 'cscs_' . $key;
							if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
								$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
								if ( empty( $value ) ) {
									$value = '';
								} elseif ( is_array( $value ) ) {
									$concatval = '';
									$c         = '';
									foreach ( $value as $v ) {
										$concatval .= $c . $v;
										$c          = ' ';
									}
									$value = $concatval;
								}
								$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
							}
						}
					}
				}

				switch ( $applyform ) {
					case 'JP':
						if ( isset( $_REQUEST['check']['name'] ) ) {
							$line_row .= $td_h . usces_entity_decode( $data['name1'] . ' ' . $data['name2'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['kana'] ) ) {
							$line_row .= $td_h . usces_entity_decode( $data['name3'] . ' ' . $data['name4'], $ext ) . $td_f;
						}
						break;
					case 'US':
					default:
						if ( isset( $_REQUEST['check']['name'] ) ) {
							$line_row .= $td_h . usces_entity_decode( $data['name2'] . ' ' . $data['name1'], $ext ) . $td_f;
						}
						break;
				}

				if ( ! empty( $cscs_meta ) ) {
					foreach ( $cscs_meta as $key => $entry ) {
						if ( 'name_after' == $entry['position'] ) {
							$name     = $entry['name'];
							$cscs_key = 'cscs_' . $key;
							if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
								$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
								if ( empty( $value ) ) {
									$value = '';
								} elseif ( is_array( $value ) ) {
									$concatval = '';
									$c         = '';
									foreach ( $value as $v ) {
										$concatval .= $c . $v;
										$c          = ' ';
									}
									$value = $concatval;
								}
								$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
							}
						}
					}
				}

				$address_info = '';
				switch ( $applyform ) {
					case 'JP':
						if ( isset( $_REQUEST['check']['zip'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['zipcode'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['country'] ) ) {
							$address_info .= $td_h . ( isset( $usces_settings['country'][ $data['country'] ] ) ? $usces_settings['country'][ $data['country'] ] : '' ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['pref'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['pref'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['address1'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['address1'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['address2'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['address2'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['address3'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['address3'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['tel'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['tel'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['fax'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['fax'], $ext ) . $td_f;
						}
						break;
					case 'US':
					default:
						if ( isset( $_REQUEST['check']['address2'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['address2'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['address3'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['address3'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['address1'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['address1'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['pref'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['pref'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['country'] ) ) {
							$address_info .= $td_h . ( isset( $usces_settings['country'][ $data['country'] ] ) ? $usces_settings['country'][ $data['country'] ] : '' ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['zip'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['zipcode'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['tel'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['tel'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['fax'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $data['fax'], $ext ) . $td_f;
						}
						break;
				}
				$address_info_args = compact( 'td_h', 'td_f', 'ext', 'order_id', 'applyform' );
				$line_row         .= apply_filters( 'usces_filter_pro_csv_address_info', $address_info, $data, $address_info_args );

				if ( ! empty( $cscs_meta ) ) {
					foreach ( $cscs_meta as $key => $entry ) {
						if ( 'fax_after' == $entry['position'] ) {
							$name     = $entry['name'];
							$cscs_key = 'cscs_' . $key;
							if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
								$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
								if ( empty( $value ) ) {
									$value = '';
								} elseif ( is_array( $value ) ) {
									$concatval = '';
									$c         = '';
									foreach ( $value as $v ) {
										$concatval .= $c . $v;
										$c          = ' ';
									}
									$value = $concatval;
								}
								$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
							}
						}
					}
				}
				$line_row .= apply_filters( 'usces_filter_chk_pro_data_customer', null, $usces_opt_order, $order_id, $data );
				// ----------------------------------------------------------------------
				if ( ! empty( $csde_meta ) ) {
					foreach ( $csde_meta as $key => $entry ) {
						if ( 'name_pre' == $entry['position'] ) {
							$name     = $entry['name'];
							$csde_key = 'csde_' . $key;
							if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
								$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
								if ( empty( $value ) ) {
									$value = '';
								} elseif ( is_array( $value ) ) {
									$concatval = '';
									$c         = '';
									foreach ( $value as $v ) {
										$concatval .= $c . $v;
										$c          = ' ';
									}
									$value = $concatval;
								}
								$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
							}
						}
					}
				}

				switch ( $applyform ) {
					case 'JP':
						if ( isset( $_REQUEST['check']['delivery_name'] ) ) {
							$line_row .= $td_h . usces_entity_decode( $deli['name1'] . ' ' . $deli['name2'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_kana'] ) ) {
							$line_row .= $td_h . usces_entity_decode( $deli['name3'] . ' ' . $deli['name4'], $ext ) . $td_f;
						}
						break;
					case 'US':
					default:
						if ( isset( $_REQUEST['check']['delivery_name'] ) ) {
							$line_row .= $td_h . usces_entity_decode( $deli['name2'] . ' ' . $deli['name1'], $ext ) . $td_f;
						}
						break;
				}

				if ( ! empty( $csde_meta ) ) {
					foreach ( $csde_meta as $key => $entry ) {
						if ( 'name_after' == $entry['position'] ) {
							$name     = $entry['name'] . '</td>';
							$csde_key = 'csde_' . $key;
							if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
								$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
								if ( empty( $value ) ) {
									$value = '';
								} elseif ( is_array( $value ) ) {
									$concatval = '';
									$c         = '';
									foreach ( $value as $v ) {
										$concatval .= $c . $v;
										$c          = ' ';
									}
									$value = $concatval;
								}
								$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
							}
						}
					}
				}

				$address_info = '';
				switch ( $applyform ) {
					case 'JP':
						if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['zipcode'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
							$address_info .= $td_h . ( isset( $usces_settings['country'][ $deli['country'] ] ) ? $usces_settings['country'][ $deli['country'] ] : '' ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['pref'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['address1'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['address2'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['address3'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['tel'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['fax'], $ext ) . $td_f;
						}
						break;
					case 'US':
					default:
						if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['address2'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['address3'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['address1'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['pref'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
							$address_info .= $td_h . ( isset( $usces_settings['country'][ $deli['country'] ] ) ? $usces_settings['country'][ $deli['country'] ] : '' ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['zipcode'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['tel'], $ext ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
							$address_info .= $td_h . usces_entity_decode( $deli['fax'], $ext ) . $td_f;
						}
						break;
				}
				$line_row .= apply_filters( 'usces_filter_pro_csv_delivery_address_info', $address_info, $deli, $address_info_args );

				if ( ! empty( $csde_meta ) ) {
					foreach ( $csde_meta as $key => $entry ) {
						if ( 'fax_after' == $entry['position'] ) {
							$name     = $entry['name'];
							$csde_key = 'csde_' . $key;
							if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
								$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
								if ( empty( $value ) ) {
									$value = '';
								} elseif ( is_array( $value ) ) {
									$concatval = '';
									$c         = '';
									foreach ( $value as $v ) {
										$concatval .= $c . $v;
										$c          = ' ';
									}
									$value = $concatval;
								}
								$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
							}
						}
					}
				}
				$line_row .= apply_filters( 'usces_filter_chk_pro_data_delivery', null, $usces_opt_order, $order_id, $deli );
				// ----------------------------------------------------------------------
				if ( isset( $_REQUEST['check']['shipping_date'] ) ) {
					$line_row .= $td_h . $data['order_modified'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['peyment_method'] ) ) {
					$line_row .= $td_h . $data['payment_name'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['wc_trans_id'] ) ) {
					$line_row .= $td_h . $data['wc_trans_id'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['delivery_method'] ) ) {
					$delivery_method = '';
					if ( strtoupper( $data['deli_method'] ) == '#NONE#' ) {
						$delivery_method = __( 'No preference', 'usces' );
					} else {
						foreach ( (array) $usces->options['delivery_method'] as $dkey => $delivery ) {
							if ( $delivery['id'] == $data['deli_method'] ) {
								$delivery_method = $delivery['name'];
								break;
							}
						}
					}
					$line_row .= $td_h . $delivery_method . $td_f;
				}
				if ( isset( $_REQUEST['check']['delivery_date'] ) ) {
					$line_row .= $td_h . $data['deli_date'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['delivery_time'] ) ) {
					$line_row .= $td_h . $data['deli_time'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['delidue_date'] ) ) {
					$order_delidue_date = ( strtoupper( $data['delidue_date'] ) == '#NONE#' ) ? '' : $data['delidue_date'];
					$line_row          .= $td_h . $order_delidue_date . $td_f;
				}
				if ( isset( $_REQUEST['check']['status'] ) ) {
					$order_status = explode( ',', $data['process_status'] );
					$status       = '';
					foreach ( (array) $order_status as $os ) {
						if ( isset( $usces_management_status[ $os ] ) ) {
							$status .= $usces_management_status[ $os ] . $sp;
						}
					}
					$line_row .= $td_h . trim( $status, $sp ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['tracking_number'] ) ) {
					$line_row .= $td_h . $data['tracking_number'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['total_amount'] ) ) {
					$line_row .= $td_h . usces_crform( $data['total_price'], false, false, 'return', false ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['item_total_amount'] ) ) {
					$line_row .= $td_h . usces_crform( $data['item_total_price'], false, false, 'return', false ) . $td_f;
				}
				if ( usces_is_member_system() && usces_is_member_system_point() ) {
					if ( isset( $_REQUEST['check']['getpoint'] ) ) {
						$line_row .= $td_h . $data['getpoint'] . $td_f;
					}
					if ( isset( $_REQUEST['check']['usedpoint'] ) ) {
						$line_row .= $td_h . $data['usedpoint'] . $td_f;
					}
				}
				if ( isset( $_REQUEST['check']['discount'] ) ) {
					$line_row .= $td_h . usces_crform( $data['discount'], false, false, 'return', false ) . $td_f;
				}
				if ( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
					if ( $reduced_taxrate ) {
						if ( isset( $_REQUEST['check']['tax'] ) ) {
							if ( 'include' == usces_get_tax_mode() ) {
								$tax = $usces_tax->tax;
							} else {
								$tax = $data['tax'];
							}
							$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->subtotal_standard, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->tax_standard, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->subtotal_reduced, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->tax_reduced, false, false, 'return', false ) . $td_f;
						}
					} else {
						if ( isset( $_REQUEST['check']['tax'] ) ) {
							if ( 'include' == usces_get_tax_mode() ) {
								$materials = array(
									'total_items_price' => $data['item_total_price'],
									'discount'          => $data['discount'],
									'shipping_charge'   => $data['shipping_charge'],
									'cod_fee'           => $data['cod_fee'],
									'use_point'         => $data['usedpoint'],
									'order_id'          => $data['ID'],
								);
								$tax       = usces_internal_tax( $materials, 'return' );
							} else {
								$tax = $data['tax'];
							}
							$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
					}
				}
				if ( isset( $_REQUEST['check']['shipping_charge'] ) ) {
					$line_row .= $td_h . usces_crform( $data['shipping_charge'], false, false, 'return', false ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['cod_fee'] ) ) {
					$line_row .= $td_h . usces_crform( $data['cod_fee'], false, false, 'return', false ) . $td_f;
				}
				if ( usces_is_tax_display() && 'products' != usces_get_tax_target() ) {
					if ( $reduced_taxrate ) {
						if ( isset( $_REQUEST['check']['tax'] ) ) {
							if ( 'include' == usces_get_tax_mode() ) {
								$tax = $usces_tax->tax;
							} else {
								$tax = $data['tax'];
							}
							$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->subtotal_standard, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->tax_standard, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->subtotal_reduced, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
							$line_row .= $td_h . usces_crform( $usces_tax->tax_reduced, false, false, 'return', false ) . $td_f;
						}
					} else {
						if ( isset( $_REQUEST['check']['tax'] ) ) {
							if ( 'include' == usces_get_tax_mode() ) {
								$materials = array(
									'total_items_price' => $data['item_total_price'],
									'discount'          => $data['discount'],
									'shipping_charge'   => $data['shipping_charge'],
									'cod_fee'           => $data['cod_fee'],
									'use_point'         => $data['usedpoint'],
									'order_id'          => $data['ID'],
								);
								$tax       = usces_internal_tax( $materials, 'return' );
							} else {
								$tax = $data['tax'];
							}
							$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
						if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
						if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
							$line_row .= $td_h . '' . $td_f;
						}
					}
				}
				if ( isset( $_REQUEST['check']['note'] ) ) {
					$line_row .= $td_h . usces_entity_decode( $data['note'], $ext ) . $td_f;
				}
				if ( ! empty( $csod_meta ) ) {
					foreach ( $csod_meta as $key => $entry ) {
						$name     = $entry['name'];
						$csod_key = 'csod_' . $key;
						if ( isset( $_REQUEST['check'][ $csod_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $csod_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
				$line_row .= apply_filters( 'usces_filter_chk_pro_data_order', null, $usces_opt_order, $order_id, $data, $cart_row );

				if ( isset( $_REQUEST['check']['item_code'] ) ) {
					$line_row .= $td_h . $cart_row['item_code'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['sku_code'] ) ) {
					$line_row .= $td_h . $cart_row['sku_code'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['item_name'] ) ) {
					$line_row .= $td_h . usces_entity_decode( $cart_row['item_name'], $ext ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['sku_name'] ) ) {
					$line_row .= $td_h . usces_entity_decode( $cart_row['sku_name'], $ext ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['options'] ) ) {
					$options = usces_get_ordercart_meta( 'option', $cart_row['cart_id'] );
					$optstr  = '';
					if ( $options && is_array( $options ) && count( $options ) > 0 ) {
						foreach ( (array) $options as $key => $value ) {
							if ( ! empty( $value['meta_key'] ) ) {
								$meta_value = maybe_unserialize( $value['meta_value'] );
								if ( is_array( $meta_value ) ) {
									$meta_vals = '';
									foreach ( $meta_value as $array_val ) {
										$meta_vals .= $nb . urldecode( $array_val );
									}
									$optstr .= usces_entity_decode( urldecode( $value['meta_key'] ) . $sp . $meta_vals, $ext ) . $nb;
								} else {
									$optstr .= usces_entity_decode( urldecode( $value['meta_key'] ) . $sp . urldecode( $value['meta_value'] ), $ext ) . $nb;
								}
							}
						}
					}
					$optstr    = apply_filters( 'usces_filter_csvpro_itemopt_value', $optstr, $options, $cart_row, $usces_opt_order, $order_id, $data );
					$line_row .= $td_h . $optstr . $td_f;
				}
				if ( isset( $_REQUEST['check']['quantity'] ) ) {
					$line_row .= $td_h . $cart_row['quantity'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['price'] ) ) {
					$line_row .= $td_h . usces_crform( $cart_row['price'], false, false, 'return', false ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['unit'] ) ) {
					$line_row .= $td_h . usces_entity_decode( $cart_row['unit'], $ext ) . $td_f;
				}
				if ( isset( $_REQUEST['check']['admin_memo'] ) ) {
					$line_row .= $td_h . usces_entity_decode( $data['admin_memo'], $ext ) . $td_f;
				}
				$line_row = preg_replace( "/\n,/", "\n", ltrim( $line_row, ',' ) );
				$line_row = apply_filters( 'usces_filter_chk_pro_data_detail_line_row', $line_row, $usces_opt_order, $data, $cart_row );
				$line    .= $line_row;
				$line    .= apply_filters( 'usces_filter_chk_pro_data_detail', null, $usces_opt_order, $data, $cart_row, $line_row );
				$line    .= $tr_f . $lf;
			}
		}
		$line .= $table_f;
		$line  = apply_filters( 'wc_filter_chk_pro_data_order', $line );

		// ==========================================================================

		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=usces_product_list.' . $ext );
		mb_http_output( 'pass' );
		print( mb_convert_encoding( $line, apply_filters( 'usces_filter_output_csv_encode', 'SJIS-win' ), 'UTF-8' ) );
		exit();
	}

	/**
	 * Order list download.
	 */
	public function download_order_list() {
		global $wpdb, $usces, $usces_settings;

		if ( ! usces_admin_user_can_download_list() ) {
			exit();
		}

		require_once USCES_PLUGIN_DIR . '/classes/orderList2.class.php';

		$all_column    = true;
		$DT            = new WlcOrderList( $all_column );
		$DT->pageLimit = 'off';
		$arr_column    = $DT->get_column();
		$res           = $DT->MakeTable();
		$arr_search    = $DT->GetSearchs();
		$rows          = $DT->rows;

		$ext = $_REQUEST['ftype'];
		if ( 'csv' == $ext ) {
			$table_h = '';
			$table_f = '';
			$tr_h    = '';
			$tr_f    = '';
			$th_h1   = '"';
			$th_h    = ',"';
			$th_f    = '"';
			$td_h1   = '"';
			$td_h    = ',"';
			$td_f    = '"';
			$sp      = ':';
			$lf      = "\n";
		} else {
			exit();
		}

		$csod_meta = usces_has_custom_field_meta( 'order' );
		$cscs_meta = usces_has_custom_field_meta( 'customer' );
		$csde_meta = usces_has_custom_field_meta( 'delivery' );
		$applyform = usces_get_apply_addressform( $usces->options['system']['addressform'] );
		$usces_tax = Welcart_Tax::get_instance();

		// ==========================================================================

		$usces_opt_order = get_option( 'usces_opt_order' );
		if ( ! is_array( $usces_opt_order ) ) {
			$usces_opt_order = array();
		}
		$usces_opt_order['ftype_ord'] = $ext;
		$chk_ord                      = array();
		$chk_ord['ID']                = ( isset( $_REQUEST['check']['ID'] ) ) ? 1 : 0;
		$chk_ord['deco_id']           = ( isset( $_REQUEST['check']['deco_id'] ) ) ? 1 : 0;
		$chk_ord['date']              = ( isset( $_REQUEST['check']['date'] ) ) ? 1 : 0;
		$chk_ord['mem_id']            = ( isset( $_REQUEST['check']['mem_id'] ) ) ? 1 : 0;
		$chk_ord['email']             = ( isset( $_REQUEST['check']['email'] ) ) ? 1 : 0;
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name                 = $entry['name'];
					$cscs_key             = 'cscs_' . $key;
					$chk_ord[ $cscs_key ] = ( isset( $_REQUEST['check'][ $cscs_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_ord['name'] = ( isset( $_REQUEST['check']['name'] ) ) ? 1 : 0;
		if ( 'JP' == $applyform ) {
			$chk_ord['kana'] = ( isset( $_REQUEST['check']['kana'] ) ) ? 1 : 0;
		}
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$cscs_key             = 'cscs_' . $key;
					$chk_ord[ $cscs_key ] = ( isset( $_REQUEST['check'][ $cscs_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_ord['zip']      = ( isset( $_REQUEST['check']['zip'] ) ) ? 1 : 0;
		$chk_ord['country']  = ( isset( $_REQUEST['check']['country'] ) ) ? 1 : 0;
		$chk_ord['pref']     = ( isset( $_REQUEST['check']['pref'] ) ) ? 1 : 0;
		$chk_ord['address1'] = ( isset( $_REQUEST['check']['address1'] ) ) ? 1 : 0;
		$chk_ord['address2'] = ( isset( $_REQUEST['check']['address2'] ) ) ? 1 : 0;
		$chk_ord['address3'] = ( isset( $_REQUEST['check']['address3'] ) ) ? 1 : 0;
		$chk_ord['tel']      = ( isset( $_REQUEST['check']['tel'] ) ) ? 1 : 0;
		$chk_ord['fax']      = ( isset( $_REQUEST['check']['fax'] ) ) ? 1 : 0;
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$cscs_key             = 'cscs_' . $key;
					$chk_ord[ $cscs_key ] = ( isset( $_REQUEST['check'][ $cscs_key ] ) ) ? 1 : 0;
				}
			}
		}
		// --------------------------------------------------------------------------
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name                 = $entry['name'];
					$csde_key             = 'csde_' . $key;
					$chk_ord[ $csde_key ] = ( isset( $_REQUEST['check'][ $csde_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_ord['delivery_name'] = ( isset( $_REQUEST['check']['delivery_name'] ) ) ? 1 : 0;
		if ( 'JP' == $applyform ) {
			$chk_ord['delivery_kana'] = ( isset( $_REQUEST['check']['delivery_kana'] ) ) ? 1 : 0;
		}
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$csde_key             = 'csde_' . $key;
					$chk_ord[ $csde_key ] = ( isset( $_REQUEST['check'][ $csde_key ] ) ) ? 1 : 0;
				}
			}
		}
		$chk_ord['delivery_zip']      = ( isset( $_REQUEST['check']['delivery_zip'] ) ) ? 1 : 0;
		$chk_ord['delivery_country']  = ( isset( $_REQUEST['check']['delivery_country'] ) ) ? 1 : 0;
		$chk_ord['delivery_pref']     = ( isset( $_REQUEST['check']['delivery_pref'] ) ) ? 1 : 0;
		$chk_ord['delivery_address1'] = ( isset( $_REQUEST['check']['delivery_address1'] ) ) ? 1 : 0;
		$chk_ord['delivery_address2'] = ( isset( $_REQUEST['check']['delivery_address2'] ) ) ? 1 : 0;
		$chk_ord['delivery_address3'] = ( isset( $_REQUEST['check']['delivery_address3'] ) ) ? 1 : 0;
		$chk_ord['delivery_tel']      = ( isset( $_REQUEST['check']['delivery_tel'] ) ) ? 1 : 0;
		$chk_ord['delivery_fax']      = ( isset( $_REQUEST['check']['delivery_fax'] ) ) ? 1 : 0;
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name                 = $entry['name'];
					$csde_key             = 'csde_' . $key;
					$chk_ord[ $csde_key ] = ( isset( $_REQUEST['check'][ $csde_key ] ) ) ? 1 : 0;
				}
			}
		}
		// --------------------------------------------------------------------------
		$chk_ord['shipping_date']     = ( isset( $_REQUEST['check']['shipping_date'] ) ) ? 1 : 0;
		$chk_ord['peyment_method']    = ( isset( $_REQUEST['check']['peyment_method'] ) ) ? 1 : 0;
		$chk_ord['wc_trans_id']       = ( isset( $_REQUEST['check']['wc_trans_id'] ) ) ? 1 : 0;
		$chk_ord['delivery_method']   = ( isset( $_REQUEST['check']['delivery_method'] ) ) ? 1 : 0;
		$chk_ord['delivery_date']     = ( isset( $_REQUEST['check']['delivery_date'] ) ) ? 1 : 0;
		$chk_ord['delivery_time']     = ( isset( $_REQUEST['check']['delivery_time'] ) ) ? 1 : 0;
		$chk_ord['delidue_date']      = ( isset( $_REQUEST['check']['delidue_date'] ) ) ? 1 : 0;
		$chk_ord['status']            = ( isset( $_REQUEST['check']['status'] ) ) ? 1 : 0;
		$chk_ord['tracking_number']   = ( isset( $_REQUEST['check']['tracking_number'] ) ) ? 1 : 0;
		$chk_ord['total_amount']      = ( isset( $_REQUEST['check']['total_amount'] ) ) ? 1 : 0;
		$chk_ord['item_total_amount'] = ( isset( $_REQUEST['check']['item_total_amount'] ) ) ? 1 : 0;
		if ( usces_is_member_system() && usces_is_member_system_point() ) {
			$chk_ord['getpoint']  = ( isset( $_REQUEST['check']['getpoint'] ) ) ? 1 : 0;
			$chk_ord['usedpoint'] = ( isset( $_REQUEST['check']['usedpoint'] ) ) ? 1 : 0;
		}
		$chk_ord['discount']        = ( isset( $_REQUEST['check']['discount'] ) ) ? 1 : 0;
		$chk_ord['shipping_charge'] = ( isset( $_REQUEST['check']['shipping_charge'] ) ) ? 1 : 0;
		$chk_ord['cod_fee']         = ( isset( $_REQUEST['check']['cod_fee'] ) ) ? 1 : 0;
		if ( usces_is_tax_display() ) {
			$chk_ord['tax'] = ( isset( $_REQUEST['check']['tax'] ) ) ? 1 : 0;
			if ( usces_is_reduced_taxrate() ) {
				$chk_ord['subtotal_standard'] = ( isset( $_REQUEST['check']['subtotal_standard'] ) ) ? 1 : 0;
				$chk_ord['tax_standard']      = ( isset( $_REQUEST['check']['tax_standard'] ) ) ? 1 : 0;
				$chk_ord['subtotal_reduced']  = ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) ? 1 : 0;
				$chk_ord['tax_reduced']       = ( isset( $_REQUEST['check']['tax_reduced'] ) ) ? 1 : 0;
			}
		}
		$chk_ord['note'] = ( isset( $_REQUEST['check']['note'] ) ) ? 1 : 0;
		if ( ! empty( $csod_meta ) ) {
			foreach ( $csod_meta as $key => $entry ) {
				$name                 = $entry['name'];
				$csod_key             = 'csod_' . $key;
				$chk_ord[ $csod_key ] = ( isset( $_REQUEST['check'][ $csod_key ] ) ) ? 1 : 0;
			}
		}
		$chk_ord['admin_memo']      = ( isset( $_REQUEST['check']['admin_memo'] ) ) ? 1 : 0;
		$usces_opt_order['chk_ord'] = apply_filters( 'usces_filter_chk_ord', $chk_ord );
		update_option( 'usces_opt_order', $usces_opt_order );

		// ==========================================================================

		if ( isset( $_REQUEST['check']['status'] ) ) {
			$usces_management_status        = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
			$usces_management_status['new'] = __( 'new order', 'usces' );
		}

		// ==========================================================================

		$line     = $table_h;
		$line    .= $tr_h;
		$line_col = '';
		if ( isset( $_REQUEST['check']['ID'] ) ) {
			$line_col .= $th_h1 . __( 'ID', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['deco_id'] ) ) {
			$line_col .= $th_h . __( 'Order number', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['date'] ) ) {
			$line_col .= $th_h . __( 'order date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['mem_id'] ) ) {
			$line_col .= $th_h . __( 'membership number', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['email'] ) ) {
			$line_col .= $th_h . __( 'e-mail', 'usces' ) . $th_f;
		}
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name     = $entry['name'];
					$cscs_key = 'cscs_' . $key;
					if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		if ( isset( $_REQUEST['check']['name'] ) ) {
			$line_col .= $th_h . __( 'name', 'usces' ) . $th_f;
		}
		if ( 'JP' == $applyform ) {
			if ( isset( $_REQUEST['check']['kana'] ) ) {
				$line_col .= $th_h . __( 'furigana', 'usces' ) . $th_f;
			}
		}
		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$cscs_key = 'cscs_' . $key;
					if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}

		switch ( $applyform ) {
			case 'JP':
				if ( isset( $_REQUEST['check']['zip'] ) ) {
					$line_col .= $th_h . __( 'Zip/Postal Code', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['country'] ) ) {
					$line_col .= $th_h . __( 'Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['pref'] ) ) {
					$line_col .= $th_h . __( 'Province', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address1'] ) ) {
					$line_col .= $th_h . __( 'city', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address2'] ) ) {
					$line_col .= $th_h . __( 'numbers', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address3'] ) ) {
					$line_col .= $th_h . __( 'building name', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tel'] ) ) {
					$line_col .= $th_h . __( 'Phone number', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['fax'] ) ) {
					$line_col .= $th_h . __( 'FAX number', 'usces' ) . $th_f;
				}
				break;
			case 'US':
			default:
				if ( isset( $_REQUEST['check']['address2'] ) ) {
					$line_col .= $th_h . __( 'Address Line1', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address3'] ) ) {
					$line_col .= $th_h . __( 'Address Line2', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['address1'] ) ) {
					$line_col .= $th_h . __( 'city', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['pref'] ) ) {
					$line_col .= $th_h . __( 'State', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['country'] ) ) {
					$line_col .= $th_h . __( 'Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['zip'] ) ) {
					$line_col .= $th_h . __( 'Zip', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tel'] ) ) {
					$line_col .= $th_h . __( 'Phone number', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['fax'] ) ) {
					$line_col .= $th_h . __( 'FAX number', 'usces' ) . $th_f;
				}
				break;
		}

		if ( ! empty( $cscs_meta ) ) {
			foreach ( $cscs_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$cscs_key = 'cscs_' . $key;
					if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		$line_col .= apply_filters( 'usces_filter_chk_ord_label_customer', null, $usces_opt_order, $rows );
		// --------------------------------------------------------------------------
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_pre' == $entry['position'] ) {
					$name     = $entry['name'];
					$csde_key = 'csde_' . $key;
					if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		if ( isset( $_REQUEST['check']['delivery_name'] ) ) {
			$line_col .= $th_h . __( 'Shipping Name', 'usces' ) . $th_f;
		}
		if ( 'JP' == $applyform ) {
			if ( isset( $_REQUEST['check']['delivery_kana'] ) ) {
				$line_col .= $th_h . __( 'Shipping Furigana', 'usces' ) . $th_f;
			}
		}
		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'name_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$csde_key = 'csde_' . $key;
					if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}

		switch ( $applyform ) {
			case 'JP':
				if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
					$line_col .= $th_h . __( 'Shipping Zip', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
					$line_col .= $th_h . __( 'Shipping Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
					$line_col .= $th_h . __( 'Shipping State', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
					$line_col .= $th_h . __( 'Shipping City', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address1', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address2', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
					$line_col .= $th_h . __( 'Shipping Phone', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
					$line_col .= $th_h . __( 'Shipping FAX', 'usces' ) . $th_f;
				}
				break;
			case 'US':
			default:
				if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address1', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
					$line_col .= $th_h . __( 'Shipping Address2', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
					$line_col .= $th_h . __( 'Shipping City', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
					$line_col .= $th_h . __( 'Shipping State', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
					$line_col .= $th_h . __( 'Shipping Country', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
					$line_col .= $th_h . __( 'Shipping Zip', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
					$line_col .= $th_h . __( 'Shipping Phone', 'usces' ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
					$line_col .= $th_h . __( 'Shipping FAX', 'usces' ) . $th_f;
				}
				break;
		}

		if ( ! empty( $csde_meta ) ) {
			foreach ( $csde_meta as $key => $entry ) {
				if ( 'fax_after' == $entry['position'] ) {
					$name     = $entry['name'];
					$csde_key = 'csde_' . $key;
					if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
						$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
					}
				}
			}
		}
		$line_col .= apply_filters( 'usces_filter_chk_ord_label_delivery', null, $usces_opt_order, $rows );
		// --------------------------------------------------------------------------
		if ( isset( $_REQUEST['check']['shipping_date'] ) ) {
			$line_col .= $th_h . __( 'shpping date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['peyment_method'] ) ) {
			$line_col .= $th_h . __( 'payment method', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['wc_trans_id'] ) ) {
			$line_col .= $th_h . __( 'Transaction ID', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delivery_method'] ) ) {
			$line_col .= $th_h . __( 'shipping option', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delivery_date'] ) ) {
			$line_col .= $th_h . __( 'Delivery date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delivery_time'] ) ) {
			$line_col .= $th_h . __( 'delivery time', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['delidue_date'] ) ) {
			$line_col .= $th_h . __( 'Shipping date', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['status'] ) ) {
			$line_col .= $th_h . __( 'Status', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['tracking_number'] ) ) {
			$line_col .= $th_h . __( 'Tracking number', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['total_amount'] ) ) {
			$line_col .= $th_h . __( 'Total Amount', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['item_total_amount'] ) ) {
			$line_col .= $th_h . __( 'total items', 'usces' ) . $th_f;
		}
		if ( usces_is_member_system() && usces_is_member_system_point() ) {
			if ( isset( $_REQUEST['check']['getpoint'] ) ) {
				$line_col .= $th_h . __( 'granted points', 'usces' ) . $th_f;
			}
			if ( isset( $_REQUEST['check']['usedpoint'] ) ) {
				$line_col .= $th_h . __( 'Used points', 'usces' ) . $th_f;
			}
		}
		if ( isset( $_REQUEST['check']['discount'] ) ) {
			$line_col .= $th_h . __( 'Discount', 'usces' ) . $th_f;
		}
		if ( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
			if ( isset( $_REQUEST['check']['tax'] ) ) {
				$line_col .= $th_h . usces_tax_label( array(), 'return' ) . $th_f;
			}
			if ( usces_is_reduced_taxrate() ) {
				if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
			}
		}
		if ( isset( $_REQUEST['check']['shipping_charge'] ) ) {
			$line_col .= $th_h . __( 'Shipping', 'usces' ) . $th_f;
		}
		if ( isset( $_REQUEST['check']['cod_fee'] ) ) {
			$line_col .= $th_h . apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ) . $th_f;
		}
		if ( usces_is_tax_display() && 'products' != usces_get_tax_target() ) {
			if ( isset( $_REQUEST['check']['tax'] ) ) {
				$line_col .= $th_h . usces_tax_label( array(), 'return' ) . $th_f;
			}
			if ( usces_is_reduced_taxrate() ) {
				if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
				if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
					$line_col .= $th_h . sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . $th_f;
				}
			}
		}
		if ( isset( $_REQUEST['check']['note'] ) ) {
			$line_col .= $th_h . __( 'Notes', 'usces' ) . $th_f;
		}
		if ( ! empty( $csod_meta ) ) {
			foreach ( $csod_meta as $key => $entry ) {
				$name     = $entry['name'];
				$csod_key = 'csod_' . $key;
				if ( isset( $_REQUEST['check'][ $csod_key ] ) ) {
					$line_col .= $th_h . usces_entity_decode( $name, $ext ) . $th_f;
				}
			}
		}
		if ( isset( $_REQUEST['check']['admin_memo'] ) ) {
			$line_col .= $th_h . __( 'Administrator Note', 'usces' ) . $th_f;
		}
		$line_col = ltrim( $line_col, ',' );
		$line    .= $line_col;
		$line    .= apply_filters( 'usces_filter_chk_ord_label_order', null, $usces_opt_order, $rows, $line_col );
		$line    .= $tr_f . $lf;

		// ==========================================================================

		foreach ( (array) $rows as $data ) {
			$order_id        = $data['ID'];
			$deli            = unserialize( $data['deli_name'] );
			$reduced_taxrate = usces_is_reduced_taxrate( $order_id );

			if ( usces_is_tax_display() && $reduced_taxrate ) {
				$cart      = usces_get_ordercartdata( $order_id );
				$condition = usces_get_order_condition( $order_id );
				$materials = array(
					'total_items_price' => $data['item_total_price'],
					'discount'          => $data['discount'],
					'shipping_charge'   => $data['shipping_charge'],
					'cod_fee'           => $data['cod_fee'],
					'use_point'         => $data['usedpoint'],
					'carts'             => $cart,
					'condition'         => $condition,
					'order_id'          => $order_id,
				);
				$usces_tax->get_order_tax( $materials );
			}

			$line    .= $tr_h;
			$line_row = '';
			if ( isset( $_REQUEST['check']['ID'] ) ) {
				$line_row .= $td_h1 . $order_id . $td_f;
			}
			if ( isset( $_REQUEST['check']['deco_id'] ) ) {
				$line_row .= $td_h . usces_get_deco_order_id( $order_id ) . $td_f;
			}
			if ( isset( $_REQUEST['check']['date'] ) ) {
				$line_row .= $td_h . $data['order_date'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['mem_id'] ) ) {
				$line_row .= $td_h . $data['mem_id'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['email'] ) ) {
				$line_row .= $td_h . usces_entity_decode( $data['email'], $ext ) . $td_f;
			}
			if ( ! empty( $cscs_meta ) ) {
				foreach ( $cscs_meta as $key => $entry ) {
					if ( 'name_pre' == $entry['position'] ) {
						$name     = $entry['name'];
						$cscs_key = 'cscs_' . $key;
						if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
			}

			switch ( $applyform ) {
				case 'JP':
					if ( isset( $_REQUEST['check']['name'] ) ) {
						$line_row .= $td_h . usces_entity_decode( $data['name1'] . ' ' . $data['name2'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['kana'] ) ) {
						$line_row .= $td_h . usces_entity_decode( $data['name3'] . ' ' . $data['name4'], $ext ) . $td_f;
					}
					break;
				case 'US':
				default:
					if ( isset( $_REQUEST['check']['name'] ) ) {
						$line_row .= $td_h . usces_entity_decode( $data['name2'] . ' ' . $data['name1'], $ext ) . $td_f;
					}
					break;
			}

			if ( ! empty( $cscs_meta ) ) {
				foreach ( $cscs_meta as $key => $entry ) {
					if ( 'name_after' == $entry['position'] ) {
						$name     = $entry['name'];
						$cscs_key = 'cscs_' . $key;
						if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
			}

			$address_info = '';
			switch ( $applyform ) {
				case 'JP':
					if ( isset( $_REQUEST['check']['zip'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['zipcode'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['country'] ) ) {
						$address_info .= $td_h . ( isset( $usces_settings['country'][ $data['country'] ] ) ? $usces_settings['country'][ $data['country'] ] : '' ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['pref'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['pref'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['address1'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['address1'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['address2'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['address2'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['address3'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['address3'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['tel'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['tel'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['fax'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['fax'], $ext ) . $td_f;
					}
					break;
				case 'US':
				default:
					if ( isset( $_REQUEST['check']['address2'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['address2'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['address3'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['address3'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['address1'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['address1'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['pref'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['pref'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['country'] ) ) {
						$address_info .= $td_h . ( isset( $usces_settings['country'][ $data['country'] ] ) ? $usces_settings['country'][ $data['country'] ] : '' ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['zip'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['zipcode'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['tel'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['tel'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['fax'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $data['fax'], $ext ) . $td_f;
					}
					break;
			}
			$address_info_args = compact( 'td_h', 'td_f', 'ext', 'order_id', 'applyform' );
			$line_row         .= apply_filters( 'usces_filter_ord_csv_address_info', $address_info, $data, $address_info_args );

			if ( ! empty( $cscs_meta ) ) {
				foreach ( $cscs_meta as $key => $entry ) {
					if ( 'fax_after' == $entry['position'] ) {
						$name     = $entry['name'];
						$cscs_key = 'cscs_' . $key;
						if ( isset( $_REQUEST['check'][ $cscs_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $cscs_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
			}
			$line_row .= apply_filters( 'usces_filter_chk_ord_data_customer', null, $usces_opt_order, $order_id, $data );
			// ----------------------------------------------------------------------
			if ( ! empty( $csde_meta ) ) {
				foreach ( $csde_meta as $key => $entry ) {
					if ( 'name_pre' == $entry['position'] ) {
						$name     = $entry['name'];
						$csde_key = 'csde_' . $key;
						if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
			}

			switch ( $applyform ) {
				case 'JP':
					if ( isset( $_REQUEST['check']['delivery_name'] ) ) {
						$line_row .= $td_h . usces_entity_decode( $deli['name1'] . ' ' . $deli['name2'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_kana'] ) ) {
						$line_row .= $td_h . usces_entity_decode( $deli['name3'] . ' ' . $deli['name4'], $ext ) . $td_f;
					}
					break;
				case 'US':
				default:
					if ( isset( $_REQUEST['check']['delivery_name'] ) ) {
						$line_row .= $td_h . usces_entity_decode( $deli['name2'] . ' ' . $deli['name1'], $ext ) . $td_f;
					}
					break;
			}

			if ( ! empty( $csde_meta ) ) {
				foreach ( $csde_meta as $key => $entry ) {
					if ( 'name_after' == $entry['position'] ) {
						$name     = $entry['name'] . '</td>';
						$csde_key = 'csde_' . $key;
						if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
			}

			$address_info = '';
			switch ( $applyform ) {
				case 'JP':
					if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['zipcode'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
						$address_info .= $td_h . ( isset( $usces_settings['country'][ $deli['country'] ] ) ? $usces_settings['country'][ $deli['country'] ] : '' ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['pref'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['address1'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['address2'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['address3'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['tel'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['fax'], $ext ) . $td_f;
					}
					break;
				case 'US':
				default:
					if ( isset( $_REQUEST['check']['delivery_address2'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['address2'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_address3'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['address3'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_address1'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['address1'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_pref'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['pref'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_country'] ) ) {
						$address_info .= $td_h . ( isset( $usces_settings['country'][ $deli['country'] ] ) ? $usces_settings['country'][ $deli['country'] ] : '' ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_zip'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['zipcode'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_tel'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['tel'], $ext ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['delivery_fax'] ) ) {
						$address_info .= $td_h . usces_entity_decode( $deli['fax'], $ext ) . $td_f;
					}
					break;
			}
			$line_row .= apply_filters( 'usces_filter_ord_csv_delivery_address_info', $address_info, $deli, $address_info_args );

			if ( ! empty( $csde_meta ) ) {
				foreach ( $csde_meta as $key => $entry ) {
					if ( 'fax_after' == $entry['position'] ) {
						$name     = $entry['name'];
						$csde_key = 'csde_' . $key;
						if ( isset( $_REQUEST['check'][ $csde_key ] ) ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $csde_key, $order_id ) );
							if ( empty( $value ) ) {
								$value = '';
							} elseif ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ' ';
								}
								$value = $concatval;
							}
							$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
						}
					}
				}
			}
			$line_row .= apply_filters( 'usces_filter_chk_ord_data_delivery', null, $usces_opt_order, $order_id, $deli );
			// ----------------------------------------------------------------------
			if ( isset( $_REQUEST['check']['shipping_date'] ) ) {
				$line_row .= $td_h . $data['order_modified'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['peyment_method'] ) ) {
				$line_row .= $td_h . $data['payment_name'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['wc_trans_id'] ) ) {
				$line_row .= $td_h . $data['wc_trans_id'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['delivery_method'] ) ) {
				$delivery_method = '';
				if ( strtoupper( $data['deli_method'] ) == '#NONE#' ) {
					$delivery_method = __( 'No preference', 'usces' );
				} else {
					foreach ( (array) $usces->options['delivery_method'] as $dkey => $delivery ) {
						if ( $delivery['id'] == $data['deli_method'] ) {
							$delivery_method = $delivery['name'];
							break;
						}
					}
				}
				$line_row .= $td_h . $delivery_method . $td_f;
			}
			if ( isset( $_REQUEST['check']['delivery_date'] ) ) {
				$line_row .= $td_h . $data['deli_date'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['delivery_time'] ) ) {
				$line_row .= $td_h . $data['deli_time'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['delidue_date'] ) ) {
				$order_delidue_date = ( strtoupper( $data['delidue_date'] ) == '#NONE#' ) ? '' : $data['delidue_date'];
				$line_row          .= $td_h . $order_delidue_date . $td_f;
			}
			if ( isset( $_REQUEST['check']['status'] ) ) {
				$order_status = explode( ',', $data['process_status'] );
				$status       = '';
				foreach ( (array) $order_status as $os ) {
					if ( isset( $usces_management_status[ $os ] ) ) {
						$status .= $usces_management_status[ $os ] . $sp;
					}
				}
				$line_row .= $td_h . trim( $status, $sp ) . $td_f;
			}
			if ( isset( $_REQUEST['check']['tracking_number'] ) ) {
				$line_row .= $td_h . $data['tracking_number'] . $td_f;
			}
			if ( isset( $_REQUEST['check']['total_amount'] ) ) {
				$line_row .= $td_h . usces_crform( $data['total_price'], false, false, 'return', false ) . $td_f;
			}
			if ( isset( $_REQUEST['check']['item_total_amount'] ) ) {
				$line_row .= $td_h . usces_crform( $data['item_total_price'], false, false, 'return', false ) . $td_f;
			}
			if ( usces_is_member_system() && usces_is_member_system_point() ) {
				if ( isset( $_REQUEST['check']['getpoint'] ) ) {
					$line_row .= $td_h . $data['getpoint'] . $td_f;
				}
				if ( isset( $_REQUEST['check']['usedpoint'] ) ) {
					$line_row .= $td_h . $data['usedpoint'] . $td_f;
				}
			}
			if ( isset( $_REQUEST['check']['discount'] ) ) {
				$line_row .= $td_h . usces_crform( $data['discount'], false, false, 'return', false ) . $td_f;
			}
			if ( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
				if ( $reduced_taxrate ) {
					if ( isset( $_REQUEST['check']['tax'] ) ) {
						if ( 'include' == usces_get_tax_mode() ) {
							$tax = $usces_tax->tax;
						} else {
							$tax = $data['tax'];
						}
						$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->subtotal_standard, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->tax_standard, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->subtotal_reduced, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->tax_reduced, false, false, 'return', false ) . $td_f;
					}
				} else {
					if ( isset( $_REQUEST['check']['tax'] ) ) {
						if ( 'include' == usces_get_tax_mode() ) {
							$materials = array(
								'total_items_price' => $data['item_total_price'],
								'discount'          => $data['discount'],
								'shipping_charge'   => $data['shipping_charge'],
								'cod_fee'           => $data['cod_fee'],
								'use_point'         => $data['usedpoint'],
								'order_id'          => $data['ID'],
							);
							$tax       = usces_internal_tax( $materials, 'return' );
						} else {
							$tax = $data['tax'];
						}
						$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
				}
			}
			if ( isset( $_REQUEST['check']['shipping_charge'] ) ) {
				$line_row .= $td_h . usces_crform( $data['shipping_charge'], false, false, 'return', false ) . $td_f;
			}
			if ( isset( $_REQUEST['check']['cod_fee'] ) ) {
				$line_row .= $td_h . usces_crform( $data['cod_fee'], false, false, 'return', false ) . $td_f;
			}
			if ( usces_is_tax_display() && 'products' != usces_get_tax_target() ) {
				if ( $reduced_taxrate ) {
					if ( isset( $_REQUEST['check']['tax'] ) ) {
						if ( 'include' == usces_get_tax_mode() ) {
							$tax = $usces_tax->tax;
						} else {
							$tax = $data['tax'];
						}
						$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->subtotal_standard, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->tax_standard, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->subtotal_reduced, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
						$line_row .= $td_h . usces_crform( $usces_tax->tax_reduced, false, false, 'return', false ) . $td_f;
					}
				} else {
					if ( isset( $_REQUEST['check']['tax'] ) ) {
						if ( 'include' == usces_get_tax_mode() ) {
							$materials = array(
								'total_items_price' => $data['item_total_price'],
								'discount'          => $data['discount'],
								'shipping_charge'   => $data['shipping_charge'],
								'cod_fee'           => $data['cod_fee'],
								'use_point'         => $data['usedpoint'],
								'order_id'          => $data['ID'],
							);
							$tax       = usces_internal_tax( $materials, 'return' );
						} else {
							$tax = $data['tax'];
						}
						$line_row .= $td_h . usces_crform( $tax, false, false, 'return', false ) . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_standard'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_standard'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
					if ( isset( $_REQUEST['check']['subtotal_reduced'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
					if ( isset( $_REQUEST['check']['tax_reduced'] ) ) {
						$line_row .= $td_h . '' . $td_f;
					}
				}
			}
			if ( isset( $_REQUEST['check']['note'] ) ) {
				$note      = apply_filters( 'usces_filter_newordercsv_note_value', $data['note'], $data );
				$line_row .= $td_h . usces_entity_decode( $note, $ext ) . $td_f;
			}
			if ( ! empty( $csod_meta ) ) {
				foreach ( $csod_meta as $key => $entry ) {
					$name     = $entry['name'];
					$csod_key = 'csod_' . $key;
					if ( isset( $_REQUEST['check'][ $csod_key ] ) ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $csod_key, $order_id ) );
						if ( empty( $value ) ) {
							$value = '';
						} elseif ( is_array( $value ) ) {
							$concatval = '';
							$c         = '';
							foreach ( $value as $v ) {
								$concatval .= $c . $v;
								$c          = ' ';
							}
							$value = $concatval;
						}
						$line_row .= $td_h . usces_entity_decode( $value, $ext ) . $td_f;
					}
				}
			}
			if ( isset( $_REQUEST['check']['admin_memo'] ) ) {
				$line_row .= $td_h . usces_entity_decode( $data['admin_memo'], $ext ) . $td_f;
			}
			$line_row = preg_replace( "/\n,/", "\n", ltrim( $line_row, ',' ) );
			$line    .= $line_row;
			$line    .= apply_filters( 'usces_filter_chk_ord_data_order', null, $usces_opt_order, $order_id, $data, $line_row );
			$line    .= $tr_f . $lf;
		}
		$line .= $table_f;
		$line  = apply_filters( 'wc_filter_chk_ord_data_order', $line );

		// ==========================================================================

		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=usces_order_list.' . $ext );
		mb_http_output( 'pass' );
		print( mb_convert_encoding( $line, apply_filters( 'usces_filter_output_csv_encode', 'SJIS-win' ), 'UTF-8' ) );
		exit();
	}

	/**
	 * Action view checkbox product order.
	 */
	public function self_action_chk_pro_order( $chk_pro ) {
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			$checked = usces_checked($chk_pro, $key, 'return' );
			echo '<label for="chk_pro['.$key.']"><input type="checkbox" class="check_pro" id="chk_pro['.$key.']" value="'.$key.'"'.$checked.' />'.esc_attr($value['label']).'</label>'."\n";
		}
	}

	/**
	 * Action view checkbox order.
	 */
	public function self_action_chk_ord_order( $chk_ord ) {
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			$checked = usces_checked($chk_ord, $key, 'return' );
			echo '<label for="chk_ord['.$key.']"><input type="checkbox" class="check_order" id="chk_ord['.$key.']" value="'.$key.'"'.$checked.' />'. esc_attr($value['label']).'</label>'."\n";
		}
	}

	/**
	 * Filter checkbox product order.
	 */
	public function self_filter_chk_pro( $chk_pro ) {
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			$chk_pro[$key]  = ( isset( $_REQUEST['check'][$key] ) ) ? 1 : 0;
		}
		return $chk_pro;
	}

	/**
	 * Filter checkbox product label order.
	 */
	public function self_filter_chk_pro_label_order( $other, $usces_opt_order, $rows ) {
		$th_h                  = ',"';
		$th_f                  = '"';
		$line_col              = $other;
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			if ( isset( $_REQUEST['check'][$key] ) ) {
				$line_col .= $th_h . $value['label'] . $th_f;
			}
		}
		return $line_col;
	}

	/**
	 * Filter checkbox product data order.
	 */
	public function self_filter_chk_pro_data_order( $other, $usces_opt_order, $order_id, $data, $cart_row ) {
		$td_h                  = ',"';
		$td_f                  = '"';
		$line_row              = $other;
		$arr_mail_print_status = self::get_value_print_mail();
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		$a_order_check         = maybe_unserialize( $data['order_check'] );
		if ( ! is_array( $a_order_check ) ) {
			$a_order_check = array();
		}
		foreach ( $arr_mail_print_fields as $key => $value ) {
			if ( isset( $_REQUEST['check'][$key] ) && isset( $arr_mail_print_status[$value['type']] ) ) {
				$type_mps = $arr_mail_print_status[$value['type']];
				if ( in_array( $key, $a_order_check ) ) {
					$line_row .= $td_h . $type_mps[1]['alias'] . $td_f;
				} else {
					$line_row .= $td_h . $type_mps[0]['alias'] . $td_f;
				}
			}
		}
		return $line_row;
	}

	/**
	 * Filter checkbox order.
	 */
	public function self_filter_chk_ord( $chk_ord ) {
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			$chk_ord[$key]  = ( isset( $_REQUEST['check'][$key] ) ) ? 1 : 0;
		}
		return $chk_ord;
	}

	/**
	 * Filter checkbox label order.
	 */
	public function self_filter_chk_ord_label_order( $other, $usces_opt_order, $rows, $line_col ) {
		$th_h                  = ',"';
		$th_f                  = '"';
		$line_col              = $other;
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			if ( isset( $_REQUEST['check'][$key] ) ) {
				$line_col .= $th_h . $value['label'] . $th_f;
			}
		}
		return $line_col;
	}

	/**
	 * Filter checkbox data order.
	 */
	public function self_filter_chk_ord_data_order( $other, $usces_opt_order, $order_id, $data, $cart_row ) {
		$td_h                  = ',"';
		$td_f                  = '"';
		$line_row              = $other;
		$arr_mail_print_status = self::get_value_print_mail();
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		$a_order_check         = maybe_unserialize( $data['order_check'] );
		if ( ! is_array( $a_order_check ) ) {
			$a_order_check = array();
		}
		if ( !empty( $arr_mail_print_fields ) ) {
			foreach ( $arr_mail_print_fields as $key => $value ) {
				if ( isset( $_REQUEST['check'][$key] ) && isset( $arr_mail_print_status[$value['type']] ) ) {
					$type_mps = $arr_mail_print_status[$value['type']];
					if ( in_array( $key, $a_order_check ) ) {
						$line_row .= $td_h . $type_mps[1]['alias'] . $td_f;
					} else {
						$line_row .= $td_h . $type_mps[0]['alias'] . $td_f;
					}
				}
			}
		}
		return $line_row;
	}
}
