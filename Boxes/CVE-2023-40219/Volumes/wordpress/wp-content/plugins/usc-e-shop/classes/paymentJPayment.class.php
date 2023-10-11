<?php
/**
 * ロボットペイメント
 * (旧 Cloud Payment )
 * (旧 J-Payment )
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */

/**
 * ロボットペイメント
 */
class JPAYMENT_SETTLEMENT {
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

		$this->paymod_id          = 'jpayment';
		$this->pay_method         = array(
			'acting_jpayment_card',
			'acting_jpayment_conv',
			'acting_jpayment_bank',
		);
		$this->acting_name        = 'ROBOT PAYMENT';
		$this->acting_formal_name = 'ROBOT PAYMENT';
		$this->acting_company_url = 'https://www.robotpayment.co.jp/';

		$this->initialize_data();

		if ( is_admin() ) {
			// add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if ( $this->is_activate_card() || $this->is_activate_conv() || $this->is_activate_bank() ) {
			add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
		}

		if ( $this->is_activate_conv() || $this->is_activate_bank() ) {
			add_filter( 'usces_filter_order_confirm_mail_payment', array( $this, 'order_confirm_mail_payment' ), 10, 5 );
			add_filter( 'usces_filter_send_order_mail_payment', array( $this, 'send_order_mail_payment' ), 10, 6 );
		}

