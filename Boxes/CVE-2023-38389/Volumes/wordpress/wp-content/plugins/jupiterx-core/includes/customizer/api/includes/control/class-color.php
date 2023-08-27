<?php
/**
 * Handles color control class.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Color control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Color extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-color';

	/**
	 * Show opacity option.
	 *
	 * @since 1.0.4
	 *
	 * @var boolean
	 */
	public $opacity = true;

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		// Use RGBA.
		$this->json['opacity'] = $this->opacity;
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {
		?>
		<input class="jupiterx-color-control-field" {{{ data.inputAttrs }}} type="text" id="{{ data.id }}" value="{{ data.value }}" {{{ data.link }}} />
		<?php
	}
}
