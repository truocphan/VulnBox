<?php
/**
 * When a new member joins, confirm the authenticity of
 * the email address and the identity of the member.
 *
 * The following built-in templates have been added.
 * templates/cart/verifying.php
 * templates/cart/verified.php
 * templates/member/verifying.php
 */

/**
 * Verify Members Email class
 */
class USCES_VERIFY_MEMBERS_EMAIL {
	/**
	 * Verifyemail options
	 *
	 * @var array
	 */
	public static $opts;

	/**
	 * Initialization vector key
	 *
	 * @var string
	 */
	public static $verify_key;

	/**
	 * Construct
	 */
	public function __construct() {

		self::initialize_data();
		self::$verify_key = 'zMsHTOIF6xRrmaMB';

		if ( is_admin() ) {
			add_action( 'usces_action_admin_system_extentions', array( $this, 'setting_form' ) );
			add_action( 'init', array( $this, 'save_data' ) );
		}

		if ( self::$opts['switch_flag'] ) {

			add_action( 'wc_cron', array( $this, 'delete_flag' ) );

			if ( ! is_admin() ) {
				add_filter( 'usces_filter_veirfyemail_newmemberform', array( $this, 'member_registered' ), 10, 2 );
				add_filter( 'usces_filter_veirfyemail_newmemberfromcart', array( $this, 'member_registered' ), 10, 2 );
				add_action( 'usces_main', array( $this, 'verified_email' ) );
			}
		}
	}

	/**
	 * Initialize
	 * Modified:30 Sep.2019
	 */
	public function initialize_data() {
		global $usces;

		$options = get_option( 'usces_ex', array() );
		$options['system']['verifyemail']['switch_flag'] = ! isset( $options['system']['verifyemail']['switch_flag'] ) ? 0 : (int) $options['system']['verifyemail']['switch_flag'];
		update_option( 'usces_ex', $options );
		self::$opts = $options['system']['verifyemail'];
	}

	/**
	 * Save option data
	 * Modified:30 Sep.2019
	 */
	public function save_data() {
		global $usces;

		if ( isset( $_POST['usces_verifyemail_option_update'] ) ) {

			check_admin_referer( 'admin_system', 'wc_nonce' );

			if ( isset( $_POST['verifyemail_switch_flag'] ) ) {
				self::$opts['switch_flag'] = (int) $_POST['verifyemail_switch_flag'];
			}

			$options                          = get_option( 'usces_ex', array() );
			$options['system']['verifyemail'] = self::$opts;
			update_option( 'usces_ex', $options );
		}
	}

	/**
	 * Setting form
	 * Modified:30 Sep.2019
	 */
	public function setting_form() {
		$status = ( self::$opts['switch_flag'] || self::$opts['switch_flag'] ) ? '<span class="running">' . __( 'Running', 'usces' ) . '</span>' : '<span class="stopped">' . __( 'Stopped', 'usces' ) . '</span>';
		?>
	<form action="" method="post" name="option_form" id="verifyemail_form">
	<div class="postbox">
		<div class="postbox-header">
			<h2><span><?php esc_html_e( 'Verify New Member Email', 'usces' ); ?></span><?php echo wp_kses_post( $status ); ?></h2>
			<div class="handle-actions"><button type="button" class="handlediv" id="verifyemail"><span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Toggle panel: %s' ), __( 'Verify New Member Email', 'usces' ) ) ); ?></span><span class="toggle-indicator"></span></button></div>
		</div>
		<div class="inside">
		<table class="form_table">
			<tr height="35">
				<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_verifyemail_switch_flag');"><?php esc_html_e( 'Verifying e-mail', 'usces' ); ?></a></th>
				<td width="10"><input name="verifyemail_switch_flag" id="verifyemail_switch_flag0" type="radio" value="0"<?php checked( self::$opts['switch_flag'], 0 ); ?> /></td><td width="100"><label for="verifyemail_switch_flag0"><?php esc_html_e( 'disable', 'usces' ); ?></label></td>
				<td width="10"><input name="verifyemail_switch_flag" id="verifyemail_switch_flag1" type="radio" value="1"<?php checked( self::$opts['switch_flag'], 1 ); ?> /></td><td width="100"><label for="verifyemail_switch_flag1"><?php esc_html_e( 'enable', 'usces' ); ?></label></td>
				<td><div id="ex_verifyemail_switch_flag" class="explanation"><?php esc_html_e( 'Send an e-mail to the e-mail address registered when new member registration, and make them approve that the e-mail address is definitely their own.', 'usces' ); ?></div></td>
			</tr>
		</table>
		<hr />
		<input name="usces_verifyemail_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
		</div>
	</div><!--postbox-->
		<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
	</form>
		<?php
	}

