<?php
/**
 * Echo widget fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_widget', 'jupiterx_widget_badge', 5 );
/**
 * Echo widget badge.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_widget_badge() {

	if ( ! jupiterx_get_widget( 'badge' ) ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_widget_badge' . _jupiterx_widget_subfilters(), 'div', 'class=badge badge-secondary' );

		echo jupiterx_widget_shortcodes( jupiterx_get_widget( 'badge_content' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.

	jupiterx_close_markup_e( 'jupiterx_widget_badge' . _jupiterx_widget_subfilters(), 'div' );
}

jupiterx_add_smart_action( 'jupiterx_widget', 'jupiterx_widget_title' );
/**
 * Echo widget title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_widget_title() {
	$title = jupiterx_get_widget( 'title' );

	if ( ! $title || ! jupiterx_get_widget( 'show_title' ) ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_widget_title' . _jupiterx_widget_subfilters(), 'h3', 'class=card-title' );

		jupiterx_output_e( 'jupiterx_widget_title_text', $title );

	jupiterx_close_markup_e( 'jupiterx_widget_title' . _jupiterx_widget_subfilters(), 'h3' );
}

jupiterx_add_smart_action( 'jupiterx_widget', 'jupiterx_widget_content', 15 );
/**
 * Echo widget content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_widget_content() {
	jupiterx_open_markup_e( 'jupiterx_widget_content' . _jupiterx_widget_subfilters(), 'div', 'class=jupiterx-widget-content' );

		jupiterx_output_e( 'jupiterx_widget_content' . _jupiterx_widget_subfilters(), jupiterx_get_widget( 'content' ) );

	jupiterx_close_markup_e( 'jupiterx_widget_content' . _jupiterx_widget_subfilters(), 'div' );
}

add_filter( 'elementor/widgets/wordpress/widget_args', 'jupiterx_elementor_widget_args', 10 );
/**
 * Filter the Elementor dynamic sidebars.
 *
 * @since 1.0.0
 *
 * @param array $default_widget_args The default widget args.
 *
 * @return array $default_widget_args
 */
function jupiterx_elementor_widget_args( $default_widget_args ) {
	$default_widget_args['before_title'] = '<h3 class="card-title">';
	$default_widget_args['after_title']  = '</h3>';

	return $default_widget_args;
}

add_filter( 'elementor/widget/render_content', 'jupiterx_elementor_widget_content', 10, 2 );
/**
 * Filter the Elementor WordPress widget content.
 *
 * @since 1.0.0
 *
 * @param array  $content The widget content.
 * @param object $widget  The widget instance.
 */
