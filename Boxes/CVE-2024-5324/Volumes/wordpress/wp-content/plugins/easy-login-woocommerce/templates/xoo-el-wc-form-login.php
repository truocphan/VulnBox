<?php
/**
 * Woocommerce login form is replaced by this template
 *
 * This template can be overridden by copying it to yourtheme/templates/xoo-el-wc-form-login.php.
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$shortcode = html_entity_decode( xoo_el_helper()->get_general_option('m-myacc-sc') );

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php echo do_shortcode( apply_filters( 'xoo_el_myaccount_shortcode', $shortcode ) ); ?>
		
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>