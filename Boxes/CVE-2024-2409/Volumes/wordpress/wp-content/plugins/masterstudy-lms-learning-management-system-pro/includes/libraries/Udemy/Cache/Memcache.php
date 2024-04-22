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
 * A persistent storage class based on the memcache, which is not
 * really very persistent, as soon as you restart your memcache daemon
 * the storage will be wiped.
 *
 * Will use either the memcache or memcached extensions, preferring
 * memcached.
 */
class Udemy_Cache_Memcache extends Udemy_Cache_Abstract {

	private $connection = false;
	private $mc         = false;
	private $host;
	private $port;

	/**
	 * @var Udemy_Client the current client
	 */
	private $client;

	public function __construct( Udemy_Client $client ) {
		if ( ! function_exists( 'memcache_connect' ) && ! class_exists( 'Memcached' ) ) {
			$error = 'Memcache functions not available';

			$client->getLogger()->error( $error );
			throw new Udemy_Cache_Exception( $error );
		}

		$this->client = $client;

		if ( $client->isAppEngine() ) {
			// No credentials needed for GAE.
			$this->mc         = new Memcached();
			$this->connection = true;
		} else {
			$this->host = $client->getClassConfig( $this, 'host' );
			$this->port = $client->getClassConfig( $this, 'port' );
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			if ( empty( $this->host ) || ( empty( $this->port ) && (string) '0' != $this->port ) ) {
				$error = 'You need to supply a valid memcache host and port';

				$client->getLogger()->error( $error );
				throw new Udemy_Cache_Exception( $error );
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function get( $key, $expiration = false ) {
		$this->connect();
		$ret = false;
		if ( $this->mc ) {
			$ret = $this->mc->get( $key );
		} else {
			$ret = memcache_get( $this->connection, $key );
		}
		if ( false === $ret ) {
			$this->client->getLogger()->debug(
				'Memcache cache miss',
				array( 'key' => $key )
			);
			return false;
		}
		if ( is_numeric( $expiration ) && ( time() - $ret['time'] > $expiration ) ) {
			$this->client->getLogger()->debug(
				'Memcache cache miss (expired)',
				array(
					'key' => $key,
					'var' => $ret,
				)
			);
			$this->delete( $key );
			return false;
		}

		$this->client->getLogger()->debug(
			'Memcache cache hit',
			array(
				'key' => $key,
				'var' => $ret,
			)
		);

		return $ret['data'];
	}

	/**
	 * @inheritDoc
	 * @param string $key
	 * @param string $value
	 * @throws Udemy_Cache_Exception
	 */
	public function set( $key, $value ) {
		$this->connect();
		// we store it with the cache_time default expiration so objects will at
		// least get cleaned eventually.
		$data = array(
			'time' => time(),
			'data' => $value,
		);
		$rc   = false;
		if ( $this->mc ) {
			$rc = $this->mc->set( $key, $data );
		} else {
			$rc = memcache_set( $this->connection, $key, $data, false );
		}
		if ( false == $rc ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$this->client->getLogger()->error(
				'Memcache cache set failed',
				array(
					'key' => $key,
					'var' => $data,
				)
			);

			throw new Udemy_Cache_Exception( "Couldn't store data in cache" );
		}

		$this->client->getLogger()->debug(
			'Memcache cache set',
			array(
				'key' => $key,
				'var' => $data,
			)
		);
	}

	/**
	 * @inheritDoc
	 * @param String $key
	 */
	public function delete( $key ) {
		$this->connect();
		if ( $this->mc ) {
			$this->mc->delete( $key, 0 );
		} else {
			memcache_delete( $this->connection, $key, 0 );
		}

		$this->client->getLogger()->debug(
			'Memcache cache delete',
			array( 'key' => $key )
		);
	}

	/**
	 * Lazy initialiser for memcache connection. Uses pconnect for to take
	 * advantage of the persistence pool where possible.
	 */
	private function connect() {
		if ( $this->connection ) {
			return;
		}

		if ( class_exists( 'Memcached' ) ) {
			$this->mc = new Memcached();
			$this->mc->addServer( $this->host, $this->port );
			$this->connection = true;
		} else {
			$this->connection = memcache_pconnect( $this->host, $this->port );
		}

		if ( ! $this->connection ) {
			$error = "Couldn't connect to memcache server";

			$this->client->getLogger()->error( $error );
			throw new Udemy_Cache_Exception( $error );
		}
	}
}
