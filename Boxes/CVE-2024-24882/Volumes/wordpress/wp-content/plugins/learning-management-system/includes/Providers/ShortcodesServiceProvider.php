<?php
/**
 * Shortcodes service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\Shortcodes\CartShortcode;
use Masteriyo\Shortcodes\CheckoutShortcode;
use Masteriyo\Shortcodes\AccountShortcode;
use Masteriyo\Shortcodes\CourseCategoriesShortcode;
use Masteriyo\Shortcodes\CoursesShortcode;
use Masteriyo\Shortcodes\InstructorRegistrationShortcode;
use Masteriyo\Shortcodes\InstructorsListShortcode;
use Masteriyo\Shortcodes\RelatedCoursesShortcode;

class ShortcodesServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $provides = array(
		'shortcode.account',
		'shortcode.checkout',
		'shortcode.cart',
		'shortcode.instructor-registration',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->getContainer()->add( 'shortcode.account', AccountShortcode::class );
		$this->getContainer()->add( 'shortcode.checkout', CheckoutShortcode::class );
		$this->getContainer()->add( 'shortcode.cart', CartShortcode::class );
		$this->getContainer()->add( 'shortcode.instructor-registration', CartShortcode::class );
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
				foreach ( $this->get_shortcodes() as $shortcode ) {
					masteriyo( $shortcode )->register();
				}
			},
			0
		);
	}

	/**
	 * Get shortcodes list.
	 *
	 * @since 1.5.41
	 *
	 * @return array
	 */
	protected function get_shortcodes() {
		/**
		 * Filters shortcode classes.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $classes The shortcode classes.
		 */
		return apply_filters(
			'masteriyo_shortcodes',
			array(
				'account'                 => AccountShortcode::class,
				'checkout'                => CheckoutShortcode::class,
				'cart'                    => CartShortcode::class,
				'courses'                 => CoursesShortcode::class,
				'course_categories'       => CourseCategoriesShortcode::class,
				'instructor-registration' => InstructorRegistrationShortcode::class,
				'related_courses'         => RelatedCoursesShortcode::class,
				'instructors_list'        => InstructorsListShortcode::class,
			)
		);
	}
}
