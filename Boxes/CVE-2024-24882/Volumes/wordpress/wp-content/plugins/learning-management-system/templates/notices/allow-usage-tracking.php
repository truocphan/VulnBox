<?php
/**
 * Admin notice to ask for usage tracking.
 *
 * @since 1.6.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="masteriyo-notice masteriyo-allow-usage-notice updated">
	<div class="masteriyo-notice-main-content">
		<div class="masteriyo-logo">
			<?php masteriyo_get_svg( 'logo', true ); ?>
		</div>
		<div class="masteriyo-notice-main-content-wrapper">
			<p class="masteriyo-notice__title">
				<?php esc_html_e( 'Contribute to the enhancement', 'masteriyo' ); ?>
			</p>
			<div class="masteriyo-notice__description">
				<?php
				printf(
					wp_kses_post( 'Help us improve the plugin\'s features by sharing %s non-sensitive plugin data %s with us. - %s Team', 'masteriyo' ),
					'<a href="https://docs.masteriyo.com/getting-started/allow-usage-tracking" target="_blank">',
					'</a>',
					'Masteriyo'
				);
				?>
			</div>
		</div>
		<div class="masteriyo-x-icon-container">
			<?php masteriyo_get_svg( 'x', true ); ?>
		</div>
	</div>
	<div class="masteriyo-notice__actions submit">
		<button class="button button-primary masteriyo-allow-usage-tracking">
			<?php esc_html_e( 'Allow', 'masteriyo' ); ?>
		</button>
		<button class="button button-secondary masteriyo-deny-usage-tracking">
			<?php esc_html_e( 'No, Thanks', 'masteriyo' ); ?>
		</button>
	</div>
</div>
