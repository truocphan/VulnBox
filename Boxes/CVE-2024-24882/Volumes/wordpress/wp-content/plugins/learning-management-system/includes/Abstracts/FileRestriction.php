<?php
/**
 * FileRestrictions class.
 *
 * @since 1.0.0
 */

namespace Masteriyo\Abstracts;

use Masteriyo\Constants;
use Masteriyo\Traits\Singleton;

abstract class FileRestriction {
	use Singleton;

	/**
	 * Initialize by instantiating this class.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::instance();
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'run' ) );
	}

	/**
	 * Run this restriction script.
	 *
	 * @since 1.0.0
	 */
	abstract public function run();

	/**
	 * Redirect to a URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url
	 */
	public function redirect( $url ) {
		wp_safe_redirect( $url, 302, 'Masteriyo' );
		exit;
	}

	/**
	 * Send a file as response.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_path
	 */
	public function send_file( $file_path ) {
		$filename    = basename( $file_path );
		$filename    = strstr( $filename, '?' ) ? current( explode( '?', $filename ) ) : $filename;
		$bytes_range = $this->get_requested_bytes_range( @filesize( $file_path ) ); // @codingStandardsIgnoreLine.

		$this->send_headers( $file_path, $bytes_range );

		$start  = isset( $bytes_range['start'] ) ? $bytes_range['start'] : 0;
		$length = isset( $bytes_range['length'] ) ? $bytes_range['length'] : 0;

		if ( ! $this->send_file_in_chunks( $file_path, $start, $length ) ) {
			$this->send_error( __( 'File not found.', 'masteriyo' ) );
		}
		exit;
	}

	/**
	 * Parse the HTTP_RANGE request.
	 * Does not support multi-range requests.
	 *
	 * @since 1.0.0
	 *
	 * @param int $file_size Size of file in bytes.
	 *
	 * @return array {
	 *     Information about bytes range request: beginning and length of
	 *     file chunk, whether the range is valid/supported and whether the request is a range request.
	 *
	 *     @type int  $start            Byte offset of the beginning of the range. Default 0.
	 *     @type int  $length           Length of the requested file chunk in bytes. Optional.
	 *     @type bool $is_range_valid   Whether the requested range is a valid and supported range.
	 *     @type bool $is_range_request Whether the request is a range request.
	 * }
	 */
	protected function get_requested_bytes_range( $file_size ) {
		$start       = 0;
		$bytes_range = array(
			'start'            => $start,
			'is_range_valid'   => false,
			'is_range_request' => false,
		);

		if ( ! $file_size ) {
			return $bytes_range;
		}

		$end                   = $file_size - 1;
		$bytes_range['length'] = $file_size;

		if ( isset( $_SERVER['HTTP_RANGE'] ) ) { // @codingStandardsIgnoreLine.
			$http_range                      = sanitize_text_field( wp_unslash( $_SERVER['HTTP_RANGE'] ) ); // WPCS: input var ok.
			$bytes_range['is_range_request'] = true;

			$c_start = $start;
			$c_end   = $end;

			// Extract the range string.
			list( , $range ) = explode( '=', $http_range, 2 );

			// Make sure the client hasn't sent us a multibyte range.
			if ( strpos( $range, ',' ) !== false ) {
				return $bytes_range;
			}

			/*
			 * If the range starts with an '-' we start from the beginning.
			 * If not, we forward the file pointer
			 * and make sure to get the end byte if specified.
			 */
			if ( '-' === $range[0] ) {
				// The n-number of the last bytes is requested.
				$c_start = $file_size - substr( $range, 1 );
			} else {
				$range   = explode( '-', $range );
				$c_start = ( isset( $range[0] ) && is_numeric( $range[0] ) ) ? (int) $range[0] : 0;
				$c_end   = ( isset( $range[1] ) && is_numeric( $range[1] ) ) ? (int) $range[1] : $file_size;
			}

			/*
			 * Check the range and make sure it's treated according to the specs: http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html.
			 * End bytes can not be larger than $end.
			 */
			$c_end = ( $c_end > $end ) ? $end : $c_end;
			// Validate the requested range and return an error if it's not correct.
			if ( $c_start > $c_end || $c_start > $file_size - 1 || $c_end >= $file_size ) {
				return $bytes_range;
			}
			$start  = $c_start;
			$end    = $c_end;
			$length = $end - $start + 1;

			$bytes_range['start']          = $start;
			$bytes_range['length']         = $length;
			$bytes_range['is_range_valid'] = true;
		}
		return $bytes_range;
	}

	/**
	 * Get content type of the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_path File path.
	 *
	 * @return string
	 */
	protected function get_content_type( $file_path ) {
		$file_extension = strtolower( substr( strrchr( $file_path, '.' ), 1 ) );
		$ctype          = 'application/force-download';

		/**
		 * Filters the list of allowed mime types and file extensions.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $mime_types List of mime types.
		 */
		$allowed_mime_types = apply_filters( 'masteriyo_allowed_mime_types', get_allowed_mime_types() );

		foreach ( $allowed_mime_types as $mime => $type ) {
			$mimes = explode( '|', $mime );
			if ( in_array( $file_extension, $mimes, true ) ) {
				$ctype = $type;
				break;
			}
		}

		return $ctype;
	}

