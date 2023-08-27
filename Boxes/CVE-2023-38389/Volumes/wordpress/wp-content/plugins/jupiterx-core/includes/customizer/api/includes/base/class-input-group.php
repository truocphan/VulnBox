<?php
/**
 * Base class for control with input group icon, text and unit.
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
 * Base input group class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Base_Input_Group extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's input group text.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $text = '';

	/**
	 * Control's input group icon.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $icon = '';

	/**
	 * Controls icon alt.
	 *
	 * @since 1.20.0
	 *
	 * @var string
	 */
	public $alt = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['text'] = $this->text;
		$this->json['icon'] = $this->icon;
		$this->json['alt']  = $this->alt;
	}

	/**
	 * An Underscore (JS) template for control wrapper.
	 *
	 * Use to create the control template.
	 *
	 * @since 1.0.0
	 */
	protected function control_template() {
		?>
		<#
		hasText = ! _.isUndefined( data.text ) && ! _.isEmpty( data.text )
		hasIcon = ! _.isUndefined( data.icon ) && ! _.isEmpty( data.icon )
		controlClass = 'jupiterx-control ' + data.type + '-control'
		controlClass += ( hasIcon || hasText ) ? ' jupiterx-input-group' : ''
		controlClass += ( data.inline ) ? ' jupiterx-choose-inline' : ''
		controlClass += ( hasIcon ) ? ' has-icon' : ''
		controlClass += ( hasText ) ? ' has-text' : ''
		#>
		<div class="{{ controlClass }}" {{{ data.controlAttrs }}}>
			<?php
			$this->group_prefix_template();
			$this->group_field_template();
			?>
		</div>
		<?php
	}

	/**
	 * An Underscore (JS) template for field prefix.
	 *
	 * @since 1.0.0
	 */
	protected function group_prefix_template() {
		?>
		<# if ( hasText ) { #>
			<span class="jupiterx-input-group-text">{{ data.text }}</span>
		<# } #>
		<?php
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {}
}
