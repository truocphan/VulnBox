<?php
/**
 * Load all functionalities about Custom Fields.
 *
 * @package JupiterX\Framework\API\Custom_Fields
 *
 * @since   1.0.0
 */

/**
 * The Main class for populating Custom Fields.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package JupiterX\Framework\API\Custom_Fields
 */
class JupiterX_Custom_Fields {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		jupiterx_add_smart_action( 'init', [ $this, 'register_fields' ] );
		jupiterx_add_smart_action( 'acf/include_field_types', [ $this, 'register_field_types' ] );
		jupiterx_add_smart_action( 'acf/field_wrapper_attributes', [ $this, 'pro_fields' ], 10, 2 );
		jupiterx_add_smart_action( 'wp_enqueue_scripts', [ $this, 'styles' ], 15 );
		jupiterx_add_smart_action( 'save_post', [ $this, 'flush_styles' ], 20 );
		jupiterx_add_smart_action( 'acf/admin_enqueue_scripts', [ $this, 'assets' ] );
	}

	/**
	 * Register field types.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_field_types() {
		$fields = [
			'widget-area.php',
			'divider.php',
			'template.php',
		];

		foreach ( $fields as $control ) {
			require_once JUPITERX_API_PATH . 'custom-fields/field-types/' . $control;
		}
	}

	/**
	 * Register fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_fields() {
		if ( ! jupiterx_is_callable( 'ACF' ) ) {
			return;
		}

		$fields = [
			'post-types/options',
			'post-types/options/header',
			'post-types/options/main',
			'post-types/options/title-bar',
			'post-types/options/footer',
			'taxonomy',
			'user',
			'menus',
		];

		foreach ( $fields as $field ) {
			require_once JUPITERX_ADMIN_PATH . 'custom-fields/' . $field . '.php';
		}
	}

	/**
	 * Set pro fields.
	 *
	 * @since 1.3.0
	 *
	 * @param array $wrapper Wrapper attributes.
	 * @param array $field Field arguments.
	 * @return array Modified wrapper.
	 */
	public function pro_fields( $wrapper, $field ) {
		if ( jupiterx_is_pro() ) {
			return $wrapper;
		}

		if ( 'jupiterx_header_type' === $field['_name'] || 'jupiterx_footer_type' === $field['_name'] ) {
			$wrapper['data-pro-choices'] = [
				'_custom',
			];
		}

		return $wrapper;
	}

	/**
	 * Generate styles based on functions that hooked to jupiterx_post_styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function styles() {

		if ( ! function_exists( 'jupiterx_compile_css_fragments' ) ) {
			return;
		}

		$post_id = get_the_ID();

		if ( false === $post_id ) {
			return;
		}

		$styles = apply_filters( 'jupiterx_post_styles', array() );

		$css = '';

		foreach ( $styles as $func ) {
			$style = call_user_func( $func );
			$css  .= $style;
		}

		if ( ( is_preview() || is_customize_preview() ) && ! empty( $css ) ) {
			wp_add_inline_style( 'jupiterx', $css );
		} elseif ( ! empty( $css ) ) {
			jupiterx_compile_css_fragments( "jupiterx-post-{$post_id}", $styles );
		}
	}

	/**
	 * Flush styles and regenerate them.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public function flush_styles( $post_id ) {
		if ( ! function_exists( 'jupiterx_flush_compiler' ) ) {
			return;
		}

		jupiterx_flush_compiler( "jupiterx-post-{$post_id}" );
	}

	/**
	 * Enqueue needed assets for custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function assets() {
		if ( jupiterx_is_help_links() ) {
			wp_enqueue_script( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/js/help-links' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
			wp_enqueue_style( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/css/help-links' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		}

		wp_enqueue_script( 'jupiterx-custom-fields', JUPITERX_ASSETS_URL . 'dist/js/custom-fields' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
		wp_enqueue_style( 'jupiterx-custom-fields', JUPITERX_ASSETS_URL . 'dist/css/custom-fields' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
	}

}

// Init.
new JupiterX_Custom_Fields();
