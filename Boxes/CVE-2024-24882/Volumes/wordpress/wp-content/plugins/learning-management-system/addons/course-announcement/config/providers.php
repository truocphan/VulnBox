<?php
/**
 * Course announcement config.
 *
 * @since 1.6.16
 */

use Masteriyo\Addons\CourseAnnouncement\Providers\CourseAnnouncementServiceProvider;

/**
 * Masteriyo Course announcement service providers.
 *
 * @since 1.6.16
 */
return array_unique(
	array(
		CourseAnnouncementServiceProvider::class,
	)
);
