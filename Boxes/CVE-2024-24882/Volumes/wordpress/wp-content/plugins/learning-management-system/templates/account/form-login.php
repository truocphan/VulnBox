<?php
/**
 * Login form template content.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/account/form-login.php.
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
 * Fires before rendering login form section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_login_form_content' );

// Render signup section if registration is enable.
$is_registration_enable = masteriyo_get_setting( 'general.registration.enable_student_registration' );
?>

<div class="masteriyo-login-form-wrapper<?php echo $is_registration_enable ? '' : ' no-signup'; ?>">
	<section class="masteriyo-login">
		<div class="masteriyo-login--wrapper">
			<h3 class="masteriyo-title"><?php echo esc_html__( 'Sign In', 'masteriyo' ); ?></h3>

			<?php masteriyo_display_all_notices(); ?>

			<form id="masteriyo-login--form" class="masteriyo-login--form" method="post">
				<input type="hidden" name="action" value="masteriyo_login">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( masteriyo_get_current_url() ); ?>">

				<?php wp_nonce_field( 'masteriyo_login_nonce' ); ?>

				<div class="masteriyo-username">
					<label for="username-email-address" class="masteriyo-label"><?php echo esc_html__( 'Username or Email', 'masteriyo' ); ?></label>
					<input id="username-email-address" name="username" type="text" required class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-password">
					<label for="password" class="masteriyo-label"><?php echo esc_html__( 'Password', 'masteriyo' ); ?></label>
					<input id="password" class="masteriyo-input" name="password" type="password" autocomplete="current-password" required placeholder="">
				</div>

				<div class="masteriyo-remember-forgot">
					<div class="masteriyo-remember-me">
						<input id="remember_me" name="remember_me" type="checkbox">
						<label for="remember_me">
							<?php echo esc_html__( 'Remember me', 'masteriyo' ); ?>
						</label>
					</div>

					<div class="masteriyo-forgot-password">
						<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'reset-password' ) ); ?>" class="masteriyo-link-primary">
							<?php echo esc_html__( 'Forgot your password?', 'masteriyo' ); ?>
						</a>
					</div>
				</div>

				<?php
				/**
				 * Fires before render of login button in login form.
				 *
				 * @since 1.5.10
				 */
				do_action( 'masteriyo_login_form_before_submit_button' );
				?>

				<div class="masteriyo-btn-wrapper">
					<button type="submit" class="masteriyo-btn masteriyo-btn-primary masteriyo-login-btn">
						<?php esc_html_e( 'Sign in', 'masteriyo' ); ?>
					</button>
				</div>

				<div id="masteriyo-login-error-msg" class="masteriyo-hidden masteriyo-notify-message masteriyo-alert masteriyo-danger-msg"></div>
			</form>
			<?php masteriyo_display_all_notices(); ?>

		</div>
	</section>

	<?php if ( $is_registration_enable ) : ?>
		<section class="masteriyo-signup">
			<?php
				$args = array(
					'redirect_to' => masteriyo_get_current_url(),
				);
				?>
			<h3 class="masteriyo-title"><?php esc_html_e( 'Register', 'masteriyo' ); ?></h3>
			<span><?php esc_html_e( "Don't have an account?", 'masteriyo' ); ?></span>
			<a
				href="<?php echo esc_url( add_query_arg( $args, masteriyo_get_account_endpoint_url( 'signup' ) ) ); ?>"
				class="masteriyo-btn masteriyo-btn-primary"
			>
				<?php esc_html_e( 'Register as Student', 'masteriyo' ); ?>
			</a>
		</section>
	<?php endif; ?>
</div>

<?php

/**
 * Fires after rendering login form section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_login_form_content' );
