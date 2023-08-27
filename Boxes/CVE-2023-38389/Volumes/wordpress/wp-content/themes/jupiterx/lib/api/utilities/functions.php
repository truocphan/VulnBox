<?php
/**
 * The Jupiter Utilities is a set of tools to ease building applications.
 *
 * Since these functions are used throughout the Jupiter framework and are therefore required, they are
 * loaded automatically when the Jupiter framework is included.
 *
 * @package JupiterX\Framework\API\Utilities
 *
 * @since   1.5.0
 */

/**
 * Include plugin utilities.
 */
require_once JUPITERX_API_PATH . 'utilities/plugins.php';

/**
 * Calls function given by the first parameter and passes the remaining parameters as arguments.
 *
 * The main purpose of this function is to store the content echoed by a function in a variable.
 *
 * @since 1.0.0
 *
 * @param Callable $callback The callback to be called.
 * @param mixed    $args,... Optional. Additional parameters to be passed to the callback.
 *
 * @return string The callback content.
 */
function jupiterx_render_function( $callback ) {

	if ( ! is_callable( $callback ) ) {
		return;
	}

	$args = func_get_args();

	ob_start();

	call_user_func_array( $callback, array_slice( $args, 1 ) );

	return ob_get_clean();
}

/**
 * Calls function given by the first parameter and passes the remaining parameters as arguments.
 *
 * The main purpose of this function is to store the content echoed by a function in a variable.
 *
 * @since 1.0.0
 *
 * @param Callable $callback The callback to be called.
 * @param array    $params   Optional. The parameters to be passed to the callback, as an indexed array.
 *
 * @return string The callback content.
 */
function jupiterx_render_function_array( $callback, $params = array() ) {

	if ( ! is_callable( $callback ) ) {
		return;
	}

	ob_start();

	call_user_func_array( $callback, $params );

	return ob_get_clean();
}

