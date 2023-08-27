<?php
/**
 * Add Jupiter Post Options > Title Bar meta options.
 *
 * @package JupiterX_Core\Custom_fields
 *
 * @since 1.9.0
 */

add_action( 'jupiterx_custom_field_post_types', 'jupiterx_add_title_bar_field' );
/**
 * Add title bar field to the new page/post/portfolio. Add due to prevent content creation rule.
 *
 * @since 1.9.0
 *
 * @return void
 */
function jupiterx_add_title_bar_field() {
	if ( ! class_exists( 'acf' ) ) {
		return;
	}
	$key    = 'field_jupiterx_post_title_bar';
	$parent = 'group_jupiterx_post';

	acf_add_local_field( [
		'key'    => "{$key}_subtitle",
		'parent' => $parent,
		'label'  => __( 'Subtitle', 'jupiterx-core' ),
		'name'   => 'jupiterx_title_bar_subtitle',
		'type'   => 'textarea',
		'rows'   => '3',
	] );
}
