<?php
/**
 * Course progress service provider.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Providers
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\Models\UserCourse;
use Masteriyo\Repository\UserCourseRepository;
use Masteriyo\RestApi\Controllers\Version1\UserCourseController;

class UserCourseServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
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
		'user-course',
		'user-course.store',
		'user-course.rest',
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
		$this->getContainer()->add( 'user-course.store', UserCourseRepository::class );

		$this->getContainer()
			->add( 'user-course.rest', UserCourseController::class )
			->addArgument( 'permission' );

		$this->getContainer()
			->add( 'user-course', UserCourse::class )
			->addArgument( 'user-course.store' );
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
		add_filter( 'masteriyo_after_delete_course', array( $this, 'delete_user_courses_after_course_deletion' ), 10, 2 );
	}

	/**
	 * Delete user-course records after a course is deleted.
	 *
	 * @since 1.5.43
	 *
	 * @param integer $course_id The course ID.
	 * @param \Masteriyo\Models\Course $course The deleted course object.
	 */
	public function delete_user_courses_after_course_deletion( $course_id, $course ) {
		global $wpdb;

		/**
		 * Filters boolean: True if user-course records should be deleted after a course is deleted. Otherwise, false.
		 *
		 * @since 1.5.37
		 *
		 * @param boolean $bool True if user-course records should be deleted after a course is deleted. Otherwise, false.
		 * @param integer $course_id The deleted course ID.
		 * @param \Masteriyo\Models\Course $course The deleted course object.
		 */
		$delete = apply_filters( 'masteriyo_is_delete_user_courses_after_course_deletion', true, $course_id, $course );

		if ( $delete ) {
			$wpdb->delete(
				"{$wpdb->prefix}masteriyo_user_items",
				array(
					'item_id' => $course_id,
				)
			);
			$wpdb->query( "DELETE meta FROM {$wpdb->prefix}masteriyo_user_itemmeta meta LEFT JOIN {$wpdb->prefix}masteriyo_user_items user_items ON user_items.id = meta.user_item_id WHERE user_items.id IS NULL;" );
		}
	}
}
