<?php

	if ( ! defined( 'ABSPATH' ) ) { exit; }
	
	add_action( 'wp_ajax_piotnetforms_get_json_file', 'piotnetforms_get_json_file' );
	add_action( 'wp_ajax_nopriv_piotnetforms_get_json_file', 'piotnetforms_get_json_file' );

	function piotnetforms_get_json_file() {
		if ( ! empty( $_REQUEST['libs'] ) ) {
			foreach ( $_REQUEST['libs'] as $lib ) {
                $path = dirname( __FILE__ ) . '/../lib/' . $lib . '.json';
                $storage[ $lib ] = file_get_contents( $path );
			}
			echo json_encode( $storage );
		} else {
			return;
		}
		wp_die();
	}