	/**
	 * Member Registration
	 * usces_filter_veirfyemail_newmemberform
	 * usces_filter_veirfyemail_newmemberfromcart
	 * Modified:30 Sep.2019
	 *
	 * @param bool  $ob Switch.
	 * @param array $member Member data.
	 * @return bool
	 */
	public function member_registered( $ob, $member ) {
		global $usces;

		$time = current_time( 'timestamp' );
		$usces->set_member_meta_value( '_verifying', $time, $member['ID'] );
		$member_regmode = wp_unslash( $_POST['member_regmode'] );

		if ( 'newmemberform' === $member_regmode ) {
			add_filter( 'usces_filter_template_redirect', array( $this, 'start_verifying' ) );
		} elseif ( 'newmemberfromcart' === $member_regmode ) {
			add_filter( 'usces_filter_template_redirect', array( $this, 'start_verifying_cart' ) );
			$member['flat_pass'] = wp_unslash( $_POST['customer']['password1'] );
		}

		remove_filter( 'usces_filter_template_redirect', 'dlseller_filter_template_redirect', 2 );

		$member['regmode'] = $member_regmode;
		$this->send_verifymail( $member );
		unset( $_SESSION['usces_member'] );

		return true;
	}

	/**
	 * Start verifying
	 * Modified:30 Sep.2019
	 */
	public function start_verifying() {
		global $usces;

		if ( file_exists( get_theme_file_path( '/wc_templates/member/wc_member_verifying.php' ) ) ) {
			include get_theme_file_path( '/wc_templates/member/wc_member_verifying.php' );
			exit;
		}

		return true;
	}

	/**
	 * Start verifying
	 * Modified:30 Sep.2019
	 */
	public function start_verifying_cart() {
		global $usces;

		if ( file_exists( get_theme_file_path( '/wc_templates/cart/wc_cart_verifying.php' ) ) ) {
			include get_theme_file_path( '/wc_templates/cart/wc_cart_verifying.php' );
			exit;
		}

		return true;
	}

