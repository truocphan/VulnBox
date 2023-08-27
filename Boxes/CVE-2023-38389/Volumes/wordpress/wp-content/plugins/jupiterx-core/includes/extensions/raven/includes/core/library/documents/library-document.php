<?php
/**
 * Add Library Document Base.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Core\Library\Documents;

use Elementor\Modules\Library\Documents\Library_Document as Elementor_Library_Document;

defined( 'ABSPATH' ) || die();

/**
 * Raven library document.
 *
 * Raven library document handler class is responsible for handling
 * a document of the library type.
 *
 * @since 1.0.0
 */
abstract class Library_Document extends Elementor_Library_Document {

	/**
	 * Get document edit url.
	 *
	 * Retrieve the document edit url.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_edit_url() {
		$url = parent::get_edit_url();

		if ( isset( $_GET['action'] ) && 'elementor_new_post' === $_GET['action'] ) { // phpcs:ignore
			$url .= '#library';
		}

		return $url;
	}
}
