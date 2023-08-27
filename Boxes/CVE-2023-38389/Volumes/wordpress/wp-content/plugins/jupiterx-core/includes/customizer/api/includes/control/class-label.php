<?php
/**
 * Handles label control class.
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
 * Label control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Label extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-label';

	/**
	 * Control's label type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $label_type = '';

	/**
	 * Control's label color.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $color = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['labelType'] = $this->label_type;
		$this->json['color']     = $this->color;
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
		<#
		type = data.labelType ? 'jupiterx-label-control-' + data.labelType : ''
		color = data.color ? 'jupiterx-label-control-' + data.color : ''
		#>
		<div class="jupiterx-control jupiterx-label-control {{ type }} {{ color }}">
			<span class="jupiterx-label-control-text">{{ data.label }}</span>
		</div>
		<?php
	}
}
