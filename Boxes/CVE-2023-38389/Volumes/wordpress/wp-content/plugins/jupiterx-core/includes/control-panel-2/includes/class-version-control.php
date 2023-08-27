<?php
/**
 * The file class that handles version control.
 *
 * @package JupiterX_Core\Control_Panel_2\Version_Control
 *
 * @since 1.18.0
 */

/**
 * Version control class.
 *
 * @since 1.18.0
 */
class JupiterX_Core_Control_Panel_Version_Control {

	const ARTBEES_THEMES_PRODUCTS_API        = 'https://themes.artbees.net/wp-json/artbees-portal-products/v1/products';
	const ARTBEES_THEMES_PRODUCT_PACKAGE_API = 'https://themes.artbees.net/wp-json/artbees-portal-products/v1/products/package';

	private $jupiterx_pro = false;

	/**
	 * Class instance.
	 *
	 * @since 1.18.0
	 *
	 * @var JupiterX_Core_Control_Panel_Version_Control Class instance.
	 */
	private static $instance = null;

	/**
	 * Get a class instance.
	 *
	 * @since 1.18.0
	 *
	 * @return JupiterX_Core_Control_Panel_Version_Control Class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_cp_get_products', [ $this, 'get_products' ] );
		add_action( 'wp_ajax_jupiterx_cp_settings_reinstall', [ $this, 'reinstall' ] );

		if ( jupiterx_is_pro() ) {
			$this->jupiterx_pro = true;
		}
	}

	/**
	 * Get products from API.
	 *
	 * @since 1.18.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function get_products() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		$force    = ! empty( $_REQUEST['force'] ) ? filter_var( wp_unslash( $_REQUEST['force'] ), FILTER_VALIDATE_BOOLEAN ) : false;
		$products = get_transient( 'jupiterx_cp_settings_products' );

		if ( empty( $products ) || $force ) {
			$products = [];

			$products = wp_remote_retrieve_body(
				wp_remote_post( static::ARTBEES_THEMES_PRODUCTS_API, [
					'body' => [
						'product' => 'jupiterx',
					],
				] )
			);

			$products = json_decode( $products );

			if ( ! is_array( $products ) || empty( $products ) ) {
				wp_send_json_error( __( 'There\'s a problem in fetching the products.', 'jupiterx-core' ) );
			}

			if ( ! $this->jupiterx_pro ) {
				$wp_theme = wp_remote_retrieve_body(
					wp_remote_get( 'https://api.wordpress.org/themes/info/1.1/?action=theme_information&request[slug]=jupiterx-lite&request[fields][versions]=true' )
				);

				$wp_theme = json_decode( $wp_theme );

				if ( ! is_object( $wp_theme ) || empty( $wp_theme ) ) {
					wp_send_json_error( __( 'There\'s a problem in fetching the products.', 'jupiterx-core' ) );
				}

				// Get jupitrx-lite versions from WP api.
				foreach ( $products as $product_key => $product ) {
					if ( 'theme' !== $product->type ) {
						continue;
					}

					if ( empty( $product->source ) ) {
						$product->source = 'wp-repo';
					}

					$product->versions = null;

					foreach ( $wp_theme->versions as $name => $url ) {
						if ( preg_match( '/trunk|-.*/m', $name ) ) {
							continue;
						}

						$products[ $product_key ]->versions[] = (object) [
							'name' => $name,
							'url'  => $url,
						];
					}
				}
			}

			// Get wp-repo plugins versions from WP api.
			foreach ( $products as $product_key => $product ) {
				if ( 'wp-repo' !== $product->source ) {
					continue;
				}

				$wp_plugin = wp_remote_retrieve_body(
					wp_remote_get( 'https://api.wordpress.org/plugins/info/1.0/' . $product->slug . '.json' )
				);

				$wp_plugin = json_decode( $wp_plugin );

				if ( ! is_object( $wp_plugin ) || empty( $wp_plugin ) ) {
					wp_send_json_error( __( 'There\'s a problem in fetching the wp-repo plugins\' versions.', 'jupiterx-core' ) );
				}

				foreach ( $wp_plugin->versions as $name => $url ) {
					if ( preg_match( '/trunk|-.*/m', $name ) ) {
						continue;
					}

					$products[ $product_key ]->versions[] = (object) [
						'name' => $name,
						'url'  => $url,
					];
				}
			}

			set_transient( 'jupiterx_cp_settings_products', $products, WEEK_IN_SECONDS );
		}

		// Prepare the plugin data.
		foreach ( $products as $product_key => $product ) {
			// Remove the inactive plugins.
			if ( ! jupiterx_is_callable( $product->is_callable ) && 'theme' !== $product->type ) {
				unset( $products[ $product_key ] );
				continue;
			}

			// Translatable product type.
			$product->type_label = ( 'theme' === $product->type ) ? esc_html__( 'theme', 'jupiterx-core' ) : esc_html__( 'plugin', 'jupiterx-core' );

			// Sort versions.
			usort( $product->versions, function( $a, $b ) {
				return version_compare( $b->name, $a->name );
			} );

			// Get last 10 versions.
			$product->versions = array_slice( $product->versions, 0, 10 );

			// Add active version.
			if ( 'theme' !== $product->type ) {
				$plugin_data             = get_plugin_data( WP_PLUGIN_DIR . '/' . $product->basename );
				$product->active_version = $plugin_data['Version'];
			}
		}

		wp_send_json_success( array_values( $products ) );
	}

	/**
	 * Reinstall a product.
	 *
	 * @since 1.18.0
	 */
	public function reinstall() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$product = ! empty( $_REQUEST['product'] ) ? filter_var_array( wp_unslash( $_REQUEST['product'] ) ) : [];

		if ( empty( $product ) ) {
			wp_send_json_error( esc_html__( 'The product param is missing.', 'jupiterx-core' ) );
		}

		if ( 'wp-repo' !== $product['source'] ) {
			$product = $this->get_hosted_package( $product );
		}

		$this->apply_package( $product );
		$this->upgrade( $product );
	}

	/**
	 * Apply the product package in the update transient.
	 *
	 * @since 1.18.0
	 */
	private function apply_package( $product ) {
		$update_transient = get_site_transient( "update_{$product['type']}s" );

		if ( ! is_object( $update_transient ) ) {
			$update_transient = new \stdClass();
		}

		$product_identifier = ( 'theme' === $product['type'] ) ? $product['slug'] : $product['basename'];

		if ( 'theme' === $product['type'] ) {
			$product_info                = [];
			$product_info['new_version'] = $product['selected_version']['name'];
			$product_info['plugin']      = $product['basename'];
			$product_info['slug']        = $product['slug'];
			$product_info['package']     = $product['selected_version']['url'];
		} else {
			$product_info              = new \stdClass();
			$product_info->new_version = $product['selected_version']['name'];
			$product_info->plugin      = $product['basename'];
			$product_info->slug        = $product['slug'];
			$product_info->package     = $product['selected_version']['url'];
		}

		$update_transient->response[ $product_identifier ] = $product_info;

		remove_all_filters( "pre_set_site_transient_update_{$product['type']}s" );
		set_site_transient( "update_{$product['type']}s", $update_transient );
	}

	/**
	 * Upgrade the product.
	 *
	 * @since 1.18.0
	 */
	private function upgrade( $product ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$skin               = new WP_Ajax_Upgrader_Skin();
		$upgrader           = ucfirst( $product['type'] ) . '_Upgrader';
		$upgrader           = new $upgrader( $skin );
		$product_identifier = ( 'theme' === $product['type'] ) ? $product['slug'] : $product['basename'];
		$result             = $upgrader->bulk_upgrade( [ $product_identifier ] );

		if ( is_array( reset( $result ) ) ) {
			wp_send_json_success( $result );
		}

		wp_send_json_error( $skin->get_upgrade_messages() );
	}

	/**
	 * Get hosted product package url.
	 *
	 * @since 1.18.0
	 */
	private function get_hosted_package( $product ) {
		$product_package = wp_remote_retrieve_body(
			wp_remote_post( static::ARTBEES_THEMES_PRODUCT_PACKAGE_API, [
				'body' => [
					'product' => 'jupiterx',
					'type'    => $product['type'],
					'ID'      => $product['selected_version']['ID'],
					'version' => $product['selected_version']['name'],
				],
			] )
		);

		if ( empty( $product_package ) ) {
			wp_send_json_error( esc_html__( 'There\'s an error in getting product package.', 'jupiterx-core' ) );
		}

		$product['selected_version']['url'] = json_decode( $product_package );

		return $product;
	}

}

JupiterX_Core_Control_Panel_Version_Control::get_instance();
