<?php
/**
 * Apply for instructor by instructor approved email to instructor.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/instructor/instructor-apply-approved.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package masteriyo\Templates\Emails\HTML
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering email header.
 *
 * @since 1.6.13
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 * @param \Masteriyo\Models\User $instructor User object.
 */
do_action( 'masteriyo_email_header', $email, $instructor ); ?>

<p class="email-template--info">
<?php /* translators: %s: Instructor Display Name */ ?>
	<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $instructor->get_display_name() ) ); ?>
</p>
<p>
	<?php esc_html_e( 'Congratulations! Your application for instructor status has been approved.', 'masteriyo' ); ?>
</p>
<?php

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.6.13
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 * @param \Masteriyo\Models\User $instructor User object.
 */
do_action( 'masteriyo_email_footer', $email, $instructor );
