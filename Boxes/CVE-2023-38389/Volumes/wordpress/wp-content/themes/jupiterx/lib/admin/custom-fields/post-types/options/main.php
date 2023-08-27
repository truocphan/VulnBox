<?php
/**
 * Add Jupiter Post Options > Main meta options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

$key                = 'field_jupiterx_post_main';
$parent             = 'group_jupiterx_post';
$jupiterx_post_type = get_post_type( jupiterx_get( 'post' ) ); // @codingStandardsIgnoreLine

if ( is_admin() && empty( $jupiterx_post_type ) && isset( $_GET['post_type'] ) ) { // @codingStandardsIgnoreLine
	$jupiterx_post_type = sanitize_text_field( $_GET['post_type'] ); // @codingStandardsIgnoreLine
}

// Main tab.
acf_add_local_field( [
	'key'    => "{$key}_tab",
	'parent' => $parent,
	'label'  => __( 'Main', 'jupiterx' ),
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
		__( '%1$s <a class="jupiterx-acf-notice-link" href="%2$s" target="_blank"><strong> %3$s <span class=" dashicons dashicons-external"></span></strong></a> %4$s', 'jupiterx' ),
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

if ( in_array( $jupiterx_post_type, [ 'post', 'page' ], true ) || empty( $jupiterx_post_type ) ) {
	acf_add_local_field( [
		'key'       => "{$key}_instruction",
		'parent'    => $parent,
		'label'     => '',
		'name'      => '',
		'type'      => 'message',
		'message'   => sprintf(
			/* translators: field name & Meta box name */
			__( 'To have a blank page for page builders, choose a proper <strong> %1$s </strong> from <strong> %2$s </strong>.', 'jupiterx' ),
			__( 'Template', 'jupiterx' ),
			__( 'Post/Page Attributes', 'jupiterx' )
		),
		'new_lines' => 'wpautop',
		'esc_html'  => 0,
		'wrapper'   => [
			'class'     => 'jupiterx-meta-instruction',
		],
		'required'  => 0,
	] );
}

// Post layout.
acf_add_local_field( [
	'key'           => "{$key}_layout",
	'parent'        => $parent,
	'label'         => __( 'Layout', 'jupiterx' ),
	'name'          => 'jupiterx_layout',
	'type'          => 'select',
	'wrapper'       => [ 'width' => '50' ],
	'choices'       => JupiterX_Customizer_Utils::get_layouts( [
		'global' => __( 'Global', 'jupiterx' ),
	] ),
	'default_value' => 'global',
	'required'      => 0,
] );

// Full width.
acf_add_local_field( [
	'key'           => "{$key}_full_width",
	'parent'        => $parent,
	'label'         => __( 'Full Width', 'jupiterx' ),
	'name'          => 'jupiterx_content_full_width',
	'type'          => 'true_false',
	'wrapper'       => [ 'width' => '25' ],
	'ui'            => 1,
	'required'      => 0,
] );

// Page content spacing.
if ( in_array( $jupiterx_post_type, [ 'post', 'page' ], true ) || empty( $jupiterx_post_type ) ) {
	acf_add_local_field( [
		'key'           => "{$key}_content_spacing",
		'parent'        => $parent,
		'label'         => __( 'Content Spacing', 'jupiterx' ),
		'name'          => 'jupiterx_content_spacing',
		'type'          => 'true_false',
		'wrapper'       => [ 'width' => '25' ],
		'default_value' => '1',
		'ui'            => 1,
		'required'      => 0,
	] );
}

// Sidebar primary.
acf_add_local_field( [
	'key'               => "{$key}_sidebar_primary",
	'parent'            => $parent,
	'label'             => __( 'Sidebar Primary', 'jupiterx' ),
	'name'              => 'jupiterx_sidebar_primary',
	'type'              => 'widget_area',
	'conditional_logic' => [
		[
			[
				'field'    => "{$key}_layout",
				'operator' => '!=',
				'value'    => 'c',
			],
		],
	],
	'wrapper'           => [ 'width' => '50' ],
	'default_value'     => 'global',
	'required'          => 0,
] );

