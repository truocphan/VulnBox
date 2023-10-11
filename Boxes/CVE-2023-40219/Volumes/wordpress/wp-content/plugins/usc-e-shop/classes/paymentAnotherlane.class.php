<?php
/**
 * アナザーレーン
 *
 * @class    ANOTHERLANE_SETTLEMENT
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class ANOTHERLANE_SETTLEMENT
{
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	protected $paymod_id;			//決済代行会社ID
	protected $pay_method;			//決済種別
	protected $acting_name;			//決済代行会社略称
	protected $acting_formal_name;	//決済代行会社正式名称
	protected $acting_company_url;	//決済代行会社URL

	protected $error_mes;

	public function __construct() {

		$this->paymod_id = 'anotherlane';
		$this->pay_method = array(
			'acting_anotherlane_card'
		);
		$this->acting_name = 'アナザーレーン';
		$this->acting_formal_name = 'アナザーレーン';
		$this->acting_company_url = 'http://www.alij.ne.jp/';

		$this->initialize_data();

		if( is_admin() ) {
			//add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if( $this->is_activate_card() ) {
			add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initialize
	 */
	public function initialize_data() {

		$options = get_option( 'usces' );
		if( !isset( $options['acting_settings'] ) || !isset( $options['acting_settings']['anotherlane'] ) ) {
			$options['acting_settings']['anotherlane']['siteid'] = '';
			$options['acting_settings']['anotherlane']['sitepass'] = '';
			$options['acting_settings']['anotherlane']['ope'] = '';
			$options['acting_settings']['anotherlane']['quickcharge'] = '';
			$options['acting_settings']['anotherlane']['card_activate'] = 'off';
			update_option( 'usces', $options );
		}
	}

	/**
	 * 決済有効判定
	 * 引数が指定されたとき、支払方法で使用している場合に「有効」とする
	 * @param  ($type)
	 * @return boolean
	 */
	public function is_validity_acting( $type = '' ) {

		$acting_opts = $this->get_acting_settings();
		if( empty( $acting_opts ) ) {
			return false;
		}

		$payment_method = usces_get_system_option( 'usces_payment_method', 'sort' );
		$method = false;

		switch( $type ) {
		case 'card':
			foreach( $payment_method as $payment ) {
				if( 'acting_anotherlane_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
					$method = true;
					break;
				}
			}
			if( $method && $this->is_activate_card() ) {
				return true;
			} else {
				return false;
			}
			break;

		default:
			if( 'on' == $acting_opts['activate'] ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * クレジットカード決済有効判定
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_card() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['card_activate'] ) && ( 'on' == $acting_opts['card_activate'] ) ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * @fook   admin_print_footer_scripts
	 * @param  -
	 * @return -
	 * @echo   js
	 */
	public function admin_scripts() {

	}

	/**
	 * 決済オプション登録・更新
	 * @fook   usces_action_admin_settlement_update
	 * @param  -
	 * @return -
	 */
	public function settlement_update() {
		global $usces;

		if( $this->paymod_id != $_POST['acting'] ) {
			return;
		}

		$this->error_mes = '';
		$options = get_option( 'usces' );
		$payment_method = usces_get_system_option( 'usces_payment_method', 'settlement' );

		unset( $options['acting_settings']['anotherlane'] );
		$options['acting_settings']['anotherlane']['siteid'] = ( isset( $_POST['siteid'] ) ) ? trim( $_POST['siteid'] ) : '';
		$options['acting_settings']['anotherlane']['sitepass'] = ( isset( $_POST['sitepass'] ) ) ? trim( $_POST['sitepass'] ) : '';
		$options['acting_settings']['anotherlane']['ope'] = ( isset( $_POST['ope'] ) ) ? $_POST['ope'] : '';
		$options['acting_settings']['anotherlane']['quickcharge'] = ( isset( $_POST['quickcharge'] ) ) ? $_POST['quickcharge'] : '';
		$options['acting_settings']['anotherlane']['card_activate'] = ( isset( $_POST['card_activate'] ) ) ? $_POST['card_activate'] : 'off';

		if( 'on' == $options['acting_settings']['anotherlane']['card_activate'] ) {
			if( WCUtils::is_blank( $_POST['siteid'] ) ) {
				$this->error_mes .= '※サイトIDを入力してください<br />';
			}
			if( WCUtils::is_blank( $_POST['sitepass'] ) ) {
				$this->error_mes .= '※サイトパスワードを入力してください<br />';
			}
			if( WCUtils::is_blank( $options['acting_settings']['anotherlane']['ope'] ) ) {
				$this->error_mes .= '※稼働環境を選択してください<br />';
			}
		}

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['anotherlane']['card_activate'] ) {
				$options['acting_settings']['anotherlane']['activate'] = 'on';
				if( 'public' == $options['acting_settings']['anotherlane']['ope'] ) {
					$options['acting_settings']['anotherlane']['send_url'] = "https://payment.alij.ne.jp/service/credit";
				} else {
					$options['acting_settings']['anotherlane']['send_url'] = "https://test-payment.alij.ne.jp/service/credit";
				}
				$usces->payment_structure['acting_anotherlane_card'] = 'カード決済（'.$this->acting_name.'）';
				usces_admin_orderlist_show_wc_trans_id();
				$toactive = array();
				foreach( $payment_method as $settlement => $payment ) {
					if( 'acting_anotherlane_card' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			} else {
				$options['acting_settings']['anotherlane']['activate'] = 'off';
				unset( $usces->payment_structure['acting_anotherlane_card'] );
				$deactivate = array();
				foreach( $payment_method as $settlement => $payment ) {
					if( !array_key_exists( $settlement, $usces->payment_structure ) ) {
						if( 'deactivate' != $payment['use'] ) {
							$payment['use'] = 'deactivate';
							$deactivate[] = $payment['name'];
							usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
						}
					}
				}
				if( 0 < count( $deactivate ) ) {
					$deactivate_message = sprintf( __( "\"Deactivate\" %s of payment method.", 'usces' ), implode( ',', $deactivate ) );
					$usces->action_message .= $deactivate_message;
				}
			}
		} else {
			$usces->action_status = 'error';
			$usces->action_message = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['anotherlane']['activate'] = 'off';
			unset( $usces->payment_structure['acting_anotherlane_card'] );
			$deactivate = array();
			foreach( $payment_method as $settlement => $payment ) {
				if( in_array( $settlement, $this->pay_method ) ) {
					if( 'deactivate' != $payment['use'] ) {
						$payment['use'] = 'deactivate';
						$deactivate[] = $payment['name'];
						usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
					}
				}
			}
			if( 0 < count( $deactivate ) ) {
				$deactivate_message = sprintf( __( "\"Deactivate\" %s of payment method.", 'usces' ), implode( ',', $deactivate ) );
				$usces->action_message .= $deactivate_message.__( "Please complete the setup and update the payment method to \"Activate\".", 'usces' );
			}
		}
		ksort( $usces->payment_structure );
		update_option( 'usces', $options );
		update_option( 'usces_payment_structure', $usces->payment_structure );
	}

	/**
	 * クレジット決済設定画面タブ
	 * @fook   usces_action_settlement_tab_title
	 * @param  -
	 * @return -
	 * @echo   html
	 */
	public function settlement_tab_title() {

		$settlement_selected = get_option( 'usces_settlement_selected' );
		if( in_array( $this->paymod_id, (array)$settlement_selected ) ) {
			echo '<li><a href="#uscestabs_'.$this->paymod_id.'">'.$this->acting_name.'</a></li>';
		}
	}

	/**
	 * クレジット決済設定画面フォーム
	 * @fook   usces_action_settlement_tab_body
	 * @param  -
	 * @return -
	 * @echo   html
	 */
	public function settlement_tab_body() {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if( in_array( $this->paymod_id, (array)$settlement_selected ) ):
?>
	<div id="uscestabs_anotherlane">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
	<?php if( isset( $_POST['acting'] ) && 'anotherlane' == $_POST['acting'] ): ?>
		<?php if( '' != $this->error_mes ): ?>
		<div class="error_message"><?php wel_esc_script_e( $this->error_mes ); ?></div>
		<?php elseif( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ): ?>
		<div class="message">十分にテストを行ってから運用してください。</div>
		<?php endif; ?>
	<?php endif; ?>
	<form action="" method="post" name="anotherlane_form" id="anotherlane_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_siteid_anotherlane">サイトID</a></th>
				<td><input name="siteid" type="text" id="siteid_anotherlane" value="<?php echo esc_html( isset( $acting_opts['siteid'] ) ? $acting_opts['siteid'] : '' ); ?>" class="regular-text" maxlength="8" /></td>
			</tr>
			<tr id="ex_siteid_anotherlane" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるID（半角数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_sitepass_anotherlane">サイトパスワード</a></th>
				<td><input name="sitepass" type="text" id="sitepass_anotherlane" value="<?php echo esc_html( isset( $acting_opts['sitepass'] ) ? $acting_opts['sitepass'] : '' ); ?>" class="regular-text" maxlength="8" /></td>
			</tr>
			<tr id="ex_sitepass_anotherlane" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるパスワード（半角英数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_anotherlane">稼働環境</a></th>
				<td><label><input name="ope" type="radio" id="ope_anotherlane_1" value="test"<?php if( isset( $acting_opts['ope'] ) && $acting_opts['ope'] == 'test' ) echo ' checked="checked"'; ?> /><span>テスト環境</span></label><br />
					<label><input name="ope" type="radio" id="ope_anotherlane_2" value="public"<?php if( isset( $acting_opts['ope'] ) && $acting_opts['ope'] == 'public' ) echo ' checked="checked"'; ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_ope_anotherlane" class="explanation"><td colspan="2">動作環境を切り替えます。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_quickcharge_anotherlane">クイックチャージ</a></th>
				<td><label><input name="quickcharge" type="radio" id="quickcharge_anotherlane_1" value="on"<?php if( isset( $acting_opts['quickcharge'] ) && $acting_opts['quickcharge'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="quickcharge" type="radio" id="quickcharge_anotherlane_2" value="off"<?php if( isset( $acting_opts['quickcharge'] ) && $acting_opts['quickcharge'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_quickcharge_anotherlane" class="explanation"><td colspan="2">ログインして一度購入したメンバーは、次の購入時にはカード番号を入力する必要がなくなります。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>クレジットカード決済</th>
				<td><label><input name="card_activate" type="radio" id="card_activate_anotherlane_1" value="on"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" id="card_activate_anotherlane_2" value="off"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
		</table>
		<input name="acting" type="hidden" value="anotherlane" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php echo esc_attr( $this->acting_name ); ?>の設定を更新する" />
		<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php echo esc_attr( $this->acting_formal_name ); ?></strong></p>
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank"><?php echo esc_html( $this->acting_name ); ?>の詳細はこちら 》</a>
		<p>　</p>
		<p>この決済は「外部リンク型」の決済システムです。</p>
		<p>「外部リンク型」とは、決済会社のページへ遷移してカード情報を入力する決済システムです。</p>
	</div>
	</div><!--uscestabs_anotherlane-->
<?php
		endif;
	}

	/**
	 * 受注データ登録
	 * Call from usces_reg_orderdata() and usces_new_orderdata().
	 * @fook   usces_action_reg_orderdata
	 * @param  @array $cart, $entry, $order_id, $member_id, $payments, $charging_type, $results
	 * @return -
	 * @echo   -
	 */
	public function register_orderdata( $args ) {
		global $usces;
		extract( $args );

		$acting_flg = $payments['settlement'];
		if( !in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}

		if( !$entry['order']['total_full_price'] ) {
			return;
		}

		if( isset( $_REQUEST['SiteId'] ) && $usces->options['acting_settings']['anotherlane']['siteid'] == $_REQUEST['SiteId'] && isset( $_REQUEST['TransactionId'] ) ) {
			$usces->set_order_meta_value( 'TransactionId', $_REQUEST['TransactionId'], $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $_REQUEST['TransactionId'], $order_id );
		}
	}

	/**
	 * 決済オプション取得
	 * @param  -
	 * @return array $acting_settings
	 */
	protected function get_acting_settings() {
		global $usces;

		$acting_settings = ( isset( $usces->options['acting_settings'][$this->paymod_id] ) ) ? $usces->options['acting_settings'][$this->paymod_id] : array();
		return $acting_settings;
	}
}
