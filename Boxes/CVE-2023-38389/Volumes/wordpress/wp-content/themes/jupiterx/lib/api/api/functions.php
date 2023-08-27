<?php
/**
 * API utility functions.
 *
 * @package JupiterX\Framework\API\API
 *
 * @since 1.3.0
 */

/**
 * Print PRO badge.
 *
 * @since 1.3.0
 */
function jupiterx_pro_badge() {
	echo jupiterx_get_pro_badge(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get PRO badge.
 *
 * @since 1.3.0
 *
 * @return string The pro badge.
 */
function jupiterx_get_pro_badge() {
	if ( jupiterx_is_pro() ) {
		return '';
	}

	return sprintf(
		'<img class="jupiterx-pro-badge" src="%1$s" alt="%2$s" />',
		esc_url( jupiterx_get_pro_badge_url() ),
		esc_html__( 'Jupiter X Pro', 'jupiterx' )
	);
}

/**
 * Get PRO badge URL.
 *
 * @since 1.3.0
 *
 * @return string The pro badge URL.
 */
function jupiterx_get_pro_badge_url() {
	$icon = 'pro-badge.svg';

	if ( jupiterx_is_premium() ) {
		$icon = 'lock-badge.svg';
	}

	return esc_url( JUPITERX_ADMIN_ASSETS_URL . '/images/' . $icon );
}

/**
 * Check theme is premium.
 *
 * @since 1.3.0
 * @return boolean Is Premium.
 */
function jupiterx_is_premium() {
	return JUPITERX_PREMIUM;
}

if ( ! function_exists( 'jupiterx_is_registered' ) ) {
	/**
	 * Check theme is registered.
	 *
	 * @since 1.3.0
	 * @return boolean Is Registered.
	 */
	function jupiterx_is_registered() {
		return false;
	}
}

if ( ! function_exists( 'jupiterx_get_api_key' ) ) {
	/**
	 * Get API key.
	 *
	 * @since 1.3.0
	 *
	 * @return string API key.
	 */
	function jupiterx_get_api_key() {
		return null;
	}
}

if ( ! function_exists( 'jupiterx_is_pro' ) ) {
	/**
	 * Check theme PRO version.
	 *
	 * @since 1.3.0
	 *
	 * @return boolean PRO status.
	 */
	function jupiterx_is_pro() {
		return false;
	}
}

/**
 * Suppress the phpmd
 *
 * @SuppressWarnings(PHPMD)
 */

if ( function_exists( 'jupiterx_core' ) && ! function_exists( 'jupiterx_get_update_plugins' ) ) {
	/**
	 * Added for compatibility reasons and prevent fatal errors on plugins page.
	 *
	 * @since 1.14.0
	 *
	 * @param boolean $jupiterx_plugins Filter only Jupiter X plugins.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_update_plugins( $jupiterx_plugins = true ) {
		return [];
	}
}

/**
 * Suppress the phpmd
 *
 * @SuppressWarnings(PHPMD)
 */

if ( function_exists( 'jupiterx_core' ) && ! function_exists( 'jupiterx_get_plugin_conflicts' ) ) {
	/**
	 * Added for compatibility reasons and prevent fatal errors on plugins page.
	 *
	 * @param array $plugin_data Plugin to check for conflicts.
	 * @param array $plugins List of plugins.
	 *
	 * @since 1.14.0
	 *
	 * @return array
	 */
	function jupiterx_get_plugin_conflicts( $plugin_data, $plugins ) {
		return [
			'themes'  => [],
			'plugins' => [],
		];
	}
}
