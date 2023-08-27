<?php

/**
 * Handle the JupiterX custom fonts post type.
 *
 * @package JupiterX_Core\Post_Type
 *
 * @since 2.5.0
 */
class JupiterX_Icons_Post_Type {

	private static $instance = null;

	private static $post_type = 'jupiterx-icons';

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'create_custom_icons_post_type' ] );
	}

	public function create_custom_icons_post_type() {
		$args = [
			'label'               => esc_html__( 'Custom Icons', 'jupiterx-core' ),
			'description'         => esc_html__( 'Custom Icons news and reviews', 'jupiterx-core' ),
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

		register_post_type( self::$post_type, $args );
	}
}

JupiterX_Icons_Post_Type::get_instance();
