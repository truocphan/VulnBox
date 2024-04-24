<?php
/**
 * Course model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Models\Course;
use Masteriyo\Repository\CourseRepository;
use Masteriyo\RestApi\Controllers\Version1\CoursesController;

class CourseServiceProvider extends AbstractServiceProvider {
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
		'course',
		'course.store',
		'course.rest',
		'mto-course',
		'mto-course.store',
		'mto-course.rest',
		'\Masteriyo\RestApi\Controllers\Version1\CoursesController',
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
		$this->getContainer()->add( 'course.store', CourseRepository::class );

		$this->getContainer()->add( 'course.rest', CoursesController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\CoursesController' )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'course', Course::class )
			->addArgument( 'course.store' );

		// Register based on post type.
		$this->getContainer()->add( 'mto-course.store', CourseRepository::class );

		$this->getContainer()->add( 'mto-course.rest', CoursesController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'mto-course', Course::class )
			->addArgument( 'mto-course.store' );

	}
}
