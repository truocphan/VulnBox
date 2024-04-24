<?php
/**
 * Quizes post type.
 *
 * @since 1.0.0
 *
 * @package PostType;
 */

namespace Masteriyo\PostType;

/**
 * Quizes post type.
 *
 * @since 1.0.0
 */
class Quiz extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'mto-quiz';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$debug      = masteriyo_is_post_type_debug_enabled();
		$permalinks = masteriyo_get_permalink_structure();

		$this->labels = array(
			'name'                  => _x( 'Quizzes', 'Quiz General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Quiz', 'Quiz Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Quizzes', 'masteriyo' ),
			'name_admin_bar'        => __( 'Quiz', 'masteriyo' ),
			'archives'              => __( 'Quiz Archives', 'masteriyo' ),
			'attributes'            => __( 'Quiz Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Quiz:', 'masteriyo' ),
			'all_items'             => __( 'All Quizzes', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Quiz', 'masteriyo' ),
			'edit_item'             => __( 'Edit Quiz', 'masteriyo' ),
			'update_item'           => __( 'Update Quiz', 'masteriyo' ),
			'view_item'             => __( 'View Quiz', 'masteriyo' ),
			'view_items'            => __( 'View Quizzes', 'masteriyo' ),
			'search_items'          => __( 'Search Quiz', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into quiz', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this quiz', 'masteriyo' ),
			'items_list'            => __( 'Quizzes list', 'masteriyo' ),
			'items_list_navigation' => __( 'Quizzes list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter quizzes list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Quizzes', 'masteriyo' ),
			'description'         => __( 'Quizzes Description', 'masteriyo' ),
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
			'show_in_rest'        => false,
			'has_archive'         => false,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'quiz', 'quizzes' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => true,
			'rewrite'             => $permalinks['quiz_rewrite_slug'] ? array(
				'slug'       => $permalinks['quiz_rewrite_slug'],
				'with_front' => false,
				'feeds'      => true,
			) : false,
		);
	}


}
