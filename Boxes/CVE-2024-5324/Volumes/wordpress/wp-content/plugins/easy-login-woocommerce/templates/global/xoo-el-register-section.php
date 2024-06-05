<?php
/**
 * Registration Form
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/global/xoo-el-register-section.php
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$redirect 	= xoo_el_helper()->get_general_option( 'm-red-register' );
$redirect 	= !empty( $redirect ) ? $redirect : sanitize_text_field( $_SERVER['REQUEST_URI'] );


//Register Fields
xoo_el_fields()->get_fields_html('register');

?>

<input type="hidden" name="_xoo_el_form" value="register">

<?php do_action( 'xoo_el_register_add_fields', $args ); ?>

<button type="submit" class="button btn xoo-el-action-btn xoo-el-register-btn"><?php esc_html_e( xoo_el_helper()->get_general_option( 'txt-btn-reg' ) ) ?></button>

<input type="hidden" name="xoo_el_redirect" value="<?php echo esc_url( $redirect ); ?>">