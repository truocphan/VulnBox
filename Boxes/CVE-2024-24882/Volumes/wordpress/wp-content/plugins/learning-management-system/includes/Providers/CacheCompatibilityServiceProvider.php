<?php
/**
 * Cache plugin compatibility service provider.
 *
 * @since 1.5.43
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Compatibility\Cache\WPRocket;
use Masteriyo\Compatibility\Cache\LiteSpeed;
use Masteriyo\Compatibility\Cache\WPOptimize;
use Masteriyo\Compatibility\Cache\HummingBird;
use Masteriyo\Compatibility\Cache\W3TotalCache;
use Masteriyo\Compatibility\Cache\WPSuperCache;
use Masteriyo\Abstracts\CachePluginCompatibility;
use Masteriyo\Compatibility\Cache\WPFastestCache;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Cache plugin compatibility service provider.
 *
 * @since 1.5.43
 */
class CacheCompatibilityServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {

	/**
	 * Cache plugin compatibilities class.
	 *
	 * @since 1.5.43
	 *
	 * @var array
	 */
	private $cache_plugins = array(
		W3TotalCache::class,
		WPFastestCache::class,
		WPSuperCache::class,
		WPOptimize::class,
		HummingBird::class,
		LiteSpeed::class,
		WPRocket::class,
	);

	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.5.43
	 *
	 * @var array
	 */
	protected $provides = array();

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.5.43
	 */
	public function register() {
	}

	/**
	 * In much the same way, this method has access to the container
	 * itself and can interact with it however you wish, the difference
	 * is that the boot method is invoked as soon as you register
	 * the service provider with the container meaning that everything
	 * in this method is eagerly loaded.
	 *
	 * If you wish to apply inflectors or register further service providers
	 * from this one, it must be from a bootable service provider like
	 * this one, otherwise they will be ignored.
	 *
	 * @since 1.5.43
	 */
	public function boot() {
		if ( ! is_blog_installed() ) {
			return;
		}

		/**
		 * Fires before registering cache plugin compatibilities.
		 *
		 * @since 1.5.36
		 */
		do_action( 'masteriyo_register_cache_plugin_compatibilities' );

		/**
		 * Filters cache plugins classes.
		 *
		 * @since 1.5.36
		 *
		 * @param string[] $cache_plugins Cache plugins classes.
		 */
		$cache_plugins = apply_filters( 'masteriyo_register_cache_plugin_compatibilities', $this->cache_plugins );

		$cache_plugins = array_filter(
			$cache_plugins,
			function ( $plugin ) {
				return class_exists( $plugin );
			}
		);

		$cache_plugin_objects = array_map(
			function( $class ) {
				return new $class();
			},
			$cache_plugins
		);

		$cache_plugin_objects = array_filter(
			$cache_plugin_objects,
			function ( $object ) {
				return $object instanceof CachePluginCompatibility;
			}
		);

		foreach ( $cache_plugin_objects as $object ) {
			$object->init();
		}

		/**
		 * Fires after registering cache plugin compatibilities.
		 *
		 * @since 1.5.36
		 */
		do_action( 'masteriyo_after_register_cache_plugin_compatibilities' );
	}
}
