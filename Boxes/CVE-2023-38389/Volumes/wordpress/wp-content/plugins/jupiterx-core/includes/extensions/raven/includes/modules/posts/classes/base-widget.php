<?php
/**
 * The module widgets current Base Widget class.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Posts\Classes;

defined( 'ABSPATH' ) || die();

/**
 * Base Widget.
 *
 * An abstract class that shares function for each widgets.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Base_Widget extends \JupiterX_Core\Raven\Base\Base_Widget {

	/**
	 * Whether the widget has content.
	 *
	 * Used in cases where the widget has no content. When widgets uses only
	 * skins to display dynamic content generated on the server. For example the
	 * posts widget in Elemenrot Pro. Default is true, the widget has content
	 * template.
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * Holds the widget instance query.
	 *
	 * @access public
	 *
	 * @var object
	 */
	public $query;
}
