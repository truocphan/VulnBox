<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/first-and-last-name.php.
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

<div class="masteriyo-checkout---fname-lname-wrapper masteriyo-col-2">
	<!-- First name -->
	<div class="masteriyo-checkout----fname">
		<label for="billing-first-name" class="masteriyo-label">
			<?php esc_html_e( 'First Name', 'masteriyo' ); ?>
			<span>*</span>
		</label>

		<input
			type="text"
			id="billing-first-name"
			class="masteriyo-input"
			name="billing_first_name"
			value="<?php echo is_null( $user ) || is_wp_error( $user ) ? '' : esc_attr( $user->get_first_name() ); ?>"
		/>

		<?php if ( masteriyo_notice_exists( 'billing_first_name', Notice::ERROR ) ) : ?>
		<div class="masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_first_name', Notice::ERROR ) ); ?>
		</div>
		<?php endif; ?>
	</div>
	<!-- /First Name -->

	<!-- Last name -->
	<div class="masteriyo-checkout----lname">
		<label for="billing-last-name" class="masteriyo-label">
			<?php esc_html_e( 'Last Name', 'masteriyo' ); ?>
			<span>*</span>
		</label>

		<input
			type="text"
			id="billing-last-name"
			class="masteriyo-input"
			name="billing_last_name"
			value="<?php echo is_null( $user ) || is_wp_error( $user ) ? '' : esc_attr( $user->get_last_name() ); ?>"
		/>

		<?php if ( masteriyo_notice_exists( 'billing_last_name', Notice::ERROR ) ) : ?>
		<div class="masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_last_name', Notice::ERROR ) ); ?>
		</div>
		<?php endif; ?>
	</div>
	<!-- /Last Name -->
</div>
<?php
