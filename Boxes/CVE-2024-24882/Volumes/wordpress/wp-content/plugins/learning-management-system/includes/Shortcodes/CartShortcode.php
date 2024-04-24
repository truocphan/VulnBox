<?php
/**
 * Cart page shortcode.
 *
 * @since 1.0.0
 * @class CartShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Cart page shortcode.
 */
class CartShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_cart';

	/**
	 * Get shortcode content.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		/**
		 * Prepare Template.
		 */
		$template_path = masteriyo( 'template' )->locate( 'cart.php' );

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}
}
