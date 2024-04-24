<?php
/**
 * Courses Course tags.
 */

namespace Masteriyo\Taxonomy\Course;

use Masteriyo\Taxonomy\Taxonomy;

class Tag extends Taxonomy {

	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_tag';

	/**
	 * Post type the taxonomy belongs to.
	 *
	 * @since 1.0.0
	 */
	protected $post_type = 'mto-course';

	/**
	 * Default labels.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_args() {

		$permalinks = masteriyo_get_permalink_structure();

		/**
		 * Filters arguments for course tag taxonomy.
		 *
		 * @since 1.5.1
		 *
		 * @param array $args The arguments for course tag taxonomy.
		 */
		return apply_filters(
			'masteriyo_taxonomy_args_course_tag',
			array(
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tag_cloud'    => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'         => $permalinks['course_tag_rewrite_slug'],
					'with_front'   => false,
					'hierarchical' => true,
				),
				'labels'            => array(
					'name'                       => _x( 'Course Tags', 'Taxonomy General Name', 'masteriyo' ),
					'singular_name'              => _x( 'Course Tag', 'Taxonomy Singular Name', 'masteriyo' ),
					'menu_name'                  => __( 'Course Tag', 'masteriyo' ),
					'all_items'                  => __( 'All Course Tags', 'masteriyo' ),
					'parent_item'                => __( 'Parent Course Tag', 'masteriyo' ),
					'parent_item_colon'          => __( 'Parent Course Tag:', 'masteriyo' ),
					'new_item_name'              => __( 'New Course Tag Name', 'masteriyo' ),
					'add_new_item'               => __( 'Add New Course Tag', 'masteriyo' ),
					'edit_item'                  => __( 'Edit Course Tag', 'masteriyo' ),
					'update_item'                => __( 'Update Course Tag', 'masteriyo' ),
					'view_item'                  => __( 'View Course Tag', 'masteriyo' ),
					'separate_items_with_commas' => __( 'Separate course tags with commas', 'masteriyo' ),
					'add_or_remove_items'        => __( 'Add or remove course tags', 'masteriyo' ),
					'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
					'popular_items'              => __( 'Popular Course Tags', 'masteriyo' ),
					'search_items'               => __( 'Search Course Tags', 'masteriyo' ),
					'not_found'                  => __( 'Not Found', 'masteriyo' ),
					'no_terms'                   => __( 'No course tags', 'masteriyo' ),
					'items_list'                 => __( 'Course Tags list', 'masteriyo' ),
					'items_list_navigation'      => __( 'Course Tags list navigation', 'masteriyo' ),
				),
			)
		);
	}
}
