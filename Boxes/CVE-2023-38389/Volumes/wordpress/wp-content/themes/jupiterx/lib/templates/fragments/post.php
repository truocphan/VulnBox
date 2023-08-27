<?php
/**
 * Echo post fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_main_header_content', 'jupiterx_main_header_post_title' );
/**
 * Echo page title bar post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_main_header_post_title() {
	if ( ! is_singular() ) {
		return;
	}

	$title     = jupiterx_output( 'jupiterx_main_header_post_title_text', get_the_title() );
	$title_tag = 'h1';

	if ( empty( $title ) ) {
		return;
	}

	jupiterx_open_markup_e(
		'jupiterx_main_header_post_title',
		$title_tag,
		array(
			'class'    => 'jupiterx-main-header-post-title',
			'itemprop' => 'headline',
		)
	);

		echo wp_kses_post( $title ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.

	jupiterx_close_markup_e( 'jupiterx_main_header_post_title', $title_tag );
}

jupiterx_add_smart_action( 'jupiterx_post_header', 'jupiterx_post_title' );
/**
 * Echo post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_title() {
	$title     = jupiterx_output( 'jupiterx_post_title_text', get_the_title() );
	$title_tag = 'h1';

	if ( empty( $title ) || is_singular() && ! jupiterx_post_element_enabled( 'title' ) ) {
		return;
	}

	if ( ! is_singular() ) {
		$title_link = jupiterx_open_markup(
			'jupiterx_post_title_link',
			'a',
			array(
				'href'  => esc_url( get_permalink() ), // Automatically escaped.
				'title' => the_title_attribute( 'echo=0' ),
				'rel'   => 'bookmark',
			)
		);

		$title_link .= $title;
		$title_link .= jupiterx_close_markup( 'jupiterx_post_title_link', 'a' );

		$title     = $title_link;
		$title_tag = 'h2';
	}

	jupiterx_open_markup_e(
		'jupiterx_post_title',
		$title_tag,
		array(
			'class'    => 'jupiterx-post-title',
			'itemprop' => 'headline',
		)
	);

		echo wp_kses_post( $title ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.

	jupiterx_close_markup_e( 'jupiterx_post_title', $title_tag );
}

jupiterx_add_smart_action( 'jupiterx_main_header_content', 'jupiterx_post_search_title' );
/**
 * Echo search post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_search_title() {

	if ( ! is_search() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_search_title', 'h1', [ 'class' => 'jupiterx-search-title' ] );

		printf( '%1$s%2$s', jupiterx_output( 'jupiterx_search_title_text', __( 'Search results for: ', 'jupiterx' ) ), get_search_query() ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.

	jupiterx_close_markup_e( 'jupiterx_search_title', 'h1' );
}

jupiterx_add_smart_action( 'jupiterx_main_header_content', 'jupiterx_post_404_title' );
/**
 * Echo 404 post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_404_title() {

	if ( ! is_404() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_404_title', 'h1', [ 'class' => 'jupiterx-404-title' ] );

		jupiterx_output_e( 'jupiterx_404_title_text', esc_html__( '404', 'jupiterx' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.

	jupiterx_close_markup_e( 'jupiterx_404_title', 'h1' );
}

jupiterx_add_smart_action( 'jupiterx_main_header_content', 'jupiterx_post_archive_title' );
/**
 * Echo archive post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_archive_title() {

	if ( ! is_archive() || is_search() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_archive_title', 'h1', [ 'class' => 'jupiterx-archive-title jupiterx-archive-header-post-title' ] );

		jupiterx_output_e( 'jupiterx_archive_title_text', get_the_archive_title() );

	jupiterx_close_markup_e( 'jupiterx_archive_title', 'h1' );
}

jupiterx_add_smart_action( 'jupiterx_main_header_content', 'jupiterx_subtitle' );
/**
 * Echo subtitle.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_subtitle() {
	remove_filter( 'term_description', 'wpautop' );
	remove_filter( 'get_the_post_type_description', 'wpautop' );

	$subtitle     = get_the_archive_description();
	$subtitle_tag = 'p';

	if ( is_singular() ) {
		$subtitle = jupiterx_get_field( 'jupiterx_title_bar_subtitle' );
	}

	if ( is_tax() || is_category() || is_tag() ) {
		$term_id  = get_queried_object_id();
		$subtitle = term_description( $term_id );
	}

	if ( empty( $subtitle ) ) {
		return;
	}

	jupiterx_open_markup_e(
		'jupiterx_subtitle',
		$subtitle_tag,
		[
			'class'    => 'jupiterx-subtitle',
			'itemprop' => 'description',
		]
	);

		echo wp_kses_post( $subtitle ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.

	jupiterx_close_markup_e( 'jupiterx_post_title', $subtitle_tag );
}

jupiterx_add_smart_action( 'jupiterx_post_header', 'jupiterx_post_meta', 15 );
/**
 * Echo post meta.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_meta() {

	/**
	 * Filter whether {@see jupiterx_post_meta()} should be short-circuit or not.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $pre True to short-circuit, False to let the function run.
	 */
	if ( apply_filters( 'jupiterx_pre_post_meta', ! is_singular() ) ) {
		return;
	}

	$post_meta = jupiterx_get_field( 'jupiterx_post_meta', 'global' );

	$elements = apply_filters( 'jupiterx_post_meta_elements', jupiterx_get_post_single_elements() );

	$default_elements = [ 'date', 'author', 'categories', 'comments' ];

	$enabled_elements = array_intersect( $default_elements, $elements );

	if ( ( 'global' === $post_meta && empty( $enabled_elements ) ) || empty( $post_meta ) ) {
		return;
	}

	if ( '1' === $post_meta && empty( $enabled_elements ) ) {
		$elements = $default_elements;
	}

	jupiterx_open_markup_e( 'jupiterx_post_meta', 'ul', [ 'class' => 'jupiterx-post-meta list-inline' ] );

		/**
		 * Filter the post meta actions and order.
		 *
		 * A do_action( "jupiterx_post_meta_{$array_key}" ) is called for each array key set. Array values are used to set the priority of
		 * each actions. The array ordered using asort();
		 *
		 * @since 1.0.0
		 *
		 * @param array $fragments An array of fragment files.
		 */
		$meta_items = apply_filters(
			'jupiterx_post_meta_items',
			array(
				'date'       => 10,
				'author'     => 20,
				'categories' => 30,
				'comments'   => 40,
			)
		);

		asort( $meta_items );

	foreach ( $meta_items as $meta => $priority ) {

		$content = jupiterx_render_function( 'do_action', "jupiterx_post_meta_$meta" );

		if ( ! $content || ! in_array( $meta, $elements, true ) ) {
			continue;
		}

		jupiterx_open_markup_e( "jupiterx_post_meta_item[_{$meta}]", 'li', [ 'class' => "jupiterx-post-meta-{$meta} list-inline-item" ] );

			jupiterx_output_e( "jupiterx_post_meta_item_{$meta}_text", $content );

		jupiterx_close_markup_e( "jupiterx_post_meta_item[_{$meta}]", 'li' );
	}

	jupiterx_close_markup_e( 'jupiterx_post_meta', 'ul' );
}