// Sidebar secondary.
acf_add_local_field( [
	'key'               => "{$key}_sidebar_secondary",
	'parent'            => $parent,
	'label'             => __( 'Sidebar Secondary', 'jupiterx' ),
	'name'              => 'jupiterx_sidebar_secondary',
	'type'              => 'widget_area',
	'conditional_logic' => [
		[
			[
				'field'    => "{$key}_layout",
				'operator' => '==',
				'value'    => 'global',
			],
		],
		[
			[
				'field'    => "{$key}_layout",
				'operator' => '==',
				'value'    => 'sp_ss_c',
			],
		],
		[
			[
				'field'    => "{$key}_layout",
				'operator' => '==',
				'value'    => 'c_sp_ss',
			],
		],
		[
			[
				'field'    => "{$key}_layout",
				'operator' => '==',
				'value'    => 'sp_c_ss',
			],
		],
	],
	'wrapper'           => [ 'width' => '50' ],
	'default_value'     => 'global',
	'required'          => 0,
] );

// Divider.
acf_add_local_field( [
	'key'    => "{$key}_divider",
	'parent' => $parent,
	'type'   => 'jupiterx-divider',
] );

// Featured image.
acf_add_local_field( [
	'key'           => "{$key}_featured_image",
	'parent'        => $parent,
	'label'         => __( 'Featured Image', 'jupiterx' ),
	'name'          => 'jupiterx_post_featured_image',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '25' ],
	'choices'       => [
		'global'  => __( 'Global', 'jupiterx' ),
		'1'       => __( 'Yes', 'jupiterx' ),
		''        => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
	'required'      => 0,
] );

// Meta - Post & Portfolio.
if ( 'page' !== $jupiterx_post_type ) {
	acf_add_local_field( [
		'key'           => "{$key}_meta",
		'parent'        => $parent,
		'label'         => __( 'Meta', 'jupiterx' ),
		'name'          => 'jupiterx_post_meta',
		'type'          => 'button_group',
		'wrapper'       => [ 'width' => '25' ],
		'choices'       => [
			'global'  => __( 'Global', 'jupiterx' ),
			'1'       => __( 'Yes', 'jupiterx' ),
			''        => __( 'No', 'jupiterx' ),
		],
		'default_value' => 'global',
		'required'      => 0,
	] );
}

// Tags - Post & Portfolio.
if ( 'page' !== $jupiterx_post_type ) {
	acf_add_local_field( [
		'key'           => "{$key}_tags",
		'parent'        => $parent,
		'label'         => __( 'Tags', 'jupiterx' ),
		'name'          => 'jupiterx_post_tags',
		'type'          => 'button_group',
		'wrapper'       => [ 'width' => '25' ],
		'choices'       => [
			'global'  => __( 'Global', 'jupiterx' ),
			'1'       => __( 'Yes', 'jupiterx' ),
			''        => __( 'No', 'jupiterx' ),
		],
		'default_value' => 'global',
		'required'      => 0,
	] );
}

// Social share - Post & Portfolio & Page.
acf_add_local_field( [
	'key'           => "{$key}_social_share",
	'parent'        => $parent,
	'label'         => __( 'Social Share', 'jupiterx' ),
	'name'          => 'jupiterx_post_social_share',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '25' ],
	'choices'       => [
		'global'  => __( 'Global', 'jupiterx' ),
		'1'       => __( 'Yes', 'jupiterx' ),
		''        => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
	'required'      => 0,
] );

// Author box - Post.
if ( ! in_array( $jupiterx_post_type, [ 'portfolio', 'page' ], true ) ) {
	acf_add_local_field( [
		'key'           => "{$key}_author_box",
		'parent'        => $parent,
		'label'         => __( 'Author Box', 'jupiterx' ),
		'name'          => 'jupiterx_post_author_box',
		'type'          => 'button_group',
		'wrapper'       => [ 'width' => '25' ],
		'choices'       => [
			'global'  => __( 'Global', 'jupiterx' ),
			'1'       => __( 'Yes', 'jupiterx' ),
			''        => __( 'No', 'jupiterx' ),
		],
		'default_value' => 'global',
		'required'      => 0,
	] );
}

