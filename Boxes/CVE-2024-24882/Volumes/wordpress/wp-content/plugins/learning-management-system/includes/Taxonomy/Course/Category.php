<?php
/**
 * Courses Course categories.
 */

namespace Masteriyo\Taxonomy\Course;

use Masteriyo\Taxonomy\Taxonomy;

class Category extends Taxonomy {
	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_cat';

	/**
	 * Post type the taxonomy belongs to.
	 *
	 * @since 1.0.0
	 */
	protected $post_type = 'mto-course';

	/**
	 * Get settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_args() {

		$permalinks = masteriyo_get_permalink_structure();

		/**
		 * Filters arguments for course category taxonomy.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The arguments for course category taxonomy.
		 */
		return apply_filters(
			'masteriyo_taxonomy_args_course_cat',
			array(
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tag_cloud'    => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'         => $permalinks['course_category_rewrite_slug'],
					'with_front'   => false,
					'hierarchical' => true,
				),
				'labels'            => array(
					'name'                       => _x( 'Course Categories', 'Taxonomy General Name', 'masteriyo' ),
					'singular_name'              => _x( 'Course Category', 'Taxonomy Singular Name', 'masteriyo' ),
					'menu_name'                  => __( 'Course Category', 'masteriyo' ),
					'all_items'                  => __( 'All Course Categories', 'masteriyo' ),
					'parent_item'                => __( 'Parent Course Category', 'masteriyo' ),
					'parent_item_colon'          => __( 'Parent Course Category:', 'masteriyo' ),
					'new_item_name'              => __( 'New Course Category Name', 'masteriyo' ),
					'add_new_item'               => __( 'Add New Course Category', 'masteriyo' ),
					'edit_item'                  => __( 'Edit Course Category', 'masteriyo' ),
					'update_item'                => __( 'Update Course Category', 'masteriyo' ),
					'view_item'                  => __( 'View Course Category', 'masteriyo' ),
					'separate_items_with_commas' => __( 'Separate course categories with commas', 'masteriyo' ),
					'add_or_remove_items'        => __( 'Add or remove course categories', 'masteriyo' ),
					'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
					'popular_items'              => __( 'Popular Course Categories', 'masteriyo' ),
					'search_items'               => __( 'Search Course Categories', 'masteriyo' ),
					'not_found'                  => __( 'Not Found', 'masteriyo' ),
					'no_terms'                   => __( 'No course categories', 'masteriyo' ),
					'items_list'                 => __( 'Course Categories list', 'masteriyo' ),
					'items_list_navigation'      => __( 'Course Categories list navigation', 'masteriyo' ),
				),
				'capabilities'      => array(
					'manage_terms' => 'manage_course_categories',
					'edit_terms'   => 'edit_course_categories',
					'delete_terms' => 'delete_course_categories',
					'assign_terms' => 'assign_course_categories',
				),
			)
		);
	}
}
