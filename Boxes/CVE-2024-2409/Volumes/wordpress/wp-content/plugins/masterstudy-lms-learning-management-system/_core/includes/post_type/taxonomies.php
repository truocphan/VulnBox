<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}


class STM_Lms_Taxonomies {
	public function __construct() {
		add_action( 'init', array( $this, 'taxonomies_init' ), - 1 );
		add_filter( 'manage_edit-stm_lms_question_taxonomy_columns', array( $this, 'add_stm_taxonomy_count_column' ) );
		add_filter( 'manage_edit-stm_lms_question_taxonomy_sortable_columns', array( $this, 'add_stm_taxonomy_count_column' ) );
		add_filter( 'manage_edit-stm_lms_course_taxonomy_columns', array( $this, 'add_stm_taxonomy_count_column' ) );
		add_filter( 'manage_edit-stm_lms_course_taxonomy_sortable_columns', array( $this, 'add_stm_taxonomy_count_column' ) );
		add_filter( 'manage_stm_lms_question_taxonomy_custom_column', array( $this, 'display_stm_taxonomy_count_column' ), 10, 3 );
		add_filter( 'manage_stm_lms_course_taxonomy_custom_column', array( $this, 'display_stm_taxonomy_count_column' ), 10, 3 );
	}

	public function taxonomies_init() {
		$taxonomies = $this->taxonomies();
		foreach ( $taxonomies as $taxonomy => $taxonomy_args ) {
			register_taxonomy( $taxonomy, $taxonomy_args['post_type'], $taxonomy_args['args'] );
		}
	}

	public function taxonomies() {
		$rewrite_slug = STM_LMS_Options::get_option( 'courses_categories_slug', 'stm_lms_course_category' );

		return apply_filters(
			'stm_lms_taxonomies',
			array(
				'stm_lms_course_taxonomy'   => array(
					'post_type' => 'stm-courses',
					'args'      => array(
						'hierarchical'      => true,
						'labels'            => array(
							'name'              => _x( 'Courses category', 'taxonomy general name', 'masterstudy-lms-learning-management-system' ),
							'singular_name'     => _x( 'Course category', 'taxonomy singular name', 'masterstudy-lms-learning-management-system' ),
							'search_items'      => __( 'Search Courses category', 'masterstudy-lms-learning-management-system' ),
							'all_items'         => __( 'All Courses category', 'masterstudy-lms-learning-management-system' ),
							'parent_item'       => __( 'Parent Course category', 'masterstudy-lms-learning-management-system' ),
							'parent_item_colon' => __( 'Parent Course category:', 'masterstudy-lms-learning-management-system' ),
							'edit_item'         => __( 'Edit Course category', 'masterstudy-lms-learning-management-system' ),
							'update_item'       => __( 'Update Course category', 'masterstudy-lms-learning-management-system' ),
							'add_new_item'      => __( 'Add New Course category', 'masterstudy-lms-learning-management-system' ),
							'new_item_name'     => __( 'New Course category Name', 'masterstudy-lms-learning-management-system' ),
							'menu_name'         => __( 'Course category', 'masterstudy-lms-learning-management-system' ),
						),
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => $rewrite_slug ),
					),
				),
				'stm_lms_question_taxonomy' => array(
					'post_type' => 'stm-questions',
					'args'      => array(
						'public'            => false,
						'labels'            => array(
							'name'              => _x( 'Questions category', 'taxonomy general name', 'masterstudy-lms-learning-management-system' ),
							'singular_name'     => _x( 'Question category', 'taxonomy singular name', 'masterstudy-lms-learning-management-system' ),
							'search_items'      => __( 'Search Questions category', 'masterstudy-lms-learning-management-system' ),
							'all_items'         => __( 'All Questions category', 'masterstudy-lms-learning-management-system' ),
							'parent_item'       => __( 'Parent Question category', 'masterstudy-lms-learning-management-system' ),
							'parent_item_colon' => __( 'Parent Question category:', 'masterstudy-lms-learning-management-system' ),
							'edit_item'         => __( 'Edit Question category', 'masterstudy-lms-learning-management-system' ),
							'update_item'       => __( 'Update Question category', 'masterstudy-lms-learning-management-system' ),
							'add_new_item'      => __( 'Add New Question category', 'masterstudy-lms-learning-management-system' ),
							'new_item_name'     => __( 'New Question category Name', 'masterstudy-lms-learning-management-system' ),
							'menu_name'         => __( 'Question category', 'masterstudy-lms-learning-management-system' ),
						),
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => true,
					),
				),
			)
		);
	}

	public function add_stm_taxonomy_count_column( $columns ) {
		unset( $columns['posts'] );
		$columns['stm_taxonomy_count'] = esc_html__( 'Count', 'masterstudy-lms-learning-management-system' );

		return $columns;
	}

	public function display_stm_taxonomy_count_column( $content, $column_name, $term_id ) {
		if ( 'stm_taxonomy_count' === $column_name ) {
			$taxonomy   = get_current_screen()->taxonomy;
			$post_types = array(
				'stm_lms_question_taxonomy' => 'stm-questions',
				'stm_lms_course_taxonomy'   => 'stm-courses',
			);
			$new_url    = add_query_arg(
				array(
					'post_type' => $post_types[ $taxonomy ],
					$taxonomy   => get_term_by( 'id', $term_id, $taxonomy )->slug,
				),
				admin_url( 'edit.php' ),
			);
			return sprintf( '<a href="%s">%d</a>', esc_url( $new_url ), get_term_by( 'id', $term_id, $taxonomy )->count );
		}
		return $content;
	}

}

new STM_Lms_Taxonomies();
