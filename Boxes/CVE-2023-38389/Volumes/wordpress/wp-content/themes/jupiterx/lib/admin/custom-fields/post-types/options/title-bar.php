<?php
/**
 * Add Jupiter Post Options > Title Bar meta options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

$key    = 'field_jupiterx_post_title_bar';
$parent = 'group_jupiterx_post';

// Title bar.
acf_add_local_field( [
	'key'    => "{$key}_tab",
	'parent' => $parent,
	'label'  => __( 'Title Bar', 'jupiterx' ),
	'type'   => 'tab',
] );

acf_add_local_field( [
	'key'       => "{$key}_layout_builder_notice",
	'parent'    => $parent,
	'label'     => '',
	'name'      => '',
	'type'      => 'message',
	'message'   => sprintf(
		/* translators: field name & Meta box name */
		__( '%1$s <a class="jupiterx-acf-notice-link" href="%2$s" target="_blank"><strong>%3$s<span class=" dashicons dashicons-external"></span></strong></a> %4$s', 'jupiterx' ),
		__( 'It’s recommended to use the new ', 'jupiterx' ),
		esc_url( admin_url( 'admin.php?page=jupiterx#/layout-builder' ) ),
		__( 'Layout Builder', 'jupiterx' ),
		__( 'feature.', 'jupiterx' )
	),
	'new_lines' => 'wpautop',
	'esc_html'  => 0,
	'wrapper'   => [
		'class'     => 'jupiterx-meta-instruction',
	],
	'required'  => 0,
] );

// Title.
acf_add_local_field( [
	'key'           => "{$key}_title",
	'parent'        => $parent,
	'label'         => __( 'Title', 'jupiterx' ),
	'name'          => 'jupiterx_title_bar_title',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '50' ],
	'choices'       => [
		'global' => __( 'Global', 'jupiterx' ),
		'1'      => __( 'Yes', 'jupiterx' ),
		''       => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
] );

// Breadcrumb.
acf_add_local_field( [
	'key'           => "{$key}_breadcrumb",
	'parent'        => $parent,
	'label'         => __( 'Breadcrumb', 'jupiterx' ),
	'name'          => 'jupiterx_title_bar_breadcrumb',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '50' ],
	'choices'       => [
		'global' => __( 'Global', 'jupiterx' ),
		'1'      => __( 'Yes', 'jupiterx' ),
		''       => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
] );

do_action( 'jupiterx_custom_field_post_types' );
