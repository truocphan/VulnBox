<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function piotnetforms_generate_widget_id() {
	$random_number = substr( hexdec( uniqid() ), -8 );
	return 'p' . $random_number;
}

function piotnetforms_replace_widget_id_in_content( $content, $id, $new_id ) {
	foreach ( $content as &$widget ) {
		$current_id = $widget['id'];
		if ( $current_id === $id ) {
			$widget['id'] = $new_id;
			break;
		}

		if ( isset( $widget['elements'] ) ) {
			$widget['elements'] = piotnetforms_replace_widget_id_in_content( $widget['elements'], $id, $new_id );
		}
	}
	return $content;
}

function piotnetforms_fill_settings_in_content( $content, $widgets ) {
	$length = count( $content );
	for ( $i = 0; $i <= $length - 1; $i++ ) {
		$widget = $widgets[ $content[ $i ]['id'] ];

		$content[ $i ]['settings'] = $widget['settings'];

		if ( isset( $widget['fonts'] ) ) {
			$content[ $i ]['fonts'] = $widget['fonts'];
		}

		if ( isset( $content[ $i ]['elements'] ) ) {
			$content[ $i ]['elements'] = piotnetforms_fill_settings_in_content( $content[ $i ]['elements'], $widgets );
		}
	}
	return $content;
}

function piotnetforms_do_import( $post_id, $data ) {
	$widgets     = $data['widgets'];
	$content     = $data['content'];
	$new_widgets = [];

	$widget_ids     = [];
	$new_widget_ids = [];

	foreach ( $widgets as $widget_id => $widget ) {
		$new_widget_id = piotnetforms_generate_widget_id();

		$widget_ids[]     = $widget_id;
		$new_widget_ids[] = $new_widget_id;

		$new_widgets[ $new_widget_id ] = $widget;

		$content = piotnetforms_replace_widget_id_in_content( $content, $widget_id, $new_widget_id );
	}

	$data['widgets'] = $new_widgets;
	$data['content'] = piotnetforms_fill_settings_in_content( $content, $new_widgets );

	$css = str_replace( $widget_ids, $new_widget_ids, $data['css'] );
	unset( $data['css'] );

	update_post_meta( $post_id, '_piotnetforms_data', wp_slash( json_encode( $data ) ) );

	update_post_meta( $post_id, '_piotnet-revision-version', 1 );

	$upload_dir = wp_upload_dir()['basedir'] . '/piotnetforms/css/';
	$css_path   = $upload_dir . $post_id . '.css';

	$file = fopen( $css_path, 'wb' );
	fwrite( $file, stripslashes( $css ) );
	fclose( $file );
}
