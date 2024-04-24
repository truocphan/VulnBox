<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/thankyou.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

use Masteriyo\Enums\OrderStatus;

defined( 'ABSPATH' ) || exit;

if ( ! $order ) {
	echo wp_kses_post(
		sprintf(
			'<p class = "masteriyo-notice masteriyo-notice--success masteriyo-thankyou-order-received">%s</p>',
			/**
			 * Filters order-received thank you message.
			 *
			 * @since 1.0.0
			 *
			 * @param string $message The thank you message.
			 */
			apply_filters( 'masteriyo_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'masteriyo' ), null )
		)
	);

	return;
}

?>

<div class="masteriyo-w-100 masteriyo-container masteriyo-order">
<?php
/**
 * Fires before rendering thankyou message.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_thankyou', $order->get_id() );
?>

	<?php if ( $order->has_status( OrderStatus::FAILED ) ) : ?>

		<p class="masteriyo-notice masteriyo-notice--error masteriyo-thankyou-order-failed">
			<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'masteriyo' ); ?>
		</p>

		<p class="masteriyo-notice masteriyo-notice--error masteriyo-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">
				<?php esc_html_e( 'Pay', 'masteriyo' ); ?>
			</a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'account' ) ); ?>" class="button pay">
					<?php esc_html_e( 'Account', 'masteriyo' ); ?>
				</a>
			<?php endif; ?>
		</p>

		<?php
			echo wp_kses_post(
				sprintf(
					'Go to %courses%s page.',
					wp_kses_post( '<a href="' . esc_url( masteriyo_get_page_permalink( 'courses' ) ) . '">' ),
					wp_kses_post( '</a>' )
				)
			);
		?>

	<?php else : ?>

		<p class="masteriyo-notice masteriyo-notice--success masteriyo-thankyou-order-received">
			<?php
			/**
			 * Filters order-received thank you message.
			 *
			 * @since 1.0.0
			 *
			 * @param string $message The thank you message.
			 */
			echo esc_html( apply_filters( 'masteriyo_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'masteriyo' ), $order ) );
			?>
		</p>

		<ul class="masteriyo-order-overview masteriyo-thankyou-order-details order_details">

			<li class="masteriyo-order-overview__order order">
				<?php esc_html_e( 'Order number:', 'masteriyo' ); ?>
				<strong><?php echo absint( $order->get_order_number() ); ?></strong>
			</li>

			<li class="masteriyo-order-overview__date date">
				<?php esc_html_e( 'Date:', 'masteriyo' ); ?>
				<strong><?php echo esc_html( masteriyo_format_datetime( $order->get_date_created() ) ); ?></strong>
			</li>

			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
				<li class="masteriyo-order-overview__email email">
					<?php esc_html_e( 'Email:', 'masteriyo' ); ?>
					<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
				</li>
			<?php endif; ?>

			<li class="masteriyo-order-overview__total total">
				<?php esc_html_e( 'Total:', 'masteriyo' ); ?>
				<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
			</li>

			<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="masteriyo-order-overview__payment-method method">
					<?php esc_html_e( 'Payment method:', 'masteriyo' ); ?>
					<strong><?php echo esc_html( $order->get_payment_method_title() ); ?></strong>
				</li>
			<?php endif; ?>
		</ul>

		<?php
			echo wp_kses_post(
				sprintf(
					'Go to %saccount%s page.',
					wp_kses_post( '<a href="' . esc_url( masteriyo_get_page_permalink( 'account' ) . '#/courses' ) . '">' ),
					wp_kses_post( '</a>' )
				)
			);
		?>

	<?php endif; ?>

	<?php
	/**
	 * Fires after rendering thankyou message. Targets specific payment methods.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id Order ID.
	 */
	do_action( 'masteriyo_thankyou_' . $order->get_payment_method(), $order->get_id() );
	?>
	<?php
	/**
	 * Fires before rendering thankyou message.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id Order ID.
	 */
	do_action( 'masteriyo_thankyou', $order->get_id() );
	?>

</div>
<?php

