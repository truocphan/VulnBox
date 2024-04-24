<?php
/**
 * Masteriyo formatting functions.
 *
 * @since 1.0.0
 */

use Masteriyo\DateTime;
use Masteriyo\Constants;

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @since 1.0.0
 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
 * @return bool
 */
function masteriyo_string_to_bool( $string ) {
	if ( is_bool( $string ) ) {
		return $string;
	}

	$string = strtolower( $string );

	return ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

/**
 * Converts a bool to a 'yes' or 'no'.
 *
 * @since 1.0.0
 * @param bool|string $bool Bool to convert. If a string is passed it will first be converted to a bool.
 * @return string
 */
function masteriyo_bool_to_string( $bool ) {
	if ( ! is_bool( $bool ) ) {
		$bool = masteriyo_string_to_bool( $bool );
	}
	return true === $bool ? 'yes' : 'no';
}

/**
 * Convert a date string to a DateTime.
 *
 * @since  1.0.0
 * @param  string $time_string Time string.
 * @return Masteriyo\DateTime
 */
function masteriyo_string_to_datetime( $time_string ) {
	// Strings are defined in local WP timezone. Convert to UTC.
	if ( 1 === preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|((-|\+)\d{2}:\d{2}))$/', $time_string, $date_bits ) ) {
		$offset    = ! empty( $date_bits[7] ) ? iso8601_timezone_to_offset( $date_bits[7] ) : masteriyo_timezone_offset();
		$timestamp = gmmktime( $date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1] ) - $offset;
	} else {
		$timestamp = masteriyo_string_to_timestamp( get_gmt_from_date( gmdate( 'Y-m-d H:i:s', masteriyo_string_to_timestamp( $time_string ) ) ) );
	}
	$datetime = new DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );

	// Set local timezone or offset.
	if ( get_option( 'timezone_string' ) ) {
		$datetime->setTimezone( new DateTimeZone( masteriyo_timezone_string() ) );
	} else {
		$datetime->set_utc_offset( masteriyo_timezone_offset() );
	}

	return $datetime;
}

/**
 * Get timezone offset in seconds.
 *
 * @since  1.0.0
 * @return float
 */
function masteriyo_timezone_offset() {
	$timezone = get_option( 'timezone_string' );

	if ( $timezone ) {
		$timezone_object = new DateTimeZone( $timezone );
		return $timezone_object->getOffset( new DateTime( 'now' ) );
	} else {
		return floatval( get_option( 'gmt_offset', 0 ) ) * HOUR_IN_SECONDS;
	}
}

/**
 * Convert mysql datetime to PHP timestamp, forcing UTC. Wrapper for strtotime.
 *
 * Based on wcs_strtotime_dark_knight() from WooCommerce Subscriptions by Prospress.
 *
 * @since  1.0.0
 * @param  string   $time_string    Time string.
 * @param  int|null $from_timestamp Timestamp to convert from.
 * @return int
 */
function masteriyo_string_to_timestamp( $time_string, $from_timestamp = null ) {
	$original_timezone = date_default_timezone_get();

	// phpcs:disable
	date_default_timezone_set( 'UTC' );

	if ( null === $from_timestamp ) {
		$next_timestamp = strtotime( $time_string );
	} else {
		$next_timestamp = strtotime( $time_string, $from_timestamp );
	}

	date_default_timezone_set( $original_timezone );
	// phpcs:enable

	return $next_timestamp;
}


/**
 * Masteriyo Timezone - helper to retrieve the timezone string for a site until.
 * a WP core method exists (see https://core.trac.wordpress.org/ticket/24730).
 *
 * Adapted from https://secure.php.net/manual/en/function.timezone-name-from-abbr.php#89155.
 *
 * @since 1.0.0
 * @return string PHP timezone string for the site
 */
