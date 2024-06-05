<?php
/**
 * Lost Password Form
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/global/xoo-el-lostpw-section.php
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


?>


<span class="xoo-el-form-txt"><?php _e('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.','easy-login-woocommerce'); ?></span>

<?php  xoo_el_fields()->get_fields_html('lostpw'); //Lost Password Fields ?>

<?php do_action( 'xoo_el_lostpw_add_fields', $args ); ?>

<input type="hidden" name="_xoo_el_form" value="lostPassword">

<?php wp_referer_field(); ?>

<button type="submit" class="button btn xoo-el-action-btn xoo-el-lostpw-btn"><?php esc_html_e( xoo_el_helper()->get_general_option( 'txt-btn-respw' ) ) ?></button>