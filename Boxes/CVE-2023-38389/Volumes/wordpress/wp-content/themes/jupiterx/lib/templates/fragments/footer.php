<?php
/**
 * Echo footer fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_footer_custom', 'jupiterx_get_custom_footer' );
/**
 * Get header settings.
 *
 * @since 1.0.0
 */
function jupiterx_get_custom_footer() {
	$template = jupiterx_get_field_mod( 'jupiterx_footer_template', 'global' );

	if ( 'global' === jupiterx_get_field( 'jupiterx_footer_type', 'global' ) ) {
		$template = get_theme_mod( 'jupiterx_footer_template' );
	}

	// Fallback.
	if ( empty( $template ) && jupiterx_is_preview() ) {
		jupiterx_output_e( 'jupiterx_custom_footer_template_fallback', sprintf(
			'<div class="container"><div class="alert alert-warning" role="alert">%1$s</div></div>',
			__( 'Select a custom footer template.', 'jupiterx' )
		) );
	}

	// Template.
	jupiterx_output_e( 'jupiterx_custom_footer_template', jupiterx_get_custom_template( $template ) );
}

jupiterx_add_smart_action( 'jupiterx_footer_before_markup', 'jupiterx_footer_behavior' );
/**
 * Apply footer display type.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_behavior() {

	$footer_behavior = jupiterx_get_field_mod( 'jupiterx_footer_behavior', 'global', 'static' );

	$footer_behavior = apply_filters( 'jupiterx_footer_behavior', $footer_behavior );

	if ( 'full_width' === jupiterx_get_field_mod( 'jupiterx_site_width', 'global', 'full_width' ) && 'fixed' === $footer_behavior ) {
		jupiterx_add_attribute( 'jupiterx_footer', 'class', 'jupiterx-footer-fixed' );
	}
}

jupiterx_add_smart_action( 'jupiterx_footer', 'jupiterx_footer_widgets' );
/**
 * Echo the footer content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_widgets() {
	// Do not render if footer is not enabled.
	if ( ! jupiterx_is_footer_enabled() ) {
		return;
	}

	$layout_columns = jupiterx_get_footer_layout_columns();

	$columns_type = str_replace( '_', '-', $layout_columns );

	jupiterx_open_markup_e( 'jupiterx_footer_widgets', 'div', 'class=jupiterx-footer-widgets jupiterx-' . $columns_type );

		jupiterx_open_markup_e( 'jupiterx_fixed_wrap[_footer]', 'div', 'class=' . jupiterx_get_footer_container_class() );

			jupiterx_open_markup_e( 'jupiterx_footer_widgets_row', 'div', 'class=row' );

				$columns_pattern = jupiterx_get_footer_columns_pattern( $layout_columns );

				foreach ( $columns_pattern as $index => $column_class ) { // @codingStandardsIgnoreLine

					$index++;

					jupiterx_open_markup_e( 'jupiterx_footer_widgets_column[' . $index . ']', 'div', 'class=' . $column_class );

						echo jupiterx_widget_area( 'footer_widgets_column_' . $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					jupiterx_close_markup_e( 'jupiterx_footer_widgets_column[' . $index . ']', 'div' );

				} // phpcs:ignore

			jupiterx_close_markup_e( 'jupiterx_footer_widgets_row', 'div' );

		jupiterx_close_markup_e( 'jupiterx_fixed_wrap[_footer]', 'div' );

	jupiterx_close_markup_e( 'jupiterx_footer_widgets', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_footer', 'jupiterx_subfooter' );
/**
 * Echo the sub footer content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_subfooter() {
	if ( ! jupiterx_is_footer_sub_enabled() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_subfooter', 'div', 'class=jupiterx-subfooter' );

		jupiterx_open_markup_e( 'jupiterx_fixed_wrap[_subfooter]', 'div', 'class=' . jupiterx_get_footer_sub_container_class() );

			jupiterx_open_markup_e( 'jupiterx_subfooter_row', 'div', [ 'class' => 'row' ] );

				$elements = get_theme_mod( 'jupiterx_footer_sub_elements', [ 'menu', 'copyright' ] );

				$sort_content = get_theme_mod( 'jupiterx_footer_sub_sort_content', [ 'sub_menu', 'sub_copyright' ] );

				foreach ( $sort_content as $index => $content ) {  // phpcs:ignore

					$func_name = 'jupiterx_footer_' . $content;

					// Remove the `sub_` prefix.
					$content_name = str_replace( 'sub_', '', $content );

					if ( function_exists( $func_name ) && in_array( $content_name, $elements ) ) {  // phpcs:ignore

						$class = 0 === $index ? 'col-md' : 'col-md-auto';

						jupiterx_open_markup_e( "jupiterx_subfooter_column[$content]", 'div', 'class=' . $class );

						$func_name();

						jupiterx_close_markup_e( "jupiterx_subfooter_column[$content]", 'div' );

					}  // phpcs:ignore

				}  // phpcs:ignore

			jupiterx_close_markup_e( 'jupiterx_subfooter_row', 'div' );

		jupiterx_close_markup_e( 'jupiterx_fixed_wrap[_footer]', 'div' );

	jupiterx_close_markup_e( 'jupiterx_subfooter', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_footer_after_markup', 'jupiterx_scroll_top_button' );
/**
 * Echo the scroll top button content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_scroll_top_button() {
	if ( ! get_theme_mod( 'jupiterx_site_scroll_top', true ) ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_corner_buttons', 'div', [
		'class' => 'jupiterx-corner-buttons',
		'data-jupiterx-scroll' => wp_json_encode( [ 'offset' => 1000 ] ),
	] );

		jupiterx_open_markup_e( 'jupiterx_scroll_top_button', 'button', [
			'class' => 'jupiterx-scroll-top jupiterx-icon-angle-up',
			'data-jupiterx-scroll-target' => 0,
		] );

		jupiterx_close_markup_e( 'jupiterx_scroll_top_button', 'button' );

	jupiterx_close_markup_e( 'jupiterx_corner_buttons', 'div' );
}

jupiterx_add_smart_action( 'wp_print_footer_scripts', 'jupiterx_replace_nojs_class' );
/**
 * Print inline JavaScript in the footer to replace the 'no-js' class with 'js'.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_replace_nojs_class() {
	?><script type="text/javascript">
		(function() {
			document.body.className = document.body.className.replace('no-js','js');
		}());
	</script>
	<?php
}