/**
 * Remove a directory and its files.
 *
 * @since 1.0.0
 *
 * @param string $dir_path Path to directory to remove.
 *
 * @return bool Returns true if the directory was removed; else, return false.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_remove_dir( $dir_path ) {

	if ( ! is_dir( $dir_path ) ) {
		return false;
	}

	$items = scandir( $dir_path );
	unset( $items[0], $items[1] );

	foreach ( $items as $needle => $item ) {
		$path = $dir_path . '/' . $item;

		if ( is_dir( $path ) ) {
			jupiterx_remove_dir( $path );
		} else {
			unlink( $path );
		}
	}

	return rmdir( $dir_path );
}

/**
 * Convert internal path to a url.
 *
 * This function must only be used with internal paths.
 *
 * @since 1.0.0
 *
 * @param string $path          Path to be converted. Accepts absolute and relative internal paths.
 * @param bool   $force_rebuild Optional. Forces the rebuild of the root url and path.
 *
 * @return string Url.
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_path_to_url( $path, $force_rebuild = false ) {
	static $root_path, $root_url;

	// Stop here if it is already a url or data format.
	if ( _jupiterx_is_uri( $path ) ) {
		return $path;
	}

	// Standardize backslashes.
	$path = wp_normalize_path( $path );

	// Set root and host if it isn't cached.
	if ( ! $root_path || true === $force_rebuild ) {

		// Standardize backslashes set host.
		$root_path = wp_normalize_path( untrailingslashit( ABSPATH ) );
		$root_url  = untrailingslashit( site_url() );

		// Remove subfolder if necessary.
		$subfolder = wp_parse_url( $root_url, PHP_URL_PATH );

		if ( $subfolder && '/' !== $subfolder ) {
			$pattern   = '#' . untrailingslashit( preg_quote( $subfolder ) ) . '$#'; // @codingStandardsIgnoreLine
			$root_path = preg_replace( $pattern, '', $root_path );
			$root_url  = preg_replace( $pattern, '', $root_url );
		}

		// If it's a multisite and not the main site, then add the site's path.
		if ( ! is_main_site() ) {
			$blogdetails = get_blog_details( get_current_blog_id() );

			if ( $blogdetails && ( ! defined( 'WP_SITEURL' ) || ( defined( 'WP_SITEURL' ) && WP_SITEURL === site_url() ) ) ) {
				$root_url = untrailingslashit( $root_url ) . $blogdetails->path;
			}
		}

		// Maybe re-add tilde from host.
		$maybe_tilde = jupiterx_get( 0, explode( '/', trailingslashit( ltrim( $subfolder, '/' ) ) ) );

		if ( false !== stripos( $maybe_tilde, '~' ) ) {
			$root_url = trailingslashit( $root_url ) . $maybe_tilde;
		}
	}

	// Remove root if necessary.
	if ( false !== stripos( $path, $root_path ) ) {
		$path = str_replace( $root_path, '', $path );
	} elseif ( false !== stripos( $path, jupiterx_get( 'DOCUMENT_ROOT', $_SERVER ) ) ) {
		$path = str_replace( jupiterx_get( 'DOCUMENT_ROOT', $_SERVER ), '', $path );
	}

	return trailingslashit( $root_url ) . ltrim( $path, '/' );
}

/**
 * Convert internal url to a path.
 *
 * This function must only be used with internal urls.
 *
 * @since 1.0.0
 *
 * @param string $url           Url to be converted. Accepts only internal urls.
 * @param bool   $force_rebuild Optional. Forces the rebuild of the root url and path.
 *
 * @return string Absolute path.
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_url_to_path( $url, $force_rebuild = false ) {
	static $root_path, $blogdetails;
	$site_url = site_url();

	if ( true === $force_rebuild ) {
		$root_path   = '';
		$blogdetails = '';
	}

	// Fix protocol. It isn't needed to set SSL as it is only used to parse the URL.
	if ( ! wp_parse_url( $url, PHP_URL_SCHEME ) ) {
		$original_url = $url;
		$url          = 'http://' . ltrim( $url, '/' );
	}

	// It's not an internal URL. Bail out.
	if ( false === stripos( wp_parse_url( $url, PHP_URL_HOST ), wp_parse_url( $site_url, PHP_URL_HOST ) ) ) {
		return isset( $original_url ) ? $original_url : $url;
	}

	// Parse url and standardize backslashes.
	$url  = wp_parse_url( $url, PHP_URL_PATH );
	$path = wp_normalize_path( $url );

	// Maybe remove tilde from path.
	$trimmed_path = trailingslashit( ltrim( $path, '/' ) );
	$maybe_tilde  = jupiterx_get( 0, explode( '/', $trimmed_path ) );

	if ( false !== stripos( $maybe_tilde, '~' ) ) {
		$ends_with_slash = substr( $path, - 1 ) === '/';
		$path            = preg_replace( '#\~[^/]*\/#', '', $trimmed_path );

		if ( $path && ! $ends_with_slash ) {
			$path = rtrim( $path, '/' );
		}
	}

	// Set root if it isn't cached yet.
	if ( ! $root_path ) {
		// Standardize backslashes and remove windows drive for local installs.
		$root_path = wp_normalize_path( untrailingslashit( ABSPATH ) );
		$set_root  = true;
	}

	/*
	 * If the subfolder exists for the root URL, then strip it off of the root path.
	 * Why? We don't want a double subfolder in the final path.
	 */
	$subfolder = wp_parse_url( $site_url, PHP_URL_PATH );

	if ( isset( $set_root ) && $subfolder && '/' !== $subfolder ) {
		$root_path = preg_replace( '#' . untrailingslashit( preg_quote( $subfolder ) ) . '$#', '', $root_path ); // @codingStandardsIgnoreLine

		// Add an extra step which is only used for extremely rare case.
		if ( defined( 'WP_SITEURL' ) ) {
			$subfolder = wp_parse_url( WP_SITEURL, PHP_URL_PATH );

			if ( '' !== $subfolder ) {
				$root_path = preg_replace( '#' . untrailingslashit( preg_quote( $subfolder ) ) . '$#', '', $root_path ); // @codingStandardsIgnoreLine
			}
		}
	}

	// Remove the blog path for multisites.
	if ( ! is_main_site() ) {

		// Set blogdetails if it isn't cached.
		if ( ! $blogdetails ) {
			$blogdetails = get_blog_details( get_current_blog_id() );
		}

		$path = preg_replace( '#^(\/?)' . trailingslashit( preg_quote( ltrim( $blogdetails->path, '/' ) ) ) . '#', '', $path ); // @codingStandardsIgnoreLine
	}

	// Remove Windows drive for local installs if the root isn't cached yet.
	if ( isset( $set_root ) ) {
		$root_path = jupiterx_sanitize_path( $root_path );
	}

	// Add root of it doesn't exist.
	if ( false === strpos( $path, $root_path ) ) {
		$path = trailingslashit( $root_path ) . ltrim( $path, '/' );
	}

	return jupiterx_sanitize_path( $path );
}

