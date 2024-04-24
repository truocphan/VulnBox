<?php
/**
 * Password reset request confirmation.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/account/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\MyAccoun
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

masteriyo_display_all_notices();


/**
 * Fires before rendering password reset request confirmation message.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_reset_password_request_confirmation_message' );

?>

<p>
	<?php
	/**
	 * Filters password reset request confirmation message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message The password reset request confirmation message.
	 */
	echo esc_html( apply_filters( 'masteriyo_reset_password_request_confirmation_message', esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'masteriyo' ) ) );
	?>
</p>

<?php

/**
 * Fires after rendering password reset request confirmation message.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_reset_password_request_confirmation_message' );

