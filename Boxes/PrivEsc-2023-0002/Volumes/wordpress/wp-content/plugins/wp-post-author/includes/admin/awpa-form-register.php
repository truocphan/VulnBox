<?php

defined( 'ABSPATH' ) || exit;


// Register Custom Post Type
function awpa_user_form_post_types() {

    $labels = array(
        'name'                  => _x( 'User Forms', 'Post Type General Name', 'wp-post-author' ),
        'singular_name'         => _x( 'User Form', 'Post Type Singular Name', 'wp-post-author' ),
        'menu_name'             => __( 'User Forms', 'wp-post-author' ),
        'name_admin_bar'        => __( 'User Forms', 'wp-post-author' ),
        'archives'              => __( 'Form Archives', 'wp-post-author' ),
        'attributes'            => __( 'Form Attributes', 'wp-post-author' ),
        'parent_item_colon'     => __( 'Parent Form:', 'wp-post-author' ),
        'all_items'             => __( 'All Forms', 'wp-post-author' ),
        'add_new_item'          => __( 'Add New Form', 'wp-post-author' ),
        'add_new'               => __( 'Add New', 'wp-post-author' ),
        'new_item'              => __( 'New Form', 'wp-post-author' ),
        'edit_item'             => __( 'Edit Form', 'wp-post-author' ),
        'update_item'           => __( 'Update Form', 'wp-post-author' ),
        'view_item'             => __( 'View Form', 'wp-post-author' ),
        'view_items'            => __( 'View Forms', 'wp-post-author' ),
        'search_items'          => __( 'Search Form', 'wp-post-author' ),
        'not_found'             => __( 'Not found', 'wp-post-author' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wp-post-author' ),
        'featured_image'        => __( 'Featured Image', 'wp-post-author' ),
        'set_featured_image'    => __( 'Set featured image', 'wp-post-author' ),
        'remove_featured_image' => __( 'Remove featured image', 'wp-post-author' ),
        'use_featured_image'    => __( 'Use as featured image', 'wp-post-author' ),
        'insert_into_item'      => __( 'Insert into item', 'wp-post-author' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'wp-post-author' ),
        'items_list'            => __( 'Forms list', 'wp-post-author' ),
        'items_list_navigation' => __( 'Forms list navigation', 'wp-post-author' ),
        'filter_items_list'     => __( 'Filter items list', 'wp-post-author' ),
    );
    $args = array(
        'label'                 => __( 'User Form', 'wp-post-author' ),
        'description'           => __( 'Post Type Description', 'wp-post-author' ),
        'labels'                => $labels,
        'supports'              => array( 'title','editor' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => false,
        'show_in_menu'          => false,
        'menu_position'         => 70,
        'show_in_admin_bar'     => false,
        'show_in_rest'          =>true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'awpa_user_form_build', $args );

}
add_action( 'init', 'awpa_user_form_post_types', 0 );