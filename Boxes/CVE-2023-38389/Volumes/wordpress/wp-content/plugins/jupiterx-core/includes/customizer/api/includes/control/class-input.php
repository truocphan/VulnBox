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
 * Input control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Input extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-input';

	/**
	 * Control's unit.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $units = [
		'-',
		'px',
		'%',
		'vh',
		'vw',
		'em',
		'rem',
	];

	/**
	 * Control's default unit.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $default_unit = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['units']       = $this->units;
		$this->json['defaultUnit'] = $this->default_unit;
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
		value = _.isObject( data.value ) ? data.value : {}
		units = ! _.isEmpty( data.units ) ? data.units : [ '-' ]
		hasText = ! _.isUndefined( data.text ) && ! _.isEmpty( data.text )
		hasIcon = ! _.isUndefined( data.icon ) && ! _.isEmpty( data.icon )
		hasUnits = ! _.isUndefined( data.units ) && ! _.isEmpty( data.units )
		controlClass = 'jupiterx-control ' + data.type + '-control'
		controlClass += ( hasIcon || hasText || hasUnits ) ? ' jupiterx-input-group' : ''
		controlClass += ( hasIcon ) ? ' has-icon' : ''
		controlClass += ( hasText ) ? ' has-text' : ''
		controlClass += ( hasUnits ) ? ' has-units' : ''
		#>
		<div class="{{ controlClass }}" {{{ data.controlAttrs }}}>
			<?php
			$this->group_prefix_template();
			$this->group_field_template();
			$this->group_units_template();
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
		size = value.size || ''
		defaultUnit = data.defaultUnit ? data.defaultUnit : _.first( units )
		unitValue = value.unit ? value.unit : defaultUnit
		step = 'px' == unitValue ? 1 : .1
		inputAttrs = data.inputAttrs

		if ( '%' === unitValue ) {
			dataInputAttrs = data.inputAttrs ? data.inputAttrs : ''
			dataInputAttrsArray = []

			dataInputAttrs = dataInputAttrs.split('"')

			dataInputAttrs.forEach( function get_attr( element, index ) {
				if ( element === 'min=' ) {
					dataInputAttrsArray[ index + 1 ] = "min=0"
				}

				if ( element === ' max=' ) {
					dataInputAttrsArray[ index + 1 ] = "max=100"
				}

				if ( element !== ' max=' && element !== 'min='  && element.includes( '=' ) ) {
					dataInputAttrsArray[ index ] =  dataInputAttrs[ index ] + dataInputAttrs[ index + 1 ]
				}
			} )

			dataInputAttrsArrayFiltered = dataInputAttrsArray.filter( function( element ) {
				return element != null;
			});

			inputAttrs = dataInputAttrsArrayFiltered
		}
		#>
		<div class="jupiterx-control-input-wrapper">
			<input id="{{ data.id }}_range" class="jupiterx-input-control-input-range" {{{ inputAttrs }}} type="range" value="{{ size }}" step="{{ step }}" {{{ data.link }}} data-setting-property-link="size">
			<input id="{{ data.id }}" class="jupiterx-input-control-input" {{{ data.inputAttrs }}} type="number" value="{{ size }}" step="{{ step }}" {{{ data.link }}} data-setting-property-link="size" />
		</div>
		<?php
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_units_template() {
		?>
		<#
		defaultUnit = data.defaultUnit ? data.defaultUnit : _.first( units )
		unitValue = value.unit ? value.unit : defaultUnit
		selectorClass = 1 === _.size( units ) ? 'disabled' : ''
		#>
		<div class="jupiterx-control-units-container">
			<input type="hidden" value="{{ unitValue }}" {{{ data.link }}} data-setting-property-link="unit" />
			<ul class="jupiterx-control-unit-selector" data-inputs=".jupiterx-input-control-input">
				<# _.each( units, function ( unit ) { #>
					<li class="jupiterx-control-unit {{ unit === unitValue ? 'selected-unit' : '' }}">{{ unit }}</li>
				<# } ) #>
			</ul>
		</div>
		<?php
	}

	/**
	 * Format CSS value from theme mod array value.
	 *
	 * @since 1.0.0
	 *
	 * @return array Empty properties
	 */
	public static function format_properties() {
		return [];
	}

	/**
	 * Format theme mod array value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 *
	 * @return string The formatted value.
	 */
	public static function format_value( $value ) {
		if ( ! isset( $value['size'] ) || '' === $value['size'] || ! isset( $value['unit'] ) ) {
			return '';
		}

		$unit = '-' !== $value['unit'] ? $value['unit'] : '';

		$css_value = $value['size'] . $unit;

		return $css_value;
	}
}
