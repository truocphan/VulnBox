<?php
/**
 * Course announcement addon.
 *
 * @since 1.6.16
 *
 * @package Masteriyo\Addons\CourseAnnouncement
 */

namespace Masteriyo\Addons\CourseAnnouncement;

use Masteriyo\Addons\CourseAnnouncement\Controllers\CourseAnnouncementController;
use Masteriyo\Addons\CourseAnnouncement\PostType\CourseAnnouncement;
use Masteriyo\PostType\PostType;
use Masteriyo\Pro\Addons;

defined( 'ABSPATH' ) || exit;

/**
 * Course announcement addon class.
 *
 * @since 1.6.16
 */
class CourseAnnouncementAddon {

	/**
	 * Init addon.
	 *
	 * @since 1.6.16
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.6.16
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_rest_api_get_rest_namespaces', array( $this, 'register_rest_namespaces' ) );
		add_filter( 'masteriyo_register_post_types', array( $this, 'register_post_types' ) );
		add_filter( 'masteriyo_admin_submenus', array( $this, 'add_course_announcement_submenu' ) );
	}

	/**
	 * Register namespaces.
	 *
	 * @since 1.6.16
	 *
	 * @param array $namespaces Rest namespaces.
	 * @return array
	 */
	public function register_rest_namespaces( $namespaces ) {
		$namespaces['masteriyo/v1']['course-announcement'] = CourseAnnouncementController::class;
		return $namespaces;
	}

	/**
	 * Register post types.
	 *
	 * @since 1.6.16
	 *
	 * @param array $post_types
	 * @return array
	 */
	public function register_post_types( $post_types ) {
		$post_types['course-announcement'] = CourseAnnouncement::class;
		return $post_types;
	}

	/**
	 * Add course announcement submenu.
	 *
	 * @since 1.6.16
	 *
	 * @param array $submenus Submenus.
	 */
	public function add_course_announcement_submenu( $submenus ) {
		$submenus['course-announcements'] = array(
			'page_title' => __( 'Announcements', 'masteriyo' ),
			'menu_title' => __( 'Announcements', 'masteriyo' ),
			'capability' => 'edit_courses',
			'position'   => 72,
		);

		return $submenus;
	}


}
