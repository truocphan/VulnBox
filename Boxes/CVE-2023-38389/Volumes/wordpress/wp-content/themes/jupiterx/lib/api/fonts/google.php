<?php
/**
 * Main class that handles Google fonts.
 *
 * @package JupiterX\Framework\API\Fonts
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Google fonts loader class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Fonts
 */
final class _JupiterX_Load_Google_Fonts {

	/**
	 * Google fonts.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $google_fonts = [];

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Save the new font families after saving the customizer.
		add_action( 'customize_save_after', [ $this, 'get_selected_google_font' ] );
		add_filter( 'style_loader_tag', [ $this, 'custom_parameter_for_font_css' ], 10, 2 );
		JupiterX_Fonts::handle_enqueue_script( $this );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		global $wp_customize;

		$this->google_fonts = jupiterx_get_option( 'jupiterx_selected_google_fonts' );

		// Check customizer is updating font_family field Or check our selected google font is empty.
		if (
			( $wp_customize && $this->check_font_family_is_updated( $wp_customize->unsanitized_post_values() ) )
			|| empty( $this->google_fonts )
		) {
			$this->get_selected_google_font();
		}

		// Don't enqueue if there's no Google font selected from the Customizer.
		if ( empty( $this->google_fonts ) || empty( JupiterX_Fonts::$font_loader_type ) ) {
			return;
		}

		if ( 'css' === JupiterX_Fonts::$font_loader_type ) {
			// Print script.
			$this->enqueue_fonts();
			return;
		}

		wp_enqueue_script( 'jupiterx-webfont' );
		// Print script.
		wp_add_inline_script( 'jupiterx-webfont', $this->enqueue_fonts() );

	}

	/**
	 * Get script to print.
	 *
	 * @since 1.0.0
	 * @return string|void
	 */
	public function enqueue_fonts() {
		$fonts                      = array_keys( $this->google_fonts );
		$old_selected_fonts_subsets = $this->get_selected_font_subset();
		// String weights.
		$weights = implode( ',', JupiterX_Fonts::FONT_WEIGHTS );

		// Add weights and subsets to each fonts.
		$fonts = array_map( function ( $value ) use ( $weights, $old_selected_fonts_subsets ) {
			$subsets = '';

			if ( $old_selected_fonts_subsets && ! empty( $old_selected_fonts_subsets[ $value ] ) ) {
				$subsets = implode( ',', $old_selected_fonts_subsets[ $value ] );
			}

			$subsets = ! empty( $subsets ) && 'latin' !== $subsets ? ':' . $subsets : '';

			if ( 'javascript' === JupiterX_Fonts::$font_loader_type ) {
				return "{$value}:{$weights}{$subsets}";
			}

			return empty( $subsets ) ? "{$value}:{$weights}" : "{$value}:{$weights}&subset={$subsets}";
		}, $fonts );

		$fonts = apply_filters( 'jupiterx_webfontloader_google', $fonts );

		if ( 'javascript' === JupiterX_Fonts::$font_loader_type ) {
			$fonts = implode( "','", $fonts );

			return "WebFont.load({
				google: {
					families: ['{$fonts}']
				}
			});";
		}

		$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $fonts ) );

		wp_enqueue_style( 'jupiterx-google-fonts', $fonts_url, [], JUPITERX_VERSION );
	}

	/**
	 * Add rel and type to fonts link tag.
	 *
	 * @param string $html Link html stucture.
	 * @param string $handle Link unique id.
	 * @since 2.0.0
	 * @return string
	 */
	public function custom_parameter_for_font_css( $html, $handle ) {
		if ( 0 === strcmp( $handle, 'jupiterx-google-fonts' ) ) {
			$html = str_replace( "rel='stylesheet'", "type='text/css' rel='stylesheet'", $html ); // phpcs:ignore
		}

		return $html;
	}

	/**
	 * Get all selected google fonts
	 *
	 * @return void
	 */
	public function get_selected_google_font() {
		$this->google_fonts = [];

		if ( ! method_exists( 'JupiterX_Customizer', 'get_fields' ) ) {
			$this->google_fonts = JupiterX_Fonts::get_registered_fonts( 'google' );
			return;
		}

		foreach ( JupiterX_Customizer::get_fields() as $args ) {
			if ( ! isset( $args['type'] ) || 'jupiterx-typography' !== $args['type'] ) {
				continue;
			}

			// Get the value.
			$value = self::get_sanitized_field_value( $args );

			if ( isset( $value['desktop'] ) ) {
				$value = $value['desktop'];
			}

			// Add the requested google-font.
			if ( ! empty( $value['font_family'] )
				&& in_array( $value['font_family'], JupiterX_Fonts::GOOGLE_FONTS, true )
				&& ! isset( $this->google_fonts[ $value['font_family'] ] ) ) {
				$this->google_fonts[ $value['font_family'] ] = array();
			}
		}

		jupiterx_update_option( 'jupiterx_selected_google_fonts', $this->google_fonts );
	}

	/**
	 * Gets the value or falls back to default.
	 *
	 * @static
	 * @access public
	 * @param array $field The field arguments.
	 * @return string|array|boolean
	 */
	public static function get_sanitized_field_value( $field ) {
		$default = ! empty( $field['default'] ) ? $field['default'] : false;
		$value   = get_theme_mod( $field['settings'], $default );

		return $value;
	}

	/**
	 * Gets selected font subsets.
	 *
	 * @static
	 * @access public
	 * @return array|boolean
	 */
	public static function get_selected_font_subset() {
		$old_selected_fonts       = get_theme_mod( 'jupiterx_typography_fonts', [] );
		$formatted_selected_fonts = [];

		foreach ( $old_selected_fonts as $key => $old_selected_font ) {
			if ( empty( $old_selected_font['subsets'] ) || empty( $old_selected_font['name'] ) ) {
				continue;
			}

			if ( is_array( $old_selected_font['subsets'] ) && count( $old_selected_font['subsets'] ) > 0 ) {
				$formatted_selected_fonts[ $old_selected_font['name'] ] = $old_selected_font['subsets'];
			}
		}

		if ( count( $formatted_selected_fonts ) === 0 ) {
			return false;
		}

		return apply_filters( 'jupiterx_old_selected_subsets', $formatted_selected_fonts );
	}

	/**
	 * Check font family is updated is customizer.
	 *
	 * @static
	 * @access public
	 * @param array $array The main array of changed values.
	 *
	 * @return array|boolean
	 */
	private function check_font_family_is_updated( $array ) {
		if ( array_key_exists( 'font_family', $array ) ) {
			return true;
		}

		foreach ( $array as $element ) {
			if ( ! is_array( $element ) ) {
				continue;
			}

			if ( $this->check_font_family_is_updated( $element ) ) {
				return true;
			}
		}

		return false;
	}
}

new _JupiterX_Load_Google_Fonts();
