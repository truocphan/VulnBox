<?php
/**
 * Echo the structural markup that wraps around comments. It also calls the comments action hooks.
 *
 * This template will return empty if the post which is called is password protected.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

// Stop here if the post is password protected.
if ( post_password_required() ) {
	return;
}

if ( ! jupiterx_post_element_enabled( 'comments' ) ) {
	return false;
}

jupiterx_open_markup_e(
	'jupiterx_comments',
	'div',
	array(
		'id'    => 'comments',
		'class' => 'jupiterx-comments',
	)
);
	// phpcs:disable Generic.WhiteSpace.ScopeIndent.IncorrectExact -- Code structure mirrors HTML markup.
	if ( comments_open() || get_comments_number() ) :

		if ( have_comments() ) :
			jupiterx_open_markup_e( 'jupiterx_comments_list', 'ol', [ 'class' => 'jupiterx-comments-list' ] );

				wp_list_comments(
					array(
						'avatar_size' => 50,
						'callback'    => 'jupiterx_comment_callback',
					)
				);

			jupiterx_close_markup_e( 'jupiterx_comments_list', 'ol' );
		else :

			/**
			 * Fires if no comments exist.
			 *
			 * This hook only fires if comments are open.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jupiterx_no_comment' );
		endif;

		/**
		 * Fires after the comments list.
		 *
		 * This hook only fires if comments are open.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_after_open_comments' );
	endif;

	if ( ! comments_open() ) :

		/**
		 * Fires if comments are closed.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_comments_closed' );
	endif;

jupiterx_close_markup_e( 'jupiterx_comments', 'div' );
//phpcs:enable Generic.WhiteSpace.ScopeIndent.IncorrectExact -- Code structure mirrors HTML markup.
