<?php
/**
 * Template handler class interface.
 *
 * @since 1.0.0
 *
 * @package Masteriyo
 */

namespace Masteriyo\Contracts;

defined( 'ABSPATH' ) || exit;

interface Template {
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
	public function get_part( $slug, $name = '' );

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
	public function get( $template_name, $args = array(), $template_path = '', $default_path = '' );

	/**
	 * Like get_template, but returns the HTML instead of outputting.
	 *
	 * @since 1.0.0
	 *
	 * @see get_template
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public function get_html( $template_name, $args = array(), $template_path = '', $default_path = '' );

	/**
	 * Add a template to the template cache.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cache_key Object cache key.
	 * @param string $template Located template.
	 */
	public function set_cache( $cache_key, $template );

	/**
	 * Get template cache.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cache_key Object cache key.
	 *
	 * @return string
	 */
	public function get_cache( $cache_key );

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
	public function locate( $template_name, $template_path = '', $default_path = '' );
}
