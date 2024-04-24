<?php
/**
 * Lessons class.
 */

namespace Masteriyo\PostType;

class Lesson extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'mto-lesson';

	public function __construct() {
		$debug      = masteriyo_is_post_type_debug_enabled();
		$permalinks = masteriyo_get_permalink_structure();

		$this->labels = array(
			'name'                  => _x( 'Lessons', 'Lesson General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Lesson', 'Lesson Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Lessons', 'masteriyo' ),
			'name_admin_bar'        => __( 'Lesson', 'masteriyo' ),
			'archives'              => __( 'Lesson Archives', 'masteriyo' ),
			'attributes'            => __( 'Lesson Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Lesson:', 'masteriyo' ),
			'all_items'             => __( 'All Lessons', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Lesson', 'masteriyo' ),
			'edit_item'             => __( 'Edit Lesson', 'masteriyo' ),
			'update_item'           => __( 'Update Lesson', 'masteriyo' ),
			'view_item'             => __( 'View Lesson', 'masteriyo' ),
			'view_items'            => __( 'View Lessons', 'masteriyo' ),
			'search_items'          => __( 'Search Lesson', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into lesson', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this lesson', 'masteriyo' ),
			'items_list'            => __( 'Lessons list', 'masteriyo' ),
			'items_list_navigation' => __( 'Lessons list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter lessons list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Lessons', 'masteriyo' ),
			'description'         => __( 'Lessons Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'comments', 'custom-fields', 'post-formats' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'menu_position'       => 5,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => $debug,
			'show_in_admin_bar'   => $debug,
			'show_in_nav_menus'   => $debug,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'lesson', 'lessons' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => true,
			'rewrite'             => $permalinks['lesson_rewrite_slug'] ? array(
				'slug'       => $permalinks['lesson_rewrite_slug'],
				'with_front' => false,
				'feeds'      => true,
			) : false,
		);
	}
}
