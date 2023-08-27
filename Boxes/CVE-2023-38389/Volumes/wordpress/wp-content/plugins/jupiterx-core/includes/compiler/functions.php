<?php
/**
 * Compile and cache CSS, LESS and JS files.
 *
 * The Jupiter Compiler compiles multiple internal or external CSS, LESS and JS files on a
 * per page basis. LESS content will automatically be converted to CSS.
 *
 * Internal file changes are automatically detected if development mode is enabled.
 * Third party enqueued styles and scripts can be compiled and cached into a single file.
 *
 * @package API\Compiler
 */

/**
 * Compile CSS fragments and enqueue compiled file.
 *
 * This function should be used in a similar fashion to
 * {@link http://codex.wordpress.org/Function_Reference/wp_enqueue_script wp_enqueue_script()}.
 *
 * Fragments can be added to the compiler using {@see jupiterx_compiler_add_fragment()}.
 *
 * @since 1.0.0
 *
 * @param string       $id          A unique string used as a reference. Similar to the WordPress scripts
 *                                  $handle argument.
 * @param string|array $fragments   File(s) absolute path. Internal or external file(s) url accepted but may increase
 *                                  compiling time.
 * @param array        $args        {
 *                                  Optional. Array of arguments used by the compiler.
 *
 * @type array         $depedencies An array of registered handles this script depends on. Default false.
 * }
 *
 * @return object      Compiler object.
 */
function jupiterx_compile_css_fragments( $id, $fragments, $args = array() ) {

	if ( empty( $fragments ) ) {
		return false;
	}

	$params = array(
		'id'        => $id,
		'type'      => 'style',
		'format'    => 'css',
		'fragments' => (array) $fragments,
	);

	$compiler = new _JupiterX_Compiler( array_merge( $params, $args ) );
	$compiler->run_compiler();

	return $compiler;
}

/**
 * Compile LESS fragments, convert to CSS and enqueue compiled file.
 *
 * This function should be used in a similar fashion to
 * {@link http://codex.wordpress.org/Function_Reference/wp_enqueue_script wp_enqueue_script()}.
 *
 * Fragments can be added to the compiler using {@see jupiterx_compiler_add_fragment()}.
 *
 * @since 1.0.0
 *
 * @param string       $id          The compiler ID. Similar to the WordPress scripts $handle argument.
 * @param string|array $fragments   File(s) absolute path. Internal or external file(s) url accepted but may increase
 *                                  compiling time.
 * @param array        $args        {
 *                                  Optional. Array of arguments used by the compiler.
 *
 * @type array         $depedencies An array of registered handles this script depends on. Default false.
 * }
 *
 * @return object      Compiler object.
 */
function jupiterx_compile_less_fragments( $id, $fragments, $args = array() ) {

	if ( empty( $fragments ) ) {
		return false;
	}

	$params = array(
		'id'        => $id,
		'type'      => 'style',
		'format'    => 'less',
		'fragments' => (array) $fragments,
	);

	$compiler = new _JupiterX_Compiler( array_merge( $params, $args ) );
	$compiler->run_compiler();

	return $compiler;
}

/**
 * Compile LESS fragments, convert to CSS and enqueue compiled file.
 *
 * This function should be used in a similar fashion to
 * {@link http://codex.wordpress.org/Function_Reference/wp_enqueue_script wp_enqueue_script()}.
 *
 * Fragments can be added to the compiler using {@see jupiterx_compiler_add_fragment()}.
 *
 * @since 1.0.0
 *
 * @param string       $id          The compiler ID. Similar to the WordPress scripts $handle argument.
 * @param string|array $fragments   File(s) absolute path. Internal or external file(s) url accepted but may increase
 *                                  compiling time.
 * @param array        $args        {
 *                                  Optional. Array of arguments used by the compiler.
 *
 * @type array         $depedencies An array of registered handles this script depends on. Default false.
 * }
 *
 * @return object      Compiler object.
 */
function jupiterx_compile_scss_fragments( $id, $fragments, $args = array() ) {

	if ( empty( $fragments ) ) {
		return false;
	}

	$params = array(
		'id'        => $id,
		'type'      => 'style',
		'format'    => 'scss',
		'fragments' => (array) $fragments,
	);

	$compiler = new _JupiterX_Compiler( array_merge( $params, $args ) );
	$compiler->run_compiler();

	return $compiler;
}

