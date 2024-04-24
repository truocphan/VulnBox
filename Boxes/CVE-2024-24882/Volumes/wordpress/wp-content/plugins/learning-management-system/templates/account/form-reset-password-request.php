<?php

/**
 * Password reset request form.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/account/form-reset-password-request.php.
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
 * Fires before rendering password reset request form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_password_reset_request_form' );

?>

<section class="masteriyo-reset">
	<div class="masteriyo-reset--wrapper masteriyo-form-container">
		<h3 class="masteriyo-reset--title">
			<?php echo esc_html__( 'Reset Password', 'masteriyo' ); ?>
		</h3>
		<p class="masteriyo-reset--msg">
			<?php echo esc_html__( "Enter your user account's verified email address and we will send you a password reset link.", 'masteriyo' ); ?>
		</p>

		<form id="masteriyo-reset--form" class="masteriyo-reset--form" method="post">
			<div class="masteriyo-username">
				<label for="reset-username-email-address" class="masteriyo-label">
					<?php echo esc_html__( 'Username or Email', 'masteriyo' ); ?>
				</label>
				<input id="reset-username-email-address"
					class="masteriyo-input" name="user_login" type="text"
					required placeholder="" />
			</div>

			<div class="masteriyo-btn-wrapper">
				<button type="submit" name="masteriyo-password-reset-request"
					value="yes" class="masteriyo-reset-btn masteriyo-btn masteriyo-btn-primary">
					<?php echo esc_html__( 'Send Reset Email', 'masteriyo' ); ?>
				</button>

				<div class="masteriyo-reset-signin">
					<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'account' ) ); ?>" class="masteriyo-link-primary">
						<?php echo esc_html__( 'Go back to sign-in', 'masteriyo' ); ?>
					</a>
				</div>
			</div>

			<?php wp_nonce_field( 'masteriyo-password-reset-request' ); ?>

			<?php masteriyo_display_all_notices(); ?>
		</form>

	</div>
</section>

<?php

/**
 * Fires after rendering password reset request form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_password_reset_request_form' );
