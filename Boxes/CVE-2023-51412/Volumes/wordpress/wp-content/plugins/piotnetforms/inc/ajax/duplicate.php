<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

require_once __DIR__ . '/../source/export.php';
require_once __DIR__ . '/../source/import.php';

add_action( 'wp_ajax_piotnetforms_duplicate', 'piotnetforms_duplicate' );
add_action( 'wp_ajax_nopriv_piotnetforms_duplicate', 'piotnetforms_duplicate' );

function piotnetforms_duplicate() {
	if ( ! isset( $_GET['id'] ) ) {
		die( "can't find post id" );
	} elseif ( ! is_user_logged_in() || ! current_user_can( 'edit_others_posts' ) ) {
		die( 'permission error' );
	} else {
		$post_id = intval( $_GET['id'] );
		$data    = piotnetforms_export_post( $post_id );

		if ( isset( $data['error'] ) ) {
			die( $data['error'] );
		}

        $post = [
            'post_title'  => $data['title'] . ' (Copy)',
            'post_status' => 'publish',
            'post_type'   => 'piotnetforms',
        ];

        $post_id = wp_insert_post( $post );

        if ( is_wp_error( $post_id ) ) {
            print_r( "Can't insert post: " . $post_id->get_error_message() );
        } else {
            piotnetforms_do_import( $post_id, $data );
            // echo '<script>window.location.href="' . admin_url( 'edit.php?post_type=piotnetforms' ) . '"</script>';
        }
	}
	wp_die();
}
