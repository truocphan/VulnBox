<?php
/**
 * Handles input control class.
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
 * Text input control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Text extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-text';

	/**
	 * Control's text input type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $input_type = 'text';

	/**
	 * Control's input group unit.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $unit = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$input_types = [ 'text', 'url', 'number', 'email' ];

		// Revert to text if acceptable type is not found.
		if ( ! in_array( $this->input_type, $input_types, true ) ) {
			$this->input_type = 'text';
		}

		$this->json['inputType'] = $this->input_type;
		$this->json['unit']      = $this->unit;
	}

	/**
	 * An Underscore (JS) template for control wrapper.
	 *
	 * @since 1.0.0
	 */
	protected function control_template() {
		?>
		<#
		hasText = ! _.isUndefined( data.text ) && ! _.isEmpty( data.text )
		hasIcon = ! _.isUndefined( data.icon ) && ! _.isEmpty( data.icon )
		hasUnit = ! _.isUndefined( data.unit ) && ! _.isEmpty( data.unit )
		controlClass = 'jupiterx-control jupiterx-input-control ' + data.type + '-control'
		controlClass += ( hasIcon || hasText || hasUnit ) ? ' jupiterx-input-group' : ''
		controlClass += ( hasIcon ) ? ' has-icon' : ''
		controlClass += ( hasText ) ? ' has-text' : ''
		controlClass += ( hasUnit ) ? ' has-unit' : ''
		controlClass += ( data.inputType === 'number' ) ? ' jupiterx-input-control-number' : ''
		#>
		<div class="{{ controlClass }}" {{{ data.controlAttrs }}}>
			<?php
			$this->group_prefix_template();
			$this->group_field_template();
			$this->group_suffix_template();
			?>
		</div>
		<?php
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {
		?>
		<#
			unitValue = data.value ? data.value  : 0
		#>
		<# if ( data.inputType === 'number' ) { #>
			<input id="{{ data.id }}_range" class="jupiterx-input-control-field-range" {{{ data.inputAttrs }}} type="range" value="{{ unitValue }}" step="1" {{{ data.link }}}>
		<# } #>
		<input class="jupiterx-input-control-field" {{{ data.inputAttrs }}} type="{{ data.inputType }}" step="1" id="{{ data.id }}" value="{{ unitValue }}" {{{ data.link }}} />
		<?php
	}

	/**
	 * An Underscore (JS) template for field suffix.
	 *
	 * @since 1.0.0
	 */
	public function group_suffix_template() {
		?>
		<# if ( hasUnit ) { #>
			<span class="jupiterx-input-group-unit">{{ data.unit }}</span>
		<# } #>
		<?php
	}

	/**
	 * Format theme mod array value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 * @param array $args The field's arguments.
	 *
	 * @return string The formatted value.
	 */
	public static function format_value( $value, $args ) {
		if ( isset( $args['unit'] ) ) {
			$value .= $args['unit'];
		}

		return $value;
	}
}
