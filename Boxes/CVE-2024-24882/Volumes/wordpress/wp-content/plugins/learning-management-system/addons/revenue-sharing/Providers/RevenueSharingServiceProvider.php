<?php
/**
 * Revenue sharing service provider.
 *
 * @since 1.6.14
 * @package \Masteriyo\Addons\RevenueSharing\Providers
 */

namespace Masteriyo\Addons\RevenueSharing\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Addons\RevenueSharing\RevenueSharingAddon;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Addons\RevenueSharing\Setting;

/**
 * Revenue sharing service provider.
 *
 * @since 1.6.14
 */
class RevenueSharingServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	protected $provides = array(
		'addons.revenue-sharing',
		RevenueSharingAddon::class,
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.6.14
	 */
	public function register() {
		$this->getContainer()->add( 'addons.revenue-sharing', RevenueSharingAddon::class )
			->addArgument( Setting::class );
	}
}
