<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/state.php.
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
<div class="masteriyo-checkout----state">
	<label for="billing-state" class="masteriyo-label">
		<?php esc_html_e( 'State', 'masteriyo' ); ?>
		<span>*</span>
	</label>
	<select id="billing-state" class="masteriyo-input" name="billing_state">
		<option value="">
			<?php esc_html_e( 'Select States', 'masteriyo' ); ?>
		</option>
	</select>

	<?php if ( masteriyo_notice_exists( 'billing_state', Notice::ERROR ) ) : ?>
		<div class="masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_state', Notice::ERROR ) ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
