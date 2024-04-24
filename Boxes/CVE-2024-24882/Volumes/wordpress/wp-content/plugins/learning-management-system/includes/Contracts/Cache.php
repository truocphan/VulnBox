<?php
/**
 * Cache interface.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Interfaces
 */

namespace Masteriyo\Contracts;

defined( 'ABSPATH' ) || exit;

/**
 * Cache interface.
 *
 * @since 1.0.0
 */

interface Cache {
	/**
	 * Get prefix for use with wp_cache_set. Allows all cache in a group to be invalidated at once.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $group Group of cache to get.
	 *
	 * @return string
	 */
	public function get_prefix( $group );

	/**
	 * Invalidate cache group.
	 *
	 * @param string $group Group of cache to clear.
	 * @since 1.0.0
	 */
	public function invalidate_cache_group( $group );

	/**
	 * Retrieves the cache contents from the cache by key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key   The key under which the cache contents are stored.
	 * @param string $group     Where the cache contents are grouped
	 * @param bool $force       Whether to force an update of the local cache from the persistent cache.
	 * @param bool $found       Whether the key was found in the cache (passed by reference). Disambiguates a return of false, a storable value.
	 *
	 * @return mixed|false The cache contents on success, false on failure to retrieve contents.
	 */
	public function get( $key, $group = '', $force = false, $found = null );

	/**
	 * Saves the data to cache.
	 *
	 * @since 1.0.0
	 * @since 1.4.12 Added default value zero to expire parameter.
	 *
	 * @param int|string $key   The cache key to use for retrieval later.
	 * @param mixed $data       The contents to store in the cache.
	 * @param string $group     Where to group the cache contents. Enables the same key to be used across groups.
	 * @param int $expire       When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool True on success, false on failure.
	 */
	public function set( $key, $data, $group = '', $expire = 0  );

	/**
	 * Adds data to the cache, if the cache key doesn’t already exist.
	 *
	 * @since 1.0.0
	 * @since 1.4.12 Added default value zero to expire parameter.
	 *
	 * @param int|string $key   The cache key to use for retrieval later.
	 * @param mixed $data       The data to add to the cache.
	 * @param string $group     Where to group the cache contents. Enables the same key to be used across groups.
	 * @param int $expire       When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool True on success, false if cache key and group already exist.
	 */
	public function add( $key, $data, $group = '', $expire = 0 );

	/**
	 * Replaces the contents of the cache with new data.
	 *
	 * @since 1.0.0
	 * @since 1.4.12 Added default value zero to expire parameter.
	 *
	 * @param int|string $key   The cache key to use for retrieval later.
	 * @param mixed $data       The new data to store in the cache.
	 * @param string $group     Where to group the cache contents. Enables the same key to be used across groups.
	 * @param int $expire       When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool  False if original value does not exist, true if contents were replaced
	 */
	public function replace( $key, $data, $group = '', $expire = 0 );

	/**
	 * Retrieves the cache contents from the cache by key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key   The key under which the cache contents are stored.
	 * @param string $group     Where the cache contents are grouped
	 *
	 * @return bool True on successful removal, false on failure.
	 */
	public function delete( $key, $group = '' );

	/**
	 * Removes all cache items.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True on success, false on failure.
	 */
	public function flush();

}
