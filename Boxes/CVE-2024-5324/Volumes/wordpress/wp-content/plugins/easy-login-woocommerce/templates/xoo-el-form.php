<?php
/**
 * The template is a form container
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/xoo-el-form.php.
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

$form_active = $args['form_active'];

?>

<div class="xoo-el-form-container xoo-el-form-<?php echo esc_attr( $args['display'] ); ?>" data-active="<?php echo esc_attr( $form_active ); ?>">

	<?php if( $form_active === 'resetpw' && isset( $args['forms']['resetpw']['user'] ) && !is_wp_error( $args['forms']['resetpw']['user'] ) ): ?>
		<span class="xoo-el-resetpw-tgr xoo-el-resetpw-hnotice"><?php _e( 'Continue to resetting password', 'easy-login-woocommerce' ); ?></span>
	<?php endif; ?>

	<?php do_action( 'xoo_el_before_header', $args ); ?>

	<?php xoo_el_helper()->get_template( 'global/xoo-el-header.php', array( 'args' => $args ) ); ?>

	<?php do_action( 'xoo_el_after_header', $args ); ?>

	<?php foreach ( $args['forms'] as $form => $form_args ): ?>

		<?php if( $form_args['enable'] !== 'yes' ) continue; ?>
	
		<div data-section="<?php echo esc_attr( $form ) ?>" class="xoo-el-section">

			<div class="xoo-el-fields">

				<?php do_action( 'xoo_el_before_form', $form, $args ); ?>

				<form class="xoo-el-action-form xoo-el-form-<?php echo esc_attr( $form ); ?>">

					<?php do_action( 'xoo_el_form_start', $form, $args ); ?>

					<?php xoo_el_helper()->get_template( 'global/xoo-el-'.$form.'-section.php', array( 'args' => $args ) ); ?>

					<?php do_action( 'xoo_el_form_end', $form, $args ); ?>

				</form>

				<?php do_action( 'xoo_el_after_form', $form, $args ); ?>

			</div>

		</div>

	<?php endforeach; ?>

	<?php do_action( 'xoo_el_container_end', $args ); ?>

</div>