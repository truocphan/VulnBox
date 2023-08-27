<?php
/**
 * Despite its name, this template echos between the opening HTML markup and the opening primary markup.
 *
 * This template must be called using get_header().
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

jupiterx_output_e( 'jupiterx_doctype', '<!DOCTYPE html>' );

jupiterx_open_markup_e( 'jupiterx_html', 'html', str_replace( '"', '', str_replace( '" ', '&', jupiterx_render_function( 'language_attributes' ) ) ) );

	jupiterx_open_markup_e( 'jupiterx_head', 'head' );

		/**
		 * Fires in the head.
		 *
		 * This hook fires in the head HTML section, not in wp_header().
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_head' );

		wp_head();

	jupiterx_close_markup_e( 'jupiterx_head', 'head' );

	jupiterx_open_markup_e(
		'jupiterx_body',
		'body',
		[
			'class'     => esc_attr( implode( ' ', get_body_class( 'no-js' ) ) ),
			'itemscope' => 'itemscope',
			'itemtype'  => 'http://schema.org/WebPage',
		]
	);

		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		}

		jupiterx_open_markup_e(
			'jupiterx_a11y_skip_navigation_link',
			'a',
			[
				'class'     => 'jupiterx-a11y jupiterx-a11y-skip-navigation-link',
				'href'      => '#jupiterx-main',
			]
		);

			esc_html_e( 'Skip to content', 'jupiterx' );

		jupiterx_close_markup_e( 'jupiterx_a11y_skip_navigation_link', 'a' );

		jupiterx_open_markup_e( 'jupiterx_site', 'div', [ 'class' => 'jupiterx-site' ] );

			jupiterx_open_markup_e(
				'jupiterx_main',
				'main',
				[
					'id' => 'jupiterx-main',
					'class' => 'jupiterx-main',
				]
			);
