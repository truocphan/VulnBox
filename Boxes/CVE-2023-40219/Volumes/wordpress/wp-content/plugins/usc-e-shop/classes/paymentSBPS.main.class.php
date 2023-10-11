<?php
/**
 * Settlement Class.
 * SB Payment Service
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.16
 */

/**
 * SB Payment Service Main Class
 */
class SBPS_MAIN {

	/**
	 * 決済代行会社ID
	 *
	 * @var string
	 */
	protected $paymod_id;

	/**
	 * 決済種別
	 *
	 * @var string
	 */
	protected $pay_method;

	/**
	 * 決済代行会社略称
	 *
	 * @var string
	 */
	protected $acting_name;

	/**
	 * 決済代行会社正式名称
	 *
	 * @var string
	 */
	protected $acting_formal_name;

	/**
	 * 併用不可決済モジュール
	 *
	 * @var array
	 */
	protected $unavailable_method;

	/**
	 * クレジットカード決済
	 * ex) 'sbps_card'
	 *
	 * @var string
	 */
	protected $acting_card;

	/**
	 * コンビニ決済
	 * ex) 'sbps_conv'
	 *
	 * @var string
	 */
	protected $acting_conv;

	/**
	 * Pay-easy（ペイジー）決済
	 * ex) 'sbps_payeasy'
	 *
	 * @var string
	 */
	protected $acting_payeasy;

	/**
	 * ウォレット決済
	 * ex) 'sbps_wallet'
	 *
	 * @var string
	 */
	protected $acting_wallet;

	/**
	 * キャリア決済
	 * ex) 'sbps_mobile'
	 *
	 * @var string
	 */
	protected $acting_mobile;

	/**
	 * PayPay オンライン決済
	 * ex) 'sbps_paypay'
	 *
	 * @var string
	 */
	protected $acting_paypay;

	/**
	 * クレジットカード決済フラグ
	 * ex) 'acting_sbps_card'
	 *
	 * @var string
	 */
	protected $acting_flg_card;

	/**
	 * コンビニ決済フラグ
	 * ex) 'acting_sbps_conv'
	 *
	 * @var string
	 */
	protected $acting_flg_conv;

	/**
	 * Pay-easy（ペイジー）決済フラグ
	 * ex) 'acting_sbps_payeasy'
	 *
	 * @var string
	 */
	protected $acting_flg_payeasy;

	/**
	 * ウォレット決済フラグ
	 * ex) 'acting_sbps_wallet'
	 *
	 * @var string
	 */
	protected $acting_flg_wallet;

	/**
	 * キャリア決済フラグ
	 * ex) 'acting_sbps_mobile'
	 *
	 * @var string
	 */
	protected $acting_flg_mobile;

	/**
	 * PayPay オンライン決済フラグ
	 * ex) 'acting_sbps_paypay'
	 *
	 * @var string
	 */
	protected $acting_flg_paypay;

	/**
	 * エラーメッセージ
	 *
	 * @var string
	 */
	protected $error_mes;

