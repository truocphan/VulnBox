<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'register_post_type' ) ) :
	$dashboard_slug = get_option( 'frontend_admin_dashboard_slug' );
	if ( ! $dashboard_slug ) {
		$dashboard_slug = 'frontend-dashboard';
	}

	$labels = array(
		'name'                  => _x( 'Forms', 'Post Type General Name', 'acf-frontend-form-element' ),
		'singular_name'         => _x( 'Form', 'Post Type Singular Name', 'acf-frontend-form-element' ),
		'menu_name'             => __( 'Forms', 'acf-frontend-form-element' ),
		'name_admin_bar'        => __( 'Form', 'acf-frontend-form-element' ),
		'archives'              => __( 'Form Archives', 'acf-frontend-form-element' ),
		'all_items'             => __( 'Forms', 'acf-frontend-form-element' ),
		'add_new_item'          => __( 'Add New Form', 'acf-frontend-form-element' ),
		'add_new'               => __( 'Add New', 'acf-frontend-form-element' ),
		'new_item'              => __( 'New Form', 'acf-frontend-form-element' ),
		'edit_item'             => __( 'Edit Form', 'acf-frontend-form-element' ),
		'update_item'           => __( 'Update Form', 'acf-frontend-form-element' ),
		'view_item'             => __( 'View Form', 'acf-frontend-form-element' ),
		'search_items'          => __( 'Search Form', 'acf-frontend-form-element' ),
		'not_found'             => __( 'Not found', 'acf-frontend-form-element' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'acf-frontend-form-element' ),
		'items_list'            => __( 'Forms list', 'acf-frontend-form-element' ),
		'item_published'        => __( 'Settings Saved', 'acf-frontend-form-element' ),
		'item_updated'          => __( 'Settings Saved', 'acf-frontend-form-element' ),
		'items_list_navigation' => __( 'Forms list navigation', 'acf-frontend-form-element' ),
		'filter_items_list'     => __( 'Filter forms list', 'acf-frontend-form-element' ),
	);

	$args = array(
		'label'             => __( 'Form', 'acf-frontend-form-element' ),
		'description'       => __( 'Form', 'acf-frontend-form-element' ),
		'labels'            => $labels,
		'supports'          => false,
		'show_in_rest'      => true,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_in_menu'      =>  'fea-settings',
		'menu_position'     => 80,
		'show_in_admin_bar' => true,
		'can_export'        => true,
		'rewrite'           => array(
			'with_front' => true,
			'slug'       => $dashboard_slug,
		),
		'capability_type'   => 'page',
		'query_var'         => false,
	);
	register_post_type( 'admin_form', $args );

	add_filter(
		'post_updated_messages',
		function ( $messages ) {
			$messages['admin_form'] = array(
				'',
				__( 'Form updated.' ),
				__( 'Custom field updated.' ),
				__( 'Custom field deleted.' ),
				__( 'Form updated.' ),
				'',
				__( 'Form published.' ),
				__( 'Form saved.' ),
				__( 'Form submitted.' ),
				'',
				__( 'Form draft updated.' ),
			);
			return $messages;
		}
	);


	/*
		 $labels = array(
		'name'                  => _x( 'Templates', 'Post Type General Name', 'acf-frontend-form-element' ),
		'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'acf-frontend-form-element' ),
		'menu_name'             => __( 'Templates', 'acf-frontend-form-element' ),
		'name_admin_bar'        => __( 'Template', 'acf-frontend-form-element' ),
		'archives'              => __( 'Template Archives', 'acf-frontend-form-element' ),
		'all_items'             => __( 'Templates', 'acf-frontend-form-element' ),
		'add_new_item'          => __( 'Add New Template', 'acf-frontend-form-element' ),
		'add_new'               => __( 'Add New', 'acf-frontend-form-element' ),
		'new_item'              => __( 'New Template', 'acf-frontend-form-element' ),
		'edit_item'             => __( 'Edit Template', 'acf-frontend-form-element' ),
		'update_item'           => __( 'Update Template', 'acf-frontend-form-element' ),
		'view_item'             => __( 'View Template', 'acf-frontend-form-element' ),
		'search_items'          => __( 'Search Template', 'acf-frontend-form-element' ),
		'not_found'             => __( 'Not found', 'acf-frontend-form-element' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'acf-frontend-form-element' ),
		'items_list'            => __( 'Templates list', 'acf-frontend-form-element' ),
		'item_published'        => __( 'Settings Saved', 'acf-frontend-form-element' ),
		'item_updated'          => __( 'Settings Saved', 'acf-frontend-form-element' ),
		'items_list_navigation' => __( 'Templates list navigation', 'acf-frontend-form-element' ),
		'filter_items_list'     => __( 'Filter templates list', 'acf-frontend-form-element' ),
	);

	$args = array(
		'label'                 => __( 'Template', 'acf-frontend-form-element' ),
		'description'           => __( 'Template', 'acf-frontend-form-element' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'show_in_rest'          => true,
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'frontend-admin-settings',
		'menu_position'         => 80,
		'show_in_admin_bar'     => false,
		'can_export'            => true,
		'capability_type'       => 'page',
		'query_var'                => false,
	);
	register_post_type( 'fea-template', $args ); */


	add_filter(
		'post_updated_messages',
		function ( $messages ) {
			$messages['admin_template'] = array(
				'',
				__( 'Template updated.' ),
				__( 'Custom field updated.' ),
				__( 'Custom field deleted.' ),
				__( 'Template updated.' ),
				'',
				__( 'Template published.' ),
				__( 'Template saved.' ),
				__( 'Template submitted.' ),
				'',
				__( 'Template draft updated.' ),
			);
			return $messages;
		}
	);

	do_action( 'frontend_admin/post_types' );

endif;