// Related posts - Post & Portfolio.
if ( 'page' !== $jupiterx_post_type ) {
	acf_add_local_field( [
		'key'           => "{$key}_related_posts",
		'parent'        => $parent,
		'label'         => __( 'Related Posts', 'jupiterx' ),
		'name'          => 'jupiterx_post_related_posts',
		'type'          => 'button_group',
		'wrapper'       => [ 'width' => '25' ],
		'choices'       => [
			'global'  => __( 'Global', 'jupiterx' ),
			'1'       => __( 'Yes', 'jupiterx' ),
			''        => __( 'No', 'jupiterx' ),
		],
		'default_value' => 'global',
		'required'      => 0,
	] );
}

// Comments.
acf_add_local_field( [
	'key'           => "{$key}_comments",
	'parent'        => $parent,
	'label'         => __( 'Comments', 'jupiterx' ),
	'name'          => 'jupiterx_post_comments',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '25' ],
	'choices'       => [
		'global'  => __( 'Global', 'jupiterx' ),
		'1'       => __( 'Yes', 'jupiterx' ),
		''        => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
	'required'      => 0,
] );

// Styles accordion.
acf_add_local_field( [
	'key'           => "{$key}_accordion",
	'parent'        => $parent,
	'label'         => 'Styles',
	'type'          => 'accordion',
	'open'          => true,
] );

// Background group.
acf_add_local_field( [
	'key'        => "{$key}_background",
	'parent'     => $parent,
	'name'       => 'jupiterx_main_background',
	'type'       => 'group',
	'layout'     => 'block',
	'sub_fields' => [
		[ // Color.
			'key'      => 'color',
			'parent'   => $parent,
			'label'    => __( 'Background Color', 'jupiterx' ),
			'name'     => 'color',
			'_name'    => 'color',
			'type'     => 'color_picker',
			'wrapper'  => [ 'width' => '50' ],
			'required' => 0,
		],
		[ // Image.
			'key'           => 'image',
			'parent'        => $parent,
			'label'         => __( 'Background Image', 'jupiterx' ),
			'name'          => 'image',
			'_name'         => 'image',
			'type'          => 'image',
			'wrapper'       => [ 'width' => '50' ],
			'return_format' => 'url',
			'required'      => 0,
		],
		[ // Position.
			'key'     => 'position',
			'parent'  => $parent,
			'label'   => __( 'Position', 'jupiterx' ),
			'name'    => 'position',
			'_name'   => 'position',
			'type'    => 'select',
			'wrapper' => [ 'width' => '50' ],
			'choices' => [
				''             => __( 'Global', 'jupiterx' ),
				'top left'     => __( 'Top Left', 'jupiterx' ),
				'top'          => __( 'Top Center', 'jupiterx' ),
				'top right'    => __( 'Top Right', 'jupiterx' ),
				'center left'  => __( 'Center Left', 'jupiterx' ),
				'center'       => __( 'Center', 'jupiterx' ),
				'center right' => __( 'Center Right', 'jupiterx' ),
				'bottom left'  => __( 'Bottom Left', 'jupiterx' ),
				'bottom'       => __( 'Bottom center', 'jupiterx' ),
				'bottom right' => __( 'Bottom Right', 'jupiterx' ),
			],
			'required' => 0,
		],
		[ // Repeat.
			'key'     => 'repeat',
			'parent'  => $parent,
			'label'   => __( 'Repeat', 'jupiterx' ),
			'name'    => 'repeat',
			'_name'   => 'repeat',
			'type'    => 'select',
			'wrapper' => [ 'width' => '50' ],
			'choices' => [
				''          => __( 'Global', 'jupiterx' ),
				'no-repeat' => __( 'No Repeat', 'jupiterx' ),
				'repeat'    => __( 'Repeat', 'jupiterx' ),
				'repeat-x'  => __( 'Repeat Horizontally', 'jupiterx' ),
				'repeat-y'  => __( 'Repeat Vertically', 'jupiterx' ),
			],
			'required' => 0,
		],
		[ // Attachment.
			'key'     => 'attachment',
			'parent'  => $parent,
			'label'   => __( 'Fixed', 'jupiterx' ),
			'name'    => 'attachment',
			'_name'   => 'attachment',
			'type'    => 'button_group',
			'wrapper' => [ 'width' => '50' ],
			'choices' => [
				''       => __( 'Global', 'jupiterx' ),
				'fixed'  => __( 'Yes', 'jupiterx' ),
				'scroll' => __( 'No', 'jupiterx' ),
			],
			'required' => 0,
		],
		[ // Size.
			'key'     => 'size',
			'parent'  => $parent,
			'label'   => __( 'Cover', 'jupiterx' ),
			'name'    => 'size',
			'_name'   => 'size',
			'type'    => 'button_group',
			'wrapper' => [ 'width' => '50' ],
			'choices' => [
				''      => __( 'Global', 'jupiterx' ),
				'cover' => __( 'Yes', 'jupiterx' ),
				'auto'  => __( 'No', 'jupiterx' ),
			],
			'required' => 0,
		],
	],
] );

