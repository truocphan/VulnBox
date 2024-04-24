<?php

/**
 * My Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/account/orders.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @since 1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering order-history section in account page.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order[] $orders Orders objects.
 */
do_action( 'masteriyo_before_account_orders', $orders );

?>
<?php if ( count( $orders ) > 0 ) : ?>
<table class="masteriyo-account-orders-table">
	<thead>
		<tr>
			<?php foreach ( masteriyo_get_account_orders_columns() as $column_id => $column_name ) : ?>
				<th class="masteriyo-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
	</thead>

	<tbody>
		<?php
		foreach ( $orders as $order ) {  // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			?>
			<tr class="status-<?php echo esc_attr( $order->get_status() ); ?>">
				<?php foreach ( masteriyo_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<td class="masteriyo-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php if ( has_action( 'masteriyo_my_account_my_orders_column_' . $column_id ) ) : ?>
							<?php
							/**
							 * Fires before rendering a column content in order history table in account page.
							 *
							 * @since 1.0.0
							 *
							 * @param \Masteriyo\Models\Order\Order $order Order object for current row.
							 */
							do_action( 'masteriyo_my_account_my_orders_column_' . $column_id, $order );
							?>

						<?php elseif ( 'order-number' === $column_id ) : ?>
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
								<?php echo esc_html( _x( '#', 'hash before order number', 'masteriyo' ) . $order->get_order_number() ); ?>
							</a>

						<?php elseif ( 'order-date' === $column_id ) : ?>
							<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
								<?php echo esc_html( masteriyo_format_datetime( $order->get_date_created() ) ); ?>
							</time>

						<?php elseif ( 'order-status' === $column_id ) : ?>
							<?php echo esc_html( masteriyo_get_order_status_name( $order->get_status() ) ); ?>

						<?php elseif ( 'order-total' === $column_id ) : ?>
							<?php
							echo wp_kses_post(
								sprintf(
									/* translators: 1: Formatted order total 2: Total order items */
									_n(
										'%1$s for %2$s item',
										'%1$s for %2$s items',
										$order->get_item_count(),
										'masteriyo'
									),
									$order->get_formatted_order_total(),
									$order->get_item_count()
								)
							);
							?>

						<?php elseif ( 'order-actions' === $column_id ) : ?>
							<?php
							$order_actions = masteriyo_get_account_orders_actions( $order );

							if ( ! empty( $order_actions ) ) {
								foreach ( $order_actions as $key => $order_action ) {
									echo wp_kses_post(
										sprintf(
											/* translators: 1: Order action url 2: Order action class 3: Order action title 4:Order action name */
											__( '<a href="%1$s" class="%2$s" title="%3$s">%4$s</a>', 'masteriyo' ),
											esc_url( $order->get_view_order_url() ),
											esc_attr( 'masteriyo-button button ' . sanitize_html_class( $key ) ),
											esc_html( $order_action['name'] ),
											esc_html( $order_action['name'] )
										)
									);
								}
							}
							?>
						<?php endif; ?>
					</td>
				<?php endforeach; ?>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

<?php else : ?>
	<div class="masteriyo-message masteriyo-message--info masteriyo-Message masteriyo-Message--info masteriyo-info">
		<a class="masteriyo-Button button" href="
		<?php
		/**
		 * Filters return to shop URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The return to shop URL.
		 */
		echo esc_url( apply_filters( 'masteriyo_return_to_shop_redirect', masteriyo_get_page_permalink( 'courses' ) ) );
		?>
		"><?php esc_html_e( 'Browse courses', 'masteriyo' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'masteriyo' ); ?>
	</div>
<?php endif; ?>

<?php

/**
 * Fires after rendering order-history section in account page.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\Order\Order[] $orders Orders objects.
 */
do_action( 'masteriyo_after_account_orders', $orders );
