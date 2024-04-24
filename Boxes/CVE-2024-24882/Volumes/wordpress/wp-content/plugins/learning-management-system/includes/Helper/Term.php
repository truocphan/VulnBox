<?php

/**
 * Get full list of course visibilty term ids.
 *
 * @since  1.0.0
 * @return int[]
 */
function masteriyo_get_course_visibility_term_ids() {
	if ( ! taxonomy_exists( 'course_visibility' ) ) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'course_visibility',
			'hide_empty' => false,
		)
	);

	$terms = wp_list_pluck( $terms, 'term_taxonomy_id', 'name' );

	$terms = wp_parse_args(
		$terms,
		array(
			'exclude-from-catalog' => 0,
			'exclude-from-search'  => 0,
			'featured'             => 0,
			'outofstock'           => 0,
			'rated-1'              => 0,
			'rated-2'              => 0,
			'rated-3'              => 0,
			'rated-4'              => 0,
			'rated-5'              => 0,
		)
	);
	return array_map( 'absint', $terms );
}
