<?php
/**
 * This file contains JupiterX_Control_Panel_Updates_Downgrades class only.
 *
 * @author      Ugur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        https://artbees.net
 * @since       Version 5.9.9
 * @package     artbees
 */

/**
 * WordPress auto update and downgrade feature for theme
 *
 * @author      Ugur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        https://artbees.net
 * @since       Version 5.9.9
 * @package     artbees
 */
class JupiterX_Control_Panel_Updates_Downgrades {

	/**
	 * Api_url Api url address.
	 *
	 * @var string $api_url
	 */
	var $api_url;

	/**
	 * switch for jupiterx_get_theme_release_package_url return type. Ajax parameter workaround.
	 *
	 * @var bool determines jupiterx_get_theme_release_package will return only url or object.
	 */
	var $url_object;

	/**
	 * Theme update error message
	 *
	 * @var string
	 */
	protected $update_error;

	/**
	 * JupiterX_Control_Panel_Updates_Downgrades constructor.
	 */
	function __construct() {
		$this->api_url = 'https://artbees.net/api/v1/';
		$stored_api_key = jupiterx_get_option( 'api_key' );
		$this->url_object = true;
		add_action( 'wp_ajax_jupiterx_get_theme_release_package_url', array( &$this, 'jupiterx_get_theme_release_package_url' ) );
		add_action( 'wp_ajax_jupiterx_modify_auto_update', array( &$this, 'jupiterx_modify_auto_update' ) );
		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];

		if ( ! $this->is_verified_to_update_product( $stored_api_key ) ) {
			add_action( 'after_theme_row_' . $theme_base, array( &$this, 'unauthorized_update_notice' ), 10, 3 );
		}