	/**
	 * Send headers for the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_path   File path.
	 * @param array  $bytes_range Array containing info about bytes range request (see {@see get_requested_bytes_range} for structure).
	 */
	protected function send_headers( $file_path, $bytes_range = array() ) {
		$this->check_server_config();
		$this->clean_buffers();
		masteriyo_nocache_headers();

		header( 'X-Robots-Tag: noindex, nofollow', true );
		header( 'Content-Type: ' . $this->get_content_type( $file_path ) );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Transfer-Encoding: binary' );

		$file_size = @filesize( $file_path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( ! $file_size ) {
			return;
		}

		if ( isset( $bytes_range['is_range_request'] ) && true === $bytes_range['is_range_request'] ) {
			if ( false === $bytes_range['is_range_valid'] ) {
				header( 'HTTP/1.1 416 Requested Range Not Satisfiable' );
				header( 'Content-Range: bytes 0-' . ( $file_size - 1 ) . '/' . $file_size );
				exit;
			}

			$start  = $bytes_range['start'];
			$end    = $bytes_range['start'] + $bytes_range['length'] - 1;
			$length = $bytes_range['length'];

			header( 'HTTP/1.1 206 Partial Content' );
			header( "Accept-Ranges: 0-$file_size" );
			header( "Content-Range: bytes $start-$end/$file_size" );
			header( "Content-Length: $length" );
		} else {
			header( 'Content-Length: ' . $file_size );
		}
	}

	/**
	 * Check and set certain server config variables to ensure file transfer work as intended.
	 *
	 * @since 1.0.0
	 */
	protected function check_server_config() {
		masteriyo_set_time_limit( 0 );
		if ( function_exists( 'apache_setenv' ) ) {
			@apache_setenv( 'no-gzip', 1 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_apache_setenv
		}
		@ini_set( 'zlib.output_compression', 'Off' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.PHP.IniSet.Risky
		@session_write_close(); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.VIP.SessionFunctionsUsage.session_session_write_close
	}

	/**
	 * Clean all output buffers.
	 *
	 * Can prevent errors, for example: transfer closed with 3 bytes remaining to read.
	 *
	 * @since 1.0.0
	 */
	protected function clean_buffers() {
		if ( ob_get_level() ) {
			$levels = ob_get_level();
			for ( $i = 0; $i < $levels; $i++ ) {
				@ob_end_clean(); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			}
		} else {
			@ob_end_clean(); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
	}

	/**
	 * Send file in chunks.
	 *
	 * Sends file in chunks so big files are possible to be transferred without changing PHP.INI
	 * - https://forum.codeigniter.com/thread-38732.html
	 * - http://codeigniter.com/wiki/Download_helper_for_large_files/
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_path  File.
	 * @param integer $start     Byte offset/position of the beginning from which to read from the file.
	 * @param integer $length    Length of the chunk to be read from the file in bytes, 0 means full file.
	 *
	 * @return boolean Success or failure.
	 */
	public function send_file_in_chunks( $file_path, $start = 0, $length = 0 ) {
		if ( ! Constants::get( 'MASTERIYO_CHUNK_SIZE' ) || (int) Constants::get( 'MASTERIYO_CHUNK_SIZE' ) <= 0 ) {
			Constants::set( 'MASTERIYO_CHUNK_SIZE', 1024 * 1024 );
		}
		$handle = @fopen( $file_path, 'r' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen

		if ( false === $handle ) {
			return false;
		}

		if ( ! $length ) {
			$length = @filesize( $file_path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}

		$read_length = (int) Constants::get( 'MASTERIYO_CHUNK_SIZE' );

		if ( $length ) {
			$end = $start + $length - 1;

			@fseek( $handle, $start ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			$p = @ftell( $handle ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

			while ( ! @feof( $handle ) && $p <= $end ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				// Don't run past the end of file.
				if ( $p + $read_length > $end ) {
					$read_length = $end - $p + 1;
				}

				echo @fread( $handle, $read_length ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.XSS.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.file_system_read_fread
				$p = @ftell( $handle ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

				if ( ob_get_length() ) {
					ob_flush();
					flush();
				}
			}
		} else {
			while ( ! @feof( $handle ) ) { // @codingStandardsIgnoreLine.
				echo @fread( $handle, $read_length ); // @codingStandardsIgnoreLine.
				if ( ob_get_length() ) {
					ob_flush();
					flush();
				}
			}
		}

		return @fclose( $handle ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fclose
	}

	/**
	 * Die with an error message.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $message Error message.
	 * @param string  $title   Error title.
	 * @param integer $status  Error status.
	 */
	protected function send_error( $message, $title = '', $status = 404 ) {
		wp_die( $message, $title, array( 'response' => $status ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
