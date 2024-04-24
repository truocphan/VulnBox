<?php
/**
 * Course Announcement service provider.
 *
 * @since 1.6.16
 * @package \Masteriyo\Addons\CourseAnnouncement\Providers
 */

namespace Masteriyo\Addons\CourseAnnouncement\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Addons\CourseAnnouncement\CourseAnnouncementAddon;
use Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement;
use Masteriyo\Addons\CourseAnnouncement\Repository\CourseAnnouncementRepository;
use Masteriyo\Addons\CourseAnnouncement\Controllers\CourseAnnouncementController;

/**
 * Course Announcement service provider.
 *
 * @since 1.6.16
 */
class CourseAnnouncementServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.16
	 *
	 * @var array
	 */
	protected $provides = array(
		'course-announcement',
		'course-announcement.store',
		'course-announcement.rest',
		'mto-course-announcement',
		'mto-course-announcement.store',
		'mto-course-announcement.rest',
		'addons.course-announcement',
		CourseAnnouncementAddon::class,
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.6.16
	 */
	public function register() {
		$this->getContainer()->add( 'course-announcement.store', CourseAnnouncementRepository::class );

		$this->getContainer()->add( 'course-announcement.rest', CourseAnnouncementController::class )
			->addArgument( 'permission' );
		$this->getContainer()->add( 'course-announcement', CourseAnnouncement::class )
			->addArgument( 'course-announcement.store' );

		// Register based on post type.
		$this->getContainer()->add( 'mto-course-announcement.store', CourseAnnouncementRepository::class );

		$this->getContainer()->add( 'mto-course-announcement.rest', CourseAnnouncementController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'mto-course-announcement', CourseAnnouncement::class )
			->addArgument( 'mto-course-announcement.store' );

		$this->getContainer()->add( 'addons.course-announcement', CourseAnnouncementAddon::class, true );
	}
}
