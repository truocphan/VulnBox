<?php
/**
 * Handles select control class.
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
 * Font control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Font extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-font';

	/**
	 * Control's placeholder.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $placeholder = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['placeholder'] = $this->placeholder;
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {
		?>
		<select class="jupiterx-font-control-field jupiterx-select-field" {{{ data.inputAttrs }}} value="{{ data.value }}" id="{{ data.id }}" {{{ data.link }}}>
			<# if ( ! _.isEmpty( data.placeholder ) ) { #>
				<option value="" <# if ( _.isEmpty( data.value ) ) { #> selected<# } #>>{{{ data.placeholder }}}</option>
			<# } #>
			<# _.each( wp.customize.JupiterX.fonts.stack, function( fonts, type ) { #>
				<optgroup label="{{ type }}">
					<# _.each( fonts, function( font ) { #>
						<# value = font.value || font.name #>
						<# selected = ( data.value === value ) #>
						<option data-type="{{ font.type }}" value="{{ value }}" title="{{ font.name }}" <# if ( selected ) { #> selected<# } #>>{{{ font.name }}}</option>
					<# } ); #>
				</optgroup>
			<# } ); #>
		</select>
		<?php
	}
}
