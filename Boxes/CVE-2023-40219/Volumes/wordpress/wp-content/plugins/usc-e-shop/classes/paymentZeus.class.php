<?php
/**
 * Settlement Class.
 * ZEUS
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.1.0
 * @since    2.4.0
 */

/**
 * ゼウス決済モジュール
 */
class ZEUS_SETTLEMENT {

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
	 * 決済結果（正常）
	 *
	 * @var array
	 */
	protected $payment_normal_results = array(
		'OK',
		'Success_order',
		'SuccessOK',
		'success',
		'決済完了',
		'与信完了',
		'仮売完了',
		'取り消し',
	);

	/**
	 * 決済結果（異常）
	 *
	 * @var array
	 */
	protected $payment_error_results = array(
		'failure_order',
		'Invalid',
		'maintenance',
		'connect error',
	);

	/**
	 * 決済オプション
	 *
	 * @var array
	 */
	public $acting_opts;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->paymod_id          = 'zeus';
		$this->pay_method         = array(
			'acting_zeus_card',
			'acting_zeus_bank',
			'acting_zeus_conv',
		);
		$this->acting_name        = 'ゼウス';
		$this->acting_formal_name = __( 'ZEUS Japanese Settlement', 'usces' );
		$this->acting_company_url = 'https://www.cardservice.co.jp/';

		$this->initialize_data();
		$this->acting_opts = $this->get_acting_settings();

