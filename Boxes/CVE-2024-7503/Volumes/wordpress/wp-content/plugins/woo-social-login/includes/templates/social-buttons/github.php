<?php
/**
 * github Button Template
 * 
 * Handles to load github button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons/github.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Connect with GitHub', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-github woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-github-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with GitHub', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Connect with GitHub', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-github">
			<img src="<?php echo esc_url($githubimgurl);?>" alt="<?php esc_html_e( 'GitHub', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>
