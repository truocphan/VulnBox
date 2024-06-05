<?php
/**
 * Contains Header HTML for switching tabs.
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/global/xoo-el-header.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 2.6
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//return if single form pattern
if( $args['navstyle'] !== 'tabs' ) return;

?>

<div class="xoo-el-header">
	<ul class="xoo-el-tabs">
		
        <?php if( in_array( 'login', $args['tabs'] ) && xoo_el_helper()->get_general_option( 'txt-tab-login' ) ): ?>
		  <li data-tab="login" class="xoo-el-login-tgr" style="order: <?php echo array_search('login', $args['tabs'] ) ?> "><?php esc_html_e( xoo_el_helper()->get_general_option( 'txt-tab-login' ) ) ?></li>
        <?php endif; ?>

		<?php if( in_array( 'register', $args['tabs'] ) && xoo_el_helper()->get_general_option( 'txt-tab-reg' ) ):?> 
			<li data-tab="register" class="xoo-el-reg-tgr" style="order: <?php echo array_search('register', $args['tabs'] ) ?>"><?php esc_html_e( xoo_el_helper()->get_general_option( 'txt-tab-reg' ) ) ?></li>
		<?php endif; ?>

	</ul>
</div>