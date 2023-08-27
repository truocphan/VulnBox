<?php
/**
 * This class gets the theme mods and create the variables for the CSS compiler.
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
 * Customizer CSS compiler class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Customizer_Get_Variables {

	/**
	 * Vars holder.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $vars = [];

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Include this class to avoid errors in using our controls class.
		require_once ABSPATH . WPINC . '/class-wp-customize-control.php';

		// Run compiler.
		$this->run();
	}

	/**
	 * Start the process of compiling the variables.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$controls = array_merge( JupiterX_Customizer::$control_types, JupiterX_Customizer::$group_control_types );

		foreach ( JupiterX_Customizer::$settings as $args ) {
			if ( ! isset( $args['css_var'] ) || ! isset( $args['type'] ) ) {
				continue;
			}

			$default = false;

			if ( isset( $args['default'] ) ) {
				$default = $args['default'];
			}

			$value = get_theme_mod( $args['settings'], $default );

			if ( empty( $value ) ) {
				continue;
			}

			if ( ! isset( $args['responsive'] ) ) {
				$this->add_vars( $args, $value, $controls );
			}

			if ( isset( $args['responsive'] ) && $args['responsive'] ) {
				$this->add_responsive_vars( $args, $value, $controls );
			}
		}
	}

	/**
	 * Default variable addition.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Settings arguments.
	 * @param mixed $value Settings value.
	 * @param array $controls List of Controls.
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function add_vars( $args, $value, $controls ) {
		if ( is_array( $value ) ) {
			if (
				'jupiterx-box-shadow' === $args['type'] &&
				isset( $value['position'] ) &&
				' ' === $value['position']
			) {
				$value['position'] = '';
			}

			$this->add_properties_vars( $args, $value, $controls );
		}

		if ( method_exists( $controls[ $args['type'] ], 'format_value' ) ) {
			$value = call_user_func( [ $controls[ $args['type'] ], 'format_value' ], $value, $args );
		}

		// At this point when the value is still an array then we have to cancel adding this variable.
		if ( is_array( $value ) ) {
			return;
		}

		$name = isset( $args['css_var']['name'] ) ? $args['css_var']['name'] : $args['css_var'];

		$name = isset( $args['device'] ) && 'desktop' !== $args['device'] ? "{$name}-{$args['device']}" : $name;

		$name = str_replace( '_', '-', $name );

		// Set a value replacement.
		if ( isset( $args['css_var']['value'] ) ) {
			$value = str_replace( '$', $value, $args['css_var']['value'] );
		}

		if ( is_numeric( $value ) || ! empty( $value ) ) {
			$this->vars[ str_replace( '_', '-', $name ) ] = $value;
		}
	}

	/**
	 * Responsive variable addition.
	 *
	 * @param array $args Settings arguments.
	 * @param mixed $value Settings value.
	 * @param array $controls List of Controls.
	 */
	public function add_responsive_vars( $args, $value, $controls ) {
		foreach ( JupiterX_Customizer::$responsive_devices as $device => $media_query ) {
			if ( ! isset( $value[ $device ] ) ) {
				continue;
			}

			$device_args = array_merge( $args, [
				'device' => $device,
			] );

			$device_value = $value[ $device ];

			$this->add_vars( $device_args, $device_value, $controls );
		}
	}

	/**
	 * Add properties variable from array value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Settings arguments.
	 * @param mixed $value Settings value.
	 * @param array $controls List of Controls.
	 */
	public function add_properties_vars( $args, $value, $controls ) {
		if ( method_exists( $controls[ $args['type'] ], 'format_properties' ) ) {
			$value = $controls[ $args['type'] ]::format_properties( $value, $args );
		}

		foreach ( $value as $property => $property_value ) {
			if ( isset( $args['device'] ) && 'desktop' !== $args['device'] ) {
				$property = $property . '-' . $args['device'];
			}

			$css_var = str_replace( '_', '-', sprintf( '%1$s-%2$s', $args['css_var'], $property ) );

			if ( ! is_array( $property_value ) && ( is_numeric( $property_value ) || ! empty( $property_value ) ) ) {
				$this->vars[ $css_var ] = $property_value;
			}
		}
	}

	/**
	 * Get the compiled vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array Compiled vars.
	 */
	public function get_vars() {
		return $this->vars;
	}
}
