<?php
/**
 * Utility Shortcodes.
 *
 * @package JupiterX_Core\Utilities
 */

add_shortcode( 'jupiterx_current_date', 'jupiterx_current_date_shortcode' );
/**
 * Return current date.
 *
 * @since 1.16.0
 *
 * @param array $atts shortcode attribute date format.
 *
 * @return string date format.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_current_date_shortcode( $atts ) {

	/**
	 * Shortcode attributes.
	 * [current_date format=’d/m/Y’] =>  01/05/2020
	 * [current_date format=’F d, Y’] => Feb 04, 2020
	 */

	$atts = shortcode_atts(
		[
			'format' => '',
		], $atts
	);

	if ( ! empty( $atts['format'] ) ) {
		$date_format = $atts['format'];
	} else {
		$date_format = 'l jS \of F Y h:i:s A';
	}

	if ( 'z' === $date_format ) {
		return date_i18n( $date_format ) + 1;
	} else {
		return date_i18n( $date_format );
	}

}
