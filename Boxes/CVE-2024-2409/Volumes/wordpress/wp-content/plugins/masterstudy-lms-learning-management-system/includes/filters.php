<?php

/** @var \MasterStudy\Lms\Plugin $plugin */
add_filter( 'wp_rest_search_handlers', array( $plugin, 'register_search_handlers' ) );

add_filter(
	'rest_user_query',
	function ( array $prepared_args, \WP_REST_Request $request ) {
		unset( $prepared_args['has_published_posts'] );

		return $prepared_args;
	},
	10,
	2
);

add_filter(
	'masterstudy_lms_lesson_video_sources',
	function () {
		return array_map(
			function ( $id, $label ) {
				return array(
					'id'    => $id,
					'label' => $label,
				);
			},
			array_keys( apply_filters( 'ms_plugin_video_sources', array() ) ),
			array_values( apply_filters( 'ms_plugin_video_sources', array() ) )
		);
	}
);

function masterstudy_lms_rest_api_user( $data, $user, $request ) {
	$lms_avatar_url       = get_user_meta( $user->ID, 'stm_lms_user_avatar', true );
	$data->data['avatar'] = ! empty( $lms_avatar_url ) ? $lms_avatar_url : get_avatar_url( $user->ID );

	return $data;
}
add_filter( 'rest_prepare_user', 'masterstudy_lms_rest_api_user', 10, 3 );

function masterstudy_lms_double_slash_api_data( $value, $key ) {
	$double_slashes = array(
		'post_title',
		'post_content',
		'answers',
	);

	if ( in_array( $key, $double_slashes, true ) ) {
		if ( is_array( $value ) ) {
			array_walk_recursive(
				$value,
				function ( &$item, $item_key ) {
					if ( in_array( $item_key, array( 'question', 'text' ), true ) ) {
						$item = str_replace( '\\', '\\\\', $item );
					}
				}
			);
		} else {
			$value = str_replace( '\\', '\\\\', $value );
		}
	}

	return $value;
}
add_filter( 'masterstudy_lms_map_api_data', 'masterstudy_lms_double_slash_api_data', 10, 2 );

function masterstudy_lms_allow_iframe_to_instructor( $allowed_tags ) {
	if ( ! current_user_can( 'stm_lms_instructor' ) ) {
		return $allowed_tags;
	}

	$allowed_tags['iframe'] = array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'frameborder'     => true,
		'allowfullscreen' => true,
	);

	return $allowed_tags;
}

add_filter( 'wp_kses_allowed_html', 'masterstudy_lms_allow_iframe_to_instructor', 1 );

/**
 * Register Blocks Category class.
 *
 * @access public
 * @param array $categories - block categories.
 * @return array - returns block categories
 **/
function masterstudy_lms_gutenberg_blocks_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'masterstudy-lms-blocks',
			'title' => esc_html__( 'Masterstudy Blocks', 'masterstudy-lms-learning-management-system' ),
		),
	);
	return $categories;
}
if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
	add_filter( 'block_categories_all', 'masterstudy_lms_gutenberg_blocks_category' );
} else {
	add_filter( 'block_categories', 'masterstudy_lms_gutenberg_blocks_category' );
}
