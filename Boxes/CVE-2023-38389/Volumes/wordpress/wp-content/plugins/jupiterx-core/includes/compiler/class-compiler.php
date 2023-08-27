<?php
/**
 * This class compiles and minifies CSS, LESS and JS.
 *
 * @package JupiterX\Framework\API\Compiler
 *
 * @since   1.0.0
 */

/**
 * Compiles and minifies CSS, LESS and JS.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package JupiterX\Framework\API\Compiler
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
final class _JupiterX_Compiler {

	/**
	 * Compiler's runtime configuration parameters.
	 *
	 * @var array
	 */
	private $config;

	/**
	 * Cache dir.
	 *
	 * @var string
	 */
	private $dir;

	/**
	 * Cache url.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * The fragment currently being processed.
	 *
	 * @var string
	 */
	private $current_fragment;

	/**
	 * The compiled content.
	 *
	 * @var string
	 */
	private $compiled_content;

	/**
	 * Compiled content's filename.
	 *
	 * @var string
	 */
	private $filename;

	/**
	 * Create a new Compiler.
	 *
	 * @since 1.0.0
	 *
	 * @param array $config Runtime configuration parameters for the Compiler.
	 */
	public function __construct( array $config ) {
		$this->config = $this->init_config( $config );
		$this->dir    = jupiterx_get_compiler_dir( is_admin() ) . $this->config['id'];
		$this->url    = jupiterx_get_compiler_url( is_admin() ) . $this->config['id'];
	}

	/**
	 * Run the compiler.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run_compiler() {
		// Modify the WP Filesystem method.
		add_filter( 'filesystem_method', array( $this, 'modify_filesystem_method' ) );

		$this->set_fragments();
		$this->set_filename();

		if ( ! $this->cache_file_exist() ) {
			$this->filesystem();
			$this->maybe_make_dir();
			$this->combine_fragments();
			$this->cache_file();
		}

		if ( $this->config['enqueue'] ) {
			$this->enqueue_file();
		}

		// Keep it safe and reset the WP Filesystem method.
		remove_filter( 'filesystem_method', array( $this, 'modify_filesystem_method' ) );
	}

	/**
	 * Callback to set the WP Filesystem method.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function modify_filesystem_method() {
		return 'direct';
	}

	/**
	 * Initialise the WP Filesystem.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|void
	 */
	public function filesystem() {

		// If the WP_Filesystem is not already loaded, load it.
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		// If the WP_Filesystem is not initialized or is not set to WP_Filesystem_Direct, then initialize it.
		if ( $this->is_wp_filesystem_direct() ) {
			return true;
		}

		// Initialize the filesystem.
		$response = WP_Filesystem();

		// If the filesystem did not initialize, then generate a report and exit.
		if ( true !== $response || ! $this->is_wp_filesystem_direct() ) {
			return $this->kill();
		}

		return true;
	}

	/**
	 * Check if the filesystem is set to "direct".
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_wp_filesystem_direct() {
		return isset( $GLOBALS['wp_filesystem'] ) && is_a( $GLOBALS['wp_filesystem'], 'WP_Filesystem_Direct' );
	}

	/**
	 * Make directory.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function maybe_make_dir() {

		if ( ! @is_dir( $this->dir ) ) {  // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- This is a valid use case.
			wp_mkdir_p( $this->dir );
		}

		return is_writable( $this->dir );
	}

	/**
	 * Set class fragments.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_fragments() {
		global $_jupiterx_compiler_added_fragments;

		$added_fragments = jupiterx_get( $this->config['id'], $_jupiterx_compiler_added_fragments[ $this->config['format'] ] );

		if ( $added_fragments ) {
			$this->config['fragments'] = array_merge( $this->config['fragments'], $added_fragments );
		}

		/**
		 * Filter the compiler fragment files.
		 *
		 * The dynamic portion of the hook name, $this->config['id'], refers to the compiler id used as a reference.
		 *
		 * @since 1.0.0
		 *
		 * @param array $fragments An array of fragment files.
		 */
		$this->config['fragments'] = apply_filters( 'jupiterx_compiler_fragments_' . $this->config['id'], $this->config['fragments'] );
	}

	/**
	 * Set the filename for the compiled asset.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_filename() {
		$hash = $this->hash( $this->config );

		if ( empty( _jupiterx_get_cache_busting() ) ) {
			$this->config['version'] = $hash;

			$hash = 'style';

			if ( 'script' === $this->config['type'] ) {
				$hash = 'script';
			}
		}

		$this->filename = $hash . '.' . $this->get_extension();
	}

	/**
	 * Hash the given array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $given_array Given array to be hashed.
	 *
	 * @return string
	 */
	public function hash( array $given_array ) {
		return substr( md5( @serialize( $given_array ) ), 0, 7 ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize -- Valid use case.
	}

	/**
	 * Checks if the file exists on the filesystem, meaning it's been cached.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function cache_file_exist() {
		$filename = $this->get_filename();

		if ( _jupiterx_is_compiler_dev_mode() || is_customize_preview() ) {
			return false;
		}

		if ( empty( $filename ) ) {
			return false;
		}

		return file_exists( $filename );
	}

	/**
	 * Get the absolute path of the cached and compiled file.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_filename() {
		if ( isset( $this->filename ) ) {
			return $this->dir . '/' . $this->filename;
		}

		return '';
	}

	/**
	 * Create cached file.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function cache_file() {
		$filename = $this->get_filename();

		if ( empty( $filename ) ) {
			return false;
		}

		// It is safe to access the filesystem because we made sure it was set.
		return $GLOBALS['wp_filesystem']->put_contents( $filename, $this->compiled_content, FS_CHMOD_FILE );
	}

	/**
	 * Enqueue cached file.
	 *
	 * @since 1.0.0
	 *
	 * @return void|bool
	 */
	private function enqueue_file() {

		// Enqueue CSS file.
		if ( 'style' === $this->config['type'] ) {
			return wp_enqueue_style(
				$this->config['id'],
				$this->get_url(),
				$this->config['dependencies'],
				$this->config['version']
			);
		}

		// Enqueue JS file.
		if ( 'script' === $this->config['type'] ) {
			return wp_enqueue_script(
				$this->config['id'],
				$this->get_url(),
				$this->config['dependencies'],
				$this->config['version'],
				$this->config['in_footer']
			);
		}

		return false;
	}

	/**
	 * Get cached file url.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_url() {
		$url = trailingslashit( $this->url ) . $this->filename;

		if ( is_ssl() ) {
			$url = str_replace( 'http://', 'https://', $url );
		}

		return $url;
	}

	/**
	 * Get the file extension from the configured "type".
	 *
	 * @since 1.0.0
	 *
	 * @return string|null
	 */
	public function get_extension() {

		if ( 'style' === $this->config['type'] ) {
			return 'css';
		}

		if ( 'script' === $this->config['type'] ) {
			return 'js';
		}
	}

	/**
	 * Combine content of the fragments.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function combine_fragments() {
		$content = '';

		// Loop through fragments.
		foreach ( $this->config['fragments'] as $fragment ) {

			// Stop here if the fragment is empty.
			if ( empty( $fragment ) ) {
				continue;
			}

			$fragment_content = $this->get_content( $fragment );

			// Stop here if no content or content is an html page.
			if ( ! $fragment_content || preg_match( '#^\s*\<#', $fragment_content ) ) {
				continue;
			}

			// Continue processing style.
			if ( 'style' === $this->config['type'] ) {
				$fragment_content = $this->replace_css_url( $fragment_content );
				$fragment_content = $this->add_content_media_query( $fragment_content );
			}

			// If there's content, start a new line.
			if ( $content ) {
				$content .= "\n\n";
			}

			$content .= $fragment_content;
		}

		$this->compiled_content = ! empty( $content ) ? $this->format_content( $content ) : '';
	}

	/**
	 * Get the fragment's content.
	 *
	 * @since 1.0.0
	 *
	 * @param string|callable $fragment The given fragment from which to get the content.
	 *
	 * @return bool|string
	 */
	private function get_content( $fragment ) {
		// Set the current fragment used by other functions.
		$this->current_fragment = $fragment;

		// If the fragment is callable, call it to get the content.
		if ( $this->is_function( $fragment ) ) {
			return $this->get_function_content();
		}

		$content = $this->get_internal_content();

		// Try remote content if the internal content returned false.
		if ( empty( $content ) ) {
			$content = $this->get_remote_content();
		}

		if (
			empty( $content ) &&
			! is_wp_error( wp_remote_get( $fragment ) ) &&
			filter_var( $fragment, FILTER_VALIDATE_URL )
		) {
			return '';
		}

		// If the fragment is string.
		if ( empty( $content ) && is_string( $fragment ) ) {
			return $fragment;
		}

		return $content;
	}

	/**
	 * Get internal file content.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool
	 */
	public function get_internal_content() {
		$fragment = $this->current_fragment;

		if ( 'string' === $this->config['fragments_type'] ) {
			return $fragment;
		}

		if ( ! file_exists( $fragment ) ) {

			// Replace URL with path.
			$fragment = jupiterx_url_to_path( $fragment );

			// Stop here if it isn't a valid file.
			if ( ! file_exists( $fragment ) || 0 === @filesize( $fragment ) ) { // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged  -- Valid use case.
				return false;
			}
		}

		// It is safe to access the filesystem because we made sure it was set.
		return $GLOBALS['wp_filesystem']->get_contents( $fragment );
	}

	/**
	 * Get external file content.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool
	 */
	public function get_remote_content() {
		$fragment = $this->current_fragment;

		if ( empty( $fragment ) ) {
			return false;
		}

		// For a relative URL, add http: to it.
		if ( substr( $fragment, 0, 2 ) === '//' ) {
			$fragment = 'http:' . $fragment;
		} elseif ( substr( $fragment, 0, 1 ) === '/' ) { // Add domain if it is local but could not be fetched as a file.
			$fragment = site_url( $fragment );
		}

		$request = wp_remote_get( $fragment );

		if ( is_wp_error( $request ) ) {
			return '';
		}

		// If no content was received and the URL is not https, then convert the URL to SSL and retry.
		if (
			( ! isset( $request['body'] ) || 200 !== $request['response']['code'] ) &&
			( substr( $fragment, 0, 8 ) !== 'https://' )
		) {
			$fragment = str_replace( 'http://', 'https://', $fragment );
			$request  = wp_remote_get( $fragment );

			if ( is_wp_error( $request ) ) {
				return '';
			}
		}

		if ( ( ! isset( $request['body'] ) || 200 !== $request['response']['code'] ) ) {
			return false;
		}

		return wp_remote_retrieve_body( $request );
	}

	/**
	 * Get function content.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool
	 */
	public function get_function_content() {

		if ( ! is_callable( $this->current_fragment ) ) {
			return false;
		}

		return call_user_func( $this->current_fragment );
	}

	/**
	 * Wrap content in query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Given content to process.
	 *
	 * @return string
	 */
	public function add_content_media_query( $content ) {

		// Ignore if the fragment is a function.
		if ( $this->is_function( $this->current_fragment ) ) {
			return $content;
		}

		$query = wp_parse_url( $this->current_fragment, PHP_URL_QUERY );

		// Bail out if there are no query args or no media query.
		if ( empty( $query ) || false === stripos( $query, 'jupiterx_compiler_media_query' ) ) {
			return $content;
		}

		// Wrap the content in the query.
		return sprintf(
			"@media %s {\n%s\n}\n",
			jupiterx_get( 'jupiterx_compiler_media_query', wp_parse_args( $query ) ),
			$content
		);
	}

	/**
	 * Format CSS, LESS and JS content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Given content to process.
	 *
	 * @return string
	 */
	public function format_content( $content ) {

		if ( 'style' === $this->config['type'] ) {

			if ( 'less' === $this->config['format'] ) {

				if ( ! class_exists( 'JupiterX_Lessc' ) ) {
					jupiterx_core()->load_files( [ 'compiler/vendors/lessc' ] );
				}

				$parser = new JupiterX_Lessc();

				$parser = $this->register_less_functions( $parser );

				$parser->setVariables( apply_filters( 'jupiterx_compiler_less_variables', $this->config['variables'] ) );

				$content = $parser->compile( $content );

				$content = $this->clean_style( $content );
			}

			if ( is_rtl() ) {
				if ( ! class_exists( 'CSSJanus' ) ) {
					jupiterx_core()->load_files( [ 'compiler/vendors/CSSJanus' ] );
				}

				$content = CSSJanus::transform( $content );
			}

			if ( ! _jupiterx_is_compiler_dev_mode() ) {
				$content = $this->minify_style_2( $content );
			}

			return $content;
		}

		if ( 'script' === $this->config['type'] && ! _jupiterx_is_compiler_dev_mode() && $this->config['minify_js'] ) {

			if ( ! class_exists( 'JSMin' ) ) {
				jupiterx_core()->load_files( [ 'compiler/vendors/js-minifier' ] );
			}

			$js_min = new JSMin( $content );
			return $js_min->min();
		}

		return $content;
	}

	/**
	 * Replace CSS URL shortcuts with a valid URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Given content to process.
	 *
	 * @return string
	 */
	public function replace_css_url( $content ) {
		return preg_replace_callback(
			'#url\s*\(\s*[\'"]*?([^\'"\)]+)[\'"]*\s*\)#i',
			array( $this, 'replace_css_url_callback' ),
			$content
		);
	}

	/**
	 * Convert any CSS URL relative paths to absolute URLs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $matches Matches to process, where 0 is the CSS' URL() and 1 is the URI.
	 *
	 * @return string
	 */
	public function replace_css_url_callback( $matches ) {

		// If the URI is absolute, bail out and return the CSS.
		if ( _jupiterx_is_uri( $matches[1] ) ) {
			return $matches[0];
		}

		$base = $this->current_fragment;

		// Separate the placeholders and path.
		$paths = explode( '../', $matches[1] );

		/**
		 * Walk backwards through each of the the fragment's directories, one-by-one. The `foreach` loop
		 * provides us with a performant way to walk the fragment back to its base path based upon the
		 * number of placeholders.
		 */
		foreach ( $paths as $path ) {
			$base = dirname( $base );
		}

		// Make sure it is a valid base.
		if ( '.' === $base ) {
			$base = '';
		}

		// Rebuild the URL and make sure it is valid using the jupiterx_path_to_url function.
		$url = jupiterx_path_to_url( trailingslashit( $base ) . ltrim( end( $paths ), '/\\' ) );

		// Return the rebuilt path converted to an URL.
		return 'url("' . $url . '")';
	}

	/**
	 * Register LESS_PHP functions.
	 *
	 * @since 1.0.0
	 *
	 * @param object $parser The LESS parser.
	 *
	 * @todo Refactoring is required.
	 *
	 * @return object
	 */
	private function register_less_functions( $parser ) {
		$parser->registerFunction( 'jupiterx_value', function( $arg ) {
			$output = '';

			if ( isset( $arg[2][1][2][0] ) ) {
				$output = $arg[2][1][2][0]; // Default.
			}

			if ( ! empty( $arg[2][0][2][1][1] ) ) {
				return $arg[2][0][2][1][1]; // E.g. ~"@{@{var}-width}".
			}

			if ( ! empty( $arg[2][0][1] ) ) {
				$value = $arg[2][0][1]; // E.g. @text-size.
				$unit  = empty( $arg[2][0][2] ) ? '' : $arg[2][0][2]; // E.g. @text-size unit.

				return $value . $unit;
			}

			return $output;
		} );

		$parser->registerFunction( 'jupiterx_value_pattern', function( $arg ) {
			if ( 0 === strlen( $arg[2][0][1] ) ) {
				return '';
			}

			list($type, $value, $unit) = $arg[2][0];

			$format = $arg[2][1][2][0];

			// When value is 0px, parser automatically remove px (but not %) from it.
			if ( 0 == $arg[2][0][1] ) { // @codingStandardsIgnoreLine
				$unit = '%';
			}

			return sprintf( $format, $value . $unit );
		} );

		$parser->registerFunction( 'jupiterx_replace', function( $args ) {
			list( $string, $search, $replace ) = $args[2];

			// Arrange if string is from a variable use the true condition. e.g. @{var-name}.
			$string  = isset( $string[2][1][1] ) ? $string[2][1][1] : $string[2][0];
			$search  = $search[2][0];
			$replace = $replace[2][0];

			return str_replace( $search, $replace, $string );
		} );

		return $parser;
	}

	/**
	 * Initialize the configuration.
	 *
	 * @since 1.0.0
	 *
	 * @param array $config Runtime configuration parameters for the Compiler.
	 *
	 * @return array
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	private function init_config( array $config ) {
		// Fix dependencies, if "depedencies" is specified.
		if ( isset( $config['depedencies'] ) ) {
			$config['dependencies'] = $config['depedencies'];
			unset( $config['depedencies'] );
		}

		$defaults = [
			'id'             => false,
			'type'           => false,
			'format'         => false,
			'fragments'      => [],
			'fragments_type' => 'path', // url, path, string.
			'variables'      => [],
			'dependencies'   => false,
			'in_footer'      => false,
			'minify_js'      => true,
			'version'        => JUPITERX_VERSION,
			'enqueue'        => true,
		];

		if ( is_customize_preview() ) {
			$defaults['uniqid'] = uniqid();
		} else {
			$defaults['theme_mods'] = get_theme_mods();
		}

		return array_merge( $defaults, $config );
	}

	/**
	 * Get the fragments' modification times.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 */
	private function get_fragments_filemtime() {
		$fragments_filemtime = array();

		foreach ( $this->config['fragments'] as $index => $fragment ) {

			// Skip this one if the fragment is a function.
			if ( $this->is_function( $fragment ) ) {
				if ( ! is_callable( $fragment ) ) {
					continue;
				}

				$fragments_filemtime[ $index ] = $this->hash( [ call_user_func( $fragment ) ] );

			}

			if ( file_exists( $fragment ) ) {
				$fragments_filemtime[ $index ] = @filemtime( $fragment ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case.
			}
		}

		return $fragments_filemtime;
	}

	/**
	 * Get the new hash for the given fragments' modification times.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hash                The original hash to modify.
	 * @param array  $fragments_filemtime Array of fragments' modification times.
	 *
	 * @return string
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 */
	private function get_new_hash( $hash, array $fragments_filemtime ) {

		if ( empty( $fragments_filemtime ) ) {
			return $hash;
		}

		// Set filemtime hash.
		$_hash = $this->hash( $fragments_filemtime );

		$this->remove_modified_files( $hash, $_hash );

		// Set the new hash which will trigger a new compiling.
		return $hash . '-' . $_hash;
	}

	/**
	 * Remove any modified files.  A file is considered modified when:
	 *
	 * 1. It has both a base hash and filemtime hash, separated by '-'.
	 * 2. Its base hash matches the given hash.
	 * 3. Its filemtime hash does not match the given filemtime hash.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hash           Base hash.
	 * @param string $filemtime_hash The filemtime hash (from hashing the fragments).
	 *
	 * @return void
	 */
	private function remove_modified_files( $hash, $filemtime_hash ) {

		if ( ! is_dir( $this->dir ) ) {
			return;
		}

		$items = @scandir( $this->dir );  // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case.
		unset( $items[0], $items[1] );

		if ( empty( $items ) ) {
			return;
		}

		foreach ( $items as $item ) {

			// Skip this one if it's a directory.
			if ( @is_dir( $item ) ) { // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case.
				continue;
			}

			// Skip this one if it's not the same type.
			if ( pathinfo( $item, PATHINFO_EXTENSION ) !== $this->get_extension() ) {
				continue;
			}

			// Skip this one if it does not have a '-' in the filename.
			if ( strpos( $item, '-' ) === false ) {
				continue;
			}

			$hash_parts = explode( '-', pathinfo( $item, PATHINFO_FILENAME ) );

			// Skip this one if it does not match the given base hash.
			if ( $hash_parts[0] !== $hash ) {
				continue;
			}

			// Skip this one if it does match the given filemtime's hash.
			if ( $hash_parts[1] === $filemtime_hash ) {
				continue;
			}

			// Clean up other modified files.
			@unlink( $this->dir . '/' . $item );  // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case.
		}
	}

	/**
	 * Clean the CSS.
	 *
	 * @since 1.15.0
	 *
	 * @param string $content Given content to process.
	 *
	 * @return string
	 */
	private function clean_style( $content ) {
		$content = preg_replace( '/\n.+: ;/', '', $content ); // Remove properties without value. (e.g. font-size: ;).

		return $content;
	}

	/**
	 * Minify the CSS.
	 *
	 * @since 1.15.0
	 *
	 * @param string $content Given content to process.
	 *
	 * @return string
	 */
	private function minify_style_2( $content ) {
		$replace = [
			'/[^{}\r\n]+(?:\r?\n[^{}\r\n]+)*{\s*}/' => '',  // Strip empty selectors.
			'/\s*@media.*(\n|\s.)*\n.*}/'           => '',  // Strip empty @media.
			'/\n/'                                  => '',  // Strip line breaks.
		];

		$search  = array_keys( $replace );
		$content = preg_replace( $search, $replace, $content );

		return $content;
	}

	/**
	 * Minify the CSS.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Given content to process.
	 *
	 * @return string
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 */
	private function minify_style( $content ) {
		$replace = array(
			'/([^\r\n{}]+)(,(?=[^}]*{)|\s*{)}/' => '',  // Strip empty selectors.
			'/@media\s\(.*\).*{}/'              => '',  // Strip empty @media.
			'#/\*.*?\*/#s'                      => '',  // Strip comments.
			'#\s\s+#'                           => ' ', // Strip excess whitespace.
		);

		$search  = array_keys( $replace );
		$content = preg_replace( $search, $replace, $content );

		$replace = array(
			': '  => ':',
			'; '  => ';',
			' {'  => '{',
			' }'  => '}',
			', '  => ',',
			'{ '  => '{',
			',\n' => ',', // Don't wrap multiple selectors.
			'\n}' => '}', // Don't wrap closing braces.
			'} '  => "}\n", // Put each rule on it's own line.
			'\n'  => '', // Remove all line breaks.
		);

		$search = array_keys( $replace );

		return trim( str_replace( $search, $replace, $content ) );
	}

	/**
	 * Check if the given fragment is a callable.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $fragment Given fragment to check.
	 *
	 * @return bool
	 */
	private function is_function( $fragment ) {
		return ( is_array( $fragment ) || is_callable( $fragment ) );
	}

	/**
	 * Kill it :(
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function kill() {

		// Send report if set.
		if ( jupiterx_get( 'jupiterx_send_compiler_report' ) ) { // @codingStandardsIgnoreLine
			// $this->report(); // @codingStandardsIgnoreLine
		}

		$html = jupiterx_output( 'jupiterx_compiler_error_title_text', sprintf(
			'<h2>%s</h2>',
			__( 'Not cool, Jupiter cannot work its magic :(', 'jupiterx-core' )
		) );

		$html .= jupiterx_output( 'jupiterx_compiler_error_message_text', sprintf(
			'<p>%s</p>',
			__( 'Your current install or file permission prevents Jupiter from working its magic. Please get in touch with Jupiter support. We will gladly get you started within 24 - 48 hours (working days).', 'jupiterx-core' )
		) );

		$html .= jupiterx_output( 'jupiterx_compiler_error_contact_text', sprintf(
			'<a class="button" href="https://themes.artbees.net/support/" target="_blank">%s</a>',
			__( 'Contact Jupiter Support', 'jupiterx-core' )
		) );

		$html .= jupiterx_output( 'jupiterx_compiler_error_report_text', sprintf(
			'<p style="margin-top: 12px; font-size: 12px;"><a href="' . add_query_arg( 'jupiterx_send_compiler_report', true ) . '">%1$s</a>. %2$s</p>',
			__( 'Send us an automatic report', 'jupiterx-core' ),
			__( 'We respect your time and understand you might not be able to contact us.', 'jupiterx-core' )
		) );

		wp_die( wp_kses_post( $html ) );
	}

	/**
	 * Send report.
	 *
	 * @since 1.0.0
	 *
	 * @todo Decide if we want to use and change the report recipient.
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 */
	private function report() {
		// Send report.
		wp_mail(
			'hello@getjupiter.io',
			'Compiler error',
			'Compiler error reported by ' . home_url(),
			array(
				'MIME-Version: 1.0' . "\r\n",
				'Content-type: text/html; charset=utf-8' . "\r\n",
				"X-Mailer: PHP \r\n",
				'From: ' . wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) . ' < ' . get_option( 'admin_email' ) . '>' . "\r\n",
				'Reply-To: ' . get_option( 'admin_email' ) . "\r\n",
			)
		);

		// Die and display message.
		$message = jupiterx_output(
			'jupiterx_compiler_report_error_text',
			sprintf(
				'<p>%s<p>',
				__( 'Thanks for your contribution by reporting this issue. We hope to hear from you again.', 'jupiterx-core' )
			)
		);

		wp_die( wp_kses_post( $message ) );
	}

	/**
	 * Get the property's value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property Name of the property to get.
	 *
	 * @return mixed
	 */
	public function __get( $property ) {

		if ( property_exists( $this, $property ) ) {
			return $this->{$property};
		}
	}
}
