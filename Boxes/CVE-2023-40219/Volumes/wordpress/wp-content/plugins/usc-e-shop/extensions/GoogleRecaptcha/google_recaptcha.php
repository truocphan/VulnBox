<?php

class USCES_GOOGLE_RECAPTCHA
{

	public static $opts;

	public function __construct()
	{

		self::initialize_data();

		if (is_admin()) {
			add_action('usces_action_admin_system_extentions', array($this, 'setting_form'));
			add_action('init', array($this, 'save_data'));
		}
	}

	/**********************************************
	 * Initialize
	 ***********************************************/
	public function initialize_data()
	{
		global $usces;
		$options = get_option('usces_ex');
		$options['system']['google_recaptcha']['status'] = !isset($options['system']['google_recaptcha']['status']) ? 0 : (int)$options['system']['google_recaptcha']['status'];
		$options['system']['google_recaptcha']['site_key'] = !isset($options['system']['google_recaptcha']['site_key']) ? '' : $options['system']['google_recaptcha']['site_key'];
		$options['system']['google_recaptcha']['secret_key'] = !isset($options['system']['google_recaptcha']['secret_key']) ? '' : $options['system']['google_recaptcha']['secret_key'];
		update_option('usces_ex', $options);
		self::$opts = $options['system']['google_recaptcha'];
	}

	/**********************************************
	 * save option data
	 ***********************************************/
	public function save_data() {
		global $usces;

		if( isset( $_POST['usces_google_recaptcha_option_update'] ) ) {
			check_admin_referer( 'admin_system', 'wc_nonce' );

			self::$opts['status'] = ( isset( $_POST['google_recaptcha_status'] ) ) ? (int)$_POST['google_recaptcha_status'] : 0;
			self::$opts['site_key'] = ( isset( $_POST['site_key'] ) ) ? $_POST['site_key'] : '';
			self::$opts['secret_key'] = ( isset( $_POST['secret_key'] ) ) ? $_POST['secret_key'] : '';

			$options = get_option( 'usces_ex' );
			$options['system']['google_recaptcha'] = self::$opts;
			update_option( 'usces_ex', $options );
		}
	}

	/**********************************************
	 * setting_form
	 ***********************************************/
	public function setting_form() {
		$status = ( self::$opts['status'] || self::$opts['status'] ) ? '<span class="running">' . __( 'Running', 'usces' ) . '</span>' : '<span class="stopped">' . __( 'Stopped', 'usces' ) . '</span>';
		?>
		<form action="" method="post" name="option_form" id="google_recaptcha_form">
			<div class="postbox">
				<div class="postbox-header">
					<h2><span><?php _e( 'Google reCAPTCHA v3','usces' ); ?></span><?php wel_esc_script_e( $status ); ?></h2>
					<div class="handle-actions"><button type="button" class="handlediv" id="google_recaptcha"><span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Toggle panel: %s' ), __( 'Google reCAPTCHA v3', 'usces' ) ) ); ?></span><span class="toggle-indicator"></span></button></div>
				</div>
				<div class="inside">
					<table class="form_table">

						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_google_recaptcha_status');"><?php _e( 'Google reCAPTCHA v3', 'usces' ); ?></a></th>
							<td width="10"><input name="google_recaptcha_status" id="google_recaptcha_status0" type="radio" value="0"<?php if( self::$opts['status'] === 0 ) echo 'checked="checked"'; ?> /></td><td width="100"><label for="google_recaptcha_status0"><?php _e( 'disable', 'usces' ); ?></label></td>
							<td width="10"><input name="google_recaptcha_status" id="google_recaptcha_status1" type="radio" value="1"<?php if( self::$opts['status'] === 1 ) echo 'checked="checked"'; ?> /></td><td width="100"><label for="google_recaptcha_status1"><?php _e( 'enable', 'usces' ); ?></label></td>
							<td><div id="ex_google_recaptcha_status" class="explanation"><?php _e( "Enable Google reCAPTCHA v3.", 'usces'); ?><br><?php echo sprintf( __( "You can register your new site <a href=\"%s\" target=\"_blank\">here</a>.", 'usces'), 'https://www.google.com/recaptcha/admin/create' ); ?><?php _e( "Select \"reCAPTCHA v3\" as the reCAPTCHA type.", 'usces'); ?></div></td>
						</tr>

					</table>
					<table class="form_table">
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_google_recaptcha_site_key');"><?php _e( 'Site Key', 'usces' ); ?></a></th>
							<td width="10"><input name="site_key" id="site_key" type="text" value="<?php echo self::$opts['site_key'];?>" /></td>
							<td><div id="ex_google_recaptcha_site_key" class="explanation"><?php _e( "The site key to use when displaying the reCAPTCHA.", 'usces'); ?></div></td>
							<td><div id="error_google_recaptcha_site_key" class="explanation"><?php _e( "Please enter the site key.", 'usces'); ?></div></td>

						</tr>
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_google_recaptcha_secret_key');"><?php _e( 'Secret Key', 'usces' ); ?></a></th>
							<td width="10"><input name="secret_key" id="secret_key" type="text" value="<?php echo self::$opts['secret_key'];?>" /></td>
							<td><div id="ex_google_recaptcha_secret_key" class="explanation"><?php _e( "The secret key used for validation. It must not be public.", 'usces'); ?></div></td>
							<td><div id="error_google_recaptcha_secret_key" class="explanation"><?php _e( "Please enter the secret key.", 'usces'); ?></div></td>
						</tr>
					</table>
					<hr />
					<input name="usces_google_recaptcha_option_update" type="submit" class="button button-primary" value="<?php _e( 'change decision', 'usces' ); ?>" />
				</div>
			</div><!--postbox-->
			<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
		</form>
		<?php
	}
}
