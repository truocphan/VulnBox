<?php
/**
 * Class to register Jupiter post types and custom taxonomies.
 *
 * @package JupiterX_Core\Post_Type
 *
 * @since 1.0.0
 */

/**
 * Handle the Jupiter Portfolio post type.
 *
 * @since 1.0.0
 *
 * @package JupiterX_Core\Post_Type
 */
final class JupiterX_Portfolio {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );

		if ( class_exists( 'acf' ) ) {
			$post_types = [
				'post' => '',
				'portfolio' => 'portfolio_',
			];

			foreach ( $post_types as $post_type ) {
				add_filter( "manage_edit-{$post_type}category_columns", [ $this, 'taxonomy_category_order_heading' ] );
				add_action( "manage_{$post_type}category_custom_column", [ $this, 'taxonomy_category_order_content' ], 10, 3 );
			}
		}
	}

	/**
	 * Register post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = [
			'name'           => _x( 'Portfolios', 'Portfolio General Name', 'jupiterx-core' ),
			'singular_name'  => _x( 'Portfolio', 'Portfolio Singular Name', 'jupiterx-core' ),
			'menu_name'      => esc_html__( 'Portfolios', 'jupiterx-core' ),
			'name_admin_bar' => esc_html__( 'Portfolio', 'jupiterx-core' ),
			'all_items'      => esc_html__( 'All Portfolios', 'jupiterx-core' ),
		];

		/**
		 * Filter portfolio post type arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The post type arguments.
		 */
		$args = apply_filters( 'jupiterx_portfolio_args', [
			'label'         => esc_html__( 'Portfolio', 'jupiterx-core' ),
			'description'   => esc_html__( 'Portfolio Description', 'jupiterx-core' ),
			'labels'        => $labels,
			'supports'      => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'trackbacks', 'revisions', 'custom_fields', 'page-attributes' ],
			'public'        => true,
			'menu_position' => 5,
			'can_export'    => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
		] );

		register_post_type( 'portfolio', $args );
	}

	/**
	 * Call taxonomies registration.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		$this->register_category_taxonomy();
		$this->register_tag_taxonomy();
	}

	/**
	 * Register category taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_category_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Portfolio Categories', 'Category General Name', 'jupiterx-core' ),
			'singular_name'              => _x( 'Category', 'Category Singular Name', 'jupiterx-core' ),
			'menu_name'                  => esc_html__( 'Categories', 'jupiterx-core' ),
			'all_items'                  => esc_html__( 'All Categories', 'jupiterx-core' ),
			'parent_item'                => esc_html__( 'Parent Category', 'jupiterx-core' ),
			'parent_item_colon'          => esc_html__( 'Parent Category:', 'jupiterx-core' ),
			'new_item_name'              => esc_html__( 'New Category Name', 'jupiterx-core' ),
			'add_new_item'               => esc_html__( 'Add New Category', 'jupiterx-core' ),
			'edit_item'                  => esc_html__( 'Edit Category', 'jupiterx-core' ),
			'update_item'                => esc_html__( 'Update Category', 'jupiterx-core' ),
			'view_item'                  => esc_html__( 'View Category', 'jupiterx-core' ),
			'separate_items_with_commas' => esc_html__( 'Separate categories  with commas', 'jupiterx-core' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove categories ', 'jupiterx-core' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'jupiterx-core' ),
			'popular_items'              => esc_html__( 'Popular Categories', 'jupiterx-core' ),
			'search_items'               => esc_html__( 'Search Categories', 'jupiterx-core' ),
			'not_found'                  => esc_html__( 'Not Found', 'jupiterx-core' ),
			'no_terms'                   => esc_html__( 'No categories ', 'jupiterx-core' ),
			'items_list'                 => esc_html__( 'Categories list', 'jupiterx-core' ),
			'items_list_navigation'      => esc_html__( 'Categories list navigation', 'jupiterx-core' ),
		);

		/**
		 * Filter portfolio category taxonomy arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The taxonomy arguments.
		 */
		$args = apply_filters( 'jupiterx_portfolio_category_args', [
			'labels'       => $labels,
			'rewrite'      => [ 'slug' => 'portfolio-category' ],
			'hierarchical' => true,
			'show_in_rest' => true,
		] );

		register_taxonomy( 'portfolio_category', 'portfolio', $args );
	}

	/**
	 * Register tag taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_tag_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Portfolio Tags', 'Tag General Name', 'jupiterx-core' ),
			'singular_name'              => _x( 'Tag', 'Tag Singular Name', 'jupiterx-core' ),
			'menu_name'                  => esc_html__( 'Tags', 'jupiterx-core' ),
			'all_items'                  => esc_html__( 'All Tags', 'jupiterx-core' ),
			'parent_item'                => esc_html__( 'Parent Tag', 'jupiterx-core' ),
			'parent_item_colon'          => esc_html__( 'Parent Tag:', 'jupiterx-core' ),
			'new_item_name'              => esc_html__( 'New Tag Name', 'jupiterx-core' ),
			'add_new_item'               => esc_html__( 'Add New Tag', 'jupiterx-core' ),
			'edit_item'                  => esc_html__( 'Edit Tag', 'jupiterx-core' ),
			'update_item'                => esc_html__( 'Update Tag', 'jupiterx-core' ),
			'view_item'                  => esc_html__( 'View Tag', 'jupiterx-core' ),
			'separate_items_with_commas' => esc_html__( 'Separate tags with commas', 'jupiterx-core' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove tags', 'jupiterx-core' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'jupiterx-core' ),
			'popular_items'              => esc_html__( 'Popular Tags', 'jupiterx-core' ),
			'search_items'               => esc_html__( 'Search Tags', 'jupiterx-core' ),
			'not_found'                  => esc_html__( 'Not Found', 'jupiterx-core' ),
			'no_terms'                   => esc_html__( 'No tags', 'jupiterx-core' ),
			'items_list'                 => esc_html__( 'Tags list', 'jupiterx-core' ),
			'items_list_navigation'      => esc_html__( 'Tags list navigation', 'jupiterx-core' ),
		);

		/**
		 * Filter portfolio tag taxonomy arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The taxonomy arguments.
		 */
		$args = apply_filters( 'jupiterx_portfolio_tag_args', [
			'labels'       => $labels,
			'rewrite'      => [ 'slug' => 'portfolio-tag' ],
			'show_in_rest' => true,
		] );

		register_taxonomy( 'portfolio_tag', 'portfolio', $args );
	}

	/**
	 * Add taxonomy category order heading.
	 *
	 * @since 1.21.0
	 */
	public function taxonomy_category_order_heading( $col_th ) {
		array_pop( $col_th );
		$col_th['jupiterx_order_cat'] = 'Order';
		$col_th['posts']              = 'Count';

		return wp_parse_args( array( 'jupiterx_order_cat' => 'Order' ), $col_th );
	}

	/**
	 * Add taxonomy category order content.
	 *
	 * @since 1.21.0
	 */
	public function taxonomy_category_order_content( $value, $column_name, $term_id ) {
		$column_name = '';
		$value       = get_field( 'jupiterx_taxonomy_order_number', 'category_' . $term_id );
		echo $value;

	}
}

new JupiterX_Portfolio();
