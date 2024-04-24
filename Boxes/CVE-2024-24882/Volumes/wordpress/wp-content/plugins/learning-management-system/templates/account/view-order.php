<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/account/view-order.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<p>
	<?php
	printf(
		/* translators: 1: order number 2: order date 3: order status */
		esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'masteriyo' ),
		'<mark class="order-number">' . absint( $order->get_order_number() ) . '</mark>',
		'<mark class="order-date">' . esc_html( masteriyo_format_datetime( $order->get_date_created() ) ) . '</mark>',
		'<mark class="order-status">' . esc_html( masteriyo_get_order_status_name( $order->get_status() ) ) . '</mark>'
	);
	?>
</p>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'masteriyo' ); ?></h2>
	<ol class="commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="comment note">
			<div class="comment_container">
				<div class="comment-text">
					<p class="meta"><?php echo esc_html( date_i18n( 'l jS \o\f F Y, h:ia', strtotime( $note->comment_date ) ) ); ?></p>
					<div class="description">
						<?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
	<?php
endif;

?>
<section class="masteriyo-order-details">
	<?php
	/**
	 * Fires before rendering order details table in account page.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\Order $order Order object.
	 */
	do_action( 'masteriyo_order_details_before_order_table', $order );
	?>

	<h2><?php esc_html_e( 'Order details', 'masteriyo' ); ?></h2>

	<table>
		<thead>
			<tr>
				<th><?php esc_html_e( 'course', 'masteriyo' ); ?></th>
				<th><?php esc_html_e( 'Total', 'masteriyo' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			/**
			 * Fires before rendering order items in in account page.
			 *
			 * @since 1.0.0
			 *
			 * @param \Masteriyo\Models\Order\Order $order Order object.
			 */
			do_action( 'masteriyo_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$course = $item->get_course();

				if ( is_null( $course ) ) {
					continue;
				}
				?>
				<tr class="
				<?php
				/**
				 * Filters order item table row class.
				 *
				 * @since 1.0.0
				 *
				 * @param string $class The order item table row class.
				 * @param object $order_item Order item object.
				 * @param Masteriyo\Models\Order\Order $order Order object.
				 */
				echo esc_attr( apply_filters( 'masteriyo_order_item_class', 'masteriyo-table__line-item order_item', $item, $order ) );
				?>
				">

					<td>
						<?php
						/**
						 * Filters order item permalink.
						 *
						 * @since 1.0.0
						 *
						 * @param string $url The order item permalink.
						 * @param object $order_item Order item object.
						 * @param Masteriyo\Models\Order\Order $order Order object.
						 */
						$course_permalink = apply_filters( 'masteriyo_order_item_permalink', $course->get_permalink( $item ), $item, $order );
						$course_name      = $course_permalink ? sprintf( '<a href="%s">%s</a>', $course_permalink, $item->get_name() ) : $item->get_name();

						/**
						 * Filters order item name to be displayed in order detail table.
						 *
						 * @since 1.0.0
						 *
						 * @param string $item_name The order item name.
						 * @param object $order_item Order item object.
						 */
						echo wp_kses_post( apply_filters( 'masteriyo_order_item_name', $course_name, $item ) );

						$qty          = $item->get_quantity();
						$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

						if ( $refunded_qty ) {
							$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
						} else {
							$qty_display = esc_html( $qty );
						}

						echo wp_kses_post(
							/**
							 * Filters order item quantity html to render.
							 *
							 * @since 1.0.0
							 *
							 * @param string $html The order item quantity html to render.
							 * @param object $order_item Order item object.
							 */
							apply_filters(
								'masteriyo_order_item_quantity_html',
								' <strong class="course-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>',
								$item
							)
						);

						/**
						 * Fires before rendering order item meta in account page.
						 *
						 * @since 1.0.0
						 *
						 * @param integer $item_id Order item ID.
						 * @param object $item Order item object.
						 * @param \Masteriyo\Models\Order\Order $order Order object.
						 */
						do_action( 'masteriyo_order_item_meta_start', $item_id, $item, $order );

						masteriyo_display_item_meta( $item );

						/**
						 * Fires after rendering order item meta in account page.
						 *
						 * @since 1.0.0
						 *
						 * @param integer $item_id Order item ID.
						 * @param object $item Order item object.
						 * @param \Masteriyo\Models\Order\Order $order Order object.
						 */
						do_action( 'masteriyo_order_item_meta_end', $item_id, $item, $order );
						?>
					</td>

					<td class="course-total">
						<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
					</td>
				</tr>
				<?php
			}
			/**
			 * Fires after rendering order items in in account page.
			 *
			 * @since 1.0.0
			 *
			 * @param \Masteriyo\Models\Order\Order $order Order object.
			 */
			do_action( 'masteriyo_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
				<tr>
					<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
					<td><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tfoot>
	</table>

	<?php
	/**
	 * Fires after rendering order details table in account page.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\Order $order Order object.
	 */
	do_action( 'masteriyo_order_details_after_order_table', $order );
	?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order $order Order object.
 */
do_action( 'masteriyo_after_order_details', $order );

if ( $show_customer_details ) {
	?>
	<section class="masteriyo-customer-details">

		<h2><?php esc_html_e( 'Billing address', 'masteriyo' ); ?></h2>

		<address>
			<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'masteriyo' ) ) ); ?>

			<?php if ( $order->get_billing_phone() ) : ?>
				<p><?php echo esc_html( $order->get_billing_phone() ); ?></p>
			<?php endif; ?>

			<?php if ( $order->get_billing_email() ) : ?>
				<p><?php echo esc_html( $order->get_billing_email() ); ?></p>
			<?php endif; ?>
		</address>

		<?php
		/**
		 * Fires after rendering customer details in order details section in account page.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Models\Order\Order $order Order object.
		 */
		do_action( 'masteriyo_order_details_after_customer_details', $order );
		?>

	</section>
	<?php
}
