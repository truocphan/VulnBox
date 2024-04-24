<?php
/**
 * Utility functions.
 *
 * @since 1.0.0
 */

/**
 * Array merge and sum function.
 *
 * Source:  https://gist.github.com/Nickology/f700e319cbafab5eaedc
 *
 * @since 1.0.0
 * @return array
 */
function masteriyo_array_merge_recursive_numeric() {
	$arrays = func_get_args();

	// If there's only one array, it's already merged.
	if ( 1 === count( $arrays ) ) {
		return $arrays[0];
	}

	// Remove any items in $arrays that are NOT arrays.
	foreach ( $arrays as $key => $array ) {
		if ( ! is_array( $array ) ) {
			unset( $arrays[ $key ] );
		}
	}

	// We start by setting the first array as our final array.
	// We will merge all other arrays with this one.
	$final = array_shift( $arrays );

	foreach ( $arrays as $b ) {
		foreach ( $final as $key => $value ) {
			// If $key does not exist in $b, then it is unique and can be safely merged.
			if ( ! isset( $b[ $key ] ) ) {
				$final[ $key ] = $value;
			} else {
				// If $key is present in $b, then we need to merge and sum numeric values in both.
				if ( is_numeric( $value ) && is_numeric( $b[ $key ] ) ) {
					// If both values for these keys are numeric, we sum them.
					$final[ $key ] = $value + $b[ $key ];
				} elseif ( is_array( $value ) && is_array( $b[ $key ] ) ) {
					// If both values are arrays, we recursively call ourself.
					$final[ $key ] = masteriyo_array_merge_recursive_numeric( $value, $b[ $key ] );
				} else {
					// If both keys exist but differ in type, then we cannot merge them.
					// In this scenario, we will $b's value for $key is used.
					$final[ $key ] = $b[ $key ];
				}
			}
		}

		// Finally, we need to merge any keys that exist only in $b.
		foreach ( $b as $key => $value ) {
			if ( ! isset( $final[ $key ] ) ) {
				$final[ $key ] = $value;
			}
		}
	}

	return $final;
}

/**
 * Merge two arrays.
 *
 * @since 1.0.0
 *
 * @param array $a1 First array to merge.
 * @param array $a2 Second array to merge.
 * @return array
 */
function masteriyo_array_overlay( $a1, $a2 ) {
	foreach ( $a1 as $k => $v ) {
		if ( ! array_key_exists( $k, $a2 ) ) {
			continue;
		}
		if ( is_array( $v ) && is_array( $a2[ $k ] ) ) {
			$a1[ $k ] = masteriyo_array_overlay( $v, $a2[ $k ] );
		} else {
			$a1[ $k ] = $a2[ $k ];
		}
	}
	return $a1;
}

/**
 * Flatten a multi-dimensional associative array with dots.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  string  $prepend
 * @return array
 */
function masteriyo_array_dot( $array, $prepend = '' ) {
	$results = array();

	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) && ! empty( $value ) ) {
			$results = array_merge( $results, masteriyo_array_dot( $value, $prepend . $key . '.' ) );
		} else {
			$results[ $prepend . $key ] = $value;
		}
	}

	return $results;
}

/**
 * Convert a flatten "dot" notation array into an expanded array.
 *
 * @since 1.3.6
 *
 * @param  iterable  $array
 * @return array
 */
function masteriyo_array_undot( $array ) {
	$results = array();

	foreach ( $array as $key => $value ) {
		masteriyo_array_set( $results, $key, $value );
	}

	return $results;
}

/**
 * Set an array item to a given value using "dot" notation.
 *
 * If no key is given to the method, the entire array will be replaced.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  string|null  $key
 * @param  mixed  $value
 * @return array
 */
function masteriyo_array_set( &$array, $key, $value ) {
	if ( is_null( $key ) ) {
		$array = $value;
		return $array;
	}

	$keys = explode( '.', $key );

	foreach ( $keys as $i => $key ) {
		if ( count( $keys ) === 1 ) {
			break;
		}

		unset( $keys[ $i ] );

		// If the key doesn't exist at this depth, we will just create an empty array
		// to hold the next value, allowing us to create the arrays to hold final
		// values at the correct depth. Then we'll keep digging into the array.
		if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
			$array[ $key ] = array();
		}

		$array = &$array[ $key ];
	}

	$array[ array_shift( $keys ) ] = $value;

	return $array;
}

