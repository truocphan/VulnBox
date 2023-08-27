<?php
/**
 * This class handles init of core plugin installer.
 *
 * @since 1.0.0
 *
 * @package Jupiter\Framework\Admin\Core_Install
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init theme core installer.
 *
 * @since 1.0.0
 *
 * @package Jupiter\Framework\Admin\Core_Install
 */
class JupiterX_Theme_Core_Install {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_print_scripts', 'wp_print_admin_notice_templates' );
		add_action( 'wp_ajax_jupiterx_core_install_plugin_notice', [ $this, 'dismiss_notice' ] );
		add_action( 'admin_notices', [ $this, 'install_notice' ], 2 );
	}

	/**
	 * Load scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'jupiterx-core-install', JUPITERX_ASSETS_URL . 'dist/css/core-install' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_script( 'jupiterx-core-install', JUPITERX_ASSETS_URL . 'dist/js/core-install' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'wp-util', 'updates' ], JUPITERX_VERSION, true );
		wp_localize_script( 'jupiterx-core-install', 'jupiterxCoreInstall', [
			'controlPanelUrl' => admin_url( 'admin.php?page=' . JUPITERX_SLUG ),
			'i18n'            => [
				'idle'            => esc_html__( 'Activate Jupiter X Core Plugin', 'jupiterx' ),
				'installing'      => esc_html__( 'Installing plugin...', 'jupiterx' ),
				'activating'      => esc_html__( 'Activating plugin...', 'jupiterx' ),
				'completed'       => esc_html__( 'Plugin activation completed.', 'jupiterx' ),
				'errorActivating' => esc_html__( 'There was an issue during the activation process.', 'jupiterx' ),
			],
		] );
	}

	/**
	 * Print admin notice.
	 *
	 * @since 1.0.0
	 */
	public function install_notice() {
		$notice_state_meta = 'jupiterx_core_install_plugin_notice';

		if ( get_user_meta( get_current_user_id(), $notice_state_meta, true ) === 'disabled' ) {
			return;
		}

		if ( jupiterx_get( 'tgmpa-nonce' ) ) {
			return;
		}
		?>

		<div id="jupiterx-core-install-notice" class="updated jupiterx-core-install-notice notice is-dismissible">
			<?php wp_nonce_field( 'jupiterx-core-installer-nonce', 'jupiterx-core-installer-notice-nonce' ); ?>
			<?php  ?>
			<div class="jupiterx-core-install-notice-logo">
				<img src="<?php echo esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/jupiterx-notice-logo.png' ); ?>" alt="<?php esc_html_e( 'Jupiter X', 'jupiterx' ); ?>" />
			</div>
			<?php  ?>
			<div class="jupiterx-core-install-notice-content">
			<?php
			$notice_title = __( 'Almost done!', 'jupiterx' );
			$notice_title = __( 'Almost done! 👋', 'jupiterx' );
			?>
				<h2><?php echo esc_html( $notice_title ); ?></h2>
				<p><?php esc_html_e( 'To complete the installation and unlock more features, we highly recommend to activate Jupiter X Core plugin.', 'jupiterx' ); ?> <a class="jupiterx-core-install-notice-link" target="_blank" href="<?php echo esc_url( 'https://themes.artbees.net/docs/getting-started-with-jupiter-x/' ); ?>"><?php esc_html_e( 'Learn More', 'jupiterx' ); ?>.</a></p>
				<?php $this->install_notice_button(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Install notice button.
	 *
	 * @since 1.7.0
	 */
	private function install_notice_button() {
		$action = 'install';
		$plugin = 'jupiterx-core';

		if ( empty( $this->tgmpa ) ) {
			return admin_url( 'themes.php?page=tgmpa-install-plugins' );
		}

		if ( $this->tgmpa->is_plugin_installed( $plugin ) ) {
			$action = 'activate';
		}

		$nonce_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin'           => rawurlencode( $plugin ),
					'tgmpa-' . $action => $action . '-plugin',
				],
				$this->tgmpa->get_tgmpa_url()
			),
			'tgmpa-' . $action,
			'tgmpa-nonce'
		);

		?>
		<a class="button button-primary button-hero" href="<?php echo esc_url( $nonce_url ); ?>">
			<?php  ?>
			<span class="dashicons dashicons-download"></span>
			<?php  ?>
			<span class="button-text">
			<?php
				/* translators: The install/activate action */
				printf( esc_html__( '%s Jupiter X Core Plugin', 'jupiterx' ), esc_html( $action ) );
			?>
			</span>
		</a>
		<?php
	}

	/**
	 * Update notice visibility.
	 *
	 * @since 1.2.0
	 */
	public function dismiss_notice() {
		check_ajax_referer( 'jupiterx-core-installer-nonce', '_wpnonce' );

		if ( empty( $_POST['state'] ) ) {
			wp_send_json_error();
		}

		update_user_meta(
			get_current_user_id(),
			'jupiterx_core_install_plugin_notice',
			sanitize_text_field( wp_unslash( $_POST['state'] ) )
		);

		wp_send_json_success();
	}

}

/**
 * Run the core installer.
 *
 * Show installer notice only when logged in user can manage install plugins and core plugin is not installed or activated.
 *
 * @since 1.0.0
 */
if ( current_user_can( 'install_plugins' ) ) {
	if ( ! function_exists( 'jupiterx_core' ) ) {
		new JupiterX_Theme_Core_Install( 'install' );
	}
}
