<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/company.php.
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
<div class="masteriyo-checkout---company-wrapper masteriyo-col-2">
	<div class="masteriyo-checkout----company">
		<label for="billing-company" class="masteriyo-label">
			<?php esc_html_e( 'Company Name', 'masteriyo' ); ?>
			<span>*</span>
		</label>

		<input
			type="text"
			id="billing-company"
			class="masteriyo-input"
			name="billing_company"
			value="<?php echo is_null( $user ) || is_wp_error( $user ) ? '' : esc_attr( $user->get_billing_company_name() ); ?>"
		/>

		<?php if ( masteriyo_notice_exists( 'billing_company', Notice::ERROR ) ) : ?>
			<div class="masteriyo-error masteriyo-danger-msg">
				<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_company', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
