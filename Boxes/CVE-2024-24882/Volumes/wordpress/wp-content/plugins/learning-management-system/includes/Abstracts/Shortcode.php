<?php
/**
 * Shortcode abstract class.
 *
 * @since 1.0.0
 * @class Shortcode
 * @package Masteriyo\Abstracts
 */

namespace Masteriyo\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode abstract class.
 */
abstract class Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $tag = '';

	/**
	 * Shortcode attributes.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Shortcode default attributes.
	 *
	 * @since 1.0.6
	 *
	 * @var array
	 */
	protected $default_attributes = array();

	/**
	 * Arguments to pass to the template.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $template_args = array();

	/**
	 * Get shortcode attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Set shortcode attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return $this
	 */
	public function set_attributes( $attributes ) {
		$this->attributes = $this->parse_attributes( $attributes );
	}

	/**
	 * Set template args.
	 *
	 * @since 1.0.0
	 *
	 * @return
	 */
	public function set_template_args( $template_args ) {
		$this->attributes = (array) $template_args;
	}

	/**
	 * Get template args.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_template_args() {
		return $this->template_args;
	}

	/**
	 * Get shortcode tag.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_tag() {
		return $this->tag;
	}

	/**
	 * Parse shortcode attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attributes Shortcode attributes.
	 *
	 * @return array
	 */
	protected function parse_attributes( $attributes ) {
		return shortcode_atts(
			$this->default_attributes,
			$attributes,
			$this->get_tag()
		);
	}

	/**
	 * Register this shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_shortcode( $this->get_tag(), array( $this, 'shortcode_callback' ) );
	}

	/**
	 * Shortcode callback.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function shortcode_callback( $attributes = array() ) {
		$this->set_attributes( $attributes );
		return $this->get_content();
	}

	/**
	 * Get shortcode content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_content();

	/**
	 * Get rendered html after injecting the data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Data to inject.
	 * @param string $file_path Path of the php file containing HTML.
	 *
	 * @return string
	 */
	protected function get_rendered_html( $data, $file_path ) {
		ob_start();
		extract( $data ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		include $file_path;
		return ob_get_clean();
	}

	/**
	 * Parse and clean multiple values separated by a delimiter in a string.
	 *
	 * @since 1.6.12
	 *
	 * @param string $value The string containing multiple values.
	 * @param string $delimiter The delimiter used to separate the values. Default delimiter is comma.
	 * @param callable|null $sanitization_func The sanitization function to apply to each value. Default is 'null'. Can be a callable function or null.
	 *
	 * @return array An array containing the parsed and cleaned values. Returns an empty array if the input value is empty.
	 */
	protected function parse_values_attribute( $value, $delimiter = ',', $sanitization_func = null ) {
		if ( empty( $value ) ) {
			return array();
		}

		$values = explode( $delimiter, $value );

		if ( is_callable( $sanitization_func ) ) {
			$values = array_map( $sanitization_func, $values );
		}

		return array_filter( $values );
	}
}
