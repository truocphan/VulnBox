<?php
/**
 * This class handles admin notices.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.18.0
 */

/**
 * Handle admin notices.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.18.0
 */
class JupiterX_Core_Admin_Notices {

	/**
	 * Constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'admin_notices', [ $this, 'check_required_plugins' ] );
		add_filter( 'jet-dashboard/js-page-config', [ $this, 'remove_croco_license_notice' ], 10, 1 );
	}

	/**
	 * Remove Croco notice.
	 *
	 * @param $notices
	 * @return void|array
	 * @since 1.20.0
	 */
	public function remove_croco_license_notice( $notices ) {
		if ( empty( $notices['noticeList'] ) ) {
			return $notices;
		}

		foreach ( $notices['noticeList'] as $key => $notice ) {
			if ( empty( $notice['id'] ) || '30days-to-license-expire' !== $notice['id'] ) {
				continue;
			}

			unset( $notices['noticeList'][ $key ] );
		}

		// Reindex array after unset
		$notices['noticeList'] = array_values( $notices['noticeList'] );

		return $notices;
	}

	/**
	 * Check required plugins.
	 *
	 * @since 1.18.0
	 */
	public function check_required_plugins() {
		$required_plugins = [
			'Elementor\Plugin' => 'Elementor',
			'ACF' => 'Advanced Custom Fields',
		];

		foreach ( $required_plugins as $plugin_class => $plugin_name ) {
			if ( ! class_exists( $plugin_class ) ) {
				continue;
			}

			unset( $required_plugins[ $plugin_class ] );
		}

		if ( empty( $required_plugins ) ) {
			return;
		}

		$required_plugins = array_values( $required_plugins );

		?>
		<div class="notice notice-warning is-dismissible">
			<p>
			<?php
				if ( count( $required_plugins ) === 1 ) {
					printf(
					/* translators: The required plugins. */
						esc_html__( '%1$s requires %2$s plugin to be installed and activated.', 'jupiterx-core' ),
						'<strong>Jupiter X</strong>',
						"<strong>$required_plugins[0]</strong>"
					);
				}

				if ( count( $required_plugins ) === 2 ) {
					printf(
						/* translators: The required plugins. */
						esc_html__( '%1$s requires %2$s and %3$s plugins to be installed and activated.', 'jupiterx-core' ),
						'<strong>Jupiter X</strong>',
						'<strong>Elementor</strong>',
						'<strong>Advanced Custom Fields</strong>'
					);
				}
			?>
			</p>
			<p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=jupiterx#/maintenance' ); ?>"><?php esc_html_e( 'Activate them in Dashboard > Bundled Plugins', 'jupiterx-core' ); ?></a></p>
		</div>
		<?php
	}

}

new JupiterX_Core_Admin_Notices();
