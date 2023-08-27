<?php
/**
 * The file class that handles sellkit box.
 *
 * @package JupiterX_Core\Control_Panel_2\Sellkit.
 *
 * @since 2.0.6
 */

/**
 * Layout Builder class.
 *
 * @since 2.0.6
 */
class JupiterX_Core_Control_Panel_Sellkit {
	/**
	 * Class instance.
	 *
	 * @since 2.0.6
	 *
	 * @var JupiterX_Core_Control_Panel_Sellkit Class instance.
	 */
	private static $instance = null;

	/**
	 * Get a class instance.
	 *
	 * @since 2.0.6
	 *
	 * @return JupiterX_Core_Control_Panel_Sellkit Class instance.
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
	 * @since 2.0.6
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_set_sellkit_dismiss', [ $this, 'set_dismiss' ] );
		add_action( 'wp_ajax_jupiterx_install_sellkit_pro', [ $this, 'install_sellkit_pro' ] );
		add_action( 'wp_ajax_jupiterx_install_sellkit_free', [ $this, 'install_sellkit_free' ] );

		// Get TGMPA.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		}
	}

	/**
	 * Set sellkit box dismiss value.
	 *
	 * @return array
	 */
	public function set_dismiss() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$status = filter_input( INPUT_POST, 'status' );

		update_user_meta(
			get_current_user_id(),
			'jupiterx_dismiss_sellkit_box',
			$status
		);

		wp_send_json_success( [ 'status' => $status ] );
	}

	/**
	 * Get sellkit pro install and acitvate link form tgmpa.
	 *
	 * @return array
	 */
	public function install_sellkit_pro() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$action = 'install';
		$plugin = 'sellkit-pro';

		if ( empty( $this->tgmpa ) ) {
			return admin_url( 'themes.php?page=tgmpa-install-plugins' );
		}

		if ( $this->tgmpa->is_plugin_installed( $plugin ) ) {
			$action = 'activate';
		}

		$install_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin' => rawurlencode( $plugin ),
					'tgmpa-install' => 'install-plugin',
				],
				$this->tgmpa->get_tgmpa_url()
			),
			'tgmpa-install',
			'tgmpa-nonce'
		);

		$active_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin' => rawurlencode( $plugin ),
					'tgmpa-activate' => 'activate-plugin',
				],
				$this->tgmpa->get_tgmpa_url()
			),
			'tgmpa-activate',
			'tgmpa-nonce'
		);

		wp_send_json_success(
			[
				'active' => $active_url,
				'install' => $install_url,
				'action' => $action,
			]
		);
	}

	/**
	 * Get sellkit free install and acitvate link form tgmpa.
	 *
	 * @return array
	 */
	public function install_sellkit_free() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$action = 'install';
		$plugin = 'sellkit';

		if ( empty( $this->tgmpa ) ) {
			return admin_url( 'themes.php?page=tgmpa-install-plugins' );
		}

		if ( $this->tgmpa->is_plugin_installed( $plugin ) ) {
			$action = 'activate';
		}

		$install_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin' => rawurlencode( $plugin ),
					'tgmpa-install' => 'install-plugin',
				],
				$this->tgmpa->get_tgmpa_url()
			),
			'tgmpa-install',
			'tgmpa-nonce'
		);

		$active_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin' => rawurlencode( $plugin ),
					'tgmpa-activate' => 'activate-plugin',
				],
				$this->tgmpa->get_tgmpa_url()
			),
			'tgmpa-activate',
			'tgmpa-nonce'
		);

		wp_send_json_success(
			[
				'active' => $active_url,
				'install' => $install_url,
				'action' => $action,
			]
		);
	}
}

JupiterX_Core_Control_Panel_Sellkit::get_instance();