		if ( is_admin() ) {
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if ( $this->is_validity_acting( 'card' ) || $this->is_validity_acting( 'bank' ) || $this->is_validity_acting( 'conv' ) ) {
			add_action( 'plugins_loaded', array( $this, 'acting_construct' ), 11 );
			add_action( 'usces_after_cart_instant', array( $this, 'acting_transaction' ), 11 );
			add_filter( 'usces_filter_order_confirm_mail_payment', array( $this, 'order_confirm_mail_payment' ), 10, 5 );
			add_filter( 'usces_filter_is_complete_settlement', array( $this, 'is_complete_settlement' ), 10, 3 );
			add_action( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
			add_filter( 'usces_filter_get_link_key', array( $this, 'get_link_key' ), 10, 2 );
			add_action( 'usces_action_revival_order_data', array( $this, 'revival_orderdata' ), 10, 3 );
			if ( is_admin() ) {
				add_action( 'usces_action_admin_ajax', array( $this, 'admin_ajax' ) );
				add_filter( 'usces_filter_orderlist_detail_value', array( $this, 'orderlist_settlement_status' ), 10, 4 );
				add_action( 'usces_action_order_edit_form_status_block_middle', array( $this, 'settlement_status' ), 10, 3 );
				add_action( 'usces_action_order_edit_form_settle_info', array( $this, 'settlement_information' ), 10, 2 );
				add_action( 'usces_action_endof_order_edit_form', array( $this, 'settlement_dialog' ), 10, 2 );
				add_filter( 'usces_filter_settle_info_field_meta_keys', array( $this, 'settlement_info_field_meta_keys' ) );
				add_filter( 'usces_filter_settle_info_field_keys', array( $this, 'settlement_info_field_keys' ), 10, 2 );
				add_filter( 'usces_filter_settle_info_field_value', array( $this, 'settlement_info_field_value' ), 10, 3 );
			} else {
				add_filter( 'usces_filter_payment_detail', array( $this, 'payment_detail' ), 10, 2 );
				add_filter( 'usces_filter_payments_str', array( $this, 'payments_str' ), 10, 2 );
				add_filter( 'usces_filter_payments_arr', array( $this, 'payments_arr' ), 10, 2 );
				add_filter( 'usces_filter_delivery_check', array( $this, 'delivery_check' ), 15 );
				add_filter( 'usces_filter_delivery_secure_form_loop', array( $this, 'delivery_secure_form_loop' ), 10, 2 );
				add_filter( 'usces_filter_confirm_inform', array( $this, 'confirm_inform' ), 10, 5 );
				add_action( 'usces_action_confirm_page_point_inform', array( $this, 'e_point_inform' ), 10, 5 );
				add_filter( 'usces_filter_confirm_point_inform', array( $this, 'point_inform' ), 10, 5 );
				if ( defined( 'WCEX_COUPON' ) ) {
					add_filter( 'wccp_filter_coupon_inform', array( $this, 'point_inform' ), 10, 5 );
				}
				add_action( 'usces_pre_purchase', array( $this, 'pre_purchase' ) );
				add_action( 'usces_action_acting_processing', array( $this, 'acting_processing' ), 10, 2 );
				add_action( 'usces_pre_acting_return', array( $this, 'pre_acting_return' ) );
				add_filter( 'usces_filter_check_acting_return_results', array( $this, 'acting_return' ) );
				add_filter( 'usces_filter_check_acting_return_duplicate', array( $this, 'check_acting_return_duplicate' ), 10, 2 );
				add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
				add_action( 'usces_post_reg_orderdata', array( $this, 'post_register_orderdata' ), 10, 2 );
				add_filter( 'usces_filter_get_error_settlement', array( $this, 'error_page_message' ) );
			}
		}

		if ( $this->is_validity_acting( 'card' ) ) {
			if ( $this->is_activate_card( 'api' ) && 1 === (int) $this->acting_opts['3dsecur'] ) {
				remove_action( 'wp_ajax_welcart_confirm_check', 'welcart_confirm_check_ajax' );
				remove_action( 'wp_ajax_nopriv_welcart_confirm_check', 'welcart_confirm_check_ajax' );
				remove_filter( 'usces_filter_uscesL10n', 'usces_confirm_uscesL10n', 11, 2 );
				add_action( 'wp_ajax_zeus_3dsecure_enrol', array( $this, 'zeus_3dsecure_enrol' ) );
				add_action( 'wp_ajax_nopriv_zeus_3dsecure_enrol', array( $this, 'zeus_3dsecure_enrol' ) );
			}
			add_action( 'usces_action_admin_member_info', array( $this, 'admin_member_info' ), 10, 3 );
			add_action( 'usces_action_post_update_memberdata', array( $this, 'admin_update_memberdata' ), 10, 2 );
			add_filter( 'usces_filter_uscesL10n', array( $this, 'set_uscesL10n' ), 12, 2 );
			add_action( 'wp_print_footer_scripts', array( $this, 'footer_scripts' ), 9 );
			add_filter( 'usces_filter_available_payment_method', array( $this, 'set_available_payment_method' ) );
			add_filter( 'usces_filter_template_redirect', array( $this, 'member_update_settlement' ), 1 );
			add_action( 'usces_action_member_submenu_list', array( $this, 'e_update_settlement' ) );
			add_filter( 'usces_filter_member_submenu_list', array( $this, 'update_settlement' ), 10, 2 );
			add_filter( 'usces_filter_delete_member_check', array( $this, 'delete_member_check' ), 10, 2 );

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
		}
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize.
	 */
	public function initialize_data() {
		$options                                        = get_option( 'usces', array() );
		$options['acting_settings']['zeus']['ipaddrs']  = ( isset( $options['acting_settings']['zeus']['ipaddrs'] ) ) ? $options['acting_settings']['zeus']['ipaddrs'] : array();
		$options['acting_settings']['zeus']['card_url'] = ( isset( $options['acting_settings']['zeus']['card_url'] ) ) ? $options['acting_settings']['zeus']['card_url'] : '';
		$options['acting_settings']['zeus']['card_secureurl']       = ( isset( $options['acting_settings']['zeus']['card_secureurl'] ) ) ? $options['acting_settings']['zeus']['card_secureurl'] : '';
		$options['acting_settings']['zeus']['card_tokenurl']        = ( isset( $options['acting_settings']['zeus']['card_tokenurl'] ) ) ? $options['acting_settings']['zeus']['card_tokenurl'] : '';
		$options['acting_settings']['zeus']['bank_url']             = ( isset( $options['acting_settings']['zeus']['bank_url'] ) ) ? $options['acting_settings']['zeus']['bank_url'] : '';
		$options['acting_settings']['zeus']['conv_url']             = ( isset( $options['acting_settings']['zeus']['conv_url'] ) ) ? $options['acting_settings']['zeus']['conv_url'] : '';
		$options['acting_settings']['zeus']['card_activate']        = ( isset( $options['acting_settings']['zeus']['card_activate'] ) ) ? $options['acting_settings']['zeus']['card_activate'] : 'off';
		$options['acting_settings']['zeus']['clientip']             = ( isset( $options['acting_settings']['zeus']['clientip'] ) ) ? $options['acting_settings']['zeus']['clientip'] : '';
		$options['acting_settings']['zeus']['connection']           = ( isset( $options['acting_settings']['zeus']['connection'] ) ) ? $options['acting_settings']['zeus']['connection'] : 1;
		$options['acting_settings']['zeus']['3dsecur']              = ( isset( $options['acting_settings']['zeus']['3dsecur'] ) ) ? $options['acting_settings']['zeus']['3dsecur'] : 2;
		$options['acting_settings']['zeus']['3ds_pattern']          = ( isset( $options['acting_settings']['zeus']['3ds_pattern'] ) ) ? $options['acting_settings']['zeus']['3ds_pattern'] : '';
		$options['acting_settings']['zeus']['security']             = ( isset( $options['acting_settings']['zeus']['security'] ) ) ? $options['acting_settings']['zeus']['security'] : 2;
		$options['acting_settings']['zeus']['authkey']              = ( isset( $options['acting_settings']['zeus']['authkey'] ) ) ? $options['acting_settings']['zeus']['authkey'] : '';
		$options['acting_settings']['zeus']['quickcharge']          = ( isset( $options['acting_settings']['zeus']['quickcharge'] ) ) ? $options['acting_settings']['zeus']['quickcharge'] : '';
		$options['acting_settings']['zeus']['batch']                = ( isset( $options['acting_settings']['zeus']['batch'] ) ) ? $options['acting_settings']['zeus']['batch'] : '';
		$options['acting_settings']['zeus']['auto_settlement_mail'] = ( isset( $options['acting_settings']['zeus']['auto_settlement_mail'] ) ) ? $options['acting_settings']['zeus']['auto_settlement_mail'] : 'off';
		$options['acting_settings']['zeus']['howpay']               = ( isset( $options['acting_settings']['zeus']['howpay'] ) ) ? $options['acting_settings']['zeus']['howpay'] : '';
		$options['acting_settings']['zeus']['howpay_B1']            = ( isset( $options['acting_settings']['zeus']['howpay_B1'] ) ) ? $options['acting_settings']['zeus']['howpay_B1'] : '';
		$options['acting_settings']['zeus']['howpay_02']            = ( isset( $options['acting_settings']['zeus']['howpay_02'] ) ) ? $options['acting_settings']['zeus']['howpay_02'] : '';
		$options['acting_settings']['zeus']['bank_activate']        = ( isset( $options['acting_settings']['zeus']['bank_activate'] ) ) ? $options['acting_settings']['zeus']['bank_activate'] : 'off';
		$options['acting_settings']['zeus']['bank_ope']             = ( isset( $options['acting_settings']['zeus']['bank_ope'] ) ) ? $options['acting_settings']['zeus']['bank_ope'] : '';
		$options['acting_settings']['zeus']['clientip_bank']        = ( isset( $options['acting_settings']['zeus']['clientip_bank'] ) ) ? $options['acting_settings']['zeus']['clientip_bank'] : '';
		$options['acting_settings']['zeus']['testid_bank']          = ( isset( $options['acting_settings']['zeus']['testid_bank'] ) ) ? $options['acting_settings']['zeus']['testid_bank'] : '';
		$options['acting_settings']['zeus']['bank_expired_date']    = ( isset( $options['acting_settings']['zeus']['bank_expired_date'] ) ) ? $options['acting_settings']['zeus']['bank_expired_date'] : '';
		$options['acting_settings']['zeus']['conv_activate']        = ( isset( $options['acting_settings']['zeus']['conv_activate'] ) ) ? $options['acting_settings']['zeus']['conv_activate'] : 'off';
		$options['acting_settings']['zeus']['conv_ope']             = ( isset( $options['acting_settings']['zeus']['conv_ope'] ) ) ? $options['acting_settings']['zeus']['conv_ope'] : '';
		$options['acting_settings']['zeus']['clientip_conv']        = ( isset( $options['acting_settings']['zeus']['clientip_conv'] ) ) ? $options['acting_settings']['zeus']['clientip_conv'] : '';
		$options['acting_settings']['zeus']['testid_conv']          = ( isset( $options['acting_settings']['zeus']['testid_conv'] ) ) ? $options['acting_settings']['zeus']['testid_conv'] : '';
		$options['acting_settings']['zeus']['test_type_conv']       = ( isset( $options['acting_settings']['zeus']['test_type_conv'] ) ) ? $options['acting_settings']['zeus']['test_type_conv'] : '';
		$options['acting_settings']['zeus']['pay_cvs']              = ( isset( $options['acting_settings']['zeus']['pay_cvs'] ) ) ? $options['acting_settings']['zeus']['pay_cvs'] : array();
		$options['acting_settings']['zeus']['conv_span']            = ( isset( $options['acting_settings']['zeus']['conv_span'] ) ) ? $options['acting_settings']['zeus']['conv_span'] : '';
		$options['acting_settings']['zeus']['activate']             = ( isset( $options['acting_settings']['zeus']['activate'] ) ) ? $options['acting_settings']['zeus']['activate'] : 'off';
		if ( 'on' === $options['acting_settings']['zeus']['activate'] && 'on' === $options['acting_settings']['zeus']['card_activate'] ) {
			if ( ! isset( $options['acting_settings']['zeus']['card_order_ref'] ) ) {
				$options['acting_settings']['zeus']['card_order_ref'] = 'https://linkpt.cardservice.co.jp/cgi-bin/order_ref.cgi';
			}
			if ( ! isset( $options['acting_settings']['zeus']['card_price_change'] ) ) {
				$options['acting_settings']['zeus']['card_price_change'] = 'https://linkpt.cardservice.co.jp/cgi-bin/credit/price_change/reflect/index.cgi';
			}
		}
		update_option( 'usces', $options );

		$this->unavailable_method = array( 'acting_welcart_card', 'acting_escott_card', 'acting_sbps_card', 'acting_paygent_card' );
	}

	/**
	 * 併用不可決済モジュール
	 * usces_filter_unavailable_payments
	 *
	 * @return array
	 */
	public function unavailable_payments() {
		return $this->unavailable_method;
	}

	/**
	 * 決済有効判定
	 * 支払方法で使用している場合に true
	 *
	 * @param  string $type Module type.
	 * @return bool
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
					if ( 'acting_zeus_card' === $payment['settlement'] && 'activate' === $payment['use'] ) {
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

			case 'bank':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_zeus_bank' === $payment['settlement'] && 'activate' === $payment['use'] ) {
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

			case 'conv':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_zeus_conv' === $payment['settlement'] && 'activate' === $payment['use'] ) {
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

			default:
				if ( 'on' === $acting_opts['activate'] ) {
					return true;
				} else {
					return false;
				}
		}
	}

	/**
	 * クレジットカード決済有効判定
	 *
	 * @param  string $type 'link' Secure Link|'api' Secure API.
	 * @return boolean
	 */
	public function is_activate_card( $type = '' ) {
		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) {
			if ( empty( $type ) ) {
				if ( isset( $acting_opts['card_activate'] ) && 'on' === $acting_opts['card_activate'] ) {
					$res = true;
				} else {
					$res = false;
				}
			} elseif ( 'link' === $type ) {
				if ( isset( $acting_opts['card_activate'] ) && 'on' === $acting_opts['card_activate'] && isset( $acting_opts['connection'] ) && 1 === (int) $acting_opts['connection'] ) {
					$res = true;
				} else {
					$res = false;
				}
			} elseif ( 'api' === $type ) {
				if ( isset( $acting_opts['card_activate'] ) && 'on' === $acting_opts['card_activate'] && isset( $acting_opts['connection'] ) && 2 === (int) $acting_opts['connection'] ) {
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
	 * 銀行振込決済（入金おまかせサービス）有効判定
	 *
	 * @return bool
	 */
	public function is_activate_bank() {
		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['bank_activate'] ) && 'on' === $acting_opts['bank_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * コンビニ決済有効判定
	 *
	 * @return bool
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
	 * 管理画面メッセージ表示
	 * admin_notices
	 */
	public function admin_notices() {
		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) {
			if ( ! isset( $acting_opts['vercheck'] ) || '115' !== $acting_opts['vercheck'] ) {
				echo '<div class="error"><p>決済に「ゼウス」をご利用の場合は、<a href="' . esc_url( admin_url( 'admin.php?usces_page=usces_settlement' ) ) . '">「セキュリティーコード」の設定内容を確認</a>して更新ボタンを押してください。設定を更新するとこのメッセージは表示されなくなります。</p></div>';
			}
		}
	}

	/**
	 * 管理画面スクリプト
	 * admin_print_footer_scripts
	 */
	public function admin_scripts() {
		$admin_page = filter_input( INPUT_GET, 'page' );
		switch ( $admin_page ) :
			/* クレジット決済設定画面 */
			case 'usces_settlement':
				$settlement_selected = get_option( 'usces_settlement_selected', array() );
				if ( in_array( $this->paymod_id, (array) $settlement_selected, true ) ) :
					$acting_opts = $this->get_acting_settings();
					?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var card_activate = "<?php echo esc_js( $acting_opts['card_activate'] ); ?>";
	if( "on" == card_activate ) {
		$(".card_zeus").css("display","");
	} else {
		$(".card_zeus").css("display","none");
	}
	$(document).on( "change", ".card_activate_zeus", function() {
		if( "on" == $(this).val() ) {
			$(".card_zeus").css("display","");
		} else {
			$(".card_zeus").css("display","none");
		}
	});

	if( '2' == $("input[name='connection']:checked").val() ) {
		$(".3dsecur_zeus").css("display","");
	} else {
		$(".3dsecur_zeus").css("display","none");
	}
	$(document).on( "click", "input[name='connection']", function() {
		if( '2' == $("input[name='connection']:checked").val() ) {
			$(".3dsecur_zeus").css("display","");
		} else {
			$(".3dsecur_zeus").css("display","none");
		}
	});

	if( 'on' == $("input[name='howpay']:checked").val() ) {
		$(".howpay_option_zeus").css("display","");
	} else {
		$(".howpay_option_zeus").css("display","none");
	}
	$(document).on( "click", "input[name='howpay']", function() {
		if( 'on' == $("input[name='howpay']:checked").val() ) {
			$(".howpay_option_zeus").css("display","");
		} else {
			$(".howpay_option_zeus").css("display","none");
		}
	});

	var bank_activate = "<?php echo esc_js( $acting_opts['bank_activate'] ); ?>";
	if( "on" == bank_activate ) {
		$(".bank_zeus").css("display","");
	} else {
		$(".bank_zeus").css("display","none");
	}
	$(document).on( "change", ".bank_activate_zeus", function() {
		if( "on" == $(this).val() ) {
			$(".bank_zeus").css("display","");
		} else {
			$(".bank_zeus").css("display","none");
		}
	});

	var conv_activate = "<?php echo esc_js( $acting_opts['conv_activate'] ); ?>";
	if( "on" == conv_activate ) {
		$(".conv_zeus").css("display","");
	} else {
		$(".conv_zeus").css("display","none");
	}
	$(document).on( "change", ".conv_activate_zeus", function() {
		if( "on" == $(this).val() ) {
			$(".conv_zeus").css("display","");
		} else {
			$(".conv_zeus").css("display","none");
		}
	});
});
</script>
					<?php
				endif;
				break;

			/* 受注編集画面・継続課金会員詳細画面 */
			case 'usces_orderlist':
			case 'usces_continue':
				$order_id        = '';
				$acting_flg      = '';
				$order_action    = filter_input( INPUT_GET, 'order_action' );
				$continue_action = filter_input( INPUT_GET, 'continue_action' );
				if ( ( 'usces_orderlist' === $admin_page && ( 'edit' === $order_action || 'editpost' === $order_action || 'newpost' === $order_action ) ) ||
					( 'usces_continue' === $admin_page && 'settlement_zeus_card' === $continue_action ) ) {
					$order_id = ( isset( $_REQUEST['order_id'] ) ) ? wp_unslash( $_REQUEST['order_id'] ) : '';
					if ( ! empty( $order_id ) ) {
						$acting_flg = $this->get_order_acting_flg( $order_id );
					}
				}
				if ( 'acting_zeus_card' === $acting_flg ) :
					?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	adminOrderEdit = {
		getSettlementCard: function() {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			var mode = ( "" != $("#error").val() ) ? "error_zeus_card" : "get_zeus_card";
			$.ajax({
				url:ajaxurl,
				type:"POST",
				cache:false,
				dataType:"json",
				data:{
					action:"usces_admin_ajax",
					mode:mode,
					order_id:$("#order_id").val(),
					order_num:$("#order_num").val(),
					tracking_id:$("#tracking_id").val(),
					member_id:$("#member_id").val(),
					wc_nonce:$("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				if( retVal.acting_status ) {
					var num = $("#order_num").val();
					if( $("#settlement-status-"+num).length ) {
						$("#settlement-status-"+num).html(retVal.acting_status);
					} else {
						$("#settlement-status").html(retVal.acting_status);
					}
				}
				if( retVal.result ) {
					$("#settlement-response").html(retVal.result);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(jqXHR,textStatus,errorThrown) {
				console.log(textStatus);
				console.log(jqXHR.status);
				console.log(errorThrown.message);
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		saleSettlementCard: function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url:ajaxurl,
				type:"POST",
				cache:false,
				dataType:"json",
				data:{
					action:"usces_admin_ajax",
					mode:"sale_zeus_card",
					order_id:$("#order_id").val(),
					order_num:$("#order_num").val(),
					tracking_id:$("#tracking_id").val(),
					member_id:$("#member_id").val(),
					amount:amount,
					wc_nonce:$("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				if( retVal.acting_status ) {
					var num = $("#order_num").val();
					if( $("#settlement-status-"+num).length ) {
						$("#settlement-status-"+num).html(retVal.acting_status);
					} else {
						$("#settlement-status").html(retVal.acting_status);
					}
				}
				if( retVal.result ) {
					$("#settlement-response").html(retVal.result);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(jqXHR,textStatus,errorThrown) {
				console.log(textStatus);
				console.log(jqXHR.status);
				console.log(errorThrown.message);
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		cancelSettlementCard: function() {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url:ajaxurl,
				type:"POST",
				cache:false,
				dataType:"json",
				data:{
					action:"usces_admin_ajax",
					mode:"cancel_zeus_card",
					order_id:$("#order_id").val(),
					order_num:$("#order_num").val(),
					tracking_id:$("#tracking_id").val(),
					member_id:$("#member_id").val(),
					wc_nonce:$("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				if( retVal.acting_status ) {
					var num = $("#order_num").val();
					if( $("#settlement-status-"+num).length ) {
						$("#settlement-status-"+num).html(retVal.acting_status);
					} else {
						$("#settlement-status").html(retVal.acting_status);
					}
				}
				if( retVal.result ) {
					$("#settlement-response").html(retVal.result);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(jqXHR,textStatus,errorThrown) {
				console.log(textStatus);
				console.log(jqXHR.status);
				console.log(errorThrown.message);
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		changeSettlementCard: function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url:ajaxurl,
				type:"POST",
				cache:false,
				dataType:"json",
				data:{
					action:"usces_admin_ajax",
					mode:"change_zeus_card",
					order_id:$("#order_id").val(),
					order_num:$("#order_num").val(),
					tracking_id:$("#tracking_id").val(),
					member_id:$("#member_id").val(),
					amount:amount,
					wc_nonce:$("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				if( retVal.acting_status ) {
					var num = $("#order_num").val();
					if( $("#settlement-status-"+num).length ) {
						$("#settlement-status-"+num).html(retVal.acting_status);
					} else {
						$("#settlement-status").html(retVal.acting_status);
					}
				}
				if( retVal.result ) {
					$("#settlement-response").html(retVal.result);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(jqXHR,textStatus,errorThrown) {
				console.log(textStatus);
				console.log(jqXHR.status);
				console.log(errorThrown.message);
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		reSettlementCard: function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url:ajaxurl,
				type:"POST",
				cache:false,
				dataType:"json",
				data:{
					action:"usces_admin_ajax",
					mode:"re_settlement_zeus_card",
					order_id:$("#order_id").val(),
					order_num:$("#order_num").val(),
					tracking_id:$("#tracking_id").val(),
					member_id:$("#member_id").val(),
					amount:amount,
					wc_nonce:$("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				if( retVal.acting_status ) {
					var num = $("#order_num").val();
					if( $("#settlement-status-"+num).length ) {
						$("#settlement-status-"+num).html(retVal.acting_status);
					} else {
						$("#settlement-status").html(retVal.acting_status);
					}
				}
				if( retVal.result ) {
					$("#settlement-response").html(retVal.result);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(jqXHR,textStatus,errorThrown) {
				console.log(textStatus);
				console.log(jqXHR.status);
				console.log(errorThrown.message);
				$("#settlement-response-loading").html("");
			});
			return false;
		},
	};

	$("#settlement_dialog").dialog({
		dialogClass:"admin-zeus-dialog",
		bgiframe:true,
		autoOpen:false,
		height:"auto",
		width:800,
		resizable:true,
		modal:true,
		buttons: {
			"<?php esc_html_e( 'Close' ); ?>":function() {
				$(this).dialog("close");
			}
		},
		open: function() {
			adminOrderEdit.getSettlementCard();
		},
		close: function() {
		}
	});

	$(document).on( "click", ".settlement-information", function() {
		var tracking_id = $(this).attr("data-tracking_id");
		var order_num = $(this).attr("data-num");
		$("#tracking_id").val(tracking_id);
		$("#order_num").val(order_num);
		$("#settlement_dialog").dialog("option","title","<?php echo esc_js( $this->acting_formal_name ); ?>");
		$("#settlement_dialog").dialog("open");
	});

	$(document).on( "click", "#sale_settlement", function() {
		var amount = parseInt($("#amount_change").val())||0;
		if( 0 == amount ) {
			if( ! confirm("取消処理を実行します。よろしいですか？") ) {
				return;
			}
			adminOrderEdit.cancelSettlementCard();
		} else {
			if( ! confirm("売上処理を実行します。よろしいですか？") ) {
				return;
			}
			adminOrderEdit.saleSettlementCard(amount);
		}
	});

	$(document).on( "click", "#cancel_settlement", function() {
		if( ! confirm("取消処理を実行します。よろしいですか？") ) {
			return;
		}
		adminOrderEdit.cancelSettlementCard();
	});

	$(document).on( "click", "#change_settlement", function() {
		var amount_change = parseInt($("#amount_change").val())||0;
		if( 0 == amount_change ) {
			if( ! confirm("取消処理を実行します。よろしいですか？") ) {
				return;
			}
			adminOrderEdit.cancelSettlementCard();
		} else {
			if( ! confirm("決済金額を"+amount_change+"円に変更します。よろしいですか？") ) {
				return;
			}
			adminOrderEdit.changeSettlementCard(amount_change);
		}
	});

	$(document).on( "click", "#re_settlement", function() {
		var amount = parseInt($("#amount_change").val())||0;
		if( 0 < amount ) {
			if( ! confirm("新規決済を実行します。よろしいですか？") ) {
				return;
			}
			adminOrderEdit.reSettlementCard( amount );
		}
	});

	$(document).on( "keydown", ".settlement-amount", function(e) {
		var halfVal = $(this).val().replace(/[！-～]/g,
			function(tmpStr) {
				return String.fromCharCode(tmpStr.charCodeAt(0)-0xFEE0);
			}
		);
		$(this).val(halfVal.replace(/[^0-9]/g,''));
	});
	$(document).on( "keyup", ".settlement-amount", function() {
		this.value = this.value.replace(/[^0-9]+/i,'');
		this.value = Number(this.value)||0;
	});
	$(document).on( "blur", ".settlement-amount", function() {
		this.value = this.value.replace(/[^0-9]+/i,'');
	});
					<?php if ( 'usces_continue' === $admin_page ) : ?>
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
					member_id: $("#member_id").val(),
					order_id: $("#order_id").val(),
					contracted_year: $("#contracted-year option:selected").val(),
					contracted_month: $("#contracted-month option:selected").val(),
					contracted_day: $("#contracted-day option:selected").val(),
					charged_year: $("#charged-year option:selected").val(),
					charged_month: $("#charged-month option:selected").val(),
					charged_day: $("#charged-day option:selected").val(),
					price: $("#price").val(),
					status: $("#dlseller-status").val(),
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				if( "OK" == retVal.status ) {
					adminOperation.setActionStatus("success","<?php esc_html_e( 'The update was completed.', 'usces' ); ?>");
				} else {
					var message = ( retVal.message != "" ) ? retVal.message : "<?php esc_html_e( 'failure in update', 'usces' ); ?>";
					adminOperation.setActionStatus("error",message);
				}
			}).fail( function(jqXHR,textStatus,errorThrown) {
				console.log(textStatus);
				console.log(jqXHR.status);
				console.log(errorThrown.message);
				adminOperation.setActionStatus("error","<?php esc_html_e( 'failure in update', 'usces' ); ?>");
			});
			return false;
		}
	};

	$(document).on( "click", "#continuation-update", function() {
		var status = $("#dlseller-status option:selected").val();
		if( "continuation" == status ) {
			var year = $("#charged-year option:selected").val();
			var month = $("#charged-month option:selected").val();
			var day = $("#charged-day option:selected").val();
			if( 0 == year || 0 == month || 0 == day ) {
				alert("<?php esc_html_e( 'Data have deficiency.', 'usces' ); ?>");
				$("#charged-year").focus();
				return;
			}
			if( "" == $("#price").val() || 0 == parseFloat($("#price").val()) ) {
				alert("<?php printf( __( 'Input the %s', 'usces' ), esc_html__( 'Amount', 'dlseller' ) ); ?>");
				$("#price").focus();
				return;
			}
		}
		if( ! confirm("<?php esc_html_e( 'Are you sure you want to update the settings?', 'usces' ); ?>") ) {
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
	 * 決済オプション登録・更新
	 * usces_action_admin_settlement_update
	 */
	public function settlement_update() {
		global $usces;

		if ( filter_input( INPUT_POST, 'acting' ) !== $this->paymod_id ) {
			return;
		}

		$this->error_mes = '';
		$options         = get_option( 'usces', array() );
		$payment_method  = usces_get_system_option( 'usces_payment_method', 'settlement' );
		$post_data       = wp_unslash( $_POST );

		unset( $options['acting_settings']['zeus'] );
		$options['acting_settings']['zeus']['card_activate'] = ( isset( $post_data['card_activate'] ) ) ? $post_data['card_activate'] : '';
		$options['acting_settings']['zeus']['clientip']      = ( isset( $post_data['clientip'] ) ) ? trim( $post_data['clientip'] ) : '';
		$options['acting_settings']['zeus']['connection']    = ( isset( $post_data['connection'] ) ) ? $post_data['connection'] : 1;
		$options['acting_settings']['zeus']['3dsecur']       = ( isset( $post_data['3dsecur'] ) ) ? $post_data['3dsecur'] : 2;
		if ( isset( $post_data['3ds_pattern'] ) ) {
			$options['acting_settings']['zeus']['3ds_pattern'] = $post_data['3ds_pattern'];
		}
		$options['acting_settings']['zeus']['security'] = ( isset( $post_data['security'] ) ) ? $post_data['security'] : 2;
		if ( isset( $post_data['authkey'] ) ) {
			$options['acting_settings']['zeus']['authkey'] = trim( $post_data['authkey'] );
		}
		$options['acting_settings']['zeus']['quickcharge']          = ( isset( $post_data['quickcharge'] ) ) ? $post_data['quickcharge'] : '';
		$options['acting_settings']['zeus']['batch']                = ( isset( $post_data['batch'] ) ) ? $post_data['batch'] : '';
		$options['acting_settings']['zeus']['auto_settlement_mail'] = ( isset( $post_data['auto_settlement_mail'] ) ) ? $post_data['auto_settlement_mail'] : 'off';
		$options['acting_settings']['zeus']['howpay']               = ( isset( $post_data['howpay'] ) ) ? $post_data['howpay'] : '';
		$options['acting_settings']['zeus']['howpay_B1']            = ( isset( $post_data['howpay_B1'] ) ) ? $post_data['howpay_B1'] : '';
		$options['acting_settings']['zeus']['howpay_02']            = ( isset( $post_data['howpay_02'] ) ) ? $post_data['howpay_02'] : '';
		$options['acting_settings']['zeus']['bank_activate']        = ( isset( $post_data['bank_activate'] ) ) ? $post_data['bank_activate'] : '';
		$options['acting_settings']['zeus']['bank_ope']             = ( isset( $post_data['bank_ope'] ) ) ? $post_data['bank_ope'] : '';
		$options['acting_settings']['zeus']['clientip_bank']        = ( isset( $post_data['clientip_bank'] ) ) ? trim( $post_data['clientip_bank'] ) : '';
		$options['acting_settings']['zeus']['testid_bank']          = ( isset( $post_data['testid_bank'] ) ) ? trim( $post_data['testid_bank'] ) : '';
		$options['acting_settings']['zeus']['bank_expired_date']    = ( isset( $post_data['bank_expired_date'] ) ) ? trim( $post_data['bank_expired_date'] ) : '';
		$options['acting_settings']['zeus']['conv_activate']        = ( isset( $post_data['conv_activate'] ) ) ? $post_data['conv_activate'] : '';
		$options['acting_settings']['zeus']['conv_ope']             = ( isset( $post_data['conv_ope'] ) ) ? $post_data['conv_ope'] : '';
		$options['acting_settings']['zeus']['clientip_conv']        = ( isset( $post_data['clientip_conv'] ) ) ? trim( $post_data['clientip_conv'] ) : '';
		$options['acting_settings']['zeus']['testid_conv']          = ( isset( $post_data['testid_conv'] ) ) ? trim( $post_data['testid_conv'] ) : '';
		$options['acting_settings']['zeus']['test_type_conv']       = ( ( isset( $post_data['testid_conv'] ) && WCUtils::is_blank( $post_data['testid_conv'] ) ) || ( ! isset( $post_data['test_type'] ) ) ) ? 0 : $post_data['test_type'];
		$options['acting_settings']['zeus']['pay_cvs']              = ( isset( $post_data['pay_cvs'] ) ) ? $post_data['pay_cvs'] : array();
		$options['acting_settings']['zeus']['conv_span']            = ( isset( $post_data['conv_span'] ) ) ? trim( $post_data['conv_span'] ) : '';

		if ( 'on' === $options['acting_settings']['zeus']['card_activate'] ) {
			if ( WCUtils::is_blank( $post_data['clientip'] ) ) {
				$this->error_mes .= '※カード決済IPコードを入力してください<br />';
			}
			if ( isset( $post_data['authkey'] ) && WCUtils::is_blank( $post_data['authkey'] ) && isset( $post_data['security'] ) && 3 === (int) $post_data['security'] ) {
				$this->error_mes .= '※認証キーを入力してください<br />';
			}
			if ( isset( $post_data['batch'] ) && 'on' === $post_data['batch'] ) {
				if ( isset( $post_data['quickcharge'] ) && 'on' === $post_data['quickcharge'] ) {
				} else {
					$this->error_mes                                  .= '※バッチ処理を利用する場合は、QuickCharge を「利用する」にしてください<br />';
					$options['acting_settings']['zeus']['quickcharge'] = 'on';
				}
			}
		}
		if ( 'on' === $options['acting_settings']['zeus']['bank_activate'] ) {
			if ( WCUtils::is_blank( $post_data['clientip_bank'] ) ) {
				$this->error_mes .= '※銀行振込決済（入金おまかせサービス）IPコードを入力してください<br />';
			}
			if ( WCUtils::is_blank( $post_data['testid_bank'] ) && isset( $post_data['bank_ope'] ) && 'test' === $post_data['bank_ope'] ) {
				$this->error_mes .= '※銀行振込決済（入金おまかせサービス）テストIDを入力してください<br />';
			}
		}
		if ( 'on' === $options['acting_settings']['zeus']['conv_activate'] ) {
			if ( WCUtils::is_blank( $post_data['clientip_conv'] ) ) {
				$this->error_mes .= '※コンビニ決済IPコードを入力してください<br />';
			}
			if ( WCUtils::is_blank( $post_data['testid_conv'] ) && isset( $post_data['conv_ope'] ) && 'test' === $post_data['conv_ope'] ) {
				$this->error_mes .= '※コンビニ決済テストIDを入力してください<br />';
			}
			if ( empty( $post_data['pay_cvs'] ) ) {
				$this->error_mes .= '※コンビニ種類を選択してください<br />';
			}
		}
		if ( 'on' === $options['acting_settings']['zeus']['card_activate'] || 'on' === $options['acting_settings']['zeus']['bank_activate'] || 'on' === $options['acting_settings']['zeus']['conv_activate'] ) {
			$unavailable_activate = false;
			foreach ( $payment_method as $settlement => $payment ) {
				if ( in_array( $settlement, $this->unavailable_method ) && 'activate' === $payment['use'] ) {
					$unavailable_activate = true;
					break;
				}
			}
			if ( $unavailable_activate ) {
				$this->error_mes .= __( '* Settlement that can not be used together is activated.', 'usces' ) . '<br />';
			}
		}

		if ( '' === $this->error_mes ) {
			$usces->action_status  = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if ( 'on' === $options['acting_settings']['zeus']['card_activate'] || 'on' === $options['acting_settings']['zeus']['bank_activate'] || 'on' === $options['acting_settings']['zeus']['conv_activate'] ) {
				$options['acting_settings']['zeus']['activate'] = 'on';
				$options['acting_settings']['zeus']['ipaddrs']  = array( '210.164.6.67', '202.221.139.50' );
				$toactive                                       = array();
				if ( 'on' === $options['acting_settings']['zeus']['card_activate'] ) {
					$options['acting_settings']['zeus']['card_url']          = 'https://linkpt.cardservice.co.jp/cgi-bin/secure.cgi';
					$options['acting_settings']['zeus']['card_secureurl']    = 'https://linkpt.cardservice.co.jp/cgi-bin/secure/api.cgi';
					$options['acting_settings']['zeus']['card_tokenurl']     = 'https://linkpt.cardservice.co.jp/cgi-bin/token/token.cgi';
					$options['acting_settings']['zeus']['card_order_ref']    = 'https://linkpt.cardservice.co.jp/cgi-bin/order_ref.cgi';
					$options['acting_settings']['zeus']['card_price_change'] = 'https://linkpt.cardservice.co.jp/cgi-bin/credit/price_change/reflect/index.cgi';
					$usces->payment_structure['acting_zeus_card']            = 'カード決済（ZEUS）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_zeus_card' === $settlement && 'activate' !== $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_zeus_card'] );
				}
				if ( 'on' === $options['acting_settings']['zeus']['bank_activate'] ) {
					$options['acting_settings']['zeus']['bank_url'] = 'https://linkpt.cardservice.co.jp/cgi-bin/ebank.cgi';
					$usces->payment_structure['acting_zeus_bank']   = '銀行振込決済（ZEUS）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_zeus_bank' === $settlement && 'activate' !== $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_zeus_bank'] );
				}
				if ( 'on' === $options['acting_settings']['zeus']['conv_activate'] ) {
					$options['acting_settings']['zeus']['conv_url'] = 'https://linkpt.cardservice.co.jp/cgi-bin/cvs.cgi';
					$usces->payment_structure['acting_zeus_conv']   = 'コンビニ決済（ZEUS）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_zeus_conv' === $settlement && 'activate' !== $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_zeus_conv'] );
				}
				$options['acting_settings']['zeus']['vercheck'] = '115';
				usces_admin_orderlist_show_wc_trans_id();
				if ( 0 < count( $toactive ) ) {
					$usces->action_message .= __( 'Please update the payment method to "Activate". <a href="admin.php?page=usces_initial#payment_method_setting">General Setting > Payment Methods</a>', 'usces' );
				}
			} else {
				$options['acting_settings']['zeus']['activate'] = 'off';
				unset( $usces->payment_structure['acting_zeus_card'], $usces->payment_structure['acting_zeus_bank'], $usces->payment_structure['acting_zeus_conv'] );
			}
			if ( 'on' !== $options['acting_settings']['zeus']['quickcharge'] || 'off' === $options['acting_settings']['zeus']['activate'] ) {
				usces_clear_quickcharge( 'zeus_pcid' );
			}
			$deactivate = array();
			foreach ( $payment_method as $settlement => $payment ) {
				if ( ! array_key_exists( $settlement, $usces->payment_structure ) ) {
					if ( 'deactivate' !== $payment['use'] ) {
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
			$usces->action_status                           = 'error';
			$usces->action_message                          = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['zeus']['activate'] = 'off';
			unset( $usces->payment_structure['acting_zeus_card'], $usces->payment_structure['acting_zeus_bank'], $usces->payment_structure['acting_zeus_conv'] );
			$deactivate = array();
			foreach ( $payment_method as $settlement => $payment ) {
				if ( in_array( $settlement, $this->pay_method ) ) {
					if ( 'deactivate' !== $payment['use'] ) {
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
		$settlement_selected = get_option( 'usces_settlement_selected', array() );
		if ( in_array( 'zeus', (array) $settlement_selected, true ) ) {
			echo '<li><a href="#uscestabs_zeus">ゼウス</a></li>';
		}
	}

	/**
	 * クレジット決済設定画面フォーム
	 * usces_action_settlement_tab_body
	 */
	public function settlement_tab_body() {
		global $usces;

		$acting_opts         = $this->get_acting_settings();
		$settlement_selected = get_option( 'usces_settlement_selected', array() );
		if ( in_array( 'zeus', (array) $settlement_selected, true ) ) :
			$card_activate = ( isset( $acting_opts['card_activate'] ) && 'on' === $acting_opts['card_activate'] ) ? 'on' : 'off';
			$clientip      = ( isset( $acting_opts['clientip'] ) ) ? $acting_opts['clientip'] : '';
			$connection    = ( isset( $acting_opts['connection'] ) ) ? (int) $acting_opts['connection'] : 0;
			$authkey       = ( isset( $acting_opts['authkey'] ) ) ? $acting_opts['authkey'] : '';
			$threedsecure  = ( 2 === $connection && isset( $acting_opts['3dsecur'] ) && 1 === (int) $acting_opts['3dsecur'] ) ? 1 : 2;
			$pattern       = ( 2 === $connection && isset( $acting_opts['3ds_pattern'] ) ) ? $acting_opts['3ds_pattern'] : '';
			$security      = ( isset( $acting_opts['security'] ) && 1 === (int) $acting_opts['security'] ) ? 1 : 2;
			$quickcharge   = ( isset( $acting_opts['quickcharge'] ) && 'on' === $acting_opts['quickcharge'] ) ? $acting_opts['quickcharge'] : 'off';
			$batch         = ( isset( $acting_opts['batch'] ) && 'on' === $acting_opts['batch'] ) ? $acting_opts['batch'] : 'off';
			$howpay        = ( isset( $acting_opts['howpay'] ) && 'on' === $acting_opts['howpay'] ) ? $acting_opts['howpay'] : 'off';
			$howpay_b1     = ( isset( $acting_opts['howpay_B1'] ) && 'on' === $acting_opts['howpay_B1'] ) ? $acting_opts['howpay_B1'] : '';
			$howpay_02     = ( isset( $acting_opts['howpay_02'] ) && 'on' === $acting_opts['howpay_02'] ) ? $acting_opts['howpay_02'] : '';
			$wcex_name     = '';
			if ( defined( 'WCEX_DLSELLER' ) ) {
				$wcex_name = '自動継続課金';
			} elseif ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
				$wcex_name = '定期購入';
			}
			$auto_settlement_mail = ( isset( $acting_opts['auto_settlement_mail'] ) && 'on' === $acting_opts['auto_settlement_mail'] ) ? 'on' : 'off';

			$bank_activate     = ( isset( $acting_opts['bank_activate'] ) && 'on' === $acting_opts['bank_activate'] ) ? 'on' : 'off';
			$bank_ope          = ( isset( $acting_opts['bank_ope'] ) && 'public' === $acting_opts['bank_ope'] ) ? 'public' : 'test';
			$clientip_bank     = ( isset( $acting_opts['clientip_bank'] ) ) ? $acting_opts['clientip_bank'] : '';
			$testid_bank       = ( isset( $acting_opts['testid_bank'] ) ) ? $acting_opts['testid_bank'] : '';
			$bank_expired_date = ( isset( $acting_opts['bank_expired_date'] ) ) ? $acting_opts['bank_expired_date'] : '';

			$conv_activate  = ( isset( $acting_opts['conv_activate'] ) && 'on' === $acting_opts['conv_activate'] ) ? 'on' : 'off';
			$conv_ope       = ( isset( $acting_opts['conv_ope'] ) && 'public' === $acting_opts['conv_ope'] ) ? 'public' : 'test';
			$clientip_conv  = ( isset( $acting_opts['clientip_conv'] ) ) ? $acting_opts['clientip_conv'] : '';
			$testid_conv    = ( isset( $acting_opts['testid_conv'] ) ) ? $acting_opts['testid_conv'] : '';
			$test_type_conv = ( isset( $acting_opts['test_type_conv'] ) ) ? (int) $acting_opts['test_type_conv'] : 0;
			$pay_cvs        = ( isset( $acting_opts['pay_cvs'] ) && is_array( $acting_opts['pay_cvs'] ) ) ? $acting_opts['pay_cvs'] : array();
			$conv_span      = ( isset( $acting_opts['conv_span'] ) ) ? $acting_opts['conv_span'] : '';
			?>
	<div id="uscestabs_zeus">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
			<?php
			if ( 'zeus' === filter_input( INPUT_POST, 'acting' ) ) :
				if ( '' !== $this->error_mes ) :
					?>
		<div class="error_message"><?php echo wp_kses_post( $this->error_mes ); ?></div>
					<?php
				elseif ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) :
					?>
		<div class="message">十分にテストを行ってから運用してください。</div>
					<?php
				endif;
			endif;
			?>
	<form action="" method="post" name="zeus_form" id="zeus_form">
		<table class="settle_table">
			<tr>
				<th>クレジットカード決済</th>
				<td><label><input name="card_activate" type="radio" class="card_activate_zeus" id="card_activate_zeus_1" value="on"<?php checked( $card_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" class="card_activate_zeus" id="card_activate_zeus_2" value="off"<?php checked( $card_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_clid_zeus">カード決済IPコード</a></th>
				<td><input name="clientip" type="text" id="clid_zeus" value="<?php echo esc_attr( $clientip ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_clid_zeus" class="explanation card_zeus"><td colspan="2">契約時にゼウスから発行されるクレジットカード決済用のIPコード（半角数字）</td></tr>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_connection_zeus">接続方式</a></th>
				<td><label><input name="connection" type="radio" id="connection_zeus_1" value="1"<?php checked( $connection, 1 ); ?> /><span>Secure Link</span></label><br />
					<label><input name="connection" type="radio" id="connection_zeus_2" value="2"<?php checked( $connection, 2 ); ?> /><span>Secure API</span></label>
				</td>
			</tr>
			<tr id="ex_connection_zeus" class="explanation card_zeus"><td colspan="2">認証接続方法。契約に従って指定する必要があります。</td></tr>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_authkey_zeus">認証キー</a></th>
				<td><input name="authkey" type="text" id="clid_zeus" value="<?php echo esc_attr( $authkey ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_authkey_zeus" class="explanation card_zeus"><td colspan="2">契約時にゼウスから発行される認証キー（半角数字）</td></tr>
			<tr class="card_zeus 3dsecur_zeus">
				<th><a class="explanation-label" id="label_ex_3dsecur_zeus">3Dセキュア（※）</a></th>
				<td><label><input name="3dsecur" type="radio" id="3dsecur_zeus_1" value="1"<?php checked( $threedsecure, 1 ); ?> /><span>利用する</span></label><br />
					<label><input name="3dsecur" type="radio" id="3dsecur_zeus_2" value="2"<?php checked( $threedsecure, 2 ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_3dsecur_zeus" class="explanation card_zeus 3dsecur_zeus"><td colspan="2">3Dセキュアを利用するには Secure API を利用した接続が必要です。契約に従って指定する必要があります。</td></tr>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_security_zeus">セキュリティーコード（※）</a></th>
				<td><label><input name="security" type="radio" id="security_zeus_1" value="1"<?php checked( $security, 1 ); ?> /><span>利用する</span></label><br />
					<label><input name="security" type="radio" id="security_zeus_2" value="2"<?php checked( $security, 2 ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_security_zeus" class="explanation card_zeus"><td colspan="2">セキュリティーコードの入力を必須とするかどうかを指定します。契約に従って指定する必要があります。</td></tr>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_quickcharge_zeus">QuickCharge</a></th>
				<td><label><input name="quickcharge" type="radio" id="quickcharge_zeus_1" value="on"<?php checked( $quickcharge, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="quickcharge" type="radio" id="quickcharge_zeus_2" value="off"<?php checked( $quickcharge, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_quickcharge_zeus" class="explanation card_zeus"><td colspan="2">ログインして一度購入したメンバーは、次の購入時にはカード番号を入力する必要がなくなります。</td></tr>
			<?php if ( defined( 'WCEX_DLSELLER' ) || defined( 'WCEX_AUTO_DELIVERY' ) ) : ?>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_batch_zeus">バッチ処理</a></th>
				<td><label><input name="batch" type="radio" id="batch_zeus_1" value="on"<?php checked( $batch, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="batch" type="radio" id="batch_zeus_2" value="off"<?php checked( $batch, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_batch_zeus" class="explanation card_zeus"><td colspan="2">ゼウス決済を<?php echo esc_html( $wcex_name ); ?>でご利用の場合は、「利用する」にしてください。また、QuickCharge も「利用する」にしてください。</td></tr>
			<?php endif; ?>
			<?php if ( defined( 'WCEX_DLSELLER' ) ) : ?>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_auto_settlement_mail_zeus">自動継続課金完了メール</a></th>
				<td><label><input name="auto_settlement_mail" type="radio" id="auto_settlement_mail_zeus_on" value="on"<?php checked( $auto_settlement_mail, 'on' ); ?> /><span><?php esc_html_e( 'Send', 'usces' ); ?></span></label><br />
					<label><input name="auto_settlement_mail" type="radio" id="auto_settlement_mail_zeus_off" value="off"<?php checked( $auto_settlement_mail, 'off' ); ?> /><span><?php esc_html_e( "Don't send", 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_auto_settlement_mail_zeus" class="explanation card_zeus"><td colspan="2"><?php esc_html_e( 'Send billing completion mail to the member on which automatic continuing charging processing (required WCEX DLSeller) is executed.', 'usces' ); ?></td></tr>
			<?php endif; ?>
			<tr class="card_zeus">
				<th><a class="explanation-label" id="label_ex_howpay_zeus">お客様の支払方法</a></th>
				<td><label><input name="howpay" type="radio" id="howpay_zeus_2" value="off"<?php checked( $howpay, 'off' ); ?> /><span>一括払いのみ</span></label><br />
					<label><input name="howpay" type="radio" id="howpay_zeus_1" value="on"<?php checked( $howpay, 'on' ); ?> /><span>分割払いに対応する</span></label><br />
					<div class="howpay_option_zeus">
						<label><input name="howpay_B1" type="checkbox" id="howpay_zeus_B1" value="on"<?php checked( $howpay_b1, 'on' ); ?> /><span>ボーナス一括に対応する</span></label><br />
						<label><input name="howpay_02" type="checkbox" id="howpay_zeus_02" value="on"<?php checked( $howpay_02, 'on' ); ?> /><span>2回払いに対応する</span></label>
					</div/>
				</td>
			</tr>
			<tr id="ex_howpay_zeus" class="explanation card_zeus"><td colspan="2">お客様が利用するクレジットカードのカード会社により、選択できる分割回数が異なります。ボーナス一括、2回払いは契約がある場合にのみご利用いただけます。<?php if ( ! empty( $wcex_name ) ) echo esc_html( $wcex_name ) . '商品は常に「一括払い」で決済されます。'; ?></td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_bank_zeus">銀行振込決済（入金おまかせサービス）</a></th>
				<td><label><input name="bank_activate" type="radio" class="bank_activate_zeus" id="bank_activate_zeus_1" value="on"<?php checked( $bank_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="bank_activate" type="radio" class="bank_activate_zeus" id="bank_activate_zeus_2" value="off"<?php checked( $bank_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_bank_zeus" class="explanation"><td colspan="2">銀行振込支払いの自動照会機能です。振込みがあった場合、自動的に入金済みになり、入金確認メールが自動送信されます。</td></tr>
			<tr class="bank_zeus">
				<th><a class="explanation-label" id="label_ex_bank_ope_zeus">稼働環境</a></th>
				<td><label><input name="bank_ope" type="radio" id="bank_ope_zeus_1" value="test"<?php checked( $bank_ope, 'test' ); ?> /><span>テスト環境</span></label><br />
					<label><input name="bank_ope" type="radio" id="bank_ope_zeus_2" value="public"<?php checked( $bank_ope, 'public' ); ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_bank_ope_zeus" class="explanation bank_zeus"><td colspan="2">動作環境を切り替えます。</td></tr>
			<tr class="bank_zeus">
				<th><a class="explanation-label" id="label_ex_bank_clid_zeus">銀行振込決済IPコード</a></th>
				<td><input name="clientip_bank" type="text" id="bank_clid_zeus" value="<?php echo esc_attr( $clientip_bank ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_bank_clid_zeus" class="explanation bank_zeus"><td colspan="2">契約時にゼウスから発行される入金おまかせサービス用のIPコード（半角数字）</td></tr>
			<tr class="bank_zeus">
				<th><a class="explanation-label" id="label_ex_bank_testid_zeus">テストID</a></th>
				<td><input name="testid_bank" type="text" id="testid_bank_zeus" value="<?php echo esc_attr( $testid_bank ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_bank_testid_zeus" class="explanation bank_zeus"><td colspan="2">契約時にゼウスから発行される入金おまかせサービス接続テストで必要なテストID（半角数字）</td></tr>
			<tr class="bank_zeus">
				<th><a class="explanation-label" id="label_ex_bank_expired_date_zeus">支払期日</a></th>
				<td>当日＋<select name="bank_expired_date">
					<option value=""></option>
			<?php for ( $i = 1; $i <= 30; $i++ ) : ?>
					<option value="<?php echo esc_html( $i ); ?>"<?php selected( $bank_expired_date, $i, true ); ?>><?php echo esc_html( $i ); ?></option>
			<?php endfor; ?>
				</select>日</td>
			</tr>
			<tr id="ex_bank_expired_date_zeus" class="explanation"><td colspan="2">お申し込みごとに支払期日を設定することができます。設定がない場合には、予め設定していた加盟店指定の支払期日が設定されます。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_conv_zeus">コンビニ決済サービス</a></th>
				<td><label><input name="conv_activate" type="radio" class="conv_activate_zeus" id="conv_activate_zeus_1" value="on"<?php checked( $conv_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="conv_activate" type="radio" class="conv_activate_zeus" id="conv_activate_zeus_2" value="off"<?php checked( $conv_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_conv_zeus" class="explanation"><td colspan="2">コンビニ支払いができる決済サービスです。払い込みがあった場合、自動的に入金済みになります。</td></tr>
			<tr class="conv_zeus">
				<th><a class="explanation-label" id="label_ex_conv_ope_zeus">稼働環境</a></th>
				<td><label><input name="conv_ope" type="radio" id="conv_ope_zeus_1" value="test"<?php checked( $conv_ope, 'test' ); ?> /><span>テスト環境</span></label><br />
					<label><input name="conv_ope" type="radio" id="conv_ope_zeus_2" value="public"<?php checked( $conv_ope, 'public' ); ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_conv_ope_zeus" class="explanation conv_zeus"><td colspan="2">動作環境を切り替えます。</td></tr>
			<tr class="conv_zeus">
				<th><a class="explanation-label" id="label_ex_conv_clid_zeus">コンビニ決済IPコード</a></th>
				<td><input name="clientip_conv" type="text" id="conv_clid_zeus" value="<?php echo esc_attr( $clientip_conv ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_conv_clid_zeus" class="explanation conv_zeus"><td colspan="2">契約時にゼウスから発行されるコンビニ決済サービス用のIPコード（半角数字）</td></tr>
			<tr class="conv_zeus">
				<th><a class="explanation-label" id="label_ex_conv_testid_zeus">テストID</a></th>
				<td><input name="testid_conv" type="text" id="testid_conv_zeus" value="<?php echo esc_attr( $testid_conv ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_conv_testid_zeus" class="explanation conv_zeus"><td colspan="2">契約時にゼウスから発行されるコンビニ決済サービス接続テストで必要なテストID（半角数字）</td></tr>
			<tr class="conv_zeus">
				<th><a class="explanation-label" id="label_ex_conv_testtype_zeus">テストタイプ</a></th>
				<td><label><input name="test_type" type="radio" id="conv_testtype_zeus_1" value="0"<?php checked( $test_type_conv, 0 ); ?> /><span>入金テスト無し</span></label><br />
					<label><input name="test_type" type="radio" id="conv_testtype_zeus_2" value="1"<?php checked( $test_type_conv, 1 ); ?> /><span>売上確定テスト</span></label><br />
					<label><input name="test_type" type="radio" id="conv_testtype_zeus_3" value="2"<?php checked( $test_type_conv, 2 ); ?> /><span>売上取消テスト</span></label>
				</td>
			</tr>
			<tr id="ex_conv_testtype_zeus" class="explanation conv_zeus"><td colspan="2">テスト環境でのテストタイプを指定します。</td></tr>
			<tr class="conv_zeus">
				<th rowspan="6"><a class="explanation-label" id="label_ex_pay_cvs_zeus">コンビニ種類</a></th>
				<td><label><input name="pay_cvs[]" type="checkbox" id="pay_cvs_D001" value="D001"<?php checked( in_array( 'D001', $pay_cvs, true ), true ); ?> /><span><?php echo esc_html( usces_get_conv_name( 'D001' ) ); ?></span></label></td>
			</tr>
			<tr class="conv_zeus">
				<td><label><input name="pay_cvs[]" type="checkbox" id="pay_cvs_D002" value="D002"<?php checked( in_array( 'D002', $pay_cvs, true ), true ); ?> /><span><?php echo esc_html( usces_get_conv_name( 'D002' ) ); ?></span></label></td>
			</tr>
			<tr class="conv_zeus">
				<td><label><input name="pay_cvs[]" type="checkbox" id="pay_cvs_D030" value="D030"<?php checked( in_array( 'D030', $pay_cvs, true ), true ); ?> /><span><?php echo esc_html( usces_get_conv_name( 'D030' ) ); ?></span></label></td>
			</tr>
			<tr class="conv_zeus">
				<td><label><input name="pay_cvs[]" type="checkbox" id="pay_cvs_D015" value="D015"<?php checked( in_array( 'D015', $pay_cvs, true ), true ); ?> /><span><?php echo esc_html( usces_get_conv_name( 'D015' ) ); ?></span></label></td>
			</tr>
			<tr class="conv_zeus">
				<td><label><input name="pay_cvs[]" type="checkbox" id="pay_cvs_D050" value="D050"<?php checked( in_array( 'D050', $pay_cvs, true ), true ); ?> /><span><?php echo esc_html( usces_get_conv_name( 'D050' ) ); ?></span></label></td>
			</tr>
			<tr class="conv_zeus">
				<td><label><input name="pay_cvs[]" type="checkbox" id="pay_cvs_D060" value="D060"<?php checked( in_array( 'D060', $pay_cvs, true ), true ); ?> /><span><?php echo esc_html( usces_get_conv_name( 'D060' ) ); ?></span></label></td>
			</tr>
			<tr id="ex_pay_cvs_zeus" class="explanation conv_zeus"><td colspan="2">契約時にご利用のお申込みをいただいたコンビニを選択します。</td></tr>
			<tr class="conv_zeus">
				<th><a class="explanation-label" id="label_ex_conv_span_zeus">支払期日</a></th>
				<td>当日＋<select name="conv_span">
					<option value=""></option>
			<?php for ( $i = 2; $i <= 59; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>"<?php selected( $conv_span, $i, true ); ?>><?php echo esc_html( $i ); ?></option>
			<?php endfor; ?>
				</select>日</td>
			</tr>
			<tr id="ex_conv_span_zeus" class="explanation"><td colspan="2">お申し込みごとに支払期日を設定することができます。設定がない場合には、予め設定していた加盟店指定の支払期日が設定されます。</td></tr>
		</table>
		<input name="acting" type="hidden" value="zeus" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="ゼウスの設定を更新する" />
			<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php esc_html_e( 'ZEUS Japanese Settlement', 'usces' ); ?></strong></p>
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank">ゼウス決済サービスの詳細はこちら 》</a>
		<p>　</p>
		<p>この決済は「非通過型・トークン方式」の決済システムです。</p>
		<p>「非通過型」とは、決済会社のページへは遷移せず、Welcart のページのみで完結する決済システムです。<br />
		デザインの統一されたスタイリッシュな決済が可能です。但し、カード番号を扱いますので専用SSLが必須となります。<br />
		入力されたカード番号はトークンに置き換えてゼウスのシステムに送信されますので、Welcart に保存することはありません。</a>
		<p>※ボーナス一括、2回払いは契約がある場合にのみご利用いただけます。</p>
		<p>　</p>
		<p>※ 3Dセキュアとセキュリティーコード</p>
		<p>3Dセキュアとおよびセキュリティーコードの利用は、決済サービス契約時に決定します。契約内容に従って指定しないと正常に動作しませんのでご注意ください。<br />
		3Dセキュアを利用する場合はテストカードを使用してのテスト決済はできません。<br />
		詳しくは<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank">株式会社ゼウス</a>（代表：03-3498-9030）にお問い合わせください。</p>
		<p>　</p>
		<p><strong>テスト稼動について</strong></p>
		<p>銀行振込決済（入金おまかせサービス）およびコンビニ決済のテストを行う際は、「稼働環境」で「テスト環境」を選択し、「テストID」の項目にゼウスから発行されるテストIDを入力してください。<br />
		また、本稼働の際には、「本番環境」を選択して更新してください。</p>
	</div>
	</div><!--uscestabs_zeus-->
			<?php
		endif;
	}

	/**
	 * 結果通知前処理
	 * usces_construct
	 */
	public function acting_construct() {
		$acting_opts = $this->get_acting_settings();
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) && in_array( $_SERVER['REMOTE_ADDR'], $acting_opts['ipaddrs'] ) ) {
			$result_data = wp_unslash( $_REQUEST );
			if ( isset( $result_data['sendpoint'] ) ) {
				$sendpoint   = $result_data['sendpoint'];
				$acting_data = usces_get_order_acting_data( $sendpoint );
				if ( empty( $acting_data['sesid'] ) ) {
					if ( isset( $result_data['result'] ) && 'OK' === $result_data['result'] && isset( $result_data['money'] ) && 0 === (int) $result_data['money'] ) {
					} else {
						$log = array(
							'acting' => 'zeus',
							'key'    => $sendpoint,
							'result' => 'SESSION ERROR',
							'data'   => $result_data,
						);
						usces_save_order_acting_error( $log );
					}
				} else {
					if ( isset( $result_data['acting'] ) && 'zeus_bank' === $result_data['acting'] ) {
						usces_restore_order_acting_data( $sendpoint );
					}
				}
			}
		}
	}

	/**
	 * 結果通知処理
	 * usces_after_cart_instant
	 */
	public function acting_transaction() {
		global $usces;

		/* zeus_card */
		if ( isset( $_REQUEST['acting'] ) && 'zeus_card' === $_REQUEST['acting'] && isset( $_REQUEST['result'] ) && isset( $_REQUEST['ordd'] ) ) {
			foreach ( $_REQUEST as $key => $value ) {
				if ( 'uscesid' === $key ) {
					continue;
				}
				$data[ $key ] = $value;
			}

			if ( empty( $data['sendpoint'] ) ) {
				$log = array(
					'acting' => $data['acting'],
					'key'    => '(empty key)',
					'result' => $data['result'],
					'data'   => $data,
				);
				usces_save_order_acting_error( $log );
				header( 'HTTP/1.0 200 OK' );
				die( 'error1' );
			}

			if ( 'OK' === $data['result'] ) {
				$order_data = usces_restore_order_acting_data( $data['sendpoint'] );
				$order_id   = $this->get_order_id( $data['sendpoint'] );
				if ( empty( $order_id ) && ! empty( $order_data ) ) {
					$res = $usces->order_processing( $data );
					if ( 'ordercompletion' === $res ) {
						$_nonce = ( isset( $order_data['_nonce'] ) ) ? $order_data['_nonce'] : wp_create_nonce( 'acting_zeus_card' );
						wp_redirect(
							add_query_arg(
								array(
									'acting'        => $data['acting'],
									'acting_return' => 1,
									'result'        => 1,
									'_nonce'        => $_nonce,
								),
								USCES_CART_URL
							)
						);
					} else {
						$log = array(
							'acting' => $data['acting'],
							'key'    => $rand,
							'result' => 'ORDER DATA REGISTERED ERROR',
							'data'   => $data,
						);
						usces_save_order_acting_error( $log );
						wp_redirect(
							add_query_arg(
								array(
									'acting'        => $data['acting'],
									'acting_return' => 0,
									'result'        => 0,
								),
								USCES_CART_URL
							)
						);
					}
				} else {
					$log = array(
						'acting' => $data['acting'],
						'key'    => $data['sendpoint'],
						'result' => 'ORDER DATA RESTORE ERROR',
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => $data['acting'],
								'acting_return' => 0,
								'result'        => 0,
							),
							USCES_CART_URL
						)
					);
				}
				header( 'HTTP/1.0 200 OK' );
				die( 'zeus' );

			} else {
				$log = array(
					'acting' => $data['acting'],
					'key'    => $data['sendpoint'],
					'result' => $data['result'],
					'data'   => $data,
				);
				usces_save_order_acting_error( $log );
				header( 'HTTP/1.0 200 OK' );
				die( 'error3' );
			}

			/* zeus_bank */
		} elseif ( isset( $_REQUEST['acting'] ) && 'zeus_bank' === $_REQUEST['acting'] && isset( $_REQUEST['order_no'] ) && isset( $_REQUEST['tracking_no'] ) ) {
			foreach ( $_REQUEST as $key => $value ) {
				if ( 'uscesid' === $key ) {
					continue;
				}
				$data[ $key ] = $value;
			}

			if ( '04' === $data['status'] || '05' === $data['status'] ) {
				$log = array(
					'acting' => $data['acting'],
					'key'    => $data['sendpoint'],
					'result' => $data['status'],
					'data'   => $data,
				);
				usces_save_order_acting_error( $log );
				header( 'HTTP/1.0 200 OK' );
				die( 'error0' );
			}

			$order_id = $this->get_order_id( $data['tracking_no'] );
			if ( empty( $order_id ) ) {
				$res = $usces->order_processing( $data );
				if ( 'error' === $res ) {
					$log = array(
						'acting' => $data['acting'],
						'key'    => $data['sendpoint'],
						'result' => 'ORDER DATA REGISTERED ERROR',
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					header( 'HTTP/1.0 200 OK' );
					die( 'error1' );

				} else {
					$order_id = $usces->cart->get_order_entry( 'ID' );
					if ( $order_id ) {
						$usces->cart->clear_cart();
					}
				}
			}

			if ( ! empty( $order_id ) ) {
				if ( '03' === $data['status'] ) {
					$res = usces_change_order_receipt( $order_id, 'receipted' );
				} else {
					$res = usces_change_order_receipt( $order_id, 'noreceipt' );
				}
				if ( false === $res ) {
					$log = array(
						'acting' => $data['acting'],
						'key'    => $data['sendpoint'],
						'result' => 'ORDER DATA UPDATE ERROR',
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					header( 'HTTP/1.0 200 OK' );
					die( 'error2' );
				}
				if ( '03' === $data['status'] ) {
					usces_action_acting_getpoint( $order_id );
				}

				$res = $usces->set_order_meta_value( 'acting_' . $data['tracking_no'], usces_serialize( $data ), $order_id );
				if ( false === $res ) {
					$log = array(
						'acting' => $data['acting'],
						'key'    => $data['sendpoint'],
						'result' => 'ORDER META DATA UPDATE ERROR',
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					header( 'HTTP/1.0 200 OK' );
					die( 'error3' );
				}
			}

			header( 'HTTP/1.0 200 OK' );
			die( 'zeus' );

			/* zeus_conv */
		} elseif ( isset( $_REQUEST['acting'] ) && 'zeus_conv' === $_REQUEST['acting'] && isset( $_REQUEST['status'] ) && isset( $_REQUEST['sendpoint'] ) && isset( $_REQUEST['clientip'] ) ) {
			foreach ( $_REQUEST as $key => $value ) {
				if ( 'uscesid' === $key ) {
					continue;
				}
				if ( 'username' === $key ) {
					$data[ $key ] = mb_convert_encoding( $value, 'UTF-8', 'SJIS' );
				} else {
					$data[ $key ] = $value;
				}
			}

			$order_id = $this->get_order_id( $data['sendpoint'] );
			if ( ! empty( $order_id ) ) {
				if ( '05' !== $data['status'] ) {
					if ( '04' === $data['status'] ) {
						$res = usces_change_order_receipt( $order_id, 'receipted' );
					} else {
						$res = usces_change_order_receipt( $order_id, 'noreceipt' );
					}
					if ( false === $res ) {
						$log = array(
							'acting' => $data['acting'],
							'key'    => $data['sendpoint'],
							'result' => 'ORDER DATA UPDATE ERROR',
							'data'   => $data,
						);
						usces_save_order_acting_error( $log );
						header( 'HTTP/1.0 200 OK' );
						die( 'error2' );
					}
					if ( '04' === $data['status'] ) {
						usces_action_acting_getpoint( $order_id );
					}

					$res = $usces->set_order_meta_value( 'acting_' . $data['sendpoint'], usces_serialize( $data ), $order_id );
					if ( false === $res ) {
						$log = array(
							'acting' => $data['acting'],
							'key'    => $data['sendpoint'],
							'result' => 'ORDER META DATA UPDATE ERROR',
							'data'   => $data,
						);
						usces_save_order_acting_error( $log );
						header( 'HTTP/1.0 200 OK' );
						die( 'error3' );
					}
				}
			}

			header( 'HTTP/1.0 200 OK' );
			die( 'zeus' );
		}

		if ( isset( $_REQUEST['backfrom_zeus_bank'] ) && '1' === $_REQUEST['backfrom_zeus_bank'] ) {
			$usces->cart->clear_cart();
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
		global $usces;

		switch ( $payment['settlement'] ) {
			case 'acting_zeus_card':
				$div_name    = '';
				$acting_opts = $this->get_acting_settings();
				if ( 'on' === $acting_opts['howpay'] ) {
					$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_zeus_card', $order_id ) );
					$howpay      = ( isset( $acting_data['howpay'] ) ) ? $acting_data['howpay'] : '1';
					$div         = ( isset( $acting_data['div'] ) ) ? $acting_data['div'] : '01';
					if ( '1' == $howpay ) {
						$div_name = '　一括払い';
					} else {
						switch ( $div ) {
							case '01':
								$div_name = '　一括払い';
								break;
							case '02':
								$div_name = '　分割（2回）';
								break;
							case '03':
								$div_name = '　分割（3回）';
								break;
							case '05':
								$div_name = '　分割（5回）';
								break;
							case '06':
								$div_name = '　分割（6回）';
								break;
							case '10':
								$div_name = '　分割（10回）';
								break;
							case '12':
								$div_name = '　分割（12回）';
								break;
							case '15':
								$div_name = '　分割（15回）';
								break;
							case '18':
								$div_name = '　分割（18回）';
								break;
							case '20':
								$div_name = '　分割（20回）';
								break;
							case '24':
								$div_name = '　分割（24回）';
								break;
							case '99':
								$div_name = '　分割（リボ払い）';
								break;
							case 'B1':
								$div_name = '　分割（ボーナス一括払い）';
								break;
						}
					}
				}
				if ( '' !== $div_name && isset( $payment['name'] ) ) {
					if ( usces_is_html_mail() ) {
						$msg_payment  = '<tr><td colspan="2" style="padding: 0 0 25px 0;">';
						$msg_payment .= $payment['name'] . $div_name;
						$msg_payment .= '</td></tr>';
					} else {
						$msg_payment  = __( '** Payment method **', 'usces' ) . "\r\n";
						$msg_payment .= usces_mail_line( 1, $data['order_email'] ); // ********************
						$msg_payment .= $payment['name'] . $div_name;
						$msg_payment .= "\r\n\r\n";
					}
				}
				break;

			case 'acting_zeus_bank':
				break;

			case 'acting_zeus_conv':
				$conv_name   = '';
				$acting_data = $this->get_order_meta_acting( $order_id );
				if ( isset( $acting_data['pay_cvs'] ) ) {
					$conv_name = usces_get_conv_name( $acting_data['pay_cvs'] );
				}
				if ( '' !== $conv_name && isset( $payment['name'] ) ) {
					if ( usces_is_html_mail() ) {
						$msg_payment  = '<tr><td colspan="2" style="padding: 0 0 25px 0;">';
						$msg_payment .= $payment['name'] . '　（' . $conv_name . '）';
						$msg_payment .= '</td></tr>';
					} else {
						$msg_payment  = __( '** Payment method **', 'usces' ) . "\r\n";
						$msg_payment .= usces_mail_line( 1, $data['order_email'] ); // ********************
						$msg_payment .= $payment['name'] . '　（' . $conv_name . '）';
						$msg_payment .= "\r\n\r\n";
					}
				}
				break;
		}

		return $msg_payment;
	}

	/**
	 * ポイント即時付与
	 * usces_filter_is_complete_settlement
	 *
	 * @param  bool   $complete Complete the payment.
	 * @param  string $payment_name Payment name.
	 * @param  string $status Payment status.
	 * @return bool
	 */
	public function is_complete_settlement( $complete, $payment_name, $status ) {
		$payment = usces_get_payments_by_name( $payment_name );
		if ( isset( $payment['settlement'] ) && 'acting_zeus_card' === $payment['settlement'] ) {
			$complete = true;
		}
		return $complete;
	}

	/**
	 * 購入完了メッセージ
	 * usces_filter_completion_settlement_message
	 *
	 * @param  string $form Message form.
	 * @param  array  $entry Entry data.
	 * @return string
	 */
	public function completion_settlement_message( $form, $entry ) {
		global $usces;

		if ( isset( $_REQUEST['acting'] ) && 'zeus_conv' === wp_unslash( $_REQUEST['acting'] ) ) {
			$form .= '<div id="status_table"><h5>ゼウス・コンビニ決済</h5>';
			$form .= '<table>';
			$form .= '<tr><th>オーダー番号</th><td>' . esc_html( $usces->payment_results['order_no'] ) . '</td></tr>';
			$form .= '<tr><th>お支払先</th><td>' . esc_html( usces_get_conv_name( $usces->payment_results['pay_cvs'] ) ) . '</td></tr>';
			switch ( $usces->payment_results['pay_cvs'] ) {
				case 'D001': /* セブンイレブン */
					$form .= '<tr><th>払込票番号</th><td>' . esc_html( $usces->payment_results['pay_no1'] ) . '</td></tr>';
					$form .= '<tr><th>URL</th><td><a href="' . esc_attr( $usces->payment_results['pay_url'] ) . '" target="_blank">' . esc_html( $usces->payment_results['pay_url'] ) . '</a></td></tr>';
					break;
				case 'D002': /* ローソン */
				case 'D050': /* ミニストップ */
					$form .= '<tr><th>受付番号</th><td>' . esc_html( $usces->payment_results['pay_no1'] ) . '</td></tr>';
					if ( isset( $usces->payment_results['pay_no2'] ) ) {
						$form .= '<tr><th>確認番号</th><td>' . esc_html( $usces->payment_results['pay_no2'] ) . '</td></tr>';
					}
					break;
				case 'D040': /* サークルKサンクス */
				case 'D015': /* セイコーマート */
					$form .= '<tr><th>お支払受付番号</th><td>' . esc_html( $usces->payment_results['pay_no1'] ) . '</td></tr>';
					break;
				case 'D030': /* ファミリーマート */
					$form .= '<tr><th>注文番号</th><td>' . esc_html( $usces->payment_results['pay_no1'] ) . '</td></tr>';
					$form .= '<tr><th>企業コード</th><td>' . esc_html( $usces->payment_results['pay_no2'] ) . '</td></tr>';
					break;
				case 'D060': /* デイリーヤマザキ */
					$form .= '<tr><th>オンライン決済番号</th><td>' . esc_html( $usces->payment_results['pay_no1'] ) . '</td></tr>';
					break;
			}
			$form .= '<tr><th>お支払期限</th><td>' . esc_html( substr( $usces->payment_results['pay_limit'], 0, 4 ) . '年' . substr( $usces->payment_results['pay_limit'], 4, 2 ) . '月' . substr( $usces->payment_results['pay_limit'], 6, 2 ) . '日' ) . '（期限を過ぎますとお支払ができません）</td></tr>';
			// $form .= '<!-- <tr><th>エラーコード</th><td>' . esc_html( $usces->payment_results['error_code'] ) . '</td></tr>';
			$form .= '</table>';
			$form .= '<p>「お支払いのご案内」は、' . esc_html( $entry['customer']['mailaddress1'] ) . '　宛にメールさせていただいております。</p>';
			$form .= '</div>';
		}
		return $form;
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
		if ( isset( $results['order_number'] ) && isset( $results['sendpoint'] ) ) {
			$linkkey = $results['sendpoint'];
		}
		return $linkkey;
	}

	/**
	 * 受注データ復旧処理
	 * usces_action_revival_order_data
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $log_key Link key.
	 * @param  string $acting Payment type.
	 */
	public function revival_orderdata( $order_id, $log_key, $acting ) {
		global $usces;

		if ( ! in_array( $acting, $this->pay_method, true ) ) {
			return;
		}

		$data = array();
		switch ( $acting ) {
			case 'acting_zeus_card':
				$data['sendpoint'] = $log_key;
				$usces->set_order_meta_value( $acting, usces_serialize( $data ), $order_id );
			case 'acting_zeus_conv':
			case 'acting_zeus_bank':
				$usces->set_order_meta_value( 'acting_' . $log_key, usces_serialize( $data ), $order_id );
				break;
		}
	}

	/**
	 * 管理画面決済処理
	 * usces_action_admin_ajax
	 */
	public function admin_ajax() {
		global $usces;

		$mode = filter_input( INPUT_POST, 'mode' );
		$data = array();

		switch ( $mode ) {
			/* クレジットカード参照 */
			case 'get_zeus_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id    = filter_input( INPUT_POST, 'order_id' );
				$order_num   = filter_input( INPUT_POST, 'order_num' );
				$tracking_id = filter_input( INPUT_POST, 'tracking_id' );
				$member_id   = filter_input( INPUT_POST, 'member_id' );
				if ( empty( $order_id ) || empty( $tracking_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				$res    = '';
				$status = '';

				$latest_log = $this->get_acting_latest_log( $order_id, $tracking_id );
				if ( ! empty( $latest_log['order_no'] ) ) {
					$order_ref = wel_zeus_get_order_ref( $latest_log['order_no'] );
					if ( 'TEST' === $order_ref['status'] ) {
						$status      = 'OK';
						$status_name = $this->get_status_name( $order_ref['status'] );
						$res        .= '<div class="zeus-settlement-admin card-test">' . $status_name . '</div>';
						$res        .= '<table class="settlement-admin-table">';
						$res        .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res        .= '<td><input type="tel" class="settlement-amount" value="' . intval( $latest_log['amount'] ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
						$res        .= '</tr>';
						$res        .= '</table>';
					} elseif ( 'payment' === $order_ref['status'] || 'change' === $order_ref['status'] ) {
						$status      = 'OK';
						$status_name = $this->get_status_name( $order_ref['status'] );
						$res        .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
						$res        .= '<table class="settlement-admin-table">';
						$res        .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res        .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $latest_log['amount'] ) . '" /></td>';
						$res        .= '</tr>';
						$res        .= '</table>';
						$res        .= '<div class="settlement-admin-button">';
						$res        .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
						$res        .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
						$res        .= '</div>';
					} elseif ( 'auth' === $order_ref['status'] ) {
						$status      = 'OK';
						$status_name = $this->get_status_name( $order_ref['status'] );
						$res        .= '<div class="zeus-settlement-admin card-auth">' . $status_name . '</div>';
						$res        .= '<table class="settlement-admin-table">';
						$res        .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res        .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $latest_log['amount'] ) . '" /></td>';
						$res        .= '</tr>';
						$res        .= '</table>';
						$res        .= '<div class="settlement-admin-button">';
						$res        .= '<input id="sale_settlement" type="button" class="button" value="売上処理" />';
						$res        .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
						$res        .= '</div>';
					} elseif ( 'cancel' === $order_ref['status'] ) {
						$status      = 'OK';
						$status_name = $this->get_status_name( $order_ref['status'] );
						$res        .= '<div class="zeus-settlement-admin card-cancel">' . $status_name . '</div>';
						$res        .= '<table class="settlement-admin-table">';
						if ( ! empty( $member_id ) ) {
							$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
							$res .= '</tr>';
							$res .= '</table>';
							$res .= '<div class="settlement-admin-button">';
							$res .= '<input id="re_settlement" type="button" class="button" value="新規決済" />';
							$res .= '</div>';
						} else {
							$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res .= '<td><input type="tel" class="settlement-amount" value="' . intval( $latest_log['amount'] ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
							$res .= '</tr>';
							$res .= '</table>';
						}
					} else {
						if ( 'TEST' === $latest_log['status'] ) {
							$status      = 'OK';
							$status_name = $this->get_status_name( $latest_log['status'] );
							$res        .= '<div class="zeus-settlement-admin card-test">' . $status_name . '</div>';
							$res        .= '<table class="settlement-admin-table">';
							$res        .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res        .= '<td><input type="tel" class="settlement-amount" value="' . intval( $latest_log['amount'] ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
							$res        .= '</tr>';
							$res        .= '</table>';
						} elseif ( 'payment' === $latest_log['status'] || 'change' === $latest_log['status'] ) {
							$status      = 'OK';
							$status_name = $this->get_status_name( 'payment' );
							$res        .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
							$res        .= '<table class="settlement-admin-table">';
							$res        .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res        .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $latest_log['amount'] ) . '" /></td>';
							$res        .= '</tr>';
							$res        .= '</table>';
							$res        .= '<div class="settlement-admin-button">';
							$res        .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
							$res        .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
							$res        .= '</div>';
						} elseif ( 'auth' === $latest_log['status'] ) {
							$status      = 'OK';
							$status_name = $this->get_status_name( $latest_log['status'] );
							$res        .= '<div class="zeus-settlement-admin card-auth">' . $status_name . '</div>';
							$res        .= '<table class="settlement-admin-table">';
							$res        .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res        .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $latest_log['amount'] ) . '" /></td>';
							$res        .= '</tr>';
							$res        .= '</table>';
							$res        .= '<div class="settlement-admin-button">';
							$res        .= '<input id="sale_settlement" type="button" class="button" value="売上処理" />';
							$res        .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
							$res        .= '</div>';
						} elseif ( 'cancel' === $latest_log['status'] ) {
							$status      = 'OK';
							$status_name = $this->get_status_name( $latest_log['status'] );
							$res        .= '<div class="zeus-settlement-admin card-cancel">' . $status_name . '</div>';
							$res        .= '<table class="settlement-admin-table">';
							if ( ! empty( $member_id ) ) {
								$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
								$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
								$res .= '</tr>';
								$res .= '</table>';
								$res .= '<div class="settlement-admin-button">';
								$res .= '<input id="re_settlement" type="button" class="button" value="新規決済" />';
								$res .= '</div>';
							} else {
								$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
								$res .= '<td><input type="tel" class="settlement-amount" value="' . intval( $latest_log['amount'] ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
								$res .= '</tr>';
								$res .= '</table>';
							}
						} else {
							$status      = 'ERROR';
							$status_name = $this->get_status_name( 'error' );
							$res        .= '<div class="zeus-settlement-admin card-error">' . $status_name . '</div>';
						}
					}
				} else {
					$latest_log = $this->get_acting_latest_log( $order_id, $tracking_id, 'ALL' );
					if ( 'autodelivery' === $latest_log['status'] || 'dlseller' === $latest_log['status'] ) {
						$status      = 'OK';
						$status_name = $this->get_status_name( $latest_log['status'] );
						$res        .= '<div class="zeus-settlement-admin card-error">' . $status_name . '</div>';
						$res        .= '<table class="settlement-admin-table">';
						if ( ! empty( $member_id ) ) {
							$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
							$res .= '</tr>';
							$res .= '</table>';
							$res .= '<div class="settlement-admin-button">';
							$res .= '<input id="re_settlement" type="button" class="button" value="新規決済" />';
							$res .= '</div>';
						} else {
							$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res .= '<td><input type="tel" class="settlement-amount" value="' . intval( $latest_log['amount'] ) . '" disabled="disabled" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
							$res .= '</tr>';
							$res .= '</table>';
						}
					} else {
						$status      = 'ERROR';
						$status_name = $this->get_status_name( $latest_log['status'], $latest_log['result'] );
						$res        .= '<div class="zeus-settlement-admin card-error">' . $status_name . '</div>';
					}
				}
				$res           .= $this->settlement_history( $order_id, $tracking_id );
				$data['result'] = $res;
				$data['status'] = $status;
				wp_send_json( $data );
				break;

			/* クレジットカード売上処理 */
			case 'sale_zeus_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id    = filter_input( INPUT_POST, 'order_id' );
				$order_num   = filter_input( INPUT_POST, 'order_num' );
				$tracking_id = filter_input( INPUT_POST, 'tracking_id' );
				$amount      = filter_input( INPUT_POST, 'amount' );
				if ( empty( $order_id ) || empty( $tracking_id ) || empty( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				$res           = '';
				$status        = '';
				$acting_status = '';

				$latest_log = $this->get_acting_latest_log( $order_id, $tracking_id );
				if ( ! empty( $latest_log['order_no'] ) ) {
					$acting_opts = $this->get_acting_settings();

					$params             = array();
					$params['clientip'] = $acting_opts['clientip'];
					$params['king']     = $amount;
					$params['date']     = wp_date( 'Ymd' );
					$params['ordd']     = $latest_log['order_no'];
					$params['autype']   = 'sale';

					$page_sale = $this->secure_link_batch( $acting_opts['card_url'], $params );
					if ( false !== strpos( $page_sale, 'Success_order' ) ) {
						$status = 'OK';
						wel_zeus_save_acting_log( $params, 'zeus_card', 'sale', $status, $order_id, $tracking_id );
						$status_name   = $this->get_status_name( 'sale' );
						$res          .= '<div class="zeus-settlement-admin card-sale">' . $status_name . '</div>';
						$res          .= '<table class="settlement-admin-table">';
						$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
						$res          .= '</tr>';
						$res          .= '</table>';
						$res          .= '<div class="settlement-admin-button">';
						$res          .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
						$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
						$res          .= '</div>';
						$acting_status = '<span class="acting-status card-sale">' . $status_name . '</span>';
					} else {
						$status = $this->get_error_results( $page_sale );
						if ( empty( $status ) ) {
							$status = 'ERROR';
						}
						wel_zeus_save_acting_log( $params, 'zeus_card', 'sale', $status, $order_id, $tracking_id );
						$res .= '<div class="zeus-settlement-admin card-error">売上処理エラー</div>';
						$res .= '<table class="settlement-admin-table">';
						$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $latest_log['amount'] ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $latest_log['amount'] ) . '" /></td>';
						$res .= '</tr>';
						$res .= '</table>';
						$res .= '<div class="settlement-admin-button">';
						$res .= '<input id="sale_settlement" type="button" class="button" value="売上処理" />';
						$res .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
						$res .= '</div>';
					}
				} else {
					$status = 'NG';
					wel_zeus_save_acting_log( array(), 'zeus_card', 'sale', $status, $order_id, $tracking_id );
					$res .= '<div class="zeus-settlement-admin card-error">売上処理不可</div>';
				}
				$res                  .= $this->settlement_history( $order_id, $tracking_id );
				$data['result']        = $res;
				$data['status']        = $status;
				$data['acting_status'] = $acting_status;
				wp_send_json( $data );
				break;

			/* クレジットカード取消処理 */
			case 'cancel_zeus_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id    = filter_input( INPUT_POST, 'order_id' );
				$order_num   = filter_input( INPUT_POST, 'order_num' );
				$tracking_id = filter_input( INPUT_POST, 'tracking_id' );
				if ( empty( $order_id ) || empty( $tracking_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				$res           = '';
				$status        = '';
				$acting_status = '';

				$latest_log = $this->get_acting_latest_log( $order_id, $tracking_id );
				if ( ! empty( $latest_log['order_no'] ) ) {
					$acting_opts = $this->get_acting_settings();
					/**
					 * Cancel API
					$params                               = array();
					$params['authentication']['clientip'] = $acting_opts['clientip'];
					$params['authentication']['key']      = $acting_opts['authkey'];
					$params['return']['order_number']     = $latest_log['order_no'];

					$cancel_req  = '<?xml version="1.0" encoding="utf-8"?>';
					$cancel_req .= '<request service="secure_link" action="payment_return">';
					$cancel_req .= $this->assoc2xml( $params );
					$cancel_req .= '</request>';
					$xml         = $this->get_xml( $acting_opts['card_secureurl'], $cancel_req );
					if ( ! empty( $xml ) ) {
						$cancel_res = $this->xml2assoc( $xml );
						if ( 'success' === $cancel_res['response']['result']['status'] ) {
							$status                 = 'OK';
							$cancel_res['order_no'] = $latest_log['order_no'];
							wel_zeus_save_acting_log( $cancel_res, 'zeus_card', 'cancel', 'OK', $order_id, $tracking_id );
						} else {
							$status                 = 'ERROR';
							$cancel_res['order_no'] = $latest_log['order_no'];
							wel_zeus_save_acting_log( $cancel_res, 'zeus_card', 'cancel', $cancel_res['response']['result']['status'], $order_id, $tracking_id );
						}
					} else {
						$status = 'NG';
						wel_zeus_save_acting_log( $params, 'zeus_card', 'cancel', 'NG', $order_id, $tracking_id );
					}
					 */
					$params             = array();
					$params['clientip'] = $acting_opts['clientip'];
					$params['return']   = 'yes';
					$params['ordd']     = $latest_log['order_no'];

					$page_cancel = $this->secure_link_batch( $acting_opts['card_url'], $params );
					if ( false !== strpos( $page_cancel, 'SuccessOK' ) ) {
						$status = 'OK';
						wel_zeus_save_acting_log( $params, 'zeus_card', 'cancel', $status, $order_id, $tracking_id );
						$status_name   = $this->get_status_name( 'cancel' );
						$res          .= '<div class="zeus-settlement-admin card-cancel">' . $status_name . '</div>';
						$res          .= '<table class="settlement-admin-table">';
						$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="0" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
						$res          .= '</tr>';
						$res          .= '</table>';
						$res          .= '<div class="settlement-admin-button">';
						$res          .= '<input id="re_settlement" type="button" class="button" value="新規決済" />';
						$res          .= '</div>';
						$acting_status = '<span class="acting-status card-cancel">' . $status_name . '</span>';
						$acting_data   = $usces->get_order_meta_value( 'acting_zeus_card', $order_id );
						if ( $acting_data ) {
							$acting_data = usces_unserialize( $acting_data );
							if ( isset( $acting_data['money'] ) ) {
								$acting_data['money'] = 0;
							}
							$usces->set_order_meta_value( 'acting_zeus_card', usces_serialize( $acting_data ), $order_id );
						}
					} else {
						$status = $this->get_error_results( $page_cancel );
						if ( empty( $status ) ) {
							$status = 'ERROR';
						}
						wel_zeus_save_acting_log( $params, 'zeus_card', 'cancel', $status, $order_id, $tracking_id );
						$res .= '<div class="zeus-settlement-admin card-error">取消処理エラー</div>';
					}
				} else {
					$status = 'NG';
					wel_zeus_save_acting_log( array(), 'zeus_card', 'cancel', $status, $order_id, $tracking_id );
					$res .= '<div class="zeus-settlement-admin card-error">取消処理不可</div>';
				}
				$res                  .= $this->settlement_history( $order_id, $tracking_id );
				$data['result']        = $res;
				$data['status']        = $status;
				$data['acting_status'] = $acting_status;
				wp_send_json( $data );
				break;

			/* クレジットカード金額変更 */
			case 'change_zeus_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id    = filter_input( INPUT_POST, 'order_id' );
				$order_num   = filter_input( INPUT_POST, 'order_num' );
				$tracking_id = filter_input( INPUT_POST, 'tracking_id' );
				$amount      = filter_input( INPUT_POST, 'amount' );
				if ( empty( $order_id ) || empty( $tracking_id ) || empty( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				$res           = '';
				$status        = '';
				$acting_status = '';

				$latest_log = $this->get_acting_latest_log( $order_id, $tracking_id );
				if ( ! empty( $latest_log['order_no'] ) ) {
					$order_no    = $latest_log['order_no'];
					$acting_opts = $this->get_acting_settings();

					$params                                = array();
					$params['authentication']['clientip']  = $acting_opts['clientip'];
					$params['authentication']['key']       = $acting_opts['authkey'];
					$params['transaction']['money']        = $amount;
					$params['transaction']['order_number'] = $latest_log['order_no'];
					$params['transaction']['pubsec']       = 'yes';

					$change_req  = '<?xml version="1.0" encoding="utf-8"?>';
					$change_req .= '<request>';
					$change_req .= $this->assoc2xml( $params );
					$change_req .= '</request>';

					$xml = $this->get_xml( $acting_opts['card_price_change'], $change_req );
					if ( ! empty( $xml ) ) {
						$change_res = $this->xml2assoc( $xml );
						if ( 'success' === $change_res['response']['result']['status'] ) {
							$status        = 'OK';
							$log           = $change_res['response']['result'];
							$log['amount'] = $amount;
							wel_zeus_save_acting_log( $log, 'zeus_card', 'change', $status, $order_id, $tracking_id );

							$new_order_no = ( isset( $change_res['response']['result']['result_order_number'] ) ) ? $change_res['response']['result']['result_order_number'] : '';
							if ( ! empty( $new_order_no ) ) {
								$usces->set_order_meta_value( 'wc_trans_id', $new_order_no, $order_id );
								$usces->set_order_meta_value( 'trans_id', $new_order_no, $order_id );
							}
							$acting_data = $usces->get_order_meta_value( 'acting_zeus_card', $order_id );
							if ( $acting_data ) {
								$acting_data = usces_unserialize( $acting_data );
								if ( isset( $acting_data['ordd'] ) && ! empty( $new_order_no ) ) {
									$acting_data['ordd'] = $new_order_no;
								}
								if ( isset( $acting_data['money'] ) ) {
									$acting_data['money'] = $amount;
								}
								$usces->set_order_meta_value( 'acting_zeus_card', usces_serialize( $acting_data ), $order_id );
							}

							if ( ! empty( $new_order_no ) ) {
								$order_ref = wel_zeus_get_order_ref( $new_order_no );
								if ( 'payment' === $order_ref['status'] ) {
									$status_name = $this->get_status_name( $order_ref['status'] );
									wel_zeus_save_acting_log( $order_ref, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
									$res          .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
									$res          .= '<table class="settlement-admin-table">';
									$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
									$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
									$res          .= '</tr>';
									$res          .= '</table>';
									$res          .= '<div class="settlement-admin-button">';
									$res          .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
									$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
									$res          .= '</div>';
									$acting_status = '<span class="acting-status card-payment">' . $status_name . '</span>';
								} elseif ( 'auth' === $order_ref['status'] ) {
									$status_name = $this->get_status_name( $order_ref['status'] );
									wel_zeus_save_acting_log( $order_ref, 'zeus_card', 'auth', $status, $order_id, $tracking_id );
									$res .= '<div class="zeus-settlement-admin card-auth">' . $status_name . '</div>';
									$res .= '<table class="settlement-admin-table">';
									$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
									$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
									$res .= '</tr>';
									$res .= '</table>';
									$res .= '<div class="settlement-admin-button">';
									if ( '仮売完了' === $order_ref['RESULT'] ) {
										$res .= '<input id="sale_settlement" type="button" class="button" value="売上処理" />';
									}
									$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
									$res          .= '</div>';
									$acting_status = '<span class="acting-status card-auth">' . $status_name . '</span>';
								} else {
									$status_name   = $this->get_status_name( 'payment' );
									$res          .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
									$res          .= '<table class="settlement-admin-table">';
									$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
									$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
									$res          .= '</tr>';
									$res          .= '</table>';
									$res          .= '<div class="settlement-admin-button">';
									$res          .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
									$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
									$res          .= '</div>';
									$acting_status = '<span class="acting-status card-payment">' . $status_name . '</span>';
								}
							} else {
								$status_name   = $this->get_status_name( 'payment' );
								$res          .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
								$res          .= '<table class="settlement-admin-table">';
								$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
								$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
								$res          .= '</tr>';
								$res          .= '</table>';
								$res          .= '<div class="settlement-admin-button">';
								$res          .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
								$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
								$res          .= '</div>';
								$acting_status = '<span class="acting-status card-payment">' . $status_name . '</span>';
							}
						} else {
							$status = $change_res['response']['result']['status'];
							if ( empty( $status ) ) {
								$status = 'ERROR';
							}
							wel_zeus_save_acting_log( $change_res, 'zeus_card', 'change', $status, $order_id, $tracking_id );
							$res .= '<div class="zeus-settlement-admin card-error">金額変更エラー</div>';
						}
					} else {
						$status = 'ERROR';
						wel_zeus_save_acting_log( $params, 'zeus_card', 'change', $status, $order_id, $tracking_id );
						$res .= '<div class="zeus-settlement-admin card-error">金額変更エラー</div>';
					}
				} else {
					$status = 'NG';
					wel_zeus_save_acting_log( $params, 'zeus_card', 'change', $status, $order_id, $tracking_id );
					$res .= '<div class="zeus-settlement-admin card-error">金額変更不可</div>';
				}
				$res                  .= $this->settlement_history( $order_id, $tracking_id );
				$data['result']        = $res;
				$data['status']        = $status;
				$data['acting_status'] = $acting_status;
				wp_send_json( $data );
				break;

			/* クレジットカード新規決済 */
			case 're_settlement_zeus_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id    = filter_input( INPUT_POST, 'order_id' );
				$order_num   = filter_input( INPUT_POST, 'order_num' );
				$tracking_id = filter_input( INPUT_POST, 'tracking_id' );
				$member_id   = filter_input( INPUT_POST, 'member_id' );
				$amount      = filter_input( INPUT_POST, 'amount' );
				if ( empty( $order_id ) || empty( $tracking_id ) || empty( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				$res           = '';
				$status        = '';
				$acting_status = '';

				$acting_opts = $this->get_acting_settings();

				$params               = array();
				$params['clientip']   = $acting_opts['clientip'];
				$params['cardnumber'] = '9999999999999992';
				$params['expyy']      = '00';
				$params['expmm']      = '00';
				$params['money']      = $amount;
				$params['send']       = 'mall';
				$params['telno']      = '0000000000';
				$params['email']      = $this->get_email( $order_id );
				$params['sendid']     = $member_id;
				$params['sendpoint']  = $tracking_id;
				$params['pubsec']     = 'yes';
				$params['printord']   = 'yes';

				$page = $this->secure_link_batch( $acting_opts['card_url'], $params );
				if ( false !== strpos( $page, 'Success_order' ) ) {
					$status = 'OK';

					$new_order_no = $this->get_order_number( $page );
					if ( ! empty( $new_order_no ) ) {
						$usces->set_order_meta_value( 'wc_trans_id', $new_order_no, $order_id );
						$usces->set_order_meta_value( 'trans_id', $new_order_no, $order_id );
					}
					$acting_data = $usces->get_order_meta_value( 'acting_zeus_card', $order_id );
					if ( $acting_data ) {
						$acting_data          = usces_unserialize( $acting_data );
						$acting_data['ordd']  = $new_order_no;
						$acting_data['money'] = $amount;
						if ( ! isset( $acting_data['acting'] ) ) {
							$acting_data['acting'] = 'zeus_card';
						}
						if ( ! isset( $acting_data['sendpoint'] ) ) {
							$acting_data['sendpoint'] = $tracking_id;
						}
						if ( isset( $acting_data['settltment_status'] ) ) {
							unset( $acting_data['settltment_status'] );
						}
						if ( isset( $acting_data['settltment_errmsg'] ) ) {
							unset( $acting_data['settltment_errmsg'] );
						}
					} else {
						$acting_data = array(
							'acting'    => 'zeus_card',
							'ordd'      => $new_order_no,
							'sendpoint' => $tracking_id,
							'money'     => $amount,
						);
					}
					$usces->set_order_meta_value( 'acting_zeus_card', usces_serialize( $acting_data ), $order_id );

					if ( ! empty( $new_order_no ) ) {
						$order_ref = wel_zeus_get_order_ref( $new_order_no );
						if ( 'payment' === $order_ref['status'] ) {
							$status_name = $this->get_status_name( $order_ref['status'] );
							wel_zeus_save_acting_log( $order_ref, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
							$res          .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
							$res          .= '<table class="settlement-admin-table">';
							$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
							$res          .= '</tr>';
							$res          .= '</table>';
							$res          .= '<div class="settlement-admin-button">';
							$res          .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
							$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
							$res          .= '</div>';
							$acting_status = '<span class="acting-status card-payment">' . $status_name . '</span>';
						} elseif ( 'auth' === $order_ref['status'] ) {
							$status_name = $this->get_status_name( $order_ref['status'] );
							wel_zeus_save_acting_log( $order_ref, 'zeus_card', 'auth', $status, $order_id, $tracking_id );
							$res .= '<div class="zeus-settlement-admin card-auth">' . $status_name . '</div>';
							$res .= '<table class="settlement-admin-table">';
							$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
							$res .= '</tr>';
							$res .= '</table>';
							$res .= '<div class="settlement-admin-button">';
							if ( '仮売完了' === $order_ref['RESULT'] ) {
								$res .= '<input id="sale_settlement" type="button" class="button" value="売上処理" />';
							}
							$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
							$res          .= '</div>';
							$acting_status = '<span class="acting-status card-auth">' . $status_name . '</span>';
						} else {
							$status_name = $this->get_status_name( 'payment' );
							if ( ! isset( $params['ordd'] ) ) {
								$params['ordd'] = $new_order_no;
							}
							wel_zeus_save_acting_log( $params, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
							$res          .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
							$res          .= '<table class="settlement-admin-table">';
							$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
							$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
							$res          .= '</tr>';
							$res          .= '</table>';
							$res          .= '<div class="settlement-admin-button">';
							$res          .= '<input id="change_settlement" type="button" class="button" value="金額変更" />';
							$res          .= '<input id="cancel_settlement" type="button" class="button" value="取消処理" />';
							$res          .= '</div>';
							$acting_status = '<span class="acting-status card-payment">' . $status_name . '</span>';
						}
					} else {
						$status_name = $this->get_status_name( 'payment' );
						wel_zeus_save_acting_log( $params, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
						$res          .= '<div class="zeus-settlement-admin card-payment">' . $status_name . '</div>';
						$res          .= '<table class="settlement-admin-table">';
						$res          .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
						$res          .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount_original" value="' . intval( $amount ) . '" /></td>';
						$res          .= '</tr>';
						$res          .= '</table>';
						$acting_status = '<span class="acting-status card-payment">' . $status_name . '</span>';
					}
				} else {
					$status = $this->get_error_results( $page );
					if ( empty( $status ) ) {
						$status = 'ERROR';
					}
					wel_zeus_save_acting_log( $params, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
					$res .= '<div class="zeus-settlement-admin card-error">新規決済エラー</div>';
					$res .= '<table class="settlement-admin-table">';
					$res .= '<tr><th>' . __( 'Settlement amount', 'usces' ) . '</th>';
					$res .= '<td><input type="tel" class="settlement-amount" id="amount_change" value="' . intval( $amount ) . '" />' . __( usces_crcode( 'return' ), 'usces' ) . '</td>';
					$res .= '</tr>';
					$res .= '</table>';
					$res .= '<div class="settlement-admin-button">';
					$res .= '<input id="re_settlement" type="button" class="button" value="新規決済" />';
					$res .= '</div>';
				}

				$res                  .= $this->settlement_history( $order_id, $tracking_id );
				$data['result']        = $res;
				$data['status']        = $status;
				$data['acting_status'] = $acting_status;
				wp_send_json( $data );
				break;

			/* 継続課金情報更新 */
			case 'continuation_update':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id         = filter_input( INPUT_POST, 'order_id' );
				$member_id        = filter_input( INPUT_POST, 'member_id' );
				$contracted_year  = filter_input( INPUT_POST, 'contracted_year' );
				$contracted_month = filter_input( INPUT_POST, 'contracted_month' );
				$contracted_day   = filter_input( INPUT_POST, 'contracted_day' );
				$charged_year     = filter_input( INPUT_POST, 'charged_year' );
				$charged_month    = filter_input( INPUT_POST, 'charged_month' );
				$charged_day      = filter_input( INPUT_POST, 'charged_day' );
				$price            = filter_input( INPUT_POST, 'price', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
				$status           = filter_input( INPUT_POST, 'status' );

				$continue_data = $this->get_continuation_data( $member_id, $order_id );
				if ( ! $continue_data ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				/* 継続中→停止 */
				if ( 'continuation' === $continue_data['status'] && 'cancellation' === $status ) {
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
	 * 決済履歴
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $tracking_id Tracking ID.
	 * @return string
	 */
	private function settlement_history( $order_id, $tracking_id ) {
		$history  = '';
		$log_data = $this->get_acting_log( $order_id, $tracking_id, 'ALL' );
		if ( $log_data ) {
			$num      = count( $log_data );
			$history  = '<table class="settlement-history">';
			$history .= '<thead class="settlement-history-head">';
			$history .= '<tr><th></th><th>' . __( 'Processing date', 'usces' ) . '</th><th>オーダーNo</th><th>処理結果</th><th>' . __( 'Settlement amount', 'usces' ) . '</th><th>エラーメッセージ</th></tr>';
			$history .= '</thead>';
			$history .= '<tbody class="settlement-history-body">';
			foreach ( (array) $log_data as $data ) {
				$log         = usces_unserialize( $data['log'] );
				$order_no    = $this->get_order_no( $log );
				$status_name = $this->get_status_name( $data['status'], $data['result'] );
				$err_result  = '';
				$class       = '';
				$amount      = usces_crform( $data['amount'], false, true, 'return', true );
				if ( ! in_array( $data['result'], $this->payment_normal_results, true ) ) {
					$err_result = $data['result'];
					$class      = ' error';
				}
				$history .= '<tr>';
				$history .= '<td class="num">' . $num . '</td>';
				$history .= '<td class="datetime">' . $data['datetime'] . '</td>';
				$history .= '<td class="transactionid">' . $order_no . '</td>';
				$history .= '<td class="status">' . $status_name . '</td>';
				$history .= '<td class="amount">' . $amount . '</td>';
				$history .= '<td class="result' . $class . '">' . $err_result . '</td>';
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
		if ( 'wc_trans_id' !== $key ) {
			return $detail;
		}

		$acting_flg = $this->get_order_acting_flg( $order_id );
		if ( 'acting_zeus_card' === $acting_flg ) {
			$tracking_id = $this->get_tracking_id( $order_id );
			if ( ! empty( $tracking_id ) ) {
				$acting_status = $this->get_acting_status( $order_id, $tracking_id, 'ALL' );
				$class         = ' card-' . $acting_status;
				$status_name   = $this->get_status_name( $acting_status );
				if ( ! empty( $status_name ) ) {
					$detail = '<td>' . esc_html( $value ) . '<span class="acting-status' . esc_html( $class ) . '">' . esc_html( $status_name ) . '</span></td>';
				}
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
		$order_action = ( isset( $action_args['order_action'] ) ) ? $action_args['order_action'] : '';
		$order_id     = ( isset( $action_args['order_id'] ) ) ? $action_args['order_id'] : '';
		if ( 'new' !== $order_action && ! empty( $order_id ) ) {
			$payment    = usces_get_payments_by_name( $data['order_payment_name'] );
			$acting_flg = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';
			if ( 'acting_zeus_card' === $acting_flg ) {
				$tracking_id   = $this->get_tracking_id( $order_id );
				$acting_status = $this->get_acting_status( $order_id, $tracking_id, 'ALL' );
				$class         = ' card-' . $acting_status;
				$status_name   = $this->get_status_name( $acting_status );
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

	/**
	 * 受注編集画面【支払情報】
	 * usces_action_order_edit_form_settle_info
	 *
	 * @param  array $data Order data.
	 * @param  array $action_args Compact array( 'order_action', 'order_id', 'cart' ).
	 */
	public function settlement_information( $data, $action_args ) {
		$order_action = ( isset( $action_args['order_action'] ) ) ? $action_args['order_action'] : '';
		$order_id     = ( isset( $action_args['order_id'] ) ) ? $action_args['order_id'] : '';
		if ( 'new' !== $order_action && ! empty( $order_id ) ) {
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( isset( $payment['settlement'] ) && 'acting_zeus_card' === $payment['settlement'] ) {
				$tracking_id = $this->get_tracking_id( $order_id );
				if ( ! empty( $tracking_id ) ) {
					echo '<input type="button" class="button settlement-information" id="settlement-information-' . esc_attr( $tracking_id ) . '" data-tracking_id="' . esc_attr( $tracking_id ) . '" data-num="1" value="' . esc_attr__( 'Settlement info', 'usces' ) . '">';
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
		$order_action = ( isset( $action_args['order_action'] ) ) ? $action_args['order_action'] : '';
		$order_id     = ( isset( $action_args['order_id'] ) ) ? $action_args['order_id'] : '';
		if ( 'new' !== $order_action && ! empty( $order_id ) ) :
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( isset( $payment['settlement'] ) && 'acting_zeus_card' === $payment['settlement'] ) :
				?>
<div id="settlement_dialog" title="">
	<div id="settlement-response-loading"></div>
	<fieldset>
	<div id="settlement-response"></div>
	<input type="hidden" id="order_num">
	<input type="hidden" id="tracking_id">
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
		$keys = array_merge( $keys, array( 'div', 'auth_code' ) );
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
		if ( isset( $fields['acting'] ) && 'zeus_card' === $fields['acting'] ) {
			$field_keys  = array( 'div' );
			$acting_opts = $this->get_acting_settings();
			if ( 2 === (int) $acting_opts['connection'] && 1 === (int) $acting_opts['3dsecur'] ) {
				$field_keys[] = 'auth_code';
			}
			$keys = array_merge( $keys, $field_keys );
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
		if ( false !== strpos( $acting, 'zeus_card' ) ) {
			if ( 'acting' === $key ) {
				$value = 'カード決済（ZEUS）';
			} elseif ( 'div' === $key ) {
				switch ( $value ) {
					case '01':
						$value = '一括払い';
						break;
					case '02':
						$value = '分割（2回）';
						break;
					case '03':
						$value = '分割（3回）';
						break;
					case '05':
						$value = '分割（5回）';
						break;
					case '06':
						$value = '分割（6回）';
						break;
					case '10':
						$value = '分割（10回）';
						break;
					case '12':
						$value = '分割（12回）';
						break;
					case '15':
						$value = '分割（15回）';
						break;
					case '18':
						$value = '分割（18回）';
						break;
					case '20':
						$value = '分割（20回）';
						break;
					case '24':
						$value = '分割（24回）';
						break;
					case '99':
						$value = '分割（リボ払い）';
						break;
					case 'B1':
						$value = '分割（ボーナス一括払い）';
						break;
				}
			}
		} elseif ( 'zeus_bank' === $acting ) {
			if ( 'acting' === $key ) {
				$value = '銀行振込決済（ZEUS）';
			} elseif ( 'status' === $key ) {
				if ( '01' === $value ) {
					$value = '受付中';
				} elseif ( '02' === $value ) {
					$value = '未入金';
				} elseif ( '03' === $value ) {
					$value = '入金済';
				} elseif ( '04' === $value ) {
					$value = 'エラー';
				} elseif ( '05' === $value ) {
					$value = '入金失敗';
				}
			} elseif ( 'error_message' === $key ) {
				if ( '0002' === $value ) {
					$value = '入金不足';
				} elseif ( '0003' === $value ) {
					$value = '過剰入金';
				}
			}
		} elseif ( 'zeus_conv' === $acting ) {
			if ( 'acting' === $key ) {
				$value = 'コンビニ決済（ZEUS）';
			} elseif ( 'pay_cvs' === $key ) {
				$value = esc_html( usces_get_conv_name( $value ) );
			} elseif ( 'status' === $key ) {
				if ( '01' === $value ) {
					$value = '未入金';
				} elseif ( '02' === $value ) {
					$value = '申込エラー';
				} elseif ( '03' === $value ) {
					$value = '期日切';
				} elseif ( '04' === $value ) {
					$value = '入金済';
				} elseif ( '05' === $value ) {
					$value = '売上確定';
				} elseif ( '06' === $value ) {
					$value = '入金取消';
				} elseif ( '11' === $value ) {
					$value = 'キャンセル後入金';
				} elseif ( '12' === $value ) {
					$value = 'キャンセル後売上';
				} elseif ( '13' === $value ) {
					$value = 'キャンセル後取消';
				}
			} elseif ( 'pay_limit' === $key ) {
				$value = substr( $value, 0, 4 ) . '年' . substr( $value, 4, 2 ) . '月' . substr( $value, 6, 2 ) . '日';
			}
		}
		return $value;
	}

	/**
	 * 会員データ編集画面 カード情報登録情報
	 * usces_action_admin_member_info
	 *
	 * @param array $member Member data.
	 * @param array $member_metas Member meta data.
	 * @param array $member_history Member's history order data.
	 */
	public function admin_member_info( $member, $member_metas, $member_history ) {
		$acting_opts = $this->get_acting_settings();
		if ( 'on' !== $acting_opts['quickcharge'] ) {
			return;
		}

		$cardinfo = array();
		foreach ( $member_metas as $value ) {
			if ( in_array( $value['meta_key'], array( 'zeus_pcid', 'zeus_partofcard' ), true ) ) {
				$cardinfo[ $value['meta_key'] ] = $value['meta_value'];
			}
		}
		if ( 0 < count( $cardinfo ) ) :
			foreach ( $cardinfo as $key => $value ) :
				if ( 'zeus_pcid' !== $key ) :
					if ( 'zeus_partofcard' === $key ) {
						$label = __( 'Lower 4 digits', 'usces' );
					} elseif ( 'zeus_limitofcard' === $key ) {
						$label = __( 'Expiration date', 'usces' );
					} else {
						$label = $key;
					}
					?>
		<tr>
			<td class="label"><?php echo esc_html( $label ); ?></td>
			<td><div class="rod_left shortm"><?php echo esc_html( $value ); ?></div></td>
		</tr>
					<?php
		endif;
			endforeach;
			if ( array_key_exists( 'zeus_pcid', $cardinfo ) ) :
				?>
		<tr>
			<td class="label">QuickCharge</td>
			<td><div class="rod_left shortm"><?php esc_html_e( 'Registered', 'usces' ); ?></div></td>
		</tr>
				<?php
				if ( ! usces_have_member_continue_order( $member['ID'] ) && ! usces_have_member_regular_order( $member['ID'] ) ) :
					?>
		<tr>
			<td class="label"><input type="checkbox" name="zeus_pcid" id="zeus_pcid" value="delete"></td>
			<td><label for="zeus_pcid">QuickCharge を解除する</label></td>
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
	 * @param int  $member_id Member ID.
	 * @param bool $res Member data update results.
	 */
	public function admin_update_memberdata( $member_id, $res ) {
		global $usces;

		if ( ! $this->is_activate_card() || false === $res ) {
			return;
		}

		if ( 'delete' === filter_input( INPUT_POST, 'zeus_pcid' ) ) {
			$usces->del_member_meta( 'zeus_pcid', $member_id );
			$usces->del_member_meta( 'zeus_partofcard', $member_id );
			$usces->del_member_meta( 'zeus_limitofcard', $member_id );
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
		$payment    = usces_get_payments_by_name( $entry['order']['payment_name'] );
		$acting_flg = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';

		switch ( $acting_flg ) {
			case 'acting_zeus_card':
				if ( ! isset( $entry['order']['cbrand'] ) || ( isset( $entry['order']['howpay'] ) && '1' == $entry['order']['howpay'] ) ) {
					$str = '　一括払い';
				} else {
					$div_name = 'div_' . $entry['order']['cbrand'];
					switch ( $entry['order'][ $div_name ] ) {
						case '01':
							$str = '　一括払い';
							break;
						case '02':
							$str = '　分割（2回）';
							break;
						case '03':
							$str = '　分割（3回）';
							break;
						case '05':
							$str = '　分割（5回）';
							break;
						case '06':
							$str = '　分割（6回）';
							break;
						case '10':
							$str = '　分割（10回）';
							break;
						case '12':
							$str = '　分割（12回）';
							break;
						case '15':
							$str = '　分割（15回）';
							break;
						case '18':
							$str = '　分割（18回）';
							break;
						case '20':
							$str = '　分割（20回）';
							break;
						case '24':
							$str = '　分割（24回）';
							break;
						case '99':
							$str = '　分割（リボ払い）';
							break;
						case 'B1':
							$str = '　分割（ボーナス一括払い）';
							break;
					}
				}
				break;

			case 'acting_zeus_bank':
				$acting_opts = $this->get_acting_settings();
				if ( ! empty( $acting_opts['bank_expired_date'] ) ) {
					$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $acting_opts['bank_expired_date'] ) . __( ')', 'usces' );
					$str            = apply_filters( 'usces_filter_zeus_payment_limit_bank', $payment_detail, $acting_opts['bank_expired_date'] );
				}
				break;

			case 'acting_zeus_conv':
				if ( isset( $entry['order']['pay_cvs'] ) ) {
					$conv_name = usces_get_conv_name( $entry['order']['pay_cvs'] );
					$str       = ( '' !== $conv_name ) ? __( '(', 'usces' ) . $conv_name . __( ')', 'usces' ) : '';
				}
				$acting_opts = $this->get_acting_settings();
				if ( ! empty( $acting_opts['conv_span'] ) ) {
					$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $acting_opts['conv_span'] ) . __( ')', 'usces' );
					$str           .= apply_filters( 'usces_filter_zeus_payment_limit_conv', $payment_detail, $acting_opts['conv_span'] );
				}
				break;
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
			case 'acting_zeus_card':
				if ( $this->is_validity_acting( 'card' ) ) {
					$payments_str .= "'" . $payment['name'] . "': 'zeus', ";
				}
				break;
			case 'acting_zeus_bank':
				if ( $this->is_validity_acting( 'bank' ) ) {
					$payments_str .= "'" . $payment['name'] . "': 'zeus_bank', ";
				}
				break;
			case 'acting_zeus_conv':
				if ( $this->is_validity_acting( 'conv' ) ) {
					$payments_str .= "'" . $payment['name'] . "': 'zeus_conv', ";
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
			case 'acting_zeus_card':
				if ( $this->is_validity_acting( 'card' ) ) {
					$payments_arr[] = 'zeus';
				}
				break;
			case 'acting_zeus_bank':
				if ( $this->is_validity_acting( 'bank' ) ) {
					$payments_arr[] = 'zeus_bank';
				}
				break;
			case 'acting_zeus_conv':
				if ( $this->is_validity_acting( 'conv' ) ) {
					$payments_arr[] = 'zeus_conv';
				}
				break;
		}
		return $payments_arr;
	}

	/**
	 * カード情報入力チェック
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
			case 'acting_zeus_card':
				if ( isset( $_POST['acting'] ) && 'zeus' !== filter_input( INPUT_POST, 'acting' ) ) {
					$mes .= 'カード決済データが不正です！';
				} elseif ( empty( $_POST['zeus_card_option'] ) || ( 'new' === filter_input( INPUT_POST, 'zeus_card_option' ) && empty( $_POST['zeus_token_value'] ) ) ) {
					$mes .= 'カード決済データが不正です！';
				} elseif ( ! empty( $_POST['zeus_token_value'] ) ) {
					if ( ! wel_check_credit_security() ) {
						$mes .= __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '<br />';
					}
				}
				break;

			case 'acting_zeus_bank':
				if ( WCUtils::is_blank( $_POST['username_bank'] ) ) {
					$mes .= 'お振込名義を入力してください。<br />';
				} elseif ( ! preg_match( '/^[ァ-ヶー]+$/u', filter_input( INPUT_POST, 'username_bank' ) ) ) {
					$mes .= 'お振込名義は全角カタカナで入力してください。<br />';
				}
				break;

			case 'acting_zeus_conv':
				if ( WCUtils::is_blank( $_POST['username_conv'] ) ) {
					$mes .= 'お名前を入力してください。<br />';
				} elseif ( ! preg_match( '/^[ァ-ヶー]+$/u', filter_input( INPUT_POST, 'username_conv' ) ) ) {
					$mes .= 'お名前は全角カタカナで入力してください。<br />';
				}
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
			case 'acting_zeus_card':
				$acting_opts = $this->get_acting_settings();
				if ( ( ! isset( $acting_opts['activate'] ) || 'on' !== $acting_opts['activate'] ) ||
					( ! isset( $acting_opts['card_activate'] ) || 'on' !== $acting_opts['card_activate'] ) ||
					'activate' !== $payment['use'] ) {
					return $form;
				}

				$form  = '<input type="hidden" name="acting" value="' . $this->paymod_id . '">';
				$form .= '<table class="customer_form" id="' . $this->paymod_id . '">';
				$form .= '<tr><th scope="row">クレジットカード情報</th><td id="zeus_token_card_info_area"></td></tr>';

				$howpay = ( isset( $_POST['howpay'] ) ) ? filter_input( INPUT_POST, 'howpay' ) : '1';
				$cbrand = ( isset( $_POST['cbrand'] ) ) ? filter_input( INPUT_POST, 'cbrand' ) : '';
				$div    = ( isset( $_POST['div'] ) ) ? filter_input( INPUT_POST, 'div' ) : '';

				$form_howpay = '';
				$member_page = ( isset( $_GET['usces_page'] ) && 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page' ) ) ? true : false;
				if ( 'on' === $acting_opts['howpay'] && ! $member_page ) {
					$howpay_b1    = ( 'on' === $acting_opts['howpay_B1'] ) ? '</option><option value="B1"' . selected( $div, 'B1', false ) . '>' . __( 'Bonus lump-sum payment', 'usces' ) : '';
					$howpay_02    = ( 'on' === $acting_opts['howpay_02'] ) ? '</option><option value="02"' . selected( $div, '02', false ) . '>2' . __( '-time payment', 'usces' ) : '';
					$form_howpay .= '
					<tr>
						<th scope="row">' . __( 'payment method', 'usces' ) . '</th>
						<td>
							<input name="offer[howpay]" type="radio" value="1" id="howdiv1"' . checked( $howpay, '1', false ) . ' /><label for="howdiv1">' . __( 'Single payment', 'usces' ) . '</label>&nbsp;&nbsp;&nbsp;
							<input name="offer[howpay]" type="radio" value="0" id="howdiv2"' . checked( $howpay, '0', false ) . ' /><label for="howdiv2">' . __( 'Payment in installments', 'usces' ) . '</label>
						</td>
					</tr>
					<tr id="cbrand_zeus">
						<th scope="row">' . __( 'Card brand', 'usces' ) . '</th>
						<td>
						<select name="offer[cbrand]" id="cbrand">
							<option value=""' . selected( WCUtils::is_blank( $cbrand ), true, false ) . '>--------</option>
							<option value="1"' . selected( $cbrand, '1', false ) . '>JCB</option>
							<option value="1"' . selected( $cbrand, '1', false ) . '>VISA</option>
							<option value="1"' . selected( $cbrand, '1', false ) . '>MASTER</option>
							<option value="2"' . selected( $cbrand, '2', false ) . '>DINERS</option>
							<option value="3"' . selected( $cbrand, '3', false ) . '>AMEX</option>
						</select>
						</td>
					</tr>
					<tr id="div_zeus">
						<th scope="row">' . __( 'Number of payments', 'usces' ) . '</th>
						<td>
						<select name="offer[div_1]" id="brand1">
							<option value="01"' . selected( $div, '01', false ) . '>' . __( 'Single payment', 'usces' ) . $howpay_02 . '</option>
							<option value="03"' . selected( $div, '03', false ) . '>3' . __( '-time payment', 'usces' ) . '</option>
							<option value="05"' . selected( $div, '05', false ) . '>5' . __( '-time payment', 'usces' ) . '</option>
							<option value="06"' . selected( $div, '06', false ) . '>6' . __( '-time payment', 'usces' ) . '</option>
							<option value="10"' . selected( $div, '10', false ) . '>10' . __( '-time payment', 'usces' ) . '</option>
							<option value="12"' . selected( $div, '12', false ) . '>12' . __( '-time payment', 'usces' ) . '</option>
							<option value="15"' . selected( $div, '15', false ) . '>15' . __( '-time payment', 'usces' ) . '</option>
							<option value="18"' . selected( $div, '18', false ) . '>18' . __( '-time payment', 'usces' ) . '</option>
							<option value="20"' . selected( $div, '20', false ) . '>20' . __( '-time payment', 'usces' ) . '</option>
							<option value="24"' . selected( $div, '24', false ) . '>24' . __( '-time payment', 'usces' ) . '</option>
							<option value="99"' . selected( $div, '99', false ) . '>' . __( 'Libor Funding pay', 'usces' ) . $howpay_b1 . '</option>
						</select>
						<select name="offer[div_2]" id="brand2">
							<option value="01"' . selected( $div, '01', false ) . '>' . __( 'Single payment', 'usces' ) . '</option>
							<option value="99"' . selected( $div, '99', false ) . '>' . __( 'Libor Funding pay', 'usces' ) . $howpay_b1 . '</option>
						</select>
						<select name="offer[div_3]" id="brand3">
							<option value="01"' . selected( $div, '01', false ) . '>' . __( 'Single payment', 'usces' ) . '</option>
							<option value="03"' . selected( $div, '03', false ) . '>3' . __( '-time payment', 'usces' ) . '</option>
							<option value="05"' . selected( $div, '05', false ) . '>5' . __( '-time payment', 'usces' ) . '</option>
							<option value="06"' . selected( $div, '06', false ) . '>6' . __( '-time payment', 'usces' ) . '</option>
							<option value="10"' . selected( $div, '10', false ) . '>10' . __( '-time payment', 'usces' ) . '</option>
							<option value="12"' . selected( $div, '12', false ) . '>12' . __( '-time payment', 'usces' ) . '</option>
							<option value="15"' . selected( $div, '15', false ) . '>15' . __( '-time payment', 'usces' ) . '</option>
							<option value="18"' . selected( $div, '18', false ) . '>18' . __( '-time payment', 'usces' ) . '</option>
							<option value="20"' . selected( $div, '20', false ) . '>20' . __( '-time payment', 'usces' ) . '</option>
							<option value="24"' . selected( $div, '24', false ) . '>24' . __( '-time payment', 'usces' ) . '</option>
							<option value="99"' . selected( $div, '99', false ) . '>' . __( 'Libor Funding pay', 'usces' ) . $howpay_b1 . '</option>
						</select>
						</td>
					</tr>
				</table>';
				}
				$form .= apply_filters( 'usces_filter_delivery_secure_form_howpay', $form_howpay );
				break;

			case 'acting_zeus_bank':
				$acting_opts = $this->get_acting_settings();
				if ( ( ! isset( $acting_opts['activate'] ) || 'on' !== $acting_opts['activate'] ) ||
					( ! isset( $acting_opts['bank_activate'] ) || 'on' !== $acting_opts['bank_activate'] ) ||
					'activate' !== $payment['use'] ) {
					return $form;
				}

				$entry    = $usces->cart->get_entry();
				$username = ( isset( $_POST['username_bank'] ) ) ? filter_input( INPUT_POST, 'username_bank' ) : $entry['customer']['name3'] . $entry['customer']['name4'];

				$form = '
				<table class="customer_form" id="' . $this->paymod_id . '_bank">
					<tr>
					<th scope="row"><em>' . __( '*', 'usces' ) . '</em>お振込名義</th>
					<td colspan="2"><input name="username_bank" id="username_bank" type="text" size="30" value="' . esc_attr( $username ) . '" />' . __( '(full-width Kana)', 'usces' ) . '</td>
					</tr>
				</table>';
				break;

			case 'acting_zeus_conv':
				$acting_opts = $this->get_acting_settings();
				if ( ( ! isset( $acting_opts['activate'] ) || 'on' !== $acting_opts['activate'] ) ||
					( ! isset( $acting_opts['conv_activate'] ) || 'on' !== $acting_opts['conv_activate'] ) ||
					'activate' !== $payment['use'] ) {
					return $form;
				}

				$pay_cvs  = ( isset( $_POST['pay_cvs'] ) ) ? filter_input( INPUT_POST, 'pay_cvs' ) : '';
				$entry    = $usces->cart->get_entry();
				$username = ( isset( $_POST['username_conv'] ) ) ? filter_input( INPUT_POST, 'username_conv' ) : $entry['customer']['name3'] . $entry['customer']['name4'];

				$form = '
				<table class="customer_form" id="' . $this->paymod_id . '_conv">
					<tr>
					<th scope="row">' . __( 'Convenience store for payment', 'usces' ) . '</th>
					<td colspan="2">
					<select name="offer[pay_cvs]" id="pay_cvs_zeus">';
				foreach ( (array) $acting_opts['pay_cvs'] as $pay_cvs_code ) {
					if ( 'D040' === $pay_cvs_code ) {
						continue;
					}
					$form .= '
						<option value="' . $pay_cvs_code . '"' . selected( $pay_cvs_code, $pay_cvs, false ) . '>' . usces_get_conv_name( $pay_cvs_code ) . '</option>';
				}
				$form .= '
					</select>
					</td>
					</tr>
					<tr>
					<th scope="row"><em>' . __( '*', 'usces' ) . '</em>' . __( 'Full name', 'usces' ) . '</th>
					<td colspan="2"><input name="username_conv" id="username_conv" type="text" size="30" value="' . esc_attr( $username ) . '" />' . __( '(full-width Kana)', 'usces' ) . '</td>
					</tr>
				</table>';
				break;
		}

		return $form;
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

		if ( ! in_array( $acting_flg, $this->pay_method, true ) ) {
			return $form;
		}

		$entry = $usces->cart->get_entry();
		if ( ! $entry['order']['total_full_price'] ) {
			return $form;
		}

		switch ( $acting_flg ) {
			case 'acting_zeus_card':
				$acting_opts = $this->get_acting_settings();
				$usces->save_order_acting_data( $rand );
				usces_save_order_acting_data( $rand );
				$mem_id = '';
				if ( $usces->is_member_logged_in() ) {
					$member = $usces->get_member();
					$mem_id = $member['ID'];
				}
				$zeus_card_option = ( isset( $_POST['zeus_card_option'] ) ) ? filter_input( INPUT_POST, 'zeus_card_option' ) : '';
				$zeus_token_value = ( isset( $_POST['zeus_token_value'] ) ) ? filter_input( INPUT_POST, 'zeus_token_value' ) : '';
				$form             = '';
				if ( $this->is_activate_card( 'api' ) && 1 === (int) $acting_opts['3dsecur'] ) {
					$form .= '<div id="3dscontainer"></div>';
				}
				$form .= '<form name="purchase_form" id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="card_option" id="card_option" value="' . $zeus_card_option . '">
				<input type="hidden" name="token_key" id="token_key" value="' . $zeus_token_value . '">
				<input type="hidden" name="money" value="' . usces_crform( $entry['order']['total_full_price'], false, false, 'return', false ) . '">
				<input type="hidden" name="telno" value="' . esc_attr( str_replace( '-', '', $entry['customer']['tel'] ) ) . '">
				<input type="hidden" name="email" value="' . esc_attr( $entry['customer']['mailaddress1'] ) . '">
				<input type="hidden" name="sendid" id="sendid" value="' . $mem_id . '">
				<input type="hidden" name="sendpoint" id="sendpoint" value="' . $rand . '">';
				if ( isset( $entry['order']['cbrand'] ) && isset( $entry['order']['howpay'] ) && WCUtils::is_zero( $entry['order']['howpay'] ) ) {
					$div_name = 'div_' . $entry['order']['cbrand'];
					$form    .= '<input type="hidden" name="howpay" value="' . $entry['order']['howpay'] . '">
					<input type="hidden" name="cbrand" value="' . $entry['order']['cbrand'] . '">
					<input type="hidden" name="div" value="' . $entry['order'][ $div_name ] . '">
					<input type="hidden" name="div_1" value="' . $entry['order']['div_1'] . '">
					<input type="hidden" name="div_2" value="' . $entry['order']['div_2'] . '">
					<input type="hidden" name="div_3" value="' . $entry['order']['div_3'] . '">';
				}
				$form .= '<div class="send">';
				if ( $this->is_activate_card( 'api' ) && 1 === (int) $acting_opts['3dsecur'] ) {
					$form .= '<div id="zeus-loading" style="display:none"><div id="welcart-loading-text">' . __( 'Processing...', 'usces' ) . '</div></div>';
				}
				$form .= apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
				<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
				<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
				<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
				break;

			case 'acting_zeus_conv':
				$member      = $usces->get_member();
				$acting_opts = $this->get_acting_settings();
				$usces->save_order_acting_data( $rand );
				usces_save_order_acting_data( $rand );
				$pay_cvs       = ( isset( $entry['order']['pay_cvs'] ) ) ? $entry['order']['pay_cvs'] : '';
				$username_conv = ( isset( $_POST['username_conv'] ) ) ? filter_input( INPUT_POST, 'username_conv' ) : '';
				$form          = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="act" value="secure_order">
				<input type="hidden" name="money" value="' . usces_crform( $entry['order']['total_full_price'], false, false, 'return', false ) . '">
				<input type="hidden" name="username" value="' . esc_attr( $username_conv ) . '">
				<input type="hidden" name="telno" value="' . esc_attr( str_replace( '-', '', $entry['customer']['tel'] ) ) . '">
				<input type="hidden" name="email" value="' . esc_attr( $entry['customer']['mailaddress1'] ) . '">
				<input type="hidden" name="pay_cvs" value="' . $pay_cvs . '">
				<input type="hidden" name="sendid" value="' . $member['ID'] . '">
				<input type="hidden" name="sendpoint" value="' . $rand . '">';
				$form         .= '
				<div class="send">
				' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
				<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
				<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' /></div>
				<input type="hidden" name="username_conv" value="' . esc_attr( $username_conv ) . '">
				<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
				break;

			case 'acting_zeus_bank':
				$member      = $usces->get_member();
				$acting_opts = $this->get_acting_settings();
				$usces->save_order_acting_data( $rand );
				usces_save_order_acting_data( $rand );
				$username_bank = ( isset( $_POST['username_bank'] ) ) ? filter_input( INPUT_POST, 'username_bank' ) : '';
				$form          = '<form id="purchase_form" action="' . $acting_opts['bank_url'] . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="clientip" value="' . esc_attr( $acting_opts['clientip_bank'] ) . '">
				<input type="hidden" name="act" value="order">
				<input type="hidden" name="money" value="' . usces_crform( $entry['order']['total_full_price'], false, false, 'return', false ) . '">';
				if ( isset( $acting_opts['bank_ope'] ) && 'test' === $acting_opts['bank_ope'] ) {
					$form .= '<input type="hidden" name="username" value="' . esc_attr( trim( $username_bank ) . '_' . $acting_opts['testid_bank'] ) . '">';
					$form .= '<input type="hidden" name="telno" value="99999999999">';
				} else {
					$form .= '<input type="hidden" name="username" value="' . esc_attr( trim( $username_bank ) ) . '">';
					$form .= '<input type="hidden" name="telno" value="' . esc_attr( str_replace( '-', '', $entry['customer']['tel'] ) ) . '">';
				}
				if ( ! empty( $acting_opts['bank_expired_date'] ) ) {
					$form .= '<input type="hidden" name="expired_date" value="' . esc_attr( $acting_opts['bank_expired_date'] ) . '">';
					$form .= '<input type="hidden" name="expired_date_of_user" value="' . esc_attr( $acting_opts['bank_expired_date'] ) . '">';
				}
				$form .= '<input type="hidden" name="email" value="' . esc_attr( $entry['customer']['mailaddress1'] ) . '">
				<input type="hidden" name="sendid" value="' . $member['ID'] . '">
				<input type="hidden" name="sendpoint" value="' . $rand . '">
				<input type="hidden" name="siteurl" value="' . esc_url( get_option( 'home' ) ) . '/?backfrom_zeus_bank=1">
				<input type="hidden" name="sitestr" value="「' . esc_attr( get_option( 'blogname' ) ) . '」トップページへ">';
				$form .= '<input type="hidden" name="dummy" value="&#65533;" />';
				$form .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"' ) . $purchase_disabled . ' /></div>';
				$form .= '</form>';
				$form .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' /></div>
				<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
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
		wel_esc_script_e( $form );
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
		$acting_flg = ( isset( $payment['settlement'] ) && 'acting' === $payment['settlement'] ) ? $payment['module'] : $payment['settlement'];

		switch ( $acting_flg ) {
			case 'acting_zeus_card':
				$acting_opts = $this->get_acting_settings();
				if ( isset( $_POST['zeus_card_option'] ) ) {
					$form .= '<input type="hidden" name="zeus_card_option" value="' . esc_attr( filter_input( INPUT_POST, 'zeus_card_option' ) ) . '">';
				}
				if ( isset( $_POST['zeus_card_option'] ) && 'new' === filter_input( INPUT_POST, 'zeus_card_option' ) ) {
					$form .= '<input type="hidden" name="zeus_token_value" value="' . esc_attr( filter_input( INPUT_POST, 'zeus_token_value' ) ) . '">';
				}
				break;

			case 'acting_zeus_bank':
				if ( isset( $_POST['username_bank'] ) ) {
					$form .= '<input type="hidden" name="username_bank" value="' . esc_attr( filter_input( INPUT_POST, 'username_bank' ) ) . '">';
				}
				break;

			case 'acting_zeus_conv':
				if ( isset( $entry['order']['pay_cvs'] ) ) {
					$form .= '<input type="hidden" name="offer[pay_cvs]" value="' . esc_attr( $entry['order']['pay_cvs'] ) . '">';
				}
				if ( isset( $_POST['username_conv'] ) ) {
					$form .= '<input type="hidden" name="username_conv" value="' . esc_attr( filter_input( INPUT_POST, 'username_conv' ) ) . '">';
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
		global $usces;

		$cart = $usces->cart->get_cart();
		if ( empty( $cart ) ) {
			$tracking_id = filter_input( INPUT_GET, 'sendpoint' );
			if ( ! empty( $tracking_id ) ) {
				usces_restore_order_acting_data( $tracking_id );
			}
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

		if ( ! in_array( $acting_flg, $this->pay_method, true ) ) {
			return;
		}

		$cart = $usces->cart->get_cart();

		if ( empty( $cart ) ) {
			if ( isset( $_REQUEST['sendpoint'] ) ) {
				usces_restore_order_acting_data( wp_unslash( $_REQUEST['sendpoint'] ) );
				$cart = $usces->cart->get_cart();
				if ( empty( $cart ) ) {
					wp_redirect( USCES_CART_URL );
				}
			} else {
				wp_redirect( USCES_CART_URL );
			}
		} else {
			if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $acting_flg ) ) {
				wp_redirect( USCES_CART_URL );
			}
		}

		$usces->error_message = $usces->zaiko_check();
		if ( '' !== $usces->error_message || 0 === $usces->cart->num_row() ) {
			wp_redirect( USCES_CART_URL );
			exit();
		}

		$acting_opts = $this->get_acting_settings();
		parse_str( $post_query, $post_data );

		/* Secure API */
		if ( 'acting_zeus_card' === $acting_flg && 2 === (int) $acting_opts['connection'] ) {

			/* 3D Secure */
			if ( 1 === (int) $acting_opts['3dsecur'] && ! isset( $post_data['outside'] ) ) {
				/* Auth Reqest */
				$this->zeus_3dsecure_auth();
			} else {
				$res = $this->zeus_secure_payreq();
				return $res;
			}

			/* Secure Link */
		} elseif ( 'acting_zeus_card' === $acting_flg && 1 === (int) $acting_opts['connection'] ) {
			$sendid    = ( 'on' === $acting_opts['quickcharge'] && $usces->is_member_logged_in() && isset( $post_data['sendid'] ) ) ? $post_data['sendid'] : '';
			$sendpoint = ( isset( $post_data['sendpoint'] ) ) ? $post_data['sendpoint'] : '';

			$params             = array();
			$params['send']     = 'mall';
			$params['clientip'] = $acting_opts['clientip'];
			if ( 'on' === $acting_opts['quickcharge'] && isset( $post_data['card_option'] ) && 'prev' === $post_data['card_option'] && ! empty( $sendid ) ) {
				$params['cardnumber'] = '8888888888888882';
				$params['expyy']      = '00';
				$params['expmm']      = '00';
			} elseif ( isset( $post_data['token_key'] ) ) {
				$params['token_key'] = $post_data['token_key'];
			}
			$params['money']        = $post_data['money'];
			$params['telno']        = str_replace( '-', '', $post_data['telno'] );
			$params['email']        = $post_data['email'];
			$params['sendid']       = $sendid;
			$params['sendpoint']    = $sendpoint;
			$params['printord']     = 'yes';
			$params['return_value'] = 'yes';
			if ( 'on' === $acting_opts['howpay'] && isset( $post_data['howpay'] ) && WCUtils::is_zero( $post_data['howpay'] ) ) {
				$params['div'] = $post_data['div'];
			}

			$page = $this->secure_link_batch( $acting_opts['card_url'], $params );
			if ( false !== strpos( $page, 'Success_order' ) ) {
				usces_ordered_acting_data( $sendpoint, 'propriety' );
				$_nonce = ( isset( $post_data['_nonce'] ) ) ? $post_data['_nonce'] : wp_create_nonce( 'acting_zeus_card' );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => 1,
							'result'        => 1,
							'_nonce'        => $_nonce,
						),
						USCES_CART_URL
					)
				);
			} else {
				$err_code = $this->get_err_code( $page );
				$data     = explode( "\r\n", $page );
				$log      = array(
					'acting' => 'zeus_card',
					'key'    => $sendpoint,
					'result' => $err_code,
					'data'   => $data,
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => 0,
							'err_code'      => substr( $err_code, -3 ),
						),
						USCES_CART_URL
					)
				);
			}
			exit();

		} elseif ( 'acting_zeus_conv' === $acting_flg ) {

			$interface = $this->get_interface( $acting_opts['conv_url'] );

			$params              = array();
			$params['clientip']  = $acting_opts['clientip_conv'];
			$params['act']       = $post_data['act'];
			$params['money']     = $post_data['money'];
			$params['username']  = mb_convert_encoding( $post_data['username'], 'SJIS', 'UTF-8' );
			$params['telno']     = str_replace( '-', '', $post_data['telno'] );
			$params['email']     = $post_data['email'];
			$params['pay_cvs']   = $post_data['pay_cvs'];
			$params['sendid']    = $post_data['sendid'];
			$params['sendpoint'] = $post_data['sendpoint'];
			if ( isset( $acting_opts['conv_ope'] ) && 'test' === $acting_opts['conv_ope'] ) {
				$params['testid']    = $acting_opts['testid_conv'];
				$params['test_type'] = $acting_opts['test_type_conv'];
			}
			if ( ! empty( $acting_opts['conv_span'] ) ) {
				$params['span'] = $acting_opts['conv_span'];
			}
			$vars = http_build_query( $params );

			$header  = 'POST ' . $interface['path'] . " HTTP/1.1\r\n";
			$header .= 'Host: ' . $interface['host'] . "\r\n";
			$header .= "User-Agent: PHP Script\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= 'Content-Length: ' . strlen( $vars ) . "\r\n";
			$header .= "Connection: close\r\n\r\n";
			$header .= $vars;

			$fp = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
			if ( ! $fp ) {
				$log = array(
					'acting' => 'zeus_conv',
					'key'    => $post_data['sendpoint'],
					'result' => 'SSL/TLS ERROR ( ' . $errno . ' )',
					'data'   => array( $errstr ),
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_conv',
							'acting_return' => '0',
						),
						USCES_CART_URL
					)
				);
				exit();
			}

			if ( $fp ) {
				$page = '';
				$qstr = '';
				fwrite( $fp, $header );
				while ( ! feof( $fp ) ) {
					$scr   = fgets( $fp, 1024 );
					$page .= $scr;
					if ( false !== strpos( $scr, 'order_no' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
					if ( false !== strpos( $scr, 'pay_no1' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
					if ( false !== strpos( $scr, 'pay_no2' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
					if ( false !== strpos( $scr, 'pay_limit' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
					if ( false !== strpos( $scr, 'pay_url' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
					if ( false !== strpos( $scr, 'error_code' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
					if ( false !== strpos( $scr, 'sendpoint' ) ) {
						$qstr .= trim( $scr ) . '&';
					}
				}
				$qstr .= 'pay_cvs=' . $post_data['pay_cvs'] . '&wctid=' . $post_data['sendpoint'];
				fclose( $fp );

				if ( false !== strpos( $page, 'Success_order' ) ) {
					$result_data = array(
						'acting'        => 'zeus_conv',
						'acting_return' => '1',
					);
					parse_str( $qstr, $data );
					$result_data = array_merge( $result_data, $data );
					wp_redirect( add_query_arg( $result_data, USCES_CART_URL ) );
					exit();

				} else {
					parse_str( $qstr, $data );
					$log = array(
						'acting' => 'zeus_conv',
						'key'    => $post_data['sendpoint'],
						'result' => 'CERTIFICATION ERROR',
						'data'   => $data,
					);
					usces_save_order_acting_error( $log );
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => 'zeus_conv',
								'acting_return' => '0',
							),
							USCES_CART_URL
						)
					);
					exit();
				}
			}
			exit();
		}
	}

	/**
	 * 結果通知前処理
	 * usces_pre_acting_return
	 */
	public function pre_acting_return() {
		global $usces;

		$tracking_id = filter_input( INPUT_GET, 'wctid' );
		if ( empty( $tracking_id ) ) {
			$tracking_id = filter_input( INPUT_GET, 'sendpoint' );
		}
		if ( ! empty( $tracking_id ) ) {
			$cart = $usces->cart->get_cart();
			if ( empty( $cart ) ) {
				usces_restore_order_acting_data( $tracking_id );
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
		$acting = filter_input( INPUT_GET, 'acting' );
		switch ( $acting ) {
			case 'zeus_card':
				if ( $results['acting_return'] && isset( $results['wctid'] ) && usces_is_trusted_acting_data( $results['wctid'] ) ) {
					$results[0] = 1;
				} else {
					$results[0] = ( isset( $results['acting_return'] ) ) ? $results['acting_return'] : 0;
				}
				$results['reg_order'] = false;
				break;

			case 'zeus_conv':
				if ( $results['acting_return'] ) {
					$results[0] = 1;
				} else {
					$results[0] = 0;
				}
				$results['reg_order'] = true;
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
		$result_data = wp_unslash( $_REQUEST );
		$acting      = ( isset( $result_data['acting'] ) ) ? $result_data['acting'] : '';
		switch ( $acting ) {
			case 'zeus_card':
				if ( isset( $result_data['ordd'] ) ) {
					$trans_id = $result_data['ordd'];
				} elseif ( isset( $result_data['zeusordd'] ) ) {
					$trans_id = $result_data['zeusordd'];
				}
				break;
			case 'zeus_conv':
			case 'zeus_bank':
				$trans_id = ( isset( $result_data['order_no'] ) ) ? $result_data['order_no'] : '';
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
		if ( ! in_array( $acting_flg, $this->pay_method, true ) ) {
			return;
		}

		if ( ! $entry['order']['total_full_price'] ) {
			return;
		}

		/* zeus card */
		if ( 'acting_zeus_card' === $acting_flg ) {
			$acting_opts = $this->get_acting_settings();
			/* Secure API */
			if ( 2 === (int) $acting_opts['connection'] && ! empty( $results['zeusordd'] ) && ! empty( $results['zeussuffix'] ) ) {
				if ( ! isset( $results['acting'] ) ) {
					$results['acting'] = 'zeus_card';
				}
				if ( 'on' === $acting_opts['howpay'] ) {
					if ( isset( $entry['order']['howpay'] ) && '0' === $entry['order']['howpay'] ) {
						$div_name          = 'div_' . $entry['order']['cbrand'];
						$results['howpay'] = '0';
						$results['div']    = $entry['order'][ $div_name ];
					} else {
						$results['howpay'] = '1';
						$results['div']    = '01';
					}
				}
				$usces->set_order_meta_value( 'acting_zeus_card', usces_serialize( $results ), $order_id );
				$usces->set_order_meta_value( 'wc_trans_id', $results['zeusordd'], $order_id );
				$usces->set_order_meta_value( 'trans_id', $results['zeusordd'], $order_id );
				if ( 'on' === $acting_opts['quickcharge'] && $usces->is_member_logged_in() ) {
					$usces->set_member_meta_value( 'zeus_pcid', '8888888888888882' );
					$usces->set_member_meta_value( 'zeus_partofcard', $results['zeussuffix'] );
				}
				if ( isset( $results['auth_code'] ) ) {
					$usces->set_order_meta_value( 'auth_code', $results['auth_code'], $order_id );
				}

				$tracking_id = ( isset( $results['wctid'] ) ) ? $results['wctid'] : '';
				if ( empty( $tracking_id ) ) {
					$tracking_id = ( isset( $results['sendpoint'] ) ) ? $results['sendpoint'] : '';
				}
				if ( ! empty( $tracking_id ) ) {
					$usces->set_order_meta_value( 'tracking_id', $tracking_id, $order_id );

					$order_ref = wel_zeus_get_order_ref( $results['zeusordd'] );
					$status    = ( empty( $order_ref['status'] ) ) ? 'payment' : $order_ref['status'];
					if ( ! isset( $results['money'] ) ) {
						$results['money'] = usces_crform( $entry['order']['total_full_price'], false, false, 'return', false );
					}
					wel_zeus_save_acting_log( $results, 'zeus_card', $status, 'OK', $order_id, $tracking_id );
				}

				/* Secure Link */
			} elseif ( 1 === (int) $acting_opts['connection'] && ! empty( $results['ordd'] ) ) {
				if ( 'on' === $acting_opts['howpay'] ) {
					if ( isset( $entry['order']['howpay'] ) && '0' === $entry['order']['howpay'] ) {
						$div_name          = 'div_' . $entry['order']['cbrand'];
						$results['howpay'] = '0';
						$results['div']    = $entry['order'][ $div_name ];
					} else {
						$results['howpay'] = '1';
						$results['div']    = '01';
					}
				}
				$usces->set_order_meta_value( 'acting_zeus_card', usces_serialize( $results ), $order_id );
				$usces->set_order_meta_value( 'wc_trans_id', $results['ordd'], $order_id );
				$usces->set_order_meta_value( 'trans_id', $results['ordd'], $order_id );

				if ( 'on' === $acting_opts['quickcharge'] && ! empty( $results['sendid'] ) ) {
					if ( isset( $results['cardnumber'] ) ) {
						$usces->set_member_meta_value( 'zeus_partofcard', $results['cardnumber'], $results['sendid'] );
						$usces->set_member_meta_value( 'zeus_pcid', '8888888888888882', $results['sendid'] );
					}
				}

				$tracking_id = ( isset( $results['sendpoint'] ) ) ? $results['sendpoint'] : '';
				if ( ! empty( $tracking_id ) ) {
					$usces->set_order_meta_value( 'tracking_id', $tracking_id, $order_id );

					$order_ref = wel_zeus_get_order_ref( $results['ordd'] );
					$status    = ( empty( $order_ref['status'] ) ) ? 'payment' : $order_ref['status'];
					wel_zeus_save_acting_log( $results, 'zeus_card', $status, 'OK', $order_id, $tracking_id );
				}
			}

			/* zeus_bank */
		} elseif ( 'acting_zeus_bank' === $acting_flg && isset( $results['order_no'] ) && isset( $results['tracking_no'] ) ) {
			if ( isset( $results['acting_return'] ) ) {
				unset( $results['acting_return'] );
				unset( $results['0'] );
				unset( $results['reg_order'] );
			}
			$usces->set_order_meta_value( 'acting_' . $results['tracking_no'], usces_serialize( $results ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $results['order_no'], $order_id );
			$usces->set_order_meta_value( 'trans_id', $results['order_no'], $order_id );

			/* zeus conv */
		} elseif ( 'acting_zeus_conv' === $acting_flg && ! empty( $results['wctid'] ) ) {
			if ( isset( $results['acting_return'] ) ) {
				unset( $results['acting_return'] );
				unset( $results['0'] );
				unset( $results['reg_order'] );
			}
			$usces->set_order_meta_value( 'acting_' . $results['wctid'], usces_serialize( $results ), $order_id );
			if ( ! empty( $results['order_no'] ) ) {
				$usces->set_order_meta_value( 'wc_trans_id', $results['order_no'], $order_id );
				$usces->set_order_meta_value( 'trans_id', $results['order_no'], $order_id );
			}
		}
	}

	/**
	 * 受注データ登録後処理
	 * usces_post_reg_orderdata
	 *
	 * @param string $order_id Order Number.
	 * @param array  $results Results.
	 */
	public function post_register_orderdata( $order_id, $results ) {
		if ( ! empty( $order_id ) && isset( $results['acting'] ) && 'zeus_card' === $results['acting'] ) {
			$tracking_id = ( isset( $results['wctid'] ) ) ? $results['wctid'] : '';
			if ( empty( $tracking_id ) ) {
				$tracking_id = ( isset( $results['sendpoint'] ) ) ? $results['sendpoint'] : '';
			}
			if ( ! empty( $tracking_id ) ) {
				// usces_ordered_acting_data( $_REQUEST['wctid'] ); 元々の処理
				usces_delete_ordered_acting_data( $tracking_id );
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
		$acting = ( isset( $_REQUEST['acting'] ) ) ? wp_unslash( $_REQUEST['acting'] ) : '';
		if ( 'zeus_card' === $acting ) {
			$form .= '<div class="support_box">';
			if ( isset( $_GET['code'] ) ) {
				$code  = filter_input( INPUT_GET, 'code' );
				$form .= '<br />エラーコード：' . esc_html( $code );
				if ( in_array( $code, array( '02130514', '02130517', '02130619', '02130620', '02130621', '02130640' ), true ) ) {
					$form .= '<br />カード番号が正しくないようです。';
				} elseif ( in_array( $code, array( '02130714', '02130717', '02130725', '02130814', '02130817', '02130825' ), true ) ) {
					$form .= '<br />カードの有効期限が正しくないようです。';
				} elseif ( in_array( $code, array( '02130922' ), true ) ) {
					$form .= '<br />カードの有効期限が切れているようです。';
				} elseif ( in_array( $code, array( '02131117', '02131123', '02131124' ), true ) ) {
					$form .= '<br />カードの名義が正しくないようです。';
				} elseif ( in_array( $code, array( '02131414', '02131417', '02131437' ), true ) ) {
					$form .= '<br />お客様情報の電話番号が正しくないようです。';
				} elseif ( in_array( $code, array( '02131527', '02131528', '02131529', '02131537' ), true ) ) {
					$form .= '<br />お客様情報のEメールアドレスが正しくないようです。';
				}
				$form .= '<br />
				<br />
				<a href="' . USCES_CUSTOMER_URL . '">もう一度決済を行う 》</a><br />';
			} else {
				if ( isset( $_GET['err_code'] ) ) {
					$form .= '<br />エラーコード：' . filter_input( INPUT_GET, 'err_code' );
				}
				$form .= '<br />
				カード番号を再入力する場合はこちらをクリックしてください。<br />
				<br />
				<a href="' . USCES_CUSTOMER_URL . '&re-enter=1">カード番号の再入力 》</a><br />';
			}
			$form .= '<br />
			株式会社ゼウス カスタマーサポート　（24時間365日対応）<br />
			電話番号：0570-02-3939　（つながらないときは 03-4334-0500）<br />
			E-mail:support@cardservice.co.jp
			</div>';

		} elseif ( 'zeus_conv' === $acting || 'zeus_bank' === $acting ) {
			$form .= '<div class="support_box">';
			if ( isset( $_GET['code'] ) ) {
				$code  = filter_input( INPUT_GET, 'code' );
				$form .= '<br />エラーコード：' . esc_html( $code );
				if ( in_array( $code, array( '800002', '0013' ), true ) ) {
					$form .= '<br />このコンビニはお取り扱いできません。詳細に関してはカスタマーサポートまでお問い合わせください。';
				} elseif ( in_array( $code, array( '900000', '0011' ), true ) ) {
					$form .= '<br />お申し込み情報が正しく入力されていないか、通信時にエラーが発生している可能性がございます。入力情報を再度ご確認いただいた上でお申し込みをいただくか、カスタマーサポートまでお問い合わせください。';
				} elseif ( in_array( $code, array( '0008' ), true ) ) {
					$form .= '<br />このコンビニはお取り扱いできません。別のコンビニをご選択いただき、再度お申し込みをいただくか、カスタマーサポートまでお問い合わせください。';
				}
			} else {
				if ( 'zeus_conv' === $acting ) {
					$form .= '<br />このコンビニはお取り扱いできません。詳細に関してはカスタマーサポートまでお問い合わせください。';
				} else {
					$form .= '<br />詳細に関してはカスタマーサポートまでお問い合わせください。';
				}
			}
			$form .= '<br /><br />
			<a href="' . USCES_CUSTOMER_URL . '">もう一度決済を行う 》</a><br /><br />
			株式会社ゼウス カスタマーサポート　（24時間365日対応）<br />
			電話番号：0570-08-3000　（つながらないときは 03-3498-9888）<br />
			E-mail:support@cardservice.co.jp
			</div>';
		}
		return $form;
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

		if ( ! $this->is_validity_acting( 'card' ) ) {
			return $l10n;
		}

		if ( 'delivery' === $usces->page ) {
			$acting_opts = $this->get_acting_settings();
			$pcid        = null;
			$partofcard  = null;
			if ( $usces->is_member_logged_in() ) {
				$member = $usces->get_member();
				if ( ! isset( $_GET['re-enter'] ) ) {
					$pcid       = $usces->get_member_meta_value( 'zeus_pcid', $member['ID'] );
					$partofcard = $usces->get_member_meta_value( 'zeus_partofcard', $member['ID'] );
				}
			}
			$l10n .= "'zeus_form': 'cart',\n";
			$l10n .= "'zeus_security': '" . $acting_opts['security'] . "',\n";
			$l10n .= "'zeus_quickcharge': '" . $acting_opts['quickcharge'] . "',\n";
			$l10n .= "'zeus_howpay': '" . $acting_opts['howpay'] . "',\n";
			$l10n .= "'zeus_thisyear': '" . date_i18n( 'Y' ) . "',\n";
			$l10n .= "'zeus_pcid': '" . $pcid . "',\n";
			$l10n .= "'zeus_partofcard': '" . $partofcard . "',\n";
			if ( ! empty( $pcid ) && ! empty( $partofcard ) ) {
				$member_update_settlement = add_query_arg(
					array(
						'usces_page' => 'member_update_settlement',
						're-enter'   => 1,
					),
					USCES_MEMBER_URL
				);
				$l10n                    .= "'zeus_cardupdate_url': '" . urlencode( $member_update_settlement ) . "',\n";
			}
		} elseif ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) {
			if ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' === filter_input( INPUT_GET, 'usces_page' ) || 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page' ) ) ) {
				$acting_opts = $this->get_acting_settings();
				$member      = $usces->get_member();
				$pcid        = $usces->get_member_meta_value( 'zeus_pcid', $member['ID'] );
				$partofcard  = $usces->get_member_meta_value( 'zeus_partofcard', $member['ID'] );
				$l10n       .= "'zeus_form': 'member',\n";
				$l10n       .= "'zeus_security': '" . $acting_opts['security'] . "',\n";
				$l10n       .= "'zeus_quickcharge': '" . $acting_opts['quickcharge'] . "',\n";
				$l10n       .= "'zeus_thisyear': '" . date_i18n( 'Y' ) . "',\n";
				$l10n       .= "'zeus_thismonth': '" . date_i18n( 'm' ) . "',\n";
				$l10n       .= "'zeus_pcid': '" . $pcid . "',\n";
				$l10n       .= "'zeus_partofcard': '" . $partofcard . "',\n";
			}
		}
		return $l10n;
	}

	/**
	 * Front scripts.
	 * wp_print_footer_scripts
	 */
	public function footer_scripts() {
		global $usces;

		if ( ! $this->is_validity_acting( 'card' ) ) {
			return;
		}

		/* 発送・支払方法ページ */
		if ( 'delivery' === $usces->page ) :
			$acting_opts = $this->get_acting_settings();
			if ( 'on' === $acting_opts['card_activate'] ) :
				wp_enqueue_style( 'zeus-token-style', USCES_FRONT_PLUGIN_URL . '/css/zeus_token.css', array(), USCES_VERSION );
				wp_enqueue_script( 'zeus-token-script', USCES_FRONT_PLUGIN_URL . '/js/zeus_token.js', array(), USCES_VERSION );
				wp_enqueue_script( 'usces_cart_zeus', USCES_FRONT_PLUGIN_URL . '/js/cart_zeus.js', array( 'jquery' ), USCES_VERSION, true );
				?>
<script type="text/javascript">
var zeusTokenIpcode = "<?php echo esc_attr( $acting_opts['clientip'] ); ?>";
</script>
				<?php
			endif;

			/* 内容確認ページ */
		elseif ( 'confirm' === $usces->page ) :
			$entry = $usces->cart->get_entry();
			$cart  = $usces->cart->get_cart();
			if ( empty( $cart ) || empty( $entry['order']['total_full_price'] ) ) {
				return;
			}

			$payment = usces_get_payments_by_name( $entry['order']['payment_name'] );
			if ( 'acting_zeus_card' === $payment['settlement'] && 'activate' === $payment['use'] ) :
				$acting_opts = $this->get_acting_settings();
				if ( $this->is_activate_card( 'api' ) && 1 === (int) $acting_opts['3dsecur'] ) :
					wp_enqueue_script( 'zeus-3ds-script', USCES_FRONT_PLUGIN_URL . '/js/zeus_3ds.js', array(), USCES_VERSION );
					wp_enqueue_style( 'usces-loading', USCES_FRONT_PLUGIN_URL . '/css/loading.css', array(), USCES_VERSION );
					ob_start();
					?>
<script>
	var loading_img = document.getElementById('zeus-loading');
	var purchase_button = document.getElementById('purchase_button');
	purchase_button.dataset.clicked = '';
	purchase_button.addEventListener( 'click', function(event) {
		purchase_button.dataset.clicked = 'clicked';
	});
	var purchase_form = document.getElementById('purchase_form');
	purchase_form.addEventListener( 'submit', function(event) {
		if( purchase_button.dataset.clicked === 'clicked' ) {
			loading_img.style.display = 'block';
			event.preventDefault();
			let params = new URLSearchParams();
			params.append("action","zeus_3dsecure_enrol");
			params.append("card_option",document.getElementById("card_option").value);
			params.append("token_key",document.getElementById("token_key").value);
			params.append("sendid",document.getElementById("sendid").value);
			params.append("sendpoint",document.getElementById("sendpoint").value);
			params.append("_nonce","<?php echo esc_attr( wp_create_nonce( 'acting_zeus_card' ) ); ?>");
			return fetch( uscesL10n.ajaxurl+"?uscesid="+uscesL10n.uscesid, {
				method:'POST',
				cache:'no-cache',
				body:params
			}).then( function(res) {
				return res.json();
			}).then( function(data) {
				//console.log('EnrolReq='+data);
				if( 'stock' == data.action && 'error' == data.status ) {
					location.href = "<?php echo esc_js( USCES_CART_URL ); ?>";
				}
				if( 'EnrolReq' == data.action && 'success' == data.status ) {
					purchase_button.disabled = true;
					try {
						setPareqParams(data.MD,data.iframeUrl,data.TermUrl,data.threeDSMehtod,data.iframeUrl);
					} catch(error) {
						purchase_button.disabled = false;
						loading_img.style.display = 'none';
					}
				} else if( 'EnrolReq' == data.action && 'outside' == data.status ) {
					if( null !== data.code ) {
						var outside = document.createElement('input');
						outside.setAttribute('type','hidden');
						outside.setAttribute('name','outside');
						outside.setAttribute('value',data.code);
					}
					var purchase = document.createElement('input');
					purchase.setAttribute('type','hidden');
					purchase.setAttribute('name','purchase');
					purchase.setAttribute('value','purchase');
					document.purchase_form.appendChild(outside);
					document.purchase_form.appendChild(purchase);
					document.purchase_form.submit();
				} else {
					location.href = "<?php echo esc_js( trailingslashit( USCES_CART_URL ) ); ?>?acting=zeus_card&acting_return=0&status="+data.status+"&code="+data.code;
				}
			}).catch((reason) => {
				//console.log(reason);
				loading_img.style.display = 'none';
			});
		}
	});
</script>
					<?php
					$scripts = ob_get_contents();
					ob_end_clean();
					$scripts = apply_filters( 'usces_filter_purchase_button_script_zeus', $scripts );
					echo $scripts;
				endif;
			endif;

			/* マイページ */
		elseif ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) :
			if ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' === filter_input( INPUT_GET, 'usces_page' ) || 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page' ) ) ) :
				$acting_opts = $this->get_acting_settings();
				wp_enqueue_style( 'zeus-token-style', USCES_FRONT_PLUGIN_URL . '/css/zeus_token.css', array(), USCES_VERSION );
				wp_enqueue_script( 'zeus-token-script', USCES_FRONT_PLUGIN_URL . '/js/zeus_token.js', array(), USCES_VERSION );
				wp_enqueue_script( 'usces_member_zeus', USCES_FRONT_PLUGIN_URL . '/js/member_zeus.js', array( 'jquery' ), USCES_VERSION, true );
				?>
<script type="text/javascript">
var zeusTokenIpcode = "<?php echo esc_attr( $acting_opts['clientip'] ); ?>";
</script>
				<?php
				print_google_recaptcha_response( filter_input( INPUT_GET, 'usces_page' ), 'member-card-info', 'member_update_settlement' );
			else :
				$member = $usces->get_member();
				if ( usces_have_member_continue_order( $member['ID'] ) || usces_have_member_regular_order( $member['ID'] ) ) :
					?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$("input[name='deletemember']").css("display","none");
});
</script>
					<?php
				endif;
			endif;
		endif;
	}

	/**
	 * 利用可能な決済モジュール
	 * usces_filter_available_payment_method
	 *
	 * @param  array $payments Payments.
	 * @return array
	 */
	public function set_available_payment_method( $payments ) {
		global $usces;

		if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) {
			$payment_method = array();
			foreach ( (array) $payments as $id => $payment ) {
				if ( isset( $payment['settlement'] ) && 'acting_zeus_card' === $payment['settlement'] ) {
					$payment_method[ $id ] = $payments[ $id ];
					break;
				}
			}
			if ( ! empty( $payment_method ) ) {
				$payments = $payment_method;
			}
		}
		return $payments;
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
			if ( 'on' !== $acting_opts['quickcharge'] ) {
				return;
			}

			if ( isset( $_REQUEST['usces_page'] ) && 'member_update_settlement' === $_REQUEST['usces_page'] ) {
				add_filter( 'usces_filter_states_form_js', array( $this, 'states_form_js' ) );
				$usces->page = 'member_update_settlement';
				$this->member_update_settlement_form();
				exit();

			} elseif ( isset( $_REQUEST['usces_page'] ) && 'member_register_settlement' === $_REQUEST['usces_page'] && 'on' === $acting_opts['batch'] ) {
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
	 * @param  string $form HTML Form.
	 * @param  array  $member Members data.
	 * @return string
	 */
	public function update_settlement( $form, $member ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( 'on' === $acting_opts['quickcharge'] ) {
			$member     = $usces->get_member();
			$pcid       = $usces->get_member_meta_value( 'zeus_pcid', $member['ID'] );
			$partofcard = $usces->get_member_meta_value( 'zeus_partofcard', $member['ID'] );
			if ( ! empty( $pcid ) && ! empty( $partofcard ) ) {
				$update_settlement_url = add_query_arg(
					array(
						'usces_page' => 'member_update_settlement',
						're-enter'   => 1,
					),
					USCES_MEMBER_URL
				);
				$form                 .= '<li class="gotoedit"><a href="' . $update_settlement_url . '">' . __( 'Change the credit card is here >>', 'usces' ) . '</a></li>';
			} elseif ( 'on' === $acting_opts['batch'] ) {
				$register_settlement_url = add_query_arg(
					array(
						'usces_page' => 'member_register_settlement',
						're-enter'   => 1,
					),
					USCES_MEMBER_URL
				);
				$form                   .= '<li class="gotoedit"><a href="' . $register_settlement_url . '">' . __( 'Credit card registration is here >>', 'usces' ) . '</a></li>';
			}
		}
		return $form;
	}

	/**
	 * クレジットカード登録・変更ページ
	 */
	public function member_update_settlement_form() {
		global $usces;

		$script   = '';
		$message  = '';
		$form     = '';
		$register = ( 'member_register_settlement' === $usces->page ) ? true : false;

		$update_settlement_url = add_query_arg(
			array(
				'usces_page' => 'member_update_settlement',
				'settlement' => 1,
				're-enter'   => 1,
			),
			USCES_MEMBER_URL
		);

		$member      = $usces->get_member();
		$acting_opts = $this->get_acting_settings();

		if ( isset( $_POST['zeus_card_update'] ) ) {
			if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'wc_nonce' ), 'member_update_settlement' ) || empty( $_POST['zeus_token_value'] ) ) {
				$usces->error_message .= __( 'failure in update', 'usces' );
			} else {
				$verify_action = wel_verify_update_settlement( $member['ID'] );
				if ( ! $verify_action ) {
					$usces->error_message .= '<p>' . __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '</p>';
				} else {
					$params                 = array();
					$params['send']         = 'mall';
					$params['clientip']     = $acting_opts['clientip'];
					$params['token_key']    = filter_input( INPUT_POST, 'zeus_token_value' );
					$params['cardnumber']   = '8888888888888882';
					$params['expyy']        = '00';
					$params['expmm']        = '00';
					$params['money']        = '0';
					$params['telno']        = str_replace( '-', '', $member['tel'] );
					$params['email']        = $member['mailaddress1'];
					$params['sendid']       = $member['ID'];
					$params['sendpoint']    = usces_rand();
					$params['pubsec']       = 'yes';
					$params['printord']     = '';
					$params['return_value'] = 'yes';

					$page = $this->secure_link_batch( $acting_opts['card_url'], $params );
					if ( false !== strpos( $page, 'Success_order' ) ) {
						$usces->error_message = '';
						$message              = __( 'Successfully updated.', 'usces' );
						if ( ! empty( $_POST['zeus_token_masked_card_no'] ) ) {
							$partofcard = substr( filter_input( INPUT_POST, 'zeus_token_masked_card_no' ), -4 );
							$usces->set_member_meta_value( 'zeus_partofcard', $partofcard );
						}
						$pcid = $usces->get_member_meta_value( 'zeus_pcid', $member['ID'] );
						if ( empty( $pcid ) ) {
							$usces->set_member_meta_value( 'zeus_pcid', '8888888888888882' );
						}
						$this->send_update_settlement_mail();
					} else {
						$err_code              = $this->get_err_code( $page );
						$usces->error_message .= __( 'failure in update', 'usces' );
					}

					if ( '' !== $message ) {
						$script .= "
<script type=\"text/javascript\">
jQuery.event.add(window,'load',function() {
	alert('" . $message . "');
});
</script>";
					}
				}
			}
		}
		$error_message = apply_filters( 'usces_filter_member_update_settlement_error_message', $usces->error_message );

		ob_start();
		get_header();
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
	<div class="header_explanation">
			<?php do_action( 'usces_action_member_update_settlement_page_header' ); ?>
	</div>
	<div class="error_message"><?php echo wp_kses_post( $error_message ); ?></div>
	<form id="member-card-info" name="member_update_settlement" action="<?php echo esc_url( $update_settlement_url ); ?>" method="post" onKeyDown="if(event.keyCode == 13) {return false;}">
		<input type="hidden" name="acting" value="<?php echo esc_attr( $this->paymod_id ); ?>">
		<table class="customer_form" id="<?php echo esc_attr( $this->paymod_id ); ?>">
		<tr><th scope="row"><?php esc_html_e( 'Credit card information', 'usces' ); ?></th><td id="zeus_token_card_info_area"></td></tr>
		</table>
		<div class="send">
			<?php if ( $register ) : ?>
			<input type="hidden" name="zeus_card_update" value="register" />
			<input type="button" id="card-register" class="card-update" value="<?php esc_attr_e( 'Register' ); ?>" />
			<?php else : ?>
			<input type="hidden" name="zeus_card_update" value="update" />
			<input type="button" id="card-update" class="card-update" value="<?php esc_attr_e( 'update it', 'usces' ); ?>" />
			<?php endif; ?>
			<input type="button" name="back" value="<?php esc_attr_e( 'Back to the member page.', 'usces' ); ?>" onclick="location.href='<?php echo esc_url( USCES_MEMBER_URL ); ?>'" />
			<input type="button" name="top" value="<?php esc_attr_e( 'Back to the top page.', 'usces' ); ?>" onclick="location.href='<?php echo esc_url( home_url() ); ?>'" />
		</div>
			<?php do_action( 'usces_action_member_update_settlement_page_inform' ); ?>
			<?php wp_nonce_field( 'member_update_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="footer_explanation">
			<?php do_action( 'usces_action_member_update_settlement_page_footer' ); ?>
	</div>
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
		if ( '' !== $script ) {
			echo $script; // no escape due to script.
		}
		$sidebar = apply_filters( 'usces_filter_member_update_settlement_page_sidebar', 'cartmember' );
		if ( ! empty( $sidebar ) ) {
			get_sidebar( $sidebar );
		}

		get_footer();
		$form = ob_get_contents();
		ob_end_clean();

		echo $form; // no escape.
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
		$to_name     = sprintf( _x( '%s', 'honorific', 'usces' ), $name );

		$message  = '--------------------------------' . "\r\n";
		$message .= __( 'Member ID', 'usces' ) . ' : ' . $member['ID'] . "\r\n";
		$message .= __( 'Name', 'usces' ) . ' : ' . $to_name . "\r\n";
		$message .= __( 'e-mail adress', 'usces' ) . ' : ' . $member['mailaddress1'] . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message .= __( 'If you have not requested this email, sorry to trouble you, but please contact us.', 'usces' ) . "\r\n\r\n";
		$message  = apply_filters( 'usces_filter_send_update_settlement_mail_message', $message, $member );
		$message  = apply_filters( 'usces_filter_send_update_settlement_mail_message_head', $mail_header, $member ) . $message . apply_filters( 'usces_filter_send_update_settlement_mail_message_foot', $mail_footer, $member ) . "\r\n";
		$message  = sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n" . $message;

		$send_para = array(
			'to_name'      => $to_name,
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
		$admin_message .= __( 'Name', 'usces' ) . ' : ' . $to_name . "\r\n";
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
			'subject'      => $subject . '( ' . $to_name . ' )',
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
				$latest_log   = $this->get_acting_latest_log( $con_order_id, 0, 'ALL' );
				if ( ! empty( $latest_log ) ) {
					$next_charging = ( empty( $continue_data['con_next_contracting'] ) ) ? dlseller_next_charging( $con_order_id ) : $continue_data['con_next_contracting'];
					$message      .= ' ( ' . __( 'Next Withdrawal Date', 'dlseller' ) . ' : ' . date_i18n( __( 'Y/m/d' ), strtotime( $next_charging ) );
					if ( 0 < (int) $continue_data['con_interval'] ) {
						$next_contracting = ( empty( $continue_data['con_next_contracting'] ) ) ? dlseller_next_contracting( $con_order_id ) : $continue_data['con_next_contracting'];
						$message         .= ', ' . __( 'Renewal Date', 'dlseller' ) . ' : ' . date_i18n( __( 'Y/m/d' ), strtotime( $next_contracting ) );
					}
					$message .= ' )';
					if ( isset( $latest_log['result'] ) && 'OK' !== $latest_log['result'] ) {
						$message .= ' ' . __( 'Condition', 'dlseller' ) . ' : ' . __( 'Settlement error', 'usces' );
						if ( ! empty( $latest_log['tracking_id'] ) ) {
							$message .= ' ( ' . __( 'Transaction ID', 'usces' ) . ' : ' . $latest_log['tracking_id'] . ' )';
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
				if ( isset( $payment['settlement'] ) && 'acting_zeus_card' !== $payment['settlement'] ) {
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
					$latest_log   = $this->get_acting_latest_log( $reg_order_id, 0, 'ALL' );
					if ( isset( $latest_log['result'] ) && 'OK' !== $latest_log['result'] ) {
						$message .= ' ' . __( 'Condition', 'autodelivery' ) . ' : ' . __( 'Settlement error', 'usces' );
						$trans_id = $usces->get_order_meta_value( 'trans_id', $reg_order_id );
						if ( $trans_id ) {
							$message .= ' ( ' . __( 'Transaction ID', 'usces' ) . ' : ' . $trans_id . ' )';
						}
					} else {
						if ( $this->isdate( $regular_data['regdet_schedule_date'] ) ) {
							$message .= ' ( ' . __( 'Scheduled order date', 'autodelivery' ) . ' : ' . date_i18n( __( 'Y/m/d' ), strtotime( $regular_data['regdet_schedule_date'] ) ) . ' )';
						}
					}
					$message .= "\r\n";
				}
			}
			if ( '' !== $message ) {
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
	 * 会員データ削除チェック
	 * usces_filter_delete_member_check
	 *
	 * @param  bool $del Deletable.
	 * @param  int  $member_id Member ID.
	 * @return bool
	 */
	public function delete_member_check( $del, $member_id ) {
		if ( usces_have_member_continue_order( $member_id ) || usces_have_member_regular_order( $member_id ) ) {
			$del = false;
		}
		return $del;
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
			$zeus_card = false;
			foreach ( (array) $payments_restriction as $key => $payment ) {
				if ( isset( $payment['settlement'] ) && 'acting_zeus_card' === $payment['settlement'] ) {
					$zeus_card = true;
				}
			}
			if ( ! $zeus_card ) {
				$payments               = usces_get_system_option( 'usces_payment_method', 'settlement' );
				$payments_restriction[] = $payments['acting_zeus_card'];
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
		if ( ! array_key_exists( 'acting_zeus_card', $payment_method ) ) {
			$payment_method[] = 'acting_zeus_card';
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
		if ( 99 === (int) $usces_item['item_chargingday'] ) {
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
		if ( isset( $continue_data['acting'] ) && 'acting_zeus_card' === $continue_data['acting'] ) {
			$url       = admin_url( 'admin.php?page=usces_continue&continue_action=settlement_zeus_card&member_id=' . esc_attr( $member_id ) . '&order_id=' . esc_attr( $order_id ) );
			$condition = '<a href="' . $url . '">' . __( 'Detail', 'usces' ) . '</a>';
			if ( 'continuation' === $continue_data['status'] ) {
				$latest_log = $this->get_acting_latest_log( $order_id, 0, 'ALL' );
				if ( isset( $latest_log['result'] ) && ! in_array( $latest_log['result'], $this->payment_normal_results, true ) ) {
					$condition .= '<div class="acting-status zeus-error">' . __( 'Settlement error', 'usces' ) . '</div>';
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
		if ( 'settlement_zeus_card' === $continue_action ) {
			$member_id = filter_input( INPUT_GET, 'member_id' );
			$order_id  = filter_input( INPUT_GET, 'order_id' );
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
		if ( isset( $payment['settlement'] ) && 'acting_zeus_card' !== $payment['settlement'] ) {
			return;
		}

		$continue_data = $this->get_continuation_data( $member_id, $order_id );
		if ( 'acting_zeus_card' !== $continue_data['acting'] ) {
			return;
		}

		$con_id     = $continue_data['con_id'];
		$curent_url = $_SERVER['REQUEST_URI'];

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

		$log_data = $this->get_acting_log( $order_id, 0, 'ALL' );
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
				echo '<option value="0"' . selected( (int) $contracted_year, 0, false ) . '></option>';
				for ( $i = 0; $i <= 10; $i++ ) {
					$year = (int) $this_year + $i;
					echo '<option value="' . esc_html( $year ) . '"' . selected( (int) $contracted_year, $year, false ) . '>' . esc_html( $year ) . '</option>';
				}
				echo '</select>-<select id="contracted-month">';
				echo '<option value="0"' . selected( (int) $contracted_month, 0, false ) . '></option>';
				for ( $i = 1; $i <= 12; $i++ ) {
					$month = sprintf( '%02d', $i );
					echo '<option value="' . esc_html( $month ) . '"' . selected( (int) $contracted_month, $i, false ) . '>' . esc_html( $month ) . '</option>';
				}
				echo '</select>-<select id="contracted-day">';
				echo '<option value="0"' . selected( (int) $contracted_day, 0, false ) . '></option>';
				for ( $i = 1; $i <= 31; $i++ ) {
					$day = sprintf( '%02d', $i );
					echo '<option value="' . esc_html( $day ) . '"' . selected( (int) $contracted_day, $i, false ) . '>' . esc_html( $day ) . '</option>';
				}
				echo '</select>';
				?>
				</td>
				<th><?php esc_html_e( 'Next Withdrawal Date', 'dlseller' ); ?></th>
				<td>
				<?php
				echo '<select id="charged-year">';
				echo '<option value="0"' . selected( (int) $charged_year, 0, false ) . '></option>';
				echo '<option value="' . esc_html( $this_year ) . '"' . selected( (int) $charged_year, $this_year, false ) . '>' . esc_html( $this_year ) . '</option>';
				$next_year = (int) $this_year + 1;
				echo '<option value="' . esc_html( $next_year ) . '"' . selected( (int) $charged_year, $next_year, false ) . '>' . esc_html( $next_year ) . '</option>';
				echo '</select>-<select id="charged-month">';
				echo '<option value="0"' . selected( (int) $charged_month, 0, false ) . '></option>';
				for ( $i = 1; $i <= 12; $i++ ) {
					$month = sprintf( '%02d', $i );
					echo '<option value="' . esc_html( $month ) . '"' . selected( (int) $charged_month, $i, false ) . '>' . esc_html( $month ) . '</option>';
				}
				echo '</select>-<select id="charged-day">';
				echo '<option value="0"' . selected( (int) $charged_day, 0, false ) . '></option>';
				for ( $i = 1; $i <= 31; $i++ ) {
					$day = sprintf( '%02d', $i );
					echo '<option value="' . esc_html( $day ) . '"' . selected( (int) $charged_day, $i, false ) . '>' . esc_html( $day ) . '</option>';
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
				<?php if ( 'continuation' === $continue_data['status'] ) : ?>
					<option value="continuation" selected><?php esc_html_e( 'Continuation', 'dlseller' ); ?></option>
					<option value="cancellation"><?php esc_html_e( 'Stop', 'dlseller' ); ?></option>
				<?php else : ?>
					<option value="cancellation" selected><?php esc_html_e( 'Cancellation', 'dlseller' ); ?></option>
					<option value="continuation"><?php esc_html_e( 'Resumption', 'dlseller' ); ?></option>
				<?php endif; ?>
				<?php
					$dlseller_status_options = ob_get_contents();
					ob_end_clean();
					$dlseller_status_options = apply_filters( 'usces_filter_continuation_charging_status_options', $dlseller_status_options, $continue_data );
					wel_esc_script_e( $dlseller_status_options );
				?>
				</select></td>
				<td colspan="2"><input id="continuation-update" type="button" class="button button-primary" value="<?php esc_attr_e( 'Update' ); ?>" /></td>
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
		<th scope="col">オーダーNo</th>
		<th scope="col"><?php esc_html_e( 'Settlement amount', 'usces' ); ?></th>
		<th scope="col"><?php esc_html_e( 'Processing classification', 'usces' ); ?></th>
		<th scope="col">&nbsp;</th>
	</tr>
	</thead>
		<?php
		foreach ( (array) $log_data as $data ) :
			$log         = usces_unserialize( $data['log'] );
			$order_no    = $this->get_order_no( $log );
			$tracking_id = $data['tracking_id'];
			$status      = $this->get_acting_status( $order_id, $tracking_id, 'ALL' );
			$class       = ' card-' . $status;
			$status_name = $this->get_status_name( $status );
			$amount      = usces_crform( $data['amount'], false, true, 'return', true );
			?>
	<tbody>
	<tr>
		<td><?php echo esc_html( $num ); ?></td>
		<td><?php echo esc_html( $data['datetime'] ); ?></td>
		<td><?php echo esc_html( $order_no ); ?></td>
		<td class="amount"><?php echo esc_attr( $amount ); ?></td>
		<td><span id="settlement-status-<?php echo esc_attr( $num ); ?>"><span class="acting-status<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $status_name ); ?></span></span></td>
		<td>
			<input type="button" class="button settlement-information" id="settlement-information-<?php echo esc_attr( $tracking_id ); ?>" data-tracking_id="<?php echo esc_attr( $tracking_id ); ?>" data-num="<?php echo esc_attr( $num ); ?>" value="<?php esc_attr_e( 'Settlement info', 'usces' ); ?>">
		</td>
	</tr>
	</tbody>
			<?php
			$num--;
		endforeach;
		?>
</table>
</div><!--datatable-->
<input name="member_id" type="hidden" id="member_id" value="<?php echo esc_attr( $member_id ); ?>" />
<input name="order_id" type="hidden" id="order_id" value="<?php echo esc_attr( $order_id ); ?>" />
<input name="con_id" type="hidden" id="con_id" value="<?php echo esc_attr( $con_id ); ?>" />
<input name="usces_referer" type="hidden" id="usces_referer" value="<?php echo esc_url( $curent_url ); ?>" />
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

		if ( ! usces_is_membersystem_state() || 0 >= $continue_data['price'] || 'acting_zeus_card' !== $continue_data['acting'] ) {
			return;
		}

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		if ( ! $order_data || $usces->is_status( 'cancel', $order_data['order_status'] ) ) {
			return;
		}

		$acting_opts = $this->get_acting_settings();
		if ( 'on' !== $acting_opts['quickcharge'] || 'on' !== $acting_opts['batch'] ) {
			return;
		}

		$tracking_id = usces_acting_key();
		$amount      = usces_crform( $continue_data['price'], false, false, 'return', false );

		$params               = array();
		$params['clientip']   = $acting_opts['clientip'];
		$params['cardnumber'] = '9999999999999992';
		$params['expyy']      = '00';
		$params['expmm']      = '00';
		$params['money']      = $amount;
		$params['send']       = 'mall';
		$params['telno']      = '0000000000';
		$params['email']      = $this->get_email( $order_id );
		$params['sendid']     = $member_id;
		$params['sendpoint']  = $tracking_id;
		$params['pubsec']     = 'yes';
		$params['printord']   = 'yes';

		$page = $this->secure_link_batch( $acting_opts['card_url'], $params );
		if ( false !== strpos( $page, 'Success_order' ) ) {
			$status       = 'OK';
			$data         = array( 'result' => 'OK' );
			$new_order_no = $this->get_order_number( $page );
			if ( ! empty( $new_order_no ) ) {
				$order_ref = wel_zeus_get_order_ref( $new_order_no );
				if ( 'payment' === $order_ref['status'] || 'auth' === $order_ref['status'] ) {
					wel_zeus_save_acting_log( $order_ref, 'zeus_card', $order_ref['status'], $status, $order_id, $tracking_id );
					$this->auto_settlement_mail( $member_id, $order_id, $data, $continue_data );
					do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $order_ref );
				} else {
					if ( ! isset( $params['ordd'] ) ) {
						$params['ordd'] = $new_order_no;
					}
					wel_zeus_save_acting_log( $params, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
					$this->auto_settlement_mail( $member_id, $order_id, $data, $continue_data );
					$order_ref = wel_zeus_get_order_ref( $new_order_no );
					do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $params );
				}
			} else {
				wel_zeus_save_acting_log( $params, 'zeus_card', 'payment', $status, $order_id, $tracking_id );
				$this->auto_settlement_mail( $member_id, $order_id, $data, $continue_data );
				do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $params );
			}
		} else {
			$status = $this->get_error_results( $page );
			if ( empty( $status ) ) {
				$status = 'ERROR';
			}
			$data = explode( "\r\n", $page );
			$log  = array(
				'acting' => 'zeus_card',
				'key'    => $tracking_id,
				'result' => $status,
				'data'   => $data,
			);
			usces_save_order_acting_error( $log );
			wel_zeus_save_acting_log( $data, 'zeus_card', 'dlseller', $status, $order_id, $tracking_id );
			$this->auto_settlement_error_mail( $member_id, $order_id, array( 'result' => $status ), $continue_data );
			do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $data );
		}
	}

	/**
	 * 自動継続課金処理メール（正常）
	 *
	 * @param  int   $member_id Member ID.
	 * @param  int   $order_id Order number.
	 * @param  array $response_data Response data.
	 * @param  array $continue_data Continuation data.
	 */
	public function auto_settlement_mail( $member_id, $order_id, $response_data, $continue_data ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$order_data  = $usces->get_order_data( $order_id, 'direct' );
		$mail_body   = $this->auto_settlement_message( $member_id, $order_id, $order_data, $response_data, $continue_data, false );

		if ( 'on' === $acting_opts['auto_settlement_mail'] ) {
			$subject     = apply_filters( 'usces_filter_zeus_auto_settlement_mail_subject', __( 'Announcement of automatic continuing charging process', 'usces' ), $member_id, $order_id, $order_data, $response_data, $continue_data );
			$member_info = $usces->get_member_info( $member_id );
			$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$mail_data   = usces_mail_data();
			$mail_header = '';
			if ( isset( $usces->options['put_customer_name'] ) && 1 === (int) $usces->options['put_customer_name'] ) {
				$mail_header .= sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n";
			}
			$mail_header .= __( 'We will report automated accounting process was carried out as follows.', 'usces' ) . "\r\n\r\n";
			$mail_footer  = __( 'If you have any questions, please contact us.', 'usces' ) . "\r\n\r\n" . $mail_data['footer']['thankyou'];
			$message      = apply_filters( 'usces_filter_zeus_auto_settlement_mail_header', $mail_header, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_zeus_auto_settlement_mail_body', $mail_body, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_zeus_auto_settlement_mail_footer', $mail_footer, $member_id, $order_id, $order_data, $response_data, $continue_data );
			$headers      = apply_filters( 'usces_filter_zeus_auto_settlement_mail_headers', '' );
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

		$ok                                     = ( empty( $this->continuation_charging_mail['OK'] ) ) ? 0 : $this->continuation_charging_mail['OK'];
		$this->continuation_charging_mail['OK'] = $ok + 1;
		$this->continuation_charging_mail['mail'][] = $mail_body;
	}

	/**
	 * 自動継続課金処理メール（エラー）
	 *
	 * @param  int   $member_id Member ID.
	 * @param  int   $order_id Order number.
	 * @param  array $response_data Response data.
	 * @param  array $continue_data Continuation data.
	 */
	public function auto_settlement_error_mail( $member_id, $order_id, $response_data, $continue_data ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$order_data  = $usces->get_order_data( $order_id, 'direct' );
		$mail_body   = $this->auto_settlement_message( $member_id, $order_id, $order_data, $response_data, $continue_data, false );

		if ( 'on' === $acting_opts['auto_settlement_mail'] ) {
			$subject     = apply_filters( 'usces_filter_zeus_auto_settlement_error_mail_subject', __( 'Announcement of automatic continuing charging process', 'usces' ), $member_id, $order_id, $order_data, $response_data, $continue_data );
			$member_info = $usces->get_member_info( $member_id );
			$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$mail_data   = usces_mail_data();
			$mail_header = '';
			if ( isset( $usces->options['put_customer_name'] ) && 1 === (int) $usces->options['put_customer_name'] ) {
				$mail_header .= sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n";
			}
			$mail_header .= __( 'We will reported that an error occurred in automated accounting process.', 'usces' ) . "\r\n\r\n";
			$mail_footer  = __( 'If you have any questions, please contact us.', 'usces' ) . "\r\n\r\n" . $mail_data['footer']['thankyou'];
			$message      = apply_filters( 'usces_filter_zeus_auto_settlement_error_mail_header', $mail_header, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_zeus_auto_settlement_error_mail_body', $mail_body, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_zeus_auto_settlement_error_mail_footer', $mail_footer, $member_id, $order_id, $order_data, $response_data, $continue_data );
			$headers      = apply_filters( 'usces_filter_zeus_auto_settlement_error_mail_headers', '' );
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
	 * @param  boolean $html HTML mail.
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
				if ( isset( $data['result'] ) && in_array( $data['result'], $this->payment_normal_results, true ) ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Result', 'usces' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= __( 'Normal done', 'usces' );
					$message .= '</td></tr>';
				} else {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Result', 'usces' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= __( 'Error', 'usces' );
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
				if ( isset( $data['result'] ) && in_array( $data['result'], $this->payment_normal_results, true ) ) {
					$message .= __( 'Result', 'usces' ) . ' : ' . __( 'Normal done', 'usces' ) . "\r\n";
				} else {
					$message .= __( 'Result', 'usces' ) . ' : ' . __( 'Error', 'usces' ) . "\r\n";
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
		$admin_subject = apply_filters( 'usces_filter_zeus_autobilling_email_admin_subject', __( 'Automatic Continuing Charging Process Result', 'usces' ) . ' ' . $today, $today );
		$admin_footer  = apply_filters( 'usces_filter_zeus_autobilling_email_admin_mail_footer', __( 'For details, please check on the administration panel > Continuous charge member list > Continuous charge member information.', 'usces' ) );
		$admin_message = __( 'Report that automated accounting process has been completed.', 'usces' ) . "\r\n\r\n"
			. __( 'Processing date', 'usces' ) . ' : ' . wp_date( 'Y-m-d H:i:s' ) . "\r\n"
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
	 * 決済オプション取得
	 *
	 * @return array
	 */
	public function get_acting_settings() {
		global $usces;

		$acting_settings = ( isset( $usces->options['acting_settings'][ $this->paymod_id ] ) ) ? $usces->options['acting_settings'][ $this->paymod_id ] : array();
		return apply_filters( 'usces_filter_get_acting_settings_zeus', $acting_settings );
	}

	/**
	 * Secure API
	 * 3D Secure Enrol.
	 */
	public function zeus_3dsecure_enrol() {
		global $usces;

		$entry = $usces->cart->get_entry();
		if ( empty( $entry ) ) {
			$result_data = array(
				'action' => 'session',
				'status' => 'error',
			);
			wp_send_json( $result_data );
		}

		$usces->error_message = $usces->zaiko_check();
		if ( '' !== $usces->error_message || 0 === (int) $usces->cart->num_row() ) {
			$result_data = array(
				'action' => 'stock',
				'status' => 'error',
			);
			wp_send_json( $result_data );
		}

		$acting_opts = $this->get_acting_settings();
		$result_data = array();
		if ( $this->is_activate_card( 'api' ) && 1 === (int) $acting_opts['3dsecur'] ) {
			$sendid    = ( 'on' === $acting_opts['quickcharge'] && isset( $_POST['sendid'] ) ) ? filter_input( INPUT_POST, 'sendid' ) : '';
			$sendpoint = filter_input( INPUT_POST, 'sendpoint', FILTER_DEFAULT, array( 'options' => array( 'default' => '' ) ) );
			$_nonce    = filter_input( INPUT_POST, '_nonce', FILTER_DEFAULT, array( 'options' => array( 'default' => wp_create_nonce( 'acting_zeus_card' ) ) ) );
			$uscesid   = filter_input( INPUT_GET, 'uscesid', FILTER_DEFAULT, array( 'options' => array( 'default' => $usces->get_uscesid( false ) ) ) );

			$data                               = array();
			$data['authentication']['clientip'] = $acting_opts['clientip'];
			$data['authentication']['key']      = $acting_opts['authkey'];
			if ( 'on' === $acting_opts['quickcharge'] && isset( $_POST['card_option'] ) && 'prev' === filter_input( INPUT_POST, 'card_option' ) && ! empty( $sendid ) ) {
				$data['card']['history']['key']    = 'sendid';
				$data['card']['history']['action'] = 'send_email';
			} elseif ( isset( $_POST['token_key'] ) ) {
				$data['token_key'] = filter_input( INPUT_POST, 'token_key' );
			}
			$data['payment']['amount'] = usces_crform( $entry['order']['total_full_price'], false, false, 'return', false );
			if ( isset( $entry['order']['cbrand'] ) && isset( $entry['order']['howpay'] ) && WCUtils::is_zero( $entry['order']['howpay'] ) ) {
				$div_name                 = 'div_' . $entry['order']['cbrand'];
				$data['payment']['count'] = $entry['order'][ $div_name ];
			} else {
				$data['payment']['count'] = '01';
			}
			$data['user']['telno']         = str_replace( '-', '', $entry['customer']['tel'] );
			$data['user']['email']         = $entry['customer']['mailaddress1'];
			$data['uniq_key']['sendid']    = $sendid;
			$data['uniq_key']['sendpoint'] = $sendpoint;
			$data['use_3ds2_flag']         = '1';

			$enrol_req  = '<?xml version="1.0" encoding="utf-8"?>';
			$enrol_req .= '<request service="secure_link_3d" action="enroll">';
			$enrol_req .= $this->assoc2xml( $data );
			$enrol_req .= '</request>';

			$xml = $this->get_xml( $acting_opts['card_secureurl'], $enrol_req );
			if ( empty( $xml ) ) {
				$log = array(
					'acting' => 'zeus_card_API(3D Enrol)',
					'key'    => $sendpoint,
					'result' => 'EnrolRes Error',
					'data'   => $enrol_req,
				);
				usces_save_order_acting_error( $log );
				$result_data = array(
					'action' => 'EnrolReq',
					'status' => 'error',
				);
			} else {
				$enrol_res = $this->xml2assoc( $xml );
				if ( 'outside' === $enrol_res['response']['result']['status'] ) {
					usces_ordered_acting_data( $sendpoint, 'propriety' );
					$result_data = array(
						'action' => 'EnrolReq',
						'status' => $enrol_res['response']['result']['status'],
						'xid'    => $enrol_res['response']['xid'],
						'code'   => $enrol_res['response']['result']['code'],
					);
				} elseif ( 'success' === $enrol_res['response']['result']['status'] ) {
					usces_ordered_acting_data( $sendpoint, 'propriety' );
					$term_data   = array(
						'purchase'  => '1',
						'sendpoint' => $sendpoint,
						'_nonce'    => $_nonce,
						'uscesid'   => $uscesid,
					);
					$term_url    = add_query_arg( $term_data, USCES_CART_URL );
					$result_data = array(
						'action'        => 'EnrolReq',
						'status'        => $enrol_res['response']['result']['status'],
						'MD'            => $enrol_res['response']['xid'],
						'PaReq'         => $enrol_res['response']['redirection']['PaReq'],
						'TermUrl'       => $term_url,
						'threeDSMehtod' => '2',
						'iframeUrl'     => $enrol_res['response']['iframeUrl'],
					);
				} else {
					$log = array(
						'acting' => 'zeus_card_API(3D Enrol)',
						'key'    => $sendpoint,
						'result' => $enrol_res['response']['result']['status'] . ':' . $enrol_res['response']['result']['code'],
						'data'   => $enrol_res,
					);
					usces_save_order_acting_error( $log );
					$result_data = array(
						'action' => 'EnrolReq',
						'status' => $enrol_res['response']['result']['status'],
						'code'   => $enrol_res['response']['result']['code'],
					);
				}
			}
		}
		wp_send_json( $result_data );
	}

	/**
	 * Secure API
	 * 3D Secure Authentication.
	 * usces_zeus_3dsecure_auth
	 * ( call from acting_processing )
	 */
	public function zeus_3dsecure_auth() {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$sendpoint   = filter_input( INPUT_GET, 'sendpoint', FILTER_DEFAULT, array( 'options' => array( 'default' => '' ) ) );
		$_nonce      = filter_input( INPUT_GET, '_nonce', FILTER_DEFAULT, array( 'options' => array( 'default' => wp_create_nonce( 'acting_zeus_card' ) ) ) );

		$request_body = file_get_contents( 'php://input' );
		$body         = json_decode( $request_body, true );
		if ( ! empty( $body['MD'] ) && ! empty( $body['PaRes'] ) ) {
			$data          = array();
			$data['xid']   = $body['MD'];
			$data['PaRes'] = $body['PaRes'];
			$auth_req      = '<?xml version="1.0" encoding="utf-8" ?>';
			$auth_req     .= '<request service="secure_link_3d" action="authentication">';
			$auth_req     .= $this->assoc2xml( $data );
			$auth_req     .= '</request>';

			$xml = $this->get_xml( $acting_opts['card_secureurl'], $auth_req );
			if ( false !== strpos( $xml, 'Invalid' ) ) {
				$log = array(
					'acting' => 'zeus_card_API(3D Auth)',
					'key'    => $sendpoint,
					'result' => 'AuthReq Error',
					'data'   => $auth_req,
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => '0',
							'status'        => 'AuthReq',
							'code'          => '0',
						),
						USCES_CART_URL
					)
				);
				exit();
			}

			$auth_res = $this->xml2assoc( $xml );
			if ( 'success' !== $auth_res['response']['result']['status'] ) {
				$log = array(
					'acting' => 'zeus_card_API(3D Auth)',
					'key'    => $sendpoint,
					'result' => $auth_res['response']['result']['status'] . ':' . $auth_res['response']['result']['code'],
					'data'   => $auth_res,
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => '0',
							'status'        => $auth_res['response']['result']['status'],
							'code'          => $auth_res['response']['result']['code'],
						),
						USCES_CART_URL
					)
				);
				exit();
			}

			$data        = array();
			$data['xid'] = $body['MD'];
			$pay_req     = '<?xml version="1.0" encoding="utf-8" ?>';
			$pay_req    .= '<request service="secure_link_3d" action="payment">';
			$pay_req    .= $this->assoc2xml( $data );
			$pay_req    .= '</request>';

			$xml = $this->get_xml( $acting_opts['card_secureurl'], $pay_req );
			if ( empty( $xml ) ) {
				$log = array(
					'acting' => 'zeus_card_API(3D Auth)',
					'key'    => $sendpoint,
					'result' => 'PayReq Error',
					'data'   => $pay_req,
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => '0',
							'status'        => 'PayRes',
							'code'          => '0',
						),
						USCES_CART_URL
					)
				);
				exit();
			}

			$pay_res = $this->xml2assoc( $xml );
			if ( 'success' === $pay_res['response']['result']['status'] ) {
				$result_data = array(
					'acting'        => 'zeus_card',
					'acting_return' => '1',
					'zeussuffix'    => $pay_res['response']['card']['number']['suffix'],
					'zeusordd'      => $pay_res['response']['order_number'],
					'wctid'         => $sendpoint,
					'auth_code'     => $auth_res['response']['result']['code'],
				);
				$res         = $usces->order_processing( $result_data );
				if ( 'ordercompletion' === $res ) {
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => 'zeus_card',
								'acting_return' => 1,
								'result'        => 1,
								'_nonce'        => $_nonce,
								'uscesid'       => $usces->get_uscesid( false ),
							),
							USCES_CART_URL
						)
					);
					exit();
				} else {
					$log = array(
						'acting' => 'zeus_card',
						'key'    => $sendpoint,
						'result' => 'ORDER DATA REGISTERED ERROR',
						'data'   => $result_data,
					);
					usces_save_order_acting_error( $log );
					wp_redirect(
						add_query_arg(
							array(
								'acting'        => 'zeus_card',
								'acting_return' => 0,
								'result'        => 0,
							),
							USCES_CART_URL
						)
					);
					exit();
				}
			} else {
				$log = array(
					'acting' => 'zeus_card_API(3D Auth)',
					'key'    => $sendpoint,
					'result' => $pay_res['response']['result']['status'] . ':' . $pay_res['response']['result']['code'],
					'data'   => $pay_res,
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => '0',
							'status'        => $pay_res['response']['result']['status'],
							'code'          => $pay_res['response']['result']['code'],
						),
						USCES_CART_URL
					)
				);
				exit();
			}
		}
		exit();
	}

	/**
	 * Secure API
	 * Payment Request. (3D Secure Not used.)
	 * usces_zeus_secure_payreq
	 * ( call from acting_processing )
	 */
	protected function zeus_secure_payreq() {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$sendid      = ( 'on' == $acting_opts['quickcharge'] && isset( $_POST['sendid'] ) ) ? filter_input( INPUT_POST, 'sendid' ) : '';
		$sendpoint   = ( isset( $_POST['sendpoint'] ) ) ? filter_input( INPUT_POST, 'sendpoint' ) : '';
		$outside     = ( isset( $_POST['outside'] ) ) ? filter_input( INPUT_POST, 'outside' ) : '';

		$data                               = array();
		$data['authentication']['clientip'] = $acting_opts['clientip'];
		$data['authentication']['key']      = $acting_opts['authkey'];
		if ( 'on' === $acting_opts['quickcharge'] && isset( $_POST['card_option'] ) && 'prev' === filter_input( INPUT_POST, 'card_option' ) && ! empty( $sendid ) ) {
			$data['card']['history']['key']    = 'sendid';
			$data['card']['history']['action'] = 'send_email';
		} elseif ( isset( $_POST['token_key'] ) ) {
			$data['token_key'] = filter_input( INPUT_POST, 'token_key' );
		}
		$data['payment']['amount'] = filter_input( INPUT_POST, 'money' );
		if ( isset( $_POST['howpay'] ) && WCUtils::is_zero( filter_input( INPUT_POST, 'howpay' ) ) ) {
			$data['payment']['count'] = filter_input( INPUT_POST, 'div' );
		} else {
			$data['payment']['count'] = '01';
		}
		$data['user']['telno']         = str_replace( '-', '', filter_input( INPUT_POST, 'telno' ) );
		$data['user']['email']         = filter_input( INPUT_POST, 'email' );
		$data['uniq_key']['sendid']    = $sendid;
		$data['uniq_key']['sendpoint'] = $sendpoint;

		$pay_req  = '<?xml version="1.0" encoding="utf-8" ?>';
		$pay_req .= '<request service="secure_link" action="payment">';
		$pay_req .= $this->assoc2xml( $data );
		$pay_req .= '</request>';

		$xml = $this->get_xml( $acting_opts['card_secureurl'], $pay_req );
		if ( empty( $xml ) ) {
			$log = array(
				'acting' => 'zeus_card_API',
				'key'    => $sendpoint,
				'result' => 'PayReq Error',
				'data'   => $pay_req,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'zeus_card',
						'acting_return' => '0',
						'status'        => 'PayReq',
						'code'          => '0',
					),
					USCES_CART_URL
				)
			);
			exit();
		}

		usces_ordered_acting_data( $sendpoint, 'propriety' );

		$pay_res = $this->xml2assoc( $xml );
		if ( 'success' === $pay_res['response']['result']['status'] ) {
			$result_data = array(
				'acting'     => 'zeus_card',
				'zeussuffix' => $pay_res['response']['card']['number']['suffix'],
				'zeusordd'   => $pay_res['response']['order_number'],
				'wctid'      => $sendpoint,
			);
			if ( ! empty( $outside ) ) {
				$result_data['auth_code'] = $outside;
			}
			$res = $usces->order_processing( $result_data );
			if ( 'ordercompletion' === $res ) {
				$_nonce = ( isset( $_POST['_nonce'] ) ) ? filter_input( INPUT_POST, '_nonce' ) : wp_create_nonce( 'acting_zeus_card' );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => 1,
							'result'        => 1,
							'_nonce'        => $_nonce,
						),
						USCES_CART_URL
					)
				);
			} else {
				$log = array(
					'acting' => 'zeus_card',
					'key'    => $sendpoint,
					'result' => 'ORDER DATA REGISTERED ERROR',
					'data'   => $result_data,
				);
				usces_save_order_acting_error( $log );
				wp_redirect(
					add_query_arg(
						array(
							'acting'        => 'zeus_card',
							'acting_return' => 0,
							'result'        => 0,
						),
						USCES_CART_URL
					)
				);
			}
			exit();
		} else {
			$log = array(
				'acting' => 'zeus_card_API',
				'key'    => $sendpoint,
				'result' => $pay_res['response']['result']['status'] . ':' . $pay_res['response']['result']['code'],
				'data'   => $pay_res,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'zeus_card',
						'acting_return' => '0',
						'status'        => $pay_res['response']['result']['status'],
						'code'          => $pay_res['response']['result']['code'],
					),
					USCES_CART_URL
				)
			);
			exit();
		}
	}

	/**
	 * XML to Association.
	 * usces_xml2assoc
	 *
	 * @param  string $xml XML data.
	 * @return array
	 */
	public function xml2assoc( $xml ) {
		$arr = array();
		if ( ! preg_match_all( '|\<\s*?(\w+).*?\>(.*)\<\/\s*\\1.*?\>|s', $xml, $m ) ) {
			return $xml;
		}
		if ( is_array( $m[1] ) ) {
			for ( $i = 0; $i < sizeof( $m[1] ); $i++ ) {
				$arr[ $m[1][ $i ] ] = $this->xml2assoc( $m[2][ $i ] );
			}
		} else {
			$arr[ $m[1] ] = $this->xml2assoc( $m[2] );
		}
		return $arr;
	}

	/**
	 * Association to XML.
	 * usces_assoc2xml
	 *
	 * @param  array $prm_array Parameters.
	 * @return string
	 */
	protected function assoc2xml( $prm_array ) {
		$xml = '';
		if ( is_array( $prm_array ) ) {
			$i = 0;
			foreach ( $prm_array as $index => $element ) {
				if ( is_array( $element ) ) {
					$acts = explode( '_', $index, 3 );
					if ( is_array( $acts ) && 2 < count( $acts ) && 'history' === $acts[0] && 'action' === $acts[1] ) {
						$xml .= '<history action="' . $acts[2] . '">';
						$xml .= $this->assoc2xml( $element );
						$xml .= '</history>';
					} else {
						$xml .= '<' . $index . '>';
						$xml .= $this->assoc2xml( $element );
						$xml .= '</' . $index . '>';
					}
				} else {
					$xml .= '<' . $index . '>' . $element . '</' . $index . '>';
				}
				$i++;
				if ( $i > 500 ) {
					break;
				}
			}
		}
		return $xml;
	}

	/**
	 * Get XML Response.
	 * usces_get_xml
	 *
	 * @param  string $url Connect url.
	 * @param  string $params Sending parameters.
	 * @return string
	 */
	protected function get_xml( $url, $params ) {
		$interface = $this->get_interface( $url );

		$header  = 'POST ' . $interface['path'] . " HTTP/1.1\r\n";
		$header .= 'Host: ' . $interface['host'] . "\r\n";
		$header .= "User-Agent: PHP Script\r\n";
		$header .= "Content-Type: text/xml\r\n";
		$header .= 'Content-Length: ' . strlen( $params ) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$header .= $params;

		$fp = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
		if ( ! $fp ) {
			usces_log( 'zeus API : TLS(v1.2) Error', 'acting_transaction.log' );
		}

		$xml = '';
		if ( $fp ) {
			fwrite( $fp, $header );
			while ( ! feof( $fp ) ) {
				$xml .= fgets( $fp, 1024 );
			}
			fclose( $fp );
		}
		return $xml;
	}

	/**
	 * Secure Link Batch.
	 *
	 * @param  string $url Connect url.
	 * @param  array  $params Sending parameters.
	 * @return string
	 */
	protected function secure_link_batch( $url, $params ) {
		$page      = '';
		$interface = $this->get_interface( $url );
		$vars      = http_build_query( $params );

		$header  = 'POST ' . $interface['path'] . " HTTP/1.1\r\n";
		$header .= 'Host: ' . $interface['host'] . "\r\n";
		$header .= "User-Agent: PHP Script\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= 'Content-Length: ' . strlen( $vars ) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$header .= $vars;

		$fp = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
		if ( $fp ) {
			fwrite( $fp, $header );
			while ( ! feof( $fp ) ) {
				$scr   = fgets( $fp, 1024 );
				$page .= $scr;
			}
			fclose( $fp );
		}

		return $page;
	}

	/**
	 * Get clientip.
	 *
	 * @param  string $acting Acting type.
	 * @return string
	 */
	protected function get_clientip( $acting ) {
		$clientip    = '';
		$acting_opts = $this->get_acting_settings();
		switch ( $acting ) {
			case 'zeus_card':
				$clientip = $acting_opts['clientip'];
				break;
			case 'zeus_conv':
				$clientip = $acting_opts['clientip_conv'];
				break;
			case 'zeus_bank':
				$clientip = $acting_opts['clientip_bank'];
				break;
		}
		return $clientip;
	}

	/**
	 * Get order_number.
	 *
	 * @param  string $page Post data.
	 * @return string
	 */
	protected function get_order_number( $page ) {
		if ( empty( $page ) ) {
			return '';
		}

		$log  = explode( "\r\n", $page );
		$ordd = '';
		foreach ( (array) $log as $line ) {
			if ( false !== strpos( $line, 'ordd' ) ) {
				list( $status, $ordd ) = explode( '=', $line );
			}
		}
		if ( empty( $ordd ) ) {
			foreach ( (array) $log as $idx => $line ) {
				if ( false !== strpos( $line, 'Success_order' ) ) {
					list( $status, $ordd ) = explode( "\n", $line );
					break;
				}
			}
		}
		return $ordd;
	}

	/**
	 * Get err_code.
	 *
	 * @param  string $page Post data.
	 * @return string
	 */
	protected function get_err_code( $page ) {
		if ( empty( $page ) ) {
			return '';
		}

		$log      = explode( "\r\n", $page );
		$err_code = '';
		foreach ( (array) $log as $line ) {
			if ( false !== strpos( $line, 'err_code' ) ) {
				list( $name, $err_code ) = explode( '=', $line );
			}
		}
		return $err_code;
	}

	/**
	 * Get err_code.
	 *
	 * @param  string $page Post data.
	 * @return string
	 */
	protected function get_error_results( $page ) {
		$error_results = '';
		if ( ! empty( $page ) ) {
			$log = explode( "\r\n", $page );
			foreach ( (array) $log as $line ) {
				foreach ( $this->payment_error_results as $error ) {
					if ( false !== strpos( $line, $error ) ) {
						$error_results = $line;
						break;
					}
				}
				if ( ! empty( $error_results ) ) {
					break;
				}
			}
		}
		return $error_results;
	}

	/**
	 * Get order_id by meta_data ( conv, bank )
	 *
	 * @param  int $key Order number.
	 * @return string
	 */
	protected function get_order_id( $key ) {
		global $wpdb;

		$query    = $wpdb->prepare( "SELECT `order_id` FROM {$wpdb->prefix}usces_order_meta WHERE `meta_key` = %s", 'acting_' . $key );
		$order_id = $wpdb->get_var( $query );
		return $order_id;
	}

	/**
	 * Get order_meta_data ( conv, bank )
	 *
	 * @param  int $order_id Order number.
	 * @return array
	 */
	protected function get_order_meta_acting( $order_id ) {
		global $wpdb;

		$query       = $wpdb->prepare( "SELECT `meta_value` FROM {$wpdb->prefix}usces_order_meta WHERE `order_id` = %d AND `meta_key` LIKE %s", $order_id, 'acting_%' );
		$meta_value  = $wpdb->get_var( $query );
		$acting_data = usces_unserialize( $meta_value );
		return $acting_data;
	}

	/**
	 * Tracking ID 取得
	 *
	 * @param  int $order_id Order number.
	 * @return string
	 */
	protected function get_tracking_id( $order_id ) {
		global $usces;

		$tracking_id = $usces->get_order_meta_value( 'tracking_id', $order_id );
		return $tracking_id;
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
	 * @param  string $tracking_id Tracking ID.
	 * @param  string $result Result.
	 * @return array
	 */
	public function get_acting_log( $order_id = 0, $tracking_id = 0, $result = 'OK' ) {
		global $wpdb;

		if ( empty( $order_id ) ) {
			if ( 'OK' === $result ) {
				$query = $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `tracking_id` = %s AND `result` IN( %s ) ORDER BY `ID` DESC, `datetime` DESC",
					$tracking_id,
					implode( "','", $this->payment_normal_results )
				);
				$query = stripslashes( $query );
			} else {
				$query = $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `tracking_id` = %s ORDER BY `ID` DESC, `datetime` DESC",
					$tracking_id
				);
			}
		} else {
			if ( empty( $tracking_id ) ) {
				if ( 'OK' === $result ) {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `datetime` IN( SELECT MAX( `datetime` ) FROM {$wpdb->prefix}usces_acting_log GROUP BY `tracking_id` ) AND `order_id` = %d AND `result` IN( %s ) ORDER BY `ID` DESC, `datetime` DESC",
						$order_id,
						implode( "','", $this->payment_normal_results )
					);
					$query = stripslashes( $query );
				} else {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `datetime` IN( SELECT MAX( `datetime` ) FROM {$wpdb->prefix}usces_acting_log GROUP BY `tracking_id` ) AND `order_id` = %d ORDER BY `ID` DESC, `datetime` DESC",
						$order_id
					);
				}
			} else {
				if ( 'OK' === $result ) {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `order_id` = %d AND `tracking_id` = %s AND `result` IN( %s ) ORDER BY `ID` DESC, `datetime` DESC",
						$order_id,
						$tracking_id,
						implode( "','", $this->payment_normal_results )
					);
					$query = stripslashes( $query );
				} else {
					$query = $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}usces_acting_log WHERE `order_id` = %d AND `tracking_id` = %s ORDER BY `ID` DESC, `datetime` DESC",
						$order_id,
						$tracking_id
					);
				}
			}
		}
		$log_data = $wpdb->get_results( $query, ARRAY_A );
		return $log_data;
	}

	/**
	 * 最新処理取得
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $tracking_id Tracking ID.
	 * @param  string $result Result.
	 * @return array
	 */
	public function get_acting_latest_log( $order_id, $tracking_id, $result = 'OK' ) {
		$latest_log = array();
		$log_data   = $this->get_acting_log( $order_id, $tracking_id, $result );
		if ( $log_data ) {
			$data                      = current( $log_data );
			$log                       = usces_unserialize( $data['log'] );
			$order_no                  = $this->get_order_no( $log );
			$latest_log['acting']      = $data['acting'];
			$latest_log['status']      = $data['status'];
			$latest_log['result']      = $data['result'];
			$latest_log['log']         = $log;
			$latest_log['amount']      = (int) $data['amount'];
			$latest_log['order_id']    = $data['order_id'];
			$latest_log['order_no']    = $order_no;
			$latest_log['tracking_id'] = $data['tracking_id'];
		}
		return $latest_log;
	}

	/**
	 * 決済処理取得
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $tracking_id Tracking ID.
	 * @param  string $result Result.
	 * @return string
	 */
	private function get_acting_status( $order_id, $tracking_id, $result = 'OK' ) {
		$acting_status = '';
		$log_data      = $this->get_acting_log( $order_id, $tracking_id, $result );
		if ( $log_data ) {
			foreach ( (array) $log_data as $data ) {
				if ( 'change' === $data['status'] ) {
					continue;
				}
				if ( 'TEST' === $data['status'] || 'test' === $data['status'] ) {
					$acting_status = 'test';
				} elseif ( 'cancel' === $data['status'] || 'sale' === $data['status'] || 'auth' === $data['status'] ) {
					$acting_status = $data['status'];
				} elseif ( 'autodelivery' === $data['status'] || 'dlseller' === $data['status'] ) {
					$acting_status = 'error';
				} elseif ( '仮売完了' === $data['result'] ) {
					$acting_status = 'auth';
				} elseif ( '決済完了' === $data['result'] || 'payment' === $data['status'] ) {
					$acting_status = 'payment';
				}
				if ( '' !== $acting_status ) {
					break;
				}
			}
		}
		return $acting_status;
	}

	/**
	 * 処理区分名称取得
	 *
	 * @param  string $payment_status Payment status code.
	 * @param  string $payment_result Payment result.
	 * @return string
	 */
	private function get_status_name( $payment_status, $payment_result = '' ) {
		$status_name = '';
		switch ( $payment_status ) {
			case 'TEST':
			case 'test':
				$status_name = 'テスト決済';
				break;
			case 'payment':
				$status_name = '決済完了';
				break;
			case 'auth':
				$status_name = '仮売完了';
				break;
			case 'sale':
				$status_name = '決済完了';
				break;
			case 'cancel':
				$status_name = '取り消し';
				break;
			case 'change':
				$status_name = '金額変更';
				break;
			case 'error':
				$status_name = 'エラー';
				break;
			case 'dlseller':
				$status_name = '継続課金決済エラー';
				break;
			case 'autodelivery':
				$status_name = '定期購入決済エラー';
				break;
			default:
				if ( in_array( $payment_result, $this->payment_normal_results, true ) ) {
					if ( 'OK' === $payment_result || 'Success_order' === $payment_result || 'SuccessOK' === $payment_result ) {
						$status_name = '決済完了';
					} else {
						$status_name = $payment_result;
					}
				} else {
					$status_name = ( ! empty( $payment_result ) ) ? $payment_result : '決済完了';
				}
				$status_name = $payment_status;
		}
		return $status_name;
	}

	/**
	 * オーダーNo 取得
	 *
	 * @param  array $log Log data.
	 * @return string
	 */
	private function get_order_no( $log ) {
		$order_no = ( isset( $log['ordd'] ) ) ? $log['ordd'] : '';
		if ( empty( $order_no ) && isset( $log['zeusordd'] ) ) {
			$order_no = $log['zeusordd'];
		}
		if ( empty( $order_no ) && isset( $log['ordd'] ) ) {
			$order_no = $log['ordd'];
		}
		if ( empty( $order_no ) && isset( $log['order_no'] ) ) {
			$order_no = $log['order_no'];
		}
		if ( empty( $order_no ) && isset( $log['ORDER_NO'] ) ) {
			$order_no = $log['ORDER_NO'];
		}
		if ( empty( $order_no ) && isset( $log['result_order_number'] ) ) {
			$order_no = $log['result_order_number'];
		}
		if ( empty( $order_no ) && isset( $log['response']['data']['ORDER_NO'] ) ) {
			$order_no = $log['response']['data']['ORDER_NO'];
		}
		if ( empty( $order_no ) && isset( $log['response']['result']['result_order_number'] ) ) {
			$order_no = $log['response']['result']['result_order_number'];
		}
		return $order_no;
	}

	/**
	 * Email 取得
	 *
	 * @param  int $order_id Order number.
	 * @return string
	 */
	private function get_email( $order_id ) {
		global $wpdb;

		$query = $wpdb->prepare( "SELECT `order_email` FROM {$wpdb->prefix}usces_order WHERE `ID` = %d", $order_id );
		$email = $wpdb->get_var( $query );
		return $email;
	}

	/**
	 * Get interface.
	 *
	 * @param  string $url URL.
	 * @return array
	 */
	public function get_interface( $url ) {
		$interface = parse_url( $url );
		if ( defined( 'ZEUS_SSL_TEST' ) ) {
			$interface['host'] = ZEUS_SSL_TEST . $interface['host'];
		}
		if ( defined( 'ZEUS_TLS_TEST' ) ) {
			$interface['host'] = ZEUS_TLS_TEST;
		}
		return $interface;
	}
}

/**
 * Get order_ref
 *
 * @param  string $order_no Order No.
 * @return array
 */
function wel_zeus_get_order_ref( $order_no ) {
	$order_ref       = array();
	$zeus_settlement = ZEUS_SETTLEMENT::get_instance();
	$acting_opts     = $zeus_settlement->get_acting_settings();
	$interface       = $zeus_settlement->get_interface( $acting_opts['card_order_ref'] );

	$params             = array();
	$params['clientip'] = $acting_opts['clientip'];
	$params['zkey']     = $acting_opts['authkey'];
	$params['order_no'] = $order_no;
	$vars               = http_build_query( $params );

	$header  = 'POST ' . $interface['path'] . " HTTP/1.1\r\n";
	$header .= 'Host: ' . $interface['host'] . "\r\n";
	$header .= "User-Agent: PHP Script\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= 'Content-Length: ' . strlen( $vars ) . "\r\n";
	$header .= "Connection: close\r\n\r\n";
	$header .= $vars;

	$fp = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
	if ( $fp ) {
		$xml = '';
		fwrite( $fp, $header );
		while ( ! feof( $fp ) ) {
			$scr = fgets( $fp, 1024 );
			if ( false !== strpos( $scr, '<' ) ) {
				$xml .= mb_convert_encoding( $scr, 'UTF-8', 'sjis-win' );
			}
		}
		fclose( $fp );
		$assoc = $zeus_settlement->xml2assoc( $xml );
		if ( isset( $assoc['message']['response']['data'] ) ) {
			$order_ref = $assoc['message']['response']['data'];
			if ( isset( $order_ref['RESULT'] ) ) {
				if ( '決済完了' === $order_ref['RESULT'] ) {
					$order_ref['status'] = 'payment';
				} elseif ( '与信完了' === $order_ref['RESULT'] || '仮売完了' === $order_ref['RESULT'] ) {
					$order_ref['status'] = 'auth';
				} elseif ( '取り消し' === $order_ref['RESULT'] ) {
					$order_ref['status'] = 'cancel';
				} else {
					$order_ref['status'] = $order_ref['RESULT'];
				}
			} else {
				$order_ref['status'] = '';
			}
		} else {
			$order_ref['status'] = '';
		}
	}
	if ( empty( $order_ref['status'] ) && false !== strpos( $order_no, 'TEST' ) ) {
		$order_ref['status'] = 'TEST';
	}

	return $order_ref;
}

/**
 * 決済ログ出力
 *
 * @param  string $log Log data.
 * @param  string $acting Acting type.
 * @param  string $status Status.
 * @param  string $result Result.
 * @param  int    $order_id Order number.
 * @param  string $tracking_id Tracking ID.
 * @return array
 */
function wel_zeus_save_acting_log( $log, $acting, $status, $result, $order_id, $tracking_id ) {
	global $wpdb;

	if ( isset( $log['money'] ) ) {
		$amount = $log['money'];
	} elseif ( isset( $log['amount'] ) ) {
		$amount = $log['amount'];
	} elseif ( isset( $log['king'] ) ) {
		$amount = $log['king'];
	} elseif ( isset( $log['KING'] ) ) {
		$amount = $log['KING'];
	} else {
		$amount = 0;
	}
	$query = $wpdb->prepare(
		"INSERT INTO {$wpdb->prefix}usces_acting_log ( `datetime`, `log`, `acting`, `status`, `result`, `amount`, `order_id`, `tracking_id` ) VALUES ( %s, %s, %s, %s, %s, %f, %d, %s )",
		wp_date( 'Y-m-d H:i:s' ),
		usces_serialize( $log ),
		$acting,
		$status,
		$result,
		$amount,
		$order_id,
		$tracking_id
	);
	$res   = $wpdb->query( $query );
	return $res;
}
