<?php
/**
 * WP helper functions.
 *
 * @since 1.6.0
 * @package Masteriyo\Helper
 */

if ( ! ( function_exists( 'add_action' ) && function_exists( 'add_filter' ) ) ) {
	return;
}

/**
 * Registers a script according to `wp_register_script`. Honors this request by
 * reassigning internal dependency properties of any script handle already
 * registered by that name. It does not deregister the original script, to
 * avoid losing inline scripts which may have been attached.
 *
 * @since 1.6.0
 *
 * @see https://github.dev/WordPress/masteriyo/blob/trunk/lib/client-assets.php
 *
 * @param \WP_Scripts       $scripts   WP_Scripts instance.
 * @param string           $handle    Name of the script. Should be unique.
 * @param string           $src       Full URL of the script, or path of the script relative to the WordPress root directory.
 * @param array            $deps      Optional. An array of registered script handles this script depends on. Default empty array.
 * @param string|bool|null $ver       Optional. String specifying script version number, if it has one, which is added to the URL
 *                                    as a query string for cache busting purposes. If version is set to false, a version
 *                                    number is automatically added equal to current installed WordPress version.
 *                                    If set to null, no version is added.
 * @param bool             $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>.
 *                                    Default 'false'.
 */
function masteriyo_override_script( $scripts, $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {
	/*
	 * Force `wp-i18n` script to be registered in the <head> as a
	 * temporary workaround for https://meta.trac.wordpress.org/ticket/6195.
	 */
	$in_footer = 'wp-i18n' === $handle ? false : $in_footer;

	$script = $scripts->query( $handle, 'registered' );
	if ( $script ) {
		/*
		 * In many ways, this is a reimplementation of `wp_register_script` but
		 * bypassing consideration of whether a script by the given handle had
		 * already been registered.
		 */

		// See: `_WP_Dependency::__construct` .
		$script->src  = $src;
		$script->deps = $deps;
		$script->ver  = $ver;
		$script->args = $in_footer ? 1 : null;
	} else {
		$scripts->add( $handle, $src, $deps, $ver, ( $in_footer ? 1 : null ) );
	}

	if ( in_array( 'wp-i18n', $deps, true ) ) {
		$scripts->set_translations( $handle );
	}

	/*
	 * Wp-editor module is exposed as window.wp.editor.
	 * Problem: there is quite some code expecting window.wp.oldEditor object available under window.wp.editor.
	 * Solution: fuse the two objects together to maintain backward compatibility.
	 * For more context, see https://github.com/WordPress/masteriyo/issues/33203
	 */
	if ( 'wp-editor' === $handle ) {
		$scripts->add_inline_script(
			'wp-editor',
			'Object.assign( window.wp.editor, window.wp.oldEditor );',
			'after'
		);
	}
}

/**
 * Registers vendor JavaScript files to be used as dependencies of the editor
 * and plugins.
 *
 * This function is called from a script during the plugin build process, so it
 * should not call any WordPress PHP functions.
 *
 * @since 1.6.0
 *
 * @param \WP_Scripts $scripts WP_Scripts instance.
 */
function masteriyo_register_vendor_scripts( $scripts ) {
	$extension = SCRIPT_DEBUG ? '.js' : '.min.js';

	// Load custom react and rect-dom scripts for only WP version less than 6.2
	if ( version_compare( $GLOBALS['wp_version'], '6.2', '>=' ) ) {
		return;
	}

	masteriyo_override_script(
		$scripts,
		'react',
		plugins_url( 'libs/react' . $extension, MASTERIYO_PLUGIN_FILE ),
		// See https://github.com/pmmmwh/react-refresh-webpack-plugin/blob/main/docs/TROUBLESHOOTING.md#externalising-react.
		SCRIPT_DEBUG ? array( 'wp-react-refresh-entry', 'wp-polyfill' ) : array( 'wp-polyfill' ),
		'18'
	);
	masteriyo_override_script(
		$scripts,
		'react-dom',
		plugins_url( 'libs/react-dom' . $extension, MASTERIYO_PLUGIN_FILE ),
		array( 'react' ),
		'18'
	);
}
add_action( 'wp_default_scripts', 'masteriyo_register_vendor_scripts' );