function masteriyo_timezone_string() {
	// Added in WordPress 5.3 Ref https://developer.wordpress.org/reference/functions/wp_timezone_string/.
	if ( function_exists( 'wp_timezone_string' ) ) {
		return wp_timezone_string();
	}

	// If site timezone string exists, return it.
	$timezone = get_option( 'timezone_string' );
	if ( $timezone ) {
		return $timezone;
	}

	// Get UTC offset, if it isn't set then return UTC.
	$utc_offset = floatval( get_option( 'gmt_offset', 0 ) );
	if ( ! is_numeric( $utc_offset ) || 0.0 === $utc_offset ) {
		return 'UTC';
	}

	// Adjust UTC offset from hours to seconds.
	$utc_offset = (int) ( $utc_offset * 3600 );

	// Attempt to guess the timezone string from the UTC offset.
	$timezone = timezone_name_from_abbr( '', $utc_offset );
	if ( $timezone ) {
		return $timezone;
	}

	// Last try, guess timezone string manually.
	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			// WordPress restrict the use of date(), since it's affected by timezone settings, but in this case is just what we need to guess the correct timezone.
			if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && intval( $city['offset'] ) === $utc_offset ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				return $city['timezone_id'];
			}
		}
	}

	// Fallback to UTC.
	return 'UTC';
}

/**
 * Format decimal numbers ready for DB storage.
 *
 * Sanitize, remove decimals, and optionally round + trim off zeros.
 *
 * This function does not remove thousands - this should be done before passing a value to the function.
 *
 * @since 1.0.0
 *
 * @param  float|string $number     Expects either a float or a string with a decimal separator only (no thousands).
 * @param  mixed        $dp number  Number of decimal points to use, blank to use masteriyo_price_num_decimals, or false to avoid all rounding.
 * @param  bool         $trim_zeros From end of string.
 * @return string
 */
function masteriyo_format_decimal( $number, $dp = false, $trim_zeros = false ) {
	$number = $number ?? '';

	$locale   = localeconv();
	$decimals = array( masteriyo_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );

	// Remove locale from string.
	if ( ! is_float( $number ) ) {
		$number = str_replace( $decimals, '.', $number );

		// Convert multiple dots to just one.
		$number = preg_replace( '/\.(?![^.]+$)|[^0-9.-]/', '', masteriyo_clean( $number ) );
	}

	if ( false !== $dp ) {
		$dp     = intval( '' === $dp ? masteriyo_get_price_decimals() : $dp );
		$number = number_format( floatval( $number ), $dp, '.', '' );
	} elseif ( is_float( $number ) ) {
		// DP is false - don't use number format, just return a string using whatever is given. Remove scientific notation using sprintf.
		$number = str_replace( $decimals, '.', sprintf( '%.' . masteriyo_get_rounding_precision() . 'f', $number ) );
		// We already had a float, so trailing zeros are not needed.
		$trim_zeros = true;
	}

	if ( $trim_zeros && strstr( $number, '.' ) ) {
		$number = rtrim( rtrim( $number, '0' ), '.' );
	}

	return $number;
}

/**
 * Return the thousand separator for prices.
 *
 * @since  1.0.0
 * @return string
 */
function masteriyo_get_price_thousand_separator() {
	$separator = masteriyo_get_setting( 'payments.currency.thousand_separator' );

	/**
	 * Filters thousand separator for price.
	 *
	 * @since 1.0.0
	 *
	 * @param string $thousand_separator The thousand separator for price.
	 */
	$separator = apply_filters( 'masteriyo_get_price_thousand_separator', $separator );

	return stripslashes( $separator );
}

/**
 * Return the decimal separator for prices.
 *
 * @since  1.0.0
 * @return string
 */
function masteriyo_get_price_decimal_separator() {
	$separator = masteriyo_get_setting( 'payments.currency.decimal_separator' );

	/**
	 * Filters decimal separator for price.
	 *
	 * @since 1.0.0
	 *
	 * @param string $decimal_separator The decimal separator for price.
	 */
	$separator = apply_filters( 'masteriyo_get_price_decimal_separator', $separator );

	return $separator ? stripslashes( $separator ) : '.';
}

/**
 * Return the number of decimals after the decimal point.
 *
 * @since  1.0.0
 * @return int
 */
