<?php
/**
 * This class extends WordPress dashboard.
 *
 * @package JupiterX_Core\Dashboard
 *
 * @since 1.1.0
 */

use Elementor\Core\Files\CSS\Global_CSS;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Core\Responsive\Files\Frontend;

/**
 * Extends WordPress dashboard.
 *
 * @package JupiterX_Core\Dashboard
 *
 * @since 1.1.0
 */
class JupiterX_Dashboard {

	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		jupiterx_core()->load_files( [ 'dashboard/widgets/class-overview' ] );

		add_action( 'wp_ajax_jupiterx_dashboard', [ $this, 'ajax_handler' ] );
	}

	/**
	 * Map requests to proper methods.
	 *
	 * @since 1.1.0
	 */
	public function ajax_handler() {
		check_ajax_referer( 'jupiterx_dashboard', 'nonce' );

		$type = jupiterx_post( 'type' );

		if ( ! $type ) {
			wp_send_json_error( esc_html__( 'Type param is missing.', 'jupiterx-core' ) );
		}

		$method = str_replace( '-', '_', $type );

		$this->$method();

		wp_send_json_error(
			/* translators: action type */
			sprintf( esc_html__( 'Type param (%s) is not valid.', 'jupiterx-core' ), $type )
		);
	}

	/**
	 * Get network sites
	 *
	 * @since 1.1.0
	 * @throws Exception When get_sites function is not defined.
	 */
	public function get_sites() {
		try {

			if ( ! function_exists( 'get_sites' ) ) {
				throw new Exception( esc_html__( 'The get_sites function is not defined.', 'jupiterx-core' ) );
			}

			$sites = get_sites( [
				'fields' => 'ids',
				'number' => 500,
			] );

		} catch ( \Throwable $th ) {
			wp_send_json_error( $th->getMessage() );
		}

		wp_send_json_success( array_chunk( $sites, 20 ) );
	}

	/**
	 * Flush network cache.
	 *
	 * @since 1.1.0
	 * @throws Exception When site param is missing.
	 */
	public function flush_network_cache() {
		try {

			$sites = jupiterx_post( 'sites' );

			if ( ! $sites ) {
				throw new Exception( esc_html__( 'Site param is missing.', 'jupiterx-core' ) );
			}

			foreach ( $sites as $site ) {
				switch_to_blog( $site );

				jupiterx_core_flush_cache();

				restore_current_blog();
			}
		} catch ( \Throwable $th ) {
			wp_send_json_error( $th->getMessage() );
		}

		wp_send_json_success( $sites );
	}

}

new JupiterX_Dashboard();
