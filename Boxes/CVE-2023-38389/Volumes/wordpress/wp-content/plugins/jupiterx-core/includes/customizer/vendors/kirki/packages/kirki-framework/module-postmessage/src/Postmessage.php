<?php
/**
 * Automatic postMessage scripts calculation for Kirki controls.
 *
 * @package kirki-framework/module-postmessage
 * @author Ari Stathopoulos (@aristath)
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Module;

use Kirki\URL;

/**
 * Adds styles to the customizer.
 */
class Postmessage {

	/**
	 * An array of fields to be processed.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'the_style' ], 0 );
		add_action( 'customize_preview_init', [ $this, 'postmessage' ] );
		add_action( 'kirki_field_add_setting_args', [ $this, 'field_add_setting_args' ] );
	}


	/**
	 * Add styles in <head>.
	 *
	 * @access public
	 * @since 3.0.28
	 * @return void
	 */
	public function the_style() {
		// Get an array of all fields.
		$fields = \Kirki\Compatibility\Kirki::$fields;
		echo '<style id="kirki-css-vars">';
		echo ':root{';
		foreach ( $fields as $id => $args ) {
			if ( ! isset( $args['css_vars'] ) || empty( $args['css_vars'] ) ) {
				continue;
			}
			$val = Kirki_Values::get_value( $args['kirki_config'], $id );
			foreach ( $args['css_vars'] as $css_var ) {
			if ( isset( $css_var[2] ) && is_array( $val ) && isset( $val[ $css_var[2] ] ) ) {
				$val = $val[ $css_var[2] ];
			}
			echo esc_attr( $css_var[0] ) . ':' . esc_attr( str_replace( '$', $val, $css_var[1] ) ) . ';';
		}
		}
		echo '}';
		echo '</style>';
	}

	/**
	 * Filter setting args before adding the setting to the customizer.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $args The field arguments.
	 * @return array
	 */
	public function field_add_setting_args( $args ) {

		if ( ! isset( $args['transport'] ) ) {
			return $args;
		}

		$args['transport'] = 'auto' === $args['transport'] ? 'postMessage' : $args['transport'];

		if ( 'postMessage' === $args['transport'] ) {
			$args['js_vars'] = isset( $args['js_vars'] ) ? (array) $args['js_vars'] : [];
			$args['output']  = isset( $args['output'] ) ? (array) $args['output'] : [];
			$js_vars         = $args['js_vars'];

			// Try to auto-generate js_vars.
			// First we need to check if js_vars are empty, and that output is not empty.
			if ( empty( $args['js_vars'] ) && ! empty( $args['output'] ) ) {

				// Convert to array of arrays if needed.
				if ( isset( $args['output']['element'] ) ) {
					/* translators: The field ID where the error occurs. */
					_doing_it_wrong( __METHOD__, sprintf( esc_html__( '"output" invalid format in field %s. The "output" argument should be defined as an array of arrays.', 'jupiterx-core' ), esc_html( $args['settings'] ) ), '3.0.10' );

					$args['output'] = array( $args['output'] );
				}

				foreach ( $args['output'] as $output ) {
					$output['element']  = isset( $output['element'] ) ? $output['element'] : ':root';
					$output['element']  = is_array( $output['element'] ) ? implode( ',', $output['element'] ) : $output['element'];
					$output['function'] = isset( $output['function'] ) ? $output['function'] : 'style';
					$js_vars[]          = $output;
				}
			}

			$args['js_vars'] = $js_vars;
		}

		$this->fields[] = $args;

		return $args;

	}

	/**
	 * Enqueues the postMessage script
	 * and adds variables to it using the wp_localize_script function.
	 * The rest is handled via JS.
	 */
	public function postmessage() {
		wp_enqueue_script( 'kirki_auto_css_vars', URL::get_from_path( __DIR__ . '/script.js' ), [ 'jquery', 'customize-preview' ], KIRKI_VERSION, true );
		wp_enqueue_script( 'kirki_auto_postmessage', URL::get_from_path( __DIR__ . '/postMessage.js' ), [ 'jquery', 'customize-preview', 'wp-hooks' ], '1.0.6', true );

		$fields = $this->fields;

		// Compatibility with v3 API.
		if ( class_exists( '\Kirki\Compatibility\Kirki' ) ) {
			$fields = array_merge( \Kirki\Compatibility\Kirki::$fields, $fields );
		}

		$data = [];

		foreach ( $fields as $field ) {
			if ( isset( $field['transport'] ) && 'postMessage' === $field['transport'] && isset( $field['css_vars'] ) && ! empty( $field['css_vars'] ) ) {
				$data[] = $field;
			}
		}

		wp_localize_script( 'kirki_auto_postmessage', 'kirkiPostMessageFields', $data );
		wp_localize_script( 'kirki_auto_css_vars', 'kirkiCssVarFields', $data );

		$extras = apply_filters( 'kirki_postmessage_script', false );

		if ( $extras ) {
			wp_add_inline_script( 'kirki_auto_postmessage', $extras, 'after' );
		}

	}

}
