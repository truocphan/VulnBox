<?php
/**
 * Linkedin link Button Template
 * 
 * Handles to load linkedin link button template
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Link your account with LinkedIn', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-linkedin woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-li-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Link your account with LinkedIn', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Link your account with LinkedIn', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-linkedin">
			<img src="<?php echo esc_url($lilinkimgurl);?>" alt="<?php esc_html_e( 'Link your account with LinkedIn', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>