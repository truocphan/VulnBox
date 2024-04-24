<?php
/**
 * Divi integration service provider.
 *
 * @since 1.6.13
 */

namespace Masteriyo\Addons\DiviIntegration\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Addons\DiviIntegration\DiviIntegrationAddon;

/**
 * Divi integration service provider.
 *
 * @since 1.6.13
 */
class DiviIntegrationServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.13
	 *
	 * @var array
	 */
	protected $provides = array(
		'addons.divi-integration',
		DiviIntegrationAddon::class,
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.6.13
	 */
	public function register() {
		$this->getContainer()->add( 'addons.divi-integration', DiviIntegrationAddon::class, true );
	}
}
