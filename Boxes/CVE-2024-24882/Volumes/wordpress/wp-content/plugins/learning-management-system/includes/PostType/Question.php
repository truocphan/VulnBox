<?php
/**
 * Question post type.
 *
 * @since 1.0.0
 */

namespace Masteriyo\PostType;

class Question extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'mto-question';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$debug = masteriyo_is_post_type_debug_enabled();

		$this->labels = array(
			'name'                  => _x( 'Questions', 'Question General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Question', 'Question Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Questions', 'masteriyo' ),
			'name_admin_bar'        => __( 'Question', 'masteriyo' ),
			'archives'              => __( 'Question Archives', 'masteriyo' ),
			'attributes'            => __( 'Question Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Question:', 'masteriyo' ),
			'all_items'             => __( 'All Questions', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Question', 'masteriyo' ),
			'edit_item'             => __( 'Edit Question', 'masteriyo' ),
			'update_item'           => __( 'Update Question', 'masteriyo' ),
			'view_item'             => __( 'View Question', 'masteriyo' ),
			'view_items'            => __( 'View Questions', 'masteriyo' ),
			'search_items'          => __( 'Search Question', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into question', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this question', 'masteriyo' ),
			'items_list'            => __( 'Questions list', 'masteriyo' ),
			'items_list_navigation' => __( 'Questions list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter questions list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Questions', 'masteriyo' ),
			'description'         => __( 'Questions Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'comments', 'custom-fields', 'page-attributes', 'post-formats' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'menu_position'       => 5,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => $debug,
			'show_in_admin_bar'   => $debug,
			'show_in_nav_menus'   => $debug,
			'can_export'          => true,
			'show_in_rest'        => false,
			'has_archive'         => true,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'question', 'questions' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => true,
		);
	}
}