	/**
	 * Send verifymail
	 * Modified:30 Sep.2019
	 *
	 * @param array $user User data.
	 * @return bool
	 */
	public function send_verifymail( $user ) {
		global $usces;

		$remaining_hour    = $this->get_remaining_hour();
		$res               = false;
		$newmem_admin_mail = $usces->options['newmem_admin_mail'];
		$name              = sprintf( _x( '%s', 'honorific', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
		$mailaddress1      = trim( $user['mailaddress1'] );

		$subject  = apply_filters( 'usces_filter_send_verifymembermail_subject', __( 'Request email confirmation', 'usces' ), $user );
		$message  = sprintf( __( 'Thank you for registering to %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n\r\n";
		$message .= __( 'By accessing the following URL, you can complete membership registration.', 'usces' ) . "\r\n";
		$message .= __( 'Please note that the procedure has not been completed until you receive registration complete e-mail.', 'usces' ) . "\r\n\r\n";
		$message .= sprintf( __( 'The following URL is valid for %d hours. If it expires, please try again from the beginning.', 'usces' ), $remaining_hour ) . "\r\n\r\n";
		$message .= $this->get_verify_url( $user ) . "\r\n\r\n";

		$message .= '--------------------------------' . "\r\n";
		$message .= __( 'Please delete this email if you were not aware that you were going to receive it.', 'usces' ) . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message .= get_option( 'blogname' ) . "\r\n";
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
			$message   = $dear_name . "\r\n\r\n" . $message;
		}
		$message = apply_filters( 'usces_filter_send_verifymembermail_message', $message, $user );

		$para1 = array(
			'to_name'      => $name,
			'to_address'   => $mailaddress1,
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		$para1 = apply_filters( 'usces_filter_send_verifymembermail_para1', $para1 );
		$res   = usces_send_mail( $para1 );
		return $res;
	}

	/**
	 * Get verify url
	 * Modified:30 Sep.2019
	 *
	 * @param array $user User data.
	 * @return string
	 */
	public function get_verify_url( $user ) {
		global $usces;

		$encrypt_value = $this->encrypt_value( $user );
		$query         = array(
			'verify'     => $encrypt_value,
			'usces_page' => 'memberverified',
			'uscesid'    => $usces->get_uscesid( false ),
		);
		$query         = apply_filters( 'usces_filter_verifymail_query', $query, $user );
		$url           = add_query_arg( $query, USCES_MEMBER_URL );

		return $url;
	}

	/**
	 * Encrypt value
	 * Modified:30 Sep.2019
	 *
	 * @param array $user User data.
	 * @return array
	 */
	public function encrypt_value( $user ) {
		$datatext      = $user['regmode'] . ',' . $user['ID'];
		$algo          = 'AES-128-CBC';
		$key           = get_option( 'usces_wcid' );
		$iv            = self::$verify_key;
		$encrypt_value = openssl_encrypt( $datatext, $algo, $key, OPENSSL_RAW_DATA, $iv );
		$encrypt_value = urlencode( base64_encode( $encrypt_value ) );

		return $encrypt_value;
	}

	/**
	 * Decrypt value
	 * Modified:30 Sep.2019
	 *
	 * @param string $value Value.
	 * @return string
	 */
	public function decrypt_value( $value ) {
		$algo = 'AES-128-CBC';
		$key  = get_option( 'usces_wcid' );
		$iv   = self::$verify_key;
		$data = base64_decode( $value );
		$data = openssl_decrypt( $data, $algo, $key, OPENSSL_RAW_DATA, $iv );

		return $data;
	}

	/**
	 * Verified email
	 * usces_main
	 * Modified:30 Sep.2019
	 */
	public function verified_email() {
		global $usces;

		if ( ! $usces->is_cart_or_member_page( $_SERVER['REQUEST_URI'] ) || ! isset( $_GET['verify'] ) ) {
			return;
		}

		usces_register_action( 'page_allow_email', 'get', 'usces_page', 'memberverified', array( $this, 'allow_email' ) );
		add_filter( 'usces_filter_template_redirect', array( $this, 'complete_verifying' ) );
		remove_filter( 'usces_filter_template_redirect', 'dlseller_filter_template_redirect', 2 );
	}

	/**
	 * Allow email
	 * Modified:30 Sep.2019
	 */
	public function allow_email() {
		global $usces;

		if ( ! $usces->is_cart_or_member_page( $_SERVER['REQUEST_URI'] ) || ! isset( $_GET['verify'] ) ) {
			return;
		}

		$value    = wp_unslash( $_GET['verify'] );
		$datatext = $this->decrypt_value( $value );
		$data     = explode( ',', $datatext );
		$regmode  = $data[0];
		$mem_id   = (int) $data[1];
		$member   = $usces->get_member_info( $mem_id );
		$flag     = $usces->get_member_meta_value( '_verifying', $mem_id );
		if ( empty( $member ) || empty( $flag ) ) {
			wp_redirect( USCES_MEMBER_URL );
			exit;
		}

		$user = array();
		foreach ( $member as $key => $value ) {
			if ( 'mem_email' === $key ) {
				$user['mailaddress1'] = $value;
			} elseif ( 'mem_name1' === $key ) {
				$user['name1'] = $value;
			} elseif ( 'mem_name2' === $key ) {
				$user['name2'] = $value;
			} elseif ( 'mem_name3' === $key ) {
				$user['name3'] = $value;
			} elseif ( 'mem_name4' === $key ) {
				$user['name4'] = $value;
			} elseif ( 'mem_zip' === $key ) {
				$user['zipcode'] = $value;
			} elseif ( 'customer_country' === $key ) {
				$user['country'] = $value;
			} elseif ( 'mem_pref' === $key ) {
				$user['pref'] = $value;
			} elseif ( 'mem_address1' === $key ) {
				$user['address1'] = $value;
			} elseif ( 'mem_address2' === $key ) {
				$user['address2'] = $value;
			} elseif ( 'mem_address3' === $key ) {
				$user['address3'] = $value;
			} elseif ( 'mem_tel' === $key ) {
				$user['tel'] = $value;
			} elseif ( 'mem_fax' === $key ) {
				$user['fax'] = $value;
			} else {
				$user[ $key ] = $value;
			}
		}

		$usces->del_member_meta( '_verifying', $mem_id );

		do_action( 'usces_action_member_registered', $user, $mem_id );
		usces_send_regmembermail( $user );

		if ( 'newmemberform' === $regmode ) {

			unset( $_SESSION['usces_member'] );
			$usces->page = 'newcompletion';
			add_action( 'the_post', array( $usces, 'action_memberFilter' ) );
			add_action( 'template_redirect', array( $usces, 'template_redirect' ) );

			do_action( 'usces_action_after_newmemberform_verified', $user, $data );

		} elseif ( 'newmemberfromcart' === $regmode ) {
			unset( $_SESSION['usces_entry']['customer'] );
			$usces->page = 'cartverified';
			add_action( 'the_post', array( $usces, 'action_cartFilter' ) );
			add_action( 'template_redirect', array( $usces, 'template_redirect' ) );

			do_action( 'usces_action_after_newmemberfromcart_verified', $user, $data );
		}
	}

	/**
	 * Complete verifying
	 * Modified:30 Sep.2019
	 */
	public function complete_verifying() {
		global $usces;

		if ( file_exists( get_theme_file_path( '/wc_templates/cart/wc_cart_verified.php' ) ) ) {
			include get_theme_file_path( '/wc_templates/cart/wc_cart_verified.php' );
			exit;
		}

		return true;
	}

	/**
	 * Get remaining hour
	 * Modified:30 Sep.2019
	 */
	public static function get_remaining_hour() {
		$crons          = _get_cron_array();
		$remaining_time = 0;
		foreach ( $crons as $time => $cron ) {
			foreach ( $cron as $key => $value ) {
				if ( 'wc_cron' === $key ) {
					$remaining_time = $time - current_time( 'timestamp', 1 );
				}
			}
		}
		$remaining_hour = floor( $remaining_time / 3600 );
		if ( 1 > $remaining_hour ) {
			$remaining_hour = 1;
		}

		return $remaining_hour;
	}

	/**
	 * 'wc_cron' event
	 * Modified:30 Sep.2019
	 */
	public function delete_flag() {
		global $wpdb;

		$table   = usces_get_tablename( 'usces_member_meta' );
		$mem_ids = $wpdb->get_col( $wpdb->prepare( "SELECT `member_id` FROM {$table} WHERE `meta_key` = %s", '_verifying' ) );

		foreach ( (array) $mem_ids as $mem_id ) {
			usces_delete_memberdata( $mem_id );
		}
	}
}
