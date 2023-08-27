<?php
/**
 * JupiterX_Core Utilities is a set of tools to ease building applications.
 *
 * @package JupiterX_Core\Utilities
 *
 * @since 1.18.0
 */

if ( ! function_exists( 'jupiterx_core_get_option' ) ) {
	/**
	 * Get option from options storage. This is duplicate of jupiterx_get_option.
	 *
	 * @since 1.18.0
	 *
	 * @param string  $option  Option name.
	 * @param boolean $default Default value.
	 *
	 * @return mixed Value set for the option.
	 */
	function jupiterx_core_get_option( $option, $default = false ) {
		$options = get_option( 'jupiterx', [] );

		if ( ! isset( $options[ $option ] ) ) {
			return $default;
		}

		return $options[ $option ];
	}
}

if ( ! function_exists( 'jupiterx_core_update_option' ) ) {
	/**
	 * Update option from options storage. This is duplicate of jupiterx_update_option.
	 *
	 * @param string $option Option name.
	 * @param mixed  $value  Update value.
	 *
	 * @return boolean False if value was not updated and true if value was updated.
	 */
	function jupiterx_core_update_option( $option, $value ) {
		$options = get_option( 'jupiterx', [] );

		// No need to update the same value.
		if ( isset( $options[ $option ] ) && $value === $options[ $option ] ) {
			return false;
		}

		// Update the option.
		$options[ $option ] = $value;
		update_option( 'jupiterx', $options );

		return true;
	}
}

if ( ! function_exists( 'jupiterx_core_is_registered' ) ) {
	/**
	 * Check Jupiter X is registered. This is duplicate of jupiterx_is_registered.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean
	 */
	function jupiterx_core_is_registered() {
		return ! ( empty( jupiterx_core_get_api_key() ) && empty( jupiterx_core_get_option( 'envato_purchase_code_5177775', '' ) ) );
	}
}

if ( ! function_exists( 'jupiterx_core_get_api_key' ) ) {
	/**
	 * Get Jupiter X API key. This is duplicate of jupiterx_get_api_key.
	 *
	 * @since 1.18.0
	 *
	 * @return mixed
	 */
	function jupiterx_core_get_api_key() {
		$api_key = jupiterx_core_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			return null;
		}

		return $api_key;
	}
}
