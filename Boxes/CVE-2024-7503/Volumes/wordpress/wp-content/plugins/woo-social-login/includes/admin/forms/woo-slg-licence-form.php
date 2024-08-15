<?php // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Page Licence From
 * 
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Assign Some Variable
$email_address     = isset( $_POST['woo_slg_email_address'] ) ? $_POST['woo_slg_email_address'] : get_option( 'woo_slg_email_address' );
$activation_code   = isset( $_POST['woo_slg_activation_code'] ) ? $_POST['woo_slg_activation_code'] : get_option( 'woo_slg_activation_code' );
$activation_status = get_option( 'woo_slg_activated' );

// Check activation status
if( $activation_status ) {
	$code_length = strlen( $activation_code ) / 2;
	$activation_code = substr( $activation_code, 0, $code_length ) . str_repeat( '*', $code_length );
}

if ( isset( $data['status'] ) && true == $data['status'] ) {
	$class = 'notice';
} else {
	$class = 'error';
} 
?>
<!-- beginning of the settings meta box -->
<div class="wrap">
<?php woo_slg_header_menu(); ?>

<!-- beginning of the licence settings meta box -->
<div class="wpweb-header">	
	<div class="wpweb-logo"><img src="<?php echo esc_url(WOO_SLG_IMG_URL) . '/wpweb-logo.svg'; ?>" class="woo-slg-logo" alt="WPWebElite"></div>
	<div class="woo-slg-title-heading"><?php echo esc_html__('License', 'wooslg'); ?></div>
</div>
<div class="sub-header">
<div class="woo-slg-top-error-wrap"><h2></h2></div>
</div>
<div class="wpweb-activation_section">
	<div id="loader">
		  <img src="<?php echo WOO_SLG_URL . 'includes/images/loader.gif'; ?>" alt="Loading..." />
	</div>
	<div class="wpweb-section-wrap">
		<div class="wpweb-section-header">
			<div class="wpweb-header-text">
				<h2 id="license" class="wpweb-title wpweb-icon-important <?php if( ! empty( $activation_status ) ) { echo 'active'; } ?>">
					<?php _e( 'License', 'wooslg' ); ?>					
				</h2>				
			</div>
			<div class="wpweb-license-container">
				<form method="post" action="">
					<input type="hidden" name="action" value="your_plugin_save_settings">
					<?php wp_nonce_field( 'your_plugin_save_settings' ); ?>
					<div class="wpweb-fields-container">
						<div class="wpweb-license-container-fieldset">
							<div class="wpweb-field">
								<div class="wp-wb-txt">
									<label for="license_key"><?php _e( 'License Key*', 'wooslg' ); ?></label>
									<input type="text" id="license_key" placeholder="Enter plugin license key"  name="woo_slg_activation_code" class="woo_slg_activation_code" value="<?php if ( $activation_status ) { echo esc_attr( $activation_code ); } ?>" <?php if ( $activation_status ) { echo 'readonly'; } ?>/>
								</div>
							</div>

							<div class="wpweb-field">
								<div class="wp-wb-txt">
									<label for="email_address"><?php _e( 'Email Address*', 'wooslg' ); ?></label>
									<input type="email" id="email_address" placeholder="Enter email address" name="woo_slg_email_address" class="woo_slg_email_address" value="<?php if ( $activation_status ) { echo esc_attr( $email_address ); } ?> " <?php if ( $activation_status ) { echo 'readonly'; } ?>/>
								</div>	
							</div>
						</div>
						<?php if ( $activation_status ) { ?>
							<div class="wpweb-license-manage-container">
								<?php
								printf(
									__( 'Congratulations, and thank you for registering your website. To manage your licenses, sign up on %s.', 'wooslg' ), // phpcs:ignore WordPress.Security.EscapeOutput
									'<a href="https://updater.wpwebelite.com/login/" target="_blank">' . esc_html__( 'WPWeb License Manager', 'wooslg' ) . '</a>'
								);
								?>
							</div>
						<?php } ?>
					</div>
					<?php if ( $activation_status ) {
						$btntxt = 'Deactivate License';
					} else {
						$btntxt = 'Activate License';
					}
					submit_button( $btntxt, 'primary', 'woo_slg_button' ); ?>
				</form>
				<div class="wpweb-db-reg-howto">
					<h3 class="wpweb-db-reg-howto-heading"><?php esc_html_e( 'How To Find Your Purchase Code', 'wooslg' ); ?></h3>
					<ol class="wpweb-db-reg-howto-list wpweb-db-card-text-small">
						<li>
							<?php
							printf(
								/* translators: "CodeCanyon sign in link" link. */
								__( 'Sign in to your %s. <strong>IMPORTANT:</strong> You must be signed into the same CodeCanyon account that purchased WooCommerce Social Login. If you are signed in already, look in the top menu bar to ensure it is the right account.', 'wooslg' ), // phpcs:ignore WordPress.Security.EscapeOutput
								'<a href="https://codecanyon.net/sign_in" target="_blank">' . esc_html__( 'CodeCanyon account', 'wooslg' ) . '</a>'
							);
							?>
						</li>
						<li>
							<?php
							printf(
								/* translators: "Generate A Personal Token" link. */
								__( 'Visit the %s. You should see a row for WooCommerce Social Login.  If you don\'t, please re-check step 1 that you are on the correct account.', 'wooslg' ), // phpcs:ignore WordPress.Security.EscapeOutput
								'<a href="https://codecanyon.net/downloads" target="_blank">' . esc_html__( 'CodeCanyon downloads page', 'wooslg' ) . '</a>'
							);
							?>
						</li>
						<li>
							<?php
								esc_html_e( 'Click the download button in the WooCommerce Social Login row.', 'wooslg' )
							?>
						</li>
						<li>
							<?php
								esc_html_e( 'Select either License certificate & purchase code (PDF) or License certificate & purchase code (text). This should then download either a text or PDF file.', 'wooslg' )
							?>
						</li>
						<li>
							<?php
								esc_html_e( 'Open up that newly downloaded file and copy the Item Purchase Code.', 'wooslg' )
							?>
						</li>
					</ol>
				</div>
			</div>
		</div>		
	</div>
	
</div>
</div>
