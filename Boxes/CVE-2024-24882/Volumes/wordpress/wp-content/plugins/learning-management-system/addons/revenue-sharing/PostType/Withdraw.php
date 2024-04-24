<?php
/**
 * Withdraw class.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\PostType;
 */

namespace Masteriyo\Addons\RevenueSharing\PostType;

use Masteriyo\PostType\PostType;

/**
 * Zoom class.
 */
class Withdraw extends PostType {

	/**
	 * Post slug.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $slug = PostType::WITHDRAW;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$debug = masteriyo_is_post_type_debug_enabled();

		$this->labels = array(
			'name'                  => _x( 'Withdraw', 'Withdraw General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Withdraw', 'Withdraw Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Withdraws', 'masteriyo' ),
			'name_admin_bar'        => __( 'Withdraw', 'masteriyo' ),
			'archives'              => __( 'Withdraw Archives', 'masteriyo' ),
			'attributes'            => __( 'Withdraw Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Withdraw:', 'masteriyo' ),
			'all_items'             => __( 'All Withdraws', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Withdraw', 'masteriyo' ),
			'edit_item'             => __( 'Edit Withdraw', 'masteriyo' ),
			'update_item'           => __( 'Update Withdraw', 'masteriyo' ),
			'view_item'             => __( 'View Withdraw', 'masteriyo' ),
			'view_items'            => __( 'View Withdraws', 'masteriyo' ),
			'search_items'          => __( 'Search Withdraw', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into Withdraw', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Withdraw', 'masteriyo' ),
			'items_list'            => __( 'Withdraws list', 'masteriyo' ),
			'items_list_navigation' => __( 'Withdraws list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter withdraws list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Withdraw', 'masteriyo' ),
			'description'         => __( 'Withdraw Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'custom-fields' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'menu_position'       => 5,
			'public'              => $debug,
			'show_ui'             => $debug,
			'show_in_menu'        => $debug,
			'show_in_admin_bar'   => $debug,
			'show_in_nav_menus'   => $debug,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'withdraw', 'withdraws' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => true,
		);
	}
}
