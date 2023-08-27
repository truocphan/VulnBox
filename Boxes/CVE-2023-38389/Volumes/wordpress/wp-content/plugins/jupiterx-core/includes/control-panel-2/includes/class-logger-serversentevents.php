<?php
/**
 * WP Importer Logger Server Sent Event class.
 *
 * For use to save the server log of importing process in Jupiter.
 *
 * @package Jupiter
 * @subpackage Template Import
 * @since 6.0.3
 *
 * @todo Clean up.
 *
 * phpcs:ignoreFile
 * @SuppressWarnings(PHPMD)
 */

/**
 * Store server log while importing process.
 *
 * @since 6.0.3
 *
 * @see https://github.com/humanmade/WordPress-Importer/blob/master/class-logger-serversentevents.php
 *
 * @codingStandardsIgnoreFile
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class JupiterX_Core_Control_Panel_Importer_Logger_ServerSentEvents extends JupiterX_Core_Control_Panel_Importer_Logger {
	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log( $level, $message, array $context = array() ) {
		$data = compact( 'level', 'message' );

		switch ( $level ) {
			case 'emergency':
			case 'alert':
			case 'critical':
			case 'error':
			case 'warning':
			case 'notice':
			case 'info':
				echo "event: log\n";
				echo 'data: ' . wp_json_encode( $data ) . "\n\n";
				flush();
				break;

			case 'debug':
				if ( defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
					echo "event: log\n";
					echo 'data: ' . wp_json_encode( $data ) . "\n\n";
					flush();
					break;
				}
				break;
		}
	}
}
