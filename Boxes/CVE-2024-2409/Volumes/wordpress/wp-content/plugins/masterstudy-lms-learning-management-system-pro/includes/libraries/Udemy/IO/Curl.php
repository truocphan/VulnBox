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

/**
 * Curl based implementation of Udemy_IO.
 *
 */

if ( ! class_exists( 'Udemy_Client' ) ) {
	require_once dirname( __FILE__ ) . '/../autoload.php';
}

class Udemy_IO_Curl extends Udemy_IO_Abstract {

	// cURL hex representation of version 7.30.0
	const NO_QUIRK_VERSION = 0x071E00;

	private $options = array();

	/** @var bool $disableProxyWorkaround */
	private $disableProxyWorkaround;

	public function __construct( Udemy_Client $client ) {
		if ( ! extension_loaded( 'curl' ) ) {
			$error = 'The cURL IO handler requires the cURL extension to be enabled';
			$client->getLogger()->critical( $error );
			throw new Udemy_IO_Exception( $error );
		}

		parent::__construct( $client );

		$this->disableProxyWorkaround = $this->client->getClassConfig(
			'Udemy_IO_Curl',
			'disable_proxy_workaround'
		);
	}

	/**
	 * Execute an HTTP Request
	 *
	 * @param Udemy_Http_Request $request the http request to be executed
	 * @return array containing response headers, body, and http code
	 * @throws Udemy_IO_Exception on curl or IO error
	 */
	public function executeRequest( Udemy_Http_Request $request ) {
		$curl = curl_init(); // phpcs:ignore WordPress.WP.AlternativeFunctions

		if ( $request->getPostBody() ) {
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $request->getPostBody() ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}

		$requestHeaders = $request->getRequestHeaders();
		if ( $requestHeaders && is_array( $requestHeaders ) ) {
			$curlHeaders = array();
			foreach ( $requestHeaders as $k => $v ) {
				$curlHeaders[] = "$k: $v";
			}
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $curlHeaders ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		curl_setopt( $curl, CURLOPT_URL, $request->getUrl() ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $request->getRequestMethod() ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		curl_setopt( $curl, CURLOPT_USERAGENT, $request->getUserAgent() ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, false ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		curl_setopt( $curl, CURLOPT_HEADER, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		if ( $request->canGzip() ) {
			curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}

		$options = $this->client->getClassConfig( 'Udemy_IO_Curl', 'options' );
		if ( is_array( $options ) ) {
			$this->setOptions( $options );
		}

		foreach ( $this->options as $key => $var ) {
			curl_setopt( $curl, $key, $var ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}

		if ( ! isset( $this->options[ CURLOPT_CAINFO ] ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions
			curl_setopt( $curl, CURLOPT_CAINFO, dirname( __FILE__ ) . '/cacerts.pem' );
		}

		$this->client->getLogger()->debug(
			'cURL request',
			array(
				'url'     => $request->getUrl(),
				'method'  => $request->getRequestMethod(),
				'headers' => $requestHeaders,
				'body'    => $request->getPostBody(),
			)
		);

		$response = curl_exec( $curl ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		if ( false === $response ) {
			$error = curl_error( $curl ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$code  = curl_errno( $curl ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$map   = $this->client->getClassConfig( 'Udemy_IO_Exception', 'retry_map' );

			$this->client->getLogger()->error( 'cURL ' . $error );
			throw new Udemy_IO_Exception( $error, $code, null, $map );
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions
		$headerSize = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );

		list($responseHeaders, $responseBody) = $this->parseHttpResponse( $response, $headerSize );
		// phpcs:ignore WordPress.WP.AlternativeFunctions
		$responseCode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

		$this->client->getLogger()->debug(
			'cURL response',
			array(
				'code'    => $responseCode,
				'headers' => $responseHeaders,
				'body'    => $responseBody,
			)
		);

		return array( $responseBody, $responseHeaders, $responseCode );
	}

	/**
	 * Set options that update the transport implementation's behavior.
	 * @param $options
	 */
	public function setOptions( $options ) {
		$this->options = $options + $this->options;
	}

	/**
	 * Set the maximum request time in seconds.
	 * @param $timeout in seconds
	 */
	public function setTimeout( $timeout ) {
		// Since this timeout is really for putting a bound on the time
		// we'll set them both to the same. If you need to specify a longer
		// CURLOPT_TIMEOUT, or a higher CONNECTTIMEOUT, the best thing to
		// do is use the setOptions method for the values individually.
		$this->options[ CURLOPT_CONNECTTIMEOUT ] = $timeout;
		$this->options[ CURLOPT_TIMEOUT ]        = $timeout;
	}

	/**
	 * Get the maximum request time in seconds.
	 * @return timeout in seconds
	 */
	public function getTimeout() {
		return $this->options[ CURLOPT_TIMEOUT ];
	}

	/**
	 * Test for the presence of a cURL header processing bug
	 *
	 * {@inheritDoc}
	 *
	 * @return boolean
	 */
	protected function needsQuirk() {
		if ( $this->disableProxyWorkaround ) {
			return false;
		}

		$ver        = curl_version();
		$versionNum = $ver['version_number'];
		return $versionNum < self::NO_QUIRK_VERSION;
	}
}
