<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly*/
}
do_action( 'stm_lms_template_main' );

get_header();

stm_lms_register_style( 'pmpro_membership' );
?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
<div class="stm-lms-wrapper">
	<div class="container">

		<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

		<div class="stm-lms-user-memberships">
		<?php
		global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels;

		$pmpro_order = new MemberOrder();
		$pmpro_order->getLastMemberOrder();
		$mylevels     = pmpro_getMembershipLevelsForUser();
		$pmpro_levels = pmpro_getAllLevels( false, true );
		$gateway      = pmpro_getOption( 'gateway' );
		$invoices     = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT *, UNIX_TIMESTAMP(CONVERT_TZ(timestamp, '+00:00', @@global.time_zone)) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = %d AND status NOT IN('review', 'token', 'error') ORDER BY timestamp DESC LIMIT 4",
				$current_user->ID
			)
		);
		?>
<div id="pmpro_account">
	<h3><?php esc_html_e( 'My Memberships', 'masterstudy-lms-learning-management-system' ); ?></h3>
		<div id="pmpro_account-membership" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_box', 'pmpro_account-membership' ) ); ?>">
			<table class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_table' ) ); ?>" width="100%" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Level', 'masterstudy-lms-learning-management-system' ); ?></th>
						<th><?php esc_html_e( 'Billing', 'masterstudy-lms-learning-management-system' ); ?></th>
						<th><?php esc_html_e( 'Expiration', 'masterstudy-lms-learning-management-system' ); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $mylevels ) ) { ?>
					<tr>
						<td colspan="3">
						<?php
						/* Check to see if the user has a cancelled order*/
						$pmpro_order = new MemberOrder();
						$pmpro_order->getLastMemberOrder( $current_user->ID, array( 'cancelled', 'expired', 'admin_cancelled' ) );

						if ( isset( $pmpro_order->membership_id ) && ! empty( $pmpro_order->membership_id ) && empty( $level->id ) ) {
							$level = pmpro_getLevel( $pmpro_order->membership_id );
						}

						// If no level check for a default level.
						if ( empty( $level ) || ! $level->allow_signups ) {
							$default_level_id = apply_filters( 'pmpro_default_level', 0 );
						}

						// Show the correct checkout link.
						if ( ! empty( $level ) && ! empty( $level->allow_signups ) ) {
							printf(
								/* translators: %s URL */
								wp_kses_post( __( "Your membership is not active. <a href='%s'>Renew now.</a>", 'masterstudy-lms-learning-management-system' ) ),
								esc_url( pmpro_url( 'checkout', '?level=' . $level->id ) )
							);
						} elseif ( ! empty( $default_level_id ) ) {
							printf(
								/* translators: %s URL */
								wp_kses_post( __( "You do not have an active membership. <a href='%s'>Register here.</a>", 'masterstudy-lms-learning-management-system' ) ),
								esc_url( pmpro_url( 'checkout', '?level=' . $default_level_id ) )
							);
						} else {
							printf(
								/* translators: %s URL */
								wp_kses_post( __( "You do not have an active membership. <a href='%s'>Choose a membership level.</a>", 'masterstudy-lms-learning-management-system' ) ),
								esc_url( pmpro_url( 'levels' ) )
							);
						}
						?>
						</td>
					</tr>
						<?php } else { ?>
						<?php
						foreach ( $mylevels as $level ) {
							?>
						<tr>
							<td class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_account-membership-levelname' ) ); ?>">
							<?php echo esc_html( $level->name ); ?>
							</td>
							<td class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_account-membership-levelfee' ) ); ?>">
								<p><?php echo wp_kses_post( pmpro_getLevelCost( $level, true, true ) ); ?></p>
							</td>
							<td class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_account-membership-expiration' ) ); ?>">
							<?php
								$expiration_text = '<p>';
							if ( $level->enddate ) {
								$expiration_text .= date_i18n( get_option( 'date_format' ), $level->enddate );
								/**
								 * Filter to include the expiration time with expiration date
								 *
								 * @param bool $pmpro_show_time_on_expiration_date Show the expiration time with expiration date (default: false).
								 *
								 * @return bool $pmpro_show_time_on_expiration_date Whether to show the expiration time with expiration date.
								 */
								if ( apply_filters( 'pmpro_show_time_on_expiration_date', false ) ) {
									$expiration_text .= ' ' . date_i18n( get_option( 'time_format', __( 'g:i a' ) ), $level->enddate );
								}
							} else {
								$expiration_text .= esc_html_x( '&#8212;', 'A dash is shown when there is no expiration date.', 'masterstudy-lms-learning-management-system' );
							}
								$expiration_text .= '</p>';
								echo wp_kses_post( apply_filters( 'pmpro_account_membership_expiration_text', $expiration_text, $level ) );
							?>
							</td>
							<td>
							<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_actionlinks' ) ); ?>">
								<?php
								do_action( 'pmpro_member_action_links_before' );

								// Build the links to return.
								$pmpro_member_action_links = array();

								if ( array_key_exists( $level->id, $pmpro_levels ) && pmpro_isLevelExpiringSoon( $level ) ) {
									?>
										<a id="pmpro_actionlink-renew" href="<?php echo esc_url( add_query_arg( 'level', $level->id, pmpro_url( 'checkout', '', 'https' ) ) ); ?>"><?php echo esc_html__( 'Renew', 'masterstudy-lms-learning-management-system' ); ?></a>
									<?php
								}

								if ( ( isset( $pmpro_order->status ) && 'success' === $pmpro_order->status ) && ( 'stripe' === $gateway ) && pmpro_isLevelRecurring( $level ) ) {
									?>
										<a id="pmpro_actionlink-update-billing" href="<?php echo esc_url( pmpro_url( 'billing', '', 'https' ) ); ?>"><img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/pmpro/change_billing.svg' ); ?>"></a>
									<?php
								}
								/* To do: Only show CHANGE link if this level is in a group that has upgrade/downgrade rules*/
								if ( count( $pmpro_levels ) > 1 && ! defined( 'PMPRO_DEFAULT_LEVEL' ) ) {
									?>
										<a id="pmpro_actionlink-change" href="<?php echo esc_url( pmpro_url( 'levels' ) ); ?>">
											<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/pmpro/change_plan.svg' ); ?>">
										</a>
									<?php
								}
								if ( array_key_exists( $level->id, $pmpro_levels ) ) {
									?>
									<a id="pmpro_actionlink-cancel" href="<?php echo esc_url( add_query_arg( 'levelstocancel', $level->id, pmpro_url( 'cancel' ) ) ); ?>"><img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/pmpro/pmpro_cancel_plan.svg' ); ?>"></a>
									<?php
								}

								do_action( 'pmpro_member_action_links_after' );
								?>
								</div> <!-- end pmpro_actionlinks -->
							</td>
						</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- end pmpro_account-membership -->
		<h3><?php esc_html_e( 'Past Invoices', 'masterstudy-lms-learning-management-system' ); ?></h3>
		<div id="pmpro_account-invoices" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_box', 'pmpro_account-invoices' ) ); ?>">
		<table class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_table' ) ); ?>" width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Date', 'masterstudy-lms-learning-management-system' ); ?></th>
					<th><?php esc_html_e( 'Level', 'masterstudy-lms-learning-management-system' ); ?></th>
					<th><?php esc_html_e( 'Amount', 'masterstudy-lms-learning-management-system' ); ?></th>
					<th><?php esc_html_e( 'Status', 'masterstudy-lms-learning-management-system' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $invoices as $invoice ) {
				/* get an member order object*/
				$invoice_id = $invoice->id;
				$invoice    = new MemberOrder();

				$invoice->getMemberOrderByID( $invoice_id );
				$invoice->getMembershipLevel();

				// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				if ( in_array( $invoice->status, array( '', 'success', 'cancelled' ) ) ) {
					$display_status = __( 'Paid', 'masterstudy-lms-learning-management-system' );
				} elseif ( 'pending' === $invoice->status ) {
					// Some Add Ons set status to pending.
					$display_status = __( 'Pending', 'masterstudy-lms-learning-management-system' );
				} elseif ( 'refunded' === $invoice->status ) {
					$display_status = __( 'Refunded', 'masterstudy-lms-learning-management-system' );
				}
				?>
					<tr id="pmpro_account-invoice-<?php echo esc_attr( $invoice->code ); ?>">
						<td><a href="<?php echo esc_url( pmpro_url( 'invoice', '?invoice=' . $invoice->code ) ); ?>"><?php echo esc_html( date_i18n( get_option( 'date_format' ), $invoice->getTimestamp() ) ); ?></a></td>
						<td>
						<?php
						if ( ! empty( $invoice->membership_level ) ) {
							echo esc_html( $invoice->membership_level->name );
						} else {
							echo esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );
						}
						?>
						</td>
						<td><?php echo wp_kses_post( pmpro_escape_price( pmpro_formatPrice( $invoice->total ) ) ); ?></td>
						<td><?php echo ( isset( $display_status ) ) ? esc_html( $display_status ) : ''; ?></td>
					</tr>
					<?php
			}
			?>
			</tbody>
		</table>
		<?php if ( count( $invoices ) === 4 ) { ?>
			<div class="view_all_invoices"><a id="pmpro_actionlink-invoices" href="<?php echo esc_url( pmpro_url( 'invoice' ) ); ?>"><?php esc_html_e( 'View All Invoices', 'masterstudy-lms-learning-management-system' ); ?></a></div>
		<?php } ?>
	</div> <!-- end pmpro_account-invoices -->

</div> <!-- end pmpro_account -->

		</div>
	</div>
</div>
<?php
get_footer();
