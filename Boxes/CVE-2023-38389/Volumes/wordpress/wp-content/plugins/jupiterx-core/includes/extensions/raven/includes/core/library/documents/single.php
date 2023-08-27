<?php
/**
 * Add Library Single Document.
 *
 * @package JupiterX_Core\Raven
 * @since 1.5.0
 */

namespace JupiterX_Core\Raven\Core\Library\Documents;

defined( 'ABSPATH' ) || die();

use Elementor\Modules\Library\Documents\Page;

/**
 * Raven single library document.
 *
 * Raven single library document handler class is responsible for
 * handling a document of a single type.
 *
 * @since 1.5.0
 */
class Single extends Page {

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @since 1.5.0
	 * @access public
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['library_view'] = 'list';
		$properties['group']        = 'blocks';

		return $properties;
	}

	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'single';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 1.5.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return __( 'Single', 'jupiterx-core' );
	}
}
