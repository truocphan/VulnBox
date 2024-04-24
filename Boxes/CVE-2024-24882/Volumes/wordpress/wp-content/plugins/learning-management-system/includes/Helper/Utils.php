<?php
/**
 * Utility functions.
 *
 * @since 1.0.0
 */

namespace Masteriyo\Helper;

use Masteriyo\Constants;
class Utils {

	/**
	 * Wrapper for mb_strtoupper which see's if supported first.
	 *
	 * @since  1.0.0
	 * @param  string $string String to format.
	 * @return string
	 */
	public static function strtoupper( $string ) {
		return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $string ) : strtoupper( $string );
	}

	/**
	 * Make a string lowercase.
	 * Try to use mb_strtolower() when available.
	 *
	 * @since  1.0.0
	 * @param  string $string String to format.
	 * @return string
	 */
	public static function strtolower( $string ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
	}

	/**
	 * Converts a bool to a 'yes' or 'no'.
	 *
	 * @since 1.0.0
	 * @param bool $bool String to convert.
	 * @return string
	 */
	public static function bool_to_string( $bool ) {
		return true === $bool ? 'yes' : 'no';
	}

	/**
	 * Converts a string (e.g. 'yes' or 'no') to a bool.
	 *
	 * @since 1.0.0
	 * @param string $string String to convert.
	 * @return bool
	 */
	public static function string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
	}

	/**
	 * Helper to get cached object terms and filter by field using wp_list_pluck().
	 * Works as a cached alternative for wp_get_post_terms() and wp_get_object_terms().
	 *
	 * @since  1.0.0
	 *
	 * @param  int    $object_id Object ID.
	 * @param  string $taxonomy  Taxonomy slug.
	 * @param  string $field     Field name.
	 * @param  string $index_key Index key name.
	 * @return array
	 */
	public static function get_object_terms( $object_id, $taxonomy, $field = null, $index_key = null ) {
		// Test if terms exists. get_the_terms() return false when it finds no terms.
		$terms = get_the_terms( $object_id, $taxonomy );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}

		return is_null( $field ) ? $terms : wp_list_pluck( $terms, $field, $index_key );
	}

	/**
	 * Wrapper for _doing_it_wrong().
	 *
	 * @since  1.0.0
	 *
	 * @param string $function Function used.
	 * @param string $message Message to log.
	 * @param string $version Version the message was added in.
	 */
	public static function doing_it_wrong( $function, $message, $version ) {
		// phpcs:disable
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

		if ( \is_ajax() || self::is_rest_api_request() ) {
			/**
			 * Fires when the given function is being used incorrectly.
			 *
			 * @since 1.0.0
			 *
			 * @see https://developer.wordpress.org/reference/hooks/doing_it_wrong_run/
			 *
			 * @param string $function Function used.
			 * @param string $message Message to log.
			 * @param string $version Version the message was added in.
			 */
			do_action( 'doing_it_wrong_run', $function, $message, $version );
			error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
		} else {
			_doing_it_wrong( $function, $message, $version );
		}
		// @phpcs:enable
	}

	/**
	 * Given a path, this will convert any of the subpaths into their corresponding tokens.
	 *
	 * @since 1.0.0
	 * @param string $path The absolute path to tokenize.
	 * @param array  $path_tokens An array keyed with the token, containing paths that should be replaced.
	 * @return string The tokenized path.
	 */
	public static function tokenize_path( $path, $path_tokens ) {
		// Order most to least specific so that the token can encompass as much of the path as possible.
		uasort(
			$path_tokens,
			function ( $a, $b ) {
				$a = strlen( $a );
				$b = strlen( $b );

				if ( $a > $b ) {
					return -1;
				}

				if ( $b > $a ) {
					return 1;
				}

				return 0;
			}
		);

		foreach ( $path_tokens as $token => $token_path ) {
			if ( 0 !== strpos( $path, $token_path ) ) {
				continue;
			}

			$path = str_replace( $token_path, '{{' . $token . '}}', $path );
		}

		return $path;
	}

	/**
	 * Given a tokenized path, this will expand the tokens to their full path.
	 *
	 * @since 1.0.0
	 * @param string $path The absolute path to expand.
	 * @param array  $path_tokens An array keyed with the token, containing paths that should be expanded.
	 * @return string The absolute path.
	 */
	public static function untokenize_path( $path, $path_tokens ) {
		foreach ( $path_tokens as $token => $token_path ) {
			$path = str_replace( '{{' . $token . '}}', $token_path, $path );
		}

		return $path;
	}

	/**
	 * Fetches an array containing all of the configurable path constants to be used in tokenization.
	 *
	 * @since 1.0.0
	 *
	 * @return array The key is the define and the path is the constant.
	 */
	public static function get_path_define_tokens() {
		$defines = array(
			'ABSPATH',
			'WP_CONTENT_DIR',
			'WP_PLUGIN_DIR',
			'WPMU_PLUGIN_DIR',
			'PLUGINDIR',
			'WP_THEME_DIR',
		);

		$path_tokens = array();

		foreach ( $defines as $define ) {
			if ( defined( $define ) ) {
				$path_tokens[ $define ] = constant( $define );
			}
		}

		/**
		 * Filters path tokens.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $path_tokens The path tokens.
		 */
		return apply_filters( 'masteriyo_get_path_define_tokens', $path_tokens );
	}

	/**
	 * Get the template path.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function template_path() {
		/**
		 * Filters relative templates directory.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template_path Template path.
		 */
		return apply_filters( 'masteriyo_template_path', 'masteriyo/' );
	}

	/**
	 * Returns true if the request is a non-legacy REST API request.
	 *
	 * Legacy REST requests should still run some extra code for backwards compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		/**
		 * Filters boolean: true if current request is rest API request.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if current request is rest API request.
		 */
		return apply_filters( 'masteriyo_is_rest_api_request', $is_rest_api_request );
	}

	/**
	 * Check if the home URL is https. If it is, we don't need to do things such as 'force ssl'.
	 *
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public static function is_https_site() {
		return false !== strstr( get_option( 'home' ), 'https:' );
	}

	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @since 1.0.0s
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	public static function clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( array( __CLASS__, 'clean' ), $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 *
	 * @since 1.0.0
	 *
	 * @param  string  $name   Name of the cookie being set.
	 * @param  string  $value  Value of the cookie.
	 * @param  integer $expire Expiry of the cookie.
	 * @param  bool    $secure Whether the cookie should be served only over https.
	 * @param  bool    $httponly Whether the cookie is only accessible over HTTP, not scripting languages like JavaScript.
	 */
	public static function set_cookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
		if ( ! headers_sent() ) {
			$cookie_path = COOKIEPATH ? COOKIEPATH : '/';

			/**
			 * Filters boolean: true if the cookie is only accessible over HTTP, not scripting languages like JavaScript.
			 *
			 * @since 1.0.0
			 *
			 * @param boolean $bool true if the cookie is only accessible over HTTP, not scripting languages like JavaScript.
			 * @param string $name Name of the cookie being set.
			 * @param string $value Value of the cookie.
			 * @param integer $expire Expiry of the cookie.
			 * @param boolean $secure Whether the cookie should be served only over https.
			 */
			$http_only = apply_filters( 'masteriyo_cookie_httponly', $httponly, $name, $value, $expire, $secure );

			setcookie( $name, $value, $expire, $cookie_path, COOKIE_DOMAIN, $secure, $http_only );
		} elseif ( Constants::is_true( 'WP_DEBUG' ) ) {
			headers_sent( $file, $line );
			trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // phpcs:ignore
		}
	}
}
