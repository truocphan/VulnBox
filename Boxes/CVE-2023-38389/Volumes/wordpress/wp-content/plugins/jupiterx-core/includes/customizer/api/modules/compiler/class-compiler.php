<?php
/**
 * This class initializes actions for compiling theme settings into CSS variables.
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
 * Initialize compiler actions.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Customizer_Compiler {

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_save_after', [ $this, 'customize_save' ] );
		add_filter( 'jupiterx_compiler_less_variables', [ $this, 'css_variables' ] );
	}

	/**
	 * Run after customizer save.
	 *
	 * @since 1.0.0
	 */
	public function customize_save() {
		if ( ! function_exists( 'jupiterx_flush_compiler' ) ) {
			return;
		}

		jupiterx_flush_compiler( 'jupiterx' );
	}

	/**
	 * Get variables and pass to the Jupiter CSS compiler.
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars Returns variables.
	 */
	public function css_variables( $vars ) {
		// Start compiling vars.
		$compiler = new JupiterX_Customizer_Get_Variables();

		// Get compiled vars.
		$css_vars = $compiler->get_vars();

		// Combine and overwrite.
		return array_merge( $vars, $css_vars );
	}
}
