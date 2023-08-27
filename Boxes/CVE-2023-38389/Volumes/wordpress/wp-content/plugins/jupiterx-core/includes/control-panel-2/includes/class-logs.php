<?php
/**
 * The file class that handles logs.
 *
 * @package JupiterX_Core\Control_Panel_2\Logs
 *
 * @since 1.20.0
 */

/**
 * Logs class.
 *
 * @since 1.20.0
 */
class JupiterX_Core_Control_Panel_Logs {
	/**
	 * Class instance.
	 *
	 * @since 1.20.0
	 *
	 * @var JupiterX_Core_Control_Panel_Logs Class instance.
	 */
	private static $instance = null;

	/**
	 * File path.
	 *
	 * @since 1.20.0
	 */
	private $file_path;

	/**
	 * Lines.
	 *
	 * @since 1.20.0
	 */
	private $lines;

	/**
	 * Get a class instance.
	 *
	 * @since 1.20.0
	 *
	 * @return JupiterX_Core_Control_Panel_Logs Class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.20.0
	 */
	public function __construct() {
		$this->file_path = ini_get( 'error_log' );
		$this->lines     = apply_filters( 'jupiterx_cp_tools_logs_lines', 10000 );

		add_action( 'wp_ajax_jupiterx_cp_get_logs', [ $this, 'get_logs' ] );
		add_action( 'wp_ajax_jupiterx_cp_delete_logs', [ $this, 'delete_logs' ] );
	}

	/**
	 * Get logs.
	 *
	 * @since 1.20.0
	 */
	public function get_logs() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! file_exists( $this->file_path ) ) {
			wp_send_json_error( [ esc_html__( 'The log file does not exist yet.', 'jupiterx-core' ) ] );
		}

		if ( empty( filesize( $this->file_path ) ) ) {
			wp_send_json_error( [ esc_html__( 'The log file is empty.', 'jupiterx-core' ), $this->get_info() ] );
		}

		$logs = $this->tail( $this->file_path, $this->lines );

		$logs = $this->formate( $logs );

		wp_send_json_success( [ $logs, $this->get_info() ] );
	}

	/**
	 * Delete logs.
	 *
	 * @since 1.20.0
	 */
	public function delete_logs() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		wp_delete_file( $this->file_path );

		wp_send_json_success( [ esc_html__( 'The log file is cleared.', 'jupiterx-core' ), $this->get_info() ] );
	}

	/**
	 * Get info.
	 *
	 * @since 1.20.0
	 */
	public function get_info() {
		$info = [
			'enabled' => jupiterx_is_debug_log(),
		];

		if ( ! file_exists( $this->file_path ) ) {
			return $info;
		}

		// File path.
		$info['filePath'] = $this->file_path;

		// File size - credit: https://gist.github.com/liunian/9338301
		$info['fileSize'] = $this->get_file_size();

		return $info;
	}

	/**
	 * Get file size.
	 *
	 * @since 1.20.0
	 */
	private function get_file_size() {
		$file_size = @filesize( $this->file_path );

		if ( empty( $file_size ) ) {
			return '0B';
		}

		$i         = floor( log( $file_size, 1024 ) );
		$file_size = round( $file_size / pow( 1024, $i ), [ 0, 0, 2, 2, 3 ][ $i ] ) . [ 'B', 'KB', 'MB', 'GB' ][ $i ];

		return $file_size;
	}

	/**
	 * Tail the log file.
	 * Credit: https://stackoverflow.com/questions/15025875/what-is-the-best-way-in-php-to-read-last-lines-from-a-file/15025877
	 *
	 * @since 1.20.0
	 */
	private function tail( $file_path, $lines = 1, $adaptive = true ) {
		// Open file.
		$f = @fopen( $file_path, 'rb' ); // phpcs:ignore

		if ( false === $f ) {
			return false;
		}

		// Sets buffer size, according to the number of lines to retrieve.
		// This gives a performance boost when reading a few lines from the file.
		if ( ! $adaptive ) {
			$buffer = 4096;
		} else {
			$buffer = ( $lines < 2 ? 64 : ( $lines < 10 ? 512 : 4096 ) );
		}

		// Jump to last character.
		fseek( $f, -1, SEEK_END );

		// Read it and adjust line number if necessary.
		// (Otherwise the result would be wrong if file doesn't end with a blank line).
		if ( fread( $f, 1 ) != "\n" ) $lines -= 1; // phpcs:ignore

		// Start reading.
		$output = '';
		$chunk  = '';

		// While we would like more.
		while ( ftell( $f ) > 0 && $lines >= 0 ) {
			// Figure out how far back we should jump.
			$seek = min( ftell( $f ), $buffer );

			// Do the jump (backwards, relative to where we are).
			fseek( $f, -$seek, SEEK_CUR );

			// Read a chunk and prepend it to our output.
			$output = ( $chunk = fread( $f, $seek ) ) . $output; // phpcs:ignore

			// Jump back to where we started reading.
			fseek( $f, -mb_strlen( $chunk, '8bit' ), SEEK_CUR );

			// Decrease our line counter.
			$lines -= substr_count( $chunk, "\n" );
		}

		// While we have too many lines.
		// (Because of buffer size we might have read too many).
		while ( $lines++ < 0 ) {
			// Find first newline and remove all text before that.
			$output = substr( $output, strpos( $output, "\n" ) + 1 );
		}

		// Close file and return.
		fclose( $f ); // phpcs:ignore
		return trim( $output );
	}

	/**
	 * Formate the logs.
	 * Credit: https://stackoverflow.com/questions/65005004/regex-format-log-message-into-html-codes
	 *
	 * @since 1.20.0
	 */
	private function formate( $logs ) {
		$html = '';

		preg_match_all( '/^(\[[^][]*\hUTC].*)((?:\R(?!\[).*)*)/m', $logs, $matches );

		if ( empty( $matches[1] ) ) {
			return $html;
		}

		foreach ( $matches[1] as $key => $summery ) {
			if ( empty( $matches[2][ $key ] ) ) {
				$html .= '<div> ' . $summery . '</div>';
				continue;
			}

			$html .= '<details>
				<summary>' . $summery . '</summary>
				<pre>' . $matches[2][ $key ] . '</pre>
			</details>';
		}

		return $html;
	}
}

JupiterX_Core_Control_Panel_Logs::get_instance();
