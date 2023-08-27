<?php
/**
 * Despite its name, this template echos between the closing primary markup and the closing HTML markup.
 *
 * This template must be called using get_footer().
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

			jupiterx_close_markup_e( 'jupiterx_main', 'main' );

		jupiterx_close_markup_e( 'jupiterx_site', 'div' );

		wp_footer();

	jupiterx_close_markup_e( 'jupiterx_body', 'body' );

jupiterx_close_markup_e( 'jupiterx_html', 'html' );
