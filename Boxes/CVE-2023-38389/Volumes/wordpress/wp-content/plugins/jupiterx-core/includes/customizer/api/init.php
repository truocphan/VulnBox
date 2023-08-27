<?php
/**
 * This class handles customizer function.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '_JupiterX_Core_Customizer_Init' ) ) {
	/**
	 * Extends WordPress customizer capability.
	 *
	 * @since 1.0.0
	 * @ignore
	 * @access private
	 *
	 * @package JupiterX\Framework\API\Customizer
	 */
	final class _JupiterX_Core_Customizer_Init {

		/**
		 * List of autoloaded modules.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		protected $modules = [
			'compiler'     => 'JupiterX_Customizer_Compiler',
			'kirki-extend' => 'JupiterX_Customizer_Kirki_Extend',
			'post-message' => 'JupiterX_Customizer_Post_Message',
		];

		/**
		 * Construct the class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->add_hooks();
			$this->load_modules();
		}

		/**
		 * Define customizer constants.
		 *
		 * @since 1.0.0
		 */
		protected function define_constants() {
			define( 'JUPITERX_CORE_CUSTOMIZER_PATH', trailingslashit( jupiterx_core()->plugin_dir() . 'includes/customizer/api' ) );
			define( 'JUPITERX_CORE_CUSTOMIZER_URL', trailingslashit( jupiterx_core()->plugin_url() . 'includes/customizer/api' ) );

			if ( function_exists( 'jupiterx_core' ) && is_customize_preview() ) {
				jupiterx_customizer_kirki();
			}
		}

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		protected function includes() {
			include_once JUPITERX_CORE_CUSTOMIZER_PATH . 'includes/class-autoloader.php';
			include_once JUPITERX_CORE_CUSTOMIZER_PATH . 'includes/class-templates.php';
			include_once JUPITERX_CORE_CUSTOMIZER_PATH . 'classes/class-multilingual.php';
		}

		/**
		 * Add filters and actions.
		 *
		 * @since 1.0.0
		 */
		protected function add_hooks() {
			add_action( 'customize_register', [ $this, 'register_control_types' ] );
			add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'customize_preview_init', [ $this, 'enqueue_preview_scripts' ] );
			add_action( 'scp_js_path_url', [ $this, 'multilingual_script_path' ] );
		}

		/**
		 * Load modules.
		 *
		 * @since 1.0.0
		 */
		protected function load_modules() {
			foreach ( $this->modules as $module ) {
				if ( class_exists( $module ) && ( ! method_exists( $module, 'active' ) || $module::active() ) ) {
					new $module();
				}
			}
		}

		/**
		 * Register all control types.
		 *
		 * @since 1.0.0
		 *
		 * @param object $wp_customize Global customize object.
		 */
		public function register_control_types( $wp_customize ) {
			foreach ( JupiterX_Customizer::$control_types as $control_type ) {
				$wp_customize->register_control_type( $control_type );
			}
		}

		/**
		 * Enqueue styles and scripts.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {
			wp_register_script( 'jupiterx-webfont', JUPITERX_ADMIN_ASSETS_URL . 'lib/webfont/webfont' . JUPITERX_MIN_JS . '.js', [], '1.6.26', false );
			wp_register_script( 'jupiterx-spectrum', JUPITERX_ASSETS_URL . 'customizer/lib/spectrum/spectrum' . JUPITERX_MIN_JS . '.js', [], '1.8.0', true );
			wp_register_script( 'jupiterx-select2', JUPITERX_ASSETS_URL . 'customizer/lib/select2/select2' . JUPITERX_MIN_JS . '.js', [], '4.0.6', true );
			wp_register_script( 'jupiterx-stepper', JUPITERX_ASSETS_URL . 'customizer/lib/stepper/stepper' . JUPITERX_MIN_JS . '.js', [], '1.0.0', true );
			wp_register_script( 'jupiterx-url-polyfill', JUPITERX_ASSETS_URL . 'customizer/lib/url-polyfill/url-polyfill' . JUPITERX_MIN_JS . '.js', [], '1.1.0', false );
			wp_register_script( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/js/help-links' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
			wp_enqueue_script( 'jupiterx-customizer', JUPITERX_ASSETS_URL . 'dist/js/customizer' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'jquery-ui-draggable', 'jquery-ui-sortable', 'jupiterx-webfont', 'jupiterx-spectrum', 'jupiterx-select2', 'jupiterx-stepper', 'jupiterx-url-polyfill', 'jupiterx-help-links' ], JUPITERX_VERSION, true );
			wp_register_style( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/css/help-links' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
			wp_register_style( 'jupiterx-spectrum', JUPITERX_ASSETS_URL . 'customizer/lib/spectrum/spectrum' . JUPITERX_MIN_CSS . '.css', [], '1.8.0' );
			wp_register_style( 'jupiterx-select2', JUPITERX_ASSETS_URL . 'customizer/lib/select2/select2' . JUPITERX_MIN_CSS . '.css', [], '4.0.6' );
			wp_enqueue_style( 'jupiterx-customizer', JUPITERX_ASSETS_URL . 'dist/css/customizer' . JUPITERX_RTL . JUPITERX_MIN_CSS . '.css', [ 'jupiterx-spectrum', 'jupiterx-select2', 'jupiterx-help-links' ], JUPITERX_VERSION );

			wp_localize_script( 'jupiterx-customizer', 'jupiterxCustomizer', [
				'nonce' => wp_create_nonce( 'jupiterx_customizer_preview' ),
				'customizer_preview_redirect_url_nonce' => wp_create_nonce( 'jupiterx_core_get_customizer_preview_redirect_url' ),
				'base_url' => get_stylesheet_directory_uri(),
				'themeBase' => JUPITERX_ASSETS_URL,
			] );
		}

		/**
		 * Enqueue preview styles and scripts.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_preview_scripts() {
			wp_enqueue_script( 'jupiterx-customizer-preview', JUPITERX_ASSETS_URL . 'dist/js/customizer-preview' . JUPITERX_MIN_JS . '.js', [ 'customize-preview', 'jupiterx-utils' ], JUPITERX_VERSION, true );
		}

		/**
		 * Filter js path for customizer multilingual scripts.
		 *
		 * @since 1.0.0
		 */
		public function multilingual_script_path() {
			return JUPITERX_ASSETS_URL . 'customizer/lib/';
		}
	}
}

// Run customizer class.
new _JupiterX_Core_Customizer_Init();
