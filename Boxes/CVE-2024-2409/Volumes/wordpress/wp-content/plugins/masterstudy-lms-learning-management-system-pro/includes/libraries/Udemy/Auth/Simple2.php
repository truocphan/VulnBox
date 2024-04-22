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
 * Simple API access implementation. Can either be used to make requests
 * completely unauthenticated, or by using a Simple API Access developer
 * key.
 */
class Udemy_Auth_Simple2 extends Udemy_Auth_Abstract {

	private $client;

	public function __construct( Udemy_Client $client, $config = null ) {
		$this->client = $client;
	}

	/**
	 * Perform an authenticated / signed apiHttpRequest.
	 * This function takes the apiHttpRequest, calls apiAuth->sign on it
	 * (which can modify the request in what ever way fits the auth mechanism)
	 * and then calls apiCurlIO::makeRequest on the signed request
	 *
	 * @param Udemy_Http_Request $request
	 * @return Udemy_Http_Request The resulting HTTP response including the
	 * responseHttpCode, responseHeaders and responseBody.
	 */
	public function authenticatedRequest( Udemy_Http_Request $request ) {
		$request = $this->sign( $request );
		return $this->io->makeRequest( $request );
	}

	public function sign( Udemy_Http_Request $request ) {
		$client_id     = $this->client->getClassConfig( $this, 'client_id' );
		$client_secret = $this->client->getClassConfig( $this, 'client_secret' );
		$request->setRequestHeaders(
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
			array( 'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ) )
		);
		return $request;
	}
}
