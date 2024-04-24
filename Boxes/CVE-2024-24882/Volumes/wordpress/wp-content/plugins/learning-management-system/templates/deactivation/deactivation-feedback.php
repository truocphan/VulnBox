<?php
/**
 * Masteriyo deactivation feedback.
 *
 * @package Masteriyo\Templates\Deactivation
 *
 * @since 1.6.0
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $status, $page, $s;
$deactivate_url = wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . MASTERIYO_PLUGIN_BASENAME . '&amp;plugin_status=' . $status . '&amp;paged=' . $page . '&amp;s=' . $s, 'deactivate-plugin_' . MASTERIYO_PLUGIN_BASENAME );
?>
<div id="masteriyo-deactivate-feedback-popup-wrapper">
	<div class="masteriyo-deactivate-feedback-popup-inner">
		<div class="masteriyo-deactivate-feedback-popup-header">
			<div class="masteriyo-deactivate-feedback-popup-header__logo-wrap">
				<div class="masteriyo-deactivate-feedback-popup-header__logo-icon">
					<?php masteriyo_get_svg( 'logo', true ); ?>
				</div>
				<span class="masteriyo-deactivate-feedback-popup-header-title"><?php esc_html_e( 'Quick Feedback', 'masteriyo' ); ?></span>
			</div>
			<a class="close-deactivate-feedback-popup"><span class="dashicons dashicons-no-alt"></span></a>
		</div>
		<form class="masteriyo-deactivate-feedback-form" method="POST">
			<?php wp_nonce_field( 'masteriyo_deactivation_feedback_nonce' ); ?>
			<input type="hidden" name="action" value="masteriyo_deactivation_feedback"/>
			<div class="masteriyo-deactivate-feedback-popup-form-caption">
			<?php
				printf(
				/* translators: %1$s: Opening span tag, %2$s: Closing span tag */
					esc_html__( 'Could you please share why you are deactivating %1$sMasteriyo%2$s plugin?', 'masteriyo' ),
					'<span>',
					'</span>'
				);
				?>
			</div>
			<div class="masteriyo-deactivate-feedback-popup-form-body">
				<?php foreach ( $deactivate_reasons as $reason_slug => $reason ) : ?>
					<div class="masteriyo-deactivate-feedback-popup-input-wrapper">
						<input
							id="masteriyo-deactivate-feedback-<?php echo esc_attr( $reason_slug ); ?>"
							class="masteriyo-deactivate-feedback-input"
							type="radio"
							name="reason_slug"
							value="<?php echo esc_attr( $reason_slug ); ?>"
						/>

						<label
							for="masteriyo-deactivate-feedback-<?php echo esc_attr( $reason_slug ); ?>"
							class="masteriyo-deactivate-feedback-label">
								<?php echo esc_html( $reason['title'] ); ?>
						</label>
						<?php if ( $reason['is_input'] ) : ?>
							<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
								<input class="masteriyo-feedback-text" type="text"
								name="reason_<?php echo esc_attr( $reason_slug ); ?>"
								placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>"/>
							<?php endif; ?>
						<?php else : ?>
							<div class="masteriyo-feedback-link">
								<?php echo wp_kses_post( $reason['link'] ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="masteriyo-deactivate-feedback-popup-form-footer">
				<a href="<?php echo esc_url( $deactivate_url ); ?>" class="skip"><?php esc_html_e( 'Skip &amp; Deactivate', 'masteriyo' ); ?></a>
				<button class="submit" type="submit"><?php esc_html_e( 'Submit &amp; Deactivate', 'masteriyo' ); ?></button>
			</div>
			<span class="consent">* <?php esc_html_e( 'By submitting this form, you will also be sending us your email address & website URL.', 'masteriyo' ); ?></span>
		</form>
	</div>
</div>