function masteriyo_get_price_decimals() {
	$num_decimals = masteriyo_get_setting( 'payments.currency.number_of_decimals' );

	/**
	 * Filters the number of decimals after the decimal point.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $num_decimals The number of decimals after the decimal point.
	 */
	$num_decimals = apply_filters( 'masteriyo_get_price_decimals', $num_decimals );

	return absint( $num_decimals );
}

/**
 * Format the price with a currency symbol.
 *
 * @since 1.0.0
 *
 * @param  float $price Raw price.
 * @param  array $args  Arguments to format a price {
 *     Array of arguments.
 *     Defaults to empty array.
 *
 *     @type bool   $ex_tax_label       Adds exclude tax label.
 *                                      Defaults to false.
 *     @type string $currency           Currency code.
 *                                      Defaults to empty string (Use the result from get_masteriyo_currency()).
 *     @type string $decimal_separator  Decimal separator.
 *                                      Defaults the result of get_price_decimal_separator().
 *     @type string $thousand_separator Thousand separator.
 *                                      Defaults the result of get_price_thousand_separator().
 *     @type string $decimals           Number of decimals.
 *                                      Defaults the result of get_price_decimals().
 *     @type string $price_format       Price format depending on the currency position.
 *                                      Defaults the result of get_masteriyo_price_format().
 *     @type boolean $html             Whether to format price with HTML or not.
 *                                      Defaults to true.
 * }
 * @return string
 */
