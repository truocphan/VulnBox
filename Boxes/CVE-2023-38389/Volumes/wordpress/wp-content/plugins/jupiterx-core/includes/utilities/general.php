<?php
/**
 * JupiterX_Core General Utilities
 *
 * @package JupiterX_Core\Utilities
 *
 * @since 1.20.0
 */

if ( ! function_exists( 'jupiterx_log' ) ) {
	/**
	 * Add log in WordPress default debug file.
	 *
	 * @since 1.20.0
	 *
	 * @param string $message The log message.
	 * @param array $data The log data.
	 *
	 * @return void
	 */
	function jupiterx_log( $message, $data = null ) {
		if ( ! jupiterx_is_debug_log() || empty( $message ) || ! is_string( $message ) ) {
			return;
		}

		// Check JUPITERX_LOG.
		if ( ! defined( 'JUPITERX_LOG' ) || empty( JUPITERX_LOG ) ) {
			return false;
		}

		// Add message.
		$log = '[Jupiter X] ' . $message;

		// phpcs:disable
		// Add data.
		if ( ! empty( $data ) ) {
			$log .= "\n" . print_r( $data, true );
		}

		// Add stack trace.
		$backtrace = debug_backtrace();

		if ( ! empty( $backtrace ) ) {
			$backtrace = reset( $backtrace );
		}

		if ( ! empty( $backtrace['file'] ) || ! empty( $backtrace['line'] ) ) {
			$log .= "\nStack trace:\n#0 {$backtrace['file']}({$backtrace['line']})";
		}

		// Log.
		error_log( $log );
		// phpcs:enable
	}
}

if ( ! function_exists( 'jupiterx_is_debug_log' ) ) {
	/**
	 * Check if debug log is enabled.
	 *
	 * @since 1.20.0
	 *
	 * @return boolean
	 */
	function jupiterx_is_debug_log() {
		// Check WP_DEBUG.
		if ( defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
			return false;
		}

		// Check WP_DEBUG_LOG.
		if ( defined( 'WP_DEBUG_LOG' ) && false === WP_DEBUG_LOG ) {
			return false;
		}

		return true;
	}
}
