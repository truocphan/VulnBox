<?php
/**
 * Settlement Class.
 * WelcartPay based on e-SCOTT
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.2.0
 * @since    1.4.14
 */
class WELCARTPAY_SETTLEMENT extends ESCOTT_MAIN {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * 継続課金結果通知メール
	 *
	 * @var string
	 */
	protected $continuation_charging_mail;

	/**
	 * 処理ステータス
	 *
	 * @var array
	 */
	protected $latest_status = array(
		'1Auth',
		'1Capture',
		'1Gathering',
		'1Delete',
		'2Add',
		'2Chg',
		'2Del',
		'5Auth',
		'5Gathering',
		'5Capture',
		'5Delete',
		'receipted',
		'11Gathering',
		'11Delete',
	);

	/**
	 * 処理ステータス（取消以外）
	 *
	 * @var array
	 */
	protected $primarily_status = array(
		'1Auth',
		'1Gathering',
		'2Add',
		'5Auth',
		'5Gathering',
		'5Capture',
		'receipted',
	);

	/**
	 * 処理ステータス（再オーソリ）
	 *
	 * @var array
	 */
	protected $reauth_status = array(
		'1ReAuth',
	);

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->acting_name        = 'WelcartPay';
		$this->acting_formal_name = 'WelcartPay based on e-SCOTT';

		$this->acting_card     = 'welcart_card';
		$this->acting_conv     = 'welcart_conv';
		$this->acting_atodene  = 'welcart_atodene';
		$this->acting_applepay = 'welcart_applepay';
		$this->acting_unionpay = 'welcart_unionpay';

		$this->acting_flg_card     = 'acting_welcart_card';
		$this->acting_flg_conv     = 'acting_welcart_conv';
		$this->acting_flg_atodene  = 'acting_welcart_atodene';
		$this->acting_flg_applepay = 'acting_welcart_applepay';
		$this->acting_flg_unionpay = 'acting_welcart_unionpay';

		$this->pay_method    = array(
			'acting_welcart_card',
			'acting_welcart_conv',
			'acting_welcart_applepay',
			'acting_welcart_unionpay',
		);
		$this->merchantfree3 = 'wc2collne';
		$this->quick_key_pre = 'wcpay';

		parent::__construct( 'welcart' );

		if ( $this->is_activate_card() || $this->is_activate_conv() || $this->is_activate_applepay() || $this->is_activate_unionpay() ) {
			if ( is_admin() ) {
				add_action( 'usces_action_admin_ajax', array( $this, 'admin_ajax' ) );
				add_filter( 'usces_filter_orderlist_detail_value', array( $this, 'orderlist_settlement_status' ), 10, 4 );
				add_action( 'usces_action_order_edit_form_status_block_middle', array( $this, 'settlement_status' ), 10, 3 );
				add_action( 'usces_action_order_edit_form_settle_info', array( $this, 'settlement_information' ), 10, 2 );
				add_action( 'usces_action_endof_order_edit_form', array( $this, 'settlement_dialog' ), 10, 2 );
			}
		}

