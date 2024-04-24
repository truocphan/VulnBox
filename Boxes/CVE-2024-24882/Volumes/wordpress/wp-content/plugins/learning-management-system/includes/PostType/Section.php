<?php
/**
 * Sections class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\PostType;
 */

namespace Masteriyo\PostType;

/**
 * Sections class.
 */
class Section extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'mto-section';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$debug      = masteriyo_is_post_type_debug_enabled();
		$permalinks = masteriyo_get_permalink_structure();

		$this->labels = array(
			'name'                  => _x( 'Sections', 'Section General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Section', 'Section Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Sections', 'masteriyo' ),
			'name_admin_bar'        => __( 'Section', 'masteriyo' ),
			'archives'              => __( 'Section Archives', 'masteriyo' ),
			'attributes'            => __( 'Section Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Section:', 'masteriyo' ),
			'all_items'             => __( 'All Sections', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Section', 'masteriyo' ),
			'edit_item'             => __( 'Edit Section', 'masteriyo' ),
			'update_item'           => __( 'Update Section', 'masteriyo' ),
			'view_item'             => __( 'View Section', 'masteriyo' ),
			'view_items'            => __( 'View Sections', 'masteriyo' ),
			'search_items'          => __( 'Search Section', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into section', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this section', 'masteriyo' ),
			'items_list'            => __( 'Sections list', 'masteriyo' ),
			'items_list_navigation' => __( 'Sections list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter sections list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Sections', 'masteriyo' ),
			'description'         => __( 'Sections Description', 'masteriyo' ),
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
			'capability_type'     => array( 'section', 'sections' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => true,
			'rewrite'             => $permalinks['section_rewrite_slug'] ? array(
				'slug'       => $permalinks['section_rewrite_slug'],
				'with_front' => false,
				'feeds'      => true,
			) : false,
		);
	}
}
