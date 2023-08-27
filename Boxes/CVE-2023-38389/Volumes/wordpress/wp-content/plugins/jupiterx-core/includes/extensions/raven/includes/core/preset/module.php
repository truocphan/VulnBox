<?php
/**
 * Add Template Library Module.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Core\Preset;

defined( 'ABSPATH' ) || die();

/**
 * Raven preset library module.
 *
 * Raven preset library module handler class is responsible for registering and fetching
 * Raven Presets.
 *
 * @since 1.5.0
 */
class Module {

	/**
	 * Constructor
	 *
	 * @access public
	 * @since 1.5.0
	 */
	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_preset_control' ], 10, 2 );
		add_action( 'wp_ajax_raven_element_presets', [ $this, 'get_element_presets' ] );
	}

	/**
	 * Register preset control for active elements.
	 *
	 * @param mixed $element
	 * @param string $section_id
	 * @param array $args
	 * @return void
	 */
	public function register_preset_control( $element, $section_id ) {
		if ( 'widget' !== $element->get_type() ) {
			return;
		}

		$elements = $this->get_elements();

		if ( ! in_array( $element->get_name(), $elements, true ) ) {
			return;
		}

		if ( 'section_raven_presets' === $section_id ) {
			return;
		}

		if ( ! empty( $element->get_controls( 'section_raven_presets' ) ) ) {
			$element->remove_control( 'section_raven_presets' );
		}

		if ( ! empty( $element->get_controls( 'raven_presets' ) ) ) {
			$element->remove_control( 'raven_presets' );
		}

		$element->start_controls_section(
			'section_raven_presets',
			[
				'label' => 'Presets',
				'tab' => 'content',
			]
		);

		$element->add_control(
			'raven_presets',
			[
				'type' => 'raven_presets',
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Fetch raven element presets.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return void
	 */
	public function get_element_presets() {
		// phpcs:ignore WordPress.Security
		if ( empty( $_POST['raven_element'] ) ) {
			wp_send_json_error( 'raven_element field is missing' );
		}

		// phpcs:ignore WordPress.Security
		$raven_element = sanitize_text_field( wp_unslash( $_POST['raven_element'] ) );
		$url           = 'https://jupiterx.artbees.net/library/wp-json/jupiterx/v1/presets/' . $raven_element;
		$presets       = get_transient( 'raven_preset_' . $raven_element );

		if ( ! empty( $presets ) ) {
			return is_array( $presets ) ? wp_send_json_success( $presets ) : wp_send_json_success( [] );
		}

		$response = wp_remote_get( $url, [
			'timeout' => 40,
		] );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( 'Unable to fetch presets.' );
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			wp_send_json_error( 'Unable to fetch presets.' );
		}

		$presets = json_decode( wp_remote_retrieve_body( $response ), true );

		set_transient( 'raven_preset_' . $raven_element, $presets, 24 * HOUR_IN_SECONDS );

		wp_send_json_success( $presets );
	}

	/**
	 * Get active preset elements.
	 *
	 * @access public
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_elements() {
		$transient_key = 'raven_presets_elements';

		$preset_elements = get_transient( $transient_key );

		if ( false !== $preset_elements ) {
			return $preset_elements;
		}

		$preset_elements = [];

		$url = 'https://jupiterx.artbees.net/library/wp-json/jupiterx/v1/presets-elements';

		$response = wp_remote_get( $url, [
			'timeout' => 60,
		] );

		if ( is_wp_error( $response ) ) {
			set_transient( $transient_key, $preset_elements, 24 * HOUR_IN_SECONDS );
			set_transient( $transient_key . '_cached', $preset_elements );

			return $preset_elements;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			set_transient( $transient_key, $preset_elements, 24 * HOUR_IN_SECONDS );
			set_transient( $transient_key . '_cached', $preset_elements );

			return $preset_elements;
		}

		$body = wp_remote_retrieve_body( $response );

		$preset_elements = json_decode( $body, true );

		set_transient( $transient_key, $preset_elements, 24 * HOUR_IN_SECONDS );
		set_transient( $transient_key . '_cached', $preset_elements );

		return $preset_elements;
	}
}
