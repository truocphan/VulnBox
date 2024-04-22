<?php

/**
 * Apply Filter Post Meta
 */
add_filter( 'stm_wp_import_post_meta', 'stm_wp_import_post_meta_filter', 10, 1 );

function stm_wp_import_post_meta_filter( $post_meta ) {
	if ( function_exists('consulting_importer_get_placeholder') && !empty( consulting_importer_get_placeholder() ) ) {
		foreach ( $post_meta as $meta_index => $meta ) {
			switch ( $meta['key'] ) {
				case '_elementor_data':
					consulting_import_rebuilder_elementor_data($post_meta[ $meta_index ]['value'] );
					break;
				case 'testimonial_bg_img':
					$post_meta[ $meta_index ]['value']  = consulting_importer_get_placeholder();
					break;
				case 'title_box_bg_image':
					if ( ! empty( $meta['value'] ) ) {
						$post_meta[ $meta_index ]['value']  = consulting_importer_get_placeholder();
					}
					break;
			}

		}
	}

	return $post_meta;
}

/**
 * Apply Filter for changing Attachment URL
 */
add_filter( 'stm_wp_import_attachment_url', 'stm_wp_import_attachment_url_filter', 10, 4 );

function stm_wp_import_attachment_url_filter( $url, $theme, $layout, $builder ) {
	switch ( $theme ) {
		case 'consulting':
			if ( $builder === 'elementor' ) {
				$url = 'https://consulting.stylemixthemes.com/demo/wp-content/uploads/2016/06/placeholder.gif';
			}
			break;
		case 'masterstudy':
			$image_name = basename($url);
			if ( 'default' === $layout && 'elementor' !== $builder ) {
				$url = 'http://stylemixthemes.com/masterstudy/demo/wp-content/uploads/2015/07/placeholder.gif';
			} else {
				$url = "https://stylemixthemes.com/masterstudy/demoimages/{$layout}/{$image_name}";
			}
			break;
		case 'pearl':
			$url = str_replace('.jpeg', '?dpr=1&auto=format&fit=crop&w=1920&h=700&q=1', $url);
			break;
		case 'motors':
			if ( $builder === 'elementor' ) {

				$lName = str_replace('_', '-', $layout);

				$url = str_replace( 'http://test.loc', 'https://motors.stylemixthemes.com/demo/' . $lName, $url );
			} else {
				$url = str_replace( '.jpeg', '?dpr=1&auto=format&fit=crop&w=1920&h=700&q=1', $url );
			}
			break;
		case 'elab':
			$image_name = basename($url);
			$url = "https://elab.stylemixthemes.com/demoimages/{$layout}/{$image_name}";
			break;
		case 'betop':
			$image_name = basename($url);
			$url = "https://betop.stylemixthemes.com/demoimages/{$layout}/{$image_name}";
			break;
		case 'hotello':
			$image_name = basename($url);
			$url = "https://hotello.stylemixthemes.com/demoimages/{$layout}/{$image_name}";
			break;
		case 'cryterio':
			$upgraded_layouts = [
				'token_sale',
				'crypto_blog',
				'creative_ico',
				'ico_directory',
				'ico_listing',
				'ico_agency',
				'ico_isometric',
				'ico_white',
				'ico_purple',
			];

			if ( ! in_array( $layout, $upgraded_layouts ) ) {
				$url = str_replace('.jpeg', '?dpr=1&auto=format&fit=crop&w=1920&h=700&q=1', $url);
			} else {
				$image_name = basename($url);
				if ( strpos( $image_name, 'unsplash_photo' ) !== false ) {
					$image_name = explode('.', $image_name);
					if ( ! empty( $image_name[0] ) ) {
						$image_name = $image_name[0];
					}
					$image_name = str_replace('unsplash_photo', 'photo', $image_name);
					$url = "https://images.unsplash.com//{$image_name}?ixlib=rb-0.3.5&s=b447d7f07e2a211cd7f6745e77aeca03&auto=format&fit=crop&w=1300&h=800&q=10.jpeg";
				} else {
					$url = "https://crypterio.stylemixthemes.com/demoimages/{$layout}/{$image_name}";
				}
			}

			break;
		case 'sequoia':
			$lName = str_replace('_', '-', $layout);

			$url = str_replace( 'http://sequoia.loc', 'https://sequoia.stylemixthemes.com/demo/' . $lName, $url );
			break;
	}

	return $url;
}

/**
 * Add Action before Fetch Attachment
 */
add_action( 'stm_wp_import_before_fetch_attachment', 'stm_wp_import_before_fetch_attachment_action', 10, 1 );

function stm_wp_import_before_fetch_attachment_action( $builder ) {
	if ( function_exists('consulting_importer_get_placeholder') && $builder === 'elementor' ) {
		$placeholder = consulting_importer_get_placeholder();
		// Check if we have placeholder Already
		if ( $placeholder ) {
			return true;
		}
	}
}

/**
 * Add Action after Insert Attachment
 */
add_action( 'stm_wp_import_after_insert_attachment', 'stm_wp_import_after_insert_attachment_action', 10, 2 );

function stm_wp_import_after_insert_attachment_action( $post_id, $builder ) {
	if ( $builder === 'elementor' ) {
		update_post_meta( $post_id, '_wp_attachment_image_alt', 'theme_placeholder' );
	}
}

/**
 * Add Action after Import Post Meta
 */
add_action( 'stm_import_post_meta', 'stm_import_post_meta_action', 10, 3 );

function stm_import_post_meta_action( $post_id, $key, $value ) {
	$post_metas = [
		'stm_before',
		'stm_after',
	];

	if ( in_array( $key, $post_metas ) ) {
		update_post_meta($post_id, $key, $value);
	}
}

/**
 * Add Action for Mega Menu items
 */
add_action( 'stm_wp_import_update_nav_menu', 'stm_wp_import_update_nav_menu_action', 10, 2 );

function stm_wp_import_update_nav_menu_action( $post_meta, $post_id ) {
	$custom_args = [];

	foreach ( $post_meta as $key => $val ) {
		if ( ! empty( $val['value'] ) && strpos( $val['key'], '_stm') !== false ) {
			$custom_args[$val['key']] = $val['value'];
		}
	}

	if ( ! empty( $custom_args ) ) {
		foreach ( $custom_args as $key => $val ) {
			update_post_meta( $post_id, $key, $val );
		}
	}
}
