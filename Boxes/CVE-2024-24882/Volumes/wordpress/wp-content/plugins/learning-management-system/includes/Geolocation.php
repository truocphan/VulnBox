<?php
/**
 * Geolocation class.
 *
 * @since 1.0.0
 * @package Masteriyo
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Geolocation Class.
 */
class Geolocation {
	/**
	 * API endpoints for looking up user IP address.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static $ip_lookup_apis = array(
		'ipify'             => 'http://api.ipify.org/',
		'ipecho'            => 'http://ipecho.net/plain',
		'ident'             => 'http://ident.me',
		'whatismyipaddress' => 'http://bot.whatismyipaddress.com',
	);

	/**
	 * API endpoints for geolocating an IP address.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static $geoip_apis = array(
		'ipinfo.io'  => 'https://ipinfo.io/%s/json',
		'ip-api.com' => 'http://ip-api.com/json/%s',
	);

	/**
	 * Get current user IP Address from headers.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_ip_address_from_header() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}
		return '';
	}

	/**
	 * Get user IP Address using an external service.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_external_ip_address() {
		$external_ip_address = '0.0.0.0';

		if ( '' !== self::get_ip_address_from_header() ) {
			$transient_name      = 'external_ip_address_' . self::get_ip_address_from_header();
			$external_ip_address = get_transient( $transient_name );
		}

		if ( false === $external_ip_address ) {
			$external_ip_address = '0.0.0.0';

			/**
			 * Filters the geolocation IP lookup APIs.
			 *
			 * @since 1.0.0
			 *
			 * @param array $apis List of APIs.
			 */
			$ip_lookup_services = apply_filters( 'masteriyo_geolocation_ip_lookup_apis', self::$ip_lookup_apis );

			$ip_lookup_services_keys = array_keys( $ip_lookup_services );
			shuffle( $ip_lookup_services_keys );

			foreach ( $ip_lookup_services_keys as $service_name ) {
				$service_endpoint = $ip_lookup_services[ $service_name ];
				$response         = wp_safe_remote_get( $service_endpoint, array( 'timeout' => 2 ) );

				if ( ! is_wp_error( $response ) && rest_is_ip_address( $response['body'] ) ) {
					/**
					 * Filters the geolocation IP lookup API response.
					 *
					 * @since 1.0.0
					 *
					 * @param mixed $response The API response data.
					 * @param string $service_name The API service name.
					 */
					$external_ip_address = apply_filters( 'masteriyo_geolocation_ip_lookup_api_response', masteriyo_clean( $response['body'] ), $service_name );
					break;
				}
			}

