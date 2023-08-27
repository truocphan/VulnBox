<?php
/**
 * Handle the JupiterX custom snippets post type.
 *
 * @package JupiterX_Core\Post_Type
 *
 * @since 2.0.0
*/

/**
 * Bypass php eval error by phpmd
 *
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.EvalExpression)
 */
class JupiterX_Custom_Snippets {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'create_custom_snippets_post_type' ] );
	}

	public function create_custom_snippets_post_type() {

		$args = [
			'label'               => __( 'Custom Snippets', 'jupiterx-core' ),
			'description'         => __( 'Custom Snippets news and reviews', 'jupiterx-core' ),
			'labels'              => [],
			'supports'            => [ 'title', 'editor', 'author', 'custom-fields' ],
			'taxonomies'          => [],
			'public'              => true,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'can_export'          => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		];

		register_post_type( 'jupiterx-codes', $args );
	}
}
JupiterX_Custom_Snippets::get_instance();
