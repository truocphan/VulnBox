<?php
/**
 * Opening markups of main section.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

/**
 * Fires in the main header.
 *
 * @since 1.0.0
 */
do_action( 'jupiterx_main_header' );

jupiterx_open_markup_e( 'jupiterx_main_content', 'div', [ 'class' => 'jupiterx-main-content' ] );

	$container = 'container';

	if ( jupiterx_get_field( 'jupiterx_content_full_width' ) ) { // phpcs:ignore
		$container = 'container-fluid';
	} // phpcs:ignore

	jupiterx_open_markup_e( 'jupiterx_fixed_wrap[_main_content]', 'div', 'class=' . $container );

		jupiterx_open_markup_e(
			'jupiterx_main_grid',
			'div',
			array(
				'class' => 'row',
			)
		);

			jupiterx_open_markup_e(
				'jupiterx_primary',
				'div',
				[
					'id'    => 'jupiterx-primary',
					'class' => 'jupiterx-primary ' . jupiterx_get_layout_class( 'content' ),
				]
			);
