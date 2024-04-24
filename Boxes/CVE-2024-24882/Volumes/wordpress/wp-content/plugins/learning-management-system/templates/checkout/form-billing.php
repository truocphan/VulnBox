<?php

/**
 * Masteriyo billing form.
 *
 * @package Masteriyo\Templates
 * @since 1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering billing address form in checkout page.
 *
 * @since 1.0.0
 *
 * @since 1.6.0  Added $user and $checkout parameter.
 *
 * @param \Masteriyo\Models\User $user
 * @param \Masteriyo\Checkout $checkout
 */
do_action( 'masteriyo_checkout_before_billing', $user, $checkout );
?>

<div class="masteriyo-checkout-main">
	<h3 class="masteriyo-checkout--title">
		<?php esc_html_e( 'Payment Details', 'masteriyo' ); ?>
	</h3>

	<form action="" class="masteriyo-checkout--form">
		<?php
		/**
		 * Checkout form.
		 *
		 * @since 1.6.0
		 *
		 * @param \Masteriyo\Models\User $user
		 * @param \Masteriyo\Checkout $checkout
		 */
		do_action( 'masteriyo_checkout_form_content', $user, $checkout );
		?>
	</form>
</div>
<?php
/**
 * Fires after rendering billing address form in checkout page.
 *
 * @since 1.0.0
 * @param \Masteriyo\Models\User $user
 * @param \Masteriyo\Checkout $checkout
 */
do_action( 'masteriyo_checkout_after_billing', $user, $checkout );
