<?php
/**
 * Apply for instructor by student rejected email to student.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/student/instructor-apply-rejected.php.
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
 * @param \Masteriyo\Models\User $student User object.
 */
do_action( 'masteriyo_email_header', $email, $student ); ?>

<p class="email-template--info">
<?php /* translators: %s: Student Display Name */ ?>
	<?php printf( esc_html__( 'Dear %s,', 'masteriyo' ), esc_html( $student->get_display_name() ) ); ?>
</p>
<p>
	<?php esc_html_e( 'We regret to inform you that your application for instructor status has been rejected.', 'masteriyo' ); ?>
</p>
<?php

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.6.13
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 * @param \Masteriyo\Models\User $student User object.
 */
do_action( 'masteriyo_email_footer', $email, $student );
