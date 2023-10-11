<?php
/**
 * CreditCard Security Measures class
 *
 * @package Welcart
 */
class USCES_CREDITCARD_SECURITY {

	/**
	 * Option value.
	 *
	 * @var array
	 */
	public static $option;

	/**
	 * Default option value.
	 *
	 * @var array
	 */
	public static $default_option;

	/**
	 * Construct.
	 */
	public function __construct() {

		self::initialize_data();

		if ( is_admin() ) {
			add_action( 'usces_action_admin_system_extentions', array( $this, 'setting_form' ) );
			add_action( 'init', array( $this, 'save_data' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
		}
	}

	/**
	 * Initialize.
	 */
	public function initialize_data() {

		self::$default_option = array(
			'time_frame'   => array( 15, 30, 60 ),
			'set_count'    => array( 5, 10, 15, 20 ),
			'lockout_time' => array( 1, 6, 12, 24 ),
		);

		$ex_option                                        = get_option( 'usces_ex', array() );
		$ex_option['system']['credit_security']['active'] = ( isset( $ex_option['system']['credit_security']['active'] ) ) ? (int) $ex_option['system']['credit_security']['active'] : 1;
		$ex_option['system']['credit_security']['time_frame']   = ( isset( $ex_option['system']['credit_security']['time_frame'] ) ) ? (int) $ex_option['system']['credit_security']['time_frame'] : self::$default_option['time_frame'][0];
		$ex_option['system']['credit_security']['set_count']    = ( isset( $ex_option['system']['credit_security']['set_count'] ) ) ? (int) $ex_option['system']['credit_security']['set_count'] : self::$default_option['set_count'][1];
		$ex_option['system']['credit_security']['lockout_time'] = ( isset( $ex_option['system']['credit_security']['lockout_time'] ) ) ? (int) $ex_option['system']['credit_security']['lockout_time'] : self::$default_option['lockout_time'][0];
		update_option( 'usces_ex', $ex_option );
		self::$option = $ex_option['system']['credit_security'];
	}

	/**
	 * Save option value.
	 */
	public function save_data() {
		global $usces;

		if ( isset( $_POST['usces_credit_security_option_update'] ) ) {
			check_admin_referer( 'admin_system', 'wc_nonce' );

			self::$option['active']       = ( isset( $_POST['credit_security_active'] ) ) ? (int) $_POST['credit_security_active'] : 1;
			self::$option['time_frame']   = ( isset( $_POST['credit_security_time_frame'] ) ) ? (int) $_POST['credit_security_time_frame'] : self::$default_option['time_frame'][0];
			self::$option['set_count']    = ( isset( $_POST['credit_security_set_count'] ) ) ? (int) $_POST['credit_security_set_count'] : self::$default_option['set_count'][1];
			self::$option['lockout_time'] = ( isset( $_POST['credit_security_lockout_time'] ) ) ? (int) $_POST['credit_security_lockout_time'] : self::$default_option['lockout_time'][0];

			$ex_option                              = get_option( 'usces_ex', array() );
			$ex_option['system']['credit_security'] = self::$option;
			update_option( 'usces_ex', $ex_option );
		}
	}

	/**
	 * Setting form.
	 */
	public function setting_form() {
		$active = ( 1 === self::$option['active'] ) ? '<span class="running">' . __( 'Running', 'usces' ) . '</span>' : '<span class="stopped">' . __( 'Stopped', 'usces' ) . '</span>';
		?>
		<form action="" method="post" name="option_form" id="credit_security_form">
			<div class="postbox">
				<div class="postbox-header">
					<h2><span><?php esc_html_e( 'Credit Card Security Measures', 'usces' ); ?></span><?php wel_esc_script_e( $active ); ?></h2>
					<div class="handle-actions"><button type="button" class="handlediv" id="credit_security"><span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Toggle panel: %s' ), __( 'Security measures', 'usces' ) ) ); ?></span><span class="toggle-indicator"></span></button></div>
				</div>
				<div class="inside">
					<table class="form_table">
						<tr>
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_credit_security_active');"><?php esc_html_e( 'Credit Master Measure', 'usces' ); ?></a></th>
							<td width="10"><input name="credit_security_active" type="radio" id="credit_security_active_0" value="0"<?php checked( self::$option['active'], 0 ); ?> /></td>
							<td width="100"><label for="credit_security_active_0"><?php esc_html_e( 'disable', 'usces' ); ?></label></td>
							<td width="10"><input name="credit_security_active" type="radio" id="credit_security_active_1" value="1"<?php checked( self::$option['active'], 1 ); ?> /></td>
							<td width="100"><label for="credit_security_active_1"><?php esc_html_e( 'enable', 'usces' ); ?></label></td><td></td>
							<td><div id="ex_credit_security_active" class="explanation"><?php esc_html_e( 'If the card information input fails (Update Count) times in (Counting time) minutes, the update lock will be applied for (Lock time) hours and the card information input will be disabled.', 'usces' ); ?></div></td>
						</tr>
					</table>
					<table class="form_table" id="credit_master_form">
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_credit_security_time_frame');"><?php esc_html_e( 'Counting time', 'usces' ); ?></a></th>
							<td><select name="credit_security_time_frame">
						<?php foreach ( self::$default_option['time_frame'] as $value ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $value, self::$option['time_frame'] ); ?>><?php echo esc_html( $value ); ?></option>
						<?php endforeach; ?>
							</select><label for="credit_security_time_frame"><?php esc_html_e( 'min', 'usces' ); ?></label></td>
							<td><div id="ex_credit_security_time_frame" class="explanation"><?php esc_html_e( 'Time to count updates (minutes)', 'usces' ); ?></div></td>
						</tr>
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_credit_security_set_count');"><?php esc_html_e( 'Update Count', 'usces' ); ?></a></th>
							<td><select name="credit_security_set_count">
						<?php foreach ( self::$default_option['set_count'] as $value ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $value, self::$option['set_count'] ); ?>><?php echo esc_html( $value ); ?></option>
						<?php endforeach; ?>
							</select><label for="credit_security_set_count"><?php esc_html_e( 'counts', 'usces' ); ?></label></td>
							<td><div id="ex_credit_security_set_count" class="explanation"><?php esc_html_e( 'How many times the card information update is repeated to be locked.', 'usces' ); ?></div></td>
						</tr>
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_credit_security_lockout_time');"><?php esc_html_e( 'Lock time', 'usces' ); ?></a></th>
							<td><select name="credit_security_lockout_time">
						<?php foreach ( self::$default_option['lockout_time'] as $value ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $value, self::$option['lockout_time'] ); ?>><?php echo esc_html( $value ); ?></option>
						<?php endforeach; ?>
							</select><label for="credit_security_lockout_time"><?php esc_html_e( 'hours', 'usces' ); ?></label></td>
							<td><div id="ex_credit_security_lockout_time" class="explanation"><?php esc_html_e( 'Time to update lock (hours)', 'usces' ); ?></div></td>
						</tr>
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_credit_security_unlock');"><?php esc_html_e( 'Unlock', 'usces' ); ?></a></th>
							<td><input type="button" id="credit_security_unlock" value="<?php esc_html_e( 'Cancellation', 'usces' ); ?>" class="button"><span id="unlock-loading"></span></td>
							<td><div id="ex_credit_security_unlock" class="explanation"><?php esc_html_e( 'Unlock all locks.', 'usces' ); ?><?php esc_html_e( 'If you wish to unlock a specific member, please unlock it from the Edit Member page.', 'usces' ); ?></div></td>
						</tr>
					</table>
					<hr/>
					<input name="usces_credit_security_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>"/>
				</div>
			</div><!--postbox-->
			<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
		</form>
		<?php
	}

	/**
	 * Admin scripts.
	 * admin_print_footer_scripts
	 */
	public function admin_scripts() {
		$page = filter_input( INPUT_GET, 'page' );
		if ( 'usces_system' === $page ) :
			?>
<script type="text/javascript">
jQuery(document).ready( function($) {
	if( '1' == $("input[name='credit_security_active']:checked").val() ) {
		$('#credit_master_form').css('display','');
	} else {
		$('#credit_master_form').css('display','none');
	}
	$(document).on( 'change', "input[name='credit_security_active']", function() {
		if( '1' == $("input[name='credit_security_active']:checked").val() ) {
			$('#credit_master_form').css('display','');
		} else {
			$('#credit_master_form').css('display','none');
		}
	});
	$(document).on( 'click', '#credit_security_unlock', function() {
		if( ! confirm("<?php esc_html_e( 'Unlock all locks?', 'usces' ); ?>") ) {
			return false;
		}
		$('#unlock-loading').html('<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />');
		$.ajax({
			url: ajaxurl,
			type: "POST",
			cache: false,
			dataType: 'json',
			data: {
				action: 'credit_security_unlock',
				wc_nonce : '<?php echo wp_create_nonce( 'credit_security_unlock' ); ?>',
			}
		}).done( function( retVal, dataType ) {
			$('#unlock-loading').html('');
			alert("<?php esc_html_e( 'Unlocked Completed.', 'usces' ); ?>");
		}).fail( function( jqXHR, textStatus, errorThrown ) {
			console.log( textStatus );
			console.log( jqXHR.status );
			console.log( errorThrown.message );
			$('#unlock-loading').html('');
		});
		return false;
	});
});
</script>
			<?php
		endif;
	}
}
