<?php
/**
 * Add Library Footer Document.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Core\Library\Documents;

defined( 'ABSPATH' ) || die();

/**
 * Raven footer library document.
 *
 * Raven footer library document handler class is responsible for
 * handling a document of a footer type.
 *
 * @since 1.0.0
 */
class Footer extends Library_Document {

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'footer';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return __( 'Footer', 'jupiterx-core' );
	}
}
