<?php
/**
 * Echo the structural markup for each comment. It also calls the comment action hooks.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

jupiterx_open_markup_e(
	'jupiterx_comment',
	'article',
	array(
		'id'        => 'comment-content-' . get_comment_ID(), // Automatically escaped.
		'class'     => 'jupiterx-comment',
		'itemprop'  => 'comment',
		'itemscope' => 'itemscope',
		'itemtype'  => 'http://schema.org/Comment',
	)
);

	/**
	 * Fires before the comment header.
	 *
	 * @since 3.0.0
	 */
	do_action( 'jupiterx_comment_before_header' );

	jupiterx_open_markup_e( 'jupiterx_comment_header', 'header', [ 'class' => 'jupiterx-comment-header' ] );

		/**
		 * Fires in the comment header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_comment_header' );

	jupiterx_close_markup_e( 'jupiterx_comment_header', 'header' );

	jupiterx_open_markup_e(
		'jupiterx_comment_body',
		'div',
		[
			'class'    => 'jupiterx-comment-body',
			'itemprop' => 'text',
		]
	);

		/**
		 * Fires in the comment body.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_comment_content' );

	jupiterx_close_markup_e( 'jupiterx_comment_body', 'div' );

	/**
	 * Fires after the body content.
	 *
	 * @since 3.0.0
	 */
	do_action( 'jupiterx_comment_after_body' );

jupiterx_close_markup_e( 'jupiterx_comment', 'article' ); // phpcs:ignore Generic.WhiteSpace.ScopeIndent.Incorrect -- Code structure mirrors HTML markup.
