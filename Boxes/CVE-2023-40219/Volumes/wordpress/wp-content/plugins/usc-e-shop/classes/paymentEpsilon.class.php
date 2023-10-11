<?php
/**
 * Settlement Class.
 * Epsilon
 *
 * @package  Welcart
 * @author   Collne Inc.
 */

/**
 * Epsilon
 */
class EPSILON_SETTLEMENT {

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
	 * エラーメッセージ
	 *
	 * @var string
	 */
	protected $error_mes;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->paymod_id          = 'epsilon';
		$this->pay_method         = array(
			'acting_epsilon_card',
			'acting_epsilon_conv',
			'acting_epsilon_paypay',
		);
		$this->acting_name        = 'イプシロン';
		$this->acting_formal_name = 'イプシロン';
		$this->acting_company_url = 'https://www.epsilon.jp/';

		$this->initialize_data();

		if ( is_admin() ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if ( $this->is_validity_acting() ) {
			add_action( 'usces_after_cart_instant', array( $this, 'acting_transaction' ) );
			add_filter( 'usces_filter_is_complete_settlement', array( $this, 'is_complete_settlement' ), 10, 3 );

			if ( $this->is_activate_card() || $this->is_activate_conv() || $this->is_activate_paypay() ) {
				add_action( 'usces_action_reg_orderdata', array( $this, 'register_order_data' ) );
				if ( is_admin() ) {
					add_filter( 'usces_filter_settle_info_field_keys', array( $this, 'settlement_info_field_keys' ), 10, 2 );
					add_filter( 'usces_filter_settle_info_field_value', array( $this, 'settlement_info_field_value' ), 10, 3 );
					add_action( 'usces_action_revival_order_data', array( $this, 'revival_order_data' ), 10, 3 );
				} else {
					add_filter( 'usces_filter_confirm_inform', array( $this, 'confirm_inform' ), 10, 5 );
					add_action( 'usces_action_acting_processing', array( $this, 'acting_processing' ), 10, 2 );
					add_filter( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
				}
			}

			if ( $this->is_validity_acting( 'card' ) ) {
				if ( is_admin() ) {
					add_action( 'usces_action_admin_member_info', array( $this, 'admin_member_info' ), 10, 3 );
					add_action( 'usces_action_post_update_memberdata', array( $this, 'admin_update_memberdata' ), 10, 2 );
				}
			}

			if ( $this->is_validity_acting( 'conv' ) ) {
				add_filter( 'usces_filter_payment_detail', array( $this, 'payment_detail' ), 10, 2 );
				add_filter( 'usces_filter_noreceipt_status', array( $this, 'noreceipt_status' ) );
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
		$options = get_option( 'usces', array() );
		$options['acting_settings']['epsilon']['contract_code']   = ( isset( $options['acting_settings']['epsilon']['contract_code'] ) ) ? $options['acting_settings']['epsilon']['contract_code'] : '';
		$options['acting_settings']['epsilon']['ope']             = ( isset( $options['acting_settings']['epsilon']['ope'] ) ) ? $options['acting_settings']['epsilon']['ope'] : '';
		$options['acting_settings']['epsilon']['card_activate']   = ( isset( $options['acting_settings']['epsilon']['card_activate'] ) ) ? $options['acting_settings']['epsilon']['card_activate'] : '';
		$options['acting_settings']['epsilon']['multi_currency']  = ( isset( $options['acting_settings']['epsilon']['multi_currency'] ) ) ? $options['acting_settings']['epsilon']['multi_currency'] : '';
		$options['acting_settings']['epsilon']['3dsecure']        = ( isset( $options['acting_settings']['epsilon']['3dsecure'] ) ) ? $options['acting_settings']['epsilon']['3dsecure'] : '';
		$options['acting_settings']['epsilon']['process_code']    = ( isset( $options['acting_settings']['epsilon']['process_code'] ) ) ? $options['acting_settings']['epsilon']['process_code'] : '';
		$options['acting_settings']['epsilon']['conv_activate']   = ( isset( $options['acting_settings']['epsilon']['conv_activate'] ) ) ? $options['acting_settings']['epsilon']['conv_activate'] : '';
		$options['acting_settings']['epsilon']['conv_limit']      = ( isset( $options['acting_settings']['epsilon']['conv_limit'] ) ) ? $options['acting_settings']['epsilon']['conv_limit'] : '';
		$options['acting_settings']['epsilon']['paypay_activate'] = ( isset( $options['acting_settings']['epsilon']['paypay_activate'] ) ) ? $options['acting_settings']['epsilon']['paypay_activate'] : '';
		update_option( 'usces', $options );

		$available_settlement = get_option( 'usces_available_settlement', array() );
		if ( ! in_array( 'epsilon', $available_settlement, true ) ) {
			$available_settlement['epsilon'] = $this->acting_name;
			update_option( 'usces_available_settlement', $available_settlement );
		}
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
					if ( 'acting_epsilon_card' === $payment['settlement'] && 'activate' === $payment['use'] ) {
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
					if ( 'acting_epsilon_conv' === $payment['settlement'] && 'activate' === $payment['use'] ) {
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

			case 'paypay':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_epsilon_paypay' === $payment['settlement'] && 'activate' === $payment['use'] ) {
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
			( isset( $acting_opts['card_activate'] ) && ( 'on' === $acting_opts['card_activate'] ) ) ) {
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
	 * PayPay決済有効判定
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
	 * 未入金ステータス
	 * usces_filter_noreceipt_status
	 *
	 * @param  array $noreceipt_status Receive payment notification.
	 * @return array
	 */
	public function noreceipt_status( $noreceipt_status ) {
		if ( ! in_array( 'acting_epsilon_conv', $noreceipt_status, true ) ) {
			$noreceipt_status[] = 'acting_epsilon_conv';
			update_option( 'usces_noreceipt_status', $noreceipt_status );
		}
		return $noreceipt_status;
	}

	/**
	 * 管理画面スクリプト
	 * admin_print_footer_scripts
	 */
	public function admin_scripts() {
		$admin_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		switch ( $admin_page ) :
			/* クレジット決済設定画面 */
			case 'usces_settlement':
				$settlement_selected = get_option( 'usces_settlement_selected', array() );
				if ( in_array( 'epsilon', $settlement_selected, true ) ) :
					?>
<script type="text/javascript">
jQuery(document).ready( function($) {
	if( 'on' == $("input[name='card_activate']:checked").val() ) {
		$('.card_form_epsilon').css('display','');
	} else {
		$('.card_form_epsilon').css('display','none');
	}
	$(document).on( 'change', "input[name='card_activate']", function() {
		if( 'on' == $("input[name='card_activate']:checked").val() ) {
			$('.card_form_epsilon').css('display','');
		} else {
			$('.card_form_epsilon').css('display','none');
		}
	});
	$(document).on( 'change', '.multi_currency_epsilon', function() {
		if( 'on' == $(this).val() ) {
			$('#3dsecure_epsilon_on').prop('checked',true);
			$('#process_code_epsilon_off').prop('checked',true);
		}
	});
	$(document).on( 'change', '.3dsecure_epsilon', function() {
		if( 'on' == $(this).val() ) {
			$('#process_code_epsilon_off').prop('checked',true);
		} else if( 'off' == $(this).val() ) {
			if( $('#multi_currency_epsilon_on').prop('checked') ) {
				$('#3dsecure_epsilon_on').prop('checked',true);
			}
		}
	});
	$(document).on( 'change', '.process_code_epsilon', function() {
		if( 'on' == $(this).val() ) {
			$('#multi_currency_epsilon_off').prop('checked',true);
			$('#3dsecure_epsilon_off').prop('checked',true);
		}
	});
	if( 'on' == $("input[name='conv_activate']:checked").val() ) {
		$(".conv_form_epsilon").css("display","");
	} else {
		$(".conv_form_epsilon").css("display","none");
	}
	$("input[name='conv_activate']").click( function() {
		if( 'on' == $("input[name='conv_activate']:checked").val() ) {
			$(".conv_form_epsilon").css("display","");
		} else {
			$(".conv_form_epsilon").css("display","none");
		}
	});
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

		if ( 'epsilon' !== filter_input( INPUT_POST, 'acting', FILTER_SANITIZE_STRING ) ) {
			return;
		}

		$this->error_mes = '';
		$options         = get_option( 'usces', array() );
		$payment_method  = usces_get_system_option( 'usces_payment_method', 'settlement' );
		$post_data       = wp_unslash( $_POST );

		unset( $options['acting_settings']['epsilon'] );
		$options['acting_settings']['epsilon']['contract_code']   = ( isset( $post_data['contract_code'] ) ) ? trim( $post_data['contract_code'] ) : '';
		$options['acting_settings']['epsilon']['ope']             = ( isset( $post_data['ope'] ) ) ? $post_data['ope'] : '';
		$options['acting_settings']['epsilon']['card_activate']   = ( isset( $post_data['card_activate'] ) ) ? $post_data['card_activate'] : '';
		$options['acting_settings']['epsilon']['multi_currency']  = ( isset( $post_data['multi_currency'] ) ) ? $post_data['multi_currency'] : '';
		$options['acting_settings']['epsilon']['3dsecure']        = ( isset( $post_data['3dsecure'] ) ) ? $post_data['3dsecure'] : '';
		$options['acting_settings']['epsilon']['process_code']    = ( isset( $post_data['process_code'] ) ) ? $post_data['process_code'] : '';
		$options['acting_settings']['epsilon']['conv_activate']   = ( isset( $post_data['conv_activate'] ) ) ? $post_data['conv_activate'] : '';
		$options['acting_settings']['epsilon']['conv_limit']      = ( isset( $post_data['conv_limit'] ) ) ? wp_unslash( $post_data['conv_limit'] ) : '';
		$options['acting_settings']['epsilon']['paypay_activate'] = ( isset( $post_data['paypay_activate'] ) ) ? $post_data['paypay_activate'] : '';

		if ( 'on' === $options['acting_settings']['epsilon']['card_activate'] || 'on' === $options['acting_settings']['epsilon']['conv_activate'] || 'on' === $options['acting_settings']['epsilon']['paypay_activate'] ) {
			if ( '' === $options['acting_settings']['epsilon']['contract_code'] ) {
				$this->error_mes .= '※契約番号を入力してください<br />';
			}
			if ( '' === $options['acting_settings']['epsilon']['ope'] ) {
				$this->error_mes .= '※稼働環境を選択してください<br />';
			}
		}

		if ( '' === $this->error_mes ) {
			$usces->action_status  = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if ( 'on' === $options['acting_settings']['epsilon']['card_activate'] || 'on' === $options['acting_settings']['epsilon']['conv_activate'] || 'on' === $options['acting_settings']['epsilon']['paypay_activate'] ) {
				$options['acting_settings']['epsilon']['activate'] = 'on';
				if ( 'public' === $options['acting_settings']['epsilon']['ope'] ) {
					$options['acting_settings']['epsilon']['send_url'] = 'https://secure.epsilon.jp/cgi-bin/order/receive_order3.cgi';
				} elseif ( 'test' === $options['acting_settings']['epsilon']['ope'] ) {
					$options['acting_settings']['epsilon']['send_url'] = 'https://beta.epsilon.jp/cgi-bin/order/receive_order3.cgi';
				}
				$toactive = array();
				if ( 'on' === $options['acting_settings']['epsilon']['card_activate'] ) {
					$usces->payment_structure['acting_epsilon_card'] = 'カード決済（イプシロン）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_epsilon_card' === $settlement && 'activate' !== $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_epsilon_card'] );
				}
				if ( 'on' === $options['acting_settings']['epsilon']['conv_activate'] ) {
					$usces->payment_structure['acting_epsilon_conv'] = 'コンビニ決済（イプシロン）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_epsilon_conv' === $settlement && 'activate' !== $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_epsilon_conv'] );
				}
				if ( 'on' === $options['acting_settings']['epsilon']['paypay_activate'] ) {
					$usces->payment_structure['acting_epsilon_paypay'] = 'PayPay決済（イプシロン）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_epsilon_paypay' === $settlement && 'activate' !== $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_epsilon_paypay'] );
				}
				usces_admin_orderlist_show_wc_trans_id();
				if ( 0 < count( $toactive ) ) {
					$usces->action_message .= __( 'Please update the payment method to "Activate". <a href="admin.php?page=usces_initial#payment_method_setting">General Setting > Payment Methods</a>', 'usces' );
				}
			} else {
				$options['acting_settings']['epsilon']['activate'] = 'off';
				unset( $usces->payment_structure['acting_epsilon_card'] );
				unset( $usces->payment_structure['acting_epsilon_conv'] );
				unset( $usces->payment_structure['acting_epsilon_paypay'] );
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
			$options['acting_settings']['epsilon']['activate'] = 'off';
			unset( $usces->payment_structure['acting_epsilon_card'] );
			unset( $usces->payment_structure['acting_epsilon_conv'] );
			unset( $usces->payment_structure['acting_epsilon_paypay'] );
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
	 * クレジット決済設定画面タブ
	 * usces_action_settlement_tab_title
	 */
	public function settlement_tab_title() {
		$settlement_selected = get_option( 'usces_settlement_selected', array() );
		if ( in_array( $this->paymod_id, $settlement_selected, true ) ) {
			echo '<li><a href="#uscestabs_' . esc_html( $this->paymod_id ) . '">' . esc_html( $this->acting_name ) . '</a></li>';
		}
	}

	/**
	 * クレジット決済設定画面フォーム
	 * usces_action_settlement_tab_body
	 */
	public function settlement_tab_body() {
		$settlement_selected = get_option( 'usces_settlement_selected', array() );
		if ( in_array( $this->paymod_id, $settlement_selected, true ) ) :
			$acting_opts     = $this->get_acting_settings();
			$contract_code   = ( isset( $acting_opts['contract_code'] ) ) ? $acting_opts['contract_code'] : '';
			$ope             = ( isset( $acting_opts['ope'] ) && 'public' === $acting_opts['ope'] ) ? 'public' : 'test';
			$card_activate   = ( isset( $acting_opts['card_activate'] ) && 'on' === $acting_opts['card_activate'] ) ? 'on' : 'off';
			$multi_currency  = ( isset( $acting_opts['multi_currency'] ) && 'on' === $acting_opts['multi_currency'] ) ? 'on' : 'off';
			$threedsecure    = ( isset( $acting_opts['3dsecure'] ) && 'on' === $acting_opts['3dsecure'] ) ? 'on' : 'off';
			$process_code    = ( isset( $acting_opts['process_code'] ) && 'on' === $acting_opts['process_code'] ) ? 'on' : 'off';
			$conv_activate   = ( isset( $acting_opts['conv_activate'] ) && 'on' === $acting_opts['conv_activate'] ) ? 'on' : 'off';
			$conv_limit      = ( isset( $acting_opts['conv_limit'] ) ) ? $acting_opts['conv_limit'] : '';
			$paypay_activate = ( isset( $acting_opts['paypay_activate'] ) && 'on' === $acting_opts['paypay_activate'] ) ? 'on' : 'off';
			?>
	<div id="uscestabs_epsilon">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
			<?php
			if ( 'epsilon' === filter_input( INPUT_POST, 'acting', FILTER_SANITIZE_STRING ) ) :
				if ( '' !== $this->error_mes ) :
					?>
		<div class="error_message"><?php wel_esc_script_e( $this->error_mes ); ?></div>
					<?php
				elseif ( isset( $acting_opts['activate'] ) && 'on' === $acting_opts['activate'] ) :
					?>
		<div class="message"><?php esc_html_e( 'Test thoroughly before use.', 'usces' ); ?></div>
					<?php
				endif;
			endif;
			?>
	<form action="" method="post" name="epsilon_form" id="epsilon_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_contract_code_epsilon">契約番号</a></th>
				<td><input name="contract_code" type="text" id="contract_code_epsilon" value="<?php echo esc_attr( $contract_code ); ?>" class="regular-text" maxlength="8" /></td>
			</tr>
			<tr id="ex_contract_code_epsilon" class="explanation"><td colspan="2">契約時にイプシロンから発行される契約番号（半角数字8桁）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_epsilon"><?php esc_html_e( 'Operation Environment', 'usces' ); ?></a></th>
				<td><label><input name="ope" type="radio" id="ope_epsilon_test" value="test"<?php checked( $ope, 'test' ); ?> /><span>テスト環境</span></label><br />
					<label><input name="ope" type="radio" id="ope_epsilon_public" value="public"<?php checked( $ope, 'public' ); ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_ope_epsilon" class="explanation"><td colspan="2">動作環境を切り替えます。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>クレジットカード決済</th>
				<td><label><input name="card_activate" type="radio" id="card_activate_epsilon_on" value="on"<?php checked( $card_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" id="card_activate_epsilon_off" value="off"<?php checked( $card_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr class="card_form_epsilon">
				<th><a class="explanation-label" id="label_ex_multi_currency_epsilon">多通貨決済</a></th>
				<td><label><input name="multi_currency" type="radio" class="multi_currency_epsilon" id="multi_currency_epsilon_on" value="on"<?php checked( $multi_currency, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="multi_currency" type="radio" class="multi_currency_epsilon" id="multi_currency_epsilon_off" value="off"<?php checked( $multi_currency, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_multi_currency_epsilon" class="explanation card_form_epsilon"><td colspan="2">イプシロンとの契約時にクレジットカード決済（多通貨）の契約をした場合、「利用する」にしてください。</td></tr>
			<tr class="card_form_epsilon">
				<th><a class="explanation-label" id="label_ex_3dsecure_epsilon">3Dセキュア</a></th>
				<td><label><input name="3dsecure" type="radio" class="3dsecure_epsilon" id="3dsecure_epsilon_on" value="on"<?php checked( $threedsecure, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="3dsecure" type="radio" class="3dsecure_epsilon" id="3dsecure_epsilon_off" value="off"<?php checked( $threedsecure, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_3dsecure_epsilon" class="explanation card_form_epsilon"><td colspan="2">イプシロンとの契約時に3Dセキュアの契約をした場合、「利用する」にしてください。<br />「多通貨決済」では必須です。「登録済み課金」は併用できません。</td></tr>
			<tr class="card_form_epsilon">
				<th><a class="explanation-label" id="label_ex_process_code_epsilon">登録済み課金</th>
				<td><label><input name="process_code" type="radio" class="process_code_epsilon" id="process_code_epsilon_on" value="on"<?php checked( $process_code, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="process_code" type="radio" class="process_code_epsilon" id="process_code_epsilon_off" value="off"<?php checked( $process_code, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_process_code_epsilon" class="explanation card_form_epsilon"><td colspan="2">Welcart の会員システムを利用している場合、1度クレジットカード決済を実施すると会員番号で紐付けてクレジットカード番号をイプシロンで保持し、2回目以降のクレジット決済において、クレジットカード番号の入力を不要にします。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>コンビニ決済</th>
				<td><label><input name="conv_activate" type="radio" id="conv_activate_epsilon_on" value="on"<?php checked( $conv_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="conv_activate" type="radio" id="conv_activate_epsilon_off" value="off"<?php checked( $conv_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr class="conv_form_epsilon">
				<th><a class="explanation-label" id="label_ex_conv_limit"><?php esc_html_e( 'Payment due days', 'usces' ); ?></a></th>
				<td><select name="conv_limit">
					<option value=""></option>
			<?php for ( $d = 10; $d >= 3; $d-- ) : ?>
					<option value="<?php echo esc_attr( $d ); ?>"<?php selected( $conv_limit, $d ); ?>><?php echo esc_html( $d ); ?>日間</option>
			<?php endfor; ?>
					</select>
				</td>
			</tr>
			<tr id="ex_conv_limit" class="explanation conv_form_epsilon"><td colspan="2">設定する場合はイプシロン管理画面の「コンビニ支払期限」と合わせる必要があります。ここでの設定はあくまで内容確認ページでの支払時期を表示するものとなります。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>PayPay決済</th>
				<td><label><input name="paypay_activate" type="radio" id="paypay_activate_epsilon_on" value="on"<?php checked( $paypay_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="paypay_activate" type="radio" id="paypay_activate_epsilon_off" value="off"<?php checked( $paypay_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
		</table>
		<input name="acting" type="hidden" value="epsilon" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php echo esc_attr( $this->acting_name ); ?>の設定を更新する" />
			<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php echo esc_html( $this->acting_formal_name ); ?></strong></p>
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank"><?php echo esc_html( $this->acting_name ); ?>の詳細はこちら 》</a>
		<p>　</p>
		<p>この決済は「外部リンク型」の決済システムです。</p>
		<p>「外部リンク型」とは、決済会社のページへ遷移してカード情報を入力する決済システムです。</p>
	</div>
	</div><!--uscestabs_epsilon-->
			<?php
		endif;
	}

	/**
	 * 結果通知処理
	 * usces_after_cart_instant
	 */
	public function acting_transaction() {
		global $wpdb;

		if ( ! isset( $_GET['acting_return'] ) && isset( $_GET['trans_code'] ) && isset( $_GET['user_id'] ) && isset( $_GET['result'] ) && isset( $_GET['order_number'] ) ) {
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'epsilon',
						'acting_return' => '1',
						'trans_code'    => filter_input( INPUT_GET, 'trans_code', FILTER_SANITIZE_STRING ),
						'user_id'       => filter_input( INPUT_GET, 'user_id', FILTER_SANITIZE_STRING ),
						'result'        => filter_input( INPUT_GET, 'result', FILTER_SANITIZE_STRING ),
						'order_number'  => filter_input( INPUT_GET, 'order_number', FILTER_SANITIZE_STRING ),
					),
					USCES_CART_URL
				)
			);
			exit;

		} elseif ( isset( $_POST['trans_code'] ) && isset( $_POST['user_id'] ) && isset( $_POST['order_number'] ) ) {
			$post_data = array();
			foreach ( $_POST as $key => $value ) {
				$post_data[ $key ] = mb_convert_encoding( wp_unslash( $value ), 'UTF-8', 'SJIS' );
			}

			if ( '1' === $post_data['paid'] ) {
				$order_id = $this->get_order_id( $post_data['order_number'] );
				if ( ! $order_id ) {
					$log = array(
						'acting' => 'epsilon_conv',
						'key'    => $post_data['order_number'],
						'result' => 'ORDER DATA UPDATE ERROR1',
						'data'   => $post_data,
					);
					usces_save_order_acting_error( $log );
					// usces_log( 'Epsilon conv error1 : ' . print_r( $post_data, true ), 'acting_transaction.log' );
					exit( '0 999 ERROR1' );
				}

				$res = usces_change_order_receipt( $order_id, 'receipted' );
				if ( false === $res ) {
					$log = array(
						'acting' => 'epsilon_conv',
						'key'    => $post_data['order_number'],
						'result' => 'ORDER DATA UPDATE ERROR2',
						'data'   => $post_data,
					);
					usces_save_order_acting_error( $log );
					// usces_log( 'Epsilon conv error2 : ' . print_r( $data, true ), 'acting_transaction.log' );
					exit( '0 999 ERROR2' );
				}

				$mquery = $wpdb->prepare( "UPDATE {$wpdb->prefix}usces_order_meta SET `meta_value` = %s WHERE `meta_key` = %s AND `order_id` = %d", serialize( $post_data ), 'acting_epsilon_conv', $order_id );
				$res    = $wpdb->query( $mquery );
				if ( ! $res ) {
					$log = array(
						'acting' => 'epsilon_conv',
						'key'    => $post_data['order_number'],
						'result' => 'ORDER DATA UPDATE ERROR3',
						'data'   => $post_data,
					);
					usces_save_order_acting_error( $log );
					// usces_log( 'Epsilon conv error3 : ' . print_r( $post_data, true ), 'acting_transaction.log' );
					exit( '0 999 ERROR3' );
				}

				usces_action_acting_getpoint( $order_id );

				// usces_log( 'Epsilon conv transaction : ' . $post_data['settlement_id'], 'acting_transaction.log' );
				exit( '1' );
			}
		}
	}

	/**
	 * 受注ID 取得
	 *
	 * @param  string $order_number Order number.
	 * @return int
	 */
	protected function get_order_id( $order_number ) {
		global $wpdb;

		$query    = $wpdb->prepare( "SELECT `order_id` FROM {$wpdb->prefix}usces_order_meta WHERE `meta_key` = %s AND `meta_value` = %s", 'settlement_id', $order_number );
		$order_id = $wpdb->get_var( $query );
		return $order_id;
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
		$payments = usces_get_system_option( 'usces_payment_method', 'name' );
		if ( isset( $payments[ $payment_name ]['settlement'] ) && ( 'acting_epsilon_card' === $payments[ $payment_name ]['settlement'] || 'acting_epsilon_paypay' === $payments[ $payment_name ]['settlement'] ) ) {
			$complete = true;
		}
		return $complete;
	}

	/**
	 * 受注データ登録
	 * Called by usces_reg_orderdata() and usces_new_orderdata().
	 * usces_action_reg_orderdata
	 *
	 * @param  array $args Compact array( $cart, $entry, $order_id, $member_id, $payments, $charging_type, $results ).
	 */
	public function register_order_data( $args ) {
		global $usces;
		extract( $args ); // phpcs:ignore

		if ( ! $entry['order']['total_full_price'] ) {
			return;
		}

		if ( isset( $_GET['acting'] ) && isset( $_GET['acting_return'] ) && isset( $_GET['trans_code'] ) && 'epsilon' === filter_input( INPUT_GET, 'acting', FILTER_SANITIZE_STRING ) ) {
			$acting_flg   = ( isset( $payments['settlement'] ) ) ? $payments['settlement'] : '';
			$order_number = filter_input( INPUT_GET, 'order_number', FILTER_SANITIZE_STRING );
			$usces->set_order_meta_value( 'settlement_id', $order_number, $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $order_number, $order_id );
			if ( isset( $results['reg_order'] ) ) {
				unset( $results['0'] );
				unset( $results['reg_order'] );
			}
			$results['trans_code'] = filter_input( INPUT_GET, 'trans_code', FILTER_SANITIZE_STRING );
			if ( 'acting_epsilon_card' === $acting_flg ) {
				$user_id     = filter_input( INPUT_GET, 'user_id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE );
				$acting_opts = $this->get_acting_settings();
				if ( 'on' === $acting_opts['process_code'] && $user_id ) {
					$usces->del_member_meta( 'epsilon_process_code_release', $user_id );
					$results['user_id'] = $user_id;
				}
			}
			$usces->set_order_meta_value( $acting_flg, usces_serialize( $results ), $order_id );
		}
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
		array_push( $keys, 'trans_code', 'conveni_name', 'conveni_date' );
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
		return $value;
	}

	/**
	 * 受注データ復旧処理
	 * usces_action_revival_order_data
	 *
	 * @param  int    $order_id Order number.
	 * @param  string $log_key Link key.
	 * @param  string $acting_flg Payment type.
	 */
	public function revival_order_data( $order_id, $log_key, $acting_flg ) {
		global $usces;
		if ( in_array( $acting_flg, $this->pay_method, true ) ) {
			$usces->set_order_meta_value( 'settlement_id', $log_key, $order_id );
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
		if ( isset( $payment['settlement'] ) && 'acting_epsilon_conv' === $payment['settlement'] ) {
			$acting_opts = $this->get_acting_settings();
			if ( ! empty( $acting_opts['conv_limit'] ) ) {
				$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $acting_opts['conv_limit'] ) . __( ')', 'usces' );
				$str            = apply_filters( 'usces_filter_epsilon_payment_limit_conv', $payment_detail, $acting_opts['conv_limit'] );
			}
		}
		return $str;
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
		if ( in_array( $acting_flg, $this->pay_method, true ) ) {
			$form = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
				<div class="send">
				' . apply_filters( 'usces_filter_confirm_before_backbutton', null, $payments, $acting_flg, $rand ) . '
				<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="' . __( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_confirm_prebutton', null ) . ' />
				<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="' . apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) ) . '"' . $purchase_disabled . ' /></div>
				<input type="hidden" name="rand" value="' . esc_attr( $rand ) . '">
				<input type="hidden" name="_nonce" value="' . wp_create_nonce( $acting_flg ) . '">';
		}
		return $form;
	}

	/**
	 * 決済処理
	 * usces_action_acting_processing
	 *
	 * @param  string $acting_flg Payment type.
	 * @param  array  $post_query Post data.
	 */
	public function acting_processing( $acting_flg, $post_query ) {
		if ( ! in_array( $acting_flg, $this->pay_method, true ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $acting_flg ) ) {
			wp_redirect( USCES_CART_URL );
		}

		global $usces;
		$entry = $usces->cart->get_entry();
		$cart  = $usces->cart->get_cart();
		if ( ! $entry || ! $cart ) {
			wp_redirect( USCES_CART_URL );
		}

		$delim       = apply_filters( 'usces_filter_delim', $usces->delim );
		$acting      = substr( $acting_flg, 7 );
		$acting_opts = $this->get_acting_settings();
		$rand        = wp_unslash( $_REQUEST['rand'] );
		usces_save_order_acting_data( $rand );
		$user_name = mb_strimwidth( $entry['customer']['name1'] . $entry['customer']['name2'], 0, 64, '', 'UTF-8' );
		$item_code = mb_convert_kana( $usces->getItemCode( $cart[0]['post_id'] ), 'a', 'UTF-8' );
		$item_name = $usces->getItemName( $cart[0]['post_id'] );
		if ( 1 < count( $cart ) ) {
			$item_name .= ' ' . __( 'Others', 'usces' );
		}
		if ( 32 < mb_strlen( $item_name, 'UTF-8' ) ) {
			$item_name = mb_strimwidth( $item_name, 0, 28, '...', 'UTF-8' );
		}

		switch ( $acting_flg ) {
			case 'acting_epsilon_card': /* クレジットカード決済 */
				if ( 'on' === $acting_opts['multi_currency'] ) {
					$st_code      = '10000-0000-00000-00001-00000-00000-00000';
					$currency_id  = $usces->get_currency_code();
					$user_id      = '-';
					$process_code = '1';
				} else {
					$st_code     = '10000-0000-00000-00000-00000-00000-00000';
					$currency_id = '';
					if ( 'on' === $acting_opts['process_code'] ) {
						$member = $usces->get_member();
						if ( ! empty( $member['ID'] ) ) {
							$release      = $usces->get_member_meta_value( 'epsilon_process_code_release', $member['ID'] );
							$user_id      = $member['ID'];
							$process_code = ( empty( $release ) ) ? '2' : '4';
						} else {
							$user_id      = '-';
							$process_code = '1';
						}
					} else {
						$user_id      = '-';
						$process_code = '1';
					}
				}
				$threedsecure = ( 'on' === $acting_opts['3dsecure'] ) ? '21' : '';
				break;
			case 'acting_epsilon_conv': /* コンビニ決済 */
				$st_code      = '00100-0000-00000-00000-00000-00000-00000';
				$user_id      = '-';
				$currency_id  = '';
				$process_code = '1';
				break;
			case 'acting_epsilon_paypay': /* PayPay決済 */
				$st_code      = '00000-0000-00000-00000-00000-10000-00000';
				$user_id      = '-';
				$currency_id  = '';
				$process_code = '1';
				break;
		}

		$send_data = array(
			'version'        => '2',
			'contract_code'  => $acting_opts['contract_code'],
			'user_id'        => $user_id,
			'user_name'      => $user_name,
			'user_mail_add'  => $entry['customer']['mailaddress1'],
			'item_code'      => $item_code,
			'item_name'      => $item_name,
			'order_number'   => $rand,
			'st_code'        => $st_code,
			'mission_code'   => '1',
			'item_price'     => $entry['order']['total_full_price'],
			'process_code'   => $process_code,
			'memo1'          => '',
			'memo2'          => 'wc1collne',
			'xml'            => '1',
			'character_code' => 'UTF8',
			'currency_id'    => $currency_id,
		);
		if ( 'acting_epsilon_conv' === $acting_flg ) {
			$send_data['user_tel']       = str_replace( '-', '', mb_convert_kana( $entry['customer']['tel'], 'a', 'UTF-8' ) );
			$send_data['user_name_kana'] = $entry['customer']['name3'] . $entry['customer']['name4'];
		}
		if ( ! empty( $threedsecure ) ) {
			$send_data['tds_flag'] = $threedsecure;
		}
		$vars      = http_build_query( $send_data );
		$host      = parse_url( USCES_CART_URL );
		$interface = parse_url( $acting_opts['send_url'] );

		$request  = 'POST ' . $acting_opts['send_url'] . " HTTP/1.1\r\n";
		$request .= 'Host: ' . $host['host'] . "\r\n";
		$request .= "User-Agent: PHP Script\r\n";
		$request .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$request .= 'Content-Length: ' . strlen( $vars ) . "\r\n";
		$request .= "Connection: close\r\n\r\n";
		$request .= $vars;

		$fp = @stream_socket_client( 'tlsv1.2://' . $interface['host'] . ':443', $errno, $errstr, 30 );
		if ( ! $fp ) {
			// usces_log( 'Epsilon : TLS(v1.2) Socket Error', 'acting_transaction.log' );
			$log = array(
				'acting' => $acting,
				'key'    => $rand,
				'result' => 'SSL/TLS ERROR (' . $errno . ')',
				'data'   => array( $errstr ),
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'epsilon',
						'acting_return' => '0',
					),
					USCES_CART_URL
				)
			);
			exit;
		}

		fwrite( $fp, $request );
		while ( ! feof( $fp ) ) {
			$scr = fgets( $fp, 1024 );
			preg_match_all( '/<result\s(.*)\s\/>/', $scr, $match, PREG_SET_ORDER );
			if ( ! empty( $match[0][1] ) ) {
				list( $key, $value ) = explode( '=', $match[0][1] );
				$datas[ $key ]       = urldecode( trim( $value, '"' ) );
			}
		}
		fclose( $fp );
		if ( 1 === (int) $datas['result'] ) {
			wp_redirect( $datas['redirect'] );
		} else {
			// usces_log( 'Epsilon : Certification Error' . print_r( $datas, true ), 'acting_transaction.log' );
			$err_code   = ( isset( $datas['err_code'] ) ) ? urlencode( $datas['err_code'] ) : '';
			$err_detail = ( isset( $datas['err_detail'] ) ) ? urlencode( $datas['err_detail'] ) : '';
			$log        = array(
				'acting' => $acting,
				'key'    => $rand,
				'result' => $err_code,
				'data'   => $datas,
			);
			usces_save_order_acting_error( $log );
			wp_redirect(
				add_query_arg(
					array(
						'acting'        => 'epsilon',
						'acting_return' => '0',
					),
					USCES_CART_URL
				)
			);
		}
		exit;
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
		if ( isset( $_GET['acting'] ) && 'epsilon' === filter_input( INPUT_GET, 'acting', FILTER_SANITIZE_STRING ) ) {
			$payments = usces_get_payments_by_name( $entry['order']['payment_name'] );
			if ( isset( $payments['settlement'] ) && 'acting_epsilon_conv' === $payments['settlement'] ) {
				$form .= '<div id="status_table"><h5>イプシロン・コンビニ決済</h5>
					<p>「お支払いのご案内」は、' . esc_html( $entry['customer']['mailaddress1'] ) . '　宛にメールさせていただいております。</p>
					</div>' . "\n";
			}
		}
		return $form;
	}

	/**
	 * 会員データ編集画面 カード情報登録情報
	 * usces_action_admin_member_info
	 *
	 * @param  array $member Member data.
	 * @param  array $member_metas Member meta data.
	 * @param  array $member_history Member's history order data.
	 */
	public function admin_member_info( $member, $member_metas, $member_history ) {
		if ( ! $this->is_activate_card() ) {
			return;
		}

		$acting_opts = $this->get_acting_settings();
		if ( 'on' !== $acting_opts['process_code'] ) {
			return;
		}

		global $usces;

		$epsilon_card = false;
		foreach ( $member_history as $history ) {
			$payments   = usces_get_payments_by_name( $history['payment_name'] );
			$settlement = ( 'acting' === $payments['settlement'] ) ? $payments['module'] : $payments['settlement'];
			if ( 'acting_epsilon_card' === $settlement ) {
				$epsilon_card = true;
				break;
			}
		}

		if ( 0 < count( $member_history ) && $epsilon_card ) :
			?>
		<tr>
			<td class="label"><input type="checkbox" name="epsilon_process_code_release" id="epsilon_process_code_release" value="release"></td>
			<td><label for="epsilon_process_code_release">登録済み課金を解除する</label></td>
		</tr>
			<?php
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

		global $usces;

		$acting_opts = $this->get_acting_settings();
		if ( 'on' === $acting_opts['process_code'] ) {
			if ( filter_input( INPUT_POST, 'epsilon_process_code_release', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE ) ) {
				$usces->set_member_meta_value( 'epsilon_process_code_release', 'release', $member_id );
			} else {
				$usces->del_member_meta( 'epsilon_process_code_release', $member_id );
			}
		}
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
}
