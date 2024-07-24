<?php
/**
 * Line Link Button Template
 * 
 * Handles to load Line link button template
 * 
 * @package WooCommerce - Social Login
 * @since 1.9.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Link your account with Line', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-line woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-line-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Link your account with Line', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Link your account with Windows Live', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-line">
			<img src="<?php echo esc_url($lineimgurl);?>" alt="<?php esc_html_e( 'Link your account with Line', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>