/**
 * Compile JS fragments and enqueue compiled file.
 *
 * This function should be used in a similar fashion to
 * {@link http://codex.wordpress.org/Function_Reference/wp_enqueue_script wp_enqueue_script()}.
 *
 * Fragments can be added to the compiler using {@see jupiterx_compiler_add_fragment()}.
 *
 * @since 1.0.0
 *
 * @param string       $id          The compiler ID. Similar to the WordPress scripts $handle argument.
 * @param string|array $fragments   File(s) absolute path. Internal or external file(s) URL accepted but may increase
 *                                  compiling time.
 * @param array        $args        {
 *                                  Optional. Array of arguments used by the compiler.
 *
 * @type array         $depedencies An array of registered handles this script depends on. Default false.
 * @type bool          $in_footer   Whether to enqueue the script before </head> or before </body>. Default false.
 * @type bool          $minify_js   Whether the JavaScript should be minified or not. Be aware that minifying
 *                                      the JavaScript can considerably slow down the process of compiling files.
 *                                      Default false.
 * }
 *
 * @return object      Compiler object.
 */
function jupiterx_compile_js_fragments( $id, $fragments, $args = array() ) {

	if ( empty( $fragments ) ) {
		return false;
	}

	$params = array(
		'id'        => $id,
		'type'      => 'script',
		'format'    => 'js',
		'fragments' => (array) $fragments,
	);

	$compiler = new _JupiterX_Compiler( array_merge( $params, $args ) );
	$compiler->run_compiler();

	return $compiler;
}

/**
 * Add CSS, LESS or JS fragments to a compiler.
 *
 * This function should be used in a similar fashion to
 * {@link http://codex.wordpress.org/Function_Reference/wp_enqueue_script wp_enqueue_script()}.
 *
 * @since 1.0.0
 *
 * @param string       $id        The compiler ID. Similar to the WordPress scripts $handle argument.
 * @param string|array $fragments File(s) absolute path. Internal or external file(s) url accepted but may increase
 *                                compiling time.
 * @param string       $format    Compiler format the fragments should be added to. Accepts 'css',
 *                                'less' or 'js'.
 *
 * @return void|bool
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_compiler_add_fragment( $id, $fragments, $format ) {

	if ( empty( $fragments ) ) {
		return false;
	}

	global $_jupiterx_compiler_added_fragments;

	foreach ( (array) $fragments as $key => $fragment ) {

		// Stop here if the format isn't valid.
		if ( ! isset( $_jupiterx_compiler_added_fragments[ $format ] ) ) {
			continue;
		}

		// Register a new compiler ID if it doesn't exist and add fragment.
		if ( ! isset( $_jupiterx_compiler_added_fragments[ $format ][ $id ] ) ) {
			$_jupiterx_compiler_added_fragments[ $format ][ $id ] = array( $fragment );
		} else { // Add fragment to existing compiler.
			$_jupiterx_compiler_added_fragments[ $format ][ $id ][] = $fragment;
		}
	}
}

/**
 * Flush cached compiler files.
 *
 * Each compiler has its own folder which contains the cached CSS and JS files. The file format
 * of the cached file can be specified if needed.
 *
 * @since 1.0.0
 *
 * @param string      $id          The compiler ID. Similar to the WordPress scripts $handle argument.
 * @param string|bool $file_format Optional. Define which file format(s) should be removed. Both CSS and JS
 *                                 files will be removed if set to false. Accepts 'false', 'css' or 'js'.
 * @param bool        $admin       Optional. Whether it is an admin compiler or not.
 *
 * @return void|bool
 */