function masteriyo_price( $price, $args = array() ) {
	/**
	 * Filters price arguments like currency, decimal separator etc.
	 *
	 * @since 1.0.0
	 * @since 1.5.36 Added html arguments, whether to format price with HTML.
	 * @since 1.5.36 Added show_price_free_text parameter.
	 *
	 * @param array $args Price arguments like currency, decimal separator etc.
	 */
	$args = apply_filters(
		'masteriyo_price_args',
		wp_parse_args(
			$args,
			array(
				'currency'             => '',
				'decimal_separator'    => masteriyo_get_price_decimal_separator(),
				'thousand_separator'   => masteriyo_get_price_thousand_separator(),
				'decimals'             => masteriyo_get_price_decimals(),
				'price_format'         => masteriyo_get_price_format(),
				'html'                 => true,
				'show_price_free_text' => true, // True for showing the text "Free" when price is zero. False to disable this behavior.
			)
		)
	);

	$original_price = $price;

	// Convert to float to avoid issues on PHP 8.
	$price = (float) $price;

	$unformatted_price = $price;
	$negative          = $price < 0;

	// @see https://www.php.net/manual/en/function.abs.php for why we are not using abs() instead.
	$price = $negative ? $price * -1 : $price;

	/**
	 * Filters raw price value.
	 *
	 * @since 1.0.0
	 *
	 * @since 1.6.0 Added original price.
	 *
	 * @param float $aw_price Raw price.
	 * @param float|string $original_price Original price float, or empty string. Since 1.6.0
	 */
	$price = apply_filters( 'masteriyo_raw_price', $price, $original_price );

	$price = number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

	/**
	 * Filters formatted price value.
	 *
	 * @since 1.0.0
	 * @since 1.6.0 Added original price.
	 *
	 * @param string $price Formatted price value.
	 * @param float $unformatted_price Unformatted price value.
	 * @param integer $decimals The number of decimals after the decimal point.
	 * @param string $decimal_separator Decimal separator.
	 * @param string $thousand_separator Thousand separator.
	 * @param float|string $original_price Original price as float or empty string. Since 1.6.0
	 */
	$price = apply_filters( 'masteriyo_formatted_price', $price, $unformatted_price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'], $original_price );

	/**
	 * Filters boolean: true if zeros should be trimmed from price.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $bool true if zeros should be trimmed from price.
	 */
	if ( apply_filters( 'masteriyo_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
		$price = masteriyo_trim_zeros( $price );
	}

	if ( masteriyo_string_to_bool( $args['show_price_free_text'] ) && 0 === intval( masteriyo_round( $price ) ) ) {
		/**
		 * Filter to change the 'Free' text instead of $0.00.
		 *
		 * @since 1.0.7
		 *
		 * @param string Free text.
		 * @param string $price Price.
		 */
		$formatted_price = apply_filters( 'masteriyo_price_free_text', _x( 'Free', 'Course price text', 'masteriyo' ), $price );
	} else {
		if ( $args['html'] ) {
			$formatted_price = sprintf( $args['price_format'], '<span class="masteriyo-price-currencySymbol">' . masteriyo_get_currency_symbol( $args['currency'] ) . '</span>', $price );
		} else {
			$formatted_price = sprintf( $args['price_format'], masteriyo_get_currency_symbol( $args['currency'] ), $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . $formatted_price;
	}

	if ( $args['html'] ) {
		$html = '<span class="masteriyo-price-amount amount"><bdi>' . $formatted_price . '</bdi></span>';
	} else {
		$html = html_entity_decode( $formatted_price );
	}

	/**
	 * Filters the string of price markup.
	 *
	 * @since 1.0.0
	 * @since 1.6.0 Added original price.
	 *
	 * @param string $html              Price HTML markup depends upon the $html argument.
	 * @param string $price             Formatted price.
	 * @param array  $args              Pass on the args.
	 * @param float  $unformatted_price Price as float to allow plugins custom formatting.
	 * @param float|string $original_price Original price as float or empty string. Since 1.6.0
	 */
	return apply_filters( 'masteriyo_price', $html, $price, $args, $unformatted_price );
}

/**
 * Get the price format depending on the currency position.
 *
 * @since 1.0.0
 *
 * @return string
 */
function masteriyo_get_price_format() {
	$currency_pos = masteriyo_get_setting( 'payments.currency.currency_position' );
	$format       = '%1$s%2$s';

	switch ( $currency_pos ) {
		case 'left':
			$format = '%1$s%2$s';
			break;
		case 'right':
			$format = '%2$s%1$s';
			break;
		case 'left_space':
			$format = '%1$s&nbsp;%2$s';
			break;
		case 'right_space':
			$format = '%2$s&nbsp;%1$s';
			break;
	}

	/**
	 * Filters price format depending on the currency position.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format The price format depending on the currency position
	 * @param string $currency_pos Currency position: left, right, left_space or right_space.
	 */
	return apply_filters( 'masteriyo_price_format', $format, $currency_pos );
}

/**
 * Get rounding precision for internal MASTERIYO calculations.
 *
 * @since 1.0.0
 * @return int
 */
function masteriyo_get_rounding_precision() {
	$precision          = masteriyo_get_price_decimals() + 2;
	$rounding_precision = absint( Constants::get( 'MASTERIYO_ROUNDING_PRECISION' ) );

	$precision = ( $rounding_precision > $precision ) ? $rounding_precision : $precision;

	return $precision;
}

/**
 * Trim trailing zeros off prices.
 *
 * @since 1.0.0
 *
 * @param string|float|int $price Price.
 * @return string
 */
function masteriyo_trim_zeros( $price ) {
	return preg_replace( '/' . preg_quote( masteriyo_get_price_decimal_separator(), '/' ) . '0++$/', '', $price );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @since 1.0.0
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function masteriyo_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'masteriyo_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}


/**
 * Masteriyo Date Format - Allows to change date format for everything Masteriyo.
 *
 * @since 1.0.0
 *
 * @return string
 */
function masteriyo_date_format() {
	$date_format = get_option( 'date_format' );

	if ( empty( $date_format ) ) {
		// Return default date format if the option is empty.
		$date_format = 'F j, Y';
	}

	/**
	 * Filters date format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_format Date format. Default is "F j, Y" if date_format option is empty.
	 */
	return apply_filters( 'masteriyo_date_format', $date_format );
}

/**
 * Masteriyo Time Format - Allows to change time format for everything Masteriyo.
 *
 * @since 1.0.0
 *
 * @return string
 */
function masteriyo_time_format() {
	$time_format = get_option( 'time_format' );
	if ( empty( $time_format ) ) {
		// Return default time format if the option is empty.
		$time_format = 'g:i a';
	}

	/**
	 * Filters time format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $time_format Time format. Default is "g:i a" if time_format option is empty.
	 */
	return apply_filters( 'masteriyo_time_format', $time_format );
}

/**
 * Sanitize permalink values before insertion into DB.
 *
 * Cannot use masteriyo_clean because it sometimes strips % chars and breaks the user's setting.
 *
 * @since  1.0.0
 * @param  string $value Permalink.
 * @return string
 */
function masteriyo_sanitize_permalink( $value ) {
	global $wpdb;

	$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

	if ( is_wp_error( $value ) ) {
		$value = '';
	}

	$value = esc_url_raw( trim( $value ) );
	$value = str_replace( 'http://', '', $value );
	return untrailingslashit( $value );
}

/**
 * Make a string lowercase.
 * Try to use mb_strtolower() when available.
 *
 * @since  1.0.0
 *
 * @param  string $string String to format.
 *
 * @return string
 */
function masteriyo_strtolower( $string ) {
	return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
}

/**
 * Wrapper for mb_strtoupper which see's if supported first.
 *
 * @since  1.0.0
 * @param  string $string String to format.
 * @return string
 */
function masteriyo_strtoupper( $string ) {
	return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $string ) : strtoupper( $string );
}

/**
 * Formats a stock amount by running it through a filter.
 *
 * @since 1.0.0
 *
 * @param  int|float $amount Stock amount.
 * @return int|float
 */
function masteriyo_stock_amount( $amount ) {
	/**
	 * Filters a stock amount formatted by running it through a filter.
	 *
	 * @since 1.0.0
	 *
	 * @param int|float $amount The stock amount.
	 */
	return apply_filters( 'masteriyo_stock_amount', $amount );
}

/**
 * Round a number using the built-in `round` function, but unless the value to round is numeric
 * (a number or a string that can be parsed as a number), apply 'floatval' first to it
 * (so it will convert it to 0 in most cases).
 *
 * This is needed because in PHP 7 applying `round` to a non-numeric value returns 0,
 * but in PHP 8 it throws an error. Specifically, in Masteriyo we have a few places where
 * round('') is often executed.
 *
 * @since 1.0.0
 *
 * @param mixed $val The value to round.
 * @param int   $precision The optional number of decimal digits to round to.
 * @param int   $mode A constant to specify the mode in which rounding occurs.
 *
 * @return float The value rounded to the given precision as a float, or the supplied default value.
 */
function masteriyo_round( $val, $precision = 0, $mode = PHP_ROUND_HALF_UP ) {
	$precision = (int) $precision;
	$mode      = (int) $mode;
	$val       = is_numeric( $val ) ? $val : (float) $val;

	return round( $val, $precision, $mode );
}

/**
 * Format the postcode according to the country and length of the postcode.
 *
 * @since 1.0.0
 *
 * @param string $postcode Unformatted postcode.
 * @param string $country  Base country.
 * @return string
 */
function masteriyo_format_postcode( $postcode, $country ) {
	$postcode = masteriyo_normalize_postcode( $postcode );

	switch ( $country ) {
		case 'CA':
		case 'GB':
			$postcode = substr_replace( $postcode, ' ', -3, 0 );
			break;
		case 'IE':
			$postcode = substr_replace( $postcode, ' ', 3, 0 );
			break;
		case 'BR':
		case 'PL':
			$postcode = substr_replace( $postcode, '-', -3, 0 );
			break;
		case 'JP':
			$postcode = substr_replace( $postcode, '-', 3, 0 );
			break;
		case 'PT':
			$postcode = substr_replace( $postcode, '-', 4, 0 );
			break;
		case 'PR':
		case 'US':
			$postcode = rtrim( substr_replace( $postcode, '-', 5, 0 ), '-' );
			break;
		case 'NL':
			$postcode = substr_replace( $postcode, ' ', 4, 0 );
			break;
	}

	/**
	 * Filters the formatted postcode according to the country and its length.
	 *
	 * @since 1.0.0
	 *
	 * @param string $postcode The formatted postcode according to the country and its length.
	 * @param string $country Country code.
	 */
	return apply_filters( 'masteriyo_format_postcode', trim( $postcode ), $country );
}

/**
 * Normalize postcodes.
 *
 * Remove spaces and convert characters to uppercase.
 *
 * @since 1.0.0
 * @param string $postcode Postcode.
 * @return string
 */
function masteriyo_normalize_postcode( $postcode ) {
	return preg_replace( '/[\s\-]/', '', trim( masteriyo_strtoupper( $postcode ) ) );
}

/**
 * Format phone numbers.
 *
 * @since 1.0.0
 *
 * @param string $phone Phone number.
 * @return string
 */
function masteriyo_format_phone_number( $phone ) {
	if ( ! MASTERIYO_Validation::is_phone( $phone ) ) {
		return '';
	}
	return preg_replace( '/[^0-9\+\-\(\)\s]/', '-', preg_replace( '/[\x00-\x1F\x7F-\xFF]/', '', $phone ) );
}

/**
 * Sanitize phone number.
 * Allows only numbers and "+" (plus sign).
 *
 * @since 1.0.0
 * @param string $phone Phone number.
 * @return string
 */
function masteriyo_sanitize_phone_number( $phone ) {
	return preg_replace( '/[^\d+]/', '', $phone );
}

if ( ! function_exists( 'masteriyo_format_rating' ) ) {
	/**
	 * Format rating to SVGs.
	 *
	 * @since 1.0.0
	 *
	 * @param float $rating
	 * @param boolean $echo
	 * @return mixed
	 */
	function masteriyo_format_rating( $rating, $echo = false ) {
		$rating      = abs( $rating );
		$rating      = masteriyo_round( $rating, 1 );
		$whole       = floor( $rating );
		$whole       = min( $whole, 5 );
		$fraction    = $rating - $whole;
		$count_stars = 0;
		$html        = '';

		// Set full stars.
		for ( $count = 0; $count < $whole; ++$count ) {
			++$count_stars;
			$html .= masteriyo_get_svg( 'full_star' );
		}

		// Set half star if there is.
		if ( $fraction > 0.4 ) {
			++$count_stars;
			$html .= masteriyo_get_svg( 'half_star' );
		}

		// Set the empty stars.
		for ( ; $count_stars < 5; ++$count_stars ) {
			$html .= masteriyo_get_svg( 'empty_star' );
		}

		$svg_args = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'    => true,
				'fill' => true,
			),
		);

		if ( $echo ) {
			echo wp_kses( $html, $svg_args );
		} else {
			return $html;
		}
	}
}

/**
 * Trim a string and append a suffix.
 *
 * @since 1.0.0
 *
 * @param  string  $string String to trim.
 * @param  integer $chars  Amount of characters.
 *                         Defaults to 200.
 * @param  string  $suffix Suffix.
 *                         Defaults to '...'.
 * @return string
 */
function masteriyo_trim_string( $string, $chars = 200, $suffix = '...' ) {
	if ( strlen( $string ) > $chars ) {
		if ( function_exists( 'mb_substr' ) ) {
			$string = mb_substr( $string, 0, ( $chars - mb_strlen( $suffix ) ) ) . $suffix;
		} else {
			$string = substr( $string, 0, ( $chars - strlen( $suffix ) ) ) . $suffix;
		}
	}
	return $string;
}

/**
 * Convert a float to a string without locale formatting which PHP adds when changing floats to strings.
 *
 * @since 1.0.0
 *
 * @param  float $float Float value to format.
 * @return string
 */
function masteriyo_float_to_string( $float ) {
	if ( ! is_float( $float ) ) {
		return $float;
	}

	$locale = localeconv();
	$string = strval( $float );
	$string = str_replace( $locale['decimal_point'], '.', $string );

	return $string;
}

/**
 * Format a date for output.
 *
 * @since  1.0.0
 * @param  Masteriyo\DateTime $date   Instance of Masteriyo\DateTime.
 * @param  string      $format Data format.
 *                             Defaults to the masteriyo_date_format function if not set.
 * @return string
 */
function masteriyo_format_datetime( $date, $format = '' ) {
	if ( empty( $format ) ) {
		$format = masteriyo_date_format();
	}

	if ( ! is_a( $date, 'Masteriyo\DateTime' ) ) {
		return '';
	}

	return $date->date_i18n( $format );
}

/**
 * Parses and formats a date for ISO8601/RFC3339.
 *
 * Required WP 4.4 or later.
 * See https://developer.wordpress.org/reference/functions/mysql_to_rfc3339/
 *
 * @since  1.0.0
 * @param  string|null|Masteriyo\DateTime $date Date.
 * @param  bool                    $utc  Send false to get local/offset time.
 * @return string|null ISO8601/RFC3339 formatted datetime.
 */
function masteriyo_rest_prepare_date_response( $date, $utc = true ) {
	if ( is_numeric( $date ) ) {
		$date = new DateTime( "@$date", new \DateTimeZone( 'UTC' ) );
		$date->setTimezone( new \DateTimeZone( masteriyo_timezone_string() ) );
	} elseif ( is_string( $date ) ) {
		$date = new DateTime( $date, new \DateTimeZone( 'UTC' ) );
		$date->setTimezone( new \DateTimeZone( masteriyo_timezone_string() ) );
	}

	if ( ! is_a( $date, 'Masteriyo\DateTime' ) ) {
		return null;
	}

	// Get timestamp before changing timezone to UTC.
	return gmdate( 'Y-m-d\TH:i:s\Z', $utc ? $date->getTimestamp() : $date->getOffsetTimestamp() );
}

/**
 * Convert plaintext phone number to clickable phone number.
 *
 * Remove formatting and allow "+".
 * Example and specs: https://developer.mozilla.org/en/docs/Web/HTML/Element/a#Creating_a_phone_link
 *
 * @since 1.0.0
 *
 * @param string $phone Content to convert phone number.
 * @return string Content with converted phone number.
 */
function masteriyo_make_phone_clickable( $phone ) {
	$number = trim( preg_replace( '/[^\d|\+]/', '', $phone ) );

	return $number ? '<a href="tel:' . esc_attr( $number ) . '">' . esc_html( $phone ) . '</a>' : '';
}

/**
 * Convert kebab-case to PascalCase
 *
 * @since 1.3.4
 *
 * @param string $text
 * @return string
 */
function masteriyo_kebab_to_pascal( $text ) {
	return str_replace( ' ', '', ucwords( str_replace( '-', ' ', $text ) ) );
}

/**
 * Convert snake_case to PascalCase
 *
 * @since 1.3.4
 *
 * @param string $text
 * @return string
 */
function masteriyo_snake_to_pascal( $text ) {
	return str_replace( ' ', '', ucwords( str_replace( '_', ' ', $text ) ) );
}

/**
 * Convert snake_case to camelCase.
 *
 * @since 1.3.4
 *
 * @param string $text
 * @return string
 */
function masteriyo_snake_to_camel( $text ) {
	return lcfirst( masteriyo_snake_to_pascal( $text ) );
}

/**
 * Convert kebab-case to camelCase.
 *
 * @since 1.3.4
 *
 * @param string $text
 * @return string
 */
function masteriyo_kebab_to_camel( $text ) {
	return lcfirst( masteriyo_kebab_to_pascal( $text ) );
}


/**
 * Convert kebab-case to snake_case.
 *
 * @since 1.3.4
 *
 * @param string $text
 * @return string
 */
function masteriyo_kebab_to_snake( $text ) {
	return str_replace( ' ', '', str_replace( '-', '_', $text ) );
}


/**
 * Convert snake_case to kebab-case.
 *
 * @since 1.3.4
 *
 * @param string $text
 * @return string
 */
function masteriyo_snake_to_kebab( $text ) {
	return str_replace( ' ', '', str_replace( '_', '-', $text ) );
}

/**
 * Format course highlights.
 *
 * e.g. <ul><li></li></ul>
 *
 * @since 1.4.4
 *
 * @param string $highlights
 * @return string
 */
function masteriyo_format_course_highlights( $highlights ) {
	if ( ! masteriyo_starts_with( '<ul>', $highlights ) ) {
		$highlights = '<ul>' . $highlights . '</ul>';
	}

	return $highlights;
}