/**
 * Sanitize path.
 *
 * @since 1.0.0
 *
 * @param string $path Path to be sanitize. Accepts absolute and relative internal paths.
 *
 * @return string Sanitize path.
 */
function jupiterx_sanitize_path( $path ) {

	// Try to convert it to real path.
	if ( false !== realpath( $path ) ) {
		$path = realpath( $path );
	}

	// Remove Windows drive for local installs if the root isn't cached yet.
	$path = preg_replace( '#^[A-Z]\:#i', '', $path );

	return wp_normalize_path( $path );
}

/**
 * Get value from $_GET or defined $haystack.
 *
 * @since 1.0.0
 *
 * @param string $needle   Name of the searched key.
 * @param mixed  $haystack Optional. The target to search. If false, $_GET is set to be the $haystack.
 * @param mixed  $default  Optional. Value to return if the needle isn't found.
 *
 * @return string Returns the value if found; else $default is returned.
 */
function jupiterx_get( $needle, $haystack = false, $default = null ) {

	if ( false === $haystack ) {
		$haystack = $_GET; // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- The nonce verification check should be at the form processing level.
	}

	$haystack = (array) $haystack;

	if ( isset( $haystack[ $needle ] ) ) {
		return $haystack[ $needle ];
	}

	return $default;
}

/**
 * Get value from $_POST.
 *
 * @since 1.0.0
 *
 * @param string $needle  Name of the searched key.
 * @param mixed  $default Optional. Value to return if the needle isn't found.
 *
 * @return string Returns the value if found; else $default is returned.
 */
function jupiterx_post( $needle, $default = null ) {
	return jupiterx_get( $needle, $_POST, $default ); // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- The nonce verification check should be at the form processing level.
}

/**
 * Get value from $_GET or $_POST superglobals.
 *
 * @since 1.0.0
 *
 * @param string $needle  Name of the searched key.
 * @param mixed  $default Optional. Value to return if the needle isn't found.
 *
 * @return string Returns the value if found; else $default is returned.
 */
function jupiterx_get_or_post( $needle, $default = null ) {
	$get = jupiterx_get( $needle );

	if ( $get ) {
		return $get;
	}

	$post = jupiterx_post( $needle );

	if ( $post ) {
		return $post;
	}

	return $default;
}

/**
 * Checks if a value exists in a multi-dimensional array.
 *
 * @since 1.0.0
 *
 * @param string $needle   The searched value.
 * @param array  $haystack The multi-dimensional array.
 * @param bool   $strict   If the third parameter strict is set to true, the jupiterx_in_multi_array()
 *                         function will also check the types of the needle in the haystack.
 *
 * @return bool Returns true if needle is found in the array; else, false is returned.
 */
