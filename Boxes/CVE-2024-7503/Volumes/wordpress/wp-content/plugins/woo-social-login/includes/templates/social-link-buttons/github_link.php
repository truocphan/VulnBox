<?php
/**
 * GitHub Button Template
 * 
 * Handles to load github button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-link-buttons/github_link.php
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
		
		<a title="<?php esc_html_e( 'Link your account with GitHub', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-github woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-github-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Link your account with GitHub', 'wooslg' ); ?>
		</a>

	<?php } else { ?>
	
		<a title="<?php esc_html_e( 'Link your account with GitHub', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-github">
			<img src="<?php echo esc_url($githubimglinkurl);?>" alt="<?php esc_html_e( 'Link your account with GitHub', 'wooslg');?>" />
		</a>
		
	<?php } ?>
</div>