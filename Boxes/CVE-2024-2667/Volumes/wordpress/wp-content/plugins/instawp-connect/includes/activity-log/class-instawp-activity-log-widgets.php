<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstaWP_Activity_Log_Widgets {

	public function __construct() {
		add_filter( 'widget_update_callback', array( $this, 'hooks_widget_update_callback' ), 9999, 4 );
		add_filter( 'sidebar_admin_setup', array( $this, 'hooks_widget_delete' ) ); // Widget delete.
	}

	public function hooks_widget_update_callback( $instance, $new_instance, $old_instance, WP_Widget $widget ) {
		$event_args = array(
			'action'         => 'widget_updated',
			'object_type'    => 'Widget',
			'object_subtype' => 'sidebar_unknown',
			'object_id'      => 0,
			'object_name'    => $widget->id_base,
		);

		if ( empty( $_REQUEST['sidebar'] ) ) {
			return $instance;
		}

		InstaWP_Activity_Log::insert_log( $event_args );

		// We are need return the instance, for complete the filter.
		return $instance;
	}

	public function hooks_widget_delete() {
		if ( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) && ! empty( $_REQUEST['widget-id'] ) ) {
			if ( isset( $_REQUEST['delete_widget'] ) && 1 === (int) $_REQUEST['delete_widget'] ) {
				InstaWP_Activity_Log::insert_log( array(
					'action'         => 'widget_deleted',
					'object_type'    => 'Widget',
					'object_subtype' => strtolower( sanitize_text_field( $_REQUEST['sidebar'] ) ),
					'object_id'      => 0,
					'object_name'    => sanitize_text_field( $_REQUEST['id_base'] ),
				) );
			}
		}
	}
}

new InstaWP_Activity_Log_Widgets();