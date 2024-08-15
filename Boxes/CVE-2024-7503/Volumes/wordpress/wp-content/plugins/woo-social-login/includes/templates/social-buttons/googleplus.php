<?php
/**
 * Googleplus Button Template
 * 
 * Handles to load Googleplus button template
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
		
		<a class="woo-slg-social-login-googleplus woo-slg-social-btn" title="<?php esc_html_e( 'Connect with Google', 'wooslg'); ?>" >
			<i class="woo-slg-icon woo-slg-gp-icon"></i>
			<?php echo !empty( $button_text ) ? esc_html( $button_text ) : esc_html__( 'Continue with Google', 'wooslg' ); ?>
			<div id="woo-slg-gplus" class="woo-slg-social-googleplus">
			</div>
		</a>
		
	<?php } else { ?>
		
		<a title="<?php esc_html_e( 'Connect with Google', 'wooslg' ); ?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus ">
			<img src="<?php echo esc_url( $gpimgurl ); ?>" alt="<?php esc_html_e( 'Google', 'wooslg' ); ?>" />	
			<div id="woo-slg-gplus" class="woo-slg-social-googleplus">
			</div>
		</a>
		
	<?php } ?>
</div>