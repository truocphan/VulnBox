<?php
/**
 * The Jupiter API functions.
 *
 * @package JupiterX\Pro\API
 */

/**
 * Check theme is registered.
 *
 * @since 1.12.O
 *
 * @return boolean Is Registered.
 */
function jupiterx_is_registered() {
	return ! ( empty( jupiterx_get_api_key() ) && empty( jupiterx_get_option( 'envato_purchase_code_5177775', '' ) ) );
}

/**
 * Get API key.
 *
 * @since 1.12.O
 *
 * @return string API key.
 */
function jupiterx_get_api_key() {
	$api_key = jupiterx_get_option( 'api_key' );

	if ( empty( $api_key ) ) {
		return null;
	}

	return $api_key;
}

/**
 * Check theme PRO version.
 *
 * @since 1.12.O
 *
 * @return boolean PRO status.
 */
function jupiterx_is_pro() {
	if ( ! jupiterx_is_callable( 'JupiterX_Pro' ) ) {
		return false;
	}

	if ( ! jupiterx_pro()->is_active() ) {
		return false;
	}

	return ! empty( jupiterx_get_api_key() );
}
