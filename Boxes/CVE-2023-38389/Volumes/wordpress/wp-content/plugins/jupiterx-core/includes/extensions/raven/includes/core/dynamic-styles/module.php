<?php
/**
 * Dynamic styles.
 *
 * @package JupiterX_Core\Raven
 * @since 1.20.0
 */

namespace JupiterX_Core\Raven\Core\Dynamic_Styles;

use Elementor\Core\Responsive\Files\Frontend;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Dynamic styles.
 *
 * Dynamic styles module handler class is responsible for generating dynamic
 * styles based on Elementor breakpoints.
 *
 * @since 1.20.0
 */
class Module {

	private $styles = [];

	/**
	 * Constructor.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'compile' ] );
	}

	/**
	 * Compiler and enqueue the styles.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function compile() {
		if ( ! function_exists( 'jupiterx_compile_css_fragments' ) ) {
			return;
		}

		$this->parse();

		if ( empty( $this->styles ) ) {
			return;
		}

		jupiterx_compile_css_fragments(
			'jupiterx-elements-dynamic-styles',
			apply_filters( 'jupiterx-elements-dynamic-css', $this->styles ),
			[ 'fragments_type' => 'string' ]
		);
	}

	/**
	 * Parse the style to replace the Elementor breakpoints.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	private function parse() {
		$rtl   = is_rtl() ? '-rtl' : '';
		$style = jupiterx_core()->plugin_dir() . "/includes/extensions/raven/assets/css/dynamic-styles{$rtl}.min.css";

		if ( ! file_exists( $style ) ) {
			$this->styles = false;
			return;
		}

		$parser = new Frontend( 'jupiterx-core', $style );

		$this->styles = $parser->parse_content();
	}
}
