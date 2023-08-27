<?php
/**
 * Functions for working with custom fields.
 *
 * @package JupiterX\Framework\API\Custom_Fields
 *
 * @since   1.0.0
 */

/**
 * A wrapper for get_field function that allow us to set a default value for it.
 *
 * We return default value only when the post meta is not set for post.
 * Otherwise we return the post meta value even it is empty string or false.
 *
 * @since 1.0.0
 *
 * @param  string  $field          The field name or key.
 * @param  mixed   $default        Optional. The default value to return of the post meta value doesn't exist.
 * @param  mixed   $post_id        Optional. The post_id of which the value is saved against.
 * @param  boolean $format_value   Optional. Whether or not to format the value as described above.
 *
 * @return mixed   $default|$value Default value that passed to function if post meta is null or post meta value.
 */
function jupiterx_get_field( $field, $default = false, $post_id = false, $format_value = false ) {

	if ( ! class_exists( 'acf' ) ) {
		return $default;
	}

	$pre = apply_filters( 'pre_jupiterx_get_field', $default, $post_id );

	if ( is_array( $pre ) && true === $pre['skip'] ) {
		return $pre['value'];
	}

	$value = get_field( $field, $post_id, $format_value );

	/**
	 * Some values are saved as empty string or 0 for fields (e.g true_false fields).
	 * So we used is_null instead of is_empty to check if post meta is set or not.
	 */
	if ( is_null( $value ) ) {
		return $default;
	}

	return $value;
}

/**
 * Jupiter get option.
 *
 * @since 1.0.0
 *
 * @param string $name          The option name.
 * @param mixed  $field_default Custom field default value.
 * @param mixed  $mod_default Theme modification default value.
 * @param bool   $post_id The post ID.
 *
 * @return mixed The option value.
 */
function jupiterx_get_field_mod( $name, $field_default = '', $mod_default = '', $post_id = false ) {
	$value = jupiterx_get_field( $name, $field_default, $post_id );

	if ( $field_default === $value ) {
		$value = get_theme_mod( $name, $mod_default );
	}

	return $value;
}

/**
 * Helper function to generate a unique selector for post.
 *
 * @since 1.0.0
 *
 * @param  string $selector Valid CSS selector to append on unique post selector.
 *
 * @return string $unique_selector Unique selector for targeted element on post.
 */
function jupiterx_get_post_selector( $selector ) {

	if ( ! is_singular() ) {
		return;
	}

	$post_id   = get_the_ID();
	$post_type = get_post_type( $post_id );

	$unique_selector = '.postid-' . $post_id;

	if ( 'page' === $post_type ) {
		$unique_selector = '.page-id-' . $post_id;
	}

	$unique_selector .= ' ' . $selector;

	return $unique_selector;
}

add_filter( 'jupiterx_post_styles', 'jupiterx_post_style_functions' );
/**
 * Adds function names to jupiterx_post_style.
 * Each function returns a string as valid CSS style.
 *
 * @since 1.0.0
 *
 * @param  array $function_names Function names that generating styles.
 *
 * @return array $function_names Modified function names.
 */
function jupiterx_post_style_functions( $function_names ) {

	$function_names[] = 'jupiterx_cf_style_background';
	$function_names[] = 'jupiterx_cf_style_spacing';
	$function_names[] = 'jupiterx_cf_style_header_position';
	$function_names[] = 'jupiterx_cf_style_content_padding';

	return $function_names;
}

/**
 * Generates Background styles based on custom fields.
 *
 * @since 1.0.0
 *
 * @return string Valid CSS
 */
function jupiterx_cf_style_background() {
	$selector = jupiterx_get_post_selector( '.jupiterx-main' );

	if ( ! $selector ) {
		return;
	}

	$css = [];

	$main_background = get_theme_mod( 'jupiterx_site_main_background', [
		'color' => '#fff',
	] );

	$background = jupiterx_get_field( 'jupiterx_main_background' );

	// Color style.
	if ( ! empty( $background['color'] ) ) {
		$css[] = 'background-color: ' . esc_attr( $background['color'] ) . ';';
	}

	// Image style.
	if ( ! empty( $background['image'] ) ) {
		$image = wp_get_attachment_url( $background['image'] );
		$css[] = 'background-image: url(' . esc_url( $image ) . ');';
	}

	$image_props = [
		'position',
		'repeat',
		'attachment',
		'size',
	];

	$custom_props = [];

	foreach ( $image_props as $property ) {
		if ( ! empty( $background[ $property ] ) ) {
			$custom_props[ $property ] = $background[ $property ];
		}
	}

	// Image properties style.
	if ( ! empty( $custom_props ) ) {
		foreach ( $custom_props as $key => $value ) {
			$css[] = 'background-' . esc_attr( $key ) . ': ' . esc_attr( $value ) . ';';
		}
	}

	if ( ! empty( $css ) ) {
		return sprintf( '%1$s { %2$s }', $selector, implode( '', $css ) );
	}
}

