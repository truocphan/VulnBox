<?php
/**
 * MASTERIYO-API endpoint handler.
 *
 * This handles API related functionality in Masteriyo.
 *
 * @package Masteriyo
 * @since   1.5.13
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;


/**
 * Masteriyo API class.
 */
class Api {

	/**
	 * Init the API by setting up action and filter hooks.
	 *
	 * @since 1.5.13
	 */
	public function init() {
		add_action( 'init', array( $this, 'add_endpoint' ), 0 );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		add_action( 'parse_request', array( $this, 'handle_api_requests' ), 0 );
	}

	/**
	 * Add new query vars.
	 *
	 * @since 1.5.13
	 * @param array $vars Query vars.
	 * @return string[]
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'masteriyo-api';
		return $vars;
	}

	/**
	 * Masteriyo API for payment gateway IPNs, etc.
	 *
	 * @since 1.5.13
	 */
	public static function add_endpoint() {
		add_rewrite_endpoint( 'masteriyo-api', EP_ALL );
	}

	/**
	 * API request - Trigger any API requests.
	 *
	 * @since   1.5.13
	 * @version 1.5.13
	 */
	public function handle_api_requests() {
		global $wp;

		if ( ! empty( $_GET['masteriyo-api'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$wp->query_vars['masteriyo-api'] = sanitize_key( wp_unslash( $_GET['masteriyo-api'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		// masteriyo-api endpoint requests.
		if ( ! empty( $wp->query_vars['masteriyo-api'] ) ) {

			// Buffer, we won't want any output here.
			ob_start();

			// No cache headers.
			masteriyo_nocache_headers();

			// Clean the API request.
			$api_request = strtolower( masteriyo_clean( $wp->query_vars['masteriyo-api'] ) );

			// Make sure gateways are available for request.
			masteriyo( 'payment-gateways' )->get_available_payment_gateways();

			// Trigger generic action before request hook.
			do_action( 'masteriyo_api_request', $api_request );

			// Is there actually something hooked into this API request? If not trigger 400 - Bad request.
			status_header( has_action( 'masteriyo_api_' . $api_request ) ? 200 : 400 );

			// Trigger an action which plugins can hook into to fulfill the request.
			do_action( 'masteriyo_api_' . $api_request );

			// Done, clear buffer and exit.
			ob_end_clean();
			die( '-1' );
		}
	}
}
