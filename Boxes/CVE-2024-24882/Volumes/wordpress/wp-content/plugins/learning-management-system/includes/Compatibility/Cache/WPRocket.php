<?php
/**
 * Compatibility with WP Rocket plugin.
 *
 * @since 1.5.36
 */

namespace Masteriyo\Compatibility\Cache;

use Masteriyo\Abstracts\CachePluginCompatibility;

class WPRocket extends CachePluginCompatibility {
	/**
	 * Cache plugin slug.
	 *
	 * @since 1.5.36
	 *
	 * @var string
	 */
	protected $plugin = 'wp-rocket/wp-rocket.php';

	/**
	 * Do not page.
	 *
	 * @since 1.5.36
	 */
	public function do_not_cache() {
		masteriyo_maybe_define_constant( 'DONOTCACHEPAGE', true );
	}
}