// Margin & Padding group.
acf_add_local_field( [
	'key'        => "{$key}_spacing",
	'parent'     => $parent,
	'name'       => 'jupiterx_main_spacing',
	'type'       => 'group',
	'layout'     => 'block',
	'sub_fields' => [
		[ // Padding top.
			'key'      => 'padding_top',
			'parent'   => $parent,
			'label'    => __( 'Padding Top', 'jupiterx' ),
			'name'     => 'padding_top',
			'_name'    => 'padding_top',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Padding right.
			'key'      => 'padding_right',
			'parent'   => $parent,
			'label'    => __( 'Padding Right', 'jupiterx' ),
			'name'     => 'padding_right',
			'_name'    => 'padding_right',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Padding bottom.
			'key'      => 'padding_bottom',
			'parent'   => $parent,
			'label'    => __( 'Padding Bottom', 'jupiterx' ),
			'name'     => 'padding_bottom',
			'_name'    => 'padding_bottom',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Padding left.
			'key'      => 'padding_left',
			'parent'   => $parent,
			'label'    => __( 'Padding Left', 'jupiterx' ),
			'name'     => 'padding_left',
			'_name'    => 'padding_left',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Margin top.
			'key'      => 'margin_top',
			'parent'   => $parent,
			'label'    => __( 'Margin Top', 'jupiterx' ),
			'name'     => 'margin_top',
			'_name'    => 'margin_top',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Margin right.
			'key'      => 'margin_right',
			'parent'   => $parent,
			'label'    => __( 'Margin Right', 'jupiterx' ),
			'name'     => 'margin_right',
			'_name'    => 'margin_right',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Margin bottom.
			'key'      => 'margin_bottom',
			'parent'   => $parent,
			'label'    => __( 'Margin Bottom', 'jupiterx' ),
			'name'     => 'margin_bottom',
			'_name'    => 'margin_bottom',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
		[ // Margin left.
			'key'      => 'margin_left',
			'parent'   => $parent,
			'label'    => __( 'Margin Left', 'jupiterx' ),
			'name'     => 'margin_left',
			'_name'    => 'margin_left',
			'type'     => 'number',
			'wrapper'  => [ 'width' => '25' ],
			'append'   => 'px',
			'required' => 0,
		],
	],
] );

// Styles accordion end.
acf_add_local_field( [
	'key'      => "{$key}_accordion_end",
	'parent'   => $parent,
	'label'    => 'Styles',
	'type'     => 'accordion',
	'endpoint' => 1,
] );