		if ( $this->is_validity_acting( 'card' ) ) {
			add_filter( 'usces_fiter_the_payment_method_explanation', array( $this, 'set_payment_method_explanation' ), 10, 3 );
			add_filter( 'usces_filter_available_payment_method', array( $this, 'set_available_payment_method' ) );
			add_filter( 'usces_filter_delivery_secure_form_howpay', array( $this, 'delivery_secure_form_howpay' ) );
			add_filter( 'usces_filter_template_redirect', array( $this, 'member_update_settlement' ), 1 );
			add_action( 'usces_action_member_submenu_list', array( $this, 'e_update_settlement' ) );
			add_filter( 'usces_filter_member_submenu_list', array( $this, 'update_settlement' ), 10, 2 );
			add_filter( 'usces_filter_save_order_acting_data', array( $this, 'save_order_acting_data' ) );

			/* WCEX DL Seller */
			if ( defined( 'WCEX_DLSELLER' ) ) {
				if ( defined( 'WCEX_DLSELLER_VERSION' ) && version_compare( WCEX_DLSELLER_VERSION, '2.2-beta', '<=' ) ) {
					add_filter( 'usces_filter_the_continue_payment_method', array( $this, 'continuation_payment_method' ) );
				}
				add_filter( 'dlseller_filter_first_charging', array( $this, 'first_charging_date' ), 9, 5 );
				add_filter( 'dlseller_filter_the_payment_method_restriction', array( $this, 'payment_method_restriction' ), 10, 2 );
				add_filter( 'dlseller_filter_continue_member_list_limitofcard', array( $this, 'continue_member_list_limitofcard' ), 10, 4 );
				add_filter( 'dlseller_filter_continue_member_list_condition', array( $this, 'continue_member_list_condition' ), 10, 4 );
				add_action( 'dlseller_action_continue_member_list_page', array( $this, 'continue_member_list_page' ) );
				add_filter( 'dlseller_filter_card_update_mail', array( $this, 'continue_member_card_update_mail' ), 10, 3 );
				add_action( 'dlseller_action_do_continuation_charging', array( $this, 'auto_continuation_charging' ), 10, 4 );
				add_action( 'dlseller_action_do_continuation', array( $this, 'do_auto_continuation' ), 10, 2 );
				add_filter( 'dlseller_filter_reminder_mail_body', array( $this, 'reminder_mail_body' ), 10, 3 );
				add_filter( 'dlseller_filter_contract_renewal_mail_body', array( $this, 'contract_renewal_mail_body' ), 10, 3 );
			}

			/* WCEX Auto Delivery */
			if ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
				add_filter( 'wcad_filter_shippinglist_acting', array( $this, 'set_shippinglist_acting' ) );
				add_filter( 'wcad_filter_available_regular_payment_method', array( $this, 'available_regular_payment_method' ) );
				add_filter( 'wcad_filter_the_payment_method_restriction', array( $this, 'payment_method_restriction' ), 10, 2 );
				add_action( 'wcad_action_reg_auto_orderdata', array( $this, 'register_auto_orderdata' ) );
			}
		}

		if ( $this->is_activate_atodene() ) {
			if ( is_admin() ) {
				add_action( 'usces_after_cart_instant', array( $this, 'atodene_upload' ), 9 );
				add_action( 'usces_action_order_list_page', array( $this, 'output_atodene_csv' ) );
				add_action( 'usces_action_order_list_searchbox_bottom', array( $this, 'action_atodene_button' ) );
				add_action( 'usces_action_order_list_footer', array( $this, 'order_list_footer' ) );
				add_filter( 'usces_filter_order_list_page_js', array( $this, 'order_list_page_js' ) );
				add_filter( 'usces_order_list_action_status', array( $this, 'order_list_action_status' ) );
				add_filter( 'usces_order_list_action_message', array( $this, 'order_list_action_message' ) );
				add_filter( 'usces_filter_deli_comps', array( $this, 'delivery_company_name' ) );

				$acting_opts = $this->get_acting_settings();
				if ( isset( $acting_opts['atodene_byitem'] ) && 'on' === $acting_opts['atodene_byitem'] ) {
					add_filter( 'usces_item_master_second_section', array( $this, 'edit_item_atodene_byitem' ), 10, 2 );
					add_action( 'usces_action_save_product', array( $this, 'save_item_atodene_byitem' ), 10, 2 );
				}
			}
		}

		if ( $this->is_validity_acting( 'atodene' ) ) {
			add_filter( 'usces_filter_nonacting_settlements', array( $this, 'nonacting_settlements' ) );

			/* WCEX Auto Delivery */
			if ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
				add_filter( 'wcad_filter_the_payment_method_restriction', array( $this, 'payment_method_restriction_atodene' ), 11, 2 );
			}
		}

		if ( $this->is_validity_acting( 'unionpay' ) ) {
			add_filter( 'usces_filter_save_order_acting_data', array( $this, 'save_order_acting_data' ) );
		}

		$this->initialize_data();
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
		$options = get_option( 'usces', array() );

		$options['acting_settings']['welcart']['merchant_id']                   = ( isset( $options['acting_settings']['welcart']['merchant_id'] ) ) ? $options['acting_settings']['welcart']['merchant_id'] : '';
		$options['acting_settings']['welcart']['merchant_pass']                 = ( isset( $options['acting_settings']['welcart']['merchant_pass'] ) ) ? $options['acting_settings']['welcart']['merchant_pass'] : '';
		$options['acting_settings']['welcart']['tenant_id']                     = ( isset( $options['acting_settings']['welcart']['tenant_id'] ) ) ? $options['acting_settings']['welcart']['tenant_id'] : '0001';
		$options['acting_settings']['welcart']['auth_key']                      = ( isset( $options['acting_settings']['welcart']['auth_key'] ) ) ? $options['acting_settings']['welcart']['auth_key'] : '';
		$options['acting_settings']['welcart']['ope']                           = ( isset( $options['acting_settings']['welcart']['ope'] ) ) ? $options['acting_settings']['welcart']['ope'] : 'test';
		$options['acting_settings']['welcart']['card_activate']                 = ( isset( $options['acting_settings']['welcart']['card_activate'] ) ) ? $options['acting_settings']['welcart']['card_activate'] : 'off';
		$options['acting_settings']['welcart']['card_key_aes']                  = ( isset( $options['acting_settings']['welcart']['card_key_aes'] ) ) ? $options['acting_settings']['welcart']['card_key_aes'] : '';
		$options['acting_settings']['welcart']['card_key_iv']                   = ( isset( $options['acting_settings']['welcart']['card_key_iv'] ) ) ? $options['acting_settings']['welcart']['card_key_iv'] : '';
		$options['acting_settings']['welcart']['foreign_activate']              = ( isset( $options['acting_settings']['welcart']['foreign_activate'] ) ) ? $options['acting_settings']['welcart']['foreign_activate'] : 'off';
		$options['acting_settings']['welcart']['seccd']                         = ( isset( $options['acting_settings']['welcart']['seccd'] ) ) ? $options['acting_settings']['welcart']['seccd'] : 'on';
		$options['acting_settings']['welcart']['sec3d_activate']                = ( isset( $options['acting_settings']['welcart']['sec3d_activate'] ) ) ? $options['acting_settings']['welcart']['sec3d_activate'] : 'off';
		$options['acting_settings']['welcart']['token_code']                    = ( isset( $options['acting_settings']['welcart']['token_code'] ) ) ? $options['acting_settings']['welcart']['token_code'] : '';
		$options['acting_settings']['welcart']['quickpay']                      = ( isset( $options['acting_settings']['welcart']['quickpay'] ) ) ? $options['acting_settings']['welcart']['quickpay'] : 'off';
		$options['acting_settings']['welcart']['chooseable_quickpay']           = ( isset( $options['acting_settings']['welcart']['chooseable_quickpay'] ) ) ? $options['acting_settings']['welcart']['chooseable_quickpay'] : 'on';
		$options['acting_settings']['welcart']['operateid']                     = ( isset( $options['acting_settings']['welcart']['operateid'] ) ) ? $options['acting_settings']['welcart']['operateid'] : '1Gathering';
		$options['acting_settings']['welcart']['operateid_dlseller']            = ( isset( $options['acting_settings']['welcart']['operateid_dlseller'] ) ) ? $options['acting_settings']['welcart']['operateid_dlseller'] : '1Gathering';
		$options['acting_settings']['welcart']['auto_settlement_mail']          = ( isset( $options['acting_settings']['welcart']['auto_settlement_mail'] ) ) ? $options['acting_settings']['welcart']['auto_settlement_mail'] : 'off';
		$options['acting_settings']['welcart']['howtopay']                      = ( isset( $options['acting_settings']['welcart']['howtopay'] ) ) ? $options['acting_settings']['welcart']['howtopay'] : '1';
		$options['acting_settings']['welcart']['conv_activate']                 = ( isset( $options['acting_settings']['welcart']['conv_activate'] ) ) ? $options['acting_settings']['welcart']['conv_activate'] : 'off';
		$options['acting_settings']['welcart']['conv_limit']                    = ( ! empty( $options['acting_settings']['welcart']['conv_limit'] ) ) ? $options['acting_settings']['welcart']['conv_limit'] : '7';
		$options['acting_settings']['welcart']['conv_fee_type']                 = ( isset( $options['acting_settings']['welcart']['conv_fee_type'] ) ) ? $options['acting_settings']['welcart']['conv_fee_type'] : '';
		$options['acting_settings']['welcart']['conv_fee']                      = ( isset( $options['acting_settings']['welcart']['conv_fee'] ) ) ? $options['acting_settings']['welcart']['conv_fee'] : '';
		$options['acting_settings']['welcart']['conv_fee_limit_amount']         = ( isset( $options['acting_settings']['welcart']['conv_fee_limit_amount'] ) ) ? $options['acting_settings']['welcart']['conv_fee_limit_amount'] : '';
		$options['acting_settings']['welcart']['conv_fee_first_amount']         = ( isset( $options['acting_settings']['welcart']['conv_fee_first_amount'] ) ) ? $options['acting_settings']['welcart']['conv_fee_first_amount'] : '';
		$options['acting_settings']['welcart']['conv_fee_first_fee']            = ( isset( $options['acting_settings']['welcart']['conv_fee_first_fee'] ) ) ? $options['acting_settings']['welcart']['conv_fee_first_fee'] : '';
		$options['acting_settings']['welcart']['conv_fee_amounts']              = ( isset( $options['acting_settings']['welcart']['conv_fee_amounts'] ) ) ? $options['acting_settings']['welcart']['conv_fee_amounts'] : array();
		$options['acting_settings']['welcart']['conv_fee_fees']                 = ( isset( $options['acting_settings']['welcart']['conv_fee_fees'] ) ) ? $options['acting_settings']['welcart']['conv_fee_fees'] : array();
		$options['acting_settings']['welcart']['conv_fee_end_fee']              = ( isset( $options['acting_settings']['welcart']['conv_fee_end_fee'] ) ) ? $options['acting_settings']['welcart']['conv_fee_end_fee'] : '';
		$options['acting_settings']['welcart']['atodene_activate']              = ( isset( $options['acting_settings']['welcart']['atodene_activate'] ) ) ? $options['acting_settings']['welcart']['atodene_activate'] : 'off';
		$options['acting_settings']['welcart']['atodene_byitem']                = ( isset( $options['acting_settings']['welcart']['atodene_byitem'] ) ) ? $options['acting_settings']['welcart']['atodene_byitem'] : 'off';
		$options['acting_settings']['welcart']['atodene_billing_method']        = ( isset( $options['acting_settings']['welcart']['atodene_billing_method'] ) ) ? $options['acting_settings']['welcart']['atodene_billing_method'] : '2';
		$options['acting_settings']['welcart']['atodene_fee_type']              = ( isset( $options['acting_settings']['welcart']['atodene_fee_type'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_type'] : '';
		$options['acting_settings']['welcart']['atodene_fee']                   = ( isset( $options['acting_settings']['welcart']['atodene_fee'] ) ) ? $options['acting_settings']['welcart']['atodene_fee'] : '';
		$options['acting_settings']['welcart']['atodene_fee_limit_amount']      = ( isset( $options['acting_settings']['welcart']['atodene_fee_limit_amount'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_limit_amount'] : '';
		$options['acting_settings']['welcart']['atodene_fee_first_amount']      = ( isset( $options['acting_settings']['welcart']['atodene_fee_first_amount'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_first_amount'] : '';
		$options['acting_settings']['welcart']['atodene_fee_first_fee']         = ( isset( $options['acting_settings']['welcart']['atodene_fee_first_fee'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_first_fee'] : '';
		$options['acting_settings']['welcart']['atodene_fee_amounts']           = ( isset( $options['acting_settings']['welcart']['atodene_fee_amounts'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_amounts'] : array();
		$options['acting_settings']['welcart']['atodene_fee_fees']              = ( isset( $options['acting_settings']['welcart']['atodene_fee_fees'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_fees'] : array();
		$options['acting_settings']['welcart']['atodene_fee_end_fee']           = ( isset( $options['acting_settings']['welcart']['atodene_fee_end_fee'] ) ) ? $options['acting_settings']['welcart']['atodene_fee_end_fee'] : '';
		$options['acting_settings']['welcart']['applepay_activate']             = ( isset( $options['acting_settings']['welcart']['applepay_activate'] ) ) ? $options['acting_settings']['welcart']['applepay_activate'] : 'off';
		$options['acting_settings']['welcart']['applepay_tenant_id']            = ( isset( $options['acting_settings']['welcart']['applepay_tenant_id'] ) ) ? $options['acting_settings']['welcart']['applepay_tenant_id'] : '';
		$options['acting_settings']['welcart']['applepay_quickpay']             = ( isset( $options['acting_settings']['welcart']['applepay_quickpay'] ) ) ? $options['acting_settings']['welcart']['applepay_quickpay'] : 'off';
		$options['acting_settings']['welcart']['applepay_operateid']            = ( isset( $options['acting_settings']['welcart']['applepay_operateid'] ) ) ? $options['acting_settings']['welcart']['applepay_operateid'] : '1Gathering';
		$options['acting_settings']['welcart']['applepay_key_aes']              = ( isset( $options['acting_settings']['welcart']['applepay_key_aes'] ) ) ? $options['acting_settings']['welcart']['applepay_key_aes'] : '';
		$options['acting_settings']['welcart']['applepay_key_iv']               = ( isset( $options['acting_settings']['welcart']['applepay_key_iv'] ) ) ? $options['acting_settings']['welcart']['applepay_key_iv'] : '';
		$options['acting_settings']['welcart']['applepay_certificate_path']     = ( isset( $options['acting_settings']['welcart']['applepay_certificate_path'] ) ) ? $options['acting_settings']['welcart']['applepay_certificate_path'] : '';
		$options['acting_settings']['welcart']['applepay_certificate_key_pass'] = ( isset( $options['acting_settings']['welcart']['applepay_certificate_key_pass'] ) ) ? $options['acting_settings']['welcart']['applepay_certificate_key_pass'] : '';
		$options['acting_settings']['welcart']['applepay_merchantidentifier']   = ( isset( $options['acting_settings']['welcart']['applepay_merchantidentifier'] ) ) ? $options['acting_settings']['welcart']['applepay_merchantidentifier'] : '';
		$options['acting_settings']['welcart']['unionpay_activate']             = ( isset( $options['acting_settings']['welcart']['unionpay_activate'] ) ) ? $options['acting_settings']['welcart']['unionpay_activate'] : 'off';
		$options['acting_settings']['welcart']['unionpay_key_aes']              = ( isset( $options['acting_settings']['welcart']['unionpay_key_aes'] ) ) ? $options['acting_settings']['welcart']['unionpay_key_aes'] : '';
		$options['acting_settings']['welcart']['unionpay_key_iv']               = ( isset( $options['acting_settings']['welcart']['unionpay_key_iv'] ) ) ? $options['acting_settings']['welcart']['unionpay_key_iv'] : '';
		$options['acting_settings']['welcart']['unionpay_pagelanguage']         = ( isset( $options['acting_settings']['welcart']['unionpay_pagelanguage'] ) ) ? $options['acting_settings']['welcart']['unionpay_pagelanguage'] : '';
		$options['acting_settings']['welcart']['activate']                      = ( isset( $options['acting_settings']['welcart']['activate'] ) ) ? $options['acting_settings']['welcart']['activate'] : 'off';
		update_option( 'usces', $options );

		$welcartpay_keys = get_option( 'usces_welcartpay_keys', array() );
		if ( empty( $welcartpay_keys ) ) {
			$welcartpay_keys = array(
				'c0778c9aefe850d5ac8efed5d62ed281',
				'd0771e4b42ef683223df03f9558c23fd',
				'dfef8e46f7231e7e8271f906582a4e1d',
				'ad6dbb5e26cc9db1fe5d876a75764559',
				'4fc1738fffa5aa33792ddf8e5c183f72',
				'd255b1cb2c4d20959e3c80e457e5274c',
				'479ffcfe47db920e972a8c7932e581d9',
				'43c7f4782379b05cf69bbbfb547e3312',
				'524047b0e0ad64d4f7b42c14c77758e2',
				'b848aed9c05cbf2c85d2889b274c18ec',
			);
			update_option( 'usces_welcartpay_keys', $welcartpay_keys );
		}

		$available_settlement = get_option( 'usces_available_settlement', array() );
		if ( ! in_array( 'welcart', $available_settlement, true ) ) {
			$settlement           = array(
				'welcart' => __( 'WelcartPay', 'usces' ),
			);
			$available_settlement = array_merge( $settlement, $available_settlement );
			update_option( 'usces_available_settlement', $available_settlement );
		}

		$noreceipt_status = get_option( 'usces_noreceipt_status', array() );
		if ( ! in_array( 'acting_welcart_conv', $noreceipt_status, true ) || ! in_array( 'acting_welcart_atodene', $noreceipt_status, true ) ) {
			$noreceipt_status[] = 'acting_welcart_conv';
			$noreceipt_status[] = 'acting_welcart_atodene';
			update_option( 'usces_noreceipt_status', $noreceipt_status );
		}

		$this->unavailable_method = array(
			'acting_escott_card',
			'acting_zeus_card',
			'acting_zeus_conv',
			'acting_sbps_card',
		);
	}

	/**
	 * Admin scripts.
	 * admin_print_footer_scripts
	 */
	public function admin_scripts() {
		global $usces;

		$admin_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		switch ( $admin_page ) :
			case 'usces_settlement':
				$settlement_selected = get_option( 'usces_settlement_selected', array() );
				if ( in_array( 'welcart', $settlement_selected, true ) ) :
					$acting_opts = $this->get_acting_settings();
					?>
<script type="text/javascript">
jQuery(document).ready( function($) {
	var card_activate = "<?php echo esc_attr( $acting_opts['card_activate'] ); ?>";
	var conv_activate = "<?php echo esc_attr( $acting_opts['conv_activate'] ); ?>";
	var atodene_activate = "<?php echo esc_attr( $acting_opts['atodene_activate'] ); ?>";
	var applepay_activate = "<?php echo esc_attr( $acting_opts['applepay_activate'] ); ?>";
	var unionpay_activate = "<?php echo esc_attr( $acting_opts['unionpay_activate'] ); ?>";

	if( "on" == card_activate || "token" == card_activate ) {
		$(".card_welcart").css("display","");
		$(".card_token_code_welcart").css("display","");
		$(".card_howtopay_welcart").css("display","");
		$(".card_sec3d_welcart").css("display","");
		var sec3d_activate = "<?php echo esc_attr( $acting_opts['sec3d_activate'] ); ?>";
		if( "on" == sec3d_activate ) {
			$(".card_key_welcart").css("display","");
		} else {
			$(".card_key_welcart").css("display","none");
		}
		var quickpay_welcart = "<?php echo esc_attr( $acting_opts['quickpay'] ); ?>";
		if( "on" == quickpay_welcart ) {
			$(".card_chooseable_quickpay_welcart").css("display","");
		} else {
			$(".card_chooseable_quickpay_welcart").css("display","none");
		}
	} else if( "link" == card_activate ) {
		$(".card_welcart").css("display","");
		$(".card_key_welcart").css("display","");
		$(".card_token_code_welcart").css("display","none");
		$(".card_howtopay_welcart").css("display","none");
		$(".card_sec3d_welcart").css("display","none");
		var quickpay_welcart = "<?php echo esc_attr( $acting_opts['quickpay'] ); ?>";
		if( "on" == quickpay_welcart ) {
			$(".card_chooseable_quickpay_welcart").css("display","");
		} else {
			$(".card_chooseable_quickpay_welcart").css("display","none");
		}
	} else {
		$(".card_welcart").css("display","none");
		$(".card_key_welcart").css("display","none");
		$(".card_token_code_welcart").css("display","none");
		$(".card_howtopay_welcart").css("display","none");
		$(".card_sec3d_welcart").css("display","none");
		$(".card_chooseable_quickpay_welcart").css("display","none");
	}

	if( "on" == conv_activate ) {
		$(".conv_welcart").css("display","");
	} else {
		$(".conv_welcart").css("display","none");
	}

	if( "on" == atodene_activate ) {
		$(".atodene_welcart").css("display","");
	} else {
		$(".atodene_welcart").css("display","none");
	}

	if( "on" == applepay_activate ) {
		$(".applepay_welcart").css( "display","");
	} else {
		$(".applepay_welcart").css("display","none");
	}

	if( "on" == unionpay_activate ) {
		$(".unionpay_welcart").css("display","");
	} else {
		$(".unionpay_welcart").css("display","none");
	}

	$(document).on( "change", ".card_activate_welcart", function() {
		if( "on" == $(this).val() || "token" == $(this).val() ) {
			$(".card_welcart").css("display","");
			$(".card_token_code_welcart").css("display","");
			$(".card_howtopay_welcart").css("display","");
			$(".card_sec3d_welcart").css("display","");
			if( "on" == $("input[name='sec3d_activate']:checked").val() ) {
				$(".card_key_welcart").css("display","");
			} else {
				$(".card_key_welcart").css("display","none");
			}
			if( "on" == $("input[name='quickpay']:checked").val() ) {
				$(".card_chooseable_quickpay_welcart").css("display","");
			} else {
				$(".card_chooseable_quickpay_welcart").css("display","none");
			}
		} else if( "link" == $(this).val() ) {
			$(".card_welcart").css("display","");
			$(".card_key_welcart").css("display","");
			$(".card_token_code_welcart").css("display","none");
			$(".card_howtopay_welcart").css("display","none");
			$(".card_sec3d_welcart").css("display","none");
			if( "on" == $("input[name='quickpay']:checked").val() ) {
				$(".card_chooseable_quickpay_welcart").css("display","");
			} else {
				$(".card_chooseable_quickpay_welcart").css("display","none");
			}
		} else {
			$(".card_welcart").css("display","none");
			$(".card_key_welcart").css("display","none");
			$(".card_token_code_welcart").css("display","none");
			$(".card_howtopay_welcart").css("display","none");
			$(".card_sec3d_welcart").css("display","none");
			$(".card_chooseable_quickpay_welcart").css("display","none");
		}
	});

	$(document).on( "change", ".sec3d_activate_welcart", function() {
		if( "on" == $(this).val() ) {
			$(".card_key_welcart").css("display","");
		} else {
			$(".card_key_welcart").css("display","none");
		}
	});

	$(document).on( "change", ".quickpay_welcart", function() {
		if( "on" == $(this).val() ) {
			$(".card_chooseable_quickpay_welcart").css("display","");
		} else {
			$(".card_chooseable_quickpay_welcart").css("display","none");
		}
	});

	$(document).on( "change", ".conv_activate_welcart", function() {
		if( "on" == $(this).val() ) {
			$(".conv_welcart").css("display","");
		} else {
			$(".conv_welcart").css("display","none");
		}
	});

	$(document).on( "change", ".atodene_activate_welcart", function() {
		if( "on" == $(this).val() ) {
			$(".atodene_welcart").css("display","");
		} else {
			$(".atodene_welcart").css("display","none");
		}
	});

	$(document).on( "change", ".applepay_activate_welcart", function() {
		if( "on" == $(this).val() ) {
			$(".applepay_welcart").css("display","");
		} else {
			$(".applepay_welcart").css("display","none");
		}
	});

	$(document).on( "change", ".unionpay_activate_welcart", function() {
		if( "on" == $(this).val() ) {
			$(".unionpay_welcart").css("display","");
		} else {
			$(".unionpay_welcart").css("display","none");
		}
	});

	adminSettlementWelcartPay = {
		openFee : function(mode) {
			$("#fee_change_field").html("");
			$("#fee_fix").val($("#"+mode+"_fee").val());
			$("#fee_limit_amount_fix").val($("#"+mode+"_fee_limit_amount_fix").val());
			$("#fee_first_amount").val($("#"+mode+"_fee_first_amount").val());
			$("#fee_first_fee").val($("#"+mode+"_fee_first_fee").val());
			$("#fee_limit_amount_change").val($("#"+mode+"_fee_limit_amount_change").val());
			var fee_amounts = new Array();
			var fee_fees = new Array();
			if( 0 < $("#"+mode+"_fee_amounts").val().length ) {
				fee_amounts = $("#"+mode+"_fee_amounts").val().split("|");
			}
			if( 0 < $("#"+mode+"_fee_fees").val().length ) {
				fee_fees = $("#"+mode+"_fee_fees").val().split("|");
			}
			if( 0 < fee_amounts.length ) {
				var amount = parseInt($("#fee_first_amount").val()) + 1;
				for( var i = 0; i < fee_amounts.length; i++ ) {
					html = '<tr id="row_'+i+'"><td class="cod_f"><span id="amount_'+i+'">'+amount+'</span></td><td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td><td class="cod_e"><input name="fee_amounts['+i+']" type="text" class="short_str num" value="'+fee_amounts[i]+'" /></td><td class="cod_cod"><input name="fee_fees['+i+']" type="text" class="short_str num" value="'+fee_fees[i]+'" /></td></tr>';
					$("#fee_change_field").append(html);
					amount = parseInt(fee_amounts[i]) + 1;
				}
				$("#end_amount").html(amount);
			} else {
				$("#end_amount").html(parseInt($("#"+mode+"_fee_first_amount").val()) + 1);
			}
			$("#fee_end_fee").val($("#"+mode+"_fee_end_fee").val());

			var fee_type = $("#"+mode+"_fee_type").val();
			if( "change" == fee_type ) {
				$("#fee_type_change").prop("checked",true);
				$("#welcartpay_fee_fix_table").css("display","none");
				$("#welcartpay_fee_change_table").css("display","");
			} else {
				$("#fee_type_fix").prop("checked",true);
				$("#welcartpay_fee_fix_table").css("display","");
				$("#welcartpay_fee_change_table").css("display","none");
			}
		},

		updateFee : function(mode) {
			var fee_type = $("input[name='fee_type']:checked").val();
			$("#"+mode+"_fee_type").val(fee_type);
			$("#"+mode+"_fee").val($("#fee_fix").val());
			$("#"+mode+"_fee_limit_amount_"+fee_type).val($("#fee_limit_amount_"+fee_type).val());
			$("#"+mode+"_fee_first_amount").val($("#fee_first_amount").val());
			$("#"+mode+"_fee_first_fee").val($("#fee_first_fee").val());
			var fee_amounts = "";
			var fee_fees = "";
			var sp = "";
			var fee_amounts_length = $("input[name^='fee_amounts']").length;
			for( var i = 0; i < fee_amounts_length; i++ ) {
				fee_amounts += sp + $("input[name='fee_amounts\["+i+"\]']").val();
				fee_fees += sp + $("input[name='fee_fees\["+i+"\]']").val();
				sp = "|";
			}
			$("#"+mode+"_fee_amounts").val(fee_amounts);
			$("#"+mode+"_fee_fees").val(fee_fees);
			$("#"+mode+"_fee_end_fee").val($("#fee_end_fee").val());
		},

		setFeeType : function( mode, closed ) {
			var fee_type = $("input[name='fee_type']:checked").val();
			if( "change" == fee_type ) {
				$("#"+mode+"_fee_type_field").html("<?php esc_attr_e( 'Variable', 'usces' ); ?>");
				if( !closed ) {
					$("#welcartpay_fee_fix_table").css("display","none");
					$("#welcartpay_fee_change_table").css("display","");
				}
			} else if( "fix" == fee_type ) {
				$("#"+mode+"_fee_type_field").html("<?php esc_attr_e( 'Fixation', 'usces' ); ?>");
				if( !closed ) {
					$("#welcartpay_fee_fix_table").css("display","");
					$("#welcartpay_fee_change_table").css("display","none");
				}
			}
		}
	};

	$("#welcartpay_fee_dialog").dialog({
		autoOpen: false,
		height: 500,
		width: 450,
		modal: true,
		open: function() {
			adminSettlementWelcartPay.openFee($("#welcartpay_fee_mode").val());
		},
		buttons: {
			"<?php esc_attr_e( 'Settings' ); ?>": function() {
				adminSettlementWelcartPay.updateFee($("#welcartpay_fee_mode").val());
			},
			"<?php esc_attr_e( 'Close' ); ?>": function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			adminSettlementWelcartPay.setFeeType($("#welcartpay_fee_mode").val(),true);
		}
	});

	$(document).on( "click", "#conv_fee_setting", function() {
		$("#welcartpay_fee_mode").val("conv");
		$("#welcartpay_fee_dialog").dialog("option","title","<?php esc_attr_e( 'Online storage agency settlement fee setting', 'usces' ); ?>");
		$("#welcartpay_fee_dialog").dialog("open");
	});

	$(document).on( "click", "#atodene_fee_setting", function() {
		$("#welcartpay_fee_mode").val("atodene");
		$("#welcartpay_fee_dialog").dialog("option","title","<?php esc_attr_e( 'Postpay settlement fee setting', 'usces' ); ?>");
		$("#welcartpay_fee_dialog").dialog("open");
	});

	$(document).on( "click", ".fee_type", function() {
		if( "change" == $(this).val() ) {
			$("#welcartpay_fee_fix_table").css("display","none");
			$("#welcartpay_fee_change_table").css("display","");
		} else {
			$("#welcartpay_fee_fix_table").css("display","");
			$("#welcartpay_fee_change_table").css("display","none");
		}
	});

	$(document).on( "change", "input[name='fee_first_amount']", function() {
		var rows = $("input[name^='fee_amounts']");
		var first_amount = $("input[name='fee_first_amount']");
		if( 0 == rows.length && $(first_amount).val() != '' ) {
			$("#end_amount").html(parseInt($(first_amount).val()) + 1);
		} else if( 0 < rows.length && $(first_amount).val() != '' ) {
			$('#amount_0').html(parseInt($(first_amount).val()) + 1);
		}
	});

	$(document).on( "change", "#fee_limit_amount_change", function() {
		if( "change" == $("input[name='fee_type']:checked").val() ) {
			var amount = parseInt($("#end_amount").html());
			var limit = parseInt($("#fee_limit_amount_change").val());
			if( amount >= limit ) {
				alert("<?php esc_attr_e( 'A value of the amount of upper limit is incorrect.', 'usces' ); ?>"+amount+' : '+limit);
			}
		}
	});

	$(document).on( "change", "input[name^='fee_amounts']", function() {
		var rows = $("input[name^='fee_amounts']");
		var cnt = $(rows).length;
		var end_amount = $("#end_amount");
		var id = $(rows).index(this);
		if( id >= cnt-1 ) {
			$( end_amount ).html(parseInt($(rows).eq(id).val()) + 1);
		} else if( id < cnt - 1 ) {
			$("#amount_"+(id + 1)).html(parseInt($(rows).eq(id).val()) + 1);
		}
	});

	$(document).on( "click", "#fee_add_row", function() {
		var rows = $("input[name^='fee_amounts']");
		$(rows).unbind("change");
		var first_amount = $("input[name='fee_first_amount']");
		var first_fee = $("input[name='fee_first_fee']");
		var end_amount = $("#end_amount");
		var enf_fee = $("input[name='fee_end_fee']");
		if( 0 == rows.length ) {
			amount = ( $(first_amount).val() == '' ) ? '' : parseInt($(first_amount).val()) + 1;
		} else if( 0 < rows.length ) {
			amount = ( $(rows).eq(rows.length-1).val() == '' ) ? '' : parseInt($(rows).eq(rows.length-1).val()) + 1;
		}
		html = '<tr id="row_'+rows.length+'"><td class="cod_f"><span id="amount_'+rows.length+'">'+amount+'</span></td><td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td><td class="cod_e"><input name="fee_amounts['+rows.length+']" type="text" class="short_str num" /></td><td class="cod_cod"><input name="fee_fees['+rows.length+']" type="text" class="short_str num" /></td></tr>';
		$("#fee_change_field").append(html);
		rows = $("input[name^='fee_amounts']");
		$(rows).bind( "change", function() {
			var cnt = $(rows).length - 1;
			var id = $(rows).index(this);
			if( id >= cnt ) {
				$(end_amount).html(parseInt($(rows).eq(id).val()) + 1);
			} else if( id < cnt ) {
				$("#amount_"+(id + 1)).html(parseInt($(rows).eq(id).val()) + 1);
			}
		});
	});

	$(document).on( "click", "#fee_del_row", function() {
		var rows = $("input[name^='fee_amounts']");
		var first_amount = $("input[name='fee_first_amount']");
		var end_amount = $("#end_amount");
		var del_id = rows.length - 1;
		if( 0 < rows.length ) {
			$("#row_"+del_id).remove();
		}
		rows = $("input[name^='fee_amounts']");
		if( 0 == rows.length && $(first_amount).val() != "" ) {
			$(end_amount).html(parseInt($(first_amount).val()) + 1);
		} else if( 0 < rows.length && $(rows).eq(rows.length - 1).val() != "" ) {
			$(end_amount).html(parseInt($(rows).eq(rows.length - 1).val()) + 1);
		}
	});

	adminSettlementWelcartPay.setFeeType("conv",false);
	adminSettlementWelcartPay.setFeeType("atodene",false);
});
</script>
					<?php
				endif;
				break;

			case 'usces_orderlist':
			case 'usces_continue':
				$acting_flg   = '';
				$dialog_title = '';
				$order_id     = '';
				$order_data   = array();

				$order_action    = filter_input( INPUT_GET, 'order_action', FILTER_SANITIZE_STRING );
				$continue_action = filter_input( INPUT_GET, 'continue_action', FILTER_SANITIZE_STRING );

				/* 受注編集画面・継続課金会員詳細画面 */
				if ( ( 'usces_orderlist' === $admin_page && ( 'edit' === $order_action || 'editpost' === $order_action || 'newpost' === $order_action ) ) ||
					( 'usces_continue' === $admin_page && 'settlement' === $continue_action ) ) {
					$order_id = filter_input( INPUT_GET, 'order_id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE );
					if ( ! $order_id && isset( $_POST['order_id'] ) ) {
						$order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE );
					}
					if ( $order_id ) {
						$order_data = $usces->get_order_data( $order_id, 'direct' );
						$payment    = usces_get_payments_by_name( $order_data['order_payment_name'] );
						if ( isset( $payment['settlement'] ) ) {
							$acting_flg = $payment['settlement'];
						}
						if ( isset( $payment['name'] ) ) {
							$dialog_title = $payment['name'];
						}
					}
				}
				$args = compact( 'order_id', 'acting_flg', 'admin_page', 'order_data' );

				if ( in_array( $acting_flg, $this->pay_method, true ) ) :
					?>
<script type="text/javascript">
jQuery(document).ready( function( $ ) {
	adminOrderEdit = {
					<?php
					if ( 'acting_welcart_card' === $acting_flg ) :
						?>
		getSettlementInfoCard : function() {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			var mode = ( "" != $("#error").val() ) ? "error_welcartpay_card" : "get_welcartpay_card";
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: mode,
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		captureSettlementCard : function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "capture_welcartpay_card",
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				if( retVal.status == "OK" ) {
					$("#settlement-status").html(retVal.acting_status);
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val("");
				} else {
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val(retVal.status);
				}
				$("#settlement-response-loading").html("");
			}).fail( function( retVal ) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		changeSettlementCard : function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "change_welcartpay_card",
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		deleteSettlementCard : function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "delete_welcartpay_card",
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				if( retVal.status == "OK" ) {
					$("#settlement-status").html(retVal.acting_status);
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val("");
				} else {
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val(retVal.status);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		authSettlementCard : function(mode,amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: mode+"_welcartpay_card",
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				if( retVal.status == "OK" ) {
					$("#settlement-status").html(retVal.acting_status);
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val("");
					if( retVal.trans_id && $("#settlement-information-9999999999-1").length ) {
						$("[id=settlement-information-9999999999-1]").attr("id","settlement-information-"+retVal.trans_id+"-1");
						$("#trans_id").val(retVal.trans_id);
					}
				} else {
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val(retVal.status);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		}
						<?php
					elseif ( 'acting_welcart_conv' === $acting_flg ) :
						?>
		getSettlementInfoConv : function() {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "get_welcartpay_conv",
					order_id: $("#order_id").val(),
					trans_id: $("#trans_id").val(),
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		changeSettlementConv : function(paylimit,amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');

			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "change_welcartpay_conv",
					order_id: $("#order_id").val(),
					trans_id: $("#trans_id").val(),
					paylimit: paylimit,
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		deleteSettlementConv : function(amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "delete_welcartpay_conv",
					order_id: $("#order_id").val(),
					trans_id: $("#trans_id").val(),
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				if( retVal.status == "OK" ) {
					$("#settlement-status").html(retVal.acting_status);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		addSettlementConv : function(paylimit,amount) {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "add_welcartpay_conv",
					order_id: $("#order_id").val(),
					trans_id: $("#trans_id").val(),
					paylimit: paylimit,
					amount: amount,
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				if( retVal.status == "OK" ) {
					$("#settlement-status").html(retVal.acting_status);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		}
						<?php
					elseif ( 'acting_welcart_unionpay' === $acting_flg ) :
						?>
		getSettlementInfoUnionPay : function() {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			var mode = ( "" != $("#error").val() ) ? "error_welcartpay_unionpay" : "get_welcartpay_unionpay";
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: mode,
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		},
		deleteSettlementUnionPay : function() {
			$("#settlement-response").html("");
			$("#settlement-response-loading").html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
			$.ajax({
				url: ajaxurl,
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
					action: "usces_admin_ajax",
					mode: "delete_welcartpay_unionpay",
					order_id: $("#order_id").val(),
					order_num: $("#order_num").val(),
					trans_id: $("#trans_id").val(),
					member_id: $("#member_id").val(),
					wc_nonce: $("#wc_nonce").val()
				}
			}).done( function(retVal,dataType) {
				$("#settlement-response").html(retVal.result);
				if( retVal.status == "OK" ) {
					$("#settlement-status").html(retVal.acting_status);
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val("");
				} else {
					$("#responsecd-"+$("#trans_id").val()+"-"+$("#order_num").val()).val(retVal.status);
				}
				$("#settlement-response-loading").html("");
			}).fail( function(retVal) {
				$("#settlement-response-loading").html("");
			});
			return false;
		}
						<?php
					endif;
					?>
	};

	$("#settlement_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: "auto",
		width: 800,
		resizable: true,
		modal: true,
		buttons: {
			"<?php esc_attr_e( 'Close' ); ?>": function() {
				$(this).dialog("close");
			}
		},
		open: function() {
					<?php
					if ( 'acting_welcart_card' === $acting_flg ) :
						?>
			adminOrderEdit.getSettlementInfoCard();
						<?php
					elseif ( 'acting_welcart_conv' === $acting_flg ) :
						?>
			adminOrderEdit.getSettlementInfoConv();
						<?php
					elseif ( 'acting_welcart_unionpay' === $acting_flg ) :
						?>
			adminOrderEdit.getSettlementInfoUnionPay();
						<?php
					endif;
					?>
		},
		close: function() {
					<?php do_action( 'usces_action_welcartpay_settlement_dialog_close', $args ); ?>
		}
	});

	$(document).on( "click", ".settlement-information", function() {
		var idname = $(this).attr("id");
		var ids = idname.split("-");
		$("#trans_id").val(ids[2]);
		$("#order_num").val(ids[3]);
		if( undefined != $("#responsecd-"+ids[2]+"-"+ids[3]) ) {
			$("#error").val($("#responsecd-"+ids[2]+"-"+ids[3]).val());
		} else {
			$("#error").val("");
		}
		$("#settlement_dialog").dialog("option","title","<?php echo esc_attr( $dialog_title ); ?>");
		$("#settlement_dialog").dialog("open");
	});

					<?php
					if ( 'acting_welcart_card' === $acting_flg ) :
						?>
	$(document).on( "click", "#capture-settlement", function() {
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to execute sales accounting processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.captureSettlementCard($("#amount_change").val());
	});

	$(document).on( "click", "#delete-settlement", function() {
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to cancellation processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.deleteSettlementCard($("#amount_change").val());
	});

	$(document).on( "click", "#change-settlement", function() {
		if( $("#amount_change").val() == $("#amount").val() ) {
			return;
		}
		var amount = $("#amount_change").val();
		if( amount == "" || parseInt(amount) === 0 || !checkNum(amount) ) {
			alert("<?php esc_attr_e( 'The spending amount format is incorrect. Please enter with numeric value.', 'usces' ); ?>");
			return;
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to change the spending amount?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.changeSettlementCard($("#amount_change").val());
	});

	$(document).on( "click", "#auth-settlement", function() {
		var amount = $("#amount_change").val();
		if( amount == "" || parseInt(amount) === 0 || !checkNum(amount) ) {
			alert("<?php esc_attr_e( 'The spending amount format is incorrect. Please enter with numeric value.', 'usces' ); ?>");
			return;
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to execute credit processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.authSettlementCard("auth",$("#amount_change").val());
	});

	$(document).on( "click", "#gathering-settlement", function() {
		var amount = $("#amount_change").val();
		if( amount == "" || parseInt(amount) === 0 || !checkNum(amount) ) {
			alert("<?php esc_attr_e( 'The spending amount format is incorrect. Please enter with numeric value.', 'usces' ); ?>");
			return;
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to execute credit sales processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.authSettlementCard("gathering",$("#amount_change").val());
	});

	$(document).on( "click", "#reauth-settlement", function() {
		var amount = $("#amount_change").val();
		if( amount == "" || parseInt(amount) === 0 || !checkNum(amount) ) {
			alert("<?php esc_attr_e( 'The spending amount format is incorrect. Please enter with numeric value.', 'usces' ); ?>");
			return;
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to re-authorization?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.authSettlementCard("reauth",$("#amount_change").val());
	});

						<?php
					elseif ( 'acting_welcart_conv' === $acting_flg ) :
						?>
	$(document).on( "click", "#delete-settlement", function() {
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to cancellation processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.deleteSettlementConv($("#amount_change").val());
	});

	$(document).on( "click", "#change-settlement", function() {
		if( ( $("#paylimit_change").val() == $("#paylimit").val() ) &&
			( $("#amount_change").val() == $("#amount").val() ) ) {
			return;
		}
		var paylimit = $("#paylimit_change").val();
		var amount = $("#amount_change").val();
		var today = "<?php echo esc_attr( $this->get_transaction_date() ); ?>";
		if( paylimit.length != 8 || !checkNum(paylimit) ) {
			alert("<?php esc_attr_e( 'The payment due format is incorrect. Please enter with 8 digit number.', 'usces' ); ?>");
			return;
		}
		if( today > paylimit ) {
			alert("<?php esc_attr_e( 'The payment due is incorrect. Date before today cannot be specified.', 'usces' ); ?>");
			return;
		}
		if( amount == "" || parseInt( amount ) === 0 || !checkNum( amount ) ) {
			alert("<?php esc_attr_e( 'The payment amount format is incorrect. Please enter with numeric value.', 'usces' ); ?>");
			return;
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to change payment due and payment amount?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.changeSettlementConv($("#paylimit_change").val(),$("#amount_change").val());
	});

	$(document).on( "click", "#add-settlement", function() {
		var paylimit = $("#paylimit_change").val();
		var amount = $("#amount_change").val();
		var today = "<?php echo esc_attr( $this->get_transaction_date() ); ?>";
		if( paylimit.length != 8 || !checkNum(paylimit) ) {
			alert("<?php esc_attr_e( 'The payment due format is incorrect. Please enter with 8 digit number.', 'usces' ); ?>");
			return;
		}
		if( today > paylimit ) {
			alert("<?php esc_attr_e( 'The payment due is incorrect. Date before today cannot be specified.', 'usces' ); ?>");
			return;
		}
		if( amount == "" || parseInt(amount) === 0 || !checkNum(amount) ) {
			alert("<?php esc_attr_e( 'The payment amount format is incorrect. Please enter with numeric value.', 'usces' ); ?>");
			return;
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to execute the registration processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.addSettlementConv($("#paylimit_change").val(),$("#amount_change").val());
	});

						<?php
					elseif ( 'acting_welcart_unionpay' === $acting_flg ) :
						?>
	$(document).on( "click", "#delete-settlement", function() {
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to cancellation processing?', 'usces' ); ?>") ) {
			return;
		}
		adminOrderEdit.deleteSettlementUnionPay();
	});

						<?php
					endif;
					if ( 'usces_continue' === $admin_page ) :
						?>
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
				if( retVal.status == "OK" ) {
					adminOperation.setActionStatus("success","<?php esc_attr_e( 'The update was completed.', 'usces' ); ?>");
				} else {
					mes = ( retVal.message != "" ) ? retVal.message : "<?php esc_attr_e( 'failure in update', 'usces' ); ?>";
					adminOperation.setActionStatus("error",mes);
				}
			}).fail( function(retVal) {
				adminOperation.setActionStatus("error","<?php esc_attr_e( 'failure in update', 'usces' ); ?>");
			});
			return false;
		}
	};

	$("#price").bind( "change", function() {
		usces_check_money($(this));
	});

	$(document).on( "click", "#continuation-update", function() {
		var status = $("#dlseller-status option:selected").val();
		if( status == "continuation" ) {
			var year = $("#charged-year option:selected").val();
			var month = $("#charged-month option:selected").val();
			var day = $("#charged-day option:selected").val();
			if( year == 0 || month == 0 || day == 0 ) {
				alert("<?php esc_attr_e( 'Data have deficiency.', 'usces' ); ?>");
				$("#charged-year").focus();
				return;
			}
			if( $("#price").val() == "" || parseInt($("#price").val()) == 0 ) {
				alert("<?php printf( __( 'Input the %s', 'usces' ), esc_attr__( 'Amount', 'dlseller' ) ); ?>");
				$("#price").focus();
				return;
			}
		}
		if( !confirm("<?php esc_attr_e( 'Are you sure you want to update the settings?', 'usces' ); ?>") ) {
			return;
		}
		adminContinuation.update();
	});
						<?php
					endif;
					?>
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

		if ( 'welcart' !== filter_input( INPUT_POST, 'acting', FILTER_SANITIZE_STRING ) ) {
			return;
		}

		$this->error_mes = '';
		$options         = get_option( 'usces', array() );
		$payment_method  = usces_get_system_option( 'usces_payment_method', 'settlement' );
		$post_data       = wp_unslash( $_POST );

		unset( $options['acting_settings']['welcart'] );
		$options['acting_settings']['welcart']['merchant_id']                   = ( isset( $post_data['merchant_id'] ) ) ? trim( $post_data['merchant_id'] ) : '';
		$options['acting_settings']['welcart']['merchant_pass']                 = ( isset( $post_data['merchant_pass'] ) ) ? trim( $post_data['merchant_pass'] ) : '';
		$options['acting_settings']['welcart']['tenant_id']                     = ( isset( $post_data['tenant_id'] ) ) ? trim( $post_data['tenant_id'] ) : '0001';
		$options['acting_settings']['welcart']['auth_key']                      = ( isset( $post_data['auth_key'] ) ) ? trim( $post_data['auth_key'] ) : '';
		$options['acting_settings']['welcart']['ope']                           = ( isset( $post_data['ope'] ) ) ? $post_data['ope'] : '';
		$options['acting_settings']['welcart']['card_activate']                 = ( isset( $post_data['card_activate'] ) ) ? $post_data['card_activate'] : 'off';
		$options['acting_settings']['welcart']['card_key_aes']                  = ( isset( $post_data['card_key_aes'] ) ) ? $post_data['card_key_aes'] : '';
		$options['acting_settings']['welcart']['card_key_iv']                   = ( isset( $post_data['card_key_iv'] ) ) ? $post_data['card_key_iv'] : '';
		$options['acting_settings']['welcart']['foreign_activate']              = ( isset( $post_data['foreign_activate'] ) ) ? $post_data['foreign_activate'] : '';
		$options['acting_settings']['welcart']['seccd']                         = ( isset( $post_data['seccd'] ) ) ? $post_data['seccd'] : 'on';
		$options['acting_settings']['welcart']['sec3d_activate']                = ( isset( $post_data['sec3d_activate'] ) ) ? $post_data['sec3d_activate'] : 'off';
		$options['acting_settings']['welcart']['token_code']                    = ( isset( $post_data['token_code'] ) ) ? trim( $post_data['token_code'] ) : '';
		$options['acting_settings']['welcart']['quickpay']                      = ( isset( $post_data['quickpay'] ) ) ? $post_data['quickpay'] : '';
		$options['acting_settings']['welcart']['chooseable_quickpay']           = ( isset( $post_data['chooseable_quickpay'] ) ) ? $post_data['chooseable_quickpay'] : 'on';
		$options['acting_settings']['welcart']['operateid']                     = ( isset( $post_data['operateid'] ) ) ? $post_data['operateid'] : '1Gathering';
		$options['acting_settings']['welcart']['operateid_dlseller']            = ( isset( $post_data['operateid_dlseller'] ) ) ? $post_data['operateid_dlseller'] : '1Gathering';
		$options['acting_settings']['welcart']['auto_settlement_mail']          = ( isset( $post_data['auto_settlement_mail'] ) ) ? $post_data['auto_settlement_mail'] : 'off';
		$options['acting_settings']['welcart']['howtopay']                      = ( isset( $post_data['howtopay'] ) ) ? $post_data['howtopay'] : '';
		$options['acting_settings']['welcart']['conv_activate']                 = ( isset( $post_data['conv_activate'] ) ) ? $post_data['conv_activate'] : 'off';
		$options['acting_settings']['welcart']['conv_limit']                    = ( ! empty( $post_data['conv_limit'] ) ) ? $post_data['conv_limit'] : '7';
		$options['acting_settings']['welcart']['conv_fee_type']                 = ( isset( $post_data['conv_fee_type'] ) ) ? $post_data['conv_fee_type'] : '';
		$options['acting_settings']['welcart']['conv_fee']                      = ( isset( $post_data['conv_fee'] ) ) ? $post_data['conv_fee'] : '';
		$options['acting_settings']['welcart']['conv_fee_limit_amount']         = ( isset( $post_data[ 'conv_fee_limit_amount_' . $options['acting_settings']['welcart']['conv_fee_type'] ] ) ) ? $post_data[ 'conv_fee_limit_amount_' . $options['acting_settings']['welcart']['conv_fee_type'] ] : '';
		$options['acting_settings']['welcart']['conv_fee_first_amount']         = ( isset( $post_data['conv_fee_first_amount'] ) ) ? $post_data['conv_fee_first_amount'] : '';
		$options['acting_settings']['welcart']['conv_fee_first_fee']            = ( isset( $post_data['conv_fee_first_fee'] ) ) ? $post_data['conv_fee_first_fee'] : '';
		$options['acting_settings']['welcart']['conv_fee_amounts']              = ( isset( $post_data['conv_fee_amounts'] ) ) ? explode( '|', $post_data['conv_fee_amounts'] ) : array();
		$options['acting_settings']['welcart']['conv_fee_fees']                 = ( isset( $post_data['conv_fee_fees'] ) ) ? explode( '|', $post_data['conv_fee_fees'] ) : array();
		$options['acting_settings']['welcart']['conv_fee_end_fee']              = ( isset( $post_data['conv_fee_end_fee'] ) ) ? $post_data['conv_fee_end_fee'] : '';
		$options['acting_settings']['welcart']['atodene_activate']              = ( isset( $post_data['atodene_activate'] ) ) ? $post_data['atodene_activate'] : 'off';
		$options['acting_settings']['welcart']['atodene_byitem']                = ( isset( $post_data['atodene_byitem'] ) ) ? $post_data['atodene_byitem'] : 'off';
		$options['acting_settings']['welcart']['atodene_billing_method']        = ( isset( $post_data['atodene_billing_method'] ) ) ? $post_data['atodene_billing_method'] : '2';
		$options['acting_settings']['welcart']['atodene_fee_type']              = ( isset( $post_data['atodene_fee_type'] ) ) ? $post_data['atodene_fee_type'] : '';
		$options['acting_settings']['welcart']['atodene_fee']                   = ( isset( $post_data['atodene_fee'] ) ) ? $post_data['atodene_fee'] : '';
		$options['acting_settings']['welcart']['atodene_fee_limit_amount']      = ( isset( $post_data[ 'atodene_fee_limit_amount_' . $options['acting_settings']['welcart']['atodene_fee_type'] ] ) ) ? $post_data[ 'atodene_fee_limit_amount_' . $options['acting_settings']['welcart']['atodene_fee_type'] ] : '';
		$options['acting_settings']['welcart']['atodene_fee_first_amount']      = ( isset( $post_data['atodene_fee_first_amount'] ) ) ? $post_data['atodene_fee_first_amount'] : '';
		$options['acting_settings']['welcart']['atodene_fee_first_fee']         = ( isset( $post_data['atodene_fee_first_fee'] ) ) ? $post_data['atodene_fee_first_fee'] : '';
		$options['acting_settings']['welcart']['atodene_fee_amounts']           = ( isset( $post_data['atodene_fee_amounts'] ) ) ? explode( '|', $post_data['atodene_fee_amounts'] ) : array();
		$options['acting_settings']['welcart']['atodene_fee_fees']              = ( isset( $post_data['atodene_fee_fees'] ) ) ? explode( '|', $post_data['atodene_fee_fees'] ) : array();
		$options['acting_settings']['welcart']['atodene_fee_end_fee']           = ( isset( $post_data['atodene_fee_end_fee'] ) ) ? $post_data['atodene_fee_end_fee'] : '';
		$options['acting_settings']['welcart']['applepay_activate']             = ( isset( $post_data['applepay_activate'] ) ) ? $post_data['applepay_activate'] : 'off';
		$options['acting_settings']['welcart']['applepay_tenant_id']            = ( isset( $post_data['applepay_tenant_id'] ) ) ? $post_data['applepay_tenant_id'] : '';
		$options['acting_settings']['welcart']['applepay_quickpay']             = ( isset( $post_data['applepay_quickpay'] ) ) ? $post_data['applepay_quickpay'] : '';
		$options['acting_settings']['welcart']['applepay_operateid']            = ( isset( $post_data['applepay_operateid'] ) ) ? $post_data['applepay_operateid'] : '1Gathering';
		$options['acting_settings']['welcart']['applepay_key_aes']              = ( isset( $post_data['applepay_key_aes'] ) ) ? $post_data['applepay_key_aes'] : '';
		$options['acting_settings']['welcart']['applepay_key_iv']               = ( isset( $post_data['applepay_key_iv'] ) ) ? $post_data['applepay_key_iv'] : '';
		$options['acting_settings']['welcart']['applepay_certificate_path']     = ( isset( $post_data['applepay_certificate_path'] ) ) ? $post_data['applepay_certificate_path'] : '';
		$options['acting_settings']['welcart']['applepay_certificate_key_pass'] = ( isset( $post_data['applepay_certificate_key_pass'] ) ) ? $post_data['applepay_certificate_key_pass'] : '';
		$options['acting_settings']['welcart']['applepay_merchantidentifier']   = ( isset( $post_data['applepay_merchantidentifier'] ) ) ? $post_data['applepay_merchantidentifier'] : '';
		$options['acting_settings']['welcart']['unionpay_activate']             = ( isset( $post_data['unionpay_activate'] ) ) ? $post_data['unionpay_activate'] : 'off';
		$options['acting_settings']['welcart']['unionpay_key_aes']              = ( isset( $post_data['unionpay_key_aes'] ) ) ? $post_data['unionpay_key_aes'] : '';
		$options['acting_settings']['welcart']['unionpay_key_iv']               = ( isset( $post_data['unionpay_key_iv'] ) ) ? $post_data['unionpay_key_iv'] : '';
		$options['acting_settings']['welcart']['unionpay_pagelanguage']         = ( isset( $post_data['unionpay_pagelanguage'] ) ) ? $post_data['unionpay_pagelanguage'] : '';

		if ( 'on' === $options['acting_settings']['welcart']['card_activate'] || 'on' === $options['acting_settings']['welcart']['conv_activate'] ) {
			$unavailable_activate = false;
			foreach ( $payment_method as $settlement => $payment ) {
				if ( in_array( $settlement, $this->unavailable_method, true ) && 'activate' === $payment['use'] ) {
					$unavailable_activate = true;
					break;
				}
			}
			if ( $unavailable_activate ) {
				$this->error_mes .= __( '* Settlement that can not be used together is activated.', 'usces' ) . '<br />';
			} else {
				if ( WCUtils::is_blank( $post_data['merchant_id'] ) ) {
					$this->error_mes .= __( '* Please enter the Merchant ID.', 'usces' ) . '<br />';
				}
				if ( WCUtils::is_blank( $post_data['merchant_pass'] ) ) {
					$this->error_mes .= __( '* Please enter the Merchant Password.', 'usces' ) . '<br />';
				}
				if ( WCUtils::is_blank( $post_data['tenant_id'] ) ) {
					$this->error_mes .= __( '* Please enter the Tenant ID.', 'usces' ) . '<br />';
				}
				if ( WCUtils::is_blank( $post_data['auth_key'] ) ) {
					$this->error_mes .= __( '* Please enter the Settlement auth key.', 'usces' ) . '<br />';
				} else {
					$auth_key        = md5( $post_data['auth_key'] );
					$welcartpay_keys = get_option( 'usces_welcartpay_keys', array() );
					if ( ! in_array( $auth_key, $welcartpay_keys, true ) ) {
						$this->error_mes .= __( '* The Settlement auth key is incorrect.', 'usces' ) . '<br />';
					}
				}
				if ( WCUtils::is_blank( $post_data['ope'] ) ) {
					$this->error_mes .= __( '* Please select the operating environment.', 'usces' ) . '<br />';
				}
				if ( WCUtils::is_blank( $post_data['operateid'] ) ) {
					$this->error_mes .= __( '* Please select the processing classification.', 'usces' ) . '<br />';
				}
				if ( 'on' === $options['acting_settings']['welcart']['card_activate'] ) {
					if ( WCUtils::is_blank( $post_data['token_code'] ) ) {
						$this->error_mes .= __( '* Please enter the Token auth code.', 'usces' ) . '<br />';
					}
				}
			}
		}
		if ( 'on' === $options['acting_settings']['welcart']['unionpay_activate'] ) {
			if ( WCUtils::is_blank( $post_data['unionpay_key_aes'] ) ) {
				$this->error_mes .= __( '* Please enter the UnionPay tripartite encryption key.', 'usces' ) . '<br />';
			}
			if ( WCUtils::is_blank( $post_data['unionpay_key_iv'] ) ) {
				$this->error_mes .= __( '* Please enter the UnionPay tripartite initialization vector.', 'usces' ) . '<br />';
			}
		}

		if ( '' === $this->error_mes ) {
			$usces->action_status  = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if ( ( 'on' === $options['acting_settings']['welcart']['card_activate'] || 'link' === $options['acting_settings']['welcart']['card_activate'] || 'token' === $options['acting_settings']['welcart']['card_activate'] ) ||
				'on' === $options['acting_settings']['welcart']['conv_activate'] ||
				'on' === $options['acting_settings']['welcart']['atodene_activate'] ||
				'on' === $options['acting_settings']['welcart']['applepay_activate'] ||
				'on' === $options['acting_settings']['welcart']['unionpay_activate'] ) {
				$options['acting_settings']['welcart']['activate'] = 'on';
				if ( 'public' === $options['acting_settings']['welcart']['ope'] ) {
					$options['acting_settings']['welcart']['send_url']                 = 'https://www.e-scott.jp/online/aut/OAUT002.do';
					$options['acting_settings']['welcart']['send_url_member']          = 'https://www.e-scott.jp/online/crp/OCRP005.do';
					$options['acting_settings']['welcart']['send_url_conv']            = 'https://www.e-scott.jp/online/cnv/OCNV005.do';
					$options['acting_settings']['welcart']['redirect_url_conv']        = 'https://link.kessai.info/JLP/JLPcon';
					$options['acting_settings']['welcart']['send_url_link']            = 'https://www.e-scott.jp/euser/snp/SSNP005ReferStart.do';
					$options['acting_settings']['welcart']['api_token']                = 'https://www.e-scott.jp/euser/stn/CdGetJavaScript.do';
					$options['acting_settings']['welcart']['send_url_token']           = 'https://www.e-scott.jp/online/atn/OATN005.do';
					$options['acting_settings']['welcart']['send_url_3dsecure']        = 'https://www.e-scott.jp/online/tds/OTDS010.do';
					$options['acting_settings']['welcart']['send_url_applepay']        = 'https://www.e-scott.jp/online/aut/OAUT002.do';
					$options['acting_settings']['welcart']['send_url_applepay_member'] = 'https://www.e-scott.jp/online/crp/OCRP005.do';
					$options['acting_settings']['welcart']['send_url_unionpay']        = 'https://www.e-scott.jp/online/agc/OAGC010.do';
				} else {
					$options['acting_settings']['welcart']['send_url']                 = 'https://www.test.e-scott.jp/online/aut/OAUT002.do';
					$options['acting_settings']['welcart']['send_url_member']          = 'https://www.test.e-scott.jp/online/crp/OCRP005.do';
					$options['acting_settings']['welcart']['send_url_conv']            = 'https://www.test.e-scott.jp/online/cnv/OCNV005.do';
					$options['acting_settings']['welcart']['redirect_url_conv']        = 'https://link.kessai.info/JLPCT/JLPcon';
					$options['acting_settings']['welcart']['send_url_link']            = 'https://www.test.e-scott.jp/euser/snp/SSNP005ReferStart.do';
					$options['acting_settings']['welcart']['api_token']                = 'https://www.test.e-scott.jp/euser/stn/CdGetJavaScript.do';
					$options['acting_settings']['welcart']['send_url_token']           = 'https://www.test.e-scott.jp/online/atn/OATN005.do';
					$options['acting_settings']['welcart']['send_url_3dsecure']        = 'https://www.test.e-scott.jp/online/tds/OTDS010.do';
					$options['acting_settings']['welcart']['send_url_applepay']        = 'https://www.test.e-scott.jp/online/aut/OAUT002.do';
					$options['acting_settings']['welcart']['send_url_applepay_member'] = 'https://www.test.e-scott.jp/online/crp/OCRP005.do';
					$options['acting_settings']['welcart']['send_url_unionpay']        = 'https://www.test.e-scott.jp/online/agc/OAGC010.do';
					$options['acting_settings']['welcart']['tenant_id']                = '0001';
				}
				$toactive = array();
				if ( 'on' === $options['acting_settings']['welcart']['card_activate'] ) {
					if ( ! empty( $options['acting_settings']['welcart']['token_code'] ) ) {
						$options['acting_settings']['welcart']['card_activate'] = 'token';
					}
				}
				if ( 'on' === $options['acting_settings']['welcart']['card_activate'] || 'link' === $options['acting_settings']['welcart']['card_activate'] || 'token' === $options['acting_settings']['welcart']['card_activate'] ) {
					$usces->payment_structure['acting_welcart_card'] = __( 'Credit card transaction (WelcartPay)', 'usces' );
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_welcart_card' === $settlement && 'deactivate' === $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_welcart_card'] );
				}
				if ( 'on' === $options['acting_settings']['welcart']['conv_activate'] ) {
					$usces->payment_structure['acting_welcart_conv'] = __( 'Online storage agency (WelcartPay)', 'usces' );
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_welcart_conv' === $settlement && 'deactivate' === $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_welcart_conv'] );
				}
				if ( 'on' === $options['acting_settings']['welcart']['atodene_activate'] ) {
					$usces->payment_structure['acting_welcart_atodene'] = __( 'Postpay settlement (WelcartPay/ATODENE)', 'usces' );
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_welcart_atodene' === $settlement && 'deactivate' === $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_welcart_atodene'] );
				}
				if ( 'on' === $options['acting_settings']['welcart']['unionpay_activate'] ) {
					$usces->payment_structure['acting_welcart_unionpay'] = __( 'UnionPay (WelcartPay)', 'usces' );
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_welcart_unionpay' === $settlement && 'deactivate' === $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_welcart_unionpay'] );
				}
				usces_admin_orderlist_show_wc_trans_id();
				if ( 0 < count( $toactive ) ) {
					$usces->action_message .= __( 'Please update the payment method to "Activate". <a href="admin.php?page=usces_initial#payment_method_setting">General Setting > Payment Methods</a>', 'usces' );
				}
			} else {
				$options['acting_settings']['welcart']['activate'] = 'off';
				unset( $usces->payment_structure['acting_welcart_card'] );
				unset( $usces->payment_structure['acting_welcart_conv'] );
				unset( $usces->payment_structure['acting_welcart_atodene'] );
				unset( $usces->payment_structure['acting_welcart_applepay'] );
				unset( $usces->payment_structure['acting_welcart_unionpay'] );
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
			$usces->action_status                              = 'error';
			$usces->action_message                             = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['welcart']['activate'] = 'off';
			unset( $usces->payment_structure['acting_welcart_card'] );
			unset( $usces->payment_structure['acting_welcart_conv'] );
			unset( $usces->payment_structure['acting_welcart_atodene'] );
			unset( $usces->payment_structure['acting_welcart_applepay'] );
			unset( $usces->payment_structure['acting_welcart_unionpay'] );
			$deactivate = array();
			foreach ( $payment_method as $settlement => $payment ) {
				if ( in_array( $settlement, $this->pay_method, true ) ) {
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
	 * クレジット決済設定画面フォーム
	 * usces_action_settlement_tab_body
	 */
	public function settlement_tab_body() {
		$acting_opts         = $this->get_acting_settings();
		$settlement_selected = get_option( 'usces_settlement_selected', array() );
		if ( in_array( 'welcart', $settlement_selected, true ) ) :
			$ope                 = ( isset( $acting_opts['ope'] ) && 'public' === $acting_opts['ope'] ) ? 'public' : 'test';
			$card_activate       = ( isset( $acting_opts['card_activate'] ) && ( 'on' === $acting_opts['card_activate'] || 'token' === $acting_opts['card_activate'] ) ) ? 'on' : $acting_opts['card_activate'];
			$sec3d_activate      = ( isset( $acting_opts['sec3d_activate'] ) && 'on' === $acting_opts['sec3d_activate'] ) ? 'on' : 'off';
			$seccd               = ( isset( $acting_opts['seccd'] ) && 'on' === $acting_opts['seccd'] ) ? 'on' : 'off';
			$quickpay            = ( isset( $acting_opts['quickpay'] ) && 'on' === $acting_opts['quickpay'] ) ? 'on' : 'off';
			$chooseable_quickpay = ( isset( $acting_opts['chooseable_quickpay'] ) && 'on' === $acting_opts['chooseable_quickpay'] ) ? 'on' : 'off';
			$operateid           = ( isset( $acting_opts['operateid'] ) ) ? $acting_opts['operateid'] : '1Gathering';
			if ( defined( 'WCEX_DLSELLER' ) ) {
				$operateid_dlseller   = ( isset( $acting_opts['operateid_dlseller'] ) ) ? $acting_opts['operateid_dlseller'] : '1Gathering';
				$auto_settlement_mail = ( isset( $acting_opts['auto_settlement_mail'] ) && 'on' === $acting_opts['auto_settlement_mail'] ) ? 'on' : 'off';
			}
			$howtopay               = ( isset( $acting_opts['howtopay'] ) ) ? $acting_opts['howtopay'] : '1';
			$conv_activate          = ( isset( $acting_opts['conv_activate'] ) && 'on' === $acting_opts['conv_activate'] ) ? 'on' : 'off';
			$atodene_activate       = ( isset( $acting_opts['atodene_activate'] ) && 'on' === $acting_opts['atodene_activate'] ) ? 'on' : 'off';
			$atodene_byitem         = ( isset( $acting_opts['atodene_byitem'] ) && 'on' === $acting_opts['atodene_byitem'] ) ? 'on' : 'off';
			$atodene_billing_method = ( isset( $acting_opts['atodene_billing_method'] ) ) ? $acting_opts['atodene_billing_method'] : '2';
			$unionpay_activate      = ( isset( $acting_opts['unionpay_activate'] ) && 'on' === $acting_opts['unionpay_activate'] ) ? 'on' : 'off';
			?>
	<div id="uscestabs_welcart">
	<div class="settlement_service"><span class="service_title"><?php esc_html_e( 'WelcartPay', 'usces' ); ?></span></div>
			<?php
			if ( 'welcart' === filter_input( INPUT_POST, 'acting', FILTER_SANITIZE_STRING ) ) :
				if ( '' !== $this->error_mes ) :
					?>
	<div class="error_message"><?php echo wp_kses_post( $this->error_mes ); ?></div>
					<?php
				elseif ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) :
					?>
	<div class="message"><?php esc_html_e( 'Test thoroughly before use.', 'usces' ); ?></div>
					<?php
				endif;
			endif;
			?>
	<form action="" method="post" name="welcart_form" id="welcart_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_merchant_id_welcart"><?php esc_html_e( 'Merchant ID', 'usces' ); /* マーチャントID */ ?></a></th>
				<td><input name="merchant_id" type="text" id="merchant_id_welcart" value="<?php echo esc_attr( $acting_opts['merchant_id'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_merchant_id_welcart" class="explanation"><td colspan="2"><?php esc_html_e( 'Merchant ID (single-byte numbers only) issued from e-SCOTT.', 'usces' ); ?></td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_merchant_pass_welcart"><?php esc_html_e( 'Merchant Password', 'usces' ); /* マーチャントパスワード */ ?></a></th>
				<td><input name="merchant_pass" type="text" id="merchant_pass_welcart" value="<?php echo esc_attr( $acting_opts['merchant_pass'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_merchant_pass_welcart" class="explanation"><td colspan="2"><?php esc_html_e( 'Merchant Password (single-byte alphanumeric characters only) issued from e-SCOTT.', 'usces' ); ?></td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_tenant_id_welcart"><?php esc_html_e( 'Tenant ID', 'usces' ); /* 店舗コード */ ?></a></th>
				<td><input name="tenant_id" type="text" id="tenant_id_welcart" value="<?php echo esc_attr( $acting_opts['tenant_id'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_tenant_id_welcart" class="explanation"><td colspan="2"><?php echo wp_kses_post( __( 'Tenant ID issued from e-SCOTT.<br />If you have only one shop to contract, enter 0001.', 'usces' ) ); ?></td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_auth_key_welcart"><?php esc_html_e( 'Settlement auth key', 'usces' ); /* 決済認証キー */ ?></a></th>
				<td><input name="auth_key" type="text" id="auth_key_welcart" value="<?php echo esc_attr( $acting_opts['auth_key'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_auth_key_welcart" class="explanation"><td colspan="2"><?php esc_html_e( 'Settlement auth key (single-byte numbers only) issued from e-SCOTT.', 'usces' ); ?></td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_welcart"><?php esc_html_e( 'Operation Environment', 'usces' ); /* 動作環境 */ ?></a></th>
				<td><label><input name="ope" type="radio" id="ope_welcart_1" value="test"<?php checked( $ope, 'test' ); ?> /><span><?php esc_html_e( 'Testing environment', 'usces' ); ?></span></label><br />
					<label><input name="ope" type="radio" id="ope_welcart_2" value="public"<?php checked( $ope, 'public' ); ?> /><span><?php esc_html_e( 'Production environment', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_ope_welcart" class="explanation"><td colspan="2"><?php esc_html_e( 'Switch the operating environment.', 'usces' ); ?></td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><?php esc_html_e( 'Credit card settlement', 'usces' ); /* クレジットカード決済 */ ?></th>
				<td><label><input name="card_activate" type="radio" class="card_activate_welcart" id="card_activate_welcart_1" value="on"<?php checked( $card_activate, 'on' ); ?> /><span><?php esc_html_e( 'Use with non-passage type', 'usces' ); ?></span></label><br />
					<label><input name="card_activate" type="radio" class="card_activate_welcart" id="card_activate_welcart_2" value="link"<?php checked( $card_activate, 'link' ); ?> /><span><?php esc_html_e( 'Use with external link type', 'usces' ); ?></span></label><br />
					<label><input name="card_activate" type="radio" class="card_activate_welcart" id="card_activate_welcart_0" value="off"<?php checked( $card_activate, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr class="card_sec3d_welcart">
				<th><a class="explanation-label" id="label_ex_sec3d_activate"><?php esc_html_e( '3D Secure', 'usces' ); /* 3Dセキュア */ ?></a></th>
				<td><label><input name="sec3d_activate" type="radio" class="sec3d_activate_welcart" id="sec3d_activate_1" value="on"<?php checked( $sec3d_activate, 'on' ); ?> /><span><?php esc_html_e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="sec3d_activate" type="radio" class="sec3d_activate_welcart" id="sec3d_activate_0" value="off"<?php checked( $sec3d_activate, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_sec3d_activate" class="explanation card_welcart card_sec3d_welcart"><td colspan="2"><?php esc_html_e( '3D secure authentication at the time of payment. If you want to use it, you need to apply to Sony Payment Service Co., Ltd.', 'usces' ); ?></td></tr>
			<tr class="card_welcart card_key_welcart">
				<th><a class="explanation-label" id="label_ex_card_key_aes_welcart"><?php esc_html_e( 'Encryption Key', 'usces' ); /* 暗号化キー */ ?></a></th>
				<td><input name="card_key_aes" type="text" id="card_key_aes_welcart" value="<?php echo esc_attr( $acting_opts['card_key_aes'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_card_key_aes_welcart" class="explanation card_welcart card_key_welcart"><td colspan="2"><?php esc_html_e( 'Encryption key (single-byte alphanumeric characters only) issued by e-SCOTT.', 'usces' ); ?><?php esc_html_e( 'If you want to use 3D Secure Authentication or External Link Type Payment, please apply to Sony Payment Service Co., Ltd.', 'usces' ); ?></td></tr>
			<tr class="card_welcart card_key_welcart">
				<th><a class="explanation-label" id="label_ex_card_key_iv_welcart"><?php esc_html_e( 'Initialization Vector', 'usces' ); /* 初期化ベクトル */ ?></a></th>
				<td><input name="card_key_iv" type="text" id="card_key_iv_welcart" value="<?php echo esc_attr( $acting_opts['card_key_iv'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_card_key_iv_welcart" class="explanation card_welcart card_key_welcart"><td colspan="2"><?php esc_html_e( 'Initialization vector (single-byte alphanumeric characters only) issued by e-SCOTT.', 'usces' ); ?><?php esc_html_e( 'If you want to use 3D Secure Authentication or External Link Type Payment, please apply to Sony Payment Service Co., Ltd.', 'usces' ); ?></td></tr>
			<tr class="card_token_code_welcart">
				<th><a class="explanation-label" id="label_ex_token_code_welcart"><?php esc_html_e( 'Token auth code', 'usces' ); /* トークン決済認証コード */ ?></a></th>
				<td><input name="token_code" type="text" id="token_code_welcart" value="<?php echo esc_attr( $acting_opts['token_code'] ); ?>" class="regular-text" maxlength="32" /></td>
			</tr>
			<tr id="ex_token_code_welcart" class="explanation card_token_code_welcart"><td colspan="2"><?php esc_html_e( 'Token auth code (single-byte alphanumeric characters only) issued from e-SCOTT.', 'usces' ); ?></td></tr>
			<tr class="card_welcart">
				<th><a class="explanation-label" id="label_ex_seccd_welcart"><?php echo wp_kses_post( __( 'Security code <br /> (authentication assist)', 'usces' ) ); /* セキュリティコード */ ?></a></th>
				<td><label><input name="seccd" type="radio" id="seccd_welcart_1" value="on"<?php checked( $seccd, 'on' ); ?> /><span><?php esc_html_e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="seccd" type="radio" id="seccd_welcart_0" value="off"<?php checked( $seccd, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_seccd_welcart" class="explanation card_welcart"><td colspan="2"><?php esc_html_e( "Use 'Security code' of authentication assist matching. If you decide not to use, please also set 'Do not verify matching' on the e-SCOTT management screen.", 'usces' ); ?></td></tr>
			<tr class="card_welcart">
				<th><a class="explanation-label" id="label_ex_quickpay_welcart"><?php esc_html_e( 'Quick payment', 'usces' ); /* クイック決済 */ ?></a></th>
				<td><label><input name="quickpay" type="radio" class="quickpay_welcart" id="quickpay_welcart_1" value="on"<?php checked( $quickpay, 'on' ); ?> /><span><?php esc_html_e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="quickpay" type="radio" class="quickpay_welcart" id="quickpay_welcart_0" value="off"<?php checked( $quickpay, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_quickpay_welcart" class="explanation card_welcart"><td colspan="2"><?php esc_html_e( 'Members can pay with saved card. Card number will be registered in e-SCOTT Smart system.', 'usces' ); ?><?php esc_html_e( "When using automatic continuing charging (required WCEX DLSeller) or subscription (required WCEX Auto Delivery), please make 'Quick payment' of 'Use'.", 'usces' ); ?></td></tr>
			<tr class="card_chooseable_quickpay_welcart">
				<th><a class="explanation-label" id="label_ex_chooseable_quickpay_welcart"><?php esc_html_e( 'Register credit card', 'usces' ); /* クレジットカードの登録 */ ?></a></th>
				<td><label><input name="chooseable_quickpay" type="radio" class="chooseable_quickpay_welcart" id="chooseable_quickpay_welcart_1" value="on"<?php checked( $chooseable_quickpay, 'on' ); ?> /><span><?php esc_html_e( 'Member chooses', 'usces' ); ?></span></label><br />
					<label><input name="chooseable_quickpay" type="radio" class="chooseable_quickpay_welcart" id="chooseable_quickpay_welcart_0" value="off"<?php checked( $chooseable_quickpay, 'off' ); ?> /><span><?php esc_html_e( 'Always register', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_chooseable_quickpay_welcart" class="explanation card_chooseable_quickpay_welcart"><td colspan="2"><?php esc_html_e( "In case of 'Always register', 'Register and purchase credit card' will not be displayed when purchasing with credit card.", 'usces' ); ?></td></tr>
			<tr class="card_welcart">
				<th><a class="explanation-label" id="label_ex_operateid_welcart"><?php esc_html_e( 'Processing classification', 'usces' ); /* 処理区分 */ ?></a></th>
				<td><label><input name="operateid" type="radio" id="operateid_welcart_1" value="1Auth"<?php checked( $operateid, '1Auth' ); ?> /><span><?php esc_html_e( 'Credit', 'usces' ); /* 与信 */ ?></span></label><br />
					<label><input name="operateid" type="radio" id="operateid_welcart_2" value="1Gathering"<?php checked( $operateid, '1Gathering' ); ?> /><span><?php esc_html_e( 'Credit sales', 'usces' ); /* 与信売上計上 */ ?></span></label>
				</td>
			</tr>
			<tr id="ex_operateid_welcart" class="explanation card_welcart"><td colspan="2"><?php esc_html_e( "In case of 'Credit' setting, it need to change to 'Sales recorded' manually in later. In case of 'Credit sales recorded' setting, sales will be recorded at the time of purchase.", 'usces' ); ?></td></tr>
			<?php if ( defined( 'WCEX_DLSELLER' ) ) : ?>
			<tr class="card_welcart">
				<th><a class="explanation-label" id="label_ex_operateid_dlseller_welcart"><?php esc_html_e( 'Automatic Continuing Charging Processing Classification', 'usces' ); /* 自動継続課金処理区分 */ ?></a></th>
				<td><label><input name="operateid_dlseller" type="radio" id="operateid_dlseller_welcart_1" value="1Auth"<?php checked( $operateid_dlseller, '1Auth' ); ?> /><span><?php esc_html_e( 'Credit', 'usces' ); /* 与信 */ ?></span></label><br />
					<label><input name="operateid_dlseller" type="radio" id="operateid_dlseller_welcart_2" value="1Gathering"<?php checked( $operateid_dlseller, '1Gathering' ); ?> /><span><?php esc_html_e( 'Credit sales', 'usces' ); /* 与信売上計上 */ ?></span></label>
				</td>
			</tr>
			<tr id="ex_operateid_dlseller_welcart" class="explanation card_welcart"><td colspan="2"><?php esc_html_e( 'Processing classification when automatic continuing charging (required WCEX DLSeller).', 'usces' ); ?></td></tr>
			<tr class="card_welcart">
				<th><a class="explanation-label" id="label_ex_auto_settlement_mail_welcart"><?php esc_html_e( 'Automatic Continuing Charging Completion Mail', 'usces' ); /* 自動継続課金完了メール */ ?></a></th>
				<td><label><input name="auto_settlement_mail" type="radio" id="auto_settlement_mail_welcart_1" value="on"<?php checked( $auto_settlement_mail, 'on' ); ?> /><span><?php esc_html_e( 'Send', 'usces' ); ?></span></label><br />
					<label><input name="auto_settlement_mail" type="radio" id="auto_settlement_mail_welcart_0" value="off"<?php checked( $auto_settlement_mail, 'off' ); ?> /><span><?php esc_html_e( "Don't send", 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_auto_settlement_mail_welcart" class="explanation card_welcart"><td colspan="2"><?php esc_html_e( 'Send billing completion mail to the member on which automatic continuing charging processing (required WCEX DLSeller) is executed.', 'usces' ); ?></td></tr>
			<?php endif; ?>
			<tr class="card_howtopay_welcart">
				<th><a class="explanation-label" id="label_ex_howtopay_welcart"><?php esc_html_e( 'Number of payments', 'usces' ); /* 支払回数 */ ?></a></th>
				<td><label><input name="howtopay" type="radio" id="howtopay_welcart_1" value="1"<?php checked( $howtopay, '1' ); ?> /><span><?php esc_html_e( 'Lump-sum payment only', 'usces' ); /* 一括払いのみ */ ?></span></label><br />
					<label><input name="howtopay" type="radio" id="howtopay_welcart_2" value="2"<?php checked( $howtopay, '2' ); ?> /><span><?php esc_html_e( 'Activate installment payment', 'usces' ); /* 分割払いを有効にする */ ?></span></label><br />
					<label><input name="howtopay" type="radio" id="howtopay_welcart_3" value="3"<?php checked( $howtopay, '3' ); ?> /><span><?php esc_html_e( 'Activate installment payments and bonus payments', 'usces' ); /* 分割払いとボーナス払いを有効にする */ ?></span></label>
				</td>
			</tr>
			<tr id="ex_howtopay_welcart" class="explanation card_howtopay_welcart"><td colspan="2"><?php esc_html_e( 'It can be selected when using in non-passage type.', 'usces' ); ?></td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><?php esc_html_e( 'Online storage agency', 'usces' ); /* オンライン収納代行 */ ?></th>
				<td><label><input name="conv_activate" type="radio" class="conv_activate_welcart" id="conv_activate_welcart_1" value="on"<?php checked( $conv_activate, 'on' ); ?> /><span><?php esc_html_e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="conv_activate" type="radio" class="conv_activate_welcart" id="conv_activate_welcart_0" value="off"<?php checked( $conv_activate, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr class="conv_welcart">
				<th><?php esc_html_e( 'Payment due days', 'usces' ); /* 支払期限日数 */ ?></th>
				<td><input name="conv_limit" type="text" id="conv_limit" value="<?php echo esc_attr( $acting_opts['conv_limit'] ); ?>" class="small-text" /><?php esc_html_e( 'days', 'usces' ); ?></td>
			</tr>
			<tr class="conv_welcart">
				<th><a class="explanation-label" id="label_ex_conv_fee_welcart"><?php esc_html_e( 'Fee', 'usces' ); /* 手数料 */ ?></a></th>
				<td><span id="conv_fee_type_field" class="fee_type_field"><?php echo esc_html( $this->get_fee_name( $acting_opts['conv_fee_type'] ) ); ?></span><input type="button" class="button" value="<?php esc_attr_e( 'Detailed setting', 'usces' ); ?>" id="conv_fee_setting" /></td>
			</tr>
			<tr id="ex_conv_fee_welcart" class="explanation conv_welcart"><td colspan="2"><?php esc_html_e( 'Set the online storage agency commission and settlement upper limit. Leave it blank if you do not need it.', 'usces' ); ?></td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><?php esc_html_e( 'Postpay settlement (ATODENE)', 'usces' ); /* 後払い決済 */ ?></th>
				<td><label><input name="atodene_activate" type="radio" class="atodene_activate_welcart" id="atodene_activate_welcart_1" value="on"<?php checked( $atodene_activate, 'on' ); ?> /><span><?php esc_html_e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="atodene_activate" type="radio" class="atodene_activate_welcart" id="atodene_activate_welcart_0" value="off"<?php checked( $atodene_activate, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr class="atodene_welcart">
				<th><a class="explanation-label" id="label_ex_atodene_byitem_welcart"><?php esc_html_e( 'Possibility of each items', 'usces' ); /* 商品ごとの可否 */ ?></a></th>
				<td><label><input name="atodene_byitem" type="radio" id="atodene_byitem_welcart_1" value="on"<?php checked( $atodene_byitem, 'on' ); ?> /><span><?php esc_html_e( 'Enabled', 'usces' ); ?></span></label><br />
					<label><input name="atodene_byitem" type="radio" id="atodene_byitem_welcart_0" value="off"<?php checked( $atodene_byitem, 'off' ); ?> /><span><?php esc_html_e( 'Disabled', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_atodene_byitem_welcart" class="explanation atodene_welcart"><td colspan="2"><?php echo wp_kses_post( __( 'It is effective when setting possibility of each items. Invalid when not distinguished in particular.<br />If enabled, a selection field will be added to determine whether postpay settlement is possible on the product registration screen. If there is a product in the cart that can not be postpaid settlement, we exclude postpaid settlement from the payment method options.<br />In addition, availability data is added to the product CSV as a custom field (welcartpay_atodene).', 'usces' ) ); ?></td></tr>
			<tr class="atodene_welcart">
				<th><a class="explanation-label" id="label_ex_atodene_billing_method_welcart"><?php esc_html_e( 'Invoice delivery method', 'usces' ); /* 請求書送付方法 */ ?></a></th>
				<td><label><input name="atodene_billing_method" type="radio" id="atodene_billing_method_welcart_2" value="2"<?php checked( $atodene_billing_method, '2' ); ?> /><span><?php esc_html_e( 'Separate shipment', 'usces' ); /* 別送 */ ?></span></label><br />
					<label><input name="atodene_billing_method" type="radio" id="atodene_billing_method_welcart_3" value="3"<?php checked( $atodene_billing_method, '3' ); ?> /><span><?php esc_html_e( 'Include shipment', 'usces' ); /* 同梱 */ ?></span></label>
				</td>
			</tr>
			<tr id="ex_atodene_billing_method_welcart" class="explanation atodene_welcart"><td colspan="2"><?php esc_html_e( 'How to send invoices from ATODENE.', 'usces' ); ?></td></tr>
			<tr class="atodene_welcart">
				<th><a class="explanation-label" id="label_ex_atodene_fee_welcart"><?php esc_html_e( 'Fee', 'usces' ); ?></a></th>
				<td><span id="atodene_fee_type_field" class="fee_type_field"><?php echo esc_html( $this->get_fee_name( $acting_opts['atodene_fee_type'] ) ); ?></span><input type="button" class="button" value="<?php esc_attr_e( 'Detailed setting', 'usces' ); ?>" id="atodene_fee_setting" /></td>
			</tr>
			<tr id="ex_atodene_fee_welcart" class="explanation atodene_welcart"><td colspan="2"><?php esc_html_e( 'Set up postpaid settlement fee and maximum settlement amount. Leave it blank if you do not need it.', 'usces' ); ?></td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><?php esc_html_e( 'UnionPay', 'usces' ); /* 銀聯 */ ?></th>
				<td><label><input name="unionpay_activate" type="radio" class="unionpay_activate_welcart" id="unionpay_activate_welcart_1" value="on"<?php checked( $unionpay_activate, 'on' ); ?> /><span><?php esc_html_e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="unionpay_activate" type="radio" class="unionpay_activate_welcart" id="unionpay_activate_welcart_0" value="off"<?php checked( $unionpay_activate, 'off' ); ?> /><span><?php esc_html_e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr class="unionpay_welcart">
				<th><a class="explanation-label" id="label_ex_unionpay_key_aes_welcart"><?php esc_html_e( 'Encryption Key', 'usces' ); /* 銀聯暗号化キー */ ?></a></th>
				<td><input name="unionpay_key_aes" type="text" id="unionpay_key_aes_welcart" value="<?php echo esc_attr( $acting_opts['unionpay_key_aes'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_unionpay_key_aes_welcart" class="explanation unionpay_welcart"><td colspan="2"><?php esc_html_e( 'UnionPay encryption key (single-byte alphanumeric characters only) issued by e-SCOTT.', 'usces' ); ?></td></tr>
			<tr class="unionpay_welcart">
				<th><a class="explanation-label" id="label_ex_unionpay_key_iv_welcart"><?php esc_html_e( 'Initialization Vector', 'usces' ); /* 銀聯初期化ベクトル */ ?></a></th>
				<td><input name="unionpay_key_iv" type="text" id="unionpay_key_iv_welcart" value="<?php echo esc_attr( $acting_opts['unionpay_key_iv'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_unionpay_key_iv_welcart" class="explanation unionpay_welcart"><td colspan="2"><?php esc_html_e( 'UnionPay initialization vector (single-byte alphanumeric characters only) issued by e-SCOTT.', 'usces' ); ?></td></tr>
			<tr class="unionpay_welcart">
				<th><a class="explanation-label" id="label_ex_unionpay_pagelanguage_welcart"><?php esc_html_e( 'Page Language', 'usces' ); /* 三者間ページ言語 */ ?></a></th>
				<td><select name="unionpay_pagelanguage" id="unionpay_pagelanguage_welcart">
						<option value=""><?php esc_html_e( 'Not set', 'usces' ); ?></option>
						<option value="zh_CN"<?php selected( $acting_opts['unionpay_pagelanguage'], 'zh_CN' ); ?>><?php esc_html_e( 'Chinese (Simplified)', 'usces' ); ?></option>
						<option value="zh_TW"<?php selected( $acting_opts['unionpay_pagelanguage'], 'zh_TW' ); ?>><?php esc_html_e( 'Chinese (Traditional)', 'usces' ); ?></option>
						<option value="en_US"<?php selected( $acting_opts['unionpay_pagelanguage'], 'en_US' ); ?>><?php esc_html_e( 'English', 'usces' ); ?></option>
						<option value="ja_JP"<?php selected( $acting_opts['unionpay_pagelanguage'], 'ja_JP' ); ?>><?php esc_html_e( 'Japanese', 'usces' ); ?></option>
						<option value="ko_KR"<?php selected( $acting_opts['unionpay_pagelanguage'], 'ko_KR' ); ?>><?php esc_html_e( 'Korean', 'usces' ); ?></option>
					</select>
				</td>
			</tr>
			<tr id="ex_unionpay_pagelanguage_welcart" class="explanation unionpay_welcart"><td colspan="2"><?php esc_html_e( 'Specifies the language of the SecurePay payment page. If not set, the page is displayed according to the language of the end user\'s browser. For mobile devices, only Chinese and English are displayed.', 'usces' ); ?></td></tr>
		</table>
		<input type="hidden" name="acting" value="welcart" />
		<input type="hidden" name="conv_fee_type" id="conv_fee_type" value="<?php echo esc_attr( $acting_opts['conv_fee_type'] ); ?>" />
		<input type="hidden" name="conv_fee" id="conv_fee" value="<?php echo esc_attr( $acting_opts['conv_fee'] ); ?>" />
		<input type="hidden" name="conv_fee_limit_amount_fix" id="conv_fee_limit_amount_fix" value="<?php echo esc_attr( $acting_opts['conv_fee_limit_amount'] ); ?>" />
		<input type="hidden" name="conv_fee_first_amount" id="conv_fee_first_amount" value="<?php echo esc_attr( $acting_opts['conv_fee_first_amount'] ); ?>" />
		<input type="hidden" name="conv_fee_first_fee" id="conv_fee_first_fee" value="<?php echo esc_attr( $acting_opts['conv_fee_first_fee'] ); ?>" />
		<input type="hidden" name="conv_fee_limit_amount_change" id="conv_fee_limit_amount_change" value="<?php echo esc_attr( $acting_opts['conv_fee_limit_amount'] ); ?>" />
		<input type="hidden" name="conv_fee_amounts" id="conv_fee_amounts" value="<?php echo esc_attr( implode( '|', $acting_opts['conv_fee_amounts'] ) ); ?>" />
		<input type="hidden" name="conv_fee_fees" id="conv_fee_fees" value="<?php echo esc_attr( implode( '|', $acting_opts['conv_fee_fees'] ) ); ?>" />
		<input type="hidden" name="conv_fee_end_fee" id="conv_fee_end_fee" value="<?php echo esc_attr( $acting_opts['conv_fee_end_fee'] ); ?>" />
		<input type="hidden" name="atodene_fee_type" id="atodene_fee_type" value="<?php echo esc_attr( $acting_opts['atodene_fee_type'] ); ?>" />
		<input type="hidden" name="atodene_fee" id="atodene_fee" value="<?php echo esc_attr( $acting_opts['atodene_fee'] ); ?>" />
		<input type="hidden" name="atodene_fee_limit_amount_fix" id="atodene_fee_limit_amount_fix" value="<?php echo esc_attr( $acting_opts['atodene_fee_limit_amount'] ); ?>" />
		<input type="hidden" name="atodene_fee_first_amount" id="atodene_fee_first_amount" value="<?php echo esc_attr( $acting_opts['atodene_fee_first_amount'] ); ?>" />
		<input type="hidden" name="atodene_fee_first_fee" id="atodene_fee_first_fee" value="<?php echo esc_attr( $acting_opts['atodene_fee_first_fee'] ); ?>" />
		<input type="hidden" name="atodene_fee_limit_amount_change" id="atodene_fee_limit_amount_change" value="<?php echo esc_attr( $acting_opts['atodene_fee_limit_amount'] ); ?>" />
		<input type="hidden" name="atodene_fee_amounts" id="atodene_fee_amounts" value="<?php echo esc_attr( implode( '|', $acting_opts['atodene_fee_amounts'] ) ); ?>" />
		<input type="hidden" name="atodene_fee_fees" id="atodene_fee_fees" value="<?php echo esc_attr( implode( '|', $acting_opts['atodene_fee_fees'] ) ); ?>" />
		<input type="hidden" name="atodene_fee_end_fee" id="atodene_fee_end_fee" value="<?php echo esc_attr( $acting_opts['atodene_fee_end_fee'] ); ?>" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Update WelcartPay settings', 'usces' ); ?>" />
			<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong>WelcartPay based on e-SCOTT</strong><br />
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank"><?php esc_html_e( 'Details of e-SCOTT Smart is here >>', 'usces' ); ?></a></p>
		<p><?php esc_html_e( "'Non-passage type' is a settlement system that completes with shop site only, without transitioning to the page of the settlement company.", 'usces' ); ?><br />
			<?php esc_html_e( 'Stylish with unified design is possible. However, because we will handle the card number, dedicated SSL is required.', 'usces' ); ?><br />
			<?php esc_html_e( "'External link type' is a settlement system that moves to the page of the settlement company and inputs card information.", 'usces' ); ?><br />
			<?php esc_html_e( 'In both types, the entered card number will be sent to the e-SCOTT Smart system, so it will not be saved in Welcart.', 'usces' ); ?></p>
		<p><?php esc_html_e( "'WCEX DL Seller' is necessary when using 'automatic continuing charging'.", 'usces' ); ?><br />
			<?php esc_html_e( "'WCEX Auto Delivery' is necessary when using 'subscription'.", 'usces' ); ?></p>
		<p><?php esc_html_e( 'In addition, in the production environment, it is SSL communication with only an authorized SSL certificate, so it is necessary to be careful.', 'usces' ); ?></p>
		<p><?php esc_html_e( 'The Welcart member account used in the test environment may not be available in the production environment.', 'usces' ); ?><br />
			<?php esc_html_e( 'Please make another member registration in the test environment and production environment, or delete the member used in the test environment once and register again in the production environment.', 'usces' ); ?></p>
		<p><strong><?php esc_html_e( '[About 3D Secure]', 'usces' ); ?></strong><br />
			<?php esc_html_e( 'If you do not use 3D Secure (do not check the "Use" box), the merchant is responsible for payment due to fraudulent use of the credit card.', 'usces' ); ?><br />
			<?php esc_html_e( 'Even if we have already paid the merchant an amount equivalent to the sales proceeds, the merchant must return the amount to us upon request for a chargeback (return of sales proceeds) from the credit card company.', 'usces' ); ?><br />
			<?php esc_html_e( 'Please note that chargebacks may occur even if you select "Use". Please understand this in advance.', 'usces' ); ?><br />
			<?php esc_html_e( 'If you have applied for the EMV 3D Secure service, please be sure to select "Use".', 'usces' ); ?></p>
		<p><strong><?php esc_html_e( '[Note on chargebacks]', 'usces' ); ?></strong><br />
			<?php esc_html_e( '* Even if sales approval has been obtained (when the authorization result is OK), chargebacks will still be incurred.', 'usces' ); ?><br />
			<?php esc_html_e( '* If chargebacks occur, there is no compensation or reimbursement by us or the credit card companies. The merchant is responsible for all charges.', 'usces' ); ?><br />
			<?php esc_html_e( "* Chargebacks will be incurred regardless of whether the merchant's intentional or negligent conduct is involved.", 'usces' ); ?><br />
			<?php esc_html_e( 'Please be sure to confirm the following before starting to use the service.', 'usces' ); ?><br />
			<a href="https://www.sonypaymentservices.jp/consider/creditcard/chargeback.html" target="_blank"><?php esc_html_e( 'About chargebacks', 'usces' ); ?></a></p>
	</div>
	</div><!--uscestabs_welcart-->

	<div id="welcartpay_fee_dialog" class="cod_dialog">
		<fieldset>
		<table id="welcartpay_fee_type_table" class="cod_type_table">
			<tr>
				<th><?php esc_html_e( 'Type of the fee', 'usces' ); ?></th>
				<td class="radio"><input name="fee_type" type="radio" id="fee_type_fix" class="fee_type" value="fix" /></td><td><label for="fee_type_fix"><?php esc_html_e( 'Fixation', 'usces' ); ?></label></td>
				<td class="radio"><input name="fee_type" type="radio" id="fee_type_change" class="fee_type" value="change" /></td><td><label for="fee_type_change"><?php esc_html_e( 'Variable', 'usces' ); ?></label></td>
			</tr>
		</table>
		<table id="welcartpay_fee_fix_table" class="cod_fix_table">
			<tr>
				<th><?php esc_html_e( 'Fee', 'usces' ); ?></th>
				<td><input name="fee" type="text" id="fee_fix" class="short_str num" /><?php usces_crcode(); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Upper limit', 'usces' ); ?></th>
				<td><input name="fee_limit_amount_fix" type="text" id="fee_limit_amount_fix" class="short_str num" /><?php usces_crcode(); ?></td>
			</tr>
		</table>
		<div id="welcartpay_fee_change_table" class="cod_change_table">
		<input type="button" class="button" id="fee_add_row" value="<?php esc_attr_e( 'Add row', 'usces' ); ?>" />
		<input type="button" class="button" id="fee_del_row" value="<?php esc_attr_e( 'Delete row', 'usces' ); ?>" />
		<table>
			<thead>
				<tr>
					<th colspan="3"><?php esc_html_e( 'A purchase amount', 'usces' ); ?>(<?php usces_crcode(); ?>)</th>
					<th><?php esc_html_e( 'Fee', 'usces' ); ?>(<?php usces_crcode(); ?>)</th>
				</tr>
				<tr>
					<td class="cod_f">0</td>
					<td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td>
					<td class="cod_e"><input name="fee_first_amount" id="fee_first_amount" type="text" class="short_str num" /></td>
					<td class="cod_cod"><input name="fee_first_fee" id="fee_first_fee" type="text" class="short_str num" /></td>
				</tr>
			</thead>
			<tbody id="fee_change_field"></tbody>
			<tfoot>
				<tr>
					<td class="cod_f"><span id="end_amount"></span></td>
					<td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td>
					<td class="cod_e"><input name="fee_limit_amount_change" type="text" id="fee_limit_amount_change" class="short_str num" /></td>
					<td class="cod_cod"><input name="fee_end_fee" type="text" id="fee_end_fee" class="short_str num" /></td>
				</tr>
			</tfoot>
		</table>
		</div>
		</fieldset>
		<input type="hidden" id="welcartpay_fee_mode">
	</div><!--welcartpay_fee_dialog-->
			<?php
		endif;
	}

	/**
	 * ゼウス定期購入メッセージ
	 * wcad_filter_admin_notices
	 *
	 * @param  string $msg Admin message.
	 * @return string
	 */
	public function admin_notices_autodelivery( $msg ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) &&
			( isset( $acting_opts['card_activate'] ) && ( 'on' === $acting_opts['card_activate'] || 'link' === $acting_opts['card_activate'] || 'token' === $acting_opts['card_activate'] ) ) &&
			( isset( $acting_opts['quickpay'] ) && 'on' === $acting_opts['quickpay'] ) ) {
			$msg = '';
		} else {
			$zeus_opts = $usces->options['acting_settings']['zeus'];
			$p_flag    = ( ( isset( $zeus_opts['activate'] ) && 'on' === $zeus_opts['activate'] ) && ( isset( $zeus_opts['card_activate'] ) && 'on' === $zeus_opts['card_activate'] ) ) ? true : false;
			$batch     = ( isset( $zeus_opts['batch'] ) ) ? $zeus_opts['batch'] : 'off';
			if ( ! $p_flag || 'off' === $batch ) {
				$msg .= '
				<div class="error">
				<p>' . __( "In 'credit settlement Settings', please set to 'use' the quickpay of WelcartPay.", 'usces' ) . '</p>
				</div>';
			}
		}
		return $msg;
	}

	/**
	 * 入金通知処理および、三者間決済画面からのリダイレクト
	 * usces_after_cart_instant
	 */
	public function acting_transaction() {
		global $usces;

		/* 入金通知 */
		if ( isset( $_REQUEST['MerchantFree1'] ) && isset( $_REQUEST['MerchantId'] ) && isset( $_REQUEST['TransactionId'] ) && isset( $_REQUEST['RecvNum'] ) && isset( $_REQUEST['NyukinDate'] ) &&
			( isset( $_REQUEST['MerchantFree2'] ) && 'acting_welcart_conv' === wp_unslash( $_REQUEST['MerchantFree2'] ) ) ) {
			$acting_opts = $this->get_acting_settings();
			if ( $acting_opts['merchant_id'] === wp_unslash( $_REQUEST['MerchantId'] ) ) {
				$response_data = wp_unslash( $_REQUEST );
				$order_id      = usces_get_order_id_by_trans_id( $response_data['MerchantFree1'] );
				if ( ! empty( $order_id ) ) {

					/* オーダーステータス変更 */
					usces_change_order_receipt( $order_id, 'receipted' );
					/* ポイント付与 */
					usces_action_acting_getpoint( $order_id );

					$response_data['OperateId'] = 'receipted';
					$order_meta                 = usces_unserialize( $usces->get_order_meta_value( $response_data['MerchantFree2'], $order_id ) );
					$meta_value                 = array_merge( $order_meta, $response_data );
					$usces->set_order_meta_value( $response_data['MerchantFree2'], usces_serialize( $meta_value ), $order_id );
					$this->save_acting_history_log( $response_data, $order_id . '_' . $response_data['MerchantFree1'] );
					usces_log( '[WelcartPay] conv receipted : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				} else {
					usces_log( '[WelcartPay] conv receipted order_id error : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
			}
			header( 'HTTP/1.0 200 OK' );
			die();

		} elseif ( isset( $_REQUEST['EncryptValue'] ) ) {
			$acting_opts = $this->get_acting_settings();

			/* 銀聯決済 */
			if ( isset( $_REQUEST['acting'] ) && $this->acting_unionpay === wp_unslash( $_REQUEST['acting'] ) && $this->is_validity_acting( 'unionpay' ) ) {
				$encryptvalue = openssl_decrypt( $_REQUEST['EncryptValue'], 'aes-128-cbc', $acting_opts['unionpay_key_aes'], false, $acting_opts['unionpay_key_iv'] );
				if ( $encryptvalue ) {
					parse_str( $encryptvalue, $response_data );
					if ( isset( $response_data['OperateId'] ) && isset( $response_data['ResponseCd'] ) && isset( $response_data['MerchantFree1'] ) &&
						( isset( $response_data['MerchantFree2'] ) && 'acting_welcart_unionpay' === $response_data['MerchantFree2'] ) ) {
						if ( 'OK' === $response_data['ResponseCd'] ) {
							$order_data = usces_restore_order_acting_data( $response_data['MerchantFree1'] );
							if ( ! empty( $order_data ) ) {
								$response_data['acting'] = $this->acting_unionpay;
								$res                     = $usces->order_processing( $response_data );
								if ( 'ordercompletion' === $res ) {
									$_nonce = ( isset( $order_data['_nonce'] ) ) ? $order_data['_nonce'] : wp_create_nonce( $this->acting_flg_unionpay );
									wp_redirect(
										add_query_arg(
											array(
												'acting' => $this->acting_unionpay,
												'acting_return' => 1,
												'result' => 1,
												'_nonce' => $_nonce,
											),
											USCES_CART_URL
										)
									);
								} else {
									$logdata = array_merge( $usces_entries['order'], $response_data );
									$log     = array(
										'acting' => $this->acting_unionpay,
										'key'    => $response_data['MerchantFree1'],
										'result' => 'ORDER DATA REGISTERED ERROR',
										'data'   => $logdata,
									);
									usces_save_order_acting_error( $log );
									wp_redirect(
										add_query_arg(
											array(
												'acting' => $this->acting_unionpay,
												'acting_return' => 0,
												'result' => 0,
											),
											USCES_CART_URL
										)
									);
								}
							} else {
								$log = array(
									'acting' => $this->acting_unionpay,
									'key'    => $response_data['MerchantFree1'],
									'result' => 'ORDER DATA RESTORE ERROR',
									'data'   => $response_data,
								);
								usces_save_order_acting_error( $log );
								wp_redirect(
									add_query_arg(
										array(
											'acting' => $this->acting_unionpay,
											'acting_return' => 0,
											'result' => 0,
										),
										USCES_CART_URL
									)
								);
							}
						} elseif ( 'KI0' === $response_data['ResponseCd'] ) {
							/* Return to Merchant */
							wp_redirect(
								add_query_arg(
									array(
										'confirm' => 1,
									),
									USCES_CART_URL
								)
							);
						} else {
							$responsecd = explode( '|', $response_data['ResponseCd'] );
							foreach ( (array) $responsecd as $cd ) {
								$response_data[ $cd ] = $this->response_message( $cd );
							}
							$log = array(
								'acting' => $this->acting_unionpay,
								'key'    => $response_data['MerchantFree1'],
								'result' => $response_data['ResponseCd'],
								'data'   => $response_data,
							);
							usces_save_order_acting_error( $log );
							wp_redirect(
								add_query_arg(
									array(
										'acting'        => $this->acting_unionpay,
										'acting_return' => 0,
										'result'        => 0,
									),
									USCES_CART_URL
								)
							);
						}
					}
					exit();
				}

				/* 外部リンク（三者間画面）決済 */
			} else {
				$encryptvalue = openssl_decrypt( $_REQUEST['EncryptValue'], 'aes-128-cbc', $acting_opts['key_aes'], false, $acting_opts['key_iv'] );
				if ( $encryptvalue ) {
					parse_str( $encryptvalue, $response_data );
					if ( isset( $response_data['OperateId'] ) && isset( $response_data['ResponseCd'] ) && ( isset( $response_data['MerchantId'] ) && $acting_opts['merchant_id'] === $response_data['MerchantId'] ) ) {
						$cancel = array( 'P51', 'P52', 'P55', 'P56', 'P62', 'P63', 'P64', 'P65', 'P69', 'P70' );
						if ( isset( $response_data['MerchantFree1'] ) && ( isset( $response_data['MerchantFree2'] ) && 'acting_welcart_card' === $response_data['MerchantFree2'] ) ) {
							if ( 'OK' === $response_data['ResponseCd'] ) {
								/* 会員登録・会員変更 */
								if ( '4MemAdd' === $response_data['OperateId'] || '4MemChg' === $response_data['OperateId'] ) {
									$member = $usces->get_member();
									$usces->set_member_meta_value( 'wcpay_member_id', $response_data['KaiinId'], $member['ID'] );
									$usces->set_member_meta_value( 'wcpay_member_passwd', $response_data['KaiinPass'], $member['ID'] );

									$usces_entries = $usces->cart->get_entry();
									$cart          = $usces->cart->get_cart();

									if ( usces_have_continue_charge() ) {
										$chargingday = $usces->getItemChargingDay( $cart[0]['post_id'] );
										if ( 99 === (int) $chargingday ) { /* 受注日課金 */
											$operateid = $acting_opts['operateid'];
										} else {
											$operateid = '1Auth';
										}
									} else {
										$operateid = $acting_opts['operateid'];
									}
									$operateid = apply_filters( 'usces_filter_escott_operateid', $operateid, $cart, $usces_entries['order']['total_full_price'] );

									$home_url    = str_replace( 'http://', 'https://', home_url( '/' ) );
									$redirecturl = $home_url . '?page_id=' . USCES_CART_NUMBER;

									$data_list                    = array();
									$data_list['OperateId']       = $operateid;
									$data_list['MerchantPass']    = $acting_opts['merchant_pass'];
									$data_list['TransactionDate'] = $response_data['TransactionDate'];
									$data_list['MerchantFree1']   = $response_data['MerchantFree1'];
									$data_list['MerchantFree2']   = $response_data['MerchantFree2'];
									$data_list['MerchantFree3']   = $this->merchantfree3;
									$data_list['TenantId']        = $acting_opts['tenant_id'];
									$data_list['KaiinId']         = $response_data['KaiinId'];
									$data_list['KaiinPass']       = $response_data['KaiinPass'];
									$data_list['PayType']         = '01';
									$data_list['Amount']          = $usces_entries['order']['total_full_price'];
									$data_list['ProcNo']          = '0000000';
									if ( isset( $response_data['EncodeXId3D'] ) ) {
										$data_list['EncodeXId3D'] = $response_data['EncodeXId3D'];
									}
									if ( isset( $response_data['TransactionId3D'] ) ) {
										$data_list['TransactionId3D'] = $response_data['TransactionId3D'];
									}
									if ( isset( $response_data['MessageVersionNo3D'] ) ) {
										$data_list['MessageVersionNo3D'] = $response_data['MessageVersionNo3D'];
									}
									if ( isset( $response_data['TransactionStatus3D'] ) ) {
										$data_list['TransactionStatus3D'] = $response_data['TransactionStatus3D'];
									}
									if ( isset( $response_data['CAVVAlgorithm3D'] ) ) {
										$data_list['CAVVAlgorithm3D'] = $response_data['CAVVAlgorithm3D'];
									}
									if ( isset( $response_data['ECI3D'] ) ) {
										$data_list['ECI3D'] = $response_data['ECI3D'];
									}
									if ( isset( $response_data['CAVV3D'] ) ) {
										$data_list['CAVV3D'] = $response_data['CAVV3D'];
									}
									if ( isset( $response_data['SecureResultCode'] ) ) {
										$data_list['SecureResultCode'] = $response_data['SecureResultCode'];
									}
									if ( isset( $response_data['DSTransactionId'] ) ) {
										$data_list['DSTransactionId'] = $response_data['DSTransactionId'];
									}
									if ( isset( $response_data['ThreeDSServerTransactionId'] ) ) {
										$data_list['ThreeDSServerTransactionId'] = $response_data['ThreeDSServerTransactionId'];
									}
									$data_list['RedirectUrl'] = $redirecturl;
									$data_query               = http_build_query( $data_list );
									$encryptvalue             = openssl_encrypt( $data_query, 'aes-128-cbc', $acting_opts['key_aes'], false, $acting_opts['key_iv'] );
									wp_redirect(
										add_query_arg(
											array(
												'MerchantId'   => $acting_opts['merchant_id'],
												'EncryptValue' => urlencode( $encryptvalue ),
											),
											$acting_opts['send_url_link']
										)
									);
									exit();

									/* 決済 */
								} else {
									$order_data = usces_restore_order_acting_data( $response_data['MerchantFree1'] );
									if ( ! empty( $order_data ) ) {
										$res = $usces->order_processing( $response_data );
										if ( 'ordercompletion' === $res ) {
											$_nonce = ( isset( $order_data['_nonce'] ) ) ? $order_data['_nonce'] : wp_create_nonce( $this->acting_flg_card );
											wp_redirect(
												add_query_arg(
													array(
														'acting'        => $this->acting_card,
														'acting_return' => 1,
														'result'        => 1,
														'_nonce'        => $_nonce,
													),
													USCES_CART_URL
												)
											);
										} else {
											$log = array(
												'acting' => $this->acting_card,
												'key'    => $response_data['MerchantFree1'],
												'result' => 'ORDER DATA REGISTERED ERROR',
												'data'   => $response_data,
											);
											usces_save_order_acting_error( $log );
											wp_redirect(
												add_query_arg(
													array(
														'acting'        => $this->acting_card,
														'acting_return' => 0,
														'result'        => 0,
													),
													USCES_CART_URL
												)
											);
										}
									} else {
										$log = array(
											'acting' => $this->acting_card,
											'key'    => $response_data['MerchantFree1'],
											'result' => 'ORDER DATA RESTORE ERROR',
											'data'   => $response_data,
										);
										usces_save_order_acting_error( $log );
										wp_redirect(
											add_query_arg(
												array(
													'acting'        => $this->acting_card,
													'acting_return' => 0,
													'result'        => 0,
												),
												USCES_CART_URL
											)
										);
									}
								}
							} elseif ( in_array( $response_data['ResponseCd'], $cancel, true ) ) {
								wp_redirect(
									add_query_arg(
										array(
											'acting'  => $this->acting_card,
											'confirm' => 1,
										),
										USCES_CART_URL
									)
								);
							} else {
								$responsecd = explode( '|', $response_data['ResponseCd'] );
								foreach ( (array) $responsecd as $cd ) {
									$response_data[ $cd ] = $this->response_message( $cd );
								}
								$log = array(
									'acting' => $this->acting_card,
									'key'    => $response_data['MerchantFree1'],
									'result' => $response_data['ResponseCd'],
									'data'   => $response_data,
								);
								usces_save_order_acting_error( $log );
								wp_redirect(
									add_query_arg(
										array(
											'acting'        => $this->acting_card,
											'acting_return' => 0,
											'result'        => 0,
										),
										USCES_CART_URL
									)
								);
							}
						} else {
							/* マイページからの会員登録・会員変更 */
							if ( '4MemAdd' === $response_data['OperateId'] || '4MemChg' === $response_data['OperateId'] ) {
								if ( 'OK' === $response_data['ResponseCd'] ) {
									$member = $usces->get_member();
									$usces->set_member_meta_value( 'wcpay_member_id', $response_data['KaiinId'], $member['ID'] );
									$usces->set_member_meta_value( 'wcpay_member_passwd', $response_data['KaiinPass'], $member['ID'] );

								} elseif ( in_array( $response_data['ResponseCd'], $cancel ) ) { // phpcs:ignore

								} else {
									usces_log( '[WelcartPay] 4MemChg NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
								}
								wp_redirect( USCES_MEMBER_URL );
							}
						}
						exit();
					}
				}
			}
		}
	}

	/**
	 * 管理画面決済処理
	 * usces_action_admin_ajax
	 */
	public function admin_ajax() {
		global $usces;

		$mode = filter_input( INPUT_POST, 'mode', FILTER_SANITIZE_STRING );
		$data = array();

		switch ( $mode ) {
			/* 取引参照 */
			case 'get_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					$data['result'] = '';
					wp_send_json( $data );
					break;
				}
				$res      = '';
				$log_data = array();
				if ( '9999999999' === $trans_id ) {
					$member_id       = filter_input( INPUT_POST, 'member_id' );
					$response_member = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
					if ( 'OK' === $response_member['ResponseCd'] ) {
						$order_data       = $usces->get_order_data( $order_id, 'direct' );
						$total_full_price = $order_data['order_item_total_price'] - $order_data['order_usedpoint'] + $order_data['order_discount'] + $order_data['order_shipping_charge'] + $order_data['order_cod_fee'] + $order_data['order_tax'];

						$res .= '<div class="welcart-settlement-admin card-new">' . __( 'New', 'usces' ) . '</div>';
						$res .= '<table class="welcart-settlement-admin-table">';
						$res .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
						$res .= '<td><input type="text" id="amount_change" value="' . $total_full_price . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $total_full_price . '" /></td>';
						$res .= '</tr>';
						$res .= '</table>';
						$res .= '<div class="welcart-settlement-admin-button">';
						$res .= '<input id="auth-settlement" type="button" class="button" value="' . __( 'Credit', 'usces' ) . '" />'; /* 与信 */
						$res .= '<input id="gathering-settlement" type="button" class="button" value="' . __( 'Credit sales', 'usces' ) . '" />'; /* 与信売上計上 */
						$res .= '</div>';
					} else {
						$res .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>';
						$res .= '<div class="welcart-settlement-admin-error">';
						$res .= '<div><span class="message">' . __( 'Credit card information not registered', 'usces' ) . '</span></div>'; /* カード情報未登録 */
						$res .= '</div>';
					}
					$data['status'] = 'OK';
					$data['result'] = $res;
				} else {
					if ( 1 === (int) $order_num ) {
						$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );
					} else {
						$log_data    = $this->get_acting_log( $order_id, $order_id . '_' . $trans_id );
						$acting_data = usces_unserialize( $log_data[0]['log'] );
					}
					$operateid                     = $this->get_acting_operateid( $order_id . '_' . $trans_id );
					$acting_opts                   = $this->get_acting_settings();
					$transaction_date              = $this->get_transaction_date();
					$param_list                    = array();
					$params                        = array();
					$param_list['MerchantId']      = $acting_opts['merchant_id'];
					$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
					$param_list['TransactionDate'] = $transaction_date;
					$param_list['MerchantFree1']   = $trans_id;
					$param_list['MerchantFree2']   = 'acting_welcart_card';
					$param_list['MerchantFree3']   = $this->merchantfree3;
					$param_list['TenantId']        = $acting_opts['tenant_id'];
					$params['send_url']            = $acting_opts['send_url'];
					$params['param_list']          = array_merge(
						$param_list,
						array(
							'OperateId'   => '1Search',
							'ProcessId'   => $acting_data['ProcessId'],
							'ProcessPass' => $acting_data['ProcessPass'],
						)
					);
					$response_data                 = $this->connection( $params );
					if ( 'OK' === $response_data['ResponseCd'] ) {
						$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
						if ( isset( $latest_log['OperateId'] ) ) {
							$class       = ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 1 ) );
							$status_name = $this->get_operate_name( $latest_log['OperateId'] );
							$res        .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
							$res        .= '<table class="welcart-settlement-admin-table">';
							if ( isset( $response_data['Amount'] ) ) {
								$res .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
								$res .= '<td><input type="text" id="amount_change" value="' . $response_data['Amount'] . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $response_data['Amount'] . '" /></td>';
								$res .= '</tr>';
							}
							if ( isset( $response_data['SalesDate'] ) ) {
								$res .= '<tr><th>' . __( 'Recorded sales date', 'usces' ) . '</th><td>' . $response_data['SalesDate'] . '</td></tr>';
							}
							$res .= '</table>';
							$res .= '<div class="welcart-settlement-admin-button">';
							if ( '1Delete' === $latest_log['OperateId'] ) {
								$res .= '<input id="reauth-settlement" type="button" class="button" value="' . __( 'Re-authorization', 'usces' ) . '" />'; /* 再オーソリ */
							} else {
								if ( '1Auth' === $operateid && '1Capture' !== $latest_log['OperateId'] && '1Gathering' !== $latest_log['OperateId'] ) {
									$res .= '<input id="reauth-settlement" type="button" class="button" value="' . __( 'Re-authorization', 'usces' ) . '" />'; /* 再オーソリ */
									$res .= '<input id="capture-settlement" type="button" class="button" value="' . __( 'Sales recorded', 'usces' ) . '" />'; /* 売上計上 */
								}
								if ( '1Delete' !== $latest_log['OperateId'] ) {
									$res .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
								}
								if ( '1Change' !== $latest_log['OperateId'] ) {
									$res .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change spending amount', 'usces' ) . '" />'; /* 利用額変更 */
								}
							}
							$res .= '</div>';
						}
					} else {
						if ( 'K12' === $response_data['ResponseCd'] ) {
							$res .= '<div class="welcart-settlement-admin card-delete">' . __( 'Expired', 'usces' ) . '</div>';
							$res .= '<div class="welcart-settlement-admin-expired">';
							$res .= '<div><span class="code">K12</span> : <span class="message">' . __( 'Handling expired.', 'usces' ) . '</span></div>';
							$res .= '</div>';
						} else {
							$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>';
							$res       .= '<div class="welcart-settlement-admin-error">';
							$responsecd = explode( '|', $response_data['ResponseCd'] );
							foreach ( (array) $responsecd as $cd ) {
								$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
							}
							$res .= '</div>';
							usces_log( '[WelcartPay] 1Search connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
						}
					}
					$res           .= $this->settlement_history( $order_id . '_' . $trans_id );
					$data['status'] = $response_data['ResponseCd'];
					$data['result'] = $res;
				}
				wp_send_json( $data );
				break;

			/* 売上計上 */
			case 'capture_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				$amount    = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res           = '';
				$acting_status = '';
				$log_data      = array();
				if ( 1 === (int) $order_num ) {
					$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );
				} else {
					$log_data    = $this->get_acting_log( $order_id, $order_id . '_' . $trans_id );
					$acting_data = usces_unserialize( $log_data[0]['log'] );
				}
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_card';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$member_id                     = filter_input( INPUT_POST, 'member_id' );
				$response_member               = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$param_list['KaiinId']   = $response_member['KaiinId'];
					$param_list['KaiinPass'] = $response_member['KaiinPass'];
				}
				$params['send_url']   = $acting_opts['send_url'];
				$params['param_list'] = array_merge(
					$param_list,
					array(
						'OperateId'   => '1Capture',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
						'SalesDate'   => $transaction_date,
					)
				);
				$response_data        = $this->connection( $params );
				if ( 'K81' === $response_data['ResponseCd'] ) {
					$param_list['KaiinId']   = '';
					$param_list['KaiinPass'] = '';
					$params['param_list']    = array_merge(
						$param_list,
						array(
							'OperateId'   => '1Capture',
							'ProcessId'   => $acting_data['ProcessId'],
							'ProcessPass' => $acting_data['ProcessPass'],
							'SalesDate'   => $transaction_date,
						)
					);
					$response_data           = $this->connection( $params );
				}
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$class       = ' card-' . mb_strtolower( substr( $response_data['OperateId'], 1 ) );
					$status_name = $this->get_operate_name( $response_data['OperateId'] );
					$res        .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
					$res        .= '<table class="welcart-settlement-admin-table">';
					$res        .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
					$res        .= '<td><input type="text" id="amount_change" value="' . $amount . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $amount . '" /></td>';
					$res        .= '</tr>';
					if ( isset( $response_data['SalesDate'] ) ) {
						$res .= '<tr><th>' . __( 'Recorded sales date', 'usces' ) . '</th><td>' . $response_data['SalesDate'] . '</td></tr>';
					}
					$res          .= '</table>';
					$res          .= '<div class="welcart-settlement-admin-button">';
					$res          .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
					$res          .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change spending amount', 'usces' ) . '" />'; /* 利用額変更 */
					$res          .= '</div>';
					$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
				} else {
					$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>';
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 1Capture connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_capture_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status']        = $response_data['ResponseCd'];
				$data['acting_status'] = $acting_status;
				$data['result']        = $res;
				$this->save_admin_log( $order_id, $order_id . '_' . $trans_id );
				wp_send_json( $data );
				break;

			/* 取消/返品 */
			case 'delete_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				$amount    = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res           = '';
				$acting_status = '';
				$log_data      = array();
				if ( 1 === (int) $order_num ) {
					$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );
				} else {
					$log_data    = $this->get_acting_log( $order_id, $order_id . '_' . $trans_id );
					$acting_data = usces_unserialize( $log_data[0]['log'] );
				}
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_card';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$member_id                     = filter_input( INPUT_POST, 'member_id' );
				$response_member               = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$param_list['KaiinId']   = $response_member['KaiinId'];
					$param_list['KaiinPass'] = $response_member['KaiinPass'];
				}
				$params['send_url']   = $acting_opts['send_url'];
				$params['param_list'] = array_merge(
					$param_list,
					array(
						'OperateId'   => '1Delete',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
					)
				);
				$response_data        = $this->connection( $params );
				if ( 'K81' === $response_data['ResponseCd'] ) {
					$param_list['KaiinId']   = '';
					$param_list['KaiinPass'] = '';
					$params['param_list']    = array_merge(
						$param_list,
						array(
							'OperateId'   => '1Delete',
							'ProcessId'   => $acting_data['ProcessId'],
							'ProcessPass' => $acting_data['ProcessPass'],
						)
					);
					$response_data           = $this->connection( $params );
				}
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$class         = ' card-' . mb_strtolower( substr( $response_data['OperateId'], 1 ) );
					$status_name   = $this->get_operate_name( $response_data['OperateId'] );
					$res          .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
					$res          .= '<table class="welcart-settlement-admin-table">';
					$res          .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
					$res          .= '<td><input type="text" id="amount_change" value="' . $amount . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $amount . '" /></td>';
					$res          .= '</tr>';
					$res          .= '</table>';
					$res          .= '<div class="welcart-settlement-admin-button">';
					$res          .= '<input id="reauth-settlement" type="button" class="button" value="' . __( 'Re-authorization', 'usces' ) . '" />'; /* 再オーソリ */
					$res          .= '</div>';
					$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
				} else {
					$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>';
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 1Delete connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_delete_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status']        = $response_data['ResponseCd'];
				$data['acting_status'] = $acting_status;
				$data['result']        = $res;
				$this->save_admin_log( $order_id, $order_id . '_' . $trans_id );
				wp_send_json( $data );
				break;

			/* 利用額変更 */
			case 'change_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				$amount    = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) || is_null( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res      = '';
				$log_data = array();
				if ( 1 === (int) $order_num ) {
					$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );
				} else {
					$log_data    = $this->get_acting_log( $order_id, $order_id . '_' . $trans_id );
					$acting_data = usces_unserialize( $log_data[0]['log'] );
				}
				$operateid                     = $this->get_acting_operateid( $order_id . '_' . $trans_id );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_card';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$member_id                     = filter_input( INPUT_POST, 'member_id' );
				$response_member               = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$param_list['KaiinId']   = $response_member['KaiinId'];
					$param_list['KaiinPass'] = $response_member['KaiinPass'];
				}
				$params['send_url']   = $acting_opts['send_url'];
				$params['param_list'] = array_merge(
					$param_list,
					array(
						'OperateId'   => '1Change',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
						'Amount'      => $amount,
					)
				);
				$response_data        = $this->connection( $params );
				if ( 'K81' === $response_data['ResponseCd'] ) {
					$param_list['KaiinId']   = '';
					$param_list['KaiinPass'] = '';
					$params['param_list']    = array_merge(
						$param_list,
						array(
							'OperateId'   => '1Change',
							'ProcessId'   => $acting_data['ProcessId'],
							'ProcessPass' => $acting_data['ProcessPass'],
							'Amount'      => $amount,
						)
					);
					$response_data           = $this->connection( $params );
				}
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$class       = ' card-' . mb_strtolower( substr( $operateid, 1 ) );
					$status_name = $this->get_operate_name( $operateid );
					$res        .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
					$res        .= '<table class="welcart-settlement-admin-table">';
					$res        .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
					$res        .= '<td><input type="text" id="amount_change" value="' . $amount . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $amount . '" /></td>';
					$res        .= '</tr>';
					if ( isset( $response_data['SalesDate'] ) ) {
						$res .= '<tr><th>' . __( 'Recorded sales date', 'usces' ) . '</th><td>' . $response_data['SalesDate'] . '</td></tr>';
					}
					$res .= '</table>';
					$res .= '<div class="welcart-settlement-admin-button">';
					if ( '1Capture' !== $operateid && '1Gathering' !== $operateid ) {
						$res .= '<input id="capture-settlement" type="button" class="button" value="' . __( 'Sales recorded', 'usces' ) . '" />'; /* 売上計上 */
					}
					$res .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
					$res .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change spending amount', 'usces' ) . '" />'; /* 利用額変更 */
					$res .= '</div>';
				} else {
					$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 1Change connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_change_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res           .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status'] = $response_data['ResponseCd'];
				$data['result'] = $res;
				$this->save_admin_log( $order_id, $order_id . '_' . $trans_id );
				wp_send_json( $data );
				break;

			/* 与信 */
			case 'auth_welcartpay_card':
			/* 与信売上計上 */
			case 'gathering_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				$amount    = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) || is_null( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res           = '';
				$acting_status = '';
				$log_data      = array();
				if ( '9999999999' === $trans_id ) {
					$trans_id = usces_acting_key();
				} else {
					if ( 1 === (int) $order_num ) {
						$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );
					} else {
						$log_data    = $this->get_acting_log( $order_id, $order_id . '_' . $trans_id );
						$acting_data = usces_unserialize( $log_data[0]['log'] );
					}
				}
				$operateid                     = ( 'auth_welcartpay_card' === $mode ) ? '1Auth' : '1Gathering';
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_card';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$member_id                     = filter_input( INPUT_POST, 'member_id' );
				$response_member               = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$params['send_url']   = $acting_opts['send_url'];
					$params['param_list'] = array_merge(
						$param_list,
						array(
							'KaiinId'   => $response_member['KaiinId'],
							'KaiinPass' => $response_member['KaiinPass'],
							'OperateId' => $operateid,
							'PayType'   => '01',
							'Amount'    => $amount,
						)
					);
					$response_data        = $this->connection( $params );
					if ( 'OK' === $response_data['ResponseCd'] ) {
						if ( 1 === (int) $order_num ) {
							$cardlast4                = substr( $response_member['CardNo'], -4 );
							$expyy                    = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 2 ) . substr( $response_member['CardExp'], 0, 2 );
							$expmm                    = substr( $response_member['CardExp'], 2, 2 );
							$response_data['acting']  = $this->acting_card;
							$response_data['CardNo']  = $cardlast4;
							$response_data['CardExp'] = $expyy . '/' . $expmm;
							$usces->set_order_meta_value( 'acting_welcart_card', usces_serialize( $response_data ), $order_id );
							$usces->set_order_meta_value( 'trans_id', $trans_id, $order_id );
							$usces->set_order_meta_value( 'wc_trans_id', $trans_id, $order_id );
						} else {
							if ( $log_data ) {
								$this->update_acting_log( $response_data, $order_id . '_' . $trans_id );
							}
						}

						$class       = ' card-' . mb_strtolower( substr( $operateid, 1 ) );
						$status_name = $this->get_operate_name( $operateid );
						$res        .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
						$res        .= '<table class="welcart-settlement-admin-table">';
						$res        .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
						$res        .= '<td><input type="text" id="amount_change" value="' . $amount . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $amount . '" /></td>';
						$res        .= '</tr>';
						$res        .= '</table>';
						$res        .= '<div class="welcart-settlement-admin-button">';
						if ( '1Capture' !== $operateid && '1Gathering' !== $operateid ) {
							$res .= '<input id="capture-settlement" type="button" class="button" value="' . __( 'Sales recorded', 'usces' ) . '" />'; /* 売上計上 */
						}
						$res          .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
						$res          .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change spending amount', 'usces' ) . '" />'; /* 利用額変更 */
						$res          .= '</div>';
						$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
					} else {
						$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
						$res       .= '<div class="welcart-settlement-admin-error">';
						$responsecd = explode( '|', $response_data['ResponseCd'] );
						foreach ( (array) $responsecd as $cd ) {
							$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
						}
						$res .= '</div>';
						usces_log( '[WelcartPay] ' . $operateid . ' connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
					}
					do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
					if ( ! isset( $response_data['Amount'] ) ) {
						$response_data['Amount'] = $amount;
					}
					$response_data = apply_filters( 'usces_filter_escott_' . mb_strtolower( substr( $operateid, 1 ) ) . '_history_log', $response_data, $order_id );
					$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
					$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
					$data['status']        = $response_data['ResponseCd'];
					$data['acting_status'] = $acting_status;
					$data['result']        = $res;
					$data['trans_id']      = $trans_id;
				} else {
					$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_member['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 4MemRefM connection NG : ' . print_r( $response_member, true ), 'acting_transaction.log' );
					$data['status'] = $response_member['ResponseCd'];
					$data['result'] = $res;
				}
				wp_send_json( $data );
				break;

			/* 再オーソリ */
			case 'reauth_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$res       = '';
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				$amount    = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) || is_null( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res           = '';
				$acting_status = '';
				if ( 1 === (int) $order_num ) {
					$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );
				} else {
					$log_data    = $this->get_acting_log( $order_id, $order_id . '_' . $trans_id );
					$acting_data = usces_unserialize( $log_data[0]['log'] );
				}
				$operateid                     = $this->get_acting_operateid( $order_id . '_' . $trans_id );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_card';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$member_id                     = filter_input( INPUT_POST, 'member_id' );
				$response_member               = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$param_list['KaiinId']   = $response_member['KaiinId'];
					$param_list['KaiinPass'] = $response_member['KaiinPass'];
				}
				// if ( '1Gathering' === $operateid ) {
					$param_list['SalesDate'] = $transaction_date;
				// }
				$params['send_url']   = $acting_opts['send_url'];
				$params['param_list'] = array_merge(
					$param_list,
					array(
						'OperateId'   => '1ReAuth',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
						'Amount'      => $amount,
					)
				);
				$response_data        = $this->connection( $params );
				if ( 'K81' === $response_data['ResponseCd'] ) {
					$param_list['KaiinId']   = '';
					$param_list['KaiinPass'] = '';
					$params['param_list']    = array_merge(
						$param_list,
						array(
							'OperateId'   => '1ReAuth',
							'ProcessId'   => $acting_data['ProcessId'],
							'ProcessPass' => $acting_data['ProcessPass'],
							'Amount'      => $amount,
						)
					);
					$response_data           = $this->connection( $params );
				}
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$acting_data['TransactionId']   = $response_data['TransactionId'];
					$acting_data['TransactionDate'] = $response_data['TransactionDate'];
					$acting_data['ProcessId']       = $response_data['ProcessId'];
					$acting_data['ProcessPass']     = $response_data['ProcessPass'];
					$usces->set_order_meta_value( 'acting_welcart_card', usces_serialize( $acting_data ), $order_id );

					$class       = ' card-' . mb_strtolower( substr( $operateid, 1 ) );
					$status_name = $this->get_operate_name( $operateid );
					$res        .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
					$res        .= '<table class="welcart-settlement-admin-table">';
					$res        .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
					$res        .= '<td><input type="text" id="amount_change" value="' . $amount . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $amount . '" /></td>';
					$res        .= '</tr>';
					if ( isset( $response_data['SalesDate'] ) ) {
						$res .= '<tr><th>' . __( 'Recorded sales date', 'usces' ) . '</th><td>' . $response_data['SalesDate'] . '</td></tr>';
					}
					$res .= '</table>';
					$res .= '<div class="welcart-settlement-admin-button">';
					if ( '1Capture' !== $operateid && '1Gathering' !== $operateid ) {
						$res .= '<input id="capture-settlement" type="button" class="button" value="' . __( 'Sales recorded', 'usces' ) . '" />'; /* 売上計上 */
					}
					$res          .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
					$res          .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change spending amount', 'usces' ) . '" />'; /* 利用額変更 */
					$res          .= '</div>';
					$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
				} else {
					$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 1ReAuth connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_reauth_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status']        = $response_data['ResponseCd'];
				$data['acting_status'] = $acting_status;
				$data['result']        = $res;
				$this->save_admin_log( $order_id, $order_id . '_' . $trans_id );
				wp_send_json( $data );
				break;

			/* 決済エラー */
			case 'error_welcartpay_card':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					$data['result'] = '';
					wp_send_json( $data );
					break;
				}
				$res             = '';
				$member_id       = filter_input( INPUT_POST, 'member_id' );
				$response_member = $this->escott_member_reference( $member_id ); /* e-SCOTT 会員照会 */
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$order_data       = $usces->get_order_data( $order_id, 'direct' );
					$total_full_price = $order_data['order_item_total_price'] - $order_data['order_usedpoint'] + $order_data['order_discount'] + $order_data['order_shipping_charge'] + $order_data['order_cod_fee'] + $order_data['order_tax'];

					$res .= '<div class="welcart-settlement-admin card-error">' . __( 'Repayment', 'usces' ) . '</div>'; /* 再決済 */
					$res .= '<table class="welcart-settlement-admin-table">';
					$res .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th>';
					$res .= '<td><input type="text" id="amount_change" value="' . $total_full_price . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $total_full_price . '" /></td>';
					$res .= '</tr>';
					$res .= '</table>';
					$res .= '<div class="welcart-settlement-admin-button">';
					$res .= '<input id="auth-settlement" type="button" class="button" value="' . __( 'Credit', 'usces' ) . '" />'; /* 与信 */
					$res .= '<input id="gathering-settlement" type="button" class="button" value="' . __( 'Credit sales', 'usces' ) . '" />'; /* 与信売上計上 */
					$res .= '</div>';
					$res .= $this->settlement_history( $order_id . '_' . $trans_id );
				} else {
					$res .= '<div class="welcart-settlement-admin card-error">' . __( 'Settlement error', 'usces' ) . '</div>'; /* エラー */
					$res .= '<div class="welcart-settlement-admin-error">';
					$res .= '<div><span class="message">' . __( 'Credit card information not registered', 'usces' ) . '</span></div>'; /* カード情報未登録 */
					$res .= '</div>';
				}
				$data['status'] = 'OK';
				$data['result'] = $res;
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
				$price            = filter_input( INPUT_POST, 'price' );
				if ( is_null( $price ) ) {
					$price = 0;
				}
				$status = filter_input( INPUT_POST, 'status' );

				if ( version_compare( WCEX_DLSELLER_VERSION, '3.0-beta', '<=' ) ) {
					$continue_data = usces_unserialize( $usces->get_member_meta_value( 'continuepay_' . $order_id, $member_id ) );
				} else {
					$continue_data = $this->get_continuation_data( $order_id, $member_id );
				}
				if ( ! $continue_data ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}

				/* 継続中→停止 */
				if ( 'continuation' === $continue_data['status'] && 'cancellation' === $status ) {
					if ( version_compare( WCEX_DLSELLER_VERSION, '3.0-beta', '<=' ) ) {
						$continue_data['status'] = 'cancellation';
						$usces->set_member_meta_value( 'continuepay_' . $order_id, usces_serialize( $continue_data ), $member_id );
					} else {
						$this->update_continuation_data( $order_id, $member_id, $continue_data, true );
					}
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
					if ( version_compare( WCEX_DLSELLER_VERSION, '3.0-beta', '<=' ) ) {
						$usces->set_member_meta_value( 'continuepay_' . $order_id, usces_serialize( $continue_data ), $member_id );
					} else {
						$this->update_continuation_data( $order_id, $member_id, $continue_data );
					}
				}
				$data['status'] = 'OK';
				wp_send_json( $data );
				break;

			/* オンライン収納代行データ登録 */
			case 'add_welcartpay_conv':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id = filter_input( INPUT_POST, 'order_id' );
				$trans_id = filter_input( INPUT_POST, 'trans_id' );
				$paylimit = filter_input( INPUT_POST, 'paylimit' );
				$amount   = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $trans_id ) || is_null( $paylimit ) || is_null( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res                           = '';
				$acting_status                 = '';
				$order_data                    = $usces->get_order_data( $order_id, 'direct' );
				$namekanji                     = $order_data['order_name1'] . $order_data['order_name2'];
				$namekana                      = ( ! empty( $order_data['order_name3'] ) ) ? $order_data['order_name3'] . $order_data['order_name4'] : $namekanji;
				$telno                         = $order_data['order_tel'];
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_conv';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$params['send_url']            = $acting_opts['send_url_conv'];
				$params['param_list']          = array_merge(
					$param_list,
					array(
						'OperateId' => '2Add',
						'PayLimit'  => $paylimit . '2359',
						'Amount'    => $amount,
						'NameKanji' => $namekanji,
						'NameKana'  => $namekana,
						'TelNo'     => $telno,
						'ReturnURL' => home_url( '/' ),
					)
				);
				$response_data                 = $this->connection( $params );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$response_data['acting']   = $this->acting_conv;
					$response_data['PayLimit'] = $params['param_list']['PayLimit'];
					$response_data['Amount']   = $params['param_list']['Amount'];
					$usces->set_order_meta_value( 'acting_welcart_conv', usces_serialize( $response_data ), $order_id );
					$freearea = trim( $response_data['FreeArea'] );
					$url      = add_query_arg(
						array(
							'code' => $freearea,
							'rkbn' => 2,
						),
						$acting_opts['redirect_url_conv']
					);
					$usces->set_order_meta_value( 'welcart_conv_url', $url, $order_id );

					$res          .= '<div class="welcart-settlement-admin conv-noreceipt">' . __( 'Unpaid', 'usces' ) . '</div>'; /* 未入金 */
					$res          .= '<table class="welcart-settlement-admin-table">';
					$res          .= '<tr><th>' . __( 'Payment due', 'usces' ) . '</th>';
					$res          .= '<td><input type="text" id="paylimit_change" value="' . $paylimit . '" style="ime-mode:disabled" size="10" /><input type="hidden" id="paylimit" value="' . $paylimit . '" /></td>';
					$res          .= '</tr>';
					$res          .= '<tr><th>' . __( 'Payment amount', 'usces' ) . '</th>';
					$res          .= '<td><input type="text" id="amount_change" value="' . $amount . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $amount . '" /></td>';
					$res          .= '</tr>';
					$res          .= '</table>';
					$res          .= '<div class="welcart-settlement-admin-button">';
					$res          .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
					$res          .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change' ) . '" />'; /* 変更 */
					$res          .= '</div>';
					$acting_status = '<span class="acting-status conv-noreceipt">' . __( 'Unpaid', 'usces' ) . '</span>';
				} else {
					$res       .= '<div class="welcart-settlement-admin conv-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 2Add connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_add_conv_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status']        = $response_data['ResponseCd'];
				$data['acting_status'] = $acting_status;
				$data['result']        = $res;
				wp_send_json( $data );
				break;

			/* オンライン収納代行データ変更 */
			case 'change_welcartpay_conv':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id = filter_input( INPUT_POST, 'order_id' );
				$trans_id = filter_input( INPUT_POST, 'trans_id' );
				$paylimit = filter_input( INPUT_POST, 'paylimit' );
				$amount   = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $trans_id ) || is_null( $paylimit ) || is_null( $amount ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res                           = '';
				$acting_data                   = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_conv', $order_id ) );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_conv';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$params['send_url']            = $acting_opts['send_url_conv'];
				$params['param_list']          = array_merge(
					$param_list,
					array(
						'OperateId'   => '2Chg',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
						'PayLimit'    => $paylimit . '2359',
						'Amount'      => $amount,
					)
				);
				$response_data                 = $this->connection( $params );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$acting_data['PayLimit'] = $params['param_list']['PayLimit'];
					$acting_data['Amount']   = $params['param_list']['Amount'];
					$usces->set_order_meta_value( 'acting_welcart_conv', usces_serialize( $acting_data ), $order_id );
					$freearea = trim( $response_data['FreeArea'] );
					$url      = add_query_arg(
						array(
							'code' => $freearea,
							'rkbn' => 2,
						),
						$acting_opts['redirect_url_conv']
					);
					$usces->set_order_meta_value( 'welcart_conv_url', $url, $order_id );

					$res .= '<div class="welcart-settlement-admin conv-noreceipt">' . __( 'Unpaid', 'usces' ) . '</div>'; /* 未入金 */
					$res .= '<table class="welcart-settlement-admin-table">';
					if ( isset( $acting_data['PayLimit'] ) ) {
						$res .= '<tr><th>' . __( 'Payment due', 'usces' ) . '</th><td>' . $acting_data['PayLimit'] . '</td></tr>';
					}
					if ( isset( $acting_data['Amount'] ) ) {
						$res .= '<tr><th>' . __( 'Payment amount', 'usces' ) . '</th><td>' . $acting_data['Amount'] . '</td></tr>';
					}
					$res .= '</table>';
					$res .= '<div class="welcart-settlement-admin-button">';
					$res .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
					$res .= '</div>';
				} else {
					$res       .= '<div class="welcart-settlement-admin conv-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 2Chg connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_change_conv_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res           .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status'] = $response_data['ResponseCd'];
				$data['result'] = $res;
				wp_send_json( $data );
				break;

			/* オンライン収納代行データ削除 */
			case 'delete_welcartpay_conv':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id = filter_input( INPUT_POST, 'order_id' );
				$trans_id = filter_input( INPUT_POST, 'trans_id' );
				$amount   = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res                           = '';
				$acting_status                 = '';
				$acting_data                   = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_conv', $order_id ) );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_conv';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$params['send_url']            = $acting_opts['send_url_conv'];
				$params['param_list']          = array_merge(
					$param_list,
					array(
						'OperateId'   => '2Del',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
					)
				);
				$response_data                 = $this->connection( $params );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$res .= '<div class="welcart-settlement-admin conv-del">' . __( 'Canceled', 'usces' ) . '</div>'; /* 取消済み */
					$res .= '<table class="welcart-settlement-admin-table">';
					if ( isset( $acting_data['PayLimit'] ) ) {
						$paylimit = substr( $acting_data['PayLimit'], 0, 8 );
						$res     .= '<tr><th>' . __( 'Payment due', 'usces' ) . '</th><td>' . $paylimit . '</td></tr>';
					}
					if ( isset( $acting_data['Amount'] ) ) {
						$res .= '<tr><th>' . __( 'Payment amount', 'usces' ) . '</th><td>' . $acting_data['Amount'] . '</td></tr>';
					}
					$res          .= '</table>';
					$acting_status = '<span class="acting-status conv-del">' . __( 'Canceled', 'usces' ) . '</span>';
				} else {
					$res       .= '<div class="welcart-settlement-admin conv-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 2Del connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = ( isset( $acting_data['Amount'] ) ) ? $acting_data['Amount'] : $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_delete_conv_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status']        = $response_data['ResponseCd'];
				$data['acting_status'] = $acting_status;
				$data['result']        = $res;
				wp_send_json( $data );
				break;

			/* オンライン収納代行データ入金結果参照 */
			case 'get_welcartpay_conv':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id = filter_input( INPUT_POST, 'order_id' );
				$trans_id = filter_input( INPUT_POST, 'trans_id' );
				if ( is_null( $order_id ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res                           = '';
				$acting_data                   = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_conv', $order_id ) );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_conv';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$params['send_url']            = $acting_opts['send_url_conv'];
				$params['param_list']          = array_merge(
					$param_list,
					array(
						'OperateId'   => '2Ref',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
					)
				);
				$response_data                 = $this->connection( $params );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					if ( isset( $response_data['NyukinDate'] ) ) {
						$res .= '<div class="welcart-settlement-admin conv-receipted">' . __( 'Paid', 'usces' ) . '</div>'; /* 入金済 */
						$res .= '<table class="welcart-settlement-admin-table">';
						if ( isset( $response_data['RecvNum'] ) ) {
							$res .= '<tr><th>' . __( 'Receipt number', 'usces' ) . '</th><td>' . $response_data['RecvNum'] . '</td></tr>'; /* 受付番号 */
						}
						if ( isset( $response_data['NyukinDate'] ) ) {
							$res .= '<tr><th>' . __( 'Deposit date', 'usces' ) . '</th><td>' . $response_data['NyukinDate'] . '</td></tr>'; /* 入金日時 */
						}
						if ( isset( $response_data['CvsCd'] ) ) {
							$cvs_name = $this->get_cvs_name( $response_data['CvsCd'] );
							$res     .= '<tr><th>' . __( 'Convenience store code', 'usces' ) . '</th><td>' . $cvs_name . '</td></tr>'; /* 収納機関コード */
						}
						if ( isset( $response_data['TenantCd'] ) ) {
							$res .= '<tr><th>' . __( 'Tenant code', 'usces' ) . '</th><td>' . $response_data['TenantCd'] . '</td></tr>'; /* 店舗コード */
						}
						if ( isset( $response_data['Amount'] ) ) {
							$res .= '<tr><th>' . __( 'Payment amount', 'usces' ) . '</th><td>' . $response_data['Amount'] . __( usces_crcode( 'return' ), 'usces' ) . '</td></tr>';
						}
						$res .= '</table>';
					} else {
						$paylimit   = substr( $acting_data['PayLimit'], 0, 8 );
						$expiration = $this->check_paylimit( $order_id, $trans_id );
						$res       .= '<div class="welcart-settlement-admin conv-noreceipt">' . __( 'Unpaid', 'usces' ); /* 未入金 */
						if ( $expiration ) {
							$res .= __( '(Expired)', 'usces' ); /* （期限切れ） */
						}
						$res       .= '</div>';
						$res       .= '<table class="welcart-settlement-admin-table">';
						$res       .= '<tr><th>' . __( 'Payment due', 'usces' ) . '</th>';
						$res       .= '<td><input type="text" id="paylimit_change" value="' . $paylimit . '" style="ime-mode:disabled" size="10" /><input type="hidden" id="paylimit" value="' . $paylimit . '" /></td>';
						$res       .= '</tr>';
						$res       .= '<tr><th>' . __( 'Payment amount', 'usces' ) . '</th>';
						$res       .= '<td><input type="text" id="amount_change" value="' . $acting_data['Amount'] . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $acting_data['Amount'] . '" /></td>';
						$res       .= '</tr>';
						$res       .= '</table>';
						$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
						if ( isset( $latest_log['OperateId'] ) ) {
							$res .= '<div class="welcart-settlement-admin-button">';
							if ( '2Del' !== $latest_log['OperateId'] ) {
								$res .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
							}
							if ( '2Chg' !== $latest_log['OperateId'] ) {
								$res .= '<input id="change-settlement" type="button" class="button" value="' . __( 'Change' ) . '" />'; /* 変更 */
							}
							$res .= '</div>';
						}
					}
				} else {
					$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
					if ( isset( $latest_log['OperateId'] ) && '2Del' === $latest_log['OperateId'] && 'K12' === $response_data['ResponseCd'] ) {
						$paylimit = substr( $acting_data['PayLimit'], 0, 8 );
						$res     .= '<div class="welcart-settlement-admin conv-del">' . __( 'Canceled', 'usces' ) . '</div>'; /* 取消済み */
						$res     .= '<table class="welcart-settlement-admin-table">';
						$res     .= '<tr><th>' . __( 'Payment due', 'usces' ) . '</th>';
						$res     .= '<td><input type="text" id="paylimit_change" value="' . $paylimit . '" style="ime-mode:disabled" size="10" /><input type="hidden" id="paylimit" value="' . $paylimit . '" /></td>';
						$res     .= '</tr>';
						$res     .= '<tr><th>' . __( 'Payment amount', 'usces' ) . '</th>';
						$res     .= '<td><input type="text" id="amount_change" value="' . $acting_data['Amount'] . '" style="text-align:right;ime-mode:disabled" size="10" />' . __( usces_crcode( 'return' ), 'usces' ) . '<input type="hidden" id="amount" value="' . $acting_data['Amount'] . '" /></td>';
						$res     .= '</tr>';
						$res     .= '</table>';
						$res     .= '<div class="welcart-settlement-admin-button">';
						$res     .= '<input id="add-settlement" type="button" class="button" value="' . __( 'Register' ) . '" />'; /* 登録 */
						$res     .= '</div>';
					} else {
						$res       .= '<div class="welcart-settlement-admin conv-error">' . __( 'Error', 'usces' ) . '</div>'; /* エラー */
						$res       .= '<div class="welcart-settlement-admin-error">';
						$responsecd = explode( '|', $response_data['ResponseCd'] );
						foreach ( (array) $responsecd as $cd ) {
							$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
						}
						$res .= '</div>';
						usces_log( '[WelcartPay] 2Ref connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
					}
				}
				$res           .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status'] = $response_data['ResponseCd'];
				$data['result'] = $res;
				wp_send_json( $data );
				break;

			/* 銀聯取引参照 */
			case 'get_welcartpay_unionpay':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res                           = '';
				$log_data                      = array();
				$acting_data                   = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_unionpay', $order_id ) );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_unionpay';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$params['send_url']            = $acting_opts['send_url_unionpay'];
				$params['param_list']          = array_merge(
					$param_list,
					array(
						'OperateId'   => '11Search',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
					)
				);
				$response_data                 = $this->connection( $params );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
					if ( isset( $latest_log['OperateId'] ) ) {
						$class       = ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 2 ) );
						$status_name = $this->get_operate_name( $latest_log['OperateId'] );
						$res        .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
						$res        .= '<table class="welcart-settlement-admin-table">';
						if ( isset( $latest_log['Amount'] ) ) {
							$res .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th><td>' . usces_crform( $latest_log['Amount'], false, true, 'return', true ) . '</td></tr>';
						}
						if ( isset( $latest_log['Status'] ) ) {
							$res .= '<tr><th>' . __( 'Transaction status', 'usces' ) . '</th><td>' . $this->get_unionpay_status_name( $latest_log['Status'] ) . '</td></tr>';
						}
						$res .= '</table>';
						$res .= '<div class="welcart-settlement-admin-button">';
						if ( '11Delete' !== $latest_log['OperateId'] ) {
							$res .= '<input id="delete-settlement" type="button" class="button" value="' . __( 'Unregister', 'usces' ) . '" />'; /* 取消 */
						}
						$res .= '</div>';
					}
				} else {
					if ( 'K12' === $response_data['ResponseCd'] ) {
						$res .= '<div class="welcart-settlement-admin card-delete">' . __( 'Expired', 'usces' ) . '</div>';
						$res .= '<div class="welcart-settlement-admin-expired">';
						$res .= '<div><span class="code">K12</span> : <span class="message">' . __( 'Handling expired.', 'usces' ) . '</span></div>';
						$res .= '</div>';
					} else {
						$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>';
						$res       .= '<div class="welcart-settlement-admin-error">';
						$responsecd = explode( '|', $response_data['ResponseCd'] );
						foreach ( $responsecd as $cd ) {
							$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
						}
						$res .= '</div>';
						usces_log( '[WelcartPay] 11Search connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
					}
				}
				$res           .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status'] = $response_data['ResponseCd'];
				$data['result'] = $res;
				wp_send_json( $data );
				break;

			/* 銀聯取消 */
			case 'delete_welcartpay_unionpay':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				$order_id  = filter_input( INPUT_POST, 'order_id' );
				$order_num = filter_input( INPUT_POST, 'order_num' );
				$trans_id  = filter_input( INPUT_POST, 'trans_id' );
				$amount    = filter_input( INPUT_POST, 'amount' );
				if ( is_null( $order_id ) || is_null( $order_num ) || is_null( $trans_id ) ) {
					$data['status'] = 'NG';
					wp_send_json( $data );
					break;
				}
				$res                           = '';
				$acting_status                 = '';
				$log_data                      = array();
				$acting_data                   = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_unionpay', $order_id ) );
				$acting_opts                   = $this->get_acting_settings();
				$transaction_date              = $this->get_transaction_date();
				$param_list                    = array();
				$params                        = array();
				$param_list['MerchantId']      = $acting_opts['merchant_id'];
				$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$param_list['TransactionDate'] = $transaction_date;
				$param_list['MerchantFree1']   = $trans_id;
				$param_list['MerchantFree2']   = 'acting_welcart_unionpay';
				$param_list['MerchantFree3']   = $this->merchantfree3;
				$param_list['TenantId']        = $acting_opts['tenant_id'];
				$params['send_url']            = $acting_opts['send_url_unionpay'];
				$params['param_list']          = array_merge(
					$param_list,
					array(
						'OperateId'   => '11Delete',
						'ProcessId'   => $acting_data['ProcessId'],
						'ProcessPass' => $acting_data['ProcessPass'],
					)
				);
				$response_data                 = $this->connection( $params );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$class         = ' card-' . mb_strtolower( substr( $response_data['OperateId'], 2 ) );
					$status_name   = $this->get_operate_name( $response_data['OperateId'] );
					$res          .= '<div class="welcart-settlement-admin' . $class . '">' . $status_name . '</div>';
					$res          .= '<table class="welcart-settlement-admin-table">';
					$res          .= '<tr><th>' . __( 'Spending amount', 'usces' ) . '</th><td>' . usces_crform( $amount, false, true, 'return', true ) . '</td></tr>';
					$res          .= '</table>';
					$acting_status = '<span class="acting-status' . $class . '">' . $status_name . '</span>';
				} else {
					$res       .= '<div class="welcart-settlement-admin card-error">' . __( 'Error', 'usces' ) . '</div>';
					$res       .= '<div class="welcart-settlement-admin-error">';
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$res .= '<div><span class="code">' . $cd . '</span> : <span class="message">' . $this->response_message( $cd ) . '</span></div>';
					}
					$res .= '</div>';
					usces_log( '[WelcartPay] 11Delete connection NG : ' . print_r( $response_data, true ), 'acting_transaction.log' );
				}
				do_action( 'usces_action_admin_' . $mode, $response_data, $order_id, $trans_id );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_delete_history_log', $response_data, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $trans_id );
				$res                  .= $this->settlement_history( $order_id . '_' . $trans_id );
				$data['status']        = $response_data['ResponseCd'];
				$data['acting_status'] = $acting_status;
				$data['result']        = $res;
				wp_send_json( $data );
				break;
		}
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

		if ( 'wc_trans_id' !== $key || empty( $value ) ) {
			return $detail;
		}

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		$payment    = usces_get_payments_by_name( $order_data['order_payment_name'] );
		$acting_flg = ( isset( $payment['settlement'] ) ) ? $payment['settlement'] : '';

		if ( 'acting_welcart_card' === $acting_flg ) {
			$trans_id   = $usces->get_order_meta_value( 'trans_id', $order_id );
			$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
			if ( isset( $latest_log['OperateId'] ) ) {
				$class  = ( ctype_digit( substr( $latest_log['OperateId'], 0, 1 ) ) ) ? ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 1 ) ) : ' card-' . $latest_log['OperateId'];
				$detail = '<td>' . $value . '<span class="acting-status' . $class . '">' . $this->get_operate_name( $latest_log['OperateId'] ) . '</span></td>';
			} elseif ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
				$regular_id = $usces->get_order_meta_value( 'regular_id', $order_id );
				if ( ! empty( $regular_id ) && empty( $trans_id ) ) {
					$detail = '<td>' . $value . '<span class="acting-status card-error">' . __( 'Card unregistered', 'usces' ) . '</span></td>';
				}
			}
		} elseif ( 'acting_welcart_conv' === $acting_flg ) {
			$trans_id   = $usces->get_order_meta_value( 'trans_id', $order_id );
			$expiration = $this->check_paylimit( $order_id, $trans_id );
			if ( $expiration ) {
				if ( ! $usces->is_status( 'receipted', $order_data['order_status'] ) ) {
					$detail = '<td>' . $value . '<span class="acting-status conv-expiration">' . __( 'Expired', 'usces' ) . '</span></td>';
				}
			} else {
				$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
				if ( isset( $latest_log['OperateId'] ) && '2Del' === $latest_log['OperateId'] ) {
					$detail = '<td>' . $value . '<span class="acting-status conv-del">' . __( 'Canceled', 'usces' ) . '</span></td>';
				} else {
					$management_status = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
					if ( $usces->is_status( 'noreceipt', $value ) ) {
						$detail = '<td>' . $value . '<span class="acting-status conv-noreceipt">' . esc_html( $management_status['noreceipt'] ) . '</span></td>';
					} elseif ( $usces->is_status( 'receipted', $value ) ) {
						$detail = '<td>' . $value . '<span class="acting-status conv-receipted">' . esc_html( $management_status['receipted'] ) . '</span></td>';
					} else {
						$detail = '<td>' . $value . '</td>';
					}
				}
			}
		} elseif ( 'acting_welcart_unionpay' === $acting_flg ) {
			$trans_id   = $usces->get_order_meta_value( 'trans_id', $order_id );
			$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id );
			if ( isset( $latest_log['OperateId'] ) ) {
				$class  = ( ctype_digit( substr( $latest_log['OperateId'], 0, 2 ) ) ) ? ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 2 ) ) : ' card-' . $latest_log['OperateId'];
				$detail = '<td>' . $value . '<span class="acting-status' . $class . '">' . $this->get_operate_name( $latest_log['OperateId'] ) . '</span></td>';
			}
		}
		return $detail;
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
		if ( 'welcart_card' !== $acting && 'welcart_conv' !== $acting && 'welcart_atodene' !== $acting && 'welcart_applepay' !== $acting && 'welcart_unionpay' !== $acting ) {
			return $value;
		}

		switch ( $key ) {
			case 'acting':
				switch ( $value ) {
					case 'welcart_card':
						$value = __( 'WelcartPay - Credit card transaction', 'usces' );
						break;
					case 'welcart_conv':
						$value = __( 'WelcartPay - Online storage agency', 'usces' );
						break;
					case 'welcart_atodene':
						$value = __( 'WelcartPay - Postpay settlement', 'usces' );
						break;
					case 'welcart_applepay':
						$value = __( 'WelcartPay - Apple Pay', 'usces' );
						break;
					case 'welcart_unionpay':
						$value = __( 'WelcartPay - UnionPay', 'usces' );
						break;
				}
				break;
		}

		$value = parent::settlement_info_field_value( $value, $key, $acting );

		return $value;
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
		extract( $action_args ); // phpcs:ignore

		if ( 'new' !== $order_action && ! empty( $order_id ) ) {
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( in_array( $payment['settlement'], $this->pay_method ) ) {
				$acting_data   = usces_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
				$merchantfree1 = ( isset( $acting_data['MerchantFree1'] ) ) ? $acting_data['MerchantFree1'] : '';
				if ( ! empty( $merchantfree1 ) ) {
					$status_name = '';
					$class       = '';
					$latest_log  = $this->get_acting_latest_log( $order_id . '_' . $merchantfree1 );
					if ( isset( $latest_log['OperateId'] ) ) {
						switch ( $payment['settlement'] ) {
							case 'acting_welcart_conv':
								$expiration = $this->check_paylimit( $order_id, $merchantfree1 );
								if ( $expiration ) {
									if ( ! $usces->is_status( 'receipted', $data['order_status'] ) ) {
										$class       = ' conv-expiration';
										$status_name = __( 'Expired', 'usces' );
									}
								} else {
									if ( '2Del' === $latest_log['OperateId'] ) {
										$class       = ' conv-del';
										$status_name = __( 'Canceled', 'usces' );
									}
								}
								break;
							case 'acting_welcart_card':
								$class       = ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 1 ) );
								$status_name = $this->get_operate_name( $latest_log['OperateId'] );
								break;
							case 'acting_welcart_unionpay':
								$class       = ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 2 ) );
								$status_name = $this->get_operate_name( $latest_log['OperateId'] );
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
				} elseif ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
					$regular_id = $usces->get_order_meta_value( 'regular_id', $order_id );
					if ( ! empty( $regular_id ) ) {
						echo '
						<tr>
							<td class="label status">' . esc_html__( 'Settlement status', 'usces' ) . '</td>
							<td class="col1 status"><span id="settlement-status"><span class="acting-status card-error">' . esc_html__( 'Card unregistered', 'usces' ) . '</span></span></td>
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
	 * @param  array $action_args Compact array( $order_action, $order_id, $cart ).
	 */
	public function settlement_information( $data, $action_args ) {
		global $usces;
		extract( $action_args ); // phpcs:ignore

		if ( 'new' !== $order_action && ! empty( $order_id ) ) {
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( in_array( $payment['settlement'], $this->pay_method, true ) ) {
				$acting_data   = usces_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
				$merchantfree1 = ( isset( $acting_data['MerchantFree1'] ) && isset( $acting_data['ProcessId'] ) && isset( $acting_data['ProcessPass'] ) ) ? $acting_data['MerchantFree1'] : '9999999999';
				echo '<input type="button" id="settlement-information-' . esc_attr( $merchantfree1 ) . '-1" class="button settlement-information" value="' . esc_attr__( 'Settlement info', 'usces' ) . '">';
			}
		}
	}

	/**
	 * 決済情報ダイアログ
	 * usces_action_endof_order_edit_form
	 *
	 * @param  array $data Order data.
	 * @param  array $action_args Compact array( $order_action, $order_id, $cart ).
	 */
	public function settlement_dialog( $data, $action_args ) {
		global $usces;
		extract( $action_args ); // phpcs:ignore

		if ( 'new' !== $order_action && ! empty( $order_id ) ) :
			$payment = usces_get_payments_by_name( $data['order_payment_name'] );
			if ( in_array( $payment['settlement'], $this->pay_method, true ) ) :
				?>
<div id="settlement_dialog" title="">
	<div id="settlement-response-loading"></div>
	<fieldset>
	<div id="settlement-response"></div>
	<input type="hidden" id="order_num">
	<input type="hidden" id="trans_id">
	<input type="hidden" id="acting" value="<?php echo esc_attr( $payment['settlement'] ); ?>">
	<input type="hidden" id="error">
	</fieldset>
</div>
				<?php
			endif;
		endif;
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

		parent::acting_processing( $acting_flg, $post_query );

		$acting_opts = $this->get_acting_settings();

		/* 外部リンク（三者間画面） */
		if ( 'acting_welcart_card' === $acting_flg && 'link' === $acting_opts['card_activate'] ) {

			$usces_entries = $usces->cart->get_entry();
			$cart          = $usces->cart->get_cart();

			if ( ! $usces_entries || ! $cart ) {
				wp_redirect( USCES_CART_URL );
				exit();
			}

			if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $acting_flg ) ) {
				wp_redirect( USCES_CART_URL );
				exit();
			}

			parse_str( $post_query, $post_data );
			$transaction_date = $this->get_transaction_date();
			$rand             = $post_data['rand'];
			$member           = $usces->get_member();

			usces_save_order_acting_data( $rand );

			$param_list = array();
			$params     = array();

			$quick_member = ( isset( $post_data['quick_member'] ) ) ? $post_data['quick_member'] : '';
			if ( 'add' !== $quick_member ) {
				if ( ! empty( $member['ID'] ) && 'on' === $acting_opts['quickpay'] ) {
					$kaiin_id   = $this->get_quick_kaiin_id( $member['ID'] );
					$kaiin_pass = $this->get_quick_pass( $member['ID'] );
				} else {
					$kaiin_id   = '';
					$kaiin_pass = '';
				}
				if ( empty( $kaiin_id ) || empty( $kaiin_pass ) ) {
					$quick_member = 'no';
				}
			}

			$home_url    = str_replace( 'http://', 'https://', home_url( '/' ) );
			$redirecturl = $home_url . '?page_id=' . USCES_CART_NUMBER;

			if ( ! empty( $member['ID'] ) && 'on' === $acting_opts['quickpay'] && ( 'add' === $quick_member || 'update' === $quick_member ) ) {
				$data_list                    = array();
				$data_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$data_list['TransactionDate'] = $transaction_date;
				$data_list['MerchantFree1']   = $rand;
				$data_list['MerchantFree2']   = $acting_flg;
				$data_list['MerchantFree3']   = $this->merchantfree3;
				$data_list['TenantId']        = $acting_opts['tenant_id'];
				if ( 'add' === $quick_member ) {
					$data_list['OperateId'] = '4MemAdd';
					$data_list['KaiinId']   = $this->make_kaiin_id( $member['ID'] );
					$data_list['KaiinPass'] = $this->make_kaiin_pass();
				} elseif ( 'update' === $quick_member ) {
					$data_list['OperateId'] = '4MemChg';
					$data_list['KaiinId']   = $kaiin_id;
					$data_list['KaiinPass'] = $kaiin_pass;
				}
				$data_list['ProcNo']      = '0000000';
				$data_list['RedirectUrl'] = $redirecturl;
				$data_query               = http_build_query( $data_list );
				$encryptvalue             = openssl_encrypt( $data_query, 'aes-128-cbc', $acting_opts['key_aes'], false, $acting_opts['key_iv'] );

				$param_list['MerchantId']   = $acting_opts['merchant_id'];
				$param_list['EncryptValue'] = urlencode( $encryptvalue );
				wp_redirect( add_query_arg( $param_list, $acting_opts['send_url_link'] ) );
			} else {
				if ( usces_have_continue_charge() ) {
					$chargingday = $usces->getItemChargingDay( $cart[0]['post_id'] );
					if ( 99 === (int) $chargingday ) { /* 受注日課金 */
						$operateid = $acting_opts['operateid'];
					} else {
						$operateid = '1Auth';
					}
				} else {
					$operateid = $acting_opts['operateid'];
				}
				$operateid = apply_filters( 'usces_filter_escott_operateid', $operateid, $cart, $usces_entries['order']['total_full_price'] );

				$data_list                    = array();
				$data_list['OperateId']       = $operateid;
				$data_list['MerchantPass']    = $acting_opts['merchant_pass'];
				$data_list['TransactionDate'] = $transaction_date;
				$data_list['MerchantFree1']   = $rand;
				$data_list['MerchantFree2']   = $acting_flg;
				$data_list['MerchantFree3']   = $this->merchantfree3;
				$data_list['TenantId']        = $acting_opts['tenant_id'];
				if ( 'on' === $acting_opts['quickpay'] && ! empty( $kaiin_id ) && ! empty( $kaiin_pass ) ) {
					$data_list['KaiinId']   = $kaiin_id;
					$data_list['KaiinPass'] = $kaiin_pass;
				}
				$data_list['PayType']     = '01';
				$data_list['Amount']      = $usces_entries['order']['total_full_price'];
				$data_list['ProcNo']      = '0000000';
				$data_list['RedirectUrl'] = $redirecturl;
				$data_query               = http_build_query( $data_list );
				$encryptvalue             = openssl_encrypt( $data_query, 'aes-128-cbc', $acting_opts['key_aes'], false, $acting_opts['key_iv'] );

				$param_list['MerchantId']   = $acting_opts['merchant_id'];
				$param_list['EncryptValue'] = urlencode( $encryptvalue );
				wp_redirect( add_query_arg( $param_list, $acting_opts['send_url_link'] ) );
			}
			exit();

			/* 銀聯 */
		} elseif ( 'acting_welcart_unionpay' === $acting_flg ) {
			$home_url = str_replace( 'http://', 'https://', home_url( '/' ) );

			$usces_entries = $usces->cart->get_entry();
			$cart          = $usces->cart->get_cart();

			if ( ! $usces_entries || ! $cart ) {
				wp_redirect( USCES_CART_URL );
				exit();
			}

			if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $acting_flg ) ) {
				wp_redirect( USCES_CART_URL );
				exit();
			}

			parse_str( $post_query, $post_data );
			$transaction_date = $this->get_transaction_date();
			$rand             = $post_data['rand'];

			usces_save_order_acting_data( $rand );

			$data_list                    = array();
			$data_list['MerchantPass']    = $acting_opts['merchant_pass'];
			$data_list['TransactionDate'] = $transaction_date;
			$data_list['MerchantFree1']   = $rand;
			$data_list['MerchantFree2']   = $acting_flg;
			$data_list['MerchantFree3']   = $this->merchantfree3;
			$data_list['TenantId']        = $acting_opts['tenant_id'];
			$data_list['Amount']          = $usces_entries['order']['total_full_price'];
			$data_list['CurrencyId']      = '392'; /* JPY */
			if ( ! empty( $acting_opts['unionpay_pagelanguage'] ) ) {
				$data_list['PageLanguage'] = $acting_opts['unionpay_pagelanguage'];
			}
			// $data_list['PayCardType'] = '00';
			$data_list['ProcNo']          = sprintf( '%020d', $rand );
			$data_list['SuccessURL']      = USCES_CART_URL . $usces->delim . 'acting=' . $this->acting_unionpay . '&acting_return=1';
			$data_list['ErrorURL']        = USCES_CART_URL . $usces->delim . 'acting=' . $this->acting_unionpay . '&acting_return=0';
			$data_list['StatusNoticeURL'] = $home_url;
			$data_list['EndNoticeURL']    = $home_url;
			$data_query                   = http_build_query( $data_list );
			$encryptvalue                 = openssl_encrypt( $data_query, 'aes-128-cbc', $acting_opts['unionpay_key_aes'], false, $acting_opts['unionpay_key_iv'] );

			$param_list                 = array();
			$param_list['MerchantId']   = $acting_opts['merchant_id'];
			$param_list['OperateId']    = '11Gathering';
			$param_list['EncryptValue'] = $encryptvalue;

			$gc = new SLNConnection();
			$gc->send_request_unionpay( $acting_opts['send_url_unionpay'], $param_list );
			exit();
		}
	}

	/**
	 * 受注データ登録
	 * Called by usces_reg_orderdata() and usces_new_orderdata().
	 * usces_action_reg_orderdata
	 *
	 * @param  array $args Compact array( $cart, $entry, $order_id, $member_id, $payments, $charging_type, $results ).
	 */
	public function register_orderdata( $args ) {
		extract( $args ); // phpcs:ignore

		if ( ! isset( $results['MerchantFree1'] ) ) {
			return;
		}

		$acting_flg = $payments['settlement'];
		if ( ! in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}

		if ( ! $entry['order']['total_full_price'] ) {
			return;
		}

		parent::register_orderdata( $args );

		if ( ! isset( $results['Amount'] ) ) {
			$results['Amount'] = $entry['order']['total_full_price'];
		}
		$results = apply_filters( 'usces_filter_escott_register_orderdata_history_log', $results, $args );
		$this->save_acting_history_log( $results, $order_id . '_' . $results['MerchantFree1'] );
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

		parent::footer_scripts();

		/* 発送・支払方法ページ */
		if ( 'delivery' === $usces->page ) {
			$acting_opts = $this->get_acting_settings();
			/* リンク型 */
			if ( isset( $acting_opts['card_activate'] ) && 'link' === $acting_opts['card_activate'] ) {
				wp_enqueue_style( 'jquery-ui-style' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_style( 'jquery-ui-dialog-min-css', includes_url() . 'css/jquery-ui-dialog.min.css' );
				wp_register_script( 'usces_cart_escott_link', USCES_FRONT_PLUGIN_URL . '/js/cart_escott_link.js', array( 'jquery' ), USCES_VERSION, true );
				$escott_params                         = array();
				$escott_params['card_activate']        = $acting_opts['card_activate'];
				$escott_params['message']['agreement'] = __( '* Cautions on Use of Credit Cards', 'usces' ) . "\n"
					. __( 'In order to prevent unauthorized use of your credit card through theft of information such as your credit card number, we use "EMV 3D Secure," an identity authentication service recommended by international brands.', 'usces' ) . "\n"
					. __( 'In order to use EMV 3D Secure, it is necessary to send information about you to the card issuer.', 'usces' ) . "\n"
					. __( 'Please read "* Provision of Personal Information to Third Parties" below and enter your card information only if you agree to the terms of the agreement.', 'usces' ) . "\n"
					. __( '* Provision of Personal Information to Third Parties', 'usces' ) . "\n"
					. __( 'The following personal information, etc. collected from customers will be provided to the issuer of the card being used by the customer for the purpose of detecting and preventing fraudulent use by the card issuer.', 'usces' ) . "\n"
					. __( '"Membership information held by the business", "IP address", "device information", "Information on the Internet usage environment", and "Billing address".', 'usces' ) . "\n"
					. __( 'If the issuer of the card you are using is located in a foreign country, these information may be transferred to the country to which such issuer belongs.', 'usces' ) . "\n"
					. __( 'If you are a minor, you are required to obtain the consent of a person with parental authority or a guardian before using the Service.', 'usces' ) . "\n"
					. __( '* Agreement to provide personal information to a third party', 'usces' ) . "\n"
					. __( 'If you agree to the above "* Provision of Personal Information to Third Parties", please click "Agree" and proceed to enter your credit card information.', 'usces' ) . "\n"
					. __( '* Safety Control Measures', 'usces' ) . "\n"
					. __( 'We may provide all or part of the information obtained from our customers to subcontractors in the United States.', 'usces' ) . "\n"
					. __( 'We will confirm that the subcontractor takes necessary and appropriate measures for the safe management of the information before storing it.', 'usces' ) . "\n"
					. __( 'For an overview of the legal system regarding the protection of personal information in the relevant country, please check here.', 'usces' ) . "\n"
					. 'https://www.ppc.go.jp/personalinfo/legal/kaiseihogohou/#gaikoku';
				$escott_params['message']['agree']     = __( 'Agree', 'usces' );
				$escott_params['message']['disagree']  = __( 'Disagree', 'usces' );
				wp_localize_script( 'usces_cart_escott_link', 'escott_params', $escott_params );
				wp_enqueue_script( 'usces_cart_escott_link' );
			}
		}

		if ( ! usces_is_membersystem_state() || ! usces_is_login() ) {
			return;
		}

		/* クレジットカード情報更新ページ */
		if ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) || 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) ) ) {
			// wp_enqueue_script( 'usces_escott_member', USCES_FRONT_PLUGIN_URL . '/js/member_escott.js', array( 'jquery' ), USCES_VERSION, true );
			print_google_recaptcha_response( filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ), 'member-card-info', 'member_update_settlement' );
		}

		/* マイページ */
		if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) {
			$acting_opts = $this->get_acting_settings();
			if ( 'on' === $acting_opts['quickpay'] ) {
				wp_enqueue_style( 'jquery-ui-style' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_style( 'jquery-ui-dialog-min-css', includes_url() . 'css/jquery-ui-dialog.min.css' );
				wp_register_script( 'usces_member_escott', USCES_FRONT_PLUGIN_URL . '/js/member_escott.js', array( 'jquery' ), USCES_VERSION, true );
				$escott_params                            = array();
				$escott_params['sec3d_activate']          = $acting_opts['sec3d_activate'];
				$escott_params['message']['agreement']    = __( '* Cautions on Use of Credit Cards', 'usces' ) . "\n"
					. __( 'In order to prevent unauthorized use of your credit card through theft of information such as your credit card number, we use "EMV 3D Secure," an identity authentication service recommended by international brands.', 'usces' ) . "\n"
					. __( 'In order to use EMV 3D Secure, it is necessary to send information about you to the card issuer.', 'usces' ) . "\n"
					. __( 'Please read "* Provision of Personal Information to Third Parties" below and enter your card information only if you agree to the terms of the agreement.', 'usces' ) . "\n"
					. __( '* Provision of Personal Information to Third Parties', 'usces' ) . "\n"
					. __( 'The following personal information, etc. collected from customers will be provided to the issuer of the card being used by the customer for the purpose of detecting and preventing fraudulent use by the card issuer.', 'usces' ) . "\n"
					. __( '"Membership information held by the business", "IP address", "device information", "Information on the Internet usage environment", and "Billing address".', 'usces' ) . "\n"
					. __( 'If the issuer of the card you are using is located in a foreign country, these information may be transferred to the country to which such issuer belongs.', 'usces' ) . "\n"
					. __( 'If you are a minor, you are required to obtain the consent of a person with parental authority or a guardian before using the Service.', 'usces' ) . "\n"
					. __( '* Agreement to provide personal information to a third party', 'usces' ) . "\n"
					. __( 'If you agree to the above "* Provision of Personal Information to Third Parties", please click "Agree" and proceed to enter your credit card information.', 'usces' ) . "\n"
					. __( '* Safety Control Measures', 'usces' ) . "\n"
					. __( 'We may provide all or part of the information obtained from our customers to subcontractors in the United States.', 'usces' ) . "\n"
					. __( 'We will confirm that the subcontractor takes necessary and appropriate measures for the safe management of the information before storing it.', 'usces' ) . "\n"
					. __( 'For an overview of the legal system regarding the protection of personal information in the relevant country, please check here.', 'usces' ) . "\n"
					. 'https://www.ppc.go.jp/personalinfo/legal/kaiseihogohou/#gaikoku';
				$escott_params['message']['agree']        = __( 'Agree', 'usces' );
				$escott_params['message']['disagree']     = __( 'Disagree', 'usces' );
				$escott_params['update_settlement_url']   = urlencode(
					add_query_arg(
						array(
							'usces_page' => 'member_update_settlement',
							're-enter'   => 1,
						),
						USCES_MEMBER_URL
					)
				);
				$escott_params['register_settlement_url'] = urlencode(
					add_query_arg(
						array(
							'usces_page' => 'member_register_settlement',
							're-enter'   => 1,
						),
						USCES_MEMBER_URL
					)
				);
				wp_localize_script( 'usces_member_escott', 'escott_params', $escott_params );
				wp_enqueue_script( 'usces_member_escott' );
			}
		}
	}

	/**
	 * 支払方法説明
	 * usces_fiter_the_payment_method_explanation
	 *
	 * @param  string $explanation Explanation of Payment Method.
	 * @param  array  $payment Payment data.
	 * @param  string $value Input value.
	 * @return string
	 */
	public function set_payment_method_explanation( $explanation, $payment, $value ) {
		global $usces;

		$quickpay = '';
		if ( $this->acting_flg_card === $payment['settlement'] ) {
			$acting_opts = $this->get_acting_settings();
			if ( 'link' === $acting_opts['card_activate'] ) {
				if ( usces_is_login() && 'on' === $acting_opts['quickpay'] ) {
					$member     = $usces->get_member();
					$kaiin_id   = $this->get_quick_kaiin_id( $member['ID'] );
					$kaiin_pass = $this->get_quick_pass( $member['ID'] );
					if ( ! empty( $kaiin_id ) && ! empty( $kaiin_pass ) ) {
						$quickpay = '<p class="' . $this->paymod_id . '_quick_member"><label type="update"><input type="checkbox" name="quick_member" value="update"><span>' . __( 'Change and register purchased credit card', 'usces' ) . '</span></label></p>';
					} else {
						if ( usces_have_regular_order() || usces_have_continue_charge() ) {
							$quickpay = '<input type="hidden" name="quick_member" value="add">';
						} elseif ( 'on' !== $acting_opts['chooseable_quickpay'] ) {
							$quickpay = '<input type="hidden" name="quick_member" value="add">';
						} else {
							$quickpay = '<p class="' . $this->paymod_id . '_quick_member"><label type="add"><input type="checkbox" name="quick_member" value="add"><span>' . __( 'Register and purchase a credit card', 'usces' ) . '</span></label></p>';
						}
					}
				} else {
					$quickpay = '<input type="hidden" name="quick_member" value="no">';
				}
			}
		}
		return $quickpay . $explanation;
	}

	/**
	 * 利用可能な決済モジュール
	 * usces_filter_available_payment_method
	 *
	 * @param  array $payments Payment data.
	 * @return array
	 */
	public function set_available_payment_method( $payments ) {
		global $usces;

		if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) {
			$payment_method = array();
			foreach ( (array) $payments as $id => $payment ) {
				if ( $this->acting_flg_card === $payment['settlement'] ) {
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
	 * 支払回数
	 * usces_filter_delivery_secure_form_howpay
	 *
	 * @param  string $html HTML.
	 * @return string
	 */
	public function delivery_secure_form_howpay( $html ) {
		if ( isset( $_GET['usces_page'] ) && ( 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) || 'member_register_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) ) ) {
			$html = '';
		}
		return $html;
	}

	/**
	 * 内容確認ページ [注文する] ボタン
	 * usces_filter_confirm_inform
	 *
	 * @param  string $html Purchase post form.
	 * @param  array  $payments Payment data.
	 * @param  string $acting_flg Payment type.
	 * @param  string $rand Welcart transaction key.
	 * @param  string $purchase_disabled Disable purchase button.
	 * @return string
	 */
	public function confirm_inform( $html, $payments, $acting_flg, $rand, $purchase_disabled ) {
		global $usces;

		$html = parent::confirm_inform( $html, $payments, $acting_flg, $rand, $purchase_disabled );

		$usces_entries = $usces->cart->get_entry();
		if ( ! $usces_entries['order']['total_full_price'] ) {
			return $html;
		}

		if ( $this->acting_flg_unionpay === $acting_flg ) {
			$html = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
				<input type="hidden" name="rand" value="' . $rand . '">
				<div class="send">
					' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
					<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
					<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . apply_filters( 'usces_filter_confirm_nextbutton', null ) . $purchase_disabled . ' />
				</div>
				<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
		}
		return $html;
	}

	/**
	 * Front styles.
	 * wp_print_styles
	 */
	public function print_styles() {
		global $usces;

		parent::print_styles();

		/* マイページ */
		if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) :
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] || 'link' === $acting_opts['card_activate'] ) :
				?>
<style type="text/css">
#escott-dialog {
	left: 50% !important;
	transform: translateY(-50%) translateX(-50%);
	-webkit- transform: translateY(-50%) translateX(-50%);
	width: 90% !important;
	max-width: 700px;
}
#escott-agree-dialog {
	height: auto !important;
}
.escott_agreement_message {
	width: 100%;
	height: 200px;
}
</style>
				<?php
			endif;
		endif;
	}

	/**
	 * Front scripts.
	 * wp_enqueue_scripts
	 */
	public function enqueue_scripts() {
		global $usces;

		/* 発送・支払方法ページ、クレジットカード情報更新ページ */
		if ( ! is_admin() && $this->is_validity_acting( 'card' ) ) :
			if ( ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'delivery' === $usces->page ) ||
				( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) && ( 'member_register_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) || 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) ) ) ) :
				$acting_opts = $this->get_acting_settings();
				if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) :
					?>
<script type="text/javascript" src="<?php echo esc_attr( $acting_opts['api_token'] ); ?>?k_TokenNinsyoCode=<?php echo esc_attr( $acting_opts['token_code'] ); ?>" callBackFunc="setToken" class="spsvToken"></script>
					<?php
				endif;
			endif;
		endif;
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

		$l10n = parent::set_uscesL10n( $l10n, $post_id );

		if ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'delivery' === $usces->page ) {
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'link' === $acting_opts['card_activate'] ) {
				$front_ajaxurl  = trailingslashit( USCES_SSL_URL ) . 'index.php';
				$l10n          .= "'front_ajaxurl': '" . $front_ajaxurl . "',\n";
				$payment_method = usces_get_system_option( 'usces_payment_method', 'sort' );
				$payment_method = apply_filters( 'usces_fiter_the_payment_method', $payment_method, '' );
				foreach ( (array) $payment_method as $id => $payment ) {
					if ( $payment['settlement'] === $this->acting_flg_card ) {
						$l10n .= "'escott_link_payment_id': '" . $id . "',\n";
						break;
					}
				}
			}
		} elseif ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) && ( isset( $_GET['usces_page'] ) && ( 'member_register_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) || 'member_update_settlement' === filter_input( INPUT_GET, 'usces_page', FILTER_SANITIZE_STRING ) ) ) ) {
			$acting_opts = $this->get_acting_settings();
			if ( isset( $acting_opts['card_activate'] ) && 'token' === $acting_opts['card_activate'] ) {
				$front_ajaxurl = trailingslashit( USCES_SSL_URL ) . 'index.php';
				$l10n         .= "'front_ajaxurl': '" . $front_ajaxurl . "',\n";
				$l10n         .= "'escott_token_error_message': '" . __( 'Credit card information is not appropriate.', 'usces' ) . "',\n";
			}
		}
		return $l10n;
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
			if ( 'on' !== $acting_opts['quickpay'] ) {
				return;
			}

			if ( isset( $_REQUEST['usces_page'] ) && 'member_update_settlement' === wp_unslash( $_REQUEST['usces_page'] ) ) {
				add_filter( 'usces_filter_states_form_js', array( $this, 'states_form_js' ) );
				$usces->page = 'member_update_settlement';
				$this->member_update_settlement_form();
				exit();

			} elseif ( isset( $_REQUEST['usces_page'] ) && 'member_register_settlement' === wp_unslash( $_REQUEST['usces_page'] ) ) {
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
	 * 会員データ削除チェック
	 * usces_filter_delete_member_check
	 *
	 * @param  boolean $del Deletable.
	 * @param  int     $member_id Member ID.
	 * @return boolean
	 */
	public function delete_member_check( $del, $member_id ) {
		$kaiin_id = $this->get_quick_kaiin_id( $member_id );
		if ( ! empty( $kaiin_id ) ) {
			$del = false;
		}
		return $del;
	}

	/**
	 * クレジットカード登録・変更ページリンク
	 * usces_action_member_submenu_list
	 */
	public function e_update_settlement() {
		global $usces;

		$member = $usces->get_member();
		$html   = $this->update_settlement( '', $member );
		echo $html; // no escape.
	}

	/**
	 * クレジットカード登録・変更ページリンク
	 * usces_filter_member_submenu_list
	 *
	 * @param  string $html Submenu area of the member page.
	 * @param  array  $member Member information.
	 * @return string
	 */
	public function update_settlement( $html, $member ) {
		$acting_opts = $this->get_acting_settings();
		if ( 'on' === $acting_opts['quickpay'] ) {
			/* e-SCOTT 会員照会 */
			$response_member = $this->escott_member_reference( $member['ID'] );
			if ( 'OK' === $response_member['ResponseCd'] ) {
				if ( 'on' === $acting_opts['sec3d_activate'] ) {
					$html .= '<li class="gotoedit"><a href="javascript:void(0);" class="escott_agreement" data-mode="update">' . __( 'Change the credit card is here >>', 'usces' ) . '</a></li>';
				} else {
					$update_settlement_url = add_query_arg(
						array(
							'usces_page' => 'member_update_settlement',
							're-enter'   => 1,
						),
						USCES_MEMBER_URL
					);
					$html                 .= '<li class="gotoedit"><a href="' . $update_settlement_url . '">' . __( 'Change the credit card is here >>', 'usces' ) . '</a></li>';
				}
			} else {
				if ( 'on' === $acting_opts['sec3d_activate'] ) {
					$html .= '<li class="gotoedit"><a href="javascript:void(0);" class="escott_agreement" data-mode="register">' . __( 'Credit card registration is here >>', 'usces' ) . '</a></li>';
				} else {
					$register_settlement_url = add_query_arg(
						array(
							'usces_page' => 'member_register_settlement',
							're-enter'   => 1,
						),
						USCES_MEMBER_URL
					);
					$html                   .= '<li class="gotoedit"><a href="' . $register_settlement_url . '">' . __( 'Credit card registration is here >>', 'usces' ) . '</a></li>';
				}
			}
		}
		return $html;
	}

	/**
	 * クレジットカード登録・変更ページ
	 */
	public function member_update_settlement_form() {
		global $usces;

		$member      = $usces->get_member();
		$acting_opts = $this->get_acting_settings();

		/* 外部リンク（三者間画面） */
		if ( 'link' === $acting_opts['card_activate'] ) {
			$transaction_date = $this->get_transaction_date();
			$home_url         = str_replace( 'http://', 'https://', home_url( '/' ) );
			$redirecturl      = $home_url . '?page_id=' . USCES_MEMBER_NUMBER;

			$data_list                    = array();
			$data_list['MerchantPass']    = $acting_opts['merchant_pass'];
			$data_list['TransactionDate'] = $transaction_date;
			$data_list['MerchantFree3']   = $this->merchantfree3;
			$data_list['TenantId']        = $acting_opts['tenant_id'];
			if ( 'member_register_settlement' === $usces->page ) {
				$data_list['OperateId'] = '4MemAdd';
				$data_list['KaiinId']   = $this->make_kaiin_id( $member['ID'] );
				$data_list['KaiinPass'] = $this->make_kaiin_pass();
			} else {
				$data_list['OperateId'] = '4MemChg';
				$data_list['KaiinId']   = $this->get_quick_kaiin_id( $member['ID'] );
				$data_list['KaiinPass'] = $this->get_quick_pass( $member['ID'] );
			}
			$data_list['ProcNo']      = '0000000';
			$data_list['RedirectUrl'] = $redirecturl;
			$data_query               = http_build_query( $data_list );
			$encryptvalue             = openssl_encrypt( $data_query, 'aes-128-cbc', $acting_opts['key_aes'], false, $acting_opts['key_iv'] );

			$param_list['MerchantId']   = $acting_opts['merchant_id'];
			$param_list['EncryptValue'] = urlencode( $encryptvalue );
			wp_redirect( add_query_arg( $param_list, $acting_opts['send_url_link'] ) );
			exit();

			/* マイページ */
		} else {
			$script       = '';
			$done_message = '';
			$html         = '';
			$register     = ( 'member_register_settlement' === $usces->page ) ? true : false;
			$deleted      = false;

			$cardno = '';
			$seccd  = '';
			$expyy  = '';
			$expmm  = '';

			if ( 'on' === $acting_opts['quickpay'] ) {
				if ( isset( $_POST['update'] ) ) {
					check_admin_referer( 'member_update_settlement', 'wc_nonce' );
					$verify_action = wel_verify_update_settlement( $member['ID'] );
					if ( ! $verify_action ) {
						$usces->error_message .= '<p>' . __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '</p>';
					} else {
						$response_member = $this->escott_member_update( $member['ID'] );
						if ( 'OK' === $response_member['ResponseCd'] ) {
							$this->send_update_settlement_mail();
							$done_message = __( 'Successfully updated.', 'usces' );
						} else {
							$error_message = array();
							$responsecd    = explode( '|', $response_member['ResponseCd'] );
							foreach ( (array) $responsecd as $cd ) {
								$error_message[] = $this->error_message( $cd );
							}
							$error_message = array_unique( $error_message );
							if ( 0 < count( $error_message ) ) {
								foreach ( $error_message as $message ) {
									$usces->error_message .= '<p>' . $message . '</p>';
								}
							}
							$cardno = filter_input( INPUT_POST, 'cardno', FILTER_SANITIZE_STRING );
							$seccd  = filter_input( INPUT_POST, 'seccd', FILTER_SANITIZE_STRING );
							$expyy  = filter_input( INPUT_POST, 'expyy', FILTER_SANITIZE_STRING );
							$expmm  = filter_input( INPUT_POST, 'expmm', FILTER_SANITIZE_STRING );
						}
					}
				} elseif ( isset( $_POST['register'] ) ) {
					check_admin_referer( 'member_update_settlement', 'wc_nonce' );
					$verify_action = wel_verify_update_settlement( $member['ID'] );
					if ( ! $verify_action ) {
						$usces->error_message .= '<p>' . __( 'Update has been locked. Please contact the store administrator.', 'usces' ) . '</p>';
					} else {
						$response_member = $this->escott_member_register( $member['ID'] );
						if ( 'OK' === $response_member['ResponseCd'] ) {
							$done_message = __( 'Successfully registered.', 'usces' );
							$register     = false;
						} else {
							$error_message = array();
							$responsecd    = explode( '|', $response_member['ResponseCd'] );
							foreach ( (array) $responsecd as $cd ) {
								$error_message[] = $this->error_message( $cd );
							}
							$error_message = array_unique( $error_message );
							if ( 0 < count( $error_message ) ) {
								foreach ( $error_message as $message ) {
									$usces->error_message .= '<p>' . $message . '</p>';
								}
							}
							$cardno = filter_input( INPUT_POST, 'cardno', FILTER_SANITIZE_STRING );
							$seccd  = filter_input( INPUT_POST, 'seccd', FILTER_SANITIZE_STRING );
							$expyy  = filter_input( INPUT_POST, 'expyy', FILTER_SANITIZE_STRING );
							$expmm  = filter_input( INPUT_POST, 'expmm', FILTER_SANITIZE_STRING );
						}
					}
				}

				if ( ! $deleted ) {
					/* e-SCOTT 会員照会 */
					$response_member = $this->escott_member_reference( $member['ID'] );
					if ( 'OK' === $response_member['ResponseCd'] ) {
						$cardlast4 = substr( $response_member['CardNo'], -4 );
						$expyy     = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 2 ) . substr( $response_member['CardExp'], 0, 2 );
						$expmm     = substr( $response_member['CardExp'], 2, 2 );
					} else {
						$cardlast4 = '';
					}
					$html .= '<input name="acting" type="hidden" value="' . $this->paymod_id . '" />
					<table class="customer_form" id="' . $this->paymod_id . '">';
					if ( ! empty( $cardlast4 ) ) {
						$html .= '
						<tr>
							<th scope="row">' . __( 'The last four digits of your card number', 'usces' ) . '</th>
							<td colspan="2"><p>' . $cardlast4 . '</p></td>
						</tr>';
					}
					$cardno_attention = apply_filters( 'usces_filter_cardno_attention', __( '(Single-byte numbers only)', 'usces' ) . '<div class="attention">' . __( '* Please do not enter symbols or letters other than numbers such as space (blank), hyphen (-) between numbers.', 'usces' ) . '</div>' );
					$html            .= '
						<tr>
							<th scope="row">' . __( 'card number', 'usces' ) . '</th>
							<td colspan="2"><input name="cardno" id="cardno" type="tel" value="' . $cardno . '" />' . $cardno_attention . '</td>
						</tr>';
					if ( 'on' === $acting_opts['seccd'] ) {
						$seccd_attention = apply_filters( 'usces_filter_seccd_attention', __( '(Single-byte numbers only)', 'usces' ) );
						$html           .= '
						<tr>
							<th scope="row">' . __( 'security code', 'usces' ) . '</th>
							<td colspan="2"><input name="seccd" id="seccd" type="tel" value="' . $seccd . '" />' . $seccd_attention . '</td>
						</tr>';
					}
					$html .= '
						<tr>
							<th scope="row">' . __( 'Card expiration', 'usces' ) . '</th>
							<td colspan="2">
							<select id="expmm">
								<option value=""' . selected( empty( $expmm ), true, false ) . '>--</option>';
					for ( $i = 1; $i <= 12; $i++ ) {
						$html .= '
								<option value="' . sprintf( '%02d', $i ) . '"' . selected( (int) $expmm, $i, false ) . '>' . sprintf( '%2d', $i ) . '</option>';
					}
					$html .= '
							</select>' . __( 'month', 'usces' ) . '&nbsp;
							<select id="expyy">
								<option value=""' . selected( empty( $expyy ), true, false ) . '>----</option>';
					for ( $i = 0; $i < 15; $i++ ) {
						$year  = date_i18n( 'Y' ) + $i;
						$html .= '
								<option value="' . $year . '"' . selected( (int) $expyy, (int) $year, false ) . '>' . $year . '</option>';
					}
					$html .= '
							</select>' . __( 'year', 'usces' ) . '
							</td>
						</tr>
					</table>';
				}
			}

			$update_settlement_url = add_query_arg(
				array(
					'usces_page' => $usces->page,
					'settlement' => 1,
					're-enter'   => 1,
				),
				USCES_MEMBER_URL
			);
			if ( '' !== $done_message ) {
				$script .= '
<script type="text/javascript">
jQuery.event.add( window, "load", function() {
	alert("' . $done_message . '");
});
</script>';
			}
			$error_message = apply_filters( 'usces_filter_member_update_settlement_error_message', $usces->error_message );

			ob_start();
			get_header();
			if ( '' !== $script ) {
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
				<?php
				if ( $register ) :
					?>
<h1 class="member_page_title"><?php esc_html_e( 'Credit card registration', 'usces' ); ?></h1>
					<?php
				else :
					?>
<h1 class="member_page_title"><?php esc_html_e( 'Credit card update', 'usces' ); ?></h1>
					<?php
				endif;
				?>
<div class="entry">
<div id="memberpages">
<div class="whitebox">
	<div id="memberinfo">
	<div class="header_explanation"></div>
				<?php
				if ( ! $deleted && ! $register ) :
					?>
	<p><?php esc_html_e( 'If you want to change the expiration date only, please the card number to the blank.', 'usces' ); ?></p>
					<?php
				endif;
				?>
	<div class="error_message"><?php echo wp_kses_post( $error_message ); ?></div>
				<?php
				if ( 'token' === $acting_opts['card_activate'] ) :
					echo $html; // no escape.
				endif;
				?>
	<form id="member-card-info" name="member_update_settlement" action="<?php echo esc_url( $update_settlement_url ); ?>" method="post" onKeyDown="if(event.keyCode == 13) {return false;}">
				<?php
				if ( 'on' === $acting_opts['card_activate'] ) :
					echo $html; // no escape.
				endif;
				?>
		<div class="send">
			<input type="hidden" name="expmm" value="<?php echo esc_attr( $expmm ); ?>" />
			<input type="hidden" name="expyy" value="<?php echo esc_attr( $expyy ); ?>" />
				<?php
				if ( 'token' === $acting_opts['card_activate'] ) :
					?>
			<input type="hidden" name="token" id="token" value="" />
					<?php
				endif;
				if ( 'on' === $acting_opts['sec3d_activate'] ) :
					?>
			<input type="hidden" name="rand" id="rand" value="<?php echo esc_attr( usces_acting_key() ); ?>" />
					<?php
				endif;
				if ( $register ) :
					?>
			<input type="hidden" name="register" value="register" />
			<input type="button" id="card-register" class="card-register" value="<?php esc_attr_e( 'Register' ); ?>" />
					<?php
				else :
					if ( ! $deleted ) :
						?>
			<input type="hidden" name="update" value="update" />
			<input type="button" id="card-update" class="card-update" value="<?php esc_attr_e( 'Update' ); ?>" />
						<?php
					endif;
				endif;
				?>
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
				<?php
			else :
				?>
<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'usces' ); ?></p>
				<?php
			endif;
			?>
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
	}

	/**
	 * クレジットカード変更確認メール
	 */
	public function send_update_settlement_mail() {
		global $usces;

		$member = $usces->get_member();
		// $mail_data = $usces->options['mail_data'];

		$subject     = apply_filters( 'usces_filter_send_update_settlement_mail_subject', __( 'Confirmation of credit card update', 'usces' ), $member );
		$mail_header = __( 'Your credit card information has been updated on the membership page.', 'usces' ) . "\r\n\r\n";
		// $mail_footer = $mail_data['footer']['thankyou'];
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

		$message        = '';
		$query          = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_continuation WHERE `con_member_id` = %d AND `con_acting` = %s AND `con_status` = 'continuation'", $member_id, 'acting_welcart_card' );
		$continue_order = $wpdb->get_results( $query, ARRAY_A );
		if ( 0 < count( $continue_order ) ) {
			$message .= '--------------------------------' . "\r\n";
			$message .= __( 'Auto-continuation charging Information under Contract with a credit card', 'usces' ) . "\r\n";
			foreach ( $continue_order as $continue_data ) {
				$con_id       = $continue_data['con_id'];
				$con_order_id = $continue_data['con_order_id'];
				$message     .= __( 'Order number', 'usces' ) . ' : ' . $con_order_id;
				$status       = $this->get_latest_status( $member_id, $con_order_id );
				if ( ! empty( $status ) ) {
					$next_charging = ( empty( $continue_data['con_next_contracting'] ) ) ? dlseller_next_charging( $con_order_id ) : $continue_data['con_next_contracting'];
					$message      .= ' ( ' . __( 'Next Withdrawal Date', 'dlseller' ) . ' : ' . date_i18n( __( 'Y/m/d' ), strtotime( $next_charging ) );
					if ( 0 < (int) $continue_data['con_interval'] ) {
						$next_contracting = ( empty( $continue_data['con_next_contracting'] ) ) ? dlseller_next_contracting( $con_order_id ) : $continue_data['con_next_contracting'];
						$message         .= ', ' . __( 'Renewal Date', 'dlseller' ) . ' : ' . date_i18n( __( 'Y/m/d' ), strtotime( $next_contracting ) );
					}
					$message .= ' )';
					if ( 'OK' !== $status ) {
						$message .= ' ' . __( 'Condition', 'dlseller' ) . ' : ' . __( 'Settlement error', 'usces' );
						$log_data = $this->get_acting_log( $con_order_id );
						if ( $log_data && 0 < count( $log_data ) ) {
							$acting_data = usces_unserialize( $log_data[0]['log'] );
							$trans_id    = ( isset( $acting_data['MerchantFree1'] ) ) ? $acting_data['MerchantFree1'] : '';
						} else {
							$trans_id = $usces->get_order_meta_value( 'trans_id', $con_order_id );
						}
						if ( $trans_id ) {
							$message .= ' ( ' . __( 'Transaction ID', 'usces' ) . ' : ' . $trans_id . ' )';
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

		$message                   = '';
		$regular_table_name        = $wpdb->prefix . 'usces_regular';
		$regular_detail_table_name = $wpdb->prefix . 'usces_regular_detail';
		$order_table_name          = $wpdb->prefix . 'usces_order';
		$order_meta_table_name     = $wpdb->prefix . 'usces_order_meta';

		$query         = $wpdb->prepare( "SELECT r.reg_id, r.reg_payment_name, d.regdet_schedule_date FROM {$regular_detail_table_name} AS `d` RIGHT JOIN {$regular_table_name} AS `r` ON  r.reg_id = d.reg_id WHERE r.reg_mem_id = %d AND d.regdet_condition = 'continuation' GROUP BY r.reg_id", $member_id );
		$regular_order = $wpdb->get_results( $query, ARRAY_A );
		if ( 0 < count( $regular_order ) ) {
			foreach ( $regular_order as $regular_data ) {
				$payment = $usces->getPayments( $regular_data['reg_payment_name'] );
				if ( 'acting_welcart_card' !== $payment['settlement'] ) {
					continue;
				}
				$reg_id             = $regular_data['reg_id'];
				$message           .= __( 'Regular ID', 'autodelivery' ) . ' : ' . $reg_id;
				$query              = $wpdb->prepare(
					"SELECT o.ID AS `order_id`, meta.meta_value AS `deco_id`, DATE_FORMAT( order_date, %s ) AS `date` 
						FROM {$order_table_name} AS `o` 
						LEFT JOIN {$order_meta_table_name} AS `meta` ON o.ID = meta.order_id AND meta.meta_key = 'dec_order_id' 
						LEFT JOIN {$regular_table_name} ON o.ID = reg_order_id 
						WHERE reg_id = %d 
					UNION ALL 
					SELECT o1.ID AS `order_id`, meta1.meta_value AS `deco_id`, DATE_FORMAT( order_date, %s ) AS `date` 
						FROM {$order_table_name} AS `o1` 
						LEFT JOIN {$order_meta_table_name} AS `meta1` ON o1.ID = meta1.order_id AND meta1.meta_key = 'dec_order_id' 
						LEFT JOIN {$order_meta_table_name} AS `meta2` ON o1.ID = meta2.order_id AND meta2.meta_key = 'regular_id' 
						WHERE meta2.meta_value = %d 
					ORDER BY order_id DESC, date DESC",
					'%Y-%m-%d',
					$reg_id,
					'%Y-%m-%d',
					$reg_id
				);
				$regular_order_data = $wpdb->get_results( $query, ARRAY_A );
				if ( 0 < count( $regular_order_data ) ) {
					$reg_order_id = $regular_order_data[0]['order_id'];
					$status       = $this->get_latest_status( $member_id, $reg_order_id );
					if ( ! empty( $status ) && 'OK' === $status ) {
						if ( $this->isdate( $regular_data['regdet_schedule_date'] ) ) {
							$message .= ' ( ' . __( 'Scheduled order date', 'autodelivery' ) . ' : ' . date_i18n( __( 'Y/m/d' ), strtotime( $regular_data['regdet_schedule_date'] ) ) . ' )';
						}
					} else {
						$message .= ' ' . __( 'Condition', 'autodelivery' ) . ' : ' . __( 'Settlement error', 'usces' );
						$trans_id = $usces->get_order_meta_value( 'trans_id', $reg_order_id );
						if ( $trans_id ) {
							$message .= ' ( ' . __( 'Transaction ID', 'usces' ) . ' : ' . $trans_id . ' )';
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
	 * 利用可能な支払方法
	 * usces_filter_the_continue_payment_method
	 *
	 * @param  array $payment_method Payment method.
	 * @return array
	 */
	public function continuation_payment_method( $payment_method ) {
		$acting_opts = $this->get_acting_settings();
		if ( 'on' === $acting_opts['quickpay'] ) {
			$payment_method[] = 'acting_welcart_card';
		}
		return $payment_method;
	}

	/**
	 * 利用可能な支払方法
	 * dlseller_filter_the_payment_method_restriction
	 * wcad_filter_the_payment_method_restriction
	 *
	 * @param  array  $payments_restriction Payment method.
	 * @param  string $payments_value Input value.
	 * @return array
	 */
	public function payment_method_restriction( $payments_restriction, $payments_value ) {
		$acting_opts = $this->get_acting_settings();
		if ( ( usces_have_regular_order() || usces_have_continue_charge() ) && usces_is_login() && 'on' === $acting_opts['quickpay'] ) {
			$payments               = usces_get_system_option( 'usces_payment_method', 'settlement' );
			$payments_restriction[] = $payments['acting_welcart_card'];
			foreach ( (array) $payments_restriction as $key => $value ) {
				$sort[ $key ] = $value['sort'];
			}
			array_multisort( $sort, SORT_ASC, $payments_restriction );
		}
		return $payments_restriction;
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
				$today = date_i18n( 'Y-m-d', current_time( 'timestamp' ) );
				list( $year, $month, $day ) = explode( '-', $today );
				$time  = mktime( 0, 0, 0, (int) $month, (int) $day, (int) $year );
			}
		}
		return $time;
	}

	/**
	 * 継続課金会員リスト「有効期限」
	 * dlseller_filter_continue_member_list_limitofcard
	 *
	 * @param  string $limitofcard Card expiration date.
	 * @param  int    $member_id Member ID.
	 * @param  int    $order_id Order number.
	 * @param  array  $meta_data Continuation data.
	 * @return string
	 */
	public function continue_member_list_limitofcard( $limitofcard, $member_id, $order_id, $meta_data ) {
		if ( isset( $meta_data['acting'] ) ) {
			if ( version_compare( WCEX_DLSELLER_VERSION, '3.0-beta', '<=' ) ) {
				$payment = usces_get_payments_by_name( $meta_data['acting'] );
				$acting  = $payment['settlement'];
			} else {
				$acting = $meta_data['acting'];
			}
			if ( 'acting_welcart_card' !== $acting ) {
				return $limitofcard;
			}

			$acting_opts = $this->get_acting_settings();
			if ( 'on' !== $acting_opts['quickpay'] ) {
				return $limitofcard;
			}

			$kaiin_id   = $this->get_quick_kaiin_id( $member_id );
			$kaiin_pass = $this->get_quick_pass( $member_id );

			if ( ! empty( $kaiin_id ) && ! empty( $kaiin_pass ) ) {
				/* e-SCOTT 会員照会 */
				$response_member = $this->escott_member_reference( $member_id, $kaiin_id, $kaiin_pass );
				if ( 'OK' === $response_member['ResponseCd'] ) {
					$expyy       = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 2 ) . substr( $response_member['CardExp'], 0, 2 );
					$expmm       = substr( $response_member['CardExp'], 2, 2 );
					$limit       = $expyy . $expmm;
					$now         = date_i18n( 'Ym', current_time( 'timestamp', 0 ) );
					$limitofcard = $expmm . '/' . substr( $response_member['CardExp'], 0, 2 );
					if ( $limit <= $now ) {
						$limitofcard .= '<br /><a href="javascript:void(0)" onClick="uscesMail.getMailData( \'' . $member_id . '\', \'' . $order_id . '\' )">' . __( 'Update Request Email', 'dlseller' ) . '</a>';
					}
				}
			} else {
				$limitofcard = '';
			}
		}
		return $limitofcard;
	}

	/**
	 * 継続課金会員リスト「契約」
	 * dlseller_filter_continue_member_list_continue_status
	 *
	 * @param  string $status Continuation status.
	 * @param  int    $member_id Member ID.
	 * @param  int    $order_id Order number.
	 * @param  array  $meta_data Continuation data.
	 * @return string
	 */
	public function continue_member_list_continue_status( $status, $member_id, $order_id, $meta_data ) {
		return $status;
	}

	/**
	 * 継続課金会員リスト「状態」
	 * dlseller_filter_continue_member_list_condition
	 *
	 * @param  string $condition Continuation condition.
	 * @param  int    $member_id Member ID.
	 * @param  int    $order_id Order number.
	 * @param  array  $meta_data Continuation data.
	 * @return string
	 */
	public function continue_member_list_condition( $condition, $member_id, $order_id, $meta_data ) {
		global $usces;

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		$payment    = $usces->getPayments( $order_data['order_payment_name'] );
		if ( isset( $payment['settlement'] ) && 'acting_welcart_card' === $payment['settlement'] ) {
			$url       = admin_url( 'admin.php?page=usces_continue&continue_action=settlement&member_id=' . $member_id . '&order_id=' . $order_id );
			$condition = '<a href="' . $url . '">' . __( 'Detail', 'usces' ) . '</a>';

			if ( 'continuation' === $meta_data['status'] ) {
				$status = $this->get_latest_status( $member_id, $order_id );
				if ( ! empty( $status ) && 'OK' !== $status ) {
					$condition .= '<div class="acting-status card-error">' . __( 'Settlement error', 'usces' ) . '</div>';
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
		if ( 'settlement' === $continue_action ) {
			$member_id = filter_input( INPUT_GET, 'member_id' );
			$order_id  = filter_input( INPUT_GET, 'order_id' );
			if ( ! is_null( $member_id ) && ! is_null( $order_id ) ) {
				$this->continue_member_settlement_info_page( $member_id, $order_id );
				exit();
			}
		}
	}

	/**
	 * 継続課金会員決済状況ページ
	 *
	 * @param  int $member_id Member ID.
	 * @param  int $order_id Order number.
	 */
	public function continue_member_settlement_info_page( $member_id, $order_id ) {
		global $usces;

		if ( version_compare( WCEX_DLSELLER_VERSION, '3.0-beta', '<=' ) ) {
			$continue_data = usces_unserialize( $usces->get_member_meta_value( 'continuepay_' . $order_id, $member_id ) );
		} else {
			$continue_data = $this->get_continuation_data( $order_id, $member_id );
		}
		$curent_url = $_SERVER['REQUEST_URI'];
		$navibutton = '<a href="' . esc_url( $_SERVER['HTTP_REFERER'] ) . '" class="back-list"><span class="dashicons dashicons-list-view"></span>' . __( 'Back to the continue members list', 'dlseller' ) . '</a>';

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		if ( ! $order_data ) {
			return;
		}

		$member_info = $usces->get_member_info( $member_id );
		$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
		$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_card', $order_id ) );

		$payment = $usces->getPayments( $order_data['order_payment_name'] );
		if ( 'acting_welcart_card' !== $payment['settlement'] ) {
			return;
		}

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
		$year             = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 4 );
		$total_full_price = $order_data['order_item_total_price'] - $order_data['order_usedpoint'] + $order_data['order_discount'] + $order_data['order_shipping_charge'] + $order_data['order_cod_fee'] + $order_data['order_tax'];

		$log_data = $this->get_acting_log( $order_id );
		$num      = ( $log_data ) ? count( $log_data ) + 1 : 1;

		$kaiin_id = $this->get_quick_kaiin_id( $member_id );
		$card     = ( empty( $kaiin_id ) ) ? '&nbsp;<span id="settlement-status"><span class="acting-status card-error">' . __( 'Card unregistered', 'usces' ) . '</span></span>' : '';
		?>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Management <?php esc_html_e( 'Continuation charging member information', 'dlseller' ); ?></h1>
<p class="version_info">Version <?php echo esc_html( WCEX_DLSELLER_VERSION ); ?></p>
		<?php usces_admin_action_status(); ?>
<div class="edit_pagenav"><?php echo wp_kses_post( $navibutton ); ?></div>
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
				<td><?php echo esc_html( $member_id ); ?><?php echo wp_kses_post( $card ); ?></td>
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
					<select id="contracted-year">
						<option value="0"<?php selected( (int) $contracted_year, 0 ); ?>></option>
		<?php for ( $i = 0; $i <= 10; $i++ ) : ?>
						<option value="<?php echo esc_attr( $year + $i ); ?>"<?php selected( (int) $contracted_year, ( (int) $year + $i ) ); ?>><?php echo esc_html( (int) $year + $i ); ?></option>
		<?php endfor; ?>
					</select>-<select id="contracted-month">
						<option value="0"<?php selected( (int) $contracted_month, 0 ); ?>></option>
		<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
						<option value="<?php printf( '%02d', $i ); ?>"<?php selected( (int) $contracted_month, $i ); ?>><?php printf( '%2d', $i ); ?></option>
		<?php endfor; ?>
					</select>-<select id="contracted-day">
						<option value="0"<?php selected( $contracted_day, 0 ); ?>></option>
		<?php for ( $i = 1; $i <= 31; $i++ ) : ?>
						<option value="<?php printf( '%02d', $i ); ?>"<?php selected( (int) $contracted_day, $i ); ?>><?php printf( '%2d', $i ); ?></option>
		<?php endfor; ?>
					</select>
				</td>
				<th><?php esc_html_e( 'Next Withdrawal Date', 'dlseller' ); ?></th>
				<td>
					<select id="charged-year">
						<option value="0"<?php selected( (int) $charged_year, 0 ); ?>></option>
						<option value="<?php echo esc_attr( $year ); ?>"<?php selected( (int) $charged_year, (int) $year ); ?>><?php echo esc_html( $year ); ?></option>
						<option value="<?php echo esc_attr( $year + 1 ); ?>"<?php selected( (int) $charged_year, ( (int) $year + 1 ) ); ?>><?php echo esc_html( (int) $year + 1 ); ?></option>
					</select>-<select id="charged-month">
						<option value="0"<?php selected( (int) $charged_month, 0 ); ?>></option>
		<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
						<option value="<?php printf( '%02d', $i ); ?>"<?php selected( (int) $charged_month, $i ); ?>><?php printf( '%2d', $i ); ?></option>
		<?php endfor; ?>
					</select>-<select id="charged-day">
						<option value="0"<?php selected( $charged_day, 0 ); ?>></option>
		<?php for ( $i = 1; $i <= 31; $i++ ) : ?>
						<option value="<?php printf( '%02d', $i ); ?>"<?php selected( (int) $charged_day, $i ); ?>><?php printf( '%2d', $i ); ?></option>
		<?php endfor; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Amount on order', 'usces' ); ?></th>
				<td><?php usces_crform( $continue_data['order_price'], false ); ?></td>
				<th><?php esc_html_e( 'Settlement amount', 'usces' ); ?></th>
				<td><input type="text" id="price" style="text-align: right;" value="<?php usces_crform( $continue_data['price'], false, false, '', false ); ?>"><?php usces_crcode(); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Status', 'dlseller' ); ?></th>
				<td><select id="dlseller-status">
		<?php
		ob_start();
		if ( 'continuation' === $continue_data['status'] ) :
			?>
					<option value="continuation" selected="selected"><?php esc_html_e( 'Continuation', 'dlseller' ); ?></option>
					<option value="cancellation"><?php esc_html_e( 'Stop', 'dlseller' ); ?></option>
			<?php
		else :
			?>
				<option value="cancellation" selected="selected"><?php esc_html_e( 'Cancellation', 'dlseller' ); ?></option>
				<option value="continuation"><?php esc_html_e( 'Resumption', 'dlseller' ); ?></option>
			<?php
		endif;
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
		<th scope="col"><?php esc_html_e( 'Transaction ID', 'usces' ); ?></th>
		<th scope="col"><?php esc_html_e( 'Settlement amount', 'usces' ); ?></th>
		<th scope="col"><?php esc_html_e( 'Processing classification', 'usces' ); ?></th>
		<th scope="col">&nbsp;</th>
	</tr>
	</thead>
		<?php
		foreach ( (array) $log_data as $log_row ) :
			$log = $this->get_acting_latest_log( $log_row['log_key'], 'ALL' );
			if ( isset( $log['OperateId'] ) && isset( $log['ResponseCd'] ) && 'OK' === $log['ResponseCd'] ) {
				$class         = ' card-' . mb_strtolower( substr( $log['OperateId'], 1 ) );
				$status_name   = $this->get_operate_name( $log['OperateId'] );
				$merchantfree1 = $log['MerchantFree1'];
				$responsecd    = '';
				$amount        = ( isset( $log['Amount'] ) ) ? usces_crform( $log['Amount'], false, true, 'return', true ) : '';
			} else {
				$class       = ' card-error';
				$status_name = __( 'Settlement error', 'usces' );
				if ( isset( $log_row['log'] ) ) {
					$log = usces_unserialize( $log_row['log'] );
					if ( isset( $log['MerchantFree1'] ) ) {
						$merchantfree1 = $log['MerchantFree1'];
					} else {
						$log_key       = explode( '_', $log_row['log_key'] );
						$merchantfree1 = ( isset( $log_key[1] ) ) ? $log_key[1] : '9999999999';
					}
					$responsecd = $log['ResponseCd'];
					$amount     = ( isset( $log['Amount'] ) ) ? usces_crform( $log['Amount'], false, true, 'return', true ) : '';
				} else {
					$log_key       = explode( '_', $log_row['log_key'] );
					$merchantfree1 = ( isset( $log_key[1] ) ) ? $log_key[1] : '9999999999';
					$responsecd    = 'NG';
					$amount        = '';
				}
			}
			?>
	<tbody>
	<tr>
		<td><?php echo esc_html( $num ); ?></td>
		<td><?php echo esc_html( $log_row['datetime'] ); ?></td>
		<td><?php echo esc_html( $merchantfree1 ); ?></td>
		<td class="amount"><?php echo esc_html( $amount ); ?></td>
			<?php
			if ( ! empty( $status_name ) ) :
				?>
		<td><span id="settlement-status-<?php echo esc_attr( $num ); ?>"><span class="acting-status<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $status_name ); ?></span></span></td>
		<td>
			<input type="button" id="settlement-information-<?php echo esc_attr( $merchantfree1 ); ?>-<?php echo esc_attr( $num ); ?>" class="button settlement-information" value="<?php esc_attr_e( 'Settlement info', 'usces' ); ?>">
			<input type="hidden" id="responsecd-<?php echo esc_attr( $merchantfree1 ); ?>-<?php echo esc_attr( $num ); ?>" value="<?php echo esc_attr( $responsecd ); ?>">
		</td>
				<?php
			else :
				?>
		<td>&nbsp;</td><td>&nbsp;</td>
				<?php
			endif;
			?>
	</tr>
	</tbody>
			<?php
			$num--;
		endforeach;
		$trans_id   = $usces->get_order_meta_value( 'trans_id', $order_id );
		$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id, 'ALL' );
		if ( $latest_log ) :
			$class       = ' card-' . mb_strtolower( substr( $latest_log['OperateId'], 1 ) );
			$status_name = $this->get_operate_name( $latest_log['OperateId'] );
			if ( isset( $latest_log['Amount'] ) ) {
				$amount = usces_crform( $latest_log['Amount'], false, true, 'return', true );
			} elseif ( isset( $continue_data['order_price'] ) ) {
				$amount = usces_crform( $continue_data['order_price'], false, true, 'return', true );
			} else {
				$amount = '';
			}
			?>
	<tbody>
	<tr>
		<td>1</td>
		<td><?php echo esc_html( $order_data['order_date'] ); ?></td>
		<td><?php echo esc_html( $trans_id ); ?></td>
		<td class="amount"><?php echo esc_html( $amount ); ?></td>
			<?php
			if ( ! empty( $status_name ) ) :
				?>
		<td><span id="settlement-status-1"><span class="acting-status<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $status_name ); ?></span></span></td>
		<td><input type="button" id="settlement-information-<?php echo esc_attr( $trans_id ); ?>-1" class="button settlement-information" value="<?php esc_attr_e( 'Settlement info', 'usces' ); ?>"></td>
				<?php
			else :
				?>
		<td>&nbsp;</td><td>&nbsp;</td>
				<?php
			endif;
			?>
	</tr>
	</tbody>
			<?php
		endif;
		?>
</table>
</div><!--datatable-->
<input name="member_id" type="hidden" id="member_id" value="<?php echo esc_attr( $member_id ); ?>" />
<input name="order_id" type="hidden" id="order_id" value="<?php echo esc_attr( $order_id ); ?>" />
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
	 * 継続課金会員クレジットカード変更依頼メール
	 * dlseller_filter_card_update_mail
	 *
	 * @param  string $message Message.
	 * @param  int    $member_id Member ID.
	 * @param  int    $order_id Order number.
	 * @return string
	 */
	public function continue_member_card_update_mail( $message, $member_id, $order_id ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( ! usces_is_membersystem_state() || 'on' !== $acting_opts['quickpay'] ) {
			return $message;
		}

		$kaiin_id   = $this->get_quick_kaiin_id( $member_id );
		$kaiin_pass = $this->get_quick_pass( $member_id );

		if ( ! empty( $kaiin_id ) && ! empty( $kaiin_pass ) ) {
			/* e-SCOTT 会員照会 */
			$response_member = $this->escott_member_reference( $member_id, $kaiin_id, $kaiin_pass );
			if ( 'OK' === $response_member['ResponseCd'] ) {
				$expyy = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 2 ) . substr( $response_member['CardExp'], 0, 2 );
				$expmm = substr( $response_member['CardExp'], 2, 2 );

				$now         = date_i18n( 'Ym', current_time( 'timestamp', 0 ) );
				$member_info = $usces->get_member_info( $member_id );
				$mail_data   = usces_mail_data();

				$nonsessionurl = usces_url( 'cartnonsession', 'return' );
				$parts         = parse_url( $nonsessionurl );
				if ( isset( $parts['query'] ) ) {
					parse_str( $parts['query'], $query );
				}
				if ( false !== strpos( $nonsessionurl, '/usces-cart' ) ) {
					$nonsessionurl = str_replace( '/usces-cart', '/usces-member', $nonsessionurl );
				} elseif ( isset( $query['page_id'] ) && USCES_CART_NUMBER === $query['page_id'] ) {
					$nonsessionurl = str_replace( 'page_id=' . USCES_CART_NUMBER, 'page_id=' . USCES_MEMBER_NUMBER, $nonsessionurl );
				}
				$delim = ( false === strpos( $nonsessionurl, '?' ) ) ? '?' : '&';

				$regd = $expyy . $expmm;
				if ( $regd === $now ) {
					$flag = 'NOW';
				} elseif ( $regd < $now ) {
					$flag = 'PASSED';
				} else {
					return $message;
				}

				$exp   = mktime( 0, 0, 0, $expmm, 1, $expyy );
				$limit = date_i18n( __( 'F, Y' ), $exp );
				$name  = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );

				$message  = __( 'Member ID', 'dlseller' ) . ' : ' . $member_id . "\n";
				$message .= __( 'Contractor name', 'dlseller' ) . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), $name ) . "\n\n\n";
				$message .= __( 'Thank you very much for using our service.', 'dlseller' ) . "\r\n\r\n";
				$message .= __( 'Please be sure to check this notification because it is an important contact for continued use of the service under contract.', 'dlseller' ) . "\r\n\r\n";
				$message .= __( '---------------------------------------------------------', 'dlseller' ) . "\r\n";
				$message .= sprintf( __( 'Currently registered credit card expiration date is %s, ', 'dlseller' ), $limit ) . "\r\n";
				if ( 'NOW' === $flag ) {
					$message .= __( 'So you keep on this you will not be able to pay next month.', 'dlseller' ) . "\r\n";
				} else {
					$message .= __( 'So your payment of this month is outstanding payment.', 'dlseller' ) . "\r\n";
				}
				$message .= __( '---------------------------------------------------------', 'dlseller' ) . "\r\n\r\n";
				$message .= __( 'If you have received a new credit card, ', 'dlseller' ) . "\r\n";
				$message .= __( 'Please click the URL below and update the card information during this month.', 'dlseller' ) . "\r\n";
				$message .= __( 'Sorry for troubling you, please process it.', 'dlseller' ) . "\r\n\r\n\r\n";
				$message .= $nonsessionurl . $delim . 'dlseller_card_update=login&dlseller_up_mode=1&dlseller_order_id=' . $order_id . "\r\n";
				$message .= __( 'If the card information update procedure failed, please contact us at the following email address.', 'dlseller' ) . "\r\n\r\n";
				$message .= __( 'Thank you.', 'dlseller' ) . "\r\n\r\n\r\n";
				$message .= $mail_data['footer']['ordermail'];
				$message  = apply_filters( 'usces_filter_continue_member_card_update_mail', $message, $member_id, $member_info );
				if ( function_exists( 'usces_is_html_mail' ) && usces_is_html_mail() ) {
					$message = do_shortcode( wpautop( $message ) );
				}
			}
		}
		return $message;
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
		if ( ! usces_is_membersystem_state() || 'on' !== $acting_opts['quickpay'] ) {
			return;
		}

		if ( 0 >= $continue_data['price'] ) {
			return;
		}

		$order_data = $usces->get_order_data( $order_id, 'direct' );
		if ( ! $order_data || $usces->is_status( 'cancel', $order_data['order_status'] ) ) {
			return;
		}

		$payment = $usces->getPayments( $order_data['order_payment_name'] );
		if ( 'acting_welcart_card' !== $payment['settlement'] ) {
			return;
		}

		$kaiin_id   = $this->get_quick_kaiin_id( $member_id );
		$kaiin_pass = $this->get_quick_pass( $member_id );
		$rand       = usces_acting_key();

		if ( ! empty( $kaiin_id ) && ! empty( $kaiin_pass ) ) {
			$transaction_date = $this->get_transaction_date();
			$param_list       = array();
			$params_member    = array();
			$params           = array();

			/* 共通部 */
			$param_list['MerchantId']      = $acting_opts['merchant_id'];
			$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
			$param_list['TransactionDate'] = $transaction_date;
			$param_list['MerchantFree1']   = $rand;
			$param_list['MerchantFree2']   = $payment['settlement'];
			$param_list['MerchantFree3']   = $this->merchantfree3;
			$param_list['TenantId']        = $acting_opts['tenant_id'];
			$params_member['send_url']     = $acting_opts['send_url_member'];
			$params_member['param_list']   = array_merge(
				$param_list,
				array(
					'OperateId' => '4MemRefM',
					'KaiinId'   => $kaiin_id,
					'KaiinPass' => $kaiin_pass,
				)
			);
			/* e-SCOTT 会員照会 */
			$response_member = $this->connection( $params_member );
			if ( 'OK' === $response_member['ResponseCd'] ) {
				$params['send_url']   = $acting_opts['send_url'];
				$params['param_list'] = array_merge(
					$param_list,
					array(
						'OperateId' => $acting_opts['operateid_dlseller'],
						'Amount'    => usces_crform( $continue_data['price'], false, false, 'return', false ),
						'PayType'   => '01',
						'KaiinId'   => $kaiin_id,
						'KaiinPass' => $kaiin_pass,
					)
				);
				/* e-SCOTT 決済 */
				$response_data = $this->connection( $params );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $continue_data['price'];
				}
				$response_data = apply_filters( 'usces_filter_escott_auto_continuation_charging_history_log', $response_data, $member_id, $order_id, $continue_data );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $rand );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$cardlast4                = substr( $response_member['CardNo'], -4 );
					$expyy                    = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 2 ) . substr( $response_member['CardExp'], 0, 2 );
					$expmm                    = substr( $response_member['CardExp'], 2, 2 );
					$response_data['acting']  = $this->acting_card;
					$response_data['PayType'] = '01';
					$response_data['CardNo']  = $cardlast4;
					$response_data['CardExp'] = $expyy . '/' . $expmm;
					$this->save_acting_log( $response_data, $order_id . '_' . $rand );
					$this->auto_settlement_mail( $member_id, $order_id, $response_data, $continue_data );
				} else {
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$response_data[ $cd ] = $this->response_message( $cd );
					}
					$log = array(
						'acting' => $this->acting_card,
						'key'    => $rand,
						'result' => $response_data['ResponseCd'],
						'data'   => $response_data,
					);
					usces_save_order_acting_error( $log );
					$this->save_acting_log( $response_data, $order_id . '_' . $rand );
					$this->auto_settlement_error_mail( $member_id, $order_id, $response_data, $continue_data );
				}
				do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $response_data );
			} else {
				if ( ! isset( $response_member['Amount'] ) ) {
					$response_member['Amount'] = $continue_data['price'];
				}
				$response_member = apply_filters( 'usces_filter_escott_auto_continuation_charging_history_log', $response_member, $member_id, $order_id, $continue_data );
				$this->save_acting_history_log( $response_member, $order_id . '_' . $rand );
				$responsecd = explode( '|', $response_member['ResponseCd'] );
				foreach ( (array) $responsecd as $cd ) {
					$response_member[ $cd ] = $this->response_message( $cd );
				}
				$log = array(
					'acting' => $this->acting_card . '(member_process)',
					'key'    => $member_id,
					'result' => $response_member['ResponseCd'],
					'data'   => $response_member,
				);
				usces_save_order_acting_error( $log );
				$this->save_acting_log( $response_member, $order_id . '_' . $rand );
				$this->auto_settlement_error_mail( $member_id, $order_id, $response_member, $continue_data );
				do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $response_member );
			}
		} else {
			$logdata = array(
				'KaiinId'   => $kaiin_id,
				'KaiinPass' => $kaiin_pass,
			);
			$log     = array(
				'acting' => $this->acting_card . '(member_process)',
				'key'    => $member_id,
				'result' => 'MEMBER ERROR',
				'data'   => $logdata,
			);
			usces_save_order_acting_error( $log );
			$log['ResponseCd']    = 'NG';
			$log['MerchantFree1'] = $rand;
			$this->save_acting_log( $log, $order_id . '_' . $rand );
			$this->auto_settlement_error_mail( $member_id, $order_id, $logdata, $continue_data );
			do_action( 'usces_action_auto_continuation_charging', $member_id, $order_id, $continue_data, $log );
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
			$subject     = apply_filters( 'usces_filter_escott_auto_settlement_mail_subject', __( 'Announcement of automatic continuing charging process', 'usces' ), $member_id, $order_id, $order_data, $response_data, $continue_data );
			$member_info = $usces->get_member_info( $member_id );
			$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$mail_data   = usces_mail_data();
			$mail_header = '';
			if ( isset( $usces->options['put_customer_name'] ) && 1 === (int) $usces->options['put_customer_name'] ) {
				$mail_header .= sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n";
			}
			$mail_header .= __( 'We will report automated accounting process was carried out as follows.', 'usces' ) . "\r\n\r\n";
			$mail_footer  = __( 'If you have any questions, please contact us.', 'usces' ) . "\r\n\r\n" . $mail_data['footer']['thankyou'];
			$message      = apply_filters( 'usces_filter_escott_auto_settlement_mail_header', $mail_header, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_escott_auto_settlement_mail_body', $mail_body, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_escott_auto_settlement_mail_footer', $mail_footer, $member_id, $order_id, $order_data, $response_data, $continue_data );
			$headers      = apply_filters( 'usces_filter_escott_auto_settlement_mail_headers', '' );
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
			$subject     = apply_filters( 'usces_filter_escott_auto_settlement_error_mail_subject', __( 'Announcement of automatic continuing charging process', 'usces' ), $member_id, $order_id, $order_data, $response_data, $continue_data );
			$member_info = $usces->get_member_info( $member_id );
			$name        = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$mail_data   = usces_mail_data();
			$mail_header = '';
			if ( isset( $usces->options['put_customer_name'] ) && 1 === (int) $usces->options['put_customer_name'] ) {
				$mail_header .= sprintf( __( 'Dear %s', 'usces' ), $name ) . "\r\n\r\n";
			}
			$mail_header .= __( 'We will reported that an error occurred in automated accounting process.', 'usces' ) . "\r\n\r\n";
			$mail_footer  = __( 'If you have any questions, please contact us.', 'usces' ) . "\r\n\r\n" . $mail_data['footer']['thankyou'];
			$message      = apply_filters( 'usces_filter_escott_auto_settlement_error_mail_header', $mail_header, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_escott_auto_settlement_error_mail_body', $mail_body, $member_id, $order_id, $order_data, $response_data, $continue_data ) .
				apply_filters( 'usces_filter_escott_auto_settlement_error_mail_footer', $mail_footer, $member_id, $order_id, $order_data, $response_data, $continue_data );
			$headers      = apply_filters( 'usces_filter_escott_auto_settlement_error_mail_headers', '' );
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
	 * @param  array   $response_data Response data.
	 * @param  array   $continue_data Continuation data.
	 * @param  boolean $html HTML Mail.
	 * @return string
	 */
	public function auto_settlement_message( $member_id, $order_id, $order_data, $response_data, $continue_data, $html = true ) {
		global $usces;

		if ( usces_is_html_mail() && $html ) {
			$message = $this->auto_settlement_message_htmlbody( $member_id, $order_id, $order_data, $response_data, $continue_data );
		} else {
			$member_info     = $usces->get_member_info( $member_id );
			$name            = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
			$contracted_date = ( isset( $continue_data['contractedday'] ) ) ? $continue_data['contractedday'] : '';
			$charged_date    = ( isset( $continue_data['chargedday'] ) ) ? $continue_data['chargedday'] : '';

			$message     = usces_mail_line( 2 );
			$message    .= __( 'Order ID', 'dlseller' ) . ' : ' . $order_id . "\r\n";
			$message    .= __( 'Application Date', 'dlseller' ) . ' : ' . $order_data['order_date'] . "\r\n";
			$message    .= __( 'Member ID', 'dlseller' ) . ' : ' . $member_id . "\r\n";
			$message    .= __( 'Contractor name', 'dlseller' ) . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), $name ) . "\r\n";

			$cart      = usces_get_ordercartdata( $order_id );
			$cart_row  = current( $cart );
			$item_name = $usces->getCartItemName_byOrder( $cart_row );
			$options   = ( empty( $cart_row['options'] ) ) ? array() : $cart_row['options'];
			$message  .= __( 'Items', 'usces' ) . ' : ' . $item_name . "\r\n";
			if ( is_array( $options ) && count( $options ) > 0 ) {
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
			if ( isset( $response_data['reminder'] ) ) {
				if ( ! empty( $charged_date ) ) {
					$message .= __( 'Next Withdrawal Date', 'dlseller' ) . ' : ' . $charged_date . "\r\n";
				}
				if ( ! empty( $contracted_date ) ) {
					$message .= __( 'Renewal Date', 'dlseller' ) . ' : ' . $contracted_date . "\r\n";
				}
			} else {
				if ( isset( $response_data['MerchantFree1'] ) ) {
					$message .= __( 'Transaction ID', 'usces' ) . ' : ' . $response_data['MerchantFree1'] . "\r\n";
				}
				if ( isset( $response_data['TransactionId'] ) ) {
					$message .= __( 'Sequence number', 'usces' ) . ' : ' . $response_data['TransactionId'] . "\r\n";
				}
				if ( ! empty( $charged_date ) ) {
					$message .= __( 'Next Withdrawal Date', 'dlseller' ) . ' : ' . $charged_date . "\r\n";
				}
				if ( ! empty( $contracted_date ) ) {
					$message .= __( 'Renewal Date', 'dlseller' ) . ' : ' . $contracted_date . "\r\n";
				}
				$message .= "\r\n";
				if ( isset( $response_data['ResponseCd'] ) ) {
					if ( 'OK' === $response_data['ResponseCd'] ) {
						$message .= __( 'Result', 'usces' ) . ' : ' . __( 'Normal done', 'usces' ) . "\r\n";
					} else {
						$message   .= __( 'Result', 'usces' ) . ' : ' . __( 'Error', 'usces' ) . "\r\n";
						$responsecd = explode( '|', $response_data['ResponseCd'] );
						foreach ( (array) $responsecd as $cd ) {
							$message .= $cd . ' : ' . $this->response_message( $cd ) . "\r\n";
						}
					}
				} else {
					$message .= __( 'Result', 'usces' ) . ' : ' . __( 'Error', 'usces' ) . "\r\n";
					$message .= __( 'Credit card is not registered.', 'usces' ) . "\r\n";
				}
			}
			$message .= usces_mail_line( 2 ) . "\r\n";
		}
		return $message;
	}

	/**
	 * Automatic renewal billing process email body html
	 *
	 * @param  int   $member_id Member ID.
	 * @param  int   $order_id Order number.
	 * @param  array $order_data Order data.
	 * @param  array $response_data Response data.
	 * @param  array $continue_data Continuation data.
	 * @return string
	 */
	public function auto_settlement_message_htmlbody( $member_id, $order_id, $order_data, $response_data, $continue_data ) {
		global $usces;

		$member_info     = $usces->get_member_info( $member_id );
		$name            = usces_localized_name( $member_info['mem_name1'], $member_info['mem_name2'], 'return' );
		$contracted_date = ( isset( $continue_data['contractedday'] ) ) ? $continue_data['contractedday'] : '';
		$charged_date    = ( isset( $continue_data['chargedday'] ) ) ? $continue_data['chargedday'] : '';

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

		$cart      = usces_get_ordercartdata( $order_id );
		$cart_row  = current( $cart );
		$item_name = $usces->getCartItemName_byOrder( $cart_row );
		$options   = ( empty( $cart_row['options'] ) ) ? array() : $cart_row['options'];
		$message  .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
		$message  .= __( 'Items', 'usces' );
		$message  .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
		$message  .= $item_name;
		if ( is_array( $options ) && count( $options ) > 0 ) {
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
		if ( isset( $response_data['reminder'] ) ) {
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
			if ( isset( $response_data['MerchantFree1'] ) ) {
				$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
				$message .= __( 'Transaction ID', 'usces' );
				$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
				$message .= $response_data['MerchantFree1'];
				$message .= '</td></tr>';
			}
			if ( isset( $response_data['TransactionId'] ) ) {
				$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
				$message .= __( 'Sequence number', 'usces' );
				$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
				$message .= $response_data['TransactionId'];
				$message .= '</td></tr>';
			}
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
			if ( isset( $response_data['ResponseCd'] ) ) {
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message .= __( 'Result', 'usces' );
					$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message .= __( 'Normal done', 'usces' );
					$message .= '</td></tr>';
				} else {
					$message   .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
					$message   .= __( 'Result', 'usces' );
					$message   .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
					$message   .= __( 'Error', 'usces' );
					$responsecd = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$message .= $cd . ' : ' . $this->response_message( $cd ) . '<br>';
					}
					$message .= '</td></tr>';
				}
			} else {
				$message .= '<tr><td style="background-color: #f9f9f9; padding: 12px; width: 33%; border: 1px solid #ddd; text-align: left;">';
				$message .= __( 'Result', 'usces' );
				$message .= '</td><td style="padding: 12px; width: 67%; border: 1px solid #ddd;">';
				$message .= __( 'Error', 'usces' ) . '<br>';
				$message .= __( 'Credit card is not registered.', 'usces' );
				$message .= '</td></tr>';
			}
		}
		$message .= '</tbody></table>';
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
		$admin_subject = apply_filters( 'usces_filter_escott_auto_settlement_mail_admin_subject', __( 'Automatic Continuing Charging Process Result', 'usces' ) . ' ' . $today, $today );
		$admin_footer  = apply_filters( 'usces_filter_escott_auto_settlement_mail_admin_mail_footer', __( 'For details, please check on the administration panel > Continuous charge member list > Continuous charge member information.', 'usces' ) );
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
	 */
	public function reminder_mail_body( $mail_body, $order_id, $continue_data ) {
		global $usces;

		$member_id     = $continue_data['member_id'];
		$order_id      = $continue_data['order_id'];
		$order_data    = $usces->get_order_data( $order_id, 'direct' );
		$response_data = array( 'reminder' => 'reminder' );
		$mail_body     = $this->auto_settlement_message( $member_id, $order_id, $order_data, $response_data, $continue_data );
		return $mail_body;
	}

	/**
	 * 契約更新日通知メール
	 * dlseller_filter_contract_renewal_mail_body
	 *
	 * @param  string $mail_body Message body.
	 * @param  int    $order_id Order number.
	 * @param  array  $continue_data Continuation data.
	 */
	public function contract_renewal_mail_body( $mail_body, $order_id, $continue_data ) {
		global $usces;

		$member_id     = $continue_data['member_id'];
		$order_data    = $usces->get_order_data( $order_id, 'direct' );
		$response_data = array( 'reminder' => 'contract_renewal' );
		$mail_body     = $this->auto_settlement_message( $member_id, $order_id, $order_data, $response_data, $continue_data );
		return $mail_body;
	}

	/**
	 * 利用可能な支払方法
	 * wcad_filter_available_regular_payment_method
	 *
	 * @param  array $payment_method Payment method.
	 * @return array
	 */
	public function available_regular_payment_method( $payment_method ) {
		$acting_opts = $this->get_acting_settings();
		if ( isset( $acting_opts['quickpay'] ) && 'on' === $acting_opts['quickpay'] ) {
			$payment_method[] = 'acting_welcart_card';
		}
		return $payment_method;
	}

	/**
	 * 発送先リスト利用可能決済
	 * wcad_filter_shippinglist_acting
	 *
	 * @param  string $acting Payment method.
	 * @return string
	 */
	public function set_shippinglist_acting( $acting ) {
		$acting = 'acting_welcart_card';
		return $acting;
	}

	/**
	 * 定期購入決済処理
	 * wcad_action_reg_auto_orderdata
	 *
	 * @param  array $args Compact array( $cart, $entry, $order_id, $member_id, $payments, $charging_type, $total_amount, $reg_id, $order_date ).
	 */
	public function register_auto_orderdata( $args ) {
		global $usces;
		extract( $args ); // phpcs:ignore

		$acting_opts = $this->get_acting_settings();
		if ( ! usces_is_membersystem_state() || 'on' !== $acting_opts['quickpay'] ) {
			return;
		}

		if ( 0 >= $total_amount ) {
			return;
		}

		$acting_flg = $payments['settlement'];
		if ( 'acting_welcart_card' !== $payments['settlement'] ) {
			return;
		}

		$settltment_errmsg = '';
		$kaiin_id          = $this->get_quick_kaiin_id( $member_id );
		$kaiin_pass        = $this->get_quick_pass( $member_id );
		$rand              = usces_acting_key();

		if ( ! empty( $kaiin_id ) && ! empty( $kaiin_pass ) ) {
			$transaction_date = $this->get_transaction_date();
			$param_list       = array();
			$params_member    = array();
			$params           = array();

			/* 共通部 */
			$param_list['MerchantId']      = $acting_opts['merchant_id'];
			$param_list['MerchantPass']    = $acting_opts['merchant_pass'];
			$param_list['TransactionDate'] = $transaction_date;
			$param_list['MerchantFree1']   = $rand;
			$param_list['MerchantFree2']   = 'acting_welcart_card';
			$param_list['MerchantFree3']   = $this->merchantfree3;
			$param_list['TenantId']        = $acting_opts['tenant_id'];
			$params_member['send_url']     = $acting_opts['send_url_member'];
			$params_member['param_list']   = array_merge(
				$param_list,
				array(
					'OperateId' => '4MemRefM',
					'KaiinId'   => $kaiin_id,
					'KaiinPass' => $kaiin_pass,
				)
			);
			/* e-SCOTT 会員照会 */
			$response_member = $this->connection( $params_member );
			if ( 'OK' === $response_member['ResponseCd'] ) {
				$params['send_url']   = $acting_opts['send_url'];
				$params['param_list'] = array_merge(
					$param_list,
					array(
						'OperateId' => $acting_opts['operateid'],
						'Amount'    => $total_amount,
						'PayType'   => '01',
						'KaiinId'   => $kaiin_id,
						'KaiinPass' => $kaiin_pass,
					)
				);
				/* e-SCOTT 決済 */
				$response_data = $this->connection( $params );
				if ( ! isset( $response_data['Amount'] ) ) {
					$response_data['Amount'] = $total_amount;
				}
				$response_data = apply_filters( 'usces_filter_escott_auto_register_orderdata_history_log', $response_data, $member_id, $order_id );
				$this->save_acting_history_log( $response_data, $order_id . '_' . $rand );
				if ( 'OK' === $response_data['ResponseCd'] ) {
					$usces->set_order_meta_value( 'trans_id', $rand, $order_id );
					$usces->set_order_meta_value( 'wc_trans_id', $rand, $order_id );
					$cardlast4                = substr( $response_member['CardNo'], -4 );
					$expyy                    = substr( date_i18n( 'Y', current_time( 'timestamp' ) ), 0, 2 ) . substr( $response_member['CardExp'], 0, 2 );
					$expmm                    = substr( $response_member['CardExp'], 2, 2 );
					$response_data['acting']  = $this->acting_card;
					$response_data['PayType'] = '01';
					$response_data['CardNo']  = $cardlast4;
					$response_data['CardExp'] = $expyy . '/' . $expmm;
					$usces->set_order_meta_value( $acting_flg, usces_serialize( $response_data ), $order_id );
				} else {
					$settltment_errmsg = __( '[Regular purchase] Settlement was not completed.', 'autodelivery' );
					$responsecd        = explode( '|', $response_data['ResponseCd'] );
					foreach ( (array) $responsecd as $cd ) {
						$response_data[ $cd ] = $this->response_message( $cd );
					}
					$log = array(
						'acting' => $this->acting_card,
						'key'    => $rand,
						'result' => $response_data['ResponseCd'],
						'data'   => $response_data,
					);
					usces_save_order_acting_error( $log );
				}
				do_action( 'usces_action_register_auto_orderdata', $args, $response_data );
			} else {
				if ( ! isset( $response_member['Amount'] ) ) {
					$response_member['Amount'] = $total_amount;
				}
				$response_member = apply_filters( 'usces_filter_escott_auto_register_orderdata_history_log', $response_member, $member_id, $order_id );
				$this->save_acting_history_log( $response_member, $order_id . '_' . $rand );
				$settltment_errmsg = __( '[Regular purchase] Member information acquisition error.', 'autodelivery' );
				$responsecd        = explode( '|', $response_member['ResponseCd'] );
				foreach ( (array) $responsecd as $cd ) {
					$response_member[ $cd ] = $this->response_message( $cd );
				}
				$log = array(
					'acting' => $this->acting_card . '(member_process)',
					'key'    => $member_id,
					'result' => $response_member['ResponseCd'],
					'data'   => $response_member,
				);
				usces_save_order_acting_error( $log );
				do_action( 'usces_action_register_auto_orderdata', $args, $response_member );
			}
		} else {
			$settltment_errmsg = __( '[Regular purchase] Member information acquisition error.', 'autodelivery' );
			$logdata           = array(
				'KaiinId'   => $kaiin_id,
				'KaiinPass' => $kaiin_pass,
			);
			$log               = array(
				'acting' => $this->acting_card . '(member_process)',
				'key'    => $member_id,
				'result' => 'MEMBER ERROR',
				'data'   => $logdata,
			);
			usces_save_order_acting_error( $log );
			do_action( 'usces_action_register_auto_orderdata', $args, $log );
		}
		if ( '' !== $settltment_errmsg ) {
			$settlement = array(
				'settltment_status' => __( 'Failure', 'autodelivery' ),
				'settltment_errmsg' => $settltment_errmsg,
			);
			$usces->set_order_meta_value( $acting_flg, usces_serialize( $settlement ), $order_id );
			wcad_settlement_error_mail( $order_id, $settltment_errmsg );
		}
	}

	/**
	 * 決済ログ出力
	 *
	 * @param  array  $log Log data.
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return boolean
	 */
	private function save_acting_log( $log, $log_key ) {
		global $wpdb;

		$datetime = current_time( 'mysql' );
		$query    = $wpdb->prepare( "INSERT INTO {$wpdb->prefix}usces_log ( `datetime`, `log`, `log_type`, `log_key` ) VALUES ( %s, %s, %s, %s )",
			$datetime,
			usces_serialize( $log ),
			'acting_welcart',
			$log_key
		);
		$res      = $wpdb->query( $query ); // phpcs:ignore
		return $res;
	}

	/**
	 * 決済ログ取得
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return array
	 */
	public function get_acting_log( $order_id, $log_key = '' ) {
		global $wpdb;

		if ( ! empty( $log_key ) ) {
			$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_log WHERE `log_type` = 'acting_welcart' AND `log_key` = %s ORDER BY datetime DESC", $log_key );
		} else {
			$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_log WHERE `log_type` = 'acting_welcart' AND `log_key` LIKE %s ORDER BY datetime DESC", $wpdb->esc_like( $order_id . '_' ) . '%' );
		}
		$log_data = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore
		return $log_data;
	}

	/**
	 * 決済ログ更新
	 *
	 * @param  array  $log Log data.
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return boolean
	 */
	private function update_acting_log( $log, $log_key ) {
		global $wpdb;

		$datetime = current_time( 'mysql' );
		$query    = $wpdb->prepare( "UPDATE {$wpdb->prefix}usces_log SET `datetime` = %s, `log` = %s WHERE `log_type` = %s AND `log_key` = %s",
			$datetime,
			usces_serialize( $log ),
			'acting_welcart',
			$log_key
		);
		$res      = $wpdb->query( $query ); // phpcs:ignore
		return $res;
	}

	/**
	 * 決済履歴ログ出力
	 *
	 * @param  array  $log Log data.
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return boolean
	 */
	private function save_acting_history_log( $log, $log_key ) {
		global $wpdb;

		$datetime = current_time( 'mysql' );
		$query    = $wpdb->prepare( "INSERT INTO {$wpdb->prefix}usces_log ( `datetime`, `log`, `log_type`, `log_key` ) VALUES ( %s, %s, %s, %s )",
			$datetime,
			usces_serialize( $log ),
			'acting_welcart_history',
			$log_key
		);
		$res      = $wpdb->query( $query ); // phpcs:ignore
		return $res;
	}

	/**
	 * 決済履歴ログ取得
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return array
	 */
	public function get_acting_history_log( $log_key ) {
		global $wpdb;

		$query    = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_log WHERE `log_type` = 'acting_welcart_history' AND `log_key` = %s ORDER BY datetime DESC", $log_key );
		$log_data = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore
		return $log_data;
	}

	/**
	 * 決済処理取得
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return string
	 */
	private function get_acting_operateid( $log_key ) {
		$operateid  = '';
		$latest_log = $this->get_acting_latest_log( $log_key, 'OK' );
		if ( isset( $latest_log['OperateId'] ) ) {
			$operateid = $latest_log['OperateId'];
		}
		return $operateid;
	}

	/**
	 * 初回決済処理取得
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return string
	 */
	private function get_acting_first_operateid( $log_key ) {
		global $wpdb;

		$query    = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usces_log WHERE `log_type` = 'acting_welcart_history' AND `log_key` = %s ORDER BY datetime ASC", $log_key );
		$log_data = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore
		if ( $log_data ) {
			$log       = usces_unserialize( $log_data[0]['log'] );
			$operateid = ( isset( $log['OperateId'] ) ) ? $log['OperateId'] : '';
		} else {
			$operateid = '';
		}
		return $operateid;
	}

	/**
	 * 決済履歴
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return string
	 */
	private function settlement_history( $log_key ) {
		$html     = '';
		$log_data = $this->get_acting_history_log( $log_key );
		if ( $log_data ) {
			$num  = count( $log_data );
			$html = '<table class="settlement-history">
				<thead class="settlement-history-head">
					<tr><th></th><th>' . __( 'Processing date', 'usces' ) . '</th><th>' . __( 'Sequence number', 'usces' ) . '</th><th>' . __( 'Processing classification', 'usces' ) . '</th><th>' . __( 'Amount', 'usces' ) . '</th><th>' . __( 'Result', 'usces' ) . '</th></tr>
				</thead>
				<tbody class="settlement-history-body">';
			foreach ( (array) $log_data as $data ) {
				$log          = usces_unserialize( $data['log'] );
				$class        = ( 'OK' !== $log['ResponseCd'] ) ? ' error' : '';
				$operate_name = ( isset( $log['OperateId'] ) ) ? $this->get_operate_name( $log['OperateId'] ) : '';
				$amount       = ( isset( $log['Amount'] ) ) ? usces_crform( $log['Amount'], false, true, 'return', true ) : '';
				$html        .= '<tr>
					<td class="num">' . $num . '</td>
					<td class="datetime">' . $data['datetime'] . '</td>
					<td class="transactionid">' . $log['TransactionId'] . '</td>
					<td class="operateid">' . $operate_name . '</td>
					<td class="amount">' . $amount . '</td>
					<td class="responsecd' . $class . '">' . $log['ResponseCd'] . '</td>
				</tr>';
				$num--;
			}
			$html .= '</tbody>
				</table>';
		}
		return $html;
	}

	/**
	 * 最新処理取得
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @param  string $responsecd ResponseCd.
	 * @return array
	 */
	public function get_acting_latest_log( $log_key, $responsecd = 'OK' ) {
		$latest_log = array();
		$log_data   = $this->get_acting_history_log( $log_key );
		if ( $log_data ) {
			if ( 'OK' === $responsecd ) {
				$reauth = false;
				foreach ( (array) $log_data as $data ) {
					$log = usces_unserialize( $data['log'] );
					if ( isset( $log['ResponseCd'] ) ) {
						if ( 'OK' === $log['ResponseCd'] && in_array( $log['OperateId'], $this->reauth_status ) ) {
							$reauth = true;
						} else {
							if ( $reauth ) {
								if ( 'OK' === $log['ResponseCd'] && in_array( $log['OperateId'], $this->primarily_status ) ) {
									$latest_log = $log;
									break;
								}
							} else {
								if ( 'OK' === $log['ResponseCd'] && in_array( $log['OperateId'], $this->latest_status ) ) {
									$latest_log = $log;
									break;
								}
							}
						}
					}
				}
			} else {
				$latest_log = usces_unserialize( $log_data[0]['log'] );
			}
		}
		return $latest_log;
	}

	/**
	 * 最新処理ステータス取得
	 *
	 * @param  int $member_id Member ID.
	 * @param  int $order_id Order number.
	 * @return string
	 */
	public function get_latest_status( $member_id, $order_id ) {
		global $usces;

		$status   = '';
		$log_data = $this->get_acting_log( $order_id );
		if ( $log_data && 0 < count( $log_data ) ) {
			$acting_data = usces_unserialize( $log_data[0]['log'] );
			$trans_id    = ( isset( $acting_data['MerchantFree1'] ) ) ? $acting_data['MerchantFree1'] : '';
		} else {
			$trans_id = $usces->get_order_meta_value( 'trans_id', $order_id );
		}
		if ( $trans_id ) {
			$latest_log = $this->get_acting_latest_log( $order_id . '_' . $trans_id, 'ALL' );
			$status     = ( isset( $latest_log['ResponseCd'] ) ) ? $latest_log['ResponseCd'] : 'NG';
		}
		return $status;
	}

	/**
	 * 処理区分名称取得
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return string
	 */
	private function get_acting_status_name( $log_key ) {
		$status_name = '';
		$log_data    = $this->get_acting_history_log( $log_key );
		if ( $log_data ) {
			$log = usces_unserialize( $log_data[0]['log'] );
			if ( isset( $log['OperateId'] ) ) {
				$status_name = $this->get_operate_name( $log['OperateId'] );
			}
		}
		return $status_name;
	}

	/**
	 * 銀聯取引ステータス取得
	 *
	 * @param  string $status Status.
	 * @return string
	 */
	protected function get_unionpay_status_name( $status ) {
		$status_name = '';
		switch ( trim( $status ) ) {
			case 1: /* 売上確定済 */
				$status_name = __( 'Sales confirmed', 'usces' );
				break;
			case 2: /* 取消済 */
				$status_name = __( 'Canceled', 'usces' );
				break;
			case 3: /* 返品済 */
				$status_name = __( 'Returned', 'usces' );
				break;
			case 4: /* 参照処理済 */
				$status_name = __( 'Reference processed', 'usces' );
				break;
			case 97: /* NG(詳細不明) */
				$status_name = __( 'NG (details unknown)', 'usces' );
				break;
			case 98: /* レスポンス不明 */
				$status_name = __( 'Unknown response', 'usces' );
				break;
			case 99:
				$status_name = 'NG';
				break;
		}
		return $status_name;
	}

	/**
	 * 期限切れチェック
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $trans_id Transaction ID.
	 * @return boolean
	 */
	private function check_paylimit( $order_id, $trans_id ) {
		global $usces;

		$expiration = false;
		$receipted  = false;
		$log_data   = $this->get_acting_history_log( $order_id . '_' . $trans_id );
		if ( $log_data ) {
			foreach ( (array) $log_data as $data ) {
				$log = usces_unserialize( $data['log'] );
				if ( isset( $log['OperateId'] ) && 'receipted' === $log['OperateId'] ) {
					$receipted = true;
					break;
				}
			}
		}
		if ( $receipted ) {
			return false;
		}
		$today       = date_i18n( 'YmdHi', current_time( 'timestamp' ) );
		$acting_data = usces_unserialize( $usces->get_order_meta_value( 'acting_welcart_conv', $order_id ) );
		if ( $today > $acting_data['PayLimit'] ) {
			$expiration = true;
		}
		return $expiration;
	}

	/**
	 * 削除済みチェック
	 *
	 * @param  string $log_key [order_id]_[trans_id].
	 * @return boolean
	 */
	private function check_deleted( $log_key ) {
		$deleted  = false;
		$log_data = $this->get_acting_history_log( $log_key );
		if ( $log_data ) {
			foreach ( (array) $log_data as $data ) {
				$log = usces_unserialize( $data['log'] );
				if ( isset( $log['OperateId'] ) && '2Del' === $log['OperateId'] ) {
					$deleted = true;
					break;
				}
			}
		}
		return $deleted;
	}

	/**
	 * 継続課金会員データ取得
	 *
	 * @param  int $order_id Order number.
	 * @param  int $member_id Member ID.
	 * @return array
	 */
	private function get_continuation_data( $order_id, $member_id ) {
		global $wpdb;

		$query = $wpdb->prepare( "SELECT 
			`con_acting` AS `acting`, 
			`con_order_price` AS `order_price`, 
			`con_price` AS `price`, 
			`con_next_charging` AS `chargedday`, 
			`con_next_contracting` AS `contractedday`, 
			`con_startdate` AS `startdate`, 
			`con_status` AS `status` 
			FROM {$wpdb->prefix}usces_continuation 
			WHERE con_order_id = %d AND con_member_id = %d",
			$order_id,
			$member_id
		);
		$data = $wpdb->get_row( $query, ARRAY_A ); // phpcs:ignore
		return $data;
	}

	/**
	 * 継続課金会員データ更新
	 *
	 * @param  int     $order_id Order number.
	 * @param  int     $member_id Member ID.
	 * @param  array   $data Continuation data.
	 * @param  boolean $stop Stop continuous billing.
	 * @return boolean
	 */
	private function update_continuation_data( $order_id, $member_id, $data, $stop = false ) {
		global $wpdb;

		if ( $stop ) {
			$query = $wpdb->prepare( "UPDATE {$wpdb->prefix}usces_continuation SET 
				`con_status` = 'cancellation' 
				WHERE `con_order_id` = %d AND `con_member_id` = %d",
				$order_id,
				$member_id
			);
		} else {
			$query = $wpdb->prepare( "UPDATE {$wpdb->prefix}usces_continuation SET 
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
		$res = $wpdb->query( $query ); // phpcs:ignore
		return $res;
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
			$res = checkdate( (int) $month, (int) $day, (int) $year );
			return $res;
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * 支払方法
	 * usces_fiter_the_payment_method
	 *
	 * @param  array $payments Payment data.
	 * @return array
	 */
	public function payment_method( $payments ) {
		global $usces;

		$conv_exclusion    = false;
		$atodene_exclusion = false;

		if ( usces_have_regular_order() ) {
			$conv_exclusion = true;

		} elseif ( usces_have_continue_charge() ) {
			$conv_exclusion    = true;
			$atodene_exclusion = true;

		} else {
			$acting_opts = $this->get_acting_settings();
			if ( 'on' === $acting_opts['atodene_byitem'] ) { /* 商品ごとの可否が有効 */
				$cart = $usces->cart->get_cart();
				foreach ( $cart as $cart_row ) {
					$product           = wel_get_product( $cart_row['post_id'] );
					$atodene_propriety = $product['atodene_propriety'];
					if ( 1 === (int) $atodene_propriety ) {
						$atodene_exclusion = true;
						break;
					}
				}
			}
		}

		if ( $conv_exclusion ) {
			foreach ( $payments as $key => $payment ) {
				if ( $this->acting_flg_conv === $payment['settlement'] ) {
					unset( $payments[ $key ] );
				}
			}
		}
		if ( $atodene_exclusion ) {
			foreach ( $payments as $key => $payment ) {
				if ( $this->acting_flg_atodene === $payment['settlement'] ) {
					unset( $payments[ $key ] );
				}
			}
		}

		return $payments;
	}

	/**
	 * ATODENE CSVアップロード
	 * usces_after_cart_instant
	 */
	public function atodene_upload() {
		if ( isset( $_POST['page'] ) && 'atodene_results_csv' === wp_unslash( $_POST['page'] ) && isset( $_POST['action'] ) && 'atodene_upload' === wp_unslash( $_POST['action'] ) ) {
			$path     = WP_CONTENT_DIR . '/uploads/';
			$workfile = $_FILES['atodene_upcsv']['tmp_name'];
			if ( ! is_uploaded_file( $workfile ) ) {
				$message = __( 'The file was not uploaded.', 'usces' );
				wp_redirect(
					add_query_arg(
						array(
							'page'          => 'usces_orderlist',
							'usces_status'  => 'error',
							'usces_message' => urlencode( $message ),
							'order_action'  => 'atodene_upload',
						),
						USCES_ADMIN_URL
					)
				);
				exit();
			}

			list( $fname, $fext ) = explode( '.', $_FILES['atodene_upcsv']['name'], 2 );
			if ( 'csv' !== $fext ) {
				$message = __( 'The file is not supported.', 'usces' ) . $fname . '.' . $fext;
				wp_redirect(
					add_query_arg(
						array(
							'page'          => 'usces_orderlist',
							'usces_status'  => 'error',
							'usces_message' => urlencode( $message ),
							'order_action'  => 'atodene_upload',
						),
						USCES_ADMIN_URL
					)
				);
				exit();
			}

			$new_filename = base64_encode( $fname . '_' . time() . '.' . $fext );
			if ( ! move_uploaded_file( $_FILES['atodene_upcsv']['tmp_name'], $path . $new_filename ) ) {
				$message = __( 'The file was not stored.', 'usces' ) . $_FILES['atodene_upcsv']['name'];
				wp_redirect(
					add_query_arg(
						array(
							'page'          => 'usces_orderlist',
							'usces_status'  => 'error',
							'usces_message' => urlencode( $message ),
							'order_action'  => 'atodene_upload',
						),
						USCES_ADMIN_URL
					)
				);
				exit();
			}

			wp_redirect(
				add_query_arg(
					array(
						'page'           => 'usces_orderlist',
						'usces_status'   => 'none',
						'usces_message'  => '',
						'order_action'   => 'upload_atodene_results',
						'atodene_upfile' => urlencode( $new_filename ),
						'wc_nonce'       => wp_create_nonce( 'order_list' ),
					),
					USCES_ADMIN_URL
				)
			);
			exit();
		}
	}

	/**
	 * ATODENE CSV出力・CSVアップロード
	 * usces_action_order_list_page
	 *
	 * @param  string $order_action Order action.
	 */
	public function output_atodene_csv( $order_action ) {
		switch ( $order_action ) {
			case 'download_atodene_register':
				$this->download_atodene_register();
				break;
			case 'download_atodene_update':
				$this->download_atodene_update();
				break;
			case 'download_atodene_report':
				$this->download_atodene_report();
				break;
			case 'upload_atodene_results':
				if ( isset( $_GET['atodene_upfile'] ) && ! WCUtils::is_blank( wp_unslash( $_GET['atodene_upfile'] ) ) ) {
					$res                   = $this->upload_atodene_results();
					$_GET['usces_status']  = ( isset( $res['status'] ) ) ? $res['status'] : '';
					$_GET['usces_message'] = ( isset( $res['message'] ) ) ? $res['message'] : '';
				}
				break;
		}
	}

	/**
	 * ATODENE アクションボタン
	 * usces_action_order_list_searchbox_bottom
	 */
	public function action_atodene_button() {
		?>
		<input type="button" id="dl_atodene_register_csv" class="searchbutton button" value="<?php esc_attr_e( 'ATODENE transaction registration CSV output', 'usces' ); ?>" />
		<!--<input type="button" id="dl_atodene_update_csv" class="searchbutton" value="<?php esc_attr_e( 'ATODENE transaction batch change and cancel CSV output', 'usces' ); ?>" />-->
		<input type="button" id="up_atodene_results_csv" class="searchbutton button" value="<?php esc_attr_e( 'ATODENE credit review result CSV upload', 'usces' ); ?>" />
		<input type="button" id="dl_atodene_report_csv" class="searchbutton button" value="<?php esc_attr_e( 'ATODENE shipping report registration CSV output', 'usces' ); ?>" />
		<?php
	}

	/**
	 * ATODENE CSVアップロードダイアログ
	 * usces_action_order_list_footer
	 */
	public function order_list_footer() {
		?>
		<div id="atodene_upload_dialog" class="upload_dialog" style="display:none;">
			<p><?php _e( "Upload the prescribed CSV file and import credit screening results.<br />Please choose a file, and press 'Start of capture'.", 'usces' ); ?></p>
			<form action="<?php echo esc_url( USCES_ADMIN_URL ); ?>" method="post" enctype="multipart/form-data" name="atodene_upform" id="atodene_upform">
				<fieldset>
					<p><input name="atodene_upcsv" type="file" class="filename" /></p>
				</fieldset>
				<p><input name="atodene_uploadcsv" type="submit" class="button" value="<?php esc_attr_e( 'Start of capture', 'usces' ); ?>" /></p>
				<input name="page" type="hidden" value="atodene_results_csv" />
				<input name="action" type="hidden" value="atodene_upload" />
			</form>
		</div>
		<?php
	}

	/**
	 * ATODENE CSVダウンロードダイアログ
	 * usces_filter_order_list_page_js
	 *
	 * @param string $html HTML.
	 * @return string
	 */
	public function order_list_page_js( $html ) {
		$html .= '
		$(document).on( "click", "#dl_atodene_register_csv", function() {
			if( $("input[name*=\'listcheck\']:checked").length == 0 ) {
				alert( "' . __( 'Choose the data.', 'usces' ) . '" );
				$("#orderlistaction").val("");
				return false;
			}
			var listcheck = "";
			$("input[name*=\'listcheck\']").each( function( i ) {
				if( $(this).prop("checked") ) {
					listcheck += "&listcheck["+i+"]="+$(this).val();
				}
			});
			location.href = "' . USCES_ADMIN_URL . '?page=usces_orderlist&order_action=download_atodene_register"+listcheck+"&noheader=true&_nonce=' . wp_create_nonce( 'csv_nonce' ) . '";
		});';

		$html .= '
		$(document).on( "click", "#dl_atodene_report_csv", function() {
			if( $("input[name*=\'listcheck\']:checked").length == 0 ) {
				alert("' . __( 'Choose the data.', 'usces' ) . '");
				$("#orderlistaction").val("");
				return false;
			}
			var listcheck = "";
			$("input[name*=\'listcheck\']").each( function( i ) {
				if( $(this).prop("checked") ) {
					listcheck += "&listcheck["+i+"]="+$(this).val();
				}
			});
			location.href = "' . USCES_ADMIN_URL . '?page=usces_orderlist&order_action=download_atodene_report"+listcheck+"&noheader=true&_nonce=' . wp_create_nonce( 'csv_nonce' ) . '";
		});

		$(document).on( "click", "#up_atodene_results_csv", function() {
			$("#atodene_upload_dialog").dialog({
				bgiframe: true,
				autoOpen: false,
				title: "' . __( 'Credit Review Result CSV Capture', 'usces' ) . '",
				height: 350,
				width: 550,
				modal: true,
				buttons: {
					"' . __( 'Close' ) . '": function() {
						$(this).dialog("close");
					}
				},
				close: function() {
				}
			}).dialog("open");
		});';

		return $html;
	}

	/**
	 * ATODENE アクションステータス
	 * usces_order_list_action_status
	 *
	 * @param  string $status Status.
	 * @return string
	 */
	public function order_list_action_status( $status ) {
		if ( isset( $_GET['order_action'] ) && ( 'atodene_upload' === filter_input( INPUT_GET, 'order_action', FILTER_SANITIZE_STRING ) || 'upload_atodene_results' === filter_input( INPUT_GET, 'order_action', FILTER_SANITIZE_STRING ) ) && isset( $_GET['usces_status'] ) && ! empty( $_GET['usces_status'] ) ) {
			$status = filter_input( INPUT_GET, 'usces_status' );
		}
		return $status;
	}

	/**
	 * ATODENE アクションステータスメッセージ
	 * usces_order_list_action_message
	 *
	 * @param  string $message Message.
	 * @return string
	 */
	public function order_list_action_message( $message ) {
		if ( isset( $_GET['order_action'] ) && ( 'atodene_upload' === filter_input( INPUT_GET, 'order_action', FILTER_SANITIZE_STRING ) || 'upload_atodene_results' === filter_input( INPUT_GET, 'order_action', FILTER_SANITIZE_STRING ) ) && isset( $_GET['usces_message'] ) && ! empty( $_GET['usces_message'] ) ) {
			$message = urldecode( filter_input( INPUT_GET, 'usces_message' ) );
		}
		return $message;
	}

	/**
	 * ATODENE 運送会社名
	 * usces_filter_deli_comps
	 *
	 * @param  array $deli_comps Delivery company.
	 * @return array
	 */
	public function delivery_company_name( $deli_comps ) {
		$deli_comps = array(
			'佐川急便',
			'ヤマト運輸',
			'クロネコメール便',
			'ゆうパック',
			'レターパック',
			'福山通運',
			'西濃運輸',
		);
		$deli_comps = apply_filters( 'usces_filter_deli_comps_atodene', $deli_comps );
		return $deli_comps;
	}

	/**
	 * 後払い決済の可否
	 * usces_item_master_second_section
	 *
	 * @param  string $second_section Second section area.
	 * @param  int    $post_id Post ID.
	 * @return string
	 */
	public function edit_item_atodene_byitem( $second_section, $post_id ) {
		global $usces;

		$division      = $usces->getItemDivision( $post_id );
		$charging_type = $usces->getItemChargingType( $post_id );
		$acting_opts   = $this->get_acting_settings();
		if ( 'shipped' === $division && 'continue' !== $charging_type && 'on' === $acting_opts['atodene_byitem'] ) { /* 商品ごとの可否が有効 */
			$product           = wel_get_product( $post_id );
			$atodene_propriety = $product['atodene_propriety'];
			$checked           = ( 1 === (int) $atodene_propriety ) ? array( '', ' checked="checked"' ) : array( ' checked="checked"', '' );
			$second_section   .= '
			<tr>
				<th>' . __( 'Atobarai Propriety', 'usces' ) . '</th>
				<td>
					<label for="atodene_propriety_0"><input name="atodene_propriety" id="atodene_propriety_0" type="radio" value="0"' . $checked[0] . '>' . __( 'available', 'usces' ) . '</label>
					<label for="atodene_propriety_1"><input name="atodene_propriety" id="atodene_propriety_1" type="radio" value="1"' . $checked[1] . '>' . __( 'not available', 'usces' ) . '</label>
				</td>
			</tr>';
		}
		return $second_section;
	}

	/**
	 * 後払い決済の可否更新
	 * usces_action_save_product
	 *
	 * @param  int   $post_id Post ID.
	 * @param  array $post Post data.
	 */
	public function save_item_atodene_byitem( $post_id, $post ) {
		/* This process has moved to item_post.php 1567. */

		// if ( isset( $_POST['atodene_propriety'] ) ) {
		// 	update_post_meta( $post_id, 'atodene_propriety', $_POST['atodene_propriety'] );
		// }
	}

	/**
	 * 接続しない決済モジュール
	 * usces_filter_nonacting_settlements
	 *
	 * @param  array $nonacting_settlements Non-working settlement module..
	 * @return array
	 */
	public function nonacting_settlements( $nonacting_settlements ) {
		if ( ! in_array( 'acting_welcart_atodene', $nonacting_settlements ) ) {
			$nonacting_settlements[] = 'acting_welcart_atodene';
		}
		return $nonacting_settlements;
	}

	/**
	 * 利用可能な支払方法
	 * wcad_filter_the_payment_method_restriction
	 *
	 * @param  array  $payments_restriction Payment method.
	 * @param  string $payments_value Input value.
	 * @return array
	 */
	public function payment_method_restriction_atodene( $payments_restriction, $payments_value ) {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( usces_have_regular_order() ) {
			$atodene_exclusion = false;
			if ( 'on' === $acting_opts['atodene_byitem'] ) { /* 商品ごとの可否が有効 */
				$cart = $usces->cart->get_cart();
				foreach ( $cart as $cart_row ) {
					$product           = wel_get_product( $cart_row['post_id'] );
					$atodene_propriety = $product['atodene_propriety'];
					if ( 1 === (int) $atodene_propriety ) {
						$atodene_exclusion = true;
						break;
					}
				}
			}
			if ( ! $atodene_exclusion ) {
				$payments               = usces_get_system_option( 'usces_payment_method', 'settlement' );
				$payments_restriction[] = $payments['acting_welcart_atodene'];
				foreach ( (array) $payments_restriction as $key => $value ) {
					$sort[ $key ] = $value['sort'];
				}
				array_multisort( $sort, SORT_ASC, $payments_restriction );
			}
		}
		return $payments_restriction;
	}

	/**
	 * ATODENE 取引登録CSV出力
	 */
	public function download_atodene_register() {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$filename    = mb_convert_encoding( __( 'ATODENE_transaction_', 'usces' ), 'SJIS', 'UTF-8' ) . date_i18n( 'YmdHis', current_time( 'timestamp' ) ) . '.csv';

		$line = '"ご購入店受注番号","購入者注文日","会社名","部署名","氏名","氏名（フリガナ）","郵便番号","住所","電話番号","メールアドレス","配送先会社名","配送先部署名","配送先氏名","配送先氏名（フリガナ）","配送先郵便番号","配送先住所","配送先電話番号","請求書送付方法","予備領域1","予備領域2","予備領域3","顧客請求総額（税込）","明細名（商品名）","単価（税込）","数量"' . "\r\n";

		$ids = $_GET['listcheck'];
		foreach ( (array) $ids as $order_id ) {
			$order_data = $usces->get_order_data( $order_id, 'direct' );
			$payment    = $usces->getPayments( $order_data['order_payment_name'] );
			if ( 'acting_welcart_atodene' !== $payment['settlement'] ) {
				continue;
			}

			$delivery = usces_unserialize( $order_data['order_delivery'] );
			$cart     = usces_get_ordercartdata( $order_id );

			$order_date = substr( $order_data['order_date'], 0, 10 );
			$date       = str_replace( '-', '/', $order_date );

			$company       = $usces->get_order_meta_value( 'cscs_company', $order_id );
			$order_name    = $order_data['order_name1'] . $order_data['order_name2'];
			$order_kana    = mb_convert_kana( $order_data['order_name3'], 'ak', 'UTF-8' ) . mb_convert_kana( $order_data['order_name4'], 'ak', 'UTF-8' );
			$order_zip     = str_replace( 'ー', '', mb_convert_kana( $order_data['order_zip'], 'a', 'UTF-8' ) );
			$order_post    = str_replace( '-', '', $order_zip );
			$order_address = $order_data['order_pref'] . $order_data['order_address1'];
			if ( ! empty( $order_data['order_address2'] ) ) {
				$order_address .= mb_convert_kana( $order_data['order_address2'], 'ak', 'UTF-8' );
			}
			if ( ! empty( $order_data['order_address3'] ) ) {
				$order_address .= mb_convert_kana( $order_data['order_address3'], 'ak', 'UTF-8' );
			}
			$order_tel = $order_data['order_tel'];
			$email     = $order_data['order_email'];

			$shipto_company = $usces->get_order_meta_value( 'csde_company', $order_id );
			$shipto_name    = $delivery['name1'] . $delivery['name2'];
			$shipto_kana    = mb_convert_kana( $delivery['name3'], 'ak', 'UTF-8' ) . mb_convert_kana( $delivery['name4'], 'ak', 'UTF-8' );
			$shipto_zip     = str_replace( 'ー', '', mb_convert_kana( $delivery['zipcode'], 'a', 'UTF-8' ) );
			$shipto_post    = str_replace( '-', '', $delivery['zipcode'] );
			$shipto_address = $delivery['pref'] . $delivery['address1'];
			if ( ! empty( $delivery['address2'] ) ) {
				$shipto_address .= mb_convert_kana( $delivery['address2'], 'ak', 'UTF-8' );
			}
			if ( ! empty( $delivery['address3'] ) ) {
				$shipto_address .= mb_convert_kana( $delivery['address3'], 'ak', 'UTF-8' );
			}
			$shipto_tel = $delivery['tel'];

			$amount = $order_data['order_item_total_price'] - $order_data['order_usedpoint'] + $order_data['order_discount'] + $order_data['order_shipping_charge'] + $order_data['order_cod_fee'] + $order_data['order_tax'];

			$line .= '"' . $order_id . '",' .
				'"' . $date . '",' .
				'"' . $company . '","",' .
				'"' . $order_name . '",' .
				'"' . $order_kana . '",' .
				'"' . $order_post . '",' .
				'"' . $order_address . '",' .
				'"' . $order_tel . '",' .
				'"' . $email . '",' .
				'"' . $shipto_company . '","",' .
				'"' . $shipto_name . '",' .
				'"' . $shipto_kana . '",' .
				'"' . $shipto_post . '",' .
				'"' . $shipto_address . '",' .
				'"' . $shipto_tel . '",' .
				'"' . $acting_opts['atodene_billing_method'] . '","","","",' .
				'"' . $amount . '",';

			$row = 1;
			foreach ( $cart as $cart_row ) {
				if ( 1 < $row ) {
					$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				}
				$line .= '"' . $cart_row['item_name'] . '",';
				$line .= '"' . usces_crform( $cart_row['price'], false, false, 'return', false ) . '",';
				$line .= '"' . $cart_row['quantity'] . '"' . "\r\n";
				$row++;
			}

			if ( 0 !== (int) $order_data['order_discount'] ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . apply_filters( 'usces_confirm_discount_label', __( 'Discount', 'usces' ), $order_id ) . '",';
				$line .= '"' . usces_crform( $cart_row['order_discount'], false, false, 'return', false ) . '","1"' . "\r\n";
			}

			if ( usces_is_tax_display() && 'products' === usces_get_tax_target() && 'exclude' === usces_get_tax_mode() ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . usces_tax_label( $order_data, 'return' ) . '",';
				$line .= '"' . usces_crform( $order_data['order_tax'], false, false, 'return', false ) . '","1"' . "\r\n";
			}

			if ( usces_is_member_system() && usces_is_member_system_point() && 0 === (int) usces_point_coverage() && 0 !== (int) $order_data['order_usedpoint'] ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . __( 'use of points', 'usces' ) . '",';
				$line .= '"' . usces_crform( $order_data['order_usedpoint'], false, false, 'return', false ) . '","1"' . "\r\n";
			}

			if ( 0 < $order_data['order_shipping_charge'] ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . __( 'Shipping', 'usces' ) . '",';
				$line .= '"' . usces_crform( $order_data['order_shipping_charge'], false, false, 'return', false ) . '","1"' . "\r\n";
			}

			if ( 0 < $order_data['order_cod_fee'] ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ) . '",';
				$line .= '"' . usces_crform( $order_data['order_cod_fee'], false, false, 'return', false ) . '","1"' . "\r\n";
			}

			if ( usces_is_tax_display() && 'all' === usces_get_tax_target() && 'exclude' === usces_get_tax_mode() ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . usces_tax_label( $order_data, 'return' ) . '",';
				$line .= '"' . usces_crform( $order_data['order_tax'], false, false, 'return', false ) . '","1"' . "\r\n";
			}

			if ( usces_is_member_system() && usces_is_member_system_point() && 1 === (int) usces_point_coverage() && 0 !== (int) $order_data['order_usedpoint'] ) {
				$line .= '"","","","","","","","","","","","","","","","","","","","","","",';
				$line .= '"' . __( 'use of points', 'usces' ) . '",';
				$line .= '"' . usces_crform( $order_data['order_usedpoint'], false, false, 'return', false ) . '","1"' . "\r\n";
			}
		}

		header( 'Content-Type: application/octet-stream' );
		header( "Content-disposition: attachment; filename=$filename" );
		mb_http_output( 'pass' );
		print( mb_convert_encoding( $line, 'SJIS-win', 'UTF-8' ) ); // phpcs:ignore
		exit();
	}

	/**
	 * ATODENE 取引一括変更・キャンセルCSV出力
	 */
	public function download_atodene_update() {
		exit();
	}

	/**
	 * ATODENE 出荷報告登録CSV出力
	 */
	public function download_atodene_report() {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$filename    = mb_convert_encoding( __( 'ATODENE_shippingreport_', 'usces' ), 'SJIS', 'UTF-8' ) . date_i18n( 'YmdHis', current_time( 'timestamp' ) ) . '.csv';

		$line = '"運送会社名","配送伝票番号","購入者注文日","お問合せ番号","ご購入店受注番号","氏名","予備項目","配送先氏名","配送先住所","顧客請求金額（税込）","請求書送付方法","審査結果"' . "\r\n";

		$ids = $_GET['listcheck'];
		foreach ( (array) $ids as $order_id ) {
			$order_data = $usces->get_order_data( $order_id, 'direct' );
			$payment    = $usces->getPayments( $order_data['order_payment_name'] );
			if ( 'acting_welcart_atodene' !== $payment['settlement'] ) {
				continue;
			}

			$delivery_company = $usces->get_order_meta_value( 'delivery_company', $order_id );
			$tracking_number  = $usces->get_order_meta_value( apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number' ), $order_id );
			$atodene_number   = $usces->get_order_meta_value( 'atodene_number', $order_id );

			if ( ! empty( $delivery_company ) && ! empty( $tracking_number ) && ! empty( $atodene_number ) ) {
				$line .= '"' . $delivery_company . '",' .
					'"' . $tracking_number . '","",' .
					'"' . $atodene_number . '",' .
					'"' . $order_id . '","","","","","","",""' . "\r\n";
			}
		}

		header( 'Content-Type: application/octet-stream' );
		header( "Content-disposition: attachment; filename=$filename" );
		mb_http_output( 'pass' );
		print( mb_convert_encoding( $line, 'SJIS-win', 'UTF-8' ) ); // phpcs:ignore
		exit();
	}

	/**
	 * ATODENE 与信審査結果CSV取込
	 *
	 * @return array
	 */
	public function upload_atodene_results() {
		global $usces, $wpdb;

		$res  = array();
		$path = WP_CONTENT_DIR . '/uploads/';

		if ( isset( $_GET['atodene_upfile'] ) && ! WCUtils::is_blank( filter_input( INPUT_GET, 'atodene_upfile', FILTER_SANITIZE_STRING ) ) && isset( $_GET['order_action'] ) && 'upload_atodene_results' === filter_input( INPUT_GET, 'order_action', FILTER_SANITIZE_STRING ) ) {
			$file_name       = urldecode( filter_input( INPUT_GET, 'atodene_upfile' ) );
			$decode_filename = base64_decode( $file_name );
			if ( ! file_exists( $path . $file_name ) ) {
				$res['status']  = 'error';
				$res['message'] = __( 'CSV file does not exist.', 'usces' ) . esc_html( $decode_filename );
				return( $res );
			}
		}

		$wpdb->query( 'SET SQL_BIG_SELECTS=1' );
		set_time_limit( 3600 );

		define( 'COL_ORDER_ID', 0 ); /* ご購入店受注番号 */
		define( 'COL_ATODINE_NUMBER', 1 ); /* お問合せ番号 */
		define( 'COL_NAME', 2 ); /* 氏名 */
		define( 'COL_AMOUNT', 3 ); /* 顧客請求金額（税込） */
		define( 'COL_BILLING_METHOD', 4 ); /* 請求書送付方法(別送/同梱) */
		define( 'COL_RESULTS', 5 ); /* 与信審査結果(OK/NG/保留/審査中) */

		if ( ! ( $fpo = fopen( $path . $file_name, 'r' ) ) ) {
			$res['status']  = 'error';
			$res['message'] = __( 'A file does not open.', 'usces' ) . esc_html( $decode_filename );
			return $res;
		}

		$orglines = array();
		$sp       = ',';

		$fname_parts = explode( '.', $decode_filename );
		if ( 'csv' !== end( $fname_parts ) ) {
			$res['status']  = 'error';
			$res['message'] = __( 'This file is not in the CSV file.', 'usces' ) . esc_html( $decode_filename );
			return $res;
		}

		$buf = '';
		while ( ! feof( $fpo ) ) {
			$temp = fgets( $fpo, 10240 );
			if ( 0 === strlen( $temp ) ) {
				continue;
			}
			$orglines[] = str_replace( '"', '', $temp );
		}
		fclose( $fpo );

		foreach ( $orglines as $sjisline ) {
			$line = mb_convert_encoding( $sjisline, 'UTF-8', 'SJIS' );
			list( $order_id, $atodene_number, $name, $amount, $billing_method, $atodene_results ) = explode( $sp, $line );
			if ( ! is_numeric( $order_id ) ) {
				continue;
			}
			$order_data = $usces->get_order_data( $order_id, 'direct' );
			if ( $order_data ) {
				$atodene_results = mb_convert_kana( $atodene_results, 'r', 'UTF-8' );
				if ( 'OK' === trim( $atodene_results ) ) {
					$res = usces_change_order_receipt( (int) $order_id, 'receipted' );
					usces_action_acting_getpoint( (int) $order_id );
				}
				if ( ! empty( $atodene_number ) ) {
					$usces->set_order_meta_value( 'atodene_number', trim( $atodene_number ), (int) $order_id );
				}
				if ( ! empty( $atodene_results ) ) {
					$usces->set_order_meta_value( 'atodene_results', trim( $atodene_results ), (int) $order_id );
				}
			}
		}
		unlink( $path . $file_name );

		return $res;
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
			$data['_nonce'] = filter_input( INPUT_POST, '_nonce' );
		}
		return $data;
	}

	/**
	 * Save admin log.
	 *
	 * @since 2.7.2
	 * @see admin_ajax()
	 * @param integer $order_id The order id.
	 * @param string  $log_key The log key.
	 * @return void
	 */
	private function save_admin_log( $order_id, $log_key ) {
		$latest_log = array();
		$log_data   = $this->get_acting_history_log( $log_key );
		if ( ! empty( $log_data ) ) {
			$latest_log = current( $log_data );
		}

		Logger::log( $order_id, 'orderedit', 'update', array( 'acting_welcart_card' => $latest_log ) );
	}
}