/**
 * Shuffle the given array and return the result.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  int|null  $seed
 * @return array
 */
function masteriyo_array_shuffle( $array, $seed = null ) {
	if ( is_null( $seed ) ) {
		shuffle( $array );
	} else {
		wp_rand( $seed );
		shuffle( $array );
		wp_rand();
	}

	return $array;
}

/**
 * Determine whether the given value is array accessible.
 *
 * @since 1.3.6
 *
 * @param  mixed  $value
 * @return bool
 */
function masteriyo_array_is_accessible( $value ) {
	return is_array( $value ) || $value instanceof \ArrayAccess;
}

/**
 * Add an element to an array using "dot" notation if it doesn't exist.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  string  $key
 * @param  mixed  $value
 * @return array
 */
function masteriyo_array_add( $array, $key, $value ) {
	if ( is_null( masteriyo_array_get( $array, $key ) ) ) {
		masteriyo_array_set( $array, $key, $value );
	}

	return $array;
}

/**
 * Divide an array into two arrays. One with keys and the other with values.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @return array
 */
function masteriyo_array_divide( $array ) {
	return array( array_keys( $array ), array_values( $array ) );
}

/**
 * Get all of the given array except for a specified array of keys.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  array|string  $keys
 * @return array
 */
function masteriyo_array_except( $array, $keys ) {
	masteriyo_array_forget( $array, $keys );

	return $array;
}

/**
 * Return the first element in an array passing a given truth test.
 *
 * @since 1.3.6
 *
 * @param  iterable|array  $array
 * @param  callable|null  $callback
 * @param  mixed  $default
 * @return mixed
 */
function masteriyo_array_first( $array, callable $callback = null, $default = null ) {
	if ( is_null( $callback ) ) {
		if ( empty( $array ) ) {
			return masteriyo_value( $default );
		}

		foreach ( $array as $item ) {
			return $item;
		}
	}

	foreach ( $array as $key => $value ) {
		if ( $callback( $value, $key ) ) {
			return $value;
		}
	}

	return masteriyo_value( $default );
}

/**
 * Return the last element in an array passing a given truth test.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  callable|null  $callback
 * @param  mixed  $default
 * @return mixed
 */
function masteriyo_array_last( $array, callable $callback = null, $default = null ) {
	if ( is_null( $callback ) ) {
		return empty( $array ) ? masteriyo_value( $default ) : end( $array );
	}

	return masteriyo_array_first( array_reverse( $array, true ), $callback, $default );
}

/**
 * Get an item from an array using "dot" notation.
 *
 * @since 1.3.6
 *
 * @param  \ArrayAccess|array  $array
 * @param  string|int|null  $key
 * @param  mixed  $default
 * @return mixed
 */
function masteriyo_array_get( $array, $key, $default = null ) {
	if ( ! masteriyo_array_is_accessible( $array ) ) {
		return masteriyo_value( $default );
	}

	if ( is_null( $key ) ) {
		return $array;
	}

	if ( masteriyo_array_exists( $array, $key ) ) {
		return $array[ $key ];
	}

	if ( strpos( $key, '.' ) === false ) {
		return isset( $array[ $key ] ) ? masteriyo_value( $default ) : null;
	}

	foreach ( explode( '.', $key ) as $segment ) {
		if ( masteriyo_array_is_accessible( $array ) && masteriyo_array_exists( $array, $segment ) ) {
			$array = $array[ $segment ];
		} else {
			return masteriyo_value( $default );
		}
	}

	return $array;
}

/**
 * If the given value is not an array and not null, wrap it in one.
 *
 * @since 1.3.6
 *
 * @param  mixed  $value
 * @return array
 */
function masteriyo_array_wrap( $value ) {
	if ( is_null( $value ) ) {
		return array();
	}

	return is_array( $value ) ? $value : array( $value );
}

/**
 * Conditionally compile classes from an array into a CSS class list.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @return string
 */
function masteriyo_array_to_css_classes( $array ) {
	$class_list = masteriyo_array_wrap( $array );
	$classes    = array();

	foreach ( $class_list as $class => $constraint ) {
		if ( is_numeric( $class ) ) {
			$classes[] = $constraint;
		} elseif ( $constraint ) {
			$classes[] = $class;
		}
	}

	return implode( ' ', $classes );
}