function jupiterx_flush_compiler( $id, $file_format = false, $admin = false ) {
	static $jupiterx_flushed = false;

	$cache_dir = jupiterx_get_compiler_dir( $admin );

	// Always flush Jupiter' global cache.
	if ( ! $jupiterx_flushed && ! $id ) {
		$jupiterx_flushed = true;

		jupiterx_flush_compiler( 'jupiterx', $file_format, $admin );
	}

	$dir = trailingslashit( $cache_dir ) . $id;

	// Stop here if directory doesn't exist.
	if ( ! is_dir( $dir ) ) {
		return;
	}

	// Remove only the specified file format.
	if ( $file_format ) {
		$items = scandir( $dir );
		unset( $items[0], $items[1] );

		foreach ( $items as $item ) {
			if ( false !== stripos( $item, '.' . $file_format ) ) {
				@unlink( trailingslashit( $dir ) . $item ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case.
			}
		}

		return;
	}

	// Remove all file formats.
	jupiterx_remove_dir( $dir );

	jupiterx_flush_cache_plugins();
}

/**
 * Flush admin cached compiler files.
 *
 * This function is a shortcut of {@see jupiterx_flush_compiler()}.
 *
 * @since 1.0.0
 *
 * @param string      $id          The compiler ID. Similar to the WordPress scripts $handle argument.
 * @param string|bool $file_format Optional. Define which file formats should be removed. Both CSS and JS
 *                                 files will be removed if set to false. Accepts 'false', 'css' or 'js'.
 *
 * @return void
 */
function jupiterx_flush_admin_compiler( $id, $file_format = false ) {
	jupiterx_flush_compiler( $id, $file_format, true );
}

/**
 * Flush cache plugins
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_flush_cache_plugins() {

	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	}

	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}

	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}

	if ( class_exists( 'WpFastestCache' ) ) {
		$GLOBALS['wp_fastest_cache']->deleteCache();
	}

	if ( class_exists( 'autoptimizeCache' ) ) {
		autoptimizeCache::clearall();
	}
}

/**
 * Get absolute path to the Jupiter' compiler directory.
 *
 * @since 1.0.0
 *
 * @param bool $is_admin Optional. When true, gets the admin compiler directory. Default is false.
 *
 * @return string
 */
function jupiterx_get_compiler_dir( $is_admin = false ) {
	$wp_upload_dir = wp_upload_dir();
	$suffix        = $is_admin ? 'jupiterx/admin-compiler/' : 'jupiterx/compiler/';

	/**
	 * Deprecated. Filter the Jupiter compiler directory.
	 *
	 * This filter is deprecated for security and compatibility purposes.
	 *
	 * @since      1.0.0
	 * @deprecated 1.3.0
	 */
	apply_filters( 'jupiterx_compiler_dir', false, $is_admin );

	return wp_normalize_path( trailingslashit( $wp_upload_dir['basedir'] ) . $suffix );
}

/**
 * Get absolute URL to the Jupiter' compiler directory.
 *
 * @since 1.0.0
 *
 * @param bool $is_admin Optional. When true, gets the admin compiler directory. Default is false.
 *
 * @return string
 */
function jupiterx_get_compiler_url( $is_admin = false ) {
	$wp_upload_dir = wp_upload_dir();
	$suffix        = $is_admin ? 'jupiterx/admin-compiler/' : 'jupiterx/compiler/';

	return trailingslashit( $wp_upload_dir['baseurl'] ) . $suffix;
}

/**
 * Handle child theme script backward compatibility.
 *
 * @since  1.15.1
 *
 * @param string       $id        The compiler ID.
 * @param string|array $fragments File(s) absolute path.
 * @param string       $format    Compiler format.
 *
 * @return void
 */
function jupiterx_handle_child_compatibility( $id, $fragments, $format ) {

	if ( 'jupiterx' !== $id || 'js' !== $format ) {
		return;
	}

	jupiterx_compile_js_fragments(
		'jupiterx-child',
		$fragments,
		[
			'dependencies' => [ 'jquery' ],
			'in_footer'   => true,
		]
	);
}

/**
 * Check if development mode is enabled.
 *
 * Takes legacy constant into consideration.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return bool
 */
function _jupiterx_is_compiler_dev_mode() {

	if ( defined( 'JUPITERX_COMPILER_DEV_MODE' ) ) {
		return JUPITERX_COMPILER_DEV_MODE;
	}

	return jupiterx_get_option( 'dev_mode', false );
}

/**
 * Get cache busting method.
 *
 * @since  1.0.0
 * @access private
 *
 * @return bool
 */
function _jupiterx_get_cache_busting() {
	return jupiterx_get_option( 'cache_busting', true );
}

/**
 * Initialize added fragments global.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 */
global $_jupiterx_compiler_added_fragments;

if ( ! isset( $_jupiterx_compiler_added_fragments ) ) {
	$_jupiterx_compiler_added_fragments = array(
		'css'  => array(),
		'less' => array(),
		'scss' => array(),
		'js'   => array(),
	);
}
