<?php
/**
 * みずほファクター
 *
 * @class    MIZUHO_SETTLEMENT
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class MIZUHO_SETTLEMENT
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

		$this->paymod_id = 'mizuho';
		//$this->pay_method = array(
		//	'acting_mizuho_card',
		//	'acting_mizuho_conv1',
		//	'acting_mizuho_conv2',
		//);
		$this->pay_method = array(
			'acting_mizuho_card',
		);
		$this->acting_name = 'みずほファクター';
		$this->acting_formal_name = 'みずほファクター';
		$this->acting_company_url = 'http://www.mizuho-factor.co.jp/';

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
		if( !isset( $options['acting_settings'] ) || !isset( $options['acting_settings']['mizuho'] ) ) {
			$options['acting_settings']['mizuho']['shopid'] = '';
			$options['acting_settings']['mizuho']['cshopid'] = '';
			$options['acting_settings']['mizuho']['hash_pass'] = '';
			$options['acting_settings']['mizuho']['ope'] = '';
			$options['acting_settings']['mizuho']['send_url'] = '';
			$options['acting_settings']['mizuho']['send_url_mbl'] = '';
			$options['acting_settings']['mizuho']['card_activate'] = 'off';
			$options['acting_settings']['mizuho']['conv1_activate'] = 'off';
			$options['acting_settings']['mizuho']['conv2_activate'] = 'off';
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
				if( 'acting_mizuho_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
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

		case 'conv':
			foreach( $payment_method as $payment ) {
				if( 'acting_mizuho_conv1' == $payment['settlement'] && 'activate' == $payment['use'] ) {
					$method = true;
					break;
				} elseif( 'acting_mizuho_conv2' == $payment['settlement'] && 'activate' == $payment['use'] ) {
					$method = true;
					break;
				}
			}
			if( $method && $this->is_activate_conv1() ) {
				return true;
			} elseif( $method && $this->is_activate_conv2() ) {
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
	 * コンビニ・ウェルネット決済有効判定
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_conv1() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['conv1_activate'] ) && 'on' == $acting_opts['conv1_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * コンビニ・セブンイレブン決済有効判定
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_conv2() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['conv2_activate'] ) && 'on' == $acting_opts['conv2_activate'] ) ) {
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

		unset( $options['acting_settings']['mizuho'] );
		$options['acting_settings']['mizuho']['shopid'] = ( isset( $_POST['shopid'] ) ) ? trim( $_POST['shopid'] ) : '';
		$options['acting_settings']['mizuho']['cshopid'] = ( isset( $_POST['cshopid'] ) ) ? trim( $_POST['cshopid'] ) : '';
		$options['acting_settings']['mizuho']['hash_pass'] = ( isset( $_POST['hash_pass'] ) ) ? trim( $_POST['hash_pass'] ) : '';
		$options['acting_settings']['mizuho']['ope'] = ( isset( $_POST['ope'] ) ) ? $_POST['ope'] : '';
		$options['acting_settings']['mizuho']['send_url'] = ( isset( $_POST['send_url'] ) ) ? trim( $_POST['send_url'] ) : '';
		$options['acting_settings']['mizuho']['send_url_mbl'] = ( isset( $_POST['send_url_mbl'] ) ) ? trim( $_POST['send_url_mbl'] ) : '';
		$options['acting_settings']['mizuho']['card_activate'] = ( isset( $_POST['card_activate'] ) ) ? $_POST['card_activate'] : 'off';
		$options['acting_settings']['mizuho']['conv1_activate'] = ( isset( $_POST['conv1_activate'] ) ) ? $_POST['conv1_activate'] : 'off';
		$options['acting_settings']['mizuho']['conv2_activate'] = ( isset( $_POST['conv2_activate'] ) ) ? $_POST['conv2_activate'] : 'off';

		if( WCUtils::is_blank( $_POST['shopid'] ) ) {
			$this->error_mes .= '※加盟店コードを入力してください<br />';
		}
		if( WCUtils::is_blank( $_POST['cshopid'] ) ) {
			$this->error_mes .= '※加盟店サブコードを入力してください<br />';
		}
		if( WCUtils::is_blank( $_POST['hash_pass'] ) ) {
			$this->error_mes .= '※ハッシュ用パスワードを入力してください<br />';
		}
		if( isset( $_POST['ope'] ) && 'public' == $_POST['ope'] && WCUtils::is_blank( $_POST['send_url'] ) ) {
			$this->error_mes .= '※本番URLを入力してください<br />';
		}
		if( defined( 'WCEX_MOBILE' ) && isset( $_POST['ope'] ) && 'public' == $_POST['ope'] && WCUtils::is_blank( $_POST['send_url_mbl'] ) ) {
			$this->error_mes .= '※本番URL(携帯)を入力してください<br />';
		}

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['mizuho']['card_activate'] || 'on' == $options['acting_settings']['mizuho']['conv1_activate'] || 'on' == $options['acting_settings']['mizuho']['conv2_activate'] ) {
				$options['acting_settings']['mizuho']['activate'] = 'on';
				$options['acting_settings']['mizuho']['send_url_test'] = "https://tst.kessai-navi.jp/mltbank/MBWebFrontPayment";
				if( defined( 'WCEX_MOBILE' ) ) {
					$options['acting_settings']['mizuho']['send_url_mbl_test'] = "https://tst.kessai-navi.jp/mltbank/iMBWebFrontPayment";
				}
				$toactive = array();
				if( 'on' == $options['acting_settings']['mizuho']['card_activate'] ) {
					$usces->payment_structure['acting_mizuho_card'] = 'カード決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_mizuho_card' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_mizuho_card'] );
				}
				if( 'on' == $options['acting_settings']['mizuho']['conv1_activate'] ) {
					$usces->payment_structure['acting_mizuho_conv1'] = 'コンビニ・ウェルネット決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_mizuho_conv1' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_mizuho_conv1'] );
				}
				if( 'on' == $options['acting_settings']['mizuho']['conv2_activate'] ) {
					$usces->payment_structure['acting_mizuho_conv2'] = 'コンビニ・セブンイレブン決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_mizuho_conv2' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_mizuho_conv2'] );
				}
				usces_admin_orderlist_show_wc_trans_id();
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			} else {
				$options['acting_settings']['mizuho']['activate'] = 'off';
				unset( $usces->payment_structure['acting_mizuho_card'] );
				unset( $usces->payment_structure['acting_mizuho_conv1'] );
				unset( $usces->payment_structure['acting_mizuho_conv2'] );
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
			$options['acting_settings']['mizuho']['activate'] = 'off';
			unset( $usces->payment_structure['acting_mizuho_card'] );
			unset( $usces->payment_structure['acting_mizuho_conv1'] );
			unset( $usces->payment_structure['acting_mizuho_conv2'] );
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
	<div id="uscestabs_mizuho">
	<div class="settlement_service"><span class="service_title"><?php echo esc_html( $this->acting_formal_name ); ?></span></div>
	<?php if( isset( $_POST['acting'] ) && 'mizuho' == $_POST['acting'] ): ?>
		<?php if( '' != $this->error_mes ): ?>
		<div class="error_message"><?php wel_esc_script_e( $this->error_mes ); ?></div>
		<?php elseif( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ): ?>
		<div class="message">十分にテストを行ってから運用してください。</div>
		<?php endif; ?>
	<?php endif; ?>
	<form action="" method="post" name="mizuho_form" id="mizuho_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_shopid_mizuho">加盟店コード</a></th>
				<td><input name="shopid" type="text" id="shopid" value="<?php echo esc_html( isset( $acting_opts['shopid'] ) ? $acting_opts['shopid'] : '' ); ?>" class="regular-text" maxlength="6" /></td>
			</tr>
			<tr id="ex_shopid_mizuho" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行される加盟店コード（半角数字6桁）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_cshopid_mizuho">加盟店サブコード</a></th>
				<td><input name="cshopid" type="text" id="cshopid" value="<?php echo esc_html( isset( $acting_opts['cshopid'] ) ? $acting_opts['cshopid'] : '' ); ?>" class="regular-text" maxlength="5" /></td>
			</tr>
			<tr id="ex_cshopid_mizuho" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行される加盟店サブコード（半角数字5桁）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_hash_pass_mizuho">ハッシュ用パスワード</a></th>
				<td><input name="hash_pass" type="text" id="hash_pass" value="<?php echo esc_html( isset( $acting_opts['hash_pass'] ) ? $acting_opts['hash_pass'] : '' ); ?>" class="regular-text" maxlength="20" /></td>
			</tr>
			<tr id="ex_hash_pass_mizuho" class="explanation"><td colspan="2">契約時に<?php echo esc_html( $this->acting_name ); ?>から発行されるハッシュ用パスワード（半角英数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_mizuho"><?php _e( 'Operation Environment', 'usces' ); ?></a></th>
				<td><label><input name="ope" type="radio" id="ope_mizuho_1" value="test"<?php if( isset( $acting_opts['ope'] ) && $acting_opts['ope'] == 'test' ) echo ' checked="checked"'; ?> /><span>テスト環境</span></label><br />
					<label><input name="ope" type="radio" id="ope_mizuho_2" value="public"<?php if( isset( $acting_opts['ope'] ) && $acting_opts['ope'] == 'public' ) echo ' checked="checked"'; ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_ope_mizuho" class="explanation"><td colspan="2">動作環境を切り替えます。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_send_url_mizuho">本番URL</a></th>
				<td><input name="send_url" type="text" id="send_url_mizuho" value="<?php echo esc_html( isset( $acting_opts['send_url'] ) ? $acting_opts['send_url'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_send_url_mizuho" class="explanation"><td colspan="2">本番環境で接続するURLを設定します。決済インタフェース「接続先URL(画面連携型)PC」に示されるURLを入力してください。</td></tr>
			<?php if( defined( 'WCEX_MOBILE' ) ): ?>
			<tr>
				<th><a class="explanation-label" id="label_ex_send_url_mbl_mizuho">本番URL(携帯)</a></th>
				<td><input name="send_url_mbl" type="text" id="send_url_mbl_mizuho" value="<?php echo esc_html( isset( $acting_opts['send_url_mbl'] ) ? $acting_opts['send_url_mbl'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_send_url_mbl_mizuho" class="explanation"><td colspan="2">本番環境で接続するURLを設定します。決済インタフェース「接続先URL(画面連携型)MB」に示されるURLを入力してください。</td></tr>
			<?php endif; ?>
		</table>
		<table class="settle_table">
			<tr>
				<th>クレジットカード決済</th>
				<td><label><input name="card_activate" type="radio" id="card_activate_mizuho_1" value="on"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" id="card_activate_mizuho_2" value="off"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
		</table>
		<!--<table class="settle_table">
			<tr>
				<th>コンビニ決済<br><a class="explanation-label" id="label_ex_conv1_activate">ウェルネット決済</a></th>
				<td><label><input name="conv1_activate" type="radio" id="conv1_activate_mizuho_1" value="on"<?php if( isset( $acting_opts['conv1_activate'] ) && $acting_opts['conv1_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="conv1_activate" type="radio" id="conv1_activate_mizuho_2" value="off"<?php if( isset( $acting_opts['conv1_activate'] ) && $acting_opts['conv1_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_conv1_activate" class="explanation"><td colspan="2">ローソン、ファミリーマート、サークルK サンクス、ミニストップ、デイリーヤマザキ、スリーエフでのご利用が可能です。</td></tr>
			<tr>
				<th>コンビニ決済<br><a class="explanation-label" id="label_ex_conv2_activate">セブンイレブン決済</a></th>
				<td><label><input name="conv2_activate" type="radio" id="conv2_activate_mizuho_1" value="on"<?php if( isset( $acting_opts['conv2_activate'] ) && $acting_opts['conv2_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="conv2_activate" type="radio" id="conv2_activate_mizuho_2" value="off"<?php if( isset( $acting_opts['conv2_activate'] ) && $acting_opts['conv2_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr id="ex_conv2_activate" class="explanation"><td colspan="2">セブンイレブンでのご利用が可能です。</td></tr>
		</table>-->
		<input name="acting" type="hidden" value="mizuho" />
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
	</div><!--uscestabs_mizuho-->
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

		if( isset( $_REQUEST['acting'] ) && 'mizuho_card' == $_REQUEST['acting'] ) {
			$data['stran'] = esc_sql( $_REQUEST['stran'] );
			$data['mbtran'] = esc_sql( $_REQUEST['mbtran'] );
			$usces->set_order_meta_value( 'acting_'.$_REQUEST['acting'], serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $_REQUEST['stran'], $order_id );

		} elseif( isset( $_REQUEST['acting'] ) && 'mizuho_conv' == $_REQUEST['acting'] ) {
			$data['stran'] = esc_sql( $_REQUEST['stran'] );
			$data['mbtran'] = esc_sql( $_REQUEST['mbtran'] );
			$data['bktrans'] = esc_sql( $_REQUEST['bktrans'] );
			$data['tranid'] = esc_sql( $_REQUEST['tranid'] );
			$usces->set_order_meta_value( 'stran', $data['stran'], $order_id );
			$usces->set_order_meta_value( 'acting_'.$_REQUEST['acting'], serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $data['stran'], $order_id );
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
