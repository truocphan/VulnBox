<?php
/**
 * Echo the posts loop structural markup. It also calls the loop action hooks.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

/**
 * Fires before the loop.
 *
 * This hook fires even if no post exists.
 *
 * @since 1.0.0
 */
do_action( 'jupiterx_before_loop' );
	// phpcs:disable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
	if ( have_posts() && ! is_404() ) :

		/**
		 * Fires before posts loop.
		 *
		 * This hook fires if posts exist.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_before_posts_loop' );

		while ( have_posts() ) :
			the_post();

			// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Variable called in a function scope.
			$article_attributes = [
				'id'        => esc_attr( get_the_ID() ), // Automatically escaped.
				'class'     => esc_attr( implode( ' ', get_post_class( is_singular() ? 'jupiterx-post' : 'jupiterx-post jupiterx-post-loop' ) ) ), // Automatically escaped.
				'itemscope' => 'itemscope',
				'itemtype'  => 'http://schema.org/CreativeWork',
			];

			// Blog specifc attributes.
			if ( 'post' === get_post_type() ) {

				$article_attributes['itemtype'] = 'http://schema.org/BlogPosting';

				// Only add to blogPost attribute to the main query.
				if ( is_main_query() && ! is_search() ) {
					$article_attributes['itemprop'] = 'blogPost';
				}
			}
			// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

			jupiterx_open_markup_e( 'jupiterx_post', 'article', $article_attributes );

				jupiterx_open_markup_e( 'jupiterx_post_body', 'div', [
					'class'    => 'jupiterx-post-body',
					'itemprop' => 'articleBody',
				] );

					/**
					 * Fires in the post body.
					 *
					 * @since 1.0.0
					 */
					do_action( 'jupiterx_post_body' );

				jupiterx_close_markup_e( 'jupiterx_post_body', 'div' );

			jupiterx_close_markup_e( 'jupiterx_post', 'article' );
		endwhile;

		/**
		 * Fires after the posts loop.
		 *
		 * This hook fires if posts exist.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_after_posts_loop' );
	else :

			/**
			 * Fires if no posts exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jupiterx_no_post' );
	endif;

/**
 * Fires after the loop.
 *
 * This hook fires even if no post exists.
 *
 * @since 1.0.0
 */
	do_action( 'jupiterx_after_loop' );
// phpcs:enable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
