<?php
/**
 * Compatibility with WP Fastest Cache plugin.
 *
 * @since 1.5.36
 */

namespace Masteriyo\Compatibility\Cache;

use Masteriyo\Abstracts\CachePluginCompatibility;

class WPFastestCache extends CachePluginCompatibility {
	/**
	 * Cache plugin slug.
	 *
	 * @since 1.5.36
	 *
	 * @var string
	 */
	protected $plugin = 'wp-fastest-cache/wpFastestCache.php';

	/**
	 * Do not page.
	 *
	 * @since 1.5.36
	 */
	public function do_not_cache() {
		function_exists( 'wpfc_exclude_current_page' ) && wpfc_exclude_current_page();
	}
}
