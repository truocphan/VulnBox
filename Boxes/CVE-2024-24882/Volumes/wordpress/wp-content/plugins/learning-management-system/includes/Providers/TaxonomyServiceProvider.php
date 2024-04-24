<?php
/**
 * Taxonomy service provider.
 *
 * @since 1.5.41
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\Taxonomy\Course\Category;
use Masteriyo\Taxonomy\Course\Difficulty;
use Masteriyo\Taxonomy\Course\Tag;
use Masteriyo\Taxonomy\Course\Visibility;

/**
 * Post type service provider.
 *
 * @since 1.5.41
 */
class TaxonomyServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {

	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.5.41
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
	 * @since 1.5.41
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
	 * @since 1.5.41
	 */
	public function boot() {
		add_action(
			'init',
			function() {
				if ( ! is_blog_installed() ) {
					return;
				}

				/**
				 * Fires before registering taxonomies.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_register_taxonomy' );

				( new Category() )->register();
				( new Tag() )->register();
				( new Difficulty() )->register();
				( new Visibility() )->register();

				/**
				 * Fires after registering taxonomies.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_after_register_taxonomy' );
			},
			0
		);
	}
}
