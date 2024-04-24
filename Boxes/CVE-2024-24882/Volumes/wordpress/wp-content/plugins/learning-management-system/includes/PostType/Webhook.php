<?php
/**
 * Webhook post type class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\PostType;

/**
 * Webhook post type class.
 *
 * @since 1.6.9
 */
class Webhook extends PostType {

	/**
	 * Post slug.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	protected $slug = PostType::WEBHOOK;

	/**
	 * Constructor.
	 *
	 * @since 1.6.9
	 */
	public function __construct() {
		$debug    = masteriyo_is_post_type_debug_enabled();
		$supports = array( 'title', 'editor', 'custom-fields', 'author', 'publicize', 'wpcom-markdown' );

		$this->labels = array(
			'name'                  => _x( 'Webhooks', 'Webhook General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Webhook', 'Webhook Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Webhooks', 'masteriyo' ),
			'name_admin_bar'        => __( 'Webhook', 'masteriyo' ),
			'archives'              => __( 'Webhook Archives', 'masteriyo' ),
			'attributes'            => __( 'Webhook Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Webhook:', 'masteriyo' ),
			'all_items'             => __( 'All Webhooks', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Webhook', 'masteriyo' ),
			'edit_item'             => __( 'Edit Webhook', 'masteriyo' ),
			'update_item'           => __( 'Update Webhook', 'masteriyo' ),
			'view_item'             => __( 'View Webhook', 'masteriyo' ),
			'view_items'            => __( 'View Webhooks', 'masteriyo' ),
			'search_items'          => __( 'Search Webhook', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into webhook', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this webhook', 'masteriyo' ),
			'items_list'            => __( 'Webhooks list', 'masteriyo' ),
			'items_list_navigation' => __( 'Webhooks list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter webhooks list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Webhooks', 'masteriyo' ),
			'description'         => __( 'Webhooks Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => $supports,
			'hierarchical'        => false,
			'menu_position'       => 5,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => $debug,
			'show_in_admin_bar'   => $debug,
			'show_in_nav_menus'   => $debug,
			'can_export'          => true,
			'show_in_rest'        => true,
			'has_archive'         => false,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'mto_webhook', 'mto_webhooks' ),
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'can_export'          => true,
			'delete_with_user'    => true,
		);
	}
}
