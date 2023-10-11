<?php
/**
 * New product image register extensions.
 *
 * This file use control show or hide new product image box.
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @since    2.6.0
 */
class NEW_PRODUCT_IMAGE_REGISTER {

	/**
	 * Extended Options.
	 *
	 * @var object
	 */
	public static $opts;

	/**
	 * Construct.
	 */
	public function __construct() {

		self::initialize_data();

		if ( is_admin() ) {
			add_action( 'usces_action_admin_system_extentions', array( $this, 'setting_form' ) );
			add_action( 'init', array( $this, 'save_data' ) );
		}

		if ( self::$opts['switch_flag'] ) {
			if ( is_admin() ) {
				require_once USCES_PLUGIN_DIR . 'includes/product/wel-item-images.php';
			}
		}
	}

	/**
	 * Handle init data.
	 */
	public function initialize_data() {
		$options = get_option( 'usces_ex' );
		$options['system']['newproductimage']['switch_flag'] = ! isset( $options['system']['newproductimage']['switch_flag'] ) ? 1 : (int) $options['system']['newproductimage']['switch_flag'];
		update_option( 'usces_ex', $options );
		self::$opts = $options['system']['newproductimage'];
	}

	/**
	 * Handle save data.
	 */
	public function save_data() {
		global $usces;
		if ( isset( $_POST['usces_newproductimage_option_update'] ) ) {

			check_admin_referer( 'admin_system', 'wc_nonce' );

			if ( isset( $_POST['newproductimage_switch_flag'] ) ) {
				self::$opts['switch_flag'] = (int) $_POST['newproductimage_switch_flag'];
			}

			$options                              = get_option( 'usces_ex' );
			$options['system']['newproductimage'] = self::$opts;
			update_option( 'usces_ex', $options );
		}
	}

	/**
	 * Handel build setting from.
	 */
	public function setting_form() {
		$check_disable = ( 0 === self::$opts['switch_flag'] ) ? 'checked="checked"' : '';
		$check_enable  = ( 1 === self::$opts['switch_flag'] ) ? 'checked="checked"' : '';
		?>
		<form action="" method="post" name="option_form" id="newproductimage_form">
			<div class="postbox">
				<div class="postbox-header">
					<h2>
						<span><?php esc_attr_e( 'New product image registration', 'usces' ); ?></span>
						<?php if ( self::$opts['switch_flag'] ) { ?>
							<span class="running"><?php esc_attr_e( 'Running', 'usces' ); ?></span>
						<?php } else { ?>
							<span class="stopped"><?php esc_attr_e( 'Stopped', 'usces' ); ?></span>
						<?php } ?>
					</h2>
					<div class="handle-actions">
						<button type="button" class="handlediv" id="newproductimage">
							<span class="screen-reader-text">
								<?php
								// translators: %s: Toggle panel name.
								sprintf( esc_html__( 'Toggle panel: %s' ), esc_html__( 'New product image registration', 'usces' ) );
								?>
							</span>
							<span class="toggle-indicator"></span>
						</button>
					</div>
				</div>
				<div class="inside">
					<table class="form_table">
						<tr height="35">
							<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_newproductimage_switch_flag');"><?php esc_attr_e( 'New product image registration', 'usces' ); ?></a></th>
							<td width="10">
								<input name="newproductimage_switch_flag" id="newproductimage_switch_flag0" type="radio" value="0" <?php echo esc_attr( $check_disable ); ?> />
							</td>
							<td width="100"><label for="newproductimage_switch_flag0"><?php esc_attr_e( 'disable', 'usces' ); ?></label></td>
							<td width="10">
								<input name="newproductimage_switch_flag" id="newproductimage_switch_flag1" type="radio" value="1" <?php echo esc_attr( $check_enable ); ?> />
							</td><td width="100"><label for="newproductimage_switch_flag1"><?php esc_attr_e( 'enable', 'usces' ); ?></label></td>
							<td><div id="ex_newproductimage_switch_flag" class="explanation"><?php esc_attr_e( 'Control enable or disable the new item image box.', 'usces' ); ?><?php esc_attr_e( 'Select "Disable" if you wish to operate with the conventional rule of linking file names and product codes.', 'usces' ); ?></div></td>
						</tr>
					</table>
					<hr />
					<input name="usces_newproductimage_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
				</div>
			</div><!--postbox-->
			<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
		</form>
		<?php
	}
}
