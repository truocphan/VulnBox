<?php
/**
 *
 * Load the API components.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\API
 */

// Stop here if the API was already loaded.
if ( defined( 'JUPITERX_API' ) ) {
	return;
}

// Declare Jupiter API.
define( 'JUPITERX_API', true );

// Mode.
if ( ! defined( 'SCRIPT_DEBUG' ) ) {
	define( 'SCRIPT_DEBUG', false ); // @phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- Valid use case as we need it defined.
}

// Assets.
define( 'JUPITERX_MIN_CSS', SCRIPT_DEBUG ? '' : '.min' );
define( 'JUPITERX_MIN_JS', SCRIPT_DEBUG ? '' : '.min' );
define( 'JUPITERX_RTL', is_rtl() ? '-rtl' : '' );

// Path.
if ( ! defined( 'JUPITERX_API_PATH' ) ) {
	define( 'JUPITERX_API_PATH', wp_normalize_path( trailingslashit( dirname( __FILE__ ) ) ) );
}

define( 'JUPITERX_API_ADMIN_PATH', JUPITERX_API_PATH . 'admin/' );

// Load dependencies here, as these are used further down.
require_once JUPITERX_API_PATH . 'utilities/functions.php';
require_once JUPITERX_API_PATH . 'components.php';

// Url.
if ( ! defined( 'JUPITERX_API_URL' ) ) {
	define( 'JUPITERX_API_URL', jupiterx_path_to_url( JUPITERX_API_PATH ) );
}

// Backwards compatibility constants.
define( 'JUPITERX_API_COMPONENTS_PATH', JUPITERX_API_PATH );
define( 'JUPITERX_API_COMPONENTS_ADMIN_PATH', JUPITERX_API_PATH . 'admin/' );
define( 'JUPITERX_API_COMPONENTS_URL', JUPITERX_API_URL );
