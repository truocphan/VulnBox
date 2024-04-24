<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/order-details.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

/**
 * Fires before rendering order table in email.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order $order Order object.
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_before_order_table', $order, $email ); ?>

<h2>
	<?php
	/* translators: %s: Order ID. */
	echo wp_kses_post( sprintf( __( 'Order #%s', 'masteriyo' ), $order->get_order_number() ) );
	?>
</h2>

<div class="order-list">
	<table>
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Course', 'masteriyo' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'masteriyo' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			masteriyo_the_email_order_items(
				$order,
				array(
					'show_image' => false,
					'image_size' => array( 32, 32 ),
				)
			);
			?>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<th scope="row" colspan="1" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'masteriyo' ); ?></th>
					<td style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
</div>

<?php
/**
 * Fires after rendering order table in email.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order $order Order object.
 * @param \Masteriyo\Emails\Email $email Email object.
 */
do_action( 'masteriyo_email_after_order_table', $order, $email );
