<?php
/**
 * Apply for instructor by student email to admin.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/admin/instructor-apply.php.
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
 * @param \Masteriyo\Models\User $user User object.
 */
do_action( 'masteriyo_email_header', $email, $user ); ?>

<p class="email-template--info">
	<?php /* translators: %s: Display Name */ ?>
	<?php printf( esc_html__( 'Dear %s,', 'masteriyo' ), esc_html( $user->get_display_name() ) ); ?>
</p>
<p>
	<?php esc_html_e( 'A student has applied for instructor status.', 'masteriyo' ); ?>
</p>
<p>
	<?php
	printf(
		esc_html__( 'The student with the following details has applied for instructor status:', 'masteriyo' )
	);
	?>
</p>
<p>
	<?php
	printf(
		/* translators: %s: User Name */
		esc_html__( 'Name: %s', 'masteriyo' ),
		esc_html( $user->get_display_name() )
	);
	?>
	<br>
	<?php
	printf(
		/* translators: %s: User email */
		esc_html__( 'Email: %s', 'masteriyo' ),
		esc_html( $user->get_email() )
	);
	?>
</p>
<?php

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.6.13
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 * @param \Masteriyo\Models\User $user User object.
 */
do_action( 'masteriyo_email_footer', $email, $user );