	/**
	 * Construct.
	 *
	 * @param  string $mode 'sbps'|'dsk'.
	 */
	public function __construct( $mode ) {

		$this->paymod_id = $mode;

		if ( $this->is_activate_conv() ) {
			add_filter( 'usces_filter_noreceipt_status', array( $this, 'noreceipt_status' ) );
		}

		if ( is_admin() ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if ( $this->is_validity_acting() ) {
			add_action( 'plugins_loaded', array( $this, 'acting_construct' ), 11 );
			add_action( 'usces_after_cart_instant', array( $this, 'acting_transaction' ), 9 );
			add_filter( 'usces_filter_order_confirm_mail_payment', array( $this, 'order_confirm_mail_payment' ), 10, 5 );
			add_filter( 'usces_filter_is_complete_settlement', array( $this, 'is_complete_settlement' ), 10, 3 );
			add_filter( 'usces_filter_get_link_key', array( $this, 'get_link_key' ), 10, 2 );
			add_action( 'usces_action_revival_order_data', array( $this, 'revival_orderdata' ), 10, 3 );
			if ( is_admin() ) {
				add_filter( 'usces_filter_settle_info_field_meta_keys', array( $this, 'settlement_info_field_meta_keys' ) );
				add_filter( 'usces_filter_settle_info_field_keys', array( $this, 'settlement_info_field_keys' ), 10, 2 );
				add_filter( 'usces_filter_settle_info_field_value', array( $this, 'settlement_info_field_value' ), 10, 3 );
			} else {
				add_filter( 'usces_filter_payment_detail', array( $this, 'payment_detail' ), 10, 2 );
				add_filter( 'usces_filter_payments_str', array( $this, 'payments_str' ), 10, 2 );
				add_filter( 'usces_filter_payments_arr', array( $this, 'payments_arr' ), 10, 2 );
				// add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
				add_filter( 'usces_filter_confirm_inform', array( $this, 'confirm_inform' ), 10, 5 );
				add_action( 'usces_action_confirm_page_point_inform', array( $this, 'e_point_inform' ), 10, 5 );
				add_filter( 'usces_filter_confirm_point_inform', array( $this, 'point_inform' ), 10, 5 );
				if ( defined( 'WCEX_COUPON' ) ) {
					add_filter( 'wccp_filter_coupon_inform', array( $this, 'point_inform' ), 10, 5 );
				}
				add_action( 'usces_action_acting_processing', array( $this, 'acting_processing' ), 10, 2 );
				add_filter( 'usces_filter_check_acting_return_results', array( $this, 'acting_return' ) );
				add_filter( 'usces_filter_check_acting_return_duplicate', array( $this, 'check_acting_return_duplicate' ), 10, 2 );
				add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
				// add_filter( 'usces_filter_get_error_settlement', array( $this, 'error_page_message' ) );
				add_filter( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
			}
		}

		if ( $this->is_validity_acting( 'card' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_print_footer_scripts', array( $this, 'footer_scripts' ), 9 );
			add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
			add_filter( 'usces_filter_delivery_secure_form_loop', array( $this, 'delivery_secure_form_loop' ), 10, 2 );
			add_action( 'usces_action_pre_delete_memberdata', array( $this, 'delete_member' ) );
			add_filter( 'usces_filter_get_error_settlement', array( $this, 'error_page_message' ) );
			if ( is_admin() ) {
				add_action( 'usces_action_admin_member_info', array( $this, 'admin_member_info' ), 10, 3 );
				add_action( 'usces_action_post_update_memberdata', array( $this, 'admin_update_memberdata' ), 10, 2 );
			}
		}
	}

	/**
	 * Initialize.
	 */
	public function initialize_data() {

	}

	/**
	 * 決済有効判定
	 * 引数が指定されたとき、支払方法で使用している場合に「有効」とする
	 *
	 * @param  string|NULL $type Payment type.
	 * @return boolean
	 */
	public function is_validity_acting( $type = '' ) {
		$acting_opts = $this->get_acting_settings();
		if ( empty( $acting_opts ) ) {
			return false;
		}

		$payment_method = usces_get_system_option( 'usces_payment_method', 'sort' );
		$method         = false;

		switch ( $type ) {
			case 'card':
				foreach ( $payment_method as $payment ) {
					if ( $this->acting_flg_card === $payment['settlement'] && 'activate' === $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_card() ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'conv':
				foreach ( $payment_method as $payment ) {
					if ( $this->acting_flg_conv === $payment['settlement'] && 'activate' === $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_conv() ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'payeasy':
				foreach ( $payment_method as $payment ) {
					if ( $this->acting_flg_payeasy === $payment['settlement'] && 'activate' === $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_payeasy() ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'wallet':
				foreach ( $payment_method as $payment ) {
					if ( $this->acting_flg_wallet === $payment['settlement'] && 'activate' === $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_wallet() ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'mobile':
				foreach ( $payment_method as $payment ) {
					if ( $this->acting_flg_mobile === $payment['settlement'] && 'activate' === $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_mobile() ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'paypay':
				foreach ( $payment_method as $payment ) {
					if ( $this->acting_flg_paypay === $payment['settlement'] && 'activate' === $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_paypay() ) {
					return true;
				} else {
					return false;
				}
				break;

			default:
				if ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) {
					return true;
				} else {
					return false;
				}
		}
	}

	/**
	 * クレジットカード決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_card() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['card_activate'] ) && ( 'on' === $acting_opts['card_activate'] || 'token' === $acting_opts['card_activate'] ) ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * コンビニ決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_conv() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['conv_activate'] ) && 'on' === $acting_opts['conv_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * ペイジー決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_payeasy() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['payeasy_activate'] ) && 'on' === $acting_opts['payeasy_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * ウォレット決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_wallet() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['wallet_activate'] ) && 'on' === $acting_opts['wallet_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * キャリア決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_mobile() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['mobile_activate'] ) && 'on' === $acting_opts['mobile_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * PayPay オンライン決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_paypay() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['paypay_activate'] ) && 'on' === $acting_opts['paypay_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * 決済オプション取得
	 *
	 * @return array
	 */
	protected function get_acting_settings() {
		global $usces;

		$acting_settings = ( isset( $usces->options['acting_settings'][ $this->paymod_id ] ) ) ? $usces->options['acting_settings'][ $this->paymod_id ] : array();
		return $acting_settings;
	}

	/**
	 * 動作環境ごとの接続先取得
	 *
	 * @return array
	 */
	protected function get_connection() {
		$connection  = array();
		$acting_opts = $this->get_acting_settings();
		if ( 'public' === $acting_opts['ope'] ) {
			$connection['send_url']  = $acting_opts['send_url'];
			$connection['token_url'] = $acting_opts['token_url'];
			$connection['api_url']   = $acting_opts['api_url'];
		} elseif ( 'test' === $acting_opts['ope'] ) {
			$connection['send_url']  = $acting_opts['send_url_test'];
			$connection['token_url'] = $acting_opts['token_url_test'];
			$connection['api_url']   = $acting_opts['api_url_test'];
		} else {
			$connection['send_url']  = $acting_opts['send_url_check'];
			$connection['token_url'] = $acting_opts['token_url_test'];
			$connection['api_url']   = $acting_opts['api_url_test'];
		}
		return $connection;
	}

	/**
	 * 未入金ステータス
	 * usces_filter_noreceipt_status
	 *
	 * @param  array $noreceipt_status Receive payment notification.
	 * @return array
	 */
	public function noreceipt_status( $noreceipt_status ) {
		if ( ! in_array( 'acting_' . $this->paymod_id . '_conv', $noreceipt_status ) || ! in_array( 'acting_' . $this->paymod_id . '_payeasy', $noreceipt_status ) ) {
			$noreceipt_status[] = 'acting_' . $this->paymod_id . '_conv';
			$noreceipt_status[] = 'acting_' . $this->paymod_id . '_payeasy';
			update_option( 'usces_noreceipt_status', $noreceipt_status );
		}
		return $noreceipt_status;
	}

	/**
	 * 決済オプション登録・更新
	 * usces_action_admin_settlement_update
	 */
	public function settlement_update() {

	}

	/**
	 * クレジット決済設定画面タブ
	 * usces_action_settlement_tab_title
	 */
	public function settlement_tab_title() {
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if ( in_array( $this->paymod_id, (array) $settlement_selected ) ) {
			echo '<li><a href="#uscestabs_' . esc_html( $this->paymod_id ) . '">' . esc_html( $this->acting_formal_name ) . '</a></li>';
		}
	}

	/**
	 * クレジット決済設定画面フォーム
	 * usces_action_settlement_tab_body
	 */
	public function settlement_tab_body() {

	}

	/**
	 * 結果通知前処理
	 * usces_construct
	 */
	public function acting_construct() {
		if ( isset( $_POST['res_result'] ) && isset( $_POST['res_pay_method'] ) && isset( $_POST['order_id'] ) ) {
			$post_data  = wp_unslash( $_POST );
			$rand       = $post_data['order_id'];
			$acting_flg = ( isset( $post_data['free1'] ) ) ? $post_data['free1'] : '';
			if ( in_array( $acting_flg, $this->pay_method ) ) {
				usces_restore_order_acting_data( $rand );
			}
			usces_log( $this->acting_name . ' construct : ' . $rand, 'acting_transaction.log' );
		}
	}

	/**
	 * 結果通知処理
	 * usces_after_cart_instant
	 */
	public function acting_transaction() {
		global $usces;

		if ( ! isset( $_GET['acting_return'] ) && isset( $_POST['res_result'] ) && isset( $_POST['res_pay_method'] ) ) {
			$post_data = wp_unslash( $_POST );
			foreach ( $post_data as $key => $value ) {
				$data[ $key ] = mb_convert_encoding( $value, 'UTF-8', 'SJIS' );
			}
			$acting_flg     = ( isset( $data['free1'] ) ) ? $data['free1'] : '';
			$acting         = substr( $acting_flg, 7 );
			$_GET['acting'] = $acting;

			switch ( $data['res_result'] ) {
				case 'OK': /* 決済処理OK */
					$order_id = $this->get_order_id( $data['res_tracking_id'] );
					if ( ! $order_id ) {
						$res = $usces->order_processing( $data );
						if ( 'ordercompletion' === $res ) {
							// $usces->set_order_meta_value( 'wc_trans_id', $data['res_tracking_id'], $order_id );
						} else {
							usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' order processing error : ' . print_r( $data, true ), 'acting_transaction.log' );
							die( 'NG,order processing error' );
						}
					}

					usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [OK] transaction : ' . $data['res_tracking_id'], 'acting_transaction.log' );
					die( 'OK,' );
					break;

				case 'PY': /* 入金結果通知 */
					$order_id = $this->get_order_id( $data['res_tracking_id'] );
					if ( ! $order_id ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [PY] error1 : ' . print_r( $data, true ), 'acting_transaction.log' );
						die( 'NG,order_id error' );
					}

					$order_status = $this->get_order_status( $order_id );
					if ( $usces->is_status( 'receipted', $order_status ) ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [PY] second transaction : ' . $order_id, 'acting_transaction.log' );
						die( 'OK,' );
					}

					$res = usces_change_order_receipt( $order_id, 'receipted' );
					if ( false === $res ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [PY] error2 : ' . print_r( $data, true ), 'acting_transaction.log' );
						die( 'NG,usces_order update error' );
					}

					$res = $usces->set_order_meta_value( $acting_flg, serialize( $data ), $order_id );
					if ( false === $res ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [PY] error3 : ' . print_r( $data, true ), 'acting_transaction.log' );
						die( 'NG,usces_order_meta update error' );
					}

					usces_action_acting_getpoint( $order_id );
					do_action( 'usces_action_' . $this->paymod_id . '_payment_completion', $data, $order_id );

					usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [PY] transaction : ' . $order_id, 'acting_transaction.log' );
					die( 'OK,' );
					break;

				case 'CN': /* 期限切通知 */
					$order_id = $this->get_order_id( $data['res_tracking_id'] );
					if ( ! $order_id ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [CN] error1 : ' . print_r( $data, true ), 'acting_transaction.log' );
						die( 'NG,order_id error' );
					}

					$res = usces_change_order_receipt( $order_id, 'noreceipt' );
					if ( false === $res ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [CN] error2 : ' . print_r( $data, true ), 'acting_transaction.log' );
						die( 'NG,usces_order update error' );
					}

					$res = $usces->set_order_meta_value( $acting_flg, serialize( $data ), $order_id );
					if ( false === $res ) {
						usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [CN] error3 : ' . print_r( $data, true ), 'acting_transaction.log' );
						die( 'NG,usces_order_meta update error' );
					}

					usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [CN] transaction : ' . $order_id, 'acting_transaction.log' );
					die( 'OK,' );
					break;

				case 'NG': /* 要求NG */
					$log = array(
						'acting' => $acting,
						'key'    => $data['res_tracking_id'],
						'result' => $data['res_result'],
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					die( 'NG,' );
					break;

				default:
					if ( empty( $data['res_result'] ) ) {
						return;
					}
					usces_log( $this->acting_name . ' ' . $data['res_pay_method'] . ' [' . $data['res_result'] . '] : ' . print_r( $data, true ), 'acting_transaction.log' );
					die( 'OK,' );
			}
		}
	}

	/**
	 * 管理画面送信メール
	 * usces_filter_order_confirm_mail_payment
	 *
	 * @param  string $msg_payment Default payment message.
	 * @param  int    $order_id Order number.
	 * @param  array  $payment Payment information.
	 * @param  array  $cart Cart data.
	 * @param  array  $data Order data.
	 * @return string
	 */
	public function order_confirm_mail_payment( $msg_payment, $order_id, $payment, $cart, $data ) {
		return $msg_payment;
	}

	/**
	 * ポイント即時付与
	 * usces_filter_is_complete_settlement
	 *
	 * @param  boolean $complete Payment completion flag.
	 * @param  string  $payment_name Payment name.
	 * @param  string  $status Status.
	 * @return boolean
	 */
	public function is_complete_settlement( $complete, $payment_name, $status ) {
		$payment = usces_get_payments_by_name( $payment_name );
		if ( $this->acting_flg_card === $payment['settlement'] || $this->acting_flg_wallet === $payment['settlement'] || $this->acting_flg_mobile === $payment['settlement'] || $this->acting_flg_paypay === $payment['settlement'] ) {
			$complete = true;
		}
		return $complete;
	}

	/**
	 * 決済リンクキー
	 * usces_filter_get_link_key
	 *
	 * @param  string $linkkey Settlement link key.
	 * @param  array  $results Response data.
	 * @return string
	 */
	public function get_link_key( $linkkey, $results ) {
		if ( isset( $_REQUEST['res_tracking_id'] ) ) {
			$linkkey = wp_unslash( $_REQUEST['res_tracking_id'] );
		}
		return $linkkey;
	}

	/**
	 * 受注データ復旧処理
	 * usces_action_revival_order_data
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $log_key Link key.
	 * @param  string $acting_flg Payment type.
	 */
	public function revival_orderdata( $order_id, $log_key, $acting_flg ) {
		global $usces;

		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}

		$data             = array();
		$data['LINK_KEY'] = $log_key;
		$usces->set_order_meta_value( $acting_flg, serialize( $data ), $order_id );
		$usces->set_order_meta_value( 'res_tracking_id', $log_key, $order_id );
	}

	/**
	 * 受注データから取得する決済情報のキー
	 * usces_filter_settle_info_field_meta_keys
	 *
	 * @param  array $keys Settlement information key.
	 * @return array
	 */
	public function settlement_info_field_meta_keys( $keys ) {
		$keys = array_merge( $keys, array( 'acting', 'trans_id' ) );
		return $keys;
	}

	/**
	 * 受注編集画面に表示する決済情報のキー
	 * usces_filter_settle_info_field_keys
	 *
	 * @param  array $keys Settlement information keys.
	 * @param  array $fields Settlement information fields.
	 * @return array
	 */
	public function settlement_info_field_keys( $keys, $fields ) {
		if ( isset( $fields['acting'] ) && isset( $fields['res_tracking_id'] ) ) {
			switch ( $fields['acting'] ) {
				case $this->acting_card:
				case $this->acting_conv:
				case $this->acting_payeasy:
				case $this->acting_wallet:
				case $this->acting_mobile:
				case $this->acting_paypay:
					$keys = array( 'acting', 'res_tracking_id', 'trans_id' );
					break;
			}
		}
		return $keys;
	}

	/**
	 * 受注編集画面に表示する決済情報の値整形
	 * usces_filter_settle_info_field_value
	 *
	 * @param  string $value Value.
	 * @param  string $key Key.
	 * @param  string $acting Acting type.
	 * @return string
	 */
	public function settlement_info_field_value( $value, $key, $acting ) {
		if ( 'acting' === $key ) {
			if ( $this->acting_card === $value ) {
				$value = $this->acting_name . ' カード決済';
			} elseif ( $this->acting_conv === $value ) {
				$value = $this->acting_name . ' コンビニ決済';
			} elseif ( $this->acting_payeasy === $value ) {
				$value = $this->acting_name . ' ペイジー決済';
			} elseif ( $this->acting_wallet === $value ) {
				$value = $this->acting_name . ' ウォレット決済';
			} elseif ( $this->acting_mobile === $value ) {
				$value = $this->acting_name . ' キャリア決済';
			} elseif ( $this->acting_paypay === $value ) {
				$value = $this->acting_name . ' PayPay オンライン決済';
			}
		}
		return $value;
	}

	/**
	 * 支払方法説明
	 * usces_filter_payment_detail
	 *
	 * @param  string $str Payment method description.
	 * @param  array  $usces_entries Entry data.
	 * @return string
	 */
	public function payment_detail( $str, $usces_entries ) {
		$payment = usces_get_payments_by_name( $usces_entries['order']['payment_name'] );
		if ( isset( $payment['settlement'] ) && $this->acting_flg_conv === $payment['settlement'] ) {
			$acting_opts = $this->get_acting_settings();
			if ( ! empty( $acting_opts['conv_limit'] ) ) {
				$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $acting_opts['conv_limit'] ) . __( ')', 'usces' );
				$str            = apply_filters( 'usces_filter_sbps_payment_limit_conv', $payment_detail, $acting_opts['conv_limit'] );
			}
		}
		return $str;
	}

	/**
	 * 支払方法 JavaScript 用決済名追加
	 * usces_filter_payments_str
	 *
	 * @param  string $payments_str Payments.
	 * @param  array  $payment Selected payment.
	 * @return string
	 */
	public function payments_str( $payments_str, $payment ) {
		if ( $this->acting_flg_card === $payment['settlement'] ) {
			if ( $this->is_activate_card() ) {
				$payments_str .= "'" . $payment['name'] . "': '" . $this->paymod_id . "_form', ";
			}
		}
		return $payments_str;
	}

	/**
	 * 支払方法 JavaScript 用決済追加
	 * usces_filter_payments_arr
	 *
	 * @param  array $payments_arr Payments.
	 * @param  array $payment Selected payment.
	 * @return array
	 */
	public function payments_arr( $payments_arr, $payment ) {
		if ( $this->acting_flg_card === $payment['settlement'] ) {
			if ( $this->is_activate_card() ) {
				$payments_arr[] = $this->paymod_id . '_form';
			}
		}
		return $payments_arr;
	}

	/**
	 * 内容確認ページ [注文する] ボタン
	 * usces_filter_confirm_inform
	 *
	 * @param  string $html Purchase post form.
	 * @param  array  $payments Payment method info.
	 * @param  string $acting_flg Payment type.
	 * @param  string $rand Welcart transaction key.
	 * @param  string $purchase_disabled Disable purchase button.
	 * @return string
	 */
	public function confirm_inform( $html, $payments, $acting_flg, $rand, $purchase_disabled ) {
		global $usces;

		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return $html;
		}

		$usces_entries = $usces->cart->get_entry();
		$cart          = $usces->cart->get_cart();
		if ( ! $usces_entries || ! $cart ) {
			return $html;
		}
		if ( ! $usces_entries['order']['total_full_price'] ) {
			return $html;
		}
		$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;

		$acting_opts = $this->get_acting_settings();
		$usces->save_order_acting_data( $rand );
		usces_save_order_acting_data( $rand );

		$member    = $usces->get_member();
		$cust_code = ( empty( $member['ID'] ) ) ? str_replace( '-', '', mb_convert_kana( $usces_entries['customer']['tel'], 'a', 'UTF-8' ) ) : $member['ID'];
		// $item_id = $cart[0]['post_id'];
		$item_id   = mb_convert_kana( $usces->getItemCode( $cart[0]['post_id'] ), 'a', 'UTF-8' );
		$item_name = $usces->getItemName( $cart[0]['post_id'] );
		if ( 1 < $cart_count ) {
			$item_name .= ' ' . __( 'Others', 'usces' );
		}
		if ( 36 < mb_strlen( $item_name, 'UTF-8' ) ) {
			$item_name = mb_substr( $item_name, 0, 30, 'UTF-8' ) . '...';
		}
		$item_name    = esc_attr( $item_name );
		$amount       = usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false );
		$free1        = $acting_flg;
		$limit_second = '600';

		if ( $this->acting_flg_card === $acting_flg && isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
			$cust_quick  = ( isset( $_POST['cust_quick'] ) ) ? $_POST['cust_quick'] : 'off';
			$cust_manage = ( isset( $_POST['cust_manage'] ) ) ? $_POST['cust_manage'] : 'off';
			$token       = ( isset( $_POST['token'] ) ) ? $_POST['token'] : '';
			$token_key   = ( isset( $_POST['tokenKey'] ) ) ? $_POST['tokenKey'] : '';
			$html        = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13){return false;}">';
			// if ( ! empty( $token ) && ! empty( $token_key ) ) {
			$html .= '
				<input type="hidden" name="cust_quick" value="' . $cust_quick . '" />
				<input type="hidden" name="cust_manage" value="' . $cust_manage . '" />
				<input type="hidden" name="cust_code" value="' . $cust_code . '" />
				<input type="hidden" name="order_id" value="' . $rand . '" />
				<input type="hidden" name="item_id" value="' . $item_id . '" />
				<input type="hidden" name="item_name" value="' . $item_name . '" />
				<input type="hidden" name="tax" value="" />
				<input type="hidden" name="amount" value="' . $amount . '" />
				<input type="hidden" name="free1" value="' . $free1 . '" />
				<input type="hidden" name="free2" value="" />
				<input type="hidden" name="free3" value="" />
				<input type="hidden" name="token" value="' . $token . '" />
				<input type="hidden" name="token_key" value="' . $token_key . '" />
				<input type="hidden" name="limit_second" value="' . $limit_second . '" />';
			// }
			$html .= '<div class="send">
				' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
				<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
				<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
				<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';

		} else {
			$connection     = $this->get_connection();
			$sps_cust_no    = '';
			$sps_payment_no = '';
			if ( $this->acting_flg_card === $acting_flg ) {
				$pay_method = ( 'on' === $acting_opts['3d_secure'] ) ? 'credit3d2' : 'credit';
				$acting     = $this->acting_card;
				$free_csv   = '';
			} elseif ( $this->acting_flg_conv === $acting_flg ) {
				$pay_method = 'webcvs';
				$acting     = $this->acting_conv;
				$free_csv   = $this->set_free_csv( $usces_entries['customer'], $acting_opts['conv_limit'] );
			} elseif ( $this->acting_flg_payeasy === $acting_flg ) {
				$pay_method = 'payeasy';
				$acting     = $this->acting_payeasy;
				$free_csv   = $this->set_free_csv( $usces_entries['customer'] );
			} elseif ( $this->acting_flg_wallet === $acting_flg ) {
				$pay_method = '';
				if ( 'on' === $acting_opts['wallet_yahoowallet'] ) {
					$pay_method .= ',yahoowallet';
				}
				if ( 'on' === $acting_opts['wallet_rakuten'] ) {
					$pay_method .= ',rakuten';
				}
				if ( 'on' === $acting_opts['wallet_paypal'] ) {
					$pay_method .= ',paypal';
				}
				if ( 'on' === $acting_opts['wallet_alipay'] ) {
					$pay_method .= ',alipay';
				}
				$pay_method = ltrim( $pay_method, ',' );
				$acting     = $this->acting_wallet;
				$free_csv   = '';
			} elseif ( $this->acting_flg_mobile === $acting_flg ) {
				$pay_method = '';
				if ( 'on' === $acting_opts['mobile_docomo'] ) {
					$pay_method .= ',docomo';
				}
				if ( 'on' === $acting_opts['mobile_auone'] ) {
					$pay_method .= ',auone';
				}
				if ( 'on' === $acting_opts['mobile_softbank2'] ) {
					$pay_method .= ',softbank2';
				}
				$pay_method = ltrim( $pay_method, ',' );
				$acting     = $this->acting_mobile;
				$free_csv   = '';
			} elseif ( $this->acting_flg_paypay === $acting_flg ) {
				$pay_method = 'paypay';
				$acting     = $this->acting_paypay;
				$free_csv   = $this->set_free_csv( $usces_entries['customer'] );
			}
			$pay_type          = '0';
			$auto_charge_type  = '';
			$service_type      = '0';
			$div_settle        = '';
			$last_charge_month = '';
			$camp_type         = '';
			$terminal_type     = '0';
			$success_url       = USCES_CART_URL . $usces->delim . 'acting=' . $acting . '&acting_return=1';
			$cancel_url        = USCES_CART_URL . $usces->delim . 'acting=' . $acting . '&confirm=1';
			$error_url         = USCES_CART_URL . $usces->delim . 'acting=' . $acting . '&acting_return=0';
			$pagecon_url       = apply_filters( 'usces_filter_' . $this->paymod_id . '_pagecon_url', USCES_CART_URL );
			$request_date      = wp_date( 'YmdHis' );
			$sps_hashcode      = $pay_method . $acting_opts['merchant_id'] . $acting_opts['service_id'] . $cust_code . $sps_cust_no . $sps_payment_no . $rand . $item_id . $item_name . $amount . $pay_type . $auto_charge_type . $service_type . $div_settle . $last_charge_month . $camp_type . $terminal_type . $success_url . $cancel_url . $error_url . $pagecon_url . $free1 . $free_csv . $request_date . $limit_second . $acting_opts['hash_key'];
			$sps_hashcode      = sha1( $sps_hashcode );

			$html  = '<form id="purchase_form" name="purchase_form" action="' . $connection['send_url'] . '" method="post" onKeyDown="if (event.keyCode == 13){return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="pay_method" value="' . $pay_method . '" />
				<input type="hidden" name="merchant_id" value="' . $acting_opts['merchant_id'] . '" />
				<input type="hidden" name="service_id" value="' . $acting_opts['service_id'] . '" />
				<input type="hidden" name="cust_code" value="' . $cust_code . '" />
				<input type="hidden" name="sps_cust_no" value="' . $sps_cust_no . '" />
				<input type="hidden" name="sps_payment_no" value="' . $sps_payment_no . '" />
				<input type="hidden" name="order_id" value="' . $rand . '" />
				<input type="hidden" name="item_id" value="' . $item_id . '" />
				<input type="hidden" name="pay_item_id" value="" />
				<input type="hidden" name="item_name" value="' . $item_name . '" />
				<input type="hidden" name="tax" value="" />
				<input type="hidden" name="amount" value="' . $amount . '" />
				<input type="hidden" name="pay_type" value="' . $pay_type . '" />
				<input type="hidden" name="auto_charge_type" value="' . $auto_charge_type . '" />
				<input type="hidden" name="service_type" value="' . $service_type . '" />
				<input type="hidden" name="div_settle" value="' . $div_settle . '" />
				<input type="hidden" name="last_charge_month" value="' . $last_charge_month . '" />
				<input type="hidden" name="camp_type" value="' . $camp_type . '" />
				<input type="hidden" name="terminal_type" value="' . $terminal_type . '" />
				<input type="hidden" name="success_url" value="' . $success_url . '" />
				<input type="hidden" name="cancel_url" value="' . $cancel_url . '" />
				<input type="hidden" name="error_url" value="' . $error_url . '" />
				<input type="hidden" name="pagecon_url" value="' . $pagecon_url . '" />
				<input type="hidden" name="free1" value="' . $free1 . '" />
				<input type="hidden" name="free2" value="" />
				<input type="hidden" name="free3" value="" />
				<input type="hidden" name="free_csv" value="' . $free_csv . '" />
				<input type="hidden" name="request_date" value="' . $request_date . '" />
				<input type="hidden" name="limit_second" value="' . $limit_second . '" />
				<input type="hidden" name="sps_hashcode" value="' . $sps_hashcode . '" />';
			$html .= '<input type="hidden" name="dummy" value="&#65533;" />';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"' ) . $purchase_disabled . ' /></div>';
			$html .= '</form>';
			$html .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13){return false;}">
				<div class="send">
					' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
					<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
				</div>';
		}
		return $html;
	}

	/**
	 * Encode free_csv
	 *
	 * @param array  $customer Customer data.
	 * @param string $conv_limit Convenience store payment deadline.
	 */
	public function set_free_csv( $customer, $conv_limit = '' ) {
		$free_csv = '';
		if ( ! WCUtils::is_blank( $customer['name1'] ) ) {
			$free_csv = 'LAST_NAME=' . mb_convert_encoding( $customer['name1'], 'SJIS', 'UTF-8' ) . ',FIRST_NAME=' . mb_convert_encoding( $customer['name2'], 'SJIS', 'UTF-8' ) . ',TEL=' . str_replace( '-', '', $customer['tel'] ) . ',MAIL=' . $customer['mailaddress1'];
			if ( ! empty( $conv_limit ) ) {
				$days      = sprintf( '+%d day', $conv_limit );
				$bill_date = wp_date( 'Ymd', strtotime( $days ) );
				$free_csv .= ',BILL_DATE=' . $bill_date;
			}
			$acting_opts = $this->get_acting_settings();
			if ( ! empty( $acting_opts['3des_key'] ) && ! empty( $acting_opts['3desinit_key'] ) ) {
				$free_csv = openssl_encrypt( $free_csv, 'des-ede3-cbc', $acting_opts['3des_key'], OPENSSL_RAW_DATA, $acting_opts['3desinit_key'] );
			}
			$free_csv = base64_encode( $free_csv );
		}
		return $free_csv;
	}

	/**
	 * 内容確認ページ ポイントフォーム
	 * usces_action_confirm_page_point_inform
	 */
	public function e_point_inform() {
		$html = $this->point_inform( '' );
		wel_esc_script_e( $html );
	}

	/**
	 * 内容確認ページ ポイントフォーム
	 * usces_filter_confirm_point_inform
	 *
	 * @param  string $html Input point form.
	 * @return string
	 */
	public function point_inform( $html ) {
		global $usces;

		$usces_entries = $usces->cart->get_entry();
		$payment       = usces_get_payments_by_name( $usces_entries['order']['payment_name'] );
		if ( $this->acting_flg_card !== $payment['settlement'] ) {
			return $html;
		}

		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
			$html .= '
			<input type="hidden" name="token" value="' . $_POST['token'] . '">
			<input type="hidden" name="tokenKey" value="' . $_POST['tokenKey'] . '">';
			if ( isset( $_POST['cust_quick'] ) ) {
				$html .= '
			<input type="hidden" name="cust_quick" value="' . $_POST['cust_quick'] . '">';
			}
			if ( isset( $_POST['cust_manage'] ) ) {
				$html .= '
			<input type="hidden" name="cust_manage" value="' . $_POST['cust_manage'] . '">';
			}
		}
		return $html;
	}

	/**
	 * 決済処理
	 * usces_action_acting_processing
	 *
	 * @param  string $acting_flg Acting type.
	 * @param  array  $post_query Post data.
	 */
	public function acting_processing( $acting_flg, $post_query ) {
		global $usces;

		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}
		$usces_entries = $usces->cart->get_entry();
		$cart          = $usces->cart->get_cart();
		if ( ! $usces_entries || ! $cart ) {
			wp_redirect( USCES_CART_URL );
			exit;
		}
		if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $acting_flg ) ) {
			wp_redirect( USCES_CART_URL );
			exit;
		}

		$usces->error_message = $usces->zaiko_check();
		if ( '' !== $usces->error_message || 0 === $usces->cart->num_row() ) {
			wp_redirect( USCES_CART_URL );
			exit();
		}

		$acting_opts = $this->get_acting_settings();
		parse_str( $post_query, $post_data );
		$connection = $this->get_connection();

		if ( $this->acting_flg_card === $acting_flg && isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
			$item_name   = trim( mb_convert_encoding( $post_data['item_name'], 'SJIS', 'UTF-8' ) );
			$free1       = trim( $post_data['free1'] );
			$order_rowno = '1';
			if ( isset( $post_data['cust_quick'] ) ) {
				if ( 'quick' === $post_data['cust_quick'] ) {
					$token           = '';
					$token_key       = '';
					$cust_manage_flg = '0';
				} else {
					$token           = trim( $post_data['token'] );
					$token_key       = trim( $post_data['token_key'] );
					$cust_manage_flg = ( 'save' === $post_data['cust_manage'] || 'change' === $post_data['cust_manage'] ) ? '1' : '0';
				}
			} else {
				$token           = trim( $post_data['token'] );
				$token_key       = trim( $post_data['token_key'] );
				$cust_manage_flg = ( isset( $post_data['cust_manage'] ) && 'save' === $post_data['cust_manage'] ) ? '1' : '0';
			}
			$cardbrand_return_flg = '0';
			$encrypted_flg        = '1';
			$request_date         = wp_date( 'YmdHis' );
			$sps_hashcode         = $acting_opts['merchant_id'] . $acting_opts['service_id'] . trim( $post_data['cust_code'] ) . trim( $post_data['order_id'] ) . trim( $post_data['item_id'] ) . $item_name . trim( $post_data['amount'] ) . $free1 . $order_rowno . trim( $post_data['token'] ) . trim( $post_data['token_key'] ) . $cust_manage_flg . $cardbrand_return_flg . $encrypted_flg . $request_date . trim( $post_data['limit_second'] ) . $acting_opts['hash_key'];
			$sps_hashcode         = sha1( $sps_hashcode );

			/* 決済要求 */
			$request_settlement  = '<?xml version="1.0" encoding="Shift_JIS"?>
<sps-api-request id="ST01-00131-101">
	<merchant_id>' . $acting_opts['merchant_id'] . '</merchant_id>
	<service_id>' . $acting_opts['service_id'] . '</service_id>
	<cust_code>' . trim( $post_data['cust_code'] ) . '</cust_code>
	<order_id>' . trim( $post_data['order_id'] ) . '</order_id>
	<item_id>' . trim( $post_data['item_id'] ) . '</item_id>
	<item_name>' . base64_encode( $item_name ) . '</item_name>
	<amount>' . trim( $post_data['amount'] ) . '</amount>
	<free1>' . base64_encode( $free1 ) . '</free1>
	<order_rowno>' . $order_rowno . '</order_rowno>
	<pay_option_manage>
		<token>' . $token . '</token>
		<token_key>' . $token_key . '</token_key>
		<cust_manage_flg>' . $cust_manage_flg . '</cust_manage_flg>
		<cardbrand_return_flg>' . $cardbrand_return_flg . '</cardbrand_return_flg>
	</pay_option_manage>
	<encrypted_flg>' . $encrypted_flg . '</encrypted_flg>
	<request_date>' . $request_date . '</request_date>
	<limit_second>' . trim( $post_data['limit_second'] ) . '</limit_second>
	<sps_hashcode>' . $sps_hashcode . '</sps_hashcode>
</sps-api-request>';
			$xml_settlement      = $this->get_xml_response( $connection['api_url'], $request_settlement );
			$response_settlement = $this->xml2assoc( $xml_settlement, $this->acting_card, $encrypted_flg );
			if ( isset( $response_settlement['res_result'] ) && 'OK' === $response_settlement['res_result'] ) {
				$sps_transaction_id = $response_settlement['res_sps_transaction_id'];
				$tracking_id        = $response_settlement['res_tracking_id'];
				$request_date       = wp_date( 'YmdHis' );
				$sps_hashcode       = $acting_opts['merchant_id'] . $acting_opts['service_id'] . $sps_transaction_id . $tracking_id . $request_date . trim( $post_data['limit_second'] ) . $acting_opts['hash_key'];
				$sps_hashcode       = sha1( $sps_hashcode );

				/* 確定要求 */
				$request_credit  = '<?xml version="1.0" encoding="Shift_JIS"?>
<sps-api-request id="ST02-00101-101">
	<merchant_id>' . $acting_opts['merchant_id'] . '</merchant_id>
	<service_id>' . $acting_opts['service_id'] . '</service_id>
	<sps_transaction_id>' . $sps_transaction_id . '</sps_transaction_id>
	<tracking_id>' . $tracking_id . '</tracking_id>
	<processing_datetime></processing_datetime>
	<request_date>' . $request_date . '</request_date>
	<limit_second>' . trim( $post_data['limit_second'] ) . '</limit_second>
	<sps_hashcode>' . $sps_hashcode . '</sps_hashcode>
</sps-api-request>';
				$xml_credit      = $this->get_xml_response( $connection['api_url'], $request_credit );
				$response_credit = $this->xml2assoc( $xml_credit, $this->acting_card );
				if ( isset( $response_credit['res_result'] ) && 'OK' === $response_credit['res_result'] ) {
					if ( isset( $acting_opts['sales'] ) && 'auto' === $acting_opts['sales'] ) {
						$process_date = $response_credit['res_process_date'];
						$request_date = wp_date( 'YmdHis' );
						$sps_hashcode = $acting_opts['merchant_id'] . $acting_opts['service_id'] . $sps_transaction_id . $tracking_id . $process_date . trim( $post_data['amount'] ) . $request_date . trim( $post_data['limit_second'] ) . $acting_opts['hash_key'];
						$sps_hashcode = sha1( $sps_hashcode );

						/* 売上要求（自動売上）*/
						$request_sales  = '<?xml version="1.0" encoding="Shift_JIS"?>
<sps-api-request id="ST02-00201-101">
	<merchant_id>' . $acting_opts['merchant_id'] . '</merchant_id>
	<service_id>' . $acting_opts['service_id'] . '</service_id>
	<sps_transaction_id>' . $sps_transaction_id . '</sps_transaction_id>
	<tracking_id>' . $tracking_id . '</tracking_id>
	<processing_datetime>' . $process_date . '</processing_datetime>
	<pay_option_manage>
		<amount>' . trim( $post_data['amount'] ) . '</amount>
	</pay_option_manage>
	<request_date>' . $request_date . '</request_date>
	<limit_second>' . trim( $post_data['limit_second'] ) . '</limit_second>
	<sps_hashcode>' . $sps_hashcode . '</sps_hashcode>
</sps-api-request>';
						$xml_sales      = $this->get_xml_response( $connection['api_url'], $request_sales );
						$response_sales = $this->xml2assoc( $xml_sales, $this->acting_card );
						if ( isset( $response_sales['res_result'] ) && 'OK' === $response_sales['res_result'] ) {
							$result_data = array(
								'acting'                 => $this->acting_card,
								'acting_return'          => 1,
								'res_result'             => $response_sales['res_result'],
								'res_tracking_id'        => $tracking_id,
								'res_sps_transaction_id' => $response_sales['res_sps_transaction_id'],
								'res_process_date'       => $response_sales['res_process_date'],
								'res_pay_method'         => 'sales',
								'trans_id'               => $post_data['order_id'],
							);
							wp_redirect( add_query_arg( $result_data, USCES_CART_URL ) );
							exit;

						} else {
							$res_err_code = ( isset( $response_sales['res_err_code'] ) ) ? $response_sales['res_err_code'] : 'ST02-00201-101 Error';
							$data         = ( ! empty( $response_sales ) ) ? $response_sales : $xml_sales;
							$log          = array(
								'acting' => $this->acting_card,
								'key'    => $post_data['order_id'],
								'result' => $res_err_code,
								'data'   => $data,
							);
							usces_save_order_acting_error( $log );
							$result_data = array(
								'acting'        => $this->acting_card,
								'acting_return' => 0,
								'res_err_code'  => $res_err_code,
							);
							wp_redirect( add_query_arg( $result_data, USCES_CART_URL ) );
							exit;
						}

					} else {
						/* 指定売上 */
						$result_data = array(
							'acting'                 => $this->acting_card,
							'acting_return'          => 1,
							'res_result'             => $response_credit['res_result'],
							'res_tracking_id'        => $tracking_id,
							'res_sps_transaction_id' => $response_credit['res_sps_transaction_id'],
							'res_process_date'       => $response_credit['res_process_date'],
							'res_pay_method'         => 'manual',
							'trans_id'               => $post_data['order_id'],
						);
						wp_redirect( add_query_arg( $result_data, USCES_CART_URL ) );
						exit;
					}

				} else {
					$res_err_code = ( isset( $response_credit['res_err_code'] ) ) ? $response_credit['res_err_code'] : 'ST02-00101-101 Error';
					$data         = ( ! empty( $response_credit ) ) ? $response_credit : $xml_credit;
					$log          = array(
						'acting' => $this->acting_card,
						'key'    => $post_data['order_id'],
						'result' => $res_err_code,
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					$result_data = array(
						'acting'        => $this->acting_card,
						'acting_return' => 0,
						'res_err_code'  => $res_err_code,
					);
					wp_redirect( add_query_arg( $result_data, USCES_CART_URL ) );
					exit;
				}

			} else {
				$res_err_code = ( isset( $response_settlement['res_err_code'] ) ) ? $response_settlement['res_err_code'] : 'ST01-00131-101 Error';
				$data         = ( ! empty( $response_settlement ) ) ? $response_settlement : $xml_settlement;
				$log          = array(
					'acting' => $this->acting_card,
					'key'    => $post_data['order_id'],
					'result' => $res_err_code,
					'data'   => $data,
				);
				usces_save_order_acting_error( $log );
				$result_data = array(
					'acting'        => $this->acting_card,
					'acting_return' => 0,
					'res_err_code'  => $res_err_code,
				);
				wp_redirect( add_query_arg( $result_data, USCES_CART_URL ) );
				exit;
			}

			exit;
		}
	}

	/**
	 * Get XML response
	 *
	 * @param  string $url Send URL.
	 * @param  array  $data Send data.
	 * @return string
	 */
	protected function get_xml_response( $url, $data ) {
		$acting_opts = $this->get_acting_settings();
		$interface   = parse_url( $url );

		$header  = 'POST ' . $url . " HTTP/1.1\r\n";
		$header .= 'Host: ' . $interface['host'] . "\r\n";
		$header .= 'Authorization: Basic ' . base64_encode( $acting_opts['basic_id'] . ':' . $acting_opts['basic_password'] ) . "\r\n";
		$header .= "User-Agent: PHP Script\r\n";
		$header .= "Content-Type: text/xml\r\n";
		$header .= 'Content-Length: ' . strlen( $data ) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$header .= $data;

		$fp = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
		if ( ! $fp ) {
			usces_log( $this->paymod_id . ' API : TLS(v1.2) Error', 'acting_transaction.log' );
		}

		$xml = '';
		if ( $fp ) {
			fwrite( $fp, $header );
			while ( ! feof( $fp ) ) {
				$code = fgets( $fp, 1024 );
				if ( false !== strpos( $code, '>' ) ) {
					$xml .= $code;
				}
			}
			fclose( $fp );
		}
		return $xml;
	}

	/**
	 * XML to Associative Array
	 *
	 * @param  object $xml_data XML data.
	 * @param  string $acting Acting type.
	 * @param  int    $encrypted_flg Encrypted flag.
	 * @return array
	 */
	protected function xml2assoc( $xml_data, $acting, $encrypted_flg = 0 ) {
		$array_data = array();
		if ( $this->is_valid_xml( $xml_data, $acting ) ) {
			$object_data = simplexml_load_string( $xml_data );
			$json_data   = json_encode( $object_data );
			$array_data  = json_decode( $json_data, true );
			if ( 1 === (int) $encrypted_flg ) {
				$acting_opts = $this->get_acting_settings();
				if ( isset( $array_data['res_pay_method_info'] ) && ! empty( $acting_opts['3des_key'] ) && ! empty( $acting_opts['3desinit_key'] ) ) {
					foreach ( $array_data['res_pay_method_info'] as $info => $value ) {
						$value                                      = base64_decode( $value );
						$value                                      = openssl_decrypt( $value, 'des-ede3-cbc', $acting_opts['3des_key'], OPENSSL_NO_PADDING, $acting_opts['3desinit_key'] );
						$array_data['res_pay_method_info'][ $info ] = trim( $value );
					}
				}
				if ( isset( $array_data['pay_method_info'] ) && ! empty( $acting_opts['3des_key'] ) && ! empty( $acting_opts['3desinit_key'] ) ) {
					foreach ( $array_data['pay_method_info'] as $info => $value ) {
						$value                                  = base64_decode( $value );
						$value                                  = openssl_decrypt( $value, 'des-ede3-cbc', $acting_opts['3des_key'], OPENSSL_NO_PADDING, $acting_opts['3desinit_key'] );
						$array_data['pay_method_info'][ $info ] = trim( $value );
					}
				}
			}
		}
		return $array_data;
	}

	/**
	 * XML Validator.
	 *
	 * @param  object $xml_data XML data.
	 * @param  string $acting Acting type.
	 * @return boolean
	 */
	private function is_valid_xml( $xml_data, $acting ) {
		if ( ! empty( $xml_data ) ) {
			if ( stripos( $xml_data, '<?xml version=' ) !== false ) {
				return true;
			}
		}
		$log = array(
			'acting' => $acting,
			'key'    => '(empty)',
			'result' => 'XML validation error',
			'data'   => array( 'response' => $xml_data ),
		);
		usces_save_order_acting_error( $log );
		return false;
	}

	/**
	 * 決済完了ページ制御
	 * usces_filter_check_acting_return_results
	 *
	 * @param  array $results Result data.
	 * @return array
	 */
	public function acting_return( $results ) {
		if ( ! in_array( 'acting_' . $results['acting'], $this->pay_method ) ) {
			return $results;
		}
		if ( isset( $results['acting_return'] ) && 1 !== (int) $results['acting_return'] ) {
			return $results;
		}

		if ( isset( $_REQUEST['cancel'] ) ) {
			$results[0]           = 0;
			$results['reg_order'] = false;
		} else {
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
				if ( isset( $_REQUEST['res_result'] ) && 'OK' === $_REQUEST['res_result'] ) {
					$results[0]           = 1;
					$results['reg_order'] = true;
				} else {
					$results[0]           = 0;
					$results['reg_order'] = false;
				}
			} else {
				if ( isset( $_REQUEST['res_result'] ) && 'OK' === $_REQUEST['res_result'] ) {
					$results[0] = 1;
				} else {
					$results[0] = 0;
				}
				$results['reg_order'] = false;
			}
		}
		return $results;
	}

	/**
	 * 重複オーダー禁止処理
	 * usces_filter_check_acting_return_duplicate
	 *
	 * @param  string $trans_id Transaction ID.
	 * @param  array  $results Result data.
	 * @return string
	 */
	public function check_acting_return_duplicate( $trans_id, $results ) {
		global $usces;

		$acting     = ( isset( $_GET['acting'] ) ) ? wp_unslash( $_GET['acting'] ) : '';
		$acting_flg = 'acting_' . $acting;
		if ( in_array( $acting_flg, $this->pay_method ) ) {
			if ( isset( $_REQUEST['order_id'] ) ) {
				$trans_id = wp_unslash( $_REQUEST['order_id'] );
			}
		}
		return $trans_id;
	}

	/**
	 * 受注データ登録
	 * usces_action_reg_orderdata
	 *
	 * @param string $args {
	 *     The array of Order related data.
	 *     @type array  $cart          Cart data.
	 *     @type array  $entry         Entry data.
	 *     @type int    $order_id      Order ID.
	 *     @type int    $member_id     Member ID.
	 *     @type array  $payments      Payment data.
	 *     @type int    $charging_type Charging type.
	 *     @type array  $results       Results data.
	 * }
	 */
	public function register_orderdata( $args ) {
		global $usces;
		extract( $args );

		$acting_flg = $payments['settlement'];
		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}
		if ( ! $entry['order']['total_full_price'] ) {
			return;
		}

		if ( isset( $results['res_tracking_id'] ) ) {
			$usces->set_order_meta_value( 'res_tracking_id', $results['res_tracking_id'], $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $results['res_tracking_id'], $order_id );
			if ( isset( $results['order_id'] ) ) {
				$usces->set_order_meta_value( 'trans_id', $results['order_id'], $order_id );
			} elseif ( isset( $results['trans_id'] ) ) {
				$usces->set_order_meta_value( 'trans_id', $results['trans_id'], $order_id );
			}
			if ( ! isset( $results['acting'] ) ) {
				$results['acting'] = substr( $acting_flg, 7 );
			}
			if ( isset( $results['acting_return'] ) ) {
				unset( $results['acting_return'] );
				unset( $results['page_id'] );
				unset( $results['0'] );
				unset( $results['reg_order'] );
			}
			$usces->set_order_meta_value( $acting_flg, usces_serialize( $results ), $order_id );
		}
	}

	/**
	 * 会員データ削除
	 * usces_action_pre_delete_memberdata
	 *
	 * @param  string $member_id Member ID.
	 */
	public function delete_member( $member_id ) {
		$this->api_customer_delete( $member_id );
	}

	/**
	 * 決済エラーメッセージ
	 * usces_filter_get_error_settlement
	 *
	 * @param  string $html Credit card number error.
	 * @return string Credit card number re-entry message.
	 */
	public function error_page_message( $html ) {
		if ( isset( $_REQUEST['acting'] ) && $this->acting_card === $_REQUEST['acting'] ) {
			if ( isset( $_GET['res_err_code'] ) && '101' === substr( $_GET['res_err_code'], 0, 3 ) ) {
				$html .= '<div class="support_box">
					クレジットカード情報に誤りがあります。<br />カード番号を再入力する場合は、こちらをクリックしてください。<br /><br />
					<p class="return_settlement"><a href="' . add_query_arg( array( 'backDelivery' => $this->acting_card, 're-enter' => 1 ), USCES_CUSTOMER_URL ) . '">カード番号の再入力</a></p>
				</div>';
			}
		}
		return $html;
	}

	/**
	 * 購入完了メッセージ
	 * usces_filter_completion_settlement_message
	 *
	 * @param  string $html Purchase complete message.
	 * @param  array  $usces_entries Entry data.
	 * @return string
	 */
	public function completion_settlement_message( $html, $usces_entries ) {
		if ( isset( $_REQUEST['acting'] ) && ( $this->acting_conv === $_REQUEST['acting'] || $this->acting_payeasy === $_REQUEST['acting'] ) ) {
			$title = ( $this->acting_conv === $_REQUEST['acting'] ) ? 'コンビニ決済' : 'ペイジー決済';
			$html .= '<div id="status_table"><h5>' . $this->acting_formal_name . '　' . $title . '</h5>';
			$html .= '<p>' . sprintf( __( 'Information on payment will be mailed to %s.', 'usces' ), esc_html( $usces_entries['customer']['mailaddress1'] ) ) . '</p>';
			$html .= '</div>';
		}
		return $html;
	}

	/**
	 * フロントスクリプト
	 * wp_enqueue_scripts
	 */
	public function enqueue_scripts() {
		global $usces;

		/* 発送・支払方法ページ */
		if ( ! is_admin() && 'delivery' === $usces->page && $this->is_validity_acting( 'card' ) ) :
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) :
				$connection = $this->get_connection();
				?>
<script type="text/javascript" src="<?php echo esc_url( $connection['token_url'] ); ?>"></script>
				<?php
			endif;
		endif;
	}

	/**
	 * フロントスクリプト
	 * wp_print_footer_scripts
	 */
	public function footer_scripts() {
		global $usces;

		/* 発送・支払方法ページ */
		if ( $this->is_validity_acting( 'card' ) && $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'delivery' === $usces->page ) {
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
				wp_register_style( 'sbps-token-style', USCES_FRONT_PLUGIN_URL . '/css/sbps_token.css' );
				wp_enqueue_style( 'sbps-token-style' );
				wp_register_script( 'usces_cart_sbps', USCES_FRONT_PLUGIN_URL . '/js/cart_sbps.js', array( 'jquery' ), USCES_VERSION, true );
				$sbps_params                    = array();
				$sbps_params['sbps_merchantId'] = $acting_opts['merchant_id'];
				$sbps_params['sbps_serviceId']  = $acting_opts['service_id'];
				$sbps_params['message']         = array(
					'error_token'       => __( 'Credit card information is not appropriate.', 'usces' ),
					'error_card_number' => __( 'The card number is not a valid credit card number.', 'usces' ),
					'error_card_expym'  => __( 'The card\'s expiration date is invalid.', 'usces' ),
					'error_card_seccd'  => __( 'The card\'s security code is invalid.', 'usces' ),
				);
				wp_localize_script( 'usces_cart_sbps', 'sbps_params', $sbps_params );
				wp_enqueue_script( 'usces_cart_sbps' );
			}
		}
	}

