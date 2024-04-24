<?php
/**
 * Course Announcement class.
 *
 * @since 1.6.16
 *
 * @package Masteriyo\PostType;
 */

namespace Masteriyo\Addons\CourseAnnouncement\PostType;

use Masteriyo\PostType\PostType;

/**
 * CourseAnnouncement class.
 */
class CourseAnnouncement extends PostType {

	/**
	 * Post slug.
	 *
	 * @since 1.6.16
	 *
	 * @var string
	 */
	protected $slug = PostType::COURSEANNOUNCEMENT;

	/**
	 * Constructor.
	 *
	 * @since 1.6.16
	 */
	public function __construct() {
		$debug = masteriyo_is_post_type_debug_enabled();

		$this->labels = array(
			'name'                  => _x( 'Course Announcement', 'Course Announcement General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Course Announcement', 'Course Announcement Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Course Announcements', 'masteriyo' ),
			'name_admin_bar'        => __( 'Course Announcement', 'masteriyo' ),
			'archives'              => __( 'Course Announcement Archives', 'masteriyo' ),
			'attributes'            => __( 'Course Announcement Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Course Announcement:', 'masteriyo' ),
			'all_items'             => __( 'All Course Announcements', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Course Announcement', 'masteriyo' ),
			'edit_item'             => __( 'Edit Course Announcement', 'masteriyo' ),
			'update_item'           => __( 'Update Course Announcement', 'masteriyo' ),
			'view_item'             => __( 'View Course Announcement', 'masteriyo' ),
			'view_items'            => __( 'View Course Announcements', 'masteriyo' ),
			'search_items'          => __( 'Search Course Announcement', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into Course Announcement', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Course Announcement', 'masteriyo' ),
			'items_list'            => __( 'Course Announcements list', 'masteriyo' ),
			'items_list_navigation' => __( 'Course Announcements list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter course announcements list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Course Announcement', 'masteriyo' ),
			'description'         => __( 'Course Announcement Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'custom-fields' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'menu_position'       => 5,
			'public'              => $debug,
			'show_ui'             => $debug,
			'show_in_menu'        => $debug,
			'show_in_admin_bar'   => $debug,
			'show_in_nav_menus'   => $debug,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'announcement', 'announcements' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => false,
		);
	}
}
