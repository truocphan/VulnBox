<?php
/**
 * Wordpress.com Button Template
 * 
 * Handles to load wordpresscom button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons/wordpresscom.php
 * 
 * @package WooCommerce - Social Login
 * @since 2.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Connect with Wordpress.com', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-wordpresscom woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-wordpresscom-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with Wordpress.com', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Connect with Wordpress.com', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-wordpresscom">
			<img src="<?php echo esc_url($wordpresscomimgurl);?>" alt="<?php esc_html_e( 'Wordpress.com', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>