	/**
	 * カード情報入力チェック
	 * usces_filter_delivery_check
	 *
	 * @param  string $mes Validation check message.
	 * @return string
	 */
	public function delivery_check( $mes ) {
		global $usces;

		if ( ! isset( $_POST['offer']['payment_name'] ) ) {
			return $mes;
		}

		if ( ! empty( $mes ) ) {
			return $mes;
		}

		$payment = $usces->getPayments( $_POST['offer']['payment_name'] );
		if ( $this->acting_flg_card === $payment['settlement'] ) {
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
				if ( isset( $_POST['cust_quick'] ) && 'quick' === $_POST['cust_quick'] ) {
				} else {
					if ( ( isset( $_POST['token'] ) && empty( $_POST['token'] ) ) &&
						( isset( $_POST['tokenKey'] ) && empty( $_POST['tokenKey'] ) ) ) {
						$mes .= __( 'Please enter the card information correctly.', 'usces' ) . '<br />';
					} else {
						if ( ! wel_check_credit_security() ) {
							$mes .= __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '<br />';
						}
					}
				}
			}
		}
		return $mes;
	}

	/**
	 * 支払方法ページ用入力フォーム
	 * usces_filter_delivery_secure_form_loop
	 *
	 * @param  string $nouse Empty.
	 * @param  array  $payment Payment data.
	 * @return string
	 */
	public function delivery_secure_form_loop( $nouse, $payment ) {
		global $usces;

		$html = '';
		if ( $this->acting_flg_card === $payment['settlement'] ) {
			$acting_opts = $this->get_acting_settings();
			if ( ( ! isset( $acting_opts['activate'] ) || 'on' !== $acting_opts['activate'] ) ||
				( ! isset( $acting_opts['card_activate'] ) || 'token' !== $acting_opts['card_activate'] ) ||
				'activate' !== $payment['use'] ) {
				return $html;
			}

			$re_enter = ( isset( $_REQUEST['re_enter'] ) && false !== strpos( $_REQUEST['re_enter'], $this->paymod_id . '_card' ) ) ? true : false;

			$cust_code = '';
			if ( usces_is_login() ) {
				$member    = $usces->get_member();
				$cust_code = $member['ID'];
			}

			$cust_ref = array();
			if ( 'on' === $acting_opts['cust_manage'] || 'choice' === $acting_opts['cust_manage'] ) {
				if ( ! empty( $cust_code ) ) {
					$cust_ref = $this->api_customer_reference( $cust_code );
				}
			}

			$html .= '<table class="customer_form" id="' . $this->paymod_id . '_form">';
			$html .= '<tr><th scope="row">' . __( 'Credit card information', 'usces' ) . '</th><td>';

			if ( 'off' !== $acting_opts['cust_manage'] && ! empty( $cust_ref['cc_number'] ) ) {
				$cardlast4 = substr( $cust_ref['cc_number'], -4 );
				$html     .= '<p><label><input type="radio" name="cust_quick" class="cust_quick" id="cust_quick_use" value="quick"><span>登録済みのクレジットカードを使う</span></label></p>';
				$html     .= '<div class="sbps_registerd_card_area">';
				$html     .= '<div>登録済みのカード番号下4桁<span class="cc_cardlast4">' . $cardlast4 . '</span></div>';
				$html     .= '</div>';
				$html     .= '<p><label><input type="radio" name="cust_quick" class="cust_quick" id="cust_quick_new" value="new"><span>新しいクレジットカードを使う</span></label></p>';
			}

			$html .= '<div class="sbps_new_card_area">';
			$html .= '<dl>';
			$html .= '<dt>' . __( 'card number', 'usces' ) . '</dt>
					<dd><input type="tel" class="cc_number" id="cc_number" maxlength="16" value=""></dd>';

			if ( 'off' !== $acting_opts['cust_manage'] ) {
				if ( usces_have_regular_order() || usces_have_continue_charge() ) {
					$html .= '<input type="hidden" name="cust_manage" value="save" />';
				} elseif ( 'on' === $acting_opts['cust_manage'] ) {
					$html .= '<input type="hidden" name="cust_manage" value="save" />';
				} elseif ( 'choice' === $acting_opts['cust_manage'] ) {
					if ( ! empty( $cust_ref['cc_number'] ) ) {
						$html .= '<dd><label><input type="checkbox" name="cust_manage" id="cust_manage" value="change"><span id="cust_manage_label">登録済のカードを変更して購入する</span></label></dd>';
					} else {
						$html .= '<dd><label><input type="checkbox" name="cust_manage" id="cust_manage" value="save"><span id="cust_manage_label">クレジットカードを登録して購入する</span></label></dd>';
					}
				}
			}
			$cardno_attention = apply_filters( 'usces_filter_cardno_attention', '<dd><div class="attention">' . __( '* Please do not enter symbols or letters other than numbers such as space (blank), hyphen (-) between numbers.', 'usces' ) . '</div></dd>' );
			$html            .= $cardno_attention;

			$html .= '<dt>' . __( 'Card expiration', 'usces' ) . '</dt>
					<dd><select class="cc_expmm" id="cc_expmm">
							<option value="">--</option>';
			for ( $i = 1; $i <= 12; $i++ ) {
				$html .= '
							<option value="' . sprintf( '%02d', $i ) . '">' . sprintf( '%02d', $i ) . '</option>';
			}
			$html .= '
						</select>' . __( 'month', 'usces' ) . '&nbsp;
						<select class="cc_expyy" id="cc_expyy">
							<option value="">----</option>';
			for ( $i = 0; $i <= 15; $i++ ) {
				$year  = wp_date( 'Y' ) + $i;
				$html .= '
							<option value="' . $year . '">' . $year . '</option>';
			}
			$html           .= '
						</select>' . __( 'year', 'usces' ) . '</dd>
					<dt>' . __( 'security code', 'usces' ) . '</dt>
					<dd><input type="tel" class="cc_seccd" id="cc_seccd" maxlength="4" value=""></dd>';
			$seccd_attention = apply_filters( 'usces_filter_seccd_attention', '' );
			$html           .= $seccd_attention;
			$html           .= '
				</dl>
				</div>';
			$html           .= '
				<input type="hidden" name="acting" value="' . $this->paymod_id . '" />
				<input type="hidden" name="confirm" value="confirm" />
				<input type="hidden" name="token" id="token" value="" />
				<input type="hidden" name="tokenKey" id="tokenKey" value="" />';
			$html           .= '</td></tr>';
			$html           .= '</table>';
		}
		return $html;
	}

	/**
	 * 会員データ編集画面 カード情報登録情報
	 * usces_action_admin_member_info
	 *
	 * @param  array $member Member data.
	 * @param  array $member_metas Member meta data.
	 * @param  array $usces_member_history Member's history order data.
	 */
	public function admin_member_info( $member, $member_metas, $usces_member_history ) {
		if ( 0 < count( $usces_member_history ) ) :
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) ) :
				$cust_ref = $this->api_customer_reference( $member['ID'] );
				if ( isset( $cust_ref['result'] ) && 'OK' === $cust_ref['result'] ) :
					$cardlast4 = substr( $cust_ref['cc_number'], -4 );
					$expyy     = substr( $cust_ref['cc_expiration'], 0, 4 );
					$expmm     = substr( $cust_ref['cc_expiration'], 4, 2 );
					?>
		<tr>
			<td class="label"><?php esc_html_e( 'Lower 4 digits', 'usces' ); ?></td>
			<td><div class="rod_left shortm"><?php echo esc_html( $cardlast4 ); ?></div></td>
		</tr>
		<tr>
			<td class="label"><?php esc_html_e( 'Expiration date', 'usces' ); ?></td>
			<td><div class="rod_left shortm"><?php echo esc_html( $expyy . '/' . $expmm ); ?></div></td>
		</tr>
		<tr>
			<td class="label">カード情報</td>
			<td><div class="rod_left shortm"><?php esc_html_e( 'Registered', 'usces' ); ?></div></td>
		</tr>
					<?php
					if ( ! usces_have_member_continue_order( $member['ID'] ) && ! usces_have_member_regular_order( $member['ID'] ) ) :
						?>
		<tr>
			<td class="label"><input type="checkbox" name="sbps_quick" id="sbps-quick-release" value="release"></td>
			<td><label for="sbps-quick-release">カード情報の登録を解除する</label></td>
		</tr>
						<?php
					endif;
				endif;
			endif;
		endif;
	}

	/**
	 * 会員データ編集画面 カード情報登録解除
	 * usces_action_post_update_memberdata
	 *
	 * @param  string  $member_id Member ID.
	 * @param  boolean $res Deletable.
	 */
	public function admin_update_memberdata( $member_id, $res ) {
		if ( ! $this->is_activate_card() || false === $res ) {
			return;
		}
		if ( isset( $_POST['sbps_quick'] ) && 'release' === $_POST['sbps_quick'] ) {
			$this->api_customer_delete( $member_id );
		}
	}

	/**
	 * Get order_id by meta_data
	 *
	 * @param  string $res_tracking_id Tracking ID.
	 * @return int
	 */
	protected function get_order_id( $res_tracking_id ) {
		global $wpdb;

		$order_meta_table_name = $wpdb->prefix . 'usces_order_meta';
		$query                 = $wpdb->prepare( "SELECT `order_id` FROM $order_meta_table_name WHERE `meta_key` = %s AND `meta_value` = %s", 'res_tracking_id', $res_tracking_id );
		$order_id              = $wpdb->get_var( $query );
		return $order_id;
	}

	/**
	 * Get order_status by order data
	 *
	 * @param  int $order_id Order number.
	 * @return string
	 */
	protected function get_order_status( $order_id ) {
		global $wpdb;

		$order_table_name = $wpdb->prefix . 'usces_order';
		$query            = $wpdb->prepare( "SELECT `order_status` FROM $order_table_name WHERE `ID` = %d", $order_id );
		$order_status     = $wpdb->get_var( $query );
		return $order_status;
	}

	/**
	 * Get customers card data
	 *
	 * @param  string $cust_code Member ID.
	 * @return array
	 */
	protected function api_customer_reference( $cust_code ) {
		global $wpdb;

		$cust_ref    = array();
		$acting_opts = $this->get_acting_settings();
		if ( empty( $acting_opts['3des_key'] ) || empty( $acting_opts['3desinit_key'] ) ) {
			return $cust_ref;
		}
		$connection = $this->get_connection();

		$sps_cust_info_return_flg = '1';
		$response_info_type       = '2';
		$cardbrand_return_flg     = '0';
		$encrypted_flg            = '1';
		$request_date             = wp_date( 'YmdHis' );
		$sps_hashcode             = $acting_opts['merchant_id'] . $acting_opts['service_id'] . $cust_code . $sps_cust_info_return_flg . $response_info_type . $cardbrand_return_flg . $encrypted_flg . $request_date . $acting_opts['hash_key'];
		$sps_hashcode             = sha1( $sps_hashcode );

		/* クレジットカード情報参照要求 */
		$request_cust_ref  = '<?xml version="1.0" encoding="Shift_JIS"?>
<sps-api-request id="MG02-00104-101">
	<merchant_id>' . $acting_opts['merchant_id'] . '</merchant_id>
	<service_id>' . $acting_opts['service_id'] . '</service_id>
	<cust_code>' . $cust_code . '</cust_code>
	<sps_cust_info_return_flg>' . $sps_cust_info_return_flg . '</sps_cust_info_return_flg>
	<response_info_type>' . $response_info_type . '</response_info_type>
	<pay_option_manage>
		<cardbrand_return_flg>' . $cardbrand_return_flg . '</cardbrand_return_flg>
	</pay_option_manage>
	<encrypted_flg>' . $encrypted_flg . '</encrypted_flg>
	<request_date>' . $request_date . '</request_date>
	<sps_hashcode>' . $sps_hashcode . '</sps_hashcode>
</sps-api-request>';
		$xml_cust_ref      = $this->get_xml_response( $connection['api_url'], $request_cust_ref );
		$response_cust_ref = $this->xml2assoc( $xml_cust_ref, $this->acting_card );
		if ( isset( $response_cust_ref['res_result'] ) ) {
			$cust_ref['result'] = $response_cust_ref['res_result'];
			if ( 'OK' === $response_cust_ref['res_result'] ) {
				if ( isset( $response_cust_ref['res_pay_method_info']['cc_number'] ) && ! empty( $acting_opts['3des_key'] ) && ! empty( $acting_opts['3desinit_key'] ) ) {
					$cc_number             = base64_decode( $response_cust_ref['res_pay_method_info']['cc_number'] );
					$cust_ref['cc_number'] = openssl_decrypt( $cc_number, 'des-ede3-cbc', $acting_opts['3des_key'], OPENSSL_NO_PADDING, $acting_opts['3desinit_key'] );
				}
				if ( isset( $response_cust_ref['res_pay_method_info']['cc_expiration'] ) && ! empty( $acting_opts['3des_key'] ) && ! empty( $acting_opts['3desinit_key'] ) ) {
					$cc_expiration             = base64_decode( $response_cust_ref['res_pay_method_info']['cc_expiration'] );
					$cust_ref['cc_expiration'] = openssl_decrypt( $cc_expiration, 'des-ede3-cbc', $acting_opts['3des_key'], OPENSSL_NO_PADDING, $acting_opts['3desinit_key'] );
				}
				if ( isset( $response_cust_ref['res_sps_info']['cc_number'] ) ) {
					$cust_ref['sps_cust_no'] = $response_cust_ref['res_sps_info']['res_sps_cust_no'];
				}
			} else {
				if ( isset( $response_cust_ref['res_err_code'] ) ) {
					$cust_ref['err_code'] = $response_cust_ref['res_err_code'];
				}
			}
		}
		return $cust_ref;
	}

	/**
	 * Delete customers card data
	 *
	 * @param  string $cust_code Member ID.
	 * @return boolean
	 */
	protected function api_customer_delete( $cust_code ) {
		$res      = false;
		$cust_ref = $this->api_customer_reference( $cust_code );
		if ( isset( $cust_ref['result'] ) && 'OK' === $cust_ref['result'] ) {
			$acting_opts = $this->get_acting_settings();
			$connection  = $this->get_connection();

			$sps_cust_info_return_flg = '0';
			$request_date             = wp_date( 'YmdHis' );
			$sps_hashcode             = $acting_opts['merchant_id'] . $acting_opts['service_id'] . $cust_code . $sps_cust_info_return_flg . $request_date . $acting_opts['hash_key'];
			$sps_hashcode             = sha1( $sps_hashcode );

			/* クレジットカード情報削除要求 */
			$request_cust_del  = '<?xml version="1.0" encoding="Shift_JIS"?>
<sps-api-request id="MG02-00103-101">
	<merchant_id>' . $acting_opts['merchant_id'] . '</merchant_id>
	<service_id>' . $acting_opts['service_id'] . '</service_id>
	<cust_code>' . $cust_code . '</cust_code>
	<sps_cust_info_return_flg>' . $sps_cust_info_return_flg . '</sps_cust_info_return_flg>
	<request_date>' . $request_date . '</request_date>
	<sps_hashcode>' . $sps_hashcode . '</sps_hashcode>
</sps-api-request>';
			$xml_cust_del      = $this->get_xml_response( $connection['api_url'], $request_cust_del );
			$response_cust_del = $this->xml2assoc( $xml_cust_del, $this->acting_card );
			if ( isset( $response_cust_del['res_result'] ) && 'OK' === $response_cust_del['res_result'] ) {
				$res = true;
			}
		}
		return $res;
	}
}
