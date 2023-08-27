<?php
/**
 * This class handles CSS post message for Customizer previewer.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post message preview scripts.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Customizer_Post_Message {

	/**
	 * Module activate condition.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean Class active state.
	 */
	public static function active() {
		return class_exists( 'Kirki' );
	}

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_scripts' ] );
	}

	/**
	 * Enqueue preview styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_preview_scripts() {
		wp_enqueue_script( 'jupiterx-customizer-postmessage', JUPITERX_ASSETS_URL . 'dist/js/customizer-postmessage' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'kirki_auto_postmessage' ], JUPITERX_VERSION, true );

		$settings = [];

		foreach ( JupiterX_Customizer::$settings as $key => $setting ) {
			if ( isset( $setting['transport'] ) && 'postMessage' === $setting['transport'] && isset( $setting['output'] ) && ! empty( $setting['output'] ) ) {
				$settings[] = $setting;
			}
		}

		wp_localize_script( 'jupiterx-customizer-postmessage', 'jupiterPostMessage', [
			'settings'          => $settings,
			'responsiveDevices' => JupiterX_Customizer::$responsive_devices,
		] );
	}
}
