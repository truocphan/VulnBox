<?php
/**
 * Facebook Link Button Template
 * 
 * Handles to load facebook link button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-link-buttons/facebook_link.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show facebook button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Link your account with Facebook', 'wooslg');?>" data-action="connect" data-plugin="woo-slg" data-popupwidth="600" data-popupheight="800" rel="nofollow" href="<?php echo esc_url($facebookClass->woo_slg_get_login_url()); ?>" class="woo-slg-social-login-facebook woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-fb-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Link your account with Facebook', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Link your account with Facebook', 'wooslg');?>" data-action="connect" data-plugin="woo-slg" data-popupwidth="600" data-popupheight="800" rel="nofollow" href="<?php echo esc_url($facebookClass->woo_slg_get_login_url()); ?>" class="woo-slg-social-login-facebook">
			<img src="<?php echo esc_url($fblinkimgurl);?>" alt="<?php esc_html_e( 'Link your account to Facebook', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>