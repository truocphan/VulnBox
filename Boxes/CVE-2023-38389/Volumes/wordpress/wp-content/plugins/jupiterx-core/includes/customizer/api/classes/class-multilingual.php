<?php

// phpcs:ignoreFile
/**
 * If Polylang is active:
 * - save and retrieve customizer setting per language.
 * - on front-page, set options and theme mod for the selected language.
 *
 * Inspired by https://github.com/fastlinemedia/customizer-export-import
 *
 * @package JupiterX\Framework\API\Customizer
 */

if ( ! is_multilingual_customizer() ) {
	return;
}

if ( ! function_exists( 'pll_current_language' ) && ! class_exists( 'SitePress' ) ) {
	return;
}

/**
 * Functionality for multilingual customizer.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\API\Customizer
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class CoreCustomizerMultilingual {

	public static function init() {
		$self = new self();

		// Disable detect browser language, will return default language instead.
		add_filter( 'pll_preferred_language', '__return_false' );

		add_action( 'customize_controls_enqueue_scripts', [ __CLASS__, 'add_lang_to_customizer_previewer' ], 9 );
		add_action( 'wp_before_admin_bar_render', [ __CLASS__, 'on_wp_before_admin_bar_render' ], 100 );
		add_action( 'admin_menu', [ __CLASS__, 'on_admin_menu' ], 100 );
		add_action( 'after_setup_theme', [ __CLASS__, 'remove_filters' ], 5 );

		$theme_stylesheet_slug = get_option( 'stylesheet' );
		$option_types          = [ 'blogname', 'blogdescription', 'site_icon' ];

		if ( ! ( defined('DOING_AJAX') && DOING_AJAX && isset( $_POST['action'] ) && in_array( $_POST['action'], [ 'jupiterx_cp_cleanup_mods', 'abb_install_template_procedure' ], true ) ) ) {
			// Get theme mod options.
			add_filter( 'option_theme_mods_' . $theme_stylesheet_slug, [ $self, 'on_option_theme_mods_get' ], 10, 1 );
			// Update theme mod options.
			add_filter( 'pre_update_option_theme_mods_' . $theme_stylesheet_slug, [ $self, 'on_option_theme_mods_update' ], 10, 2 );
		}

		foreach ( $option_types as $option_type ) {
			add_filter( 'pre_option_' . $option_type, [ $self, 'on_wp_option_get' ], 10, 3 ); // get_option hook.
			add_filter( 'pre_update_option_' . $option_type, [ $self, 'on_wp_option_update' ], 10, 3 ); // update_option hook.
		}

		return $self;
	}

	/**
	 * Remove bloginfo update filters. As we save options per language in this class, we don't need WPML functionality.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function remove_filters() {
		global $WPML_String_Translation; // @phpcs:ignore
		remove_filter( 'pre_update_option_blogname', [ $WPML_String_Translation, 'pre_update_option_blogname' ], 5 ); // @phpcs:ignore
		remove_filter( 'pre_update_option_blogdescription', [ $WPML_String_Translation, 'pre_update_option_blogdescription' ], 5 ); // @phpcs:ignore
	}

	/**
	 * Get current language.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool $language|false Current language or false when none of Polylang & WPML are active.
	 */
	public static function get_language() {
		if ( function_exists( 'pll_current_language' ) ) {
			$language = pll_current_language();

			if ( ! $language ) {
				$language = pll_default_language();
			}

			return $language;
		}

		if ( class_exists( 'SitePress' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			return ICL_LANGUAGE_CODE;
		}

		return false;
	}

	/**
	 * Get a list of active languages with extra parameters like name and slug.
	 *
	 * @since 1.0.0
	 *
	 * @return array|bool $languages|false List of active languages or false when none of Polylang & WPML are active.
	 */
	public static function get_languages_list() {
		if ( function_exists( 'pll_current_language' ) ) {
			return get_option( '_transient_pll_languages_list' );
		}

		if ( class_exists( 'SitePress' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			$list      = icl_get_languages( 'skip_missing=1' );
			$languages = [];

			foreach ( $list as $language ) {
				$temp         = [];
				$temp['name'] = $language['native_name'];
				$temp['slug'] = $language['code'];
				$languages[]  = $temp;
			}

			return $languages;
		}

		return false;
	}

	/**
	 * Get a proper option key per plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool Option key or false when none of Polylang & WPML are active.
	 */
	public static function get_option_key() {
		if ( function_exists( 'pll_current_language' ) ) {
			return '_customizer_polylang_settings_';
		}

		if ( class_exists( 'SitePress' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			return '_customizer_wpml_settings_';
		}

		return false;
	}

	/**
	 * Get home URL of current language.
	 *
	 * @param string $language current language.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool Home URL of current language or false when none of Polylang & WPML are active.
	 */
	public static function get_home_url( $language ) {
		if ( function_exists( 'pll_current_language' ) ) {
			return pll_home_url( $language );
		}

		if ( class_exists( 'SitePress' ) ) {
			global $sitepress;
			return $sitepress->language_url( $language );
		}

		return false;
	}

	/**
	 * Helper to fetch custom customizer db content.
	 *
	 * @since 1.3.0
	 *
	 * @return mixed Customizer array or false.
	 */
	protected function get_custom_customizer_option() {
		$current_language = self::get_language();
		$theme_slug       = get_option( 'template' );
		$option_prefix    = str_replace( '-', '_', $theme_slug );
		$option_name      = $option_prefix . self::get_option_key() . $current_language;

		return get_option( $option_name, false );
	}

	/**
	 * Helper to update custom customizer db content.
	 *
	 * @since 1.3.0
	 *
	 * @param mixed $data Data to insert.
	 *
	 * @return bool Success.
	 */
	protected function update_custom_customizer_option( $data ) {
		$current_language = self::get_language();
		$theme_slug       = get_option( 'template' );
		$option_prefix    = str_replace( '-', '_', $theme_slug );
		$option_name      = $option_prefix . self::get_option_key() . $current_language;

		return update_option( $option_name, $data );
	}

	/**
	 * Helper
	 *
	 * @since 1.3.0
	 *
	 * @return bool If the current language is the default language.
	 */
	protected function current_lang_not_default() {
		if ( class_exists( 'SitePress' ) ) {
			global $sitepress;
			return $sitepress->get_current_language() !== $sitepress->get_default_language();
		}

		return pll_current_language() !== pll_default_language();
	}

	/**
	 * Check the custom db field on get_option hook to be able to return custom language value.
	 * If the current language is default, then return from default wp option
	 *
	 * @since 1.3.0
	 *
	 * @param bool   $pre_option This is false. If something else is returned wp exits the check in db and uses this value.
	 * @param string $option Option name asked for.
	 * @param mixed  $default Default value, second args when asking for options.
	 *
	 * @return mixed
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function on_wp_option_get( $pre_option, $option, $default ) {

		// If not the default language, then skip the custom check and wp will the use default options.
		if ( $this->current_lang_not_default() ) {
			$data = $this->get_custom_customizer_option();

			// Found the custom option. Move on.
			if ( is_array( $data ) && isset( $data['options'] ) && isset( $data['options'][ $option ] ) ) {
				return $data['options'][ $option ];
			}
		}

		return $default;
	}

	/**
	 * Update the custom db field on get_option hook.
	 * If the current language is not default, then return old value to prevent from saving to default wp option.
	 *
	 * @since 1.3.0
	 *
	 * @param mixed  $value The new, unserialized option value.
	 * @param mixed  $old_value The old option value.
	 * @param string $option Option name.
	 *
	 * @return mixed
	 */
	public function on_wp_option_update( $value, $old_value, $option ) {
		// Fetch custom option db field.
		$data       = $this->get_custom_customizer_option();
		$theme_slug = get_option( 'template' );
		// If false, the field hasn't been created yet, so it must be created.
		if ( false === $data ) {
			$data = [
				'template' => $theme_slug,
				'mods'     => [],
				'options'  => [],
			];
		}

		// Make sure the options array exists. We are going to use it soon.
		if ( ! isset( $data['options'] ) ) {
			$data['options'] = [];
		}

		$data['options'][ $option ] = $value;

		// Update option value in custom db field. (Not necessary to save for default language since it uses default wp option fields for values when get option).
		$this->update_custom_customizer_option( $data );

		// If the current language is not the default language, prevent saving to option table by passing the old value back. It will then exit after the filter.
		if ( $this->current_lang_not_default() ) {
			return $old_value;
		}

		return $value;
	}

	/**
	 * Check the custom db field on get_option customizer field option name hook to be able to return custom language value.
	 * Parse arguments with default wp customizer values to make sure all are present in the return.
	 *
	 * @since 1.3.0
	 *
	 * @param array $value The customizer settings.
	 *
	 * @return array
	 */
	public function on_option_theme_mods_get( $value ) {
		$data = $this->get_custom_customizer_option();

		if ( isset( $data['mods'] ) && is_array( $data['mods'] ) && ! empty( $data['mods'] ) ) {
			$value = wp_parse_args( $data['mods'], $value );
		}

		return $value;
	}

	/**
	 * Update custom customizer option.
	 * If the current language is not default, then return old value to prevent from saving to customizer wp option.
	 *
	 * @since 1.3.0
	 *
	 * @param mixed $value The new, unserialized option value.
	 * @param mixed $old_value The old option value.
	 */
	public function on_option_theme_mods_update( $value, $old_value ) {

		$current_data = $this->get_custom_customizer_option();
		$theme_slug   = get_option( 'template' );

		$data = [
			'template' => $theme_slug,
			'mods'     => isset( $current_data['mods'] ) ? $current_data['mods'] : [],
			'options'  => isset( $current_data['options'] ) ? $current_data['options'] : [],
		];

		if ( is_array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $key => $val ) {
				$data['mods'][ $key ] = $val;
			}
		}
		$this->update_custom_customizer_option( $data );

		if ( $this->current_lang_not_default() ) {
			return $old_value;
		}

		return $value;
	}

	/**
	 * If Polylang activated, set the preview url and add select language control
	 *
	 * @author soderlind
	 * @version 1.0.0
	 * @link https://gist.github.com/soderlind/1908634f5eb0c1f69428666dd2a291d0
	 *
	 * @since 1.0.0
	 */
	public static function add_lang_to_customizer_previewer() {
		$languages = self::get_languages_list();

		if ( ! $languages || jupiterx_get( 'kt-woomail-customize' ) ) {
			return;
		}

		$handle      = 'dss-add-lang-to-template';
		$js_path_url = trailingslashit( apply_filters( 'scp_js_path_url', get_stylesheet_directory_uri() . '/js/' ) );
		$src         = $js_path_url . 'customizer-multilingual.js';
		$deps        = [ 'customize-controls' ];
		wp_enqueue_script( $handle, $src, $deps, JUPITERX_VERSION, true );
		$language = ( empty( $_REQUEST['lang'] ) ) ? self::get_language() : $_REQUEST['lang']; // @phpcs:ignore

		if ( empty( $language ) ) {
			$language = self::default_language();
		}

		$url = add_query_arg( 'lang', $language, self::get_home_url( $language ) );

		wp_add_inline_script(
			$handle,
			sprintf(
				'JupiterXCustomizerMultilingual.init( %s );', wp_json_encode(
					[
						'url'              => $url,
						'languages'        => $languages,
						'current_language' => $language,
						'switcher_text'    => __( 'Language:', 'jupiterx-core' ),
					]
				)
			), 'after'
		);
	}

	/**
	 * Append lang="contrycode" to the customizer url in the adminbar
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function on_wp_before_admin_bar_render() {
		global $wp_admin_bar;
		$customize_node = $wp_admin_bar->get_node( 'customize' );
		if ( ! empty( $customize_node ) ) {
			$customize_node->href = add_query_arg( 'lang', self::get_language(), $customize_node->href );
			$wp_admin_bar->add_node( $customize_node );
		}
	}

	/**
	 * Append lang="contrycode" to the customizer url in the Admin->Apperance->Customize menu
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function on_admin_menu() {
		global $menu, $submenu;
		$parent = 'themes.php';
		if ( ! isset( $submenu[ $parent ] ) ) {
			return;
		}
		foreach ( $submenu[ $parent ] as $k => $d ) {
			if ( 'customize' === $d['1'] ) {
				$submenu[ $parent ][ $k ]['2'] = add_query_arg( 'lang', self::get_language(), $submenu[ $parent ][ $k ]['2'] ); // @phpcs:ignore
				break;
			}
		}
	}

}

CoreCustomizerMultilingual::init();

if ( class_exists( 'WP_Customize_Setting' ) ) {
	/**
	 * A class that extends WP_Customize_Setting so we can access
	 * the protected updated method when importing options.
	 *
	 * @since 0.3
	 */
	final class Customizermultilingialoption extends WP_Customize_Setting { // @phpcs:ignore


		/**
		 * Import an option value for this setting.
		 *
		 * @since 0.3
		 *
		 * @param mixed $value The option value.
		 *
		 * @return void
		 */
		public function import( $value ) {
			$this->update( $value );
		}
	}
}
