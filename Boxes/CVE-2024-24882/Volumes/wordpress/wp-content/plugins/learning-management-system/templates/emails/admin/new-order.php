<?php
/**
 * New order email to admin.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/admin/new-order.php.
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
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 * @param \Masteriyo\Emails\Order $order Order object.
 */
do_action( 'masteriyo_email_header', $email, $order ); ?>

<p class="email-template--info">
	<?php /* translators: %s: Customer username */ ?>
	<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $site_title ) ); ?>
</p>
<p>
	<?php esc_html_e( 'Great News! You made a sale!', 'masteriyo' ); ?>
</p>
<p>
	<?php
	printf(
		'%s just purchased <b>%s</b> for %s.',
		esc_html( $customer->get_billing_first_name() ),
		$order_item_course ? esc_html( $order_item_course->get_name() ) : '',
		wp_kses_post( masteriyo_price( $order->get_total() ) )
	);
	?>
</p>
<?php
/**
 * Action hook for rendering order details in new order email.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order $order Order object.
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_order_details', $order, $email );


/**
 * Action hook for rendering customer details.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order $order Order object.
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_customer_details', $order, $email );

/**
 * Action hook fired in email's footer section.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Emails\Email $email Email object.
 * @param \Masteriyo\Models\Order $order Order object.
 */
do_action( 'masteriyo_email_footer', $email, $order );
