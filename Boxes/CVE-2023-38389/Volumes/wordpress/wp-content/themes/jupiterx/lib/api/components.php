<?php
/**
 * The Jupiter Component defines which API components of the framework are loaded.
 *
 * It can be different on a per page bases. This keeps Jupiter as performant and lightweight as possible
 * by only loading what is needed.
 *
 * @package JupiterX\Framework\API
 *
 * @since 1.0.0
 */

/**
 * Load Jupiter API components.
 *
 * This function loads Jupiter API components. Components are only loaded once, even if they are called many times.
 * Admin components and functions are automatically wrapped in an is_admin() check.
 *
 * @since 1.0.0
 *
 * @param string|array $components Name of the API component(s) to include as and indexed array. The name(s) must be
 *                                 the Jupiter API component folder.
 *
 * @return bool Will always return true.
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_load_api_components( $components ) {
	static $loaded = array();

	$root = JUPITERX_API_PATH;

	$common = array(
		'api'           => $root . 'api/functions.php',
		'compatibility' => [
			$root . 'compatibility/functions.php',
			$root . 'compatibility/class.php',
		],
		'html'          => [
			$root . 'html/functions.php',
			$root . 'html/class-attribute.php',
		],
		'actions'       => $root . 'actions/functions.php',
		'filters'       => $root . 'filters/functions.php',
		'fonts'         => $root . 'fonts/class.php',
		'customizer'    => [
			$root . 'customizer/class-utils.php',
			$root . 'customizer/functions.php',
		],
		'custom-fields'     => [
			$root . 'custom-fields/functions.php',
			$root . 'custom-fields/class.php',
		],
		'image'           => $root . 'image/functions.php',
		'layout'          => $root . 'layout/functions.php',
		'template'        => $root . 'template/functions.php',
		'header'          => $root . 'header/functions.php',
		'widget'          => $root . 'widget/functions.php',
		'menu'            => $root . 'menu/class.php',
		'footer'          => $root . 'footer/functions.php',
		'woocommerce'     => $root . 'woocommerce/functions.php',
		'elementor'       => $root . 'elementor/functions.php',
		'lazy-load'       => $root . 'lazy-load/functions.php',
		'events-calendar' => $root . 'events-calendar/functions.php',
	);

	// Only load admin fragments if is_admin() is true.
	if ( is_admin() ) {
		$admin = [
			'options'    => $root . 'options/functions.php',
			'elementor'  => $root . 'elementor/functions-admin.php',
			'api'        => $root . 'api/ajax.php',
			'onboarding' => $root . 'onboarding/functions.php',
		];
	} else {
		$admin = [];
	}

	// Set dependencies.
	$dependencies = array(
		'html'         => array(
			'filters',
		),
		'fields'       => array(
			'actions',
			'html',
		),
		'options'      => 'fields',
		'post-meta'    => 'fields',
		'layout'       => 'fields',
	);

	foreach ( (array) $components as $component ) {

		// Stop here if the component is already loaded or doesn't exist.
		if ( in_array( $component, $loaded, true ) || ( ! isset( $common[ $component ] ) && ! isset( $admin[ $component ] ) ) ) {
			continue;
		}

		// Cache loaded component before calling dependencies.
		$loaded[] = $component;

		// Load dependencies.
		if ( array_key_exists( $component, $dependencies ) ) {
			jupiterx_load_api_components( $dependencies[ $component ] );
		}

		$_components = array();

		// Add common components.
		if ( isset( $common[ $component ] ) ) {
			$_components = (array) $common[ $component ];
		}

		// Add admin components.
		if ( isset( $admin[ $component ] ) ) {
			$_components = array_merge( (array) $_components, (array) $admin[ $component ] );
		}

		// Load components.
		foreach ( $_components as $component_path ) {
			require_once $component_path;
		}

		/**
		 * Fires when an API component is loaded.
		 *
		 * The dynamic portion of the hook name, $component, refers to the name of the API component loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_loaded_api_component_' . $component );
	}

	return true;
}

/**
 * Register API component support.
 *
 * @since 1.0.0
 *
 * @param string $feature The feature to register.
 *
 * @return bool Will always return true.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_add_api_component_support( $feature ) {
	global $_jupiterx_api_components_support;

	$args = func_get_args();

	if ( 1 === func_num_args() ) {
		$args = true;
	} else {
		$args = array_slice( $args, 1 );
	}

	$_jupiterx_api_components_support[ $feature ] = $args;

	return true;
}

/**
 * Gets the API component support argument(s).
 *
 * @since 1.0.0
 *
 * @param string $feature The feature to check.
 *
 * @return mixed The argument(s) passed.
 */
function jupiterx_get_component_support( $feature ) {
	global $_jupiterx_api_components_support;

	if ( ! isset( $_jupiterx_api_components_support[ $feature ] ) ) {
		return false;
	}

	return $_jupiterx_api_components_support[ $feature ];
}

/**
 * Remove API component support.
 *
 * @since 1.0.0
 *
 * @param string $feature The feature to remove.
 *
 * @return bool Will always return true.
 */
function jupiterx_remove_api_component_support( $feature ) {
	global $_jupiterx_api_components_support;
	unset( $_jupiterx_api_components_support[ $feature ] );
	return true;
}

/**
 * Initialize API components support global.
 *
 * @ignore
 * @access private
 */
global $_jupiterx_api_components_support;

if ( ! isset( $_jupiterx_api_components_support ) ) {
	$_jupiterx_api_components_support = array();
}
