<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/postcode.php.
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

<div class="masteriyo-checkout----zip-code">
	<label for="billing-zip-code" class="masteriyo-label">
		<?php esc_html_e( 'Zip Code / Postal Code', 'masteriyo' ); ?>
		<span>*</span>
	</label>

	<input
		type="text"
		id="billing-zip-code"
		class="masteriyo-input"
		name="billing_postcode"
		value="<?php echo is_null( $user ) || is_wp_error( $user ) ? '' : esc_attr( $user->get_billing_postcode() ); ?>"
	/>

	<?php if ( masteriyo_notice_exists( 'billing_postcode', Notice::ERROR ) ) : ?>
		<div class="masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_postcode', Notice::ERROR ) ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
