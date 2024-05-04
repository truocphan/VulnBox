<?php
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	
	// require all widgets
	foreach ( glob( __DIR__ . '/../widgets/*.php' ) as $file ) {
		require_once $file;
	}

	add_action( 'wp_ajax_piotnetforms_widget_preview', 'piotnetforms_widget_preview' );
	add_action( 'wp_ajax_nopriv_piotnetforms_widget_preview', 'piotnetforms_widget_preview' );

	function piotnetforms_widget_preview() {
		$widget_id = sanitize_text_field( $_POST['widget_id'] );
		$function  = sanitize_text_field( $_POST['function'] );

		$response = [
			'widget_id' => $widget_id,
		];

		if ( ! is_user_logged_in() || ! current_user_can( 'edit_others_posts' ) ) {
			print_r( 'permission_error' );
			return;
		}

		if ( $function == 'widget_init' ) {
			$widget_data       = sanitize_text_field( $_POST['widget_data'] );
			$widget            = new $widget_data['class_name']();
			$widget->widget_id = $widget_id;

			$response['outputHTML'] = $widget->output( $widget_id, true );
		} elseif ( $function == 'widget_edit' ) {
			$widget_settings    = sanitize_text_field( $_POST['widget_settings'] );
			$widget_information = sanitize_text_field( $_POST['widget_information'] );

			$widget            = new $widget_information['class_name']();
			$widget->settings  = $widget_settings;
			$widget->widget_id = $widget_id;

			$response['outputHTML'] = stripslashes( $widget->output( $widget_id, true ) );
		}

		echo json_encode( $response );

		wp_die();
	}