<?php
/**
 * Facebook Button Template
 * 
 * Handles to load facebook button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons/facebook.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Connect with Apple', 'wooslg');?>" href="<?php echo esc_url($appleClass->woo_slg_get_apple_login_url()); ?>" class="woo-slg-social-login-apple woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-apple-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Link your account to Apple', 'wooslg' ); ?>
		</a>
		<?php update_option( 'apple_link_btn_status', 'true' ); ?>
	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Connect with Apple', 'wooslg');?>" href="<?php echo esc_url($appleClass->woo_slg_get_apple_login_url()); ?>" class="woo-slg-social-login-apple">
			<img src="<?php echo esc_url($applelinkimgurl);?>" alt="<?php esc_html_e( 'Apple', 'wooslg');?>" />
		</a>
		<?php update_option( 'apple_link_btn_status', 'true' ); ?>
	<?php } ?>
</div>
