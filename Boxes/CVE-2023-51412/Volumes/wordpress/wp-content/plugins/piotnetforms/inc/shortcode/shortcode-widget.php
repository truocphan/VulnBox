<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

// require all widgets
foreach ( glob( __DIR__ . '/../widgets/*.php' ) as $file ) {
	require_once $file;
}

function piotnetforms_shortcode( $args, $content ) {
	ob_start();
	if ( ! empty( $args['id'] ) ) {
		$post_id         = $args['id'];
		$raw_data = get_post_meta( $post_id, '_piotnetforms_data', true );
		if ( ! empty( $raw_data ) ) {
			echo '<div id="piotnetforms" class="piotnetforms" data-piotnetforms-shortcode-id="' . esc_attr( $post_id ) . '">';
			$data = json_decode( $raw_data, true );
			$widget_content = $data['content'];
			@piotnetforms_render_loop( $widget_content, $post_id );

			$upload = wp_upload_dir();
			$upload_dir = $upload['baseurl'];
			$upload_dir = $upload_dir . '/piotnetforms/css/';

			$css_file = $upload_dir . $post_id . ".css";
			wp_enqueue_style( 'piotnetforms-style-' . $post_id, $css_file, [], get_post_meta( $post_id, '_piotnet-revision-version', true ) );
			echo '</div>';
			wp_add_inline_script( 'main-asasasdasd', 'alert("hello world");' );
			wp_enqueue_script( 'main-asasasdasd' );
			wp_enqueue_script( 'piotnetforms-script' );
			wp_enqueue_style( 'piotnetforms-style' );
		}
	}
	return ob_get_clean();
}
add_shortcode( 'piotnetforms', 'piotnetforms_shortcode' );

function piotnetforms_render_loop( $loop, $post_id ) {
	foreach ( $loop as $widget_item ) {
		$widget            = new $widget_item['class_name'];
		$widget->settings  = $widget_item['settings'];
		$widget_id         = $widget_item['id'];
		$widget->widget_id = $widget_id;
		$widget->post_id   = $post_id;

		if ( ! empty( $widget_item['fonts'] ) ) {
			$fonts = $widget_item['fonts'];
			if ( ! empty( $fonts ) ) {
				foreach ( $fonts as $font ) {
					wp_enqueue_style( 'piotnetforms-add-font-' . $font, $font );
				}
			}
		}

		$widget_type = $widget->get_type();
		if ( $widget_type === 'section' || $widget_type === 'column' ) {
			echo @$widget->output_wrapper_start( $widget_id );
			if ( isset( $widget_item['elements'] ) ) {
				echo @piotnetforms_render_loop( $widget_item['elements'], $post_id );
			}
		} else {
			$output = $widget->output( $widget_id );
			$output = piotnetforms_dynamic_tags( $output );
			echo @$output;
		}

		if ( $widget_type === 'section' || $widget_type === 'column' ) {
			echo @$widget->output_wrapper_end( $widget_id );
		}
	}
}

function piotnetforms_dynamic_tags( $output ) {

	if ( stripos( $output, '{{' ) !== false && stripos( $output, '}}' ) !== false ) {
		$pattern = '~\{\{\s*(.*?)\s*\}\}~';
		preg_match_all( $pattern, $output, $matches );
		$dynamic_tags = [];

		if ( ! empty( $matches[1] ) ) {
			$matches = array_unique( $matches[1] );

			foreach ( $matches as $key => $match ) {
				if ( stripos( $match, '|' ) !== false ) {
					$match_attr = explode( '|', $match );
					$attr_array = [];
					foreach ( $match_attr as $key_attr => $value_attr ) {
						if ( $key_attr != 0 ) {
							$attr                           = explode( ':', $value_attr, 2 );
							$attr_array[ trim( $attr[0] ) ] = trim( $attr[1] );
						}
					}

					$dynamic_tags[] = [
						'dynamic_tag' => '{{' . $match . '}}',
						'name'        => trim( $match_attr[0] ),
						'attr'        => $attr_array,
					];
				} else {
					$dynamic_tags[] = [
						'dynamic_tag' => '{{' . $match . '}}',
						'name'        => trim( $match ),
					];
				}
			}
		}

		if ( ! empty( $dynamic_tags ) ) {
			foreach ( $dynamic_tags as $tag ) {
				if ( $tag['name'] == 'current_date_time' ) {
					if ( empty( $tag['attr']['date_format'] ) ) {
						$tag_value = date( 'Y-m-d H:i:s' );
					} else {
						$tag_value = date( $tag['attr']['date_format'] );
					}

					$output = str_replace( $tag['dynamic_tag'], $tag_value, $output );
				}

				if ( $tag['name'] == 'post_id' ) {
					$tag_value = get_the_ID();
					$output    = str_replace( $tag['dynamic_tag'], $tag_value, $output );
				}
			}
		}
	}

	return $output;
}
