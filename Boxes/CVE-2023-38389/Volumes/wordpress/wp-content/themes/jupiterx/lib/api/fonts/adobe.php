<?php
/**
 * Main class that handles Adobe fonts.
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
 * Adobe fonts loader class.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Fonts
 */
final class _JupiterX_Load_Adobe_Fonts {


	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		JupiterX_Fonts::handle_enqueue_script( $this );
		add_filter( 'jupiterx_custom_fonts', [ $this, 'add_custom_fonts' ] );
		add_filter( 'jupiterx_font_types', [ $this, 'add_font_type' ] );
		add_filter( 'elementor/fonts/groups', [ $this, 'elementor_font_group' ] );
		add_filter( 'elementor/fonts/additional_fonts', [ $this, 'add_elementor_fonts' ] );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		$project_id = jupiterx_get_option( 'adobe_fonts_project_id' );

		if ( empty( $project_id ) || empty( JupiterX_Fonts::$font_loader_type ) ) {
			return;
		}

		if ( 'css' === JupiterX_Fonts::$font_loader_type ) {
			wp_enqueue_style( 'jupiterx-typekit-fonts', "https://use.typekit.net/{$project_id}.css", array(), JUPITERX_VERSION );
			return;
		}

		$script = "WebFont.load({
			typekit: {
				id:'{$project_id}'
			}
		});";

		wp_enqueue_script( 'jupiterx-webfont' );

		// Print script.
		wp_add_inline_script( 'jupiterx-webfont', $script );
	}

	/**
	 * Add Adobe fonts from the custom fonts list of the theme.
	 *
	 * @param array $custom_fonts Current custom fonts.
	 *
	 * @return array Compiled custom fonts.
	 */
	public function add_custom_fonts( $custom_fonts ) {
		$kit_fonts = $this->load_typekit_fonts();

		if ( empty( $kit_fonts ) || ! is_array( $kit_fonts ) ) {
			return $custom_fonts;
		}

		$fonts = [];

		foreach ( $kit_fonts as $font ) {
			$fonts[ $font['name'] ] = [
				'type'  => 'adobe',
				'value' => isset( $font['css_names'][0] ) ? $font['css_names'][0] : $font['slug'],
			];
		}

		return array_merge( $custom_fonts, $fonts );
	}


	/**
	 * Load typekit fonts for jupiterX customizer and Elementor.
	 *
	 * @since 1.7.0
	 *
	 * @return array Typekit fonts.
	 */
	public function load_typekit_fonts() {
		$project_id = jupiterx_get_option( 'adobe_fonts_project_id' );

		if ( empty( $project_id ) ) {
			return;
		}

		// Get data from API.
		$response = wp_remote_get( "https://typekit.com/api/v1/json/kits/{$project_id}/published" );

		// Parse json.
		$adobe = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset( $adobe['kit']['families'] ) || empty( $adobe['kit']['families'] ) ) {
			return;
		}

		$kit_fonts = $adobe['kit']['families'];

		return $kit_fonts;
	}

	/**
	 * Add a fount group for elementor's typography control
	 *
	 * @param array $font_groups is current Elementor fonts.
	 *
	 * @since  1.7.0
	 *
	 * @access public
	 */
	public function elementor_font_group( $font_groups ) {
		$new_group['jupiterx-typekit-fonts'] = __( 'Adobe Fonts', 'jupiterx' );
		$font_groups                         = array_merge( $new_group, $font_groups );
		return $font_groups;
	}


	/**
	 * Merge Adobe fonts with Elementor's fonts
	 *
	 * @since  1.7.0
	 *
	 * @param array $fonts is current Elementor's font opject.
	 *
	 * @access public
	 *
	 * @return array add fonts to elementor typographt control.
	 */
	public function add_elementor_fonts( $fonts ) {
		$all_fonts = $this->load_typekit_fonts();

		if ( empty( $all_fonts ) || ! is_array( $all_fonts ) ) {
			return $fonts;
		}

		$elementor_typekit_fonts = [];

		foreach ( $all_fonts as $font_family_name => $fonts_url ) {
			$font_slug                            = isset( $fonts_url['slug'] ) ? $fonts_url['slug'] : '';
			$font_css                             = isset( $fonts_url['css_names'][0] ) ? $fonts_url['css_names'][0] : $font_slug;
			$elementor_typekit_fonts[ $font_css ] = 'jupiterx-typekit-fonts';
		}

		return array_merge( $fonts, $elementor_typekit_fonts );
	}
	/**
	 * Add new font type.
	 *
	 * @param array $types Current font types.
	 *
	 * @return array Combined types.
	 */
	public function add_font_type( $types ) {
		$types['adobe'] = 'Adobe Fonts' . jupiterx_get_pro_badge();

		return $types;
	}
	/**
	 * This function determine the current page created by Elementor or not.
	 *
	 * @return boolean Combined types.
	 */
	public function is_elementor() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return false;
		}
		global $post;

		if ( $post ) {
			return \Elementor\Plugin::$instance->documents->get( $post->ID )->is_built_with_elementor();
		}
	}
}

new _JupiterX_Load_Adobe_Fonts();
