<?php
/**
 * Yahoo Button Template
 * 
 * Handles to load yahoo button template
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
		
		<a title="<?php esc_html_e( 'Connect with Yahoo', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-yahoo woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-yh-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with Yahoo', 'wooslg' ); ?>
		</a>
	
	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Connect with Yahoo', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-yahoo">
			<img src="<?php echo esc_url($yhimgurl);?>" alt="<?php esc_html_e( 'Yahoo', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>