		if ( $this->is_validity_acting( 'conv' ) ) {
			add_filter( 'usces_filter_payment_detail', array( $this, 'payment_detail' ), 10, 2 );
			add_action( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
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
		if ( ! isset( $options['acting_settings'] ) || ! isset( $options['acting_settings']['jpayment'] ) ) {
			$options['acting_settings']['jpayment']['activate']       = '';
			$options['acting_settings']['jpayment']['aid']            = '';
			$options['acting_settings']['jpayment']['card_activate']  = 'off';
			$options['acting_settings']['jpayment']['card_jb']        = '';
			$options['acting_settings']['jpayment']['conv_activate']  = 'off';
			$options['acting_settings']['jpayment']['conv_limit']     = '14';
			$options['acting_settings']['jpayment']['webm_activate']  = 'off';
			$options['acting_settings']['jpayment']['bitc_activate']  = 'off';
			$options['acting_settings']['jpayment']['suica_activate'] = 'off';
			$options['acting_settings']['jpayment']['bank_activate']  = 'off';
			update_option( 'usces', $options );
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
					if ( 'acting_jpayment_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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
					if ( 'acting_jpayment_conv' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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

			case 'bank':
				foreach ( $payment_method as $payment ) {
					if ( 'acting_jpayment_bank' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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

			default:
				if ( 'on' == $acting_opts['activate'] ) {
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
		if ( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) &&
			( isset( $acting_opts['card_activate'] ) && ( 'on' == $acting_opts['card_activate'] ) ) ) {
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
		if ( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) &&
			( isset( $acting_opts['conv_activate'] ) && 'on' == $acting_opts['conv_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * バンクチェック決済有効判定
	 *
	 * @return boolean
	 */
	public function is_activate_bank() {

		$acting_opts = $this->get_acting_settings();
		if ( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) &&
			( isset( $acting_opts['bank_activate'] ) && 'on' == $acting_opts['bank_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * 管理画面スクリプト
	 * admin_print_footer_scripts
	 */
	public function admin_scripts() {

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

		$this->error_mes = '';
		$options         = get_option( 'usces', array() );
		$payment_method  = usces_get_system_option( 'usces_payment_method', 'settlement' );

		unset( $options['acting_settings']['jpayment'] );
		$options['acting_settings']['jpayment']['aid']           = ( isset( $_POST['aid'] ) ) ? trim( wp_unslash( $_POST['aid'] ) ) : '';
		$options['acting_settings']['jpayment']['card_activate'] = ( isset( $_POST['card_activate'] ) ) ? wp_unslash( $_POST['card_activate'] ) : '';
		$options['acting_settings']['jpayment']['card_jb']       = ( isset( $_POST['card_jb'] ) ) ? wp_unslash( $_POST['card_jb'] ) : '';
		$options['acting_settings']['jpayment']['conv_activate'] = ( isset( $_POST['conv_activate'] ) ) ? wp_unslash( $_POST['conv_activate'] ) : '';
		$options['acting_settings']['jpayment']['conv_limit']    = ( isset( $_POST['conv_limit'] ) ) ? $_POST['conv_limit'] : '14';
		$options['acting_settings']['jpayment']['bank_activate'] = ( isset( $_POST['bank_activate'] ) ) ? wp_unslash( $_POST['bank_activate'] ) : '';

		if ( WCUtils::is_blank( $_POST['aid'] ) ) {
			$this->error_mes .= '※店舗IDコードを入力してください<br />';
		}
		if ( isset( $_POST['card_activate'] ) && 'on' == wp_unslash( $_POST['card_activate'] ) && empty( $_POST['card_jb'] ) ) {
			$this->error_mes .= '※ジョブタイプを指定してください<br />';
		}

		if ( '' == $this->error_mes ) {
			$usces->action_status  = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if ( 'on' == $options['acting_settings']['jpayment']['card_activate'] || 'on' == $options['acting_settings']['jpayment']['conv_activate'] || 'on' == $options['acting_settings']['jpayment']['bank_activate'] ) {
				$options['acting_settings']['jpayment']['activate'] = 'on';
				$options['acting_settings']['jpayment']['send_url'] = 'https://credit.j-payment.co.jp/gateway/payform.aspx';
				$toactive = array();
				if ( 'on' == $options['acting_settings']['jpayment']['card_activate'] ) {
					$usces->payment_structure['acting_jpayment_card'] = 'カード決済（' . $this->acting_name . '）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_jpayment_card' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_jpayment_card'] );
				}
				if ( 'on' == $options['acting_settings']['jpayment']['conv_activate'] ) {
					$usces->payment_structure['acting_jpayment_conv'] = 'コンビニ決済（' . $this->acting_name . '）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_jpayment_conv' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_jpayment_conv'] );
				}
				if ( 'on' == $options['acting_settings']['jpayment']['bank_activate'] ) {
					$usces->payment_structure['acting_jpayment_bank'] = 'バンクチェック決済（' . $this->acting_name . '）';
					foreach ( $payment_method as $settlement => $payment ) {
						if ( 'acting_jpayment_bank' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_jpayment_bank'] );
				}
				usces_admin_orderlist_show_wc_trans_id();
				if ( 0 < count( $toactive ) ) {
					$usces->action_message .= __( 'Please update the payment method to "Activate". <a href="admin.php?page=usces_initial#payment_method_setting">General Setting > Payment Methods</a>', 'usces' );
				}
			} else {
				$options['acting_settings']['jpayment']['activate'] = 'off';
				unset( $usces->payment_structure['acting_jpayment_card'] );
				unset( $usces->payment_structure['acting_jpayment_conv'] );
				unset( $usces->payment_structure['acting_jpayment_bank'] );
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
			$usces->action_status                               = 'error';
			$usces->action_message                              = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['jpayment']['activate'] = 'off';
			unset( $usces->payment_structure['acting_jpayment_card'] );
			unset( $usces->payment_structure['acting_jpayment_conv'] );
			unset( $usces->payment_structure['acting_jpayment_bank'] );
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

		$settlement_selected = get_option( 'usces_settlement_selected' );
		if ( in_array( $this->paymod_id, (array) $settlement_selected ) ) :
			$acting_opts    = $this->get_acting_settings();
			$aid            = ( isset( $acting_opts['aid'] ) ) ? $acting_opts['aid'] : '';
			$card_activate  = ( isset( $acting_opts['card_activate'] ) && 'on' == $acting_opts['card_activate'] ) ? 'on' : 'off';
			$card_jb        = ( isset( $acting_opts['card_jb'] ) ) ? $acting_opts['card_jb'] : '';
			$conv_activate  = ( isset( $acting_opts['conv_activate'] ) && 'on' == $acting_opts['conv_activate'] ) ? 'on' : 'off';
			$webm_activate  = ( isset( $acting_opts['webm_activate'] ) && 'on' == $acting_opts['webm_activate'] ) ? 'on' : 'off';
			$bitc_activate  = ( isset( $acting_opts['bitc_activate'] ) && 'on' == $acting_opts['bitc_activate'] ) ? 'on' : 'off';
			$suica_activate = ( isset( $acting_opts['suica_activate'] ) && 'on' == $acting_opts['suica_activate'] ) ? 'on' : 'off';
			$bank_activate  = ( isset( $acting_opts['bank_activate'] ) && 'on' == $acting_opts['bank_activate'] ) ? 'on' : 'off';
			?>
	<div id="uscestabs_jpayment">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
			<?php
			if ( isset( $_POST['acting'] ) && 'jpayment' == wp_unslash( $_POST['acting'] ) ) :
				if ( '' != $this->error_mes ) :
					?>
		<div class="error_message"><?php wel_esc_script_e( $this->error_mes ); ?></div>
			<?php elseif ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) : ?>
		<div class="message">十分にテストを行ってから運用してください。</div>
					<?php
				endif;
			endif;
			?>
	<form action="" method="post" name="jpayment_form" id="jpayment_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_aid_jpayment">店舗ID</a></th>
				<td><input name="aid" type="text" id="aid_jpayment" value="<?php echo esc_html( $aid ); ?>" class="regular-text" maxlength="6" /></td>
			</tr>
			<tr id="ex_aid_jpayment" class="explanation"><td colspan="2">契約時に <?php echo esc_html( $this->acting_name ); ?> から発行される店舗ID（半角数字）</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_card_jpayment">クレジットカード決済</a></th>
				<td><label><input name="card_activate" type="radio" id="card_activate_jpayment_1" value="on"<?php checked( $card_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" id="card_activate_jpayment_2" value="off"<?php checked( $card_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_card_jpayment" class="explanation"><td colspan="2">クレジットカード決済を利用するかどうか。<br />※自動継続課金には対応していません。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_card_jb_jpayment">ジョブタイプ</a></th>
				<td><!--<label><input name="card_jb" type="radio" id="card_jb_jpayment_1" value="CHECK"<?php checked( $card_jb, 'CHECK' ); ?> /><span>有効性チェック</span></label><br />-->
					<label><input name="card_jb" type="radio" id="card_jb_jpayment_2" value="AUTH"<?php checked( $card_jb, 'AUTH' ); ?> /><span>仮売上処理</span></label><br />
					<label><input name="card_jb" type="radio" id="card_jb_jpayment_3" value="CAPTURE"<?php checked( $card_jb, 'CAPTURE' ); ?> /><span>仮実同時売上処理</span></label>
				</td>
			</tr>
			<tr id="ex_card_jb_jpayment" class="explanation"><td colspan="2">決済の種類を指定します。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_conv_jpayment">コンビニ決済</a></th>
				<td><label><input name="conv_activate" type="radio" id="conv_activate_jpayment_1" value="on"<?php checked( $conv_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="conv_activate" type="radio" id="conv_activate_jpayment_2" value="off"<?php checked( $conv_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_conv_jpayment" class="explanation"><td colspan="2">コンビニ（ペーパーレス）決済を利用するかどうか。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_conv_limit_jpayment">支払期限</a></th>
				<td>
			<?php
			$selected = array_fill( 1, 30, '' );
			if ( isset( $acting_opts['conv_limit'] ) ) {
				$selected[ $acting_opts['conv_limit'] ] = ' selected';
			} else {
				$selected[14] = ' selected';
			}
			?>
				<select name="conv_limit" id="conv_limit">
			<?php for ( $i = 1; $i <= 30; $i++ ) : ?>
					<option value="<?php echo esc_html( $i ); ?>"<?php echo esc_html( $selected[ $i ] ); ?>><?php echo esc_html( $i ); ?></option>
			<?php endfor; ?>
				</select>日</td>
			</tr>
			<tr id="ex_conv_limit_jpayment" class="explanation"><td colspan="2">設定する場合は契約時の支払期限と合わせる必要があります。変更したい場合は <?php echo esc_html( $this->acting_name ); ?> にお申し込みください。ここでの設定はあくまで内容確認ページでの支払時期を表示するものとなります。</td></tr>
		</table>
		<!--<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_webm_jpayment">WebMoney決済</a></th>
				<td><label><input name="webm_activate" type="radio" id="webm_activate_jpayment_1" value="on"<?php checked( $webm_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="webm_activate" type="radio" id="webm_activate_jpayment_2" value="off"<?php checked( $webm_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_webm_jpayment" class="explanation"><td colspan="2">電子マネー（WebMoney）決済を利用するかどうか。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_bitc_jpayment">BitCash決済</a></th>
				<td><label><input name="bitc_activate" type="radio" id="bitc_activate_jpayment_1" value="on"<?php checked( $bitc_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="bitc_activate" type="radio" id="bitc_activate_jpayment_2" value="off"<?php checked( $bitc_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_bitc_jpayment" class="explanation"><td colspan="2">電子マネー（BitCash）決済を利用するかどうか。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_suica_jpayment">モバイルSuica決済</a></th>
				<td><label><input name="suica_activate" type="radio" id="suica_activate_jpayment_1" value="on"<?php checked( $suica_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="suica_activate" type="radio" id="suica_activate_jpayment_2" value="off"<?php checked( $suica_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_suica_jpayment" class="explanation"><td colspan="2">電子マネー（モバイルSuica）決済を利用するかどうか。</td></tr>
		</table>-->
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_bank_jpayment">バンクチェック決済</a></th>
				<td><label><input name="bank_activate" type="radio" id="bank_activate_jpayment_1" value="on"<?php checked( $bank_activate, 'on' ); ?> /><span>利用する</span></label><br />
					<label><input name="bank_activate" type="radio" id="bank_activate_jpayment_2" value="off"<?php checked( $bank_activate, 'off' ); ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_bank_jpayment" class="explanation"><td colspan="2">バンクチェック決済を利用するかどうか。</td></tr>
		</table>
		<input name="acting" type="hidden" value="jpayment" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php echo esc_attr( $this->acting_name ); ?> の設定を更新する" />
			<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php echo esc_html( $this->acting_formal_name ); ?></strong></p>
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank"><?php echo esc_html( $this->acting_name ); ?> の詳細はこちら 》</a>
		<p>　</p>
		<p>この決済は「外部リンク型」の決済システムです。</p>
		<p>「外部リンク型」とは、決済会社のページへ遷移してカード情報を入力する決済システムです。</p>
	</div>
	</div><!--uscestabs_jpayment-->
			<?php
		endif;
	}

	/**
	 * 受注データ登録
	 * Called by usces_reg_orderdata() and usces_new_orderdata().
	 * usces_action_reg_orderdata
	 *
	 * @param array $args Compact array( $cart, $entry, $order_id, $member_id, $payments, $charging_type, $results ).
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

		if ( isset( $_REQUEST['acting'] ) && ( 'jpayment_card' == $_REQUEST['acting'] || 'jpayment_conv' == $_REQUEST['acting'] || 'jpayment_bank' == $_REQUEST['acting'] ) ) {
			$usces->set_order_meta_value( 'settlement_id', wp_unslash( $_GET['cod'] ), $order_id );
			if ( ! empty( $_GET['gid'] ) ) {
				$usces->set_order_meta_value( 'wc_trans_id', wp_unslash( $_GET['gid'] ), $order_id );
			}
			foreach ( $_GET as $key => $value ) {
				if ( 'purchase_jpayment' != $key ) {
					$data[ $key ] = wp_unslash( $value );
				}
			}
			$usces->set_order_meta_value( 'acting_' . wp_unslash( $_REQUEST['acting'] ), serialize( $data ), $order_id );
		}
	}

	/**
	 * 管理画面送信メール
	 * usces_filter_order_confirm_mail_payment
	 *
	 * @param  string $msg_payment Payment information message.
	 * @param  int    $order_id Order number.
	 * @param  array  $payment Payment data.
	 * @param  array  $cart Cart data.
	 * @param  array  $data Order data.
	 * @return string
	 */
	public function order_confirm_mail_payment( $msg_payment, $order_id, $payment, $cart, $data ) {
		global $usces;

		if ( 'acting_jpayment_conv' == $payment['settlement'] ) {
			$args         = maybe_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
			$msg_payment .= '決済番号 : ' . $args['gid'] . "\r\n";
			$msg_payment .= '決済金額 : ' . number_format( $args['ta'] ) . __( 'dollars', 'usces' ) . "\r\n";
			$msg_payment .= 'お支払先 : ' . usces_get_conv_name( $args['cv'] ) . "\r\n";
			$msg_payment .= 'コンビニ受付番号 : ' . $args['no'] . "\r\n";
			if ( '030' != $args['cv'] ) { /* ファミリーマート以外 */
				$msg_payment .= 'コンビニ受付番号情報URL : ' . $args['cu'] . "\r\n";
			}
			$msg_payment .= "\r\n" . usces_mail_line( 2, $data['order_email'] ) . "\r\n";

		} elseif ( 'acting_jpayment_bank' == $payment['settlement'] ) {
			$args         = maybe_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
			$msg_payment .= '決済番号 : ' . $args['gid'] . "\r\n";
			$msg_payment .= '決済金額 : ' . number_format( $args['ta'] ) . __( 'dollars', 'usces' ) . "\r\n";
			$bank         = explode( '.', $args['bank'] );
			$msg_payment .= '銀行コード : ' . $bank[0] . "\r\n";
			$msg_payment .= '銀行名 : ' . $bank[1] . "\r\n";
			$msg_payment .= '支店コード : ' . $bank[2] . "\r\n";
			$msg_payment .= '支店名 : ' . $bank[3] . "\r\n";
			$msg_payment .= '口座種別 : ' . $bank[4] . "\r\n";
			$msg_payment .= '口座番号 : ' . $bank[5] . "\r\n";
			$msg_payment .= '口座名義 : ' . $bank[6] . "\r\n";
			$msg_payment .= '支払期限 : ' . substr( $args['exp'], 0, 4 ) . '年' . substr( $args['exp'], 4, 2 ) . '月' . substr( $args['exp'], 6, 2 ) . "日\r\n";
			$msg_payment .= "\r\n" . usces_mail_line( 2, $data['order_email'] ) . "\r\n";
		}
		return $msg_payment;
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
	public function send_order_mail_payment( $msg_payment, $order_id, $payment, $cart, $entry, $data ) {
		global $usces;

		if ( 'acting_jpayment_conv' == $payment['settlement'] ) {
			$args         = maybe_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
			$msg_payment .= '決済番号 : ' . $args['gid'] . "\r\n";
			$msg_payment .= '決済金額 : ' . number_format( $args['ta'] ) . __( 'dollars', 'usces' ) . "\r\n";
			$msg_payment .= 'お支払先 : ' . usces_get_conv_name( $args['cv'] ) . "\r\n";
			$msg_payment .= 'コンビニ受付番号 : ' . $args['no'] . "\r\n";
			if ( '030' != $args['cv'] ) { /* ファミリーマート以外 */
				$msg_payment .= 'コンビニ受付番号情報URL : ' . $args['cu'] . "\r\n";
			}
			$msg_payment .= "\r\n" . usces_mail_line( 2, $entry['customer']['mailaddress1'] ) . "\r\n";

		} elseif ( 'acting_jpayment_bank' == $payment['settlement'] ) {
			$args         = maybe_unserialize( $usces->get_order_meta_value( $payment['settlement'], $order_id ) );
			$msg_payment .= '決済番号 : ' . $args['gid'] . "\r\n";
			$msg_payment .= '決済金額 : ' . number_format( $args['ta'] ) . __( 'dollars', 'usces' ) . "\r\n";
			$bank         = explode( '.', $args['bank'] );
			$msg_payment .= '銀行コード : ' . $bank[0] . "\r\n";
			$msg_payment .= '銀行名 : ' . $bank[1] . "\r\n";
			$msg_payment .= '支店コード : ' . $bank[2] . "\r\n";
			$msg_payment .= '支店名 : ' . $bank[3] . "\r\n";
			$msg_payment .= '口座種別 : ' . $bank[4] . "\r\n";
			$msg_payment .= '口座番号 : ' . $bank[5] . "\r\n";
			$msg_payment .= '口座名義 : ' . $bank[6] . "\r\n";
			$msg_payment .= '支払期限 : ' . substr( $args['exp'], 0, 4 ) . '年' . substr( $args['exp'], 4, 2 ) . '月' . substr( $args['exp'], 6, 2 ) . "日\r\n";
			$msg_payment .= "\r\n" . usces_mail_line( 2, $entry['customer']['mailaddress1'] ) . "\r\n";
		}
		return $msg_payment;
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
		if ( isset( $payment['settlement'] ) && 'acting_jpayment_conv' == $payment['settlement'] ) {
			$acting_opts    = $this->get_acting_settings();
			if ( ! empty( $acting_opts['conv_limit'] ) ) {
				$payment_detail = __( '(', 'usces' ) . sprintf( __( 'Payment is valid for %s days from the date of order.', 'usces' ), $acting_opts['conv_limit'] ) . __( ')', 'usces' );
				$str            = apply_filters( 'usces_filter_jpayment_payment_limit_conv', $payment_detail, $acting_opts['conv_limit'] );
			}
		}
		return $str;
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
		global $usces;

		if ( isset( $_REQUEST['acting'] ) && 'jpayment_conv' == $_REQUEST['acting'] ) {
			$form .= '<div id="status_table"><h5>' . esc_html( $this->acting_formal_name ) . ' コンビニペーパーレス決済</h5>';
			$form .= '<table>';
			$form .= '<tr><th>決済番号</th><td>' . esc_html( wp_unslash( $_GET['gid'] ) ) . '</td></tr>';
			$form .= '<tr><th>決済金額</th><td>' . esc_html( wp_unslash( $_GET['ta'] ) ) . '</td></tr>';
			$form .= '<tr><th>お支払先</th><td>' . esc_html( usces_get_conv_name( wp_unslash( $_GET['cv'] ) ) ) . '</td></tr>';
			$form .= '<tr><th>コンビニ受付番号</th><td>' . esc_html( wp_unslash( $_GET['no'] ) ) . '</td></tr>';
			if ( '030' != wp_unslash( $_GET['cv'] ) ) { /* ファミリーマート以外 */
				$form .= '<tr><th>コンビニ受付番号情報URL</th><td><a href="' . esc_html( wp_unslash( $_GET['cu'] ) ) . '" target="_blank">' . esc_html( wp_unslash( $_GET['cu'] ) ) . '</a></td></tr>';
			}
			$form .= '</table>';
			$form .= '<p>「お支払いのご案内」は、' . esc_html( $entry['customer']['mailaddress1'] ) . '　宛にメールさせていただいております。</p>';
			$form .= '</div>';
		}
		return $form;
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
