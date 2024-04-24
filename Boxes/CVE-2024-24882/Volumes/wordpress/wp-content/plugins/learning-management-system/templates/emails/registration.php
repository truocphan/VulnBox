<?php
/**
 * Registration email.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/registration.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering email header.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_header', $email ); ?>

<p class="email-template--info">
	<?php /* translators: %s: Customer username */ ?>
	<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $user->get_display_name() ) ); ?>
</p>

<p class="email-template--info">
	<?php
		printf(
			/* translators: %1$s: Site title, %2$s: Username, %3$s: account link */
			esc_html__( 'Thank you for registering at %1$s. You can now login to you account from %2$s', 'masteriyo' ),
			wp_kses_post( $site_title ),
			wp_kses_post( make_clickable( masteriyo_get_page_permalink( 'account' ) ) )
		);
		?>
</p>

<?php

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_footer', $email );
