<?php

/**
 * Customer new account email verification.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/email-verification.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 *
 * @since 1.6.12
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering email header.
 *
 * @since 1.6.12
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_header', $email ); ?>

<p class="email-template--info">
<?php /* translators: %s: Customer username */ ?>
<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $instructor->get_display_name() ) ); ?>
</p>

<p class="email-template--info">
	<?php
	printf(
		/* translators: %1$s: Site title, %2$s: Username */
		esc_html__( 'Thank you for registering at %1$s.', 'masteriyo' ),
		wp_kses_post( $site_title ),
		esc_html( $instructor->get_display_name() )
	);
	?>
</p>

<p class="email-template--info">
	<?php
	printf(
		/* translators: %s: Account verification link */
		esc_html__( 'Please click the following link to verify your account and complete the registration process: ', 'masteriyo' )
	);

	printf( '<a href="%s">%s</a>', esc_url( masteriyo_generate_email_verification_link( $instructor, wp_create_nonce( 'masteriyo_email_verification_nonce' ) ) ), esc_html__( 'Verify Account', 'masteriyo' ) );
	?>
</p>


<p class="email-template--info">
	<?php
	/* translators: %s: Verification link expiration time */
	printf( esc_html__( 'Please note that this verification link is valid for 24 hours only. After that, you will need to request a new verification link.', 'masteriyo' ), esc_html__( '24 hours', 'masteriyo' ) );
	?>
</p>

<p class="email-template--info">
	<?php
	/* translators: %s: Site title*/
	printf( esc_html__( 'Best regards,%s', 'masteriyo' ), wp_kses_post( '<br />' . $site_title ) );
	?>
</p>

<?php

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.6.12
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_footer', $email );
