<?php
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	add_action( 'wp_ajax_piotnetforms_save', 'piotnetforms_save' );
	add_action( 'wp_ajax_nopriv_piotnetforms_save', 'piotnetforms_save' );

	const DATA_VERSION_PIOTNET = 1;

	function piotnetforms_save() {

		$post_id = sanitize_text_field( $_POST['post_id'] );

		if ( ! is_user_logged_in() || ! current_user_can( 'edit_others_posts' ) ) {
			print_r( 'permission_error' );
			return;
		}

		print_r( $post_id );

		if ( isset( $_POST['piotnetforms_data'] ) ) {
			$raw_data = stripslashes( sanitize_text_field($_POST['piotnetforms_data']) );

            $data            = json_decode( $raw_data );
            $data->version = DATA_VERSION_PIOTNET;
			$data_str        = json_encode( $data );
			update_post_meta( $post_id, '_piotnetforms_data', wp_slash( $data_str ) );
		}

		if ( isset( $_POST['piotnet-widgets-css'] ) ) {
			$widgets_css      = sanitize_text_field($_POST['piotnet-widgets-css']);
			$revision_version = intval( get_post_meta( $post_id, '_piotnet-revision-version', true ) ) + 1;
			update_post_meta( $post_id, '_piotnet-revision-version', $revision_version );

			$upload     = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir . '/piotnetforms/css/';

			$file = fopen( $upload_dir . $post_id . '.css', 'wb' );
			fwrite( $file, stripslashes( $widgets_css ) );
			fclose( $file );
		}

		$my_post_update = [
			'ID'          => $post_id,
			'post_status' => 'publish',
		];
		wp_update_post( $my_post_update );

		wp_die();
	}