jupiterx_add_filter( 'jupiterx_post_meta_elements', 'jupiterx_post_meta_show_comments' );
/**
 * Force add or removal of comments element.
 *
 * @since 1.0.0
 *
 * @param array $elements Current elements to show.
 *
 * @return array Filtered elements.
 */
function jupiterx_post_meta_show_comments( $elements ) {
	$post_comments = jupiterx_get_field( 'jupiterx_post_comments', 'global' );

	// Add comments on the list.
	if ( '1' === $post_comments ) {
		$elements[] = 'comments';
		$elements   = array_unique( $elements );
	}

	// Remove comments on the list.
	if ( empty( $post_comments ) ) {
		$elements = array_filter( $elements, function ( $element ) {
			return 'comments' !== $element;
		} );
	}

	return $elements;
}

jupiterx_add_smart_action( 'jupiterx_post_body', 'jupiterx_post_image', 5 );
/**
 * Echo post image.
 *
 * @since 1.0.0
 *
 * @return bool
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_post_image() {

	if ( ! has_post_thumbnail() || ! current_theme_supports( 'post-thumbnails' ) || ! jupiterx_post_element_enabled( 'featured_image' ) ) {
		return false;
	}

	global $post;

	/**
	 * Filter whether Jupiter should handle the image edition (resize) or let WP do so.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $edit True to use Jupiter Image API to handle the image edition (resize), false to let {@link http://codex.wordpress.org/Function_Reference/the_post_thumbnail the_post_thumbnail()} taking care of it. Default true.
	 */
	$edit = apply_filters( 'jupiterx_post_image_edit', true );

	if ( $edit ) {

		$image = jupiterx_get_post_attachment( $post->ID, 'full' );

		/**
		 * Filter the arguments used by {@see jupiterx_edit_image()} to edit the post image.
		 *
		 * @since 1.0.0
		 *
		 * @param bool|array $edit_args Arguments used by {@see jupiterx_edit_image()}. Set to false to use WordPress
		 *                              large size.
		 */
		$edit_medium_args = apply_filters(
			'jupiterx_edit_post_image_medium_args',
			[
				'resize' => [ 800, false ],
			]
		);

		if ( empty( $edit_medium_args ) ) {
			$image_medium = jupiterx_get_post_attachment( $post->ID, 'large' ); // Default medium size is 300px, so we use large one.
		} else {
			$image_medium = jupiterx_edit_post_attachment( $post->ID, $edit_medium_args );
		}

		/**
		 * Filter the arguments used by {@see jupiterx_edit_image()} to edit the post small image.
		 *
		 * The small image is only used for screens equal or smaller than the image width set, which is 480px by default.
		 *
		 * @since 1.0.0
		 *
		 * @param bool|array $edit_args Arguments used by {@see jupiterx_edit_image()}. Set to false to use WordPress
		 *                              small size.
		 */
		$edit_small_args = apply_filters(
			'jupiterx_edit_post_image_small_args',
			[
				'resize' => [ 480, false ],
			]
		);

		if ( empty( $edit_small_args ) ) {
			$image_small = jupiterx_get_post_attachment( $post->ID, 'thumbnail' );
		} else {
			$image_small = jupiterx_edit_post_attachment( $post->ID, $edit_small_args );
		}
	}

	if ( ! $image ) {
		return false;
	}

	jupiterx_open_markup_e( 'jupiterx_post_image', 'div', [ 'class' => 'jupiterx-post-image' ] );

	if ( ! is_singular() ) {
		jupiterx_open_markup_e(
			'jupiterx_post_image_link',
			'a',
			[
				'href'            => esc_url( get_permalink() ), // Automatically escaped.
				'title'           => the_title_attribute( 'echo=0' ),
				'data-object-fit' => 'cover',
			]
		);
	}

		jupiterx_open_markup_e( 'jupiterx_post_image_item_wrap', 'picture' );

		if ( $edit ) {
			if ( $image_small ) {
				jupiterx_selfclose_markup_e(
					'jupiterx_post_image_small_item',
					'source',
					[
						'media'  => esc_attr( '(max-width: ' . $image_small->width . 'px)' ),
						'srcset' => esc_url( $image_small->src ),
					],
					$image_small
				);
			}

			if ( $image_medium ) {
				jupiterx_selfclose_markup_e(
					'jupiterx_post_image_medium_item',
					'source',
					[
						'media'  => esc_attr( '(max-width: ' . $image_medium->width . 'px)' ),
						'srcset' => esc_url( $image_medium->src ),
					],
					$image_medium
				);
			}

			jupiterx_selfclose_markup_e(
				'jupiterx_post_image_item',
				'img',
				[
					'width'    => esc_attr( $image->width ),
					'height'   => esc_attr( $image->height ),
					'src'      => esc_url( $image->src ), // Automatically escaped.
					'alt'      => esc_attr( $image->alt ), // Automatically escaped.
					'itemprop' => 'image',
				],
				$image
			);
		} else {
			// Jupiter API isn't available, use wp_get_attachment_image_attributes filter instead.
			the_post_thumbnail();
		}

		jupiterx_close_markup_e( 'jupiterx_post_image_item_wrap', 'picture' );

	if ( ! is_singular() ) {
		jupiterx_close_markup_e( 'jupiterx_post_image_link', 'a' );
	}

	jupiterx_close_markup_e( 'jupiterx_post_image', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_post_body', 'jupiterx_post_content' );
/**
 * Echo post content.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_post_content() {
	$is_elementor_library = filter_input( INPUT_GET, 'elementor_library', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$is_preview           = filter_input( INPUT_GET, 'preview', FILTER_DEFAULT );
	$preview_id           = filter_input( INPUT_GET, 'preview_id', FILTER_SANITIZE_NUMBER_INT );

	/**
	 * Prevents header & footer duplication in the Elementor preview mode.
	 */
	if ( ! empty( $is_elementor_library ) && ! empty( $is_preview ) && ! empty( $preview_id ) ) {
		$template_type = get_post_meta( $preview_id, '_elementor_template_type', true );
		$target_types  = [ 'header', 'footer' ];

		if ( in_array( $template_type, $target_types, true ) ) {
			return;
		}
	}

	global $post;

	jupiterx_open_markup_e(
		'jupiterx_post_content',
		'div',
		array(
			'class'    => 'jupiterx-post-content clearfix',
			'itemprop' => 'text',
		)
	);

	if ( ! is_singular() && ( has_excerpt() || is_search() ) ) {
		the_excerpt();

		if ( ! is_search() ) {
			echo jupiterx_post_more_link( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	} elseif ( post_archive_is_summery() ) {
		the_excerpt();
	} else {
		the_content();
	}

	if ( is_singular() && 'open' === get_option( 'default_ping_status' ) && post_type_supports( $post->post_type, 'trackbacks' ) ) {
		echo '<!--';
		trackback_rdf();
		echo '-->' . "\n";
	}

	jupiterx_close_markup_e( 'jupiterx_post_content', 'div' );
}

/**
 * Archive Page Excerpt.
 *
 * @since 2.0.0
 *
 * @return boolean
 */
function post_archive_is_summery() {
	if (
		'post' === get_post_type( get_the_ID() ) &&
		is_archive() &&
		empty( get_theme_mod( 'jupiterx_post_archive_template_type' ) ) &&
		'summary' === get_theme_mod( 'jupiterx_post_archive_default_type' )
	) {
		return true;
	}

	return false;
}

// Filter.
add_filter( 'the_content_more_link', 'jupiterx_post_more_link' );
/**
 * Modify post "more link".
 *
 * @param string $output The "Read more" markup.
 *
 * @since 1.0.0
 *
 * @return string The modified "more link".
 */
function jupiterx_post_more_link( $output ) {
	global $post;

	$output = jupiterx_open_markup(
		'jupiterx_post_more_link',
		'a',
		array(
			'href'  => esc_url( get_permalink() ), // Automatically escaped.
			'class' => 'jupiterx-post-more-link btn btn-outline-secondary',
		)
	);

		$output .= jupiterx_output( 'jupiterx_post_more_link_text', esc_html__( 'Continue reading', 'jupiterx' ) );

	$output .= jupiterx_close_markup( 'jupiterx_post_more_link', 'a' );

	return $output;
}

jupiterx_add_smart_action( 'jupiterx_post_body', 'jupiterx_post_content_navigation', 20 );
/**
 * Echo post content navigation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_content_navigation() {
	echo wp_link_pages(
		array(
			'before' => jupiterx_open_markup( 'jupiterx_post_content_navigation', 'p', array( 'class' => 'font-weight-bold' ) ) . jupiterx_output( 'jupiterx_post_content_navigation_text', esc_html__( 'Pages:', 'jupiterx' ) ),
			'after'  => jupiterx_close_markup( 'jupiterx_post_content_navigation', 'p' ),
			'echo'   => false,
		)
	);
}

jupiterx_add_smart_action( 'jupiterx_post_body', 'jupiterx_post_tags', 30 );
/**
 * Echo post meta tags.
 *
 * @since 1.0.0
 */
function jupiterx_post_tags() {

	if ( ! jupiterx_post_element_enabled( 'tags' ) ) {
		return false;
	}

	$tags = jupiterx_render_function( 'do_shortcode', '[jupiterx_post_meta_tags]' );

	if ( ! $tags || ! jupiterx_get_field_mod( 'jupiterx_post_tags', 'global', true ) ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_post_tags', 'div', 'class=jupiterx-post-tags' );

		jupiterx_open_markup_e( 'jupiterx_post_tags_row', 'div', 'class=jupiterx-post-tags-row' );

			echo wp_kses_post( $tags ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.

		jupiterx_close_markup_e( 'jupiterx_post_tags_row', 'div' );

	jupiterx_close_markup_e( 'jupiterx_post_tags', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_post_body', 'jupiterx_post_social_share', 40 );
/**
 * Echo post meta tags.
 *
 * @since 1.0.0
 */
function jupiterx_post_social_share() {
	if ( ! is_singular() || ! jupiterx_post_element_enabled( 'social_share' ) ) {
		return;
	}

	$post_type = get_post_type();

	if ( is_singular( $post_type ) ) {
		$links        = get_theme_mod( "jupiterx_{$post_type}_single_social_share_filter", [ 'facebook', 'twitter', 'linkedin' ] );
		$name_enabled = get_theme_mod( "jupiterx_{$post_type}_single_social_share_name", true );
	}

	if ( isset( $links ) && isset( $name_enabled ) ) {
		jupiterx_post_social_share_shortcode( $post_type, $links, $name_enabled );
	}
}

// Filter.
jupiterx_add_smart_action( 'previous_post_link', 'jupiterx_previous_post_link', 10, 4 );
/**
 * Modify post "previous link".
 *
 * @since 1.0.0
 *
 * @param string $output "Next link" output.
 * @param string $format Link output format.
 * @param string $link Link permalink format.
 * @param int    $post Post ID.

 * @return string The modified "previous link".
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_previous_post_link( $output, $format, $link, $post ) {
	// Using $link won't apply wp filters, so rather strip tags the $output.
	$text = strip_tags( $output ); // @codingStandardsIgnoreLine

	$output = jupiterx_open_markup(
		'jupiterx_previous_link[_post_navigation]',
		'a',
		[
			'href'  => esc_url( get_permalink( $post ) ), // Automatically escaped.
			'class' => 'jupiterx-post-navigation-link jupiterx-post-navigation-previous col-md-6',
			'rel'   => 'previous',
			'title' => $post->post_title, // Automatically escaped.
		]
	);

		$output .= jupiterx_get_post_navigation_thumbnail( $post->ID );

		$output .= jupiterx_open_markup( 'jupiterx_previous_body[_post_navigation]', 'div', 'class=jupiterx-post-navigation-body' );

			$output .= jupiterx_open_markup( 'jupiterx_previous_title[_post_navigation]', 'h6', 'class=jupiterx-post-navigation-title' );

				$output .= jupiterx_output( 'jupiterx_previous_text[_post_navigation]', $text );

			$output .= jupiterx_close_markup( 'jupiterx_previous_title[_post_navigation]', 'h6' );

			$output .= jupiterx_open_markup( 'jupiterx_previous_label[_post_navigation]', 'span', 'class=jupiterx-post-navigation-label' );

				$output .= jupiterx_output( 'jupiterx_previous_label_text[_post_navigation]', esc_html__( 'Previous', 'jupiterx' ) );

			$output .= jupiterx_close_markup( 'jupiterx_previous_label[_post_navigation]', 'span' );

		$output .= jupiterx_close_markup( 'jupiterx_previous_body[_post_navigation]', 'div' );

	$output .= jupiterx_close_markup( 'jupiterx_previous_link[_post_navigation]', 'a' );

	return $output;
}

// Filter.
jupiterx_add_smart_action( 'next_post_link', 'jupiterx_next_post_link', 10, 4 );
/**
 * Modify post "next link".
 *
 * @since 1.0.0
 *
 * @param string $output "Next link" output.
 * @param string $format Link output format.
 * @param string $link Link permalink format.
 * @param int    $post Post ID.
 *
 * @return string The modified "next link".
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_next_post_link( $output, $format, $link, $post ) {
	// Using $link won't apply WP filters, so rather strip tags the $output.
	$text = strip_tags( $output ); // @codingStandardsIgnoreLine

	$output = jupiterx_open_markup(
		'jupiterx_next_link[_post_navigation]',
		'a',
		[
			'href'  => esc_url( get_permalink( $post ) ), // Automatically escaped.
			'class' => 'jupiterx-post-navigation-link jupiterx-post-navigation-next col-md-6 ml-auto',
			'rel'   => 'next',
			'title' => $post->post_title, // Automatically escaped.
		]
	);

		$output .= jupiterx_open_markup( 'jupiterx_next_body[_post_navigation]', 'div', 'class=jupiterx-post-navigation-body' );

			$output .= jupiterx_open_markup( 'jupiterx_next_title[_post_navigation]', 'h6', 'class=jupiterx-post-navigation-title' );

				$output .= jupiterx_output( 'jupiterx_next_text[_post_navigation]', $text );

			$output .= jupiterx_close_markup( 'jupiterx_next_title[_post_navigation]', 'h6' );

			$output .= jupiterx_open_markup( 'jupiterx_next_label[_post_navigation]', 'span', 'class=jupiterx-post-navigation-label' );

				$output .= jupiterx_output( 'jupiterx_next_label_text[_post_navigation]', esc_html__( 'Next', 'jupiterx' ) );

			$output .= jupiterx_close_markup( 'jupiterx_next_label[_post_navigation]', 'span' );

		$output .= jupiterx_close_markup( 'jupiterx_next_body[_post_navigation]', 'div' );

		$output .= jupiterx_get_post_navigation_thumbnail( $post->ID );

	$output .= jupiterx_close_markup( 'jupiterx_next_link[_post_navigation]', 'a' );

	return $output;
}

jupiterx_add_smart_action( 'jupiterx_post_after_markup', 'jupiterx_post_navigation' );
/**
 * Echo post navigation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_navigation() {

	/**
	 * Filter whether {@see jupiterx_post_navigation()} should be short-circuit or not.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $pre True to short-circuit, False to let the function run.
	 */
	if ( apply_filters( 'jupiterx_pre_post_navigation', ! is_singular() ) ) {
		return;
	}

	if ( ! jupiterx_post_element_enabled( 'navigation' ) ) {
		return false;
	}

	$previous = is_attachment() ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	jupiterx_open_markup_e(
		'jupiterx_post_navigation',
		'div',
		[
			'class' => 'jupiterx-post-navigation',
			'role'  => 'navigation',
		]
	);

		jupiterx_open_markup_e(
			'jupiterx_post_navigation_row',
			'div',
			[
				'class' => 'row',
			]
		);

			// Previous.
			if ( $previous ) {
				previous_post_link( '%link', $previous->post_title );
			}

			// Next.
			if ( $next ) {
				next_post_link( '%link', $next->post_title );
			}

		jupiterx_close_markup_e( 'jupiterx_post_navigation_row', 'div' );

	jupiterx_close_markup_e( 'jupiterx_post_navigation', 'div' );
}

/**
 * Echo post navigation thumbnail.
 *
 * @param integer $id The post ID.
 *
 * @since 1.0.0
 */
function jupiterx_get_post_navigation_thumbnail( $id ) {
	$post_type     = get_post_type();
	$image_enabled = get_theme_mod( "jupiterx_${post_type}_single_navigation_image", true );

	if ( ! $image_enabled ) {
		return;
	}

	return get_the_post_thumbnail( $id, 'thumbnail' );
}

jupiterx_add_smart_action( 'jupiterx_post_after_markup', 'jupiterx_post_author_box', 20 );
/**
 * Echo post author box.
 *
 * @since 1.0.0
 */
function jupiterx_post_author_box() {
	if ( ! is_single() ) {
		return;
	}

	if ( ! jupiterx_post_element_enabled( 'author_box' ) ) {
		return false;
	}

	$email    = get_the_author_meta( 'jupiterx_user_email' );
	$networks = [ 'facebook', 'twitter' ];

	jupiterx_open_markup_e( 'jupiterx_post_author_box', 'div', 'class=jupiterx-post-author-box' );

		jupiterx_open_markup_e( 'jupiterx_post_author_box_avatar', 'div', 'class=jupiterx-post-author-box-avatar' );

			echo get_avatar( get_the_author_meta( 'ID' ), 96 );

		jupiterx_close_markup_e( 'jupiterx_post_author_box_avatar', 'div' );

		jupiterx_open_markup_e( 'jupiterx_post_author_box_content', 'div', 'class=jupiterx-post-author-box-content' );

			jupiterx_open_markup_e(
				'jupiterx_post_author_box_link',
				'a',
				[
					'href'      => get_author_posts_url( get_the_author_meta( 'ID' ) ), // Automatically escaped.
					'class'     => 'jupiterx-post-author-box-link',
					'rel'       => 'author',
					'itemprop'  => 'author',
					'itemscope' => '',
					'itemtype'  => 'http://schema.org/Person',
				]
			);
				$author = get_the_author_meta( 'nickname' );

				jupiterx_selfclose_markup_e(
					'jupiterx_post_meta_author_name_meta',
					'meta',
					[
						'itemprop' => 'name',
						'content'  => esc_attr( $author ),
					]
				);

				jupiterx_output_e( 'jupiterx_post_author_box_name', get_the_author_meta( 'display_name' ) );

			jupiterx_close_markup_e( 'jupiterx_post_author_box_link', 'a' );

			jupiterx_output_e( 'jupiterx_post_author_box_bio', wpautop( get_the_author_meta( 'description' ) ) );

			jupiterx_open_markup_e(
				'jupiterx_post_author_icons',
				'ul',
				'class=jupiterx-post-author-icons list-inline'
			);

			if ( '0' !== $email ) { // phpcs:ignore
				jupiterx_open_markup_e(
					'jupiterx_post_author_icons_email',
					'li',
					'class=list-inline-item'
				);
					jupiterx_open_markup_e(
						'jupiterx_post_author_email_link',
						'a',
						[
							'href' => 'mailto:' . get_the_author_meta( 'user_email' ),
							'class' => 'jupiterx-icon-share-email',
						]
					);

					jupiterx_close_markup_e( 'jupiterx_post_author_icons_email_link', 'a' );

				jupiterx_close_markup_e( 'jupiterx_post_author_icons_email', 'li' );
			} // phpcs:ignore

			foreach ( $networks as $key => $network ) { // phpcs:ignore
				$author_meta = get_the_author_meta( 'jupiterx_user_' . $network );

				if ( empty( $author_meta ) ) { // phpcs:ignore
					continue;
				} // phpcs:ignore

				jupiterx_open_markup_e(
					'jupiterx_post_author_icons_' . $network,
					'li',
					'class=list-inline-item'
				);

					jupiterx_open_markup_e(
						'jupiterx_post_author_icons_' . $network . '_link',
						'a',
						[
							'href'   => esc_url( $author_meta ),
							'class'  => esc_attr( 'jupiterx-icon-' . $network ),
							'target' => '_blank',
						]
					);

					jupiterx_close_markup_e( 'jupiterx_post_author_icons_' . $network . '_link', 'a' );

				jupiterx_close_markup_e( 'jupiterx_post_author_icons_' . $network, 'li' );
			} // phpcs:ignore

			jupiterx_close_markup_e( 'jupiterx_post_author_icons', 'ul' );

		jupiterx_close_markup_e( 'jupiterx_post_author_box_content', 'div' );

	jupiterx_close_markup_e( 'jupiterx_post_author_box', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_post_after_markup', 'jupiterx_post_related', 30 );
/**
 * Echo post related.
 *
 * @since 1.0.0
 */
function jupiterx_post_related() {
	if ( ! is_single() ) {
		return;
	}

	if ( ! jupiterx_post_element_enabled( 'related_posts' ) ) {
		return false;
	}

	/**
	 * Filter the related posts thumbnail size.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $size he post thumbnail size.
	 */
	$thumbnail_size = apply_filters( 'jupiterx_post_related_thumbnail_size', 'medium' );

	/**
	 * Filter the related posts thumbnail attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attr The post thumbnail attributes.
	 */
	$thumbnail_attr = apply_filters( 'jupiterx_post_related_thumbnail_attr', [ 'data-object-fit' => 'cover' ] );

	$related_posts_label = __( 'Recommended Posts', 'jupiterx' );

	$post_id      = get_the_ID();
	$post_type    = get_post_type();
	$post_count   = [
		'posts_per_page' => 4,
		'columns'        => 3,
	];
	$category_ids = [];
	$taxonomy     = 'category';

	// Change taxonomy.
	if ( 'portfolio' === $post_type ) {
		$taxonomy            = 'portfolio_category';
		$related_posts_label = __( 'Related Works', 'jupiterx' );
	}

	// Prepare terms.
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}

	foreach ( $terms as $term ) {
		$term_ids[] = $term->term_id;
	}

	// Change post count based on layout.
	$layout = jupiterx_get_layout();

	if ( in_array( $layout, [ 'c_sp', 'sp_c', 'c_ss', 'ss_c' ], true ) ) {
		$post_count['posts_per_page'] = 3;
		$post_count['columns']        = 4;
	}

	if ( in_array( $layout, [ 'c_sp_ss', 'sp_ss_c', 'sp_c_ss' ], true ) ) {
		$post_count['posts_per_page'] = 2;
		$post_count['columns']        = 6;
	}

	/**
	 * Filter the related post count.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_count Post count arguments
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	$post_count = apply_filters( 'jupiterx_post_related_count', $post_count );

	/**
	 * Filter the related posts arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args WP Query arguments.
	 */
	$args = apply_filters( 'jupiterx_post_related_args', [
		'post_type'           => $post_type,
		'post__not_in'        => [ $post_id ],
		'tax_query'           => [ // @codingStandardsIgnoreLine
			'relation' => 'OR',
			[
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $term_ids,
			],
		],
		'posts_per_page'      => $post_count['posts_per_page'],
		'ignore_sticky_posts' => 1,
	] );

	$query = new WP_Query( $args );

	if ( ! $query->post_count ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_post_related', 'div', 'class=jupiterx-post-related' );

		jupiterx_open_markup_e( 'jupiterx_post_related_label', 'h2', 'class=jupiterx-post-related-label' );

			jupiterx_output_e( 'jupiterx_post_related_label_text', $related_posts_label );

		jupiterx_close_markup_e( 'jupiterx_post_related_label', 'h2' );

		jupiterx_open_markup_e( 'jupiterx_post_related_wrap', 'div', 'class=row' );

	if ( $query->have_posts() ) :

		while ( $query->have_posts() ) :
			$query->the_post();

			jupiterx_open_markup_e( 'jupiterx_post_related_col', 'div', "class=col-md-6 col-lg-{$post_count['columns']}" );

				jupiterx_open_markup_e( 'jupiterx_post_related_card', 'a', [
					'class' => 'card',
					'href'  => esc_url( get_permalink() ),
				] );

					the_post_thumbnail( $thumbnail_size, $thumbnail_attr );

					jupiterx_open_markup_e( 'jupiterx_post_related_content', 'div', 'class=card-body' );

						jupiterx_open_markup_e( 'jupiterx_post_related_title', 'h6', 'class=card-title' );

						jupiterx_output_e( 'jupiterx_post_related_title_text', get_the_title() );

						jupiterx_close_markup_e( 'jupiterx_post_related_title', 'h6' );

					jupiterx_close_markup_e( 'jupiterx_post_related_content', 'div' );

				jupiterx_close_markup_e( 'jupiterx_post_related_card', 'a' );

			jupiterx_close_markup_e( 'jupiterx_post_related_col', 'div' );

		endwhile;

	endif;

		wp_reset_postdata();

		jupiterx_close_markup_e( 'jupiterx_post_related_wrap', 'div' );

	jupiterx_close_markup_e( 'jupiterx_post_related', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_after_posts_loop', 'jupiterx_posts_pagination' );
/**
 * Echo posts pagination.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_posts_pagination() {

	/**
	 * Filter whether {@see jupiterx_posts_pagination()} should be short-circuit or not.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $pre True to short-circuit, False to let the function run.
	 */
	if ( apply_filters( 'jupiterx_pre_post_pagination', is_singular() ) ) {
		return;
	}

	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$current = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$count   = intval( $wp_query->max_num_pages );

	jupiterx_open_markup_e(
		'jupiterx_posts_pagination',
		'ul',
		array(
			'class' => 'pagination jupiterx-posts-pagination',
			'role'  => 'navigation',
		)
	);

	// Previous.
	if ( get_previous_posts_link() ) {
		jupiterx_open_markup_e( 'jupiterx_posts_pagination_item[_previous]', 'li', [ 'class' => 'page-item' ] );

			jupiterx_open_markup_e(
				'jupiterx_previous_link[_posts_pagination]',
				'a',
				[
					'class' => 'page-link',
					'href' => previous_posts( false ), // Automatically escaped.
				],
				$current
			);

				jupiterx_output_e( 'jupiterx_previous_text[_posts_pagination]', esc_html__( 'Previous', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_previous_link[_posts_pagination]', 'a' );

		jupiterx_close_markup_e( 'jupiterx_posts_pagination_item[_previous]', 'li' );
	}

	// Links.
	foreach ( range( 1, (int) $wp_query->max_num_pages ) as $link ) {

		// Skip if next is set.
		if ( isset( $next ) && $link !== $next ) {
			continue;
		} else {
			$next = $link + 1;
		}

		$is_separator = array(
			1 !== $link, // Not first.
			1 === $current && 3 === $link ? false : true, // Force first 3 items.
			$count > 3, // More.
			$count !== $link, // Not last.
			( $current - 1 ) !== $link, // Not previous.
			$current !== $link, // Not current.
			( $current + 1 ) !== $link, // Not next.
		);

		// Separator.
		if ( ! in_array( false, $is_separator, true ) ) {
			jupiterx_open_markup_e( 'jupiterx_posts_pagination_item[_separator]', 'li', [ 'class' => 'page-item disabled' ] );

				jupiterx_open_markup_e( 'jupiterx_posts_pagination_item[_separator]_wrap', 'span', [ 'class' => 'page-link' ] );

					jupiterx_output_e( 'jupiterx_posts_pagination_item_separator_text', '...' );

				jupiterx_close_markup_e( 'jupiterx_posts_pagination_item[_separator]_wrap', 'span' );

			jupiterx_close_markup_e( 'jupiterx_posts_pagination_item[_separator]', 'li' );

			// Jump.
			if ( $link < $current ) {
				$next = $current - 1;
			} elseif ( $link > $current ) {
				$next = $count;
			}

			continue;
		}

		// Integer.
		if ( $link === $current ) {
			jupiterx_open_markup_e( 'jupiterx_posts_pagination_item[_active]', 'li', [ 'class' => 'page-item active' ] );

				jupiterx_open_markup_e( 'jupiterx_posts_pagination_item[_active]_wrap', 'span', [ 'class' => 'page-link' ] );

					jupiterx_output_e( 'jupiterx_posts_pagination_item[_active]_text', $link );

				jupiterx_close_markup_e( 'jupiterx_posts_pagination_item[_active]_wrap', 'span' );

			jupiterx_close_markup_e( 'jupiterx_posts_pagination_item[_active]', 'li' );
		} else {
			jupiterx_open_markup_e( 'jupiterx_posts_pagination_item', 'li', [ 'class' => 'page-item' ] );

				jupiterx_open_markup_e(
					'jupiterx_posts_pagination_item_link',
					'a',
					[
						'class' => 'page-link',
						'href' => esc_url( get_pagenum_link( $link ) ), // Automatically escaped.
					],
					$link
				);

					jupiterx_output_e( 'jupiterx_posts_pagination_item_link_text', $link );

				jupiterx_close_markup_e( 'jupiterx_posts_pagination_item_link', 'a' );

			jupiterx_close_markup_e( 'jupiterx_posts_pagination_item', 'li' );
		}
	}

	// Next.
	if ( get_next_posts_link() ) {
		jupiterx_open_markup_e( 'jupiterx_posts_pagination_item[_next]', 'li', [ 'class' => 'page-item' ] );

			jupiterx_open_markup_e(
				'jupiterx_next_link[_posts_pagination]',
				'a',
				[
					'class' => 'page-link',
					'href' => esc_url( next_posts( $count, false ) ), // Automatically escaped.
				],
				$current
			);

				jupiterx_output_e( 'jupiterx_next_text[_posts_pagination]', esc_html__( 'Next', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_next_link[_posts_pagination]', 'a' );

		jupiterx_close_markup_e( 'jupiterx_posts_pagination_item[_next]', 'li' );
	}

	jupiterx_close_markup_e( 'jupiterx_posts_pagination', 'ul' );
}

jupiterx_add_smart_action( 'jupiterx_no_post', 'jupiterx_no_post' );
/**
 * Echo no post content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_no_post() {
	jupiterx_open_markup_e( 'jupiterx_post', 'article', [ 'class' => 'jupiterx-no-article jupiterx-post' ] );

		jupiterx_open_markup_e( 'jupiterx_post_header', 'header' );

			jupiterx_open_markup_e( 'jupiterx_post_title', 'h1', array( 'class' => 'jupiterx-post-title' ) );

				jupiterx_output_e( 'jupiterx_no_post_article_title_text', esc_html__( 'Whoops, no result found!', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_post_title', 'h1' );

		jupiterx_close_markup_e( 'jupiterx_post_header', 'header' );

		jupiterx_open_markup_e( 'jupiterx_post_body', 'div' );

			jupiterx_open_markup_e( 'jupiterx_post_content', 'div', array( 'class' => 'jupiterx-post-content' ) );

				jupiterx_open_markup_e( 'jupiterx_no_post_article_content', 'p' );

					jupiterx_output_e( 'jupiterx_no_post_article_content_text', esc_html__( 'It looks like nothing was found at this location. Try a new search?', 'jupiterx' ) );

				jupiterx_close_markup_e( 'jupiterx_no_post_article_content', 'p' );

					jupiterx_output_e( 'jupiterx_no_post_search_form', get_search_form( false ) );

			jupiterx_close_markup_e( 'jupiterx_post_content', 'div' );

		jupiterx_close_markup_e( 'jupiterx_post_body', 'div' );

	jupiterx_close_markup_e( 'jupiterx_post', 'article' );
}

// Filter.
jupiterx_add_smart_action( 'the_password_form', 'jupiterx_post_password_form' );
/**
 * Modify password protected form.
 *
 * @since 1.0.0
 *
 * @return string The form.
 */
function jupiterx_post_password_form() {
	global $post;

	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID ); // @codingStandardsIgnoreLine

	// Notice.
	$output = jupiterx_open_markup( 'jupiterx_password_form_notice', 'p', array( 'class' => 'alert alert-warning' ) );

		$output .= jupiterx_output( 'jupiterx_password_form_notice_text', esc_html__( 'This post is protected. To view it, enter the password below!', 'jupiterx' ) );

	$output .= jupiterx_close_markup( 'jupiterx_password_form_notice', 'p' );

	// Form.
	$output .= jupiterx_open_markup(
		'jupiterx_password_form',
		'form',
		array(
			'class'  => 'form-inline',
			'method' => 'post',
			'action' => esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ), // Automatically escaped.
		)
	);

		$output .= jupiterx_selfclose_markup(
			'jupiterx_password_form_input',
			'input',
			array(
				'class'       => 'form-control',
				'type'        => 'password',
				'placeholder' => esc_attr( apply_filters( 'jupiterx_password_form_input_placeholder', esc_html__( 'Password', 'jupiterx' ) ) ), // Automatically escaped.
				'name'        => 'post_password',
			)
		);

		$output .= jupiterx_selfclose_markup(
			'jupiterx_password_form_submit',
			'input',
			array(
				'class' => 'btn btn-dark ml-2',
				'type'  => 'submit',
				'name'  => 'submit',
				'value' => esc_attr( apply_filters( 'jupiterx_password_form_submit_text', esc_html__( 'Submit', 'jupiterx' ) ) ),
			)
		);

	$output .= jupiterx_close_markup( 'jupiterx_password_form', 'form' );

	return $output;
}

// Filter.
jupiterx_add_smart_action( 'post_gallery', 'jupiterx_post_gallery', 10, 2 );
/**
 * Modify WP {@link https://codex.wordpress.org/Function_Reference/gallery_shortcode Gallery Shortcode} output.
 *
 * This implements the functionality of the Gallery Shortcode for displaying WordPress images in a post.
 *
 * @since 1.0.0
 *
 * @param string $output   The gallery output. Default empty.
 * @param array  $attr     Attributes of the {@link https://codex.wordpress.org/Function_Reference/gallery_shortcode gallery_shortcode()}.
 *
 * @return string HTML content to display gallery.
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_post_gallery( $output, $attr ) {

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'carousel' ) ) {
		return;
	}

	$post     = get_post();
	$html5    = current_theme_supports( 'html5', 'gallery' );
	$defaults = array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure' : 'dl',
		'icontag'    => $html5 ? 'div' : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => '',
	);
	$atts     = shortcode_atts( $defaults, $attr, 'gallery' );
	$id       = intval( $atts['id'] );

	// Set attachements.
	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts(
			array(
				'include'        => $atts['include'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);

		$attachments = array();

		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'exclude'        => $atts['exclude'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);
	} else {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);
	}

	// Stop here if no attachment.
	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";

		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}

		return $output;
	}

	// Valid tags.
	$valid_tags = wp_kses_allowed_html( 'post' );
	$validate   = array(
		'itemtag',
		'captiontag',
		'icontag',
	);

	// Validate tags.
	foreach ( $validate as $tag ) {
		if ( ! isset( $valid_tags[ $atts[ $tag ] ] ) ) {
			$atts[ $tag ] = $defaults[ $tag ];
		}
	}

	// Set variables used in the output.
	$columns    = intval( $atts['columns'] );
	$size_class = sanitize_html_class( $atts['size'] );

	// WP adds the opening div in the gallery_style filter (weird), so we follow it as we don't want to break people's site.
	$gallery_div = jupiterx_open_markup(
		"jupiterx_post_gallery[_{$id}]",
		'div',
		array(
			'class'               => esc_attr( "row gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}" ), // Automatically escaped.
			'data-uk-grid-margin' => false,
		),
		$id,
		$columns
	);

	/**
	 * Apply WP core filter. Filter the default gallery shortcode CSS styles.
	 *
	 * Documented in WordPress.
	 *
	 * @ignore
	 */
	$output = apply_filters( 'gallery_style', $gallery_div ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Used in function scope.

		$i = 0; foreach ( $attachments as $attachment_id => $attachment ) {

			$attr        = ( trim( $attachment->post_excerpt ) ) ? [ 'aria-describedby' => "gallery-{$id}" ] : [];
			$image_meta  = wp_get_attachment_metadata( $attachment_id );
			$orientation = '';

		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}

		// Set the image output.
		if ( 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $attachment_id, $atts['size'], false, $attr + [ 'data-object-fit' => 'cover' ] );
		} else {
			$image_output = wp_get_attachment_link( $attachment_id, $atts['size'], ( 'file' !== $atts['link'] ), false, false, $attr );
		}
			$output .= jupiterx_open_markup( "jupiterx_post_gallery_item[_{$attachment_id}]", $atts['itemtag'], [ 'class' => 'gallery-item' ] );

				$output .= jupiterx_open_markup( "jupiterx_post_gallery_icon[_{$attachment_id}]", $atts['icontag'], array( 'class' => esc_attr( "gallery-icon {$orientation}" ) ) ); // Automatically escaped.

					$output .= jupiterx_output( "jupiterx_post_gallery_icon[_{$attachment_id}]", $image_output, $attachment_id, $atts );

				$output .= jupiterx_close_markup( "jupiterx_post_gallery_icon[_{$attachment_id}]", $atts['icontag'] );

		if ( $atts['captiontag'] && trim( $attachment->post_excerpt ) ) {
			$output .= jupiterx_open_markup( "jupiterx_post_gallery_caption[_{$attachment_id}]", $atts['captiontag'], array( 'class' => 'wp-caption-text gallery-caption' ) );

				$output .= jupiterx_output( "jupiterx_post_gallery_caption_text[_{$attachment_id}]", wptexturize( $attachment->post_excerpt ) );

			$output .= jupiterx_close_markup( "jupiterx_post_gallery_caption[_{$attachment_id}]", $atts['captiontag'] );
		}

			$output .= jupiterx_close_markup( "jupiterx_post_gallery_item[_{$attachment_id}]", $atts['itemtag'] );
		}

		$output .= jupiterx_close_markup( "jupiterx_post_gallery[_{$id}]", 'div' );

		return $output;
}
