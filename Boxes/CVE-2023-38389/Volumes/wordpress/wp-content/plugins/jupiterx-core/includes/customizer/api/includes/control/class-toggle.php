<?php
/**
 * Handles toggle control class.
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
 * Toggle control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Toggle extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-toggle';

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {
		?>
		<label class="jupiterx-toggle-control-label">
			<input class="jupiterx-toggle-control-checkbox screen-reader-text" {{{ data.inputAttrs }}} type="checkbox" id="{{ data.id }}" value="{{ data.value }}" <# if ( data.value ) { #> checked <# } #> hidden {{{ data.link }}} />
			<span class="jupiterx-toggle-control-switch">
				<span class="jupiterx-toggle-control-handler"></span>
			</span>
		</label>
		<?php
	}
}
