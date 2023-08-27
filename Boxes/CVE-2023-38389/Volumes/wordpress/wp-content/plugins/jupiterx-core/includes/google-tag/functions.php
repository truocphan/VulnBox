<?php
/**
 * The Jupiter Google Tag component.
 *
 * @package JupiterX_Core\Google_Tag
 */

add_action( 'wp_enqueue_scripts', 'jupiterx_google_analytics' );
/**
 * Echo Google Analytics script in header.
 *
 * @since 1.3.0
 *
 * @return void
 */
function jupiterx_google_analytics() {
	$ga_id        = jupiterx_get_option( 'google_analytics_id' );
	$anonymize_ip = jupiterx_get_option( 'google_analytics_anonymization', true );

	if ( empty( $ga_id ) ) {
		return;
	}

	$ga_url = 'https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $ga_id ) . '#asyncload';
	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_script( 'jupiterx-gtag-script', $ga_url, array(), null, false );
	wp_add_inline_script(
		'jupiterx-gtag-script',
		jupiterx_google_analytics_inline_script( $ga_id, $anonymize_ip )
	);
}

/**
 * Get inline script part of Google Analytics script.
 *
 * @since 1.3.0
 *
 * @param string $ga_id Google Analytics Tracking Id.
 * @param string $anonymize_ip IP Anonymization.
 *
 * @return string
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_google_analytics_inline_script( $ga_id, $anonymize_ip ) {
	ob_start();

	// phpcs:disable
	?>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		<?php if ( $anonymize_ip ) : ?>
			gtag('config', '<?php echo esc_attr( $ga_id ); ?>', { 'anonymize_ip': true });
		<?php else : ?>
			gtag('config', '<?php echo esc_attr( $ga_id ); ?>');
		<?php endif; ?>
	</script>
	<?php
	// phpcs:enable

	return str_replace( array( '<script>', '</script>' ), '', ob_get_clean() );
}
