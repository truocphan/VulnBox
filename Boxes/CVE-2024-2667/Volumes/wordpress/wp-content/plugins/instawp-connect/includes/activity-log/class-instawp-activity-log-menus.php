<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstaWP_Activity_Log_Menus {

	public function __construct() {
		add_action( 'wp_update_nav_menu', array( $this, 'hooks_menu_created_or_updated' ) );
		add_action( 'wp_create_nav_menu', array( $this, 'hooks_menu_created_or_updated' ) );
		add_action( 'delete_nav_menu', array( $this, 'hooks_menu_deleted' ), 10, 3 );
	}

	public function hooks_menu_created_or_updated( $nav_menu_selected_id ) {
		if ( $menu_object = wp_get_nav_menu_object( $nav_menu_selected_id ) ) {
			if ( 'wp_create_nav_menu' === current_filter() ) {
				$action = 'menu_created';
			} else {
				$action = 'menu_updated';
			}

			InstaWP_Activity_Log::insert_log( array(
				'action'      => $action,
				'object_type' => 'Menus',
				'object_id'   => $menu_object->term_id,
				'object_name' => $menu_object->name,
			) );
		}
	}

	public function hooks_menu_deleted( $term, $tt_id, $deleted_term ) {
		InstaWP_Activity_Log::insert_log( array(
			'action'      => 'menu_deleted',
			'object_type' => 'Menus',
			'object_id'   => $deleted_term->term_id,
			'object_name' => $deleted_term->name,
		) );
	}
}

new InstaWP_Activity_Log_Menus();