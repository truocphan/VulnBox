<?php // phpcs:ignore

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly.

/**
 * STM PLugin Notices class
 */
class STMNotices {

	/**
	 * Initializa building of admin notice
	 *
	 * @param array $plugin_data - data related to plugin.
	 * @return void
	 */
	public static function init( $plugin_data ) {

		if ( ! isset( $plugin_data['notice_title'] ) || ! isset( $plugin_data['notice_logo'] ) ) {
			return;
		}

		add_filter(
			'stm_admin_notices_data',
			function ( $notices ) use ( $plugin_data ) {
				$notices[] = $plugin_data;

				return $notices;
			}
		);

		add_action( 'admin_notices', array( self::class, 'stm_admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'admin_enqueue' ), 100 );

		add_action( 'wp_ajax_stm_discard_admin_notice', array( self::class, 'discard_admin_notice' ) );
		add_action( 'add_admin_notice', array( self::class, 'build_notice' ) );
	}

	/**
	 * Admin notices
	 *
	 * @return void
	 */
	public static function stm_admin_notices() {

		$notice_data = apply_filters( 'stm_admin_notices_data', array() );

		foreach ( $notice_data as $data ) {
			self::build_notice( $data );
		}
	}

	/**
	 * Discard Admin notices
	 *
	 * @return void
	 */
	public static function discard_admin_notice() {
		if ( isset( $_POST['pluginName'] ) ) {
			$plugin_name = sanitize_text_field( $_POST['pluginName'] );
			set_transient( 'stm_' . $plugin_name . '_notice_setting', 1, 0 );
		}
	}

	/**
	 * Enqueue admin notice scripts
	 *
	 * @return void
	 */
	public static function admin_enqueue() {
		wp_enqueue_style( 'stm_admin_notice', STM_ADMIN_NOTICES_URL . 'assets/css/admin.css', false ); // phpcs:ignore
		wp_enqueue_script( 'stm_admin_notice', STM_ADMIN_NOTICES_URL . 'assets/js/an-scripts.js', array( 'jquery' ), '1.0', true );
	}

	/**
	 * Builds admin notice
	 *
	 * @param array $plugin_data - data related to plugin.
	 * @return void
	 */
	public static function build_notice( $plugin_data ) {

		$btn_one_class   = ( ! empty( $plugin_data['notice_btn_one_class'] ) ) ? ' ' . $plugin_data['notice_btn_one_class'] : '';
		$btn_two_class   = ( ! empty( $plugin_data['notice_btn_two_class'] ) ) ? ' ' . $plugin_data['notice_btn_two_class'] : '';
		$btn_three_class = ( ! empty( $plugin_data['notice_btn_three_class'] ) ) ? ' ' . $plugin_data['notice_btn_three_class'] : '';
		$btn_one_attrs   = ( ! empty( $plugin_data['notice_btn_one_attrs'] ) ) ? ' ' . $plugin_data['notice_btn_one_attrs'] : '';
		$btn_two_attrs   = ( ! empty( $plugin_data['notice_btn_two_attrs'] ) ) ? ' ' . $plugin_data['notice_btn_two_attrs'] : '';
		$btn_three_attrs = ( ! empty( $plugin_data['notice_btn_three_attrs'] ) ) ? ' ' . $plugin_data['notice_btn_three_attrs'] : '';

		$html  = '<div class="notice is-dismissible stm-notice stm-notice-' . esc_attr( $plugin_data['notice_type'] ) . '">';
		$html  = '<div class="notice is-dismissible stm-notice stm-notice-' . esc_attr( $plugin_data['notice_type'] ) . '">';
		$html .= '<div class="img"><img src="' . STM_ADMIN_NOTICES_URL . 'assets/img/' . esc_attr( $plugin_data['notice_logo'] ) . '" /></div>';
		$html .= '<div class="text-wrap">';
		$html .= '<h4>' . $plugin_data['notice_title'] . '</h4>';
		$html .= ( ! empty( $plugin_data['notice_desc'] ) ) ? '<h5>' . $plugin_data['notice_desc'] . '</h5>' : '';
		$html .= '</div>';
		$html .= ( ! empty( $plugin_data['notice_btn_one'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_one'] ) . '" class="button btn-first' . $btn_one_class . '" ' . esc_attr( $btn_one_attrs ) . '>' . $plugin_data['notice_btn_one_title'] . '</a>' : '';
		$html .= ( ! empty( $plugin_data['notice_btn_two'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_two'] ) . '" class="button btn-second' . $btn_two_class . '" ' . esc_attr( $btn_two_attrs ) . '>' . $plugin_data['notice_btn_two_title'] . '</a>' : '';
		$html .= ( ! empty( $plugin_data['notice_btn_three'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_three'] ) . '" class="button btn-second' . $btn_three_class . '" ' . esc_attr( $btn_three_attrs ) . '>' . $plugin_data['notice_btn_three_title'] . '</a>' : '';
		$html .= '</div>';

		echo wp_kses_post( $html );
	}
}
