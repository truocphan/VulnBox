<?php
/**
 * テレコムクレジット
 *
 * @class    TELECOM_SETTLEMENT
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class TELECOM_SETTLEMENT
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

		$this->paymod_id = 'telecom';
		$this->pay_method = array(
			'acting_telecom_card',
			'acting_telecom_edy',
		);
		$this->acting_name = 'テレコムクレジット';
		$this->acting_formal_name = 'テレコムクレジット';
		$this->acting_company_url = 'http://www.telecomcredit.co.jp/';
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

		if( $this->is_validity_acting( 'card' ) ) {
			if( is_admin() ) {
				add_action( 'usces_action_admin_member_info', array( $this, 'member_settlement_info' ), 10, 3 );
				add_action( 'usces_action_post_update_memberdata', array( $this, 'member_edit_post' ), 10, 2 );
			}
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
		if( !isset( $options['acting_settings'] ) || !isset( $options['acting_settings']['telecom'] ) ) {
			$options['acting_settings']['telecom']['clientip'] = '';
			$options['acting_settings']['telecom']['stype'] = '';
			$options['acting_settings']['telecom']['card_activate'] = 'off';
			$options['acting_settings']['telecom']['oneclick'] = '';
			$options['acting_settings']['telecom']['edy_activate'] = 'off';
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
				if( 'acting_telecom_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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

		case 'edy':
			foreach( $payment_method as $payment ) {
				if( 'acting_telecom_edy' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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
	 * Edy 決済有効判定
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_edy() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['edy_activate'] ) && ( 'on' == $acting_opts['edy_activate'] ) ) ) {
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

		unset( $options['acting_settings']['telecom'] );
		$options['acting_settings']['telecom']['clientip'] = ( isset( $_POST['clientip'] ) ) ? $_POST['clientip'] : '';
		$options['acting_settings']['telecom']['stype'] = ( isset( $_POST['stype'] ) ) ? $_POST['stype'] : '';
		$options['acting_settings']['telecom']['card_activate'] = ( isset( $_POST['card_activate'] ) ) ? $_POST['card_activate'] : 'off';
		$options['acting_settings']['telecom']['oneclick'] = ( isset( $_POST['oneclick'] ) ) ? $_POST['oneclick'] : '';
		//$options['acting_settings']['telecom']['edy_activate'] = ( isset( $_POST['edy_activate'] ) ) ? $_POST['edy_activate'] : 'off';
		$options['acting_settings']['telecom']['edy_activate'] = 'off';

		if( WCUtils::is_blank( $_POST['clientip'] ) ) {
			$this->error_mes .= '※クライアントIPを入力してください<br />';
		}
		if( WCUtils::is_blank( $_POST['stype'] ) ) {
			$this->error_mes .= '※決済タイプを入力してください<br />';
		}

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['telecom']['card_activate'] || 'on' == $options['acting_settings']['telecom']['edy_activate'] ) {
				$options['acting_settings']['telecom']['activate'] = 'on';
				$toactive = array();
				if( 'on' == $options['acting_settings']['telecom']['card_activate'] ) {
					if( 'EP' == $options['acting_settings']['telecom']['stype'] ) {
						$options['acting_settings']['telecom']['send_url'] = "https://settle.saa-s.com/inetcredit/secure/order.pl";
						if( 'on' == $options['acting_settings']['telecom']['oneclick'] ) {
							$options['acting_settings']['telecom']['oneclick_send_url'] = "https://settle.saa-s.com/inetcredit/secure/one-click-order.pl";
						}
					} else {
						if( 'E' == $options['acting_settings']['telecom']['stype'][0] ) {
							$options['acting_settings']['telecom']['send_url'] = "https://www.credit-cgiserver.com/inetcredit/secure/order.pl";
						} else {
							$options['acting_settings']['telecom']['send_url'] = "https://secure.telecomcredit.co.jp/inetcredit/secure/order.pl";
						}
						if( 'on' == $options['acting_settings']['telecom']['oneclick'] ) {
							$options['acting_settings']['telecom']['oneclick_send_url'] = "https://secure.telecomcredit.co.jp/inetcredit/secure/one-click-order.pl";
						}
					}
					$usces->payment_structure['acting_telecom_card'] = 'カード決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_telecom_card' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_telecom_card'] );
				}
				if( 'on' == $options['acting_settings']['telecom']['edy_activate'] ) {
					$options['acting_settings']['telecom']['send_url_edy'] = "https://secure.telecomcredit.co.jp/payment/edy/order.pl";
					$usces->payment_structure['acting_telecom_edy'] = 'Edy決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_telecom_edy' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_telecom_edy'] );
				}
				usces_admin_orderlist_show_wc_trans_id();
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			} else {
				$options['acting_settings']['telecom']['activate'] = 'off';
				unset( $usces->payment_structure['acting_telecom_card'] );
				unset( $usces->payment_structure['acting_telecom_edy'] );
			}
			if( 'on' != $options['acting_settings']['telecom']['oneclick'] || 'off' == $options['acting_settings']['telecom']['activate'] ) {
				usces_clear_quickcharge( 'telecom_oneclick' );
			}
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
		} else {
			$usces->action_status = 'error';
			$usces->action_message = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['telecom']['activate'] = 'off';
			unset( $usces->payment_structure['acting_telecom_card'] );
			unset( $usces->payment_structure['acting_telecom_edy'] );
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
	<div id="uscestabs_telecom">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
	<?php if( isset( $_POST['acting'] ) && 'telecom' == $_POST['acting'] ): ?>
		<?php if( '' != $this->error_mes ): ?>
		<div class="error_message"><?php wel_esc_script_e( $this->error_mes ); ?></div>
		<?php elseif( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ): ?>
		<div class="message">十分にテストを行ってから運用してください。</div>
		<?php endif; ?>
	<?php endif; ?>
	<form action="" method="post" name="telecom_form" id="telecom_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_clientip_telecom">クライアントIP</a></th>
				<td><input name="clientip" type="text" id="clientip_telecom" value="<?php echo esc_html( isset( $acting_opts['clientip'] ) ? $acting_opts['clientip'] : '' ); ?>" class="regular-text" maxlength="5" /></td>
			</tr>
			<tr id="ex_clientip_telecom" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるクライアントIP（半角数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_stype_telecom">決済タイプ</a></th>
				<td><input name="stype" type="text" id="stype_telecom" value="<?php echo esc_html( isset( $acting_opts['stype'] ) ? $acting_opts['stype'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_stype_telecom" class="explanation"><td colspan="2">設定依頼書に記載されている決済タイプ</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>クレジットカード決済</th>
				<td><label><input name="card_activate" type="radio" id="card_activate_telecom_1" value="on"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" id="card_activate_telecom_2" value="off"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_oneclick_telecom">スピード決済</a></th>
				<td><label><input name="oneclick" type="radio" id="oneclick_telecom_1" value="on"<?php if( isset( $acting_opts['oneclick'] ) && $acting_opts['oneclick'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="oneclick" type="radio" id="oneclick_telecom_2" value="off"<?php if( isset( $acting_opts['oneclick'] ) && $acting_opts['oneclick'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_oneclick_telecom" class="explanation"><td colspan="2">2回目以降の利用はカード番号を入力しなくても決済可能となります。</td></tr>
		</table>
		<!--<table class="settle_table">
			<tr>
				<th>Edy決済</th>
				<td><label><input name="edy_activate" type="radio" id="edy_activate_telecom_1" value="on"<?php if( isset( $acting_opts['edy_activate'] ) && $acting_opts['edy_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="edy_activate" type="radio" id="edy_activate_telecom_2" value="off"<?php if( isset( $acting_opts['edy_activate'] ) && $acting_opts['edy_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
		</table>-->
		<input name="acting" type="hidden" value="telecom" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php echo esc_html( $this->acting_name ); ?>の設定を更新する" />
		<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php echo esc_html( $this->acting_formal_name ); ?></strong></p>
		<a href="<?php echo esc_url( $this->acting_company_url ); ?>" target="_blank"><?php echo esc_html( $this->acting_name ); ?>の詳細はこちら 》</a>
		<p>　</p>
		<p>この決済は「外部リンク型」の決済システムです。</p>
		<p>「外部リンク型」とは、決済会社のページへ遷移してカード情報を入力する決済システムです。</p>
	</div>
	</div><!--uscestabs_telecom-->
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

		if( isset( $_REQUEST['acting'] ) && 'telecom_card' == $_REQUEST['acting'] && isset( $_REQUEST['acting_return'] ) && isset( $_REQUEST['option'] ) ) {
			$data['option'] = esc_sql( $_REQUEST['option'] );
			$usces->set_order_meta_value( 'acting_telecom_card', serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $_REQUEST['option'], $order_id );
			$usces->set_order_meta_value( 'trans_id', $_REQUEST['option'], $order_id );
			$acting_opts = $usces->options['acting_settings']['telecom'];
			if( 'on' == $acting_opts['oneclick'] ) {
				$usces->set_member_meta_value( 'telecom_oneclick', $member_id );
			}
		}
	}

	/**
	 * 会員データ編集画面 スピード決済登録情報
	 * @fook   usces_action_admin_member_info
	 * @param  $member_data $member_meta_data $member_history
	 * @return -
	 * @echo   -
	 */
	public function member_settlement_info( $member_data, $member_meta_data, $member_history ) {

		if( 0 < count( $member_meta_data ) ):
			$cardinfo = array();
			foreach( $member_meta_data as $value ) {
				if( in_array( $value['meta_key'], array( 'telecom_oneclick' ) ) ) {
					$cardinfo[$value['meta_key']] = $value['meta_value'];
				}
			}
			if( 0 < count( $cardinfo ) ):
				if( array_key_exists( 'telecom_oneclick', $cardinfo ) ): ?>
			<tr>
				<td class="label">スピード決済</td>
				<td><div class="rod_left shortm">登録あり</div></td>
			</tr>
			<tr>
				<td class="label"><input type="checkbox" name="telecom_oneclick" id="telecom_oneclick" value="delete"></td>
				<td><label for="telecom_oneclick">スピード決済を解除する</label></td>
			</tr>
<?php			endif;
			endif;
		endif;
	}

	/**
	 * 会員データ編集画面 スピード決済登録解除
	 * @fook   usces_action_post_update_memberdata
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function member_edit_post( $member_id, $res ) {
		global $usces;

		if( isset( $_POST['telecom_oneclick'] ) && $_POST['telecom_oneclick'] == 'delete' ) {
			$usces->del_member_meta( 'telecom_oneclick', $member_id );
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