/**
 * Determines if an array is associative.
 *
 * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @return bool
 */
function masteriyo_array_is_assoc( array $array ) {
	$keys = array_keys( $array );

	return array_keys( $keys ) !== $keys;
}

/**
 * Recursively sort an array by keys and values.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  int  $options
 * @param  bool  $descending
 * @return array
 */
function masteriyo_array_sort_recursive( $array, $options = SORT_REGULAR, $descending = false ) {
	foreach ( $array as &$value ) {
		if ( is_array( $value ) ) {
			$value = masteriyo_array_sort_recursive( $value, $options, $descending );
		}
	}

	if ( masteriyo_array_is_assoc( $array ) ) {
		$descending ? krsort( $array, $options ) : ksort( $array, $options );
	} else {
		$descending ? rsort( $array, $options ) : sort( $array, $options );
	}

	return $array;
}

/**
 * Determines if an array is a list.
 *
 * An array is a "list" if all array keys are sequential integers starting from 0 with no gaps in between.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @return bool
 */
function masteriyo_array_is_list( $array ) {
	return ! masteriyo_array_is_assoc( $array );
}

/**
 * Determine if the given key exists in the provided array.
 *
 * @since 1.3.6
 *
 * @param  \ArrayAccess|array  $array
 * @param  string|int  $key
 * @return bool
 */
function masteriyo_array_exists( $array, $key ) {
	if ( $array instanceof \Enumerable ) {
		return $array->has( $key );
	}

	if ( $array instanceof \ArrayAccess ) {
		return $array->offsetExists( $key );
	}

	return array_key_exists( $key, $array );
}

/**
 * Check if an item or items exist in an array using "dot" notation.
 *
 * @since 1.3.6
 *
 * @param  \ArrayAccess|array  $array
 * @param  string|array  $keys
 * @return bool
 */
function masteriyo_array_has( $array, $keys ) {
	$keys = (array) $keys;

	if ( ! $array || array() === $keys ) {
		return false;
	}

	foreach ( $keys as $key ) {
		$sub_key_array = $array;

		if ( masteriyo_array_exists( $array, $key ) ) {
			continue;
		}

		foreach ( explode( '.', $key ) as $segment ) {
			if ( masteriyo_array_is_accessible( $sub_key_array ) && masteriyo_array_exists( $sub_key_array, $segment ) ) {
				$sub_key_array = $sub_key_array[ $segment ];
			} else {
				return false;
			}
		}
	}

	return true;
}

/**
 * Determine if any of the keys exist in an array using "dot" notation.
 *
 * @since 1.3.6
 *
 * @param  \ArrayAccess|array  $array
 * @param  string|array  $keys
 * @return bool
 */
function masteriyo_array_has_any( $array, $keys ) {
	if ( is_null( $keys ) ) {
		return false;
	}

	$keys = (array) $keys;

	if ( ! $array ) {
		return false;
	}

	if ( array() === $keys ) {
		return false;
	}

	foreach ( $keys as $key ) {
		if ( masteriyo_array_has( $array, $key ) ) {
			return true;
		}
	}

	return false;
}
/**
 * Remove one or many array items from a given array using "dot" notation.
 *
 * @param  array  $array
 * @param  array|string  $keys
 * @return void
 */
function masteriyo_array_forget( &$array, $keys ) {
	$original = &$array;
	$keys     = (array) $keys;

	if ( count( $keys ) === 0 ) {
		return;
	}

	foreach ( $keys as $key ) {
		// if the exact key exists in the top-level, remove it
		if ( masteriyo_array_exists( $array, $key ) ) {
			unset( $array[ $key ] );

			continue;
		}

		$parts       = explode( '.', $key );
		$total_parts = count( $parts );

		// clean up before each pass
		$array = &$original;

		while ( $total_parts > 1 ) {
			$part = array_shift( $parts );

			if ( isset( $array[ $part ] ) && is_array( $array[ $part ] ) ) {
				$array = &$array[ $part ];
			} else {
				continue 2;
			}
		}

		unset( $array[ array_shift( $parts ) ] );
	}
}

/**
 * Flatten a multi-dimensional array into a single level.
 *
 * @since 1.3.6
 *
 * @param  iterable|array  $array
 * @param  int  $depth
 * @return array
 */
