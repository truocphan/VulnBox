<?php
/**
 * Masteriyo Divi extension.
 *
 * @package Masteriyo\Addons\DiviIntegration
 *
 * @since 1.6.13
 */

namespace Masteriyo\Addons\DiviIntegration;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo Divi extension.
 *
 * @package Masteriyo\Addons\DiviIntegration
 *
 * @since 1.6.13
 */
class MasteriyoDiviExtension extends \DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	public $gettext_domain = 'masteriyo';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	public $name = 'masteriyo-divi-extension';

	/**
	 * The extension's version
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	public $version = MASTERIYO_VERSION;

	/**
	 * Constructor.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function __construct( $name = 'masteriyo-divi-extension', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );

		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_scripts_styles' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ), 99 );
		add_filter( 'body_class', array( $this, 'add_body_class' ), 10, 2 );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.6.13
	 */
	public function enqueue_scripts_styles() {
		if ( ! Helper::is_divi_builder() ) {
			return;
		}

		$divi_integration_src = plugins_url( '/addons/divi-integration/js/build/diviIntegration.js', MASTERIYO_PLUGIN_FILE );

		if ( masteriyo_is_development() ) {
			$divi_integration_src = 'http://localhost:3000/dist/diviIntegration.js';
		} else {
			$divi_integration_dep_src = plugins_url( '/addons/divi-integration/js/build/dependencies.js', MASTERIYO_PLUGIN_FILE );

			wp_enqueue_script(
				'masteriyo-divi-integration-dependency',
				$divi_integration_dep_src,
				array( 'jquery' ),
				$this->version,
				true
			);
		}

		wp_enqueue_script(
			'masteriyo-divi-integration',
			$divi_integration_src,
			masteriyo_is_development() ? array( 'jquery' ) : array( 'jquery', 'masteriyo-divi-integration-dependency' ),
			$this->version,
			true
		);

		wp_add_inline_script( 'masteriyo-divi-integration', 'var _MASTERIYO_STYLE_TEMPLATES_ = [];' );
		wp_add_inline_script( 'masteriyo-divi-integration', 'var _MASTERIYO_SPECIAL_SETTINGS_ = {};' );
	}

	/**
	 * Dequeue the frontend bundle as this extension does not need JS and CSS in the frontend.
	 *
	 * @since 1.6.15
	 *
	 * @return void
	 */
	public function dequeue_scripts_styles() {
		wp_dequeue_script( "{$this->name}-frontend-bundle" );
		wp_dequeue_style( "{$this->name}-styles" );
	}

	/**
	 * Add class to the body tag.
	 *
	 * @since 1.6.13
	 *
	 * @param string[] $classes An array of body class names.
	 * @param string[] $class   An array of additional class names added to the body.
	 *
	 * @return string[]
	 */
	public function add_body_class( $classes, $class ) {
		if ( Helper::is_divi_builder() && ! in_array( 'masteriyo', $classes, true ) ) {
			$classes[] = 'masteriyo';
		}

		return $classes;
	}
}
