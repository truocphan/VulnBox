<?php
/**
 * Masteriyo checkout form payment.
 *
 * @package Masteriyo\Templates;
 * @since 1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<li class="payment-method payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>">
	<input
		id="payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>"
		type="radio"
		class="input-radio"
		name="payment_method"
		value="<?php echo esc_attr( $gateway->get_name() ); ?>"
		<?php checked( $gateway->is_chosen(), true ); ?>
		data-order_button_text="<?php echo esc_attr( $gateway->get_order_button_text() ); ?>" />

	<div class="payment-method__detail">
		<label for="payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>" class="masteriyo-label">
			<?php
				echo esc_html( $gateway->get_title() );
				echo wp_kses_post( $gateway->get_icon() );
			?>
		</label>

		<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
			<div class="payment-box payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>" <?php ( ! $gateway->is_chosen() ) ? 'style="display:block;' : ''; ?>>
				<?php $gateway->payment_fields(); ?>
			</div>
		<?php endif; ?>
	</div>
</li>
<?php

