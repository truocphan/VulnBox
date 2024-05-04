<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

function piotnetforms_get_css( $post_id ) {
	$upload_dir = wp_upload_dir()['baseurl'] . '/piotnetforms/css/';
	$css_path   = $upload_dir . $post_id . '.css';
	return file_get_contents( $css_path );
}

function piotnetforms_remove_settings_in_content( $content ) {
	$length = count( $content );
	for ( $i = 0; $i <= $length - 1; $i++ ) {
		unset( $content[ $i ]['settings'] );
		unset( $content[ $i ]['fonts'] );

		if ( isset( $content[ $i ]['elements'] ) ) {
			$content[ $i ]['elements'] = piotnetforms_remove_settings_in_content( $content[ $i ]['elements'] );
		}
	}
	return $content;
}

function piotnetforms_export_post( $post_id ) {
	$post = get_post( $post_id );
	if ( is_null( $post ) ) {
		return [ 'error' => "the post doesn't exists" ];
	}

	$post_meta = get_post_meta( $post_id, '_piotnetforms_data', true );
	if ( empty( $post_meta ) ) {
		return [ 'error' => "the post haven't piotnetforms data" ];
	}

	$post_meta_settings = json_decode( $post_meta, true );

	return [
		'title'   => $post->post_title,
		'version' => $post_meta_settings['version'],
		'widgets' => $post_meta_settings['widgets'],
		'content' => piotnetforms_remove_settings_in_content( $post_meta_settings['content'] ),
		'css'     => piotnetforms_get_css( $post_id ),
	];
}
