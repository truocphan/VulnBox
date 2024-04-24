<?php
/**
 * Compatibility with WP Optimize plugin.
 *
 * @since 1.5.36
 */

namespace Masteriyo\Compatibility\Cache;

use Masteriyo\Abstracts\CachePluginCompatibility;

class WPOptimize extends CachePluginCompatibility {
	/**
	 * Cache plugin slug.
	 *
	 * @since 1.5.36
	 *
	 * @var string
	 */
	protected $plugin = 'wp-optimize/wp-optimize.php';

	/**
	 * Do not page.
	 *
	 * @since 1.5.36
	 */
	public function do_not_cache() {
		masteriyo_maybe_define_constant( 'DONOTCACHEPAGE', 1 );
	}
}
