<?php
/**
 * ペイジェント決済モジュール
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    2.2.6
 */
require_once USCES_PLUGIN_DIR . '/classes/pageant.config.php';
require_once USCES_PLUGIN_DIR . '/classes/paymentPaygent.module.class.php';

/**
 * ペイジェント決済モジュール
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class PAYGENT_SETTLEMENT {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

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
	 * 決済代行会社URL
	 *
	 * @var string
	 */
	protected $acting_company_url;

	/**
	 * 併用不可決済モジュール
	 *
	 * @var array
	 */
	protected $unavailable_method;

	/**
	 * エラーメッセージ
	 *
	 * @var string
	 */
	protected $error_mes;

	/**
	 * 自動継続課金処理結果メール
	 *
	 * @var array
	 */
	protected $continuation_charging_mail;

	/**
	 * コンビニ接続タイプA（企業コード：CVSタイプ）
	 *
	 * @var array
	 */
	protected $cvs_type_a = array(
		CODE_SEICOMART   => '01',
		CODE_LOWSON      => '02',
		CODE_MINISTOP    => '02',
		CODE_YAMAZAKI    => '02',
		CODE_SEVENELEVEN => '03',
		CODE_FAMILYMART  => '04',
	);

	/**
	 * コンビニ接続タイプA（企業コード：コンビニ）
	 *
	 * @var array
	 */
	protected $cvs_company_a = array(
		CODE_SEICOMART   => 'セイコーマート',
		CODE_LOWSON      => 'ローソン',
		CODE_MINISTOP    => 'ミニストップ',
		CODE_YAMAZAKI    => 'デイリーヤマザキ',
		CODE_SEVENELEVEN => 'セブンイレブン',
		CODE_FAMILYMART  => 'ファミリーマート',
	);

	/**
	 * 銀行ネット決済支払期間
	 *
	 * @var array
	 */
	protected $asp_payment_term = array(
		'0000030' => '即日購入後 30分以内',
		'0000060' => '即日購入後 1時間以内',
		'0000120' => '即日購入後 2時間以内',
		'0000180' => '即日購入後 3時間以内',
		'0000360' => '即日購入後 6時間以内',
		'0010000' => '購入後 24時間以内',
		'0020000' => '購入後 2日以内',
		'0030000' => '購入後 3日以内',
		'0050000' => '購入後 5日以内',
		'0070000' => '購入後 7日以内',
		'0140000' => '購入後 14日以内',
	);

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->paymod_id          = 'paygent';
		$this->pay_method         = array(
			'acting_paygent_card',
			'acting_paygent_conv',
			'acting_paygent_atm',
			'acting_paygent_bank',
			'acting_paygent_paidy',
		);
		$this->acting_name        = 'ペイジェント';
		$this->acting_formal_name = 'ペイジェント';
		$this->acting_company_url = 'https://www.paygent.co.jp/';

		$this->initialize_data();

		if ( is_admin() ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_ajax_check_file_path', array( $this, 'check_file_path' ) );
			add_action( 'wp_ajax_upload_certificate_file', array( $this, 'upload_certificate_file' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if ( $this->is_validity_acting() ) {
			add_action( 'usces_after_cart_instant', array( $this, 'acting_transaction' ), 11 );
			add_filter( 'usces_filter_is_complete_settlement', array( $this, 'is_complete_settlement' ), 10, 3 );
			if ( is_admin() ) {
				add_action( 'usces_action_admin_ajax', array( $this, 'admin_ajax' ) );
				add_filter( 'usces_filter_orderlist_detail_value', array( $this, 'orderlist_settlement_status' ), 10, 4 );
				add_action( 'usces_action_order_edit_form_status_block_middle', array( $this, 'settlement_status' ), 10, 3 );
				add_action( 'usces_action_order_edit_form_settle_info', array( $this, 'settlement_information' ), 10, 2 );
				add_action( 'usces_action_endof_order_edit_form', array( $this, 'settlement_dialog' ), 10, 2 );
				add_filter( 'usces_filter_settle_info_field_meta_keys', array( $this, 'settlement_info_field_meta_keys' ) );
				add_filter( 'usces_filter_settle_info_field_keys', array( $this, 'settlement_info_field_keys' ), 10, 2 );
				add_filter( 'usces_filter_settle_info_field_value', array( $this, 'settlement_info_field_value' ), 10, 3 );
				add_filter( 'usces_filter_order_confirm_mail_payment', array( $this, 'order_confirm_mail_payment' ), 10, 5 );
				add_filter( 'usces_filter_get_link_key', array( $this, 'get_link_key' ), 10, 2 );
				add_action( 'usces_action_revival_order_data', array( $this, 'revival_orderdata' ), 10, 3 );
			} else {
				add_filter( 'usces_filter_payment_detail', array( $this, 'payment_detail' ), 10, 2 );
				add_filter( 'usces_filter_payments_str', array( $this, 'payments_str' ), 10, 2 );
				add_filter( 'usces_filter_payments_arr', array( $this, 'payments_arr' ), 10, 2 );
				add_filter( 'usces_filter_confirm_inform', array( $this, 'confirm_inform' ), 10, 5 );
				add_action( 'usces_action_confirm_page_point_inform', array( $this, 'e_point_inform' ), 10, 5 );
				add_filter( 'usces_filter_confirm_point_inform', array( $this, 'point_inform' ), 10, 5 );
				if ( defined( 'WCEX_COUPON' ) ) {
					add_filter( 'wccp_filter_coupon_inform', array( $this, 'point_inform' ), 10, 5 );
				}
				add_action( 'usces_pre_purchase', array( $this, 'pre_purchase' ), 1 );
				add_action( 'usces_action_acting_processing', array( $this, 'acting_processing' ), 10, 2 );
				add_filter( 'usces_filter_check_acting_return_results', array( $this, 'acting_return' ) );
				add_filter( 'usces_filter_check_acting_return_duplicate', array( $this, 'check_acting_return_duplicate' ), 10, 2 );
				add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
				add_filter( 'usces_filter_get_error_settlement', array( $this, 'error_page_message' ) );
				add_filter( 'usces_filter_send_order_mail_payment', array( $this, 'order_mail_payment' ), 10, 6 );
			}

			if ( $this->is_validity_acting( 'card' ) ) {
				if ( $this->is_activate_card( 'module' ) ) {
					add_action( 'init', array( $this, 'done_3ds_auth' ) );
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
					add_action( 'wp_print_footer_scripts', array( $this, 'footer_scripts' ), 9 );
					add_filter( 'usces_filter_uscesL10n', array( $this, 'set_uscesL10n' ), 12, 2 );
					add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
					add_filter( 'usces_filter_delivery_secure_form_loop', array( $this, 'delivery_secure_form_loop' ), 10, 2 );
					add_filter( 'usces_filter_save_order_acting_data', array( $this, 'save_order_acting_data' ) );
					add_filter( 'usces_filter_delete_member_check', array( $this, 'delete_member_check' ), 10, 2 );
					add_action( 'usces_action_pre_delete_memberdata', array( $this, 'delete_member' ) );
				}
				add_filter( 'usces_filter_template_redirect', array( $this, 'member_update_settlement' ), 1 );
				add_action( 'usces_action_member_submenu_list', array( $this, 'e_update_settlement' ) );
				add_filter( 'usces_filter_member_submenu_list', array( $this, 'update_settlement' ), 10, 2 );
				if ( is_admin() ) {
					add_action( 'usces_action_admin_member_info', array( $this, 'admin_member_info' ), 10, 3 );
					add_action( 'usces_action_post_update_memberdata', array( $this, 'admin_update_memberdata' ), 10, 2 );
				}

				/* WCEX DL Seller */
				if ( defined( 'WCEX_DLSELLER' ) ) {
					add_filter( 'usces_filter_the_continue_payment_method', array( $this, 'continuation_payment_method' ) );
					add_filter( 'dlseller_filter_first_charging', array( $this, 'first_charging_date' ), 9, 5 );
					add_filter( 'dlseller_filter_the_payment_method_restriction', array( $this, 'payment_method_restriction' ), 10, 2 );
					add_filter( 'dlseller_filter_continue_member_list_condition', array( $this, 'continue_member_list_condition' ), 10, 4 );
					add_action( 'dlseller_action_continue_member_list_page', array( $this, 'continue_member_list_page' ) );
					add_action( 'dlseller_action_do_continuation_charging', array( $this, 'auto_continuation_charging' ), 10, 4 );
					add_action( 'dlseller_action_do_continuation', array( $this, 'do_auto_continuation' ), 10, 2 );
					add_filter( 'dlseller_filter_reminder_mail_body', array( $this, 'reminder_mail_body' ), 10, 3 );
					add_filter( 'dlseller_filter_contract_renewal_mail_body', array( $this, 'contract_renewal_mail_body' ), 10, 3 );
				}

				/* WCEX Auto Delivery */
				if ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
					if ( is_admin() && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.2.3', '<' ) ) {
						add_filter( 'wcad_filter_admin_notices', array( $this, 'admin_notices_autodelivery' ), 11 );
					}
					add_filter( 'wcad_filter_shippinglist_acting', array( $this, 'set_shippinglist_acting' ) );
					add_filter( 'wcad_filter_available_regular_payment_method', array( $this, 'available_regular_payment_method' ) );
					add_filter( 'wcad_filter_the_payment_method_restriction', array( $this, 'payment_method_restriction' ), 10, 2 );
					add_action( 'wcad_action_reg_auto_orderdata', array( $this, 'register_auto_orderdata' ) );
				}
			}

			if ( $this->is_validity_acting( 'conv' ) ) {
				add_filter( 'usces_filter_noreceipt_status', array( $this, 'noreceipt_status' ) );
				if ( $this->is_activate_conv( 'module' ) ) {
					add_action( 'wp_print_footer_scripts', array( $this, 'footer_scripts' ), 9 );
					add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
					add_filter( 'usces_filter_delivery_secure_form_loop', array( $this, 'delivery_secure_form_loop' ), 10, 2 );
					add_filter( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
				}
			}

			if ( $this->is_validity_acting( 'atm' ) ) {
				add_filter( 'usces_filter_noreceipt_status', array( $this, 'noreceipt_status' ) );
				if ( $this->is_activate_atm() ) {
					add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
					add_filter( 'usces_filter_delivery_secure_form_loop', array( $this, 'delivery_secure_form_loop' ), 10, 2 );
					add_filter( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
				}
			}

			if ( $this->is_validity_acting( 'bank' ) ) {
				add_filter( 'usces_filter_noreceipt_status', array( $this, 'noreceipt_status' ) );
				if ( $this->is_activate_bank() ) {
					add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
					add_filter( 'usces_filter_delivery_secure_form_loop', array( $this, 'delivery_secure_form_loop' ), 10, 2 );
				}
			}

			if ( $this->is_validity_acting( 'paidy' ) ) {
				if ( $this->is_activate_paidy() ) {
					add_action( 'wp_print_footer_scripts', array( $this, 'footer_scripts' ), 9 );
				}
			}
		}
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize.
	 */
	public function initialize_data() {
		$options = get_option( 'usces', array() );
		$options['acting_settings']['paygent']['activate']             = ( isset( $options['acting_settings']['paygent']['activate'] ) ) ? $options['acting_settings']['paygent']['activate'] : 'off';
		$options['acting_settings']['paygent']['seq_merchant_id']      = ( isset( $options['acting_settings']['paygent']['seq_merchant_id'] ) ) ? $options['acting_settings']['paygent']['seq_merchant_id'] : '';
		$options['acting_settings']['paygent']['connect_id']           = ( isset( $options['acting_settings']['paygent']['connect_id'] ) ) ? $options['acting_settings']['paygent']['connect_id'] : '';
		$options['acting_settings']['paygent']['connect_password']     = ( isset( $options['acting_settings']['paygent']['connect_password'] ) ) ? $options['acting_settings']['paygent']['connect_password'] : '';
		$options['acting_settings']['paygent']['telegram_version']     = TELEGRAM_VERSION;
		$options['acting_settings']['paygent']['hc']                   = ( isset( $options['acting_settings']['paygent']['hc'] ) ) ? $options['acting_settings']['paygent']['hc'] : '';
		$options['acting_settings']['paygent']['conv_hc']              = ( isset( $options['acting_settings']['paygent']['conv_hc'] ) ) ? $options['acting_settings']['paygent']['conv_hc'] : '';
		$options['acting_settings']['paygent']['ope']                  = ( isset( $options['acting_settings']['paygent']['ope'] ) ) ? $options['acting_settings']['paygent']['ope'] : '';
		$options['acting_settings']['paygent']['card_activate']        = ( isset( $options['acting_settings']['paygent']['card_activate'] ) ) ? $options['acting_settings']['paygent']['card_activate'] : 'off';
		$options['acting_settings']['paygent']['token_key']            = ( isset( $options['acting_settings']['paygent']['token_key'] ) ) ? $options['acting_settings']['paygent']['token_key'] : '';
		$options['acting_settings']['paygent']['token_hc']             = ( isset( $options['acting_settings']['paygent']['token_hc'] ) ) ? $options['acting_settings']['paygent']['token_hc'] : '';
		$options['acting_settings']['paygent']['certificate_path']     = ( isset( $options['acting_settings']['paygent']['certificate_path'] ) ) ? $options['acting_settings']['paygent']['certificate_path'] : '';
		$options['acting_settings']['paygent']['client_file']          = ( isset( $options['acting_settings']['paygent']['client_file'] ) ) ? $options['acting_settings']['paygent']['client_file'] : '';
		$options['acting_settings']['paygent']['ca_file']              = ( isset( $options['acting_settings']['paygent']['ca_file'] ) ) ? $options['acting_settings']['paygent']['ca_file'] : '';
		$options['acting_settings']['paygent']['payment_class']        = ( isset( $options['acting_settings']['paygent']['payment_class'] ) ) ? $options['acting_settings']['paygent']['payment_class'] : '';
		$options['acting_settings']['paygent']['use_card_conf_number'] = ( isset( $options['acting_settings']['paygent']['use_card_conf_number'] ) ) ? $options['acting_settings']['paygent']['use_card_conf_number'] : '';
		$options['acting_settings']['paygent']['stock_card_mode']      = ( isset( $options['acting_settings']['paygent']['stock_card_mode'] ) ) ? $options['acting_settings']['paygent']['stock_card_mode'] : '';
		$options['acting_settings']['paygent']['sales_mode']           = ( isset( $options['acting_settings']['paygent']['sales_mode'] ) ) ? $options['acting_settings']['paygent']['sales_mode'] : '0';
		$options['acting_settings']['paygent']['sales_mode_dlseller']  = ( isset( $options['acting_settings']['paygent']['sales_mode_dlseller'] ) ) ? $options['acting_settings']['paygent']['sales_mode_dlseller'] : '0';
		$options['acting_settings']['paygent']['auto_settlement_mail'] = ( isset( $options['acting_settings']['paygent']['auto_settlement_mail'] ) ) ? $options['acting_settings']['paygent']['auto_settlement_mail'] : 'off';
		$options['acting_settings']['paygent']['threedsecure_ryaku']   = ( isset( $options['acting_settings']['paygent']['threedsecure_ryaku'] ) ) ? $options['acting_settings']['paygent']['threedsecure_ryaku'] : 'off';
		$options['acting_settings']['paygent']['threedsecure_hc']      = ( isset( $options['acting_settings']['paygent']['threedsecure_hc'] ) ) ? $options['acting_settings']['paygent']['threedsecure_hc'] : '';
		$options['acting_settings']['paygent']['attempt']              = ( isset( $options['acting_settings']['paygent']['attempt'] ) ) ? $options['acting_settings']['paygent']['attempt'] : 'on';
		$options['acting_settings']['paygent']['conv_activate']        = ( isset( $options['acting_settings']['paygent']['conv_activate'] ) ) ? $options['acting_settings']['paygent']['conv_activate'] : 'off';
		$options['acting_settings']['paygent']['payment_term_day']     = ( isset( $options['acting_settings']['paygent']['payment_term_day'] ) ) ? $options['acting_settings']['paygent']['payment_term_day'] : '';
		$options['acting_settings']['paygent']['payment_term_min']     = ( isset( $options['acting_settings']['paygent']['payment_term_min'] ) ) ? $options['acting_settings']['paygent']['payment_term_min'] : '';
		$options['acting_settings']['paygent']['sales_type']           = ( isset( $options['acting_settings']['paygent']['sales_type'] ) ) ? $options['acting_settings']['paygent']['sales_type'] : '1';
		$options['acting_settings']['paygent']['cvs_type']             = ( ! empty( $options['acting_settings']['paygent']['cvs_type'] ) ) ? $options['acting_settings']['paygent']['cvs_type'] : array( '01', '02', '03', '04' );
		$options['acting_settings']['paygent']['atm_activate']         = ( isset( $options['acting_settings']['paygent']['atm_activate'] ) ) ? $options['acting_settings']['paygent']['atm_activate'] : 'off';
		$options['acting_settings']['paygent']['payment_limit_date']   = ( isset( $options['acting_settings']['paygent']['payment_limit_date'] ) ) ? $options['acting_settings']['paygent']['payment_limit_date'] : '';
		$options['acting_settings']['paygent']['bank_activate']        = ( isset( $options['acting_settings']['paygent']['bank_activate'] ) ) ? $options['acting_settings']['paygent']['bank_activate'] : 'off';
		$options['acting_settings']['paygent']['asp_payment_term']     = ( isset( $options['acting_settings']['paygent']['asp_payment_term'] ) ) ? $options['acting_settings']['paygent']['asp_payment_term'] : '0050000';
		$options['acting_settings']['paygent']['paidy_activate']       = ( isset( $options['acting_settings']['paygent']['paidy_activate'] ) ) ? $options['acting_settings']['paygent']['paidy_activate'] : 'off';
		$options['acting_settings']['paygent']['paidy_public_key']     = ( isset( $options['acting_settings']['paygent']['paidy_public_key'] ) ) ? $options['acting_settings']['paygent']['paidy_public_key'] : '';
		if ( empty( $options['acting_settings']['paygent']['certificate_path'] ) ) {
			$upload_dir = wp_upload_dir();
			$dir        = $this->create_certificate_path();
			$options['acting_settings']['paygent']['certificate_path'] = $upload_dir['basedir'] . $dir;
		}
		update_option( 'usces', $options );

		$available_settlement = get_option( 'usces_available_settlement' );
		if ( ! in_array( $this->paymod_id, $available_settlement ) ) {
			$available_settlement[ $this->paymod_id ] = $this->acting_formal_name;
			update_option( 'usces_available_settlement', $available_settlement );
		}

		$this->unavailable_method = array( 'acting_paidy' );
	}

	/**
	 * 証明書ファイルパス名生成
	 *
	 * @return string
	 */
	private function create_certificate_path() {
		$dir = '/p' . substr( str_shuffle( '1234567890abcdefghijklmnopqrstuvwxyz' ), 0, 12 );
		return $dir;
	}

	/**
	 * 決済有効判定
	 * 支払方法で使用している場合に true
	 *
	 * @param  string $type Module type.
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
					if ( 'acting_paygent_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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
					if ( 'acting_paygent_conv' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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
			case 'atm':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_paygent_atm' == $payment['settlement'] && 'activate' == $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_atm() ) {
					return true;
				} else {
					return false;
				}
				break;
			case 'bank':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_paygent_bank' == $payment['settlement'] && 'activate' == $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_bank() ) {
					return true;
				} else {
					return false;
				}
				break;
			case 'paidy':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_paygent_paidy' == $payment['settlement'] && 'activate' == $payment['use'] ) {
						$method = true;
						break;
					}
				}
				if ( $method && $this->is_activate_paidy() ) {
					return true;
				} else {
					return false;
				}
				break;
			default:
				if ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) {
					return true;
				} else {
					return false;
				}
		}
	}

	/**
	 * クレジットカード決済有効判定
	 *
	 * @param  string $type 'link'|'module'.
	 * @return boolean
	 */
	public function is_activate_card( $type = '' ) {
		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) {
			if ( empty( $type ) ) {
				if ( isset( $acting_opts['card_activate'] ) && ( 'off' != $acting_opts['card_activate'] ) ) {
					$res = true;
				} else {
					$res = false;
				}
			} elseif ( 'link' == $type ) {
				if ( isset( $acting_opts['card_activate'] ) && ( 'on' == $acting_opts['card_activate'] ) ) {
					$res = true;
				} else {
					$res = false;
				}
			} elseif ( 'module' == $type ) {
				if ( isset( $acting_opts['card_activate'] ) && ( 'module' == $acting_opts['card_activate'] ) ) {
					$res = true;
				} else {
					$res = false;
				}
			} else {
				$res = false;
			}
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * コンビニ決済有効判定
	 *
	 * @param  string $type 'link'|'module'.
	 * @return boolean
	 */
	public function is_activate_conv( $type = '' ) {
		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) {
			if ( empty( $type ) ) {
				if ( isset( $acting_opts['conv_activate'] ) && ( 'off' != $acting_opts['conv_activate'] ) ) {
					$res = true;
				} else {
					$res = false;
				}
			} elseif ( 'link' == $type ) {
				if ( isset( $acting_opts['conv_activate'] ) && ( 'on' == $acting_opts['conv_activate'] ) ) {
					$res = true;
				} else {
					$res = false;
				}
			} elseif ( 'module' == $type ) {
				if ( isset( $acting_opts['conv_activate'] ) && ( 'module' == $acting_opts['conv_activate'] ) ) {
					$res = true;
				} else {
					$res = false;
				}
			} else {
				$res = false;
			}
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * ATM決済（Pay-easy）有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_atm() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) &&
			( isset( $acting_opts['atm_activate'] ) && 'module' == $acting_opts['atm_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * 銀行ネット決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_bank() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) &&
			( isset( $acting_opts['bank_activate'] ) && 'module' == $acting_opts['bank_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * Paidy決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_paidy() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) &&
			( isset( $acting_opts['paidy_activate'] ) && 'module' == $acting_opts['paidy_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * 未入金ステータス
	 * usces_filter_noreceipt_status
	 *
	 * @param  array $noreceipt_status Receive payment notification.
	 * @return array
	 */
	public function noreceipt_status( $noreceipt_status ) {
		if ( ! in_array( 'acting_paygent_conv', $noreceipt_status ) || ! in_array( 'acting_paygent_atm', $noreceipt_status ) || ! in_array( 'acting_paygent_bank', $noreceipt_status ) ) {
			$noreceipt_status[] = 'acting_paygent_conv';
			$noreceipt_status[] = 'acting_paygent_atm';
			$noreceipt_status[] = 'acting_paygent_bank';
			$noreceipt_status   = array_unique( $noreceipt_status );
			update_option( 'usces_noreceipt_status', $noreceipt_status );
		}
		return $noreceipt_status;
	}

	/**
	 * 管理画面スクリプト
	 * admin_print_footer_scripts
	 */
	public function admin_scripts() {
		$admin_page = ( isset( $_GET['page'] ) ) ? wp_unslash( $_GET['page'] ) : '';
		switch ( $admin_page ) :
			/* クレジット決済設定画面 */
			case 'usces_settlement':
				$settlement_selected = get_option( 'usces_settlement_selected' );
				if ( in_array( $this->paymod_id, (array) $settlement_selected ) ) :
					$acting_opts = $this->get_acting_settings();
					?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	var paygent_card_activate = "<?php echo esc_js( $acting_opts['card_activate'] ); ?>";
	var paygent_card_threedsecure = "<?php echo esc_js( $acting_opts['threedsecure_ryaku'] ); ?>";
	if ( "on" == paygent_card_activate ) {
		$( ".paygent_card_form" ).css( "display", "" );
		$( ".paygent_card_module" ).css( "display", "none" );
		$( ".paygent_card_threedsecure" ).css( "display", "none" );
	} else if ( "module" == paygent_card_activate ) {
		$( ".paygent_card_form" ).css( "display", "" );
		$( ".paygent_card_module" ).css( "display", "" );
		if ( "on" == paygent_card_threedsecure ) {
			$( ".paygent_card_threedsecure" ).css( "display", "" );
		} else {
			$( ".paygent_card_threedsecure" ).css( "display", "none" );
		}
	} else {
		$( ".paygent_card_form" ).css( "display", "none" );
		$( ".paygent_card_module" ).css( "display", "none" );
		$( ".paygent_card_threedsecure" ).css( "display", "none" );
	}
	$( document ).on( "change", ".activate_paygent_card", function() {
		if ( "on" == $( this ).val() ) {
			$( ".paygent_card_form" ).css( "display", "" );
			$( ".paygent_card_module" ).css( "display", "none" );
			$( ".paygent_card_threedsecure" ).css( "display", "none" );
		} else if ( "module" == $( this ).val() ) {
			$( ".paygent_card_form" ).css( "display", "" );
			$( ".paygent_card_module" ).css( "display", "" );
			if ( "on" == $( "input[name='threedsecure_ryaku']:checked" ).val() ) {
				$( ".paygent_card_threedsecure" ).css( "display", "" );
			} else {
				$( ".paygent_card_threedsecure" ).css( "display", "none" );
			}
		} else {
			$( ".paygent_card_form" ).css( "display", "none" );
			$( ".paygent_card_module" ).css( "display", "none" );
			$( ".paygent_card_threedsecure" ).css( "display", "none" );
		}
	});
	if ( "on" == paygent_card_threedsecure && "module" == paygent_card_activate ) {
		$( ".paygent_card_threedsecure" ).css( "display", "" );
	} else {
		$( ".paygent_card_threedsecure" ).css( "display", "none" );
	}
	$( document ).on( "change", ".activate_paygent_card_threedsecure", function() {
		if ( "module" == $( "input[name='card_activate']:checked" ).val() ) {
			if ( "on" == $( this ).val() ) {
				$( ".paygent_card_threedsecure" ).css( "display", "" );
			} else {
				$( ".paygent_card_threedsecure" ).css( "display", "none" );
			}
		}
	});

	var paygent_conv_activate = "<?php echo esc_js( $acting_opts['conv_activate'] ); ?>";
	if ( "on" == paygent_conv_activate ) {
		$( ".paygent_conv_form" ).css( "display", "" );
		$( ".paygent_conv_link" ).css( "display", "" );
		$( ".paygent_conv_module" ).css( "display", "none" );
	} else if ( "module" == paygent_conv_activate ) {
		$( ".paygent_conv_form" ).css( "display", "" );
		$( ".paygent_conv_link" ).css( "display", "none" );
		$( ".paygent_conv_module" ).css( "display", "" );
	} else {
		$( ".paygent_conv_form" ).css( "display", "none" );
		$( ".paygent_conv_link" ).css( "display", "none" );
		$( ".paygent_conv_module" ).css( "display", "none" );
	}
	$( document ).on( "change", ".activate_paygent_conv", function() {
		if ( "on" == $( this ).val() ) {
			$( ".paygent_conv_form" ).css( "display", "" );
			$( ".paygent_conv_link" ).css( "display", "" );
			$( ".paygent_conv_module" ).css( "display", "none" );
		} else if ( "module" == $( this ).val() ) {
			$( ".paygent_conv_form" ).css( "display", "" );
			$( ".paygent_conv_link" ).css( "display", "none" );
			$( ".paygent_conv_module" ).css( "display", "" );
		} else {
			$( ".paygent_conv_form" ).css( "display", "none" );
			$( ".paygent_conv_link" ).css( "display", "none" );
			$( ".paygent_conv_module" ).css( "display", "none" );
		}
	});

	var paygent_atm_activate = "<?php echo esc_js( $acting_opts['atm_activate'] ); ?>";
	if ( "module" == paygent_atm_activate ) {
		$( ".paygent_atm_form" ).css( "display", "" );
	} else {
		$( ".paygent_atm_form" ).css( "display", "none" );
	}
	$( document ).on( "change", ".activate_paygent_atm", function() {
		if ( "module" == $( this ).val() ) {
			$( ".paygent_atm_form" ).css( "display", "" );
		} else {
			$( ".paygent_atm_form" ).css( "display", "none" );
		}
	});

	var paygent_bank_activate = "<?php echo esc_js( $acting_opts['bank_activate'] ); ?>";
	if ( "module" == paygent_bank_activate ) {
		$( ".paygent_bank_form" ).css( "display", "" );
	} else {
		$( ".paygent_bank_form" ).css( "display", "none" );
	}
	$( document ).on( "change", ".activate_paygent_bank", function() {
		if ( "module" == $( this ).val() ) {
			$( ".paygent_bank_form" ).css( "display", "" );
		} else {
			$( ".paygent_bank_form" ).css( "display", "none" );
		}
	});

	var paygent_paidy_activate = "<?php echo esc_js( $acting_opts['paidy_activate'] ); ?>";
	if ( "module" == paygent_paidy_activate ) {
		$( ".paygent_paidy_form" ).css( "display", "" );
	} else {
		$( ".paygent_paidy_form" ).css( "display", "none" );
	}
	$( document ).on( "change", ".activate_paygent_paidy", function() {
		if ( "module" == $( this ).val() ) {
			$( ".paygent_paidy_form" ).css( "display", "" );
		} else {
			$( ".paygent_paidy_form" ).css( "display", "none" );
		}
	});

	$( document ).on( "click", "#change_certificate_path", function() {
		$( "#certificate_path_paygent" ).prop( "disabled", "" );
	});

	$( document ).on( "click", "#client_file_upload", function() {
		$.ajax({
			url: ajaxurl,
			type: "POST",
			cache: false,
			dataType: "json",
			data: {
				action: "check_file_path",
				path: $( "#certificate_path_paygent" ).val(),
				file: $( "#client_file_paygent" ).val(),
				wc_nonce: $( "#wc_nonce" ).val()
			}
		}).done( function( retVal, dataType ) {
			if ( "OK" == retVal.status ) {
				if ( ! retVal.file_exists ) {
					$( "#client_file_paygent" ).val( "" );
				}
				$( "#upload_filetype" ).val( "client_file" );
				$( "#upload_file" ).prop( 'accept', ".pem" );
				$( "#filetype_description" ).html( "拡張子「.pem」のファイルを指定してください。" );
				$( "#upload_dialog_paygent" ).dialog( "option" , "title" , "クライアント証明書ファイルのアップロード" );
				$( "#upload_dialog_paygent" ).dialog( "open" );
			} else {
				alert( retVal.status );
				return false;
			}
		}).fail( function( retVal ) {
		});
		return false;
	});

	$( document ).on( "click", "#ca_file_upload", function() {
		$.ajax({
			url: ajaxurl,
			type: "POST",
			cache: false,
			dataType: "json",
			data: {
				action: "check_file_path",
				path: $( "#certificate_path_paygent" ).val(),
				file: $( "#ca_file_paygent" ).val(),
				wc_nonce: $( "#wc_nonce" ).val()
			}
		}).done( function( retVal ) {
			if ( "OK" == retVal.status ) {
				if ( ! retVal.file_exists ) {
					$( "#ca_file_paygent" ).val( "" );
				}
				$( "#upload_filetype" ).val( "ca_file" );
				$( "#upload_file" ).prop( 'accept', ".crt" );
				$( "#filetype_description" ).html( "拡張子「.crt」のファイルを指定してください。" );
				$( "#upload_dialog_paygent" ).dialog( "option" , "title" , "証明済みのCAファイルのアップロード" );
				$( "#upload_dialog_paygent" ).dialog( "open" );
			} else {
				alert( retVal.status );
				return false;
			}
		}).fail( function( retVal ) {
		});
		return false;
	});

	$( "#upload_dialog_paygent" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: 240,
		width: 400,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Cancel', 'usces' ); ?>": function() {
				$( this ).dialog( 'close' );
			}
		},
		close: function() {
			$( "#upload_file" ).val( "" );
		}
	});

	$( "#upload_button" ).on( "click", function() {
		let $upfile = $( 'input[name="upload_file"]' );
		let fd      = new FormData();
		fd.append( "action", 'upload_certificate_file' );
		fd.append( "upfile", $upfile.prop( 'files' )[0] );
		fd.append( "upfile_name", $upfile.prop( 'files' )[0].name );
		fd.append( "wc_nonce", $( "#wc_nonce" ).val() );
		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: fd,
			processData: false,
			contentType: false,
			cache: false,
		}).done( function( retVal ) {
			if ( "OK" == retVal.status ) {
				if ( "client_file" == $( "#upload_filetype" ).val() ) {
					$( "#client_file_paygent" ).val( $upfile.prop( 'files' )[0].name );
				} else if ( "ca_file" == $( "#upload_filetype" ).val() ) {
					$( "#ca_file_paygent" ).val( $upfile.prop( 'files' )[0].name );
				}
				$( "#upload_dialog_paygent" ).dialog( 'close' );
			} else {
				alert( "アップロードに失敗しました。" );
			}
		}).fail( function() {
			console.log( retVal );
		});
	});
});
</script>
					<?php
				endif;
				break;

			/* 受注編集画面・継続課金会員詳細画面 */
			case 'usces_orderlist':
			case 'usces_continue':
				$order_id   = '';
				$acting_flg = '';
				if ( ( 'usces_orderlist' == $admin_page && ( isset( $_GET['order_action'] ) && ( 'edit' == wp_unslash( $_GET['order_action'] ) || 'editpost' == wp_unslash( $_GET['order_action'] ) || 'newpost' == wp_unslash( $_GET['order_action'] ) ) ) ) ||
					( 'usces_continue' == $admin_page && ( isset( $_GET['continue_action'] ) && 'settlement_paygent_card' == wp_unslash( $_GET['continue_action'] ) ) ) ) {
					$order_id = ( isset( $_REQUEST['order_id'] ) ) ? wp_unslash( $_REQUEST['order_id'] ) : '';
					if ( ! empty( $order_id ) ) {
						$acting_flg = $this->get_order_acting_flg( $order_id );
					}
				}
				if ( in_array( $acting_flg, $this->pay_method ) ) :
					?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	adminOrderEdit = {
		updateSettlementStatus : function( mode ) {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "get_paygent_" + mode,
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					member_id: $( "#member_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					var num = $( "#order_num" ).val();
					if ( $( "#settlement-status-" + num ).length ) {
						$( "#settlement-status-" + num ).html( retVal.acting_status );
					} else {
						$( "#settlement-status" ).html( retVal.acting_status );
					}
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
					<?php
					if ( 'acting_paygent_card' == $acting_flg ) :
						?>
		getSettlementCard : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			var mode = ( "" != $( "#error" ).val() ) ? "error_paygent_card" : "get_paygent_card";
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: mode,
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					member_id: $( "#member_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					var num = $( "#order_num" ).val();
					if ( $( "#settlement-status-" + num ).length ) {
						$( "#settlement-status-" + num ).html( retVal.acting_status );
					} else {
						$( "#settlement-status" ).html( retVal.acting_status );
					}
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		salesSettlementCard : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "sales_paygent_card",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					member_id: $( "#member_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					var num = $( "#order_num" ).val();
					if ( $( "#settlement-status-" + num ).length ) {
						$( "#settlement-status-" + num ).html( retVal.acting_status );
					} else {
						$( "#settlement-status" ).html( retVal.acting_status );
					}
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		cancelSettlementCard : function( mode ) {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: mode + "_cancel_paygent_card",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					member_id: $( "#member_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					var num = $( "#order_num" ).val();
					if ( $( "#settlement-status-" + num ).length ) {
						$( "#settlement-status-" + num ).html( retVal.acting_status );
					} else {
						$( "#settlement-status" ).html( retVal.acting_status );
					}
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		changeSettlementCard : function( mode, amount ) {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: mode + "_revise_paygent_card",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					member_id: $( "#member_id" ).val(),
					amount: amount,
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					var num = $( "#order_num" ).val();
					if ( $( "#settlement-status-" + num ).length ) {
						$( "#settlement-status-" + num ).html( retVal.acting_status );
					} else {
						$( "#settlement-status" ).html( retVal.acting_status );
					}
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		authSettlementCard : function( amount ) {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "auth_paygent_card",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					member_id: $( "#member_id" ).val(),
					amount: amount,
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					var num = $( "#order_num" ).val();
					if ( $( "#settlement-status-" + num ).length ) {
						$( "#settlement-status-" + num ).html( retVal.acting_status );
					} else {
						$( "#settlement-status" ).html( retVal.acting_status );
					}
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
						<?php
					elseif ( 'acting_paygent_conv' == $acting_flg ) :
						?>
		getSettlementConv : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "get_paygent_conv",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					$( "#settlement-status" ).html( retVal.acting_status );
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
						<?php
					elseif ( 'acting_paygent_atm' == $acting_flg ) :
						?>
		getSettlementAtm : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "get_paygent_atm",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
						<?php
					elseif ( 'acting_paygent_bank' == $acting_flg ) :
						?>
		getSettlementBank : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "get_paygent_bank",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					$( "#settlement-status" ).html( retVal.acting_status );
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
						<?php
					elseif ( 'acting_paygent_paidy' == $acting_flg ) :
						?>
		getSettlementPaidy : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "get_paygent_paidy",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					$( "#settlement-status" ).html( retVal.acting_status );
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				if ( $( "#refund-settlement" ).length ) {
					$( "#refund-settlement" ).prop( "disabled", true );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		cancelSettlementPaidy : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "cancel_paygent_paidy",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					$( "#settlement-status" ).html( retVal.acting_status );
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				if ( $( "#refund-settlement" ).length ) {
					$( "#refund-settlement" ).prop( "disabled", true );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		captureSettlementPaidy : function() {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "capture_paygent_paidy",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					$( "#settlement-status" ).html( retVal.acting_status );
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				if ( $( "#refund-settlement" ).length ) {
					$( "#refund-settlement" ).prop( "disabled", true );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
		refundSettlementPaidy : function( amount ) {
			$( "#settlement-response" ).html( "" );
			$( "#settlement-response-loading" ).html( '<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />' );
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: "usces_admin_ajax",
					mode: "refund_paygent_paidy",
					order_id: $( "#order_id" ).val(),
					trading_id: $( "#trading_id" ).val(),
					amount: amount,
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( retVal.acting_status ) {
					$( "#settlement-status" ).html( retVal.acting_status );
				}
				if ( retVal.result ) {
					$( "#settlement-response" ).html( retVal.result );
				}
				if ( $( "#refund-settlement" ).length ) {
					$( "#refund-settlement" ).prop( "disabled", true );
				}
				$( "#settlement-response-loading" ).html( "" );
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				$( "#settlement-response-loading" ).html( "" );
			});
			return false;
		},
						<?php
					endif;
					?>
	};

	$( "#settlement_dialog" ).dialog({
		dialogClass: "admin-paygent-dialog",
		bgiframe: true,
		autoOpen: false,
		height: "auto",
		width: 800,
		resizable: true,
		modal: true,
		buttons: {
			"<?php esc_html_e( 'Close' ); ?>": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
					<?php
					if ( 'acting_paygent_card' == $acting_flg ) :
						?>
			adminOrderEdit.getSettlementCard();
						<?php
					elseif ( 'acting_paygent_conv' == $acting_flg ) :
						?>
			adminOrderEdit.getSettlementConv();
						<?php
					elseif ( 'acting_paygent_atm' == $acting_flg ) :
						?>
			adminOrderEdit.getSettlementAtm();
						<?php
					elseif ( 'acting_paygent_bank' == $acting_flg ) :
						?>
			adminOrderEdit.getSettlementBank();
						<?php
					elseif ( 'acting_paygent_paidy' == $acting_flg ) :
						?>
			adminOrderEdit.getSettlementPaidy();
						<?php
					endif;
					?>
		},
		close: function() {
		}
	});

	$( document ).on( "click", ".settlement-information", function() {
		var trading_id = $( this ).attr( "data-trading_id" );
		var order_num  = $( this ).attr( "data-num" );
		$( "#trading_id" ).val( trading_id );
		$( "#order_num" ).val( order_num );
		$( "#settlement_dialog" ).dialog( "option", "title", "<?php echo esc_js( $this->acting_formal_name ); ?>" );
		$( "#settlement_dialog" ).dialog( "open" );
	});

	$( document ).on( "click", "#update-status", function() {
		var mode = $( this ).attr( "data-mode" );
		adminOrderEdit.updateSettlementStatus( mode );
	});
					<?php
					if ( 'acting_paygent_card' == $acting_flg ) :
						?>
	$( document ).on( "click", "#sales-settlement", function() {
		if ( ! confirm( "売上処理を実行します。よろしいですか？" ) ) {
			return;
		}
		adminOrderEdit.salesSettlementCard();
	});

	$( document ).on( "click", "#auth-cancel-settlement", function() {
		if ( ! confirm( "キャンセル処理を実行します。よろしいですか？" ) ) {
			return;
		}
		adminOrderEdit.cancelSettlementCard( 'auth' );
	});

	$( document ).on( "click", "#sales-cancel-settlement", function() {
		if ( ! confirm( "キャンセル処理を実行します。よろしいですか？" ) ) {
			return;
		}
		adminOrderEdit.cancelSettlementCard( 'sales' );
	});

	$( document ).on( "click", "#auth-revise-settlement", function() {
		var amount_change = parseInt( $( "#amount_change" ).val() ) || 0;
		if ( 0 == amount_change ) {
			if ( ! confirm( "キャンセル処理を実行します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.cancelSettlementCard( 'auth' );
		} else {
			if ( ! confirm( "決済金額を" + amount_change + "円に変更します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.changeSettlementCard( 'auth', amount_change );
		}
	});

	$( document ).on( "click", "#sales-revise-settlement", function() {
		var amount_change = parseInt( $( "#amount_change" ).val() ) || 0;
		if ( 0 == amount_change ) {
			if ( ! confirm( "キャンセル処理を実行します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.cancelSettlementCard( 'auth' );
		} else {
			if ( ! confirm( "決済金額を" + amount_change + "円に変更します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.changeSettlementCard( 'sales', amount_change );
		}
	});

	$( document ).on( "click", "#auth-settlement", function() {
		var amount = parseInt( $( "#amount_change" ).val() ) || 0;
		if ( 0 < amount ) {
			if ( ! confirm( "新規オーソリを実行します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.authSettlementCard( amount );
		}
	});
						<?php
					elseif ( 'acting_paygent_paidy' == $acting_flg ) :
						?>
	$( document ).on( "click", "#paidy-capture-settlement", function() {
		if ( ! confirm( "売上処理を実行します。よろしいですか？" ) ) {
			return;
		}
		adminOrderEdit.captureSettlementPaidy();
	});

	$( document ).on( "click", "#paidy-cancel-settlement", function() {
		if ( ! confirm( "キャンセル処理を実行します。よろしいですか？" ) ) {
			return;
		}
		adminOrderEdit.cancelSettlementPaidy();
	});

	$( document ).on( "click", "#paidy-refund-settlement", function() {
		var amount_original = parseInt( $( "#amount_original" ).val() ) || 0;
		var amount_change   = parseInt( $( "#amount_change" ).val() ) || 0;
		if ( amount_change == amount_original ) {
			return;
		}
		if ( amount_change > amount_original ) {
			alert( "決済金額を超える金額は返金できません。" );
			return;
		}
		if ( 0 == amount_change ) {
			if ( ! confirm( "全額返金処理を実行します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.refundSettlementPaidy( amount_original );
		} else {
			var amount = amount_original - amount_change;
			if ( ! confirm( amount + "円の返金処理を実行します。よろしいですか？" ) ) {
				return;
			}
			adminOrderEdit.refundSettlementPaidy( amount );
		}
	});
						<?php
					endif;
					?>
	$( document ).on( "keydown", ".settlement-amount", function( e ) {
		var halfVal = $( this ).val().replace( /[！-～]/g,
			function( tmpStr ) {
				return String.fromCharCode( tmpStr.charCodeAt(0) - 0xFEE0 );
			}
		);
		$( this ).val( halfVal.replace( /[^0-9]/g, '' ) );
	});
	$( document ).on( "keyup", ".settlement-amount", function() {
		this.value = this.value.replace( /[^0-9]+/i, '' );
		this.value = Number( this.value ) || 0;
	});
	$( document ).on( "blur", ".settlement-amount", function() {
		this.value = this.value.replace( /[^0-9]+/i, '' );
	});
					<?php if ( 'usces_continue' == $admin_page ) : ?>
	adminContinuation = {
		update : function() {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "continuation_update",
					member_id: $( "#member_id" ).val(),
					order_id: $( "#order_id" ).val(),
					contracted_year: $( "#contracted-year option:selected" ).val(),
					contracted_month: $( "#contracted-month option:selected" ).val(),
					contracted_day: $( "#contracted-day option:selected" ).val(),
					charged_year: $( "#charged-year option:selected" ).val(),
					charged_month: $( "#charged-month option:selected" ).val(),
					charged_day: $( "#charged-day option:selected" ).val(),
					price: $( "#price" ).val(),
					status: $( "#dlseller-status" ).val(),
					wc_nonce: $( "#wc_nonce" ).val()
				}
			}).done( function( retVal, dataType ) {
				if ( "OK" == retVal.status ) {
					adminOperation.setActionStatus( "success", "<?php esc_html_e( 'The update was completed.', 'usces' ); ?>" );
				} else {
					var message = ( retVal.message != "" ) ? retVal.message : "<?php esc_html_e( 'failure in update', 'usces' ); ?>";
					adminOperation.setActionStatus( "error", message );
				}
			}).fail( function( jqXHR, textStatus, errorThrown ) {
				console.log( textStatus );
				console.log( jqXHR.status );
				console.log( errorThrown.message );
				adminOperation.setActionStatus( "error", "<?php esc_html_e( 'failure in update', 'usces' ); ?>" );
			});
			return false;
		}
	};

	$( document ).on( "click", "#continuation-update", function() {
		var status = $( "#dlseller-status option:selected" ).val();
		if ( "continuation" == status ) {
			var year = $( "#charged-year option:selected" ).val();
			var month = $( "#charged-month option:selected" ).val();
			var day = $( "#charged-day option:selected" ).val();
			if ( 0 == year || 0 == month || 0 == day ) {
				alert( "<?php esc_html_e( 'Data have deficiency.', 'usces' ); ?>" );
				$( "#charged-year" ).focus();
				return;
			}
			if ( "" == $( "#price" ).val() || 0 == parseFloat( $( "#price" ).val() ) ) {
				alert( "<?php printf( __( 'Input the %s', 'usces' ), esc_html__( 'Amount', 'dlseller' ) ); // phpcs:ignore ?>" );
				$( "#price" ).focus();
				return;
			}
		}
		if ( ! confirm( "<?php esc_html_e( 'Are you sure you want to update the settings?', 'usces' ); ?>" ) ) {
			return;
		}
		adminContinuation.update();
	});
					<?php endif; ?>
});
</script>
					<?php
				endif;
				break;
		endswitch;
	}

	/**
	 * Certificate file path check.
	 */
	public function check_file_path() {
		$post_data        = wp_unslash( $_POST );
		$certificate_path = rtrim( $post_data['path'], '/' );
		if ( empty( $certificate_path ) ) {
			$data['status'] = '証明書ファイルパスが指定されていません';
		} else {
			if ( ! is_dir( $certificate_path ) ) {
				wp_mkdir_p( $certificate_path );
			}
			if ( ! empty( $post_data['file'] ) && file_exists( $certificate_path . '/' . $post_data['file'] ) ) {
				$data['file_exists'] = 1;
			}
			$data['status'] = ( is_writable( $certificate_path ) ) ? 'OK' : '証明書ファイルパスのディレクトリに書き込み権限がありません';
		}
		wp_send_json( $data );
	}

	/**
	 * Upload paygent certificate file.
	 */
	public function upload_certificate_file() {
		$upfile      = $_FILES['upfile'];
		$acting_opts = $this->get_acting_settings();
		$data        = array();
		if ( 0 < $upfile['error'] ) {
			$data['status'] = $upfile['error'];
		} else {
			$res = move_uploaded_file( $upfile['tmp_name'], $acting_opts['certificate_path'] . '/' . $upfile['name'] );
			if ( $res ) {
				$data['status'] = 'OK';
			} else {
				$data['status'] = 'NG';
			}
		}
		wp_send_json( $data );
	}

	/**
	 * 決済オプション登録・更新
	 * usces_action_admin_settlement_update
	 */
	public function settlement_update() {
		global $usces;

		if ( $this->paymod_id != wp_unslash( $_POST['acting'] ) ) {
			return;
		}

		$this->error_mes  = '';
		$payment_method   = usces_get_system_option( 'usces_payment_method', 'settlement' );
		$options          = get_option( 'usces' );
		$certificate_path = $options['acting_settings']['paygent']['certificate_path'];
		$post_data        = wp_unslash( $_POST );

		unset( $options['acting_settings']['paygent'] );
		$options['acting_settings']['paygent']['seq_merchant_id']      = ( isset( $post_data['seq_merchant_id'] ) ) ? trim( $post_data['seq_merchant_id'] ) : '';
		$options['acting_settings']['paygent']['connect_id']           = ( isset( $post_data['connect_id'] ) ) ? trim( $post_data['connect_id'] ) : '';
		$options['acting_settings']['paygent']['connect_password']     = ( isset( $post_data['connect_password'] ) ) ? trim( $post_data['connect_password'] ) : '';
		$options['acting_settings']['paygent']['telegram_version']     = TELEGRAM_VERSION;
		$options['acting_settings']['paygent']['hc']                   = ( isset( $post_data['hc'] ) ) ? trim( $post_data['hc'] ) : '';
		$options['acting_settings']['paygent']['conv_hc']              = ( isset( $post_data['conv_hc'] ) ) ? trim( $post_data['conv_hc'] ) : '';
		$options['acting_settings']['paygent']['ope']                  = ( isset( $post_data['ope'] ) ) ? $post_data['ope'] : '';
		$options['acting_settings']['paygent']['card_activate']        = ( isset( $post_data['card_activate'] ) ) ? $post_data['card_activate'] : 'off';
		$options['acting_settings']['paygent']['token_key']            = ( isset( $post_data['token_key'] ) ) ? trim( $post_data['token_key'] ) : '';
		$options['acting_settings']['paygent']['token_hc']             = ( isset( $post_data['token_hc'] ) ) ? trim( $post_data['token_hc'] ) : '';
		$options['acting_settings']['paygent']['client_file']          = ( isset( $post_data['client_file'] ) ) ? trim( $post_data['client_file'] ) : '';
		$options['acting_settings']['paygent']['ca_file']              = ( isset( $post_data['ca_file'] ) ) ? trim( $post_data['ca_file'] ) : '';
		$options['acting_settings']['paygent']['payment_class']        = ( isset( $post_data['payment_class'] ) ) ? $post_data['payment_class'] : '0';
		$options['acting_settings']['paygent']['use_card_conf_number'] = ( isset( $post_data['use_card_conf_number'] ) ) ? $post_data['use_card_conf_number'] : 'off';
		$options['acting_settings']['paygent']['stock_card_mode']      = ( isset( $post_data['stock_card_mode'] ) ) ? $post_data['stock_card_mode'] : 'off';
		$options['acting_settings']['paygent']['sales_mode']           = ( isset( $post_data['sales_mode'] ) ) ? $post_data['sales_mode'] : '0';
		$options['acting_settings']['paygent']['sales_mode_dlseller']  = ( isset( $post_data['sales_mode_dlseller'] ) ) ? $post_data['sales_mode_dlseller'] : '0';
		$options['acting_settings']['paygent']['auto_settlement_mail'] = ( isset( $post_data['auto_settlement_mail'] ) ) ? $post_data['auto_settlement_mail'] : 'off';
		$options['acting_settings']['paygent']['threedsecure_ryaku']   = ( isset( $post_data['threedsecure_ryaku'] ) ) ? $post_data['threedsecure_ryaku'] : 'off';
		$options['acting_settings']['paygent']['threedsecure_hc']      = ( isset( $post_data['threedsecure_hc'] ) ) ? trim( $post_data['threedsecure_hc'] ) : '';
		$options['acting_settings']['paygent']['attempt']              = ( isset( $post_data['attempt'] ) ) ? $post_data['attempt'] : 'on';
		$options['acting_settings']['paygent']['conv_activate']        = ( isset( $post_data['conv_activate'] ) ) ? $post_data['conv_activate'] : 'off';
		$options['acting_settings']['paygent']['payment_term_day']     = ( isset( $post_data['payment_term_day'] ) ) ? $post_data['payment_term_day'] : '5';
		$options['acting_settings']['paygent']['payment_term_min']     = ( isset( $post_data['payment_term_min'] ) ) ? $post_data['payment_term_min'] : '';
		$options['acting_settings']['paygent']['sales_type']           = ( isset( $post_data['sales_type'] ) ) ? $post_data['sales_type'] : '1';
		$options['acting_settings']['paygent']['cvs_type']             = ( isset( $post_data['cvs_type'] ) ) ? $post_data['cvs_type'] : array( '01', '02', '03', '04' );
		$options['acting_settings']['paygent']['atm_activate']         = ( isset( $post_data['atm_activate'] ) ) ? $post_data['atm_activate'] : 'off';
		$options['acting_settings']['paygent']['payment_limit_date']   = ( isset( $post_data['payment_limit_date'] ) ) ? $post_data['payment_limit_date'] : '';
		$options['acting_settings']['paygent']['bank_activate']        = ( isset( $post_data['bank_activate'] ) ) ? $post_data['bank_activate'] : 'off';
		$options['acting_settings']['paygent']['asp_payment_term']     = ( isset( $post_data['asp_payment_term'] ) ) ? $post_data['asp_payment_term'] : '0050000';
		$options['acting_settings']['paygent']['paidy_activate']       = ( isset( $post_data['paidy_activate'] ) ) ? $post_data['paidy_activate'] : 'off';
		$options['acting_settings']['paygent']['paidy_public_key']     = ( isset( $post_data['paidy_public_key'] ) ) ? $post_data['paidy_public_key'] : '';
		if ( ! isset( $post_data['certificate_path'] ) || WCUtils::is_blank( $post_data['certificate_path'] ) ) {
			$certificate_path_before = ( isset( $post_data['certificate_path_before'] ) ) ? $post_data['certificate_path_before'] : '';
			if ( empty( $certificate_path ) || $certificate_path_before != $certificate_path ) {
				$upload_dir = wp_upload_dir();
				$dir        = $this->create_certificate_path();
				$options['acting_settings']['paygent']['certificate_path'] = $upload_dir['basedir'] . $dir;
			} else {
				$options['acting_settings']['paygent']['certificate_path'] = $certificate_path;
			}
		} else {
			$options['acting_settings']['paygent']['certificate_path'] = rtrim( trim( $post_data['certificate_path'] ), '/' );
		}

		if ( WCUtils::is_blank( $options['acting_settings']['paygent']['seq_merchant_id'] ) ) {
			$this->error_mes .= '※マーチャントIDを入力してください<br />';
		}
		if ( 'on' == $options['acting_settings']['paygent']['card_activate'] || 'on' == $options['acting_settings']['paygent']['conv_activate'] ) {
			if ( WCUtils::is_blank( $options['acting_settings']['paygent']['hc'] ) ) {
				$this->error_mes .= '※ハッシュ値生成キーを入力してください<br />';
			}
		} elseif ( 'module' == $options['acting_settings']['paygent']['card_activate'] || 'module' == $options['acting_settings']['paygent']['conv_activate'] ) {
			if ( '' == $options['acting_settings']['paygent']['connect_id'] ) {
				$this->error_mes .= '※接続IDを入力してください<br />';
			}
			if ( '' == $options['acting_settings']['paygent']['connect_password'] ) {
				$this->error_mes .= '※接続パスワードを入力してください<br />';
			}
			if ( '' != $options['acting_settings']['paygent']['client_file'] ) {
				if ( ! file_exists( $options['acting_settings']['paygent']['certificate_path'] . '/' . $options['acting_settings']['paygent']['client_file'] ) ) {
					$this->error_mes .= '※クライアント証明書ファイルがアップロードされていません<br />';
				}
			}
			if ( '' != $options['acting_settings']['paygent']['ca_file'] ) {
				if ( ! file_exists( $options['acting_settings']['paygent']['certificate_path'] . '/' . $options['acting_settings']['paygent']['ca_file'] ) ) {
					$this->error_mes .= '※CAファイルがアップロードされていません<br />';
				}
			}
		}
		if ( WCUtils::is_blank( $options['acting_settings']['paygent']['ope'] ) ) {
			$this->error_mes .= '※稼働環境を選択してください<br />';
		}
		if ( WCUtils::is_blank( $options['acting_settings']['paygent']['conv_hc'] ) ) {
			$this->error_mes .= '※差分通知ハッシュ値生成キーを入力してください<br />';
		}
		if ( 'on' == $options['acting_settings']['paygent']['conv_activate'] ) {
			if ( '' == $options['acting_settings']['paygent']['payment_term_day'] && '' == $options['acting_settings']['paygent']['payment_term_min'] ) {
			} elseif ( '' != $options['acting_settings']['paygent']['payment_term_day'] && '' != $options['acting_settings']['paygent']['payment_term_min'] ) {
				$this->error_mes .= '※「支払期間（日指定）」と「支払期間（分指定）」の両方を指定することはできません<br />';
			} elseif ( '' != $options['acting_settings']['paygent']['payment_term_day'] ) {
				$term_day = (int) $options['acting_settings']['paygent']['payment_term_day'];
				if ( 2 > $term_day || 60 < $term_day ) {
					$this->error_mes .= '※「支払期間（日指定）」が指定できる範囲を超えています<br />';
				}
			} elseif ( '' != $options['acting_settings']['paygent']['payment_term_min'] ) {
				$term_min = (int) $options['acting_settings']['paygent']['payment_term_min'];
				if ( 5 > $term_min || 2880 < $term_min ) {
					$this->error_mes .= '※「支払期間（分指定）」が指定できる範囲を超えています<br />';
				}
			}
		} elseif ( 'module' == $options['acting_settings']['paygent']['conv_activate'] ) {
			if ( empty( $options['acting_settings']['paygent']['cvs_type'] ) ) {
				$this->error_mes .= '※申込コンビニを選択してください<br />';
			}
		}

		if ( 'module' == $options['acting_settings']['paygent']['paidy_activate'] ) {
			if ( WCUtils::is_blank( $options['acting_settings']['paygent']['paidy_public_key'] ) ) {
				$this->error_mes .= '※パブリックキーを入力してください<br />';
			}
		}

		if ( '' == $this->error_mes ) {
			$usces->action_status  = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			$toactive              = array();
			if ( 'on' == $options['acting_settings']['paygent']['card_activate'] || 'module' == $options['acting_settings']['paygent']['card_activate'] ) {
				$usces->payment_structure['acting_paygent_card'] = 'カード決済（' . $this->acting_name . '）';
				if ( '' == $options['acting_settings']['paygent']['payment_class'] ) {
					$options['acting_settings']['paygent']['payment_class'] = '0';
				}
				if ( '' == $options['acting_settings']['paygent']['use_card_conf_number'] ) {
					$options['acting_settings']['paygent']['use_card_conf_number'] = 'off';
				}
				if ( '' == $options['acting_settings']['paygent']['stock_card_mode'] ) {
					$options['acting_settings']['paygent']['stock_card_mode'] = 'off';
				}
				if ( '' == $options['acting_settings']['paygent']['threedsecure_ryaku'] ) {
					$options['acting_settings']['paygent']['threedsecure_ryaku'] = 'off';
				}
				foreach ( $payment_method as $settlement => $payment ) {
					if ( 'acting_paygent_card' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
			} else {
				unset( $usces->payment_structure['acting_paygent_card'] );
			}
			if ( 'on' == $options['acting_settings']['paygent']['conv_activate'] || 'module' == $options['acting_settings']['paygent']['conv_activate'] ) {
				$usces->payment_structure['acting_paygent_conv'] = 'コンビニ決済（' . $this->acting_name . '）';
				if ( '' == $options['acting_settings']['paygent']['payment_term_day'] && '' == $options['acting_settings']['paygent']['payment_term_min'] ) {
					$options['acting_settings']['paygent']['payment_term_day'] = 5;
				}
				foreach ( $payment_method as $settlement => $payment ) {
					if ( 'acting_paygent_conv' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
			} else {
				unset( $usces->payment_structure['acting_paygent_conv'] );
			}
			if ( 'module' == $options['acting_settings']['paygent']['atm_activate'] ) {
				$usces->payment_structure['acting_paygent_atm'] = 'ATM決済（' . $this->acting_name . '）';
				foreach ( $payment_method as $settlement => $payment ) {
					if ( 'acting_paygent_atm' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
			} else {
				unset( $usces->payment_structure['acting_paygent_atm'] );
			}
			if ( 'module' == $options['acting_settings']['paygent']['bank_activate'] ) {
				$usces->payment_structure['acting_paygent_bank'] = '銀行ネット決済（' . $this->acting_name . '）';
				foreach ( $payment_method as $settlement => $payment ) {
					if ( 'acting_paygent_bank' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
			} else {
				unset( $usces->payment_structure['acting_paygent_bank'] );
			}
			if ( 'module' == $options['acting_settings']['paygent']['paidy_activate'] ) {
				$usces->payment_structure['acting_paygent_paidy'] = 'Paidy（' . $this->acting_name . '）';
				foreach ( $payment_method as $settlement => $payment ) {
					if ( 'acting_paygent_paidy' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
			} else {
				unset( $usces->payment_structure['acting_paygent_paidy'] );
			}
			if ( ( 'on' == $options['acting_settings']['paygent']['card_activate'] || 'module' == $options['acting_settings']['paygent']['card_activate'] ) ||
				( 'on' == $options['acting_settings']['paygent']['conv_activate'] || 'module' == $options['acting_settings']['paygent']['conv_activate'] ) ||
				'module' == $options['acting_settings']['paygent']['atm_activate'] ||
				'module' == $options['acting_settings']['paygent']['bank_activate'] ||
				'module' == $options['acting_settings']['paygent']['paidy_activate'] ) {
				$options['acting_settings']['paygent']['activate'] = 'on';
				if ( 'public' == $options['acting_settings']['paygent']['ope'] ) {
					$options['acting_settings']['paygent']['send_url']  = 'https://link.paygent.co.jp/v/u/request';
					$options['acting_settings']['paygent']['token_url'] = 'https://token.paygent.co.jp/js/PaygentToken.js';
				} else {
					$options['acting_settings']['paygent']['send_url']  = 'https://sandbox.paygent.co.jp/v/u/request';
					$options['acting_settings']['paygent']['token_url'] = 'https://sandbox.paygent.co.jp/js/PaygentToken.js';
				}
				usces_admin_orderlist_show_wc_trans_id();
				if ( 0 < count( $toactive ) ) {
					$usces->action_message .= __( 'Please update the payment method to "Activate". <a href="admin.php?page=usces_initial#payment_method_setting">General Setting > Payment Methods</a>', 'usces' );
				}
			} else {
				$options['acting_settings']['paygent']['activate'] = 'off';
				unset( $usces->payment_structure['acting_paygent_card'] );
				unset( $usces->payment_structure['acting_paygent_conv'] );
				unset( $usces->payment_structure['acting_paygent_atm'] );
				unset( $usces->payment_structure['acting_paygent_bank'] );
				unset( $usces->payment_structure['acting_paygent_paidy'] );
			}
			$deactivate = array();
			foreach ( $payment_method as $settlement => $payment ) {
				if ( ! array_key_exists( $settlement, $usces->payment_structure ) ) {
					if ( 'deactivate' != $payment['use'] ) {
						$payment['use'] = 'deactivate';
						$deactivate[]   = $payment['name'];
						usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
					}
				}
			}
			if ( 0 < count( $deactivate ) ) {
				$deactivate_message     = sprintf( __( '"Deactivate" %s of payment method.', 'usces' ), implode( ',', $deactivate ) );
				$usces->action_message .= $deactivate_message;
			}
		} else {
			$usces->action_status                              = 'error';
			$usces->action_message                             = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['paygent']['activate'] = 'off';
			unset( $usces->payment_structure['acting_paygent_card'] );
			unset( $usces->payment_structure['acting_paygent_conv'] );
			unset( $usces->payment_structure['acting_paygent_atm'] );
			unset( $usces->payment_structure['acting_paygent_bank'] );
			unset( $usces->payment_structure['acting_paygent_paidy'] );
			$deactivate = array();
			foreach ( $payment_method as $settlement => $payment ) {
				if ( in_array( $settlement, $this->pay_method ) ) {
					if ( 'deactivate' != $payment['use'] ) {
						$payment['use'] = 'deactivate';
						$deactivate[]   = $payment['name'];
						usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
					}
				}
			}
			if ( 0 < count( $deactivate ) ) {
				$deactivate_message     = sprintf( __( '"Deactivate" %s of payment method.', 'usces' ), implode( ',', $deactivate ) );
				$usces->action_message .= $deactivate_message . __( 'Please complete the setup and update the payment method to "Activate".', 'usces' );
			}
		}

		ksort( $usces->payment_structure );
		update_option( 'usces', $options );
		update_option( 'usces_payment_structure', $usces->payment_structure );
	}

	/**
	 * クレジット決済設定画面タブ
	 * usces_action_settlement_tab_title
	 */
	public function settlement_tab_title() {
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if ( in_array( $this->paymod_id, (array) $settlement_selected ) ) {
			echo '<li><a href="#uscestabs_' . esc_html( $this->paymod_id ) . '">' . esc_html( $this->acting_name ) . '</a></li>';
		}
	}

	/**
	 * クレジット決済設定画面フォーム
	 * usces_action_settlement_tab_body
	 */
	public function settlement_tab_body() {
		global $usces;

		$acting_opts         = $this->get_acting_settings();
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if ( in_array( $this->paymod_id, (array) $settlement_selected ) ) :
			$seq_merchant_id  = ( isset( $acting_opts['seq_merchant_id'] ) ) ? $acting_opts['seq_merchant_id'] : '';
			$connect_id       = ( isset( $acting_opts['connect_id'] ) ) ? $acting_opts['connect_id'] : '';
			$connect_password = ( isset( $acting_opts['connect_password'] ) ) ? $acting_opts['connect_password'] : '';
			$hc               = ( isset( $acting_opts['hc'] ) ) ? $acting_opts['hc'] : '';
			$conv_hc          = ( isset( $acting_opts['conv_hc'] ) ) ? $acting_opts['conv_hc'] : '';
			$ope_test         = '';
			$ope_public       = '';
			if ( isset( $acting_opts['ope'] ) && 'public' == $acting_opts['ope'] ) {
				$ope_public = ' checked="checked"';
			} else {
				$ope_test = ' checked="checked"'; /* default */
			}
			$card_activate_link   = '';
			$card_activate_module = '';
			$card_activate_off    = '';
			if ( isset( $acting_opts['card_activate'] ) && 'on' == $acting_opts['card_activate'] ) {
				$card_activate_link = ' checked="checked"';
			} elseif ( isset( $acting_opts['card_activate'] ) && 'module' == $acting_opts['card_activate'] ) {
				$card_activate_module = ' checked="checked"';
			} else {
				$card_activate_off = ' checked="checked"';
			}
			$token_key            = ( isset( $acting_opts['token_key'] ) ) ? $acting_opts['token_key'] : '';
			$token_hc             = ( isset( $acting_opts['token_hc'] ) ) ? $acting_opts['token_hc'] : '';
			$certificate_path     = ( isset( $acting_opts['certificate_path'] ) ) ? $acting_opts['certificate_path'] : '';
			$client_file          = ( isset( $acting_opts['client_file'] ) ) ? $acting_opts['client_file'] : '';
			$ca_file              = ( isset( $acting_opts['ca_file'] ) ) ? $acting_opts['ca_file'] : '';
			$client_file_disabled = '';
			$ca_file_disabled     = '';
			$payment_class_0      = '';
			$payment_class_1      = '';
			$payment_class_2      = '';
			if ( isset( $acting_opts['payment_class'] ) && '1' == $acting_opts['payment_class'] ) {
				$payment_class_1 = ' checked="checked"';
			} elseif ( isset( $acting_opts['payment_class'] ) && '2' == $acting_opts['payment_class'] ) {
				$payment_class_2 = ' checked="checked"';
			} else {
				$payment_class_0 = ' checked="checked"'; /* default */
			}
			$use_card_conf_number_on  = '';
			$use_card_conf_number_off = '';
			if ( isset( $acting_opts['use_card_conf_number'] ) && 'on' == $acting_opts['use_card_conf_number'] ) {
				$use_card_conf_number_on = ' checked="checked"';
			} else {
				$use_card_conf_number_off = ' checked="checked"';
			}
			$stock_card_mode_on  = '';
			$stock_card_mode_off = '';
			if ( isset( $acting_opts['stock_card_mode'] ) && 'on' == $acting_opts['stock_card_mode'] ) {
				$stock_card_mode_on = ' checked="checked"';
			} else {
				$stock_card_mode_off = ' checked="checked"';
			}
			$stock_card_mode_msg = '';
			if ( defined( 'WCEX_DLSELLER' ) ) {
				$stock_card_mode_msg = '自動継続課金をご利用の場合は「利用する」を選択してください。';
			} elseif ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
				$stock_card_mode_msg = '定期購入をご利用の場合は「利用する」を選択してください。';
			}
			$sales_mode_on  = '';
			$sales_mode_off = '';
			if ( isset( $acting_opts['sales_mode'] ) && '1' == $acting_opts['sales_mode'] ) {
				$sales_mode_on = ' checked="checked"';
			} else {
				$sales_mode_off = ' checked="checked"';
			}
			$sales_mode_dlseller_on  = '';
			$sales_mode_dlseller_off = '';
			if ( isset( $acting_opts['sales_mode_dlseller'] ) && '1' == $acting_opts['sales_mode_dlseller'] ) {
				$sales_mode_dlseller_on = ' checked="checked"';
			} else {
				$sales_mode_dlseller_off = ' checked="checked"';
			}
			$auto_settlement_mail_on  = '';
			$auto_settlement_mail_off = '';
			if ( isset( $acting_opts['auto_settlement_mail'] ) && 'on' == $acting_opts['auto_settlement_mail'] ) {
				$auto_settlement_mail_on = ' checked="checked"';
			} else {
				$auto_settlement_mail_off = ' checked="checked"';
			}
			$threedsecure_on  = '';
			$threedsecure_off = '';
			if ( isset( $acting_opts['threedsecure_ryaku'] ) && 'on' == $acting_opts['threedsecure_ryaku'] ) {
				$threedsecure_on = ' checked="checked"';
			} else {
				$threedsecure_off = ' checked="checked"';
			}
			$threedsecure_hc = ( isset( $acting_opts['threedsecure_hc'] ) ) ? $acting_opts['threedsecure_hc'] : '';
			$attempt_on      = '';
			$attempt_off     = '';
			if ( isset( $acting_opts['attempt'] ) && 'on' == $acting_opts['attempt'] ) {
				$attempt_on = ' checked="checked"';
			} else {
				$attempt_off = ' checked="checked"';
			}
			$conv_activate_link   = '';
			$conv_activate_module = '';
			$conv_activate_off    = '';
			if ( isset( $acting_opts['conv_activate'] ) && 'on' == $acting_opts['conv_activate'] ) {
				$conv_activate_link = ' checked="checked"';
			} elseif ( isset( $acting_opts['conv_activate'] ) && 'module' == $acting_opts['conv_activate'] ) {
				$conv_activate_module = ' checked="checked"';
			} else {
				$conv_activate_off = ' checked="checked"';
			}
			$payment_term_day = ( isset( $acting_opts['payment_term_day'] ) ) ? $acting_opts['payment_term_day'] : '5';
			$payment_term_min = ( isset( $acting_opts['payment_term_min'] ) ) ? $acting_opts['payment_term_min'] : '';
			$sales_type_1     = '';
			$sales_type_3     = '';
			if ( isset( $acting_opts['sales_type'] ) && '3' == $acting_opts['sales_type'] ) {
				$sales_type_3 = ' checked="checked"';
			} else {
				$sales_type_1 = ' checked="checked"';
			}
			$cvs_type_01 = '';
			$cvs_type_02 = '';
			$cvs_type_03 = '';
			$cvs_type_04 = '';
			if ( isset( $acting_opts['cvs_type'] ) ) {
				if ( in_array( '01', $acting_opts['cvs_type'] ) ) {
					$cvs_type_01 = ' checked="checked"';
				}
				if ( in_array( '02', $acting_opts['cvs_type'] ) ) {
					$cvs_type_02 = ' checked="checked"';
				}
				if ( in_array( '03', $acting_opts['cvs_type'] ) ) {
					$cvs_type_03 = ' checked="checked"';
				}
				if ( in_array( '04', $acting_opts['cvs_type'] ) ) {
					$cvs_type_04 = ' checked="checked"';
				}
			}
			$atm_activate_link   = '';
			$atm_activate_module = '';
			$atm_activate_off    = '';
			if ( isset( $acting_opts['atm_activate'] ) && 'module' == $acting_opts['atm_activate'] ) {
				$atm_activate_module = ' checked="checked"';
			} else {
				$atm_activate_off = ' checked="checked"';
			}
			$payment_limit_date   = ( isset( $acting_opts['payment_limit_date'] ) ) ? $acting_opts['payment_limit_date'] : '';
			$bank_activate_link   = '';
			$bank_activate_module = '';
			$bank_activate_off    = '';
			if ( isset( $acting_opts['bank_activate'] ) && 'module' == $acting_opts['bank_activate'] ) {
				$bank_activate_module = ' checked="checked"';
			} else {
				$bank_activate_off = ' checked="checked"';
			}
			$asp_payment_term      = ( isset( $acting_opts['asp_payment_term'] ) ) ? $acting_opts['asp_payment_term'] : '0050000';
			$paidy_activate_module = '';
			$paidy_activate_off    = '';
			if ( isset( $acting_opts['paidy_activate'] ) && 'module' == $acting_opts['paidy_activate'] ) {
				$paidy_activate_module = ' checked="checked"';
			} else {
				$paidy_activate_off = ' checked="checked"';
			}
			$paidy_public_key = ( isset( $acting_opts['paidy_public_key'] ) ) ? $acting_opts['paidy_public_key'] : '';
			?>
	<div id="uscestabs_paygent">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
			<?php if ( isset( $_POST['acting'] ) && $this->paymod_id == wp_unslash( $_POST['acting'] ) ) : ?>
				<?php if ( '' != $this->error_mes ) : ?>
	<div class="error_message"><?php wel_esc_script_e( $this->error_mes ); ?></div>
				<?php elseif ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) : ?>
	<div class="message">十分にテストを行ってから運用してください。</div>
				<?php endif; ?>
			<?php endif; ?>
	<form action="" method="post" name="paygent_form" id="paygent_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_seq_merchant_id_paygent">マーチャントID</a></th>
				<td><input name="seq_merchant_id" type="text" id="seq_merchant_id_paygent" value="<?php echo esc_attr( $seq_merchant_id ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_seq_merchant_id_paygent" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるマーチャントID（半角数字）。マーチャントID は試験環境と本番環境で異なります。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_connect_id_paygent">接続ID</a></th>
				<td><input name="connect_id" type="text" id="connect_id_paygent" value="<?php echo esc_attr( $connect_id ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_connect_id_paygent" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行される接続ID（半角英数字）。モジュールタイプで利用するときは必須です。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_connect_password_paygent">接続パスワード</a></th>
				<td><input name="connect_password" type="text" id="connect_password_paygent" value="<?php echo esc_attr( $connect_password ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_connect_password_paygent" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行される接続パスワード（半角英数字）。モジュールタイプで利用するときは必須です。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_hc_paygent">リンクタイプハッシュ生成キー</a></th>
				<td><input name="hc" type="text" id="hc_paygent" value="<?php echo esc_attr( $hc ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_hc_paygent" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるリンクタイプハッシュ生成キー（半角英数字）。リンクタイプで利用するときは必須です。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_conv_hc_paygent">差分通知<br />ハッシュ値生成キー</a></th>
				<td><input name="conv_hc" type="text" id="conv_hc_paygent" value="<?php echo esc_attr( $conv_hc ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_conv_hc_paygent" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行される差分通知ハッシュ値生成キー（半角英数字）。別途「差分通知オプション」のお申し込みが必要です。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_paygent">稼働環境</a></th>
				<td><label><input name="ope" type="radio" id="ope_paygent_test" value="test"<?php echo esc_html( $ope_test ); ?> /><span>テスト環境</span></label><br />
					<label><input name="ope" type="radio" id="ope_paygent_public" value="public"<?php echo esc_html( $ope_public ); ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_ope_paygent" class="explanation"><td colspan="2">動作環境を切り替えます。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_card_activate_paygent">クレジットカード決済</a></th>
				<td><label><input name="card_activate" type="radio" class="activate_paygent_card" id="card_activate_paygent_link" value="on"<?php echo esc_html( $card_activate_link ); ?> /><span>リンクタイプで利用する</span></label><br />
					<label><input name="card_activate" type="radio" class="activate_paygent_card" id="card_activate_paygent_module" value="module"<?php echo esc_html( $card_activate_module ); ?> /><span>モジュールタイプで利用する</span></label><br />
					<label><input name="card_activate" type="radio" class="activate_paygent_card" id="card_activate_paygent_off" value="off"<?php echo esc_html( $card_activate_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_card_activate_paygent" class="explanation"><td colspan="2">モジュールタイプで利用する場合は、申込サービスタイプは「リンク＋モジュール」、モジュール使用は「同意済」になっている必要があります。</td></tr>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_certificate_path_paygent">証明書ファイルパス</a></th>
				<td><input name="certificate_path" type="text" id="certificate_path_paygent" value="<?php echo esc_attr( $certificate_path ); ?>" class="regular-text" disabled="disabled" /><br />
					<input type="button" class="button" value="変更する" id="change_certificate_path" />
					<input name="certificate_path_before" type="hidden" value="<?php echo esc_attr( $certificate_path ); ?>" />
				</td>
			</tr>
			<tr id="ex_certificate_path_paygent" class="explanation paygent_card_form"><td colspan="2">モジュールタイプで利用するときは必須です。</td></tr>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_client_file_paygent">クライアント証明書ファイル</a></th>
				<td><input name="client_file" type="text" id="client_file_paygent" value="<?php echo esc_attr( $client_file ); ?>" class="regular-text"<?php echo esc_html( $client_file_disabled ); ?> /><br />
					<input type="button" class="button" value="アップロード" id="client_file_upload"<?php echo esc_html( $client_file_disabled ); ?> /><span id="client_file_upload_result"></span>
				</td>
			</tr>
			<tr id="ex_client_file_paygent" class="explanation paygent_card_form"><td colspan="2">モジュールタイプで利用するときは必須です。クライアント証明書ファイルは試験環境と本番環境で異なります。アップロードができない場合は wp-content/uploads/ フォルダのパーミッションを確認してください。</td></tr>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_ca_file_paygent">CAファイル</a></th>
				<td><input name="ca_file" type="text" id="ca_file_paygent" value="<?php echo esc_attr( $ca_file ); ?>" class="regular-text"<?php echo esc_html( $ca_file_disabled ); ?> /><br />
					<input type="button" class="button" value="アップロード" id="ca_file_upload"<?php echo esc_html( $ca_file_disabled ); ?> /><span id="ca_file_upload_result"></span>
				</td>
			</tr>
			<tr id="ex_ca_file_paygent" class="explanation paygent_card_form"><td colspan="2">モジュールタイプで利用するときは必須です。アップロードができない場合は wp-content/uploads/ フォルダのパーミッションを確認してください。</td></tr>
			<tr class="paygent_card_form paygent_card_module">
				<th><a class="explanation-label" id="label_ex_token_key_paygent">トークン生成鍵</a></th>
				<td><input name="token_key" type="text" id="token_key_paygent" value="<?php echo esc_attr( $token_key ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_token_key_paygent" class="explanation paygent_card_form paygent_card_module"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるトークン生成鍵（半角英数字）。モジュールタイプで利用するときは必須です。トークン生成鍵は試験環境と本番環境で異なります。</td></tr>
			<tr class="paygent_card_form paygent_card_module">
				<th><a class="explanation-label" id="label_ex_token_hc_paygent">トークン受取ハッシュ鍵</a></th>
				<td><input name="token_hc" type="text" id="token_hc_paygent" value="<?php echo esc_attr( $token_hc ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_token_hc_paygent" class="explanation paygent_card_form paygent_card_module"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるトークン受取ハッシュ鍵（半角英数字）。モジュールタイプで利用するときは必須です。</td></tr>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_payment_class_paygent">支払区分</a></th>
				<td><label><input name="payment_class" type="radio" id="payment_class_paygent_0" value="0"<?php echo esc_html( $payment_class_0 ); ?> /><span>1回払いのみ</span></label><br />
					<label><input name="payment_class" type="radio" id="payment_class_paygent_2" value="1"<?php echo esc_html( $payment_class_1 ); ?> /><span>全て</span></label><br />
					<label><input name="payment_class" type="radio" id="payment_class_paygent_2" value="2"<?php echo esc_html( $payment_class_2 ); ?> /><span>ボーナス一括以外全て</span></label>
				</td>
			</tr>
			<tr id="ex_payment_class_paygent" class="explanation paygent_card_form"><td colspan="2">ユーザーに支払を許可するカード支払方法の区分です。加盟店審査を経て加盟店様ごとに設定された支払可能回数から、購入者に提示する支払回数をさらに絞り込みたい場合に使用してください。</td></tr>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_use_card_conf_number_paygent">カード確認番号<br />利用フラグ</a></th>
				<td><label><input name="use_card_conf_number" type="radio" id="use_card_conf_number_paygent_on" value="on"<?php echo esc_html( $use_card_conf_number_on ); ?> /><span>利用する</span></label><br />
					<label><input name="use_card_conf_number" type="radio" id="use_card_conf_number_paygent_off" value="off"<?php echo esc_html( $use_card_conf_number_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_use_card_conf_number_paygent" class="explanation paygent_card_form"><td colspan="2">確認番号の入力を必須とするかどうかを指定します。確認番号が実際に使用されるかどうかは、カードを発行したイシュアーに依存します。</td></tr>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_stock_card_mode_paygent">カード情報お預りモード</a></th>
				<td><label><input name="stock_card_mode" type="radio" id="stock_card_mode_paygent_on" value="on"<?php echo esc_html( $stock_card_mode_on ); ?> /><span>利用する</span></label><br />
					<label><input name="stock_card_mode" type="radio" id="stock_card_mode_paygent_off" value="off"<?php echo esc_html( $stock_card_mode_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_stock_card_mode_paygent" class="explanation paygent_card_form"><td colspan="2">会員は購入の際に登録済みのカードで支払うことができます。カード番号はペイジェントのシステムに保存されます。利用する場合は、基本設定の「会員システム」を「利用する」に設定してください。<?php echo esc_html( $stock_card_mode_msg ); ?></td></tr>
			<tr class="paygent_card_form paygent_card_module">
				<th><a class="explanation-label" id="label_ex_sales_mode_paygent">同時売上モード</a></th>
				<td><label><input name="sales_mode" type="radio" id="sales_mode_paygent_on" value="1"<?php echo esc_html( $sales_mode_on ); ?> /><span>売上</span></label><br />
					<label><input name="sales_mode" type="radio" id="sales_mode_paygent_off" value="0"<?php echo esc_html( $sales_mode_off ); ?> /><span>オーソリのみ</span></label>
				</td>
			</tr>
			<tr id="ex_sales_mode_paygent" class="explanation paygent_card_form paygent_card_module"><td colspan="2">決済時にオーソリ（与信）のみ実施するか、オーソリ後売上処理を実施するかを指定します。</td></tr>
			<?php if ( defined( 'WCEX_DLSELLER' ) ) : ?>
			<tr class="paygent_card_form paygent_card_module">
				<th><a class="explanation-label" id="label_ex_sales_mode_dlseller_paygent">自動継続課金同時売上モード</a></th>
				<td><label><input name="sales_mode_dlseller" type="radio" id="sales_mode_dlseller_paygent_on" value="1"<?php echo esc_html( $sales_mode_dlseller_on ); ?> /><span>売上</span></label><br />
					<label><input name="sales_mode_dlseller" type="radio" id="sales_mode_dlseller_paygent_off" value="0"<?php echo esc_html( $sales_mode_dlseller_off ); ?> /><span>オーソリのみ</span></label>
				</td>
			</tr>
			<tr id="ex_sales_mode_dlseller_paygent" class="explanation paygent_card_form paygent_card_module"><td colspan="2">自動継続課金（要 WCEX DLSeller）時の同時売上モード。</td></tr>
			<tr class="paygent_card_form paygent_card_module">
				<th><a class="explanation-label" id="label_ex_auto_settlement_mail_paygent">自動継続課金完了メール</a></th>
				<td><label><input name="auto_settlement_mail" type="radio" id="auto_settlement_mail_paygent_on" value="on"<?php echo esc_html( $auto_settlement_mail_on ); ?> /><span><?php esc_html_e( 'Send', 'usces' ); ?></span></label><br />
					<label><input name="auto_settlement_mail" type="radio" id="auto_settlement_mail_paygent_off" value="off"<?php echo esc_html( $auto_settlement_mail_off ); ?> /><span><?php esc_html_e( "Don't send", 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_auto_settlement_mail_paygent" class="explanation paygent_card_form paygent_card_module"><td colspan="2"><?php _e( 'Send billing completion mail to the member on which automatic continuing charging processing (required WCEX DLSeller) is executed.', 'usces' ); ?></td></tr>
			<?php endif; ?>
			<tr class="paygent_card_form">
				<th><a class="explanation-label" id="label_ex_threedsecure_paygent">3Dセキュア</a></th>
				<td><label><input name="threedsecure_ryaku" type="radio" class="activate_paygent_card_threedsecure" id="threedsecure_paygent_on" value="on"<?php echo esc_html( $threedsecure_on ); ?> /><span>契約</span></label><br />
					<label><input name="threedsecure_ryaku" type="radio" class="activate_paygent_card_threedsecure" id="threedsecure_paygent_off" value="off"<?php echo esc_html( $threedsecure_off ); ?> /><span>未契約／利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_threedsecure_paygent" class="explanation paygent_card_form"><td colspan="2">3Dセキュアの契約をした場合のみ「契約」を選択してください。</td></tr>
			<tr class="paygent_card_form paygent_card_threedsecure">
				<th><a class="explanation-label" id="label_ex_threedsecure_hc_paygent">3Dセキュア結果受付ハッシュ鍵</a></th>
				<td><input name="threedsecure_hc" type="text" id="threedsecure_hc_paygent" value="<?php echo esc_attr( $threedsecure_hc ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_threedsecure_hc_paygent" class="explanation paygent_card_form paygent_card_threedsecure"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行される3Dセキュア結果受付ハッシュ鍵（半角英数字）。3Dセキュアを利用するときは必須です。</td></tr>
			<tr class="paygent_card_form paygent_card_threedsecure">
				<th><a class="explanation-label" id="label_ex_attempt_paygent">チャージバックリスク回避</a></th>
				<td><label><input name="attempt" type="radio" id="attempt_paygent_on" value="on"<?php echo esc_html( $attempt_on ); ?> /><span>優先する</span></label><br />
					<label><input name="attempt" type="radio" id="attempt_paygent_off" value="off"<?php echo esc_html( $attempt_off ); ?> /><span>優先しない</span></label>
				</td>
			</tr>
			<tr id="ex_attempt_paygent" class="explanation paygent_card_form paygent_card_threedsecure"><td colspan="2">「優先する」を選択すると、チャージバックリスクが加盟店にある場合の3Dセキュア認証を認証エラー（決済エラー）とします。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_conv_activate_paygent">コンビニ決済（番号方式）</a></th>
				<td><label><input name="conv_activate" type="radio" class="activate_paygent_conv" id="conv_activate_paygent_link" value="on"<?php echo esc_html( $conv_activate_link ); ?> /><span>リンクタイプで利用する</span></label><br />
					<label><input name="conv_activate" type="radio" class="activate_paygent_conv" id="conv_activate_paygent_module" value="module"<?php echo esc_html( $conv_activate_module ); ?> /><span>モジュールタイプで利用する</span></label><br />
					<label><input name="conv_activate" type="radio" class="activate_paygent_conv" id="conv_activate_paygent_off" value="off"<?php echo esc_html( $conv_activate_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_conv_activate_paygent" class="explanation"><td colspan="2">モジュールタイプで利用する場合は、申込サービスタイプは「リンク＋モジュール」、モジュール使用は「同意済」になっている必要があります。</td></tr>
			<tr class="paygent_conv_form">
				<th><a class="explanation-label" id="label_ex_payment_term_day_paygent">支払期間（日指定）</a></th>
				<td><input name="payment_term_day" type="text" id="payment_term_day_paygent" value="<?php echo esc_attr( $payment_term_day ); ?>" class="small-text" /></td>
			</tr>
			<tr id="ex_payment_term_day_paygent" class="explanation paygent_conv_form"><td colspan="2">支払うことのできる期限を日で指定します。指定できる範囲は2以上60以下です。（半角数字）</td></tr>
			<tr class="paygent_conv_form paygent_conv_link">
				<th><a class="explanation-label" id="label_ex_payment_term_min_paygent">支払期間（分指定）</a></th>
				<td><input name="payment_term_min" type="text" id="payment_term_min_paygent" value="<?php echo esc_attr( $payment_term_min ); ?>" class="small-text" /></td>
			</tr>
			<tr id="ex_payment_term_min_paygent" class="explanation paygent_conv_form paygent_conv_link"><td colspan="2">支払うことのできる期限を分で指定します。指定できる範囲は5以上2880以下です。（半角数字）</td></tr>
			<tr class="paygent_conv_form paygent_conv_module">
				<th><a class="explanation-label" id="label_ex_sales_type_paygent">支払種別</a></th>
				<td><label><input name="sales_type" type="radio" class="sales_type_paygent" id="sales_type_paygent_1" value="1"<?php echo esc_html( $sales_type_1 ); ?> /><span>前払い</span></label><br />
					<label><input name="sales_type" type="radio" class="sales_type_paygent" id="sales_type_paygent_3" value="3"<?php echo esc_html( $sales_type_3 ); ?> /><span>後払い</span></label>
				</td>
			</tr>
			<tr id="ex_sales_type_paygent" class="explanation paygent_conv_form paygent_conv_module"><td colspan="2">前払いか後払いかを表す種別です。</td></tr>
			<tr class="paygent_conv_form paygent_conv_module">
				<th rowspan="7"><a class="explanation-label" id="label_ex_cvs_type_paygent">申込コンビニ</a></th>
				<td><label><input name="cvs_type[]" type="checkbox" id="cvs_type_01" value="01"<?php echo esc_html( $cvs_type_01 ); ?> /><span>セイコーマート</span></label></td>
			</tr>
			<tr class="paygent_conv_form paygent_conv_module">
				<td><label><input name="cvs_type[]" type="checkbox" id="cvs_type_02" value="02"<?php echo esc_html( $cvs_type_02 ); ?> /><span>ローソン／ミニストップ／デイリーヤマザキ</span></label></td>
			</tr>
			<tr class="paygent_conv_form paygent_conv_module">
				<td><label><input name="cvs_type[]" type="checkbox" id="cvs_type_03" value="03"<?php echo esc_html( $cvs_type_03 ); ?> /><span>セブンイレブン</span></label></td>
			</tr>
			<tr class="paygent_conv_form paygent_conv_module">
				<td><label><input name="cvs_type[]" type="checkbox" id="cvs_type_04" value="04"<?php echo esc_html( $cvs_type_04 ); ?> /><span>ファミリーマート</span></label></td>
			</tr>
			<tr id="ex_cvs_type_paygent" class="explanation paygent_conv_form paygent_conv_module"><td colspan="2">契約時にご利用のお申込みをいただいたコンビニを選択します。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_atm_activate_paygent">ATM決済（ペイジー）</a></th>
				<td>
					<label><input name="atm_activate" type="radio" class="activate_paygent_atm" id="atm_activate_paygent_module" value="module"<?php echo esc_html( $atm_activate_module ); ?> /><span>利用する</span></label><br />
					<label><input name="atm_activate" type="radio" class="activate_paygent_atm" id="atm_activate_paygent_off" value="off"<?php echo esc_html( $atm_activate_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_atm_activate_paygent" class="explanation"><td colspan="2">モジュールタイプになります。利用する場合は、申込サービスタイプは「リンク＋モジュール」、モジュール使用は「同意済」になっている必要があります。</td></tr>
			<tr class="paygent_atm_form">
				<th><a class="explanation-label" id="label_ex_payment_limit_date_paygent">支払期限日</a></th>
				<td><input name="payment_limit_date" type="text" id="payment_limit_date_paygent" value="<?php echo esc_attr( $payment_limit_date ); ?>" class="small-text" /></td>
			</tr>
			<tr id="ex_payment_limit_date_paygent" class="explanation paygent_atm_form"><td colspan="2">取引発生日時から0日～60日より設定します。設定がない場合は、システムデフォルト値 30日が設定されます。0日とは当日中を指します。（半角数字）</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_bank_activate_paygent">銀行ネット決済（ネットバンキング）</a></th>
				<td>
					<label><input name="bank_activate" type="radio" class="activate_paygent_bank" id="bank_activate_paygent_module" value="module"<?php echo esc_html( $bank_activate_module ); ?> /><span>利用する</span></label><br />
					<label><input name="bank_activate" type="radio" class="activate_paygent_bank" id="bank_activate_paygent_off" value="off"<?php echo esc_html( $bank_activate_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_bank_activate_paygent" class="explanation"><td colspan="2">モジュールタイプになります。利用する場合は、申込サービスタイプは「リンク＋モジュール」、モジュール使用は「同意済」になっている必要があります。</td></tr>
			<tr class="paygent_bank_form">
				<th><a class="explanation-label" id="label_ex_asp_payment_term_paygent">支払期間</a></th>
				<td><select name="asp_payment_term">
			<?php
			foreach ( $this->asp_payment_term as $term => $value ) {
				$selected = ( $asp_payment_term == $term ) ? ' selected="selected"' : '';
				echo '<option value="' . esc_attr( $term ) . '"' . $selected . '>' . esc_html( $value ) . '</option>';
			}
			?>
					</select>
				</td>
			</tr>
			<tr id="ex_asp_payment_term_paygent" class="explanation paygent_bank_form"><td colspan="2">ASP画面有効期限を設定します。すぐに支払ってもらいたい場合は30分程度を指定してください。一般的には長くて7日程度です。デフォルト値は5日です。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_paidy_activate_paygent">Paidy</a></th>
				<td><label><input name="paidy_activate" type="radio" class="activate_paygent_paidy" id="paidy_activate_paygent_module" value="module"<?php echo esc_html( $paidy_activate_module ); ?> /><span>利用する</span></label><br />
					<label><input name="paidy_activate" type="radio" class="activate_paygent_paidy" id="paidy_activate_paygent_off" value="off"<?php echo esc_html( $paidy_activate_off ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_paidy_activate_paygent" class="explanation"><td colspan="2">モジュールタイプになります。利用する場合は、申込サービスタイプは「リンク＋モジュール」、モジュール使用は「同意済」になっている必要があります。</td></tr>
			<tr class="paygent_paidy_form">
				<th><a class="explanation-label" id="label_ex_paidy_public_key_paygent">パブリックキー</a></th>
				<td><input name="paidy_public_key" type="text" id="paidy_public_key_paygent" value="<?php echo esc_attr( $paidy_public_key ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_paidy_public_key_paygent" class="explanation paygent_paidy_form"><td colspan="2">Paidy 加盟店管理画面の設定より「パブリックキー」を取得してください。（半角数字）</td></tr>
		</table>
		<input name="acting" type="hidden" value="paygent" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php echo esc_attr( $this->acting_name ); ?>の設定を更新する" />
			<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div id="upload_dialog_paygent" class="upload_dialog_paygent">
		<form action="<?php echo esc_url( USCES_ADMIN_URL ); ?>" method="post" enctype="multipart/form-data" name="upload_form_paygent" id="upload_form_paygent">
		<p id="filetype_description"></p>
		<fieldset>
			<input type="file" name="upload_file" id="upload_file" />
		</fieldset>
		<input type="button" id="upload_button" class="button" value="アップロード" />
		<input type="hidden" id="upload_filetype" value="" />
		</form>
	</div>
	<div class="settle_exp">
		<p><strong><?php echo esc_attr( $this->acting_formal_name ); ?></strong></p>
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank"><?php echo esc_attr( $this->acting_name ); ?>の詳細はこちら 》</a>
		<p>この決済は「外部リンク型（リンクタイプ）」と「非通過型（モジュールタイプ）」が選択できます。</p>
		<p>リンクタイプでもペイジェントの決済システムと Welcart の決済データの連携機能を利用したい場合は、「クライアント証明書ファイル」と「CAファイル」をサーバーに設置する必要があります。また、ペイジェントの申込サービスタイプは「リンク＋モジュール」モジュール使用は「同意済」になっている必要があります。詳細は「ペイジェント決済導入マニュアル」を参照してください。</p>
		<p>決済通知ステータスの申請は「任意」となっていますが、以下のように申請してください。リンクタイプで利用する場合は※印のステータス通知は必須となります。<br />
【クレジット決済】<br />
　■ 申込済※<br />
　■ オーソリNG<br />
　■ 3Dセキュア中断（3Dセキュア契約時のみ）<br />
　■ オーソリOK<br />
　■ オーソリ取消済<br />
　■ オーソリ期限切<br />
　■ 消込済<br />
　■ 消込済（売上取消期限切）<br />
　■ 売上取消済<br />
【コンビニ決済】<br />
　■ 申込済※<br />
　■ 支払期限切<br />
　■ 消込済※<br />
　■ 速報検知済※<br />
　■ 速報取消済<br />
【ATM決済】<br />
　■ 支払期限切<br />
　■ 消込済※<br />
【銀行ネット決済】<br />
　■ 申込中断<br />
　■ 消込済※<br />
【Paidy】<br />
　■ オーソリNG<br />
　■ オーソリOK<br />
　■ オーソリ取消済<br />
　■ オーソリ期限切<br />
　■ 消込済<br />
　■ 消込済（売上取消期限切）<br />
　■ 売上取消済<br />
		</p>
	</div>
	</div><!--uscestabs_paygent-->
			<?php
		endif;
	}

	/**
	 * 結果通知処理
	 * usces_after_cart_instant
	 */
	public function acting_transaction() {
		global $usces;

		if ( isset( $_POST['trading_id'] ) && isset( $_POST['payment_status'] ) && ! isset( $_POST['purchase'] ) ) {
			$post_data       = Paygent_Module::encording_response_kana( wp_unslash( $_POST ) );
			$acting_opts     = $this->get_acting_settings();
			$original_string = trim( $post_data['payment_notice_id'] ) . trim( $post_data['payment_id'] ) . trim( $post_data['trading_id'] ) . trim( $post_data['payment_type'] ) . trim( $post_data['payment_amount'] ) . trim( $acting_opts['conv_hc'] );
			$hased_string    = hash( 'sha256', $original_string );
			if ( $hased_string === $post_data['hc'] ) {
				$payment_type   = ( isset( $post_data['payment_type'] ) ) ? $post_data['payment_type'] : '';
				$payment_status = $post_data['payment_status'];
				switch ( $payment_type ) {
					/* カード決済 */
					case PAYMENT_TYPE_CREDIT:
						switch ( $payment_status ) {
							case STATUS_REQUESTED: /* 申込済 */
								if ( ! empty( $post_data['trading_id'] ) ) {
									$order_id = $this->get_order_id( $post_data['trading_id'] );
									if ( ! $order_id ) {
										if ( $this->is_activate_card( 'link' ) ) {
											$order_data = usces_restore_order_acting_data( $post_data['trading_id'] );
											if ( $order_data ) {
												$res = $usces->order_processing( $post_data );
												if ( 'ordercompletion' == $res ) {
													$usces->cart->crear_cart();
												} else {
													$log = array(
														'acting' => 'paygent_card',
														'key'    => $post_data['trading_id'],
														'result' => 'ORDER DATA REGISTERED ERROR',
														'data'   => $post_data,
													);
													usces_save_order_acting_error( $log );
												}
												$order_id = $this->get_order_id( $post_data['trading_id'] );
												if ( $order_id ) {
													$this->save_acting_log( $post_data, 'paygent_card', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
												}
											}
										}
									} else {
										$this->save_acting_log( $post_data, 'paygent_card', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
									}
								}
								break;
							case STATUS_AUTHORIZE_NG: /* オーソリNG */
							case STATUS_AUTHORIZE_OK: /* オーソリOK */
							case STATUS_AUTHORIZE_CANCELED: /* オーソリ取消済 */
							case STATUS_AUTHORIZE_EXPIRED: /* オーソリ期限切 */
							case STATUS_CLEARED: /* 消込済 */
							case STATUS_CLEARED_SALES_CANCEL_INVALIDITY: /* 消込済（売上取消期限切） */
							case STATUS_SALES_CANCELED: /* 売上取消済 */
							case STATUS_3DSECURE_INTERRUPTION: /* 3Dセキュア中断 */
							case STATUS_3DSECURE_CERTIFICATION: /* 3Dセキュア認証 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_card', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
								}
								break;
							case STATUS_SALES_CANCELING: /* 売上取消中 */
							case STATUS_SALES_CANCELING_TALLY: /* 売上取消集計中 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_card', $payment_status, RESULT_STATUS_ERROR, $order_id, $post_data['trading_id'] );
								}
								break;
						}
						break;

					/* コンビニ決済 */
					case PAYMENT_TYPE_CONVENI_NUM:
						switch ( $payment_status ) {
							case STATUS_REQUESTED: /* 申込済 */
								if ( ! empty( $post_data['trading_id'] ) ) {
									$order_id = $this->get_order_id( $post_data['trading_id'] );
									if ( ! $order_id ) {
										if ( $this->is_activate_conv( 'link' ) ) {
											$order_data = usces_restore_order_acting_data( $post_data['trading_id'] );
											if ( $order_data ) {
												$res = $usces->order_processing( $post_data );
												if ( 'ordercompletion' == $res ) {
													$usces->cart->crear_cart();
												} else {
													$log = array(
														'acting' => 'paygent_conv',
														'key'    => $post_data['trading_id'],
														'result' => 'ORDER DATA REGISTERED ERROR',
														'data'   => $post_data,
													);
													usces_save_order_acting_error( $log );
												}
												$order_id = $this->get_order_id( $post_data['trading_id'] );
												if ( $order_id ) {
													$this->save_acting_log( $post_data, 'paygent_conv', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
												}
											}
										}
									} else {
										$this->save_acting_log( $post_data, 'paygent_conv', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
									}
								}
								break;
							case STATUS_PRELIMINARY_DETECTED: /* 速報検地済 */
							case STATUS_CLEARED: /* 消込済 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_conv', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
									$order_status = $this->get_order_status( $order_id );
									if ( 0 == $order_status ) {
										$res = usces_change_order_receipt( $order_id, 'receipted' );
										if ( false === $res ) {
											$log = array(
												'acting' => 'paygent_conv',
												'key'    => $post_data['trading_id'],
												'result' => 'ORDER DATA UPDATE ERROR',
												'data'   => $post_data,
											);
											usces_save_order_acting_error( $log );
										} else {
											usces_action_acting_getpoint( $order_id );
											$usces->set_order_meta_value( 'acting_paygent_conv', usces_serialize( $post_data ), $order_id );
										}
									}
								}
								break;
							case STATUS_PRELIMINARY_CANCELED: /* 速報取消済 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_conv', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
									$order_status = $this->get_order_status( $order_id );
									if ( 0 < $order_status ) {
										$res = usces_change_order_receipt( $order_id, 'noreceipt' );
										if ( false === $res ) {
											$log = array(
												'acting' => 'paygent_conv',
												'key'    => $post_data['trading_id'],
												'result' => 'ORDER DATA UPDATE ERROR',
												'data'   => $post_data,
											);
											usces_save_order_acting_error( $log );
										} else {
											usces_action_acting_getpoint( $order_id, false );
										}
									}
								}
								break;
							case STATUS_PAYMENT_EXPIRED: /* 支払期限切 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_conv', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
								}
								break;
						}
						break;

					/* ATM決済 */
					case PAYMENT_TYPE_ATM:
						switch ( $payment_status ) {
							case STATUS_PAYMENT_EXPIRED: /* 支払期限切 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_atm', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
								}
								break;
							case STATUS_CLEARED: /* 消込済 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_atm', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
									$order_status = $this->get_order_status( $order_id );
									if ( 0 == $order_status ) {
										$res = usces_change_order_receipt( $order_id, 'receipted' );
										if ( false === $res ) {
											$log = array(
												'acting' => 'paygent_atm',
												'key'    => $post_data['trading_id'],
												'result' => 'ORDER DATA UPDATE ERROR',
												'data'   => $post_data,
											);
											usces_save_order_acting_error( $log );
										} else {
											usces_action_acting_getpoint( $order_id );
											$usces->set_order_meta_value( 'acting_paygent_atm', usces_serialize( $post_data ), $order_id );
										}
									}
								}
								break;
						}
						break;

					/* 銀行ネット決済 */
					case PAYMENT_TYPE_BANK:
						switch ( $payment_status ) {
							case STATUS_REGISTRATION_SUSPENDED: /* 申込中断 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_bank', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
								}
								break;
							case STATUS_CLEARED: /* 消込済 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_bank', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
									$order_status = $this->get_order_status( $order_id );
									if ( 0 == $order_status ) {
										$res = usces_change_order_receipt( $order_id, 'receipted' );
										if ( false === $res ) {
											$log = array(
												'acting' => 'paygent_bank',
												'key'    => $post_data['trading_id'],
												'result' => 'ORDER DATA UPDATE ERROR',
												'data'   => $post_data,
											);
											usces_save_order_acting_error( $log );
										} else {
											usces_action_acting_getpoint( $order_id );
											$usces->set_order_meta_value( 'acting_paygent_bank', usces_serialize( $post_data ), $order_id );
										}
									}
								}
								break;
						}
						break;

					/* Paidy */
					case PAYMENT_TYPE_PAIDY:
						switch ( $payment_status ) {
							case STATUS_AUTHORIZE_NG: /* オーソリNG */
							case STATUS_AUTHORIZE_OK: /* オーソリOK */
							case STATUS_AUTHORIZE_CANCELED: /* オーソリ取消済 */
							case STATUS_AUTHORIZE_EXPIRED: /* オーソリ期限切 */
							case STATUS_CLEARED: /* 消込済 */
							case STATUS_CLEARED_SALES_CANCEL_INVALIDITY: /* 消込済（売上取消期限切） */
							case STATUS_SALES_CANCELED: /* 売上取消済 */
								$order_id = $this->get_order_id( $post_data['trading_id'] );
								if ( $order_id ) {
									$this->save_acting_log( $post_data, 'paygent_paidy', $payment_status, RESULT_STATUS_NORMAL, $order_id, $post_data['trading_id'] );
								}
								break;
						}
						break;
				}
			}
			die( 'result=0' );
		}
	}

	/**
	 * ポイント即時付与
	 * usces_filter_is_complete_settlement
	 *
	 * @param  boolean $complete Complete the payment.
	 * @param  string  $payment_name Payment name.
	 * @param  string  $status Payment status.
	 * @return boolean
	 */
	public function is_complete_settlement( $complete, $payment_name, $status ) {
		$payment = usces_get_payments_by_name( $payment_name );
		if ( isset( $payment['settlement'] ) && ( 'acting_paygent_card' == $payment['settlement'] || 'acting_paygent_paidy' == $payment['settlement'] ) ) {
			$complete = true;
		}
		return $complete;
	}

	/**
	 * 管理画面決済処理
	 * usces_action_admin_ajax
	 */
	public function admin_ajax() {
		global $usces;

		$post_data = wp_unslash( $_POST );
		$mode      = sanitize_title( $post_data['mode'] );
		$data      = array();

		switch ( $mode ) {
			/* クレジットカード参照 */
			case 'get_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				$member_id  = ( isset( $post_data['member_id'] ) ) ? $post_data['member_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$latest_log = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$data       = $this->settlement_reference_card( $order_id, $trading_id, $payment_id, $member_id );
				wp_send_json( $data );
				break;

			/* クレジットカード売上処理 */
			case 'sales_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_CARD_COMMIT;
				$settlement    = $this->settlement_request( $telegram_kind, $trading_id, $payment_id );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_card', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_card( $order_id, $trading_id, $payment_id );
				}
				$this->save_admin_log( $order_id, $trading_id );
				wp_send_json( $data );
				break;

			/* クレジットカードオーソリ取消 */
			case 'auth_cancel_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_AUTH_CANCEL;
				$settlement    = $this->settlement_request( $telegram_kind, $trading_id, $payment_id );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_card', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_card( $order_id, $trading_id, $payment_id );
				}
				$this->save_admin_log( $order_id, $trading_id );
				wp_send_json( $data );
				break;

			/* クレジットカードオーソリ変更 */
			case 'auth_revise_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				$amount     = ( isset( $post_data['amount'] ) ) ? $post_data['amount'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) || empty( $amount ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_CARD_COMMIT_AUTH_REVISE;
				$settlement    = $this->settlement_request_revise( $telegram_kind, $trading_id, $payment_id, $amount );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_card', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_card( $order_id, $trading_id, $payment_id );
				}
				$this->save_admin_log( $order_id, $trading_id );
				wp_send_json( $data );
				break;

			/* クレジットカード売上取消（キャンセル処理） */
			case 'sales_cancel_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_CARD_COMMIT_CANCEL;
				$settlement    = $this->settlement_request( $telegram_kind, $trading_id, $payment_id );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_card', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_card( $order_id, $trading_id, $payment_id );
				}
				$this->save_admin_log( $order_id, $trading_id );
				wp_send_json( $data );
				break;

			/* クレジットカード売上金額変更（売上変更） */
			case 'sales_revise_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				$amount     = ( isset( $post_data['amount'] ) ) ? $post_data['amount'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) || empty( $amount ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_CARD_COMMIT_REVISE;
				$settlement    = $this->settlement_request_revise( $telegram_kind, $trading_id, $payment_id, $amount );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_card', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_card( $order_id, $trading_id, $payment_id );
				}
				$this->save_admin_log( $order_id, $trading_id );
				wp_send_json( $data );
				break;

			/* クレジットカード新規オーソリ */
			case 'auth_paygent_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				$member_id  = ( isset( $post_data['member_id'] ) ) ? $post_data['member_id'] : '';
				$amount     = ( isset( $post_data['amount'] ) ) ? $post_data['amount'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) || empty( $amount ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				// $trading_id = usces_acting_key();
				$customer   = $this->customer_ref( $member_id );
				if ( isset( $customer['customer_id'] ) && $customer['customer_id'] == $member_id && isset( $customer['customer_card_id'] ) ) {
					$telegram_kind = PAYGENT_CREDIT;

					$pm = Paygent_Module::get_instance();
					$pm->init();
					$pm->req_put( 'trading_id', $trading_id );
					$pm->req_put( 'payment_amount', $amount );
					$pm->req_put( 'payment_class', '10' );
					$pm->req_put( '3dsecure_ryaku', '1' );
					$pm->req_put( 'stock_card_mode', '1' );
					$pm->req_put( 'customer_id', $member_id );
					$pm->req_put( 'customer_card_id', $customer['customer_card_id'] );
					$pm->req_put( 'sales_mode', '0' ); /* Auth */

					$result_post   = $pm->post( $telegram_kind );
					$result_status = $pm->get_result_status();
					if ( ! ( true === $result_post ) || RESULT_STATUS_NORMAL != $result_status ) {
						$response_data = array(
							'result'          => $result_post,
							'result_status'   => $result_status,
							'response_code'   => $pm->get_response_code(),
							'response_detail' => $pm->get_response_detail(),
							'result_message'  => $pm->get_result_message(),
						);
						$this->save_acting_log( $response_data, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
						$status         = 'payment_error';
						$status_name    = $this->get_status_name( $status );
						$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
						$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
						$result        .= $this->settlement_history( $order_id, $trading_id );
						$data['result'] = $result;
						$data['status'] = $status;
					} else {
						$response_data  = $pm->get_response_data();
						if ( ! isset( $response_data['payment_type'] ) ) {
							$response_data['payment_type'] = PAYMENT_TYPE_CREDIT;
						}
						$this->save_acting_log( $response_data, 'paygent_card', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
						$usces->set_order_meta_value( 'acting_paygent_card', usces_serialize( $response_data ), $order_id );
						$payment_id     = ( isset( $response_data['payment_id'] ) ) ? $response_data['payment_id'] : '';
						$data           = $this->settlement_reference_card( $order_id, $trading_id, $payment_id );
					}
					$this->save_admin_log( $order_id, $trading_id );
				} else {
					if ( ! isset( $customer['payment_type'] ) ) {
						$customer['payment_type'] = PAYMENT_TYPE_CREDIT;
					}
					$this->save_acting_log( $customer, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
					$this->save_admin_log( $order_id, $trading_id );
				}				
				wp_send_json( $data );
				break;

			/* コンビニ参照 */
			case 'get_paygent_conv':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log     = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id     = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
				if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
					$status      = 'reference_error';
					$status_name = $this->get_status_name( $status );
					$result     .= '<div class="paygent-settlement-admin conv-error">' . $status_name . '</div>';
				} else {
					$payment_status = ( isset( $settlement_ref['payment_status'] ) ) ? $settlement_ref['payment_status'] : '';
					$status         = $this->get_status( $payment_status );
					$class          = ' conv-' . $status;
					$status_name    = $this->get_status_name( $payment_status );
					$result        .= '<div class="paygent-settlement-admin' . $class . '">' . $status_name . '</div>';
				}
				$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="conv" value="状態更新" /></div>';
				$result        .= $this->settlement_history( $order_id, $trading_id );
				$data['result'] = $result;
				$data['status'] = $status;
				wp_send_json( $data );
				break;

			/* ATM参照 */
			case 'get_paygent_atm':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log     = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id     = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
				if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
					$status      = 'reference_error';
					$status_name = $this->get_status_name( $status );
					$result     .= '<div class="paygent-settlement-admin atm-error">' . $status_name . '</div>';
				} else {
					$payment_status = ( isset( $settlement_ref['payment_status'] ) ) ? $settlement_ref['payment_status'] : '';
					$status         = $this->get_status( $payment_status );
					$class          = ' atm-' . $status;
					$status_name    = $this->get_status_name( $payment_status );
					$result        .= '<div class="paygent-settlement-admin' . $class . '">' . $status_name . '</div>';
				}
				$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="atm" value="状態更新" /></div>';
				$result        .= $this->settlement_history( $order_id, $trading_id );
				$data['result'] = $result;
				$data['status'] = $status;
				wp_send_json( $data );
				break;

			/* 銀行ネット決済参照 */
			case 'get_paygent_bank':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result        = '';
				$status        = '';
				$acting_status = '';

				$latest_log     = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id     = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
				if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
					if ( 'bank_applying' == $latest_log['status'] ) {
						$status      = 'bank_applying';
						$status_name = $this->get_status_name( $status );
						$result     .= '<div class="paygent-settlement-admin bank-applying">' . $status_name . '</div>';
					} else {
						$status      = 'reference_error';
						$status_name = $this->get_status_name( $status );
						$result     .= '<div class="paygent-settlement-admin bank-error">' . $status_name . '</div>';
					}
				} else {
					$payment_status = ( isset( $settlement_ref['payment_status'] ) ) ? $settlement_ref['payment_status'] : '';
					$status         = $this->get_status( $payment_status );
					$class          = ' bank-' . $status;
					$status_name    = $this->get_status_name( $payment_status );
					$result        .= '<div class="paygent-settlement-admin' . $class . '">' . $status_name . '</div>';
					if ( 'bank_applying' == $latest_log['status'] && STATUS_REQUESTED == $payment_status ) {
						$this->save_acting_log( $settlement_ref, 'paygent_bank', $payment_status, RESULT_STATUS_NORMAL, $order_id, $trading_id );
						$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
					}
				}
				$result               .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="bank" value="状態更新" /></div>';
				$result               .= $this->settlement_history( $order_id, $trading_id );
				$data['result']        = $result;
				$data['status']        = $status;
				$data['acting_status'] = $acting_status;
				wp_send_json( $data );
				break;

			/* Paidy 参照 */
			case 'get_paygent_paidy':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$latest_log = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$data       = $this->settlement_reference_paidy( $order_id, $trading_id, $payment_id );
				wp_send_json( $data );
				break;

			/* Paidy オーソリキャンセル */
			case 'cancel_paygent_paidy':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_PAIDY_AUTH_CANCEL;
				$settlement    = $this->settlement_request_paidy( $telegram_kind, $trading_id, $payment_id );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_paidy', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin paidy-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_paidy', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_paidy( $order_id, $trading_id );
				}
				wp_send_json( $data );
				break;

			/* Paidy 売上 */
			case 'capture_paygent_paidy':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_PAIDY_CAPTURE;
				$settlement    = $this->settlement_request_paidy( $telegram_kind, $trading_id, $payment_id );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_paidy', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin paidy-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_paidy', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_paidy( $order_id, $trading_id );
				}
				wp_send_json( $data );
				break;

			/* Paidy 返金 */
			case 'refund_paygent_paidy':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id   = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
				$amount     = ( isset( $post_data['amount'] ) ) ? $post_data['amount'] : '';
				if ( empty( $order_id ) || empty( $trading_id ) || empty( $amount ) ) {
					$data['status'] = 'reference_error';
					wp_send_json( $data );
					break;
				}

				$result = '';
				$status = '';

				$latest_log    = $this->get_acting_latest_log( $order_id, $trading_id );
				$payment_id    = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$telegram_kind = PAYGENT_PAIDY_REFUND;
				$settlement    = $this->settlement_request_paidy_refund( $telegram_kind, $trading_id, $amount, $payment_id );
				if ( isset( $settlement['result_status'] ) && RESULT_STATUS_ERROR == $settlement['result_status'] ) {
					$this->save_acting_log( $settlement, 'paygent_paidy', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					$status         = 'payment_error';
					$status_name    = $this->get_status_name( $status );
					$result        .= '<div class="paygent-settlement-admin paidy-error">' . $status_name . '</div>';
					$result        .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" /></div>';
					$result        .= $this->settlement_history( $order_id, $trading_id );
					$data['result'] = $result;
					$data['status'] = $status;
				} else {
					$this->save_acting_log( $settlement, 'paygent_paidy', $telegram_kind, RESULT_STATUS_NORMAL, $order_id, $trading_id );
					$data = $this->settlement_reference_paidy( $order_id, $trading_id );
				}
				wp_send_json( $data );
				break;

			/* 継続課金情報更新 */
			case 'continuation_update':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id         = ( isset( $post_data['order_id'] ) ) ? $post_data['order_id'] : '';
				$member_id        = ( isset( $post_data['member_id'] ) ) ? $post_data['member_id'] : '';
				$contracted_year  = ( isset( $post_data['contracted_year'] ) ) ? $post_data['contracted_year'] : '';
				$contracted_month = ( isset( $post_data['contracted_month'] ) ) ? $post_data['contracted_month'] : '';
				$contracted_day   = ( isset( $post_data['contracted_day'] ) ) ? $post_data['contracted_day'] : '';
				$charged_year     = ( isset( $post_data['charged_year'] ) ) ? $post_data['charged_year'] : '';
				$charged_month    = ( isset( $post_data['charged_month'] ) ) ? $post_data['charged_month'] : '';
				$charged_day      = ( isset( $post_data['charged_day'] ) ) ? $post_data['charged_day'] : '';
				$price            = ( isset( $post_data['price'] ) ) ? $post_data['price'] : 0;
				$status           = ( isset( $post_data['status'] ) ) ? $post_data['status'] : '';

				$continue_data = $this->get_continuation_data( $member_id, $order_id );
				if ( ! $continue_data ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				/* 継続中→停止 */
				if ( 'continuation' == $continue_data['status'] && 'cancellation' == $status ) {
					$this->update_continuation_data( $member_id, $order_id, $continue_data, true );
				} else {
					if ( ! empty( $contracted_year ) && ! empty( $contracted_month ) && ! empty( $contracted_day ) ) {
						$contracted_date = ( empty( $continue_data['contractedday'] ) ) ? dlseller_next_contracting( $order_id ) : $continue_data['contractedday'];
						if ( $contracted_date ) {
							$new_contracted_date = $contracted_year . '-' . $contracted_month . '-' . $contracted_day;
							if ( ! $this->isdate( $new_contracted_date ) ) {
								$data['status']  = 'NG';
								$data['message'] = __( 'Next contract renewal date is incorrect.', 'dlseller' );
								wp_send_json( $data );
							}
						}
					} else {
						$new_contracted_date = '';
					}
					$new_charged_date = $charged_year . '-' . $charged_month . '-' . $charged_day;
					if ( ! $this->isdate( $new_charged_date ) ) {
						$data['status']  = 'NG';
						$data['message'] = __( 'Next settlement date is incorrect.', 'dlseller' );
						wp_send_json( $data );
					}
					$tomorrow = date_i18n( 'Y-m-d', strtotime( '+1 day' ) );
					if ( $new_charged_date < $tomorrow ) {
						$data['status']  = 'NG';
						$data['message'] = sprintf( __( 'The next settlement date must be after %s.', 'dlseller' ), $tomorrow );
						wp_send_json( $data );
					}
					$continue_data['contractedday'] = $new_contracted_date;
					$continue_data['chargedday']    = $new_charged_date;
					$continue_data['price']         = usces_crform( $price, false, false, 'return', false );
					$continue_data['status']        = $status;
					$this->update_continuation_data( $member_id, $order_id, $continue_data );
				}
				$data['status'] = 'OK';
				wp_send_json( $data );
				break;
		}
	}

	/**
	 * クレジットカード決済履歴
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @param  string $payment_id Payment ID.
	 * @param  int    $member_id Member ID.
	 * @return array
	 */
	private function settlement_reference_card( $order_id, $trading_id, $payment_id, $member_id = '' ) {
		global $usces;

		$data           = array();
		$result         = '';
		$status         = '';
		$acting_status  = '';

		if ( empty( $payment_id ) ) {
			$latest_log = $this->get_acting_latest_log( $order_id, $trading_id, RESULT_STATUS_ALL );
			if ( isset( $latest_log['status'] ) && ( 'payment_error' == $latest_log['status'] || 'customer_error' == $latest_log['status'] ) ) {
				$customer = $this->customer_ref( $member_id );
				if ( isset( $customer['customer_id'] ) && $customer['customer_id'] == $member_id ) {
					$order_data = $usces->get_order_data( $order_id, 'direct' );
					$amount     = $order_data['order_item_total_price'] - $order_data['order_usedpoint'] + $order_data['order_discount'] + $order_data['order_shipping_charge'] + $order_data['order_cod_fee'] + $order_data['order_tax'];
					$result    .= '<table class="settlement-admin-table">';
					$result    .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
					$result    .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
					$result    .= '</tr></table>';
					$result    .= '<div class="paygent-settlement-admin-button">';
					$result    .= '<input id="auth-settlement" type="button" class="button" value="新規オーソリ" />';
					$result    .= '<input id="update-status" type="button" class="button" data-mode="card" value="状態更新" />';
					$result    .= '</div>';
				} else {
					$status  = 'error';
					$result .= '<div class="paygent-settlement-admin card-error">' . __( 'Credit card information not registered', 'usces' ) . '</div>';
					$result .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
				}
			} else {
				$status      = 'error';
				$status_name = $this->get_status_name( $status );
				$result     .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
				$result     .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
			}
		} else {
			$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
			if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
				$status      = 'reference_error';
				$status_name = $this->get_status_name( $status );
				$result     .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
				$result     .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
			} elseif ( isset( $settlement_ref['payment_status'] ) ) {
				$payment_status = $settlement_ref['payment_status'];
				$status         = $this->get_status( $payment_status );
				$class          = ' card-' . $status;
				$status_name    = $this->get_status_name( $payment_status );
				$amount         = $settlement_ref['payment_amount'];
				$result        .= '<div class="paygent-settlement-admin' . $class . '">' . $status_name . '</div>';
				switch ( $payment_status ) {
					case STATUS_AUTHORIZE_EXPIRED: /* オーソリ期限切 */
					case STATUS_AUTHORIZE_CANCELED: /* オーソリ取消済 */
						$result .= '<table class="settlement-admin-table">';
						$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$result .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
						$result .= '</tr></table>';
						$result .= '<div class="paygent-settlement-admin-button">';
						$result .= '<input id="auth-settlement" type="button" class="button" value="新規オーソリ" />';
						$result .= '<input id="update-status" type="button" class="button" data-mode="card" value="状態更新" />';
						$result .= '</div>';
						break;
					case STATUS_AUTHORIZE_OK: /* オーソリOK */
						$result .= '<table class="settlement-admin-table">';
						$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$result .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
						$result .= '</tr></table>';
						$result .= '<div class="paygent-settlement-admin-button">';
						$result .= '<input id="sales-settlement" type="button" class="button" value="売上処理" />';
						$result .= '<input id="auth-revise-settlement" type="button" class="button" value="金額変更" />';
						$result .= '<input id="auth-cancel-settlement" type="button" class="button" value="キャンセル処理" />';
						$result .= '<input id="update-status" type="button" class="button" data-mode="card" value="状態更新" />';
						$result .= '</div>';
						break;
					case STATUS_AUTHORIZE_CANCELING: /* オーソリ取消中 */
						$result .= '<table class="settlement-admin-table">';
						$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$result .= '<td><input type="tel" class="settlement-amount" value="' . intval( $amount ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
						$result .= '</tr></table>';
						$result .= '<div class="paygent-settlement-admin-button">';
						$result .= '<input id="auth-cancel-settlement" type="button" class="button" value="キャンセル処理" />';
						$result .= '</div>';
						break;
					case STATUS_SALES_REQUEST_PENDING: /* 売上要求中 */
						$result .= '<table class="settlement-admin-table">';
						$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$result .= '<td><input type="tel" class="settlement-amount" value="' . intval( $amount ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
						$result .= '</tr></table>';
						$result .= '<div class="paygent-settlement-admin-button">';
						$result .= '<input id="sales-settlement" type="button" class="button" value="売上処理" />';
						$result .= '</div>';
						break;
					case STATUS_CLEARED: /* 消込済 */
						$result .= '<table class="settlement-admin-table">';
						$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$result .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
						$result .= '</tr></table>';
						$result .= '<div class="paygent-settlement-admin-button">';
						$result .= '<input id="sales-revise-settlement" type="button" class="button" value="金額変更" />';
						$result .= '<input id="sales-cancel-settlement" type="button" class="button" value="キャンセル処理" />';
						$result .= '<input id="update-status" type="button" class="button" data-mode="card" value="状態更新" />';
						$result .= '</div>';
						break;
					case STATUS_SALES_CANCELING: /* 売上取消中 */
					case STATUS_SALES_CANCELING_TALLY: /* 売上取消集計中 */
						$result .= '<table class="settlement-admin-table">';
						$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$result .= '<td><input type="tel" class="settlement-amount" value="' . intval( $amount ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
						$result .= '</tr></table>';
						$result .= '<div class="paygent-settlement-admin-button">';
						$result .= '<input id="sales-cancel-settlement" type="button" class="button" value="キャンセル処理" />';
						$result .= '</div>';
						break;
					default:
						$result .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
				}
				$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
			} else {
				$status      = 'error';
				$status_name = $this->get_status_name( $status );
				$result     .= '<div class="paygent-settlement-admin card-error">' . $status_name . '</div>';
				$result     .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="card" value="状態更新" /></div>';
			}
		}
		$result               .= $this->settlement_history( $order_id, $trading_id );
		$data['result']        = $result;
		$data['status']        = $status;
		$data['acting_status'] = $acting_status;
		return $data;
	}

	/**
	 * Paidy 決済履歴
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @param  string $payment_id Payment ID.
	 * @return array
	 */
	private function settlement_reference_paidy( $order_id, $trading_id, $payment_id = '' ) {
		$data           = array();
		$result         = '';
		$status         = '';
		$acting_status  = '';
		$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
		if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
			$status      = 'reference_error';
			$status_name = $this->get_status_name( $status );
			$result     .= '<div class="paygent-settlement-admin paidy-error">' . $status_name . '</div>';
			$result     .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" /></div>';
		} elseif ( isset( $settlement_ref['payment_status'] ) ) {
			$payment_status = $settlement_ref['payment_status'];
			$status         = $this->get_status( $payment_status );
			$class          = ' paidy-' . $status;
			$status_name    = $this->get_status_name( $payment_status );
			$amount         = $settlement_ref['payment_amount'];
			$result        .= '<div class="paygent-settlement-admin' . $class . '">' . $status_name . '</div>';
			switch ( $payment_status ) {
				case STATUS_AUTHORIZE_OK: /* オーソリOK */
					$result .= '<table class="settlement-admin-table">';
					$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
					$result .= '<td><input type="tel" class="settlement-amount" value="' . intval( $amount ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
					$result .= '</tr></table>';
					$result .= '<div class="paygent-settlement-admin-button">';
					$result .= '<input id="paidy-capture-settlement" type="button" class="button" value="売上処理" />';
					$result .= '<input id="paidy-cancel-settlement" type="button" class="button" value="オーソリキャンセル処理" />';
					$result .= '<input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" />';
					$result .= '</div>';
					break;
				case STATUS_CLEARED: /* 消込済 */
					$result .= '<table class="settlement-admin-table">';
					$result .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
					$result .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
					$result .= '</tr></table>';
					$result .= '<div class="paygent-settlement-admin-button">';
					$result .= '<input id="paidy-refund-settlement" type="button" class="button" value="返金処理" />';
					$result .= '<input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" />';
					$result .= '</div>';
					break;
				default:
					$result .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" /></div>';
			}
			$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
		} else {
			$status      = 'error';
			$status_name = $this->get_status_name( $status );
			$result     .= '<div class="paygent-settlement-admin paidy-error">' . $status_name . '</div>';
			$result     .= '<div class="paygent-settlement-admin-button"><input id="update-status" type="button" class="button" data-mode="paidy" value="状態更新" /></div>';
		}
		$result               .= $this->settlement_history( $order_id, $trading_id );
		$data['result']        = $result;
		$data['status']        = $status;
		$data['acting_status'] = $acting_status;
		return $data;
	}

	/**
	 * 決済履歴
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @return string
	 */
	private function settlement_history( $order_id, $trading_id ) {
		$history  = '';
		$log_data = $this->get_acting_log( $order_id, $trading_id, RESULT_STATUS_ALL );
		if ( $log_data ) {
			$num      = count( $log_data );
			$history  = '<table class="settlement-history">';
			$history .= '<thead class="settlement-history-head">';
			$history .= '<tr><th></th><th>' . __( 'Processing date', 'usces' ) . '</th><th>決済ID</th><th>' . __( 'Processing classification', 'usces' ) . '</th><th>' . __( 'Amount', 'usces' ) . '</th><th>' . __( 'Result', 'usces' ) . '</th></tr>';
			$history .= '</thead>';
			$history .= '<tbody class="settlement-history-body">';
			foreach ( (array) $log_data as $data ) {
				$log        = usces_unserialize( $data['log'] );
				$payment_id = ( isset( $log['payment_id'] ) ) ? $log['payment_id'] : '';
				if ( empty( $payment_id ) && isset( $log['paidy_id'] ) ) {
					$payment_id = $log['paidy_id'];
				}
				$status_name = ( isset( $data['status'] ) ) ? $this->get_status_name( $data['status'] ) : '';
				$err_code    = '';
				$class       = '';
				if ( 2 == strlen( $data['status'] ) || 0 < $data['amount'] ) {
					$amount = usces_crform( $data['amount'], false, true, 'return', true );
				} else {
					$amount = '';
				}
				if ( RESULT_STATUS_NORMAL != $data['result'] ) {
					$err_code = ( isset( $log['result_message'] ) ) ? $log['result_message'] : '';
					$class    = ' error';
				}
				$history .= '<tr>';
				$history .= '<td class="num">' . $num . '</td>';
				$history .= '<td class="datetime">' . $data['datetime'] . '</td>';
				$history .= '<td class="transactionid">' . $payment_id . '</td>';
				$history .= '<td class="status">' . $status_name . '</td>';
				$history .= '<td class="amount">' . $amount . '</td>';
				$history .= '<td class="result' . $class . '">' . $err_code . '</td>';
				$history .= '</tr>';
				$num--;
			}
			$history .= '</tbody>';
			$history .= '</table>';
		}
		return $history;
	}

	/**
	 * 決済状況
	 * usces_filter_orderlist_detail_value
	 *
	 * @param  string $detail HTML.
	 * @param  string $value Settlement info key.
	 * @param  string $key Settlement info value.
	 * @param  int    $order_id Order number.
	 * @return array
	 */
	public function orderlist_settlement_status( $detail, $value, $key, $order_id ) {
		global $usces;

		if ( 'wc_trans_id' != $key || empty( $value ) ) {
			return $detail;
		}

		$acting_flg = $this->get_order_acting_flg( $order_id );
		if ( in_array( $acting_flg, $this->pay_method ) ) {
			$class          = '';
			$status_name    = '';
			$trading_id     = $this->get_trading_id( $order_id );
			$payment_status = $this->get_payment_status( $order_id, $trading_id );
			if ( ! empty( $payment_status ) ) {
				switch ( $acting_flg ) {
					case 'acting_paygent_card':
						$class       = ' card-' . $this->get_status( $payment_status );
						$status_name = $this->get_status_name( $payment_status );
						break;
					case 'acting_paygent_conv':
						$class       = ' conv-' . $this->get_status( $payment_status );
						$status_name = $this->get_status_name( $payment_status );
						break;
					case 'acting_paygent_atm':
						$class       = ' atm-' . $this->get_status( $payment_status );
						$status_name = $this->get_status_name( $payment_status );
						break;
					case 'acting_paygent_bank':
						$class       = ' bank-' . $this->get_status( $payment_status );
						$status_name = $this->get_status_name( $payment_status );
						break;
					case 'acting_paygent_paidy':
						$class       = ' paidy-' . $this->get_status( $payment_status );
						$status_name = $this->get_status_name( $payment_status );
						break;
				}
			}
			if ( ! empty( $class ) && ! empty( $status_name ) ) {
				$detail = '<td>' . esc_html( $value ) . '<span class="acting-status' . esc_html( $class ) . '">' . esc_html( $status_name ) . '</span></td>';
			}
		}
		return $detail;
	}

	/**
	 * 受注編集画面【ステータス】
	 * usces_action_order_edit_form_status_block_middle
	 *
	 * @param  array $data Order data.
	 * @param  array $cscs_meta Custom field data.
	 * @param  array $action_args Compact array( 'order_action', 'order_id', 'cart' ).
	 */
	public function settlement_status( $data, $cscs_meta, $action_args ) {
		global $usces;
		extract( $action_args );

		if ( 'new' != $order_action && ! empty( $order_id ) ) {
			$payment    = usces_get_payments_by_name( $data['order_payment_name'] );
			$acting_flg = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';
			if ( in_array( $acting_flg, $this->pay_method ) ) {
				$trading_id     = $this->get_trading_id( $order_id );
				$payment_status = $this->get_payment_status( $order_id, $trading_id );
				if ( ! empty( $payment_status ) ) {
					$class       = '';
					$status_name = '';
					switch ( $acting_flg ) {
						case 'acting_paygent_card':
							$class       = ' card-' . $this->get_status( $payment_status );
							$status_name = $this->get_status_name( $payment_status );
							break;
						case 'acting_paygent_conv':
							$class       = ' conv-' . $this->get_status( $payment_status );
							$status_name = $this->get_status_name( $payment_status );
							break;
						case 'acting_paygent_atm':
							$class       = ' atm-' . $this->get_status( $payment_status );
							$status_name = $this->get_status_name( $payment_status );
							break;
						case 'acting_paygent_bank':
							$class       = ' bank-' . $this->get_status( $payment_status );
							$status_name = $this->get_status_name( $payment_status );
							break;
						case 'acting_paygent_paidy':
							$class       = ' paidy-' . $this->get_status( $payment_status );
							$status_name = $this->get_status_name( $payment_status );
							break;
					}
					if ( ! empty( $status_name ) ) {
						echo '
						<tr>
							<td class="label status">' . esc_html__( 'Settlement status', 'usces' ) . '</td>
							<td class="col1 status"><span id="settlement-status"><span class="acting-status' . esc_attr( $class ) . '">' . esc_html( $status_name ) . '</span></span></td>
						</tr>';
					}
				}
			}
		}
	}

	/**
	 * 受注編集画面【支払情報】
	 * usces_action_order_edit_form_settle_info
	 *
	 * @param  array $data Order data.
	 * @param  array $action_args Compact array( 'order_action', 'order_id', 'cart' ).
	 */
	public function settlement_information( $data, $action_args ) {
		global $usces;
		extract( $action_args );

		if ( 'new' != $order_action && ! empty( $order_id ) ) {
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( isset( $payment['settlement'] ) && in_array( $payment['settlement'], $this->pay_method ) ) {
				$acting_data = usces_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
				$trading_id  = ( isset( $acting_data['trading_id'] ) ) ? $acting_data['trading_id'] : '9999999999';
				if ( ! empty( $trading_id ) ) {
					echo '<input type="button" class="button settlement-information" id="settlement-information-' . esc_html( $trading_id ) . '" data-trading_id="' . esc_attr( $trading_id ) . '" data-num="1" value="' . esc_attr__( 'Settlement info', 'usces' ) . '">';
				}
			}
		}
	}

	/**
	 * 決済情報ダイアログ
	 * usces_action_endof_order_edit_form
	 *
	 * @param  array $data Order data.
	 * @param  array $action_args Compact array( 'order_action', 'order_id', 'cart' ).
	 */
	public function settlement_dialog( $data, $action_args ) {
		global $usces;
		extract( $action_args );

		if ( 'new' != $order_action && ! empty( $order_id ) ) :
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( isset( $payment['settlement'] ) && in_array( $payment['settlement'], $this->pay_method ) ) :
				?>
<div id="settlement_dialog" title="">
	<div id="settlement-response-loading"></div>
	<fieldset>
	<div id="settlement-response"></div>
	<input type="hidden" id="order_num">
	<input type="hidden" id="trading_id">
	<input type="hidden" id="acting" value="<?php echo esc_attr( $payment['settlement'] ); ?>">
	<input type="hidden" id="error">
	</fieldset>
</div>
				<?php
			endif;
		endif;
	}

	/**
	 * 受注データから取得する決済情報のキー
	 * usces_filter_settle_info_field_meta_keys
	 *
	 * @param  array $keys Settlement information key.
	 * @return array
	 */
	public function settlement_info_field_meta_keys( $keys ) {
		$keys = array_merge( $keys, array( 'payment_type', 'trading_id', 'split_count' ) );
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
		if ( isset( $fields['payment_type'] ) ) {
			switch ( $fields['payment_type'] ) {
				case PAYMENT_TYPE_CREDIT:
					$keys = array( 'trading_id', 'payment_type', 'split_count' );
					break;
				case PAYMENT_TYPE_CONVENI_NUM:
				case PAYMENT_TYPE_ATM:
				case PAYMENT_TYPE_BANK:
					$keys = array( 'trading_id', 'payment_type', 'payment_date', 'payment_limit_date' );
					break;
				case PAYMENT_TYPE_PAIDY:
					$keys = array( 'trading_id', 'payment_type' );
					break;
			}
		}
		return $keys;
	}

	/**
	 * 受注編集画面に表示する決済情報の値整形
	 * usces_filter_settle_info_field_value
	 *
	 * @param  string $value Settlement information value.
	 * @param  string $key Settlement information key.
	 * @param  string $acting Acting type.
	 * @return string
	 */
	public function settlement_info_field_value( $value, $key, $acting ) {
		switch ( $key ) {
			case 'payment_type':
				switch ( $value ) {
					case PAYMENT_TYPE_CREDIT:
						$value = $this->acting_name . ' カード決済';
						break;
					case PAYMENT_TYPE_CONVENI_NUM:
						$value = $this->acting_name . ' コンビニ決済';
						break;
					case PAYMENT_TYPE_ATM:
						$value = $this->acting_name . ' ATM決済';
						break;
					case PAYMENT_TYPE_BANK:
						$value = $this->acting_name . ' 銀行ネット決済';
						break;
					case PAYMENT_TYPE_PAIDY:
						$value = $this->acting_name . ' Paidy';
						break;
				}
				break;

			case 'split_count':
				switch ( $value ) {
					case '01':
						$value = __( 'One time payment', 'usces' );
						break;
					case '02':
					case '03':
					case '05':
					case '06':
					case '10':
					case '12':
					case '15':
					case '18':
					case '20':
					case '24':
						$times = (int) $value;
						$value = $times . __( '-time payment', 'usces' );
						break;
					case '23':
						$value = __( 'Bonus lump-sum payment', 'usces' );
						break;
					case '80':
						$value = __( 'Libor Funding pay', 'usces' );
						break;
				}
		}
		return $value;
	}

	/**
	 * 管理画面送信メール
	 * usces_filter_order_confirm_mail_payment
	 *
	 * @param  string $msg_payment Payment information message.
	 * @param  int    $order_id Order number.
	 * @param  array  $payment Payment data.
	 * @param  array  $cart Cart data.
	 * @param  array  $order_data Order data.
	 * @return string
	 */
	public function order_confirm_mail_payment( $msg_payment, $order_id, $payment, $cart, $order_data ) {
		global $usces;

		switch ( $payment['settlement'] ) {
			case 'acting_paygent_card':
				$acting_opts = $this->get_acting_settings();
				if ( 0 !== (int) $acting_opts['payment_class'] ) {
					$trading_id = $this->get_trading_id( $order_id );
					$latest_log = $this->get_acting_latest_log( $order_id, $trading_id );
					if ( isset( $latest_log['log']['split_count'] ) && isset( $latest_log['log']['payment_class'] ) ) {
						switch ( $latest_log['log']['payment_class'] ) {
							case '10':
								$split_count = __( '(', 'usces' ) . __( 'One time payment', 'usces' ) . __( ')', 'usces' );
								break;
							case '23':
								$split_count = __( '(', 'usces' ) . __( 'Bonus lump-sum payment', 'usces' ) . __( ')', 'usces' );
								break;
							case '80':
								$split_count = __( '(', 'usces' ) . __( 'Libor Funding pay', 'usces' ) . __( ')', 'usces' );
								break;
							default:
								$times = (int) $latest_log['log']['split_count'];
								if ( 0 < $times ) {
									$split_count = __( '(', 'usces' ) . $times . __( '-time payment', 'usces' ) . __( ')', 'usces' );
								}
						}
						if ( ! empty( $split_count ) ) {
							if ( usces_is_html_mail() ) {
								$msg_payment  = '<tr><td colspan="2" style="padding: 0 0 25px 0;">';
								$msg_payment .= $payment['name'] . $split_count;
								$msg_payment .= '</td></tr>';
							} else {
								$msg_payment  = __( '** Payment method **', 'usces' ) . "\r\n";
								$msg_payment .= usces_mail_line( 1, $order_data['order_email'] );
								$msg_payment .= $payment['name'] . $split_count;
								$msg_payment .= "\r\n\r\n";
							}
						}
					}
				}
				break;
			case 'acting_paygent_conv':
				$mode = ( isset( $_POST['mode'] ) ) ? wp_unslash( $_POST['mode'] ) : '';
				if ( 'orderConfirmMail' == $mode || 'changeConfirmMail' == $mode ) {
					$acting_data           = usces_unserialize( $usces->get_order_meta_value( 'acting_paygent_conv', $order_id ) );
					$usable_cvs_company_id = ( isset( $acting_data['usable_cvs_company_id'] ) ) ? $acting_data['usable_cvs_company_id'] : '';
					$receipt_number        = ( isset( $acting_data['receipt_number'] ) ) ? $acting_data['receipt_number'] : '';
					if ( usces_is_html_mail() ) {
						$msg_payment  = '<tr><td colspan="2" style="padding: 0 0 25px 0;">';
						$msg_payment .= $payment['name'] . '<br><br>';
						$lb           = '<br>';
					} else {
						$lb = "\r\n";
					}
					if ( ! empty( $receipt_number ) ) {
						if ( false !== strpos( $usable_cvs_company_id, CODE_SEICOMART ) ) {
							$msg_payment .= $this->cvs_company_a[ CODE_SEICOMART ] . 'でのお支払い' . $lb;
							$msg_payment .= '受付番号：' . $receipt_number . $lb;
						}
						if ( false !== strpos( $usable_cvs_company_id, CODE_SEVENELEVEN ) ) {
							$msg_payment .= $this->cvs_company_a[ CODE_SEVENELEVEN ] . 'でのお支払い' . $lb;
							$msg_payment .= '払込票番号：' . $receipt_number . $lb;
						}
						if ( false !== strpos( $usable_cvs_company_id, CODE_LOWSON ) ) {
							$msg_payment .= $this->cvs_company_a[ CODE_LOWSON ] . 'でのお支払い' . $lb;
							$msg_payment .= 'お客様番号：' . $receipt_number . $lb;
							$msg_payment .= '確認番号：' . CONVENI_CONFIRMATION_NUMBER . $lb;
						}
						if ( false !== strpos( $usable_cvs_company_id, CODE_MINISTOP ) ) {
							$msg_payment .= $this->cvs_company_a[ CODE_MINISTOP ] . 'でのお支払い' . $lb;
							$msg_payment .= 'お客様番号：' . $receipt_number . $lb;
							$msg_payment .= '確認番号：' . CONVENI_CONFIRMATION_NUMBER . $lb;
						}
						if ( false !== strpos( $usable_cvs_company_id, CODE_FAMILYMART ) ) {
							$msg_payment .= $this->cvs_company_a[ CODE_FAMILYMART ] . 'でのお支払い' . $lb;
							$msg_payment .= '収納番号：' . $receipt_number . $lb;
						}
						if ( false !== strpos( $usable_cvs_company_id, CODE_YAMAZAKI ) ) {
							$msg_payment .= $this->cvs_company_a[ CODE_YAMAZAKI ] . 'でのお支払い' . $lb;
							$msg_payment .= '決済番号：' . $receipt_number . $lb;
						}
					}
					if ( ! empty( $acting_data['receipt_print_url'] ) ) {
						$receipt_print_url = ( usces_is_html_mail() ) ? '<a href="' . esc_url( $acting_data['receipt_print_url'] ) . '">' . esc_url( $acting_data['receipt_print_url'] ) . '<a>' : $acting_data['receipt_print_url'];
						if ( false !== strpos( $usable_cvs_company_id, CODE_SEICOMART ) || false !== strpos( $usable_cvs_company_id, CODE_SEVENELEVEN ) ) {
							$msg_payment .= '払込票URL：' . $receipt_print_url . $lb;
						} else {
							$msg_payment .= '支払参照URL：' . $receipt_print_url . $lb;
						}
					}
					if ( ! empty( $acting_data['payment_limit_date'] ) ) {
						$msg_payment .= 'お支払い期限日：' . date( __( 'Y/m/d' ), strtotime( $acting_data['payment_limit_date'] ) ) . $lb;
					}
					if ( usces_is_html_mail() ) {
						$msg_payment .= '</td></tr>';
					}
				}
				break;
			case 'acting_paygent_atm':
				$mode = ( isset( $_POST['mode'] ) ) ? wp_unslash( $_POST['mode'] ) : '';
				if ( 'orderConfirmMail' == $mode || 'changeConfirmMail' == $mode ) {
					$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_paygent_atm', $order_id ) );
					$lb          = ( usces_is_html_mail() ) ? '<br>' : "\r\n";
					if ( isset( $acting_data['pay_center_number'] ) ) {
						$msg_payment .= '収納機関番号：' . $acting_data['pay_center_number'] . $lb;
					}
					if ( isset( $acting_data['customer_number'] ) ) {
						$msg_payment .= 'お客様番号：' . $acting_data['customer_number'] . $lb;
					}
					if ( isset( $acting_data['conf_number'] ) ) {
						$msg_payment .= '確認番号：' . $acting_data['conf_number'] . $lb;
					}
					if ( isset( $acting_data['payment_limit_date'] ) ) {
						$msg_payment .= 'お支払い期限日：' . date( __( 'Y/m/d' ), strtotime( $acting_data['payment_limit_date'] ) ) . $lb;
					}
				}
				break;
			case 'acting_paygent_bank':
				break;
			case 'acting_paygent_paidy':
				break;
		}
		return $msg_payment;
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
		if ( isset( $results['trading_id'] ) ) {
			$linkkey = $results['trading_id'];
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

		switch ( $acting_flg ) {
			case 'acting_paygent_card':
				$data = array(
					'trading_id'   => $log_key,
					'payment_type' => PAYMENT_TYPE_CREDIT,
				);
				$usces->set_order_meta_value( $acting_flg, usces_serialize( $data ), $order_id );
				$usces->set_order_meta_value( 'trading_id', $log_key, $order_id );
				break;
			case 'acting_paygent_conv':
				$data = array(
					'trading_id'   => $log_key,
					'payment_type' => PAYMENT_TYPE_CONVENI_NUM,
				);
				$usces->set_order_meta_value( $acting_flg, usces_serialize( $data ), $order_id );
				$usces->set_order_meta_value( 'trading_id', $log_key, $order_id );
				break;
			case 'acting_paygent_atm':
				$data = array(
					'trading_id'   => $log_key,
					'payment_type' => PAYMENT_TYPE_ATM,
				);
				$usces->set_order_meta_value( $acting_flg, usces_serialize( $data ), $order_id );
				$usces->set_order_meta_value( 'trading_id', $log_key, $order_id );
				break;
			case 'acting_paygent_bank':
				$data = array(
					'trading_id'   => $log_key,
					'payment_type' => PAYMENT_TYPE_BANK,
				);
				$usces->set_order_meta_value( $acting_flg, usces_serialize( $data ), $order_id );
				$usces->set_order_meta_value( 'trading_id', $log_key, $order_id );
				break;
			case 'acting_paygent_paidy':
				$data = array(
					'trading_id'   => $log_key,
					'payment_type' => PAYMENT_TYPE_PAIDY,
				);
				$usces->set_order_meta_value( $acting_flg, usces_serialize( $data ), $order_id );
				$usces->set_order_meta_value( 'trading_id', $log_key, $order_id );
				break;
		}
	}

	/**
	 * 支払方法説明
	 * usces_filter_payment_detail
	 *
	 * @param  string $str Payment method description.
	 * @param  array  $entry Entry data.
	 * @return string
	 */
	public function payment_detail( $str, $entry ) {
		$payment = usces_get_payments_by_name( $entry['order']['payment_name'] );
		if ( isset( $payment['settlement'] ) && 'acting_paygent_card' == $payment['settlement'] ) {
			$acting_opts = $this->get_acting_settings();
			if ( 0 !== (int) $acting_opts['payment_class'] ) {
				$split_count = ( isset( $_POST['split_count'] ) ) ? wp_unslash( $_POST['split_count'] ) : '01';
				switch ( $split_count ) {
					case '01':
						$str = __( '(', 'usces' ) . __( 'One time payment', 'usces' ) . __( ')', 'usces' );
						break;
					case '02':
					case '03':
					case '05':
					case '06':
					case '10':
					case '12':
					case '15':
					case '18':
					case '20':
					case '24':
						$times = (int) $split_count;
						$str   = __( '(', 'usces' ) . $times . __( '-time payment', 'usces' ) . __( ')', 'usces' );
						break;
					case '23':
						$str = __( '(', 'usces' ) . __( 'Bonus lump-sum payment', 'usces' ) . __( ')', 'usces' );
						break;
					case '80':
						$str = __( '(', 'usces' ) . __( 'Libor Funding pay', 'usces' ) . __( ')', 'usces' );
						break;
					default:
						$str = __( '(', 'usces' ) . __( 'One time payment', 'usces' ) . __( ')', 'usces' );
				}
			}
		} elseif ( isset( $payment['settlement'] ) && 'acting_paygent_conv' == $payment['settlement'] ) {
			$acting_opts    = $this->get_acting_settings();
			$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $acting_opts['payment_term_day'] ) . __( ')', 'usces' );
			$str            = apply_filters( 'usces_filter_paygent_payment_limit_conv', $payment_detail, $acting_opts['payment_term_day'] );
		} elseif ( isset( $payment['settlement'] ) && 'acting_paygent_atm' == $payment['settlement'] ) {
			$acting_opts        = $this->get_acting_settings();
			$payment_limit_date = ( empty( $acting_opts['payment_limit_date'] ) ) ? 30 : $acting_opts['payment_limit_date'];
			$payment_detail     = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $payment_limit_date ) . __( ')', 'usces' );
			$str                = apply_filters( 'usces_filter_paygent_payment_limit_atm', $payment_detail, $acting_opts['payment_limit_date'] );
		} elseif ( isset( $payment['settlement'] ) && 'acting_paygent_bank' == $payment['settlement'] ) {
			$acting_opts      = $this->get_acting_settings();
			$asp_payment_term = $this->asp_payment_term[ $acting_opts['asp_payment_term'] ];
			$payment_detail   = __( '(', 'usces' ) . sprintf( '%sにお支払いを完了してください。', $asp_payment_term ) . __( ')', 'usces' );
			$str              = apply_filters( 'usces_filter_paygent_payment_limit_bank', $payment_detail, $acting_opts['asp_payment_term'] );
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
		switch ( $payment['settlement'] ) {
			case 'acting_paygent_card':
				if ( $this->is_activate_card( 'module' ) ) {
					$payments_str .= "'" . $payment['name'] . "': 'paygent_card_form', ";
				}
				break;
			case 'acting_paygent_conv':
				if ( $this->is_activate_conv( 'module' ) ) {
					$payments_str .= "'" . $payment['name'] . "': 'paygent_conv_form', ";
				}
				break;
			case 'acting_paygent_atm':
				if ( $this->is_activate_atm() ) {
					$payments_str .= "'" . $payment['name'] . "': 'paygent_atm_form', ";
				}
				break;
			case 'acting_paygent_bank':
				if ( $this->is_activate_bank() ) {
					$payments_str .= "'" . $payment['name'] . "': 'paygent_bank_form', ";
				}
				break;
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
		switch ( $payment['settlement'] ) {
			case 'acting_paygent_card':
				if ( $this->is_activate_card( 'module' ) ) {
					$payments_arr[] = 'paygent_card_form';
				}
				break;
			case 'acting_paygent_conv':
				if ( $this->is_activate_conv( 'module' ) ) {
					$payments_arr[] = 'paygent_conv_form';
				}
				break;
			case 'acting_paygent_atm':
				if ( $this->is_activate_atm() ) {
					$payments_arr[] = 'paygent_atm_form';
				}
				break;
			case 'acting_paygent_bank':
				if ( $this->is_activate_bank() ) {
					$payments_arr[] = 'paygent_bank_form';
				}
				break;
		}
		return $payments_arr;
	}

	/**
	 * 内容確認ページ [注文する] ボタン
	 * usces_filter_confirm_inform
	 *
	 * @param  string $form Purchase post form.
	 * @param  array  $payments Payment data.
	 * @param  string $acting_flg Payment type.
	 * @param  string $rand Welcart transaction key.
	 * @param  string $purchase_disabled Disable purchase button.
	 * @return string
	 */
	public function confirm_inform( $form, $payments, $acting_flg, $rand, $purchase_disabled ) {
		global $usces;

		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return $form;
		}

		$entry = $usces->cart->get_entry();
		$cart  = $usces->cart->get_cart();
		if ( ! $entry || ! $cart ) {
			return $form;
		}
		if ( ! $entry['order']['total_full_price'] ) {
			return $form;
		}

		$acting_opts = $this->get_acting_settings();

		switch ( $acting_flg ) {
			/* カード決済 */
			case 'acting_paygent_card':
				usces_save_order_acting_data( $rand );
				if ( 'module' == $acting_opts['card_activate'] ) {
					$token              = ( isset( $_POST['token'] ) ) ? wp_unslash( $_POST['token'] ) : '';
					$masked_card_number = ( isset( $_POST['masked_card_number'] ) ) ? wp_unslash( $_POST['masked_card_number'] ) : '';
					$valid_until        = ( isset( $_POST['valid_until'] ) ) ? wp_unslash( $_POST['valid_until'] ) : '';
					$fingerprint        = ( isset( $_POST['fingerprint'] ) ) ? wp_unslash( $_POST['fingerprint'] ) : '';
					$hc                 = ( isset( $_POST['hc'] ) ) ? wp_unslash( $_POST['hc'] ) : '';
					$stock_card         = ( isset( $_POST['stock_card'] ) ) ? wp_unslash( $_POST['stock_card'] ) : '';
					$stock_card_mode    = ( isset( $_POST['stock_card_mode'] ) ) ? wp_unslash( $_POST['stock_card_mode'] ) : '';
					$split_count        = ( isset( $_POST['split_count'] ) ) ? wp_unslash( $_POST['split_count'] ) : '';

					$form = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
						<input type="hidden" name="token" value="' . $token . '">
						<input type="hidden" name="masked_card_number" value="' . $masked_card_number . '">
						<input type="hidden" name="valid_until" value="' . $valid_until . '">
						<input type="hidden" name="fingerprint" value="' . $fingerprint . '">
						<input type="hidden" name="hc" value="' . $hc . '">
						<input type="hidden" name="trading_id" value="' . $rand . '">';
					if ( ! empty( $stock_card ) ) {
						$form .= '<input type="hidden" name="stock_card" value="' . $stock_card . '">';
					}
					if ( ! empty( $stock_card_mode ) ) {
						$form .= '<input type="hidden" name="stock_card_mode" value="' . $stock_card_mode . '">';
					}
					if ( ! empty( $split_count ) ) {
						$form .= '<input type="hidden" name="split_count" value="' . $split_count . '">';
					}
					$form .= '<div class="send">
						' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
						<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
						<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
						<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
				} else {
					$form  = '<form id="purchase_form" name="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
						<input type="hidden" name="trading_id" value="' . $rand . '">
						<input type="hidden" name="payment_type" value="' . PAYMENT_TYPE_CREDIT . '">
						<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">
						<div class="send">
							<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', '' ) . $purchase_disabled . ' />
						</div>
					</form>';
					$form .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13){return false;}">
						<div class="send">
							' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
							<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
						</div>';
				}
				break;

			/* コンビニ決済 */
			case 'acting_paygent_conv':
				usces_save_order_acting_data( $rand );
				if ( 'module' == $acting_opts['conv_activate'] ) {
					$cvs_company_id       = ( isset( $_POST['cvs_company_id'] ) ) ? wp_unslash( $_POST['cvs_company_id'] ) : '';
					$customer_family_name = ( isset( $_POST['customer_family_name'] ) ) ? wp_unslash( $_POST['customer_family_name'] ) : '';
					$customer_name        = ( isset( $_POST['customer_name'] ) ) ? wp_unslash( $_POST['customer_name'] ) : '';
					$customer_tel         = ( isset( $_POST['customer_tel'] ) ) ? wp_unslash( $_POST['customer_tel'] ) : '';

					$form  = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
						<input type="hidden" name="cvs_company_id" value="' . $cvs_company_id . '">
						<input type="hidden" name="customer_family_name" value="' . $customer_family_name . '">
						<input type="hidden" name="customer_name" value="' . $customer_name . '">
						<input type="hidden" name="customer_tel" value="' . $customer_tel . '">
						<input type="hidden" name="trading_id" value="' . $rand . '">';
					$form .= '<div class="send">
						' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
						<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
						<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
						<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
				} else {
					$form  = '<form id="purchase_form" name="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
						<input type="hidden" name="trading_id" value="' . $rand . '">
						<input type="hidden" name="payment_type" value="' . PAYMENT_TYPE_CONVENI_NUM . '">
						<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">
						<div class="send">
							<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', '' ) . $purchase_disabled . ' />
						</div>
					</form>';
					$form .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13){return false;}">
						<div class="send">
							' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
							<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
						</div>';
				}
				break;

			/* ATM決済 */
			case 'acting_paygent_atm':
				usces_save_order_acting_data( $rand );
				$customer_family_name      = ( isset( $_POST['customer_family_name'] ) ) ? wp_unslash( $_POST['customer_family_name'] ) : '';
				$customer_name             = ( isset( $_POST['customer_name'] ) ) ? wp_unslash( $_POST['customer_name'] ) : '';
				$customer_family_name_kana = ( isset( $_POST['customer_family_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_family_name_kana'] ) ) : '';
				$customer_name_kana        = ( isset( $_POST['customer_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_name_kana'] ) ) : '';

				$form  = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
					<input type="hidden" name="customer_family_name" value="' . $customer_family_name . '">
					<input type="hidden" name="customer_name" value="' . $customer_name . '">
					<input type="hidden" name="customer_family_name_kana" value="' . $customer_family_name_kana . '">
					<input type="hidden" name="customer_name_kana" value="' . $customer_name_kana . '">
					<input type="hidden" name="trading_id" value="' . $rand . '">';
				$form .= '<div class="send">
					' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
					<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
					<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
					<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
				break;

			/* 銀行ネット決済 */
			case 'acting_paygent_bank':
				usces_save_order_acting_data( $rand );
				$customer_family_name      = ( isset( $_POST['customer_family_name'] ) ) ? wp_unslash( $_POST['customer_family_name'] ) : '';
				$customer_name             = ( isset( $_POST['customer_name'] ) ) ? wp_unslash( $_POST['customer_name'] ) : '';
				$customer_family_name_kana = ( isset( $_POST['customer_family_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_family_name_kana'] ) ) : '';
				$customer_name_kana        = ( isset( $_POST['customer_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_name_kana'] ) ) : '';

				$form  = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
					<input type="hidden" name="customer_family_name" value="' . $customer_family_name . '">
					<input type="hidden" name="customer_name" value="' . $customer_name . '">
					<input type="hidden" name="customer_family_name_kana" value="' . $customer_family_name_kana . '">
					<input type="hidden" name="customer_name_kana" value="' . $customer_name_kana . '">
					<input type="hidden" name="trading_id" value="' . $rand . '">';
				$form .= '<div class="send">
					' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
					<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
					<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
					<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
				break;

			/* Paidy */
			case 'acting_paygent_paidy':
				usces_save_order_acting_data( $rand );
				$form = '<form name="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
						<input type="hidden" name="purchase" value="' . $acting_flg . '">
						<input type="hidden" name="trading_id" value="' . $rand . '">
						<input type="hidden" name="paidy_id">
						<input type="hidden" name="paidy_created_at">
						<input type="hidden" name="paidy_status">
						<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">
						<div class="send paidy-send"><input type="button" id="paidy-checkout-button" onclick="paidyPay()" value="Paidyでお支払い" /></div>
					</form>
					<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
						<div class="send">
							' . apply_filters( 'usces_filter_confirm_before_backbutton', '', $payments, $acting_flg, $rand ) . '
							<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', '' ) . ' />
						</div>';
				break;
		}
		return $form;
	}

	/**
	 * 内容確認ページ ポイントフォーム
	 * usces_action_confirm_page_point_inform
	 */
	public function e_point_inform() {
		$form = $this->point_inform( '' );
		echo( $form );
	}

	/**
	 * 内容確認ページ ポイントフォーム
	 * usces_filter_confirm_point_inform
	 *
	 * @param  string $form Input point form.
	 * @return string
	 */
	public function point_inform( $form ) {
		global $usces;

		$entry      = $usces->cart->get_entry();
		$payment    = usces_get_payments_by_name( $entry['order']['payment_name'] );
		$acting_flg = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';
		switch ( $acting_flg ) {
			case 'acting_paygent_card':
				if ( 'module' == $this->get_service_type( 'card' ) ) {
					$token              = ( isset( $_POST['token'] ) ) ? wp_unslash( $_POST['token'] ) : '';
					$masked_card_number = ( isset( $_POST['masked_card_number'] ) ) ? wp_unslash( $_POST['masked_card_number'] ) : '';
					$valid_until        = ( isset( $_POST['valid_until'] ) ) ? wp_unslash( $_POST['valid_until'] ) : '';
					$fingerprint        = ( isset( $_POST['fingerprint'] ) ) ? wp_unslash( $_POST['fingerprint'] ) : '';
					$hc                 = ( isset( $_POST['hc'] ) ) ? wp_unslash( $_POST['hc'] ) : '';
					$stock_card         = ( isset( $_POST['stock_card'] ) ) ? wp_unslash( $_POST['stock_card'] ) : '';
					$stock_card_mode    = ( isset( $_POST['stock_card_mode'] ) ) ? wp_unslash( $_POST['stock_card_mode'] ) : '';
					$split_count        = ( isset( $_POST['split_count'] ) ) ? wp_unslash( $_POST['split_count'] ) : '';

					$form .= '
					<input type="hidden" name="token" value="' . $token . '">
					<input type="hidden" name="masked_card_number" value="' . $masked_card_number . '">
					<input type="hidden" name="valid_until" value="' . $valid_until . '">
					<input type="hidden" name="fingerprint" value="' . $fingerprint . '">
					<input type="hidden" name="hc" value="' . $hc . '">';
					if ( ! empty( $stock_card ) ) {
						$form .= '<input type="hidden" name="stock_card" value="' . $stock_card . '">';
					}
					if ( ! empty( $stock_card_mode ) ) {
						$form .= '<input type="hidden" name="stock_card_mode" value="' . $stock_card_mode . '">';
					}
					if ( ! empty( $split_count ) ) {
						$form .= '<input type="hidden" name="split_count" value="' . $split_count . '">';
					}
				}
				break;

			case 'acting_paygent_conv':
				if ( 'module' == $this->get_service_type( 'conv' ) ) {
					$cvs_company_id       = ( isset( $_POST['cvs_company_id'] ) ) ? wp_unslash( $_POST['cvs_company_id'] ) : '';
					$customer_family_name = ( isset( $_POST['customer_family_name'] ) ) ? wp_unslash( $_POST['customer_family_name'] ) : '';
					$customer_name        = ( isset( $_POST['customer_name'] ) ) ? wp_unslash( $_POST['customer_name'] ) : '';
					$customer_tel         = ( isset( $_POST['customer_tel'] ) ) ? wp_unslash( $_POST['customer_tel'] ) : '';

					$form .= '
					<input type="hidden" name="cvs_company_id" value="' . $cvs_company_id . '">
					<input type="hidden" name="customer_family_name" value="' . $customer_family_name . '">
					<input type="hidden" name="customer_name" value="' . $customer_name . '">
					<input type="hidden" name="customer_tel" value="' . $customer_tel . '">';
				}
				break;

			case 'acting_paygent_atm':
				if ( 'module' == $this->get_service_type( 'atm' ) ) {
					$customer_family_name      = ( isset( $_POST['customer_family_name'] ) ) ? wp_unslash( $_POST['customer_family_name'] ) : '';
					$customer_name             = ( isset( $_POST['customer_name'] ) ) ? wp_unslash( $_POST['customer_name'] ) : '';
					$customer_family_name_kana = ( isset( $_POST['customer_family_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_family_name_kana'] ) ) : '';
					$customer_name_kana        = ( isset( $_POST['customer_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_name_kana'] ) ) : '';

					$form .= '
					<input type="hidden" name="customer_family_name" value="' . $customer_family_name . '">
					<input type="hidden" name="customer_name" value="' . $customer_name . '">
					<input type="hidden" name="customer_family_name_kana" value="' . $customer_family_name_kana . '">
					<input type="hidden" name="customer_name_kana" value="' . $customer_name_kana . '">';
				}
				break;

			case 'acting_paygent_bank':
				if ( 'module' == $this->get_service_type( 'bank' ) ) {
					$customer_family_name      = ( isset( $_POST['customer_family_name'] ) ) ? wp_unslash( $_POST['customer_family_name'] ) : '';
					$customer_name             = ( isset( $_POST['customer_name'] ) ) ? wp_unslash( $_POST['customer_name'] ) : '';
					$customer_family_name_kana = ( isset( $_POST['customer_family_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_family_name_kana'] ) ) : '';
					$customer_name_kana        = ( isset( $_POST['customer_name_kana'] ) ) ? String_Utitily::convert_katakana_zen2han( wp_unslash( $_POST['customer_name_kana'] ) ) : '';

					$form .= '
					<input type="hidden" name="customer_family_name" value="' . $customer_family_name . '">
					<input type="hidden" name="customer_name" value="' . $customer_name . '">
					<input type="hidden" name="customer_family_name_kana" value="' . $customer_family_name_kana . '">
					<input type="hidden" name="customer_name_kana" value="' . $customer_name_kana . '">';
				}
				break;
		}
		return $form;
	}

	/**
	 * セッション復帰処理
	 * usces_pre_purchase
	 */
	public function pre_purchase() {
		$acting_opts = $this->get_acting_settings();
		if ( 'on' == $acting_opts['threedsecure_ryaku'] && isset( $_POST['from3ds'] ) && isset( $_POST['trading_id'] ) ) {
			usces_restore_order_acting_data( wp_unslash( $_POST['trading_id'] ) );
		}
	}

	/**
	 * 決済処理
	 * usces_action_acting_processing
	 *
	 * @param  string $acting_flg Payment type.
	 * @param  array  $post_query Post data.
	 */
	public function acting_processing( $acting_flg, $post_query ) {
		global $usces;

		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}

		$entry = $usces->cart->get_entry();
		$cart  = $usces->cart->get_cart();
		if ( ! $entry || ! $cart ) {
			wp_redirect( USCES_CART_URL );
			exit();
		}
		if ( isset( $_REQUEST['_nonce'] ) && ! wp_verify_nonce( $_REQUEST['_nonce'], $acting_flg ) ) {
			wp_redirect( USCES_CART_URL );
			exit();
		}

		$acting_opts = $this->get_acting_settings();
		parse_str( $post_query, $post_data );

		$acting       = substr( $acting_flg, 7 );
		$member       = $usces->get_member();
		$trading_id   = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
		$service_type = '';
		$payment_type = '';

		switch ( $acting_flg ) {
			case 'acting_paygent_card':
				$service_type = $this->get_service_type( 'card' );
				$payment_type = PAYMENT_TYPE_CREDIT;
				break;
			case 'acting_paygent_conv':
				$service_type = $this->get_service_type( 'conv' );
				$payment_type = PAYMENT_TYPE_CONVENI_NUM;
				break;
			case 'acting_paygent_atm':
				$service_type = $this->get_service_type( 'atm' );
				$payment_type = PAYMENT_TYPE_ATM;
				break;
			case 'acting_paygent_bank':
				$service_type = $this->get_service_type( 'bank' );
				$payment_type = PAYMENT_TYPE_BANK;
				break;
			case 'acting_paygent_paidy':
				$service_type = $this->get_service_type( 'paidy' );
				$payment_type = PAYMENT_TYPE_PAIDY;
				break;
		}

		if ( 'link' == $service_type ) {
			$hash_key             = $acting_opts['hc'];
			$fix_params           = '';
			$id                   = $entry['order']['total_full_price'];
			$inform_url           = USCES_CART_URL;
			$seq_merchant_id      = $acting_opts['seq_merchant_id'];
			$payment_term_day     = $acting_opts['payment_term_day'];
			$payment_term_min     = $acting_opts['payment_term_min'];
			$payment_class        = $acting_opts['payment_class'];
			$use_card_conf_number = ( 'on' == $acting_opts['use_card_conf_number'] ) ? '1' : '0';
			if ( 'on' == $acting_opts['stock_card_mode'] && ! empty( $member['ID'] ) ) {
				$stock_card_mode = '1';
				$customer_id     = $member['ID'];
			} else {
				$stock_card_mode = '';
				$customer_id     = '';
			}
			$threedsecure_ryaku = ( 'on' == $acting_opts['threedsecure_ryaku'] ) ? '0' : '1';

			$merchant_name             = mb_convert_kana( $usces->options['company_name'], 'RNASKV', 'UTF-8' );
			$banner_url                = '';
			$return_url                = USCES_CART_URL . $usces->delim . 'acting=' . $acting . '&acting_return=1&trading_id=' . $trading_id;
			$stop_return_url           = USCES_CART_URL . $usces->delim . 'acting=' . $acting . '&confirm=1';
			$customer_family_name      = trim( $entry['customer']['name1'] );
			$customer_name             = trim( $entry['customer']['name2'] );
			$customer_family_name_kana = trim( mb_convert_kana( $entry['customer']['name3'], 'rnaskh', 'UTF-8' ) );
			$customer_name_kana        = trim( mb_convert_kana( $entry['customer']['name4'], 'rnaskh', 'UTF-8' ) );
			$customer_tel              = str_replace( '-', '', mb_convert_kana( $entry['customer']['tel'], 'a', 'UTF-8' ) );

			/* Create hash hex string */
			$org_str  =
				$trading_id .
				$payment_type .
				$fix_params .
				$id .
				$inform_url .
				$seq_merchant_id .
				$payment_term_day .
				$payment_term_min .
				$payment_class .
				$use_card_conf_number .
				$customer_id .
				$threedsecure_ryaku .
				$hash_key;
			$hash_str = hash( 'sha256', $org_str );
			$appendix = ( 'ja' == usces_get_local_language() ) ? '0' : '1';

			$url       = $acting_opts['send_url'];
			$send_data = array(
				'trading_id'                => $trading_id,
				'payment_type'              => $payment_type,
				'fix_params'                => $fix_params,
				'id'                        => $id,
				'inform_url'                => $inform_url,
				'seq_merchant_id'           => $seq_merchant_id,
				'merchant_name'             => $merchant_name,
				'payment_term_day'          => $payment_term_day,
				'payment_term_min'          => $payment_term_min,
				'banner_url'                => $banner_url,
				'return_url'                => $return_url,
				'stop_return_url'           => $stop_return_url,
				'customer_family_name'      => $customer_family_name,
				'customer_name'             => $customer_name,
				'customer_family_name_kana' => $customer_family_name_kana,
				'customer_name_kana'        => $customer_name_kana,
				'customer_tel'              => $customer_tel,
				'payment_class'             => $payment_class,
				'use_card_conf_number'      => $use_card_conf_number,
				'stock_card_mode'           => $stock_card_mode,
				'customer_id'               => $customer_id,
				'threedsecure_ryaku'        => $threedsecure_ryaku,
				'appendix'                  => $appendix,
				'hc'                        => $hash_str,

				/* btob mode ON */
				'isbtob'                    => '1',

				/* お支払い完了画面非表示区分 */
				'finish_disable'            => '1',
			);

			$interface = parse_url( $url );
			$vars      = http_build_query( $send_data );

			$header  = 'POST ' . $interface['path'] . " HTTP/1.1\r\n";
			$header .= 'Host: ' . $interface['host'] . "\r\n";
			$header .= "User-Agent: PHP Script\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= 'Content-Length: ' . strlen( $vars ) . "\r\n";
			$header .= "Connection: close\r\n\r\n";
			$header .= $vars;
			$fp      = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
			if ( ! $fp ) {
				usces_log( $acting . ' : TLS(v1.2) Error', 'acting_transaction.log' );
				$log = array(
					'acting' => $acting,
					'key'    => $trading_id,
					'result' => 'SSL/TLS ERROR (' . $errno . ')',
					'data'   => array( $errstr ),
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => $acting,
							'acting_return' => 0,
						),
						USCES_CART_URL
					)
				);
				exit();
			}
			if ( $fp ) {
				$data     = array();
				$response = array();
				fwrite( $fp, $header );
				while ( ! feof( $fp ) ) {
					$line = fgets( $fp, 1024 );
					if ( false !== strpos( $line, 'result' ) ) {
						$item           = explode( '=', $line, 2 );
						$data['result'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'response_code' ) ) {
						$item                  = explode( '=', $line, 2 );
						$data['response_code'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'response_detail' ) ) {
						$item                    = explode( '=', $line, 2 );
						$data['response_detail'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'url' ) ) {
						$item        = explode( '=', $line, 2 );
						$data['url'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'trading_id' ) ) {
						$item               = explode( '=', $line, 2 );
						$data['trading_id'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'payment_type' ) ) {
						$item                 = explode( '=', $line, 2 );
						$data['payment_type'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'limit_date' ) ) {
						$item               = explode( '=', $line, 2 );
						$data['limit_date'] = trim( $item[1] );
					}
					if ( false !== strpos( $line, 'trade_generation_date' ) ) {
						$item                          = explode( '=', $line, 2 );
						$data['trade_generation_date'] = trim( $item[1] );
					}
					$response[] = $line;
				}
				fclose( $fp );

				if ( 0 == $data['result'] && ! empty( $data['url'] ) ) {
					wp_redirect( $data['url'] );
					exit();
				} else {
					$log = array(
						'acting' => $acting,
						'key'    => $trading_id,
						'result' => 'RESPONSE ERROR',
						'data'   => $response,
					);
					usces_save_order_acting_error( $log );
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => $acting,
								'acting_return' => 0,
							),
							USCES_CART_URL
						)
					);
				}
				exit();
			}
		} elseif ( 'module' == $service_type ) {
			$pm = Paygent_Module::get_instance();
			switch ( $payment_type ) {
				/* カード決済 */
				case PAYMENT_TYPE_CREDIT:
					$telegram_kind    = PAYGENT_CREDIT;
					$token            = $post_data['token'];
					$stock_card_mode  = 0;
					$customer_card_id = '';

					if ( 'on' == $acting_opts['threedsecure_ryaku'] ) {
						/* 3Dセキュア認証の結果を受ける */
						if ( isset( $post_data['from3ds'] ) ) {
							if ( 'on' == $acting_opts['attempt'] && ! empty( $post_data['attempt_kbn'] ) ) {
								$log = array(
									'acting' => 'paygent_card(3ds_attempt)',
									'key'    => $trading_id,
									'result' => '3DS ATTEMPT',
									'data'   => $post_data,
								);
								usces_save_order_acting_error( $log );
								wp_redirect(
									add_query_arg(
										array(
											'acting'        => 'paygent_card',
											'acting_return' => 0,
											'retry'         => 1,
										),
										USCES_CART_URL
									)
								);
								exit();
							}
						} else {
							/* 3Dセキュア認証画面へリダイレクト */
							$this->certification_3ds( $post_data, $member['ID'] );
						}
					}

					if ( 'on' == $acting_opts['stock_card_mode'] && ! empty( $member['ID'] ) ) {
						if ( isset( $post_data['stock_card'] ) ) {
							if ( 'stock' == $post_data['stock_card'] ) {
								$stock_card_mode  = 1;
								$customer_card_id = $usces->get_member_meta_value( 'paygent_customer_card_id', $member['ID'] );
							} else {
								if ( isset( $post_data['stock_card_mode'] ) && 'change' == $post_data['stock_card_mode'] ) {
									$stock_card_mode = 1;

									/* カード変更 */
									$customer = $this->customer_card_upd( $member['ID'], $token );
									if ( isset( $customer['result_status'] ) && RESULT_STATUS_ERROR == $customer['result_status'] ) {
										$log = array(
											'acting' => $acting,
											'key'    => $trading_id,
											'result' => $customer['response_code'],
											'data'   => $customer,
										);
										usces_save_order_acting_error( $log );
										wp_redirect(
											add_query_arg(
												array(
													'acting'        => $acting,
													'acting_return' => 0,
													'retry'         => 1,
												),
												USCES_CART_URL
											)
										);
										exit();
									}
									if ( isset( $customer['customer_card_id'] ) ) {
										$customer_card_id = $customer['customer_card_id'];
										$token            = '';
									}
								}
							}
						} else {
							if ( isset( $post_data['stock_card_mode'] ) && 'save' == $post_data['stock_card_mode'] ) {
								$stock_card_mode = 1;

								/* カード登録 */
								$customer = $this->customer_card_add( $member['ID'], $token );
								if ( isset( $customer['result_status'] ) && RESULT_STATUS_ERROR == $customer['result_status'] ) {
									$log = array(
										'acting' => $acting,
										'key'    => $trading_id,
										'result' => $customer['response_code'],
										'data'   => $customer,
									);
									usces_save_order_acting_error( $log );
									wp_redirect(
										add_query_arg(
											array(
												'acting'        => $acting,
												'acting_return' => 0,
												'retry'         => 1,
											),
											USCES_CART_URL
										)
									);
									exit();
								}
								if ( isset( $customer['customer_card_id'] ) ) {
									$customer_card_id = $customer['customer_card_id'];
									$token            = '';
								}
							}
						}
					}

					$pm->init();
					$pm->req_put( 'trading_id', $trading_id );
					$pm->req_put( 'payment_amount', $entry['order']['total_full_price'] );
					if ( 0 == (int) $acting_opts['payment_class'] || '01' == $post_data['split_count'] ) {
						$pm->req_put( 'payment_class', '10' );
					} elseif ( '23' == $post_data['split_count'] || '80' == $post_data['split_count'] ) {
						$pm->req_put( 'payment_class', $post_data['split_count'] );
					} else {
						$pm->req_put( 'payment_class', '61' );
						$pm->req_put( 'split_count', $post_data['split_count'] );
					}
					if ( 'on' == $acting_opts['threedsecure_ryaku'] && isset( $post_data['3ds_auth_id'] ) ) {
						$pm->req_put( '3dsecure_use_type', '2' );
						$pm->req_put( 'http_accept', $_SERVER['HTTP_ACCEPT'] );
						$pm->req_put( 'http_user_agent', $_SERVER['HTTP_USER_AGENT'] );
						$pm->req_put( 'term_url', USCES_CART_URL );
						$pm->req_put( '3ds_auth_id', $post_data['3ds_auth_id'] );
					} else {
						$pm->req_put( '3dsecure_ryaku', '1' );
					}
					if ( 1 == $stock_card_mode && ! empty( $customer_card_id ) ) {
						$pm->req_put( 'stock_card_mode', '1' );
						$pm->req_put( 'customer_id', $member['ID'] );
						$pm->req_put( 'customer_card_id', $customer_card_id );
						if ( ! empty( $token ) && 'on' == $acting_opts['use_card_conf_number'] ) {
							$pm->req_put( 'card_token', $token );
							$pm->req_put( 'security_code_token', '1' );
							$pm->req_put( 'security_code_use', '1' );
						}
					} else {
						$pm->req_put( 'card_token', $token );
						if ( 'on' == $acting_opts['use_card_conf_number'] ) {
							$pm->req_put( 'security_code_use', '1' );
						}
					}
					$pm->req_put( 'sales_mode', $acting_opts['sales_mode'] );
					break;

				/* コンビニ決済 */
				case PAYMENT_TYPE_CONVENI_NUM:
					$telegram_kind        = PAYGENT_CONVENI_NUM;
					$customer_family_name = trim( $post_data['customer_family_name'] );
					$customer_name        = trim( $post_data['customer_name'] );
					$customer_tel         = str_replace( '-', '', mb_convert_kana( $post_data['customer_tel'], 'a', 'UTF-8' ) );
					$pm->init();
					$pm->req_put( 'trading_id', $trading_id );
					$pm->req_put( 'payment_amount', $entry['order']['total_full_price'] );
					$pm->req_put( 'customer_family_name', $customer_family_name );
					$pm->req_put( 'customer_name', $customer_name );
					$pm->req_put( 'customer_tel', $customer_tel );
					$pm->req_put( 'payment_limit_date', $acting_opts['payment_term_day'] );
					$pm->req_put( 'cvs_company_id', $post_data['cvs_company_id'] );
					$pm->req_put( 'sales_type', $acting_opts['sales_type'] );
					break;

				/* ATM決済 */
				case PAYMENT_TYPE_ATM:
					$telegram_kind             = PAYGENT_ATM;
					$customer_family_name      = trim( $post_data['customer_family_name'] );
					$customer_name             = trim( $post_data['customer_name'] );
					$customer_family_name_kana = trim( $post_data['customer_family_name_kana'] );
					$customer_name_kana        = trim( $post_data['customer_name_kana'] );
					$payment_detail            = apply_filters( 'usces_filter_paygent_atm_payment_detail', 'お支払い一式' );
					$payment_detail_kana       = apply_filters( 'usces_filter_paygent_atm_payment_detail_kana', 'ｵｼﾊﾗｲｲﾂｼｷ' );
					$pm->init();
					$pm->req_put( 'trading_id', $trading_id );
					$pm->req_put( 'payment_amount', $entry['order']['total_full_price'] );
					$pm->req_put( 'customer_family_name', $customer_family_name );
					$pm->req_put( 'customer_name', $customer_name );
					$pm->req_put( 'customer_family_name_kana', $customer_family_name_kana );
					$pm->req_put( 'customer_name_kana', $customer_name_kana );
					$pm->req_put( 'payment_detail', $payment_detail );
					$pm->req_put( 'payment_detail_kana', $payment_detail_kana );
					if ( isset( $acting_data['payment_limit_date'] ) && '' != $acting_data['payment_limit_date'] ) {
						$pm->req_put( 'payment_limit_date', (int) $acting_data['payment_limit_date'] );
					}
					break;

				/* 銀行ネット決済 */
				case PAYMENT_TYPE_BANK:
					$telegram_kind             = PAYGENT_BANK_ASP;
					$customer_family_name      = trim( $post_data['customer_family_name'] );
					$customer_name             = trim( $post_data['customer_name'] );
					$customer_family_name_kana = trim( $post_data['customer_family_name_kana'] );
					$customer_name_kana        = trim( $post_data['customer_name_kana'] );
					$claim_kanji               = apply_filters( 'usces_filter_paygent_bank_claim_kanji', 'お支払い一式' );
					$claim_kana                = apply_filters( 'usces_filter_paygent_bank_claim_kana', 'ｵｼﾊﾗｲｲﾂｼｷ' );
					$pm->init();
					$pm->req_put( 'trading_id', $trading_id );
					$pm->req_put( 'amount', $entry['order']['total_full_price'] );
					$pm->req_put( 'customer_family_name', $customer_family_name );
					$pm->req_put( 'customer_name', $customer_name );
					$pm->req_put( 'customer_family_name_kana', $customer_family_name_kana );
					$pm->req_put( 'customer_name_kana', $customer_name_kana );
					$pm->req_put( 'claim_kanji', $claim_kanji );
					$pm->req_put( 'claim_kana', $claim_kana );
					$return_arg      = array(
						'acting'        => $acting,
						'acting_return' => 1,
					);
					$pm->req_put( 'return_url', add_query_arg( $return_arg, USCES_CART_URL ) );
					if ( isset( $acting_data['asp_payment_term'] ) && '' != $acting_data['asp_payment_term'] ) {
						$pm->req_put( 'asp_payment_term', $acting_data['asp_payment_term'] );
					}
					$stop_return_arg = array(
						'acting'  => $acting,
						'confirm' => 1,
					);
					$pm->req_put( 'stop_return_url', add_query_arg( $stop_return_arg, USCES_CART_URL ) );
					break;

				/* Paidy */
				case PAYMENT_TYPE_PAIDY:
					if ( isset( $post_data['paidy_id'] ) && isset( $post_data['paidy_created_at'] ) && isset( $post_data['paidy_status'] ) && 'authorized' == $post_data['paidy_status'] ) {
						$telegram_kind = PAYGENT_PAIDY_REF;
						$pm->init();
						$pm->req_put( 'paidy_payment_id', $post_data['paidy_id'] );
						// $api_retrieve_url = 'https://api.paidy.com/payments/' . $post_data['paidy_id'];
						// $params           = array(
						// 	'method'  => 'GET',
						// 	'headers' => array(
						// 		'Content-Type'  => 'application/json;charset=utf-8',
						// 		'Authorization' => 'Bearer ' . $acting_opts['paidy_secret_key'],
						// 		'Paidy-Version' => '2018-04-10',
						// 	),
						// );
						// $response         = wp_remote_get( $api_retrieve_url, $params );
						// $response_data    = json_decode( wp_remote_retrieve_body( $response ), true );
					} else {
						$log           = array(
							'acting' => $acting,
							'key'    => $trading_id,
							'result' => 'PAIDY AUTHORIZE ERROR',
							'data'   => $post_data,
						);
						usces_save_order_acting_error( $log );
						wp_redirect(
							add_query_arg(
								array(
									'acting'        => $acting,
									'acting_return' => 0,
									'result'        => 0,
								),
								USCES_CART_URL
							)
						);
						exit();
					}
					break;
			}

			if ( ! empty( $payment_type ) && ! empty( $telegram_kind ) ) {
				$result_post   = $pm->post( $telegram_kind );
				$result_status = $pm->get_result_status();
				if ( ! ( true === $result_post ) || RESULT_STATUS_NORMAL != $result_status ) {
					$response_data = array(
						'result'          => $result_post,
						'result_status'   => $result_status,
						'response_code'   => $pm->get_response_code(),
						'response_detail' => $pm->get_response_detail(),
						'result_message'  => $pm->get_result_message(),
					);
					$response_code = ( true !== $result_post ) ? $result_post : $pm->get_response_code();
					$log           = array(
						'acting' => $acting,
						'key'    => $trading_id,
						'result' => $response_code,
						'data'   => $response_data,
					);
					usces_save_order_acting_error( $log );
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => $acting,
								'acting_return' => 0,
								'retry'         => 1,
							),
							USCES_CART_URL
						)
					);
					exit();
				}
				$response_data = $pm->get_response_data();
				if ( ! isset( $response_data['payment_type'] ) ) {
					$response_data['payment_type'] = $payment_type;
				}
				if ( ! isset( $response_data['trading_id'] ) ) {
					$response_data['trading_id'] = $trading_id;
				}
				$res = $usces->order_processing( $response_data );
				if ( 'ordercompletion' != $res ) {
					$log = array(
						'acting' => $acting,
						'key'    => $trading_id,
						'result' => 'ORDER DATA REGISTERED ERROR',
						'data'   => $response_data,
					);
					usces_save_order_acting_error( $log );
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => $acting,
								'acting_return' => 0,
								'result'        => 0,
							),
							USCES_CART_URL
						)
					);
					exit();
				}
				if ( PAYMENT_TYPE_BANK == $payment_type && isset( $response_data['asp_url'] ) ) {
					$usces->cart->clear_cart(); /* Order OK. */
					wp_redirect( $response_data['asp_url'] );
				} else {
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => $acting,
								'acting_return' => 1,
								'result'        => 1,
							),
							USCES_CART_URL
						)
					);
				}
				exit();
			}
		}
	}

	/**
	 * 決済完了ページ制御
	 * usces_filter_check_acting_return_results
	 *
	 * @param  array $results Result data.
	 * @return array
	 */
	public function acting_return( $results ) {
		$acting = ( isset( $_GET['acting'] ) ) ? wp_unslash( $_GET['acting'] ) : '';
		switch ( $acting ) {
			case 'paygent_card':
			case 'paygent_conv':
			case 'paygent_atm':
			case 'paygent_bank':
			case 'paygent_paidy':
				$results              = wp_unslash( $_GET );
				$results[0]           = wp_unslash( $_GET['acting_return'] );
				$results['reg_order'] = false;
				break;
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
		$acting = ( isset( $_GET['acting'] ) ) ? wp_unslash( $_GET['acting'] ) : '';
		switch ( $acting ) {
			case 'paygent_card':
			case 'paygent_conv':
			case 'paygent_atm':
			case 'paygent_bank':
			case 'paygent_paidy':
				break;
		}
		return $trans_id;
	}

	/**
	 * 受注データ登録
	 * Called by usces_reg_orderdata() and usces_new_orderdata().
	 * usces_action_reg_orderdata
	 *
	 * @param  array $args Compact array( $cart, $entry, $order_id, $member_id, $payments, $charging_type, $results ).
	 */
	public function register_orderdata( $args ) {
		global $usces;
		extract( $args );

		$acting_flg = ( isset( $payments['settlement'] ) ) ? $payments['settlement'] : '';
		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}
		if ( ! $entry['order']['total_full_price'] ) {
			return;
		}

		if ( isset( $_REQUEST['payment_type'] ) ) {
			$payment_type = wp_unslash( $_REQUEST['payment_type'] );
		} elseif ( isset( $results['payment_type'] ) ) {
			$payment_type = $results['payment_type'];
		} else {
			$payment_type = '';
		}
		if ( ! empty( $payment_type ) ) {
			if ( isset( $results['acting_return'] ) ) {
				unset( $results['acting_return'] );
				unset( $results['page_id'] );
				unset( $results['0'] );
				unset( $results['reg_order'] );
			}
			$trading_id = ( isset( $results['trading_id'] ) ) ? $results['trading_id'] : '';
			$usces->set_order_meta_value( $acting_flg, usces_serialize( $results ), $order_id );
			$usces->set_order_meta_value( 'trading_id', $trading_id, $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $trading_id, $order_id );

			switch ( $payment_type ) {
				case PAYMENT_TYPE_ATM:
					$payment_id     = ( isset( $results['payment_id'] ) ) ? $results['payment_id'] : '';
					$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
					if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
						$this->save_acting_log( $settlement_ref, 'paygent_atm', 'reference_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
					} else {
						$this->save_acting_log( $settlement_ref, 'paygent_atm', $settlement_ref['payment_status'], RESULT_STATUS_NORMAL, $order_id, $trading_id );
					}
					break;
				case PAYMENT_TYPE_BANK:
					$this->save_acting_log( $results, 'paygent_bank', 'bank_applying', RESULT_STATUS_NORMAL, $order_id, $trading_id );
					break;
			}
		}
	}

	/**
	 * 決済エラーメッセージ
	 * usces_filter_get_error_settlement
	 *
	 * @param  string $form Payment error message.
	 * @return string
	 */
	public function error_page_message( $form ) {
		$acting = ( isset( $_GET['acting'] ) ) ? wp_unslash( $_GET['acting'] ) : '';
		if ( 'paygent_card' == $acting ) {
			if ( isset( $_GET['retry'] ) ) {
				$retry_url = add_query_arg(
					array(
						'backDelivery' => 'paygent_card',
						're-enter'     => 1,
					),
					USCES_CUSTOMER_URL
				);
				$form     .= '<div class="support_box">クレジットカード情報に誤りがあります。<br />カード番号を再入力する場合は、こちらをクリックしてください。<br /><br />';
				$form     .= '<p class="return_settlement"><a href="' . $retry_url . '">カード番号の再入力</a></p>';
				$form     .= '</div>';
			}
		}
		return $form;
	}

	/**
	 * サンキューメール
	 * usces_filter_send_order_mail_payment
	 *
	 * @param  string $msg_payment Payment method message.
	 * @param  int    $order_id Order number.
	 * @param  array  $payment Payment data.
	 * @param  array  $cart Cart data.
	 * @param  array  $entry Entry data.
	 * @param  array  $data Order data.
	 * @return string
	 */
	public function order_mail_payment( $msg_payment, $order_id, $payment, $cart, $entry, $data ) {
		global $usces;

		switch ( $payment['settlement'] ) {
			case 'acting_paygent_card':
				break;
			case 'acting_paygent_conv':
				$acting_data           = usces_unserialize( $usces->get_order_meta_value( 'acting_paygent_conv', $order_id ) );
				$usable_cvs_company_id = ( isset( $acting_data['usable_cvs_company_id'] ) ) ? $acting_data['usable_cvs_company_id'] : '';
				$receipt_number        = ( isset( $acting_data['receipt_number'] ) ) ? $acting_data['receipt_number'] : '';
				$lb                    = ( usces_is_html_mail() ) ? '<br>' : "\r\n";
				if ( ! empty( $receipt_number ) ) {
					if ( false !== strpos( $usable_cvs_company_id, CODE_SEICOMART ) ) {
						$msg_payment .= $this->cvs_company_a[ CODE_SEICOMART ] . 'でのお支払い' . $lb;
						$msg_payment .= '受付番号：' . $receipt_number . $lb;
					}
					if ( false !== strpos( $usable_cvs_company_id, CODE_SEVENELEVEN ) ) {
						$msg_payment .= $this->cvs_company_a[ CODE_SEVENELEVEN ] . 'でのお支払い' . $lb;
						$msg_payment .= '払込票番号：' . $receipt_number . $lb;
					}
					if ( false !== strpos( $usable_cvs_company_id, CODE_LOWSON ) ) {
						$msg_payment .= $this->cvs_company_a[ CODE_LOWSON ] . 'でのお支払い' . $lb;
						$msg_payment .= 'お客様番号：' . $receipt_number . $lb;
						$msg_payment .= '確認番号：' . CONVENI_CONFIRMATION_NUMBER . $lb;
					}
					if ( false !== strpos( $usable_cvs_company_id, CODE_MINISTOP ) ) {
						$msg_payment .= $this->cvs_company_a[ CODE_MINISTOP ] . 'でのお支払い' . $lb;
						$msg_payment .= 'お客様番号：' . $receipt_number . $lb;
						$msg_payment .= '確認番号：' . CONVENI_CONFIRMATION_NUMBER . $lb;
					}
					if ( false !== strpos( $usable_cvs_company_id, CODE_FAMILYMART ) ) {
						$msg_payment .= $this->cvs_company_a[ CODE_FAMILYMART ] . 'でのお支払い' . $lb;
						$msg_payment .= '収納番号：' . $receipt_number . $lb;
					}
					if ( false !== strpos( $usable_cvs_company_id, CODE_YAMAZAKI ) ) {
						$msg_payment .= $this->cvs_company_a[ CODE_YAMAZAKI ] . 'でのお支払い' . $lb;
						$msg_payment .= '決済番号：' . $receipt_number . $lb;
					}
				}
				if ( ! empty( $acting_data['receipt_print_url'] ) ) {
					$receipt_print_url = ( usces_is_html_mail() ) ? '<a href="' . esc_url( $acting_data['receipt_print_url'] ) . '">' . esc_url( $acting_data['receipt_print_url'] ) . '<a>' : $acting_data['receipt_print_url'];
					if ( false !== strpos( $usable_cvs_company_id, CODE_SEICOMART ) || false !== strpos( $usable_cvs_company_id, CODE_SEVENELEVEN ) ) {
						$msg_payment .= '払込票URL：' . $receipt_print_url . $lb;
					} else {
						$msg_payment .= '支払参照URL：' . $receipt_print_url . $lb;
					}
				}
				if ( ! empty( $acting_data['payment_limit_date'] ) ) {
					$msg_payment .= 'お支払い期限日：' . date( __( 'Y/m/d' ), strtotime( $acting_data['payment_limit_date'] ) ) . $lb;
				}
				break;
			case 'acting_paygent_atm':
				$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_paygent_atm', $order_id ) );
				$lb          = ( usces_is_html_mail() ) ? '<br>' : "\r\n";
				if ( isset( $acting_data['pay_center_number'] ) ) {
					$msg_payment .= '収納機関番号：' . $acting_data['pay_center_number'] . $lb;
				}
				if ( isset( $acting_data['customer_number'] ) ) {
					$msg_payment .= 'お客様番号：' . $acting_data['customer_number'] . $lb;
				}
				if ( isset( $acting_data['conf_number'] ) ) {
					$msg_payment .= '確認番号：' . $acting_data['conf_number'] . $lb;
				}
				if ( ! empty( $acting_data['payment_limit_date'] ) ) {
					$msg_payment .= 'お支払い期限日：' . date( __( 'Y/m/d' ), strtotime( $acting_data['payment_limit_date'] ) ) . $lb;
				}
				break;
			case 'acting_paygent_bank':
				break;
			case 'acting_paygent_paidy':
				break;
		}
		return $msg_payment;
	}

	/**
	 * Redirect from 3DS authentication to purchase.
	 * init
	 */
	public function done_3ds_auth() {
		if ( ! $this->is_activate_card( 'module' ) ) {
			return;
		}
		if ( ! isset( $_GET['result'] ) || ! isset( $_GET['3ds_auth_id'] ) || ! isset( $_GET['done_3ds_auth'] ) ) {
			return;
		}

		$acting_opts     = $this->get_acting_settings();
		$trading_id      = trim( $_GET['done_3ds_auth'] );
		$original_string = trim( $_GET['result'] ) . trim( $_GET['3ds_auth_id'] );
		if ( isset( $_GET['attempt_kbn'] ) ) {
			$original_string .= trim( $_GET['attempt_kbn'] );
		}
		$original_string .= trim( $acting_opts['threedsecure_hc'] );
		$hased_string     = hash( 'sha256', $original_string );

		/* Hash check */
		if ( $hased_string !== $_GET['hc'] ) {
			$log = array(
				'acting' => 'paygent_card(3ds_process)',
				'key'    => $trading_id,
				'result' => '3DS ERROR',
				'data'   => $_GET,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'paygent_card',
						'acting_return' => 0,
						'retry'         => 1,
					),
					USCES_CART_URL
				)
			);
			exit();
		}

		/* Get session data */
		$post_data = $this->get_post_data( $trading_id );
		if ( $post_data ) {
			$this->delete_post_data( $trading_id );
			?>
			<!DOCTYPE html>
			<html lang="ja">
			<head>
			<title></title>
			</head>
			<body onload="javascript:document.forms['redirectForm'].submit();">
			<form action="<?php echo esc_url( USCES_CART_URL ); ?>" method="post" id="redirectForm">
			<?php
			foreach ( (array) $post_data as $key => $value ) {
				echo '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
			}
			if ( isset( $_GET['result'] ) ) {
				echo '<input type="hidden" name="result" value="' . $_GET['result'] . '" />' . "\n";
			}
			if ( isset( $_GET['3ds_auth_id'] ) ) {
				echo '<input type="hidden" name="3ds_auth_id" value="' . $_GET['3ds_auth_id'] . '" />' . "\n";
			}
			if ( isset( $_GET['3dsecure_ds_transaction_id'] ) ) {
				echo '<input type="hidden" name="3dsecure_ds_transaction_id" value="' . $_GET['3dsecure_ds_transaction_id'] . '" />' . "\n";
			}
			if ( isset( $_GET['attempt_kbn'] ) ) {
				echo '<input type="hidden" name="attempt_kbn" value="' . $_GET['attempt_kbn'] . '" />' . "\n";
			}
			?>
			<input type="hidden" name="purchase" value="purchase" />
			<input type="hidden" name="from3ds" value="1" />
			<div class="wait_message" style="text-align: center; margin-top: 100px;"><?php esc_html_e( __( 'Hold on for a while, please.', 'usces' ) ); ?></div>
			</form>
			</body>
			</html>
			<?php
		} else {
			$log = array(
				'acting' => 'paygent_card(3ds_process)',
				'key'    => $trading_id,
				'result' => 'SESSION ERROR',
				'data'   => $_GET,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'paygent_card',
						'acting_return' => 0,
						'retry'         => 1,
					),
					USCES_CART_URL
				)
			);
		}
		exit();
	}

	/**
	 * Redirect to 3DS authentication.
	 *
	 * @param array $post_data Post data.
	 * @param int   $member_id Member ID.
	 */
	public function certification_3ds( $post_data, $member_id ) {
		global $usces;

		$entry = $usces->cart->get_entry();
		if ( ! $entry ) {
			wp_redirect( USCES_CART_URL );
			exit();
		}

		$trading_id = ( isset( $post_data['trading_id'] ) ) ? $post_data['trading_id'] : '';
		$this->save_post_data( $trading_id, $post_data );

		$acting_opts     = $this->get_acting_settings();
		$card_set_method = 'token';
		if ( 'on' == $acting_opts['stock_card_mode'] && ! empty( $member_id ) ) {
			if ( isset( $post_data['stock_card'] ) && 'stock' == $post_data['stock_card'] ) {
				$card_set_method = 'customer';
			}
		}

		$merchant_name = mb_convert_kana( $usces->options['company_name'], 'RNASKV', 'UTF-8' );
		$term_url      = add_query_arg( 'done_3ds_auth', $trading_id, USCES_CART_URL );

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'term_url', $term_url );
		$pm->req_put( 'authentication_type', '01' );
		$pm->req_put( 'merchant_name', $merchant_name );
		$pm->req_put( 'payment_amount', $entry['order']['total_full_price'] );
		$pm->req_put( 'card_set_method', $card_set_method );
		if ( 'customer' == $card_set_method ) {
			$customer_card_id = $usces->get_member_meta_value( 'paygent_customer_card_id', $member_id );
			$pm->req_put( 'customer_id', $member_id );
			$pm->req_put( 'customer_card_id', $customer_card_id );
		} else {
			$pm->req_put( 'card_token', $post_data['token'] );
		}
		$result_post   = $pm->post( PAYGENT_CARD_3DS2 );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result_post ) || RESULT_STATUS_NORMAL != $result_status ) {
			$response_data = array(
				'result'          => $result_post,
				'result_status'   => $result_status,
				'response_code'   => $pm->get_response_code(),
				'response_detail' => $pm->get_response_detail(),
				'result_message'  => $pm->get_result_message(),
			);
			$response_code = ( true !== $result_post ) ? $result_post : $pm->get_response_code();
			$log           = array(
				'acting' => $acting,
				'key'    => $trading_id,
				'result' => $response_code,
				'data'   => $response_data,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => $acting,
						'acting_return' => 0,
						'retry'         => 1,
					),
					USCES_CART_URL
				)
			);
			exit();
		}

		$response_data = $pm->get_response_data();
		if ( isset( $response_data['out_acs_html'] ) ) {
			echo( $response_data['out_acs_html'] );
		} else {
			$log           = array(
				'acting' => $acting,
				'key'    => $trading_id,
				'result' => '3DS OUT ACS ERROR',
				'data'   => $response_data,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => $acting,
						'acting_return' => 0,
						'retry'         => 1,
					),
					USCES_CART_URL
				)
			);
		}
		exit();
	}

	/**
	 * Scripts.
	 * wp_enqueue_scripts
	 */
	public function enqueue_scripts() {
		global $usces;

		/* 発送・支払方法ページ、クレジットカード情報更新ページ */
		if ( ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'delivery' == $usces->page ) ||
			( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) && ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' == wp_unslash( $_GET['usces_page'] ) || 'member_update_settlement' == wp_unslash( $_GET['usces_page'] ) ) ) ) ) :
			if ( $this->is_activate_card( 'module' ) ) :
				$acting_opts = $this->get_acting_settings();
				?>
<script type="text/javascript" src="<?php echo esc_url( $acting_opts['token_url'] ); ?>" charset="UTF-8"></script>
				<?php
			endif;
		endif;
	}

	/**
	 * Front scripts.
	 * wp_print_footer_scripts
	 */
	public function footer_scripts() {
		global $usces;

		if ( $this->is_validity_acting( 'card' ) || $this->is_validity_acting( 'conv' ) ) {
			/* 発送・支払方法ページ */
			if ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'delivery' == $usces->page ) {
				if ( $this->is_activate_card( 'module' ) || $this->is_activate_conv( 'module' ) ) {
					$acting_opts = $this->get_acting_settings();
					wp_register_style( 'paygent-token-style', USCES_FRONT_PLUGIN_URL . '/css/paygent_token.css' );
					wp_enqueue_style( 'paygent-token-style' );
					wp_register_script( 'usces_cart_paygent', USCES_FRONT_PLUGIN_URL . '/js/cart_paygent.js', array( 'jquery' ), USCES_VERSION, true );
					$paygent_params = array();
					if ( $this->is_activate_card( 'module' ) ) {
						$paygent_params['card_service_type']            = $this->get_service_type( 'card' );
						$paygent_params['seq_merchant_id']              = $acting_opts['seq_merchant_id'];
						$paygent_params['token_key']                    = $acting_opts['token_key'];
						$paygent_params['use_card_conf_number']         = $acting_opts['use_card_conf_number'];
						$paygent_params['message']['error_token']       = __( 'Credit card information is not appropriate.', 'usces' );
						$paygent_params['message']['error_card_number'] = __( 'The card number is not a valid credit card number.', 'usces' );
						$paygent_params['message']['error_card_expire'] = __( 'The card\'s expiration date is invalid.', 'usces' );
						$paygent_params['message']['error_card_cvc']    = __( 'The card\'s security code is invalid.', 'usces' );
					}
					if ( $this->is_activate_conv( 'module' ) ) {
						$paygent_params['conv_service_type']                     = $this->get_service_type( 'conv' );
						$paygent_params['message']['error_customer_family_name'] = '利用者名（姓）を入力してください。';
						$paygent_params['message']['error_customer_name']        = '利用者名（名）を入力してください。';
						$paygent_params['message']['error_customer_tel']         = '利用者電話番号を入力してください。';
					}
					wp_localize_script( 'usces_cart_paygent', 'paygent_params', $paygent_params );
					wp_enqueue_script( 'usces_cart_paygent' );
				}
			}
		}

		if ( $this->is_validity_acting( 'card' ) ) :
			if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) :
				/* クレジットカード情報更新ページ */
				if ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' == wp_unslash( $_GET['usces_page'] ) || 'member_update_settlement' == wp_unslash( $_GET['usces_page'] ) ) ) :
					$acting_opts = $this->get_acting_settings();
					wp_register_style( 'paygent-token-style', USCES_FRONT_PLUGIN_URL . '/css/paygent_token.css' );
					wp_enqueue_style( 'paygent-token-style' );
					wp_register_script( 'usces_member_paygent', USCES_FRONT_PLUGIN_URL . '/js/member_paygent.js', array( 'jquery' ), USCES_VERSION, true );
					$paygent_params                         = array();
					$paygent_params['seq_merchant_id']      = $acting_opts['seq_merchant_id'];
					$paygent_params['token_key']            = $acting_opts['token_key'];
					$paygent_params['use_card_conf_number'] = $acting_opts['use_card_conf_number'];
					$paygent_params['message']              = array(
						'error_token'       => __( 'Credit card information is not appropriate.', 'usces' ),
						'error_card_number' => __( 'The card number is not a valid credit card number.', 'usces' ),
						'error_card_expire' => __( 'The card\'s expiration date is invalid.', 'usces' ),
						'error_card_cvc'    => __( 'The card\'s security code is invalid.', 'usces' ),
						'confirm_deletion'  => __( 'Are you sure delete credit card registration?', 'usces' ),
					);
					wp_localize_script( 'usces_member_paygent', 'paygent_params', $paygent_params );
					wp_enqueue_script( 'usces_member_paygent' );
					print_google_recaptcha_response( wp_unslash( $_GET['usces_page'] ), 'member-card-info', 'member_update_settlement' );
				else :
					$member = $usces->get_member();
					if ( usces_have_member_continue_order( $member['ID'] ) || usces_have_member_regular_order( $member['ID'] ) ) :
						?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	$( "input[name='deletemember']" ).css( "display", "none" );
});
</script>
						<?php
					endif;
				endif;
			endif;
		endif;

		if ( $this->is_validity_acting( 'paidy' ) ) :
			/* 内容確認ページ */
			if ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'confirm' == $usces->page ) :
				$entry = $usces->cart->get_entry();
				$cart  = $usces->cart->get_cart();
				if ( empty( $entry['order']['total_full_price'] ) ) {
					return;
				}
				$payment = usces_get_payments_by_name( $entry['order']['payment_name'] );
				if ( isset( $payment['settlement'] ) && 'acting_paygent_paidy' == $payment['settlement'] ) {
				} else {
					return;
				}

				$continue = ( defined( 'WCEX_DLSELLER' ) ) ? usces_have_continue_charge( $cart ) : false;
				$regular  = ( defined( 'WCEX_AUTO_DELIVERY' ) ) ? usces_have_regular_order() : false;
				if ( $continue || $regular ) {
					return;
				}

				$acting_opts = $this->get_acting_settings();
				if ( usces_is_login() ) {
					$member           = $usces->get_member();
					$age              = ceil( ( strtotime( date_i18n( 'Y-m-d H:i:s' ) ) - strtotime( $member['registered'] ) ) / ( 60 * 60 * 24 ) );
					$member_data      = $this->get_buyer_data( $member['ID'] );
					$buyer_data       = '"user_id": "' . $member['ID'] . '",' . "\n";
					$buyer_data      .= "\t\t\t" . '"age": ' . $age . ',' . "\n";
					$buyer_data      .= "\t\t\t" . '"ltv": ' . $member_data['ltv'] . ',' . "\n";
					$buyer_data      .= "\t\t\t" . '"order_count": ' . $member_data['order_count'] . ',' . "\n";
					$buyer_data      .= "\t\t\t" . '"last_order_amount": ' . $member_data['last_order_amount'] . ',' . "\n";
					$buyer_data      .= "\t\t\t" . '"last_order_at": ' . $member_data['last_order_at'] . ',' . "\n";
					$number_of_points = '"number_of_points": "' . $member['point'] . '"' . "\n";
				} else {
					$buyer_data       = '"age": null,' . "\n";
					$buyer_data      .= "\t\t\t" . '"ltv": null,' . "\n";
					$buyer_data      .= "\t\t\t" . '"order_count": null,' . "\n";
					$buyer_data      .= "\t\t\t" . '"last_order_amount": null,' . "\n";
					$buyer_data      .= "\t\t\t" . '"last_order_at": null,' . "\n";
					$number_of_points = '';
				}
				$amount = usces_crform( $entry['order']['total_full_price'], false, false, 'return', false );
				if ( isset( $acting_opts['ope'] ) && 'public' == $acting_opts['ope'] ) {
					$email = trim( $entry['customer']['mailaddress1'] );
					if ( ! empty( $entry['customer']['tel'] ) ) {
						$phone = '"phone": "' . str_replace( '-', '', mb_convert_kana( $entry['customer']['tel'], 'a', 'UTF-8' ) ) . '"' . "\n";
					} else {
						$phone = '';
					}
				} else {
					$email = 'successful.payment@paidy.com';
					$phone = '"phone": "08000000001"' . "\n";
				}
				$name1 = $entry['customer']['name1'] . ' ' . $entry['customer']['name2'];
				if ( ! empty( $entry['customer']['name3'] ) ) {
					$name2 = '"name2": "' . $entry['customer']['name3'] . ' ' . $entry['customer']['name4'] . '",' . "\n";
				} else {
					$name2 = '';
				}
				$line1                         = '';
				$line2                         = '';
				$city                          = '';
				$state                         = '';
				$zip                           = '';
				$additional_shipping_addresses = '';
				if ( usces_is_login() && defined( 'WCEX_MSA' ) && isset( $entry['delivery']['delivery_flag'] ) && 2 == $entry['delivery']['delivery_flag'] ) {
					$msacart   = $usces->msacart->get_cart();
					$count_msa = ( is_array( $msacart ) ) ? count( $msacart ) : 0;
					if ( 0 < $count_msa ) {
						krsort( $msacart );
						$idx_msa = 0;
						foreach ( $msacart as $group_id => $group ) {
							$delivery         = $group['delivery'];
							$destination_info = msa_get_destination( $usces->current_member['id'], $delivery['destination_id'] );
							if ( 7 == strlen( trim( $destination_info['msa_zip'] ) ) ) {
								$msa_zip = substr( trim( $destination_info['msa_zip'] ), 0, 3 ) . '-' . substr( trim( $destination_info['msa_zip'] ), 3 );
							} else {
								$msa_zip = trim( $destination_info['msa_zip'] );
							}
							if ( 0 == $idx_msa ) {
								$line1 = trim( $destination_info['msa_address3'] );
								$line2 = trim( $destination_info['msa_address2'] );
								$city  = trim( $destination_info['msa_address1'] );
								$state = trim( $destination_info['msa_pref'] );
								$zip   = $msa_zip;
							} elseif ( 1 == $idx_msa ) {
								$additional_shipping_addresses .= '"additional_shipping_addresses": [{' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"line1": "' . trim( $destination_info['msa_address3'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"line2": "' . trim( $destination_info['msa_address2'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"city": "' . trim( $destination_info['msa_address1'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"state": "' . trim( $destination_info['msa_pref'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"zip": "' . $msa_zip . '"' . "\n";
								$additional_shipping_addresses .= "\t\t\t" . '}';
							} else {
								$additional_shipping_addresses .= ',' . "\n\t\t\t" . '{' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"line1": "' . trim( $destination_info['msa_address3'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"line2": "' . trim( $destination_info['msa_address2'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"city": "' . trim( $destination_info['msa_address1'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"state": "' . trim( $destination_info['msa_pref'] ) . '",' . "\n";
								$additional_shipping_addresses .= "\t\t\t\t" . '"zip": "' . $msa_zip . '"' . "\n";
								$additional_shipping_addresses .= "\t\t\t" . '}';
							}
							$idx_msa++;
						}
						$additional_shipping_addresses .= '],' . "\n";
					}
				} else {
					$line1 = trim( $entry['delivery']['address3'] );
					$line2 = trim( $entry['delivery']['address2'] );
					$city  = trim( $entry['delivery']['address1'] );
					$state = trim( $entry['delivery']['pref'] );
					if ( 7 == strlen( trim( $entry['delivery']['zipcode'] ) ) ) {
						$zip = substr( trim( $entry['delivery']['zipcode'] ), 0, 3 ) . '-' . substr( trim( $entry['delivery']['zipcode'] ), 3 );
					} else {
						$zip = trim( $entry['delivery']['zipcode'] );
					}
				}
				$items = '';
				foreach ( $cart as $cart_row ) {
					$items .= "\n\t\t\t\t" . '{' . "\n";
					$items .= "\t\t\t\t\t" . '"id": "' . $usces->getItemCode( $cart_row['post_id'] ) . '",' . "\n";
					$items .= "\t\t\t\t\t" . '"quantity": ' . $cart_row['quantity'] . ',' . "\n";
					$items .= "\t\t\t\t\t" . '"title": "' . $usces->getItemName( $cart_row['post_id'] ) . '",' . "\n";
					$items .= "\t\t\t\t\t" . '"unit_price": ' . $cart_row['price'] . "\n";
					$items .= "\t\t\t\t" . '},';
				}
				if ( isset( $entry['order']['discount'] ) && 0 != $entry['order']['discount'] ) {
					$items .= "\n\t\t\t\t" . '{' . "\n";
					$items .= "\t\t\t\t\t" . '"quantity": 1,' . "\n";
					$items .= "\t\t\t\t\t" . '"title": "' . apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ) ) . '",' . "\n";
					$items .= "\t\t\t\t\t" . '"unit_price": ' . $entry['order']['discount'] . "\n";
					$items .= "\t\t\t\t" . '},';
				}
				if ( usces_is_member_system() && usces_is_member_system_point() && ! empty( $entry['order']['usedpoint'] ) ) {
					$items .= "\n\t\t\t\t" . '{' . "\n";
					$items .= "\t\t\t\t\t" . '"quantity": 1,' . "\n";
					$items .= "\t\t\t\t\t" . '"title": "' . __( 'Used points', 'usces' ) . '",' . "\n";
					$items .= "\t\t\t\t\t" . '"unit_price": ' . ( $entry['order']['usedpoint'] * -1 ) . "\n";
					$items .= "\t\t\t\t" . '},';
				}
				if ( ! empty( $entry['order']['cod_fee'] ) ) {
					$items .= "\n\t\t\t\t" . '{' . "\n";
					$items .= "\t\t\t\t\t" . '"quantity": 1,' . "\n";
					$items .= "\t\t\t\t\t" . '"title": "' . apply_filters( 'usces_filter_paidy_fee_label', 'Paidy手数料' ) . '",' . "\n";
					$items .= "\t\t\t\t\t" . '"unit_price": ' . $entry['order']['cod_fee'] . "\n";
					$items .= "\t\t\t\t" . '},';
				}
				$items = rtrim( $items, ',' ) . "\n";
				if ( 0 != $entry['order']['shipping_charge'] ) {
					$shipping = $entry['order']['shipping_charge'];
				} else {
					$shipping = '0';
				}
				if ( isset( $entry['order']['tax'] ) && 'exclude' == usces_get_tax_mode() ) {
					$tax = $entry['order']['tax'] . "\n";
				} else {
					$tax = '0' . "\n";
				}
				?>
<script type="text/javascript" src="https://apps.paidy.com/"></script>
<script type="text/javascript">
var config = {
	"api_key": "<?php echo esc_html( $acting_opts['paidy_public_key'] ); ?>",
	"closed": function( callbackData ) {
		if ( 'closed' == callbackData.status ) {
			document.getElementById( "paidy-checkout-button" ).style.pointerEvents = "auto";
		} else {
			var purchase_form = document.forms.purchase_form;
			purchase_form.paidy_id.value = callbackData.id;
			purchase_form.paidy_created_at.value = callbackData.created_at;
			purchase_form.paidy_status.value = callbackData.status;
			purchase_form.submit();
		}
	}
};
var paidyHandler = Paidy.configure( config );
function paidyPay() {
	document.getElementById( "paidy-checkout-button" ).style.pointerEvents = "none";
	var rand    = document.getElementsByName( "trading_id" )[0].value;
	var payload = {
		"amount": <?php echo esc_js( $amount ); ?>,
		"currency": "JPY",
		"store_name": "<?php echo esc_html( get_option( 'blogname' ) ); ?>",
		"buyer": {
			"email": "<?php echo esc_js( $email ); ?>",
			"name1": "<?php echo esc_js( $name1 ); ?>",
			<?php wel_esc_script_e( $name2 ); ?>
			<?php wel_esc_script_e( $phone ); ?>
		},
		"buyer_data": {
			<?php wel_esc_script_e( $buyer_data ); ?>
			<?php wel_esc_script_e( $additional_shipping_addresses ); ?>
			<?php wel_esc_script_e( $number_of_points ); ?>
		},
		"order": {
			"items": [
				<?php wel_esc_script_e( $items ); ?>
			],
			"order_ref": rand,
			"shipping": <?php wel_esc_script_e( $shipping ); ?>,
			"tax": <?php wel_esc_script_e( $tax ); ?>
		},
		"shipping_address": {
			"line1": "<?php echo esc_js( $line1 ); ?>",
			"line2": "<?php echo esc_js( $line2 ); ?>",
			"city": "<?php echo esc_js( $city ); ?>",
			"state": "<?php echo esc_js( $state ); ?>",
			"zip": "<?php echo esc_js( $zip ); ?>"
		}
	};
	paidyHandler.launch( payload );
};
</script>
				<?php
			endif;
		endif;
	}

	/**
	 * Paidy Checkout Buyer data オブジェクト
	 *
	 * @param  int $member_id Post ID.
	 * @return array
	 */
	private function get_buyer_data( $member_id ) {
		global $wpdb;

		$ltv               = 0;
		$order_count       = 0;
		$last_order_amount = 0;
		$last_order_at     = 0;

		$query   = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_order WHERE `mem_id` = %d ORDER BY `order_date` DESC", $member_id );
		$results = $wpdb->get_results( $query, ARRAY_A );
		if ( 0 < count( $results ) ) {
			foreach ( $results as $order ) {
				if ( false === strpos( $order['order_status'], 'cancel' ) && false === strpos( $order['order_status'], 'estimate' ) ) {
					$payment = usces_get_payments_by_name( $order['order_payment_name'] );
					if ( isset( $payment['settlement'] ) && 'acting_paygent_paidy' != $payment['settlement'] ) {
						$total_price = $order['order_item_total_price'] - $order['order_usedpoint'] + $order['order_discount'] + $order['order_shipping_charge'] + $order['order_cod_fee'] + $order['order_tax'];
						if ( 0 == $order_count ) {
							$last_order_amount = $total_price;
							$last_order_at     = ceil( ( strtotime( date_i18n( 'Y-m-d H:i:s' ) ) - strtotime( $order['order_date'] ) ) / ( 60 * 60 * 24 ) );
						}
						$ltv += $total_price;
						$order_count++;
					}
				}
			}
		}
		$member_data = array(
			'ID'                => $member_id,
			'ltv'               => $ltv,
			'order_count'       => $order_count,
			'last_order_amount' => $last_order_amount,
			'last_order_at'     => $last_order_at,
		);

		return $member_data;
	}

	/**
	 * Set uscesL10n.
	 * usces_filter_uscesL10n
	 *
	 * @param  string $l10n uscesL10n.
	 * @param  int    $post_id Post ID.
	 * @return string
	 */
	public function set_uscesL10n( $l10n, $post_id ) {
		global $usces;

		if ( ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'delivery' == $usces->page ) ||
			( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) && ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' == wp_unslash( $_GET['usces_page'] ) || 'member_update_settlement' == wp_unslash( $_GET['usces_page'] ) ) ) ) ) {
			if ( $this->is_activate_card( 'module' ) ) {
				$front_ajaxurl = USCES_SSL_URL;
				if ( '/' != substr( $front_ajaxurl, -1 ) ) {
					$front_ajaxurl .= '/';
				}
				$front_ajaxurl .= 'index.php';
				$l10n          .= "'front_ajaxurl': '" . $front_ajaxurl . "',\n";
			}
		}
		return $l10n;
	}

	/**
	 * 支払方法チェック
	 * usces_filter_delivery_check
	 *
	 * @param  string $mes Validation check message.
	 * @return string
	 */
	public function delivery_check( $mes ) {
		if ( ! isset( $_POST['offer']['payment_name'] ) ) {
			return $mes;
		}

		if ( ! empty( $mes ) ) {
			return $mes;
		}

		$payment    = usces_get_payments_by_name( $_POST['offer']['payment_name'] );
		$acting_flg = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';
		switch ( $acting_flg ) {
			case 'acting_paygent_card':
				if ( $this->is_activate_card( 'module' ) ) {
					if ( isset( $_POST['stock_card'] ) && 'stock' == wp_unslash( $_POST['stock_card'] ) ) {
						$acting_opts = $this->get_acting_settings();
						if ( 'on' == $acting_opts['use_card_conf_number'] ) {
							if ( isset( $_POST['token'] ) && empty( $_POST['token'] ) ) {
								$mes .= __( 'Please enter the card information correctly.', 'usces' ) . '<br />';
							}
						}
					} else {
						if ( isset( $_POST['token'] ) && empty( $_POST['token'] ) ) {
							$mes .= __( 'Please enter the card information correctly.', 'usces' ) . '<br />';
						} else {
							if ( ! wel_check_credit_security() ) {
								$mes .= __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '<br />';
							}
						}
					}
				}
				break;

			case 'acting_paygent_conv':
				if ( $this->is_activate_conv( 'module' ) ) {
					if ( isset( $_POST['customer_family_name'] ) && empty( $_POST['customer_family_name'] ) ) {
						$mes .= '利用者名（姓）を入力してください。<br />';
					}
					if ( isset( $_POST['customer_name'] ) && empty( $_POST['customer_name'] ) ) {
						$mes .= '利用者名（名）を入力してください。<br />';
					}
					if ( isset( $_POST['customer_tel'] ) && empty( $_POST['customer_tel'] ) ) {
						$mes .= '利用者電話番号を入力してください。<br />';
					}
				}
				break;

			case 'acting_paygent_atm':
				break;

			case 'acting_paygent_bank':
				break;
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

		$form = '';
		switch ( $payment['settlement'] ) {
			case 'acting_paygent_card':
				if ( 'activate' != $payment['use'] || ! $this->is_activate_card( 'module' ) ) {
					return $form;
				}

				$acting_opts = $this->get_acting_settings();
				$re_enter    = ( isset( $_REQUEST['re_enter'] ) && false !== strpos( $_REQUEST['re_enter'], 'paygent_card' ) ) ? true : false;

				$customer_id = '';
				if ( usces_is_login() ) {
					$usces->get_current_member();
					$customer_id = $usces->current_member['id'];
				}

				$customer = array();
				if ( 'on' == $acting_opts['stock_card_mode'] ) {
					if ( ! empty( $customer_id ) ) {
						$customer = $this->customer_ref( $customer_id );
					}
				}
				$split_count     = ( isset( $_POST['split_count'] ) ) ? wp_unslash( $_POST['split_count'] ) : '01';
				$card_area_class = 'paygent_card_area';

				$form .= '
				<table class="customer_form" id="paygent_card_form">
					<tr>
						<th scope="row"><em>' . __( '*', 'usces' ) . '</em>' . __( 'Credit card information', 'usces' ) . '</th>
						<td>';
				if ( 'on' == $acting_opts['stock_card_mode'] && ! empty( $customer['card_number'] ) ) {
					$card_area_class = 'paygent_new_card_area';
					$cardlast4       = substr( $customer['card_number'], -4 );
					$split_type      = $this->split_type( $customer['card_brand'] );
					$form           .= '
						<p><label><input type="radio" name="stock_card" class="stock_card" id="stock_card_use" value="stock"><span>登録済みのクレジットカードを使う</span></label></p>
							<div class="paygent_registerd_card_area">
								<div>登録済みのカード番号下4桁<span class="cardlast4">' . $cardlast4 . '</span></div>
								<input type="hidden" id="split_type" value="' . $split_type . '" />
							</div>
						<p><label><input type="radio" name="stock_card" class="stock_card" id="stock_card_new" value="new"><span>新しいクレジットカードを使う</span></label></p>';
				}
				$form .= '<div class="' . $card_area_class . '">
							<dl>
								<dt>' . __( 'card number', 'usces' ) . '</dt>
								<dd><input type="tel" class="card_number" id="card_number" maxlength="16" value=""></dd>';
				if ( 'on' == $acting_opts['stock_card_mode'] ) {
					if ( ! empty( $customer['card_number'] ) ) {
						if ( usces_have_regular_order() || usces_have_continue_charge() ) {
							$form .= '<input type="hidden" name="stock_card_mode" value="change">';
						} else {
							$form .= '<dd><label><input type="checkbox" name="stock_card_mode" id="stock_card_mode" value="change"><span id="stock_card_mode_label">登録済のカードを変更して購入する</span></label></dd>';
						}
					} else {
						if ( usces_have_regular_order() || usces_have_continue_charge() ) {
							$form .= '<input type="hidden" name="stock_card_mode" value="save">';
						} else {
							$form .= '<dd><label><input type="checkbox" name="stock_card_mode" id="stock_card_mode" value="save"><span id="stock_card_mode_label">クレジットカードを登録して購入する</span></label></dd>';
						}
					}
				}
				$cardno_attention = apply_filters( 'usces_filter_cardno_attention', '<dd><div class="attention">' . __( '* Please do not enter symbols or letters other than numbers such as space (blank), hyphen (-) between numbers.', 'usces' ) . '</div></dd>' );
				$form            .= $cardno_attention;
				$form            .= '<dt>' . __( 'Card expiration', 'usces' ) . '</dt>
								<dd><select class="expire_month" id="expire_month">
									<option value="">--</option>';
				for ( $i = 1; $i <= 12; $i++ ) {
					$form .= '<option value="' . sprintf( '%02d', $i ) . '">' . sprintf( '%02d', $i ) . '</option>';
				}
				$form .= '</select>' . __( 'month', 'usces' ) . '&nbsp;
							<select class="expire_year" id="expire_year">
								<option value="">--</option>';
				for ( $i = 0; $i <= 15; $i++ ) {
					$year  = date_i18n( 'Y' ) + $i;
					$year  = substr( $year, -2 );
					$form .= '
								<option value="' . $year . '">' . $year . '</option>';
				}
				$form .= '</select>' . __( 'year', 'usces' ) . '</dd>
					</dl>
				</div>';
				if ( 'on' == $acting_opts['use_card_conf_number'] ) {
					$form         .= '<div class="paygent_card_split_count_area">
						<dl><dt>' . __( 'security code', 'usces' ) . '</dt>
							<dd><input type="tel" class="cvc" id="cvc" maxlength="4" value=""></dd>';
					$cvc_attention = apply_filters( 'usces_filter_seccd_attention', '' );
					$form         .= $cvc_attention;
					$form         .= '
						</dl>
					</div>';
				}

				$form_split_count = '';
				if ( ( usces_have_regular_order() || usces_have_continue_charge() ) && usces_is_login() ) {
					$form_split_count .= '<input type="hidden" name="split_count" value="01" />';
				} else {
					if ( 0 === (int) $acting_opts['payment_class'] ) {
						$form_split_count .= '<input type="hidden" name="split_count" value="01" />';
					} elseif ( 1 <= (int) $acting_opts['payment_class'] ) {
						$form_split_count  = '<div class="paygent_card_split_count_area">
							<dl><dt>分割回数</dt>
								<dd>';
						$form_split_count .= '
									<select name="split_count" id="split_count_default" >
										<option value="01"' . ( ( '01' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'One time payment', 'usces' ) . '</option>
									</select>';
						$form_split_count .= '
									<select name="split_count" id="split_count_4535" style="display:none;" disabled="disabled" >
										<option value="01"' . ( ( '01' == $split_count ) ? ' selected="selected"' : '' ) . '>1' . __( '-time payment', 'usces' ) . '</option>
										<option value="02"' . ( ( '02' == $split_count ) ? ' selected="selected"' : '' ) . '>2' . __( '-time payment', 'usces' ) . '</option>
										<option value="03"' . ( ( '03' == $split_count ) ? ' selected="selected"' : '' ) . '>3' . __( '-time payment', 'usces' ) . '</option>
										<option value="05"' . ( ( '05' == $split_count ) ? ' selected="selected"' : '' ) . '>5' . __( '-time payment', 'usces' ) . '</option>
										<option value="06"' . ( ( '06' == $split_count ) ? ' selected="selected"' : '' ) . '>6' . __( '-time payment', 'usces' ) . '</option>
										<option value="10"' . ( ( '10' == $split_count ) ? ' selected="selected"' : '' ) . '>10' . __( '-time payment', 'usces' ) . '</option>
										<option value="12"' . ( ( '12' == $split_count ) ? ' selected="selected"' : '' ) . '>12' . __( '-time payment', 'usces' ) . '</option>
										<option value="15"' . ( ( '15' == $split_count ) ? ' selected="selected"' : '' ) . '>15' . __( '-time payment', 'usces' ) . '</option>
										<option value="18"' . ( ( '18' == $split_count ) ? ' selected="selected"' : '' ) . '>18' . __( '-time payment', 'usces' ) . '</option>
										<option value="20"' . ( ( '20' == $split_count ) ? ' selected="selected"' : '' ) . '>20' . __( '-time payment', 'usces' ) . '</option>
										<option value="24"' . ( ( '24' == $split_count ) ? ' selected="selected"' : '' ) . '>24' . __( '-time payment', 'usces' ) . '</option>
										<option value="80"' . ( ( '80' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'Libor Funding pay', 'usces' ) . '</option>';
						if ( 1 == (int) $acting_opts['payment_class'] ) {
							$form_split_count .= '
										<option value="23"' . ( ( '23' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'Bonus lump-sum payment', 'usces' ) . '</option>';
						}
						$form_split_count .= '
									</select>';
						$form_split_count .= '
									<select name="split_count" id="split_count_37" style="display:none;" disabled="disabled" >
										<option value="01"' . ( ( '01' == $split_count ) ? ' selected="selected"' : '' ) . '>1' . __( '-time payment', 'usces' ) . '</option>
										<option value="03"' . ( ( '03' == $split_count ) ? ' selected="selected"' : '' ) . '>3' . __( '-time payment', 'usces' ) . '</option>
										<option value="05"' . ( ( '05' == $split_count ) ? ' selected="selected"' : '' ) . '>5' . __( '-time payment', 'usces' ) . '</option>
										<option value="06"' . ( ( '06' == $split_count ) ? ' selected="selected"' : '' ) . '>6' . __( '-time payment', 'usces' ) . '</option>
										<option value="10"' . ( ( '10' == $split_count ) ? ' selected="selected"' : '' ) . '>10' . __( '-time payment', 'usces' ) . '</option>
										<option value="12"' . ( ( '12' == $split_count ) ? ' selected="selected"' : '' ) . '>12' . __( '-time payment', 'usces' ) . '</option>
										<option value="15"' . ( ( '15' == $split_count ) ? ' selected="selected"' : '' ) . '>15' . __( '-time payment', 'usces' ) . '</option>
										<option value="18"' . ( ( '18' == $split_count ) ? ' selected="selected"' : '' ) . '>18' . __( '-time payment', 'usces' ) . '</option>
										<option value="20"' . ( ( '20' == $split_count ) ? ' selected="selected"' : '' ) . '>20' . __( '-time payment', 'usces' ) . '</option>
										<option value="24"' . ( ( '24' == $split_count ) ? ' selected="selected"' : '' ) . '>24' . __( '-time payment', 'usces' ) . '</option>';
						if ( 1 == (int) $acting_opts['payment_class'] ) {
							$form_split_count .= '
										<option value="23"' . ( ( '23' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'Bonus lump-sum payment', 'usces' ) . '</option>';
						}
						$form_split_count .= '
									</select>';
						$form_split_count .= '
									<select name="split_count" id="split_count_36" style="display:none;" disabled="disabled" >
										<option value="01"' . ( ( '01' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'One time payment', 'usces' ) . '</option>
										<option value="80"' . ( ( '80' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'Libor Funding pay', 'usces' ) . '</option>';
						if ( 1 == (int) $acting_opts['payment_class'] ) {
							$form_split_count .= '
										<option value="23"' . ( ( '23' == $split_count ) ? ' selected="selected"' : '' ) . '>' . __( 'Bonus lump-sum payment', 'usces' ) . '</option>';
						}
						$form_split_count .= '
									</select>';
						$form_split_count .= '
								</dd>
							</dl>
						</div>';
					}
				}
				$form .= apply_filters( 'usces_filter_paygent_card_split_count', $form_split_count );
				$form .= '
						<input type="hidden" name="acting" value="paygent_card" />
						<input type="hidden" name="confirm" value="confirm" />
						<input type="hidden" name="token" id="token" value="" />
						<input type="hidden" name="masked_card_number" id="masked_card_number" value="" />
						<input type="hidden" name="valid_until" id="valid_until" value="" />
						<input type="hidden" name="fingerprint" id="fingerprint" value="" />
						<input type="hidden" name="hc" id="hc" value="" />';
				$form .= '
					</td></tr>
				</table>';
				break;

			case 'acting_paygent_conv':
				if ( 'activate' != $payment['use'] || ! $this->is_activate_conv( 'module' ) ) {
					return $form;
				}

				$acting_opts    = $this->get_acting_settings();
				$entry          = $usces->cart->get_entry();
				$cvs_company_id = ( isset( $_POST['cvs_company_id'] ) ) ? $_POST['cvs_company_id'] : '';
				$name1          = ( isset( $_POST['customer_family_name'] ) ) ? $_POST['customer_family_name'] : trim( $entry['customer']['name1'] );
				$name2          = ( isset( $_POST['customer_name'] ) ) ? $_POST['customer_name'] : trim( $entry['customer']['name2'] );
				$tel            = ( isset( $_POST['customer_tel'] ) ) ? $_POST['customer_tel'] : trim( $entry['customer']['tel'] );
				$tel            = str_replace( '-', '', mb_convert_kana( $tel, 'a', 'UTF-8' ) );

				$form .= '
				<table class="customer_form" id="paygent_conv_form">
					<tr>
						<th scope="row"><em>' . __( '*', 'usces' ) . '</em>' . __( 'Convenience store for payment', 'usces' ) . '</th>
						<td colspan="2">
							<select name="cvs_company_id" id="cvs_company_id">';
				foreach ( $this->cvs_company_a as $cvs_cd => $cvs_company ) {
					$cvs_type = $this->cvs_type_a[ $cvs_cd ];
					if ( ! in_array( $cvs_type, $acting_opts['cvs_type'] ) ) {
						continue;
					}
					$selected = ( $cvs_company_id == $cvs_cd ) ? ' selected="selected"' : '';
					$form    .= '
								<option value="' . esc_attr( $cvs_cd ) . '"' . $selected . '>' . esc_attr( $cvs_company ) . '</option>';
				}
				$form .= '
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><em>' . __( '*', 'usces' ) . '</em>利用者名</th>
						<td>姓<input name="customer_family_name" class="customer_name" id="customer_family_name" type="text" value="' . esc_attr( $name1 ) . '" /></td>
						<td>名<input name="customer_name" class="customer_name" id="customer_name" type="text" value="' . esc_attr( $name2 ) . '" />（全角）</td>
					</tr>
					<tr>
						<th scope="row"><em>' . __( '*', 'usces' ) . '</em>利用者電話番号</th>
						<td colspan="2"><input name="customer_tel" id="customer_tel" type="tel" value="' . esc_attr( $tel ) . '" /></td>
					</tr>
				</table>';
				break;

			case 'acting_paygent_atm':
				if ( 'activate' != $payment['use'] || ! $this->is_activate_atm() ) {
					return $form;
				}

				$acting_opts = $this->get_acting_settings();
				$entry       = $usces->cart->get_entry();
				$name1       = ( isset( $_POST['customer_family_name'] ) ) ? $_POST['customer_family_name'] : trim( $entry['customer']['name1'] );
				$name2       = ( isset( $_POST['customer_name'] ) ) ? $_POST['customer_name'] : trim( $entry['customer']['name2'] );
				$name3       = ( isset( $_POST['customer_family_name_kana'] ) ) ? $_POST['customer_family_name_kana'] : String_Utitily::convert_katakana_zen2han( trim( $entry['customer']['name3'] ) );
				$name4       = ( isset( $_POST['customer_name_kana'] ) ) ? $_POST['customer_name_kana'] : String_Utitily::convert_katakana_zen2han( trim( $entry['customer']['name4'] ) );

				$form .= '
				<table class="customer_form" id="paygent_atm_form">
					<tr>
						<th scope="row">利用者名</th>
						<td>姓<input name="customer_family_name" class="customer_name" id="customer_family_name" type="text" value="' . esc_attr( $name1 ) . '" /></td>
						<td>名<input name="customer_name" class="customer_name" id="customer_name" type="text" value="' . esc_attr( $name2 ) . '" />（全角）</td>
					</tr>
					<tr>
						<th scope="row">利用者名ｶﾅ</th>
						<td>ｾｲ<input name="customer_family_name_kana" class="customer_name" id="customer_family_name_kana" type="text" value="' . esc_attr( $name3 ) . '" /></td>
						<td>ﾒｲ<input name="customer_name_kana" class="customer_name" id="customer_name_kana" type="text" value="' . esc_attr( $name4 ) . '" />（半角ｶﾅ）</td>
					</tr>
				</table>';
				break;

			case 'acting_paygent_bank':
				if ( 'activate' != $payment['use'] || ! $this->is_activate_bank() ) {
					return $form;
				}

				$acting_opts = $this->get_acting_settings();
				$entry       = $usces->cart->get_entry();
				$name1       = ( isset( $_POST['customer_family_name'] ) ) ? $_POST['customer_family_name'] : trim( $entry['customer']['name1'] );
				$name2       = ( isset( $_POST['customer_name'] ) ) ? $_POST['customer_name'] : trim( $entry['customer']['name2'] );
				$name3       = ( isset( $_POST['customer_family_name_kana'] ) ) ? $_POST['customer_family_name_kana'] : String_Utitily::convert_katakana_zen2han( trim( $entry['customer']['name3'] ) );
				$name4       = ( isset( $_POST['customer_name_kana'] ) ) ? $_POST['customer_name_kana'] : String_Utitily::convert_katakana_zen2han( trim( $entry['customer']['name4'] ) );

				$form .= '
				<table class="customer_form" id="paygent_bank_form">
					<tr>
						<th scope="row">利用者名</th>
						<td>姓<input name="customer_family_name" class="customer_name" id="customer_family_name" type="text" value="' . esc_attr( $name1 ) . '" /></td>
						<td>名<input name="customer_name" class="customer_name" id="customer_name" type="text" value="' . esc_attr( $name2 ) . '" />（全角）</td>
					</tr>
					<tr>
						<th scope="row">利用者名ｶﾅ</th>
						<td>ｾｲ<input name="customer_family_name_kana" class="customer_name" id="customer_family_name_kana" type="text" value="' . esc_attr( $name3 ) . '" /></td>
						<td>ﾒｲ<input name="customer_name_kana" class="customer_name" id="customer_name_kana" type="text" value="' . esc_attr( $name4 ) . '" />（半角ｶﾅ）</td>
					</tr>
				</table>';
				break;
		}
		return $form;
	}

	/**
	 * お預かりカードの分割可能回数
	 *
	 * @param  string $card_brand Card brand.
	 * @return string
	 */
	private function split_type( $card_brand ) {
		switch ( $card_brand ) {
			case 'VISA':
			case 'JCB':
			case 'MASTER':
				$split_type = '4535';
				break;
			case 'DINERS':
				$split_type = '36';
				break;
			case 'AMEX':
				$split_type = '37';
				break;
			default:
				$split_type = 'default';
		}
		return $split_type;
	}

	/**
	 * 購入完了メッセージ
	 * usces_filter_completion_settlement_message
	 *
	 * @param  string $form Purchase complete message.
	 * @param  array  $entry Entry data.
	 * @return string
	 */
	public function completion_settlement_message( $form, $entry ) {
		$acting = ( isset( $_GET['acting'] ) ) ? wp_unslash( $_GET['acting'] ) : '';
		if ( 'paygent_conv' == $acting || 'paygent_atm' == $acting ) {
			$form .= '<div class="completion-settlement-message"><span class="bold">' . $this->acting_formal_name . esc_html( $entry['order']['payment_name'] ) . '</span>';
			$form .= '<p>' . sprintf( __( "Information on payment will be mailed to %s.", 'usces' ), esc_html( $entry['customer']['mailaddress1'] ) ) . '</p>';
			$form .= '</div>';
		}
		return $form;
	}

	/**
	 * Temporary storage of session data.
	 * usces_filter_save_order_acting_data
	 *
	 * @param  array $data Session data.
	 * @return array
	 */
	public function save_order_acting_data( $data ) {
		if ( isset( $_POST['_nonce'] ) ) {
			$data['_nonce'] = wp_unslash( $_POST['_nonce'] );
		}
		return $data;
	}

	/**
	 * 会員データ削除チェック
	 * usces_filter_delete_member_check
	 *
	 * @param  boolean $deletable Deletable.
	 * @param  int     $member_id Member ID.
	 * @return boolean
	 */
	public function delete_member_check( $deletable, $member_id ) {
		$customer = $this->customer_ref( $member_id );
		if ( isset( $customer['customer_id'] ) && $customer['customer_id'] == $member_id ) {
			if ( usces_have_member_continue_order( $member_id ) || usces_have_member_regular_order( $member_id ) ) {
				$deletable = false;
			}
		}
		return $deletable;
	}

	/**
	 * 会員データ削除
	 * usces_action_pre_delete_memberdata
	 *
	 * @param  string $member_id Member ID.
	 */
	public function delete_member( $member_id ) {
		$this->customer_card_del( $member_id );
	}

	/**
	 * クレジットカード登録・変更ページ表示
	 * usces_filter_template_redirect
	 */
	public function member_update_settlement() {
		global $usces;

		if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) {
			if ( ! usces_is_membersystem_state() || ! usces_is_login() ) {
				return;
			}
			$acting_opts = $this->get_acting_settings();
			if ( 'on' != $acting_opts['stock_card_mode'] ) {
				return;
			}
			if ( isset( $_REQUEST['usces_page'] ) && 'member_update_settlement' == $_REQUEST['usces_page'] ) {
				add_filter( 'usces_filter_states_form_js', array( $this, 'states_form_js' ) );
				$usces->page = 'member_update_settlement';
				$this->member_update_settlement_form();
				exit();
			} elseif ( isset( $_REQUEST['usces_page'] ) && 'member_register_settlement' == $_REQUEST['usces_page'] ) {
				add_filter( 'usces_filter_states_form_js', array( $this, 'states_form_js' ) );
				$usces->page = 'member_register_settlement';
				$this->member_update_settlement_form();
				exit();
			}
		}
		return false;
	}

	/**
	 * クレジットカード登録・変更ページ表示
	 * usces_filter_states_form_js
	 *
	 * @param  string $js Scripts.
	 * @return string
	 */
	public function states_form_js( $js ) {
		return '';
	}

	/**
	 * クレジットカード登録・変更ページリンク
	 * usces_action_member_submenu_list
	 */
	public function e_update_settlement() {
		global $usces;

		$member = $usces->get_member();
		$form   = $this->update_settlement( '', $member );
		echo $form; // no escape.
	}

	/**
	 * クレジットカード登録・変更ページリンク
	 * usces_filter_member_submenu_list
	 *
	 * @param  string $form Submenu area of the member page.
	 * @param  array  $member Member information.
	 * @return string
	 */
	public function update_settlement( $form, $member ) {
		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['card_activate'] ) && 'off' != $acting_opts['card_activate'] && 'on' == $acting_opts['stock_card_mode'] && ! empty( $member['ID'] ) ) {
			$customer = $this->customer_ref( $member['ID'] );
			if ( isset( $customer['customer_id'] ) && $customer['customer_id'] == $member['ID'] ) {
				$update_settlement_url = add_query_arg(
					array(
						'usces_page' => 'member_update_settlement',
						're-enter'   => 1,
					),
					USCES_MEMBER_URL
				);
				$form                 .= '<li><a href="' . $update_settlement_url . '">' . __( 'Change the credit card is here >>', 'usces' ) . '</a></li>';
			} else {
				$register_settlement_url = add_query_arg(
					array(
						'usces_page' => 'member_register_settlement',
						're-enter'   => 1,
					),
					USCES_MEMBER_URL
				);
				$form                   .= '<li><a href="' . $register_settlement_url . '">' . __( 'Credit card registration is here >>', 'usces' ) . '</a></li>';
			}
		}
		return $form;
	}

	/**
	 * クレジットカード登録・変更ページ
	 */
	public function member_update_settlement_form() {
		global $usces;

		$member      = $usces->get_member();
		$acting_opts = $this->get_acting_settings();

		$form                  = '';
		$script                = '';
		$done_message          = '';
		$update_settlement_url = add_query_arg(
			array(
				'usces_page' => $usces->page,
				'settlement' => 1,
				're-enter'   => 1,
			),
			USCES_MEMBER_URL
		);
		$register              = ( 'member_register_settlement' == $usces->page ) ? true : false;
		$deleted               = false;

		$cardlast4 = '';
		$expyy     = '';
		$expmm     = '';

		$update = ( isset( $_POST['update'] ) ) ? $_POST['update'] : '';
		if ( 'register' == $update ) {
			check_admin_referer( 'member_update_settlement', 'wc_nonce' );
			$verify_action = wel_verify_update_settlement( $member['ID'] );
			if ( ! $verify_action ) {
				$usces->error_message .= '<p>' . __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '</p>';
				$register              = false;
			} else {
				$token    = ( isset( $_POST['token'] ) ) ? trim( $_POST['token'] ) : '';
				$customer = $this->customer_card_add( $member['ID'], $token );
				if ( isset( $customer['result_status'] ) && RESULT_STATUS_ERROR == $customer['result_status'] ) {
					$usces->error_message .= '<p>' . $customer['result_message'] . '</p>';
					$done_message          = __( 'Registration failed.', 'usces' ) . $customer['response_code'];
				} else {
					$done_message = __( 'Successfully registered.', 'usces' );
					$register     = false;
				}
			}
		} elseif ( 'update' == $update ) {
			check_admin_referer( 'member_update_settlement', 'wc_nonce' );
			$verify_action = wel_verify_update_settlement( $member['ID'] );
			if ( ! $verify_action ) {
				$usces->error_message .= '<p>' . __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '</p>';
				$register              = false;
			} else {
				$token    = ( isset( $_POST['token'] ) ) ? trim( $_POST['token'] ) : '';
				$customer = $this->customer_card_upd( $member['ID'], $token );
				if ( isset( $customer['result_status'] ) && RESULT_STATUS_ERROR == $customer['result_status'] ) {
					$usces->error_message .= '<p>' . $customer['result_message'] . '</p>';
					$done_message          = __( 'Update failed.', 'usces' ) . $customer['response_code'];
					$register              = true; /* 登録画面 */
				} else {
					$this->send_update_settlement_mail();
					$done_message = __( 'Successfully updated.', 'usces' );
				}
			}
		} elseif ( 'delete' == $update ) {
			check_admin_referer( 'member_update_settlement', 'wc_nonce' );
			$customer = $this->customer_card_del( $member['ID'] );
			if ( isset( $customer['result_status'] ) && RESULT_STATUS_ERROR == $customer['result_status'] ) {
				$usces->error_message .= '<p>' . $customer['result_message'] . '</p>';
				$done_message          = __( 'Deletion failed.', 'usces' ) . $customer['response_code'];
			} else {
				$done_message = __( 'Successfully deleted.', 'usces' );
				$register     = true;
			}
		}

		$customer = $this->customer_ref( $member['ID'] );
		if ( ! empty( $customer ) && isset( $customer['card_number'] ) && isset( $customer['card_valid_term'] ) ) {
			$cardlast4 = substr( $customer['card_number'], -4 );
			$expyy     = substr( $customer['card_valid_term'], 2, 2 );
			$expmm     = substr( $customer['card_valid_term'], 0, 2 );
		}
		$form .= '<input name="acting" type="hidden" value="' . $this->paymod_id . '" />
			<table class="customer_form" id="' . $this->paymod_id . '">';
		if ( ! empty( $cardlast4 ) ) {
			$form .= '
				<tr>
					<th scope="row">' . __( 'The last four digits of your card number', 'usces' ) . '</th>
					<td colspan="2"><p>' . $cardlast4 . '</p></td>
				</tr>';
		}
		$cardno_attention = apply_filters( 'usces_filter_cardno_attention', __( '(Single-byte numbers only)', 'usces' ) . '<div class="attention">' . __( '* Please do not enter symbols or letters other than numbers such as space (blank), hyphen (-) between numbers.', 'usces' ) . '</div>' );
		$form            .= '
				<tr>
					<th scope="row">' . __( 'card number', 'usces' ) . '</th>
					<td colspan="2"><input type="tel" class="card_number" id="card_number" maxlength="16" value="">' . $cardno_attention . '</td>
				</tr>';
		$form            .= '
				<tr>
					<th scope="row">' . __( 'Card expiration', 'usces' ) . '</th>
					<td colspan="2">
					<select class="expire_month" id="expire_month">
						<option value=""' . ( empty( $expmm ) ? ' selected="selected"' : '' ) . '>--</option>';
		for ( $i = 1; $i <= 12; $i++ ) {
			$form .= '
						<option value="' . sprintf( '%02d', $i ) . '"' . ( ( $i == (int) $expmm ) ? ' selected="selected"' : '' ) . '>' . sprintf( '%2d', $i ) . '</option>';
		}
		$form .= '
					</select>' . __( 'month', 'usces' ) . '&nbsp;
					<select class="expire_year" id="expire_year">
						<option value=""' . ( empty( $expyy ) ? ' selected="selected"' : '' ) . '>--</option>';
		for ( $i = 0; $i < 15; $i++ ) {
			$year     = date_i18n( 'Y' ) + $i;
			$year     = substr( $year, -2 );
			$selected = ( $year == $expyy ) ? ' selected="selected"' : '';
			$form    .= '
						<option value="' . $year . '"' . $selected . '>' . $year . '</option>';
		}
		$form .= '
					</select>' . __( 'year', 'usces' ) . '
					</td>
				</tr>';
		if ( 'on' == $acting_opts['use_card_conf_number'] ) {
			$seccd_attention = apply_filters( 'usces_filter_seccd_attention', __( '(Single-byte numbers only)', 'usces' ) );
			$form           .= '
				<tr>
					<th scope="row">' . __( 'security code', 'usces' ) . '</th>
					<td colspan="2"><input type="tel" class="cvc" id="cvc" maxlength="4" value="">' . $seccd_attention . '</td>
				</tr>';
		}
		$form .= '
			</table>';

		if ( '' != $done_message ) {
			$script .= '
<script type="text/javascript">
jQuery.event.add( window, "load", function() {
	alert( "' . $done_message . '" );
});
</script>';
		}
		$error_message = apply_filters( 'usces_filter_member_update_settlement_error_message', $usces->error_message );

		ob_start();
		get_header();
		if ( '' != $script ) {
			echo $script; // no escape due to script.
		}
		?>
<div id="content" class="two-column">
<div class="catbox">
		<?php
		if ( have_posts() ) :
			usces_remove_filter();
			?>
<div class="post" id="wc_member_update_settlement">
			<?php if ( $register ) : ?>
<h1 class="member_page_title"><?php esc_html_e( 'Credit card registration', 'usces' ); ?></h1>
			<?php else : ?>
<h1 class="member_page_title"><?php esc_html_e( 'Credit card update', 'usces' ); ?></h1>
			<?php endif; ?>
<div class="entry">
<div id="memberpages">
<div class="whitebox">
	<div id="memberinfo">
	<div class="header_explanation"></div>
	<div class="error_message"><?php wel_esc_script_e( $error_message ); ?></div>
	<form id="member-card-info" name="member_update_settlement" action="<?php echo esc_url( $update_settlement_url ); ?>" method="post" onKeyDown="if(event.keyCode == 13) {return false;}">
			<?php wel_esc_script_e( $form ); ?>
		<div class="send">
			<input type="hidden" name="update" value="" />
			<input type="hidden" name="token" id="token" value="" />
			<input type="hidden" name="masked_card_number" id="masked_card_number" value="" />
			<input type="hidden" name="valid_until" id="valid_until" value="" />
			<input type="hidden" name="fingerprint" id="fingerprint" value="" />
			<input type="hidden" name="hc" id="hc" value="" />
			<?php if ( $register ) : ?>
			<input type="button" id="card-register" class="card-update" data-update_mode="register" value="<?php esc_attr_e( 'Register', 'usces' ); ?>" />
			<?php else : ?>
			<input type="button" id="card-update" class="card-update" data-update_mode="update" value="<?php esc_attr_e( 'Update', 'usces' ); ?>" />
				<?php if ( ! usces_have_member_continue_order( $member['ID'] ) && ! usces_have_member_regular_order( $member['ID'] ) ) : ?>
			<input type="button" id="card-delete" class="card-delete" data-update_mode="delete" value="<?php esc_attr_e( 'Delete', 'usces' ); ?>" />
				<?php endif; ?>
			<?php endif; ?>
			<input type="button" name="back" value="<?php esc_attr_e( 'Back to the member page.', 'usces' ); ?>" onclick="location.href='<?php echo esc_url( USCES_MEMBER_URL ); ?>'" />
			<input type="button" name="top" value="<?php esc_attr_e( 'Back to the top page.', 'usces' ); ?>" onclick="location.href='<?php echo esc_url( home_url() ); ?>'" />
		</div>
			<?php wp_nonce_field( 'member_update_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="footer_explanation"></div>
	</div><!-- end of memberinfo -->
</div><!-- end of whitebox -->
</div><!-- end of memberpages -->
</div><!-- end of entry -->
</div><!-- end of post -->
		<?php else : ?>
<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'usces' ); ?></p>
		<?php endif; ?>
</div><!-- end of catbox -->
</div><!-- end of content -->
		<?php
		$sidebar = apply_filters( 'usces_filter_member_update_settlement_page_sidebar', 'cartmember' );
		if ( ! empty( $sidebar ) ) {
			get_sidebar( $sidebar );
		}
		get_footer();
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents; // no escape.
	}

	/**
	 * クレジットカード変更メール
	 */
	public function send_update_settlement_mail() {
		global $usces;

		$member = $usces->get_member();

		$subject     = apply_filters( 'usces_filter_send_update_settlement_mail_subject', __( 'Confirmation of credit card update', 'usces' ), $member );
		$mail_header = __( 'Your credit card information has been updated on the membership page.', 'usces' ) . "\r\n\r\n";
		$mail_footer = get_option( 'blogname' ) . "\r\n";
		$name        = usces_localized_name( $member['name1'], $member['name2'], 'return' );

		$message  = '--------------------------------' . "\r\n";
		$message .= __( 'Member ID', 'usces' ) . ' : ' . $member['ID'] . "\r\n";
		$message .= __( 'Name', 'usces' ) . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), $name ) . "\r\n";
		$message .= __( 'e-mail adress', 'usces' ) . ' : ' . $member['mailaddress1'] . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message .= __( 'If you have not requested this email, sorry to trouble you, but please contact us.', 'usces' ) . "\r\n\r\n";
		$message  = apply_filters( 'usces_filter_send_update_settlement_mail_message', $message, $member );
		$message  = apply_filters( 'usces_filter_send_update_settlement_mail_message_head', $mail_header, $member ) . $message . apply_filters( 'usces_filter_send_update_settlement_mail_message_foot', $mail_footer, $member ) . "\r\n";
		$message  = sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n" . $message;

		$send_para = array(
			'to_name'      => sprintf( _x( '%s', 'honorific', 'usces' ), $name ),
			'to_address'   => $member['mailaddress1'],
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		usces_send_mail( $send_para );

		$admin_message  = $mail_header;
		$admin_message .= '--------------------------------' . "\r\n";
		$admin_message .= __( 'Member ID', 'usces' ) . ' : ' . $member['ID'] . "\r\n";
		$admin_message .= __( 'Name', 'usces' ) . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), $name ) . "\r\n";
		$admin_message .= __( 'e-mail adress', 'usces' ) . ' : ' . $member['mailaddress1'] . "\r\n";
		$admin_message .= '--------------------------------' . "\r\n\r\n";
		if ( usces_have_member_continue_order( $member['ID'] ) ) {
			$admin_message .= $this->message_continue_order( $member['ID'] );
		}
		if ( usces_have_member_regular_order( $member['ID'] ) ) {
			$admin_message .= $this->message_regular_order( $member['ID'] );
		}
		$admin_message .=
			"\r\n----------------------------------------------------\r\n" .
			'REMOTE_ADDR : ' . $_SERVER['REMOTE_ADDR'] .
			"\r\n----------------------------------------------------\r\n";

		$admin_para = array(
			'to_name'      => apply_filters( 'usces_filter_bccmail_to_admin_name', 'Shop Admin' ),
			'to_address'   => $usces->options['order_mail'],
			'from_name'    => apply_filters( 'usces_filter_bccmail_from_admin_name', 'Welcart Auto BCC' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject . '( ' . sprintf( _x( '%s', 'honorific', 'usces' ), $name ) . ' )',
			'message'      => do_shortcode( $admin_message ),
		);
		usces_send_mail( $admin_para );
	}

	/**
	 * 契約中の自動継続課金情報
	 *
	 * @param  int $member_id Member ID.
	 */
	public function message_continue_order( $member_id ) {
		global $usces, $wpdb;
		$message = '';

		$query          = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_continuation WHERE `con_member_id` = %d AND `con_acting` = %s AND `con_status` = 'continuation'", $member_id, 'acting_paygent_card' );
		$continue_order = $wpdb->get_results( $query, ARRAY_A );
		if ( 0 < count( $continue_order ) ) {
			$message .= '--------------------------------' . "\r\n";
			$message .= __( 'Auto-continuation charging Information under Contract with a credit card', 'usces' ) . "\r\n";
			foreach ( $continue_order as $continue_data ) {
				$con_id       = $continue_data['con_id'];
				$con_order_id = $continue_data['con_order_id'];
				$message     .= __( 'Order number', 'usces' ) . ' : ' . $con_order_id;
				$latest_log   = $this->get_acting_latest_log( $con_order_id, 0, RESULT_STATUS_ALL );
				if ( ! empty( $latest_log ) ) {
					$next_charging = ( empty( $continue_data['con_next_contracting'] ) ) ? dlseller_next_charging( $con_order_id ) : $continue_data['con_next_contracting'];
					$message      .= ' ( ' . __( 'Next Withdrawal Date', 'dlseller' ) . ' : ' . date( __( 'Y/m/d' ), strtotime( $next_charging ) );
					if ( 0 < (int) $continue_data['con_interval'] ) {
						$next_contracting = ( empty( $continue_data['con_next_contracting'] ) ) ? dlseller_next_contracting( $con_order_id ) : $continue_data['con_next_contracting'];
						$message         .= ', ' . __( 'Renewal Date', 'dlseller' ) . ' : ' . date( __( 'Y/m/d' ), strtotime( $next_contracting ) );
					}
					$message .= ' )';
					if ( isset( $latest_log['result'] ) && RESULT_STATUS_NORMAL != $latest_log['result'] ) {
						$message .= ' ' . __( 'Condition', 'dlseller' ) . ' : ' . __( 'Settlement error', 'usces' );
						if ( ! empty( $latest_log['trading_id'] ) ) {
							$message .= ' ( ' . __( 'Transaction ID', 'usces' ) . ' : ' . $latest_log['trading_id'] . ' )';
						}
					}
				}
				$message .= "\r\n";
			}
			$message .= '--------------------------------' . "\r\n\r\n";
		}
		return $message;
	}

	/**
	 * 契約中の定期購入情報
	 *
	 * @param  int $member_id Member ID.
	 */
	public function message_regular_order( $member_id ) {
		global $usces, $wpdb;
		$message = '';

		$query_order   = $wpdb->prepare(
			"SELECT r.reg_id, r.reg_payment_name, d.regdet_schedule_date 
				FROM {$wpdb->prefix}usces_regular_detail AS `d` 
				RIGHT JOIN {$wpdb->prefix}usces_regular AS `r` ON r.reg_id = d.reg_id 
				WHERE r.reg_mem_id = %d AND d.regdet_condition = 'continuation' 
				GROUP BY r.reg_id",
			$member_id
		);
		$regular_order = $wpdb->get_results( $query_order, ARRAY_A );
		if ( 0 < count( $regular_order ) ) {
			foreach ( $regular_order as $regular_data ) {
				$payment = $usces->getPayments( $regular_data['reg_payment_name'] );
				if ( isset( $payment['settlement'] ) && 'acting_paygent_card' != $payment['settlement'] ) {
					continue;
				}
				$reg_id             = $regular_data['reg_id'];
				$query              = $wpdb->prepare(
					"SELECT o.ID AS `order_id`, meta.meta_value AS `deco_id`, DATE_FORMAT( o.order_date, %s ) AS `order_date` 
						FROM {$wpdb->prefix}usces_order AS `o` 
						LEFT JOIN {$wpdb->prefix}usces_order_meta AS `meta` ON o.ID = meta.order_id AND meta.meta_key = 'dec_order_id' 
						LEFT JOIN {$wpdb->prefix}usces_regular ON o.ID = `reg_order_id` 
						WHERE `reg_id` = %d 
					UNION ALL 
					SELECT o1.ID AS `order_id`, meta1.meta_value AS `deco_id`, DATE_FORMAT( o1.order_date, %s ) AS `order_date` 
						FROM {$wpdb->prefix}usces_order AS `o1` 
						LEFT JOIN {$wpdb->prefix}usces_order_meta AS `meta1` ON o1.ID = meta1.order_id AND meta1.meta_key = 'dec_order_id' 
						LEFT JOIN {$wpdb->prefix}usces_order_meta AS `meta2` ON o1.ID = meta2.order_id AND meta2.meta_key = 'regular_id' 
						WHERE meta2.meta_value = %d 
					ORDER BY `order_id` DESC, `order_date` DESC",
					'%Y-%m-%d',
					$reg_id,
					'%Y-%m-%d',
					$reg_id
				);
				$regular_order_data = $wpdb->get_results( $query, ARRAY_A );
				if ( 0 < count( $regular_order_data ) ) {
					$message     .= __( 'Regular ID', 'autodelivery' ) . ' : ' . $reg_id;
					$reg_order_id = $regular_order_data[0]['order_id'];
					$latest_log   = $this->get_acting_latest_log( $reg_order_id, 0, RESULT_STATUS_ALL );
					if ( isset( $latest_log['result'] ) && RESULT_STATUS_NORMAL != $latest_log['result'] ) {
						$message   .= ' ' . __( 'Condition', 'autodelivery' ) . ' : ' . __( 'Settlement error', 'usces' );
						$trading_id = $usces->get_order_meta_value( 'trading_id', $reg_order_id );
						if ( $trading_id ) {
							$message .= ' ( ' . __( 'Transaction ID', 'usces' ) . ' : ' . $trading_id . ' )';
						}
					} else {
						if ( $this->isdate( $regular_data['regdet_schedule_date'] ) ) {
							$message .= ' ( ' . __( 'Scheduled order date', 'autodelivery' ) . ' : ' . date( __( 'Y/m/d' ), strtotime( $regular_data['regdet_schedule_date'] ) ) . ' )';
						}
					}
					$message .= "\r\n";
				}
			}
			if ( '' != $message ) {
				$message = '--------------------------------' . "\r\n"
					. __( 'Subscription Information under Contract with a credit card', 'usces' ) . "\r\n"
					. $message
					. '--------------------------------' . "\r\n\r\n";
			}
		}
		return $message;
	}

	/**
	 * 日付チェック
	 *
	 * @param  object $date DateTime.
	 * @return boolean
	 */
	private function isdate( $date ) {
		if ( empty( $date ) ) {
			return false;
		}
		try {
			new DateTime( $date );
			list( $year, $month, $day ) = explode( '-', $date );
			$res                        = checkdate( (int) $month, (int) $day, (int) $year );
			return $res;
		} catch ( Exception $e ) {
			return false;
		}
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
			if ( isset( $acting_opts['card_activate'] ) && 'off' != $acting_opts['card_activate'] && 'on' == $acting_opts['stock_card_mode'] ) :
				$customer = $this->customer_ref( $member['ID'] );
				if ( ! empty( $customer ) && isset( $customer['card_number'] ) && isset( $customer['card_valid_term'] ) ) :
					$cardlast4 = substr( $customer['card_number'], -4 );
					$expyy     = substr( $customer['card_valid_term'], 2, 2 );
					$expmm     = substr( $customer['card_valid_term'], 0, 2 );
					?>
		<tr>
			<td class="label"><?php esc_html_e( 'Lower 4 digits', 'usces' ); ?></td>
			<td><div class="rod_left shortm"><?php echo esc_html( $cardlast4 ); ?></div></td>
		</tr>
		<tr>
			<td class="label"><?php esc_html_e( 'Expiration date', 'usces' ); ?></td>
			<td><div class="rod_left shortm"><?php echo esc_html( $expmm . '/' . $expyy ); ?></div></td>
		</tr>
		<tr>
			<td class="label">カード情報</td>
			<td><div class="rod_left shortm"><?php esc_html_e( 'Registered', 'usces' ); ?></div></td>
		</tr>
		<tr>
			<td class="label"><input type="checkbox" name="paygent_stock_card_mode" id="paygent-stock_card_mode-release" value="release"></td>
			<td><label for="paygent-stock_card_mode-release">カード情報の登録を解除する</label></td>
		</tr>
					<?php
				endif;
			endif;
		endif;
	}

	/**
	 * 会員データ編集画面 カード情報登録解除
	 * usces_action_post_update_memberdata
	 *
	 * @param int     $member_id Member ID.
	 * @param boolean $res Result.
	 */
	public function admin_update_memberdata( $member_id, $res ) {
		if ( ! $this->is_activate_card() || false === $res ) {
			return;
		}
		if ( isset( $_POST['paygent_stock_card_mode'] ) && 'release' == $_POST['paygent_stock_card_mode'] ) {
			$this->customer_card_del( $member_id );
		}
	}

	/**
	 * 利用可能な支払方法（継続課金・定期購入）
	 * dlseller_filter_the_payment_method_restriction
	 * wcad_filter_the_payment_method_restriction
	 *
	 * @param  array  $payments_restriction Payment method.
	 * @param  string $value Input value.
	 * @return array
	 */
	public function payment_method_restriction( $payments_restriction, $value ) {
		if ( ( usces_have_regular_order() || usces_have_continue_charge() ) && usces_is_login() ) {
			$paygent_card = false;
			foreach ( (array) $payments_restriction as $key => $payment ) {
				if ( isset( $payment['settlement'] ) && 'acting_paygent_card' == $payment['settlement'] ) {
					$paygent_card = true;
				}
			}
			if ( ! $paygent_card ) {
				$payments               = usces_get_system_option( 'usces_payment_method', 'settlement' );
				$payments_restriction[] = $payments['acting_paygent_card'];
			}
			$sort = array();
			foreach ( (array) $payments_restriction as $key => $payment ) {
				$sort[ $key ] = $payment['sort'];
			}
			array_multisort( $sort, SORT_ASC, $payments_restriction );
		}
		return $payments_restriction;
	}

	/**
	 * 利用可能な支払方法
	 * usces_filter_the_continue_payment_method
	 *
	 * @param  array $payment_method Payment method.
	 * @return array
	 */
	public function continuation_payment_method( $payment_method ) {
		if ( ! array_key_exists( 'acting_paygent_card', $payment_method ) ) {
			$payment_method[] = 'acting_paygent_card';
		}
		return $payment_method;
	}

	/**
	 * 「初回引落し日」
	 * dlseller_filter_first_charging
	 *
	 * @param  object $time Datetime.
	 * @param  int    $post_id Post ID.
	 * @param  array  $usces_item Item data.
	 * @param  int    $order_id Order number.
	 * @param  array  $continue_data Continuation data.
	 * @return object
	 */
	public function first_charging_date( $time, $post_id, $usces_item, $order_id, $continue_data ) {
		if ( 99 == $usces_item['item_chargingday'] ) {
			if ( empty( $order_id ) ) {
				$today                      = date_i18n( 'Y-m-d', current_time( 'timestamp' ) );
				list( $year, $month, $day ) = explode( '-', $today );
				$time                       = mktime( 0, 0, 0, (int) $month, (int) $day, (int) $year );
			}
		}
		return $time;
	}

	/**
	 * 継続課金会員リスト「状態」
	 * dlseller_filter_continue_member_list_condition
	 *
	 * @param  string $condition Continuation condition.
	 * @param  int    $member_id Member ID.
	 * @param  int    $order_id Order number.
	 * @param  array  $continue_data Continuation data.
	 * @return string
	 */
	public function continue_member_list_condition( $condition, $member_id, $order_id, $continue_data ) {
		global $usces;

		if ( isset( $continue_data['acting'] ) && 'acting_paygent_card' == $continue_data['acting'] ) {
			$url       = admin_url( 'admin.php?page=usces_continue&continue_action=settlement_paygent_card&member_id=' . esc_attr( $member_id ) . '&order_id=' . esc_attr( $order_id ) );
			$condition = '<a href="' . $url . '">' . __( 'Detail', 'usces' ) . '</a>';
			if ( 'continuation' == $continue_data['status'] ) {
				$latest_log = $this->get_acting_latest_log( $order_id, 0, RESULT_STATUS_ALL );
				if ( isset( $latest_log['result'] ) && RESULT_STATUS_NORMAL != $latest_log['result'] ) {
					$condition .= '<div class="acting-status paygent-error">' . __( 'Settlement error', 'usces' ) . '</div>';
				}
			}
		}
		return $condition;
	}

	/**
	 * 継続課金会員決済状況ページ表示
	 * dlseller_action_continue_member_list_page
	 *
	 * @param  string $continue_action Continuation action.
	 */
	public function continue_member_list_page( $continue_action ) {
		if ( 'settlement_paygent_card' == $continue_action ) {
			$member_id = ( isset( $_GET['member_id'] ) ) ? wp_unslash( $_GET['member_id'] ) : '';
			$order_id  = ( isset( $_GET['order_id'] ) ) ? wp_unslash( $_GET['order_id'] ) : '';
			if ( ! empty( $member_id ) && ! empty( $order_id ) ) {
				$this->continue_member_settlement_info_page( $member_id, $order_id );
				exit();
			}
		}
	}

	/**
	 * 継続課金会員決済詳細ページ
	 *
	 * @param  int $member_id Member ID.
	 * @param  int $order_id Order number.
	 */
	public function continue_member_settlement_info_page( $member_id, $order_id ) {
		global $usces;

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		if ( ! $order_data ) {
			return;
		}

		$payment = $usces->getPayments( $order_data['order_payment_name'] );
		if ( isset( $payment['settlement'] ) && 'acting_paygent_card' != $payment['settlement'] ) {
			return;
		}

		$continue_data = $this->get_continuation_data( $member_id, $order_id );
		if ( 'acting_paygent_card' != $continue_data['acting'] ) {
			return;
		}

		$con_id     = $continue_data['con_id'];
		$curent_url = esc_url( $_SERVER['REQUEST_URI'] );

		$member_info = $usces->get_member_info( $member_id );
		$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );

		$contracted_date = ( empty( $continue_data['contractedday'] ) ) ? dlseller_next_contracting( $order_id ) : $continue_data['contractedday'];
		if ( ! empty( $contracted_date ) ) {
			list( $contracted_year, $contracted_month, $contracted_day ) = explode( '-', $contracted_date );
		} else {
			$contracted_year  = 0;
			$contracted_month = 0;
			$contracted_day   = 0;
		}
		$charged_date = ( empty( $continue_data['chargedday'] ) ) ? dlseller_next_charging( $order_id ) : $continue_data['chargedday'];
		if ( ! empty( $charged_date ) ) {
			list( $charged_year, $charged_month, $charged_day ) = explode( '-', $charged_date );
		} else {
			$charged_year  = 0;
			$charged_month = 0;
			$charged_day   = 0;
		}
		$this_year = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 4 );

		$log_data = $this->get_acting_log( $order_id, 0, RESULT_STATUS_ALL );
		$num      = ( $log_data ) ? count( $log_data ) : 1;
		?>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Management <?php esc_html_e( 'Continuation charging member information', 'dlseller' ); ?></h1>
<p class="version_info">Version <?php echo esc_html( WCEX_DLSELLER_VERSION ); ?></p>
		<?php usces_admin_action_status(); ?>
<div class="edit_pagenav"><a href="<?php echo esc_url( $_SERVER['HTTP_REFERER'] ); ?>" class="back-list"><span class="dashicons dashicons-list-view"></span><?php esc_html_e( 'Back to the continue members list', 'dlseller' ); ?></a></div>
<div id="datatable">
<div id="tablesearch" class="usces_tablesearch">
<div id="searchBox" style="display:block">
	<table class="search_table">
	<tr>
		<td class="label"><?php esc_html_e( 'Continuation charging information', 'dlseller' ); ?></td>
		<td>
			<table class="order_info">
			<tr>
				<th><?php esc_html_e( 'Member ID', 'dlseller' ); ?></th>
				<td><?php echo esc_html( $member_id ); ?></td>
				<th><?php esc_html_e( 'Contractor name', 'dlseller' ); ?></th>
				<td><?php echo esc_html( $name ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Order ID', 'dlseller' ); ?></th>
				<td><?php echo esc_html( $order_id ); ?></td>
				<th><?php esc_html_e( 'Application Date', 'dlseller' ); ?></th>
				<td><?php echo esc_html( $order_data['order_date'] ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Renewal Date', 'dlseller' ); ?></th>
				<td>
				<?php
				echo '<select id="contracted-year">';
				if ( 0 == (int) $contracted_year ) {
					echo '<option value="0" selected="selected"></option>';
				} else {
					echo '<option value="0"></option>';
				}
				for ( $i = 0; $i <= 10; $i++ ) {
					$year = (int) $this_year + $i;
					if ( (int) $contracted_year == $year ) {
						echo "<option value=\"{$year}\" selected=\"selected\">{$year}</option>";
					} else {
						echo "<option value=\"{$year}\">{$year}</option>";
					}
				}
				echo '</select>-<select id="contracted-month">';
				if ( 0 == (int) $contracted_month ) {
					echo '<option value="0" selected="selected"></option>';
				} else {
					echo '<option value="0"></option>';
				}
				for ( $i = 1; $i <= 12; $i++ ) {
					$month = sprintf( '%02d', $i );
					if ( (int) $contracted_month == $i ) {
						echo "<option value=\"{$month}\" selected=\"selected\">{$month}</option>";
					} else {
						echo "<option value=\"{$month}\">{$month}</option>";
					}
				}
				echo '</select>-<select id="contracted-day">';
				if ( 0 == (int) $contracted_day ) {
					echo '<option value="0" selected="selected"></option>';
				} else {
					echo '<option value="0"></option>';
				}
				for ( $i = 1; $i <= 31; $i++ ) {
					$day = sprintf( '%02d', $i );
					if ( (int) $contracted_day == $i ) {
						echo "<option value=\"{$day}\" selected=\"selected\">{$day}</option>";
					} else {
						echo "<option value=\"{$day}\">{$day}</option>";
					}
				}
				echo '</select>';
				?>
				</td>
				<th><?php _e( 'Next Withdrawal Date', 'dlseller' ); ?></th>
				<td>
				<?php
				echo '<select id="charged-year">';
				if ( 0 == (int) $charged_year ) {
					echo '<option value="0" selected="selected"></option>';
				} else {
					echo '<option value="0"></option>';
				}
				if ( $charged_year == $this_year ) {
					echo "<option value=\"{$this_year}\" selected=\"selected\">{$this_year}</option>";
				} else {
					echo "<option value=\"{$this_year}\">{$this_year}</option>";
				}
				$next_year = (int) $this_year + 1;
				if ( $charged_year == $next_year ) {
					echo "<option value=\"{$next_year}\" selected=\"selected\">{$next_year}</option>";
				} else {
					echo "<option value=\"{$next_year}\">{$next_year}</option>";
				}
				echo '</select>-<select id="charged-month">';
				if ( 0 == $charged_month ) {
					echo '<option value="0" selected="selected"></option>';
				} else {
					echo '<option value="0"></option>';
				}
				for ( $i = 1; $i <= 12; $i++ ) {
					$month = sprintf( '%02d', $i );
					if ( (int) $charged_month == $i ) {
						echo "<option value=\"{$month}\" selected=\"selected\">{$month}</option>";
					} else {
						echo "<option value=\"{$month}\">{$month}</option>";
					}
				}
				echo '</select>-<select id="charged-day">';
				if ( 0 == $charged_day ) {
					echo '<option value="0" selected="selected"></option>';
				} else {
					echo '<option value="0"></option>';
				}
				for ( $i = 1; $i <= 31; $i++ ) {
					$day = sprintf( '%02d', $i );
					if ( (int) $charged_day == $i ) {
						echo "<option value=\"{$day}\" selected=\"selected\">{$day}</option>";
					} else {
						echo "<option value=\"{$day}\">{$day}</option>";
					}
				}
				echo '</select>';
				?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Amount on order', 'usces' ); ?></th>
				<td><?php usces_crform( $continue_data['order_price'], false ); ?></td>
				<th><?php esc_html_e( 'Transaction amount', 'usces' ); ?></th>
				<td><input type="text" class="amount" id="price" style="text-align: right;" value="<?php usces_crform( $continue_data['price'], false, false, '', false ); ?>"><?php usces_crcode(); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Status', 'dlseller' ); ?></th>
				<td><select id="dlseller-status">
				<?php ob_start(); ?>
				<?php if ( 'continuation' == $continue_data['status'] ) : ?>
					<option value="continuation" selected="selected"><?php esc_html_e( 'Continuation', 'dlseller' ); ?></option>
					<option value="cancellation"><?php esc_html_e( 'Stop', 'dlseller' ); ?></option>
				<?php else : ?>
					<option value="cancellation" selected="selected"><?php esc_html_e( 'Cancellation', 'dlseller' ); ?></option>
					<option value="continuation"><?php esc_html_e( 'Resumption', 'dlseller' ); ?></option>
				<?php endif; ?>
				<?php
					$dlseller_status_options = ob_get_contents();
					ob_end_clean();
					$dlseller_status_options = apply_filters( 'usces_filter_continuation_charging_status_options', $dlseller_status_options, $continue_data );
					wel_esc_script_e( $dlseller_status_options );
				?>
				</select></td>
				<td colspan="2"><input id="continuation-update" type="button" class="button button-primary" value="<?php _e( 'Update' ); ?>" /></td>
			</tr>
			</table>
			<?php do_action( 'usces_action_continuation_charging_information', $continue_data, $member_id, $order_id ); ?>
		</td>
	</tr>
	</table>
</div><!-- searchBox -->
</div><!-- tablesearch -->
<table id="mainDataTable" class="new-table order-new-table">
	<thead>
	<tr>
		<th scope="col">&nbsp;</th>
		<th scope="col"><?php esc_html_e( 'Processing date', 'usces' ); ?></th>
		<th scope="col"><?php esc_html_e( 'Transaction ID', 'usces' ); ?></th>
		<th scope="col"><?php esc_html_e( 'Settlement amount', 'usces' ); ?></th>
		<th scope="col"><?php esc_html_e( 'Processing classification', 'usces' ); ?></th>
		<th scope="col">&nbsp;</th>
	</tr>
	</thead>
		<?php
		$trading_id = '';
		foreach ( (array) $log_data as $data ) :
			if ( isset( $data['tracking_id'] ) ) :
				if ( $trading_id == $data['tracking_id'] ) :
					continue;
				endif;
				$trading_id = $data['tracking_id'];
			endif;
			$latest_log = $this->get_acting_latest_log( $order_id, $trading_id, RESULT_STATUS_ALL );
			if ( $latest_log ) :
				$payment_id     = ( isset( $latest_log['payment_id'] ) ) ? $latest_log['payment_id'] : '';
				$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
				if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) :
					$status      = 'reference_error';
					$class       = ' card-error';
					$status_name = $this->get_status_name( $status );
					$amount      = usces_crform( $latest_log['amount'], false, true, 'return', true );
				else :
					$payment_status = $settlement_ref['payment_status'];
					$status         = $this->get_status( $payment_status );
					$class          = ' card-' . $status;
					$status_name    = $this->get_status_name( $payment_status );
					$amount         = usces_crform( $settlement_ref['payment_amount'], false, true, 'return', true );
				endif;
				?>
	<tbody>
	<tr>
		<td><?php echo esc_html( $num ); ?></td>
		<td><?php echo esc_html( $data['datetime'] ); ?></td>
		<td><?php echo esc_html( $trading_id ); ?></td>
		<td class="amount"><?php echo esc_attr( $amount ); ?></td>
		<td><span id="settlement-status-<?php echo esc_attr( $num ); ?>"><span class="acting-status<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $status_name ); ?></span></span></td>
		<td>
			<input type="button" class="button settlement-information" id="settlement-information-<?php echo esc_attr( $trading_id ); ?>" data-trading_id="<?php echo esc_attr( $trading_id ); ?>" data-num="<?php echo esc_attr( $num ); ?>" value="<?php esc_attr_e( 'Settlement info', 'usces' ); ?>">
		</td>
	</tr>
	</tbody>
				<?php
				$num--;
			endif;
		endforeach;
		?>
</table>
</div><!--datatable-->
<input name="member_id" type="hidden" id="member_id" value="<?php echo esc_attr( $member_id ); ?>" />
<input name="order_id" type="hidden" id="order_id" value="<?php echo esc_attr( $order_id ); ?>" />
<input name="con_id" type="hidden" id="con_id" value="<?php echo esc_attr( $con_id ); ?>" />
<input name="usces_referer" type="hidden" id="usces_referer" value="<?php echo urlencode( $curent_url ); ?>" />
		<?php wp_nonce_field( 'order_edit', 'wc_nonce' ); ?>
</div><!--usces_admin-->
</div><!--wrap-->
		<?php
		$order_action = 'edit';
		$cart         = array();
		$action_args  = compact( 'order_action', 'order_id', 'cart' );
		$this->settlement_dialog( $order_data, $action_args );
		include ABSPATH . 'wp-admin/admin-footer.php';
	}

	/**
	 * 自動継続課金処理
	 * dlseller_action_do_continuation_charging
	 *
	 * @param  string $today Today.
	 * @param  int    $member_id Member ID.
	 * @param  int    $order_id Order number.
	 * @param  array  $continue_data Continuation data.
	 */
	public function auto_continuation_charging( $today, $member_id, $order_id, $continue_data ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( ! usces_is_membersystem_state() || 'on' != $acting_opts['stock_card_mode'] ) {
			return;
		}

		if ( 0 >= $continue_data['price'] ) {
			return;
		}

		if ( 'acting_paygent_card' != $continue_data['acting'] ) {
			return;
		}

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		if ( ! $order_data || $usces->is_status( 'cancel', $order_data['order_status'] ) ) {
			return;
		}

		$trading_id = usces_acting_key();
		$customer   = $this->customer_ref( $member_id );
		if ( isset( $customer['customer_id'] ) && $customer['customer_id'] == $member_id && isset( $customer['customer_card_id'] ) ) {
			$telegram_kind = PAYGENT_CREDIT;
			$amount        = usces_crform( $continue_data['price'], false, false, 'return', false );

			$pm = Paygent_Module::get_instance();
			$pm->init();
			$pm->req_put( 'trading_id', $trading_id );
			$pm->req_put( 'payment_amount', $amount );
			$pm->req_put( 'payment_class', '10' );
			$pm->req_put( '3dsecure_ryaku', '1' );
			$pm->req_put( 'stock_card_mode', '1' );
			$pm->req_put( 'customer_id', $member_id );
			$pm->req_put( 'customer_card_id', $customer['customer_card_id'] );
			$pm->req_put( 'sales_mode', $acting_opts['sales_mode_dlseller'] );

			$result_post   = $pm->post( $telegram_kind );
			$result_status = $pm->get_result_status();
			if ( ! ( true === $result_post ) || RESULT_STATUS_NORMAL != $result_status ) {
				$response_data = array(
					'result'          => $result_post,
					'result_status'   => $result_status,
					'response_code'   => $pm->get_response_code(),
					'response_detail' => $pm->get_response_detail(),
					'result_message'  => $pm->get_result_message(),
				);
				$response_code = ( true !== $result_post ) ? $result_post : $pm->get_response_code();
				$log           = array(
					'acting' => 'paygent_card',
					'key'    => $trading_id,
					'result' => $response_code,
					'data'   => $response_data,
				);
				usces_save_order_acting_error( $log );
				$this->save_acting_log( $response_data, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
				$this->auto_settlement_error_mail( $member_id, $order_id, $response_data, $continue_data );
				do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $response_data );
			} else {
				$response_data  = $pm->get_response_data();
				$payment_id     = ( isset( $response_data['payment_id'] ) ) ? $response_data['payment_id'] : '';
				$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
				if ( isset( $settlement_ref['result_status'] ) && RESULT_STATUS_ERROR == $settlement_ref['result_status'] ) {
					$this->save_acting_log( $settlement_ref, 'paygent_card', 'reference_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
				} else {
					$this->save_acting_log( $settlement_ref, 'paygent_card', $settlement_ref['payment_status'], RESULT_STATUS_NORMAL, $order_id, $trading_id );
				}
				$this->auto_settlement_mail( $member_id, $order_id, $settlement_ref, $continue_data );
				do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $settlement_ref );
			}
		} else {
			$result = ( isset( $customer['result'] ) ) ? $customer['result'] : 'error';
			$log    = array(
				'acting' => 'paygent_card(member_process)',
				'key'    => $member_id,
				'result' => $result,
				'data'   => $customer,
			);
			usces_save_order_acting_error( $log );
			if ( ! isset( $customer['payment_type'] ) ) {
				$customer['payment_type'] = PAYMENT_TYPE_CREDIT;
			}
			$this->save_acting_log( $customer, 'paygent_card', 'customer_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
			$this->auto_settlement_error_mail( $member_id, $order_id, $customer, $continue_data );
			do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $customer );
		}
	}

	/**
	 * 自動継続課金処理メール（正常）
	 *
	 * @param  int   $member_id Member ID.
	 * @param  int   $order_id Order number.
	 * @param  array $data Response data.
	 * @param  array $continue_data Continuation data.
	 */
	public function auto_settlement_mail( $member_id, $order_id, $data, $continue_data ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$order_data  = $usces->get_order_data( $order_id, 'direct' );
		$mail_body   = $this->auto_settlement_message( $member_id, $order_id, $order_data, $data, $continue_data, false );

		if ( 'on' == $acting_opts['auto_settlement_mail'] ) {
			$subject     = apply_filters( 'usces_filter_paygent_auto_settlement_mail_subject', __( 'Announcement of automatic continuing charging process', 'usces' ), $member_id, $order_id, $order_data, $data, $continue_data );
			$member_info = $usces->get_member_info( $member_id );
			$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$mail_data   = usces_mail_data();
			$mail_header = '';
			if ( isset( $usces->options['put_customer_name'] ) && 1 === (int) $usces->options['put_customer_name'] ) {
				$mail_header .= sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n";
			}
			$mail_header .= __( 'We will report automated accounting process was carried out as follows.', 'usces' ) . "\r\n\r\n";
			$mail_footer  = __( 'If you have any questions, please contact us.', 'usces' ) . "\r\n\r\n" . $mail_data['footer']['thankyou'];
			$message      = apply_filters( 'usces_filter_paygent_auto_settlement_mail_header', $mail_header, $member_id, $order_id, $order_data, $data, $continue_data ) .
				apply_filters( 'usces_filter_paygent_auto_settlement_mail_body', $mail_body, $member_id, $order_id, $order_data, $data, $continue_data ) .
				apply_filters( 'usces_filter_paygent_auto_settlement_mail_footer', $mail_footer, $member_id, $order_id, $order_data, $data, $continue_data );
			$headers      = apply_filters( 'usces_filter_paygent_auto_settlement_mail_headers', '' );
			$to_customer  = array(
				'to_name'      => sprintf( _x( '%s', 'honorific', 'usces' ), $name ),
				'to_address'   => $member_info['mem_email'],
				'from_name'    => get_option( 'blogname' ),
				'from_address' => $usces->options['sender_mail'],
				'return_path'  => $usces->options['sender_mail'],
				'subject'      => $subject,
				'message'      => do_shortcode( $message ),
				'headers'      => $headers,
			);
			usces_send_mail( $to_customer );
		}

		$ok                                         = ( empty( $this->continuation_charging_mail['OK'] ) ) ? 0 : $this->continuation_charging_mail['OK'];
		$this->continuation_charging_mail['OK']     = $ok + 1;
		$this->continuation_charging_mail['mail'][] = $mail_body;
	}

	/**
	 * 自動継続課金処理メール（エラー）
	 *
	 * @param  int   $member_id Member ID.
	 * @param  int   $order_id Order number.
	 * @param  array $data Response data.
	 * @param  array $continue_data Continuation data.
	 */
	public function auto_settlement_error_mail( $member_id, $order_id, $data, $continue_data ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$order_data  = $usces->get_order_data( $order_id, 'direct' );
		$mail_body   = $this->auto_settlement_message( $member_id, $order_id, $order_data, $data, $continue_data, false );

		if ( 'on' == $acting_opts['auto_settlement_mail'] ) {
			$subject     = apply_filters( 'usces_filter_paygent_auto_settlement_error_mail_subject', __( 'Announcement of automatic continuing charging process', 'usces' ), $member_id, $order_id, $order_data, $data, $continue_data );
			$member_info = $usces->get_member_info( $member_id );
			$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$mail_data   = usces_mail_data();
			$mail_header = '';
			if ( isset( $usces->options['put_customer_name'] ) && 1 === (int) $usces->options['put_customer_name'] ) {
				$mail_header .= sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n";
			}
			$mail_header .= __( 'We will reported that an error occurred in automated accounting process.', 'usces' ) . "\r\n\r\n";
			$mail_footer  = __( 'If you have any questions, please contact us.', 'usces' ) . "\r\n\r\n" . $mail_data['footer']['thankyou'];
			$message      = apply_filters( 'usces_filter_paygent_auto_settlement_error_mail_header', $mail_header, $member_id, $order_id, $order_data, $data, $continue_data ) .
				apply_filters( 'usces_filter_paygent_auto_settlement_error_mail_body', $mail_body, $member_id, $order_id, $order_data, $data, $continue_data ) .
				apply_filters( 'usces_filter_paygent_auto_settlement_error_mail_footer', $mail_footer, $member_id, $order_id, $order_data, $data, $continue_data );
			$headers      = apply_filters( 'usces_filter_paygent_auto_settlement_error_mail_headers', '' );
			$to_customer = array(
				'to_name'      => sprintf( _x( '%s', 'honorific', 'usces' ), $name ),
				'to_address'   => $member_info['mem_email'],
				'from_name'    => get_option( 'blogname' ),
				'from_address' => $usces->options['sender_mail'],
				'return_path'  => $usces->options['sender_mail'],
				'subject'      => $subject,
				'message'      => do_shortcode( $message ),
				'headers'      => $headers,
			);
			usces_send_mail( $to_customer );
		}

		$error                                      = ( empty( $this->continuation_charging_mail['NG'] ) ) ? 0 : $this->continuation_charging_mail['NG'];
		$this->continuation_charging_mail['NG']     = $error + 1;
		$this->continuation_charging_mail['mail'][] = $mail_body;
	}

	/**
	 * 自動継続課金処理メール本文
	 *
	 * @param  int     $member_id Member ID.
	 * @param  int     $order_id Order number.
	 * @param  array   $order_data Order data.
	 * @param  array   $data Response data.
	 * @param  array   $continue_data Continuation data.
	 * @param  boolean $html
	 * @return string
	 */
	public function auto_settlement_message( $member_id, $order_id, $order_data, $data, $continue_data, $html = true ) {
		global $usces;

		$member_info     = $usces->get_member_info( $member_id );
		$name            = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
		$contracted_date = ( isset( $continue_data['contractedday'] ) ) ? $continue_data['contractedday'] : '';
		$charged_date    = ( isset( $continue_data['chargedday'] ) ) ? $continue_data['chargedday'] : '';

		$cart = usces_get_ordercartdata( $order_id );
		if ( is_array( $cart ) && 0 < count( $cart ) ) {
			$cart_row  = current( $cart );
			$item_name = $usces->getCartItemName_byOrder( $cart_row );
			$options   = ( isset( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		}

		if ( usces_is_html_mail() && $html ) {
			$message  = '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;"><tbody>';
			$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
			$message .= __( 'Order ID', 'dlseller' );
			$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
			$message .= $order_id;
			$message .= '</td></tr>';

			$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
			$message .= __( 'Application Date', 'dlseller' );
			$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
			$message .= $order_data['order_date'];
			$message .= '</td></tr>';

			$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
			$message .= __( 'Member ID', 'dlseller' );
			$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
			$message .= $member_id;
			$message .= '</td></tr>';

			$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
			$message .= __( 'Contractor name', 'dlseller' );
			$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
			$message .= sprintf( _x( '%s', 'honorific', 'usces' ), $name );
			$message .= '</td></tr>';
			$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
			$message .= __( 'Items', 'usces' );
			$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
			$message .= $item_name;
			if ( is_array( $options ) && 0 < count( $options ) ) {
				$optstr = '';
				foreach ( $options as $key => $value ) {
					if ( ! empty( $key ) ) {
						$key   = urldecode( $key );
						$value = maybe_unserialize( $value );
						if ( is_array( $value ) ) {
							$c       = '';
							$optstr .= '( ' . $key . ' : ';
							foreach ( $value as $v ) {
								$optstr .= $c . rawurldecode( $v );
								$c       = ', ';
							}
							$optstr .= ' )<br>';
						} else {
							$optstr .= '( ' . $key . ' : ' . rawurldecode( $value ) . ' )<br>';
						}
					}
				}
				$message .= $optstr;
			}
			$message .= '</td></tr>';

			$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
			$message .= __( 'Settlement amount', 'usces' );
			$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
			$message .= usces_crform( $continue_data['price'], true, false, 'return' );
			$message .= '</td></tr>';
			if ( isset( $data['reminder'] ) ) {
				if ( ! empty( $charged_date ) ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Next Withdrawal Date', 'dlseller' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= $charged_date;
					$message .= '</td></tr>';
				}
				if ( ! empty( $contracted_date ) ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Renewal Date', 'dlseller' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= $contracted_date;
					$message .= '</td></tr>';
				}
			} else {
				if ( ! empty( $charged_date ) ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Next Withdrawal Date', 'dlseller' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= $charged_date;
					$message .= '</td></tr>';
				}
				if ( ! empty( $contracted_date ) ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Renewal Date', 'dlseller' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= $contracted_date;
					$message .= '</td></tr>';
				}

				if ( isset( $data['result_status'] ) && RESULT_STATUS_ERROR == $data['result_status'] ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Result', 'usces' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= __( 'Error', 'usces' );
					$message .= '</td></tr>';
				} else {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Result', 'usces' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= __( 'Normal done', 'usces' );
					$message .= '</td></tr>';
				}
			}
			$message .= '</tbody></table>';

		} else {
			$message  = usces_mail_line( 2 ); // --------------------
			$message .= __( 'Order ID', 'dlseller' ) . ' : ' . $order_id . "\r\n";
			$message .= __( 'Application Date', 'dlseller' ) . ' : ' . $order_data['order_date'] . "\r\n";
			$message .= __( 'Member ID', 'dlseller' ) . ' : ' . $member_id . "\r\n";
			$message .= __( 'Contractor name', 'dlseller' ) . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), $name ) . "\r\n";

			$message .= __( 'Items', 'usces' ) . ' : ' . $item_name . "\r\n";
			if ( is_array( $options ) && 0 < count( $options ) ) {
				$optstr = '';
				foreach ( $options as $key => $value ) {
					if ( ! empty( $key ) ) {
						$key   = urldecode( $key );
						$value = maybe_unserialize( $value );
						if ( is_array( $value ) ) {
							$c       = '';
							$optstr .= '( ' . $key . ' : ';
							foreach ( $value as $v ) {
								$optstr .= $c . rawurldecode( $v );
								$c       = ', ';
							}
							$optstr .= " )\r\n";
						} else {
							$optstr .= '( ' . $key . ' : ' . rawurldecode( $value ) . " )\r\n";
						}
					}
				}
				$message .= $optstr;
			}

			$message .= __( 'Settlement amount', 'usces' ) . ' : ' . usces_crform( $continue_data['price'], true, false, 'return' ) . "\r\n";
			if ( ! empty( $charged_date ) ) {
				$message .= __( 'Next Withdrawal Date', 'dlseller' ) . ' : ' . $charged_date . "\r\n";
			}
			if ( ! empty( $contracted_date ) ) {
				$message .= __( 'Renewal Date', 'dlseller' ) . ' : ' . $contracted_date . "\r\n";
			}
			if ( isset( $data['reminder'] ) ) {
			} else {
				$message .= "\r\n";
				if ( isset( $data['result_status'] ) && RESULT_STATUS_ERROR == $data['result_status'] ) {
					$message .= __( 'Result', 'usces' ) . ' : ' . __( 'Error', 'usces' ) . "\r\n";
					if ( isset( $data['res_err_code'] ) ) {
						$message .= 'res_err_code : ' . $data['res_err_code'] . "\r\n";
					}
				} else {
					$message .= __( 'Result', 'usces' ) . ' : ' . __( 'Normal done', 'usces' ) . "\r\n";
				}
			}
			$message .= usces_mail_line( 2 ) . "\r\n"; // --------------------
		}
		return $message;
	}

	/**
	 * 自動継続課金処理
	 * dlseller_action_do_continuation
	 *
	 * @param  string $today Today.
	 * @param  array  $todays_charging Charged data.
	 */
	public function do_auto_continuation( $today, $todays_charging ) {
		global $usces;

		if ( empty( $todays_charging ) ) {
			return;
		}

		$ok            = ( empty( $this->continuation_charging_mail['OK'] ) ) ? 0 : $this->continuation_charging_mail['OK'];
		$error         = ( empty( $this->continuation_charging_mail['NG'] ) ) ? 0 : $this->continuation_charging_mail['NG'];
		$admin_subject = apply_filters( 'usces_filter_paygent_autobilling_email_admin_subject', __( 'Automatic Continuing Charging Process Result', 'usces' ) . ' ' . $today, $today );
		$admin_footer  = apply_filters( 'usces_filter_paygent_autobilling_email_admin_mail_footer', __( 'For details, please check on the administration panel > Continuous charge member list > Continuous charge member information.', 'usces' ) );
		$admin_message = __( 'Report that automated accounting process has been completed.', 'usces' ) . "\r\n\r\n"
			. __( 'Processing date', 'usces' ) . ' : ' . date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) . "\r\n"
			. __( 'Normal done', 'usces' ) . ' : ' . $ok . "\r\n"
			. __( 'Abnormal done', 'usces' ) . ' : ' . $error . "\r\n\r\n";
		foreach ( (array) $this->continuation_charging_mail['mail'] as $mail ) {
			$admin_message .= $mail . "\r\n";
		}
		$admin_message .= $admin_footer . "\r\n";

		$to_admin = array(
			'to_name'      => apply_filters( 'usces_filter_bccmail_to_admin_name', 'Shop Admin' ),
			'to_address'   => $usces->options['order_mail'],
			'from_name'    => apply_filters( 'usces_filter_bccmail_from_admin_name', 'Welcart Auto BCC' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $admin_subject,
			'message'      => do_shortcode( $admin_message ),
		);
		usces_send_mail( $to_admin );
		unset( $this->continuation_charging_mail );
	}

	/**
	 * 課金日通知メール
	 * dlseller_filter_reminder_mail_body
	 *
	 * @param  string $mail_body Message body.
	 * @param  int    $order_id Order number.
	 * @param  array  $continue_data Continuation data.
	 * @return string
	 */
	public function reminder_mail_body( $mail_body, $order_id, $continue_data ) {
		global $usces;

		$member_id  = $continue_data['member_id'];
		$order_id   = $continue_data['order_id'];
		$order_data = $usces->get_order_data( $order_id, 'direct' );
		$data       = array( 'reminder' => 'reminder' );
		$mail_body  = $this->auto_settlement_message( $member_id, $order_id, $order_data, $data, $continue_data );
		return $mail_body;
	}

	/**
	 * 契約更新日通知メール
	 * dlseller_filter_contract_renewal_mail_body
	 *
	 * @param  string $mail_body Message body.
	 * @param  int    $order_id Order number.
	 * @param  array  $continue_data Continuation data.
	 * @return string
	 */
	public function contract_renewal_mail_body( $mail_body, $order_id, $continue_data ) {
		global $usces;

		$member_id  = $continue_data['member_id'];
		$order_data = $usces->get_order_data( $order_id, 'direct' );
		$data       = array( 'reminder' => 'contract_renewal' );
		$mail_body  = $this->auto_settlement_message( $member_id, $order_id, $order_data, $data, $continue_data );
		return $mail_body;
	}

	/**
	 * 継続課金会員データ取得
	 *
	 * @param  int $member_id Member ID.
	 * @param  int $order_id Order number.
	 * @return array
	 */
	private function get_continuation_data( $member_id, $order_id ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT 
			`con_id` AS `con_id`, 
			`con_acting` AS `acting`, 
			`con_order_price` AS `order_price`, 
			`con_price` AS `price`, 
			`con_next_charging` AS `chargedday`, 
			`con_next_contracting` AS `contractedday`, 
			`con_startdate` AS `startdate`, 
			`con_status` AS `status` 
			FROM {$wpdb->prefix}usces_continuation 
			WHERE `con_order_id` = %d AND `con_member_id` = %d",
			$order_id,
			$member_id
		);
		$data  = $wpdb->get_row( $query, ARRAY_A );
		return $data;
	}

	/**
	 * 継続課金会員データ更新
	 *
	 * @param  int     $member_id Member ID.
	 * @param  int     $order_id Order number.
	 * @param  array   $data Continuation data.
	 * @param  boolean $stop Stop continuous billing.
	 * @return boolean
	 */
	private function update_continuation_data( $member_id, $order_id, $data, $stop = false ) {
		global $wpdb;

		if ( $stop ) {
			$query = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}usces_continuation SET 
				`con_status` = 'cancellation' 
				WHERE `con_order_id` = %d AND `con_member_id` = %d",
				$order_id,
				$member_id
			);
		} else {
			$query = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}usces_continuation SET 
				`con_price` = %f, 
				`con_next_charging` = %s, 
				`con_next_contracting` = %s, 
				`con_status` = %s 
				WHERE `con_order_id` = %d AND `con_member_id` = %d",
				$data['price'],
				$data['chargedday'],
				$data['contractedday'],
				$data['status'],
				$order_id,
				$member_id
			);
		}
		$res = $wpdb->query( $query );
		return $res;
	}

	/**
	 * 管理画面メッセージ
	 * wcad_filter_admin_notices
	 *
	 * @param  string $msg Admin notice message.
	 * @return string
	 */
	public function admin_notices_autodelivery( $msg ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['card_activate'] ) && 'off' != $acting_opts['card_activate'] && 'on' == $acting_opts['stock_card_mode'] ) {
			$msg = '';
		} else {
			$msg = '<div class="error"><p>「クレジット決済設定」にて、ペイジェントのカード情報お預りモードを「利用する」に設定してください。</p></div>';
		}
		return $msg;
	}

	/**
	 * 発送先リスト利用可能決済
	 * wcad_filter_shippinglist_acting
	 *
	 * @param  string $acting Payment method.
	 * @return string
	 */
	public function set_shippinglist_acting( $acting ) {
		$acting = 'acting_paygent_card';
		return $acting;
	}

	/**
	 * 管理画面利用可能決済メッセージ
	 * wcad_filter_available_regular_payment_method
	 *
	 * @param  array $payment_method Payment method.
	 * @return array
	 */
	public function available_regular_payment_method( $payment_method ) {
		$payment_method[] = 'acting_paygent_card';
		return $payment_method;
	}

	/**
	 * 定期購入決済処理
	 * wcad_action_reg_auto_orderdata
	 *
	 * @param  array $args Compact array( $cart, $entry, $order_id, $member_id, $payments, $charging_type, $total_amount, $reg_id ).
	 */
	public function register_auto_orderdata( $args ) {
		global $usces;
		extract( $args );

		$acting_opts = $this->get_acting_settings();
		if ( ! usces_is_membersystem_state() || 'on' != $acting_opts['stock_card_mode'] ) {
			return;
		}

		if ( 0 >= $total_amount ) {
			return;
		}

		$acting_flg = ( isset( $payments['settlement'] ) ) ? $payments['settlement'] : '';
		if ( 'acting_paygent_card' != $acting_flg ) {
			return;
		}

		$settltment_errmsg = '';

		$trading_id    = usces_acting_key();
		$customer      = $this->customer_ref( $member_id );
		if ( isset( $customer['customer_id'] ) && $customer['customer_id'] == $member_id && isset( $customer['customer_card_id'] ) ) {
			$telegram_kind = PAYGENT_CREDIT;
			$amount        = usces_crform( $total_amount, false, false, 'return', false );

			$pm = Paygent_Module::get_instance();
			$pm->init();
			$pm->req_put( 'trading_id', $trading_id );
			$pm->req_put( 'payment_amount', $amount );
			$pm->req_put( 'payment_class', '10' );
			$pm->req_put( '3dsecure_ryaku', '1' );
			$pm->req_put( 'stock_card_mode', '1' );
			$pm->req_put( 'customer_id', $member_id );
			$pm->req_put( 'customer_card_id', $customer['customer_card_id'] );
			$pm->req_put( 'sales_mode', $acting_opts['sales_mode'] );

			$result_post   = $pm->post( $telegram_kind );
			$result_status = $pm->get_result_status();
			if ( ! ( true === $result_post ) || RESULT_STATUS_NORMAL != $result_status ) {
				$settltment_errmsg = __( '[Regular purchase] Settlement was not completed.', 'autodelivery' );
				$response_data     = array(
					'result'          => $result_post,
					'result_status'   => $result_status,
					'response_code'   => $pm->get_response_code(),
					'response_detail' => $pm->get_response_detail(),
					'result_message'  => $pm->get_result_message(),
				);
				$response_code     = ( true !== $result_post ) ? $result_post : $pm->get_response_code();
				$log               = array(
					'acting' => 'paygent_card',
					'key'    => $trading_id,
					'result' => $response_code,
					'data'   => $response_data,
				);
				usces_save_order_acting_error( $log );
				$this->save_acting_log( $response_data, 'paygent_card', 'payment_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
				do_action( 'usces_action_register_auto_orderdata', $args, $response_data );
			} else {
				$response_data  = $pm->get_response_data();
				$payment_id     = ( isset( $response_data['payment_id'] ) ) ? $response_data['payment_id'] : '';
				$settlement_ref = $this->settlement_ref( $order_id, $trading_id, $payment_id );
				$usces->set_order_meta_value( $acting_flg, usces_serialize( $settlement_ref ), $order_id );
				$usces->set_order_meta_value( 'trading_id', $trading_id, $order_id );
				$usces->set_order_meta_value( 'wc_trans_id', $trading_id, $order_id );
				do_action( 'usces_action_register_auto_orderdata', $args, $settlement_ref );
			}
		} else {
			$settltment_errmsg = __( '[Regular purchase] Member information acquisition error.', 'autodelivery' );
			$result            = ( isset( $customer['result'] ) ) ? $customer['result'] : 'error';
			$log               = array(
				'acting' => 'paygent_card(member_process)',
				'key'    => $member_id,
				'result' => $result,
				'data'   => $customer,
			);
			usces_save_order_acting_error( $log );
			if ( ! isset( $customer['payment_type'] ) ) {
				$customer['payment_type'] = PAYMENT_TYPE_CREDIT;
			}
			$this->save_acting_log( $customer, 'paygent_card', 'customer_error', RESULT_STATUS_ERROR, $order_id, $trading_id );
			do_action( 'usces_action_register_auto_orderdata', $args, $customer );
		}
		if ( '' != $settltment_errmsg ) {
			$settlement = array(
				'settltment_status' => __( 'Failure', 'autodelivery' ),
				'settltment_errmsg' => $settltment_errmsg,
				'trading_id'        => $trading_id,
			);
			$usces->set_order_meta_value( $acting_flg, usces_serialize( $settlement ), $order_id );
			$usces->set_order_meta_value( 'trading_id', $trading_id, $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $trading_id, $order_id );
			wcad_settlement_error_mail( $order_id, $settltment_errmsg );
		}
	}

	/**
	 * Save session data.
	 *
	 * @param  string $trading_id Trading ID.
	 * @param  array  $post_data Post data.
	 * @return boolean
	 */
	private function save_post_data( $trading_id, $post_data ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"INSERT INTO {$wpdb->prefix}usces_log ( `datetime`, `log`, `log_type`, `log_key` ) VALUES ( %s, %s, %s, %s )",
			current_time( 'mysql' ),
			usces_serialize( $post_data ),
			'acting_post_data',
			$trading_id
		);
		$res   = $wpdb->query( $query );
		return $res;
	}

	/**
	 * Get session data.
	 *
	 * @param  string $trading_id Trading ID.
	 * @return array
	 */
	private function get_post_data( $trading_id ) {
		global $wpdb;

		$query = $wpdb->prepare( "SELECT `log` FROM {$wpdb->prefix}usces_log WHERE `log_type` = %s AND `log_key` = %s", 'acting_post_data', $trading_id );
		$data  = $wpdb->get_var( $query );
		return usces_unserialize( $data );
	}

	/**
	 * Delete session data.
	 *
	 * @param string $trading_id Trading ID.
	 */
	private function delete_post_data( $trading_id ) {
		global $wpdb;

		$query = $wpdb->prepare( "DELETE FROM {$wpdb->prefix}usces_log WHERE `log_type` = %s AND `log_key` = %s", 'acting_post_data', $trading_id );
		$wpdb->query( $query );
	}

	/**
	 * サービスタイプ取得
	 *
	 * @param  string $acting Acting type.
	 * @return string
	 */
	private function get_service_type( $acting ) {
		$service_type = '';
		$acting_opts  = $this->get_acting_settings();
		$activation   = $acting_opts[ $acting . '_activate' ];
		if ( 'on' == $activation ) {
			$service_type = 'link';
		} elseif ( 'module' == $activation ) {
			$service_type = 'module';
		}
		return $service_type;
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
	 * 受注ID 取得
	 *
	 * @param  string $trading_id Trading ID.
	 * @return int
	 */
	protected function get_order_id( $trading_id ) {
		global $wpdb;

		$query    = $wpdb->prepare( "SELECT `order_id` FROM {$wpdb->prefix}usces_order_meta WHERE `meta_key` = %s AND `meta_value` = %s", 'trading_id', $trading_id );
		$order_id = $wpdb->get_var( $query );
		return $order_id;
	}

	/**
	 * マーチャント取引ID 取得
	 *
	 * @param  int $order_id Order number.
	 * @return string
	 */
	protected function get_trading_id( $order_id ) {
		global $usces;

		$trading_id = $usces->get_order_meta_value( 'trading_id', $order_id );
		return $trading_id;
	}

	/**
	 * 受注ステータス取得
	 *
	 * @param  int $order_id Order number.
	 * @return string
	 */
	protected function get_order_status( $order_id ) {
		global $wpdb;

		$query        = $wpdb->prepare( "SELECT LOCATE( 'receipted', `order_status` ) FROM {$wpdb->prefix}usces_order WHERE `ID` = %d", $order_id );
		$order_status = $wpdb->get_var( $query );
		return $order_status;
	}

	/**
	 * 受注データ支払方法取得
	 *
	 * @param  int $order_id Order number.
	 * @return string
	 */
	protected function get_order_acting_flg( $order_id ) {
		global $wpdb;

		$query              = $wpdb->prepare( "SELECT `order_payment_name` FROM {$wpdb->prefix}usces_order WHERE `ID` = %d", $order_id );
		$order_payment_name = $wpdb->get_var( $query );
		$payment            = usces_get_payments_by_name( $order_payment_name );
		$acting_flg         = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';
		return $acting_flg;
	}

	/**
	 * 決済ログ取得
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @param  string $result Result.
	 * @return array
	 */
	public function get_acting_log( $order_id = 0, $trading_id = 0, $result = RESULT_STATUS_NORMAL ) {
		global $wpdb;

		if ( empty( $order_id ) ) {
			if ( RESULT_STATUS_NORMAL == $result ) {
				$query = $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `tracking_id` = %s AND `result` = %s ORDER BY `ID` DESC, `datetime` DESC",
					$trading_id,
					RESULT_STATUS_NORMAL
				);
			} else {
				$query = $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `tracking_id` = %s ORDER BY `ID` DESC, `datetime` DESC",
					$trading_id
				);
			}
		} else {
			if ( empty( $trading_id ) ) {
				if ( RESULT_STATUS_NORMAL == $result ) {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `datetime` IN( SELECT MAX( `datetime` ) FROM {$wpdb->prefix}usces_acting_log GROUP BY `tracking_id` ) AND `order_id` = %d AND `result` = %s ORDER BY `ID` DESC, `datetime` DESC",
						$order_id,
						RESULT_STATUS_NORMAL
					);
				} else {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `datetime` IN( SELECT MAX( `datetime` ) FROM {$wpdb->prefix}usces_acting_log GROUP BY `tracking_id` ) AND `order_id` = %d ORDER BY `ID` DESC, `datetime` DESC",
						$order_id
					);
				}
			} else {
				if ( RESULT_STATUS_NORMAL == $result ) {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `order_id` = %d AND `tracking_id` = %s AND `result` = %s ORDER BY `ID` DESC, `datetime` DESC",
						$order_id,
						$trading_id,
						RESULT_STATUS_NORMAL
					);
				} else {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `order_id` = %d AND `tracking_id` = %s ORDER BY `ID` DESC, `datetime` DESC",
						$order_id,
						$trading_id
					);
				}
			}
		}
		$log_data = $wpdb->get_results( $query, ARRAY_A );
		return $log_data;
	}

	/**
	 * 決済ログ出力
	 *
	 * @param  string $log Log data.
	 * @param  string $acting Acting type.
	 * @param  string $status Status.
	 * @param  string $result Result.
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @return array
	 */
	private function save_acting_log( $log, $acting, $status, $result, $order_id, $trading_id ) {
		global $wpdb;

		if ( isset( $log['payment_amount'] ) ) {
			$amount = $log['payment_amount'];
		} elseif ( isset( $log['reduced_amount'] ) ) {
			$amount = $log['reduced_amount'];
		} elseif ( isset( $log['amount'] ) ) {
			$amount = $log['amount'];
		} else {
			$amount = 0;
		}
		if ( ! empty( $log['payment_date'] ) ) {
			$datetime = date( 'Y-m-d H:i:s', strtotime( $log['payment_date'] ) );
		} elseif ( ! empty( $log['payment_init_date'] ) ) {
			$datetime = date( 'Y-m-d H:i:s', strtotime( $log['payment_init_date'] ) );
		} else {
			$datetime = current_time( 'mysql' );
		}
		$query = $wpdb->prepare(
			"INSERT INTO {$wpdb->prefix}usces_acting_log ( `datetime`, `log`, `acting`, `status`, `result`, `amount`, `order_id`, `tracking_id` ) VALUES ( %s, %s, %s, %s, %s, %f, %d, %s )",
			$datetime,
			usces_serialize( $log ),
			$acting,
			$status,
			$result,
			$amount,
			$order_id,
			$trading_id
		);
		$res   = $wpdb->query( $query );
		return $res;
	}

	/**
	 * 最新処理取得
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @param  string $result Result.
	 * @return array
	 */
	public function get_acting_latest_log( $order_id, $trading_id, $result = RESULT_STATUS_NORMAL ) {
		$latest_log = array();
		$log_data   = $this->get_acting_log( $order_id, $trading_id, $result );
		if ( $log_data ) {
			$data = current( $log_data );
			$log  = usces_unserialize( $data['log'] );
			if ( isset( $log['payment_id'] ) ) {
				$payment_id = $log['payment_id'];
			} else {
				$payment_id = '';
			}
			$latest_log['acting']     = $data['acting'];
			$latest_log['status']     = $data['status'];
			$latest_log['result']     = $data['result'];
			$latest_log['log']        = $log;
			$latest_log['amount']     = $data['amount'];
			$latest_log['order_id']   = $data['order_id'];
			$latest_log['payment_id'] = $payment_id;
			$latest_log['trading_id'] = $data['tracking_id'];
		}
		return $latest_log;
	}

	/**
	 * 決済ステータス取得
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id Trading ID.
	 * @return string
	 */
	private function get_payment_status( $order_id, $trading_id ) {
		global $wpdb;

		$payment_status = '';
		$latest_log     = $this->get_acting_latest_log( $order_id, $trading_id, RESULT_STATUS_ALL );
		if ( isset( $latest_log['status'] ) ) {
			$payment_status = $latest_log['status'];
		}
		return $payment_status;
	}

	/**
	 * 処理区分名称取得
	 *
	 * @param  string $payment_status Payment status code.
	 * @return string
	 */
	private function get_status_name( $payment_status ) {
		$status_name = '';
		switch ( $payment_status ) {
			case STATUS_REQUESTED: /* 申込済 */
				$status_name = '申込済';
				break;
			case STATUS_AUTHORIZE_NG: /* オーソリNG */
				$status_name = 'オーソリNG';
				break;
			case STATUS_PAYMENT_EXPIRED: /* 支払期限切 */
				$status_name = '支払期限切';
				break;
			case STATUS_3DSECURE_INTERRUPTION: /* 3Dセキュア中断 */
				$status_name = '3Dセキュア中断';
				break;
			case STATUS_3DSECURE_CERTIFICATION: /* 3Dセキュア認証 */
				$status_name = '3Dセキュア認証';
				break;
			case STATUS_REGISTRATION_SUSPENDED: /* 申込中断 */
				$status_name = '申込中断';
				break;
			case STATUS_PAYMENT_INVALIDITY_NO_CLEAR: /* 支払期限切（消込対象外） */
				$status_name = '支払期限切（消込対象外）';
				break;
			case STATUS_AUTHORIZE_OK: /* オーソリOK */
				$status_name = 'オーソリOK';
				break;
			case STATUS_AUTHORIZE_COMPLETED: /* オーソリ完了 */
				$status_name = 'オーソリ完了';
				break;
			case STATUS_SALES_REQUEST_PENDING: /* 売上要求中 */
				$status_name = '売上要求中';
				break;
			case STATUS_AUTHORIZE_CANCELING: /* オーソリ取消中 */
				$status_name = 'オーソリ取消中';
				break;
			case STATUS_AUTHORIZE_CANCELED: /* オーソリ取消済 */
				$status_name = 'オーソリ取消済';
				break;
			case STATUS_AUTHORIZE_EXPIRED: /* オーソリ期限切 */
				$status_name = 'オーソリ期限切';
				break;
			case STATUS_CLEARED: /* 消込済 */
				$status_name = '消込済';
				break;
			case STATUS_CLEARED_SALES_CANCEL_INVALIDITY: /* 消込済（売上取消期限切） */
				$status_name = '消込済（売上取消期限切）';
				break;
			case STATUS_SALES_CANCELING: /* 売上取消中 */
				$status_name = '売上取消中';
				break;
			case STATUS_PRELIMINARY_DETECTED: /* 速報検地済 */
				$status_name = '速報検地済';
				break;
			case STATUS_COMPLETE_CLEARED: /* 消込完了 */
				$status_name = '消込完了';
				break;
			case STATUS_SALES_CANCELING_TALLY: /* 売上取消集計中 */
				$status_name = '売上取消集計中';
				break;
			case STATUS_SALES_CANCELED: /* 売上取消済 */
				$status_name = '売上取消済';
				break;
			case STATUS_PRELIMINARY_CANCELED: /* 速報取消済 */
				$status_name = '速報取消済';
				break;
			case STATUS_COMPLETE_CANCELED: /* 取消完了 */
				$status_name = '取消完了';
				break;
			case PAYGENT_CREDIT: /* カード決済オーソリ */
				$status_name = 'カード決済オーソリ';
				break;
			case PAYGENT_AUTH_CANCEL: /* カード決済オーソリキャンセル */
				$status_name = 'カード決済オーソリキャンセル';
				break;
			case PAYGENT_CARD_COMMIT: /* カード決済売上 */
				$status_name = 'カード決済売上';
				break;
			case PAYGENT_CARD_COMMIT_CANCEL: /* カード決済売上キャンセル */
				$status_name = 'カード決済売上キャンセル';
				break;
			case PAYGENT_CARD_COMMIT_AUTH_REVISE: /* カード決済補正オーソリ */
				$status_name = 'カード決済補正オーソリ';
				break;
			case PAYGENT_CARD_COMMIT_REVISE: /* カード決済補正売上 */
				$status_name = 'カード決済補正売上';
				break;
			case PAYGENT_PAIDY_AUTH_CANCEL: /* Paidy オーソリキャンセル */
				$status_name = 'Paidyオーソリキャンセル';
				break;
			case PAYGENT_PAIDY_CAPTURE: /* Paidy 売上 */
				$status_name = 'Paidy売上';
				break;
			case PAYGENT_PAIDY_REFUND: /* Paidy 返金 */
				$status_name = 'Paidy返金';
				break;
			case 'bank_applying': /* 銀行ネット決済ASP */
				$status_name = '申込中';
				break;
			case 'reference_error': /* 決済履歴取得不可 */
				$status_name = '決済履歴取得不可';
				break;
			case 'payment_error': /* 決済エラー */
				$status_name = '決済エラー';
				break;
			case 'customer_error': /* 会員情報エラー */
				$status_name = '会員情報エラー';
				break;
			case 'error': /* その他エラー */
				$status_name = 'エラー';
				break;
			default:
				$status_name = $payment_status;
		}
		return $status_name;
	}

	/**
	 * 処理区分取得
	 *
	 * @param  string $payment_status Payment status code.
	 * @return string
	 */
	private function get_status( $payment_status ) {
		$status = '';
		switch ( $payment_status ) {
			case STATUS_REQUESTED: /* 申込済 */
				$status = 'requested';
				break;
			case STATUS_AUTHORIZE_NG: /* オーソリNG */
				$status = 'authorize-ng';
				break;
			case STATUS_PAYMENT_EXPIRED: /* 支払期限切 */
				$status = 'payment-expired';
				break;
			case STATUS_3DSECURE_INTERRUPTION: /* 3Dセキュア中断 */
				$status = '3dsecure-interruption';
				break;
			case STATUS_3DSECURE_CERTIFICATION: /* 3Dセキュア認証 */
				$status = '3dsecure-certification';
				break;
			case STATUS_REGISTRATION_SUSPENDED: /* 申込中断 */
				$status = 'registration-suspended';
				break;
			case STATUS_PAYMENT_INVALIDITY_NO_CLEAR: /* 支払期限切（消込対象外） */
				$status = 'payment-invalidity-no-clear';
				break;
			case STATUS_AUTHORIZE_OK: /* オーソリOK */
				$status = 'authorize-ok';
				break;
			case STATUS_AUTHORIZE_COMPLETED: /* オーソリ完了 */
				$status = 'authorize-completed';
				break;
			case STATUS_SALES_REQUEST_PENDING: /* 売上要求中 */
				$status = 'sales-request-pending';
				break;
			case STATUS_AUTHORIZE_CANCELING: /* オーソリ取消中 */
				$status = 'authorize-canceling';
				break;
			case STATUS_AUTHORIZE_CANCELED: /* オーソリ取消済 */
				$status = 'authorize-canceled';
				break;
			case STATUS_AUTHORIZE_EXPIRED: /* オーソリ期限切 */
				$status = 'authorize-expired';
				break;
			case STATUS_CLEARED: /* 消込済 */
				$status = 'cleared';
				break;
			case STATUS_CLEARED_SALES_CANCEL_INVALIDITY: /* 消込済（売上取消期限切） */
				$status = 'cleared-sales-cancel-invalidity';
				break;
			case STATUS_SALES_CANCELING: /* 売上取消中 */
				$status = 'being-sales-canceling';
				break;
			case STATUS_PRELIMINARY_DETECTED: /* 速報検地済 */
				$status = 'preliminary-detected';
				break;
			case STATUS_COMPLETE_CLEARED: /* 消込完了 */
				$status = 'complete-cleared';
				break;
			case STATUS_SALES_CANCELING_TALLY: /* 売上取消集計中 */
				$status = 'being-sales-canceling-tally';
				break;
			case STATUS_SALES_CANCELED: /* 売上取消済 */
				$status = 'sales-canceled';
				break;
			case STATUS_PRELIMINARY_CANCELED: /* 速報取消済 */
				$status = 'preliminary-canceled';
				break;
			case STATUS_COMPLETE_CANCELED: /* 取消完了 */
				$status = 'complete-canceled';
				break;
			case 'bank_applying': /* 銀行ネット決済ASP */
				$status = 'applying';
				break;
			case 'reference_error': /* 決済履歴取得不可 */
				$status = 'reference-error';
				break;
			case 'payment_error': /* 決済エラー */
				$status = 'payment-error';
				break;
			case 'customer_error': /* 会員情報エラー */
				$status = 'customer-error';
				break;
			case 'error': /* その他エラー */
				$status = 'error';
				break;
			default:
				$status = $payment_status;
		}
		return $status;
	}

	/**
	 * 決済結果参照
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trading_id マーチャント取引ID.
	 * @param  string $payment_id 決済ID.
	 * @return array
	 */
	private function settlement_ref( $order_id, $trading_id, $payment_id = '' ) {
		$settlement = array();

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'trading_id', $trading_id );
		if ( ! empty( $payment_id ) ) {
			$pm->req_put( 'payment_id', $payment_id );
		}
		$result        = $pm->post( PAYGENT_REF );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$settlement['result']          = $result;
			$settlement['result_status']   = RESULT_STATUS_ERROR;
			$settlement['response_code']   = $pm->get_response_code();
			$settlement['response_detail'] = $pm->get_response_detail();
			$settlement['result_message']  = $pm->get_result_message();
		} else {
			$settlement = $pm->get_response_data();
		}
		return $settlement;
	}

	/**
	 * 決済要求
	 *
	 * @param  string $telegram_kind 電文種別ID.
	 * @param  string $trading_id マーチャント取引ID.
	 * @param  string $payment_id 決済ID.
	 * @return array
	 */
	private function settlement_request( $telegram_kind, $trading_id, $payment_id ) {
		$settlement = array();

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'trading_id', $trading_id );
		$pm->req_put( 'payment_id', $payment_id );
		$result        = $pm->post( $telegram_kind );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$settlement['result']          = $result;
			$settlement['result_status']   = RESULT_STATUS_ERROR;
			$settlement['response_code']   = $pm->get_response_code();
			$settlement['response_detail'] = $pm->get_response_detail();
			$settlement['result_message']  = $pm->get_result_message();
		} else {
			$settlement = $pm->get_response_data();
		}
		return $settlement;
	}

	/**
	 * 決済金額補正要求
	 *
	 * @param  string $telegram_kind 電文種別ID.
	 * @param  string $trading_id マーチャント取引ID.
	 * @param  string $payment_id 決済ID.
	 * @param  float  $amount 決済金額.
	 * @return array
	 */
	private function settlement_request_revise( $telegram_kind, $trading_id, $payment_id, $amount ) {
		$settlement = array();

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'trading_id', $trading_id );
		$pm->req_put( 'payment_id', $payment_id );
		$pm->req_put( 'payment_amount', $amount );
		$pm->req_put( 'reduction_flag', '0' );
		$result        = $pm->post( $telegram_kind );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$settlement['result']          = $result;
			$settlement['result_status']   = RESULT_STATUS_ERROR;
			$settlement['response_code']   = $pm->get_response_code();
			$settlement['response_detail'] = $pm->get_response_detail();
			$settlement['result_message']  = $pm->get_result_message();
		} else {
			$settlement = $pm->get_response_data();
		}
		return $settlement;
	}

	/**
	 * Paidy 要求
	 *
	 * @param  string $telegram_kind 電文種別ID.
	 * @param  string $trading_id マーチャント取引ID.
	 * @param  string $payment_id 決済ID.
	 * @return array
	 */
	private function settlement_request_paidy( $telegram_kind, $trading_id, $payment_id = '' ) {
		$settlement = array();

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'trading_id', $trading_id );
		if ( ! empty( $payment_id ) ) {
			$pm->req_put( 'payment_id', $payment_id );
		}
		$result        = $pm->post( $telegram_kind );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$settlement['result']          = $result;
			$settlement['result_status']   = RESULT_STATUS_ERROR;
			$settlement['response_code']   = $pm->get_response_code();
			$settlement['response_detail'] = $pm->get_response_detail();
			$settlement['result_message']  = $pm->get_result_message();
		} else {
			$settlement = $pm->get_response_data();
		}
		return $settlement;
	}

	/**
	 * Paidy 返金要求
	 *
	 * @param  string $telegram_kind 電文種別ID.
	 * @param  string $trading_id マーチャント取引ID.
	 * @param  float  $amount 決済金額.
	 * @param  string $payment_id 決済ID.
	 * @return array
	 */
	private function settlement_request_paidy_refund( $telegram_kind, $trading_id, $amount, $payment_id = '' ) {
		$settlement = array();

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'trading_id', $trading_id );
		if ( ! empty( $payment_id ) ) {
			$pm->req_put( 'payment_id', $payment_id );
		}
		$pm->req_put( 'amount', $amount );
		$result        = $pm->post( $telegram_kind );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$settlement['result']          = $result;
			$settlement['result_status']   = RESULT_STATUS_ERROR;
			$settlement['response_code']   = $pm->get_response_code();
			$settlement['response_detail'] = $pm->get_response_detail();
			$settlement['result_message']  = $pm->get_result_message();
		} else {
			$settlement = $pm->get_response_data();
		}
		return $settlement;
	}

	/**
	 * 会員情報取得
	 *
	 * @param  int $member_id Member ID.
	 * @return array
	 */
	private function customer_ref( $member_id ) {
		global $usces;

		$customer         = array();
		$customer_card_id = $usces->get_member_meta_value( 'paygent_customer_card_id', $member_id );
		if ( empty( $customer_card_id ) ) {
			return $customer;
		}

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'customer_id', $member_id );
		$pm->req_put( 'customer_card_id', $customer_card_id );
		$result        = $pm->post( PAYGENT_CARD_STOCK_GET );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$customer['result']          = $result;
			$customer['result_status']   = RESULT_STATUS_ERROR;
			$customer['response_code']   = $pm->get_response_code();
			$customer['response_detail'] = $pm->get_response_detail();
			$customer['result_message']  = $pm->get_result_message();
		} else {
			$customer = $pm->get_response_data();
		}
		return $customer;
	}

	/**
	 * 会員カード情報登録
	 *
	 * @param  int    $member_id Member ID.
	 * @param  string $token Card Token.
	 * @return array
	 */
	private function customer_card_add( $member_id, $token ) {
		global $usces;

		$customer    = array();
		$acting_opts = $this->get_acting_settings();

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'customer_id', $member_id );
		$pm->req_put( 'valid_check_flg', 1 );
		$pm->req_put( 'card_token', $token );
		if ( 'on' == $acting_opts['use_card_conf_number'] ) {
			$pm->req_put( 'security_code_use', 1 );
		}
		$result        = $pm->post( PAYGENT_CARD_STOCK_SET );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$customer['result']          = $result;
			$customer['result_status']   = RESULT_STATUS_ERROR;
			$customer['response_code']   = $pm->get_response_code();
			$customer['response_detail'] = $pm->get_response_detail();
			$customer['result_message']  = $pm->get_result_message();
		} else {
			$customer = $pm->get_response_data();
			if ( isset( $customer['customer_card_id'] ) ) {
				$usces->set_member_meta_value( 'paygent_customer_card_id', $customer['customer_card_id'], $member_id );
			}
		}
		return $customer;
	}

	/**
	 * 会員カード情報更新
	 *
	 * @param  int    $member_id Member ID.
	 * @param  string $token Card Token.
	 * @return array
	 */
	private function customer_card_upd( $member_id, $token ) {
		$customer = $this->customer_card_del( $member_id );
		if ( isset( $customer['result_status'] ) && RESULT_STATUS_ERROR == $customer['result_status'] ) {
			return $customer;
		}
		$customer = $this->customer_card_add( $member_id, $token );
		return $customer;
	}

	/**
	 * 会員カード情報削除
	 *
	 * @param  int $member_id Member ID.
	 * @return array
	 */
	private function customer_card_del( $member_id ) {
		global $usces;

		$customer         = array();
		$customer_card_id = $usces->get_member_meta_value( 'paygent_customer_card_id', $member_id );

		$pm = Paygent_Module::get_instance();
		$pm->init();
		$pm->req_put( 'customer_id', $member_id );
		$pm->req_put( 'customer_card_id', $customer_card_id );
		$result        = $pm->post( PAYGENT_CARD_STOCK_DEL );
		$result_status = $pm->get_result_status();
		if ( ! ( true === $result ) || RESULT_STATUS_NORMAL != $result_status ) {
			$customer['result']          = $result;
			$customer['result_status']   = RESULT_STATUS_ERROR;
			$customer['response_code']   = $pm->get_response_code();
			$customer['response_detail'] = $pm->get_response_detail();
			$customer['result_message']  = $pm->get_result_message();
		} else {
			$usces->del_member_meta( 'paygent_customer_card_id', $member_id );
			$customer = $pm->get_response_data();
		}
		return $customer;
	}

	/**
	 * Save admin log.
	 *
	 * @since 2.7.4
	 * @see admin_ajax()
	 * @param integer $order_id The order id.
	 * @param string  $trading_id The trading id.
	 * @return void
	 */
	private function save_admin_log( $order_id, $trading_id ) {
		$latest_log = array();
		$log_data   = $this->get_acting_log( $order_id, $trading_id );
		if ( $log_data ) {
			$data = current( $log_data );
			$log  = usces_unserialize( $data['log'] );

			$payment_id = ( isset( $log['payment_id'] ) ) ? $log['payment_id'] : 'no value';

			$latest_log['datetime']     = $data['datetime'];
			$latest_log['acting']     = $data['acting'];
			$latest_log['status']     = $this->get_status_name( $data['status'] );
			$latest_log['result']     = $data['result'];
			$latest_log['log']        = $log;
			$latest_log['amount']     = $data['amount'];
			$latest_log['order_id']   = $data['order_id'];
			$latest_log['payment_id'] = $payment_id;
			$latest_log['trading_id'] = $data['tracking_id'];
		}

		Logger::log( $order_id, 'orderedit', 'update', array( 'paygent_card' => $latest_log ) );
	}
}
