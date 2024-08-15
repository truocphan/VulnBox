<?php
/**
 * vk Button Template
 * 
 * Handles to load vk button template
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
		
		<a title="<?php esc_html_e( 'Connect with VK.com', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-vk woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-vk-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with VK.com', 'wooslg' ); ?>
		</a>
	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Connect with VK.com', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-vk">
			<img src="<?php echo esc_url($vkimgurl);?>" alt="<?php esc_html_e( 'VK', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>