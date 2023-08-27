<?php
/**
 * WordPress auto update feature for theme
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        https://artbees.net
 * @since       1.11.1
 * @package     JupiterX\Framework\Admin
 */

/**
 * Class for Managing Updates.
 */
class JupiterX_Theme_Update {

	/**
	 * Artbees API URL.
	 *
	 * @var string
	 */
	private $api_url;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->api_url = 'https://artbees.net/api/v1/';

		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];

		if ( ! $this->is_verified_to_update_product() ) {
			add_action( 'after_theme_row_' . $theme_base, array( &$this, 'unauthorized_update_notice' ), 10, 3 );

			return;
		}

		add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_for_update' ] );
	}

	/**
	 * Returns an array of data containing current theme version and theme folder name.
	 *
	 * @since 1.11.0
	 *
	 * @return array
	 */
	public function get_theme_data() {

		$theme_data    = wp_get_theme( get_option( 'template' ) );
		$theme_version = $theme_data->version;

		$theme_name = 'jupiterx';

		return array(
			'theme_version' => $theme_version,
			'theme_base'    => $theme_name,
		);

	}

	/**
	 * Hook into WP check update data and inject custom array for theme WP updater.
	 *
	 * @since 1.11.0
	 *
	 * @param array $transient Update transient.
	 * @return array $transient Update transient.
	 */
	public function check_for_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Theme data.
		$theme_data = $this->get_theme_data();

		// Theme update data.
		$release         = $this->get_release_note();
		$release_version = trim( str_replace( 'V', '', $release->post_title ) );
		$release_id      = $release->ID;

		if ( version_compare( $release_version, $theme_data['theme_version'], '<=' ) ) {
			return $transient;
		}

		$response['theme']       = $theme_data['theme_base'];
		$response['package']     = $this->get_theme_latest_release_package_url( $release_id );
		$response['new_version'] = $release_version;
		$response['url']         = false;

		$transient->response[ $theme_data['theme_base'] ] = $response;

		return $transient;
	}

	/**
	 * Check if there is a new version and if so, it returns the latest version number
	 *
	 * @since 1.11.0
	 *
	 * @param int $set_transient Set site transient for update_themes data.
	 *
	 * @return mixed
	 */
	public function check_latest_version( $set_transient = 0 ) {

		if ( $set_transient ) {
			$current = get_site_transient( 'update_themes' );
			set_site_transient( 'update_themes', $current );
		}

		if ( false === get_transient( 'jupiterx_jupiterx_theme_version' ) ) {
			global $wp_version;

			$theme_data    = $this->get_theme_data();
			$theme_base    = $theme_data['theme_base'];
			$theme_version = $theme_data['theme_version'];

			$request = array(
				'slug' => $theme_base,
				'version' => $theme_version,
			);

			// Start checking for an update.
			$data = array(
				'body' => array(
					'action' => 'check_new_version',
					'request' => serialize( $request ), // phpcs:ignore WordPress.PHP
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
			);

			$raw_response = wp_remote_post( $this->api_url . 'update-theme', $data );

			if ( ! is_wp_error( $raw_response ) && ( 200 === $raw_response['response']['code'] ) ) {

				$current_version = jupiterx_get_option( 'theme_current_version' );

				$new_version = trim( $raw_response['body'] );

				set_transient( 'jupiterx_jupiterx_theme_version', null );
				if ( version_compare( $current_version, $new_version, '<' ) ) {
					set_transient( 'jupiterx_jupiterx_theme_version', $new_version, DAY_IN_SECONDS );
				}
			}
		}

		if ( version_compare( jupiterx_get_option( 'theme_current_version' ), get_transient( 'jupiterx_jupiterx_theme_version' ), '<' ) ) {
			return get_transient( 'jupiterx_jupiterx_theme_version' );
		}

		return false;
	}

	/**
	 * Fix string length.
	 *
	 * @since 1.11.0
	 *
	 * @param array $matches Matches.
	 * @return string
	 */
	public function fix_str_length( $matches ) {
		$string       = $matches[2];
		$right_length = strlen( $string ); // yes, strlen even for UTF-8 characters, PHP wants the mem size, not the char count.
		return 's:' . $right_length . ':"' . $string . '";';
	}


	/**
	 * Fixed serialized.
	 *
	 * @since 1.11.0
	 *
	 * @param string $string String.
	 * @return string
	 */
	public function fixed_serialized( $string ) {
		if ( ! preg_match( '/^[aOs]:/', $string ) ) {
			return $string;
		}

		// phpcs:ignore WordPress.PHP
		if ( unserialize( $string ) !== false ) {
			return $string;
		}

		$string = preg_replace( "%\n%", '', $string );
		// doublequote exploding.
		$data     = preg_replace( '%";%', 'µµµ', $string );
		$tab      = explode( 'µµµ', $data );
		$new_data = '';

		foreach ( $tab as $line ) {
			$new_data .= preg_replace_callback( '%\bs:(\d+):"(.*)%', array( &$this, 'fix_str_length' ), $line );
		}

		return $new_data;
	}

	/**
	 * Get release note for latest version.
	 *
	 * @since 1.11.0
	 *
	 * @return mixed
	 */
	public function get_release_note() {
		$api_key = jupiterx_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			return false;
		}

		global $wp_version;

		$theme_data    = $this->get_theme_data();
		$theme_base    = $theme_data['theme_base'];
		$theme_version = $theme_data['theme_version'];

		$request = array(
			'slug' => $theme_base,
			'version' => $theme_version,
		);

		// Start checking for an update.
		$data = array(
			'body' => array(
				'action' => 'get_release_note',
				'request' => serialize( $request ), // phpcs:ignore WordPress.PHP
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme', $data );

		if ( ! is_wp_error( $raw_response ) && ( 200 === $raw_response['response']['code'] ) ) {
			$response = $raw_response['body'];
		}

		if ( is_wp_error( $raw_response ) ) {
			$response = is_wp_error( $raw_response );
		}

		$repair_serialize = $this->fixed_serialized( $response );

		return unserialize( $repair_serialize ); // phpcs:ignore WordPress.PHP

	}

	/**
	 * Get theme latest verion package url.
	 *
	 * @since 1.11.0
	 *
	 * @param string $release_id Theme Release Id.
	 *
	 * @return string $url
	 */
	public function get_theme_latest_release_package_url( $release_id ) {
		$api_url = 'https://artbees.net/api/v1/';
		$api_key = jupiterx_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			return false;
		}

		$raw_response = wp_remote_post( $api_url . 'update-theme', [
			'body' => [
				'action'          => 'get_release_download_link',
				'apikey'          => $api_key,
				'domain'          => $_SERVER['SERVER_NAME'], // phpcs:ignore WordPress.Security
				'release_id'      => $release_id,
				'release_package' => '',
			],
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . esc_url( home_url( '/' ) ),
		] );

		if ( is_wp_error( $raw_response ) ) {
			return false;
		}

		if ( 200 !== $raw_response['response']['code'] ) {
			return false;
		}

		$json_response = json_decode( json_decode( $raw_response['body'], JSON_FORCE_OBJECT ) );

		if ( ! is_object( $json_response ) ) {
			return false;
		}

		if ( ! $json_response->success ) {
			return false;
		}

		return $json_response->download_link;
	}



	/**
	 * Get notice for themes list when user is not authorised to update the theme. In other words the product is not registered via an API key.
	 *
	 *  @since 1.11.0
	 */
	public function unauthorized_update_notice() {
		$table = _get_list_table( 'WP_MS_Themes_List_Table' );
	?>
		<tr class="plugin-update-tr"><td colspan="<?php echo esc_attr( $table->get_column_count() ); ?>" class="plugin-update colspanchange">
			<div class="update-message jupiterx-update-screen-notice">
			<?php
				// translators: 1: Link start tag, 2: Link end Tag.
				printf( __( 'You need to authorize this site in order to get upgrades or support for this theme. %1$sRegsiter Your Theme%2$s.', 'jupiterx' ), '<a href="' . admin_url( 'admin.php?page=' . JUPITERX_SLUG ) . '">', '</a>' ); // phpcs:ignore WordPress.Security
			?>
			</div>
		</tr>
		<?php
	}

	/**
	 * Check if Current Customer is verified and authorized to update product
	 *
	 * @since 1.11.0
	 *
	 * @return boolean
	 */
	public function is_verified_to_update_product() {
		$api_key = jupiterx_get_option( 'api_key' );

		if ( ! empty( $api_key ) ) {
			return true;
		}

		return false;
	}
}


new JupiterX_Theme_Update();
