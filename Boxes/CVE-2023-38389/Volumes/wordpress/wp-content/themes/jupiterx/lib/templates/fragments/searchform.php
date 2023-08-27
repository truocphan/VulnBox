<?php
/**
 * Modify the search from.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

// Filter.
jupiterx_add_smart_action( 'get_search_form', 'jupiterx_search_form' );
/**
 * Modify the search form.
 *
 * @since 1.0.0
 *
 * @return string The form.
 */
function jupiterx_search_form() {
	$output = jupiterx_open_markup(
		'jupiterx_search_form',
		'form',
		array(
			'class'  => 'jupiterx-search-form form-inline',
			'method' => 'get',
			'action' => esc_url( home_url( '/' ) ),
			'role'   => 'search',
		)
	);

		if ( class_exists( 'SitePress' ) ) {
			$output .= jupiterx_selfclose_markup(
				'jupiterx_lang_search_input',
				'input',
				[
					'type'  => 'hidden',
					'value' => ICL_LANGUAGE_CODE,
					'name'  => 'lang',
				]
			);
		}

		$output .= jupiterx_selfclose_markup(
			'jupiterx_search_form_input',
			'input',
			array(
				'class'       => 'form-control',
				'type'        => 'search',
				'placeholder' => __( 'Search', 'jupiterx' ), // Automatically escaped.
				'value'       => esc_attr( get_search_query() ),
				'name'        => 's',
			)
		);

		$output .= jupiterx_open_markup( 'jupiterx_search_form_input_button', 'button', 'class=btn jupiterx-icon-search-1' );

		$output .= jupiterx_close_markup( 'jupiterx_search_form_input_button', 'button' );

	$output .= jupiterx_close_markup( 'jupiterx_search_form', 'form' );

	return $output;
}
