<?php
/**
 * Password reset form.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/account/form-reset-password.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Account
 * @version 1.5.12
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering password reset form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_password_reset_form' );

?>

<section class="masteriyo-reset">
		<div class="masteriyo-reset--wrapper masteriyo-form-container">
			<h3 class="masteriyo-reset--title"><?php echo esc_html__( 'New password', 'masteriyo' ); ?></h3>
			<p class="masteriyo-reset--msg">
				<?php
				/**
				 * Filters password reset form message.
				 *
				 * @since 1.0.0
				 *
				 * @param string $message The password reset form message.
				 */
				echo esc_html( apply_filters( 'masteriyo_reset_password_message', __( 'Enter a new password below.', 'masteriyo' ) ) );
				?>
			</p>

			<form id="masteriyo-reset--form" class="masteriyo-reset--form" method="post">
				<input type="hidden" name="remember" value="true">
					<div class="masteriyo-username">
						<label for="password" class="masteriyo-label"><?php echo esc_html__( 'New password', 'masteriyo' ); ?><span class="masteriyo-text-red">*</span></label>
						<input id="password" class="masteriyo-input" name="password" type="password" required autocomplete="new-password" >
					</div>
					<div class="masteriyo-password">
						<label for="confirm-password" class="masteriyo-label"><?php echo esc_html__( 'Re-enter new password.', 'masteriyo' ); ?><span class="masteriyo-text-red">*</span></label>
						<input id="confirm-password" class="masteriyo-input" name="confirm-password" type="password" required autocomplete="new-password" >
					</div>

				<div class="masteriyo-btn-wrapper">
					<button type="submit" name="masteriyo-password-reset" value="yes" class="masteriyo-reset-btn masteriyo-btn masteriyo-btn-primary">
						<?php echo esc_html__( 'Reset', 'masteriyo' ); ?>
					</button>

					<div class="masteriyo-reset-signin">
						<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'account' ) ); ?>" class="masteriyo-link-primary">
							<?php echo esc_html__( 'Go back to sign-in', 'masteriyo' ); ?>
						</a>
					</div>
				</div>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $key ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $login ); ?>" />

				<?php
				/**
				 * Fires after rendering password reset form's input fields.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_password_reset_form' );
				?>

				<?php wp_nonce_field( 'masteriyo-password-reset' ); ?>
			</form>

			<?php masteriyo_display_all_notices(); ?>
		</div>
</section>

<?php

/**
 * Fires after rendering password reset form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_password_reset_form' );