		add_filter( 'site_transient_update_themes', array( &$this, 'check_for_update' ), 1 );
	}

	/**
	 * Get notice for themes list when user is not authorised to update the theme. In other words the product is not registered via an API key.
	 */
	public function unauthorized_update_notice() {
		$table  = _get_list_table( 'WP_MS_Themes_List_Table' );
		?>
		<tr class="plugin-update-tr"><td colspan="<?php echo esc_attr( $table->get_column_count() ); ?>" class="plugin-update colspanchange">
				<div class="update-message jupiterx-update-screen-notice">
					<?php
					printf(
						__( 'You need to authorize this site in order to get upgrades or support for this theme. %1$sRegsiter Your Theme%2$s.', 'jupiterx' ),
						'<a href="' . esc_url( admin_url( 'admin.php?page=' . JUPITERX_SLUG ) ) . '">', '</a>'
					);
					?>
				</div>
		</tr>
		<?php
	}


	/**
	 * Returns an array of data containing current theme version and theme folder name
	 *
	 * @return array
	 */
	public function get_theme_data() {

		$theme_data = wp_get_theme( get_option( 'template' ) );
		$theme_version = $theme_data->version;

		$theme_base = 'jupiterx';

		return array(
			'theme_version' => $theme_version,
			'theme_base'    => $theme_base,
		);

	}



	/**
	 * Hook into WP check update data and inject custom array for theme WP updater
	 *
	 * @param array $checked_data
	 * @return array    $checked_data
	 */
	public function check_for_update( $checked_data ) {
		if ( ! is_object( $checked_data ) ) {
			return $checked_data;
		}

		$transient_array = get_transient( 'jupiterx_modify_auto_update' );
		if ( $transient_array ) {
			// extract method array into variables
			$theme_data = $this->get_theme_data();
			$this->url_object = false;
			$response['theme'] = $theme_data['theme_base'];
			$response['package'] = $transient_array['package_url'];
			// $response['package'] = str_replace( 'http://','http://test2', $response['package'] );
			$response['new_version'] = $transient_array['release_version'];
			$response['url'] = 'https://themes.artbees.net/support/jupiterx/release-notes/';
			// $response['url'] = $response['package'];
			$checked_data->response[ $theme_data['theme_base'] ] = $response;
		}
		return $checked_data;

	}

	public function jupiterx_modify_auto_update() {
		$this->url_object = false;
		$transient_name = 'jupiterx_modify_auto_update';

		delete_transient( $transient_name );
		$package_url = $this->jupiterx_get_theme_release_package_url();
		if ( false === $package_url ) {
			$error = __( 'Something went wrong!', 'jupiterx' );
			$error = empty( $this->update_error ) ? $error : $this->update_error;
			return wp_send_json_error( $error );
		}
		$transient_array['package_url'] = $package_url;
		$transient_array['release_version'] = $_POST['release_version'];
		$transient_array['release_id'] = $_POST['release_id'];
		set_transient( $transient_name, $transient_array, 60 );

		add_filter( 'site_transient_update_themes', [ $this, 'check_for_update' ], 1 );

		wp_send_json_success();
	}


	public function get_release_notes() {
		global $wp_version;
		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];
		$theme_version = $theme_data['theme_version'];
		$request = array(
			'slug' => $theme_base,
			'version' => $theme_version,
		);

		// Start checking for an update
		$data = array(
			'body' => array(
				'action' => 'get_release_notes',
				'request' => serialize( $request ),
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && (200 == $raw_response['response']['code'] ) ) {
			$response = $raw_response['body'];
		}

		if ( is_wp_error( $raw_response ) ) {
			$response = is_wp_error( $raw_response );
		}

		return json_decode( $response );

	}



	/**
	 * Get theme update url
	 *
	 * @return string $url
	 */
	public function get_theme_update_url() {

		$api_key = jupiterx_get_option( 'api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}

		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];

		return wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $theme_base ) ), 'upgrade-theme_' . $theme_base );
	}

	/**
	 * Get theme latest version package url
	 *
	 * @return string $url
	 */
	public function get_theme_latest_package_url() {

		$api_key = jupiterx_get_option( 'api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}

		global $wp_version;

		$data = array(
			'body' => array(
				'action' => 'get_theme_package',
				'apikey' => jupiterx_get_option( 'api_key' ),
				'domain' => $_SERVER['SERVER_NAME'],
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {
			return $raw_response['body'];
		}
		return false;
	}

	/**
	 * Get theme release url.
	 *
	 * @return string $url
	 */
	public function jupiterx_get_theme_release_package_url() {
		check_ajax_referer( 'jupiterx-ajax-get-theme-release-package-url-nonce', 'security' );

		$api_key         = jupiterx_get_option( 'api_key' );
		$release_package = ! empty( $_POST['release_package'] ) ? sanitize_title( $_POST['release_package'] ) : false;

		if ( empty( $api_key ) ) {
			wp_send_json_error( esc_html__( 'API Key is missing.', 'jupiterx' ) );
		}

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , [
			'body' => [
				'action'          => 'get_release_download_link',
				'apikey'          => $api_key,
				'domain'          => $_SERVER['SERVER_NAME'],
				'release_id'      => $_POST['release_id'],
				'release_package' => $release_package,
			],
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . esc_url( home_url( '/' ) ),
		] );

		if ( is_wp_error( $raw_response ) ) {
			wp_send_json_error( $raw_response->get_error_message() );
		}

		if ( 200 !== $raw_response['response']['code'] ) {
			wp_send_json_error( $raw_response['response']['code'] );
		}

		$json_response = json_decode( json_decode( $raw_response['body'], JSON_FORCE_OBJECT ) );

		if ( ! is_object( $json_response ) ) {
			wp_send_json_error( esc_html__( 'The response is not a valid JSON object.', 'jupiterx' ) );
		}

		if ( ! $json_response->success ) {
			wp_send_json_error( $json_response->message );
		}

		if ( $release_package ) {
			wp_send_json_success( $json_response->download_link );
		}

		return $json_response->download_link;
	}


	/**
	 *
	 *
	 * Check if Current Customer is verified and authorized to update product
	 */
	function is_verified_to_update_product() {

		$api_key = jupiterx_get_option( 'api_key' );

		if ( ! empty( $api_key ) ) {
			return true;
		}
		return false;

	}

	/**
	 * Check if new update is available
	 *
	 * @since 1.14.0
	 *
	 * @return boolean
	 */
	function is_new_update_available() {
		$releases = $this->get_release_notes();
		if ( empty( $releases ) || ! is_array( $releases ) ) {
			return;
		}

		foreach ( $releases as $index => $release ) {
			$release_version = trim( str_replace( 'V', '', $release->post_title ) );
			$version_compare = version_compare( $release_version, JUPITERX_VERSION );

			if ( 1 === $version_compare ) {
				return true;
			}

			return false;
		}

	}

}


new JupiterX_Control_Panel_Updates_Downgrades();
