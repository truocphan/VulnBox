<?php
/**
 * Course progress item service provider.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Providers
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Models\CourseProgressItem;
use Masteriyo\Repository\CourseProgressItemRepository;
use Masteriyo\RestApi\Controllers\Version1\CourseProgressItemsController;

class CourseProgressItemServiceProvider extends AbstractServiceProvider {
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
		'course-progress-item',
		'course-progress-item.store',
		'course-progress-item.rest',
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
		$this->getContainer()->add( 'course-progress-item.store', CourseProgressItemRepository::class );

		$this->getContainer()
			->add( 'course-progress-item.rest', CourseProgressItemsController::class )
			->addArgument( 'permission' );

		$this->getContainer()
			->add( 'course-progress-item', CourseProgressItem::class )
			->addArgument( 'course-progress-item.store' );
	}
}
