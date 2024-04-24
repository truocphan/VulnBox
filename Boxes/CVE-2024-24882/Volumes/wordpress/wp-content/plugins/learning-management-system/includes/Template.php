<?php
/**
 * Template functions wrapper class.
 *
 * @package Masteriyo
 *
 * @since 1.0.0
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Utils;
use Masteriyo\Constants;
use Masteriyo\Contracts\Template as TemplateInterface;

/**
 * Template functions wrapper class.
 */
class Template implements TemplateInterface {
	/**
	 * Get template part.
	 *
	 * MASTERIYO_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed  $slug Template slug.
	 * @param string $name Template name (default: '').
	 */
	public function get_part( $slug, $name = '' ) {
		$cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name, Constants::get( 'MASTERIYO_VERSION' ) ) ) );
		$template  = $this->get_cache( $cache_key );

		if ( ! $template ) {
			if ( $name ) {
				$template = Constants::get( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ? '' : locate_template(
					array(
						"{$slug}-{$name}.php",
						Utils::template_path() . "{$slug}-{$name}.php",
					)
				);

				if ( ! $template ) {
					$fallback = Constants::get( 'MASTERIYO_PLUGIN_DIR' ) . "/templates/{$slug}-{$name}.php";
					$template = file_exists( $fallback ) ? $fallback : '';
				}
			}

			if ( ! $template ) {
				// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/masteriyo/slug.php.
				$template = Constants::get( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ? '' : locate_template(
					array(
						"{$slug}.php",
						Utils::template_path() . "{$slug}.php",
					)
				);
			}

			// Don't cache the absolute path so that it can be shared between web servers with different paths.
			$tokenized_template_path = Utils::tokenize_path( $template, Utils::get_path_define_tokens() );

			$this->set_cache( $cache_key, $tokenized_template_path );
		} else {
			// Make sure that the absolute path to the template is resolved.
			$template = Utils::untokenize_path( $template, Utils::get_path_define_tokens() );
		}

		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template Template part filename.
		 * @param string $slug Template slug.
		 * @param string $name Template name from function parameter.
		 */
		$template = apply_filters( 'masteriyo_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}

	/**
	 * Get other templates and include the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 */
	public function get( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		$cache_key = sanitize_key( implode( '-', array( 'template', $template_name, $template_path, $default_path, Constants::get( 'MASTERIYO_VERSION' ) ) ) );
		$template  = $this->get_cache( $cache_key );

		if ( ! $template ) {
			$template = $this->locate( $template_name, $template_path, $default_path );

			// Don't cache the absolute path so that it can be shared between web servers with different paths.
			$tokenized_template_path = Utils::tokenize_path( $template, Utils::get_path_define_tokens() );

			$this->set_cache( $cache_key, $tokenized_template_path );
		} else {
			// Make sure that the absolute path to the template is resolved.
			$template = Utils::untokenize_path( $template, Utils::get_path_define_tokens() );
		}

		/**
		 * Allow 3rd party plugin filter template file from their plugin.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template Template path.
		 * @param string $template_name Template name.
		 * @param array $args Template arguments.
		 * @param string $template_path Template path from function parameter.
		 * @param string $default_path Default templates directory path.
		 */
		$filter_template = apply_filters( 'masteriyo_get_template', $template, $template_name, $args, $template_path, $default_path );

		if ( $filter_template !== $template ) {
			if ( ! file_exists( $filter_template ) ) {
				/* translators: %s template */
				Utils::doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'masteriyo' ), '<code>' . $filter_template . '</code>' ), '1.0.0' );
				return;
			}
			$template = $filter_template;
		}

		$action_args = array(
			'template_name' => $template_name,
			'template_path' => $template_path,
			'located'       => $template,
			'args'          => $args,
		);

		if ( ! empty( $args ) && is_array( $args ) ) {
			if ( isset( $args['action_args'] ) ) {
				Utils::doing_it_wrong(
					__FUNCTION__,
					__( 'action_args should not be overwritten when calling get_template.', 'masteriyo' ),
					'1.0.0'
				);
				unset( $args['action_args'] );
			}
			extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		}

		/**
		 * Fires before rendering template part.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template_name Template name.
		 * @param string $template_path Template path.
		 * @param string $located Absolute path of the template.
		 * @param array $args Template part arguments.
		 */
		do_action( 'masteriyo_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

		include $action_args['located'];

		/**
		 * Fires after rendering template part.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template_name Template name.
		 * @param string $template_path Template path.
		 * @param string $located Absolute path of the template.
		 * @param array $args Template part arguments.
		 */
		do_action( 'masteriyo_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
	}

	/**
	 * Like get_template, but returns the HTML instead of outputting.
	 *
	 * @since 1.0.0
	 *
	 * @see get_template
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public function get_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		ob_start();
		$this->get( $template_name, $args, $template_path, $default_path );
		return ob_get_clean();
	}

	/**
	 * Add a template to the template cache.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cache_key Object cache key.
	 * @param string $template Located template.
	 */
	public function set_cache( $cache_key, $template ) {
		$cache = masteriyo( 'cache' );

		$cache->set( $cache_key, $template, 'masteriyo' );

		$cached_templates = $cache->get( 'cached_templates', 'masteriyo' );

		if ( is_array( $cached_templates ) ) {
			$cached_templates[] = $cache_key;
		} else {
			$cached_templates = array( $cache_key );
		}

		$cache->set( 'cached_templates', $cached_templates, 'masteriyo' );
	}

	/**
	 * Get template cache.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cache_key Object cache key.
	 *
	 * @return string
	 */
	public function get_cache( $cache_key ) {
		$cache = masteriyo( 'cache' );

		return (string) $cache->get( $cache_key, 'masteriyo' );
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public function locate( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = Utils::template_path();
		}

		if ( ! $default_path ) {
			$default_path = Constants::get( 'MASTERIYO_PLUGIN_DIR' ) . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template || Constants::get( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Filters the located template path.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template Template path.
		 * @param string $template_name Template name.
		 * @param string $template_path Template relative path.
		 */
		return apply_filters( 'masteriyo_locate_template', $template, $template_name, $template_path );
	}
}
