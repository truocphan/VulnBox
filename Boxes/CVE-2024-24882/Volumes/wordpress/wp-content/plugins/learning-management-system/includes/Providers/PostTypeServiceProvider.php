<?php
/**
 * Post Type service provider.
 *
 * @since 1.5.41
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\PostType\Quiz;
use Masteriyo\PostType\Order;
use Masteriyo\PostType\Course;
use Masteriyo\PostType\Lesson;
use Masteriyo\PostType\Section;
use Masteriyo\PostType\Question;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\PostType\Webhook;

/**
 * Post type service provider.
 *
 * @since 1.5.41
 */
class PostTypeServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {

	/**
	 * Post types.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $post_types = array(
		'course'   => Course::class,
		'lesson'   => Lesson::class,
		'section'  => Section::class,
		'quiz'     => Quiz::class,
		'question' => Question::class,
		'order'    => Order::class,
		'webhook'  => Webhook::class,
	);

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
				 * Fires before registering post-types.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_register_post_type' );

				/**
				 * Filters post type classes.
				 *
				 * @since 1.0.0
				 *
				 * @param string[] $post_types Post type classes.
				 */
				$post_types = apply_filters( 'masteriyo_register_post_types', $this->post_types );
				foreach ( $post_types as $post_type => $class ) {
					$post_type = new $class();
					$post_type->register();
				}

				/**
				 * Fires after registering post-types.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_after_register_post_type' );
			},
			0
		);
	}
}
