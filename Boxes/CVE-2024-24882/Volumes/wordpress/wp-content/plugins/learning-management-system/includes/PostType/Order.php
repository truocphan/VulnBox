<?php
/**
 * Orders class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\PostType;
 */

namespace Masteriyo\PostType;

/**
 * Orders class.
 */
class Order extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'mto-order';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$debug = masteriyo_is_post_type_debug_enabled();

		$this->labels = array(
			'name'                  => _x( 'Orders', 'Order General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Order', 'Order Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Orders', 'masteriyo' ),
			'name_admin_bar'        => __( 'Order', 'masteriyo' ),
			'archives'              => __( 'Order Archives', 'masteriyo' ),
			'attributes'            => __( 'Order Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Order:', 'masteriyo' ),
			'all_items'             => __( 'All Orders', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Order', 'masteriyo' ),
			'edit_item'             => __( 'Edit Order', 'masteriyo' ),
			'update_item'           => __( 'Update Order', 'masteriyo' ),
			'view_item'             => __( 'View Order', 'masteriyo' ),
			'view_items'            => __( 'View Orders', 'masteriyo' ),
			'search_items'          => __( 'Search Order', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into order', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this order', 'masteriyo' ),
			'items_list'            => __( 'Orders list', 'masteriyo' ),
			'items_list_navigation' => __( 'Orders list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter orders list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Orders', 'masteriyo' ),
			'description'         => __( 'Orders Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'custom-fields', 'post-formats' ),
			'taxonomies'          => array(),
			'hierarchical'        => true,
			'public'              => $debug,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'show_in_rest'        => true,
			'has_archive'         => true,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'order', 'orders' ),
			'exclude_from_search' => false,
			'publicly_queryable'  => is_admin(),
			'can_export'          => true,
			'delete_with_user'    => true,
		);
	}
}