/**
 * Generates style for removing the content padding.
 *
 * @since 1.0.0
 *
 * @return string Valid CSS
 */
function jupiterx_cf_style_content_padding() {

	$space = jupiterx_get_field( 'jupiterx_content_spacing', 1 );

	if ( $space ) {
		return;
	}

	$selector = jupiterx_get_post_selector( '.jupiterx-main-content' );

	if ( ! $selector ) {
		return;
	}

	$styles = 'padding-top: 0; padding-bottom: 0;';

	if ( ! empty( $styles ) ) {
		return sprintf(
			'%1$s { %2$s}',
			$selector,
			$styles
		);
	}
}

/**
 * Generates spacing styles based on custom fields.
 *
 * @since 1.0.0
 *
 * @return string Valid CSS
 */
function jupiterx_cf_style_spacing() {

	$space_defaults = array(
		'padding_top'    => '',
		'padding_right'  => '',
		'padding_bottom' => '',
		'padding_left'   => '',
		'margin_top'     => '',
		'margin_right'   => '',
		'margin_bottom'  => '',
		'margin_left'    => '',
	);

	$space = jupiterx_get_field( 'jupiterx_main_spacing' );
	$space = wp_parse_args( $space, $space_defaults );

	$selector = jupiterx_get_post_selector( '.jupiterx-main' );

	if ( ! $selector ) {
		return;
	}

	$styles = '';

	foreach ( $space as $property => $value ) {
		if ( ! empty( $value ) ) {
			$property = str_replace( '_', '-', $property );
			$styles  .= esc_attr( $property ) . ': ' . esc_attr( $value ) . 'px; ';
		}
	}

	if ( ! empty( $styles ) ) {
		return sprintf(
			'%1$s { %2$s}',
			$selector,
			$styles
		);
	}
}

/**
 * Generates style for fixed header in bottom
 *
 * @since 1.0.0
 *
 * @return string Valid CSS
 */
function jupiterx_cf_style_header_position() {
	$header_position = get_theme_mod( 'jupiterx_header_position', 'global' );

	if ( 'global' === jupiterx_get_field( 'jupiterx_header_behavior', 'global' ) ) {
		$header_position = jupiterx_get_field( 'jupiterx_header_position', 'global' );
	}

	$selector = jupiterx_get_post_selector( '.jupiterx-header[data-jupiterx-behavior="fixed"]' );

	if ( ! $selector ) {
		return;
	}

	if ( 'bottom' === $header_position ) {
		return sprintf(
			'%1$s { top: auto !important; bottom: 0; border-bottom-width: 0; }',
			$selector
		);
	}
}

add_filter( 'acf/load_field/name=jupiterx_header_template', 'jupiterx_get_template_field_choices' );
add_filter( 'acf/load_field/name=jupiterx_header_sticky_template', 'jupiterx_get_template_field_choices' );
add_filter( 'acf/load_field/name=jupiterx_footer_template', 'jupiterx_get_template_field_choices' );
add_filter( 'acf/load_field/name=jupiterx_mega_template', 'jupiterx_get_template_field_choices' );
/**
 * Get field templates.
 *
 * @since 1.19.0
 *
 * @param array $field The field params.
 */
function jupiterx_get_template_field_choices( $field ) {

	if ( is_admin() ) {
		$templates = JupiterX_Customizer_Utils::get_templates( $field['template_type'] );

		foreach ( $templates as $key => $value ) {
			$field['choices'][ $key ] = $value;
		}
	}

	return $field;
}

add_filter( 'pre_jupiterx_get_field', 'jupiterx_filter_search_archive_acf_fields', 10, 2 );
/**
 * Filter field values on search & archive pages.
 *
 * @since 1.21.0
 *
 * @param mixed $default Default value.
 * @param mixed $post_id Post Id.
 * @return array
 */
function jupiterx_filter_search_archive_acf_fields( $default, $post_id ) {
	if ( false !== $post_id ) {
		return [
			'skip' => false,
			'value' => $default,
		];
	}

	return [
		'skip' => is_search() || is_archive(),
		'value' => $default,
	];
}