function jupiterx_in_multi_array( $needle, $haystack, $strict = false ) {

	if ( in_array( $needle, $haystack, $strict ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict -- The rule does not account for when we are passing a boolean within a variable as the 3rd argument.
		return true;
	}

	foreach ( (array) $haystack as $value ) {

		if ( is_array( $value ) && jupiterx_in_multi_array( $needle, $value ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Checks if a key or index exists in a multi-dimensional array.
 *
 * @since 1.0.0
 *
 * @param string $needle   The key to search for within the haystack.
 * @param array  $haystack The array to be searched.
 *
 * @return bool Returns true if needle is found in the array; else, false is returned.
 */
function jupiterx_multi_array_key_exists( $needle, array $haystack ) {

	if ( array_key_exists( $needle, $haystack ) ) {
		return true;
	}

	foreach ( $haystack as $value ) {

		if ( is_array( $value ) && jupiterx_multi_array_key_exists( $needle, $value ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Search content for shortcodes and filter shortcodes through their hooks.
 *
 * Shortcodes must be delimited with curly brackets (e.g. {key}) and correspond to the searched array key.
 *
 * @since 1.0.0
 *
 * @param string $content  Content containing the shortcode(s) delimited with curly brackets (e.g. {key}).
 *                        Shortcode(s) correspond to the searched array key and will be replaced by the array
 *                        value if found.
 * @param array  $haystack The associative array used to replace shortcode(s).
 *
 * @return string Content with shortcodes filtered out.
 */
function jupiterx_array_shortcodes( $content, $haystack ) {

	if ( preg_match_all( '#{(.*?)}#', $content, $matches ) ) {

		foreach ( $matches[1] as $needle ) {
			$sub_keys = explode( '.', $needle );
			$value    = false;

			foreach ( $sub_keys as $sub_key ) {
				$search = $value ? $value : $haystack;
				$value  = jupiterx_get( $sub_key, $search );
			}

			if ( $value ) {
				$content = str_replace( '{' . $needle . '}', $value, $content );
			}
		}
	}

	return $content;
}

/**
 * Make sure the menu position is valid.
 *
 * If the menu position is unavailable, it will loop through the positions until one is found that is available.
 *
 * @since 1.0.0
 *
 * @global    $menu
 *
 * @param int $position The desired position.
 *
 * @return bool Valid position.
 */
function jupiterx_admin_menu_position( $position ) {
	global $menu;

	if ( ! is_array( $position ) ) {
		return $position;
	}

	if ( array_key_exists( $position, $menu ) ) {
		return jupiterx_admin_menu_position( $position + 1 );
	}

	return $position;
}

/**
 * Sanitize HTML attributes from array to string.
 *
 * @since 1.0.0
 *
 * @param array $attributes The array key defines the attribute name and the array value define the
 *                          attribute value.
 *
 * @return string The escaped attributes.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_esc_attributes( $attributes ) {

	/**
	 * Filter attributes escaping methods.
	 *
	 * For all unspecified selectors, values are automatically escaped using
	 * {@link http://codex.wordpress.org/Function_Reference/esc_attr esc_attr()}.
	 *
	 * @since 1.0.0
	 *
	 * @param array $method Associative array of selectors as keys and escaping method as values.
	 */
	$methods = apply_filters( 'jupiterx_escape_attributes_methods', array(
		'href'     => 'esc_url',
		'src'      => 'esc_url',
		'itemtype' => 'esc_url',
		'onclick'  => 'esc_js',
	) );

	$string = '';

	foreach ( (array) $attributes as $attribute => $value ) {

		if ( null === $value ) {
			continue;
		}

		$method = jupiterx_get( $attribute, $methods );

		if ( $method ) {
			$value = call_user_func( $method, $value );
		} else {
			$value = esc_attr( $value );
		}

		$string .= $attribute . '="' . $value . '" ';
	}

	return trim( $string );
}

/**
 * Checks if the given input is a URL or data URI.  It checks that the given input begins with:
 *      - http
 *      - https
 *      - //
 *      - data
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @param string $maybe_uri The given input to check.
 *
 * @return bool
 */
function _jupiterx_is_uri( $maybe_uri ) {
	return ( 1 === preg_match( '#^(http|https|\/\/|data)#', $maybe_uri ) );
}

/**
 * Checks if the page is a post type archive.
 *
 * @since  1.0.0
 *
 * @param string $post_type The given input to check.
 *
 * @return bool
 */
function jupiterx_is_archive( $post_type = 'post' ) {
	if ( is_author() || ! is_archive() || get_post_type() !== $post_type ) {
		return false;
	}

	return true;
}

/**
 * Get a template from Elementor.
 *
 * @since  1.0.0
 *
 * @param string $id The template post id.
 *
 * @return bool
 */
function jupiterx_get_custom_template( $id ) {
	if ( ! class_exists( 'Elementor\Plugin' ) ) {
		return;
	}

	if ( empty( $id ) ) {
		return;
	}

	$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );

	return $content;
}

/**
 * Check if post element is enabled.
 *
 * It checks post meta option then customizer option.
 *
 * @todo Find a better place for this function.
 *
 * @since 1.0.0
 *
 * @param string $element The element.
 * @param string $post_type The post type.
 *
 * @return boolean True if element enabled.
 */
function jupiterx_post_element_enabled( $element, $post_type = 'post' ) {

	$elements = [];
	$field    = jupiterx_get_field( "jupiterx_{$post_type}_{$element}", 'global' );

	if ( '1' === $field ) {
		return true;
	}

	if ( empty( $field ) ) {
		return false;
	}

	$elements = jupiterx_get_post_single_elements();

	if ( ! empty( $elements ) && ! in_array( $element, $elements, true ) ) {
		return false;
	}

	// An exception only for title.
	if ( empty( $elements ) && 'title' === $element ) {
		return false;
	}

	return true;
}

/**
 * Get list of enabled elements in single post and portfolio.
 *
 * @since 1.0.0
 * @since 1.10.0 Supports all custom post types.
 *
 * @return array List of enabled elements.
 */
function jupiterx_get_post_single_elements() {
	$elements = [];

	if ( is_singular( 'post' ) ) {
		$elements = get_theme_mod( 'jupiterx_post_single_elements', [
			'featured_image',
			'date',
			'author',
			'categories',
			'tags',
			'social_share',
			'author_box',
			'related_posts',
			'comments',
			'navigation',
		] );
	} elseif ( is_singular( 'portfolio' ) ) {
		$elements = get_theme_mod( 'jupiterx_portfolio_single_elements', [
			'featured_image',
			'categories',
			'social_share',
			'related_posts',
			'comments',
			'navigation',
		] );
	} elseif ( is_singular( 'page' ) ) {
		$elements = get_theme_mod( 'jupiterx_page_single_elements', [
			'featured_image',
			'comments',
		] );
	} elseif ( is_singular() && ! is_singular( 'page' ) ) {
		$elements = get_theme_mod( 'jupiterx_' . get_post_type() . '_single_elements', [
			'featured_image',
		] );
	}

	return $elements;
}

/**
 * Get current visiting page key from list of conditions.
 *
 * @since 1.0.0
 *
 * @return string
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_get_current_page_key() {
	$jupiterx_cpt_archives = jupiterx_get_post_types_archives();

	foreach ( $jupiterx_cpt_archives as $jupiterx_cpt_archive => $jupiterx_cpt_archive_label ) {
		if ( is_post_type_archive( str_replace( 'archive__', '', $jupiterx_cpt_archive ) ) ) {
			return $jupiterx_cpt_archive;
		}
	}

	// WooCommerce product page or shop.
	if ( class_exists( 'woocommerce' ) ) {
		if ( is_shop() || is_product() ) {
			return 'product';
		}
	}

	// Archive.
	if ( is_archive() ) {
		return 'archive';
	}

	// Single post or blog.
	if ( is_singular( 'post' ) || is_home() ) {
		return 'post';
	}

	// Single page.
	if ( is_page() && ! is_front_page() ) {
		return 'page';
	}

	// Single portfolio.
	if ( is_singular( 'portfolio' ) ) {
		return 'portfolio';
	}

	// Search page.
	if ( is_search() ) {
		return 'search';
	}

	// Custom post type.
	if ( in_array( get_post_type(), jupiterx_get_post_types(), true ) ) {
		return get_post_type();
	}

	return '';
}

/**
 * Get value from theme mod which uses exception control.
 *
 * Function will get the value from current page visiting key.
 *
 * @since 1.0.0
 *
 * @param string $setting Theme mod setting name.
 *
 * @return array
 */
function jupiterx_get_exception_mod( $setting ) {
	$exceptions = get_theme_mod( $setting );

	if ( empty( $exceptions ) ) {
		return [];
	}

	$page_key = jupiterx_get_current_page_key();

	if ( empty( $page_key ) || ! isset( $exceptions[ $page_key ] ) ) {
		return [];
	}

	return $exceptions[ $page_key ];
}

/**
 * Get automatic direction based on RTL/LTR.
 *
 * @since 1.0.0
 *
 * @param string $direction The direction.
 *
 * @return string The direction.
 */
function jupiterx_get_direction( $direction ) {
	if ( ! is_rtl() ) {
		return $direction;
	}

	if ( false !== stripos( $direction, 'left' ) ) {
		return str_replace( 'left', 'right', $direction );
	}

	if ( false !== stripos( $direction, 'right' ) ) {
		return str_replace( 'right', 'left', $direction );
	}

	return $direction;
}

/**
 * Echo help link with custom link and title.
 *
 * @param string $href URL of help link.
 * @param string $title Title of help link.
 * @param string $theme Dark theme and Light theme for help links.
 *
 * @since 1.3.0
 *
 * @return void
 */
function jupiterx_the_help_link( $href = '#', $title = '', $theme = 'light' ) {
	echo jupiterx_get_the_help_link( $href, $title, $theme ); //phpcs:ignore
}

/**
 * Echo help link with custom link and title.
 *
 * @param string $href URL of help link.
 * @param string $title Title of help link.
 * @param string $theme Dark theme and Light theme for help links.
 *
 * @since 1.3.0
 * @since 1.11.0 Prevent from echoing when help links is disabled.
 *
 * @return string Help link markup.
 */
function jupiterx_get_the_help_link( $href = '#', $title = '', $theme = 'light' ) {
	if ( ! jupiterx_is_help_links() ) {
		return '';
	}

	$theme = 'dark' === $theme ? 'dark' : 'light';
	return '<a href="' . esc_url( $href ) . '" class="jupiterx-help-link jupiterx-help-link-' . esc_attr( $theme ) . ' jupiterx-icon-question" title="' . esc_attr( $title ) . '" target="_blank"></a>';
}

/**
 * Determines whether a plugin is active via `function_exists` or `class_exists`.
 *
 * @since 1.2.0
 * @since 1.5.0 Remove plugin slug as parameter.
 *
 * @param string $callable Plugin callable function or class.
 *
 * @return boolean Plugin class/function status.
 */
function jupiterx_is_callable( $callable ) {
	return ( class_exists( $callable ) || function_exists( $callable ) ) ? true : false;
}

/**
 * Check if user had Jupiter theme.
 *
 * @since 1.2.0
 *
 * @return bool If user had Jupiter theme, returns true. Otherwise returns false.
 */
function jupiterx_had_jupiter() {
	$jupiter_exist = get_option( 'mk_jupiter_theme_current_version' );

	if ( ! empty( $jupiter_exist ) ) {
		return true;
	}

	return false;
}

/**
 * Get utm links for upgrade to pro buttons.
 *
 * @param string $utm The name of the utm link.
 *
 * @since 1.3.0
 *
 * @return string The requested url.
 */
function jupiterx_upgrade_link( $utm = '' ) {
	$base_link = 'https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees';
	switch ( $utm ) {
		case 'comparison':
			return $base_link . '&utm_source=FreeVSProPageUpgradeCTA&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign';
		case 'customizer':
			return $base_link . '&utm_source=CustomizerAreaUpgradeButton&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign';
		case 'banner':
			return $base_link . '&utm_source=BannerAdUpgradeCTA&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign';
		case 'templates':
			return $base_link . '&utm_source=TemplateSectionImportButton&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign';

		default:
			return $base_link . '&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign';
	}
}

/**
 * Dynamically remove an action.
 *
 * A wrapper function of WP `remove_action` that doesn't require priority parameter.
 *
 * @since 1.0.5
 *
 * @param string $tag Action where function is hooked.
 * @param string $function_name Name of the function.
 * @param int    $priority Priority level.
 */
function jupiterx_dynamic_remove_action( $tag, $function_name, $priority = null ) {
	if ( ! is_null( $priority ) ) {
		remove_action( $tag, $function_name, $priority );
		return;
	}

	global $wp_filter;

	if ( ! isset( $wp_filter[ $tag ]->callbacks ) ) {
		return;
	}

	/**
	 * Run through each hooks and find its correct priority.
	 */
	foreach ( $wp_filter[ $tag ]->callbacks as $priority => $hooks ) {
		if ( array_key_exists( $function_name, $hooks ) ) {
			remove_action( $tag, $function_name, $priority );
		}
	}
}

/**
 * Get site container width.
 *
 * @since 1.11.0
 *
 * @return boolean $container_width Size and unit of container width.
 */
function jupiterx_is_preview() {
	$preview_mode = is_preview() || is_customize_preview();
	if ( class_exists( 'Elementor\Plugin' ) ) {
		$elementor_edit_mode = \Elementor\Plugin::$instance->preview->is_preview_mode();
		return $preview_mode || $elementor_edit_mode;
	}
	return $preview_mode;
}

/** Get public custom post types.
 *
 * A function that retrieves custom post types that has is publicly queryable or has something to represent on front-end such as single page.
 *
 * @since 1.10.0
 *
 * @param string $output The type of output to return, either 'names' or 'objects'.
 *
 * @return array A list of post names or objects.
 */
function jupiterx_get_custom_post_types( $output = 'names' ) {
	$args = [
		'public'             => true,
		'publicly_queryable' => true,
		'_builtin'           => false,
	];

	$post_types = get_post_types( $args, $output );

	$excludes = [
		'portfolio',
		'elementor_library',
		'jupiterx-codes',
		'jupiterx-fonts',
		'jupiterx-icons',
		'sellkit_step',
	];

	if ( class_exists( 'WooCommerce' ) ) {
		array_push( $excludes, 'product' );
	}

	if ( in_array( 'memberpress-courses/main.php', get_option( 'active_plugins', [] ), true ) ) {
		array_push( $excludes, 'mpcs-course', 'mpcs-lesson' );
	}

	if ( in_array( 'memberpress-downloads/main.php', get_option( 'active_plugins', [] ), true ) ) {
		array_push( $excludes, 'mpdl-file' );
	}

	return array_diff_key(
		$post_types,
		array_flip( $excludes )
	);
}

/**
 * Get integrated post types with the theme.
 *
 * @since 1.10.0
 *
 * @param string $output The type of output to return, either 'names', 'labels' or 'objects'.
 * @param array  $args   Optional. An array of key => value arguments to match against the post type objects. Default empty array.
 *
 * @return array A list of integrated post types.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_get_post_types( $output = 'names', $args = [] ) {
	$post_types = jupiterx_get_option( 'post_types' );

	if ( empty( $post_types ) ) {
		return [];
	}

	$custom = [];

	foreach ( $post_types as $post_type ) {
		$object = get_post_type_object( $post_type );

		if ( ! empty( $object ) ) {
			$custom[ $post_type ] = $object;
		}
	}

	if ( ! empty( $args ) ) {
		$custom = wp_filter_object_list( $custom, $args, 'and', false );
	}

	if ( 'names' === $output ) {
		$custom = array_keys( $custom );
	} else {
		foreach ( $custom as $object ) {
			$custom[ $object->name ] = 'labels' === $output ? $object->label : $object;
		}
	}

	return $custom;
}

/**
 * Get integrated post types archives with the theme.
 *
 * @since 1.15.0
 *
 * @return array A list of integrated post types archives.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_get_post_types_archives() {
	$post_types = jupiterx_get_post_types( 'labels' );
	$archives   = [];

	foreach ( $post_types as $type => $label ) {
		$object = get_post_type_object( $type );

		if ( empty( $object->has_archive ) ) {
			continue;
		}

		$archives[ 'archive__' . $type ] = $label . esc_html__( ' Archive', 'jupiterx' );
	}

	return $archives;
}

/**
 * Check function already exists.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_get_archive_post_type' ) ) {
	/**
	 * Get archive post type. If there are multiple post types on the archive template then return empty.
	 *
	 * @since 1.10.0
	 *
	 * @return string
	 */
	function jupiterx_get_archive_post_type() {
		$post_type = '';

		if ( ! is_archive() ) {
			return $post_type;
		}

		global $wp_query;

		$query_vars = $wp_query->query_vars;

		if ( isset( $query_vars['post_type'] ) ) {
			$post_type = $query_vars['post_type'];

			if ( is_array( $post_type ) && count( $post_type ) === 1 ) {
				$post_type = $post_type[0];
			}

			if ( is_array( $post_type ) && count( $post_type ) > 1 ) {
				$post_type = '';
			}
		}

		if ( empty( $post_type ) && isset( $query_vars['taxonomy'] ) ) {
			$taxonomy_post_types = get_taxonomy( $query_vars['taxonomy'] )->object_type;

			if ( count( $taxonomy_post_types ) === 1 ) {
				$post_type = $taxonomy_post_types[0];
			}
		}

		if ( empty( $post_type ) && ( is_category() || is_tag() || is_date() || is_author() ) ) {
			$post_type = 'post';
		}

		return $post_type;
	}
}

/**
 * Get option from options storage.
 *
 * @param string  $option  Option name.
 * @param boolean $default Default value.
 *
 * @return mixed Value set for the option.
 */
function jupiterx_get_option( $option, $default = false ) {
	$options = get_option( 'jupiterx', [] );

	if ( ! isset( $options[ $option ] ) ) {
		return $default;
	}

	return $options[ $option ];
}

/**
 * Update option from options storage.
 *
 * @param string $option Option name.
 * @param mixed  $value  Update value.
 *
 * @return boolean False if value was not updated and true if value was updated.
 */
function jupiterx_update_option( $option, $value ) {
	$options = get_option( 'jupiterx', [] );

	// Make sure options value to be array always.
	if ( ! is_array( $options ) ) {
		$options = [];
	}

	// No need to update the same value.
	if ( isset( $options[ $option ] ) && $value === $options[ $option ] ) {
		return false;
	}

	// Update the option.
	$options[ $option ] = $value;
	update_option( 'jupiterx', $options );

	return true;
}

/**
 * Delete option from options storage.
 *
 * @param string $option Option name.
 *
 * @return boolean False if value was not deleted and true if value was deleted.
 */
function jupiterx_delete_option( $option ) {
	$options = get_option( 'jupiterx', [] );

	// Option not exist.
	if ( ! isset( $options[ $option ] ) ) {
		return false;
	}

	// Remove the option.
	unset( $options[ $option ] );
	update_option( 'jupiterx', $options );

	return true;
}

/**
 * Check current viewing screen.
 *
 * @param string $id Screen name.
 *
 * @return boolean Result of current viewing screen.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_is_screen( $id = '' ) {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}

	$screen = get_current_screen();

	if ( ! $screen ) {
		return false;
	} elseif ( is_array( $id ) ) {
		return in_array( $screen->id, $id, true );
	} else {
		return ( $id === $screen->id );
	}
}

/**
 * Check white label.
 *
 * @return boolean Enable white label or not.
 */
function jupiterx_is_white_label() {
	if ( ! jupiterx_is_callable( 'jupiterx_core' ) ) {
		return false;
	}

	if ( ! jupiterx_get_option( 'white_label' ) ) {
		return false;
	}

	return true;
}

/**
 * Check if help links is enabled.
 *
 * @return boolean The help links enabled or not.
 */
function jupiterx_is_help_links() {
	if ( ! jupiterx_is_white_label() || ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_help_links', true ) ) ) {
		return true;
	}

	return false;
}


/**
 * WP color picker alpha localize since WordPress 5.5.
 *
 * @since 1.21.0
 */
if ( ! function_exists( 'jupiterx_wpcolorpickeralpha_localize' ) ) {
	/**
	 * Get archive post type. If there are multiple post types on the archive template then return empty.
	 *
	 * @since 1.21.0
	 *
	 * @return void
	 */
	function jupiterx_wpcolorpickeralpha_localize() {
		$color_picker_strings = array(
			'clear'            => __( 'Clear', 'jupiterx' ),
			'clearAriaLabel'   => __( 'Clear color', 'jupiterx' ),
			'defaultString'    => __( 'Default', 'jupiterx' ),
			'defaultAriaLabel' => __( 'Select default color', 'jupiterx' ),
			'pick'             => __( 'Select Color', 'jupiterx' ),
			'defaultLabel'     => __( 'Color value', 'jupiterx' ),
		);

		wp_localize_script( 'wp-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );
	}
}

if ( ! function_exists( 'is_admin_post_editor_page' ) ) {
	/**
	 * Check whether current page is post admin page or not.
	 *
	 * @since 1.22.0
	 */
	function is_admin_post_editor_page() {
		global $pagenow;

		if (
			is_admin() &&
			( 'post.php' === $pagenow || 'post-new.php' === $pagenow )
		) {
			return true;
		}

		return false;
	}
}
