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
		
		<a title="<?php esc_html_e( 'Connect with Facebook', 'wooslg');?>" data-action="connect" data-plugin="woo-slg" data-popupwidth="600" data-popupheight="800" rel="nofollow" href="<?php echo esc_url($facebookClass->woo_slg_get_login_url()); ?>" class="woo-slg-social-login-facebook woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-fb-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with Facebook', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Connect with Facebook', 'wooslg');?>" data-action="connect" data-plugin="woo-slg" data-popupwidth="600" data-popupheight="800" rel="nofollow" href="<?php echo esc_url($facebookClass->woo_slg_get_login_url()); ?>" class="woo-slg-social-login-facebook">
			<img src="<?php echo esc_url($fbimgurl);?>" alt="<?php esc_html_e( 'Facebook', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>