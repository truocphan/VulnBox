<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/gdpr.php.
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
<div class="masteriyo-checkout---gdpr-wrapper">
	<div class="masteriyo-checkout----gdpr">
		<input type="checkbox" id="gdpr" name="gdpr">

		<?php
			echo wp_kses_post(
				sprintf(
				/* translators: %1$s: GDPR message, %2$s: Privacy policy url */
					__( '%1$s <a href="%2$s" target="_blank">Privacy Policy</a>', 'masteriyo' ),
					esc_attr( $gdpr_message ),
					esc_url( get_privacy_policy_url() )
				)
			);
			?>

		<?php if ( masteriyo_notice_exists( 'gdpr', Notice::ERROR ) ) : ?>
		<div class="masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'gdpr', Notice::ERROR ) ); ?>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