function jupiterx_elementor_widget_content( $content, $widget ) {

	if ( ! method_exists( $widget, 'get_widget_instance' ) ) {
		return $content;
	}

	$id_base = esc_attr( $widget->get_widget_instance()->id_base );
	$class   = [ 'jupiterx-widget' ];
	$class[] = esc_attr( 'widget_' . $id_base );

	if ( strpos( $id_base, 'woocommerce' ) !== false ) {
		$class[] = 'woocommerce';
	}

	jupiterx_open_markup_e( 'jupiterx_widget[_' . $id_base . ']', 'div', 'class=' . implode( ' ', $class ) );

		jupiterx_open_markup_e( 'jupiterx_widget_content[_' . $id_base . ']', 'div', 'class=jupiterx-widget-content' );

			jupiterx_output_e( 'jupiterx_widget_content[_' . $id_base . ']', $content );

		jupiterx_close_markup_e( 'jupiterx_widget_content[_' . $id_base . ']', 'div' );

	jupiterx_close_markup_e( 'jupiterx_widget[_' . $id_base . ']', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_after_widget', 'jupiterx_sidebar_widget_divider', 20 );
/**
 * Echo widget divider.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_sidebar_widget_divider() {
	global $_jupiterx_widget_area;

	if ( 'sidebar_primary' !== $_jupiterx_widget_area['id'] && 'sidebar_secondary' !== $_jupiterx_widget_area['id'] ) {
		return;
	}

	$settings = get_theme_mod( 'jupiterx_sidebar_divider_widgets', [] );

	if ( ! isset( $settings['width'] ) || '' === $settings['width'] ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_sidebar_widget_divider', 'span', 'class=jupiterx-widget-divider' );

	jupiterx_close_markup_e( 'jupiterx_sidebar_widget_divider', 'span' );
}

jupiterx_add_smart_action( 'jupiterx_after_widget', 'jupiterx_footer_widget_divider', 20 );
/**
 * Echo widget divider.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_widget_divider() {
	global $_jupiterx_widget_area;

	if ( false === strpos( $_jupiterx_widget_area['id'], 'footer_widgets_column' ) ) {
		return;
	}

	$settings = get_theme_mod( 'jupiterx_footer_widgets_divider', [] );

	if ( ! isset( $settings['width'] ) || '' === $settings['width'] ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_footer_widget_divider', 'span', 'class=jupiterx-widget-divider' );

	jupiterx_close_markup_e( 'jupiterx_footer_widget_divider', 'span' );
}

jupiterx_add_smart_action( 'jupiterx_no_widget', 'jupiterx_no_widget' );
/**
 * Echo no widget content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_no_widget() {

	// Only apply this notice to sidebar_primary and sidebar_secondary.
	if ( ! in_array( jupiterx_get_widget_area( 'id' ), array( 'sidebar_primary', 'sidebar_secondary' ), true ) ) {
		return;
	}

	if ( ! jupiterx_is_preview() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_no_widget_notice', 'p', array( 'class' => 'alert alert-warning' ) );

		jupiterx_output_e(
			'jupiterx_no_widget_notice_text',
			// translators: Name of the widget area.
			sprintf( __( '%s does not have any widget assigned!', 'jupiterx' ), jupiterx_get_widget_area( 'name' ) )
		);

	jupiterx_close_markup_e( 'jupiterx_no_widget_notice', 'p' );
}

jupiterx_add_filter( 'jupiterx_widget_content_attributes', 'jupiterx_modify_widget_content_attributes' );
/**
 * Modify core widgets content attributes, so they use the default UIKit styling.
 *
 * @since 1.0.0
 *
 * @param array $attributes The current widget attributes.
 *
 * @return array The modified widget attributes.
 */
function jupiterx_modify_widget_content_attributes( $attributes ) {
	$type = jupiterx_get_widget( 'type' );

	$target = array(
		'archives',
		'categories',
		'links',
		'meta',
		'pages',
		'recent-posts',
		'recent-comments',
	);

	return $attributes;
}

jupiterx_add_filter( 'jupiterx_widget_content_rss_output', 'jupiterx_widget_rss_content' );
/**
 * Modify widget RSS content.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The widget RSS content.
 */
function jupiterx_widget_rss_content( $content ) {

	$options = jupiterx_get_widget( 'options' );

	$content .= '<p><a class="btn btn-light" href="' . esc_url( jupiterx_get( 'url', $options ) ) . '" target="_blank">' . __( 'Read full feed', 'jupiterx' ) . '</a><p>';

	$content = preg_replace( '/<a .*?class=.?rsswidget.?/', "$0 target='_blank'", $content );
	$content = preg_replace( '/<li/', "$0 class='jupiterx-icon-angle-right'", $content );

	return $content;
}

jupiterx_add_filter( 'jupiterx_widget_content_recent-comments_output', 'jupiterx_widget_recent_comments_content' );
jupiterx_add_filter( 'jupiterx_widget_content_jupiterx_posts_output', 'jupiterx_widget_recent_comments_content' );
/**
 * Modify widget comments content.
 *
 * @since 1.0.0
 *
 * @todo Refactor to use HTML Parser.
 *
 * @param string $content The widget content.
 *
 * @return string The widget comments content.
 */
function jupiterx_widget_recent_comments_content( $content ) {

	$content = preg_replace( '/<li class="*"/', '$0jupiterx-recent-comment ', $content );

	$content = preg_replace( '/<span class="comment-author-link"/', '<span class="jupiterx-icon-solid-comment comment-author-link"', $content );

	return $content;
}

jupiterx_add_filter( 'jupiterx_widget_content_categories_output', 'jupiterx_modify_widget_count' );
jupiterx_add_filter( 'jupiterx_widget_content_archives_output', 'jupiterx_modify_widget_count' );
/**
 * Modify widget count.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_modify_widget_count( $content ) {
	$count = jupiterx_output( 'jupiterx_widget_count', '$1' );

	if ( true === jupiterx_get( 'dropdown', jupiterx_get_widget( 'options' ) ) ) {
		$output = $count;
	} else {
		$output  = jupiterx_open_markup( 'jupiterx_widget_count', 'span', 'class=jupiterx-count' );
		$output .= $count;
		$output .= jupiterx_close_markup( 'jupiterx_widget_count', 'span' );
	}

	// Keep closing tag to avoid overwriting the inline JavaScript.
	return preg_replace( '#>((\s|&nbsp;)\((.*)\))#', '>' . $output, $content );
}

jupiterx_add_filter( 'jupiterx_widget_content_categories_output', 'jupiterx_remove_widget_dropdown' );
jupiterx_add_filter( 'jupiterx_widget_content_archives_output', 'jupiterx_remove_widget_dropdown' );
/**
 * Modify widget dropdown select & label.
 *
 * @since 1.0.0
 *
 * @todo Refactor to use HTML Parser.
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function jupiterx_remove_widget_dropdown( $content ) {

	// Category dropdown.
	$content = str_replace( 'postform', 'postform form-control form-control-sm', $content );

	// Archive dropdown.
	$content = str_replace( 'archive-dropdown"', 'archive-dropdown" class="form-control form-control-sm"', $content );

	// Remove label.
	$content = preg_replace( '#<label([^>]*)class="screen-reader-text"(.*?)>(.*?)</label>#', '', $content );

	return $content;
}

jupiterx_add_filter( 'jupiterx_widget_content_nav_menu_output', 'jupiterx_remove_widget_menu' );
/**
 * Modify widget menu.
 *
 * @since 1.0.0
 *
 * @todo Refactor to use HTML Parser.
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function jupiterx_remove_widget_menu( $content ) {

	$content = str_replace( 'menu-item-has-children', 'menu-item-has-children jupiterx-icon-plus', $content );

	return $content;
}

jupiterx_add_filter( 'wp_generate_tag_cloud_data', 'jupiterx_modify_widget_tag_cloud' );
/**
 * Add custom class to tag cloud links.
 *
 * @since 1.0.0
 *
 * @param array $tags_data The term data for term used to generate the tag cloud.
 *
 * @return array The modified term data.
 */
function jupiterx_modify_widget_tag_cloud( $tags_data ) {

	foreach ( $tags_data as $key => $tag ) {
		$tags_data[ $key ]['class'] .= ' btn btn-light';
	}

	return $tags_data;
}

jupiterx_add_filter( 'jupiterx_widget_content_woocommerce_product_search_output', 'jupiterx_modify_widget_woocommerce_search' );
/**
 * Modify WooCommerce search widget.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function jupiterx_modify_widget_woocommerce_search( $content ) {

	$content = str_replace( 'class="woocommerce-product-search', 'class="woocommerce-product-search jupiterx-search-form form-inline', $content );

	$content = str_replace( 'class="search-field', 'class="search-field form-control', $content );

	$content = str_replace( 'type="submit"', 'type="submit" class="btn jupiterx-icon-search-1"', $content );

	$content = preg_replace( '/(<button.*.">)(.*)/', '$1</button>', $content );

	return $content;
}


jupiterx_add_filter( 'jupiterx_widget_content_woocommerce_product_categories_output', 'jupiterx_modify_widget_woocommerce_categories' );
/**
 * Modify WooCommerce product category widget.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function jupiterx_modify_widget_woocommerce_categories( $content ) {
	$content = str_replace( 'cat-parent"', 'cat-parent jupiterx-icon-plus"', $content );

	return $content;
}

jupiterx_add_filter( 'jupiterx_widget_content_woocommerce_price_filter_output', 'jupiterx_modify_widget_woocommerce_price_filter' );
/**
 * Modify WooCommerce price filter widget.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function jupiterx_modify_widget_woocommerce_price_filter( $content ) {

	$content = str_replace( 'class="button', 'class="btn btn-primary btn-sm', $content );

	return $content;
}
