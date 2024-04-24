<?php

/**
 * Sign up form template content.
 *
 * @version 1.0.0
 */

use Masteriyo\Addons\UserRegistrationIntegration\Helper;
use Masteriyo\Pro\Addons;

defined( 'ABSPATH' ) || exit;

$session = masteriyo( 'session' );

/**
 * Fires before rendering instructor registration form.
 *
 * @since 1.2.0
 */
do_action( 'masteriyo_before_instructor_registration_form_content' );

if ( ( new Addons() )->is_active( 'user-registration-integration' ) && method_exists( Helper::class, 'is_registration_form_replaceable' ) && Helper::is_registration_form_replaceable( 'instructor' ) ) {
	return;
}
?>

<section class="masteriyo-signup">
		<div class="masteriyo-signup--wrapper masteriyo-form-container">
			<h3 class="masteriyo-signup--title"><?php echo esc_html__( 'Become an instructor', 'masteriyo' ); ?></h3>

			<?php masteriyo_display_all_notices(); ?>

			<form id="masteriyo-instructor-registration--form" class="masteriyo-signup-form" method="post">
				<input type="hidden" name="remember" value="true">
				<?php wp_nonce_field( 'masteriyo-instructor-registration' ); ?>

				<div class="masteriyo-first-name">
					<label for="first-name" class="masteriyo-label">
						<?php echo esc_html__( 'First Name', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="first-name" class="masteriyo-input" name="first-name" type="text" value="<?php echo esc_attr( $session->get( 'instructor-registration.first-name' ) ); ?>" >
				</div>

				<div class="masteriyo-last-name">
					<label for="last-name" class="masteriyo-label">
						<?php echo esc_html__( 'Last Name', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="last-name" class="masteriyo-input" name="last-name" type="text" value="<?php echo esc_attr( $session->get( 'instructor-registration.last-name' ) ); ?>">
				</div>
				<div class="masteriyo-username">
					<label for="username" class="masteriyo-label">
						<?php echo esc_html__( 'Username', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="username" class="masteriyo-input" name="username" type="text"  value="<?php echo esc_attr( $session->get( 'instructor-registration.username' ) ); ?>">
				</div>

				<div class="masteriyo-email">
					<label for="email" class="masteriyo-label">
						<?php echo esc_html__( 'Email Address', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="email" class="masteriyo-input" name="email" type="text"  value="<?php echo esc_attr( $session->get( 'instructor-registration.email' ) ); ?>">
				</div>

				<div class="masteriyo-password">
					<label for="password" class="masteriyo-label">
						<?php echo esc_html__( 'Password', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="password" class="masteriyo-input" name="password" type="password" autocomplete="current-password"  value="<?php echo esc_attr( $session->get( 'instructor-registration.password' ) ); ?>">
				</div>

				<div class="masteriyo-confirm-password">
					<label for="confirm-password" class="masteriyo-label">
						<?php echo esc_html__( 'Confirm Password', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="confirm-password" class="masteriyo-input" name="confirm-password" type="password" autocomplete="current-password" >
				</div>

				<?php if ( masteriyo_show_gdpr_msg() ) : ?>
					<div class="masteriyo-gdpr">
						<input type="checkbox" id="gdpr" name="gdpr">
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %1$s: GDPR message, %2$s: Privacy policy url */
								__( '%1$s <a href="%2$s" target="_blank">Privacy Policy</a>', 'masteriyo' ),
								esc_html( masteriyo_get_setting( 'advance.gdpr.message' ) ),
								esc_url( get_privacy_policy_url() )
							)
						);
						?>
					</div>
				<?php endif; ?>

				<?php
				/**
				 * Fires before render of submit button in instructor registration form.
				 *
				 * @since 1.5.10
				 */
				do_action( 'masteriyo_instructor_registration_form_before_submit_button' );
				?>
				<button type="submit" name="masteriyo-instructor-registration" value="yes" class="masteriyo-btn-signup masteriyo-btn masteriyo-btn-primary">
					<?php echo esc_html__( 'Register', 'masteriyo' ); ?>
				</button>

				<div class="masteriyo-notify-message masteriyo-alert masteriyo-danger-msg masteriyo-hidden"></div>
				<div class="masteriyo-notify-message masteriyo-alert masteriyo-success-msg masteriyo-hidden">
					<span><?php echo esc_html__( 'Registration complete.', 'masteriyo' ); ?></span>
				</div>
			</form>
		</div>
</section>

<?php

/**
 * Fires after rendering instructor registration form.
 *
 * @since 1.2.0
 */
do_action( 'masteriyo_after_instructor_registration_form_content' );
