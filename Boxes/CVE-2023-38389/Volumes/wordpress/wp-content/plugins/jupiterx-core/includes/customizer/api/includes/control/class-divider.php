<?php
/**
 * Handles divider control class.
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
 * Divider control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Divider extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-divider';

	/**
	 * Divider type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $divider_type = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['dividerType'] = $this->divider_type;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<# type = data.dividerType ? 'jupiterx-divider-control-' + data.dividerType : '' #>
		<div class="jupiterx-control jupiterx-divider-control {{ type }}" {{{ data.controlAttrs }}}></div>
		<?php
	}
}