			set_transient( $transient_name, $external_ip_address, WEEK_IN_SECONDS );
		}

		return $external_ip_address;
	}

	/**
	 * Geolocate an IP address.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $ip_address   IP Address.
	 * @param  bool   $fallback     If true, fallbacks to alternative IP detection (can be slower).
	 * @param  bool   $api_fallback If true, uses geolocation APIs if the database file doesn't exist (can be slower).
	 * @return array
	 */
	public static function geolocate_ip( $ip_address = '', $fallback = false, $api_fallback = true ) {
		/**
		 * Filter to allow custom geolocation of the IP address.
		 *
		 * @since 1.0.0
		 *
		 * @param false|string $code The country IP.
		 * @param string $ip_address The given IP address.
		 * @param boolean $fallback Whether to fallback or not.
		 * @param boolean $api_fallback Whether to use fallback API or not.
		 */
		$country_code = apply_filters( 'masteriyo_geolocate_ip', false, $ip_address, $fallback, $api_fallback );

		if ( false !== $country_code ) {
			return array(
				'country'    => $country_code,
				'state'      => '',
				'city'       => '',
				'postcode'   => '',
				'ip_address' => $ip_address,
			);
		}

		if ( empty( $ip_address ) ) {
			$ip_address = self::get_ip_address_from_header();
		}

		$country_code = self::get_country_code_from_headers();

		/**
		 * Get geolocation filter.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $geolocation Geolocation data, including country, state, city, and postcode, ip_address.
		 * @param string $ip_address  IP Address.
		 */
		$geolocation = apply_filters(
			'masteriyo_get_geolocation',
			array(
				'country'    => $country_code,
				'state'      => '',
				'city'       => '',
				'postcode'   => '',
				'ip_address' => $ip_address,
			),
			$ip_address
		);

		// If we still haven't found a country code, let's consider doing an API lookup.
		if ( '' === $geolocation['country'] && $api_fallback ) {
			$geolocation['country'] = self::geolocate_via_api( $ip_address );
		}

		// It's possible that we're in a local environment, in which case the geolocation needs to be done from the external address.
		if ( '' === $geolocation['country'] && $fallback ) {
			$external_ip_address = self::get_external_ip_address();

			// Only bother with this if the external IP differs.
			if ( '0.0.0.0' !== $external_ip_address && $external_ip_address !== $ip_address ) {
				return self::geolocate_ip( $external_ip_address, false, $api_fallback );
			}
		}

		return array(
			'country'    => $geolocation['country'],
			'state'      => $geolocation['state'],
			'city'       => $geolocation['city'],
			'postcode'   => $geolocation['postcode'],
			'ip_address' => $ip_address,
		);
	}

	/**
	 * Fetches the country code from the request headers, if available.
	 *
	 * @since 1.0.0
	 *
	 * @return string The country code pulled from the headers, or empty string if not found.
	 */
	private static function get_country_code_from_headers() {
		$country_code = '';

		$headers = array(
			'MM_COUNTRY_CODE',
			'GEOIP_COUNTRY_CODE',
			'HTTP_CF_IPCOUNTRY',
			'HTTP_X_COUNTRY_CODE',
		);

		foreach ( $headers as $header ) {
			if ( empty( $_SERVER[ $header ] ) ) {
				continue;
			}

			$country_code = strtoupper( sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ) );
			break;
		}

		return $country_code;
	}

	/**
	 * Use APIs to Geolocate the user.
	 *
	 * @since 1.0.0
	 *
	 * @param string $ip_address IP address.
	 *
	 * @return string
	 */
	private static function geolocate_via_api( $ip_address ) {
		$country_code = get_transient( 'geoip_' . $ip_address );

		if ( false === $country_code ) {
			/**
			 * Filters the API endpoints for geo-locating an IP address.
			 *
			 * @since 1.0.0
			 *
			 * @param array $geoip_apis List of APIs.
			 */
			$geoip_services = apply_filters( 'masteriyo_geolocation_geoip_apis', self::$geoip_apis );

			if ( empty( $geoip_services ) ) {
				return '';
			}

			$geoip_services_keys = array_keys( $geoip_services );

			shuffle( $geoip_services_keys );

			foreach ( $geoip_services_keys as $service_name ) {
				$service_endpoint = $geoip_services[ $service_name ];
				$response         = wp_safe_remote_get( sprintf( $service_endpoint, $ip_address ), array( 'timeout' => 2 ) );

				if ( ! is_wp_error( $response ) && $response['body'] ) {
					switch ( $service_name ) {
						case 'ipinfo.io':
							$data         = json_decode( $response['body'] );
							$country_code = isset( $data->country ) ? $data->country : '';
							break;
						case 'ip-api.com':
							$data         = json_decode( $response['body'] );
							$country_code = isset( $data->countryCode ) ? $data->countryCode : ''; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
							break;
						default:
							/**
							 * Filters the IP geolocation API response.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $response The API response data.
							 */
							$country_code = apply_filters( 'masteriyo_geolocation_geoip_response_' . $service_name, '', $response['body'] );
							break;
					}

					$country_code = sanitize_text_field( strtoupper( $country_code ) );

					if ( $country_code ) {
						break;
					}
				}
			}

			set_transient( 'geoip_' . $ip_address, $country_code, WEEK_IN_SECONDS );
		}

		return $country_code;
	}
}
