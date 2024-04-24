<?php
/**
 * Compatibility with W3 Total Cache plugin.
 *
 * @since 1.5.36
 */

namespace Masteriyo\Compatibility\Cache;

use Masteriyo\Abstracts\CachePluginCompatibility;

class W3TotalCache extends CachePluginCompatibility {
	/**
	 * Cache plugin slug.
	 *
	 * @since 1.5.36
	 *
	 * @var string
	 */
	protected $plugin = 'w3-total-cache/w3-total-cache.php';

	/**
	 * Do not page.
	 *
	 * @since 1.5.36
	 */
	public function do_not_cache() {
		masteriyo_maybe_define_constant( 'DONOTCACHEPAGE' ); // Don't add value to this constant.
	}
}
