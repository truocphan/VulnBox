<?php
/**
 * Admin cancelled order email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/order-cancelled.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package masteriyo\Templates\Emails
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering email header.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_header', $email );
?>

<p>
<?php
	printf(
		/* translators: %s: Customer first name */
		esc_html__( 'Hi %s,', 'masteriyo' ),
		esc_html( $order->get_billing_first_name() )
	);
	?>
</p>
<p>
<?php
	printf(
		/* translators: %s: Course name */
		__( 'Your order just got cancelled for <b>%s</b>.', 'masteriyo' ),
		$order_item_course ? esc_html( $order_item_course->get_name() ) : ''
	);
	?>
</p>

<?php
/**
 * Action hook for rendering order details in cancelled order email.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order $order Order object.
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_order_details', $order, $email );

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_footer', $email );
