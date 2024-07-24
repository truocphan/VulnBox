<?php
/**
 * Googleplus Link  Button Template
 * 
 * Handles to load Googleplus link button template
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
		
		<a title="<?php esc_html_e( 'Link your account with Google', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-gp-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Link your account with Google', 'wooslg' ); ?>
			<div id="woo-slg-gplus" class="woo-slg-social-googleplus">
			</div>
		</a>

	<?php } else { ?>

		<a title="<?php esc_html_e( 'Link your account with Google', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus">
			<img src="<?php echo esc_url($gpimglinkurl);?>" alt="<?php esc_html_e( 'Link your account with Google', 'wooslg');?>" />
			<div id="woo-slg-gplus" class="woo-slg-social-googleplus">
			</div>
		</a>

	<?php } ?>
</div>