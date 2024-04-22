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

class Udemy_Service {

	public $batchPath;
	public $rootUrl;
	public $version;
	public $servicePath;
	public $availableScopes;
	public $resource;
	private $client;

	public function __construct( Udemy_Client $client ) {
		$this->client = $client;
	}

	/**
	 * Return the associated Udemy_Client class.
	 * @return Udemy_Client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Create a new HTTP Batch handler for this service
	 *
	 * @return Udemy_Http_Batch
	 */
	public function createBatch() {
		return new Udemy_Http_Batch(
			$this->client,
			false,
			$this->rootUrl,
			$this->batchPath
		);
	}
}
