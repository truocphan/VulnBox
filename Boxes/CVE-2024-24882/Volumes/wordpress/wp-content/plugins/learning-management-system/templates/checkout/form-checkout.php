<?php
/**
 * Masteriyo form checkout.
 *
 * @package Masteriyo\Templates
 * @since 1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering checkout form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_checkout_form' );

if ( ! is_user_logged_in() && ! masteriyo_is_guest_checkout_enabled() ) {
	$message = sprintf(
		/**
		 * Filters message to show for requiring user to login for using checkout page.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The message.
		 */
		apply_filters(
			'masteriyo_checkout_must_be_logged_in_message',
			// translators: %s: account page link
			__( 'You must be %1$slogged in%2$s to checkout.', 'masteriyo' )
		),
		'<a href="' . esc_url( masteriyo_get_account_url() ) . '">',
		'</a>'
	);

	echo wp_kses(
		$message,
		array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		)
	);

	return;
}

?>
<div class="masteriyo-checkout" id="masteriyo-checkout">
	<form
		name="checkout" method="post" class="masteriyo-w-100 masteriyo-container masteriyo-checkout--form masteriyo-checkout"
		action="<?php echo esc_url( masteriyo_get_checkout_url() ); ?>"
		enctype="multipart/form-data"
		class="checkout masteriyo-checkout"
		validate="novalidate">

		<div class="masteriyo-checkout-wrapper">
			<?php
			/**
			 * Fires inside form element of checkout form.
			 *
			 * @since 1.0.0
			 */
			do_action( 'masteriyo_checkout_form' );
			?>

			<div class="masteriyo-checkout-summary">
				<?php
				/**
				 * Action hook for rendering checkout summary in checkout form.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_checkout_summary' );
				?>
			</div>
		</div>

	</form>
</div>

<?php
/**
 * Fires after rendering checkout form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_checkout_form' );