function masteriyo_array_flatten( $array, $depth = INF ) {
	$result = array();

	foreach ( $array as $item ) {
		if ( ! is_array( $item ) ) {
			$result[] = $item;
		} else {
			$values = 1 === $depth ? array_values( $item ) : masteriyo_array_flatten( $item, $depth - 1 );

			foreach ( $values as $value ) {
				$result[] = $value;
			}
		}
	}

	return $result;
}

/**
 * Get a subset of the items from the given array.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  array|string  $keys
 * @return array
 */
function masteriyo_array_only( $array, $keys ) {
	return array_intersect_key( $array, array_flip( (array) $keys ) );
}

/**
 * Push an item onto the beginning of an array.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  mixed  $value
 * @param  mixed  $key
 * @return array
 */
function masteriyo_array_prepend( $array, $value, $key = null ) {
	if ( 2 === func_num_args() ) {
		array_unshift( $array, $value );
	} else {
		$array = array( $key => $value ) + $array;
	}

	return $array;
}

 /**
 * Get a value from the array, and remove it.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  string  $key
 * @param  mixed  $default
 * @return mixed
 */
function masteriyo_array_pull( &$array, $key, $default = null ) {
	$value = masteriyo_array_get( $array, $key, $default );

	masteriyo_array_forget( $array, $key );

	return $value;
}

/**
 * Get one or a specified number of random values from an array.
 *
 * @since 1.3.6
 *
 * @param  array  $array
 * @param  int|null  $number
 * @param  bool|false  $preserve_keys
 * @return mixed
 *
 * @throws \InvalidArgumentException
 */
function masteriyo_array_random( $array, $number = null, $preserve_keys = false ) {
	$requested = is_null( $number ) ? 1 : $number;

	$count = count( $array );

	if ( $requested > $count ) {
		throw new \InvalidArgumentException(
			"You requested {$requested} items, but there are only {$count} items available."
		);
	}

	if ( is_null( $number ) ) {
		return $array[ array_rand( $array ) ];
	}

	if ( 0 === (int) $number ) {
		return array();
	}

	$keys = array_rand( $array, $number );

	$results = array();

	if ( $preserve_keys ) {
		foreach ( (array) $keys as $key ) {
			$results[ $key ] = $array[ $key ];
		}
	} else {
		foreach ( (array) $keys as $key ) {
			$results[] = $array[ $key ];
		}
	}

	return $results;
}

/**
 * Return the default value of the given value.
 *
 * @since 1.3.6
 *
 * @param  mixed  $value
 * @return mixed
 */
function masteriyo_value( $value, ...$args ) {
	return $value instanceof \Closure ? $value( ...$args ) : $value;
}

/**
 * wp_parse_args() for multi dimensional arrays.
 *
 * @since 1.5.10
 *
 * @param array|stdClass $a
 * @param array|stdClass $b
 * @return array
 */
function masteriyo_parse_args( &$a, $b ) {
	$a      = (array) $a;
	$b      = (array) $b;
	$result = $b;

	foreach ( $a as $k => &$v ) {
		if ( is_array( $v ) && isset( $result[ $k ] ) ) {
			$result[ $k ] = masteriyo_parse_args( $v, $result[ $k ] );
		} else {
			$result[ $k ] = $v;
		}
	}

	return $result;
}

/**
 * Convert array keys from snake to camel and return it.
 *
 * @since 1.5.10
 *
 * @param  array $arr
 * @return array
 */
function masteriyo_array_snake_to_camel( $arr ) {
	return array_combine( array_map( 'masteriyo_snake_to_camel', array_keys( $arr ) ), array_values( $arr ) );
}

/**
 * Join array of scalar values into a string.
 *
 * @since 1.5.20
 *
 * @param array $values Array of scalar values.
 * @param string $separator Values separator.
 * @param string $format_value Format string to apply for each value.
 *
 * @return string
 */
function masteriyo_array_join( $values, $separator = ',', $format_value = '' ) {
	if ( empty( $values ) || ! is_array( $values ) ) {
		return '';
	}

	if ( ! empty( $format_value ) ) {
		foreach ( $values as $index => $value ) {
			$values[ $index ] = str_replace( '{value}', $value, $format_value );
		}
	}

	return implode( $separator, $values );
}
