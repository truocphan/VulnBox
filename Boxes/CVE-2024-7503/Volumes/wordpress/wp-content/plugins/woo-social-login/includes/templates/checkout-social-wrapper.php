<?php
/**
 * Social Wrapper Template
 * 
 * Handles to load social wrapper template for checkout page
 * 
 * Override this template by copying it to yourtheme/woo-social-login/checkout-social-wrapper.php
 * 
 * Note: When you overwrite template, please dont remove class "woo-slg-social-container", else social buttons wont work.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Define global variable
global $woo_slg_options;

// Get Default Expand option from setting page
$default_expand = isset( $woo_slg_options['woo_slg_enable_expand_collapse'] ) ? $woo_slg_options['woo_slg_enable_expand_collapse'] : '';

$expand_collapse_class	= '';
$expand_collapse_enable = false;
if( !empty( $default_expand ) ) {

	$expand_collapse_class	= $default_expand == "collapse" ? ' woo-slg-hide' : '';
	$expand_collapse_enable = true;
}

if( $expand_collapse_enable ) {
?>
	<p class="woocommerce-info">
		<?php esc_html_e($login_heading, 'wooslg'); ?> 
		<a href="javascript:void(0);" class="woo-slg-show-social-login">
			<?php esc_html_e( 'Click here to login', 'wooslg' ); ?>
		</a>
	</p>
<?php
	$expand_collapse_class	.= ' woo-slg-social-container-checkout';
} ?>

<fieldset class="woo-slg-social-container<?php echo $expand_collapse_class;?>">
<?php 

	//do action to add login with email section
	do_action( 'woo_slg_wrapper_login_with_email');

	if( !empty($login_heading) && $expand_collapse_enable == false ) {
		echo '<span><legend>'. esc_html($login_heading).'</legend></span>';
	}

	//do action to add social login buttons
	do_action( 'woo_slg_checkout_wrapper_social_login' );

	//do action to add login with email section
	do_action( 'woo_slg_wrapper_login_with_email_bottom');
?>
</fieldset>