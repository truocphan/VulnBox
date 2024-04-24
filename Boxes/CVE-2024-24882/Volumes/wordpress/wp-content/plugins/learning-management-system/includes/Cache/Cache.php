<?php
/**
 * Cache helper class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Classes
 */

namespace Masteriyo\Cache;

use Masteriyo\Contracts\Cache as CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Cache helper.
 *
 * @since 1.0.0
 */

class Cache implements CacheInterface {

	/**
	 * Get prefix for use with wp_cache_set. Allows all cache in a group to be invalidated at once.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $group Group of cache to get.
	 *
	 * @return string
	 */
	public function get_prefix( $group ) {
		// Get cache key - uses cache key masteriyo_orders_cache_prefix to invalidate when needed.
		$prefix = wp_cache_get( 'masteriyo_' . $group . '_cache_prefix', $group );

		if ( false === $prefix ) {
			$prefix = microtime();
			wp_cache_set( 'masteriyo_' . $group . '_cache_prefix', $prefix, $group );
		}

		return 'masteriyo_cache_' . $prefix . '_';
	}

	/**
	 * Invalidate cache group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $group Group of cache to clear.
	 */
	public function invalidate_cache_group( $group ) {
		wp_cache_set( 'masteriyo_' . $group . '_cache_prefix', microtime(), $group );
	}

	/**
	 * Retrieves the cache contents from the cache by key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string|array $key   The key under which the cache contents are stored.
	 * @param string $group     Where the cache contents are grouped
	 * @param bool $force       Whether to force an update of the local cache from the persistent cache.
	 * @param bool $found       Whether the key was found in the cache (passed by reference). Disambiguates a return of false, a storable value.
	 *
	 * @return mixed|false The cache contents on success, false on failure to retrieve contents.
	 */
	public function get( $key, $group = '', $force = false, $found = null ) {
		return wp_cache_get( $this->make_cache_key( $key ), $group, $force, $found );
	}

	/**
	 * Saves the data to cache.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string|array $key   The cache key to use for retrieval later.
	 * @param mixed      $data       The contents to store in the cache.
	 * @param string     $group     Where to group the cache contents. Enables the same key to be used across groups.
	 * @param int        $expire       When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool True on success, false on failure.
	 */
	public function set( $key, $data, $group = '', $expire = 0 ) {
		return wp_cache_set( $this->make_cache_key( $key ), $data, $group, $expire );
	}

	/**
	 * Adds data to the cache, if the cache key doesn’t already exist.
	 *
	 * @since 1.0.0
	 * @since 1.4.12 Added default value zero to expire parameter.
	 *
	 * @param int|string|array $key   The cache key to use for retrieval later.
	 * @param mixed $data       The data to add to the cache.
	 * @param string $group     Where to group the cache contents. Enables the same key to be used across groups.
	 * @param int $expire       When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool True on success, false if cache key and group already exist.
	 */
	public function add( $key, $data, $group = '', $expire = 0 ) {
		return wp_cache_add( $this->make_cache_key( $key ), $data, $group, $expire );
	}

	/**
	 * Replaces the contents of the cache with new data.
	 *
	 * @since 1.0.0
	 * @since 1.4.12 Added default value zero to expire parameter.
	 *
	 * @param int|string|array $key   The cache key to use for retrieval later.
	 * @param mixed $data       The new data to store in the cache.
	 * @param string $group     Where to group the cache contents. Enables the same key to be used across groups.
	 * @param int $expire       When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool  False if original value does not exist, true if contents were replaced
	 */
	public function replace( $key, $data, $group = '', $expire = 0 ) {
		return wp_cache_replace( $this->make_cache_key( $key ), $data, $group, $expire );
	}

	/**
	 * Removes the cache contents matching key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string|array $key   What the contents in the cache are called.
	 * @param string $group Where the cache contents are grouped.
	 * @return void
	 */
	public function delete( $key, $group = '' ) {
		return wp_cache_delete( $this->make_cache_key( $key ), $group );
	}

	/**
	 * Removes all cache items.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True on success, false on failure.
	 */
	public function flush() {
		return wp_cache_flush();
	}

	/**
	 * Removes all cache items of a group.
	 *
	 * @since 1.6.16
	 *
	 * @param string $group Where the cache contents are grouped.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function flush_group( $group ) {
		if ( function_exists( 'wp_cache_flush_group' ) ) {
			return wp_cache_flush_group( $group );
		}
		return wp_cache_flush();
	}

	/**
	 * Make a proper and valid cache key.
	 *
	 * @since 1.6.16
	 *
	 * @param int|string|array $key The cache key to use for retrieval later.
	 *
	 * @return int|string
	 */
	public function make_cache_key( $key ) {
		if ( ! is_scalar( $key ) ) {
			$key = maybe_serialize( $key );
		}
		return $key;
	}
}
