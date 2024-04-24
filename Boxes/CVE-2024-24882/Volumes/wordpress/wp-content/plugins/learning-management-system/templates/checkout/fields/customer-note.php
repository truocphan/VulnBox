<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/customer-note.php.
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

use Masteriyo\Notice;
?>
<div class="masteriyo-checkout---order-note-wrapper">
	<div class="masteriyo-checkout----order-note">
		<label for="billing-order-note" class="masteriyo-label">
			<?php esc_html_e( 'Customer Note (Optional)', 'masteriyo' ); ?>
		</label>

		<textarea id="billing-order-note" class="masteriyo-input" name="customer_note"></textarea>

		<?php if ( masteriyo_notice_exists( 'customer_note', Notice::ERROR ) ) : ?>
		<div class=" masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'customer_note', Notice::ERROR ) ); ?>
		</div>
		<?php endif; ?>
	</div>
</div>
	<?php
