<?php
/*
 * Copyright 2016 Bloter and Media Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if ( ! class_exists( 'Udemy_Client' ) ) {
	require_once dirname( __FILE__ ) . '/../autoload.php';
}

/**
 * File logging class based on the PSR-3 standard.
 *
 * This logger writes to a PHP stream resource.
 */
class Udemy_Logger_File extends Udemy_Logger_Abstract {

	/**
	 * @var string|resource $file Where logs are written
	 */
	private $file;
	/**
	 * @var integer $mode The mode to use if the log file needs to be created
	 */
	private $mode = 0640;
	/**
	 * @var boolean $lock If a lock should be attempted before writing to the log
	 */
	private $lock = false;

	/**
	 * @var integer $trappedErrorNumber Trapped error number
	 */
	private $trappedErrorNumber;
	/**
	 * @var string $trappedErrorString Trapped error string
	 */
	private $trappedErrorString;

	/**
	 * {@inheritdoc}
	 */
	public function __construct( Udemy_Client $client ) {
		parent::__construct( $client );

		$file = $client->getClassConfig( 'Udemy_Logger_File', 'file' );
		if ( ! is_string( $file ) && ! is_resource( $file ) ) {
			throw new Udemy_Logger_Exception(
				'File logger requires a filename or a valid file pointer'
			);
		}

		$mode = $client->getClassConfig( 'Udemy_Logger_File', 'mode' );
		if ( ! $mode ) {
			$this->mode = $mode;
		}

		$this->lock = (bool) $client->getClassConfig( 'Udemy_Logger_File', 'lock' );
		$this->file = $file;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write( $message ) {
		if ( is_string( $this->file ) ) {
			$this->open();
		} elseif ( ! is_resource( $this->file ) ) {
			throw new Udemy_Logger_Exception( 'File pointer is no longer available' );
		}

		if ( $this->lock ) {
			flock( $this->file, LOCK_EX );
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions
		fwrite( $this->file, (string) $message );

		if ( $this->lock ) {
			flock( $this->file, LOCK_UN );
		}
	}

	/**
	 * Opens the log for writing.
	 *
	 * @return resource
	 */
	private function open() {
		// Used for trapping `fopen()` errors.
		$this->trappedErrorNumber = null;
		$this->trappedErrorString = null;

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions
		$old = set_error_handler( array( $this, 'trapError' ) );

		$needsChmod = ! file_exists( $this->file );
		// phpcs:ignore WordPress.WP.AlternativeFunctions
		$fh = fopen( $this->file, 'a' );

		restore_error_handler();

		// Handles trapped `fopen()` errors.
		if ( $this->trappedErrorNumber ) {
			throw new Udemy_Logger_Exception(
				sprintf(
					"Logger Error: '%s'",
					$this->trappedErrorString
				),
				$this->trappedErrorNumber
			);
		}

		if ( $needsChmod ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			@chmod( $this->file, $this->mode & ~umask() );
		}

		// phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		return $this->file = $fh;
	}

	/**
	 * Closes the log stream resource.
	 */
	private function close() {
		if ( is_resource( $this->file ) ) {
			fclose( $this->file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
	}

	/**
	 * Traps `fopen()` errors.
	 *
	 * @param integer $errno The error number
	 * @param string $errstr The error string
	 */
	private function trapError( $errno, $errstr ) {
		$this->trappedErrorNumber = $errno;
		$this->trappedErrorString = $errstr;
	}

	public function __destruct() {
		$this->close();
	}
}
