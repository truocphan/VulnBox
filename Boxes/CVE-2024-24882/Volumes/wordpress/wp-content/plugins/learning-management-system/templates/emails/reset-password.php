<?php
/**
 * User password reset email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


/**
 * Fires before rendering email header.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_header', $email );

?>

<p class="email-template--info">
	<?php /* translators: %s: Username */ ?>
	<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $user->get_username() ) ); ?>
</p>

<p class="email-template--info">
	<?php /* translators: %s: Site name */ ?>
	<?php esc_html_e( 'Congratulations! Your registration was successful. To access the dashboard, please reset your password by clicking on the password reset button: ', 'masteriyo' ); ?>
</p>

<p class="email-template--info email-text--bold">
	<?php /* translators: %s: Username */ ?>
	<?php printf( esc_html__( 'Username: %s', 'masteriyo' ), esc_html( $user->get_username() ) ); ?>
</p>

<a
	class="email-template--button"
	href="<?php echo esc_url( masteriyo_get_password_reset_link( $reset_key, $user->get_id() ) ); ?>"
>
	<?php esc_html_e( 'Click here to reset your password.', 'masteriyo' ); ?>
</a>

<?php

/**
 * Show user-defined additional content.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_footer', $email );
